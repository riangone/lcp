<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyokaiFurikae;

//*******************************************
// * sample controller
//*******************************************
class FrmSyokaiFurikaeController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSyokaiFurikae = '';
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

        $this->render('index', 'FrmSyokaiFurikae_layout');
    }

    public function frmOptionInputLoad()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmSyokaiFurikae = new FrmSyokaiFurikae();

            $result = $this->FrmSyokaiFurikae->frmSampleLoadDate();

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
            $this->FrmSyokaiFurikae = new FrmSyokaiFurikae();

            $result = $this->FrmSyokaiFurikae->fncDataSetSyain();

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
            $this->FrmSyokaiFurikae = new FrmSyokaiFurikae();

            $result = $this->FrmSyokaiFurikae->fncDataSet();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncFromChuSyokaiSelect()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmSyokaiFurikae = new FrmSyokaiFurikae();

            $result = $this->FrmSyokaiFurikae->fncFromChuSyokaiSelect($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

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
            $this->FrmSyokaiFurikae = new FrmSyokaiFurikae();
            $result = $this->FrmSyokaiFurikae->fncSyainmstExist($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncExistChukoSyokai()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => ''
        );
        try {
            $postData = $_POST['data']['request'];

            $this->FrmSyokaiFurikae = new FrmSyokaiFurikae();

            $result = $this->FrmSyokaiFurikae->fncExistChukoSyokai($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncDeleteChuSyokai()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => ''
        );
        try {
            $postData = $_POST['data']['request'];

            $this->FrmSyokaiFurikae = new FrmSyokaiFurikae();

            $result = $this->FrmSyokaiFurikae->fncDeleteChuSyokai($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteInsertChuSyokai()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => ''
        );
        try {
            $postData = $_POST['data']['request'];

            $this->FrmSyokaiFurikae = new FrmSyokaiFurikae();

            $result = $this->FrmSyokaiFurikae->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmSyokaiFurikae->Do_transaction();

            $blnTran = TRUE;

            $result1 = $this->FrmSyokaiFurikae->fncDeleteChuSyokaiExcu($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $result2 = $this->FrmSyokaiFurikae->fncInsertChuSyokai($postData);

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $this->FrmSyokaiFurikae->Do_commit();

            $blnTran = FALSE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        if ($blnTran) {
            $this->FrmSyokaiFurikae->Do_rollback();
        }

        $this->FrmSyokaiFurikae->Do_close();

        $this->fncReturn($result);
    }

}