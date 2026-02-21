<?php
/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * 20151021           #2184                        BUG                              Yuanjh
 * 20151105			  #2260                        BUG                              Yinhuaiyu
 * 20170809           -----                        無償整備台数取込           HM                                 
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSeibiDaisuIn;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmSeibiDaisuInController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public $FrmSeibiDaisuIn;
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmSeibiDaisuIn_layout');
    }

    public function changeFileName($param)
    {
        $strUserID = $this->FrmSeibiDaisuIn->GS_LOGINUSER['strUserID'];
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

    public function fncOutLog($strOutMsg, $blnAppend = TRUE)
    {
        $strPath = dirname(dirname(dirname(dirname(__FILE__))));
        $strErrLogPath = $strPath . "/" . $this->ClsComFnc->FncGetPath('PprErrLog');
        if (!file_exists($strErrLogPath)) {
            mkdir($strErrLogPath, 0777, TRUE);
        }
        $strLogPath = $strErrLogPath . "整備台数表取込.log";
        if ($blnAppend) {

            $objSw = fopen($strLogPath, "a");
        } else {
            $objSw = fopen($strLogPath, "w");
        }

        fwrite($objSw, $strOutMsg);
        fclose($objSw);
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
                $this->FrmSeibiDaisuIn = new FrmSeibiDaisuIn();

                $file_name = $this->changeFileName($_FILES["file"]["name"]);

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

        $this->fncCheckFileReturn($result);
    }

    public function cmdActClick()
    {
        $blnErr = FALSE;
        $postData = "";
        $result = array(
            'result' => 'false',
            'MsgID' => ''
        );
        try {

            $postData = $_POST['data']['request'];

            $date = date("Y/m/d H:i:s");

            $this->fncOutLog("取込開始:" . $date . "\r\n", FALSE);

            $this->FrmSeibiDaisuIn = new FrmSeibiDaisuIn();

            $res = $this->FrmSeibiDaisuIn->Do_conn();

            if (!$res['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmSeibiDaisuIn->Do_transaction();

            //振替ﾃﾞｰﾀを初期化
            $res = $this->FrmSeibiDaisuIn->fncFurikaeDelete($postData);

            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                $this->fncOutLog($res['data']);
                throw new \Exception($res['data']);
            }

            $res = "";
            //残高ﾃﾞｰﾀを初期化
            $res = $this->FrmSeibiDaisuIn->fncZandakaDelete($postData);

            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                $this->fncOutLog($res['data']);
                throw new \Exception($res['data']);
            }
            $res = "";

            //指定ﾌｧｲﾙの情報をﾃｰﾌﾞﾙへ取り込む
            $res = $this->fncFileRead($postData);
            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = $res['MsgID'];
                throw new \Exception($res['data']);
            }

            $this->FrmSeibiDaisuIn->Do_commit();
            $date = date("Y/m/d H:i:s");
            $this->fncOutLog("正常終了:" . $date);
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;

            $result['data'] = $e->getMessage();

        }

        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmSeibiDaisuIn->Do_rollback();
            $this->FrmSeibiDaisuIn->Do_close();
        }

        $this->fncReturn($result);
    }

    public function fncFileRead($postData = NULL)
    {
        $blnErr = FALSE;

        $result = array(
            'result' => 'false',
            'MsgID' => '',
            'data' => ''
        );
        try {
            $filename = $this->changeFileName($postData['FILENAME']);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;
            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                $this->fncOutLog("対象ﾌｧｲﾙが存在していません。");
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            $strGetArray = $this->ExcelRead($pathUpLoad);

            if ($strGetArray['result']) {
                $strGetArray = $strGetArray['data'];
                $intArrayCnt = count((array) $strGetArray);

                if ($intArrayCnt == 0) {
                    $result['MsgID'] = 'I0001';
                    $this->fncOutLog("データが存在しません。");
                    throw new \Exception("データが存在しません。");
                }

                $res1 = $this->FrmSeibiDaisuIn->fncUpdHksaiban(1);
                if (!$res1['result']) {
                    $result['MsgID'] = 'E9999';
                    $this->fncOutLog($res1['data']);
                    throw new \Exception($res1['data']);
                }

                $lngSeqNO = 1;
                $res2 = $this->FrmSeibiDaisuIn->fncSelHksaiban();

                if (!$res2['result']) {
                    $result['MsgID'] = 'E9999';
                    $this->fncOutLog($res2['data']);
                    throw new \Exception($res2['data']);
                }

                if ($res2['row'] > 0) {
                    //$lngSeqNO = $res2['data']['SEQNO'];
                    $lngSeqNO = $res2['data'][0]['SEQNO'];
                }
                $intFlg = 0;
                $intGyoNO = 1;
                foreach ((array) $strGetArray as $key => $value) {

                    if ($key == $intArrayCnt - 1) {
                        break;
                    }
                    if (!$blnErr) {
                        $blnArr = $this->fncCheckRecord($value, $key + 1, $intArrayCnt - 1, $blnErr, $postData);

                        $blnErr = $blnArr['result'];

                        if ($blnErr) {
                            $result['MsgID'] = 'W9999';
                            throw new \Exception($blnArr['errMsg']);
                        }

                        if ($this->ClsComFnc->FncNv(rtrim($value[13] ? (string) $value[13] : '')) != "") {
                            //--20151021  Yuanjh UPD  S.
                            //for ($i = 0; $i < 13; $i++)
                            for ($i = 1; $i <= 13; $i++)
                            //--20151021  Yuanjh UPD  E.
                            {
                                //20151105 Yin UPD S
                                //if ($this -> ClsComFnc -> FncNz($value[$i]) != 0)
                                if ($this->ClsComFnc->FncNz($value[$i - 1]) != 0)
                                    //20151105 Yin UPD E
                                    /*
                                                                   switch ($i)
                                                                   {
                                                                       case '4' :
                                                                           break;
                                                                       case '5' :
                                                                           break;
                                                                       case '9' :
                                                                           $res = "";
                                                                           if ($intFlg == 0)
                                                                           {
                                                                               $res = $this -> FrmSeibiDaisuIn -> fncGetZandakaInsert($value, $postData);
                                                                               if (!$res['result'])
                                                                               {
                                                                                   $result['MsgID'] = 'E9999';
                                                                                   $this -> fncOutLog($res['data']);
                                                                                   throw new Exception($res['data']);
                                                                               }
                                                                           }
                                                                           $intFlg = $intFlg + 1;
                                                                           break;
                                                                       case '10' :
                                                                           $res = "";
                                                                           if ($intFlg == 0)
                                                                           {
                                                                               $res = $this -> FrmSeibiDaisuIn -> fncGetZandakaInsert($value, $postData);
                                                                               if (!$res['result'])
                                                                               {
                                                                                   $result['MsgID'] = 'E9999';
                                                                                   $this -> fncOutLog($res['data']);
                                                                                   throw new Exception($res['data']);
                                                                               }
                                                                           }
                                                                           $intFlg = $intFlg + 1;
                                                                           $res = "";
                                                                           $res = $this -> FrmSeibiDaisuIn -> fncGetFurikaeInsert($value, $postData, $lngSeqNO, $intGyoNO, $i);
                                                                           if (!$res['result'])
                                                                           {
                                                                               $result['MsgID'] = 'E9999';
                                                                               $this -> fncOutLog($res['data']);
                                                                               throw new Exception($res['data']);
                                                                           }
                                                                           $intGyoNO = $intGyoNO + 1;
                                                                           break;
                                                                       default :
                                                                           $res = "";
                                                                           $res = $this -> FrmSeibiDaisuIn -> fncGetFurikaeInsert($value, $postData, $lngSeqNO, $intGyoNO, $i);
                                                                           if (!$res['result'])
                                                                           {
                                                                               $result['MsgID'] = 'E9999';
                                                                               $this -> fncOutLog($res['data']);
                                                                               throw new Exception($res['data']);
                                                                           }
                                                                           $intGyoNO = $intGyoNO + 1;
                                                                           break;
                                                                   }
                                                                                                           */
                                    switch ($i) {
                                        case '5':
                                            break;
                                        // case '6':
                                        //     break;
                                        case '10':
                                            $res = "";
                                            if ($intFlg == 0) {
                                                //残高ﾌｧｲﾙINSERT
                                                $res = $this->FrmSeibiDaisuIn->fncGetZandakaInsert($value, $postData);
                                                if (!$res['result']) {
                                                    $result['MsgID'] = 'E9999';
                                                    $this->fncOutLog($res['data']);
                                                    throw new \Exception($res['data']);
                                                }
                                            }
                                            $intFlg = $intFlg + 1;
                                            break;
                                        case '11':
                                            $res = "";
                                            if ($intFlg == 0) {
                                                //残高ﾌｧｲﾙINSERT
                                                $res = $this->FrmSeibiDaisuIn->fncGetZandakaInsert($value, $postData);
                                                if (!$res['result']) {
                                                    $result['MsgID'] = 'E9999';
                                                    $this->fncOutLog($res['data']);
                                                    throw new \Exception($res['data']);
                                                }
                                            }
                                            $intFlg = $intFlg + 1;
                                            $res = "";
                                            //振替ﾃﾞｰﾀINSERT
                                            $res = $this->FrmSeibiDaisuIn->fncGetFurikaeInsert($value, $lngSeqNO, $intGyoNO, $i, $postData);
                                            if (!$res['result']) {
                                                $result['MsgID'] = 'E9999';
                                                $this->fncOutLog($res['data']);
                                                throw new \Exception($res['data']);
                                            }
                                            $intGyoNO = $intGyoNO + 1;
                                            break;
                                        default:
                                            //振替ﾃﾞｰﾀINSERT
                                            $res = "";
                                            $res = $this->FrmSeibiDaisuIn->fncGetFurikaeInsert($value, $lngSeqNO, $intGyoNO, $i, $postData);
                                            if (!$res['result']) {
                                                $result['MsgID'] = '1E9999';
                                                $this->fncOutLog($res['data']);
                                                throw new \Exception($res['data']);
                                            }
                                            $intGyoNO = $intGyoNO + 1;
                                            break;
                                    }
                            }
                            $intFlg = 0;
                        }

                    }
                }

                $result['result'] = TRUE;
            } else {
                //excel 处理异常
                $result['MsgID'] = 'W9999';
                $this->fncOutLog($strGetArray['data']);
                throw new \Exception((string) $strGetArray['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            // $this -> fncOutLog($e -> getMessage());
        }

        return $result;
    }

    public function fncCheckRecord($strRecArr, $lngRcCnt, $intArrayCnt, $blnErr, $postData)
    {

        $miss = 0;
        $errMsg = '';
        $ErrRcCnt = $lngRcCnt + 6;
        //有償整備車検台数
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[0] ? (string) $strRecArr[0] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[0]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：有償整備車検台数が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }

        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[0] ? (string) $strRecArr[0] : ''))) > 13) {
            $errMsg = $ErrRcCnt . "行目：有償整備車検台数の桁数が不正です。（9999999999999以下）" . $strRecArr[0] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }
        //有償整備定期点検台数
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[1] ? (string) $strRecArr[1] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[1]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：有償整備定期点検台数が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[1] ? (string) $strRecArr[1] : ''))) > 13) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "有償整備定期点検台数の桁数が不正です。（9999999999999以下）" . $strRecArr[1] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //有償整備一般台数
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[2] ? (string) $strRecArr[2] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[2]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：有償整備一般台数が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[2] ? (string) $strRecArr[2] : ''))) > 13) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：有償整備一般台数の桁数が不正です。（9999999999999以下）" . $strRecArr[2] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //有償整備外装台数
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[3] ? (string) $strRecArr[3] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[3]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：有償整備外装台数が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[3] ? (string) $strRecArr[3] : ''))) > 13) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：有償整備外装台数の桁数が不正です。（9999999999999以下）" . $strRecArr[3] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //対象保有台数車検
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[6] ? (string) $strRecArr[6] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[6]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：対象保有台数車検が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[6] ? (string) $strRecArr[6] : ''))) > 13) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：対象保有台数車検の桁数が不正です。（9999999999999以下）" . $strRecArr[6] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }

        //対象保有台数定期点検
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[7] ? (string) $strRecArr[7] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[7]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：対象保有台数定期点検が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[7] ? (string) $strRecArr[7] : ''))) > 13) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：対象保有台数定期点検の桁数が不正です。（9999999999999以下）" . $strRecArr[7] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }

        //対象内車検台数
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[8] ? (string) $strRecArr[8] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[8]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：対象内車検台数が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[8] ? (string) $strRecArr[8] : ''))) > 13) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：対象内車検台数の桁数が不正です。（9999999999999以下）" . $strRecArr[8] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //カルテ保有台数前々月末
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[9] ? (string) $strRecArr[9] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[9]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：カルテ保有台数前々月末が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[9] ? (string) $strRecArr[9] : ''))) > 11) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：カルテ保有台数前々月末の桁数が不正です。（99999999999以下）" . $strRecArr[9] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //カルテ保有台数前月末
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[10] ? (string) $strRecArr[10] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[10]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：カルテ保有台数前月末が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[10] ? (string) $strRecArr[10] : ''))) > 11) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：カルテ保有台数前月末の桁数が不正です。（99999999999以下）" . $strRecArr[10] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //整備値引
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[11] ? (string) $strRecArr[11] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[11]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：整備値引が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[11] ? (string) $strRecArr[11] : ''))) > 13) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：整備値引の桁数が不正です。（99999999999以下）" . $strRecArr[11] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //納引値引
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[12] ? (string) $strRecArr[12] : '')) != "") {
            if (!is_numeric(rtrim($strRecArr[12]))) {
                if ($lngRcCnt < $intArrayCnt) {
                    $errMsg = $ErrRcCnt . "行目：納引値引が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[12] ? (string) $strRecArr[12] : ''))) > 13) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：納引値引の桁数が不正です。（99999999999以下）" . $strRecArr[12] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //部署コード
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[13] ? (string) $strRecArr[13] : ''))) > 3) {
            $errMsg = $ErrRcCnt . "行目：部署コードの桁数が不正です。（3ﾊﾞｲﾄ以下）" . $strRecArr[13] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[13] ? (string) $strRecArr[13] : '')) == "") {

            $errMsg = $ErrRcCnt . "行目：社員番号が未入力です" . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }

        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;

        //年月
        if ($ymd != str_replace("/", "", $this->ClsComFnc->FncNv($strRecArr[14]))) {
            $errMsg = $ErrRcCnt . "行目：年月が不正です。" . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }

        if ($miss == 1) {
            $res = array(
                'result' => $blnErr,
                'MsgID' => 'W9999',
                'errMsg' => $errMsg
            );
        }
        if ($miss > 1) {
            $res = array(
                'result' => $blnErr,
                'MsgID' => 'W9999',
                'errMsg' => '取込処理はエラー終了しました。ログファイルを確認して下さい。' . "\r\n"
            );
        }
        if ($miss == 0) {
            $res = array(
                'result' => $blnErr,
                'errMsg' => ''
            );
        }

        return $res;

    }

    public function frmSampleLoad()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmSeibiDaisuIn = new FrmSeibiDaisuIn();

            $result = $this->FrmSeibiDaisuIn->frmSampleLoadDate();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {

            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function ExcelRead($path)
    {
        // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";
        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );

            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {
                $objReader = IOFactory::createReader('Xlsx');
            } else {
                $objReader = IOFactory::createReader('Xls');
            }

            $objReader->setReadDataOnly(true);

            $objPHPExcel = $objReader->load($path);
            $worksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $rowarr = array();
            $arr = array(
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O'
            );

            for ($row = 7; $row <= $highestRow; $row++) {
                $col = array();
                $rowNothing = TRUE;
                foreach ($arr as $value) {

                    $val = $worksheet->getCell($value . $row)->getCalculatedValue();

                    if ($val != "") {
                        $rowNothing = FALSE;
                    }
                    array_push($col, $val);

                }

                $val = $worksheet->getCell('P' . $row)->getCalculatedValue();

                if (($this->ClsComFnc->FncNv($val)) != "") {
                    $rowNothing = FALSE;

                }
                $val1 = "";
                $this->ClsComFnc->FncDateChange2('H', $this->ClsComFnc->FncNv($val), $val1);

                array_push($col, $val1);

                if ($rowNothing) {
                    break;
                }
                array_push($rowarr, $col);
            }

            $result = array(
                'result' => TRUE,
                'data' => $rowarr
            );

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;

    }

}