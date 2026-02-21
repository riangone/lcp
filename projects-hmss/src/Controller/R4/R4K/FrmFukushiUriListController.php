<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmFukushiUriList;

class FrmFukushiUriListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmFukushiUriList = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }
    public function index()
    {
        $this->render('index', 'FrmFukushiUriList_layout');
    }

    public function frmLeaseUriageMeisaiLoad()
    {
        $result = array();
        try {
            $this->FrmFukushiUriList = new FrmFukushiUriList();
            $result = $this->FrmFukushiUriList->fncSelect();
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
        $cboYM = "";
        $cboYM1 = "";
        $intState = 0;
        $lngOutCnt = 0;
        try {
            $cboYM = $_POST['data']['cboYM'];
            $cboYM1 = $_POST['data']['cboYM1'];
            //ログ管理
            $intState = 9;
            $this->FrmFukushiUriList = new FrmFukushiUriList();
            $result = $this->FrmFukushiUriList->fncPrintSelect($cboYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //印刷処理
            if (count($result['data']) > 0) {
                $lngOutCnt = count($result['data']);
                //'プレビュー表示
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();
                $data = array(
                    "OKYAKUSAMA" => "",
                    "TOU_DATE" => "",
                    "TOURK_NO" => "",
                    "TOA_NAME" => "",
                    "CAR_NO" => "",
                    "UC_NO" => "",
                    "URI_BUSYO_CD" => "",
                    "SYAIN_NM" => "",
                    "SRY_PRC" => "",
                    "TODAY" => "",
                    "TOUGETU" => ""
                );
                $SRY_PRC_TOTAL = 0;
                foreach ($result['data'] as $value) {
                    $SRY_PRC_TOTAL += $value['SRY_PRC'];
                }
                $SRY_PRC_TOTAL_ARR = array("SRY_PRC_TOTAL" => $SRY_PRC_TOTAL);
                foreach ($result['data'] as $key => $value) {
                    $result['data'][$key] = array_merge($value, $SRY_PRC_TOTAL_ARR);
                }
                array_push($tmp_data, $result['data']);

                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "3";
                $datas["rptFukushiUriList"] = $tmp;
                $rpx_file_names["rptFukushiUriList"] = $data;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf2();
                $result['pdfmark'] = TRUE;
                $result['pdfpath'] = $pdfPath;
            }
            //ログ管理
            $intState = 1;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //ログ管理 Start
        if ($intState != 0) {
            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmFukushiUriList", $intState, $lngOutCnt, $cboYM1);
        }
        //ログ管理 End
        $this->fncReturn($result);
    }

}