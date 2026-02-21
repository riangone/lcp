<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmSyokusyubetuKamokuMente;

class FrmSyokusyubetuKamokuMenteController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $FrmSyokusyubetuKamokuMente;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        $this->render('index', 'FrmSyokusyubetuKamokuMente_layout');
    }

    public function fncSelSyokusyuKamokCnvSQL()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        try {
            $this->FrmSyokusyubetuKamokuMente = new FrmSyokusyubetuKamokuMente();

            $result = $this->FrmSyokusyubetuKamokuMente->fncSelSyokusyuKamokCnvSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $tmpJqgrid = $this->ClsComFncJKSYS->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

            $this->fncReturn($tmpJqgrid);
        } catch (\Exception $e) {
            $result['result'] = True;
            $result['error'] = $e->getMessage();
            $this->fncReturn($result);
        }
    }

    public function fncDelSyokusyuKamokCnvSQL()
    {
        $FrmSyokusyubetuKamokuMente = new FrmSyokusyubetuKamokuMente();
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $txtKaCd = $_POST["data"]["KAMOK_CD"];
            $txtHiCd = $_POST["data"]["HIMOK_CD"];
            $cmbItem = $_POST["data"]["KOUMK_NO"];
            $cmbSyCd = $_POST["data"]["SYOKUSYU_CD"];

            //トランザクション開始
            $FrmSyokusyubetuKamokuMente->Do_transaction();
            $blnTran = TRUE;

            $result = $FrmSyokusyubetuKamokuMente->fncDelSyokusyuKamokCnvSQL($txtKaCd, $txtHiCd, $cmbItem, $cmbSyCd);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //コミット
            $FrmSyokusyubetuKamokuMente->Do_commit();
            $result['data'] = "";
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $FrmSyokusyubetuKamokuMente->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    public function fncSelCodeMstSQL()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        try {
            $FrmSyokusyubetuKamokuMente = new FrmSyokusyubetuKamokuMente();
            $resultSelCode = $FrmSyokusyubetuKamokuMente->FncSelCodeMstSQL();
            if (!$resultSelCode['result']) {
                throw new \Exception($resultSelCode['data']);
            }
            $result['Code'] = $resultSelCode;
            $resultku = $FrmSyokusyubetuKamokuMente->FncSelKubunMstSQL();
            if (!$resultku['result']) {
                throw new \Exception($resultku['data']);
            }

            $result['Kubun'] = $resultku;
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncGetKamokuNm()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        try {
            $txtKaCd = $_POST['data']['txtKaCd'];
            $txtHiCd = $_POST['data']['txtHiCd'];
            $result = $this->FncGetKamokuMstValue($txtKaCd, $txtHiCd);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncRegSyokusyuKamokCnvSQL()
    {
        $this->FrmSyokusyubetuKamokuMente = new FrmSyokusyubetuKamokuMente();
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        $blnTran = FALSE;
        try {
            $cmbItem = $_POST['data']['cmbItem'];
            $txtKaCd = $_POST['data']['txtKaCd'];
            $txtHiCd = $_POST['data']['txtHiCd'];
            $cmbSyCd = $_POST['data']['cmbSyCd'];
            $lblCreD = $_POST['data']['lblCreD'];
            $lblCreM = $_POST['data']['lblCreM'];
            $lblCreA = $_POST['data']['lblCreA'];

            //マスタ存在チェック
            $result = $this->FncGetKamokuMstValue($txtKaCd, $txtHiCd);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
            if ($result['row'] == 0) {
                //該当データ無
                throw new \Exception('W0007');
            }

            //トランザクション開始
            $this->FrmSyokusyubetuKamokuMente->Do_transaction();
            $blnTran = TRUE;

            $result = $this->FrmSyokusyubetuKamokuMente->fncRegSyokusyuKamokCnvSQL($cmbItem, $txtKaCd, $txtHiCd, $cmbSyCd, $lblCreD, $lblCreM, $lblCreA);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //コミット
            $this->FrmSyokusyubetuKamokuMente->Do_commit();
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->FrmSyokusyubetuKamokuMente->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //科目名取得
    public function FncGetKamokuMstValue($txtKaCd, $txtHiCd)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $FrmSyokusyubetuKamokuMente = new FrmSyokusyubetuKamokuMente();
            $result = $FrmSyokusyubetuKamokuMente->FncGetKamokuMstValue($txtKaCd, $txtHiCd);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

}
