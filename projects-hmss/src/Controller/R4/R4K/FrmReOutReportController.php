<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmReOutReport;

class FrmReOutReportController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $FrmReOutReport;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmReOutReport_layout');
    }

    public function frmBusyoMstLoad()
    {
        $result = array();
        try {
            $this->FrmReOutReport = new FrmReOutReport();
            $result = $this->FrmReOutReport->reselect();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function subSpreadReShow()
    {
        $result = array();
        $cboDateFrom = "";
        $cboDateTo = "";
        try {
            $cboDateFrom = $_POST['data']['cboDateFrom'];
            $cboDateTo = $_POST['data']['cboDateTo'];
            $this->FrmReOutReport = new FrmReOutReport();
            $result = $this->FrmReOutReport->fncSearchSaiseiSyukko($cboDateFrom, $cboDateTo);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteSaiseiSyukko()
    {
        $result = array();
        $INP_DATE = "";
        try {
            $INP_DATE = $_POST['data'];
            $this->FrmReOutReport = new FrmReOutReport();
            $result = $this->FrmReOutReport->fncDeleteSaiseiSyukko($INP_DATE);
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