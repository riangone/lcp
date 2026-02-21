<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHMMainDataCreate;

//*******************************************
// * sample controller
//*******************************************
class FrmHMMainDataCreateController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $FrmHMMainDataCreate;
    private $objLog;
    private $result;
    private $blnLockFlg;
    private $fncInsertNoExist;
    // public $ClsCreateCsv = '';
    // public $ClsComFnc = '';
    // public $ClsFileObserver = '';
    // public $ClsFncLog = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
        $this->loadComponent('ClsCreateCsv');
        $this->loadComponent('ClsFileObserver');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)
        $this->render('index', 'FrmHMMainDataCreate_layout');
    }

    public function fncUriMakeTargetDt()
    {
        try {
            $this->FrmHMMainDataCreate = new FrmHMMainDataCreate();
            $this->result = $this->FrmHMMainDataCreate->fncUriMakeTargetDt();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncUriMakeJknSel()
    {
        try {
            $this->FrmHMMainDataCreate = new FrmHMMainDataCreate();
            $this->result = $this->FrmHMMainDataCreate->fncUriMakeJknSel($_POST['data']['startDate']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncCmdActClickYes()
    {
        $iProjectCode = 0;
        $blnTran = TRUE;
        $this->FrmHMMainDataCreate = new FrmHMMainDataCreate();
        try {
            $this->FrmHMMainDataCreate->Do_conn();
            $this->FrmHMMainDataCreate->Do_transaction();
            //１．売上速報データ作成
            if ($_POST['data']['radAll'] == TRUE || ($_POST['data']['radKobetu'] == TRUE && $_POST['data']['chkUriSoku'] == TRUE)) {
                $this->result = $this->FrmHMMainDataCreate->fncUriUpdMainDataMakeCtl($_POST['data']['startDate'], $_POST['data']['endDate']);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data'], 1);
                }
            }
            //２．限界利益速報データ作成
            //    限界利益速報データの作成開始月は売上速報データ作成を実行したら入るので、限界利益速報データの作成開始月をセットするのは
            //　　   限界利益速報データを単独で実行するときのみ
            if ($_POST['data']['radKobetu'] == TRUE && ($_POST['data']['chkGenriSoku'] == TRUE && $_POST['data']['chkUriSoku'] == FALSE)) {
                $this->result = $this->FrmHMMainDataCreate->fncGenriUpdMainDataMakeCtl($_POST['data']['syoriYM']);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data'], 1);
                }
            }
            //３．基準会計速報データ作成
            //   基準会計速報データの作成開始月は売上速報データ作成を実行したら入るので、基準会計速報データの作成開始月をセットするのは
            //   基準会計速報データを単独で実行するときのみ
            if ($_POST['data']['radKobetu'] == TRUE && ($_POST['data']['chkKijyunSoku'] == TRUE && $_POST['data']['chkUriSoku'] == FALSE)) {
                $this->result = $this->FrmHMMainDataCreate->fncKijyunUpdMainDataMakeCtl($_POST['data']['syoriYM']);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data'], 1);
                }
            }
            //４．会計速報データ作成
            if ($_POST['data']['radAll'] == TRUE || ($_POST['data']['radKobetu'] == TRUE && $_POST['data']['chkKaikeiSoku'] == TRUE)) {
                $this->result = $this->FrmHMMainDataCreate->fncKaikeiUpdMainDataMakeCtl($_POST['data']['startDate'], $_POST['data']['endDate']);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data'], 1);
                }
            }
            //コミット
            $this->FrmHMMainDataCreate->Do_commit();
            $blnTran = FALSE;
            //各バッチ処理を実行する
            //１．売上速報データ作成
            if ($_POST['data']['radAll'] == 'true' || ($_POST['data']['radKobetu'] == 'true' && $_POST['data']['chkUriSoku'] == 'true')) {
                //バッチﾌｧｲﾙ起動
                $kk = $this->HMOriginalDataCreate();
                $iProjectCode = 1;
                if ($kk == 9) {
                    throw new \Exception("処理に失敗しました", 1);
                }
            }

            //２．限界利益速報データ作成
            if ($_POST['data']['radAll'] == 'true' || ($_POST['data']['radKobetu'] == 'true' && $_POST['data']['chkGenriSoku'] == 'true')) {
                //バッチﾌｧｲﾙ起動
                //$this -> HMOriginalDataGenri();
                $iProjectCode = 2;
                if ($this->HMOriginalDataGenri() == 9) {

                    throw new \Exception("処理に失敗しました", 1);
                }
            }
            //３．基準会計速報データ作成
            if ($_POST['data']['radAll'] == 'true' || ($_POST['data']['radKobetu'] == 'true' && $_POST['data']['chkKijyunSoku'] == 'true')) {
                //バッチﾌｧｲﾙ起動
                //$this -> HMOriginalDataKijyun();
                $iProjectCode = 3;
                if ($this->HMOriginalDataKijyun() == 9) {
                    throw new \Exception("処理に失敗しました", 1);
                }
            }
            //４．会計速報データ作成
            if ($_POST['data']['radAll'] == 'true' || ($_POST['data']['radKobetu'] == 'true' && $_POST['data']['chkKaikeiSoku'] == 'true')) {
                //バッチﾌｧｲﾙ起動
                $iProjectCode = 4;
                if ($this->HMOriginalDataKaikei() == 9) {
                    throw new \Exception("処理に失敗しました", 1);
                }
            }
            //売上メインデータ・会計メインデータ作成処理を行う
            //バッチﾌｧｲﾙ起動
            $this->HMDATAMAINVWCREATE();
            //完了メッセージを表示する
            $this->result['iProjectCode'] = $iProjectCode;
            $this->result['data'] = 'success';
            $this->result['result'] = TRUE;
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
            $blnTran == TRUE;
        }
        if ($blnTran == TRUE) {
            $this->FrmHMMainDataCreate->Do_rollback();
        }
        //DB接続解除
        $this->FrmHMMainDataCreate->Do_close();
        $this->fncReturn($this->result);
    }

    //---各バッチ処理を実行する  関数　start---

    //------１．売上速報データ作成
    public function HMOriginalDataCreate()
    {
        $blnTran = TRUE;
        $blnRtn = TRUE;
        $strTargetStDt = "";
        $strTargetEdDt = "";
        try {
            $this->objLog = $this->ClsCreateCsv->GS_OUTPUTLOG;
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //LOG出力ﾊﾟｽを取得する
            $this->ClsCreateCsv->strLogPath = $this->ClsComFnc->FncGetPath("pprlogpath");
            if ($this->ClsCreateCsv->strLogPath == "") {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/log/LOG.Log";
            } else {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/" . $this->ClsCreateCsv->strLogPath;
            }
            if (!file_exists(dirname($this->ClsCreateCsv->strLogPath))) {
                $Logpath = dirname($this->ClsCreateCsv->strLogPath);
                mkdir($Logpath);
            }
            //ErrLOG出力ﾊﾟｽを取得する
            $this->ClsCreateCsv->strErrLogPath = $this->ClsComFnc->FncGetPath("SCURICNVERR");
            if ($this->ClsCreateCsv->strErrLogPath == "") {
                $this->ClsCreateCsv->strErrLogPath = $strPath . "/mnt/temp/log/SCURICNV.Log";
            }
            if (!file_exists(dirname($this->ClsCreateCsv->strErrLogPath))) {
                $Logpath = dirname($this->ClsCreateCsv->strErrLogPath);
                mkdir($Logpath);
            }
            //ログ出力先をｾｯﾄ
            $this->ClsCreateCsv->strLogName = $this->ClsCreateCsv->strLogPath;
            //ｴﾗｰログ出力先をｾｯﾄ
            $this->ClsCreateCsv->strErrLogName = $this->ClsCreateCsv->strErrLogPath;
            //構造体に格納(LOG)
            $this->objLog['strID'] = "新車・中古車売上速報データ作成";
            $this->objLog['strStartDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
            //売上データ作成日を取得する
            $this->result = $this->FrmHMMainDataCreate->fncUriMakeTargetDt_HMOriginalDataCreate();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            } else {
                if (count($this->result['data']) == 0) {
                    $strTargetStDt = date('Ymd', strtotime('-1 day'));
                    $strTargetEdDt = date('Ymd', strtotime('-1 day'));
                } else {
                    if ($this->ClsComFnc->FncNv($this->result['data'][0]['URI_SOKU_START_DT'] == "")) {
                        $strTargetStDt = date('Ymd', strtotime('-1 day'));
                        $strTargetEdDt = date('Ymd', strtotime('-1 day'));
                    } else {
                        $strTargetStDt = $this->result['data'][0]['URI_SOKU_START_DT'];
                        $strTargetEdDt = $this->result['data'][0]['URI_SOKU_END_DT'];
                    }
                }
            }

            //売上データ作成範囲ｺﾝﾄﾛｰﾙデータを取得する
            $this->result = $this->FrmHMMainDataCreate->fncUriMakeJknSel_HMOriginalDataCreate($strTargetStDt, $strTargetEdDt);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count($this->result['data']) == 0) {
                //ｴﾗｰLOG出力
                $this->objLog['strDataNM'] = "売上データ作成範囲コントロールデータ";
                $this->objLog['strErrMsg'] = "売上データ作成範囲が取得できません。";
                //strLogNameですか？
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 1;
            }

            //開始LOG出力
            $this->ClsFncLog->fncStartLog($this->ClsCreateCsv->strLogName, $this->objLog);
            $this->objLog['strErrMsg'] = "新車・中古車売上データ作成" . "　開始" . $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
            $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $this->objLog);
            $this->objLog['strErrMsg'] = "  処理年月 = " . $this->result['data'][0]['SYORI_YM'] . "  開始更新年月日 =" . $strTargetStDt . "  終了更新年月日 = " . $strTargetEdDt;
            $this->ClsCreateCsv->fncN5200ErrLog($this->ClsCreateCsv->strErrLogName, $this->objLog);
            //トランザクション開始
            $this->FrmHMMainDataCreate->Do_transaction();
            //CSVデータを作成する
            $this->objLog['lngCount'] = 0;
            $this->objLog['ErrCount'] = 0;
            $this->objLog['ChkCount'] = 0;
            $blnRtn = $this->ClsCreateCsv->fncSCURICreate2($this->objLog, "SCUriageMake", $this->result['SYORI_YM'], $strTargetStDt, $strTargetEdDt);

            if ($this->objLog['ErrCount'] > 0) {
                //ｴﾗｰLOG出力
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strErrLogName, $this->objLog);
            } else {
                if (!$blnRtn) {
                    //ｴﾗｰLOG出力
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strErrLogName, $this->objLog);
                    //終了LOG出力
                    $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
                    $this->objLog['strState'] = "OK";
                    $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    return 9;
                } else {
                    if ($this->objLog['strState'] == "NOTHING") {
                        //'該当データが存在しない場合
                        //'ｴﾗｰLOG出力
                        $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strErrLogName, $this->objLog);
                    }
                }
            }
            //終了LOG出力
            $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
            $this->objLog['strState'] = "OK";
            $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
            //条件変更の場合に取得できなくなってしまう。そのための補完更新を行なう。
            $this->result = $this->FrmHMMainDataCreate->fncSubSCURIUpdate();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if ($blnRtn) {
                //売上データ作成日データをNULL更新及び限界利益データ作成日を処理年月に設定する
                $this->result = $this->FrmHMMainDataCreate->fncUpdMainDataMakeCtl_create('');
                if ($this->result['result'] == FALSE) {
                    return 9;
                }
                //ｺﾐｯﾄ
                $this->FrmHMMainDataCreate->Do_commit();
                $blnTran = FALSE;

            } else {
                $this->FrmHMMainDataCreate->Do_rollback();
                $blnTran = FALSE;
            }
            $this->blnLockFlg = FALSE;
            if ($blnRtn) {
                return 1;
            }

        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            return 9;
        }
        if ($blnTran) {
            //ロールバック
            $this->FrmHMMainDataCreate->Do_rollback();
        }
        return "true";
    }

    //------２．限界利益速報データ作成
    public function HMOriginalDataGenri()
    {
        try {

            $strTargetMt = "";
            $this->objLog = $this->ClsCreateCsv->GS_OUTPUTLOG;
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //LOG出力ﾊﾟｽを取得する
            $this->ClsCreateCsv->strLogPath = $this->ClsComFnc->FncGetPath("pprlogpath");
            if ($this->ClsCreateCsv->strLogPath == "") {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/log/LOG.Log";
            } else {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/" . $this->ClsCreateCsv->strLogPath;
            }
            if (!file_exists(dirname($this->ClsCreateCsv->strLogPath))) {
                $Logpath = dirname($this->ClsCreateCsv->strLogPath);
                mkdir($Logpath);
            }
            //ログ出力先をｾｯﾄ
            $this->ClsCreateCsv->strLogName = $this->ClsCreateCsv->strLogPath;
            $tt = "";
            //構造体に格納(LOG)
            $this->objLog['strID'] = "限界利益速報データ作成";
            $this->objLog['strStartDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
            $this->objLog['strDataNM'] = "限界利益速報データ";
            //開始LOG出力
            $this->ClsFncLog->fncStartLog($this->ClsCreateCsv->strLogName, $this->objLog);
            //限界利益速報データ作成年月を取得する
            //objTargetDtDs="";
            $this->result = $this->FrmHMMainDataCreate->fncMakeTargetDt();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count($this->result['data']) == 0) {
                $this->objLog['strErrMsg'] = "メインデータ作成コントロールマスタが設定されていません";
                $tt = "";
                //ｴﾗｰLOG出力
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //終了LOG出力
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
                $this->objLog['strState'] = "OK";
                $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 1;
            } else {
                if ($this->ClsComFnc->FncNv($this->result['data'][0]['GENRI_SOKU_START_MT']) == "") {
                    //ｴﾗｰLOG出力
                    $this->objLog['strErrMsg'] = "限界利益速報データ作成対象月が設定されていません";
                    $tt = "";
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    //終了LOG出力
                    $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
                    $this->objLog['strState'] = "OK";
                    $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    return 1;
                } else {
                    $strTargetMt = $this->result['data'][0]['GENRI_SOKU_START_MT'];
                }

            }
            //存在ﾁｪｯｸ
            $this->result = $this->FrmHMMainDataCreate->fncHscUriExistCheck($strTargetMt, "S");
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count($this->result['data']) == 0) {
                $tt = "";
                $this->objLog['strErrMsg'] = "指定された年月の売上速報データが存在しません";
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //終了LOG出力
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
                $this->objLog['strState'] = "OK";
                $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 1;
            }
            //トランザクション開始
            $this->FrmHMMainDataCreate->Do_transaction();
            //1.当月限界利益データを削除する
            $this->result = $this->FrmHMMainDataCreate->fncDeleteGenri($strTargetMt, "S");
            if (!$this->result['result']) {
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }
            //2.条件変更履歴データに同一注文書番号が存在しないデータをINSERTする
            $this->result = $this->FrmHMMainDataCreate->fncInsertNoExist($strTargetMt, '', "S");
            if (!$this->result['result']) {
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }
            //3.条件変更履歴データに同一注文書番号、売上部署、売上セールが存在しているデータをINSERTする
            $this->result = $this->FrmHMMainDataCreate->fncInsertNoExist($strTargetMt, "GENKAIMAKE", "S");
            if (!$this->result['result']) {
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }

            //4.条件変更履歴データに同一注文書番号が存在しているが、売上部署又は売上セールスが一致しない
            //   データをINSERTする
            //   ①条件変更履歴データよりマイナスデータを作成する
            $this->result = $this->FrmHMMainDataCreate->fncInsertAkaJyohen($strTargetMt, "GENKAIMAKE", "S");
            if (!$this->result['result']) {
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }
            //   ②売上データより限界利益データを作成する
            $this->result = $this->FrmHMMainDataCreate->fncInsertForExist($strTargetMt, "GENKAIMAKE", "S");
            if (!$this->result['result']) {
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }
            //コミット
            $this->FrmHMMainDataCreate->Do_commit();
            $tt = "";
            //終了LOG出力
            $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
            $this->objLog['strState'] = "OK";
            $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
            //限界利益データ作成日をNULLに設定する
            $this->result = $this->FrmHMMainDataCreate->fncUpdMainDataMakeCtl_genri();
            if (!$this->result['result']) {
                //throw new \Exception("Error Processing Request", 1);
                return 9;
            }
            return 1;
        } catch (\Exception $ex) {
            $this->objLog['strErrMsg'] = $ex->getMessage();
            $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
            return 9;
        }
    }

    //------３．基準会計速報データ作成
    public function HMOriginalDataKijyun()
    {
        try {
            $strTargetMt = "";
            $this->objLog = $this->ClsCreateCsv->GS_OUTPUTLOG;
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //LOG出力ﾊﾟｽを取得する
            $this->ClsCreateCsv->strLogPath = $this->ClsComFnc->FncGetPath("pprlogpath");
            if ($this->ClsCreateCsv->strLogPath == "") {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/log/LOG.Log";
            } else {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/" . $this->ClsCreateCsv->strLogPath;
            }
            if (!file_exists(dirname($this->ClsCreateCsv->strLogPath))) {
                $Logpath = dirname($this->ClsCreateCsv->strLogPath);
                mkdir($Logpath);
            }
            //ログ出力先をｾｯﾄ
            $this->ClsCreateCsv->strLogName = $this->ClsCreateCsv->strLogPath;
            $tt = "";
            //構造体に格納(LOG)
            $this->objLog['strID'] = "基準会計速報データ作成";
            $this->objLog['strStartDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
            $this->objLog['strDataNM'] = "基準会計速報データ";
            //開始LOG出力
            $this->ClsFncLog->fncStartLog($this->ClsCreateCsv->strLogName, $this->objLog);

            //基準会計速報データ作成年月を取得する
            $this->result = $this->FrmHMMainDataCreate->fncMakeTargetDt();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count($this->result['data']) == 0) {
                $this->objLog['strErrMsg'] = "メインデータ作成コントロールマスタが設定されていません";
                //ｴﾗｰLOG出力
                $tt = "";
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //終了LOG出力
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
                $this->objLog['strState'] = "OK";
                $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 1;
            } else {
                if ($this->ClsComFnc->FncNv($this->result['data'][0]['KIJYUN_SOKU_START_MT']) == "") {
                    //ｴﾗｰLOG出力
                    $this->objLog['strErrMsg'] = "基準会計速報データ作成対象月が設定されていません";
                    $tt = "";
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    //終了LOG出力
                    $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
                    $this->objLog['strState'] = "OK";
                    $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    return 1;
                } else {
                    $strTargetMt = $this->result['data'][0]['KIJYUN_SOKU_START_MT'];
                }

            }
            //トランザクション開始
            $this->FrmHMMainDataCreate->Do_transaction();
            $lngKaikeiCnt = 0;
            $strDENNO = "";
            //当月分売上台数振替ﾃﾞｰﾀを削除
            $this->result = $this->FrmHMMainDataCreate->fncFurikaeDelete($strTargetMt, "SC", "S");
            if (!$this->result['result']) {
                $this->subErrLogOut();
                return 9;
            }
            //新中売上ﾃﾞｰﾀより売上台数振替ﾃﾞｰﾀをINSERT
            $this->result = $this->FrmHMMainDataCreate->fncFURIDAISUInsert($strTargetMt, "GENKAIMAKE", "S");
            if (!$this->result['result']) {
                $this->subErrLogOut();
                return 9;
            }
            //当月分売上基準価格会計ﾃﾞｰﾀを削除
            $tmp = explode("/", $strTargetMt);
            // $tt = cal_days_in_month(CAL_GREGORIAN, $tmp[1], $tmp[0]);
            $tt = date("t", strtotime(substr($tmp[0], 0, 4) . '-' . substr($tmp[1], 4, 2)));

            $tmpdate = $strTargetMt . $tt;
            $this->result = $this->FrmHMMainDataCreate->fncKaikeiDelete($strTargetMt . "01", $tmpdate, "SC", "S");
            if (!$this->result['result']) {
                $this->subErrLogOut();
                return 9;
            }
            $returnval = $this->fncGetSaiban("KEIRI");
            $returnval = str_pad($returnval, 5, "0", STR_PAD_LEFT);
            $strDENNO = $strTargetMt . "9" . $returnval;
            //新中売上ﾃﾞｰﾀより基準価格会計ﾃﾞｰﾀをINSERT
            $lngKaikeiCnt = $this->FrmHMMainDataCreate->fncKijyunKaikeiInsert($strTargetMt, $strDENNO, "GENKAIMAKE", "S");
            if ($lngKaikeiCnt < 0) {
                $this->subErrLogOut();
                return 9;
            }
            $lngKaikeiCnt = $this->FrmHMMainDataCreate->fncKijyunKaikeiInsert2($strTargetMt, $strDENNO, "GENKAIMAKE", "S");
            if ($lngKaikeiCnt < 0) {
                $this->subErrLogOut();
                return 9;
            }
            $lngKaikeiCnt = $this->FrmHMMainDataCreate->fncKijyunKaikeiInsert3($strTargetMt, $strDENNO, "GENKAIMAKE", "S");
            if ($lngKaikeiCnt < 0) {
                $this->subErrLogOut();
                return 9;
            }
            $lngKaikeiCnt = $this->FrmHMMainDataCreate->fncKijyunKaikeiInsert4($strTargetMt, $strDENNO, "GENKAIMAKE", "S");
            if ($lngKaikeiCnt < 0) {
                $this->subErrLogOut();
                return 9;
            }
            $lngKaikeiCnt = $this->FrmHMMainDataCreate->fncKijyunKaikeiInsert5($strTargetMt, $strDENNO, "GENKAIMAKE", "S");
            if ($lngKaikeiCnt < 0) {
                $this->subErrLogOut();
                return 9;
            }
            //ｺﾐｯﾄ
            $this->FrmHMMainDataCreate->Do_commit();
            $tt = "";
            //終了LOG出力(正常終了)
            $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
            $this->objLog['strState'] = "OK";
            $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);

            //基準会計速報データ作成日をNULLに設定する
            $this->result = $this->FrmHMMainDataCreate->fncUpdMainDataMakeCtl_kijyun();
            if (!$this->result['result']) {
                return 9;
            }
            return 1;
        } catch (\Exception $ex) {
            $this->objLog['strErrMsg'] = $ex->getMessage();
            $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
            return 9;
        }
    }

    //------４．会計速報データ作成
    public function HMOriginalDataKaikei()
    {
        // $lngCnt = 0;
        // $strTargetStDt = "";
        // $strTargetEdDt = "";
        $strkeijoDtSt = "";
        $strKeijoDtEd = "";
        $strKeiriDt = "";
        try {

            $this->objLog = $this->ClsCreateCsv->GS_OUTPUTLOG;
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //LOG出力ﾊﾟｽを取得する
            $this->ClsCreateCsv->strLogPath = $this->ClsComFnc->FncGetPath("pprlogpath");
            if ($this->ClsCreateCsv->strLogPath == "") {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/log/LOG.Log";
            } else {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/" . $this->ClsCreateCsv->strLogPath;
            }
            if (!file_exists(dirname($this->ClsCreateCsv->strLogPath))) {
                $Logpath = dirname($this->ClsCreateCsv->strLogPath);
                mkdir($Logpath);
            }
            //ログ出力先をｾｯﾄ
            $this->ClsCreateCsv->strLogName = $this->ClsCreateCsv->strLogPath;
            //構造体に格納(LOG)
            $this->objLog['strID'] = "会計速報データ作成";
            $this->objLog['strStartDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
            $this->objLog['strDataNM'] = "会計速報データ";
            //開始LOG出力
            $this->ClsFncLog->fncStartLog($this->ClsCreateCsv->strLogName, $this->objLog);
            //会計速報データ作成年月を取得する
            $this->result = $this->FrmHMMainDataCreate->fncMakeTargetDt_Kaikei();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count($this->result['data']) == 0) {
                // $t = date("Ymd", strtotime("-1 day"));
                // $strTargetStDt = $t;
                // $strTargetEdDt = $t;
            } else {
                if ($this->ClsComFnc->FncNv($this->result['data'][0]['KAIKEI_SOKU_START_DT']) == "") {
                    // $t = date("Ymd", strtotime("-1 day"));
                    // $strTargetStDt = $t;
                    // $strTargetEdDt = $t;
                } else {
                    // $strTargetStDt = $this->result['data'][0]['KAIKEI_SOKU_START_DT'];
                    // $strTargetEdDt = $this->result['data'][0]['KAIKEI_SOKU_END_DT'];
                }
            }
            $this->result = $this->FrmHMMainDataCreate->fncKeiriCtlSel();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count($this->result['data']) == 0) {
                $strKeiriDt = "";
            } else {
                $strKeiriDt = $this->ClsComFnc->FncNv($this->result['data'][0]['SYR_YMD']);
            }
            if ($strKeiriDt == "") {
                //ｴﾗｰLOG出力
                $this->objLog['strErrMsg'] = "経理処理年月が存在しません。管理者にお問合せ下さい。";
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 1;
            }
            //対象更新年月日の計上日を取得する
            $this->result = $this->FrmHMMainDataCreate->fnckeijoBiSel();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count($this->result['data']) == 0) {
                //該当データは存在しません
                //ｴﾗｰLOG出力
                $this->objLog['strErrMsg'] = "対象更新年月日のデータが存在しません。";
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //会計速報データ作成日をNULLに設定する
                $this->result = $this->FrmHMMainDataCreate->fncUpdMainDataMakeCtl_kaikei();
                if (!$this->result['result']) {
                    //throw new \Exception($this -> result['data'], 1);
                    //ログファイル作成
                    $this->objLog['strErrMsg'] = $this->result['data'];
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    return 9;
                }
                //終了LOG出力
                $tt = "";
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
                $this->objLog['strState'] = "OK";
                $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 1;
            } else {
                if ($this->ClsComFnc->FncNv($this->result['data'][0]['MIN_KEIJO_DT']) == "") {
                    //該当データは存在しません
                    //ｴﾗｰLOG出力
                    $this->objLog['strErrMsg'] = "対象更新年月日のデータが存在しません。";
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    //会計速報データ作成日をNULLに設定する
                    $this->result = $this->FrmHMMainDataCreate->fncUpdMainDataMakeCtl_kaikei();
                    if (!$this->result['result']) {
                        //throw new \Exception($this -> result['data'], 1);
                        //ログファイル作成
                        $this->objLog['strErrMsg'] = $this->result['data'];
                        $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                        return 9;
                    }
                    //終了LOG出力
                    $tt = "";
                    $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
                    $this->objLog['strState'] = "OK";
                    $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    return 1;
                } else {
                    $strkeijoDtSt = $this->result['data'][0]['MIN_KEIJO_DT'];
                }
            }
            //経理処理日＞更新日から求めた最初計上日
            if ($strKeiriDt > $strkeijoDtSt) {
                //経理処理日以前のデータは確定データが存在するのでそれ以降の速報データを作成する
                $strkeijoDtSt = $strKeiriDt;
            }
            //経理処理日＞更新日から求めた最終計上日
            if ($strKeiriDt > $strKeijoDtEd) {
                //最終計上日の方が経理処理日よりも小さい場合は既に確定データが作られているので速報の作成は行わない
                //ｴﾗｰLOG出力
                $this->objLog['strErrMsg'] = "経理処理年月以前のデータは既に確報データが存在しますので、速報データの作成は行えません！";
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //会計速報データ作成日をNULLに設定する
                $this->result = $this->FrmHMMainDataCreate->fncUpdMainDataMakeCtl_kaikei();
                if (!$this->result['result']) {
                    //throw new \Exception($this -> result['data'], 1);
                    //ログファイル作成
                    $this->objLog['strErrMsg'] = $this->result['data'];
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    return 9;
                }
                //終了LOG出力
                $tt = "";
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
                $this->objLog['strState'] = "OK";
                $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 1;
            }

            $this->result = $this->FrmHMMainDataCreate->fnckaikeiWKDelete("S");
            if (!$this->result['result']) {
                //ｴﾗｰLOG出力
                //ログファイル作成
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //終了LOG出力
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
                $this->objLog['strState'] = "NG";
                $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }
            $this->result = $this->ClsKeiriDataMake->fncKaikeiWKInsert($strkeijoDtSt, $strKeijoDtEd, "S", FALSE);
            if (!$this->result['result']) {
                //ｴﾗｰLOG出力
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //終了LOG出力
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
                $this->objLog['strState'] = "NG";
                $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }
            if (!$this->fcnErrChk($strkeijoDtSt, $strKeijoDtEd)) {
                //終了LOG出力
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
                $this->objLog['strState'] = "NG";
                $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }
            //トランザクション開始
            $this->FrmHMMainDataCreate->Do_transaction();
            $this->result = $this->FrmHMMainDataCreate->fncKaikeiDelete($strkeijoDtSt, $strKeijoDtEd, "SW", "S");
            if (!$this->result['result']) {
                //ｴﾗｰLOG出力
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //終了LOG出力
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
                $this->objLog['strState'] = "NG";
                $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }
            $this->result = $this->FrmHMMainDataCreate->fnckaikeiInsert($strkeijoDtSt, $strKeijoDtEd, "KaikeiMake", "S");
            if (!$this->result['result']) {
                //ｴﾗｰLOG出力
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //終了LOG出力
                $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
                $this->objLog['strState'] = "NG";
                $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            } else {
                if (count($this->result['data']) == 0) {
                    //ｴﾗｰLOG出力
                    $this->objLog['strErrMsg'] = "該当データが存在しません";
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    //終了LOG出力
                    $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
                    $this->objLog['strState'] = "NG";
                    $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                    return 1;
                }
            }
            //ｺﾐｯﾄ
            $this->FrmHMMainDataCreate->Do_commit();
            if ($this->fncPrint($strkeijoDtSt, $strKeijoDtEd) == FALSE) {
                //ログファイル作成
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }
            //終了LOG出力(正常終了)
            $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($this->objLog['strErrMsg'], "Y-m-d H:i:s");
            $this->objLog['strState'] = "OK";
            $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
            //会計速報データ作成日をNULLに設定する
            $this->result = $this->FrmHMMainDataCreate->fncUpdMainDataMakeCtl_kaikei();
            if (!$this->result['result']) {
                //ログファイル作成
                $this->objLog['strErrMsg'] = $this->result['data'];
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                return 9;
            }

            return 1;
        } catch (\Exception $ex) {
            $this->objLog['strErrMsg'] = $ex->getMessage();
            $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
            return 9;
        }

    }

    //------５．売上メインデータ・会計メインデータ作成処理を行う
    public function HMDATAMAINVWCREATE()
    {
        //HKAIKEI_MAIN_VW
        $this->result = $this->FrmHMMainDataCreate->HKAIKEI_MAIN_VW_select();
        if ($this->result['data'][0]['COUNT(1)'] == 1) {
            $this->result = $this->FrmHMMainDataCreate->HKAIKEI_MAIN_VW_drop();
            if ($this->result['result'] == true) {
                $this->result = $this->FrmHMMainDataCreate->HKAIKEI_MAIN_VW_create();
                if ($this->result['result'] == TRUE) {

                } else {
                    throw new \Exception($this->result['data'], 1);
                }
            } else {
                //view does not exists
                if (strstr($this->result['data'], "ORA-12003")) {
                    $this->result = $this->FrmHMMainDataCreate->HKAIKEI_MAIN_VW_create();
                    if ($this->result['result'] == TRUE) {
                    } else {
                        throw new \Exception($this->result['data'], 1);
                    }
                } else {
                    throw new \Exception($this->result['data'], 1);
                }

            }
        }

        //HSCURI_ALL_VW
        $this->result = $this->FrmHMMainDataCreate->HSCURI_ALL_VW_select();
        if ($this->result['data'][0]['COUNT(1)'] == 1) {
            $this->result = $this->FrmHMMainDataCreate->HSCURI_ALL_VW_drop();
            if ($this->result['result'] == true) {
                $this->result = $this->FrmHMMainDataCreate->HSCURI_ALL_VW_create();
                if ($this->result['result'] == TRUE) {

                } else {
                    throw new \Exception($this->result['data'], 1);
                }
            } else {
                //view does not exists
                if (strstr($this->result['data'], "ORA-12003")) {
                    $this->result = $this->FrmHMMainDataCreate->HSCURI_ALL_VW_create();
                    if ($this->result['result'] == TRUE) {
                    } else {
                        throw new \Exception($this->result['data'], 1);
                    }
                } else {
                    throw new \Exception($this->result['data'], 1);
                }

            }
        }

        //HSCURI_MAIN_VW
        $this->result = $this->FrmHMMainDataCreate->HSCURI_MAIN_VW_select();
        if ($this->result['data'][0]['COUNT(1)'] == 1) {
            $this->result = $this->FrmHMMainDataCreate->HSCURI_MAIN_VW_drop();
            if ($this->result['result'] == true) {
                $this->result = $this->FrmHMMainDataCreate->HSCURI_MAIN_VW_create();
                if ($this->result['result'] == TRUE) {

                } else {
                    throw new \Exception($this->result['data'], 1);
                }
            } else {
                //view does not exists
                if (strstr($this->result['data'], "ORA-12003")) {
                    $this->result = $this->FrmHMMainDataCreate->HSCURI_MAIN_VW_create();
                    if ($this->result['result'] == TRUE) {

                    } else {
                        throw new \Exception($this->result['data'], 1);
                    }
                } else {
                    throw new \Exception($this->result['data'], 1);
                }
            }
        }
        //$this -> FrmHMMainDataCreate -> HSCURI_MAIN_VW();
        //$this -> FrmHMMainDataCreate -> HSCURI_ALL_VW();
    }

    //---各バッチ処理を実行する 関数　end---

    public function subErrLogOut()
    {
        //ｴﾗｰLOG出力
        $this->objLog['strErrMsg'] = "基準会計速報データ作成対象月が設定されていません";

        $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
        //終了LOG出力
        $tt = "";
        $this->objLog['strEndDate'] = $this->ClsFileObserver->Fnc_GetSysDate($tt, "Y-m-d H:i:s");
        $this->objLog['strState'] = "NG";
        $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);

    }

    public function fncGetSaiban($strID)
    {
        $strNo = 0;
        try {
            $this->result = $this->FrmHMMainDataCreate->fncGetSaiban_select($strID);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            } else {
                if (count($this->result['data']) > 0) {
                    $strNo = $this->ClsComFnc->FncNz($this->result['data'][0]["SEQNO"]);
                } else {
                    $strNo = 1;
                }

            }
            $this->result = $this->FrmHMMainDataCreate->fncGetSaiban_update($strID);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            return $strNo;
        } catch (\Exception $ex) {
            // $strMsg = "cslKeiriDataMake <br/> fncGetSaiban <br/> " . $ex->getMessage();
            //ｴﾗｰLOG出力
            $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
            return -1;
        }
    }

    public function fcnErrChk($strKaishiDt, $strSyuryoDt)
    {
        try {

            $this->result = $this->FrmHMMainDataCreate->fncClsKeiriDataMake($strKaishiDt, $strSyuryoDt, "S");
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count($this->result['data']) <= 0) {
                return true;
            }

            foreach ($this->result['data'] as $value) {
                if ($this->ClsComFnc->FncNv($value['KL_KAMOK_CD']) == "") {
                    $this->objLog['strErrMsg'] = "科目コードが未登録です。" . " 仕訳No.=" . trim($this->ClsComFnc->FncNv($value['SIWAK_NO'])) . " 借方科目ｺｰﾄﾞ=" . trim($this->ClsComFnc->FncNv($value['L_KAMOK_CD'])) . " 借方項目ｺｰﾄﾞ=" . trim($this->ClsComFnc->FncNv($value['L_KOUMK_CD']));
                    //ログファイル作成
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);

                }
                if ($this->ClsComFnc->FncNv($value['KR_KAMOK_CD']) == "") {
                    $this->objLog['strErrMsg'] = "科目コードが未登録です。" . " 仕訳No.=" . trim($this->ClsComFnc->FncNv($value['SIWAK_NO'])) . " 借方科目ｺｰﾄﾞ=" . trim($this->ClsComFnc->FncNv($value['R_KAMOK_CD'])) . " 借方項目ｺｰﾄﾞ=" . trim($this->ClsComFnc->FncNv($value['R_KOUMK_CD']));
                    //ログファイル作成
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                }
            }
            return true;
        } catch (\Exception $ex) {
            $this->objLog['strErrMsg'] = $ex->getMessage();
            $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
            return false;
        }
        //$this->ClsKeiriDataMake
    }

    public function fncPrint($strkeijoDtSt, $strKeijoDtEd)
    {
        try {
            $fncPrint = false;
            //印刷処理
            $this->result = $this->FrmHMMainDataCreate->fncGetAnmattiData($strkeijoDtSt, $strKeijoDtEd, "S");
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count($this->result['data']) == 0) {
                return true;
            }
            $this->objLog['strErrMsg'] = "アンマッチデータが存在します  " . count($this->result['data']) . "件";
            //ログファイル作成
            $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
            foreach ($this->result['data'] as $value) {
                $this->objLog['strErrMsg'] = " 証憑No.=" . $this->ClsComFnc->FncNv($value['CMN_NO']) . " 販売担当社員番号=" . $this->ClsComFnc->FncNv($value['HNB_TAN_EMP_NO']) . " ｴﾗｰ内容=" . $this->ClsComFnc->FncNv($value['ERR_MSG']);
                //ログファイル作成
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
            }
            $fncPrint = true;
            return $fncPrint;
        } catch (\Exception $ex) {
            $this->objLog['strErrMsg'] = $ex->getMessage();
        }
    }

}