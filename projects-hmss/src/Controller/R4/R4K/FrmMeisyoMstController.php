<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmMeisyoMst;

class FrmMeisyoMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmMeisyoMst;
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
        $this->render('index', 'FrmMeisyoMst_layout');
    }

    /*
           ***********************************************************************
           '処 理 名：データグリッドの再表示
           '関 数 名：fncMeisyouMstSelect
           '引    数：無し
           '戻 り 値：無し
           '処理説明：データグリッドを再表示する
           '**********************************************************************
           */
    public function fncMeisyouMstSelect()
    {
        try {
            if (!isset($_POST['request'])) {
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['request'];

            //モデルの仕様するクラスを定義
            $this->FrmMeisyoMst = new FrmMeisyoMst();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmMeisyoMst->fncMeisyouMstSelect($postData);
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

    public function fncUpdate()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmMeisyoMst = new FrmMeisyoMst();
            $this->Do_conn = $this->FrmMeisyoMst->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmMeisyoMst->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            //---
            $this->result = $this->FrmMeisyoMst->fncDelete($_POST['data']['txtID']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            foreach ($_POST['data']['rowDatas'] as $value) {
                if ($value['MEISYOU_CD'] != "" && $value['MEISYOU'] != "") {
                    $this->result = $this->FrmMeisyoMst->fncInsert($value, $_POST['data']['txtID']);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }
            $this->result['data'] = "success";
            //コミット
            $this->FrmMeisyoMst->Do_commit();
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
            $this->FrmMeisyoMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmMeisyoMst->Do_close();

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
            $this->FrmMeisyoMst = new FrmMeisyoMst();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            $this->result = $this->FrmMeisyoMst->fncSingleDelete($postData['MEISYOU_CD'], $postData['MEISYOU_ID']);
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