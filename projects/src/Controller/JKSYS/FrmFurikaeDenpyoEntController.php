<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmFurikaeDenpyoEnt;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

//*******************************************
// * sample controller
//*******************************************
class FrmFurikaeDenpyoEntController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;

    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncJKSYS'
    // );
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmFurikaeDenpyoEnt_layout');
    }

    //初期設定
    public function frmFurikaeDenpyoEntLoad()
    {
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $frmFurikaeDenpyoEnt = new FrmFurikaeDenpyoEnt();
            //データ取得(人事コントロールマスタ)
            $DTJKC = $frmFurikaeDenpyoEnt->fncGetJKCMST();
            if ($DTJKC['result'] == false) {
                throw new \Exception($DTJKC['data']);
            }
            $SYORI_YM = "";
            if ($DTJKC['row'] > 0) {
                $SYORI_YM = $DTJKC['data'][0]['SYORI_YM'];
                //日付形式を確認する
                $date = $SYORI_YM . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }
            //振替先部署取得
            $DTBusyoSaki = $frmFurikaeDenpyoEnt->fncGetBusyoMstValue();
            if ($DTBusyoSaki['result'] == false) {
                throw new \Exception($DTBusyoSaki['data']);
            }
            //振替元部署名取得
            $DTBusyoMoto = $frmFurikaeDenpyoEnt->fncGetBusyoCD($SYORI_YM);
            if ($DTBusyoMoto['result'] == false) {
                throw new \Exception($DTBusyoMoto['data']);
            }
            //社員署名取得
            $DTSyainMst = $frmFurikaeDenpyoEnt->fncGetSyainMstValue();
            if ($DTSyainMst['result'] == false) {
                throw new \Exception($DTSyainMst['data']);
            }
            $res['data'] = array(
                'SyainMst' => $DTSyainMst['data'],
                'BusyoMoto' => $DTBusyoMoto['data'],
                'BusyoSaki' => $DTBusyoSaki['data'],
                'SYORI_YM' => $SYORI_YM
            );
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //データ取得(人件費他部署振替データ)
    public function fncGetJKTFDATLoad()
    {
        $res = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            //データ取得(人事コントロールマスタ)
            if (isset($_POST['request'])) {
                $SYORI_YM = $_POST['request']['dtpTaisyouYM'];
                $jinjiYM = $_POST['request']['jinjiYM'];
                $frmFurikaeDenpyoEnt = new FrmFurikaeDenpyoEnt();
                //データ取得(人件費他部署振替データ)
                $DT2 = $frmFurikaeDenpyoEnt->fncGetJKTFDAT($SYORI_YM);

                if ($DT2['result'] == false) {
                    throw new \Exception($DT2['data']);
                }
                if ($SYORI_YM < $jinjiYM) {
                    foreach ((array) $DT2['data'] as $key => $value) {
                        $DT2['data'][$key]['btnSyainSearch'] = '<button disabled class="FrmFurikaeDenpyoEnt rowSyainSearch Tab Enter" style="border: 1px solid #77d5f7;background: #16b1e9;width: 100%;">検索</button>';
                        $DT2['data'][$key]['btnBusyoSearch'] = '<button disabled class="FrmFurikaeDenpyoEnt rowSyainSearch Tab Enter" style="border: 1px solid #77d5f7;background: #16b1e9;width: 100%;">検索</button>';
                    }
                } else {
                    foreach ((array) $DT2['data'] as $key => $value) {
                        $DT2['data'][$key]['btnSyainSearch'] = '<button onclick="rowSyainSearch_Click(' . $key . ')" id = "' . $key . '_btnSyainSearch" class="FrmFurikaeDenpyoEnt rowSyainSearch Tab Enter" style="border: 1px solid #77d5f7;background: #16b1e9;width: 100%;">検索</button>';
                        $DT2['data'][$key]['btnBusyoSearch'] = '<button onclick="rowBusyoSearch_Click(' . $key . ')" id = "' . $key . '_btnBusyoSearch" class="FrmFurikaeDenpyoEnt rowSyainSearch Tab Enter" style="border: 1px solid #77d5f7;background: #16b1e9;width: 100%;">検索</button>';
                    }
                }
                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($DT2['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $res = $this->ClsComFncJKSYS->FncCreateJqGridDataIndex($DT2['data'], $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            //设置为false会多弹出一个没有内容且标题不正的msg
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //データ取得(長期欠勤連絡)
    public function fncGetJKFKDATLoad()
    {
        $res = array(
            'result' => TRUE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $SYORI_YM = $_POST['request']['date'];

                $frmFurikaeDenpyoEnt = new FrmFurikaeDenpyoEnt();
                if ($_POST['request']['flgReload'] == 1) {
                    //データ取得(勤怠データ)
                    $DT4 = $frmFurikaeDenpyoEnt->fncGetJKKTDAT($SYORI_YM);
                    if ($DT4['result'] == false) {
                        throw new \Exception($DT4['data']);
                    }
                    $data = $DT4['data'];
                } else {
                    //データ取得(人件費振替長期欠勤者データ)
                    $DT3 = $frmFurikaeDenpyoEnt->fncGetJKFKDAT($SYORI_YM);
                    if ($DT3['result'] == false) {
                        throw new \Exception($DT3['data']);
                    }
                    $data = $DT3['data'];
                }
                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($data);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $res = $this->ClsComFncJKSYS->FncCreateJqGridDataIndex($data, $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //Excelボタンクリック
    public function excelClick()
    {
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $dtpTaisyouYM = $_POST['data']['dtpTaisyouYM'];
            if ($dtpTaisyouYM == '') {
                throw new \Exception('param error');
            } else {
                //Excel出力
                $res = $this->CreateExcelData($dtpTaisyouYM);
                if (!$res['result']) {
                    throw new \Exception($res['error']);
                }
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //Excel出力
    public function CreateExcelData($dtpTaisyouYM)
    {
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $frmFurikaeDenpyoEnt = new FrmFurikaeDenpyoEnt();

            //データ取得(人件費他部署振替データ)
            $DT2 = $frmFurikaeDenpyoEnt->fncGetJKTFDAT($dtpTaisyouYM);
            if (!$DT2['result']) {
                throw new \Exception($DT2['data']);
            }
            //***Excel出力処理****
            $basePath = dirname(dirname(dirname(__FILE__)));
            $tmpPath = $basePath . '/' . $this->ClsComFncJKSYS->FncGetPath('JksysPathFrom');

            //フォルダーが存在するかどうかのﾁｪｯｸ
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
            $file = $tmpPath . '人件費振替伝票入力.xls';
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
            $strTemplatePath = $this->ClsComFncJKSYS->FncGetPath('JksysExcelLayoutPath');
            $strTemplateFile = $basePath . '/' . $strTemplatePath . 'FrmFurikaeDenpyoEntTemplate.xls';
            //人件費振替伝票入力
            if (!file_exists($strTemplateFile)) {
                throw new \Exception('W9999');
            }

            // include __DIR__ . '/Component/Classes/PHPExcel.php';
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplateFile);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            $Mon = date('m', strtotime($dtpTaisyouYM . '01'));
            $Year = date('Y', strtotime($dtpTaisyouYM . '01'));
            $strYM = $Year . '年' . $Mon . '月分';
            $objActSheet->setCellValue('A1', $strYM);

            //起始行
            $intRow = 5;
            //データ取得(人件費他部署振替データ)が存在する
            if ($DT2['row'] > 0) {
                //明細の行数を取得
                // $intNum = $DT2['row'];

                for ($j = 1; $j < $DT2['row']; $j++) {
                    $iRow = $intRow + $j;
                    $objActSheet->insertNewRowBefore($iRow, 1);
                    $cellRange = 'A' . $iRow . ':C' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'D' . $iRow . ':H' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'I' . $iRow . ':J' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'K' . $iRow . ':O' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'P' . $iRow . ':Q' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'R' . $iRow . ':V' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'W' . $iRow . ':AI' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                }
                for ($j = 0; $j < $DT2['row']; $j++) {
                    $objActSheet->setCellValueExplicit('A' . $intRow, $DT2['data'][$j]['SYAIN_NO'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('D' . $intRow, $DT2['data'][$j]['SYAIN_NM'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('I' . $intRow, $DT2['data'][$j]['FRI_MOTO_BUSYO_CD'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('K' . $intRow, $DT2['data'][$j]['BUSYO_NM1'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('P' . $intRow, $DT2['data'][$j]['FRI_SAKI_BUSYO_CD'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('R' . $intRow, $DT2['data'][$j]['BUSYO_NM2'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('W' . $intRow, $DT2['data'][$j]['BIKOU'], DataType::TYPE_STRING);
                    //行数
                    $intRow++;
                }

                //改行
                $intRow = $intRow + 3;
            } else {
                //他部署振替データが存在しない場合
                //ヘッダーセルの結合をキャンセル
                $objActSheet->unmergeCells('A4:H4');
                $objActSheet->unmergeCells('I4:O4');
                $objActSheet->unmergeCells('P4:V4');
                $objActSheet->unmergeCells('W4:AI4');
                //内容セルの結合をキャンセル
                $objActSheet->unmergeCells('A5:C5');
                $objActSheet->unmergeCells('D5:H5');
                $objActSheet->unmergeCells('I5:J5');
                $objActSheet->unmergeCells('K5:O5');
                $objActSheet->unmergeCells('P5:Q5');
                $objActSheet->unmergeCells('R5:V5');
                $objActSheet->unmergeCells('W5:AI5');
                $objActSheet->removeRow(3, 5);

                $intRow = $intRow - 1;
            }

            //データ取得(人件費振替長期欠勤者データ)
            $DT3 = $frmFurikaeDenpyoEnt->fncGetJKFKDAT($dtpTaisyouYM);
            if (!$DT3['result']) {
                throw new \Exception($DT3['data']);
            }
            //データ取得(人件費振替長期欠勤者データ)が存在する
            if ($DT3['row'] > 0) {
                //明細の行数を取得
                // $intNum = $DT3['row'];
                //行数
                $intRow = $intRow + 1;

                //明細データ出力
                for ($j = 1; $j < $DT3['row']; $j++) {
                    $iRow = $intRow + $j;
                    $objActSheet->insertNewRowBefore($iRow, 1);
                    $cellRange = 'A' . $iRow . ':C' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'D' . $iRow . ':H' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'I' . $iRow . ':J' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'K' . $iRow . ':O' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                    $cellRange = 'P' . $iRow . ':R' . $iRow;
                    $objActSheet->mergeCells($cellRange);
                }
                for ($j = 0; $j < $DT3['row']; $j++) {
                    $objActSheet->setCellValueExplicit('A' . $intRow, $DT3['data'][$j]['SYAIN_NO'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('D' . $intRow, $DT3['data'][$j]['SYAIN_NM'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('I' . $intRow, $DT3['data'][$j]['BUSYO_CD'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('K' . $intRow, $DT3['data'][$j]['BUSYO_NM'], DataType::TYPE_STRING);
                    $objActSheet->setCellValueExplicit('P' . $intRow, $DT3['data'][$j]['SYUKKIN_RITU'], DataType::TYPE_STRING);
                    //行数
                    $intRow++;
                }
            } else {
                //長期欠勤連絡データが存在しない場合
                //ヘッダーセルの結合をキャンセル
                $objActSheet->unmergeCells('A' . ($DT2['row'] + 8) . ':H' . ($DT2['row'] + 8));
                $objActSheet->unmergeCells('I' . ($DT2['row'] + 8) . ':O' . ($DT2['row'] + 8));
                $objActSheet->unmergeCells('P' . ($DT2['row'] + 8) . ':R' . ($DT2['row'] + 8));
                //内容セルの結合をキャンセル
                $objActSheet->unmergeCells('A' . ($DT2['row'] + 9) . ':C' . ($DT2['row'] + 9));
                $objActSheet->unmergeCells('D' . ($DT2['row'] + 9) . ':H' . ($DT2['row'] + 9));
                $objActSheet->unmergeCells('I' . ($DT2['row'] + 9) . ':J' . ($DT2['row'] + 9));
                $objActSheet->unmergeCells('K' . ($DT2['row'] + 9) . ':O' . ($DT2['row'] + 9));
                $objActSheet->unmergeCells('P' . ($DT2['row'] + 9) . ':R' . ($DT2['row'] + 9));
                $objActSheet->removeRow($intRow - 1, 3);
            }

            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($file);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        return $res;
    }

    //登録ボタンクリック
    public function entClick()
    {
        $tranStartFlg = FALSE;
        $frmFurikaeDenpyoEnt = new FrmFurikaeDenpyoEnt();

        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $dtpTaisyouYM = '';
            $strHiddUpdDate1 = '';
            $strHiddUpdDate2 = '';
            $DataTF = '';
            $DataFK = '';

            if (isset($_POST['data'])) {
                $dtpTaisyouYM = $_POST['data']['dtpYM'];
                $strHiddUpdDate1 = $_POST['data']['strHiddUpdDate1'];
                $strHiddUpdDate2 = $_POST['data']['strHiddUpdDate2'];
                if (isset($_POST['data']['DataTF'])) {
                    $DataTF = $_POST['data']['DataTF'];
                }
                if (isset($_POST['data']['DataFK'])) {
                    $DataFK = $_POST['data']['DataFK'];
                }
            }
            if ($dtpTaisyouYM == '') {
                throw new \Exception('param error');
            } else {
                $res = $this->InPutCheck3($dtpTaisyouYM, $strHiddUpdDate1, $strHiddUpdDate2);
                if ($res['result']) {
                    //トランザクション開始
                    $frmFurikaeDenpyoEnt->Do_transaction();
                    $tranStartFlg = TRUE;
                    if (isset($_POST['data']['DataTF'])) {
                        //人件費他部署振替データ削除処理(SQL)
                        $res = $frmFurikaeDenpyoEnt->fncDelJKTFDAT($dtpTaisyouYM);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        //追加処理
                        //一覧(他部署振替)が表示されて
                        for ($i = 0; $i < count($DataTF); $i++) {
                            if ($this->ClsComFncJKSYS->FncNv($DataTF[$i]['SYAIN_NO']) <> '') {
                                $strSyainNo = $DataTF[$i]['SYAIN_NO'];
                                $strMotoBusyoCD = $this->ClsComFncJKSYS->FncNv($DataTF[$i]['FRI_MOTO_BUSYO_CD']);
                                $strSakiBusyoCD = $DataTF[$i]['FRI_SAKI_BUSYO_CD'];
                                $strBiko = $this->ClsComFncJKSYS->FncNv($DataTF[$i]['BIKOU']);
                                $strCreateDate = $this->ClsComFncJKSYS->FncNv($DataTF[$i]['CREATE_DATE']);
                                $strCreateCD = $this->ClsComFncJKSYS->FncNv($DataTF[$i]['CRE_SYA_CD']);
                                $strCreateAPP = $this->ClsComFncJKSYS->FncNv($DataTF[$i]['CRE_PRG_ID']);
                                $res = $frmFurikaeDenpyoEnt->fncInsJKTFDAT($dtpTaisyouYM, $strSyainNo, $strMotoBusyoCD, $strSakiBusyoCD, $strBiko, $strCreateDate, $strCreateCD, $strCreateAPP);
                                if (!$res['result']) {
                                    throw new \Exception($res['data']);
                                }
                            }
                        }
                    }
                    if (isset($_POST['data']['DataFK'])) {
                        //人件費振替長期欠勤者データ削除処理
                        $res = $frmFurikaeDenpyoEnt->fncDelJKFKDAT($dtpTaisyouYM);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        //追加処理
                        //一覧(長期欠勤)が表示されている
                        for ($i = 0; $i < count($DataFK); $i++) {
                            $strSyainNo = $DataFK[$i]['SYAIN_NO'];
                            $strBusyoCD = $DataFK[$i]['BUSYO_CD'];
                            $strSyukkin = $DataFK[$i]['SYUKKIN_RITU'];
                            $strCreateDate = $DataFK[$i]['CREATE_DATE'];
                            $strCreateCD = $DataFK[$i]['CRE_SYA_CD'];
                            $strCreateAPP = $DataFK[$i]['CRE_PRG_ID'];

                            $res = $frmFurikaeDenpyoEnt->fncInsJKFKDAT($dtpTaisyouYM, $strSyainNo, $strBusyoCD, $strSyukkin, $strCreateDate, $strCreateCD, $strCreateAPP);
                            if (!$res['result']) {
                                throw new \Exception($res['data']);
                            }
                        }
                    }
                    //コミット
                    $frmFurikaeDenpyoEnt->Do_commit();
                    $res['data'] = '';
                    $res['result'] = TRUE;
                } else {
                    throw new \Exception($res['error']);
                }
            }
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $frmFurikaeDenpyoEnt->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //削除ボタンクリック
    public function deleteClick()
    {
        $tranStartFlg = FALSE;
        $frmFurikaeDenpyoEnt = new FrmFurikaeDenpyoEnt();

        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $dtpTaisyouYM = '';
            $strHiddUpdDate1 = '';
            $strHiddUpdDate2 = '';
            if (isset($_POST['data'])) {
                $dtpTaisyouYM = $_POST['data']['dtpYM'];
                $strHiddUpdDate1 = $_POST['data']['strHiddUpdDate1'];
                $strHiddUpdDate2 = $_POST['data']['strHiddUpdDate2'];
            }

            if ($dtpTaisyouYM == '') {
                throw new \Exception('param error');
            } else {
                $res = $this->InPutCheck3($dtpTaisyouYM, $strHiddUpdDate1, $strHiddUpdDate2);
                if ($res['result']) {
                    //トランザクション開始
                    $frmFurikaeDenpyoEnt->Do_transaction();
                    $tranStartFlg = TRUE;
                    //人件費他部署振替データ削除処理
                    $res = $frmFurikaeDenpyoEnt->fncDelJKTFDAT($dtpTaisyouYM);
                    if (!$res['result']) {
                        throw new \Exception($res['data']);
                    }
                    //人件費振替長期欠勤者データ削除処理
                    $res = $frmFurikaeDenpyoEnt->fncDelJKFKDAT($dtpTaisyouYM);
                    if (!$res['result']) {
                        throw new \Exception($res['data']);
                    }
                    //コミット
                    $frmFurikaeDenpyoEnt->Do_commit();
                    $res['data'] = '';
                    $res['result'] = TRUE;
                } else {
                    throw new \Exception($res['error']);
                }
            }
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $frmFurikaeDenpyoEnt->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //画面項目入力チェック3
    function InPutCheck3($dtpTaisyouYM, $strHiddUpdDate1, $strHiddUpdDate2)
    {
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $frmFurikaeDenpyoEnt = new FrmFurikaeDenpyoEnt();
            //データ取得(人件費他部署振替データ) 更新日の最大値を取得
            $res = $frmFurikaeDenpyoEnt->fncGetMaxJKTFDAT($dtpTaisyouYM);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            $strUpdDate1 = $res['data'][0]['UPD_DATE'];

            //データ取得(人件費振替長期欠勤者データ) 更新日の最大値を取得
            $res = $frmFurikaeDenpyoEnt->fncGetMaxJKFKDAT($dtpTaisyouYM);
            $strUpdDate2 = $res['data'][0]['UPD_DATE'];
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            if ($strUpdDate1 <> $strHiddUpdDate1 || $strUpdDate2 <> $strHiddUpdDate2) {
                throw new \Exception('W0018');
            }
            $res['result'] = TRUE;
            return $res;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
            return $res;
        }
    }

}
