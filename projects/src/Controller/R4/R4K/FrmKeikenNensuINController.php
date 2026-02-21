<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmKeikenNensuIN;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmKeikenNensuINController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmKeikenNensuIN = '';
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

        $this->render('index', 'FrmKeikenNensuIN_layout');
    }

    public function changeFileName($param)
    {
        $strUserID = $this->FrmKeikenNensuIN->GS_LOGINUSER['strUserID'];
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
        $strLogPath = $strErrLogPath . "経験年数データ.log";
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
                $this->FrmKeikenNensuIN = new FrmKeikenNensuIN();

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
        // $blnTrn = FALSE;
        $blnErr = FALSE;
        $postData = "";
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {

            $postData = $_POST['data']['request'];

            $date = date("Y/m/d H:i:s");

            $this->fncOutLog("取込開始:" . $date . "\r\n", FALSE);

            $this->FrmKeikenNensuIN = new FrmKeikenNensuIN();

            $res = $this->FrmKeikenNensuIN->Do_conn();

            if (!$res['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmKeikenNensuIN->Do_transaction();

            // $blnTrn = TRUE;

            $res = $this->FrmKeikenNensuIN->fncTableDelete($postData);
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

            $this->FrmKeikenNensuIN->Do_commit();
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
            $this->FrmKeikenNensuIN->Do_rollback();
            $this->FrmKeikenNensuIN->Do_close();
        }

        $this->fncReturn($result);
    }

    public function fncFileRead($postData = NULL)
    {
        $blnErr = FALSE;

        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
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
                foreach ($strGetArray as $key => $value) {
                    if (!$blnErr) {

                        $blnArr = $this->fncCheckRecord($value, $key + 1, $blnErr);
                        $blnErr = $blnArr['result'];

                        if ($blnErr) {
                            $result['MsgID'] = 'W9999';
                            throw new \Exception($blnArr['errMsg']);
                        }

                        $res = $this->FrmKeikenNensuIN->ExcuteFncGetSqlInsert($value, $postData);

                        if (!$res['result']) {
                            $result['MsgID'] = 'E9999';
                            $this->fncOutLog($res['data']);
                            throw new \Exception($res['data']);
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

    public function fncCheckRecord($strRecArr, $lngRcCnt, $blnErr)
    {
        $miss = 0;
        $errMsg = '';
        $ErrRcCnt = $lngRcCnt + 1;
        //社員番号
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[0]))) > 5) {
            $errMsg = $ErrRcCnt . "行目：社員番号の桁数が不正です。（5ﾊﾞｲﾄ以下）" . $strRecArr[0] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[0])) == "") {
            $errMsg = $ErrRcCnt . "行目：社員番号が未入力です" . "\r\n";
            ;
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }
        //社員名
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[1]))) > 30) {
            $errMsg = $ErrRcCnt . "行目：社員名桁数が不正です。（30ﾊﾞｲﾄ以下）" . $strRecArr[1] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }
        //部署ｺｰﾄﾞ
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[2]))) > 3) {
            $errMsg = $ErrRcCnt . "行目：部署コードの桁数が不正です。（3ﾊﾞｲﾄ以下）" . $strRecArr[2] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }
        //営業開始年月
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[3])) != "") {
            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[3]))) != 6) {
                $errMsg = $ErrRcCnt . "行目：営業開始年月の桁数が不正です。（6ﾊﾞｲﾄ）" . $strRecArr[3] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //営業開始年度
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[4])) != "") {
            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[4]))) != 4) {
                $errMsg = $ErrRcCnt . "行目：営業開始年度の桁数が不正です。（4ﾊﾞｲﾄ）" . $strRecArr[4] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //経験年
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[8])) != "") {

            if (!is_numeric(rtrim($strRecArr[8]))) {
                $errMsg = $ErrRcCnt . "行目：経験年が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            } else if ($this->ClsComFnc->GetByteCount(abs($this->ClsComFnc->FncNv(rtrim($strRecArr[8])))) > 2) {
                $errMsg = $ErrRcCnt . "行目：経験年の桁数が不正です。（99以下）" . $strRecArr[8] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        //経験月
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[9])) != "") {
            if (!is_numeric(rtrim($strRecArr[9]))) {
                $errMsg = $ErrRcCnt . "行目：経験月が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            } else if ($this->ClsComFnc->GetByteCount(abs($this->ClsComFnc->FncNv(rtrim($strRecArr[9])))) > 2) {
                $errMsg = $ErrRcCnt . "行目：経験月の桁数が不正です。（99以下）" . $strRecArr[9] . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }

        //経験年数
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[10])) != "") {
            if (!is_numeric(rtrim($strRecArr[10]))) {
                $errMsg = $ErrRcCnt . "行目：経験年数が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            } else if ($this->ClsComFnc->GetByteCount(abs($this->ClsComFnc->FncNv(rtrim($strRecArr[10])))) > 2) {
                $errMsg = $ErrRcCnt . "行目：経験年数の桁数が不正です。（99以下）" . $strRecArr[10] . "\r\n";
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

            $this->FrmKeikenNensuIN = new FrmKeikenNensuIN();

            $result = $this->FrmKeikenNensuIN->frmSampleLoadDate();

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
                'K'
            );

            for ($row = 2; $row <= $highestRow; $row++) {
                $col = array();
                $rowNothing = TRUE;
                foreach ($arr as $value) {
                    $val = $worksheet->getCell($value . $row)->getCalculatedValue();
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