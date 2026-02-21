<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyasyuTourokuList;

class FrmSyasyuTourokuListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSyasyuTourokuList = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmSyasyuTourokuList_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmSyasyuTourokuList = new FrmSyasyuTourokuList();
            $result = $this->FrmSyasyuTourokuList->fncSelect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncPrintTougetut()
    {
        $result = array();
        $cboYMTo = "";
        try {
            $cboYMTo = $_POST['data'];
            $this->FrmSyasyuTourokuList = new FrmSyasyuTourokuList();
            $result = $this->FrmSyasyuTourokuList->fncPrintTougetut($cboYMTo);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count($result['data']) > 0) {
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptSyasyuTourokuList.inc';
                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();

                $printdata = $this->fncprint($result['data'], $totalcar1, $totalcar2, $total);
                array_push($tmp_data, $printdata);
                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "5";
                $datas["rptSyasyuTourokuList"] = $tmp;
                $rpx_file_names["rptSyasyuTourokuList"] = $data;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf2();
                $result['pdfmark'] = TRUE;
                $result['pdfpath'] = $pdfPath;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncPrintTouki()
    {
        $result = array();
        $cboYMFrom = "";
        $cboYMTo = "";
        try {
            $cboYMFrom = $_POST['data']['cboYMFrom'];
            $cboYMTo = $_POST['data']['cboYMTo'];
            $this->FrmSyasyuTourokuList = new FrmSyasyuTourokuList();
            $result = $this->FrmSyasyuTourokuList->fncPrintTouki($cboYMFrom, $cboYMTo);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count($result['data']) > 0) {
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptSyasyuTourokuList.inc';
                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();
                $printdata = $this->fncprint($result['data'], $totalcar1, $totalcar2, $total);
                array_push($tmp_data, $printdata);
                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "5";
                $datas["rptSyasyuTourokuList"] = $tmp;
                $rpx_file_names["rptSyasyuTourokuList"] = $data;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf2();
                $result['pdfmark'] = TRUE;
                $result['pdfpath'] = $pdfPath;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncPrintDouble()
    {
        $result1 = array();
        $result2 = array();
        $result = array();
        $cboYMFrom = "";
        $cboYMTo = "";
        try {
            $cboYMFrom = $_POST['data']['cboYMFrom'];
            $cboYMTo = $_POST['data']['cboYMTo'];
            $this->FrmSyasyuTourokuList = new FrmSyasyuTourokuList();
            $result1 = $this->FrmSyasyuTourokuList->fncPrintTougetut($cboYMTo);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $result['data'] = $result1['data'];
            $result2 = $this->FrmSyasyuTourokuList->fncPrintTouki($cboYMFrom, $cboYMTo);
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $result['data'] = array_merge($result1['data'], $result2['data']);
            if (count($result1['data']) > 0 || count($result2['data']) > 0) {
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptSyasyuTourokuList.inc';
                $rpx_file_names = array();
                $tmp_data = array();
                $tmp1 = array();
                $tmp2 = array();
                if (count($result1['data']) > 0) {
                    $printdata1 = $this->fncprint($result1['data'], $totalcar1, $totalcar2, $total);
                    array_push($tmp_data, $printdata1);
                    $tmp1["data"] = $tmp_data;
                    $tmp1["mode"] = "5";
                    $datas["rptSyasyuTourokuList"] = $tmp1;
                    $rpx_file_names["rptSyasyuTourokuList"] = $data;
                }
                if (count($result2['data']) > 0) {
                    $tmp_data = array();
                    $printdata2 = $this->fncprint($result2['data'], $totalcar1, $totalcar2, $total);
                    array_push($tmp_data, $printdata2);
                    $tmp2["data"] = $tmp_data;
                    $tmp2["mode"] = "5";
                    $datas["rptSyasyuTouroku1"] = $tmp2;
                    $rpx_file_names["rptSyasyuTouroku1"] = $data;
                }
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf2();
                $result['pdfmark'] = TRUE;
                $result['pdfpath'] = $pdfPath;
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncprint($printdata, $totalcar1, $totalcar2, $total)
    {

        $tatol_array = array();
        $current_KBN = "";
        $last_KBN = "";
        foreach ($printdata as $value) {
            $current_KBN = $value['CAR_KBN'];
            if ($last_KBN === "") {
                foreach ($value as $key1 => $value1) {
                    $totalcar1[$key1] = (string) ((float) $value1 + (float) $totalcar1[$key1]);
                    $total[$key1] = (string) ((float) $value1 + (float) $total[$key1]);
                }
            } elseif ($current_KBN == $last_KBN) {
                foreach ($value as $key2 => $value2) {
                    $totalcar1[$key2] = (string) ((float) $value2 + (float) $totalcar1[$key2]);
                    $total[$key2] = (string) ((float) $value2 + (float) $total[$key2]);
                }
                $totalcar1['CAR_KBN'] = $current_KBN;
            } else {
                $totalcar1['CAR_KBN'] = $last_KBN;
                array_push($tatol_array, $totalcar1);
                $totalcar1 = $totalcar2;
                foreach ($value as $key3 => $value3) {
                    $totalcar1[$key3] = (string) ((float) $value3 + (float) $totalcar1[$key3]);
                    $total[$key3] = (string) ((float) $value3 + (float) $total[$key3]);
                }
            }
            $last_KBN = $current_KBN;
        }
        array_push($tatol_array, $totalcar1);
        array_push($tatol_array, $total);
        array_push($printdata, $tatol_array);
        return $printdata;
    }

}