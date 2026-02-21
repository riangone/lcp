<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHendoBubetu;

//*******************************************
// * sample controller
//*******************************************
class FrmHendoBubetuController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmHendoBubetu = '';
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

        $this->render('index', 'FrmHendoBubetu_layout');
    }

    public function fncDeleteHSTAFFKomoku()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $postData = $_POST['data']['request'];
            $this->FrmHendoBubetu = new FrmHendoBubetu();
            $result = $this->FrmHendoBubetu->fncDeleteHSTAFFKomokuOnly($postData);
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
            $this->FrmHendoBubetu = new FrmHendoBubetu();
            $result = $this->FrmHendoBubetu->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmHendoBubetu->Do_transaction();
            $blnTran = TRUE;

            $result1 = $this->FrmHendoBubetu->fncDeleteHSTAFFKomoku($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $result2 = $this->FrmHendoBubetu->fncInsertHSTAFFKomoku($postData);

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $this->FrmHendoBubetu->Do_commit();

            $blnTran = FALSE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        if ($blnTran) {
            $this->FrmHendoBubetu->Do_rollback();
        }

        $this->FrmHendoBubetu->Do_close();
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

            $this->FrmHendoBubetu = new FrmHendoBubetu();
            if ($postData['blnCheck'] == 'true') {
                $result = $this->FrmHendoBubetu->fncFromHSTAFFSelect($postData, TRUE);
            } else {
                $result = $this->FrmHendoBubetu->fncFromHSTAFFSelect($postData);
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

    public function subComboSet2()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmHendoBubetu = new FrmHendoBubetu();

            $result = $this->FrmHendoBubetu->subComboSet2();

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

            $this->FrmHendoBubetu = new FrmHendoBubetu();

            $result = $this->FrmHendoBubetu->frmSampleLoadDate();

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
            $this->FrmHendoBubetu = new FrmHendoBubetu();

            $result = $this->FrmHendoBubetu->fncDataSet();

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