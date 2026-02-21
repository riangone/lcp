<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJinkenhiInfoCreate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20240307           202402_人事給与システム_人件費データexce入出力機能追加l   caina
 * --------------------------------------------------------------------------------------------
 */

//*******************************************
// * sample controller
//*******************************************
class FrmJinkenhiInfoCreateController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $uploadfile;
    //20240307 caina ins s
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }
    //20240307 caina ins e

    public $frmJinkenhiInfoCreate = "";

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmJinkenhiInfoCreate_layout');
    }

    //ページロード
    public function frmJinkenhiInfoCreateLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        try {
            $this->frmJinkenhiInfoCreate = new FrmJinkenhiInfoCreate();
            //--- 処理年月取得 ---
            $shoriYM = $this->frmJinkenhiInfoCreate->selShoriYMSQL();
            if (!$shoriYM['result']) {
                throw new \Exception($shoriYM['data']);
            }
            $SYORI_YM = "";
            if ($shoriYM["row"] > 0) {
                $SYORI_YM = $shoriYM["data"][0]["SYORI_YM"];
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
            $result["data"]["SYORI_YM"] = $SYORI_YM;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //支給データ取込みが完了しているか確認
    public function selShikyuSyoreiKinData()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (!isset($_POST['data']['dtpYM'])) {
                throw new \Exception("param error");
            }
            $dtpYM = $_POST['data']['dtpYM'];
            $this->frmJinkenhiInfoCreate = new FrmJinkenhiInfoCreate();
            //支給データ取込みが完了しているか確認
            $result = $this->frmJinkenhiInfoCreate->selShikyuSyoreiKinDataSQL($dtpYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //人件費データ生成
    public function createJinkenhiData()
    {
        $tranStartFlg = FALSE;
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (!isset($_POST['data']['dtpYM'])) {
                throw new \Exception("param error");
            }
            $dtpYM = $_POST['data']['dtpYM'];
            $this->frmJinkenhiInfoCreate = new FrmJinkenhiInfoCreate();
            //his ->トランザクション開始
            $this->frmJinkenhiInfoCreate->Do_transaction();
            $tranStartFlg = TRUE;
            //人件費データを削除する
            $result_del = $this->frmJinkenhiInfoCreate->delJinkenhiDataSQL($dtpYM);
            if (!$result_del['result']) {
                throw new \Exception($result_del['data']);
            }
            //役員以外の給与データを登録する
            $result_ins = $this->frmJinkenhiInfoCreate->insJinkenhiDataSQL("0", $dtpYM);
            if (!$result_ins['result']) {
                throw new \Exception($result_ins['data']);
            }
            //役員の給与データを登録する
            $result_ins = $this->frmJinkenhiInfoCreate->insJinkenhiDataSQL("1", $dtpYM);
            if (!$result_ins['result']) {
                throw new \Exception($result_ins['data']);
            }
            //人件費振替長期欠勤者データに登録されている社員の人員カウントを0にする
            $result_upd = $this->frmJinkenhiInfoCreate->updJinkenhiKekkinSQL($dtpYM);
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }
            //人件費他部署振替データに登録されている社員の部署を振替先部署に変換する
            $result_upd = $this->frmJinkenhiInfoCreate->updJinkenhiBushoFurikaeSQL($dtpYM);
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }
            //人件費部署変換マスタの変換前部署コードに登録されている部署コードを変換後部署コードに変換する
            $result_upd = $this->frmJinkenhiInfoCreate->updJinkenhiBushoHenkanSQL($dtpYM);
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }
            //出向社員の人員カウントを0にする
            $result_upd = $this->frmJinkenhiInfoCreate->updJinkenhiShukkoSQL($dtpYM);
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }
            //人件費振替比率入力で入力した値を給与割して
            //派遣社員、メーカー出向、役員以外に賞与分として人件費データに登録する
            $updShoyo = $this->updShoyo($dtpYM);
            if (!$updShoyo['result']) {
                throw new \Exception($updShoyo['error']);
            }
            //コミット
            $this->frmJinkenhiInfoCreate->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($tranStartFlg) {
                $this->frmJinkenhiInfoCreate->Do_rollback();
            }
        }

        $this->fncReturn($result);
    }

    //対象年月データ存在チェック
    public function checkDB()
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        try {
            if (!isset($_POST['data']['dtpYM'])) {
                throw new \Exception("param error");
            }
            $dtpYM = $_POST['data']['dtpYM'];
            $this->frmJinkenhiInfoCreate = new FrmJinkenhiInfoCreate();
            //処理年月取得
            $result_shoriYM = $this->frmJinkenhiInfoCreate->selShoriYMSQL();
            if (!$result_shoriYM['result']) {
                throw new \Exception($result_shoriYM['data']);
            }
            if ($result_shoriYM['row'] == 0) {
                throw new \Exception("コントロールマスタが存在しません！");
            }
            $shoriYM = $result_shoriYM['data'][0]["SYORI_YM"];
            //対象年月チェック
            if (strtotime($dtpYM) < strtotime($shoriYM)) {
                throw new \Exception("selShoriYM");
            }
            //支給データ取得
            $selShikyuData = $this->frmJinkenhiInfoCreate->selShikyuDataSQL($dtpYM);
            if (!$selShikyuData['result']) {
                throw new \Exception($selShikyuData['data']);
            }
            if ($selShikyuData['data'][0]["CNTYM"] == 0) {
                throw new \Exception("selShikyuData");
            }
            //事業主データ取得
            $selJigyonushiData = $this->frmJinkenhiInfoCreate->selJigyonushiDataSQL($dtpYM);
            if (!$selJigyonushiData['result']) {
                throw new \Exception($selJigyonushiData['data']);
            }
            if ($selJigyonushiData['data'][0]["CNTYM"] == 0) {
                throw new \Exception("selJigyonushiData");
            }
            //人件費振替比率データ取得
            $selFurikaehiritsuData = $this->frmJinkenhiInfoCreate->selFurikaehiritsuDataSQL($dtpYM);
            if (!$selFurikaehiritsuData['result']) {
                throw new \Exception($selFurikaehiritsuData['data']);
            }
            if ($selFurikaehiritsuData['data'][0]["CNTYM"] == 0) {
                throw new \Exception("selFurikaehiritsuData");
            }
            //人件費データ取得
            $selJinkenhiData = $this->frmJinkenhiInfoCreate->selJinkenhiDataSQL($dtpYM);
            if (!$selJinkenhiData['result']) {
                throw new \Exception($selJinkenhiData['data']);
            }
            if ($selJinkenhiData['data'][0]["CNTYM"] > 0) {
                throw new \Exception("QaShow");
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //賞与登録
    function updShoyo($taishoYM)
    {
        $result = array(
            'result' => FALSE,
            'error' => ""
        );
        try {
            //******************************************
            //* 正社員の賞与見積り、賞与時社会保険料を按分する *
            //******************************************
            //賞与見積、賞与時社会保険料用の基本給合計を求める
            $result_sel = $this->frmJinkenhiInfoCreate->selSumKihonkyuSQL($taishoYM, "0");
            if (!$result_sel['result']) {
                throw new \Exception($result_sel['data']);
            }
            $sumKihonkyu = $result_sel['data'][0]["SUMKHK"];
            if ($sumKihonkyu == 0) {
                throw new \Exception("基本給合計が0のため、基本給割りすることが出来ません。");
            }
            //人件費振替比率入力で入力した賞与見積り、賞与時社会保険料を按分する
            $result_upd = $this->frmJinkenhiInfoCreate->updShoyoMitsumori_ShahoSQL($taishoYM, $sumKihonkyu);
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }
            //******************************************
            //* 契約社員の賞与見積り、賞与時社会保険料を按分する *
            //******************************************
            //賞与見積、賞与時社会保険料用の基本給合計を求める
            $result_sel = $this->frmJinkenhiInfoCreate->selSumKihonkyuSQL($taishoYM, "3");
            if (!$result_sel['result']) {
                throw new \Exception($result_sel['data']);
            }
            $sumKihonkyu = $result_sel['data'][0]["SUMKHK"];
            if ($sumKihonkyu == 0) {
                $result_sel = $this->frmJinkenhiInfoCreate->selFurikaehiritsuChkSQL($taishoYM);
                if (!$result_sel['result']) {
                    throw new \Exception($result_sel['data']);
                }
                if ($result_sel["data"][0]["KYK_BNS_CHK"] > 0) {
                    throw new \Exception("契約社員の基本給合計が0のため、契約社員の賞与見積、賞与時社会保険料を基本給割りすることが出来ません");
                }
            } else {
                //人件費振替比率入力で入力した賞与見積り、賞与時社会保険料を按分する
                $result_upd = $this->frmJinkenhiInfoCreate->updKYKShoyoMitsumori_ShahoSQL($taishoYM, $sumKihonkyu);
                if (!$result_upd['result']) {
                    throw new \Exception($result_upd['data']);
                }
            }

            //************************
            //* 雇用保険料を按分する *
            //************************

            //雇用保険料用の基本給合計を求める
            $result_sel = $this->frmJinkenhiInfoCreate->selSumKihonkyuSQL($taishoYM, "1");
            if (!$result_sel['result']) {
                throw new \Exception($result_sel['data']);
            }
            $sumKihonkyu = $result_sel['data'][0]["SUMKHK"];
            //人件費振替比率入力で入力した雇用保険料を按分する
            $result_upd = $this->frmJinkenhiInfoCreate->updKoyoHokenSQL($taishoYM, $sumKihonkyu);
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }

            //************************
            //* 労災保険料を按分する *
            //************************

            //労災保険料用の基本給合計を求める
            $result_sel = $this->frmJinkenhiInfoCreate->selSumKihonkyuSQL($taishoYM, "2");
            if (!$result_sel['result']) {
                throw new \Exception($result_sel['data']);
            }
            $sumKihonkyu = $result_sel['data'][0]["SUMKHK"];
            //人件費振替比率入力で入力した労災保険料を按分する
            $result_upd = $this->frmJinkenhiInfoCreate->updRosaiHokenSQL($taishoYM, $sumKihonkyu);
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }

            //**********************
            //* 退職手当を按分する *
            //**********************

            //退職給付の基本給合計を求める
            $result_sel = $this->frmJinkenhiInfoCreate->selSumKihonkyuTaishokuSQL($taishoYM);
            if (!$result_sel['result']) {
                throw new \Exception($result_sel['data']);
            }
            $sumKihonkyu = $result_sel['data'][0]["KIHONKYU"];
            $result_upd = $this->frmJinkenhiInfoCreate->updTaishokuteateSQL($taishoYM, $sumKihonkyu);
            if (!$result_upd['result']) {
                throw new \Exception($result_upd['data']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //20240307 caina ins s
    //Excel出力ボタンクリック
    public function btnDownloadClick()
    {
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data']['dtpYM'])) {
                throw new \Exception("param error");
            }
            $tableDatapYM = $_POST['data']['dtpYM'];

            $this->frmJinkenhiInfoCreate = new FrmJinkenhiInfoCreate();

            $tableData = $this->frmJinkenhiInfoCreate->GetJKJINdataSQL($tableDatapYM);
            if (!$tableData['result']) {
                throw new \Exception($tableData['data']);
            }
            if ($tableData['row'] == 0) {
                //該当データはありません。
                $result["data"] = 'W0024';
                throw new \Exception($result["data"]);
            }
            $makeExcel = $this->MakeExcel($tableData);
            if ($makeExcel['result'] == false) {
                throw new \Exception($makeExcel['error']);
            }
            $res = $makeExcel;

            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

    public function MakeExcel($tableData)
    {
        $res = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            //出力先パス
            $tmpPath1 = dirname(dirname(dirname(__FILE__)));
            $tmpPath2 = "webroot/files/JKSYS/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
            $strTemplatePath1 = $this->ClsComFncJKSYS->FncGetPath("JksysExcelLayoutPath");

            //フォルダーが存在するかどうかのﾁｪｯｸ
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath1 . "FrmJinkenhiInfoCreateTemplate.xlsx";
            if (!file_exists($strTemplatePath)) {
                throw new \Exception('W9999');
            }
            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            } else {
                $outFloder = dirname($tmpPath);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }

            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = IOFactory::createReader('Xlsx');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            //右揃え
            $objActSheet->getStyle('Y')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $objActSheet->getStyle('AB')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            //左に位置する
            $colSum = count($tableData['data']) + 2;
            $objActSheet->getStyle('A3:E' . $colSum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
            $objActSheet->getStyle('Z')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
            $objActSheet->getStyle('AC')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
            //セルの日付書式設定
            $objActSheet->getStyle('Y')->getNumberFormat()->setFormatCode('yyyy/MM/dd HH:mm:ss');
            $objActSheet->getStyle('AB')->getNumberFormat()->setFormatCode('yyyy/MM/dd HH:mm:ss');

            for ($i = 0; $i < count($tableData['data']); $i++) {
                $objActSheet->setCellValueExplicit('A' . ($i + 3), $tableData['data'][$i]['TAISYOU_YM'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('B' . ($i + 3), $tableData['data'][$i]['SYAIN_NO'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('C' . ($i + 3), $tableData['data'][$i]['BUSYO_CD'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('D' . ($i + 3), $tableData['data'][$i]['KOYOU_KB'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('E' . ($i + 3), $tableData['data'][$i]['SYOKUSYU_CD'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValue('F' . ($i + 3), $tableData['data'][$i]['KIHONKYU']);
                $objActSheet->setCellValue('G' . ($i + 3), $tableData['data'][$i]['TEIJIKAN_GESSYU']);
                $objActSheet->setCellValue('H' . ($i + 3), $tableData['data'][$i]['ZANGYOU_TEATE']);
                $objActSheet->setCellValue('I' . ($i + 3), $tableData['data'][$i]['GYOUSEKI_SYOUREI']);
                $objActSheet->setCellValue('J' . ($i + 3), $tableData['data'][$i]['HOKA_GSK_SYOUREI']);
                $objActSheet->setCellValue('K' . ($i + 3), $tableData['data'][$i]['SONOTA_TEATE']);
                $objActSheet->setCellValue('L' . ($i + 3), $tableData['data'][$i]['KENKO_HKN_RYO']);
                $objActSheet->setCellValue('M' . ($i + 3), $tableData['data'][$i]['KAIGO_HKN_RYO']);
                $objActSheet->setCellValue('N' . ($i + 3), $tableData['data'][$i]['KOUSEINENKIN']);
                $objActSheet->setCellValue('O' . ($i + 3), $tableData['data'][$i]['KOYOU_HKN_RYO']);
                $objActSheet->setCellValue('P' . ($i + 3), $tableData['data'][$i]['ROUSAI_HKN_RYO']);
                $objActSheet->setCellValue('Q' . ($i + 3), $tableData['data'][$i]['JIDOUTEATE']);
                $objActSheet->setCellValue('R' . ($i + 3), $tableData['data'][$i]['TAISYOKU_KYUFU']);
                $objActSheet->setCellValue('S' . ($i + 3), $tableData['data'][$i]['BNS_MITUMORI']);
                $objActSheet->setCellValue('T' . ($i + 3), $tableData['data'][$i]['BNS_KENKO_HKN_RYO']);
                $objActSheet->setCellValue('U' . ($i + 3), $tableData['data'][$i]['BNS_KAIGO_HKN_RYO']);
                $objActSheet->setCellValue('V' . ($i + 3), $tableData['data'][$i]['BNS_KOUSEI_NENKIN']);
                $objActSheet->setCellValue('W' . ($i + 3), $tableData['data'][$i]['BNS_JIDOU_TEATE']);
                $objActSheet->setCellValue('X' . ($i + 3), $tableData['data'][$i]['JININ_CNT']);
                if ($tableData['data'][$i]['CREATE_DATE'] != '' && $tableData['data'][$i]['CREATE_DATE'] != null) {
                    $objActSheet->setCellValue('Y' . ($i + 3), date('Y/m/d H:i:s', strtotime($tableData['data'][$i]['CREATE_DATE'])));
                }
                $objActSheet->setCellValueExplicit('Z' . ($i + 3), $tableData['data'][$i]['CRE_SYA_CD'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValue('AA' . ($i + 3), $tableData['data'][$i]['CRE_PRG_ID']);
                if ($tableData['data'][$i]['UPD_DATE'] != '' && $tableData['data'][$i]['UPD_DATE'] != null) {
                    $objActSheet->setCellValue('AB' . ($i + 3), date('Y/m/d H:i:s', strtotime($tableData['data'][$i]['UPD_DATE'])));
                }
                $objActSheet->setCellValue('AC' . ($i + 3), $tableData['data'][$i]['UPD_SYA_CD']);
                $objActSheet->setCellValueExplicit('AD' . ($i + 3), $tableData['data'][$i]['UPD_PRG_ID'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $objActSheet->setCellValue('AE' . ($i + 3), $tableData['data'][$i]['UPD_CLT_NM']);
            }

            $filename = "人件費データ.xlsx";
            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
            $objWriter->save($tmpPath . $filename);
            $objPHPExcel->disconnectWorksheets();
            unset($objReader, $objPHPExcel);
            $res['result'] = TRUE;
            $res['data']['url'] = "files/JKSYS/" . $filename;

        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    //ファイルのアップロード
    public function fncCheckFile()
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );
        try {
            $strPath = dirname(dirname(dirname(__FILE__)));
            //            $pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
//            $pathUpLoad = '/var/www/html/gdmz/cake/webroot/files/JKSYS/upload/';
            $pathUpLoad = $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');

            if (!file_exists($pathUpLoad)) {
                if (!mkdir($pathUpLoad, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。" . $pathUpLoad);
                }
                chmod($pathUpLoad, 0777);
            }
            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $file_name = $_FILES["file"]["name"];
                $this->uploadfile = $pathUpLoad . $file_name;
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
                    $result['data'] = $this->uploadfile;
                } else {
                    $result['result'] = FALSE;
                    $result['data'] = 'ファイルのアップロードに失敗しました。';
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            //            $result['data'] = $e->getMessage();
            $result['data'] = $pathUpLoad . $file_name;

        }
        //POST方式的request，直接echo.
        // echo json_encode($result);
        $this->fncCheckFileReturn($result);
    }

    public function btnActionClick()
    {
        $blnTranFlg = FALSE;
        //トランザクションflg
        $result = array(
            'result' => FALSE,
            'error' => '',
            'data' => ''
        );
        $this->frmJinkenhiInfoCreate = new FrmJinkenhiInfoCreate();
        try {
            $filename = $_POST['data']['filename'];
            $strPath = dirname(dirname(dirname(__FILE__)));

            //            $pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
//            $pathUpLoad = '/var/www/html/gdmz/cake/webroot/files/JKSYS/upload/';
            $pathUpLoad = $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');

            $strTemplatePath = $pathUpLoad . $filename;
            //トランザクション開始
            $this->frmJinkenhiInfoCreate->Do_transaction();
            $blnTranFlg = TRUE;
            //Excel取込処理
            $objReader = new Xlsx();
            $objPHPExcel = $objReader->load($strTemplatePath);

            //excelファイルの最初のシートを読み込む
            $sheet = $objPHPExcel->getSheet(0);
            //最大のカラム番号を取得
            $allColumn = $sheet->getHighestColumn(2);
            if ($allColumn !== 'AE') {
                throw new \Exception('EXCELの中身の項目数に過不足があります。');
            }
            //最大の行番号を取得
            $allRow = $sheet->getHighestRow();

            $sumrow = 3;
            $row = 0;
            for ($a = 0; $a <= $allRow; $a++) {
                $rowdata = array();

                $TAISYOU_YM = $objPHPExcel->getActiveSheet()->getCell("A" . $sumrow)->getValue();
                $SYAIN_NO = $objPHPExcel->getActiveSheet()->getCell("B" . $sumrow)->getValue();
                $BUSYO_CD = $objPHPExcel->getActiveSheet()->getCell("C" . $sumrow)->getValue();
                $KOYOU_KB = $objPHPExcel->getActiveSheet()->getCell("D" . $sumrow)->getValue();
                $SYOKUSYU_CD = $objPHPExcel->getActiveSheet()->getCell("E" . $sumrow)->getValue();
                $KIHONKYU = $objPHPExcel->getActiveSheet()->getCell("F" . $sumrow)->getValue();
                $TEIJIKAN_GESSYU = $objPHPExcel->getActiveSheet()->getCell("G" . $sumrow)->getValue();
                $ZANGYOU_TEATE = $objPHPExcel->getActiveSheet()->getCell("H" . $sumrow)->getValue();
                $GYOUSEKI_SYOUREI = $objPHPExcel->getActiveSheet()->getCell("I" . $sumrow)->getValue();
                $HOKA_GSK_SYOUREI = $objPHPExcel->getActiveSheet()->getCell("J" . $sumrow)->getValue();
                $SONOTA_TEATE = $objPHPExcel->getActiveSheet()->getCell("K" . $sumrow)->getValue();
                $KENKO_HKN_RYO = $objPHPExcel->getActiveSheet()->getCell("L" . $sumrow)->getValue();
                $KAIGO_HKN_RYO = $objPHPExcel->getActiveSheet()->getCell("M" . $sumrow)->getValue();
                $KOUSEINENKIN = $objPHPExcel->getActiveSheet()->getCell("N" . $sumrow)->getValue();
                $KOYOU_HKN_RYO = $objPHPExcel->getActiveSheet()->getCell("O" . $sumrow)->getValue();
                $ROUSAI_HKN_RYO = $objPHPExcel->getActiveSheet()->getCell("P" . $sumrow)->getValue();
                $JIDOUTEATE = $objPHPExcel->getActiveSheet()->getCell("Q" . $sumrow)->getValue();
                $TAISYOKU_KYUFU = $objPHPExcel->getActiveSheet()->getCell("R" . $sumrow)->getValue();
                $BNS_MITUMORI = $objPHPExcel->getActiveSheet()->getCell("S" . $sumrow)->getValue();
                $BNS_KENKO_HKN_RYO = $objPHPExcel->getActiveSheet()->getCell("T" . $sumrow)->getValue();
                $BNS_KAIGO_HKN_RYO = $objPHPExcel->getActiveSheet()->getCell("U" . $sumrow)->getValue();
                $BNS_KOUSEI_NENKIN = $objPHPExcel->getActiveSheet()->getCell("V" . $sumrow)->getValue();
                $BNS_JIDOU_TEATE = $objPHPExcel->getActiveSheet()->getCell("W" . $sumrow)->getValue();
                $JININ_CNT = $objPHPExcel->getActiveSheet()->getCell("X" . $sumrow)->getValue();
                $CREATE_DATE = $objPHPExcel->getActiveSheet()->getCell("Y" . $sumrow)->getValue();
                $CRE_SYA_CD = $objPHPExcel->getActiveSheet()->getCell("Z" . $sumrow)->getValue();
                $CRE_PRG_ID = $objPHPExcel->getActiveSheet()->getCell("AA" . $sumrow)->getValue();
                $UPD_DATE = $objPHPExcel->getActiveSheet()->getCell("AB" . $sumrow)->getValue();
                $UPD_SYA_CD = $objPHPExcel->getActiveSheet()->getCell("AC" . $sumrow)->getValue();
                $UPD_PRG_ID = $objPHPExcel->getActiveSheet()->getCell("AD" . $sumrow)->getValue();
                $UPD_CLT_NM = $objPHPExcel->getActiveSheet()->getCell("AE" . $sumrow)->getValue();

                if ($TAISYOU_YM != '' && $SYAIN_NO != '') {
                    $rowdata["TAISYOU_YM"] = $TAISYOU_YM;
                    $rowdata["SYAIN_NO"] = $SYAIN_NO;
                    $rowdata["BUSYO_CD"] = $BUSYO_CD;
                    $rowdata["KOYOU_KB"] = $KOYOU_KB;
                    $rowdata["SYOKUSYU_CD"] = $SYOKUSYU_CD;
                    $rowdata["KIHONKYU"] = $KIHONKYU;
                    $rowdata["TEIJIKAN_GESSYU"] = $TEIJIKAN_GESSYU;
                    $rowdata["ZANGYOU_TEATE"] = $ZANGYOU_TEATE;
                    $rowdata["GYOUSEKI_SYOUREI"] = $GYOUSEKI_SYOUREI;
                    $rowdata["HOKA_GSK_SYOUREI"] = $HOKA_GSK_SYOUREI;
                    $rowdata["SONOTA_TEATE"] = $SONOTA_TEATE;
                    $rowdata["KENKO_HKN_RYO"] = $KENKO_HKN_RYO;
                    $rowdata["KAIGO_HKN_RYO"] = $KAIGO_HKN_RYO;
                    $rowdata["KOUSEINENKIN"] = $KOUSEINENKIN;
                    $rowdata["KOYOU_HKN_RYO"] = $KOYOU_HKN_RYO;
                    $rowdata["ROUSAI_HKN_RYO"] = $ROUSAI_HKN_RYO;
                    $rowdata["JIDOUTEATE"] = $JIDOUTEATE;
                    $rowdata["TAISYOKU_KYUFU"] = $TAISYOKU_KYUFU;
                    $rowdata["BNS_MITUMORI"] = $BNS_MITUMORI;
                    $rowdata["BNS_KENKO_HKN_RYO"] = $BNS_KENKO_HKN_RYO;
                    $rowdata["BNS_KAIGO_HKN_RYO"] = $BNS_KAIGO_HKN_RYO;
                    $rowdata["BNS_KOUSEI_NENKIN"] = $BNS_KOUSEI_NENKIN;
                    $rowdata["BNS_JIDOU_TEATE"] = $BNS_JIDOU_TEATE;
                    $rowdata["JININ_CNT"] = $JININ_CNT;
                    if ($CREATE_DATE != '' && $CREATE_DATE != null && is_numeric($CREATE_DATE)) {
                        $unix_time = gmdate("Y-m-d", Date::excelToTimestamp((int) $CREATE_DATE));
                        $CREATE_DATE_LAST = gmdate('Y-m-d H:i:s', $unix_time);
                        $rowdata["CREATE_DATE"] = $CREATE_DATE_LAST;
                    } else {
                        $rowdata["CREATE_DATE"] = $CREATE_DATE;
                    }
                    $rowdata["CRE_SYA_CD"] = $CRE_SYA_CD;
                    $rowdata["CRE_PRG_ID"] = $CRE_PRG_ID;
                    if ($UPD_DATE != '' && $UPD_DATE != null && is_numeric($UPD_DATE)) {
                        $unix_time = gmdate("Y-m-d", Date::excelToTimestamp((int) $UPD_DATE));
                        $UPD_DATE_LAST = gmdate('Y-m-d H:i:s', $unix_time);
                        $rowdata["UPD_DATE"] = $UPD_DATE_LAST;
                    } else {
                        $rowdata["UPD_DATE"] = $UPD_DATE;
                    }
                    $rowdata["UPD_SYA_CD"] = $UPD_SYA_CD;
                    $rowdata["UPD_PRG_ID"] = $UPD_PRG_ID;
                    $rowdata["UPD_CLT_NM"] = $UPD_CLT_NM;

                    $resultexis = $this->frmJinkenhiInfoCreate->existData($rowdata);
                    if (!$resultexis['result']) {
                        throw new \Exception($resultexis['data']);
                    }
                    if ($resultexis['row'] > 0) {
                        $result = $this->frmJinkenhiInfoCreate->updateData($rowdata);
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        } else {
                            $row++;
                        }
                    }
                }
                $sumrow++;
            }

            if (isset($strTemplatePath) && file_exists($strTemplatePath)) {
                unlink($strTemplatePath);
            }
            //トランザクション終了
            $this->frmJinkenhiInfoCreate->Do_commit();
            $blnTranFlg = FALSE;
            $result['result'] = TRUE;
            $result['row'] = $row;
            $result['data'] = "";
        } catch (\Exception $e) {
            if ($blnTranFlg == TRUE) {
                $this->frmJinkenhiInfoCreate->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        //ファイル削除
        $UpLoadfilepath = $pathUpLoad . $filename;
        if (isset($UpLoadfilepath) && file_exists($UpLoadfilepath)) {
            @unlink($UpLoadfilepath);
        }

        $this->fncReturn($result);
    }
    //20240307 caina ins e
}
