<?php
/**
 * 説明：
 *
 *
 * @author yushuangji
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
use App\Model\R4\KRSS\FrmYosanListDownload;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Cake\Log\Log;

class FrmYosanListDownloadController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    private $FrmYosanListDownload;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');

    }
    public function index()
    {
        $this->render('index', 'FrmYosanListDownload_layout');
    }

    public function fncPatternNMSel()
    {
        $result = array("result" => FALSE, "data" => "error");
        $this->FrmYosanListDownload = new FrmYosanListDownload();
        try {
            $result = $this->FrmYosanListDownload->fncPatternNMSel();
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

    public function fncHKEIRICTL()
    {
        $result = array("result" => FALSE, "data" => "error");
        $this->FrmYosanListDownload = new FrmYosanListDownload();
        try {
            $result = $this->FrmYosanListDownload->fncHKEIRICTL();
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

    public function fncGetBusyo()
    {
        $result = array("result" => FALSE, "data" => "error");
        $this->FrmYosanListDownload = new FrmYosanListDownload();
        $this->Session = $this->request->getSession();
        try {
            $result1 = $this->FrmYosanListDownload->fncGetBusyo();
            if ($result1["result"] == FALSE) {
                throw new \Exception($result1["data"], 1);
            }
            $result2 = $this->FrmYosanListDownload->fncGetMaxMinBusyoCD($this->Session->read('login_user'));
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

    public function fncMaxMinBusyo()
    {
        $results = array("result" => FALSE, "data" => "error");
        $this->FrmYosanListDownload = new FrmYosanListDownload();
        $this->Session = $this->request->getSession();
        try {
            $results = $this->FrmYosanListDownload->fncGetMaxMinBusyoCD($this->Session->read('login_user'));
            if ($results["result"] == FALSE) {
                $results['result'] = FALSE;
                throw new \Exception($results["data"], 1);
            }
        } catch (\Exception $ex) {
            $results['data'] = $ex->getMessage();
        }
        $this->fncReturn($results);
    }


    public function fncSelect()
    {

        //$this->log('****fncSelectstart****');


        $result = array("result" => FALSE, "data" => "error", "MsgID" => "E9999");
        $this->FrmYosanListDownload = new FrmYosanListDownload();
        try {
            $intRpt = 0;
            $arrAllBusyoRowData = array();
            $postData = $_POST["data"];
            $onlyBusyo = $postData["onlyBusyo"];
            $KI = $postData["KI"];
            $NENGTU = $postData["cboYM"];
            $busyoCD_From = $postData["busyoCD_From"];
            $busyoCD_To = $postData["busyoCD_To"];
            $jqGridData = array();
            if ($onlyBusyo != "true") {
                $jqGridData = $postData["jqGridRowData"];
            }

            if ($onlyBusyo == "true") {
                $pattern_No = 0;

                //$this->log("****クリア****"  );

                $result = $this->fncWKDeal($this->FrmYosanListDownload, $NENGTU);
                if ($result["result"] == FALSE) {
                    throw new \Exception($result["data"], 1);
                }

                //$this->log("****抽出****"  );


                $result = $this->FrmYosanListDownload->fncSelect($busyoCD_From, $busyoCD_To, $pattern_No, $NENGTU);
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
                    //$this->log("****クリア****"  );

                    $result = $this->fncWKDeal($this->FrmYosanListDownloa, $NENGTU);
                    if ($result["result"] == FALSE) {
                        throw new \Exception($result["data"], 1);
                    }

                    //$this->log("****抽出****"  );
                    $result = $this->FrmYosanListDownload->fncSelect($busyoCD_From, $busyoCD_To, $pattern_No, $NENGTU);

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

            //$this->log("****EXCEL出力****"  );

            $result = $this->fncExcelExport($arrAllBusyoRowData, $KI);
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

    private function fncExcelExport($data, $KI)
    {

        //$this->log('****fncExcelExport start****');


        $result = array("result" => FALSE, "data" => "error");
        //ExcelフィアルのLineNo=1
        $startNo = 9;
        try {
            $this->FrmYosanListDownload = new FrmYosanListDownload();
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
//            $tmpPath2 = "webroot/files/KRSS/";
//            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            $tmpPath = "files/KRSS/";

            $USERID = $this->FrmYosanListDownload->GS_LOGINUSER['strUserID'];
            // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";
            $exportFile = dirname($tmpPath) . "経営成果管理表_" . $USERID . ".xlsx";
            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    throw new \Exception("Execl Error");
                }
            }

            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
            //            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmYosanListDownloadTemplate_2016.xlsx";
//            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmYosanListDownloadTemplate_2020.xlsx";
//            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmYosanhyoTemplate_2020.xlsx";
            $strTemplatePath = $strTemplatePath . "KRSS/FrmYosanhyoTemplate_2020.xlsx";
            if (file_exists($strTemplatePath) == FALSE) {
                throw new \Exception("EXCELテンプレートが見つかりません！");
            }

            //---20150804 fanzhengzhou add s.Reduce the use of memory...
//            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
//            $cacheSettings = array();
//            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            //---20150804 fanzhengzhou add e.

            //$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
//$cacheSettings = array('dir' => '/tmp');
//PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            // $cacheSettings = array();
            // PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            //$cacheSettings = "";
//$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_memcache;
//PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            $objPHPExcel = IOFactory::load($strTemplatePath);
            //            $monthArr1 = array("10" => "O", "11" => "S", "12" => "W", "01" => "AF", "02" => "AJ", "03" => "AN", "04" => "AW", "05" => "BA", "06" => "BE", "07" => "BN", "08" => "BR", "09" => "BV");
//            $monthArr1 = array("10" => "P", "11" => "U", "12" => "Z", "01" => "AK", "02" => "AP", "03" => "AU", "04" => "BF", "05" => "BK", "06" => "BP", "07" => "CA", "08" => "CF", "09" => "CP");
            $monthArr1 = array("10" => "R", "11" => "Y", "12" => "AF", "01" => "AU", "02" => "BB", "03" => "BI", "04" => "BX", "05" => "CE", "06" => "CL", "07" => "DA", "08" => "DH", "09" => "DO");
            //---20150727 fanzhengzhou add s.
            $curMonth = '';
            $lastMonth = '';
            //---20150727 fanzhengzhou add e.

            foreach ($data as $key => $value) {
                $tmpLineArr = array();
                $objCloneWorkSheet = clone $objPHPExcel->getSheetByName("経営成果管理表");
                $BusyoName = $value[0]["BUSYO_NM"];
                $sheetName = $key . "." . $BusyoName;
                $objCloneWorkSheet->setTitle($sheetName, FALSE);
                $objPHPExcel->addSheet($objCloneWorkSheet);
                $objPHPExcel->setActiveSheetIndexByName($sheetName);
                $objActiveSheet = $objPHPExcel->getActiveSheet();
                $objActiveSheet->setCellValue("D3", $KI);
                $objActiveSheet->setCellValue("D5", $key);
                $objActiveSheet->setCellValue("E5", $BusyoName);
                //---20150727 fanzhengzhou add s.
                $resComment = $this->FrmYosanListDownload->selectComment($KI, $key);
                if (count((array) $resComment['data']) > 0) {
                    $objActiveSheet->setCellValue('J3', $resComment['data'][0]['COMMENT_STR']);
                }
                //---20150727 fanzhengzhou add e.
                foreach ($value as $value1) {
                    $LineNO = $startNo + (int) $value1["LINE_NO"];
                    array_push($tmpLineArr, $LineNO);
                    $ym = $value1["NENGETU"];
                    $month = substr($ym, 4, 2);
                    //---20150727 fanzhengzhou add s.  COMMENT_STR を編集する
                    $curMonth = $month;
                    if ($curMonth != $lastMonth) {
                        $objActiveSheet->setCellValue($monthArr1[$curMonth] . '3', $value1["COMMENT_STR"]);
                    }
                    $lastMonth = $curMonth;
                    //---20150727 fanzhengzhou add e.
                    $column = $monthArr1[$month];
                    //計画 KEIKAKU
//                    $objActiveSheet -> setCellValue($column . $LineNO, $value1["KEIKAKU"] == 0 ? "" : $value1["KEIKAKU"]);
                    //$objPHPExcel -> getActiveSheet() -> setCellValue($column . $LineNO, $value1["KEIKAKU"]);

                    // //実績 JISSEKI
                    // $tColumn = "";
                    // for ($a = $column; $a < "ZZZZ"; $a++) {
                    // $a++;
                    // $tColumn = $a;
                    // break;
                    // }
                    // $objPHPExcel -> getActiveSheet() -> setCellValue($tColumn . $LineNO, $value1["JISSEKI"] == 0 ? "" : $value1["JISSEKI"]);
                    //
                    // //計画差 KEIKAKUSA
                    // $tColumn = "";
                    // for ($a = $column; $a < "ZZZZ"; $a++) {
                    // $a++;
                    // $a++;
                    // $tColumn = $a;
                    // break;
                    // }
                    // $objPHPExcel -> getActiveSheet() -> setCellValue($tColumn . $LineNO, $value1["KEIKAKUSA"] == 0 ? "" : $value1["KEIKAKUSA"]);
                    //
                    // //前年比 ZENNENHI
                    // $tColumn = "";
                    // for ($a = $column; $a < "ZZZZ"; $a++) {
                    // $a++;
                    // $a++;
                    // $a++;
                    // $tColumn = $a;
                    // break;
                    // }
                    // $objPHPExcel -> getActiveSheet() -> setCellValue($tColumn . $LineNO, $value1["ZENNENHI"] == 0 ? "" : $value1["ZENNENHI"]);
                    //---fanzhengzhou upd s.

                    if ($LineNO == 31 || $LineNO == 37 || $LineNO == 38 || $LineNO == 45 || $LineNO == 53 || $LineNO == 54 || $LineNO == 63 || $LineNO == 57 || $LineNO == 64 || $LineNO == 76 || $LineNO == 78 || $LineNO == 84 || $LineNO == 85 || $LineNO == 91 || $LineNO == 92 || $LineNO == 95 || $LineNO == 96 || $$LineNO == 102 || $$LineNO == 108 || $$LineNO == 117 || $$LineNO == 118 || $LineNO == 119 || $LineNO == 122 || $LineNO == 123 || $LineNO == 126 || $LineNO == 133 || $LineNO == 134 || $LineNO == 135 || $LineNO == 136 || $LineNO == 146 || $LineNO == 147 || $LineNO == 148 || $LineNO == 149 || $LineNO == 150 || $LineNO == 151 || $LineNO > 156) {
                    } else {
                        //実績 JISSEKI
                        $objActiveSheet->setCellValue(++$column . $LineNO, $value1["JISSEKI"] == 0 ? "" : $value1["JISSEKI"]);
                        //指標 SHIHYO
                        $objActiveSheet->setCellValue(++$column . $LineNO, $value1["SHIHYO"] == 0 ? "" : $value1["SHIHYO"]);
                        //計画差 KEIKAKUSA
                        $objActiveSheet->setCellValue(++$column . $LineNO, $value1["KEIKAKUSA"] == 0 ? "" : $value1["KEIKAKUSA"]);

                        //前年累計 ZKI_JISSEKI
                        $objActiveSheet->setCellValue(++$column . $LineNO, $value1["ZKI_JISSEKI"] == 0 ? "" : $value1["ZKI_JISSEKI"]);
                        //当年累計 TKI_JISSEKI
                        $objActiveSheet->setCellValue(++$column . $LineNO, $value1["TKI_JISSEKI"] == 0 ? "" : $value1["TKI_JISSEKI"]);
                        //前年比 ZENNENHI
                        $objActiveSheet->setCellValue(++$column . $LineNO, $value1["ZENNENHI"] == 0 ? "" : $value1["ZENNENHI"]);
                        //---fanzhengzhou upd e.
                    }

                }

                //                foreach ($tmpLineArr as $key2 => $value2) {
//                    //--- 1月~３月　の　集計---
//                    //計画集計
////                    $KEIKAKU_1 = $objPHPExcel -> getActiveSheet() -> getCell("AF" . $value2) -> getValue();
////                    $KEIKAKU_2 = $objPHPExcel -> getActiveSheet() -> getCell("AJ" . $value2) -> getValue();
////                    $KEIKAKU_3 = $objPHPExcel -> getActiveSheet() -> getCell("AN" . $value2) -> getValue();
//
////                    $KEIKAKU_1 = $objPHPExcel -> getActiveSheet() -> getCell("AK" . $value2) -> getValue();
////                    $KEIKAKU_2 = $objPHPExcel -> getActiveSheet() -> getCell("AP" . $value2) -> getValue();
////                    $KEIKAKU_3 = $objPHPExcel -> getActiveSheet() -> getCell("AU" . $value2) -> getValue();
//
//                    $KEIKAKU_1 = $objPHPExcel -> getActiveSheet() -> getCell("AU" . $value2) -> getValue();
//                    $KEIKAKU_2 = $objPHPExcel -> getActiveSheet() -> getCell("BB" . $value2) -> getValue();
//                    $KEIKAKU_3 = $objPHPExcel -> getActiveSheet() -> getCell("BI" . $value2) -> getValue();
//
//                    if ($KEIKAKU_1 != "" || $KEIKAKU_2 != "" || $KEIKAKU_3 != "") {
//                        $KEIKAKU_1to3_total = (int)$KEIKAKU_1 + (int)$KEIKAKU_2 + (int)$KEIKAKU_3;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AR" . $value2, $KEIKAKU_1to3_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AZ" . $value2, $KEIKAKU_1to3_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("BP" . $value2, $KEIKAKU_1to3_total);
//                    }
//
//                    //実績集計
////                   $JISSEKI_1 = $objPHPExcel -> getActiveSheet() -> getCell("AG" . $value2) -> getValue();
////                    $JISSEKI_2 = $objPHPExcel -> getActiveSheet() -> getCell("AK" . $value2) -> getValue();
////                    $JISSEKI_3 = $objPHPExcel -> getActiveSheet() -> getCell("AO" . $value2) -> getValue();
//
////                    $JISSEKI_1 = $objPHPExcel -> getActiveSheet() -> getCell("AL" . $value2) -> getValue();
////                    $JISSEKI_2 = $objPHPExcel -> getActiveSheet() -> getCell("AQ" . $value2) -> getValue();
////                    $JISSEKI_3 = $objPHPExcel -> getActiveSheet() -> getCell("AV" . $value2) -> getValue();
//
//                    $JISSEKI_1 = $objPHPExcel -> getActiveSheet() -> getCell("AV" . $value2) -> getValue();
//                    $JISSEKI_2 = $objPHPExcel -> getActiveSheet() -> getCell("BC" . $value2) -> getValue();
//                    $JISSEKI_3 = $objPHPExcel -> getActiveSheet() -> getCell("BJ" . $value2) -> getValue();
//
//                    if ($JISSEKI_1 != "" || $JISSEKI_2 != "" || $JISSEKI_3 != "") {
//                        $JISSEKI_1to3_total = (int)$JISSEKI_1 + (int)$JISSEKI_2 + (int)$JISSEKI_3;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AS" . $value2, $JISSEKI_1to3_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BA" . $value2, $JISSEKI_1to3_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("BQ" . $value2, $JISSEKI_1to3_total);
//                    }
//
//                    //計画差 集計
////                    $KEIKAKUSA_1 = $objPHPExcel -> getActiveSheet() -> getCell("AH" . $value2) -> getValue();
////                    $KEIKAKUSA_2 = $objPHPExcel -> getActiveSheet() -> getCell("AL" . $value2) -> getValue();
////                    $KEIKAKUSA_3 = $objPHPExcel -> getActiveSheet() -> getCell("AP" . $value2) -> getValue();
//
////                    $KEIKAKUSA_1 = $objPHPExcel -> getActiveSheet() -> getCell("AN" . $value2) -> getValue();
////                    $KEIKAKUSA_2 = $objPHPExcel -> getActiveSheet() -> getCell("AS" . $value2) -> getValue();
////                    $KEIKAKUSA_3 = $objPHPExcel -> getActiveSheet() -> getCell("AX" . $value2) -> getValue();
//
//                    $KEIKAKUSA_1 = $objPHPExcel -> getActiveSheet() -> getCell("AX" . $value2) -> getValue();
//                    $KEIKAKUSA_2 = $objPHPExcel -> getActiveSheet() -> getCell("BE" . $value2) -> getValue();
//                    $KEIKAKUSA_3 = $objPHPExcel -> getActiveSheet() -> getCell("BL" . $value2) -> getValue();
//
//                    if ($KEIKAKUSA_1 != "" || $KEIKAKUSA_2 != "" || $KEIKAKUSA_3 != "") {
//                        $KEIKAKUSA_1to3_total = (int)$KEIKAKUSA_1 + (int)$KEIKAKUSA_2 + (int)$KEIKAKUSA_3;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AT" . $value2, $KEIKAKUSA_1to3_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BC" . $value2, $KEIKAKUSA_1to3_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("BS" . $value2, $KEIKAKUSA_1to3_total);
//                    }
//
//                    //前年累積
//                    $Z_RUIKEI_2Q = $objPHPExcel -> getActiveSheet() -> getCell("BM" . $value2) -> getValue();
//                    if (Z_RUIKEI_2Q != "") {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("BT" . $value2, $Z_RUIKEI_2Q );
//                    }
//                    //当年累積
//                    $T_RUIKEI_2Q = $objPHPExcel -> getActiveSheet() -> getCell("BN" . $value2) -> getValue();
//                    if (T_RUIKEI_2Q != "") {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("BU" . $value2, $T_RUIKEI_2Q );
//                    }
//
//                    //前年比集計
////                    $ZENNENHI_1 = $objPHPExcel -> getActiveSheet() -> getCell("AI" . $value2) -> getValue();
////                    $ZENNENHI_2 = $objPHPExcel -> getActiveSheet() -> getCell("AM" . $value2) -> getValue();
// //                   $ZENNENHI_3 = $objPHPExcel -> getActiveSheet() -> getCell("AQ" . $value2) -> getValue();
//
////                    $ZENNENHI_1 = $objPHPExcel -> getActiveSheet() -> getCell("AO" . $value2) -> getValue();
////                    $ZENNENHI_2 = $objPHPExcel -> getActiveSheet() -> getCell("AT" . $value2) -> getValue();
////                    $ZENNENHI_3 = $objPHPExcel -> getActiveSheet() -> getCell("AY" . $value2) -> getValue();
////                    if ($ZENNENHI_1 != "" || $ZENNENHI_2 != "" || $ZENNENHI_3 != "") {
////                        $ZENNENHI_1to3_total = (int)$ZENNENHI_1 + (int)$ZENNENHI_2 + (int)$ZENNENHI_3;
//////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AU" . $value2, $ZENNENHI_1to3_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BD" . $value2, $ZENNENHI_1to3_total);
////                    }
//
//                    //前年比
//                    if ($Z_RUIKEI_2Q != "" && (int)$Z_RUIKEI_2Q != 0 ) {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("BU" . $value2, (int)$T_RUIKEI_2Q / (int)$Z_RUIKEI_2Q );
//	    }
//
//
//
//                    //--- 4月~6月　の　集計---
//                    //計画集計
////                    $KEIKAKU_4 = $objPHPExcel -> getActiveSheet() -> getCell("AW" . $value2) -> getValue();
////                    $KEIKAKU_5 = $objPHPExcel -> getActiveSheet() -> getCell("BA" . $value2) -> getValue();
////                    $KEIKAKU_6 = $objPHPExcel -> getActiveSheet() -> getCell("BE" . $value2) -> getValue();
//
////                    $KEIKAKU_4 = $objPHPExcel -> getActiveSheet() -> getCell("BF" . $value2) -> getValue();
////                    $KEIKAKU_5 = $objPHPExcel -> getActiveSheet() -> getCell("BK" . $value2) -> getValue();
////                    $KEIKAKU_6 = $objPHPExcel -> getActiveSheet() -> getCell("BP" . $value2) -> getValue();
//
//                    $KEIKAKU_4 = $objPHPExcel -> getActiveSheet() -> getCell("BX" . $value2) -> getValue();
//                    $KEIKAKU_5 = $objPHPExcel -> getActiveSheet() -> getCell("CE" . $value2) -> getValue();
//                    $KEIKAKU_6 = $objPHPExcel -> getActiveSheet() -> getCell("CL" . $value2) -> getValue();
//
//                    if ($KEIKAKU_4 != "" || $KEIKAKU_5 != "" || $KEIKAKU_6 != "") {
//                        $KEIKAKU_4to6_total = (int)$KEIKAKU_4 + (int)$KEIKAKU_5 + (int)$KEIKAKU_6;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BI" . $value2, $KEIKAKU_4to6_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BU" . $value2, $KEIKAKU_4to6_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("CS" . $value2, $KEIKAKU_4to6_total);
//                    }
//
//                    //実績集計
////                    $JISSEKI_4 = $objPHPExcel -> getActiveSheet() -> getCell("AX" . $value2) -> getValue();
////                    $JISSEKI_5 = $objPHPExcel -> getActiveSheet() -> getCell("BB" . $value2) -> getValue();
////                    $JISSEKI_6 = $objPHPExcel -> getActiveSheet() -> getCell("BP" . $value2) -> getValue();
//
////                    $JISSEKI_4 = $objPHPExcel -> getActiveSheet() -> getCell("BG" . $value2) -> getValue();
////                    $JISSEKI_5 = $objPHPExcel -> getActiveSheet() -> getCell("BL" . $value2) -> getValue();
////                    $JISSEKI_6 = $objPHPExcel -> getActiveSheet() -> getCell("BQ" . $value2) -> getValue();
//
//                    $JISSEKI_4 = $objPHPExcel -> getActiveSheet() -> getCell("BY" . $value2) -> getValue();
//                    $JISSEKI_5 = $objPHPExcel -> getActiveSheet() -> getCell("CF" . $value2) -> getValue();
//                    $JISSEKI_6 = $objPHPExcel -> getActiveSheet() -> getCell("CM" . $value2) -> getValue();
//
//                    if ($JISSEKI_4 != "" || $JISSEKI_5 != "" || $JISSEKI_6 != "") {
//                        $JISSEKI_4to6_total = (int)$JISSEKI_4 + (int)$JISSEKI_5 + (int)$JISSEKI_6;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BJ" . $value2, $JISSEKI_4to6_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BV" . $value2, $JISSEKI_4to6_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("CT" . $value2, $JISSEKI_4to6_total);
//                    }
//
//                    //計画差 集計
////                    $KEIKAKUSA_4 = $objPHPExcel -> getActiveSheet() -> getCell("AY" . $value2) -> getValue();
////                   $KEIKAKUSA_5 = $objPHPExcel -> getActiveSheet() -> getCell("BC" . $value2) -> getValue();
////                    $KEIKAKUSA_6 = $objPHPExcel -> getActiveSheet() -> getCell("BG" . $value2) -> getValue();
//
////                    $KEIKAKUSA_4 = $objPHPExcel -> getActiveSheet() -> getCell("BI" . $value2) -> getValue();
////                    $KEIKAKUSA_5 = $objPHPExcel -> getActiveSheet() -> getCell("BN" . $value2) -> getValue();
////                    $KEIKAKUSA_6 = $objPHPExcel -> getActiveSheet() -> getCell("BS" . $value2) -> getValue();
//
//                    $KEIKAKUSA_4 = $objPHPExcel -> getActiveSheet() -> getCell("CA" . $value2) -> getValue();
//                    $KEIKAKUSA_5 = $objPHPExcel -> getActiveSheet() -> getCell("CH" . $value2) -> getValue();
//                    $KEIKAKUSA_6 = $objPHPExcel -> getActiveSheet() -> getCell("CO" . $value2) -> getValue();
//
//                    if ($KEIKAKUSA_4 != "" || $KEIKAKUSA_5 != "" || $KEIKAKUSA_6 != "") {
//                        $KEIKAKUSA_4to6_total = (int)$KEIKAKUSA_4 + (int)$KEIKAKUSA_5 + (int)$KEIKAKUSA_6;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BK" . $value2, $KEIKAKUSA_4to6_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BX" . $value2, $KEIKAKUSA_4to6_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("CV" . $value2, $KEIKAKUSA_4to6_total);
//                    }
//
//                 //前年累積
//                    $Z_RUIKEI_3Q = $objPHPExcel -> getActiveSheet() -> getCell("CP" . $value2) -> getValue();
//                    if (Z_RUIKEI_3Q != "") {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("CW" . $value2, $Z_RUIKEI_3Q );
//                    }
//                    //当年累積
//                    $T_RUIKEI_3Q = $objPHPExcel -> getActiveSheet() -> getCell("CQ" . $value2) -> getValue();
//                    if (T_RUIKEI_3Q != "") {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("CX" . $value2, $T_RUIKEI_3Q );
//                    }
//
//                    //前年比集計
//////                    $ZENNENHI_4 = $objPHPExcel -> getActiveSheet() -> getCell("AZ" . $value2) -> getValue();
//////                    $ZENNENHI_5 = $objPHPExcel -> getActiveSheet() -> getCell("BD" . $value2) -> getValue();
//////                    $ZENNENHI_6 = $objPHPExcel -> getActiveSheet() -> getCell("BH" . $value2) -> getValue();
////                    $ZENNENHI_4 = $objPHPExcel -> getActiveSheet() -> getCell("BJ" . $value2) -> getValue();
////                    $ZENNENHI_5 = $objPHPExcel -> getActiveSheet() -> getCell("BO" . $value2) -> getValue();
////                    $ZENNENHI_6 = $objPHPExcel -> getActiveSheet() -> getCell("BT" . $value2) -> getValue();
////                    if ($ZENNENHI_4 != "" || $ZENNENHI_5 != "" || $ZENNENHI_6 != "") {
////                        $ZENNENHI_4to6_total = (int)$ZENNENHI_4 + (int)$ZENNENHI_5 + (int)$ZENNENHI_6;
//////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BL" . $value2, $ZENNENHI_4to6_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BY" . $value2, $ZENNENHI_4to6_total);
////                    }
//
//
//                    //前年比
//                    if ($Z_RUIKEI_3Q != "" && (int)$Z_RUIKEI_3Q  != 0 ) {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("CY" . $value2, (int)$T_RUIKEI_3Q / (int)$Z_RUIKEI_3Q );
//	    }
//
//
//
//                    //--- 7月~9月　の　集計---
//                    //計画集計
////                    $KEIKAKU_7 = $objPHPExcel -> getActiveSheet() -> getCell("BN" . $value2) -> getValue();
////                    $KEIKAKU_8 = $objPHPExcel -> getActiveSheet() -> getCell("BR" . $value2) -> getValue();
////                    $KEIKAKU_9 = $objPHPExcel -> getActiveSheet() -> getCell("BV" . $value2) -> getValue();
//
////                    $KEIKAKU_7 = $objPHPExcel -> getActiveSheet() -> getCell("CA" . $value2) -> getValue();
////                    $KEIKAKU_8 = $objPHPExcel -> getActiveSheet() -> getCell("CF" . $value2) -> getValue();
////                    $KEIKAKU_9 = $objPHPExcel -> getActiveSheet() -> getCell("CK" . $value2) -> getValue();
//
//                    $KEIKAKU_7 = $objPHPExcel -> getActiveSheet() -> getCell("DA" . $value2) -> getValue();
//                    $KEIKAKU_8 = $objPHPExcel -> getActiveSheet() -> getCell("DH" . $value2) -> getValue();
//                    $KEIKAKU_9 = $objPHPExcel -> getActiveSheet() -> getCell("DO" . $value2) -> getValue();
//
//                    if ($KEIKAKU_7 != "" || $KEIKAKU_8 != "" || $KEIKAKU_9 != "") {
//                        $KEIKAKU_7to9_total = (int)$KEIKAKU_7 + (int)$KEIKAKU_8 + (int)$KEIKAKU_9;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("BZ" . $value2, $KEIKAKU_7to9_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("CP" . $value2, $KEIKAKU_7to9_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("DV" . $value2, $KEIKAKU_7to9_total);
//                    }
//
//                    //実績集計
////                    $JISSEKI_7 = $objPHPExcel -> getActiveSheet() -> getCell("BO" . $value2) -> getValue();
////                    $JISSEKI_8 = $objPHPExcel -> getActiveSheet() -> getCell("BS" . $value2) -> getValue();
////                    $JISSEKI_9 = $objPHPExcel -> getActiveSheet() -> getCell("BW" . $value2) -> getValue();
//
////                    $JISSEKI_7 = $objPHPExcel -> getActiveSheet() -> getCell("CB" . $value2) -> getValue();
////                    $JISSEKI_8 = $objPHPExcel -> getActiveSheet() -> getCell("CG" . $value2) -> getValue();
////                    $JISSEKI_9 = $objPHPExcel -> getActiveSheet() -> getCell("CL" . $value2) -> getValue();
//
//                    $JISSEKI_7 = $objPHPExcel -> getActiveSheet() -> getCell("DB" . $value2) -> getValue();
//                    $JISSEKI_8 = $objPHPExcel -> getActiveSheet() -> getCell("DI" . $value2) -> getValue();
//                    $JISSEKI_9 = $objPHPExcel -> getActiveSheet() -> getCell("DP" . $value2) -> getValue();
//
//
//                    if ($JISSEKI_7 != "" || $JISSEKI_8 != "" || $JISSEKI_9 != "") {
//                        $JISSEKI_7to9_total = (int)$JISSEKI_7 + (int)$JISSEKI_8 + (int)$JISSEKI_9;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("CA" . $value2, $JISSEKI_7to9_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("CQ" . $value2, $JISSEKI_7to9_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("DW" . $value2, $JISSEKI_7to9_total);
//                    }
//
//                    //計画差 集計
////                    $KEIKAKUSA_7 = $objPHPExcel -> getActiveSheet() -> getCell("BP" . $value2) -> getValue();
////                    $KEIKAKUSA_8 = $objPHPExcel -> getActiveSheet() -> getCell("BT" . $value2) -> getValue();
////                    $KEIKAKUSA_9 = $objPHPExcel -> getActiveSheet() -> getCell("BX" . $value2) -> getValue();
//
////                    $KEIKAKUSA_7 = $objPHPExcel -> getActiveSheet() -> getCell("CD" . $value2) -> getValue();
////                    $KEIKAKUSA_8 = $objPHPExcel -> getActiveSheet() -> getCell("CI" . $value2) -> getValue();
////                    $KEIKAKUSA_9 = $objPHPExcel -> getActiveSheet() -> getCell("CN" . $value2) -> getValue();
//
//                    $KEIKAKUSA_7 = $objPHPExcel -> getActiveSheet() -> getCell("DD" . $value2) -> getValue();
//                    $KEIKAKUSA_8 = $objPHPExcel -> getActiveSheet() -> getCell("DK" . $value2) -> getValue();
//                    $KEIKAKUSA_9 = $objPHPExcel -> getActiveSheet() -> getCell("DR" . $value2) -> getValue();
//
//                    if ($KEIKAKUSA_7 != "" || $KEIKAKUSA_8 != "" || $KEIKAKUSA_9 != "") {
//                        $KEIKAKUSA_7to9_total = (int)$KEIKAKUSA_7 + (int)$KEIKAKUSA_8 + (int)$KEIKAKUSA_9;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("CB" . $value2, $KEIKAKUSA_7to9_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("CS" . $value2, $KEIKAKUSA_7to9_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("DY" . $value2, $KEIKAKUSA_7to9_total);
//                    }
//
//
//
//                 //前年累積
//                    $Z_RUIKEI_4Q = $objPHPExcel -> getActiveSheet() -> getCell("DS" . $value2) -> getValue();
//                    if (Z_RUIKEI_4Q != "") {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("DZ" . $value2, $Z_RUIKEI_4Q );
//                    }
//                    //当年累積
//                    $T_RUIKEI_4Q = $objPHPExcel -> getActiveSheet() -> getCell("DT" . $value2) -> getValue();
//                    if (T_RUIKEI_4Q != "") {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("EA" . $value2, $T_RUIKEI_4Q );
//                    }
//
//
//                    //前年比集計
//////                    $ZENNENHI_7 = $objPHPExcel -> getActiveSheet() -> getCell("BQ" . $value2) -> getValue();
//////                    $ZENNENHI_8 = $objPHPExcel -> getActiveSheet() -> getCell("BU" . $value2) -> getValue();
//////                    $ZENNENHI_9 = $objPHPExcel -> getActiveSheet() -> getCell("BY" . $value2) -> getValue();
////                    $ZENNENHI_7 = $objPHPExcel -> getActiveSheet() -> getCell("CE" . $value2) -> getValue();
////                    $ZENNENHI_8 = $objPHPExcel -> getActiveSheet() -> getCell("CJ" . $value2) -> getValue();
////                    $ZENNENHI_9 = $objPHPExcel -> getActiveSheet() -> getCell("CO" . $value2) -> getValue();
////                    if ($ZENNENHI_7 != "" || $ZENNENHI_8 != "" || $ZENNENHI_9 != "") {
////                        $ZENNENHI_7to9_total = (int)$ZENNENHI_7 + (int)$ZENNENHI_8 + (int)$ZENNENHI_9;
//////                        $objPHPExcel -> getActiveSheet() -> setCellValue("CC" . $value2, $ZENNENHI_7to9_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("CT" . $value2, $ZENNENHI_7to9_total);
////                    }
//
//                    //前年比
//                    if ($Z_RUIKEI_4Q != "" && (int)$Z_RUIKEI_4Q  != 0 ) {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("EB" . $value2, (int)$T_RUIKEI_4Q / (int)$Z_RUIKEI_4Q );
//	    }
//
//
//
//                    //--- 10月~12月　の　集計---
//                    //計画集計
////                    $KEIKAKU_10 = $objPHPExcel -> getActiveSheet() -> getCell("O" . $value2) -> getValue();
////                    $KEIKAKU_11 = $objPHPExcel -> getActiveSheet() -> getCell("S" . $value2) -> getValue();
////                    $KEIKAKU_12 = $objPHPExcel -> getActiveSheet() -> getCell("W" . $value2) -> getValue();
//
////                    $KEIKAKU_10 = $objPHPExcel -> getActiveSheet() -> getCell("P" . $value2) -> getValue();
////                    $KEIKAKU_11 = $objPHPExcel -> getActiveSheet() -> getCell("U" . $value2) -> getValue();
////                    $KEIKAKU_12 = $objPHPExcel -> getActiveSheet() -> getCell("Z" . $value2) -> getValue();
//
//                    $KEIKAKU_10 = $objPHPExcel -> getActiveSheet() -> getCell("R" . $value2) -> getValue();
//                    $KEIKAKU_11 = $objPHPExcel -> getActiveSheet() -> getCell("Y" . $value2) -> getValue();
//                    $KEIKAKU_12 = $objPHPExcel -> getActiveSheet() -> getCell("AF" . $value2) -> getValue();
//
//                    if ($KEIKAKU_10 != "" || $KEIKAKU_11 != "" || $KEIKAKU_12 != "") {
//                        $KEIKAKU_10to12_total = (int)$KEIKAKU_10 + (int)$KEIKAKU_11 + (int)$KEIKAKU_12;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AA" . $value2, $KEIKAKU_10to12_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AE" . $value2, $KEIKAKU_10to12_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("AM" . $value2, $KEIKAKU_10to12_total);
//                    }
//
//                    //実績集計
////                    $JISSEKI_10 = $objPHPExcel -> getActiveSheet() -> getCell("P" . $value2) -> getValue();
////                    $JISSEKI_11 = $objPHPExcel -> getActiveSheet() -> getCell("T" . $value2) -> getValue();
////                    $JISSEKI_12 = $objPHPExcel -> getActiveSheet() -> getCell("X" . $value2) -> getValue();
//
////                    $JISSEKI_10 = $objPHPExcel -> getActiveSheet() -> getCell("Q" . $value2) -> getValue();
////                    $JISSEKI_11 = $objPHPExcel -> getActiveSheet() -> getCell("V" . $value2) -> getValue();
////                    $JISSEKI_12 = $objPHPExcel -> getActiveSheet() -> getCell("AA" . $value2) -> getValue();
//
//                    $JISSEKI_10 = $objPHPExcel -> getActiveSheet() -> getCell("S" . $value2) -> getValue();
//                    $JISSEKI_11 = $objPHPExcel -> getActiveSheet() -> getCell("Z" . $value2) -> getValue();
//                    $JISSEKI_12 = $objPHPExcel -> getActiveSheet() -> getCell("AG" . $value2) -> getValue();
//
//                    if ($JISSEKI_10 != "" || $JISSEKI_11 != "" || $JISSEKI_12 != "") {
//                        $JISSEKI_10to12_total = (int)$JISSEKI_10 + (int)$JISSEKI_11 + (int)$JISSEKI_12;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AB" . $value2, $JISSEKI_10to12_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AF" . $value2, $JISSEKI_10to12_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("AN" . $value2, $JISSEKI_10to12_total);
//                    }
//
//                    //計画差 集計
////                    $KEIKAKUSA_10 = $objPHPExcel -> getActiveSheet() -> getCell("Q" . $value2) -> getValue();
////                    $KEIKAKUSA_11 = $objPHPExcel -> getActiveSheet() -> getCell("U" . $value2) -> getValue();
////                    $KEIKAKUSA_12 = $objPHPExcel -> getActiveSheet() -> getCell("Y" . $value2) -> getValue();
//
////                    $KEIKAKUSA_10 = $objPHPExcel -> getActiveSheet() -> getCell("S" . $value2) -> getValue();
////                    $KEIKAKUSA_11 = $objPHPExcel -> getActiveSheet() -> getCell("X" . $value2) -> getValue();
////                    $KEIKAKUSA_12 = $objPHPExcel -> getActiveSheet() -> getCell("AC" . $value2) -> getValue();
//
//                    $KEIKAKUSA_10 = $objPHPExcel -> getActiveSheet() -> getCell("U" . $value2) -> getValue();
//                    $KEIKAKUSA_11 = $objPHPExcel -> getActiveSheet() -> getCell("AB" . $value2) -> getValue();
//                    $KEIKAKUSA_12 = $objPHPExcel -> getActiveSheet() -> getCell("AI" . $value2) -> getValue();
//
//                    if ($KEIKAKUSA_10 != "" || $KEIKAKUSA_11 != "" || $KEIKAKUSA_12 != "") {
//                        $KEIKAKUSA_10to12_total = (int)$KEIKAKUSA_10 + (int)$KEIKAKUSA_11 + (int)$KEIKAKUSA_12;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AC" . $value2, $KEIKAKUSA_10to12_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AH" . $value2, $KEIKAKUSA_10to12_total);
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("AP" . $value2, $KEIKAKUSA_10to12_total);
//                    }
//
//
//                 //前年累積
//                    $Z_RUIKEI_1Q = $objPHPExcel -> getActiveSheet() -> getCell("AJ" . $value2) -> getValue();
//                    if (Z_RUIKEI_1Q != "") {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("AQ" . $value2, $Z_RUIKEI_1Q );
//                    }
//                    //当年累積
//                    $T_RUIKEI_1Q = $objPHPExcel -> getActiveSheet() -> getCell("AK" . $value2) -> getValue();
//                    if (T_RUIKEI_1Q != "") {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("AR" . $value2, $T_RUIKEI_1Q );
//                    }
//
//                    //前年比集計
//////                    $ZENNENHI_10 = $objPHPExcel -> getActiveSheet() -> getCell("R" . $value2) -> getValue();
//////                    $ZENNENHI_11 = $objPHPExcel -> getActiveSheet() -> getCell("V" . $value2) -> getValue();
//////                   $ZENNENHI_12 = $objPHPExcel -> getActiveSheet() -> getCell("Z" . $value2) -> getValue();
////                    $ZENNENHI_10 = $objPHPExcel -> getActiveSheet() -> getCell("T" . $value2) -> getValue();
////                    $ZENNENHI_11 = $objPHPExcel -> getActiveSheet() -> getCell("Y" . $value2) -> getValue();
////                    $ZENNENHI_12 = $objPHPExcel -> getActiveSheet() -> getCell("AD" . $value2) -> getValue();
////                    if ($ZENNENHI_10 != "" || $ZENNENHI_11 != "" || $ZENNENHI_12 != "") {
////                        $ZENNENHI_10to12_total = (int)$ZENNENHI_10 + (int)$ZENNENHI_11 + (int)$ZENNENHI_12;
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AD" . $value2, $ZENNENHI_10to12_total);
////                        $objPHPExcel -> getActiveSheet() -> setCellValue("AI" . $value2, $ZENNENHI_10to12_total);
////                    }
//
//                    //前年比
//                    if ($Z_RUIKEI_1Q != "" && (int)$Z_RUIKEI_1Q  != 0 ) {
//                        $objPHPExcel -> getActiveSheet() -> setCellValue("AS" . $value2, (int)$T_RUIKEI_1Q / (int)$Z_RUIKEI_1Q );
//	    }
//
//
//                }

            }

            //$this->log("****後処理****"  );
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->removeSheetByIndex(0);

            //$this->log("****保存****"  );
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
            //---20150805 fanzhengzhou upd s.
            //$objWriter -> save($exportFile);
            $objWriter->save($exportFile);
            //---20150805 fanzhengzhou upd s.
            //---20150804 fanzhengzhou add s. Free up memory... PHPExcel object contains cyclic references,so before unset,you must do disconnectWorksheets.
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
            //---20150804 fanzhengzhou add e.

            $result["result"] = TRUE;
            $result["data"] = "files/KRSS/" . "経営成果管理表_" . $USERID . ".xlsx";
            //$this->log("****出力完了****"  );

        } catch (\Exception $ex) {
            $result["result"] == FALSE;
            $result["data"] = $ex->getMessage();
        }
        return $result;
    }

    private function fncWKDeal($objFrmYosanListDownload, $NENGTU)
    {
        $this->Session = $this->request->getSession();
        $UPDUSER = $this->Session->read('login_user');
        $UPDCLTNM = $this->request->clientIp();
        $UPDAPP = "frmSimulationAllEdit";

        $wkDealResult = array("result" => FALSE, "data" => "");
        $NENGTU = str_replace("/", "", $NENGTU);
        $flgT = FALSE;

        try {
            $result = $objFrmYosanListDownload->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $objFrmYosanListDownload->Do_transaction();

            //TRUNCATE WK_HKANRIZ_KEIEISEIKA
            $result = $objFrmYosanListDownload->fncWKTRUNCATE();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //２年分のHKANRIZデータを追加
            $result = $objFrmYosanListDownload->fncWKInsert($NENGTU, $UPDAPP, $UPDCLTNM, $UPDUSER);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //20160620 Ins Start
            $result = $objFrmYosanListDownload->fncWKKIKANTRUNCATE();
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }

            $result = $objFrmYosanListDownload->fncWKKIKANINSERT($NENGTU);
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }
            //20160620 Ins End

            //20160914 Ins Start
            $result = $objFrmYosanListDownload->fncWKYOSANKIKANTRUNCATE();
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }

            $result = $objFrmYosanListDownload->fncWKYOSANKIKANINSERT($NENGTU);
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }
            //20160914 Ins End

            //delete WK_HKANRIZ_KEIEISEIKA
            // $result = $objFrmYosanListDownload -> fncDeleteKanr($pattern_No, $busyoCD_From, $busyoCD_To, $intProNo, $strUpdUser);
            //if (!$result['result']) {
            //    throw new \Exception($result['data']);
            // }
            $objFrmYosanListDownload->Do_commit();
            $flgT = TRUE;
            $wkDealResult["result"] = TRUE;

        } catch (\Exception $ex) {
            $wkDealResult["result"] = FALSE;
            $wkDealResult["data"] = $ex->getMessage();
        }
        if ($flgT == FALSE) {
            $objFrmYosanListDownload->Do_rollback();
        }
        $objFrmYosanListDownload->Do_close();
        return $wkDealResult;
    }

    public function fncGetAuthor()
    {
        $result = array();
        $this->Session = $this->request->getSession();
        try {
            $this->FrmYosanListDownload = new FrmYosanListDownload();
            $UPDUSER = $this->Session->read('login_user');
            $result = $this->FrmYosanListDownload->fncGetAuth($UPDUSER);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $ex) {
            $result["data"] = $ex->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncSelectYosan()
    {

        //        $this->log('****fncSelectYosan start****');
        $result = array("result" => FALSE, "data" => "error", "MsgID" => "E9999");
        $this->FrmYosanListDownload = new FrmYosanListDownload();
        try {
            $intRpt = 0;
            $arrAllBusyoRowData = array();
            $postData = $_POST["data"];
            $onlyBusyo = $postData["onlyBusyo"];
            $KI = $postData["KI"];
            $NENGTU = $postData["cboYM"];
            $busyoCD_From = $postData["busyoCD_From"];
            $busyoCD_To = $postData["busyoCD_To"];
            $jqGridData = array();
            if ($onlyBusyo != "true") {
                $jqGridData = $postData["jqGridRowData"];
            }

            if ($onlyBusyo == "true") {
                $pattern_No = 0;

                //	$this->log("****クリア****"  );

                $result = $this->fncWKDeal($this->FrmYosanListDownload, $NENGTU);
                if ($result["result"] == FALSE) {
                    throw new \Exception($result["data"], 1);
                }

                //	$this->log("****抽出****"  );
                $result = $this->FrmYosanListDownload->fncSelectYosan($KI, $busyoCD_From, $busyoCD_To, $pattern_No);
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
                    //	$this->log("****クリア****"  );

                    $result = $this->fncWKDeal($this->FrmYosanListDownload, $NENGTU);
                    if ($result["result"] == FALSE) {
                        throw new \Exception($result["data"], 1);
                    }

                    //                    $this->log("****抽出****"  );
                    $result = $this->FrmYosanListDownload->fncSelectYosan($KI, $busyoCD_From, $busyoCD_To, $pattern_No);

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

            //            $this->log("****EXCEL出力****"  );
            $result = $this->fncExcelExportYosan($arrAllBusyoRowData, $KI);
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


    private function fncExcelExportYosan($data, $KI)
    {

        $result = array("result" => FALSE, "data" => "error");
        //ExcelフィアルのLineNo=1
        $startNo = 9;
        try {
            $this->FrmYosanListDownload = new FrmYosanListDownload();
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/KRSS/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
            $USERID = $this->FrmYosanListDownload->GS_LOGINUSER['strUserID'];
            // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";
            $exportFile = $tmpPath . "予算表_" . $USERID . ".xlsx";
            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    throw new \Exception("Execl Error");
                }
            }
            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
            //            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmYosanhyoTemplate.xlsx";
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmYosanhyoTemplate_2020.xlsx";
            if (file_exists($strTemplatePath) == FALSE) {
                Log::error($strTemplatePath);
                throw new \Exception("EXCELテンプレートが見つかりません！");
            }
            // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
            // PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            $objPHPExcel = $objPHPExcel = IOFactory::load($strTemplatePath);

            //            $monthArr1 = array("10" => "Q", "11" => "W", "12" => "AC", "01" => "AP", "02" => "AV", "03" => "BB", "04" => "BO", "05" => "BU", "06" => "CA", "07" => "CN", "08" => "CT", "09" => "CZ");
            $monthArr1 = array("10" => "S", "11" => "Y", "12" => "AE", "01" => "AR", "02" => "AX", "03" => "BD", "04" => "BQ", "05" => "BW", "06" => "CC", "07" => "CP", "08" => "CV", "09" => "DB");

            foreach ($data as $key => $value) {
                $tmpLineArr = array();
                $BusyoName = $value[0]["BUSYO_NM"];
                $BusyoCd = $value[0]["BUSYO_CD"];
                $sheetName = "";

                // 	if ( substr($BusyoCd,2,1) == "0" ) {
//	if ( substr($BusyoCd,2,1) == "9" || substr($BusyoCd,2,1) == "3" || substr($BusyoCd,2,1) == "6" || substr($BusyoCd,2,1) == "8") {
                if (substr($BusyoCd, 2, 1) == "3" || substr($BusyoCd, 2, 1) == "6" || substr($BusyoCd, 2, 1) == "8") {
                    continue;

                } else {

                    $objCloneWorkSheet = clone $objPHPExcel->getSheetByName("予算表");

                    $sheetName = $key . "." . $BusyoName;
                    $objCloneWorkSheet->setTitle($sheetName, FALSE);
                    $objPHPExcel->addSheet($objCloneWorkSheet);
                }

                $objPHPExcel->setActiveSheetIndexByName($sheetName);

                $objActiveSheet = $objPHPExcel->getActiveSheet();

                $objActiveSheet->setCellValue("D3", $KI);
                $objActiveSheet->setCellValue("D5", $key);
                $objActiveSheet->setCellValue("E5", $BusyoName);

                foreach ($value as $value1) {
                    $LineNO = $startNo + (int) $value1["LINE_NO"];
                    array_push($tmpLineArr, $LineNO);
                    $ym = $value1["NENGETU"];
                    $month = substr($ym, 4, 2);

                    $column = $monthArr1[$month];

                    //前年実績
//テンプレートに計算式を埋め込んでいる行は編集しない
//		if ( $LineNO == 31 || $LineNO == 37 || $LineNO == 38 || $LineNO == 45 ||  $LineNO == 53 ||$LineNO == 54 || $LineNO == 63 || $LineNO == 57 || $LineNO == 64 || $LineNO == 76 || $LineNO == 78 || $LineNO == 84 || $LineNO == 85 || $LineNO == 91 || $LineNO == 92 || $LineNO == 95 || $LineNO == 96 || $$LineNO == 102 || $$LineNO == 108 || $$LineNO == 117 || $$LineNO == 118 || $LineNO == 119 || $LineNO == 122 || $LineNO == 123 || $LineNO == 126 || $LineNO == 133 || $LineNO == 134 || $LineNO == 135 || $LineNO == 136  || $LineNO == 146  || $LineNO == 147 || $LineNO == 148 || $LineNO == 149 || $LineNO == 150 || $LineNO == 151 || $LineNO > 156) {
                    if ($LineNO == 126 || $LineNO == 133 || $LineNO == 134 || $LineNO == 135 || $LineNO == 136 || $LineNO == 146 || $LineNO == 147 || $LineNO == 148 || $LineNO == 149 || $LineNO == 150 || $LineNO == 151 || $LineNO > 156) {
                    } else {
                        //計画 KEIKAKU
//			$objActiveSheet -> setCellValue($column . $LineNO, $value1["KEIKAKU"] == 0 ? "" : $value1["KEIKAKU"]);
                        //前年実績 JISSEKI
                        $objActiveSheet->setCellValue($column . $LineNO, $value1["JISSEKI"] == 0 ? 0 : $value1["JISSEKI"]);
                    }
                    ++$column;
                }


            }

            //	$this->log("****後処理****"  );
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->removeSheetByIndex(0);

            //	$this->log("****保存****"  );
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');

            //            $objWriter -> save1($exportFile);
            $objWriter->save($exportFile);

            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
            $result["result"] = TRUE;
            $result["data"] = "files/KRSS/" . "予算表_" . $USERID . ".xlsx";

            //	$this->log("****出力完了****"  );

        } catch (\Exception $ex) {
            $result["result"] == FALSE;
            $result["data"] = $ex->getMessage();
        }
        return $result;
    }


}
