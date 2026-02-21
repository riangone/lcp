<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20151117           #2275				      BUG                               YIN
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmChumonCSV;

class FrmChumonCSVController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmChumonCSV = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsCreateCsv');
        $this->loadComponent('ClsLogControl');
    }
    public function index()
    {
        $this->render('index', 'FrmChumonCSV_layout');
    }

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：frmChumonCSV_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    public function frmChumonCSVLoad()
    {
        $result = array();
        try {
            $this->FrmChumonCSV = new FrmChumonCSV();
            $result = $this->FrmChumonCSV->fncSelect();
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
    //処 理 名：CSVファイル出力
    //関 数 名：cmdAction
    //引    数：無し
    //戻 り 値：無し
    //処理説明：CSVファイル出力処理
    //**********************************************************************
    public function cmdAction()
    {
        $result = [];
        $strRtn = "";
        $blnRtn = "";
        $objLog = $this->ClsCreateCsv->GS_OUTPUTLOG;
        //ログ構造体
        $strErrMsg = "";
        $strSelDate = "";
        $strSQL = "";
        $strSCKbn = "";
        $rtnmsg = array();
        $objDs = "";

        //ログ管理
        $intState = array(0, 0, 0, 0, 0, 0, 0, 0);
        $lngOutCnt = array(0, 0, 0, 0, 0, 0, 0, 0);
        $strOutFileNM = array("", "", "", "", "", "", "", "");
        $strCboUCNO = "";
        $strCboDateFrom = "";
        $strCboDateTo = "";
        $strRdoValue = "";
        $tmpmark = TRUE;
        try {

            $strCboUCNO = $_POST['data']['cboUCNO'];
            $strCboDateFrom = $_POST['data']['cboDateFrom'];
            $strCboDateTo = $_POST['data']['cboDateTo'];
            $strRdoValue = $_POST['data']['radio'];
            $frm['rdoFlag'] = $strRdoValue;
            $frm['lblCntNew'] = "";
            $frm['lblCntUsed'] = "";
            $frm['lblCntNewChg'] = "";
            $frm['lblCntUsedChg'] = "";

            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //CSV出力ﾌｧｲﾙﾊﾟｽを取得する
            $this->ClsCreateCsv->strNewCsvPath = $this->ClsComFnc->FncGetPath("NewPath");
            $this->ClsCreateCsv->strUsedCsvPath = $this->ClsComFnc->FncGetPath("UserPath");
            $this->ClsCreateCsv->strNewChangeCsvPath = $this->ClsComFnc->FncGetPath("NewChangePath");
            $this->ClsCreateCsv->strUsedChangeCsvPath = $this->ClsComFnc->FncGetPath("UserChangePath");
            //LOG出力ﾊﾟｽを取得する
            $this->ClsCreateCsv->strLogPath = $this->ClsComFnc->FncGetPath("pprlogpath");
            if ($this->ClsCreateCsv->strNewCsvPath == "") {
                //デフォルトのCSV出力先を表示する
                // /src/mnt/temp/NEW/
                $this->ClsCreateCsv->strNewCsvPath = $strPath . "/mnt/temp/NEW/";
            }
            if ($this->ClsCreateCsv->strUsedCsvPath == "") {
                //デフォルトのCSV出力先を表示する
                $this->ClsCreateCsv->strUsedCsvPath = $strPath . "/mnt/temp/USE/";
            }
            if ($this->ClsCreateCsv->strNewChangeCsvPath == "") {
                //デフォルトのCSV出力先を表示する
                $this->ClsCreateCsv->strNewChangeCsvPath = $strPath . "/mnt/temp/NEWCHG/";
            }
            if ($this->ClsCreateCsv->strUsedChangeCsvPath == "") {
                //デフォルトのCSV出力先を表示する
                $this->ClsCreateCsv->strUsedChangeCsvPath = $strPath . "/mnt/temp/USECHG/";
            }
            if ($this->ClsCreateCsv->strLogPath == "") {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/log/LOG.Log";
            } else {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/" . $this->ClsCreateCsv->strLogPath;
            }
            //ﾊﾞｯｸｱｯﾌﾟﾊﾟｽを取得する
            $this->ClsCreateCsv->strBackUpPath = $this->ClsComFnc->FncGetPath("pprbackuppath");
            if ($this->ClsCreateCsv->strBackUpPath == "") {
                $this->ClsCreateCsv->strBackUpPath = $strPath . "/mnt/temp/BACK0908/";
            }
            //LOG出力ﾊﾟｽを取得する
            $this->ClsCreateCsv->strErrLogPath = $this->ClsComFnc->FncGetPath("pprN5200CSVERR");
            if ($this->ClsCreateCsv->strErrLogPath == "") {
                $this->ClsCreateCsv->strErrLogPath = $strPath . "/mnt/temp/log/N5200CSVERR.Log";
            }
            $strOutFileNM[0] = $this->ClsCreateCsv->strNewCsvPath . $this->ClsCreateCsv->strOrderFileName . "11" . "A.CSV";
            $strOutFileNM[1] = $this->ClsCreateCsv->strNewCsvPath . $this->ClsCreateCsv->strOrderFileName . "11" . "B.CSV";
            $strOutFileNM[2] = $this->ClsCreateCsv->strUsedCsvPath . $this->ClsCreateCsv->strOrderFileName . "21" . "A.CSV";
            $strOutFileNM[3] = $this->ClsCreateCsv->strUsedCsvPath . $this->ClsCreateCsv->strOrderFileName . "21" . "B.CSV";
            $strOutFileNM[4] = $this->ClsCreateCsv->strNewChangeCsvPath . $this->ClsCreateCsv->strChangeFileName . "1J" . "A.CSV";
            $strOutFileNM[5] = $this->ClsCreateCsv->strNewChangeCsvPath . $this->ClsCreateCsv->strChangeFileName . "1J" . "B.CSV";
            $strOutFileNM[6] = $this->ClsCreateCsv->strUsedChangeCsvPath . $this->ClsCreateCsv->strChangeFileName . "2J" . "A.CSV";
            $strOutFileNM[7] = $this->ClsCreateCsv->strUsedChangeCsvPath . $this->ClsCreateCsv->strChangeFileName . "2J" . "B.CSV";
            //フォルダ (ディレクトリ) を作成する
            if (file_exists($this->ClsCreateCsv->strNewCsvPath) == FALSE) {
                mkdir($this->ClsCreateCsv->strNewCsvPath);
            }
            if (file_exists($this->ClsCreateCsv->strUsedCsvPath) == FALSE) {
                mkdir($this->ClsCreateCsv->strUsedCsvPath);
            }
            if (file_exists($this->ClsCreateCsv->strNewChangeCsvPath) == FALSE) {
                mkdir($this->ClsCreateCsv->strNewChangeCsvPath);
            }
            if (file_exists($this->ClsCreateCsv->strUsedChangeCsvPath) == FALSE) {
                mkdir($this->ClsCreateCsv->strUsedChangeCsvPath);
            }
            if (file_exists(dirname($this->ClsCreateCsv->strLogPath)) == FALSE) {
                mkdir(dirname($this->ClsCreateCsv->strLogPath));
            }
            if (file_exists($this->ClsCreateCsv->strBackUpPath) == FALSE) {
                mkdir($this->ClsCreateCsv->strBackUpPath);
            }

            //ログ管理対応
            for ($intStateIdx = 0; $intStateIdx <= 7; $intStateIdx++) {
                $intState[$intStateIdx] = 9;
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
            $objLog['strID'] = "売上データチェック(ＣＳＶ作成)";
            $objLog['strStartDate'] = $this->ClsComFnc->FncGetSysDate("Y-m-d H:i:s");
            //開始LOG出力
            $this->ClsCreateCsv->fncStartLog($this->ClsCreateCsv->strLogName, $objLog);
            $objLog['strErrMsg'] = "売上データチェック(ＣＳＶ作成)" . "　開始" . $this->ClsComFnc->FncGetSysDate("Y-m-d H:i:s");
            $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $objLog);
            $objLog['strErrMsg'] = "  処理年月 = " . $strCboUCNO . "  開始更新年月日 = " . $strCboDateFrom . "  終了更新年月日 = " . $strCboDateTo;
            $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $objLog);
            //指定ﾌｫﾙﾀﾞに処理CSVが存在する場合ﾊﾞｯｸｱｯﾌﾟﾌｧｲﾙを作成する
            $this->FncCopyDirectory($this->ClsCreateCsv->strNewCsvPath, $this->ClsCreateCsv->strBackUpPath, TRUE);
            $this->FncCopyDirectory($this->ClsCreateCsv->strUsedCsvPath, $this->ClsCreateCsv->strBackUpPath, TRUE);
            $this->FncCopyDirectory($this->ClsCreateCsv->strNewChangeCsvPath, $this->ClsCreateCsv->strBackUpPath, TRUE);
            $this->FncCopyDirectory($this->ClsCreateCsv->strUsedChangeCsvPath, $this->ClsCreateCsv->strBackUpPath, TRUE);
            //既存データを削除する
            $this->FncDeleteFile($this->ClsCreateCsv->strNewCsvPath);
            $this->FncDeleteFile($this->ClsCreateCsv->strUsedCsvPath);
            $this->FncDeleteFile($this->ClsCreateCsv->strNewChangeCsvPath);
            $this->FncDeleteFile($this->ClsCreateCsv->strUsedChangeCsvPath);

            //***************************************
            $this->FrmChumonCSV = new FrmChumonCSV();
            $blnRtn = TRUE;
            //CSVデータを作成する
            $objLog['lngCount'] = 0;
            $objLog['ErrCount'] = 0;
            $objLog['ChkCount'] = 0;
            // ログ管理
            $rtnmsg = $this->ClsCreateCsv->fncCsvChuCreate($objLog, $frm, str_replace("/", "", $strCboUCNO), str_replace("/", "", $strCboDateFrom), str_replace("/", "", $strCboDateTo), $intState, $lngOutCnt);
            // print_r($rtnmsg);
            // print_r($objLog);
            $blnRtn = $rtnmsg['result'];
            $result['data'] = "";
            if ($objLog['ErrCount'] > 0 || $objLog['ChkCount'] > 0) {
                $objLog['strErrMsg'] = "  エラーデータ件数 = " . ($objLog['ErrCount'] + $objLog['ChkCount']);
                $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $objLog);
                $result['data'] = 1;
                $result['strErrLogName'] = $this->ClsCreateCsv->strErrLogName;
                if ($objLog['ErrCount'] > 0) {
                    $objLog['strErrMsg'] = "売上データチェック(ＣＳＶ作成)" . "　エラーデータが存在する為、処理を中断しました" . $this->ClsComFnc->FncGetSysDate("Y-m-d H:i:s");
                    $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $objLog);
                    $result['data'] = 2;
                    $result['subErrSpreadShowData'] = $rtnmsg['subErrSpreadShowData'];
                    $result['lblCnt'] = $rtnmsg['frmData']['lblCnt'];
                    $result['frm1'] = $rtnmsg['frm1'];
                    $tmpmark = FALSE;
                }
            } elseif ($blnRtn == TRUE) {
                if (isset($rtnmsg['frmData'])) {
                    if ((int) $rtnmsg['frmData']['lblCnt']['NewDataA'] + (int) $rtnmsg['frmData']['lblCnt']['NewChangeDataA'] + (int) $rtnmsg['frmData']['lblCnt']['UsedDataA'] + (int) $rtnmsg['frmData']['lblCnt']['UsedChangeDataA'] == 0) {
                        $result['data'] = 3;
                        $tmpmark = FALSE;
                    }
                }
            } elseif ($blnRtn == FALSE) {
                //ｴﾗｰLOG出力
                $this->ClsCreateCsv->fncErrLog($this->ClsCreateCsv->strLogName, $objLog);
                $result['data'] = 4;
                $tmpmark = FALSE;
                $this->FncDeleteFile($this->ClsCreateCsv->strNewCsvPath);
                $this->FncDeleteFile($this->ClsCreateCsv->strUsedCsvPath);
                $this->FncDeleteFile($this->ClsCreateCsv->strNewChangeCsvPath);
                $this->FncDeleteFile($this->ClsCreateCsv->strUsedChangeCsvPath);
            }
            //----20151117 YIN UPD S
            //終了LOG出力
            $objLog['strEndDate'] = $this->ClsComFnc->FncGetSysDate("Y-m-d H:i:s");
            if ($result['data'] == 4) {
                $objLog['strState'] = "NG";
            } else {
                $objLog['strState'] = "OK";
            }
            // $this -> ClsCreateCsv -> fncEndLog($this -> ClsCreateCsv -> strLogName, $objLog);
            //----20151117 YIN UPD E
            if ($tmpmark == TRUE) {

                //----20151117 YIN INS S
                $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $objLog);
                //----20151117 YIN INS E

                if ($objLog['ChkCount'] == 0) {
                    $objLog['strErrMsg'] = "  エラー件数 = 0";
                    $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $objLog);
                    $objLog['strErrMsg'] = "売上データチェック(ＣＳＶ作成)" . "　終了" . $this->ClsComFnc->FncGetSysDate("Y-m-d H:i:s");
                    $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $objLog);
                }
                $result['subErrSpreadShowData'] = $rtnmsg['subErrSpreadShowData'];
                $result['lblCnt'] = $rtnmsg['frmData']['lblCnt'];
                $result['frm1'] = $rtnmsg['frm1'];
                $result['pdfmark'] = FALSE;
                //売上データ対象外のチェックリストを出力する
                //-------印刷処理-------

                $PrintSelectResult = $this->FrmChumonCSV->fncUriageChkList(str_replace("/", "", $strCboUCNO), $strCboDateFrom, $strCboDateTo);
                if ($PrintSelectResult['result'] == FALSE) {
                    throw new \Exception($PrintSelectResult['data']);
                }
                if (count($PrintSelectResult['data']) > 0) {
                    //'プレビュー表示
                    $path_rpxTopdf = dirname(__DIR__);
                    include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

                    $rpx_file_names = array();
                    $tmp_data = array();
                    $tmp = array();
                    $data = array("SEQNO" => "", "TAISYO_NEN" => "", "KOUSIN_HANI" => "", "CMN_NO" => "", "UC_NO" => "", "JHN_DT" => "", "CEL_DT" => "", "UPD_DT" => "", "REC_UPD_CLT_NM" => "", "REC_CRE_SYA_CD" => "");
                    array_push($tmp_data, $PrintSelectResult['data']);
                    $tmp["data"] = $tmp_data;
                    $tmp["mode"] = "3";
                    $datas["rptChumonChkList"] = $tmp;
                    $rpx_file_names["rptChumonChkList"] = $data;
                    $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                    //20240419 lujunxia PHP8 ins s
                    //フォルダのパーミッションチェック
                    $fileFloder = WWW_ROOT . $obj->REPORTS_TEMP_PATH;
                    $outFloder = dirname($fileFloder);
                    if (file_exists($fileFloder)) {
                        if (!(is_readable($fileFloder) && is_writable($fileFloder) && is_executable($fileFloder))) {
                            throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                        }

                    } else {
                        if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                            throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                        }
                        if (!mkdir($obj->REPORTS_TEMP_PATH, 0777, TRUE)) {
                            throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                        }
                    }
                    //20240419 lujunxia PHP8 ins e
                    $pdfPath = $obj->to_pdf();
                    $result['pdfmark'] = TRUE;
                    $result['pdfpath'] = $pdfPath;
                }

            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        /** ログ管理対応 **/
        for ($idx = 0; $idx < 8; $idx++) {
            if ($intState[$idx] != 0) {
                //intState<>0の場合、ログ管理テーブルに登録
                $strPGNM = "";
                switch ($idx) {
                    case 0:
                        //lngOutCnt(0) NewのA
                        $strPGNM = "frmChumonCSV_NewA";
                        break;
                    case 1:
                        //lngOutCnt(1) NewのB
                        $strPGNM = "frmChumonCSV_NewB";
                        break;
                    case 2:
                        //lngOutCnt(2) UserのA
                        $strPGNM = "frmChumonCSV_UsedA";
                        break;
                    case 3:
                        //lngOutCnt(3) UserのB
                        $strPGNM = "frmChumonCSV_UsedB";
                        break;
                    case 4:
                        //lngOutCnt(4) NewChgのA
                        $strPGNM = "frmChumonCSV_NewChgA";
                        break;
                    case 5:
                        //lngOutCnt(5) NewChgのB
                        $strPGNM = "frmChumonCSV_NewChgB";
                        break;
                    case 6:
                        //lngOutCnt(6) UsewrChgのB
                        $strPGNM = "frmChumonCSV_UsedChgA";
                        break;
                    case 7:
                        //lngOutCnt(6) UsewrChgのB
                        $strPGNM = "frmChumonCSV_UsedChgB";
                        break;
                }

                // strOutFileNM(idx)を追加
                $this->ClsLogControl->fncLogEntry($strPGNM, $intState[$idx], $lngOutCnt[$idx], $strCboUCNO, $strCboDateFrom, $strCboDateTo, $strRdoValue, $strOutFileNM[$idx]);

            }

        }
        /** ログ管理対応 **/

        $this->fncReturn($result);
    }

    public function FncCopyDirectory($stSourcePath, $stDestPath, $bOverwrite)
    {
        $strDirectory = "";
        $lastlocation = strripos($stSourcePath, "/");
        $tmpArr = explode("/", $stSourcePath);
        $tmpArrNum = count($tmpArr);
        if ($lastlocation == (strlen($stSourcePath) - 1)) {
            $strDirectory = $tmpArr[$tmpArrNum - 2];
        } else {
            $strDirectory = $tmpArr[$tmpArrNum - 1];
        }
        $lastlocation1 = strripos($stDestPath, "/");
        if ($lastlocation1 != (strlen($stDestPath) - 1)) {
            $stDestPath = $stDestPath . "/";
        }
        $stDestPath = $stDestPath . $strDirectory . "/";

        // コピー先のディレクトリがなければ作成する
        if (file_exists($stDestPath) == FALSE) {
            mkdir($stDestPath);
            $bOverwrite = TRUE;
        }

        //コピー元のディレクトリにあるすべてのファイルをコピーする
        if ($bOverwrite == TRUE) {
            $stCopyFromFileName = scandir($stSourcePath);
            for ($i = 2; $i < count($stCopyFromFileName); $i++) {
                $stCopyFrom = $stSourcePath . $stCopyFromFileName[$i];
                $stCopyTo = $stDestPath . $stCopyFromFileName[$i];
                copy($stCopyFrom, $stCopyTo);
            }
        }
        //上書き不可能な場合は存在しない時のみコピーする
        else {
            $stCopyFromFileName = scandir($stSourcePath);
            for ($i = 2; $i < count($stCopyFromFileName); $i++) {
                $stCopyFrom = $stSourcePath . $stCopyFromFileName[$i];
                $stCopyTo = $stDestPath . $stCopyFromFileName[$i];
                if (file_exists($stCopyTo) == FALSE) {
                    copy($stCopyFrom, $stCopyTo);
                }
            }
        }
    }

    //ディレクトリ内のファイルを削除する
    public function FncDeleteFile($stSourcePath)
    {
        $fileArr = array();
        if (file_exists($stSourcePath) == TRUE) {
            $fileArr = scandir($stSourcePath);
            for ($i = 2; $i < count($fileArr); $i++) {
                unlink($stSourcePath . $fileArr[$i]);
            }
        }
    }

}
