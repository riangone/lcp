<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmJiKykTaTrkList;

class FrmJiKykTaTrkListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmJiKykTaTrkList = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }
    public function index()
    {
        $this->render('index', 'FrmJiKykTaTrkList_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmJiKykTaTrkList = new FrmJiKykTaTrkList();
            $result = $this->FrmJiKykTaTrkList->fncSelect();
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
        $cboYMStart1 = "";
        $intState = 0;
        $lngOutCnt = 0;
        try {
            $cboYMStart = $_POST['data']['cboYMStart'];
            $cboYMStart1 = $_POST['data']['cboYMStart1'];
            //ログ管理
            $intState = 9;
            $this->FrmJiKykTaTrkList = new FrmJiKykTaTrkList();
            $result = $this->FrmJiKykTaTrkList->fncPrintSelect($cboYMStart);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            // print_r($result);
            // return;
            //印刷処理
            if (count($result['data']) > 0) {
                //'プレビュー表示
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();
                $data = array(
                    "HBSS_CD" => "",
                    "SITEI_NO" => "",
                    "CARNO" => "",
                    "KYK_NM" => "",
                    "TRK_NM" => "",
                    "UC_NO" => "",
                    "URI_BUSYO_CD" => "",
                    "SYAIN_NM" => "",
                    "MGN_NIN" => "",
                    "TODAY" => ""
                );
                array_push($tmp_data, $result['data']);
                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "3";
                $datas["rptJiKykTaTrkList"] = $tmp;
                $rpx_file_names["rptJiKykTaTrkList"] = $data;
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
            $this->ClsLogControl->fncLogEntry("frmJiKykTaTrkList", $intState, $lngOutCnt, $cboYMStart1);
        }
        //ログ管理 End
        $this->fncReturn($result);
    }

}