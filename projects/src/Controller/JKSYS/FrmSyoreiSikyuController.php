<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmSyoreiSikyu;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmSyoreiSikyuController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
        $this->loadComponent('ClsLogControl');
    }
    public $intState = 0;
    public $lngOutCntG = 0;
    public $lngOutCntT = 0;
    public $frmSyoreiSikyu;
    public $dateTimePicker1 = '';

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmSyoreiSikyu_layout');
    }

    //フォームロード
    public function frmSyoreiSikyuLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->frmSyoreiSikyu = new FrmSyoreiSikyu();
            //人事ｺﾝﾄﾛｰﾙﾏｽﾀの取得を行う
            $tblCTL = $this->frmSyoreiSikyu->fncJinjiCtlMstSQL();
            if (!$tblCTL['result']) {
                throw new \Exception($tblCTL['data']);
            }
            $SYORI_YM = "";
            if ($tblCTL['row'] > 0) {
                $SYORI_YM = $tblCTL['data'][0]['SYORI_YM'];
                //日付形式を確認する
                $date = $SYORI_YM . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("W9999");
            }
            $result['data']['SYORI_YM'] = $SYORI_YM;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //EXCELファイル出力
    public function cmdExcelClick()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $this->dateTimePicker1 = $_POST['data']['dateTimePicker1'];
                $kbn = $_POST['data']['kbn'];

                switch ($kbn) {
                    //出力対象＝業績奨励手当・全部署一括の場合
                    case 1:
                        $filePath = "業績奨励手当計算書_一括_" . $this->dateTimePicker1 . ".xls";
                        $strOutTarget = "業績奨励手当・全部署一括";
                        break;
                    //出力対象＝業績奨励手当・店舗別の場合
                    case 2:
                        $filePath = "業績奨励手当計算書_部署：@@@_" . $this->dateTimePicker1 . ".xls";
                        $strOutTarget = null;
                        break;
                    //出力対象＝業績奨励手当・店舗別の場合
                    case 3:
                        $filePath = "店長奨励手当計算書_" . $this->dateTimePicker1 . ".xls";
                        $strOutTarget = "店長奨励手当";
                        break;
                }

                //***Excel出力処理****
                $basePath = dirname(dirname(dirname(__FILE__)));
                $tmpPath = $basePath . "/" . $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
                if ($this->ClsComFncJKSYS->FncGetPath("JksysPathFrom") == "") {
                    throw new \Exception('W0001');
                }
                if (($this->ClsComFncJKSYS->FncFileExists($tmpPath)) == FALSE) {
                    throw new \Exception("W0015");
                }
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                //出力Excel
                $file = $tmpPath . $filePath;
                if (file_exists($file) && !is_writable($file)) {
                    throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
                } elseif (!file_exists($file)) {
                    $dir = @opendir(dirname($file));
                    if ($dir === false) {
                        //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                        throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                    }
                    if (@readdir($dir) == false) {
                        throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                    }
                    @closedir($dir);
                }

                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $this->ClsComFncJKSYS->FncGetPath("JksysExcelLayoutPath");

                //ログ管理のため
                $this->intState = 9;

                //テンプレートファイルの存在確認
                $errmsg = '';
                if ($kbn == 1 || $kbn == 2) {
                    $strTemplatePath = $basePath . '/' . $strTemplatePath . "FrmSyoreiSikyuGyousekiTemplate.xlt";
                    //業績奨励手当計算書
                    $errmsg = "業績奨励手当計算書のテンプレートが見つかりません！";
                } elseif ($kbn == 3) {
                    $strTemplatePath = $basePath . '/' . $strTemplatePath . "FrmSyoreiSikyuTencyouTemplate.xlt";
                    //業績奨励手当計算書
                    $errmsg = "店長奨励手当計算書のテンプレートが見つかりません！";
                }
                if (!file_exists($strTemplatePath)) {
                    throw new \Exception($errmsg);
                }
                //Excel出力
                $result = $this->fncExcelOutput($strTemplatePath, $file, $kbn);
                if (!$result['result']) {
                    throw new \Exception($result['error']);
                }
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        //ログ管理
        try {
            //intState<>0の場合、ログ管理テーブルに登録
            if ($this->intState <> 0) {
                //業績奨励手当
                $log_result = $this->ClsLogControl->fncLogEntryJksys("FrmSyoreiSikyu_GyousekiSyoureiTeate_Excel", $this->intState, $this->lngOutCntG, $this->dateTimePicker1, $strOutTarget, $strTemplatePath);
                if (!$log_result['result']) {
                    throw new \Exception($log_result['Msg']);
                }
                //店長奨励手当
                $log_result = $this->ClsLogControl->fncLogEntryJksys("FrmSyoreiSikyu_TencyouSyoureiTeate_Excel", $this->intState, $this->lngOutCntT, $this->dateTimePicker1, $strOutTarget, $strTemplatePath);
                if (!$log_result['result']) {
                    throw new \Exception($log_result['Msg']);
                }
            }
        } catch (\Exception $e1) {
            $result['result'] = FALSE;
            $result['error'] = $e1->getMessage();
        }
        $this->fncReturn($result);
    }

    //十进制asc码转字符
    public function decode($str, $prefix = "&#")
    {
        $str = str_replace($prefix, '', $str);
        $a = explode(';', $str);
        $utf = '';
        foreach ($a as $dec) {
            if ($dec < 128) {
                $utf .= chr($dec);
            } else
                if ($dec < 2048) {
                    $utf .= chr(192 + ($dec - ($dec % 64)) / 64);
                    $utf .= chr(128 + ($dec % 64));
                } else {
                    $utf .= chr(224 + ($dec - ($dec % 4096)) / 4096);
                    $utf .= chr(128 + (($dec % 4096) - ($dec % 64)) / 64);
                    $utf .= chr(128 + ($dec % 64));
                }
        }
        return $utf;
    }

    //Excel出力
    private function fncExcelOutput($strTemplatePath, $strFilePathDef, $kbn)
    {
        $objPHPExcel = null;
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            // include __DIR__ . '/Component/Classes/PHPExcel.php';
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();

            $this->frmSyoreiSikyu = new FrmSyoreiSikyu();

            $styleBlue = array(
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('rgb' => 'CFFFFF')
                )
            );

            //画面.業績奨励手当にチェックが入っている場合
            //◆◆◆　業績奨励手当・全部署一括　◆◆◆
            if ($kbn == 1) {
                $strFilePath = $strFilePathDef;
                //ヘッダー **********
                $objActSheet->setCellValue('C1', $this->getPreMonth($this->dateTimePicker1, 0) . "月業績奨励手当支給計算書");
                $objActSheet->setCellValue('D5', $this->getPreMonth($this->dateTimePicker1, -2) . "月");
                $objActSheet->setCellValue('E5', $this->getPreMonth($this->dateTimePicker1, -1) . "月");

                //係数種類の取得
                $dt1 = $this->frmSyoreiSikyu->fncKeisuSyuruiSQL();
                if (!$dt1['result']) {
                    throw new \Exception($dt1['data']);
                }
                if ($dt1['row'] == 0) {
                    //ログ管理のため
                    $this->intState = 1;
                    $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='10000'のデータが存在しません。";
                    throw new \Exception("I9999");
                }
                //单元格缩进
                $objActSheet->getStyle('G5:O5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $objActSheet->getStyle('G5:O5')->getAlignment()->setIndent(1);
                $objActSheet->getStyle('D6:E6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $objActSheet->getStyle('D6:E6')->getAlignment()->setIndent(1);
                $objActSheet->getStyle('G5:O5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $objActSheet->getStyle('D6:E6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                //係数種類
                $maxValue = $dt1['row'] - 1;
                //フォーマット一時退避
                for ($i = 0; $i < $maxValue - 2; $i++) {
                    $objActSheet->insertNewColumnBefore('I', 1);
                    $objActSheet->getColumnDimension('I')->setWidth($objActSheet->getColumnDimension('H')->getWidth());
                    $objActSheet->mergeCells('I5:I7');
                }
                //保存H9的样式
                $objActSheet->duplicateStyle($objActSheet->getStyle('H9'), 'B2');
                //模板上有三列，数据少时删除模板上多余的列
                if ($maxValue < 2) {
                    $objActSheet->removeColumn(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1 + 1), 2 - $maxValue);
                    $boderCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1);
                    $objActSheet->getStyle($boderCol . '12')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                for ($i = 0; $i <= $maxValue; $i++) {
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $i + 1) . 5, $dt1['data'][$i]['MEISYO']);
                    //&#9315 -> ④
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $i + 1) . 8, $this->decode('&#' . (9315 + $i)));
                }
                //係数合計
                $s1 = "x " . $this->decode('&#9315') . "～" . $this->decode('&#' . (9315 + $maxValue));
                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1 + 1) . 7, $s1);
                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 1)));

                $s1 = $this->decode('&#9314') . " x " . $this->decode('&#' . (9315 + $maxValue + 1));
                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 2 + 1) . 7, $s1);
                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 2 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 2)));

                //値1取得（種別コード"10001"）
                $dt2 = $this->frmSyoreiSikyu->fnc10001Atai1SQL();
                if (!$dt2['result']) {
                    throw new \Exception($dt2['data']);
                }
                if ($dt2['row'] == 0) {
                    //ログ管理のため
                    $this->intState = 1;
                    $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='10001'のデータが存在しません。";
                    throw new \Exception("I9999");
                }

                $s1 = '';
                for ($i = 0; $i < $dt2['row']; $i++) {
                    $s1 .= 'x ' . $dt2['data'][$i]['ATAI_1'];
                    if ($i <> $dt2['row'] - 1)
                        $s1 .= "\r\n";
                }
                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 4 + 1) . 7, $s1);
                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 4 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 3)));

                //値1取得（種別コード"12000" コード"1"
                $s2 = '';
                $s2 .= '( ' . $this->decode('&#' . (9315 + $maxValue + 2)) . ' - ' . $this->decode('&#' . (9315 + $maxValue + 3)) . ' )';
                $s2 .= "\r\n";
                $dt2 = $this->frmSyoreiSikyu->fnc12000Atai1SQL();
                if (!$dt2['result']) {
                    throw new \Exception($dt2['data']);
                }
                if ($dt2['row'] == 0) {
                    //ログ管理のため
                    $this->intState = 1;
                    $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='12000' and コード='1'のデータが存在しません。";
                    throw new \Exception("I9999");
                }
                // &#8806 -> ≦
                $s2 .= 'x ' . $dt2['data'][0]['ATAI_1'] . $this->decode('&#8806');
                $s2 .= "\r\n";
                //値1取得（種別コード"JOGEN" コード"1"）
                $dt2 = $this->frmSyoreiSikyu->fncJOGENCode1Atai1SQL();
                if (!$dt2['result']) {
                    throw new \Exception($dt2['data']);
                }
                if ($dt2['row'] == 0) {
                    //ログ管理のため
                    $this->intState = 1;
                    $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='JOGEN' and コード='1'のデータが存在しません。";
                    throw new \Exception("I9999");
                }
                $s2 .= $dt2['data'][0]['ATAI_1'];

                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 5 + 1) . 7, $s2);
                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 5 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 4)));
                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 6 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 5)));
                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 7 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 6)));

                //*******************
                //明細 **************
                $dt2 = $this->frmSyoreiSikyu->fncGyousekiSyoureiTeateSQL($this->dateTimePicker1);
                if (!$dt2['result']) {
                    throw new \Exception($dt2['data']);
                }
                if ($dt2['row'] > 0) {
                    //行数
                    $rowCnt = 0;
                    //社员番号
                    $syaNo = '';
                    //插入行的行号
                    $insert_start_row = 11;
                    foreach ((array) $dt2['data'] as $value) {
                        if ($syaNo != $value['SYAIN_NO']) {
                            $syaNo = $value['SYAIN_NO'];
                            //行追加
                            if ($rowCnt != 0) {
                                $objActSheet->insertNewRowBefore($insert_start_row, 2);
                                //设置插入行的中间列样式
                                $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1);
                                $objActSheet->duplicateStyle($objActSheet->getStyle('B2'), 'G' . $insert_start_row . ':' . $lastCol . $insert_start_row);
                                //合并单元格
                                for ($i = 0; $i <= 10; $i++) {
                                    if ($i < 4) {
                                        $mergeColumn = 2 + $i;
                                        $mergeColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($mergeColumn + 1);
                                    } else {
                                        $mergeColumn = 2 + $maxValue + 1 + $i;
                                        $mergeColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($mergeColumn + 1);
                                    }
                                    $cellRange = $mergeColumn . $insert_start_row . ":" . $mergeColumn . ($insert_start_row + 1);
                                    $objActSheet->mergeCells($cellRange);
                                }

                                //设置插入行中间列的背景颜色
                                $lastDataCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1);
                                $objActSheet->getStyle('G' . $insert_start_row . ':' . $lastDataCol . $insert_start_row)->applyFromArray($styleBlue);

                                $insert_start_row += 2;
                            }
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(0 + 1) . (9 + ($rowCnt * 2)), $value['BUSYO_CD']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1 + 1) . (9 + ($rowCnt * 2)), (int) $value['SYAIN_NO']);

                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(0 + 1) . (9 + ($rowCnt * 2) + 1), $value['BUSYO_CD']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1 + 1) . (9 + ($rowCnt * 2) + 1), (int) $value['SYAIN_NO']);

                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(2 + 1) . (9 + ($rowCnt * 2)), $value['SYAIN_NM']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + 1) . (9 + ($rowCnt * 2)), $value['GENKAI_RIEKI1']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(4 + 1) . (9 + ($rowCnt * 2)), $value['GENKAI_RIEKI2']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + 1) . (9 + ($rowCnt * 2)), $value['GENKAI_RIEKI']);

                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1 + 1) . (9 + ($rowCnt * 2)), $value['KEISU_TOTAL']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 2 + 1) . (9 + ($rowCnt * 2)), $value['SANSYUTU_KINGAKU']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 4 + 1) . (9 + ($rowCnt * 2)), $value['ZEN_SOUSIKYU']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 5 + 1) . (9 + ($rowCnt * 2)), $value['SHIHARAI_SYOUREIKIN']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 6 + 1) . (9 + ($rowCnt * 2)), $value['ZANGYO_TEATE']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 7 + 1) . (9 + ($rowCnt * 2)), $value['SYOREI_TEATE']);

                            $rowCnt++;
                        }
                        //表示マージ
                        foreach ((array) $dt1['data'] as $dt1_key => $dt1_value) {
                            if ($value['HYOJI_JUN'] == null) {
                                continue;
                            }
                            if ($dt1_value['HYOJI_JUN'] == $value['HYOJI_JUN']) {
                                if ($value['KEISU_1'] != "") {
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (9 + ($rowCnt - 1) * 2), $value['KEISU_1']);
                                } else {
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (9 + ($rowCnt - 1) * 2), $value['JISSEKI_OFF']);
                                }
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (10 + ($rowCnt - 1) * 2), $value['KEISU_2']);
                                if ($value['KEISU_1'] == "") {
                                    $objActSheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (10 + ($rowCnt - 1) * 2))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
                                } else {
                                    //実績がNULLでない場合、白とする
                                    $objActSheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (10 + ($rowCnt - 1) * 2))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
                                }
                                break;
                            }
                            if ($dt1_key == $dt1['row'] - 1) {
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (9 + ($rowCnt - 1) * 2), "");
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (10 + ($rowCnt - 1) * 2), "");
                            }
                        }
                    }
                } else {
                    //ログ管理のため
                    $this->intState = 1;

                    throw new \Exception("I0001");
                }
                //*******************
                //フッター **********
                foreach ((array) $dt1['data'] as $dt1_key => $dt1_value) {
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (9 + ($rowCnt * 2) + 1), $dt1_value['ATAI_2']);
                }
                //隐藏行删除后，单元格公式修改
                $objActSheet->removeRow(9 + $rowCnt * 2, 1);
                //还原B2样式
                $objActSheet->duplicateStyle($objActSheet->getStyle('B1'), 'B2');
                $formulas_value = $objActSheet->getCell($objActSheet->getHighestColumn() . (9 + $rowCnt * 2))->getValue();
                $formulas_cell = $objActSheet->getHighestColumn() . (9 + $rowCnt * 2);
                $formulas_cell_pre_row = $objActSheet->getHighestColumn() . (8 + $rowCnt * 2);
                $formulas_value = str_replace($formulas_cell, $formulas_cell_pre_row, $formulas_value);
                $objActSheet->setCellValue($formulas_cell, $formulas_value);

                $objActSheet->setSelectedCell("A1");

                $this->lngOutCntG = $dt2['row'];
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
                $objWriter->setPreCalculateFormulas(false);
                $objWriter->save($strFilePath);
            }
            //◆◆◆　業績奨励手当・店舗別　◆◆◆
            elseif ($kbn == 2) {
                //部署一覧の取得
                $dt0 = $this->frmSyoreiSikyu->fncGyousekiSyoureiTeateBusyoSQL($this->dateTimePicker1);
                if (!$dt0['result']) {
                    throw new \Exception($dt0['data']);
                }
                if ($dt0['row'] == 0) {
                    $this->intState = 1;
                    $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' のデータが存在しません。";
                    throw new \Exception("I9999");
                }
                foreach ((array) $dt0['data'] as $dt0_value) {
                    $objPHPExcel = $objReader->load($strTemplatePath);
                    $objPHPExcel->setActiveSheetIndex(0);
                    $objActSheet = $objPHPExcel->getActiveSheet();

                    $strBusyoCD = $dt0_value['BUSYO_CD'];
                    $strFilePath = str_replace("@@@", $strBusyoCD, $strFilePathDef);

                    //ヘッダー **********
                    $objActSheet->setCellValue('C1', $this->getPreMonth($this->dateTimePicker1, 0) . "月業績奨励手当支給計算書　（部署：" . $strBusyoCD . ")");
                    $objActSheet->setCellValue('D5', $this->getPreMonth($this->dateTimePicker1, -2) . "月");
                    $objActSheet->setCellValue('E5', $this->getPreMonth($this->dateTimePicker1, -1) . "月");

                    //係数種類の取得
                    $dt1 = $this->frmSyoreiSikyu->fncKeisuSyuruiSQL();
                    if (!$dt1['result']) {
                        throw new \Exception($dt1['data']);
                    }
                    if ($dt1['row'] == 0) {
                        //ログ管理のため
                        $this->intState = 1;
                        $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='10000'のデータが存在しません。";
                        throw new \Exception("I9999");
                    }
                    //係数種類
                    $maxValue = $dt1['row'] - 1;
                    //单元格缩进
                    $objActSheet->getStyle('G5:O5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $objActSheet->getStyle('G5:O5')->getAlignment()->setIndent(1);
                    $objActSheet->getStyle('D6:E6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $objActSheet->getStyle('D6:E6')->getAlignment()->setIndent(1);
                    $objActSheet->getStyle('G5:O5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $objActSheet->getStyle('D6:E6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    //フォーマット一時退避
                    for ($i = 0; $i < $maxValue - 2; $i++) {
                        $objActSheet->insertNewColumnBefore('I', 1);
                        $objActSheet->getColumnDimension('I')->setWidth($objActSheet->getColumnDimension('H')->getWidth());
                        $objActSheet->mergeCells('I5:I7');
                    }
                    //保存H9的样式
                    $objActSheet->duplicateStyle($objActSheet->getStyle('H9'), 'B2');
                    //模板上有三列，数据少时删除模板上多余的列
                    if ($maxValue < 2) {
                        $objActSheet->removeColumn(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1 + 1), 2 - $maxValue);
                        $boderCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1);
                        $objActSheet->getStyle($boderCol . '12')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }
                    for ($i = 0; $i <= $maxValue; $i++) {
                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $i + 1) . 5, $dt1['data'][$i]['MEISYO']);
                        //&H8743 -> ④
                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $i + 1) . 8, $this->decode('&#' . (9315 + $i)));
                    }
                    //係数合計
                    $s1 = 'x ' . $this->decode('&#9315') . '～' . $this->decode('&#' . (9315 + $maxValue));
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1 + 1) . 7, $s1);
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 1)));

                    $s1 = $this->decode('&#9314') . ' x ' . $this->decode('&#' . (9315 + $maxValue + 1));
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 2 + 1) . 7, $s1);
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 2 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 2)));

                    //値1取得（種別コード"10001"）
                    $dt2 = $this->frmSyoreiSikyu->fnc10001Atai1SQL();
                    if (!$dt2['result']) {
                        throw new \Exception($dt2['data']);
                    }
                    if ($dt2['row'] == 0) {
                        //ログ管理のため
                        $this->intState = 1;
                        $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='10001'のデータが存在しません。";
                        throw new \Exception("I9999");
                    }

                    $s1 = '';
                    for ($i = 0; $i < $dt2['row']; $i++) {
                        $s1 .= 'x ' . $dt2['data'][$i]['ATAI_1'];
                        if ($i <> $dt2['row'] - 1)
                            $s1 .= "\r\n";
                    }
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 4 + 1) . 7, $s1);
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 4 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 3)));

                    $s2 = '';
                    $s2 .= '( ' . $this->decode('&#' . (9315 + $maxValue + 2)) . ' - ' . $this->decode('&#' . (9315 + $maxValue + 3)) . ' )';
                    $s2 .= "\r\n";
                    //値1取得（種別コード"12000" コード"1"
                    $dt2 = $this->frmSyoreiSikyu->fnc12000Atai1SQL();
                    if (!$dt2['result']) {
                        throw new \Exception($dt2['data']);
                    }
                    if ($dt2['row'] == 0) {
                        //ログ管理のため
                        $this->intState = 1;
                        $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='12000' and コード='1'のデータが存在しません。";
                        throw new \Exception('I9999');
                    }
                    $s2 .= 'x ' . $dt2['data'][0]['ATAI_1'] . $this->decode('&#8806');
                    $s2 .= "\r\n";
                    //値1取得（種別コード"JOGEN" コード"1"）
                    $dt2 = $this->frmSyoreiSikyu->fncJOGENCode1Atai1SQL();
                    if (!$dt2['result']) {
                        throw new \Exception($dt2['data']);
                    }
                    if ($dt2['row'] == 0) {
                        //ログ管理のため
                        $this->intState = 1;
                        $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='JOGEN' and コード='1'のデータが存在しません。";
                        throw new \Exception('I9999');
                    }
                    $s2 .= $dt2['data'][0]['ATAI_1'];

                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 5 + 1) . 7, $s2);
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 5 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 4)));
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 6 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 5)));
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 7 + 1) . 8, $this->decode('&#' . (9315 + $maxValue + 6)));

                    //*******************
                    //明細 **************
                    $dt2 = $this->frmSyoreiSikyu->fncGyousekiSyoureiTeateSQL($this->dateTimePicker1, $strBusyoCD);
                    if (!$dt2['result']) {
                        throw new \Exception($dt2['data']);
                    }
                    if ($dt2['row'] > 0) {
                        //行数
                        $rowCnt = 0;
                        //社员番号
                        $syaNo = "";
                        //插入行的行号
                        $insert_start_row = 11;
                        foreach ((array) $dt2['data'] as $value) {
                            if ($syaNo != $value['SYAIN_NO']) {
                                $syaNo = $value['SYAIN_NO'];
                                //行追加
                                if ($rowCnt != 0) {
                                    $objActSheet->insertNewRowBefore($insert_start_row, 2);
                                    //设置插入行的中间列样式
                                    $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1);
                                    $objActSheet->duplicateStyle($objActSheet->getStyle('B2'), 'G' . $insert_start_row . ':' . $lastCol . $insert_start_row);
                                    //合并单元格
                                    for ($i = 0; $i <= 10; $i++) {
                                        if ($i < 4) {
                                            $mergeColumn = 2 + $i;
                                            $mergeColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($mergeColumn + 1);
                                        } else {
                                            $mergeColumn = 2 + $maxValue + 1 + $i;
                                            $mergeColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($mergeColumn + 1);
                                        }
                                        $cellRange = $mergeColumn . $insert_start_row . ":" . $mergeColumn . ($insert_start_row + 1);
                                        $objActSheet->mergeCells($cellRange);

                                    }
                                    //设置插入行中间列的背景颜色
                                    $lastDataCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue);
                                    $objActSheet->getStyle('G' . $insert_start_row . ':' . $lastDataCol . $insert_start_row)->applyFromArray($styleBlue);

                                    $insert_start_row += 2;
                                }

                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(0 + 1) . (9 + ($rowCnt * 2)), $value['BUSYO_CD']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1 + 1) . (9 + ($rowCnt * 2)), (int) $value['SYAIN_NO']);

                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(0 + 1) . (9 + ($rowCnt * 2) + 1), $value['BUSYO_CD']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1 + 1) . (9 + ($rowCnt * 2) + 1), (int) $value['SYAIN_NO']);

                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(2 + 1) . (9 + ($rowCnt * 2)), $value['SYAIN_NM']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + 1) . (9 + ($rowCnt * 2)), $value['GENKAI_RIEKI1']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(4 + 1) . (9 + ($rowCnt * 2)), $value['GENKAI_RIEKI2']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + 1) . (9 + ($rowCnt * 2)), $value['GENKAI_RIEKI']);

                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 1 + 1) . (9 + ($rowCnt * 2)), $value['KEISU_TOTAL']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 2 + 1) . (9 + ($rowCnt * 2)), $value['SANSYUTU_KINGAKU']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 4 + 1) . (9 + ($rowCnt * 2)), $value['ZEN_SOUSIKYU']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 5 + 1) . (9 + ($rowCnt * 2)), $value['SHIHARAI_SYOUREIKIN']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 6 + 1) . (9 + ($rowCnt * 2)), $value['ZANGYO_TEATE']);
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $maxValue + 7 + 1) . (9 + ($rowCnt * 2)), $value['SYOREI_TEATE']);

                                $rowCnt++;
                            }
                            //表示マージ
                            foreach ((array) $dt1['data'] as $dt1_key => $dt1_value) {
                                if ($value['HYOJI_JUN'] == null) {
                                    continue;
                                }
                                if ($dt1_value['HYOJI_JUN'] == $value['HYOJI_JUN']) {
                                    if ($value['KEISU_1'] != "") {
                                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (9 + ($rowCnt - 1) * 2), $value['KEISU_1']);
                                    } else {
                                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (9 + ($rowCnt - 1) * 2), $value['JISSEKI_OFF']);
                                    }
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (10 + ($rowCnt - 1) * 2), $value['KEISU_2']);
                                    if ($value['KEISU_1'] == "") {
                                        $objActSheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (10 + ($rowCnt - 1) * 2))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
                                    } else {
                                        //実績がNULLでない場合、白とする
                                        $objActSheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (10 + ($rowCnt - 1) * 2))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
                                    }
                                    break;
                                }
                                if ($dt1_key == $dt1['row'] - 1) {
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (9 + ($rowCnt - 1) * 2), "");
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (10 + ($rowCnt - 1) * 2), "");
                                }
                            }
                        }
                    } else {
                        //ログ管理のため
                        $this->intState = 1;

                        throw new \Exception("I0001");
                    }
                    //*******************
                    //フッター **********
                    foreach ((array) $dt1['data'] as $dt1_key => $dt1_value) {
                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dt1_key + 1) . (9 + ($rowCnt * 2) + 1), $dt1_value['ATAI_2']);
                    }
                    //隐藏行删除后，单元格公式修改
                    $objActSheet->removeRow(9 + $rowCnt * 2, 1);
                    //还原B2样式
                    $objActSheet->duplicateStyle($objActSheet->getStyle('B1'), 'B2');
                    $formulas_value = $objActSheet->getCell($objActSheet->getHighestColumn() . (9 + $rowCnt * 2))->getValue();
                    $formulas_cell = $objActSheet->getHighestColumn() . (9 + $rowCnt * 2);
                    $formulas_cell_pre_row = $objActSheet->getHighestColumn() . (8 + $rowCnt * 2);
                    $formulas_value = str_replace($formulas_cell, $formulas_cell_pre_row, $formulas_value);
                    $objActSheet->setCellValue($formulas_cell, $formulas_value);

                    $objActSheet->setSelectedCell('A1');

                    $this->lngOutCntG = $dt2['row'];

                    $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
                    $objWriter->setPreCalculateFormulas(false);
                    $objWriter->save($strFilePath);
                }
            }
            //◆◆◆　店長奨励手当　◆◆◆
            else {
                $strFilePath = $strFilePathDef;
                // ヘッダー
                //和歴変換
                //20210303 CI UPD S
                $year = substr($this->dateTimePicker1, 0, 4);
                $month = substr($this->dateTimePicker1, 4, 2);
                if ($month < 10) {
                    $month = str_replace('0', '', $month);
                }
                // $ym_result = $this -> ClsComFncJKSYS -> japDateChange($year . (int)$month, '0');
                // if (!$ym_result['result'])
                // {
                // throw new \Exception($ym_result['error']);
                // }
                // $cellValue = $ym_result['data'] . '';
                // $objActSheet -> setCellValue('C1', substr($cellValue, 0, 8) . '年' . str_replace(substr($cellValue, 0, 8), '', $cellValue) . '月');
                $objActSheet->setCellValue('C1', $year . '年' . $month . '月');
                //20210303 CI UPD E
                //総限界利益掛け率の取得
                $dt1 = $this->frmSyoreiSikyu->fncGenkaiRiekiSQL();
                if (!$dt1['result']) {
                    throw new \Exception($dt1['data']);
                }
                if ($dt1['row'] == 0) {
                    //ログ管理のため
                    $this->intState = 1;
                    $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='22000' and コード='1'のデータが存在しません。";
                    throw new \Exception('I9999');
                }
                $objActSheet->setCellValue('C4', $dt1['data'][0]['ATAI_1'] . '％');
                //値1取得（種別コード"JOGEN" コード"2"）
                $dt2 = $this->frmSyoreiSikyu->fncJOGENCode2Atai1SQL();
                if (!$dt2['result']) {
                    throw new \Exception($dt2['data']);
                }
                if ($dt2['row'] == 0) {
                    //ログ管理のため
                    $this->intState = 1;
                    $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='JOGEN' and コード='2'のデータが存在しません。";
                    throw new \Exception('I9999');
                }
                $objActSheet->setCellValue('N4', sprintf('%.1f', $dt2['data'][0]['ATAI_1'] / 1000) . '千円');

                //係数取得（種別コード"20000" コード"<11"）
                $dt1 = $this->frmSyoreiSikyu->fncTencyouKeisuSQL();
                if (!$dt1['result']) {
                    throw new \Exception($dt1['data']);
                }
                if ($dt1['row'] == 0) {
                    //ログ管理のため
                    $this->intState = 1;
                    $result['data']['msg'] = "処理年月='" . date('Y/m', strtotime($this->dateTimePicker1 . '01')) . "' and 種別コード='20000' and コード<'11'のデータが存在しません。";
                    throw new \Exception('I9999');
                }
                $merge_cell_end = 10;
                foreach ((array) $dt1['data'] as $i => $value) {
                    if ($i != 0) {
                        $col_insert = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + ($i * 3) + 1);
                        $objActSheet->insertNewColumnBefore($col_insert, 3);
                        $objActSheet->mergeCells($col_insert . '2:' . (\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + ($i * 3) + 2 + 1)) . '3');
                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $i * 3 + 2 + 1) . 4, "=J4");

                        $objActSheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $i * 3 + 1))->setWidth($objActSheet->getColumnDimension('H')->getWidth());
                        $objActSheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(8 + $i * 3 + 1))->setWidth($objActSheet->getColumnDimension('I')->getWidth());
                        $objActSheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(9 + $i * 3 + 1))->setWidth($objActSheet->getColumnDimension('J')->getWidth());

                        $merge_cell_end += 3;
                    }

                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $i * 3 + 1) . 2, $value['MEISYO']);
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(8 + $i * 3 + 1) . 4, $value['ATAI_2']);
                }
                $maxValue = $dt1['row'];
                //値1の場合空白（列幅3.25）、それ以外は列削除
                $jo = 0;
                foreach ((array) $dt1['data'] as $i => $dt1_value) {
                    if ($dt1_value['ATAI_1'] == '1') {
                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + ($i * 3) - $jo + 1) . 4, '');
                        $objActSheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + ($i * 3) - $jo + 1))->setWidth(3.9);
                    } else {
                        $objActSheet->removeColumn(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $i * 3 - $jo + 1), 1);
                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $i * 3 - $jo + 1) . 2, $dt1_value['MEISYO']);
                        $jo++;

                        $merge_cell_end -= 1;
                    }
                }
                $objActSheet->removeColumn(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + ($maxValue * 3) - $jo + 1), 1);
                $merge_cell_end -= 1;
                //解除C1单元格以及重新合并单元格
                $objActSheet->unmergeCells('C1:' . (\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + ($maxValue * 3) - $jo + 1)) . '1');
                $objActSheet->mergeCells('C1:' . (\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($merge_cell_end + 1)) . '1');
                //*******************
                //明細 **************
                //フォーマット一時退避
                $dt2 = $this->frmSyoreiSikyu->fncTencyouSyoureiTeateSQL($this->dateTimePicker1);
                if (!$dt2['result']) {
                    throw new \Exception($dt2['data']);
                }
                $rowCnt = 0;
                $colCnt = 0;
                if ($dt2['row'] > 0) {
                    $bumonNum = 0;
                    $bumonCd = '';
                    $syainNo = '';
                    //--------------------------------
                    $blnBusyoBrk = false;
                    //--------------------------------
                    //部署+社員カウント
                    foreach ((array) $dt2['data'] as $value) {
                        if ($bumonCd != $value['BUSYO_CD'] || $syainNo != $value['SYAIN_NO']) {
                            if ($bumonNum > 2) {
                                $objActSheet->insertNewRowBefore(4 + $bumonNum, 1);
                            }
                            $bumonNum++;
                        }
                        $bumonCd = $value['BUSYO_CD'];
                        $syainNo = $value['SYAIN_NO'];
                    }
                    if ($bumonNum <= 2) {
                        for ($i = 0; $i < (3 - $bumonNum); $i++) {
                            $objActSheet->removeRow(5, 1);
                        }
                    }
                    $bumonCd = '';
                    $syainNo = '';
                    foreach ((array) $dt2['data'] as $value) {
                        if ($bumonCd != $value['BUSYO_CD'] || $syainNo != $value['SYAIN_NO']) {
                            if ($bumonCd != $value['BUSYO_CD']) {
                                $blnBusyoBrk = true;
                            } else {
                                $blnBusyoBrk = false;
                            }
                            $bumonCd = $value['BUSYO_CD'];
                            $syainNo = $value['SYAIN_NO'];

                            $objActSheet->setCellValue('A' . (5 + $rowCnt), $value['BUSYO_NM']);
                            //同一店舗の場合、「総限界利益」～「係数合計」は空白
                            if ($blnBusyoBrk) {
                                $objActSheet->setCellValue('B' . (5 + $rowCnt), $value['GENKAI_RIEKI']);
                                $objActSheet->setCellValue('C' . (5 + $rowCnt), $value['GENKAI_RIEKI_CALC']);
                                $objActSheet->setCellValue('D' . (5 + $rowCnt), $value['JININ']);
                                $objActSheet->setCellValue('E' . (5 + $rowCnt), $value['KEIJO_RIEKI_HON']);
                                $objActSheet->setCellValue('F' . (5 + $rowCnt), $value['KEIJO_RIEKI_ZEN']);
                                $objActSheet->setCellValue('G' . (5 + $rowCnt), $value['KEISU_ZEN']);

                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + (($maxValue * 3) - $jo + 0 + 1)) . (5 + $rowCnt), $value['KEISU_TOTAL']);
                            } else {
                                $objActSheet->setCellValue('B' . (5 + $rowCnt), '');
                                $objActSheet->setCellValue('C' . (5 + $rowCnt), '');
                                $objActSheet->setCellValue('D' . (5 + $rowCnt), '');
                                $objActSheet->setCellValue('E' . (5 + $rowCnt), '');
                                $objActSheet->setCellValue('F' . (5 + $rowCnt), '');
                                $objActSheet->setCellValue('G' . (5 + $rowCnt), '');

                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + (($maxValue * 3) - $jo + 0 + 1)) . (5 + $rowCnt), '');
                            }
                            //--------------------------------
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + (($maxValue * 3) - $jo + 1 + 1)) . (5 + $rowCnt), $value['SANSYUTU_KINGAKU']);
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + (($maxValue * 3) - $jo + 2 + 1)) . (5 + $rowCnt), $value['SYOREI_TEATE']);

                            $rowCnt++;
                            $colCnt = 0;
                        }
                        //表示マージ
                        foreach ((array) $dt1['data'] as $i => $dt1_value) {
                            if ($dt1_value['HYOJI_JUN'] == $value['HYOJI_JUN']) {
                                if ($blnBusyoBrk) {
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $colCnt + 1) . (4 + $rowCnt), $value['KEISU_1']);
                                } else {
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $colCnt + 1) . (4 + $rowCnt), '');
                                }
                                $colCnt += 1;
                                if ($dt1_value['ATAI_1'] == "1") {
                                    if ($blnBusyoBrk) {
                                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $colCnt + 1) . (4 + $rowCnt), $value['KEISU_2']);
                                    } else {
                                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $colCnt + 1) . (4 + $rowCnt), '');
                                    }
                                    $colCnt += 1;
                                }
                                if ($blnBusyoBrk) {
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $colCnt + 1) . (4 + $rowCnt), $value['KEISU']);
                                } else {
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $colCnt + 1) . (4 + $rowCnt), '');
                                }
                                $colCnt += 1;
                                break;
                            }

                            if ($i == ($dt1['row'] - 1)) {
                                if ($dt1_value['ATAI_1'] == "1") {
                                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $colCnt + 1) . (4 + $rowCnt), '');
                                    $colCnt += 1;
                                }
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $colCnt + 1) . (4 + $rowCnt), '');
                                $colCnt += 1;
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7 + $colCnt + 1) . (4 + $rowCnt), '');
                                $colCnt += 1;
                                break;
                            }
                        }
                    }
                } else {
                    //ログ管理のため
                    $this->intState = 1;
                    throw new \Exception('I0001');
                }

                $objActSheet->setSelectedCell('A1');

                $this->lngOutCntT = $dt2['row'];

                $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
                $objWriter->save($strFilePath);
            }
            $this->intState = 1;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        //キャッシュを解放
        if ($objPHPExcel) {
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel, $objActSheet);
        }
        return $result;
    }

    //年月-numか月の月
    private function getPreMonth($dateTimePicker1, $num)
    {
        $dateTimePicker1 = $dateTimePicker1 . "01";
        $rtnMon = date('m', strtotime("$dateTimePicker1 $num month"));

        return (int) $rtnMon;
    }

}
