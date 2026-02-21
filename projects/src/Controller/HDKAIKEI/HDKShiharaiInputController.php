<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20240322           本番障害.xlsx NO8            科目名、補助科目名を両方表示してほしい              YIN
 * 20240408           本番保守.xlsx NO11           貸方科目ブルダウンに 「未払金給与（社員立替）」を追加  LQS
 * 20240507           99.提供資料\FromJP\20240507         20240423_金融機関マスタ追加対応.xlsx		  LQS
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKShiharaiInput;
//20240507 LQS INS S
use App\Model\HDKAIKEI\HDKBankSearch;

//20240507 LQS INS E
//*******************************************
// * sample controller
//*******************************************
class HDKShiharaiInputController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
        $this->loadComponent('CustomHDKExportPDF');
    }
    private $HDKShiharaiInput;

    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HDKShiharaiInput_layout');
    }

    public function fncSearchSpread()
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );

        try {
            if (isset($_POST['request'])) {
                $lblSyohy_no = $_POST['request']['lblSyohy_no'];
                //一覧に表示する
                $this->HDKShiharaiInput = new HDKShiharaiInput();
                $result = $this->HDKShiharaiInput->fncSelShiharaForIchiran(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                // 20240322 YIN UPD S
                // $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount);
                // 20240322 YIN UPD E

                $this->fncReturn($tmpJqgrid);
            } else {
                $result['result'] = TRUE;
                $result['data'] = '';

                $this->fncReturn($result);
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['data'] = $e->getMessage();

            $this->fncReturn($result);
        }
    }

    public function pageLoad()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $this->Session = $this->request->getSession();
            $BusyoCD = $this->Session->read('BusyoCD');
            if (isset($BusyoCD) == FALSE) {
                $result['data']['msg'] = 'W9999';
                throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
            }

            $postData = $_POST['data'];
            //貸方科目コードの値を取得
            $RKamokuRes = $this->HDKShiharaiInput->fncSelRkamokuForDdl();
            if (!$RKamokuRes['result']) {
                throw new \Exception($RKamokuRes['data']);
            }
            $result['data']['KamokuTbl'] = $RKamokuRes['data'];

            //貸方項目コードの値を取得
            $RKomokuRes = $this->HDKShiharaiInput->fncSelRkomokuForDdl();
            if (!$RKomokuRes['result']) {
                throw new \Exception($RKomokuRes['data']);
            }
            $result['data']['KomokuTbl'] = $RKomokuRes['data'];

            //消費税区分の値を取得
            $SyohizeiKBNRes = $this->HDKShiharaiInput->fncSelMeisyoKBNForDdl();
            if (!$SyohizeiKBNRes['result']) {
                throw new \Exception($SyohizeiKBNRes['data']);
            }
            $result['data']['MeisyouTbl'] = $SyohizeiKBNRes['data'];

            //消費税率の値を取得
            $SyohizeirituRes = $this->HDKShiharaiInput->fncSelMeisyorituForDdl("DS");
            if (!$SyohizeirituRes['result']) {
                throw new \Exception($SyohizeirituRes['data']);
            }
            $result['data']['syohizeiritu'] = $SyohizeirituRes['data'];

            //パターンの値を取得
            $PatternDDLRes = $this->HDKShiharaiInput->fncSelPattern($this->Session->read('BusyoCD'));
            if (!$PatternDDLRes['result']) {
                throw new \Exception($PatternDDLRes['data']);
            }
            $result['data']['PatternTbl'] = $PatternDDLRes['data'];

            //部署 master
            $BusyoRes = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue();
            if (!$BusyoRes['result']) {
                throw new \Exception($BusyoRes['data']);
            }
            $result['data']['Busyo'] = $BusyoRes['data'];

            //科目名取得(L)
            $objDs = $this->ClsComFncHDKAIKEI->FncGetKamokuMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['error']);
            }
            $result['data']['KamokuMst'] = $objDs['data'];

            // 取引先
            $res = $this->ClsComFncHDKAIKEI->FncGetTorihikisakiMstValue("", TRUE);
            if (!$res['result']) {
                throw new \Exception($res['error']);
            }
            $result['data']['Torihiki'] = $res['intRtnCD'] == 1 ? $res['Torihiki'] : array();

            // 20240408 LQS INS S
            // 社員
            $GetSyainMstValue = $this->ClsComFncHDKAIKEI->FncGetSyainMstValue();

            if (!$GetSyainMstValue['result']) {
                throw new \Exception($GetSyainMstValue['data']);
            }
            $result['data']['Syain'] = $GetSyainMstValue['data'];
            // 20240408 LQS INS E

            // 20240507 LQS INS S
            // 金融機関
            $bankModel = new HDKBankSearch();
            $GetBankMstValue = $bankModel->btnHyouji_Click();

            if (!$GetBankMstValue['result']) {
                throw new \Exception($GetBankMstValue['data']);
            }
            $result['data']['Bank'] = $GetBankMstValue['data'];
            // 20240507 LQS INS E

            //today
            $sysDate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Y/m/d");
            $result['data']['Today'] = $sysDate;

            //getMemo
            if (isset($postData['getMemo']) && $postData['getMemo'] == "1") {
                $memoRes = $this->HDKShiharaiInput->fncMemoSelSQL();
                if (!$memoRes['result']) {
                    throw new \Exception($memoRes['data']);
                }
                $result['data']['MemoTbl'] = $memoRes['data'];
            }
            $strMode = isset($postData['strMode']) ? $postData['strMode'] : '';
            //伝票検索画面又はＣＳＶ再出力画面から開かれた場合
            if (
                isset($postData['strDispNO']) &&
                ($postData['strDispNO'] == "100" || $postData['strDispNO'] == "ReOut4OBC" || $postData['strDispNO'] == "ReOut4ZenGin")
            ) {
                if ($strMode == "1") {
                    $strKamokuCD = 0;
                    if (count($result['data']['KamokuTbl']) > 2) {
                        $strKamokuCD = $result['data']['KamokuTbl'][2]['SUCHI1'];
                    }
                    for ($i = 0; $i < count($result['data']['KomokuTbl']); $i++) {
                        $one = $result['data']['KomokuTbl'][$i];
                        if ($one['SUCHI1'] == substr(str_pad($strKamokuCD, 6), 1) && $one['MEISYOUCD'] == substr(str_pad($strKamokuCD, 6), 0, 1)) {
                            break;
                        }
                    }
                }
                //修正・削除の場合
                elseif ($strMode == "2") {
                    //証憑№のチェックを行う
                    $objds = $this->HDKShiharaiInput->fncNewSyohyNOSel($postData['strSyohy_NO']);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    $result["data"]['NewNoTbl'] = $objds['data'];

                    if ((count($result["data"]['NewNoTbl']) > 0 && $result["data"]['NewNoTbl'][0]['EDA_NO'] == $postData['strEda_No']) || count($result["data"]['NewNoTbl']) == 0) {
                        //該当枝№チェック
                        $objds = $this->HDKShiharaiInput->fncFlgCheckSQL($postData['strSyohy_NO'], $postData['strEda_No']);
                        if (!$objds['result']) {
                            throw new \Exception($objds['data']);
                        }
                        if ($objds['row'] > 0) {
                            $result["data"]['EdaNoChkTbl'] = $objds['data'];
                            //修正前データを取得する
                            $objds = $this->HDKShiharaiInput->fncSyuuseiMaeSyohyoSel($postData['strSyohy_NO'], $postData['strEda_No']);
                            if (!$objds['result']) {
                                throw new \Exception($objds['data']);
                            }
                            $result["data"]['SyuseiMaeTbl'] = $objds['data'];
                        } else {
                            $result["data"]['EdaNoChkTbl'] = array();
                        }
                        //伝票検索画面からの遷移の場合、モードの設定を行う
                        if ($postData['strDispNO'] == "100") {
                            //表示モードを指定する
                            $objds = $this->HDKShiharaiInput->fncDispModeSansyoChk($postData['strSyohy_NO']);
                            if (!$objds['result']) {
                                throw new \Exception($objds['data']);
                            }
                            $result["data"]['DispModeTbl'] = $objds['data'];
                        }

                    }

                }
            } elseif ($postData['strDispNO'] == "103") {
                //編集の場合
                if ($strMode == "2") {
                    //選択したパターンデータを取得する
                    $objDs = $this->HDKShiharaiInput->fncSelPatternData($postData['strPattern_NO']);
                    if (!$objDs['result']) {
                        throw new \Exception($objDs['data']);
                    }
                    $result["data"]['PatternTbl'] = $objDs['data'];
                }
            } else {
                $strKamokuCD = $result['data']['KamokuTbl'][2]['SUCHI1'];
                for ($i = 0; $i < count($result['data']['KomokuTbl']); $i++) {
                    $one = $result['data']['KomokuTbl'][$i];
                    if ($one['SUCHI1'] == substr(str_pad($strKamokuCD, 6), 1) && $one['MEISYOUCD'] == substr(str_pad($strKamokuCD, 6), 0, 1)) {
                        break;
                    }
                }
            }

            $result["result"] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //データの取得
    public function fncSelShiwakeData()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $SYOHY_NO = $_POST['data']['SYOHY_NO'];
            $EDA_NO = $_POST['data']['EDA_NO'];
            $GYO_NO = $_POST['data']['GYO_NO'];

            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $objDs = $this->HDKShiharaiInput->fncSelShiwakeData($SYOHY_NO, $EDA_NO, $GYO_NO);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['NewNoTbl'] = $objDs['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnCopySyohyClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $postData = $_POST['data'];
            //データの取得
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $res = $this->HDKShiharaiInput->fncSelShiharaForIchiran(substr($postData['txtCopySyohyNo'], 0, 15), substr($postData['txtCopySyohyNo'], 15, 2));
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            $result['data']['DataTbl'] = $res['data'];
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnSaishinDispClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $postData = $_POST['data'];
            $objDs = $this->HDKShiharaiInput->fncNewSyohyNOSel(substr($postData['lblSyohy_no'], 0, 15));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result["data"]['NEWTBL'] = $objDs['data'];

            if (count($result["data"]['NEWTBL']) > 0) {
                $lblSyohy_no = substr($postData['lblSyohy_no'], 0, 15) . $result["data"]['NEWTBL'][0]['EDA_NO'];
                //修正前データを取得する
                $objDs = $this->HDKShiharaiInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result["data"]['SyuseiMaeTbl'] = $objDs['data'];
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnSyuseiMaeDispClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $postData = $_POST['data'];

            $objDs = $this->HDKShiharaiInput->fncSyuuseiMaeSyohyoSel(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['SYUSEIMAETBL'] = $objDs['data'];

            if ($objDs['data'][0]['SYOHY_NO'] != "") {
                $lblSyohy_no = $this->ClsComFncHDKAIKEI->FncNv($result['data']['SYUSEIMAETBL'][0]['SYOHY_NO']) . $this->ClsComFncHDKAIKEI->FncNv($result['data']['SYUSEIMAETBL'][0]['EDA_NO']);

                //修正前データを取得する
                $objDs = $this->HDKShiharaiInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result["data"]['SyuseiMaeTbl'] = $objDs['data'];
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdEventClick1()
    {
        $result = array(
            'result' => FALSE,
            'html' => '',
            'data' => [],
            'error' => ''
        );
        $this->HDKShiharaiInput = new HDKShiharaiInput();
        $blnTran = FALSE;
        try {
            $postData = $_POST['data'];
            $resCheck = $this->inputCheck($postData);
            if (!$resCheck['result']) {
                $result['data'] = $resCheck['data'];
                $result['html'] = $resCheck['html'];
                throw new \Exception('W0034');
            }
            $strSEQNO = '';
            $strGyoNo = '';
            //トランザクション開始
            $this->HDKShiharaiInput->Do_transaction();
            $blnTran = TRUE;
            //システム日付を取得する
            $dtSysdate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Y/m/d H:i:s");
            if ($postData['lblSyohy_no'] == "") {
                $strSysdate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Ym");
                $this->Session = $this->request->getSession();
                $res = $this->fncSaiban("2", $this->Session->read('BusyoCD'), $strSysdate, "ShiharaiInput");
                if (!$res['result']) {
                    throw new \Exception($res['error']);
                }
                $strSEQNO = $res['data']['fncSaiban'];
                $strSEQNO = $strSEQNO . '00';
                if ($postData['copySyohyNo'] == '') {
                    $strGyoNo = "'1'";
                    //登録処理を行う
                    $res = $this->HDKShiharaiInput->fncShiwakeDataIns(substr($strSEQNO, 0, 15), substr($strSEQNO, 15, 2), $strGyoNo, $dtSysdate, $postData['HONBUFLG'], $postData);
                    if (!$res['result']) {
                        throw new \Exception($res['data']);
                    }
                } else {
                    $res = $this->HDKShiharaiInput->fncMaeShiwakeCopy(substr($postData['copySyohyNo'], 0, 15), '00', substr($postData['copySyohyNo'], 15, 2), $dtSysdate, $postData['HONBUFLG'], substr($strSEQNO, 0, 15));
                    if (!$res['result']) {
                        throw new \Exception($res['data']);
                    }
                    //登録処理
                    switch ($postData['sender']) {
                        case "CMDEVENTINSERT":
                            //追加ボタンが押下された場合
                            //登録処理を行う
                            $strGyoNo = "(SELECT NVL(MAX(GYO_NO),0) + 1 FROM HDPSHIWAKEDATA WHERE SYOHY_NO = '@SYOHY_NO' AND EDA_NO = '@EDA_NO')";
                            $res = $this->HDKShiharaiInput->fncShiwakeDataIns(substr($strSEQNO, 0, 15), '00', $strGyoNo, $dtSysdate, $postData['HONBUFLG'], $postData);
                            if (!$res['result']) {
                                throw new \Exception($res['data']);
                            }
                            break;
                        case "CMDEVENTUPDATE":
                            //修正ボタンが押下された場合
                            //修正処理を行う
                            $strGyoNo = $postData['hidGyoNO'];
                            $res = $this->HDKShiharaiInput->fncUpdateSQL(substr($strSEQNO, 0, 15), '00', $strGyoNo, $dtSysdate, $postData['HONBUFLG'], $postData);
                            if (!$res['result']) {
                                throw new \Exception($res['data']);
                            }
                            break;
                        case "CMDEVENTDELETE":
                            //行削除ボタンが押下された場合
                            //削除処理を行う
                            $strGyoNo = $postData['hidGyoNO'];
                            $objDs = $this->HDKShiharaiInput->fncGyoDelete(substr($strSEQNO, 0, 15), '00', $strGyoNo, $dtSysdate);
                            if (!$objDs['result']) {
                                throw new \Exception($objDs['data']);
                            }
                            //削除した行が最後の1件だった場合は、履歴の削除フラグを更新する
                            $objDs = $this->HDKShiharaiInput->fncLastDelAllUpd(substr($strSEQNO, 0, 15), '00', $dtSysdate, $postData['HONBUFLG']);
                            if (!$objDs['result']) {
                                throw new \Exception($objDs['data']);
                            }
                            // ファイルテーブルを更新する
                            $objDs = $this->HDKShiharaiInput->fileDelete(substr($strSEQNO, 0, 15), '00', $strGyoNo, $dtSysdate);
                            if (!$objDs['result']) {
                                throw new \Exception($objDs['data']);
                            }

                            break;
                        default:
                            break;
                    }
                }
            }

            //コミット
            $this->HDKShiharaiInput->Do_commit();
            $blnTran = False;
            $result['data']['strSEQNO'] = $strSEQNO;
            $result['data']['dtSysdate'] = $dtSysdate;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($blnTran) {
                $this->HDKShiharaiInput->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdEventClick2()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $this->HDKShiharaiInput = new HDKShiharaiInput();
        try {
            $postData = $_POST['data'];
            //チェック用ＳＱＬを取得する
            $objDs = $this->HDKShiharaiInput->fncFlgCheckSQL(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $CheckTbl = $objDs['data'];

            $objDs = $this->HDKShiharaiInput->fncNewSyohyNOSel(substr($postData['lblSyohy_no'], 0, 15));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $NewNoTbl = $objDs['data'];

            $result['data']['CheckTbl'] = $CheckTbl;
            $result['data']['NewNoTbl'] = $NewNoTbl;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdEventClick3()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $this->HDKShiharaiInput = new HDKShiharaiInput();
        $blnTran = FALSE;
        try {
            $postData = $_POST['data'];
            $resCheck = $this->inputCheck($postData);
            if (!$resCheck['result']) {
                $result['data'] = $resCheck['data'];
                $result['html'] = $resCheck['html'];
                throw new \Exception('W0034');
            }

            $dtSysdate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Y/m/d H:i:s");
            $intEdaNo = isset($postData['intEdaNo']) ? $postData['intEdaNo'] : "";

            //トランザクション開始
            $this->HDKShiharaiInput->Do_transaction();
            $blnTran = TRUE;
            //'印刷済みの証憑の場合
            if ($postData['FLG'] == "1") {
                $res = $this->HDKShiharaiInput->fncMaeShiwakeCopy(substr($postData['lblSyohy_no'], 0, 15), $intEdaNo, substr($postData['lblSyohy_no'], 15, 2), $dtSysdate, $postData['PatternIDFLG'], '');
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                if (strlen($intEdaNo) < 2) {
                    $length = 2 - strlen($intEdaNo);
                    for ($i = 0; $i < $length; $i++) {
                        $intEdaNo = "0" . $intEdaNo;
                    }
                } else {
                    $intEdaNo = substr($intEdaNo, 0, 2);
                }

                $strGyoNo = '';

                //登録処理
                switch ($postData['sender']) {
                    case "CMDEVENTINSERT":
                        //追加ボタンが押下された場合
                        //登録処理を行う
                        $strGyoNo = "(SELECT NVL(MAX(GYO_NO),0) + 1 FROM HDPSHIWAKEDATA WHERE SYOHY_NO = '@SYOHY_NO' AND EDA_NO = '@EDA_NO')";
                        $res = $this->HDKShiharaiInput->fncShiwakeDataIns(substr($postData['lblSyohy_no'], 0, 15), $intEdaNo, $strGyoNo, $dtSysdate, $postData['PatternIDFLG'], $postData);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    case "CMDEVENTUPDATE":
                        //修正ボタンが押下された場合
                        //修正処理を行う
                        $strGyoNo = $postData['hidGyoNO'];
                        $res = $this->HDKShiharaiInput->fncUpdateSQL(substr($postData['lblSyohy_no'], 0, 15), $intEdaNo, $strGyoNo, $dtSysdate, $postData['PatternIDFLG'], $postData);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    case "CMDEVENTDELETE":
                        //行削除ボタンが押下された場合
                        //削除処理を行う
                        $strGyoNo = $_POST['data']['hidGyoNO'];
                        $objDs = $this->HDKShiharaiInput->fncGyoDelete(substr($postData['lblSyohy_no'], 0, 15), $intEdaNo, $strGyoNo, $dtSysdate);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        //削除した行が最後の1件だった場合は、履歴の削除フラグを更新する
                        $objDs = $this->HDKShiharaiInput->fncLastDelAllUpd(substr($postData['lblSyohy_no'], 0, 15), $intEdaNo, $dtSysdate, $postData['PatternIDFLG']);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        // ファイルテーブルを更新する
                        $objDs = $this->HDKShiharaiInput->fileDelete(substr($postData['lblSyohy_no'], 0, 15), $intEdaNo, $strGyoNo, $dtSysdate);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        break;
                    case "CMDEVENTALLDELETE":
                        //全削除ボタンが押下された場合
                        $res = $this->HDKShiharaiInput->fncAllDeleteUpd(substr($postData['lblSyohy_no'], 0, 15), $dtSysdate, $postData['PatternIDFLG']);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        // ファイルテーブルを更新する
                        $objDs = $this->HDKShiharaiInput->fileDelete(substr($postData['lblSyohy_no'], 0, 15), '', '', $dtSysdate);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        break;
                    default:
                        break;
                }
                //修正前データを取得する
                $objDs = $this->HDKShiharaiInput->fncSyuuseiMaeSyohyoSel(substr($postData['lblSyohy_no'], 0, 15), $intEdaNo);
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result['data']['SyuseiMaeTbl'] = $objDs['data'];

            } else {
                $strGyoNo = '';
                //登録処理
                switch ($postData['sender']) {
                    case "CMDEVENTINSERT":
                        //追加ボタンが押下された場合
                        //登録処理を行う
                        $strGyoNo = "(SELECT NVL(MAX(GYO_NO),0) + 1 FROM HDPSHIWAKEDATA WHERE SYOHY_NO = '@SYOHY_NO' AND EDA_NO = '@EDA_NO')";
                        $res = $this->HDKShiharaiInput->fncShiwakeDataIns(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2), $strGyoNo, $dtSysdate, $postData['PatternIDFLG'], $postData);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    case "CMDEVENTUPDATE":
                        //修正ボタンが押下された場合
                        //修正処理を行う
                        $strGyoNo = $postData['hidGyoNO'];
                        $res = $this->HDKShiharaiInput->fncUpdateSQL(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2), $strGyoNo, $dtSysdate, $postData['PatternIDFLG'], $postData);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    case "CMDEVENTDELETE":
                        //行削除ボタンが押下された場合
                        //削除処理を行う
                        $strGyoNo = $postData['hidGyoNO'];
                        $objDs = $this->HDKShiharaiInput->fncGyoDelete(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2), $strGyoNo, $dtSysdate);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        //削除した行が最後の1件だった場合は、履歴の削除フラグを更新する
                        $objDs = $this->HDKShiharaiInput->fncLastDelAllUpd(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2), $dtSysdate, $postData['PatternIDFLG']);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        // ファイルテーブルを更新する
                        $objDs = $this->HDKShiharaiInput->fileDelete(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2), $strGyoNo, $dtSysdate);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        break;
                    case "CMDEVENTALLDELETE":
                        //全削除ボタンが押下された場合
                        //削除処理を行う
                        $res = $this->HDKShiharaiInput->fncAllDelete(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2));
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        //削除した行が最後の1件だった場合は、履歴の削除フラグを更新する
                        $res = $this->HDKShiharaiInput->fncAllDeleteUpd(substr($postData['lblSyohy_no'], 0, 15), $dtSysdate, $postData['PatternIDFLG']);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        // ファイルテーブルを更新する
                        $objDs = $this->HDKShiharaiInput->fileDelete(substr($postData['lblSyohy_no'], 0, 15), '', '', $dtSysdate);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        break;
                    default:
                        break;
                }
                if ($postData['sender'] == "CMDEVENTDELETE" || $postData['sender'] == "CMDEVENTALLDELETE") {
                    $objDs = $this->HDKShiharaiInput->fncFlgCheckSQL(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2));
                    if (!$objDs['result']) {
                        throw new \Exception($objDs['data']);
                    }
                    $result['data']['DispModeTbl'] = $objDs['data'];
                }
            }

            //コミット
            $this->HDKShiharaiInput->Do_commit();
            $blnTran = FALSE;
            $result['data']['dtSysdate'] = $dtSysdate;
            $result['data']['intEdaNo'] = $intEdaNo;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($blnTran) {
                $this->HDKShiharaiInput->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：採番する
    //'関 数 名：fncUpdSaiban
    //'引    数：blnUpdate    (I)True：採番テーブルを更新する False：更新しない
    //'戻 り 値：True：正常終了　False：異常終了
    //'処理説明：採番する
    //'**********************************************************************
    public function fncSaiban($strKbn, $strBusyoCD, $strNengetu, $strProID, $blnUpdate = true)
    {
        //採番ﾃｰﾌﾞﾙから採番する
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $fncSaiban = "99999999999";

            //採番ﾃｰﾌﾞﾙから採番する
            $objDr = $this->HDKShiharaiInput->fncSaiban($strKbn, $strBusyoCD, $strNengetu);
            if (!$objDr['result']) {
                throw new \Exception($objDr['data']);
            }

            //証憑№を戻す
            if ($objDr['row'] > 0) {
                $fncSaiban = $strKbn . $strBusyoCD . $strNengetu . str_pad($this->ClsComFncHDKAIKEI->FncNv($objDr['data'][0]['BANGO']), 5, '0', STR_PAD_LEFT);
            } else {
                $fncSaiban = $strKbn . $strBusyoCD . $strNengetu . "00001";
            }

            if ($blnUpdate) {
                //採番ﾃｰﾌﾞﾙに既に同一年月のものがあればUPDATE、なければINSERT
                $objDr = $this->HDKShiharaiInput->fncSaiban2($strKbn, $strBusyoCD, $strNengetu, $strProID, $objDr);
                if (!$objDr['result']) {
                    throw new \Exception($objDr['data']);
                }
            }
            $result['data']['fncSaiban'] = $fncSaiban;
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    public function txtLkamokuCDKoumokuSet()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $postData = $_POST['data'];

            //名称取得
            $kamokuRes = $this->HDKShiharaiInput->FncGetLKamokuMst($postData['strCode'], $postData['strKomoku']);
            if (!$kamokuRes['result']) {
                throw new \Exception($kamokuRes['data']);
            }
            $result['data']['LKamoku'] = $kamokuRes['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function txtLkoumkCDKoumokuSet()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $postData = $_POST['data'];

            //名称取得
            $kamokuRes = $this->HDKShiharaiInput->FncGetLKamokuMst($postData['strCode'], $postData['strKomoku']);
            if (!$kamokuRes['result']) {
                throw new \Exception($kamokuRes['data']);
            }
            $result['data']['LKamoku'] = $kamokuRes['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnPtnDeleteClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $postData = $_POST['data'];
            $res = $this->HDKShiharaiInput->fncPatternDelete($postData['hidPatternNO']);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnAddClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $postData = $_POST['data'];

            $kamokuRes = $this->HDKShiharaiInput->FncGetLKamokuMst($postData['txtLKamokuCD'], $postData['txtLKomokuCD']);
            if (!$kamokuRes['result']) {
                throw new \Exception($kamokuRes['error']);
            }
            $result['data']['lblLKamokuNM'] = !isset($kamokuRes['data'][0]) || $kamokuRes['data'][0]['KAMOK_NAME'] == null ? '' : $kamokuRes['data'][0]['KAMOK_NAME'];
            $result['data']['lblLKomokuNM'] = !isset($kamokuRes['data'][0]) || $kamokuRes['data'][0]['SUB_KAMOK_NAME'] == null ? '' : $kamokuRes['data'][0]['SUB_KAMOK_NAME'];

            //名称取得
            $txtLBusyoCD = $postData['txtLBusyoCD'];
            $txtRBusyoCD = $postData['txtRBusyoCD'];
            $objDs = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }

            $result['data']['strSyozokuTenpo'] = '';
            for ($i = 0; $i < count($objDs['data']); $i++) {
                $one = $objDs['data'][$i];
                $this->Session = $this->request->getSession();
                if ($one['BUSYO_CD'] == $this->Session->read('BusyoCD')) {
                    $result['data']['strSyozokuTenpo'] = $one['BUSYO_NM'];
                    break;
                }
            }
            $objDs = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['strKariTenpo'] = '';
            for ($i = 0; $i < count($objDs['data']); $i++) {
                $one = $objDs['data'][$i];
                if ($one['BUSYO_CD'] == $txtLBusyoCD) {
                    $result['data']['strKariTenpo'] = $one['BUSYO_NM'];
                    break;
                }
            }
            $objDs = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue(true, $txtRBusyoCD);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['strKashiTenpo'] = '';
            for ($i = 0; $i < count($objDs['data']); $i++) {
                $one = $objDs['data'][$i];
                if ($one['BUSYO_CD'] == $txtRBusyoCD) {
                    $result['data']['strKashiTenpo'] = $one['BUSYO_NM'];
                    break;
                }
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function ddlPatternSelSelectedIndexChanged()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $postData = $_POST['data'];
            $objDs = $this->HDKShiharaiInput->fncSelectPattern($postData['ddlPatternSel']);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['PATTERNTBL'] = $objDs['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdEventPatternTrkClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HDKShiharaiInput = new HDKShiharaiInput();
            $postData = $_POST['data'];
            $resCheck = $this->inputCheck($postData);
            if (!$resCheck['result']) {
                $result['data'] = $resCheck['data'];
                $result['html'] = $resCheck['html'];
                throw new \Exception('W0034');
            }

            if ($postData['hidPatternNO'] == "") {
                $res = $this->HDKShiharaiInput->fncPatternTrkDispShiwake($postData);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
            } else {
                $res = $this->HDKShiharaiInput->fncUpdPatternTrk("2", $postData['hidPatternNO'], $postData);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
            }

            //パターンの値を取得
            $this->Session = $this->request->getSession();
            $PatternDDLRes = $this->HDKShiharaiInput->fncSelPattern($this->Session->read('BusyoCD'));
            if (!$PatternDDLRes['result']) {
                throw new \Exception($PatternDDLRes['data']);
            }
            $result['data']['PatternTbl'] = $PatternDDLRes['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdPrintClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $this->HDKShiharaiInput = new HDKShiharaiInput();
        try {
            $postData = $_POST['data'];

            //削除されていないかチェックします。
            $objds = $this->HDKShiharaiInput->fncFlgCheckSQL(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2));
            if (!$objds['result']) {
                throw new \Exception($objds['data']);
            }
            $result["data"]['DispModeTbl'] = $objds['data'];

            if (count($result["data"]['DispModeTbl']) > 0) {
                $objds = $this->HDKShiharaiInput->fncNewSyohyNOSel(substr($postData['lblSyohy_no'], 0, 15));
                if (!$objds['result']) {
                    throw new \Exception($objds['data']);
                }
                $result["data"]['NewNoTbl'] = $objds['data'];
                $result["data"]['changed'] = 1;

                //証憑№のチェックを行う
                if ($result["data"]['NewNoTbl'][0]['EDA_NO'] == substr($postData['lblSyohy_no'], 15, 2)) {
                    $result["data"]['changed'] = 0;
                    //印刷処理を行う
                    //トランザクション開始
                    $this->HDKShiharaiInput->Do_transaction();

                    //ワーク証憑№のデータを全件削除する
                    $objDs = $this->HDKShiharaiInput->fncAllDelSQL();
                    if (!$objDs['result']) {
                        throw new \Exception($objDs['data']);
                    }

                    // ワーク証憑№に対象の証憑№をＩＮＳＥＲＴする
                    $objDs = $this->HDKShiharaiInput->fncInsTaisyoSyohyNOPrint($postData['lblSyohy_no']);
                    if (!$objDs['result']) {
                        throw new \Exception($objDs['data']);
                    }
                    $result["data"]['intTaisyo'] = $objDs['number_of_rows'];

                    //コミット
                    $this->HDKShiharaiInput->Do_commit();

                    $arr['CONST_ADMIN_PTN_NO'] = $postData['CONST_ADMIN_PTN_NO'];
                    $arr['CONST_HONBU_PTN_NO'] = $postData['CONST_HONBU_PTN_NO'];
                    $this->Session = $this->request->getSession();
                    $arr['BusyoCD'] = $this->Session->read('BusyoCD');
                    $printPDF = $this->CustomHDKExportPDF->FncDenpyoinsatuPrint("102", $arr);
                    if (!$printPDF['result']) {
                        throw new \Exception($printPDF['error']);
                    }
                    $result['data']['report'] = $printPDF['report'];
                }
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function inputCheck($postData)
    {
        $result = array(
            'result' => true,
            'html' => '',
            'data' => ''
        );
        try {
            if (isset($postData['txtTekyo']) && !$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['txtTekyo'])) {
                $result['html'] = 'txtTekyo';
                throw new \Exception('摘要');
            }
            if (isset($postData['txtPatternNM']) && !$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['txtPatternNM'])) {
                $result['html'] = 'txtPatternNM';
                throw new \Exception('パターン名');
            }
            if (isset($postData['txtSonotaGinko']) && !$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['txtSonotaGinko'])) {
                $result['html'] = 'txtSonotaGinko';
                throw new \Exception('振込先銀行');
            }
            if (isset($postData['txtSonotaShiten']) && !$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['txtSonotaShiten'])) {
                $result['html'] = 'txtSonotaShiten';
                throw new \Exception('支店');
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

}
