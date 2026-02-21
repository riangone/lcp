<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                                    担当
 * YYYYMMDD           #ID                                     XXXXXX                                 FCSDL
 * 20240228           20240213_機能改善要望対応 NO6    OBC科目マスタのレイアウトが変更されたため、
 *                                                   エクスポート／インポートとも対応が必要                     caina
 * 20250423           Bug        文字列内にシングルクォートがある場合は二重化して登録できるようにする      lujunxia
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HDKAIKEI;
use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKOBCDataExpImp;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat as PHPExcel_Style_NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

//*******************************************
// * sample controller
//*******************************************
class HDKOBCDataExpImpController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    public $HDKOBCDataExpImp = null;
    public $uploadfile = null;
    public $Session;

    // public $ClsComFncHDKAIKEI = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
        $this->loadComponent('CustomHDKExportPDF');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HDKOBCDataExpImp_layout');
    }


    public function getData($param)
    {
        $this->HDKOBCDataExpImp = new HDKOBCDataExpImp();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $tablename = $param['selecttable'];
            $tabledata = $this->HDKOBCDataExpImp->Getalldata($tablename);
            if (!$tabledata['result']) {
                throw new \Exception($tabledata['data']);
            }
            $res['data'] = $tabledata;
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    public function btnDownloadClick()
    {

        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $pdfDTRes = $this->getData($_POST['data']);
            $tablename = $_POST['data']['selecttable'];
            if (!$pdfDTRes['result']) {
                throw new \Exception($pdfDTRes['error']);
            }
            $makeExcel = $this->MakeExcel($pdfDTRes, $tablename);
            if ($makeExcel['result'] == false) {
                throw new \Exception($makeExcel['error']);
            }
            $res = $makeExcel;

            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    public function MakeExcel($pdfDTRes, $tablename)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            ini_set('memory_limit', '-1');
            //出力先パス
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/HDKAIKEI/";
            //            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            $tmpPath = "files/HDKAIKEI/";
            if ($tablename == 'HDK_MST_KAMOKU') {
                $fileName = $tmpPath . "OBC科目一覧.xlsx";
            }
            if ($tablename == 'HDK_MST_SHZKBN') {
                $fileName = $tmpPath . "OBC消費税区分一覧.xlsx";
            }
            if ($tablename == 'HDK_MST_TORIHIKISAKI') {
                $fileName = $tmpPath . "OBC取引先登録一覧.xlsx";
            }
            if ($tablename == 'HDK_MST_BUMON') {
                $fileName = $tmpPath . "OBC部門一覧.xlsx";
            }
            if ($tablename == 'HDK_MST_BANK') {
                $fileName = $tmpPath . "金融機関一覧.xlsx";
            }

            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            } else {
                //                $outFloder = $tmpPath1 . "/webroot/files/HDKAIKEI/";
                $outFloder = dirname($tmpPath);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                mkdir($tmpPath, 0777, TRUE);
            }

            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->createSheet();

            //枠線、色の前の2桁の00は透明度で、後ろの6桁のオンライン色、前の2桁の透明度をプラスしなければならなくて、さもなくば色はあなたの色ではありませんことを発見することができます
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'style' => Border::BORDER_MEDIUM,
                        //细边框
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
            );
            if ($tablename == 'HDK_MST_KAMOKU') {
                $objPHPExcel->setActiveSheetIndex(1);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', '勘定科目コード');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', '勘定科目名');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', '補助科目コード');
                $objPHPExcel->getActiveSheet()->setCellValue('D1', '補助科目名');
                $objPHPExcel->getActiveSheet()->setCellValue('E1', 'インデックス');
                $objPHPExcel->getActiveSheet()->setCellValue('F1', '勘定科目と同じ設定（消費税）');
                //20240228 caina UPD s
                // $objPHPExcel->getActiveSheet()->setCellValue('G1', '使用フラグ');
                // $objPHPExcel->getActiveSheet()->setCellValue('H1', '使用フラグ名');
                $objPHPExcel->getActiveSheet()->setCellValue('G1', '借方消費税区分コード');
                $objPHPExcel->getActiveSheet()->setCellValue('H1', '借方消費税区分名');
                $objPHPExcel->getActiveSheet()->setCellValue('I1', '貸方消費税区分コード');
                $objPHPExcel->getActiveSheet()->setCellValue('J1', '貸方消費税区分名');
                $objPHPExcel->getActiveSheet()->setCellValue('K1', '消費税率種別コード');
                $objPHPExcel->getActiveSheet()->setCellValue('L1', '消費税率種別');
                $objPHPExcel->getActiveSheet()->setCellValue('M1', '消費税自動計算コード');
                $objPHPExcel->getActiveSheet()->setCellValue('N1', '消費税自動計算');
                $objPHPExcel->getActiveSheet()->setCellValue('O1', '端数処理コード');
                $objPHPExcel->getActiveSheet()->setCellValue('P1', '端数処理');
                $objPHPExcel->getActiveSheet()->setCellValue('Q1', '事業区分コード');
                $objPHPExcel->getActiveSheet()->setCellValue('R1', '事業区分名');
                $objPHPExcel->getActiveSheet()->setCellValue('S1', '勘定科目と同じ設定（資金繰り）');
                $objPHPExcel->getActiveSheet()->setCellValue('T1', '借方資金繰り項目コード');
                $objPHPExcel->getActiveSheet()->setCellValue('U1', '借方資金繰り項目');
                $objPHPExcel->getActiveSheet()->setCellValue('V1', '貸方資金繰り項目コード');
                $objPHPExcel->getActiveSheet()->setCellValue('W1', '貸方資金繰り項目');
                $objPHPExcel->getActiveSheet()->setCellValue('X1', '勘定科目と同じ設定（損益分岐点）');
                $objPHPExcel->getActiveSheet()->setCellValue('Y1', '費用区分コード');
                $objPHPExcel->getActiveSheet()->setCellValue('Z1', '費用区分');
                $objPHPExcel->getActiveSheet()->setCellValue('AA1', '予算入力コード');
                $objPHPExcel->getActiveSheet()->setCellValue('AB1', '予算入力');
                $objPHPExcel->getActiveSheet()->setCellValue('AC1', '勘定科目と同じ設定（キャッシュ・フロー）');
                $objPHPExcel->getActiveSheet()->setCellValue('AD1', '振替元金額コード');
                $objPHPExcel->getActiveSheet()->setCellValue('AE1', '振替元金額');
                $objPHPExcel->getActiveSheet()->setCellValue('AF1', '第一振替先種類コード');
                $objPHPExcel->getActiveSheet()->setCellValue('AG1', '第一振替先種類');
                $objPHPExcel->getActiveSheet()->setCellValue('AH1', '第一振替先コード');
                $objPHPExcel->getActiveSheet()->setCellValue('AI1', '第一振替先');
                $objPHPExcel->getActiveSheet()->setCellValue('AJ1', '第二振替先種類コード');
                $objPHPExcel->getActiveSheet()->setCellValue('AK1', '第二振替先種類');
                $objPHPExcel->getActiveSheet()->setCellValue('AL1', '第二振替先コード');
                $objPHPExcel->getActiveSheet()->setCellValue('AM1', '第二振替先');
                //20240228 caina UPD e
                //sheet名の設定
                $objPHPExcel->getActiveSheet()->setTitle('科目・補助科目データ作成');
                //スタイル設定
                //20240228 caina upd s
                // $objPHPExcel->getActiveSheet()->getStyle("A1:AO1")->getFont()->setName('ＭＳ Ｐゴシック	')->setSize(11)->getColor()->setRGB('FFFFFF');
                // $objPHPExcel->getActiveSheet()->getStyle('A1:AO1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('36648B');
                $objPHPExcel->getActiveSheet()->getStyle("A1:AM1")->getFont()->setName('ＭＳ Ｐゴシック	')->setSize(11)->getColor()->setRGB('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A1:AM1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('36648B');
                $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
                // $objPHPExcel->getActiveSheet()->getStyle('A1:AO1')->getAlignment()->setWrapText(true);
                // $objPHPExcel->getActiveSheet()->getStyle('A1:AO1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A1:AM1')->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('A1:AM1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                //20240228 caina upd e
                $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('O')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('U')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('V')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('X')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('AC')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('AF')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('AG')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('AH')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('AI')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('AJ')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('AK')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('AL')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $objPHPExcel->getActiveSheet()->getStyle('AM')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                //20240228 caina del s
                // $objPHPExcel->getActiveSheet()->getStyle('AN')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                // $objPHPExcel->getActiveSheet()->getStyle('AO')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                //20240228 caina del e
                // 设置列宽
                $columnA = $objPHPExcel->getActiveSheet()->getColumnDimension('A');
                $columnA->setWidth(13.75);
                $columnB = $objPHPExcel->getActiveSheet()->getColumnDimension('B');
                $columnB->setWidth(19.63);
                $columnC = $objPHPExcel->getActiveSheet()->getColumnDimension('C');
                $columnC->setWidth(13.75);
                $columnD = $objPHPExcel->getActiveSheet()->getColumnDimension('D');
                $columnD->setWidth(48.25);
                $columnE = $objPHPExcel->getActiveSheet()->getColumnDimension('E');
                $columnE->setWidth(27.63);
                $columnF = $objPHPExcel->getActiveSheet()->getColumnDimension('F');
                $columnF->setWidth(16);
                $columnG = $objPHPExcel->getActiveSheet()->getColumnDimension('G');
                $columnG->setWidth(16);
                $columnH = $objPHPExcel->getActiveSheet()->getColumnDimension('H');
                $columnH->setWidth(17.63);
                $columnI = $objPHPExcel->getActiveSheet()->getColumnDimension('I');
                $columnI->setWidth(16);
                $columnJ = $objPHPExcel->getActiveSheet()->getColumnDimension('J');
                $columnJ->setWidth(17.63);
                $columnK = $objPHPExcel->getActiveSheet()->getColumnDimension('K');
                $columnK->setWidth(16);
                $columnL = $objPHPExcel->getActiveSheet()->getColumnDimension('L');
                $columnL->setWidth(17.63);
                $columnM = $objPHPExcel->getActiveSheet()->getColumnDimension('M');
                $columnM->setWidth(16);
                $columnN = $objPHPExcel->getActiveSheet()->getColumnDimension('N');
                $columnN->setWidth(11.75);
                $columnO = $objPHPExcel->getActiveSheet()->getColumnDimension('O');
                $columnO->setWidth(16);
                $columnP = $objPHPExcel->getActiveSheet()->getColumnDimension('P');
                $columnP->setWidth(24.63);
                $columnQ = $objPHPExcel->getActiveSheet()->getColumnDimension('Q');
                $columnQ->setWidth(16);
                $columnR = $objPHPExcel->getActiveSheet()->getColumnDimension('R');
                //20240228 caina upd s
                // $columnR->setWidth(10.88);
                $columnR->setWidth(20.88);
                //20240228 caina upd e
                $columnS = $objPHPExcel->getActiveSheet()->getColumnDimension('S');
                $columnS->setWidth(16);
                $columnT = $objPHPExcel->getActiveSheet()->getColumnDimension('T');
                $columnT->setWidth(19.63);
                $columnU = $objPHPExcel->getActiveSheet()->getColumnDimension('U');
                $columnU->setWidth(16);
                $columnV = $objPHPExcel->getActiveSheet()->getColumnDimension('V');
                $columnV->setWidth(16);
                $columnW = $objPHPExcel->getActiveSheet()->getColumnDimension('W');
                $columnW->setWidth(15.63);
                $columnX = $objPHPExcel->getActiveSheet()->getColumnDimension('X');
                $columnX->setWidth(16);
                $columnY = $objPHPExcel->getActiveSheet()->getColumnDimension('Y');
                $columnY->setWidth(15.63);
                $columnZ = $objPHPExcel->getActiveSheet()->getColumnDimension('Z');
                $columnZ->setWidth(16);
                $columnAA = $objPHPExcel->getActiveSheet()->getColumnDimension('AA');
                $columnAA->setWidth(16);
                $columnAB = $objPHPExcel->getActiveSheet()->getColumnDimension('AB');
                $columnAB->setWidth(8.88);
                $columnAC = $objPHPExcel->getActiveSheet()->getColumnDimension('AC');
                $columnAC->setWidth(16);
                $columnAD = $objPHPExcel->getActiveSheet()->getColumnDimension('AD');
                $columnAD->setWidth(8.88);
                $columnAE = $objPHPExcel->getActiveSheet()->getColumnDimension('AE');
                $columnAE->setWidth(16);
                $columnAF = $objPHPExcel->getActiveSheet()->getColumnDimension('AF');
                $columnAF->setWidth(16);
                $columnAG = $objPHPExcel->getActiveSheet()->getColumnDimension('AG');
                $columnAG->setWidth(31.38);
                $columnAH = $objPHPExcel->getActiveSheet()->getColumnDimension('AH');
                $columnAH->setWidth(16);
                $columnAI = $objPHPExcel->getActiveSheet()->getColumnDimension('AI');
                $columnAI->setWidth(21.5);
                $columnAJ = $objPHPExcel->getActiveSheet()->getColumnDimension('AJ');
                $columnAJ->setWidth(16);
                $columnAK = $objPHPExcel->getActiveSheet()->getColumnDimension('AK');
                $columnAK->setWidth(27.5);
                $columnAL = $objPHPExcel->getActiveSheet()->getColumnDimension('AL');
                $columnAL->setWidth(16);
                $columnAM = $objPHPExcel->getActiveSheet()->getColumnDimension('AM');
                $columnAM->setWidth(21.5);
                //20240228 caina del s
                // $columnAN = $objPHPExcel->getActiveSheet()->getColumnDimension('AN');
                // $columnAN->setWidth(15.63);
                // $columnAO = $objPHPExcel->getActiveSheet()->getColumnDimension('AO');
                // $columnAO->setWidth(27.5);
                //20240228 caina del e

                $sum = 2;
                for ($a = 0; $a < count($pdfDTRes['data']['data']); $a++) {
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $sum, $pdfDTRes['data']['data'][$a]['KAMOK_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $sum, $pdfDTRes['data']['data'][$a]['KAMOK_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $sum, $pdfDTRes['data']['data'][$a]['SUB_KAMOK_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $sum, $pdfDTRes['data']['data'][$a]['SUB_KAMOK_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $sum, $pdfDTRes['data']['data'][$a]['KAMOK_INDEX']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $sum, $pdfDTRes['data']['data'][$a]['TAX']);
                    //20240228 caina UPD s
                    // $objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $sum, $pdfDTRes['data']['data'][$a]['USE_FLG']);
                    // $objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $sum, $pdfDTRes['data']['data'][$a]['USE_FLG_NM']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $sum, $pdfDTRes['data']['data'][$a]['KARI_TAX_KBN'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $sum, $pdfDTRes['data']['data'][$a]['KARI_TAX_KBN_NM']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $sum, $pdfDTRes['data']['data'][$a]['KASI_TAX_KBN'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $sum, $pdfDTRes['data']['data'][$a]['KASI_TAX_KBN_NM']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('K' . $sum, $pdfDTRes['data']['data'][$a]['TAX_SYUBETU_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $sum, $pdfDTRes['data']['data'][$a]['TAX_SYUBETU_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('M' . $sum, $pdfDTRes['data']['data'][$a]['TAX_AUTOCALC_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $sum, $pdfDTRes['data']['data'][$a]['TAX_AUTOCALC_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('O' . $sum, $pdfDTRes['data']['data'][$a]['TAX_HASUU_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $sum, $pdfDTRes['data']['data'][$a]['TAX_HASUU_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('Q' . $sum, $pdfDTRes['data']['data'][$a]['CORP_KBN_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $sum, $pdfDTRes['data']['data'][$a]['CORP_KBN_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('S' . $sum, $pdfDTRes['data']['data'][$a]['SIKINGURI'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('T' . $sum, $pdfDTRes['data']['data'][$a]['KARI_SIKINGURI_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $sum, $pdfDTRes['data']['data'][$a]['KARI_SIKINGURI_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('V' . $sum, $pdfDTRes['data']['data'][$a]['KASI_SIKINGURI_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . $sum, $pdfDTRes['data']['data'][$a]['KASI_SIKINGURI_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . $sum, $pdfDTRes['data']['data'][$a]['SONNEKIBUNKI']);
                    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $sum, $pdfDTRes['data']['data'][$a]['HIYOU_KBN_CD']);
                    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $sum, $pdfDTRes['data']['data'][$a]['HIYOU_KBN_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AA' . $sum, $pdfDTRes['data']['data'][$a]['YOSAN_INPUT_KBN_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $sum, $pdfDTRes['data']['data'][$a]['YOSAN_INPUT_KBN_NAME']);
                    $objPHPExcel->getActiveSheet()->setCellValue('AC' . $sum, $pdfDTRes['data']['data'][$a]['CACHFLOW']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AD' . $sum, $pdfDTRes['data']['data'][$a]['FURI_MOTO_KIN_CD'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AE' . $sum, $pdfDTRes['data']['data'][$a]['FURI_MOTO_KIN'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AF' . $sum, $pdfDTRes['data']['data'][$a]['FURI_SAKI_TYPE_CD1'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AG' . $sum, $pdfDTRes['data']['data'][$a]['FURI_SAKI_TYPE_NAME1'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AH' . $sum, $pdfDTRes['data']['data'][$a]['FURI_SAKI_CD1'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AI' . $sum, $pdfDTRes['data']['data'][$a]['FURI_SAKI_NAME1'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AJ' . $sum, $pdfDTRes['data']['data'][$a]['FURI_SAKI_TYPE_CD2'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AK' . $sum, $pdfDTRes['data']['data'][$a]['FURI_SAKI_TYPE_NAME2'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AL' . $sum, $pdfDTRes['data']['data'][$a]['FURI_SAKI_CD2'], DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('AM' . $sum, $pdfDTRes['data']['data'][$a]['FURI_SAKI_NAME2'], DataType::TYPE_STRING);
                    //20240228 caina UPD e
                    $sum++;
                }
                $sum = $sum - 1;
                //20240228 caina upd s
                // $objPHPExcel->getActiveSheet()->getStyle('A2:AO' . $sum)->getFont()->setBold(false)->setName('ＭＳ Ｐゴシック	')->setSize(11);
                // $objPHPExcel->getActiveSheet()->getStyle('A1:AO' . $sum)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A2:AM' . $sum)->getFont()->setBold(false)->setName('ＭＳ Ｐゴシック	')->setSize(11);
                $objPHPExcel->getActiveSheet()->getStyle('A1:AM' . $sum)->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                //20240228 caina upd e
            }

            //创建一个新的sheet[先创建再设置]
            else
                if ($tablename == 'HDK_MST_SHZKBN') {
                    $objPHPExcel->setActiveSheetIndex(1);
                    $objPHPExcel->getActiveSheet()->setCellValue('A1', '消費税区分コード');
                    $objPHPExcel->getActiveSheet()->setCellValue('B1', '消費税区分名');
                    $objPHPExcel->getActiveSheet()->setCellValue('C1', '申告書計算区分コード');
                    $objPHPExcel->getActiveSheet()->setCellValue('D1', '申告書計算区分名');
                    $objPHPExcel->getActiveSheet()->setCellValue('E1', '略称');
                    $objPHPExcel->getActiveSheet()->setCellValue('F1', '背景色');
                    $objPHPExcel->getActiveSheet()->setCellValue('G1', '表示コード');
                    $objPHPExcel->getActiveSheet()->setCellValue('H1', '表示');
                    //sheet名の設定
                    $objPHPExcel->getActiveSheet()->setTitle('消費税区分');
                    //スタイル設定
                    $objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setName('ＭＳ Ｐゴシック	')->setSize(11)->getColor()->setRGB('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('36648B');
                    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                    $objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                    $objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                    $columnA = $objPHPExcel->getActiveSheet()->getColumnDimension('A');
                    $columnA->setWidth(17.63);
                    $columnB = $objPHPExcel->getActiveSheet()->getColumnDimension('B');
                    $columnB->setWidth(43.25);
                    $columnC = $objPHPExcel->getActiveSheet()->getColumnDimension('C');
                    $columnC->setWidth(22.63);
                    $columnD = $objPHPExcel->getActiveSheet()->getColumnDimension('D');
                    $columnD->setWidth(43.25);
                    $columnE = $objPHPExcel->getActiveSheet()->getColumnDimension('E');
                    $columnE->setWidth(19.63);
                    $columnF = $objPHPExcel->getActiveSheet()->getColumnDimension('F');
                    $columnF->setWidth(7.88);
                    $columnG = $objPHPExcel->getActiveSheet()->getColumnDimension('G');
                    $columnG->setWidth(10.88);
                    $columnH = $objPHPExcel->getActiveSheet()->getColumnDimension('H');
                    $columnH->setWidth(16);

                    $sum = 2;
                    for ($a = 0; $a < count($pdfDTRes['data']['data']); $a++) {
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $sum, $pdfDTRes['data']['data'][$a]['TAX_KBN_CD'], DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $sum, $pdfDTRes['data']['data'][$a]['TAX_KBN_NAME']);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $sum, $pdfDTRes['data']['data'][$a]['DECLARATION_KBN_CD'], DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->setCellValue('D' . $sum, $pdfDTRes['data']['data'][$a]['DECLARATION_KBN_NAME']);
                        $objPHPExcel->getActiveSheet()->setCellValue('E' . $sum, $pdfDTRes['data']['data'][$a]['NICKNAME']);
                        $objPHPExcel->getActiveSheet()->setCellValue('F' . $sum, $pdfDTRes['data']['data'][$a]['BACKCOLOR']);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $sum, $pdfDTRes['data']['data'][$a]['DISP_CD'], DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->setCellValue('H' . $sum, $pdfDTRes['data']['data'][$a]['DISP_KBN']);
                        $sum++;
                    }
                    $sum = $sum - 1;
                    $objPHPExcel->getActiveSheet()->getStyle('A2:H' . $sum)->getFont()->setBold(false)->setName('ＭＳ Ｐゴシック	')->setSize(11)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:H' . $sum)->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);
                } else
                    if ($tablename == 'HDK_MST_TORIHIKISAKI') {
                        $objPHPExcel->setActiveSheetIndex(1);
                        $objPHPExcel->getActiveSheet()->setCellValue('A1', '取引先コード');
                        $objPHPExcel->getActiveSheet()->setCellValue('B1', '法人番号');
                        $objPHPExcel->getActiveSheet()->setCellValue('C1', '取引先名');
                        $objPHPExcel->getActiveSheet()->setCellValue('D1', '事業所名');
                        $objPHPExcel->getActiveSheet()->setCellValue('E1', '取引先名カナ');
                        $objPHPExcel->getActiveSheet()->setCellValue('F1', '事業所名カナ');
                        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'インデックス');
                        $objPHPExcel->getActiveSheet()->setCellValue('H1', '有効期間（開始）');
                        $objPHPExcel->getActiveSheet()->setCellValue('I1', '有効期間（終了）');
                        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'インボイス登録区分コード');
                        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'インボイス登録区分');
                        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'インボイス登録番号');
                        $objPHPExcel->getActiveSheet()->setCellValue('M1', '郵便番号');
                        $objPHPExcel->getActiveSheet()->setCellValue('N1', '都道府県');
                        $objPHPExcel->getActiveSheet()->setCellValue('O1', '市区町村');
                        $objPHPExcel->getActiveSheet()->setCellValue('P1', '番地');
                        $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'ビル等');
                        $objPHPExcel->getActiveSheet()->setCellValue('R1', '電話番号');
                        $objPHPExcel->getActiveSheet()->setCellValue('S1', 'ＦＡＸ番号');
                        $objPHPExcel->getActiveSheet()->setCellValue('T1', 'メモ１');
                        $objPHPExcel->getActiveSheet()->setCellValue('U1', 'メモ2');
                        $objPHPExcel->getActiveSheet()->setCellValue('V1', 'メモ3');
                        //sheet名の設定
                        $objPHPExcel->getActiveSheet()->setTitle('取引先データ作成');
                        //スタイル設定
                        $objPHPExcel->getActiveSheet()->getStyle("A1:V1")->getFont()->setName('ＭＳ Ｐゴシック	')->setSize(11)->getColor()->setRGB('FFFFFF');
                        $objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('36648B');
                        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
                        $objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD);
                        $objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD);
                        $objPHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('O')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('P')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('R')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('T')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('U')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objPHPExcel->getActiveSheet()->getStyle('V')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        // 设置列宽
                        $columnA = $objPHPExcel->getActiveSheet()->getColumnDimension('A');
                        $columnA->setWidth(15.75);
                        $columnB = $objPHPExcel->getActiveSheet()->getColumnDimension('B');
                        $columnB->setWidth(9.88);
                        $columnC = $objPHPExcel->getActiveSheet()->getColumnDimension('C');
                        $columnC->setWidth(41.25);
                        $columnD = $objPHPExcel->getActiveSheet()->getColumnDimension('D');
                        $columnD->setWidth(28.40);
                        $columnE = $objPHPExcel->getActiveSheet()->getColumnDimension('E');
                        $columnE->setWidth(39.25);
                        $columnF = $objPHPExcel->getActiveSheet()->getColumnDimension('F');
                        $columnF->setWidth(22.71);
                        $columnG = $objPHPExcel->getActiveSheet()->getColumnDimension('G');
                        $columnG->setWidth(24.63);
                        $columnH = $objPHPExcel->getActiveSheet()->getColumnDimension('H');
                        $columnH->setWidth(17.63);
                        $columnI = $objPHPExcel->getActiveSheet()->getColumnDimension('I');
                        $columnI->setWidth(17.63);
                        $columnJ = $objPHPExcel->getActiveSheet()->getColumnDimension('J');
                        $columnJ->setWidth(17.63);
                        $columnK = $objPHPExcel->getActiveSheet()->getColumnDimension('K');
                        $columnK->setWidth(24.63);
                        $columnL = $objPHPExcel->getActiveSheet()->getColumnDimension('L');
                        $columnL->setWidth(21.63);
                        $columnM = $objPHPExcel->getActiveSheet()->getColumnDimension('M');
                        $columnM->setWidth(10.88);
                        $columnN = $objPHPExcel->getActiveSheet()->getColumnDimension('N');
                        $columnN->setWidth(10.88);
                        $columnO = $objPHPExcel->getActiveSheet()->getColumnDimension('O');
                        $columnO->setWidth(10.88);
                        $columnP = $objPHPExcel->getActiveSheet()->getColumnDimension('P');
                        $columnP->setWidth(10.88);
                        $columnQ = $objPHPExcel->getActiveSheet()->getColumnDimension('Q');
                        $columnQ->setWidth(10.88);
                        $columnR = $objPHPExcel->getActiveSheet()->getColumnDimension('R');
                        $columnR->setWidth(10.88);
                        $columnS = $objPHPExcel->getActiveSheet()->getColumnDimension('S');
                        $columnS->setWidth(14.88);
                        $columnT = $objPHPExcel->getActiveSheet()->getColumnDimension('T');
                        $columnT->setWidth(10.88);
                        $columnU = $objPHPExcel->getActiveSheet()->getColumnDimension('U');
                        $columnU->setWidth(10.88);
                        $columnV = $objPHPExcel->getActiveSheet()->getColumnDimension('V');
                        $columnV->setWidth(10.88);
                        $sum = 2;
                        for ($a = 0; $a < count($pdfDTRes['data']['data']); $a++) {
                            if ($pdfDTRes['data']['data'][$a]['START_DATE'] != '' && $pdfDTRes['data']['data'][$a]['START_DATE'] != null) {
                                $START_DATE = strtotime($pdfDTRes['data']['data'][$a]['START_DATE']);
                                $START_DATE_LAST = date('Y-m-d', $START_DATE);
                            } else {
                                $START_DATE_LAST = '';
                            }
                            if ($pdfDTRes['data']['data'][$a]['END_DATE'] != '' && $pdfDTRes['data']['data'][$a]['END_DATE'] != null) {
                                $END_DATE = strtotime($pdfDTRes['data']['data'][$a]['END_DATE']);
                                $END_DATE_LAST = date('Y-m-d', $END_DATE);
                            } else {
                                $END_DATE_LAST = '';
                            }
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $sum, $pdfDTRes['data']['data'][$a]['TORIHIKISAKI_CD'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $sum, $pdfDTRes['data']['data'][$a]['HOUJIN_NO'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValue('C' . $sum, $pdfDTRes['data']['data'][$a]['TORIHIKISAKI_NAME']);
                            $objPHPExcel->getActiveSheet()->setCellValue('D' . $sum, $pdfDTRes['data']['data'][$a]['JIGYOUSYO_NM']);
                            $objPHPExcel->getActiveSheet()->setCellValue('E' . $sum, $pdfDTRes['data']['data'][$a]['TORIHIKISAKI_KANA']);
                            $objPHPExcel->getActiveSheet()->setCellValue('F' . $sum, $pdfDTRes['data']['data'][$a]['JIGYOUSYO_KANA']);
                            $objPHPExcel->getActiveSheet()->setCellValue('G' . $sum, $pdfDTRes['data']['data'][$a]['TORIHIKISAKI_INDEX']);
                            $objPHPExcel->getActiveSheet()->setCellValue('H' . $sum, $START_DATE_LAST);
                            $objPHPExcel->getActiveSheet()->setCellValue('I' . $sum, $END_DATE_LAST);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('J' . $sum, $pdfDTRes['data']['data'][$a]['INVOICE_TOUROKU_KBN_CD'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValue('K' . $sum, $pdfDTRes['data']['data'][$a]['INVOICE_TOUROKU_KBN']);
                            $objPHPExcel->getActiveSheet()->setCellValue('L' . $sum, $pdfDTRes['data']['data'][$a]['INVOICE_TOUROKU_NO']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('M' . $sum, $pdfDTRes['data']['data'][$a]['POST_CODE'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $sum, $pdfDTRes['data']['data'][$a]['TODOUFUKEN'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('O' . $sum, $pdfDTRes['data']['data'][$a]['SIKUCYOUSON'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('P' . $sum, $pdfDTRes['data']['data'][$a]['BANNTI'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('Q' . $sum, $pdfDTRes['data']['data'][$a]['BILL_NAME'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('R' . $sum, $pdfDTRes['data']['data'][$a]['TEL'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('S' . $sum, $pdfDTRes['data']['data'][$a]['FAX'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('T' . $sum, $pdfDTRes['data']['data'][$a]['MEMO1'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('U' . $sum, $pdfDTRes['data']['data'][$a]['MEMO2'], DataType::TYPE_STRING);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit('V' . $sum, $pdfDTRes['data']['data'][$a]['MEMO3'], DataType::TYPE_STRING);
                            $sum++;
                        }
                        $sum = $sum - 1;
                        $objPHPExcel->getActiveSheet()->getStyle('A2:V' . $sum)->getFont()->setBold(false)->setName('ＭＳ Ｐゴシック	')->setSize(11);
                        $objPHPExcel->getActiveSheet()->getStyle('A1:V' . $sum)->getBorders()
                            ->getAllBorders()
                            ->setBorderStyle(Border::BORDER_THIN);
                    } else
                        if ($tablename == 'HDK_MST_BUMON') {
                            $objPHPExcel->setActiveSheetIndex(1);
                            $objPHPExcel->getActiveSheet()->setCellValue('A1', '部門コード');
                            $objPHPExcel->getActiveSheet()->setCellValue('B1', '部門名');
                            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'インデックス');
                            $objPHPExcel->getActiveSheet()->setCellValue('D1', '部署区分');
                            $objPHPExcel->getActiveSheet()->setCellValue('E1', '使用フラグ');
                            $objPHPExcel->getActiveSheet()->setCellValue('F1', '使用フラグ名');

                            //sheet名の設定
                            $objPHPExcel->getActiveSheet()->setTitle('部門');
                            //スタイル設定
                            $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setName('ＭＳ Ｐゴシック	')->setSize(11)->getColor()->setRGB('FFFFFF');
                            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('36648B');
                            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                            $objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                            $objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                            $columnA = $objPHPExcel->getActiveSheet()->getColumnDimension('A');
                            $columnA->setWidth(18.63);
                            $columnB = $objPHPExcel->getActiveSheet()->getColumnDimension('B');
                            $columnB->setWidth(43.25);
                            $columnC = $objPHPExcel->getActiveSheet()->getColumnDimension('C');
                            $columnC->setWidth(25.63);
                            $columnD = $objPHPExcel->getActiveSheet()->getColumnDimension('D');
                            $columnD->setWidth(12.88);
                            $columnE = $objPHPExcel->getActiveSheet()->getColumnDimension('E');
                            $columnE->setWidth(12.88);
                            $columnF = $objPHPExcel->getActiveSheet()->getColumnDimension('F');
                            $columnF->setWidth(14.88);

                            $sum = 2;
                            for ($a = 0; $a < count($pdfDTRes['data']['data']); $a++) {
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $sum, $pdfDTRes['data']['data'][$a]['BUSYO_CD'], DataType::TYPE_STRING);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $sum, $pdfDTRes['data']['data'][$a]['BUSYO_NM'], DataType::TYPE_STRING);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $sum, $pdfDTRes['data']['data'][$a]['BUSYO_KANANM'], DataType::TYPE_STRING);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $sum, $pdfDTRes['data']['data'][$a]['BUSYO_KB'], DataType::TYPE_STRING);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $sum, $pdfDTRes['data']['data'][$a]['USE_FLG'], DataType::TYPE_STRING);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $sum, $pdfDTRes['data']['data'][$a]['USE_FLG_NM'], DataType::TYPE_STRING);
                                $sum++;
                            }
                            $sum = $sum - 1;
                            $objPHPExcel->getActiveSheet()->getStyle('A2:F' . $sum)->getFont()->setBold(false)->setName('ＭＳ Ｐゴシック	')->setSize(11)->applyFromArray($styleArray);
                            $objPHPExcel->getActiveSheet()->getStyle('A1:F' . $sum)->getBorders()
                                ->getAllBorders()
                                ->setBorderStyle(Border::BORDER_THIN);
                        } else
                            if ($tablename == 'HDK_MST_BANK') {
                                $objPHPExcel->setActiveSheetIndex(1);
                                $objPHPExcel->getActiveSheet()->setCellValue('A1', '金融機関コード');
                                $objPHPExcel->getActiveSheet()->setCellValue('B1', '金融機関支店コード');
                                $objPHPExcel->getActiveSheet()->setCellValue('C1', '金融機関名漢字');
                                $objPHPExcel->getActiveSheet()->setCellValue('D1', '金融機関名カナ');
                                $objPHPExcel->getActiveSheet()->setCellValue('E1', '金融機関支店名漢字　');
                                $objPHPExcel->getActiveSheet()->setCellValue('F1', '金融機関支店名カナ　');

                                //sheet名の設定
                                $objPHPExcel->getActiveSheet()->setTitle('金融機関マスタ');
                                //スタイル設定
                                $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setName('ＭＳ Ｐゴシック	')->setSize(11)->getColor()->setRGB('000000');
                                $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('C6E0B4');
                                $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                                $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                $objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                                $columnA = $objPHPExcel->getActiveSheet()->getColumnDimension('A');
                                $columnA->setWidth(18.63);
                                $columnB = $objPHPExcel->getActiveSheet()->getColumnDimension('B');
                                $columnB->setWidth(25.63);
                                $columnC = $objPHPExcel->getActiveSheet()->getColumnDimension('C');
                                $columnC->setWidth(43.25);
                                $columnD = $objPHPExcel->getActiveSheet()->getColumnDimension('D');
                                $columnD->setWidth(18.63);
                                $columnE = $objPHPExcel->getActiveSheet()->getColumnDimension('E');
                                $columnE->setWidth(29.88);
                                $columnF = $objPHPExcel->getActiveSheet()->getColumnDimension('F');
                                $columnF->setWidth(21.63);

                                $sum = 2;
                                for ($a = 0; $a < count($pdfDTRes['data']['data']); $a++) {
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $sum, $pdfDTRes['data']['data'][$a]['BANK_CD'], DataType::TYPE_STRING);
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $sum, $pdfDTRes['data']['data'][$a]['BRANCH_CD'], DataType::TYPE_STRING);
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $sum, $pdfDTRes['data']['data'][$a]['BANK_NM'], DataType::TYPE_STRING);
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $sum, $pdfDTRes['data']['data'][$a]['BANK_KANA'], DataType::TYPE_STRING);
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $sum, $pdfDTRes['data']['data'][$a]['BRANCH_NM'], DataType::TYPE_STRING);
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $sum, $pdfDTRes['data']['data'][$a]['BRANCH_KANA'], DataType::TYPE_STRING);
                                    $sum++;
                                }
                                $sum = $sum - 1;
                                $objPHPExcel->getActiveSheet()->getStyle('A2:F' . $sum)->getFont()->setBold(false)->setName('ＭＳ Ｐゴシック	')->setSize(11)->applyFromArray($styleArray);
                            }
            $objPHPExcel->removeSheetByIndex(0);
            $objPHPExcel->setActiveSheetIndex(0);
            //ブック作成
            $objWriter = new WriterXlsx($objPHPExcel);
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objPHPExcel, $objPHPExcel);
            if ($tablename == 'HDK_MST_KAMOKU') {
                $file = "files/HDKAIKEI/" . "OBC科目一覧.xlsx";
            }
            if ($tablename == 'HDK_MST_SHZKBN') {
                $file = "files/HDKAIKEI/" . "OBC消費税区分一覧.xlsx";
            }
            if ($tablename == 'HDK_MST_TORIHIKISAKI') {
                $file = "files/HDKAIKEI/" . "OBC取引先登録一覧.xlsx";
            }
            if ($tablename == 'HDK_MST_BUMON') {
                $file = "files/HDKAIKEI/" . "OBC部門一覧.xlsx";
            }
            if ($tablename == 'HDK_MST_BANK') {
                $file = "files/HDKAIKEI/" . "金融機関一覧.xlsx";
            }

            $result['data'] = $file;
            $result['report'] = $file;
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }
        return $result;
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
            //            $pathUpLoad = $strPath . "/" . $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiUpLoad');
            $pathUpLoad = $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiUpLoad');

            if (!file_exists($pathUpLoad)) {
                if (!mkdir($pathUpLoad, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($pathUpLoad, 0777);
            }
            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $file_name = $_FILES["file"]["name"];
                $this->uploadfile = $pathUpLoad . $file_name;
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
        //POST方式的request，直接echo.
        $this->fncCheckFileReturn($result);
    }

    public function btnActionClick()
    {
        $blnTranFlg = FALSE;
        //トランザクションflg
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $this->HDKOBCDataExpImp = new HDKOBCDataExpImp();
        try {
            ini_set('memory_limit', '-1');
            $tablename = $_POST['data']['selecttable'];
            $filename = $_POST['data']['filename'];
            $txtPath = $_POST['data'];

            $strPath = dirname(dirname(dirname(__FILE__)));

            //            $pathUpLoad = $strPath . "/" . $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiUpLoad');
            $pathUpLoad = $this->ClsComFncHDKAIKEI->FncGetPath('HdkaikeiUpLoad');
            $strTemplatePath = $pathUpLoad . $filename;
            $this->Session = $this->request->getSession();
            $txtPath = $this->Session->read('login_user') . "_" . $filename;
            //トランザクション開始
            $this->HDKOBCDataExpImp->Do_transaction();
            $blnTranFlg = TRUE;
            //Excel取込処理
            $objReader = new Xlsx();
            $objPHPExcel = $objReader->load($strTemplatePath);

            $rowdata = array();
            $errList = array();
            //读取excel文件中的第一个工作表
            $sheet = $objPHPExcel->getSheet(0);
            //取得最大的行号
            $allRow = $sheet->getHighestRow() - 1;
            if ($tablename == 'HDK_MST_KAMOKU') {
                $sumrow = 2;
                for ($a = 0; $a < $allRow; $a++) {
                    $KAMOK_CD = $objPHPExcel->getActiveSheet()->getCell("A" . $sumrow)->getValue();
                    $KAMOK_NAME = $objPHPExcel->getActiveSheet()->getCell("B" . $sumrow)->getValue();
                    $SUB_KAMOK_CD = $objPHPExcel->getActiveSheet()->getCell("C" . $sumrow)->getValue();
                    $SUB_KAMOK_NAME = $objPHPExcel->getActiveSheet()->getCell("D" . $sumrow)->getValue();
                    $KAMOK_INDEX = $objPHPExcel->getActiveSheet()->getCell("E" . $sumrow)->getValue();
                    $TAX = $objPHPExcel->getActiveSheet()->getCell("F" . $sumrow)->getValue();
                    //20240228 caina UPD s
                    // $USE_FLG = $objPHPExcel->getActiveSheet()->getCell("G" . $sumrow)->getValue();
                    // $USE_FLG_NM = $objPHPExcel->getActiveSheet()->getCell("H" . $sumrow)->getValue();
                    $KARI_TAX_KBN = $objPHPExcel->getActiveSheet()->getCell("G" . $sumrow)->getValue();
                    $KARI_TAX_KBN_NM = $objPHPExcel->getActiveSheet()->getCell("H" . $sumrow)->getValue();
                    $KASI_TAX_KBN = $objPHPExcel->getActiveSheet()->getCell("I" . $sumrow)->getValue();
                    $KASI_TAX_KBN_NM = $objPHPExcel->getActiveSheet()->getCell("J" . $sumrow)->getValue();
                    $TAX_SYUBETU_CD = $objPHPExcel->getActiveSheet()->getCell("K" . $sumrow)->getValue();
                    $TAX_SYUBETU_NAME = $objPHPExcel->getActiveSheet()->getCell("L" . $sumrow)->getValue();
                    $TAX_AUTOCALC_CD = $objPHPExcel->getActiveSheet()->getCell("M" . $sumrow)->getValue();
                    $TAX_AUTOCALC_NAME = $objPHPExcel->getActiveSheet()->getCell("N" . $sumrow)->getValue();
                    $TAX_HASUU_CD = $objPHPExcel->getActiveSheet()->getCell("O" . $sumrow)->getValue();
                    $TAX_HASUU_NAME = $objPHPExcel->getActiveSheet()->getCell("P" . $sumrow)->getValue();
                    $CORP_KBN_CD = $objPHPExcel->getActiveSheet()->getCell("Q" . $sumrow)->getValue();
                    $CORP_KBN_NAME = $objPHPExcel->getActiveSheet()->getCell("R" . $sumrow)->getValue();
                    $SIKINGURI = $objPHPExcel->getActiveSheet()->getCell("S" . $sumrow)->getValue();
                    $KARI_SIKINGURI_CD = $objPHPExcel->getActiveSheet()->getCell("T" . $sumrow)->getValue();
                    $KARI_SIKINGURI_NAME = $objPHPExcel->getActiveSheet()->getCell("U" . $sumrow)->getValue();
                    $KASI_SIKINGURI_CD = $objPHPExcel->getActiveSheet()->getCell("V" . $sumrow)->getValue();
                    $KASI_SIKINGURI_NAME = $objPHPExcel->getActiveSheet()->getCell("W" . $sumrow)->getValue();
                    $SONNEKIBUNKI = $objPHPExcel->getActiveSheet()->getCell("X" . $sumrow)->getValue();
                    $HIYOU_KBN_CD = $objPHPExcel->getActiveSheet()->getCell("Y" . $sumrow)->getValue();
                    $HIYOU_KBN_NAME = $objPHPExcel->getActiveSheet()->getCell("Z" . $sumrow)->getValue();
                    $YOSAN_INPUT_KBN_CD = $objPHPExcel->getActiveSheet()->getCell("AA" . $sumrow)->getValue();
                    $YOSAN_INPUT_KBN_NAME = $objPHPExcel->getActiveSheet()->getCell("AB" . $sumrow)->getValue();
                    $CACHFLOW = $objPHPExcel->getActiveSheet()->getCell("AC" . $sumrow)->getValue();
                    $FURI_MOTO_KIN_CD = $objPHPExcel->getActiveSheet()->getCell("AD" . $sumrow)->getValue();
                    $FURI_MOTO_KIN = $objPHPExcel->getActiveSheet()->getCell("AE" . $sumrow)->getValue();
                    $FURI_SAKI_TYPE_CD1 = $objPHPExcel->getActiveSheet()->getCell("AF" . $sumrow)->getValue();
                    $FURI_SAKI_TYPE_NAME1 = $objPHPExcel->getActiveSheet()->getCell("AG" . $sumrow)->getValue();
                    $FURI_SAKI_CD1 = $objPHPExcel->getActiveSheet()->getCell("AH" . $sumrow)->getValue();
                    $FURI_SAKI_NAME1 = $objPHPExcel->getActiveSheet()->getCell("AI" . $sumrow)->getValue();
                    $FURI_SAKI_TYPE_CD2 = $objPHPExcel->getActiveSheet()->getCell("AJ" . $sumrow)->getValue();
                    $FURI_SAKI_TYPE_NAME2 = $objPHPExcel->getActiveSheet()->getCell("AK" . $sumrow)->getValue();
                    $FURI_SAKI_CD2 = $objPHPExcel->getActiveSheet()->getCell("AL" . $sumrow)->getValue();
                    $FURI_SAKI_NAME2 = $objPHPExcel->getActiveSheet()->getCell("AM" . $sumrow)->getValue();
                    //20240228 caina UPD e

                    if ($KAMOK_CD != '' && $SUB_KAMOK_CD != '') {
                        // 20250423 lujunxia upd s
                        // $rowdata[$a]["KAMOK_CD"] = $KAMOK_CD;
                        // $rowdata[$a]["KAMOK_NAME"] = $KAMOK_NAME;
                        // $rowdata[$a]["SUB_KAMOK_CD"] = $SUB_KAMOK_CD;
                        // $rowdata[$a]["SUB_KAMOK_NAME"] = $SUB_KAMOK_NAME;
                        // $rowdata[$a]["KAMOK_INDEX"] = $KAMOK_INDEX;
                        // $rowdata[$a]["TAX"] = $TAX;
                        // //20240228 caina DEL s
                        // // $rowdata[$a]["USE_FLG"] = $USE_FLG;
                        // // $rowdata[$a]["USE_FLG_NM"] = $USE_FLG_NM;
                        // //20240228 caina DEL E
                        // $rowdata[$a]["KARI_TAX_KBN"] = $KARI_TAX_KBN;
                        // $rowdata[$a]["KARI_TAX_KBN_NM"] = $KARI_TAX_KBN_NM;
                        // $rowdata[$a]["KASI_TAX_KBN"] = $KASI_TAX_KBN;
                        // $rowdata[$a]["KASI_TAX_KBN_NM"] = $KASI_TAX_KBN_NM;
                        // $rowdata[$a]["TAX_SYUBETU_CD"] = $TAX_SYUBETU_CD;
                        // $rowdata[$a]["TAX_SYUBETU_NAME"] = $TAX_SYUBETU_NAME;
                        // $rowdata[$a]["TAX_AUTOCALC_CD"] = $TAX_AUTOCALC_CD;
                        // $rowdata[$a]["TAX_AUTOCALC_NAME"] = $TAX_AUTOCALC_NAME;
                        // $rowdata[$a]["TAX_HASUU_CD"] = $TAX_HASUU_CD;
                        // $rowdata[$a]["TAX_HASUU_NAME"] = $TAX_HASUU_NAME;
                        // $rowdata[$a]["CORP_KBN_CD"] = $CORP_KBN_CD;
                        // $rowdata[$a]["CORP_KBN_NAME"] = $CORP_KBN_NAME;
                        // $rowdata[$a]["SIKINGURI"] = $SIKINGURI;
                        // $rowdata[$a]["KARI_SIKINGURI_CD"] = $KARI_SIKINGURI_CD;
                        // $rowdata[$a]["KARI_SIKINGURI_NAME"] = $KARI_SIKINGURI_NAME;
                        // $rowdata[$a]["KASI_SIKINGURI_CD"] = $KASI_SIKINGURI_CD;
                        // $rowdata[$a]["KASI_SIKINGURI_NAME"] = $KASI_SIKINGURI_NAME;
                        // $rowdata[$a]["SONNEKIBUNKI"] = $SONNEKIBUNKI;
                        // $rowdata[$a]["HIYOU_KBN_CD"] = $HIYOU_KBN_CD;
                        // $rowdata[$a]["HIYOU_KBN_NAME"] = $HIYOU_KBN_NAME;
                        // $rowdata[$a]["YOSAN_INPUT_KBN_CD"] = $YOSAN_INPUT_KBN_CD;
                        // $rowdata[$a]["YOSAN_INPUT_KBN_NAME"] = $YOSAN_INPUT_KBN_NAME;
                        // $rowdata[$a]["CACHFLOW"] = $CACHFLOW;
                        // $rowdata[$a]["FURI_MOTO_KIN_CD"] = $FURI_MOTO_KIN_CD;
                        // $rowdata[$a]["FURI_MOTO_KIN"] = $FURI_MOTO_KIN;
                        // $rowdata[$a]["FURI_SAKI_TYPE_CD1"] = $FURI_SAKI_TYPE_CD1;
                        // $rowdata[$a]["FURI_SAKI_TYPE_NAME1"] = $FURI_SAKI_TYPE_NAME1;
                        // $rowdata[$a]["FURI_SAKI_CD1"] = $FURI_SAKI_CD1;
                        // $rowdata[$a]["FURI_SAKI_NAME1"] = $FURI_SAKI_NAME1;
                        // $rowdata[$a]["FURI_SAKI_TYPE_CD2"] = $FURI_SAKI_TYPE_CD2;
                        // $rowdata[$a]["FURI_SAKI_TYPE_NAME2"] = $FURI_SAKI_TYPE_NAME2;
                        // $rowdata[$a]["FURI_SAKI_CD2"] = $FURI_SAKI_CD2;
                        // $rowdata[$a]["FURI_SAKI_NAME2"] = $FURI_SAKI_NAME2;

                        $rowdata[$a]["KAMOK_CD"] = str_replace("'", "''", $KAMOK_CD);
                        $rowdata[$a]["KAMOK_NAME"] = str_replace("'", "''", $KAMOK_NAME);
                        $rowdata[$a]["SUB_KAMOK_CD"] = str_replace("'", "''", $SUB_KAMOK_CD);
                        $rowdata[$a]["SUB_KAMOK_NAME"] = str_replace("'", "''", $SUB_KAMOK_NAME);
                        $rowdata[$a]["KAMOK_INDEX"] = str_replace("'", "''", $KAMOK_INDEX);
                        $rowdata[$a]["TAX"] = str_replace("'", "''", $TAX);
                        $rowdata[$a]["KARI_TAX_KBN"] = str_replace("'", "''", $KARI_TAX_KBN);
                        $rowdata[$a]["KARI_TAX_KBN_NM"] = str_replace("'", "''", $KARI_TAX_KBN_NM);
                        $rowdata[$a]["KASI_TAX_KBN"] = str_replace("'", "''", $KASI_TAX_KBN);
                        $rowdata[$a]["KASI_TAX_KBN_NM"] = str_replace("'", "''", $KASI_TAX_KBN_NM);
                        $rowdata[$a]["TAX_SYUBETU_CD"] = str_replace("'", "''", $TAX_SYUBETU_CD);
                        $rowdata[$a]["TAX_SYUBETU_NAME"] = str_replace("'", "''", $TAX_SYUBETU_NAME);
                        $rowdata[$a]["TAX_AUTOCALC_CD"] = str_replace("'", "''", $TAX_AUTOCALC_CD);
                        $rowdata[$a]["TAX_AUTOCALC_NAME"] = str_replace("'", "''", $TAX_AUTOCALC_NAME);
                        $rowdata[$a]["TAX_HASUU_CD"] = str_replace("'", "''", $TAX_HASUU_CD);
                        $rowdata[$a]["TAX_HASUU_NAME"] = str_replace("'", "''", $TAX_HASUU_NAME);
                        $rowdata[$a]["CORP_KBN_CD"] = str_replace("'", "''", $CORP_KBN_CD);
                        $rowdata[$a]["CORP_KBN_NAME"] = str_replace("'", "''", $CORP_KBN_NAME);
                        $rowdata[$a]["SIKINGURI"] = str_replace("'", "''", $SIKINGURI);
                        $rowdata[$a]["KARI_SIKINGURI_CD"] = str_replace("'", "''", $KARI_SIKINGURI_CD);
                        $rowdata[$a]["KARI_SIKINGURI_NAME"] = str_replace("'", "''", $KARI_SIKINGURI_NAME);
                        $rowdata[$a]["KASI_SIKINGURI_CD"] = str_replace("'", "''", $KASI_SIKINGURI_CD);
                        $rowdata[$a]["KASI_SIKINGURI_NAME"] = str_replace("'", "''", $KASI_SIKINGURI_NAME);
                        $rowdata[$a]["SONNEKIBUNKI"] = str_replace("'", "''", $SONNEKIBUNKI);
                        $rowdata[$a]["HIYOU_KBN_CD"] = str_replace("'", "''", $HIYOU_KBN_CD);
                        $rowdata[$a]["HIYOU_KBN_NAME"] = str_replace("'", "''", $HIYOU_KBN_NAME);
                        $rowdata[$a]["YOSAN_INPUT_KBN_CD"] = str_replace("'", "''", $YOSAN_INPUT_KBN_CD);
                        $rowdata[$a]["YOSAN_INPUT_KBN_NAME"] = str_replace("'", "''", $YOSAN_INPUT_KBN_NAME);
                        $rowdata[$a]["CACHFLOW"] = str_replace("'", "''", $CACHFLOW);
                        $rowdata[$a]["FURI_MOTO_KIN_CD"] = str_replace("'", "''", $FURI_MOTO_KIN_CD);
                        $rowdata[$a]["FURI_MOTO_KIN"] = str_replace("'", "''", $FURI_MOTO_KIN);
                        $rowdata[$a]["FURI_SAKI_TYPE_CD1"] = str_replace("'", "''", $FURI_SAKI_TYPE_CD1);
                        $rowdata[$a]["FURI_SAKI_TYPE_NAME1"] = str_replace("'", "''", $FURI_SAKI_TYPE_NAME1);
                        $rowdata[$a]["FURI_SAKI_CD1"] = str_replace("'", "''", $FURI_SAKI_CD1);
                        $rowdata[$a]["FURI_SAKI_NAME1"] = str_replace("'", "''", $FURI_SAKI_NAME1);
                        $rowdata[$a]["FURI_SAKI_TYPE_CD2"] = str_replace("'", "''", $FURI_SAKI_TYPE_CD2);
                        $rowdata[$a]["FURI_SAKI_TYPE_NAME2"] = str_replace("'", "''", $FURI_SAKI_TYPE_NAME2);
                        $rowdata[$a]["FURI_SAKI_CD2"] = str_replace("'", "''", $FURI_SAKI_CD2);
                        $rowdata[$a]["FURI_SAKI_NAME2"] = str_replace("'", "''", $FURI_SAKI_NAME2);
                        // 20250423 lujunxia upd e
                    } else {
                        array_push($errList, $sumrow);
                    }
                    $sumrow++;
                }
                if (!empty($errList)) {
                    throw new \Exception('エラーがありました。行：' . implode('，', $errList));
                }
            }
            if ($tablename == 'HDK_MST_SHZKBN') {
                $sumrow = 2;
                for ($a = 0; $a < $allRow; $a++) {
                    $TAX_KBN_CD = $objPHPExcel->getActiveSheet()->getCell("A" . $sumrow)->getValue();
                    $TAX_KBN_NAME = $objPHPExcel->getActiveSheet()->getCell("B" . $sumrow)->getValue();
                    $DECLARATION_KBN_CD = $objPHPExcel->getActiveSheet()->getCell("C" . $sumrow)->getValue();
                    $DECLARATION_KBN_NAME = $objPHPExcel->getActiveSheet()->getCell("D" . $sumrow)->getValue();
                    $NICKNAME = $objPHPExcel->getActiveSheet()->getCell("E" . $sumrow)->getValue();
                    $BACKCOLOR = $objPHPExcel->getActiveSheet()->getCell("F" . $sumrow)->getValue();
                    $DISP_CD = $objPHPExcel->getActiveSheet()->getCell("G" . $sumrow)->getValue();
                    $DISP_KBN = $objPHPExcel->getActiveSheet()->getCell("H" . $sumrow)->getValue();

                    if ($TAX_KBN_CD != '') {
                        // 20250423 lujunxia upd s
                        // $rowdata[$a]["TAX_KBN_CD"] = $TAX_KBN_CD;
                        // $rowdata[$a]["TAX_KBN_NAME"] = $TAX_KBN_NAME;
                        // $rowdata[$a]["DECLARATION_KBN_CD"] = $DECLARATION_KBN_CD;
                        // $rowdata[$a]["DECLARATION_KBN_NAME"] = $DECLARATION_KBN_NAME;
                        // $rowdata[$a]["NICKNAME"] = $NICKNAME;
                        // $rowdata[$a]["BACKCOLOR"] = $BACKCOLOR;
                        // $rowdata[$a]["DISP_CD"] = $DISP_CD;
                        // $rowdata[$a]["DISP_KBN"] = $DISP_KBN;

                        $rowdata[$a]["TAX_KBN_CD"] = str_replace("'", "''", $TAX_KBN_CD);
                        $rowdata[$a]["TAX_KBN_NAME"] = str_replace("'", "''", $TAX_KBN_NAME);
                        $rowdata[$a]["DECLARATION_KBN_CD"] = str_replace("'", "''", $DECLARATION_KBN_CD);
                        $rowdata[$a]["DECLARATION_KBN_NAME"] = str_replace("'", "''", $DECLARATION_KBN_NAME);
                        $rowdata[$a]["NICKNAME"] = str_replace("'", "''", $NICKNAME);
                        $rowdata[$a]["BACKCOLOR"] = str_replace("'", "''", $BACKCOLOR);
                        $rowdata[$a]["DISP_CD"] = str_replace("'", "''", $DISP_CD);
                        $rowdata[$a]["DISP_KBN"] = str_replace("'", "''", $DISP_KBN);
                        // 20250423 lujunxia upd e
                    } else {
                        array_push($errList, $sumrow);
                    }
                    $sumrow++;
                }
                if (!empty($errList)) {
                    throw new \Exception('エラーがありました。行：' . implode('，', $errList));
                }
            }
            if ($tablename == 'HDK_MST_TORIHIKISAKI') {
                $sumrow = 2;
                for ($a = 0; $a < $allRow; $a++) {
                    $TORIHIKISAKI_CD = $objPHPExcel->getActiveSheet()->getCell("A" . $sumrow)->getValue();
                    $HOUJIN_NO = $objPHPExcel->getActiveSheet()->getCell("B" . $sumrow)->getValue();
                    $TORIHIKISAKI_NAME = $objPHPExcel->getActiveSheet()->getCell("C" . $sumrow)->getValue();
                    $JIGYOUSYO_NM = $objPHPExcel->getActiveSheet()->getCell("D" . $sumrow)->getValue();
                    $TORIHIKISAKI_KANA = $objPHPExcel->getActiveSheet()->getCell("E" . $sumrow)->getValue();
                    $JIGYOUSYO_KANA = $objPHPExcel->getActiveSheet()->getCell("F" . $sumrow)->getValue();
                    $TORIHIKISAKI_INDEX = $objPHPExcel->getActiveSheet()->getCell("G" . $sumrow)->getValue();
                    $START_DATE = $objPHPExcel->getActiveSheet()->getCell("H" . $sumrow)->getValue();
                    $datatypeS = $objPHPExcel->getActiveSheet()->getStyle("H" . $sumrow)->getNumberFormat()->getFormatCode();
                    $excelTypeS = $objPHPExcel->getActiveSheet()->getCell("H" . $sumrow)->getDataType();
                    $END_DATE = $objPHPExcel->getActiveSheet()->getCell("I" . $sumrow)->getValue();
                    $datatypeE = $objPHPExcel->getActiveSheet()->getStyle("I" . $sumrow)->getNumberFormat()->getFormatCode();
                    $excelTypeE = $objPHPExcel->getActiveSheet()->getCell("I" . $sumrow)->getDataType();
                    $INVOICE_TOUROKU_KBN_CD = $objPHPExcel->getActiveSheet()->getCell("J" . $sumrow)->getValue();
                    $INVOICE_TOUROKU_KBN = $objPHPExcel->getActiveSheet()->getCell("K" . $sumrow)->getValue();
                    $INVOICE_TOUROKU_NO = $objPHPExcel->getActiveSheet()->getCell("L" . $sumrow)->getValue();
                    $POST_CODE = $objPHPExcel->getActiveSheet()->getCell("M" . $sumrow)->getValue();
                    $TODOUFUKEN = $objPHPExcel->getActiveSheet()->getCell("N" . $sumrow)->getValue();
                    $SIKUCYOUSON = $objPHPExcel->getActiveSheet()->getCell("O" . $sumrow)->getValue();
                    $BANNTI = $objPHPExcel->getActiveSheet()->getCell("P" . $sumrow)->getValue();
                    $BILL_NAME = $objPHPExcel->getActiveSheet()->getCell("Q" . $sumrow)->getValue();
                    $TEL = $objPHPExcel->getActiveSheet()->getCell("R" . $sumrow)->getValue();
                    $FAX = $objPHPExcel->getActiveSheet()->getCell("S" . $sumrow)->getValue();
                    $MEMO1 = $objPHPExcel->getActiveSheet()->getCell("T" . $sumrow)->getValue();
                    $MEMO2 = $objPHPExcel->getActiveSheet()->getCell("U" . $sumrow)->getValue();
                    $MEMO3 = $objPHPExcel->getActiveSheet()->getCell("V" . $sumrow)->getValue();

                    if ($START_DATE != '' && $excelTypeS == 's') {
                        $START_DATE_LAST = strtotime($START_DATE); // 将指定日期转成时间戳
                        if ($START_DATE_LAST == '') {
                            throw new \Exception('日付フォーマットエラー。');
                        }
                    } else if ($START_DATE != '') {
                        $START_DATE_LAST = strtotime($START_DATE);
                        if ($START_DATE_LAST == '') {
                            if ($datatypeS == 'yyyy/mm/dd' || $datatypeS == 'yyyy\-mm\-dd' || $datatypeS == 'mm-dd-yy') {
                                $date = Date::excelToTimestamp((int) $START_DATE);
                                $START_DATE_LAST = date('Y-m-d', $date);
                                $START_DATE_LAST = strtotime($START_DATE_LAST);
                            } else {
                                throw new \Exception('日付フォーマットエラー。');
                            }
                        }
                    } else {
                        $START_DATE_LAST = '';
                    }

                    if ($END_DATE != '' && $excelTypeE == 's') {
                        $END_DATE_LAST = strtotime($END_DATE); // 将指定日期转成时间戳
                        if ($END_DATE_LAST == '') {
                            throw new \Exception('日付フォーマットエラー。');
                        }
                    } else if ($END_DATE != '') {
                        $END_DATE_LAST = strtotime($END_DATE);
                        if ($END_DATE_LAST == '') {
                            if ($datatypeE == 'yyyy/mm/dd' || $datatypeE == 'yyyy\-mm\-dd' || $datatypeE == 'mm-dd-yy') {
                                $date = Date::excelToTimestamp((int) $END_DATE);
                                $END_DATE_LAST = date('Y-m-d', $date);
                                $END_DATE_LAST = strtotime($END_DATE_LAST);
                            } else {
                                throw new \Exception('日付フォーマットエラー。');
                            }
                        }
                    } else {
                        $END_DATE_LAST = '';
                    }
                    if ($TORIHIKISAKI_CD != '') {
                        // 20250423 lujunxia upd s
                        // $rowdata[$a]["TORIHIKISAKI_CD"] = $TORIHIKISAKI_CD;
                        // $rowdata[$a]["HOUJIN_NO"] = $HOUJIN_NO;
                        // $rowdata[$a]["TORIHIKISAKI_NAME"] = $TORIHIKISAKI_NAME;
                        // $rowdata[$a]["JIGYOUSYO_NM"] = $JIGYOUSYO_NM;
                        // $rowdata[$a]["TORIHIKISAKI_KANA"] = $TORIHIKISAKI_KANA;
                        // $rowdata[$a]["JIGYOUSYO_KANA"] = $JIGYOUSYO_KANA;
                        // $rowdata[$a]["TORIHIKISAKI_INDEX"] = $TORIHIKISAKI_INDEX;
                        // $rowdata[$a]["START_DATE"] = $START_DATE_LAST;
                        // $rowdata[$a]["END_DATE"] = $END_DATE_LAST;
                        // $rowdata[$a]["INVOICE_TOUROKU_KBN_CD"] = $INVOICE_TOUROKU_KBN_CD;
                        // $rowdata[$a]["INVOICE_TOUROKU_KBN"] = $INVOICE_TOUROKU_KBN;
                        // $rowdata[$a]["INVOICE_TOUROKU_NO"] = $INVOICE_TOUROKU_NO;
                        // $rowdata[$a]["POST_CODE"] = $POST_CODE;
                        // $rowdata[$a]["TODOUFUKEN"] = $TODOUFUKEN;
                        // $rowdata[$a]["SIKUCYOUSON"] = $SIKUCYOUSON;
                        // $rowdata[$a]["BANNTI"] = $BANNTI;
                        // $rowdata[$a]["BILL_NAME"] = $BILL_NAME;
                        // $rowdata[$a]["TEL"] = $TEL;
                        // $rowdata[$a]["FAX"] = $FAX;
                        // $rowdata[$a]["MEMO1"] = $MEMO1;
                        // $rowdata[$a]["MEMO2"] = $MEMO2;
                        // $rowdata[$a]["MEMO3"] = $MEMO3;

                        $rowdata[$a]["TORIHIKISAKI_CD"] = str_replace("'", "''", $TORIHIKISAKI_CD);
                        $rowdata[$a]["HOUJIN_NO"] = str_replace("'", "''", $HOUJIN_NO);
                        $rowdata[$a]["TORIHIKISAKI_NAME"] = str_replace("'", "''", $TORIHIKISAKI_NAME);
                        $rowdata[$a]["JIGYOUSYO_NM"] = str_replace("'", "''", $JIGYOUSYO_NM);
                        $rowdata[$a]["TORIHIKISAKI_KANA"] = str_replace("'", "''", $TORIHIKISAKI_KANA);
                        $rowdata[$a]["JIGYOUSYO_KANA"] = str_replace("'", "''", $JIGYOUSYO_KANA);
                        $rowdata[$a]["TORIHIKISAKI_INDEX"] = str_replace("'", "''", $TORIHIKISAKI_INDEX);
                        $rowdata[$a]["START_DATE"] = str_replace("'", "''", $START_DATE_LAST);
                        $rowdata[$a]["END_DATE"] = str_replace("'", "''", $END_DATE_LAST);
                        $rowdata[$a]["INVOICE_TOUROKU_KBN_CD"] = str_replace("'", "''", $INVOICE_TOUROKU_KBN_CD);
                        $rowdata[$a]["INVOICE_TOUROKU_KBN"] = str_replace("'", "''", $INVOICE_TOUROKU_KBN);
                        $rowdata[$a]["INVOICE_TOUROKU_NO"] = str_replace("'", "''", $INVOICE_TOUROKU_NO);
                        $rowdata[$a]["POST_CODE"] = str_replace("'", "''", $POST_CODE);
                        $rowdata[$a]["TODOUFUKEN"] = str_replace("'", "''", $TODOUFUKEN);
                        $rowdata[$a]["SIKUCYOUSON"] = str_replace("'", "''", $SIKUCYOUSON);
                        $rowdata[$a]["BANNTI"] = str_replace("'", "''", $BANNTI);
                        $rowdata[$a]["BILL_NAME"] = str_replace("'", "''", $BILL_NAME);
                        $rowdata[$a]["TEL"] = str_replace("'", "''", $TEL);
                        $rowdata[$a]["FAX"] = str_replace("'", "''", $FAX);
                        $rowdata[$a]["MEMO1"] = str_replace("'", "''", $MEMO1);
                        $rowdata[$a]["MEMO2"] = str_replace("'", "''", $MEMO2);
                        $rowdata[$a]["MEMO3"] = str_replace("'", "''", $MEMO3);
                        // 20250423 lujunxia upd e
                    } else {
                        array_push($errList, $sumrow);
                    }
                    $sumrow++;
                }
                if (!empty($errList)) {
                    throw new \Exception('エラーがありました。行：' . implode('，', $errList));
                }
            }
            if ($tablename == 'HDK_MST_BUMON') {
                $sumrow = 2;
                for ($a = 0; $a < $allRow; $a++) {
                    $BUSYO_CD = $objPHPExcel->getActiveSheet()->getCell("A" . $sumrow)->getValue();
                    $BUSYO_NM = $objPHPExcel->getActiveSheet()->getCell("B" . $sumrow)->getValue();
                    $BUSYO_KANANM = $objPHPExcel->getActiveSheet()->getCell("C" . $sumrow)->getValue();
                    $BUSYO_KB = $objPHPExcel->getActiveSheet()->getCell("D" . $sumrow)->getValue();
                    $USE_FLG = $objPHPExcel->getActiveSheet()->getCell("E" . $sumrow)->getValue();
                    $USE_FLG_NM = $objPHPExcel->getActiveSheet()->getCell("F" . $sumrow)->getValue();


                    if ($BUSYO_CD != '') {
                        // 20250423 lujunxia upd s
                        // $rowdata[$a]["BUSYO_CD"] = $BUSYO_CD;
                        // $rowdata[$a]["BUSYO_NM"] = $BUSYO_NM;
                        // $rowdata[$a]["BUSYO_KANANM"] = $BUSYO_KANANM;
                        // $rowdata[$a]["BUSYO_KB"] = $BUSYO_KB;
                        // $rowdata[$a]["USE_FLG"] = $USE_FLG;
                        // $rowdata[$a]["USE_FLG_NM"] = $USE_FLG_NM;

                        $rowdata[$a]["BUSYO_CD"] = str_replace("'", "''", $BUSYO_CD);
                        $rowdata[$a]["BUSYO_NM"] = str_replace("'", "''", $BUSYO_NM);
                        $rowdata[$a]["BUSYO_KANANM"] = str_replace("'", "''", $BUSYO_KANANM);
                        $rowdata[$a]["BUSYO_KB"] = str_replace("'", "''", $BUSYO_KB);
                        $rowdata[$a]["USE_FLG"] = str_replace("'", "''", $USE_FLG);
                        $rowdata[$a]["USE_FLG_NM"] = str_replace("'", "''", $USE_FLG_NM);
                        // 20250423 lujunxia upd e
                    } else {
                        array_push($errList, $sumrow);
                    }
                    $sumrow++;
                }
                if (!empty($errList)) {
                    throw new \Exception('エラーがありました。行：' . implode('，', $errList));
                }
            }
            if ($tablename == 'HDK_MST_BANK') {
                $sumrow = 2;
                for ($a = 0; $a < $allRow; $a++) {
                    $BANK_CD = $objPHPExcel->getActiveSheet()->getCell("A" . $sumrow)->getValue();
                    $BRANCH_CD = $objPHPExcel->getActiveSheet()->getCell("B" . $sumrow)->getValue();
                    $BANK_NM = $objPHPExcel->getActiveSheet()->getCell("C" . $sumrow)->getValue();
                    $BANK_KANA = $objPHPExcel->getActiveSheet()->getCell("D" . $sumrow)->getValue();
                    $BRANCH_NM = $objPHPExcel->getActiveSheet()->getCell("E" . $sumrow)->getValue();
                    $BRANCH_KANA = $objPHPExcel->getActiveSheet()->getCell("F" . $sumrow)->getValue();

                    if ($BANK_CD != '') {
                        // 20250423 lujunxia upd s
                        // $rowdata[$a]["BANK_CD"] = $BANK_CD;
                        // $rowdata[$a]["BRANCH_CD"] = $BRANCH_CD;
                        // $rowdata[$a]["BANK_NM"] = $BANK_NM;
                        // $rowdata[$a]["BANK_KANA"] = $BANK_KANA;
                        // $rowdata[$a]["BRANCH_NM"] = $BRANCH_NM;
                        // $rowdata[$a]["BRANCH_KANA"] = $BRANCH_KANA;

                        $rowdata[$a]["BANK_CD"] = str_replace("'", "''", $BANK_CD);
                        $rowdata[$a]["BRANCH_CD"] = str_replace("'", "''", $BRANCH_CD);
                        $rowdata[$a]["BANK_NM"] = str_replace("'", "''", $BANK_NM);
                        $rowdata[$a]["BANK_KANA"] = str_replace("'", "''", $BANK_KANA);
                        $rowdata[$a]["BRANCH_NM"] = str_replace("'", "''", $BRANCH_NM);
                        $rowdata[$a]["BRANCH_KANA"] = str_replace("'", "''", $BRANCH_KANA);
                        // 20250423 lujunxia upd e
                    } else {
                        array_push($errList, $sumrow);
                    }
                    $sumrow++;
                }
                if (!empty($errList)) {
                    throw new \Exception('エラーがありました。行：' . implode('，', $errList));
                }
            }
            if ($tablename == 'HDK_MST_KAMOKU') {
                foreach ($rowdata as $key => $value) {
                    $resultexis = $this->HDKOBCDataExpImp->existKdata($rowdata[$key]);
                    if (!$resultexis['result']) {
                        throw new \Exception($resultexis['data']);
                    }
                    if (isset($resultexis['data'][0]['KAMOK_CD']) && isset($resultexis['data'][0]['SUB_KAMOK_CD'])) {
                        $resultK = $this->HDKOBCDataExpImp->UpdateKdata($rowdata[$key]);
                        if (!$resultK['result']) {
                            throw new \Exception($resultK['data']);
                        }
                    } else {
                        $resultInsK = $this->HDKOBCDataExpImp->InsertKdata($rowdata[$key]);
                        if (!$resultInsK['result']) {
                            throw new \Exception($resultInsK['data']);
                        }
                    }
                }
            } else
                if ($tablename == 'HDK_MST_SHZKBN') {
                    $resultdel = $this->HDKOBCDataExpImp->Deldata($tablename);
                    if (!$resultdel['result']) {
                        throw new \Exception($resultdel['data']);
                    }
                    foreach ($rowdata as $key => $value) {
                        $resultK = $this->HDKOBCDataExpImp->InsertSdata($rowdata[$key]);
                        if (!$resultK['result']) {
                            throw new \Exception($resultK['data']);
                        }
                    }

                } else
                    if ($tablename == 'HDK_MST_TORIHIKISAKI') {
                        $resultdel = $this->HDKOBCDataExpImp->Deldata($tablename);
                        if (!$resultdel['result']) {
                            throw new \Exception($resultdel['data']);
                        }
                        foreach ($rowdata as $key => $value) {
                            $resultK = $this->HDKOBCDataExpImp->InsertTdata($rowdata[$key]);
                            if (!$resultK['result']) {
                                throw new \Exception($resultK['data']);
                            }
                        }

                    } else
                        if ($tablename == 'HDK_MST_BUMON') {
                            $resultdel = $this->HDKOBCDataExpImp->Deldata($tablename);
                            if (!$resultdel['result']) {
                                throw new \Exception($resultdel['data']);
                            }
                            foreach ($rowdata as $key => $value) {
                                $resultK = $this->HDKOBCDataExpImp->InsertBdata($rowdata[$key]);
                                if (!$resultK['result']) {
                                    throw new \Exception($resultK['data']);
                                }
                            }

                        } else
                            if ($tablename == 'HDK_MST_BANK') {
                                $resultdel = $this->HDKOBCDataExpImp->Deldata($tablename);
                                if (!$resultdel['result']) {
                                    throw new \Exception($resultdel['data']);
                                }
                                foreach ($rowdata as $key => $value) {
                                    $resultK = $this->HDKOBCDataExpImp->InsertBankdata($rowdata[$key]);
                                    if (!$resultK['result']) {
                                        throw new \Exception($resultK['data']);
                                    }
                                }

                            }
            if (isset($strTemplatePath) && file_exists($strTemplatePath)) {
                unlink($strTemplatePath);
            }
            //トランザクション終了
            $this->HDKOBCDataExpImp->Do_commit();
            $blnTranFlg = FALSE;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($blnTranFlg == TRUE) {
                $this->HDKOBCDataExpImp->Do_rollback();
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

    //ファイルのアップロード
    public function changeFileName($param)
    {
        $this->HDKOBCDataExpImp = new HDKOBCDataExpImp();
        $strUserID = $this->HDKOBCDataExpImp->GS_LOGINUSER['strUserID'];
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

}
