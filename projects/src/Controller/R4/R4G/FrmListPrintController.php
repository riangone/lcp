<?php

/**
 * 説明：
 *
 *
 * @author yangyang
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                                				 Feature/Bug     内容                    担当
 *YYYYMMDD             #ID                       XXXXXX
 * 20161107                                   #2597                                依頼                 yangyang
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmListPrint;

class FrmListPrintController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmListPrint;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;

    // var $components = array('RequestHandler');
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
        // レイアウトファイルの指定(app/View/Layouts/FrmListPrint_layout.ctpを参照)
        $this->render('index', 'FrmListPrint_layout');

    }

    public function cmdPreviewClick()
    {
        /**********************************************************************
                  '処 理 名：
                  '関 数 名：cmdPreviewClick
                  '引    数：なし
                  '戻 り 値：SQL
                  '処理説明：
                  '**********************************************************************/
        $myData = $_POST['data']['request'];

        $step1 = $this->subDeleteAndInsertOfWKHKASOUMEISAI($myData);
        if ($step1['result'] == FALSE) {
            $this->fncReturn($step1);
            return;
        }

        if ($step1['result']) {
            $step2 = $this->fncStandardInfoSet($myData);
        }

        if ($step2['result'] == FALSE) {
            $this->fncReturn($step1);
            return;
        }

        //該当データが存在しない場合は処理を抜ける
        if ($step2['cRow'] == 'noData') {
            $result['result'] = false;
            $result['MsgID'] = "I9999";
            $result['data'] = '外注伝票データがありません';
            $this->fncReturn($result);
            return;
        }

        /*	番号和架装番号数组		*/
        $postdata4 = array();
        foreach ((array) $step2['data'] as $key => $value) {
            $column = array();
            $column['CMN_NO'] = $value['CMN_NO'];
            $column['KASOU_NO'] = $value['KASOU_NO'];
            array_push($postdata4, $column);
        }

        /*	5、取出【CustomerData】架装依頼先	*/
        $step5 = $this->fncCustomerSelect($postdata4);

        if ($step5['result'] == FALSE) {
            $this->fncReturn($step1);
            return;
        }

        $Customer = array();
        for ($j = 0; $j < count((array) $step5['data']); $j++) {
            if ($step5['data'][$j]['cRow'] == 'noData') {
                $cols = array();
                for ($g = 0; $g < 7; $g++) {
                    $columns = array();
                    $columns['GYOUSYA_CD'] = '';
                    $columns['GYOUSYA_NM'] = '';
                    $columns['GAICYU_ZITU'] = '';
                    array_push($cols, $columns);
                }
                array_push($Customer, $cols);
            } else {
                $cols = array();
                $lngGaichuGK = 0;
                foreach ((array) $step5['data'][$j]['data'] as $key => $value) {
                    $columns = array();
                    $columns['GYOUSYA_CD'] = $step5['data'][$j]['data'][$key]['GYOUSYA_CD'];
                    $columns['GYOUSYA_NM'] = $step5['data'][$j]['data'][$key]['GYOUSYA_NM'];
                    $columns['GAICYU_ZITU'] = $step5['data'][$j]['data'][$key]['GAICYU_ZITU'];
                    array_push($cols, $columns);
                    //外注実原価合計を算出する
                    $lngGaichuGK = intval($lngGaichuGK) + intval($step5['data'][$j]['data'][$key]['GAICYU_ZITU']);
                }
                if (count((array) $step5['data'][$j]['data']) < 7) {
                    $lngColumns = 7 - count((array) $step5['data'][$j]['data']);
                    for ($f = 0; $f < $lngColumns; $f++) {
                        $columns = array();
                        $columns['GYOUSYA_CD'] = '';
                        $columns['GYOUSYA_NM'] = '';
                        $columns['GAICYU_ZITU'] = '';
                        array_push($cols, $columns);
                    }
                }
                array_push($Customer, $cols);
            }
        }

        /*	6、取出【車両配送指示】	*/
        $step6 = $this->fncKasouTblCheck($postdata4);

        if ($step6['result'] == FALSE) {
            $this->fncReturn($step1);
            return;
        }

        /* 	将前台数据$myData、$step2、$Customer、$step6拼成一个数组		*/
        $postData7 = array();
        for ($m = 0; $m < $step2['cRow']; $m++) {
            $arr_son = $step2['data'][$m];

            /* 	 插入$myData  */
            $arr_son['strcheck'] = $myData['strcheck'];
            $arr_son['flag'] = $myData['flag'];
            $arr_son['startDate'] = $myData['startDate'];
            $arr_son['endDate'] = $myData['endDate'];
            //20180601 YIN INS S
            $arr_son['againflag'] = $myData['againflag'];
            //20180601 YIN INS E

            /* 	 插入$Customer  */
            $arr_son['CustomerData'] = $Customer[$m];

            /* 	 插入MEMO  */
            $arr_son['MEMO'] = $step6['data'][$m]['data'][0]['MEMO'];

            array_push($postData7, $arr_son);
        }

        /*	7、打印	*/
        $step7 = $this->cmdPrintKasouClick($postData7);
        $this->fncReturn($step7);
        return;
    }

    //**********************************************************************
    //処理概要：架装明細プレビュー画面表示
    //**********************************************************************
    public function cmdPrintKasouClick($postData)
    {
        $path_rpxTopdf = dirname(__DIR__);

        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            if (isset($postData)) {
                $alldatas = array();
                //20180528 YIN INS S
                $noprint = false;
                //20180528 YIN INS E

                for ($n = 0; $n < count($postData); $n++) {
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
                    $lngOutCntK = 0;
                    $lngOutCntG = 0;
                    $intState = 0;
                    $strChumon = "";
                    $strKasou = "";
                    $DsKasouPrint = array();
                    $objrpt = array();
                    $blnPrintSkipFlg = False;

                    $strcheck = $postData[$n]['strcheck'];
                    $strflag = $postData[$n]['flag'];
                    //20180601 YIN INS S
                    $stragainflag = $postData[$n]['againflag'];
                    //20180601 YIN INS E
                    $strcboStartDate = $postData[$n]['startDate'];
                    $strcboEndDate = $postData[$n]['endDate'];
                    $strChumon = $postData[$n]['CMN_NO'];
                    $strKasou = $postData[$n]['KASOU_NO'];
                    $strHaisouSiji = $postData[$n]['MEMO'];
                    $strSyadaiKata = $postData[$n]['SDI_KAT'];
                    $strCar_NO = $postData[$n]['CAR_NO'];
                    $strHanbaiSyasyu = $postData[$n]['HANBAISYASYU'];
                    $strKosyo = rtrim(substr($strHanbaiSyasyu, 0, 5)) . rtrim(substr($strHanbaiSyasyu, 7, 1));
                    $strSyasyu = $postData[$n]['BASEH_KN'];
                    $strKeiyakusya = $postData[$n]['KEIYAKUSYA'];
                    $strBusyoNM = $postData[$n]['BUSYOMEI'];
                    $strSyainNM = $postData[$n]['SYAIN'];
                    $strSyasyu_NM = $postData[$n]['BASEH_KN'];
                    $CustomerData = $postData[$n]['CustomerData'];

                    //$this -> log('****************************************进入循环**************************************************');
                    //$this -> log($postData[$n]);

                    $this->FrmListPrint = new FrmListPrint();
                    //DB接続
                    $DB_Conn = $this->FrmListPrint->Do_conn();
                    if (!$DB_Conn['result']) {
                        throw new \Exception($DB_Conn['data']);
                    }

                    $intState = 9;
                    //*********ワークからHKASOUMEISAIにINSERT**********
                    //トランザクション開始
                    $this->FrmListPrint->Do_transaction();
                    $blnUpdFlg = TRUE;

                    /* 20161107 yangyang del s */
                    // $KasouNODs1 = array();
                    // $KasouNODs2 = array();
                    // $ChkE12Ds = array();
                    // $blnExistKasouNO = FALSE;
                    // $strfirstKasouNo = "";
                    // $strKasouNo = "";
                    // $i = 0;

                    // $SQL_Excute = $this -> FrmListPrint -> fncSelectFromHkasoumeisai('WK_HKASOUMEISAI_APPEND', $postData[$n]);
                    // if (!$SQL_Excute['result'])
                    // {
                    // throw new \Exception($SQL_Excute['data']);
                    // }
                    // else
                    // {
                    // $KasouNODs2 = $SQL_Excute['data'];
                    // }
                    //
                    // $SQL_Excute = $this -> FrmListPrint -> fncSelectFromHkasoumeisai('HKASOUMEISAI', $postData[$n]);
                    // if (!$SQL_Excute['result'])
                    // {
                    // throw new \Exception($SQL_Excute['data']);
                    // }
                    // else
                    // {
                    // $KasouNODs1 = $SQL_Excute['data'];
                    // }
                    //
                    // $SQL_Excute = $this -> FrmListPrint -> fncSelectM41E12Chk($postData[$n]);
                    // if (!$SQL_Excute['result'])
                    // {
                    // throw new \Exception($SQL_Excute['data']);
                    // }
                    // else
                    // {
                    // $ChkE12Ds = $SQL_Excute['data'];
                    // }
                    //
                    // // $this->log('KasouNODs2');
                    // // $this->log($KasouNODs2);
                    // // $this->log('KasouNODs1');
                    // // $this->log($KasouNODs1);
                    // // $this->log('ChkE12Ds');
                    // // $this->log($ChkE12Ds);
                    //
                    // //架装ﾃﾞｰﾀが存在しない場合でも印刷できるよう変更
                    // if (count($KasouNODs2) == 0)
                    // {
                    // $this -> log('11111111');
                    // //条件追加 M41E12にﾃﾞｰﾀが存在しない場合のみ基本情報のみで印刷可能に変更
                    // if (count($ChkE12Ds) == 0)
                    // {
                    // //ﾜｰｸﾃｰﾌﾞﾙにとりあえず共通部分のみをINSERTする
                    // //架装番号取得
                    //
                    // $SQL_Excute = $this -> fncUpdSaiban1();
                    // if (!$SQL_Excute['result'])
                    // {
                    // throw new \Exception($SQL_Excute['data']);
                    // }
                    // else
                    // {
                    // $strfirstKasouNo = $SQL_Excute['fncUpdSaiban'];
                    // }
                    // if ($strfirstKasouNo == "99999999999")
                    // {
                    // return;
                    // }
                    // $this -> log('strfirstKasouNo: ');
                    // $this -> log($strfirstKasouNo);
                    // $SQL_Excute = $this -> FrmListPrint -> fncInsertNoMeisaiIns($postData[$n], $strfirstKasouNo);
                    // if (!$SQL_Excute['result'])
                    // {
                    // throw new \Exception($SQL_Excute['data']);
                    // }
                    // else
                    // {
                    // $strfirstKasouNo = $SQL_Excute['data'];
                    // }
                    // }
                    // else
                    // {
                    // // $result['result'] = "warning";
                    // // $result['MsgID'] = "I0001";
                    // $intState = 1;
                    // continue;
                    // $blnPrintSkipFlg = True;
                    // }
                    // }
                    /* 20161107 yangyang del e */

                    if (!$blnPrintSkipFlg) {
                        /* 20161107 yangyang del s */
                        // $this -> log('22222222');
                        // //①HKASOUMEISAIをDELETE
                        // $SQL_Excute = $this -> FrmListPrint -> fncDeleteHKASOUMEISAI("HKASOUMEISAI", $strChumon);
                        // if (!$SQL_Excute['result'])
                        // {
                        // throw new \Exception($SQL_Excute['data']);
                        // }
                        // //②HKASOUMEISAIにINSERT
                        // $SQL_Excute = $this -> FrmListPrint -> fncInsertHKASOUMEISAI($strChumon);
                        // if (!$SQL_Excute['result'])
                        // {
                        // throw new \Exception($SQL_Excute['data']);
                        // }
                        // //②-2HKASOUMEISAIにﾃﾞｰﾀが存在しない場合
                        //
                        // //③HKASOUMEISAIに架装番号を設定
                        // while ($i < count($KasouNODs2))
                        // {
                        // $blnExistKasouNO = FALSE;
                        // for ($k = 0; $k <= count($KasouNODs1) - 1; $k++)
                        // {
                        // if ($this -> ClsComFnc -> FncNv($KasouNODs1[$k]["KASOUNO"]) == $this -> ClsComFnc -> FncNv($KasouNODs2[$i]["KASOUNO"]))
                        // {
                        // $this -> log('while循环AAAAA');
                        // $blnExistKasouNO = TRUE;
                        // break;
                        // }
                        // }
                        // if ($blnExistKasouNO == FALSE)
                        // {
                        // $this -> log('while循环BBBBB');
                        // //架装番号を再取得
                        // $SQL_Excute = $this -> fncUpdSaiban1();
                        // if (!$SQL_Excute['result'])
                        // {
                        // throw new \Exception($SQL_Excute['data']);
                        // }
                        // else
                        // {
                        // $strKasouNo = $SQL_Excute['fncUpdSaiban'];
                        // }
                        // if ($strKasouNo == "99999999999")
                        // {
                        // return;
                        // }
                        // $SQL_Excute = $this -> FrmListPrint -> fncUpdateKasouNOOnly($this -> ClsComFnc -> FncNv($KasouNODs2[$i]["KASOUNO"]), $strKasouNo, $strChumon);
                        //
                        // if (!$SQL_Excute['result'])
                        // {
                        // throw new \Exception($SQL_Excute['data']);
                        // }
                        //
                        // $result['strKasouNo'] = $strKasouNo;
                        // $strKasou = $strKasouNo;
                        // }
                        // $i++;
                        // }
                        /*20161107 yangyang del e */

                        //③-1HKASOUMEISAIに車両配送指示をUPDATE
                        // $SQL_Excute = $this -> FrmListPrint -> fncUpdateHaisouSiji($strHaisouSiji, $strChumon, $strKasou);
                        // if (!$SQL_Excute['result'])
                        // {
                        // throw new \Exception($SQL_Excute['data']);
                        // }
                        //20180530 YIN INS S
                        //HKASOUMEISAI_PRINTLOGをcheck
                        $printLog = false;
                        $SQL_Excute = $this->FrmListPrint->fncCheckHKASOUMEISAI_PRINTLOG($strChumon, $strKasou);
                        if (!$SQL_Excute['result']) {
                            throw new \Exception($SQL_Excute['data']);
                        }
                        if ($SQL_Excute['row'] == 0) {
                            //20180601 YIN INS S
                            if ($stragainflag == 'false') {
                                //20180601 YIN INS E
                                $SQL_Excute = $this->FrmListPrint->fncInsertHKASOUMEISAI_PRINTLOG($strChumon, $strKasou);
                                if (!$SQL_Excute['result']) {
                                    throw new \Exception($SQL_Excute['data']);
                                }
                                //20180601 YIN INS S
                            }
                            //20180601 YIN INS E
                            $printLog = true;
                        }
                        //20180530 YIN INS E
                        //20180601 YIN INS S
                        else {
                            if ($stragainflag == 'true') {
                                //20180604 YIN INS S
                                if ($strflag == 1) {
                                    //20180604 YIN INS E
                                    if ($SQL_Excute['data'][0]['CREATE_DATE'] >= $strcboStartDate && $SQL_Excute['data'][0]['CREATE_DATE'] <= $strcboEndDate) {
                                        $printLog = true;
                                    }
                                    //20180604 YIN INS S
                                } else {
                                    $printLog = true;
                                }
                                //20180604 YIN INS E
                            }
                        }
                        //20180601 YIN INS E

                        //コミット
                        $this->FrmListPrint->Do_commit();
                        $blnUpdFlg = FALSE;

                        //バッチﾌｧｲﾙ起動
                        //********************印刷処理********************
                        //20180528 YIN INS S
                        if ($printLog) {
                            //20180528 YIN INS E
                            //架装データをデータセットに格納
                            $SQL_Excute = $this->FrmListPrint->fncKasouMPrintSel($strChumon, $strKasou);
                            if (!$SQL_Excute['result']) {
                                throw new \Exception($SQL_Excute['data']);
                            } else {
                                $objDs = $SQL_Excute['data'];
                            }

                            //外注データをデータセットに格納
                            $SQL_Excute = $this->FrmListPrint->fncGaichuPrintSelect($strChumon, $strKasou);
                            if (!$SQL_Excute['result']) {
                                throw new \Exception($SQL_Excute['data']);
                            } else {
                                $objds2 = $SQL_Excute['data'];
                            }

                            // $this->log('objDs');
                            // $this->log($objDs);
                            // $this->log('objds2');
                            // $this->log($objds2);

                            //データ存在チェック
                            if (count((array) $objDs) == 0 && count((array) $objds2) == 0 && count($CustomerData) == 0) {
                                //$this -> log('22222222 00000000');
                                // $result['result'] = "warning";
                                // $result['MsgID'] = "I0001";
                                // $this -> set('result', $result);
                                // $this -> render('cmdprintkasouclick');
                                $intState = 1;
                                continue;
                            }

                            //架装データが存在している場合
                            if (count((array) $objDs) > 0) {
                                //$this -> log('22222222 11111111');
                                //レポートｶｳﾝﾄに1を設定
                                $intRptCnt = 1;
                                $intToriCnt = 0;

                                //印刷担当者を取得する
                                $SQL_Excute = $this->FrmListPrint->fncHPRINTTANTO();
                                if (!$SQL_Excute['result']) {
                                    throw new \Exception($SQL_Excute['data']);
                                } else {
                                    $objTanDs = $SQL_Excute['data'];
                                }

                                if (count((array) $objTanDs) > 0) {
                                    $this->DsKasouPrintTbl[0]['HAKKOUNIN'] = $this->ClsComFnc->FncNv($objTanDs[0]["TANTO_SEI"]);
                                    //		【発行】
                                }

                                //取引先を取得する
                                while ($intToriCnt < count($CustomerData) && $intToriCnt < 6) {
                                    $this->DsKasouPrintTbl[0]["TORIHIKI_" . ($intToriCnt + 1)] = $CustomerData[$intToriCnt]['GYOUSYA_NM'];
                                    //		【架装依頼先】
                                    $intToriCnt += 1;
                                }

                                $this->DsKasouPrintTbl[0]['HAKKOUBI'] = date('Y/m/d');
                                //		【発行日付】
                                $this->DsKasouPrintTbl[0]['CMNNO'] = $this->ClsComFnc->FncNv($objDs[0]["CMN_NO"]);
                                //		【注文書NO】
                                $this->DsKasouPrintTbl[0]["SIYOSYA_KN"] = $this->ClsComFnc->FncNv($objDs[0]["KEIYAKUSYA"]);
                                //		【様】
                                $this->DsKasouPrintTbl[0]["BUSYOMEI"] = $this->ClsComFnc->FncNv($objDs[0]["BUSYOMEI"]);
                                //		【部署】
                                $this->DsKasouPrintTbl[0]["SYAINMEI"] = $this->ClsComFnc->FncNv($objDs[0]["SYAIN"]);
                                //		【担当者】
                                $this->DsKasouPrintTbl[0]["SYADAIKATA"] = $this->ClsComFnc->FncNv($objDs[0]["SYADAIKATA"]);
                                //		【型式】

                                /* 20161108 yangyang del s */
                                // if ($this -> ClsComFnc -> FncNv($objDs[0]["CAR_NO"]) == "" || $this -> ClsComFnc -> FncNv($objDs[0]["SYADAIKATA"]) == "")
                                // {
                                // $SQL_Excute = $this -> FrmListPrint -> fncUPDKASO($strChumon, $strKasou, $strCar_NO, $strSyadaiKata);
                                //
                                // if (!$SQL_Excute['result'])
                                // {
                                // throw new \Exception($SQL_Excute['data']);
                                // }
                                // }
                                // else
                                // {
                                // $strCar_NO = $this -> ClsComFnc -> FncNv($objDs[0]["CAR_NO"]);
                                // }
                                /* 20161108 yangyang del e */

                                /* 20161108 yangyang add s */
                                $strCar_NO = $this->ClsComFnc->FncNv($objDs[0]["CAR_NO"]);
                                /* 20161108 yangyang add e */

                                $this->DsKasouPrintTbl[0]["CARNO"] = $strCar_NO;
                                //		【車台№】
                                $this->DsKasouPrintTbl[0]["SYASYU_NM"] = $this->ClsComFnc->FncNv($objDs[0]["SYASYU_NM"]);
                                //		【架装依頼先】
                                $this->DsKasouPrintTbl[0]["HANBAISYASYU"] = $this->ClsComFnc->FncNv($objDs[0]["HANBAISYASYU"]);
                                //		【架装依頼先】
                                $this->DsKasouPrintTbl[0]["MEMO"] = $this->ClsComFnc->FncNv($objDs[0]["MEMO"]);
                                //		【車両配送指示】
                                $this->DsKasouPrintTbl[0]["KASOUNO"] = $this->ClsComFnc->FncNv($objDs[0]["KASOUNO"]);
                                //		【伝票NO】
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
                                                //		【標準添付品】——【使用部品名】
                                                $this->DsKasouPrintTbl[0]["OBUHINNM_" . $strOptCNt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["BUHINNM"]);
                                                //		【標準添付品】——【使用部品名】
                                                $this->DsKasouPrintTbl[0]["OBIKOU_" . $strOptCNt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["BIKOU"]);
                                                //		【標準添付品】——【数量】
                                                $this->DsKasouPrintTbl[0]["OSURYO_" . $strOptCNt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["SUURYOU"]);
                                                //		【標準添付品】——【数量】
                                                $this->DsKasouPrintTbl[0]["OTEIKA_" . $strOptCNt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["TEIKA"]);
                                                //		【標準添付品】——【定価】
                                                $this->DsKasouPrintTbl[0]["OGENKA_" . $strOptCNt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_GEN"]);
                                                //		【標準添付品】——【定価】
                                                $this->DsKasouPrintTbl[0]["OJITUGEN_" . $strOptCNt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_ZITU"]);
                                                //		【標準添付品】——【定価】
                                                $lngSumOTeika += $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["TEIKA"]);
                                                $lngSumOGenka += $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_GEN"]);
                                                $lngSumOJitu += $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_ZITU"]);
                                                $strOptCNt = (int) $strOptCNt + 1;
                                            }
                                            break;
                                        case '1':
                                            if ((int) $strSpcCnt < 27) {
                                                $this->DsKasouPrintTbl[0]["SMEDALCD_" . $strSpcCnt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["MEDALCD"]);
                                                //		【特別仕様品】——【使用部品名】
                                                $this->DsKasouPrintTbl[0]["SBUHINNM_" . $strSpcCnt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["BUHINNM"]);
                                                //		【特別仕様品】——【使用部品名】
                                                $this->DsKasouPrintTbl[0]["SBIKOU_" . $strSpcCnt] = $this->ClsComFnc->FncNv($objDs[$intMeiCnt]["BIKOU"]);
                                                //		【特別仕様品】——【備考】
                                                $this->DsKasouPrintTbl[0]["SSURYO_" . $strSpcCnt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["SUURYOU"]);
                                                //		【特別仕様品】——【数量】
                                                $this->DsKasouPrintTbl[0]["STEIKA_" . $strSpcCnt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["TEIKA"]);
                                                //		【特別仕様品】——【定価】
                                                $this->DsKasouPrintTbl[0]["SGENKA_" . $strSpcCnt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_GEN"]);
                                                //		【特別仕様品】——【定価】
                                                $this->DsKasouPrintTbl[0]["SJITUGEN_" . $strSpcCnt] = $this->ClsComFnc->FncNz($objDs[$intMeiCnt]["BUHIN_SYANAI_ZITU"]);
                                                //		【特別仕様品】——【定価】
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
                                //		【標準添付品】——【小計】
                                $this->DsKasouPrintTbl[0]["OGENKAKEI"] = $lngSumOGenka;
                                //		【標準添付品】——【小計】
                                $this->DsKasouPrintTbl[0]["OJITUGENKEI"] = $lngSumOJitu;
                                //		【標準添付品】——【小計】
                                //特別の小計
                                $this->DsKasouPrintTbl[0]["STEIKAKEI"] = $lngSumSTeika;
                                //		【特別仕様品】——【小計】
                                $this->DsKasouPrintTbl[0]["SGENKAKEI"] = $lngSumSGenka;
                                //		【特別仕様品】——【小計】
                                $this->DsKasouPrintTbl[0]["SJITUGENKEI"] = $lngSumSJitu;
                                //		【特別仕様品】——【小計】
                                //合計
                                $this->DsKasouPrintTbl[0]["GENKAGOUKEI"] = $lngSumOGenka + $lngSumSGenka;
                                $this->DsKasouPrintTbl[0]["JITUGOUKEI"] = $lngSumOJitu + $lngSumSJitu;
                            } else {
                                //$this -> log('22222222 22222222');
                                //レポートｶｳﾝﾄに1を設定
                                $intRptCnt = 1;
                                $intToriCnt = 0;

                                //印刷担当者を取得する
                                $SQL_Excute = $this->FrmListPrint->fncHPRINTTANTO();
                                if (!$SQL_Excute['result']) {
                                    throw new \Exception($SQL_Excute['data']);
                                } else {
                                    $objTanDs = $SQL_Excute['data'];
                                }
                                if (count((array) $objTanDs) > 0) {
                                    $this->DsKasouPrintTbl[0]['HAKKOUNIN'] = $this->ClsComFnc->FncNv($objTanDs[0]["TANTO_SEI"]);
                                    //		【発行】
                                }

                                //取引先を取得する
                                while ($intToriCnt < count($CustomerData) && $intToriCnt < 6) {
                                    $this->DsKasouPrintTbl[0]["TORIHIKI_" . ($intToriCnt + 1)] = $CustomerData[$intToriCnt]['GYOUSYA_NM'];
                                    //		【架装依頼先】
                                    $intToriCnt += 1;
                                }

                                $this->DsKasouPrintTbl[0]['HAKKOUBI'] = date('Y/m/d');
                                //		【発行日付】
                                $this->DsKasouPrintTbl[0]["CMNNO"] = $strChumon;
                                //		【注文書NO】
                                $this->DsKasouPrintTbl[0]["SIYOSYA_KN"] = $strKeiyakusya;
                                //		【様】
                                $this->DsKasouPrintTbl[0]["BUSYOMEI"] = $strBusyoNM;
                                //		【部署】
                                $this->DsKasouPrintTbl[0]["SYAINMEI"] = $strSyainNM;
                                //		【担当者】
                                $this->DsKasouPrintTbl[0]["SYADAIKATA"] = $strSyadaiKata;
                                //		【型式】
                                $this->DsKasouPrintTbl[0]["CARNO"] = $strCar_NO;
                                //		【車台№】
                                $this->DsKasouPrintTbl[0]["SYASYU_NM"] = $strSyasyu_NM;
                                //		【架装依頼先】
                                $this->DsKasouPrintTbl[0]["HANBAISYASYU"] = $strHanbaiSyasyu;
                                //		【架装依頼先】
                                $this->DsKasouPrintTbl[0]["MEMO"] = $strHaisouSiji;
                                //		【車両配送指示】
                                $this->DsKasouPrintTbl[0]["KASOUNO"] = $strKasou;
                                //		【伝票NO】

                            }

                            $SQL_Excute = $this->FrmListPrint->fncM27A02($this->DsKasouPrintTbl[0]['CMNNO']);
                            if (!$SQL_Excute['result']) {
                                throw new \Exception($SQL_Excute['data']);
                            }

                            if (count((array) $SQL_Excute['data']) > 0) {
                                if ($SQL_Excute['data'][0]["HIKI_ODR_DT"] != "") {
                                    $this->DsKasouPrintTbl[0]["HIKI_ODR_DT"] = substr($SQL_Excute['data'][0]["HIKI_ODR_DT"], 4, 2) . "／" . substr($SQL_Excute['data'][0]["HIKI_ODR_DT"], 6, 2);
                                    //		【オーダー日】
                                } else {
                                    $this->DsKasouPrintTbl[0]["HIKI_ODR_DT"] = "  ／  ";
                                    //		【オーダー日】
                                }

                                $system_dt = date("Ymd");
                                $pro_dt = $SQL_Excute['data'][0]["PRO_DT"];

                                if (($pro_dt != "") && ($pro_dt <= $system_dt) && ($this->DsKasouPrintTbl[0]["CARNO"] != "")) {
                                    $this->DsKasouPrintTbl[0]["PRO_DT"] = "  ／  ";
                                    //		【生産日】
                                } else
                                    if ($pro_dt > date("Ymd")) {
                                        $this->DsKasouPrintTbl[0]["PRO_DT"] = substr($SQL_Excute['data'][0]["PRO_DT"], 4, 2) . "／" . substr($SQL_Excute['data'][0]["PRO_DT"], 6, 2);
                                        //		【生産日】
                                    } else
                                        if ($pro_dt == "" && $SQL_Excute['data'][0]["PRO_WEEK"] != "") {
                                            $this->DsKasouPrintTbl[0]["PRO_DT"] = substr($SQL_Excute['data'][0]["PRO_WEEK"], 4, 2) . "／" . substr($SQL_Excute['data'][0]["PRO_WEEK"], 6, 2);
                                            //		【生産日】
                                        } else {
                                            $this->DsKasouPrintTbl[0]["PRO_DT"] = "  ／  ";
                                            //		【生産日】
                                        }

                                if ($this->DsKasouPrintTbl[0]["CARNO"] == "") {
                                    $this->DsKasouPrintTbl[0]["ODR_NO"] = $SQL_Excute['data'][0]["ODR_NO"];
                                } else {
                                    $this->DsKasouPrintTbl[0]["ODR_NO"] = "";
                                }

                                if ($SQL_Excute['data'][0]["TENJI_WARI_DT"] != "") {
                                    $this->DsKasouPrintTbl[0]["SHOW_DAY"] = substr($SQL_Excute['data'][0]["TENJI_WARI_DT"], 4, 2) . "／" . substr($SQL_Excute['data'][0]["TENJI_WARI_DT"], 6, 2);
                                    //		【展示日】
                                } else {
                                    $this->DsKasouPrintTbl[0]["SHOW_DAY"] = "  ／  ";
                                    //		【展示日】
                                }

                                $this->DsKasouPrintTbl[0]["BUSYO_RYKNM"] = $SQL_Excute['data'][0]["BUSYO_RYKNM"];

                                if ($SQL_Excute['data'][0]["JUCHU_KB"] == "4") {
                                    $this->DsKasouPrintTbl[0]["JUCHU_KB"] = "自契他登";
                                } else {
                                    $this->DsKasouPrintTbl[0]["JUCHU_KB"] = "";
                                }
                            } else {
                                $this->DsKasouPrintTbl[0]["HIKI_ODR_DT"] = "  ／  ";
                                //		【オーダー日】
                                $this->DsKasouPrintTbl[0]["PRO_DT"] = "  ／  ";
                                //		【生産日】
                                $this->DsKasouPrintTbl[0]["ODR_NO"] = "";
                                $this->DsKasouPrintTbl[0]["SHOW_DAY"] = "  ／  ";
                                //		【展示日】
                                $this->DsKasouPrintTbl[0]["BUSYO_RYKNM"] = "";
                                $this->DsKasouPrintTbl[0]["JUCHU_KB"] = "";
                            }

                            //$this -> log('最终打印所需数据: ');
                            //$this -> log($this -> DsKasouPrintTbl[0]);

                            //架装と外注を同一ﾎﾞﾀﾝで印刷ﾌﾟﾚﾋﾞｭｰするように変更
                            // $this->log('intRptCnt');
                            // $this->log($intRptCnt);
                            // $this->log(count($objds2));

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

                                        $rpxnamesdatas = array(
                                            'rpx_file_names' => $rpx_file_names,
                                            'datas' => $datas
                                        );
                                        array_push($alldatas, $rpxnamesdatas);

                                        // $obj = new rpx_to_pdf($rpx_file_names, $datas);
                                        // $pdfPath = $obj -> to_pdf();
                                        // //スプレッドを表示 & 正常終了 , vb line 1408
                                        // $result['report'] = $pdfPath;

                                        $intState = 1;
                                    }
                                    break;
                                case '1':
                                    if (count((array) $objds2) == 0) {
                                        // //架装部用品注文書
                                        // $lngOutCntG = count($this -> DsKasouPrintTbl);
                                        //
                                        // include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                                        //
                                        // $rpx_file_names = array();
                                        // $datas = array();
                                        //
                                        // include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder.inc';
                                        // $rpx_file_names["rptOutFitOrder"] = $data_fields_rptOutFitOrder;
                                        //
                                        // $tmp_data = array();
                                        // array_push($tmp_data, $this -> DsKasouPrintTbl[0]);
                                        //
                                        // $tmp = array();
                                        // $tmp["data"] = $tmp_data;
                                        //
                                        // $tmp["mode"] = "0";
                                        // $datas["rptOutFitOrder"] = $tmp;
                                        //
                                        // $rpxnamesdatas = array(
                                        // 'rpx_file_names' => $rpx_file_names,
                                        // 'datas' => $datas
                                        // );
                                        // array_push($alldatas, $rpxnamesdatas);

                                        // $obj = new rpx_to_pdf($rpx_file_names, $datas);
                                        // $pdfPath = $obj -> to_pdf();
                                        // //スプレッドを表示 & 正常終了 , vb line 1408
                                        // $result['report'] = $pdfPath;

                                    } else {
                                        include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

                                        $rpx_file_names = array();
                                        $datas = array();

                                        //架装部用品注文書
                                        // {
                                        // $lngOutCntG = count($this -> DsKasouPrintTbl);
                                        //
                                        // include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder.inc';
                                        // $rpx_file_names["rptOutFitOrder"] = $data_fields_rptOutFitOrder;
                                        //
                                        // include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder2.inc';
                                        // $rpx_file_names["rptOutFitOrder2"] = $data_fields_rptOutFitOrder2;
                                        //
                                        // $tmp_data = array();
                                        // array_push($tmp_data, $this -> DsKasouPrintTbl[0]);
                                        //
                                        // $tmp = array();
                                        // $tmp["data"] = $tmp_data;
                                        // $tmp["mode"] = "0";
                                        // $datas["rptOutFitOrder"] = $tmp;
                                        //
                                        // //外注部用品注文書
                                        //
                                        // //外注依頼先の件数を取得する
                                        // $objGaiCnt = array();
                                        // $Customerdata = array();
                                        // $Customerdata['CMN_NO'] = $strChumon;
                                        // $Customerdata['KASOU_NO'] = $strKasou;
                                        //
                                        // $SQL_Excute = $this -> FrmListPrint -> fncCustomerSelect($Customerdata, FALSE);
                                        // if (!$SQL_Excute['result'])
                                        // {
                                        // throw new \Exception($SQL_Excute['data']);
                                        // }
                                        // $objGaiCnt = $SQL_Excute['data'];
                                        // $lngOutCntG = $this -> ClsComFnc -> FncNz(count($objGaiCnt));
                                        //
                                        // include_once $path_rpxTopdf . '/Component/tcpdf/rptContractOut.inc';
                                        // $rpx_file_names["rptContractOut"] = $data_fields_rptContractOut;
                                        // $rpx_file_names["rptContractOut2"] = $data_fields_rptContractOut;
                                        // $rpx_file_names["rptContractOut3"] = $data_fields_rptContractOut;
                                        // $rpx_file_names["rptContractOut4"] = $data_fields_rptContractOut;
                                        //
                                        // $tmp = array();
                                        //
                                        // $tmp_array = array();
                                        // foreach ($objds2 as $k => $e)
                                        // {
                                        // $name = $e['TORIHIKI_CD'];
                                        // if (!isset($tmp_array[$name]))
                                        // {
                                        // $tmp_array[$name] = $e;
                                        // unset($tmp_array[$name]['BUHINNM'], $tmp_array[$name]['SEIKYU']);
                                        // }
                                        // $tmp_array[$name]['sub_datas'][] = array(
                                        // 'BUHINNM' => $e['BUHINNM'],
                                        // 'SEIKYU' => $e['SEIKYU']
                                        // );
                                        // }
                                        // $objds2 = array_values($tmp_array);
                                        // unset($tmp_array);
                                        //
                                        // $tmp["data"] = $objds2;
                                        // $tmp["mode"] = "2";
                                        // $datas["rptContractOut"] = $tmp;
                                        // }
                                        {
                                            $lngOutCntG = count($this->DsKasouPrintTbl);
                                            include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder.inc';
                                            //$rpx_file_names["rptOutFitOrder"] = $data_fields_rptOutFitOrder;

                                            include_once $path_rpxTopdf . '/Component/tcpdf/rptOutFitOrder2.inc';
                                            //$rpx_file_names["rptOutFitOrder2"] = $data_fields_rptOutFitOrder2;

                                            include_once $path_rpxTopdf . '/Component/tcpdf/rptContractOut.inc';

                                            $tmp_data = array();
                                            array_push($tmp_data, $this->DsKasouPrintTbl[0]);
                                            //外注依頼先の件数を取得する
                                            $objGaiCnt = array();
                                            $Customerdata = array();
                                            $Customerdata['CMN_NO'] = $strChumon;
                                            $Customerdata['KASOU_NO'] = $strKasou;

                                            $SQL_Excute = $this->FrmListPrint->fncCustomerSelect($Customerdata, FALSE);
                                            if (!$SQL_Excute['result']) {
                                                throw new \Exception($SQL_Excute['data']);
                                            }
                                            $objGaiCnt = $SQL_Excute['data'];
                                            $lngOutCntG = $this->ClsComFnc->FncNz(count((array) $objGaiCnt));

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
                                            $objds2arr = array();
                                            foreach ($objds2 as $key => $value) {
                                                if ($value['TORIHIKI_CD'] == '25010' || $value['CAR_NO'] == '' || $value['CAR_NO'] == null) {
                                                    $rpx_file_names["rptOutFitOrder"] = $data_fields_rptOutFitOrder;
                                                    $tmp_data = array();
                                                    array_push($tmp_data, $this->DsKasouPrintTbl[0]);

                                                    $tmp = array();
                                                    $tmp["data"] = $tmp_data;
                                                    $tmp["mode"] = "0";
                                                    $datas["rptOutFitOrder"] = $tmp;
                                                    if ($value['TORIHIKI_CD'] == '25010') {
                                                        $rpx_file_names["rptOutFitOrder2"] = $data_fields_rptOutFitOrder2;
                                                        $tmp_data = array();
                                                        array_push($tmp_data, $this->DsKasouPrintTbl[0]);

                                                        $tmp = array();
                                                        $tmp["data"] = $tmp_data;
                                                        $tmp["mode"] = "0";
                                                        $datas["rptOutFitOrder"] = $tmp;
                                                    }
                                                    //20161024 YIN INS S
                                                    $rpx_file_names["rptContractOut"] = $data_fields_rptContractOut;
                                                    $rpx_file_names["rptContractOut2"] = $data_fields_rptContractOut;
                                                    $rpx_file_names["rptContractOut3"] = $data_fields_rptContractOut;
                                                    $rpx_file_names["rptContractOut4"] = $data_fields_rptContractOut;

                                                    array_push($objds2arr, $value);
                                                    //20161024 YIN INS E
                                                } else {
                                                    $rpx_file_names["rptContractOut"] = $data_fields_rptContractOut;
                                                    $rpx_file_names["rptContractOut2"] = $data_fields_rptContractOut;
                                                    $rpx_file_names["rptContractOut3"] = $data_fields_rptContractOut;
                                                    $rpx_file_names["rptContractOut4"] = $data_fields_rptContractOut;

                                                    array_push($objds2arr, $value);

                                                }
                                            }
                                            if (count($objds2arr) > 0) {
                                                $tmp = array();
                                                $tmp["data"] = $objds2arr;
                                                $tmp["mode"] = "2";
                                                $datas["rptContractOut"] = $tmp;
                                            }

                                        }

                                        $rpxnamesdatas = array(
                                            'rpx_file_names' => $rpx_file_names,
                                            'datas' => $datas
                                        );
                                        array_push($alldatas, $rpxnamesdatas);

                                        // $obj = new rpx_to_pdf($rpx_file_names, $datas);
                                        // $pdfPath = $obj -> to_pdf();
                                        // //スプレッドを表示 & 正常終了 , vb line 1408
                                        // $result['report'] = $pdfPath;

                                        $intState = 1;
                                    }
                                    break;
                            }
                            /* switch 结束 */
                            //20180528 YIN INS S
                            $noprint = true;
                        }
                        //20180528 YIN INS E
                        $result['result'] = TRUE;
                        $result['data'] = '';
                    }
                    $this->DsKasouPrintTbl[0] = array();
                    $postData[$n] = array();
                }
                /* for 循环结束   */

                if (count($alldatas) > 0) {
                    include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                    $obj = new \rpx_to_pdf("000", $alldatas);
                    $pdfPath = $obj->to_pdf3();
                    //スプレッドを表示 & 正常終了 , vb line 1408
                    $result['report'] = $pdfPath;
                } else {
                    $result['result'] = false;
                    $result['MsgID'] = "I9999";
                    //20180528 YIN UPD S
                    //$result['data'] = '外注伝票データがありません';
                    if ($noprint == true) {
                        $result['data'] = '外注伝票データがありません';
                    } else {
                        //20180529 lqs UPD S
                        //$result['data'] = 'データが存在しているのに印刷できないです。';
                        $result['data'] = '印刷可能なデータがありません。';
                        //20180529 lqs UPD E
                    }
                    //20180528 YIN UPD E

                }

                /* 20161107 yangyang del s */
                // for ($p = 0; $p < count($postData); $p++)
                // {
                // $oChumon = $postData[$p]['CMN_NO'];
                // //④WK_HKASOUMEISAIをDELETE
                // $SQL_Excute = $this -> FrmListPrint -> fncDeleteHKASOUMEISAI("WK_HKASOUMEISAI_APPEND", $oChumon);
                // if (!$SQL_Excute['result'])
                // {
                // throw new \Exception($SQL_Excute['data']);
                // }
                // }
                /* 20161107 yangyang del e */
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['MsgID'] = "E9999";
            $result['data'] = $e->getMessage();
        }

        //トランザクション処理が終了していない場合
        if ($blnUpdFlg) {
            //ロールバック
            $this->FrmListPrint->Do_rollback();
        }
        //ログ管理
        if ($intState != 0) {
            $resultLog1 = "";
            $resultLog2 = "";
            //架装部用品のログ管理
            $resultLog1 = $this->ClsLogControl->fncLogEntry("FrmListPrint_kasou", $intState, $lngOutCntK, $strChumon, $strKasou);
            if (!$resultLog1['result']) {
                $result['resultLog1'] = $resultLog1;
            }
            //外注加工依頼書のログ管理
            $resultLog2 = $this->ClsLogControl->fncLogEntry("FrmListPrint_gaichu", $intState, $lngOutCntG, $strChumon, $strKasou);
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
        if (isset($this->FrmListPrint->conn_orl)) {
            $this->FrmListPrint->Do_close();
            unset($this->FrmListPrint->conn_orl);
        }
        return $result;
    }

    public function fncKasouTblCheck($postData)
    {
        /**********************************************************************
                  '処 理 名：架装明細テーブルに指定された注文書番号のﾃﾞｰﾀの存在を確認するSQL
                  '関 数 名：fncKasouTblCheck
                  '引    数：なし
                  '戻 り 値：SQL文
                  '処理説明：架装明細テーブルに指定された注文書番号のﾃﾞｰﾀの存在を確認するSQL
                  '**********************************************************************/
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );
        try {
            if (isset($postData)) {
                $this->FrmListPrint = new FrmListPrint();

                $arr_result = array();
                for ($i = 0; $i < count($postData); $i++) {
                    // 処理の呼出
                    $result = $this->FrmListPrint->fncKasouTblCheck($postData[$i]);

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
                    array_push($arr_result, $result);
                }
                $result['result'] = TRUE;
                $result['data'] = $arr_result;
            } else {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $result['MsgID'] = "E9999";
        }
        return $result;
    }

    public function fncCustomerSelect($postData)
    {
        /**********************************************************************
                  '処 理 名：架装依頼先を表示
                  '関 数 名：fncMoneySelect
                  '引    数：なし
                  '戻 り 値：SQL
                  '処理説明：架装依頼先を表示する
                  '**********************************************************************/
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            if (isset($postData)) {

                $this->FrmListPrint = new FrmListPrint();

                $arr_result = array();
                for ($i = 0; $i < count($postData); $i++) {
                    /* 20161107 yangyang upd s */
                    // $result = $this -> FrmListPrint -> fncCustomerSelect($postData[$i], TRUE);
                    $result = $this->FrmListPrint->fncCustomerSelect($postData[$i], FALSE);
                    /* 20161107 yangyang upd e */

                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }

                    $count = count((array) $result['data']);
                    if ($count < 1) {
                        $result['cRow'] = 'noData';

                    } else {
                        $result['cRow'] = $count;
                    }
                    array_push($arr_result, $result);
                }
                $result['result'] = TRUE;
                $result['data'] = $arr_result;
            } else {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $result['MsgID'] = "E9999";
        }
        return $result;
    }

    public function fncStandardInfoSet($postData)
    {
        /**********************************************************************
                  '処 理 名：基本情報抽出
                  '関 数 名：fncSearchSelect
                  '引    数：なし
                  '戻 り 値：SQL
                  '処理説明：基本情報を抽出する
                  '**********************************************************************/
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );

        try {
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $NENGETU = Date("Ym");
                $this->FrmListPrint = new FrmListPrint();

                $result = $this->FrmListPrint->fncSearchSelect($postData, $NENGETU);
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
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $result['MsgID'] = "E9999";
        }
        return $result;
    }

    public function subDeleteAndInsertOfWKHKASOUMEISAI($postData)
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                // 呼出クラスのインスタンス作成
                $this->FrmListPrint = new FrmListPrint();

                /* 20161107 yangyang del s */
                // //①WK_HKASOUMEISAIから重複行を削除する
                // $Do_Excute = $this -> FrmListPrint -> fncDeleteWK_KASOUMEISAI();
                // if (!$Do_Excute['result'])
                // {
                // throw new \Exception($Do_Excute['data']);
                // }
                // //②WK_HKASOUMEISAIにINSERTする
                // $Do_Excute = $this -> FrmListPrint -> fncInsertWK_KASOUMEISAI($postData);
                // if (!$Do_Excute['result'])
                // {
                // throw new \Exception($Do_Excute['data']);
                // }
                // $result['result'] = TRUE;
                // $result['cRow'] = $Do_Excute['number_of_rows'];
                /* 20161107 yangyang del e */

                /* 20161107 yangyang add s */
                //①注文書NO検証の有無
                $result1 = $this->FrmListPrint->fncSelectCheckCMNNO($postData);
                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                } else {
                    $res1 = $result1['data'];
                }

                if (count((array) $res1) == 0) {
                    $result['result'] = "warning";
                    $result['MsgID'] = "I0001";
                } else {
                    $result['result'] = TRUE;
                    $result['data'] = $result1['data'];
                    $result['cRow'] = $result1['row'];
                }
                /* 20161107 yangyang add e */

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $result['MsgID'] = "E9999";
        }
        return $result;
    }

    /* 20161107 yangyang del s */
    // public function fncUpdSaiban1($blnUpdate = TRUE)
    // {
    // $objDr = array();
    // $strNengetu = "";
    // try
    // {
    // $strNengetu = Date("Ym");
    // $fncUpdSaiban = "99999999999";
    //
    // $result = $this -> FrmListPrint -> fncUpdSaiban($strNengetu, $fncUpdSaiban, FALSE);
    // if (!$result['result'])
    // {
    // throw new \Exception($result['data']);
    // }
    // //新たに取得した番号を架装番号に設定
    // $count = count($result['data']);
    //
    // //採番ﾃｰﾌﾞﾙに既に同一年月のものがあればUPDATE、なければINSERT
    // if ($count < 1)
    // {
    // $fncUpdSaiban = $strNengetu . "-" . "0001";
    // $UPD_TIME = $this -> ClsComFnc -> FncSqlDate(Date("Y/m/d H:m:s"));
    // if ($blnUpdate)
    // {
    // $result = $this -> FrmListPrint -> fncUpdSaibanInsert($UPD_TIME, $strNengetu, FALSE);
    // if (!$result['result'])
    // {
    // throw new \Exception($result['data']);
    // }
    // }
    // }
    // else
    // {
    // foreach ($result['data'] as $key => $value)
    // {
    // $value = $this -> ClsComFnc -> FncNv($result['data'][$key]['BANGO']);
    // $value = str_pad($value, 4, '0', STR_PAD_LEFT);
    // $fncUpdSaiban = $strNengetu . "-" . $value;
    // $BANGO = $this -> ClsComFnc -> FncSqlNz($result['data'][$key]['BANGO']);
    // }
    // if ($blnUpdate)
    // {
    // $UPD_TIME = $this -> ClsComFnc -> FncSqlDate(Date("Y/m/d H:m:s"));
    // $result = $this -> FrmListPrint -> fncUpdSaibanUpdate($BANGO, $UPD_TIME, $strNengetu);
    // if (!$result['result'])
    // {
    // throw new \Exception($result['data']);
    // }
    // }
    // }
    // $result['result'] = TRUE;
    // $result['fncUpdSaiban'] = $fncUpdSaiban;
    // return $result;
    // }
    // catch(\Exception $e)
    // {
    // $result['result'] = FALSE;
    // $result['MsgID'] = "E9999";
    // $result['data'] = $e -> getMessage();
    // $this -> set('result', $result);
    // $this -> render('cmdprintkasouclick');
    // }
    // }
    /* 20161107 yangyang del e */

}
