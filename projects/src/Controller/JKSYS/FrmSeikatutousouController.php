<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmSeikatutousou;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmSeikatutousouController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $PHPExcel;
    public $PHPReader;
    public $objWriter;
    public $sheetNo;
    public $fullfilepath;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
        $this->loadComponent('ClsFncLogJKSYS');
    }

    public function index()
    {
        $this->render('index', 'FrmSeikatutousou_layout');
    }

    public function fncJinjiCtlMstSQL()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $FrmSeikatutousou = new FrmSeikatutousou();
            //人事ｺﾝﾄﾛｰﾙﾏｽﾀの取得を行う
            $result = $FrmSeikatutousou->fncJinjiCtlMst();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $SYORI_YM = "";
            if ($result["row"] > 0) {
                //0件以外の場合
                //対象年月日をセット
                if (isset($result['data'][0]['SYORI_YM']) && strlen($result['data'][0]['SYORI_YM']) >= 4) {
                    $SYORI_YM = substr($result['data'][0]['SYORI_YM'], 0, 4);
                    $date = $result['data'][0]['SYORI_YM'] . '01';
                    if (date('Ymd', strtotime($date)) != $date) {
                        //年月格式正しくない
                        throw new \Exception("String \"" . $result['data'][0]['SYORI_YM'] . "\" から型 'Date' への変換は無効です。");
                    }
                } else {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $result['data'][0]['SYORI_YM'] . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("W9999");
            }
            $result['SYORI_YM'] = $SYORI_YM;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function cmdExcelClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            //出力先 フォルダーが存在するかどうかのﾁｪｯｸ
            $tmpPath1 = dirname(dirname(dirname(__FILE__)));
            $tmpPath2 = $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
            $outputFilePath = $tmpPath1 . "/" . $tmpPath2;
            $strErrCode = $this->ClsFncLogJKSYS->fncOutChk($outputFilePath);
            if ($this->ClsComFncJKSYS->fncNv($strErrCode) !== "") {
                throw new \Exception($strErrCode);
            }
            if ($this->ClsComFncJKSYS->FncGetPath("JksysPathFrom") == "") {
                throw new \Exception('W0001');
            }
            if (!(is_readable($outputFilePath) && is_writable($outputFilePath) && is_executable($outputFilePath))) {
                throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
            }

            $filename = $outputFilePath . "生活闘争賃金調査基礎資料.xls";
            if (file_exists($filename) && !is_writable($filename)) {
                throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
            } elseif (!file_exists($filename)) {
                $dir = @opendir(dirname($filename));
                if ($dir === false) {
                    //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                if (@readdir($dir) == false) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                @closedir($dir);
            }

            //***Excel出力処理****
            $strTemplatePath = $this->ClsComFncJKSYS->FncGetPath("JksysExcelLayoutPath");
            $strTemplateFile = $tmpPath1 . '/' . $strTemplatePath . "FrmSeikatutousouTemplate.xlt";
            //テンプレートファイルの存在確認
            if (!file_exists($strTemplateFile)) {
                throw new \Exception("生活闘争賃金調査基礎資料のテンプレートが見つかりません！");
            }
            //Excel出力
            $params = $_POST['data'];
            $result = $this->fncExcelOutput($strTemplateFile, $filename, $params);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    public function fncExcelOutput($strTemplateFile, $filename, $params)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        $this->PHPExcel = null;
        $this->PHPReader = null;
        $this->objWriter = null;
        $this->sheetNo = 0;
        $this->fullfilepath = '';

        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );

        try {
            $DateTimePicker1 = $params['DateTimePicker1'];
            $chkNo1checked = $params['chkNo1checked'];
            $chkNo2checked = $params['chkNo2checked'];
            $FrmSeikatutousou = new FrmSeikatutousou();

            $this->PHPReader = IOFactory::createReader('Xls');
            $this->PHPExcel = $this->PHPReader->load($strTemplateFile);

            //画面.調査票No.1にチェックが入っている場合
            if ($chkNo1checked == "true") {
                //---調査票1データ取得---
                $result = $FrmSeikatutousou->fncCyousahyou1($DateTimePicker1);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['row'] == 0) {
                    throw new \Exception('I0001');
                }
                if ($result['data'][0]['KENSUU'] == 0) {
                    //シート削除
                    $this->PHPExcel->removeSheetByIndex(0);
                } else {
                    $this->PHPExcel->setActiveSheetIndex($this->sheetNo);
                    $xlsCreator = $this->PHPExcel->getActiveSheet();
                    $xlsCreator->setTitle('調査票№1');
                    $xlsCreator->setCellValue("A" . 2, $DateTimePicker1 . "生活闘争賃金調査基礎資料（調査票№1）");
                    $xlsCreator->setCellValue("A" . 5, "１．基本的な実態（" . $DateTimePicker1 . "年 12月 31日　現在）");

                    $xlsCreator->setCellValue("B" . 7, $result['data'][0]['M1_JYUSUU_M']);
                    $xlsCreator->setCellValue("D" . 7, $result['data'][0]['M1_JYUSUU_W']);
                    $xlsCreator->setCellValue("F" . 7, $result['data'][0]['M1_JYUSUU']);

                    $xlsCreator->setCellValue("B" . 8, $result['data'][0]['M1_KUMISUU_M']);
                    $xlsCreator->setCellValue("D" . 8, $result['data'][0]['M1_KUMISUU_W']);
                    $xlsCreator->setCellValue("F" . 8, $result['data'][0]['M1_KUMISUU']);

                    $xlsCreator->setCellValue("B" . 9, $result['data'][0]['M1_KUMIAVNEN_M']);
                    $xlsCreator->setCellValue("D" . 9, $result['data'][0]['M1_KUMIAVNEN_W']);
                    $xlsCreator->setCellValue("F" . 9, $result['data'][0]['M1_KUMIAVNEN']);

                    $xlsCreator->setCellValue("B" . 10, $result['data'][0]['M1_KUMIAVKIN_M']);
                    $xlsCreator->setCellValue("D" . 10, $result['data'][0]['M1_KUMIAVKIN_W']);
                    $xlsCreator->setCellValue("F" . 10, $result['data'][0]['M1_KUMIAVKIN']);

                    $xlsCreator->setCellValue("B" . 11, $result['data'][0]['M1_KUMIAVFUSUU_M']);
                    $xlsCreator->setCellValue("D" . 11, $result['data'][0]['M1_KUMIAVFUSUU_W']);
                    $xlsCreator->setCellValue("F" . 11, $result['data'][0]['M1_KUMIAVFUSUU']);

                    $xlsCreator->setCellValue("B" . 12, $result['data'][0]['M1_KUMIAVKIKYU_M']);
                    $xlsCreator->setCellValue("D" . 12, $result['data'][0]['M1_KUMIAVKIKYU_W']);
                    $xlsCreator->setCellValue("F" . 12, $result['data'][0]['M1_KUMIAVKIKYU']);

                    $xlsCreator->setCellValue("B" . 13, $result['data'][0]['M1_KUMIAVTEGE_M']);
                    $xlsCreator->setCellValue("D" . 13, $result['data'][0]['M1_KUMIAVTEGE_W']);
                    $xlsCreator->setCellValue("F" . 13, $result['data'][0]['M1_KUMIAVTEGE']);

                    $xlsCreator->setCellValue("D" . 18, $result['data'][0]['M2_KIHONKYU']);
                    $xlsCreator->setCellValue("D" . 20, $result['data'][0]['M2_SYOKUMUTE']);
                    $xlsCreator->setCellValue("D" . 21, $result['data'][0]['M2_KAZOKUTE']);
                    $this->sheetNo = $this->sheetNo + 1;
                }
            } else {
                //シート削除
                $this->PHPExcel->removeSheetByIndex($this->sheetNo);
            }

            //画面.調査票No.2にチェックが入っている場合
            if ($chkNo2checked == "true") {
                //---調査票2データ取得--
                $result = $FrmSeikatutousou->fncCyousahyou2($DateTimePicker1);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['row'] == 0) {
                    //シート削除
                    $this->PHPExcel->removeSheetByIndex($this->sheetNo);
                } else {
                    $iCellPos = 9;
                    $rowCount = 0;
                    $barFlg = FALSE;
                    $this->PHPExcel->setActiveSheetIndex($this->sheetNo);
                    $xlsCreator = $this->PHPExcel->getActiveSheet();

                    $xlsCreator->setTitle('調査票№2');
                    $xlsCreator->setCellValue("A" . 2, $DateTimePicker1 . "生活闘争賃金調査基礎資料（調査票№2）");

                    for ($i = 18; $i <= 65; $i++) {
                        if ($result['data'][$rowCount]['NENREI'] < 18) {
                            $rowCount = $rowCount + 1;
                            $i = $i - 1;
                            continue;
                        }
                        if ($i == $result['data'][$rowCount]['NENREI']) {
                            $xlsCreator->setCellValue("B" . $iCellPos, $result['data'][$rowCount]['KIHONKYUU']);
                            $xlsCreator->setCellValue("C" . $iCellPos, $result['data'][$rowCount]['TEIJI']);
                            $xlsCreator->setCellValue("D" . $iCellPos, $result['data'][$rowCount]['NINZUU']);
                            $rowCount = $rowCount + 1;
                            $barFlg = TRUE;
                        } else
                            if (($barFlg == TRUE) and ($rowCount < count((array) $result['data']))) {
                                $xlsCreator->setCellValue("B" . $iCellPos, "-");
                                $xlsCreator->setCellValue("C" . $iCellPos, "-");
                                $xlsCreator->setCellValue("D" . $iCellPos, 0);
                            }
                        if ($rowCount == count((array) $result['data'])) {
                            break;
                        }
                        $iCellPos = $iCellPos + 1;
                    }
                    $this->sheetNo = $this->sheetNo + 1;
                }
            } else {
                //シート削除
                $this->PHPExcel->removeSheetByIndex($this->sheetNo);
            }

            if ($this->sheetNo == 0) {
                throw new \Exception('I0001');
            }

            $this->PHPExcel->setActiveSheetIndex(0);
            $this->objWriter = IOFactory::createWriter($this->PHPExcel, 'Xls');
            $this->fullfilepath = $filename;
            $this->objWriter->save($this->fullfilepath);
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    public function finally()
    {
        if (isset($this->objWriter)) {
            unset($this->objWriter);
        }

        if (isset($this->PHPReader)) {
            unset($this->PHPReader);
        }

        if (isset($this->PHPExcel)) {
            $this->PHPExcel->disconnectWorksheets();
            unset($this->PHPExcel);
        }

        if (($this->sheetNo === 0) && ($this->fullfilepath !== '' && file_exists($this->fullfilepath))) {
            @unlink($this->fullfilepath);
        }
    }

}
