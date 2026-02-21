<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE410SyucchoTenjikaiEntry;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType as PHPExcel_Cell_DataType;
//*******************************************
// * sample controller
//*******************************************
class HMTVE410SyucchoTenjikaiEntryController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HMTVE410SyucchoTenjikaiEntry;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HMTVE410SyucchoTenjikaiEntry_layout');
    }

    //ページ初期化
    public function pageLoad()
    {
        $result = array(
            'result' => FALSE,
            'data' => array(
                'strStartDate' => "",
                'getTermDate' => "",
                'GetBusyoMstValue' => "",
                'FncBusyoMstValue' => "",
            ),
            'error' => ''
        );
        try {
            $postData = $_POST['data'];
            //時間取得
            $strStartDate = $this->ClsComFncHMTVE->FncGetSysDate("Y/m/d H:i:s");
            $result['data']['strStartDate'] = $strStartDate;

            $this->HMTVE410SyucchoTenjikaiEntry = new HMTVE410SyucchoTenjikaiEntry();
            //対象期間を取得するgetTerm
            $resultgetTerm = $this->HMTVE410SyucchoTenjikaiEntry->getTerm($postData);
            if (!$resultgetTerm['result']) {
                throw new \Exception($resultgetTerm['data']);
            }
            $result['data']['getTermDate'] = $resultgetTerm['data'];
            $Session = $this->request->getSession();
            $BusyoCD = $Session->read('BusyoCD');
            if (!isset($BusyoCD)) {
                $result['data']['msg'] = 'W9999';
                throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
            }
            //部署コード:店舗名を表示する
            $GetBusyoMstValue = $this->HMTVE410SyucchoTenjikaiEntry->FncGetBusyoMstValue($BusyoCD);

            if (!$GetBusyoMstValue['result']) {
                throw new \Exception($GetBusyoMstValue['data']);
            }
            $result['data']['GetBusyoMstValue'] = $GetBusyoMstValue['data'];
            $result['data']['SessionBusyoCD'] = $BusyoCD;
            //部署コード:店舗名を表示する
            $FncBusyoMstValue = $this->HMTVE410SyucchoTenjikaiEntry->FncBusyoMstValue();

            if (!$FncBusyoMstValue['result']) {
                throw new \Exception($FncBusyoMstValue['data']);
            }
            $result['data']['FncBusyoMstValue'] = $FncBusyoMstValue['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);

    }

    //データを取得する
    public function btnETSearchClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            //データの取得
            if (isset($_POST['request'])) {
                $isExistMeisaiData = true;
                $postData = $_POST['request'];
                $this->HMTVE410SyucchoTenjikaiEntry = new HMTVE410SyucchoTenjikaiEntry();

                //対象期間を取得する
                $resultgetTerm = $this->HMTVE410SyucchoTenjikaiEntry->getTerm($postData);
                if (!$resultgetTerm['result']) {
                    throw new \Exception($resultgetTerm['data']);
                }
                if ($resultgetTerm['row'] == 0) {
                    $isExistMeisaiData = false;
                }

                if ($postData['ddlDay'] == "" && $postData['ddlDay2'] == "" && $postData['ddlMonth'] == "" && $postData['ddlMonth2'] == "" && $postData['ddlYear'] == "" && $postData['ddlYear2'] == "") {
                    $dt1 = $this->HMTVE410SyucchoTenjikaiEntry->getIntroduction($postData, "all");
                } else {
                    $dt1 = $this->HMTVE410SyucchoTenjikaiEntry->getIntroduction($postData, "");
                }
                if (!$dt1['result']) {
                    throw new \Exception($dt1['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($dt1['data']);
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMTVE->FncCreateJqGridDataIndex($dt1["data"], $totalPage, '', $tmpCount);
                $result = $tmpJqgrid;
                $result->isExistMeisaiData = $isExistMeisaiData;
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //部署に所属する社員を取得する
    public function getEmploye()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $postData = $_POST['data'];
            $this->HMTVE410SyucchoTenjikaiEntry = new HMTVE410SyucchoTenjikaiEntry();
            $result = $this->HMTVE410SyucchoTenjikaiEntry->getEmploye($postData);
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

    //登録
    public function btnLandClick()
    {
        $this->HMTVE410SyucchoTenjikaiEntry = new HMTVE410SyucchoTenjikaiEntry();
        $result = array(
            'result' => FALSE,
            'data' => array(
                'startDate' => '',
                'getTermDate' => ''
            ),
            'error' => ''
        );

        try {
            $postData = $_POST['data'];
            //時間取得
            $startDate = $this->ClsComFncHMTVE->FncGetSysDate("Y/m/d H:i:s");
            $result['data']['startDate'] = $startDate;
            //更新対象のﾃﾞｰﾀの取得
            $resultReObject = $this->HMTVE410SyucchoTenjikaiEntry->getReObject($postData);
            if (!$resultReObject['result']) {
                throw new \Exception($resultReObject['data']);
            }

            if ($resultReObject["row"] == 0) {
                //'取得データ件数=0の場合
                //追加処理を行う
                $resultGet = $this->HMTVE410SyucchoTenjikaiEntry->getIntroInsert($postData);
            } else {
                //'取得データ件数＞0の場合
                //'更新処理を行う
                $resultGet = $this->HMTVE410SyucchoTenjikaiEntry->getIntroUpdate($postData);
            }

            if (!$resultGet['result']) {
                throw new \Exception($resultGet['data']);
            }
            //対象期間を取得するgetTerm
            $resultgetTerm = $this->HMTVE410SyucchoTenjikaiEntry->getTerm($postData);
            if (!$resultgetTerm['result']) {
                throw new \Exception($resultgetTerm['data']);
            }
            $result['data']['getTermDate'] = $resultgetTerm['data'];
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //Exceファイル生成処理
    public function btnExcelClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => '',
            'msg' => '',
        );

        try {
            //データを取得する
            $this->HMTVE410SyucchoTenjikaiEntry = new HMTVE410SyucchoTenjikaiEntry();
            if ($_POST['data']['ddlDay'] == "" && $_POST['data']['ddlDay2'] == "" && $_POST['data']['ddlMonth'] == "" && $_POST['data']['ddlMonth2'] == "" && $_POST['data']['ddlYear'] == "" && $_POST['data']['ddlYear2'] == "") {
                $dt1 = $this->HMTVE410SyucchoTenjikaiEntry->getIntroduction($_POST['data'], "all");
            } else {
                $dt1 = $this->HMTVE410SyucchoTenjikaiEntry->getIntroduction($_POST['data'], "");
            }
            if (!$dt1['result']) {
                throw new \Exception($dt1['data']);
            }

            //***Excel出力処理****
            $strPath = dirname(dirname(dirname(__FILE__)));
            $basePath = dirname($strPath);
            $filesPath = "webroot/files/HMTVE/";
            $tmpPath = $basePath . "/" . $filesPath;
            //エクセルのテンプレートが保存されている場所を取得
            $fileName = $tmpPath . '出張展示会報告.xls';
            $strTemplatePath = $this->ClsComFncHMTVE->FncGetPath('HmtveExcelLayoutPath');
            $strTemplateFile = $strPath . '/' . $strTemplatePath . 'SYUCCHOUTENJIKAI.xls';
            //出張展示会報告
            if (!file_exists($strTemplateFile)) {
                $result['msg'] = "テンプレートファイルが存在しません。";
                throw new \Exception('W9999');
            }
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && strpos($file, "出張展示会報告") !== false) {
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

            //出力Excel
            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplateFile);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', '出張展示会報告');
            //現在の時刻
            $strStartDate = $this->ClsComFncHMTVE->FncGetSysDate("Y/m/d H:i:s");
            $objActSheet->SetCellValue('E1', "出力日時：" . $strStartDate);

            //起始行
            $intRow = 5;
            if ($_POST['data']['txtExhibitTitle1'] == '') {
                $objActSheet->SetCellValue('B2', "部署：指定なし");
            } else {
                $objActSheet->SetCellValue('B2', "部署：" . $_POST['data']['txtExhibitTitle1']);
            }

            $objActSheet->SetCellValue('B3', "期間：" . $_POST['data']['ddlYear'] . "/" . $_POST['data']['ddlMonth'] . "/" . $_POST['data']['ddlDay'] . " ～ " . $_POST['data']['ddlYear2'] . "/" . $_POST['data']['ddlMonth2'] . "/" . $_POST['data']['ddlDay2']);

            //明細の行数を取得
            for ($j = 0; $j < $dt1['row']; $j++) {
                //'NO
                $objActSheet->setCellValueExplicit('A' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['LIST_MEISAI_NO']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'開始日
                $objActSheet->setCellValueExplicit('B' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['KAISAI_YMD']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'開始時刻
                $objActSheet->setCellValueExplicit('C' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['START_TIME']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'開始時刻
                $objActSheet->setCellValueExplicit('D' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['END_TIME']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'開催場所
                $objActSheet->setCellValueExplicit('E' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['PLACE']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'デモカー
                $objActSheet->setCellValueExplicit('F' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['DEMO_CARS']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'部署
                $objActSheet->setCellValueExplicit('G' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['BUSYO_CD']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'部署
                $objActSheet->setCellValueExplicit('H' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['BUSYO_RYKNM']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'社員
                $objActSheet->setCellValueExplicit('I' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['SYAIN_NM']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'来場数
                $objActSheet->setCellValueExplicit('J' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['RAIJYO_SU']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'アンケート数
                $objActSheet->setCellValueExplicit('K' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['ENQUETE_SU']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'ＡＢホット数
                $objActSheet->setCellValueExplicit('L' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['ABHOT_SU']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'見積数
                $objActSheet->setCellValueExplicit('M' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['MITUMORI_SU']), PHPExcel_Cell_DataType::TYPE_STRING);
                //'成約数
                $objActSheet->setCellValueExplicit('N' . $intRow, $this->ClsComFncHMTVE->FncNv($dt1['data'][$j]['SEIYAKU_SU']), PHPExcel_Cell_DataType::TYPE_STRING);

                //行数
                $intRow++;
            }

            //改行
            $intRow = $intRow + 3;

            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            $file = "files/HMTVE/" . "出張展示会報告.xls";

            $result['data'] = $file;
            $result['result'] = TRUE;

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

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //No
    public function getMaxNo()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            $this->HMTVE410SyucchoTenjikaiEntry = new HMTVE410SyucchoTenjikaiEntry();

            $resultMaxNo = $this->HMTVE410SyucchoTenjikaiEntry->getMaxNo();

            if (!$resultMaxNo['result']) {
                throw new \Exception($resultMaxNo['data']);
            }
            $result['data'] = $resultMaxNo['data'];
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //削除
    public function btnDeleteClick()
    {
        $this->HMTVE410SyucchoTenjikaiEntry = new HMTVE410SyucchoTenjikaiEntry();
        $res = array(
            'result' => FALSE,
            'data' => array(
                'getTermDate' => '',
                'msg' => ''
            ),
            'error' => ''
        );

        try {
            $postData = $_POST['data'];
            $result = $this->HMTVE410SyucchoTenjikaiEntry->getIntroDelete($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //ｴﾗｰﾒｯｾｰｼﾞを表示し、処理を中断
            if ($result['number_of_rows'] == '0') {
                $res['data']['msg'] = 'W0024';
                throw new \Exception($res['data']['msg']);
            }
            //対象期間を取得するgetTerm
            $resultgetTerm = $this->HMTVE410SyucchoTenjikaiEntry->getTerm($postData);
            if (!$resultgetTerm['result']) {
                throw new \Exception($resultgetTerm['data']);
            }
            $res['data']['getTermDate'] = $resultgetTerm['data'];
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        // Viewファイル呼出し
        $this->fncReturn($res);
    }

}
