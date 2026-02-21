<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDKansaItemTeigi;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Shared\Date;

//*******************************************
// * sample controller
//*******************************************
class HMAUDKansaItemTeigiController extends AppController
{
    private $Session;
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $HMAUDKansaItemTeigi;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }


    public function index()
    {
        $this->render('index', 'HMAUDKansaItemTeigi_layout');
    }
    public function pageload()
    {
        $this->HMAUDKansaItemTeigi = new HMAUDKansaItemTeigi();
        $res = array(
            'result' => FALSE,
            'data' => array(
                'admin' => '',
                'cour' => '',
            ),
            'error' => ''
        );
        try {
            $admindata = $this->HMAUDKansaItemTeigi->getadminSql();
            if (!$admindata['result']) {
                throw new \Exception($admindata['data']);
            }
            // print_r($admindata);

            if (count((array) $admindata['data']) > 0) {
                $res['data']['admin'] = $admindata['data'][0]['COUNT'];
            } else {
                $res['data']['admin'] = '0';
            }
            //検索条件・クールには 現在のクール数を初期表示
            $cour = $this->HMAUDKansaItemTeigi->getInitializeCour();
            if (!$cour['result']) {
                throw new \Exception($cour['data']);
            }

            $res['data']['cour'] = $cour['data'];
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

    //ファイルのアップロード
    public function fncCheckFile()
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );

        try {
            $strPath = dirname(dirname(dirname(__FILE__)));
            $pathUpLoad = $strPath . "/" . $this->ClsComFncHMAUD->FncGetPath('HmaudUpLoad');
            if (!file_exists($pathUpLoad)) {
                //フォルダ権限の判断
                $outFloder = dirname($pathUpLoad);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($pathUpLoad, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($pathUpLoad, 0777);
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

    //ファイルのアップロード
    public function changeFileName($param)
    {
        $this->HMAUDKansaItemTeigi = new HMAUDKansaItemTeigi();
        $strUserID = $this->HMAUDKansaItemTeigi->GS_LOGINUSER['strUserID'];
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

    public function btnActionClick()
    {
        $blnTranFlg = FALSE;
        //トランザクションflg
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        $this->HMAUDKansaItemTeigi = new HMAUDKansaItemTeigi();
        try {

            $txtPath = $_POST['data']["txtPath"];
            $strPath = dirname(dirname(dirname(__FILE__)));
            $pathUpLoad = $strPath . "/" . $this->ClsComFncHMAUD->FncGetPath('HmaudUpLoad');

            $this->Session = $this->request->getSession();
            $txtPath = $this->Session->read('login_user') . "_" . $txtPath;

            //トランザクション開始
            $this->HMAUDKansaItemTeigi->Do_transaction();
            $blnTranFlg = TRUE;
            //Excel取込処理
            $result = $this->ExcelTorikomi($pathUpLoad . $txtPath, $_POST['data']);

            if ($result['result'] == FALSE) {
                throw new \Exception($result['error']);
            }
            //トランザクション終了
            $this->HMAUDKansaItemTeigi->Do_commit();
            $blnTranFlg = FALSE;

        } catch (\Exception $e) {
            if ($blnTranFlg == TRUE) {
                $this->HMAUDKansaItemTeigi->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }

        //ファイル削除
        $UpLoadfilepath = $pathUpLoad . $txtPath;
        if (isset($UpLoadfilepath) && file_exists($UpLoadfilepath)) {
            @unlink($UpLoadfilepath);
        }
        $this->fncReturn($result);
    }

    private function ExcelTorikomi($filePath, $param)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {

            //監査項目マスタ削除
            $result_del = $this->HMAUDKansaItemTeigi->fncDeleteDetailExist($param);
            if ($result_del['result'] == FALSE) {
                throw new \Exception($result_del['data']);
            }
            //監査実績削除
            $result_delresult = $this->HMAUDKansaItemTeigi->fncDeleteTableExist($param, 'HMAUD_AUDIT_RESULT');
            if ($result_delresult['result'] == FALSE) {
                throw new \Exception($result_delresult['data']);
            }
            //報告書ヘッダ削除
            $result_delhead = $this->HMAUDKansaItemTeigi->fncDeleteHeadTableExist($param);
            if ($result_delhead['result'] == FALSE) {
                throw new \Exception($result_delhead['data']);
            }
            //報告書明細削除
            $result_delreport = $this->HMAUDKansaItemTeigi->fncDeleteTableExist($param, 'HMAUD_AUDIT_REPORT_DETAIL');
            if ($result_delreport['result'] == FALSE) {
                throw new \Exception($result_delreport['data']);
            }

            //ID最大値取得
            $result_sel = $this->HMAUDKansaItemTeigi->fncSelectMaxchecklstid();
            if ($result_sel['result'] == FALSE) {
                throw new \Exception($result_sel['data']);
            }
            $maxid = $result_sel["data"][0]["CHECK_LST_ID"];

            $file_info = pathinfo($filePath);
            $extension = $file_info['extension'];
            if ($extension == 'xlsx') {
                $objReader = IOFactory::createReader('Xlsx');
            } else {
                $objReader = IOFactory::createReader('Xls');
            }


            $objPHPExcel = $objReader->load($filePath);
            //シートワークシートの総数を取得
            // if ($sheetCount > 1)
            // {
            // //2番目を読む
            // $objPHPExcel -> setActiveSheetIndex(1);
            // }
            // else
            // {
            $objPHPExcel->setActiveSheetIndex(0);
            // }
            $objActSheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objActSheet->getHighestRow();
            //取得总行数

            //データ読み込み、配列へ格納

            $referenceRow = array();
            //行
            for ($i = 2; $i <= $highestRow; $i++) {
                $aryVal = array();
                //列（明細ＮＯ以外）
                $blnExist = False;
                for ($j = 0; $j <= 10; $j++) {
                    if (!$objPHPExcel->getActiveSheet()->getCell($this->numberToExcelColumn($j + 1) . $i)->isInMergeRange() || $objPHPExcel->getActiveSheet()->getCell($this->numberToExcelColumn($j + 1) . $i)->isMergeRangeValueCell()) {
                        $pos = $objPHPExcel->getActiveSheet()->getCell($this->numberToExcelColumn($j + 1) . $i)->getCalculatedValue();
                        $referenceRow[$j] = $objPHPExcel->getActiveSheet()->getCell($this->numberToExcelColumn($j + 1) . $i)->getCalculatedValue();
                    } else {
                        $pos = $referenceRow[$j];
                    }
                    if ($j == 10) {
                        $datatype = $objPHPExcel->getActiveSheet()->getStyle($this->numberToExcelColumn($j + 1) . $i)->getNumberFormat()->getFormatCode();
                        $excelType = $objPHPExcel->getActiveSheet()->getCell($this->numberToExcelColumn($j + 1) . $i)->getDataType();
                        $pos = $pos != '' ? trim($pos) : '';
                        if ($pos != '' && $excelType == 's') {
                            $date_pattern = "/^[0-9]{4}(-|\/|.)[0-9]{1,2}(-|\/|.)[0-9]{1,2}$/";
                            $date_pattern1 = "/^[0-9]{8}$/";

                            if (!preg_match($date_pattern, $pos)) {
                                if (!preg_match($date_pattern1, $pos)) {
                                    throw new \Exception('日付フォーマットエラー。');
                                }
                            }
                            $EXPIRATION_DATE = strtotime($pos); // 将指定日期转成时间戳
                            if ($EXPIRATION_DATE == '') {
                                throw new \Exception('日付フォーマットエラー。');
                            }
                        } else if ($pos != '') {
                            $EXPIRATION_DATE = strtotime($pos);
                            if ($EXPIRATION_DATE == '') {
                                if ($datatype == 'yyyy/mm/dd' || $datatype == 'yyyy\-mm\-dd' || $datatype == 'mm-dd-yy') {
                                    // $EXPIRATION_DATE = date('Y-m-d', (int) PHPExcel_Shared_Date::ExcelToPHP($pos));
                                    $EXPIRATION_DATE = gmdate("Y-m-d", Date::excelToTimestamp((int) $pos));
                                    $EXPIRATION_DATE = strtotime($EXPIRATION_DATE);
                                } else {
                                    throw new \Exception('日付フォーマットエラー。');
                                }
                            }
                        } else {
                            $EXPIRATION_DATE = '';
                        }
                        if ($EXPIRATION_DATE != '' && $EXPIRATION_DATE != null) {
                            $EXPIRATION_DATE = date("Y-m-d", $EXPIRATION_DATE);
                        }
                        $pos = $EXPIRATION_DATE;
                    }
                    $aryVal[$j] = $pos;
                    $blnExist = True;

                }
                if ($blnExist) {
                    $maxid += 1;
                    //追加
                    $result_ins = $this->HMAUDKansaItemTeigi->InsertData($param, $aryVal, $maxid);
                    if ($result_ins["result"] == FALSE) {
                        throw new \Exception('追加処理中にエラーが発生しました');
                    }
                }

            }
            $objPHPExcel->disconnectWorksheets();
            unset($objReader, $objPHPExcel);
            $result["result"] = TRUE;
            $result['data'] = "I0007";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function numberToExcelColumn($num)
    {
        $num -= 1;
        $excelColumn = '';
        while ($num >= 0) {
            $mod = $num % 26;
            $excelColumn = chr(65 + $mod) . $excelColumn;
            $num = intval($num / 26) - 1;
        }
        return $excelColumn;
    }

}
