<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmListSyasyuMst;

class FrmListSyasyuMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmListSyasyuMst;
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
        $this->render('index', 'FrmListSyasyuMst_layout');
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
    public function subSpreadReShow()
    {
        try {

            if (!isset($_POST['request'])) {
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['request'];

            //モデルの仕様するクラスを定義
            $this->FrmListSyasyuMst = new FrmListSyasyuMst();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmListSyasyuMst->fncListSyasyuMstSel($postData);
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
            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($this->result["data"], $totalPage, $page, $tmpCount);
            $this->result = $tmpJqgrid;
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncSelectKUKURICD()
    {
        $result = array(
            'result' => FALSE,
            'data' => null,
            'rows' => 0,
        );
        try {
            if (!isset($_POST['data'])) {
                $this->fncReturn($this->result);
                return;
            }

            $postData = $_POST['data'];
            //モデルの仕様するクラスを定義
            $this->FrmListSyasyuMst = new FrmListSyasyuMst();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmListSyasyuMst->fncSelectKUKURI_CD($postData['whereStr']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $result['data'] = $this->result['data'];
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $result = $this->result;
        $this->fncReturn($result);
    }

    public function fncUpdate()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmListSyasyuMst = new FrmListSyasyuMst();

            $this->Do_conn = $this->FrmListSyasyuMst->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmListSyasyuMst->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            //---

            $this->result = $this->FrmListSyasyuMst->fncDelete($_POST['data']['txtKkrCD'], $_POST['data']['txtCarKbn']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            foreach ($_POST['data']['rowDatas'] as $value) {
                if ($value['KUKURI_CD'] != "" && $value['LINE_NO'] != "" && $value['CAR_KBN'] != "") {
                    $this->result = $this->FrmListSyasyuMst->fncInsert($value);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }

            //---
            $this->result['data'] = "success";
            //コミット
            $this->FrmListSyasyuMst->Do_commit();
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
            $this->FrmListSyasyuMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmListSyasyuMst->Do_close();

        $this->fncReturn($this->result);
    }

    public function fncSingleDelete()
    {
        try {
            if (!isset($_POST['data'])) {
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['data'];
            $this->FrmListSyasyuMst = new FrmListSyasyuMst();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;

            $this->result = $this->FrmListSyasyuMst->fncSingleDelete($postData['KUKURI_CD']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->result['data'] = "success";
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;

        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

}