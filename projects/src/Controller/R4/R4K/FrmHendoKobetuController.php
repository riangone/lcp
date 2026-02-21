<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHendoKobetu;

//*******************************************
// * sample controller
//*******************************************
class FrmHendoKobetuController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmHendoKobetu = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmHendoKobetu_layout');
    }

    public function fncDeleteHSTAFFKomoku()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $postData = $_POST['data']['request'];
            $this->FrmHendoKobetu = new FrmHendoKobetu();
            $result = $this->FrmHendoKobetu->fncDeleteHSTAFFKomokuOnly($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteInsertHSTAFFKomoku()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $postData = $_POST['data']['request'];
            $this->FrmHendoKobetu = new FrmHendoKobetu();
            $result = $this->FrmHendoKobetu->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmHendoKobetu->Do_transaction();
            $blnTran = TRUE;

            $result1 = $this->FrmHendoKobetu->fncDeleteHSTAFFKomoku($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $result2 = $this->FrmHendoKobetu->fncInsertHSTAFFKomoku($postData);

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $this->FrmHendoKobetu->Do_commit();

            $blnTran = FALSE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        if ($blnTran) {
            $this->FrmHendoKobetu->Do_rollback();
        }

        $this->FrmHendoKobetu->Do_close();
        $this->fncReturn($result);
    }

    public function fncSyainmstExist()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => ''
        );
        try {
            $postData = $_POST['data']['request'];
            $this->FrmHendoKobetu = new FrmHendoKobetu();
            $result = $this->FrmHendoKobetu->fncSyainmstExist($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncFromTeisyuDelIns()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $postData = $_POST['data']['request'];
            $this->FrmHendoKobetu = new FrmHendoKobetu();
            $result = $this->FrmHendoKobetu->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmHendoKobetu->Do_transaction();
            $blnTran = TRUE;

            $result1 = $this->FrmHendoKobetu->fncFromTeisyuDel($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $result2 = $this->FrmHendoKobetu->fncFromTeisyuIns($postData);

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $this->FrmHendoKobetu->Do_commit();

            $blnTran = FALSE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTran) {
            $this->FrmHendoKobetu->Do_rollback();
        }
        $this->FrmHendoKobetu->Do_close();
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

    public function fncFromHSTAFFSelect()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {

            $this->FrmHendoKobetu = new FrmHendoKobetu();
            if ($postData['blnCheck'] == 'true') {
                $result = $this->FrmHendoKobetu->fncFromHSTAFFSelect($postData, TRUE);
            } else {
                $result = $this->FrmHendoKobetu->fncFromHSTAFFSelect($postData);
            }

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function subComboSet()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {
            $postData = $_POST['data']['request'];

            $this->FrmHendoKobetu = new FrmHendoKobetu();

            $result = $this->FrmHendoKobetu->subComboSet($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function subComboSet2()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmHendoKobetu = new FrmHendoKobetu();

            $result = $this->FrmHendoKobetu->subComboSet2();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncExistCheckSel()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {
            $postData = $_POST['data']['request'];

            $this->FrmHendoKobetu = new FrmHendoKobetu();

            $result = $this->FrmHendoKobetu->fncExistCheckSel($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function frmOptionInputLoad()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmHendoKobetu = new FrmHendoKobetu();

            $result = $this->FrmHendoKobetu->frmSampleLoadDate();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncDataSetSyain()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {
            $this->FrmHendoKobetu = new FrmHendoKobetu();

            $result = $this->FrmHendoKobetu->fncDataSetSyain();

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
            $this->FrmHendoKobetu = new FrmHendoKobetu();

            $result = $this->FrmHendoKobetu->fncDataSet();

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