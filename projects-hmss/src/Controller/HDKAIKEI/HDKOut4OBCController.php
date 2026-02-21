<?php
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKOut4OBC;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

//*******************************************
// * sample controller
//*******************************************
class HDKOut4OBCController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HDKOut4OBC = null;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->Session = $this->request->getSession();
        $this->Session->delete("HDKOut4OBC_XLSX_TYPE_RECHK");
        // Viewファイル呼出し
        $this->render('index', 'HDKOut4OBC_layout');
    }

    //出力グループ名の重複チェック
    public function fncChkExistGroupNM()
    {
        $this->HDKOut4OBC = new HDKOut4OBC();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $res = $this->HDKOut4OBC->FncChkExistGroupNMSql($_POST['data']['lvTxtGroupName']);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                if ($res['row'] != 0) {
                    if ($res['data'][0]["COUNT(*)"] != 0) {
                        throw new \Exception("repeatErr");
                    }
                }
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    public function getShiwakeData($strSyohyoNo, $Mode, $retXLSXFLG, $chgColor)
    {
        $this->HDKOut4OBC = new HDKOut4OBC();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $DT = $this->HDKOut4OBC->FncChkAndSetShiwakeInfoSql($strSyohyoNo);
            if (!$DT['result']) {
                throw new \Exception($DT['data']);
            }
            if ($DT['row'] == 0) {
                if ($Mode > 0) {
                    $res['data']['errorMsg'] = "該当するデータは登録されていません！";

                }
                return $res;
            } else {
                if ($Mode > 0) {
                    if (substr($strSyohyoNo, 15, 2) < $DT['data'][0]['EDA_NO']) {
                        $res['data']['errorMsg'] = "notnew";
                        if ($Mode == 2) {
                            $res['data']['chgColor'] = "1";
                        }
                        return $res;
                    } else
                        if (substr($strSyohyoNo, 15, 2) > $DT['data'][0]['EDA_NO']) {
                            $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "に該当するデータは登録されていません！";
                            return $res;
                        }
                }
                if ($Mode > 0 && $DT['data'][0]['ＣＳＶ出力フラグ'] == "1") {
                    $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "のデータは既にＯＢＣ出力されています！";
                    return $res;
                }
                if ($Mode > 0 && $DT['data'][0]['削除フラグ'] == "1") {
                    $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "のデータは既に削除されています！";
                    return $res;
                }
                if ($Mode > 0 && $DT['data'][0]['印刷フラグ'] == "0") {
                    $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "のデータは伝票印刷が行われていません！";
                    return $res;
                }

            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    //Gridへのデータセット
    public function fncSetData()
    {
        $this->HDKOut4OBC = new HDKOut4OBC();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $data = $_POST['request'];
                $DT = $this->HDKOut4OBC->FncSetDataSql($data);
                if (!$DT['result']) {
                    throw new \Exception($DT['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($DT['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $res = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataIndex($DT['data'], $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //Excel出力ボタンクリック
    public function btnXlsxOutClick()
    {
        $this->HDKOut4OBC = new HDKOut4OBC();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $res['data']['tranStartFlg'] = FALSE;
        try {
            $this->Session = $this->request->getSession();
            $postData = $_POST['data'];
            $resCheck = $this->inputCheck($postData);
            if (!$resCheck['result']) {
                $res['data'] = $resCheck['data'];
                $res['html'] = $resCheck['html'];
                throw new \Exception('W0034');
            }
            //証憑№のチェック
            $res['data']['type'] = "FncChkAndSetShiwakeInfo";
            if (isset($_POST['data']['lvGvList']) && count($_POST['data']['lvGvList']) > 0) {
                $lvGvList = $_POST['data']['lvGvList'];
                //読取書類
                $this->Session->write('HDKOut4OBC_XLSX_TYPE_RECHK', $lvGvList[0]['SYOHYO_KBN']);
                $chooseDataArr = array();
                for ($i = 0; $i < count($lvGvList); $i++) {
                    $strSyohyoNo = $lvGvList[$i]['SYOHYO_NO_VIEW'];
                    $chgColor = "0";
                    $FncChkAndSetShiwakeInfo = $this->getShiwakeData($strSyohyoNo, 2, "", $chgColor);
                    if (!$FncChkAndSetShiwakeInfo['result']) {
                        if ($FncChkAndSetShiwakeInfo['data']['errorMsg']) {

                            if ($FncChkAndSetShiwakeInfo['data']['errorMsg'] == 'notnew') {
                                $resData = array(
                                    'chgColor' => isset($FncChkAndSetShiwakeInfo['data']['chgColor']) ? $FncChkAndSetShiwakeInfo['data']['chgColor'] : $chgColor,
                                    'rowNum' => $lvGvList[$i]['rowId'],
                                    'no' => $strSyohyoNo
                                );
                                array_push($chooseDataArr, $resData);
                            } else {
                                $res['data']['errorMsg'] = $FncChkAndSetShiwakeInfo['data']['errorMsg'];
                                throw new \Exception("W9999");
                            }
                        } else {
                            throw new \Exception($FncChkAndSetShiwakeInfo['error']);
                        }

                    }

                }
                if (count($chooseDataArr) > 0) {
                    $msg = '';
                    for ($c = 0; $c < count($chooseDataArr); $c++) {
                        $msg .= "証憑№:" . $chooseDataArr[$c]['no'] . "<br/>";
                    }
                    $res['data']['errorMsg'] = $msg . "データは最新ではありません！";
                    $res['data']['chooseData'] = $chooseDataArr;
                    throw new \Exception("W9999");
                }
            }
            $res['data']['type'] = "XlsxOut";
            //グループ№の最新取得
            $groupNoRes = $this->HDKOut4OBC->FncGetGroupNoSql();
            if (!$groupNoRes['result']) {
                throw new \Exception($groupNoRes['data']);
            }
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }

            $sysDate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Y/m/d H:i:s");
            $groupNo = $groupNoRes['row'] > 0 ? $groupNoRes['data'][0]["NVL(MAX(A.CSV_GROUP_NO),0)+1"] : "1";
            $lvTxtKeiriSyoribi = date_format(date_create($_POST['data']['lvTxtKeiriSyoribi']), "Ymd");

            //トランザクション開始
            $this->HDKOut4OBC->Do_transaction();
            $res['data']['tranStartFlg'] = TRUE;

            $params = array(
                //出力グループ名
                'lvTxtGroupName' => $_POST['data']['lvTxtGroupName'],
                // 経理処理日
                'lvTxtKeiriSyoribi' => $lvTxtKeiriSyoribi,
                'groupNo' => $groupNo,
                'sysDate' => $sysDate
            );
            //出力グループの登録
            $insRes = $this->HDKOut4OBC->SubInsertGroupDataSql($params);
            if (!$insRes['result']) {
                throw new \Exception($insRes['data']);
            }
            $BusyoCD = $this->Session->read('BusyoCD');
            if (isset($BusyoCD) == FALSE) {
                $res['data']['msg'] = 'W9999';
                throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
            }
            //仕訳データの更新
            if (isset($_POST['data']['lvGvList']) && count($_POST['data']['lvGvList']) > 0) {
                $params['PatternID'] = $this->Session->read('PatternID');
                $params['BusyoCD'] = $BusyoCD;
                $params['CONST_ADMIN_PTN_NO'] = $_POST['data']['CONST_ADMIN_PTN_NO'];
                $params['CONST_HONBU_PTN_NO'] = $_POST['data']['CONST_HONBU_PTN_NO'];
                $lvGvList = $_POST['data']['lvGvList'];
                for ($i = 0; $i < count($lvGvList); $i++) {
                    $params['intXlsxOutOrd'] = count($lvGvList) - $i;
                    $upd = $this->HDKOut4OBC->SubUpdateSyohyoDataSql($lvGvList[$i], $params);
                    if (!$upd['result']) {
                        throw new \Exception($upd['data']);
                    }
                }
            }
            //コミット
            $this->HDKOut4OBC->Do_commit();
            $res['data']['tranStartFlg'] = FALSE;

            $sessionArray = array(
                'HDKOut4OBC_XLSXType' => $this->Session->read("HDKOut4OBC_XLSX_TYPE_RECHK") == '仕訳伝票' ? "0" : "1",
                'HDKOut4OBC_GroupNo' => $groupNo,
                'HDKOut4OBC_GroupNM' => $_POST['data']['lvTxtGroupName'],
                'HDKOut4OBC_sysDate' => $sysDate,
                'login_user' => $this->Session->read("login_user")
            );
            $this->Session->delete("HDKOut4OBC_XLSX_TYPE_RECHK");
            //Excel出力
            $strTemplatePath1 = $this->ClsComFncHDKAIKEI->FncGetPath("HDKAIKEIExcelLayoutPath");
            $download = $this->CSVDownload($sessionArray, $strTemplatePath1);
            if (!$download['result']) {
                throw new \Exception($download['error']);
            }
            $res['data']['url'] = $download['data']['url'];
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($res['data']['tranStartFlg']) {
                $this->HDKOut4OBC->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //Excel出力処理(実行)
    public function CSVDownload($sessionArray, $strTemplatePath1)
    {
        $this->HDKOut4OBC = new HDKOut4OBC();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            //出力先パス
            $strPath = dirname(dirname(dirname(__FILE__)));
            $tmpPath1 = dirname($strPath);
            $tmpPath2 = "webroot/files/HDKAIKEI/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            //フォルダーが存在するかどうかのﾁｪｯｸ
            $strTemplatePath = $strPath . '/' . $strTemplatePath1 . "HDKOut4OBCTemplate.xlsx";
            if (!file_exists($strTemplatePath)) {
                throw new \Exception('W9999');
            }
            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            } else {
                $outFloder = dirname($tmpPath);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                mkdir($tmpPath, 0777, TRUE);
            }

            //***Excel出力処理****
            $dt = $this->HDKOut4OBC->XLSXDownloadSql($sessionArray['HDKOut4OBC_GroupNo'], $sessionArray['HDKOut4OBC_XLSXType']);
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            if ($dt['row'] == 0) {
                //該当データはありません。
                $result["data"] = 'W0024';
                throw new \Exception($result["data"]);
            }
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = IOFactory::createReader('Xlsx');
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();

            for ($i = 0; $i < count((array) $dt['data']); $i++) {
                $date = date_create($dt['data'][$i]['KEIRI_DT']);
                //日付
                $objActSheet->setCellValue('A' . ($i + 2), date_format($date, "Y/n/j"));
                //借方部門コード
                $objActSheet->setCellValueExplicit('B' . ($i + 2), $dt['data'][$i]['L_BUSYO_CD'], DataType::TYPE_STRING);
                //借方部門名
                $objActSheet->setCellValue('C' . ($i + 2), $dt['data'][$i]['L_BUSYO_NM']);
                //借方勘定科目コード
                $objActSheet->setCellValueExplicit('D' . ($i + 2), $dt['data'][$i]['L_KAMOK_CD'], DataType::TYPE_STRING);
                //借方勘定科目名
                $objActSheet->setCellValue('E' . ($i + 2), $dt['data'][$i]['L_KAMOK_NAME']);
                //借方補助科目コード
                $objActSheet->setCellValueExplicit('F' . ($i + 2), $dt['data'][$i]['L_SUB_KAMOK_CD'], DataType::TYPE_STRING);
                //借方補助科目名
                $objActSheet->setCellValue('G' . ($i + 2), $dt['data'][$i]['L_SUB_KAMUK_NAME']);
                //借方消費税区分名
                $objActSheet->setCellValue('H' . ($i + 2), $dt['data'][$i]['L_TAX_KBN_NAME']);
                //借方消費税率
                $objActSheet->setCellValue('I' . ($i + 2), $dt['data'][$i]['L_MEISYOU']);
                //借方取引先コード
                $objActSheet->setCellValueExplicit('J' . ($i + 2), $dt['data'][$i]['TORIHIKISAKI_CD'], DataType::TYPE_STRING);
                //借方取引先名
                $objActSheet->setCellValue('K' . ($i + 2), $dt['data'][$i]['TORIHIKISAKI_NAME']);
                //借方本体金額
                $objActSheet->setCellValue('L' . ($i + 2), $dt['data'][$i]['L_ZEIKM_GK']);
                //貸方部門コード
                $objActSheet->setCellValueExplicit('M' . ($i + 2), $dt['data'][$i]['R_BUSYO_CD'], DataType::TYPE_STRING);
                //貸方部門名
                $objActSheet->setCellValue('N' . ($i + 2), $dt['data'][$i]['R_BUSYO_NM']);
                //貸方勘定科目コード
                $objActSheet->setCellValueExplicit('O' . ($i + 2), $dt['data'][$i]['R_KAMOK_CD'], DataType::TYPE_STRING);
                //貸方勘定科目名
                $objActSheet->setCellValue('P' . ($i + 2), $dt['data'][$i]['R_KAMOK_NAME']);
                //貸方補助科目コード
                $objActSheet->setCellValueExplicit('Q' . ($i + 2), $dt['data'][$i]['R_SUB_KAMOK_CD'], DataType::TYPE_STRING);
                //貸方補助科目名
                $objActSheet->setCellValue('R' . ($i + 2), $dt['data'][$i]['R_SUB_KAMOK_NAME']);
                //貸方消費税区分名
                $objActSheet->setCellValue('S' . ($i + 2), $dt['data'][$i]['R_TAX_KBN_NAME']);
                //貸方消費税率
                $objActSheet->setCellValue('T' . ($i + 2), $dt['data'][$i]['R_MEISYOU']);
                //貸方本体金額
                $objActSheet->setCellValue('U' . ($i + 2), $dt['data'][$i]['R_ZEIKM_GK']);
                //摘要
                $objActSheet->setCellValue('V' . ($i + 2), $dt['data'][$i]['TEKYO']);
            }

            $filename = "";
            $date = date_format(date_create($sessionArray['HDKOut4OBC_sysDate']), "YmdHis");
            if ($sessionArray['HDKOut4OBC_XLSXType'] == "0") {
                $filename = "外部仕訳データ_" . $date . ".xlsx";
            } else {
                $filename = "外部支払データ_" . $date . ".xlsx";
            }
            //ブック作成
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
            $objWriter->save($tmpPath . $filename);
            $objPHPExcel->disconnectWorksheets();
            unset($objReader, $objPHPExcel);
            $res['result'] = TRUE;
            $res['data']['url'] = "files/HDKAIKEI/" . $filename;

        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    public function fncGetMaster()
    {
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            //科目
            $KamokuMst = $this->ClsComFncHDKAIKEI->FncGetKamokuMstValue();
            if (!$KamokuMst['result']) {
                throw new \Exception($KamokuMst['error']);
            }
            $res['data']['kamoku'] = $KamokuMst['data'];
            //部署
            $BusyoMst = $this->ClsComFncHDKAIKEI->FncGetCreatBusyoMstValue();
            if (!$BusyoMst['result']) {
                throw new \Exception($BusyoMst['data']);
            }
            $res['data']['busyo'] = $BusyoMst['data'];
            //作成担当者
            $SyainMst = $this->ClsComFncHDKAIKEI->FncGetSyainMstValue();
            if (!$SyainMst['result']) {
                throw new \Exception($SyainMst['data']);
            }
            $res['data']['tanntousya'] = $SyainMst['data'];
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    public function inputCheck($postData)
    {
        $result = array(
            'result' => true,
            'html' => '',
            'data' => ''
        );
        try {
            if (!$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['lvTxtGroupName'])) {
                $result['html'] = 'lvTxtGroupName';
                throw new \Exception('出力グループ名');
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }
}
