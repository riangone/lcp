<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmReOutReportEdit;

class FrmReOutReportEditController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $FrmReOutReportEdit;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmReOutReportEdit_layout');
    }

    public function select()
    {
        $result = array();
        try {
            $this->FrmReOutReportEdit = new FrmReOutReportEdit();
            $result = $this->FrmReOutReportEdit->reselect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncSaiseiSyukkoSet()
    {
        $result = array();
        $strInpDate = "";
        try {
            $strInpDate = $_POST['data'];
            $this->FrmReOutReportEdit = new FrmReOutReportEdit();
            $result = $this->FrmReOutReportEdit->fncSaiseiSyukkoSet($strInpDate);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncExistsCheck()
    {
        $result = array();
        $strInpDate = "";
        try {
            $strInpDate = $_POST['data'];
            $this->FrmReOutReportEdit = new FrmReOutReportEdit();
            $result = $this->FrmReOutReportEdit->fncExistsCheck(str_replace("/", "", $strInpDate));
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function delInsert()
    {
        $result = array();
        $DB_Conn = array();
        $Do_Excute = array();
        $GridData = "";
        $strInpDate = "";
        $blnTranFlg = FALSE;
        $blnDBCon = FALSE;
        try {
            $GridData = $_POST['data']['GridData'];
            $strInpDate = $_POST['data']['strInpDate'];
            $this->FrmReOutReportEdit = new FrmReOutReportEdit();
            $DB_Conn = $this->FrmReOutReportEdit->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            $blnDBCon = TRUE;
            $this->FrmReOutReportEdit->Do_transaction();
            $blnTranFlg = TRUE;
            $Do_Excute = $this->FrmReOutReportEdit->fncDeleteSaiseiSyukko(str_replace("/", "", $strInpDate));
            if (!$Do_Excute['result']) {
                throw new \Exception("E0007");
            }
            if ($Do_Excute['result']) {
                for ($intRow = 0; $intRow < count($GridData); $intRow++) {
                    //フラグに1がたっているものだけ処理(一行でも入力されている)
                    if ($GridData[$intRow]['CHECK'] == 1) {
                        $Do_Excute = $this->FrmReOutReportEdit->fncInsertHSaiseiSyukko(str_replace("/", "", $strInpDate), $intRow, $GridData[$intRow]);
                        if (!$Do_Excute['result']) {
                            throw new \Exception("E0007");
                        }
                    }
                }
            }
            //コミット
            $this->FrmReOutReportEdit->Do_commit();
            $blnTranFlg = FALSE;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //コミットされていない場合
        if ($blnTranFlg == TRUE) {
            //ロールバック
            $this->FrmReOutReportEdit->Do_rollback();
        }
        //DB接続解除
        if ($blnDBCon == TRUE) {
            $this->FrmReOutReportEdit->Do_close();
        }

        $this->fncReturn($result);
    }

}