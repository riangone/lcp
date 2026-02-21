<?php
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) FCS
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151102           #2245						   BUG                              li  　　
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmKaikeiMake;
use App\Model\R4\Component\ClsKeiriDataMake;

class FrmKaikeiMakeController extends AppController
{
    public $lngCnt = 0;
    public $intState = 0;
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $filePathName;
    public $Do_conn;
    public $blnTranFlg;
    //---20151102 li DEL S.
    // public $FrmKaikeiMake;
    //---20151102 li DEL E.
    public $pprlogpath;
    public $sprErrList_data = array();
    public $number_of_rows = 0;
    public $pdfPath = "";
    public $showFrame3 = false;
    //---20151102 li DEL S.
    public $UPDUSER = "";
    public $UPDCLTNM = "";
    public $ClsKeiriDataMake;
    //---20151102 li DEL E.
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsCreateCsv');
        $this->loadComponent('ClsFncLog');
        $this->loadComponent('ClsLogControl');
    }
    public function index()
    {
        $this->render('index', 'FrmKaikeiMake_layout');
    }

    /*
           '**********************************************************************
           '処理概要：フォームロード
           '**********************************************************************
           */
    //---20151102 li UPD S.
    // public function formLoad()
    public function FrmKaikeiMakelayout()
    //---20151102 li UPD E.
    {
        try {
            //モデルの仕様するクラスを定義
            $this->FrmKaikeiMake = new FrmKaikeiMake();
            //モデルクラスのselect処理を呼出し
            /*$this -> result = $this->FrmKaikeiMake -> FrmKaikeiMake_Load_select();
                         if (!$this -> result['result'])
                         {
                         throw new Exception($this -> result['data'], 1);
                         }
                         */
        } catch (\Exception $ex) {
            //$this -> result['result'] = FALSE;
            //$this -> result['data'] = $ex -> getMessage();
        }

        $this->fncReturn($this->result);
    }

    public function formDeal()
    {
        $this->pdfPath = "";
        $strDepend1 = $_POST['data']['strDepend1'];
        $strDepend2 = $_POST['data']['strDepend2'];
        $strDepend1 = str_replace("/", "", $strDepend1);
        $strDepend2 = str_replace("/", "", $strDepend2);
        $strErrMsg = "";
        $strRtn = "";
        $objLog = $this->ClsCreateCsv->GS_OUTPUTLOG;
        //--init result--
        $this->result['result'] = "";
        $this->result['data'] = "";
        $this->result['mesgID'] = "";
        $this->result['message'] = "";
        $this->result['lblmsg'] = "";
        $this->result['lblCnt'] = 0;
        $this->result['frame3_visible'] = FALSE;
        //---20151102 li INS S.
        $this->ClsKeiriDataMake = new ClsKeiriDataMake();
        //---20151102 li INS E.
        try {
            //LOG出力ﾊﾟｽを取得する
            if ($this->fncGetLogPath()) {
                $strRtn = $this->ClsCreateCsv->fncOutChk(dirname($this->ClsCreateCsv->strLogPath), $strErrMsg);

                if ($strRtn != "") {
                    $this->result['TF'] = FALSE;
                    $this->result['msgID'] = "W9999";
                    $this->result['msgContent'] = "ログ出力先パスが存在しません！";
                    // $this -> set('result', $this -> result);
                    // $this -> render('frmkaikeimakeload');
                    $this->fncReturn($this->result);
                    return;
                }
                $this->ClsCreateCsv->strLogName = $this->ClsCreateCsv->strLogPath;
                $objLog['strID'] = "会計ﾃﾞｰﾀ作成";
                $objLog['strStartDate'] = date("Y-m-d H:i:s");
                //開始LOG出力
                $tf = $this->ClsFncLog->fncStartLog($this->ClsCreateCsv->strLogName, $objLog);
                if ($tf == FALSE) {
                    throw new \Exception("error");
                }
                //---20151102 li UPD S.
                // $this -> FrmKaikeiMake = new FrmKaikeiMake();
                // $this -> result = $this -> FrmKaikeiMake -> fncKaikeiWKDelete();
                $this->result = $this->ClsKeiriDataMake->fncKaikeiWKDelete();
                //---20151102 li UPD E.
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
                //---20151102 li UPD S.
                // $this -> result = $this -> FrmKaikeiMake -> fncKaikeiWKInsert($strDepend1, $strDepend2);
                $this->result = $this->ClsKeiriDataMake->fncKaikeiWKInsert($strDepend1, $strDepend2);
                //---20151102 li UPD E.
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }


                if (!$this->fncErrChk($objLog)) {
                    if ($this->showFrame3) {
                        //$this -> result['frame3_visible'] = TRUE;
                    } else {
                        //$this -> result['frame3_visible'] = FALSE;
                    }
                    $this->result['result'] = FALSE;
                    $this->result['data'] = "科目マスタ未登録データが存在します。<br/>ログファイルを確認してください。";
                    $this->result['mesgID'] = "W0024";
                    $this->result['message'] = "";
                    $this->result['lblmsg'] = "処理を中断しました。";
                    $this->result['jqgrid_data'] = $this->sprErrList_data;
                    // $this -> set('result', $this -> result);
                    // $this -> render('frmkaikeimakeload');
                    $this->fncReturn($this->result);
                    return;
                }
                //$this -> FrmKaikeiMake = new FrmKaikeiMake();
                //---20151102 li UPD S.
                // $this -> Do_conn = $this -> FrmKaikeiMake -> Do_conn();
                $this->Do_conn = $this->ClsKeiriDataMake->Do_conn();
                //---20151102 li UPD E.
                //トランザクション開始
                //---20151102 li UPD S.
                // $this -> FrmKaikeiMake -> Do_transaction();
                $this->ClsKeiriDataMake->Do_transaction();
                //---20151102 li UPD E.
                $intRtn = 0;
                //---20151102 li UPD S.
                // $this -> result = $this -> FrmKaikeiMake -> fncKaikeiDelete($strDepend1, $strDepend2, "SW");
                $this->result = $this->ClsKeiriDataMake->fncKaikeiDelete($strDepend1, $strDepend2, "SW");
                //---20151102 li UPD E.
                if ($this->result['result'] == FALSE) {
                    //---20151102 li UPD S.
                    // $this -> FrmKaikeiMake -> Do_rollback();
                    $this->ClsKeiriDataMake->Do_rollback();
                    //---20151102 li UPD E.
                    throw new \Exception($this->result['data']);
                }
                //---20151102 li UPD S.
                // $this -> result = $this -> FrmKaikeiMake -> fncKaikeiInsert($strDepend1, $strDepend2);
                $this->result = $this->ClsKeiriDataMake->fncKaikeiInsert($strDepend1, $strDepend2, '', '', "FrmKaikeiMake");
                //---20151102 li UPD E.

                if ($this->result['result'] == FALSE) {
                    //ﾛｰﾙﾊﾞｯｸ
                    //---20151102 li UPD S.
                    // $this -> FrmKaikeiMake -> Do_rollback();
                    $this->ClsKeiriDataMake->Do_rollback();
                    //---20151102 li UPD E.
                    // $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $objlog);
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName);
                    //終了LOG出力
                    $objLog['strEndDate'] = date("Y-m-d H:i:s");
                    $objLog['strState'] = "NG";
                    $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $objLog);
                    $this->result['lblmsg'] = "処理に失敗しました。";
                    throw new \Exception($this->result['data']);
                } else {
                    $this->number_of_rows = $this->result['number_of_rows'];
                    if ($this->result['number_of_rows'] == 0) {
                        $this->result['result'] = FALSE;
                        $this->result['mesgID'] = "I0001";
                        //---20151102 li UPD S.
                        // $this -> FrmKaikeiMake -> Do_rollback();
                        $this->ClsKeiriDataMake->Do_rollback();
                        //---20151102 li UPD E.
                        throw new \Exception("rowsnull");
                    }
                }

                $this->result['lblCnt'] = $intRtn;
                //終了LOG出力
                $objLog['strEndDate'] = date("Y-m-d H:i:s");
                $objLog['strState'] = "OK";
                $this->ClsFncLog->fncEndLog($this->ClsCreateCsv->strLogName, $objLog);
                //---20151102 li UPD S.
                // $this -> FrmKaikeiMake -> Do_commit();
                $this->ClsKeiriDataMake->Do_commit();
                //---20151102 li UPD E.
                $this->intState = 9;

                if ($this->fncPrint($this->lngCnt, $strDepend1, $strDepend2) == FALSE) {
                    throw new \Exception("error");
                }

                $this->intState = 1;
                //'メッセージ表示(正常終了しました)
                $this->result['result'] = TRUE;
                $this->result['data'] = "";
                $this->result['mesgID'] = "I0011";
                $this->result['message'] = "";
                $this->result['lblmsg'] = "";
                $this->result['frame3_visible'] = FALSE;
                $this->result['jqgrid_data'] = "";
                $this->result['number_of_rows'] = $this->number_of_rows;
                $this->result['pdfpath'] = $this->pdfPath;
            } else {
                throw new \Exception("error");
            }
        } catch (\Exception $ex) {
            if ($ex->getMessage() == 'rowsnull') {
                $this->result['data'] = "rowsnull";
            } else {
                $this->result['data'] = $ex->getMessage();
                $this->result['mesgID'] = "";
            }
            $this->result['result'] = FALSE;
            $this->result['message'] = "";
            $this->result['lblmsg'] = "";
            //$this -> result['frame3_visible'] = FALSE;
            //$this -> result['jqgrid_data'] = "";
            $this->result['number_of_rows'] = $this->number_of_rows;
        }
        //finally;
        if ($this->intState != 0) {
            $this->ClsLogControl->fncLogEntry("frmKaikeiMake", $this->intState, $this->lngCnt, $strDepend1, $strDepend2);
        }

        //$this -> FrmKaikeiMake -> Do_close();

        $this->fncReturn($this->result);
        // return;
    }

    /*
           '**********************************************************************
           'LOG出力ﾊﾟｽを取得する
           '**********************************************************************
           */
    public function fncGetLogPath()
    {
        //$this -> ClsCreateCsv -> strLogPath = $this -> ClsComFnc -> FncGetPath("pprlogpath");
        try {
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $this->ClsCreateCsv->strLogPath = $this->ClsComFnc->FncGetPath("pprlogpath");
            if ($this->ClsCreateCsv->strLogPath == "") {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/log/LOG.Log";
            } else {
                $this->ClsCreateCsv->strLogPath = $strPath . "/mnt/temp/" . $this->ClsCreateCsv->strLogPath;
            }
            if (!file_exists(dirname($this->ClsCreateCsv->strLogPath))) {
                $Logpath = dirname($this->ClsCreateCsv->strLogPath);
                mkdir($Logpath);
            }
        } catch (\Exception $ex) {
            return false;
        }
        return true;
    }

    /*
           '**********************************************************************
           '処 理 名：ERRチェック
           '関 数 名：fncErrChk
           '引    数：objlog
           '戻 り 値：無し
           '処理説明：ERRチェック
           '**********************************************************************
           */
    public function fncErrChk($objlog)
    {
        $strDepend1 = $_POST['data']['strDepend1'];
        $strDepend2 = $_POST['data']['strDepend2'];
        try {
            //---20151102 li UPD S.
            // $this -> FrmKaikeiMake = new FrmKaikeiMake();
            // $this -> result = $this -> FrmKaikeiMake -> fncKaikeiCHKSQL($strDepend1, $strDepend2);
            $this->ClsKeiriDataMake = new ClsKeiriDataMake();
            $this->result = $this->ClsKeiriDataMake->fncKaikeiCHK($strDepend1, $strDepend2);
            //---20151102 li UPD E.
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            if (count((array) $this->result['data']) <= 0) {
                return TRUE;
            }
            foreach ((array) $this->result['data'] as $value) {
                if ($this->ClsComFnc->FncNv($value['KL_KAMOK_CD']) == "") {
                    $this->showFrame3 = true;
                    $objlog['strErrMsg'] = "科目コードが未登録です。";
                    $objlog['strErrMsg'] .= " 仕訳No." . $this->ClsComFnc->FncNv($value['SIWAK_NO']);
                    $objlog['strErrMsg'] .= " 借方科目ｺｰﾄﾞ" . $this->ClsComFnc->FncNv($value['L_KAMOK_CD']);
                    $objlog['strErrMsg'] .= " 借方項目ｺｰﾄﾞ" . $this->ClsComFnc->FncNv($value['L_KOUMK_CD']);
                    //ログファイル作成
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $objlog);
                    $this->subErrSpreadShow($this->ClsComFnc->FncNv($value['L_KAMOK_CD']) . "-" . $this->ClsComFnc->FncNv($value['L_KOUMK_CD']));
                }

                if ($this->ClsComFnc->FncNv($value['KR_KAMOK_CD']) == "") {
                    $objlog['strErrMsg'] = "科目コードが未登録です。";
                    $objlog['strErrMsg'] .= " 仕訳No." . $this->ClsComFnc->FncNv($value['SIWAK_NO']);
                    $objlog['strErrMsg'] .= " 借方科目ｺｰﾄﾞ" . $this->ClsComFnc->FncNv($value['R_KAMOK_CD']);
                    $objlog['strErrMsg'] .= " 借方項目ｺｰﾄﾞ" . $this->ClsComFnc->FncNv($value['R_KOUMK_CD']);
                    //ログファイル作成
                    $this->ClsFncLog->fncErrLog($this->ClsCreateCsv->strLogName, $objlog);
                    $this->subErrSpreadShow($this->ClsComFnc->FncNv($value['R_KAMOK_CD']) . "-" . $this->ClsComFnc->FncNv($value['R_KOUMK_CD']));
                }
            }
            return FALSE;
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
            $this->result['mesgID'] = "";
            $this->result['message'] = "";
            $this->result['lblmsg'] = "";
            $this->result['lblCnt'] = 0;
            $this->result['frame3_visible'] = FALSE;

            $this->fncReturn($this->result);
        }
    }

    /*
           '**********************************************************************
           '処 理 名：エラーデータグリッドの表示
           '関 数 名：subErrSpreadShow
           '引    数：strKamokuCD
           '戻 り 値：無し
           '処理説明：データグリッドを再表示する
           '**********************************************************************
           */
    public function subErrSpreadShow($strKamokuCD)
    {
        $intRow = 0;
        $blnChk = False;
        //スプレッドにデータリーダーの内容をセット
        while ($intRow < count($this->sprErrList_data)) {
            if ($strKamokuCD == $this->sprErrList_data[$intRow]) {
                $blnChk = TRUE;
                break;
            }
            $intRow++;
        }
        //while($intRow >= count($this -> sprErrList_data));
        if ($blnChk == FALSE) {
            array_push($this->sprErrList_data, $strKamokuCD);
        }
    }

    /*
           '********************************************************************
           '処理概要：アンマッチリストを出力する
           '引　　数：なし
           '戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）2007/04/02　潘
           '********************************************************************
           */
    public function fncPrint($lngCnt, $strDepend1, $strDepend2)
    {

        $bReturn = FALSE;
        $sysDate = date("Y/m/d");
        $path_rpxTopdf = dirname(__DIR__);
        include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
        include_once $path_rpxTopdf . '/Component/tcpdf/rptKaikeiUnmachi.inc';
        try {
            //---20151102 li UPD S.
            // $this -> FrmKaikeiMake = new FrmKaikeiMake();
            // $objDs = $this -> FrmKaikeiMake -> fncGetAnmattiData($strDepend1, $strDepend2);
            $this->ClsKeiriDataMake = new ClsKeiriDataMake();
            $objDs = $this->ClsKeiriDataMake->fncGetAnmattiData($strDepend1, $strDepend2);
            //---20151102 li UPD E.
            $this->lngCnt = count((array) $objDs['data']);
            if ($this->lngCnt == 0) {
                $bReturn = TRUE;
                return $bReturn;
            }
            $tmp_data = array();
            $rpx_file_names = array();
            $rpx_file_names["rptKaikeiUnmachi"] = $data_fields_rptKaikeiUnmachi;
            $strDepend1 = substr($strDepend1, 0, 4) . "年" . substr($strDepend1, 4, 2) . "月" . substr($strDepend1, 6, 2) . "日 ";
            $strDepend2 = substr($strDepend2, 0, 4) . "年" . substr($strDepend2, 4, 2) . "月" . substr($strDepend2, 6, 2) . "日 ";
            foreach ((array) $objDs['data'] as $key => $value) {
                $objDs['data'][$key]['DATESTART'] = $strDepend1;
                $objDs['data'][$key]['DATEEND'] = $strDepend2;
                $objDs['data'][$key]['SYSDATE'] = $sysDate;
            }
            array_push($tmp_data, $objDs['data']);

            $tmp = array();
            $tmp["data"] = $tmp_data;
            $tmp["mode"] = "3";
            $datas["rptKaikeiUnmachi"] = $tmp;
            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf();
            $this->pdfPath = $pdfPath;
            $bReturn = TRUE;

        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
            $this->result['mesgID'] = "";
            $this->result['message'] = "";
            $this->result['lblmsg'] = "";
            $this->result['lblCnt'] = 0;
            $this->result['frame3_visible'] = FALSE;

            $this->fncReturn($this->result);
        }
        return $bReturn;
    }

}