<?php
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmHiyouMeisai;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment as PHPExcel_Style_Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmHiyouMeisaiController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmHiyouMeisai;
    public $result;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsComFncKRSS');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmHiyouMeisai_layout');
    }

    public function fncHKEIRICTL()
    {
        $this->FrmHiyouMeisai = new FrmHiyouMeisai();
        try {
            $this->result = $this->FrmHiyouMeisai->fncHKEIRICTL();
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncAuthCheck()
    {
        $this->FrmHiyouMeisai = new FrmHiyouMeisai();
        try {
            $this->result = $this->FrmHiyouMeisai->fncAuthCheck();
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncGetBusyoMstValue()
    {
        $this->FrmHiyouMeisai = new FrmHiyouMeisai();
        try {
            $this->result = $this->FrmHiyouMeisai->FncGetBusyoMstValue();
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }

        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncGetKamokuMstValue()
    {
        $this->FrmHiyouMeisai = new FrmHiyouMeisai();
        try {
            $this->result = $this->FrmHiyouMeisai->FncGetKamokuMstValue();
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncHiyoumeisaiSel()
    {
        try {
            $data = $_POST['data'];
            $this->FrmHiyouMeisai = new FrmHiyouMeisai();
            $this->result = $this->FrmHiyouMeisai->fncHiyoumeisaiSel($data);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncHiyoumeisaiSelExcel()
    {
        try {
            $data = $_POST['data'];
            $this->FrmHiyouMeisai = new FrmHiyouMeisai();
            $this->result = $this->FrmHiyouMeisai->fncHiyoumeisaiSel($data);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }

            if ($this->result['row'] == 0) {
                throw new \Exception('nodata');
            }
            $res = $this->fncExportExcel($this->result['data']);

            if ($res['result']) {
                $this->result['data'] = $res['data'];
            } else {
                throw new \Exception($res['data']);
            }

            // $this -> set("result", $this -> result);
            // $this -> render("fncdatadeal");

        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncGetLoginUserBusyonCD()
    {
        try {
            $data = $_POST['data'];
            $this->FrmHiyouMeisai = new FrmHiyouMeisai();
            $this->result = $this->FrmHiyouMeisai->fncGetLoginUserBusyonCD($data);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncExportExcel($data)
    {
        try {
            $this->result = [
                'result' => FALSE,
                'data' => ''
            ];
            //  $groupFooter_Text1 = "";
            //  $groupFooter_Text2 = "科目計(貸方金額－借方金額)";
            $groupFooter = [
                "KAMOKUTotal_Text1" => array(
                    "Text" => "科目計",
                    "ColPosition" => "10"
                ),
                "KAMOKUTotal_Text2" => array(
                    "Text" => "(貸方金額－借方金額)",
                    "ColPosition" => "11"
                ),
                "KAMOKUTotal_Text3" => array(
                    "Text" => "(借方金額－貸方金額)",
                    "ColPosition" => "11"
                ),
                "DENPYONO_total" => array(
                    "Text" => "",
                    "ColPosition" => "13"
                ),
                "KARIKIN_total" => array(
                    "Text" => "",
                    "ColPosition" => "14"
                ),
                "KASIKIN_total" => array(
                    "Text" => "",
                    "ColPosition" => "15"
                )
            ];
            $cellPosition = [];
            //import phpexcel class

            //define object create excel
            $objExcel = new Spreadsheet();
            //define reader and writer  excel path
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/KRSS/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
            $createExcelFilePath = $tmpPath . "科目別費用明細表_" . $this->FrmHiyouMeisai->GS_LOGINUSER['strUserID'] . ".xlsx";
            //科目別費用明細表.xlsx";
            $downloadExcelPath = "files/KRSS/" . "科目別費用明細表_" . $this->FrmHiyouMeisai->GS_LOGINUSER['strUserID'] . ".xlsx";
            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    throw new \Exception("Execl Error");
                }
            }
            //エクセルのテンプレートが保存されている場所を取得
            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmHiyouMeisaiTemplate.xlsx";
            //テンプレートファイルの存在確認
            if (file_exists($strTemplatePath) == FALSE) {

                throw new \Exception("EXCELテンプレートが見つかりません！");
            }

            $objReader = IOFactory::createReader("Xlsx");
            $objTemplatePHPExcel = $objReader->load($strTemplatePath);
            $objWorksheet = $objTemplatePHPExcel->getActiveSheet();

            //deal operation

            $highestRow = $objWorksheet->getHighestRow();
            // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn();
            // e.g 'F'

            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
            // e.g. 5

            //

            for ($col = 0; $col <= $highestColumnIndex; $col++) {
                $cellCoordinate = Coordinate::stringFromColumnIndex($col + 1) . 7;
                $cell = $objWorksheet->getCell($cellCoordinate);
                if ($cell->getValue() == "") {
                } else {
                    if (strpos($cell->getValue(), "{") == 0) {
                        $tmp1 = str_replace("{", "", $cell->getValue());
                        $tmp1 = str_replace("}", "", $tmp1);
                        $cellPosition[$tmp1]['row'] = 7;
                        $cellPosition[$tmp1]['cell'] = $col;
                    }
                }
            }
            $rowCnt = 0;
            $SheetCnt = 0;
            $stackKAMOKUCDColVal = "NULL";
            $flgKAMOKUCDCol = FALSE;
            $stackSORTBUSYOColVal = "NULL";
            $flgSORTBUSYOCol = FALSE;
            $stackHIMOKUCDColVal = "NULL";
            $flgHIMOKUCDCol = FALSE;

            $flgHICol = FALSE;
            $stackHIColVal = "NULL";

            $KARIKIN_total = 0;
            $KASIKIN_total = 0;
            foreach ($data as $value) {
                //SORTBUSYO
                if ($stackSORTBUSYOColVal != $value['SORTBUSYO']) {
                    $flgSORTBUSYOCol = TRUE;
                    $stackSORTBUSYOColVal = $value['SORTBUSYO'];
                } else {
                    $flgSORTBUSYOCol = FALSE;
                }
                //KAMOKUCD
                if ($stackKAMOKUCDColVal != $value['KAMOKUCD']) {
                    $flgKAMOKUCDCol = TRUE;

                    $stackHIMOKUCDColVal = "NULL";

                    //insert line of group footer total
                    //--
                    //--
                    if ($rowCnt > 0) {
                        $objExcel->getActiveSheet()->insertNewRowBefore(1, $rowCnt);
                        $cellCoordinate = Coordinate::stringFromColumnIndex($groupFooter["KAMOKUTotal_Text1"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
                        $objWorksheet->getCell($cellCoordinate)->setValue($groupFooter["KAMOKUTotal_Text1"]['Text']);
                        $cellCoordinate_02 = Coordinate::stringFromColumnIndex($groupFooter["DENPYONO_total"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
                        if (substr(str_pad($stackKAMOKUCDColVal, 2, " "), 0, 2) == "41" || substr(str_pad($stackKAMOKUCDColVal, 2, " "), 0, 2) == "51") {
                            $cellCoordinate_01 = Coordinate::stringFromColumnIndex($groupFooter["KAMOKUTotal_Text2"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
                            $objWorksheet->getCell($cellCoordinate_01)->setValue($groupFooter["KAMOKUTotal_Text2"]['Text']);
                            $objWorksheet->getCell($cellCoordinate_02)->setValue(number_format((int) $KASIKIN_total - (int) $KARIKIN_total));
                        } else {
                            $cellCoordinate_01 = Coordinate::stringFromColumnIndex($groupFooter["KAMOKUTotal_Text3"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
                            $objWorksheet->getCell($cellCoordinate_01)->setValue($groupFooter["KAMOKUTotal_Text3"]['Text']);
                            $objWorksheet->getCell($cellCoordinate_02)->setValue(number_format((int) $KARIKIN_total - (int) $KASIKIN_total));
                        }
                        $cellCoordinate_03 = Coordinate::stringFromColumnIndex($groupFooter["KARIKIN_total"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
                        $cellCoordinate_04 = Coordinate::stringFromColumnIndex($groupFooter["KASIKIN_total"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
                        $objWorksheet->getCell($cellCoordinate_03)->setValue(number_format($KARIKIN_total));
                        $objWorksheet->getCell($cellCoordinate_04)->setValue(number_format($KASIKIN_total));
                        $objWorksheet->getStyle('M' . ($rowCnt + $highestRow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        // $objWorksheet -> getCellByColumnAndRow($groupFooter["DENPYONO_total"]["ColPosition"] - 1, (int)($rowCnt + $highestRow)) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $rowCnt++;
                        $objExcel->getActiveSheet()->insertNewRowBefore(1, $rowCnt);
                        // $rowCnt++;
                        $KARIKIN_total = 0;
                        $KASIKIN_total = 0;
                    }
                    $stackKAMOKUCDColVal = $value['KAMOKUCD'];
                } else {
                    $flgKAMOKUCDCol = FALSE;
                }

                //HIMOKUCD
                if ($stackHIMOKUCDColVal != $value["HIMOKUCD"]) {
                    $flgHIMOKUCDCol = TRUE;
                    $stackHIMOKUCDColVal = $value["HIMOKUCD"];
                } else {
                    $flgHIMOKUCDCol = FALSE;
                }

                //HI
                if ($flgKAMOKUCDCol == TRUE) {
                    $stackHIColVal = $value["HI"];
                    $flgHICol = TRUE;
                } else {
                    if ($flgHIMOKUCDCol == TRUE) {
                        $stackHIColVal = $value["HI"];
                        $flgHICol = TRUE;
                    } else {
                        if ($stackHIColVal != $value["HI"]) {
                            $stackHIColVal = $value["HI"];
                            $flgHICol = TRUE;
                        } else {
                            $flgHICol = FALSE;
                        }
                    }
                }

                // if (($stackHIColVal != $value["HI"]) || ($flgKAMOKUCDCol == TRUE || $flgHIMOKUCDCol == TRUE)) {
                // if ($flgKAMOKUCDCol == FALSE && $flgHIMOKUCDCol == FALSE) {
                // $flgHICol = TRUE;
                // $stackHIColVal = $value["HI"];
                // } else {
                // $flgHICol = FALSE;
                // }
                //
                // } else {
                // $flgHICol = FALSE;
                // }

                if ($flgSORTBUSYOCol == TRUE) {
                    $SheetCnt++;
                    $rowCnt = 0;

                    //clone sheet
                    $objcloneSheet = clone $objTemplatePHPExcel->getSheetByName("科目別費用明細表");
                    $objcloneSheet->setTitle($value['S_BUSYONM']);
                    $objTemplatePHPExcel->addSheet($objcloneSheet);
                    //set active work sheet811
                    $objWorksheet = $objTemplatePHPExcel->setActiveSheetIndexByName($value['S_BUSYONM']);
                    //set header
                    $objWorksheet->getCell('B2')->setValue($value['NEN'] . '年' . $value['TUKI'] . '月');

                    $objWorksheet->setCellValueExplicit('C4', $value['SORTBUSYO'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                    // $objWorksheet -> getCell('C4') -> setValue($value['SORTBUSYO']);
                    $objWorksheet->getCell('D4')->setValue($value['S_BUSYONM']);
                    $objWorksheet->getCell('M3')->setValue($value['TODAY']);

                }
                foreach ($value as $key1 => $value1) {

                    if (array_key_exists($key1, $cellPosition)) {
                        $row1 = $cellPosition[$key1]["row"];
                        $col1 = $cellPosition[$key1]["cell"] + 1;
                        if ($key1 == "KAMOKUCD") {
                            if ($flgKAMOKUCDCol == FALSE) {
                                $value1 = "";
                            }
                        }

                        if ($key1 == "KOMOKUMEI") {
                            if ($flgHIMOKUCDCol == FALSE) {
                                $value1 = "";
                            }
                        }
                        $row1_cellCoordinate = Coordinate::stringFromColumnIndex($col1) . $row1 + $rowCnt + 1;
                        if ($key1 == "HIMOKUCD") {

                            if ($flgHIMOKUCDCol == FALSE) {
                                $value1 = "";
                            }
                            if ($value1 != "" && strlen($value1) == 1) {
                                $value1 = "0" . $value1;
                            }

                            // $row1++;
                            $objWorksheet->getCell($row1_cellCoordinate)->setValue(" ");
                        }

                        if ($key1 == "HI") {

                            if ($flgHICol == FALSE) {
                                $value1 = "";
                            }
                            // $row1++;
                            $objWorksheet->getCell($row1_cellCoordinate)->setValue(" ");
                        }

                        if ($key1 == "KARIKIN") {
                            $KARIKIN_total += $value1;
                        }
                        // if ($key1 == "DENPYONO") {
                        // if (strlen($value1) != 12) {
                        // // $value1 = (string)number_format($value1);
                        // $value1 = (string)$value1;
                        // $objWorksheet -> getStyle('M' . ($row1 + $rowCnt)) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        // } else {
                        // $objWorksheet -> getStyle('M' . ($row1 + $rowCnt)) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        // }
                        // //$DENPYONO_total += $value1;
                        // }
                        if ($key1 == "KASIKIN") {
                            $KASIKIN_total += $value1;
                        }
                        // if ($key1 == "BUSYOCD") {

                        // $row1++;
                        // $objWorksheet -> getCellByColumnAndRow($col1, $row1 + $rowCnt - 1) -> setValue("");
                        // //$row1++;
                        // }
                        // if ($key1 == "M_BUSYONM") {
                        // $row1++;
                        // $objWorksheet -> getCellByColumnAndRow($col1, $row1 + $rowCnt - 1) -> setValue("");
                        // }
                        $objWorksheet->getCell('Q7')->setValue(" ");
                        $row1_cellCoordinate = Coordinate::stringFromColumnIndex($col1) . $row1 + $rowCnt;
                        $objWorksheet->getCell($row1_cellCoordinate)->setValue($value1);
                    }
                }
                // $rowCnt++;
                $rowCnt++;
            }
            $text1_cellCoordinate = Coordinate::stringFromColumnIndex($groupFooter["KAMOKUTotal_Text1"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
            $objWorksheet->getCell($text1_cellCoordinate)->setValue($groupFooter["KAMOKUTotal_Text1"]['Text']);
            $total_cellCoordinate = Coordinate::stringFromColumnIndex($groupFooter["DENPYONO_total"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
            if (substr(str_pad($stackKAMOKUCDColVal, 2, " "), 0, 2) == "41" || substr(str_pad($stackKAMOKUCDColVal, 2, " "), 0, 2) == "51") {
                $text2_cellCoordinate = Coordinate::stringFromColumnIndex($groupFooter["KAMOKUTotal_Text2"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
                $objWorksheet->getCell($text2_cellCoordinate)->setValue($groupFooter["KAMOKUTotal_Text2"]['Text']);
                $objWorksheet->getCell($total_cellCoordinate)->setValue(number_format((int) $KASIKIN_total - (int) $KARIKIN_total));
            } else {
                $text3_cellCoordinate = Coordinate::stringFromColumnIndex($groupFooter["KAMOKUTotal_Text3"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
                $objWorksheet->getCell($text3_cellCoordinate)->setValue($groupFooter["KAMOKUTotal_Text3"]['Text']);
                $objWorksheet->getCell($total_cellCoordinate)->setValue(number_format((int) $KARIKIN_total - (int) $KASIKIN_total));
            }
            $KARIKIN_total_cellCoordinate = Coordinate::stringFromColumnIndex($groupFooter["KARIKIN_total"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
            $KASIKIN_total_cellCoordinate = Coordinate::stringFromColumnIndex($groupFooter["KASIKIN_total"]["ColPosition"]) . (int) ($rowCnt + $highestRow);
            $objWorksheet->getCell($KARIKIN_total_cellCoordinate)->setValue(number_format($KARIKIN_total));
            $objWorksheet->getCell($KASIKIN_total_cellCoordinate)->setValue(number_format($KASIKIN_total));

            $objWorksheet->getStyle('M' . ($rowCnt + $highestRow))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $highestRow1 = $objWorksheet->getHighestRow();
            $objWorksheet->getStyle('G7:' . 'G' . $highestRow1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objWorksheet->getStyle('G7:' . 'G' . $highestRow1)->getNumberFormat()->setFormatCode('000');
            $objWorksheet = $objTemplatePHPExcel->setActiveSheetIndexByName('科目別費用明細表');

            $sheetIndex = $objTemplatePHPExcel->getIndex($objTemplatePHPExcel->getSheetByName("科目別費用明細表"));
            $objTemplatePHPExcel->removeSheetByIndex($sheetIndex);

            //save excel
            $objWriter = IOFactory::createWriter($objTemplatePHPExcel, "Xlsx");

            $objWriter->save($createExcelFilePath);
            // return $downloadExcelPath;
            $this->result['result'] = TRUE;
            $this->result['data'] = $downloadExcelPath;

        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }

        return $this->result;

    }

    public function fncAuthorityInvest()
    {
        $result = [];
        $strBusyoCD = "";
        $CurrentForm = [];
        try {
            $this->FrmHiyouMeisai = new FrmHiyouMeisai();
            $this->Session = $this->request->getSession();
            $UPDUSER = $this->Session->read('login_user');

            $strBusyoCD = $_POST['data']['BusyoCd'];
            $CurrentForm = $_POST['data']['controls'];
            $result = $this->ClsComFncKRSS->fncAuthorityInvest($CurrentForm, $UPDUSER, $strBusyoCD);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
