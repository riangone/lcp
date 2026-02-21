<?php
/**
 * 説明：個人新車ランキングリスト
 *
 *
 * @author li
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20160915              ---                      和暦廃止                        HM
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmSinKaverRankHyoKRSS;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmSinKaverRankHyoKRSSController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }

    public $lngOutCntK = "";
    public $lngOutCntU = "";
    public $FrmSinKaverRankHyoKRSS;

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmSinKaverRankHyoKRSS_layout');
    }

    //コントロールマスタ存在ﾁｪｯｸ
    public function frmKanrSyukeiLoad()
    {
        // $result = array('result' => 'false', 'data' => 'ErrorInfo', 'row' => '', );
        try {
            $this->FrmSinKaverRankHyoKRSS = new FrmSinKaverRankHyoKRSS();
            $result = $this->FrmSinKaverRankHyoKRSS->frmKanrSyukei_Load();
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
    //処 理 名：Excelボタン押下 ランキング(家賃を除く)
    //関 数 名：cmdExcelOut
    //引    数：$postData $file $templatefile
    //戻 り 値：無し
    //処理説明：個人新車ランキングリストを作成する
    //**********************************************************************
    public function fncExcelOutput2($postData, $file, $templatefile)
    {
        // $result = array('result' => 'false', 'data' => 'ErrorInfo', 'MsgID' => 'E9999');
        try {
            $blnOutFlg = FALSE;
            // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";

            // Create new PHPExcel object
            $PHPReader = new Xlsx();

            // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            // PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

            $objPHPExcel = $PHPReader->load($templatefile);
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Set page orientation and size

            $this->FrmSinKaverRankHyoKRSS = new FrmSinKaverRankHyoKRSS();
            //タイトル行用SQL
            $result1 = $this->FrmSinKaverRankHyoKRSS->fncStandardInfoSel($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $strTitle = $result1['data'][0]["TITLE"];
            $value1 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_Y']);
            $value2 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_M']);
            $value3 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_Y']);
            $value4 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_M']);

            //20160915 Upd Start 和暦廃止
            // $strHaniYM = "(平成 " . $value1 . " 年 " . $value2 . " 月  ～　平成 " . $value3 . " 年 " . $value4 . " 月)";			$valT = "";
//           //和暦
//			$valF = "";
//			$valT = "";
//			$strYmsF = "";
//			$strYmsT = "";
//			if (strlen($postData['cboYMStart']) == 6 ){
//				$strYmsF = str_replace("/", "", $postData['cboYMStart']) . "00";
//			}else
//				{
//					$strYmsF = str_replace("/", "", $postData['cboYMStart']) ;
//				}
//			if (strlen($postData['YMEnd']) == 6 ){
//				$strYmsT = str_replace("/", "", $postData['YMEnd']) . "00";
//			}else
//				{
//					$strYmsT = str_replace("/", "", $postData['YMEnd']) ;
//				}
//			$valF = $this -> ClsComFnc ->  FncDateChange4($strYmsF);
//			$valT = $this -> ClsComFnc ->  FncDateChange4($strYmsT);
//			$strHaniYM = "(" . substr($valF, 0, 6) . " " . substr($valF, 6, 2) . " 年 " . substr($valF, 9, 2) . " 月  ～　" . substr($valT, 0, 6) . " " . substr($valT, 6, 2) . " 年 " . substr($valT, 9, 2)  . " 月)";
            $strHaniYM = "(" . $value1 . " 年 " . $value2 . " 月  ～　" . $value3 . " 年 " . $value4 . " 月)";
            //20160915 Upd End　和暦廃止

            //フッター用SQL
            $result2 = $this->FrmSinKaverRankHyoKRSS->fncMemoSel();

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $intMemoCnt = count((array) $result2['data']);

            //固定費カバー率ランキング用SQL
            $result3 = $this->FrmSinKaverRankHyoKRSS->fncKaverRankSel($postData, $intMemoCnt);

            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }

            $this->lngOutCntK = $result3['row'];

            if ($result3['row'] > 0) {
                $blnOutFlg = TRUE;
                $objPHPExcel->getActiveSheet()->setCellValue('E1', $strTitle);
                $objPHPExcel->getActiveSheet()->setCellValue('G3', $strHaniYM);

                //  $this -> subKomokuSet_Yachin($objPHPExcel);
                $RowCnt = count((array) $result3['data']);
                $RowCnt = (int) $RowCnt + 7;
                $intRowCnt = 8;

                foreach ((array) $result3['data'] as $value) {
                    $this->subKoteihiMeisaiSet_Yachin($objPHPExcel, $value, $intRowCnt);

                    $intRowCnt = $intRowCnt + 1;
                }
                //
                $this->subKoteihiLineSet_Yachin($objPHPExcel, $RowCnt);

            }

            //売上台数用SQL

            $result4 = $this->FrmSinKaverRankHyoKRSS->fncUriageRankSel($postData);
            $this->lngOutCntU = $result4['row'];
            if (!$result4['result']) {
                throw new \Exception($result4['data']);
            }

            if ($result4['row'] > 0) {
                $blnOutFlg = TRUE;
                $intRowCnt = 8;

                foreach ((array) $result4['data'] as $value) {
                    $this->subUriDaisuMeisaiSet_Yachin($objPHPExcel, $value, $intRowCnt);

                    $intRowCnt = $intRowCnt + 1;
                }

                $RowCnt = (int) $result4['row'] + 7;
                // $this -> subUriDaisuLineSet($objPHPExcel, $RowCnt);
                $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
                $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
                $objPHPExcel->getActiveSheet()->getStyle('L8:' . 'O' . $RowCnt)->applyFromArray($styleArrayIn);
                $objPHPExcel->getActiveSheet()->getStyle('L8:' . 'O' . $RowCnt)->applyFromArray($styleArrayOut);

                $objPHPExcel->getActiveSheet()->getStyle('Q8:' . 'R' . $RowCnt)->applyFromArray($styleArrayIn);
                $objPHPExcel->getActiveSheet()->getStyle('Q8:' . 'R' . $RowCnt)->applyFromArray($styleArrayOut);
            }
            if (!$blnOutFlg) {
                $result['MsgID'] = 'I0001';
                throw new \Exception('');
            }
            $RowCnt = $RowCnt + 1;
            foreach ((array) $result2['data'] as $value) {
                $RowCnt = $RowCnt + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $RowCnt, $value['MEMO']);
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

    //**********************************************************************
    //処 理 名：Excelボタン押下 ランキング  部署別
    //関 数 名：cmdExcelOut
    //引    数：$postData $file $templatefile
    //戻 り 値：無し
    //処理説明：個人新車ランキングリストを作成する
    //**********************************************************************
    public function fncExcelOutput($postData, $file, $templatefile)
    {
        // $result = array('result' => 'false', 'data' => 'ErrorInfo', 'MsgID' => 'E9999');
        try {
            $blnOutFlg = FALSE;
            // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";
            // Create new PHPExcel object
            $PHPReader = new Xlsx();
            // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            // PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
            $objPHPExcel = $PHPReader->load($templatefile);
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Set page orientation and size

            $this->FrmSinKaverRankHyoKRSS = new FrmSinKaverRankHyoKRSS();
            //タイトル行用SQL
            $result1 = $this->FrmSinKaverRankHyoKRSS->fncStandardInfoSel($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $strTitle = $result1['data'][0]["TITLE"];
            $value1 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_Y']);
            $value2 = $this->ClsComFnc->FncNv($result1['data'][0]['KISYU_M']);
            $value3 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_Y']);
            $value4 = $this->ClsComFnc->FncNv($result1['data'][0]['TUKI_M']);
            //20160915 Upd Start 和暦廃止
//            //$strHaniYM = "(平成 " . $value1 . " 年 " . $value2 . " 月  ～　平成 " . $value3 . " 年 " . $value4 . " 月)";
//
//			//和暦
//			$valF = "";
//			$valT = "";
//			$strYmsF = "";
//			$strYmsT = "";
//			if (strlen($postData['cboYMStart']) == 6 ){
//				$strYmsF = str_replace("/", "", $postData['cboYMStart']) . "00";
//			}else
//				{
//					$strYmsF = str_replace("/", "", $postData['cboYMStart']) ;
//				}
//			if (strlen($postData['YMEnd']) == 6 ){
//				$strYmsT = str_replace("/", "", $postData['YMEnd']) . "00";
//			}else
//				{
//					$strYmsT = str_replace("/", "", $postData['YMEnd']) ;
//				}
//			$valF = $this -> ClsComFnc ->  FncDateChange4($strYmsF);
//
//			$valT = $this -> ClsComFnc ->  FncDateChange4($strYmsT);
//
//			$strHaniYM = "(" . substr($valF, 0, 6) . " " . substr($valF, 6, 2) . " 年 " . substr($valF, 9, 2) . " 月  ～　" . substr($valT, 0, 6) . " " . substr($valT, 6, 2) . " 年 " . substr($valT, 9, 2)  . " 月)";
            $strHaniYM = "(" . $value1 . " 年 " . $value2 . " 月  ～　" . $value3 . " 年 " . $value4 . " 月)";
            //20160915 Upd End 和暦廃止

            //フッター用SQL
            $result2 = $this->FrmSinKaverRankHyoKRSS->fncMemoSel();

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $intMemoCnt = count((array) $result2['data']);

            //固定費カバー率ランキング用SQL
            $result3 = $this->FrmSinKaverRankHyoKRSS->fncKaverRankSel($postData, $intMemoCnt);

            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }
            $this->lngOutCntK = $result3['row'];

            if ($result3['row'] > 0) {

                $blnOutFlg = TRUE;
                $objPHPExcel->getActiveSheet()->setCellValue('E1', $strTitle);
                $objPHPExcel->getActiveSheet()->setCellValue('G3', $strHaniYM);
                $RowCnt = count((array) $result3['data']);
                $RowCnt = (int) $RowCnt + 7;
                $intRowCnt = 8;

                foreach ((array) $result3['data'] as $value) {
                    $this->subKoteihiMeisaiSet($objPHPExcel, $value, $intRowCnt);

                    $intRowCnt = $intRowCnt + 1;
                }

                $this->subKoteihiLineSet($objPHPExcel, $RowCnt, $postData, $result3['data']);

            }

            //売上台数用SQL

            $result4 = $this->FrmSinKaverRankHyoKRSS->fncUriageRankSel($postData);

            if (!$result4['result']) {
                throw new \Exception($result4['data']);
            }
            $this->lngOutCntU = $result4['row'];

            if ($result4['row'] > 0) {
                $blnOutFlg = TRUE;
                $intRowCnt = 8;

                foreach ((array) $result4['data'] as $value) {
                    $this->subUriDaisuMeisaiSet($objPHPExcel, $value, $intRowCnt);
                    $intRowCnt = $intRowCnt + 1;
                }

                $RowCnt = (int) $result4['row'] + 7;
                $this->subUriDaisuLineSet($objPHPExcel, $RowCnt);

            }
            if (!$blnOutFlg) {
                $result['MsgID'] = 'I0001';
                throw new \Exception('');
            }

            $RowCnt = $RowCnt + 1;
            foreach ((array) $result2['data'] as $value) {
                $RowCnt = $RowCnt + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $RowCnt, $value['MEMO']);
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
        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('O8:' . 'R' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('O8:' . 'R' . $RowCnt)->applyFromArray($styleArrayOut);

        $objPHPExcel->getActiveSheet()->getStyle('T8:' . 'U' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('T8:' . 'U' . $RowCnt)->applyFromArray($styleArrayOut);
    }

    public function subUriDaisuMeisaiSet($objPHPExcel, $value, $intRowCnt)
    {
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $intRowCnt, $value['TOUKI_JUNI']);
        $objPHPExcel->getActiveSheet()->setCellValue('P' . $intRowCnt, $value['SYAIN_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $intRowCnt, $value['BUSYO_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('R' . $intRowCnt, $value['TOUKI_DAISU']);
        $objPHPExcel->getActiveSheet()->setCellValue('T' . $intRowCnt, $value['TOUGETU_JUNI']);
        $objPHPExcel->getActiveSheet()->setCellValue('U' . $intRowCnt, $value['TOUGETU_DAISU']);
    }

    public function subUriDaisuMeisaiSet_Yachin($objPHPExcel, $value, $intRowCnt)
    {
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $intRowCnt, $value['TOUKI_JUNI']);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $intRowCnt, $value['SYAIN_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $intRowCnt, $value['BUSYO_NM']);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $intRowCnt, $value['TOUKI_DAISU']);
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $intRowCnt, $value['TOUGETU_JUNI']);
        $objPHPExcel->getActiveSheet()->setCellValue('R' . $intRowCnt, $value['TOUGETU_DAISU']);
    }

    public function subKoteihiMeisaiSet_Yachin($objPHPExcel, $value, $intRowCnt)
    {

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $intRowCnt, $value['SANKO_JUN']);

        $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $intRowCnt, $value['SYAIN_NO'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $objPHPExcel->getActiveSheet()->setCellValue('C' . $intRowCnt, $value['SYAIN_NM']);

        $objPHPExcel->getActiveSheet()->setCellValue('D' . $intRowCnt, $value['BUSYO_NM']);

        $objPHPExcel->getActiveSheet()->setCellValue('E' . $intRowCnt, $value['Y_MINUS_KAVER_RT']);

        $objPHPExcel->getActiveSheet()->setCellValue('F' . $intRowCnt, $value['TOUKI_GENRI']);

        $objPHPExcel->getActiveSheet()->setCellValue('G' . $intRowCnt, $value['Y_MINUS_KOTEI']);

        $objPHPExcel->getActiveSheet()->setCellValue('H' . $intRowCnt, $value['WORK_BUNPAI_RT']);

        $objPHPExcel->getActiveSheet()->setCellValue('I' . $intRowCnt, $value['KANRI_DAISU']);

        $objPHPExcel->getActiveSheet()->setCellValue('J' . $intRowCnt, $value['KEIKEN_NENSU']);

    }

    public function subKoteihiMeisaiSet($objPHPExcel, $value, $intRowCnt)
    {

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $intRowCnt, $value['TOUKI_JUN']);

        $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $intRowCnt, $value['SYAIN_NO'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $objPHPExcel->getActiveSheet()->setCellValue('C' . $intRowCnt, $value['SYAIN_NM']);

        $objPHPExcel->getActiveSheet()->setCellValue('D' . $intRowCnt, $value['BUSYO_NM']);

        $objPHPExcel->getActiveSheet()->setCellValue('E' . $intRowCnt, $value['KOTEI_KAVER_RT']);

        $objPHPExcel->getActiveSheet()->setCellValue('F' . $intRowCnt, $value['TOUKI_GENRI']);

        $objPHPExcel->getActiveSheet()->setCellValue('G' . $intRowCnt, $value['KOTEIHI']);

        $objPHPExcel->getActiveSheet()->setCellValue('H' . $intRowCnt, $value['WORK_BUNPAI_RT']);

        $objPHPExcel->getActiveSheet()->setCellValue('I' . $intRowCnt, $value['Y_MINUS_KOTEI']);

        $objPHPExcel->getActiveSheet()->setCellValue('J' . $intRowCnt, $value['Y_MINUS_KAVER_RT']);

        $objPHPExcel->getActiveSheet()->setCellValue('K' . $intRowCnt, $value['SANKO_JUN']);

        $objPHPExcel->getActiveSheet()->setCellValue('L' . $intRowCnt, $value['KANRI_DAISU']);

        $objPHPExcel->getActiveSheet()->setCellValue('M' . $intRowCnt, $value['KEIKEN_NENSU']);

    }

    public function subKoteihiLineSet($objPHPExcel, $RowCnt, $postData = NULL, $data)
    {

        if ($postData['radRankingCheck'] == 'true') {
            for ($i = 7; $i <= $RowCnt; $i++) {
                if ($i == $RowCnt) {
                    $row = $i;
                } else {
                    $row = $i + 1;
                }

                if ((intval(($i - 7) / 10)) % 2 == 0) {
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':M' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':M' . $row)->getFill()->getStartColor()->setRGB('FFFFFF');
                } else {
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':M' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':M' . $row)->getFill()->getStartColor()->setRGB('d3d3d3');
                }

            }

        } else {
            $default = 8;

            foreach ($data as $key => $value) {
                if ($value['COLOR_NO'] % 2 == 0) {
                    $id = $key + $default;
                    $position = 'A' . $id . ':N' . $id;

                    $objPHPExcel->getActiveSheet()->getStyle($position)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle($position)->getFill()->getStartColor()->setRGB('d3d3d3');
                }

            }

        }
        $objPHPExcel->getActiveSheet()->getStyle('B8:' . 'B' . $RowCnt)->getNumberFormat()->setFormatCode('00000');
        $objPHPExcel->getActiveSheet()->getStyle('E8:' . 'E' . $RowCnt)->getNumberFormat()->setFormatCode("#,##0.0");
        $objPHPExcel->getActiveSheet()->getStyle('F8:' . 'F' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('G8:' . 'G' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('H8:' . 'H' . $RowCnt)->getNumberFormat()->setFormatCode("#,##0.0");
        $objPHPExcel->getActiveSheet()->getStyle('I8:' . 'I' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('J8:' . 'J' . $RowCnt)->getNumberFormat()->setFormatCode("#,##0.0");
        $objPHPExcel->getActiveSheet()->getStyle('K8:' . 'K' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('L8:' . 'L' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('M8:' . 'M' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');

        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'M' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'M' . $RowCnt)->applyFromArray($styleArrayOut);

    }

    public function subKoteihiLineSet_Yachin($objPHPExcel, $RowCnt)
    {

        for ($i = 7; $i <= $RowCnt; $i++) {
            if ($i == $RowCnt) {
                $row = $i;
            } else {
                $row = $i + 1;
            }

            if ((intval(($i - 7) / 10)) % 2 == 0) {

                $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':J' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':J' . $row)->getFill()->getStartColor()->setRGB('FFFFFF');

            } else {

                $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':J' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':J' . $row)->getFill()->getStartColor()->setRGB('d3d3d3');

            }

        }

        $objPHPExcel->getActiveSheet()->getStyle('B8:' . 'B' . $RowCnt)->getNumberFormat()->setFormatCode('00000');
        $objPHPExcel->getActiveSheet()->getStyle('E8:' . 'E' . $RowCnt)->getNumberFormat()->setFormatCode("#,##0.0");
        $objPHPExcel->getActiveSheet()->getStyle('H8:' . 'H' . $RowCnt)->getNumberFormat()->setFormatCode("#,##0.0");

        $objPHPExcel->getActiveSheet()->getStyle('F8:' . 'F' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('G8:' . 'G' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('I8:' . 'I' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('J8:' . 'J' . $RowCnt)->getNumberFormat()->setFormatCode('#,##0');

        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'J' . $RowCnt)->applyFromArray($styleArrayIn);
        $objPHPExcel->getActiveSheet()->getStyle('A8:' . 'J' . $RowCnt)->applyFromArray($styleArrayOut);

    }

    //**********************************************************************
    //処 理 名：Excelボタン押下
    //関 数 名：cmdExcelOut
    //引    数：無し
    //戻 り 値：無し
    //処理説明：個人新車ランキングリストを作成する
    //**********************************************************************
    public function fileReadDialog()
    {
        // $result = array('result' => 'false', 'data' => 'ErrorInfo');
        $postData = $_POST['data']['request'];
        $this->FrmSinKaverRankHyoKRSS = new FrmSinKaverRankHyoKRSS();
        $USERID = $this->FrmSinKaverRankHyoKRSS->GS_LOGINUSER['strUserID'];
        try {
            $intState = 0;
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/KRSS/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
            $file = $tmpPath . $postData['fileName'] . "_" . $USERID . ".xlsx";

            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Execl Error";
                    throw new \Exception($result["data"]);
                }
            }

            //エクセルのテンプレートが保存されている場所を取得
            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");

            if ($postData['radRankingCheck'] == 'true') {
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmSinKaverRankHyoKRSSTemplate_New.xlsx";
                $resultOutPut = $this->fncExcelOutput($postData, $file, $strTemplatePath);

            }
            if ($postData['radYachinCheck'] == 'true') {
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmSinKaverRankHyoKRSSTemplate_New_Yachin.xlsx";
                $resultOutPut2 = $this->fncExcelOutput2($postData, $file, $strTemplatePath);
            }
            if ($postData['radBusyoCheck'] == 'true') {

                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmSinKaverRankHyoKRSSTemplate_New_Busyo.xlsx";
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
                        throw new \Exception($resultOutPut['data']);
                    }

                }
            }
            $intState = 1;
            $result['result'] = TRUE;
            $result['data'] = "/gdmz/cake/files/KRSS/" . $postData['fileName'] . "_" . $USERID . ".xlsx";

        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
            $result['result'] = FALSE;
        }

        //ログ管理 Start
        if ($intState != 0) {

            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmSinKaverRankHyo_Koteihi_Excel", $intState, $this->lngOutCntK, $postData['cboYMStart'], $postData['YMEnd'], $postData['Rank'], $postData['rad1'], $postData['fileName'] . "_" . $USERID . ".xlsx");
            $this->ClsLogControl->fncLogEntry("frmSinKaverRankHyo_UriageDaisu_Excel", $intState, $this->lngOutCntU, $postData['cboYMStart'], $postData['YMEnd'], $postData['Rank'], $postData['rad1'], $postData['fileName'] . "_" . $USERID . ".xlsx");
        }
        //ログ管理 End

        $this->fncReturn($result);
    }

}
