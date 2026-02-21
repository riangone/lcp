<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmKoukaHyouCreate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;

//*******************************************
// * sample controller
//*******************************************
class FrmKoukaHyouCreateController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    // var $components = array(
    //     'RequestHandler',
    //     'ClsLogControl',
    //     'ClsComFncJKSYS'
    // );
    public $FrmKoukaHyouCreate;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
        $this->loadComponent('ClsLogControl');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmKoukaHyouCreate_layout');
    }

    //フォームロード
    public function frmKoukaHyouCreateLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $prvKakiBonusEndMt = '';
        $prvToukiBonusEndMt = '';
        try {
            $this->FrmKoukaHyouCreate = new FrmKoukaHyouCreate();
            /* ---------------------------------------------------------------------
             *人事コントロールマスタより評価期間終了月を取得する
             * ---------------------------------------------------------------------*/
            $dt = $this->FrmKoukaHyouCreate->SelJKCONTROLMSE_SQL('01');
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            if ($dt['row'] > 0) {
                //夏季評価期間終了
                $prvKakiBonusEndMt = $this->ClsComFncJKSYS->FncNv($dt['data'][0]['KAKI_HYOUKA_END_MT']);
                //冬季評価期間終了
                $prvToukiBonusEndMt = $this->ClsComFncJKSYS->FncNv($dt['data'][0]['TOUKI_HYOUKA_END_MT']);
            } else {
                throw new \Exception('W0008');
            }
            $result['data']['prvKakiBonusEndMt'] = $prvKakiBonusEndMt;
            $result['data']['prvToukiBonusEndMt'] = $prvToukiBonusEndMt;
            /* ---------------------------------------------------------------------
             *社員別考課表タイプデータよりMAX(評価対象期間終了)を取得する
             * ---------------------------------------------------------------------*/
            $dt = $this->setHyoukaKikan();
            if (!$dt['result']) {
                throw new \Exception($dt['error']);
            }
            //評価期間
            if (!isset($dt['data']['dtpKikanEnd'])) {
                $dt['data']['dtpKikanEnd'] = substr(date('Y-m-d'), 0, 4) . substr(date('Y-m-d'), 5, 2);
            }
            $result['data']['dtpKikanEnd'] = $dt['data']['dtpKikanEnd'];
            /* ---------------------------------------------------------------------
             *考課表ﾀｲﾌﾟｺﾝﾎﾞﾎﾞｯｸｽに値を設定する
             * ---------------------------------------------------------------------*/
            include_once dirname(__DIR__) . '/JKSYS/KoukaTypeController.php';
            $KoukaType = new KoukaTypeController();
            $dt = $KoukaType->SetComboBox();
            if (!$dt['result']) {
                throw new \Exception($dt['error']);
            }
            $result['data']['cboKoukaType'] = $dt['data'];
            //社員名取得
            $dt = $this->FrmKoukaHyouCreate->GetSyainNm();
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            $result['data']['SYAIN_NO_NAME'] = $dt['data'];

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //評価対象期間終了(MAX)を取得
    public function setHyoukaKikan($blnChek = false)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->FrmKoukaHyouCreate = new FrmKoukaHyouCreate();
            //データ取得(社員別考課表タイプデータ.評価対象期間終了)
            $dt = $this->FrmKoukaHyouCreate->SelJKKOUKA_SYAIN_TYPE_SQL();
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            $result['data']['flag'] = '0';
            if ($blnChek == false) {
                if ($dt['row'] > 0 && $dt['data'][0]['HYOUKA_KIKAN_END'] != "" && $dt['data'][0]['HYOUKA_KIKAN_END'] != null) {
                    //評価期間
                    $dt = $this->GetEndDate($dt['data'][0]['HYOUKA_KIKAN_END']);
                    if (!$dt['result']) {
                        throw new \Exception($dt['error']);
                    }
                    $result['data']['dtpKikanEnd'] = $dt['data'];
                }
            } else {
                if ($dt['row'] == 0 || $dt['data'][0]['HYOUKA_KIKAN_END'] == "" || $dt['data'][0]['HYOUKA_KIKAN_END'] == null) {
                    $result['data']['flag'] = '1';
                }
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //人事考課表を出力する
    public function fncOutputExcel()
    {
        $this->FrmKoukaHyouCreate = new FrmKoukaHyouCreate();
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        //ログ管理
        $lngOutCnt = 0;
        //ステータス
        $intState = 0;
        //出力Excel
        $strOutTarget = '人事考課表';

        $strSyain_No = '';
        $strHyokaStart = '';

        try {
            ini_set('memory_limit', -1);
            if (isset($_POST['data'])) {
                $dtpYM = $_POST['data']['dtpYM'];
                $SelectedValue = $_POST['data']['SelectedValue'];
                $rdoBoth = $_POST['data']['rdoBoth'];
                //入力チェック
                $dt = $this->setHyoukaKikan(true);
                if (!$dt['result']) {
                    throw new \Exception($dt['error']);
                }
                if ($dt['data']['flag'] == '1') {
                    throw new \Exception('W0002');
                }
                //EXCELデータを取り込む
                // include __DIR__ . '/Component/Classes/PHPExcel.php';
                //指定パスのファイルチェック
                $basePath = dirname(dirname(dirname(__FILE__)));
                //Fromフォルダチェック
                if ($this->ClsComFncJKSYS->FncGetPath('KoukaPathFrom') == '') {
                    throw new \Exception('W0001');
                }
                $downloadPath = $basePath . '/' . $this->ClsComFncJKSYS->FncGetPath('KoukaPathFrom');
                if (($this->ClsComFncJKSYS->FncFileExists($downloadPath)) == FALSE) {
                    throw new \Exception('W0015');
                }
                if (!(is_readable($downloadPath) && is_writable($downloadPath) && is_executable($downloadPath))) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                //出力対象データ取得
                $dt = $this->FrmKoukaHyouCreate->SelJKKOUKA_SQL($dtpYM, $SelectedValue, $rdoBoth);
                if (!$dt['result']) {
                    throw new \Exception($dt['data']);
                }
                //対象ﾃﾞｰﾀがない場合、ﾒｯｾｰｼﾞを表示し処理を抜ける
                if ($dt['row'] == 0) {
                    throw new \Exception('I0001');
                }
                //テンプレートフォルダチェック
                $tmpPath = $basePath . '/' . $this->ClsComFncJKSYS->FncGetPath('KoukaExcelLayoutPath');
                if (($this->ClsComFncJKSYS->FncFileExists($tmpPath)) == FALSE) {
                    $result['message'] = 'テンプレートフォルダは存在しません。';
                    throw new \Exception('W9999');
                }
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                $intState = 9;
                //-- 考課表出力 --
                //20211104 WANGYING UPD S
                $cell_empty = array();
                $cell_all = array();
                $cell_not_empty = array();
                $file_num = 1;
                $file_star_data = array();
                foreach ((array) $dt['data'] as $value) {
                    //社員番号又は評価開始期間がﾌﾞﾚｲｸ→Excel出力
                    if ($strSyain_No != $value['SYAIN_NO'] || $strHyokaStart != $value['HYOUKA_KIKAN_START']) {
                        if ($strSyain_No != '' && in_array($strSheetNm, $sheet_name)) {
                            //在循环内值会变成空或者0了
                            foreach ($cell_not_empty as $cell_not_empty_value) {
                                $objActSheet->setCellValue($cell_not_empty_value['cell'], $cell_not_empty_value['data']);
                            }
                            foreach (array_diff($cell_all, $cell_empty) as $cell_empty_value) {
                                $objActSheet->setCellValue($cell_empty_value, 0);
                                $objActSheet->setCellValue($cell_empty_value, null);
                            }
                            $cell_empty = array();
                            $cell_all = array();
                            $cell_not_empty = array();
                            $file_num = 1;
                            $file_star_data = array();
                            //Excel出力
                            $objActSheet->setSelectedCell('A1');
                            Calculation::getInstance($objPHPExcel)->setCalculationCacheEnabled(true);
                            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
                            $objWriter->save($strOutPath);
                            //クローズ
                            $objPHPExcel->disconnectWorksheets();
                            unset($objWriter, $objReader, $objPHPExcel);
                        }
                        $lngOutCnt++;

                        //社員番号、評価開始年月を退避
                        $strSyain_No = $value['SYAIN_NO'];
                        $strHyokaStart = $value['HYOUKA_KIKAN_START'];
                        //Excel雛型
                        $strExcelNm = $value['EXCEL_NM'];
                        $templateExcel = $tmpPath . $strExcelNm;
                        //Excel雛型の存在確認()
                        if (!file_exists($templateExcel)) {
                            throw new \Exception('考課表のExcel雛型が見つかりません！');
                        }
                        if (!(is_readable($templateExcel) && is_writable($templateExcel) && is_executable($templateExcel))) {
                            throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                        }
                        $objReader = IOFactory::createReader('Xlsx');
                        $objPHPExcel = $objReader->load($templateExcel);
                        $sheet_name = $objPHPExcel->getSheetNames();
                        //ｼｰﾄ名()
                        $strSheetNm = $value['SHEET_NM'];
                        $objPHPExcel->setActiveSheetIndex(0);
                        $objActSheet = $objPHPExcel->getActiveSheet();

                        //デザインファイル設定
                        //ExcelBook名設定《社員番号_評価期間開始-評価期間終了_Excel雛型名》
                        //20210302 CI UPD S
                        //$strOutPath = $downloadPath . $strSyain_No . '_' . $strHyokaStart . '-' . $value['HYOUKA_KIKAN_END'] . '_' . $strExcelNm;
                        $strOutPath = $downloadPath . $value['SOUFUBUSYO_CD'] . '_' . $strSyain_No . '_' . $value['SYAIN_NM'] . '_' . $strHyokaStart . '-' . $value['HYOUKA_KIKAN_END'] . '_' . $strExcelNm;
                        //20210302 CI UPD E
                        //ヘッダー部セット
                        $objActSheet->setCellValueExplicit('A1', $value['SOUFUBUSYO_CD'], DataType::TYPE_STRING);
                        $objActSheet->setCellValueExplicit('I1', $value['SOUFUBUSYO_NM'], DataType::TYPE_STRING);

                        $objActSheet->setCellValueExplicit('A5', substr($value['HYOUKA_KIKAN_START'], 0, 4) . '/' . substr($value['HYOUKA_KIKAN_START'], 4, 2), DataType::TYPE_STRING);
                        $objActSheet->setCellValueExplicit('P5', substr($value['HYOUKA_KIKAN_END'], 0, 4) . '/' . substr($value['HYOUKA_KIKAN_END'], 4, 2), DataType::TYPE_STRING);

                        $objActSheet->setCellValueExplicit('AB5', $value['BUSYO_CD'], DataType::TYPE_STRING);
                        $objActSheet->setCellValueExplicit('AJ5', $value['BUSYO_NM'], DataType::TYPE_STRING);

                        $objActSheet->setCellValueExplicit('BA5', $value['SYAIN_NO'], DataType::TYPE_STRING);
                        $objActSheet->setCellValueExplicit('BG5', $value['SYAIN_NM'], DataType::TYPE_STRING);

                        $objActSheet->setCellValueExplicit('CC5', $value['SHIKAKU'], DataType::TYPE_STRING);

                        foreach ($objActSheet->getRowIterator() as $row_key => $row_data) {
                            foreach ($row_data->getCellIterator() as $column_key => $cell_data) {
                                $cell = $column_key . $row_key;
                                if ($cell_data->getCalculatedValue() !== null && strpos($cell_data->getCalculatedValue(), '**') === 0) {
                                    $temp_array = array(
                                        'cell' => $cell,
                                        'data' => $cell_data->getCalculatedValue()
                                    );
                                    array_push($file_star_data, $temp_array);
                                } else
                                    if (strpos($cell_data, '**') === 0) {
                                        $temp_array = array(
                                            'cell' => $cell,
                                            'data' => $cell_data
                                        );
                                        array_push($file_star_data, $temp_array);
                                    }
                            }
                        }
                    }
                    //項目セット
                    foreach ($file_star_data as $row_key => $row_data) {
                        if ($file_num == 1) {
                            array_push($cell_all, $row_data['cell']);
                        }
                        //单纯的带**的单元格
                        if ($row_data['data'] == $value['SYUTURYOKU_KOMOKU_ID'] && $value['SYUTURYOKU_KOMOKU_ID'] != '') {
                            array_push($cell_empty, $row_data['cell']);
                            //$objActSheet -> setCellValue($cell, $value['ATAI']);
                            $temp_array = array(
                                'cell' => $row_data['cell'],
                                'data' => $value['ATAI']
                            );
                            array_push($cell_not_empty, $temp_array);
                        }
                        //等于别的单元格值的带**的单元格
                        else {
                            //if ($value['SYUTURYOKU_KOMOKU_ID'] != '' && $cell_data -> getValue() == $value['SYUTURYOKU_KOMOKU_ID'])
                            if ($value['SYUTURYOKU_KOMOKU_ID'] != '' && $row_data['data'] == $value['SYUTURYOKU_KOMOKU_ID']) {
                                array_push($cell_empty, $row_data['cell']);
                                //$objActSheet -> setCellValue($cell, $value['ATAI']);
                                $temp_array = array(
                                    'cell' => $row_data['cell'],
                                    'data' => $value['ATAI']
                                );
                                array_push($cell_not_empty, $temp_array);
                            }
                            //else
                            //{
                            // $objActSheet -> setCellValue($cell, 0);
                            // $objActSheet -> setCellValue($cell, null);
                            //}
                        }
                    }
                    $file_num++;
                    //20211104 WANGYING UPD E
                }
                //クローズ
                foreach ($cell_not_empty as $cell_not_empty_value) {
                    $objActSheet->setCellValue($cell_not_empty_value['cell'], $cell_not_empty_value['data']);
                }
                foreach (array_diff($cell_all, $cell_empty) as $cell_empty_value) {
                    $objActSheet->setCellValue($cell_empty_value, 0);
                    $objActSheet->setCellValue($cell_empty_value, null);
                }
                if (in_array($strSheetNm, $sheet_name)) {
                    $objActSheet->setSelectedCell('A1');
                    Calculation::getInstance($objPHPExcel)->setCalculationCacheEnabled(true);
                    $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
                    $objWriter->save($strOutPath);
                    $objPHPExcel->disconnectWorksheets();
                    unset($objWriter, $objReader, $objPHPExcel);
                }

                $intState = 1;
            }

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        try {
            //-- ログ管理テーブルに登録 --
            if ($intState != 0) {
                //考課表作成
                $res = $this->ClsLogControl->fncLogEntryJksys('FrmKoukaHyouCreate_Excel', $intState, $lngOutCnt, $dtpYM, $strOutTarget, $downloadPath);
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

    //指定年月の末日を取得する
    public function GetEndDate($ym)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $date = $ym . '01';
            if (date('Ymd', strtotime($date)) == $date) {
                //翌月の1日前を返す
                $result['data'] = date('Ym', strtotime($date . ' +1 month -1 day'));
            } else {
                throw new \Exception('年月が不正です。yyyyMMを指定してください。');
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

}
