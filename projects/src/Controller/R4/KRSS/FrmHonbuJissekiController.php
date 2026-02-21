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
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmHonbuJisseki;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmHonbuJissekiController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $FrmHonbuJisseki;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }

    public function index()
    {
        $this->render('index', 'FrmHonbuJisseki_layout');
    }

    //コントロールマスタ存在ﾁｪｯｸ
    public function frmGetYearMonth()
    {
        $result = [];
        try {
            $this->FrmHonbuJisseki = new FrmHonbuJisseki();
            $result = $this->FrmHonbuJisseki->frmGetYearMonth();

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
        $result = [];
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
                $this->FrmHonbuJisseki = new FrmHonbuJisseki();
                //$result = $this -> fncWKDeal($this -> FrmHonbuJisseki, $postData);
                //if (!$result['result']) {
                //    throw new Exception($result['data']);
                //}
                $result = $this->FrmHonbuJisseki->fncPrintSelect($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
            // print_r($result);
            // return;
            if (!empty($result['data'])) {
                $ExcelData = $result['data'];
                // $UPDUSER = $this->FrmHonbuJisseki->GS_LOGINUSER['strUserID'];
                // include dirname(__DIR__) . '/Component/Classes/PHPExcel.php';
                $tmpPath1 = dirname(dirname(dirname(__DIR__)));
                $tmpPath2 = 'webroot/files/KRSS/';
                $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
                //20160620 Upd Start
//                $file = $tmpPath . '本部別実績表_' . $UPDUSER . '.xlsx';
                $file = $tmpPath . '本部別実績表.xlsx';
                //20160620 Upd End

                //20160620 Upd Start
                //$strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . 'FrmHonbuJissekiTemplate.xlsx';
//20210113 Upd Start
                //$strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . 'KRSS/FrmHonbuJissekiTemplate_2016.xlsx';
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . 'KRSS/FrmHonbuJissekiTemplate_2021.xlsx';
                //20210113 Upd End
//20160620 Upd End
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
                //期
                $objActSheet->setCellValue('C3', $ExcelData[0]['KI']);
                //年月
                $objActSheet->setCellValue('C4', substr($postData, 0, 4) . '年' . substr($postData, 4, 2) . '月');
                //$objActSheet -> setCellValue('D4', substr($postData, 0, 4) . '年' . substr($postData, 5, 2) . '月');

                $countCol = 0;
                //I-1列
                //$startCol = 'H';
                $startCol = 'I';
                $busyoNmRowNum = '7';
                $curBusyo = '';
                $lastBusyo = '';
                foreach ($ExcelData as $value) {
                    $curBusyo = $value['BUSYO_CD'];
                    if ($curBusyo != $lastBusyo) {
                        $startCol++;
                        $countCol++;
                        $objActSheet->setCellValue($startCol . $busyoNmRowNum, $value['BUSYO_NM']);
                    }
                    //20160620 Upd Start
//                    $objActSheet -> setCellValue($startCol . ($value['LINE_NO'] + 9), $value['JISSEKI']);
                    if (($value['LINE_NO'] + 9) <= 123) {
                        $objActSheet->setCellValue($startCol . ($value['LINE_NO'] + 9), $value['JISSEKI']);
                    }
                    //20160620 Upd End
                    $lastBusyo = $curBusyo;
                }
                $startCol++;
                //                $objActSheet -> removeColumn($startCol, (200 - $countCol));
                $objActSheet->removeColumn($startCol, (198 - $countCol));

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

    private function fncWKDeal($objFrmHonbuJisseki, $NENGTU)
    {
        $this->Session = $this->request->getSession();
        $UPDUSER = $this->Session->read('login_user');
        $UPDCLTNM = $this->request->clientIp();
        $UPDAPP = "HonbuJisseki";
        $wkDealResult = ["result" => FALSE, "data" => ""];
        $NENGTU = str_replace("/", "", $NENGTU);
        $flgT = FALSE;
        try {
            $result = $objFrmHonbuJisseki->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $objFrmHonbuJisseki->Do_transaction();

            $result = $objFrmHonbuJisseki->fncWKTRUNCATE();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //２年分のHKANRIZデータを追加
            $result = $objFrmHonbuJisseki->fncWKInsert($NENGTU, $UPDAPP, $UPDCLTNM, $UPDUSER);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //20160620 Ins Start
            $result = $objFrmHonbuJisseki->fncWKKIKANTRUNCATE();
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }

            $result = $objFrmHonbuJisseki->fncWKKIKANINSERT($NENGTU);
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"], 1);
            }
            //20160620 Ins End

            //20160620 Del Start
            //delete WK_HKANRIZ_KEIEISEIKA
//            $result = $objFrmHonbuJisseki -> fncDeleteKanr();
//            if (!$result['result']) {
//                throw new Exception($result['data']);
//            }
//20160620 Del End
            $objFrmHonbuJisseki->Do_commit();
            $flgT = TRUE;
            $wkDealResult["result"] = TRUE;

        } catch (\Exception $ex) {
            $wkDealResult["result"] = FALSE;
            $wkDealResult["data"] = $ex->getMessage();
        }
        if ($flgT == FALSE) {
            $objFrmHonbuJisseki->Do_rollback();
        }
        $objFrmHonbuJisseki->Do_close();
        return $wkDealResult;
    }

}
