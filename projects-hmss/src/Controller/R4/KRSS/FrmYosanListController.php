<?php
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug         内容                           			担当
 * YYYYMMDD           #ID                 XXXXXX                         			FCSDL
 * 20160608			  依赖#2533			  予算編成・EXCELアップロードの複数シート対応		li
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmYosanList;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;

class FrmYosanListController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========
    private $Session;
    private $result;
    private $excelFileName = "";
    private $readExcelArr = array();
    private $excelType;
    //20160608 LI INS S
    //読み込むExcelのSheet数
    private $sheetCount;
    //20160608 LI INS E
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    private $FrmYosanList;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsComFncKRSS');

    }
    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //-------
    //from load s
    //-------
    public function index()
    {
        $this->render('index', 'FrmYosanList_layout');
    }

    public function formLoad()
    {
        try {
            $result1 = [];
            $result2 = [];
            $this->FrmYosanList = new FrmYosanList();
            $result1 = $this->FrmYosanList->fncSQL1();
            if (!$result1['result']) {
                throw new \Exception($result1['data'], 1);
            }

            $result2 = $this->FrmYosanList->subCtlSql();
            if ($result2['result'] === FALSE) {
                throw new \Exception($result2['data'], 1);
            }

            if (count((array) $result2['data']) <= 0) {
                $this->result['result'] = FALSE;
                throw new \Exception('コントロールマスタが存在しません！', 1);
            }

            $this->result['result'] = TRUE;
            $this->result['data'] = [
                'KI' => $result1['data'],
                'cboYM' => $result2['data']
            ];
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }

        $this->fncReturn($this->result);
    }

    public function formLoadcheckAuthority()
    {
        $result = [];

        try {
            $busyoCD = "";
            $tarr = $_POST['data']['controls'];
            $FrmYosanList = new FrmYosanList();
            $this->result = $FrmYosanList->fncAuthority();

            if ($this->result['result'] === FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
            //0件の場合
            if (count((array) $this->result['data']) <= 0) {
                // $this->result['MsgID'] = 'W9999';
                throw new \Exception('権限の設定がされていません。管理者にご連絡ください！', 1);
            }
            //1件の場合
            if (count((array) $this->result['data']) === 1) {
                $busyoCD = $this->result['data'][0]['BUSYO_CD'];
                $strUserID = $FrmYosanList->GS_LOGINUSER['strUserID'];
                $this->result = $this->ClsComFncKRSS->fncAuthorityInvest($tarr, $strUserID, $busyoCD);
                if ($this->result['result'] == FALSE) {
                    throw new \Exception($this->result['data']);
                }
            }
            $result = $this->result;
        } catch (\Exception $e) {
            // $result["MsgID"] = $this->result["MsgID"];
            $result["result"] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //-------
    // from load e
    //-------

    //cmdAct_Click
    public function cmdActClick()
    {
        $postData = $_POST['data']['request'];
        $result = [
            'result' => false,
            'data' => 'ErrorInfo',
            'MsgID' => 'E9999'
        ];
        try {

            //------予算取込------
            $this->result = $this->YosanExcelRead();
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }

            $this->result = $this->YosanDataTotal($postData);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
            $result = $this->result;
        } catch (\Exception $ex) {
            $result['result'] = $this->result['result'];
            $result['MsgID'] = $this->result['MsgID'];
            $result['data'] = $ex->getMessage();
        }

        $this->fncReturn($result);
    }

    //btn_excel_output
    public function btnExcelOutput()
    {
        $postData = $_POST['data'];
        $touYM = '';
        $KI = '';
        $strKisyu = '';
        $result = [
            'result' => false,
            'data' => 'ErrorInfo',
            'MsgID' => 'E9999'
        ];
        try {
            $FrmYosanList = new FrmYosanList();
            //期を求める
            //$touYM = $this -> result['data'][0]["TOU_YM"];
            $touYM = $postData['cboYM'] . "/01";
            $touYM = str_replace("/", "", $touYM);
            $K_array = $this->getKI($touYM);
            $KI = $K_array['KI'];
            $Y = $K_array['Y'];
            $M = $K_array['M'];
            //期首を求める
            $strKisyu = ((int) $KI + 1917) . "10";
            //明細ﾃﾞｰﾀ取得
            $this->result = $FrmYosanList->fncYOJITSUSQL($strKisyu, $KI, $Y, $M);
            if ($this->result['result'] == FALSE) {
                $this->result['MsgID'] = 'E9999';
                throw new \Exception($this->result['data'], 1);
            }

            if (count((array) $this->result['data']) <= 0) {
                $this->result['result'] = FALSE;
                $this->result['MsgID'] = 'I0001';
                throw new \Exception('', 1);
            }
            //excel 作成
            $excelName = $this->createExcel($this->result['data'], $KI);
            if ($excelName['result'] == false) {
                throw new \Exception($excelName['error']);
            }
            $this->result = $excelName;

            $result = $this->result;
        } catch (\Exception $ex) {
            $result['result'] = $this->result['result'];
            $result['MsgID'] = $this->result['MsgID'];
            $result['data'] = $ex->getMessage();
        }

        $this->fncReturn($result);
    }

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //-------
    //実績集計表出力 s
    //-------
    private function getKI($touYM)
    {
        $K_array = [];
        $KI = "";
        $tY = substr($touYM, 0, 4);
        $tM = substr($touYM, 4, 2);
        $tD = substr($touYM, 6, 2);

        if ($tM > 9) {
            $KI = (int) $tY - 1917;
        } else {
            $KI = (int) $tY - 1918;
        }

        $K_array["KI"] = $KI;
        $K_array["Y"] = $tY;
        $K_array["M"] = $tM;
        $K_array["D"] = $tD;

        return $K_array;
    }

    private function createExcel($data, $KI)
    {
        $result = [
            'result' => false,
            'error' => ''
        ];

        try {
            $tmpBusyoName = "";
            // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";
            $FrmYosanList = new FrmYosanList();
            $workSheetName = "実績集計表";
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle($workSheetName);
            //用紙デフォルト設定
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A3);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);

            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(15.15);
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(21.29);
            $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(8.89);
            $objPHPExcel->getActiveSheet()->getColumnDimension("O")->setWidth(9.57);
            $objPHPExcel->getActiveSheet()->getColumnDimension("P")->setWidth(9.57);

            //ヘッダ行
            $objPHPExcel->getActiveSheet()->mergeCells("A2:P2");
            $objPHPExcel->getActiveSheet()->setCellValue("A2", "第" . $KI . "期実績集計表");
            $objPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setName("ＭＳ Ｐゴシック");
            $objPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(18);
            $objPHPExcel->getActiveSheet()->getStyle("A2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            //項目名をセット
            $objPHPExcel->getActiveSheet()->setCellValue("A3", "店舗");
            $objPHPExcel->getActiveSheet()->setCellValue("B3", "項目");
            $objPHPExcel->getActiveSheet()->setCellValue("C3", "10月");
            $objPHPExcel->getActiveSheet()->setCellValue("D3", "11月");
            $objPHPExcel->getActiveSheet()->setCellValue("E3", "12月");
            $objPHPExcel->getActiveSheet()->setCellValue("F3", "1月");
            $objPHPExcel->getActiveSheet()->setCellValue("G3", "2月");
            $objPHPExcel->getActiveSheet()->setCellValue("H3", "3月");
            $objPHPExcel->getActiveSheet()->setCellValue("I3", "4月");
            $objPHPExcel->getActiveSheet()->setCellValue("J3", "5月");
            $objPHPExcel->getActiveSheet()->setCellValue("K3", "6月");
            $objPHPExcel->getActiveSheet()->setCellValue("L3", "7月");
            $objPHPExcel->getActiveSheet()->setCellValue("M3", "8月");
            $objPHPExcel->getActiveSheet()->setCellValue("N3", "9月");
            $objPHPExcel->getActiveSheet()->setCellValue("O3", "通期");
            $objPHPExcel->getActiveSheet()->setCellValue("P3", "前年通期");

            //set borders
            $this->setBordersStyle($objPHPExcel, 3, "Top");
            $startCol = "A";
            $endCol = "P";
            $this->setHorizontal($objPHPExcel, 3, "center", $startCol, $endCol);
            //$objPHPExcel -> getActiveSheet() -> getStyle($i . "3") -> getBorders() -> getTop() -> setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

            //明細データセット
            $ii = 0;
            $intRowCnt = 4;
            $tmpBusyoName = $this->ClsComFnc->FncNz($data[$ii]["BUSYO_NM"]);
            while ($ii < count($data)) {

                $objPHPExcel->getActiveSheet()->setCellValue("A" . $intRowCnt, $this->ClsComFnc->FncNz($data[$ii]["BUSYO_NM"]));
                $objPHPExcel->getActiveSheet()->setCellValue("B" . $intRowCnt, $this->ClsComFnc->FncNz($data[$ii]["KOUMOKUMEI"]));
                $objPHPExcel->getActiveSheet()->setCellValue("C" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK10"])));
                $objPHPExcel->getActiveSheet()->setCellValue("D" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK11"])));
                $objPHPExcel->getActiveSheet()->setCellValue("E" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK12"])));
                $objPHPExcel->getActiveSheet()->setCellValue("F" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK1"])));
                $objPHPExcel->getActiveSheet()->setCellValue("G" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK2"])));
                $objPHPExcel->getActiveSheet()->setCellValue("H" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK3"])));
                $objPHPExcel->getActiveSheet()->setCellValue("I" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK4"])));
                $objPHPExcel->getActiveSheet()->setCellValue("J" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK5"])));
                $objPHPExcel->getActiveSheet()->setCellValue("K" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK6"])));
                $objPHPExcel->getActiveSheet()->setCellValue("L" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK7"])));
                $objPHPExcel->getActiveSheet()->setCellValue("M" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK8"])));
                $objPHPExcel->getActiveSheet()->setCellValue("N" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GK9"])));
                $objPHPExcel->getActiveSheet()->setCellValue("O" . $intRowCnt, number_format($this->ClsComFnc->FncNz($data[$ii]["GOUKEI"])));
                $objPHPExcel->getActiveSheet()->setCellValue("P" . $intRowCnt, $data[$ii]["ZEN_GOUKEI"] == "" ? $data[$ii]["ZEN_GOUKEI"] : number_format($this->ClsComFnc->FncNz($data[$ii]["ZEN_GOUKEI"])));
                $objPHPExcel->getActiveSheet()->getStyle("B" . $intRowCnt)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB("F5F5DC");
                if ($tmpBusyoName != $this->ClsComFnc->FncNz($data[$ii]["BUSYO_NM"])) {
                    $this->setBordersStyle($objPHPExcel, $intRowCnt - 1, "", TRUE);
                }
                $this->setBordersStyle($objPHPExcel, $intRowCnt);
                $this->setFontColor($objPHPExcel, $intRowCnt);

                $startCol = "A";
                $endCol = "B";
                $this->setHorizontal($objPHPExcel, $intRowCnt, "left", $startCol, $endCol);
                $startCol = "C";
                $endCol = "P";
                $this->setHorizontal($objPHPExcel, $intRowCnt, "right", $startCol, $endCol);

                $tmpBusyoName = $data[$ii]["BUSYO_NM"];
                $intRowCnt++;
                $ii++;
            }
            $this->setBordersStyle($objPHPExcel, $intRowCnt - 1, "Bottom");
            //$objPHPExcel -> getActiveSheet() -> getStyle($i . ((int)$intRowCnt - 1)) -> getBorders() -> getBottom() -> setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

            $tmpPath1 = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
            $tmpPath2 = "webroot/files/KRSS/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;

            $excelName = "実績集計表_" . $FrmYosanList->GS_LOGINUSER['strUserID'] . ".xlsx";
            $excelPathName = $tmpPath . $excelName;
            $hostExcelPath = "files/KRSS/" . $excelName;
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
            $objWriter->save($excelPathName);
            $result['data'] = $hostExcelPath;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }

        return $result;
    }

    private function setHorizontal($objPHPExcel, $lineNo, $pos, $startCol, $endCol)
    {
        for ($i = $startCol; $i <= $endCol; $i++) {
            if ($pos == "right") {
                $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            } else if ($pos == "center") {
                $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            } else if ($pos == "left") {
                $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            }
        }
    }

    private function setFontColor($objPHPExcel, $lineNo)
    {
        for ($i = "A"; $i <= "P"; $i++) {
            if (((int) $objPHPExcel->getActiveSheet()->getCell($i . $lineNo)->getValue()) < 0) {
                $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getFont()->getColor()->setARGB(Color::COLOR_RED);
            }
        }
    }

    private function setBordersStyle($objPHPExcel, $lineNo, $pos = "", $busyoNm = FALSE)
    {
        if ($busyoNm == FALSE) {
            for ($i = "A"; $i <= "P"; $i++) {
                switch ($i) {
                    case 'A':
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                        break;
                    case 'P':
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                        break;
                    default:
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                        break;
                }

                switch ($pos) {
                    case "Top":
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                        break;
                    case "Bottom":
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
                        break;
                    default:
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
                        $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                        break;
                }
                $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getFont()->setName("ＭＳ Ｐゴシック");
                $objPHPExcel->getActiveSheet()->getStyle($i . $lineNo)->getFont()->setSize(10);
            }
        } else {
            for ($j = "A"; $j <= "P"; $j++) {
                $objPHPExcel->getActiveSheet()->getStyle($j . $lineNo)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
            }
        }
    }

    //-------
    //実績集計表出力 e
    //-------

    //-------
    //予算取込関連の関数　s
    //-------
    public function YosanExcelRead()
    {
        $blnErr = FALSE;
        $result = [
            'result' => false,
            'data' => 'ErrorInfo',
            'MsgID' => ''
        ];
        $FrmYosanList = new FrmYosanList();
        try {

            //取込Excel
            $this->result = $this->readExcel();
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }

            //入力チェック
            $this->result = $this->fncChkInput();
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }

            $res = $FrmYosanList->Do_conn();

            if (!$res['result']) {
                $this->result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $FrmYosanList->Do_transaction();
            $blnErr = TRUE;

            //登録処理を行う
            $this->result = $this->fncTouroku();
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            } else {
                $result = $this->result;
                $result["data"] = "";
                $result["MsgID"] = "";
            }

            $FrmYosanList->Do_commit();
            $blnErr = FALSE;

        } catch (\Exception $ex) {
            $result['result'] = FALSE;
            $result['MsgID'] = $this->result['MsgID'];
            $result['data'] = $ex->getMessage();
        }

        if ($blnErr) {
            $result['result'] = FALSE;
            $FrmYosanList->Do_rollback();
            //$this -> FrmYosanList -> Do_close();
        }
        $FrmYosanList->Do_close();
        return $result;
    }

    public function fncTouroku()
    {
        $this->Session = $this->request->getSession();
        $UPDUSER = $this->Session->read('login_user');
        $UPDCLTNM = $this->request->clientIp();
        $UPDAPP = "frmYosanList";

        $result = [
            'MsgID' => ''
        ];
        try {
            $FrmYosanList = new FrmYosanList();
            //ﾜｰｸﾃｰﾌﾞﾙの削除を行う
            $result = $FrmYosanList->fncSQL4();
            if ($result['result'] == FALSE) {
                throw new \Exception($result['data'], 1);
            }

            //ﾜｰｸﾃｰﾌﾞﾙの挿入を行う
            //20160608 LI UPD S
            // foreach ($this->readExcelArr as $key => $value) {
            // //業務課のみ新車・中古車台数の列が入力されていたとしたも取込まない。
//
            // if ((int)$value["BUSYO_CD"] == 174) {
            // if ($key == 15 || $key == 24) {
//
            // }
            // } else {
            // if ((int)$value["LINE_NO"] == 104 || (int)$value["LINE_NO"] == 105 || (int)$value["LINE_NO"] == 108 || (int)$value["LINE_NO"] == 117 || (int)$value["LINE_NO"] == 124 || (int)$value["LINE_NO"] == 131) {
            // } else {
            // $result = $this -> FrmYosanList -> fncSQL5($value, $key,$UPDAPP,$UPDCLTNM,$UPDUSER);
//
            // if ($result['result'] == FALSE) {
            // throw new Exception($result['data'], 1);
            // }
            // }
            // }
            // }
            // //予算テーブルの削除を行う
            // $result = $this -> FrmYosanList -> fncSQL3($this -> readExcelArr[0]['KI'], $this -> readExcelArr[0]["BUSYO_CD"]);
            // if ($result['result'] == FALSE) {
            // throw new Exception($result['data'], 1);
            // }
            //EXCELアップロードの複数シート
            for ($i = 0; $i < $this->sheetCount; $i++) {
                foreach ($this->readExcelArr[$i] as $key => $value) {
                    //業務課のみ新車・中古車台数の列が入力されていたとしたも取込まない。

                    if ((int) $value["BUSYO_CD"] == 174) {
                        if ($key == 15 || $key == 24) {

                        }
                    } else {
                        //					if ((int)$value["LINE_NO"] == 104 || (int)$value["LINE_NO"] == 105 || (int)$value["LINE_NO"] == 108 || (int)$value["LINE_NO"] == 117 || (int)$value["LINE_NO"] == 124 || (int)$value["LINE_NO"] == 131) {
//					if ((int)$value["LINE_NO"] >= 118 ) {
//					if ((int)$value["LINE_NO"] >= 131 ) {
//					if ((int)$value["LINE_NO"] == 131 || (int)$value["LINE_NO"] == 132 || (int)$value["LINE_NO"] == 133 || (int)$value["LINE_NO"] == 143 || (int)$value["LINE_NO"] == 144 || (int)$value["LINE_NO"] == 145 || (int)$value["LINE_NO"] == 146 || (int)$value["LINE_NO"] == 147 || (int)$value["LINE_NO"] == 148 ) {
                        if ((int) $value["LINE_NO"] >= 154) {
                        } else {
                            $result = $FrmYosanList->fncSQL5($value, $UPDAPP, $UPDCLTNM, $UPDUSER);

                            if ($result['result'] == FALSE) {
                                throw new \Exception($result['data'], 1);
                            }
                        }
                    }
                }
                //予算テーブルの削除を行う
                $result = $FrmYosanList->fncSQL3($this->readExcelArr[$i][0]['KI'], $this->readExcelArr[$i][0]["BUSYO_CD"]);
                if ($result['result'] == FALSE) {
                    throw new \Exception($result['data'], 1);
                }
            }
            //20160608 LI UPD E

            //予算テーブル作成
            $result = $FrmYosanList->fncSQL6($UPDAPP, $UPDCLTNM, $UPDUSER);
            if ($result['result'] == FALSE) {
                throw new \Exception($result['data'], 1);
            }
        } catch (\Exception $ex) {
            $result['MsgID'] = "E9999";
            $result['result'] = FALSE;
            $result['data'] = $ex->getMessage();
        }

        return $result;
    }

    public function readExcel()
    {
        $result = [
            'MsgID' => ''
        ];
        //			$excelDetailColumnArr = array("O" => "10", "S" => "11", "W" => "12", "AF" => "1", "AJ" => "2", "AN" => "3", "AW" => "4", "BA" => "5", "BE" => "6", "BN" => "7", "BR" => "8", "BV" => "9");
//			$excelDetailColumnArr = array("P" => "10", "U" => "11", "Z" => "12", "AK" => "1", "AP" => "2", "AU" => "3", "BF" => "4", "BK" => "5", "BP" => "6", "CA" => "7", "CF" => "8", "CK" => "9");
        $excelDetailColumnArr = [
            'Q' => '10',
            'W' => '11',
            'AC' => '12',
            'AP' => '1',
            'AV' => '2',
            'BB' => '3',
            'BO' => '4',
            'BU' => '5',
            'CA' => '6',
            'CN' => '7',
            'CT' => '8',
            'CZ' => '9'
        ];
        try {
            $fileName = $this->changeFileName($_POST['data']['request']['FILENAME']);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
//            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $fileName;
            $fileName = $pathUpLoad;
            //$_POST['data']['request']['FILENAME'];

            if (!file_exists($fileName)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";
            $arr = explode(".", $fileName);
            if (strtolower($arr[count($arr) - 1]) == "xlsx") {
                $this->excelType = "Excel2007";
                $objReader = IOFactory::createReader('Xlsx');
            } else {
                if (strtolower($arr[count($arr) - 1]) == "xls") {
                    $this->excelType = "Excel5";
                    $objReader = IOFactory::createReader('Xls');
                }
            }
            $objReader->setReadDataOnly(true);
            $objPHPExcel = IOFactory::load($fileName);
            //20160608 LI UPD S
            // $worksheet = $objPHPExcel -> getSheet(0);
//
            // $highestRow = $worksheet -> getHighestRow();
            // $KIval = $worksheet -> getCell("D3") -> getValue();
            // $BUSYO_CDval = $worksheet -> getCell("D5") -> getValue();
//
            // for ($i = 10; $i <= $highestRow; $i++) {
            // if ($worksheet -> getCell("A" . $i) -> getValue() == "" || $worksheet -> getCell("A" . $i) -> getValue() == null) {
//
            // } else {
            // $this -> readExcelArr[$excelDetailRowCount]["KI"] = $KIval;
            // $this -> readExcelArr[$excelDetailRowCount]["BUSYO_CD"] = $BUSYO_CDval;
            // $this -> readExcelArr[$excelDetailRowCount]["LINE_NO"] = $worksheet -> getCell("A" . $i) -> getValue();
            // // get column
            // foreach ($excelDetailColumnArr as $key => $value) {
            // $this -> readExcelArr[$excelDetailRowCount]["YSN_GK" . $value] = $worksheet -> getCell($key . $i) -> getValue();
            // }
            // $excelDetailRowCount++;
            // }
            // }
            //読み込むExcelのSheet数を取得
            $this->sheetCount = $objPHPExcel->getSheetCount();
            //EXCELアップロードの複数シート
            for ($sheetI = 0; $sheetI < $this->sheetCount; $sheetI++) {
                $excelDetailRowCount = 0;
                $worksheet = $objPHPExcel->getSheet($sheetI);

                $highestRow = $worksheet->getHighestRow();
                $sheetName = $worksheet->GetTitle();
                $KIval = $worksheet->getCell("D3")->getValue();
                $BUSYO_CDval = $worksheet->getCell("D5")->getValue();


                for ($i = 10; $i <= $highestRow; $i++) {
                    if ($worksheet->getCell("A" . $i)->getValue() == "" || $worksheet->getCell("A" . $i)->getValue() == null) {

                    } else {
                        $this->readExcelArr[$sheetI][$excelDetailRowCount]["KI"] = $KIval;
                        $this->readExcelArr[$sheetI][$excelDetailRowCount]["BUSYO_CD"] = $BUSYO_CDval;
                        $this->readExcelArr[$sheetI][$excelDetailRowCount]["SHEET"] = $sheetName;
                        $this->readExcelArr[$sheetI][$excelDetailRowCount]["LINE_NO"] = $worksheet->getCell("A" . $i)->getValue();
                        // get column
                        foreach ($excelDetailColumnArr as $key => $value) {
                            // $this -> readExcelArr[$sheetI][$excelDetailRowCount]["YSN_GK" . $value] = $worksheet -> getCell($key . $i) -> getValue();
                            $this->readExcelArr[$sheetI][$excelDetailRowCount]["YSN_GK" . $value] = $worksheet->getCell($key . $i)->getCalculatedValue();
                        }
                        $excelDetailRowCount++;
                    }
                }
            }
            //20160608 LI UPD E
            $result['result'] = TRUE;
        } catch (\Exception $ex) {
            $result['result'] = FALSE;
            $result['data'] = $ex->getMessage();
        }

        return $result;
    }

    public function changeFileName($param)
    {
        $FrmYosanList = new FrmYosanList();
        $strUserID = $FrmYosanList->GS_LOGINUSER['strUserID'];
        $arr = explode(".", $param);
        $long = count($arr) - 1;
        $file_type = $arr[$long];
        $file_name = '';
        for ($i = 0; $i < $long; $i++) {
            $file_name = $file_name . $arr[$i] . '.';
        }
        ;
        $file_name = substr($file_name, 0, strlen($file_name) - 1);
        $file_name = $strUserID . '_' . $file_name . '.' . $file_type;

        return $file_name;
    }

    //-------
    //予算取込関連の関数　e
    //-------

    //-------
    //予算データ集計関連の関数　s
    //-------
    public function YosanDataTotal($postData)
    {
        $result = array('result' => false, 'data' => 'ErrorInfo');
        $blnTran = FALSE;
        $this->FrmYosanList = new FrmYosanList();
        try {
            //予算データ作成が行われているかのチェックを行う

            $this->result = $this->fncChkHYOSAN($postData);
            if ($this->result['result'] == FALSE) {
                $this->result['MsgID'] = 'E9999';
                throw new \Exception($this->result['data'], 1);
            }

            $res = $this->FrmYosanList->Do_conn();
            if (!$res['result']) {
                $this->result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }

            //トランザクションを開始する
            $this->FrmYosanList->Do_transaction();
            $blnTran = TRUE;

            //予算ﾃﾞｰﾀ作成
            $this->result = $this->fncCreateYosan($postData["KEIJOBI"]);
            if ($this->result['result'] == FALSE) {
                $this->result['MsgID'] = 'E9999';
                throw new \Exception($this->result['data'], 1);
            }
            //正常終了した場合はコミット
            $this->FrmYosanList->Do_commit();
            $blnTran = FALSE;
            //正常終了のﾒｯｾｰｼﾞを表示する
            $this->result['MsgID'] = "I0005";

            $result = $this->result;
            $result['data'] = "";
        } catch (\Exception $ex) {
            $result['result'] = $this->result['result'];
            $result['MsgID'] = $this->result['MsgID'];
            $result['data'] = $ex->getMessage();
        }
        if ($blnTran == TRUE) {
            $result['result'] = FALSE;
            $this->FrmYosanList->Do_rollback();
            $this->FrmYosanList->Do_close();
        }

        return $result;
    }

    public function fncCreateYosan($intKI)
    {
        $UPDUSER = $this->Session->read('login_user');
        $UPDCLTNM = $this->request->clientIp();
        $UPDAPP = "frmYosanList";
        $result = array('result' => false, 'data' => 'ErrorInfo', 'MsgID' => "E9999");
        try {
            //予算指標ﾃｰﾌﾞﾙ削除
            $this->result = $this->FrmYosanList->fncDeleteHSHIHYO_DataTotal($intKI);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
            //予算指標ﾃｰﾌﾞﾙ追加
            $this->result = $this->FrmYosanList->fncInsertHSHIHYO_DataTotal($intKI, $UPDAPP, $UPDCLTNM, $UPDUSER);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
            //予算ﾃｰﾌﾞﾙ削除(集計部署)
            $this->result = $this->FrmYosanList->fncDeleteHTTLYOSAN_DataTotal($intKI);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
            //予算ﾃｰﾌﾞﾙ追加(集計部署)
            $this->result = $this->FrmYosanList->fncInsertHTTLYOSAN_DataTotal($intKI, $UPDAPP, $UPDCLTNM, $UPDUSER);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
            //予算指標ﾃｰﾌﾞﾙ追加(集計部署)
            $this->result = $this->FrmYosanList->fncInsertHTTLSHIHYO_DataTotal($intKI, $UPDAPP, $UPDCLTNM, $UPDUSER);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
            $result = $this->result;
        } catch (\Exception $ex) {
            $result['result'] = $this->result['result'];
            $result['data'] = $ex->getMessage();
        }
        return $result;
    }

    //-------
    //予算データ集計関連の関数　e
    //-------

    //-------
    //予算取込チェック関連の関数　s
    //-------
    public function fncCheckFile()
    {
        $result = array('result' => FALSE, 'data' => 'ErrorInfo');
        try {
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');

            if (!file_exists($pathUpLoad)) {
                mkdir($pathUpLoad, 0777, TRUE);
            }

            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                //$this -> FrmKeikenNensuIN = new FrmKeikenNensuIN();

                $file_name = $this->changeFileName($_FILES["file"]["name"]);
                $this->excelFileName = $pathUpLoad . $file_name;
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
                } else {
                    $result['result'] = FALSE;
                    $result['data'] = 'ファイルのアップロードに失敗しました。';
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncCheckFileReturn($result);
    }

    public function fncChkInput()
    {
        $result = array('MsgID' => '');
        try {
            //20160608 LI UPD S
            // if ($this -> readExcelArr[0]["KI"] == "") {
            // throw new Exception("excelファイルの「期」を入力ください！", 1);
            // }
            // if ($this -> readExcelArr[0]["BUSYO_CD"] == "") {
            // throw new Exception("excelファイルの「部署」を入力ください！！", 1);
            // }
            // if ($this -> readExcelArr[0]["LINE_NO"] == "") {
            // throw new Exception("excelファイルの「LINE_NO」を入力ください！！", 1);
            // }
            //EXCELアップロードの複数シート
            for ($i = 0; $i < $this->sheetCount; $i++) {

                if ($this->readExcelArr[$i][0]["KI"] == "") {
                    throw new \Exception("excelファイルのシート「" . $this->readExcelArr[$i][0]["SHEET"] . "」の「期」を入力ください！");
                }
                if ($this->readExcelArr[$i][0]["BUSYO_CD"] == "" || $this->readExcelArr[$i][0]["BUSYO_CD"] == 0) {
                    throw new \Exception("excelファイルのシート「" . $this->readExcelArr[$i][0]["SHEET"] . "」の「部署」を入力ください！");
                }
            }
            //20160608 LI UPD E
            $result['result'] = TRUE;
        } catch (\Exception $ex) {
            $result['MsgID'] = "E9999";
            $result['result'] = FALSE;
            $result['data'] = $ex->getMessage();
        }
        return $result;
    }

    //-------
    //予算取込チェック関連の関数　e
    //-------

    //-------
    //予算作成チェック関連の関数　s
    //-------

    /*
        '**********************************************************************
        '処 理 名：予算作成が行われているかのチェックを行う
        '関 数 名：fncChkHYOSAN
        '引    数：無し
        '戻 り 値：無し
        '処理説明：予算作成が行われているかのチェックを行う
        '**********************************************************************
        */
    public function fncChkHYOSAN($postData)
    {
        $result = array('result' => false, 'data' => 'ErrorInfo', 'MsgID' => "E9999");
        try {
            $FrmYosanList = new FrmYosanList();
            $this->result = $FrmYosanList->fncSQL2_DataTotal($postData["KEIJOBI"]);
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }
            if (count((array) $this->result['data']) <= 0) {
                $this->result['result'] = FALSE;
                throw new \Exception("予算データが存在しません！先に予算編成画面にて予算を作成してください。", 1);
            }

            $result = $this->result;
        } catch (\Exception $ex) {
            $result['result'] = $this->result['result'];
            $result['MsgID'] = "E9999";
            $result['data'] = $ex->getMessage();
        }
        return $result;
    }

    //-------
    //予算作成チェック関連の関数　e
    //-------
    //-------
    //字符変化
    //-------
    private function fncDataNullStr($obj)
    {
        if ($obj === null) {
            return "";
        } else {
            return (string) $obj;
        }
    }

    // ==========
    // = メソッド end =
    // ==========
}
