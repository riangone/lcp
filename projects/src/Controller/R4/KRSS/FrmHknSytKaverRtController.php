<?php
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmHknSytKaverRt;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmHknSytKaverRtController extends AppController
{
    public $FrmHknSytKaverRt;
    public $autoLayout = TRUE;
    private $result;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }

    public function index()
    {
        $this->render('index', 'FrmHknSytKaverRt_layout');
    }

    public function fncHKEIRICTL()
    {
        $this->FrmHknSytKaverRt = new FrmHknSytKaverRt();
        try {
            $this->result = $this->FrmHknSytKaverRt->fncHKEIRICTL();
            if ($this->result['result'] == FALSE) {
                throw new \Exception($this->result['data'], 1);
            }

            //コントロールマスタが存在していない場合
            if (count((array) $this->result['data']) <= 0) {
                throw new \Exception("コントロールマスタが存在しません！");
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    /*'**********************************************************************
     '処 理 名：エクセル出力
     '関 数 名：Button1_Click
     '引    数：無し
     '戻 り 値：無し
     '処理説明：エクセルを出力する
     '**********************************************************************
     */
    public function cmbExcelClick()
    {
        $excelData = "";
        $postData = $_POST['data'];
        $blnTranFlg = FALSE;
        $this->FrmHknSytKaverRt = new FrmHknSytKaverRt();
        try {
            $blnTranFlg = TRUE;

            //トランザクション開始
            $conn = $this->FrmHknSytKaverRt->Do_conn();
            if (!$conn['result']) {
                throw new \Exception($conn['data']);
            }
            $this->FrmHknSytKaverRt->Do_transaction();
            $this->result = $this->FrmHknSytKaverRt->fncDeleteWk_HknSytKanr();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $this->result = $this->FrmHknSytKaverRt->fncHknSytBusyoSyukei($postData);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $this->result = $this->FrmHknSytKaverRt->fncHknSytLineSyukei();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->FrmHknSytKaverRt->Do_commit();
            $blnTranFlg = FALSE;

            //***Excel出力処理****
            //----Excelデータ----
            $this->result = $this->FrmHknSytKaverRt->fncPrintSelect($postData);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            if (count((array) $this->result['data']) > 0) {
                $excelData = $this->result['data'];
                $exlresult = $this->excelmake($excelData);
                if (!$exlresult['result']) {
                    throw new \Exception($exlresult['data']);
                }
                $this->result['data'] = $exlresult['data'];
            }
            $this->result['result'] = TRUE;
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        if ($blnTranFlg) {
            $this->result['result'] = FALSE;
            $this->FrmHknSytKaverRt->Do_rollback();
            $this->FrmHknSytKaverRt->Do_close();
        }
        $this->fncReturn($this->result);
    }

    public function excelmake($excelData)
    {
        try {
            $result = array('result' => TRUE, 'data' => "");
            $USERID = $this->FrmHknSytKaverRt->GS_LOGINUSER['strUserID'];
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/KRSS/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
            $file = $tmpPath . "保険限界利益固定費カバー率_" . $USERID . ".xlsx";

            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Execl Error";
                    throw new \Exception($result["data"]);
                }
            }

            //エクセルのテンプレートが保存されている場所を取得
            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmHknSytKaverRtTemplate.xlsx";
            //テンプレートファイルの存在確認
            if (file_exists($strTemplatePath) == FALSE) {
                $result["data"] = "EXCELテンプレートが見つかりません！";
                throw new \Exception($result["data"]);
            }

            $objReader = IOFactory::createReader('Xlsx');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();

            //20160915 Upd  Start 和暦廃止
//            $objActSheet -> setCellValue('B' . 3, "平成" . $excelData[0]['NEN'] . "年" . $excelData[0]['TUKI'] . "月");
            $objActSheet->setCellValue('B' . 3, $excelData[0]['NEN'] . "年" . $excelData[0]['TUKI'] . "月");
            //20160915 Upd  End 和暦廃止            
            $ColumnToKey = array('A' => 'TOU_JUNI', 'B' => 'BUSYO_NM', 'C' => 'TOU_HKNSYT', 'D' => 'TOU_KOTEI', 'E' => 'TOU_KAVER_RT', 'F' => 'TKI_HKNSYT', 'G' => 'TKI_KOTEI', 'H' => 'TKI_KAVER_RT', 'I' => 'TKI_JUNI');
            //start row num.
            $RowCnt = 7;
            foreach ($excelData as $value) {
                foreach ($ColumnToKey as $key1 => $value1) {
                    $objActSheet->setCellValue($key1 . $RowCnt, $value[$value1]);
                }
                $RowCnt++;
            }
            $RowCnt++;
            $objActSheet->setCellValue('B' . $RowCnt, "※固定費カバー率＝(保険収入手数料－保険販売費)÷固定費(除く整備員給与・再生員給与・本社勘定)×100");
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
            $objWriter->save($file);
            $filePath = "files/KRSS/" . "保険限界利益固定費カバー率_" . $USERID . ".xlsx";
            $result['data'] = $filePath;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }
}
