<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE060TotalKShop;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
//*******************************************
// * sample controller
//*******************************************
class HMTVE060TotalKShopController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
        $this->loadComponent('ClsLogControl');
    }
    /*
           ***********************************************************************
           '処 理 名：初期表示
           '関 数 名：index
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
    public function index()
    {
        $this->render('index', 'HMTVE060TotalKShop_layout');
    }
    /*
        ***********************************************************************
        '処 理 名：ページロード
        '関 数 名：Page_Load
        '引    数：無し
        '戻 り 値 ：無し
        '処理説明 ：ページ初期化
        '**********************************************************************
        */
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
            $HMTVE060TotalKShop = new HMTVE060TotalKShop();

            // 展示会開催期間に初期値
            $objdr = $HMTVE060TotalKShop->searchDate($postData);
            if ($objdr['result'] == false) {
                throw new \Exception('データ読込に失敗しました。');
            }
            $result['data']['date'] = $objdr['data'];

            // 店舗名を取得
            $objReader = $HMTVE060TotalKShop->getShopName($postData);
            if ($objReader['result'] == false) {
                throw new \Exception($objReader['data']);
            }
            $result['data']['ShopName'] = $objReader['data'];
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }
        $this->fncReturn($result);
    }

    /*
           ***********************************************************************
           '処 理 名：確報集計テーブル表示
           '関 数 名：btnView_Click
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
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
            $HMTVE060TotalKShop = new HMTVE060TotalKShop();
            //存在チェック
            $resCheck = $HMTVE060TotalKShop->dataCheck($postData);
            if ($resCheck['result'] == false) {
                throw new \Exception("データ読込に失敗しました。");
            }
            if (count((array) $resCheck['data']) == 0) {
                throw new \Exception('MSG_W0003');
            }

            // 成約車種内訳テーブル1の生成
            $resSyasyu = $HMTVE060TotalKShop->fillCarTypeTbl();
            if ($resSyasyu['result'] == false) {
                throw new \Exception("データ読込に失敗しました。");
            }

            // スタッフテーブルの生成
            $resStaff = $HMTVE060TotalKShop->SQL2($postData);
            if ($resStaff['result'] == false) {
                throw new \Exception("データ読込に失敗しました。");
            }

            // 確報集計明細テーブル生成
            $resSumDetail = $HMTVE060TotalKShop->SQL3($postData);
            if ($resSumDetail['result'] == false) {
                throw new \Exception("データ読込に失敗しました。");
            }

            // 成約車種内訳テーブルの生成
            $resCarType = $HMTVE060TotalKShop->SQL4($postData);
            if ($resCarType['result'] == false) {
                throw new \Exception("データ読込に失敗しました。");
            }

            // 確報集計テーブル合計生成
            $resSum = $HMTVE060TotalKShop->SQL5($postData);
            if ($resSum['result'] == false) {
                throw new \Exception("データ読込に失敗しました。");
            }

            // 合計_成約車種内訳テーブル2の生成
            $resSumCarType = $HMTVE060TotalKShop->SQL6($postData);
            if ($resSumCarType['result'] == false) {
                throw new \Exception("データ読込に失敗しました。");
            }
            $resStaffData = $resStaff['data'];
            $resSumDetailData = $resSumDetail['data'];
            $resCarTypeData = $resCarType['data'];
            $resSumData = $resSum['data'];
            $resSumCarTypeData = $resSumCarType['data'];
            $syainArr = array();
            foreach ((array) $resStaffData as $value) {
                $syainArr[$value['SYAIN_NO']] = $value;
            }
            foreach ((array) $resSumDetailData as $value) {
                $syainArr[$value['SYAIN_NO']] = array_merge($syainArr[$value['SYAIN_NO']], $value);
            }
            foreach ((array) $resCarTypeData as $value) {
                $syainArr[$value['SYAIN_NO']][$value['SYASYU_CD']] = $value['SEIYAKU_DAISU_D'];
            }
            $syainData = array();
            foreach ($syainArr as $value) {
                array_push($syainData, $value);
            }
            $sumData = array();
            if (count((array) $resSumData) > 0) {
                $sumData = $resSumData[0];
            }
            foreach ((array) $resSumCarTypeData as $value) {
                $sumData[$value['SYASYU_CD']] = $value['SEIYAKU_DAISU_KEI'];
            }
            $result['data']['detail'] = $syainData;
            $result['data']['sum'] = $sumData;
            $result['data']['syasyu'] = $resSyasyu['data'];
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }
        $this->fncReturn($result);
    }

    /*
           ***********************************************************************
           '処 理 名：印刷ボタンクリック
           '関 数 名：btnPrintOut_Click
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：印刷ボタンクリック
           '**********************************************************************
           */
    public function btnPrintOutClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $intState = 0;
        $lngCount = 0;
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            } else {
                throw new \Exception('params error');
            }
            $intState = 9;
            $HMTVE060TotalKShop = new HMTVE060TotalKShop();
            // データ存在のチェック
            $resCheck = $HMTVE060TotalKShop->dataCheck($postData);
            if ($resCheck['result'] == false) {
                throw new \Exception($resCheck['data']);
            }
            if (count((array) $resCheck['data']) == 0) {
                $intState = 1;
                throw new \Exception('MSG_W0003');
            }

            $resExcel = $this->createPDFHITNET($postData, $lngCount);
            if ($resExcel['result'] == false) {
                throw new \Exception($resExcel['error']);
            }
            $intState = $resExcel['intState'];
            $lngCount = $resExcel['lngCount'];
            $result = $resExcel;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        try {
            if ($intState != 0) {
                //ログ管理テーブルに登録
                if ($intState == 9) {
                    $lngCount = 0;
                }
                $resultLog = $this->ClsLogControl->fncLogEntryHMTVE("HMTVE060_Total_K_Shop", $intState, $lngCount, $_POST['data']['lblExhibitTermFrom'], $_POST['data']['lblExhibitTermTo'], $_POST['data']['ddlExhibitDay'], $_POST['data']['lblTenpoCD']);
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

    /*
           ***********************************************************************
           '処 理 名：Excelデータ生成
           '関 数 名：createPDFHITNET
           '引    数：無し
           '戻 り 値 ：ファイルパス
           '処理説明 ：Excelデータの生成を行う
           '**********************************************************************
           */
    public function createPDFHITNET($postData, $lngCount)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $HMTVE060TotalKShop = new HMTVE060TotalKShop();
            $objReader = $HMTVE060TotalKShop->SQL2($postData);
            if ($objReader['result'] == false) {
                throw new \Exception($objReader['data']);
            }
            $syainData = $objReader['data'];

            // 確報入力明細データ取得
            $objReader = $HMTVE060TotalKShop->SQL3($postData);
            if ($objReader['result'] == false) {
                throw new \Exception($objReader['data']);
            }
            $detailData = $objReader['data'];

            // 確報入力合計データ取得
            $objReader = $HMTVE060TotalKShop->SQL5($postData);
            if ($objReader['result'] == false) {
                throw new \Exception($objReader['data']);
            }
            $detailSumData = $objReader['data'];

            // 成約車種内訳テーブル取得
            $objReader = $HMTVE060TotalKShop->fillCarTypeTbl();
            if ($objReader['result'] == false) {
                throw new \Exception($objReader['data']);
            }
            $syasyuData = $objReader['data'];

            // 成約車種内訳テーブル2取得
            $objReader = $HMTVE060TotalKShop->SQL4($postData);
            if ($objReader['result'] == false) {
                throw new \Exception($objReader['data']);
            }
            $syasyuDetailData = $objReader['data'];

            // 合計_成約車種内訳テーブル2取得
            $objReader = $HMTVE060TotalKShop->SQL6($postData);
            if ($objReader['result'] == false) {
                throw new \Exception($objReader['data']);
            }
            $syasyuSumData = $objReader['data'];


            //出力先パス
            $strPath = dirname(dirname(dirname(__FILE__)));
            $tmpPath1 = dirname($strPath);
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            $strTemplatePath = $strPath . '/' . $strTemplatePath1 . "KAKUHOUTENPODATA.xls";
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
                    if ($file != "." && $file != ".." && strpos($file, "確報店舗データ") !== false) {
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

            $fileName = $tmpPath . "確報店舗データ(" . str_replace("/", "", $postData['ddlExhibitDay']) . ").xls";

            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            // include __DIR__ . '/Component/Classes/PHPExcel.php';
            // $objReader = new Xls();

            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);

            $objActSheet = $objPHPExcel->getActiveSheet();

            $leftStyle = array('alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, ), );
            // 店舗名取得
            $objActSheet->setCellValue('J4', $postData['lblTenpoNM']);
            // 展示会開催期間取得
            $objActSheet->setCellValue('P4', '展示会開催日   ' . $postData['ddlExhibitDay']);
            $objActSheet->getStyle('J4')->applyFromArray($leftStyle);
            $objActSheet->getStyle('P4')->applyFromArray($leftStyle);
            $objActSheet->insertNewRowBefore(11, count((array) $syainData));
            $detailStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'inside' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER, ),
            );

            $normalStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'horizontal' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER, ),
            );
            $noleftStyle = array('borders' => array('right' => array('borderStyle' => Border::BORDER_THIN), ), );
            $titleStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'inside' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER, ),
            );
            $titleTopStyle = array('alignment' => array('vertical' => Alignment::VERTICAL_TOP, ), );
            $titleCenterStyle = array('alignment' => array('vertical' => Alignment::VERTICAL_CENTER, ), );
            $notTopStyle = array(
                'borders' => array(
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'bottom' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'inside' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER, ),
            );

            for ($i = 0; $i < count((array) $syainData); $i++) {
                $column = 2;
                $row = 10 + $i;
                $cellCoordinate = $this->getColumnLetter($column) . $row;
                $objActSheet->setCellValue($cellCoordinate, $syainData[$i]['SYAIN_NM']);

                $cellCoordinate = $this->getColumnLetter($column) . (17 + count((array) $syainData) + $i);
                $objActSheet->setCellValue($cellCoordinate, $syainData[$i]['SYAIN_NM']);
                if ($syainData[$i]['SYAIN_NM'] != "") {
                    $lngCount = $lngCount + 1;
                }
            }
            $cellCoordinate = $this->getColumnLetter(2) . (10 + $i);
            $objActSheet->setCellValue($cellCoordinate, "合    計");
            $cellCoordinate = $this->getColumnLetter(2) . (17 + count((array) $syainData) + $i);
            $objActSheet->setCellValue($cellCoordinate, "合    計");

            array_push($detailData, $detailSumData[0]);
            for ($i = 0; $i < count($detailData); $i++) {
                $objActSheet->setCellValueExplicit($this->getColumnLetter(3) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_KUMI_KEI']), DataType::TYPE_STRING);
                $objActSheet->setCellValue($this->getColumnLetter(4) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_KUMI_AB_KOKYAKU']));
                $objActSheet->setCellValue($this->getColumnLetter(5) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_KUMI_AB_SINTA']));
                $objActSheet->setCellValue($this->getColumnLetter(6) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_KUMI_NONAB_KOKYAKU']));
                $objActSheet->setCellValue($this->getColumnLetter(7) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_KUMI_NONAB_SINTA']));
                $objActSheet->setCellValue($this->getColumnLetter(8) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_KUMI_NONAB_FREE']));
                $objActSheet->setCellValue($this->getColumnLetter(9) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['JIZEN_JYUNBI_DM']));
                $objActSheet->setCellValue($this->getColumnLetter(10) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['JIZEN_JYUNBI_DH']));
                $objActSheet->setCellValue($this->getColumnLetter(11) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['JIZEN_JYUNBI_POSTING']));
                $objActSheet->setCellValue($this->getColumnLetter(12) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['JIZEN_JYUNBI_TEL']));
                $objActSheet->setCellValue($this->getColumnLetter(13) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['JIZEN_JYUNBI_KAKUYAKU']));
                $objActSheet->setCellValue($this->getColumnLetter(14) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_BUNSEKI_YOBIKOMI']));
                $objActSheet->setCellValue($this->getColumnLetter(16) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_BUNSEKI_KAKUYAKU']));
                $objActSheet->setCellValue($this->getColumnLetter(17) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_BUNSEKI_KOUKOKU']));
                $objActSheet->setCellValue($this->getColumnLetter(18) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_BUNSEKI_MEDIA']));
                $objActSheet->setCellValue($this->getColumnLetter(19) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_BUNSEKI_CHIRASHI']));
                $objActSheet->setCellValue($this->getColumnLetter(20) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_BUNSEKI_TORIGAKARI']));
                $objActSheet->setCellValue($this->getColumnLetter(21) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_BUNSEKI_SYOKAI']));
                $objActSheet->setCellValue($this->getColumnLetter(22) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_BUNSEKI_WEB']));
                $objActSheet->setCellValue($this->getColumnLetter(23) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RAIJYO_BUNSEKI_SONOTA']));
                $objActSheet->setCellValue($this->getColumnLetter(24) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['ENQUETE_KAISYU']));
                $objActSheet->setCellValueExplicit($this->getColumnLetter(25) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['ENQUETE_RITU']), DataType::TYPE_STRING);
                $objActSheet->setCellValue($this->getColumnLetter(26) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['ABHOT_KOKYAKU']));
                $objActSheet->setCellValue($this->getColumnLetter(27) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['ABHOT_SINTA']));
                $objActSheet->setCellValueExplicit($this->getColumnLetter(28) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['ABHOT_RITU']), DataType::TYPE_STRING);
                $objActSheet->setCellValue($this->getColumnLetter(29) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['ABHOT_ZAN']));
                $objActSheet->setCellValue($this->getColumnLetter(30) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SATEI_KOKYAKU']));
                $objActSheet->setCellValue($this->getColumnLetter(31) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SATEI_KOKYAKU_TA']));
                $objActSheet->setCellValue($this->getColumnLetter(32) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SATEI_SINTA']));
                $objActSheet->setCellValue($this->getColumnLetter(33) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SATEI_SINTA_TA']));
                $objActSheet->setCellValue($this->getColumnLetter(34) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['DEMO_KENSU']));
                $objActSheet->setCellValueExplicit($this->getColumnLetter(35) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['DEMO_RITU']), DataType::TYPE_STRING);
                $objActSheet->setCellValue($this->getColumnLetter(36) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RUNCOST_KENSU']));
                $objActSheet->setCellValue($this->getColumnLetter(37) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SKYPLAN_KENSU']));
                $objActSheet->setCellValue($this->getColumnLetter(38) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['RUNCOST_SEIYAKU_KENSU']));
                $objActSheet->setCellValue($this->getColumnLetter(39) . (10 + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SKYPLAN_KEIYAKU_KENSU']));
                $objActSheet->setCellValueExplicit($this->getColumnLetter(3) . (17 + count((array) $syainData) + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SEIYAKU_KEI']), DataType::TYPE_STRING);
                $objActSheet->setCellValue($this->getColumnLetter(4) . (17 + count((array) $syainData) + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SEIYAKU_AB_KOKYAKU']));
                $objActSheet->setCellValue($this->getColumnLetter(5) . (17 + count((array) $syainData) + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SEIYAKU_AB_SINTA']));
                $objActSheet->setCellValue($this->getColumnLetter(6) . (17 + count((array) $syainData) + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SEIYAKU_NONAB_KOKYAKU']));
                $objActSheet->setCellValue($this->getColumnLetter(7) . (17 + count((array) $syainData) + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SEIYAKU_NONAB_SINTA']));
                $objActSheet->setCellValue($this->getColumnLetter(8) . (17 + count((array) $syainData) + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SEIYAKU_NONAB_FREE']));
                $objActSheet->setCellValueExplicit($this->getColumnLetter(9) . (17 + count((array) $syainData) + $i), $this->ClsComFncHMTVE->FncNv($detailData[$i]['SOKU_RITU']), DataType::TYPE_STRING);
            }
            $objActSheet->getStyle('C10:D' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('E10:I' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('J10:N' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('O10:Q' . (10 + count((array) $syainData)))->applyFromArray($normalStyle);
            $objActSheet->getStyle('P10:P' . (10 + count((array) $syainData)))->applyFromArray($noleftStyle);
            $objActSheet->getStyle('R10:X' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('Y10:Z' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('AA10:AC' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('AD10:AH' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('AI10:AJ' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('AK10:AK' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('AL10:AL' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('AM10:AM' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('AN10:AN' . (10 + count((array) $syainData)))->applyFromArray($detailStyle);
            $objActSheet->getStyle('D10:D' . (10 + count((array) $syainData)))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("FFC0C0C0");
            $objActSheet->getStyle('Z10:Z' . (10 + count((array) $syainData)))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("FFC0C0C0");
            $objActSheet->getStyle('AC10:AC' . (10 + count((array) $syainData)))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("FFC0C0C0");
            $objActSheet->getStyle('AJ10:AJ' . (10 + count((array) $syainData)))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("FFC0C0C0");
            $objActSheet->getStyle('D' . (10 + count((array) $syainData)) . ':AN' . (10 + count((array) $syainData)))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("FFC0C0C0");

            $objActSheet->setCellValue($this->getColumnLetter(10) . (13 + count((array) $syainData)), "成約車種内訳");
            for ($i = 0; $i < count((array) $syasyuData); $i++) {
                $objActSheet->setCellValue($this->getColumnLetter(10 + $i) . (14 + count((array) $syainData)), $this->setbr(str_replace(" ", "", $this->ClsComFncHMTVE->HkToFk($syasyuData[$i]['SYASYU_NM']))));
                $objActSheet->mergeCells($this->int2Excel(10 + $i) . (14 + count((array) $syainData)) . ':' . $this->int2Excel(10 + $i) . (16 + count((array) $syainData)));
            }
            $objActSheet->setCellValue($this->getColumnLetter(10 + $i) . (14 + count((array) $syainData)), "計");
            $objActSheet->mergeCells('K' . (13 + count((array) $syainData)) . ':' . $this->int2Excel(10 + $i) . (13 + count((array) $syainData)));
            $objActSheet->mergeCells($this->int2Excel(10 + $i) . (14 + count((array) $syainData)) . ':' . $this->int2Excel(10 + $i) . (16 + count((array) $syainData)));

            $strTemp = "";
            $rownNumber = 17 + count((array) $syainData);
            $colnumber = 10;
            $sum = 0;
            $sumsum = 0;
            $isfirst = true;
            foreach ((array) $syasyuDetailData as $value) {
                if ($value['SYAIN_NO'] !== null && $value['SYAIN_NO'] !== "" && $strTemp !== $value['SYAIN_NO'] && $isfirst == false) {
                    $objActSheet->setCellValue($this->getColumnLetter($colnumber) . $rownNumber, $sum);
                    $sumsum += $sum;
                    $rownNumber += 1;
                    $colnumber = 10;
                    $sum = 0;
                }
                $objActSheet->setCellValue($this->getColumnLetter($colnumber) . $rownNumber, $value['SEIYAKU_DAISU_D']);
                $sum += $value['SEIYAKU_DAISU_D'];
                $strTemp = $value['SYAIN_NO'];
                $colnumber += 1;

                $isfirst = false;
            }
            $objActSheet->setCellValue($this->getColumnLetter($colnumber) . $rownNumber, $sum);
            $sumsum += $sum;

            $rownNumber += 1;
            $objActSheet->setCellValue($this->getColumnLetter($colnumber) . $rownNumber, $sumsum);
            $colnumber = 10;
            foreach ((array) $syasyuSumData as $value) {
                $objActSheet->setCellValue($this->getColumnLetter($colnumber) . $rownNumber, $value['SEIYAKU_DAISU_KEI']);
                $colnumber += 1;
            }
            $objActSheet->getStyle('C' . (17 + count((array) $syainData)) . ':D' . $rownNumber)->applyFromArray($notTopStyle);
            $objActSheet->getStyle('E' . (17 + count((array) $syainData)) . ':J' . $rownNumber)->applyFromArray($notTopStyle);
            $objActSheet->getStyle('K' . (13 + count((array) $syainData)) . ':' . $this->int2Excel(10 + $i) . $rownNumber)->applyFromArray($titleStyle);
            $objActSheet->getStyle('K' . (14 + count((array) $syainData)) . ':' . $this->int2Excel(10 + $i) . (14 + count((array) $syainData)))->getAlignment()->setWrapText(true);
            $objActSheet->getStyle('K' . (14 + count((array) $syainData)) . ':' . $this->int2Excel(9 + $i) . (14 + count((array) $syainData)))->applyFromArray($titleTopStyle);
            $objActSheet->getStyle($this->int2Excel(10 + $i) . (14 + count((array) $syainData)))->applyFromArray($titleCenterStyle);
            $objActSheet->getStyle('D' . (17 + count((array) $syainData)) . ':D' . $rownNumber)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("FFC0C0C0");
            $objActSheet->getStyle('J' . (17 + count((array) $syainData)) . ':J' . $rownNumber)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("FFC0C0C0");
            $objActSheet->getStyle('D' . $rownNumber . ':' . $this->int2Excel(10 + $i) . $rownNumber)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("FFC0C0C0");

            $objActSheet->setSelectedCell("C3");

            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $file = "files/HMTVE/" . "確報店舗データ(" . str_replace("/", "", $postData['ddlExhibitDay']) . ").xls";

            $result['intState'] = 1;
            $result['lngCount'] = $lngCount;

            $result['data'] = $file;
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
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

    public function setbr($str)
    {
        $arr = preg_split('/(?<!^)(?!$)/u', $str);
        $res = "";
        for ($i = 0; $i < count($arr); $i++) {
            $res .= $arr[$i] . "\n";
        }
        return $res;
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
