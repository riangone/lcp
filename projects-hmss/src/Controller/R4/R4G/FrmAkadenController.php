<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmAkaden;

//*******************************************
// * sample controller
//*******************************************
class FrmAkadenController extends AppController
{
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;

    public $DsDeleteTbl;
    public $intState = 0;
    public $lngOutCntK = 0;
    public $lngOutCntG = 0;
    public $FrmAkaden;
    public $FrmAkaden1;
    public $DB_Conn;

    public function initialize(): void
    {
        parent::initialize();
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
        $this->loadComponent('ClsReport');
        $this->loadComponent('ClsComDoRefresh');
        $this->loadComponent('ClsLogControl');
    }


    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmAkaden_layout.ctpを参照)
        $this->render('index', 'FrmAkaden_layout');
    }

    public function fncFrmAkaden()
    {
        $postData = "";
        $result = "";
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            try {

                // 呼出クラスのインスタンス作成
                if (isset($_POST['request'])) {
                    $postData = $_POST['request'];
                }
                if ($postData == '') {
                    $result = array(
                        'result' => FALSE,
                        'data' => 'param error'
                    );
                } else {

                    $this->FrmAkaden = new FrmAkaden();

                    $this->FrmAkaden->Do_transaction();
                    $result = $this->FrmAkaden->fncFrmAkaden($postData);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);

                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = $tmpJqgridShow['count'];

                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
                    $this->FrmAkaden->Do_commit();
                    unset($_POST['request']);
                    $result = $tmpJqgrid;
                }

            } catch (\Exception $ex) {
                $result['result'] = FALSE;
                $result['data'] = $ex->getMessage();
                $this->FrmAkaden->Do_rollback();
            }
            $this->fncReturn($result);
        }
    }

    public function fncDeleteKasou()
    {
        $result = "";
        $this->FrmAkaden1 = new FrmAkaden();
        $this->DB_Conn = $this->FrmAkaden1->Do_conn();
        $this->DsDeleteTbl = $_POST['data']['DsDeleteTbl'];
        $this->intState = (int) $_POST['data']['intState'];
        $this->lngOutCntK = (int) $_POST['data']['lngOutCntK'];
        $this->lngOutCntG = (int) $_POST['data']['lngOutCntG'];
        try {
            if (isset($_POST['data']['request']) == true) {
                $postData = $_POST["data"]['request'];
                if ($postData == '') {
                    $result = array(
                        'result' => FALSE,
                        'data' => 'param error'
                    );
                } else {
                    if (!$this->DB_Conn['result']) {
                        throw new \Exception($this->DB_Conn['data']);
                    }
                    $key1Cnt = count($postData[0]);
                    foreach ($postData as $value) {
                        $where = " where ";
                        $i = 0;
                        foreach ($value as $key1 => $value1) {
                            $where .= $key1 . "='" . $value1 . "'";
                            if ($i < $key1Cnt - 1) {
                                $where .= " AND ";
                            }
                            $i++;
                        }
                        $this->FrmAkaden1->Do_transaction();
                        $result = $this->FrmAkaden1->fncDeleteKasou($where);
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                        $this->FrmAkaden1->Do_commit();
                    }
                }
            }
            $result = array(
                'result' => TRUE,
                'data' => 'sql success'
            );
        } catch (\Exception $ex) {
            $result['result'] = FALSE;
            $result['data'] = $ex->getMessage();
            $this->FrmAkaden1->Do_rollback();
        }
        //---
        $strJyoken = array(
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            ""
        );
        if ($this->intState != 0) {
            //$this -> $intState<>0の場合、ログ管理テーブルに登録
            $cnt = 0;
            $intRecCnt = 1;
            for ($tt = 0; $tt <= count($this->DsDeleteTbl) - 1; $tt++) {
                if ($tt == 0) {
                    $strJyoken[0] = $this->DsDeleteTbl[$tt]['CMN_NO'];
                    $strJyoken[1] = $this->DsDeleteTbl[$tt]['KASOUNO'];
                    $strJyoken[2] = $this->DsDeleteTbl[$tt]['SYADAIKATA'];
                    $strJyoken[3] = $this->DsDeleteTbl[$tt]['CAR_NO'];
                    $cnt = 4;
                }
                $strJyoken[$cnt] = $this->DsDeleteTbl[$tt]["FUZOKUHINKBN"];
                $cnt += 1;
                $strJyoken[$cnt] = $this->DsDeleteTbl[$tt]["EDA_NO"];
                $cnt += 1;

                if ($cnt > 19 || $tt == count($this->DsDeleteTbl) - 1) {
                    $resultLog1 = "";
                    $resultLog2 = "";
                    //架装部用品のログ管理
                    $resultLog1 = $this->ClsLogControl->fncLogEntry("frmAkaden_kasou", $this->intState, $this->lngOutCntK, $strJyoken[0], $strJyoken[1], $strJyoken[2], $strJyoken[3], $strJyoken[4], $strJyoken[5], $strJyoken[6], $strJyoken[7], $strJyoken[8], $strJyoken[9], $strJyoken[10], $strJyoken[11], $strJyoken[12], $strJyoken[13], $strJyoken[14], $strJyoken[15], $strJyoken[16], $strJyoken[17], $strJyoken[18], $strJyoken[19], $intRecCnt);
                    if (!$resultLog1['result']) {
                        $result['resultLog1'] = $resultLog1;
                    }
                    //外注加工依頼書のログ管理
                    $resultLog2 = $this->ClsLogControl->fncLogEntry("frmAkaden_gaichu", $this->intState, $this->lngOutCntG, $strJyoken[0], $strJyoken[1], $strJyoken[2], $strJyoken[3], $strJyoken[4], $strJyoken[5], $strJyoken[6], $strJyoken[7], $strJyoken[8], $strJyoken[9], $strJyoken[10], $strJyoken[11], $strJyoken[12], $strJyoken[13], $strJyoken[14], $strJyoken[15], $strJyoken[16], $strJyoken[17], $strJyoken[18], $strJyoken[19], $intRecCnt);
                    if (!$resultLog2['result']) {
                        $result['resultLog2'] = $resultLog2;
                    }
                    $cnt = 0;
                    $intRecCnt++;
                }
            }
        }
        $this->fncReturn($result);
        $this->FrmAkaden1->Do_close();
    }

    public function fncHPRINTTANT()
    {
        $result = "";
        try {
            $this->FrmAkaden = new FrmAkaden();
            $this->FrmAkaden->Do_transaction();
            $result = $this->FrmAkaden->fncHPRINTTANT();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $this->FrmAkaden->Do_commit();
        } catch (\Exception $ex) {
            $result['result'] = FALSE;
            $result['data'] = $ex->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncPrintTbl()
    {
        $path_rpxTopdf = dirname(__DIR__);
        include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
        switch ($_POST['data']['intRptCnt']) {
            case '1':
                include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder.inc';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder2.inc';
                $tmp_data = array();
                $rpx_file_names = array();
                $data = $_POST['data']['tmpVal']['request'][0];

                $rpx_file_names["rptOutFitOrder"] = $data_fields_rptOutFitOrder;
                $rpx_file_names["rptOutFitOrder2"] = $data_fields_rptOutFitOrder2;
                array_push($tmp_data, $data);
                $tmp = array();
                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "0";
                $datas["rptOutFitOrder"] = $tmp;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf();
                $this->fncReturn($pdfPath);

                break;
            case "2":
                include_once $path_rpxTopdf . '/Component/tcpdf/rptContractOut.inc';
                $tmp_data = array();
                $rpx_file_names = array();

                $data = $_POST['data']['tmpVal']['request'];
                $rpx_file_names["rptContractOut"] = $data_fields_rptContractOut;

                $tmp_array = array();
                foreach ($data as $k => $e) {
                    $name = $e['TORIHIKI_CD'];
                    if (!isset($tmp_array[$name])) {
                        $tmp_array[$name] = $e;
                        unset($tmp_array[$name]['BUHINNM'], $tmp_array[$name]['SEIKYU']);
                    }
                    $tmp_array[$name]['sub_datas'][] = array(
                        'BUHINNM' => $e['BUHINNM'],
                        'SEIKYU' => $e['SEIKYU']
                    );
                }
                $data = array_values($tmp_array);
                unset($tmp_array);

                $tmp = array();
                $tmp["data"] = $data;
                $tmp["mode"] = "2";
                $datas["rptContractOut"] = $tmp;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas, "4");
                $pdfPath = $obj->to_pdf();

                $this->fncReturn($pdfPath);

                break;
            case "3":
                include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder.inc';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder2.inc';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptContractOut.inc';
                $datas = array();
                $tmp_data = array();

                $rpx_file_names = array();
                $data1 = $_POST['data']['tmpVal']['request1'][0];
                $rpx_file_names["rptOutFitOrder"] = $data_fields_rptOutFitOrder;
                $rpx_file_names["rptOutFitOrder2"] = $data_fields_rptOutFitOrder2;
                array_push($tmp_data, $data1);
                $tmp1 = array();
                $tmp1["data"] = $tmp_data;
                $tmp1["mode"] = "0";
                $datas["rptOutFitOrder"] = $tmp1;

                $data2 = $_POST['data']['tmpVal']['request2'];
                $rpx_file_names["rptContractOut"] = $data_fields_rptContractOut;
                $rpx_file_names["rptContractOut2"] = $data_fields_rptContractOut;
                $rpx_file_names["rptContractOut3"] = $data_fields_rptContractOut;
                $rpx_file_names["rptContractOut4"] = $data_fields_rptContractOut;


                foreach ($data2 as $k => $e) {
                    $name = $e['TORIHIKI_CD'];
                    if (!isset($tmp_array[$name])) {
                        $tmp_array[$name] = $e;
                        unset($tmp_array[$name]['BUHINNM'], $tmp_array[$name]['SEIKYU']);
                    }
                    $tmp_array[$name]['sub_datas'][] = array(
                        'BUHINNM' => $e['BUHINNM'],
                        'SEIKYU' => $e['SEIKYU']
                    );
                }
                $data2 = array_values($tmp_array);
                unset($tmp_array);

                $tmp2 = array();
                $tmp2["data"] = $data2;
                $tmp2["mode"] = "2";
                $datas["rptContractOut"] = $tmp2;

                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf();
                $this->fncReturn($pdfPath);
                break;
            default:
                break;
        }

    }

    public function fncGetAllData()
    {
        $result = "";
        try {

            // 呼出クラスのインスタンス作成

            if (isset($_POST['request']) == true) {
                $postData = $_POST['request'];
                $this->FrmFDHokanSelectSelect = new FrmAkaden();

                $result = $this->FrmFDHokanSelectSelect->fncFrmAkaden_part($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                unset($_POST['request']);

                $_POST['request'] = null;
            }
        } catch (\Exception $ex) {
            $result['result'] = FALSE;
            $result['data'] = $ex->getMessage();
            $this->FrmFDHokanSelectSelect->Do_rollback();
        }
        $this->fncReturn($result);
    }

    public function fncGetArrayData($SourceDataArr, $rpxName)
    {
        switch ($rpxName) {
            case 'rptOutFitOrder':
                $dataArray = array(
                    "KASOUNO" => "",
                    "CMNNO" => "",
                    "SIYOSYA_KN" => "",
                    "SYADAIKATA" => "",
                    "BUSYOMEI" => "",
                    "SYAINMEI" => "",
                    "SON_HOSYACD" => "",
                    "HAKKOUNIN" => "",
                    "Tenpo_CD" => "",
                    "SYASYU_NM" => "",
                    "HANBAISYASYU" => "",
                    "TORIHIKI_1" => "",
                    "TORIHIKI_2" => "",
                    "MEMO" => "",
                    "TORIHIKI_3" => "",
                    "OBUHINNM_1" => "",
                    "OBIKOU_1" => "",
                    "OBIKOU_2" => "",
                    "OBIKOU_3" => "",
                    "OBIKOU_4" => "",
                    "OBIKOU_6" => "",
                    "OBIKOU_7" => "",
                    "OBIKOU_8" => "",
                    "OBIKOU_9" => "",
                    "OBIKOU_10" => "",
                    "OBIKOU_11" => "",
                    "OBIKOU_12" => "",
                    "OBIKOU_5" => "",
                    "SBIKOU_1" => "",
                    "SBIKOU_7" => "",
                    "SBIKOU_8" => "",
                    "SBIKOU_9" => "",
                    "SBIKOU_10" => "",
                    "SBIKOU_11" => "",
                    "SBIKOU_12" => "",
                    "SBIKOU_13" => "",
                    "SBIKOU_14" => "",
                    "SBIKOU_4" => "",
                    "SBIKOU_5" => "",
                    "SBIKOU_6" => "",
                    "SBIKOU_2" => "",
                    "SBIKOU_3" => "",
                    "OTUMIKOMI_1" => "",
                    "OTUMIKOMI_2" => "",
                    "OTUMIKOMI_8" => "",
                    "OTUMIKOMI_9" => "",
                    "OTUMIKOMI_10" => "",
                    "OTUMIKOMI_11" => "",
                    "OTUMIKOMI_12" => "",
                    "OTUMIKOMI_3" => "",
                    "OTUMIKOMI_4" => "",
                    "OTUMIKOMI_5" => "",
                    "OTUMIKOMI_6" => "",
                    "OTUMIKOMI_7" => "",
                    "OSURYO_1" => "",
                    "OSURYO_2" => "",
                    "OSURYO_3" => "",
                    "OSURYO_4" => "",
                    "OSURYO_5" => "",
                    "OSURYO_6" => "",
                    "OSURYO_7" => "",
                    "OSURYO_8" => "",
                    "OSURYO_9" => "",
                    "OSURYO_10" => "",
                    "OSURYO_11" => "",
                    "OSURYO_12" => "",
                    "SSURYO_4" => "",
                    "SSURYO_3" => "",
                    "SSURYO_2" => "",
                    "SSURYO_1" => "",
                    "SSURYO_12" => "",
                    "SSURYO_11" => "",
                    "SSURYO_10" => "",
                    "SSURYO_9" => "",
                    "SSURYO_8" => "",
                    "SSURYO_7" => "",
                    "SSURYO_6" => "",
                    "SSURYO_5" => "",
                    "SSURYO_14" => "",
                    "SSURYO_13" => "",
                    "STUMIKOMI_4" => "",
                    "STUMIKOMI_3" => "",
                    "STUMIKOMI_2" => "",
                    "STUMIKOMI_1" => "",
                    "STUMIKOMI_13" => "",
                    "STUMIKOMI_12" => "",
                    "STUMIKOMI_11" => "",
                    "STUMIKOMI_10" => "",
                    "STUMIKOMI_9" => "",
                    "STUMIKOMI_8" => "",
                    "STUMIKOMI_7" => "",
                    "STUMIKOMI_6" => "",
                    "STUMIKOMI_5" => "",
                    "STUMIKOMI_14" => "",
                    "OTEIKA_1" => "",
                    "OTEIKA_2" => "",
                    "OTEIKA_3" => "",
                    "OTEIKA_4" => "",
                    "OTEIKA_5" => "",
                    "OTEIKA_6" => "",
                    "OTEIKA_7" => "",
                    "OTEIKA_8" => "",
                    "STEIKA_5" => "",
                    "STEIKA_4" => "",
                    "STEIKA_3" => "",
                    "STEIKA_2" => "",
                    "STEIKA_1" => "",
                    "OTEIKA_12" => "",
                    "STEIKA_6" => "",
                    "OTEIKA_11" => "",
                    "OTEIKA_10" => "",
                    "OTEIKA_9" => "",
                    "STEIKA_10" => "",
                    "STEIKA_9" => "",
                    "STEIKA_8" => "",
                    "STEIKA_7" => "",
                    "STEIKAKEI" => "",
                    "STEIKA_14" => "",
                    "STEIKA_13" => "",
                    "STEIKA_12" => "",
                    "STEIKA_11" => "",
                    "TEIKAGOUKEI" => "",
                    "OTEIKAKEI" => "",
                    "OGENKA_1" => "",
                    "OGENKA_2" => "",
                    "OGENKA_4" => "",
                    "OGENKA_5" => "",
                    "OGENKA_6" => "",
                    "OGENKA_10" => "",
                    "OGENKA_11" => "",
                    "OGENKA_12" => "",
                    "OGENKAKEI" => "",
                    "OGENKA_7" => "",
                    "OGENKA_8" => "",
                    "OGENKA_9" => "",
                    "OGENKA_3" => "",
                    "OJITUGEN_11" => "",
                    "OJITUGEN_12" => "",
                    "OJITUGENKEI" => "",
                    "OJITUGEN_10" => "",
                    "OJITUGEN_8" => "",
                    "OJITUGEN_9" => "",
                    "SGENKA_1" => "",
                    "OJITUGEN_4" => "",
                    "OJITUGEN_1" => "",
                    "SGENKA_2" => "",
                    "OJITUGEN_5" => "",
                    "OJITUGEN_2" => "",
                    "OJITUGEN_6" => "",
                    "OJITUGEN_3" => "",
                    "OJITUGEN_7" => "",
                    "SGENKA_5" => "",
                    "SGENKA_4" => "",
                    "SGENKA_3" => "",
                    "SJITUGEN_6" => "",
                    "SGENKA_6" => "",
                    "SGENKA_7" => "",
                    "SJITUGEN_5" => "",
                    "SJITUGEN_4" => "",
                    "SGENKA_8" => "",
                    "SJITUGEN_3" => "",
                    "SJITUGEN_2" => "",
                    "SGENKA_9" => "",
                    "SGENKA_10" => "",
                    "SJITUGEN_1" => "",
                    "SGENKA_13" => "",
                    "SGENKA_12" => "",
                    "SGENKA_11" => "",
                    "SJITUGEN_11" => "",
                    "SGENKA_14" => "",
                    "SGENKAKEI" => "",
                    "GENKAGOUKEI" => "",
                    "SJITUGEN_10" => "",
                    "SJITUGEN_9" => "",
                    "SJITUGEN_8" => "",
                    "SJITUGEN_7" => "",
                    "SJITUGEN_14" => "",
                    "SJITUGEN_13" => "",
                    "SJITUGEN_12" => "",
                    "SJITUGENKEI" => "",
                    "JITUGOUKEI" => "",
                    "CARNO" => "",
                    "HAKKOUBI" => "",
                    "OMEDALCD_1" => "",
                    "OMEDALCD_2" => "",
                    "OBUHINNM_2" => "",
                    "OMEDALCD_3" => "",
                    "OBUHINNM_3" => "",
                    "OMEDALCD_4" => "",
                    "OBUHINNM_4" => "",
                    "OMEDALCD_5" => "",
                    "OBUHINNM_5" => "",
                    "OMEDALCD_6" => "",
                    "OBUHINNM_6" => "",
                    "OMEDALCD_7" => "",
                    "OMEDALCD_8" => "",
                    "OBUHINNM_7" => "",
                    "OBUHINNM_8" => "",
                    "OBUHINNM_10" => "",
                    "OBUHINNM_9" => "",
                    "OMEDALCD_10" => "",
                    "OMEDALCD_9" => "",
                    "OBUHINNM_11" => "",
                    "OMEDALCD_11" => "",
                    "OMEDALCD_12" => "",
                    "OBUHINNM_12" => "",
                    "SMEDALCD_1" => "",
                    "SBUHINNM_1" => "",
                    "SMEDALCD_2" => "",
                    "SBUHINNM_2" => "",
                    "SMEDALCD_3" => "",
                    "SBUHINNM_3" => "",
                    "SMEDALCD_4" => "",
                    "SBUHINNM_4" => "",
                    "SMEDALCD_5" => "",
                    "SBUHINNM_5" => "",
                    "SMEDALCD_6" => "",
                    "SBUHINNM_6" => "",
                    "SMEDALCD_7" => "",
                    "SBUHINNM_7" => "",
                    "SMEDALCD_8" => "",
                    "SBUHINNM_8" => "",
                    "SMEDALCD_9" => "",
                    "SBUHINNM_9" => "",
                    "SMEDALCD_10" => "",
                    "SBUHINNM_10" => "",
                    "SMEDALCD_11" => "",
                    "SBUHINNM_11" => "",
                    "SMEDALCD_12" => "",
                    "SBUHINNM_12" => "",
                    "SMEDALCD_13" => "",
                    "SBUHINNM_13" => "",
                    "SMEDALCD_14" => "",
                    "SBUHINNM_14" => "",
                    "SMEDALCD_15" => "",
                    "SMEDALCD_16" => "",
                    "SBUHINNM_15" => "",
                    "SBUHINNM_16" => "",
                    "SBIKOU_15" => "",
                    "SBIKOU_16" => "",
                    "STUMIKOMI_15" => "",
                    "STUMIKOMI_16" => "",
                    "SSURYO_15" => "",
                    "SSURYO_16" => "",
                    "STEIKA_15" => "",
                    "STEIKA_16" => "",
                    "SGENKA_15" => "",
                    "SGENKA_16" => "",
                    "SJITUGEN_15" => "",
                    "SJITUGEN_16" => "",
                    "SMEDALCD_17" => "",
                    "SMEDALCD_18" => "",
                    "SBUHINNM_17" => "",
                    "SBUHINNM_18" => "",
                    "SBIKOU_17" => "",
                    "SBIKOU_18" => "",
                    "STUMIKOMI_17" => "",
                    "STUMIKOMI_18" => "",
                    "SSURYO_17" => "",
                    "SSURYO_18" => "",
                    "STEIKA_17" => "",
                    "STEIKA_18" => "",
                    "SGENKA_17" => "",
                    "SGENKA_18" => "",
                    "SJITUGEN_17" => "",
                    "SJITUGEN_18" => "",
                    "SMEDALCD_19" => "",
                    "SMEDALCD_20" => "",
                    "SBUHINNM_19" => "",
                    "SBUHINNM_20" => "",
                    "SBIKOU_19" => "",
                    "SBIKOU_20" => "",
                    "STUMIKOMI_19" => "",
                    "STUMIKOMI_20" => "",
                    "SSURYO_19" => "",
                    "SSURYO_20" => "",
                    "STEIKA_19" => "",
                    "STEIKA_20" => "",
                    "SGENKA_19" => "",
                    "SGENKA_20" => "",
                    "SJITUGEN_19" => "",
                    "SJITUGEN_20" => "",
                    "SMEDALCD_21" => "",
                    "SMEDALCD_22" => "",
                    "SBUHINNM_21" => "",
                    "SBUHINNM_22" => "",
                    "SBIKOU_21" => "",
                    "SBIKOU_22" => "",
                    "STUMIKOMI_21" => "",
                    "STUMIKOMI_22" => "",
                    "SSURYO_21" => "",
                    "SSURYO_22" => "",
                    "STEIKA_21" => "",
                    "STEIKA_22" => "",
                    "SGENKA_21" => "",
                    "SGENKA_22" => "",
                    "SJITUGEN_21" => "",
                    "SJITUGEN_22" => "",
                    "SMEDALCD_23" => "",
                    "SMEDALCD_24" => "",
                    "SBUHINNM_23" => "",
                    "SBUHINNM_24" => "",
                    "SBIKOU_23" => "",
                    "SBIKOU_24" => "",
                    "STUMIKOMI_23" => "",
                    "STUMIKOMI_24" => "",
                    "SSURYO_23" => "",
                    "SSURYO_24" => "",
                    "STEIKA_23" => "",
                    "STEIKA_24" => "",
                    "SGENKA_23" => "",
                    "SGENKA_24" => "",
                    "SJITUGEN_23" => "",
                    "SJITUGEN_24" => "",
                    "SMEDALCD_25" => "",
                    "SMEDALCD_26" => "",
                    "SBUHINNM_25" => "",
                    "SBUHINNM_26" => "",
                    "SBIKOU_25" => "",
                    "SBIKOU_26" => "",
                    "STUMIKOMI_25" => "",
                    "STUMIKOMI_26" => "",
                    "SSURYO_25" => "",
                    "SSURYO_26" => "",
                    "STEIKA_25" => "",
                    "STEIKA_26" => "",
                    "SGENKA_25" => "",
                    "SGENKA_26" => "",
                    "SJITUGEN_25" => "",
                    "SJITUGEN_26" => "",
                    "TORIHIKI_4" => "",
                    "TORIHIKI_5" => "",
                    "TORIHIKI_6" => ""
                );
                //$tmpArr = array_keys($dataArray);
                foreach ($SourceDataArr as $key => $value) {
                    if (array_key_exists($key, $dataArray)) {
                        $dataArray[$key] = $value;
                    }
                }
                $rpx_file_names = array();
                array_push($tmp_data, $dataArray);
                $tmp = array();
                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "0";
                $datas["rptOutFitOrder"] = $tmp;
                array_push($rpx_file_names, "rptOutFitOrder");
                array_push($rpx_file_names, "rptOutFitOrder2");

                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $path = $obj->to_pdf();

                return $path;
            case 'rptOutFitOrder2':
                break;
            case 'rptContractOut':
                break;
            case 'rptContractOut2':
                break;
            case 'rptContractOut3':
                break;
            case 'rptContractOut4':
                break;
            default:
                break;
        }
    }
}
