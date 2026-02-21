<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE250ReportPlaceCntTotal;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
//*******************************************
// * sample controller
//*******************************************
class HMTVE250ReportPlaceCntTotalController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $HMTVE250ReportPlaceCntTotal;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }

    public function index()
    {
        $this->render('index', 'HMTVE250ReportPlaceCntTotal_layout');
    }
    //コンボリストに日付を設定する
    public function expressDdlYmd()
    {
        $this->HMTVE250ReportPlaceCntTotal = new HMTVE250ReportPlaceCntTotal();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $res = $this->HMTVE250ReportPlaceCntTotal->getObjDateSql();
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
        } catch (\Exception $e) {

            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //データを取得して、表示します
    public function btnExpressClick()
    {
        $this->HMTVE250ReportPlaceCntTotal = new HMTVE250ReportPlaceCntTotal();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $ds = $this->HMTVE250ReportPlaceCntTotal->getPartSql($_POST['request']['NENGETU']);
            if (!$ds['result']) {
                throw new \Exception($ds['data']);
            }
            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($ds['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $res = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($ds['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //ロック解除のイベント
    public function btnRemoveClick()
    {
        $this->HMTVE250ReportPlaceCntTotal = new HMTVE250ReportPlaceCntTotal();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            //ロック解除を行う
            $upd = $this->HMTVE250ReportPlaceCntTotal->updateHdtorSql($_POST['data']['NENGETU']);
            if (!$upd['result']) {
                throw new \Exception($upd['data']);
            }
            if ($upd['number_of_rows'] <= 0) {
                //該当データはありません。
                $res['data']["msg"] = "W0024";
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {

            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //合計出力ボタン/明細出力ボタン
    public function excelOutBtnClick()
    {
        $tranStartFlg = FALSE;
        $this->HMTVE250ReportPlaceCntTotal = new HMTVE250ReportPlaceCntTotal();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $ddlYear = $_POST['data']['ddlYear'];
            $ddlMonth = $_POST['data']['ddlMonth'];
            $type = $_POST['data']['type'];
            $NENGETU = $ddlYear . $ddlMonth;
            //保管場所届出件数確定データに確定ﾌﾗｸﾞ１で更新する
            //①トランザクション開始
            $this->HMTVE250ReportPlaceCntTotal->Do_transaction();
            $tranStartFlg = TRUE;
            //②保管場所届出件数確定データの更新処理
            //②ー１．存在チェック
            $objReader = $this->HMTVE250ReportPlaceCntTotal->getManageLocaleSql($NENGETU);
            if (!$objReader['result']) {
                throw new \Exception($objReader['data']);
            }
            //②ー２．更新処理
            if ($objReader['row'] == 0) {
                //追加処理
                $ins = $this->HMTVE250ReportPlaceCntTotal->insertManageLocaleSql($NENGETU);
                if (!$ins['result']) {
                    throw new \Exception($ins['data']);
                }
            } else {
                //Ⅱ．②－１の取得件数＞０の場合
                //更新処理
                $upd = $this->HMTVE250ReportPlaceCntTotal->updateHdtorSql2($NENGETU);
                if (!$upd['result']) {
                    throw new \Exception($upd['data']);
                }
            }
            //エラーがない場合、コミットする
            $this->HMTVE250ReportPlaceCntTotal->Do_commit();
            $tranStartFlg = FALSE;
            //データを出力します
            if ($type == "sum") {
                //合計出力ボタン
                $excel = $this->btnAll_ExcelOut($ddlYear, $ddlMonth);
            } else {
                //明細出力ボタン
                $excel = $this->btnView_ExcelOut($ddlYear, $ddlMonth);
            }

            if (!$excel['result']) {
                $res['data'] = $excel['data'];
                throw new \Exception($excel['error']);
            }
            $res['data']['url'] = $excel['data'];
            //未出力データが存在しないかチェックする
            //①未出力データを抽出する
            $objReader = $this->HMTVE250ReportPlaceCntTotal->getNotexport($NENGETU);
            if (!$objReader['result']) {
                $res['data']['msg'] = $objReader['data'];
                $res['data']['CNT'] = -1;
            } else {
                $res['data']['CNT'] = $objReader['data'][0]['CNT'];
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE250ReportPlaceCntTotal->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //合計出力ボタン データを出力します
    public function btnAll_ExcelOut($ddlYear, $ddlMonth)
    {
        $tranStartFlg = FALSE;
        $this->HMTVE250ReportPlaceCntTotal = new HMTVE250ReportPlaceCntTotal();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $yearmon = $ddlYear . $ddlMonth;
        try {
            //①トランザクション開始
            $this->HMTVE250ReportPlaceCntTotal->Do_transaction();
            $tranStartFlg = TRUE;
            //軽自動車保管場所届出件数データの出力ﾌﾗｸﾞを"1"で更新する
            $upd = $this->HMTVE250ReportPlaceCntTotal->updateCar($yearmon);
            if (!$upd['result']) {
                throw new \Exception($upd['data']);
            }
            //***Exceファイル生成処理****
            //一時保存先のフルパス取得
            $strPath = dirname(dirname(dirname(__FILE__)));
            $tmpPath1 = dirname($strPath);
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            //EXCELテンプレート
            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            $strTemplatePath = $strPath . '/' . $strTemplatePath1 . "REPORTPLACECNTTOTLA.xls";
            if (!file_exists($strTemplatePath)) {
                $res['data']['msg'] = 'W9999';
                throw new \Exception('テンプレートファイルが存在しません。');
            }
            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    $res['data']['msg'] = 'E9999';
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && strpos($file, "軽自動車保管場所届出件数合計") !== false) {
                        $fullpath = $tmpPath . "/" . $file;
                        if (!is_dir($fullpath)) {
                            unlink($fullpath);
                        } else {
                            rmdir($tmpPath);
                        }
                    }
                }
            } else {
                $outFloder = dirname($tmpPath);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    $res['data']['msg'] = 'E9999';
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $res['data']['msg'] = 'E9999';
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = IOFactory::createReader("Xls");
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            //明細データ取得
            $detail = $this->HMTVE250ReportPlaceCntTotal->getExcelExportSql($yearmon);
            if (!$detail['result']) {
                throw new \Exception($detail['data']);
            }
            //file name
            $strFileName = "軽自動車保管場所届出件数合計(" . $ddlYear . "年" . $ddlMonth . "月).xls";
            $filefullpath = $tmpPath . $strFileName;

            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;
            $sum5 = 0;
            $sum6 = 0;
            $sum7 = 0;
            $sum9 = 0;
            $sum10 = 0;
            $sum11 = 0;
            $sum13 = 0;
            $sum14 = 0;
            $sum15 = 0;
            $titleText = $ddlYear . "年" . $ddlMonth . "月分";
            $objActSheet->setCellValue('A2', $titleText);

            if (count((array) $detail['data']) % 4 != 0) {
                throw new \Exception("W0030");
            }
            $i = 0;
            while ($i < count((array) $detail['data'])) {
                if ($detail['data'][$i]['SINSEI_KB'] == "1") {
                    $sum1 += (double) $detail['data'][$i]['SINSEICNT'];
                    $sum2 += (double) $detail['data'][$i]['TODOKECNT'];
                    $sum3 += (double) $detail['data'][$i]['KAKUNINCNT'];
                }
                if ($detail['data'][$i + 1]['SINSEI_KB'] == "2") {
                    $sum5 += (double) $detail['data'][$i + 1]['SINSEICNT'];
                    $sum6 += (double) $detail['data'][$i + 1]['TODOKECNT'];
                    $sum7 += (double) $detail['data'][$i + 1]['KAKUNINCNT'];
                }
                if ($detail['data'][$i + 2]['SINSEI_KB'] == "3") {
                    $sum9 += (double) $detail['data'][$i + 2]['SINSEICNT'];
                    $sum10 += (double) $detail['data'][$i + 2]['TODOKECNT'];
                    $sum11 += (double) $detail['data'][$i + 2]['KAKUNINCNT'];
                }
                if ($detail['data'][$i + 3]['SINSEI_KB'] == "4") {
                    $sum13 += (double) $detail['data'][$i + 3]['SINSEICNT'];
                    $sum14 += (double) $detail['data'][$i + 3]['TODOKECNT'];
                    $sum15 += (double) $detail['data'][$i + 3]['KAKUNINCNT'];
                }
                $i += 4;
            }
            //1.新車新規-①
            $objActSheet->setCellValue($this->getColumnLetter(2) . 16, $sum1);
            //1.新車新規-②
            $objActSheet->setCellValue($this->getColumnLetter(5) . 16, $sum2);
            //1.新車新規-③
            $cellValue = "(" . str_pad($sum3, 5, " ", STR_PAD_LEFT) . ")";
            $objActSheet->setCellValue($this->getColumnLetter(8) . 16, $cellValue);
            //1.新車新規-④
            $cellValue = $sum1 == 0 ? "0" : $this->FncRoundA((($sum2 + $sum3) / $sum1) * 100, 1);
            $objActSheet->setCellValue($this->getColumnLetter(11) . 16, $cellValue);
            //2.中古新規-①
            $objActSheet->setCellValue($this->getColumnLetter(2) . 17, $sum5);
            //2.中古新規-②
            $objActSheet->setCellValue($this->getColumnLetter(5) . 17, $sum6);
            //2.中古新規-③
            $cellValue = "(" . str_pad($sum7, 5, " ", STR_PAD_LEFT) . ")";
            $objActSheet->setCellValue($this->getColumnLetter(8) . 17, $cellValue);
            //2.中古新規-④
            $cellValue = $sum5 == 0 ? "0" : $this->FncRoundA((($sum6 + $sum7) / $sum5) * 100, 1);
            $objActSheet->setCellValue($this->getColumnLetter(11) . 17, $cellValue);
            //3.転　　　入-①
            $objActSheet->setCellValue($this->getColumnLetter(2) . 18, $sum9);
            //3.転　　　入-②
            $objActSheet->setCellValue($this->getColumnLetter(5) . 18, $sum10);
            //3.転　　　入-③
            $cellValue = "(" . str_pad($sum11, 5, " ", STR_PAD_LEFT) . ")";
            $objActSheet->setCellValue($this->getColumnLetter(8) . 18, $cellValue);
            //3.転　　　入-④
            $cellValue = $sum9 == 0 ? "0" : $this->FncRoundA((($sum10 + $sum11) / $sum9) * 100, 1);
            $objActSheet->setCellValue($this->getColumnLetter(11) . 18, $cellValue);
            //4.記　　　入-①
            $objActSheet->setCellValue($this->getColumnLetter(2) . 19, $sum13);
            //4.記　　　入-②
            $objActSheet->setCellValue($this->getColumnLetter(5) . 19, $sum14);
            //4.記　　　入-③
            $cellValue = "(" . str_pad($sum15, 5, " ", STR_PAD_LEFT) . ")";
            $objActSheet->setCellValue($this->getColumnLetter(8) . 19, $cellValue);
            //4.記　　　入-④
            $cellValue = $sum13 == 0 ? "0" : $this->FncRoundA((($sum14 + $sum15) / $sum13) * 100, 1);
            $objActSheet->setCellValue($this->getColumnLetter(11) . 19, $cellValue);
            //5.合　　　計-①
            $sumAll1 = $sum1 + $sum5 + $sum9 + $sum13;
            $objActSheet->setCellValue($this->getColumnLetter(2) . 21, $sumAll1);
            //5.合　　　計-②
            $sumAll2 = $sum2 + $sum6 + $sum10 + $sum14;
            $objActSheet->setCellValue($this->getColumnLetter(5) . 21, $sumAll2);
            //5.合　　　計-③
            $sumAll3 = $sum3 + $sum7 + $sum11 + $sum15;
            $cellValue = "(" . str_pad($sumAll3, 5, " ", STR_PAD_LEFT) . ")";
            $objActSheet->setCellValue($this->getColumnLetter(8) . 21, $cellValue);

            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, "Xls");
            $objWriter->save($filefullpath);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $file = "files/HMTVE/" . $strFileName;
            $res['data'] = $file;
            //エラーがない場合、コミットする
            $this->HMTVE250ReportPlaceCntTotal->Do_commit();
            $tranStartFlg = FALSE;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE250ReportPlaceCntTotal->Do_rollback();
                //成約プレゼント確定データの更新処理を行う
                $appoint = $this->HMTVE250ReportPlaceCntTotal->updateAppoint($yearmon);
                if (!$appoint['result']) {
                    $res['result'] = FALSE;
                    $res['error'] = $appoint['data'];
                    return $res;
                }
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    //明細出力ボタン  データを出力します
    public function btnView_ExcelOut($ddlYear, $ddlMonth)
    {
        $tranStartFlg = FALSE;
        $this->HMTVE250ReportPlaceCntTotal = new HMTVE250ReportPlaceCntTotal();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        $yearmon = $ddlYear . $ddlMonth;
        try {
            //①トランザクション開始
            $this->HMTVE250ReportPlaceCntTotal->Do_transaction();
            $tranStartFlg = TRUE;
            //軽自動車保管場所届出件数データの出力ﾌﾗｸﾞを"1"で更新する
            $upd = $this->HMTVE250ReportPlaceCntTotal->updateCar($yearmon);
            if (!$upd['result']) {
                throw new \Exception($upd['data']);
            }
            //***Exceファイル生成処理****
            //一時保存先のフルパス取得
            $strPath = dirname(dirname(dirname(__FILE__)));
            $tmpPath1 = dirname($strPath);
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            //EXCELテンプレート
            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            $strTemplatePath = $strPath . '/' . $strTemplatePath1 . "REPORTPLACECNTDETAIL.xls";
            if (!file_exists($strTemplatePath)) {
                $res['data']['msg'] = 'W9999';
                throw new \Exception('テンプレートファイルが存在しません。');
            }
            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    $res['data']['msg'] = 'E9999';
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && strpos($file, "軽自動車保管場所届出件数明細") !== false) {
                        $fullpath = $tmpPath . "/" . $file;
                        if (!is_dir($fullpath)) {
                            unlink($fullpath);
                        } else {
                            rmdir($tmpPath);
                        }
                    }
                }
            } else {
                $outFloder = dirname($tmpPath);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    $res['data']['msg'] = 'E9999';
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $res['data']['msg'] = 'E9999';
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            //明細データ取得
            $detail = $this->HMTVE250ReportPlaceCntTotal->getExcelExportSql($yearmon);
            if (!$detail['result']) {
                throw new \Exception($detail['data']);
            }
            //file name
            $strFileName = "軽自動車保管場所届出件数明細(" . $ddlYear . "年" . $ddlMonth . "月).xls";
            $filefullpath = $tmpPath . $strFileName;

            $titleStyle = array(
                'font' => array(
                    'size' => 14,
                    'name' => 'ＭＳ Ｐゴシック',
                    'bold' => true
                )
            );
            $centerlStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック',
                    'bold' => false
                ),
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN)
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $rightStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック',
                    'bold' => false
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
            $percentageStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック',
                    'bold' => false
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
                ),
                //'numberformat' => array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE),
            );
            $leftStyle = array(
                'font' => array(
                    'size' => 11,
                    'name' => 'ＭＳ Ｐゴシック',
                    'bold' => false
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                )
            );
            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;
            $sum5 = 0;
            $sum6 = 0;
            $sum7 = 0;
            $sum9 = 0;
            $sum10 = 0;
            $sum11 = 0;
            $sum13 = 0;
            $sum14 = 0;
            $sum15 = 0;
            $titleText = $ddlYear . "年" . $ddlMonth . "月分軽自動車の保管場所届出件数 及び 検査等申請件数報告書";
            $objActSheet->setCellValue('A2', $titleText);
            $objActSheet->getStyle('A2')->applyFromArray($titleStyle);
            $rowNum = 3;
            $i = 0;
            $sheetNum = 1;
            if (count((array) $detail['data']) % 4 != 0) {
                throw new \Exception("行がありません。");
            }
            while ($i < count((array) $detail['data'])) {
                if ($rowNum >= 65529) {
                    $sheetNum++;
                    //8.43
                    $objActSheet->getColumnDimension('A')->setWidth(9.2);
                    $objActSheet->getColumnDimension('B')->setWidth(9.2);
                    $objActSheet->getColumnDimension('C')->setWidth(9.2);
                    $objActSheet->getColumnDimension('D')->setWidth(9.2);
                    $objActSheet->getColumnDimension('E')->setWidth(9.2);
                    $objActSheet->getColumnDimension('F')->setWidth(9.2);
                    $objActSheet->getColumnDimension('G')->setWidth(9.2);
                    $objActSheet->getColumnDimension('H')->setWidth(9.2);
                    $objActSheet->getColumnDimension('I')->setWidth(9.2);
                    $objActSheet->getColumnDimension('J')->setWidth(9.2);
                    $objActSheet->getColumnDimension('K')->setWidth(9.2);
                    $objActSheet->setSelectedCell("A1");
                    //create sheet
                    $sheet2 = new Worksheet($objPHPExcel, "Sheet" . $sheetNum);
                    //$sheetNum：sheet order
                    $objPHPExcel->addSheet($sheet2, $sheetNum - 1);
                    $objPHPExcel->setActiveSheetIndex($sheetNum - 1);
                    $objActSheet = $objPHPExcel->getActiveSheet();

                    $objActSheet->getDefaultRowDimension()->setRowHeight(17.25);
                    $objActSheet->setCellValue('A2', $titleText);
                    $objActSheet->getStyle('A2')->applyFromArray($titleStyle);
                    $rowNum = 3;
                }

                $rowNum += 1;
                $cellValue = "部署    " . $detail['data'][$i]["BUSYO_CD"] . "   " . $detail['data'][$i]["BUSYO_RYKNM"];
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
                $objActSheet->getStyle('A' . $rowNum)->applyFromArray($leftStyle);

                $rowNum += 1;
                $cellValue = "申請区分";
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
                $objActSheet->mergeCells('A' . $rowNum . ':B' . ($rowNum + 1));
                $objActSheet->getStyle('A' . $rowNum . ':B' . ($rowNum + 1))->applyFromArray($centerlStyle);
                $cellValue = "①保管場所届出義務を\n伴う検査等申請件数";
                $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $cellValue);
                $objActSheet->mergeCells('C' . $rowNum . ':E' . ($rowNum + 1));
                $objActSheet->getStyle('C' . $rowNum . ':E' . ($rowNum + 1))->getAlignment()->setWrapText(true);
                $objActSheet->getStyle('C' . $rowNum . ':E' . ($rowNum + 1))->applyFromArray($centerlStyle);
                $cellValue = "②警察への保管\n場所届出件数";
                $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $cellValue);
                $objActSheet->mergeCells('F' . $rowNum . ':G' . ($rowNum + 1));
                $objActSheet->getStyle('F' . $rowNum . ':G' . ($rowNum + 1))->getAlignment()->setWrapText(true);
                $objActSheet->getStyle('F' . $rowNum . ':G' . ($rowNum + 1))->applyFromArray($centerlStyle);
                $cellValue = "③ユーザ自身が\n届出し確認した件数";
                $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $cellValue);
                $objActSheet->mergeCells('H' . $rowNum . ':I' . ($rowNum + 1));
                $objActSheet->getStyle('H' . $rowNum . ':I' . ($rowNum + 1))->getAlignment()->setWrapText(true);
                $objActSheet->getStyle('H' . $rowNum . ':I' . ($rowNum + 1))->applyFromArray($centerlStyle);
                $cellValue = "届出率(％)\n（②＋③）÷①";
                $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue);
                $objActSheet->mergeCells('J' . $rowNum . ':K' . ($rowNum + 1));
                $objActSheet->getStyle('J' . $rowNum . ':K' . ($rowNum + 1))->getAlignment()->setWrapText(true);
                $objActSheet->getStyle('J' . $rowNum . ':K' . ($rowNum + 1))->applyFromArray($centerlStyle);

                $rowNum += 2;
                $cellValue = "1.新車新規";
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
                $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
                $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
                $cellValue = $detail['data'][$i]["SINSEICNT"];
                $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
                $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i]["TODOKECNT"];
                $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
                $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i]["KAKUNINCNT"];
                $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
                $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i]["TODOKE_RITU"];
                $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
                $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
                $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);

                $rowNum += 1;
                $cellValue = "2.中古新規";
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
                $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
                $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
                $cellValue = $detail['data'][$i + 1]["SINSEICNT"];
                $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
                $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i + 1]["TODOKECNT"];
                $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
                $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i + 1]["KAKUNINCNT"];
                $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
                $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i + 1]["TODOKE_RITU"];
                $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
                $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
                $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);

                $rowNum += 1;
                $cellValue = "3.転　　　入";
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
                $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
                $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
                $cellValue = $detail['data'][$i + 2]["SINSEICNT"];
                $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
                $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i + 2]["TODOKECNT"];
                $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
                $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i + 2]["KAKUNINCNT"];
                $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
                $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i + 2]["TODOKE_RITU"];
                $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
                $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
                $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);

                $rowNum += 1;
                $cellValue = "4.記　　　入";
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
                $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
                $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
                $cellValue = $detail['data'][$i + 3]["SINSEICNT"];
                $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
                $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i + 3]["TODOKECNT"];
                $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
                $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i + 3]["KAKUNINCNT"];
                $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $cellValue . "件");
                $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
                $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $detail['data'][$i + 3]["TODOKE_RITU"];
                $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
                $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
                $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);

                $rowNum += 1;
                $cellValue = "5.合　　　計";
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
                $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
                $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
                $s1 = $detail['data'][$i]["SINSEICNT"] + $detail['data'][$i + 1]["SINSEICNT"] + $detail['data'][$i + 2]["SINSEICNT"] + $detail['data'][$i + 3]["SINSEICNT"];
                $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $s1 . "件");
                $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
                $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
                $s2 = $detail['data'][$i]["TODOKECNT"] + $detail['data'][$i + 1]["TODOKECNT"] + $detail['data'][$i + 2]["TODOKECNT"] + $detail['data'][$i + 3]["TODOKECNT"];
                $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $s2 . "件");
                $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
                $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
                $s3 = $detail['data'][$i]["KAKUNINCNT"] + $detail['data'][$i + 1]["KAKUNINCNT"] + $detail['data'][$i + 2]["KAKUNINCNT"] + $detail['data'][$i + 3]["KAKUNINCNT"];
                $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $s3 . "件");
                $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
                $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
                $cellValue = $s1 == 0 ? "0" : $this->FncRoundA((($s3 + $s2) / $s1) * 100, 1);
                $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
                $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
                $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);

                if ($detail['data'][$i]['SINSEI_KB'] == "1") {
                    $sum1 += (double) $detail['data'][$i]['SINSEICNT'];
                    $sum2 += (double) $detail['data'][$i]['TODOKECNT'];
                    $sum3 += (double) $detail['data'][$i]['KAKUNINCNT'];
                }
                if ($detail['data'][$i + 1]['SINSEI_KB'] == "2") {
                    $sum5 += (double) $detail['data'][$i + 1]['SINSEICNT'];
                    $sum6 += (double) $detail['data'][$i + 1]['TODOKECNT'];
                    $sum7 += (double) $detail['data'][$i + 1]['KAKUNINCNT'];
                }
                if ($detail['data'][$i + 2]['SINSEI_KB'] == "3") {
                    $sum9 += (double) $detail['data'][$i + 2]['SINSEICNT'];
                    $sum10 += (double) $detail['data'][$i + 2]['TODOKECNT'];
                    $sum11 += (double) $detail['data'][$i + 2]['KAKUNINCNT'];
                }
                if ($detail['data'][$i + 3]['SINSEI_KB'] == "4") {
                    $sum13 += (double) $detail['data'][$i + 3]['SINSEICNT'];
                    $sum14 += (double) $detail['data'][$i + 3]['TODOKECNT'];
                    $sum15 += (double) $detail['data'][$i + 3]['KAKUNINCNT'];
                }
                $i += 4;
                $rowNum += 1;
            }
            $rowNum += 1;
            $cellValue = "部署合計";
            $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
            $objActSheet->getStyle('A' . $rowNum)->applyFromArray($leftStyle);

            $rowNum += 1;
            $cellValue = "申請区分";
            $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
            $objActSheet->mergeCells('A' . $rowNum . ':B' . ($rowNum + 1));
            $objActSheet->getStyle('A' . $rowNum . ':B' . ($rowNum + 1))->applyFromArray($centerlStyle);
            $cellValue = "①保管場所届出義務を\n伴う検査等申請件数";
            $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $cellValue);
            $objActSheet->mergeCells('C' . $rowNum . ':E' . ($rowNum + 1));
            $objActSheet->getStyle('C' . $rowNum . ':E' . ($rowNum + 1))->getAlignment()->setWrapText(true);
            $objActSheet->getStyle('C' . $rowNum . ':E' . ($rowNum + 1))->applyFromArray($centerlStyle)->getAlignment()->setWrapText(true);
            $cellValue = "②警察への保管\n場所届出件数";
            $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $cellValue);
            $objActSheet->mergeCells('F' . $rowNum . ':G' . ($rowNum + 1));
            $objActSheet->getStyle('F' . $rowNum . ':G' . ($rowNum + 1))->getAlignment()->setWrapText(true);
            $objActSheet->getStyle('F' . $rowNum . ':G' . ($rowNum + 1))->applyFromArray($centerlStyle);
            $cellValue = "③ユーザ自身が\n届出し確認した件数";
            $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $cellValue);
            $objActSheet->mergeCells('H' . $rowNum . ':I' . ($rowNum + 1));
            $objActSheet->getStyle('H' . $rowNum . ':I' . ($rowNum + 1))->getAlignment()->setWrapText(true);
            $objActSheet->getStyle('H' . $rowNum . ':I' . ($rowNum + 1))->applyFromArray($centerlStyle);
            $cellValue = "届出率(％)\n（②＋③）÷①";
            $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue);
            $objActSheet->mergeCells('J' . $rowNum . ':K' . ($rowNum + 1));
            $objActSheet->getStyle('J' . $rowNum . ':K' . ($rowNum + 1))->getAlignment()->setWrapText(true);
            $objActSheet->getStyle('J' . $rowNum . ':K' . ($rowNum + 1))->applyFromArray($centerlStyle);

            $rowNum += 2;
            $cellValue = "1.新車新規";
            $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
            $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
            $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
            $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $sum1 . "件");
            $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
            $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
            $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $sum2 . "件");
            $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
            $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
            $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $sum3 . "件");
            $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
            $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
            $cellValue = $sum1 == 0 ? "0" : $this->FncRoundA((($sum2 + $sum3) / $sum1) * 100, 1);
            $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
            $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
            $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);

            $rowNum += 1;
            $cellValue = "2.中古新規";
            $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
            $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
            $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
            $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $sum5 . "件");
            $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
            $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
            $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $sum6 . "件");
            $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
            $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
            $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $sum7 . "件");
            $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
            $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
            $cellValue = $sum5 == 0 ? "0" : $this->FncRoundA((($sum6 + $sum7) / $sum5) * 100, 1);
            $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
            $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
            $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);

            $rowNum += 1;
            $cellValue = "3.転　　　入";
            $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
            $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
            $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
            $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $sum9 . "件");
            $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
            $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
            $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $sum10 . "件");
            $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
            $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
            $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $sum11 . "件");
            $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
            $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
            $cellValue = $sum9 == 0 ? "0" : $this->FncRoundA((($sum10 + $sum11) / $sum9) * 100, 1);
            $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
            $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
            $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);

            $rowNum += 1;
            $cellValue = "4.記　　　入";
            $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
            $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
            $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
            $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $sum13 . "件");
            $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
            $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
            $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $sum14 . "件");
            $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
            $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
            $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $sum15 . "件");
            $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
            $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
            $cellValue = $sum13 == 0 ? "0" : $this->FncRoundA((($sum14 + $sum15) / $sum13) * 100, 1);
            $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
            $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
            $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);

            $rowNum += 1;
            $cellValue = "5.合　　　計";
            $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $cellValue);
            $objActSheet->mergeCells('A' . $rowNum . ':B' . $rowNum);
            $objActSheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerlStyle);
            $sumAll1 = $sum1 + $sum5 + $sum9 + $sum13;
            $objActSheet->setCellValue($this->getColumnLetter(2) . $rowNum, $sumAll1 . "件");
            $objActSheet->mergeCells('C' . $rowNum . ':E' . $rowNum);
            $objActSheet->getStyle('C' . $rowNum . ':E' . $rowNum)->applyFromArray($rightStyle);
            $sumAll2 = $sum2 + $sum6 + $sum10 + $sum14;
            $objActSheet->setCellValue($this->getColumnLetter(5) . $rowNum, $sumAll2 . "件");
            $objActSheet->mergeCells('F' . $rowNum . ':G' . $rowNum);
            $objActSheet->getStyle('F' . $rowNum . ':G' . $rowNum)->applyFromArray($rightStyle);
            $sumAll3 = $sum3 + $sum7 + $sum11 + $sum15;
            $objActSheet->setCellValue($this->getColumnLetter(7) . $rowNum, $sumAll3 . "件");
            $objActSheet->mergeCells('H' . $rowNum . ':I' . $rowNum);
            $objActSheet->getStyle('H' . $rowNum . ':I' . $rowNum)->applyFromArray($rightStyle);
            $cellValue = $sumAll1 == 0 ? "0" : $this->FncRoundA((($sumAll2 + $sumAll3) / $sumAll1) * 100, 1);
            $objActSheet->setCellValue($this->getColumnLetter(9) . $rowNum, $cellValue . "%");
            $objActSheet->mergeCells('J' . $rowNum . ':K' . $rowNum);
            $objActSheet->getStyle('J' . $rowNum . ':K' . $rowNum)->applyFromArray($percentageStyle);
            //10.71
            $objActSheet->getDefaultColumnDimension()->setWidth(11.4);
            //ブック作成
            $objActSheet->setSelectedCell("A1");
            //active sheet1
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($filefullpath);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $file = "files/HMTVE/" . $strFileName;
            $res['data'] = $file;
            //エラーがない場合、コミットする
            $this->HMTVE250ReportPlaceCntTotal->Do_commit();
            $tranStartFlg = FALSE;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE250ReportPlaceCntTotal->Do_rollback();
                //成約プレゼント確定データの更新処理を行う
                $appoint = $this->HMTVE250ReportPlaceCntTotal->updateAppoint($yearmon);
                if (!$appoint['result']) {
                    $res['result'] = FALSE;
                    $res['error'] = $appoint['data'];
                    return $res;
                }
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    //四捨五入
    public function FncRoundA($dValue, $iDigits)
    {
        try {
            $dCoef = pow(10, $iDigits);
            if ($dValue > 0) {
                $sum = (floor($dValue * $dCoef + 0.5)) / $dCoef;
                if (preg_match("/^[1-9][0-9]*$/", $sum)) {
                    return $sum;
                } else {
                    //使用sprintf保留1位小数，round和直接除法并不好用
                    return sprintf("%.1f", $sum);
                }
            } else {
                $sum = (ceil($dValue * $dCoef - 0.5)) / $dCoef;
                if (preg_match("/^[1-9][0-9]*$/", $sum)) {
                    return $sum;
                } else {
                    //使用sprintf保留1位小数，round和直接除法并不好用
                    return sprintf("%.1f", $sum);
                }
            }
        } catch (\Exception $e) {
            return -1;
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
