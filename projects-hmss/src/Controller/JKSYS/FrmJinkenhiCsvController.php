<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJinkenhiCsv;
use PhpOffice\PhpSpreadsheet\IOFactory;
//*******************************************
// * sample controller
//*******************************************
class FrmJinkenhiCsvController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFncJKSYS');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmJinkenhiCsv_layout');
    }

    //ページロード
    public function frmJinkenhiCsvLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $frmJinkenhiCsv = new FrmJinkenhiCsv();

            //--- 処理年月設定 ---
            $result_time = $frmJinkenhiCsv->selShoriYM();
            if (!$result_time['result']) {
                throw new \Exception($result_time['data']);
            }
            $taishoYM = '';
            if ($result_time['row'] != 0) {
                $taishoYM = $result_time['data'][0]['SYORI_YM'];
                //日付形式を確認する
                $date = $taishoYM . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $taishoYM . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }

            //--- 本部負担金、整備負担金設定 ---
            if ($_POST['data']['taishoYM'] != '') {
                $_taishoYM = $_POST['data']['taishoYM'];
            } else {
                $_taishoYM = $taishoYM;
            }
            $dt = $frmJinkenhiCsv->selJinkenhiFutankin($_taishoYM);
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            //--- 該当データなし ---
            if ($dt['row'] == 0) {
                //--- 前月分データ取得 ---
                $taishoYM_find = date('Ym', strtotime('last month', strtotime($_taishoYM . '01')));
                $dt = $frmJinkenhiCsv->selJinkenhiFutankin($taishoYM_find);
                if (!$dt['result']) {
                    throw new \Exception($dt['data']);
                }
            }

            //戻り値
            $rtndata = array(
                'taishoYM' => $taishoYM,
                'Futankin' => $dt['data']
            );
            $result = array(
                'result' => TRUE,
                'data' => $rtndata
            );
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    public function makeCSV()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $frmJinkenhiCsv = new FrmJinkenhiCsv();

                $dtpYM = $_POST['data']['dtpYM'];
                $HombuFutankin = $_POST['data']['HombuFutankin'];
                $SeibiFutankin = $_POST['data']['SeibiFutankin'];

                //人件費データ存在チェック
                $resultCheck = $frmJinkenhiCsv->procJinkenhiExists($dtpYM);
                if (!$resultCheck['result']) {
                    throw new \Exception($resultCheck['data']);
                }
                //QA 27
                if (($resultCheck['data'][0]['BUSYO_ERR'] == null) && ($resultCheck['data'][0]['SYOKUSYU_ERR'] == null) && ($resultCheck['data'][0]['SYOKUSYU_ERR2'] == null)) {
                    $result['msg'] = substr($dtpYM, 0, 4) . '年' . substr($dtpYM, 4, 2) . '月' . '分の人件費データが存在しません。先に人件費データ生成を行ってください。';
                    throw new \Exception('W9999');
                }
                $dt = $resultCheck['data'][0];
                if ($dt['BUSYO_ERR'] == '1') {
                    $result['msg'] = '人件費データに部署コードが入っていないデータが存在しています。人件費入力から部署コードを登録してください。';
                    throw new \Exception('W9999');
                }
                if ($dt['SYOKUSYU_ERR'] == '1') {
                    $result['msg'] = '人件費データに役員以外で職種コードが入力されていないデータが存在しています。人件費入力から役員以外の職種コードを登録して下さい。';
                    throw new \Exception('W9999');
                }
                if ($dt['SYOKUSYU_ERR2'] == '1') {
                    $result['msg'] = '人件費データに車両回送係所属で整備直接員の職種が設定されている社員が存在しています。人件費入力から職種コードを修正して下さい。';
                    throw new \Exception('W9999');
                }

                //*****人件費明細（営業スタッフランキング用）*****
                //テンプレート保存先のパスを取得する
                $basePath = dirname(dirname(dirname(__FILE__)));
                $strTemplatePath = $this->ClsComFncJKSYS->FncGetPath('JksysExcelLayoutPath');
                //                $strTemplatePath = $basePath . '/' . $strTemplatePath . 'FrmJinkenhiCsvTemplate.xlt';
