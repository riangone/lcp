<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHendoKobetuCopy;

//*******************************************
// * sample controller
//*******************************************
class FrmHendoKobetuCopyController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmHendoKobetuCopy = '';
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

        $this->render('index', 'FrmHendoKobetuCopy_layout');
    }

    public function fncExistCheckSel()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmHendoKobetuCopy = new FrmHendoKobetuCopy();

            $result = $this->FrmHendoKobetuCopy->fncExistCheckSel($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
        }

        $this->fncReturn($result);
    }

    public function fncDeleteStaffKoumoku()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmHendoKobetuCopy = new FrmHendoKobetuCopy();

            $result = $this->FrmHendoKobetuCopy->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmHendoKobetuCopy->Do_transaction();

            $blnTran = TRUE;

            $result1 = $this->FrmHendoKobetuCopy->fncDeleteStaffKoumoku($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $result2 = $this->FrmHendoKobetuCopy->fncInsertStaffKoumoku($postData);

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $this->FrmHendoKobetuCopy->Do_commit();

            $blnTran = FALSE;

            $result['result'] = TRUE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTran) {
            $this->FrmHendoKobetuCopy->Do_rollback();
        }

        $this->FrmHendoKobetuCopy->Do_close();

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

            $this->FrmHendoKobetuCopy = new FrmHendoKobetuCopy();

            $result = $this->FrmHendoKobetuCopy->subComboSet2();

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