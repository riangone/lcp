<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyasyuArariChkList;

class FrmSyasyuArariChkListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $result_fncArariSyukei;
    public $result_fncArariSyukei_sel;
    public $filePathName;
    public $Do_conn;
    public $blnTranFlg;
    public $FrmSyasyuArariChkList;
    public $intObjRpt;
    public $result_objDs;
    public $result_objDs2;
    public $result_objDs3;
    private $executeFinish_result;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmSyasyuArariChkList_layout');
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
            $FrmSyasyuArariChkList = new FrmSyasyuArariChkList();
            //モデルクラスのselect処理を呼出し
            $this->result = $FrmSyasyuArariChkList->FrmSyasyuArariChkList_formLoad_select();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }

        $this->fncReturn($this->result);
    }

    /*
           '**********************************************************************
           '処 理 名：実行
           '関 数 名：fncCmdAction
           '引    数：無し
           '戻 り 値：無し
           '処理説明：印刷する
           '**********************************************************************
           */
    public function fncCmdAction()
    {
        $this->executeFinish_result = FALSE;
        $this->intObjRpt = 0;
        if ($_POST['data']['radChkList'] == "true") {
            $this->fnc_radChkList_checked();
        } elseif ($_POST['data']['radMeisai'] == "true") {
            $this->fnc_radMeisai_checked();
        } elseif ($_POST['data']['radDouble'] == "true") {
            $this->fnc_radDouble_checked();
        }
        if ($this->executeFinish_result) {
            $this->fncSelectCaseIntObjRpt();
        }

    }

    public function fnc_radChkList_checked()
    {
        $jumpFlg = false;
        try {

            //モデルの仕様するクラスを定義
            $FrmSyasyuArariChkList = new FrmSyasyuArariChkList();
            //モデルクラスのselect処理を呼出し
            $cboYMEnd = $_POST['data']['cboYMEnd'];
            $this->result_objDs = $FrmSyasyuArariChkList->fncPrintSelect($cboYMEnd);
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
            $this->FrmSyasyuArariChkList = new FrmSyasyuArariChkList();
            $this->Do_conn = $this->FrmSyasyuArariChkList->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }

            //トランザクション開始 line371
            $this->FrmSyasyuArariChkList->Do_transaction();
            //新車車種別粗利益集計処理
            if (!$this->fncArariSyukei($cboYMEnd, $cboYMStart)) {
                $jumpFlg = true;
                throw new \Exception($this->result_fncArariSyukei['data']);
            }
            //コミット
            $this->FrmSyasyuArariChkList->Do_commit();
            $this->blnTranFlg = FALSE;
            //SQL発行
            $this->result_objDs2 = $this->FrmSyasyuArariChkList->fncArariekiListSel($cboYMEnd, $cboYMStart);
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

    public function fnc_radDouble_checked()
    {
        $jumpFlg = FALSE;
        try {

            $cboYMEnd = $_POST['data']['cboYMEnd'];
            $cboYMStart = $_POST['data']['cboYMStart'];

            //モデルの仕様するクラスを定義
            $this->FrmSyasyuArariChkList = new FrmSyasyuArariChkList();
            $this->Do_conn = $this->FrmSyasyuArariChkList->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }

            //トランザクション開始
            $this->FrmSyasyuArariChkList->Do_transaction();

            //新車車種別粗利益集計処理
            if (!$this->fncArariSyukei($cboYMEnd, $cboYMStart)) {
                $jumpFlg = true;
                throw new \Exception($this->result_fncArariSyukei['data']);
            }

            //コミット
            $this->FrmSyasyuArariChkList->Do_commit();
            $this->blnTranFlg = FALSE;

            //SQL発行
            $cboYMEnd = $_POST['data']['cboYMEnd'];
            $this->result_objDs = $this->FrmSyasyuArariChkList->fncPrintSelect($cboYMEnd);
            if (!$this->result_objDs['result']) {
                $jumpFlg = true;
                throw new \Exception($this->result_objDs['data'], 1);
            }
            if (count((array) $this->result_objDs['data']) != 0) {
                $this->intObjRpt = 1;
            }

            $this->result_objDs2 = $this->FrmSyasyuArariChkList->fncArariekiListSel($cboYMEnd, $cboYMStart);
            if (!$this->result_objDs2['result']) {
                $jumpFlg = true;
                throw new \Exception($this->result_objDs2['data'], 1);
            }
            if (count((array) $this->result_objDs2['data']) != 0) {
                $this->intObjRpt += 2;
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
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncWKDel();
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result['data'], 1);
            }

            //車種別粗利益ファイルの当月分を削除する  a2

            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncArariDel($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }
            //当月分限界利益データをﾜｰｸﾃｰﾌﾞﾙに集計する  a3
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncGenriInsert($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            } elseif ($this->result_fncArariSyukei['number_of_rows'] == 0) {
                return TRUE;
            }
            //調整入力の値をINSERTする  a4
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncChoseiInsert($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }
            //車種別粗利益ファイルを読み込み、当年と前年をINSERTする  a5
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncRuikeiInsert($cboYMEnd, $cboYMStart);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }
            //新車売上の差額を算出する(会計データと売上ﾃﾞｰﾀの差額) a6
            $this->result_fncArariSyukei_sel = $this->FrmSyasyuArariChkList->fncUriAnbunSel($cboYMEnd, $cboYMStart);
            if (!$this->result_fncArariSyukei_sel['result']) {
                throw new \Exception($this->result_fncArariSyukei_sel['data'], 1);
            }
            //差額が存在していたら  a7
            if (count($this->result_fncArariSyukei_sel['data']) > 0) {
                //データを読み込む
                //差額が0以外
                $T_SAGAKU = $this->ClsComFnc->FncNz($this->result_fncArariSyukei_sel['data'][0]['SAGAKU']);
                $T_ARARI = $this->ClsComFnc->FncNz($this->result_fncArariSyukei_sel['data'][0]['ARARI']);
                if ($T_SAGAKU != 0) {
                    //新車売上合計が0以外
                    if ($T_ARARI != 0) {
                        //車種ごとに差額を按分する  a8
                        $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncUriAnbunSyasyuIns($cboYMEnd, $T_SAGAKU, $T_ARARI);
                        if (!$this->result_fncArariSyukei['result']) {
                            throw new \Exception($this->result_fncArariSyukei['data'], 1);
                        }
                    }
                    //車種ごとに割り振った差額の合計と新車売上との差額をその他に集計する  a9
                    $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncUriAnbunExtIns($cboYMEnd, $T_SAGAKU);
                    if (!$this->result_fncArariSyukei['result']) {
                        throw new \Exception($this->result_fncArariSyukei['data'], 1);
                    }

                }
            }
            //新車車両原価の差額を算出する(会計データと売上ﾃﾞｰﾀの差額) line 604  a10
            $this->result_fncArariSyukei_sel = $this->FrmSyasyuArariChkList->fncSyaryoPcsAnbunSel($cboYMEnd);
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
                        $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncSyaryoPcsAnbunSyasyuIns($cboYMEnd, $T_SAGAKU, $T_GENKA);
                        if (!$this->result_fncArariSyukei['result']) {
                            throw new \Exception($this->result_fncArariSyukei['data'], 1);
                        }
                    }
                    //車種ごとに割り振った差額の合計と車両原価差額との差額をその他に集計する  a13
                    $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncSyaryoPcsAnbunExtIns($cboYMEnd, $T_SAGAKU);
                    if (!$this->result_fncArariSyukei['result']) {
                        throw new \Exception($this->result_fncArariSyukei['data'], 1);
                    }
                }
            }

            //架付原価の差額を算出する(会計データと売上ﾃﾞｰﾀの差額) a14
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncKasouPcsAnbunSel($cboYMEnd);
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
                        $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncKasouPcsAnbunSyasyuIns($cboYMEnd, $T_SAGAKU, $T_GENKA);
                        if (!$this->result_fncArariSyukei['result']) {
                            throw new \Exception($this->result_fncArariSyukei['data'], 1);
                        }
                    }
                    //車種ごとに割り振った差額の合計と架付原価差額との差額をその他に集計する  a16
                    $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fnckasouPcsAnbunExtIns($cboYMEnd, $T_SAGAKU);
                    if (!$this->result_fncArariSyukei['result']) {
                        throw new \Exception($this->result_fncArariSyukei['data'], 1);
                    }
                }
            }
            //留保金(運賃)を求める  a17
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncUnchinIns($cboYMEnd);
            if (!$this->result_fncArariSyukei['result']) {
                throw new \Exception($this->result_fncArariSyukei['data'], 1);
            }

            //車種別粗利益データに今回求めた値をINSERTする  a18
            $this->result_fncArariSyukei = $this->FrmSyasyuArariChkList->fncArariIns($cboYMEnd);
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

    public function fncSelectCaseIntObjRpt()
    {
        $tmpPdfName1 = "";
        $tmpPdfName2 = "";
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
        }
        //'プレビュー表示
        $path_rpxTopdf = dirname(__DIR__);
        include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
        if ($tmpPdfName2 == "") {
            include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName1 . '.inc';
            switch ($tmpPdfName1) {
                case 'rptArariekiChkList':
                    $data = $this->result_objDs['data'][0];
                    $tmp_data = array();
                    $rpx_file_names = array();
                    $rpx_file_names[$tmpPdfName1] = $data_fields_rptArariekiChkList;
                    array_push($tmp_data, $data);
                    $tmp = array();
                    $tmp["data"] = $tmp_data;
                    $tmp["mode"] = "0";
                    $datas[$tmpPdfName1] = $tmp;
                    $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                    $pdfPath = $obj->to_pdf2();
                    $this->result['result'] = TRUE;
                    $this->result['data'] = $pdfPath;
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
                        $data[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total) / 1000, 0, 1);
                        $data[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total) / 1000, 0, 1);
                        $data[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                        $data[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                        $data[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                        $data[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                        $data[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                        $data[$key]['TKI_DAI_total'] = $TKI_DAI_total;
                    }

                    //---
                    $tmp_data = array();
                    $rpx_file_names = array();
                    $rpx_file_names[$tmpPdfName1] = $data_fields_rptArariekiHyo;
                    array_push($tmp_data, $data);
                    $tmp = array();
                    $tmp["data"] = $tmp_data;
                    $tmp["mode"] = "3";
                    $datas[$tmpPdfName1] = $tmp;
                    $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                    $pdfPath = $obj->to_pdf2();
                    $this->result['result'] = TRUE;
                    $this->result['data'] = $pdfPath;
                    break;
                default:
                    break;
            }

            $this->fncReturn($this->result);
            return;
        } else {
            include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName1 . '.inc';
            include_once $path_rpxTopdf . '/Component/tcpdf/' . $tmpPdfName2 . '.inc';
            $rpx_file_names = array();
            $data1 = $this->result_objDs['data'][0];
            $data2 = $this->result_objDs2['data'];
            $rpx_file_names[$tmpPdfName1] = $data_fields_rptArariekiChkList;
            $rpx_file_names[$tmpPdfName2] = $data_fields_rptArariekiHyo;
            $datas = array();
            //name1
            $tmp_data = array();
            array_push($tmp_data, $data1);
            $tmp1 = array();
            $tmp1["data"] = $tmp_data;
            $tmp1["mode"] = "0";
            $datas[$tmpPdfName1] = $tmp1;
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
                $data2[$key]['URIAGEKIN_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($URIAGEKIN_total) / 1000, 0, 1);
                $data2[$key]['ARARI_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ARARI_total) / 1000, 0, 1);
                $data2[$key]['RYUHO_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($RYUHO_total) / 1000, 0, 1);
                $data2[$key]['TOUARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TOUARA_total) / 1000, 0, 1);
                $data2[$key]['TKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($TKIARA_total) / 1000, 0, 1);
                $data2[$key]['ZKIARA_total'] = $this->fncRoundDou($this->ClsComFnc->FncNz($ZKIARA_total) / 1000, 0, 1);
                $data2[$key]['ZKI_DAI_total'] = $ZKI_DAI_total;
                $data2[$key]['TKI_DAI_total'] = $TKI_DAI_total;
            }

            //---
            $tmp_data = array();
            array_push($tmp_data, $data2);
            $tmp2 = array();
            $tmp2["data"] = $tmp_data;
            $tmp2["mode"] = "3";
            $datas[$tmpPdfName2] = $tmp2;
            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf2();
            $this->result['result'] = TRUE;
            $this->result['data'] = $pdfPath;

            $this->fncReturn($this->result);
            return;
        }
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