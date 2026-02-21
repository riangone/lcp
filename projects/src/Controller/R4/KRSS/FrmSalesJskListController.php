<?php
/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmSalesJskList;
use App\Model\R4\Component\ClsKeiriDataMake;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as PhpSpreadsheetXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Cake\Log\Log;

//*******************************************
// * sample controller
//*******************************************
class FrmSalesJskListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $USERID = "";
    public $FORMAT_NUMBER_COMMA_SEPARATED1 = '#,##0';
    public $ttt;
    public $FrmSalesJskList;
    public $ClsKeiriDataMake;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmSalesJskList_layout');

    }

    public function frmKanrSyukeiLoad()
    {
        $result = array('result' => 'false', 'data' => 'ErrorInfo', 'row' => '', );
        try {

            $this->FrmSalesJskList = new FrmSalesJskList();
            $result = $this->FrmSalesJskList->frmKanrSyukei_Load();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：印刷ボタン押下
    //関 数 名：cmdAct_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：売上台数　限界利益　実績表を作成する
    //**********************************************************************
    public function cmdActionClick()
    {
        $result = array('result' => 'false', 'data' => 'ErrorInfo', 'row' => '', );
        try {
            $postArr = $_POST['data'];
            $dtlSyoribi = $postArr["cboYM"];
            $this->FrmSalesJskList = new FrmSalesJskList();
            $this->ClsKeiriDataMake = new ClsKeiriDataMake();
            $result1 = $this->ClsKeiriDataMake->fncSLSJSKDelete(str_replace("/", "", $dtlSyoribi));
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $result2 = $this->ClsKeiriDataMake->fncSLSJSKInsert(str_replace("/", "", $dtlSyoribi));
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $result3 = $this->FrmSalesJskList->cmdAction_Click();

            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }

            $strUpdPro = "SalesJskList";
            $y = substr($dtlSyoribi, 0, 4);
            $m = substr($dtlSyoribi, 5, 2);
            $y = $y - 1;
            $m = $m - 1;
            if ($m == 0) {
                $y = $y - 1;
                $m = 12;

            } else {
                $m = ($m < 10) ? "0" . $m : $m;
            }
            $ym = $y . $m;
            $strDepend1 = $ym;
            //echo $strDepend1;
            $y = substr($dtlSyoribi, 0, 4);
            $m = substr($dtlSyoribi, 5, 2);
            $ym = $y . $m;
            $strDepend2 = $ym;
            //echo $strDepend2;
            $result4 = $this->ClsKeiriDataMake->fncSGENJInsert($strDepend1, $strDepend2, $strUpdPro);

            if (!$result['result']) {
                throw new \Exception($result4['data']);
            }

            $result = $this->FrmSalesJskList->fncPrintSelect($dtlSyoribi);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $resultdata = $result["data"];
            $res = $this->fncExportExcel($resultdata);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

            $result['result'] = TRUE;
            $result['data'] = $res['data'];

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        // if ($result['result']) {
        //
        // }
        $this->fncReturn($result);
    }

    public function ymd($resultdata, $ii)
    {
        $strymd = $resultdata[0]['TOUGETU'];
        $y = substr($strymd, 0, 4);
        $m = substr($strymd, 5, 2);
        $m = $m - $ii;
        if ($m < 0) {
            $y = $y - 1;
            $m = 12 + $m;
            $m = ($m < 10) ? "0" . $m : $m;
        } elseif ($m == 0) {
            $y = $y - 1;
            $m = 12;

        } else {
            $m = ($m < 10) ? "0" . $m : $m;
        }

        $ym = $y . "/" . $m;
        return $ym;
    }

    public function fncExportExcel($resultdata)
    {
        //print_r($resultdata);

        try {
            $result = array("result" => FALSE, "data" => "error");
            $FrmSalesJskList = new FrmSalesJskList();
            $this->USERID = $FrmSalesJskList->GS_LOGINUSER['strUserID'];
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/KRSS/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;

            //set outputfile name
            $file = $tmpPath . "売上台数　限界利益　実績表_" . $this->USERID . ".xlsx";

            //path is exist
            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Execl Error";
                    throw new \Exception($result["data"]);
                }
            }

            //エクセルのテンプレートが保存されている場所を取得
            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmSalesJskListTemplate.xlsx";
            //テンプレートファイルの存在確認
            if (file_exists($strTemplatePath) == FALSE) {
                $result["data"] = "EXCELテンプレートが見つかりません！";
                throw new \Exception($result["data"]);
            }
            $PHPExcel = new Spreadsheet();
            $PHPReader = new Xlsx();
            $PHPExcel = $PHPReader->load($strTemplatePath);
            $i = 9;

            $start = $i;
            $loc = $i;

            $ABC = array();
            $ABC1 = array();
            $ABC2 = array();
            $ABC3 = array();
            for ($i = 'A'; $i != 'AG'; $i++) {
                array_push($ABC, $i);
            }
            //array_push($ABC, $i);
            //print_r($ABC);
            $count = count($ABC);
            $coul = $ABC[$count - 1];
            foreach ($ABC as $value) {

                $firCell = $PHPExcel->getActiveSheet()->getCell($value . $start)->getValue();
                if ($firCell !== null) {
                    $replace = str_replace("{", "", $firCell);
                    $firCell = str_replace("}", "", $replace);

                    switch ($firCell) {
                        case 'BUSYO_NM':
                            $ABC1["BUSYO_NM"] = $value;
                            break;
                        case 'MANAGER':
                            $ABC1["MANAGER"] = $value;
                            break;
                    }
                }
                $secCell = $PHPExcel->getActiveSheet()->getCell($value . ($start + 1))->getValue();
                if ($secCell !== null) {
                    $replace = str_replace("{", "", $secCell);
                    $secCell = str_replace("}", "", $replace);

                    switch ($secCell) {
                        case 'KBN':
                            $ABC2["KBN"] = $value;
                            break;
                        case 'NAME':
                            $ABC2["NAME"] = $value;
                            break;
                        case 'MEISYOU':
                            $ABC2["MEISYOU"] = $value;
                            break;
                        case 'NDAI12':
                            $ABC2["NDAI12"] = $value;
                            break;
                        case 'SDAI12':
                            $ABC2["SDAI12"] = $value;
                            break;
                        case 'NDAI11':
                            $ABC2["NDAI11"] = $value;
                            break;
                        case 'SDAI11':
                            $ABC2["SDAI11"] = $value;
                            break;
                        case 'NDAI10':
                            $ABC2["NDAI10"] = $value;
                            break;
                        case 'SDAI10':
                            $ABC2["SDAI10"] = $value;
                            break;
                        case 'NDAI9':
                            $ABC2["NDAI9"] = $value;
                            break;
                        case 'SDAI9':
                            $ABC2["SDAI9"] = $value;
                            break;
                        case 'NDAI8':
                            $ABC2["NDAI8"] = $value;
                            break;
                        case 'SDAI8':
                            $ABC2["SDAI8"] = $value;
                            break;
                        case 'NDAI7':
                            $ABC2["NDAI7"] = $value;
                            break;
                        case 'SDAI7':
                            $ABC2["SDAI7"] = $value;
                            break;
                        case 'NDAI6':
                            $ABC2["NDAI6"] = $value;
                            break;
                        case 'SDAI6':
                            $ABC2["SDAI6"] = $value;
                            break;
                        case 'NDAI5':
                            $ABC2["NDAI5"] = $value;
                            break;
                        case 'SDAI5':
                            $ABC2["SDAI5"] = $value;
                            break;
                        case 'NDAI4':
                            $ABC2["NDAI4"] = $value;
                            break;
                        case 'SDAI4':
                            $ABC2["SDAI4"] = $value;
                            break;
                        case 'NDAI3':
                            $ABC2["NDAI3"] = $value;
                            break;
                        case 'SDAI3':
                            $ABC2["SDAI3"] = $value;
                            break;
                        case 'NDAI2':
                            $ABC2["NDAI2"] = $value;
                            break;
                        case 'SDAI2':
                            $ABC2["SDAI2"] = $value;
                            break;
                        case 'NDAI1':
                            $ABC2["NDAI1"] = $value;
                            break;
                        case 'SDAI1':
                            $ABC2["SDAI1"] = $value;
                            break;
                        case 'NDAI13':
                            $ABC2["NDAI13"] = $value;
                            break;
                        case 'SDAI13':
                            $ABC2["SDAI13"] = $value;
                            break;
                        case 'SDAI1+SDAI12':
                            $ABC2["SDAI1+SDAI12"] = $value;
                            break;
                        case 'NDAI1+NDAI12':
                            $ABC2["NDAI1+NDAI12"] = $value;
                            break;
                    }
                }
                $thrCell = $PHPExcel->getActiveSheet()->getCell($value . ($start + 2))->getValue();
                if ($thrCell !== null) {
                    $replace = str_replace("{", "", $thrCell);
                    $thrCell = str_replace("}", "", $replace);

                    switch ($thrCell) {
                        case 'CD':
                            $ABC3["CD"] = $value;
                            break;
                        case '（保有台数=HOYU定時間月収=TEISYU)':
                            $ABC3["HOYU"] = $value;
                            break;
                        case 'GK12':
                            $ABC3["GK12"] = $value;
                            break;
                        case 'GK11':
                            $ABC3["GK11"] = $value;
                            break;
                        case 'GK10':
                            $ABC3["GK10"] = $value;
                            break;
                        case 'GK9':
                            $ABC3["GK9"] = $value;
                            break;
                        case 'GK8':
                            $ABC3["GK8"] = $value;
                            break;
                        case 'GK7':
                            $ABC3["GK7"] = $value;
                            break;
                        case 'GK6':
                            $ABC3["GK6"] = $value;
                            break;
                        case 'GK5':
                            $ABC3["GK5"] = $value;
                            break;
                        case 'GK4':
                            $ABC3["GK4"] = $value;
                            break;
                        case 'GK3':
                            $ABC3["GK3"] = $value;
                            break;
                        case 'GK2':
                            $ABC3["GK2"] = $value;
                            break;
                        case 'GK1':
                            $ABC3["GK1"] = $value;
                            break;
                        case 'GK13':
                            $ABC3["GK13"] = $value;
                            break;
                        case 'GK1+GK12':
                            $ABC3["GK1+GK12"] = $value;
                            break;
                    }
                }
            }
            // print_r($ABC1);
            // print_r($ABC2);
            // print_r($ABC3);
            //print_r($resultdata);
            Log::error(json_encode($ABC));
            if (count($resultdata) > 0) {
                for ($ii = 0; $ii < 13; $ii++) {

                    $yymm = $this->ymd($resultdata, $ii);
                    // $this->log($ABC3['GK' . ($ii + 1)] . ($loc - 1));
                    Log::error($ABC3['GK' . ($ii + 1)] . ($loc - 1));
                    $PHPExcel->getActiveSheet()->setCellValue($ABC3['GK' . ($ii + 1)] . ($loc - 1), $yymm);

                }
                $yymm = $this->ymd($resultdata, 0);
            }
            $name = "";
            foreach ($resultdata as $value) {
                $NDAI1_NDAI12 = $value["NDAI1"] + $value["NDAI2"] + $value["NDAI3"] + $value["NDAI4"] + $value["NDAI5"] + $value["NDAI6"] + $value["NDAI7"] + $value["NDAI8"] + $value["NDAI9"] + $value["NDAI10"] + $value["NDAI11"] + $value["NDAI12"];
                $SDAI1_SDAI12 = $value["SDAI1"] + $value["SDAI2"] + $value["SDAI3"] + $value["SDAI4"] + $value["SDAI5"] + $value["SDAI6"] + $value["SDAI7"] + $value["SDAI8"] + $value["SDAI9"] + $value["SDAI10"] + $value["SDAI11"] + $value["SDAI12"];
                $GK1_GK13 = $value["GK1"] + $value["GK2"] + $value["GK3"] + $value["GK4"] + $value["GK5"] + $value["GK6"] + $value["GK7"] + $value["GK8"] + $value["GK9"] + $value["GK10"] + $value["GK11"] + $value["GK12"];
                foreach ($value as $keya => $valuea) {
                    //$name = $value["BUSYO_NM"];
                    if ($name == $value["BUSYO_NM"] && ($keya == "BUSYO_NM" || $keya == "MANAGER")) {

                        $loc = $loc - 1;
                    } else {
                        foreach ($ABC1 as $key1 => $value1) {
                            if ($keya == $key1) {

                                $PHPExcel->getActiveSheet()->setCellValue($value1 . $loc, $valuea);
                                $styleArray = array('borders' => array('top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, ), ), );
                                if ($loc < 10) {
                                    $PHPExcel->getActiveSheet()->getStyle('B' . $loc . ':' . $coul . $loc)->applyFromArray($styleArray);
                                } else {
                                    $PHPExcel->getActiveSheet()->getStyle('B' . ($loc - 1) . ':' . $coul . ($loc - 1))->applyFromArray($styleArray);
                                }

                            }
                        }

                    }

                    foreach ($ABC2 as $key2 => $value2) {
                        if ($keya == $key2) {
                            //echo "string";
                            if ($key2 == "KBN" || $key2 == "NAME" || $key2 == "MEISYOU") {
                                $PHPExcel->getActiveSheet()->setCellValue($value2 . ($loc + 1), $valuea);
                            } else {
                                $PHPExcel->getActiveSheet()->getStyle($value2 . ($loc + 1))->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
                                $PHPExcel->getActiveSheet()->setCellValue($value2 . ($loc + 1), $valuea);
                            }
                        }
                    }
                    foreach ($ABC3 as $key3 => $value3) {
                        if ($keya == $key3) {
                            if ($key3 == "CD") {
                                $PHPExcel->getActiveSheet()->setCellValue($value3 . ($loc + 2), $valuea);
                            } else {
                                $PHPExcel->getActiveSheet()->getStyle($value3 . ($loc + 2))->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);

                                $valuea = round($valuea / 1000);

                                $PHPExcel->getActiveSheet()->setCellValue($value3 . ($loc + 2), $valuea);
                            }

                            if ($keya == "HOYU") {

                                $repvalue = str_replace("HOYU", $value["HOYU"] . "    ", "（保有台数=HOYU定時間月収=TEISYU)");
                                $repvalue = str_replace("TEISYU", $value["TEISYU"] . "    ", $repvalue);
                                $PHPExcel->getActiveSheet()->setCellValue($value3 . ($loc + 2), $repvalue);
                            }
                        }
                    }

                }
                $PHPExcel->getActiveSheet()->getStyle($ABC2["NDAI1+NDAI12"] . ($loc + 1))->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["NDAI1+NDAI12"] . ($loc + 1), $NDAI1_NDAI12);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["SDAI1+SDAI12"] . ($loc + 1))->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["SDAI1+SDAI12"] . ($loc + 1), $SDAI1_SDAI12);
                $PHPExcel->getActiveSheet()->getStyle($ABC3["GK1+GK12"] . ($loc + 2))->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
                $GK1_GK13 = round($GK1_GK13 / 1000);
                $PHPExcel->getActiveSheet()->setCellValue($ABC3["GK1+GK12"] . ($loc + 2), $GK1_GK13);
                $name = $value["BUSYO_NM"];
                $loc = $loc + 4;
            }
            $NENTUKI = $PHPExcel->getActiveSheet()->getCell('B2')->getValue();
            $NENTUKI1 = count($resultdata) > 0 ? array_shift($resultdata[0]) : NULL;

            $NEN = substr($NENTUKI1, 0, 4);
            $TUKI = substr($NENTUKI1, 4, 2);
            $today = $PHPExcel->getActiveSheet()->getCell('Z2')->getValue();
            if (count($resultdata) > 0) {
                $NENTUKI = str_replace("{NEN}", $NEN, $NENTUKI);
                $NENTUKI = str_replace("{TUKI}", $TUKI, $NENTUKI);
                $today = str_replace("{Today}", $resultdata[0]['TODAY'], $today);
            }
            $PHPExcel->getActiveSheet()->setCellValue('B2', $NENTUKI);
            $PHPExcel->getActiveSheet()->setCellValue('Z2', $today);

            $objWriter = new PhpSpreadsheetXlsx($PHPExcel);
            $objWriter->save($file);
            $result['data'] = "files/KRSS/売上台数　限界利益　実績表_" . $this->USERID . ".xlsx";
            $result["result"] = TRUE;
        } catch (\Exception $ex) {
            $result["result"] = FALSE;
            $result["data"] = $ex->getMessage();
        }
        return $result;
    }

}
