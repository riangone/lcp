<?php
/**
 * 説明：新・経営成果管理表（VBA連動版）
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
 * 20160612           依赖#2530                      EXCEL出力機能の速度改善         Yinhuaiyu
 * 20160620           -　　　　　　　　               速度改善,レイアウト変更対応         HM
 * 20161102           -　　　　　　　　               出力ファイル名にパターン名付与     HM
 * 20250124            202501_KRSS_経営成果管理表修正.xlsx                出力エクセル補正                   LHB
 * 20250213           -                        テンプレートの調整                   LHB
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmBusyoKanriVBA;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmBusyoKanriVBAController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    private $FrmBusyoKanriVBA;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmBusyoKanriVBA_layout');
    }

    //パターン名取得
    public function fncPatternNMSel()
    {
        $result = array("result" => FALSE, "data" => "error");
        $this->FrmBusyoKanriVBA = new FrmBusyoKanriVBA();
        try {
            $result = $this->FrmBusyoKanriVBA->fncPatternNMSel();
            if ($result['result'] == FALSE) {
                throw new \Exception($result['data'], 1);
            } else {
                $tmpArr = array("PATTERN_NO" => "0", "PATTERN_NM" => "部署順");
                array_unshift($result['data'], $tmpArr);

                $this->fncReturn($result['data']);
            }
        } catch (\Exception $ex) {
            $result["data"] = $ex->getMessage();

            $this->fncReturn($result);
        }

    }

    //経理コントロールデータ取得
    public function fncHKEIRICTL()
    {
        $result = array("result" => FALSE, "data" => "error");
        $this->FrmBusyoKanriVBA = new FrmBusyoKanriVBA();
        try {
            $result = $this->FrmBusyoKanriVBA->fncHKEIRICTL();
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            } elseif (count((array) $result["data"]) <= 0) {
                $result["result"] = FALSE;
                throw new \Exception("コントロールマスタが存在しません！", 1);
            }
        } catch (\Exception $ex) {
            $result["data"] = $ex->getMessage();
        }

        $this->fncReturn($result);
    }

    //部署名取得
    public function fncGetBusyo()
    {
        $result = array("result" => FALSE, "data" => "error");
        $this->FrmBusyoKanriVBA = new FrmBusyoKanriVBA();
        try {
            $result1 = $this->FrmBusyoKanriVBA->fncGetBusyo();
            if ($result1["result"] == FALSE) {
                throw new \Exception($result1["data"], 1);
            }
            $this->Session = $this->request->getSession();
            $result2 = $this->FrmBusyoKanriVBA->fncGetMaxMinBusyoCD($this->Session->read('login_user'));
            if ($result2["result"] == FALSE) {
                $result2['result'] = FALSE;
                throw new \Exception($result2["data"], 1);
            }
            $result["result"] = TRUE;
            $result["AllBusyoArr"] = $result1['data'];
            $result["MaxMinBusyoArr"] = $result2['data'];
        } catch (\Exception $ex) {
            $result["data"] = $ex->getMessage();
        }

        $this->fncReturn($result);
    }

    //部署取得
    public function fncMaxMinBusyo()
    {
        $this->FrmBusyoKanriVBA = new FrmBusyoKanriVBA();
        try {
            $this->Session = $this->request->getSession();
            $result2 = $this->FrmBusyoKanriVBA->fncGetMaxMinBusyoCD($this->Session->read('login_user'));
            if ($result2["result"] == FALSE) {
                $result2['result'] = FALSE;
                throw new \Exception($result2["data"], 1);
            }
        } catch (\Exception $ex) {
            $result2['data'] = $ex->getMessage();
        }

        $this->fncReturn($result2);
    }

    //検索処理
    public function fncSelect()
    {

        $result = array("result" => FALSE, "data" => "error", "MsgID" => "E9999");
        $this->FrmBusyoKanriVBA = new FrmBusyoKanriVBA();
        try {
            $intRpt = 0;
            $arrAllBusyoRowData = array();
            $postData = $_POST["data"];
            $onlyBusyo = $postData["onlyBusyo"];
            $KI = $postData["KI"];
            $NENGTU = $postData["cboYM"];
            $busyoCD_From = $postData["busyoCD_From"];
            $busyoCD_To = $postData["busyoCD_To"];
            $chkMikakudei = $postData["chkMikakudei"];
            $jqGridData = array();
            if ($onlyBusyo != "true") {
                $jqGridData = $postData["jqGridRowData"];
            }
            $this->Session = $this->request->getSession();
            $UPDUSER = $this->Session->read('login_user');

            if ($onlyBusyo == "true") {
                $pattern_No = 0;
                //20161102 Upd Start
                $pattern_Nm = $busyoCD_From . '-' . $busyoCD_To;
                //20161102 Upd End

                //                               $result = $this -> fncWKDeal($this -> FrmBusyoKanriVBA, $pattern_No, $busyoCD_From, $busyoCD_To, 0, $UPDUSER, $NENGTU);
//                                if ($result["result"] == FALSE) {
//                                        throw new Exception($result["data"], 1);
//                                }

                //                                $result = $this -> FrmBusyoKanriVBA -> fncSelect($KI, $busyoCD_From, $busyoCD_To, $pattern_No, $busyoCD_Checked, $NENGTU);
                $result = $this->FrmBusyoKanriVBA->fncSelect($busyoCD_From, $busyoCD_To, $pattern_No, $NENGTU, $UPDUSER, $chkMikakudei);

                if ($result["result"] == FALSE) {
                    throw new \Exception($result["data"], 1);
                } else {
                    if (count((array) $result["data"]) > 0) {
                        $intRpt++;
                    }
                }
                foreach ((array) $result["data"] as $value1) {
                    if (isset($arrAllBusyoRowData[$value1["BUSYO_CD"]]) == TRUE && is_array($arrAllBusyoRowData[$value1["BUSYO_CD"]]) == TRUE) {
                        array_push($arrAllBusyoRowData[$value1["BUSYO_CD"]], $value1);
                    } else {
                        $arrAllBusyoRowData[$value1["BUSYO_CD"]] = array();
                        array_push($arrAllBusyoRowData[$value1["BUSYO_CD"]], $value1);
                    }
                }
            } else {
                foreach ($jqGridData as $value) {
                    $pattern_No = $value["PATTERN_NO"];
                    //20161102 Upd Start
                    $pattern_Nm = $value["PATTERN_NM"];
                    //20161102 Upd End

                    //                                        $result = $this -> fncWKDeal($this -> FrmBusyoKanriVBA, $pattern_No, $busyoCD_From, $busyoCD_To, 0, $UPDUSER, $NENGTU);
//                                        if ($result["result"] == FALSE) {
//                                                    throw new Exception($result["data"], 1);
//                                        }
//                                        $result = $this -> FrmBusyoKanriVBA -> fncSelect($KI, $busyoCD_From, $busyoCD_To, $pattern_No, $busyoCD_Checked, $NENGTU);
                    $result = $this->FrmBusyoKanriVBA->fncSelect($busyoCD_From, $busyoCD_To, $pattern_No, $NENGTU, $UPDUSER, $chkMikakudei);
                    if ($result["result"] == FALSE) {
                        throw new \Exception($result["data"], 1);
                    } else {
                        if (count((array) $result["data"]) > 0) {
                            $intRpt++;
                        }
                    }
                    foreach ((array) $result["data"] as $value1) {
                        if (isset($arrAllBusyoRowData[$value1["BUSYO_CD"]]) == TRUE && is_array($arrAllBusyoRowData[$value1["BUSYO_CD"]]) == TRUE) {
                            array_push($arrAllBusyoRowData[$value1["BUSYO_CD"]], $value1);
                        } else {
                            $arrAllBusyoRowData[$value1["BUSYO_CD"]] = array();
                            array_push($arrAllBusyoRowData[$value1["BUSYO_CD"]], $value1);
                        }
                    }
                }
            }

            if ($intRpt == 0) {
                $result["MsgID"] = "I0001";
                throw new \Exception("Error");
            }

            //20161102 Upd Start
//                    $result = $this -> fncExcelExport($arrAllBusyoRowData, $KI);
            $result = $this->fncExcelExport($arrAllBusyoRowData, $KI, $pattern_Nm);
            //20161102 Upd End
            $result['result'] = TRUE;
            if ($result["result"] == TRUE) {

            } else {
                throw new \Exception($result["data"], 1);
            }

        } catch (\Exception $ex) {
            $result["result"] = FALSE;
            $result["data"] = $ex->getMessage();
        }

        $this->fncReturn($result);
    }

    //EXCEL出力処理
//20161102 Upd Start
//    private function fncExcelExport($data, $KI) {
    private function fncExcelExport($data, $KI, $pattern_Nm)
    {
        //20161102 Upd End

        $result = array("result" => FALSE, "data" => "error");
        //Excelファイルの先頭行を定義
        $startNo = 9;
        try {
            $this->FrmBusyoKanriVBA = new FrmBusyoKanriVBA();
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/KRSS/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
            // $USERID = $this->FrmBusyoKanriVBA->GS_LOGINUSER['strUserID'];
            // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";
            //20160612 YIN UPD S
            //$exportFile = $tmpPath . "経営成果管理表_" . $USERID . ".xlsx";
            //$exportFile = $tmpPath . "経営成果管理表_" . $USERID . ".xlsm";
            //20160612 YIN UPD E

            //            $exportFile = $tmpPath . "経営成果管理表.xlsm";
            $exportFile = $tmpPath . "経営成果管理表" . $pattern_Nm . ".xlsm";
            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    throw new \Exception("Execl Error");
                }
            }
            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
            //20160612 YIN UPD S
            // $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "FrmBusyoKanrTemplate.xlsx";
//20160620 Upd Start
//            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "FrmBusyoKanriVBATemplate.xlsm";
//20160612 YIN UPD E
//            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "/KRSS/FrmBusyoKanriVBATemplate.xlsm";
//20160620 Upd End
            // 20250213 LHB UPD S
            // $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmBusyoKanriVBATemplate.xlsm";
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmBusyoKanriVBATemplate2025.xlsm";
            // 20250213 LHB UPD E

            if (file_exists($strTemplatePath) == FALSE) {
                throw new \Exception("EXCELテンプレートが見つかりません！");
            }
            //---20150804 fanzhengzhou add s.Reduce the use of memory...
            //20160530 YIN UPD S
            //$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
            // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            //              $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_memcache;
//            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;

            //20160530 YIN UPD E
            // $cacheSettings = array();
            //            $cacheSettings = "";

            // PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            //---20150804 fanzhengzhou add e.
            $objPHPExcel = IOFactory::load($strTemplatePath);
            //20160620 Upd Start
            //$monthArr1 = array("10" => "O", "11" => "S", "12" => "W", "01" => "AF", "02" => "AJ", "03" => "AN", "04" => "AW", "05" => "BA", "06" => "BE", "07" => "BN", "08" => "BR", "09" => "BV");
            //---20150727 fanzhengzhou add s.
            $monthArr1 = array("10" => "R", "11" => "Y", "12" => "AF", "01" => "AU", "02" => "BB", "03" => "BI", "04" => "BX", "05" => "CE", "06" => "CL", "07" => "DA", "08" => "DH", "09" => "DO");
            //20160620 Upd End

            $curMonth = '';
            $lastMonth = '';

            //20160620 Upd Start
            //---20150727 fanzhengzhou add e.
            //20160530 YIN UPD S
            // foreach ($data as $key => $value) {
            // $tmpLineArr = array();
            // $objCloneWorkSheet = clone $objPHPExcel -> getSheetByName("経営成果管理表");
            // $BusyoName = $value[0]["BUSYO_NM"];
            // $sheetName = $key . "." . $BusyoName;
//
            // $objCloneWorkSheet -> setTitle($sheetName, FALSE);
            // $objPHPExcel -> addSheet($objCloneWorkSheet);
            // $objPHPExcel -> setActiveSheetIndexByName($sheetName);
            // $objPHPExcel -> getActiveSheet() -> setCellValue("D3", $KI);
            // $objPHPExcel -> getActiveSheet() -> setCellValue("D5", $key);
            // $objPHPExcel -> getActiveSheet() -> setCellValue("E5", $BusyoName);
            // //---20150727 fanzhengzhou add s.
            // $resComment = $this -> FrmBusyoKanr -> selectComment($KI, $key);
            // if (count($resComment['data']) > 0) {
            // $objPHPExcel -> getActiveSheet() -> setCellValue('J3', $resComment['data'][0]['COMMENT_STR']);
            // }
            // //---20150727 fanzhengzhou add e.
            // foreach ($value as $key1 => $value1) {
            // $LineNO = $startNo + (int)$value1["LINE_NO"];
            // array_push($tmpLineArr, $LineNO);
            // $ym = $value1["NENGETU"];
            // $month = substr($ym, 4, 2);
            // //---20150727 fanzhengzhou add s.  COMMENT_STR を編集する
            // $curMonth = $month;
            // if ($curMonth != $lastMonth) {
            // $objPHPExcel -> getActiveSheet() -> setCellValue($monthArr1[$curMonth] . '3', $value1["COMMENT_STR"]);
            // }
            // $lastMonth = $curMonth;
            // //---20150727 fanzhengzhou add e.
            // $column = $monthArr1[$month];
            // //計画 KEIKAKU
            // $objPHPExcel -> getActiveSheet() -> setCellValue($column . $LineNO, $value1["KEIKAKU"] == 0 ? "" : $value1["KEIKAKU"]);
            // //$objPHPExcel -> getActiveSheet() -> setCellValue($column . $LineNO, $value1["KEIKAKU"]);
//
            // // //実績 JISSEKI
            // // $tColumn = "";
            // // for ($a = $column; $a < "ZZZZ"; $a++) {
            // // $a++;
            // // $tColumn = $a;
            // // break;
            // // }
            // // $objPHPExcel -> getActiveSheet() -> setCellValue($tColumn . $LineNO, $value1["JISSEKI"] == 0 ? "" : $value1["JISSEKI"]);
            // //
            // // //計画差 KEIKAKUSA
            // // $tColumn = "";
            // // for ($a = $column; $a < "ZZZZ"; $a++) {
            // // $a++;
            // // $a++;
            // // $tColumn = $a;
            // // break;
            // // }
            // // $objPHPExcel -> getActiveSheet() -> setCellValue($tColumn . $LineNO, $value1["KEIKAKUSA"] == 0 ? "" : $value1["KEIKAKUSA"]);
            // //
            // // //前年比 ZENNENHI
            // // $tColumn = "";
            // // for ($a = $column; $a < "ZZZZ"; $a++) {
            // // $a++;
            // // $a++;
            // // $a++;
            // // $tColumn = $a;
            // // break;
            // // }
            // // $objPHPExcel -> getActiveSheet() -> setCellValue($tColumn . $LineNO, $value1["ZENNENHI"] == 0 ? "" : $value1["ZENNENHI"]);
            // //---fanzhengzhou upd s.
            // //実績 JISSEKI
            // $objPHPExcel -> getActiveSheet() -> setCellValue(++$column . $LineNO, $value1["JISSEKI"] == 0 ? "" : $value1["JISSEKI"]);
            // //計画差 KEIKAKUSA
            // $objPHPExcel -> getActiveSheet() -> setCellValue(++$column . $LineNO, $value1["KEIKAKUSA"] == 0 ? "" : $value1["KEIKAKUSA"]);
            // //前年比 ZENNENHI
            // $objPHPExcel -> getActiveSheet() -> setCellValue(++$column . $LineNO, $value1["ZENNENHI"] == 0 ? "" : $value1["ZENNENHI"]);
            // //---fanzhengzhou upd e.
            // }
            // foreach ($tmpLineArr as $key2 => $value2) {
            // //--- 1月~３月　の　集計---
            // //計画集計
            // $KEIKAKU_1 = $objPHPExcel -> getActiveSheet() -> getCell("AF" . $value2) -> getValue();
            // $KEIKAKU_2 = $objPHPExcel -> getActiveSheet() -> getCell("AJ" . $value2) -> getValue();
            // $KEIKAKU_3 = $objPHPExcel -> getActiveSheet() -> getCell("AN" . $value2) -> getValue();
            // if ($KEIKAKU_1 != "" || $KEIKAKU_2 != "" || $KEIKAKU_3 != "") {
            // $KEIKAKU_1to3_total = (int)$KEIKAKU_1 + (int)$KEIKAKU_2 + (int)$KEIKAKU_3;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("AR" . $value2, $KEIKAKU_1to3_total);
            // }
//
            // //実績集計
            // $JISSEKI_1 = $objPHPExcel -> getActiveSheet() -> getCell("AG" . $value2) -> getValue();
            // $JISSEKI_2 = $objPHPExcel -> getActiveSheet() -> getCell("AK" . $value2) -> getValue();
            // $JISSEKI_3 = $objPHPExcel -> getActiveSheet() -> getCell("AO" . $value2) -> getValue();
            // if ($JISSEKI_1 != "" || $JISSEKI_2 != "" || $JISSEKI_3 != "") {
            // $JISSEKI_1to3_total = (int)$JISSEKI_1 + (int)$JISSEKI_2 + (int)$JISSEKI_3;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("AS" . $value2, $JISSEKI_1to3_total);
            // }
//
            // //計画差 集計
            // $KEIKAKUSA_1 = $objPHPExcel -> getActiveSheet() -> getCell("AH" . $value2) -> getValue();
            // $KEIKAKUSA_2 = $objPHPExcel -> getActiveSheet() -> getCell("AL" . $value2) -> getValue();
            // $KEIKAKUSA_3 = $objPHPExcel -> getActiveSheet() -> getCell("AP" . $value2) -> getValue();
            // if ($KEIKAKUSA_1 != "" || $KEIKAKUSA_2 != "" || $KEIKAKUSA_3 != "") {
            // $KEIKAKUSA_1to3_total = (int)$KEIKAKUSA_1 + (int)$KEIKAKUSA_2 + (int)$KEIKAKUSA_3;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("AT" . $value2, $KEIKAKUSA_1to3_total);
            // }
//
            // //前年比集計
            // $ZENNENHI_1 = $objPHPExcel -> getActiveSheet() -> getCell("AI" . $value2) -> getValue();
            // $ZENNENHI_2 = $objPHPExcel -> getActiveSheet() -> getCell("AM" . $value2) -> getValue();
            // $ZENNENHI_3 = $objPHPExcel -> getActiveSheet() -> getCell("AQ" . $value2) -> getValue();
            // if ($ZENNENHI_1 != "" || $ZENNENHI_2 != "" || $ZENNENHI_3 != "") {
            // $ZENNENHI_1to3_total = (int)$ZENNENHI_1 + (int)$ZENNENHI_2 + (int)$ZENNENHI_3;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("AU" . $value2, $ZENNENHI_1to3_total);
            // }
//
            // //--- 4月~6月　の　集計---
            // //計画集計
            // $KEIKAKU_4 = $objPHPExcel -> getActiveSheet() -> getCell("AW" . $value2) -> getValue();
            // $KEIKAKU_5 = $objPHPExcel -> getActiveSheet() -> getCell("BA" . $value2) -> getValue();
            // $KEIKAKU_6 = $objPHPExcel -> getActiveSheet() -> getCell("BE" . $value2) -> getValue();
            // if ($KEIKAKU_4 != "" || $KEIKAKU_5 != "" || $KEIKAKU_6 != "") {
            // $KEIKAKU_4to6_total = (int)$KEIKAKU_4 + (int)$KEIKAKU_5 + (int)$KEIKAKU_6;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("BI" . $value2, $KEIKAKU_4to6_total);
            // }
//
            // //実績集計
            // $JISSEKI_4 = $objPHPExcel -> getActiveSheet() -> getCell("AX" . $value2) -> getValue();
            // $JISSEKI_5 = $objPHPExcel -> getActiveSheet() -> getCell("BB" . $value2) -> getValue();
            // $JISSEKI_6 = $objPHPExcel -> getActiveSheet() -> getCell("BF" . $value2) -> getValue();
            // if ($JISSEKI_4 != "" || $JISSEKI_5 != "" || $JISSEKI_6 != "") {
            // $JISSEKI_4to6_total = (int)$JISSEKI_4 + (int)$JISSEKI_5 + (int)$JISSEKI_6;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("BJ" . $value2, $JISSEKI_4to6_total);
            // }
//
            // //計画差 集計
            // $KEIKAKUSA_4 = $objPHPExcel -> getActiveSheet() -> getCell("AY" . $value2) -> getValue();
            // $KEIKAKUSA_5 = $objPHPExcel -> getActiveSheet() -> getCell("BC" . $value2) -> getValue();
            // $KEIKAKUSA_6 = $objPHPExcel -> getActiveSheet() -> getCell("BG" . $value2) -> getValue();
            // if ($KEIKAKUSA_4 != "" || $KEIKAKUSA_5 != "" || $KEIKAKUSA_6 != "") {
            // $KEIKAKUSA_4to6_total = (int)$KEIKAKUSA_4 + (int)$KEIKAKUSA_5 + (int)$KEIKAKUSA_6;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("BK" . $value2, $KEIKAKUSA_4to6_total);
            // }
//
            // //前年比集計
            // $ZENNENHI_4 = $objPHPExcel -> getActiveSheet() -> getCell("AZ" . $value2) -> getValue();
            // $ZENNENHI_5 = $objPHPExcel -> getActiveSheet() -> getCell("BD" . $value2) -> getValue();
            // $ZENNENHI_6 = $objPHPExcel -> getActiveSheet() -> getCell("BH" . $value2) -> getValue();
            // if ($ZENNENHI_4 != "" || $ZENNENHI_5 != "" || $ZENNENHI_6 != "") {
            // $ZENNENHI_4to6_total = (int)$ZENNENHI_4 + (int)$ZENNENHI_5 + (int)$ZENNENHI_6;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("BL" . $value2, $ZENNENHI_4to6_total);
            // }
//
            // //--- 7月~9月　の　集計---
            // //計画集計
            // $KEIKAKU_7 = $objPHPExcel -> getActiveSheet() -> getCell("BN" . $value2) -> getValue();
            // $KEIKAKU_8 = $objPHPExcel -> getActiveSheet() -> getCell("BR" . $value2) -> getValue();
            // $KEIKAKU_9 = $objPHPExcel -> getActiveSheet() -> getCell("BV" . $value2) -> getValue();
            // if ($KEIKAKU_7 != "" || $KEIKAKU_8 != "" || $KEIKAKU_9 != "") {
            // $KEIKAKU_7to9_total = (int)$KEIKAKU_7 + (int)$KEIKAKU_8 + (int)$KEIKAKU_9;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("BZ" . $value2, $KEIKAKU_7to9_total);
            // }
//
            // //実績集計
            // $JISSEKI_7 = $objPHPExcel -> getActiveSheet() -> getCell("BO" . $value2) -> getValue();
            // $JISSEKI_8 = $objPHPExcel -> getActiveSheet() -> getCell("BS" . $value2) -> getValue();
            // $JISSEKI_9 = $objPHPExcel -> getActiveSheet() -> getCell("BW" . $value2) -> getValue();
            // if ($JISSEKI_7 != "" || $JISSEKI_8 != "" || $JISSEKI_9 != "") {
            // $JISSEKI_7to9_total = (int)$JISSEKI_7 + (int)$JISSEKI_8 + (int)$JISSEKI_9;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("CA" . $value2, $JISSEKI_7to9_total);
            // }
//
            // //計画差 集計
            // $KEIKAKUSA_7 = $objPHPExcel -> getActiveSheet() -> getCell("BP" . $value2) -> getValue();
            // $KEIKAKUSA_8 = $objPHPExcel -> getActiveSheet() -> getCell("BT" . $value2) -> getValue();
            // $KEIKAKUSA_9 = $objPHPExcel -> getActiveSheet() -> getCell("BX" . $value2) -> getValue();
            // if ($KEIKAKUSA_7 != "" || $KEIKAKUSA_8 != "" || $KEIKAKUSA_9 != "") {
            // $KEIKAKUSA_7to9_total = (int)$KEIKAKUSA_7 + (int)$KEIKAKUSA_8 + (int)$KEIKAKUSA_9;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("CB" . $value2, $KEIKAKUSA_7to9_total);
            // }
//
            // //前年比集計
            // $ZENNENHI_7 = $objPHPExcel -> getActiveSheet() -> getCell("BQ" . $value2) -> getValue();
            // $ZENNENHI_8 = $objPHPExcel -> getActiveSheet() -> getCell("BU" . $value2) -> getValue();
            // $ZENNENHI_9 = $objPHPExcel -> getActiveSheet() -> getCell("BY" . $value2) -> getValue();
            // if ($ZENNENHI_7 != "" || $ZENNENHI_8 != "" || $ZENNENHI_9 != "") {
            // $ZENNENHI_7to9_total = (int)$ZENNENHI_7 + (int)$ZENNENHI_8 + (int)$ZENNENHI_9;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("CC" . $value2, $ZENNENHI_7to9_total);
            // }
//
            // //--- 10月~12月　の　集計---
            // //計画集計
            // $KEIKAKU_10 = $objPHPExcel -> getActiveSheet() -> getCell("O" . $value2) -> getValue();
            // $KEIKAKU_11 = $objPHPExcel -> getActiveSheet() -> getCell("S" . $value2) -> getValue();
            // $KEIKAKU_12 = $objPHPExcel -> getActiveSheet() -> getCell("W" . $value2) -> getValue();
            // if ($KEIKAKU_10 != "" || $KEIKAKU_11 != "" || $KEIKAKU_12 != "") {
            // $KEIKAKU_10to12_total = (int)$KEIKAKU_10 + (int)$KEIKAKU_11 + (int)$KEIKAKU_12;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("AA" . $value2, $KEIKAKU_10to12_total);
            // }
//
            // //実績集計
            // $JISSEKI_10 = $objPHPExcel -> getActiveSheet() -> getCell("P" . $value2) -> getValue();
            // $JISSEKI_11 = $objPHPExcel -> getActiveSheet() -> getCell("T" . $value2) -> getValue();
            // $JISSEKI_12 = $objPHPExcel -> getActiveSheet() -> getCell("X" . $value2) -> getValue();
            // if ($JISSEKI_10 != "" || $JISSEKI_11 != "" || $JISSEKI_12 != "") {
            // $JISSEKI_10to12_total = (int)$JISSEKI_10 + (int)$JISSEKI_11 + (int)$JISSEKI_12;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("AB" . $value2, $JISSEKI_10to12_total);
            // }
//
            // //計画差 集計
            // $KEIKAKUSA_10 = $objPHPExcel -> getActiveSheet() -> getCell("Q" . $value2) -> getValue();
            // $KEIKAKUSA_11 = $objPHPExcel -> getActiveSheet() -> getCell("U" . $value2) -> getValue();
            // $KEIKAKUSA_12 = $objPHPExcel -> getActiveSheet() -> getCell("Y" . $value2) -> getValue();
            // if ($KEIKAKUSA_10 != "" || $KEIKAKUSA_11 != "" || $KEIKAKUSA_12 != "") {
            // $KEIKAKUSA_10to12_total = (int)$KEIKAKUSA_10 + (int)$KEIKAKUSA_11 + (int)$KEIKAKUSA_12;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("AC" . $value2, $KEIKAKUSA_10to12_total);
            // }
//
            // //前年比集計
            // $ZENNENHI_10 = $objPHPExcel -> getActiveSheet() -> getCell("R" . $value2) -> getValue();
            // $ZENNENHI_11 = $objPHPExcel -> getActiveSheet() -> getCell("V" . $value2) -> getValue();
            // $ZENNENHI_12 = $objPHPExcel -> getActiveSheet() -> getCell("Z" . $value2) -> getValue();
            // if ($ZENNENHI_10 != "" || $ZENNENHI_11 != "" || $ZENNENHI_12 != "") {
            // $ZENNENHI_10to12_total = (int)$ZENNENHI_10 + (int)$ZENNENHI_11 + (int)$ZENNENHI_12;
            // $objPHPExcel -> getActiveSheet() -> setCellValue("AD" . $value2, $ZENNENHI_10to12_total);
            // }
            // }
//
            // }
            //$this -> log("foreach:strat");
//20160620 Upd End


            //20160612 YIN INS S
            $sheetNo = 0;
            $firstNo = 0;
            $objPHPExcel->setActiveSheetIndex(0);
            $objActiveSheet = $objPHPExcel->getActiveSheet();
            //20160612 YIN INS E
            foreach ($data as $key => $value) {
                $tmpLineArr = array();
                //20160612 YIN DEL S
                //$objCloneWorkSheet = clone $objPHPExcel -> getSheetByName("経営成果管理表");
                //20160612 YIN DEL E
                $BusyoName = $value[0]["BUSYO_NM"];
                //20160612 YIN DEL S
                //$sheetName = $key . "." . $BusyoName;

                //$objCloneWorkSheet -> setTitle($sheetName, FALSE);
                //$objPHPExcel -> addSheet($objCloneWorkSheet);
                //20160612 YIN DEL E
                //20160612 YIN UPD S
                // $objActiveSheet -> setCellValue("D3", $KI);
                // $objActiveSheet -> setCellValue("D5", $key);
                // $objActiveSheet -> setCellValue("E5", $BusyoName);

                //期
//20160620 Upd Start
//	$objActiveSheet -> setCellValue("D".($firstNo+3), $KI);
                $objActiveSheet->setCellValue("D" . ($firstNo + 3), $value[0]["KI"]);
                //20160620 Upd End

                $objActiveSheet->setCellValue("D" . ($firstNo + 5), $key);
                $objActiveSheet->setCellValue("E" . ($firstNo + 5), $BusyoName);
                //20160612 YIN UPD E
                $resComment = $this->FrmBusyoKanriVBA->selectComment($KI, $key);

                //コメント
                if (count((array) $resComment['data']) > 0) {
                    //20160612 YIN UPD S
                    // $objActiveSheet -> setCellValue('J3', $resComment['data'][0]['COMMENT_STR']);
                    $objActiveSheet->setCellValue('J' . ($firstNo + 3), $resComment['data'][0]['COMMENT_STR']);
                    //20160612 YIN UPD E
                }

                //データ数ぶん繰り返し
                foreach ($value as $value1) {
                    $LineNO = $startNo + (int) $value1["LINE_NO"];
                    array_push($tmpLineArr, $LineNO);
                    $ym = $value1["NENGETU"];
                    $month = substr($ym, 4, 2);
                    $curMonth = $month;
                    if ($curMonth != $lastMonth) {
                        //20160612 YIN UPD S
                        // $objActiveSheet -> setCellValue($monthArr1[$curMonth] . '3', $value1["COMMENT_STR"]);
                        $objActiveSheet->setCellValue($monthArr1[$curMonth] . ($firstNo + 3), $value1["COMMENT_STR"]);
                        //20160612 YIN UPD E
                    }
                    $lastMonth = $curMonth;

                    $column = $monthArr1[$month];
                    //計画 KEIKAKU
                    $objActiveSheet->setCellValue($column . $LineNO, $value1["KEIKAKU"] == 0 ? "" : $value1["KEIKAKU"]);
                    //実績 JISSEKI
                    $objActiveSheet->setCellValue(++$column . $LineNO, $value1["JISSEKI"] == 0 ? "" : $value1["JISSEKI"]);
                    //指標 SHIHYO
                    $objActiveSheet->setCellValue(++$column . $LineNO, $value1["SHIHYO"] == 0 ? "" : $value1["SHIHYO"]);
                    //計画差 KEIKAKUSA
                    $objActiveSheet->setCellValue(++$column . $LineNO, $value1["KEIKAKUSA"] == 0 ? "" : $value1["KEIKAKUSA"]);
                    //20250124 LHB UPD S
                    //前年累計 ZKI_JISSEKI
                    // $objActiveSheet->setCellValue(++$column . $LineNO, $value1["ZKI_JISSEKI"] == 0 ? "" : $value1["ZKI_JISSEKI"]);
                    //当年累計 TKI_JISSEKI
                    // $objActiveSheet->setCellValue(++$column . $LineNO, $value1["TKI_JISSEKI"] == 0 ? "" : $value1["TKI_JISSEKI"]);
                    //前年比 ZENNENHI
                    // $objActiveSheet->setCellValue(++$column . $LineNO, $value1["ZENNENHI"] == 0 ? "" : $value1["ZENNENHI"]);
                    //前年実績 ZEN_JISSEKI
                    $objActiveSheet->setCellValue(++$column . $LineNO, $value1["ZEN_JISSEKI"] == 0 ? "" : $value1["ZEN_JISSEKI"]);
                    ++$column;
                    // 前年比
                    $compared = $value1["ZEN_JISSEKI"] == 0 ? "" : ($value1["JISSEKI"] / $value1["ZEN_JISSEKI"]);
                    $objActiveSheet->setCellValue(++$column . $LineNO, $value1["ZEN_JISSEKI"] == 0 ? "" : $compared);
                    //20250124 LHB UPD E

                    // $column = $column + 1;
                }

                //20160612 YIN INS S
                $sheetNo = $sheetNo + 1;
                //$startNo = $startNo + 145;
                //$firstNo = $firstNo + 145;
                $startNo = $startNo + 154;
                $firstNo = $firstNo + 154;
                //20160612 YIN INS E
            }
            //$this -> log("foreach:end");

            $objActiveSheet->setCellValue("A1", $sheetNo);
            $objActiveSheet->setCellValue("A2", $pattern_Nm);

            //20160530 YIN UPD E
            $objPHPExcel->setActiveSheetIndex(1);
            // $objPHPExcel -> removeSheetByIndex(0);
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
            //---20150805 fanzhengzhou upd s.
            //$objWriter -> save($exportFile);
            // $objWriter->setPreCalculateFormulas(false);

            //            $this -> unlink($exportFile);


            $objWriter->save($exportFile);
            //---20150805 fanzhengzhou upd s.
            //---20150804 fanzhengzhou add s. Free up memory... PHPExcel object contains cyclic references,so before unset,you must do disconnectWorksheets.
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
            //20160530 YIN INS S
            unset($objWriter);
            //20160530 YIN INS E
            //---20150804 fanzhengzhou add e.
            $result["result"] = TRUE;
            //20160612 YIN UPD S
            //$result["data"] = "files/KRSS/" . "経営成果管理表_" . $USERID . ".xlsx";
            //$result["data"] = "files/KRSS/" . "経営成果管理表_" . $USERID . ".xlsm";
//            $result["data"] = "files/KRSS/" . "経営成果管理表.xlsm";
            $result["data"] = "files/KRSS/" . "経営成果管理表" . $pattern_Nm . ".xlsm";
            //20160612 YIN UPD E

        } catch (\Exception $ex) {
            $result["result"] == FALSE;
            $result["data"] = $ex->getMessage();
        }
        return $result;
    }

    //一時ファイル削除と生成
    private function fncWKDeal($objFrmBusyoKanriVBA, $busyoCD_From, $busyoCD_To, $NENGTU)
    {
        $this->Session = $this->request->getSession();
        $UPDUSER = $this->Session->read('login_user');
        $UPDCLTNM = $this->request->clientIp();
        $UPDAPP = "frmSimulationAllEdit";

        $wkDealResult = array("result" => FALSE, "data" => "");
        $NENGTU = str_replace("/", "", $NENGTU);
        $flgT = FALSE;

        try {
            $result = $objFrmBusyoKanriVBA->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $objFrmBusyoKanriVBA->Do_transaction();

            //TRUNCATE WK_HKANRIZ_KEIEISEIKA
            $result = $objFrmBusyoKanriVBA->fncWKTRUNCATE();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //２年分のHKANRIZデータを追加
            $result = $objFrmBusyoKanriVBA->fncWKInsert($NENGTU, $UPDAPP, $UPDCLTNM, $UPDUSER);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //20160620 Ins Start
            $result = $objFrmBusyoKanriVBA->fncWKKIKANTRUNCATE();
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }

            $result = $objFrmBusyoKanriVBA->fncWKKIKANINSERT($NENGTU);
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }
            //20160620 Ins End

            //20160915 Ins Start
            $result = $objFrmBusyoKanriVBA->fncWKYOSANKIKANTRUNCATE();
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }

            $result = $objFrmBusyoKanriVBA->fncWKYOSANKIKANINSERT($NENGTU);
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }
            //20160915 Ins End

            //20160620 Del Start
            //delete WK_HKANRIZ_KEIEISEIKA
//            $result = $objFrmBusyoKanriVBA -> fncDeleteKanr($pattern_No, $busyoCD_From, $busyoCD_To, $intProNo, $strUpdUser);
//            if (!$result['result']) {
//                throw new Exception($result['data']);
//            }
//20160620 Del End

            $objFrmBusyoKanriVBA->Do_commit();
            $flgT = TRUE;
            $wkDealResult["result"] = TRUE;

        } catch (\Exception $ex) {
            $wkDealResult["result"] = FALSE;
            $wkDealResult["data"] = $ex->getMessage();
        }
        if ($flgT == FALSE) {
            $objFrmBusyoKanriVBA->Do_rollback();
        }
        $objFrmBusyoKanriVBA->Do_close();
        return $wkDealResult;
    }

    //権限設定取得
    public function fncGetAuthor()
    {
        $result = array();
        try {
            $this->FrmBusyoKanriVBA = new FrmBusyoKanriVBA();
            $this->Session = $this->request->getSession();
            $UPDUSER = $this->Session->read('login_user');
            $result = $this->FrmBusyoKanriVBA->fncGetAuth($UPDUSER);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $ex) {
            $result["data"] = $ex->getMessage();
        }
        $this->fncReturn($result);
    }

}
