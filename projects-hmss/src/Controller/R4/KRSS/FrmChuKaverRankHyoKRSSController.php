<?php
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmChuKaverRankHyoKRSS;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;

//*******************************************
// * sample controller
//*******************************************
class FrmChuKaverRankHyoKRSSController extends AppController
{

    public $autoLayout = TRUE;
    private $FrmChuKaverRankHyoKRSS;
    private $Session;
    private $FORMAT_NUMBER_COMMA_SEPARATED1 = '#,##0';
    private $FORMAT_NUMBER_COMMA_SEPARATED5 = '#,##0.0';
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }
    public $lngOutCntK = "";
    public $lngOutCntU = "";
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmChuKaverRankHyoKRSS_layout');
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array('result' => 'false', 'data' => 'ErrorInfo', 'row' => '', );
        try {

            $this->FrmChuKaverRankHyoKRSS = new FrmChuKaverRankHyoKRSS();

            $result = $this->FrmChuKaverRankHyoKRSS->frmKanrSyukei_Load();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {

            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fileReadDialog()
    {
        $result = array('result' => 'false', 'data' => 'ErrorInfo', 'MsgID' => '');
        $postData = $_POST['data']['request'];
        try {
            $intState = 0;
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/R4k/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
            $this->Session = $this->request->getSession();
            $UPDUSER = $this->Session->read('login_user');
            $file = $tmpPath . $postData['fileName'] . "_" . $UPDUSER . ".xlsx";

            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Execl Error";
                    throw new \Exception($result["data"]);
                }
            }

            //エクセルのテンプレートが保存されている場所を取得
            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");

            if ($postData['radRankingCheck'] == 'true') {

                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmChuKaverRankHyoKRSSTemplate.xlsx";
                $resultOutPut = $this->fncExcelOutput($postData, $file, $strTemplatePath);

            }
            if ($postData['radYachinCheck'] == 'true') {
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmChuKaverRankHyoKRSSTemplate_Yachin.xlsx";
                $resultOutPut2 = $this->fncExcelOutput2($postData, $file, $strTemplatePath);
            }
            if ($postData['radBusyoCheck'] == 'true') {

                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmChuKaverRankHyoKRSSTemplate.xlsx";
                $resultOutPut = $this->fncExcelOutput($postData, $file, $strTemplatePath);
            }

            //テンプレートファイルの存在確認
            if (file_exists($strTemplatePath) == FALSE) {
                $result["data"] = "EXCELテンプレートが見つかりません！";
                throw new \Exception($result["data"]);
            }

            //ログ管理
            $intState = 9;
            if ($postData['radRankingCheck'] == 'true' || $postData['radBusyoCheck'] == 'true') {

                if (!$resultOutPut['result']) {
                    if ($resultOutPut['MsgID'] == 'I0001') {
                        $intState = 1;
                        $result['MsgID'] = 'I0001';
                        throw new \Exception('noData');
                    } else {
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($resultOutPut['data']);
                    }

                }
            } else {
                if (!$resultOutPut2['result']) {
                    if ($resultOutPut2['MsgID'] == 'I0001') {
                        $intState = 1;
                        $result['MsgID'] = 'I0001';
                        throw new \Exception('noData');
                    } else {
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($resultOutPut2['data']);
                    }

                }
            }
            $intState = 1;
            $result['result'] = TRUE;
            $result['data'] = "/gdmz/cake/files/R4k/" . $postData['fileName'] . "_" . $UPDUSER . ".xlsx";

        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
            $result['result'] = FALSE;
        }
        //ログ管理 Start
        if ($intState != 0) {

            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmChuKaverRankHyo_Koteihi_Excel", $intState, $this->lngOutCntK, $postData['cboYMStart'], $postData['YMEnd'], $postData['Rank'], $postData['rad1'], $postData['fileName']);
            $this->ClsLogControl->fncLogEntry("frmChuKaverRankHyo_UriageDaisu_Excel", $intState, $this->lngOutCntU, $postData['cboYMStart'], $postData['YMEnd'], $postData['Rank'], $postData['rad1'], $postData['fileName']);
        }
        //ログ管理 End
        $this->fncReturn($result);
    }

    public function fncExcelOutput($postData, $file, $templatefile)
    {
        $result = array('result' => FALSE, 'data' => 'ErrorInfo', 'MsgID' => 'E9999');
        try {
            $blnOutFlg = FALSE;
            // Create new PHPExcel object
            $PHPReader = new XlsxReader();
            // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            // PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
            $objPHPExcel = $PHPReader->load($templatefile);
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Set page orientation and size

            $this->FrmChuKaverRankHyoKRSS = new FrmChuKaverRankHyoKRSS();
            //タイトル行用SQL
            $result1 = $this->FrmChuKaverRankHyoKRSS->fncStandardInfoSel($postData);

            if (!$result1['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($result1['data']);
            }

            $strTitle = $result1['data'][0]["TITLE"];
            $value1 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_Y']);
            $value2 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_M']);
            $value3 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_Y']);
            $value4 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_M']);

            //20160915 Start 和暦廃止
//            //$strHaniYM = "(平成 " . $value1 . " 年 " . $value2 . " 月  ～　平成 " . $value3 . " 年 " . $value4 . " 月)";
//            //和暦
//            $valF = "";
//            $valT = "";
//            $strYmsF = "";
//            $strYmsT = "";
//            if (strlen($postData['cboYMStart']) == 6) {
//                $strYmsF = str_replace("/", "", $postData['cboYMStart']) . "00";
//            } else {
//                $strYmsF = str_replace("/", "", $postData['cboYMStart']);
//            }
//            if (strlen($postData['YMEnd']) == 6) {
//                $strYmsT = str_replace("/", "", $postData['YMEnd']) . "00";
//            } else {
//                $strYmsT = str_replace("/", "", $postData['YMEnd']);
//            }
//            $valF = $this -> ClsComFnc -> FncDateChange4($strYmsF);
//
//            $valT = $this -> ClsComFnc -> FncDateChange4($strYmsT);
//
//            $strHaniYM = "(" . substr($valF, 0, 6) . " " . substr($valF, 6, 2) . " 年 " . substr($valF, 9, 2) . " 月  ～　" . substr($valT, 0, 6) . " " . substr($valT, 6, 2) . " 年 " . substr($valT, 9, 2) . " 月)";
            $strHaniYM = "(" . $value1 . " 年 " . $value2 . " 月  ～　" . $value3 . " 年 " . $value4 . " 月)";
            //20160915 End 和暦廃止

            //固定費カバー率ランキング用SQL
            $result2 = $this->FrmChuKaverRankHyoKRSS->fncKaverRankSel($postData);

            if (!$result2['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($result2['data']);
            }

            $this->lngOutCntK = $result2['row'];
            if ($result2['row'] > 0) {
                $blnOutFlg = TRUE;

                $objPHPExcel->getActiveSheet()->setCellValue('E1', $strTitle);

                $objPHPExcel->getActiveSheet()->setCellValue('G3', $strHaniYM);

                $RowCnt = count((array) $result2['data']);
                $RowCnt = (int) $RowCnt + 7;
                $intRowCnt = 8;
                foreach ((array) $result2['data'] as $value) {
                    $this->subKoteihiMeisaiSet($objPHPExcel, $value, $intRowCnt);
                    $intRowCnt = $intRowCnt + 1;
                }
                $this->subKoteihiLineSet($objPHPExcel, $RowCnt);
            }
            //売上台数用SQL
            $result3 = $this->FrmChuKaverRankHyoKRSS->fncUriageRankSel($postData);
            if (!$result3['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($result3['data']);
            }
            $this->lngOutCntU = $result3['row'];
            if ($result3['row'] > 0) {
                $blnOutFlg = TRUE;
                $intRowCnt = 8;

                foreach ((array) $result3['data'] as $value) {
                    $this->subUriDaisuMeisaiSet($objPHPExcel, $value, $intRowCnt);

                    $intRowCnt = $intRowCnt + 1;
                }

                $RowCnt = (int) $result3['row'] + 7;
                $this->subUriDaisuLineSet($objPHPExcel, $RowCnt);

            }
            if (!$blnOutFlg) {
                $result['MsgID'] = 'I0001';
                throw new \Exception('');
            }

            //フッター用SQL
            $result4 = $this->FrmChuKaverRankHyoKRSS->fncMemoSel();

            if (!$result4['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($result4['data']);
            }
            $RowCnt = $RowCnt + 1;
            // $intMemoCnt = count($result2['data']);
            foreach ((array) $result4['data'] as $value) {
                $RowCnt = $RowCnt + 1;
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $RowCnt, $value['MEMO'], DataType::TYPE_STRING);
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('カバー率ランキング表');
            // Save Excel2007 file
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');

            $objWriter->save($file);
            $result['result'] = TRUE;
        } catch (\Exception $e) {

            $result['data'] = $e->getMessage();
            $result['result'] = FALSE;

        }

        return $result;
    }

    public function fncExcelOutput2($postData, $file, $templatefile)
    {
        $result = array('result' => 'false', 'data' => 'ErrorInfo', 'MsgID' => 'E9999');
        try {
            $blnOutFlg = FALSE;
            // Create new PHPExcel object
            $PHPReader = new XlsxReader();
            // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            // PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
            $objPHPExcel = $PHPReader->load($templatefile);
            // Set document properties
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Set page orientation and size

            $this->FrmChuKaverRankHyoKRSS = new FrmChuKaverRankHyoKRSS();
            //タイトル行用SQL
            $result1 = $this->FrmChuKaverRankHyoKRSS->fncStandardInfoSel($postData);

            if (!$result1['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($result1['data']);
            }

            $strTitle = $result1['data'][0]["TITLE"];
            $value1 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_Y']);
            $value2 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_M']);
            $value3 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_Y']);
            $value4 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_M']);
            //20160915 Start 和暦廃止
//            $strHaniYM = "(平成 " . $value1 . " 年 " . $value2 . " 月  ～　平成 " . $value3 . " 年 " . $value4 . " 月)";
            $strHaniYM = "(  " . $value1 . " 年 " . $value2 . " 月  ～　 " . $value3 . " 年 " . $value4 . " 月)";
            //20160915 End 和暦廃止

            //固定費カバー率ランキング用SQL
            $result2 = $this->FrmChuKaverRankHyoKRSS->fncKaverRankSel($postData);

            if (!$result2['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($result2['data']);
            }

            $this->lngOutCntK = $result2['row'];
            if ($result2['row'] > 0) {
                $blnOutFlg = TRUE;

                $objPHPExcel->getActiveSheet()->setCellValue('D1', $strTitle);

                $objPHPExcel->getActiveSheet()->setCellValue('F3', $strHaniYM);

                $RowCnt = count((array) $result2['data']);
                $RowCnt = (int) $RowCnt + 7;
                $intRowCnt = 8;
                foreach ((array) $result2['data'] as $value) {
                    $this->subKoteihiMeisaiSet2($objPHPExcel, $value, $intRowCnt);
                    $intRowCnt = $intRowCnt + 1;
                }
                $this->subKoteihiLineSet2($objPHPExcel, $RowCnt);
            }
            //売上台数用SQL
            $result3 = $this->FrmChuKaverRankHyoKRSS->fncUriageRankSel($postData);
            if (!$result3['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($result3['data']);
            }
            $this->lngOutCntU = $result3['row'];
            if ($result3['row'] > 0) {
                $blnOutFlg = TRUE;
                $intRowCnt = 8;

                foreach ((array) $result3['data'] as $value) {
                    $this->subUriDaisuMeisaiSet2($objPHPExcel, $value, $intRowCnt);

                    $intRowCnt = $intRowCnt + 1;
                }

                $RowCnt = (int) $result3['row'] + 7;
                $this->subUriDaisuLineSet2($objPHPExcel, $RowCnt);

            }
            if (!$blnOutFlg) {
                $result['MsgID'] = 'I0001';
                throw new \Exception('');
            }

            //フッター用SQL
            $result4 = $this->FrmChuKaverRankHyoKRSS->fncMemoSel();

            if (!$result4['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($result4['data']);
            }
            $RowCnt = $RowCnt + 1;
            // $intMemoCnt = count($result2['data']);
            foreach ((array) $result4['data'] as $value) {
                $RowCnt = $RowCnt + 1;
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $RowCnt, $value['MEMO'], DataType::TYPE_STRING);
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('カバー率ランキング表');
            // Save Excel2007 file
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');

            $objWriter->save($file);
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
            $result['result'] = FALSE;
        }

        return $result;
    }

    public function subUriDaisuLineSet($objPHPExcel, $RowCnt)
    {
        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('M8:' . 'P' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('M8:' . 'P' . $RowCnt)->applyFromArray($styleArrayOut);

        $objPHPExcel->getActiveSheet()->getStyle('R8:' . 'S' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('R8:' . 'S' . $RowCnt)->applyFromArray($styleArrayOut);
    }

    public function subUriDaisuLineSet2($objPHPExcel, $RowCnt)
    {
        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('L8:' . 'O' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('L8:' . 'O' . $RowCnt)->applyFromArray($styleArrayOut);

        $objPHPExcel->getActiveSheet()->getStyle('Q8:' . 'R' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('Q8:' . 'R' . $RowCnt)->applyFromArray($styleArrayOut);
    }

    public function subUriDaisuMeisaiSet($objPHPExcel, $value, $intRowCnt)
    {
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $intRowCnt, $value['TOUKI_JUNI']);
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $intRowCnt, $value['SYAIN_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $intRowCnt, $value['BUSYO_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('P' . $intRowCnt, $value['TOUKI_DAISU']);
        $objPHPExcel->getActiveSheet()->setCellValue('R' . $intRowCnt, $value['TOUGETU_JUNI']);
        $objPHPExcel->getActiveSheet()->setCellValue('S' . $intRowCnt, $value['TOUGETU_DAISU']);
    }

    public function subUriDaisuMeisaiSet2($objPHPExcel, $value, $intRowCnt)
    {
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $intRowCnt, $value['TOUKI_JUNI']);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $intRowCnt, $value['SYAIN_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $intRowCnt, $value['BUSYO_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $intRowCnt, $value['TOUKI_DAISU']);
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $intRowCnt, $value['TOUGETU_JUNI']);
        $objPHPExcel->getActiveSheet()->setCellValue('R' . $intRowCnt, $value['TOUGETU_DAISU']);
    }

    public function subKoteihiMeisaiSet($objPHPExcel, $value, $intRowCnt)
    {

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $intRowCnt, $value['TOUKI_JUN']);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $intRowCnt, $value['SYAIN_NO'], DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $intRowCnt, $value['SYAIN_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $intRowCnt, $value['BUSYO_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $intRowCnt, $value['KOTEI_KAVER_RT']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $intRowCnt, $value['TOUKI_GENRI']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $intRowCnt, $value['KOTEIHI']);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $intRowCnt, $value['WORK_BUNPAI_RT']);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $intRowCnt, $value['Y_MINUS_KOTEI']);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $intRowCnt, $value['Y_MINUS_KAVER_RT']);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $intRowCnt, $value['SANKO_JUN']);
    }

    public function subKoteihiMeisaiSet2($objPHPExcel, $value, $intRowCnt)
    {

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $intRowCnt, $value['SANKO_JUN']);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $intRowCnt, $value['SYAIN_NO'], DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $intRowCnt, $value['SYAIN_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $intRowCnt, $value['BUSYO_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $intRowCnt, $value['Y_MINUS_KAVER_RT']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $intRowCnt, $value['TOUKI_GENRI']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $intRowCnt, $value['Y_MINUS_KOTEI']);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $intRowCnt, $value['WORK_BUNPAI_RT']);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $intRowCnt, $value['KANRI_DAISU']);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $intRowCnt, $value['KEIKEN_NENSU']);

        //$this -> subKoteihiLineSet();
    }

    public function subKoteihiLineSet($objPHPExcel, $RowCnt)
    {
        $objPHPExcel->getActiveSheet()->getStyle('B8:' . 'B' . $RowCnt)->getNumberFormat()->setFormatCode('00000');
        $objPHPExcel->getActiveSheet()->getStyle('E8:' . 'E' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED5);
        $objPHPExcel->getActiveSheet()->getStyle('F8:' . 'F' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('G8:' . 'G' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('H8:' . 'H' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED5);
        $objPHPExcel->getActiveSheet()->getStyle('I8:' . 'I' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('J8:' . 'J' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED5);
        $objPHPExcel->getActiveSheet()->getStyle('K8:' . 'K' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);

        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'K' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'K' . $RowCnt)->applyFromArray($styleArrayOut);

    }

    public function subKoteihiLineSet2($objPHPExcel, $RowCnt)
    {

        $objPHPExcel->getActiveSheet()->getStyle('B8:' . 'B' . $RowCnt)->getNumberFormat()->setFormatCode('00000');
        $objPHPExcel->getActiveSheet()->getStyle('E8:' . 'E' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED5);
        $objPHPExcel->getActiveSheet()->getStyle('F8:' . 'F' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('H8:' . 'H' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED5);
        $objPHPExcel->getActiveSheet()->getStyle('G8:' . 'G' . $RowCnt)->getNumberFormat()->setFormatCode($this->FORMAT_NUMBER_COMMA_SEPARATED1);
        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'J' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'J' . $RowCnt)->applyFromArray($styleArrayOut);

    }
}
