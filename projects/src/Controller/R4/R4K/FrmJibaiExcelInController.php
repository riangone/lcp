<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmJibaiExcelIn;
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
 * 20150915			  #2140						   BUG								yinhuaiyu
 *　　　　　
 * * --------------------------------------------------------------------------------------------
 */
class FrmJibaiExcelInController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmJibaiExcelIn = '';
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

        $this->render('index', 'FrmJibaiExcelIn_layout');
    }

    public function changeFileName($param)
    {
        $strUserID = $this->FrmJibaiExcelIn->GS_LOGINUSER['strUserID'];
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
        $strLogPath = $strErrLogPath . "自賠責データ取込.log";
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
                $this->FrmJibaiExcelIn = new FrmJibaiExcelIn();

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

            $this->FrmJibaiExcelIn = new FrmJibaiExcelIn();

            $res = $this->FrmJibaiExcelIn->Do_conn();

            if (!$res['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmJibaiExcelIn->Do_transaction();

            // $blnTrn = TRUE;

            $res = $this->FrmJibaiExcelIn->fncTableDelete($postData);
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

            $this->FrmJibaiExcelIn->Do_commit();
            $date = date("Y/m/d H:i:s");
            $this->fncOutLog("正常終了:" . $date);
            // $blnTrn = FALSE;
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;

            $result['data'] = $e->getMessage();

        }

        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmJibaiExcelIn->Do_rollback();
            $this->FrmJibaiExcelIn->Do_close();
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

            $strGetArray = $this->ExcelRead($pathUpLoad);
            if ($strGetArray['result']) {
                $strGetArray = $strGetArray['data'];
                $intArrayCnt = count($strGetArray);

                foreach ($strGetArray as $key => $value) {
                    if (!$blnErr) {
                        $blnArr = $this->fncCheckRecord($value, $key + 1, $intArrayCnt - 1, $blnErr);

                        $blnErr = $blnArr['result'];

                        if ($blnErr) {
                            $result['MsgID'] = 'W9999';
                            throw new \Exception($blnArr['errMsg']);
                        }

                        if ($this->ClsComFnc->FncNv(rtrim($value[1])) != "") {
                            $res = $this->FrmJibaiExcelIn->ExcuteFncGetSqlInsert($value, $postData);
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

    public function fncCheckRecord($strRecArr, $lngRcCnt, $intArrayCnt, $blnErr)
    {

        $miss = 0;
        $errMsg = '';
        $ErrRcCnt = $lngRcCnt + 4;
        //社員番号
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[1]))) > 5) {
            $errMsg = $ErrRcCnt . "行目：社員番号の桁数が不正です。（5ﾊﾞｲﾄ以下）" . $strRecArr[1] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[1])) == "" && (($this->ClsComFnc->FncNz(rtrim($strRecArr[3] != 0))) || ($this->ClsComFnc->FncNz(rtrim($strRecArr[4] != 0))))) {

            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：社員番号が未入力です" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }

        }

        //件数
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[3])) != "") {
            if (!is_numeric(rtrim($strRecArr[3]))) {
                if ($lngRcCnt < $intArrayCnt || $strRecArr[1] !== "") {
                    $errMsg = $ErrRcCnt . "行目：件数が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[3]))) > 3) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：件数の桁数が不正です。（999以下）" . $strRecArr[3] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //手数料
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[4])) != "") {
            if (!is_numeric(rtrim($strRecArr[4]))) {
                if ($lngRcCnt < $intArrayCnt || $strRecArr[1] !== "") {
                    $errMsg = $ErrRcCnt . "行目：手数料が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[4]))) > 8) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：手数料の桁数が不正です。（99999999以下）" . $strRecArr[4] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //保険課
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[5])) != "") {
            if (!is_numeric(rtrim($strRecArr[5]))) {
                if ($lngRcCnt < $intArrayCnt || $strRecArr[1] !== "") {
                    $errMsg = $ErrRcCnt . "行目：保険課が数値ではありません。" . "\r\n";
                    $this->fncOutLog($errMsg);
                    $blnErr = TRUE;
                    $miss = $miss + 1;
                }
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[5]))) > 8) {
            if ($lngRcCnt < $intArrayCnt) {
                $errMsg = $ErrRcCnt . "行目：保険課の桁数が不正です。（99999999以下）" . $strRecArr[5] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
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

            $this->FrmJibaiExcelIn = new FrmJibaiExcelIn();

            $result = $this->FrmJibaiExcelIn->frmSampleLoadDate();

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
                'B',
                'C',
                'AN',
                'AO',
                'AP',
                'AQ'
            );

            for ($row = 5; $row <= $highestRow; $row++) {
                $col = array();
                $rowNothing = TRUE;
                foreach ($arr as $value) {
                    if ($value == 'AN' || $value == 'AO' || $value == 'AP' || $value == 'AQ') {
                        //20150915  yinhuaiyu  mod s
                        $val = $worksheet->getCell($value . $row)->getCalculatedValue();
                        //20150915  yinhuaiyu  mod e
                    } else {
                        $val = $worksheet->getCell($value . $row)->getCalculatedValue();
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