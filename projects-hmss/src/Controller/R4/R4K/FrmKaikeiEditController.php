<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmKaikeiEdit;

//*******************************************
// * sample controller
//*******************************************
class FrmKaikeiEditController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmKaikeiEdit;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmBusyoSearch_layout.ctpを参照)

        $this->render('index', 'FrmKaikeiEdit_layout');
    }

    public function getSysDate()
    {

        $TIME = $this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s");
        $TIME = substr($TIME, 0, 10);

        $this->fncReturn($TIME);

    }

    public function fncKaikeiSet()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmKaikeiEdit = new FrmKaikeiEdit();

            $result = $this->FrmKaikeiEdit->fncKaikeiSet($postData);

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
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {
            $this->FrmKaikeiEdit = new FrmKaikeiEdit();

            $result = $this->FrmKaikeiEdit->fncDataSet();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDataSetKamoku()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {
            $this->FrmKaikeiEdit = new FrmKaikeiEdit();

            $result = $this->FrmKaikeiEdit->fncDataSetKamoku();

            if (!$result['result']) {
                throw new \Exception($result['data']);
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
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {
            $this->FrmKaikeiEdit = new FrmKaikeiEdit();

            $result = $this->FrmKaikeiEdit->fncControlNenChk();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
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

        $result = $this->ClsComFnc->FncGetBusyoMstValue($postData['CD'], $this->ClsComFnc->GS_BUSYOMST);

        if ($result['result']) {
            $result['data'] = $this->ClsComFnc->GS_BUSYOMST;
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
        if ($postData['strKomoku'] == "" || $postData['strKomoku'] == null) {
            $result = $this->ClsComFnc->FncGetKamokuMstValue($postData['CD'], $this->ClsComFnc->GS_KAMOKUMST);
        } else {

            $result = $this->ClsComFnc->FncGetKamokuMstValue($postData['CD'], $this->ClsComFnc->GS_KAMOKUMST, $postData['strKomoku']);
        }

        if ($result['result']) {
            $result['data'] = $this->ClsComFnc->GS_KAMOKUMST;
        }

        $this->fncReturn($result);
    }

    public function fncInsertKaikei()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmKaikeiEdit = new FrmKaikeiEdit();

            $result = $this->FrmKaikeiEdit->fncInsertKaikei($postData);

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

    public function fncUpdateKaikei()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmKaikeiEdit = new FrmKaikeiEdit();

            $result = $this->FrmKaikeiEdit->fncUpdateKaikei($postData);

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

}