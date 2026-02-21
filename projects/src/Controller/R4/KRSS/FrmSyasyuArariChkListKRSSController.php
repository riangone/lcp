<?php
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmSyasyuArariChkListKRSS;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class FrmSyasyuArariChkListKRSSController extends AppController
{
    public $autoLayout = TRUE;
    public $result;
    public $result_fncArariSyukei;
    public $result_fncArariSyukeibase;
    public $result_fncArariSyukei_sel;
    public $result_fncArariSyukeibase_sel;
    public $filePathName;
    public $Do_conn;
    public $blnTranFlg;
    public $FrmSyasyuArariChkListKRSS;
    public $intObjRpt;
    public $result_objDs;
    public $result_objDs2;
    public $result_objDs3;
    private $executeFinish_result;
    private $data_rptArariekiChkList;
    private $data_rptArariekiHyo;
    private $data_rptArariekiBase;
    public $USERID = "";
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');

    }
    public function index()
    {
        $this->render('index', 'FrmSyasyuArariChkListKRSS_layout');
    }

    /*
           '**********************************************************************
           '処理概要：フォームロード
           '**********************************************************************
           */
    public function formLoad()
    {
        try {
            $this->executeFinish_result = FALSE;
            //モデルの仕様するクラスを定義
            $FrmSyasyuArariChkListKRSS = new FrmSyasyuArariChkListKRSS();
            //モデルクラスのselect処理を呼出し
            $this->result = $FrmSyasyuArariChkListKRSS->FrmSyasyuArariChkListKRSS_formLoad_select();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }

        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }

        $this->fncReturn($this->result);
    }

    public function fncCmdExportExcel()
    {
        $FrmSyasyuArariChkListKRSS = new FrmSyasyuArariChkListKRSS();
        $this->USERID = $FrmSyasyuArariChkListKRSS->GS_LOGINUSER['strUserID'];
        $this->executeFinish_result = FALSE;
        $this->intObjRpt = 0;
        if ($_POST['data']['radChkList'] == "true") {
            $this->fnc_radChkList_checked();
        } elseif ($_POST['data']['radMeisai'] == "true") {
            $this->fnc_radMeisai_checked();
        } elseif ($_POST['data']['radBaseh'] == "true") {
            $this->fnc_radBaseh_checked();
        } elseif ($_POST['data']['radDouble'] == "true") {
            $this->fnc_radDouble_checked();
        }
        if ($this->executeFinish_result) {
            //$this -> fncSelectCaseIntObjRpt();
            $this->fncSelectCaseIntExportExcel();

            //var_dump($this->result_objDs);
        }
    }

    public function fnc_radChkList_checked()
    {
        $jumpFlg = false;
        try {

            //モデルの仕様するクラスを定義
            $FrmSyasyuArariChkListKRSS = new FrmSyasyuArariChkListKRSS();
            //モデルクラスのselect処理を呼出し
            $cboYMEnd = $_POST['data']['cboYMEnd'];
            // $cboYMStart = $_POST['data']['cboYMStart'];
            $this->result_objDs = $FrmSyasyuArariChkListKRSS->fncPrintSelect($cboYMEnd);
            //$this->result_objDs=$FrmSyasyuArariChkListKRSS->fncArariekiListSel_sql($cboYMEnd, $cboYMStart);
            if (!$this->result_objDs['result']) {
                $jumpFlg = true;
                throw new \Exception($this->result_objDs['data'], 1);
            }
            if (count((array) $this->result_objDs['data']) == 0) {
                $this->result['result'] = FALSE;
                $this->result['data'] = array(
                    "TFException" => FALSE,
                    "messageCode" => "I0001",
                    "messageContent" => ""
                );

                throw new \Exception("error", 1);
            }
            $this->intObjRpt = 1;
            $this->executeFinish_result = TRUE;

        } catch (\Exception $ex) {
            if ($jumpFlg) {
                $this->result['result'] = FALSE;
                $this->result['data'] = array(
                    "TFException" => true,
                    "messageContent" => $ex->getMessage()
                );
            }

            $this->fncReturn($this->result);
        }

    }

    public function fnc_radMeisai_checked()
    {
        $jumpFlg = FALSE;
        try {
            $cboYMEnd = $_POST['data']['cboYMEnd'];
            $cboYMStart = $_POST['data']['cboYMStart'];

            //モデルの仕様するクラスを定義
            $this->FrmSyasyuArariChkListKRSS = new FrmSyasyuArariChkListKRSS();
            $this->Do_conn = $this->FrmSyasyuArariChkListKRSS->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //---ysj edit s---
            //トランザクション開始 line371
            $this->FrmSyasyuArariChkListKRSS->Do_transaction();
            //新車車種別粗利益集計処理
            if (!$this->fncArariSyukei($cboYMEnd, $cboYMStart)) {
                $jumpFlg = true;
                throw new \Exception($this->result_fncArariSyukei['data']);
            }
            //コミット
            $this->FrmSyasyuArariChkListKRSS->Do_commit();

            //---ysj edit e---
            $this->blnTranFlg = FALSE;
            //SQL発行
            $this->result_objDs2 = $this->FrmSyasyuArariChkListKRSS->fncArariekiListSel($cboYMEnd, $cboYMStart);

            if (!$this->result_objDs2['result']) {
                $jumpFlg = true;
                throw new \Exception($this->result_objDs2['data'], 1);
            }
            if (count((array) $this->result_objDs2['data']) <= 0) {
                $this->result['result'] = FALSE;
                $this->result['data'] = array(
                    "TFException" => FALSE,
                    "messageCode" => "I0001",
                    "messageContent" => ""
                );
                throw new \Exception("error", 1);
            }
            $this->intObjRpt = 2;
            $this->executeFinish_result = TRUE;
        } catch (\Exception $ex) {

            if ($jumpFlg) {
                $this->result['result'] = FALSE;
                $this->result['data'] = array(
                    "TFException" => true,
                    "messageContent" => $ex->getMessage()
                );
            }

            $this->fncReturn($this->result);
        }
    }

    public function fnc_radBaseh_checked()
    {
        $jumpFlg = FALSE;
        try {
            $cboYMEnd = $_POST['data']['cboYMEnd'];
            $cboYMStart = $_POST['data']['cboYMStart'];

            //モデルの仕様するクラスを定義
            $this->FrmSyasyuArariChkListKRSS = new FrmSyasyuArariChkListKRSS();
            $this->Do_conn = $this->FrmSyasyuArariChkListKRSS->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }

            //トランザクション開始 line371
            $this->FrmSyasyuArariChkListKRSS->Do_transaction();
            //新車車種別粗利益集計処理
            if (!$this->fncArariSyukeibase($cboYMEnd, $cboYMStart)) {
                $jumpFlg = true;
                throw new \Exception($this->result_fncArariSyukeibase['data']);
            }
            //コミット
            $this->FrmSyasyuArariChkListKRSS->Do_commit();
            $this->blnTranFlg = FALSE;
            //SQL発行
            $this->result_objDs3 = $this->FrmSyasyuArariChkListKRSS->fncArariekiListSelbase($cboYMEnd, $cboYMStart);
            if (!$this->result_objDs3['result']) {
                $jumpFlg = true;
                throw new \Exception($this->result_objDs3['data'], 1);
            }
            if (count((array) $this->result_objDs3['data']) <= 0) {
                $this->result['result'] = FALSE;
                $this->result['data'] = array(
                    "TFException" => FALSE,
                    "messageCode" => "I0001",
                    "messageContent" => ""
                );
                throw new \Exception("error", 1);
            }
            $this->intObjRpt = 4;
            $this->executeFinish_result = TRUE;
        } catch (\Exception $ex) {

            if ($jumpFlg) {
                $this->result['result'] = FALSE;
                $this->result['data'] = array(
                    "TFException" => true,
                    "messageContent" => $ex->getMessage()
                );
            }

            $this->fncReturn($this->result);
        }
    }

    public function fnc_radDouble_checked()
    {
        $jumpFlg = FALSE;
        try {

            $cboYMEnd = $_POST['data']['cboYMEnd'];
            $cboYMStart = $_POST['data']['cboYMStart'];

            //モデルの仕様するクラスを定義
            $this->FrmSyasyuArariChkListKRSS = new FrmSyasyuArariChkListKRSS();
            $this->Do_conn = $this->FrmSyasyuArariChkListKRSS->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }

            //トランザクション開始
            $this->FrmSyasyuArariChkListKRSS->Do_transaction();

            //新車車種別粗利益集計処理
            if (!$this->fncArariSyukei($cboYMEnd, $cboYMStart)) {
                $jumpFlg = true;
                throw new \Exception($this->result_fncArariSyukei['data']);
            }
            //ベースH別粗利集計マスタ
            if (!$this->fncArariSyukeibase($cboYMEnd, $cboYMStart)) {
                $jumpFlg = true;
                throw new \Exception($this->result_fncArariSyukei['data']);
            }

            //コミット
            $this->FrmSyasyuArariChkListKRSS->Do_commit();
            $this->blnTranFlg = FALSE;

            //SQL発行
            $cboYMEnd = $_POST['data']['cboYMEnd'];
            $this->result_objDs = $this->FrmSyasyuArariChkListKRSS->fncPrintSelect($cboYMEnd);
            if (!$this->result_objDs['result']) {
                $jumpFlg = true;
                throw new \Exception($this->result_objDs['data'], 1);
            }
            if (count((array) $this->result_objDs['data']) != 0) {
                $this->intObjRpt = 1;
            }

            $this->result_objDs2 = $this->FrmSyasyuArariChkListKRSS->fncArariekiListSel($cboYMEnd, $cboYMStart);
            if (!$this->result_objDs2['result']) {
                $jumpFlg = true;
                throw new \Exception($this->result_objDs2['data'], 1);
            }
            if (count((array) $this->result_objDs2['data']) != 0) {
                $this->intObjRpt += 2;
            }

            $this->result_objDs3 = $this->FrmSyasyuArariChkListKRSS->fncArariekiListSelbase($cboYMEnd, $cboYMStart);

            if (!$this->result_objDs3['result']) {
                $jumpFlg = true;
                throw new \Exception($this->result_objDs3['data'], 1);
            }
            if (count((array) $this->result_objDs3['data']) != 0) {
                $this->intObjRpt += 4;
            }
            $this->executeFinish_result = TRUE;
        } catch (\Exception $ex) {
            if ($jumpFlg) {
                $this->result['result'] = FALSE;
                $this->result['data'] = array(
                    "TFException" => true,
                    "messageContent" => $ex->getMessage()
                );
            }

            $this->fncReturn($this->result);
            return;
        }
    }

    public function fncArariSyukei($cboYMEnd, $cboYMStart)
    {
        $this->result_fncArariSyukei = "";
        try {
            //ﾜｰｸﾃｰﾌﾞﾙを削除する  line547 a1
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncWKDel();

            //return;
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result['data'], 1);
            }

            //車種別粗利益ファイルの当月分を削除する  a2

            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncArariDel($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }

            //当月分限界利益データをﾜｰｸﾃｰﾌﾞﾙに集計する  a3
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncGenriInsert($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            } elseif ($this->result_fncArariSyukei['number_of_rows'] == 0) {
                return TRUE;
            }

            //調整入力の値をINSERTする  a4
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncChoseiInsert($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }

            //車種別粗利益ファイルを読み込み、当年と前年をINSERTする  a5
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncRuikeiInsert($cboYMEnd, $cboYMStart);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }
            //新車売上の差額を算出する(会計データと売上ﾃﾞｰﾀの差額) a6
            $this->result_fncArariSyukei_sel = $this->FrmSyasyuArariChkListKRSS->fncUriAnbunSel($cboYMEnd, $cboYMStart);
            if (!$this->result_fncArariSyukei_sel['result']) {
                throw new \Exception($this->result_fncArariSyukei_sel['data'], 1);
            }

            //差額が存在していたら  a7
            if (count((array) $this->result_fncArariSyukei_sel['data']) > 0) {
                //データを読み込む
                //差額が0以外
                $T_SAGAKU = $this->ClsComFnc->FncNz($this->result_fncArariSyukei_sel['data'][0]['SAGAKU']);
                $T_ARARI = $this->ClsComFnc->FncNz($this->result_fncArariSyukei_sel['data'][0]['ARARI']);
                if ($T_SAGAKU != 0) {
                    //新車売上合計が0以外
                    if ($T_ARARI != 0) {
                        //車種ごとに差額を按分する  a8
                        $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncUriAnbunSyasyuIns($cboYMEnd, $T_SAGAKU, $T_ARARI);
                        if (!$this->result_fncArariSyukei['result']) {
                            throw new \Exception($this->result_fncArariSyukei['data'], 1);
                        }
                    }

                    //車種ごとに割り振った差額の合計と新車売上との差額をその他に集計する  a9
                    $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncUriAnbunExtIns($cboYMEnd, $T_SAGAKU);
                    if (!$this->result_fncArariSyukei['result']) {
                        throw new \Exception($this->result_fncArariSyukei['data'], 1);
                    }

                }
            }

            //新車車両原価の差額を算出する(会計データと売上ﾃﾞｰﾀの差額) line 604  a10
            $this->result_fncArariSyukei_sel = $this->FrmSyasyuArariChkListKRSS->fncSyaryoPcsAnbunSel($cboYMEnd);
            if (!$this->result_fncArariSyukei_sel['result']) {
                throw new \Exception($this->result_fncArariSyukei_sel['data'], 1);
            }
            //差額が存在していたら  a11
            if (count($this->result_fncArariSyukei_sel['data']) > 0) {
                $T_SAGAKU = $this->ClsComFnc->FncNz($this->result_fncArariSyukei_sel['data'][0]['SAGAKU']);
                $T_GENKA = $this->ClsComFnc->FncNz($this->result_fncArariSyukei_sel['data'][0]['GENKA']);
                if ($T_SAGAKU != 0) {
                    if ($T_GENKA != 0) {
                        //車種ごとに差額を按分する a12
                        $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncSyaryoPcsAnbunSyasyuIns($cboYMEnd, $T_SAGAKU, $T_GENKA);
                        if (!$this->result_fncArariSyukei['result']) {
                            throw new \Exception($this->result_fncArariSyukei['data'], 1);
                        }
                    }
                    //車種ごとに割り振った差額の合計と車両原価差額との差額をその他に集計する  a13
                    $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncSyaryoPcsAnbunExtIns($cboYMEnd, $T_SAGAKU);
                    if (!$this->result_fncArariSyukei['result']) {
                        throw new \Exception($this->result_fncArariSyukei['data'], 1);
                    }
                }
            }

            //架付原価の差額を算出する(会計データと売上ﾃﾞｰﾀの差額) a14
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncKasouPcsAnbunSel($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }
            //差額が存在していたら
            if (count($this->result_fncArariSyukei['data']) > 0) {
                //データを読み込む
                $T_SAGAKU = $this->ClsComFnc->FncNz($this->result_fncArariSyukei['data'][0]['SAGAKU']);
                $T_GENKA = $this->ClsComFnc->FncNz($this->result_fncArariSyukei['data'][0]['GENKA']);
                if ($T_SAGAKU != 0) {
                    if ($T_GENKA != 0) {
                        //車種ごとに差額を按分する  a15
                        $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncKasouPcsAnbunSyasyuIns($cboYMEnd, $T_SAGAKU, $T_GENKA);
                        if (!$this->result_fncArariSyukei['result']) {
                            throw new \Exception($this->result_fncArariSyukei['data'], 1);
                        }
                    }
                    //車種ごとに割り振った差額の合計と架付原価差額との差額をその他に集計する  a16
                    $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fnckasouPcsAnbunExtIns($cboYMEnd, $T_SAGAKU);
                    if (!$this->result_fncArariSyukei['result']) {
                        throw new \Exception($this->result_fncArariSyukei['data'], 1);
                    }
                }
            }
            //留保金(運賃)を求める  a17
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncUnchinIns($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }

            //車種別粗利益データに今回求めた値をINSERTする  a18
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkListKRSS->fncArariIns($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }
            //正常終了
            return true;
        } catch (\Exception $ex) {
            $this->result_fncArariSyukei['result'] = FALSE;
            $this->result_fncArariSyukei['data'] = $ex->getMessage();
            return FALSE;
        }
    }

    //baseh
    public function fncArariSyukeibase($cboYMEnd, $cboYMStart)
    {
        $this->result_fncArariSyukeibase = "";
        try {
            //ﾜｰｸﾃｰﾌﾞﾙを削除する  line547 a1
            $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncWKDelbase();
            if (!$this->result_fncArariSyukeibase['result']) {
                throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
            }

            //車種別粗利益ファイルの当月分を削除する  a2

            $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncArariDelbase($cboYMEnd);
            if (!$this->result_fncArariSyukeibase['result']) {
                throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
            }
            //当月分限界利益データをﾜｰｸﾃｰﾌﾞﾙに集計する  a3
            $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncGenriInsertbase($cboYMEnd);
            if (!$this->result_fncArariSyukeibase['result']) {
                throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
            } elseif ($this->result_fncArariSyukeibase['number_of_rows'] == 0) {
                return TRUE;
            }
            //調整入力の値をINSERTする  a4
            $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncChoseiInsertbase($cboYMEnd);
            if (!$this->result_fncArariSyukeibase['result']) {
                throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
            }
            //車種別粗利益ファイルを読み込み、当年と前年をINSERTする  a5
            $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncRuikeiInsertbase($cboYMEnd, $cboYMStart);
            if (!$this->result_fncArariSyukeibase['result']) {
                throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
            }
            //新車売上の差額を算出する(会計データと売上ﾃﾞｰﾀの差額) a6
            $this->result_fncArariSyukeibase_sel = $this->FrmSyasyuArariChkListKRSS->fncUriAnbunSelbase($cboYMEnd, $cboYMStart);
            if (!$this->result_fncArariSyukeibase_sel['result']) {
                throw new \Exception($this->result_fncArariSyukeibase_sel['data'], 1);
            }
            //差額が存在していたら  a7
            if (count($this->result_fncArariSyukeibase_sel['data']) > 0) {
                //データを読み込む
                //差額が0以外
                $T_SAGAKU = $this->ClsComFnc->FncNz($this->result_fncArariSyukeibase_sel['data'][0]['SAGAKU']);
                $T_ARARI = $this->ClsComFnc->FncNz($this->result_fncArariSyukeibase_sel['data'][0]['ARARI']);
                if ($T_SAGAKU != 0) {
                    //新車売上合計が0以外
                    if ($T_ARARI != 0) {
                        //車種ごとに差額を按分する  a8
                        $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncUriAnbunSyasyuInsbase($cboYMEnd, $T_SAGAKU, $T_ARARI);
                        if (!$this->result_fncArariSyukeibase['result']) {
                            throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
                        }
                    }
                    //車種ごとに割り振った差額の合計と新車売上との差額をその他に集計する  a9
                    $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncUriAnbunExtInsbase($cboYMEnd, $T_SAGAKU);
                    if (!$this->result_fncArariSyukeibase['result']) {
                        throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
                    }

                }
            }
            //新車車両原価の差額を算出する(会計データと売上ﾃﾞｰﾀの差額) line 604  a10
            $this->result_fncArariSyukeibase_sel = $this->FrmSyasyuArariChkListKRSS->fncSyaryoPcsAnbunSelbase($cboYMEnd);
            if (!$this->result_fncArariSyukeibase_sel['result']) {
                throw new \Exception($this->result_fncArariSyukeibase_sel['data'], 1);
            }
            //差額が存在していたら  a11
            if (count($this->result_fncArariSyukeibase_sel['data']) > 0) {
                $T_SAGAKU = $this->ClsComFnc->FncNz($this->result_fncArariSyukeibase_sel['data'][0]['SAGAKU']);
                $T_GENKA = $this->ClsComFnc->FncNz($this->result_fncArariSyukeibase_sel['data'][0]['GENKA']);
                if ($T_SAGAKU != 0) {
                    if ($T_GENKA != 0) {
                        //車種ごとに差額を按分する a12
                        $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncSyaryoPcsAnbunSyasyuInsbase($cboYMEnd, $T_SAGAKU, $T_GENKA);
                        if (!$this->result_fncArariSyukeibase['result']) {
                            throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
                        }
                    }
                    //車種ごとに割り振った差額の合計と車両原価差額との差額をその他に集計する  a13
                    $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncSyaryoPcsAnbunExtInsbase($cboYMEnd, $T_SAGAKU);
                    if (!$this->result_fncArariSyukeibase['result']) {
                        throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
                    }
                }
            }

            //架付原価の差額を算出する(会計データと売上ﾃﾞｰﾀの差額) a14
            $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncKasouPcsAnbunSelbase($cboYMEnd);
            if (!$this->result_fncArariSyukeibase['result']) {
                throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
            }
            //差額が存在していたら
            if (count($this->result_fncArariSyukeibase['data']) > 0) {
                //データを読み込む
                $T_SAGAKU = $this->ClsComFnc->FncNz($this->result_fncArariSyukeibase['data'][0]['SAGAKU']);
                $T_GENKA = $this->ClsComFnc->FncNz($this->result_fncArariSyukeibase['data'][0]['GENKA']);
                if ($T_SAGAKU != 0) {
                    if ($T_GENKA != 0) {
                        //車種ごとに差額を按分する  a15
                        $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncKasouPcsAnbunSyasyuInsbase($cboYMEnd, $T_SAGAKU, $T_GENKA);
                        if (!$this->result_fncArariSyukeibase['result']) {
                            throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
                        }
                    }
                    //車種ごとに割り振った差額の合計と架付原価差額との差額をその他に集計する  a16
                    $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fnckasouPcsAnbunExtInsbase($cboYMEnd, $T_SAGAKU);
                    if (!$this->result_fncArariSyukeibase['result']) {
                        throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
                    }
                }
            }
            //留保金(運賃)を求める  a17
            $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncUnchinInsbase($cboYMEnd);
            if (!$this->result_fncArariSyukeibase['result']) {
                throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
            }

            //車種別粗利益データに今回求めた値をINSERTする  a18
            $this->result_fncArariSyukeibase = $this->FrmSyasyuArariChkListKRSS->fncArariInsbase($cboYMEnd);
            if (!$this->result_fncArariSyukeibase['result']) {
                throw new \Exception($this->result_fncArariSyukeibase['data'], 1);
            }
            //正常終了
            return true;
        } catch (\Exception $ex) {
            $this->result_fncArariSyukeibase['result'] = FALSE;
            $this->result_fncArariSyukeibase['data'] = $ex->getMessage();
            return FALSE;
        }
    }

    public function fncSelectCaseIntExportExcel()
    {
        $tmpPdfName1 = "";
        $tmpPdfName2 = "";
        $tmpPdfName3 = "";
        switch ($this->intObjRpt) {
            case 0:
                $this->result['result'] = FALSE;
                $this->result['data'] = array(
                    "TFException" => FALSE,
                    "messageCode" => "I0001",
                    "messageContent" => ""
                );

                $this->fncReturn($this->result);
                return;
            case 1:
                $tmpPdfName1 = "rptArariekiChkList";
                break;
            case 2:
                $tmpPdfName1 = "rptArariekiHyo";
                break;
            case 3:
                $tmpPdfName1 = "rptArariekiChkList";
                $tmpPdfName2 = "rptArariekiHyo";
                break;
            case 4:
                $tmpPdfName1 = "rptArariekiBase";
                break;
            case 5:
                $tmpPdfName1 = "rptArariekiChkList";
                $tmpPdfName2 = "rptArariekiBase";
                break;
            case 6:
                $tmpPdfName1 = "rptArariekiHyo";
                $tmpPdfName2 = "rptArariekiBase";
                break;
            case 7:
                $tmpPdfName1 = "rptArariekiChkList";
                $tmpPdfName2 = "rptArariekiHyo";
                $tmpPdfName3 = "rptArariekiBase";
                break;
        }
        //'プレビュー表示
        // $path_rpxTopdf = dirname(__DIR__);
        //include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
        if ($tmpPdfName3 == "") {
            if ($tmpPdfName2 == "") {
                //	include_once $path_rpxTopdf . '/Component/tcpdf/KRSS/' . $tmpPdfName1 . '.inc';
                switch ($tmpPdfName1) {
                    case 'rptArariekiChkList':
                        $data = $this->result_objDs['data'][0];
                        $this->data_rptArariekiChkList = $data;
                        $type = "rptArariekiChkList";
                        $this->result = $this->fncExportExcel($type);

                        break;
                    case 'rptArariekiHyo':
                        $data = $this->result_objDs2['data'];

                        //---
                        $DAISU_total = 0;
                        $URIAGEKIN_total = 0;
                        $ARARI_total = 0;
                        $RYUHO_total = 0;
                        $TOUARA_total = 0;
                        $TKIARA_total = 0;
                        $ZKIARA_total = 0;
                        $ZKI_DAI_total = 0;
                        $TKI_DAI_total = 0;
                        //------
                        //------
                        foreach ((array) $data as $key => $value) {
                            $DAISU_total += (int) $value['DAISU'];
                            $URIAGEKIN_total += (int) $value['URIAGEKIN'];
                            $ARARI_total += (int) $value['ARARI'];
                            $RYUHO_total += (int) $value['RYUHO'];
                            $TOUARA_total += (int) $value['TOUARA'];
                            $TKIARA_total += (int) $value['TKIARA'];
                            $ZKIARA_total += (int) $value['ZKIARA'];
                            $ZKI_DAI_total += (int) $value['ZKI_DAI'];
                            $TKI_DAI_total += (int) $value['TKI_DAI'];
                        }
                        foreach ((array) $data as $key => $value) {
                            $data[$key]['DAISU_total'] = $DAISU_total;
                            $data[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total), 0, 1);
                            $data[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total), 0, 1);
                            $data[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                            $data[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                            $data[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                            $data[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                            $data[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                            $data[$key]['TKI_DAI_total'] = $TKI_DAI_total;
                        }

                        $this->data_rptArariekiHyo = $data;
                        $type = "rptArariekiHyo";
                        $this->fncDealData();
                        $this->result = $this->fncExportExcel($type);

                        // $pdfPath = "";
                        // $this -> result['result'] = TRUE;
                        // $this -> result['data'] = $excelPath;
                        break;
                    case 'rptArariekiBase':
                        $data = $this->result_objDs3['data'];

                        //---
                        $DAISU_total = 0;
                        $URIAGEKIN_total = 0;
                        $ARARI_total = 0;
                        $RYUHO_total = 0;
                        $TOUARA_total = 0;
                        $TKIARA_total = 0;
                        $ZKIARA_total = 0;
                        $ZKI_DAI_total = 0;
                        $TKI_DAI_total = 0;
                        //------
                        //------
                        foreach ((array) $data as $key => $value) {
                            $DAISU_total += (int) $value['DAISU'];
                            $URIAGEKIN_total += (int) $value['URIAGEKIN'];
                            $ARARI_total += (int) $value['ARARI'];
                            $RYUHO_total += (int) $value['RYUHO'];
                            $TOUARA_total += (int) $value['TOUARA'];
                            $TKIARA_total += (int) $value['TKIARA'];
                            $ZKIARA_total += (int) $value['ZKIARA'];
                            $ZKI_DAI_total += (int) $value['ZKI_DAI'];
                            $TKI_DAI_total += (int) $value['TKI_DAI'];
                        }
                        foreach ((array) $data as $key => $value) {
                            $data[$key]['DAISU_total'] = $DAISU_total;
                            $data[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total), 0, 1);
                            $data[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total), 0, 1);
                            $data[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                            $data[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                            $data[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                            $data[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                            $data[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                            $data[$key]['TKI_DAI_total'] = $TKI_DAI_total;
                        }

                        $this->data_rptArariekiBase = $data;
                        $type = "rptArariekiBase";
                        $this->fncDealBaseData();
                        $this->result = $this->fncExportExcel($type);

                        break;
                    default:
                        break;
                }

                $this->fncReturn($this->result);
                return;
            } else {
                if ($tmpPdfName1 == "rptArariekiChkList" && $tmpPdfName2 == "rptArariekiHyo") {
                    $data1 = $this->result_objDs['data'][0];
                    $data2 = $this->result_objDs2['data'];

                    //name2
                    //---
                    $DAISU_total = 0;
                    $URIAGEKIN_total = 0;
                    $ARARI_total = 0;
                    $RYUHO_total = 0;
                    $TOUARA_total = 0;
                    $TKIARA_total = 0;
                    $ZKIARA_total = 0;
                    $ZKI_DAI_total = 0;
                    $TKI_DAI_total = 0;
                    //------
                    //------
                    foreach ((array) $data2 as $key => $value) {
                        $DAISU_total += (int) $value['DAISU'];
                        $URIAGEKIN_total += (int) $value['URIAGEKIN'];
                        $ARARI_total += (int) $value['ARARI'];
                        $RYUHO_total += (int) $value['RYUHO'];
                        $TOUARA_total += (int) $value['TOUARA'];
                        $TKIARA_total += (int) $value['TKIARA'];
                        $ZKIARA_total += (int) $value['ZKIARA'];
                        $ZKI_DAI_total += (int) $value['ZKI_DAI'];
                        $TKI_DAI_total += (int) $value['TKI_DAI'];
                    }
                    foreach ((array) $data2 as $key => $value) {
                        $data2[$key]['DAISU_total'] = $DAISU_total;
                        $data2[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total), 0, 1);
                        $data2[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total), 0, 1);
                        $data2[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                        $data2[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                        $data2[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                        $data2[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                        $data2[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                        $data2[$key]['TKI_DAI_total'] = $TKI_DAI_total;
                    }

                    $this->data_rptArariekiChkList = $data1;
                    $this->data_rptArariekiHyo = $data2;
                    $type = "all123";
                    $this->fncDealData();
                    $this->result = $this->fncExportExcel($type);

                    $this->fncReturn($this->result);
                    return;
                } elseif ($tmpPdfName1 == "rptArariekiChkList" && $tmpPdfName2 == "rptArariekiBase") {
                    $data1 = $this->result_objDs['data'][0];
                    $data2 = $this->result_objDs3['data'];

                    //name2
                    //---
                    $DAISU_total = 0;
                    $URIAGEKIN_total = 0;
                    $ARARI_total = 0;
                    $RYUHO_total = 0;
                    $TOUARA_total = 0;
                    $TKIARA_total = 0;
                    $ZKIARA_total = 0;
                    $ZKI_DAI_total = 0;
                    $TKI_DAI_total = 0;
                    //------
                    //------
                    foreach ((array) $data2 as $key => $value) {
                        $DAISU_total += (int) $value['DAISU'];
                        $URIAGEKIN_total += (int) $value['URIAGEKIN'];
                        $ARARI_total += (int) $value['ARARI'];
                        $RYUHO_total += (int) $value['RYUHO'];
                        $TOUARA_total += (int) $value['TOUARA'];
                        $TKIARA_total += (int) $value['TKIARA'];
                        $ZKIARA_total += (int) $value['ZKIARA'];
                        $ZKI_DAI_total += (int) $value['ZKI_DAI'];
                        $TKI_DAI_total += (int) $value['TKI_DAI'];
                    }
                    foreach ((array) $data2 as $key => $value) {
                        $data2[$key]['DAISU_total'] = $DAISU_total;
                        $data2[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total), 0, 1);
                        $data2[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total), 0, 1);
                        $data2[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                        $data2[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                        $data2[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                        $data2[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                        $data2[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                        $data2[$key]['TKI_DAI_total'] = $TKI_DAI_total;
                    }

                    $this->data_rptArariekiChkList = $data1;
                    $this->data_rptArariekiBase = $data2;
                    $type = "all123";
                    $this->fncDealBaseData();
                    $this->result = $this->fncExportExcel($type);

                    $this->fncReturn($this->result);
                    return;
                } else {
                    $data = $this->result_objDs2['data'];
                    $data2 = $this->result_objDs3['data'];

                    $DAISU_total = 0;
                    $URIAGEKIN_total = 0;
                    $ARARI_total = 0;
                    $RYUHO_total = 0;
                    $TOUARA_total = 0;
                    $TKIARA_total = 0;
                    $ZKIARA_total = 0;
                    $ZKI_DAI_total = 0;
                    $TKI_DAI_total = 0;
                    //------
                    //------
                    foreach ((array) $data as $key => $value) {
                        $DAISU_total += (int) $value['DAISU'];
                        $URIAGEKIN_total += (int) $value['URIAGEKIN'];
                        $ARARI_total += (int) $value['ARARI'];
                        $RYUHO_total += (int) $value['RYUHO'];
                        $TOUARA_total += (int) $value['TOUARA'];
                        $TKIARA_total += (int) $value['TKIARA'];
                        $ZKIARA_total += (int) $value['ZKIARA'];
                        $ZKI_DAI_total += (int) $value['ZKI_DAI'];
                        $TKI_DAI_total += (int) $value['TKI_DAI'];
                    }
                    foreach ((array) $data as $key => $value) {
                        $data[$key]['DAISU_total'] = $DAISU_total;
                        $data[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total), 0, 1);
                        $data[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total), 0, 1);
                        $data[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                        $data[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                        $data[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                        $data[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                        $data[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                        $data[$key]['TKI_DAI_total'] = $TKI_DAI_total;
                    }

                    //name2
                    //---
                    $DAISU_total = 0;
                    $URIAGEKIN_total = 0;
                    $ARARI_total = 0;
                    $RYUHO_total = 0;
                    $TOUARA_total = 0;
                    $TKIARA_total = 0;
                    $ZKIARA_total = 0;
                    $ZKI_DAI_total = 0;
                    $TKI_DAI_total = 0;
                    //------
                    //------
                    foreach ((array) $data2 as $key => $value) {
                        $DAISU_total += (int) $value['DAISU'];
                        $URIAGEKIN_total += (int) $value['URIAGEKIN'];
                        $ARARI_total += (int) $value['ARARI'];
                        $RYUHO_total += (int) $value['RYUHO'];
                        $TOUARA_total += (int) $value['TOUARA'];
                        $TKIARA_total += (int) $value['TKIARA'];
                        $ZKIARA_total += (int) $value['ZKIARA'];
                        $ZKI_DAI_total += (int) $value['ZKI_DAI'];
                        $TKI_DAI_total += (int) $value['TKI_DAI'];
                    }
                    foreach ((array) $data2 as $key => $value) {
                        $data2[$key]['DAISU_total'] = $DAISU_total;
                        $data2[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total), 0, 1);
                        $data2[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total), 0, 1);
                        $data2[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                        $data2[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                        $data2[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                        $data2[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                        $data2[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                        $data2[$key]['TKI_DAI_total'] = $TKI_DAI_total;
                    }

                    $this->data_rptArariekiHyo = $data;
                    $this->data_rptArariekiBase = $data2;
                    $type = "all123";
                    $this->fncDealData();
                    $this->fncDealBaseData();
                    $this->result = $this->fncExportExcel($type);

                    $this->fncReturn($this->result);
                    return;
                }
            }
        } else {
            $data = $this->result_objDs['data'][0];
            $data2 = $this->result_objDs2['data'];
            $data3 = $this->result_objDs3['data'];
            $DAISU_total = 0;
            $URIAGEKIN_total = 0;
            $ARARI_total = 0;
            $RYUHO_total = 0;
            $TOUARA_total = 0;
            $TKIARA_total = 0;
            $ZKIARA_total = 0;
            $ZKI_DAI_total = 0;
            $TKI_DAI_total = 0;
            //------
            //------
            foreach ((array) $data2 as $key => $value) {
                $DAISU_total += (int) $value['DAISU'];
                $URIAGEKIN_total += (int) $value['URIAGEKIN'];
                $ARARI_total += (int) $value['ARARI'];
                $RYUHO_total += (int) $value['RYUHO'];
                $TOUARA_total += (int) $value['TOUARA'];
                $TKIARA_total += (int) $value['TKIARA'];
                $ZKIARA_total += (int) $value['ZKIARA'];
                $ZKI_DAI_total += (int) $value['ZKI_DAI'];
                $TKI_DAI_total += (int) $value['TKI_DAI'];
            }
            foreach ((array) $data2 as $key => $value) {
                $data2[$key]['DAISU_total'] = $DAISU_total;
                $data2[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total), 0, 1);
                $data2[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total), 0, 1);
                $data2[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                $data2[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                $data2[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                $data2[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                $data2[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                $data2[$key]['TKI_DAI_total'] = $TKI_DAI_total;
            }

            $DAISU_total = 0;
            $URIAGEKIN_total = 0;
            $ARARI_total = 0;
            $RYUHO_total = 0;
            $TOUARA_total = 0;
            $TKIARA_total = 0;
            $ZKIARA_total = 0;
            $ZKI_DAI_total = 0;
            $TKI_DAI_total = 0;
            //------
            //------
            foreach ((array) $data3 as $key => $value) {
                $DAISU_total += (int) $value['DAISU'];
                $URIAGEKIN_total += (int) $value['URIAGEKIN'];
                $ARARI_total += (int) $value['ARARI'];
                $RYUHO_total += (int) $value['RYUHO'];
                $TOUARA_total += (int) $value['TOUARA'];
                $TKIARA_total += (int) $value['TKIARA'];
                $ZKIARA_total += (int) $value['ZKIARA'];
                $ZKI_DAI_total += (int) $value['ZKI_DAI'];
                $TKI_DAI_total += (int) $value['TKI_DAI'];
            }
            foreach ((array) $data3 as $key => $value) {
                $data3[$key]['DAISU_total'] = $DAISU_total;
                $data3[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total), 0, 1);
                $data3[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total), 0, 1);
                $data3[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                $data3[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                $data3[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                $data3[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                $data3[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                $data3[$key]['TKI_DAI_total'] = $TKI_DAI_total;
            }

            $this->data_rptArariekiChkList = $data;
            $this->data_rptArariekiHyo = $data2;
            $this->data_rptArariekiBase = $data3;
            $type = "all123";
            $this->fncDealData();
            $this->fncDealBaseData();
            $this->result = $this->fncExportExcel($type);

            $this->fncReturn($this->result);
            return;
        }
    }

    /*
     *deal data of excel
     */
    public function fncDealData()
    {
        $UriDai = 0;
        $ArariDai = 0;
        $RyuhoDai = 0;
        $TouDai = 0;
        $TkiDai = 0;
        $ZkiDai = 0;
        $cnt = 0;
        if (count($this->data_rptArariekiHyo) > 0) {
            foreach ($this->data_rptArariekiHyo as $key => $data) {
                $this->data_rptArariekiHyo[$key] = (array) $this->data_rptArariekiHyo[$key];
                //uridai
                if ($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['DAISU_total']) == 0) {
                    $UriDai = "0";
                } else {
                    $UriDai = number_format($this->fncRoundDou(($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['URIAGEKIN_total']) / 1000) / $this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['DAISU_total']), 0, 1));
                }
                //ArariDai
                if ($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['DAISU_total']) == 0) {
                    $ArariDai = "0";
                } else {
                    $ArariDai = number_format($this->fncRoundDou(($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['ARARI_total']) / 1000) / $this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['DAISU_total']), 0, 1));
                }
                //RyuhoDai
                if ($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['DAISU_total']) == 0) {
                    $RyuhoDai = "0";
                } else {
                    $RyuhoDai = number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['RYUHO_total']) / $this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['DAISU_total']), 0, 1));
                }
                //TouDai
                if ($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['DAISU_total']) == 0) {
                    $TouDai = "0";
                } else {
                    $TouDai = number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['TOUARA_total']) / $this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['DAISU_total']), 0, 1));
                }
                //TkiDai
                $TkiDai = ($this->data_rptArariekiHyo[$key]['TKI_DAI_total'] == 0) ? "0" : number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['TKIARA_total']) / $this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['TKI_DAI_total']), 0, 1));
                //ZkiDai
                $ZkiDai = ($this->data_rptArariekiHyo[$key]['ZKI_DAI_total'] == 0) ? "0" : number_format($this->fncRoundDou(($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['ZKIARA_total'])) / ($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['ZKI_DAI_total'])), 0, 1));
                //SoukanUri
                $SoukanUri = $this->fncRoundDou((round($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['URIAGEKIN_total'])) + round($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['HONTAIGAKU']))) / 1000, 0, 1);
                //SoukanArari
                $SoukanArari = number_format($this->fncRoundDou((round($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['ARARI_total'])) + round($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['SYARYOARARI']))) / 1000, 0, 1));
                //ARARI
                $this->data_rptArariekiHyo[$key]['ARARI'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['ARARI']) / 1000, 0, 1);
                //RYUHO
                $this->data_rptArariekiHyo[$key]['RYUHO'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['RYUHO']) / 1000, 0, 1);
                //TOUARA
                $this->data_rptArariekiHyo[$key]['TOUARA'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['TOUARA']) / 1000, 0, 1);
                //TKIARA
                $this->data_rptArariekiHyo[$key]['TKIARA'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['TKIARA']) / 1000, 0, 1);
                //ZKIARA
                $this->data_rptArariekiHyo[$key]['ZKIARA'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['ZKIARA']) / 1000, 0, 1);
                //URIAGEKIN
                if ($this->data_rptArariekiHyo[$key]['URIAGEKIN'] == "") {
                    $this->data_rptArariekiHyo[$key]['URIAGEKIN'] = 0;
                }
                $this->data_rptArariekiHyo[$key]['URIAGEKIN'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['URIAGEKIN']) / 1000, 0, 1);
                //HONTAIGAKU
                $this->data_rptArariekiHyo[$key]['HONTAIGAKU'] = number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['HONTAIGAKU']) / 1000, 0, 1));
                //SYARYOARARI
                $this->data_rptArariekiHyo[$key]['SYARYOARARI'] = number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$key]['SYARYOARARI']) / 1000, 0, 1));
                //ARARI_total
                if ($this->data_rptArariekiHyo[$key]['ARARI_total'] == "0.0") {
                    $this->data_rptArariekiHyo[$key]['ARARI_total'] = (int) $this->data_rptArariekiHyo[$key]['ARARI_total'];
                }
                //RYUHO_total
                if ($this->data_rptArariekiHyo[$key]['RYUHO_total'] == "0.0") {
                    $this->data_rptArariekiHyo[$key]['RYUHO_total'] = (int) $this->data_rptArariekiHyo[$key]['RYUHO_total'];
                }
                //TOUARA_total
                if ($this->data_rptArariekiHyo[$key]['TOUARA_total'] == "0.0") {
                    $this->data_rptArariekiHyo[$key]['TOUARA_total'] = (int) $this->data_rptArariekiHyo[$key]['TOUARA_total'];
                }
                //URIAGEKIN_total
                if ($this->data_rptArariekiHyo[$key]['URIAGEKIN_total'] == "0.0") {
                    $this->data_rptArariekiHyo[$key]['URIAGEKIN_total'] = (int) $this->data_rptArariekiHyo[$key]['URIAGEKIN_total'];
                }
                $cnt++;
            }

            $this->data_rptArariekiHyo['UriDai'] = $UriDai;
            $this->data_rptArariekiHyo['ArariDai'] = $ArariDai;
            $this->data_rptArariekiHyo['RyuhoDai'] = $RyuhoDai;
            $this->data_rptArariekiHyo['TouDai'] = $TouDai;
            $this->data_rptArariekiHyo['TkiDai'] = $TkiDai;
            $this->data_rptArariekiHyo['ZkiDai'] = $ZkiDai;
            $this->data_rptArariekiHyo['SoukanArari'] = $SoukanArari;
            $this->data_rptArariekiHyo['SoukanUri'] = $SoukanUri;

            $this->data_rptArariekiHyo['DAISU_total'] = $this->data_rptArariekiHyo[$cnt - 1]['DAISU_total'];
            $this->data_rptArariekiHyo['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$cnt - 1]['URIAGEKIN_total']) / 1000, 0, 1);
            $this->data_rptArariekiHyo['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiHyo[$cnt - 1]['ARARI_total']) / 1000, 0, 1);
            $this->data_rptArariekiHyo['RYUHO_total'] = $this->data_rptArariekiHyo[$cnt - 1]['RYUHO_total'];
            $this->data_rptArariekiHyo['TOUARA_total'] = $this->data_rptArariekiHyo[$cnt - 1]['TOUARA_total'];
            $this->data_rptArariekiHyo['TKIARA_total'] = $this->data_rptArariekiHyo[$cnt - 1]['TKIARA_total'];
            $this->data_rptArariekiHyo['ZKIARA_total'] = $this->data_rptArariekiHyo[$cnt - 1]['ZKIARA_total'];
            $this->data_rptArariekiHyo['ZKI_DAI_total'] = $this->data_rptArariekiHyo[$cnt - 1]['ZKI_DAI_total'];
            $this->data_rptArariekiHyo['TKI_DAI_total'] = $this->data_rptArariekiHyo[$cnt - 1]['TKI_DAI_total'];
        }
    }

    public function fncDealBaseData()
    {
        $UriDai = 0;
        $ArariDai = 0;
        $RyuhoDai = 0;
        $TouDai = 0;
        $TkiDai = 0;
        $ZkiDai = 0;
        $cnt = 0;
        if (count($this->data_rptArariekiBase) > 0) {
            foreach ($this->data_rptArariekiBase as $key => $data) {
                $this->data_rptArariekiBase[$key] = (array) $this->data_rptArariekiBase[$key];
                //uridai
                if ($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['DAISU_total']) == 0) {
                    $UriDai = "0";
                } else {
                    $UriDai = number_format($this->fncRoundDou(($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['URIAGEKIN_total']) / 1000) / $this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['DAISU_total']), 0, 1));
                }
                //ArariDai
                if ($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['DAISU_total']) == 0) {
                    $ArariDai = "0";
                } else {
                    $ArariDai = number_format($this->fncRoundDou(($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['ARARI_total']) / 1000) / $this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['DAISU_total']), 0, 1));
                }
                //RyuhoDai
                if ($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['DAISU_total']) == 0) {
                    $RyuhoDai = "0";
                } else {
                    $RyuhoDai = number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['RYUHO_total']) / $this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['DAISU_total']), 0, 1));
                }
                //TouDai
                if ($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['DAISU_total']) == 0) {
                    $TouDai = "0";
                } else {
                    $TouDai = number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['TOUARA_total']) / $this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['DAISU_total']), 0, 1));
                }
                //TkiDai
                $TkiDai = ($this->data_rptArariekiBase[$key]['TKI_DAI_total'] == 0) ? "0" : number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['TKIARA_total']) / $this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['TKI_DAI_total']), 0, 1));
                //ZkiDai
                $ZkiDai = ($this->data_rptArariekiBase[$key]['ZKI_DAI_total'] == 0) ? "0" : number_format($this->fncRoundDou(($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['ZKIARA_total'])) / ($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['ZKI_DAI_total'])), 0, 1));
                //SoukanUri
                $SoukanUri = $this->fncRoundDou((round($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['URIAGEKIN_total'])) + round($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['HONTAIGAKU']))) / 1000, 0, 1);
                //SoukanArari
                $SoukanArari = number_format($this->fncRoundDou((round($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['ARARI_total'])) + round($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['SYARYOARARI']))) / 1000, 0, 1));
                //ARARI
                $this->data_rptArariekiBase[$key]['ARARI'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['ARARI']) / 1000, 0, 1);
                //RYUHO
                $this->data_rptArariekiBase[$key]['RYUHO'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['RYUHO']) / 1000, 0, 1);
                //TOUARA
                $this->data_rptArariekiBase[$key]['TOUARA'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['TOUARA']) / 1000, 0, 1);
                //TKIARA
                $this->data_rptArariekiBase[$key]['TKIARA'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['TKIARA']) / 1000, 0, 1);
                //ZKIARA
                $this->data_rptArariekiBase[$key]['ZKIARA'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['ZKIARA']) / 1000, 0, 1);
                //URIAGEKIN
                if ($this->data_rptArariekiBase[$key]['URIAGEKIN'] == "") {
                    $this->data_rptArariekiBase[$key]['URIAGEKIN'] = 0;
                }
                $this->data_rptArariekiBase[$key]['URIAGEKIN'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['URIAGEKIN']) / 1000, 0, 1);
                //HONTAIGAKU
                $this->data_rptArariekiBase[$key]['HONTAIGAKU'] = number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['HONTAIGAKU']) / 1000, 0, 1));
                //SYARYOARARI
                $this->data_rptArariekiBase[$key]['SYARYOARARI'] = number_format($this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$key]['SYARYOARARI']) / 1000, 0, 1));
                //ARARI_total
                if ($this->data_rptArariekiBase[$key]['ARARI_total'] == "0.0") {
                    $this->data_rptArariekiBase[$key]['ARARI_total'] = (int) $this->data_rptArariekiBase[$key]['ARARI_total'];
                }
                //RYUHO_total
                if ($this->data_rptArariekiBase[$key]['RYUHO_total'] == "0.0") {
                    $this->data_rptArariekiBase[$key]['RYUHO_total'] = (int) $this->data_rptArariekiBase[$key]['RYUHO_total'];
                }
                //TOUARA_total
                if ($this->data_rptArariekiBase[$key]['TOUARA_total'] == "0.0") {
                    $this->data_rptArariekiBase[$key]['TOUARA_total'] = (int) $this->data_rptArariekiBase[$key]['TOUARA_total'];
                }
                //URIAGEKIN_total
                if ($this->data_rptArariekiBase[$key]['URIAGEKIN_total'] == "0.0") {
                    $this->data_rptArariekiBase[$key]['URIAGEKIN_total'] = (int) $this->data_rptArariekiBase[$key]['URIAGEKIN_total'];
                }
                $cnt++;
            }

            $this->data_rptArariekiBase['UriDai'] = $UriDai;
            $this->data_rptArariekiBase['ArariDai'] = $ArariDai;
            $this->data_rptArariekiBase['RyuhoDai'] = $RyuhoDai;
            $this->data_rptArariekiBase['TouDai'] = $TouDai;
            $this->data_rptArariekiBase['TkiDai'] = $TkiDai;
            $this->data_rptArariekiBase['ZkiDai'] = $ZkiDai;
            $this->data_rptArariekiBase['SoukanArari'] = $SoukanArari;
            $this->data_rptArariekiBase['SoukanUri'] = $SoukanUri;

            $this->data_rptArariekiBase['DAISU_total'] = $this->data_rptArariekiBase[$cnt - 1]['DAISU_total'];
            $this->data_rptArariekiBase['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$cnt - 1]['URIAGEKIN_total']) / 1000, 0, 1);
            $this->data_rptArariekiBase['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($this->data_rptArariekiBase[$cnt - 1]['ARARI_total']) / 1000, 0, 1);
            $this->data_rptArariekiBase['RYUHO_total'] = $this->data_rptArariekiBase[$cnt - 1]['RYUHO_total'];
            $this->data_rptArariekiBase['TOUARA_total'] = $this->data_rptArariekiBase[$cnt - 1]['TOUARA_total'];
            $this->data_rptArariekiBase['TKIARA_total'] = $this->data_rptArariekiBase[$cnt - 1]['TKIARA_total'];
            $this->data_rptArariekiBase['ZKIARA_total'] = $this->data_rptArariekiBase[$cnt - 1]['ZKIARA_total'];
            $this->data_rptArariekiBase['ZKI_DAI_total'] = $this->data_rptArariekiBase[$cnt - 1]['ZKI_DAI_total'];
            $this->data_rptArariekiBase['TKI_DAI_total'] = $this->data_rptArariekiBase[$cnt - 1]['TKI_DAI_total'];
        }
    }

    /*
     * return  path of excel
     */
    public function fncExportExcel($type)
    {
        $result = array('result' => '', 'data' => "");
        try {
            //set output file path
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/KRSS/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
            //path is exist
            if (!file_exists($tmpPath)) {
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    $result["data"] = "Execl Error";
                    throw new \Exception($result["data"]);
                }
            }

            $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
            $PHPReader = new XlsxReader();
            /*
             * ----------------------------------------------------------------------------------
             * type=rptArariekiChkList : 使用 $this->data_rptArariekiChkList;
             * type=rptArariekiHyo : 使用 $this->data_rptArariekiChkList;
             * type=all :使用 $this->data_rptArariekiChkList 和 $this->data_rptArariekiChkList;
             * ----------------------------------------------------------------------------------
             */

            if ($type == "rptArariekiChkList") {

                //set outputfile name
                $file = $tmpPath . "新車車種別粗利益チェックリスト_" . $this->USERID . ".xlsx";
                //$file = $tmpPath . "新車車種別粗利益チェックリスト.xls";
                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmSyasyuArariChkListKRSSTemplate.xlsx";
                //テンプレートファイルの存在確認
                if (file_exists($strTemplatePath) == FALSE) {

                    $result["data"] = "EXCELテンプレートが見つかりません！";
                    throw new \Exception($result["data"]);
                }
                $PHPExcel = $PHPReader->load($strTemplatePath);
                $PHPExcel->setActiveSheetIndex(0);

                $i = 8;

                $start = $i;
                //$PHPExcel -> getActiveSheet(0) -> removeColumn('A', 1);
                //$PHPExcel -> getActiveSheet(0) -> setCellValue('A' . $i, "");

                $column = $PHPExcel->getActiveSheet()->getHighestColumn();
                $row = $PHPExcel->getActiveSheet()->getHighestRow();

                $ABC = array();
                for ($i = 'A'; $i != $column; $i++) {
                    array_push($ABC, $i);
                }

                foreach ($ABC as $value) {
                    for ($i = $start; $i < $row; $i++) {
                        $FirCell = $PHPExcel->getActiveSheet()->getCell($value . $i)->getValue() ?? "";
                        $Cell = str_replace(["{", "}"], "", $FirCell);
                        foreach ((array) $this->data_rptArariekiChkList as $key1 => $value1) {
                            if ($key1 == $Cell) {
                                $PHPExcel->getActiveSheet()->setCellValue(
                                    $value . $i,
                                    $value1 === null ? "" : number_format($value1)
                                );
                            }
                        }
                    }
                    for ($i = 0; $i < $start; $i++) {
                        if ($PHPExcel->getActiveSheet()->getCell($value . $i)->getValue() instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                            $NEN = $PHPExcel->getActiveSheet()->getCell($value . $i)->getValue()->getPlainText();
                        } else {
                            $NEN = $PHPExcel->getActiveSheet()->getCell($value . $i)->getValue() ?? "";
                        }
                        if ($NEN == "{NEN}年{TUKI}月度") {
                            $replace = str_replace("{NEN}", $this->data_rptArariekiChkList["NEN"] ?? "", $NEN);
                            $TUKI = str_replace("{TUKI}", $this->data_rptArariekiChkList["TUKI"] ?? "", $replace);
                            $PHPExcel->getActiveSheet()->setCellValue($value . $i, $TUKI);
                        }
                    }
                }
                $styleArray = [
                    'borders' => [
                        'horizontal' => [
                            'style' => ['borderStyle' => Border::BORDER_THIN]
                        ]
                    ]
                ];
                //$PHPExcel -> getActiveSheet(0) -> getStyle('A' . ($start - 1) . ":" . $column . $row) -> applyFromArray($styleArray);
                $objWriter = new XlsxWriter($PHPExcel);
                $PHPExcel->setActiveSheetIndex(1);
                $PHPExcel->removeSheetByIndex(1);
                $PHPExcel->setActiveSheetIndex(1);
                $PHPExcel->removeSheetByIndex(1);
                $objWriter->save($file);
                //$result1 = "files/KRSS/" . "新車車種別粗利益チェックリスト_FrmSyasyuArariChkListKRSS.xls";
                $result1 = "files/KRSS/" . "新車車種別粗利益チェックリスト_" . $this->USERID . ".xlsx";
                $result['data'] = $result1;
                $result['result'] = TRUE;
                //
                return $result;
            }
            if ($type == "rptArariekiHyo") {

                $file = $tmpPath . "新車車種別粗利益表_" . $this->USERID . ".xlsx";

                //エクセルのテンプレートが保存されている場所を取得
                // $strTemplatePath = $this -> ClsComFnc -> FncGetPath("ExcelLayoutPath");
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmSyasyuArariChkListKRSSTemplate.xlsx";
                //テンプレートファイルの存在確認
                if (file_exists($strTemplatePath) == FALSE) {
                    $result["data"] = "EXCELテンプレートが見つかりません！";
                    throw new \Exception($result["data"]);
                }
                $PHPExcel = $PHPReader->load($strTemplatePath);
                $PHPExcel->setActiveSheetIndex(1);
                $i = 6;
                $loc = $i;
                $column = $PHPExcel->getActiveSheet()->getHighestColumn();
                $ABC = array();
                for ($i = 'A'; $i != $column; $i++) {
                    array_push($ABC, $i);
                }

                $ABC1 = array();
                $ABC2 = array();
                foreach ($ABC as $value) {
                    $cell1 = $PHPExcel->getActiveSheet()->getCell($value . $loc)->getValue() ?? "";
                    $cell1 = str_replace(["{", "}"], "", $cell1);
                    switch ($cell1) {
                        case 'SS_NAME':
                            $ABC1["SS_NAME"] = $value;
                            break;
                        case 'DAISU':
                            $ABC1["DAISU"] = $value;
                            break;
                        case 'URIAGEKIN':
                            $ABC1["URIAGEKIN"] = $value;
                            break;
                        case 'ARARI':
                            $ABC1["ARARI"] = $value;
                            break;
                        case 'RYUHO':
                            $ABC1["RYUHO"] = $value;
                            break;
                        case 'TOUARA':
                            $ABC1["TOUARA"] = $value;
                            break;
                        case 'TKIARA':
                            $ABC1["TKIARA"] = $value;
                            break;
                        case 'ZKIARA':
                            $ABC1["ZKIARA"] = $value;
                            break;
                    }
                    $cell2 = $PHPExcel->getActiveSheet()->getCell($value . ($loc + 1))->getValue() ?? "";
                    $cell2 = str_replace(["{", "}"], "", $cell2);

                    switch ($cell2) {
                        case 'TKI_DAI':
                            $PHPExcel->getActiveSheet()->setCellValue($value . ($loc + 1), "");
                            break;
                        case 'ZKI_DAI':
                            $PHPExcel->getActiveSheet()->setCellValue($value . ($loc + 1), "");
                            break;
                        case '(台当り)':
                            $ABC2["(台当り)"] = $value;
                            break;
                        case 'URI_DAI':
                            $ABC2["URI_DAI"] = $value;
                            break;
                        case 'ARARI_DAI':
                            $ABC2["ARARI_DAI"] = $value;
                            break;
                        case 'RYUHO_DAI':
                            $ABC2["RYUHO_DAI"] = $value;
                            break;
                        case 'TOUARA_DAI':
                            $ABC2["TOUARA_DAI"] = $value;
                            break;
                        case 'TKIARA_DAI':
                            $ABC2["TKIARA_DAI"] = $value;
                            break;
                        case 'ZKIARA_DAI':
                            $ABC2["ZKIARA_DAI"] = $value;
                            break;
                    }
                }

                foreach ($this->data_rptArariekiHyo as $value) {
                    if (is_array($value) == TRUE) {

                        foreach ((array) $value as $key1 => $value1) {
                            foreach ($ABC1 as $key2 => $value2) {
                                if ($key2 == $key1) {
                                    //if ($key2 == "TKIARA" || $key2 == "ZKIARA")
                                    //{
                                    $PHPExcel->getActiveSheet()->getStyle($value2 . $loc)->getNumberFormat()->setFormatCode("#,##0");
                                    $PHPExcel->getActiveSheet()->setCellValue($value2 . $loc, $value1);
                                    //}
                                    //else
                                    //{
                                    //$PHPExcel -> getActiveSheet() -> setCellValue($value2 . $loc, $value1);
                                    //}

                                }
                            }
                            foreach ($ABC2 as $key3 => $value3) {
                                if ($key3 == $key1) {
                                    // if ($key3 == "TKIARA_DAI" || $key3 == "ZKIARA_DAI")
                                    // {
                                    $PHPExcel->getActiveSheet()->getStyle($value3 . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                                    $PHPExcel->getActiveSheet()->setCellValue($value3 . ($loc + 1), $value1);
                                    // }
                                    // else
                                    // {
                                    // $PHPExcel -> getActiveSheet() -> setCellValue($value3 . ($loc + 1), $value1);
                                    // }

                                }
                            }
                            $PHPExcel->getActiveSheet()->setCellValue($ABC2["(台当り)"] . ($loc + 1), "(台当り)");
                        }
                        $styleArray = [
                            'borders' => [
                                'top' => ['borderStyle' => Border::BORDER_THIN]
                            ]
                        ];
                        $PHPExcel->getActiveSheet()->getStyle('B' . $loc . ":" . 'J' . ($loc + 1))->applyFromArray($styleArray);
                        $styleArray = [
                            'borders' => [
                                'bottom' => ['borderStyle' => Border::BORDER_THIN]
                            ]
                        ];
                        $PHPExcel->getActiveSheet()->getStyle('B' . $loc . ":" . 'J' . ($loc + 1))->applyFromArray($styleArray);
                        $loc = $loc + 2;
                    }

                }
                $NEN = $this->data_rptArariekiHyo[0]["NEN"];
                $TUKI = $this->data_rptArariekiHyo[0]["TUKI"];
                $TAISYO_NEN = $this->data_rptArariekiHyo[0]["TAISYO_NEN"];

                $PHPExcel->getActiveSheet()->setCellValue($ABC1["SS_NAME"] . $loc, "(計)");
                $PHPExcel->getActiveSheet()->getStyle($ABC1["DAISU"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["DAISU"] . $loc, $this->data_rptArariekiHyo["DAISU_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . $loc, $this->data_rptArariekiHyo["URIAGEKIN_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . $loc, $this->data_rptArariekiHyo["ARARI_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["RYUHO"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["RYUHO"] . $loc, $this->data_rptArariekiHyo["RYUHO_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["TOUARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["TOUARA"] . $loc, $this->data_rptArariekiHyo["TOUARA_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["TKIARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["TKIARA"] . $loc, $this->data_rptArariekiHyo["TKIARA_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ZKIARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["ZKIARA"] . $loc, $this->data_rptArariekiHyo["ZKIARA_total"]);
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["(台当り)"] . ($loc + 1), "(台当り)");
                $PHPExcel->getActiveSheet()->getStyle($ABC2["URI_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["URI_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["UriDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["ARARI_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["ARARI_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["ArariDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["RYUHO_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["RYUHO_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["RyuhoDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["TOUARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["TOUARA_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["TouDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["TKIARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["TKIARA_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["TkiDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["ZKIARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["ZKIARA_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["ZkiDai"]);
                $styleArray = [
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ];
                $PHPExcel->getActiveSheet()->getStyle('B' . $loc . ":" . 'J' . ($loc + 1))->applyFromArray($styleArray);
                $styleArray = [
                    'borders' => [
                        'bottom' => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ];
                $PHPExcel->getActiveSheet()->getStyle('B' . $loc . ":" . 'J' . ($loc + 1))->applyFromArray($styleArray);
                $loc = $loc + 2;
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["SS_NAME"] . $loc, "ボルボ車転売等");
                $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . $loc, $this->data_rptArariekiHyo[0]["HONTAIGAKU"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . $loc, $this->data_rptArariekiHyo[0]["SYARYOARARI"]);
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["SS_NAME"] . ($loc + 1), "総　勘　金　額");
                $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . ($loc + 1), $this->data_rptArariekiHyo["SoukanUri"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . ($loc + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . ($loc + 1), $this->data_rptArariekiHyo["SoukanArari"]);
                for ($i = 2; $i < 7; $i++) {
                    if ($PHPExcel->getActiveSheet()->getCell('B' . $i)->getValue() instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                        $NEN_TUKI = $PHPExcel->getActiveSheet()->getCell('B' . $i)->getValue()->getPlainText();
                    } else {
                        $NEN_TUKI = $PHPExcel->getActiveSheet()->getCell('B' . $i)->getValue() ?? "";
                    }
                    if ($NEN_TUKI === "{NEN}年{TUKI}月度") {
                        $updatedValue = str_replace(["{NEN}", "{TUKI}"], [$NEN, $TUKI], $NEN_TUKI);
                        $PHPExcel->getActiveSheet()->setCellValue('B' . $i, $updatedValue);
                    }

                    $TAISYO_NEN1 = $PHPExcel->getActiveSheet()->getCell('F' . $i)->getValue() ?? "";
                    if ($TAISYO_NEN1 === "{TAISYO_NEN}") {
                        $updatedValue = str_replace("{TAISYO_NEN}", $TAISYO_NEN, $TAISYO_NEN1);
                        $PHPExcel->getActiveSheet()->setCellValue('F' . $i, $updatedValue);
                    }
                }

                $objWriter = new XlsxWriter($PHPExcel);
                $PHPExcel->setActiveSheetIndex(0);
                $PHPExcel->removeSheetByIndex(0);
                $PHPExcel->setActiveSheetIndex(1);
                $PHPExcel->removeSheetByIndex(1);
                $objWriter->save($file);
                $result1 = "files/KRSS/" . "新車車種別粗利益表_" . $this->USERID . ".xlsx";
                //$result1 = "files/KRSS/" . "新車車種別粗利益チェックリスト_" . $this -> USERID . ".xls";
                $result['data'] = $result1;
                $result['result'] = TRUE;
                return $result;
            }

            if ($type == "rptArariekiBase") {
                $file = $tmpPath . "新車ベースH別粗利益表_" . $this->USERID . ".xlsx";

                //エクセルのテンプレートが保存されている場所を取得
                // $strTemplatePath = $this -> ClsComFnc -> FncGetPath("ExcelLayoutPath");
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmSyasyuArariChkListKRSSTemplate.xlsx";
                //テンプレートファイルの存在確認
                if (file_exists($strTemplatePath) == FALSE) {
                    $result["data"] = "EXCELテンプレートが見つかりません！";
                    throw new \Exception($result["data"]);
                }
                $PHPExcel = $PHPReader->load($strTemplatePath);
                $PHPExcel->setActiveSheetIndex(2);
                $i = 6;
                $loc = $i;
                $column = $PHPExcel->getActiveSheet()->getHighestColumn();
                $ABC = array();
                for ($i = 'A'; $i != $column; $i++) {
                    array_push($ABC, $i);
                }

                $ABC1 = array();
                $ABC2 = array();
                foreach ($ABC as $value) {
                    $cell1 = $PHPExcel->getActiveSheet()->getCell($value . $loc)->getValue() ?? "";
                    $cell1 = str_replace(["{", "}"], "", $cell1);

                    switch ($cell1) {
                        case 'BASEH_CD':
                            $ABC1["BASEH_CD"] = $value;
                            break;
                        case 'BASEH_KN':
                            $ABC1["BASEH_KN"] = $value;
                            break;
                        case 'DAISU':
                            $ABC1["DAISU"] = $value;
                            break;
                        case 'URIAGEKIN':
                            $ABC1["URIAGEKIN"] = $value;
                            break;
                        case 'ARARI':
                            $ABC1["ARARI"] = $value;
                            break;
                        case 'RYUHO':
                            $ABC1["RYUHO"] = $value;
                            break;
                        case 'TOUARA':
                            $ABC1["TOUARA"] = $value;
                            break;
                        case 'TKIARA':
                            $ABC1["TKIARA"] = $value;
                            break;
                        case 'ZKIARA':
                            $ABC1["ZKIARA"] = $value;
                            break;
                    }
                    $cell2 = $PHPExcel->getActiveSheet()->getCell($value . ($loc + 1))->getValue() ?? "";
                    $cell2 = str_replace(["{", "}"], "", $cell2);


                    switch ($cell2) {
                        case 'TKI_DAI':
                            $PHPExcel->getActiveSheet()->setCellValue($value . ($loc + 1), "");
                            break;
                        case 'ZKI_DAI':
                            $PHPExcel->getActiveSheet()->setCellValue($value . ($loc + 1), "");
                            break;
                        case '(台当り)':
                            $ABC2["(台当り)"] = $value;
                            break;
                        case 'URI_DAI':
                            $ABC2["URI_DAI"] = $value;
                            break;
                        case 'ARARI_DAI':
                            $ABC2["ARARI_DAI"] = $value;
                            break;
                        case 'RYUHO_DAI':
                            $ABC2["RYUHO_DAI"] = $value;
                            break;
                        case 'TOUARA_DAI':
                            $ABC2["TOUARA_DAI"] = $value;
                            break;
                        case 'TKIARA_DAI':
                            $ABC2["TKIARA_DAI"] = $value;
                            break;
                        case 'ZKIARA_DAI':
                            $ABC2["ZKIARA_DAI"] = $value;
                            break;
                    }
                }

                foreach ($this->data_rptArariekiBase as $value) {
                    if (is_array($value) == TRUE) {

                        foreach ((array) $value as $key1 => $value1) {
                            foreach ($ABC1 as $key2 => $value2) {
                                if ($key2 == $key1) {
                                    // if ($key2 == "TKIARA" || $key2 == "ZKIARA")
                                    // {
                                    $PHPExcel->getActiveSheet()->getStyle($value2 . $loc)->getNumberFormat()->setFormatCode("#,##0");
                                    $PHPExcel->getActiveSheet()->setCellValue($value2 . $loc, $value1);
                                    // }
                                    // else
                                    // {
                                    // $PHPExcel -> getActiveSheet() -> setCellValue($value2 . $loc, $value1);
                                    // }

                                }
                            }
                            foreach ($ABC2 as $key3 => $value3) {
                                if ($key3 == $key1) {
                                    // if ($key3 == "TKIARA_DAI" || $key3 == "ZKIARA_DAI")
                                    // {
                                    $PHPExcel->getActiveSheet()->getStyle($value3 . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                                    $PHPExcel->getActiveSheet()->setCellValue($value3 . ($loc + 1), $value1);
                                    // }
                                    // else
                                    // {
                                    // $PHPExcel -> getActiveSheet() -> setCellValue($value3 . ($loc + 1), $value1);
                                    // }

                                }
                            }
                            $PHPExcel->getActiveSheet()->setCellValue($ABC2["(台当り)"] . ($loc + 1), "(台当り)");
                        }

                        $loc = $loc + 2;
                    }

                }

                $PHPExcel->getActiveSheet()->setCellValue($ABC1["BASEH_CD"] . $loc, "(計)");
                $PHPExcel->getActiveSheet()->getStyle($ABC1["DAISU"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["DAISU"] . $loc, $this->data_rptArariekiBase["DAISU_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . $loc, $this->data_rptArariekiBase["URIAGEKIN_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . $loc, $this->data_rptArariekiBase["ARARI_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["RYUHO"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["RYUHO"] . $loc, $this->data_rptArariekiBase["RYUHO_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["TOUARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["TOUARA"] . $loc, $this->data_rptArariekiBase["TOUARA_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["TKIARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["TKIARA"] . $loc, $this->data_rptArariekiBase["TKIARA_total"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ZKIARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["ZKIARA"] . $loc, $this->data_rptArariekiBase["ZKIARA_total"]);
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["(台当り)"] . ($loc + 1), "(台当り)");
                $PHPExcel->getActiveSheet()->getStyle($ABC2["URI_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["URI_DAI"] . ($loc + 1), $this->data_rptArariekiBase["UriDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["ARARI_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["ARARI_DAI"] . ($loc + 1), $this->data_rptArariekiBase["ArariDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["RYUHO_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["RYUHO_DAI"] . ($loc + 1), $this->data_rptArariekiBase["RyuhoDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["TOUARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["TOUARA_DAI"] . ($loc + 1), $this->data_rptArariekiBase["TouDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["TKIARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["TKIARA_DAI"] . ($loc + 1), $this->data_rptArariekiBase["TkiDai"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC2["ZKIARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC2["ZKIARA_DAI"] . ($loc + 1), $this->data_rptArariekiBase["ZkiDai"]);

                $loc = $loc + 2;
                $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . $loc, $this->data_rptArariekiBase[0]["HONTAIGAKU"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . $loc, $this->data_rptArariekiBase[0]["SYARYOARARI"]);
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["BASEH_CD"] . ($loc + 1), "総　勘　金　額");
                $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . ($loc + 1), $this->data_rptArariekiBase["SoukanUri"]);
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . ($loc + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . ($loc + 1), $this->data_rptArariekiBase["SoukanArari"]);

                $objWriter = new XlsxWriter($PHPExcel);
                $PHPExcel->setActiveSheetIndex(0);
                $PHPExcel->removeSheetByIndex(0);
                $PHPExcel->setActiveSheetIndex(0);
                $PHPExcel->removeSheetByIndex(0);
                $objWriter->save($file);
                $result1 = "files/KRSS/" . "新車ベースH別粗利益表_" . $this->USERID . ".xlsx";
                //$result1 = "files/KRSS/" . "新車車種別粗利益チェックリスト_" . $this -> USERID . ".xls";
                $result['data'] = $result1;
                $result['result'] = TRUE;
                return $result;
            }
            if ($type == "all123") {
                //set outputfile name
                $file = $tmpPath . "新車車種別粗利益チェックリスト＋表_" . $this->USERID . ".xlsx";

                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmSyasyuArariChkListKRSSTemplate.xlsx";
                //テンプレートファイルの存在確認
                if (file_exists($strTemplatePath) == FALSE) {
                    $result["data"] = "EXCELテンプレートが見つかりません！";
                    throw new \Exception($result["data"]);
                }

                $PHPExcel = $PHPReader->load($strTemplatePath);
                $PHPExcel->setActiveSheetIndex(0);
                if (count((array) $this->data_rptArariekiChkList) > 0) {
                    $i = 8;

                    $start = $i;

                    $column = $PHPExcel->getActiveSheet()->getHighestColumn();
                    $row = $PHPExcel->getActiveSheet()->getHighestRow();

                    $ABC = array();
                    for ($i = 'A'; $i != $column; $i++) {
                        array_push($ABC, $i);
                    }

                    foreach ($ABC as $value) {
                        for ($i = $start; $i < $row; $i++) {
                            $FirCell = $PHPExcel->getActiveSheet()->getCell($value . $i)->getValue() ?? "";
                            $Cell = str_replace(["{", "}"], "", $FirCell);

                            foreach ((array) $this->data_rptArariekiChkList as $key1 => $value1) {
                                if ($key1 == $Cell) {

                                    $PHPExcel->getActiveSheet()->setCellValue($value . $i, number_format($value1 ?? 0));

                                }
                            }
                        }
                        for ($i = 0; $i < $start; $i++) {
                            if ($PHPExcel->getActiveSheet()->getCell($value . $i)->getValue() instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                                $NEN = $PHPExcel->getActiveSheet()->getCell($value . $i)->getValue()->getPlainText();
                            } else {
                                $NEN = $PHPExcel->getActiveSheet()->getCell($value . $i)->getValue() ?? "";
                            }
                            if ($NEN == "{NEN}年{TUKI}月度") {
                                $NEN_value = $this->data_rptArariekiChkList["NEN"] ?? "";
                                $TUKI_value = $this->data_rptArariekiChkList["TUKI"] ?? "";
                                $TUKI = str_replace(["{NEN}", "{TUKI}"], [$NEN_value, $TUKI_value], $NEN);
                                $PHPExcel->getActiveSheet()->setCellValue($value . $i, $TUKI);
                            }
                        }
                    }
                    $styleArray = [
                        'borders' => [
                            'bottom' => ['borderStyle' => Border::BORDER_THIN]
                        ]
                    ];
                }

                $PHPExcel->setActiveSheetIndex(1);
                if (count($this->data_rptArariekiHyo) > 0) {

                    $i = 6;

                    $loc = $i;
                    $column = $PHPExcel->getActiveSheet()->getHighestColumn();
                    $ABC = array();
                    for ($i = 'A'; $i != $column; $i++) {
                        array_push($ABC, $i);
                    }
                    //array_push($ABC, $column);
                    //print_r($ABC);
                    $ABC1 = array();
                    $ABC2 = array();
                    foreach ($ABC as $value) {
                        $cell1 = $PHPExcel->getActiveSheet()->getCell($value . $loc)->getValue() ?? "";
                        $cell1 = str_replace(["{", "}"], "", $cell1);
                        switch ($cell1) {
                            case 'SS_NAME':
                                $ABC1["SS_NAME"] = $value;
                                break;
                            case 'DAISU':
                                $ABC1["DAISU"] = $value;
                                break;
                            case 'URIAGEKIN':
                                $ABC1["URIAGEKIN"] = $value;
                                break;
                            case 'ARARI':
                                $ABC1["ARARI"] = $value;
                                break;
                            case 'RYUHO':
                                $ABC1["RYUHO"] = $value;
                                break;
                            case 'TOUARA':
                                $ABC1["TOUARA"] = $value;
                                break;
                            case 'TKIARA':
                                $ABC1["TKIARA"] = $value;
                                break;
                            case 'ZKIARA':
                                $ABC1["ZKIARA"] = $value;
                                break;
                        }
                        $cell2 = $PHPExcel->getActiveSheet()->getCell($value . ($loc + 1))->getValue() ?? "";
                        $cell2 = str_replace(["{", "}"], "", $cell2);

                        switch ($cell2) {
                            case 'TKI_DAI':
                                $PHPExcel->getActiveSheet()->setCellValue($value . ($loc + 1), "");
                                break;
                            case 'ZKI_DAI':
                                $PHPExcel->getActiveSheet()->setCellValue($value . ($loc + 1), "");
                                break;
                            case '(台当り)':
                                $ABC2["(台当り)"] = $value;
                                break;
                            case 'URI_DAI':
                                $ABC2["URI_DAI"] = $value;
                                break;
                            case 'ARARI_DAI':
                                $ABC2["ARARI_DAI"] = $value;
                                break;
                            case 'RYUHO_DAI':
                                $ABC2["RYUHO_DAI"] = $value;
                                break;
                            case 'TOUARA_DAI':
                                $ABC2["TOUARA_DAI"] = $value;
                                break;
                            case 'TKIARA_DAI':
                                $ABC2["TKIARA_DAI"] = $value;
                                break;
                            case 'ZKIARA_DAI':
                                $ABC2["ZKIARA_DAI"] = $value;
                                break;
                        }
                    }
                    // print_r($ABC1);
                    // print_r($ABC2);

                    foreach ((array) $this->data_rptArariekiHyo as $value) {
                        if (is_array($value) == TRUE) {

                            foreach ($value as $key1 => $value1) {
                                foreach ($ABC1 as $key2 => $value2) {
                                    if ($key2 == $key1) {
                                        // if ($key2 == "TKIARA" || $key2 == "ZKIARA")
                                        // {
                                        $PHPExcel->getActiveSheet()->getStyle($value2 . $loc)->getNumberFormat()->setFormatCode("#,##0");
                                        $PHPExcel->getActiveSheet()->setCellValue($value2 . $loc, $value1);
                                        // }
                                        // else
                                        // {
                                        // $PHPExcel -> getActiveSheet() -> setCellValue($value2 . $loc, $value1);
                                        // }
                                    }
                                }
                                foreach ($ABC2 as $key3 => $value3) {
                                    if ($key3 == $key1) {
                                        // if ($key3 == "TKIARA_DAI" || $key3 == "ZKIARA_DAI")
                                        // {
                                        $PHPExcel->getActiveSheet()->getStyle($value3 . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                                        $PHPExcel->getActiveSheet()->setCellValue($value3 . ($loc + 1), $value1);
                                        // }
                                        // else
                                        // {
                                        // $PHPExcel -> getActiveSheet() -> setCellValue($value3 . ($loc + 1), $value1);
                                        // }
                                    }
                                }
                                $PHPExcel->getActiveSheet()->setCellValue($ABC2["(台当り)"] . ($loc + 1), "(台当り)");
                            }
                            $styleArray = [
                                'borders' => [
                                    'top' => ['borderStyle' => Border::BORDER_THIN]
                                ]
                            ];
                            $PHPExcel->getActiveSheet()->getStyle('B' . $loc . ":" . 'J' . ($loc + 1))->applyFromArray($styleArray);
                            $styleArray = [
                                'borders' => [
                                    'bottom' => ['borderStyle' => Border::BORDER_THIN]
                                ]
                            ];
                            $PHPExcel->getActiveSheet()->getStyle('B' . $loc . ":" . 'J' . ($loc + 1))->applyFromArray($styleArray);
                            $loc = $loc + 2;
                        }

                    }
                    $NEN = $this->data_rptArariekiHyo[0]["NEN"];
                    $TUKI = $this->data_rptArariekiHyo[0]["TUKI"];
                    $TAISYO_NEN = $this->data_rptArariekiHyo[0]["TAISYO_NEN"];

                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["SS_NAME"] . $loc, "(計)");
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["DAISU"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["DAISU"] . $loc, $this->data_rptArariekiHyo["DAISU_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . $loc, $this->data_rptArariekiHyo["URIAGEKIN_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . $loc, $this->data_rptArariekiHyo["ARARI_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["RYUHO"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["RYUHO"] . $loc, $this->data_rptArariekiHyo["RYUHO_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["TOUARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["TOUARA"] . $loc, $this->data_rptArariekiHyo["TOUARA_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["TKIARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["TKIARA"] . $loc, $this->data_rptArariekiHyo["TKIARA_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["ZKIARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["ZKIARA"] . $loc, $this->data_rptArariekiHyo["ZKIARA_total"]);
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["(台当り)"] . ($loc + 1), "(台当り)");
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["URI_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["URI_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["UriDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["ARARI_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["ARARI_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["ArariDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["RYUHO_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["RYUHO_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["RyuhoDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["TOUARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["TOUARA_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["TouDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["TKIARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["TKIARA_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["TkiDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["ZKIARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["ZKIARA_DAI"] . ($loc + 1), $this->data_rptArariekiHyo["ZkiDai"]);
                    $styleArray = [
                        'borders' => [
                            'top' => ['borderStyle' => Border::BORDER_THIN]
                        ]
                    ];
                    $PHPExcel->getActiveSheet()->getStyle('B' . $loc . ":" . 'J' . ($loc + 1))->applyFromArray($styleArray);
                    $styleArray = [
                        'borders' => [
                            'bottom' => ['borderStyle' => Border::BORDER_THIN]
                        ]
                    ];
                    $PHPExcel->getActiveSheet()->getStyle('B' . $loc . ":" . 'J' . ($loc + 1))->applyFromArray($styleArray);
                    $loc = $loc + 2;
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["SS_NAME"] . $loc, "ボルボ車転売等");
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . $loc, $this->data_rptArariekiHyo[0]["HONTAIGAKU"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . $loc, $this->data_rptArariekiHyo[0]["SYARYOARARI"]);
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["SS_NAME"] . ($loc + 1), "総　勘　金　額");
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . ($loc + 1), $this->data_rptArariekiHyo["SoukanUri"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . ($loc + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . ($loc + 1), $this->data_rptArariekiHyo["SoukanArari"]);
                    for ($i = 2; $i < 7; $i++) {
                        if ($PHPExcel->getActiveSheet()->getCell('B' . $i)->getValue() instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                            $NEN_TUKI = $PHPExcel->getActiveSheet()->getCell('B' . $i)->getValue()->getPlainText();
                        } else {
                            $NEN_TUKI = $PHPExcel->getActiveSheet()->getCell('B' . $i)->getValue() ?? "";
                        }
                        if ($NEN_TUKI == "{NEN}年{TUKI}月度") {
                            $TUKI = str_replace(["{NEN}", "{TUKI}"], [$NEN, $TUKI], $NEN_TUKI);
                            $PHPExcel->getActiveSheet()->setCellValue('B' . $i, $TUKI);
                        }
                        $TAISYO_NEN1 = $PHPExcel->getActiveSheet()->getCell('F' . $i)->getValue() ?? "";
                        if ($TAISYO_NEN1 == "{TAISYO_NEN}") {
                            $replace = str_replace("{TAISYO_NEN}", $TAISYO_NEN, $TAISYO_NEN1);
                            $PHPExcel->getActiveSheet()->setCellValue('F' . $i, $replace);
                        }
                    }
                }

                $PHPExcel->setActiveSheetIndex(2);
                if (count((array) $this->data_rptArariekiBase) > 0) {

                    $i = 6;
                    $loc = $i;
                    $column = $PHPExcel->getActiveSheet()->getHighestColumn();
                    $ABC = array();
                    for ($i = 'A'; $i != $column; $i++) {
                        array_push($ABC, $i);
                    }

                    $ABC1 = array();
                    $ABC2 = array();
                    foreach ($ABC as $value) {
                        $cell1 = $PHPExcel->getActiveSheet()->getCell($value . $loc)->getValue() ?? "";
                        $cell1 = str_replace(["{", "}"], "", $cell1);
                        switch ($cell1) {
                            case 'BASEH_CD':
                                $ABC1["BASEH_CD"] = $value;
                                break;
                            case 'BASEH_KN':
                                $ABC1["BASEH_KN"] = $value;
                                break;
                            case 'DAISU':
                                $ABC1["DAISU"] = $value;
                                break;
                            case 'URIAGEKIN':
                                $ABC1["URIAGEKIN"] = $value;
                                break;
                            case 'ARARI':
                                $ABC1["ARARI"] = $value;
                                break;
                            case 'RYUHO':
                                $ABC1["RYUHO"] = $value;
                                break;
                            case 'TOUARA':
                                $ABC1["TOUARA"] = $value;
                                break;
                            case 'TKIARA':
                                $ABC1["TKIARA"] = $value;
                                break;
                            case 'ZKIARA':
                                $ABC1["ZKIARA"] = $value;
                                break;
                        }
                        $cell2 = $PHPExcel->getActiveSheet()->getCell($value . ($loc + 1))->getValue() ?? "";
                        $cell2 = str_replace(["{", "}"], "", $cell2);

                        switch ($cell2) {
                            case 'TKI_DAI':
                                $PHPExcel->getActiveSheet()->setCellValue($value . ($loc + 1), "");
                                break;
                            case 'ZKI_DAI':
                                $PHPExcel->getActiveSheet()->setCellValue($value . ($loc + 1), "");
                                break;
                            case '(台当り)':
                                $ABC2["(台当り)"] = $value;
                                break;
                            case 'URI_DAI':
                                $ABC2["URI_DAI"] = $value;
                                break;
                            case 'ARARI_DAI':
                                $ABC2["ARARI_DAI"] = $value;
                                break;
                            case 'RYUHO_DAI':
                                $ABC2["RYUHO_DAI"] = $value;
                                break;
                            case 'TOUARA_DAI':
                                $ABC2["TOUARA_DAI"] = $value;
                                break;
                            case 'TKIARA_DAI':
                                $ABC2["TKIARA_DAI"] = $value;
                                break;
                            case 'ZKIARA_DAI':
                                $ABC2["ZKIARA_DAI"] = $value;
                                break;
                        }
                    }

                    foreach ($this->data_rptArariekiBase as $value) {
                        if (is_array($value) == TRUE) {

                            foreach ($value as $key1 => $value1) {
                                foreach ($ABC1 as $key2 => $value2) {
                                    if ($key2 == $key1) {
                                        // if ($key2 == "TKIARA" || $key2 == "ZKIARA")
                                        // {
                                        $PHPExcel->getActiveSheet()->getStyle($value2 . $loc)->getNumberFormat()->setFormatCode("#,##0");
                                        $PHPExcel->getActiveSheet()->setCellValue($value2 . $loc, $value1);
                                        // }
                                        // else
                                        // {
                                        // $PHPExcel -> getActiveSheet() -> setCellValue($value2 . $loc, $value1);
                                        // }

                                    }
                                }
                                foreach ($ABC2 as $key3 => $value3) {
                                    if ($key3 == $key1) {
                                        // if ($key3 == "TKIARA_DAI" || $key3 == "ZKIARA_DAI")
                                        // {
                                        $PHPExcel->getActiveSheet()->getStyle($value3 . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                                        $PHPExcel->getActiveSheet()->setCellValue($value3 . ($loc + 1), $value1);
                                        // }
                                        // else
                                        // {
                                        // $PHPExcel -> getActiveSheet() -> setCellValue($value3 . ($loc + 1), $value1);
                                        // }

                                    }
                                }
                                $PHPExcel->getActiveSheet()->setCellValue($ABC2["(台当り)"] . ($loc + 1), "(台当り)");
                            }

                            $loc = $loc + 2;
                        }

                    }

                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["BASEH_CD"] . $loc, "(計)");
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["DAISU"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["DAISU"] . $loc, $this->data_rptArariekiBase["DAISU_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . $loc, $this->data_rptArariekiBase["URIAGEKIN_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . $loc, $this->data_rptArariekiBase["ARARI_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["RYUHO"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["RYUHO"] . $loc, $this->data_rptArariekiBase["RYUHO_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["TOUARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["TOUARA"] . $loc, $this->data_rptArariekiBase["TOUARA_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["TKIARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["TKIARA"] . $loc, $this->data_rptArariekiBase["TKIARA_total"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["ZKIARA"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["ZKIARA"] . $loc, $this->data_rptArariekiBase["ZKIARA_total"]);
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["(台当り)"] . ($loc + 1), "(台当り)");
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["URI_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["URI_DAI"] . ($loc + 1), $this->data_rptArariekiBase["UriDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["ARARI_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["ARARI_DAI"] . ($loc + 1), $this->data_rptArariekiBase["ArariDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["RYUHO_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["RYUHO_DAI"] . ($loc + 1), $this->data_rptArariekiBase["RyuhoDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["TOUARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["TOUARA_DAI"] . ($loc + 1), $this->data_rptArariekiBase["TouDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["TKIARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["TKIARA_DAI"] . ($loc + 1), $this->data_rptArariekiBase["TkiDai"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC2["ZKIARA_DAI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC2["ZKIARA_DAI"] . ($loc + 1), $this->data_rptArariekiBase["ZkiDai"]);

                    $loc = $loc + 2;
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . $loc, $this->data_rptArariekiBase[0]["HONTAIGAKU"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . $loc)->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . $loc, $this->data_rptArariekiBase[0]["SYARYOARARI"]);
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["BASEH_CD"] . ($loc + 1), "総　勘　金　額");
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["URIAGEKIN"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["URIAGEKIN"] . ($loc + 1), $this->data_rptArariekiBase["SoukanUri"]);
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . ($loc + 1))->getNumberFormat()->setFormatCode("#,##0");
                    $PHPExcel->getActiveSheet()->getStyle($ABC1["ARARI"] . ($loc + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $PHPExcel->getActiveSheet()->setCellValue($ABC1["ARARI"] . ($loc + 1), $this->data_rptArariekiBase["SoukanArari"]);
                }
                $len1 = count((array) $this->data_rptArariekiChkList);
                $len2 = count((array) $this->data_rptArariekiHyo);
                $len3 = count((array) $this->data_rptArariekiBase);
                if ($len1 == 0) {
                    if ($len2 == 0) {
                        if ($len3 == 0) {

                        } else {
                            $PHPExcel->setActiveSheetIndex(0);
                            $PHPExcel->removeSheetByIndex(0);
                            $PHPExcel->setActiveSheetIndex(0);
                            $PHPExcel->removeSheetByIndex(0);
                        }
                    } else {
                        if ($len3 == 0) {
                            $PHPExcel->setActiveSheetIndex(0);
                            $PHPExcel->removeSheetByIndex(0);
                            $PHPExcel->setActiveSheetIndex(1);
                            $PHPExcel->removeSheetByIndex(1);
                        } else {
                            $PHPExcel->setActiveSheetIndex(0);
                            $PHPExcel->removeSheetByIndex(0);
                        }
                    }
                } else {
                    if ($len2 == 0) {
                        if ($len3 == 0) {
                            $PHPExcel->setActiveSheetIndex(1);
                            $PHPExcel->removeSheetByIndex(1);
                            $PHPExcel->setActiveSheetIndex(1);
                            $PHPExcel->removeSheetByIndex(1);
                        } else {
                            $PHPExcel->setActiveSheetIndex(1);
                            $PHPExcel->removeSheetByIndex(1);

                        }
                    } else {
                        if ($len3 == 0) {
                            $PHPExcel->setActiveSheetIndex(2);
                            $PHPExcel->removeSheetByIndex(2);

                        } else {

                        }
                    }
                }

                $objWriter = new XlsxWriter($PHPExcel);
                $objWriter->save($file);
                $result1 = "files/KRSS/" . "新車車種別粗利益チェックリスト＋表_" . $this->USERID . ".xlsx";
                $result['result'] = TRUE;
                $result['data'] = $result1;

            }
        } catch (\Exception $e) {
            $result["result"] = FALSE;
            // $result["data"] = $e -> getMessage();

            $result['data'] = array(
                "TFException" => TRUE,
                "messageCode" => "E9999",
                "messageContent" => $e->getMessage()
            );

            // throw new \Exception("error", 1);

        }

        return $result;
    }

    public function fncRoundDou($dblDou, $intRoundKeta, $strRoundKbn)
    {
        $dCom1 = 0.0;
        $dCom2 = 0.0;
        switch ($strRoundKbn) {
            case "0":
                //切り捨て
                $fncRoundDou = intval($dblDou * pow(10, $intRoundKeta)) / pow(10, $intRoundKeta);
                break;
            case "1":
                //四捨五入
                $fncRoundDou = intval($dblDou * pow(10, $intRoundKeta) + (($dblDou < 0) ? 0.5 - 1 : 0.5)) / pow(10, $intRoundKeta);
                break;
            case "2":
                //切り上げ
                $dCom1 = $dblDou * pow(10, $intRoundKeta);
                $dCom2 = ($dblDou < 0) ? -0.999 : 0.999;
                $fncRoundDou = intval($dCom1 + $dCom2) / pow(10, $intRoundKeta);
                break;
            default:
                $fncRoundDou = $dblDou;
                break;
        }
        return sprintf("%.1f", $fncRoundDou);
    }

}
