<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmBusyoMst;

class FrmBusyoMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $Do_conn;
    public $FrmBusyoMst;
    public $result;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        $this->render('index', 'FrmBusyoMst_layout');
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
                //Viewへ返却値を設定
                // $this->set('result', $this->result);
                //Viewファイルを呼び出し
                // $this->render('frmbusyomstload');
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['request'];

            //モデルの仕様するクラスを定義
            $this->FrmBusyoMst = new FrmBusyoMst();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmBusyoMst->fncSearchBusyo($postData);
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

    public function fncDeleteBusyo()
    {
        try {

            if (!isset($_POST['data']['busyoCd'])) {
                $this->fncReturn($this->result);
                return;
            }
            $busyoCD = $_POST['data']['busyoCd'];
            //モデルの仕様するクラスを定義
            $this->FrmBusyoMst = new FrmBusyoMst();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmBusyoMst->fncDeleteBusyo($busyoCD);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->result['data'] = "success";
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

}