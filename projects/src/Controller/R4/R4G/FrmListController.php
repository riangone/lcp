<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmList;

class FrmListController extends AppController
{
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;

    // var $components = array('RequestHandler');
    // public $ClsComFnc = '';
    // public $ClsFncLog = '';
    // public $ClsReport = '';
    // public $ClsComDoRefresh = '';
    // public $ClsLogControl = '';
    public $FrmList;
    public function initialize(): void
    {
        parent::initialize();
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }

        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
        $this->loadComponent('ClsReport');
        $this->loadComponent('ClsComDoRefresh');
        $this->loadComponent('ClsLogControl');
    }
    public $DsKasouPrintTbl = array();

    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmList_layout.ctpを参照)
        $this->render('index', 'FrmList_layout');

    }

    public function fncUpdSaiban1($blnUpdate = TRUE)
    {
        $strNengetu = "";
        try {
            $strNengetu = Date("Ym");
            $fncUpdSaiban = "99999999999";
            //$this -> FrmList = new FrmList();
            $result = $this->FrmList->fncUpdSaiban($strNengetu, FALSE);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //新たに取得した番号を架装番号に設定
            $count = count((array) $result['data']);

            //採番ﾃｰﾌﾞﾙに既に同一年月のものがあればUPDATE、なければINSERT
            if ($count < 1) {
                $fncUpdSaiban = $strNengetu . "-" . "0001";
                $UPD_TIME = $this->ClsComFnc->FncSqlDate(Date("Y/m/d H:i:s"));
                if ($blnUpdate) {
                    $result = $this->FrmList->fncUpdSaibanInsert($UPD_TIME, $strNengetu, FALSE);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
            } else {
                foreach ((array) $result['data'] as $key => $value) {
                    $value = $this->ClsComFnc->FncNv($result['data'][$key]['BANGO']);
                    $value = str_pad($value, 4, '0', STR_PAD_LEFT);
                    $fncUpdSaiban = $strNengetu . "-" . $value;
                    $BANGO = $this->ClsComFnc->FncSqlNz($result['data'][$key]['BANGO']);
                }
                if ($blnUpdate) {
                    $UPD_TIME = $this->ClsComFnc->FncSqlDate(Date("Y/m/d H:i:s"));
                    $result = $this->FrmList->fncUpdSaibanUpdate($BANGO, $UPD_TIME, $strNengetu);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
            }
            $result['result'] = TRUE;
            $result['fncUpdSaiban'] = $fncUpdSaiban;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['MsgID'] = "E9999";
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //**********************************************************************
    //処理概要：架装明細保存
    //**********************************************************************
    public function cmdsave()
    {
        $postData = "";
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $SQL_Excute = "";
        $DB_Conn = "";

        $blnUpdFlg = FALSE;
        // ログ管理 STart
        $lngOutCntK = 0;
        $lngOutCntG = 0;
        $intState = 0;
        $strChumon = "";
        $strKasou = "";
        // ログ管理 End

        $blnPrintSkipFlg = False;

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
                $strChumon = $postData['strChumon'];
                $strKasou = $postData['strKasou'];
                $strHaisouSiji = $postData['strHaisouSiji'];

                $this->FrmList = new FrmList();
                //DB接続
                $DB_Conn = $this->FrmList->Do_conn();
                if (!$DB_Conn['result']) {
                    throw new \Exception($DB_Conn['data']);
                }

                $intState = 9;
                //*********ワークからHKASOUMEISAIにINSERT**********
                //トランザクション開始
                $this->FrmList->Do_transaction();
                $blnUpdFlg = TRUE;

                $KasouNODs2 = array();
                $ChkE12Ds = array();
                $blnExistKasouNO = FALSE;
                $i = 0;
                $strfirstKasouNo = "";
                $strKasouNo = "";

                $SQL_Excute = $this->FrmList->fncSelectFromHkasoumeisai('WK_HKASOUMEISAI_APPEND', $postData);
                if (!$SQL_Excute['result']) {
                    throw new \Exception($SQL_Excute['data']);
                } else {
                    $KasouNODs2 = $SQL_Excute['data'];
                }

                $SQL_Excute = $this->FrmList->fncSelectFromHkasoumeisai('HKASOUMEISAI', $postData);
                if (!$SQL_Excute['result']) {
                    throw new \Exception($SQL_Excute['data']);
                } else {
                    $KasouNODs1 = $SQL_Excute['data'];
                }

                $SQL_Excute = $this->FrmList->fncSelectM41E12Chk($postData);
                if (!$SQL_Excute['result']) {
                    throw new \Exception($SQL_Excute['data']);
                } else {
                    $ChkE12Ds = $SQL_Excute['data'];
                }
                //架装ﾃﾞｰﾀが存在しない場合でも印刷できるよう変更
                if (count((array) $KasouNODs2) == 0) {
                    //条件追加 M41E12にﾃﾞｰﾀが存在しない場合のみ基本情報のみで印刷可能に変更
                    if (count((array) $ChkE12Ds) == 0) {
                        //ﾜｰｸﾃｰﾌﾞﾙにとりあえず共通部分のみをINSERTする
                        //架装番号取得

                        $SQL_Excute = $this->fncUpdSaiban1();
                        if (!$SQL_Excute['result']) {
                            throw new \Exception($SQL_Excute['data']);
                        } else {
                            $strfirstKasouNo = $SQL_Excute['fncUpdSaiban'];
                        }
                        if ($strfirstKasouNo == "99999999999") {
                            return;
                        }

                        $SQL_Excute = $this->FrmList->fncInsertNoMeisaiIns($postData, $strfirstKasouNo);
                        if (!$SQL_Excute['result']) {
                            throw new \Exception($SQL_Excute['data']);
                        } else {
                            $strfirstKasouNo = $SQL_Excute['data'];
                        }
                    } else {
                        $result['result'] = "warning";
                        $result['MsgID'] = "I0001";
                        $intState = 1;
                        $blnPrintSkipFlg = True;
                    }
                }
                //else
                if (!$blnPrintSkipFlg) {
                    //①HKASOUMEISAIをDELETE
                    $SQL_Excute = $this->FrmList->fncDeleteHKASOUMEISAI("HKASOUMEISAI", $strChumon);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }
                    //②HKASOUMEISAIにINSERT
                    $SQL_Excute = $this->FrmList->fncInsertHKASOUMEISAI($strChumon);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }
                    //②-2HKASOUMEISAIにﾃﾞｰﾀが存在しない場合

                    //③HKASOUMEISAIに架装番号を設定
                    while ($i < count((array) $KasouNODs2)) {
                        $blnExistKasouNO = FALSE;
                        for ($k = 0; $k <= count((array) $KasouNODs1) - 1; $k++) {
                            if ($this->ClsComFnc->FncNv($KasouNODs1[$k]["KASOUNO"]) == $this->ClsComFnc->FncNv($KasouNODs2[$i]["KASOUNO"])) {
                                $blnExistKasouNO = TRUE;
                                break;
                            }
                        }
                        if ($blnExistKasouNO == FALSE) {
                            //架装番号を再取得
                            $SQL_Excute = $this->fncUpdSaiban1();
                            if (!$SQL_Excute['result']) {
                                throw new \Exception($SQL_Excute['data']);
                            } else {
                                $strKasouNo = $SQL_Excute['fncUpdSaiban'];
                            }
                            if ($strKasouNo == "99999999999") {
                                return;
                            }
                            $SQL_Excute = $this->FrmList->fncUpdateKasouNOOnly($this->ClsComFnc->FncNv($KasouNODs2[$i]["KASOUNO"]), $strKasouNo, $strChumon);

                            if (!$SQL_Excute['result']) {
                                throw new \Exception($SQL_Excute['data']);
                            }

                            $result['strKasouNo'] = $strKasouNo;

                            $strKasou = $strKasouNo;

                        }
                        $i++;
                    }

                    //③-1HKASOUMEISAIに車両配送指示をUPDATE
                    $SQL_Excute = $this->FrmList->fncUpdateHaisouSiji($strHaisouSiji, $strChumon, $strKasou);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }
                    //④WK_HKASOUMEISAIをDELETE
                    $SQL_Excute = $this->FrmList->fncDeleteHKASOUMEISAI("WK_HKASOUMEISAI_APPEND", $strChumon);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }
                    //コミット
                    $this->FrmList->Do_commit();
                    $blnUpdFlg = FALSE;

                    $result['result'] = TRUE;
                    $result['data'] = '';
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['MsgID'] = "E9999";
            $result['data'] = $e->getMessage();
        }

        //トランザクション処理が終了していない場合

        if ($blnUpdFlg) {
            //ロールバック
            $this->FrmList->Do_rollback();
        }
        //2008/07/25 INS Start ログ管理
        if ($intState != 0) {
            $resultLog1 = "";
            $resultLog2 = "";
            //架装部用品のログ管理
            $resultLog1 = $this->ClsLogControl->fncLogEntry("frmList_kasou", $intState, $lngOutCntK, $strChumon, $strKasou);
            if (!$resultLog1['result']) {
                $result['resultLog1'] = $resultLog1;
            }
            //外注加工依頼書のログ管理
            $resultLog2 = $this->ClsLogControl->fncLogEntry("frmList_gaichu", $intState, $lngOutCntG, $strChumon, $strKasou);
            if (!$resultLog2['result']) {
                $result['resultLog2'] = $resultLog2;
            }
        }
        if (isset($this->FrmList->conn_orl)) {
            $this->FrmList->Do_close();
            unset($this->FrmList->conn_orl);
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処理概要：架装明細プレビュー画面表示
    //**********************************************************************
    public function cmdPrintKasouClick()
    {
        $postData = "";
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $SQL_Excute = "";
        $DB_Conn = "";
        //架装
        $objDs = array();
        //外注
        $objds2 = array();

        $objTanDs = array();
        //標準添付品カウント
        $strOptCNt = "1";
        //特別添付品カウント
        $strSpcCnt = "1";
        //明細ｶｳﾝﾄ
        $intMeiCnt = 0;
        //架装依頼先ｶｳﾝﾄ
        $intToriCnt = 0;
        //標準の定価小計
        $lngSumOTeika = 0;
        //標準の社内原価小計
        $lngSumOGenka = 0;
        //標準の社内実原価小計
        $lngSumOJitu = 0;
        //特別の定価小計
        $lngSumSTeika = 0;
        //特別の社内原価小計
        $lngSumSGenka = 0;
        //特別の社内実原価小計
        $lngSumSJitu = 0;
        //レポートｶｳﾝﾄ
        $intRptCnt = 0;
        $blnUpdFlg = FALSE;
        //2008/07/26 INS ログ管理 STart
        $lngOutCntK = 0;
        $lngOutCntG = 0;
        $intState = 0;
        $strChumon = "";
        $strKasou = "";
        //2008/07/25 INS ログ管理 End
        $DsKasouPrint = array();

        $objrpt = array();

        $path_rpxTopdf = dirname(__DIR__);

        //20140304 Add st
        $blnPrintSkipFlg = False;
        //20140304 Add ed

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
                $strChumon = $postData['strChumon'];
                $strKasou = $postData['strKasou'];
                $strHaisouSiji = $postData['strHaisouSiji'];
                $strSyadaiKata = $postData['strSyadaiKata'];
                $strCar_NO = $postData['strCar_NO'];
                $strHanbaiSyasyu = $postData['strHanbaiSyasyu'];
                $strKosyo = $postData['strKosyo'];
                $strSyasyu = $postData['strSyasyu'];
                $strKeiyakusya = $postData['strKeiyakusya'];
                $strBusyoNM = $postData['strBusyoNM'];
                $strSyainNM = $postData['strSyainNM'];
                $strSyasyu_NM = $postData['strSyasyu_NM'];
                $CustomerData = $postData['CustomerData'];

                $this->FrmList = new FrmList();
                //DB接続
                $DB_Conn = $this->FrmList->Do_conn();
                if (!$DB_Conn['result']) {
                    throw new \Exception($DB_Conn['data']);
                }

                $intState = 9;
                //*********ワークからHKASOUMEISAIにINSERT**********
                //トランザクション開始
                $this->FrmList->Do_transaction();
                $blnUpdFlg = TRUE;

                $asouNODs1 = array();
                $KasouNODs2 = array();
                $ChkE12Ds = array();
                $blnExistKasouNO = FALSE;
                $i = 0;
                $strfirstKasouNo = "";
                $strKasouNo = "";

                $SQL_Excute = $this->FrmList->fncSelectFromHkasoumeisai('WK_HKASOUMEISAI_APPEND', $postData);
                if (!$SQL_Excute['result']) {
                    throw new \Exception($SQL_Excute['data']);
                } else {
                    $KasouNODs2 = $SQL_Excute['data'];
                }

                $SQL_Excute = $this->FrmList->fncSelectFromHkasoumeisai('HKASOUMEISAI', $postData);
                if (!$SQL_Excute['result']) {
                    throw new \Exception($SQL_Excute['data']);
                } else {
                    $KasouNODs1 = $SQL_Excute['data'];
                }

                $SQL_Excute = $this->FrmList->fncSelectM41E12Chk($postData);
                if (!$SQL_Excute['result']) {
                    throw new \Exception($SQL_Excute['data']);
                } else {
                    $ChkE12Ds = $SQL_Excute['data'];
                }
                //架装ﾃﾞｰﾀが存在しない場合でも印刷できるよう変更
                if (count((array) $KasouNODs2) == 0) {
                    //2006/06/07 ADD Start 条件追加 M41E12にﾃﾞｰﾀが存在しない場合のみ基本情報のみで印刷可能に変更
                    if (count((array) $ChkE12Ds) == 0) {
                        //ﾜｰｸﾃｰﾌﾞﾙにとりあえず共通部分のみをINSERTする
                        //架装番号取得

                        $SQL_Excute = $this->fncUpdSaiban1();
                        if (!$SQL_Excute['result']) {
                            throw new \Exception($SQL_Excute['data']);
                        } else {
                            $strfirstKasouNo = $SQL_Excute['fncUpdSaiban'];
                        }
                        if ($strfirstKasouNo == "99999999999") {
                            return;
                        }

                        $SQL_Excute = $this->FrmList->fncInsertNoMeisaiIns($postData, $strfirstKasouNo);
                        if (!$SQL_Excute['result']) {
                            throw new \Exception($SQL_Excute['data']);
                        } else {
                            $strfirstKasouNo = $SQL_Excute['data'];
                        }
                    } else {
                        $result['result'] = "warning";
                        $result['MsgID'] = "I0001";
                        $intState = 1;
                        //20140304 Add st
                        $blnPrintSkipFlg = True;
                        //20140304 Add ed
                    }
                }
                //else
                //20140304 Add st
                if (!$blnPrintSkipFlg) {
                    //20140304 Add ed
                    //①HKASOUMEISAIをDELETE
                    $SQL_Excute = $this->FrmList->fncDeleteHKASOUMEISAI("HKASOUMEISAI", $strChumon);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }
                    //②HKASOUMEISAIにINSERT
                    $SQL_Excute = $this->FrmList->fncInsertHKASOUMEISAI($strChumon);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }
                    //②-2HKASOUMEISAIにﾃﾞｰﾀが存在しない場合

                    //③HKASOUMEISAIに架装番号を設定
                    while ($i < count((array) $KasouNODs2)) {
                        $blnExistKasouNO = FALSE;
                        for ($k = 0; $k <= count((array) $KasouNODs1) - 1; $k++) {
                            if ($this->ClsComFnc->FncNv($KasouNODs1[$k]["KASOUNO"]) == $this->ClsComFnc->FncNv($KasouNODs2[$i]["KASOUNO"])) {
                                $blnExistKasouNO = TRUE;
                                break;
                            }
                        }
                        if ($blnExistKasouNO == FALSE) {
                            //架装番号を再取得
                            $SQL_Excute = $this->fncUpdSaiban1();
                            if (!$SQL_Excute['result']) {
                                throw new \Exception($SQL_Excute['data']);
                            } else {
                                $strKasouNo = $SQL_Excute['fncUpdSaiban'];
                            }
                            if ($strKasouNo == "99999999999") {
                                return;
                            }
                            $SQL_Excute = $this->FrmList->fncUpdateKasouNOOnly($this->ClsComFnc->FncNv($KasouNODs2[$i]["KASOUNO"]), $strKasouNo, $strChumon);

                            if (!$SQL_Excute['result']) {
                                throw new \Exception($SQL_Excute['data']);
                            }

                            $result['strKasouNo'] = $strKasouNo;
                            //20140304 Add st
                            $strKasou = $strKasouNo;
                            //20140304 Add ed
                        }
                        $i++;
                    }

                    //③-1HKASOUMEISAIに車両配送指示をUPDATE
                    $SQL_Excute = $this->FrmList->fncUpdateHaisouSiji($strHaisouSiji, $strChumon, $strKasou);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }
                    //④WK_HKASOUMEISAIをDELETE
                    $SQL_Excute = $this->FrmList->fncDeleteHKASOUMEISAI("WK_HKASOUMEISAI_APPEND", $strChumon);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }

                    //20180521 YIN INS S
                    //HKASOUMEISAI_PRINTLOGをcheck
                    $SQL_Excute = $this->FrmList->fncCheckHKASOUMEISAI_PRINTLOG($strChumon, $strKasou);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }
                    if ($SQL_Excute['row'] == 0) {
                        $SQL_Excute = $this->FrmList->fncInsertHKASOUMEISAI_PRINTLOG($strChumon, $strKasou);
                        if (!$SQL_Excute['result']) {
                            throw new \Exception($SQL_Excute['data']);
                        }
                    }
                    //20180521 YIN INS E

                    //コミット
                    $this->FrmList->Do_commit();
                    $blnUpdFlg = FALSE;

                    //20151215 Delete Start
                    //-----リフレッシュ処理----
                    //$RefreshSql = array();
                    //$RefreshSql[0] = "BEGIN dbms_snapshot.refresh('HKASOUMEISAI','f'); END;";
                    //$this -> ClsComDoRefresh -> DoRefresh($RefreshSql);
                    //20151215 Delete End

                    //バッチﾌｧｲﾙ起動

                    //********************印刷処理********************
                    //架装データをデータセットに格納
                    $SQL_Excute = $this->FrmList->fncKasouMPrintSel($strChumon, $strKasou);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    } else {
                        $objDs = $SQL_Excute['data'];
                    }

                    //外注データをデータセットに格納
                    $SQL_Excute = $this->FrmList->fncGaichuPrintSelect($strChumon, $strKasou);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    } else {
                        $objds2 = $SQL_Excute['data'];
                    }

                    //データ存在チェック
                    if (count((array) $objDs) == 0 && count((array) $objds2) == 0 && count($CustomerData) == 0) {
                        $result['result'] = "warning";
                        $result['MsgID'] = "I0001";
                        $intState = 1;
                        $this->fncReturn($result);
                    }

                    //架装データが存在している場合
                    if (count((array) $objDs) > 0) {
                        //レポートｶｳﾝﾄに1を設定
                        $intRptCnt = 1;
                        //印刷担当者を取得する
                        $SQL_Excute = $this->FrmList->fncHPRINTTANTO();
                        if (!$SQL_Excute['result']) {
                            throw new \Exception($SQL_Excute['data']);
                        } else {
                            $objTanDs = $SQL_Excute['data'];
                        }
                        if (count((array) $objTanDs) > 0) {
                            $this->DsKasouPrintTbl[0]['HAKKOUNIN'] = $this->ClsComFnc->FncNv($objTanDs[0]["TANTO_SEI"]);
                        }
                        //取引先を取得する

                        while ($intToriCnt < count($CustomerData) && $intToriCnt < 6) {
                            $this->DsKasouPrintTbl[0]["TORIHIKI_" . ($intToriCnt + 1)] = $CustomerData[$intToriCnt]['GYOUSYA_NM'];
                            $intToriCnt += 1;
                        }

                        $this->DsKasouPrintTbl[0]['HAKKOUBI'] = date('Y/m/d');
                        $this->DsKasouPrintTbl[0]['CMNNO'] = $this->ClsComFnc->FncNv($objDs[0]["CMN_NO"]);
                        $this->DsKasouPrintTbl[0]["SIYOSYA_KN"] = $this->ClsComFnc->FncNv($objDs[0]["KEIYAKUSYA"]);
                        $this->DsKasouPrintTbl[0]["BUSYOMEI"] = $this->ClsComFnc->FncNv($objDs[0]["BUSYOMEI"]);
                        $this->DsKasouPrintTbl[0]["SYAINMEI"] = $this->ClsComFnc->FncNv($objDs[0]["SYAIN"]);
                        $this->DsKasouPrintTbl[0]["SYADAIKATA"] = $this->ClsComFnc->FncNv($objDs[0]["SYADAIKATA"]);
                        //20141009 zhangxl update start
                        // $this -> DsKasouPrintTbl[0]["CARNO"] = $this -> ClsComFnc -> FncNv($objDs[0]["CAR_NO"]);
                        if ($this->ClsComFnc->FncNv($objDs[0]["CAR_NO"]) == "" || $this->ClsComFnc->FncNv($objDs[0]["SYADAIKATA"]) == "") {
                            $SQL_Excute = $this->FrmList->fncUPDKASO($strChumon, $strKasou, $strCar_NO, $strSyadaiKata);

                            if (!$SQL_Excute['result']) {
                                throw new \Exception($SQL_Excute['data']);
                            }
                        } else {
                            $strCar_NO = $this->ClsComFnc->FncNv($objDs[0]["CAR_NO"]);
                        }

                        $this->DsKasouPrintTbl[0]["CARNO"] = $strCar_NO;
                        //20141009 zhangxl update end
                        $this->DsKasouPrintTbl[0]["SYASYU_NM"] = $this->ClsComFnc->FncNv($objDs[0]["SYASYU_NM"]);
                        $this->DsKasouPrintTbl[0]["HANBAISYASYU"] = $this->ClsComFnc->FncNv($objDs[0]["HANBAISYASYU"]);
                        $this->DsKasouPrintTbl[0]["MEMO"] = $this->ClsComFnc->FncNv($objDs[0]["MEMO"]);
                        $this->DsKasouPrintTbl[0]["KASOUNO"] = $this->ClsComFnc->FncNv($objDs[0]["KASOUNO"]);
                        $this->DsKasouPrintTbl[0]["TEIKAGOUKEI"] = $this->ClsComFnc->FncNv($objDs[0]["GOUKEI"]);

                        //明細データが存在する間繰り返す
                        while ($intMeiCnt < count((array) $objDs)) {
                            //付属品区分　0:標準　1:特別
                            $Fuzokuhinkbn = "";
                            $Fuzokuhinkbn = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["FUZOKUHINKBN"]);
                            switch ($Fuzokuhinkbn) {
                                case '0':
                                    if ((int) $strOptCNt < 13) {
                                        $this->DsKasouPrintTbl[0]["OMEDALCD_" . $strOptCNt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["MEDALCD"]);
                                        $this->DsKasouPrintTbl[0]["OBUHINNM_" . $strOptCNt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["BUHINNM"]);
                                        $this->DsKasouPrintTbl[0]["OBIKOU_" . $strOptCNt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["BIKOU"]);
                                        $this->DsKasouPrintTbl[0]["OSURYO_" . $strOptCNt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["SUURYOU"]);
                                        $this->DsKasouPrintTbl[0]["OTEIKA_" . $strOptCNt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["TEIKA"]);
                                        $this->DsKasouPrintTbl[0]["OGENKA_" . $strOptCNt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_GEN"]);
                                        $this->DsKasouPrintTbl[0]["OJITUGEN_" . $strOptCNt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_ZITU"]);
                                        $lngSumOTeika += $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["TEIKA"]);
                                        $lngSumOGenka += $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_GEN"]);
                                        $lngSumOJitu += $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_ZITU"]);
                                        $strOptCNt = (int) $strOptCNt + 1;
                                    }
                                    break;
                                case '1':
                                    //20140307 Y0010 EDIT St
                                    //if ((int)($strOptCNt) < 27)
                                    if ((int) $strSpcCnt < 27)
                                    //20140307 Y0010 EDIT Ed
                                    {
                                        $this->DsKasouPrintTbl[0]["SMEDALCD_" . $strSpcCnt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["MEDALCD"]);
                                        $this->DsKasouPrintTbl[0]["SBUHINNM_" . $strSpcCnt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["BUHINNM"]);
                                        $this->DsKasouPrintTbl[0]["SBIKOU_" . $strSpcCnt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["BIKOU"]);
                                        $this->DsKasouPrintTbl[0]["SSURYO_" . $strSpcCnt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["SUURYOU"]);
                                        $this->DsKasouPrintTbl[0]["STEIKA_" . $strSpcCnt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["TEIKA"]);
                                        $this->DsKasouPrintTbl[0]["SGENKA_" . $strSpcCnt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_GEN"]);
                                        $this->DsKasouPrintTbl[0]["SJITUGEN_" . $strSpcCnt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_ZITU"]);
                                        $lngSumSTeika += $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["TEIKA"]);
                                        $lngSumSGenka += $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_GEN"]);
                                        $lngSumSJitu += $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_ZITU"]);
                                        $strSpcCnt = (int) $strSpcCnt + 1;
                                    }
                                    break;
                                default:
                                    break;
                            }
                            $intMeiCnt += 1;
                        }

                        //標準の小計
                        $this->DsKasouPrintTbl[0]["OTEIKAKEI"] = $lngSumOTeika;
                        $this->DsKasouPrintTbl[0]["OGENKAKEI"] = $lngSumOGenka;
                        $this->DsKasouPrintTbl[0]["OJITUGENKEI"] = $lngSumOJitu;
                        //特別の小計
                        $this->DsKasouPrintTbl[0]["STEIKAKEI"] = $lngSumSTeika;
                        $this->DsKasouPrintTbl[0]["SGENKAKEI"] = $lngSumSGenka;
                        $this->DsKasouPrintTbl[0]["SJITUGENKEI"] = $lngSumSJitu;
                        //合計
                        $this->DsKasouPrintTbl[0]["GENKAGOUKEI"] = $lngSumOGenka + $lngSumSGenka;
                        $this->DsKasouPrintTbl[0]["JITUGOUKEI"] = $lngSumOJitu + $lngSumSJitu;
                    } else {
                        //レポートｶｳﾝﾄに1を設定
                        $intRptCnt = 1;
                        //印刷担当者を取得する
                        $SQL_Excute = $this->FrmList->fncHPRINTTANTO();
                        if (!$SQL_Excute['result']) {
                            throw new \Exception($SQL_Excute['data']);
                        } else {
                            $objTanDs = $SQL_Excute['data'];
                        }
                        if (count((array) $objTanDs) > 0) {
                            $this->DsKasouPrintTbl[0]['HAKKOUNIN'] = $this->ClsComFnc->FncNv($objTanDs[0]["TANTO_SEI"]);
                        }
                        //取引先を取得する
                        while ($intToriCnt < count($CustomerData) && $intToriCnt < 6) {
                            $this->DsKasouPrintTbl[0]["TORIHIKI_" . ($intToriCnt + 1)] = $CustomerData[$intToriCnt]['GYOUSYA_NM'];
                            $intToriCnt += 1;
                        }
                        $this->DsKasouPrintTbl[0]['HAKKOUBI'] = date('Y/m/d');
                        $this->DsKasouPrintTbl[0]["CMNNO"] = $strChumon;
                        $this->DsKasouPrintTbl[0]["SIYOSYA_KN"] = $strKeiyakusya;
                        $this->DsKasouPrintTbl[0]["BUSYOMEI"] = $strBusyoNM;
                        $this->DsKasouPrintTbl[0]["SYAINMEI"] = $strSyainNM;
                        $this->DsKasouPrintTbl[0]["SYADAIKATA"] = $strSyadaiKata;
                        $this->DsKasouPrintTbl[0]["CARNO"] = $strCar_NO;
                        $this->DsKasouPrintTbl[0]["SYASYU_NM"] = $strSyasyu_NM;
                        $this->DsKasouPrintTbl[0]["HANBAISYASYU"] = $strHanbaiSyasyu;
                        $this->DsKasouPrintTbl[0]["MEMO"] = $strHaisouSiji;
                        $this->DsKasouPrintTbl[0]["KASOUNO"] = $strKasou;

                    }

                    //20140924 zhangxl insert start
                    $SQL_Excute = $this->FrmList->fncM27A02($this->DsKasouPrintTbl[0]['CMNNO']);
                    if (!$SQL_Excute['result']) {
                        throw new \Exception($SQL_Excute['data']);
                    }

                    if (count((array) $SQL_Excute['data']) > 0) {
                        if ($SQL_Excute['data'][0]["HIKI_ODR_DT"] != "") {
                            $this->DsKasouPrintTbl[0]["HIKI_ODR_DT"] = substr($SQL_Excute['data'][0]["HIKI_ODR_DT"], 4, 2) . "／" . substr($SQL_Excute['data'][0]["HIKI_ODR_DT"], 6, 2);
                        } else {
                            $this->DsKasouPrintTbl[0]["HIKI_ODR_DT"] = "  ／  ";
                        }

                        $system_dt = date("Ymd");
                        $pro_dt = $SQL_Excute['data'][0]["PRO_DT"];

                        if (($pro_dt != "") && ($pro_dt <= $system_dt) && ($this->DsKasouPrintTbl[0]["CARNO"] != "")) {
                            $this->DsKasouPrintTbl[0]["PRO_DT"] = "  ／  ";
                        } else
                            //20140929 修正 start
                            //if ($pro_dt > date("Ymd", strtotime('+1 day')))
                            if ($pro_dt > date("Ymd"))
                            //20140929 修正 end
                            {
                                $this->DsKasouPrintTbl[0]["PRO_DT"] = substr($SQL_Excute['data'][0]["PRO_DT"], 4, 2) . "／" . substr($SQL_Excute['data'][0]["PRO_DT"], 6, 2);
                            } else
                                if ($pro_dt == "" && $SQL_Excute['data'][0]["PRO_WEEK"] != "") {
                                    $this->DsKasouPrintTbl[0]["PRO_DT"] = substr($SQL_Excute['data'][0]["PRO_WEEK"], 4, 2) . "／" . substr($SQL_Excute['data'][0]["PRO_WEEK"], 6, 2);
                                } else {
                                    $this->DsKasouPrintTbl[0]["PRO_DT"] = "  ／  ";
                                }

                        if ($this->DsKasouPrintTbl[0]["CARNO"] == "") {
                            $this->DsKasouPrintTbl[0]["ODR_NO"] = $SQL_Excute['data'][0]["ODR_NO"];
                        } else {
                            $this->DsKasouPrintTbl[0]["ODR_NO"] = "";
                        }

                        //20141008 zhangxl 追加 start
                        if ($SQL_Excute['data'][0]["TENJI_WARI_DT"] != "") {
                            $this->DsKasouPrintTbl[0]["SHOW_DAY"] = substr($SQL_Excute['data'][0]["TENJI_WARI_DT"], 4, 2) . "／" . substr($SQL_Excute['data'][0]["TENJI_WARI_DT"], 6, 2);
                        } else {
                            $this->DsKasouPrintTbl[0]["SHOW_DAY"] = "  ／  ";
                        }

                        $this->DsKasouPrintTbl[0]["BUSYO_RYKNM"] = $SQL_Excute['data'][0]["BUSYO_RYKNM"];

                        if ($SQL_Excute['data'][0]["JUCHU_KB"] == "4") {
                            $this->DsKasouPrintTbl[0]["JUCHU_KB"] = "自契他登";
                        } else {
                            $this->DsKasouPrintTbl[0]["JUCHU_KB"] = "";
                        }
                        //20141008 zhangxl 追加 end
                    } else {
                        $this->DsKasouPrintTbl[0]["HIKI_ODR_DT"] = "  ／  ";
                        $this->DsKasouPrintTbl[0]["PRO_DT"] = "  ／  ";
                        $this->DsKasouPrintTbl[0]["ODR_NO"] = "";
                        //20141008 zhangxl 追加 start
                        $this->DsKasouPrintTbl[0]["SHOW_DAY"] = "  ／  ";
                        $this->DsKasouPrintTbl[0]["BUSYO_RYKNM"] = "";
                        $this->DsKasouPrintTbl[0]["JUCHU_KB"] = "";
                        //20141008 zhangxl 追加 end
                    }
                    //20140924 zhangxl insert end

                    //2006/04/08 UPDATE Start 架装と外注を同一ﾎﾞﾀﾝで印刷ﾌﾟﾚﾋﾞｭｰするように変更
                    switch ($intRptCnt) {
                        case '0':
                            if (count((array) $objds2) > 0) {
                                //外注部用品注文書
                                $lngOutCntG = $this->ClsComFnc->FncNz($objds2[0]["MAI"]);
                                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

                                $rpx_file_names = array();
                                $datas = array();

                                include_once $path_rpxTopdf . '/Component/tcpdf/rptContractOut.inc';
                                $rpx_file_names["rptContractOut"] = $data_fields_rptContractOut;
                                $rpx_file_names["rptContractOut2"] = $data_fields_rptContractOut;
                                $rpx_file_names["rptContractOut3"] = $data_fields_rptContractOut;
                                $rpx_file_names["rptContractOut4"] = $data_fields_rptContractOut;
                                $tmp = array();
                                $tmp_array = array();
                                foreach ((array) $objds2 as $k => $e) {
                                    $name = $e['TORIHIKI_CD'];
                                    if (!isset($tmp_array[$name])) {
                                        $tmp_array[$name] = $e;
                                        unset($tmp_array[$name]['BUHINNM'], $tmp_array[$name]['SEIKYU']);
                                    }
                                    $tmp_array[$name]['sub_datas'][] = array(
                                        'BUHINNM' => $e['BUHINNM'],
                                        'SEIKYU' => $e['SEIKYU']
                                    );
                                }
                                $tmp["data"] = $objds2;
                                $tmp["mode"] = "2";
                                $datas["rptContractOut"] = $tmp;

                                $obj = new \rpx_to_pdf($rpx_file_names, $datas);

                                $pdfPath = $obj->to_pdf();
                                //スプレッドを表示 & 正常終了 , vb line 1408
                                $result['report'] = $pdfPath;

                                $intState = 1;
                            }
                            break;
                        case '1':
                            if (count((array) $objds2) == 0) {
                                //架装部用品注文書
                                $lngOutCntG = count($this->DsKasouPrintTbl);

                                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

                                include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder.inc';
                                $rpx_file_names["rptOutFitOrder"] = $data_fields_rptOutFitOrder;
                                //20140404 表示帳票減少対応 St
                                //									include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder2.inc';
                                //									$rpx_file_names["rptOutFitOrder2"] = $data_fields_rptOutFitOrder2;
                                //20140404 表示帳票減少対応 Ed
                                $tmp_data = array();
                                array_push($tmp_data, $this->DsKasouPrintTbl[0]);

                                $tmp = array();
                                $tmp["data"] = $tmp_data;
                                $tmp["mode"] = "0";
                                $datas["rptOutFitOrder"] = $tmp;

                                $obj = new \rpx_to_pdf($rpx_file_names, $datas);

                                $pdfPath = $obj->to_pdf();
                                //スプレッドを表示 & 正常終了 , vb line 1408
                                $result['report'] = $pdfPath;
                            } else {
                                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

                                $rpx_file_names = array();
                                $datas = array();

                                //架装部用品注文書

                                $lngOutCntG = count($this->DsKasouPrintTbl);

                                include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder.inc';
                                $rpx_file_names["rptOutFitOrder"] = $data_fields_rptOutFitOrder;

                                include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder2.inc';
                                $rpx_file_names["rptOutFitOrder2"] = $data_fields_rptOutFitOrder2;

                                $tmp_data = array();
                                array_push($tmp_data, $this->DsKasouPrintTbl[0]);

                                $tmp = array();
                                $tmp["data"] = $tmp_data;
                                $tmp["mode"] = "0";
                                $datas["rptOutFitOrder"] = $tmp;

                                //外注部用品注文書

                                //外注依頼先の件数を取得する
                                $objGaiCnt = array();
                                $Customerdata = array();
                                $Customerdata['CMN_NO'] = $strChumon;
                                $Customerdata['KASOUNO'] = $strKasou;

                                $SQL_Excute = $this->FrmList->fncCustomerSelect($Customerdata, FALSE);
                                if (!$SQL_Excute['result']) {
                                    throw new \Exception($SQL_Excute['data']);
                                }
                                $objGaiCnt = $SQL_Excute['data'];
                                $lngOutCntG = $this->ClsComFnc->FncNz(count((array) $objGaiCnt));

                                include_once $path_rpxTopdf . '/Component/tcpdf/rptContractOut.inc';
                                $rpx_file_names["rptContractOut"] = $data_fields_rptContractOut;
                                $rpx_file_names["rptContractOut2"] = $data_fields_rptContractOut;
                                $rpx_file_names["rptContractOut3"] = $data_fields_rptContractOut;
                                $rpx_file_names["rptContractOut4"] = $data_fields_rptContractOut;

                                $tmp = array();

                                $tmp_array = array();
                                foreach ((array) $objds2 as $k => $e) {
                                    $name = $e['TORIHIKI_CD'];
                                    if (!isset($tmp_array[$name])) {
                                        $tmp_array[$name] = $e;
                                        unset($tmp_array[$name]['BUHINNM'], $tmp_array[$name]['SEIKYU']);
                                    }
                                    $tmp_array[$name]['sub_datas'][] = array(
                                        'BUHINNM' => $e['BUHINNM'],
                                        'SEIKYU' => $e['SEIKYU']
                                    );
                                }
                                $objds2 = array_values($tmp_array);
                                unset($tmp_array);

                                $tmp["data"] = $objds2;
                                $tmp["mode"] = "2";
                                $datas["rptContractOut"] = $tmp;
                                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                                $pdfPath = $obj->to_pdf();
                                //スプレッドを表示 & 正常終了 , vb line 1408
                                $result['report'] = $pdfPath;

                                $intState = 1;
                            }
                            break;
                    }
                    $result['result'] = TRUE;
                    $result['data'] = '';
                    //20140304 Add st
                }
                //20140304 Add ed
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['MsgID'] = "E9999";
            $result['data'] = $e->getMessage();
        }

        //トランザクション処理が終了していない場合

        if ($blnUpdFlg) {
            //ロールバック
            $this->FrmList->Do_rollback();
        }
        //2008/07/25 INS Start ログ管理
        if ($intState != 0) {
            $resultLog1 = "";
            $resultLog2 = "";
            //架装部用品のログ管理
            $resultLog1 = $this->ClsLogControl->fncLogEntry("frmList_kasou", $intState, $lngOutCntK, $strChumon, $strKasou);
            if (!$resultLog1['result']) {
                $result['resultLog1'] = $resultLog1;
            }
            //外注加工依頼書のログ管理
            $resultLog2 = $this->ClsLogControl->fncLogEntry("frmList_gaichu", $intState, $lngOutCntG, $strChumon, $strKasou);
            if (!$resultLog2['result']) {
                $result['resultLog2'] = $resultLog2;
            }
        }
        if (isset($objDs)) {
            unset($objDs);
        }
        if (isset($objds2)) {
            unset($objds2);
        }
        if (isset($objTanDs)) {
            unset($objTanDs);
        }
        if (isset($this->FrmList->conn_orl)) {
            $this->FrmList->Do_close();
            unset($this->FrmList->conn_orl);
        }
        $this->fncReturn($result);
    }

    public function fncCustomerSelect()
    {
        /**********************************************************************
                  '処 理 名：架装依頼先を表示
                  '関 数 名：fncMoneySelect
                  '引    数：なし
                  '戻 り 値：SQL
                  '処理説明：架装依頼先を表示する
                  '**********************************************************************/
        $postData = '';
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $postData = $_POST['data']['request'];
            if (isset($postData)) {

                $this->FrmList = new FrmList();

                $result = $this->FrmList->fncCustomerSelect($postData, TRUE);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $count = count((array) $result['data']);
                if ($count < 1) {
                    $result['cRow'] = 'noData';

                } else {
                    $result['cRow'] = $count;
                }

            } else {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncMoneyKasouMeisai()
    {
        /**********************************************************************
                  '処 理 名：架装明細テーブルから原価を抽出
                  '関 数 名：fncMoneyKasouMeisai
                  '引    数：strFzkKbn   (I)付属品区分
                  '戻 り 値：SQL
                  '処理説明：架装明細テーブルから原価を抽出
                  '**********************************************************************/
        $postData = '';
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $postData = $_POST['data']['request'];
            if (isset($postData)) {
                $this->FrmList = new FrmList();

                $result0 = $this->FrmList->fncMoneyKasouMeisai($postData, "0");

                if ($result0['result'] == false) {
                    throw new \Exception($this->result0['data']);
                }

                $result1 = $this->FrmList->fncMoneyKasouMeisai($postData, "1");

                if ($result1['result'] == false) {
                    throw new \Exception($this->result1['data']);
                }
                $count0 = count((array) $result0['data']);
                $count1 = count((array) $result1['data']);
                if ($count0 < 1 && $count1 < 1) {
                    $result0['cRow'] = 'noData';
                    $result1['cRow'] = 'noData';

                } else
                    if ($count0 < 1) {
                        $result0['cRow'] = 'noData';
                        $result1['cRow'] = $count1;
                    } else
                        if ($count1 < 1) {
                            $result1['cRow'] = 'noData';
                            $result0['cRow'] = $count0;
                        } else {
                            $result1['cRow'] = $count1;
                            $result0['cRow'] = $count0;
                        }

                $result['result'] = TRUE;
                $result['cRow'] = $result0['cRow'] . "+" . $result1['cRow'];
                $result['data'] = array_merge((array) $result0['data'], (array) $result1['data']);

                // $this -> log($result, LOG_DEBUG);
            } else {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncMoneyM41E12()
    {
        /**********************************************************************
                  '処 理 名：金額を抽出
                  '関 数 名：fncMoneyM41E12
                  '引    数：strFzkKbn   (I)付属品区分
                  '戻 り 値：SQL
                  '処理説明：金額を抽出
                  '**********************************************************************/
        $postData = '';
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $postData = $_POST['data']['request'];
            if (isset($postData)) {
                $this->FrmList = new FrmList();

                $result0 = $this->FrmList->fncMoneyM41E12($postData, "0");

                if ($result0['result'] == FALSE) {
                    throw new \Exception($result0['data']);
                }

                $result1 = $this->FrmList->fncMoneyM41E12($postData, "1");

                if ($result1['result'] == FALSE) {
                    throw new \Exception($result1['data']);
                }

                $count0 = count((array) $result0['data']);
                $count1 = count((array) $result1['data']);

                if ($count0 < 1 && $count1 < 1) {
                    $result0['cRow'] = 'noData';
                    $result1['cRow'] = 'noData';
                } else
                    if ($count0 < 1) {
                        $result0['cRow'] = 'noData';
                        $result1['cRow'] = $count1;
                    } else
                        if ($count1 < 1) {
                            $result1['cRow'] = 'noData';
                            $result0['cRow'] = $count0;
                        } else {
                            $result1['cRow'] = $count1;
                            $result0['cRow'] = $count0;
                        }

                $result['result'] = TRUE;
                $result['cRow'] = $result0['cRow'] . "+" . $result1['cRow'];
                $result['data'] = array_merge((array) $result0['data'], (array) $result1['data']);
            } else {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncStandardInfoSet()
    {
        /**********************************************************************
                  '処 理 名：基本情報抽出
                  '関 数 名：fncSearchSelect
                  '引    数：なし
                  '戻 り 値：SQL
                  '処理説明：基本情報を抽出する
                  '**********************************************************************/
        $postData = "";
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {

                $NENGETU = Date("Ym");

                $this->FrmList = new FrmList();

                $result = $this->FrmList->fncSearchSelect($postData, $NENGETU);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $count = count((array) $result['data']);

                    if ($count < 1) {
                        $result['cRow'] = 'noData';

                    } else {
                        $result['cRow'] = $count;

                    }

                }

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncCopyKasouInsert()
    {
        /**********************************************************************
                  '処 理 名：架装明細テーブルに追加する(ｺﾋﾟｰ時)
                  '関 数 名：fncM41E12Check
                  '引    数：なし
                  '戻 り 値：SQL文
                  '処理説明：架装明細テーブルに追加する(ｺﾋﾟｰ時)
                  '**********************************************************************/

        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmList = new FrmList();
                $result = $this->FrmList->Do_conn();
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $this->FrmList->Do_transaction();

                $result1 = $this->FrmList->fncUpdSaibanDelWK($postData);

                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                }

                $result2 = $this->FrmList->fncCopyKasouInsert($postData);

                if (!$result2['result']) {
                    throw new \Exception($result2['data']);
                }

                $this->FrmList->Do_commit();
                $result['data'] = '';
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmList->Do_rollback();
        }

        $this->fncReturn($result);
        $this->FrmList->Do_close();
    }

    function fncUpdSaibanDelWK()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmList = new FrmList();
                $result = $this->FrmList->Do_conn();
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $this->FrmList->Do_transaction();
                $result = $this->FrmList->fncUpdSaibanDelWK($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $this->FrmList->Do_commit();

                $this->FrmList->Do_close();
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }

    //2013/11/01 fuxiaolin start
    public function fncUpdSaiban()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $blnUpdate = TRUE;
                if ($postData['blnUpdate'] == 'false') {
                    $blnUpdate = FALSE;
                }

                $strNengetu = Date("Ym");
                $fncUpdSaiban = "99999999999";
                $this->FrmList = new FrmList();
                $result = $this->FrmList->fncUpdSaiban($strNengetu);
                if (!$result['result']) {
                    $result['fncUpdSaiban'] = $fncUpdSaiban;
                    throw new \Exception($this->result['data']);
                }

                $count = count((array) $result['data']);

                if ($count < 1) {
                    $fncUpdSaiban = $strNengetu . "-" . "0001";
                    $UPD_TIME = $this->ClsComFnc->FncSqlDate($this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s"));
                    if ($blnUpdate) {
                        $result = $this->FrmList->fncUpdSaibanInsert($UPD_TIME, $strNengetu);
                        if (!$result['result']) {
                            $result['fncUpdSaiban'] = $fncUpdSaiban;
                            throw new \Exception($this->result['data']);
                        }
                    }

                } else {
                    foreach ((array) $result['data'] as $key => $value) {
                        $value = $this->ClsComFnc->FncNv($result['data'][$key]['BANGO']);
                        $value = str_pad($value, 4, '0', STR_PAD_LEFT);
                        $fncUpdSaiban = $strNengetu . "-" . $value;
                        $BANGO = $this->ClsComFnc->FncSqlNz($result['data'][$key]['BANGO']);
                    }
                    if ($blnUpdate) {
                        $UPD_TIME = $this->ClsComFnc->FncSqlDate(Date("Y/m/d H:i:s"));

                        $result = $this->FrmList->fncUpdSaibanUpdate($BANGO, $UPD_TIME, $strNengetu);

                        if (!$result['result']) {
                            $result['fncUpdSaiban'] = $fncUpdSaiban;
                            throw new \Exception($this->result['data']);
                        }
                    }

                }
                $result['fncUpdSaiban'] = $fncUpdSaiban;
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    public function fncKasouTblCheck()
    {
        /**********************************************************************
                  '処 理 名：架装明細テーブルに指定された注文書番号のﾃﾞｰﾀの存在を確認するSQL
                  '関 数 名：fncKasouTblCheck
                  '引    数：なし
                  '戻 り 値：SQL文
                  '処理説明：架装明細テーブルに指定された注文書番号のﾃﾞｰﾀの存在を確認するSQL
                  '**********************************************************************/
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmList = new FrmList();
                // 処理の呼出
                $result = $this->FrmList->fncKasouTblCheck($postData);
                if (!$result['result']) {
                    throw new \Exception($this->result['data']);
                } else {
                    $count = count((array) $result['data']);

                    if ($count < 1) {
                        $result['cRow'] = 'noData';

                    } else {
                        $result['cRow'] = $count;
                    }

                }

            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncM41E12Check()
    {
        /**********************************************************************
                  '処 理 名：付属品明細に指定された注文書番号のﾃﾞｰﾀの存在を確認するSQL
                  '関 数 名：fncM41E12Check
                  '引    数：なし
                  '戻 り 値：SQL文
                  '処理説明：付属品明細に指定された注文書番号のﾃﾞｰﾀの存在を確認するSQL
                  '**********************************************************************/
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmList = new FrmList();
                // 処理の呼出
                $result = $this->FrmList->fncM41E12Check($postData);
                if (!$result['result']) {
                    throw new \Exception($this->result['data']);
                } else {
                    $count = count((array) $result['data']);
                    if ($count < 1) {
                        $result['cRow'] = 'noData';

                    } else {
                        $result['cRow'] = $count;
                    }

                }

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteKasou()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );
        try {

            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $blnTran = TRUE;
                $this->FrmList = new FrmList();
                $result = $this->FrmList->Do_conn();
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $this->FrmList->Do_transaction();
                //ﾜｰｸﾃｰﾌﾞﾙから削除する
                $result1 = $this->FrmList->fncDeleteKasou($postData, "WK_HKASOUMEISAI_APPEND");

                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                }

                $result2 = $this->FrmList->fncDeleteKasou($postData, "HKASOUMEISAI");
                if (!$result2['result']) {
                    throw new \Exception($result2['data']);
                }
                $this->FrmList->Do_commit();
                $blnTran = FALSE;

                $result = array(
                    'result' => TRUE,
                    'data' => 'sql success'
                );
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTran) {
            $this->FrmList->Do_rollback();
        }
        $this->FrmList->Do_close();
        $this->fncReturn($result);
    }

    public function fncSelHkasou()
    {

        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'param error'
        );
        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                // 呼出クラスのインスタンス作成
                $this->FrmList = new FrmList();
                // 20131004 kamei upd start
                // 処理の呼出
                $result = $this->FrmList->select($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {

                    $count = count((array) $result['data']);

                    if ($count < 1) {
                        $result['cRow'] = 'noData';
                    } else {
                        $result['cRow'] = $count;
                    }
                }

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);

    }

    public function deleteWKClear()
    {
        $result = "";

        try {
            $this->FrmList = new FrmList();
            //20131205 luchao delete　--既存バグ修正
            // $result1 = $this -> FrmList -> checkWKClear();
            // if (!$result1['result'])
            // {
            // throw new \Exception($result1['data']);
            // }
            //20131205 luchao delete　--既存バグ修正
            $result = $this->FrmList->deleteWKClear();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    // public function checkWKClear()
    // {
    // $postData = "";
    // $result = "";
    //
    // try
    // {
    //
    // $this -> FrmList = new FrmList();
    // // 処理の呼出
    // $result = $this -> FrmList -> checkWKClear();
    // if (!$result['result'])
    // {
    // throw new \Exception($result['data']);
    // }
    // else
    // {
    // $count = count((array) $result['data']);
    //
    // if ($count < 1)
    // {
    // $result['cRow'] = 'noData';
    // }
    // else
    // {
    // $result['cRow'] = $count;
    // }
    // }
    //
    // }
    // catch(\Exception $e)
    // {
    // $result['result'] = FALSE;
    // $result['data'] = $e -> getMessage();
    // }
    // $this -> set('result', $result);
    // $this -> render('checkwkclear');
    // }

    public function subDeleteAndInsertOfWKHKASOUMEISAI()
    {

        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'param error'
        );
        //20131106 luchao modify start
        $Do_Excute = "";
        //20131106 luchao modify end
        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];

            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                // 呼出クラスのインスタンス作成
                $this->FrmList = new FrmList();
                //①WK_HKASOUMEISAIから重複行を削除する
                $Do_Excute = $this->FrmList->fncDeleteWK_KASOUMEISAI();
                if (!$Do_Excute['result']) {
                    throw new \Exception($Do_Excute['data']);
                }
                //②WK_HKASOUMEISAIにINSERTする
                $Do_Excute = $this->FrmList->fncInsertWK_KASOUMEISAI($postData);
                if (!$Do_Excute['result']) {
                    throw new \Exception($Do_Excute['data']);
                }
                $result['result'] = true;
                $result['cRow'] = $Do_Excute['number_of_rows'];
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //20131210 LuChao 既存バグ修正 Start
    public function fncDeleteWKOther()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => ''
        );
        $Do_Excute = "";
        try {
            if (isset($_POST['data']['request'])) {
                $postData = $_POST['data']['request'];
            }
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $FrmList = new FrmList();
                $Do_Excute = $FrmList->FncDeleteWKOther($postData);
                if (!$Do_Excute['result']) {
                    throw new \Exception($Do_Excute['data']);
                }
                $result['result'] = $Do_Excute['result'];
            }
        } catch (\Exception $e) {
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //20131210 LuChao 既存バグ修正 End

}
