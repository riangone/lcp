<?php
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmAuthCtlEdit;

class FrmAuthCtlEditController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public $FrmAuthCtlEdit = "";
    public function index()
    {

        $this->render('index', 'FrmAuthCtlEdit_layout');
    }

    public function fncGetBusyo()
    {
        $result = array();
        $result1 = array();
        $result2 = array();
        try {
            $this->FrmAuthCtlEdit = new FrmAuthCtlEdit();
            $result1 = $this->FrmAuthCtlEdit->fncGetBusyo(FALSE);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $result2 = $this->FrmAuthCtlEdit->fncGetBusyo(TRUE);
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $result['result'] = TRUE;
            $result['data']['false'] = $result1['data'];
            $result['data']['true'] = $result2['data'];
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncSQL1()
    {
        $result = array();
        $postArr = array();
        try {
            $postArr = $_POST['data'];
            $this->FrmAuthCtlEdit = new FrmAuthCtlEdit();
            $result = $this->FrmAuthCtlEdit->fncDoSQL1($postArr);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncSQL2()
    {
        $result = array();
        $postArr = array();
        try {
            $postArr = $_POST['data'];
            $this->FrmAuthCtlEdit = new FrmAuthCtlEdit();
            $result = $this->FrmAuthCtlEdit->fncDoSQL2($postArr);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function delRowData()
    {
        $result = array();
        $Do_conn = array();
        $postArr = array();
        try {
            $postArr = $_POST['data'];
            $this->FrmAuthCtlEdit = new FrmAuthCtlEdit();
            //ＤＢ接続
            $Do_conn = $this->FrmAuthCtlEdit->Do_conn();
            if (!$Do_conn['result']) {
                throw new \Exception($Do_conn['data']);
            }
            //トランザクションを開始する
            $this->FrmAuthCtlEdit->Do_transaction();
            //削除を行う
            $result = $this->FrmAuthCtlEdit->fncDoSQL3($postArr);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['data'] = "";
            //コミット処理を行う
            $this->FrmAuthCtlEdit->Do_commit();
        } catch (\Exception $e) {
            //ロールバック処理を行う
            $this->FrmAuthCtlEdit->Do_rollback();
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->FrmAuthCtlEdit->Do_close();

        $this->fncReturn($result);
    }

    public function cmdUpdateClick()
    {
        $result = array();
        $result1 = array();
        $Do_conn = array();
        $postArr = array();
        try {
            $postArr = $_POST['data'];
            $this->FrmAuthCtlEdit = new FrmAuthCtlEdit();
            //ＤＢ接続
            $Do_conn = $this->FrmAuthCtlEdit->Do_conn();
            if (!$Do_conn['result']) {
                throw new \Exception($Do_conn['data']);
            }
            //トランザクションを開始する
            $this->FrmAuthCtlEdit->Do_transaction();
            //データベースに登録する
            //削除を行う
            $result1 = $this->FrmAuthCtlEdit->fncDoSQL3($postArr);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            //追加処理を行う
            foreach ($postArr['CREATE_DATE'] as $key => $value) {
                $HAUTH_ID = $postArr['HAUTH_ID'][$key];
                $result1 = $this->FrmAuthCtlEdit->fncDoSQL4($postArr, $value, $HAUTH_ID);
                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                }
            }
            //コミット処理を行う
            $this->FrmAuthCtlEdit->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            //ロールバック処理を行う
            $this->FrmAuthCtlEdit->Do_rollback();
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //DB接続解除
        $this->FrmAuthCtlEdit->Do_close();

        $this->fncReturn($result);
    }

}
