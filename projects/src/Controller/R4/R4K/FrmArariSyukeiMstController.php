<?php
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
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmArariSyukeiMst;

class FrmArariSyukeiMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmArariSyukeiMst;
    public $blnTranFlg;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        $this->render('index', 'FrmArariSyukeiMst_layout');
    }

    /*
     ***********************************************************************
     '処 理 名：データグリッドの再表示
     '関 数 名：subSpreadReShow
     '引    数：無し
     '戻 り 値：無し
     '処理説明：データグリッドを再表示する
     '**********************************************************************
     */
    public function fncFrmArariSyukeiMstSelect()
    {
        try {

            // if (!isset($_POST['request'])) {
            //     $postData = "";
            // } else {
            //     $postData = $_POST['request'];
            // }

            //モデルの仕様するクラスを定義
            $this->FrmArariSyukeiMst = new FrmArariSyukeiMst();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmArariSyukeiMst->fncFrmArariSyukeiMstSelect();
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
            $this->result = $this->FrmArariSyukeiMst->fncFrmArariSyukeiMstSelect();
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
            $this->FrmArariSyukeiMst = new FrmArariSyukeiMst();

            $this->Do_conn = $this->FrmArariSyukeiMst->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmArariSyukeiMst->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            //---

            $this->result = $this->FrmArariSyukeiMst->fncDelete();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            foreach ($_POST['data'] as $key => $value) {
                if ($value['OYA_CD'] != "") {
                    $this->result = $this->FrmArariSyukeiMst->fncInsert($value);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }
            //---
            $this->result['data'] = "success";
            //コミット
            $this->FrmArariSyukeiMst->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;
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
            $this->FrmArariSyukeiMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmArariSyukeiMst->Do_close();

        $this->fncReturn($this->result);
    }

}