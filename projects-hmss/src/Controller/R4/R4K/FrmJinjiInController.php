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
 * 20151208           #2227                                                         li
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\Component\ClsComFnc;
use App\Model\R4\R4K\FrmJinjiIn;

//*******************************************
// * sample controller
//*******************************************
class FrmJinjiInController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $objLog;
    public $result;
    public $exceptionTF = FALSE;
    public $blnErr = FALSE;
    public $FrmJinjiIn;
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
        $this->render('index', 'FrmJinjiIn_layout');
    }

    public function frmSample_Load()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {
            $this->FrmJinjiIn = new FrmJinjiIn();

            $result = $this->FrmJinjiIn->frmSampleLoadDate();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function changeFileName($param)
    {
        $strUserID = $this->FrmJinjiIn->GS_LOGINUSER['strUserID'];
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
                //フォルダ権限の判断
                $outFloder = dirname($pathUpLoad);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                mkdir($pathUpLoad, 0777, TRUE);
            } else {
                if (!(is_readable($pathUpLoad) && is_writable($pathUpLoad) && is_executable($pathUpLoad))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }
            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $this->FrmJinjiIn = new FrmJinjiIn();

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
        try {
            $postData = $_POST['data']['request'];
            $txtJinjiName = $postData['FILENAME'];
            $chkRtrajJin = $postData['chkRtraiJin'];
            //$date = date("Y/m/d H:i:s");

            //$this -> fncOutLog($objLog, FALSE);
            //---
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $strErrLogPath = $strPath . "/" . $this->ClsComFnc->FncGetPath('PprErrLog');
            if (!file_exists($strErrLogPath)) {
                mkdir($strErrLogPath, 0777, TRUE);
            }
            $strLogPath = $strErrLogPath . "人事データ取込.log";
            $date = date("Y/m/d H:i:s");
            //構造体に格納(LOG)
            $this->objLog['strID'] = '人事関連データ取込み';
            //ログ出力先をｾｯﾄ
            $this->ClsCreateCsv->strLogName = $strLogPath;
            $this->objLog['strStartDate'] = $date;
            //開始LOG出力
            $this->ClsFncLog->fncStartLog($this->ClsCreateCsv->strLogName, $this->objLog);
            //---
            $this->FrmJinjiIn = new FrmJinjiIn();

            $res = $this->FrmJinjiIn->Do_conn();

            if (!$res['result']) {
                $this->result['MsgID'] = 'E9999';
                $this->result['msgContent'] = $res['data'];
                throw new \Exception($res['data']);
            }
            //トランザクション開始
            $this->FrmJinjiIn->Do_transaction();

            //人事関連データ取込み処理
            $this->objLog['strDataNM'] = "人事関連データ取込み";
            //--1
            $result_fnc = $this->FncJinjiCnv($txtJinjiName, $chkRtrajJin);
            if (!$result_fnc) {
                //ﾗｰLOG出力
                $this->ClsCreateCsv->fncErrLog($this->ClsCreateCsv->strLogName, $this->objLog);
                //終了LOG出力
                $this->objLog['strState'] = "NG";
                $this->objLog['strEndDate'] = date("Y/m/d H:i:s");
                $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
                $this->result['lblMSG'] = '処理を中断しました。';
                // throw new \Exception($result_fnc['data']);
                throw new \Exception($result_fnc);
            }
            $this->FrmJinjiIn->Do_commit();
            $this->ClsCreateCsv->fncOutLog($this->ClsCreateCsv->strLogName, $this->objLog);
            //終了LOG出力
            $this->objLog['strEndDate'] = date("Y/m/d H:i:s");
            $this->objLog['strState'] = "OK";
            $this->result['msgContent'] = "処理が正常に終了しました。";
            $this->ClsCreateCsv->fncEndLog($this->ClsCreateCsv->strLogName, $this->objLog);
            $this->result['data'] = "success";
            $this->result['result'] = TRUE;
            $this->result['lbljijCnt'] = $this->objLog['lngCount'];
        } catch (\Exception $e) {
            $this->result['data'] = "";
            $this->result['result'] = FALSE;
            $this->FrmJinjiIn->Do_rollback();
            $this->FrmJinjiIn->Do_close();
        }

        $this->fncReturn($this->result);
    }

    //yushuangji add start
    public function FncJinjiCnv($strFileName, $blnDelFlg)
    {
        try {
            //取込みＣＳＶﾃﾞｰﾀをワークＤＢに登録する
            $this->result = $this->FrmJinjiIn->fncTableDelete("WK_CNVDATA");

            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //人事ﾃﾞｰﾀのフォーマット変更のため(最終行にカンマ追加)
            if (!$this->fncFileRead($strFileName, "WK_CNVDATA", 11, $this->objLog)) {
                return false;
            }
            //科目コード変換更新（（TMrh）ｺｰﾄﾞ-->Rｺｰﾄﾞ）
            $this->result = $this->FrmJinjiIn->fncUPDWK_CNVDATA();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //初期化指定の場合　対象ﾃｰﾌﾞﾙ初期化
            if ($blnDelFlg == FALSE || $blnDelFlg == "true") {
                $this->result = $this->FrmJinjiIn->fncDELHFURIKAE();
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
            }

            //振替データに登録する
            //--- 20151208 LI INS S
            $ClsComFnc = new ClsComFnc();
            //--- 20151208 LI INS E
            $this->result = $this->FrmJinjiIn->fncSELECTWK_CNVDATA();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            } elseif (count($this->result['data']) > 0) {
                //データが存在する場合
                foreach ($this->result['data'] as $value) {
                    //--- 20151208 LI UPD S
                    // $this -> result = $this -> FrmJinjiIn -> fncJinjiInsert($value);
                    $this->result = $this->FrmJinjiIn->fncJinjiInsert($value, $ClsComFnc);
                    //--- 20151208 LI UPD E
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                    $this->objLog['lngCount'] = (int) $this->objLog['lngCount'] + 1;
                }
            }
            //初期化指定の場合　営業所人員ﾃｰﾌﾞﾙ初期化
            if ($blnDelFlg == FALSE || $blnDelFlg == "true") {
                $this->result = $this->FrmJinjiIn->fncDELETEHEIJININ();
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
            }
            //営業所人員データに登録する
            $this->result = $this->FrmJinjiIn->fncEijinInsert();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            return TRUE;
        } catch (\Exception $ex) {
            $strErrMsg = $ex->getMessage();
            $this->result['msgContent'] = $strErrMsg;
            $this->objLog['strErrMsg'] = $strErrMsg;
            $this->objLog["lngCount"] = -1;
            $this->exceptionTF = TRUE;
            return false;
        }

    }

    public function fncFileRead($strFileName, $strTableName, $lngItemNum, $objLog)
    {
        $lngRctCnt = 0;
        $strRecArr = array();
        $sqlstr = "";

        try {
            //------------------------------------
            //   初期処理
            //------------------------------------
            //
            //INSERT文の取得
            $sqlstr_ins = $this->FrmJinjiIn->fncGetSqlInsert_sql($strTableName, $lngItemNum);
            //ｽﾄﾘｰﾑﾘｰﾀﾞを定義
            $filename = $this->changeFileName($strFileName);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;
            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                //$this -> fncOutLog("対象ﾌｧｲﾙが存在していません。");
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            //------------------------------------
            //   取込ﾁｪｯｸ処理
            //------------------------------------
            //ｴﾗｰﾌﾗｸﾞを初期化
            $readFileArr = file($pathUpLoad);
            foreach ($readFileArr as $strRecord) {
                $sqlstr = "";
                //終了判定
                if ($strRecord == "" || $strRecord == null) {
                    break;
                } elseif (ord($strRecord) == 26) {
                    break;
                }
                //実行ｸｴﾘの初期化

                //ﾚｺｰﾄﾞ件数をｶｳﾝﾄｱｯﾌﾟ
                $lngRctCnt += 1;
                //""を取り除く
                //読込みﾚｺｰﾄﾞをｶﾝﾏで分割
                $strRecArr = explode(",", $strRecord);
                //項目数が正常な場合のみ、項目のﾁｪｯｸ処理を行う
                if (!$this->blnErr) {
                    if (count($strRecArr) <= 5) {
                        throw new \Exception("Index was outside the bounds of the array.", 1);
                    }
                    // $this -> fncCheckRecord($strRecArr, $lngRctCnt, &$blnErr);
                    $this->fncCheckRecord($strRecArr, $lngRctCnt, $this->blnErr);
                    if (!$this->blnErr) {
                        if (round(floatval($this->ClsComFnc->FncNz($strRecArr[6]))) != 0) {
                            //更新項目を設定
                            $sqlstr .= " VALUES (";
                            $sqlstr .= " '01' ";
                            for ($i = 0; $i <= $lngItemNum - 3; $i++) {
                                $sqlstr .= " ,";
                                if ($i > count($strRecArr) - 1) {
                                    $sqlstr .= "''\n";
                                } else {
                                    if ($i == 6) {
                                        $sqlstr .= $this->ClsComFnc->FncSqlNv(trim(round(floatval($strRecArr[$i]))));
                                    } else {
                                        $sqlstr .= $this->ClsComFnc->FncSqlNv(trim($strRecArr[$i]));
                                    }
                                }
                            }
                            $sqlstr .= ")";
                            //ｸｴﾘ実行

                            $result = $this->FrmJinjiIn->Fnc_ExecuteScalar($sqlstr_ins . $sqlstr);
                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            } else {
                                //if ($result['row'] <= 0)
                                if ($result['number_of_rows'] <= 0) {
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
            if ($this->blnErr) {
                return false;
            }
            return true;
        } catch (\Exception $ex) {
            $this->result['msgContent'] = $ex->getMessage();
            $this->result['msgID'] = "";
            $this->exceptionTF = TRUE;
            $this->objLog['strErrMsg'] = "clsDataConvert \r\n FncSCGENJCnv \r\n" . $ex->getMessage();
            $this->objLog['lngCount'] = -1;
            return false;
        }
    }

    public function fncCheckRecord($strRecArr, $lngRctCnt, $blnErr)
    {
        if (is_numeric($strRecArr[6]) == FALSE) {
            $this->objLog['strErrMsg'] = $lngRctCnt . "行目：金額が数値ではありません";
            $this->blnErr = TRUE;
        }
        return true;
    }

    //yushuangji add end
}
