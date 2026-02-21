<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSCUrkIn;

//*******************************************
// * sample controller
//*******************************************
class FrmSCUrkInController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $objLog;
    public $result;
    public $exceptionTF = FALSE;
    public $FrmSCUrkIn;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
        $this->loadComponent('ClsCreateCsv');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmSCUrkIn_layout');
    }

    public function frmSampleLoad()
    {
        try {
            $this->FrmSCUrkIn = new FrmSCUrkIn();

            $this->result = $this->FrmSCUrkIn->frmSampleLoadDate();

            if (!$this->result['result']) {
                $this->result['MsgID'] = "E9999";
                throw new \Exception($this->result['data']);
            } else {
                if (count((array) $this->result['data']) <= 0) {
                    $this->result['MsgID'] = "E9999";
                    throw new \Exception("コントロールマスタが存在しません！");
                }
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['msgContent'] = $e->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function changeFileName($param)
    {
        $strUserID = $this->FrmSCUrkIn->GS_LOGINUSER['strUserID'];
        $arr = explode(".", $param);
        $long = count($arr) - 1;
        $file_type = $arr[$long];
        $file_name = '';
        for ($i = 0; $i < $long; $i++) {
            $file_name = $file_name . $arr[$i] . '.';
        }
        $file_name = substr($file_name, 0, strlen($file_name) - 1);
        $file_name = $strUserID . '_' . $file_name . '.' . $file_type;
        return $file_name;
    }

    public function fncCheckFile()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );
        try {
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));

//            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');

            if (!file_exists($pathUpLoad)) {
                mkdir($pathUpLoad, 0777, TRUE);
            }
            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $this->FrmSCUrkIn = new FrmSCUrkIn();

                $file_name = $this->changeFileName($_FILES["file"]["name"]);
                //upload file
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
                } else {
                    $result['result'] = FALSE;
                    $result['data'] = 'ファイルのアップロードに失敗しました。';
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        // echo json_encode($result);
        $this->fncCheckFileReturn($result);
    }

    public function cmdActClick()
    {

        $postData = "";
        $this->objLog = $this->ClsCreateCsv->GS_OUTPUTLOG;

        $this->result = array();
        $this->result['MsgID'] = "E9999";
        try {
            $postData = $_POST['data']['request'];
            $txtFileName = $postData['FILENAME'];
            $radioChk = $postData['radioChk'];
            $cboYM = $postData['cboYM'];
            //$date = date("Y/m/d H:i:s");

            //$this -> fncOutLog($objLog, FALSE);
            //---
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $strErrLogPath = $strPath . "/" . $this->ClsComFnc->FncGetPath('PprErrLog');
            if (!file_exists($strErrLogPath)) {
                mkdir($strErrLogPath, 0777, TRUE);
            }
            $strLogPath = $strErrLogPath . "売掛金データ取込.log";
            $date = date("Y-m-d H:i:s");
            //構造体に格納(LOG)
            $this->objLog['strID'] = '売掛金データ取込み';
            //ログ出力先をｾｯﾄ
            $this->ClsCreateCsv->strLogName = $strLogPath;
            $this->objLog['strStartDate'] = $date;
            //開始LOG出力
            $this->ClsFncLog->fncStartLog($this->ClsCreateCsv->strLogName, $this->objLog);
            //---
            $this->FrmSCUrkIn = new FrmSCUrkIn();

            $res = $this->FrmSCUrkIn->Do_conn();

            if (!$res['result']) {
                $this->result['MsgID'] = 'E9999';
                $this->result['msgContent'] = $res['data'];
                throw new \Exception($res['data']);
            }
            //トランザクション開始
            $this->FrmSCUrkIn->Do_transaction();

            //人事関連データ取込み処理
            $this->objLog['strDataNM'] = "人事関連データ取込み";
            //--1
            $cboYM = str_replace("/", "", $cboYM);
            $result_fnc = $this->FncSCUrkIn($txtFileName, $cboYM, $radioChk);
            if (!$result_fnc) {
                //ﾗｰLOG出力
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //終了LOG出力
                $this->objLog['strState'] = "NG";
                $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                $this->result['data'] = 'error';
                $this->result['lblMSG'] = '処理を中断しました。';
                $this->result['msgContent'] = "取込処理はエラー終了しました。ログファイルを確認してください。";
                $this->result['MsgID'] = "";
                // throw new \Exception($result_fnc['data']);
                throw new \Exception($result_fnc);
            }
            $this->FrmSCUrkIn->Do_commit();
            $this->ClsCreateCsv->fncOutLog($this->ClsCreateCsv->strLogName, $this->objLog);
            //終了LOG出力
            $this->objLog['strEndDate'] = date("Y-m-d H:i:s");
            $this->objLog['strState'] = "OK";
            $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
            $this->result['data'] = "success";
            $this->result['result'] = TRUE;
            $this->result['lbljijCnt'] = $this->objLog['lngCount'];
            $this->result['msgContent'] = "処理が正常に終了しました。";
        } catch (\Exception $e) {
            $this->result['data'] = "";
            $this->result['result'] = FALSE;
            $this->FrmSCUrkIn->Do_rollback();
            $this->FrmSCUrkIn->Do_close();
        }

        $this->fncReturn($this->result);
    }

    //yushuangji add start
    public function FncSCUrkIn($strFileName, $cboYM, $radioChk)
    {
        $blnErr = FALSE;
        try {
            //----
            //計上年月のデータが存在する場合削除処理を行う
            $this->result = $this->FrmSCUrkIn->fncTableDelete_HSCURKZAN($cboYM, $radioChk);
            //----
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->objLog['lngCount'] = 0;
            //売掛金データ取込み
            //ｽﾄﾘｰﾑﾘｰﾀﾞを定義
            $lngRcCnt = 0;
            /*'------------------------------------
                         '   取込ﾁｪｯｸ処理
                         '------------------------------------
                         */
            $filename = $this->changeFileName($strFileName);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
//            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;
            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                //$this -> fncOutLog("対象ﾌｧｲﾙが存在していません。");
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            //------------------------------------
            //   取込ﾁｪｯｸ処理
            //------------------------------------
            //ｴﾗｰﾌﾗｸﾞを初期化
            $readFileArr = file($pathUpLoad);
            foreach ($readFileArr as $strRecord) {
                //終了判定
                if ($strRecord == "" || $strRecord == null) {
                    break;
                } elseif (ord($strRecord) == 26) {
                    break;
                }
                //読込みﾚｺｰﾄﾞをｶﾝﾏで分割
                $strRecArr = explode("\t", $strRecord);
                $tt = str_replace('"', '', $strRecArr[3]);
                if ($tt == "B2") {
                    //ﾚｺｰﾄﾞ件数をｶｳﾝﾄｱｯﾌﾟ
                    $lngRcCnt += 1;
                    /*'------------------------------------
                                       '   ﾚｺｰﾄﾞﾁｪｯｸ処理
                                       '------------------------------------
                                       */
                    //項目数をﾁｪｯｸ
                    if (count($strRecArr) != 33) {
                        $this->objLog['strErrMsg'] = $lngRcCnt . "行目：項目数が不正です。";
                        $blnErr = TRUE;
                        return FALSE;
                    }
                    //項目数が正常な場合のみ、項目のﾁｪｯｸ処理を行う
                    if (!$blnErr) {
                        // $this -> fncUrkChk($strRecArr, $lngRcCnt, &$blnErr);
                        $this->fncUrkChk($strRecArr, $lngRcCnt, $blnErr);
                        //正常ﾃﾞｰﾀの場合はDB登録
                        if (!$blnErr) {
                            $this->result = $this->FrmSCUrkIn->fncSCUrkZanInsert($cboYM, $strRecArr, $radioChk);
                            if (!$this->result['result']) {
                                //return;
                                throw new \Exception($this->result['data']);
                            }
                            $this->objLog['lngCount'] = $this->objLog['lngCount'] + 1;
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            $strErrMsg = "clsCsvIn \r\n FncJinjiCnv " . $ex->getMessage();
            $this->result['data'] = $ex->getMessage();
            $this->result['msgContent'] = $strErrMsg;
            $this->objLog['strErrMsg'] = $strErrMsg;
            $this->objLog["lngCount"] = -1;
            $this->exceptionTF = TRUE;
            return false;
        }
        return true;

    }

    public function fncUrkChk($strRecArr, $lngRcCnt, $blnErr)
    {
        $strRecArr[20] = mb_convert_encoding($strRecArr[20], 'SJIS');
        $ErrRcCnt = $lngRcCnt + 1;
        if (mb_strlen($this->ClsComFnc->FncNv($strRecArr[20]), "SJIS") > 10) {
            $this->objLog['strErrMsg'] = $ErrRcCnt . "行目：注文書番号の桁数が不正です。（10ﾊﾞｲﾄ以下）" . $strRecArr[20];
            $blnErr = TRUE;
        }
        return TRUE;
    }
    //yushuangji add end
}