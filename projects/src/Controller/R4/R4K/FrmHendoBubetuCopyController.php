<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHendoBubetuCopy;

//*******************************************
// * sample controller
//*******************************************
class FrmHendoBubetuCopyController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmHendoBubetuCopy = '';
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

        $this->render('index', 'FrmHendoBubetuCopy_layout');
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
            $this->FrmHendoBubetuCopy = new FrmHendoBubetuCopy();

            $result = $this->FrmHendoBubetuCopy->fncExistCheckSel($postData);

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
            $this->FrmHendoBubetuCopy = new FrmHendoBubetuCopy();

            $result = $this->FrmHendoBubetuCopy->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmHendoBubetuCopy->Do_transaction();

            $blnTran = TRUE;

            $result1 = $this->FrmHendoBubetuCopy->fncDeleteStaffKoumoku($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $result2 = $this->FrmHendoBubetuCopy->fncInsertStaffKoumoku($postData);

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $this->FrmHendoBubetuCopy->Do_commit();

            $blnTran = FALSE;

            $result['result'] = TRUE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTran) {
            $this->FrmHendoBubetuCopy->Do_rollback();
        }

        $this->FrmHendoBubetuCopy->Do_close();

        $this->fncReturn($result);
    }



}