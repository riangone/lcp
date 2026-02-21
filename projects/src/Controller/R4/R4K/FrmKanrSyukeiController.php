<?php
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151027           #2241						   BUG                              li
 * 20151105           #2257						   BUG                              li
 * 20151109           #2256						   BUG                              li
 * 20160414           #2417						   BUG                              YIN
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmKanrSyukei;

//*******************************************
// * sample controller
//*******************************************
class FrmKanrSyukeiController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
        $this->loadComponent('ClsLogControl');
    }
    public $FrmKanrSyukei;
    public $objSw;
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmBusyoSearch_layout.ctpを参照)

        $this->render('index', 'FrmKanrSyukei_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmKanrSyukei = new FrmKanrSyukei();

            $result = $this->FrmKanrSyukei->frmKanrSyukeiLoad();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {

            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncSiwakeErrPrintSelect()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
            'MsgId' => ''
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmKanrSyukei = new FrmKanrSyukei();

            $objLog = $this->ClsFncLog->GS_OUTPUTLOG;
            //LOG出力ﾊﾟｽを取得する
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $this->ClsFncLog->strLogPath = $strPath . "/mnt/temp/" . $this->ClsComFnc->FncGetPath('pprlogpath');
            if ($this->ClsFncLog->strLogPath == "") {
                // $this -> ClsFncLog -> strLogPath = $strPath . "/" . "mnt/temp/GenkaMst/GenkaMst.csv";
                $this->ClsFncLog->strLogPath = $strPath . "/" . "mnt/temp/log/log.log";
                $tmpPath = substr($this->ClsFncLog->strLogPath, 0, (strripos($this->ClsFncLog->strLogPath, "/")) + 1);

                if (!file_exists($tmpPath)) {
                    mkdir($tmpPath, 0777, TRUE);
                }
            } else {

                $tmpPath = substr($this->ClsFncLog->strLogPath, 0, (strripos($this->ClsFncLog->strLogPath, "/")) + 1);
                if (!file_exists($tmpPath)) {
                    mkdir($tmpPath, 0777, TRUE);
                }
            }
            //LOG出力開始
            $sysDate = $this->ClsComFnc->FncGetSysDate("y-m-d h:i:s");

            $objLog['strStartDate'] = $sysDate;
            $objLog["strID"] = "当月部署別実績集計";
            $objLog["strState"] = "";
            $this->ClsFncLog->fncStartLog($this->ClsFncLog->strLogPath, $objLog);

            $result = $this->FrmKanrSyukei->fncSiwakeErrPrintSelect($postData);

            if (!$result['result']) {
                $result['result'] = FALSE;
                $result['MsgId'] = 'E9999';
                throw new \Exception($result['data']);
            } else {
                $result['result'] = TRUE;
                if ($result['row'] != "0") {

                    $result['MsgId'] = '01';
//                $path_rpxTopdf = dirname(__DIR__);
                $path_rpxTopdf = '/var/www/html/gdmz/cake/src/Controller/R4';
                    include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                    $tmpPdfName = "rptSiwakeErrList";
                    include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                    $tmp_data = array();
                    $rpx_file_names = array();
                    $rpx_file_names[$tmpPdfName] = $data_fields_rptSiwakeErrList;
                    array_push($tmp_data, $result['data']);
                    $tmp = array();
                    $datas = array();
                    $tmp["data"] = $tmp_data;
                    $tmp["mode"] = "3";
                    $datas[$tmpPdfName] = $tmp;
                    $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                    $pdfPath = $obj->to_pdf2();
                    $result['path'] = $pdfPath;
                    throw new \Exception("");
                }
            }

        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncAction()
    {

        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
            'path1' => '',
            'path2' => '',
            'path3' => '',
            'path4' => '',
            'MsgId' => ''
        );

        $postData = $_POST['data']['request'];
        $intState = 0;
        $strRdoValue = "";
        try {
            //LOG出力ﾊﾟｽを取得する
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $this->ClsFncLog->strLogPath = $strPath . "/mnt/temp/" . $this->ClsComFnc->FncGetPath('pprlogpath');
            if ($this->ClsFncLog->strLogPath == "") {
                $this->ClsFncLog->strLogPath = $strPath . "/" . "mnt/temp/log/log.log";
                $tmpPath = substr($this->ClsFncLog->strLogPath, 0, (strripos($this->ClsFncLog->strLogPath, "/")) + 1);
                if (!file_exists($tmpPath)) {
                    mkdir($tmpPath, 0777, TRUE);
                }
            } else {

                $tmpPath = substr($this->ClsFncLog->strLogPath, 0, (strripos($this->ClsFncLog->strLogPath, "/")) + 1);
                if (!file_exists($tmpPath)) {
                    mkdir($tmpPath, 0777, TRUE);
                }
            }

            $this->FrmKanrSyukei = new FrmKanrSyukei();
            if ($postData['checked'] == 'false') {
                $objLog = $this->ClsFncLog->GS_OUTPUTLOG;
                $sysDate = $this->ClsComFnc->FncGetSysDate("y-m-d h:i:s");

                $objLog['strStartDate'] = $sysDate;
                $objLog["strID"] = "当月部署別実績集計";
                $objLog["strState"] = "";
                $this->Session = $this->request->getSession();
                $UPDUSER = $this->Session->read('login_user');
                $blnTranFlg = TRUE;

                $res = $this->FrmKanrSyukei->Do_conn();

                if (!$res['result']) {
                    $result['result'] = FALSE;
                    $result['MsgId'] = 'E9999';
                    throw new \Exception($res['data']);
                }
                $this->FrmKanrSyukei->Do_transaction();

                $res = $this->FncKeiriDataMake($objLog, str_replace("/", "", $postData['YM']), "KanrSyukei");

                //check error
                if (!$res['result']) {
                    $result['result'] = FALSE;
                    $result['MsgId'] = 'E9999';
                    throw new \Exception($res['data']);
                } else {

                    $result1 = $this->FrmKanrSyukei->fncDeleteKanrSyukei(str_replace("/", "", $postData['YM']));
                    if (!$result1['result']) {
                        $result['result'] = FALSE;
                        $result['MsgId'] = 'E9999';
                        throw new \Exception($result1['data']);
                    }

                    $result2 = $this->FrmKanrSyukei->fncWKDelete();
                    if (!$result2['result']) {
                        $result['result'] = FALSE;
                        $result['MsgId'] = 'E9999';
                        throw new \Exception($result2['data']);
                    }

                    $result3 = $this->FrmKanrSyukei->fncKaikeiSelIns(str_replace("/", "", $postData['YM']));
                    if (!$result3['result']) {
                        $result['result'] = FALSE;
                        $result['MsgId'] = 'E9999';
                        throw new \Exception($result3['data']);
                    }
                    //---20151027 li UPD S.
                    //$lngKaikeiCnt = $result3['number_of_rows'];
                    $lngKaikeiCnt = $result3['number_of_rows'];
                    //---20151027 li UPD E.

                    $result4 = $this->FrmKanrSyukei->fncFurikaeSelIns(str_replace("/", "", $postData['YM']));
                    if (!$result4['result']) {
                        $result['result'] = FALSE;
                        $result['MsgId'] = 'E9999';
                        throw new \Exception($result4['data']);
                    }
                    //---20151027 li UPD S.
                    // $lngFrikaeCnt = $result4['number_of_rows'];
                    $lngFrikaeCnt = $result4['number_of_rows'];
                    //---20151027 li UPD E.

                    if ($lngKaikeiCnt == 0 && $lngFrikaeCnt == 0) {
                        $result['result'] = FALSE;
                        $result['MsgId'] = 'I0001';
                        throw new \Exception("");
                    }

                    //ﾜｰｸﾃｰﾌﾞﾙに差額データをINSERT
                    $result5 = $this->FrmKanrSyukei->fncWKHkanrsyukeiZanIns();

                    if (!$result5['result']) {
                        $result['result'] = FALSE;
                        $result['MsgId'] = 'E9999';
                        throw new \Exception($result5['data']);
                    }

                    // 営業所人員当データ作成
                    $result6 = $this->FrmKanrSyukei->fncJininAtariIns(str_replace("/", "", $postData['YM']));

                    if (!$result6['result']) {
                        $result['result'] = FALSE;
                        $result['MsgId'] = 'E9999';
                        throw new \Exception($result6['data']);
                    }
                    // print_r($result6);
                    //	本番ﾃｰﾌﾞﾙにINSERT
                    $result7 = $this->FrmKanrSyukei->fncHkanrSyukeiIns();

                    if (!$result7['result']) {
                        $result['result'] = FALSE;
                        $result['MsgId'] = 'E9999';
                        throw new \Exception($result7['data']);
                    }

                    $this->FrmKanrSyukei->Do_commit();

                    $blnTranFlg = FALSE;
                    $result['num1'] = number_format($lngKaikeiCnt);
                    $result['num2'] = number_format($lngFrikaeCnt);
                    $result8 = $this->FrmKanrSyukei->fncGoukeiSelect(str_replace("/", "", $postData['YM']));
                    if (!$result8['result']) {
                        $result['result'] = FALSE;
                        $result['MsgId'] = 'E9999';
                        throw new \Exception($result8['data']);
                    }
                    if ($result8['row'] == 0) {
                        $strKariGKCnt = 0;
                        $strKasiGKCnt = 0;
                    } else {
                        $result['strKariGKCnt'] = number_format($result8['data'][0]['L_KEI']);
                        $result['strKasiGKCnt'] = number_format($result8['data'][0]['R_KEI']);
                    }

                    $this->fncTougetuOutLog($this->ClsFncLog->strLogPath, number_format($lngFrikaeCnt), number_format($lngKaikeiCnt), $result['strKariGKCnt']);

                    $objLog = $this->ClsFncLog->GS_OUTPUTLOG;
                    $sysDate = $this->ClsComFnc->FncGetSysDate("y-m-d h:i:s");

                    $objLog['strEndDate'] = $sysDate;
                    $objLog["strID"] = "当月部署別実績集計";
                    $objLog["strState"] = "";
                    $this->ClsFncLog->fncEndLog($this->ClsFncLog->strLogPath, $objLog);
                    //print
                }

            } else {
                $strRdoValue = "作表のみ";
            }
            $intState = 9;
            $intRptCnt = 0;
            $datas = array();
            $rpx_file_names = array();
            //---20151105 li UPD S.
            // $result9 = $this -> FrmKanrSyukei -> fncPrintSelect(str_replace("/", "", $postData['YM']));
            $result9 = $this->FrmKanrSyukei->fncPrintSelect1(str_replace("/", "", $postData['YM']));
            //---20151105 li UPD E.

            if (!$result9['result']) {
                $result['result'] = FALSE;
                $result['MsgId'] = 'E9999';
                throw new \Exception($result9['data']);
            }

            if ($result9['row'] != 0) {
                //print
//                $path_rpxTopdf = dirname(__DIR__);
                $path_rpxTopdf = '/var/www/html/gdmz/cake/src/Controller/R4';
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                //---20151105 li UPD S.
                // $tmpPdfName = "rptZandakaList";
                $tmpPdfName = "rptZandakaList1";
                //---20151105 li UPD E.
                include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                $tmp_data = array();

                //---20151105 li UPD S.
                // $rpx_file_names[$tmpPdfName] = $data_fields_rptZandakaList;
                $rpx_file_names[$tmpPdfName] = $data_fields_rptZandakaList1;
                //---20151105 li UPD E.
                //---20151105 li DEL S.
                // for ($i = 0; $i <= 99; $i++)
                // {
                // $result9['data'][$i] = $data_fields_rptZandakaList;
                // }
                //---20151105 li DEL E.
                $ZEN_ZAN_TOTAL = 0;
                $TOU_GK_TOTAL = 0;
                $TAISYOU_GK_TOTAL = 0;
                $ZEN_GK_TOTAL = 0;
                $KINRI_GK_TOTAL = 0;
                $NISSU_TOTAL = 0.0;

                foreach ((array) $result9['data'] as $key => $value) {
                    $ZEN_ZAN_TOTAL = $ZEN_ZAN_TOTAL + $value['ZEN_ZAN'];
                    $TOU_GK_TOTAL = $TOU_GK_TOTAL + $value['TOU_GK'];
                    $TAISYOU_GK_TOTAL = $TAISYOU_GK_TOTAL + $value['TAISYOU_GK'];
                    $ZEN_GK_TOTAL = $ZEN_GK_TOTAL + $value['ZEN_GK'];
                    $KINRI_GK_TOTAL = $KINRI_GK_TOTAL + $value['KINRI_GK'];

                }
                //20160414 YIN  INS S
                $NISSU_TOTAL = $TOU_GK_TOTAL == 0 ? 0 : round(($ZEN_GK_TOTAL * 30) / $TOU_GK_TOTAL, 1);
                //20160414 YIN  INS E

                foreach ((array) $result9['data'] as $key => $value) {
                    $result9['data'][$key]['ZEN_ZAN_TOTAL'] = $ZEN_ZAN_TOTAL;
                    $result9['data'][$key]['TOU_GK_TOTAL'] = $TOU_GK_TOTAL;
                    $result9['data'][$key]['TAISYOU_GK_TOTAL'] = $TAISYOU_GK_TOTAL;
                    $result9['data'][$key]['ZEN_GK_TOTAL'] = $ZEN_GK_TOTAL;
                    $result9['data'][$key]['KINRI_GK_TOTAL'] = $KINRI_GK_TOTAL;
                    $result9['data'][$key]['NISSU_TOTAL'] = $NISSU_TOTAL;
                }

                array_push($tmp_data, $result9['data']);
                $tmp1 = array();

                $tmp1["data"] = $tmp_data;
                $tmp1["mode"] = "3";
                $datas[$tmpPdfName] = $tmp1;

                $intRptCnt = $intRptCnt + 1;

            }
            //---20151105 li INS S.
            $result92 = $this->FrmKanrSyukei->fncPrintSelect2(str_replace("/", "", $postData['YM']));
            if (!$result92['result']) {
                $result['result'] = FALSE;
                $result['MsgId'] = 'E9999';
                throw new \Exception($result92['data']);
            }

            if ($result92['row'] != 0) {
//                $path_rpxTopdf = dirname(__DIR__);
                $path_rpxTopdf = '/var/www/html/gdmz/cake/src/Controller/R4';

                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                $tmpPdfName = "rptZandakaList2";
                include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                $tmp_data = array();

                $rpx_file_names[$tmpPdfName] = $data_fields_rptZandakaList2;

                $ZEN_ZAN_TOTAL = 0;
                $TOU_GK_TOTAL = 0;
                $TAISYOU_GK_TOTAL = 0;
                $ZEN_GK_TOTAL = 0;
                $KINRI_GK_TOTAL = 0;
                foreach ((array) $result92['data'] as $key => $value) {
                    $ZEN_ZAN_TOTAL = $ZEN_ZAN_TOTAL + $value['ZEN_ZAN'];
                    $TOU_GK_TOTAL = $TOU_GK_TOTAL + $value['TOU_GK'];
                    $TAISYOU_GK_TOTAL = $TAISYOU_GK_TOTAL + $value['TAISYOU_GK'];
                    $ZEN_GK_TOTAL = $ZEN_GK_TOTAL + $value['ZEN_GK'];
                    $KINRI_GK_TOTAL = $KINRI_GK_TOTAL + $value['KINRI_GK'];
                }

                foreach ((array) $result92['data'] as $key => $value) {
                    $result92['data'][$key]['ZEN_ZAN_TOTAL'] = $ZEN_ZAN_TOTAL;
                    $result92['data'][$key]['TOU_GK_TOTAL'] = $TOU_GK_TOTAL;
                    $result92['data'][$key]['TAISYOU_GK_TOTAL'] = $TAISYOU_GK_TOTAL;
                    $result92['data'][$key]['ZEN_GK_TOTAL'] = $ZEN_GK_TOTAL;
                    $result92['data'][$key]['KINRI_GK_TOTAL'] = $KINRI_GK_TOTAL;
                }

                array_push($tmp_data, $result92['data']);
                $tmp1 = array();
                $tmp1["data"] = $tmp_data;
                $tmp1["mode"] = "3";
                $datas[$tmpPdfName] = $tmp1;

                $intRptCnt = $intRptCnt + 1;
            }
            //---20151105 li INS E.
            //---20151027 li DEL S.
            //仮データ1　start
            // else
            // {
            // $path_rpxTopdf = dirname(__DIR__);
            // include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
            // $tmpPdfName = "rptZandakaList";
            // include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
            // $tmp_data = array();
            // $rpx_file_names[$tmpPdfName] = $data_fields_rptZandakaList;
            //
            // for ($i = 0; $i <= 99; $i++)
            // {
            // $result9['data'][$i] = $data_fields_rptZandakaList;
            // }
            // $ZEN_ZAN_TOTAL = 0;
            // $TOU_GK_TOTAL = 0;
            // $TAISYOU_GK_TOTAL = 0;
            // $ZEN_GK_TOTAL = 0;
            // $KINRI_GK_TOTAL = 0;
            // $NISSU_TOTAL = 0;
            // foreach ($result9['data'] as $key => $value)
            // {
            // $ZEN_ZAN_TOTAL = $ZEN_ZAN_TOTAL + $value['ZEN_ZAN'];
            // $TOU_GK_TOTAL = $TOU_GK_TOTAL + $value['TOU_GK'];
            // $TAISYOU_GK_TOTAL = $TAISYOU_GK_TOTAL + $value['TAISYOU_GK'];
            // $ZEN_GK_TOTAL = $ZEN_GK_TOTAL + $value['ZEN_GK'];
            // $KINRI_GK_TOTAL = $KINRI_GK_TOTAL + $value['KINRI_GK'];
            // $NISSU_TOTAL = $NISSU_TOTAL + $value['NISSU'];
            // }
            // for ($i = 0; $i <= 99; $i++)
            // {
            // $result9['data'][$i]['ZEN_ZAN_TOTAL'] = $ZEN_ZAN_TOTAL;
            // $result9['data'][$i]['TOU_GK_TOTAL'] = $TOU_GK_TOTAL;
            // $result9['data'][$i]['TAISYOU_GK_TOTAL'] = $TAISYOU_GK_TOTAL;
            // $result9['data'][$i]['ZEN_GK_TOTAL'] = $ZEN_GK_TOTAL;
            // $result9['data'][$i]['KINRI_GK_TOTAL'] = $KINRI_GK_TOTAL;
            // $result9['data'][$i]['NISSU_TOTAL'] = $NISSU_TOTAL;
            // }
            //
            // array_push($tmp_data, $result9['data']);
            // $tmp1 = array();
            // $tmp1["data"] = $tmp_data;
            // $tmp1["mode"] = "3";
            // $datas[$tmpPdfName] = $tmp1;
            //
            // $intRptCnt = $intRptCnt + 1;
            //
            // }
            //仮データ1　end
            //---20151027 li DEL E.
            $result10 = $this->FrmKanrSyukei->fncKijyunPrintSelect(str_replace("/", "", $postData['YM']));
            if (!$result10['result']) {
                $result['result'] = FALSE;
                $result['MsgId'] = 'E9999';
                throw new \Exception($result10['data']);
            }

            if ($result10['row'] != 0) {

//                $path_rpxTopdf = dirname(__DIR__);
                $path_rpxTopdf = '/var/www/html/gdmz/cake/src/Controller/R4';
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                $tmpPdfName = "rptKijyunList";
                include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';

                $tmp_data = array();

                $rpx_file_names[$tmpPdfName] = $data_fields_rptKijyunList;
                array_push($tmp_data, $result10['data']);
                $tmp2 = array();

                $tmp2["data"] = $tmp_data;
                $tmp2["mode"] = "3";
                $datas[$tmpPdfName] = $tmp2;

                $intRptCnt = $intRptCnt + 1;
            }
            //---20151027 li DEL S.
            //仮データ2　startse
            // {
            //
            // // $path_rpxTopdf = dirname(__DIR__);
            // // include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
            // // $tmpPdfName = "rptKijyunList";
            // // include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
            // // $tmp_data = array();
            // // $rpx_file_names[$tmpPdfName] = $data_fields_rptKijyunList;
            // // for ($i = 0; $i <= 99; $i++)
            // // {
            // // $result10['data'][$i] = $data_fields_rptKijyunList;
            // // }
            // //
            // // array_push($tmp_data, $result10['data']);
            // // $tmp2 = array();
            // // $tmp2["data"] = $tmp_data;
            // // $tmp2["mode"] = "3";
            // // $datas[$tmpPdfName] = $tmp2;
            // //
            // // $intRptCnt = $intRptCnt + 1;
            // }
            // //仮データ2　end
            //---20151027 li DEL E.
            //---20151027 li UPD S.
            //$result11 = $this -> FrmKanrSyukei -> fncKijyunUnmachiPrintSelect(str_replace("/", "", $postData['YM']));
            $result11 = $this->FrmKanrSyukei->fncKijyunUnmachiPrintSelectNew(str_replace("/", "", $postData['YM']));
            //---20151027 li UPD E.
            if (!$result11['result']) {
                $result['result'] = FALSE;
                $result['MsgId'] = 'E9999';
                throw new \Exception($result11['data']);
            }

            if ($result11['row'] != 0) {

//                $path_rpxTopdf = dirname(__DIR__);
                $path_rpxTopdf = '/var/www/html/gdmz/cake/src/Controller/R4';
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                //---20151027 li UPD S.
                // $tmpPdfName = "rptKijyunUnmachiList";
                $tmpPdfName = "rptKijyunUnmachiListNew";
                //---20151027 li UPD E.
                include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                $tmp_data = array();
                //---20151027 li UPD S.
                // $rpx_file_names[$tmpPdfName] = $data_fields_rptKijyunUnmachiList;
                // array_push($tmp_data, $result11['data']);
                $rpx_file_names[$tmpPdfName] = $data_fields_rptKijyunUnmachiListNew;
                foreach ((array) $result11['data'] as $key => $value) {
                    $result11['data'][$key]['CNT'] = $result11['row'] . '件';
                }
                //---20151027 li UPD E.
                array_push($tmp_data, $result11['data']);
                $tmp3 = array();
                $tmp3["data"] = $tmp_data;
                $tmp3["mode"] = "3";
                $datas[$tmpPdfName] = $tmp3;

                $intRptCnt = $intRptCnt + 1;
            }
            //---20151027 li INS S.
            $result13 = $this->FrmKanrSyukei->fncKijyunUnmachiPrintSelectOld(str_replace("/", "", $postData['YM']));
            if (!$result13['result']) {
                $result['result'] = FALSE;
                $result['MsgId'] = 'E9999';
                throw new \Exception($result13['data']);
            }

            if ($result13['row'] != 0) {

                //$path_rpxTopdf = dirname(__DIR__);
                $path_rpxTopdf = '/var/www/html/gdmz/cake/src/Controller/R4';

                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                $tmpPdfName = "rptKijyunUnmachiListOld";
                include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                $tmp_data = array();
                $rpx_file_names[$tmpPdfName] = $data_fields_rptKijyunUnmachiListOld;
                foreach ((array) $result13['data'] as $key => $value) {
                    $result13['data'][$key]['CNT'] = $result13['row'] . '件';
                }
                array_push($tmp_data, $result13['data']);
                $tmp5 = array();
                $tmp5["data"] = $tmp_data;
                $tmp5["mode"] = "3";
                $datas[$tmpPdfName] = $tmp5;

                $intRptCnt = $intRptCnt + 1;
            }
            //---20151027 li INS E.
            //---20151027 li DEL S.
            //仮データ3　start
            // else
            // {
            //
            // }
            //仮データ3　end
            //---20151027 li DEL E.

            $lngOutCnt = 0;
            $lngOutCnt = $lngOutCnt + (int) $result11['row'];

            //資産残高未登録エラーリスト
            $result12 = $this->FrmKanrSyukei->fncZanErrPrintSelect(str_replace("/", "", $postData['YM']));
            if (!$result12['result']) {
                $result['result'] = FALSE;
                $result['MsgId'] = 'E9999';
                throw new \Exception($result12['data']);
            }

            if ($result12['row'] != 0) {
                //print
//                $path_rpxTopdf = dirname(__DIR__);
                $path_rpxTopdf = '/var/www/html/gdmz/cake/src/Controller/R4';

                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                $tmpPdfName = "rptZanErrList";
                include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                $tmp_data = array();

                $rpx_file_names[$tmpPdfName] = $data_fields_rptZanErrList;
                array_push($tmp_data, $result12['data']);
                $tmp4 = array();

                $tmp4["data"] = $tmp_data;
                $tmp4["mode"] = "3";
                $datas[$tmpPdfName] = $tmp4;

                $intRptCnt = $intRptCnt + 1;
            }
            //---20151027 li DEL S.
            //仮データ4　start
            // else
            // {
            // $path_rpxTopdf = dirname(__DIR__);
            // include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
            // $tmpPdfName = "rptZanErrList";
            // include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
            // $tmp_data = array();
            // $rpx_file_names[$tmpPdfName] = $data_fields_rptZanErrList;
            //
            // for ($i = 0; $i <= 99; $i++)
            // {
            // $result12['data'][$i] = $data_fields_rptZanErrList;
            // }
            //
            // array_push($tmp_data, $result12['data']);
            // $tmp4 = array();
            //
            // $tmp4["data"] = $tmp_data;
            // $tmp4["mode"] = "3";
            // $datas[$tmpPdfName] = $tmp4;
            //
            // $intRptCnt = $intRptCnt + 1;
            // }
            //仮データ4　end
            //---20151027 li DEL E.

            if ($intRptCnt == 0) {

                $result['result'] = FALSE;
                $result['MsgId'] = 'I0001';
                throw new \Exception("");

            }

            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf2();
            $result['path4'] = $pdfPath;
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
        }

        if ($intState != 0) {

            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmKanrSyukei", $intState, $lngOutCnt, $postData['YM'], $strRdoValue);

        }

        $this->fncReturn($result);
    }

    public function fncGetSaiban($strID)
    {
        $Do_Excute = "";
        $strMsg = "";
        $strNO = "";
        $objDr = "";
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );

        try {
            $Do_Excute = $this->FrmKanrSyukei->fncGetSaibanSelect($strID);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data']);
            }
            $objDr = $Do_Excute['data'];
            //該当データあり
            if (count((array) $objDr) > 0) {
                //---20151027 li UPD S.
                // $strNO = $this -> ClsComFnc -> FncNz($objDr["SEQNO"]);
                $strNO = $this->ClsComFnc->FncNz($objDr[0]["SEQNO"]);
                //---20151027 li UPD E.
            } else {
                $strNO = 1;
            }
            $Do_Excute = $this->FrmKanrSyukei->fncGetSaibanUpdate($strID);
            //objDr = clsComDB.Fnc_DataReader(strSQL.ToString)
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data']);
            }
            $result['result'] = TRUE;
            $result['data'] = $strNO;
        } catch (\Exception $e) {
            //---20151027 li UPD S.
            // $strMsg = "clsKeiriDataMake" . "\r\n" . "fncGetSaiban " . "\r\n" . $e -> getMessage();
            $strMsg = "FrmKanrSyukeiController" . "\r\n" . "fncGetSaiban " . "\r\n" . $e->getMessage();
            //---20151027 li UPD E.
            $result['result'] = FALSE;
            $result['data'] = $strMsg;
        }

        return $result;
    }

    public function fncKeiriDataMake(&$objLog, $strSyoriDT, $strUpdPro, $blnDispMsg = TRUE)
    {
        $strErrMsg = "";
        // $lngKaikeiCnt = "";
        $strDENNO = "";
        $Do_Excute = "";
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );

        try {
            //当月分売上台数振替ﾃﾞｰﾀを削除
            $Do_Excute = $this->FrmKanrSyukei->fncFurikaeDelete($strSyoriDT, "SC");

            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }

            //新中売上ﾃﾞｰﾀより売上台数振替ﾃﾞｰﾀをINSERT
            //2006/12/11 UPD 更新ユーザ、更新ﾏｼﾝ、更新プログラムを引数に追加
            $Do_Excute = $this->FrmKanrSyukei->fncFURIDAISUInsert($strSyoriDT, $strUpdPro);

            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }
            //当月分売上基準価格会計ﾃﾞｰﾀを削除
            $y = substr($strSyoriDT, 0, 4);
            //---20151105 li UPD S.
            // $m = substr($strSyoriDT, 5, 2);
            $m = substr($strSyoriDT, 4, 2);
            //---20151105 li UPD E.
            $d = date("t", strtotime($y . '-' . $m));
            $ymd = $y . $m . $d;
            //20140221 luchao 这个地方有可能出现问题，开发的时候请仔细对应
            $Do_Excute = $this->FrmKanrSyukei->fncKaikeiDelete($strSyoriDT . "01", $ymd, "SC");
            //20140221 luchao 这个地方有可能出现问题，开发的时候请仔细对应
            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }

            $Do_Excute = $this->fncGetSaiban("KEIRI");
            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 2);
            }
            $strDENNO = $strSyoriDT . str_pad(round($Do_Excute['data']), 5, "0", STR_PAD_LEFT);

            //新中売上ﾃﾞｰﾀより基準価格会計ﾃﾞｰﾀをINSERT
            //2006/12/11 UPD 更新ユーザ、更新ﾏｼﾝ、更新プログラムを引数に追加 START
            $Do_Excute = $this->FrmKanrSyukei->fncKijyunKaikeiInsert($strSyoriDT, $strDENNO, $strUpdPro);
            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }
            $Do_Excute = $this->FrmKanrSyukei->fncKijyunKaikeiInsert2($strSyoriDT, $strDENNO, $strUpdPro);
            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }
            $Do_Excute = $this->FrmKanrSyukei->fncKijyunKaikeiInsert3($strSyoriDT, $strDENNO, $strUpdPro);
            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }
            $Do_Excute = $this->FrmKanrSyukei->fncKijyunKaikeiInsert4($strSyoriDT, $strDENNO, $strUpdPro);
            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }
            $Do_Excute = $this->FrmKanrSyukei->fncKijyunKaikeiInsert5($strSyoriDT, $strDENNO, $strUpdPro);
            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }
            //当月残高データ削除
            $Do_Excute = $this->FrmKanrSyukei->fncKNRZANDelete($strSyoriDT);
            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }

            //当月残高金利会計ﾃﾞｰﾀを削除
            //20140221 luchao 这个地方有可能出现问题，开发的时候请仔细对应
            $Do_Excute = $this->FrmKanrSyukei->fncKaikeiDelete($strSyoriDT . "01", $ymd, "ZN");
            //20140221 luchao 这个地方有可能出现问题，开发的时候请仔细对应
            if (!$Do_Excute['result']) {

                throw new \Exception($Do_Excute['data'], 1);
            }
            //当月資産残高金利データをINSERT
            $Do_Excute = $this->FrmKanrSyukei->fncSSKNRZANInsert($strSyoriDT, $strUpdPro);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 1);
            }

            $Do_Excute = $this->fncGetSaiban("KEIRI");
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 2);
            }
            $strDENNO = $strSyoriDT . str_pad(round($Do_Excute['data']), 5, "0", STR_PAD_LEFT);

            //当月資産残高金利会計データをINSERT
            $Do_Excute = $this->FrmKanrSyukei->fncSSKNRKaikeiInsert($strSyoriDT, $strDENNO, $strUpdPro);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 1);
            }

            //当月一般売掛金残高データをINSERT
            //---20151109 li UPD S.
            // $Do_Excute = $this -> FrmKanrSyukei -> fncIPKNRZANInsert($strSyoriDT, $strDENNO, $strUpdPro);
            $Do_Excute = $this->FrmKanrSyukei->fncIPKNRZANInsert($strSyoriDT, $strUpdPro);
            //---20151109 li UPD E.
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 1);
            }
            //---20151027 li INS S.
            $Do_Excute = $this->fncGetSaiban("KEIRI");
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 2);
            }
            $strDENNO = $strSyoriDT . str_pad(round($Do_Excute['data']), 5, "0", STR_PAD_LEFT);
            //---20151027 li INS E.

            //当月一般売掛金残高金利会計データをINSERT
            $Do_Excute = $this->FrmKanrSyukei->fncIPKNRKaikeiInsert($strSyoriDT, $strDENNO, $strUpdPro);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 1);
            }

            $Do_Excute = $this->fncGetSaiban("KEIRI");
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 2);
            }
            $strDENNO = $strSyoriDT . str_pad(round($Do_Excute['data']), 5, "0", STR_PAD_LEFT);

            //当月ペナルティ会計ﾃﾞｰﾀを削除
            //20140221 luchao 这个地方有可能出现问题，开发的时候请仔细对应
            $Do_Excute = $this->FrmKanrSyukei->fncKaikeiDelete($strSyoriDT . "01", $ymd, "PN");
            //20140221 luchao 这个地方有可能出现问题，开发的时候请仔细对应
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 1);
            }

            //当月当月ペナルティ会計ﾃﾞｰﾀをINSERT(新車売掛金)
            $Do_Excute = $this->FrmKanrSyukei->fncPenaKaikeiInsert($strSyoriDT, $strDENNO, $strUpdPro);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 1);
            }

            $Do_Excute = $this->fncGetSaiban("KEIRI");

            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 2);
            }

            $strDENNO = $strSyoriDT . str_pad(round($Do_Excute['data']), 5, "0", STR_PAD_LEFT);

            //当月ペナルティ会計データをINSERT(中古売掛金)
            $Do_Excute = $this->FrmKanrSyukei->fncPenaKaikeiChukoInsert($strSyoriDT, $strDENNO, $strUpdPro);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data'], 1);
            }

            //2006/12/11 UPD 更新ユーザ、更新ﾏｼﾝ、更新プログラムを引数に追加 END
            $result['result'] = TRUE;
            //-------------------------
        } catch (\Exception $e) {

            if ($e->getCode() == 1) {
                //---20151027 li UPD S.
                //$strErrMsg = "ClsKeiriDataMake" . "\r\n" . "FncKeiriDataMake" . "\r\n" . $e -> getMessage();
                $strErrMsg = "FrmKanrSyukeiController" . "\r\n" . "FncKeiriDataMake" . "\r\n" . $e->getMessage();
                //---20151027 li UPD E.
            } else {
                $strErrMsg = $e->getMessage();
            }

            if ($blnDispMsg) {
                $result['data'] = $strErrMsg;
            }
            $objLog['strErrMsg'] = $strErrMsg;
            $objLog['lngCount'] = -1;
            $result['result'] = FALSE;
        }
        return $result;
    }

    function fncTougetuOutLog($strFileNM, $lngFrikaeCnt, $lngKaikeiCnt, $strKariGKCnt)
    {
        // //LOG出力ﾊﾟｽを取得する
        // $strPath = dirname(dirname(dirname(dirname(__FILE__))));
        // $this -> ClsFncLog -> strLogPath = $strPath . "/mnt/temp/" . $this -> ClsComFnc -> FncGetPath('pprlogpath');
        // if ($this -> ClsFncLog -> strLogPath == "")
        // {
        // $this -> ClsFncLog -> strLogPath = $strPath . "/" . "mnt/temp/log/log.log";
        // $tmpPath = substr($this -> ClsFncLog -> strLogPath, 0, (strripos($this -> ClsFncLog -> strLogPath, "/")) + 1);
        // if (!file_exists($tmpPath))
        // {
        // mkdir($tmpPath, 0777, TRUE);
        // }
        // }
        // else
        // {
        //
        // $tmpPath = substr($this -> ClsFncLog -> strLogPath, 0, (strripos($this -> ClsFncLog -> strLogPath, "/")) + 1);
        // if (!file_exists($tmpPath))
        // {
        // mkdir($tmpPath, 0777, TRUE);
        // }
        // }

        $strOut = "";
        $this->objSw = "";
        //インスタンス作成
        $this->objSw = fopen($strFileNM, 'a+');
        $strOut = $strOut . "     ";
        $strOut = $strOut . "振替データ件数 ";
        $strOut = $strOut . $lngFrikaeCnt . "件 ";
        $strOut = $strOut . "\r\n ";
        fwrite($this->objSw, $strOut);
        $strOut = "";
        $strOut = $strOut . "     ";
        $strOut = $strOut . "会計データ件数 ";
        $strOut = $strOut . $lngKaikeiCnt . "件 ";
        $strOut = $strOut . "\r\n ";
        fwrite($this->objSw, $strOut);

        //*****借方金額合計*****
        $strOut = "";
        $strOut = $strOut . "     ";
        $strOut = $strOut . "借方金額合計 ";
        $strOut = $strOut . $strKariGKCnt . "円 ";
        $strOut = $strOut . "\r\n ";
        fwrite($this->objSw, $strOut);

        //*****貸方金額合計*****
        $strOut = "";
        $strOut = $strOut . "     ";
        $strOut = $strOut . "貸方金額合計 ";
        $strOut = $strOut . $strKariGKCnt . "円 ";
        $strOut = $strOut . "\r\n ";
        fwrite($this->objSw, $strOut);
        fclose($this->objSw);
    }

}