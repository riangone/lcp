<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20240418           svn-ver.38694			            	VBソース変更              				lqs
 * 20240426			[確定登録ボタンを押下したら口座NO、必須摘要がクリアされてしまいます]修正				lqs
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMDPS;

use App\Controller\AppController;
use App\Model\HMDPS\HMDPS102ShiharaiDenpyoInput;
//*******************************************
// * sample controller
//*******************************************
class HMDPS102ShiharaiDenpyoInputController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    // public $components = array(
    //     'RequestHandler',
    //     'ClsComFncHMDPS',
    //     'CustomExportPDF'
    // );
    public $HMDPS102ShiharaiDenpyoInput;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMDPS');
        $this->loadComponent('CustomExportPDF');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMDPS102ShiharaiDenpyoInput_layout');
    }

    public function pageLoad()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();

            $this->Session = $this->request->getSession();
            $BusyoCD = $this->Session->read('BusyoCD');
            if (isset($BusyoCD) == FALSE) {
                $result['data']['msg'] = 'W9999';
                throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
            }

            $postData = $_POST['data'];
            //貸方科目コードの値を取得
            $RKamokuRes = $this->HMDPS102ShiharaiDenpyoInput->fncSelRkamokuForDdl();
            if (!$RKamokuRes['result']) {
                throw new \Exception($RKamokuRes['data']);
            }
            $result['data']['KamokuTbl'] = $RKamokuRes['data'];

            //貸方項目コードの値を取得
            $RKomokuRes = $this->HMDPS102ShiharaiDenpyoInput->fncSelRkomokuForDdl();
            if (!$RKomokuRes['result']) {
                throw new \Exception($RKomokuRes['data']);
            }
            $result['data']['KomokuTbl'] = $RKomokuRes['data'];

            //消費税区分の値を取得
            $SyohizeiRes = $this->HMDPS102ShiharaiDenpyoInput->fncSelMeisyoForDdl("DS");
            if (!$SyohizeiRes['result']) {
                throw new \Exception($SyohizeiRes['data']);
            }
            $result['data']['MeisyouTbl'] = $SyohizeiRes['data'];

            //取引先区分の値を取得
            $TorihikiRes = $this->HMDPS102ShiharaiDenpyoInput->fncSelMeisyoForDdl("DT");
            if (!$TorihikiRes['result']) {
                throw new \Exception($TorihikiRes['data']);
            }
            $result['data']['TorihikiTbl'] = $TorihikiRes['data'];

            //パターンの値を取得
            $PatternDDLRes = $this->HMDPS102ShiharaiDenpyoInput->fncSelPattern($this->Session->read('BusyoCD'));
            if (!$PatternDDLRes['result']) {
                throw new \Exception($PatternDDLRes['data']);
            }
            $result['data']['PatternTbl'] = $PatternDDLRes['data'];

            // 20240418 lqs INS S
            //相手先区分にセット
            $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSelMeisyoForDdl("DA");
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['AitesakiKBN'] = $objDs['data'];
            //特例区分の値を取得
            $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSelMeisyoForDdl("DR");
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['TokureiKBN'] = $objDs['data'];
            // 20240418 lqs INS E

            //部署 master
            $BusyoRes = $this->ClsComFncHMDPS->FncGetBusyoMstValue();
            if (!$BusyoRes['result']) {
                throw new \Exception($BusyoRes['data']);
            }
            $result['data']['Busyo'] = $BusyoRes['data'];

            //科目名取得(L)
            $objDs = $this->ClsComFncHMDPS->FncGetKamokuMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['KamokuMstBlank'] = $objDs['data'];
            $objDs = $this->ClsComFncHMDPS->FncGetKamokuMstValue("", "1");
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['KamokuMstNotBlank'] = $objDs['data'];

            $res = $this->ClsComFncHMDPS->FncGetTorihikisakiMstValue("", TRUE);
            if (!$res['result']) {
                throw new \Exception($res['error']);
            }
            $result['data']['Torihiki'] = $res['intRtnCD'] == 1 ? $res['Torihiki'] : array();

            //today
            $sysDate = $this->ClsComFncHMDPS->FncGetSysDate("Y/m/d");
            $result['data']['Today'] = $sysDate;

            //getMemo
            if (isset($postData['getMemo']) && $postData['getMemo'] == "1") {
                $memoRes = $this->HMDPS102ShiharaiDenpyoInput->fncMemoSelSQL();
                if (!$memoRes['result']) {
                    throw new \Exception($memoRes['data']);
                }
                $result['data']['MemoTbl'] = $memoRes['data'];
            }
            $strMode = isset($postData['strMode']) ? $postData['strMode'] : '';
            //伝票検索画面又はＣＳＶ再出力画面から開かれた場合
            if (isset($postData['strDispNO']) && ($postData['strDispNO'] == "100" || $postData['strDispNO'] == "105")) {
                if ($strMode == "1") {
                    $strKamokuCD = 0;
                    if (count((array) $result['data']['KamokuTbl']) > 2) {
                        $strKamokuCD = $result['data']['KamokuTbl'][2]['SUCHI1'];
                    }
                    $ddlRKomokuCD = '';
                    for ($i = 0; $i < count((array) $result['data']['KomokuTbl']); $i++) {
                        $one = $result['data']['KomokuTbl'][$i];
                        if ($one['SUCHI1'] == substr(str_pad($strKamokuCD, 6), 1) && $one['MEISYOUCD'] == substr(str_pad($strKamokuCD, 6), 0, 1)) {
                            $ddlRKomokuCD = $one['SUCHI2'];
                            break;
                        }
                    }
                    $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($strKamokuCD, 6), 1), $ddlRKomokuCD);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    $result["data"]['RKOUBANTBL'] = $objds['data'];
                }
                //修正・削除の場合
                elseif ($strMode == "2") {
                    //証憑№のチェックを行う
                    $objds = $this->HMDPS102ShiharaiDenpyoInput->fncNewSyohyNOSel($postData['strSyohy_NO']);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    $result["data"]['NewNoTbl'] = $objds['data'];

                    if ((count((array) $result["data"]['NewNoTbl']) > 0 && $result["data"]['NewNoTbl'][0]['EDA_NO'] == $postData['strEda_No']) || count((array) $result["data"]['NewNoTbl']) == 0) {
                        //データの取得
                        $objds = $this->HMDPS102ShiharaiDenpyoInput->fncSelShiwakeData($postData['strSyohy_NO'], $postData['strEda_No'], 1);
                        if (!$objds['result']) {
                            throw new \Exception($objds['data']);
                        }
                        $result["data"]['DataTbl'] = $objds['data'];

                        $result["data"]['LKOUBANTBL'] = array();
                        $result["data"]['RKOUBANTBL'] = array();

                        if (count((array) $result["data"]['DataTbl']) > 0) {
                            //口座キー・必須摘要の名称を取得する(借方)
                            $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata($result["data"]['DataTbl'][0]['L_KAMOK_CD'], $result["data"]['DataTbl'][0]['L_KOUMK_CD']);
                            if (!$objds['result']) {
                                throw new \Exception($objds['data']);
                            }
                            $result["data"]['LKOUBANTBL'] = $objds['data'];

                            //口座キー・必須摘要の名称を取得する(貸方)
                            $rkamokuCD = substr(str_pad($this->ClsComFncHMDPS->FncNv($result["data"]['DataTbl'][0]['SHR_KAMOK_KB']), 3), 0, 1) . $result["data"]['DataTbl'][0]['R_KAMOK_CD'];
                            $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($rkamokuCD, 6), 1), $result["data"]['DataTbl'][0]['R_KOUMK_CD']);
                            if (!$objds['result']) {
                                throw new \Exception($objds['data']);
                            }
                            $result["data"]['RKOUBANTBL'] = $objds['data'];

                            //修正前データを取得する
                            $objds = $this->HMDPS102ShiharaiDenpyoInput->fncSyuuseiMaeSyohyoSel($postData['strSyohy_NO'], $postData['strEda_No']);
                            if (!$objds['result']) {
                                throw new \Exception($objds['data']);
                            }
                            $result["data"]['SyuseiMaeTbl'] = $objds['data'];

                            //該当枝№チェック
                            $objds = $this->HMDPS102ShiharaiDenpyoInput->fncFlgCheckSQL($postData['strSyohy_NO'], $postData['strEda_No']);
                            if (!$objds['result']) {
                                throw new \Exception($objds['data']);
                            }
                            $result["data"]['EdaNoChkTbl'] = $objds['data'];

                            //伝票検索画面からの遷移の場合、モードの設定を行う
                            if ($postData['strDispNO'] == "100") {
                                //表示モードを指定する
                                $objds = $this->HMDPS102ShiharaiDenpyoInput->fncDispModeSansyoChk($postData['strSyohy_NO']);
                                if (!$objds['result']) {
                                    throw new \Exception($objds['data']);
                                }
                                $result["data"]['DispModeTbl'] = $objds['data'];
                            }
                        }

                    }

                }
            } elseif ($postData['strDispNO'] == "103") {
                //編集の場合
                if ($strMode == "2") {
                    //選択したパターンデータを取得する
                    $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSelPatternData($postData['strPattern_NO']);
                    if (!$objDs['result']) {
                        throw new \Exception($objDs['data']);
                    }
                    $result["data"]['PatternTbl'] = $objDs['data'];

                    $result["data"]['LKOUBANTBL'] = array();
                    $result["data"]['RKOUBANTBL'] = array();

                    if (count((array) $result["data"]['PatternTbl']) > 0) {
                        //口座キー・必須摘要の名称を取得する(借方)
                        $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata($result["data"]['PatternTbl'][0]['L_KAMOK_CD'], $result["data"]['PatternTbl'][0]['L_KOUMK_CD']);
                        if (!$objds['result']) {
                            throw new \Exception($objds['data']);
                        }
                        $result["data"]['LKOUBANTBL'] = $objds['data'];

                        //口座キー・必須摘要の名称を取得する(貸方)
                        $rkamokuCD = substr(str_pad($this->ClsComFncHMDPS->FncNv($result["data"]['PatternTbl'][0]['SHR_KAMOK_KB']), 3), 0, 1) . $result["data"]['PatternTbl'][0]['R_KAMOK_CD'];
                        $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($rkamokuCD, 6), 1), $result["data"]['PatternTbl'][0]['R_KOUMK_CD']);
                        if (!$objds['result']) {
                            throw new \Exception($objds['data']);
                        }
                        $result["data"]['RKOUBANTBL'] = $objds['data'];
                    }
                }
            } else {
                $strKamokuCD = $result['data']['KamokuTbl'][2]['SUCHI1'];
                $ddlRKomokuCD = '';
                for ($i = 0; $i < count((array) $result['data']['KomokuTbl']); $i++) {
                    $one = $result['data']['KomokuTbl'][$i];
                    if ($one['SUCHI1'] == substr(str_pad($strKamokuCD, 6), 1) && $one['MEISYOUCD'] == substr(str_pad($strKamokuCD, 6), 0, 1)) {
                        $ddlRKomokuCD = $one['SUCHI2'];
                        break;
                    }
                }
                $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($strKamokuCD, 6), 1), $ddlRKomokuCD);
                if (!$objds['result']) {
                    throw new \Exception($objds['data']);
                }
                $result["data"]['RKOUBANTBL'] = $objds['data'];
            }

            $result["result"] = TRUE;
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
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];
            //データの取得
            $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSelShiwakeData(substr($postData['txtCopySyohyNo'], 0, 15), substr($postData['txtCopySyohyNo'], 15, 2), 1);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['DataTbl'] = $objDs['data'];

            $result["data"]['LKOUBANTBL'] = array();
            $result["data"]['RKOUBANTBL'] = array();

            if (count((array) $result["data"]['DataTbl']) > 0) {
                //口座キー・必須摘要の名称を取得する(借方)
                $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata($result["data"]['DataTbl'][0]['L_KAMOK_CD'], $result["data"]['DataTbl'][0]['L_KOUMK_CD']);
                if (!$objds['result']) {
                    throw new \Exception($objds['data']);
                }
                $result["data"]['LKOUBANTBL'] = $objds['data'];

                //口座キー・必須摘要の名称を取得する(貸方)
                $rkamokuCD = substr(str_pad($this->ClsComFncHMDPS->FncNv($result["data"]['DataTbl'][0]['SHR_KAMOK_KB']), 3), 0, 1) . $result["data"]['DataTbl'][0]['R_KAMOK_CD'];
                $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($rkamokuCD, 6), 1), $result["data"]['DataTbl'][0]['R_KOUMK_CD']);
                if (!$objds['result']) {
                    throw new \Exception($objds['data']);
                }
                $result["data"]['RKOUBANTBL'] = $objds['data'];
            }
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
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];
            $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncNewSyohyNOSel(substr($postData['lblSyohy_no'], 0, 15));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result["data"]['NEWTBL'] = $objDs['data'];

            if (count((array) $result["data"]['NEWTBL']) > 0) {
                $lblSyohy_no = substr($postData['lblSyohy_no'], 0, 15) . $result["data"]['NEWTBL'][0]['EDA_NO'];

                //登録内容を画面項目に表示する
                //データの取得
                $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSelShiwakeData(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), 1);
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result["data"]['DataTbl'] = $objDs['data'];

                $result["data"]['LKOUBANTBL'] = array();
                $result["data"]['RKOUBANTBL'] = array();

                if (count((array) $result["data"]['DataTbl']) > 0) {
                    //口座キー・必須摘要の名称を取得する(借方)
                    $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata($result["data"]['DataTbl'][0]['L_KAMOK_CD'], $result["data"]['DataTbl'][0]['L_KOUMK_CD']);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    $result["data"]['LKOUBANTBL'] = $objds['data'];

                    //口座キー・必須摘要の名称を取得する(貸方)
                    $rkamokuCD = substr(str_pad($this->ClsComFncHMDPS->FncNv($result["data"]['DataTbl'][0]['SHR_KAMOK_KB']), 3), 0, 1) . $result["data"]['DataTbl'][0]['R_KAMOK_CD'];
                    $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($rkamokuCD, 6), 1), $result["data"]['DataTbl'][0]['R_KOUMK_CD']);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    $result["data"]['RKOUBANTBL'] = $objds['data'];

                    //修正前データを取得する
                    $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                    if (!$objDs['result']) {
                        throw new \Exception($objDs['data']);
                    }
                    $result["data"]['SyuseiMaeTbl'] = $objDs['data'];
                }
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
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];

            $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSyuuseiMaeSyohyoSel(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['SYUSEIMAETBL'] = $objDs['data'];

            if ($objDs['data'][0]['SYOHY_NO'] != "") {
                $lblSyohy_no = $this->ClsComFncHMDPS->FncNv($result['data']['SYUSEIMAETBL'][0]['SYOHY_NO']) . $this->ClsComFncHMDPS->FncNv($result['data']['SYUSEIMAETBL'][0]['EDA_NO']);
                //登録内容を画面項目に表示する
                //データの取得
                $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSelShiwakeData(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), 1);
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result['data']['DataTbl'] = $objDs['data'];

                $result["data"]['LKOUBANTBL'] = array();
                $result["data"]['RKOUBANTBL'] = array();

                if (count((array) $result['data']['DataTbl']) > 0) {
                    //口座キー・必須摘要の名称を取得する(借方)
                    $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata($result["data"]['DataTbl'][0]['L_KAMOK_CD'], $result["data"]['DataTbl'][0]['L_KOUMK_CD']);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    $result["data"]['LKOUBANTBL'] = $objds['data'];

                    //口座キー・必須摘要の名称を取得する(貸方)
                    $rkamokuCD = substr(str_pad($this->ClsComFncHMDPS->FncNv($result["data"]['DataTbl'][0]['SHR_KAMOK_KB']), 3), 0, 1) . $result["data"]['DataTbl'][0]['R_KAMOK_CD'];
                    $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($rkamokuCD, 6), 1), $result["data"]['DataTbl'][0]['R_KOUMK_CD']);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    $result["data"]['RKOUBANTBL'] = $objds['data'];

                    //修正前データを取得する
                    $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                    if (!$objDs['result']) {
                        throw new \Exception($objDs['data']);
                    }
                    $result["data"]['SyuseiMaeTbl'] = $objDs['data'];
                }
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
            'data' => [],
            'error' => ''
        );
        $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
        $blnTran = FALSE;
        try {
            $this->Session = $this->request->getSession();
            $postData = $_POST['data'];
            $strSEQNO = '';
            //トランザクション開始
            $this->HMDPS102ShiharaiDenpyoInput->Do_transaction();
            $blnTran = TRUE;
            if ($postData['lblSyohy_no'] == "") {
                $strSysdate = $this->ClsComFncHMDPS->FncGetSysDate("Ym");
                $res = $this->fncSaiban("2", $this->Session->read('BusyoCD'), $strSysdate, "ShiharaiInput");
                if (!$res['result']) {
                    throw new \Exception($res['error']);
                }
                $strSEQNO = $res['data']['fncSaiban'];
                $strSEQNO = $strSEQNO . '00';
            }
            //unuseful code but in VB
            // else {
            // $strSEQNO = $postData['lblSyohy_no'];
            // }

            //システム日付を取得する
            $dtSysdate = $this->ClsComFncHMDPS->FncGetSysDate("Y/m/d H:i:s");

            //登録処理を行う

            $res = $this->HMDPS102ShiharaiDenpyoInput->fncShiwakeDataIns(substr($strSEQNO, 0, 15), substr($strSEQNO, 15, 2), "'1'", $dtSysdate, $postData['HONBUFLG'], $postData);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }

            //コミット
            $this->HMDPS102ShiharaiDenpyoInput->Do_commit();
            $blnTran = False;
            $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($postData['ddlRKamokuCD'], 6), 1), $postData['ddlRKomokuCD']);
            if (!$objds['result']) {
                throw new \Exception($objds['data']);
            }
            $result["data"]['RKOUBANTBL'] = $objds['data'];
            $result['data']['strSEQNO'] = $strSEQNO;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($blnTran) {
                $this->HMDPS102ShiharaiDenpyoInput->Do_rollback();
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
        $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
        try {
            $postData = $_POST['data'];
            //チェック用ＳＱＬを取得する
            $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncFlgCheckSQL(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $CheckTbl = $objDs['data'];

            $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncNewSyohyNOSel(substr($postData['lblSyohy_no'], 0, 15));
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
        $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
        $blnTran = FALSE;
        try {
            $postData = $_POST['data'];

            $dtSysdate = $this->ClsComFncHMDPS->FncGetSysDate("Y/m/d H:i:s");

            //トランザクション開始
            $this->HMDPS102ShiharaiDenpyoInput->Do_transaction();
            $blnTran = TRUE;
            //'印刷済みの証憑の場合
            if ($postData['FLG'] == "1") {
                $res = $this->HMDPS102ShiharaiDenpyoInput->fncMaeShiwakeCopy(substr($postData['lblSyohy_no'], 0, 15), $postData['intEdaNo'], substr($postData['lblSyohy_no'], 15, 2), $dtSysdate, $postData['PatternIDFLG']);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }

                $strGyoNo = '';

                //登録処理
                switch ($postData['sender']) {
                    case "CMDEVENTINSERT": //追加ボタンが押下された場合
                        //登録処理を行う
                        $strGyoNo = "1";
                        $res = $this->HMDPS102ShiharaiDenpyoInput->fncShiwakeDataIns(substr($postData['lblSyohy_no'], 0, 15), str_pad($postData['intEdaNo'], 2, "0", STR_PAD_LEFT), $strGyoNo, $dtSysdate, $postData['PatternIDFLG'], $postData);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    case "CMDEVENTUPDATE": //修正ボタンが押下された場合
                        //修正処理を行う
                        $strGyoNo = $postData['hidGyoNO'];
                        $res = $this->HMDPS102ShiharaiDenpyoInput->fncUpdateSQL(substr($postData['lblSyohy_no'], 0, 15), str_pad($postData['intEdaNo'], 2, "0", STR_PAD_LEFT), $strGyoNo, $dtSysdate, $postData['PatternIDFLG'], $postData);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    case "CMDEVENTALLDELETE": //全削除ボタンが押下された場合
                        //削除処理を行う
                        $res = $this->HMDPS102ShiharaiDenpyoInput->fncAllDeleteUpd(substr($postData['lblSyohy_no'], 0, 15), $dtSysdate, $postData['PatternIDFLG']);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    default:
                        break;
                }

            } else {
                $strGyoNo = '';
                //登録処理
                switch ($postData['sender']) {
                    case "CMDEVENTINSERT": //追加ボタンが押下された場合
                        //登録処理を行う
                        $strGyoNo = "1";
                        $res = $this->HMDPS102ShiharaiDenpyoInput->fncShiwakeDataIns(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2), $strGyoNo, $dtSysdate, $postData['PatternIDFLG'], $postData);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    case "CMDEVENTUPDATE": //修正ボタンが押下された場合
                        //修正処理を行う
                        $strGyoNo = $postData['hidGyoNO'];
                        $res = $this->HMDPS102ShiharaiDenpyoInput->fncUpdateSQL(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2), $strGyoNo, $dtSysdate, $postData['PatternIDFLG'], $postData);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    case "CMDEVENTALLDELETE": //全削除ボタンが押下された場合
                        //削除処理を行う
                        $res = $this->HMDPS102ShiharaiDenpyoInput->fncAllDelete(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2));
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        //削除した行が最後の1件だった場合は、履歴の削除フラグを更新する
                        $res = $this->HMDPS102ShiharaiDenpyoInput->fncAllDeleteUpd(substr($postData['lblSyohy_no'], 0, 15), $dtSysdate, $postData['PatternIDFLG']);
                        if (!$res['result']) {
                            throw new \Exception($res['data']);
                        }
                        break;
                    default:
                        break;
                }
            }

            //コミット
            $this->HMDPS102ShiharaiDenpyoInput->Do_commit();
            $blnTran = FALSE;
            $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($postData['ddlRKamokuCD'], 6), 1), $postData['ddlRKomokuCD']);
            if (!$objds['result']) {
                throw new \Exception($objds['data']);
            }
            $result["data"]['RKOUBANTBL'] = $objds['data'];
            $result['data']['dtSysdate'] = $dtSysdate;
            $result['data']['intEdaNo'] = str_pad($postData['intEdaNo'], 2, "0", STR_PAD_LEFT);
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($blnTran) {
                $this->HMDPS102ShiharaiDenpyoInput->Do_rollback();
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
            $objDr = $this->HMDPS102ShiharaiDenpyoInput->fncSaiban($strKbn, $strBusyoCD, $strNengetu);
            if (!$objDr['result']) {
                throw new \Exception($objDr['data']);
            }

            //証憑№を戻す
            if ($objDr['row'] > 0) {
                $fncSaiban = $strKbn . $strBusyoCD . $strNengetu . str_pad($this->ClsComFncHMDPS->FncNv($objDr['data'][0]['BANGO']), 5, '0', STR_PAD_LEFT);
            } else {
                $fncSaiban = $strKbn . $strBusyoCD . $strNengetu . "00001";
            }

            if ($blnUpdate) {
                //採番ﾃｰﾌﾞﾙに既に同一年月のものがあればUPDATE、なければINSERT
                $objDr = $this->HMDPS102ShiharaiDenpyoInput->fncSaiban2($strKbn, $strBusyoCD, $strNengetu, $strProID, $objDr);
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
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];

            //名称取得
            $kamokuRes = $this->ClsComFncHMDPS->FncGetKamokuMstValue($postData['strCode'], $postData['strKomoku'], FALSE);
            if (!$kamokuRes['result']) {
                throw new \Exception($kamokuRes['error']);
            }
            $result['data']['lblLKamokuNM'] = $kamokuRes['intRtnCD'] == 1 ? $kamokuRes['strKamokuNM'] : '';

            //口座キー・必須摘要の名称を取得する(借方)
            $res = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata($postData['strCode'], $postData['txtLKomokuCD']);
            if (!$res['result']) {
                throw new \Exception($res['error']);
            }
            $result['data']['LKOUBANTBL'] = $res['data'];

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
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];

            $kamokuRes = $this->ClsComFncHMDPS->FncGetKamokuMstValue($postData['strCode'], $postData['strKomoku'], FALSE);
            if (!$kamokuRes['result']) {
                throw new \Exception($kamokuRes['error']);
            }
            $result['data']['lblLKamokuNM'] = $kamokuRes['intRtnCD'] == 1 ? $kamokuRes['strKamokuNM'] : '';

            //口座キー・必須摘要の名称を取得する(借方)
            $res = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata($postData['strCode'], $postData['txtLKomokuCD']);
            if (!$res['result']) {
                throw new \Exception($res['error']);
            }
            $result['data']['LKOUBANTBL'] = $res['data'];

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
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];
            $res = $this->HMDPS102ShiharaiDenpyoInput->fncPatternDelete($postData['hidPatternNO']);
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
            $this->Session = $this->request->getSession();
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];

            //20240426 lqs UPD S
            // $kamokuRes = $this->ClsComFncHMDPS->FncGetKamokuMstValue($postData['txtLKamokuCD'], $postData['txtLKamokuCD'], FALSE);
            $kamokuRes = $this->ClsComFncHMDPS->FncGetKamokuMstValue($postData['txtLKamokuCD'], $postData['strLKomokuCD'], FALSE);
            //20240426 lqs UPD E
            if (!$kamokuRes['result']) {
                throw new \Exception($kamokuRes['error']);
            }
            $result['data']['lblLKamokuNM'] = $kamokuRes['intRtnCD'] == 1 ? $kamokuRes['strKamokuNM'] : '';

            //口座キー・必須摘要の名称を取得する(借方)
            $res = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata($postData['txtLKamokuCD'], $postData['txtLKomokuCD']);
            if (!$res['result']) {
                throw new \Exception($res['error']);
            }
            $result['data']['LKOUBANTBL'] = $res['data'];

            //名称取得
            $txtLBusyoCD = $postData['txtLBusyoCD'];
            $txtRbusyoCD = $postData['txtRbusyoCD'];
            $objDs = $this->ClsComFncHMDPS->FncGetBusyoMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }

            $result['data']['strSyozokuTenpo'] = '';
            for ($i = 0; $i < count($objDs['data']); $i++) {
                $one = $objDs['data'][$i];
                if ($one['BUSYO_CD'] == $this->Session->read('BusyoCD')) {
                    $result['data']['strSyozokuTenpo'] = $one['BUSYO_NM'];
                    break;
                }
            }
            $objDs = $this->ClsComFncHMDPS->FncGetBusyoMstValue();
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
            $objDs = $this->ClsComFncHMDPS->FncGetBusyoMstValue(true, $txtRbusyoCD);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['strKashiTenpo'] = '';
            for ($i = 0; $i < count($objDs['data']); $i++) {
                $one = $objDs['data'][$i];
                if ($one['BUSYO_CD'] == $txtRbusyoCD) {
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

    public function btnClearClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];
            $objds = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($postData['ddlRKamokuCD'], 6), 1), $postData['ddlRKomokuCD']);
            if (!$objds['result']) {
                throw new \Exception($objds['data']);
            }
            $result["data"]['RKOUBANTBL'] = $objds['data'];

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
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];
            $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncSelectPattern($postData['ddlPatternSel']);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['PATTERNTBL'] = $objDs['data'];

            if (count((array) $result['data']['PATTERNTBL']) != 0) {
                //口座キー・必須摘要の名称を取得する(借方)
                $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata($result['data']['PATTERNTBL'][0]['L_KAMOK_CD'], $result['data']['PATTERNTBL'][0]['L_KOUMK_CD']);
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result['data']['LKOUBANTBL'] = $objDs['data'];

                //口座キー・必須摘要の名称を取得する(貸方)
                $rkamokuCD = substr(str_pad($this->ClsComFncHMDPS->FncNv($result["data"]['PATTERNTBL'][0]['SHR_KAMOK_KB']), 3), 0, 1) . $result["data"]['PATTERNTBL'][0]['R_KAMOK_CD'];
                $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($rkamokuCD, 6), 1), $result['data']['PATTERNTBL'][0]['R_KOUMK_CD']);
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result['data']['RKOUBANTBL'] = $objDs['data'];
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function ddlRKamokuCDSelectedIndexChanged()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];

            //口座キー・必須摘要の名称を取得する(貸方)
            $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncKouzaHittekiKashikata(substr(str_pad($postData['ddlRKamokuCD'], 6), 1), $postData['ddlRKomokuCD']);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['RKOUBANTBL'] = $objDs['data'];

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
            $this->Session = $this->request->getSession();
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $postData = $_POST['data'];

            if ($postData['hidPatternNO'] == "") {
                $res = $this->HMDPS102ShiharaiDenpyoInput->fncPatternTrkDispShiwake($postData);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
            } else {
                $res = $this->HMDPS102ShiharaiDenpyoInput->fncUpdPatternTrk("2", $postData['hidPatternNO'], $postData);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
            }

            //パターンの値を取得
            $PatternDDLRes = $this->HMDPS102ShiharaiDenpyoInput->fncSelPattern($this->Session->read('BusyoCD'));
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
        $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
        try {
            $this->Session = $this->request->getSession();
            $postData = $_POST['data'];

            //削除されていないかチェックします。
            $objds = $this->HMDPS102ShiharaiDenpyoInput->fncFlgCheckSQL(substr($postData['lblSyohy_no'], 0, 15), substr($postData['lblSyohy_no'], 15, 2));
            if (!$objds['result']) {
                throw new \Exception($objds['data']);
            }
            $result["data"]['DispModeTbl'] = $objds['data'];

            if (count((array) $result["data"]['DispModeTbl']) > 0) {
                $objds = $this->HMDPS102ShiharaiDenpyoInput->fncNewSyohyNOSel(substr($postData['lblSyohy_no'], 0, 15));
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
                    $this->HMDPS102ShiharaiDenpyoInput->Do_transaction();

                    //ワーク証憑№のデータを全件削除する
                    $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncAllDelSQL();
                    if (!$objDs['result']) {
                        throw new \Exception($objDs['data']);
                    }

                    // ワーク証憑№に対象の証憑№をＩＮＳＥＲＴする
                    $objDs = $this->HMDPS102ShiharaiDenpyoInput->fncInsTaisyoSyohyNOPrint($postData['lblSyohy_no']);
                    if (!$objDs['result']) {
                        throw new \Exception($objDs['data']);
                    }
                    $result["data"]['intTaisyo'] = $objDs['number_of_rows'];

                    //コミット
                    $this->HMDPS102ShiharaiDenpyoInput->Do_commit();

                    $arr['CONST_ADMIN_PTN_NO'] = $postData['CONST_ADMIN_PTN_NO'];
                    $arr['CONST_HONBU_PTN_NO'] = $postData['CONST_HONBU_PTN_NO'];
                    $arr['BusyoCD'] = $this->Session->read('BusyoCD');
                    $printPDF = $this->CustomExportPDF->FncDenpyoinsatuPrint("102", $arr);
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

    //20240418 lqs INS S
    // '**********************************************************************
    // '処 理 名：お客様名／取引先名取得
    // '関 数 名：txtOkyakusamaNOTorihikisakiNmSet
    // '処理説明：フォーカス移動時にお客様名／取引先名を取得する
    // '**********************************************************************
    public function txtOkyakusamaNOTorihikisakiNmSet()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $postData = $_POST['data'];
            //名称取得
            $this->HMDPS102ShiharaiDenpyoInput = new HMDPS102ShiharaiDenpyoInput();
            $objDs = $this->HMDPS102ShiharaiDenpyoInput->FncGetNameValue($postData);
            $result['data']['NM'] = '';
            if ($objDs) {
                if (!$objDs['result']) {
                    throw new \Exception($objDs['error']);
                }
                if (count((array) $objDs['data']) > 0) {
                    $result['data']['NM'] = $objDs['data'][0]['NM'];
                }
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }
    //20240418 lqs INS E
}
