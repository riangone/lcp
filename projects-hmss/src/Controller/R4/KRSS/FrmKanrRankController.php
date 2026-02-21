<?php
/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20160915              ---                      和暦廃止                        HM
 * -----------------------------------------------------------------------------------------
 */

namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmKanrRank;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmKanrRankController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsKeieiSeika');
        $this->loadComponent('ClsComFnc');
    }
    public $FrmKanrRank = '';
    public $UPDAPP = 'KanrRank';

    /**
     * @param none
     * @return void
     * 処理説明：Viewファイル呼出し
     */
    public function index()
    {
        $this->render('index', 'FrmKanrRank_layout');
    }

    /**
     * 処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
     * 関 数 名：frmKanrSyukei_Load
     * @param none
     * @return $result
     * 処理説明：初期設定
     */
    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmKanrRank = new FrmKanrRank();
            $result = $this->FrmKanrRank->selectData();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    /**
     * 処 理 名：入力ﾁｪｯｸ
     * 関 数 名：fncRankingDataSel
     * @param none
     * @return $result
     * 処理説明：入力ﾁｪｯｸ
     */
    public function fncRankingDataSel()
    {
        $result = array();
        $cboYM = "";
        try {
            $cboYM = $_POST['data'];
            $this->FrmKanrRank = new FrmKanrRank();
            $result = $this->FrmKanrRank->fncRankingDataSel($cboYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
        //$this-> log($result['data']);
    }

    /**
     * 処 理 名：Excel出力
     * 関 数 名：cmdAction_Click
     * @param none
     * @return $result
     * 処理説明：ランキングリストをExcel出力する
     */
    public function cmdActionClick()
    {
        $result = array();
        $postArr = array();
        $UPDUSER = '';
        $Flag = '';
        try {
            $postArr = $_POST['data'];
            $Flag = $_POST['data']['flag'];
            $this->FrmKanrRank = new FrmKanrRank();
            $UPDUSER = $this->FrmKanrRank->GS_LOGINUSER['strUserID'];
            $UPDCLTNM = $this->request->clientIp();

            //-------部署別実績ﾜｰｸ集計処理-------
//            $tempresult = $this -> ClsKeieiSeika -> fncCreateJissekiWK($postArr['cboYM'], $postArr['strKisyuYMD'], $UPDUSER, $UPDCLTNM, $this -> UPDAPP);
            $tempresult = $this->ClsKeieiSeika->fncCreateJissekiWK_NEW($postArr['cboYM'], $postArr['strKisyuYMD'], $UPDUSER, $UPDCLTNM, $this->UPDAPP);
            if ($tempresult['result'] == FALSE) {
                throw new \Exception($tempresult['data']);
            }
            //-------Excel出力-------
            //$Flag=1  新車
            //$Flag=2  中古車
            //$Flag=3  整備

            //            $result = $this -> ClsKeieiSeika -> fncRankingSelect(str_replace("/", "", $postArr['cboYM']) . "01", $postArr['strKI'], $postArr['dblNinzu'], $postArr['dblDaisu'], $Flag);
            $result = $this->ClsKeieiSeika->fncRankingSelect_NEW(str_replace("/", "", $postArr['cboYM']) . "01", $postArr['strKI'], $postArr['dblNinzu'], $postArr['dblDaisu'], $Flag);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if (count($result['data']) > 0) {
                $ExcelData = $result['data'];
                $tmpPath1 = dirname(dirname(dirname(__DIR__)));
                $tmpPath2 = 'webroot/files/KRSS/';
                $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
                //                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . 'FrmKanrRankTemplate.xlsx';
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . 'KRSS/FrmKanrRankTemplate_2016.xlsx';
                switch ($Flag) {
                    //種類＝新車の場合
                    case 1:
                        $file = $tmpPath . '新車ランキングリスト_' . $UPDUSER . '.xlsx';
                        break;
                    //種類＝中古車の場合
                    case 2:
                        $file = $tmpPath . '中古車ランキングリスト_' . $UPDUSER . '.xlsx';
                        break;
                    //種類＝整備の場合
                    case 3:
                        $file = $tmpPath . '整備ランキングリスト_' . $UPDUSER . '.xlsx';
                        break;
                }
                if (!file_exists($tmpPath)) {
                    if (!mkdir($tmpPath, 0777, TRUE)) {
                        $result["data"] = 'Execl Error';
                        throw new \Exception($result["data"]);
                    }
                }

                //テンプレートファイルの存在確認
                if (file_exists($strTemplatePath) == FALSE) {
                    $result["data"] = 'EXCELテンプレートが見つかりません！';
                    throw new \Exception($result["data"]);
                }
                $this->DrawExcel($ExcelData, $strTemplatePath, $file, $Flag);

                $result['data'] = strstr($file, 'files/');
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    /**
     * 関 数 名：DrawExcel
     * @param array $ExcelData. Excel Data
     * @param string $strTemplatePath.The directory of template
     * @param string $file.The destination file
     * @param 1:新車 2:中古車 3:整備
     * @return void
     */
    private function DrawExcel($ExcelData, $strTemplatePath, $file, $Flag)
    {
        $objReader = IOFactory::createReader('Xlsx');
        $objPHPExcel = $objReader->load($strTemplatePath);
        $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objPHPExcel->getActiveSheet();

        //20160915 Upd Start 和暦廃止
//        $objActSheet -> setCellValue('B3', "平成" . $ExcelData[0]['NEN'] . "年" . $ExcelData[0]['TUKI'] . "月度");
        $objActSheet->setCellValue('B3', $ExcelData[0]['NEN'] . "年" . $ExcelData[0]['TUKI'] . "月度");
        //20160915 Upd End 和暦廃止

        $objActSheet->setCellValue('B1', $ExcelData[0]['TITLE']);
        //TITLE_HED開始の行
        $TITLE_HED_START = 7;

        $startColArr = array("G", "R", "AC");
        $ColumnToTITLE_HED = array();

        //HEDDER開始の行
        $HEDDER_START = 8;
        $ColumnToHEDDER = array();

        //LINE開始の行
//        $LINE_START = 9;
        $LINE_START = 10;

        $ColumnToLINE = array();

        $rownum = $LINE_START;
        foreach ($ExcelData as $key => $value) {

            if ($value['LINE_NO'] == 1) {
                //            if ($value['LINE_NO'] == 103) {
                unset($ColumnToTITLE_HED);
                unset($ColumnToHEDDER);
                unset($ColumnToLINE);
                $rownum = $LINE_START;
                $startCol = $startColArr[$value['GROUP_CNT']];
                if ($startCol != null) {
                    for ($k = 1, $startCol; $k < 12; $k++, $startCol++) {
                        $ColumnToTITLE_HED[$startCol] = 'TITLE_HED' . $k;
                        $ColumnToHEDDER[$startCol] = 'HEDDER' . $k;
                        $ColumnToLINE[$startCol] = 'LINE' . $k;
                    }
                }
                //TITLE_HED
                foreach ($ColumnToTITLE_HED as $key1 => $value1) {
                    if ($value[$value1] != "" && $value[$value1] != null) {
                        $objActSheet->setCellValue($key1 . $TITLE_HED_START, $value[$value1]);
                    }
                }
                if ($value['HED_BUS1'] != "" && $value['HED_BUS1'] != null) {
                    $objActSheet->mergeCells('G7:H7');
                    $objActSheet->getStyle('G7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $objActSheet->setCellValue('G' . $TITLE_HED_START, $value['HED_BUS1']);
                }
                if ($value['HED_BUS2'] != "" && $value['HED_BUS2'] != null) {
                    $objActSheet->mergeCells('I7:J7');
                    $objActSheet->getStyle('I7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $objActSheet->setCellValue('I' . $TITLE_HED_START, $value['HED_BUS2']);
                }
                //HEDDER
                foreach ($ColumnToHEDDER as $key2 => $value2) {
                    $objActSheet->setCellValue($key2 . $HEDDER_START, $value[$value2]);
                }
                //GroupHeader1_BeforePrint
                if ($ExcelData[$key]['GROUP_CNT'] == "0") {
                    $objActSheet->mergeCells('G8:H8');
                    $objActSheet->getStyle('G8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $objActSheet->setCellValue('G8', "（本社除く人員当）");

                    $objActSheet->mergeCells('I8:J8');
                    $objActSheet->getStyle('I8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $objActSheet->setCellValue('I8', $value['HED_OPT2']);

                    $objActSheet->setCellValue('L8', "平均");
                }
            }
            //            if ($value['LINE_NO'] == 55) {
//                continue;
//            }
            //LINE
            $this->Detail_BeforePrint($value);
            foreach ($ColumnToLINE as $key3 => $value3) {
                //$this->log("key3");
//$this->log($key3);
//$this->log("value3");
//$this->log($value3);
//$this->log("rownum");
//$this->log($rownum);
//$this->log("$value[$value3]");
//$this->log($value[$value3]);

                $objActSheet->setCellValue($key3 . $rownum, (string) $value[$value3]);
            }
            $rownum++;
        }

        if ($Flag == 2) {
            //Set R90="".if not do this,when removeColumn,rownumber=89 will not remove.The reason is not clear.
            //   $objActSheet -> setCellValue('R90', "");
            //   $objActSheet -> removeColumn('R', 22);
        }
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save($file);
    }

    /**
     * 関 数 名：Detail_BeforePrint
     * @param array &$sender.Row data.
     * @return void
     */
    public function Detail_BeforePrint(&$sender)
    {
        //        if ($sender['TANI'] != "") {
//            if ($sender['GROUP_CNT'] != '0') {
        $this->ClsComFnc->FncNv($sender['LINE1']) == "" ? $sender['LINE1'] = $this->ClsComFnc->FncNv($sender['LINE1']) : $sender['LINE1'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE1'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        $this->ClsComFnc->FncNv($sender['LINE2']) == "" ? $sender['LINE2'] = $this->ClsComFnc->FncNv($sender['LINE2']) : $sender['LINE2'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE2'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        $this->ClsComFnc->FncNv($sender['LINE3']) == "" ? $sender['LINE3'] = $this->ClsComFnc->FncNv($sender['LINE3']) : $sender['LINE3'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE3'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        $this->ClsComFnc->FncNv($sender['LINE4']) == "" ? $sender['LINE4'] = $this->ClsComFnc->FncNv($sender['LINE4']) : $sender['LINE4'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE4'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        //            }
        $this->ClsComFnc->FncNv($sender['LINE5']) == "" ? $sender['LINE5'] = $this->ClsComFnc->FncNv($sender['LINE5']) : $sender['LINE5'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE5'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        $this->ClsComFnc->FncNv($sender['LINE6']) == "" ? $sender['LINE6'] = $this->ClsComFnc->FncNv($sender['LINE6']) : $sender['LINE6'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE6'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        $this->ClsComFnc->FncNv($sender['LINE7']) == "" ? $sender['LINE7'] = $this->ClsComFnc->FncNv($sender['LINE7']) : $sender['LINE7'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE7'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        $this->ClsComFnc->FncNv($sender['LINE8']) == "" ? $sender['LINE8'] = $this->ClsComFnc->FncNv($sender['LINE8']) : $sender['LINE8'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE8'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        $this->ClsComFnc->FncNv($sender['LINE9']) == "" ? $sender['LINE9'] = $this->ClsComFnc->FncNv($sender['LINE9']) : $sender['LINE9'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE9'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        $this->ClsComFnc->FncNv($sender['LINE10']) == "" ? $sender['LINE10'] = $this->ClsComFnc->FncNv($sender['LINE10']) : $sender['LINE10'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE10'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        $this->ClsComFnc->FncNv($sender['LINE11']) == "" ? $sender['LINE11'] = $this->ClsComFnc->FncNv($sender['LINE11']) : $sender['LINE11'] = rtrim((string) $this->ClsComFnc->FncNv($sender['LINE11'])) . rtrim($this->ClsComFnc->FncNv($sender['TANI']));
        //        }
//        if ($sender['GROUP_CNT'] == "0" && $sender['LINE_NO'] == "82") {
        if ($sender['GROUP_CNT'] == "0" && $sender['LINE_NO'] == "111") {
            $sender['LINE2'] = "";
            $sender['LINE4'] = "";
        }
    }

}