//                $strTemplatePath = $strTemplatePath . 'FrmJinkenhiCsvTemplate.xlt';
                $strTemplatePath = $basePath . '/' . $strTemplatePath . '人件費個別明細.xlt';
                if (!file_exists($strTemplatePath)) {
                    $result['msg'] = 'テンプレートファイルが存在しません。' . $strTemplatePath;
                    throw new \Exception('W9999');
                }

                $result = $frmJinkenhiCsv->Fnc_GetSysDateWareki($dtpYM);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['row'] > 0) {
                    $strWareki = $result['data'][0]['TODAY_VAL'];
                    //20210303 CI UPD S
                    //$strWareki = substr($strWareki, 0, 3) . '.' . substr($strWareki, 3, 2);
                    $strWareki = substr($strWareki, 0, 4) . '.' . substr($strWareki, 4, 2);
                    //20210303 CI UPD E
                    //ファイル名取得
                    $excelfileName = $strWareki . '月人件費.xls';

                    //--- 処理年月取得 ---
                    $result_time = $frmJinkenhiCsv->selShoriYM();
                    if (!$result_time['result']) {
                        throw new \Exception($result_time['data']);
                    }
                    $taishoYM = '';
                    if ($result_time['row'] != 0) {
                        $taishoYM = $result_time['data'][0]['SYORI_YM'];
                        //日付形式を確認する
                        $date = $taishoYM . '01';
                        if (date('Ymd', strtotime($date)) != $date) {
                            $taishoYM = date('Ym');
                        }
                    } else {
                        $taishoYM = date('Ym');
                    }
                    //出力先のパスがcheck
                    $tmpPath = $basePath . '/' . $this->ClsComFncJKSYS->FncGetPath('JksysPathFrom');
                    if ($this->ClsComFncJKSYS->FncGetPath("JksysPathFrom") == "") {
                        throw new \Exception('W0001');
                    }
                    if (($this->ClsComFncJKSYS->FncFileExists($tmpPath)) == FALSE) {
                        throw new \Exception("W0015");
                    }
                    if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                        throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                    }
                    //対象年月が処理年月以前の場合、DB更新は行なわない
                    if ($dtpYM >= $taishoYM) {
                        //入力チェック
                        $resultCheck = $this->checkInput($_POST['data']);
                        if (!$resultCheck['result']) {
                            throw new \Exception($resultCheck['error']);
                        }

                        //負担金データ、人件費科目変換データ登録
                        $resultInsert = $this->InsertDB($dtpYM, $HombuFutankin, $SeibiFutankin);
                        if (!$resultInsert['result']) {
                            throw new \Exception($resultInsert['error']);
                        }
                    }

                    //ＣＳＶ出力（経営成果用）
                    $result = $this->outputCSV($dtpYM, $basePath);
                    if (!$result['result']) {
                        throw new \Exception($result['error']);
                    }

                    //人件費明細出力（営業スタッフランキング用）
                    $result = $this->outputMeisaiExcel($dtpYM, $strTemplatePath, $basePath, $excelfileName);
                    if (!$result['result']) {
                        throw new \Exception($result['error']);
                    }
                }
            } else {
                throw new \Exception('param error');
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //入力チェック
    public function checkInput($postdata)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            // '**************
            // '* チェック１ *
            // '**************
            //本部負担金 必須チェック，数値チェック
            if ($postdata['checkRetHombu'] == -1) {
                throw new \Exception('W0001_Hombu');
            }
            if ($postdata['checkRetHombu'] == -2) {
                throw new \Exception('W0002_Hombu');
            }

            //整備負担金 必須チェック，数値チェック
            if ($postdata['checkRetSeibi'] == -1) {
                throw new \Exception('W0001_Seibi');
            }
            if ($postdata['checkRetSeibi'] == -2) {
                throw new \Exception('W0002_Seibi');
            }
            // '**************
            // '* チェック３ *
            // '**************
            if ($postdata['HombuFutankin'] == '0') {
                throw new \Exception('W0017_Hombu');
            }
            if ($postdata['SeibiFutankin'] == '0') {
                throw new \Exception('W0017_Seibi');
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //負担金データ、人件費科目変換データ登録
    public function InsertDB($taishoYM, $HombuFutankin, $SeibiFutankin)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        $frmJinkenhiCsv = new FrmJinkenhiCsv();
        $blnTran = FALSE;

        try {
            //トランザクション開始
            $frmJinkenhiCsv->Do_transaction();
            $blnTran = TRUE;

            //負担金データの削除
            $result = $frmJinkenhiCsv->delFutankinData($taishoYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //負担金データの登録
            $result = $frmJinkenhiCsv->insFutankinData($taishoYM, $HombuFutankin, $SeibiFutankin);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //人件費科目変換データの削除
            $result = $frmJinkenhiCsv->delKamokuHenkanData($taishoYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //人件費科目変換データの登録
            $result = $this->createKamokuHenkanData($frmJinkenhiCsv, $taishoYM, $HombuFutankin, $SeibiFutankin);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //コミット
            $frmJinkenhiCsv->Do_commit();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $frmJinkenhiCsv->Do_rollback();
            }
        }
        return $result;
    }

    //人件費科目変換データ生成
    public function createKamokuHenkanData($frmJinkenhiCsv, $TAISYOU_YM, $HombuFutankin, $SeibiFutankin)
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );
        try {
            //★借方データの生成★
            //**********************
            //*  給与（定時間）生成   *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 1);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  給与（諸手当）生成   *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 2);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  給与（残業手当）生成 *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 3);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  給与（奨励金）生成   *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 4);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //* 福利厚生費生成       *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 5);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  退職手当生成        *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 6);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  賞与生成           *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 7);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  総人員生成          *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 8);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  人員データ生成      *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 9);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  本部負担金データ生成 *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 10);
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  整備負担金データ生成   *
            //**********************
            $result = $frmJinkenhiCsv->insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, 11);
            if (!$result['result']) {
                return $result;
            }
            //★貸方データの生成★
            //**********************
            //*  給与生成           *
            //**********************
            $result = $frmJinkenhiCsv->insRKamokuHenkanData($TAISYOU_YM, '112', '43320');
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  賞与生成           *
            //**********************
            $result = $frmJinkenhiCsv->insRKamokuHenkanData($TAISYOU_YM, '112', '43331');
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  福利厚生費生成      *
            //**********************
            $result = $frmJinkenhiCsv->insRKamokuHenkanData($TAISYOU_YM, '112', '43350');
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  退職給付費用生成     *
            //**********************
            $result = $frmJinkenhiCsv->insRKamokuHenkanData($TAISYOU_YM, '112', '43340');
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  本部負担金データ生成 *
            //**********************
            $result = $frmJinkenhiCsv->insRKamokuHenkanData($TAISYOU_YM, '007', '84551', '51');
            if (!$result['result']) {
                return $result;
            }
            //**********************
            //*  整備負担金データ生成 *
            //**********************
            $result = $frmJinkenhiCsv->insRKamokuHenkanData($TAISYOU_YM, '251', '84222');
            if (!$result['result']) {
                return $result;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    public function outputCSV($TAISYOU_YM, $basePath)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $myfile = null;

        try {
            $frmJinkenhiCsv = new FrmJinkenhiCsv();

            //人件費科目変換データ取得
            $dt = $frmJinkenhiCsv->selKamokuHenkanData($TAISYOU_YM);
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            //該当データなし
            if ($dt['row'] == 0) {
                throw new \Exception('W0016');
            }

            //***CSV出力処理****
            $tmpPath = $basePath . '/' . $this->ClsComFncJKSYS->FncGetPath('JksysPathFrom');
            //出力CSV
            $filename = substr($TAISYOU_YM, 4, 2) . '月部署.csv';
            $filename = $tmpPath . $filename;
            if (file_exists($filename)) {
                if (!is_writable($filename)) {
                    throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
                }
                @unlink($filename);
            } elseif (!file_exists($filename)) {
                $dir = @opendir(dirname($filename));
                if ($dir === false) {
                    //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                if (@readdir($dir) == false) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                @closedir($dir);
            }

            $myfile = fopen($filename, 'w');
            foreach ((array) $dt['data'] as $value) {
                //初期化
                $strOut = '';
                //対象年月
                $strOut .= $value['TAISYOU_YM'];
                $strOut .= '3';
                $strOut .= ',';
                //貸借区分
                $strOut .= $value['TAISK_KB'];
                $strOut .= ',';
                //部署コード
                $strOut .= $value['BUSYO_CD'];
                $strOut .= ',';
                //予備１
                $strOut .= ' ';
                $strOut .= ',';
                //科目コード
                $strOut .= $value['KAMOK_CD'];
                $strOut .= ',';
                //予備２
                $strOut .= ' ';
                $strOut .= ',';
                //金額
                $strOut .= number_format($this->ClsComFncJKSYS->FncNz($value['KINGAKU']), 1, '.', '');
                $strOut .= ',';
                //予備３
                $strOut .= ' ';
                $strOut .= ',';
                //費目コード
                $strOut .= $value['HIMOK_CD'];

                $strOut .= ',';
                $strOut .= "\r\n";
                fwrite($myfile, $strOut);
            }
            @fclose($myfile);

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            if (is_resource($myfile)) {
                fclose($myfile);
            }
            if ($myfile != null) {
                unset($myfile);
            }
        }
        return $result;
    }

    //明細Excel出力
    public function outputMeisaiExcel($TAISYOU_YM, $strTemplatePath, $basePath, $fileName)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        //ログ管理
        $intState = 0;
        $lngOutCnt = 0;
        $file = '';

        try {
            //ログ管理のため
            $intState = 9;

            $frmJinkenhiCsv = new FrmJinkenhiCsv();

            //人件費科目変換データ取得
            $result = $frmJinkenhiCsv->selJinkenhiMeisaiData($TAISYOU_YM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $lngOutCnt = $result['row'];

            if ($result['row'] == 0) {
                //該当データなし
                $intState = 1;
                throw new \Exception('W0016');
            }

            //***Excel出力処理****
            $tmpPath = $basePath . '/' . $this->ClsComFncJKSYS->FncGetPath('JksysPathFrom');
            $file = $tmpPath . $fileName;

            //エクセルのテンプレートが保存されている場所を取得
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);

            //シート設定
            $sheetName = substr($TAISYOU_YM, 4, 2) . '月人件';
            $objActSheet = $objPHPExcel->getActiveSheet();
            $objActSheet->setTitle($sheetName);

            $rowIndex = 2;
            foreach ((array) $result['data'] as $value) {
                $objActSheet->setCellValueExplicit('A' . $rowIndex, $value['SYAIN_NO'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('B' . $rowIndex, $value['SYAIN_NM'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('C' . $rowIndex, $value['BUSYO_CD'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValue('D' . $rowIndex, $value['KYUYOKEI']);
                $objActSheet->setCellValue('E' . $rowIndex, $value['SYAHOKEI']);
                $objActSheet->setCellValue('F' . $rowIndex, $value['BNS_MITUMORI']);
                $objActSheet->setCellValue('G' . $rowIndex, $value['JINKENHIKEI']);
                $objActSheet->setCellValue('H' . $rowIndex, $value['TEIJIKAN_GESSYU']);
                $rowIndex++;
            }

            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($file);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);

            //正常
            $intState = 1;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            $lngOutCnt = 0;
        }
        //ログ管理
        try {
            //intState<>0の場合、ログ管理テーブルに登録
            if ($intState <> 0) {
                //出向社員請求明細書印刷
                $res = $this->ClsLogControl->fncLogEntryJksys('FrmJinkenhiCsv', $intState, $lngOutCnt, $TAISYOU_YM, $file);
                if (!$res['result']) {
                    throw new \Exception($res['Msg']);
                }
            }
        } catch (\Exception $e1) {
            $result['result'] = FALSE;
            $result['error'] = $e1->getMessage();
        }
        return $result;
    }

}
