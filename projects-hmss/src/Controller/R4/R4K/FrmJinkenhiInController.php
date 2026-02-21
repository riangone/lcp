<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmJinkenhiIn;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmJinkenhiInController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmJinkenhiIn = '';
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

        $this->render('index', 'FrmJinkenhiIn_layout');
    }

    public function changeFileName($param)
    {
        $strUserID = $this->FrmJinkenhiIn->GS_LOGINUSER['strUserID'];
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
        $strLogPath = $strErrLogPath . "人件費データ取込.log";
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
                $this->FrmJinkenhiIn = new FrmJinkenhiIn();

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
        $blnTrn = FALSE;
        $blnErr = FALSE;
        $postData = "";
        $result = array(
            'result' => 'false',
            'data' => ''
        );
        try {

            $postData = $_POST['data']['request'];

            $date = date("Y/m/d H:i:s");

            $this->fncOutLog("取込開始:" . $date . "\r\n", FALSE);

            $this->FrmJinkenhiIn = new FrmJinkenhiIn();

            $res = $this->FrmJinkenhiIn->Do_conn();

            if (!$res['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmJinkenhiIn->Do_transaction();

            $blnTrn = TRUE;

            $res = $this->FrmJinkenhiIn->fncTableDelete($postData);
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

            $this->FrmJinkenhiIn->Do_commit();
            $blnTrn = FALSE;
            //部署コードアンマッチリスト出力
            $resSel = $this->FrmJinkenhiIn->fncUnmachiListSel($postData);
            if (!$resSel['result']) {
                $result['MsgID'] = 'E9999';
                $this->fncOutLog($resSel['data']);
                throw new \Exception($resSel['data']);
            }
            $countRow = count((array) $resSel['data']);
            // echo $countRow;
            $result['data'] = $resSel['data'];
            if ($countRow > 0) {
                $path_rpxTopdf = dirname(__DIR__);
                $tmpPdfName = "rptJinkenInUnmachi";
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName . '.inc';
                $data = $resSel['data'];
                $tmp_data = array();
                $rpx_file_names = array();
                $rpx_file_names[$tmpPdfName] = $data_fields_rptJinkenInUnmachi;
                array_push($tmp_data, $data);
                $tmp = array();
                $datas = array();
                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "3";
                $datas[$tmpPdfName] = $tmp;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                if (file_exists($obj->REPORTS_TEMP_PATH)) {
                    if (!(is_readable($obj->REPORTS_TEMP_PATH) && is_writable($obj->REPORTS_TEMP_PATH) && is_executable($obj->REPORTS_TEMP_PATH))) {
                        $result['MsgID'] = 'E9999';
                        throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                    }

                } else {
                    $outFloder = dirname(WWW_ROOT . $obj->REPORTS_TEMP_PATH);
                    if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                        $result['MsgID'] = 'E9999';
                        throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                    }
                    if (!mkdir($obj->REPORTS_TEMP_PATH, 0777, TRUE)) {
                        $result['MsgID'] = 'E9999';
                        throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                    }
                }
                $pdfPath = $obj->to_pdf();
                $result['path'] = $pdfPath;
            }
            //'プレビュー表示

            $date = date("Y/m/d H:i:s");
            $this->fncOutLog("正常終了:" . $date);

            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;

            $result['data'] = $e->getMessage();

        }

        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmJinkenhiIn->Do_rollback();
            $this->FrmJinkenhiIn->Do_close();
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

                        $res = $this->FrmJinkenhiIn->ExcuteFncGetSqlInsert($value, $postData);

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
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        } else {
            if (!is_numeric(rtrim($strRecArr[0]))) {
                $errMsg = $ErrRcCnt . "行目：社員番号の入力値が不正です。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
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
        //給与計
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[3])) != "") {
            if (!is_numeric(rtrim($strRecArr[3]))) {
                $errMsg = $ErrRcCnt . "行目：給与計が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[3]))) > 7) {
            $errMsg = $ErrRcCnt . "行目：給与計の桁数が不正です。（9999999以下）" . $strRecArr[3] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }

        //社保計
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[4])) != "") {
            if (!is_numeric(rtrim($strRecArr[4]))) {
                $errMsg = $ErrRcCnt . "行目：社保計が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[4]))) > 7) {
            $errMsg = $ErrRcCnt . "行目：社保計の桁数が不正です。（9999999以下）" . $strRecArr[4] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }

        //賞与見積
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[5])) != "") {
            if (!is_numeric(rtrim($strRecArr[5]))) {
                $errMsg = $ErrRcCnt . "行目：賞与見積が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[5]))) > 7) {
            $errMsg = $ErrRcCnt . "行目：賞与見積の桁数が不正です。（9999999以下）" . $strRecArr[5] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }

        //人件費計
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[6])) != "") {
            if (!is_numeric(rtrim($strRecArr[6]))) {
                $errMsg = $ErrRcCnt . "行目：人件費計が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;
            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[6]))) > 7) {
            $errMsg = $ErrRcCnt . "行目：人件費計の桁数が不正です。（9999999以下）" . $strRecArr[6] . "\r\n";
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

            $this->FrmJinkenhiIn = new FrmJinkenhiIn();

            $result = $this->FrmJinkenhiIn->frmSampleLoadDate();

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
                'H'
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
