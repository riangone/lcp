<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmBusyoMstEdit;

class FrmBusyoMstEditController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $Do_conn;
    public $frmBusyoMstEdit;
    public $result;
    public $FrmBusyoMst;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        $this->render('index', 'FrmBusyoMstEdit_layout');
    }

    public function fncExistsCheck()
    {
        try {
            if (!isset($_POST['data']['busyoCd'])) {
                $this->fncReturn($this->result);
                return;
            }
            $BUSYO_CD = $_POST['data']['busyoCd'];
            //モデルの仕様するクラスを定義
            $this->FrmBusyoMst = new FrmBusyoMstEdit();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmBusyoMst->fncExistsCheck($BUSYO_CD);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncInsertBusyo()
    {
        try {
            if (isset($_POST['data']['txtBusyoCD']) && isset($_POST['data']['txtBusyoNM']) && isset($_POST['data']['txtBusyoKN']) && isset($_POST['data']['txtBusyoRK'])) {
                if ($_POST['data']['txtBusyoCD'] != "" && $_POST['data']['txtBusyoNM'] != "" && $_POST['data']['txtBusyoKN'] != "" && $_POST['data']['txtBusyoRK'] != "") {
                    $_POST['data']['txtStartDate'] = str_replace("/", "", $_POST['data']['txtStartDate']);
                    $_POST['data']['txtEndDate'] = str_replace("/", "", $_POST['data']['txtEndDate']);

                    //モデルの仕様するクラスを定義
                    $this->FrmBusyoMst = new FrmBusyoMstEdit();
                    //モデルクラスのselect処理を呼出し
                    $this->result = $this->FrmBusyoMst->fncInsertBusyo($_POST['data']);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                    $this->result['data'] = "success";
                } else {
                    throw new \Exception("error");
                }

            }

        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncBusyoSet()
    {
        try {

            if (!isset($_POST['data']['busyoCd'])) {
                $this->fncReturn($this->result);
                return;
            }
            //モデルの仕様するクラスを定義
            $this->FrmBusyoMst = new FrmBusyoMstEdit();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmBusyoMst->fncBusyoSet($_POST['data']['busyoCd']);

            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncUpdateBusyo()
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmBusyoMst = new FrmBusyoMstEdit();
            //モデルクラスのselect処理を呼出し
            $this->result = $this->FrmBusyoMst->fncUpdateBusyo($_POST['data']);

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