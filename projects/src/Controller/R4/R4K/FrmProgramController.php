<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmProgram;

class FrmProgramController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $FrmProgram;
    private $blnTranFlg;
    private $result;
    private $Do_conn;
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
        $this->render('index', 'FrmProgram_layout');
    }

    /*
    ***********************************************************************
    '処 理 名：データグリッドの再表示
    '関 数 名：fncFrmProgramSelect
    '引    数：無し
    '戻 り 値：無し
    '処理説明：データグリッドを再表示する
    '**********************************************************************
    */
    public function fncFrmProgramSelect()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmProgram = new FrmProgram();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmProgram->fncFrmProgramSelect();

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
            $this->result = $this->FrmProgram->fncFrmProgramSelect();
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
            $this->FrmProgram = new FrmProgram();

            $this->Do_conn = $this->FrmProgram->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmProgram->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            //---

            $this->result = $this->FrmProgram->fncDelete();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            foreach ($_POST['data'] as $value) {
                if ($value['PRO_NO'] != "") {
                    $this->result = $this->FrmProgram->fncInsert($value);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }
            //---
            $this->result['data'] = "success";
            //コミット
            $this->FrmProgram->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmProgram->Do_rollback();
        }
        //DB接続解除
        $this->FrmProgram->Do_close();

        $this->fncReturn($this->result);
    }
    public function fncDelete()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmProgram = new FrmProgram();

            //トランザクション開始
            $this->FrmProgram->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;

            $this->result = $this->FrmProgram->fncSingleDelete($_POST['data']['PRO_NO']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->result['data'] = "success";
            //コミット
            $this->FrmProgram->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmProgram->Do_rollback();
        }
        //DB接続解除
        $this->FrmProgram->Do_close();

        $this->fncReturn($this->result);
    }
}