<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE050TotalS;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType as PHPExcel_Cell_DataType;
use PhpOffice\PhpSpreadsheet\Style\Border as PHPExcel_Style_Border;
//*******************************************
// * sample controller
//*******************************************
class HMTVE050TotalSController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmJinkenhiEnt = "";
    public $HMTVE050TotalS;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE050TotalS_layout');
    }

    public function setExhibitTermDate()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //時間取得
            $this->HMTVE050TotalS = new HMTVE050TotalS();
            $result = $this->HMTVE050TotalS->setExhibitTermDate();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }
    public function btnViewClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $postdata = $_POST['data'];
            } else {
                throw new \Exception('params error');
            }
            //存在チェック
            $this->HMTVE050TotalS = new HMTVE050TotalS();
            $resul_check = $this->HMTVE050TotalS->getDate($postdata);
            if (!$resul_check['result']) {
                throw new \Exception($resul_check['data']);
            }
            if (count((array) $resul_check['data']) == 0) {
                throw new \Exception('W0024');
            }
            //速報入力データからデータを取得し、一覧を作成する
            //車種データを取得する
            $result_CarTypeData = $this->HMTVE050TotalS->getCarTypeData();
            if (!$result_CarTypeData['result']) {
                throw new \Exception($result_CarTypeData['data']);
            }
            $result['data']['CarTypeData'] = $result_CarTypeData['data'];
            //店舗データを取得する
            $result_ShopData = $this->HMTVE050TotalS->getShopData($postdata);
            if (!$result_ShopData['result']) {
                throw new \Exception($result_ShopData['data']);
            }
            $result['data']['ShopData'] = $result_ShopData['data'];
            //内訳テーブルの生成
            //内訳データを取得する
            $result_DetailData = $this->HMTVE050TotalS->getDetailData($postdata);
            if (!$result_DetailData['result']) {
                throw new \Exception($result_DetailData['data']);
            }
            $result['data']['DetailData'] = $result_DetailData['data'];
            //総合計をセットする
            $result_setAllSum = $this->HMTVE050TotalS->setAllSum($postdata);
            if (!$result_setAllSum['result']) {
                throw new \Exception($result_setAllSum['data']);
            }

            $result['data']['setAllSum'] = $result_setAllSum['data'];
            //総合計_内訳テーブルの生成
            //合計データを取得する
            $result_getSumData = $this->HMTVE050TotalS->getSumData($postdata);
            if (!$result_getSumData['result']) {
                throw new \Exception($result_getSumData['data']);
            }
            $busyoArr = array();
            foreach ((array) $result_getSumData['data'] as $value) {
                $busyoArr[$value['SYASYU_CD']] = $value['DAISU_GK'];
            }
            $result['data']['getSumData'] = $busyoArr;

            $busyoArr = array();
            $strTemp = "";
            $num = 0;
            foreach ((array) $result_DetailData['data'] as $value_detail) {
                if ($value_detail['BUSYO_CD'] != "") {
                    if ($strTemp != $value_detail['BUSYO_CD']) {
                        $num++;
                        $strTemp = $value_detail['BUSYO_CD'];

                    }
                    $result_ShopData['data'][$num - 1][$value_detail['SYASYU_CD']] = $value_detail['DAISU_GK'];
                }
            }
            $result['data']['detail'] = $result_ShopData['data'];
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnOutputHITNETClick()
    {
        $tranStartFlg = false;
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
            $this->HMTVE050TotalS = new HMTVE050TotalS();
            // データ存在のチェック
            $objdr = $this->HMTVE050TotalS->getDate($postData);
            if ($objdr['result'] == false) {
                throw new \Exception($objdr['data']);
            }
            if (count((array) $objdr['data']) == 0) {
                throw new \Exception('W0024');
            }

            //速報確定データに確定ﾌﾗｸﾞ１で更新する
            $this->RenewQuickReport($postData);
            // if ($RenewQuickReport['result'] == false)
            // {
            // throw new \Exception($RenewQuickReport['error']);
            // }
            //トランザクション開始
            $this->HMTVE050TotalS->Do_transaction();
            $tranStartFlg = TRUE;
            //速報データの出力ﾌﾗｸﾞを"1"で更新する
            $objdr_upd = $this->HMTVE050TotalS->RenewQucikReportPutoutData($postData);
            if ($objdr_upd['result'] == false) {
                throw new \Exception($objdr_upd['data']);
            }
            //Excelファイル生成処理
            $makeExcel = $this->MakeExcel($postData);
            if ($makeExcel['result'] == false) {
                throw new \Exception($makeExcel['error']);
            }

            $this->HMTVE050TotalS->Do_commit();
            $tranStartFlg = FALSE;
            $result = $makeExcel;

        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE050TotalS->Do_rollback();
                $this->HMTVE050TotalS->fncDataback($postData);
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：確報確定データ
    // '関 数 名：$RenewQuickReport
    // '引 数 　：strSql
    // '戻 り 値：なし
    // '処理説明：確報確定データの更新処理
    // '2009/04/02 UPD clsdb追加
    // '**********************************************************************
    public function RenewQuickReport($postData)
    {
        $tranStartFlg = false;
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $result_datediff = $this->DateDiff($postData['lblExhibitTerm'], $postData['ddlExhibitDay']);
            if ($result_datediff['result'] == false) {
                throw new \Exception($result_datediff['error']);
            }
            $i = $result_datediff['data'];
            //トランザクション開始
            $this->HMTVE050TotalS->Do_transaction();
            $tranStartFlg = TRUE;
            for ($x = 0; $x <= $i; $x++) {
                $postData['IVENTDT'] = date("Y/m/d", strtotime("+" . $x . " day", strtotime($postData['lblExhibitTerm'])));
                $objdr = $this->HMTVE050TotalS->getStartDate($postData);
                if ($objdr['result'] == false) {
                    throw new \Exception($objdr['data']);
                }
                if (count((array) $objdr['data']) == 0) {
                    $objdr_ins = $this->HMTVE050TotalS->fncInsert($postData);
                    if ($objdr_ins['result'] == false) {
                        throw new \Exception($objdr_ins['data']);
                    }
                } else {
                    $objdr_upd = $this->HMTVE050TotalS->fncUpdate($postData);
                    if ($objdr_upd['result'] == false) {
                        throw new \Exception($objdr_upd['data']);
                    }
                }
            }
            $this->HMTVE050TotalS->Do_commit();
            $result['result'] = true;

        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMTVE050TotalS->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    // '**********************************************************************
    // '処 理 名：Excelファイル
    // '関 数 名：MakeExcel
    // '引 数 　：strSql
    // '戻 り 値：なし
    // '処理説明：Exceファイル生成処理
    // '2009/04/02 UPD clsdbに変更
    // '**********************************************************************
    public function MakeExcel($postData)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $dt = $this->HMTVE050TotalS->getCreate($postData);
            if ($dt['result'] == false) {
                throw new \Exception($dt['data']);
            }
            $dt2 = $this->HMTVE050TotalS->getCreate2($postData);
            if ($dt2['result'] == false) {
                throw new \Exception($dt2['data']);
            }

            //出力先パス
            $strPath = dirname(dirname(dirname(__FILE__)));
            $tmpPath1 = dirname($strPath);
            $tmpPath2 = "webroot/files/HMTVE/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            $strTemplatePath1 = $this->ClsComFncHMTVE->FncGetPath("HmtveExcelLayoutPath");
            $strTemplatePath = $strPath . '/' . $strTemplatePath1 . "HITNETSOKUHOUDATA.xls";
            $fileName = $tmpPath . "HITNET用速報データ.xls";
            if (!file_exists($strTemplatePath)) {
                throw new \Exception('W9999');
            }
            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && strpos($file, "HITNET用速報データ") !== false) {
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

            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            $objActSheet->setCellValue('AL2', $dt['data'][0]['SYS']);
            $objActSheet->setCellValue('I4', $dt['data'][0]['FROM_DATE'] . '～' . $dt['data'][0]['TO_DATE']);
            $objActSheet->setCellValue('I6', $dt['data'][0]['IVENT_NM']);
            $objActSheet->setCellValue('J10', $dt['data'][0]['GK']);
            $m = 13;
            $n = 14;
            $z = 0;
            $styleThinBlackBorderOutline = array(
                'borders' => array(
                    'allBorders' => array(//设置全部边框
                        'borderStyle' => PHPExcel_Style_Border::BORDER_THIN
                    ),
                ),
            );
            for ($i = 0; $i < count((array) $dt2['data']); $i++) {
                $objActSheet->mergeCells($this->IntToChr(2 + $z * 3) . $m . ':' . $this->IntToChr(4 + $z * 3) . $m);
                // $objActSheet -> setCellValue($this -> IntToChr(2 + $z * 3) . $m, $dt2['data'][$i]['RYAKUSYOU']);
                $objActSheet->setCellValueExplicit($this->IntToChr(2 + $z * 3) . $m, $dt2['data'][$i]['RYAKUSYOU'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objActSheet->mergeCells($this->IntToChr(2 + $z * 3) . $n . ':' . $this->IntToChr(4 + $z * 3) . $n);
                $objActSheet->setCellValue($this->IntToChr(2 + $z * 3) . $n, $dt2['data'][$i]['DAISU']);
                $objActSheet->getStyle($this->IntToChr(2 + $z * 3) . $m . ':' . $this->IntToChr(4 + $z * 3) . $m)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->getStyle($this->IntToChr(2 + $z * 3) . $n . ':' . $this->IntToChr(4 + $z * 3) . $n)->applyFromArray($styleThinBlackBorderOutline);

                $z++;
                if ($i != 0 && ($i % 14 == 0)) {
                    $m = $m + 4;
                    $n = $n + 4;
                    $z = 0;
                }
            }

            $objActSheet->setSelectedCell("C13");
            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $file = "files/HMTVE/" . "HITNET用速報データ.xls";

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

    /*
           ***********************************************************************
           '処 理 名：ロック解除クリックのイベント
           '関 数 名：btnUnLock_Click
           '引    数：無し
           '戻 り 値 ：なし
           '処理説明 ：ロック解除を行う
           '**********************************************************************
           */
    public function btnUnLockClick()
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
            $HMTVE050TotalS = new HMTVE050TotalS();
            $result = $HMTVE050TotalS->unLock($postData);
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

    public function DateDiff($day1, $day2)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $second1 = strtotime($day1);
            $second2 = strtotime($day2);

            if ($second1 < $second2) {
                $tmp = $second2;
                $second2 = $second1;
                $second1 = $tmp;
            }
            $result['data'] = ($second1 - $second2) / 86400;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function IntToChr($index, $start = 65)
    {
        try {
            $str = '';
            if (floor($index / 26) > 0) {
                $str .= $this->IntToChr(floor($index / 26) - 1);
            }
            return $str . chr($index % 26 + $start);
        } catch (\Exception $e) {
            return false;
        }
    }

}

