<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE220PresentOrderTotal;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment as PHPExcel_Style_Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font as PHPExcel_Style_Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
//*******************************************
// * sample controller
//*******************************************
class HMTVE220PresentOrderTotalController extends AppController
{
    public $autoLayout = TRUE;
    private $HMTVE220PresentOrderTotal;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE220PresentOrderTotal_layout');
    }

    //部署データ取得
    public function getTenpo()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $this->HMTVE220PresentOrderTotal = new HMTVE220PresentOrderTotal();
            $postdata = $_POST['request'];
            $result = $this->HMTVE220PresentOrderTotal->getTenpo($postdata['STARTDT']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncHMTVE->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //ロック解除
    public function btnRemoveClick()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $this->HMTVE220PresentOrderTotal = new HMTVE220PresentOrderTotal();
            $postdata = $_POST['data'];
            $result = $this->HMTVE220PresentOrderTotal->getUpdate0($postdata['STARTDT']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //成約プレゼント確定データに確定ﾌﾗｸﾞ１で更新する
    function updateAgreement($STARTDT)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $this->HMTVE220PresentOrderTotal = new HMTVE220PresentOrderTotal();
            $this->HMTVE220PresentOrderTotal->Do_transaction();
            $blnTran = TRUE;
            $resultChk = $this->HMTVE220PresentOrderTotal->getChk($STARTDT);
            if (!$resultChk['result']) {
                throw new \Exception($resultChk['data']);
            }
            if (count((array) $resultChk['data']) > 0) {
                $resultIns = $this->HMTVE220PresentOrderTotal->getInsert($STARTDT);
                if (!$resultIns['result']) {
                    throw new \Exception('W0006');
                }
            } else {
                $resultUpd = $this->HMTVE220PresentOrderTotal->getUpdate($STARTDT);
                if (!$resultUpd['result']) {
                    throw new \Exception('W0006');
                }
            }
            $this->HMTVE220PresentOrderTotal->Do_commit();
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = 'W0006';

            if ($blnTran) {
                $this->HMTVE220PresentOrderTotal->Do_rollback();
            }

        }
        $result['data'] = '';
        return $result;
    }

    //Excelファイル生成処理
    function makeExcle($STARTDT, $ENDDT)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );

        try {
            $dt1Res = $this->createExcelDataTable1($STARTDT);
            if (!$dt1Res['result']) {
                throw new \Exception($dt1Res['error']);
            }
            $dt2Res = $this->createExcelDataTable2($STARTDT);
            if (!$dt2Res['result']) {
                throw new \Exception($dt2Res['error']);
            }
            if (count((array) $dt1Res['data']) == 0 || count((array) $dt2Res['data']) == 0) {
                throw new \Exception('W0003');
            }
            $dt3Res = $this->createExcelDataTable3($STARTDT);
            if (!$dt3Res['result']) {
                throw new \Exception($dt3Res['error']);
            }
            $dt4Res = $this->createExcelDataTable4($STARTDT);
            if (!$dt4Res['result']) {
                throw new \Exception($dt4Res['error']);
            }

            $strFileName = "展示会成約プレゼント(" . $STARTDT . "～" . $ENDDT . ").XLS";
            $dt1 = $dt1Res['data'];
            $dt2 = $dt2Res['data'];
            $dt3 = $dt3Res['data'];
            $dt4 = $dt4Res['data'];

            //出力先パス
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            //path is exist
            if (file_exists($tmpPath)) {
                $outFolder = $tmpPath1 . "/webroot/files/";
                if (!is_readable($outFolder) && is_writeable($outFolder) && is_executable($outFolder)) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!(is_readable($tmpPath) && is_writeable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && strpos($file, "展示会成約プレゼント") !== false) {
                        $fullpath = $tmpPath . "/" . $file;
                        if (!is_dir($fullpath)) {
                            unlink($fullpath);
                        } else {
                            rmdir($tmpPath);
                        }
                    }
                }
            } else {
                $outFolder = dirname($tmpPath);
                if (!(is_readable($outFolder) && is_writeable($outFolder) && is_executable($outFolder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }

            //Excel生成
            $result = $this->createExcelData($dt1, $dt2, $dt3, $tmpPath, $strFileName, $dt4, $STARTDT, $ENDDT);

            return $result;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            return $result;
        }
    }

    //Excel生成
    function createExcelData($dt1, $dt2, $dt3, $strFilePath, $strFileName, $MaxOrderNo, $STARTDT, $ENDDT)
    {
        $result = array(
            'result' => FALSE,
            'data' => null,
            'error' => ''
        );
        $BeginTempCD = "";
        $rowNum = 4;
        $intSheetCnt = 1;
        $rowCount = 0;

        try {
            $tmpPath1 = dirname(dirname(dirname(__FILE__)));
            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath1 . "TENJIKAISEIYAKUDATA.xls";

            if (!file_exists($strTemplatePath)) {
                throw new \Exception('no templete');
            }

            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);

            $titleStyle = array(
                'font' => array(
                    'size' => 14,
                    'name' => 'ＭＳ Ｐゴシック',
                    'bold' => true,
                ),
                'alignment' => array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ),
            );
            $topleftStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                )
            );
            $topleftbottomStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            );

            $MidLeftStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            );
            $MidMiddleStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            );
            $MidRightStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            );
            $toprightbottomStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            );
            $BotLeftStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            );
            $BotMiddleStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            );
            $BotRighyStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            );
            $fontStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック',
                    'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
                ),
            );

            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            $objActSheet->setCellValue('A2', "展示会成約プレゼント");
            $objActSheet->getStyle('A2')->applyFromArray($titleStyle);

            if ($rowNum > 65528) {
                $intSheetCnt = $intSheetCnt + 1;
                $objActSheet = $objPHPExcel->createSheet($intSheetCnt);
                $rowNum = 3;
                $objActSheet->setCellValue('A2', "展示会成約プレゼント");
                $objActSheet->getStyle('A2')->applyFromArray($titleStyle);
            }

            $objActSheet->setCellValue('A' . $rowNum, " 展示会実施日　" . substr($STARTDT, 0, 4) . '/' . substr($STARTDT, 4, 2) . '/' . substr($STARTDT, 6, 2) . '～' . substr($ENDDT, 0, 4) . '/' . substr($ENDDT, 4, 2) . '/' . substr($ENDDT, 6, 2) . '　　　　　　');
            $objActSheet->getStyle('A' . $rowNum)->applyFromArray($fontStyle);

            $rowNum += 2;

            $objActSheet->getColumnDimension('A')->setWidth(20);
            // $objActSheet -> setCellValueByColumnAndRow(0, $rowNum, $dt1[0][0]);
            $objActSheet->setCellValue('A' . $rowNum, ' ');
            $objActSheet->getStyle(cellCoordinate: 'A' . $rowNum)->applyFromArray($topleftStyle);
            $objActSheet->getStyle(cellCoordinate: 'A' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
            $objActSheet->getStyle(cellCoordinate: 'A' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);
            $objActSheet->getStyle(cellCoordinate: 'A' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
            $objActSheet->getStyle(cellCoordinate: 'A' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

            switch (count($dt1)) {
                case 1:
                    $objActSheet->getColumnDimension('B')->setWidth(20);
                    $objActSheet->setCellValue('B' . $rowNum, $dt1[0][1]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($toprightbottomStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                case 2:
                    $objActSheet->getColumnDimension('B')->setWidth(20);
                    $objActSheet->setCellValue('B' . $rowNum, $dt1[0][1]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);


                    $objActSheet->getColumnDimension('C')->setWidth(20);
                    $objActSheet->setCellValue('C' . $rowNum, $dt1[1][1]);
                    $objActSheet->getStyle('C' . $rowNum)->applyFromArray($toprightbottomStyle);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                case 3:
                    $objActSheet->getColumnDimension('B')->setWidth(20);
                    $objActSheet->setCellValue('B' . $rowNum, $dt1[0][1]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->getColumnDimension('C')->setWidth(20);
                    $objActSheet->setCellValue('C' . $rowNum, $dt1[1][1]);
                    $objActSheet->getStyle('C' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->getColumnDimension('D')->setWidth(20);
                    $objActSheet->setCellValue('D' . $rowNum, $dt1[2][1]);
                    $objActSheet->getStyle('D' . $rowNum)->applyFromArray($toprightbottomStyle);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                case 4:
                    $objActSheet->getColumnDimension('B')->setWidth(20);
                    $objActSheet->setCellValue('B' . $rowNum, $dt1[0][1]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->getColumnDimension('C')->setWidth(20);
                    $objActSheet->setCellValue('C' . $rowNum, $dt1[1][1]);
                    $objActSheet->getStyle('C' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->getColumnDimension('D')->setWidth(20);
                    $objActSheet->setCellValue('D' . $rowNum, $dt1[2][1]);
                    $objActSheet->getStyle('D' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->getColumnDimension('E')->setWidth(20);
                    $objActSheet->setCellValue('E' . $rowNum, $dt1[3][1]);
                    $objActSheet->getStyle('E' . $rowNum)->applyFromArray($toprightbottomStyle);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                case 5:
                    $objActSheet->getColumnDimension('B')->setWidth(20);
                    $objActSheet->setCellValue('B' . $rowNum, $dt1[0][1]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->getColumnDimension('C')->setWidth(20);
                    $objActSheet->setCellValue('C' . $rowNum, $dt1[1][1]);
                    $objActSheet->getStyle('C' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->getColumnDimension('D')->setWidth(20);
                    $objActSheet->setCellValue('D' . $rowNum, $dt1[2][1]);
                    $objActSheet->getStyle('D' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->getColumnDimension('E')->setWidth(20);
                    $objActSheet->setCellValue('E' . $rowNum, $dt1[3][1]);
                    $objActSheet->getStyle('E' . $rowNum)->applyFromArray($topleftbottomStyle);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->getColumnDimension('F')->setWidth(20);
                    $objActSheet->setCellValue('F' . $rowNum, $dt1[4][1]);
                    $objActSheet->getStyle('F' . $rowNum)->applyFromArray($toprightbottomStyle);
                    $objActSheet->getStyle('F' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('F' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('F' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('F' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                default:
                    break;
            }

            for ($i = 0; $i < count($dt2); $i++) {
                if ($BeginTempCD != $dt2[$i][0]) {
                    if ($rowCount == count($dt2) - 1) {
                        $rowCount = $i;
                    } else {
                        $rowCount = $i + 1;
                    }

                    $rowNum += 1;
                }

                switch ($dt2[$i][3]) {
                    case 1:
                        $objActSheet->setCellValue('A' . $rowNum, $dt2[$i][0] . " " . $dt2[$i][1]);
                        $objActSheet->getStyle('A' . $rowNum)->applyFromArray($MidLeftStyle);
                        $objActSheet->getStyle('A' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                        $objActSheet->getStyle('A' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);
                        $objActSheet->getStyle('A' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                        $objActSheet->getStyle('A' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                        $objActSheet->setCellValue('B' . $rowNum, $dt2[$i][4]);
                        if ($MaxOrderNo != 1) {
                            $objActSheet->getStyle('B' . $rowNum)->applyFromArray($MidMiddleStyle);
                            $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                            $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                        } else {
                            $objActSheet->getStyle('B' . $rowNum)->applyFromArray($MidRightStyle);
                            $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                            $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                            $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                        }
                        break;
                    case 2:
                        $objActSheet->setCellValue('C' . $rowNum, $dt2[$i][4]);
                        if ($MaxOrderNo != 2) {
                            $objActSheet->getStyle('C' . $rowNum)->applyFromArray($MidMiddleStyle);
                            $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                            $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

                        } else {
                            $objActSheet->getStyle('C' . $rowNum)->applyFromArray($MidRightStyle);
                            $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                            $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                            $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                        }
                        break;
                    case 3:
                        $objActSheet->setCellValue('D' . $rowNum, $dt2[$i][4]);
                        if ($MaxOrderNo != 3) {
                            $objActSheet->getStyle('D' . $rowNum)->applyFromArray($MidMiddleStyle);
                            $objActSheet->getStyle('D' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                            $objActSheet->getStyle('D' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('D' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('D' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                        } else {
                            $objActSheet->getStyle('D' . $rowNum)->applyFromArray($MidRightStyle);
                            $objActSheet->getStyle('D' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                            $objActSheet->getStyle('D' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('D' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                            $objActSheet->getStyle('D' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                        }
                        break;
                    case 4:
                        $objActSheet->setCellValue('E' . $rowNum, $dt2[$i][4]);
                        if ($MaxOrderNo != 4) {
                            $objActSheet->getStyle('E' . $rowNum)->applyFromArray($MidMiddleStyle);
                            $objActSheet->getStyle('E' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                            $objActSheet->getStyle('E' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('E' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('E' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                        } else {
                            $objActSheet->getStyle('E' . $rowNum)->applyFromArray($MidRightStyle);
                            $objActSheet->getStyle('E' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                            $objActSheet->getStyle('E' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                            $objActSheet->getStyle('E' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                            $objActSheet->getStyle('E' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                        }
                        break;
                    case 5:
                        $objActSheet->setCellValue('F' . $rowNum, $dt2[$i][4]);
                        $objActSheet->getStyle('F' . $rowNum)->applyFromArray($MidRightStyle);
                        $objActSheet->getStyle('F' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                        $objActSheet->getStyle('F' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                        $objActSheet->getStyle('F' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                        $objActSheet->getStyle('F' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

                        break;
                    default:
                        break;
                }

                $BeginTempCD = $dt2[$i][0];

            }

            $rowNum += 1;
            $objActSheet->setCellValue('A' . $rowNum, "合計");
            $objActSheet->getStyle('A' . $rowNum)->applyFromArray($BotLeftStyle);
            $objActSheet->getStyle('A' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
            $objActSheet->getStyle('A' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);
            $objActSheet->getStyle('A' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
            $objActSheet->getStyle('A' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

            switch (count($dt3)) {
                case 1:
                    $objActSheet->setCellValue('B' . $rowNum, $dt3[0][2]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($BotRighyStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                case 2:
                    $objActSheet->setCellValue('B' . $rowNum, $dt3[0][2]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('C' . $rowNum, $dt3[1][2]);
                    $objActSheet->getStyle('C' . $rowNum)->applyFromArray($BotRighyStyle);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                case 3:
                    $objActSheet->setCellValue('B' . $rowNum, $dt3[0][2]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('C' . $rowNum, $dt3[1][2]);
                    $objActSheet->getStyle('C' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('D' . $rowNum, $dt3[2][2]);
                    $objActSheet->getStyle('D' . $rowNum)->applyFromArray($BotRighyStyle);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                case 4:
                    $objActSheet->setCellValue('B' . $rowNum, $dt3[0][2]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('C' . $rowNum, $dt3[1][2]);
                    $objActSheet->getStyle('C' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('D' . $rowNum, $dt3[2][2]);
                    $objActSheet->getStyle('D' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('E' . $rowNum, $dt3[3][2]);
                    $objActSheet->getStyle('E' . $rowNum)->applyFromArray($BotRighyStyle);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                case 5:
                    $objActSheet->setCellValue('B' . $rowNum, $dt3[0][2]);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('B' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('C' . $rowNum, $dt3[1][2]);
                    $objActSheet->getStyle('C' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('C' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('D' . $rowNum, $dt3[2][2]);
                    $objActSheet->getStyle('D' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('D' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('E' . $rowNum, $dt3[3][2]);
                    $objActSheet->getStyle('E' . $rowNum)->applyFromArray($BotMiddleStyle);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('E' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                    $objActSheet->setCellValue('F' . $rowNum, $dt3[4][2]);
                    $objActSheet->getStyle('F' . $rowNum)->applyFromArray($BotRighyStyle);
                    $objActSheet->getStyle('F' . $rowNum)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('F' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                    $objActSheet->getStyle('F' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                    $objActSheet->getStyle('F' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                    break;
                default:
                    break;
            }
            $objActSheet->setSelectedCell("A1");

            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($strFilePath . $strFileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $file = "files/HMTVE/" . $strFileName;

            $result['data'] = $file;
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            if ($e->getMessage() == 'no templete') {
                $result['error'] = 'W9999';
            } else {
                $result['error'] = '出力処理中にエラーが発生しました。';
            }
            if (isset($objPHPExcel)) {
                unset($objPHPExcel);
            }
            if (isset($objReader)) {
                unset($objReader);
            }
            if (isset($objWriter)) {
                unset($objWriter);
            }
        }
        return $result;
    }

    //展示会成約_集計Excel出力(上)
    function createExcelDataTable1($STARTDT)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        $arr = array();
        try {
            $result = $this->HMTVE220PresentOrderTotal->getTitle($STARTDT);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count((array) $result['data']) > 0) {
                for ($i = 0; $i < count((array) $result['data']); $i++) {
                    $row = $result['data'][$i];
                    $arrrow = array_fill(0, 3, '');
                    if ($row['HINMEI'] != null) {
                        $arrrow[1] = $row['HINMEI'];
                    }
                    if ($row['ORDER_NO'] != null) {
                        $arrrow[2] = $row['ORDER_NO'];
                    }
                    array_push($arr, $arrrow);
                }
            }

            $result['result'] = TRUE;
            $result['data'] = $arr;
            return $result;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            return $result;
        }
    }

    //展示会成約_集計Excel出力(下)
    function createExcelDataTable2($STARTDT)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        $arr = array();
        try {
            $result = $this->HMTVE220PresentOrderTotal->getDetail($STARTDT);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count((array) $result['data']) > 0) {
                for ($i = 0; $i < count((array) $result['data']); $i++) {
                    $row = $result['data'][$i];
                    $arrrow = array_fill(0, 5, '');
                    if ($row['BUSYO_CD'] != null) {
                        $arrrow[0] = $row['BUSYO_CD'];
                    }
                    if ($row['BUSYO_RYKNM'] != null) {
                        $arrrow[1] = $row['BUSYO_RYKNM'];
                    }
                    if ($row['HINMEI'] != null) {
                        $arrrow[2] = $row['HINMEI'];
                    }
                    if ($row['ORDER_NO'] != null) {
                        $arrrow[3] = $row['ORDER_NO'];
                    }
                    if ($row['ORDER_NUM'] != null) {
                        $arrrow[4] = $row['ORDER_NUM'];
                    }
                    array_push($arr, $arrrow);
                }
            }

            $result['result'] = TRUE;
            $result['data'] = $arr;
            return $result;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            return $result;
        }
    }

    //展示会成約_集計Excel出力(上)
    function createExcelDataTable3($STARTDT)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        $arr = array();
        try {
            $result = $this->HMTVE220PresentOrderTotal->getTotal($STARTDT);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count((array) $result['data']) > 0) {
                for ($i = 0; $i < count((array) $result['data']); $i++) {
                    $row = $result['data'][$i];
                    $arrrow = array_fill(0, 3, '');
                    if ($row['ORDER_NO'] != null) {
                        $arrrow[1] = $row['ORDER_NO'];
                    }
                    if ($row['TOTAL'] != null) {
                        $arrrow[2] = $row['TOTAL'];
                    }
                    array_push($arr, $arrrow);
                }
            }

            $result['result'] = TRUE;
            $result['data'] = $arr;
            return $result;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            return $result;
        }
    }

    //展示会成約_集計Excel出力
    function createExcelDataTable4($STARTDT)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $result = $this->HMTVE220PresentOrderTotal->getMaxOrderNo($STARTDT);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count((array) $result['data']) > 0) {
                $result['data'] = $result['data'][0]['MAXNO'];
            } else {
                $result['data'] = 0;
            }

            $result['result'] = TRUE;
            return $result;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            return $result;
        }
    }

    //展示会成約_集計Excel出力
    public function btnPutoutClick()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'dataCheck' => null,
            'error' => ''
        );
        try {
            $postdata = $_POST['data'];
            // 'Exceファイル生成前処理
            $resultUpd = $this->updateAgreement($postdata['STARTDT']);
            if (!$resultUpd['result']) {
                throw new \Exception($resultUpd['error']);
            }

            $this->HMTVE220PresentOrderTotal = new HMTVE220PresentOrderTotal();


            //トランザクションを開始する
            $this->HMTVE220PresentOrderTotal->Do_transaction();

            $resultupd = $this->HMTVE220PresentOrderTotal->getUpdate1($postdata['STARTDT']);

            //'Excelファイル生成処理
            $resultExcel = $this->makeExcle($postdata['STARTDT'], $postdata['ENDDT']);


            //トランザクション終了処理
            if ($resultupd['result'] && $resultExcel['result']) {
                $this->HMTVE220PresentOrderTotal->Do_commit();
            } else {
                $this->HMTVE220PresentOrderTotal->Do_rollback();
                if (!$resultupd['result']) {
                    throw new \Exception($resultUpd['data']);
                } else {
                    throw new \Exception($resultExcel['error']);
                }

            }


            //'未出力データが存在しないかチェックする
            $resultCheck = $this->HMTVE220PresentOrderTotal->getSelect1($postdata['STARTDT']);
            if (!$resultCheck['result']) {
                throw new \Exception($resultCheck['error']);
            }
            $result['dataCheck'] = $resultCheck['data'];
            $result['data'] = $resultExcel['data'];
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

}
