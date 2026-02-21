<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJinkenhiMeisai;
use PhpOffice\PhpSpreadsheet\IOFactory;

//*******************************************
// * sample controller
//*******************************************
class FrmJinkenhiMeisaiController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $frmJinkenhiMeisai;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
        $this->loadComponent('ClsLogControl');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmJinkenhiMeisai_layout');
    }

    //画面初期化
    public function frmJinkenhiMeisaiLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->frmJinkenhiMeisai = new FrmJinkenhiMeisai();

            //部署コード
            $GetBusyoMstValue = $this->frmJinkenhiMeisai->FncGetBusyoMstValue();
            if (!$GetBusyoMstValue['result']) {
                throw new \Exception($GetBusyoMstValue['data']);
            }
            $result['data']['GetBusyoMstValue'] = $GetBusyoMstValue['data'];

            //データ取得(人事コントロールマスタ)
            $resultGetJKCMST = $this->frmJinkenhiMeisai->fncGetJKCMST();
            if (!$resultGetJKCMST['result']) {
                throw new \Exception($resultGetJKCMST['data']);
            }
            $SYORI_YM = "";
            //データが存在する場合
            if ($resultGetJKCMST['row'] > 0) {
                //日付形式を確認する
                $SYORI_YM = $resultGetJKCMST['data'][0]['SYORI_YM'];
                $date = $SYORI_YM . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }
            $result['data']['SYORI_YM'] = $SYORI_YM;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function btnExcelClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        //ログ管理のため
        $intState = 0;
        $lngOutCnt = 0;
        $baseFn = "部署別人件費明細";

        try {
            $intRec = 0;
            $strBusyoCDFrom = $_POST['data']["BusyoCDFrom"];
            $strBusyoCDTo = $_POST['data']["BusyoCDTo"];
            $strYM = $_POST['data']["TaisyouYM"];

            $basePath = dirname(dirname(dirname(__FILE__)));
            $check_result = $this->InPutCheck($basePath);
            if (!$check_result['result']) {
                throw new \Exception($check_result['error']);
            }

            if ($check_result['error'] == '') {
                //ログ管理のため
                $intState = 9;

                //パスワード情報を取得する(SQL)
                $this->frmJinkenhiMeisai = new FrmJinkenhiMeisai();
                $resultPass = $this->frmJinkenhiMeisai->fncGetPASSMST();
                if (!$resultPass['result']) {
                    throw new \Exception($resultPass['data']);
                }

                //データが存在する場合
                if ($resultPass['row'] > 0) {
                    //パスワード取得
                    $strPass = $resultPass["data"][0]["PASS"];

                    //出力データ取得
                    $DT2 = $this->frmJinkenhiMeisai->fncGetJinkenhi($strYM, $strBusyoCDFrom, $strBusyoCDTo);
                    if (!$DT2['result']) {
                        throw new \Exception($DT2['data']);
                    }

                    //***Excel出力処理****
                    //データが存在する場合
                    if ($DT2["row"] > 0) {
                        //テンプレート保存先のパスを取得する
                        $strTemplatePath = $this->ClsComFncJKSYS->FncGetPath("JksysExcelLayoutPath");
                        //$strTemplatePath = $basePath . '/' . $strTemplatePath . "FrmJinkenhiMeisaiTemplate.xlt";
                        $strTemplatePath = $basePath . '/' . $strTemplatePath . "部署別人件費明細.xlt";
                        //部署別人件費明細
                        if (!file_exists($strTemplatePath)) {
                            throw new \Exception($strTemplatePath);
                        }
                        $zipPath = $basePath . "/" . $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
                        $zipName = $baseFn . ".zip";
                        if (file_exists($zipPath . '/' . $zipName)) {
                            if (!is_writable($zipPath . '/' . $zipName)) {
                                throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
                            }
                            @unlink($zipPath . '/' . $zipName);
                        } elseif (!file_exists($zipPath . '/' . $zipName)) {
                            $dir = @opendir(dirname($zipPath . '/' . $zipName));
                            if ($dir === false) {
                                //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                                throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                            }
                            if (@readdir($dir) == false) {
                                throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                            }
                            @closedir($dir);
                        }

                        while (TRUE) {
                            $intI = $intRec;

                            //部署コード取得
                            $strBusyoCD = $DT2["data"][$intI]["BUSYO_CD"];

                            //一時出力先
                            $filePath = $check_result['filepath'];
                            $strOutPath = $filePath . "/" . $baseFn . $strBusyoCD . ".xls";

                            //Excel出力(出力データ、部署CD、一時出力先、、テンプレート、カウント、戻り値)
                            $resultIntRec = $this->CreateExcelData($DT2, $strBusyoCD, $strOutPath, $strTemplatePath, $intI, $intRec, $strYM);
                            if (!$resultIntRec['result']) {
                                throw new \Exception($resultIntRec['error']);
                            }
                            $intRec = $resultIntRec['intRec'];
                            if ($intRec == -1) {
                                break;
                            }
                        }

                        //Excelファイルパスワード設定(部署CD、一時出力先、パスワード)
                        $resultPass = $this->PassSetExcel($basePath, $baseFn, $strPass);
                        if (!$resultPass['result']) {
                            throw new \Exception($resultPass['error']);
                        }

                        $lngOutCnt = $DT2["row"];
                    } else {
                        //出力データが存在しない場合
                        $intState = 1;
                        throw new \Exception('W9999');
                    }
                } else {
                    $check_result['error'] = "passworderror";
                }
            }

            $intState = 1;
            //部署別人件費明細
            if ($intRec == -1) {
                $result['data'] = "I0011";
            } else
                if ($check_result['error'] != '') {
                    throw new \Exception($check_result['error']);
                }

            $result["result"] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        //ログ管理
        try {
            //intState<>0の場合、ログ管理テーブルに登録
            if ($intState <> 0) {
                $basePath = dirname(dirname(dirname(__FILE__)));
                $zipPath = $basePath . "/" . $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
                $zipName = $baseFn . ".zip";
                //出向社員請求明細書印刷
                $res = $this->ClsLogControl->fncLogEntryJksys("FrmJinkenhiMeisai_Excel", $intState, $lngOutCnt, $strYM, $strBusyoCDFrom, $strBusyoCDTo, $zipPath . '/' . $zipName);
                if (!$res['result']) {
                    throw new \Exception($res['Msg']);
                }
            }
        } catch (\Exception $e1) {
            $result['result'] = FALSE;
            $result['error'] = $e1->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    public function InPutCheck($basePath)
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
        );
        try {
            $tmpPath = $basePath . "/" . $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
            if ($this->ClsComFncJKSYS->FncGetPath("JksysPathFrom") == "") {
                throw new \Exception('W0001');
            }
            if (($this->ClsComFncJKSYS->FncFileExists($tmpPath)) == False) {
                $result['error'] = "W0015";
            } else
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    $result['error'] = 'フォルダのパーミッションはエラーが発生しました。';
                } else {
                    $filePath = $tmpPath . "/FrmJinkenhiMeisai";
                    if (!file_exists($filePath)) {
                        if (!mkdir($filePath, 0777, TRUE)) {
                            $result['error'] = 'フォルダのパーミッションはエラーが発生しました。';
                        }
                        chmod($filePath, 0777);
                    }
                    $result['filepath'] = $filePath;
                }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //Excel出力
    public function CreateExcelData($dt, $strBusyoCD, $strOutPath, $strTemplatePath, $intI, $intRec, $strYM)
    {
        $resultIntRec = array(
            'result' => FALSE,
            'error' => '',
            'intRec' => $intRec
        );
        try {
            //ヘッダーフラグ
            $strHFlg = "";
            //スタート行番号
            $intRow = 5;
            //件数
            $intNum = 0;
            //人件費計
            $intJinkenhi = 0;
            //1H単価
            $intOnehour = 0;
            //定時間月収
            $intTeiji = 0;
            //残業手当
            $intZangyou = 0;
            //業績奨励
            $intGyouseki = 0;
            //他業績奨励
            $intHokaGyouseki = 0;
            //其他手当
            $intSonota = 0;
            //給与計
            $intKyuyo = 0;
            //社保負担
            $intSyaho = 0;
            //賞与見積
            $intMitumori = 0;
            //賞与社保負担
            $intSyouyo_Syaho = 0;
            //人件費計_合計
            $intJinkenhi_Sum = 0;
            //1H単位_合計
            $intOnehour_Sum = 0;

            $objReader = IOFactory::createReader('Xls');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            for ($intJ = $intI; $intJ < $dt["row"]; $intJ++) {
                if ($strBusyoCD == $dt["data"][$intJ]["BUSYO_CD"]) {
                    //***** ページヘッダー *****
                    if ($strHFlg == "") {
                        //部署コード/部署名
                        $objActSheet->setCellValue('A2', "(" . $dt["data"][$intJ]["BUSYO_CD"] . ") " . $dt["data"][$intJ]["BUSYO_NM"]);
                        $objActSheet->getStyle('A2')->getFont()->setSize(11);
                        //対象月
                        $objActSheet->setCellValue('F2', substr($strYM, 0, 4) . '/' . substr($strYM, 4, 2) . "月分　人件費明細表");
                        $objActSheet->getStyle('F2')->getFont()->setSize(12);
                        //印刷日
                        $objActSheet->setCellValue('N2', date("Y/m/d"));
                        $objActSheet->getStyle('N2')->getFont()->setSize(11);

                        $strHFlg = "1";
                    }
                    //***** 明細データ *****
                    //人件費計
                    $intJinkenhi = (int) $dt["data"][$intJ]["KYUUYO_KEI"] + (int) $dt["data"][$intJ]["SYAHO"] + (int) $dt["data"][$intJ]["BNS_MITUMORI"] + (int) $dt["data"][$intJ]["SYOUYO_SYAHO"];

                    //1H単価
                    $intOnehour = round((int) ($intJinkenhi - (int) $dt["data"][$intJ]["ZANGYOU_TEATE"] - (int) $dt["data"][$intJ]["GYOUSEKI_SYOUREI"] - (int) $dt["data"][$intJ]["HOKA_GSK_SYOUREI"]) / (double) $this->ClsComFncJKSYS->GMONTHAVGTIMES);

                    $objActSheet->setCellValueExplicit('A' . $intRow, $dt["data"][$intJ]["SYAIN_NO"], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $objActSheet->setCellValue('B' . $intRow, $dt["data"][$intJ]["SYAIN_NM"]);
                    $objActSheet->setCellValue('C' . $intRow, $dt["data"][$intJ]["MEISYOU"]);
                    $objActSheet->setCellValue('D' . $intRow, $dt["data"][$intJ]["KUBUN_NM"]);

                    //定時間月収
                    $objActSheet->setCellValue('E' . $intRow, $dt["data"][$intJ]["TEIJIKAN_GESSYU"]);
                    $intTeiji += (int) $dt["data"][$intJ]["TEIJIKAN_GESSYU"];
                    //残業手当
                    $objActSheet->setCellValue('F' . $intRow, $dt["data"][$intJ]["ZANGYOU_TEATE"]);
                    $intZangyou += (int) $dt["data"][$intJ]["ZANGYOU_TEATE"];
                    //業績奨励
                    $objActSheet->setCellValue('G' . $intRow, $dt["data"][$intJ]["GYOUSEKI_SYOUREI"]);
                    $intGyouseki += (int) $dt["data"][$intJ]["GYOUSEKI_SYOUREI"];
                    //他業績奨励
                    $objActSheet->setCellValue('H' . $intRow, $dt["data"][$intJ]["HOKA_GSK_SYOUREI"]);
                    $intHokaGyouseki += (int) $dt["data"][$intJ]["HOKA_GSK_SYOUREI"];
                    //其他手当
                    $objActSheet->setCellValue('I' . $intRow, $dt["data"][$intJ]["SONOTA_TEATE"]);
                    $intSonota += (int) $dt["data"][$intJ]["SONOTA_TEATE"];
                    //給与計
                    $objActSheet->setCellValue('J' . $intRow, $dt["data"][$intJ]["KYUUYO_KEI"]);
                    $intKyuyo += (int) $dt["data"][$intJ]["KYUUYO_KEI"];
                    //社保負担
                    $objActSheet->setCellValue('K' . $intRow, $dt["data"][$intJ]["SYAHO"]);
                    $intSyaho += (int) $dt["data"][$intJ]["SYAHO"];
                    //賞与見積
                    $objActSheet->setCellValue('L' . $intRow, $dt["data"][$intJ]["BNS_MITUMORI"]);
                    $intMitumori += (int) $dt["data"][$intJ]["BNS_MITUMORI"];
                    //賞与社保負担
                    $objActSheet->setCellValue('M' . $intRow, $dt["data"][$intJ]["SYOUYO_SYAHO"]);
                    $intSyouyo_Syaho += (int) $dt["data"][$intJ]["SYOUYO_SYAHO"];
                    //人件費計
                    $objActSheet->setCellValue('N' . $intRow, $intJinkenhi);
                    $intJinkenhi_Sum += $intJinkenhi;
                    //1H単価
                    $objActSheet->setCellValue('O' . $intRow, $intOnehour);
                    $intOnehour_Sum += $intOnehour;

                    //行数
                    $intRow++;
                    //人数
                    $intNum++;
                } else {
                    //合計
                    $objActSheet->setCellValue('B' . $intRow, $intNum);
                    $objActSheet->setCellValue('C' . $intRow, "人");
                    $objActSheet->setCellValue('D' . $intRow, "合計");
                    $objActSheet->setCellValue('E' . $intRow, $intTeiji);
                    $objActSheet->setCellValue('F' . $intRow, $intZangyou);
                    $objActSheet->setCellValue('G' . $intRow, $intGyouseki);
                    $objActSheet->setCellValue('H' . $intRow, $intHokaGyouseki);
                    $objActSheet->setCellValue('I' . $intRow, $intSonota);
                    $objActSheet->setCellValue('J' . $intRow, $intKyuyo);
                    $objActSheet->setCellValue('K' . $intRow, $intSyaho);
                    $objActSheet->setCellValue('L' . $intRow, $intMitumori);
                    $objActSheet->setCellValue('M' . $intRow, $intSyouyo_Syaho);
                    $objActSheet->setCellValue('N' . $intRow, $intJinkenhi_Sum);
                    $objActSheet->setCellValue('O' . $intRow, $intOnehour_Sum);

                    //行数
                    $intRow = $intRow + 3;
                    //コメント
                    $objActSheet->setCellValue('B' . $intRow, "＜定　時　間＞・・・・・基本給＋職務手当＋家族手当（パート給与含む）");
                    $intRow++;
                    $objActSheet->setCellValue('B' . $intRow, "＜残業　手当＞・・・・・普通残業＋深夜残業");
                    $intRow++;
                    $objActSheet->setCellValue('B' . $intRow, "＜業績　奨励＞・・・・・販売業績奨励手当＋整備業績奨励手当");
                    $intRow++;
                    $objActSheet->setCellValue('B' . $intRow, "＜他　業績奨＞・・・・・上記項目「業績奨励」以外の奨励金（保険奨励金・特別奨励金等）");
                    $intRow++;
                    $objActSheet->setCellValue('B' . $intRow, "＜その他手当＞・・・・・通勤手当＋車両手当＋調整給＋研修手当（新人社員）");
                    $intRow++;
                    $objActSheet->setCellValue('B' . $intRow, "＜給　与　計＞・・・・・上記項目「定時間」「残業手当」「業績奨励」「他業績奨」「その他手当」の合計");
                    $intRow++;
                    $objActSheet->setCellValue('B' . $intRow, "＜社保　負担＞・・・・・社会保険（健康保険、厚生年金、雇用保険、労災保険、児童手当）と退職年金の会社負担額の合計");
                    $intRow++;
                    $objActSheet->setCellValue('B' . $intRow, "＜賞与　見積＞・・・・・１ヶ月分賞与の見積額");
                    $intRow++;
                    $objActSheet->setCellValue('B' . $intRow, "＜人件費　計＞・・・・・上記項目「給与計」「社保負担」「賞与見積」の合計");
                    $intRow++;
                    $objActSheet->setCellValue('B' . $intRow, "＜１Ｈ　単価＞・・・・・（人件費計ー（残業手当＋業績奨励＋他業績奨））／ 月平均時間");

                    //クローズ
                    $objActSheet->setSelectedCell("A1");
                    $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
                    $objWriter->save($strOutPath);
                    $objPHPExcel->disconnectWorksheets();
                    unset($objWriter, $objReader, $objPHPExcel);

                    $resultIntRec['intRec'] = $intJ;
                    $resultIntRec['result'] = true;
                    return $resultIntRec;
                }
            }

            //合計
            $objActSheet->setCellValue('B' . $intRow, $intNum);
            $objActSheet->setCellValue('C' . $intRow, "人");
            $objActSheet->setCellValue('D' . $intRow, "合計");
            $objActSheet->setCellValue('E' . $intRow, $intTeiji);
            $objActSheet->setCellValue('F' . $intRow, $intZangyou);
            $objActSheet->setCellValue('G' . $intRow, $intGyouseki);
            $objActSheet->setCellValue('H' . $intRow, $intHokaGyouseki);
            $objActSheet->setCellValue('I' . $intRow, $intSonota);
            $objActSheet->setCellValue('J' . $intRow, $intKyuyo);
            $objActSheet->setCellValue('K' . $intRow, $intSyaho);
            $objActSheet->setCellValue('L' . $intRow, $intMitumori);
            $objActSheet->setCellValue('M' . $intRow, $intSyouyo_Syaho);
            $objActSheet->setCellValue('N' . $intRow, $intJinkenhi_Sum);
            $objActSheet->setCellValue('O' . $intRow, $intOnehour_Sum);

            //行数
            $intRow = $intRow + 3;

            //コメント
            $objActSheet->setCellValue('B' . $intRow, "＜定　時　間＞・・・・・基本給＋職務手当＋家族手当（パート給与含む）");
            $intRow++;
            $objActSheet->setCellValue('B' . $intRow, "＜残業　手当＞・・・・・普通残業＋深夜残業");
            $intRow++;
            $objActSheet->setCellValue('B' . $intRow, "＜業績　奨励＞・・・・・販売業績奨励手当＋整備業績奨励手当");
            $intRow++;
            $objActSheet->setCellValue('B' . $intRow, "＜他　業績奨＞・・・・・上記項目「業績奨励」以外の奨励金（保険奨励金・特別奨励金等）");
            $intRow++;
            $objActSheet->setCellValue('B' . $intRow, "＜その他手当＞・・・・・通勤手当＋車両手当＋調整給＋研修手当（新人社員）");
            $intRow++;
            $objActSheet->setCellValue('B' . $intRow, "＜給　与　計＞・・・・・上記項目「定時間」「残業手当」「業績奨励」「他業績奨」「その他手当」の合計");
            $intRow++;
            $objActSheet->setCellValue('B' . $intRow, "＜社保　負担＞・・・・・社会保険（健康保険、厚生年金、雇用保険、労災保険、児童手当）と退職年金の会社負担額の合計");
            $intRow++;
            $objActSheet->setCellValue('B' . $intRow, "＜賞与　見積＞・・・・・１ヶ月分賞与の見積額");
            $intRow++;
            $objActSheet->setCellValue('B' . $intRow, "＜人件費　計＞・・・・・上記項目「給与計」「社保負担」「賞与見積」の合計");
            $intRow++;
            $objActSheet->setCellValue('B' . $intRow, "＜１Ｈ　単価＞・・・・・（人件費計ー（残業手当＋業績奨励＋他業績奨））／ 月平均時間");

            //クローズ
            $objActSheet->setSelectedCell("A1");
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
            $objWriter->save($strOutPath);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);

            $resultIntRec['intRec'] = -1;
            $resultIntRec['result'] = true;
        } catch (\Exception $e) {
            $resultIntRec['result'] = FALSE;
            $resultIntRec['error'] = $e->getMessage();
        }
        return $resultIntRec;
    }

    public function PassSetExcel($basePath, $baseFn, $strPass)
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
        );
        try {
            $cmd = "";
            $zipPath = $basePath . "/" . $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
            $zipName = $baseFn . ".zip";
            $filePath = $zipPath . "/FrmJinkenhiMeisai";
            $cmd = "cd " . $zipPath . " && \zip -r -P " . $strPass . " " . $zipName . " FrmJinkenhiMeisai";
            $rtn = exec($cmd);
            $cmd = "rm -rf " . $filePath;
            $rtn = exec($cmd);

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

}
