<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmRankingInput;

//*******************************************
// * sample controller
//*******************************************
class FrmRankingInputController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmRankingInput;
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
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmRankingInput_layout');
    }

    public function fncControlNenChk()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            // $postData = $_POST['data']['request'];

            $this->FrmRankingInput = new FrmRankingInput();
            $result = $this->FrmRankingInput->fncControlNenChk();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncRankingDataSel()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }

            $this->FrmRankingInput = new FrmRankingInput();
            $result = $this->FrmRankingInput->fncRankingDataSel($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncInsert()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }

            $this->FrmRankingInput = new FrmRankingInput();

            $res = $this->FrmRankingInput->Do_conn();

            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

            $this->FrmRankingInput->Do_transaction();

            $blnTrn = TRUE;

            $res = $this->FrmRankingInput->fncDelete($postData);

            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

            $res = $this->FrmRankingInput->fncRankingDataIns($postData);

            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

            $this->FrmRankingInput->Do_commit();
            $blnTrn = FALSE;
            $result['result'] = TRUE;
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTrn) {
            $result['result'] = FALSE;
            $this->FrmRankingInput->Do_rollback();
            $this->FrmRankingInput->Do_close();
        }

        $this->fncReturn($result);
    }

    public function fncDelete()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }

            $this->FrmRankingInput = new FrmRankingInput();

            $res = $this->FrmRankingInput->Do_conn();

            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

            $this->FrmRankingInput->Do_transaction();

            $blnTrn = TRUE;

            $res = $this->FrmRankingInput->fncDelete($postData);

            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

            $this->FrmRankingInput->Do_commit();
            $blnTrn = FALSE;
            $result['result'] = TRUE;
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTrn) {
            $result['result'] = FALSE;
            $this->FrmRankingInput->Do_rollback();
            $this->FrmRankingInput->Do_close();
        }

        $this->fncReturn($result);
    }

}