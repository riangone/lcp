<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSCUriageMeisai;

class FrmSCUriageMeisaiController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSCUriageMeisai = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmSCUriageMeisai_layout');
    }

    public function fncSelectMeisai()
    {
        $CmnNO = "";
        $result = array();
        try {
            $CmnNO = $_POST['data'];
            $this->FrmSCUriageMeisai = new FrmSCUriageMeisai();
            $result = $this->FrmSCUriageMeisai->FncSelectMeisai($CmnNO);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncSelectSitadori()
    {
        $strCMN_NO = "";
        $strTblName = "";
        $strRIR_NO = "";
        $result = array();
        try {
            $strCMN_NO = $_POST['data']['strCMN_NO'];
            $strTblName = $_POST['data']['strTblName'];
            $strRIR_NO = $_POST['data']['strRIR_NO'];
            $this->FrmSCUriageMeisai = new FrmSCUriageMeisai();
            $result = $this->FrmSCUriageMeisai->FncSelectSitadori($strCMN_NO, $strTblName, $strRIR_NO);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncSelectJyohen()
    {

        $strCmnNO = "";
        $intCnt = "";
        $result = array();
        try {
            $strCmnNO = $_POST['data']['strCmnNO'];
            $intCnt = $_POST['data']['intCnt'];
            $this->FrmSCUriageMeisai = new FrmSCUriageMeisai();
            $result = $this->FrmSCUriageMeisai->fncSelectJyohen($strCmnNO, $intCnt);
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