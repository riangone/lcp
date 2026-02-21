<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSeikyuHakko;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border as PHPExcel_Style_Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment as PHPExcel_Style_Alignment;

class FrmSeikyuHakkoController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSeikyuHakko = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }
    public function index()
    {
        $this->render('index', 'FrmSeikyuHakko_layout');
    }

    public function frmLeaseUriageMeisaiLoad()
    {
        $result = array();
        try {
            $this->FrmSeikyuHakko = new FrmSeikyuHakko();
            $result = $this->FrmSeikyuHakko->fncSelect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdActionClick()
    {
        $result = array();
        $cboYM = "";
        $cboYM1 = "";
        $intState = 0;
        $lngOutCnt = 0;
        try {
            $cboYM = $_POST['data']['cboYM'];
            $cboYM1 = $_POST['data']['cboYM1'];
            //ログ管理
            $intState = 9;
            $this->FrmSeikyuHakko = new FrmSeikyuHakko();
            $result = $this->FrmSeikyuHakko->fncPrintSelect($cboYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //印刷処理
            if (count($result['data']) > 0) {
                $lngOutCnt = count($result['data']);
                //'プレビュー表示
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptSeikyuHakko1.inc';
                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();
                $totalArr = array();
                $current_CNT = "";
                $last_CNT = "";
                foreach ($result['data'] as $key => $value) {
                    //For example:登録№ 久留米501.It's length>the width,so just show 久留米. Start.
                    $RIKUJI_Len = mb_strwidth($value['RIKUJI_CD'], 'UTF-8');
                    $TOURK_NO1_Len = mb_strwidth($value['TOURK_NO1'], 'UTF-8');
                    if ($TOURK_NO1_Len >= 9) {
                        $result['data'][$key]['TOURK_NO1'] = $value['RIKUJI_CD'];
                    } elseif ($RIKUJI_Len >= 8) {
                        $result['data'][$key]['TOURK_NO1'] = $value['RIKUJI_CD'];
                    }
                    //End.
                    $current_CNT = $value['CNT'];
                    if ($last_CNT === "") {
                        foreach ($total as $key1 => $value1) {
                            $total[$key1] = (string) ($value1 + $value[$key1]);
                        }
                    } elseif ($current_CNT == $last_CNT) {
                        foreach ($total as $key2 => $value2) {
                            $total[$key2] = (string) ($value2 + $value[$key2]);
                        }
                    } else {
                        array_push($totalArr, $total);
                        $total = $total1;
                        foreach ($total as $key3 => $value3) {
                            $total[$key3] = (string) ($value3 + $value[$key3]);
                        }
                    }
                    $last_CNT = $current_CNT;
                }
                array_push($totalArr, $total);
                array_push($result['data'], $totalArr);
                array_push($tmp_data, $result['data']);
                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "8";
                $datas["rptSeikyuHakko1"] = $tmp;
                $rpx_file_names["rptSeikyuHakko1"] = $data;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                //set PageSize 364*257  B4
                $wid_and_hei = array(
                    "364",
                    //20240524 lujunxia PHP8 upd s
                    //"257"
                    "257.1"
                    //20240524 lujunxia PHP8 upd e
                );
                $pdfPath = $obj->to_pdf2($wid_and_hei);
                $result['pdfmark'] = TRUE;
                $result['pdfpath'] = $pdfPath;
            }
            //ログ管理
            $intState = 1;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //ログ管理 Start
        if ($intState != 0) {
            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmSeikyuHakko_Print", $intState, $lngOutCnt, $cboYM1);
        }
        //ログ管理 End
        $this->fncReturn($result);
    }

    public function button1Click()
    {
        $result = array();
        //ログ管理
        $intState = 0;
        $lngOutCnt = 0;
        try {
            $cboYM = $_POST['data'];
            //ログ管理
            $intState = 9;
            $this->FrmSeikyuHakko = new FrmSeikyuHakko();
            $result = $this->FrmSeikyuHakko->fncPrintSelect(str_replace("/", "", $cboYM));
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if (count($result['data']) == 0) {
                $result['data'] = "I0001";
            } else {
                $lngOutCnt = count($result['data']);
                $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
                $tmpPath2 = "webroot/files/R4k/";
                $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
                $file = $tmpPath . "オートリース請求書.xls";

                if (!file_exists($tmpPath)) {
                    if (!mkdir($tmpPath, 0777, TRUE)) {
                        $result["data"] = "Execl Error";
                        throw new \Exception($result["data"]);
                    }
                }

                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "SeikyuHakkoTemplate.xls";
                //テンプレートファイルの存在確認
                if (file_exists($strTemplatePath) == FALSE) {
                    $result["data"] = "オートリース請求書のテンプレートが見つかりません！";
                    throw new \Exception($result["data"]);
                }
                $spreadsheet = IOFactory::load($strTemplatePath);
                $objActSheet = $spreadsheet->getActiveSheet();

                $intPage = $this->ClsComFnc->fncRoundDou((count($result['data']) - 1) / 20, 0, 0);
                //画表格
                if ($intPage > 0) {
                    $objActSheet->getStyle('A55:U1000')->getFont()->setName('ＭＳ Ｐゴシック');
                    $objActSheet->getStyle('A55:U1000')->getFont()->setSize(10);

                    // //set format start.
                    // $objActSheet -> getStyle('J8') -> getNumberFormat() -> setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                    // $objActSheet -> getStyle('N8') -> getNumberFormat() -> setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                    // //set format end.
                    for ($i = 1; $i <= $intPage; $i++) {
                        //set format start.
                        $objActSheet->getStyle('E' . (13 + $i * 54) . ':' . 'P' . (53 + $i * 54))->getNumberFormat()->setFormatCode("#,###");
                        //set format end.

                        //line 1
                        $objActSheet->getRowDimension(1 + $i * 54)->setRowHeight(24);
                        $objActSheet->setCellValue('A' . (1 + $i * 54), "請求書");
                        $objActSheet->getStyle('A' . (1 + $i * 54))->getFont()->setSize(20);
                        $objActSheet->getStyle('A' . (1 + $i * 54))->getFont()->setBold(true);
                        $objActSheet->getStyle('A' . (1 + $i * 54))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objActSheet->mergeCells('A' . (1 + $i * 54) . ':' . 'S' . (1 + $i * 54));
                        //line 2
                        $objActSheet->getRowDimension(2 + $i * 54)->setRowHeight(11.25);
                        //line 3
                        $objActSheet->getRowDimension(3 + $i * 54)->setRowHeight(12);
                        $objActSheet->getStyle('A' . (3 + $i * 54))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objActSheet->mergeCells('A' . (3 + $i * 54) . ':' . 'S' . (3 + $i * 54));
                        //line 4
                        $objActSheet->getRowDimension(4 + $i * 54)->setRowHeight(11.25);
                        //line 5
                        $objActSheet->getRowDimension(5 + $i * 54)->setRowHeight(13.5);
                        $objActSheet->setCellValue('B' . (5 + $i * 54), '（GD）市南区金屋町2－15');

                        $objActSheet->getStyle('P' . (5 + $i * 54))->getFont()->setSize(10);
                        $objActSheet->mergeCells('P' . (5 + $i * 54) . ':' . 'Q' . (5 + $i * 54));
                        //line 6
                        $objActSheet->getRowDimension(6 + $i * 54)->setRowHeight(13.5);
                        $objActSheet->setCellValue('B' . (6 + $i * 54), '（DZM）オートリース株式会社　御中');

                        $objActSheet->setCellValue('Q' . (6 + $i * 54), "（GD）市中区幟町13番4号");
                        //line 7
                        $objActSheet->getRowDimension(7 + $i * 54)->setRowHeight(13.5);
                        $objActSheet->setCellValue('P' . (7 + $i * 54), '会社名');

                        $objActSheet->setCellValue('Q' . (7 + $i * 54), '株式会社　（GD）（DZM）');
                        $objActSheet->getStyle('Q' . (7 + $i * 54))->getFont()->setBold(true);
                        //line 8
                        $objActSheet->getRowDimension(8 + $i * 54)->setRowHeight(17.25);
                        $objActSheet->setCellValue('H' . (8 + $i * 54), '請　求　総　額 ');
                        $objActSheet->mergeCells('J' . (8 + $i * 54) . ':' . 'K' . (8 + $i * 54));
                        $objActSheet->getStyle('H' . (8 + $i * 54) . ':' . 'K' . (8 + $i * 54))->getFont()->setSize(14);
                        $objActSheet->getStyle('H' . (8 + $i * 54) . ':' . 'K' . (8 + $i * 54))->getFont()->setBold(true);

                        $objActSheet->mergeCells('L' . (8 + $i * 54) . ':' . 'M' . (8 + $i * 54));
                        $objActSheet->setCellValue('L' . (8 + $i * 54), '(内、消費税額 ');
                        $objActSheet->getStyle('L' . (8 + $i * 54))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        $objActSheet->setCellValue('O' . (8 + $i * 54), ')');

                        $objActSheet->setCellValue('P' . (8 + $i * 54), '担当者名');

                        $objActSheet->setCellValue('Q' . (8 + $i * 54), '取締役社長');
                        $objActSheet->getStyle('Q' . (8 + $i * 54) . ':' . 'R' . (8 + $i * 54))->getFont()->setBold(true);

                        $objActSheet->setCellValue('R' . (8 + $i * 54), '（ST）　（ZY）');
                        $objActSheet->getStyle('R' . (8 + $i * 54))->getFont()->setSize(14);

                        $objActSheet->setCellValue('S' . (8 + $i * 54), '㊞');

                        $objActSheet->getStyle('J' . (8 + $i * 54) . ':' . 'O' . (8 + $i * 54))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
                        $objActSheet->getStyle('Q' . (8 + $i * 54) . ':' . 'S' . (8 + $i * 54))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
                        //line 9
                        $objActSheet->getRowDimension(9 + $i * 54)->setRowHeight(11.25);
                        //line 10,11
                        $objActSheet->setCellValue('B' . (10 + $i * 54), '登録№');
                        $objActSheet->setCellValue('C' . (10 + $i * 54), '車台№');
                        $objActSheet->setCellValue('D' . (10 + $i * 54), '登録月日');
                        $objActSheet->setCellValue('E' . (10 + $i * 54), '合計');
                        $objActSheet->setCellValue('F' . (10 + $i * 54), '車両代');
                        $objActSheet->setCellValue('G' . (11 + $i * 54), '消費税');
                        $objActSheet->setCellValue('H' . (10 + $i * 54), '登録手数料');
                        $objActSheet->setCellValue('H' . (11 + $i * 54), '(消費税込)');
                        $objActSheet->setCellValue('I' . (10 + $i * 54), '取得税');
                        $objActSheet->setCellValue('J' . (10 + $i * 54), 'ﾘｻｲｸﾙ料');
                        $objActSheet->setCellValue('K' . (10 + $i * 54), '重量税');
                        $objActSheet->setCellValue('L' . (10 + $i * 54), '自動車税');
                        $objActSheet->setCellValue('M' . (10 + $i * 54), '自賠責');
                        $objActSheet->setCellValue('N' . (10 + $i * 54), '架装費');
                        $objActSheet->setCellValue('O' . (11 + $i * 54), '消費税');
                        $objActSheet->setCellValue('P' . (10 + $i * 54), '運賃');
                        $objActSheet->setCellValue('Q' . (11 + $i * 54), '消費税');
                        $objActSheet->setCellValue('R' . (10 + $i * 54), '備考');
                        $objActSheet->setCellValue('T' . (10 + $i * 54), '注文書№');
                        $objActSheet->setCellValue('U' . (10 + $i * 54), '扱者');

                        $objActSheet->mergeCells('B' . (10 + $i * 54) . ':' . 'B' . (11 + $i * 54));
                        $objActSheet->mergeCells('C' . (10 + $i * 54) . ':' . 'C' . (11 + $i * 54));
                        $objActSheet->mergeCells('D' . (10 + $i * 54) . ':' . 'D' . (11 + $i * 54));
                        $objActSheet->mergeCells('E' . (10 + $i * 54) . ':' . 'E' . (11 + $i * 54));
                        $objActSheet->mergeCells('F' . (10 + $i * 54) . ':' . 'G' . (10 + $i * 54));
                        $objActSheet->mergeCells('I' . (10 + $i * 54) . ':' . 'I' . (11 + $i * 54));
                        $objActSheet->mergeCells('J' . (10 + $i * 54) . ':' . 'J' . (11 + $i * 54));
                        $objActSheet->mergeCells('K' . (10 + $i * 54) . ':' . 'K' . (11 + $i * 54));
                        $objActSheet->mergeCells('L' . (10 + $i * 54) . ':' . 'L' . (11 + $i * 54));
                        $objActSheet->mergeCells('M' . (10 + $i * 54) . ':' . 'M' . (11 + $i * 54));
                        $objActSheet->mergeCells('N' . (10 + $i * 54) . ':' . 'O' . (10 + $i * 54));
                        $objActSheet->mergeCells('P' . (10 + $i * 54) . ':' . 'Q' . (10 + $i * 54));
                        $objActSheet->mergeCells('R' . (10 + $i * 54) . ':' . 'S' . (11 + $i * 54));
                        $objActSheet->mergeCells('T' . (10 + $i * 54) . ':' . 'T' . (11 + $i * 54));
                        $objActSheet->mergeCells('U' . (10 + $i * 54) . ':' . 'U' . (11 + $i * 54));

                        $objActSheet->getStyle('A' . (10 + $i * 54) . ':' . 'U' . (11 + $i * 54))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objActSheet->getStyle('A' . (10 + $i * 54) . ':' . 'U' . (11 + $i * 54))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                        $objActSheet->getStyle('A' . (10 + $i * 54) . ':' . 'U' . (11 + $i * 54))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $objActSheet->getStyle('F' . (10 + $i * 54))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                        $objActSheet->getStyle('F' . (11 + $i * 54))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                        $objActSheet->getStyle('H' . (10 + $i * 54))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                        $objActSheet->getStyle('H' . (11 + $i * 54))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                        $objActSheet->getStyle('N' . (10 + $i * 54))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                        $objActSheet->getStyle('N' . (11 + $i * 54))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                        $objActSheet->getStyle('P' . (10 + $i * 54))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                        $objActSheet->getStyle('P' . (11 + $i * 54))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);

                        $objActSheet->getRowDimension(12 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(13 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(14 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(15 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(16 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(17 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(18 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(19 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(20 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(21 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(22 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(23 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(24 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(25 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(26 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(27 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(28 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(29 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(30 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(31 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(32 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(33 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(34 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(35 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(36 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(37 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(38 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(39 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(40 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(41 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(42 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(43 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(44 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(45 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(46 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(47 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(48 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(49 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(50 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(51 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(52 + $i * 54)->setRowHeight(12);
                        $objActSheet->getRowDimension(53 + $i * 54)->setRowHeight(12);

                        $objActSheet->getStyle('D' . (12 + $i * 54) . ':' . 'D' . (51 + $i * 54))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $objActSheet->getStyle('D' . (12 + $i * 54) . ':' . 'D' . (51 + $i * 54))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objActSheet->mergeCells('D' . (12 + $i * 54) . ':' . 'D' . (13 + $i * 54));
                        $objActSheet->mergeCells('D' . (14 + $i * 54) . ':' . 'D' . (15 + $i * 54));
                        $objActSheet->mergeCells('D' . (16 + $i * 54) . ':' . 'D' . (17 + $i * 54));
                        $objActSheet->mergeCells('D' . (18 + $i * 54) . ':' . 'D' . (19 + $i * 54));
                        $objActSheet->mergeCells('D' . (20 + $i * 54) . ':' . 'D' . (21 + $i * 54));
                        $objActSheet->mergeCells('D' . (22 + $i * 54) . ':' . 'D' . (23 + $i * 54));
                        $objActSheet->mergeCells('D' . (24 + $i * 54) . ':' . 'D' . (25 + $i * 54));
                        $objActSheet->mergeCells('D' . (26 + $i * 54) . ':' . 'D' . (27 + $i * 54));
                        $objActSheet->mergeCells('D' . (28 + $i * 54) . ':' . 'D' . (29 + $i * 54));
                        $objActSheet->mergeCells('D' . (30 + $i * 54) . ':' . 'D' . (31 + $i * 54));
                        $objActSheet->mergeCells('D' . (32 + $i * 54) . ':' . 'D' . (33 + $i * 54));
                        $objActSheet->mergeCells('D' . (34 + $i * 54) . ':' . 'D' . (35 + $i * 54));
                        $objActSheet->mergeCells('D' . (36 + $i * 54) . ':' . 'D' . (37 + $i * 54));
                        $objActSheet->mergeCells('D' . (38 + $i * 54) . ':' . 'D' . (39 + $i * 54));
                        $objActSheet->mergeCells('D' . (40 + $i * 54) . ':' . 'D' . (41 + $i * 54));
                        $objActSheet->mergeCells('D' . (42 + $i * 54) . ':' . 'D' . (43 + $i * 54));
                        $objActSheet->mergeCells('D' . (44 + $i * 54) . ':' . 'D' . (45 + $i * 54));
                        $objActSheet->mergeCells('D' . (46 + $i * 54) . ':' . 'D' . (47 + $i * 54));
                        $objActSheet->mergeCells('D' . (48 + $i * 54) . ':' . 'D' . (49 + $i * 54));
                        $objActSheet->mergeCells('D' . (50 + $i * 54) . ':' . 'D' . (51 + $i * 54));

                        $objActSheet->mergeCells('R' . (12 + $i * 54) . ':' . 'S' . (12 + $i * 54));
                        $objActSheet->mergeCells('R' . (13 + $i * 54) . ':' . 'S' . (13 + $i * 54));
                        $objActSheet->mergeCells('R' . (14 + $i * 54) . ':' . 'S' . (14 + $i * 54));
                        $objActSheet->mergeCells('R' . (15 + $i * 54) . ':' . 'S' . (15 + $i * 54));
                        $objActSheet->mergeCells('R' . (16 + $i * 54) . ':' . 'S' . (16 + $i * 54));
                        $objActSheet->mergeCells('R' . (17 + $i * 54) . ':' . 'S' . (17 + $i * 54));
                        $objActSheet->mergeCells('R' . (18 + $i * 54) . ':' . 'S' . (18 + $i * 54));
                        $objActSheet->mergeCells('R' . (19 + $i * 54) . ':' . 'S' . (19 + $i * 54));
                        $objActSheet->mergeCells('R' . (20 + $i * 54) . ':' . 'S' . (20 + $i * 54));
                        $objActSheet->mergeCells('R' . (21 + $i * 54) . ':' . 'S' . (21 + $i * 54));
                        $objActSheet->mergeCells('R' . (22 + $i * 54) . ':' . 'S' . (22 + $i * 54));
                        $objActSheet->mergeCells('R' . (23 + $i * 54) . ':' . 'S' . (23 + $i * 54));
                        $objActSheet->mergeCells('R' . (24 + $i * 54) . ':' . 'S' . (24 + $i * 54));
                        $objActSheet->mergeCells('R' . (25 + $i * 54) . ':' . 'S' . (25 + $i * 54));
                        $objActSheet->mergeCells('R' . (26 + $i * 54) . ':' . 'S' . (26 + $i * 54));
                        $objActSheet->mergeCells('R' . (27 + $i * 54) . ':' . 'S' . (27 + $i * 54));
                        $objActSheet->mergeCells('R' . (28 + $i * 54) . ':' . 'S' . (28 + $i * 54));
                        $objActSheet->mergeCells('R' . (29 + $i * 54) . ':' . 'S' . (29 + $i * 54));
                        $objActSheet->mergeCells('R' . (30 + $i * 54) . ':' . 'S' . (30 + $i * 54));
                        $objActSheet->mergeCells('R' . (31 + $i * 54) . ':' . 'S' . (31 + $i * 54));
                        $objActSheet->mergeCells('R' . (32 + $i * 54) . ':' . 'S' . (32 + $i * 54));
                        $objActSheet->mergeCells('R' . (33 + $i * 54) . ':' . 'S' . (33 + $i * 54));
                        $objActSheet->mergeCells('R' . (34 + $i * 54) . ':' . 'S' . (34 + $i * 54));
                        $objActSheet->mergeCells('R' . (35 + $i * 54) . ':' . 'S' . (35 + $i * 54));
                        $objActSheet->mergeCells('R' . (36 + $i * 54) . ':' . 'S' . (36 + $i * 54));
                        $objActSheet->mergeCells('R' . (37 + $i * 54) . ':' . 'S' . (37 + $i * 54));
                        $objActSheet->mergeCells('R' . (38 + $i * 54) . ':' . 'S' . (38 + $i * 54));
                        $objActSheet->mergeCells('R' . (39 + $i * 54) . ':' . 'S' . (39 + $i * 54));
                        $objActSheet->mergeCells('R' . (40 + $i * 54) . ':' . 'S' . (40 + $i * 54));
                        $objActSheet->mergeCells('R' . (41 + $i * 54) . ':' . 'S' . (41 + $i * 54));
                        $objActSheet->mergeCells('R' . (42 + $i * 54) . ':' . 'S' . (42 + $i * 54));
                        $objActSheet->mergeCells('R' . (43 + $i * 54) . ':' . 'S' . (43 + $i * 54));
                        $objActSheet->mergeCells('R' . (44 + $i * 54) . ':' . 'S' . (44 + $i * 54));
                        $objActSheet->mergeCells('R' . (45 + $i * 54) . ':' . 'S' . (45 + $i * 54));
                        $objActSheet->mergeCells('R' . (46 + $i * 54) . ':' . 'S' . (46 + $i * 54));
                        $objActSheet->mergeCells('R' . (47 + $i * 54) . ':' . 'S' . (47 + $i * 54));
                        $objActSheet->mergeCells('R' . (48 + $i * 54) . ':' . 'S' . (48 + $i * 54));
                        $objActSheet->mergeCells('R' . (49 + $i * 54) . ':' . 'S' . (49 + $i * 54));
                        $objActSheet->mergeCells('R' . (50 + $i * 54) . ':' . 'S' . (50 + $i * 54));
                        $objActSheet->mergeCells('R' . (51 + $i * 54) . ':' . 'S' . (51 + $i * 54));
                        $objActSheet->mergeCells('R' . (52 + $i * 54) . ':' . 'S' . (52 + $i * 54));
                        $objActSheet->mergeCells('R' . (53 + $i * 54) . ':' . 'S' . (53 + $i * 54));

                        $objActSheet->getStyle('A' . (14 + $i * 54) . ':' . 'U' . (15 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (18 + $i * 54) . ':' . 'U' . (19 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (22 + $i * 54) . ':' . 'U' . (23 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (26 + $i * 54) . ':' . 'U' . (27 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (30 + $i * 54) . ':' . 'U' . (31 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (34 + $i * 54) . ':' . 'U' . (35 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (38 + $i * 54) . ':' . 'U' . (39 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (42 + $i * 54) . ':' . 'U' . (43 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (46 + $i * 54) . ':' . 'U' . (47 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (50 + $i * 54) . ':' . 'U' . (51 + $i * 54))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);

                        $objActSheet->getStyle('P' . (12 + $i * 54) . ':' . 'P' . (52 + $i * 54))->getBorders()->getHorizontal()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('A' . (53 + $i * 54) . ':' . 'U' . (53 + $i * 54))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $objActSheet->getStyle('A' . (12 + $i * 54) . ':' . 'U' . (53 + $i * 54))->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
                        $objActSheet->getStyle('S' . (12 + $i * 54) . ':' . 'S' . (53 + $i * 54))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $objActSheet->getStyle('U' . (12 + $i * 54) . ':' . 'U' . (53 + $i * 54))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        //20240524 lujunxia PHP8 ins s
                        $objActSheet->setSelectedCell('U' . (53 + $i * 54));
                        //20240524 lujunxia PHP8 ins e
                    }
                }
                //添数据
                //初期処理
                $intPage = 0;
                //配列カウント
                $intArrayCnt = 0;
                //連番
                $intRenban = 0;
                //DSカウント
                $lngKensu = 0;

                while ($lngKensu < count($result['data'])) {
                    //列、行およびセルに対し、プロパティや値を設定します。
                    switch ($intArrayCnt) {
                        case 0:
                            //配列番号：0＝改ページ後
                            //登録諸費用明細"印字
                            $objActSheet->setCellValue('A' . (3 + $intPage * 54), $result['data'][0]['TOUGETU'] . " 登 録 諸 費 用 明 細 書");
                            //今日の日付を印字
                            //---20150810 #1964 fanzhengzhou upd s. 
                            //$objActSheet -> setCellValue('P' . (5 + $intPage * 54), $result['data'][0]['TODAY']);
                            $objActSheet->setCellValue('P' . (5 + $intPage * 54), $this->deleteZero($result['data'][0]['TODAY']));
                            //---20150810 #1964 fanzhengzhou upd e.
                            //連番をセット
                            $intRenban = 1;

                            //明細を配列にセット
                            $this->subMeisaiSet($intArrayCnt, $intPage, $intRenban, $lngKensu, $objActSheet, $result['data']);
                            if ($objActSheet->getCell('G' . (13 + $intArrayCnt + $intPage * 54))->getValue() != '0') {
                                $objActSheet->setCellValue('G' . (13 + $intArrayCnt + $intPage * 54), "=ROUNDDOWN(F" . ($intPage * 54 + $intArrayCnt + 13) . "*" . $this->ClsComFnc->FncNz($result['data'][$lngKensu]['SYARYO_SHZ_RT'] . ",0)"));
                            }
                            if ($objActSheet->getCell('O' . (13 + $intArrayCnt + $intPage * 54))->getValue() != '0') {
                                $objActSheet->setCellValue('O' . (13 + $intArrayCnt + $intPage * 54), "=ROUNDDOWN(N" . ($intPage * 54 + $intArrayCnt + 13) . "*" . $this->ClsComFnc->FncNz($result['data'][$lngKensu]['SYARYO_SHZ_RT'] . ",0)"));
                            }

                            //明細(合計)配列にセット
                            $objActSheet->setCellValue('E' . (13 + $intArrayCnt + $intPage * 54), "=SUM(F" . ($intPage * 54 + $intArrayCnt + 13) . ":P" . ($intPage * 54 + $intArrayCnt + 13) . ")");
                            break;
                        default:
                            //明細を配列にセット
                            $this->subMeisaiSet($intArrayCnt, $intPage, $intRenban, $lngKensu, $objActSheet, $result['data']);
                            if ($objActSheet->getCell('G' . (13 + $intArrayCnt + $intPage * 54))->getValue() != '0') {
                                $objActSheet->setCellValue('G' . (13 + $intArrayCnt + $intPage * 54), "=ROUNDDOWN(F" . ($intPage * 54 + $intArrayCnt + 13) . "*" . $this->ClsComFnc->FncNz($result['data'][$lngKensu]['SYARYO_SHZ_RT'] . ",0)"));
                            }
                            if ($objActSheet->getCell('O' . (13 + $intArrayCnt + $intPage * 54))->getValue() != '0') {
                                $objActSheet->setCellValue('O' . (13 + $intArrayCnt + $intPage * 54), "=ROUNDDOWN(N" . ($intPage * 54 + $intArrayCnt + 13) . "*" . $this->ClsComFnc->FncNz($result['data'][$lngKensu]['SYARYO_SHZ_RT'] . ",0)"));
                            }

                            //明細(合計)配列にセット
                            $objActSheet->setCellValue('E' . (13 + $intArrayCnt + $intPage * 54), "=SUM(F" . ($intPage * 54 + $intArrayCnt + 13) . ":P" . ($intPage * 54 + $intArrayCnt + 13) . ")");
                            break;
                    }

                    //配列カウント＞＝38以上で改ページ処理
                    if ($intArrayCnt >= 38) {
                        //小計を明細配列に代入
                        $objActSheet->setCellValue('A' . (53 + $intPage * 54), "小計");
                        $objActSheet->setCellValue('E' . (53 + $intPage * 54), "=Sum(E" . ($intPage * 54 + 12) . ":E" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('F' . (53 + $intPage * 54), "=Sum(F" . ($intPage * 54 + 12) . ":F" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('G' . (53 + $intPage * 54), "=Sum(G" . ($intPage * 54 + 12) . ":G" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('H' . (53 + $intPage * 54), "=Sum(H" . ($intPage * 54 + 12) . ":H" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('I' . (53 + $intPage * 54), "=Sum(I" . ($intPage * 54 + 12) . ":I" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('J' . (53 + $intPage * 54), "=Sum(J" . ($intPage * 54 + 12) . ":J" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('K' . (53 + $intPage * 54), "=Sum(K" . ($intPage * 54 + 12) . ":K" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('L' . (53 + $intPage * 54), "=Sum(L" . ($intPage * 54 + 12) . ":L" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('M' . (53 + $intPage * 54), "=Sum(M" . ($intPage * 54 + 12) . ":M" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('N' . (53 + $intPage * 54), "=Sum(N" . ($intPage * 54 + 12) . ":N" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('O' . (53 + $intPage * 54), "=Sum(O" . ($intPage * 54 + 12) . ":O" . ($intPage * 54 + 51) . ")");
                        $objActSheet->setCellValue('P' . (53 + $intPage * 54), "=Sum(P" . ($intPage * 54 + 12) . ":P" . ($intPage * 54 + 51) . ")");
                        //明細配列をエクセルに出力
                        $intArrayCnt = 0;
                        //配列カウント初期化
                        $intPage += 1;
                        //ページカウントアップ
                        $intRenban = 0;
                        //連番の初期化
                    } else {
                        //次レコード表示位置
                        $intArrayCnt += 2;
                        //1明細2行
                        $intRenban += 1;
                        //連番のカウントアップ
                    }
                    $lngKensu += 1;
                    //DSのカウントアップ
                }
                //ページ最終行でちょうど終わった場合、ページ数がプラス1されているので実際のページ数に戻すため－1する
                if ($intArrayCnt !== 0) {
                    //小計を明細配列に代入
                    $objActSheet->setCellValue('A' . (53 + $intPage * 54), "小計");
                    $objActSheet->setCellValue('E' . (53 + $intPage * 54), "=Sum(E" . ($intPage * 54 + 12) . ":E" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('F' . (53 + $intPage * 54), "=Sum(F" . ($intPage * 54 + 12) . ":F" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('G' . (53 + $intPage * 54), "=Sum(G" . ($intPage * 54 + 12) . ":G" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('H' . (53 + $intPage * 54), "=Sum(H" . ($intPage * 54 + 12) . ":H" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('I' . (53 + $intPage * 54), "=Sum(I" . ($intPage * 54 + 12) . ":I" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('J' . (53 + $intPage * 54), "=Sum(J" . ($intPage * 54 + 12) . ":J" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('K' . (53 + $intPage * 54), "=Sum(K" . ($intPage * 54 + 12) . ":K" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('L' . (53 + $intPage * 54), "=Sum(L" . ($intPage * 54 + 12) . ":L" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('M' . (53 + $intPage * 54), "=Sum(M" . ($intPage * 54 + 12) . ":M" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('N' . (53 + $intPage * 54), "=Sum(N" . ($intPage * 54 + 12) . ":N" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('O' . (53 + $intPage * 54), "=Sum(O" . ($intPage * 54 + 12) . ":O" . ($intPage * 54 + 51) . ")");
                    $objActSheet->setCellValue('P' . (53 + $intPage * 54), "=Sum(P" . ($intPage * 54 + 12) . ":P" . ($intPage * 54 + 51) . ")");
                } elseif ($intArrayCnt === 0) {
                    $intPage -= 1;
                }
                //請求総額の計算式
                $strSeikyuSou = "=E53";
                for ($i = 1; $i <= $intPage; $i++) {
                    $strSeikyuSou = $strSeikyuSou . "+E" . ($i * 54 + 53);
                }
                $objActSheet->setCellValue('J8', $strSeikyuSou);
                //消費税の 計算式
                $strSyohiZei = "=G53+O53";
                for ($i = 1; $i <= $intPage; $i++) {
                    $strSyohiZei = $strSyohiZei . "+G" . ($i * 54 + 53) . "+O" . ($i * 54 + 53);
                }
                $objActSheet->setCellValue('N8', $strSyohiZei);

                $objActSheet->setTitle('請求書');

                $objWriter = IOFactory::createWriter($spreadsheet, IOFactory::WRITER_XLS);
                $objWriter->save($file);
                $result['data'] = 'files/R4k/' . 'オートリース請求書.xls';
            }
            $result['result'] = TRUE;
            //ログ管理
            $intState = 1;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //ログ管理 Start
        if ($intState != 0) {
            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmSeikyuHakko_Excel", $intState, $lngOutCnt, $cboYM);
        }
        //ログ管理 End
        $this->fncReturn($result);
    }

    //明細を配列にセット
    public function subMeisaiSet($intArrayCnt, $intPage, $intRenban, $lngKensu, &$objActSheet, $data)
    {
        $objActSheet->setCellValue('A' . (12 + $intArrayCnt + $intPage * 54), $intRenban);
        $objActSheet->setCellValue('B' . (12 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNv($data[$lngKensu]["TOURK_NO1"]));
        $objActSheet->setCellValue('B' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNv($data[$lngKensu]["TOURK_NO23"]));
        $objActSheet->setCellValue('C' . (12 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNv($data[$lngKensu]["SYADAI"]));
        $objActSheet->setCellValue('C' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNv($data[$lngKensu]["CARNO"]));
        //---20150810 #1964 fanzhengzhou upd s. 
        //$objActSheet -> setCellValue('D' . (12 + $intArrayCnt + $intPage * 54), $this -> ClsComFnc -> FncNv($data[$lngKensu]["TOU_DATE"]));
        $objActSheet->setCellValue('D' . (12 + $intArrayCnt + $intPage * 54), $this->deleteZero($this->ClsComFnc->FncNv($data[$lngKensu]["TOU_DATE"])));
        //---20150810 #1964 fanzhengzhou upd e.
        $objActSheet->setCellValue('F' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["SYARYO_DAI"]));

        $objActSheet->setCellValue('G' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["SYARYO_SHZ"]));
        $objActSheet->setCellValue('H' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["TOUROKURYO"]));
        $objActSheet->setCellValue('I' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["SYARYOU_ZEI"]));
        $objActSheet->setCellValue('J' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["RCYL_GK"]));
        $objActSheet->setCellValue('K' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["JYURYO_ZEI"]));
        $objActSheet->setCellValue('L' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["JIDOUSYA_ZEI"]));
        $objActSheet->setCellValue('M' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["JIBAI_HOK_RYO"]));
        $objActSheet->setCellValue('N' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["KASOUHI"]));

        $objActSheet->setCellValue('O' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["KASOUZEI"]));
        //---20150810 #1964 fanzhengzhou upd s.
        //$objActSheet -> setCellValue('P' . (13 + $intArrayCnt + $intPage * 54), $this -> ClsComFnc -> FncNz($data[$lngKensu]["SITADORI"]) === 0 ? 0 : $this -> ClsComFnc -> FncNz($data[$lngKensu]["SITADORI"] * (-1)));
        $objActSheet->setCellValue('P' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNz($data[$lngKensu]["SITADORI"]) == 0 ? 0 : '▲' . number_format($this->ClsComFnc->FncNz($data[$lngKensu]["SITADORI"])));
        //---20150810 #1964 fanzhengzhou upd e.
        $objActSheet->setCellValue('R' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNv($data[$lngKensu]["MGN_MEI_KNJ1"]));

        $objActSheet->setCellValue('T' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNv($data[$lngKensu]["CMN_NO"]));
        $objActSheet->setCellValue('U' . (13 + $intArrayCnt + $intPage * 54), $this->ClsComFnc->FncNv($data[$lngKensu]["SYAIN_NM"]));

        if ($this->ClsComFnc->FncNz($data[$lngKensu]["SITADORI"]) !== "0") {
            $objActSheet->setCellValue('P' . (12 + $intArrayCnt + $intPage * 54), "下取車");
        }
    }

    //---20150810 #1964 fanzhengzhou add s.
    private function deleteZero($str)
    {
        $strArr = str_split($str);
        foreach ($strArr as $key => $value) {
            if ($value == '0') {
                if (is_numeric($strArr[$key + 1])) {
                    $strArr[$key] = str_replace('0', '', $value);
                }
            }
        }
        return implode('', $strArr);
    }
    //---20150810 #1964 fanzhengzhou add e.
}