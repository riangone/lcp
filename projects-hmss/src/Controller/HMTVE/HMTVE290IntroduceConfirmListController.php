<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE290IntroduceConfirmList;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
//*******************************************
// * sample controller
//*******************************************
class HMTVE290IntroduceConfirmListController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    private $Session;
    private $HMTVE290IntroduceConfirmList;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
        $this->loadComponent('ClsLogControl');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE290IntroduceConfirmList_layout');
    }
    //Jqgrid
    public function fncSearchSpread()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            if (isset($_POST['request'])) {
                $this->HMTVE290IntroduceConfirmList = new HMTVE290IntroduceConfirmList();
                $result = $this->HMTVE290IntroduceConfirmList->CreateIntroducerSql($_POST['request']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFncHMTVE->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);

                $this->fncReturn($tmpJqgrid);
            } else {
                $result = array(
                    'result' => TRUE,
                    'data' => array()
                );
                $this->fncReturn($result);
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();

            $this->fncReturn($result);
        }
    }

    //画面初期化
    public function pageLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->Session = $this->request->getSession();
            $this->HMTVE290IntroduceConfirmList = new HMTVE290IntroduceConfirmList();
            $HDTINTRODUCEDATA = $this->HMTVE290IntroduceConfirmList->getObjectTerm();
            if (!$HDTINTRODUCEDATA['result']) {
                throw new \Exception($HDTINTRODUCEDATA['data']);
            }
            $result['data']['HDTINTRODUCEDATA'] = $HDTINTRODUCEDATA['data'];
            //店舗名
            $ExpressShopName = $this->HMTVE290IntroduceConfirmList->ExpressShopName($_POST['data']['txtPosition']);
            if (!$ExpressShopName['result']) {
                throw new \Exception($ExpressShopName['data']);
            }
            $result['data']['ExpressShopName'] = $ExpressShopName['data'];
            //部署
            $FoucsMove = $this->HMTVE290IntroduceConfirmList->FoucsMove();
            if (!$FoucsMove['result']) {
                throw new \Exception($FoucsMove['data']);
            }
            $result['data']['HBUSYO'] = $FoucsMove['data'];

            $result['data']['BusyoCD'] = $this->Session->read('BusyoCD');
            if (!isset($result['data']['BusyoCD'])) {
                $result['data']['message'] = 'W9999';
                throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
            }
            $result['data']['SyainNM'] = $this->Session->read('SyainNM');
            if (!isset($result['data']['SyainNM'])) {
                $result['data']['message'] = 'W9999';
                throw new \Exception('表示できる社員が存在しません。管理者にお問い合わせください。');
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：登録ボタンクリックのイベント
    //'関 数 名：btnLogin_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：入力データの登録
    //'**********************************************************************
    public function btnLoginClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $tranStartFlg = FALSE;
        $this->HMTVE290IntroduceConfirmList = new HMTVE290IntroduceConfirmList();
        try {
            $this->Session = $this->request->getSession();
            //トランザクション開始
            $this->HMTVE290IntroduceConfirmList->Do_transaction();
            $tranStartFlg = TRUE;
            if ($this->Session->read('PatternID') == $_POST['data']['CONST_ADMIN_PTN_NO'] || $this->Session->read('PatternID') == $_POST['data']['CONST_HONBU_PTN_NO'] || $this->Session->read('PatternID') == $_POST['data']['CONST_TESTER_PTN_NO']) {
                if (isset($_POST['data']['jqgrid'])) {
                    foreach ($_POST['data']['jqgrid'] as $value) {
                        $update = $this->HMTVE290IntroduceConfirmList->btnLogin_Click1($value);
                        if (!$update['result']) {
                            throw new \Exception($update['data']);
                        }
                    }
                }
            } else
                if ($this->Session->read('PatternID') != $_POST['data']['CONST_ADMIN_PTN_NO'] || $this->Session->read('PatternID') != $_POST['data']['CONST_HONBU_PTN_NO'] || $this->Session->read('PatternID') != $_POST['data']['CONST_TESTER_PTN_NO']) {
                    if (isset($_POST['data']['jqgrid'])) {
                        foreach ($_POST['data']['jqgrid'] as $value) {
                            $update = $this->HMTVE290IntroduceConfirmList->btnLogin_Click2($value);
                            if (!$update['result']) {
                                throw new \Exception($update['data']);
                            }
                        }
                    }
                }
            $update = $this->HMTVE290IntroduceConfirmList->btnLogin_Click_Other($_POST['data']);
            if (!$update['result']) {
                throw new \Exception($update['data']);
            }

            //コミット
            $this->HMTVE290IntroduceConfirmList->Do_commit();

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE290IntroduceConfirmList->Do_rollback();
            }

            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：確認済みへボタンクリックのイベント
    //'関 数 名：btnConfirm_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：入力データの登録
    public function btnConfirmClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $tranStartFlg = FALSE;
        $this->HMTVE290IntroduceConfirmList = new HMTVE290IntroduceConfirmList();
        try {
            //トランザクション開始
            $this->HMTVE290IntroduceConfirmList->Do_transaction();
            $tranStartFlg = TRUE;
            if (isset($_POST['data']['jqgrid'])) {
                foreach ($_POST['data']['jqgrid'] as $value) {
                    $update = $this->HMTVE290IntroduceConfirmList->btnConfirm_Click($value);
                    if (!$update['result']) {
                        throw new \Exception($update['data']);
                    }
                }
                $update = $this->HMTVE290IntroduceConfirmList->btnLogin_Click_Other($_POST['data']);
                if (!$update['result']) {
                    throw new \Exception($update['data']);
                }
            }
            //コミット
            $this->HMTVE290IntroduceConfirmList->Do_commit();

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE290IntroduceConfirmList->Do_rollback();
            }

            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //Excel出力ボタンクリック
    public function btnExcelClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $intState = 0;
        $lngCount = 0;
        try {
            $intState = 9;
            $this->HMTVE290IntroduceConfirmList = new HMTVE290IntroduceConfirmList();
            $strExcel = $this->HMTVE290IntroduceConfirmList->getCreateSql($_POST['data']);
            if (!$strExcel['result']) {
                throw new \Exception($strExcel['data']);
            }
            //抽出結果＝0件の場合
            if ($strExcel['row'] == 0) {
                $intState = 1;
                $lngCount = 0;
                throw new \Exception("W0024");
            }
            $lngCount = $strExcel['row'];
            //出力先パス
            $basePath = dirname(dirname(dirname(__FILE__)));
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = dirname($basePath) . "/" . $tmpPath2;
            //③一時ファイル名生成
            $strFileName = "紹介者確認データ.xls";
            //チェックを行う
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && $file == $strFileName) {
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
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }
            //②テンプレートファイルのパスを取得する
            $strTemplatePath = $this->ClsComFncHMTVE->FncGetPath('HmtveExcelLayoutPath');
            $strMotoPath = $basePath . '/' . $strTemplatePath . 'SYOUKAIKAKUNINDATA.xls';
            if (!file_exists($strMotoPath)) {
                throw new \Exception('W9999');
            }
            //④一時保存先のフルパス取得
            $strFilePath = $tmpPath . $strFileName;
            //⑤EXCEL生成
            $result = $this->CreateExcelData($strExcel['data'], $strFilePath, $strMotoPath, $_POST['data'], $strFileName, $intState);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
            $intState = $result["intState"];
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        try {
            $strTai = "";
            if ($_POST['data']['rdoConfirm'] == "true") {
                $strTai = "確認済み";
            } else
                if ($_POST['data']['rdoNotConfirm'] == "true") {
                    $strTai = "未確認";
                } else
                    if ($_POST['data']['rdoTwo'] == "true") {
                        $strTai = "両方";
                    }
            //部署別人件費明細書
            $resultLog = $this->ClsLogControl->fncLogEntryHMTVE("HMTVE290_IntroduceConfirmList", $intState, $lngCount, $_POST['data']['txtPosition'], $_POST['data']['ddlYear'], $_POST['data']['ddlMonth'], $_POST['data']['ddlDay'], $_POST['data']['ddlYear2'], $_POST['data']['ddlMonth2'], $_POST['data']['ddlDay2'], $strTai);
            if (!$resultLog['result']) {
                throw new \Exception($resultLog['Msg']);
            }
        } catch (\Exception $e1) {
            $result['result'] = FALSE;
            $result['error'] = $e1->getMessage();
        }

        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：Excelデータ
    //'関 数 名：CreateExcelData
    //'引 数 　：strSql
    //'戻 り 値：なし
    //'処理説明：Excelデータを作ります
    //'**********************************************************************
    private function CreateExcelData($dt, $strFilePath, $strMotoPath, $postData, $strFileName, $intState)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strMotoPath);
            //該当シートを指定
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', "紹介者確認一覧");
            $objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getFont()->setSize(14);
            $objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getFont()->setName('ＭＳ Ｐゴシック');
            $objPHPExcel->getActiveSheet()->mergeCells("A1:B1");
            $objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $objActSheet->setCellValue('A2', "対象");
            $objPHPExcel->getActiveSheet()->getStyle("A2" . ":" . "A2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("A2" . ":" . "A2")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            //画面項目NO13.対象_未確認が選択されている場合は、"未確認"
            //画面項目NO14.対象_確認済みが選択されている場合は、"確認済み"
            //画面項目NO15.対象_両方が選択されている場合は、"両方"
            if ($postData['rdoNotConfirm'] == "true") {
                $objActSheet->setCellValue('B2', "未確認");
            } elseif ($postData['rdoConfirm'] == "true") {
                $objActSheet->setCellValue('B2', "確認済み");
            } else {
                $objActSheet->setCellValue('B2', "両方");
            }

            $objActSheet->setCellValue('A3', "部署");
            //画面項目NO6.部署名＝""の場合、"選択されていません"　以外、画面項目NO6.部署名
            if ($postData['lblPosition'] == "") {
                $objActSheet->setCellValue('B3', "選択されていません");
            } else {
                $objActSheet->setCellValue('B3', $postData['lblPosition']);
            }
            $objPHPExcel->getActiveSheet()->getStyle("B2" . ":" . "B3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle("B2" . ":" . "B3")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $objActSheet->setCellValue('A4', "日付");
            $objPHPExcel->getActiveSheet()->getStyle("A3" . ":" . "A4")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("A3" . ":" . "A4")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            //画面項目NO7=""　又は　画面項目NO10=""の場合,"選択されていません"
            //以外
            //画面項目NO7 & "/" & 画面項目NO8 & "/" & 画面項目NO9 & "～" & 画面項目NO10 & "/" & 画面項目NO11 & "/" & 画面項目NO12
            if ($postData['ddlYear'] != "" || $postData['ddlYear2'] != "") {
                $objActSheet->setCellValue('B4', $postData['ddlYear'] . "/" . $postData['ddlMonth'] . "/" . $postData['ddlDay'] . "～" . $postData['ddlYear2'] . "/" . $postData['ddlMonth2'] . "/" . $postData['ddlDay2']);
            } else {
                $objActSheet->setCellValue('B4', "選択されていません");
            }
            $objActSheet->setCellValue('A6', "受理№");
            $objActSheet->setCellValue('B6', "受理日");
            $objActSheet->setCellValue('C6', "店舗");
            $objActSheet->setCellValue('D6', "担当者");
            $objActSheet->setCellValue('E6', "お客様");
            $objActSheet->setCellValue('F6', "紹介者");
            $objActSheet->setCellValue('G6', "店長");
            $objActSheet->setCellValue('H6', "担当");
            $objActSheet->setCellValue('I6', "未商談");
            $objActSheet->setCellValue('J6', "承認");
            $objActSheet->setCellValue('K6', "確認");
            $objActSheet->setCellValue('L6', "不備理由");

            $objPHPExcel->getActiveSheet()->getStyle('A6:L6')
                ->getBorders()
                ->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
            $objPHPExcel->getActiveSheet()->getStyle('A6:L6')
                ->getBorders()
                ->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
            $objPHPExcel->getActiveSheet()->getStyle('A6:L6')
                ->getBorders()
                ->getInside()->setBorderStyle(Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->getStyle('A6:L6')
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $count = count($dt);
            for ($j = 0; $j < $count; $j++) {
                $i = $j + 7;
                $objActSheet->setCellValueExplicit('A' . $i, $dt[$j]['JYURI_NO'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('B' . $i, $dt[$j]['JYURI_DT'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('C' . $i, $dt[$j]['BUSYO_RYKNM'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('D' . $i, $dt[$j]['SYAIN_NM'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('E' . $i, $dt[$j]['OKYAKU_NM'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('F' . $i, $dt[$j]['SYOUKAI_NM'], DataType::TYPE_STRING);
                if ($dt[$j]['MANEGER_CHK'] != "" && $dt[$j]['MANEGER_CHK'] != null) {
                    $objActSheet->setCellValueExplicit('G' . $i, $dt[$j]['MANEGER_CHK'], DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit('G' . $i, " ", DataType::TYPE_STRING);
                }

                $objActSheet->setCellValueExplicit('H' . $i, $dt[$j]['TANTO_CHK'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('I' . $i, $dt[$j]['SYOUDAN_FLG'], DataType::TYPE_STRING);
                if ($dt[$j]['SYOUNIN_FLG'] == "1") {
                    $objActSheet->setCellValueExplicit('J' . $i, "承認", DataType::TYPE_STRING);
                } elseif ($dt[$j]['SYOUNIN_FLG'] == "2") {
                    $objActSheet->setCellValueExplicit('J' . $i, "不可", DataType::TYPE_STRING);
                } else {
                    $objActSheet->setCellValueExplicit('J' . $i, "", DataType::TYPE_STRING);
                }
                $objActSheet->setCellValueExplicit('K' . $i, $dt[$j]['KAKUNIN_FLG'], DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('L' . $i, $dt[$j]['FUBI_RIYU'], DataType::TYPE_STRING);
            }

            $style = $objPHPExcel->getActiveSheet()->getStyle('A7:L' . $i);
            $style->getBorders()->getInside()->setBorderStyle(Border::BORDER_THIN);
            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $rightStyle = $objPHPExcel->getActiveSheet()->getStyle('L6:L' . $i);
            $rightStyle->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
            $rightStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $bottomStyle = $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':L' . $i);
            $bottomStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
            $bottomStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('L6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $objActSheet->setSelectedCell("A1");
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($strFilePath);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);

            $intState = 1;

            $result["data"]["url"] = "files/HMTVE/" . $strFileName;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = '出力処理中にエラーが発生しました。';
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
        $result["intState"] = $intState;

        return $result;
    }

}
