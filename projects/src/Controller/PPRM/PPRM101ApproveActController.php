<?php
/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20180327			  Bug						   json_encodeエラー修正				YIN
 * 20180911			  Bug						   Bug #2857						YIN
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM101ApproveAct;
use App\Model\PPRM\Component\clsSQLforPrint;
use App\Model\PPRM\Component\ClsSyounin;
use App\Model\PPRM\Component\ClsProc;
use App\Model\PPRM\Component\ClsComFncPprm;
use App\Model\R4\Component\ClsComFnc;

//20170926 YIN INS S
//20180718 YIN DEL S
// App::uses('ClsComFncComponent', 'Controller/Component');
//20180718 YIN DEL E
//20170926 YIN INS E

class PPRM101ApproveActController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;

    // public $ClsComFnc;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    public $Session;


    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM101ApproveAct_layout';
        $this->render('/PPRM/PPRM101ApproveAct/index', $layout);
    }

    //'**********************************************************************
    //'処 理 名：更新日付取得
    //'関 数 名：getUpdDate
    //'引    数：なし
    //'戻 り 値：更新日付
    //'処理説明：店舗日締承認データの更新日付を取得する
    //'**********************************************************************
    public function fncgetUpdDate()
    {
        $postData = $_POST["data"]["request"];
        try {
            $PPRM101ApproveAct = new PPRM101ApproveAct();
            $result = $PPRM101ApproveAct->getUpdDate($postData);
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
    //'処 理 名：経理承認済みチェック
    //'関 数 名：fncchkKeiri
    //'引    数：なし
    //'戻 り 値：True：承認済み　False：未承認
    //'処理説明：経理承認済みかチェックする
    //'**********************************************************************
    public function fncchkKeiri()
    {
        $postData = $_POST["data"]["request"];
        try {
            $PPRM101ApproveAct = new PPRM101ApproveAct();
            $result = $PPRM101ApproveAct->chkKeiri($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result1 = $PPRM101ApproveAct->getUpdDate($postData);
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $result['data1'] = $result1['data'];

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);

    }

    //'**********************************************************************
    //'処 理 名：イメージファイルの有無確認
    //'関 数 名：fncjpgKakunin
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：イメージファイルの有無確認
    //'**********************************************************************
    public function fncjpgKakunin()
    {
        $postData = $_POST["data"]["request"];
        try {
            $PPRM101ApproveAct = new PPRM101ApproveAct();
            $result = $PPRM101ApproveAct->jpgKakunin($postData);
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
    //'処 理 名：店舗名取得
    //'関 数 名：fncgetTenpo
    //'処理説明：店舗名を取得する
    //'**********************************************************************
    public function fncgetTenpo()
    {
        $postData = $_POST["data"]["request"];
        try {
            $ClsComFncPprm = new ClsComFncPprm();
            $result = $ClsComFncPprm->FncGetBusyoMstValue_ppr($postData["TCD"], TRUE);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    // '**********************************************************************
    // '処 理 名：データ存在チェック
    // '関 数 名：SerchData
    // '引 数   ：なし
    // '戻 り 値：True：存在する　False：存在しない
    // '処理説明：店舗日締承認データに存在しているかチェックする
    // '**********************************************************************
    public function fncSerchData()
    {
        $postData = $_POST["data"]["request"];
        try {
            $PPRM101ApproveAct = new PPRM101ApproveAct();
            $result = $PPRM101ApproveAct->SerchData($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    // '**********************************************************************
    // '処 理 名：データ登録
    // '関 数 名：DataInsert
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：データを登録する
    // '**********************************************************************
    public function fncDataInsert()
    {
        $postData = $_POST["data"]["request"];
        try {
            $this->Session = $this->request->getSession();
            $postData['BusyoCD'] = $this->Session->read('BusyoCD');
            $postData['login_user'] = $this->Session->read('login_user');
            $postData['MachineNM'] = $this->request->clientIp();
            $PPRM101ApproveAct = new PPRM101ApproveAct();
            $DB_Conn = $PPRM101ApproveAct->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            //トランザクション開始
            $PPRM101ApproveAct->Do_transaction();
            $result = $PPRM101ApproveAct->DataInsert($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result = $PPRM101ApproveAct->getBusyoRNM($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //20180411 YIN INS S
            $result2 = $PPRM101ApproveAct->getUpdDate($postData);
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $result['data2'] = $result2['data'];
            //20180411 YIN INS E

            $PPRM101ApproveAct->Do_commit();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $PPRM101ApproveAct->Do_rollback();
        }
        if (isset($PPRM101ApproveAct->conn_orl)) {
            $PPRM101ApproveAct->Do_close();
            unset($PPRM101ApproveAct->conn_orl);
        }

        $this->fncReturn($result);

    }

    // '**********************************************************************
    // '処 理 名：店舗名（略式）取得
    // '関 数 名：fncgetBusyoRNM
    // '処理説明：店舗名（略式）を取得する
    // '**********************************************************************
    public function fncgetBusyoRNM()
    {
        $postData = array();
        try {
            $this->Session = $this->request->getSession();
            $postData['BusyoCD'] = $this->Session->read('BusyoCD');
            $PPRM101ApproveAct = new PPRM101ApproveAct();
            $result = $PPRM101ApproveAct->getBusyoRNM($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    //'***********************************************************************
    //'処 理 名：権限設定（初期値）
    //'関 数 名：PPRM203DCMonyKindInput_load
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：権限設定（初期値）
    //'***********************************************************************
    public function subSetEnabledOnPageLoad()
    {
        $ClsProc = new ClsProc();
        $txtTenpoCD = $_POST['data']['txtTenpoCD'];
        $this->Session = $this->request->getSession();
        $btnEnabled = $ClsProc->SubSetEnabled_OnPageLoad($this->Session->read('Sys_KB'), "PPRM101ApproveAct", $this->Session->read('login_user'), $txtTenpoCD);
        $result['result'] = true;
        $result['data'] = $btnEnabled;
        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：日締帳票のPDF生成
    // '関 数 名：fncsubPrintPDFJimu
    // '戻 り 値：
    // '処理説明：
    // '**********************************************************************
    public function fncsubPrintPDFJimu()
    {
        $postData = $_POST["data"]["request"];
        try {
            include_once "tcpdf/rpx_to_pdf.php";
            include_once 'tcpdf/rptHijimeIchiran.inc';
            include_once 'tcpdf/rptGenkinSuitochoEigyoKinshu.inc';
            include_once 'tcpdf/rptGenkinSuitochoEigyo.inc';
            include_once 'tcpdf/rptCardMeisaiNyukinIchiran.inc';
            include_once 'tcpdf/rptCardMeisaiFurikaeIchiran.inc';
            include_once 'tcpdf/rptShiireMeisaiIchiran.inc';
            include_once 'tcpdf/rptFurikaeMeisaiIchiran.inc';
            include_once 'tcpdf/rptSonotaMeisaiIchiran.inc';

            $rpx_file_names = array();
            $datas = array();
            $print = false;

            $clsSQL = new clsSQLforPrint();
            $DB_Conn = $clsSQL->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //トランザクション開始
            $clsSQL->Do_transaction();
            $blnUpdFlg = TRUE;
            //初期処理
            $this->Session = $this->request->getSession();
            $result = $clsSQL->subJimuInit("ApproveAct", $this->request->clientIp(), $postData['hidOpenDate'], $this->Session->read('login_user'), $this->Session->read('SyainNM'), $postData['HJMNo']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //ワーク伝票管理登録
            $result = $clsSQL->insWkAll();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //日締出力帳票一覧取得
            $result = $clsSQL->fncCreatHijimeIchiranSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {

                $rpx_file_names['rptHijimeIchiran'] = $data_fields_HijimeIchiran;

                $tmp_data = array();
                $tmp_data = $this->dealData($result, $postData);
                $tmp_data['data'] = $tmp_data['data'][0];
                $tmp_data['mode'] = "0";

                $datas['rptHijimeIchiran'] = $tmp_data;
                $print = true;

            }
            //現金出納帳(営業)金種表取得
            $result = $clsSQL->fncCreatEigyoKinshuDataSet();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count((array) $result['data']) > 0) {

                $rpx_file_names['rptGenkinSuitochoEigyoKinshu'] = $data_fields_GenkinSuitochoEigyoKinshu;

                $tmp_data = array();
                $ZANDAKA_SHIHEI_SUM = 0;
                $ZANDAKA_KOUKA_SUM = 0;
                $ZANDAKA_KOGITTE_SUM = 0;
                foreach ((array) $result['data'] as $key => $value) {
                    $ZANDAKA_SHIHEI_SUM += intval($value['ZANDAKA_SHIHEI']);
                    $ZANDAKA_KOUKA_SUM += intval($value['ZANDAKA_KOUKA']);
                    $ZANDAKA_KOGITTE_SUM += intval($value['ZANDAKA_KOGITTE']);
                    if ($result['data'][$key]['HJM_SYR_DTM'] != "" && $result['data'][$key]['HJM_SYR_DTM'] != null) {
                        $HJM_SYR_DTM = str_replace("/", "", $value['HJM_SYR_DTM']);
                        $HJM_SYR_DTM = str_replace(":", "", $HJM_SYR_DTM);
                        $result['data'][$key]['HJM_SYR_DTM'] = substr($HJM_SYR_DTM, 0, 4) . "年" . substr($HJM_SYR_DTM, 4, 2) . "月" . substr($HJM_SYR_DTM, 6, 2) . "日" . substr($HJM_SYR_DTM, 8, 3) . "時" . substr($HJM_SYR_DTM, 11, 2) . "分" . substr($HJM_SYR_DTM, 13, 2) . "秒";
                    }

                }
                foreach ((array) $result['data'] as $key1 => $value1) {
                    $result['data'][$key1]['ZANDAKA_SHIHEI_SUM'] = $ZANDAKA_SHIHEI_SUM;
                    $result['data'][$key1]['ZANDAKA_KOUKA_SUM'] = $ZANDAKA_KOUKA_SUM;
                    $result['data'][$key1]['ZANDAKA_KOGITTE_SUM'] = $ZANDAKA_KOGITTE_SUM;
                }
                $tmp_data = $this->dealData($result, $postData, 'rptGenkinSuitochoEigyoKinshu');
                $tmp_data['mode'] = "5";

                $datas['rptGenkinSuitochoEigyoKinshu'] = $tmp_data;
                $print = true;

            }
            //現金出納帳(営業)取得
            $result = $clsSQL->fncCreatEigyoGenkinSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {

                $rpx_file_names['rptGenkinSuitochoEigyo'] = $data_fields_GenkinSuitochoEigyo;

                $tmp_data = array();
                $KARIKATA_SUM = 0;
                $KASHIKATA_SUM = 0;
                $INP_DENPY_NO_SUM = $result['row'];
                foreach ((array) $result['data'] as $key => $value) {
                    $KARIKATA_SUM += intval($value['KARIKATA']);
                    $KASHIKATA_SUM += intval($value['KASHIKATA']);
                }
                foreach ((array) $result['data'] as $key1 => $value1) {
                    $result['data'][$key1]['KARIKATA_SUM'] = $KARIKATA_SUM;
                    $result['data'][$key1]['KASHIKATA_SUM'] = $KASHIKATA_SUM;
                    $result['data'][$key1]['INP_DENPY_NO_SUM'] = $INP_DENPY_NO_SUM;
                }
                $tmp_data = $this->dealData($result, $postData, 'rptGenkinSuitochoEigyo');
                $tmp_data['mode'] = "12";

                $datas['rptGenkinSuitochoEigyo'] = $tmp_data;
                $print = true;

            }
            //カード伝票明細一覧表_カード入金
            $result = $clsSQL->fncCreatCardMeisaiNyuSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {

                $rpx_file_names['rptCardMeisaiNyukinIchiran'] = $data_fields_CardMeisaiNyukinIchiran;

                $tmp_data = array();
                $KARIKATA_TOTAL = 0;
                $KASHIKATA_TOTAL = 0;
                $i = 0;
                $BANK_HD = $result['data'][0]['BANK_NM_HD'];
                foreach ((array) $result['data'] as $key => $value) {
                    if ($value['BANK_NM_HD'] == $BANK_HD) {
                        $KARIKATA_TOTAL += intval($value['KARIKATA']);
                        $KASHIKATA_TOTAL += intval($value['KASHIKATA']);
                    } else {
                        $BANK_HD = $value['BANK_NM_HD'];
                        $result['data'][$key - 1]['KARIKATA_SUM'] = $KARIKATA_TOTAL;
                        $result['data'][$key - 1]['KASHIKATA_SUM'] = $KASHIKATA_TOTAL;
                        $result['data'][$key - 1]['INP_DENPY_NO_SUM'] = $i;

                        $i = 0;
                        $KARIKATA_TOTAL = 0;
                        $KASHIKATA_TOTAL = 0;
                        $KARIKATA_TOTAL += intval($value['KARIKATA']);
                        $KASHIKATA_TOTAL += intval($value['KASHIKATA']);

                    }
                    $i++;
                }
                $result['data'][$key]['KARIKATA_SUM'] = $KARIKATA_TOTAL;
                $result['data'][$key]['KASHIKATA_SUM'] = $KASHIKATA_TOTAL;
                $result['data'][$key]['INP_DENPY_NO_SUM'] = $i;

                $tmp_data = $this->dealData($result, $postData, "rptCardMeisaiNyukinIchiran");
                $tmp_data['mode'] = "13";

                $datas['rptCardMeisaiNyukinIchiran'] = $tmp_data;
                $print = true;

            }
            //カード伝票明細一覧表_カード振替
            $result = $clsSQL->fncCreatCardMeisaiFriSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {

                $rpx_file_names['rptCardMeisaiFurikaeIchiran'] = $data_fields_CardMeisaiFurikaeIchiran;

                $tmp_data = array();
                $KARIKATA_TOTAL = 0;
                $KASHIKATA_TOTAL = 0;
                $i = 0;
                $BANK_HD = $result['data'][0]['BANK_NM_HD'];
                foreach ((array) $result['data'] as $key => $value) {
                    if ($value['BANK_NM_HD'] == $BANK_HD) {
                        $KARIKATA_TOTAL += intval($value['KARIKATA']);
                        $KASHIKATA_TOTAL += intval($value['KASHIKATA']);
                    } else {
                        $BANK_HD = $value['BANK_NM_HD'];
                        $result['data'][$key - 1]['KARIKATA_SUM'] = $KARIKATA_TOTAL;
                        $result['data'][$key - 1]['KASHIKATA_SUM'] = $KASHIKATA_TOTAL;
                        $result['data'][$key - 1]['INP_DENPY_NO_SUM'] = $i;

                        $i = 0;
                        $KARIKATA_TOTAL = 0;
                        $KASHIKATA_TOTAL = 0;
                        $KARIKATA_TOTAL += intval($value['KARIKATA']);
                        $KASHIKATA_TOTAL += intval($value['KASHIKATA']);

                    }
                    $i++;
                }
                $result['data'][$key]['KARIKATA_SUM'] = $KARIKATA_TOTAL;
                $result['data'][$key]['KASHIKATA_SUM'] = $KASHIKATA_TOTAL;
                $result['data'][$key]['INP_DENPY_NO_SUM'] = $i;
                $tmp_data = $this->dealData($result, $postData, "rptCardMeisaiFurikaeIchiran");
                $tmp_data['mode'] = "13";

                $datas['rptCardMeisaiFurikaeIchiran'] = $tmp_data;
                $print = true;

            }

            //仕入伝票明細一覧表
            $result = $clsSQL->fncCreatShiireMeisaiSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {

                $rpx_file_names['rptShiireMeisaiIchiran'] = $data_fields_ShiireMeisaiIchiran;

                $tmp_data = array();
                $KARIKATA_TOTAL = 0;
                $KASHIKATA_TOTAL = 0;
                $INP_DENPY_NO_TOTAL = $result['row'];
                foreach ((array) $result['data'] as $key => $value) {
                    $KARIKATA_TOTAL += intval($value['KARIKATA']);
                    $KASHIKATA_TOTAL += intval($value['KASHIKATA']);
                }
                foreach ((array) $result['data'] as $key1 => $value1) {
                    $result['data'][$key1]['KARIKATA_TOTAL'] = $KARIKATA_TOTAL;
                    $result['data'][$key1]['KASHIKATA_TOTAL'] = $KASHIKATA_TOTAL;
                    $result['data'][$key1]['INP_DENPY_NO_TOTAL'] = $INP_DENPY_NO_TOTAL;
                }
                $tmp_data = $this->dealData($result, $postData, 'rptShiireMeisaiIchiran');
                $tmp_data['mode'] = "12";

                $datas['rptShiireMeisaiIchiran'] = $tmp_data;
                $print = true;

            }
            //振替伝票明細一覧表
            $result = $clsSQL->fncCreatFurikaeMeisaiSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {

                $rpx_file_names['rptFurikaeMeisaiIchiran'] = $data_fields_FurikaeMeisaiIchiran;

                $tmp_data = array();
                $KEIJO_GK_TOTAL = 0;
                $INP_DENPY_NO_TOTAL = $result['row'];
                foreach ((array) $result['data'] as $key => $value) {
                    $KEIJO_GK_TOTAL += intval($value['KEIJO_GK']);
                }
                foreach ((array) $result['data'] as $key1 => $value1) {
                    $result['data'][$key1]['KEIJO_GK_TOTAL'] = $KEIJO_GK_TOTAL;
                    $result['data'][$key1]['INP_DENPY_NO_TOTAL'] = $INP_DENPY_NO_TOTAL;
                }
                $tmp_data = $this->dealData($result, $postData, 'rptFurikaeMeisaiIchiran');
                $tmp_data['mode'] = "12";

                $datas['rptFurikaeMeisaiIchiran'] = $tmp_data;
                $print = true;

            }

            //その他伝票明細一覧表
            $result = $clsSQL->fncCreatSonotaMeisaiSQL();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {

                $rpx_file_names['rptSonotaMeisaiIchiran'] = $data_fields_SonotaMeisaiIchiran;

                $tmp_data = array();
                $KARIKATA_TOTAL = 0;
                $KASHIKATA_TOTAL = 0;
                $i = 0;
                $BANK_HD = $result['data'][0]['BANK_HD'];
                foreach ((array) $result['data'] as $key => $value) {
                    if ($value['BANK_HD'] == $BANK_HD) {
                        $KARIKATA_TOTAL += intval($value['KARIKATA']);
                        $KASHIKATA_TOTAL += intval($value['KASHIKATA']);
                    } else {
                        $BANK_HD = $value['BANK_HD'];
                        $result['data'][$key - 1]['KARIKATA_TOTAL'] = $KARIKATA_TOTAL;
                        $result['data'][$key - 1]['KASHIKATA_TOTAL'] = $KASHIKATA_TOTAL;
                        $result['data'][$key - 1]['INP_DENPY_NO_TOTAL'] = $i;
                        $i = 0;
                        $KARIKATA_TOTAL = 0;
                        $KASHIKATA_TOTAL = 0;
                        $KARIKATA_TOTAL += intval($value['KARIKATA']);
                        $KASHIKATA_TOTAL += intval($value['KASHIKATA']);

                    }
                    $i++;
                }
                $result['data'][$key]['KARIKATA_TOTAL'] = $KARIKATA_TOTAL;
                $result['data'][$key]['KASHIKATA_TOTAL'] = $KASHIKATA_TOTAL;
                $result['data'][$key]['INP_DENPY_NO_TOTAL'] = $i;

                $tmp_data = $this->dealData($result, $postData, 'rptSonotaMeisaiIchiran');
                $tmp_data['mode'] = "13";

                $datas['rptSonotaMeisaiIchiran'] = $tmp_data;
                $print = true;

            }

            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf();

            //20180911 YIN DEL S
            // $clsSQL -> Do_commit();
            //20180911 YIN DEL E

            $blnUpdFlg = FALSE;

            if ($print) {
                $result = array(
                    'result' => true,
                    'data' => 'data',
                    'flag' => 'true',
                    'msg' => 'true',
                    'reports' => $pdfPath
                );
            } else {
                $result = array(
                    'result' => true,
                    'data' => 'nodata',
                );
            }
            //20180911 YIN INS S
            $finalRes = $clsSQL->subJimuFinal();
            if (!$finalRes['result']) {
                throw new \Exception($finalRes['data']);
            }
            $clsSQL->Do_commit();
            //20180911 YIN INS E

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $clsSQL->Do_rollback();
        }
        if (isset($clsSQL->conn_orl)) {
            $clsSQL->Do_close();
            unset($clsSQL->conn_orl);
        }
        $this->fncReturn($result);

    }

    // '**********************************************************************
    // '処 理 名：整備日報のPDF生成
    // '関 数 名：subPrintPDFSeibi
    // '戻 り 値：
    // '処理説明：
    // '**********************************************************************
    public function fncsubPrintPDFSeibi()
    {
        $postData = $_POST["data"]["request"];
        try {
            include_once "tcpdf/rpx_to_pdf.php";
            include_once 'tcpdf/rptGaichuKensyuIchiran.inc';
            include_once 'tcpdf/rptSeibinippoMaineMain.inc';
            include_once 'tcpdf/rptUriageMeisaiIchiranMain.inc';

            $clsComFnc = new ClsComFnc();

            $rpx_file_names = array();
            $datas = array();
            $print = false;

            $clsSQL = new clsSQLforPrint();
            //初期処理
            $this->Session = $this->request->getSession();
            $clsSQL->subSeibiInit($this->Session->read('login_user'), $this->Session->read('SyainNM'), $postData['TCD'], $postData['HJMDT']);
            //整備日報（日計）/売上明細一覧
            $arr = array();
            $objdscom = $clsSQL->fncCreatSeibiNippoSQL(0);
            if (!$objdscom['result']) {
                throw new \Exception($objdscom['data']);
            }
            if ($objdscom['row'] > 0) {
                $arr = $this->GetSeibiData($clsSQL, $objdscom);
                if (!$arr['result']) {
                    throw new \Exception($arr['data']);
                }

                $rpx_file_names['rptSeibinippoMaineMain'] = $data_fields_rptSeibinippoMaineMain;

                $tmp_data = array();
                $tmp = array();
                $data = $arr['data'];
                array_push($tmp, $data);
                $tmp_data['data'] = $tmp;
                $tmp_data['mode'] = "15";

                $datas['rptSeibinippoMaineMain'] = $tmp_data;
                $print = true;

            }
            //整備日報（月計）
            $arr = array();
            $result = $clsSQL->fncCreatSeibiNippoSQL(1);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                $arr = $this->GetSeibiData($clsSQL, $result);
                if (!$arr['result']) {
                    throw new \Exception($arr['data']);
                }

                $rpx_file_names['rptSeibinippoyujiMaineMain'] = $data_fields_rptSeibinippoMaineMain;

                $tmp_data = array();
                $tmp = array();
                $data = $arr['data'];
                array_push($tmp, $data);
                $tmp_data['data'] = $tmp;
                $tmp_data['mode'] = "15";

                $datas['rptSeibinippoyujiMaineMain'] = $tmp_data;
                $print = true;

            }

            $arr = array();

            //売上明細一覧表
            if ($objdscom['row'] > 0) {
                $arr = $this->GetMeisaiData($clsSQL, $objdscom);
                if (!$arr['result']) {
                    throw new \Exception($arr['data']);
                }

                $rpx_file_names['rptUriageMeisaiIchiranMain'] = $data_fields_rptUriageMeisaiIchiranMain;
                $tmp_data = array();
                $tmp_data['data'][0] = $arr['data'];
                $tmp_data['mode'] = "14";
                $datas['rptUriageMeisaiIchiranMain'] = $tmp_data;
                $print = true;

            }

            //外注検収一覧表
            $result = $clsSQL->fncGaichuKensyuIchiran();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if ($result['row'] > 0) {

                $rpx_file_names['rptGaichuKensyuIchiran'] = $data_fields_GaichuKensyuIchiran;

                $URG_GK_SUM_TOTAL = 0;
                $HTU_GK_SUM_TOTAL = 0;
                $KEU_GK_SUM_TOTAL = 0;
                $GTU_SHZ_GKU_TOTAL = 0;
                $TOT_SHR_GKU_TOTAL = 0;
                $GTU_KEU_NO_TOTAL = 0;
                $GTU_KEU_NO_TOTAL = $result['row'];
                foreach ((array) $result['data'] as $key => $value) {
                    $URG_GK_SUM_TOTAL += intval($value['URG_GK_SUM']);
                    $HTU_GK_SUM_TOTAL += intval($value['HTU_GK_SUM']);
                    $KEU_GK_SUM_TOTAL += intval($value['KEU_GK_SUM']);
                    $GTU_SHZ_GKU_TOTAL += intval($value['GTU_SHZ_GKU']);
                    $TOT_SHR_GKU_TOTAL += intval($value['TOT_SHR_GKU']);
                }
                foreach ((array) $result['data'] as $key1 => $value1) {
                    $strTorokuNo = "";
                    if ($result['data'][$key1]['RIKUJI_NM'] != "" && $result['data'][$key1]['RIKUJI_NM'] != null) {
                        $strTorokuNo .= $result['data'][$key1]['RIKUJI_NM'];
                        $strTorokuNo .= $clsComFnc->FncNv($result['data'][$key1]['VCLRGTNO_SYU'], " ");
                        $VCLRGTNO_KANA = mb_convert_kana($clsComFnc->FncNv($result['data'][$key1]['VCLRGTNO_KANA'], " "), "Hc");
                        $VCLRGTNO_KANA = $this->dbc2Sbc($VCLRGTNO_KANA);
                        $strTorokuNo .= $VCLRGTNO_KANA;
                        $strTorokuNo .= $clsComFnc->FncNv($result['data'][$key1]['VCLRGTNO_REN'], " ");
                        //20171010 YIN UPD S
                        // $result['data'][$key1]['TOUROKU_NO'] = $strTorokuNo;
                    }
                    $result['data'][$key1]['TOUROKU_NO'] = $strTorokuNo;
                    //20171010 YIN UPD E
                    $result['data'][$key1]['URG_GK_SUM_TOTAL'] = $URG_GK_SUM_TOTAL;
                    $result['data'][$key1]['HTU_GK_SUM_TOTAL'] = $HTU_GK_SUM_TOTAL;
                    $result['data'][$key1]['KEU_GK_SUM_TOTAL'] = $KEU_GK_SUM_TOTAL;
                    $result['data'][$key1]['GTU_SHZ_GKU_TOTAL'] = $GTU_SHZ_GKU_TOTAL;
                    $result['data'][$key1]['TOT_SHR_GKU_TOTAL'] = $TOT_SHR_GKU_TOTAL;
                    $result['data'][$key1]['GTU_KEU_NO_TOTAL'] = $GTU_KEU_NO_TOTAL;
                }
                $tmp_data = array();
                $tmp = array();
                $data = $result['data'];
                array_push($tmp, $data);
                $tmp_data['data'] = $tmp;
                $tmp_data['mode'] = "3";

                $datas['rptGaichuKensyuIchiran'] = $tmp_data;
                $print = true;

            }
            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf();

            if ($print) {
                $result = array(
                    'result' => true,
                    'data' => 'data',
                    'flag' => 'true',
                    'msg' => 'true',
                    'reports' => $pdfPath
                );
            } else {
                $result = array(
                    'result' => true,
                    'data' => 'nodata',
                );
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);

    }

    // * 将字符转换成unicode
    // * @param string $char 必须是UTF-8字符
    // * @return int
    // public function char2Unicode($char)
    // {
    // 	switch (strlen($char)) {
    // 		case 1:
    // 			return ord($char);
    // 		case 2:
    // 			return (ord($char{ 1}) & 63) | ((ord($char{ 0}) & 31) << 6);
    // 		case 3:
    // 			return (ord($char{ 2}) & 63) | ((ord($char{ 1}) & 63) << 6) | ((ord($char{ 0}) & 15) << 12);
    // 		case 4:
    // 			return (ord($char{ 3}) & 63) | ((ord($char{ 2}) & 63) << 6) | ((ord($char{ 1}) & 63) << 12) | ((ord($char{ 0}) & 7) << 18);
    // 		default:
    // 			trigger_error('Character is not UTF-8!', E_USER_WARNING);
    // 			return false;
    // 	}
    // }

    // * 将unicode转换成字符
    // * @param int $unicode
    // * @return string UTF-8字符
    public function unicode2Char($unicode)
    {
        if ($unicode < 128)
            return chr($unicode);
        if ($unicode < 2048)
            return chr(($unicode >> 6) + 192) . chr(($unicode & 63) + 128);
        if ($unicode < 65536)
            return chr(($unicode >> 12) + 224) . chr((($unicode >> 6) & 63) + 128) . chr(($unicode & 63) + 128);
        if ($unicode < 2097152)
            return chr(($unicode >> 18) + 240) . chr((($unicode >> 12) & 63) + 128) . chr((($unicode >> 6) & 63) + 128) . chr(($unicode & 63) + 128);
        return false;
    }

    // * 半角转全角
    // * @param string $str
    // * @return string
    public function dbc2Sbc($str)
    {
        // return preg_replace('/[\x{0020}\x{0020}-\x{7e}]/ue', '($unicode=$this->char2Unicode(\'\0\')) == 0x0020 ? $this->unicode2Char（0x3000） : (($code=$unicode+0xfee0) > 256 ? $this->unicode2Char($code) : chr($code))', $str);
        return preg_replace_callback(
            '/[\x{0020}\x{0020}-\x{7e}]/u',
            function () {
                return '($unicode=$this->char2Unicode(\'\0\')) == 0x0020 ? $this->unicode2Char（0x3000） : (($code=$unicode+0xfee0) > 256 ? $this->unicode2Char($code) : chr($code))';
            },
            $str
        );
    }

    public function dealData($objds, $postData, $rpx_file = null)
    {
        $syounin = new ClsSyounin();
        $syouninRes = $syounin->SyouninSQL(substr($postData['HJMNo'], 0, 3), "1", $postData['HJMNo']);
        if (!$syouninRes['result']) {
            throw new \Exception($syouninRes['data']);
        }

        $busyoNM1 = "";
        $busyoNM2 = "";
        $busyoNM3 = "";
        $busyoNM4 = "";
        $date1 = "";
        $date2 = "";
        $date3 = "";
        $date4 = "";
        $strTantouFlg = NULL;
        $Text = NULL;
        $Circle = NULL;
        $strCreDate = NULL;
        $strCreNAME = NULL;
        if (count((array) $syouninRes['data']) > 0) {
            $strKeiriFlg = $syouninRes['data'][0]['KEIRI_SNN_FLG'];
            if ($syouninRes['data'][0]['KEIRI_SNN_BUSYO_CD'] != "") {
                $getNameRes = $syounin->getBusyoRNM($syouninRes['data'][0]['KEIRI_SNN_BUSYO_CD']);
                if ($getNameRes['result'] == false) {
                    throw new \Exception($getNameRes['data']);
                }
                $busyoNM1 = $getNameRes['data'][0]['BUSYORNM'];
            }
            if ($syouninRes['data'][0]['KEIRI_SNN_DATE'] != "") {
                $date1 = $syounin->chgWAREKI(str_replace("/", "", $syouninRes['data'][0]['KEIRI_SNN_DATE']));
            }
            $keiri = array(
                'text1' => $busyoNM1,
                'text2' => $date1,
                'text3' => $syouninRes['data'][0]['KEIRI_SNN_TANTO_NM']
            );
            $strTenchoFlg = $syouninRes['data'][0]['TENCHO_SNN_FLG'];
            if ($syouninRes['data'][0]['TENCHO_SNN_BUSYO_CD'] != "") {
                $getNameRes = $syounin->getBusyoRNM($syouninRes['data'][0]['TENCHO_SNN_BUSYO_CD']);
                if (!$getNameRes['result']) {
                    throw new \Exception($getNameRes['data']);
                }
                $busyoNM2 = $getNameRes['data'][0]['BUSYORNM'];
            }
            if ($syouninRes['data'][0]['TENCHO_SNN_DATE'] != "") {
                $date2 = $syounin->chgWAREKI(str_replace("/", "", $syouninRes['data'][0]['TENCHO_SNN_DATE']));
            }
            $tencho = array(
                'text1' => $busyoNM2,
                'text2' => $date2,
                'text3' => $syouninRes['data'][0]['TENCHO_SNN_TANTO_NM']
            );
            $strKachoFlg = $syouninRes['data'][0]['KACHO_SNN_FLG'];
            if ($syouninRes['data'][0]['KACHO_SNN_BUSYO_CD'] != "") {
                $getNameRes = $syounin->getBusyoRNM($syouninRes['data'][0]['KACHO_SNN_BUSYO_CD']);
                if (!$getNameRes['result']) {
                    throw new \Exception($getNameRes['data']);
                }
                $busyoNM3 = $getNameRes['data'][0]['BUSYORNM'];
            }
            if ($syouninRes['data'][0]['KACHO_SNN_DATE'] != "") {
                $date3 = $syounin->chgWAREKI(str_replace("/", "", $syouninRes['data'][0]['KACHO_SNN_DATE']));
            }
            $kacho = array(
                'text1' => $busyoNM3,
                'text2' => $date3,
                'text3' => $syouninRes['data'][0]['KACHO_SNN_TANTO_NM']
            );
            $strTantouFlg = $syouninRes['data'][0]['TAN_SNN_FLG'];
            if ($syouninRes['data'][0]['TAN_SNN_BUSYO_CD'] != "") {
                $getNameRes = $syounin->getBusyoRNM($syouninRes['data'][0]['TAN_SNN_BUSYO_CD']);
                if (!$getNameRes['result']) {
                    throw new \Exception($getNameRes['data']);
                }
                $busyoNM4 = $getNameRes['data'][0]['BUSYORNM'];
            }
            if ($syouninRes['data'][0]['TAN_SNN_DATE'] != "") {
                $date4 = $syounin->chgWAREKI(str_replace("/", "", $syouninRes['data'][0]['TAN_SNN_DATE']));
            }
            $tantou = array(
                'text1' => $busyoNM4,
                'text2' => $date4,
                'text3' => $syouninRes['data'][0]['TAN_SNN_TANTO_NM']
            );
            $strCreDate = $syouninRes['data'][0]['CRE_DTM'];
            $strCreNAME = $syouninRes['data'][0]['CRE_NM'];

            $X = array(
                '1' => 207,
                '2' => 228,
                '3' => 249,
                '4' => 270
            );
            $Circle = array(
                'X' => $X,
                'Y' => 33.5,
                'R' => 8.5
            );
            $Text = array();
            if ($strKeiriFlg == "1") {
                $Text['1'] = $keiri;
            } else {
                $Circle['X']['1'] = 20000;
            }
            if ($strTenchoFlg == "1") {
                $Text['2'] = $tencho;
            } else {
                $Circle['X']['2'] = 20000;
            }
            if ($strKachoFlg == "1") {
                $Text['3'] = $kacho;
            } else {
                $Circle['X']['3'] = 20000;
            }
            if ($strTantouFlg == "1") {
                $Text['4'] = $tantou;
            } else {
                $Circle['X']['4'] = 20000;
            }
        }
        $tmp = array();
        if ($rpx_file != null) {
            if ($rpx_file == "rptGenkinSuitochoEigyoKinshu") {
                foreach ($objds['data'] as $key => $value) {
                    $arr = array();
                    $arr = $value;
                    if ($strTantouFlg == "1") {
                        $arr['CreDate'] = $strCreDate;
                        $arr['CreName'] = $strCreNAME;
                    }
                    if ($key == 0) {
                        $arr['Text'] = $Text;
                        $arr['Circle'] = $Circle;
                    }
                    array_push($tmp, $arr);
                }
            }
            if ($rpx_file == "rptCardMeisaiNyukinIchiran" || $rpx_file == "rptCardMeisaiFurikaeIchiran") {
                $BANK_HD = $objds['data'][0]['BANK_NM_HD'];
                $j = 0;

                foreach ($objds['data'] as $key => $value) {
                    $arr = array();
                    $arr = $value;
                    if ($strTantouFlg == "1") {
                        $arr['CreDate'] = $strCreDate;
                        $arr['CreName'] = $strCreNAME;
                    }
                    if ($value['BANK_NM_HD'] == $BANK_HD) {
                        if (($j % 8) == 0) {
                            $arr['Text'] = $Text;
                            $arr['Circle'] = $Circle;
                        }
                        $j++;
                    } else {
                        $j = 0;
                        $BANK_HD = $value['BANK_NM_HD'];
                        $arr['Text'] = $Text;
                        $arr['Circle'] = $Circle;
                        $j++;
                    }
                    array_push($tmp, $arr);
                }

            }

            if ($rpx_file == 'rptSonotaMeisaiIchiran') {
                $BANK_HD = $objds['data'][0]['BANK_HD'];
                $j = 0;

                foreach ($objds['data'] as $key => $value) {
                    $arr = array();
                    $arr = $value;
                    if ($strTantouFlg == "1") {
                        $arr['CreDate'] = $strCreDate;
                        $arr['CreName'] = $strCreNAME;
                    }
                    if ($value['BANK_HD'] == $BANK_HD) {
                        if (($j % 8) == 0) {
                            $arr['Text'] = $Text;
                            $arr['Circle'] = $Circle;
                        }
                        $j++;
                    } else {
                        $j = 0;
                        $BANK_HD = $value['BANK_HD'];
                        $arr['Text'] = $Text;
                        $arr['Circle'] = $Circle;
                        $j++;

                    }
                    array_push($tmp, $arr);
                }

            }
            if ($rpx_file == 'rptShiireMeisaiIchiran' || $rpx_file == 'rptGenkinSuitochoEigyo') {
                $j = 0;
                foreach ($objds['data'] as $key => $value) {
                    $arr = array();
                    $arr = $value;
                    if ($strTantouFlg == "1") {
                        $arr['CreDate'] = $strCreDate;
                        $arr['CreName'] = $strCreNAME;
                    }
                    if (($j % 8) == 0) {
                        $arr['Text'] = $Text;
                        $arr['Circle'] = $Circle;
                    }
                    $j++;
                    array_push($tmp, $arr);
                }

            }
            if ($rpx_file == 'rptFurikaeMeisaiIchiran') {
                $j = 0;
                foreach ($objds['data'] as $key => $value) {
                    $arr = array();
                    $arr = $value;
                    if ($strTantouFlg == "1") {
                        $arr['CreDate'] = $strCreDate;
                        $arr['CreName'] = $strCreNAME;
                    }
                    if (($j % 6) == 0) {
                        $arr['Text'] = $Text;
                        $arr['Circle'] = $Circle;
                    }
                    $j++;
                    array_push($tmp, $arr);
                }
            }
        } else {
            foreach ($objds['data'] as $key => $value) {
                $arr = array();
                $arr = $value;
                if ($strTantouFlg == "1") {
                    $arr['CreDate'] = $strCreDate;
                    $arr['CreName'] = $strCreNAME;
                }
                $arr['Text'] = $Text;
                $arr['Circle'] = $Circle;
                array_push($tmp, $arr);
            }
        }

        $tmp_data = array();
        $tmp_data['data'][0] = $tmp;
        return $tmp_data;
    }

    // '**********************************************************************
    // '処 理 名：店舗日締承認データを更新する
    // '関 数 名：UpdateSyounin
    // '引 数   ：種類（1:経理担当,2:店長,3:課長,4:担当）、状態（0:未承認、1:承認）
    // '戻 り 値：なし
    // '処理説明：承認する（店舗日締承認データの各承認フラグを1にする）
    // '        ：未承認にする（店舗日締承認データの各承認フラグを0にする）
    // '**********************************************************************
    public function fncUpdateSyounin()
    {
        $postData = $_POST["data"]["request"];
        try {
            $this->Session = $this->request->getSession();
            $postData['BusyoCD'] = $this->Session->read('BusyoCD');
            $postData['login_user'] = $this->Session->read('login_user');
            $postData['MachineNM'] = $this->request->clientIp();
            $postData['SyainNM'] = $this->Session->read('SyainNM');
            $PPRM101ApproveAct = new PPRM101ApproveAct();
            $DB_Conn = $PPRM101ApproveAct->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            //トランザクション開始
            $PPRM101ApproveAct->Do_transaction();
            $result = $PPRM101ApproveAct->UpdateSyounin($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //20180326 YIN INS S
            $result['data'] = "";
            //20180326 YIN INS E

            $PPRM101ApproveAct->Do_commit();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $PPRM101ApproveAct->Do_rollback();
        }
        if (isset($PPRM101ApproveAct->conn_orl)) {
            $PPRM101ApproveAct->Do_close();
            unset($PPRM101ApproveAct->conn_orl);
        }

        $this->fncReturn($result);

    }

    public function GetSeibiData($clsSQL, $result)
    {
        $arr = array();

        $arr['rptSeibinippoMaine'] = $result['data'];
        $tenpocd = $result['data'][0]['TENPO_CD'];
        $updstr = $result['data'][0]['URIAGEDT_STA'];
        $updend = $result['data'][0]['URIAGEDT_END'];
        try {
            //有償売上分
            $result = $clsSQL->fncCreatSeibiYushoSQL($tenpocd, $updstr, $updend);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                $RESULTKBN = $result['data'][0]['RESULTKBN'];
                $SUB_NEB_TTL = 0;
                $SUB_NEB_PRF = 0;
                $SUB_GAC_PRF = 0;
                $SUB_BUH_PRF = 0;
                $SUB_ARARI_PRF = 0;
                $SUB_KOSEIHI = 0;
                $SUB_NEB_PER_DAI = 0;
                $SUB_GEN_PER_DAI = 0;
                $SUB_URI_PER_DAI = 0;
                $SUB_ARARI_PRF_DAI = 0;
                $SUB_ARARI_TTL = 0;
                $SUB_URI_TTL = 0;
                $SUB_GEN_TTL = 0;
                $SUB_URI_SUBTTL = 0;
                $SUB_GAC_GEN = 0;
                $SUB_GAC_URI = 0;
                $SUB_GEN_SUBTTL = 0;
                $SUB_DAISU = 0;
                $SUB_KOC_URI = 0;
                $SUB_BUH_URI = 0;
                $SUB_KOC_GEN = 0;
                $SUB_BUH_GEN = 0;
                $TTL_NEB_TTL = 0;
                $TTL_GEN_TTL = 0;
                $ARARI_PRF_KOC = 0;
                $TTL_NEB_PRF = 0;
                $TTL_GAC_PRF = 0;
                $TTL_BUH_PRF = 0;
                $TTL_ARARI_PRF = 0;
                $TTL_KOSEIHI = 0;
                $TTL_NEB_PER_DAI = 0;
                $TTL_GEN_PER_DAI = 0;
                $TTL_URI_PER_DAI = 0;
                $TTL_ARARI_PER_DAI = 0;
                $TTL_ARARI_TTL = 0;
                $TTL_URI_TTL = 0;
                $TTL_URI_SUBTTL = 0;
                $TTL_GAC_GEN = 0;
                $TTL_GAC_URI = 0;
                $TTL_GEN_SUBTTL = 0;
                $TTL_DAISU = 0;
                $TTL_KOC_URI = 0;
                $TTL_BUH_URI = 0;
                $TTL_KOC_GEN = 0;
                $TTL_BUH_GEN = 0;
                $ARARI_TTL_KOC = 0;
                $ARARI_TTL_BUH = 0;
                $ARARI_TTL_GAC = 0;
                $ARARI_TTL_URI = 0;
                $ARARI_PRF_BUH = 0;
                $ARARI_PRF_GAC = 0;
                $ARARI_PRF_URI = 0;

                foreach ($result['data'] as $key => $value) {
                    if ($value['RESULTKBN'] != $RESULTKBN) {
                        $SUB_KOSEIHI = 0;
                        $SUB_ARARI_PRF = 0;
                        $SUB_NEB_PRF = 0;
                        $SUB_GAC_PRF = 0;
                        $SUB_BUH_PRF = 0;
                        $SUB_ARARI_PRF_DAI = 0;
                        $SUB_NEB_PER_DAI = 0;
                        $SUB_GEN_PER_DAI = 0;
                        $SUB_URI_PER_DAI = 0;
                        $TTL_URI = $result['data'][$key - 1]['TTL_URI'];

                        //構成比
                        if ($TTL_URI != 0) {
                            $SUB_KOSEIHI = ($SUB_URI_TTL / $TTL_URI) * 100;
                        }
                        //粗利益率
                        if ($SUB_URI_TTL != 0) {
                            $SUB_ARARI_PRF = (($SUB_URI_TTL - $SUB_GEN_TTL) / $SUB_URI_TTL) * 100;
                        }
                        //部品利益率
                        if ($SUB_BUH_URI != 0) {
                            $SUB_BUH_PRF = (($SUB_BUH_URI - $SUB_BUH_GEN) / $SUB_BUH_URI) * 100;
                        }
                        //外注利益率
                        if ($SUB_GAC_URI != 0) {
                            $SUB_GAC_PRF = (($SUB_GAC_URI - $SUB_GAC_GEN) / $SUB_GAC_URI) * 100;
                        }
                        //値引率
                        if ($SUB_URI_TTL != 0) {
                            $SUB_NEB_PRF = ($SUB_NEB_TTL / $SUB_URI_TTL) * 100;
                        }

                        if ($SUB_DAISU != 0) {
                            //売上台当り
                            $SUB_URI_PER_DAI = $SUB_URI_TTL / $SUB_DAISU;
                            //原価台当り
                            $SUB_GEN_PER_DAI = $SUB_GEN_TTL / $SUB_DAISU;
                            //粗利台当り
                            $SUB_ARARI_PRF_DAI = $SUB_ARARI_TTL / $SUB_DAISU;
                            //値引台当り
                            $SUB_NEB_PER_DAI = $SUB_NEB_TTL / $SUB_DAISU;
                        }

                        $result['data'][$key - 1]['SUB_NEB_TTL'] = $SUB_NEB_TTL;
                        $result['data'][$key - 1]['SUB_ARARI_TTL'] = $SUB_ARARI_TTL;
                        $result['data'][$key - 1]['SUB_URI_TTL'] = $SUB_URI_TTL;
                        $result['data'][$key - 1]['SUB_GEN_TTL'] = $SUB_GEN_TTL;
                        $result['data'][$key - 1]['SUB_URI_SUBTTL'] = $SUB_URI_SUBTTL;
                        $result['data'][$key - 1]['SUB_GAC_GEN'] = $SUB_GAC_GEN;
                        $result['data'][$key - 1]['SUB_GAC_URI'] = $SUB_GAC_URI;
                        $result['data'][$key - 1]['SUB_GEN_SUBTTL'] = $SUB_GEN_SUBTTL;
                        $result['data'][$key - 1]['SUB_DAISU'] = $SUB_DAISU;
                        $result['data'][$key - 1]['SUB_KOC_URI'] = $SUB_KOC_URI;
                        $result['data'][$key - 1]['SUB_BUH_URI'] = $SUB_BUH_URI;
                        $result['data'][$key - 1]['SUB_KOC_GEN'] = $SUB_KOC_GEN;
                        $result['data'][$key - 1]['SUB_BUH_GEN'] = $SUB_BUH_GEN;

                        $result['data'][$key - 1]['SUB_KOSEIHI'] = $SUB_KOSEIHI;
                        $result['data'][$key - 1]['SUB_ARARI_PRF'] = $SUB_ARARI_PRF;
                        $result['data'][$key - 1]['SUB_NEB_PRF'] = $SUB_NEB_PRF;
                        $result['data'][$key - 1]['SUB_GAC_PRF'] = $SUB_GAC_PRF;
                        $result['data'][$key - 1]['SUB_BUH_PRF'] = $SUB_BUH_PRF;
                        $result['data'][$key - 1]['SUB_ARARI_PRF_DAI'] = $SUB_ARARI_PRF_DAI;
                        $result['data'][$key - 1]['SUB_NEB_PER_DAI'] = $SUB_NEB_PER_DAI;
                        $result['data'][$key - 1]['SUB_GEN_PER_DAI'] = $SUB_GEN_PER_DAI;
                        $result['data'][$key - 1]['SUB_URI_PER_DAI'] = $SUB_URI_PER_DAI;

                        $SUB_NEB_TTL = 0;
                        $SUB_ARARI_TTL = 0;
                        $SUB_URI_TTL = 0;
                        $SUB_GEN_TTL = 0;
                        $SUB_URI_SUBTTL = 0;
                        $SUB_GAC_GEN = 0;
                        $SUB_GAC_URI = 0;
                        $SUB_GEN_SUBTTL = 0;
                        $SUB_DAISU = 0;
                        $SUB_KOC_URI = 0;
                        $SUB_BUH_URI = 0;
                        $SUB_KOC_GEN = 0;
                        $SUB_BUH_GEN = 0;

                        $RESULTKBN = $result['data'][$key]['RESULTKBN'];

                        $SUB_NEB_TTL += intval($value['NEB_TTL']);
                        $SUB_ARARI_TTL += intval($value['ARARI_TTL']);
                        $SUB_URI_TTL += intval($value['URI_TTL']);
                        $SUB_GEN_TTL += intval($value['GEN_TTL']);
                        $SUB_URI_SUBTTL += intval($value['URI_SUBTTL']);
                        $SUB_GAC_GEN += intval($value['GAC_GEN']);
                        $SUB_GAC_URI += intval($value['GAC_URI']);
                        $SUB_GEN_SUBTTL += intval($value['GEN_SUBTTL']);
                        $SUB_DAISU += intval($value['DAISU']);
                        $SUB_KOC_URI += intval($value['KOC_URI']);
                        $SUB_BUH_URI += intval($value['BUH_URI']);
                        $SUB_KOC_GEN += intval($value['KOC_GEN']);
                        $SUB_BUH_GEN += intval($value['BUH_GEN']);

                    } else {
                        $SUB_NEB_TTL += intval($value['NEB_TTL']);
                        $SUB_ARARI_TTL += intval($value['ARARI_TTL']);
                        $SUB_URI_TTL += intval($value['URI_TTL']);
                        $SUB_GEN_TTL += intval($value['GEN_TTL']);
                        $SUB_URI_SUBTTL += intval($value['URI_SUBTTL']);
                        $SUB_GAC_GEN += intval($value['GAC_GEN']);
                        $SUB_GAC_URI += intval($value['GAC_URI']);
                        $SUB_GEN_SUBTTL += intval($value['GEN_SUBTTL']);
                        $SUB_DAISU += intval($value['DAISU']);
                        $SUB_KOC_URI += intval($value['KOC_URI']);
                        $SUB_BUH_URI += intval($value['BUH_URI']);
                        $SUB_KOC_GEN += intval($value['KOC_GEN']);
                        $SUB_BUH_GEN += intval($value['BUH_GEN']);

                    }

                    $TTL_NEB_TTL += intval($value['NEB_TTL']);
                    $TTL_ARARI_TTL += intval($value['ARARI_TTL']);
                    $TTL_URI_TTL += intval($value['URI_TTL']);
                    $TTL_GEN_TTL += intval($value['GEN_TTL']);
                    $TTL_URI_SUBTTL += intval($value['URI_SUBTTL']);
                    $TTL_GAC_GEN += intval($value['GAC_GEN']);
                    $TTL_GAC_URI += intval($value['GAC_URI']);
                    $TTL_GEN_SUBTTL += intval($value['GEN_SUBTTL']);
                    $TTL_DAISU += intval($value['DAISU']);
                    $TTL_KOC_URI += intval($value['KOC_URI']);
                    $TTL_BUH_URI += intval($value['BUH_URI']);
                    $TTL_KOC_GEN += intval($value['KOC_GEN']);
                    $TTL_BUH_GEN += intval($value['BUH_GEN']);

                }

                $SUB_KOSEIHI = 0;
                $SUB_ARARI_PRF = 0;
                $SUB_NEB_PRF = 0;
                $SUB_GAC_PRF = 0;
                $SUB_BUH_PRF = 0;
                $SUB_ARARI_PRF_DAI = 0;
                $SUB_NEB_PER_DAI = 0;
                $SUB_GEN_PER_DAI = 0;
                $SUB_URI_PER_DAI = 0;
                $TTL_URI = $result['data'][$key]['TTL_URI'];

                //構成比
                if ($TTL_URI != 0) {
                    $SUB_KOSEIHI = ($SUB_URI_TTL / $TTL_URI) * 100;
                }
                //粗利益率
                if ($SUB_URI_TTL != 0) {
                    $SUB_ARARI_PRF = (($SUB_URI_TTL - $SUB_GEN_TTL) / $SUB_URI_TTL) * 100;
                }
                //部品利益率
                if ($SUB_BUH_URI != 0) {
                    $SUB_BUH_PRF = (($SUB_BUH_URI - $SUB_BUH_GEN) / $SUB_BUH_URI) * 100;
                }
                //外注利益率
                if ($SUB_GAC_URI != 0) {
                    $SUB_GAC_PRF = (($SUB_GAC_URI - $SUB_GAC_GEN) / $SUB_GAC_URI) * 100;
                }
                //値引率
                if ($SUB_URI_TTL != 0) {
                    $SUB_NEB_PRF = ($SUB_NEB_TTL / $SUB_URI_TTL) * 100;
                }

                if ($SUB_DAISU != 0) {
                    //売上台当り
                    $SUB_URI_PER_DAI = $SUB_URI_TTL / $SUB_DAISU;
                    //原価台当り
                    $SUB_GEN_PER_DAI = $SUB_GEN_TTL / $SUB_DAISU;
                    //粗利台当り
                    $SUB_ARARI_PRF_DAI = $SUB_ARARI_TTL / $SUB_DAISU;
                    //値引台当り
                    $SUB_NEB_PER_DAI = $SUB_NEB_TTL / $SUB_DAISU;
                }

                $result['data'][$key]['SUB_NEB_TTL'] = $SUB_NEB_TTL;
                $result['data'][$key]['SUB_ARARI_TTL'] = $SUB_ARARI_TTL;
                $result['data'][$key]['SUB_URI_TTL'] = $SUB_URI_TTL;
                $result['data'][$key]['SUB_GEN_TTL'] = $SUB_GEN_TTL;
                $result['data'][$key]['SUB_URI_SUBTTL'] = $SUB_URI_SUBTTL;
                $result['data'][$key]['SUB_GAC_GEN'] = $SUB_GAC_GEN;
                $result['data'][$key]['SUB_GAC_URI'] = $SUB_GAC_URI;
                $result['data'][$key]['SUB_GEN_SUBTTL'] = $SUB_GEN_SUBTTL;
                $result['data'][$key]['SUB_DAISU'] = $SUB_DAISU;
                $result['data'][$key]['SUB_KOC_URI'] = $SUB_KOC_URI;
                $result['data'][$key]['SUB_BUH_URI'] = $SUB_BUH_URI;
                $result['data'][$key]['SUB_KOC_GEN'] = $SUB_KOC_GEN;
                $result['data'][$key]['SUB_BUH_GEN'] = $SUB_BUH_GEN;

                $result['data'][$key]['SUB_KOSEIHI'] = $SUB_KOSEIHI;
                $result['data'][$key]['SUB_ARARI_PRF'] = $SUB_ARARI_PRF;
                $result['data'][$key]['SUB_NEB_PRF'] = $SUB_NEB_PRF;
                $result['data'][$key]['SUB_GAC_PRF'] = $SUB_GAC_PRF;
                $result['data'][$key]['SUB_BUH_PRF'] = $SUB_BUH_PRF;
                $result['data'][$key]['SUB_ARARI_PRF_DAI'] = $SUB_ARARI_PRF_DAI;
                $result['data'][$key]['SUB_NEB_PER_DAI'] = $SUB_NEB_PER_DAI;
                $result['data'][$key]['SUB_GEN_PER_DAI'] = $SUB_GEN_PER_DAI;
                $result['data'][$key]['SUB_URI_PER_DAI'] = $SUB_URI_PER_DAI;

                $TTL_KOSEIHI = 0;
                $TTL_ARARI_PRF = 0;
                $TTL_BUH_PRF = 0;
                $TTL_GAC_PRF = 0;
                $TTL_NEB_PRF = 0;
                $TTL_URI_PER_DAI = 0;
                $TTL_GEN_PER_DAI = 0;
                $TTL_ARARI_PER_DAI = 0;
                $TTL_NEB_PER_DAI = 0;

                $ARARI_TTL_KOC = 0;
                $ARARI_PRF_KOC = 0;
                $ARARI_TTL_BUH = 0;
                $ARARI_PRF_BUH = 0;
                $ARARI_TTL_GAC = 0;
                $ARARI_PRF_GAC = 0;
                $ARARI_TTL_URI = 0;
                $ARARI_PRF_URI = 0;

                //構成比
                if ($TTL_URI != 0) {
                    $TTL_KOSEIHI = ($TTL_URI_TTL / $TTL_URI) * 100;
                }
                //粗利益率
                if ($TTL_URI_TTL != 0) {
                    $TTL_ARARI_PRF = (($TTL_URI_TTL - $TTL_GEN_TTL) / $TTL_URI_TTL) * 100;
                }
                //部品利益率
                if ($TTL_BUH_URI != 0) {
                    $TTL_BUH_PRF = (($TTL_BUH_URI - $TTL_BUH_GEN) / $TTL_BUH_URI) * 100;
                }
                //外注利益率
                if ($TTL_GAC_URI != 0) {
                    $TTL_GAC_PRF = (($TTL_GAC_URI - $TTL_GAC_GEN) / $TTL_GAC_URI) * 100;
                }
                //値引率
                if ($TTL_URI_TTL != 0) {
                    $TTL_NEB_PRF = ($TTL_NEB_TTL / $TTL_URI_TTL) * 100;
                }

                if ($TTL_DAISU != 0) {
                    //売上台当り
                    $TTL_URI_PER_DAI = $TTL_URI_TTL / $TTL_DAISU;
                    //原価台当り
                    $TTL_GEN_PER_DAI = $TTL_GEN_TTL / $TTL_DAISU;
                    //粗利台当り
                    $TTL_ARARI_PER_DAI = $TTL_ARARI_TTL / $TTL_DAISU;
                    //値引台当り
                    $TTL_NEB_PER_DAI = $TTL_NEB_TTL / $TTL_DAISU;
                }

                //工賃粗利合計
                $ARARI_TTL_KOC = $TTL_KOC_URI - $TTL_KOC_GEN;
                //工賃粗利率
                if ($TTL_KOC_URI != 0) {
                    $ARARI_PRF_KOC = ($ARARI_TTL_KOC / $TTL_KOC_URI) * 100;
                }
                //部品粗利合計
                $ARARI_TTL_BUH = $TTL_BUH_URI - $TTL_BUH_GEN;
                //部品粗利率
                if ($TTL_BUH_URI != 0) {
                    $ARARI_PRF_BUH = ($ARARI_TTL_BUH / $TTL_BUH_URI) * 100;
                }
                //外注粗利合計
                $ARARI_TTL_GAC = $TTL_GAC_URI - $TTL_GAC_GEN;
                //外注粗利率
                if ($TTL_GAC_URI != 0) {
                    $ARARI_PRF_GAC = ($ARARI_TTL_GAC / $TTL_GAC_URI) * 100;
                }
                //売上粗利合計
                $ARARI_TTL_URI = $TTL_URI_TTL - $TTL_GEN_TTL;
                //売上粗利率
                if ($TTL_URI_TTL != 0) {
                    $ARARI_PRF_URI = ($ARARI_TTL_URI / $TTL_URI_TTL) * 100;
                }

                $result['data'][$key]['TTL_NEB_TTL'] = $TTL_NEB_TTL;
                $result['data'][$key]['TTL_ARARI_TTL'] = $TTL_ARARI_TTL;
                $result['data'][$key]['TTL_URI_TTL'] = $TTL_URI_TTL;
                $result['data'][$key]['TTL_GEN_TTL'] = $TTL_GEN_TTL;
                $result['data'][$key]['TTL_URI_SUBTTL'] = $TTL_URI_SUBTTL;
                $result['data'][$key]['TTL_GAC_GEN'] = $TTL_GAC_GEN;
                $result['data'][$key]['TTL_GAC_URI'] = $TTL_GAC_URI;
                $result['data'][$key]['TTL_GEN_SUBTTL'] = $TTL_GEN_SUBTTL;
                $result['data'][$key]['TTL_DAISU'] = $TTL_DAISU;
                $result['data'][$key]['TTL_KOC_URI'] = $TTL_KOC_URI;
                $result['data'][$key]['TTL_BUH_URI'] = $TTL_BUH_URI;
                $result['data'][$key]['TTL_KOC_GEN'] = $TTL_KOC_GEN;
                $result['data'][$key]['TTL_BUH_GEN'] = $TTL_BUH_GEN;

                $result['data'][$key]['TTL_KOSEIHI'] = $TTL_KOSEIHI;
                $result['data'][$key]['TTL_ARARI_PRF'] = $TTL_ARARI_PRF;
                $result['data'][$key]['TTL_BUH_PRF'] = $TTL_BUH_PRF;
                $result['data'][$key]['TTL_GAC_PRF'] = $TTL_GAC_PRF;
                $result['data'][$key]['TTL_NEB_PRF'] = $TTL_NEB_PRF;
                $result['data'][$key]['TTL_URI_PER_DAI'] = $TTL_URI_PER_DAI;
                $result['data'][$key]['TTL_GEN_PER_DAI'] = $TTL_GEN_PER_DAI;
                $result['data'][$key]['TTL_ARARI_PER_DAI'] = $TTL_ARARI_PER_DAI;
                $result['data'][$key]['TTL_NEB_PER_DAI'] = $TTL_NEB_PER_DAI;

                $result['data'][$key]['ARARI_TTL_KOC'] = $ARARI_TTL_KOC;
                $result['data'][$key]['ARARI_PRF_KOC'] = $ARARI_PRF_KOC;
                $result['data'][$key]['ARARI_TTL_BUH'] = $ARARI_TTL_BUH;
                $result['data'][$key]['ARARI_PRF_BUH'] = $ARARI_PRF_BUH;
                $result['data'][$key]['ARARI_TTL_GAC'] = $ARARI_TTL_GAC;
                $result['data'][$key]['ARARI_PRF_GAC'] = $ARARI_PRF_GAC;
                $result['data'][$key]['ARARI_TTL_URI'] = $ARARI_TTL_URI;
                $result['data'][$key]['ARARI_PRF_URI'] = $ARARI_PRF_URI;

                $arr['rptSeibiYusho'] = $result['data'];
            }
            //無償売上分
            $result = $clsSQL->fncCreatSeibiMushoSQL($tenpocd, $updstr, $updend);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                $DAISU_TOTAL = 0;
                $GEN_TTL_TOTAL = 0;
                $BUH_GEN_TOTAL = 0;
                $GAC_GEN_TOTAL = 0;
                $KOC_URI_TOTAL = 0;
                $KOC_GEN_TOTAL = 0;

                foreach ($result['data'] as $key => $value) {
                    $DAISU_TOTAL += intval($value['DAISU']);
                    $GEN_TTL_TOTAL += intval($value['GEN_TTL']);
                    $BUH_GEN_TOTAL += intval($value['BUH_GEN']);
                    $GAC_GEN_TOTAL += intval($value['GAC_GEN']);
                    $KOC_URI_TOTAL += intval($value['KOC_URI']);
                    $KOC_GEN_TOTAL += intval($value['KOC_GEN']);

                }

                $URI_PER_DAI_TOTAL = 0;
                $GEN_PER_DAI_TOTAL = 0;

                if ($value['TTL_DAISU'] != 0) {
                    $URI_PER_DAI_TOTAL = $value['TTL_URI'] / $value['TTL_DAISU'];
                    $GEN_PER_DAI_TOTAL = $value['TTL_GEN'] / $value['TTL_DAISU'];
                }

                $result['data'][$key]['DAISU_TOTAL'] = $DAISU_TOTAL;
                $result['data'][$key]['GEN_TTL_TOTAL'] = $GEN_TTL_TOTAL;
                $result['data'][$key]['BUH_GEN_TOTAL'] = $BUH_GEN_TOTAL;
                $result['data'][$key]['GAC_GEN_TOTAL'] = $GAC_GEN_TOTAL;
                $result['data'][$key]['KOC_URI_TOTAL'] = $KOC_URI_TOTAL;
                $result['data'][$key]['KOC_GEN_TOTAL'] = $KOC_GEN_TOTAL;
                $result['data'][$key]['URI_PER_DAI_TOTAL'] = $URI_PER_DAI_TOTAL;
                $result['data'][$key]['GEN_PER_DAI_TOTAL'] = $GEN_PER_DAI_TOTAL;

                $arr['rptSeibiMusho'] = $result['data'];
            }
            //総計
            $result = $clsSQL->fncCreatSeibiSokeiSQL($tenpocd, $updstr, $updend);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {

                $URI_TTL_TOTAL = 0;
                $DAISU_TOTAL = 0;
                $GEN_TTL_TOTAL = 0;
                foreach ($result['data'] as $key => $value) {
                    $URI_TTL_TOTAL += intval($value['URI_TTL']);
                    $DAISU_TOTAL += intval($value['DAISU']);
                    $GEN_TTL_TOTAL += intval($value['GEN_TTL']);
                }

                $result['data'][$key]['URI_TTL_TOTAL'] = $URI_TTL_TOTAL;
                $result['data'][$key]['DAISU_TOTAL'] = $DAISU_TOTAL;
                $result['data'][$key]['GEN_TTL_TOTAL'] = $GEN_TTL_TOTAL;

                $arr['rptSeibiSokei'] = $result['data'];
            }
            //諸費用
            $result = $clsSQL->fncCreatSeibiSyohiyoDataSet($tenpocd, $updstr, $updend);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                $arr['rptSeibiSyohiyo'] = $result['data'];
            }
            //前受金
            $result = $clsSQL->fncCreatSeibiMaeukeSQL($tenpocd, $updstr, $updend);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] > 0) {
                $PACKKIN_TOTAL = 0;
                $MAEUKEDENPY_TOTAL = 0;
                $MAEUKEKIN_TOTAL = 0;
                $PACKDENPY_TOTAL = 0;
                foreach ($result['data'] as $key => $value) {
                    $PACKKIN_TOTAL += intval($value['PACKKIN']);
                    $MAEUKEDENPY_TOTAL += intval($value['MAEUKEDENPY']);
                    $MAEUKEKIN_TOTAL += intval($value['MAEUKEKIN']);
                    $PACKDENPY_TOTAL += intval($value['PACKDENPY']);
                }

                $result['data'][$key]['PACKKIN_TOTAL'] = $PACKKIN_TOTAL;
                $result['data'][$key]['MAEUKEDENPY_TOTAL'] = $MAEUKEDENPY_TOTAL;
                $result['data'][$key]['MAEUKEKIN_TOTAL'] = $MAEUKEKIN_TOTAL;
                $result['data'][$key]['PACKDENPY_TOTAL'] = $PACKDENPY_TOTAL;

                $arr['rptSeibiMaeuke'] = $result['data'];
            }

            $arr['result'] = true;
            $arr['data'] = $arr;
        } catch (\Exception $e) {
            $arr['result'] = FALSE;
            $arr['data'] = $e->getMessage();
        }

        return $arr;
    }

    public function GetMeisaiData($clsSQL, $result)
    {
        $clsComFnc = new ClsComFnc();
        //20170926 YIN INS S
        //20180718 YIN DEL S
        // $ClsComFncComponent = new ClsComFncComponent();
        //20180718 YIN DEL E
        //20170926 YIN INS E
        $arr = array();

        $tenpocd = $result['data'][0]['TENPO_CD'];
        $updstr = $result['data'][0]['URIAGEDT_STA'];
        $updend = $result['data'][0]['URIAGEDT_END'];
        try {

            $arr['rptUriageMeisaiIchiran'] = $result['data'];
            $objds1 = $clsSQL->fncCreatUriMeisaiSQL($tenpocd, $updstr, $updend);
            if (!$objds1['result']) {
                throw new \Exception($objds1['data']);
            }
            if ($objds1['row'] > 0) {
                $YUDAISU = 0;
                $YUKOCURI = 0;
                $YUBUHURI = 0;
                $YUGACURI = 0;
                $YUURITTL = 0;
                $YUKOCGEN = 0;
                $YUBUHGEN = 0;
                $YUGACGEN = 0;
                $YUGENTTL = 0;
                $YUARARITTL = 0;
                $YUNEBTTL = 0;
                $YUBUHPRF = 0;
                $YUGACPRF = 0;

                $MUDAISU = 0;
                $MUKOCURI = 0;
                $MUBUHURI = 0;
                $MUGACURI = 0;
                $MUURITTL = 0;
                $MUKOCGEN = 0;
                $MUBUHGEN = 0;
                $MUGACGEN = 0;
                $MUGENTTL = 0;
                $MUARARITTL = 0;
                $MUNEBTTL = 0;
                $MUBUHPRF = 0;
                $MUGACPRF = 0;
                $SHZ_GKU_SUM = 0;
                $BUH_SEB_TTL_SUM = 0;
                $TTL_GKU_SUM = 0;
                $SYH_TTL_SUM = 0;
                $last = "";
                foreach ($objds1['data'] as $key => $value) {
                    if ($value['YUMUKBN'] == 0) {
                        $YUDAISU = $YUDAISU + $clsComFnc->FncNz($value['DAISU']);
                        $YUKOCURI = $YUKOCURI + $clsComFnc->FncNz($value['KOC_URI']);
                        $YUBUHURI = $YUBUHURI + $clsComFnc->FncNz($value['BUH_URI']);
                        $YUGACURI = $YUGACURI + $clsComFnc->FncNz($value['GAC_URI']);
                        $YUURITTL = $YUURITTL + $clsComFnc->FncNz($value['URI_TTL']);
                        $YUKOCGEN = $YUKOCGEN + $clsComFnc->FncNz($value['KOC_GEN']);
                        $YUBUHGEN = $YUBUHGEN + $clsComFnc->FncNz($value['BUH_GEN']);
                        $YUGACGEN = $YUGACGEN + $clsComFnc->FncNz($value['GAC_GEN']);
                        $YUGENTTL = $YUGENTTL + $clsComFnc->FncNz($value['GEN_TTL']);
                        $YUARARITTL = $YUARARITTL + $clsComFnc->FncNz($value['ARARI_TTL']);
                        $YUNEBTTL = $YUNEBTTL + $clsComFnc->FncNz($value['NEB_TTL']);
                    } else {
                        $MUDAISU = $MUDAISU + $clsComFnc->FncNz($value['DAISU']);
                        $MUKOCURI = $MUKOCURI + $clsComFnc->FncNz($value['KOC_URI']);
                        $MUBUHURI = $MUBUHURI + $clsComFnc->FncNz($value['BUH_URI']);
                        $MUGACURI = $MUGACURI + $clsComFnc->FncNz($value['GAC_URI']);
                        $MUURITTL = $MUURITTL + $clsComFnc->FncNz($value['URI_TTL']);
                        $MUKOCGEN = $MUKOCGEN + $clsComFnc->FncNz($value['KOC_GEN']);
                        $MUBUHGEN = $MUBUHGEN + $clsComFnc->FncNz($value['BUH_GEN']);
                        $MUGACGEN = $MUGACGEN + $clsComFnc->FncNz($value['GAC_GEN']);
                        $MUGENTTL = $MUGENTTL + $clsComFnc->FncNz($value['GEN_TTL']);
                        $MUARARITTL = $MUARARITTL + $clsComFnc->FncNz($value['ARARI_TTL']);
                        $MUNEBTTL = $MUNEBTTL + $clsComFnc->FncNz($value['NEB_TTL']);
                    }
                    if ($YUBUHURI <> 0) {
                        $YUBUHPRF = (($YUBUHURI - $YUBUHGEN) / $YUBUHURI) * 100;
                    }
                    if ($YUGACURI <> 0) {
                        $YUGACPRF = (($YUGACURI - $YUGACGEN) / $YUGACURI) * 100;
                    }
                    if ($MUBUHURI <> 0) {
                        $MUBUHPRF = (($MUBUHURI - $MUBUHGEN) / $MUBUHURI) * 100;
                    }
                    if ($MUGACURI <> 0) {
                        $MUGACPRF = (($MUGACURI - $MUGACGEN) / $MUGACURI) * 100;
                    }
                    if ($last != $value['SEB_NOU_NO_KEY']) {
                        $SHZ_GKU_SUM = $SHZ_GKU_SUM + $value['SHZ_GKU'];
                        $BUH_SEB_TTL_SUM = $BUH_SEB_TTL_SUM + $value['BUH_SEB_TTL'];
                        $TTL_GKU_SUM = $TTL_GKU_SUM + $value['TTL_GKU'];
                        $SYH_TTL_SUM = $SYH_TTL_SUM + $value['SYH_TTL'];
                        $last = $value['SEB_NOU_NO_KEY'];
                    }

                }

                foreach ($objds1['data'] as $key => $value) {
                    $objds1['data'][$key]['YUDAISU'] = $YUDAISU;
                    $objds1['data'][$key]['YUKOCURI'] = $YUKOCURI;
                    $objds1['data'][$key]['YUBUHURI'] = $YUBUHURI;
                    $objds1['data'][$key]['YUGACURI'] = $YUGACURI;
                    $objds1['data'][$key]['YUURITTL'] = $YUURITTL;
                    $objds1['data'][$key]['YUKOCGEN'] = $YUKOCGEN;
                    $objds1['data'][$key]['YUBUHGEN'] = $YUBUHGEN;
                    $objds1['data'][$key]['YUGACGEN'] = $YUGACGEN;
                    $objds1['data'][$key]['YUGENTTL'] = $YUGENTTL;
                    $objds1['data'][$key]['YUARARITTL'] = $YUARARITTL;
                    $objds1['data'][$key]['YUNEBTTL'] = $YUNEBTTL;
                    $objds1['data'][$key]['YUBUHPRF'] = $YUBUHPRF;
                    $objds1['data'][$key]['YUGACPRF'] = $YUGACPRF;
                    $objds1['data'][$key]['MUDAISU'] = $MUDAISU;
                    $objds1['data'][$key]['MUKOCURI'] = $MUKOCURI;
                    $objds1['data'][$key]['MUBUHURI'] = $MUBUHURI;
                    $objds1['data'][$key]['MUGACURI'] = $MUGACURI;
                    $objds1['data'][$key]['MUURITTL'] = $MUURITTL;
                    $objds1['data'][$key]['MUKOCGEN'] = $MUKOCGEN;
                    $objds1['data'][$key]['MUBUHGEN'] = $MUBUHGEN;
                    $objds1['data'][$key]['MUGACGEN'] = $MUGACGEN;
                    $objds1['data'][$key]['MUGENTTL'] = $MUGENTTL;
                    $objds1['data'][$key]['MUARARITTL'] = $MUARARITTL;
                    $objds1['data'][$key]['MUNEBTTL'] = $MUNEBTTL;
                    $objds1['data'][$key]['MUBUHPRF'] = $MUBUHPRF;
                    $objds1['data'][$key]['MUGACPRF'] = $MUGACPRF;
                    $strTorokuNo = $clsComFnc->FncNv($value['RIKUJI_NM'], "　　") . " " . $clsComFnc->FncNv($value['VCLRGTNO_SYU'], " ") . " " . $this->dbc2Sbc(mb_convert_kana($clsComFnc->FncNv($value['VCLRGTNO_KANA'], " "), "Hc")) . " " . $clsComFnc->FncNv($value['VCLRGTNO_REN'], " ");
                    $objds1['data'][$key]['TOROKUNO'] = $strTorokuNo;
                    $objds1['data'][$key]['SHZ_GKU_SUM'] = $SHZ_GKU_SUM;
                    $objds1['data'][$key]['BUH_SEB_TTL_SUM'] = $BUH_SEB_TTL_SUM;
                    $objds1['data'][$key]['TTL_GKU_SUM'] = $TTL_GKU_SUM;
                    $objds1['data'][$key]['SYH_TTL_SUM'] = $SYH_TTL_SUM;
                    //20170926 YIN INS S
                    //20180718 YIN UPD S
                    // $objds1['data'][$key]['SEIKYU_NM'] = $ClsComFncComponent -> FncGetByteString($objds1['data'][$key]['SEIKYU_NM'],0,32);
                    $objds1['data'][$key]['SEIKYU_NM'] = $this->ClsComFnc->FncGetByteString($objds1['data'][$key]['SEIKYU_NM'], 0, 32);
                    //20180718 YIN UPD E
                    //20170926 YIN INS E
                }
                $arr['rptUriMeisai'] = $objds1['data'];
            }
            $objds2 = $clsSQL->fncCreatUriSyohiyoSQL($tenpocd, $updstr, $updend);
            if (!$objds2['result']) {
                throw new \Exception($objds2['data']);
            }
            if ($objds2['row'] > 0) {
                $YUDENPYO = 0;
                $YUHIYOGK = 0;
                $YUJIBAI = 0;
                $YUJURYO = 0;
                $YUINSHI = 0;
                $YUDAIKO = 0;

                $MUDENPYO = 0;
                $MUHIYOGK = 0;
                $MUJIBAI = 0;
                $MUJURYO = 0;
                $MUINSHI = 0;
                $MUDAIKO = 0;
                $DAIKO_SUM = 0;
                $HIYOUGK_SUM = 0;
                $JIBAI_SUM = 0;
                $JURYO_SUM = 0;
                $INSHI_SUM = 0;
                $DENPYOSU = 0;
                foreach ($objds2['data'] as $key => $value) {
                    if ($value['YUMUKBN'] == 0) {
                        $YUHIYOGK = $YUHIYOGK + $clsComFnc->FncNz($value['HIYOUGK']);
                        $YUJIBAI = $YUJIBAI + $clsComFnc->FncNz($value['JIBAI']);
                        $YUJURYO = $YUJURYO + $clsComFnc->FncNz($value['JURYO']);
                        $YUINSHI = $YUINSHI + $clsComFnc->FncNz($value['INSHI']);
                        $YUDAIKO = $YUDAIKO + $clsComFnc->FncNz($value['DAIKO']);
                        $YUDENPYO = $YUDENPYO + $value['YUDENPYOSU'];
                    } else {
                        $MUHIYOGK = $MUHIYOGK + $clsComFnc->FncNz($value['HIYOUGK']);
                        $MUJIBAI = $MUJIBAI + $clsComFnc->FncNz($value['JIBAI']);
                        $MUJURYO = $MUJURYO + $clsComFnc->FncNz($value['JURYO']);
                        $MUINSHI = $MUINSHI + $clsComFnc->FncNz($value['INSHI']);
                        $MUDAIKO = $MUDAIKO + $clsComFnc->FncNz($value['DAIKO']);
                        $MUDENPYO = $MUDENPYO + $value['MUDENPYOSU'];
                    }
                    $DAIKO_SUM = $DAIKO_SUM + $value['DAIKO'];
                    $HIYOUGK_SUM = $HIYOUGK_SUM + $value['HIYOUGK'];
                    $JIBAI_SUM = $JIBAI_SUM + $value['JIBAI'];
                    $JURYO_SUM = $JURYO_SUM + $value['JURYO'];
                    $INSHI_SUM = $INSHI_SUM + $value['INSHI'];
                }

                foreach ($objds2['data'] as $key => $value) {
                    $objds2['data'][$key]['YUHIYOGK'] = $YUHIYOGK;
                    $objds2['data'][$key]['YUJIBAI'] = $YUJIBAI;
                    $objds2['data'][$key]['YUJURYO'] = $YUJURYO;
                    $objds2['data'][$key]['YUINSHI'] = $YUINSHI;
                    $objds2['data'][$key]['YUDAIKO'] = $YUDAIKO;
                    $objds2['data'][$key]['MUHIYOGK'] = $MUHIYOGK;
                    $objds2['data'][$key]['MUJIBAI'] = $MUJIBAI;
                    $objds2['data'][$key]['MUJURYO'] = $MUJURYO;
                    $objds2['data'][$key]['MUINSHI'] = $MUINSHI;
                    $objds2['data'][$key]['MUDAIKO'] = $MUDAIKO;
                    $strTorokuNo = $clsComFnc->FncNv($value['RIKUJI_NM'], "  ") . $clsComFnc->FncNv($value['VCLRGTNO_SYU'], " ") . $this->dbc2Sbc(mb_convert_kana($clsComFnc->FncNv($value['VCLRGTNO_KANA'], " "), "Hc")) . $clsComFnc->FncNv($value['VCLRGTNO_REN'], " ");
                    $objds2['data'][$key]['TOROKUNO'] = $strTorokuNo;
                    $objds2['data'][$key]['DAIKO_SUM'] = $DAIKO_SUM;
                    $objds2['data'][$key]['HIYOUGK_SUM'] = $HIYOUGK_SUM;
                    $objds2['data'][$key]['JIBAI_SUM'] = $JIBAI_SUM;
                    $objds2['data'][$key]['JURYO_SUM'] = $JURYO_SUM;
                    $objds2['data'][$key]['INSHI_SUM'] = $INSHI_SUM;
                    $objds2['data'][$key]['YUDENPYO'] = $YUDENPYO;
                    $objds2['data'][$key]['MUDENPYO'] = $MUDENPYO;
                    $objds2['data'][$key]['DENPYOSU'] = $YUDENPYO + $MUDENPYO;
                    //20180718 YIN UPD S
                    // $objds2['data'][$key]['SEIKYU_NM'] = $ClsComFncComponent -> FncGetByteString($objds2['data'][$key]['SEIKYU_NM'],0,10);
                    $objds2['data'][$key]['SEIKYU_NM'] = $this->ClsComFnc->FncGetByteString($objds2['data'][$key]['SEIKYU_NM'], 0, 10);
                    //20180718 YIN UPD E
                }
                $arr['rptUriSyohiyo'] = $objds2['data'];
            }
            $objds3 = $clsSQL->fncCreatUriPackSQL($tenpocd, $updstr, $updend);
            if (!$objds3['result']) {
                throw new \Exception($objds3['data']);
            }
            if ($objds3['row'] > 0) {
                $PACKKIN_SUM = 0;
                $DENPYOSU = 0;
                foreach ($objds3['data'] as $key => $value) {
                    $PACKKIN_SUM = $PACKKIN_SUM + $value['PACKKIN'];
                    $DENPYOSU = $DENPYOSU + $value['DENPYOSU'];
                }
                foreach ($objds3['data'] as $key => $value) {
                    $objds3['data'][$key]['PACKKIN_SUM'] = $PACKKIN_SUM;
                    $objds3['data'][$key]['DENPYOSU'] = $DENPYOSU;
                }
                $arr['rptUriPack'] = $objds3['data'];
            }
            $arr['result'] = true;
            $arr['data'] = $arr;
        } catch (\Exception $e) {
            $arr['result'] = FALSE;
            $arr['data'] = $e->getMessage();
        }

        return $arr;

    }

}
