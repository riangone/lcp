<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmUriBusyoCnv;

class FrmUriBusyoCnvController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmUriBusyoCnv = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmUriBusyoCnv_layout');
    }

    public function fncListSel()
    {
        $mark = "";
        $txtSYAINNO = "";
        $txtSYAINKN = "";
        $result = array();
        try {
            $mark = $_POST['data']['mark'];
            $txtSYAINNO = $_POST['data']['SYAINNO'];
            $txtSYAINKN = $_POST['data']['SYAINKN'];
            $this->FrmUriBusyoCnv = new FrmUriBusyoCnv();
            $result = $this->FrmUriBusyoCnv->fncListSel($mark, $txtSYAINNO, $txtSYAINKN);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncDeletData()
    {
        $result = array();
        $CMNNO = "";
        try {
            $CMNNO = $_POST['data'];
            $this->FrmUriBusyoCnv = new FrmUriBusyoCnv();
            $result = $this->FrmUriBusyoCnv->fncDeletData($CMNNO);
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