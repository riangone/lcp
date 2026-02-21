<?php
/**
 *
 * ラインマスタメンテナンス
 *
 * @alias FrmLineMst
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                       FCSDL  
 * 20151111           #2115                   BUG                             Yuanjh 
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSCUriageMake;

class FrmSCUriageMakeController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSCUriageMake = "";
    public $UPDAPP = "SCUriageMake";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsCreateCsv');
    }
    public function index()
    {
        $this->render('index', 'FrmSCUriageMake_layout');
    }

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：FrmSCUriageMake_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    public function frmSCUriageMakeLoad()
    {
        $result = [];
        try {
            $this->FrmSCUriageMake = new FrmSCUriageMake();
            $result = $this->FrmSCUriageMake->fncSelect();
            if (!$result['result']) {
                throw new \Exception($result['data']);

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //前回抽出条件表示
    public function fncGetCTLInfo()
    {
        $result = [];
        try {
            $this->FrmSCUriageMake = new FrmSCUriageMake();
            $result = $this->FrmSCUriageMake->fncGetCTLInfo();
            if (!$result['result']) {
                throw new \Exception($result['data']);

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：実行ﾎﾞﾀﾝ押下時
    //関 数 名：cmdAction
    //引    数：無し
    //戻 り 値：無し
    //処理説明：売上データ作成処理
    //**********************************************************************
    public function cmdAction()
    {

        $strRtn = "";
        $blnRtn = TRUE;
        $objLog = $this->ClsCreateCsv->GS_OUTPUTLOG;
        $strErrMsg = "";
        $strSQL = "";
        $cboUCNO = "";
        $cboDateFrom = "";
        $cboDateTo = "";
        $selradio = "";
        $frm = array();
        $rtnmsg = array();
        $result = array();
        $blnConn = FALSE;
        $Do_conn = "";
        $Do_conn1 = "";
        $tmpmark = TRUE;
        try {
            $cboUCNO = $_POST['data']['cboUCNO'];
            $cboDateFrom = $_POST['data']['cboDateFrom'];
            $cboDateTo = $_POST['data']['cboDateTo'];
            $selradio = $_POST['data']['radio'];
            $frm['rdoFlag'] = $selradio;
            $frm['lblCntNew'] = "";
            $frm['lblCntUsed'] = "";
            $frm['lblCntNewChg'] = "";
            $frm['lblCntUsedChg'] = "";

            //LOG出力ﾊﾟｽを取得する
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));

            $this->ClsCreateCsv->strLogPath = $this->ClsComFnc->FncGetPath("pprlogpath");
            if ($this->ClsCreateCsv->strLogPath == "") {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/log/LOG.Log";
            } else {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/" . $this->ClsCreateCsv->strLogPath;
            }
            if (file_exists($this->ClsCreateCsv->strLogPath) == FALSE) {
                $Logpath = dirname($this->ClsCreateCsv->strLogPath);
                mkdir($Logpath);
            }
            //ErrLOG出力ﾊﾟｽを取得する
            $this->ClsCreateCsv->strErrLogPath = $this->ClsComFnc->FncGetPath("SCURICNVERR");
            if ($this->ClsCreateCsv->strErrLogPath == "") {
                $this->ClsCreateCsv->strErrLogPath = $strPath . "/mnt/temp/log/SCURICNV.Log";
            }

            //ログ出力先をｾｯﾄ
            $this->ClsCreateCsv->strLogName = $this->ClsCreateCsv->strLogPath;
            //ｴﾗｰログ出力先をｾｯﾄ
            $this->ClsCreateCsv->strErrLogName = $this->ClsCreateCsv->strErrLogPath;
            //既存ｴﾗｰログを削除
            if (file_exists($this->ClsCreateCsv->strErrLogName)) {
                unlink($this->ClsCreateCsv->strErrLogName);
            }
            //構造体に格納(LOG)
            $objLog['strID'] = "新車・中古車売上データ作成";
            $objLog['strStartDate'] = $this->ClsComFnc->FncGetSysDate("Y-m-d H:i:s");
            //開始LOG出力
            $this->ClsCreateCsv->fncStartLog($this->ClsCreateCsv->strLogName, $objLog);
            $objLog['strErrMsg'] = "新車・中古車売上データ作成" . "　開始" . $this->ClsComFnc->FncGetSysDate("Y-m-d H:i:s");
            $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $objLog);
            $objLog['strErrMsg'] = "  処理年月 = " . $cboUCNO . "  開始更新年月日 = " . $cboDateFrom . "  終了更新年月日 = " . $cboDateTo;
            $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $objLog);
            $this->FrmSCUriageMake = new FrmSCUriageMake();
            $Do_conn = $this->FrmSCUriageMake->Do_conn();
            if (!$Do_conn['result']) {
                throw new \Exception($Do_conn['data']);

            }
            $Do_conn1 = $this->ClsCreateCsv->ClsCreateCsv->Do_conn();
            if (!$Do_conn1['result']) {
                throw new \Exception($Do_conn1['data']);

            }
            $this->FrmSCUriageMake->Do_transaction();
            $this->ClsCreateCsv->ClsCreateCsv->Do_transaction();
            $blnConn = TRUE;
            $blnTran = TRUE;

            //CSVデータを作成する
            $objLog['lngCount'] = 0;
            $objLog['ErrCount'] = 0;
            $objLog['ChkCount'] = 0;

            $rtnmsg = $this->ClsCreateCsv->fncSCURICreate2($objLog, $frm, $this->UPDAPP, str_replace("/", "", $cboUCNO), str_replace("/", "", $cboDateFrom), str_replace("/", "", $cboDateTo));
            // print_r($rtnmsg);
            // print_r($objLog);
            // return;
            $blnRtn = $rtnmsg['result'];
            $result['data'] = "";
            if ($objLog['ErrCount'] > 0) {

                $result['data'] = 1;
                $result['strErrLogName'] = $this->ClsCreateCsv->strErrLogName;
            } elseif ($blnRtn == TRUE) {
                if (isset($rtnmsg['frmData'])) {
                    if ((int) $rtnmsg['frmData']['lblCnt']['NewDataA'] + (int) $rtnmsg['frmData']['lblCnt']['NewChangeDataA'] + (int) $rtnmsg['frmData']['lblCnt']['UsedDataA'] + (int) $rtnmsg['frmData']['lblCnt']['UsedChangeDataA'] == 0) {

                        $result['data'] = 2;
                        $tmpmark = FALSE;
                    }
                }
            } elseif ($blnRtn == FALSE) {
                //ｴﾗｰLOG出力
                $this->ClsCreateCsv->fncErrLog($this->ClsCreateCsv->strLogName, $objLog);

                $result['data'] = 3;
                $tmpmark = FALSE;
            }
            //終了LOG出力
            $objLog['strEndDate'] = $this->ClsComFnc->FncGetSysDate("Y-m-d H:i:s");
            if ($result['data'] == 3) {
                $objLog['strState'] = "NG";
            } else {
                $objLog['strState'] = "OK";
            }
            $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $objLog);
            if ($tmpmark == TRUE) {
                $result['subErrSpreadShowData'] = $rtnmsg['subErrSpreadShowData'];
                $result['lblCnt'] = $rtnmsg['frmData']['lblCnt'];
                $result['frm1'] = $rtnmsg['frm1'];
                $result['pdfmark'] = FALSE;
                //-----車両情報補完更新-----
                //M27A02(新車在庫情報)からデータを取得している(カー№等)が、該当データが削除されてしまうため
                //条件変更の場合に取得できなくなってしまう。そのための補完更新を行なう。
                $Updresult = $this->FrmSCUriageMake->subSCURIUpdate();
                if ($Updresult['result'] == FALSE) {
                    throw new \Exception($Updresult['data']);
                }

                //-----ｺﾝﾄﾛｰﾙﾏｽﾀ抽出条件更新-----
                $Updresult = $this->FrmSCUriageMake->fncUpdateCTLInfo($cboUCNO, $cboDateFrom, $cboDateTo, $selradio);
                if ($Updresult['result'] == FALSE) {
                    throw new \Exception($Updresult['data']);
                }
                $this->ClsCreateCsv->ClsCreateCsv->Do_commit();
                $this->FrmSCUriageMake->Do_commit();
                $blnTran = FALSE;

                //-------注文書ｴﾗｰﾁｪｯｸﾘｽﾄ印刷処理------
                $PrintSelectResult = $this->FrmSCUriageMake->fncPrintSelect($cboDateFrom, $cboDateTo, $selradio);
                if ($PrintSelectResult['result'] == FALSE) {
                    throw new \Exception($PrintSelectResult['data']);
                }

                if (count((array) $PrintSelectResult['data']) > 0) {

                    //'プレビュー表示
                    $path_rpxTopdf = dirname(__DIR__);
                    include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

                    $rpx_file_names = array();
                    $tmp_data = array();
                    $tmp = array();
                    //--20151111  Yuanjh UPD  S.
                    /*
                    $data = Array(
                        "KIKANF" => "",
                        "KIKANT" => "",
                        "TODAY" => "",
                        "CMN_NO" => "",
                        "UC_NO" => "",
                        "ERR_MSG1" => "",
                        "ERR_MSG2" => "",
                        "ERR_MSG3" => "",
                        "ERR_NO" => "",
                    );
                    */
                    $data = array(
                        "CMN_NO" => "",
                        "UC_NO" => "",
                        "ERR_MSG1" => "",
                        "ERR_MSG2" => "",
                        "ERR_MSG3" => "",
                        "ERR_NO" => "",
                    );
                    //--20151111  Yuanjh UPD  E.
                    //******Grouping data.  start********************
                    $PrintSelectResult1 = array();
                    $tempArr = array();
                    $currentArr = array();
                    $lastArr = array();
                    foreach ((array) $PrintSelectResult['data'] as $key => $value) {
                        $currentArr = $value;
                        if ($key == 0) {
                            $lastArr = $currentArr;
                            array_push($PrintSelectResult1, $currentArr);
                        } else {
                            if ($currentArr['CMN_NO'] != $lastArr['CMN_NO'] && $currentArr['UC_NO'] != $lastArr['UC_NO']) {
                                $lastArr = $currentArr;
                                array_push($PrintSelectResult1, $data);
                                array_push($PrintSelectResult1, $currentArr);
                            } else {
                                $lastArr = $currentArr;
                                $currentArr['CMN_NO'] = "";
                                $currentArr['UC_NO'] = "";
                                array_push($PrintSelectResult1, $currentArr);
                            }
                        }
                    }
                    //******Grouping data.  end**********************
                    array_push($tmp_data, $PrintSelectResult1);
                    $tmp["data"] = $tmp_data;
                    $tmp["mode"] = "3";
                    //--20151111  Yuanjh  ADD  S.
                    $tmp["DateFrom"] = $cboDateFrom;
                    $tmp["DateTo"] = $cboDateTo;
                    //--20151111  Yuanjh  ADD  E.
                    $datas["rptScUriageChk"] = $tmp;
                    $rpx_file_names["rptScUriageChk"] = $data;
                    $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                    $pdfPath = $obj->to_pdf2();
                    $result['pdfmark'] = TRUE;
                    $result['pdfpath'] = $pdfPath;
                }
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTran == TRUE) {
            $this->ClsCreateCsv->ClsCreateCsv->Do_rollback();
            $this->FrmSCUriageMake->Do_rollback();
        }
        //DB接続解除
        if ($blnConn == TRUE) {
            $this->ClsCreateCsv->ClsCreateCsv->Do_close();
            $this->FrmSCUriageMake->Do_close();
        }
        $this->fncReturn($result);
    }

}