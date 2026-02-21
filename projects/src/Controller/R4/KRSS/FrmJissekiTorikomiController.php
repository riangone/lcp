<?php
/**
 * 説明：
 *
 *
 * @author li
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                  担当
 * YYYYMMDD           #ID                       XXXXXX                                FCSDL
 * 20160511           #2437                     実績取込機能改修                         Sun
 * 20160518           #2437                     シートレイアウト調整                         HM
 * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmJissekiTorikomi;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * 実績取込画面
 * FrmJissekiTorikomiController
 */
class FrmJissekiTorikomiController extends AppController
{
    public $autoLayout = TRUE;

    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    public $FrmJissekiTorikomi = "";
    public function index()
    {
        $this->render('index', 'FrmJissekiTorikomi_layout');
    }
    //**********************************************************************
    //処 理 名：frmLoad
    //関 数 名：frmLoad
    //引    数：
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function frmLoad()
    {
        $result = array(
            "result" => FALSE,
            "data" => "error"
        );
        $this->FrmJissekiTorikomi = new FrmJissekiTorikomi();
        try {
            $result = $this->FrmJissekiTorikomi->fncHKEIRICTL();
            if ($result["result"] == FALSE) {
                throw new \Exception($result["data"]);
            } elseif (count((array) $result["data"]) <= 0) {
                $result["result"] = FALSE;
                throw new \Exception("コントロールマスタが存在しません！");
            }
        } catch (\Exception $ex) {
            $result["result"] = FALSE;
            $result["data"] = $ex->getMessage();
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：登録
    //関 数 名：cmdAct_Click
    //引    数：
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function cmdActClick()
    {

        $postData = "";
        $result = array();
        try {

            $postData = $_POST['data']['request'];

            if ($postData['MARK'] == '1') {
                $res = $this->fncComment($postData);
            } elseif ($postData['MARK'] == '2') {
                $res = $this->fncService($postData);
            } elseif ($postData['MARK'] == '3') {
                $res = $this->fncHoken($postData);
            }
            //20160511 Sun Add Start
            elseif ($postData['MARK'] == '4') {
                $res = $this->fncTougetueigyo($postData);
            } elseif ($postData['MARK'] == '5') {
                $res = $this->fncTougetusabisu($postData);
            }
            //20160511 Sun Add End
            //20161012 Sun Add Start
            elseif ($postData['MARK'] == '6') {
                $res = $this->fncTougetuchuko($postData);
            }
            //20161012 Sun Add End
            else {
                $res = $this->fncOther($postData);
            }
            if (!$res['result']) {
                $result['MsgID'] = $res['MsgID'];
                $result['data'] = $res['data'];
                $result['result'] = FALSE;
            } else {
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：期を年に変更する
    //関 数 名：kiToDate
    //引    数：$ki
    //戻 り 値：$cboY
    //処理説明：実績取込
    //**********************************************************************
    function kiToDate($ki = null)
    {
        $cboY = 1917 + $ki;

        return $cboY;
    }

    //**********************************************************************
    //処 理 名：登録ボタン押下 コメント
    //関 数 名：fncComment
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function fncComment($postData = NULL)
    {
        try {

            $blnErr = FALSE;
            $result = array(
                'result' => 'false',
                'MsgID' => '',
                'data' => ''
            );
            $this->FrmJissekiTorikomi = new FrmJissekiTorikomi();

            $res = "";
            $res = $this->fncFileReadComment($postData);
            if (!$res['result']) {
                $result['MsgID'] = $res['MsgID'];
                throw new \Exception($res['data']);
            }
            $data = $res['data'];

            $res = $this->FrmJissekiTorikomi->Do_conn();

            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmJissekiTorikomi->Do_transaction();

            foreach ((array) $data as $value) {
                $res = $this->FrmJissekiTorikomi->fncTableDelete($value['KI']);
                if (!$res['result']) {
                    $blnErr = TRUE;
                    $result['MsgID'] = 'E9999';
                    throw new \Exception($res['data']);
                }
                break;
            }
            foreach ((array) $data as $value) {
                $KI = $value['KI'];
                $BUSYO = $value['BUSYO'];
                foreach ($value as $key1 => $value1) {
                    if ($key1 != 'KI' && $key1 != 'BUSYO') {

                        $res = $this->FrmJissekiTorikomi->ExcuteFncGetSqlInsert($KI, $key1, $BUSYO, $value1);
                        if (!$res['result']) {

                            $blnErr = TRUE;
                            $result['MsgID'] = 'E9999';
                            throw new \Exception($res['data']);
                        }
                    }
                }
            }
            $this->FrmJissekiTorikomi->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmJissekiTorikomi->Do_rollback();
            $this->FrmJissekiTorikomi->Do_close();
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：登録ボタン押下 サービス実績
    //関 数 名：fncService
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function fncService($postData = NULL)
    {
        try {

            $blnErr = FALSE;
            $result = array(
                'result' => 'false',
                'MsgID' => '',
                'data' => ''
            );

            $this->FrmJissekiTorikomi = new FrmJissekiTorikomi();

            $res = "";
            $res = $this->fncFileReadService($postData);
            if (!$res['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $data = $res['data'];
            $res = $this->FrmJissekiTorikomi->Do_conn();
            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmJissekiTorikomi->Do_transaction();
            if (count((array) $data) > 0) {
                $ym = $data[0][0];
                $res = $this->FrmJissekiTorikomi->fncTableDeleteService($ym);
                if (!$res['result']) {
                    $blnErr = TRUE;
                    $result['MsgID'] = 'E9999';
                    throw new \Exception($res['data']);
                }
                foreach ((array) $data as $value) {

                    $res = $this->FrmJissekiTorikomi->ExcuteFncGetSqlInsertService($value);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
            }
            $this->FrmJissekiTorikomi->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmJissekiTorikomi->Do_rollback();
            $this->FrmJissekiTorikomi->Do_close();
        }

        return $result;
    }

    //**********************************************************************
    //処 理 名：登録ボタン押下 保険実績
    //関 数 名：fncHoken
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function fncHoken($postData = NULL)
    {

        try {

            $blnErr = FALSE;
            $result = array(
                'result' => 'false',
                'MsgID' => '',
                'data' => ''
            );

            $this->FrmJissekiTorikomi = new FrmJissekiTorikomi();

            $res = "";

            $res = $this->fncFileReadHoken($postData);

            if (!$res['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $data = $res['data'];
            $res = $this->FrmJissekiTorikomi->Do_conn();

            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmJissekiTorikomi->Do_transaction();

            if (count((array) $data) > 0) {
                $ym = $data[0][0];
                $res = $this->FrmJissekiTorikomi->fncTableDeleteHoken($ym);

                if (!$res['result']) {
                    $blnErr = TRUE;
                    $result['MsgID'] = 'E9999';
                    throw new \Exception($res['data']);
                }

                foreach ((array) $data as $value) {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncGetSqlInsertHoken($value);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
            }
            $this->FrmJissekiTorikomi->Do_commit();

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;

            $result['data'] = $e->getMessage();

        }
        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmJissekiTorikomi->Do_rollback();
            $this->FrmJissekiTorikomi->Do_close();
        }

        return $result;
    }

    //20160511 Sun Add Start
    //**********************************************************************
    //処 理 名：登録ボタン押下 台数（営業）
    //関 数 名：fncTougetueigyo
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function fncTougetueigyo($postData = NULL)
    {
        try {

            $blnErr = FALSE;
            $result = array(
                'result' => 'false',
                'MsgID' => '',
                'data' => ''
            );
            $this->FrmJissekiTorikomi = new FrmJissekiTorikomi();

            $res = "";
            $res = $this->fncFileReadTougetueigyo($postData);
            if (!$res['result']) {
                $result['MsgID'] = $res['MsgID'];
                throw new \Exception($res['data']);
            }
            $edata = $res['data'];

            $res = $this->FrmJissekiTorikomi->Do_conn();

            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmJissekiTorikomi->Do_transaction();

            foreach ((array) $edata as $value) {
                $ENENGETU = $value['0'];
                $EBUSYO_CD = $value['1'];
                //セルが空白の場合は 0 とみなしてデータは登録してください
                if ($value['3'] == '') {
                    $KOKYAKUSU = '0';
                } else {
                    $KOKYAKUSU = $value['3'];
                }
                ;
                if ($value['4'] == '') {
                    $DAISU = '0';
                } else {
                    $DAISU = $value['4'];
                }
                if ($value['5'] == '') {
                    $PDEMSU = '0';
                } else {
                    $PDEMSU = $value['5'];
                }
                if ($value['6'] == '') {
                    $SITADORISU = '0';
                } else {
                    $SITADORISU = $value['6'];
                }
                //自新直顧客数 LINE_NO = 2 で登録
                $LINE_NO1 = '2';
                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO1);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO1, $KOKYAKUSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO1, $KOKYAKUSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //20160716 Upd Start
//					//顧客代替台数 LINE_NO = 4 で登録
//					$LINE_NO2 = '4';
                //顧客代替台数 LINE_NO = 3 で登録
                $LINE_NO2 = '3';
                //20160716 Upd Start
                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO2);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO2, $DAISU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO2, $DAISU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //20161013 Ins Start
                ////PDEM台数 LINE_NO = 116 で登録
                //$LINE_NO3 = '116';
                //PDEM台数 LINE_NO = 118 で登録
                $LINE_NO3 = '118';


                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO3);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO3, $PDEMSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO3, $PDEMSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }

                ////下取り台数 LINE_NO = 119 で登録
                //$LINE_NO4 = '119';
                //下取り台数 LINE_NO = 121 で登録
                $LINE_NO4 = '121';


                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO4);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO4, $SITADORISU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO4, $SITADORISU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //20161013 Ins End

            }

            $this->FrmJissekiTorikomi->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmJissekiTorikomi->Do_rollback();
            $this->FrmJissekiTorikomi->Do_close();
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：登録ボタン押下 台数（サービス）
    //関 数 名：fncTougetusabisu
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function fncTougetusabisu($postData = NULL)
    {
        try {

            $blnErr = FALSE;
            $result = array(
                'result' => 'false',
                'MsgID' => '',
                'data' => ''
            );
            $this->FrmJissekiTorikomi = new FrmJissekiTorikomi();

            $res = "";
            $res = $this->fncFileReadTougetusabisu($postData);
            if (!$res['result']) {
                $result['MsgID'] = $res['MsgID'];
                throw new \Exception($res['data']);
            }
            $edata = $res['data'];

            $res = $this->FrmJissekiTorikomi->Do_conn();

            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmJissekiTorikomi->Do_transaction();

            foreach ((array) $edata as $value) {
                $ENENGETU = $value['0'];
                $EBUSYO_CD = $value['1'];
                //セルが空白の場合は 0 とみなしてデータは登録してください
                if ($value['3'] == '') {
                    $KOKYAKUSU = '0';
                } else {
                    $KOKYAKUSU = $value['3'];
                }
                ;
                if ($value['4'] == '') {
                    $DAISU = '0';
                } else {
                    $DAISU = $value['4'];
                }
                //初回車検対象台数 LINE_NO = 8 で登録
                $LINE_NO1 = '8';
                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO1);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO1, $KOKYAKUSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO1, $KOKYAKUSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }

                //初回車検入庫 LINE_NO = 9 で登録
                $LINE_NO2 = '9';
                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO2);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO2, $DAISU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO2, $DAISU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
            }
            $this->FrmJissekiTorikomi->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmJissekiTorikomi->Do_rollback();
            $this->FrmJissekiTorikomi->Do_close();
        }
        return $result;
    }

    //20160511 Sun Add End

    //20161012 Sun Add Start
    //**********************************************************************
    //処 理 名：登録ボタン押下 台数（中古）
    //関 数 名：fncTougetuchuko
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function fncTougetuchuko($postData = NULL)
    {
        try {

            $blnErr = FALSE;
            $result = array(
                'result' => 'false',
                'MsgID' => '',
                'data' => ''
            );
            $this->FrmJissekiTorikomi = new FrmJissekiTorikomi();

            $res = "";
            $res = $this->fncFileReadTougetuchuko($postData);
            if (!$res['result']) {
                $result['MsgID'] = $res['MsgID'];
                throw new \Exception($res['data']);
            }
            $edata = $res['data'];

            $res = $this->FrmJissekiTorikomi->Do_conn();

            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmJissekiTorikomi->Do_transaction();

            foreach ((array) $edata as $value) {
                $ENENGETU = $value['0'];
                $EBUSYO_CD = $value['1'];
                //セルが空白の場合は 0 とみなしてデータは登録してください
                if ($value['3'] == '') {
                    $KOKYAKUSU = '0';
                } else {
                    $KOKYAKUSU = $value['3'];
                }
                ;
                if ($value['4'] == '') {
                    $DAISU = '0';
                } else {
                    $DAISU = $value['4'];
                }
                if ($value['5'] == '') {
                    $PDEMSU = '0';
                } else {
                    $PDEMSU = $value['5'];
                }


                if ($value['6'] == '') {
                    $SAWAYAKAPLUSSU = '0';
                } else {
                    $SAWAYAKAPLUSSU = $value['6'];
                }


                //自中直顧客数 LINE_NO = 4 で登録
                $LINE_NO1 = '4';
                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO1);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO1, $KOKYAKUSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO1, $KOKYAKUSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }

                //下取台数 LINE_NO = 121 で登録
                $LINE_NO2 = '121';
                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO2);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO2, $DAISU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO2, $DAISU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }

                //さわやかプラス LINE_NO = 122 で登録
                $LINE_NO3 = '122';
                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO3);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO3, $SAWAYAKAPLUSSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO3, $SAWAYAKAPLUSSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }


                //PDEM台数 LINE_NO = 118 で登録
                $LINE_NO4 = '118';
                $sel = $this->FrmJissekiTorikomi->FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO4);
                if (!$sel['result']) {
                    throw new \Exception($result['data']);
                }
                $sdata = $sel['data'];
                //すでに存在している場合はUPDATE
                if (!empty($sdata) && isset($sdata[0]['LINE_NO']) && $sdata[0]['LINE_NO'] != "") {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO4, $PDEMSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
                //存在していない場合はINSERT
                else {
                    $res = $this->FrmJissekiTorikomi->ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO4, $PDEMSU);
                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }

            }
            $this->FrmJissekiTorikomi->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmJissekiTorikomi->Do_rollback();
            $this->FrmJissekiTorikomi->Do_close();
        }
        return $result;
    }

    //20161012 Sun Add End

    //**********************************************************************
    //処 理 名：登録ボタン押下  その他
    //関 数 名：fncOther
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function fncOther($postData = NULL)
    {
        try {

            $blnErr = FALSE;
            $result = array(
                'result' => 'false',
                'MsgID' => '',
                'data' => ''
            );

            $this->FrmJissekiTorikomi = new FrmJissekiTorikomi();
            $res = "";
            $res = $this->fncFileReadOther($postData);

            if (!$res['result']) {
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $data = $res['data'];
            $res = $this->FrmJissekiTorikomi->Do_conn();
            if (!$res['result']) {
                $blnErr = TRUE;
                $result['MsgID'] = 'E9999';
                throw new \Exception($res['data']);
            }
            $this->FrmJissekiTorikomi->Do_transaction();
            $delType = FALSE;
            if (count((array) $data) > 0) {
                foreach ((array) $data as $value) {

                    //処理のデータを削除する
                    if ($delType == FALSE) {

                        $res = $this->FrmJissekiTorikomi->fncTableDeleteOther($value['NENGETU']);
                        if (!$res['result']) {
                            $blnErr = TRUE;
                            $result['MsgID'] = 'E9999';
                            throw new \Exception($res['data']);
                        }
                        $delType = TRUE;
                    }

                    //処理のデータをInsertする
                    $res = $this->FrmJissekiTorikomi->ExcuteFncGetSqlInsertOther($value);

                    if (!$res['result']) {
                        $blnErr = TRUE;
                        $result['MsgID'] = 'E9999';
                        throw new \Exception($res['data']);
                    }
                }
            }
            $this->FrmJissekiTorikomi->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnErr) {
            $result['result'] = FALSE;
            $this->FrmJissekiTorikomi->Do_rollback();
            $this->FrmJissekiTorikomi->Do_close();
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：コメント ファイル内容の取得する
    //関 数 名：fncFileReadComment
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function fncFileReadComment($postData = NULL)
    {

        $result = array(
            'result' => 'false',
            'MsgID' => '',
            'data' => ''
        );
        try {
            $filename = $this->changeFileName($postData['FILENAME']);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
//            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;

            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            $result = $this->ExcelReadComment($pathUpLoad);
            if (!$result['result']) {
                $result['MsgID'] = 'W9999';
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //**********************************************************************
    //処 理 名：サービス実績 ファイル内容の取得する
    //関 数 名：fncFileReadService
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function fncFileReadService($postData = NULL)
    {

        $result = array(
            'result' => 'false',
            'MsgID' => '',
            'data' => ''
        );
        try {
            $filename = $this->changeFileName($postData['FILENAME']);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;
            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            $result = $this->ExcelReadService($pathUpLoad);

            if (!$result['result']) {
                $result['MsgID'] = 'W9999';
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //**********************************************************************
    //処 理 名：保険実績 ファイル内容の取得する
    //関 数 名：fncFileReadHoken
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //*********************************************************************
    public function fncFileReadHoken($postData = NULL)
    {

        $result = array(
            'result' => 'false',
            'MsgID' => '',
            'data' => ''
        );
        try {
            $filename = $this->changeFileName($postData['FILENAME']);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
//            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;

            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            $result = $this->ExcelReadHoken($pathUpLoad);

            if (!$result['result']) {
                $result['MsgID'] = 'W9999';
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
//            $result['data'] = $e->getMessage();
            $result['data'] =$pathUpLoad;
        }

        return $result;
    }

    //20160511 Sun Add Start
    //**********************************************************************
    //処 理 名：保険実績  台数（営業）内容の取得する
    //関 数 名：fncFileReadTougetueigyo
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //*********************************************************************
    public function fncFileReadTougetueigyo($postData = NULL)
    {

        $result = array(
            'result' => 'false',
            'MsgID' => '',
            'data' => ''
        );
        try {
            $filename = $this->changeFileName($postData['FILENAME']);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;

            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            $result = $this->ExcelReadTougetueigyo($pathUpLoad);

            if (!$result['result']) {
                $result['MsgID'] = 'W9999';
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //**********************************************************************
    //処 理 名：保険実績 台数（サービス）内容の取得する
    //関 数 名：fncFileReadTougetusabisu
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //*********************************************************************
    public function fncFileReadTougetusabisu($postData = NULL)
    {

        $result = array(
            'result' => 'false',
            'MsgID' => '',
            'data' => ''
        );
        try {
            $filename = $this->changeFileName($postData['FILENAME']);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;

            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            $result = $this->ExcelReadTougetusabisu($pathUpLoad);

            if (!$result['result']) {
                $result['MsgID'] = 'W9999';
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //20160511 Sun Add End

    //20161012 Sun Add End

    //**********************************************************************
    //処 理 名：保険実績 台数（中古）内容の取得する
    //関 数 名：fncFileReadTougetusabisu
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //*********************************************************************
    public function fncFileReadTougetuchuko($postData = NULL)
    {

        $result = array(
            'result' => 'false',
            'MsgID' => '',
            'data' => ''
        );
        try {
            $filename = $this->changeFileName($postData['FILENAME']);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;

            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            $result = $this->ExcelReadTougetuchuko($pathUpLoad);

            if (!$result['result']) {
                $result['MsgID'] = 'W9999';
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //20161012 Sun Add End

    //**********************************************************************
    //処 理 名：その他 ファイル内容の取得する
    //関 数 名：fncFileReadOther
    //引    数：$postData
    //戻 り 値：$result
    //処理説明：実績取込
    //*********************************************************************
    public function fncFileReadOther($postData = NULL)
    {

        $result = array(
            'result' => 'false',
            'MsgID' => '',
            'data' => ''
        );
        try {
            $filename = $this->changeFileName($postData['FILENAME']);
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            //$pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;

            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['MsgID'] = 'W9997';
                throw new \Exception("対象ﾌｧｲﾙが存在していません。");
            }

            $result = $this->ExcelReadOther($pathUpLoad);
            if (!$result['result']) {
                $result['MsgID'] = 'W9999';
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //**********************************************************************
    //処 理 名：登録ボタン押下 コメント ファイル内容の取得する
    //関 数 名：ExcelReadComment
    //引    数：$path
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function ExcelReadComment($path)
    {

        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );

            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {

                $objReader = IOFactory::createReader('Xlsx');
            }

            $objReader->setReadDataOnly(true);

            $objPHPExcel = IOFactory::load($path);
            $sheetCount = $objPHPExcel->getSheetCount();

            $arrComment = array();
            //20160518 Upd Sta
//				$arr = array(
//					'O',
//					'S',
//					'W',
//					'AF',
//					'AJ',
//					'AN',
//					'AW',
//					'BA',
//					'BE',
//					'BN',
//					'BR',
//					'BV'
//				);
//				$arr = array('P', 'U', 'Z', 'AK', 'AP', 'AU', 'BF', 'BK', 'BP', 'CA', 'CF','CK');
//20160518 Upd End
            $arr = array('R', 'Y', 'AF', 'AU', 'BB', 'BI', 'BX', 'CE', 'CL', 'DA', 'DH', 'DO');


            for ($i = 0; $i < $sheetCount; $i++) {
                $rowarr = array();
                $worksheet = $objPHPExcel->getSheet($i);
                if ($worksheet->getCell('B1')->getValue() != '経営成果管理表') {
                    throw new \Exception('ファイル列数 と 取込先テーブル列数が 異なります。');
                }
                $rowarr['KI'] = $worksheet->getCell('D3')->getValue();
                $rowarr['BUSYO'] = $worksheet->getCell('D5')->getValue();
                //期;
                $year = $this->kiToDate($rowarr['KI']);
                //年間コメント
                $rowarr['000000'] = $worksheet->getCell('J3')->getValue();
                //月別コメント
                foreach ($arr as $value) {
                    $month = $worksheet->getCell($value . '7')->getValue();
                    $month = str_replace('月', '', $month);
                    $month = $this->make_semiangle($month);
                    $ym = strlen($month) == 2 ? $year . $month : ($year + 1) . '0' . $month;
                    $val = $worksheet->getCell($value . '3')->getValue();
                    $rowarr[$ym] = $val;
                }
                array_push($arrComment, $rowarr);
            }

            $result = array(
                'result' => TRUE,
                'data' => $arrComment
            );

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：登録ボタン押下 サービス実績 ファイル内容の取得する
    //関 数 名：ExcelReadService
    //引    数：$path
    //戻 り 値：$result
    //処理説明：実績取込
    //*******************************************************************
    public function ExcelReadService($path)
    {
        $objReader = null;
        $objPHPExcel = null;


        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );

            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {

                $objReader = IOFactory::createReader('Xlsx');
            }

            $objReader->setReadDataOnly(true);

            $objPHPExcel = $objReader->load($path);

            $worksheet = $objPHPExcel->getSheet(0);

            $highestRow = $worksheet->getHighestRow();

            if (($worksheet->getCell('AF' . 1)->getValue() == '順番') && ($worksheet->getCell('AG' . 1)->getValue() == '')) {
                $rowarr = array();
                $arr = array(
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G',
                    'H',
                    'I',
                    'J',
                    'K',
                    'L',
                    'M',
                    'N',
                    'O',
                    'P',
                    'Q',
                    'R',
                    'S',
                    'T',
                    'U',
                    'V',
                    'W',
                    'X',
                    'Y',
                    'Z',
                    'AA',
                    'AB',
                    'AC',
                    'AD',
                    'AE',
                    'AF'
                );

                for ($row = 3; $row <= $highestRow; $row++) {
                    $col = array();
                    foreach ($arr as $value) {
                        $val = $worksheet->getCell($value . $row)->getValue();
                        array_push($col, $val);
                    }
                    array_push($rowarr, $col);
                }
                $result = array(
                    'result' => TRUE,
                    'data' => $rowarr
                );
            } else {
                throw new \Exception('ファイル列数 と 取込先テーブル列数が 異なります。');
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：登録ボタン押下 保険実績 ファイル内容の取得する
    //関 数 名：ExcelReadHoken
    //引    数：$path
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function ExcelReadHoken($path)
    {

        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );

            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {

                $objReader = IOFactory::createReader('Xlsx');
            }

            $objReader->setReadDataOnly(true);

            $objPHPExcel = IOFactory::load($path);

            $worksheet = $objPHPExcel->getSheet(0);

            $highestRow = $worksheet->getHighestRow();

            //20160511 Sun UPD. Start
            // if (($worksheet -> getCell(M . 1) -> getValue() == '長期率') && ($worksheet -> getCell(N . 1) -> getValue() != ''))
            // {
            // throw new \Exception('ファイル列数 と 取込先テーブル列数が 異なります。');
            // }
            // else
            if (($worksheet->getCell('M' . 1)->getValue() == '長期率') && ($worksheet->getCell('N' . 1)->getValue() == ''))
            //20160511 Sun UPD. End
            {
                $rowarr = array();
                $arr = array(
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G',
                    'H',
                    'I',
                    'J',
                    'K',
                    'L',
                    'M'
                );

                for ($row = 3; $row <= $highestRow; $row++) {
                    $col = array();
                    foreach ($arr as $value) {
                        //							$val = $worksheet -> getCell($value . $row) -> getValue();
                        $val = $worksheet->getCell($value . $row)->getCalculatedValue();
                        array_push($col, $val);
                    }
                    array_push($rowarr, $col);
                }
                $result = array(
                    'result' => TRUE,
                    'data' => $rowarr
                );
            }
            //20160511 Sun ADD. Start
            else {
                throw new \Exception('ファイル列数 と 取込先テーブル列数が 異なります。');
            }
            //20160511 Sun ADD. End

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //20160511 Sun Add Start
    //**********************************************************************
    //処 理 名：登録ボタン押下  台数（営業） ファイル内容の取得する
    //関 数 名：ExcelReadTougetueigyo
    //引    数：$path
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function ExcelReadTougetueigyo($path)
    {

        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );

            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {

                $objReader = IOFactory::createReader('Xlsx');
            }

            $objReader->setReadDataOnly(true);

            $objPHPExcel = IOFactory::load($path);

            $worksheet = $objPHPExcel->getSheet(0);

            $highestRow = $worksheet->getHighestRow();

            if (($worksheet->getCell('G' . 1)->getValue() == '下取り台数') && ($worksheet->getCell('H' . 1)->getValue() == '')) {
                $rowarr = array();
                $arr = array(
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G'
                );

                for ($row = 3; $row <= $highestRow; $row++) {
                    $col = array();
                    if ($worksheet->getCell('A' . $row)->getValue() != '') {
                        foreach ($arr as $value) {
                            $val = $worksheet->getCell($value . $row)->getValue();
                            array_push($col, $val);
                        }
                        array_push($rowarr, $col);
                    }
                }
                $result = array(
                    'result' => TRUE,
                    'data' => $rowarr
                );
            } else {
                throw new \Exception('ファイル列数 と 取込先テーブル列数が 異なります。');
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：登録ボタン押下 台数（サービス） ファイル内容の取得する
    //関 数 名：ExcelReadTougetusabisu
    //引    数：$path
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function ExcelReadTougetusabisu($path)
    {

        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );

            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {

                $objReader = IOFactory::createReader('Xlsx');
            }

            $objReader->setReadDataOnly(true);

            $objPHPExcel = IOFactory::load($path);

            $worksheet = $objPHPExcel->getSheet(0);

            $highestRow = $worksheet->getHighestRow();

            if (($worksheet->getCell('E' . 1)->getValue() == '初回車検入庫') && ($worksheet->getCell('F' . 1)->getValue() == '')) {
                $rowarr = array();
                $arr = array(
                    'A',
                    'B',
                    'C',
                    'D',
                    'E'
                );

                for ($row = 3; $row <= $highestRow; $row++) {
                    $col = array();
                    if ($worksheet->getCell('A' . $row)->getValue() != '') {
                        foreach ($arr as $value) {
                            $val = $worksheet->getCell($value . $row)->getValue();
                            array_push($col, $val);
                        }
                        array_push($rowarr, $col);
                    }
                }
                $result = array(
                    'result' => TRUE,
                    'data' => $rowarr
                );
            } else {
                throw new \Exception('ファイル列数 と 取込先テーブル列数が 異なります。');
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //20161012 Sun Add End

    //**********************************************************************
    //処 理 名：登録ボタン押下 台数（中古） ファイル内容の取得する
    //関 数 名：ExcelReadTougetusabisu
    //引    数：$path
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function ExcelReadTougetuchuko($path)
    {

        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );

            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {

                $objReader = IOFactory::createReader('Xlsx');
            }

            $objReader->setReadDataOnly(true);

            $objPHPExcel = IOFactory::load($path);

            $worksheet = $objPHPExcel->getSheet(0);

            $highestRow = $worksheet->getHighestRow();

            if (($worksheet->getCell('G' . 1)->getValue() == 'さわやかプラス') && ($worksheet->getCell('H' . 1)->getValue() == '')) {
                $rowarr = array();
                $arr = array(
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'H'
                );

                for ($row = 3; $row <= $highestRow; $row++) {
                    $col = array();
                    if ($worksheet->getCell('A' . $row)->getValue() != '') {
                        foreach ($arr as $value) {
                            $val = $worksheet->getCell($value . $row)->getValue();
                            array_push($col, $val);
                        }
                        array_push($rowarr, $col);
                    }
                }
                $result = array(
                    'result' => TRUE,
                    'data' => $rowarr
                );
            } else {
                throw new \Exception('ファイル列数 と 取込先テーブル列数が 異なります。');
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //20161012 Sun Add End


    //**********************************************************************
    //処 理 名：登録ボタン押下 その他 ファイル内容の取得する
    //関 数 名：ExcelReadOther
    //引    数：$path
    //戻 り 値：$result
    //処理説明：実績取込
    //**********************************************************************
    public function ExcelReadOther($path)
    {

        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );

            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {

                $objReader = IOFactory::createReader('Xlsx');
            }

            $objReader->setReadDataOnly(true);

            $objPHPExcel = IOFactory::load($path);
            $sheetCount = $objPHPExcel->getSheetCount();

            $arrOther = array();
            //20160518 Upd Sta
//				$arr = array(
//					'P',
//					'T',
//					'X',
//					'AG',
//					'AK',
//					'AO',
//					'AX',
//					'BB',
//					'BF',
//					'BO',
//					'BS',
//					'BW'
//				);
            $arr = array('Q', 'V', 'AA', 'AL', 'AQ', 'AV', 'BG', 'BL', 'BQ', 'CB', 'CG', 'CL');
            //20160518 Upd End

            for ($i = 0; $i < $sheetCount; $i++) {
                $col = array();
                $worksheet = $objPHPExcel->getSheet($i);
                if ($worksheet->getCell('B1')->getValue() != '経営成果管理表') {
                    throw new \Exception('ファイル列数 と 取込先テーブル列数が 異なります。');
                }
                foreach ($arr as $value) {
                    //年月
                    $col['NENGETU'] = $this->getMon($value, $worksheet);
                    //部署コード
                    $col['BUSYO_CD'] = $worksheet->getCell('D5')->getValue();
                    for ($row = 10; $row <= 145; $row++) {
                        //ラインNo
                        $col['LINE_NO'] = $worksheet->getCell('A' . $row)->getValue();
                        //値
                        $col['TOUGETU'] = $worksheet->getCell($value . $row)->getValue();
                        $tougetu = trim($col['TOUGETU'] ?? '');
                        $tougetu = is_numeric($tougetu) ? floatval($tougetu) : 0;
                        if (abs($tougetu) > 0) {
                            array_push($arrOther, $col);
                        }
                    }
                }
            }
            $result = array(
                'result' => TRUE,
                'data' => $arrOther
            );

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：全角ー＞半角を変更
    //関 数 名：make_semiangle
    //引    数：$str
    //戻 り 値：str
    //処理説明：実績取込
    //**********************************************************************
    function make_semiangle($str)
    {
        $arr = array(
            '０' => '0',
            '１' => '1',
            '２' => '2',
            '３' => '3',
            '４' => '4',
            '５' => '5',
            '６' => '6',
            '７' => '7',
            '８' => '8',
            '９' => '9',
            'Ａ' => 'A',
            'Ｂ' => 'B',
            'Ｃ' => 'C',
            'Ｄ' => 'D',
            'Ｅ' => 'E',
            'Ｆ' => 'F',
            'Ｇ' => 'G',
            'Ｈ' => 'H',
            'Ｉ' => 'I',
            'Ｊ' => 'J',
            'Ｋ' => 'K',
            'Ｌ' => 'L',
            'Ｍ' => 'M',
            'Ｎ' => 'N',
            'Ｏ' => 'O',
            'Ｐ' => 'P',
            'Ｑ' => 'Q',
            'Ｒ' => 'R',
            'Ｓ' => 'S',
            'Ｔ' => 'T',
            'Ｕ' => 'U',
            'Ｖ' => 'V',
            'Ｗ' => 'W',
            'Ｘ' => 'X',
            'Ｙ' => 'Y',
            'Ｚ' => 'Z',
            'ａ' => 'a',
            'ｂ' => 'b',
            'ｃ' => 'c',
            'ｄ' => 'd',
            'ｅ' => 'e',
            'ｆ' => 'f',
            'ｇ' => 'g',
            'ｈ' => 'h',
            'ｉ' => 'i',
            'ｊ' => 'j',
            'ｋ' => 'k',
            'ｌ' => 'l',
            'ｍ' => 'm',
            'ｎ' => 'n',
            'ｏ' => 'o',
            'ｐ' => 'p',
            'ｑ' => 'q',
            'ｒ' => 'r',
            'ｓ' => 's',
            'ｔ' => 't',
            'ｕ' => 'u',
            'ｖ' => 'v',
            'ｗ' => 'w',
            'ｘ' => 'x',
            'ｙ' => 'y',
            'ｚ' => 'z',
            '（' => '(',
            '）' => ')',
            '〔' => '[',
            '〕' => ']',
            '【' => '[',
            '】' => ']',
            '〖' => '[',
            '〗' => ']',
            '“' => '[',
            '”' => ']',
            '‘' => '[',
            '’' => ']',
            '｛' => '{',
            '｝' => '}',
            '《' => '<',
            '》' => '>',
            '％' => '%',
            '＋' => '+',
            '—' => '-',
            '－' => '-',
            '～' => '-',
            '：' => ':',
            '。' => '.',
            '、' => ',',
            '，' => '.',
            '、' => '.',
            '；' => ',',
            '？' => '?',
            '！' => '!',
            '…' => '-',
            '‖' => '|',
            '”' => '"',
            '’' => '`',
            '‘' => '`',
            '｜' => '|',
            '〃' => '"',
            '　' => ' '
        );
        return strtr($str, $arr);
    }

    //**********************************************************************
    //処 理 名：年月
    //関 数 名：getMon
    //引    数：$value,$worksheet
    //戻 り 値：str
    //処理説明：実績取込
    //**********************************************************************

    function getMon($value, $worksheet)
    {
        $monValue = '';
        $year = $this->kiToDate($worksheet->getCell('D3')->getValue());
        switch ($value) {
            //20160518 Update Start
//				case 'P' :
//					$monValue = 'O';
//					break;
//				case 'T' :
//					$monValue = 'S';
//					break;
//				case 'X' :
//					$monValue = 'W';
//					break;
//				case 'AG' :
//					$monValue = 'AF';
//					break;
//				case 'AK' :
//					$monValue = 'AJ';
//					break;
//				case 'AO' :
//					$monValue = 'AN';
//					break;
//				case 'AX' :
//					$monValue = 'AW';
//					break;
//				case 'BB' :
//					$monValue = 'BA';
//					break;
//				case 'BF' :
//					$monValue = 'BE';
//					break;
//				case 'BO' :
//					$monValue = 'BN';
//					break;
//				case 'BS' :
//					$monValue = 'BR';
//					break;
//				case 'BW' :
//					$monValue = 'BV';
//					break;

            case 'Q':
                $monValue = 'P';
                break;
            case 'V':
                $monValue = 'W';
                break;
            case 'AA':
                $monValue = 'Z';
                break;
            case 'AL':
                $monValue = 'AK';
                break;
            case 'AQ':
                $monValue = 'AP';
                break;
            case 'AV':
                $monValue = 'AU';
                break;
            case 'BG':
                $monValue = 'BF';
                break;
            case 'BL':
                $monValue = 'BK';
                break;
            case 'BQ':
                $monValue = 'BP';
                break;
            case 'CB':
                $monValue = 'CA';
                break;
            case 'CG':
                $monValue = 'CF';
                break;
            case 'CL':
                $monValue = 'CK';
                break;
            //20150915 Update End

        }


        $month = $worksheet->getCell($monValue . '7')->getValue();
        $month = str_replace('月', '', $month ?? '');
        $month = $this->make_semiangle($month);
        return strlen($month) == 2 ? $year . $month : ($year + 1) . '0' . $month;
    }

    //**********************************************************************
    //処 理 名：ファイルの名前のチェック
    //関 数 名：changeFileName
    //引    数：($param)
    //戻 り 値：$file_name;
    //処理説明：実績取込
    //**********************************************************************
    public function changeFileName($param)
    {
        $this->Session = $this->request->getSession();
        $strUserID = $this->Session->read('login_user');

        $arr = explode(".", $param);
        $long = count($arr) - 1;
        $file_type = $arr[$long];
        $file_name = '';
        for ($i = 0; $i < $long; $i++) {
            $file_name = $file_name . $arr[$i] . '.';
        }
        $file_name = substr($file_name, 0, strlen($file_name) - 1);
        $file_name = $strUserID . '_' . $file_name . '.' . $file_type;

        return $file_name;
    }

    public function fncCheckFile()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );
        try {
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
//            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');

            if (!file_exists($pathUpLoad)) {
                mkdir($pathUpLoad, 0777, TRUE);
            }
            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $this->FrmJissekiTorikomi = new FrmJissekiTorikomi();
                $file_name = $this->changeFileName($_FILES["file"]["name"]);
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
                } else {
                    $result['result'] = FALSE;
                    $result['data'] = 'ファイルのアップロードに失敗しました。';
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncCheckFileReturn($result);
    }

}
