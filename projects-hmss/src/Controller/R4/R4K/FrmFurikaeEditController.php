<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmFurikaeEdit;

//*******************************************
// * sample controller
//*******************************************
class FrmFurikaeEditController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public $FrmFurikaeEdit;
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmFurikaeEdit_layout');
    }

    public function fncFurikaeMotSet()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {
            $postData = $_POST['data']['request'];

            $this->FrmFurikaeEdit = new FrmFurikaeEdit();

            $result = $this->FrmFurikaeEdit->fncFurikaeMotSet($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncFurikaeExist()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmFurikaeEdit = new FrmFurikaeEdit();

            $result = $this->FrmFurikaeEdit->fncFurikaeExist($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDataSet()
    {
        $result = array(
            'result' => 'false',
            'row' => ''
        );
        try {
            $this->FrmFurikaeEdit = new FrmFurikaeEdit();

            $result1 = $this->FrmFurikaeEdit->fncDataSet();

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $result2 = $this->FrmFurikaeEdit->fncDataSetKamoku();

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $result3 = $this->FrmFurikaeEdit->fncDataSetJqGrid();

            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }

            $result['result'] = TRUE;
            $result['data1'] = $result1['data'];
            $result['data2'] = $result2['data'];
            $result['data3'] = $result3['data'];
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncFurikaeSet()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => ''
        );
        try {
            $postData = $_POST['data']['request'];

            $this->FrmFurikaeEdit = new FrmFurikaeEdit();

            $result = $this->FrmFurikaeEdit->fncFurikaeSet($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncInsertFurikae()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => ''
        );
        try {
            $postData = $_POST['data']['request'];

            // print_r($postData);
// return;
            $this->FrmFurikaeEdit = new FrmFurikaeEdit();

            $result = $this->FrmFurikaeEdit->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $result['data'] = '';
            }

            $this->FrmFurikaeEdit->Do_transaction();
            $blnTran = TRUE;

            foreach ($postData as $key => $value) {
                if ($key == 0) {
                    $result1 = $this->FrmFurikaeEdit->fncInsertFurikae($value, TRUE);

                } else {
                    $result1 = $this->FrmFurikaeEdit->fncInsertFurikae($value, FALSE);
                }

                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                }

            }
            // $result1 = $this -> FrmFurikaeEdit -> fncInsertFurikae($postData);
            //
            // if (!$result1['result'])
            // {
            // throw new Exception($result1['data']);
            // }
            //
            $this->FrmFurikaeEdit->Do_commit();
            //
            $blnTran = FALSE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        if ($blnTran) {
            $this->FrmFurikaeEdit->Do_rollback();
        }

        $this->FrmFurikaeEdit->Do_close();

        $this->fncReturn($result);
    }

    public function fncDeleteFurikae()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $postData = $_POST['data']['request'];

            $this->FrmFurikaeEdit = new FrmFurikaeEdit();
            $result = $this->FrmFurikaeEdit->fncDeleteFurikae($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncGetKamokuMstValue()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $postData = $_POST['data']['request'];

        $result = $this->ClsComFnc->FncGetKamokuMstValue($postData['Kamoku'], $this->ClsComFnc->GS_KAMOKUMST, $postData['Himoku'], "0");

        if ($result['result']) {
            $result['data'] = $this->ClsComFnc->GS_KAMOKUMST;
        }

        $this->fncReturn($result);
    }

    public function fncGetBusyoMstValue()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $postData = $_POST['data']['request'];

        $result = $this->ClsComFnc->FncGetBusyoMstValue($postData['BusyoCD'], $this->ClsComFnc->GS_BUSYOMST);

        if ($result['result']) {
            $result['data'] = $this->ClsComFnc->GS_BUSYOMST;
        }

        $this->fncReturn($result);
    }

    public function fncExistsSprChk()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $postData = $_POST['data']['request'];

        try {

            foreach ($postData as $key => $value) {
                $result = $this->ClsComFnc->FncGetBusyoMstValue($value, $this->ClsComFnc->GS_BUSYOMST);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $result['data'] = $this->ClsComFnc->GS_BUSYOMST;
                    if ($result['data']['intRtnCD'] == -1) {
                        $result['data']['errMsg'] = $key;
                        break;
                    }

                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncControlNenChk()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            // $postData = $_POST['data']['request'];

            $this->FrmFurikaeEdit = new FrmFurikaeEdit();
            $result = $this->FrmFurikaeEdit->fncControlNenChk();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncFurikaeExistChk()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $postData = $_POST['data']['request'];

            $this->FrmFurikaeEdit = new FrmFurikaeEdit();
            $result = $this->FrmFurikaeEdit->fncFurikaeExistChk($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}