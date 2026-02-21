<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE140PublicityOrderTotal;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
//*******************************************
// * sample controller
//*******************************************
class HMTVE140PublicityOrderTotalController extends AppController
{
    public $autoLayout = TRUE;
    public $HMTVE140PublicityOrderTotal;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        $this->render('index', 'HMTVE140PublicityOrderTotal_layout');
    }
    //コンボリストを設定する
    public function setDropDownList()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $this->HMTVE140PublicityOrderTotal = new HMTVE140PublicityOrderTotal();

            $result = $this->HMTVE140PublicityOrderTotal->setDropDownList();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //展示会宣材注文_集計画面を表示する
    public function btnETSearchClick()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $this->HMTVE140PublicityOrderTotal = new HMTVE140PublicityOrderTotal();
            $postdata = $_POST['request'];
            $result = $this->HMTVE140PublicityOrderTotal->shop($postdata['IVENTYM']);
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

    //未出力データが存在しないかチェックする
    public function checkInput($IVENTYM)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            $this->HMTVE140PublicityOrderTotal = new HMTVE140PublicityOrderTotal();

            $result = $this->HMTVE140PublicityOrderTotal->checkInput($IVENTYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    //ロック解除を行う
    public function btnLockClick()
    {
        $result = array(
            'result' => false,
            'data' => '',
            'error' => ''
        );
        try {
            $this->HMTVE140PublicityOrderTotal = new HMTVE140PublicityOrderTotal();
            $postdata = $_POST['data'];
            $result = $this->HMTVE140PublicityOrderTotal->lockRelease($postdata['IVENTYM']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $result['data'] = '';
        $this->fncReturn($result);
    }

    //展示会宣材注文_集計Excel出力
    public function btnExcelOutClick()
    {
        $result = array(
            'result' => false,
            'data' => null,
            'dataCheck' => null,
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            $this->HMTVE140PublicityOrderTotal = new HMTVE140PublicityOrderTotal();
            $postdata = $_POST['data'];
            $resultchk = $this->HMTVE140PublicityOrderTotal->checkExist($postdata['IVENTYM']);
            $flg = '';
            if (!$resultchk['result']) {
                throw new \Exception($resultchk['data']);
            }
            if (count((array) $resultchk['data']) == 0) {
                $flg = 'insert';
            } else {
                $flg = 'update';
            }

            //更新処理
            $this->HMTVE140PublicityOrderTotal->Do_transaction();
            $blnTran = TRUE;
            $resultflg = null;
            if ($flg == 'insert') {
                $resultflg = $this->HMTVE140PublicityOrderTotal->insert($postdata['IVENTYM']);
            } elseif ($flg == 'update') {
                $resultflg = $this->HMTVE140PublicityOrderTotal->update($postdata['IVENTYM']);
            }
            if (!$resultflg['result']) {
                throw new \Exception('W0006');
            }
            $this->HMTVE140PublicityOrderTotal->Do_commit();
            $blnTran = FALSE;

            //Excel data
            $resultdt1 = $this->createExcelDataTable1($postdata['IVENTYM']);
            $resultdt2 = $this->createExcelDataTable2($postdata['IVENTYM']);

            //トランザクションを開始する
            $this->HMTVE140PublicityOrderTotal->Do_transaction();
            $blnTran = TRUE;

            $resultupd = $this->HMTVE140PublicityOrderTotal->updateFlg($postdata['IVENTYM']);

            //Excelファイル生成処理
            $resultExcel = $this->makeExcel($postdata['IVENTYM'], $resultdt1, $resultdt2);

            //トランザクション終了処理
            if ($resultupd['result'] && $resultExcel['result']) {
                $this->HMTVE140PublicityOrderTotal->Do_commit();
            } else {
                $this->HMTVE140PublicityOrderTotal->Do_rollback();
                if ($resultExcel['error'] == 'フォルダのパーミッションはエラーが発生しました。' || $resultExcel['error'] == 'W0003' || $resultExcel['error'] == 'テンプレートファイルが存在しません。') {
                    throw new \Exception($resultExcel['error']);
                }
                if ($resultExcel['result'] && !$resultupd['result']) {
                    $this->HMTVE140PublicityOrderTotal->Do_transaction();
                    $resultcfm = $this->HMTVE140PublicityOrderTotal->updateConfirm($postdata['IVENTYM']);
                    if (!$resultcfm['result']) {
                        throw new \Exception('W0006');
                    }
                    $this->HMTVE140PublicityOrderTotal->Do_commit();
                    throw new \Exception('W0006');
                } else {
                    throw new \Exception('W0006');
                }

            }

            $result['data'] = $resultExcel['data'];
            $result['result'] = TRUE;

            $blnTran = FALSE;
            $resultCheck = $this->checkInput($postdata['IVENTYM']);
            if (!$resultCheck['result']) {
                throw new \Exception($resultCheck['error']);
            }
            $result['dataCheck'] = $resultCheck['data'];
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMTVE140PublicityOrderTotal->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

    //Excel生成
    function createExcelData($dt1, $dt2, $strFilePath, $strFileName, $IVENTYM)
    {
        $result = array(
            'result' => FALSE,
            'data' => null,
            'error' => ''
        );
        try {
            $tmpPath1 = dirname(dirname(dirname(__FILE__)));
            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath1 . "TENJIKAICYUMONSHODATA.xls";

            if (!file_exists($strTemplatePath)) {
                throw new \Exception('no templete');
            }

            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);

            $fontStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
            );
            $titleStyle = array(
                'font' => array(
                    'size' => 14,
                    'name' => 'ＭＳ Ｐゴシック',
                    'bold' => true,
                ),
                'alignment' => array('vertical' => Alignment::VERTICAL_CENTER, ),
            );
            $topleftStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
                'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER, )
            );
            $topStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $toprightStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $leftStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $leftbottomStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_MEDIUM)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $leftbottomAlignCenterStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_MEDIUM)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $bottomAlignRightStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_MEDIUM)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $normalAlignRightStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $rightStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $rightbottomStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック'
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => Border::BORDER_MEDIUM)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $topleftSetting = array(
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
            );
            $toprightSetting = array(
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
            );
            $leftSetting = array(
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
            );
            $leftbottomSetting = array(
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_MEDIUM)
                ),
            );
            $rightSetting = array(
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
            );
            $rightbottomSetting = array(
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => Border::BORDER_MEDIUM)
                ),
            );

            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            $objActSheet->getPageSetup()->setFitToPage(false);
            $objActSheet->getPageSetup()->setFitToWidth(0);
            $scale = 0.4;
            $scalePercent = intval($scale * 100);
            $objActSheet->getPageSetup()->setFitToHeight($scalePercent);
            $blnRet = True;
            $rowNum = 4;
            $intSheetCnt = 0;
            $rowCount = 0;

            $order1 = array_fill(0, count($dt2), 0);
            $order2 = array_fill(0, count($dt2), 0);
            $order3 = array_fill(0, count($dt2), 0);
            $count = 0;
            $everyRows = 0;

            $objActSheet->setCellValue('A2', substr($IVENTYM, 0, 4) . '.' . substr($IVENTYM, 4, 2) . "月展示会宣材注文書");
            $objActSheet->getStyle('A2')->applyFromArray($titleStyle);

            for ($i = 0; $i < count($dt2); $i++) {
                if ($rowNum > 65528) {
                    $intSheetCnt = $intSheetCnt + 1;
                    $objActSheet = $objPHPExcel->createSheet($intSheetCnt);
                    $rowNum = 3;
                    $objActSheet->setCellValue('A2', substr($IVENTYM, 0, 4) . '.' . substr($IVENTYM, 4, 2) . "月展示会宣材注文書");
                    $objActSheet->getStyle('A2')->applyFromArray($titleStyle);
                }

                $value = $dt2[$i];
                if ($blnRet) {
                    $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, '部署');
                    $objActSheet->getStyle('A' . $rowNum)->applyFromArray($fontStyle);
                    $objActSheet->setCellValueExplicit('B' . $rowNum, $value[5], DataType::TYPE_STRING);
                    $objActSheet->getStyle('B' . $rowNum)->applyFromArray($fontStyle);
                    $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $value[6]);
                    $objActSheet->getStyle('C' . $rowNum)->applyFromArray($fontStyle);

                    $rowNum++;
                    $objActSheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
                    $objActSheet->getStyle('A' . $rowNum . ':C' . $rowNum)->applyFromArray($topleftSetting);
                    $objActSheet->getStyle('A' . $rowNum)->applyFromArray($topleftStyle);

                    $objActSheet->setCellValue($this->getColumnLetter(3) . $rowNum, $dt1[1]);
                    $objActSheet->getStyle('D' . $rowNum)->applyFromArray($topStyle);

                    $objActSheet->setCellValue($this->getColumnLetter(4) . $rowNum, $dt1[2]);
                    $objActSheet->getStyle('E' . $rowNum)->applyFromArray($topStyle);

                    $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $dt1[3]);
                    $objActSheet->getStyle('F' . $rowNum)->applyFromArray($topStyle);

                    $objActSheet->setCellValue($this->getColumnLetter(6) . $rowNum, $dt1[4]);
                    $objActSheet->mergeCells('G' . $rowNum . ':I' . $rowNum);
                    $objActSheet->getStyle('G' . $rowNum . ':I' . $rowNum)->applyFromArray($toprightSetting);
                    $objActSheet->getStyle('G' . $rowNum)->applyFromArray($toprightStyle);

                    $rowNum++;
                    $blnRet = FALSE;
                }
                if ($rowCount == count($dt2) - 1) {
                    $rowCount = $i;
                } else {
                    $rowCount = $i + 1;
                }

                if ($value[5] == $dt2[$rowCount][5]) {
                    $objActSheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
                    $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $value[0]);
                    if ($i == count($dt2) - 1) {
                        //結合と罫線を行う
                        $objActSheet->getStyle('A' . $rowNum . ':C' . $rowNum)->applyFromArray($leftbottomSetting);
                        $objActSheet->getStyle('A' . $rowNum)->applyFromArray($leftbottomStyle);
                    } else {
                        //結合と罫線を行う
                        $objActSheet->getStyle('A' . $rowNum . ':C' . $rowNum)->applyFromArray($leftSetting);
                        $objActSheet->getStyle('A' . $rowNum)->applyFromArray($leftStyle);
                    }

                    $objActSheet->setCellValueExplicit('D' . $rowNum, $value[1], DataType::TYPE_STRING);
                    if ($i == count($dt2) - 1) {
                        $objActSheet->getStyle('D' . $rowNum)->applyFromArray($bottomAlignRightStyle);
                    } else {
                        $objActSheet->getStyle('D' . $rowNum)->applyFromArray($normalAlignRightStyle);
                    }

                    $objActSheet->setCellValueExplicit('E' . $rowNum, $value[2], DataType::TYPE_STRING);
                    if ($i == count($dt2) - 1) {
                        $objActSheet->getStyle('E' . $rowNum)->applyFromArray($bottomAlignRightStyle);
                    } else {
                        $objActSheet->getStyle('E' . $rowNum)->applyFromArray($normalAlignRightStyle);
                    }

                    $objActSheet->setCellValueExplicit('F' . $rowNum, $value[3], DataType::TYPE_STRING);
                    if ($i == count($dt2) - 1) {
                        $objActSheet->getStyle('F' . $rowNum)->applyFromArray($bottomAlignRightStyle);
                    } else {
                        $objActSheet->getStyle('F' . $rowNum)->applyFromArray($normalAlignRightStyle);
                    }

                    $order1[$count] += $value[1];
                    $order2[$count] += $value[2];
                    $order3[$count] += $value[3];
                    $objActSheet->mergeCells('G' . $rowNum . ':I' . $rowNum);
                    $objActSheet->setCellValue($this->getColumnLetter(6) . $rowNum, $value[4]);
                    if ($i == count($dt2) - 1) {
                        //結合と罫線を行う
                        $objActSheet->getStyle('G' . $rowNum . ':I' . $rowNum)->applyFromArray($rightbottomSetting);
                        $objActSheet->getStyle('G' . $rowNum)->applyFromArray($rightbottomStyle);
                    } else {
                        //結合と罫線を行う
                        $objActSheet->getStyle('G' . $rowNum . ':I' . $rowNum)->applyFromArray($rightSetting);
                        $objActSheet->getStyle('G' . $rowNum)->applyFromArray($rightStyle);
                    }

                    $rowNum++;
                    $count++;
                    $blnRet = FALSE;
                } else {
                    if ($i < count($dt2) - 1) {
                        $objActSheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
                        $objActSheet->getStyle('A' . $rowNum . ':C' . $rowNum)->applyFromArray($leftbottomSetting);
                        $objActSheet->getStyle('A' . $rowNum)->applyFromArray($leftbottomStyle);

                        $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $value[0]);
                        $objActSheet->setCellValueExplicit('D' . $rowNum, $value[1], DataType::TYPE_STRING);
                        $objActSheet->getStyle('D' . $rowNum)->applyFromArray($bottomAlignRightStyle);

                        $objActSheet->setCellValueExplicit('E' . $rowNum, $value[2], DataType::TYPE_STRING);
                        $objActSheet->getStyle('E' . $rowNum)->applyFromArray($bottomAlignRightStyle);

                        $objActSheet->setCellValueExplicit('F' . $rowNum, $value[3], DataType::TYPE_STRING);
                        $objActSheet->getStyle('F' . $rowNum)->applyFromArray($bottomAlignRightStyle);

                        $order1[$count] += $value[1];
                        $order2[$count] += $value[2];
                        $order3[$count] += $value[3];
                        $objActSheet->mergeCells('G' . $rowNum . ':I' . $rowNum);
                        $objActSheet->getStyle('G' . $rowNum . ':I' . $rowNum)->applyFromArray($rightbottomSetting);
                        $objActSheet->getStyle('G' . $rowNum)->applyFromArray($rightbottomStyle);
                        $objActSheet->setCellValue($this->getColumnLetter(6) . $rowNum, $value[4]);
                    }
                    $blnRet = True;
                    $rowNum += 2;
                    $everyRows = $count;
                    $count = 0;
                }

            }
            if ($everyRows == 0) {
                $everyRows = $count - 1;
            }
            $rowNum++;

            //合計表を生成
            $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, '部署合計');
            $objActSheet->getStyle('A' . $rowNum)->applyFromArray($fontStyle);
            $rowNum++;
            $objActSheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
            $objActSheet->getStyle('A' . $rowNum . ':C' . $rowNum)->applyFromArray($topleftSetting);
            $objActSheet->getStyle('A' . $rowNum)->applyFromArray($topleftStyle);

            $objActSheet->setCellValue($this->getColumnLetter(3) . $rowNum, $dt1[1]);
            $objActSheet->getStyle('D' . $rowNum)->applyFromArray($topStyle);

            $objActSheet->setCellValue($this->getColumnLetter(4) . $rowNum, $dt1[2]);
            $objActSheet->getStyle('E' . $rowNum)->applyFromArray($topStyle);

            $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $dt1[3]);
            $objActSheet->getStyle('F' . $rowNum)->applyFromArray($topStyle);

            $objActSheet->setCellValue($this->getColumnLetter(6) . $rowNum, $dt1[4]);
            $objActSheet->mergeCells('G' . $rowNum . ':I' . $rowNum);
            $objActSheet->getStyle('G' . $rowNum . ':I' . $rowNum)->applyFromArray($toprightSetting);
            $objActSheet->getStyle('G' . $rowNum)->applyFromArray($toprightStyle);
            $rowNum++;

            for ($j = 0; $j <= $everyRows; $j++) {
                $value = $dt2[$j];
                $objActSheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
                $objActSheet->getStyle('A' . $rowNum . ':C' . $rowNum)->applyFromArray($leftSetting);
                $objActSheet->getStyle('A' . $rowNum)->applyFromArray($leftStyle);
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $value[0]);

                $objActSheet->setCellValueExplicit('D' . $rowNum, $order1[$j], DataType::TYPE_STRING);
                $objActSheet->getStyle('D' . $rowNum)->applyFromArray($normalAlignRightStyle);

                $objActSheet->setCellValueExplicit('E' . $rowNum, $order2[$j], DataType::TYPE_STRING);
                $objActSheet->getStyle('E' . $rowNum)->applyFromArray($normalAlignRightStyle);

                $objActSheet->setCellValueExplicit('F' . $rowNum, $order3[$j], DataType::TYPE_STRING);
                $objActSheet->getStyle('F' . $rowNum)->applyFromArray($normalAlignRightStyle);

                $objActSheet->mergeCells('G' . $rowNum . ':I' . $rowNum);
                $objActSheet->setCellValue($this->getColumnLetter(6) . $rowNum, $value[4]);
                $objActSheet->getStyle('G' . $rowNum . ':I' . $rowNum)->applyFromArray($rightSetting);
                $objActSheet->getStyle('G' . $rowNum)->applyFromArray($rightStyle);
                $rowNum++;
            }

            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;
            for ($k = 0; $k <= $everyRows; $k++) {
                $sum1 += $order1[$k];
                $sum2 += $order2[$k];
                $sum3 += $order3[$k];
            }
            $objActSheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
            $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, "合  計");
            $objActSheet->getStyle('A' . $rowNum . ':C' . $rowNum)->applyFromArray($leftbottomSetting);
            $objActSheet->getStyle('A' . $rowNum)->applyFromArray($leftbottomAlignCenterStyle);

            $objActSheet->setCellValueExplicit('D' . $rowNum, $sum1, DataType::TYPE_STRING);
            $objActSheet->getStyle('D' . $rowNum)->applyFromArray($bottomAlignRightStyle);

            $objActSheet->setCellValueExplicit('E' . $rowNum, $sum2, DataType::TYPE_STRING);
            $objActSheet->getStyle('E' . $rowNum)->applyFromArray($bottomAlignRightStyle);

            $objActSheet->setCellValueExplicit('F' . $rowNum, $sum3, DataType::TYPE_STRING);
            $objActSheet->getStyle('F' . $rowNum)->applyFromArray($bottomAlignRightStyle);

            $objActSheet->mergeCells('G' . $rowNum . ':I' . $rowNum);
            $objActSheet->setCellValue($this->getColumnLetter(6) . $rowNum, '');
            $objActSheet->getStyle('G' . $rowNum . ':I' . $rowNum)->applyFromArray($rightbottomSetting);
            $objActSheet->getStyle('G' . $rowNum)->applyFromArray($rightbottomStyle);
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
                $result['error'] = 'テンプレートファイルが存在しません。';
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

    //Excelファイル生成処理
    function makeExcel($IVENTYM, $resultdt1, $resultdt2)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        try {
            if (!$resultdt1['result']) {
                throw new \Exception($resultdt1['error']);
            }
            if (!$resultdt2['result']) {
                throw new \Exception($resultdt2['error']);
            }
            $dt1 = $resultdt1['data'];
            $dt2 = $resultdt2['data'];
            if (count($dt1) == 1 || count($dt2) == 0) {
                throw new \Exception('W0003');
            }
            $strFileName = "展示会宣材注文書(" . substr($IVENTYM, 0, 4) . "年" . substr($IVENTYM, 4, 2) . ").XLS";
            //出力先パス
            $strPath = dirname(dirname(dirname(__FILE__)));
            $tmpPath1 = dirname($strPath);
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;

            //path is exist
            if (file_exists($tmpPath)) {
                $outFolder = $tmpPath1 . "/webroot/files/";
                if (!(is_readable($outFolder) && is_writeable($outFolder) && is_executable($outFolder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!(is_readable($tmpPath) && is_writeable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && strpos($file, "展示会宣材注文書") !== false) {
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

            $result = $this->createExcelData($dt1, $dt2, $tmpPath, $strFileName, $IVENTYM);

            return $result;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            return $result;
        }
    }

    //展示会宣材注文_集計Excel出力(上)
    function createExcelDataTable1($IVENTYM)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        $arr = array();
        try {
            $this->HMTVE140PublicityOrderTotal = new HMTVE140PublicityOrderTotal();
            $result = $this->HMTVE140PublicityOrderTotal->createExcelDataTable1($IVENTYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            array_push($arr, '');
            if (count((array) $result['data']) > 0) {
                if ($result['data'][0]['HINMEI1'] != null) {
                    array_push($arr, $result['data'][0]['HINMEI1']);
                } else {
                    array_push($arr, '');
                }
                if ($result['data'][0]['HINMEI2'] != null) {
                    array_push($arr, $result['data'][0]['HINMEI2']);
                } else {
                    array_push($arr, '');
                }
                if ($result['data'][0]['HINMEI3'] != null) {
                    array_push($arr, $result['data'][0]['HINMEI3']);
                } else {
                    array_push($arr, '');
                }
                array_push($arr, '備考');
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

    //展示会宣材注文_集計Excel出力(下)
    function createExcelDataTable2($IVENTYM)
    {
        $result = array(
            'result' => false,
            'data' => null,
            'error' => ''
        );
        $arr = array();
        try {
            $this->HMTVE140PublicityOrderTotal = new HMTVE140PublicityOrderTotal();
            $result = $this->HMTVE140PublicityOrderTotal->SQL($IVENTYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if (count((array) $result['data']) > 0) {
                for ($i = 0; $i < count((array) $result['data']); $i++) {
                    $arrrow = array_fill(0, 7, '');
                    $row = $result['data'][$i];

                    foreach ((array) $row as $key => $value) {
                        if ($key == 'HIDUKE' && $value != null) {
                            $arrrow[0] = $value;
                        } elseif ($key == 'HIDUKE' && $value == null) {
                            $arrrow[0] = '';
                        }
                        if ($key == 'ORDER1' && $value != null) {
                            $arrrow[1] = $value;
                        } elseif ($key == 'ORDER1' && $value == null) {
                            $arrrow[1] = '';
                        }
                        if ($key == 'ORDER2' && $value != null) {
                            $arrrow[2] = $value;
                        } elseif ($key == 'ORDER2' && $value == null) {
                            $arrrow[2] = '';
                        }
                        if ($key == 'ORDER3' && $value != null) {
                            $arrrow[3] = $value;
                        } elseif ($key == 'ORDER3' && $value == null) {
                            $arrrow[3] = '';
                        }
                        if ($key == 'BIKOU' && $value != null) {
                            $arrrow[4] = $value;
                        } elseif ($key == 'BIKOU' && $value == null) {
                            $arrrow[4] = '';
                        }
                        if ($key == 'BUSYO_CD' && $value != null) {
                            $arrrow[5] = $value;
                        } elseif ($key == 'BUSYO_CD' && $value == null) {
                            $arrrow[5] = '';
                        }
                        if ($key == 'BUSYO_RYKNM' && $value != null) {
                            $arrrow[6] = $value;
                        } elseif ($key == 'BUSYO_RYKNM' && $value == null) {
                            $arrrow[6] = '';
                        }
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
    public function getColumnLetter($col)
    {
        $columnNumber = $col + 1;
        $columnLetter = '';
        while ($columnNumber > 0) {
            $remainder = ($columnNumber - 1) % 26;
            $columnLetter = chr(65 + $remainder) . $columnLetter;
            $columnNumber = intdiv($columnNumber - 1, 26);
        }
        return $columnLetter;
    }
}
