<?php
/**
 * 説明：
 *
 *
 * @author fanzhengzhou
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
use App\Model\R4\KRSS\FrmSonekiMeisai;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmSonekiMeisaiController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsComFncKRSS');
    }
    public $FrmSonekiMeisai = "";
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmSonekiMeisai_layout');
    }

    public function fncGetBusyo()
    {
        $result = array();
        try {
            $this->FrmSonekiMeisai = new FrmSonekiMeisai();
            $result = $this->FrmSonekiMeisai->fncGetBusyo();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmSonekiMeisai = new FrmSonekiMeisai();
            $result = $this->FrmSonekiMeisai->selectData();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncAuthCheck()
    {
        $result = array();
        try {
            $this->FrmSonekiMeisai = new FrmSonekiMeisai();
            $result = $this->FrmSonekiMeisai->fncAuthCheck();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['result'] == TRUE && count((array) $result['data']) == 1) {
                $this->ClsComFnc->FncGetBusyoMstValue((String) $result['data'][0]["BUSYO_CD"], $this->ClsComFnc->GS_BUSYOMST);
                $result['BusyoMst'] = $this->ClsComFnc->GS_BUSYOMST;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncAuthorityInvest()
    {
        $result = array();
        $strBusyoCD = "";
        $strSyainNo = "";
        $CurrentForm = array();
        try {
            $this->FrmSonekiMeisai = new FrmSonekiMeisai();
            $strSyainNo = $this->FrmSonekiMeisai->GS_LOGINUSER['strUserID'];
            $strBusyoCD = $_POST['data']['BusyoCd'];
            $CurrentForm = $_POST['data']['controls'];
            $result = $this->ClsComFncKRSS->fncAuthorityInvest($CurrentForm, $strSyainNo, $strBusyoCD);
            if ($result['result'] == FALSE) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：実行
    //関 数 名：cmdEnd_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：科目別費用明細を印刷する
    //**********************************************************************
    public function cmdActionClick()
    {
        $result = array();
        $postArr = array();
        try {
            $postArr = $_POST['data'];
            $this->FrmSonekiMeisai = new FrmSonekiMeisai();
            $result = $this->FrmSonekiMeisai->fncPrintSelect($postArr['strKI'], $postArr['cboKisyu'], $postArr['cboYM'], $postArr['txtBusyoCDFrom'], $postArr['txtBusyoCDTo'], $postArr['AUTHID']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $USERID = $this->FrmSonekiMeisai->GS_LOGINUSER['strUserID'];

            // print_r($result['data']);
            // return;
            if (count((array) $result['data']) > 0) {
                $ExcelData = $result['data'];
                $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
                $tmpPath2 = "webroot/files/KRSS/";
                $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
                $file = $tmpPath . "損益科目内訳表_" . $USERID . ".xlsx";

                if (!file_exists($tmpPath)) {
                    if (!mkdir($tmpPath, 0777, TRUE)) {
                        $result["data"] = "Execl Error";
                        throw new \Exception($result["data"]);
                    }
                }

                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmSonekiMeisaiTemplate.xlsx";
                //テンプレートファイルの存在確認
                if (file_exists($strTemplatePath) == FALSE) {
                    $result["data"] = "EXCELテンプレートが見つかりません！";
                    throw new \Exception($result["data"]);
                }
                $objReader = IOFactory::createReader('Xlsx');
                $objPHPExcel = $objReader->load($strTemplatePath);
                $objPHPExcel->setActiveSheetIndex(0);
                $objActSheet = $objPHPExcel->getActiveSheet();
                //detail row start.
                $DetailStartRow = 6;
                //set title information.  第　97　期　01 月度　　損益科目内訳明細表　（yyyy/MM ～　yyyy/MM )
                $objActSheet->setCellValue('E' . 2, "第　" . $ExcelData[0]['KI'] . "　期　" . $ExcelData[0]['GATUDO'] . " 月度　　損益科目内訳明細表　（" . $ExcelData[0]['KISYU'] . " ～　" . $ExcelData[0]['SYORITUKI'] . ")");

                $objActSheet->setCellValue('E' . 5, $ExcelData[0]['SYORITUKI'] . "月");
                $objActSheet->setCellValue('F' . 5, $ExcelData[0]['ZENGETU1'] . "月");
                $objActSheet->setCellValue('G' . 5, $ExcelData[0]['ZENGETU2'] . "月");
                $objActSheet->setCellValue('H' . 5, $ExcelData[0]['ZENGETU3'] . "月");
                $objActSheet->setCellValue('I' . 5, $ExcelData[0]['ZENGETU4'] . "月");
                $objActSheet->setCellValue('J' . 5, $ExcelData[0]['ZENGETU5'] . "月");
                //set sheet's name.
                $objActSheet->setTitle($ExcelData[0]['BUSYO_NM']);

                $LAST_BUSYO_CD = $ExcelData[0]['BUSYO_CD'];
                $NOW_BUSYO_CD = "";
                foreach ((array) $ExcelData as $key => $value) {
                    $NOW_BUSYO_CD = $value['BUSYO_CD'];
                    if ($NOW_BUSYO_CD != $LAST_BUSYO_CD) {
                        $objClonedWorksheet = clone $objPHPExcel->getSheetByName($ExcelData[0]['BUSYO_NM']);
                        $objClonedWorksheet->setTitle($value['BUSYO_NM']);
                        $objPHPExcel->addSheet($objClonedWorksheet);
                    }
                    $LAST_BUSYO_CD = $NOW_BUSYO_CD;
                }
                $ColumnToKey = array('A' => 'MEISYOU', 'B' => 'KAMOKU_CD', 'C' => 'HIMOK_CD', 'D' => 'TOUGETUYOSAN', 'E' => 'S_TOUGETU', 'F' => 'S_ZENGETU1', 'G' => 'S_ZENGETU2', 'H' => 'S_ZENGETU3', 'I' => 'S_ZENGETU4', 'J' => 'S_ZENGETU5', 'K' => 'S_JISSEKI', 'L' => 'TOUKIYOSAN', 'M' => 'S_KAMIKI', 'N' => 'S_SIMOKI');

                //显示0开头的值
                $objActSheet->setCellValueExplicit('B' . 2, $ExcelData[0]['BUSYO_CD'] . "  " . $ExcelData[0]['BUSYO_NM'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                //$objActSheet -> setCellValue('D' . 3, $ExcelData[0]['BUSYO_NM']);
                $LAST_BUSYO_CD = $ExcelData[0]['BUSYO_CD'];
                $NOW_BUSYO_CD = "";
                $i = 0;
                $j = $DetailStartRow;
                $LAST_LINE_NO = $ExcelData[0]['LINE_NO'];
                $NOW_LINE_NO = "";
                //科目合計
                $KAMOKU_Total = array();
                //初期化
                foreach ($ColumnToKey as $v) {
                    $KAMOKU_Total[$v] = 0;
                }
                //$page = 2;
                foreach ((array) $ExcelData as $key => $value) {
                    $NOW_BUSYO_CD = $value['BUSYO_CD'];
                    if ($NOW_BUSYO_CD != $LAST_BUSYO_CD) {
                        $this->GroupFooter2_BeforePrint($KAMOKU_Total);
                        foreach ($ColumnToKey as $key3 => $value3) {
                            if ($value3 == "HIMOK_CD") {
                                continue;
                            }
                            $objActSheet->setCellValue($key3 . $j, $KAMOKU_Total[$value3]);
                            //初期化
                            $KAMOKU_Total[$value3] = 0;
                        }
                        //---20150708 fan add s.Draw border.
                        if ($j > 40) {
                            //---20150806 #2070 fanzhengzhou del s.
                            //template's bottom border's postion.
                            // $btmLinePos = 'A40:N40';
                            // $objActSheet -> getStyle($btmLinePos) -> getBorders() -> getBottom() -> setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                            //---20150806 #2070 fanzhengzhou del e.
                            //borders' color.
                            $color = array('rgb' => '808000');
                            $styleArray = array('borders' => array('vertical' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color, ), 'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color, ), 'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color, ), 'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color, ), ), );
                            $objActSheet->getStyle('A41:N' . $j)->applyFromArray($styleArray);
                            //---20150806 #2070 fanzhengzhou add s.
                            $bottomstyle = array('borders' => array('bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color)));
                            $KK = 1;
                            while ((40 + 35 * $KK) < $j) {
                                $BottomRow = 40 + 35 * $KK;
                                $PageBottomLinePos = "A{$BottomRow}:N{$BottomRow}";
                                $objActSheet->getStyle($PageBottomLinePos)->applyFromArray($bottomstyle);
                                ++$KK;
                            }
                            //---20150806 #2070 fanzhengzhou add e.
                        }
                        //---20150708 fan add e.Draw border.
                        $i++;
                        $objPHPExcel->setActiveSheetIndex($i);
                        $objActSheet = $objPHPExcel->getActiveSheet();
                        //显示0开头的值
                        $objActSheet->setCellValueExplicit('B' . 2, $ExcelData[$key]['BUSYO_CD'] . "  " . $ExcelData[$key]['BUSYO_NM'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        //$objActSheet -> setCellValue('D' . 3, $ExcelData[$key]['BUSYO_NM']);
                        $j = $DetailStartRow;
                    }

                    $NOW_LINE_NO = $value['LINE_NO'];
                    if ($LAST_BUSYO_CD == $NOW_BUSYO_CD && $NOW_LINE_NO != $LAST_LINE_NO) {
                        $this->GroupFooter2_BeforePrint($KAMOKU_Total);
                        foreach ($ColumnToKey as $key2 => $value2) {
                            if ($value2 == "HIMOK_CD") {
                                continue;
                            }
                            $objActSheet->setCellValue($key2 . $j, $KAMOKU_Total[$value2]);
                            //初期化
                            $KAMOKU_Total[$value2] = 0;
                        }
                        $j = $j + 2;
                    }
                    $RowData = $value;
                    $this->Detail_BeforePrint($RowData);
                    foreach ($ColumnToKey as $key1 => $value1) {
                        if ($value1 == "TOUGETUYOSAN" || $value1 == "TOUKIYOSAN") {
                            $objActSheet->setCellValue($key1 . $j, "");
                            continue;
                        }

                        if ($value1 == "HIMOK_CD") {
                            $objActSheet->setCellValueExplicit($key1 . $j, $RowData[$value1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        } else {
                            $objActSheet->setCellValue($key1 . $j, $RowData[$value1]);
                        }
                        switch ($value1) {
                            case "MEISYOU":
                                $KAMOKU_Total[$value1] = $value['ITEM_NM'];
                                break;
                            case "KAMOKU_CD":
                                $KAMOKU_Total[$value1] = "ライン計";
                                break;
                            case "HIMOK_CD":
                                $KAMOKU_Total[$value1] = $value['HIMOK_CD'];
                                break;
                            case "TOUGETUYOSAN":
                                $KAMOKU_Total[$value1] = $value['TOUGETUYOSAN'];
                                break;
                            case "TOUKIYOSAN":
                                $KAMOKU_Total[$value1] = $value['TOUKIYOSAN'];
                                break;
                            default:
                                $KAMOKU_Total[$value1] += $value[$value1];
                        }
                    }
                    $j++;
                    $LAST_LINE_NO = $NOW_LINE_NO;
                    $LAST_BUSYO_CD = $NOW_BUSYO_CD;
                }
                $this->GroupFooter2_BeforePrint($KAMOKU_Total);
                foreach ($ColumnToKey as $key => $value) {
                    if ($value == "HIMOK_CD") {
                        continue;
                    }
                    $objActSheet->setCellValue($key . $j, $KAMOKU_Total[$value]);
                }
                //---20150708 fan add s.Draw border.
                if ($j > 40) {
                    //---20150806 #2070 fanzhengzhou del s.
                    // //template's bottom border's postion.
                    // $btmLinePos = 'A40:N40';
                    // $objActSheet -> getStyle($btmLinePos) -> getBorders() -> getBottom() -> setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                    //---20150806 #2070 fanzhengzhou del e.
                    //borders' color.
                    $color = array('rgb' => '808000');
                    $styleArray = array('borders' => array('vertical' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color, ), 'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color, ), 'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color, ), 'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color, ), ), );
                    $objActSheet->getStyle('A41:N' . $j)->applyFromArray($styleArray);
                    //---20150806 #2070 fanzhengzhou add s.
                    $bottomstyle = array('borders' => array('bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => $color)));
                    $KK = 1;
                    while ((40 + 35 * $KK) < $j) {
                        $BottomRow = 40 + 35 * $KK;
                        $PageBottomLinePos = "A{$BottomRow}:N{$BottomRow}";
                        $objActSheet->getStyle($PageBottomLinePos)->applyFromArray($bottomstyle);
                        ++$KK;
                    }
                    //---20150806 #2070 fanzhengzhou add e.
                }
                //---20150708 fan add e.Draw border.
                //when open the file,show sheet1.
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
                $objWriter->save($file);
                $result['data'] = "files/KRSS/" . "損益科目内訳表_" . $USERID . ".xlsx";
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function ToHalfAdjust($dValue, $iDigits)
    {
        $dCoef = pow(10, $iDigits);

        if ($dValue > 0) {
            return floor($dValue * $dCoef + 0.5) / $dCoef;
        } else {
            return ceil($dValue * $dCoef - 0.5) / $dCoef;
        }
    }

    public function FncValueCnv($objText)
    {
        if (rtrim($objText) == "" || rtrim($objText) == "0") {
            //---NULLの場合---
            return "";
        } else {
            //---以外の場合
            return (string) $this->ToHalfAdjust((double) ((int) ($this->ClsComFnc->FncNz(rtrim($objText))) / 1000), 0);
        }

    }

    public function Detail_BeforePrint(&$DetailArr)
    {
        $DetailArr['S_TOUGETU'] = $this->FncValueCnv($DetailArr['S_TOUGETU']);
        $DetailArr['S_ZENGETU1'] = $this->FncValueCnv($DetailArr['S_ZENGETU1']);
        $DetailArr['S_ZENGETU2'] = $this->FncValueCnv($DetailArr['S_ZENGETU2']);
        $DetailArr['S_ZENGETU3'] = $this->FncValueCnv($DetailArr['S_ZENGETU3']);
        $DetailArr['S_ZENGETU4'] = $this->FncValueCnv($DetailArr['S_ZENGETU4']);
        $DetailArr['S_ZENGETU5'] = $this->FncValueCnv($DetailArr['S_ZENGETU5']);
        $DetailArr['S_JISSEKI'] = $this->FncValueCnv($DetailArr['S_JISSEKI']);
        $DetailArr['S_KAMIKI'] = $this->FncValueCnv($DetailArr['S_KAMIKI']);
        $DetailArr['S_SIMOKI'] = $this->FncValueCnv($DetailArr['S_SIMOKI']);

    }

    public function GroupFooter2_BeforePrint(&$GroupFooter2Arr)
    {
        $GroupFooter2Arr['TOUGETUYOSAN'] = $this->FncValueCnv($GroupFooter2Arr['TOUGETUYOSAN']);
        $GroupFooter2Arr['S_TOUGETU'] = $this->FncValueCnv($GroupFooter2Arr['S_TOUGETU']);
        $GroupFooter2Arr['S_ZENGETU1'] = $this->FncValueCnv($GroupFooter2Arr['S_ZENGETU1']);
        $GroupFooter2Arr['S_ZENGETU2'] = $this->FncValueCnv($GroupFooter2Arr['S_ZENGETU2']);
        $GroupFooter2Arr['S_ZENGETU3'] = $this->FncValueCnv($GroupFooter2Arr['S_ZENGETU3']);
        $GroupFooter2Arr['S_ZENGETU4'] = $this->FncValueCnv($GroupFooter2Arr['S_ZENGETU4']);
        $GroupFooter2Arr['S_ZENGETU5'] = $this->FncValueCnv($GroupFooter2Arr['S_ZENGETU5']);
        $GroupFooter2Arr['S_JISSEKI'] = $this->FncValueCnv($GroupFooter2Arr['S_JISSEKI']);
        $GroupFooter2Arr['TOUKIYOSAN'] = $this->FncValueCnv($GroupFooter2Arr['TOUKIYOSAN']);
        $GroupFooter2Arr['S_KAMIKI'] = $this->FncValueCnv($GroupFooter2Arr['S_KAMIKI']);
        $GroupFooter2Arr['S_SIMOKI'] = $this->FncValueCnv($GroupFooter2Arr['S_SIMOKI']);

    }

}
