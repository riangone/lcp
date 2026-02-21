<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20240322           本番障害.xlsx NO8            科目名、補助科目名を両方表示してほしい              YIN
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKShiwakeInput;

//*******************************************
// * sample controller
//*******************************************
class HDKShiwakeInputController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HDKShiwakeInput = null;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
        $this->loadComponent('CustomHDKExportPDF');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HDKShiwakeInput_layout');
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
                $this->HDKShiwakeInput = new HDKShiwakeInput();
                $result = $this->HDKShiwakeInput->fncSelShiwakeForIchiran(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
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

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：ページ初期化
    //'**********************************************************************
    public function pageLoad()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->Session = $this->request->getSession();
            $result['BusyoCD'] = $this->Session->read('BusyoCD');
            if (!isset($result['BusyoCD'])) {
                $result['data']['message'] = 'W9999';
                throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
            }
            $this->HDKShiwakeInput = new HDKShiwakeInput();
            $strDispNO = $_POST['data']['strDispNO'];

            //消費税区分にセット
            $objDs = $this->HDKShiwakeInput->fncHDKMSTSHZKBN();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['MeisyouTbl'] = $objDs['data'];
            //消費税率の値を取得
            $objDs = $this->HDKShiwakeInput->fnchmeisyoumst("DS");
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['TorihikiTbl'] = $objDs['data'];
            // パターンの値を取得
            $objDs = $this->HDKShiwakeInput->fncSelPattern($this->Session->read('BusyoCD'));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['PatternTbl'] = $objDs['data'];
            //メモ欄を設定する
            $result["data"]['MemoTbl'] = array();
            $memo = $_POST['data']['memo'];
            if ($memo == "false") {
                $objDs = $this->HDKShiwakeInput->fncMemoSelSQL();
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result["data"]['MemoTbl'] = $objDs['data'];
            }
            //部署名取得
            $objDs = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['BusyoMst'] = $objDs['data'];
            //科目名取得
            $objDs = $this->ClsComFncHDKAIKEI->FncGetKamokuMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['error']);
            }
            $result['data']['KamokuMstBlank'] = $objDs['data'];
            // 取引先
            $objds = $this->ClsComFncHDKAIKEI->FncGetTorihikisakiMstValue("", TRUE);
            if (!$objds['result']) {
                throw new \Exception($objds['error']);
            }
            $result["data"]['Torihiki'] = $objds['intRtnCD'] == 1 ? $objds['Torihiki'] : array();
            //伝票検索画面又はＣＳＶ・ＸＬＳＸ再出力画面から開かれた場合
            if (isset($strDispNO) && $strDispNO == "100") {
                //表示モードを指定する
                $objds = $this->HDKShiwakeInput->fncDispModeSansyoChk($_POST['data']['strSyohy_NO']);
                if (!$objds['result']) {
                    throw new \Exception($objds['data']);
                }
                $result["data"]['DispModeTbl'] = $objds['data'];
            }
            $strMode = isset($_POST['data']['strMode']) ? $_POST['data']['strMode'] : "";
            if ($strDispNO == "100" || $strDispNO == "ReOut4OBC" || $strDispNO == "ReOut4ZenGin") {
                if ($strMode == "2") {
                    //証憑№のチェックを行う
                    $objds = $this->HDKShiwakeInput->fncNewSyohyNOSel($_POST['data']['strSyohy_NO']);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    if ($objds['row'] == 0) {
                        throw new \Exception("W0026");
                    }
                    if ($objds['data'][0]['EDA_NO'] != $_POST['data']['strEda_No']) {
                        throw new \Exception("W0025");
                    }
                    $result["data"]['NewNoTbl'] = $objds['data'];
                    //該当枝№チェック
                    $objds = $this->HDKShiwakeInput->fncFlgCheckSQL($_POST['data']['strSyohy_NO'], $_POST['data']['strEda_No']);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    if ($objds['row'] > 0) {
                        $result["data"]['EdaNoChkTbl'] = $objds['data'];
                        //修正前データを取得する
                        $objds = $this->HDKShiwakeInput->fncSyuuseiMaeSyohyoSel($_POST['data']['strSyohy_NO'], $_POST['data']['strEda_No']);
                        if (!$objds['result']) {
                            throw new \Exception($objds['data']);
                        }
                        $result["data"]['SyuseiMaeTbl'] = $objds['data'];
                    } else {
                        $result["data"]['EdaNoChkTbl'] = array();
                    }
                }
            } else
                if ($strDispNO == "103") {
                    if ($strMode == "2") {
                        //選択したパターンデータを取得する
                        $objDs = $this->HDKShiwakeInput->fncSelPatternData($_POST['data']['strPattern_NO']);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        $result["data"]['PatternTbl103'] = $objDs['data'];
                    }
                }
            $result["result"] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：修正前データの表示を行う
    //'関 数 名：btnSyuseiMaeDisp_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：修正前データの表示を行う
    //'**********************************************************************
    public function btnSyuseiMaeDispClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $lblSyohy_no = $_POST['data']['lblSyohy_no'];

            $this->HDKShiwakeInput = new HDKShiwakeInput();
            $objDs = $this->HDKShiwakeInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            if ($this->ClsComFncHDKAIKEI->FncNv($objDs['data'][0]['SYOHY_NO']) == "") {
                throw new \Exception("W0026");
            }
            $result['data']['SYUSEIMAETBL'] = $objDs['data'];
            //修正前データを取得する
            $objDs = $this->HDKShiwakeInput->fncSyuuseiMaeSyohyoSel($objDs['data'][0]['SYOHY_NO'], $objDs['data'][0]['EDA_NO']);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['SyuseiMaeTbl'] = $objDs['data'];
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：最新データの表示を行う
    //'関 数 名：btnSaishinDisp_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：最新データの表示を行う
    //'**********************************************************************
    public function btnSaishinDispClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $lblSyohy_no = $_POST['data']['lblSyohy_no'];

            $this->HDKShiwakeInput = new HDKShiwakeInput();
            $objDs = $this->HDKShiwakeInput->fncNewSyohyNOSel(substr($lblSyohy_no, 0, 15));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            if ($objDs['row'] == 0) {
                throw new \Exception("W0026");
            }
            $result['data']['NEWTBL'] = $objDs['data'];
            //修正前データを取得する
            $objDs = $this->HDKShiwakeInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), $objDs['data'][0]['EDA_NO']);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['SyuseiMaeTbl'] = $objDs['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：行追加を行う
    //'関 数 名：btnAdd_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：行追加処理(入力チェック・確認メッセージの表示を行う)
    //'**********************************************************************
    public function btnAddClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            if (isset($_POST['data']['lblSyohy_no']) && $_POST['data']['lblSyohy_no'] != "") {
                $lblSyohy_no = $_POST['data']['lblSyohy_no'];

                $this->HDKShiwakeInput = new HDKShiwakeInput();
                $objDs = $this->HDKShiwakeInput->fncGyoNoSel(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result['data']['CheckTbl'] = $objDs['data'];
            }
            //** 名称取得
            $txtLBusyoCD = $_POST['data']['txtLBusyoCD'];
            $txtRbusyoCD = $_POST['data']['txtRbusyoCD'];
            $this->Session = $this->request->getSession();
            $objDs = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue(true, $this->Session->read('BusyoCD'));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['strSyozokuTenpo'] = $objDs['data'];
            $objDs = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue(true, $txtLBusyoCD);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['strKariTenpo'] = $objDs['data'];
            $objDs = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue(true, $txtRbusyoCD);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['strKashiTenpo'] = $objDs['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：行追加・行修正・行削除を行う
    //'関 数 名：cmdEvent_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：ＤＢへの追加・修正・削除処理を行う
    //'**********************************************************************
    public function cmdEventClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $tranStartFlg = FALSE;
        $this->HDKShiwakeInput = new HDKShiwakeInput();
        try {
            $this->Session = $this->request->getSession();
            $postData = $_POST['data'];
            $resCheck = $this->inputCheck($postData);
            if (!$resCheck['result']) {
                $result['data'] = $resCheck['data'];
                $result['html'] = $resCheck['html'];
                throw new \Exception('W0034');
            }
            $strSEQNO = $_POST['data']['strSEQNO'];
            $intEdaNo = isset($_POST['data']['intEdaNo']) ? $_POST['data']['intEdaNo'] : "";
            $lblSyohy_no = $_POST['data']['lblSyohy_no'];
            //トランザクション開始
            $this->HDKShiwakeInput->Do_transaction();
            $tranStartFlg = TRUE;
            //証憑№の取得を行う
            if ($lblSyohy_no == "") {
                $strSysdate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Ym");
                $objDs = $this->fncSaiban("1", $this->Session->read('BusyoCD'), $strSysdate, "ShiwakeInput");
                if (!$objDs['result']) {
                    throw new \Exception($objDs['error']);
                }
                $strSEQNO = $objDs['data']['fncSaiban'] . "00";
            } else {
                $strSEQNO = $lblSyohy_no;
            }
            //システム日付を取得する
            $strSysdate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Y/m/d H:i:s");
            //新規の証憑登録の場合
            if ($_POST['data']['flag'] == 1) {
                //登録処理を行う
                $objDs = $this->HDKShiwakeInput->fncShiwakeDataIns(substr($strSEQNO, 0, 15), substr($strSEQNO, 15, 2), "'1'", $strSysdate, $this->Session->read('BusyoCD'), "", "ShiwakeInput", "", $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
            }
            //追加の証憑登録の場合
            else
                if ($_POST['data']['flag'] == "2") {
                    //印刷済みの証憑の場合
                    if ($_POST['data']['PRINT_OUT_FLG'] == "1") {
                        $objDs = $this->HDKShiwakeInput->fncMaeShiwakeCopy(substr($lblSyohy_no, 0, 15), $intEdaNo, substr($lblSyohy_no, 15, 2), $strSysdate, $_POST['data'], $this->Session->read('BusyoCD'), $this->Session->read('PatternID'));
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        if (strlen($intEdaNo) < 2) {
                            $length = 2 - strlen($intEdaNo);
                            for ($i = 0; $i < $length; $i++) {
                                $intEdaNo = "0" . $intEdaNo;
                            }
                        } else {
                            $intEdaNo = substr($intEdaNo, 0, 2);
                        }
                        //登録処理
                        //追加ボタンが押下された場合
                        if ($_POST['data']['sender'] == "CMDEVENTINSERT") {
                            //登録処理を行う
                            $strGyoNo = "(SELECT NVL(MAX(GYO_NO),0) + 1 FROM HDPSHIWAKEDATA WHERE SYOHY_NO = '@SYOHY_NO' AND EDA_NO = '@EDA_NO')";
                            $objDs = $this->HDKShiwakeInput->fncShiwakeDataIns(substr($lblSyohy_no, 0, 15), $intEdaNo, $strGyoNo, $strSysdate, $_POST['data']['strCreBusyoCD'], $_POST['data']['strCreSyainCD'], $_POST['data']['strCrePrgID'], $_POST['data']['strCreCltNM'], $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                            if (!$objDs['result']) {
                                throw new \Exception($objDs['data']);
                            }
                        }
                        //修正ボタンが押下された場合
                        else
                            if ($_POST['data']['sender'] == "CMDEVENTUPDATE") {
                                //修正処理を行う
                                $strGyoNo = $_POST['data']['hidGyoNO'];
                                $objDs = $this->HDKShiwakeInput->fncUpdateSQL(substr($lblSyohy_no, 0, 15), $intEdaNo, $strGyoNo, $strSysdate, $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                                if (!$objDs['result']) {
                                    throw new \Exception($objDs['data']);
                                }
                            }
                            //削除ボタンが押下された場合
                            else
                                if ($_POST['data']['sender'] == "CMDEVENTDELETE") {
                                    //削除処理を行う
                                    $strGyoNo = $_POST['data']['hidGyoNO'];
                                    $objDs = $this->HDKShiwakeInput->fncGyoDelete(substr($lblSyohy_no, 0, 15), $intEdaNo, $strGyoNo, $strSysdate);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                    //削除した行が最後の1件だった場合は、履歴の削除フラグを更新する
                                    $objDs = $this->HDKShiwakeInput->fncLastDelAllUpd(substr($lblSyohy_no, 0, 15), $intEdaNo, $strSysdate, $this->Session->read('BusyoCD'), $this->Session->read('PatternID'), $_POST['data']);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                    //添付ファイル削除
                                    $objDs = $this->HDKShiwakeInput->fncHDKATTACHMENT(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strSysdate, $strGyoNo);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                }
                                //全削除ボタンが押下された場合
                                else
                                    if ($_POST['data']['sender'] == "CMDEVENTALLDELETE") {
                                        //削除処理を行う
                                        $objDs = $this->HDKShiwakeInput->fncAllDeleteUpd(substr($lblSyohy_no, 0, 15), $strSysdate, $this->Session->read('BusyoCD'), $this->Session->read('PatternID'), $_POST['data']);
                                        if (!$objDs['result']) {
                                            throw new \Exception($objDs['data']);
                                        }
                                        //添付ファイル削除
                                        $objDs = $this->HDKShiwakeInput->fncHDKATTACHMENT(substr($lblSyohy_no, 0, 15), '', $strSysdate);
                                        if (!$objDs['result']) {
                                            throw new \Exception($objDs['data']);
                                        }
                                    }
                        //修正前データを取得する
                        $objDs = $this->HDKShiwakeInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), $intEdaNo);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        $result['data']['SyuseiMaeTbl'] = $objDs['data'];
                    } else {
                        //追加ボタンが押下された場合
                        if ($_POST['data']['sender'] == "CMDEVENTINSERT") {
                            //登録処理を行う
                            $strGyoNo = "(SELECT NVL(MAX(GYO_NO),0) + 1 FROM HDPSHIWAKEDATA WHERE SYOHY_NO = '@SYOHY_NO' AND EDA_NO = '@EDA_NO')";
                            $objDs = $this->HDKShiwakeInput->fncShiwakeDataIns(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strGyoNo, $strSysdate, $_POST['data']['strCreBusyoCD'], $_POST['data']['strCreSyainCD'], $_POST['data']['strCrePrgID'], $_POST['data']['strCreCltNM'], $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                            if (!$objDs['result']) {
                                throw new \Exception($objDs['data']);
                            }
                        } //修正ボタンが押下された場合
                        else
                            if ($_POST['data']['sender'] == "CMDEVENTUPDATE") {
                                //修正処理を行う
                                $strGyoNo = $_POST['data']['hidGyoNO'];
                                $objDs = $this->HDKShiwakeInput->fncUpdateSQL(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strGyoNo, $strSysdate, $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                                if (!$objDs['result']) {
                                    throw new \Exception($objDs['data']);
                                }
                            }
                            //削除ボタンが押下された場合
                            else
                                if ($_POST['data']['sender'] == "CMDEVENTDELETE") {
                                    //削除処理を行う
                                    $strGyoNo = $_POST['data']['hidGyoNO'];
                                    $objDs = $this->HDKShiwakeInput->fncGyoDelete(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strGyoNo, $strSysdate);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                    //削除した行が最後の1件だった場合は、履歴の削除フラグを更新する
                                    $objDs = $this->HDKShiwakeInput->fncLastDelAllUpd(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strSysdate, $this->Session->read('BusyoCD'), $this->Session->read('PatternID'), $_POST['data']);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                    //添付ファイル削除
                                    $objDs = $this->HDKShiwakeInput->fncHDKATTACHMENT(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strSysdate, $strGyoNo);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                }
                                //全削除ボタンが押下された場合
                                else
                                    if ($_POST['data']['sender'] == "CMDEVENTALLDELETE") {
                                        //削除処理を行う
                                        $objDs = $this->HDKShiwakeInput->fncAllDelete(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                                        if (!$objDs['result']) {
                                            throw new \Exception($objDs['data']);
                                        }
                                        //履歴の削除フラグを更新する
                                        $objDs = $this->HDKShiwakeInput->fncAllDeleteUpd(substr($lblSyohy_no, 0, 15), $strSysdate, $this->Session->read('BusyoCD'), $this->Session->read('PatternID'), $_POST['data']);
                                        if (!$objDs['result']) {
                                            throw new \Exception($objDs['data']);
                                        }
                                        //添付ファイル削除
                                        $objDs = $this->HDKShiwakeInput->fncHDKATTACHMENT(substr($lblSyohy_no, 0, 15), '', $strSysdate);
                                        if (!$objDs['result']) {
                                            throw new \Exception($objDs['data']);
                                        }
                                    }
                        if ($_POST['data']['sender'] == "CMDEVENTDELETE" || $_POST['data']['sender'] == "CMDEVENTALLDELETE") {
                            $objDs = $this->HDKShiwakeInput->fncFlgCheckSQL(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                            if (!$objDs['result']) {
                                throw new \Exception($objDs['data']);
                            }
                            $result['data']['DispModeTbl'] = $objDs['data'];
                        }
                    }
                }
            //コミット
            $this->HDKShiwakeInput->Do_commit();

            $result['data']['intEdaNo'] = substr($intEdaNo, 0, 2);
            $result['data']['dtSysdate'] = $strSysdate;
            $result['data']['strSEQNO'] = $strSEQNO;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HDKShiwakeInput->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //追加の証憑登録の場合
    public function fncCheckJikkoSeigyo()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $lblSyohy_no = $_POST['data']['lblSyohy_no'];

            $this->HDKShiwakeInput = new HDKShiwakeInput();
            //チェック用ＳＱＬを取得する
            $objDs = $this->HDKShiwakeInput->fncFlgCheckSQL(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['CheckTbl'] = $objDs['data'];
            $objDs = $this->HDKShiwakeInput->fncNewSyohyNOSel(substr($lblSyohy_no, 0, 15));
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

    public function cmdEventPrintClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $tranStartFlg = false;
        $this->HDKShiwakeInput = new HDKShiwakeInput();
        try {
            $lblSyohy_no = $_POST['data']['lblSyohy_no'];
            //トランザクション開始
            $this->HDKShiwakeInput->Do_transaction();
            $tranStartFlg = TRUE;
            //削除されていないかチェックします。
            $objDs = $this->HDKShiwakeInput->fncFlgCheckSQL(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            if ($objDs['row'] == 0) {
                throw new \Exception('W0026');
            }
            //証憑№のチェックを行う
            $objDs = $this->HDKShiwakeInput->fncNewSyohyNOSel(substr($lblSyohy_no, 0, 15));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            if ($objDs['data'][0]['EDA_NO'] != substr($lblSyohy_no, 15, 2)) {
                throw new \Exception('W0025');
            }
            //ワーク証憑№のデータを全件削除する
            $objDs = $this->HDKShiwakeInput->fncAllDelSQL();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $intTaisyo = $this->HDKShiwakeInput->fncInsTaisyoSyohyNOPrint($lblSyohy_no);
            if (!$intTaisyo['result']) {
                throw new \Exception($intTaisyo['data']);
            }
            if ($intTaisyo['number_of_rows'] < 1) {
                throw new \Exception("W0024");
            }
            //コミット
            $this->HDKShiwakeInput->Do_commit();
            $tranStartFlg = FALSE;
            //印刷プレビュー画面の表示
            $arr = array();
            $arr['CONST_ADMIN_PTN_NO'] = $_POST['data']['CONST_ADMIN_PTN_NO'];
            $arr['CONST_HONBU_PTN_NO'] = $_POST['data']['CONST_HONBU_PTN_NO'];
            $this->Session = $this->request->getSession();
            $arr['BusyoCD'] = $this->Session->read('BusyoCD');
            $printPDF = $this->CustomHDKExportPDF->FncDenpyoinsatuPrint("101", $arr);
            if (!$printPDF['result']) {
                throw new \Exception($printPDF['error']);
            }
            $result['data']['report'] = $printPDF['report'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HDKShiwakeInput->Do_rollback();
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

            $objDr = $this->HDKShiwakeInput->fncSaiban($strKbn, $strBusyoCD, $strNengetu);
            if (!$objDr['result']) {
                throw new \Exception($objDr['data']);
            }

            if ($objDr['row'] > 0) {
                $BANGO = $this->ClsComFncHDKAIKEI->FncNv($objDr['data'][0]['BANGO']);
                if (strlen($BANGO) < 5) {
                    $length = 5 - strlen($this->ClsComFncHDKAIKEI->FncNv($objDr['data'][0]['BANGO']));
                    for ($i = 0; $i < $length; $i++) {
                        $BANGO = "0" . $BANGO;
                    }
                } else {
                    $BANGO = substr($BANGO, 0, 5);
                }
                $fncSaiban = $strKbn . $strBusyoCD . $strNengetu . $BANGO;
            } else {
                $fncSaiban = $strKbn . $strBusyoCD . $strNengetu . "00001";
            }

            if ($blnUpdate) {
                //採番ﾃｰﾌﾞﾙに既に同一年月のものがあればUPDATE、なければINSERT
                $objDr = $this->HDKShiwakeInput->fncSaiban2($strKbn, $strBusyoCD, $strNengetu, $strProID, $objDr);
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

    //仕訳データの取得
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
            $this->HDKShiwakeInput = new HDKShiwakeInput();
            if (!isset($_POST['data']['fileExist'])) {
                $objDs = $this->HDKShiwakeInput->fncSelShiwakeData($SYOHY_NO, $EDA_NO, $GYO_NO);
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result['data']['NewNoTbl'] = $objDs['data'];
            }
            // 添付ファイル
            $objDs = $this->HDKShiwakeInput->fncFileCheck($SYOHY_NO, $EDA_NO, $GYO_NO);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            if (count((array) $objDs['data']) > 0) {
                $result['data']['fileExist'] = true;
            } else {
                $result['data']['fileExist'] = false;
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：表示されている仕訳をパターンとして登録する
    //'関 数 名：btnPatternTrk_Click
    //'処理説明：表示されている仕訳をパターンとして登録する
    //'**********************************************************************
    public function cmdEventPatternTrkClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'data' => []
        );
        $tranStartFlg = FALSE;
        $this->HDKShiwakeInput = new HDKShiwakeInput();
        try {
            $this->Session = $this->request->getSession();
            $postData = $_POST['data'];
            $resCheck = $this->inputCheck($postData);
            if (!$resCheck['result']) {
                $result['data'] = $resCheck['data'];
                $result['html'] = $resCheck['html'];
                throw new \Exception('W0034');
            }
            $hidPatternNO = isset($_POST['data']['hidPatternNO']) ? $_POST['data']['hidPatternNO'] : "";
            //トランザクション開始
            $this->HDKShiwakeInput->Do_transaction();
            $tranStartFlg = TRUE;
            if ($hidPatternNO == "") {
                $objDs = $this->HDKShiwakeInput->fncPatternTrkDispShiwake($_POST['data'], $this->Session->read('BusyoCD'));
            } else {
                $objDs = $this->HDKShiwakeInput->fncUpdPatternTrk("1", $_POST['data'], $this->Session->read('BusyoCD'));
            }
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            //パターンの値を取得
            $objDs = $this->HDKShiwakeInput->fncSelPattern($this->Session->read('BusyoCD'));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['PatternTbl'] = $objDs['data'];
            //コミット
            $this->HDKShiwakeInput->Do_commit();

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HDKShiwakeInput->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：パターンを削除する(パターン検索画面より遷移)
    //'関 数 名：btnPtnDelete_Click
    //'処理説明：パターンを削除する
    //'**********************************************************************
    public function btnPtnDeleteClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $tranStartFlg = FALSE;
        $this->HDKShiwakeInput = new HDKShiwakeInput();
        try {
            //トランザクション開始
            $this->HDKShiwakeInput->Do_transaction();
            $tranStartFlg = TRUE;
            $objDs = $this->HDKShiwakeInput->fncPatternDelete($_POST['data']['hidPatternNO']);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            //コミット
            $this->HDKShiwakeInput->Do_commit();

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HDKShiwakeInput->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：パターン選択
    //'関 数 名：btnPatternTrk_Click
    //'処理説明：選択されたパターンによって仕訳を展開する
    //'**********************************************************************
    public function ddlPatternSelSelectedIndexChanged()
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'data' => []
        );
        try {
            $this->HDKShiwakeInput = new HDKShiwakeInput();
            $objDs = $this->HDKShiwakeInput->fncSelectPattern($_POST['data']['ddlPatternSel']);
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

    // public function FncGetKamokuMstValue()
// {
// 	$result = array(
// 		'result' => FALSE,
// 		'error' => ''
// 	);
// 	try {
// 		//科目名取得
// 		$objDs = $this->ClsComFncHDKAIKEI->FncGetKamokuMstValue($_POST['data']['KamokuCD'], $_POST['data']['KomokuCD'], FALSE);
// 		if (!$objDs['result']) {
// 			throw new \Exception($objDs['error']);
// 		}
// 		$result['data']['strKamokuNM'] = $objDs['intRtnCD'] == 1 ? $objDs['strKamokuNM'] : '';
// 		//口座キー・必須摘要の名称を取得する
// 		$objDs = $this->HDKShiwakeInput->fncKouzaHittekiKashikata($_POST['data']['KamokuCD'], $_POST['data']['txtKomokuCD']);
// 		if (!$objDs['result']) {
// 			throw new \Exception($objDs['data']);
// 		}
// 		$result['data']['KOUBANTBL'] = $objDs['data'];

    // 		$result['result'] = TRUE;
// 	} catch (\Exception $e) {
// 		$result['result'] = FALSE;
// 		$result['error'] = $e->getMessage();
// 	}
// 	$this->set('result', $result);
// 	$this->render('fncgetkamokumstvalue');
// }
    public function inputCheck($postData)
    {
        $result = array(
            'result' => true,
            'html' => '',
            'data' => ''
        );
        try {
            if (isset($postData['txtTekyo']) && (!$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['txtTekyo']))) {
                $result['html'] = 'txtTekyo';
                throw new \Exception('摘要');
            }
            if (isset($postData['txtPatternNM']) && (!$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['txtPatternNM']))) {
                $result['html'] = 'txtPatternNM';
                throw new \Exception('パターン名');
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }
}