<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmSyukkouSeikyuPrint;
use PhpOffice\PhpSpreadsheet\IOFactory;
//*******************************************
// * sample controller
//*******************************************
class FrmSyukkouSeikyuPrintController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $FrmSyukkouSeikyuPrint;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'FrmSyukkouSeikyuPrint_layout');
    }

    //フォームロード
    public function fncLoad()
    {
        $result = array(
            'result' => false,
            'error' => '',
        );
        try {
            $this->FrmSyukkouSeikyuPrint = new FrmSyukkouSeikyuPrint();
            //人事ｺﾝﾄﾛｰﾙﾏｽﾀの取得を行う
            $tblCTL = $this->FrmSyukkouSeikyuPrint->fncJinjiCtlMstSQL();
            if (!$tblCTL['result']) {
                throw new \Exception($tblCTL['data']);
            }
            $SYORI_YM = "";
            if ($tblCTL["row"] > 0) {
                //日付形式を確認する
                $SYORI_YM = $tblCTL['data'][0]['SYORI_YM'];
                $date = $SYORI_YM . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("W9999");
            }
            $result['data']['SYORI_YM'] = $SYORI_YM;
            //出向先コンボデータ取得
            $tblCTL = $this->FrmSyukkouSeikyuPrint->fncSyukkoSakiComboSQL();
            if (!$tblCTL['result']) {
                throw new \Exception($tblCTL['data']);
            }
            $result['data']['BUSYO'] = $tblCTL;

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //
    public function fncPriClick()
    {
        $result = array(
            'result' => false,
            'error' => '',
        );
        $strPath = '';
        $strFilePath = '';

        //ログ管理のため
        $intState = 0;
        $lngOutCnt = 0;
        try {
            //テンプレートパス取得
            $strPath = dirname(dirname(dirname(__FILE__)));
            //出力先空白チェック
            $strFilePath = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
            if ($this->ClsComFncJKSYS->FncGetPath("JksysPathFrom") == "") {
                $result['msg'] = '出力先を指定してください。';
                throw new \Exception('W9999');
            }
            if (($this->ClsComFncJKSYS->FncFileExists($strFilePath)) == FALSE) {
                throw new \Exception("W0015");
            }
            if (!(is_readable($strFilePath) && is_writable($strFilePath) && is_executable($strFilePath))) {
                throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
            }

            $postData = $_POST['data'];
            $this->FrmSyukkouSeikyuPrint = new FrmSyukkouSeikyuPrint();

            //存在チェック
            $checkRes = $this->FrmSyukkouSeikyuPrint->fncGetSyukkoSakiSQL($postData);
            if (!$checkRes['result']) {
                throw new \Exception($checkRes['data']);
            }
            if ($checkRes['row'] == 0) {
                $result['msg'] = date('Y/m', strtotime($postData['taisyoYM'] . '01')) . 'の出向社員請求明細データが存在しません。出向社員請求明細情報生成を先に行ってください';
                throw new \Exception('W9999');
            }

            //ログ管理のため
            $intState = 9;
            //印刷プレビューデータの取得
            $tblCTL = $this->FrmSyukkouSeikyuPrint->fncGetPreviewDataSQL($postData);
            if (!$tblCTL['result']) {
                throw new \Exception($tblCTL['data']);
            }
            if ($tblCTL['row'] == 0) {
                //ログ管理のため
                $intState = 1;
                throw new \Exception('I0001');
            }

            include_once "Component/tcpdf/rpx_to_pdf.php";
            include_once "Component/tcpdf/rptSyukkouSeikyuPrint.inc";

            //プレビュー表示
            $lastBusyoCD = null;
            $pdfDT = array();
            $oneBusyo = array();
            foreach ((array) $tblCTL['data'] as $key => $row) {
                if ($row['BUSYO_CD'] <> $lastBusyoCD) {
                    if ($lastBusyoCD <> null) {
                        array_push($pdfDT, $oneBusyo);
                    }

                    //明细
                    $oneBusyo = array();
                    $oneBusyo['data'] = array();
                    $oneBusyo['NEN'] = $row['NEN'];
                    $oneBusyo['GETU'] = $row['GETU'];
                    $oneBusyo['BUSYO_NM'] = $row['BUSYO_NM'];
                    $oneBusyo['BUSYO_CD'] = $row['BUSYO_CD'];
                    $oneBusyo['KOTEI_TINGIN_KEI_SUM'] = 0;
                    $oneBusyo['KAIKEI_FUTAN_KEI_SUM'] = 0;
                    $oneBusyo['BNS_KEI_SUM'] = 0;
                    $oneBusyo['KIHONKYU_SUM'] = 0;
                    $oneBusyo['KENKO_HKN_RYO_SUM'] = 0;
                    $oneBusyo['BNS_GK_SUM'] = 0;
                    $oneBusyo['CHOUSEIKYU_SUM'] = 0;
                    $oneBusyo['KAIGO_HKN_RYO_SUM'] = 0;
                    $oneBusyo['BNS_KENKO_HKN_RYO_SUM'] = 0;
                    $oneBusyo['SYOKUMU_TEATE_SUM'] = 0;
                    $oneBusyo['KOUSEINENKIN_SUM'] = 0;
                    $oneBusyo['BNS_KAIGO_HKN_RYO_SUM'] = 0;
                    $oneBusyo['KAZOKU_TEATE_SUM'] = 0;
                    $oneBusyo['JIDOU_TEATE_SUM'] = 0;
                    $oneBusyo['BNS_KOUSEI_NENKIN_SUM'] = 0;
                    $oneBusyo['TUKIN_TEATE_SUM'] = 0;
                    $oneBusyo['KOYOU_HKN_RYO_SUM'] = 0;
                    $oneBusyo['BNS_JIDOU_TEATE_SUM'] = 0;
                    $oneBusyo['SYARYOU_TEATE_SUM'] = 0;
                    $oneBusyo['TAISYOKU_NENKIN_SUM'] = 0;
                    $oneBusyo['BNS_KOYOU_HOKEN_SUM'] = 0;
                    $oneBusyo['SYOUREIKIN_SUM'] = 0;
                    $oneBusyo['ROUSAI_UWA_HKN_RYO_SUM'] = 0;
                    $oneBusyo['ZANGYOU_TEATE_SUM'] = 0;
                    $oneBusyo['SYUKKOU_TEATE_SUM'] = 0;
                    $oneBusyo['JIKANSA_TEATE_SUM'] = 0;
                    $oneBusyo['FUTANKIN_KEI_SUM'] = 0;
                }

                //合计
                $oneBusyo['KOTEI_TINGIN_KEI_SUM'] += $row['KOTEI_TINGIN_KEI'];
                $oneBusyo['KAIKEI_FUTAN_KEI_SUM'] += $row['KAIKEI_FUTAN_KEI'];
                $oneBusyo['BNS_KEI_SUM'] += $row['BNS_KEI'];
                $oneBusyo['KIHONKYU_SUM'] += $row['KIHONKYU'];
                $oneBusyo['KENKO_HKN_RYO_SUM'] += $row['KENKO_HKN_RYO'];
                $oneBusyo['BNS_GK_SUM'] += $row['BNS_GK'];
                $oneBusyo['CHOUSEIKYU_SUM'] += $row['CHOUSEIKYU'];
                $oneBusyo['KAIGO_HKN_RYO_SUM'] += $row['KAIGO_HKN_RYO'];
                $oneBusyo['BNS_KENKO_HKN_RYO_SUM'] += $row['BNS_KENKO_HKN_RYO'];
                $oneBusyo['SYOKUMU_TEATE_SUM'] += $row['SYOKUMU_TEATE'];
                $oneBusyo['KOUSEINENKIN_SUM'] += $row['KOUSEINENKIN'];
                $oneBusyo['BNS_KAIGO_HKN_RYO_SUM'] += $row['BNS_KAIGO_HKN_RYO'];
                $oneBusyo['KAZOKU_TEATE_SUM'] += $row['KAZOKU_TEATE'];
                $oneBusyo['JIDOU_TEATE_SUM'] += $row['JIDOU_TEATE'];
                $oneBusyo['BNS_KOUSEI_NENKIN_SUM'] += $row['BNS_KOUSEI_NENKIN'];
                $oneBusyo['TUKIN_TEATE_SUM'] += $row['TUKIN_TEATE'];
                $oneBusyo['KOYOU_HKN_RYO_SUM'] += $row['KOYOU_HKN_RYO'];
                $oneBusyo['BNS_JIDOU_TEATE_SUM'] += $row['BNS_JIDOU_TEATE'];
                $oneBusyo['SYARYOU_TEATE_SUM'] += $row['SYARYOU_TEATE'];
                $oneBusyo['TAISYOKU_NENKIN_SUM'] += $row['TAISYOKU_NENKIN'];
                $oneBusyo['BNS_KOYOU_HOKEN_SUM'] += $row['BNS_KOYOU_HOKEN'];
                $oneBusyo['SYOUREIKIN_SUM'] += $row['SYOUREIKIN'];
                $oneBusyo['ROUSAI_UWA_HKN_RYO_SUM'] += $row['ROUSAI_UWA_HKN_RYO'];
                $oneBusyo['ZANGYOU_TEATE_SUM'] += $row['ZANGYOU_TEATE'];
                $oneBusyo['SYUKKOU_TEATE_SUM'] += $row['SYUKKOU_TEATE'];
                $oneBusyo['JIKANSA_TEATE_SUM'] += $row['JIKANSA_TEATE'];
                $oneBusyo['FUTANKIN_KEI_SUM'] += $row['FUTANKIN_KEI'];

                array_push($oneBusyo['data'], $row);

                $lastBusyoCD = $row['BUSYO_CD'];
            }

            array_push($pdfDT, $oneBusyo);

            $datas['rptSyukkouSeikyuPrint']['data'] = $pdfDT;
            $datas['rptSyukkouSeikyuPrint']['mode'] = '1';

            $rpx_file_names['rptSyukkouSeikyuPrint'] = $data_fields_rptSyukkouSeikyuPrint;
            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            //フォルダのパーミッションチェック
            if (!(is_readable($obj->REPORTS_TEMP_PATH) && is_writable($obj->REPORTS_TEMP_PATH) && is_executable($obj->REPORTS_TEMP_PATH))) {
                throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
            }
            $result['printData']['data'] = $obj->to_pdf();
            unset($obj);

            $lngOutCnt = $tblCTL['row'];
            //ログ管理のため
            $intState = 1;

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        //ログ管理
        try {
            //intState<>0の場合、ログ管理テーブルに登録
            if ($intState <> 0) {
                //出向社員請求明細書印刷
                $res = $this->ClsLogControl->fncLogEntryJksys("SyukkoSeikyuPrint", $intState, $lngOutCnt, $postData['taisyoYM'], $postData['busyoNM']);
                if (!$res['result']) {
                    throw new \Exception($res['Msg']);
                }
            }
        } catch (\Exception $e1) {
            $result['result'] = FALSE;
            $result['error'] = $e1->getMessage();
        }

        //*********EXCEL EXPORT*************
        if ($result['result']) {
            try {
                $strTemplatePath = $this->ClsComFncJKSYS->FncGetPath("JksysExcelLayoutPath");
                $strTemplateFile = $strPath . '/' . $strTemplatePath . "SyukkouSeikyuPrint.xlt";
                //テンプレートの存在確認
                if (!file_exists($strTemplateFile)) {
                    $result['msg'] = 'テンプレートファイルが存在しません。';
                    throw new \Exception('W9999');
                }

                $file = $strFilePath . "出向社員請求明細書.xls";
                if (file_exists($file) && !is_writable($file)) {
                    throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
                } elseif (!file_exists($file)) {
                    $dir = @opendir(dirname($file));
                    if ($dir === false) {
                        //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                        throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                    }
                    if (@readdir($dir) == false) {
                        throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                    }
                    @closedir($dir);
                }

                //SQL
                $DT = $this->FrmSyukkouSeikyuPrint->fncGetDataSQL($postData);
                if (!$DT['result']) {
                    throw new \Exception($DT['data']);
                }
                if ($DT['row'] > 0) {
                    $today = date("Y/m/d");
                    $objReader = IOFactory::createReader('Xls');
                    //ブックオープン
                    $objPHPExcel = $objReader->load($strTemplateFile);
                    $objPHPExcel->setActiveSheetIndex(0);
                    $objActSheet = $objPHPExcel->getActiveSheet();
                    foreach ((array) $DT['data'] as $row) {
                        //部署名取得
                        $strBName = $row['BUSYO_NM'];
                        //シートをコピーする
                        $objCloneSheet = clone $objPHPExcel->getSheet(0);
                        //シート設定
                        $objCloneSheet->setTitle($strBName);

                        $objPHPExcel->addSheet($objCloneSheet);
                        //年月
                        $objCloneSheet->setCellValue('B2', substr($postData['taisyoYM'], 0, 4));
                        $objCloneSheet->setCellValueExplicit('E2', substr($postData['taisyoYM'], 4, 2), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        //作成日
                        $objCloneSheet->setCellValue('BD1', $today);
                        //部署名
                        $objCloneSheet->setCellValue('D6', $strBName);
                        //人員
                        $objCloneSheet->setCellValue('J9', $row['NINZU'] . '人');
                        //総金額
                        $objCloneSheet->setCellValue('J11', $row['SOU_KINGAKU']);
                        //給与手当
                        $objCloneSheet->setCellValue('O13', $row['KYUYO_TEATE']);
                        //賞与
                        $objCloneSheet->setCellValue('O14', $row['SYOUYO']);
                        //退職年金
                        $objCloneSheet->setCellValue('O15', $row['TAISYOKU_NENKIN']);
                        //福利厚生費
                        $objCloneSheet->setCellValue('O16', $row['FUKURI_KOUSEIHI']);
                    }

                    //シートを削除する
                    $objPHPExcel->removeSheetByIndex(0);
                    $objPHPExcel->setActiveSheetIndex(0);
                    $objActSheet->setSelectedCell("A1");
                    $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
                    $objWriter->save($file);

                    //unset object
                    $objPHPExcel->disconnectWorksheets();
                    unset($objWriter, $objReader, $objPHPExcel);
                }
            } catch (\Exception $e) {
                $result['result'] = FALSE;
                $result['error'] = $e->getMessage();
            }
        }

        $this->fncReturn($result);
    }

}
