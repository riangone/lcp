<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20240417           svn-ver.38694			            	VBソース変更              				lqs
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMDPS;

use App\Controller\AppController;
use App\Model\HMDPS\HMDPS101ShiwakeDenpyoInput;
//*******************************************
// * sample controller
//*******************************************
class HMDPS101ShiwakeDenpyoInputController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncHMDPS',
    //     'CustomExportPDF'
    // );
    public $HMDPS101ShiwakeDenpyoInput;
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
        $this->render('index', 'HMDPS101ShiwakeDenpyoInput_layout');
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
                $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
                $result = $this->HMDPS101ShiwakeDenpyoInput->fncSelShiwakeForIchiran(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMDPS->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFncHMDPS->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

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

            $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
            $strDispNO = $_POST['data']['strDispNO'];
            //貸方消費税区分にセット
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSelMeisyoForDdl("DS");
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['MeisyouTbl'] = $objDs['data'];
            //取引先区分の値を取得
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSelMeisyoForDdl("DT");
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['TorihikiTbl'] = $objDs['data'];
            // 20240417 lqs INS S
            //相手先区分にセット
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSelMeisyoForDdl("DA");
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['AitesakiKBN'] = $objDs['data'];
            //特例区分の値を取得
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSelMeisyoForDdl("DR");
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['TokureiKBN'] = $objDs['data'];
            // 20240417 lqs INS E
            //パターンの値を取得
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSelPattern($this->Session->read('BusyoCD'));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['PatternTbl'] = $objDs['data'];
            //メモ欄を設定する
            $result["data"]['MemoTbl'] = array();
            $memo = $_POST['data']['memo'];
            if ($memo == "false") {
                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncMemoSelSQL();
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result["data"]['MemoTbl'] = $objDs['data'];
            }
            //部署名取得
            $objDs = $this->ClsComFncHMDPS->FncGetBusyoMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['BusyoMst'] = $objDs['data'];
            //科目名取得
            $objDs = $this->ClsComFncHMDPS->FncGetKamokuMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['KamokuMstBlank'] = $objDs['data'];
            $objDs = $this->ClsComFncHMDPS->FncGetKamokuMstValue("", "888888");
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['KamokuMstNotBlank'] = $objDs['data'];
            //伝票検索画面又はＣＳＶ再出力画面から開かれた場合
            if (isset($strDispNO) && $strDispNO == "100") {
                //表示モードを指定する
                $objds = $this->HMDPS101ShiwakeDenpyoInput->fncDispModeSansyoChk($_POST['data']['strSyohy_NO']);
                if (!$objds['result']) {
                    throw new \Exception($objds['data']);
                }
                $result["data"]['DispModeTbl'] = $objds['data'];
            }
            $strMode = isset($_POST['data']['strMode']) ? $_POST['data']['strMode'] : "";
            if ($strDispNO == "100" || $strDispNO == "105") {
                if ($strMode == "2") {
                    //証憑№のチェックを行う
                    $objds = $this->HMDPS101ShiwakeDenpyoInput->fncNewSyohyNOSel($_POST['data']['strSyohy_NO']);
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
                    $objds = $this->HMDPS101ShiwakeDenpyoInput->fncFlgCheckSQL($_POST['data']['strSyohy_NO'], $_POST['data']['strEda_No']);
                    if (!$objds['result']) {
                        throw new \Exception($objds['data']);
                    }
                    if ($objds['row'] > 0) {
                        $result["data"]['EdaNoChkTbl'] = $objds['data'];
                        //修正前データを取得する
                        $objds = $this->HMDPS101ShiwakeDenpyoInput->fncSyuuseiMaeSyohyoSel($_POST['data']['strSyohy_NO'], $_POST['data']['strEda_No']);
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
                        $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSelPatternData($_POST['data']['strPattern_NO']);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        $result["data"]['PatternTbl103'] = $objDs['data'];
                        //口座キー・必須摘要の名称を取得する
                        $result["data"]['LKOUBANTBL'] = array();
                        $result["data"]['RKOUBANTBL'] = array();
                        if ($objDs['row'] > 0) {
                            //口座キー・必須摘要の名称を取得する(借方)
                            if ($result["data"]['PatternTbl103']['0']['L_KAMOK_CD'] !== null) {
                                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncKouzaHittekiKashikata($result["data"]['PatternTbl103']['0']['L_KAMOK_CD'], $result["data"]['PatternTbl103']['0']['L_KOUMK_CD']);
                                if (!$objDs['result']) {
                                    throw new \Exception($objDs['data']);
                                }
                                $result["data"]['LKOUBANTBL'] = $objDs['data'];
                            }
                            //口座キー・必須摘要の名称を取得する(貸方)
                            if ($result["data"]['PatternTbl103']['0']['R_KAMOK_CD']) {
                                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncKouzaHittekiKashikata($result["data"]['PatternTbl103']['0']['R_KAMOK_CD'], $result["data"]['PatternTbl103']['0']['R_KOUMK_CD']);
                                if (!$objDs['result']) {
                                    throw new \Exception($objDs['data']);
                                }
                                $result["data"]['RKOUBANTBL'] = $objDs['data'];
                            }
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

            $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            if ($this->ClsComFncHMDPS->FncNv($objDs['data'][0]['SYOHY_NO']) == "") {
                throw new \Exception("W0026");
            }
            $result['data']['SYUSEIMAETBL'] = $objDs['data'];
            //修正前データを取得する
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSyuuseiMaeSyohyoSel($objDs['data'][0]['SYOHY_NO'], $objDs['data'][0]['EDA_NO']);
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

            $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncNewSyohyNOSel(substr($lblSyohy_no, 0, 15));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            if ($objDs['row'] == 0) {
                throw new \Exception("W0026");
            }
            $result['data']['NEWTBL'] = $objDs['data'];
            //修正前データを取得する
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), $objDs['data'][0]['EDA_NO']);
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
            $this->Session = $this->request->getSession();
            if (isset($_POST['data']['lblSyohy_no']) && $_POST['data']['lblSyohy_no'] != "") {
                $lblSyohy_no = $_POST['data']['lblSyohy_no'];

                $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncGyoNoSel(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result['data']['CheckTbl'] = $objDs['data'];
            }
            //** 名称取得
            $txtLBusyoCD = $_POST['data']['txtLBusyoCD'];
            $txtRbusyoCD = $_POST['data']['txtRbusyoCD'];
            $objDs = $this->ClsComFncHMDPS->FncGetBusyoMstValue(true, $this->Session->read('BusyoCD'));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['strSyozokuTenpo'] = $objDs['data'];
            $objDs = $this->ClsComFncHMDPS->FncGetBusyoMstValue(true, $txtLBusyoCD);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['strKariTenpo'] = $objDs['data'];
            $objDs = $this->ClsComFncHMDPS->FncGetBusyoMstValue(true, $txtRbusyoCD);
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
        $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
        try {
            $this->Session = $this->request->getSession();
            $strSEQNO = $_POST['data']['strSEQNO'];
            $intEdaNo = isset($_POST['data']['intEdaNo']) ? $_POST['data']['intEdaNo'] : "";
            $lblSyohy_no = $_POST['data']['lblSyohy_no'];
            //トランザクション開始
            $this->HMDPS101ShiwakeDenpyoInput->Do_transaction();
            $tranStartFlg = TRUE;
            //証憑№の取得を行う
            if ($lblSyohy_no == "") {
                $strSysdate = $this->ClsComFncHMDPS->FncGetSysDate("Ym");
                $objDs = $this->fncSaiban("1", $this->Session->read('BusyoCD'), $strSysdate, "ShiwakeInput");
                if (!$objDs['result']) {
                    throw new \Exception($objDs['error']);
                }
                $strSEQNO = $objDs['data']['fncSaiban'] . "00";
            } else {
                $strSEQNO = $lblSyohy_no;
            }
            //システム日付を取得する
            $strSysdate = $this->ClsComFncHMDPS->FncGetSysDate("Y/m/d H:i:s");
            //新規の証憑登録の場合
            if ($_POST['data']['flag'] == 1) {
                //登録処理を行う
                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncShiwakeDataIns(substr($strSEQNO, 0, 15), substr($strSEQNO, 15, 2), "'1'", $strSysdate, $this->Session->read('BusyoCD'), "", "ShiwakeInput", "", $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
            }
            //追加の証憑登録の場合
            else
                if ($_POST['data']['flag'] == "2") {
                    //印刷済みの証憑の場合
                    if ($_POST['data']['PRINT_OUT_FLG'] == "1") {
                        $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncMaeShiwakeCopy(substr($lblSyohy_no, 0, 15), $intEdaNo, substr($lblSyohy_no, 15, 2), $strSysdate, $_POST['data'], $this->Session->read('BusyoCD'), $this->Session->read('PatternID'));
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
                            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncShiwakeDataIns(substr($lblSyohy_no, 0, 15), $intEdaNo, $strGyoNo, $strSysdate, $_POST['data']['strCreBusyoCD'], $_POST['data']['strCreSyainCD'], $_POST['data']['strCrePrgID'], $_POST['data']['strCreCltNM'], $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                            if (!$objDs['result']) {
                                throw new \Exception($objDs['data']);
                            }
                        }
                        //修正ボタンが押下された場合
                        else
                            if ($_POST['data']['sender'] == "CMDEVENTUPDATE") {
                                //修正処理を行う
                                $strGyoNo = $_POST['data']['hidGyoNO'];
                                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncUpdateSQL(substr($lblSyohy_no, 0, 15), $intEdaNo, $strGyoNo, $strSysdate, $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                                if (!$objDs['result']) {
                                    throw new \Exception($objDs['data']);
                                }
                            }
                            //削除ボタンが押下された場合
                            else
                                if ($_POST['data']['sender'] == "CMDEVENTDELETE") {
                                    //削除処理を行う
                                    $strGyoNo = $_POST['data']['hidGyoNO'];
                                    $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncGyoDelete(substr($lblSyohy_no, 0, 15), $intEdaNo, $strGyoNo, $strSysdate);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                    //削除した行が最後の1件だった場合は、履歴の削除フラグを更新する
                                    $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncLastDelAllUpd(substr($lblSyohy_no, 0, 15), $intEdaNo, $strSysdate, $this->Session->read('BusyoCD'), $this->Session->read('PatternID'), $_POST['data']);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                }
                                //全削除ボタンが押下された場合
                                else
                                    if ($_POST['data']['sender'] == "CMDEVENTALLDELETE") {
                                        //削除処理を行う
                                        $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncAllDeleteUpd(substr($lblSyohy_no, 0, 15), $strSysdate, $this->Session->read('BusyoCD'), $this->Session->read('PatternID'), $_POST['data']);
                                        if (!$objDs['result']) {
                                            throw new \Exception($objDs['data']);
                                        }
                                    }
                        //修正前データを取得する
                        $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSyuuseiMaeSyohyoSel(substr($lblSyohy_no, 0, 15), $intEdaNo);
                        if (!$objDs['result']) {
                            throw new \Exception($objDs['data']);
                        }
                        $result['data']['SyuseiMaeTbl'] = $objDs['data'];
                    } else {
                        //追加ボタンが押下された場合
                        if ($_POST['data']['sender'] == "CMDEVENTINSERT") {
                            //登録処理を行う
                            $strGyoNo = "(SELECT NVL(MAX(GYO_NO),0) + 1 FROM HDPSHIWAKEDATA WHERE SYOHY_NO = '@SYOHY_NO' AND EDA_NO = '@EDA_NO')";
                            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncShiwakeDataIns(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strGyoNo, $strSysdate, $_POST['data']['strCreBusyoCD'], $_POST['data']['strCreSyainCD'], $_POST['data']['strCrePrgID'], $_POST['data']['strCreCltNM'], $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                            if (!$objDs['result']) {
                                throw new \Exception($objDs['data']);
                            }
                        }//修正ボタンが押下された場合
                        else
                            if ($_POST['data']['sender'] == "CMDEVENTUPDATE") {
                                //修正処理を行う
                                $strGyoNo = $_POST['data']['hidGyoNO'];
                                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncUpdateSQL(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strGyoNo, $strSysdate, $this->Session->read('PatternID'), $_POST['data'], $this->Session->read('BusyoCD'));
                                if (!$objDs['result']) {
                                    throw new \Exception($objDs['data']);
                                }
                            }
                            //削除ボタンが押下された場合
                            else
                                if ($_POST['data']['sender'] == "CMDEVENTDELETE") {
                                    //削除処理を行う
                                    $strGyoNo = $_POST['data']['hidGyoNO'];
                                    $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncGyoDelete(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strGyoNo, $strSysdate);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                    //削除した行が最後の1件だった場合は、履歴の削除フラグを更新する
                                    $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncLastDelAllUpd(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2), $strSysdate, $this->Session->read('BusyoCD'), $this->Session->read('PatternID'), $_POST['data']);
                                    if (!$objDs['result']) {
                                        throw new \Exception($objDs['data']);
                                    }
                                }
                                //全削除ボタンが押下された場合
                                else
                                    if ($_POST['data']['sender'] == "CMDEVENTALLDELETE") {
                                        //削除処理を行う
                                        $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncAllDelete(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                                        if (!$objDs['result']) {
                                            throw new \Exception($objDs['data']);
                                        }
                                        //履歴の削除フラグを更新する
                                        $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncAllDeleteUpd(substr($lblSyohy_no, 0, 15), $strSysdate, $this->Session->read('BusyoCD'), $this->Session->read('PatternID'), $_POST['data']);
                                        if (!$objDs['result']) {
                                            throw new \Exception($objDs['data']);
                                        }
                                    }
                        if ($_POST['data']['sender'] == "CMDEVENTDELETE" || $_POST['data']['sender'] == "CMDEVENTALLDELETE") {
                            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncFlgCheckSQL(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
                            if (!$objDs['result']) {
                                throw new \Exception($objDs['data']);
                            }
                            $result['data']['DispModeTbl'] = $objDs['data'];
                        }
                    }
                }
            //コミット
            $this->HMDPS101ShiwakeDenpyoInput->Do_commit();

            $result['data']['intEdaNo'] = substr($intEdaNo, 0, 2);
            $result['data']['dtSysdate'] = $strSysdate;
            $result['data']['strSEQNO'] = $strSEQNO;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMDPS101ShiwakeDenpyoInput->Do_rollback();
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

            $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
            //チェック用ＳＱＬを取得する
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncFlgCheckSQL(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['CheckTbl'] = $objDs['data'];
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncNewSyohyNOSel(substr($lblSyohy_no, 0, 15));
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
        $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
        try {
            $this->Session = $this->request->getSession();
            $lblSyohy_no = $_POST['data']['lblSyohy_no'];
            //トランザクション開始
            $this->HMDPS101ShiwakeDenpyoInput->Do_transaction();
            $tranStartFlg = TRUE;
            //削除されていないかチェックします。
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncFlgCheckSQL(substr($lblSyohy_no, 0, 15), substr($lblSyohy_no, 15, 2));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            if ($objDs['row'] == 0) {
                throw new \Exception('W0026');
            }
            //証憑№のチェックを行う
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncNewSyohyNOSel(substr($lblSyohy_no, 0, 15));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            if ($objDs['data'][0]['EDA_NO'] != substr($lblSyohy_no, 15, 2)) {
                throw new \Exception('W0025');
            }
            //ワーク証憑№のデータを全件削除する
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncAllDelSQL();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $intTaisyo = $this->HMDPS101ShiwakeDenpyoInput->fncInsTaisyoSyohyNOPrint($lblSyohy_no);
            if (!$intTaisyo['result']) {
                throw new \Exception($intTaisyo['data']);
            }
            if ($intTaisyo['number_of_rows'] < 1) {
                throw new \Exception("W0024");
            }
            //コミット
            $this->HMDPS101ShiwakeDenpyoInput->Do_commit();
            $tranStartFlg = FALSE;
            //印刷プレビュー画面の表示
            $arr = array();
            $arr['CONST_ADMIN_PTN_NO'] = $_POST['data']['CONST_ADMIN_PTN_NO'];
            $arr['CONST_HONBU_PTN_NO'] = $_POST['data']['CONST_HONBU_PTN_NO'];
            $arr['BusyoCD'] = $this->Session->read('BusyoCD');
            $printPDF = $this->CustomExportPDF->FncDenpyoinsatuPrint("101", $arr);
            if (!$printPDF['result']) {
                throw new \Exception($printPDF['error']);
            }
            $result['data']['report'] = $printPDF['report'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMDPS101ShiwakeDenpyoInput->Do_rollback();
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

            $objDr = $this->HMDPS101ShiwakeDenpyoInput->fncSaiban($strKbn, $strBusyoCD, $strNengetu);
            if (!$objDr['result']) {
                throw new \Exception($objDr['data']);
            }

            if ($objDr['row'] > 0) {
                $BANGO = $this->ClsComFncHMDPS->FncNv($objDr['data'][0]['BANGO']);
                if (strlen($BANGO) < 5) {
                    $length = 5 - strlen($this->ClsComFncHMDPS->FncNv($objDr['data'][0]['BANGO']));
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
                $objDr = $this->HMDPS101ShiwakeDenpyoInput->fncSaiban2($strKbn, $strBusyoCD, $strNengetu, $strProID, $objDr);
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

            $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSelShiwakeData($SYOHY_NO, $EDA_NO, $GYO_NO);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['NewNoTbl'] = $objDs['data'];
            //口座キー・必須摘要の名称を取得する(借方)
            $result['data']['LKOUBANTBL'] = array();
            $result['data']['RKOUBANTBL'] = array();
            if ($objDs['row'] > 0) {
                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncKouzaHittekiKashikata($result['data']['NewNoTbl'][0]['L_KAMOK_CD'], $result['data']['NewNoTbl'][0]['L_KOUMK_CD']);
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result['data']['LKOUBANTBL'] = $objDs['data'];
                //口座キー・必須摘要の名称を取得する(貸方)
                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncKouzaHittekiKashikata($result['data']['NewNoTbl'][0]['R_KAMOK_CD'], $result['data']['NewNoTbl'][0]['R_KOUMK_CD']);
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

    //'**********************************************************************
    //'処 理 名：表示されている仕訳をパターンとして登録する
    //'関 数 名：btnPatternTrk_Click
    //'処理説明：表示されている仕訳をパターンとして登録する
    //'**********************************************************************
    public function cmdEventPatternTrkClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $tranStartFlg = FALSE;
        $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
        try {
            $this->Session = $this->request->getSession();
            $hidPatternNO = isset($_POST['data']['hidPatternNO']) ? $_POST['data']['hidPatternNO'] : "";
            //トランザクション開始
            $this->HMDPS101ShiwakeDenpyoInput->Do_transaction();
            $tranStartFlg = TRUE;
            if ($hidPatternNO == "") {
                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncPatternTrkDispShiwake($_POST['data'], $this->Session->read('BusyoCD'));
            } else {
                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncUpdPatternTrk("1", $_POST['data'], $this->Session->read('BusyoCD'));
            }
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            //パターンの値を取得
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSelPattern($this->Session->read('BusyoCD'));
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['PatternTbl'] = $objDs['data'];
            //コミット
            $this->HMDPS101ShiwakeDenpyoInput->Do_commit();

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMDPS101ShiwakeDenpyoInput->Do_rollback();
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
        $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
        try {
            //トランザクション開始
            $this->HMDPS101ShiwakeDenpyoInput->Do_transaction();
            $tranStartFlg = TRUE;
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncPatternDelete($_POST['data']['hidPatternNO']);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            //コミット
            $this->HMDPS101ShiwakeDenpyoInput->Do_commit();

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMDPS101ShiwakeDenpyoInput->Do_rollback();
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
            'error' => ''
        );
        try {
            $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncSelectPattern($_POST['data']['ddlPatternSel']);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['PATTERNTBL'] = $objDs['data'];
            //口座キー・必須摘要の名称を取得する
            $result["data"]['LKOUBANTBL'] = array();
            $result["data"]['RKOUBANTBL'] = array();
            if ($objDs['row'] > 0) {
                //口座キー・必須摘要の名称を取得する(借方)
                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncKouzaHittekiKashikata($result['data']['PATTERNTBL'][0]['L_KAMOK_CD'], $result['data']['PATTERNTBL'][0]['L_KOUMK_CD']);
                if (!$objDs['result']) {
                    throw new \Exception($objDs['data']);
                }
                $result['data']['LKOUBANTBL'] = $objDs['data'];
                //口座キー・必須摘要の名称を取得する(貸方)
                $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncKouzaHittekiKashikata($result['data']['PATTERNTBL'][0]['R_KAMOK_CD'], $result['data']['PATTERNTBL'][0]['R_KOUMK_CD']);
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

    // 20240417 lqs INS S
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
            $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->FncGetNameValue($postData);
            $result['data']['NM'] = '';
            if ($objDs) {
                if (!$objDs['result']) {
                    throw new \Exception($objDs['error']);
                }
                if (count((array) $objDs['data']) > 0) {
                    // 証憑№を戻す
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
    // 20240417 lqs INS E
    public function fncGetKamokuMstValue()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->HMDPS101ShiwakeDenpyoInput = new HMDPS101ShiwakeDenpyoInput();
            //科目名取得
            $objDs = $this->ClsComFncHMDPS->FncGetKamokuMstValue($_POST['data']['KamokuCD'], $_POST['data']['KomokuCD'], FALSE);
            if (!$objDs['result']) {
                throw new \Exception($objDs['error']);
            }
            $result['data']['strKamokuNM'] = $objDs['intRtnCD'] == 1 ? $objDs['strKamokuNM'] : '';
            //口座キー・必須摘要の名称を取得する
            $objDs = $this->HMDPS101ShiwakeDenpyoInput->fncKouzaHittekiKashikata($_POST['data']['KamokuCD'], $_POST['data']['txtKomokuCD']);
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $result['data']['KOUBANTBL'] = $objDs['data'];

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
