<?php
namespace App\Controller\CkChkzaiko;

use App\Controller\AppController;
use App\Model\CkChkzaiko\CkChkzaiko;

class CkChkzaikoController extends AppController
{
    // public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $CkChkzaiko;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsReport');
        $this->loadComponent('ClsFncLog');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'CkChkzaiko_layout');
    }

    public function fncCkChkzaikoSelect()
    {
        $postData = "";
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );

        $this->CkChkzaiko = new CkChkzaiko();
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            try {
                $postData = $_POST['request'];

                $result = $this->CkChkzaiko->fncCkChkzaikoSelect("", "", "", $postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data'], TRUE);
                $sortStr = $tmpJqgridShow['sortStr'];
                $start = $tmpJqgridShow['start'];
                $limit = $tmpJqgridShow['limit'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                //$sortStr, $start, $limit, $postData
                $result = $this->CkChkzaiko->fncCkChkzaikoSelect($sortStr, $start, $limit, $postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $result = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
                unset($_POST['request']);
                $_POST['request'] = null;

            } catch (\Exception $e) {
                $result['result'] = FALSE;
                $result['MsgID'] = "E9999";
                $result['data'] = $e->getMessage();
            }
            $this->fncReturn($result);
        }
    }

    public function fncOutput()
    {
        $getData = array();
        $path_rpxTopdf = dirname(__DIR__);
        include_once $path_rpxTopdf . '/CkChkzaiko/Component/tcpdf/rpx_to_pdf.php';
        include_once $path_rpxTopdf . '/CkChkzaiko/Component/tcpdf/rptCkChkzaiko.inc';

        $tmp_data = array();
        $rpx_file_names = array();
        $data = $_POST['data']['selectedCk_chkzaikoArr'];
        $rpx_file_names["rptCkChkzaiko"] = $data_fields_rptCkChkzaiko;

        array_push($tmp_data, $data);
        $tmp = array();
        $tmp["data"] = $tmp_data;
        $tmp["mode"] = "3";
        $datas["rptCkChkzaiko"] = $tmp;

        $obj = new \rpx_to_pdf($rpx_file_names, $datas);
        $pdfPath = $obj->to_pdf();

        $this->fncReturn($pdfPath);
    }

    //未使用の機能
    public function fncConfirm()
    {
        $this->postData = "";
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data'])) {
                $this->postData = $_POST['data'];
            }
            if ($this->postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->CkChkzaiko = new CkChkzaiko();

                //step1:connect db 未使用
                $result = $this->CkChkzaiko->connMysql();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                //step2:get select result
                $arrUpdValue = array();
                $UPDUSER = $this->Session->read('login_user');
                $NowTime = date("Ymd");

                foreach ($this->postData['list_CMN_NO'] as $key => $value) {
                    $arrUpdValue["CMN_NO" . $key] = $value;
                }

                $result = $this->CkChkzaiko->fncUpdatePrintInfo($UPDUSER, $arrUpdValue, $NowTime);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['MsgID'] = "E9999";
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncOutPutPDFSingle()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $getData = array();
            $path_rpxTopdf = dirname(__DIR__);
            include_once $path_rpxTopdf . '/CkChkzaiko/Component/tcpdf/rpx_to_pdf.php';
            include_once $path_rpxTopdf . '/CkChkzaiko/Component/tcpdf/rptCkChkzaiko_Single.inc';

            $tmp_data = array();
            $rpx_file_names = array();
            $data = json_decode($_POST['data']['selectedCk_chkzaikoArr'], true);

            $this->CkChkzaiko = new CkChkzaiko();
            $result = $this->CkChkzaiko->fncCkChkzaikoSelect('', '', '', '', $data);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $rpx_file_names["rptCkChkzaiko_Single"] = $data_fields_rptCkChkzaiko;

            array_push($tmp_data, $result['data']);
            $tmp = array();
            $tmp["data"] = $tmp_data;
            $tmp["mode"] = "3";
            $datas["rptCkChkzaiko_Single"] = $tmp;

            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf("L", "A5");
            $result['data'] = $pdfPath;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncInsertCk()
    {

        try {
            $this->CkChkzaiko = new CkChkzaiko();
            $TResult = "";
            $TResult = array("TF" => TRUE, );
            $dataV = $_POST['data']['selectedCk_chkzaikoArr'];
            $UPD_TIME = $this->ClsComFnc->FncSqlDate($this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s"));
            $numTypeArray = array(
                "TRA_CAR_SEQ_NO",
                "SATEI_GK",
                "TRA_GK",
                "YOTAK_GK",
                "RCYL_GK",
                "ASR_RYOKIN",
                "AIRBUG_RYOKIN",
                "FULON_RYOKIN",
                "JOHO_KNR_RYOKIN",
                "SHIKIN_KNR_RYOKIN",
                "MSY_TOU_TTK_DAIKO_HYO",
                "MSY_TOU_AZK_HTE_HYO",
                "SIY_SMI_CAR_SYR_HYO"
            );
            $result = $this->CkChkzaiko->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $this->CkChkzaiko->Do_transaction();
            foreach ($dataV as $value1) {

                //print_r($value1);
                //echo "-----";
                $sqlKeys = array();
                $sqlVals = array();
                $keysArr = array();
                $tmpArr = array();
                foreach ($value1 as $key => $value) {
                    if ($key == "MEIGARA_MEI") {

                    }
                    switch ($key) {
                        case "MEIGARA_MEI":
                            break;
                        case "TOU_NO_RKJ_NM":
                            break;
                        //2014-02-25 修正 START 登録日を REC_CRE_DT から TOU_DTに修正
                        //case "REC_CRE_DT" :
                        case "TOU_DT":
                            break;
                        //2014-02-25 修正 END 登録日を REC_CRE_DT から TOU_DTに修正
                        case "SHZ_RT":
                            break;
                        case "SHZ_KB":
                            break;
                        case "KYOTN_RKN":
                            array_push($sqlKeys, "KYOTN_NM");
                            array_push($sqlVals, "'" . $value . "'");
                            break;
                        case "OUT_PUT_DTM":
                            array_push($sqlKeys, "OUT_PUT_DTM");
                            array_push($sqlVals, $UPD_TIME);
                            break;
                        case "OUT_PUT_FLG":
                            array_push($sqlKeys, "OUT_PUT_FLG");
                            array_push($sqlVals, '1');
                            break;
                        default:
                            if ($key == "TRA_CAR_SEQ_NO" || $key == "CMN_NO") {
                                $tmpArr[$key] = $value;
                            }
                            array_push($sqlKeys, $key);
                            if (in_array($key, $numTypeArray)) {
                                array_push($sqlVals, $value);
                            } else {
                                array_push($sqlVals, "'" . $value . "'");
                            }
                    }

                }
                array_push($keysArr, $tmpArr);
                $sqlKeys = implode(",", $sqlKeys);
                $sqlVals = implode(",", $sqlVals);
                //ysj
                $TResult = $this->CkChkzaiko->insertCK($sqlKeys, $sqlVals);

                if ($TResult['result']) {
                    $TResult['data'] = '';
                } else {
                    throw new \Exception($TResult['data']);

                }
                //$this -> CkChkzaiko->in//($sqlKeys, $sqlVals);\
            }
            $this->CkChkzaiko->Do_commit();
        } catch (\Exception $e) {
            $TResult['data'] = $e->getMessage();
            $this->CkChkzaiko->Do_rollback();
        }
        $result = $this->CkChkzaiko->Do_close();

        $this->fncReturn($TResult);
    }

}
