<?php
/**
 * 説明：
 *
 *
 * @author yushuangji
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150916	#2149		BUG		yinhuaiyu
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmKanrChkList;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as PhpSpreadsheetXlsx;
use Cake\Log\Log;

class FrmKanrChkListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public $FrmKanrChkList = "";
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmKanrChkList_layout');
    }

    public function fncGetBusyo()
    {
        $result = array();
        try {
            $this->FrmKanrChkList = new FrmKanrChkList();
            $result = $this->FrmKanrChkList->fncGetBusyo();
            $result = $this->ClsComFnc->FncGetKamokuMstValue();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncGetKamokuNM()
    {
        $result = array();
        try {
            $this->FrmKanrChkList = new FrmKanrChkList();
            $result = $this->FrmKanrChkList->fncGetKamokuNM();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function frmKanrSyukeiLoad()
    {
        $result = array();
        try {
            $this->FrmKanrChkList = new FrmKanrChkList();
            $result = $this->FrmKanrChkList->selectData();
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
    //関 数 名：cmdEnd_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：科目別費用明細を印刷する
    //**********************************************************************
    public function cmdActionClick()
    {

        $result = array();
        $postArr = array();
        try {

            $postArr = $_POST['data'];
            $this->FrmKanrChkList = new FrmKanrChkList();
            $result = $this->FrmKanrChkList->fncPrintSelect($postArr['cboKisyu'], $postArr['cboYM'], $postArr['txtKamokuCDFrom'], $postArr['txtKamokuCDTo']);
            if ($result['result'] == FALSE) {

                throw new \Exception($result['data'], 1);
            } else if (count((array) $result['data']) <= 0) {
                $result['result'] = FALSE;
                $result['MsgID'] = "I0001";
                throw new \Exception("no data,error");
            }
            $USERID = $this->FrmKanrChkList->GS_LOGINUSER['strUserID'];

            //set output file path
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/KRSS/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;

            //set outputfile name
            $file = $tmpPath . "部署別経営成果チェックリスト_" . $USERID . ".xlsx";

            //path is exist
            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Execl Error";
                    throw new \Exception($result["data"]);
                }
            }

            //エクセルのテンプレートが保存されている場所を取得
            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmKanrChkListTemplate.xlsx";
            //テンプレートファイルの存在確認
            if (file_exists($strTemplatePath) == FALSE) {
                $result["data"] = "EXCELテンプレートが見つかりません！";
                throw new \Exception($result["data"]);
            }

            $PHPExcel = new Spreadsheet();
            //Reading a spreadsheet From Local

            $PHPReader = new Xlsx();
            $PHPExcel = $PHPReader->load($strTemplatePath);

            $i = 1;
            while ($PHPExcel->getActiveSheet()->getCell('A' . $i)->getValue() != 'KAMOKU_CD') {
                $i++;
            }

            $location = $i;

            //Get zhe highest column than when it not null
            $date = $PHPExcel->getActiveSheet()->getHighestColumn();

            $ABC = array();
            for ($i = 'A'; $i != $date; $i++) {
                array_push($ABC, $i);
            }
            array_push($ABC, $date);
            $celli = array();
            foreach ($ABC as $value) {
                $objPHPExcel = $PHPExcel->getActiveSheet()->getcell($value . $location)->getValue();
                if ($objPHPExcel !== null) {
                    $replace = str_replace("{", "", $objPHPExcel);
                    $replacevalue2 = str_replace("}", "", $replace);
                    $celli[$value] = $replacevalue2;
                }
            }
            $po = '100000';
            $po1 = '100000';
            $po2 = '100000';
            $po3 = '100000';
            $ZAN = 0;
            $LGK = 0;
            $RGK = 0;
            $TOUZAN = 0;
            $TOUKI_ZANDAKA = 0;
            $ZEN_DOUGETU = 0;
            $ZEN_DOUNEN = 0;
            foreach ((array) $result['data'] as $value1) {

                if ($value1["KAMOKU_CD"] == $po && $value1["HIMOKU_CD"] == $po1 && $value1["KAMOKUMEI"] == $po2 && $value1["LINE_NO"] == $po3) {
                } else {
                    if ($po == '100000') {
                    } else {
                        //if ($ZAN = 0 && $LGK = 0 && $RGK = 0 && $TOUZAN = 0 && $TOUKI_ZANDAKA = 0 && $ZEN_DOUGETU = 0 && $ZEN_DOUNEN = 0) {
                        if ($ZAN == 0 && $LGK == 0 && $RGK == 0 && $TOUZAN == 0 && $TOUKI_ZANDAKA == 0 && $ZEN_DOUGETU == 0 && $ZEN_DOUNEN == 0) {
                        } else {
                            foreach ($celli as $key2 => $value2) {
                                switch ($value2) {
                                    case 'BUSYO_CD':
                                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, "合計");
                                        break;
                                    case 'ZAN':
                                        //	$PHPExcel -> getActiveSheet(0) -> setCellValue($key2 . $location, (int)$ZAN);
                                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($ZAN));
                                        Log::error('残合計：' . $ZAN);
                                        break;
                                    case 'LGK':
                                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($LGK));
                                        break;
                                    case 'RGK':
                                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($RGK));
                                        break;
                                    case 'TOUZAN':
                                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($TOUZAN));
                                        break;
                                    case 'TOUKI_ZANDAKA':
                                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($TOUKI_ZANDAKA));
                                        break;
                                    case 'ZEN_DOUGETU':
                                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($ZEN_DOUGETU));
                                        break;
                                    case 'ZEN_DOUNEN':
                                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($ZEN_DOUNEN));
                                        break;
                                }
                            }
                            $ZAN = 0;
                            $LGK = 0;
                            $RGK = 0;
                            $TOUZAN = 0;
                            $TOUKI_ZANDAKA = 0;
                            $ZEN_DOUGETU = 0;
                            $ZEN_DOUNEN = 0;
                            $location = $location + 2;

                        }
                    }
                }

                foreach ($celli as $key2 => $value2) {

                    switch ($value2) {
                        case 'KAMOKU_CD':
                            if ($value1[$value2] === $po) {

                            } else {

                                $PHPExcel->getActiveSheet()->setCellValueExplicit($key2 . $location, $value1[$value2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                            }
                            break;
                        case 'HIMOKU_CD':
                            if ($value1[$value2] === $po1) {

                            } else {
                                $PHPExcel->getActiveSheet()->setCellValueExplicit('A' . $location, $value1["KAMOKU_CD"], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                                $PHPExcel->getActiveSheet()->setCellValueExplicit('D' . $location, $value1["LINE_NO"], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                                $PHPExcel->getActiveSheet()->setCellValueExplicit($key2 . $location, $value1[$value2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                            }
                            break;
                        case 'KAMOKUMEI':
                            if ($value1[$value2] == $po2) {

                            } else {
                                $PHPExcel->getActiveSheet()->setCellValueExplicit($key2 . $location, $value1[$value2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                            }
                            break;
                        case 'LINE_NO':
                            if ($value1[$value2] == $po3) {

                            } else {
                                $PHPExcel->getActiveSheet()->setCellValueExplicit($key2 . $location, $value1[$value2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                            }
                            break;
                        case 'BUSYO_CD':
                            $PHPExcel->getActiveSheet()->setCellValueExplicit($key2 . $location, $value1[$value2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                            break;
                        case 'BUSYOMEI':
                            $PHPExcel->getActiveSheet()->setCellValueExplicit($key2 . $location, $value1[$value2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                            break;
                        case '':
                            break;
                        default:
                            $PHPExcel->getActiveSheet()->setCellValueExplicit($key2 . $location, number_format($value1[$value2]), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                            switch ($value2) {
                                case 'ZAN':
                                    $ZAN = (int) $ZAN + (int) ($value1["ZAN"]);
                                    Log::error('残：' . $ZAN . ' ZAN:' . $value1["ZAN"]);
                                    break;
                                case 'LGK':
                                    $LGK = (int) $LGK + (int) ($value1["LGK"]);
                                    break;
                                case 'RGK':
                                    $RGK = (int) $RGK + (int) ($value1["RGK"]);
                                    break;
                                case 'TOUZAN':
                                    $TOUZAN = (int) $TOUZAN + (int) ($value1["TOUZAN"]);
                                    break;
                                case 'TOUKI_ZANDAKA':
                                    $TOUKI_ZANDAKA = (int) $TOUKI_ZANDAKA + (int) ($value1["TOUKI_ZANDAKA"]);
                                    break;
                                case 'ZEN_DOUGETU':
                                    $ZEN_DOUGETU = (int) $ZEN_DOUGETU + (int) ($value1["ZEN_DOUGETU"]);
                                    break;
                                case 'ZEN_DOUNEN':
                                    $ZEN_DOUNEN = (int) $ZEN_DOUNEN + (int) ($value1["ZEN_DOUNEN"]);
                                    break;
                            }

                            break;
                    }

                }

                $po = $value1["KAMOKU_CD"];
                $po1 = $value1["HIMOKU_CD"];
                $po2 = $value1["KAMOKUMEI"];
                $po3 = $value1["LINE_NO"];

                $location = $location + 1;

            }

            foreach ($celli as $key2 => $value2) {
                switch ($value2) {
                    case 'BUSYO_CD':
                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, "合計");
                        break;
                    case 'ZAN':
                        //	$PHPExcel -> getActiveSheet(0) -> setCellValue($key2 . $location, (int)$ZAN);
                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($ZAN));
                        break;
                    case 'LGK':
                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($LGK));
                        break;
                    case 'RGK':
                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($RGK));
                        break;
                    case 'TOUZAN':
                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($TOUZAN));
                        break;
                    case 'TOUKI_ZANDAKA':
                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($TOUKI_ZANDAKA));
                        break;
                    case 'ZEN_DOUGETU':
                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($ZEN_DOUGETU));
                        break;
                    case 'ZEN_DOUNEN':
                        $PHPExcel->getActiveSheet()->setCellValue($key2 . $location, number_format($ZEN_DOUNEN));
                        break;
                }
            }
            $ZAN = 0;
            $LGK = 0;
            $RGK = 0;
            $TOUZAN = 0;
            $TOUKI_ZANDAKA = 0;
            $ZEN_DOUGETU = 0;
            $ZEN_DOUNEN = 0;
            $location = $location + 2;

            $PHPExcel->getActiveSheet()->setCellValue("B2", $value1["NEN"] . "年" . $value1["TUKI"] . "月");

            $PHPExcel->getActiveSheet()->setCellValue("I3", "DATE:");
            $PHPExcel->getActiveSheet()->setCellValue("J3", $value1["TODAY"]);

            $objWriter = new PhpSpreadsheetXlsx($PHPExcel);

            $objWriter->save($file);
            $result['data'] = "files/KRSS/" . "部署別経営成果チェックリスト_" . $USERID . ".xlsx";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
