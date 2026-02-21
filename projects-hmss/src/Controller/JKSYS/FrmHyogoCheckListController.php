<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmHyogoCheckList;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmHyogoCheckListController extends AppController
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
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFncJKSYS');
    }
    public $frmHyogoCheckList;

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmHyogoCheckList_layout');
    }

    //画面初期化(画面起動時)
    public function formit()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->frmHyogoCheckList = new FrmHyogoCheckList();
            //データ取得(評価実施期間)
            $DT = $this->frmHyogoCheckList->FncGetJHTRDAT();
            if (!$DT['result']) {
                throw new \Exception($DT['data']);
            }
            if ($DT['data'][0]['JISSHI_YM'] == "/") {
                throw new \Exception("コントロールマスタが存在しません！");
            }
            //データ取得(評価対象期間)
            $DT2 = $this->frmHyogoCheckList->FncGetJHTRDAT2("");
            if (!$DT2['result']) {
                throw new \Exception($DT2['data']);
            }
            $result['data']['DT'] = array();
            $result['currYM'] = date('Y/m');
            if ($DT2['row'] > 0) {
                //評価対象期間
                if ($DT2['data'][0]['KIKAN'] == ' ～ ') {
                    $DT2['data'][0]['KIKAN'] = '';
                }
                $result['data']['DT'] = $DT2['data'];
                //実施年月
                $result['currYM'] = substr($DT2['data'][0]['JISSHI_YM'], 0, 4) . '/' . substr($DT2['data'][0]['JISSHI_YM'], 4, 2);
            }
            //評価実施年月設定
            $YMSet = $this->SetHyoukaCombox();
            if (!$YMSet['result']) {
                throw new \Exception($YMSet['error']);
            }
            if (count($YMSet['data']['JISSHI_YM']) > 0) {
                $result['data']['YMSet'] = $YMSet['data']['JISSHI_YM'];
            }
            $result['data']['strSyokoukyuMonth'] = $YMSet['data']['KAKI_BONUS_MONTH'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //評価実施年月設定
    public function SetHyoukaCombox()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->frmHyogoCheckList = new FrmHyogoCheckList();
            $DR = $this->frmHyogoCheckList->FncGetTeikiSyokyuMonth();
            if (!$DR['result']) {
                throw new \Exception($DR['data']);
            }
            if ($DR['row'] == 0) {
                throw new \Exception("コントロールマスタが存在しません！");
            }
            $_strSyokoukyuMonth = $this->ClsComFncJKSYS->FncNv($DR['data']['0']['KAKI_BONUS_MONTH']);
            $YMSet = $this->frmHyogoCheckList->SetHyoukaCombox($_strSyokoukyuMonth);
            if (!$YMSet['result']) {
                throw new \Exception($YMSet['data']);
            }
            if ($YMSet['row'] == 0) {
                throw new \Exception('W9999');
            }
            foreach ((array) $YMSet['data']['0'] as $value) {
                if ($value == null) {
                    throw new \Exception('W9999');
                }
            }
            $result['data']['JISSHI_YM'] = $YMSet['data'];
            $result['data']['KAKI_BONUS_MONTH'] = $DR['data']['0']['KAKI_BONUS_MONTH'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //評価実施年月フォーカス移動時
    public function cmbJissiSelectedIndexChanged()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $this->frmHyogoCheckList = new FrmHyogoCheckList();
                $result = $this->frmHyogoCheckList->FncGetJHTRDAT2($_POST["data"]['strJisshi']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['row'] > 0 && $result['data'][0]['KIKAN'] == ' ～ ') {
                    $result['row'] = 0;
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //Excelボタンクリック
    public function cmdExcelClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'key' => ''
        );
        $fileName = "";
        $strZenKai = "";
        $strZenZenKai = "";
        $strZBonus = "";
        $strZZBonus = "";
        $intRec = 0;

        $intState = 0;
        $lngOutCnt = 0;
        try {
            if (isset($_POST['data'])) {
                $strJisshi = $_POST["data"]["JISSI"];
                //実施年月
                $strFrom = $_POST["data"]["dtFrom"];
                //評価対象期間開始
                $strTo = $_POST["data"]["dtTo"];
                //評価対象期間終了
                $strSyokoukyuMonth = $_POST["data"]["strSyokoukyuMonth"];
            } else {
                throw new \Exception('param error');
            }
            //出力先パス
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

            $strTemplatePath = $this->ClsComFncJKSYS->FncGetPath("JksysExcelLayoutPath");
            $strTemplatePath = $basePath . '/' . $strTemplatePath . "FrmHyogoCheckListTemplate.xls";
            //評語チェックリスト
            if (!file_exists($strTemplatePath)) {
                $result['key'] = "W9999";
                throw new \Exception("テンプレートファイルが存在しません。");
            }
            //Excel出力(出力データ、出力先、職種集計区分[1件目]、ボーナスチェック[今回、前回、前々回]、戻り値)
            $fileName = $tmpPath . "評語チェックリスト.xls";
            if (file_exists($fileName) && !is_writable($fileName)) {
                throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
            } elseif (!file_exists($fileName)) {
                $dir = @opendir(dirname($fileName));
                if ($dir === false) {
                    //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                if (@readdir($dir) == false) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                @closedir($dir);
            }

            $this->frmHyogoCheckList = new FrmHyogoCheckList();
            //存在チェック
            //データ取得(評価履歴データ)
            $result = $this->frmHyogoCheckList->FncGetJHRDAT($strJisshi);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //データが存在する場合
            if ($result['row'] > 0) {
                $intState = 9;
                //データ取得(評価取込履歴データ)
                $DT = $this->frmHyogoCheckList->FncGetJHTRDAT3($strJisshi, $strSyokoukyuMonth);
                if (!$DT['result']) {
                    throw new \Exception($DT['data']);
                }
                for ($i = 0; $i < $DT['row']; $i++) {
                    if ($i == 0) {
                        //前回分
                        $strZenKai = $DT['data'][$i]['JISSHI_YM'];
                    } else {
                        //前々回文
                        $strZenZenKai = $DT['data'][$i]['JISSHI_YM'];
                        break;
                    }
                }
                //ボーナス月をチェックする
                //データ取得(人事コントロールマスタ)
                $DT2 = $this->frmHyogoCheckList->FncGetBonus();
                if (!$DT2['result']) {
                    throw new \Exception($DT2['data']);
                }
                //データが存在する場合
                if ($DT2['row'] > 0) {
                    $strKaki = $DT2['data'][0]['KAKI_BONUS_MONTH'];
                    $strTouki = $DT2['data'][0]['TOUKI_BONUS_MONTH'];
                    //評価実施年月
                    if (substr($strJisshi, 5, 2) == $strKaki) {
                        //夏季ボーナス月の場合
                        $strBonus = substr($strJisshi, 0, 4) . "年上期評語チェックリスト";
                    } elseif (substr($strJisshi, 5, 2) == $strTouki) {
                        //冬季ボーナス月の場合
                        $strBonus = substr($strJisshi, 0, 4) . "年下期評語チェックリスト";
                    } else {
                        $strBonus = substr($strJisshi, 0, 4) . "年" . substr($strJisshi, 5, 2) . "月評語チェックリスト";
                    }
                    //前月
                    if ($strZenKai <> "") {
                        if (substr($strZenKai, 4, 2) == $strKaki) {
                            //夏季ボーナス月の場合
                            $strZBonus = substr($strZenKai, 2, 2) . "年夏";
                        } elseif (substr($strZenKai, 4, 2) == $strTouki) {
                            //冬季ボーナス月の場合
                            $strZBonus = substr($strZenKai, 2, 2) . "年冬";
                        } else {
                            $strZBonus = substr($strZenKai, 0, 4) . "/" . substr($strZenKai, 4, 2);
                        }
                    }
                    //前々月
                    if ($strZenZenKai <> "") {
                        if (substr($strZenZenKai, 4, 2) == $strKaki) {
                            //夏季ボーナス月の場合
                            $strZZBonus = substr($strZenZenKai, 2, 2) . "年夏";
                        } elseif (substr($strZenZenKai, 4, 2) == $strTouki) {
                            //冬季ボーナス月の場合
                            $strZZBonus = substr($strZenZenKai, 2, 2) . "年夏";
                        } else {
                            $strZZBonus = substr($strZenZenKai, 0, 4) . "/" . substr($strZenZenKai, 4, 2);
                        }
                    }
                }
                //評価チェックリストデータを取得する
                $DT3 = $this->frmHyogoCheckList->FncGetHCRDAT($strZenKai, $strZenZenKai, $strJisshi, $strFrom, $strTo);
                if (!$DT3['result']) {
                    throw new \Exception($DT3['data']);
                }
                //データが存在する場合
                if ($DT3['row'] > 0) {
                    //一件目の職種集計区分を取得
                    $strKbn = $DT3['data'][0]['職種集計区分'];
                    $result_intRec = $this->createExcelData($DT3, $fileName, $strKbn, $strBonus, $strZBonus, $strZZBonus, $intRec, $strTemplatePath);
                    if (!$result_intRec['result']) {
                        throw new \Exception($result_intRec['error']);
                    }
                    $intRec = $result_intRec['intRec'];
                } else {
                    $result['key'] = "W9999";
                    $intState = 1;
                    throw new \Exception($strJisshi . "評語チェックリストデータが存在しません。");
                }
                $lngOutCnt = $DT3["row"];
            } else {
                $result['key'] = "W9999";
                $intState = 1;
                throw new \Exception($strJisshi . "実施分の評価データが存在しません。評価情報取込を行ってください。");
            }
            if ($intRec == 1) {
                $intState = 1;
                //完了メッセージ
                $result['data'] = "I0011";

                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            //エラー時出力ファイル削除
            if ($this->ClsComFncJKSYS->FncFileExists($fileName)) {
                @unlink($fileName);
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        //ログ管理
        try {
            //intState<>0の場合、ログ管理テーブルに登録
            if ($intState <> 0) {
                //部署別人件費明細書
                $resultJksys = $this->ClsLogControl->fncLogEntryJksys("FrmHyogoCheckList_Excel", $intState, $lngOutCnt, $strJisshi, $strFrom, $strTo, $fileName);
                if (!$resultJksys['result']) {
                    throw new \Exception($resultJksys['Msg']);
                }
            }
        } catch (\Exception $e1) {
            $result['result'] = FALSE;
            $result['error'] = $e1->getMessage();
        }
        $this->fncReturn($result);
    }

    //Excel出力
    function createExcelData($dt, $fileName, $strKbn, $strBonus, $strZBonus, $strZZBonus, $intRec, $strTemplatePath)
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $strKbn_NM_B = "";
        $strKbn_NM_A = "";
        $strSSK_B = "";
        $strSSK_A = "";
        $intCnt = 0;
        //シート№
        $intSNo = 1;
        //スタート行番号
        $intRow = 5;
        try {
            //防止数据量大时导致excel对象内存溢出
            ini_set('memory_limit', '-1');
            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            //職種集計区分_件数確認
            $sheetName = array();
            for ($i = 0; $i < $dt['row']; $i++) {
                $strSSK_B = $dt['data'][$i]['職種集計区分'];
                if ($strSSK_B <> $strSSK_A) {
                    $intCnt++;
                    $strSSK_A = $strSSK_B;
                    //シート名
                    $strKbn_NM_B = $dt['data'][$i]["職種集計区分名"];
                    if ($strKbn_NM_B == $strKbn_NM_A) {
                        $sheetName[] = $strKbn_NM_B . $intSNo;
                    } else {
                        $sheetName[] = $strKbn_NM_B;
                    }
                    $strKbn_NM_A = $strKbn_NM_B;
                    $intSNo++;
                }
            }
            //***** ページヘッダー *****
            //操作シート
            $sheetNameDup = array();
            for ($i = 0; $i < $intCnt; $i++) {
                $objCloneSheet = clone $objPHPExcel->getSheet(0);
                //シート名
                if (in_array($sheetName[$i], $sheetNameDup, true)) {
                    $objCloneSheet->setTitle('Sheet' . ($i + 1));
                } else {
                    $objCloneSheet->setTitle($sheetName[$i]);
                    array_push($sheetNameDup, $sheetName[$i]);
                }
                $objPHPExcel->addSheet($objCloneSheet);
            }

            $strSSK_A = "";
            $intSNo = 1;

            for ($i = 0; $i < $dt['row']; $i++) {
                $strSSK_B = $dt['data'][$i]['職種集計区分'];

                if ($strSSK_B <> $strSSK_A) {
                    if ($intSNo <> 1) {
                        $objPHPExcel->setActiveSheetIndex($intSNo - 1);
                        $objActSheet = $objPHPExcel->getActiveSheet();
                        $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
                        $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
                        $objActSheet->getStyle('A4:' . 'H' . ($intRow - 1))->applyFromArray($styleArrayIn);
                        $objActSheet->getStyle('A4:' . 'H' . ($intRow - 1))->applyFromArray($styleArrayOut);
                        $objActSheet->setSelectedCell("A1");
                    }
                    $objPHPExcel->setActiveSheetIndex($intSNo);
                    $objActSheet = $objPHPExcel->getActiveSheet();
                    //ページ比率
                    $objPHPExcel->getActivesheet()->getSheetView()->setZoomScale(85);
                    //対象月
                    $objActSheet->setCellValue('C2', $strBonus);
                    //職種集計区分名
                    $objActSheet->setCellValue('A2', '(' . $dt['data'][$i]['職種集計区分名'] . ")");
                    //前々回
                    $objActSheet->setCellValue('E4', $strZZBonus);
                    //前回
                    $objActSheet->setCellValue('F4', $strZBonus);

                    $objActSheet->setCellValue('A4', '資格');
                    $objActSheet->setCellValue('B4', '点数');
                    $objActSheet->setCellValue('C4', '氏名');
                    $objActSheet->setCellValue('D4', '部署');
                    $objActSheet->setCellValue('G4', '申請');
                    $objActSheet->setCellValue('H4', '決定');

                    $strSSK_A = $strSSK_B;
                    $intSNo = $intSNo + 1;
                    $intRow = 5;
                }
                //***** 明細データ *****
                $objActSheet->setCellValue('A' . $intRow, $dt['data'][$i]['資格名']);
                $objActSheet->setCellValue('B' . $intRow, $dt['data'][$i]['点数']);
                $objActSheet->setCellValue('C' . $intRow, $dt['data'][$i]['氏名']);
                $objActSheet->setCellValue('D' . $intRow, $dt['data'][$i]['部門名']);
                $objActSheet->setCellValue('E' . $intRow, $dt['data'][$i]['前々回最終評価']);
                $objActSheet->setCellValue('F' . $intRow, $dt['data'][$i]['前回最終評価']);
                $objActSheet->setCellValue('G' . $intRow, $dt['data'][$i]['今回評価']);
                $objActSheet->setCellValue('H' . $intRow, "");

                $intRow++;
            }
            $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)));
            $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)));
            $objActSheet->getStyle('A4:' . 'H' . ($intRow - 1))->applyFromArray($styleArrayIn);
            $objActSheet->getStyle('A4:' . 'H' . ($intRow - 1))->applyFromArray($styleArrayOut);
            $objActSheet->setSelectedCell("A1");
            //シートを削除する
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->removeSheetByIndex(0);
            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);

            $intRec = 1;
            $result['intRec'] = $intRec;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

}
