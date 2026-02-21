<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240328    		業務において DM→DBに文言が変わりましたので変更おねがいします             	   caina
 * 20240805       システムから出力するCSVファイルの文字コードをSJIS で出力するように修正お願いします  caina
 * 20250127       表記変更DB→DM                                                                        lujunxia
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE070TotalKHonbu;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
//*******************************************
// * sample controller
//*******************************************
class HMTVE070TotalKHonbuController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
        $this->loadComponent('ClsLogControl');
    }
    //  ***********************************************************************
    //  '処 理 名：初期表示
    //  '関 数 名：index
    //  '引    数：無し
    //  '戻 り 値 ：無し
    //  '処理説明 ：
    //  '**********************************************************************
    public function index()
    {
        $this->render('index', 'HMTVE070TotalKHonbu_layout');
    }

    //  ***********************************************************************
    //  '処 理 名：ページロード
    //  '関 数 名：Page_Load
    //  '引    数：無し
    //  '戻 り 値 ：無し
    //  '処理説明 ：ページ初期化
    //  '**********************************************************************

    public function pageload()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            } else {
                throw new \Exception('params error');
            }
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();

            $result = $HMTVE070TotalKHonbu->SQL24($postData);
            if ($result['result'] == false) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }
        $this->fncReturn($result);
    }


    // ***********************************************************************
    // '処 理 名：確報集計テーブル表示
    // '関 数 名：btnView_Click
    // '引    数：無し
    // '戻 り 値 ：無し
    // '処理説明 ：
    // '**********************************************************************

    public function btnViewClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            } else {
                throw new \Exception('params error');
            }
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();
            //存在チェック
            $resCheck = $HMTVE070TotalKHonbu->SQL($postData);
            if ($resCheck['result'] == false) {
                throw new \Exception($resCheck['data']);
            }
            if (count((array) $resCheck['data']) == 0) {
                throw new \Exception('MSG_W0003');
            }

            // 車種データを取得する
            $resSyasyu = $HMTVE070TotalKHonbu->SQL4();
            if ($resSyasyu['result'] == false) {
                throw new \Exception($resSyasyu['data']);
            }

            // 店舗テーブル生成
            $resShop = $HMTVE070TotalKHonbu->SQL1($postData);
            if ($resShop['result'] == false) {
                throw new \Exception($resShop['data']);
            }

            // 確報集計明細テーブル生成
            $resSumDetail = $HMTVE070TotalKHonbu->SQL2($postData);
            if ($resSumDetail['result'] == false) {
                throw new \Exception($resSumDetail['data']);
            }

            // 成約車種内訳明細テーブルの生成
            $resCarType = $HMTVE070TotalKHonbu->SQL5($postData);
            if ($resCarType['result'] == false) {
                throw new \Exception($resCarType['data']);
            }

            // 確報集計テーブル合計生成
            $resSum = $HMTVE070TotalKHonbu->SQL3($postData);
            if ($resSum['result'] == false) {
                throw new \Exception($resSum['data']);
            }

            // 合計_成約車種内訳テーブル2の生成
            $resSumCarType = $HMTVE070TotalKHonbu->SQL6($postData);
            if ($resSumCarType['result'] == false) {
                throw new \Exception($resSumCarType['data']);
            }
            $resShopData = $resShop['data'];
            $resSumDetailData = $resSumDetail['data'];
            $resCarTypeData = $resCarType['data'];
            $resSumData = $resSum['data'];
            $resSumCarTypeData = $resSumCarType['data'];
            $busyoArr = array();
            foreach ((array) $resShopData as $value) {
                $busyoArr[$value['BUSYO_CD']] = $value;
            }

            foreach ((array) $resSumDetailData as $value) {
                if (!isset($busyoArr[$value['BUSYO_CD']]) || !is_array($busyoArr[$value['BUSYO_CD']])) {
                    $busyoArr[$value['BUSYO_CD']] = [];
                }
                $busyoArr[$value['BUSYO_CD']] = array_merge($busyoArr[$value['BUSYO_CD']], $value);
            }

            foreach ((array) $resCarTypeData as $value) {
                if (!isset($busyoArr[$value['BUSYO_CD']])) {
                    $busyoArr[$value['BUSYO_CD']] = [];
                }
                $busyoArr[$value['BUSYO_CD']][$value['SYASYU_CD']] = $value['SEIYAKU_DAISU_D'];
            }
            $busyoData = array();
            foreach ($busyoArr as $value) {
                array_push($busyoData, $value);
            }
            $sumData = array();
            if (count((array) $resSumData) > 0) {
                $sumData = $resSumData[0];
            }
            foreach ((array) $resSumCarTypeData as $value) {
                $sumData[$value['SYASYU_CD']] = $value['SEIYAKU_DAISU_KEI'];
            }
            $result['data']['detail'] = $busyoData;
            $result['data']['sum'] = $sumData;
            $result['data']['syasyu'] = $resSyasyu['data'];
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }
        $this->fncReturn($result);
    }


    //    ***********************************************************************
    //    '処 理 名：Excel出力ボタン
    //    '関 数 名：btnExcelOut_Click
    //    '引    数：無し
    //    '戻 り 値 ：無し
    //    '処理説明 ：確報集計(本部用)Excel出力
    //    '**********************************************************************

    public function btnExcelOutClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            } else {
                throw new \Exception('params error');
            }
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();
            // データ存在のチェック
            $objdrShop1 = $HMTVE070TotalKHonbu->SQL($postData);
            if ($objdrShop1['result'] == false) {
                throw new \Exception($objdrShop1['data']);
            }
            if (count((array) $objdrShop1['data']) == 0) {
                throw new \Exception('MSG_W0003');
            }

            // 取得DataTable1
            $dt1 = $this->createExcelDataTable1($postData);
            if ($dt1['result'] == false) {
                throw new \Exception($dt1['error']);
            }
            if (count((array) $dt1['data']) == 0) {
                throw new \Exception('MSG_W0003');
            }

            // 取得DataTable2
            $dt2 = $this->createExcelDataTable2($postData);
            if ($dt2['result'] == false) {
                throw new \Exception($dt2['error']);
            }
            if (count((array) $dt2['data']) == 0) {
                throw new \Exception('MSG_W0003');
            }
            $resExcel = $this->createExcelData($dt1['data'], $dt2['data'], $postData);
            if ($resExcel['result'] == false) {
                throw new \Exception($resExcel['error']);
            }
            $result = $resExcel;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }


    //   ***********************************************************************
    //   '処 理 名：確報集計(本部用)Excel出力1
    //   '関 数 名：createExcelDataTable1
    //   '引    数：無し
    //   '戻 り 値 ：Excel出力DataTable(上)
    //   '処理説明 ：確報集計(本部用)Excel出力(上)
    //   '**********************************************************************

    public function createExcelDataTable1($postData)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();

            $objdrShopShop = $HMTVE070TotalKHonbu->SQL1($postData);
            if ($objdrShopShop['result'] == false) {
                throw new \Exception($objdrShopShop['data']);
            }
            if (count((array) $objdrShopShop['data']) == 0) {
                $result['result'] = true;
                $result['data'] = $objdrShopShop['data'];
                return $result;
            }

            $objdrShopShopDetail = $HMTVE070TotalKHonbu->SQL2($postData);
            if ($objdrShopShopDetail['result'] == false) {
                throw new \Exception($objdrShopShopDetail['data']);
            }
            if (count((array) $objdrShopShopDetail['data']) == 0) {
                $result['result'] = true;
                $result['data'] = $objdrShopShopDetail['data'];
                return $result;
            }

            $objdrShopShopSum = $HMTVE070TotalKHonbu->SQL3($postData);
            if ($objdrShopShopSum['result'] == false) {
                throw new \Exception($objdrShopShopSum['data']);
            }
            if (count((array) $objdrShopShopSum['data']) == 0) {
                $result['result'] = true;
                $result['data'] = $objdrShopShopSum['data'];
                return $result;
            }
            $objdrShopShopSum['data'][0]['BUSYO_RYKNM'] = "合計";

            array_push($objdrShopShopDetail['data'], $objdrShopShopSum['data'][0]);
            $result = $objdrShopShopDetail;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }


    //  ***********************************************************************
    //  '処 理 名：確報集計(本部用)Excel出力2
    //  '関 数 名：createExcelDataTable2
    //  '引    数：無し
    //  '戻 り 値 ：Excel出力DataTable(下)
    //  '処理説明 ：確報集計(本部用)Excel出力(下)
    //  '**********************************************************************

    public function createExcelDataTable2($postData)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();

            $objdrShop = $HMTVE070TotalKHonbu->SQL1($postData);
            if ($objdrShop['result'] == false) {
                throw new \Exception($objdrShop['data']);
            }
            if (count((array) $objdrShop['data']) == 0) {
                $result['result'] = true;
                $result['data'] = $objdrShop['data'];
                return $result;
            }

            $objdrShopDetail = $HMTVE070TotalKHonbu->SQL2($postData);
            if ($objdrShopDetail['result'] == false) {
                throw new \Exception($objdrShopDetail['data']);
            }
            if (count((array) $objdrShopDetail['data']) == 0) {
                $result['result'] = true;
                $result['data'] = $objdrShopDetail['data'];
                return $result;
            }

            $objdrShopSum = $HMTVE070TotalKHonbu->SQL3($postData);
            if ($objdrShopSum['result'] == false) {
                throw new \Exception($objdrShopSum['data']);
            }
            if (count((array) $objdrShopSum['data']) == 0) {
                $result['result'] = true;
                $result['data'] = $objdrShopSum['data'];
                return $result;
            }

            $objdrSya = $HMTVE070TotalKHonbu->SQL4();
            if ($objdrSya['result'] == false) {
                throw new \Exception($objdrSya['data']);
            }
            if (count((array) $objdrSya['data']) == 0) {
                $result['result'] = true;
                $result['data'] = $objdrSya['data'];
                return $result;
            }

            $objdrSyaDetail = $HMTVE070TotalKHonbu->SQL5($postData);
            if ($objdrSyaDetail['result'] == false) {
                throw new \Exception($objdrSyaDetail['data']);
            }
            if (count((array) $objdrSyaDetail['data']) == 0) {
                $result['result'] = true;
                $result['data'] = $objdrSyaDetail['data'];
                return $result;
            }

            $objdrSyaSum = $HMTVE070TotalKHonbu->SQL6($postData);
            if ($objdrSyaSum['result'] == false) {
                throw new \Exception($objdrSyaSum['data']);
            }
            if (count((array) $objdrSyaSum['data']) == 0) {
                $result['result'] = true;
                $result['data'] = $objdrSyaSum['data'];
                return $result;
            }

            $busyoArr = array();
            foreach ((array) $objdrShopDetail['data'] as $value) {
                $value['TOTAL'] = 0;
                $busyoArr[$value['BUSYO_CD']] = $value;
            }
            foreach ((array) $objdrSyaDetail['data'] as $value) {
                $busyoArr[$value['BUSYO_CD']][$value['SYASYU_CD']] = $value['SEIYAKU_DAISU_D'];
                $busyoArr[$value['BUSYO_CD']]['TOTAL'] += $value['SEIYAKU_DAISU_D'] ? (int) $value['SEIYAKU_DAISU_D'] : 0;
            }
            $objdrShopSum['data'][0]['TOTAL'] = 0;
            foreach ((array) $objdrSyaSum['data'] as $value) {
                $objdrShopSum['data'][0]['BUSYO_RYKNM'] = '合計';
                $objdrShopSum['data'][0][$value['SYASYU_CD']] = $value['SEIYAKU_DAISU_KEI'];
                $objdrShopSum['data'][0]['TOTAL'] += $value['SEIYAKU_DAISU_KEI'] ? (int) $value['SEIYAKU_DAISU_KEI'] : 0;
            }
            $busyoData = array();
            foreach ($busyoArr as $value) {
                array_push($busyoData, $value);
            }
            array_push($busyoData, $objdrShopSum['data'][0]);
            $result['data']['data'] = $busyoData;
            $result['data']['column'] = $objdrSya;
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }


    // ***********************************************************************
    // '処 理 名：Excelデータ生成
    // '関 数 名：createExcelData
    // '引    数：無し
    // '戻 り 値 ：ファイルパス
    // '処理説明 ：Excelデータの生成を行う
    // '**********************************************************************

    public function createExcelData($dt1, $dt2, $postData)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            //出力先パス
            $strPath = dirname(dirname(dirname(__FILE__)));
            $tmpPath1 = dirname($strPath);
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;

            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            $strTemplatePath = $strPath . '/' . $strTemplatePath1 . "KAKUHOUHONBUDATA.xls";
            if (!file_exists($strTemplatePath)) {
                throw new \Exception('テンプレートファイルが存在しません。');
            }
            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && strpos($file, "確報本部データ") !== false) {
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
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Excel Error";
                    throw new \Exception($result["data"]);
                }
            }

            $fileName = $tmpPath . "確報本部データ(" . str_replace("/", "", $postData['lblExhibitTermStart']) . "～" . str_replace("/", "", $postData['lblExhibitTermEnd']) . ").xls";

            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            // 展示会開催期間出力
            $objActSheet->setCellValue('M2', '           展示会開催期間');
            $firstLineStyle = array(
                'borders' => array('bottom' => array('borderStyle' => Border::BORDER_MEDIUM), ),
                'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, ),
            );
            $objActSheet->setCellValue('P2', $postData['lblExhibitTermStart'] . '～' . $postData['lblExhibitTermEnd']);
            $objActSheet->getStyle('P2:U2')->applyFromArray($firstLineStyle);
            $objActSheet->insertNewRowBefore(9, count((array) $dt1));

            $rowNum = 8;
            foreach ($dt1 as $value) {
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $value['BUSYO_RYKNM']);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(1) . $rowNum, $value['RAIJYO_KUMI_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(2) . $rowNum, $value['RAIJYO_KUMI_AB_KOKYAKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(3) . $rowNum, $value['RAIJYO_KUMI_AB_SINTA_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(4) . $rowNum, $value['RAIJYO_KUMI_NONAB_KOKYAKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(5) . $rowNum, $value['RAIJYO_KUMI_NONAB_SINTA_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(6) . $rowNum, $value['RAIJYO_KUMI_NONAB_FREE_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(7) . $rowNum, $value['JIZEN_JYUNBI_DM_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(8) . $rowNum, $value['JIZEN_JYUNBI_DH_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(9) . $rowNum, $value['JIZEN_JYUNBI_POSTING_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(10) . $rowNum, $value['JIZEN_JYUNBI_TEL_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(11) . $rowNum, $value['JIZEN_JYUNBI_KAKUYAKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(12) . $rowNum, $value['RAIJYO_BUNSEKI_YOBIKOMI_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(14) . $rowNum, $value['RAIJYO_BUNSEKI_KAKUYAKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(15) . $rowNum, $value['RAIJYO_BUNSEKI_KOUKOKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(16) . $rowNum, $value['RAIJYO_BUNSEKI_MEDIA_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(17) . $rowNum, $value['RAIJYO_BUNSEKI_CHIRASHI_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(18) . $rowNum, $value['RAIJYO_BUNSEKI_TORIGAKARI_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(19) . $rowNum, $value['RAIJYO_BUNSEKI_SYOKAI_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(20) . $rowNum, $value['RAIJYO_BUNSEKI_WEB_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(21) . $rowNum, $value['RAIJYO_BUNSEKI_SONOTA_KEI'], DataType::TYPE_STRING);
                if (isset($value['ENQUETE_KAISYU_KEI']) && strpos($value['ENQUETE_KAISYU_KEI'], '.') !== FALSE) {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(22) . $rowNum, number_format($value['ENQUETE_KAISYU_KEI'], 1), DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(22) . $rowNum, $value['ENQUETE_KAISYU_KEI'], DataType::TYPE_STRING);
                }
                if (isset($value['ENQUETE_RITU']) && strpos($value['ENQUETE_RITU'], '.') !== FALSE) {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(23) . $rowNum, number_format($value['ENQUETE_RITU'], 1), DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(23) . $rowNum, $value['ENQUETE_RITU'], DataType::TYPE_STRING);
                }
                $objActSheet->setCellValueExplicit($this->getColumnLetter(24) . $rowNum, $value['ABHOT_KOKYAKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(25) . $rowNum, $value['ABHOT_SINTA_KEI'], DataType::TYPE_STRING);
                if (isset($value['ABHOT_RITU']) && strpos($value['ABHOT_RITU'], '.') !== FALSE) {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(26) . $rowNum, number_format($value['ABHOT_RITU'], 1), DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(26) . $rowNum, $value['ABHOT_RITU'], DataType::TYPE_STRING);
                }
                $objActSheet->setCellValueExplicit($this->getColumnLetter(27) . $rowNum, $value['ABHOT_ZAN_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(28) . $rowNum, $value['SATEI_KOKYAKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(29) . $rowNum, $value['SATEI_KOKYAKU_TA_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(30) . $rowNum, $value['SATEI_SINTA_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(31) . $rowNum, $value['SATEI_SINTA_TA_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(32) . $rowNum, $value['DEMO_KENSU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(33) . $rowNum, $value['DEMO_RITU'], DataType::TYPE_STRING);
                if (isset($value['RUNCOST_KENSU_KEI']) && strpos($value['RUNCOST_KENSU_KEI'], '.') !== FALSE) {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(34) . $rowNum, number_format($value['RUNCOST_KENSU_KEI'], 1), DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(34) . $rowNum, $value['RUNCOST_KENSU_KEI'], DataType::TYPE_STRING);
                }
                if (isset($value['SKYPLAN_KENSU_KEI']) && strpos($value['SKYPLAN_KENSU_KEI'], '.') !== FALSE) {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(35) . $rowNum, number_format($value['SKYPLAN_KENSU_KEI'], 1), DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(35) . $rowNum, $value['SKYPLAN_KENSU_KEI'], DataType::TYPE_STRING);
                }
                if (isset($value['RUNCOST_SEIYAKU_KENSU_KEI']) && strpos($value['RUNCOST_SEIYAKU_KENSU_KEI'], '.') !== FALSE) {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(36) . $rowNum, number_format($value['RUNCOST_SEIYAKU_KENSU_KEI'], 1), DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(36) . $rowNum, $value['RUNCOST_SEIYAKU_KENSU_KEI'], DataType::TYPE_STRING);
                }
                if (isset($value['SKYPLAN_KEIYAKU_KENSU_KEI']) && strpos($value['SKYPLAN_KEIYAKU_KENSU_KEI'], '.') !== FALSE) {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(37) . $rowNum, number_format($value['SKYPLAN_KEIYAKU_KENSU_KEI'], 1), DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(37) . $rowNum, $value['SKYPLAN_KEIYAKU_KENSU_KEI'], DataType::TYPE_STRING);
                }

                $rowNum++;
            }
            $firstTBTitleStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'inside' => array('borderStyle' => Border::BORDER_THIN),
                ),
            );
            $RightStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'horizontal' => array('borderStyle' => Border::BORDER_THIN),
                    'vertical' => array('borderStyle' => Border::BORDER_MEDIUM),
                ),
            );
            $NormalStyle = array('borders' => array('vertical' => array('borderStyle' => Border::BORDER_NONE), ), );
            $objActSheet->getStyle('A8:A' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('B8:B' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('C8:G' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('H8:L' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('M8:V' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('M8:N' . ($rowNum - 1))->applyFromArray($NormalStyle);
            $objActSheet->getStyle('W8:X' . ($rowNum - 1))->applyFromArray($RightStyle);
            $objActSheet->getStyle('Y8:Z' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('AA8:AB' . ($rowNum - 1))->applyFromArray($RightStyle);
            $objActSheet->getStyle('AC8:AD' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('AE8:AF' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('AG8:AH' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('AI8:AL' . ($rowNum - 1))->applyFromArray($RightStyle);

            $rowNum += 2;
            $length = 8 + count((array) $dt2['column']['data']);
            $lengthVar = (int) floor(($length + 1) / 2);
            $objActSheet->setCellValue($this->getColumnLetter($lengthVar) . $rowNum, '成約車種内訳');
            $rowNum += 1;
            $TopBottomStyle = array('borders' => array('outline' => array('borderStyle' => Border::BORDER_MEDIUM), ), );
            $TopRightBottomStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'vertical' => array('borderStyle' => Border::BORDER_THIN),
                ),
            );
            for ($i = 0; $i < count((array) $dt2['column']['data']); $i++) {
                $objActSheet->setCellValue($this->getColumnLetter(8 + $i) . $rowNum, str_replace("｜", "―", str_replace(" ", "", $this->ClsComFncHMTVE->HkToFk($dt2['column']['data'][$i]['SYASYU_NM']))));
                $objActSheet->mergeCells($this->int2Excel(8 + $i) . $rowNum . ':' . $this->int2Excel(8 + $i) . ($rowNum + 2));
                $objActSheet->getStyle($this->getColumnLetter(1) . $rowNum)->applyFromArray($TopRightBottomStyle);
            }
            $objActSheet->mergeCells($this->int2Excel($lengthVar) . ($rowNum - 1) . ':' . $this->int2Excel($lengthVar + 2) . ($rowNum - 1));
            $objActSheet->getStyle('I' . ($rowNum - 1) . ':' . $this->int2Excel(8 + $i) . ($rowNum - 1))->applyFromArray($TopBottomStyle);
            $objActSheet->getStyle('I' . $rowNum . ':' . $this->int2Excel(8 + $i) . ($rowNum + 2))->applyFromArray($TopRightBottomStyle);
            $objActSheet->setCellValue($this->getColumnLetter(8 + $i) . $rowNum, '計');
            $rowNum += 3;
            $detailstart = $rowNum;
            foreach ($dt2['data'] as $value) {
                $objActSheet->setCellValue($this->getColumnLetter(0) . $rowNum, $value['BUSYO_RYKNM']);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(1) . $rowNum, $value['SEIYAKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(2) . $rowNum, $value['SEIYAKU_AB_KOKYAKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(3) . $rowNum, $value['SEIYAKU_AB_SINTA_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(4) . $rowNum, $value['SEIYAKU_NONAB_KOKYAKU_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(5) . $rowNum, $value['SEIYAKU_NONAB_SINTA_KEI'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit($this->getColumnLetter(6) . $rowNum, $value['SEIYAKU_NONAB_FREE_KEI'], DataType::TYPE_STRING);
                if (isset($value['SOKU_RITU']) && strpos($value['SOKU_RITU'], '.') !== FALSE) {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(7) . $rowNum, number_format($value['SOKU_RITU'], 1), DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(7) . $rowNum, $value['SOKU_RITU'], DataType::TYPE_STRING);
                }
                for ($i = 0; $i < count((array) $dt2['column']['data']); $i++) {
                    $objActSheet->setCellValueExplicit($this->getColumnLetter(8 + $i) . $rowNum, $value[$dt2['column']['data'][$i]['SYASYU_CD']] == 0 ? "" : $value[$dt2['column']['data'][$i]['SYASYU_CD']], DataType::TYPE_STRING);
                }
                $objActSheet->setCellValueExplicit($this->getColumnLetter(8 + $i) . $rowNum, $value['TOTAL'] == 0 ? "" : $value['TOTAL'], DataType::TYPE_STRING);
                $rowNum += 1;
            }
            $objActSheet->getStyle('A' . $detailstart . ':B' . ($rowNum - 1))->applyFromArray($RightStyle);
            $objActSheet->getStyle('C' . $detailstart . ':G' . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);
            $objActSheet->getStyle('H' . $detailstart . ':H' . ($rowNum - 1))->applyFromArray($RightStyle);
            $objActSheet->getStyle('I' . $detailstart . ':' . $this->int2Excel(8 + $i) . ($rowNum - 1))->applyFromArray($firstTBTitleStyle);

            $objActSheet->setSelectedCell("P11:P13");

            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $file = "files/HMTVE/" . "確報本部データ(" . str_replace("/", "", $postData['lblExhibitTermStart']) . "～" . str_replace("/", "", $postData['lblExhibitTermEnd']) . ").xls";

            $result['data'] = $file;
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            if ($e->getMessage() == 'テンプレートファイルが存在しません。' || $e->getMessage() == 'フォルダのパーミッションはエラーが発生しました。') {
                $result['error'] = $e->getMessage();
            } else {
                $result['error'] = '出力処理中にエラーが発生しました。' . $e->getMessage();
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


    //    ***********************************************************************
    //    '処 理 名：CSV出力ボタンクリック
    //    '関 数 名：btnCSVOut_Click
    //    '引    数：無し
    //    '戻 り 値 ：無し
    //    '処理説明 ：CSV出力を行う
    //    '**********************************************************************

    public function btnCSVOutClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $f = null;
        $intState = 0;
        $flg = 0;
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            } else {
                throw new \Exception('params error');
            }
            $intState = 9;
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();
            // データ存在のチェック
            $objdr = $HMTVE070TotalKHonbu->SQL($postData);
            if ($objdr['result'] == false) {
                throw new \Exception($objdr['data']);
            }
            if (count((array) $objdr['data']) == 0) {
                $intState = 1;
                throw new \Exception('MSG_W0003');
            }

            //出力先パス
            $strPath = dirname(dirname(dirname(__FILE__)));
            $tmpPath1 = dirname($strPath);
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && strpos($file, "確報入力データ") !== false) {
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
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Excel Error";
                    throw new \Exception($result["data"]);
                }
            }
            $fileName = $tmpPath . "確報入力データ.csv";
            if (file_exists($fileName)) {
                unlink($fileName);
            }
            $f = fopen($fileName, "w");
            $sb = "";
            $sb1 = "";
            $sb2 = "";
            $sb3 = "";
            $sb4 = "";
            $list1 = array();
            $list2 = array();

            //20240805 caina del s
            // $sb = mb_convert_encoding($sb, 'shift-jis');
            //20240805 caina del e

            $sb .= "展示会開催日";
            $sb .= ",";
            $sb .= "部署コード";
            $sb .= ",";
            $sb .= "部署名";
            $sb .= ",";
            $sb .= "社員№";
            $sb .= ",";
            $sb .= "社員名";
            $sb .= ",";
            $sb .= "来場組数_計_計画";
            $sb .= ",";
            $sb .= "来場組数_AB_顧客_計画";
            $sb .= ",";
            $sb .= "来場組数_AB_新他_計画";
            $sb .= ",";
            $sb .= "来場組数_NON-AB_顧客_計画";
            $sb .= ",";
            $sb .= "来場組数_NON-AB_新他_計画";
            $sb .= ",";
            $sb .= "来場組数_NON-AB_新他_内フリー_計画";
            $sb .= ",";
            //20250127 lujunxia upd s
            //20240328 caina upd s
            $sb .= "展示会事前活動_DM発信数_計画";
            // $sb .= "展示会事前活動_DB発信数_計画";
            //20240328 caina upd e
            //20250127 lujunxia upd e
            $sb .= ",";
            $sb .= "展示会事前活動_DH配布数_計画";
            $sb .= ",";
            $sb .= "展示会事前活動_ポスティング_計画";
            $sb .= ",";
            $sb .= "展示会事前活動_TELコール_計画";
            $sb .= ",";
            $sb .= "展示会事前活動_来店確約数_計画";
            $sb .= ",";
            //20250127 lujunxia upd s
            //20240328 caina upd s
            $sb .= "来場分析_事前活動結果_DM／DH／ポスティング/TELコール_計画";
            // $sb .= "来場分析_事前活動結果_DB／DH／ポスティング/TELコール_計画";
            //20240328 caina upd e
            //20250127 lujunxia upd e
            $sb .= ",";
            $sb .= "来場分析_事前活動結果_内確約来店数_計画";
            $sb .= ",";
            $sb .= "来場分析_新聞_計画";
            $sb .= ",";
            $sb .= "来場分析_ラジオ・テレビ_計画";
            $sb .= ",";
            $sb .= "来場分析_折込チラシ_計画";
            $sb .= ",";
            $sb .= "来場分析_通りがかり_計画";

            $sb .= ",";
            $sb .= "来場分析_紹介_計画";
            $sb .= ",";
            $sb .= "来場分析_WEB_計画";
            $sb .= ",";
            $sb .= "来場分析_その他_計画";
            $sb .= ",";
            $sb .= "アンケート回収_計画";
            $sb .= ",";
            $sb .= "アンケート回収率_計画";
            $sb .= ",";
            $sb .= "ABホット発生_顧客_計画";
            $sb .= ",";
            $sb .= "ABホット発生_新他_計画";
            $sb .= ",";
            $sb .= "ABホット発生_発生率_計画";
            $sb .= ",";
            $sb .= "ABホット残_計画";
            $sb .= ",";

            $sb .= "査定_顧客_自銘柄_計画";
            $sb .= ",";
            $sb .= "査定_顧客_他銘柄_計画";
            $sb .= ",";
            $sb .= "査定_新他_自銘柄_計画";
            $sb .= ",";
            $sb .= "査定_新他_他銘柄_計画";
            $sb .= ",";
            $sb .= "デモ件数_計画";
            $sb .= ",";
            $sb .= "デモ率_計画";
            $sb .= ",";

            $sb .= "ランコス提案_計画";
            $sb .= ",";
            $sb .= "ＳＫＹプラン提案_計画";
            $sb .= ",";

            $sb .= "成約台数_計画";
            $sb .= ",";
            $sb .= "成約内訳_AB_顧客_計画";
            $sb .= ",";
            $sb .= "成約内訳_AB_新他_計画";
            $sb .= ",";
            $sb .= "成約内訳_NON-AB_顧客_計画";
            $sb .= ",";
            $sb .= "成約内訳_NON-AB_新他_計画";
            $sb .= ",";
            $sb .= "成約内訳_NON-AB_新他_内フリー_計画";
            $sb .= ",";
            $sb .= "成約内訳_即決率_計画";
            $sb .= ",";

            $objDs = $HMTVE070TotalKHonbu->SQL4();
            if ($objDs['result'] == false) {
                throw new \Exception($objDs['data']);
            }
            foreach ((array) $objDs['data'] as $value) {
                $sb .= $value['SYASYU_NM'] . "(成約)";
                $sb .= ",";
            }
            $sb .= "";
            $sb .= "成約台数合計";
            $sb .= ",";
            $sb .= "来場組数_計_実績";
            $sb .= ",";
            $sb .= "来場組数_AB_顧客_実績";
            $sb .= ",";
            $sb .= "来場組数_AB_新他_実績";

            $sb .= ",";
            $sb .= "来場組数_NON-AB_顧客_実績";
            $sb .= ",";
            $sb .= "来場組数_NON-AB_新他_実績";
            $sb .= ",";
            $sb .= "来場組数_NON-AB_新他_内フリー_実績";
            $sb .= ",";
            //20250127 lujunxia upd s
            //20240328 caina upd s
            $sb .= "展示会事前活動_DM発信数_実績";
            // $sb .= "展示会事前活動_DB発信数_実績";
            //20240328 caina upd e
            //20250127 lujunxia upd e
            $sb .= ",";
            $sb .= "展示会事前活動_DH配布数_実績";
            $sb .= ",";
            $sb .= "展示会事前活動_ポスティング_実績";
            $sb .= ",";
            $sb .= "展示会事前活動_TELコール_実績";
            $sb .= ",";
            $sb .= "展示会事前活動_来店確約数_実績";
            $sb .= ",";
            //20250127 lujunxia upd s
            //20240328 caina upd s
            $sb .= "来場分析_事前活動結果_DM／DH／ポスティング/TELコール_実績";
            // $sb .= "来場分析_事前活動結果_DB／DH／ポスティング/TELコール_実績";
            //20240328 caina upd e
            //20250127 lujunxia upd e
            $sb .= ",";
            $sb .= "来場分析_事前活動結果_内確約来店数_実績";

            $sb .= ",";
            $sb .= "来場分析_新聞_実績";
            $sb .= ",";
            $sb .= "来場分析_ラジオ・テレビ_実績";
            $sb .= ",";
            $sb .= "来場分析_折込チラシ_実績";
            $sb .= ",";
            $sb .= "来場分析_通りがかり_実績";
            $sb .= ",";
            $sb .= "来場分析_紹介_実績";
            $sb .= ",";
            $sb .= "来場分析_WEB_実績";
            $sb .= ",";
            $sb .= "来場分析_その他_実績";
            $sb .= ",";
            $sb .= "アンケート回収_実績";
            $sb .= ",";
            $sb .= "アンケート回収率_実績";
            $sb .= ",";
            $sb .= "ABホット発生_顧客_実績";

            $sb .= ",";
            $sb .= "ABホット発生_新他_実績";
            $sb .= ",";
            $sb .= "ABホット発生_発生率_実績";
            $sb .= ",";
            $sb .= "ABホット残_実績";
            $sb .= ",";

            $sb .= "査定_顧客_自銘柄_実績";
            $sb .= ",";
            $sb .= "査定_顧客_他銘柄_実績";
            $sb .= ",";
            $sb .= "査定_新他_自銘柄_実績";
            $sb .= ",";
            $sb .= "査定_新他_他銘柄_実績";
            $sb .= ",";
            $sb .= "デモ件数_実績";
            $sb .= ",";
            $sb .= "デモ率_実績";
            $sb .= ",";
            $sb .= "ランコス提案_実績";
            $sb .= ",";
            $sb .= "ＳＫＹプラン提案_実績";
            $sb .= ",";

            $sb .= "成約台数_実績";
            $sb .= ",";
            $sb .= "成約内訳_AB_顧客_実績";
            $sb .= ",";
            $sb .= "成約内訳_AB_新他_実績";

            $sb .= ",";
            $sb .= "成約内訳_NON-AB_顧客_実績";
            $sb .= ",";
            $sb .= "成約内訳_NON-AB_新他_実績";
            $sb .= ",";
            $sb .= "成約内訳_NON-AB_新他_内フリー_実績";
            $sb .= ",";
            $sb .= "成約内訳_即決率_実績";
            $sb .= ",";

            foreach ((array) $objDs['data'] as $value) {
                $sb .= $value['SYASYU_NM'] . "(成約)";
                $sb .= ",";
            }

            $sb .= "成約車種内訳_計_実績";
            $sb .= ",";

            foreach ((array) $objDs['data'] as $value) {
                $sb .= $value['SYASYU_NM'] . "(試乗)";
                $sb .= ",";
            }

            $sb .= "試乗車種内訳_計_実績";
            $sb .= ",";

            foreach ((array) $objDs['data'] as $value) {
                $sb .= $value['SYASYU_NM'] . "(来場)";
                $sb .= ",";
            }

            $sb .= "来場目的内訳_計_実績";
            $sb .= "\r\n";

            $objdr = $HMTVE070TotalKHonbu->SQL8($postData);
            if ($objdr['result'] == false) {
                throw new \Exception($objdr['data']);
            }
            $objdr2 = $HMTVE070TotalKHonbu->fncSyasyuKeikaku($postData);
            if ($objdr2['result'] == false) {
                throw new \Exception($objdr2['data']);
            }
            $objdr2cnt = 0;
            foreach ((array) $objdr['data'] as $value) {
                if ($this->ClsComFncHMTVE->FncNz($value['KEYCNT']) == "16") {
                    $sb1 .= $value['IVENT_DATE'];
                    $sb1 .= ",";
                    $sb1 .= $value['BUSYO_CD'];
                    $sb1 .= ",";
                    $sb1 .= $value['BUSYO_NM'];
                    $sb1 .= ",";
                    $sb1 .= $value['SYAIN_NO'];
                    $sb1 .= ",";
                    $sb1 .= $value['SYAIN_NM'];
                    $sb1 .= ",";
                } else {
                    $sb1 .= str_pad((string) $this->ClsComFncHMTVE->FncNv($value['IVENT_DATE']), 8, " ", STR_PAD_RIGHT);
                    $sb1 .= ",";
                    $sb1 .= str_pad((string) $this->ClsComFncHMTVE->FncNv($value['BUSYO_CD']), 3, " ", STR_PAD_LEFT);
                    $sb1 .= ",";
                    $sb1 .= $value["BUSYO_NM"];
                    $sb1 .= ",";
                    $sb1 .= str_pad((string) $this->ClsComFncHMTVE->FncNv($value['SYAIN_NO']), 5, " ", STR_PAD_LEFT);
                    $sb1 .= ",";
                    $sb1 .= $value["SYAIN_NM"];
                    $sb1 .= ",";
                }

                $sb1 .= $value["RAIJYO_KUMI_KEI"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_KUMI_AB_KOKYAKU"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_KUMI_AB_SINTA"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_KUMI_NONAB_KOKYAKU"];
                $sb1 .= ",";
                if ($value["RAIJYO_KUMI_NONAB_SINTA"] == "" || $value["RAIJYO_KUMI_NONAB_SINTA"] == null) {
                    $sb1 .= "0";
                } else {
                    $sb1 .= $value["RAIJYO_KUMI_NONAB_SINTA"];
                }
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_KUMI_NONAB_FREE"];
                $sb1 .= ",";
                $sb1 .= $value["JIZEN_JYUNBI_DM"];
                $sb1 .= ",";
                $sb1 .= $value["JIZEN_JYUNBI_DH"];
                $sb1 .= ",";
                $sb1 .= $value["JIZEN_JYUNBI_POSTING"];
                $sb1 .= ",";
                $sb1 .= $value["JIZEN_JYUNBI_TEL"];
                $sb1 .= ",";
                $sb1 .= $value["JIZEN_JYUNBI_KAKUYAKU"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_BUNSEKI_YOBIKOMI"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_BUNSEKI_KAKUYAKU"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_BUNSEKI_KOUKOKU"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_BUNSEKI_MEDIA"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_BUNSEKI_CHIRASHI"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_BUNSEKI_TORIGAKARI"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_BUNSEKI_SYOKAI"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_BUNSEKI_WEB"];
                $sb1 .= ",";
                $sb1 .= $value["RAIJYO_BUNSEKI_SONOTA"];
                $sb1 .= ",";
                $sb1 .= $value["ENQUETE_KAISYU"];
                $sb1 .= ",";
                $sb1 .= strstr($value["ENQUETE_RITU"], '.') !== false ? number_format($value["ENQUETE_RITU"], 2) : $value["ENQUETE_RITU"];
                $sb1 .= ",";
                $sb1 .= $value["ABHOT_KOKYAKU"];
                $sb1 .= ",";
                $sb1 .= $value["ABHOT_SINTA"];
                $sb1 .= ",";
                $sb1 .= strstr($value["ABHOT_RITU"], '.') !== false ? number_format($value["ABHOT_RITU"], 2) : $value["ABHOT_RITU"];
                $sb1 .= ",";
                $sb1 .= $value["ABHOT_ZAN"];
                $sb1 .= ",";

                $sb1 .= $value["SATEI_KOKYAKU"];
                $sb1 .= ",";
                $sb1 .= $value["SATEI_KOKYAKU_TA"];
                $sb1 .= ",";
                $sb1 .= $value["SATEI_SINTA"];
                $sb1 .= ",";
                $sb1 .= $value["SATEI_SINTA_TA"];
                $sb1 .= ",";
                $sb1 .= $value["DEMO_KENSU"];
                $sb1 .= ",";
                $sb1 .= strstr($value["DEMO_RITU"], '.') !== false ? number_format($value["DEMO_RITU"], 2) : $value["DEMO_RITU"];
                $sb1 .= ",";
                $sb1 .= $value["RUNCOST_KENSU"];
                $sb1 .= ",";

                $sb1 .= $value["SKYPLAN_KENSU"];
                $sb1 .= ",";
                $sb1 .= $value["SEIYAKU_KEI"];
                $sb1 .= ",";
                $sb1 .= $value["SEIYAKU_AB_KOKYAKU"];
                $sb1 .= ",";
                $sb1 .= $value["SEIYAKU_AB_SINTA"];
                $sb1 .= ",";
                $sb1 .= $value["SEIYAKU_NONAB_KOKYAKU"];
                $sb1 .= ",";
                $sb1 .= $value["SEIYAKU_NONAB_SINTA"];
                $sb1 .= ",";
                $sb1 .= $value["SEIYAKU_NONAB_FREE"];
                $sb1 .= ",";
                $sb1 .= strstr($value["SOKU_RITU"], '.') !== false ? number_format($value["SOKU_RITU"], 2) : $value["SOKU_RITU"];
                $sb1 .= ",";

                $intSunSya3 = 0;
                for ($objdr2cnt; $objdr2cnt < count((array) $objdr2['data']); $objdr2cnt++) {
                    if ($this->ClsComFncHMTVE->FncNv($value['IVENT_DATE']) == $this->ClsComFncHMTVE->FncNv($objdr2['data'][$objdr2cnt]['IVENT_DATE']) && $this->ClsComFncHMTVE->FncNv($value['SYAIN_NO']) == $this->ClsComFncHMTVE->FncNv($objdr2['data'][$objdr2cnt]['SYAIN_NO'])) {
                        $sb1 .= $this->ClsComFncHMTVE->FncNz($objdr2['data'][$objdr2cnt]['SEIYAKU']);
                        $sb1 .= ",";
                        $intSunSya3 += $this->ClsComFncHMTVE->FncNz($objdr2['data'][$objdr2cnt]['SEIYAKU']);
                    } else {
                        break;
                    }
                }
                $sb1 .= $intSunSya3;
                $sb1 .= ",";
                array_push($list1, $sb1);
                $sb1 = "";

            }
            $objdr = $HMTVE070TotalKHonbu->SQL9($postData);
            if ($objdr['result'] == false) {
                throw new \Exception($objdr['data']);
            }
            $objdr2 = $HMTVE070TotalKHonbu->fncSyasyuJisseki($postData);
            if ($objdr2['result'] == false) {
                throw new \Exception($objdr2['data']);
            }
            $objdr2cnt = 0;
            foreach ((array) $objdr['data'] as $value) {
                if ($this->ClsComFncHMTVE->FncNz($value['KEYCNT']) == "16") {
                    $sb2 .= $value['IVENT_DATE'];
                    $sb2 .= ",";
                    $sb2 .= $value['BUSYO_CD'];
                    $sb2 .= ",";
                    $sb2 .= $value['SYAIN_NO'];
                    $sb2 .= ",";
                } else {
                    $sb2 .= str_pad((string) $this->ClsComFncHMTVE->FncNv($value['IVENT_DATE']), 8, " ", STR_PAD_RIGHT);
                    $sb2 .= ",";
                    $sb2 .= str_pad((string) $this->ClsComFncHMTVE->FncNv($value['BUSYO_CD']), 3, " ", STR_PAD_LEFT);
                    $sb2 .= ",";
                    $sb2 .= str_pad((string) $this->ClsComFncHMTVE->FncNv($value['SYAIN_NO']), 5, " ", STR_PAD_LEFT);
                    $sb2 .= ",";
                }

                $sb2 .= $value["RAIJYO_KUMI_KEI"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_KUMI_AB_KOKYAKU"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_KUMI_AB_SINTA"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_KUMI_NONAB_KOKYAKU"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_KUMI_NONAB_SINTA"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_KUMI_NONAB_FREE"];
                $sb2 .= ",";
                $sb2 .= $value["JIZEN_JYUNBI_DM"];
                $sb2 .= ",";
                $sb2 .= $value["JIZEN_JYUNBI_DH"];
                $sb2 .= ",";
                $sb2 .= $value["JIZEN_JYUNBI_POSTING"];
                $sb2 .= ",";
                $sb2 .= $value["JIZEN_JYUNBI_TEL"];
                $sb2 .= ",";
                $sb2 .= $value["JIZEN_JYUNBI_KAKUYAKU"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_BUNSEKI_YOBIKOMI"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_BUNSEKI_KAKUYAKU"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_BUNSEKI_KOUKOKU"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_BUNSEKI_MEDIA"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_BUNSEKI_CHIRASHI"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_BUNSEKI_TORIGAKARI"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_BUNSEKI_SYOKAI"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_BUNSEKI_WEB"];
                $sb2 .= ",";
                $sb2 .= $value["RAIJYO_BUNSEKI_SONOTA"];
                $sb2 .= ",";
                $sb2 .= $value["ENQUETE_KAISYU"];
                $sb2 .= ",";
                $sb2 .= strstr($value["ENQUETE_RITU"], '.') !== false ? number_format($value["ENQUETE_RITU"], 2) : $value["ENQUETE_RITU"];
                $sb2 .= ",";
                $sb2 .= $value["ABHOT_KOKYAKU"];

                $sb2 .= ",";
                if ($value["ABHOT_SINTA"] == "" || $value["ABHOT_SINTA"] == null) {
                    $sb2 .= "0";
                } else {
                    $sb2 .= $value["ABHOT_SINTA"];
                }

                $sb2 .= ",";
                $sb2 .= strstr($value["ABHOT_RITU"], '.') !== false ? number_format($value["ABHOT_RITU"], 2) : $value["ABHOT_RITU"];
                $sb2 .= ",";
                $sb2 .= $value["ABHOT_ZAN"];
                $sb2 .= ",";
                $sb2 .= $value["SATEI_KOKYAKU"];
                $sb2 .= ",";
                $sb2 .= $value["SATEI_KOKYAKU_TA"];
                $sb2 .= ",";
                $sb2 .= $value["SATEI_SINTA"];
                $sb2 .= ",";
                $sb2 .= $value["SATEI_SINTA_TA"];
                $sb2 .= ",";
                $sb2 .= $value["DEMO_KENSU"];
                $sb2 .= ",";
                $sb2 .= strstr($value["DEMO_RITU"], '.') !== false ? number_format($value["DEMO_RITU"], 2) : $value["DEMO_RITU"];
                $sb2 .= ",";

                $sb2 .= $value["RUNCOST_SEIYAKU_KENSU"];
                $sb2 .= ",";

                $sb2 .= $value["SKYPLAN_KEIYAKU_KENSU"];
                $sb2 .= ",";

                $sb2 .= $value["SEIYAKU_KEI"];
                $sb2 .= ",";
                $sb2 .= $value["SEIYAKU_AB_KOKYAKU"];
                $sb2 .= ",";
                $sb2 .= $value["SEIYAKU_AB_SINTA"];
                $sb2 .= ",";
                $sb2 .= $value["SEIYAKU_NONAB_KOKYAKU"];
                $sb2 .= ",";
                $sb2 .= $value["SEIYAKU_NONAB_SINTA"];
                $sb2 .= ",";
                $sb2 .= $value["SEIYAKU_NONAB_FREE"];
                $sb2 .= ",";
                $sb2 .= strstr($value["SOKU_RITU"], '.') !== false ? number_format($value["SOKU_RITU"], 2) : $value["SOKU_RITU"];
                $sb2 .= ",";

                $intSumSeiyaku = 0;
                $intSumSijyo = 0;
                $intSumRaijyo = 0;
                for ($objdr2cnt; $objdr2cnt < count((array) $objdr2['data']); $objdr2cnt++) {
                    if ($this->ClsComFncHMTVE->FncNv($value['IVENT_DATE']) == $this->ClsComFncHMTVE->FncNv($objdr2['data'][$objdr2cnt]['IVENT_DATE']) && $this->ClsComFncHMTVE->FncNv($value['SYAIN_NO']) == $this->ClsComFncHMTVE->FncNv($objdr2['data'][$objdr2cnt]['SYAIN_NO'])) {
                        $sb2 .= $this->ClsComFncHMTVE->FncNz($objdr2['data'][$objdr2cnt]['SEIYAKU_DAISU_D']);
                        $sb2 .= ",";
                        $intSumSeiyaku += $this->ClsComFncHMTVE->FncNz($objdr2['data'][$objdr2cnt]['SEIYAKU_DAISU_D']);

                        $sb3 .= $this->ClsComFncHMTVE->FncNz($objdr2['data'][$objdr2cnt]['SIJYO_DAISU']);
                        $sb3 .= ",";
                        $intSumSijyo += $this->ClsComFncHMTVE->FncNz($objdr2['data'][$objdr2cnt]['SIJYO_DAISU']);

                        $sb4 .= $this->ClsComFncHMTVE->FncNz($objdr2['data'][$objdr2cnt]['RAIJYO_DAISU']);
                        $sb4 .= ",";
                        $intSumRaijyo += $this->ClsComFncHMTVE->FncNz($objdr2['data'][$objdr2cnt]['RAIJYO_DAISU']);
                    } else {
                        break;
                    }
                }
                $sb2 .= $intSumSeiyaku;
                $sb2 .= ",";

                $sb2 .= $sb3;

                $sb2 .= $intSumSijyo;
                $sb2 .= ",";

                $sb2 .= $sb4;

                $sb2 .= $intSumRaijyo;

                array_push($list2, $sb2);

                $sb2 = "";
                $sb3 = "";
                $sb4 = "";

            }

            $intPlanCnt = 0;
            $intDoCnt = 0;
            $strDamyPlan = "";
            $strDamyDo = "";
            $strKeyPlan = "";
            $strKeyDo = "";
            $strPlanAll = array();

            if (count((array) $list1) > 0) {
                for ($i = 0; $i < count((array) explode(',', $list1[0])) - 1; $i++) {
                    $strDamyPlan .= ",";
                }
            }

            if (count((array) $list2) > 0) {
                for ($i = 0; $i < count((array) explode(',', $list2[0])) - 3; $i++) {
                    $strDamyDo .= ",";
                }
            }

            while ($intPlanCnt < count((array) $list1) || $intDoCnt < count((array) $list2)) {
                if ($intPlanCnt < count((array) $list1)) {
                    $strPlanAll = explode(',', $list1[$intPlanCnt]);
                    $strKeyPlan = ($strPlanAll[0] ? $strPlanAll[0] : "") . ($strPlanAll[1] ? $strPlanAll[1] : "") . ($strPlanAll[3] ? $strPlanAll[3] : "");
                } else {
                    $strKeyPlan = "9999999999999999";
                }
                if ($intDoCnt < count((array) $list2)) {
                    $strKeyDo = substr(str_replace(",", "", $list2[$intDoCnt]), 0, 16);
                } else {
                    $strKeyDo = "9999999999999999";
                }

                if ($strKeyPlan < $strKeyDo) {
                    $sb .= $list1[$intPlanCnt];
                    $sb .= $strDamyDo;
                    $sb .= "\n";
                    $intPlanCnt += 1;
                } elseif ($strKeyPlan > $strKeyDo) {
                    $sb .= $strDamyPlan;
                    $sb .= substr($list2[$intDoCnt], 19);
                    $sb .= "\n";
                    $intDoCnt += 1;
                } else {
                    $sb .= $list1[$intPlanCnt];
                    $sb .= substr($list2[$intDoCnt], 19);
                    $sb .= "\n";
                    $intPlanCnt += 1;
                    $intDoCnt += 1;
                }
            }

            //20240805 caina ins s
            $sb = mb_convert_encoding($sb, "SJIS-win");
            //20240805 caina ins e
            fwrite($f, $sb);
            @fclose($f);

            $intState = 1;
            $flg = 1;

            $result['result'] = true;
            $result['data'] = "files/HMTVE/確報入力データ.csv";

        } catch (\Exception $e) {
            if ($flg == 0) {
                $intState = 9;
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            if ($f != null) {
                @fclose($f);
                unset($f);
            }
        }
        try {
            if ($intState != 0) {
                $lngCount = 0;
                if (isset($list2)) {
                    $lngCount = count((array) $list2);
                }
                $resultLog = $this->ClsLogControl->fncLogEntryHMTVE("HMTVE070_Total_K_Honbu", $intState, $lngCount, $_POST['data']['lblExhibitTermStart'], $_POST['data']['lblExhibitTermEnd']);
                if (!$resultLog['result']) {
                    throw new \Exception($resultLog['Msg']);
                }
            }
        } catch (\Exception $e1) {
            $result['result'] = FALSE;
            $result['error'] = $e1->getMessage();
        }
        $this->fncReturn($result);
    }


    //   ***********************************************************************
    //   '処 理 名：HITNET用Excel出力ボタンクリック
    //   '関 数 名：btnOutputHITNET_Click
    //   '引    数：無し
    //   '戻 り 値 ：無し
    //   '処理説明 ：HITNET用Excel出力を行う
    //   '**********************************************************************

    public function btnOutputHITNETClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            } else {
                throw new \Exception('params error');
            }
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();
            // データ存在のチェック
            $objdr = $HMTVE070TotalKHonbu->SQL($postData);
            if ($objdr['result'] == false) {
                throw new \Exception($objdr['data']);
            }
            if (count((array) $objdr['data']) == 0) {
                throw new \Exception('MSG_W0003');
            }

            // 確報確定データに確定ﾌﾗｸﾞ１で更新する
            $update1 = $this->update1($postData);
            if ($update1['result'] == false) {
                throw new \Exception($update1['error']);
            }

            // 速報データの出力ﾌﾗｸﾞを"1"で更新するとExceファイル生成処理
            $update2 = $this->update2($postData);
            if ($update2['result'] == false) {
                throw new \Exception($update2['error']);
            }
            $result = $update2;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }


    //  ***********************************************************************
    //  '処 理 名：確報確定データに確定ﾌﾗｸﾞ１で更新する
    //  '関 数 名：update1
    //  '引    数：無し
    //  '戻 り 値 ：なし
    //  '処理説明 ：確報確定データ更新
    //  '**********************************************************************

    public function update1($postData)
    {
        $tranStartFlg = false;
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();
            //トランザクション開始
            $HMTVE070TotalKHonbu->Do_transaction();
            $tranStartFlg = TRUE;

            $objdr = $HMTVE070TotalKHonbu->SQL10($postData);
            if ($objdr['result'] == false) {
                throw new \Exception($objdr['data']);
            }
            if (count((array) $objdr['data']) > 0) {
                $intCheck = $HMTVE070TotalKHonbu->SQL12($postData);
                if ($intCheck['result'] == false) {
                    throw new \Exception($intCheck['data']);
                }
            } else {
                $intCheck = $HMTVE070TotalKHonbu->SQL11($postData);
                if ($intCheck['result'] == false) {
                    throw new \Exception($intCheck['data']);
                }
            }
            if ($intCheck['number_of_rows'] <= 0) {
                $HMTVE070TotalKHonbu->Do_rollback();
                $result['result'] = true;
                return $result;
            } else {
                //コミット
                $HMTVE070TotalKHonbu->Do_commit();
            }
            $result['result'] = true;

        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $HMTVE070TotalKHonbu->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = 'MSG_W0006';
        }
        return $result;
    }


    // ***********************************************************************
    // '処 理 名：速報データ更新
    // '関 数 名：update2
    // '引    数：無し
    // '戻 り 値 ：なし
    // '処理説明 ：速報データの出力ﾌﾗｸﾞを"1"で更新する
    // '**********************************************************************

    public function update2($postData)
    {
        $tranStartFlg = false;
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();
            //トランザクション開始
            $HMTVE070TotalKHonbu->Do_transaction();
            $tranStartFlg = TRUE;

            $intCheck = $HMTVE070TotalKHonbu->SQL13($postData);
            if ($intCheck['result'] == false) {
                $HMTVE070TotalKHonbu->Do_rollback();
                $HMTVE070TotalKHonbu->Do_transaction();
                $res = $HMTVE070TotalKHonbu->SQL14($postData);
                if ($res['result'] == false) {
                    throw new \Exception($res['data']);
                }
                $HMTVE070TotalKHonbu->Do_commit();
                $result['result'] = true;
                $result['data'] = 'MSG_W0006';
                return $result;

            }

            //出力先パス
            $strPath = dirname(dirname(dirname(__FILE__)));
            $tmpPath1 = dirname($strPath);
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;

            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            $strTemplatePath = $strPath . '/' . $strTemplatePath1 . "HITNETKAKUHOUDATA.xls";
            if (!file_exists($strTemplatePath)) {
                throw new \Exception('テンプレートファイルが存在しません。');
            }
            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && strpos($file, "HITNET用確報データ") !== false) {
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
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Excel Error";
                    throw new \Exception($result["data"]);
                }
            }

            $fileName = $tmpPath . "HITNET用確報データ(" . str_replace("/", "", $postData['lblExhibitTermStart']) . "～" . str_replace("/", "", $postData['lblExhibitTermEnd']) . ").xls";

            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();

            // 展示会開催期間取得
            $objActSheet->setCellValue('I5', $postData['lblExhibitTermStart'] . '～' . $postData['lblExhibitTermEnd']);
            $commonStyle = array(
                'borders' => array('bottom' => array('borderStyle' => Border::BORDER_THIN), ),
                'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, ),
            );
            $objdr = $HMTVE070TotalKHonbu->SQL16($postData);
            if ($objdr['result'] == false) {
                throw new \Exception($objdr['data']);
            }
            $objActSheet->setCellValueExplicit('I6', $objdr['data'][0]['IVENT_NM'], DataType::TYPE_STRING);
            $objActSheet->getStyle('I6')->applyFromArray($commonStyle);

            $objdr18 = $HMTVE070TotalKHonbu->SQL18($postData);
            if ($objdr18['result'] == false) {
                throw new \Exception($objdr18['data']);
            }
            $objdrData = $objdr18['data'][0];
            $col = 'J';
            $objActSheet->setCellValue($col . '9', $objdrData['RAIJYO_KUMI_KEI']);
            $objActSheet->setCellValue($col . '10', $objdrData['RAIJYO_KUMI_AB_GK']);
            $objActSheet->setCellValue($col . '11', $objdrData['RAIJYO_KUMI_AB_KOKYAKU_KEI']);
            $objActSheet->setCellValue($col . '12', $objdrData['RAIJYO_KUMI_AB_SINTA_KEI']);
            $objActSheet->setCellValue($col . '13', $objdrData['RAIJYO_KUMI_NONAB_GK']);
            $objActSheet->setCellValue($col . '14', $objdrData['RAIJYO_KUMI_NONAB_KOKYAKU_KEI']);
            $objActSheet->setCellValue($col . '15', $objdrData['RAIJYO_KUMI_NONAB_SINTA_KEI'] - $objdrData['RAIJYO_KUMI_NONAB_FREE_KEI']);
            $objActSheet->setCellValue($col . '16', $objdrData['RAIJYO_KUMI_NONAB_FREE_KEI']);
            $objActSheet->setCellValue($col . '17', $objdrData['ABHOT_KEI']);
            $objActSheet->setCellValue($col . '18', $objdrData['SEIYAKU_KEI']);
            $strSQLJISSEKI01 = $HMTVE070TotalKHonbu->SQL19($postData, "1");
            if ($strSQLJISSEKI01['result'] == false) {
                throw new \Exception($strSQLJISSEKI01['data']);
            }
            $objActSheet->setCellValue($col . '19', $strSQLJISSEKI01['data'][0]['JISSEKI']);
            $strSQLJISSEKI02 = $HMTVE070TotalKHonbu->SQL19($postData, "0");
            if ($strSQLJISSEKI02['result'] == false) {
                throw new \Exception($strSQLJISSEKI02['data']);
            }
            $objActSheet->setCellValue($col . '20', $strSQLJISSEKI02['data'][0]['JISSEKI']);
            $objActSheet->setCellValue($col . '21', $objdrData['SEIYAKU_AB_KOKYAKU_KEI'] + $objdrData['SEIYAKU_AB_SINTA_KEI']);
            $objActSheet->setCellValue($col . '22', $objdrData['SEIYAKU_AB_KOKYAKU_KEI']);
            $objActSheet->setCellValue($col . '23', $objdrData['SEIYAKU_AB_SINTA_KEI']);
            $objActSheet->setCellValue($col . '24', $objdrData['SEIYAKU_NONAB_KOKYAKU_KEI'] + $objdrData['SEIYAKU_NONAB_SINTA_KEI']);
            $objActSheet->setCellValue($col . '25', $objdrData['SEIYAKU_NONAB_KOKYAKU_KEI']);
            $objActSheet->setCellValue($col . '26', $objdrData['SEIYAKU_NONAB_SINTA_KEI'] - $objdrData['SEIYAKU_NONAB_FREE_KEI']);
            $objActSheet->setCellValue($col . '27', $objdrData['SEIYAKU_NONAB_FREE_KEI']);
            $objActSheet->setCellValue($col . '28', $objdrData['ABHOT_ZAN_KEI']);
            $objActSheet->setCellValue($col . '29', $objdrData['JIZEN_JYUNBI_DM_KEI']);
            $objActSheet->setCellValue($col . '30', $objdrData['JIZEN_JYUNBI_TEL_KEI']);
            $objActSheet->setCellValue($col . '31', $objdrData['JIZEN_JYUNBI_KAKUYAKU_KEI']);
            $objActSheet->setCellValue($col . '32', $objdrData['RAIJYO_BUNSEKI_KAKUYAKU_KEI']);
            $objActSheet->setCellValue($col . '33', $objdrData['JIZEN_JYUNBI_DH_KEI']);
            $objActSheet->setCellValue($col . '35', $objdrData['ENQUETE_KAISYU_KEI']);
            $objActSheet->setCellValue($col . '36', $objdrData['SATEI_GK']);
            $objActSheet->setCellValue($col . '37', $objdrData['SATEI_KOKYAKU_KEI'] + $objdrData['SATEI_KOKYAKU_TA_KEI']);
            $objActSheet->setCellValue($col . '38', $objdrData['SATEI_KOKYAKU_KEI']);
            $objActSheet->setCellValue($col . '39', $objdrData['SATEI_KOKYAKU_TA_KEI']);
            $objActSheet->setCellValue($col . '40', $objdrData['SATEI_SINTA_KEI'] + $objdrData['SATEI_SINTA_TA_KEI']);
            $objActSheet->setCellValue($col . '41', $objdrData['SATEI_SINTA_KEI']);
            $objActSheet->setCellValue($col . '42', $objdrData['SATEI_SINTA_TA_KEI']);
            $objActSheet->setCellValue($col . '43', $objdrData['RUNCOST_KENSU_KEI']);
            $objActSheet->setCellValue($col . '44', $objdrData['SKYPLAN_KENSU_KEI']);
            $objActSheet->setCellValue($col . '45', $objdrData['RUNCOST_SEIYAKU_KENSU_KEI']);
            $objActSheet->setCellValue($col . '46', $objdrData['SKYPLAN_KEIYAKU_KENSU_KEI']);

            $currentRow = 48;
            $colsyaNm = 'C';
            $colsya = 'I';
            $objActSheet->setCellValue($colsyaNm . $currentRow, "合計");
            $objActSheet->setCellValue($colsya . $currentRow, $objdrData['DEMO_KENSU_KEI']);
            $objdr20 = $HMTVE070TotalKHonbu->SQL20($postData);
            if ($objdr20['result'] == false) {
                throw new \Exception($objdr20['data']);
            }
            foreach ((array) $objdr20['data'] as $value) {
                $currentRow++;
                $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'H' . $currentRow);
                $objActSheet->mergeCells($colsya . $currentRow . ':' . 'K' . $currentRow);
                $objActSheet->setCellValue($colsyaNm . $currentRow, $value['SYASYU_NM']);
                $objActSheet->setCellValue($colsya . $currentRow, $value['DEMO_DAISU_KEI'] ? $value['DEMO_DAISU_KEI'] : 0);
            }
            $TitleRow2Style = array(
                'borders' => array('outline' => array('borderStyle' => Border::BORDER_MEDIUM), ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::HORIZONTAL_CENTER,
                ),
            );
            $TopLeftBottomStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'inside' => array('borderStyle' => Border::BORDER_MEDIUM),
                ),
            );
            if (($currentRow - 48) > 5) {
                $objActSheet->mergeCells('B' . (int) (($currentRow - 48) / 2 + 46) . ':' . 'B' . (int) (($currentRow - 48) / 2 + 50));
                $objActSheet->setCellValue('B' . (int) (($currentRow - 48) / 2 + 46), "デモ件数");
            } else {
                $objActSheet->mergeCells('B48' . ':' . 'B' . $currentRow);
                $objActSheet->setCellValue('B48', "デモ件数");
            }
            $objActSheet->getStyle('B48:B' . $currentRow)->applyFromArray($TitleRow2Style);
            $objActSheet->getStyle('B48:B' . $currentRow)->getAlignment()->setWrapText(true);
            $objActSheet->getStyle($colsyaNm . '48:K' . $currentRow)->applyFromArray($TopLeftBottomStyle);

            $objdr21 = $HMTVE070TotalKHonbu->SQL21($postData);
            if ($objdr21['result'] == false) {
                throw new \Exception($objdr21['data']);
            }
            $currentRow = 8;
            $colsyaNm = 'P';
            $colsya = 'V';
            foreach ((array) $objdr21['data'] as $value) {
                $currentRow++;
                $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'U' . $currentRow);
                $objActSheet->mergeCells($colsya . $currentRow . ':' . 'X' . $currentRow);
                $objActSheet->setCellValue($colsyaNm . $currentRow, $value['SYASYU_NM']);
                $objActSheet->setCellValue($colsya . $currentRow, $value['SEIYAKU_DAISU_KEI'] ? $value['SEIYAKU_DAISU_KEI'] : 0);
            }
            $AllBoldStyleLeft = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'inside' => array('borderStyle' => Border::BORDER_MEDIUM),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::HORIZONTAL_CENTER,
                ),
            );
            $AllBoldStyleRight = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'inside' => array('borderStyle' => Border::BORDER_MEDIUM),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::HORIZONTAL_CENTER,
                ),
            );
            $objActSheet->getStyle($colsyaNm . '9:U' . $currentRow)->applyFromArray($AllBoldStyleLeft);
            $objActSheet->getStyle($colsya . '9:X' . $currentRow)->applyFromArray($AllBoldStyleRight);

            $currentRow += 2;
            $objActSheet->setCellValue($colsyaNm . $currentRow, "来店きっかけ");
            $currentRow += 1;
            $objActSheet->setCellValue($colsyaNm . $currentRow, "テレビ");
            $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'U' . $currentRow);
            $objActSheet->setCellValue($colsya . $currentRow, $objdrData['RAIJYO_BUNSEKI_MEDIA_KEI']);
            $objActSheet->mergeCells($colsya . $currentRow . ':' . 'X' . $currentRow);
            $currentRow += 1;
            $objActSheet->setCellValue($colsyaNm . $currentRow, "新聞広告");
            $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'U' . $currentRow);
            $objActSheet->setCellValue($colsya . $currentRow, $objdrData['RAIJYO_BUNSEKI_KOUKOKU_KEI']);
            $objActSheet->mergeCells($colsya . $currentRow . ':' . 'X' . $currentRow);
            $currentRow += 1;
            $objActSheet->setCellValue($colsyaNm . $currentRow, "チラシ");
            $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'U' . $currentRow);
            $objActSheet->setCellValue($colsya . $currentRow, $objdrData['RAIJYO_BUNSEKI_CHIRASHI_KEI']);
            $objActSheet->mergeCells($colsya . $currentRow . ':' . 'X' . $currentRow);
            $currentRow += 1;
            //20250127 lujunxia upd s
            //20240328 caina upd s
            $objActSheet->setCellValue($colsyaNm . $currentRow, "DM");
            // $objActSheet->setCellValue($colsyaNm . $currentRow, "DB");
            //20240328 caina upd e
            //20250127 lujunxia upd e
            $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'U' . $currentRow);
            $objActSheet->setCellValue($colsya . $currentRow, $objdrData['RAIJYO_BUNSEKI_YOBIKOMI_KEI']);
            $objActSheet->mergeCells($colsya . $currentRow . ':' . 'X' . $currentRow);
            $currentRow += 1;
            $objActSheet->setCellValue($colsyaNm . $currentRow, "通りがかり");
            $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'U' . $currentRow);
            $objActSheet->setCellValue($colsya . $currentRow, $objdrData['RAIJYO_BUNSEKI_TORIGAKARI_KEI']);
            $objActSheet->mergeCells($colsya . $currentRow . ':' . 'X' . $currentRow);
            $currentRow += 1;
            $objActSheet->setCellValue($colsyaNm . $currentRow, "WEB");
            $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'U' . $currentRow);
            $objActSheet->setCellValue($colsya . $currentRow, $objdrData['RAIJYO_BUNSEKI_WEB_KEI']);
            $objActSheet->mergeCells($colsya . $currentRow . ':' . 'X' . $currentRow);
            $currentRow += 1;
            $objActSheet->setCellValue($colsyaNm . $currentRow, "その他");
            $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'U' . $currentRow);
            $objActSheet->setCellValue($colsya . $currentRow, $objdrData['RAIJYO_BUNSEKI_SONOTA_KEI']);
            $objActSheet->mergeCells($colsya . $currentRow . ':' . 'X' . $currentRow);

            $objActSheet->getStyle($colsyaNm . ($currentRow - 6) . ':U' . $currentRow)->applyFromArray($AllBoldStyleLeft);
            $objActSheet->getStyle($colsya . ($currentRow - 6) . ':X' . $currentRow)->applyFromArray($AllBoldStyleRight);

            $currentRow += 2;
            $strSEIYAKU_KEI = "";
            if ($objdrData['SEIYAKU_KEI'] == 0) {
                $strSEIYAKU_KEI = "0";
            } else {
                $strSEIYAKU_KEI = (string) $objdrData['SEIYAKU_KEI'];
            }
            if ((int) $strSEIYAKU_KEI !== ((int) $strSQLJISSEKI01['data'][0]['JISSEKI'] + (int) $strSQLJISSEKI02['data'][0]['JISSEKI'])) {
                $objActSheet->setCellValue($colsyaNm . $currentRow, "ボルボ又はその他が発生しています");
                $objActSheet->mergeCells($colsyaNm . $currentRow . ':' . 'Z' . $currentRow);
                $currentRow += 1;
                $objActSheet->setCellValue($colsyaNm . $currentRow, "車両合計");
                $objActSheet->setCellValue($colsya . $currentRow, (int) $strSEIYAKU_KEI);
                $currentRow += 1;
                $objActSheet->setCellValue($colsyaNm . $currentRow, "成約合計");
                $objActSheet->setCellValue($colsya . $currentRow, (int) $strSQLJISSEKI01['data'][0]['JISSEKI'] + (int) $strSQLJISSEKI02['data'][0]['JISSEKI']);
                $currentRow += 1;
                $objActSheet->setCellValue($colsyaNm . $currentRow, "訂正を行ってください。");
            }
            $objActSheet->setSelectedCell("B47");

            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $file = "files/HMTVE/" . "HITNET用確報データ(" . str_replace("/", "", $postData['lblExhibitTermStart']) . "～" . str_replace("/", "", $postData['lblExhibitTermEnd']) . ").xls";

            $result['data'] = $file;
            $result['result'] = true;

            $HMTVE070TotalKHonbu->Do_commit();
            $tranStartFlg = false;

        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $HMTVE070TotalKHonbu->Do_rollback();
            }
            $res = $HMTVE070TotalKHonbu->SQL14($postData);
            $result['result'] = FALSE;
            if ($e->getMessage() == 'テンプレートファイルが存在しません。' || $e->getMessage() == 'フォルダのパーミッションはエラーが発生しました。') {
                $result['error'] = $e->getMessage();
            } else {
                $result['error'] = 'MSG_W0006';
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


    //    ***********************************************************************
    //    '処 理 名：ロック解除クリックのイベント
    //    '関 数 名：btnLock_Click
    //    '引    数：無し
    //    '戻 り 値 ：なし
    //    '処理説明 ：ロック解除を行う
    //    '**********************************************************************

    public function btnLockClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            } else {
                throw new \Exception('params error');
            }
            $HMTVE070TotalKHonbu = new HMTVE070TotalKHonbu();
            $result = $HMTVE070TotalKHonbu->Lock($postData);
            if ($result['result'] == false) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }

    /**
     * 数值转换为Excel列
     *
     * @param integer $num
     * @return string
     */
    function int2Excel($num)
    {
        try {
            $az = 26;
            $m = (int) ($num % $az);
            $q = (int) ($num / $az);
            $letter = chr(ord('A') + $m);
            if ($q > 0) {
                return $this->int2Excel($q - 1) . $letter;
            }
            return $letter;
        } catch (\Exception $e) {
            return false;
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
