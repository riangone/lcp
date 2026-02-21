<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHanteChkList;

class FrmHanteChkListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmHanteChkList = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }
    public function index()
    {
        $this->render('index', 'FrmHanteChkList_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmHanteChkList = new FrmHanteChkList();
            $result = $this->FrmHanteChkList->fncSelect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdActionClick()
    {
        $result = array();
        $cboYMStart = "";
        $cboYMEnd = "";
        $intState = 1;
        $lngOutCnt = 0;
        try {
            $cboYMStart = $_POST['data']['cboYMStart'];
            $cboYMEnd = $_POST['data']['cboYMEnd'];
            //ログ管理
            $intState = 9;
            $this->FrmHanteChkList = new FrmHanteChkList();
            $result = $this->FrmHanteChkList->fncPrintSelect($cboYMStart, $cboYMEnd);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if (count($result['data']) > 0) {
                //'プレビュー表示
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptHanteChkList.inc';
                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();

                $CURRENT_KRI_DATE = "";
                $LAST_KRI_DATE = "";
                $total_array = array();
                foreach ($result['data'] as $value) {
                    $CURRENT_KRI_DATE = $value['WHERE_KRI_DATE'];
                    if ($LAST_KRI_DATE === "") {
                        foreach ($total as $key1 => $value1) {
                            $total[$key1] = (string) ($value1 + $value[$key1]);
                        }
                    } elseif ($CURRENT_KRI_DATE == $LAST_KRI_DATE) {
                        foreach ($total as $key2 => $value2) {
                            $total[$key2] = (string) ($value2 + $value[$key2]);
                        }
                    } else {
                        array_push($total_array, $total);
                        $total = $total1;
                        foreach ($total as $key3 => $value3) {
                            $total[$key3] = (string) ($value3 + $value[$key3]);
                        }
                    }
                    $LAST_KRI_DATE = $CURRENT_KRI_DATE;
                }
                array_push($total_array, $total);
                array_push($result['data'], $total_array);
                array_push($tmp_data, $result['data']);

                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "6";
                $datas["rptHanteChkList"] = $tmp;
                $rpx_file_names["rptHanteChkList"] = $data;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf2();
                $result['pdfmark'] = TRUE;
                $result['pdfpath'] = $pdfPath;
            }
            //ログ管理
            $lngOutCnt = count($result['data']);
            $intState = 1;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //ログ管理 Start
        if ($intState != 0) {
            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmHanteChkList", $intState, $lngOutCnt, $cboYMStart, $cboYMEnd);
        }
        //ログ管理 End
        $this->fncReturn($result);
    }

}