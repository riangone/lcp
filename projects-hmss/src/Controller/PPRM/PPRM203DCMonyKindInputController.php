<?php
/**
 * 説明：
 *
 *
 * @author YANGYANG
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM203DCMonyKindInput;
use App\Model\PPRM\Component\ClsProc;
use App\Model\PPRM\Component\ClsComFncPprm;

//*******************************************
// * sample controller
//*******************************************
class PPRM203DCMonyKindInputController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    // public $autoRender = false;

    // public $ClsComFnc = '';
    public $PPRM203DCMonyKindInput;
    public $ClsComFncPprm;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    public $result = array();
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM203DCMonyKindInput_layout';
        $this->render('/PPRM/PPRM203DCMonyKindInput/index', $layout);
    }

    //'***********************************************************************
    //'処 理 名：権限設定（初期値）
    //'関 数 名：pPRM203DCMonyKindInput_load
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：権限設定（初期値）
    //'***********************************************************************
    public function pPRM203DCMonyKindInputLoad()
    {
        $ClsProc = new ClsProc();
        $txtTenpoCD = $_POST['data']['txtTenpoCD'];
        $Session = $this->request->getSession();
        $btnEnabled = $ClsProc->SubSetEnabled_OnPageLoad($Session->read('Sys_KB'), "PPRM203DCMonyKindInput", "81121", $txtTenpoCD);
        $result['result'] = true;
        $result['data'] = $btnEnabled;

        $this->fncReturn($result);
    }

    //'*********************************************************************
    //'処 理 名：コールバック処理
    //'関 数 名：raiseCallbackEvent
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：コールバック処理を行う
    //'********************************************************************
    public function raiseCallbackEvent()
    {
        try {
            $strTCD = "";
            $strHNO = "";
            $strHDT = "";
            $strFLG = "";
            $strRenban = "";
            $arrValues = array();

            $eventArgument = $_POST['data']["eventArgument"];
            $arrValues = explode("\r\n", $eventArgument);
            $strRetValue = '';

            switch ($arrValues[0]) {
                case '0':
                    //店舗コード

                    $strHDT = $arrValues[1];
                    $strHDT = substr($strHDT, 1);
                    $strHDT = str_replace("/", "", $strHDT);
                    if ($strHDT != "") {
                        $strHDT = str_pad($strHDT, 8, " ", STR_PAD_RIGHT);
                    }
                    $strHDT = substr($strHDT, 2);
                    $strHDT = rtrim($strHDT);

                    $strTCD = $arrValues[2];
                    $strTCD = substr($strTCD, 1);
                    if ($strTCD != "") {
                        $strTCD = str_pad($strTCD, 3, " ", STR_PAD_RIGHT);
                    }
                    $strTCD = substr($strTCD, 0, 3);
                    $strTCD = rtrim($strTCD);

                    $strHNO = $strTCD . $strHDT;

                    // 日締№取得 --SQL
                    $strRenban = $this->getRenban($strTCD, $strHNO);
                    if (!$strRenban['result']) {
                        throw new \Exception($strRenban['data']);
                    }
                    $strRenban = $strRenban['data'];

                    $strFLG = 1;

                    // 部署名取得
                    $result = $this->FncGetBusyoNM($strTCD);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $strRetValue = $result['data'];
                    $strFLG = 1;
                    // 結果
                    $strRetValue = $arrValues[0] . "\r\n" . $strRetValue . "\r\n" . $strHNO . $strRenban . "\r\n" . $strFLG;
                    break;

                case '1':
                    //日締№

                    if ($arrValues[1] == "" || $arrValues[1] == null || !isset($arrValues[1])) {
                        $strTCD = "";
                        $strHNO = "";
                        $strHDT = "";
                    } else {
                        if (strlen($arrValues[1]) == 12) {
                            $strTCD = $arrValues[1];
                            $strTCD = str_pad($strTCD, 12, " ", STR_PAD_RIGHT);
                            $strTCD = substr($strTCD, 0, 3);
                            $strTCD = rtrim($strTCD);

                            $strHNO = $arrValues[1];

                            $tmp1 = $arrValues[1];
                            $tmp1 = str_pad($tmp1, 12, " ", STR_PAD_RIGHT);
                            $tmp1 = substr($tmp1, 3, 2);

                            $tmp2 = $arrValues[1];
                            $tmp2 = str_pad($tmp2, 12, " ", STR_PAD_RIGHT);
                            $tmp2 = substr($tmp2, 5, 2);

                            $tmp3 = $arrValues[1];
                            $tmp3 = str_pad($tmp3, 12, " ", STR_PAD_RIGHT);
                            $tmp3 = substr($tmp3, 7, 2);
                            $strHDT = "20" . $tmp1 . "/" . $tmp2 . "/" . $tmp3;

                            if ($this->ClsComFnc->IsDate($strHDT) == false) {
                                $strHDT = "";
                                $strFLG = 0;
                            } else {
                                $strFLG = 1;
                            }
                            $result = $this->FncGetBusyoNM($strTCD);
                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                            $strRetValue = $result['data'];
                        } else {
                            $strTCD = "";
                            $strHNO = "";
                            $strHDT = "";
                            $strFLG = 0;
                        }
                    }
                    //結果
                    $strRetValue = $arrValues[0] . "\r\n" . $strRetValue . "\r\n" . $strTCD . "\r\n" . $strHDT . "\r\n" . $strFLG;
                    break;

                case '2':
                    //日締日

                    $strHDT = $arrValues[1];
                    $strHDT = substr($strHDT, 1);
                    $strHDT = str_replace("/", "", $strHDT);
                    if ($strHDT != "") {
                        $strHDT = str_pad($strHDT, 8, " ", STR_PAD_RIGHT);
                    }
                    $strHDT = substr($strHDT, 2);
                    $strHDT = rtrim($strHDT);

                    $strTCD = $arrValues[2];
                    $strTCD = substr($strTCD, 1);
                    if ($strTCD != "") {
                        $strTCD = str_pad($strTCD, 3, " ", STR_PAD_RIGHT);
                    }
                    $strTCD = substr($strTCD, 0, 3);
                    $strTCD = rtrim($strTCD);

                    $strHNO = $strTCD . $strHDT;

                    $strRenban = $this->getRenban($strTCD, $strHNO);
                    if (!$strRenban['result']) {
                        throw new \Exception($strRenban['data']);
                    }
                    $strRenban = $strRenban['data'];

                    $strFLG = 1;

                    //'結果
                    $strRetValue = $arrValues[0] . "\r\n" . $strRetValue . "\r\n" . $strHNO . $strRenban . "\r\n" . $strFLG;
                    break;
            }

            $this->result['result'] = TRUE;
            $this->result['data'] = $strRetValue;
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：日締№取得
    //'関 数 名：getRenban
    //'引 数 1 ：strTCD(店舗コード)
    //'引 数 1 ：strHNO(日締№)
    //'戻 り 値：result
    //'処理説明：getHJMNO
    //'**********************************************************************
    public function getRenban($strTCD, $strHNO)
    {
        try {
            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $result = $this->PPRM203DCMonyKindInput->getRenban($strTCD, $strHNO);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if ($result['row'] > 0) {
                $strHJMNO = $this->ClsComFnc->FncNv($result['data'][0]['TEN_HJM_NO']);

                if ($strHJMNO == "") {
                    $getRenban = "001";
                } else {
                    $getRenban = $strHJMNO + 1;
                    $getRenban = str_pad($getRenban, 12, " ", STR_PAD_RIGHT);
                    $getRenban = substr($getRenban, 9, 3);
                    $getRenban = rtrim($getRenban);
                }
            } else {
                $getRenban = "001";
            }

            $result['result'] = TRUE;
            $result['data'] = $getRenban;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //'**********************************************************************
    //'処 理 名：店舗名取得（関数）
    //'関 数 名：getBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に店舗名を取得する
    //'**********************************************************************
    public function getBusyoNM()
    {
        try {
            $strCD = $_POST['data']['strCD'];
            $result = $this->FncGetBusyoNM($strCD);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：店舗名取得（関数）
    //'関 数 名：FncGetBusyoNM
    //'引 数 　：strTCD(店舗コード)
    //'戻 り 値：result
    //'処理説明：値変更時に店舗名を取得する
    //'**********************************************************************
    public function FncGetBusyoNM($strTCD)
    {
        try {
            $this->ClsComFncPprm = new ClsComFncPprm();
            $result = $this->ClsComFncPprm->FncGetBusyoMstValue_ppr($strTCD, TRUE);
            if ($result['result'] && $result['row'] > 0) {
                $result['data'] = $this->ClsComFnc->FncNv($result['data'][0]['BUSYO_NM']);
            } else {
                $result['data'] = '';
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }
    //20170905 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部店舗名取得（関数）
    //'関 数 名：fncGetALLBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に店舗名を取得する
    //'**********************************************************************
    public function fncGetALLBusyoNM()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $this->ClsComFncPprm = new ClsComFncPprm();
            $result = $this->ClsComFncPprm->FncGetALLBusyoMstPPR();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);

    }
    //20170905 ZHANGXIAOLEI INS E

    //'**********************************************************************
    //'処 理 名：日締№取得
    //'関 数 名：getHJMNO
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：getHJMNO
    //'**********************************************************************
    public function getHJMNO()
    {
        try {
            $txtTenpoCD = $_POST['data']['txtTenpoCD'];
            $HJMNo = $_POST['data']['HJMNo'];

            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $result = $this->PPRM203DCMonyKindInput->getHJMNO($txtTenpoCD, $HJMNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if ($result['row'] > 0) {
                $strHJMNO = $this->ClsComFnc->FncNv($result['data'][0]['TEN_HJM_NO']);

                if ($strHJMNO == "") {
                    $getHJMNO = $HJMNo . "001";
                } else {
                    $getHJMNO = $strHJMNO + 1;
                }
            } else {
                $getHJMNO = $HJMNo . "001";
            }

            $this->result['result'] = TRUE;
            $this->result['data'] = $getHJMNO;
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：経理承認確認
    //'関 数 名：managerConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：経理承認確認
    //'**********************************************************************
    public function managerConfirm()
    {
        try {
            $txtTenpoCD = $_POST['data']['txtTenpoCD'];
            $txtHJMNo = $_POST['data']['txtHJMNo'];

            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $result = $this->PPRM203DCMonyKindInput->managerConfirm($txtTenpoCD, $txtHJMNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if ($result['row'] > 0) {
                $strFLG = $this->ClsComFnc->FncNv($result['data'][0]['KEIRI_SNN_FLG']);
            } else {
                $strFLG = "";
            }

            $this->result['result'] = TRUE;
            $this->result['data'] = $strFLG;

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：金種別残高データ取得/小切手データ取得/帳簿上の残高取得/実際の残高取得
    //'関 数 名：getDataAll
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：金種別残高データ取得/小切手データ取得/帳簿上の残高取得/実際の残高取得
    //'**********************************************************************
    public function getDataAll()
    {
        try {
            $txtTenpoCD = $_POST['data']['txtTenpoCD'];
            $txtHJMNo = $_POST['data']['txtHJMNo'];

            $result1 = $this->setKinsyuData($txtTenpoCD, $txtHJMNo);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $result2 = $this->setKogiteData($txtTenpoCD, $txtHJMNo);
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            $result3 = $this->getTyouboZandaka($txtTenpoCD, $txtHJMNo);
            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }

            $result4 = $this->getJissaiZandaka($txtTenpoCD, $txtHJMNo);
            if (!$result4['result']) {
                throw new \Exception($result4['data']);
            }

            $this->result['result'] = TRUE;
            $this->result['setKinsyuData'] = $result1['data'];
            $this->result['setKogiteData'] = $result2['data'];
            $this->result['getTyouboZandaka'] = $result3['data'];
            $this->result['getJissaiZandaka'] = $result4['data'];

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：金種別残高データ取得
    //'関 数 名：setKinsyuData
    //'引 数 1：txtTenpoCD(店舗コード)
    //'引 数 2：txtHJMNo(日締№)
    //'戻 り 値：result
    //'処理説明：金種別残高データ取得
    //'**********************************************************************
    public function setKinsyuData($txtTenpoCD, $txtHJMNo)
    {
        try {
            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $result = $this->PPRM203DCMonyKindInput->setKinsyuData($txtTenpoCD, $txtHJMNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：小切手データ取得
    //'関 数 名：setKogiteData
    //'引 数 1：txtTenpoCD(店舗コード)
    //'引 数 2：txtHJMNo(日締№)
    //'戻 り 値：result
    //'処理説明：小切手データ取得
    //'**********************************************************************
    public function setKogiteData($txtTenpoCD, $txtHJMNo)
    {
        try {
            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $result = $this->PPRM203DCMonyKindInput->setKogiteData($txtTenpoCD, $txtHJMNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：帳簿上の残高取得
    //'関 数 名：getTyouboZandaka
    //'引 数 1：txtTenpoCD(店舗コード)
    //'引 数 2：txtHJMNo(日締№)
    //'戻 り 値：result
    //'処理説明：帳簿上の残高取得
    //'**********************************************************************
    public function getTyouboZandaka($txtTenpoCD, $txtHJMNo)
    {
        try {
            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $result = $this->PPRM203DCMonyKindInput->getTyouboZandaka($txtTenpoCD, $txtHJMNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：実際の残高取得
    //'関 数 名：getJissaiZandaka
    //'引 数 1：txtTenpoCD(店舗コード)
    //'引 数 2：txtHJMNo(日締№)
    //'戻 り 値：result
    //'処理説明：実際の残高取得
    //'**********************************************************************
    public function getJissaiZandaka($txtTenpoCD, $txtHJMNo)
    {
        try {
            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $result = $this->PPRM203DCMonyKindInput->getJissaiZandaka($txtTenpoCD, $txtHJMNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;

    }

    //'**********************************************************************
    //'処 理 名：日締№検索（関数）
    //'関 数 名：FncUpdDate
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：日締№の有無をチェック
    //'**********************************************************************
    public function fncUpdDate()
    {
        try {
            $strTCD = $_POST['data']['strTCD'];
            $strHJMNo = $_POST['data']['strHJMNo'];

            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $result = $this->PPRM203DCMonyKindInput->FncUpdDate($strTCD, $strHJMNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $FncUpdDate = '';
            if ($result['row'] > 0) {
                $FncUpdDate = $this->ClsComFnc->FncNv($result['data'][0]['UPD_DATE']);
            }

            $this->result['result'] = TRUE;
            $this->result['data'] = $FncUpdDate;

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：金種データの登録処理を実行
    //'関 数 名：InsertAllData
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：金種データの登録処理を実行
    //'**********************************************************************
    public function insertAllData()
    {
        try {
            $postData = $_POST['data'];
            $txtTenpoCD = $postData['txtTenpoCD'];
            $txtHJMNo = $postData['txtHJMNo'];

            //DB接続
            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $DB_Conn = $this->PPRM203DCMonyKindInput->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //トランザクション開始
            $this->PPRM203DCMonyKindInput->Do_transaction();

            //店舗日締金種明細データ削除
            $result1 = $this->DeleteMeisai($txtTenpoCD, $txtHJMNo);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            //店舗日締金種ヘッダーデータ削除
            $result2 = $this->DeleteHeader($txtTenpoCD, $txtHJMNo);
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            //店舗日締金種明細データ登録
            //金種別残高用
            $txtMaisu_10000 = $postData['txtMaisu_10000'];
            $lblKin_10000 = $postData['lblKin_10000'];
            $txtMaisu_5000 = $postData['txtMaisu_5000'];
            $lblKin_5000 = $postData['lblKin_5000'];
            $txtMaisu_2000 = $postData['txtMaisu_2000'];
            $lblKin_2000 = $postData['lblKin_2000'];
            $txtMaisu_1000 = $postData['txtMaisu_1000'];
            $lblKin_1000 = $postData['lblKin_1000'];
            $txtMaisu_500 = $postData['txtMaisu_500'];
            $lblKin_500 = $postData['lblKin_500'];
            $txtMaisu_100 = $postData['txtMaisu_100'];
            $lblKin_100 = $postData['lblKin_100'];
            $txtMaisu_50 = $postData['txtMaisu_50'];
            $lblKin_50 = $postData['lblKin_50'];
            $txtMaisu_10 = $postData['txtMaisu_10'];
            $lblKin_10 = $postData['lblKin_10'];
            $txtMaisu_5 = $postData['txtMaisu_5'];
            $lblKin_5 = $postData['lblKin_5'];
            $txtMaisu_1 = $postData['txtMaisu_1'];
            $lblKin_1 = $postData['lblKin_1'];

            $strKind = "";
            $lngMNO = 0;
            $strKinsyu = "";
            $lngMaisu = 0;
            $lngZandaka = 0;
            $strExec = "";

            $arrData1 = array();
            for ($i = 1; $i < 11; $i++) {
                $colomns = array();
                //Insert実行フラグ
                $strExec = "0";
                switch ($i) {
                    case 1:
                        if ($txtMaisu_10000 != "") {
                            $strKind = "0";
                            $lngMNO = 1;
                            $strKinsyu = "10000";
                            $lngMaisu = (int) $txtMaisu_10000;
                            $lngZandaka = (int) $lblKin_10000;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    case 2:
                        if ($txtMaisu_5000 != "") {
                            $strKind = "0";
                            $lngMNO = 2;
                            $strKinsyu = "5000";
                            $lngMaisu = (int) $txtMaisu_5000;
                            $lngZandaka = (int) $lblKin_5000;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    case 3:
                        if ($txtMaisu_2000 != "") {
                            $strKind = "0";
                            $lngMNO = 3;
                            $strKinsyu = "2000";
                            $lngMaisu = (int) $txtMaisu_2000;
                            $lngZandaka = (int) $lblKin_2000;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    case 4:
                        if ($txtMaisu_1000 != "") {
                            $strKind = "0";
                            $lngMNO = 4;
                            $strKinsyu = "1000";
                            $lngMaisu = (int) $txtMaisu_1000;
                            $lngZandaka = (int) $lblKin_1000;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    case 5:
                        if ($txtMaisu_500 != "") {
                            $strKind = "1";
                            $lngMNO = 1;
                            $strKinsyu = "500";
                            $lngMaisu = (int) $txtMaisu_500;
                            $lngZandaka = (int) $lblKin_500;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    case 6:
                        if ($txtMaisu_100 != "") {
                            $strKind = "1";
                            $lngMNO = 2;
                            $strKinsyu = "100";
                            $lngMaisu = (int) $txtMaisu_100;
                            $lngZandaka = (int) $lblKin_100;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    case 7:
                        if ($txtMaisu_50 != "") {
                            $strKind = "1";
                            $lngMNO = 3;
                            $strKinsyu = "50";
                            $lngMaisu = (int) $txtMaisu_50;
                            $lngZandaka = (int) $lblKin_50;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    case 8:
                        if ($txtMaisu_10 != "") {
                            $strKind = "1";
                            $lngMNO = 4;
                            $strKinsyu = "10";
                            $lngMaisu = (int) $txtMaisu_10;
                            $lngZandaka = (int) $lblKin_10;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    case 9:
                        if ($txtMaisu_5 != "") {
                            $strKind = "1";
                            $lngMNO = 5;
                            $strKinsyu = "5";
                            $lngMaisu = (int) $txtMaisu_5;
                            $lngZandaka = (int) $lblKin_5;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    case 10:
                        if ($txtMaisu_1 != "") {
                            $strKind = "1";
                            $lngMNO = 6;
                            $strKinsyu = "1";
                            $lngMaisu = (int) $txtMaisu_1;
                            $lngZandaka = (int) $lblKin_1;
                            $strExec = "1";

                            $colomns['strKind'] = $strKind;
                            $colomns['lngMNO'] = $lngMNO;
                            $colomns['strKinsyu'] = $strKinsyu;
                            $colomns['lngMaisu'] = $lngMaisu;
                            $colomns['lngZandaka'] = $lngZandaka;
                        } else {
                            $strExec = "0";
                        }
                        $colomns['strExec'] = $strExec;
                        array_push($arrData1, $colomns);
                        break;

                    default:
                        break;
                }
            }

            $Session = $this->request->getSession();
            $sessionData = array(
                'UserId' => $Session->read('login_user'),
                'BusyoCD' => $Session->read('BusyoCD'),
                'MachineNM' => $this->request->clientIp(),
            );
            foreach ($arrData1 as $value) {
                $strExec = $value['strExec'];

                if ($strExec == "1") {
                    $strKind = $value['strKind'];
                    $lngMNO = $value['lngMNO'];
                    $strKinsyu = $value['strKinsyu'];
                    $lngMaisu = $value['lngMaisu'];
                    $lngZandaka = $value['lngZandaka'];

                    $result3 = $this->InsertMeisai($txtTenpoCD, $txtHJMNo, $strKind, $lngMNO, $strKinsyu, $lngMaisu, $lngZandaka, $sessionData);
                    if (!$result3['result']) {
                        throw new \Exception($result3['data']);
                    }
                }
            }

            //小切手用
            if (isset($postData['arrKinsyu']) && $postData['arrZandaka']) {
                $arrKinsyu = $postData['arrKinsyu'];
                $arrZandaka = $postData['arrZandaka'];
                $arrData2 = array();
                for ($j = 0; $j < count($arrKinsyu); $j++) {
                    $colomns = array();
                    $colomns['strKinsyu'] = $arrKinsyu[$j];
                    $colomns['lngZandaka'] = $arrZandaka[$j];
                    $lngMNO = $j + 1;
                    $colomns['lngMNO'] = $lngMNO;
                    array_push($arrData2, $colomns);
                }

                foreach ($arrData2 as $value) {
                    $strKinsyu = $value['strKinsyu'];
                    $lngZandaka = $value['lngZandaka'];
                    $lngMNO = $value['lngMNO'];

                    $result4 = $this->InsertKinsyu($txtTenpoCD, $txtHJMNo, $strKinsyu, $lngZandaka, $lngMNO, $sessionData);
                    if (!$result4['result']) {
                        throw new \Exception($result4['data']);
                    }

                }
            }


            //店舗日締金種ヘッダーデータ登録
            //小計用
            $lblShiheiGoukei = $postData['lblShiheiGoukei'];
            $lblKoukaGoukei = $postData['lblKoukaGoukei'];
            $lblKogiteGoukei = $postData['lblKogiteGoukei'];
            $lblShiheiGoukei = $postData['lblShiheiGoukei'];
            $lblJissaiGoukei = $postData['lblJissaiGoukei'];
            $txtRiyu = $postData['txtRiyu'];
            $genkinzangk = (int) $lblShiheiGoukei + (int) $lblKoukaGoukei;

            $result5 = $this->InsertHeader($txtTenpoCD, $txtHJMNo, $lblShiheiGoukei, $lblKoukaGoukei, $lblKogiteGoukei, $genkinzangk, $lblJissaiGoukei, $txtRiyu, $sessionData);
            if (!$result5['result']) {
                throw new \Exception($result5['data']);
            }

            //コミット
            $this->PPRM203DCMonyKindInput->Do_commit();

            $this->result['result'] = TRUE;

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $this->PPRM203DCMonyKindInput->Do_rollback();
        }

        if (isset($this->PPRM203DCMonyKindInput->conn_orl)) {
            $this->PPRM203DCMonyKindInput->Do_close();
            unset($this->PPRM203DCMonyKindInput->conn_orl);
        }

        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：店舗日締金種明細データ削除
    //'関 数 名：DeleteMeisai
    //'引 数 1：txtTenpoCD(店舗コード)
    //'引 数 2：txtHJMNo(日締№)
    //'戻 り 値：result
    //'処理説明：店舗日締金種明細データ削除
    //'**********************************************************************
    public function DeleteMeisai($txtTenpoCD, $txtHJMNo)
    {
        try {
            $result = $this->PPRM203DCMonyKindInput->DeleteMeisai($txtTenpoCD, $txtHJMNo);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：店舗日締金種ヘッダーデータ削除
    //'関 数 名：DeleteHeader
    //'引 数 1：txtTenpoCD(店舗コード)
    //'引 数 2：txtHJMNo(日締№)
    //'戻 り 値：result
    //'処理説明：店舗日締金種ヘッダーデータ削除
    //'**********************************************************************
    public function DeleteHeader($txtTenpoCD, $txtHJMNo)
    {
        try {
            $result = $this->PPRM203DCMonyKindInput->DeleteHeader($txtTenpoCD, $txtHJMNo);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：店舗日締金種明細データ登録
    //'関 数 名：InsertMeisai
    //'引 数 1：txtTenpoCD(店舗コード)
    //'引 数 2：txtHJMNo(日締№)
    //'引 数 3：strKind(紙幣0/硬貨1/小切手2)
    //'引 数 4：lngMNO(1-6行)
    //'引 数 5：strKinsyu(金種)
    //'引 数 6：lngMaisu(枚数)
    //'引 数 7：lngZandaka(残高)
    //'戻 り 値：result
    //'処理説明：金種別残高用
    //'**********************************************************************
    public function InsertMeisai($txtTenpoCD, $txtHJMNo, $strKind, $lngMNO, $strKinsyu, $lngMaisu, $lngZandaka, $sessionData)
    {
        try {
            $result = $this->PPRM203DCMonyKindInput->InsertMeisai($txtTenpoCD, $txtHJMNo, $strKind, $lngMNO, $strKinsyu, $lngMaisu, $lngZandaka, $sessionData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：小切手明細データ登録
    //'関 数 名：InsertKinsyu
    //'引 数 1：txtTenpoCD(店舗コード)
    //'引 数 2：txtHJMNo(日締№)
    //'引 数 3：strKinsyu(小切手№)
    //'引 数 4：lngZandaka(金額)
    //'引 数 5：lngMNO
    //'戻 り 値：result
    //'処理説明：小切手用
    //'**********************************************************************
    public function InsertKinsyu($txtTenpoCD, $txtHJMNo, $strKinsyu, $lngZandaka, $lngMNO, $sessionData)
    {
        try {
            $result = $this->PPRM203DCMonyKindInput->InsertKinsyu($txtTenpoCD, $txtHJMNo, $strKinsyu, $lngZandaka, $lngMNO, $sessionData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：店舗日締金種ヘッダーデータ登録
    //'関 数 名：InsertHeader
    //'引 数 1：txtTenpoCD(店舗コード)
    //'引 数 2：txtHJMNo(日締№)
    //'引 数 3：lblShiheiGoukei(小計①)
    //'引 数 4：lblKoukaGoukei(小計②)
    //'引 数 5：lblKogiteGoukei(小計③)
    //'引 数 6：genkinzangk(小計①+小計②)
    //'引 数 7：lblJissaiGoukei(実際の残高)
    //'引 数 8：txtRiyu(帳簿上の残高と実際の残高の不一致の理由)
    //'戻 り 値：result
    //'処理説明：店舗日締金種ヘッダーデータ登録
    //'**********************************************************************
    public function InsertHeader($txtTenpoCD, $txtHJMNo, $lblShiheiGoukei, $lblKoukaGoukei, $lblKogiteGoukei, $genkinzangk, $lblJissaiGoukei, $txtRiyu, $sessionData)
    {
        try {
            $result = $this->PPRM203DCMonyKindInput->InsertHeader($txtTenpoCD, $txtHJMNo, $lblShiheiGoukei, $lblKoukaGoukei, $lblKogiteGoukei, $genkinzangk, $lblJissaiGoukei, $txtRiyu, $sessionData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：削除処理
    //'関 数 名：cmdEvent_Click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：金種データの削除
    //'**********************************************************************
    public function cmdEventClick()
    {
        try {
            $postData = $_POST['data'];
            $txtTenpoCD = $postData['txtTenpoCD'];
            $txtHJMNo = $postData['txtHJMNo'];

            //DB接続
            $this->PPRM203DCMonyKindInput = new PPRM203DCMonyKindInput();
            $DB_Conn = $this->PPRM203DCMonyKindInput->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //トランザクション開始
            $this->PPRM203DCMonyKindInput->Do_transaction();

            //店舗日締金種明細データ削除
            $result1 = $this->DeleteMeisai($txtTenpoCD, $txtHJMNo);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            //店舗日締金種ヘッダーデータ削除
            $result2 = $this->DeleteHeader($txtTenpoCD, $txtHJMNo);
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            //コミット
            $this->PPRM203DCMonyKindInput->Do_commit();

            $this->result['result'] = TRUE;
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $this->PPRM203DCMonyKindInput->Do_rollback();
        }

        if (isset($this->PPRM203DCMonyKindInput->conn_orl)) {
            $this->PPRM203DCMonyKindInput->Do_close();
            unset($this->PPRM203DCMonyKindInput->conn_orl);
        }
        $this->fncReturn($this->result);
    }

}
