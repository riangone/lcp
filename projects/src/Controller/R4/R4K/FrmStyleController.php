<?php
namespace App\Controller\R4\R4K;

/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150827           #2090						   BUG                              li
 * --------------------------------------------------------------------------------------------
 */
use App\Controller\AppController;
use App\Model\R4\R4K\FrmStyle;

class FrmStyleController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;

    private $FrmStyle;
    private $result;
    private $Do_conn;
    private $blnTranFlg;
    // public $ClsComFnc = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        $this->render('index', 'FrmStyle_layout');
    }

    /*
    ***********************************************************************
    '処 理 名：データグリッドの再表示
    '関 数 名：fncFrmStyleSelect
    '引    数：無し
    '戻 り 値：無し
    '処理説明：データグリッドを再表示する
    '**********************************************************************
    */
    public function fncFrmStyleSelect()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmStyle = new FrmStyle();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmStyle->fncFrmStyleSelect();

            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);

            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            for ($ii = 0; $ii < count((array) $this->result['data']); $ii++) {
                foreach ((array) $this->result['data'][$ii] as $key => $value) {
                    $this->result['data'][$ii][$key] = trim($this->ClsComFnc->fncNv($value));
                }
            }
            $this->result = $this->FrmStyle->fncFrmStyleSelect();
            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($this->result["data"], $totalPage, $page, $tmpCount);

            $this->result = $tmpJqgrid;
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }

        $this->fncReturn($this->result);
    }
    public function fncUpdate()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmStyle = new FrmStyle();

            $this->Do_conn = $this->FrmStyle->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmStyle->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;

            $this->result = $this->FrmStyle->fncDelete();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            foreach ($_POST['data'] as $value) {
                if ($value['STYLE_ID'] != "") {
                    $this->result = $this->FrmStyle->fncInsert($value);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }

            $this->result['data'] = "success";
            //コミット
            $this->FrmStyle->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = FALSE;
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmStyle->Do_rollback();
        }
        //DB接続解除
        $this->FrmStyle->Do_close();

        $this->fncReturn($this->result);
    }

    public function fncDelete()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmStyle = new FrmStyle();
            //---20150827 li INS S.
            $this->Do_conn = $this->FrmStyle->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //---20150827 li INS E.
            //トランザクション開始
            $this->FrmStyle->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = TRUE;
            //所属ﾏｽﾀ削除処理
            $this->result = $this->FrmStyle->fncSingleDel($_POST['data']['STYLE_ID']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //ﾊﾟﾀｰﾝﾏｽﾀ削除処理
            $this->result = $this->FrmStyle->fncPatternDel($_POST['data']['STYLE_ID']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //パターン名マスタ削除
            $this->result = $this->FrmStyle->fncNmPatternDel($_POST['data']['STYLE_ID']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //階層ﾏｽﾀ削除処理
            $this->result = $this->FrmStyle->fncKaisouMstDel($_POST['data']['STYLE_ID']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //ログインﾃｰﾌﾞﾙ更新処理(所属とﾊﾟﾀｰﾝを削除)
            $this->result = $this->FrmStyle->fncUpdateLog($_POST['data']['STYLE_ID']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $this->result['data'] = "success";
            //コミット
            $this->FrmStyle->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = FALSE;
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmStyle->Do_rollback();
        }
        //DB接続解除
        $this->FrmStyle->Do_close();

        $this->fncReturn($this->result);
    }
}