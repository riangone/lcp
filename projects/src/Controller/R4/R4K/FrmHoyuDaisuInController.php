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
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL 　　　　
 * 20151019                 #2185 #2186              BUG                         Yuanjh
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHoyuDaisuIn;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmHoyuDaisuInController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmHoyuDaisuIn;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmHoyuDaisuIn_layout');
    }

    public function frmSampleLoad()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmHoyuDaisuIn = new FrmHoyuDaisuIn();

            $result = $this->FrmHoyuDaisuIn->frmSampleLoadDate();

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
        $strUserID = $this->FrmHoyuDaisuIn->GS_LOGINUSER['strUserID'];
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
        $strLogPath = $strErrLogPath . "保有台数表取込.log";
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
                $this->FrmHoyuDaisuIn = new FrmHoyuDaisuIn();

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

        // echo json_encode($result);
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

            $this->FrmHoyuDaisuIn = new FrmHoyuDaisuIn();

            $res = $this->FrmHoyuDaisuIn->Do_conn();

            if (!$res['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmHoyuDaisuIn->Do_transaction();

            //振替ﾃﾞｰﾀを初期化
            $res = $this->FrmHoyuDaisuIn->fncFurikaeDelete($postData);

            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                $this->fncOutLog($res['data']);
                throw new \Exception($res['data']);
            }

            $res = "";
            //残高ﾃﾞｰﾀを初期化
            $res = $this->FrmHoyuDaisuIn->fncZandakaDelete($postData);

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

            $this->FrmHoyuDaisuIn->Do_commit();
            $date = date("Y/m/d H:i:s");
            $this->fncOutLog("正常終了:" . $date);
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;

            $result['data'] = $e->getMessage();

        }

        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmHoyuDaisuIn->Do_rollback();
            $this->FrmHoyuDaisuIn->Do_close();
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
                $intArrayCnt = count((array) $strGetArray);

                if ($intArrayCnt == 1 && $strGetArray[0][0] == "0") {

                    $result['MsgID'] = 'I0001';
                    $this->fncOutLog("データが存在しません。");
                    throw new \Exception("データが存在しません。");
                }

                $ym = str_replace("/", "", $postData['KEIJOBI']);

                $y = substr($ym, 0, 4);
                $m = substr($ym, 4, 2);
                $d = date("t", strtotime($y . '-' . $m));
                $ymd = $y . $m . $d;

                if ($ymd != str_replace("/", "", $this->ClsComFnc->FncNv($strGetArray[0][0]))) {
                    $result['MsgID'] = 'W9999';
                    $this->fncOutLog("4行目：年月が不正です。");
                    throw new \Exception("4行目：年月が不正です。");
                }

                $res1 = $this->FrmHoyuDaisuIn->fncUpdHksaiban(1);
                if (!$res1['result']) {
                    $this->fncOutLog($res1['data']);
                    throw new \Exception($res1['data']);
                }

                $lngSeqNO = 1;
                $res2 = $this->FrmHoyuDaisuIn->fncSelHksaiban();
                if (!$res2['result']) {
                    throw new \Exception($res2['data']);
                }

                if ($res2['row'] > 0) {
                    //20151019   Yuanjh  UPD S.
                    //$lngSeqNO = $res2['data']['SEQNO'];
                    $lngSeqNO = $res2['data'][0]['SEQNO'];
                    //20151019   Yuanjh  UPD E.
                }
                $intGyoNO = 1;

                foreach ($strGetArray as $key => $value) {

                    if ($key == $intArrayCnt - 1) {
                        break;
                    }

                    if ($key >= 2) {
                        if (!$blnErr) {

                            if ($this->ClsComFnc->FncNv(rtrim($value[5])) == "") {
                                $blnErr = FALSE;
                            } else {

                                $blnArr = $this->fncCheckRecord($value, $key, $intArrayCnt - 1, $blnErr, $postData);

                                $blnErr = $blnArr['result'];

                                if ($blnErr) {
                                    $result['MsgID'] = 'W9999';
                                    throw new \Exception($blnArr['errMsg']);
                                }
                                $res = $this->FrmHoyuDaisuIn->fncGetZandakaInsert($value, $postData);
                                if (!$res['result']) {
                                    $result['MsgID'] = 'E9999';
                                    $this->fncOutLog($res['data']);
                                    throw new \Exception($res['data']);
                                }

                                $res = $this->FrmHoyuDaisuIn->fncGetFurikaeInsert($value, $lngSeqNO, $intGyoNO, $postData);
                                if (!$res['result']) {
                                    $result['MsgID'] = 'E9999';
                                    $this->fncOutLog($res['data']);
                                    throw new \Exception($res['data']);
                                }

                                $intGyoNO = $intGyoNO + 1;
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

    public function fncCheckRecord($strRecArr, $lngRcCnt, $intArrayCnt, $blnErr, $postData)
    {

        $miss = 0;
        $errMsg = '';
        $ErrRcCnt = $lngRcCnt + 4;

        //科目コード存在ﾁｪｯｸ
        if (!($this->ClsComFnc->FncNv($strRecArr[5]) == "00901" || $this->ClsComFnc->FncNv($strRecArr[5]) == "00902")) {

            $errMsg = $ErrRcCnt . "行目：科目コードが不正です。" . $this->ClsComFnc->FncNv($strRecArr[5]) . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;

        }
        //部署コード
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[1]))) > 3) {
            $errMsg = $ErrRcCnt . "行目：部署コードの桁数が不正です。（3ﾊﾞｲﾄ以下）" . $strRecArr[1] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[1])) == "") {

            $errMsg = $ErrRcCnt . "行目：部署コードが未入力です" . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }
        //部署コード存在ﾁｪｯｸ
        $resMst = $this->ClsComFnc->FncGetBusyoMstValue($this->ClsComFnc->FncNv(rtrim($strRecArr[1])), $this->ClsComFnc->GS_BUSYOMST);

        if (!$resMst['result']) {
            $errMsg = $ErrRcCnt . "行目：部署コードの存在チェックに失敗しました。" . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }

        //前月末台数
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[2])) == "") {

            $errMsg = $ErrRcCnt . "行目：前月末台数が未入力です。" . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }

        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[2])) != "") {
            if (!is_numeric(rtrim($strRecArr[2]))) {

                $errMsg = $ErrRcCnt . "行目：前月末台数が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;

            }
        }

        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[2]))) > 13) {
            $errMsg = $ErrRcCnt . "行目：前月末台数の桁数が不正です。（9999999999999以下）" . $strRecArr[2] . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }

        //当月末台数
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[3])) == "") {

            $errMsg = $ErrRcCnt . "行目：当月末台数が未入力です。" . "\r\n";
            $this->fncOutLog($errMsg);
            $blnErr = TRUE;
            $miss = $miss + 1;
        }

        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[3])) != "") {
            if (!is_numeric(rtrim($strRecArr[3]))) {

                $errMsg = $ErrRcCnt . "行目：当月末台数が数値ではありません。" . "\r\n";
                $this->fncOutLog($errMsg);
                $blnErr = TRUE;
                $miss = $miss + 1;

            }
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[3]))) > 13) {
            $errMsg = $ErrRcCnt . "行目：当月末台数の桁数が不正です。（9999999999999以下）" . $strRecArr[3] . "\r\n";
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
                'G'
            );

            for ($row = 4; $row <= $highestRow; $row++) {
                $col = array();
                $rowNothing = TRUE;
                foreach ($arr as $value) {
                    if ($value == 'B' && $row == 4) {

                        $val = $worksheet->getCell($value . $row)->getValue();
                        $val = $this->ClsComFnc->FncNv($val);
                        if ($val == "") {
                            $val = "0";
                        } else {
                            $val = gmdate("Ymd", Date::excelToTimestamp((int) $val));
                        }

                    } else {
                        $val = $worksheet->getCell($value . $row)->getCalculatedValue();
                        $val = $this->ClsComFnc->FncNv($val);
                    }

                    if ($val != "") {
                        $rowNothing = FALSE;
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