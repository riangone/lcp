<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmTesuryo;

class FrmTesuryoController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmTesuryo;
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
        $this->render('index', 'FrmTesuryo_layout');
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
    public function fncFrmTesuryoSelect()
    {
        try {

            //モデルの仕様するクラスを定義
            $this->FrmTesuryo = new FrmTesuryo();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmTesuryo->fncFrmTesuryoSelect();
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
            $this->result = $this->FrmTesuryo->fncFrmTesuryoSelect($sortstr);
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
        //print_r($_POST['data']);
        try {
            //モデルの仕様するクラスを定義
            $this->FrmTesuryo = new FrmTesuryo();

            $this->Do_conn = $this->FrmTesuryo->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmTesuryo->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            //---

            $this->result = $this->FrmTesuryo->fncDelete();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            foreach ($_POST['data'] as $value) {
                if ($value['KIJYUN_DT'] != "") {
                    $this->result = $this->FrmTesuryo->fncInsert($value);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
            }
            //---
            $this->result['data'] = "success";
            //コミット
            $this->FrmTesuryo->Do_commit();
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
            $this->FrmTesuryo->Do_rollback();
        }
        //DB接続解除
        $this->FrmTesuryo->Do_close();

        $this->fncReturn($this->result);
    }

    public function fncSingleDelete()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmTesuryo = new FrmTesuryo();

            //---

            $this->result = $this->FrmTesuryo->fncSingleDelete($_POST['data']['KIJYUN_DT']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->result['data'] = "success";
            //---
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $this->fncReturn($this->result);
    }
}
