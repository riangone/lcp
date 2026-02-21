<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmNinhoIn;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　
 * 20150916			  #2143						   BUG								yinhuaiyu
 *　　　　　
 * * --------------------------------------------------------------------------------------------
 */
class FrmNinhoInController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmNinhoIn = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmNinhoIn_layout');
    }

    public function changeFileName($param)
    {
        $strUserID = $this->FrmNinhoIn->GS_LOGINUSER['strUserID'];
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
        $strLogPath = $strErrLogPath . "任意保険データ取込.log";
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
            //$pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');

            if (!file_exists($pathUpLoad)) {
                mkdir($pathUpLoad, 0777, TRUE);
            }

            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $this->FrmNinhoIn = new FrmNinhoIn();

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

            $this->FrmNinhoIn = new FrmNinhoIn();

            $res = $this->FrmNinhoIn->Do_conn();

            if (!$res['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmNinhoIn->Do_transaction();


            $res = $this->FrmNinhoIn->fncTableDelete($postData);
            //
            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                $this->fncOutLog($res['data']);
                throw new \Exception($res['data']);
            }
            $res = "";
            $res = $this->fncFileRead($postData);
            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = $res['MsgID'];
                throw new \Exception($res['data']);
            }

            $this->FrmNinhoIn->Do_commit();
            $date = date("Y/m/d H:i:s");
            $this->fncOutLog("正常終了:" . $date);
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;

            $result['data'] = $e->getMessage();

        }

        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmNinhoIn->Do_rollback();
            $this->FrmNinhoIn->Do_close();
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
            //$pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;
            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                $this->fncOutLog("対象ﾌｧｲﾙが存在していません。");
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            $Month = $postData['KEIJOBI'];
            $Month = explode('/', $Month);
            $Month = $Month[1];
            if ($Month < 10) {
                $intTougetu = $Month + 5;
            } else {
                $intTougetu = $Month - 7;
            }

            $strGetArray = $this->ExcelRead($pathUpLoad);

            if ($strGetArray['result']) {

                $strGetArray = $strGetArray['data'];

                $intArrayCnt = count($strGetArray);

                if ($intArrayCnt % 4 != 0) {
                    $result['MsgID'] = 'W9999';
                    $mesg = "1明細4行中にＡ列からＱ列までに空白の行が存在しています！(" . ($intArrayCnt + 2) . "行目)";
                    $this->fncOutLog($mesg);
                    throw new \Exception($mesg);
                }
                foreach ($strGetArray as $key => $value) {
                    if ($key % 4 == 0) {
                        $blnArr = array();
                        if (!$blnErr) {
                            $errMsg = '';
                            $missTotal = 0;
                            for ($i = 0; $i <= 3; $i++) {
                                $blnArr = $this->fncCheckRecord($strGetArray[$key + $i], $key + 1 + $i, $intArrayCnt - 1, $intTougetu, $blnErr, $missTotal, $errMsg);

                                $errMsg = $blnArr['errMsg'];
                                $missTotal = $blnArr['miss'];
                                $blnErr = $blnArr['result'];
                            }
                            if ($blnErr) {
                                $result['MsgID'] = 'W9999';
                                throw new \Exception($blnArr['errMsg']);
                            }

                            if (($this->ClsComFnc->FncNz(rtrim($strGetArray[$key + 1][$intTougetu])) == 0) && ($this->ClsComFnc->FncNz(rtrim($strGetArray[$key][$intTougetu])) == 0)) {
                                $lngHokenryo = $this->ClsComFnc->FncNz(rtrim($strGetArray[$key + 3][$intTougetu]));
                            } else {
                                $lngHokenryo = $this->ClsComFnc->FncNz(rtrim($strGetArray[$key + 3][$intTougetu]));
                                $countVal1 = ($this->ClsComFnc->FncNz(rtrim($strGetArray[$key][$intTougetu]))) * 2000;
                                $countVal2 = ($this->ClsComFnc->FncNz(rtrim($strGetArray[$key + 1][$intTougetu]))) * 400;
                                $lngHokenryo = $lngHokenryo - $countVal1 + $countVal2;
                            }

                            $lngHokenryo = $this->ClsComFnc->Dou_to_long($lngHokenryo);
                            $res = $this->FrmNinhoIn->ExcuteFncGetSqlInsert($value, $postData, $lngHokenryo, $strGetArray[$key + 2]);
                            if (!$res['result']) {
                                $result['MsgID'] = 'E9999';
                                $this->fncOutLog($res['data']);
                                throw new \Exception($res['data']);
                            }
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

    public function fncCheckRecord($strRecArr, $lngRcCnt, $intArrayCnt, $intTougetu, $blnErr, $missTotal, $errMsg)
    {
        // $miss = 0;
        // $errMsg = '';
        $ErrRcCnt = $lngRcCnt + 1;
        if ($lngRcCnt % 4 == 1) {
            //部署ｺｰﾄﾞ

            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[0]))) > 3) {
                $errMsg = $ErrRcCnt . "行目：部署コードの桁数が不正です。（3ﾊﾞｲﾄ以下）" . $strRecArr[0] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $missTotal = $missTotal + 1;
            }
            //社員番号
            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[1]))) > 5) {
                $errMsg = $ErrRcCnt . "行目：社員番号の桁数が不正です。（5ﾊﾞｲﾄ以下）" . $strRecArr[1] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $missTotal = $missTotal + 1;
            }
            if ($this->ClsComFnc->FncNv(rtrim($strRecArr[1])) == "") {
                $errMsg = $ErrRcCnt . "行目：社員番号が未入力です" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $missTotal = $missTotal + 1;
            }
            //社員名
            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[2]))) > 30) {
                $errMsg = $ErrRcCnt . "行目：社員名桁数が不正です。（30ﾊﾞｲﾄ以下）" . $strRecArr[2] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $missTotal = $missTotal + 1;
            }

        }
        //保険料
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[$intTougetu])) != "") {
            if (!is_numeric(rtrim($strRecArr[$intTougetu]))) {
                $errMsg = $ErrRcCnt . "行目：保険料が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $missTotal = $missTotal + 1;
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[$intTougetu]))) > 9) {
            $errMsg = $ErrRcCnt . "行目：保険料の桁数が不正です。（999999999以下）" . $strRecArr[$intTougetu] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $missTotal = $missTotal + 1;
        }

        if ($lngRcCnt % 4 == 3) {
            //備考
            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[16]))) > 20) {
                $errMsg = $ErrRcCnt . "行目：備考の桁数が不正です。（20ﾊﾞｲﾄ以下）" . $strRecArr[16] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $missTotal = $missTotal + 1;
            }
        }

        if ($missTotal == 1) {
            $res = array(
                'result' => $blnErr,
                'MsgID' => 'W9999',
                'errMsg' => $errMsg,
                'miss' => $missTotal
            );

        }
        if ($missTotal > 1) {
            $res = array(
                'result' => $blnErr,
                'MsgID' => 'W9999',
                'errMsg' => '取込処理はエラー終了しました。ログファイルを確認して下さい。' . "\r\n",
                'miss' => $missTotal
            );
        }
        if ($missTotal == 0) {
            $res = array(
                'result' => $blnErr,
                'errMsg' => '',
                'miss' => $missTotal
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

            $this->FrmNinhoIn = new FrmNinhoIn();

            $result = $this->FrmNinhoIn->frmSampleLoadDate();

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
        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );

            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {
                $reader = IOFactory::createReader('Xlsx');
            } else {
                $reader = IOFactory::createReader('Xls');
            }

            $objPHPExcel = $reader->load($path);

            $worksheet = $objPHPExcel->getSheet(0);

            $highestRow = $worksheet->getHighestRow();
            $rowarr = array();
            $arr = array(
                'A',
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
                'O',
                'P',
                'Q'
            );

            for ($row = 2; $row <= $highestRow; $row++) {
                $col = array();
                $rowNothing = TRUE;
                foreach ($arr as $value) {

                    if ($value == 'A' || $value == 'B' || $value == 'C' || $value == 'Q') {
                        $val = $worksheet->getCell($value . $row)->getCalculatedValue();
                    } else {
                        //20150916 yinhuaiyu mod s
                        $val = $worksheet->getCell($value . $row)->getCalculatedValue();
                        //20150916 yinhuaiyu mod e
                    }
                    if ($val != "" && $val !== null) {
                        $rowNothing = FALSE;
                    }
                    if ($val === null) {
                        $val = '';
                    }
                    array_push($col, $val);

                }
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