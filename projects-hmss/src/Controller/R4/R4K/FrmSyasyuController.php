<?php
/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                              担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150813           #1967                         BUG                             Yuanjh
 * * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyasyu;

class FrmSyasyuController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmSyasyu;
    public $blnTranFlg = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        $this->render('index', 'FrmSyasyu_layout');
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
    public function fncFrmSyasyuSelect()
    {
        try {

            if (!isset($_POST['request'])) {
                $postData = "";
            } else {
                $postData = $_POST['request'];
            }

            //モデルの仕様するクラスを定義
            $this->FrmSyasyu = new FrmSyasyu();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmSyasyu->fncFrmSyasyuSelect($postData, "");
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);

            $sortstr = $tmpJqgridShow['sortStr'];
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            for ($ii = 0; $ii < count((array) $this->result['data']); $ii++) {
                foreach ((array) $this->result['data'][$ii] as $key => $value) {
                    $this->result['data'][$ii][$key] = trim($this->ClsComFnc->fncNv($value));
                }
            }

            $this->result = $this->FrmSyasyu->fncFrmSyasyuSelect($postData, $sortstr);

            // if (isset($this->result['data'][0]["ARARI"])) {
            //     $this->log(strpos($this->result['data'][0]["ARARI"], '.'));
            // }
            //---20150813  Yuanjh  add S.
            foreach ($this->result['data'] as $key => $value) {
                $tmpARARI = $value["ARARI"];
                if ((substr($tmpARARI, -3)) == '.00') {
                    $tmpARARI = str_replace('.00', '', $tmpARARI);
                    $this->result['data'][$key]["ARARI"] = $tmpARARI;
                }
            }
            //---20150813  Yuanjh  add E.
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
            $this->FrmSyasyu = new FrmSyasyu();

            $this->Do_conn = $this->FrmSyasyu->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmSyasyu->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            //---

            $this->result = $this->FrmSyasyu->fncDelete();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            foreach ($_POST['data'] as $value) {
                if ($value['UCOYA_CD'] != "") {
                    $this->result = $this->FrmSyasyu->fncInsert($value);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }
            //---
            $this->result['data'] = "success";
            //コミット
            $this->FrmSyasyu->Do_commit();
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
            $this->FrmSyasyu->Do_rollback();
        }
        //DB接続解除
        $this->FrmSyasyu->Do_close();

        $this->fncReturn($this->result);
    }
}
