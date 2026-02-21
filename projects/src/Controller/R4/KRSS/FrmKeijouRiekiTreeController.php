<?php
/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20160511           #2436                     NEW                            YinHuaiyu
 * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmKeijouRiekiTree;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmKeijouRiekiTreeController extends AppController
{
    public $autoLayout = TRUE;
    private $FrmKeijouRiekiTree;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    public function index()
    {
        $this->render('index', 'FrmKeijouRiekiTree_layout');
    }

    //コントロールマスタ存在ﾁｪｯｸ
    public function frmGetYearMonth()
    {
        $result = array();
        try {
            $FrmKeijouRiekiTree = new FrmKeijouRiekiTree();
            $result = $FrmKeijouRiekiTree->frmGetYearMonth();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：実行
    //関 数 名：cmdAction
    //引    数：無し
    //戻 り 値：$result
    //処理説明：本部別実績表を印刷する
    //**********************************************************************
    public function cmdAction()
    {
        $result = array();
        $cboYM = "";
        $sheetNM = "sheet1";
        try {
            if (isset($_POST['data'])) {
                $cboYM = $_POST['data'];
                $tpM = substr($cboYM, 4, 2);
                $tpY = substr($cboYM, 0, 4);
                if ((int) $tpM >= 10) {
                    $tpY = (int) $tpY;
                    $KIFM = $tpY . "10";
                } else {
                    $KIFM = (int) $tpY - 1;
                    $KIFM = $KIFM . "10";
                }
                $this->FrmKeijouRiekiTree = new FrmKeijouRiekiTree();

                $result = $this->FrmKeijouRiekiTree->fncPrintSelect($cboYM, $KIFM);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                //期
                // $tY = substr($cboYM, 0, 4);
                // $tM = substr($cboYM, 4, 2);
                // if ($tM > 9) {
                //     $KI = (int) $tY - 1917;
                //     $lastY = (int) substr($cboYM, 2, 2);
                // } else {
                //     $KI = (int) $tY - 1918;
                //     $lastY = (int) substr($cboYM, 2, 2) - 1;
                // }

                //生成したEXCELファイルのシート名は、 画面．対象年月 を  GEE.MM 形式 とする
                //西暦⇒和暦変換  和暦 + 年月日
                $HYM = $this->ClsComFnc->FncDateChange4($cboYM . "01");
                $H = substr($HYM, 0, 6);
                $Y = substr($HYM, 6, 2);
                $M = substr($HYM, 9, 2);
                switch ($H) {
                    case '明治':
                        $sheetNM = "M" . $Y . "." . $M;
                        break;
                    case '大正':
                        $sheetNM = "T" . $Y . "." . $M;
                        break;
                    case '昭和':
                        $sheetNM = "S" . $Y . "." . $M;
                        break;
                    case '平成':
                        $sheetNM = "H" . $Y . "." . $M;
                        break;
                    default:
                        break;
                }

            }
            if (count((array) $result['data']) > 0) {
                $ExcelData = $result['data'];
                $tmpPath1 = dirname(dirname(dirname(__DIR__)));
                $tmpPath2 = 'webroot/files/KRSS/';
                $tmpPath = $tmpPath1 . "/" . $tmpPath2;
                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
                //生成したEXCELファイル名は、「実績と人員構成.xlsx 」とする
                $file = dirname($tmpPath1) . "/" . $tmpPath2 . '実績と人員構成' . '.xlsx';
                //					$strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . 'FrmKeijouRiekiTreeTemplate.xlsx';
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . 'KRSS/FrmKeijouRiekiTreeTemplate.xlsx';
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

                $objReader = IOFactory::createReader('Xlsx');
                $objPHPExcel = $objReader->load($strTemplatePath);
                $objPHPExcel->setActiveSheetIndex(0);
                $objActSheet = $objPHPExcel->getActiveSheet();
                $objActSheet->setTitle($sheetNM, FALSE);

                $start = 4;

                $maxrow = $objActSheet->getHighestRow();

                $CDsarr = array();

                for ($i = $start; $i <= $maxrow; $i++) {
                    $busyoCD = $objActSheet->getCell("B" . $i)->getValue();
                    if ($busyoCD != "" || $busyoCD != null) {
                        $CDarr = array(
                            "CD" => $busyoCD,
                            "row" => $i
                        );
                        array_push($CDsarr, $CDarr);
                    }
                    if ($busyoCD == "000") {
                        $CDarr = array(
                            "CD" => "000",
                            "row" => $i
                        );
                        array_push($CDsarr, $CDarr);
                    }
                }
                foreach ($ExcelData as $value) {
                    $TOU_ZAN = $value['TOU_ZAN'];
                    $TKI_ZAN = $value['TKI_ZAN'];
                    $CD = $value['BUSYO_CD'];
                    foreach ($CDsarr as $value1) {
                        if ($CD == $value1['CD']) {
                            $objActSheet->setCellValue("C" . $value1["row"], $TOU_ZAN);
                            $objActSheet->setCellValue("D" . $value1["row"], $TKI_ZAN);
                        }
                    }
                }

                $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
                $objWriter->save($file);

                $result['data'] = strstr($file, 'files/');
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
