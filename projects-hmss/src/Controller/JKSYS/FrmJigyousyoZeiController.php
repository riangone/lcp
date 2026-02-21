<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJigyousyoZei;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmJigyousyoZeiController extends AppController
{
    public $autoLayout = TRUE;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFncJKSYS');
    }

    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmJigyousyoZei_layout');
    }

    //データ取得
    public function fncGetJKCMST()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $FrmJigyousyoZei = new FrmJigyousyoZei();

            $resultMST = $FrmJigyousyoZei->FncGetJKCMST();
            if (!$resultMST['result']) {
                throw new \Exception($resultMST['data']);
            }
            $KISYU_YMD = "";
            $KIMATU_YMD = "";
            if ($resultMST['row'] > 0) {
                $KISYU_YMD = $resultMST['data'][0]['KISYU_YMD'];
                $KIMATU_YMD = $resultMST['data'][0]['KIMATU_YMD'];
                //日付形式を確認する
                if (date('Ymd', strtotime($KISYU_YMD)) != $KISYU_YMD) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $KISYU_YMD . "\" から型 'Date' への変換は無効です。");
                } else {
                    $KISYU_YMD = date('Y/m/d', strtotime($KISYU_YMD));
                }
                if (date('Ymd', strtotime($KIMATU_YMD)) != $KIMATU_YMD) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $KIMATU_YMD . "\" から型 'Date' への変換は無効です。");
                } else {
                    $KIMATU_YMD = date('Y/m/d', strtotime($KIMATU_YMD));
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }

            $result['data']['KISYU_YMD'] = $KISYU_YMD;
            $result['data']['KIMATU_YMD'] = $KIMATU_YMD;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function btnExcelClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //ログ管理のため
        $intState = 0;
        $lngOutCnt = 0;

        try {
            $dtpTaisyouYM_F = $_POST['data']['dtpTaisyouYM_F'];
            $dtpTaisyouYM_T = $_POST['data']['dtpTaisyouYM_T'];
            $txtOld = $_POST['data']['txtOld'];

            //チェック
            $basePath = dirname(dirname(dirname(__FILE__)));
            $check_result = $this->InPutCheck($basePath);
            if (!$check_result['result']) {
                throw new \Exception($check_result['error']);
            }
            $strTemplatePath = $this->ClsComFncJKSYS->FncGetPath('JksysExcelLayoutPath');
            $strTemplateFile = $basePath . '/' . $strTemplatePath . 'FrmJigyousyoZeiTemplate.xls';
            if (!file_exists($strTemplateFile)) {
                throw new \Exception('W9999');
            }
            //ログ管理
            $intState = 9;

            //出力先パス
            $strFilePath = $check_result['filepath'] . '事業所税資料.xls';
            if (file_exists($strFilePath) && !is_writable($strFilePath)) {
                throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
            } elseif (!file_exists($strFilePath)) {
                $dir = @opendir(dirname($strFilePath));
                if ($dir === false) {
                    //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                if (@readdir($dir) == false) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                @closedir($dir);
            }

            //対象期間の月数を取得する
            $time1 = strtotime($dtpTaisyouYM_F);
            $time2 = strtotime($dtpTaisyouYM_T);
            $year1 = date('Y', $time1);
            $month1 = date('m', $time1);
            $year2 = date('Y', $time2);
            $month2 = date('m', $time2);
            $intMonthTime = ($year2 * 12 + $month2) - ($year1 * 12 + $month1);

            $FrmJigyousyoZei = new FrmJigyousyoZei();
            //データ取得(全体)
            $DT_L = $FrmJigyousyoZei->FncGetJIGYOU($dtpTaisyouYM_F, $intMonthTime);
            if (!$DT_L['result']) {
                throw new \Exception($DT_L['data']);
            }
            //データ取得(障害者・老人)
            $DT_R = $FrmJigyousyoZei->FncGetJIGYOU2($dtpTaisyouYM_F, $txtOld, $intMonthTime);
            if (!$DT_R['result']) {
                throw new \Exception($DT_R['data']);
            }

            //データが存在する場合
            if ($DT_L['row'] > 0 && $DT_R['row'] > 0) {
                $lngOutCnt = $DT_L['row'];

                $intSCnt = 0;
                $intOCnt = 0;
                $strSyainNM_B = '';
                $strSyainNM_A = '';

                //***** MAX値取得 ***************
                //障害者数のMAX値を取得
                $tmpDatarow = array();
                foreach ((array) $DT_R['data'] as $value) {
                    if ($value['KBN'] == 1) {
                        array_push($tmpDatarow, $value);
                    }
                }

                for ($intI = 0; $intI < count($tmpDatarow); $intI++) {
                    $strBusyoCD_B = $tmpDatarow[$intI]['BUSYO_CD'];
                    $tmpDatarowS = array();
                    foreach ((array) $DT_R['data'] as $value) {
                        if (($value['KBN'] == 1) && ($value['BUSYO_CD'] == $strBusyoCD_B)) {
                            array_push($tmpDatarowS, $value);
                        }
                    }

                    for ($intJ = 0; $intJ < count($tmpDatarowS); $intJ++) {
                        $strSyainNM_B = $tmpDatarowS[$intJ]['SYAIN_NM'];
                        if ($strSyainNM_B != $strSyainNM_A) {
                            $intOCnt = $intOCnt + 1;
                        }
                        $strSyainNM_A = $strSyainNM_B;
                    }

                    if ($intOCnt > $intSCnt) {
                        $intSCnt = $intOCnt;
                    }
                    $intOCnt = 0;
                }

                //リセット
                $intRCnt = 0;
                $intOCnt = 0;
                $strSyainNM_B = '';
                $strSyainNM_A = '';

                //老人数のMAX値を取得
                $tmpDatarow2 = array();
                foreach ((array) $DT_R['data'] as $value) {
                    if ($value['KBN'] == 2) {
                        array_push($tmpDatarow2, $value);
                    }
                }

                for ($intI = 0; $intI < count($tmpDatarow2); $intI++) {
                    $strBusyoCD_B = $tmpDatarow2[$intI]['BUSYO_CD'];
                    $tmpDatarowR = array();
                    foreach ((array) $DT_R['data'] as $value) {
                        if (($value['KBN'] == 2) && ($value['BUSYO_CD'] == $strBusyoCD_B)) {
                            array_push($tmpDatarowR, $value);
                        }
                    }

                    for ($intJ = 0; $intJ < count($tmpDatarowR); $intJ++) {
                        $strSyainNM_B = $tmpDatarowR[$intJ]['SYAIN_NM'];
                        if ($strSyainNM_B != $strSyainNM_A) {
                            $intOCnt = $intOCnt + 1;
                        }
                        $strSyainNM_A = $strSyainNM_B;
                    }

                    if ($intOCnt > $intRCnt) {
                        $intRCnt = $intOCnt;
                    }
                    $intOCnt = 0;
                }

                //Excel出力(出力データ1、出力データ2、障害者MAX値、老人MAX値、出力先、戻り値)
                $intStateResult = $this->CreateExcelData($DT_L, $DT_R, $intSCnt, $intRCnt, $strFilePath, $_POST['data'], $intMonthTime, $intState, $strTemplateFile);
                $intState = $intStateResult['intState'];
                if (!$intStateResult['result']) {
                    throw new \Exception($intStateResult['error']);
                }
            }

            if ($intState == 1) {
                $result['data'] = 'I0011';
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        try {
            //ログ管理テーブルに登録
            if ($intState <> 0) {
                $res = $this->ClsLogControl->fncLogEntryJksys('FrmJigyousyoZei', $intState, $lngOutCnt, $dtpTaisyouYM_F, $dtpTaisyouYM_T, $txtOld);
                if (!$res['result']) {
                    throw new \Exception($res['Msg']);
                }
            }
        } catch (\Exception $e1) {
            $result['result'] = FALSE;
            $result['error'] = $e1->getMessage();
        }

        $this->fncReturn($result);
    }

    public function InPutCheck($basePath)
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
        );
        try {
            //フォルダーが存在するかどうかのﾁｪｯｸ
            $filePath = $basePath . '/' . $this->ClsComFncJKSYS->FncGetPath('JksysPathFrom');
            if ($this->ClsComFncJKSYS->FncGetPath("JksysPathFrom") == "") {
                throw new \Exception('W0001');
            }
            if (($this->ClsComFncJKSYS->FncFileExists($filePath)) == FALSE) {
                throw new \Exception("W0015");
            }
            if (!(is_readable($filePath) && is_writable($filePath) && is_executable($filePath))) {
                throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
            } else {
                $result['filepath'] = $filePath;
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function CreateExcelData($DT_L, $DT_R, $intSCnt, $intRCnt, $strFilePath, $param, $intMonthTime, $intState, $strTemplateFile)
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'intState' => $intState
        );
        try {
            $PHPReader = IOFactory::createReader('Xls');
            $PHPExcel = $PHPReader->load($strTemplateFile);

            if ($intSCnt == 0) {
                //障害者が0人の場合
                $intSCnt = 1;
            }
            if ($intRCnt == 0) {
                //老人が0人の場合
                $intRCnt = 1;
            }

            //列数の算出
            //全列数
            $strAllCol = '';
            $n = 6 + $intSCnt * 2 + $intRCnt * 2;
            if ($n > 0) {
                $strAllColResult = $this->n2column($n);
                if (!$strAllColResult['result']) {
                    throw new \Exception($strAllColResult['error']);
                }
                $strAllCol = $strAllColResult['strAllCol'];
            }

            $activeSheet = $PHPExcel->getActiveSheet();

            //給与計 A
            $styleC = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'CCFFFF']
                ]
            ];
            //障害者 + 障害者計 B
            $styleD = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF99']
                ]
            ];
            //老人 + 老人計 C
            $styleG = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFCC99']
                ]
            ];
            //A-B-C
            $styleLast = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'CCFFCC']
                ]
            ];
            //Font 10
            $styleFont = [
                'font' => [
                    'size' => 10
                ]
            ];

            //***** ページヘッダー  start**********
            //タイトル
            $activeSheet->setCellValue('A1', '事業所税資料');
            $activeSheet->mergeCells('A1:' . $strAllCol . '1');

            //対象期間
            $dtpTaisyouYM_F = $param['dtpTaisyouYM_F'];
            $dtpTaisyouYM_T = $param['dtpTaisyouYM_T'];
            $activeSheet->setCellValue('A3', '対象期間：' . $dtpTaisyouYM_F . '～' . $dtpTaisyouYM_T);

            //給与計 A
            $activeSheet->getColumnDimension('C')->setWidth(11.15);

            //D4から障害者分
            $activeSheet->setCellValue('D4', '障害者');
            $strAllColResult = $this->n2column(3 + $intSCnt * 2);
            if (!$strAllColResult['result']) {
                throw new \Exception($strAllColResult['error']);
            }
            $activeSheet->mergeCells('D4:' . $strAllColResult['strAllCol'] . '4');
            $activeSheet->getStyle('D4:' . $strAllColResult['strAllCol'] . '4')->applyFromArray($styleD);

            //障害者の次列から1列分
            $strAllColResult = $this->n2column(4 + $intSCnt * 2);
            if (!$strAllColResult['result']) {
                throw new \Exception($strAllColResult['error']);
            }
            $activeSheet->setCellValue($strAllColResult['strAllCol'] . '4', '障害者計 B');
            $activeSheet->getColumnDimension($strAllColResult['strAllCol'])->setWidth(11.15);

            //障害者計Bの次列から老人分
            $strAllColResult = $this->n2column(5 + $intSCnt * 2);
            if (!$strAllColResult['result']) {
                throw new \Exception($strAllColResult['error']);
            }
            $iStart = $strAllColResult['strAllCol'];
            $strAllColResult = $this->n2column((5 + $intSCnt * 2 + $intRCnt * 2) - 1);
            if (!$strAllColResult['result']) {
                throw new \Exception($strAllColResult['error']);
            }
            $iEnd = $strAllColResult['strAllCol'];
            $activeSheet->setCellValue($iStart . '4', '老人');
            $activeSheet->mergeCells($iStart . '4:' . $iEnd . '4');
            $activeSheet->getStyle($iStart . '4:' . $iEnd . '4')->applyFromArray($styleG);

            //老人の次列から1列分
            $strAllColResult = $this->n2column((6 + $intSCnt * 2 + $intRCnt * 2) - 1);
            if (!$strAllColResult['result']) {
                throw new \Exception($strAllColResult['error']);
            }
            $activeSheet->setCellValue($strAllColResult['strAllCol'] . '4', '老人計 C');
            $activeSheet->getColumnDimension($strAllColResult['strAllCol'])->setWidth(11.15);

            //老人計Cの次列から1列分
            $strAllColResult = $this->n2column((7 + $intSCnt * 2 + $intRCnt * 2) - 1);
            if (!$strAllColResult['result']) {
                throw new \Exception($strAllColResult['error']);
            }
            $activeSheet->setCellValue($strAllColResult['strAllCol'] . '4', 'A-B-C');
            $activeSheet->getColumnDimension($strAllColResult['strAllCol'])->setWidth(11.15);

            $activeSheet->getStyle('A4:' . $strAllCol . '4')->applyFromArray($styleFont);
            //***** ページヘッダー  end**********

            //***** 明細データ  start**************
            //スタート行番号
            $iStartRow = 5;
            $intRow = $iStartRow;
            //障害者のスタート列
            $intSCol = 4;
            //老人のスタート列
            $intRCol = 5 + $intSCnt * 2;

            $strLFlg = '0';
            $strBusyoCD_A = '';

            //給与計A(部署ごと)
            $intSumA = 0;
            //障害者計B(社員ごと)
            $intKeiB = 0;
            //障害者計B(部署ごと)
            $intSumB = 0;
            //障害者計C(社員ごと)
            $intKeiC = 0;
            //老人計C(部署ごと)
            $intSumC = 0;
            //給与計A(総合計)
            $intGoukeiA = 0;
            //障害者計B(総合計)
            $intGoukeiB = 0;
            //老人計C(総合計)
            $intGoukeiC = 0;

            //部署毎データ
            for ($intI = 0; $intI < ($DT_L['row']); $intI++) {
                //部署コード取得
                $strBusyoCD_B = $DT_L['data'][$intI]['BUSYO_CD'];
                //部署名取得
                $strBusyoNM = $DT_L['data'][$intI]['BUSYO_NM'];
                //給与計A取得
                $intKei = $DT_L['data'][$intI]['KYUUYO_KEI_A'];

                //給与Aの総合計
                $intGoukeiA = $intGoukeiA + $intKei;

                if ($strBusyoCD_B != $strBusyoCD_A) {
                    //2部署目以降の場合
                    if ($strBusyoCD_A != '') {
                        //改行
                        $intRow = $intRow + 1;
                    }
                }

                //左部のデータ(1部署につき1度)
                if ($strBusyoCD_B != $strBusyoCD_A) {
                    //部署
                    $activeSheet->setCellValue('A' . $intRow, $strBusyoNM);
                    $activeSheet->mergeCells('A' . $intRow . ':' . 'B' . $intRow);

                    //部署ごとの給与計A(リセット)
                    $intSumA = 0;
                    $strLFlg = '0';
                }

                if (($strLFlg == '0') || ($strBusyoCD_B == $strBusyoCD_A)) {
                    //「初回」もしくは「同じ部署コード」の場合↓

                    //部署ごとの給与計A
                    $intSumA = $intSumA + $intKei;

                    //給与集計後↓

                    //給与計A
                    $activeSheet->setCellValue('C' . $intRow, $intSumA);
                    if ($intSumA != 0) {
                        $activeSheet->getStyle('C' . $iStartRow . ':C' . $intRow)
                            ->getNumberFormat()
                            ->setFormatCode('#,##0');
                    }

                    $strLFlg = '1';
                }

                //障害・老人データ(1部署につき1度)
                if ($strBusyoCD_B != $strBusyoCD_A) {
                    //部署コードが左部と同じ
                    $tmpDatarow = array();
                    foreach ($DT_R['data'] as $value) {
                        if ($value['BUSYO_CD'] == $strBusyoCD_B) {
                            array_push($tmpDatarow, $value);
                        }
                    }

                    //フラグリセット
                    //区分Before
                    $strKbn_B = '';
                    //区分After
                    $strKbn_A = '';
                    //社員名Before
                    $strSyainNM_B = '';
                    //社員名After
                    $strSyainNM_A = '';
                    //初回フラグ
                    $strRFlg1 = '0';
                    $strRFlg2 = '0';
                    //部署ごとの障害者計
                    $intSumB = 0;
                    //部署ごとの老人計
                    $intSumC = 0;
                    //障害者の表示列
                    $intSCol = 4;
                    //老人の表示列
                    $intRCol = 5 + $intSCnt * 2;

                    for ($intJ = 0; $intJ < count($tmpDatarow); $intJ++) {
                        //社員名取得
                        $strSyainNM_B = $tmpDatarow[$intJ]['SYAIN_NM'];
                        //給与計取得(障害者or老人)
                        $intKei = $tmpDatarow[$intJ]['KYUUYO_KEI'];
                        //区分取得
                        $strKbn_B = $tmpDatarow[$intJ]['KBN'];

                        //「区分」が変わった場合(改列)
                        if (($strKbn_B != $strKbn_A) || ($strSyainNM_B != $strSyainNM_A)) {
                            if ($strKbn_A == '1') {
                                //一つ前のデータが障害者の場合
                                $intSCol = $intSCol + 2;
                            } else
                                if ($strKbn_A == '2') {
                                    //一つ前のデータが老人の場合
                                    $intRCol = $intRCol + 2;
                                }
                        }
                        //「区分」入替
                        $strKbn_A = $strKbn_B;

                        if ($strKbn_B == '1') {
                            //*** 障害者側に表示 ****
                            //社員名(同部署につき1度)
                            if ($strSyainNM_B != $strSyainNM_A) {
                                $strAllColResult = $this->n2column($intSCol);
                                if (!$strAllColResult['result']) {
                                    throw new \Exception($strAllColResult['error']);
                                }
                                $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $strSyainNM_B);
                                $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->applyFromArray($styleFont);

                                //社員ごとの障害者計(リセット)　
                                $intKeiB = 0;
                                $strRFlg1 = '0';
                            }

                            if (($strRFlg1 == '0') || ($strSyainNM_B == $strSyainNM_A)) {
                                //「初回」もしくは「同じ社員名(の間)」の場合↓

                                //社員ごとの障害者計
                                $intKeiB = $intKeiB + $intKei;

                                //給与集計後↓

                                //給与
                                $strAllColResult = $this->n2column($intSCol + 1);
                                if (!$strAllColResult['result']) {
                                    throw new \Exception($strAllColResult['error']);
                                }
                                $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $intKeiB);
                                $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->applyFromArray($styleFont);
                                if ($intKeiB != 0) {
                                    $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->getNumberFormat()
                                        ->setFormatCode('#,##0');
                                }

                                $strRFlg1 = '1';
                            }

                            //部署ごとの障害者計
                            $intSumB = $intSumB + $intKei;
                            //障害者計Bの総合計
                            $intGoukeiB = $intGoukeiB + $intKei;
                        } else
                            if ($strKbn_B == '2') {
                                //*** 老人側に表示 ***
                                //社員名(同部署につき1度)
                                if ($strSyainNM_B != $strSyainNM_A) {
                                    $strAllColResult = $this->n2column($intRCol);
                                    if (!$strAllColResult['result']) {
                                        throw new \Exception($strAllColResult['error']);
                                    }
                                    $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $strSyainNM_B);
                                    $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->applyFromArray($styleFont);

                                    //社員ごとの老人計(リセット)　
                                    $intKeiC = 0;
                                    $strRFlg2 = '0';
                                }

                                if (($strRFlg2 == '0') || ($strSyainNM_B == $strSyainNM_A)) {
                                    //「初回」もしくは「同じ社員名」の場合↓

                                    //社員ごとの老人計　
                                    $intKeiC = $intKeiC + $intKei;

                                    //給与集計後↓

                                    //給与
                                    $strAllColResult = $this->n2column($intRCol + 1);
                                    if (!$strAllColResult['result']) {
                                        throw new \Exception($strAllColResult['error']);
                                    }
                                    $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $intKeiC);
                                    $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->applyFromArray($styleFont);
                                    if ($intKeiC != 0) {
                                        $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->getNumberFormat()
                                            ->setFormatCode('#,##0');
                                    }

                                    $strRFlg2 = '1';
                                }

                                //部署ごとの老人計
                                $intSumC = $intSumC + $intKei;
                                //老人計Cの総合計
                                $intGoukeiC = $intGoukeiC + $intKei;
                            }

                        //社員名入替
                        $strSyainNM_A = $strSyainNM_B;
                    }

                    //*** 部署ごとの障害者計・老人計表示 ***
                    //障害者計B
                    $strAllColResult = $this->n2column(4 + $intSCnt * 2);
                    if (!$strAllColResult['result']) {
                        throw new \Exception($strAllColResult['error']);
                    }
                    $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $intSumB);
                    if ($intSumB != 0) {
                        $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->getNumberFormat()
                            ->setFormatCode('#,##0');
                    }

                    //老人計C
                    $strAllColResult = $this->n2column((6 + $intSCnt * 2 + $intRCnt * 2) - 1);
                    if (!$strAllColResult['result']) {
                        throw new \Exception($strAllColResult['error']);
                    }
                    $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $intSumC);
                    if ($intSumC != 0) {
                        $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->getNumberFormat()
                            ->setFormatCode('#,##0');
                    }
                }

                //A-B-C
                $strAllColResult = $this->n2column((7 + $intSCnt * 2 + $intRCnt * 2) - 1);
                if (!$strAllColResult['result']) {
                    throw new \Exception($strAllColResult['error']);
                }
                $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $intSumA - $intSumB - $intSumC);
                if ($intSumA - $intSumB - $intSumC != 0) {
                    $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->getNumberFormat()
                        ->setFormatCode('#,##0');
                } else {
                    $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->getNumberFormat()->setFormatCode(';;"0"');
                }

                //部署コード入替
                $strBusyoCD_A = $strBusyoCD_B;
            }
            //***** 明細データ  end**************

            //障害者の表示列
            $intSCol = 4;
            //老人の表示列
            $intRCol = 5 + $intSCnt * 2;
            $intRow = $intRow + 1;

            //***** ページフッター  start**********
            $activeSheet->setCellValue('A' . $intRow, '合計');
            $activeSheet->mergeCells('A' . $intRow . ':B' . $intRow);
            $style = array('alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
            $activeSheet->getStyle('A' . $intRow . ':B' . $intRow)->applyFromArray($style);

            //給与Aの総合計
            $activeSheet->setCellValue('C' . $intRow, $intGoukeiA);
            if ($intGoukeiA != 0) {
                $activeSheet->getStyle('C' . $intRow)->getNumberFormat()
                    ->setFormatCode('#,##0');
            }
            //障害者計Bの総合計
            $strAllColResult = $this->n2column(4 + $intSCnt * 2);
            if (!$strAllColResult['result']) {
                throw new \Exception($strAllColResult['error']);
            }
            $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $intGoukeiB);
            if ($intGoukeiB != 0) {
                $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->getNumberFormat()
                    ->setFormatCode('#,##0');
            }
            //老人計Cの総合計
            $strAllColResult = $this->n2column((6 + $intSCnt * 2 + $intRCnt * 2) - 1);
            if (!$strAllColResult['result']) {
                throw new \Exception($strAllColResult['error']);
            }
            $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $intGoukeiC);
            if ($intGoukeiC != 0) {
                $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->getNumberFormat()
                    ->setFormatCode('#,##0');
            }
            //A-B-Cの総合計
            $strAllColResult = $this->n2column((7 + $intSCnt * 2 + $intRCnt * 2) - 1);
            if (!$strAllColResult['result']) {
                throw new \Exception($strAllColResult['error']);
            }
            $activeSheet->setCellValue($strAllColResult['strAllCol'] . $intRow, $intGoukeiA - $intGoukeiB - $intGoukeiC);
            if ($intGoukeiA - $intGoukeiB - $intGoukeiC != 0) {
                $activeSheet->getStyle($strAllColResult['strAllCol'] . $intRow)->getNumberFormat()
                    ->setFormatCode('#,##0');
            }
            //***** ページフッター  end**********

            //***** 样式  start**********
            //部署 + 給与計 A
            $activeSheet->getStyle('A' . $iStartRow . ':C' . $intRow)->applyFromArray($styleFont);
            $activeSheet->getStyle('C' . $iStartRow . ':C' . $intRow)->applyFromArray($styleC);
            //障害者の罫線(縦)
            for ($intK = 0; $intK < $intSCnt; $intK++) {
                $intSCol = $intSCol + 2;
            }
            $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intSCol);
            $activeSheet->getStyle($colIndex . '4:' . $colIndex . $intRow)->applyFromArray($styleD + $styleFont);

            //老人の罫線(縦)
            for ($intK = 0; $intK < $intRCnt; $intK++) {
                $intRCol = $intRCol + 2;
            }
            $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intRCol);
            $activeSheet->getStyle($colIndex . '4:' . $colIndex . $intRow)->applyFromArray($styleG + $styleFont);

            //A-B-C
            $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intRCol + 1);
            $activeSheet->getStyle($colIndex . '4:' . $colIndex . $intRow)->applyFromArray($styleLast + $styleFont);

            //行幅の設定
            for ($i = $iStartRow; $i <= $intRow; $i++) {
                $activeSheet->getRowDimension($i)->setRowHeight(15);
            }
            //borderの設定
            $activeSheet->getStyle('A4:C' . $intRow)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $activeSheet->getStyle('C4:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intSCol - 1) . $intRow)->getBorders()
                ->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $activeSheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intSCol) . '4:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intSCol) . $intRow)->getBorders()
                ->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            // $activeSheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intSCol + 1) . '4:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intRCol - 1) . $intRow)->applyFromArray($styleBorderNoLines);
            $activeSheet->getStyle(
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intSCol + 1) . '4:' .
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intRCol - 1) . $intRow
            )->getBorders()->getHorizontal()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $activeSheet->getStyle(
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intSCol + 1) . '4:' .
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intRCol - 1) . $intRow
            )->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $activeSheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intRCol) . '4:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($intRCol + 1) . $intRow)->getBorders()
                ->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            //列幅の設定
            for ($i = 'A'; $i != $activeSheet->getHighestColumn(); $i++) {
                $activeSheet->getColumnDimension($i)->setWidth(10.85);
            }
            $activeSheet->getColumnDimension($activeSheet->getHighestColumn())->setWidth(10.85);

            //***** 样式  end**********

            $PHPExcel->getActiveSheet()->setSelectedCell('A1');
            $objWriter = IOFactory::createWriter($PHPExcel, 'Xls');
            $objWriter->save($strFilePath);
            $PHPExcel->disconnectWorksheets();
            unset($objWriter, $PHPReader, $PHPExcel);

            $result['intState'] = 1;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    public function n2column($n)
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'strAllCol' => ''
        );
        try {
            //nを26で割った商を求める
            $nHi = intval($n / 26);

            //nの26での余りをを求める 1..26が求まるように工夫
            $nLo = (($n - 1) % 26) + 1;

            //26の倍数の場合
            if ($nLo == 26) {
                $nHi = $nHi - 1;
            }

            if ($nHi != 0) {
                $result['strAllCol'] = chr(hexdec(40) + $nHi) . chr(hexdec(40) + $nLo);
            } else {
                $result['strAllCol'] = chr(hexdec(40) + $nLo);
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

}
