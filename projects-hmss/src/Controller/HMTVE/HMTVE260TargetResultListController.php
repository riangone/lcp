<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240326    		受入検証.xlsx NO2     					車種を追加してください             	   caina
 * 20240611    		202406_データ集計システム_CX-80追加        CX-80追加            		 		  LHB
 * 20240712    		CX-80追加判断                           CX-80追加判断            		 	     LHB
 * 20250905    	CHUKANKAIGI_NOT80.xlsxは廃止      CX-80が発売される前後に対応入れたところですね     lujunxia
 *                                                CHUKANKAIGI_NOT80.xlsxは廃止して
 *                                                CHUKANKAIGI.xlsx を使用するようにしていただけますか？
 * 20251118         202511_データ集計システム_機能追加要望   目標と実績＿改修イメージ                caina
 * 20251224           修正依頼                一覧画面では 法人１G、法人２Ｇを合計して１行に表示     YIN
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE260TargetResultList;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\IOFactory;
//*******************************************
// * sample controller
//*******************************************
class HMTVE260TargetResultListController extends AppController
{
    public $autoLayout = TRUE;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE260TargetResultList_layout');
    }

    //***********************************************************************
    //'処 理 名：初期表示
    //'関 数 名：index
    //'引    数：無し
    //'戻 り 値 ：無し
    //'処理説明 ：
    //'**********************************************************************
    public function fncPageLoad()
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );
        try {
            $HMTVE260TargetResultList = new HMTVE260TargetResultList();

            // 店舗名を取得する
            $resBusyo = $HMTVE260TargetResultList->getShopName();
            if ($resBusyo['result'] == false) {
                throw new \Exception($resBusyo['data']);
            }
            if (count((array) $resBusyo['data']) > 0) {
                $lblShopname = $resBusyo['data'][0]['BUSYO_RYKNM'];
            } else {
                $lblShopname = "";
            }
            $sysDate = $this->ClsComFncHMTVE->FncGetSysDate();
            if (strlen($sysDate) !== 10) {
                throw new \Exception($sysDate);
            }
            $data = array(
                'sysDate' => $sysDate,
                'lblShopname' => $lblShopname,
            );
            //20240712 LHB INS S
            $isExit = $HMTVE260TargetResultList->checkCX80SQL();
            if ($isExit['result'] == false) {
                throw new \Exception($isExit['data']);
            }
            $result['isExit'] = $isExit['data'][0]['ISEXIT'];
            //20240712 LHB INS E
            $result["data"] = $data;
            $result["result"] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }


    //  ***********************************************************************
    //  '処 理 名：目標と実績表示ボタンのイベント
    //  '関 数 名：btnETSearch_Click
    //  '引    数：無し
    //  '戻 り 値 ：無し
    //  '処理説明 ：目標と実績画面の戻り値を画面項目にセットする
    //  '**********************************************************************

    public function btnETSearchClick()
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
            $HMTVE260TargetResultList = new HMTVE260TargetResultList();
            if ($postData['ActiveViewIndex'] == 0) {
                //データの取得
                $dataSet1 = $HMTVE260TargetResultList->SQL1($postData);
                if ($dataSet1['result'] == false) {
                    throw new \Exception($dataSet1['data']);
                }
                $result['data']['dataSet1'] = $this->sun181and183((array) $dataSet1['data']);
                // 目標と実績小計データを取得する
                $objdr2 = $HMTVE260TargetResultList->SQL2($postData);
                if ($objdr2['result'] == false) {
                    throw new \Exception($objdr2['data']);
                }
                if (count((array) $objdr2['data']) > 0) {
                    $result['data']['objdr2'] = $objdr2['data'];
                } else {
                    $objdr2['data'][0] = array();
                    $result['data']['objdr2'] = $objdr2['data'];
                }

                // その他行に値をセットする
                $objdr3 = $HMTVE260TargetResultList->SQL3($postData);
                if ($objdr3['result'] == false) {
                    throw new \Exception($objdr3['data']);
                }
                if (count((array) $objdr3['data']) > 0) {
                    $result['data']['objdr3'] = $objdr3['data'];
                } else {
                    $objdr3['data'][0] = array();
                    $result['data']['objdr3'] = $objdr3['data'];
                }

                // 前年同月行に値をセットする
                $objdr4 = $HMTVE260TargetResultList->SQLZennen($postData);
                if ($objdr4['result'] == false) {
                    throw new \Exception($objdr4['data']);
                }
                if (count((array) $objdr4['data']) > 0) {
                    $result['data']['objdr4'] = $objdr4['data'];
                } else {
                    $objdr4['data'][0] = array();
                    $result['data']['objdr4'] = $objdr4['data'];
                }
            } elseif ($postData['ActiveViewIndex'] == 1) {
                //20240712 LHB INS S
                $isExit = $HMTVE260TargetResultList->checkCX80SQL();
                if ($isExit['result'] == false) {
                    throw new \Exception($isExit['data']);
                }
                $result['data']['isExit'] = $isExit['data'][0]['ISEXIT'];
                $postData['isExit'] = $isExit['data'][0]['ISEXIT'];
                //20240712 LHB INS E
                // 明細MultiViewのActiveViewIndexが1(車種内訳Viewが選択されている)の場合
                $dataSet2 = $HMTVE260TargetResultList->SQL4($postData);
                if ($dataSet2['result'] == false) {
                    throw new \Exception($dataSet2['data']);
                }
                $result['data']['dataSet2'] = $this->sun181and183((array) $dataSet2['data']);

                // 合計明細ﾃｰﾌﾞﾙに値をセットする
                // 小計データを取得する
                $objdr5 = $HMTVE260TargetResultList->SQL5($postData);
                if ($objdr5['result'] == false) {
                    throw new \Exception($objdr5['data']);
                }
                if (count((array) $objdr5['data']) > 0) {
                    $result['data']['objdr5'] = $objdr5['data'];
                } else {
                    $objdr5['data'][0] = array();
                    $result['data']['objdr5'] = $objdr5['data'];
                }

                // その他行に値をセットする
                $objdr6 = $HMTVE260TargetResultList->SQL6($postData);
                if ($objdr6['result'] == false) {
                    throw new \Exception($objdr6['data']);
                }
                if (count((array) $objdr6['data']) > 0) {
                    $result['data']['objdr6'] = $objdr6['data'];
                } else {
                    $objdr6['data'][0] = array();
                    $result['data']['objdr6'] = $objdr6['data'];
                }

                // 前年同月行に値をセットする
                $objdr7 = $HMTVE260TargetResultList->SQLZenSyasyu($postData);
                if ($objdr7['result'] == false) {
                    throw new \Exception($objdr7['data']);
                }
                if (count((array) $objdr7['data']) > 0) {
                    $result['data']['objdr7'] = $objdr7['data'];
                } else {
                    $objdr7['data'][0] = array();
                    $result['data']['objdr7'] = $objdr7['data'];
                }
            }

            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }

        $this->fncReturn($result);
    }

    public function makeChukanExcel()
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

            //出力先パス
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;

            //20240712 LHB INS S
            $HMTVE260TargetResultList = new HMTVE260TargetResultList();
            $isExit = $HMTVE260TargetResultList->checkCX80SQL();
            if ($isExit['result'] == false) {
                throw new \Exception($isExit['data']);
            }
            $exit_cx80 = $isExit['data'][0]['ISEXIT'];
            $postData['isExit'] = $isExit['data'][0]['ISEXIT'];
            //20240712 LHB INS E

            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            // 20240712 LHB UPD S
            // $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath1 . "CHUKANKAIGI.xlsx";
            $strTemplatePath = $tmpPath1 . '/src/' . $strTemplatePath1;
            // 20250905 lujunxia upd s
            // if ($exit_cx80 == "1") {
            $path = "CHUKANKAIGI.xlsx";
            // } else {
            //     $path = "CHUKANKAIGI_NOT80.xlsx";
            // }
            // 20250905 lujunxia upd e
            $strTemplatePath = $strTemplatePath . $path;
            // 20240712 LHB UPD S
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
                    if ($file != "." && $file != ".." && strpos($file, "月目標と実績") !== false) {
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
            // ----------------
            //  対象データ抽出
            // ----------------
            // 目標と実績
            // 20240712 LHB DEL S
            // $HMTVE260TargetResultList = new HMTVE260TargetResultList();
            // 20240712 LHB DEL S
            $res = $HMTVE260TargetResultList->SQL1($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt1 = $this->sun181and183((array) $res['data']);

            // 車種内訳
            $res = $HMTVE260TargetResultList->SQL4($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt2 = $this->sun181and183((array) $res['data']);

            // 目標と実績(小計)
            $res = $HMTVE260TargetResultList->SQL2($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt3 = $res['data'];
            $dt3[0]['BUSYO_RYKNM'] = "小計";

            // 車種内訳(小計)
            $res = $HMTVE260TargetResultList->SQL5($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt4 = $res['data'];

            // 目標と実績(その他)
            $res = $HMTVE260TargetResultList->SQL3($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt5 = $res['data'];

            // 車種内訳(その他)
            $res = $HMTVE260TargetResultList->SQL6($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt6 = $res['data'];

            // 目標と実績(前年)
            $res = $HMTVE260TargetResultList->SQLZennen($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt7 = $res['data'];

            // 車種内訳(前年)
            $res = $HMTVE260TargetResultList->SQLZenSyasyu($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt8 = $res['data'];

            array_push($dt1, $dt3[0]);
            array_push($dt2, $dt4[0]);
            if (count((array) $dt5) > 0) {
                $dt5[0]['BUSYO_RYKNM'] = "その他";
                array_push($dt1, $dt5[0]);
                array_push($dt2, $dt6[0]);
            } else {
                array_push($dt1, array());
                array_push($dt2, array());
            }
            $dt7[0]['BUSYO_RYKNM'] = "前年同月";
            array_push($dt1, $dt7[0]);
            array_push($dt2, $dt8[0]);
            $fileName = $tmpPath . $postData['txtbDuring'] . "年" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "月目標と実績.xlsx";

            $x = 0;
            $y = 9;
            $TTD = 0;
            $TUD = 0;
            $URC = 0;

            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            $objPHPExcel = IOFactory::load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();

            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex(0 + 1) . '2', $postData['txtbDuring'] . "年" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "月分　目標と実績");
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex(0 + 1) . '3', "=NOW()");

            $normalAlignLeftStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_THIN),
                    'inside' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ),
            );
            $normalAlignRightStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_THIN),
                    'inside' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ),
            );
            $rightDoubleAlignRightStyle = array(
                'borders' => array(
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_DOUBLE),
                    'bottom' => array('borderStyle' => Border::BORDER_THIN),
                    'inside' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ),
            );
            $AlignCenterStyle = array(
                'borders' => array(
                    'outline' => array('borderStyle' => Border::BORDER_THIN),
                    'inside' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ),
            );

            for ($i = 0; $i < count($dt1); $i++) {
                if ($i == count($dt1) - 2 || $i == count($dt1) - 1) {
                    $y += 1;
                    $objActSheet->getRowDimension($y)->setRowHeight(5);
                }
                if ($i !== count($dt1) - 2 || count($dt1[$i]) > 0) {
                    // 部署
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNv($dt1[$i]['BUSYO_RYKNM']));
                    $objActSheet->mergeCells('A' . ($y + 1) . ':B' . ($y + 1));
                    $x += 2;
                    // 総限界利益_目標
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['GENRI_MOKUHYO']));
                    $x += 1;
                    // 総限界利益_月末予想
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['GENRI_JISSEKI']));
                    $x += 1;
                    // 総限界利益_達成率
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=IF(OR(D" . ($y + 1) . "=0,C" . ($y + 1) . "=0),\"\",D" . ($y + 1) . "/C" . ($y + 1) . ")");
                    $x += 1;
                    // 売上台数_当月目標_メイン権
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIMOKU_MAIN']));
                    $x += 1;
                    // 売上台数_当月目標_他チャンネル
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIMOKU_TACHANEL']));
                    $x += 1;
                    // 売上台数_月末予想_メイン権
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_MAIN_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_MAIN_S']));
                    $x += 1;
                    // 売上台数_月末予想_軽自動車
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_KEI_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_KEI_S']));
                    $x += 1;
                    // 売上台数_月末予想_ボルボ他
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_VOLVO_SONOTA_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_VOLVO_SONOTA_S']));
                    $x += 1;
                    // 売上台数_売上台数計
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=H" . ($y + 1) . "+I" . ($y + 1) . "+J" . ($y + 1));
                    $x += 1;
                    // 売上台数_達成率
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=IF(OR(K" . ($y + 1) . "=0,F" . ($y + 1) . "+G" . ($y + 1) . "=0),\"\",K" . ($y + 1) . "/(F" . ($y + 1) . "+G" . ($y + 1) . "))");
                    $x += 1;
                    // 登録台数_福祉
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_FUKUSHI_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_FUKUSHI_S']));
                    $x += 1;
                    // 登録台数_他自
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_TAJI_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_TAJI_S']));
                    $x += 1;
                    // 登録台数_他自
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_JITA_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_JITA_S']));
                    $x += 1;
                    // 登録台数_登録台数計
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=H" . ($y + 1) . "+M" . ($y + 1) . "+N" . ($y + 1) . "-O" . ($y + 1));
                    $x += 1;
                    // 軽自動車_他自
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_KEI_TAJI']));
                    $x += 1;
                    // 軽自動車_自他
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_KEI_JITA']));
                    $x += 1;
                    // 軽自動車_福祉
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_KEI_FUKUSHI']));
                    $x += 1;
                    // 中古車_直売
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['CHOKU_Y']));
                    $x += 1;
                    // 中古車_業売
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt1[$i]['GYOBAI_Y']));
                    $x += 2;
                    // 車種内訳_デミオ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['DEMIO_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['DEMIO_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_(ZM)2
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['M2G_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['M2G_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ベリーサ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['VRW_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['VRW_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_CX-3
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX3_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX3_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ＣＸ－５
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX5_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX5_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ＣＸ－８
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX8_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX8_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ＣＸ－３０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX30_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX30_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ＭＸ－３０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['MX30_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['MX30_TRK_DAISU_S']));
                    $x += 1;
                    //20240326 caina ins s
                    // 車種内訳_ＣＸ－６０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX60_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX60_TRK_DAISU_S']));
                    $x += 1;
                    //20240326 caina ins e
                    //20240611 LHB ins s
                    // 車種内訳_ＣＸ－８０
                    //20240712 LHB upd s
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX80_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX80_TRK_DAISU_S']));
                    // $x += 1;
                    if ($exit_cx80 === '1') {
                        $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX80_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['CX80_TRK_DAISU_S']));
                        $x += 1;
                    }
                    //20240712 LHB upd e
                    //20240611 LHB ins e
                    // 車種内訳_Mazda3 SDN
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['M3S_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['M3S_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_Mazda3 HB
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['M3H_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['M3H_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_Mazda6 SDN
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['M6S_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['M6S_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_Mazda6 ＷＧＮ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['M6W_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['M6W_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_アテンザ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['ATENZA_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['ATENZA_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_アクセラ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['AXS_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['AXS_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_プレマシー
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['PREMACY_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['PREMACY_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ビアンテ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['BIANTE_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['BIANTE_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ＭＰＶ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['MPV_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['MPV_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ロードスター
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['LDSTAR_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['LDSTAR_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ファミリアバン
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['FMV_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['FMV_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_ボンゴ／ブローニィ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['BONGO_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['BONGO_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_タイタン
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['TT_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['TT_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_軽自動車
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $this->ClsComFncHMTVE->FncNz($dt2[$i]['KEI_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['KEI_TRK_DAISU_S']));
                    $x += 1;
                    // 車種内訳_登録計
                    //20240326 caina upd s
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=P" . ($y + 1) . "+AP" . ($y + 1));
                    //20240611 LHB upd s
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=P" . ($y + 1) . "+AQ" . ($y + 1));
                    //20240712 LHB upd s
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=P" . ($y + 1) . "+AR" . ($y + 1));
                    if ($exit_cx80 === '1') {
                        $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=P" . ($y + 1) . "+AT" . ($y + 1));
                    } else {
                        $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=P" . ($y + 1) . "+AQ" . ($y + 1));
                    }
                    //20240712 LHB upd e
                    //20240611 LHB upd e
                    //20240326 caina upd e
                    if ($i == count($dt1) - 2 || $i == count($dt1) - 3) {
                        // 総登録台数
                        $TTD = $TTD + $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRK_GK_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRK_GK_S']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['KEI_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['KEI_TRK_DAISU_S']);
                        // 総売上台数
                        $TUD = $TUD + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URI_GK_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URI_GK_S']);
                        // 内レンタカー
                        $URC = $URC + $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRKDAISU_RENTA']);
                    }
                } else {
                    // その他
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "その他");
                    $objActSheet->mergeCells('A' . ($y + 1) . ':B' . ($y + 1));
                    // $y += 1;
                }
                $objActSheet->getStyle('A' . ($y + 1) . ':B' . ($y + 1))->applyFromArray($normalAlignLeftStyle);
                $objActSheet->getStyle('C' . ($y + 1) . ':U' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                $objActSheet->getStyle('W' . ($y + 1) . ':X' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                $objActSheet->getStyle('Z' . ($y + 1) . ':AC' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                //20240326 caina upd s
                $objActSheet->getStyle('AD' . ($y + 1) . ':AD' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AC' . ($y + 1) . ':AD' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AE' . ($y + 1) . ':AF' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AG' . ($y + 1) . ':AH' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AI' . ($y + 1) . ':AJ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AK' . ($y + 1) . ':AK' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AL' . ($y + 1) . ':AM' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AN' . ($y + 1) . ':AO' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AP' . ($y + 1) . ':AQ' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                $objActSheet->getStyle('AE' . ($y + 1) . ':AE' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                //20240611 LHB upd s
                // $objActSheet->getStyle('AD' . ($y + 1) . ':AE' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AF' . ($y + 1) . ':AG' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AH' . ($y + 1) . ':AI' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AJ' . ($y + 1) . ':AK' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AL' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AM' . ($y + 1) . ':AN' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AO' . ($y + 1) . ':AP' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                //20240712 LHB upd s
                // $objActSheet->getStyle('AD' . ($y + 1) . ':AD' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AE' . ($y + 1) . ':AF' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AG' . ($y + 1) . ':AH' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AI' . ($y + 1) . ':AJ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AK' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AM' . ($y + 1) . ':AM' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AN' . ($y + 1) . ':AO' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AP' . ($y + 1) . ':AQ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                // $objActSheet->getStyle('AR' . ($y + 1) . ':AS' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                if ($exit_cx80 === '1') {
                    $objActSheet->getStyle('AF' . ($y + 1) . ':AF' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AG' . ($y + 1) . ':AH' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AI' . ($y + 1) . ':AJ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AK' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AM' . ($y + 1) . ':AN' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AO' . ($y + 1) . ':AO' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AP' . ($y + 1) . ':AQ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AR' . ($y + 1) . ':AS' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AT' . ($y + 1) . ':AU' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                } else {
                    $objActSheet->getStyle('AD' . ($y + 1) . ':AE' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AF' . ($y + 1) . ':AG' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AH' . ($y + 1) . ':AI' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AJ' . ($y + 1) . ':AK' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AL' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AM' . ($y + 1) . ':AN' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AO' . ($y + 1) . ':AP' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('AQ' . ($y + 1) . ':AR' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                }
                //20240712 LHB upd e
                //20240611 LHB upd e
                //20240326 caina upd e

                $x = 0;
                $y += 1;
                if ($i == count($dt1) - 2) {
                    $y += 1;
                    $objActSheet->getRowDimension($y)->setRowHeight(5);
                    // 部署
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "合計");
                    $objActSheet->mergeCells('A' . ($y + 1) . ':B' . ($y + 1));
                    $x += 2;
                    // 総限界利益_目標
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=C" . ($y - 3) . "+C" . ($y - 1));
                    $x += 1;
                    // 総限界利益_月末予想
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=D" . ($y - 3) . "+D" . ($y - 1));
                    $x += 1;
                    // 総限界利益_達成率
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=IF(OR(D" . ($y + 1) . "=0,C" . ($y + 1) . "=0),\"\",D" . ($y + 1) . "/C" . ($y + 1) . ")");
                    $x += 1;
                    // 売上台数_当月目標_メイン権
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=F" . ($y - 3) . "+F" . ($y - 1));
                    $x += 1;
                    // 売上台数_当月目標_他チャンネル
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=G" . ($y - 3) . "+G" . ($y - 1));
                    $x += 1;
                    // 売上台数_月末予想_メイン権
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=H" . ($y - 3) . "+H" . ($y - 1));
                    $x += 1;
                    // 売上台数_月末予想_軽自動車
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=I" . ($y - 3) . "+I" . ($y - 1));
                    $x += 1;
                    // 売上台数_月末予想_ボルボ他
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=J" . ($y - 3) . "+J" . ($y - 1));
                    $x += 1;
                    // 売上台数_売上台数計
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=K" . ($y - 3) . "+K" . ($y - 1));
                    $x += 1;
                    // 売上台数_達成率
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=IF(OR(K" . ($y + 1) . "=0,F" . ($y + 1) . "+G" . ($y + 1) . "=0),\"\",K" . ($y + 1) . "/(F" . ($y + 1) . "+G" . ($y + 1) . "))");
                    $x += 1;
                    // 登録台数_福祉
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=M" . ($y - 3) . "+M" . ($y - 1));
                    $x += 1;
                    // 登録台数_他自
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=N" . ($y - 3) . "+N" . ($y - 1));
                    $x += 1;
                    // 登録台数_他自
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=O" . ($y - 3) . "+O" . ($y - 1));
                    $x += 1;
                    // 登録台数_登録台数計
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=P" . ($y - 3) . "+P" . ($y - 1));
                    $x += 1;
                    // 軽自動車_他自
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=Q" . ($y - 3) . "+Q" . ($y - 1));
                    $x += 1;
                    // 軽自動車_自他
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=R" . ($y - 3) . "+R" . ($y - 1));
                    $x += 1;
                    // 軽自動車_福祉
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=S" . ($y - 3) . "+S" . ($y - 1));
                    $x += 1;
                    // 中古車_直売
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=T" . ($y - 3) . "+T" . ($y - 1));
                    $x += 1;
                    // 中古車_業売
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=U" . ($y - 3) . "+U" . ($y - 1));
                    $x += 2;
                    // 車種内訳_デミオ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=W" . ($y - 3) . "+W" . ($y - 1));
                    $x += 1;
                    // 車種内訳_(ZM)2
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=X" . ($y - 3) . "+X" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ベリーサ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=W" . ($y - 3) . "+W" . ($y - 1));
                    $x += 1;
                    // 車種内訳_CX-3
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=Z" . ($y - 3) . "+Z" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＣＸ－５
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AA" . ($y - 3) . "+AA" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＣＸ－８
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AB" . ($y - 3) . "+AB" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＣＸ－３０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AC" . ($y - 3) . "+AC" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＭＸ－３０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AD" . ($y - 3) . "+AD" . ($y - 1));
                    $x += 1;
                    //20240326 caina ins s
                    // 車種内訳_ＣＸ－６０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AE" . ($y - 3) . "+AE" . ($y - 1));
                    $x += 1;
                    //20240326 caina ins e
                    //20240611 LHB ins s
                    // 車種内訳_ＣＸ－８０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AF" . ($y - 3) . "+AF" . ($y - 1));
                    $x += 1;
                    //20240611 LHB ins e
                    //20240326 caina upd s
                    //20240611 LHB upd s
                    // 車種内訳_Mazda3 SDN
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AC" . ($y - 3) . "+AC" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AD" . ($y - 3) . "+AD" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AG" . ($y - 3) . "+AG" . ($y - 1));
                    $x += 1;
                    // 車種内訳_Mazda3 HB
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AD" . ($y - 3) . "+AD" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AE" . ($y - 3) . "+AE" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AH" . ($y - 3) . "+AH" . ($y - 1));
                    $x += 1;
                    // 車種内訳_Mazda6 SDN
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AE" . ($y - 3) . "+AE" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AF" . ($y - 3) . "+AF" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AI" . ($y - 3) . "+AI" . ($y - 1));
                    $x += 1;
                    // 車種内訳_Mazda6 ＷＧＮ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AF" . ($y - 3) . "+AF" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AG" . ($y - 3) . "+AG" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AJ" . ($y - 3) . "+AJ" . ($y - 1));
                    $x += 1;
                    // 車種内訳_アテンザ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AG" . ($y - 3) . "+AG" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AH" . ($y - 3) . "+AH" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AK" . ($y - 3) . "+AK" . ($y - 1));
                    $x += 1;
                    // 車種内訳_アクセラ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AH" . ($y - 3) . "+AH" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AI" . ($y - 3) . "+AI" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AL" . ($y - 3) . "+AL" . ($y - 1));
                    $x += 1;
                    // 車種内訳_プレマシー
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AI" . ($y - 3) . "+AI" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AJ" . ($y - 3) . "+AJ" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AM" . ($y - 3) . "+AM" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ビアンテ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AJ" . ($y - 3) . "+AJ" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AK" . ($y - 3) . "+AK" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AN" . ($y - 3) . "+AN" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＭＰＶ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AK" . ($y - 3) . "+AK" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AL" . ($y - 3) . "+AL" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AO" . ($y - 3) . "+AO" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ロードスター
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AL" . ($y - 3) . "+AL" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AM" . ($y - 3) . "+AM" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AP" . ($y - 3) . "+AP" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ファミリアバン
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AM" . ($y - 3) . "+AM" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AN" . ($y - 3) . "+AN" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AQ" . ($y - 3) . "+AQ" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ボンゴ／ブローニィ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AN" . ($y - 3) . "+AN" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AO" . ($y - 3) . "+AO" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AR" . ($y - 3) . "+AR" . ($y - 1));
                    $x += 1;
                    // 車種内訳_タイタン
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AO" . ($y - 3) . "+AO" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AP" . ($y - 3) . "+AP" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AS" . ($y - 3) . "+AS" . ($y - 1));
                    $x += 1;
                    // 車種内訳_軽自動車
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AP" . ($y - 3) . "+AP" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AQ" . ($y - 3) . "+AQ" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AT" . ($y - 3) . "+AT" . ($y - 1));
                    //20240712 LHB upd s
                    // $x += 1;
                    // 車種内訳_登録計
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AQ" . ($y - 3) . "+AQ" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AR" . ($y - 3) . "+AR" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AS" . ($y - 3) . "+AS" . ($y - 1));
                    if ($exit_cx80 === '1') {
                        $x += 1;
                        $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AU" . ($y - 3) . "+AU" . ($y - 1));
                    }
                    //20240712 LHB upd e
                    //20240611 LHB upd e
                    //20240326 caina upd e

                    $objActSheet->getStyle('A' . ($y + 1) . ':B' . ($y + 1))->applyFromArray($normalAlignLeftStyle);
                    $objActSheet->getStyle('C' . ($y + 1) . ':U' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    $objActSheet->getStyle('W' . ($y + 1) . ':X' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('Z' . ($y + 1) . ':AC' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    //20240326 caina upd s
                    $objActSheet->getStyle('AD' . ($y + 1) . ':AD' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AC' . ($y + 1) . ':AD' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AE' . ($y + 1) . ':AF' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AG' . ($y + 1) . ':AH' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AI' . ($y + 1) . ':AJ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AK' . ($y + 1) . ':AK' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AL' . ($y + 1) . ':AM' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AN' . ($y + 1) . ':AO' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AP' . ($y + 1) . ':AQ' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    $objActSheet->getStyle('AE' . ($y + 1) . ':AE' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    //20240611 LHB upd s
                    // $objActSheet->getStyle('AD' . ($y + 1) . ':AE' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AF' . ($y + 1) . ':AG' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AH' . ($y + 1) . ':AI' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AJ' . ($y + 1) . ':AK' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AM' . ($y + 1) . ':AN' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AL' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AO' . ($y + 1) . ':AP' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AQ' . ($y + 1) . ':AR' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    //20240712 LHB upd s
                    // $objActSheet->getStyle('AD' . ($y + 1) . ':AD' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AE' . ($y + 1) . ':AF' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AG' . ($y + 1) . ':AH' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AI' . ($y + 1) . ':AJ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AK' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AM' . ($y + 1) . ':AM' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AN' . ($y + 1) . ':AO' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AP' . ($y + 1) . ':AQ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AR' . ($y + 1) . ':AS' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    if ($exit_cx80 === '1') {
                        $objActSheet->getStyle('AF' . ($y + 1) . ':AF' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AG' . ($y + 1) . ':AH' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AI' . ($y + 1) . ':AJ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AK' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AM' . ($y + 1) . ':AN' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AO' . ($y + 1) . ':AO' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AP' . ($y + 1) . ':AQ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AR' . ($y + 1) . ':AS' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AT' . ($y + 1) . ':AU' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    } else {
                        $objActSheet->getStyle('AD' . ($y + 1) . ':AE' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AF' . ($y + 1) . ':AG' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AH' . ($y + 1) . ':AI' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AJ' . ($y + 1) . ':AK' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AM' . ($y + 1) . ':AN' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AL' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AO' . ($y + 1) . ':AP' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AQ' . ($y + 1) . ':AR' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    }
                    //20240712 LHB upd e
                    //20240611 LHB upd e
                    //20240326 caina upd e

                    $x = 0;
                    $y += 1;
                }
                if ($i == count($dt1) - 1) {
                    $y += 1;
                    $objActSheet->getRowDimension($y)->setRowHeight(5);
                    // 部署
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "対前年差");
                    $objActSheet->mergeCells('A' . ($y + 1) . ':B' . ($y + 1));
                    $x += 2;
                    // 総限界利益_目標
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=C" . ($y - 3) . "-C" . ($y - 1));
                    $x += 1;
                    // 総限界利益_月末予想
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=D" . ($y - 3) . "-D" . ($y - 1));
                    $x += 1;
                    // 総限界利益_達成率
                    $x += 1;
                    // 売上台数_当月目標_メイン権
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=F" . ($y - 3) . "-F" . ($y - 1));
                    $x += 1;
                    // 売上台数_当月目標_他チャンネル
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=G" . ($y - 3) . "-G" . ($y - 1));
                    $x += 1;
                    // 売上台数_月末予想_メイン権
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=H" . ($y - 3) . "-H" . ($y - 1));
                    $x += 1;
                    // 売上台数_月末予想_軽自動車
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=I" . ($y - 3) . "-I" . ($y - 1));
                    $x += 1;
                    // 売上台数_月末予想_ボルボ他
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=J" . ($y - 3) . "-J" . ($y - 1));
                    $x += 1;
                    // 売上台数_売上台数計
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=K" . ($y - 3) . "-K" . ($y - 1));
                    $x += 1;
                    // 売上台数_達成率
                    $x += 1;
                    // 登録台数_福祉
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=M" . ($y - 3) . "-M" . ($y - 1));
                    $x += 1;
                    // 登録台数_他自
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=N" . ($y - 3) . "-N" . ($y - 1));
                    $x += 1;
                    // 登録台数_他自
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=O" . ($y - 3) . "-O" . ($y - 1));
                    $x += 1;
                    // 登録台数_登録台数計
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=P" . ($y - 3) . "-P" . ($y - 1));
                    $x += 1;
                    // 軽自動車_他自
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=Q" . ($y - 3) . "-Q" . ($y - 1));
                    $x += 1;
                    // 軽自動車_自他
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=R" . ($y - 3) . "-R" . ($y - 1));
                    $x += 1;
                    // 軽自動車_福祉
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=S" . ($y - 3) . "-S" . ($y - 1));
                    $x += 1;
                    // 中古車_直売
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=T" . ($y - 3) . "-T" . ($y - 1));
                    $x += 1;
                    // 中古車_業売
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=U" . ($y - 3) . "-U" . ($y - 1));
                    $x += 2;
                    // 車種内訳_デミオ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=W" . ($y - 3) . "-W" . ($y - 1));
                    $x += 1;
                    // 車種内訳_(ZM)2
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=X" . ($y - 3) . "-X" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ベリーサ
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=Y" . ($y - 3) . "-Y" . ($y - 1));
                    $x += 1;
                    // 車種内訳_CX-3
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=Z" . ($y - 3) . "-Z" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＣＸ－５
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AA" . ($y - 3) . "-AA" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＣＸ－８
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AB" . ($y - 3) . "-AB" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＣＸ－３０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AC" . ($y - 3) . "-AC" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＭＸ－３０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AD" . ($y - 3) . "-AD" . ($y - 1));
                    $x += 1;
                    //20240326 caina ins s
                    // 車種内訳_ＣＸ－６０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AE" . ($y - 3) . "-AE" . ($y - 1));
                    $x += 1;
                    //20240326 caina ins e
                    //20240611 LHB ins s
                    // 車種内訳_ＣＸ－８０
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AF" . ($y - 3) . "-AF" . ($y - 1));
                    $x += 1;
                    //20240611 LHB upd e
                    //20240326 caina upd s
                    //20240611 LHB upd s
                    // 車種内訳_Mazda3 SDN
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AC" . ($y - 3) . "-AC" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AD" . ($y - 3) . "+AD" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=G" . ($y - 3) . "+AG" . ($y - 1));
                    $x += 1;
                    // 車種内訳_Mazda3 HB
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AD" . ($y - 3) . "+AD" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AE" . ($y - 3) . "+AE" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AH" . ($y - 3) . "+AH" . ($y - 1));
                    $x += 1;
                    // 車種内訳_Mazda6 SDN
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AE" . ($y - 3) . "+AE" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AF" . ($y - 3) . "+AF" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AI" . ($y - 3) . "+AI" . ($y - 1));
                    $x += 1;
                    // 車種内訳_Mazda6 ＷＧＮ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AF" . ($y - 3) . "+AF" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AG" . ($y - 3) . "+AG" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AJ" . ($y - 3) . "+AJ" . ($y - 1));
                    $x += 1;
                    // 車種内訳_アテンザ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AG" . ($y - 3) . "+AG" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AH" . ($y - 3) . "+AH" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AK" . ($y - 3) . "+AK" . ($y - 1));
                    $x += 1;
                    // 車種内訳_アクセラ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AH" . ($y - 3) . "+AH" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AI" . ($y - 3) . "+AI" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AL" . ($y - 3) . "+AL" . ($y - 1));
                    $x += 1;
                    // 車種内訳_プレマシー
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AI" . ($y - 3) . "+AI" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AJ" . ($y - 3) . "+AJ" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AM" . ($y - 3) . "+AM" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ビアンテ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AJ" . ($y - 3) . "+AJ" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AK" . ($y - 3) . "+AK" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AN" . ($y - 3) . "+AN" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ＭＰＶ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AK" . ($y - 3) . "+AK" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AL" . ($y - 3) . "+AL" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AO" . ($y - 3) . "+AO" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ロードスター
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AL" . ($y - 3) . "+AL" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AM" . ($y - 3) . "+AM" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AP" . ($y - 3) . "+AP" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ファミリアバン
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AM" . ($y - 3) . "+AM" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AN" . ($y - 3) . "+AN" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AQ" . ($y - 3) . "+AQ" . ($y - 1));
                    $x += 1;
                    // 車種内訳_ボンゴ／ブローニィ
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AN" . ($y - 3) . "+AN" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AO" . ($y - 3) . "+AO" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AR" . ($y - 3) . "+AR" . ($y - 1));
                    $x += 1;
                    // 車種内訳_タイタン
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AO" . ($y - 3) . "+AO" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AP" . ($y - 3) . "+AP" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AS" . ($y - 3) . "+AS" . ($y - 1));
                    $x += 1;
                    // 車種内訳_軽自動車
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AP" . ($y - 3) . "+AP" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AQ" . ($y - 3) . "+AQ" . ($y - 1));
                    $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AT" . ($y - 3) . "+AT" . ($y - 1));
                    //20240712 LHB upd s
                    // $x += 1;
                    // 車種内訳_登録計
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AQ" . ($y - 3) . "+AQ" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AR" . ($y - 3) . "+AR" . ($y - 1));
                    // $objActSheet->setCellValueByColumnAndRow($x, $y + 1, "=AS" . ($y - 3) . "+AS" . ($y - 1));
                    if ($exit_cx80 === '1') {
                        $x += 1;
                        $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "=AU" . ($y - 3) . "+AU" . ($y - 1));
                    }
                    //20240712 LHB upd e
                    //20240611 LHB upd e
                    //20240326 caina upd e

                    $objActSheet->getStyle('A' . ($y + 1) . ':B' . ($y + 1))->applyFromArray($normalAlignLeftStyle);
                    $objActSheet->getStyle('C' . ($y + 1) . ':U' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    $objActSheet->getStyle('W' . ($y + 1) . ':X' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    $objActSheet->getStyle('Z' . ($y + 1) . ':AC' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    //20240326 caina upd s
                    $objActSheet->getStyle('AD' . ($y + 1) . ':AD' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AC' . ($y + 1) . ':AD' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AE' . ($y + 1) . ':AF' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AG' . ($y + 1) . ':AH' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AI' . ($y + 1) . ':AJ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AK' . ($y + 1) . ':AK' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AL' . ($y + 1) . ':AM' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AN' . ($y + 1) . ':AO' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AP' . ($y + 1) . ':AQ' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    $objActSheet->getStyle('AE' . ($y + 1) . ':AE' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    //20240611 caina upd s
                    // $objActSheet->getStyle('AD' . ($y + 1) . ':AE' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AF' . ($y + 1) . ':AG' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AH' . ($y + 1) . ':AI' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AJ' . ($y + 1) . ':AK' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AM' . ($y + 1) . ':AN' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AL' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AO' . ($y + 1) . ':AP' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AQ' . ($y + 1) . ':AR' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    //20240712 LHB upd s
                    // $objActSheet->getStyle('AD' . ($y + 1) . ':AD' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AE' . ($y + 1) . ':AF' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AG' . ($y + 1) . ':AH' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AI' . ($y + 1) . ':AJ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AK' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AM' . ($y + 1) . ':AM' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AN' . ($y + 1) . ':AO' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AP' . ($y + 1) . ':AQ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                    // $objActSheet->getStyle('AR' . ($y + 1) . ':AS' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    if ($exit_cx80 === '1') {
                        $objActSheet->getStyle('AF' . ($y + 1) . ':AF' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AG' . ($y + 1) . ':AH' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AI' . ($y + 1) . ':AJ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AK' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AM' . ($y + 1) . ':AN' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AO' . ($y + 1) . ':AO' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AP' . ($y + 1) . ':AQ' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AR' . ($y + 1) . ':AS' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AT' . ($y + 1) . ':AU' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    } else {
                        $objActSheet->getStyle('AD' . ($y + 1) . ':AE' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AF' . ($y + 1) . ':AG' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AH' . ($y + 1) . ':AI' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AJ' . ($y + 1) . ':AK' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AM' . ($y + 1) . ':AN' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AL' . ($y + 1) . ':AL' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AO' . ($y + 1) . ':AP' . ($y + 1))->applyFromArray($rightDoubleAlignRightStyle);
                        $objActSheet->getStyle('AQ' . ($y + 1) . ':AR' . ($y + 1))->applyFromArray($normalAlignRightStyle);
                    }
                    //20240712 LHB upd e
                    //20240611 LHB upd e
                    //20240326 caina upd e
                }
            }
            $objActSheet->getStyle('C10:D' . ($y + 1))->getNumberFormat()->setFormatCode('#,###;[red]-#,###');
            $objActSheet->getStyle('F10:K' . ($y + 1))->getNumberFormat()->setFormatCode('#,###;[red]-#,###');
            //20240326 caina upd s
            //20240611 LHB upd s
            // $objActSheet->getStyle('M10:AQ' . ($y + 1))->getNumberFormat()->setFormatCode('#,###;[red]-#,###');
            // $objActSheet->getStyle('M10:AR' . ($y + 1))->getNumberFormat()->setFormatCode('#,###;[red]-#,###');
            //20240712 LHB upd s
            // $objActSheet->getStyle('M10:AS' . ($y + 1))->getNumberFormat()->setFormatCode('#,###;[red]-#,###');
            if ($exit_cx80 === '1') {
                $objActSheet->getStyle('M10:AU' . ($y + 1))->getNumberFormat()->setFormatCode('#,###;[red]-#,###');
            } else {
                $objActSheet->getStyle('M10:AR' . ($y + 1))->getNumberFormat()->setFormatCode('#,###;[red]-#,###');
            }
            //20240712 LHB upd e
            //20240611 LHB upd e
            //20240326 caina upd e

            $objConditional1 = new Conditional();
            $objConditional1->setConditionType(Conditional::CONDITION_EXPRESSION);
            $objConditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
            $objConditional1->addCondition('AND(E10<>"",E10<>0,E10<0.8)');
            $objConditional1->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getEndColor()->setARGB('FFFF0000');
            $objConditional1->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
            $objConditional1->getStyle()->getNumberFormat()->setFormatCode('0%');

            $objConditional2 = new Conditional();
            $objConditional2->setConditionType(Conditional::CONDITION_EXPRESSION);
            $objConditional2->setOperatorType(Conditional::OPERATOR_EQUAL);
            $objConditional2->addCondition('AND(E10<>"",E10<>0,E10>=1)');
            $objConditional2->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getEndColor()->setARGB('FF0000FF');
            $objConditional2->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
            $objConditional2->getStyle()->getNumberFormat()->setFormatCode('0%');

            $conditionalStyles = $objActSheet->getStyle('E10:E' . ($y + 1))->getConditionalStyles();
            array_push($conditionalStyles, $objConditional1);
            array_push($conditionalStyles, $objConditional2);
            $objActSheet->getStyle('E10:E' . ($y + 1))->setConditionalStyles($conditionalStyles);

            $objConditional1 = new Conditional();
            $objConditional1->setConditionType(Conditional::CONDITION_EXPRESSION);
            $objConditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
            $objConditional1->addCondition('AND(L10<>"",L10<>0,L10<0.8)');
            $objConditional1->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getEndColor()->setARGB('FFFF0000');
            $objConditional1->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
            $objConditional1->getStyle()->getNumberFormat()->setFormatCode('0%');

            $objConditional2 = new Conditional();
            $objConditional2->setConditionType(Conditional::CONDITION_EXPRESSION);
            $objConditional2->setOperatorType(Conditional::OPERATOR_EQUAL);
            $objConditional2->addCondition('AND(L10<>"",L10<>0,L10>=1)');
            $objConditional2->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getEndColor()->setARGB('FF0000FF');
            $objConditional2->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
            $objConditional2->getStyle()->getNumberFormat()->setFormatCode('0%');

            $conditionalStyles = $objActSheet->getStyle('L10:L' . ($y + 1))->getConditionalStyles();
            array_push($conditionalStyles, $objConditional1);
            array_push($conditionalStyles, $objConditional2);
            $objActSheet->getStyle('L10:L' . ($y + 1))->setConditionalStyles($conditionalStyles);

            $x = 12;
            $y += 2;

            // 総登録台数
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "総登録台数");
            $objActSheet->mergeCells(Coordinate::stringFromColumnIndex($x + 1) . ($y + 1) . ':' . Coordinate::stringFromColumnIndex($x + 3) . ($y + 1));

            $x += 3;
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, (String) $TTD);
            $objActSheet->mergeCells(Coordinate::stringFromColumnIndex($x + 1) . ($y + 1) . ':' . Coordinate::stringFromColumnIndex($x + 3) . ($y + 1));

            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 1)->getFont()->setBold(true)->setSize(16);
            $x += 4;

            // 内レンタカー
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "内レンタカー");
            $objActSheet->mergeCells(Coordinate::stringFromColumnIndex($x + 1) . ($y + 1) . ':' . Coordinate::stringFromColumnIndex($x + 3) . ($y + 1));

            $x += 3;
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, $URC . " 台");
            $objActSheet->mergeCells(Coordinate::stringFromColumnIndex($x + 1) . ($y + 1) . ':' . Coordinate::stringFromColumnIndex($x + 3) . ($y + 1));

            $x = 12;
            $y += 1;
            // 総登録台数
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, "総売上台数");
            $objActSheet->mergeCells(Coordinate::stringFromColumnIndex($x + 1) . ($y + 1) . ':' . Coordinate::stringFromColumnIndex($x + 3) . ($y + 1));

            $x += 3;
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 1, (String) $TUD);
            $objActSheet->mergeCells(Coordinate::stringFromColumnIndex($x + 1) . ($y + 1) . ':' . Coordinate::stringFromColumnIndex($x + 3) . ($y + 1));

            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 1)->getFont()->setBold(true)->setSize(16);

            $objActSheet->getStyle('M' . $y . ':R' . ($y + 1))->applyFromArray($AlignCenterStyle);
            $objActSheet->getStyle('T' . $y . ':Y' . $y)->applyFromArray($AlignCenterStyle);

            $objActSheet->setSelectedCell("AE5");

            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $file = "files/HMTVE/" . $postData['txtbDuring'] . "年" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "月目標と実績.xlsx";

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

        $this->fncReturn($result);
    }

    public function makeGesshoExcel()
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

            //出力先パス
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;

            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            $strTemplatePath = $tmpPath1 . '/src/' . $strTemplatePath1 . "GESSYOKAIGI.xlsx";
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
                    if ($file != "." && $file != ".." && strpos($file, "月月初会議集計表") !== false) {
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
            // ----------------
            //  対象データ抽出
            // ----------------
            // 目標と実績
            $HMTVE260TargetResultList = new HMTVE260TargetResultList();
            $res = $HMTVE260TargetResultList->SQL1($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt1 = $this->sun181and183((array) $res['data']);

            // 車種内訳
            $res = $HMTVE260TargetResultList->SQL3($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt2 = $res['data'];

            // 目標と実績(小計)
            $res = $HMTVE260TargetResultList->SQL2($postData);
            if ($res['result'] == false) {
                throw new \Exception($res['data']);
            }
            $dt3 = $res['data'];

            $x = 0;
            $y = 3;
            $strTN = "";

            if ($postData['ddlMonth'] == 12) {
                $fileName = $tmpPath . ($postData['txtbDuring'] + 1) . "年" . "01" . "月月初会議集計表.xlsx";
            } else {
                $fileName = $tmpPath . $postData['txtbDuring'] . "年" . ($postData['ddlMonth'] + 1) . "月月初会議集計表.xlsx";
            }

            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = new XlsxReader();
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();

            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex(0 + 1) . '1', $postData['txtbDuring'] . "年" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "月実績　　　集　　計　　表");

            $leftRightAlignCenterBottomDot = array(
                'borders' => array(
                    'bottom' => array('borderStyle' => Border::BORDER_THIN),
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'horizontal' => array('borderStyle' => Border::BORDER_DOTTED),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ),
            );
            $leftAlignRightBottomDot = array(
                'borders' => array(
                    'bottom' => array('borderStyle' => Border::BORDER_THIN),
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_THIN),
                    'horizontal' => array('borderStyle' => Border::BORDER_DOTTED),
                    'vertical' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ),
            );
            $rightAlignRightBottomDot = array(
                'borders' => array(
                    'bottom' => array('borderStyle' => Border::BORDER_THIN),
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_THIN),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'vertical' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ),
            );
            $leftRightAlignRightBottomDot = array(
                'borders' => array(
                    'bottom' => array('borderStyle' => Border::BORDER_THIN),
                    'top' => array('borderStyle' => Border::BORDER_THIN),
                    'left' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'right' => array('borderStyle' => Border::BORDER_MEDIUM),
                    'horizontal' => array('borderStyle' => Border::BORDER_DOTTED),
                    'vertical' => array('borderStyle' => Border::BORDER_THIN),
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ),
            );
            $noFontBold = array(
                'font' => array(
                    'bold' => false,
                    'size' => 12
                ),
            );

            for ($i = 0; $i < count((array) $dt1); $i++) {
                if ($i == 0) {
                    $strTN .= "=";
                } else {
                    $strTN .= "+";
                }
                $strTN .= "A" . ($y + 3);

                // 罫線のみ設定
                $objActSheet->getStyle('A' . ($y + 2) . ':A' . ($y + 3))->applyFromArray($leftRightAlignCenterBottomDot);
                $objActSheet->getStyle('B' . ($y + 2) . ':E' . ($y + 3))->applyFromArray($leftAlignRightBottomDot);
                $objActSheet->getStyle('F' . ($y + 2) . ':G' . ($y + 3))->applyFromArray($rightAlignRightBottomDot);
                $objActSheet->getStyle('H' . ($y + 2) . ':Q' . ($y + 3))->applyFromArray($leftAlignRightBottomDot);
                $objActSheet->getStyle('R' . ($y + 2) . ':U' . ($y + 3))->applyFromArray($rightAlignRightBottomDot);
                $objActSheet->getStyle('V' . ($y + 2) . ':V' . ($y + 3))->applyFromArray($leftRightAlignRightBottomDot);
                $objActSheet->getStyle('W' . ($y + 2) . ':X' . ($y + 3))->applyFromArray($leftRightAlignRightBottomDot);
                $objActSheet->getStyle('Y' . ($y + 2) . ':AG' . ($y + 3))->applyFromArray($leftRightAlignRightBottomDot);
                $objActSheet->getStyle('A' . ($y + 3) . ':AG' . ($y + 3))->applyFromArray($noFontBold);

                // 部署
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNv($dt1[$i]['BUSYO_RYKNM']));
                // 人数
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, $this->ClsComFncHMTVE->FncNv($dt1[$i]['YSN_GK10']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . ($y + 3))->getNumberFormat()->setFormatCode('#,###;-#,###;0');
                $x += 1;
                // 総限界利益_目標
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['GENRI_MOKUHYO']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(B" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",B" . ($y + 2) . "/A" . ($y + 3) . ")");
                $x += 1;
                // 総限界利益_月末予想_↑↓→
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,B" . ($y + 2) . "=0),\"\",IF(D" . ($y + 2) . "=B" . ($y + 2) . ',"→",IF(D' . ($y + 2) . "-B" . ($y + 2) . '>0,"↑","↓")))');
                // 順位
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, $this->ClsComFncHMTVE->FncNz($dt1[$i]['GENRI_JUNI']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#,###');
                $objActSheet->getStyle('C' . ($y + 2) . ':C' . ($y + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $x += 1;
                // 総限界利益_月末予想
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['GENRI_JISSEKI']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(D" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",D" . ($y + 2) . "/A" . ($y + 3) . ")");
                $x += 1;
                // 前年実績
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['GENRI_JISSEKI_ZEN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(E" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",E" . ($y + 2) . "/A" . ($y + 3) . ")");
                $x += 1;
                // 対目標
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,B" . ($y + 2) . "=0),\"\",D" . ($y + 2) . "/B" . ($y + 2) . ")");
                $x += 1;
                // 対前年
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,E" . ($y + 2) . "=0),\"\",D" . ($y + 2) . "/E" . ($y + 2) . ")");
                $x += 1;
                // 台数_目標_メイン
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIMOKU_MAIN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(H" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",H" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 台数_目標_軽
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIMOKU_TACHANEL']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(I" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",I" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 台数_目標_合計
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=H" . ($y + 2) . "+I" . ($y + 2));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(J" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",J" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 台数_月末予想_メイン
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_MAIN_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_MAIN_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(K" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",K" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 台数_月末予想_軽
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_KEI_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_KEI_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(L" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",L" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 台数_月末予想_ボルボ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_VOLVO_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_VOLVO_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(M" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",M" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 台数_月末予想_他
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_SONOTA_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_SONOTA_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(N" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",N" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 台数_合計_↑↓→
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,J" . ($y + 2) . "=0),\"\",IF(P" . ($y + 2) . "=J" . ($y + 2) . ',"→",IF(P' . ($y + 2) . "-J" . ($y + 2) . '>0,"↑","↓")))');
                // 順位
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URIYOSOU_JUNI']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#,###');
                $objActSheet->getStyle('O' . ($y + 2) . ':O' . ($y + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $x += 1;
                // 台数_合計
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=K" . ($y + 2) . "+L" . ($y + 2) . "+M" . ($y + 2) . "+N" . ($y + 2));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(P" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",P" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 台数_前年実績
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['URI_DAI_Y_ZEN']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['URI_DAI_S_ZEN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(Q" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",Q" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 台数_対目標_メイン
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(K" . ($y + 2) . "=0,H" . ($y + 2) . "=0),\"\",K" . ($y + 2) . "/H" . ($y + 2) . ")");
                $x += 1;
                // 台数_対目標_軽
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(L" . ($y + 2) . "=0,I" . ($y + 2) . "=0),\"\",L" . ($y + 2) . "/I" . ($y + 2) . ")");
                $x += 1;
                // 台数_対目標_合計
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,J" . ($y + 2) . "=0),\"\",P" . ($y + 2) . "/J" . ($y + 2) . ")");
                $x += 1;
                // 台数_対前年
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,Q" . ($y + 2) . "=0),\"\",P" . ($y + 2) . "/Q" . ($y + 2) . ")");
                $x += 1;

                // 台数_登録台数
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRK_GK_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['TRK_GK_S']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['KEI_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt1[$i]['KEI_TRK_DAISU_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(V" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",V" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;

                // 中古売上台数_直売
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['CHOKU_Y']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(W" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",W" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;

                // 中古売上台数_業売
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['GYOBAI_Y']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(X" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",X" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;

                // 周辺利益_自動車保険
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['SHURI_HOKEN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(Y" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",Y" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 周辺利益_再リース奨励金
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['SHURI_LEASE']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(Z" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",Z" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 周辺利益_ローンＫＢ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['SHURI_LOAN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AA" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",AA" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 周辺利益_希望Ｎｏ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['SHURI_KIBOU']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AB" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",AB" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 周辺利益_パックで７５３
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['SHURI_P753']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AC" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",AC" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 周辺利益_パックでメンテ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['SHURI_PMENTE']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AD" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",AD" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 周辺利益_ボディーコート
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['SHURI_BODYCOAT']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AE" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",AE" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 周辺利益_ＪＡＦ加入
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['SHURI_JAF']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AF" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",AF" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
                $x += 1;
                // 周辺利益_ＯＳＳ加入
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt1[$i]['SHURI_OSS']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                // 一人当たり
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AG" . ($y + 2) . "=0,A" . ($y + 3) . "=0),\"\",AG" . ($y + 2) . "/A" . ($y + 3) . ")");
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');

                $x = 0;
                $y += 2;
            }
            $x = 0;
            // ' ************
            // ' *** 小計 ***
            // ' ************
            // 拠点名
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "小計");
            // 人数
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, $strTN);
            $x += 1;

            for ($i = 0; $i < count((array) $dt3); $i++) {
                // 罫線のみ設定
                $objActSheet->getStyle('A' . ($y + 2) . ':A' . ($y + 3))->applyFromArray($leftRightAlignCenterBottomDot);
                $objActSheet->getStyle('B' . ($y + 2) . ':E' . ($y + 3))->applyFromArray($leftAlignRightBottomDot);
                $objActSheet->getStyle('F' . ($y + 2) . ':G' . ($y + 3))->applyFromArray($rightAlignRightBottomDot);
                $objActSheet->getStyle('H' . ($y + 2) . ':Q' . ($y + 3))->applyFromArray($leftAlignRightBottomDot);
                $objActSheet->getStyle('R' . ($y + 2) . ':U' . ($y + 3))->applyFromArray($rightAlignRightBottomDot);
                $objActSheet->getStyle('V' . ($y + 2) . ':V' . ($y + 3))->applyFromArray($leftRightAlignRightBottomDot);
                $objActSheet->getStyle('W' . ($y + 2) . ':X' . ($y + 3))->applyFromArray($leftRightAlignRightBottomDot);
                $objActSheet->getStyle('Y' . ($y + 2) . ':AG' . ($y + 3))->applyFromArray($leftRightAlignRightBottomDot);
                $objActSheet->getStyle('A' . ($y + 3) . ':AG' . ($y + 3))->applyFromArray($noFontBold);

                // 総限界利益_目標
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['GENRI_MOKUHYO']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 総限界利益_月末予想_↑↓→
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,B" . ($y + 2) . "=0),\"\",IF(D" . ($y + 2) . "=B" . ($y + 2) . ',"→",IF(D' . ($y + 2) . "-B" . ($y + 2) . '>0,"↑","↓")))');
                $objActSheet->getStyle('C' . ($y + 2) . ':C' . ($y + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $x += 1;
                // 総限界利益_月末予想
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['GENRI_JISSEKI']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 前年実績
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['GENRI_JISSEKI_ZEN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 対目標
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,B" . ($y + 2) . "=0),\"\",D" . ($y + 2) . "/B" . ($y + 2) . ")");
                $x += 1;
                // 対前年
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,E" . ($y + 2) . "=0),\"\",D" . ($y + 2) . "/E" . ($y + 2) . ")");
                $x += 1;
                // 台数_目標_メイン
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIMOKU_MAIN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_目標_軽
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIMOKU_TACHANEL']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_目標_合計
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=H" . ($y + 2) . "+I" . ($y + 2));
                $x += 1;
                // 台数_月末予想_メイン
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIYOSOU_MAIN_Y']) + $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIYOSOU_MAIN_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_月末予想_軽
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIYOSOU_KEI_Y']) + $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIYOSOU_KEI_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_月末予想_ボルボ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIYOSOU_VOLVO_Y']) + $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIYOSOU_VOLVO_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_月末予想_他
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIYOSOU_SONOTA_Y']) + $this->ClsComFncHMTVE->FncNz($dt3[$i]['URIYOSOU_SONOTA_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_合計_↑↓→
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,J" . ($y + 2) . "=0),\"\",IF(P" . ($y + 2) . "=J" . ($y + 2) . ',"→",IF(P' . ($y + 2) . "-J" . ($y + 2) . '>0,"↑","↓")))');
                $objActSheet->getStyle('O' . ($y + 2) . ':O' . ($y + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $x += 1;
                // 台数_合計
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=K" . ($y + 2) . "+L" . ($y + 2) . "+M" . ($y + 2) . "+N" . ($y + 2));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_前年実績
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['URI_DAI_Y_ZEN']) + $this->ClsComFncHMTVE->FncNz($dt3[$i]['URI_DAI_S_ZEN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_対目標_メイン
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(K" . ($y + 2) . "=0,H" . ($y + 2) . "=0),\"\",K" . ($y + 2) . "/H" . ($y + 2) . ")");
                $x += 1;
                // 台数_対目標_軽
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(L" . ($y + 2) . "=0,I" . ($y + 2) . "=0),\"\",L" . ($y + 2) . "/I" . ($y + 2) . ")");
                $x += 1;
                // 台数_対目標_合計
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,J" . ($y + 2) . "=0),\"\",P" . ($y + 2) . "/J" . ($y + 2) . ")");
                $x += 1;
                // 台数_対前年
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,Q" . ($y + 2) . "=0),\"\",P" . ($y + 2) . "/Q" . ($y + 2) . ")");
                $x += 1;

                // 台数_登録台数
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['TRK_GK_Y']) + $this->ClsComFncHMTVE->FncNz($dt3[$i]['TRK_GK_S']) + $this->ClsComFncHMTVE->FncNz($dt3[$i]['KEI_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt3[$i]['KEI_TRK_DAISU_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;

                // 中古売上台数_直売
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['CHOKU_Y']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;

                // 中古売上台数_業売
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['GYOBAI_Y']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;

                // 周辺利益_自動車保険
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['SHURI_HOKEN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_再リース奨励金
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['SHURI_LEASE']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_ローンＫＢ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['SHURI_LOAN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_希望Ｎｏ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['SHURI_KIBOU']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_パックで７５３
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['SHURI_P753']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_パックでメンテ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['SHURI_PMENTE']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_ボディーコート
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['SHURI_BODYCOAT']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_ＪＡＦ加入
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['SHURI_JAF']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_ＯＳＳ加入
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt3[$i]['SHURI_OSS']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');

            }
            // 小計を非表示
            $objActSheet->getRowDimension($y + 2)->setVisible(false);
            $objActSheet->getRowDimension($y + 3)->setVisible(false);

            $x = 0;
            $y += 2;
            // ' ************
            // ' *** その他 ***
            // ' ************
            // 拠点名
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "その他");
            $x += 1;
            for ($i = 0; $i < count((array) $dt2); $i++) {
                // 罫線のみ設定
                $objActSheet->getStyle('A' . ($y + 2) . ':A' . ($y + 2))->applyFromArray($leftRightAlignCenterBottomDot);
                $objActSheet->getStyle('B' . ($y + 2) . ':E' . ($y + 2))->applyFromArray($leftAlignRightBottomDot);
                $objActSheet->getStyle('F' . ($y + 2) . ':G' . ($y + 2))->applyFromArray($rightAlignRightBottomDot);
                $objActSheet->getStyle('H' . ($y + 2) . ':Q' . ($y + 2))->applyFromArray($leftAlignRightBottomDot);
                $objActSheet->getStyle('R' . ($y + 2) . ':U' . ($y + 2))->applyFromArray($rightAlignRightBottomDot);
                $objActSheet->getStyle('V' . ($y + 2) . ':V' . ($y + 2))->applyFromArray($leftRightAlignRightBottomDot);
                $objActSheet->getStyle('W' . ($y + 2) . ':X' . ($y + 2))->applyFromArray($leftRightAlignRightBottomDot);
                $objActSheet->getStyle('Y' . ($y + 2) . ':AG' . ($y + 2))->applyFromArray($leftRightAlignRightBottomDot);

                // 総限界利益_目標
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['GENRI_MOKUHYO']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 総限界利益_月末予想_↑↓→
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,B" . ($y + 2) . "=0),\"\",IF(D" . ($y + 2) . "=B" . ($y + 2) . ',"→",IF(D' . ($y + 2) . "-B" . ($y + 2) . '>0,"↑","↓")))');
                $objActSheet->getStyle('C' . ($y + 2) . ':C' . ($y + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $x += 1;
                // 総限界利益_月末予想
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['GENRI_JISSEKI']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 前年実績
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['GENRI_JISSEKI_ZEN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 対目標
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,B" . ($y + 2) . "=0),\"\",D" . ($y + 2) . "/B" . ($y + 2) . ")");
                $x += 1;
                // 対前年
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,E" . ($y + 2) . "=0),\"\",D" . ($y + 2) . "/E" . ($y + 2) . ")");
                $x += 1;
                // 台数_目標_メイン
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIMOKU_MAIN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_目標_軽
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIMOKU_TACHANEL']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_目標_合計
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=H" . ($y + 2) . "+I" . ($y + 2));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_月末予想_メイン
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIYOSOU_MAIN_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIYOSOU_MAIN_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_月末予想_軽
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIYOSOU_KEI_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIYOSOU_KEI_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_月末予想_ボルボ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIYOSOU_VOLVO_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIYOSOU_VOLVO_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_月末予想_他
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIYOSOU_SONOTA_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['URIYOSOU_SONOTA_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_合計_↑↓→
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,J" . ($y + 2) . "=0),\"\",IF(P" . ($y + 2) . "=J" . ($y + 2) . ',"→",IF(P' . ($y + 2) . "-J" . ($y + 2) . '>0,"↑","↓")))');
                $objActSheet->getStyle('O' . ($y + 2) . ':O' . ($y + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $x += 1;
                // 台数_合計
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=K" . ($y + 2) . "+L" . ($y + 2) . "+M" . ($y + 2) . "+N" . ($y + 2));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_前年実績
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['URI_DAI_Y_ZEN']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['URI_DAI_S_ZEN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 台数_対目標_メイン
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(K" . ($y + 2) . "=0,H" . ($y + 2) . "=0),\"\",K" . ($y + 2) . "/H" . ($y + 2) . ")");
                $x += 1;
                // 台数_対目標_軽
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(L" . ($y + 2) . "=0,I" . ($y + 2) . "=0),\"\",L" . ($y + 2) . "/I" . ($y + 2) . ")");
                $x += 1;
                // 台数_対目標_合計
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,J" . ($y + 2) . "=0),\"\",P" . ($y + 2) . "/J" . ($y + 2) . ")");
                $x += 1;
                // 台数_対前年
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,Q" . ($y + 2) . "=0),\"\",P" . ($y + 2) . "/Q" . ($y + 2) . ")");
                $x += 1;

                // 台数_登録台数
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['TRK_GK_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['TRK_GK_S']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['KEI_TRK_DAISU_Y']) + $this->ClsComFncHMTVE->FncNz($dt2[$i]['KEI_TRK_DAISU_S']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;

                // 中古売上台数_直売
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['CHOKU_Y']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 中古売上台数_業売
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['GYOBAI_Y']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;

                // 周辺利益_自動車保険
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['SHURI_HOKEN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_再リース奨励金
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['SHURI_LEASE']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_ローンＫＢ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['SHURI_LOAN']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_希望Ｎｏ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['SHURI_KIBOU']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_パックで７５３
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['SHURI_P753']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_パックでメンテ
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['SHURI_PMENTE']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_ボディーコート
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['SHURI_BODYCOAT']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_ＪＡＦ加入
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['SHURI_JAF']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
                $x += 1;
                // 周辺利益_ＯＳＳ加入
                $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, $this->ClsComFncHMTVE->FncNz($dt2[$i]['SHURI_OSS']));
                $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');

            }

            // ' ************
            // ' *** 合計 ***
            // ' ************
            $x = 0;
            $y += 1;
            // 罫線のみ設定
            $objActSheet->getStyle('A' . ($y + 2) . ':A' . ($y + 3))->applyFromArray($leftRightAlignCenterBottomDot);
            $objActSheet->getStyle('B' . ($y + 2) . ':E' . ($y + 3))->applyFromArray($leftAlignRightBottomDot);
            $objActSheet->getStyle('F' . ($y + 2) . ':G' . ($y + 3))->applyFromArray($rightAlignRightBottomDot);
            $objActSheet->getStyle('H' . ($y + 2) . ':Q' . ($y + 3))->applyFromArray($leftAlignRightBottomDot);
            $objActSheet->getStyle('R' . ($y + 2) . ':U' . ($y + 3))->applyFromArray($rightAlignRightBottomDot);
            $objActSheet->getStyle('V' . ($y + 2) . ':V' . ($y + 3))->applyFromArray($leftRightAlignRightBottomDot);
            $objActSheet->getStyle('W' . ($y + 2) . ':X' . ($y + 3))->applyFromArray($leftRightAlignRightBottomDot);
            $objActSheet->getStyle('Y' . ($y + 2) . ':AG' . ($y + 3))->applyFromArray($leftRightAlignRightBottomDot);
            $objActSheet->getStyle('A' . ($y + 3) . ':AG' . ($y + 3))->applyFromArray($noFontBold);
            $objActSheet->getStyle('A' . ($y + 2) . ':AG' . ($y + 2))->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
            $objActSheet->getStyle('A' . ($y + 3) . ':AG' . ($y + 3))->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

            // 拠点名
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "合計");
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "一人当り平均");
            $x += 1;
            // 総限界利益_目標
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(B" . ($y - 1) . "+B" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(B" . ($y + 2) . "=0,A" . $y . "=0),\"\",B" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 総限界利益_月末予想_↑↓→
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,B" . ($y + 2) . "=0),\"\",IF(D" . ($y + 2) . "=B" . ($y + 2) . ',"→",IF(D' . ($y + 2) . "-B" . ($y + 2) . '>0,"↑","↓")))');
            $objActSheet->getStyle('C' . ($y + 2) . ':C' . ($y + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $x += 1;
            // 総限界利益_月末予想
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(D" . ($y - 1) . "+D" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(D" . ($y + 2) . "=0,A" . $y . "=0),\"\",D" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 前年実績
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(E" . ($y - 1) . "+E" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(E" . ($y + 2) . "=0,A" . $y . "=0),\"\",E" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 対目標
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,B" . ($y + 2) . "=0),\"\",D" . ($y + 2) . "/B" . ($y + 2) . ")");
            $x += 1;
            // 対前年
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(D" . ($y + 2) . "=0,E" . ($y + 2) . "=0),\"\",D" . ($y + 2) . "/E" . ($y + 2) . ")");
            $x += 1;
            // 台数_目標_メイン
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(H" . ($y - 1) . "+H" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(H" . ($y + 2) . "=0,A" . $y . "=0),\"\",H" . ($y + 2) . "/A" . $y . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
            $x += 1;
            // 台数_目標_軽
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(I" . ($y - 1) . "+I" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(I" . ($y + 2) . "=0,A" . $y . "=0),\"\",I" . ($y + 2) . "/A" . $y . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
            $x += 1;
            // 台数_目標_合計
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(J" . ($y - 1) . "+J" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(J" . ($y + 2) . "=0,A" . $y . "=0),\"\",J" . ($y + 2) . "/A" . $y . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
            $x += 1;
            // 台数_月末予想_メイン
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(K" . ($y - 1) . "+K" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(K" . ($y + 2) . "=0,A" . $y . "=0),\"\",K" . ($y + 2) . "/A" . $y . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
            $x += 1;
            // 台数_月末予想_軽
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(L" . ($y - 1) . "+L" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(L" . ($y + 2) . "=0,A" . $y . "=0),\"\",L" . ($y + 2) . "/A" . $y . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
            $x += 1;
            // 台数_月末予想_ボルボ
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(M" . ($y - 1) . "+M" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(M" . ($y + 2) . "=0,A" . $y . "=0),\"\",M" . ($y + 2) . "/A" . $y . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
            $x += 1;
            // 台数_月末予想_他
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(N" . ($y - 1) . "+N" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(N" . ($y + 2) . "=0,A" . $y . "=0),\"\",N" . ($y + 2) . "/A" . $y . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
            $x += 1;
            // 台数_合計_↑↓→
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,J" . ($y + 2) . "=0),\"\",IF(P" . ($y + 2) . "=J" . ($y + 2) . ',"→",IF(P' . ($y + 2) . "-J" . ($y + 2) . '>0,"↑","↓")))');
            $objActSheet->getStyle('O' . ($y + 2) . ':O' . ($y + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $x += 1;
            // 台数_合計
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(P" . ($y - 1) . "+P" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(P" . ($y + 2) . "=0,A" . $y . "=0),\"\",P" . ($y + 2) . "/A" . $y . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
            $x += 1;
            // 台数_前年実績
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(Q" . ($y - 1) . "+Q" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(Q" . ($y + 2) . "=0,A" . $y . "=0),\"\",Q" . ($y + 2) . "/A" . $y . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 3)->getNumberFormat()->setFormatCode('#0.0');
            $x += 1;
            // 台数_対目標_メイン
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(K" . ($y + 2) . "=0,H" . ($y + 2) . "=0),\"\",K" . ($y + 2) . "/H" . ($y + 2) . ")");
            $x += 1;
            // 台数_対目標_軽
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(L" . ($y + 2) . "=0,I" . ($y + 2) . "=0),\"\",L" . ($y + 2) . "/I" . ($y + 2) . ")");
            $x += 1;
            // 台数_対目標_合計
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,J" . ($y + 2) . "=0),\"\",P" . ($y + 2) . "/J" . ($y + 2) . ")");
            $x += 1;
            // 台数_対前年
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=IF(OR(P" . ($y + 2) . "=0,Q" . ($y + 2) . "=0),\"\",P" . ($y + 2) . "/Q" . ($y + 2) . ")");
            $x += 1;
            // 台数_登録台数
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(V" . ($y - 1) . "+V" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(V" . ($y + 2) . "=0,A" . $y . "=0),\"\",V" . ($y + 2) . "/A" . $y . ")");
            $x += 1;

            // 中古売上台数_直売
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(W" . ($y - 1) . "+W" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(W" . ($y + 2) . "=0,A" . $y . "=0),\"\",W" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 中古売上台数_業売
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(X" . ($y - 1) . "+X" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(X" . ($y + 2) . "=0,A" . $y . "=0),\"\",X" . ($y + 2) . "/A" . $y . ")");
            $x += 1;

            // 周辺利益_自動車保険
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(Y" . ($y - 1) . "+Y" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(Y" . ($y + 2) . "=0,A" . $y . "=0),\"\",Y" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 周辺利益_再リース奨励金
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(Z" . ($y - 1) . "+Z" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(Z" . ($y + 2) . "=0,A" . $y . "=0),\"\",Z" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 周辺利益_ローンＫＢ
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(AA" . ($y - 1) . "+AA" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AA" . ($y + 2) . "=0,A" . $y . "=0),\"\",AA" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 周辺利益_希望Ｎｏ
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(AB" . ($y - 1) . "+AB" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AB" . ($y + 2) . "=0,A" . $y . "=0),\"\",AB" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 周辺利益_パックで７５３
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(AC" . ($y - 1) . "+AC" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AC" . ($y + 2) . "=0,A" . $y . "=0),\"\",AC" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 周辺利益_パックでメンテ
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(AD" . ($y - 1) . "+AD" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AD" . ($y + 2) . "=0,A" . $y . "=0),\"\",AD" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 周辺利益_ボディーコート
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(AE" . ($y - 1) . "+AE" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AE" . ($y + 2) . "=0,A" . $y . "=0),\"\",AE" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 周辺利益_ＪＡＦ加入
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(AF" . ($y - 1) . "+AF" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AF" . ($y + 2) . "=0,A" . $y . "=0),\"\",AF" . ($y + 2) . "/A" . $y . ")");
            $x += 1;
            // 周辺利益_Ｍ'ｓカード加入
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 2, "=(AG" . ($y - 1) . "+AG" . ($y + 1) . ")");
            $objActSheet->getStyle(Coordinate::stringFromColumnIndex($x + 1) . $y + 2)->getNumberFormat()->setFormatCode('#,###');
            // 一人当たり
            $objActSheet->setCellValue(Coordinate::stringFromColumnIndex($x + 1) . $y + 3, "=IF(OR(AG" . ($y + 2) . "=0,A" . $y . "=0),\"\",AG" . ($y + 2) . "/A" . $y . ")");
            $x += 1;

            $objConditional1 = new Conditional();
            $objConditional1->setConditionType(Conditional::CONDITION_CELLIS);
            $objConditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
            $objConditional1->addCondition('"↑"');
            $objConditional1->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getEndColor()->setARGB('FF00FFFF');

            // 创建条件格式2
            $objConditional2 = new Conditional();
            $objConditional2->setConditionType(Conditional::CONDITION_CELLIS);
            $objConditional2->setOperatorType(Conditional::OPERATOR_EQUAL);
            $objConditional2->addCondition('"→"');
            $objConditional2->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getEndColor()->setARGB('FF00FFFF');

            // 创建条件格式3
            $objConditional3 = new Conditional();
            $objConditional3->setConditionType(Conditional::CONDITION_CELLIS);
            $objConditional3->setOperatorType(Conditional::OPERATOR_EQUAL);
            $objConditional3->addCondition('"↓"');
            $objConditional3->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getEndColor()->setARGB('FFFF0000');

            $conditionalStyles = $objActSheet->getStyle('C5:E' . ($y + 1))->getConditionalStyles();
            array_push($conditionalStyles, $objConditional1);
            array_push($conditionalStyles, $objConditional2);
            array_push($conditionalStyles, $objConditional3);
            $objActSheet->getStyle('C5:C' . ($y + 3))->setConditionalStyles($conditionalStyles);
            $objActSheet->getStyle('O5:O' . ($y + 3))->setConditionalStyles($conditionalStyles);

            $objActSheet->setSelectedCell("AE6");

            //ブック作成
            $objWriter = new XlsxWriter($objPHPExcel);
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            if ($postData['ddlMonth'] == 12) {
                $file = "files/HMTVE/" . ($postData['txtbDuring'] + 1) . "年" . "01" . "月月初会議集計表.xlsx";
            } else {
                $file = "files/HMTVE/" . $postData['txtbDuring'] . "年" . ($postData['ddlMonth'] + 1) . "月月初会議集計表.xlsx";
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

        $this->fncReturn($result);
    }

    public function sun181and183(array $results)
    {
        $mergedIndex = null;

        $skip = [
            'BUSYO_CD',
            'BUSYO_CD_1',
            'BUSYO_RYKNM',
            'GENRI_JUNI',
            'URIYOSOU_JUNI',
        ];

        foreach ($results as $index => $row) {
            if ($row['BUSYO_CD'] != 180) {
                continue;
            }

            if ($mergedIndex === null) {
                $mergedIndex = $index;
                $results[$mergedIndex]['BUSYO_CD_1'] = null;
                continue;
            }

            foreach ($row as $key => $value) {
                if (in_array($key, $skip, true)) {
                    continue;
                }

                if (is_numeric($value)) {
                    $results[$mergedIndex][$key] += $value;
                }
            }

            unset($results[$index]);
        }

        return array_values($results);
    }

}
