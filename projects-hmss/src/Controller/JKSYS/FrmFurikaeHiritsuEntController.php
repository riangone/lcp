<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmFurikaeHiritsuEnt;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmFurikaeHiritsuEntController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }
    public $FrmFurikaeHiritsuEnt;

    public function index()
    {
        $this->render('index', 'FrmFurikaeHiritsuEnt_layout');
    }

    //データ取得(人事コントロールマスタ)
    public function fncSelalldataSQL()
    {
        $res = array(
            'result' => false,
            'error' => ''
        );
        try {
            $this->FrmFurikaeHiritsuEnt = new FrmFurikaeHiritsuEnt();
            //データ取得(SQL)
            $DT = $this->FrmFurikaeHiritsuEnt->FncGetJKCMST();
            if (!$DT['result']) {
                throw new \Exception($DT['data']);
            }
            $SYORI_YM = "";
            //データが存在する場合
            if ($DT["row"] > 0) {
                $SYORI_YM = $DT['data']['0']['SYORI_YM'];
                //日付形式を確認する
                $date = $SYORI_YM . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                }
                $res['data']['SYORI_YM'] = $SYORI_YM;
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }
            //データ取得(人件費振替比率データ)
            $DT2 = $this->FrmFurikaeHiritsuEnt->FncGetJKFHDAT($SYORI_YM);
            if (!$DT2['result']) {
                throw new \Exception($DT2['data']);
            }
            //データが存在する場合
            $res['data']['DT2'] = $DT2;
            if ($DT2['row'] == 0) {
                $year = substr($SYORI_YM, 0, 4);
                $month = substr($SYORI_YM, 4, 2);
                $date = date_create($year . "-" . $month);
                //画面.対象年月 - 1
                date_add($date, date_interval_create_from_date_string("-1 month"));
                //データ取得(人件費振替比率データ)
                $DT3 = $this->FrmFurikaeHiritsuEnt->FncGetJKFHDAT(date_format($date, "Ym"));
                if (!$DT3['result']) {
                    throw new \Exception($DT3['data']);
                }
                $res['data']['DT3'] = $DT3;
            }
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = false;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //検索ボタンクリック
    public function fncSelnowdataSQL()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $strSyoriYM = $_POST["data"]["dtpTaisyouYM"];

            $this->FrmFurikaeHiritsuEnt = new FrmFurikaeHiritsuEnt();
            //データ取得(SQL)
            $DT = $this->FrmFurikaeHiritsuEnt->FncGetJKCMST();
            if (!$DT['result']) {
                throw new \Exception($DT['data']);
            }
            //データが存在する場合
            if ($DT["row"] > 0) {
                $jinjiYM = $DT['data']['0']['SYORI_YM'];
            } else {
                throw new \Exception("コントロールマスタが存在しません！");
            }
            //データ取得(人件費振替比率データ)
            $DT2 = $this->FrmFurikaeHiritsuEnt->FncGetJKFHDAT($strSyoriYM);
            if (!$DT2['result']) {
                throw new \Exception($DT2['data']);
            }
            $result['Count'] = 1;
            if ($DT2['row'] == 0) {
                $year = substr($strSyoriYM, 0, 4);
                $month = substr($strSyoriYM, 4, 2);
                $date1 = date_create($year . "-" . $month);
                date_add($date1, date_interval_create_from_date_string("-1 month"));
                //画面.対象年月 - 1
                $strSyoriYM = date_format($date1, "Ym");
                //データ取得(SQL)
                $DT2 = $this->FrmFurikaeHiritsuEnt->FncGetJKFHDAT($strSyoriYM);
                if (!$DT2['result']) {
                    throw new \Exception($DT2['data']);
                }
                $result['Count'] = 0;
            }
            $result['data']['DT2'] = $DT2;
            $result['data']['jinjiYM'] = $jinjiYM;

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //登録ボタンクリック
    public function btnEntClick()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            //入力チェック
            $result = $this->InPutCheck2($_POST['data']);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
            $lblState = $_POST['data']['lblState'];
            if ($lblState == "新規") {
                //追加処理(SQL)
                $result = $this->FncInsJKFHDAT($_POST['data']);
            } else
                if ($lblState == "修正") {
                    //更新処理(SQL)
                    $result = $this->FncUpdJKFHDAT($_POST['data']);
                }
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //削除ボタンクリック
    public function fncDelJKFHDAT()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $tranStartFlg = FALSE;
        try {
            //入力チェック
            $result_check = $this->InPutCheck2($_POST['data']);
            if (!$result_check['result']) {
                throw new \Exception($result_check['error']);
            }
            $this->FrmFurikaeHiritsuEnt = new FrmFurikaeHiritsuEnt();
            //トランザクション開始
            $this->FrmFurikaeHiritsuEnt->Do_transaction();
            $tranStartFlg = TRUE;
            //削除処理(SQL)
            $result_del = $this->FrmFurikaeHiritsuEnt->FncDelJKFHDAT($_POST['data']['dtpTaisyouYM']);
            if (!$result_del['result']) {
                throw new \Exception($result_del['data']);
            }
            //コミット
            $this->FrmFurikaeHiritsuEnt->Do_commit();

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($tranStartFlg) {
                $this->FrmFurikaeHiritsuEnt->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

    //入力チェック2
    public function InPutCheck2($postData)
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $strUpdDate = "";
            //データ取得(人件費他部署振替データ)
            $FrmFurikaeHiritsuEnt = new FrmFurikaeHiritsuEnt();
            //データ取得(人件費振替比率データ)
            $result = $FrmFurikaeHiritsuEnt->FncGetJKFHDAT($postData["dtpTaisyouYM"]);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                $strUpdDate = $result['data'][0]['UPD_DATE'];
            }
            //更新日付
            if ($strUpdDate <> $postData['strHiddUpdDate']) {
                throw new \Exception('W0018');
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //追加処理
    public function FncInsJKFHDAT($postData)
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $tranStartFlg = FALSE;
        try {
            $this->FrmFurikaeHiritsuEnt = new FrmFurikaeHiritsuEnt();
            //トランザクション開始
            $this->FrmFurikaeHiritsuEnt->Do_transaction();
            $tranStartFlg = TRUE;
            //SQL実行(Insert/Update)
            $result_ins = $this->FrmFurikaeHiritsuEnt->FncInsJKFHDAT($postData);
            if (!$result_ins['result']) {
                throw new \Exception($result_ins['data']);
            }
            //コミット
            $this->FrmFurikaeHiritsuEnt->Do_commit();

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($tranStartFlg) {
                $this->FrmFurikaeHiritsuEnt->Do_rollback();
            }
        }
        return $result;
    }

    //更新処理
    public function FncUpdJKFHDAT($postData)
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $tranStartFlg = FALSE;
        try {
            $this->FrmFurikaeHiritsuEnt = new FrmFurikaeHiritsuEnt();
            //トランザクション開始
            $this->FrmFurikaeHiritsuEnt->Do_transaction();
            $tranStartFlg = TRUE;
            //SQL実行(Insert/Update)
            $result_upd = $this->FrmFurikaeHiritsuEnt->FncUpdJKFHDAT($postData);
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }
            //コミット
            $this->FrmFurikaeHiritsuEnt->Do_commit();

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($tranStartFlg) {
                $this->FrmFurikaeHiritsuEnt->Do_rollback();
            }
        }
        return $result;
    }

    //Excelボタンクリック
    public function btnExcelClick()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $intState = 0;
            //テンプレート保存先のパスを取得する
            $strTemplatePath = $this->ClsComFncJKSYS->FncGetPath("JksysExcelLayoutPath");
            $basePath = dirname(dirname(dirname(__FILE__)));
            $_strxltPath = $basePath . '/' . $strTemplatePath . "FrmFurikaeHiritsuEntTemplate.xlt";
            if (!file_exists($_strxltPath)) {
                throw new \Exception("W9999");
            }
            //***Excel出力処理****
            //保存先のパス
            $tmpPath = $basePath . "/" . $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
            if ($this->ClsComFncJKSYS->FncGetPath("JksysPathFrom") == "") {
                throw new \Exception('W0001');
            }
            if (!file_exists($tmpPath)) {
                throw new \Exception("W0015");
            }
            if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
            }
            $dtpTaisyouYM = $_POST['data']['dtpTaisyouYM'];
            //データ取得(人件費振替比率データ)
            $this->FrmFurikaeHiritsuEnt = new FrmFurikaeHiritsuEnt();
            $DT = $this->FrmFurikaeHiritsuEnt->FncGetJKFHDAT($dtpTaisyouYM);
            if (!$DT['result']) {
                throw new \Exception($DT['data']);
            }
            if ($DT['row'] == 0) {
                throw new \Exception('I0001');
            }
            //出力Excel
            $strFilePath = $tmpPath . "人件費振替比率.xls";
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

            $intState = $this->CreateExcelData($strFilePath, $_strxltPath, $intState, $DT['data']);
            if (!$intState['result']) {
                throw new \Exception($intState['error']);
            }
            if ($intState['data'] == 1) {
                $result['data'] = "I0011";
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //Excel出力
    public function CreateExcelData($strFilePath, $strxltPath, $intState, $dtRowCol)
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strxltPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            $objActSheet->setCellValue("A" . 1, substr($dtRowCol[0]['TAISYOU_YM'], 0, 4) . "年" . substr($dtRowCol[0]['TAISYOU_YM'], 4, 2) . "月分");
            $objActSheet->setCellValue("G" . 5, $dtRowCol[0]['BNS_MITUMORI']);
            $objActSheet->setCellValue("G" . 6, $dtRowCol[0]['KENKO_HKN_RYO']);
            $objActSheet->setCellValue("G" . 7, $dtRowCol[0]['KAIGO_HKN_RYO']);
            $objActSheet->setCellValue("G" . 8, $dtRowCol[0]['KOUSEINENKIN']);
            $objActSheet->setCellValue("G" . 9, $dtRowCol[0]['JIDOUTEATE']);
            $objActSheet->setCellValue("G" . 12, $dtRowCol[0]['KOYOU_HKN_RYO']);
            $objActSheet->setCellValue("G" . 13, $dtRowCol[0]['ROUSAI_HKN_RYO']);
            $objActSheet->setCellValue("G" . 14, $dtRowCol[0]['TAISYOKUTEATE']);
            $objActSheet->setCellValue("M" . 5, $dtRowCol[0]['KYK_BNS_MITUMORI']);
            $objActSheet->setCellValue("M" . 6, $dtRowCol[0]['KYK_KENKO_HKN_RYO']);
            $objActSheet->setCellValue("M" . 7, $dtRowCol[0]['KYK_KAIGO_HKN_RYO']);
            $objActSheet->setCellValue("M" . 8, $dtRowCol[0]['KYK_KOUSEINENKIN']);
            $objActSheet->setCellValue("M" . 9, $dtRowCol[0]['KYK_JIDOUTEATE']);
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($strFilePath);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);

            $intState = 1;

            $result['result'] = true;
            $result['data'] = $intState;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        if ($intState !== 1) {
            if (file_exists($strFilePath)) {
                @unlink($strFilePath);
            }
        }
        return $result;
    }

}
