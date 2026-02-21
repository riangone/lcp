<?php
/**
 *
 * ラインマスタメンテナンス
 *
 * @alias FrmLineMst
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20160128           #2374					  BUG                               li
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmGENRILIST;

class FrmGENRILISTController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmGENRILIST = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsLogControl');
    }
    public function index()
    {
        $this->render('index', 'FrmGENRILIST_layout');
    }

    public function frmGenkaiMakeLoad()
    {
        $result = array();
        try {
            $this->FrmGENRILIST = new FrmGENRILIST();
            $result = $this->FrmGENRILIST->fncSelect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdActionClick()
    {
        $result = array();
        $cboYM = "";
        $intState = 0;
        $lngOutCnt = 0;
        try {
            $cboYM = $_POST['data'];
            //ログ管理
            $intState = 9;
            $this->FrmGENRILIST = new FrmGENRILIST();
            $result = $this->FrmGENRILIST->fncGenriIchiran(str_replace('/', '', $cboYM));
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //印刷処理
            if (count($result['data']) > 0) {
                //'プレビュー表示
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';
                include_once $path_rpxTopdf . '/Component/tcpdf/rptGenriList.inc';

                $rpx_file_names = array();
                $tmp_data = array();
                $tmp = array();

                //*************
                $NEWARRAY = array();
                $Curernt_BUSYO = "";
                $Last_BUSYO = "";
                $Curernt_SYAIN = "";
                $Last_SYAIN = "";

                foreach ($result['data'] as $key => $value) {
                    //全社　合計 s.
                    //新車
                    foreach ($SIN_TOTAL as $key11 => $val11) {
                        //20240530 lujunxia upd s
                        //$value['TODAY']などはstring!
                        if ($key11 !== 'TODAY' && $key11 !== 'SIN_TOUKI_DAISU_TOTAL' && $key11 !== 'SIN_TOUKI_GENKAIRIEKI_TOTAL') {
                            $SIN_TOTAL[$key11] += $value[$key11];
                        }
                        //$SIN_TOTAL[$key11] += $value[$key11];
                        //20240530 lujunxia upd e
                    }
                    $SIN_TOTAL['TODAY'] = $value['TODAY'];
                    $SIN_TOTAL['SIN_TOUKI_DAISU_TOTAL'] = $value['SIN_TOUKI_DAISU_TOTAL'];
                    $SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_TOTAL'] = $value['SIN_TOUKI_GENKAIRIEKI_TOTAL'];
                    //中古車
                    foreach ($CHU_TOTAL as $key22 => $val22) {
                        //20240530 lujunxia upd s
                        //$value['TODAY']などはstring!
                        if ($key22 !== 'TODAY' && $key22 !== 'CHU_TOUKI_DAISU_TOTAL' && $key22 !== 'CHU_TOUKI_GENKAIRIEKI_TOTAL') {
                            $CHU_TOTAL[$key22] += $value[$key22];
                        }
                        //$CHU_TOTAL[$key22] += $value[$key22];
                        //20240530 lujunxia upd e
                    }
                    $CHU_TOTAL['TODAY'] = $value['TODAY'];
                    $CHU_TOTAL['CHU_TOUKI_DAISU_TOTAL'] = $value['CHU_TOUKI_DAISU_TOTAL'];
                    $CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_TOTAL'] = $value['CHU_TOUKI_GENKAIRIEKI_TOTAL'];
                    //他ﾁｬﾝﾈﾙ
                    foreach ($TA_TOTAL as $key33 => $val33) {
                        //20240530 lujunxia upd s
                        //$value['TODAY']などはstring!
                        if ($key33 !== 'TODAY' && $key33 !== 'TA_TOUKI_DAISU_TOTAL' && $key33 !== 'TA_TOUKI_GENKAIRIEKI_TOTAL') {
                            $TA_TOTAL[$key33] += $value[$key33];
                        }
                        //$TA_TOTAL[$key33] += $value[$key33];
                        //20240530 lujunxia upd e
                    }
                    $TA_TOTAL['TODAY'] = $value['TODAY'];
                    $TA_TOTAL['TA_TOUKI_DAISU_TOTAL'] = $value['TA_TOUKI_DAISU_TOTAL'];
                    $TA_TOTAL['TA_TOUKI_GENKAIRIEKI_TOTAL'] = $value['TA_TOUKI_GENKAIRIEKI_TOTAL'];
                    //全社　合計 e.

                    $Curernt_BUSYO = $value['ATUKAI_BUSYO'];
                    $Curernt_SYAIN = $value['ATUKAI_SYAIN'];
                    $value['flag'] = "Detail";
                    if ($Last_BUSYO == "" || $Curernt_SYAIN == "" || ($Last_BUSYO == $Curernt_BUSYO && $Last_SYAIN == $Curernt_SYAIN)) {
                        //新車
                        $SIN_SYAIN['TODAY'] = $value['TODAY'];
                        $SIN_SYAIN['ATUKAI_BUSYO'] = $value['ATUKAI_BUSYO'];
                        $SIN_SYAIN['BUSYO_NM'] = $value['BUSYO_NM'];
                        $SIN_SYAIN['ATUKAI_SYAIN'] = $value['ATUKAI_SYAIN'];
                        $SIN_SYAIN['SYAINMEI'] = $value['SYAINMEI'];
                        $SIN_SYAIN['SIN_TOUKI_DAISU'] = $value['SIN_TOUKI_DAISU'];
                        $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI'] = $value['SIN_TOUKI_GENKAIRIEKI'];
                        $SIN_SYAIN['SIN_TOUKI_DAISU_BUSYO'] = $value['SIN_TOUKI_DAISU_BUSYO'];
                        $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_BUSYO'] = $value['SIN_TOUKI_GENKAIRIEKI_BUSYO'];
                        //中古車
                        $CHU_SYAIN['TODAY'] = $value['TODAY'];
                        $CHU_SYAIN['ATUKAI_BUSYO'] = $value['ATUKAI_BUSYO'];
                        $CHU_SYAIN['BUSYO_NM'] = $value['BUSYO_NM'];
                        $CHU_SYAIN['ATUKAI_SYAIN'] = $value['ATUKAI_SYAIN'];
                        $CHU_SYAIN['SYAINMEI'] = $value['SYAINMEI'];
                        $CHU_SYAIN['CHU_TOUKI_DAISU'] = $value['CHU_TOUKI_DAISU'];
                        $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI'] = $value['CHU_TOUKI_GENKAIRIEKI'];
                        $CHU_SYAIN['CHU_TOUKI_DAISU_BUSYO'] = $value['CHU_TOUKI_DAISU_BUSYO'];
                        $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_BUSYO'] = $value['CHU_TOUKI_GENKAIRIEKI_BUSYO'];
                        //他ﾁｬﾝﾈﾙ
                        $TA_SYAIN['TODAY'] = $value['TODAY'];
                        $TA_SYAIN['ATUKAI_BUSYO'] = $value['ATUKAI_BUSYO'];
                        $TA_SYAIN['BUSYO_NM'] = $value['BUSYO_NM'];
                        $TA_SYAIN['ATUKAI_SYAIN'] = $value['ATUKAI_SYAIN'];
                        $TA_SYAIN['SYAINMEI'] = $value['SYAINMEI'];
                        $TA_SYAIN['TA_TOUKI_DAISU'] = $value['TA_TOUKI_DAISU'];
                        $TA_SYAIN['TA_TOUKI_GENKAIRIEKI'] = $value['TA_TOUKI_GENKAIRIEKI'];
                        $TA_SYAIN['TA_TOUKI_DAISU_BUSYO'] = $value['TA_TOUKI_DAISU_BUSYO'];
                        $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_BUSYO'] = $value['TA_TOUKI_GENKAIRIEKI_BUSYO'];
                        switch ($value['DATA_KB']) {
                            //ｾｰﾙｽ:新車
                            case 1:
                                //売上
                                $SIN_SYAIN['SIN_DAISU'] += $value['SIN_DAISU'];
                                //下取
                                $SIN_SYAIN['SIN_SIT_DAISU'] += $value['SIN_SIT_DAISU'];

                                $SIN_SYAIN['SIN_URIAGE'] += $value['SIN_URIAGE'];
                                $SIN_SYAIN['SIN_SYARYOU_RIEKI'] += $value['SIN_SYARYOU_RIEKI'];
                                $SIN_SYAIN['SIN_KASOU_RIEKI'] += $value['SIN_KASOU_RIEKI'];
                                $SIN_SYAIN['SIN_KAPPU_RIEKI'] += $value['SIN_KAPPU_RIEKI'];
                                $SIN_SYAIN['SIN_TOUROKU_RIEKI'] += $value['SIN_TOUROKU_RIEKI'];
                                $SIN_SYAIN['SIN_UCHIKOMIKIN'] += $value['SIN_UCHIKOMIKIN'];
                                $SIN_SYAIN['SIN_URI_GENKA'] += $value['SIN_URI_GENKA'];
                                $SIN_SYAIN['SIN_SITADORI_SON'] += $value['SIN_SITADORI_SON'];
                                $SIN_SYAIN['SIN_HANBAITESURYO'] += $value['SIN_HANBAITESURYO'];
                                $SIN_SYAIN['SIN_SYOUKAIRYO'] += $value['SIN_SYOUKAIRYO'];
                                $SIN_SYAIN['SIN_CHUKOSYA_GENRI'] += $value['SIN_CHUKOSYA_GENRI'];
                                $SIN_SYAIN['SIN_TOUGETU_GENRI'] += $value['SIN_TOUGETU_GENRI'];
                                break;
                            //ｾｰﾙｽ:中古
                            case 2:
                                //中古車
                                $CHU_SYAIN['CHU_DAISU'] += $value['CHU_DAISU'];
                                //下取
                                $CHU_SYAIN['CHU_SIT_DAISU'] += $value['CHU_SIT_DAISU'];

                                $CHU_SYAIN['CHU_URIAGE'] += $value['CHU_URIAGE'];
                                $CHU_SYAIN['CHU_SYARYOU_RIEKI'] += $value['CHU_SYARYOU_RIEKI'];
                                $CHU_SYAIN['CHU_KASOU_RIEKI'] += $value['CHU_KASOU_RIEKI'];
                                $CHU_SYAIN['CHU_KAPPU_RIEKI'] += $value['CHU_KAPPU_RIEKI'];
                                $CHU_SYAIN['CHU_TOUROKU_RIEKI'] += $value['CHU_TOUROKU_RIEKI'];
                                $CHU_SYAIN['CHU_UCHIKOMIKIN'] += $value['CHU_UCHIKOMIKIN'];
                                $CHU_SYAIN['CHU_URI_GENKA'] += $value['CHU_URI_GENKA'];
                                $CHU_SYAIN['CHU_SITADORI_SON'] += $value['CHU_SITADORI_SON'];
                                $CHU_SYAIN['CHU_HANBAITESURYO'] += $value['CHU_HANBAITESURYO'];
                                $CHU_SYAIN['CHU_SYOUKAIRYO'] += $value['CHU_SYOUKAIRYO'];
                                $CHU_SYAIN['CHU_CHUKOSYA_GENRI'] += $value['CHU_CHUKOSYA_GENRI'];
                                $CHU_SYAIN['CHU_TOUGETU_GENRI'] += $value['CHU_TOUGETU_GENRI'];
                                break;
                            //ｾｰﾙｽ:他ﾁｬﾝﾈﾙ
                            case 3:
                                //他ﾁｬﾝﾈﾙ
                                $TA_SYAIN['TA_DAISU'] += $value['TA_DAISU'];
                                //下取
                                $TA_SYAIN['TA_SIT_DAISU'] += $value['TA_SIT_DAISU'];

                                $TA_SYAIN['TA_URIAGE'] += $value['TA_URIAGE'];
                                $TA_SYAIN['TA_SYARYOU_RIEKI'] += $value['TA_SYARYOU_RIEKI'];
                                $TA_SYAIN['TA_KASOU_RIEKI'] += $value['TA_KASOU_RIEKI'];
                                $TA_SYAIN['TA_KAPPU_RIEKI'] += $value['TA_KAPPU_RIEKI'];
                                $TA_SYAIN['TA_TOUROKU_RIEKI'] += $value['TA_TOUROKU_RIEKI'];
                                $TA_SYAIN['TA_UCHIKOMIKIN'] += $value['TA_UCHIKOMIKIN'];
                                $TA_SYAIN['TA_URI_GENKA'] += $value['TA_URI_GENKA'];
                                $TA_SYAIN['TA_SITADORI_SON'] += $value['TA_SITADORI_SON'];
                                $TA_SYAIN['TA_HANBAITESURYO'] += $value['TA_HANBAITESURYO'];
                                $TA_SYAIN['TA_SYOUKAIRYO'] += $value['TA_SYOUKAIRYO'];
                                $TA_SYAIN['TA_CHUKOSYA_GENRI'] += $value['TA_CHUKOSYA_GENRI'];
                                $TA_SYAIN['TA_TOUGETU_GENRI'] += $value['TA_TOUGETU_GENRI'];
                                break;
                        }
                    } elseif ($Last_SYAIN != $Curernt_SYAIN && $Last_BUSYO == $Curernt_BUSYO) {
                        //新車
                        foreach ($SIN_SYAIN as $key1 => $val1) {
                            //20240530 lujunxia upd s
                            //$value['TODAY']などはstring!
                            if ($key1 !== 'TODAY' && $key1 !== 'ATUKAI_BUSYO' && $key1 !== 'BUSYO_NM' && $key1 !== 'ATUKAI_SYAIN' && $key1 !== 'SYAINMEI') {
                                $SIN_BUSYO[$key1] += $val1;
                            } else {
                                //旧システムと同じのため
                                if ($key1 == 'ATUKAI_SYAIN' || $key1 == 'SYAINMEI') {
                                    $SIN_BUSYO[$key1] = 0;
                                }
                            }
                            //$SIN_BUSYO[$key1] += $val1;
                            //20240530 lujunxia upd e
                        }
                        $SIN_BUSYO['TODAY'] = $SIN_SYAIN['TODAY'];
                        $SIN_BUSYO['ATUKAI_BUSYO'] = $SIN_SYAIN['ATUKAI_BUSYO'];
                        $SIN_BUSYO['BUSYO_NM'] = $SIN_SYAIN['BUSYO_NM'];
                        $SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO'] = $SIN_SYAIN['SIN_TOUKI_DAISU_BUSYO'];
                        $SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_BUSYO'] = $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_BUSYO'];
                        $this->GroupFooter5_BeforePrint($SIN_SYAIN);
                        $SIN_SYAIN['flag'] = "GroupFooter5";
                        //visible字段   0:不表示；1:表示
                        if ($this->GroupFooter5_Format($SIN_SYAIN)) {
                            $SIN_SYAIN['visible'] = 1;
                        } else {
                            $SIN_SYAIN['visible'] = 0;
                        }
                        array_push($NEWARRAY, $SIN_SYAIN);
                        //reset the value
                        $SIN_SYAIN = $SIN_SYAIN1;

                        //中古車
                        foreach ($CHU_SYAIN as $key2 => $val2) {
                            //20240530 lujunxia upd s
                            //$value['TODAY']などはstring!
                            if ($key2 !== 'TODAY' && $key2 !== 'ATUKAI_BUSYO' && $key2 !== 'BUSYO_NM' && $key2 !== 'ATUKAI_SYAIN' && $key2 !== 'SYAINMEI') {
                                $CHU_BUSYO[$key2] += $val2;
                            } else {
                                //旧システムと同じのため
                                if ($key2 == 'ATUKAI_SYAIN' || $key2 == 'SYAINMEI') {
                                    $CHU_BUSYO[$key2] = 0;
                                }
                            }
                            //$CHU_BUSYO[$key2] += $val2;
                            //20240530 lujunxia upd e
                        }
                        $CHU_BUSYO['TODAY'] = $CHU_SYAIN['TODAY'];
                        $CHU_BUSYO['ATUKAI_BUSYO'] = $CHU_SYAIN['ATUKAI_BUSYO'];
                        $CHU_BUSYO['BUSYO_NM'] = $CHU_SYAIN['BUSYO_NM'];
                        $CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO'] = $CHU_SYAIN['CHU_TOUKI_DAISU_BUSYO'];
                        $CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_BUSYO'] = $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_BUSYO'];
                        $this->GroupFooter4_BeforePrint($CHU_SYAIN);
                        $CHU_SYAIN['flag'] = "GroupFooter4";
                        if ($this->GroupFooter4_Format($CHU_SYAIN)) {
                            $CHU_SYAIN['visible'] = 1;
                        } else {
                            $CHU_SYAIN['visible'] = 0;
                        }
                        array_push($NEWARRAY, $CHU_SYAIN);
                        //reset the value
                        $CHU_SYAIN = $CHU_SYAIN1;

                        //他ﾁｬﾝﾈﾙ
                        foreach ($TA_SYAIN as $key3 => $val3) {
                            //20240530 lujunxia upd s
                            //$value['TODAY']などはstring!
                            if ($key3 !== 'TODAY' && $key3 !== 'ATUKAI_BUSYO' && $key3 !== 'BUSYO_NM' && $key3 !== 'ATUKAI_SYAIN' && $key3 !== 'SYAINMEI') {
                                $TA_BUSYO[$key3] += $val3;
                            } else {
                                //旧システムと同じのため
                                if ($key3 == 'ATUKAI_SYAIN' || $key3 == 'SYAINMEI') {
                                    $TA_BUSYO[$key3] = 0;
                                }
                            }
                            //$TA_BUSYO[$key3] += $val3;
                            //20240530 lujunxia upd e

                        }
                        $TA_BUSYO['TODAY'] = $TA_SYAIN['TODAY'];
                        $TA_BUSYO['ATUKAI_BUSYO'] = $TA_SYAIN['ATUKAI_BUSYO'];
                        $TA_BUSYO['BUSYO_NM'] = $TA_SYAIN['BUSYO_NM'];
                        $TA_BUSYO['TA_TOUKI_DAISU_BUSYO'] = $TA_SYAIN['TA_TOUKI_DAISU_BUSYO'];
                        $TA_BUSYO['TA_TOUKI_GENKAIRIEKI_BUSYO'] = $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_BUSYO'];
                        $this->GroupFooter2_BeforePrint($TA_SYAIN);
                        $TA_SYAIN['flag'] = "GroupFooter2";
                        if ($this->GroupFooter2_Format($TA_SYAIN)) {
                            $TA_SYAIN['visible'] = 1;
                        } else {
                            $TA_SYAIN['visible'] = 0;
                        }
                        array_push($NEWARRAY, $TA_SYAIN);
                        //reset the value
                        $TA_SYAIN = $TA_SYAIN1;

                        //新車
                        $SIN_SYAIN['TODAY'] = $value['TODAY'];
                        $SIN_SYAIN['ATUKAI_BUSYO'] = $value['ATUKAI_BUSYO'];
                        $SIN_SYAIN['BUSYO_NM'] = $value['BUSYO_NM'];
                        $SIN_SYAIN['ATUKAI_SYAIN'] = $value['ATUKAI_SYAIN'];
                        $SIN_SYAIN['SYAINMEI'] = $value['SYAINMEI'];
                        $SIN_SYAIN['SIN_TOUKI_DAISU'] = $value['SIN_TOUKI_DAISU'];
                        $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI'] = $value['SIN_TOUKI_GENKAIRIEKI'];
                        $SIN_SYAIN['SIN_TOUKI_DAISU_BUSYO'] = $value['SIN_TOUKI_DAISU_BUSYO'];
                        $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_BUSYO'] = $value['SIN_TOUKI_GENKAIRIEKI_BUSYO'];
                        //中古車
                        $CHU_SYAIN['TODAY'] = $value['TODAY'];
                        $CHU_SYAIN['ATUKAI_BUSYO'] = $value['ATUKAI_BUSYO'];
                        $CHU_SYAIN['BUSYO_NM'] = $value['BUSYO_NM'];
                        $CHU_SYAIN['ATUKAI_SYAIN'] = $value['ATUKAI_SYAIN'];
                        $CHU_SYAIN['SYAINMEI'] = $value['SYAINMEI'];
                        $CHU_SYAIN['CHU_TOUKI_DAISU'] = $value['CHU_TOUKI_DAISU'];
                        $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI'] = $value['CHU_TOUKI_GENKAIRIEKI'];
                        $CHU_SYAIN['CHU_TOUKI_DAISU_BUSYO'] = $value['CHU_TOUKI_DAISU_BUSYO'];
                        $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_BUSYO'] = $value['CHU_TOUKI_GENKAIRIEKI_BUSYO'];
                        //他ﾁｬﾝﾈﾙ
                        $TA_SYAIN['TODAY'] = $value['TODAY'];
                        $TA_SYAIN['ATUKAI_BUSYO'] = $value['ATUKAI_BUSYO'];
                        $TA_SYAIN['BUSYO_NM'] = $value['BUSYO_NM'];
                        $TA_SYAIN['ATUKAI_SYAIN'] = $value['ATUKAI_SYAIN'];
                        $TA_SYAIN['SYAINMEI'] = $value['SYAINMEI'];
                        $TA_SYAIN['TA_TOUKI_DAISU'] = $value['TA_TOUKI_DAISU'];
                        $TA_SYAIN['TA_TOUKI_GENKAIRIEKI'] = $value['TA_TOUKI_GENKAIRIEKI'];
                        $TA_SYAIN['TA_TOUKI_DAISU_BUSYO'] = $value['TA_TOUKI_DAISU_BUSYO'];
                        $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_BUSYO'] = $value['TA_TOUKI_GENKAIRIEKI_BUSYO'];
                        switch ($value['DATA_KB']) {
                            //ｾｰﾙｽ:新車
                            case 1:
                                //売上
                                $SIN_SYAIN['SIN_DAISU'] += $value['SIN_DAISU'];
                                //下取
                                $SIN_SYAIN['SIN_SIT_DAISU'] += $value['SIN_SIT_DAISU'];

                                $SIN_SYAIN['SIN_URIAGE'] += $value['SIN_URIAGE'];
                                $SIN_SYAIN['SIN_SYARYOU_RIEKI'] += $value['SIN_SYARYOU_RIEKI'];
                                $SIN_SYAIN['SIN_KASOU_RIEKI'] += $value['SIN_KASOU_RIEKI'];
                                $SIN_SYAIN['SIN_KAPPU_RIEKI'] += $value['SIN_KAPPU_RIEKI'];
                                $SIN_SYAIN['SIN_TOUROKU_RIEKI'] += $value['SIN_TOUROKU_RIEKI'];
                                $SIN_SYAIN['SIN_UCHIKOMIKIN'] += $value['SIN_UCHIKOMIKIN'];
                                $SIN_SYAIN['SIN_URI_GENKA'] += $value['SIN_URI_GENKA'];
                                $SIN_SYAIN['SIN_SITADORI_SON'] += $value['SIN_SITADORI_SON'];
                                $SIN_SYAIN['SIN_HANBAITESURYO'] += $value['SIN_HANBAITESURYO'];
                                $SIN_SYAIN['SIN_SYOUKAIRYO'] += $value['SIN_SYOUKAIRYO'];
                                $SIN_SYAIN['SIN_CHUKOSYA_GENRI'] += $value['SIN_CHUKOSYA_GENRI'];
                                $SIN_SYAIN['SIN_TOUGETU_GENRI'] += $value['SIN_TOUGETU_GENRI'];
                                break;
                            //ｾｰﾙｽ:中古
                            case 2:
                                //中古車
                                $CHU_SYAIN['CHU_DAISU'] += $value['CHU_DAISU'];
                                //下取
                                $CHU_SYAIN['CHU_SIT_DAISU'] += $value['CHU_SIT_DAISU'];

                                $CHU_SYAIN['CHU_URIAGE'] += $value['CHU_URIAGE'];
                                $CHU_SYAIN['CHU_SYARYOU_RIEKI'] += $value['CHU_SYARYOU_RIEKI'];
                                $CHU_SYAIN['CHU_KASOU_RIEKI'] += $value['CHU_KASOU_RIEKI'];
                                $CHU_SYAIN['CHU_KAPPU_RIEKI'] += $value['CHU_KAPPU_RIEKI'];
                                $CHU_SYAIN['CHU_TOUROKU_RIEKI'] += $value['CHU_TOUROKU_RIEKI'];
                                $CHU_SYAIN['CHU_UCHIKOMIKIN'] += $value['CHU_UCHIKOMIKIN'];
                                $CHU_SYAIN['CHU_URI_GENKA'] += $value['CHU_URI_GENKA'];
                                $CHU_SYAIN['CHU_SITADORI_SON'] += $value['CHU_SITADORI_SON'];
                                $CHU_SYAIN['CHU_HANBAITESURYO'] += $value['CHU_HANBAITESURYO'];
                                $CHU_SYAIN['CHU_SYOUKAIRYO'] += $value['CHU_SYOUKAIRYO'];
                                $CHU_SYAIN['CHU_CHUKOSYA_GENRI'] += $value['CHU_CHUKOSYA_GENRI'];
                                $CHU_SYAIN['CHU_TOUGETU_GENRI'] += $value['CHU_TOUGETU_GENRI'];
                                break;
                            //ｾｰﾙｽ:他ﾁｬﾝﾈﾙ
                            case 3:
                                //他ﾁｬﾝﾈﾙ
                                $TA_SYAIN['TA_DAISU'] += $value['TA_DAISU'];
                                //下取
                                $TA_SYAIN['TA_SIT_DAISU'] += $value['TA_SIT_DAISU'];

                                $TA_SYAIN['TA_URIAGE'] += $value['TA_URIAGE'];
                                $TA_SYAIN['TA_SYARYOU_RIEKI'] += $value['TA_SYARYOU_RIEKI'];
                                $TA_SYAIN['TA_KASOU_RIEKI'] += $value['TA_KASOU_RIEKI'];
                                $TA_SYAIN['TA_KAPPU_RIEKI'] += $value['TA_KAPPU_RIEKI'];
                                $TA_SYAIN['TA_TOUROKU_RIEKI'] += $value['TA_TOUROKU_RIEKI'];
                                $TA_SYAIN['TA_UCHIKOMIKIN'] += $value['TA_UCHIKOMIKIN'];
                                $TA_SYAIN['TA_URI_GENKA'] += $value['TA_URI_GENKA'];
                                $TA_SYAIN['TA_SITADORI_SON'] += $value['TA_SITADORI_SON'];
                                $TA_SYAIN['TA_HANBAITESURYO'] += $value['TA_HANBAITESURYO'];
                                $TA_SYAIN['TA_SYOUKAIRYO'] += $value['TA_SYOUKAIRYO'];
                                $TA_SYAIN['TA_CHUKOSYA_GENRI'] += $value['TA_CHUKOSYA_GENRI'];
                                $TA_SYAIN['TA_TOUGETU_GENRI'] += $value['TA_TOUGETU_GENRI'];
                                break;
                        }

                    } elseif ($Last_BUSYO != $Curernt_BUSYO) {
                        //新車
                        foreach ($SIN_SYAIN as $key1 => $val1) {
                            //20240530 lujunxia upd s
                            //$value['TODAY']などはstring!
                            if ($key1 !== 'TODAY' && $key1 !== 'ATUKAI_BUSYO' && $key1 !== 'BUSYO_NM' && $key1 !== 'ATUKAI_SYAIN' && $key1 !== 'SYAINMEI') {
                                $SIN_BUSYO[$key1] += $val1;
                            } else {
                                //旧システムと同じのため
                                if ($key1 == 'ATUKAI_SYAIN' || $key1 == 'SYAINMEI') {
                                    $SIN_BUSYO[$key1] = 0;
                                }
                            }
                            //$SIN_BUSYO[$key1] += $val1;
                            //20240530 lujunxia upd e
                        }
                        $SIN_BUSYO['TODAY'] = $SIN_SYAIN['TODAY'];
                        $SIN_BUSYO['ATUKAI_BUSYO'] = $SIN_SYAIN['ATUKAI_BUSYO'];
                        $SIN_BUSYO['BUSYO_NM'] = $SIN_SYAIN['BUSYO_NM'];
                        $SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO'] = $SIN_SYAIN['SIN_TOUKI_DAISU_BUSYO'];
                        $SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_BUSYO'] = $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_BUSYO'];
                        $this->GroupFooter5_BeforePrint($SIN_SYAIN);
                        $SIN_SYAIN['flag'] = "GroupFooter5";
                        //visible字段   0:不表示；1:表示
                        if ($this->GroupFooter5_Format($SIN_SYAIN)) {
                            $SIN_SYAIN['visible'] = 1;
                        } else {
                            $SIN_SYAIN['visible'] = 0;
                        }
                        array_push($NEWARRAY, $SIN_SYAIN);
                        //reset the value
                        $SIN_SYAIN = $SIN_SYAIN1;

                        //中古車
                        foreach ($CHU_SYAIN as $key2 => $val2) {
                            //20240530 lujunxia upd s
                            //$value['TODAY']などはstring!
                            if ($key2 !== 'TODAY' && $key2 !== 'ATUKAI_BUSYO' && $key2 !== 'BUSYO_NM' && $key2 !== 'ATUKAI_SYAIN' && $key2 !== 'SYAINMEI') {
                                $CHU_BUSYO[$key2] += $val2;
                            } else {
                                if ($key2 == 'ATUKAI_SYAIN' || $key2 == 'SYAINMEI') {
                                    $CHU_BUSYO[$key2] = 0;
                                }
                            }
                            //$CHU_BUSYO[$key2] += $val2;
                            //20240530 lujunxia upd e
                        }
                        $CHU_BUSYO['TODAY'] = $CHU_SYAIN['TODAY'];
                        $CHU_BUSYO['ATUKAI_BUSYO'] = $CHU_SYAIN['ATUKAI_BUSYO'];
                        $CHU_BUSYO['BUSYO_NM'] = $CHU_SYAIN['BUSYO_NM'];
                        $CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO'] = $CHU_SYAIN['CHU_TOUKI_DAISU_BUSYO'];
                        $CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_BUSYO'] = $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_BUSYO'];
                        $this->GroupFooter4_BeforePrint($CHU_SYAIN);
                        $CHU_SYAIN['flag'] = "GroupFooter4";
                        if ($this->GroupFooter4_Format($CHU_SYAIN)) {
                            $CHU_SYAIN['visible'] = 1;
                        } else {
                            $CHU_SYAIN['visible'] = 0;
                        }
                        array_push($NEWARRAY, $CHU_SYAIN);
                        //reset the value
                        $CHU_SYAIN = $CHU_SYAIN1;

                        //他ﾁｬﾝﾈﾙ
                        foreach ($TA_SYAIN as $key3 => $val3) {
                            //20240530 lujunxia upd s
                            //$value['TODAY']などはstring!
                            if ($key3 !== 'TODAY' && $key3 !== 'ATUKAI_BUSYO' && $key3 !== 'BUSYO_NM' && $key3 !== 'ATUKAI_SYAIN' && $key3 !== 'SYAINMEI') {
                                $TA_BUSYO[$key3] += $val3;
                            } else {
                                if ($key3 == 'ATUKAI_SYAIN' || $key3 == 'SYAINMEI') {
                                    $TA_BUSYO[$key3] = 0;
                                }
                            }
                            //$TA_BUSYO[$key3] += $val3;
                            //20240530 lujunxia upd e
                        }
                        $TA_BUSYO['TODAY'] = $TA_SYAIN['TODAY'];
                        $TA_BUSYO['ATUKAI_BUSYO'] = $TA_SYAIN['ATUKAI_BUSYO'];
                        $TA_BUSYO['BUSYO_NM'] = $TA_SYAIN['BUSYO_NM'];
                        $TA_BUSYO['TA_TOUKI_DAISU_BUSYO'] = $TA_SYAIN['TA_TOUKI_DAISU_BUSYO'];
                        $TA_BUSYO['TA_TOUKI_GENKAIRIEKI_BUSYO'] = $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_BUSYO'];
                        $this->GroupFooter2_BeforePrint($TA_SYAIN);
                        $TA_SYAIN['flag'] = "GroupFooter2";
                        if ($this->GroupFooter2_Format($TA_SYAIN)) {
                            $TA_SYAIN['visible'] = 1;
                        } else {
                            $TA_SYAIN['visible'] = 0;
                        }
                        array_push($NEWARRAY, $TA_SYAIN);
                        //reset the value
                        $TA_SYAIN = $TA_SYAIN1;

                        //新車
                        $this->GroupFooter6_BeforePrint($SIN_BUSYO);
                        $SIN_BUSYO['flag'] = "GroupFooter6";
                        if ($this->GroupFooter6_Format($SIN_BUSYO)) {
                            $SIN_BUSYO['visible'] = 1;
                        } else {
                            $SIN_BUSYO['visible'] = 0;
                        }
                        array_push($NEWARRAY, $SIN_BUSYO);
                        //reset the value
                        $SIN_BUSYO = $SIN_BUSYO1;

                        //中古車
                        $this->GroupFooter7_BeforePrint($CHU_BUSYO);
                        $CHU_BUSYO['flag'] = "GroupFooter7";
                        if ($this->GroupFooter7_Format($CHU_BUSYO)) {
                            $CHU_BUSYO['visible'] = 1;
                        } else {
                            $CHU_BUSYO['visible'] = 0;
                        }
                        array_push($NEWARRAY, $CHU_BUSYO);
                        //reset the value
                        $CHU_BUSYO = $CHU_BUSYO1;

                        //他ﾁｬﾝﾈﾙ
                        $this->GroupFooter1_BeforePrint($TA_BUSYO);
                        $TA_BUSYO['flag'] = "GroupFooter1";
                        if ($this->GroupFooter1_Format($TA_BUSYO)) {
                            $TA_BUSYO['visible'] = 1;
                        } else {
                            $TA_BUSYO['visible'] = 0;
                        }
                        array_push($NEWARRAY, $TA_BUSYO);
                        //reset the value
                        $TA_BUSYO = $TA_BUSYO1;

                        //新車
                        $SIN_SYAIN['TODAY'] = $value['TODAY'];
                        $SIN_SYAIN['ATUKAI_BUSYO'] = $value['ATUKAI_BUSYO'];
                        $SIN_SYAIN['BUSYO_NM'] = $value['BUSYO_NM'];
                        $SIN_SYAIN['ATUKAI_SYAIN'] = $value['ATUKAI_SYAIN'];
                        $SIN_SYAIN['SYAINMEI'] = $value['SYAINMEI'];
                        $SIN_SYAIN['SIN_TOUKI_DAISU'] = $value['SIN_TOUKI_DAISU'];
                        $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI'] = $value['SIN_TOUKI_GENKAIRIEKI'];
                        $SIN_SYAIN['SIN_TOUKI_DAISU_BUSYO'] = $value['SIN_TOUKI_DAISU_BUSYO'];
                        $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_BUSYO'] = $value['SIN_TOUKI_GENKAIRIEKI_BUSYO'];
                        //中古車
                        $CHU_SYAIN['TODAY'] = $value['TODAY'];
                        $CHU_SYAIN['ATUKAI_BUSYO'] = $value['ATUKAI_BUSYO'];
                        $CHU_SYAIN['BUSYO_NM'] = $value['BUSYO_NM'];
                        $CHU_SYAIN['ATUKAI_SYAIN'] = $value['ATUKAI_SYAIN'];
                        $CHU_SYAIN['SYAINMEI'] = $value['SYAINMEI'];
                        $CHU_SYAIN['CHU_TOUKI_DAISU'] = $value['CHU_TOUKI_DAISU'];
                        $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI'] = $value['CHU_TOUKI_GENKAIRIEKI'];
                        $CHU_SYAIN['CHU_TOUKI_DAISU_BUSYO'] = $value['CHU_TOUKI_DAISU_BUSYO'];
                        $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_BUSYO'] = $value['CHU_TOUKI_GENKAIRIEKI_BUSYO'];
                        //他ﾁｬﾝﾈﾙ
                        $TA_SYAIN['TODAY'] = $value['TODAY'];
                        $TA_SYAIN['ATUKAI_BUSYO'] = $value['ATUKAI_BUSYO'];
                        $TA_SYAIN['BUSYO_NM'] = $value['BUSYO_NM'];
                        $TA_SYAIN['ATUKAI_SYAIN'] = $value['ATUKAI_SYAIN'];
                        $TA_SYAIN['SYAINMEI'] = $value['SYAINMEI'];
                        $TA_SYAIN['TA_TOUKI_DAISU'] = $value['TA_TOUKI_DAISU'];
                        $TA_SYAIN['TA_TOUKI_GENKAIRIEKI'] = $value['TA_TOUKI_GENKAIRIEKI'];
                        $TA_SYAIN['TA_TOUKI_DAISU_BUSYO'] = $value['TA_TOUKI_DAISU_BUSYO'];
                        $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_BUSYO'] = $value['TA_TOUKI_GENKAIRIEKI_BUSYO'];
                        switch ($value['DATA_KB']) {
                            //ｾｰﾙｽ:新車
                            case 1:
                                //売上
                                $SIN_SYAIN['SIN_DAISU'] += $value['SIN_DAISU'];
                                //下取
                                $SIN_SYAIN['SIN_SIT_DAISU'] += $value['SIN_SIT_DAISU'];

                                $SIN_SYAIN['SIN_URIAGE'] += $value['SIN_URIAGE'];
                                $SIN_SYAIN['SIN_SYARYOU_RIEKI'] += $value['SIN_SYARYOU_RIEKI'];
                                $SIN_SYAIN['SIN_KASOU_RIEKI'] += $value['SIN_KASOU_RIEKI'];
                                $SIN_SYAIN['SIN_KAPPU_RIEKI'] += $value['SIN_KAPPU_RIEKI'];
                                $SIN_SYAIN['SIN_TOUROKU_RIEKI'] += $value['SIN_TOUROKU_RIEKI'];
                                $SIN_SYAIN['SIN_UCHIKOMIKIN'] += $value['SIN_UCHIKOMIKIN'];
                                $SIN_SYAIN['SIN_URI_GENKA'] += $value['SIN_URI_GENKA'];
                                $SIN_SYAIN['SIN_SITADORI_SON'] += $value['SIN_SITADORI_SON'];
                                $SIN_SYAIN['SIN_HANBAITESURYO'] += $value['SIN_HANBAITESURYO'];
                                $SIN_SYAIN['SIN_SYOUKAIRYO'] += $value['SIN_SYOUKAIRYO'];
                                $SIN_SYAIN['SIN_CHUKOSYA_GENRI'] += $value['SIN_CHUKOSYA_GENRI'];
                                $SIN_SYAIN['SIN_TOUGETU_GENRI'] += $value['SIN_TOUGETU_GENRI'];
                                break;
                            //ｾｰﾙｽ:中古
                            case 2:
                                //中古車
                                $CHU_SYAIN['CHU_DAISU'] += $value['CHU_DAISU'];
                                //下取
                                $CHU_SYAIN['CHU_SIT_DAISU'] += $value['CHU_SIT_DAISU'];

                                $CHU_SYAIN['CHU_URIAGE'] += $value['CHU_URIAGE'];
                                $CHU_SYAIN['CHU_SYARYOU_RIEKI'] += $value['CHU_SYARYOU_RIEKI'];
                                $CHU_SYAIN['CHU_KASOU_RIEKI'] += $value['CHU_KASOU_RIEKI'];
                                $CHU_SYAIN['CHU_KAPPU_RIEKI'] += $value['CHU_KAPPU_RIEKI'];
                                $CHU_SYAIN['CHU_TOUROKU_RIEKI'] += $value['CHU_TOUROKU_RIEKI'];
                                $CHU_SYAIN['CHU_UCHIKOMIKIN'] += $value['CHU_UCHIKOMIKIN'];
                                $CHU_SYAIN['CHU_URI_GENKA'] += $value['CHU_URI_GENKA'];
                                $CHU_SYAIN['CHU_SITADORI_SON'] += $value['CHU_SITADORI_SON'];
                                $CHU_SYAIN['CHU_HANBAITESURYO'] += $value['CHU_HANBAITESURYO'];
                                $CHU_SYAIN['CHU_SYOUKAIRYO'] += $value['CHU_SYOUKAIRYO'];
                                $CHU_SYAIN['CHU_CHUKOSYA_GENRI'] += $value['CHU_CHUKOSYA_GENRI'];
                                $CHU_SYAIN['CHU_TOUGETU_GENRI'] += $value['CHU_TOUGETU_GENRI'];
                                break;
                            //ｾｰﾙｽ:他ﾁｬﾝﾈﾙ
                            case 3:
                                //他ﾁｬﾝﾈﾙ
                                $TA_SYAIN['TA_DAISU'] += $value['TA_DAISU'];
                                //下取
                                $TA_SYAIN['TA_SIT_DAISU'] += $value['TA_SIT_DAISU'];

                                $TA_SYAIN['TA_URIAGE'] += $value['TA_URIAGE'];
                                $TA_SYAIN['TA_SYARYOU_RIEKI'] += $value['TA_SYARYOU_RIEKI'];
                                $TA_SYAIN['TA_KASOU_RIEKI'] += $value['TA_KASOU_RIEKI'];
                                $TA_SYAIN['TA_KAPPU_RIEKI'] += $value['TA_KAPPU_RIEKI'];
                                $TA_SYAIN['TA_TOUROKU_RIEKI'] += $value['TA_TOUROKU_RIEKI'];
                                $TA_SYAIN['TA_UCHIKOMIKIN'] += $value['TA_UCHIKOMIKIN'];
                                $TA_SYAIN['TA_URI_GENKA'] += $value['TA_URI_GENKA'];
                                $TA_SYAIN['TA_SITADORI_SON'] += $value['TA_SITADORI_SON'];
                                $TA_SYAIN['TA_HANBAITESURYO'] += $value['TA_HANBAITESURYO'];
                                $TA_SYAIN['TA_SYOUKAIRYO'] += $value['TA_SYOUKAIRYO'];
                                $TA_SYAIN['TA_CHUKOSYA_GENRI'] += $value['TA_CHUKOSYA_GENRI'];
                                $TA_SYAIN['TA_TOUGETU_GENRI'] += $value['TA_TOUGETU_GENRI'];
                                break;
                        }
                    }
                    $Last_SYAIN = $Curernt_SYAIN;
                    $Last_BUSYO = $Curernt_BUSYO;
                    $this->Detail_BeforePrint($value);
                    array_push($NEWARRAY, $value);
                }
                //新車
                foreach ($SIN_SYAIN as $key1 => $val1) {
                    //20240530 lujunxia upd s
                    //$value['TODAY']などはstring!
                    if ($key1 !== 'TODAY' && $key1 !== 'ATUKAI_BUSYO' && $key1 !== 'BUSYO_NM' && $key1 !== 'ATUKAI_SYAIN' && $key1 !== 'SYAINMEI') {
                        $SIN_BUSYO[$key1] += $val1;
                    } else {
                        if ($key1 == 'ATUKAI_SYAIN' || $key1 == 'SYAINMEI') {
                            $SIN_BUSYO[$key1] = 0;
                        }
                    }
                    //$SIN_BUSYO[$key1] += $val1;
                    //20240530 lujunxia upd e
                }
                $SIN_BUSYO['TODAY'] = $SIN_SYAIN['TODAY'];
                $SIN_BUSYO['ATUKAI_BUSYO'] = $SIN_SYAIN['ATUKAI_BUSYO'];
                $SIN_BUSYO['BUSYO_NM'] = $SIN_SYAIN['BUSYO_NM'];
                $SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO'] = $SIN_SYAIN['SIN_TOUKI_DAISU_BUSYO'];
                $SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_BUSYO'] = $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_BUSYO'];
                $this->GroupFooter5_BeforePrint($SIN_SYAIN);
                $SIN_SYAIN['flag'] = "GroupFooter5";
                //visible字段   0:不表示；1:表示
                if ($this->GroupFooter5_Format($SIN_SYAIN)) {
                    $SIN_SYAIN['visible'] = 1;
                } else {
                    $SIN_SYAIN['visible'] = 0;
                }
                array_push($NEWARRAY, $SIN_SYAIN);
                //reset the value
                $SIN_SYAIN = $SIN_SYAIN1;

                //中古車
                foreach ($CHU_SYAIN as $key2 => $val2) {
                    //20240530 lujunxia upd s
                    //$value['TODAY']などはstring!
                    if ($key2 !== 'TODAY' && $key2 !== 'ATUKAI_BUSYO' && $key2 !== 'BUSYO_NM' && $key2 !== 'ATUKAI_SYAIN' && $key2 !== 'SYAINMEI') {
                        $CHU_BUSYO[$key2] += $val2;
                    } else {
                        if ($key2 == 'ATUKAI_SYAIN' || $key2 == 'SYAINMEI') {
                            $CHU_BUSYO[$key2] = 0;
                        }
                    }
                    //$CHU_BUSYO[$key2] += $val2;;
                    //20240530 lujunxia upd e
                }
                $CHU_BUSYO['TODAY'] = $CHU_SYAIN['TODAY'];
                $CHU_BUSYO['ATUKAI_BUSYO'] = $CHU_SYAIN['ATUKAI_BUSYO'];
                $CHU_BUSYO['BUSYO_NM'] = $CHU_SYAIN['BUSYO_NM'];
                $CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO'] = $CHU_SYAIN['CHU_TOUKI_DAISU_BUSYO'];
                $CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_BUSYO'] = $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_BUSYO'];
                $this->GroupFooter4_BeforePrint($CHU_SYAIN);
                $CHU_SYAIN['flag'] = "GroupFooter4";
                if ($this->GroupFooter4_Format($CHU_SYAIN)) {
                    $CHU_SYAIN['visible'] = 1;
                } else {
                    $CHU_SYAIN['visible'] = 0;
                }
                array_push($NEWARRAY, $CHU_SYAIN);
                //reset the value
                $CHU_SYAIN = $CHU_SYAIN1;

                //他ﾁｬﾝﾈﾙ
                foreach ($TA_SYAIN as $key3 => $val3) {
                    //20240530 lujunxia upd s
                    //$value['TODAY']などはstring!
                    if ($key3 !== 'TODAY' && $key3 !== 'ATUKAI_BUSYO' && $key3 !== 'BUSYO_NM' && $key3 !== 'ATUKAI_SYAIN' && $key3 !== 'SYAINMEI') {
                        $TA_BUSYO[$key3] += $val3;
                    } else {
                        //旧システムと同じのため
                        if ($key3 == 'ATUKAI_SYAIN' || $key3 == 'SYAINMEI') {
                            $TA_BUSYO[$key3] = 0;
                        }
                    }
                    //$TA_BUSYO[$key3] += $val3;
                    //20240530 lujunxia upd e
                }
                $TA_BUSYO['TODAY'] = $TA_SYAIN['TODAY'];
                $TA_BUSYO['ATUKAI_BUSYO'] = $TA_SYAIN['ATUKAI_BUSYO'];
                $TA_BUSYO['BUSYO_NM'] = $TA_SYAIN['BUSYO_NM'];
                $TA_BUSYO['TA_TOUKI_DAISU_BUSYO'] = $TA_SYAIN['TA_TOUKI_DAISU_BUSYO'];
                $TA_BUSYO['TA_TOUKI_GENKAIRIEKI_BUSYO'] = $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_BUSYO'];
                $this->GroupFooter2_BeforePrint($TA_SYAIN);
                $TA_SYAIN['flag'] = "GroupFooter2";
                if ($this->GroupFooter2_Format($TA_SYAIN)) {
                    $TA_SYAIN['visible'] = 1;
                } else {
                    $TA_SYAIN['visible'] = 0;
                }
                array_push($NEWARRAY, $TA_SYAIN);
                //reset the value
                $TA_SYAIN = $TA_SYAIN1;

                //新車
                $this->GroupFooter6_BeforePrint($SIN_BUSYO);
                $SIN_BUSYO['flag'] = "GroupFooter6";
                if ($this->GroupFooter6_Format($SIN_BUSYO)) {
                    $SIN_BUSYO['visible'] = 1;
                } else {
                    $SIN_BUSYO['visible'] = 0;
                }
                array_push($NEWARRAY, $SIN_BUSYO);
                //reset the value
                $SIN_BUSYO = $SIN_BUSYO1;

                //中古車
                $this->GroupFooter7_BeforePrint($CHU_BUSYO);
                $CHU_BUSYO['flag'] = "GroupFooter7";
                if ($this->GroupFooter7_Format($CHU_BUSYO)) {
                    $CHU_BUSYO['visible'] = 1;
                } else {
                    $CHU_BUSYO['visible'] = 0;
                }
                array_push($NEWARRAY, $CHU_BUSYO);
                //reset the value
                $CHU_BUSYO = $CHU_BUSYO1;

                //他ﾁｬﾝﾈﾙ
                $this->GroupFooter1_BeforePrint($TA_BUSYO);
                $TA_BUSYO['flag'] = "GroupFooter1";
                if ($this->GroupFooter1_Format($TA_BUSYO)) {
                    $TA_BUSYO['visible'] = 1;
                } else {
                    $TA_BUSYO['visible'] = 0;
                }
                array_push($NEWARRAY, $TA_BUSYO);
                //reset the value
                $TA_BUSYO = $TA_BUSYO1;

                //新車
                $this->GroupFooter8_BeforePrint($SIN_TOTAL);
                $SIN_TOTAL['flag'] = "GroupFooter8";
                if ($this->GroupFooter8_Format($SIN_TOTAL)) {
                    $SIN_TOTAL['visible'] = 1;
                } else {
                    $SIN_TOTAL['visible'] = 0;
                }
                array_push($NEWARRAY, $SIN_TOTAL);
                //中古車
                $this->GroupFooter9_BeforePrint($CHU_TOTAL);
                $CHU_TOTAL['flag'] = "GroupFooter9";
                if ($this->GroupFooter9_Format($CHU_TOTAL)) {
                    $CHU_TOTAL['visible'] = 1;
                } else {
                    $CHU_TOTAL['visible'] = 0;
                }
                array_push($NEWARRAY, $CHU_TOTAL);
                //他ﾁｬﾝﾈﾙ
                $this->GroupFooter3_BeforePrint($TA_TOTAL);
                $TA_TOTAL['flag'] = "GroupFooter3";
                if ($this->GroupFooter3_Format($TA_TOTAL)) {
                    $TA_TOTAL['visible'] = 1;
                } else {
                    $TA_TOTAL['visible'] = 0;
                }
                array_push($NEWARRAY, $TA_TOTAL);
                // print_r($NEWARRAY);
                // return;
                //***********
                array_push($tmp_data, $NEWARRAY);
                $tmp["data"] = $tmp_data;
                $tmp["mode"] = "10";
                $datas["rptGenriList"] = $tmp;
                $rpx_file_names["rptGenriList"] = $data;
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                $pdfPath = $obj->to_pdf2();
                $result['pdfmark'] = TRUE;
                $result['pdfpath'] = $pdfPath;
            }
            //ログ管理
            $lngOutCnt = count($result['data']);
            $intState = 1;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //ログ管理 Start
        if ($intState != 0) {
            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmGENRILIST", $intState, $lngOutCnt, $cboYM);
        }
        //ログ管理 End
        $this->fncReturn($result);
    }

    public function ToHalfAdjust($dValue, $iDigits)
    {
        $dCoef = pow(10, $iDigits);

        if ($dValue > 0) {
            return floor($dValue * $dCoef + 0.5) / $dCoef;
        } else {
            return ceil($dValue * $dCoef - 0.5) / $dCoef;
        }
    }

    public function FncValueCnv($objText)
    {
        if ($objText == null) {
            $objText = "";
        }
        if (rtrim($objText) == "" || rtrim($objText) == "0") {
            //---NULLの場合---
            return "";
        } else {
            //---以外の場合
            return (string) $this->ToHalfAdjust((double) ((int) ($this->ClsComFnc->FncNz(rtrim($objText))) / 1000), 0);
        }

    }

    public function Detail_BeforePrint(&$data)
    {
        $data['SIN_URIAGE'] = $this->FncValueCnv($data['SIN_URIAGE']);
        $data['CHU_URIAGE'] = $this->FncValueCnv($data['CHU_URIAGE']);
        $data['TA_URIAGE'] = $this->FncValueCnv($data['TA_URIAGE']);

        $data['SIN_SYARYOU_RIEKI'] = $this->FncValueCnv($data['SIN_SYARYOU_RIEKI']);
        $data['CHU_SYARYOU_RIEKI'] = $this->FncValueCnv($data['CHU_SYARYOU_RIEKI']);
        $data['TA_SYARYOU_RIEKI'] = $this->FncValueCnv($data['TA_SYARYOU_RIEKI']);

        $data['SIN_KASOU_RIEKI'] = $this->FncValueCnv($data['SIN_KASOU_RIEKI']);
        $data['CHU_KASOU_RIEKI'] = $this->FncValueCnv($data['CHU_KASOU_RIEKI']);
        $data['TA_KASOU_RIEKI'] = $this->FncValueCnv($data['TA_KASOU_RIEKI']);

        $data['SIN_KAPPU_RIEKI'] = $this->FncValueCnv($data['SIN_KAPPU_RIEKI']);
        $data['CHU_KAPPU_RIEKI'] = $this->FncValueCnv($data['CHU_KAPPU_RIEKI']);
        $data['TA_KAPPU_RIEKI'] = $this->FncValueCnv($data['TA_KAPPU_RIEKI']);

        $data['SIN_TOUROKU_RIEKI'] = $this->FncValueCnv($data['SIN_TOUROKU_RIEKI']);
        $data['CHU_TOUROKU_RIEKI'] = $this->FncValueCnv($data['CHU_TOUROKU_RIEKI']);
        $data['TA_TOUROKU_RIEKI'] = $this->FncValueCnv($data['TA_TOUROKU_RIEKI']);

        $data['SIN_UCHIKOMIKIN'] = $this->FncValueCnv($data['SIN_UCHIKOMIKIN']);
        $data['CHU_UCHIKOMIKIN'] = $this->FncValueCnv($data['CHU_UCHIKOMIKIN']);
        $data['TA_UCHIKOMIKIN'] = $this->FncValueCnv($data['TA_UCHIKOMIKIN']);

        $data['SIN_URI_GENKA'] = $this->FncValueCnv($data['SIN_URI_GENKA']);
        $data['CHU_URI_GENKA'] = $this->FncValueCnv($data['CHU_URI_GENKA']);
        $data['TA_URI_GENKA'] = $this->FncValueCnv($data['TA_URI_GENKA']);

        $data['SIN_SITADORI_SON'] = $this->FncValueCnv($data['SIN_SITADORI_SON']);
        $data['CHU_SITADORI_SON'] = $this->FncValueCnv($data['CHU_SITADORI_SON']);
        $data['TA_SITADORI_SON'] = $this->FncValueCnv($data['TA_SITADORI_SON']);

        $data['SIN_HANBAITESURYO'] = $this->FncValueCnv($data['SIN_HANBAITESURYO']);
        $data['CHU_HANBAITESURYO'] = $this->FncValueCnv($data['CHU_HANBAITESURYO']);
        $data['TA_HANBAITESURYO'] = $this->FncValueCnv($data['TA_HANBAITESURYO']);

        $data['SIN_SYOUKAIRYO'] = $this->FncValueCnv($data['SIN_SYOUKAIRYO']);
        $data['CHU_SYOUKAIRYO'] = $this->FncValueCnv($data['CHU_SYOUKAIRYO']);
        $data['TA_SYOUKAIRYO'] = $this->FncValueCnv($data['TA_SYOUKAIRYO']);

        $data['SIN_CHUKOSYA_GENRI'] = $this->FncValueCnv($data['SIN_CHUKOSYA_GENRI']);
        $data['CHU_CHUKOSYA_GENRI'] = $this->FncValueCnv($data['CHU_CHUKOSYA_GENRI']);
        $data['TA_CHUKOSYA_GENRI'] = $this->FncValueCnv($data['TA_CHUKOSYA_GENRI']);

        $data['SIN_TOUGETU_GENRI'] = $this->FncValueCnv($data['SIN_TOUGETU_GENRI']);
        $data['CHU_TOUGETU_GENRI'] = $this->FncValueCnv($data['CHU_TOUGETU_GENRI']);
        $data['TA_TOUGETU_GENRI'] = $this->FncValueCnv($data['TA_TOUGETU_GENRI']);

    }

    public function GroupFooter5_BeforePrint(&$SIN_SYAIN)
    {

        //----新車-----
        if ($this->ClsComFnc->FncNz(rtrim($SIN_SYAIN['SIN_DAISU'])) == 0) {
            //---20160128 li UPD S.
            // $SIN_SYAIN['SinUriageDaiatari'] = $SIN_SYAIN['SIN_URIAGE'];
            $SIN_SYAIN['SinuriageDaiAtari'] = $SIN_SYAIN['SIN_URIAGE'];
            //---20160128 li UPD E.
            $SIN_SYAIN['SinSyaryouRiekiDai'] = $SIN_SYAIN['SIN_SYARYOU_RIEKI'];
            $SIN_SYAIN['SinKasouRiekiDai'] = $SIN_SYAIN['SIN_KASOU_RIEKI'];
            $SIN_SYAIN['SinKappuRiekiDai'] = $SIN_SYAIN['SIN_KAPPU_RIEKI'];
            $SIN_SYAIN['SinTourokuRiekiDai'] = $SIN_SYAIN['SIN_TOUROKU_RIEKI'];
            $SIN_SYAIN['SinUchikomikinDai'] = $SIN_SYAIN['SIN_UCHIKOMIKIN'];
            $SIN_SYAIN['SinUriGenkaDai'] = $SIN_SYAIN['SIN_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SIT_DAISU']) != 0) {
                $SIN_SYAIN['SinSitadoriSonDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SIT_DAISU']));
            } else {
                $SIN_SYAIN['SinSitadoriSonDai'] = "";
            }
            $SIN_SYAIN['SinHanbaitesDai'] = $SIN_SYAIN['SIN_HANBAITESURYO'];
            $SIN_SYAIN['SinSyoukairyoDai'] = $SIN_SYAIN['SIN_SYOUKAIRYO'];
            $SIN_SYAIN['SinChukosyaGenDai'] = $SIN_SYAIN['SIN_CHUKOSYA_GENRI'];
            $SIN_SYAIN['SinTougetuGenDai'] = $SIN_SYAIN['SIN_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_DAISU']) != 0) {
                $SIN_SYAIN['SinToukiGenDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_DAISU']));
            } else {
                $SIN_SYAIN['SinToukiGenDai'] = "";
            }
        } else {
            //---20160128 li UPD S.
            // $SIN_SYAIN['SinUriageDaiatari'] = (int)($this -> ClsComFnc -> FncNz($SIN_SYAIN['SIN_URIAGE']) / $this -> ClsComFnc -> FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SinuriageDaiAtari'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_URIAGE']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            //---20160128 li UPD E.
            $SIN_SYAIN['SinSyaryouRiekiDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SinKasouRiekiDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SinKappuRiekiDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SinTourokuRiekiDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SinUchikomikinDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SinUriGenkaDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_URI_GENKA']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            if ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SIT_DAISU']) != 0) {
                $SIN_SYAIN['SinSitadoriSonDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SIT_DAISU']));
            } else {
                $SIN_SYAIN['SinSitadoriSonDai'] = "";
            }
            $SIN_SYAIN['SinHanbaitesDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_HANBAITESURYO']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SinSyoukairyoDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SinChukosyaGenDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SinTougetuGenDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            if ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_DAISU']) != 0) {
                $SIN_SYAIN['SinToukiGenDai'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_DAISU']));
            } else {
                $SIN_SYAIN['SinToukiGenDai'] = "";
            }
        }

        $SIN_SYAIN['SIN_URIAGE'] = $this->FncValueCnv($SIN_SYAIN['SIN_URIAGE']);
        $SIN_SYAIN['SIN_SYARYOU_RIEKI'] = $this->FncValueCnv($SIN_SYAIN['SIN_SYARYOU_RIEKI']);
        $SIN_SYAIN['SIN_KASOU_RIEKI'] = $this->FncValueCnv($SIN_SYAIN['SIN_KASOU_RIEKI']);
        $SIN_SYAIN['SIN_KAPPU_RIEKI'] = $this->FncValueCnv($SIN_SYAIN['SIN_KAPPU_RIEKI']);
        $SIN_SYAIN['SIN_TOUROKU_RIEKI'] = $this->FncValueCnv($SIN_SYAIN['SIN_TOUROKU_RIEKI']);
        $SIN_SYAIN['SIN_UCHIKOMIKIN'] = $this->FncValueCnv($SIN_SYAIN['SIN_UCHIKOMIKIN']);
        $SIN_SYAIN['SIN_URI_GENKA'] = $this->FncValueCnv($SIN_SYAIN['SIN_URI_GENKA']);
        $SIN_SYAIN['SIN_SITADORI_SON'] = $this->FncValueCnv($SIN_SYAIN['SIN_SITADORI_SON']);
        $SIN_SYAIN['SIN_HANBAITESURYO'] = $this->FncValueCnv($SIN_SYAIN['SIN_HANBAITESURYO']);
        $SIN_SYAIN['SIN_SYOUKAIRYO'] = $this->FncValueCnv($SIN_SYAIN['SIN_SYOUKAIRYO']);
        $SIN_SYAIN['SIN_CHUKOSYA_GENRI'] = $this->FncValueCnv($SIN_SYAIN['SIN_CHUKOSYA_GENRI']);
        $SIN_SYAIN['SIN_TOUGETU_GENRI'] = $this->FncValueCnv($SIN_SYAIN['SIN_TOUGETU_GENRI']);
        $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI'] = $this->FncValueCnv($SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI']);

        //---20160128 li UPD S.
        // $SIN_SYAIN['SinUriageDaiatari'] = $this -> FncValueCnv($SIN_SYAIN['SinUriageDaiatari']);
        $SIN_SYAIN['SinuriageDaiAtari'] = $this->FncValueCnv($SIN_SYAIN['SinuriageDaiAtari']);
        //---20160128 li UPD E.
        $SIN_SYAIN['SinSyaryouRiekiDai'] = $this->FncValueCnv($SIN_SYAIN['SinSyaryouRiekiDai']);
        $SIN_SYAIN['SinKasouRiekiDai'] = $this->FncValueCnv($SIN_SYAIN['SinKasouRiekiDai']);
        $SIN_SYAIN['SinKappuRiekiDai'] = $this->FncValueCnv($SIN_SYAIN['SinKappuRiekiDai']);
        $SIN_SYAIN['SinTourokuRiekiDai'] = $this->FncValueCnv($SIN_SYAIN['SinTourokuRiekiDai']);
        $SIN_SYAIN['SinUchikomikinDai'] = $this->FncValueCnv($SIN_SYAIN['SinUchikomikinDai']);
        $SIN_SYAIN['SinUriGenkaDai'] = $this->FncValueCnv($SIN_SYAIN['SinUriGenkaDai']);
        $SIN_SYAIN['SinSitadoriSonDai'] = $this->FncValueCnv($SIN_SYAIN['SinSitadoriSonDai']);
        $SIN_SYAIN['SinHanbaitesDai'] = $this->FncValueCnv($SIN_SYAIN['SinHanbaitesDai']);
        $SIN_SYAIN['SinSyoukairyoDai'] = $this->FncValueCnv($SIN_SYAIN['SinSyoukairyoDai']);
        $SIN_SYAIN['SinChukosyaGenDai'] = $this->FncValueCnv($SIN_SYAIN['SinChukosyaGenDai']);
        $SIN_SYAIN['SinTougetuGenDai'] = $this->FncValueCnv($SIN_SYAIN['SinTougetuGenDai']);
        $SIN_SYAIN['SinToukiGenDai'] = $this->FncValueCnv($SIN_SYAIN['SinToukiGenDai']);
    }

    public function GroupFooter5_Format($SIN_SYAIN)
    {
        //----新車-----
        if ($this->ClsComFnc->FncNz(rtrim($SIN_SYAIN['SIN_DAISU'])) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_URIAGE']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SYARYOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_KASOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_KAPPU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUROKU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_UCHIKOMIKIN']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_URI_GENKA']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SITADORI_SON']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_HANBAITESURYO']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SYOUKAIRYO']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_CHUKOSYA_GENRI']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUGETU_GENRI']) == 0 && $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI']) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function GroupFooter4_BeforePrint(&$CHU_SYAIN)
    {
        //------中古車------
        if ($this->ClsComFnc->FncNz(rtrim($CHU_SYAIN['CHU_DAISU'])) == 0) {

            $CHU_SYAIN['ChuUriageDaiatari'] = $CHU_SYAIN['CHU_URIAGE'];
            $CHU_SYAIN['ChuSyaryouRiekiDai'] = $CHU_SYAIN['CHU_SYARYOU_RIEKI'];
            $CHU_SYAIN['ChuKasouRiekiDai'] = $CHU_SYAIN['CHU_KASOU_RIEKI'];
            $CHU_SYAIN['ChuKappuRiekiDai'] = $CHU_SYAIN['CHU_KAPPU_RIEKI'];
            $CHU_SYAIN['ChuTourokuRiekiDai'] = $CHU_SYAIN['CHU_TOUROKU_RIEKI'];
            $CHU_SYAIN['ChuUchikomikinDai'] = $CHU_SYAIN['CHU_UCHIKOMIKIN'];
            $CHU_SYAIN['ChuUriGenkaDai'] = $CHU_SYAIN['CHU_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SIT_DAISU']) != 0) {
                $CHU_SYAIN['ChuSitadoriSonDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SIT_DAISU']));
            } else {
                $CHU_SYAIN['ChuSitadoriSonDai'] = "";
            }
            $CHU_SYAIN['ChuHanbaitesDai'] = $CHU_SYAIN['CHU_HANBAITESURYO'];
            $CHU_SYAIN['ChuSyoukairyoDai'] = $CHU_SYAIN['CHU_SYOUKAIRYO'];
            $CHU_SYAIN['ChuChukosyaGenDai'] = $CHU_SYAIN['CHU_CHUKOSYA_GENRI'];
            $CHU_SYAIN['ChuTougetuGenDai'] = $CHU_SYAIN['CHU_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_DAISU']) != 0) {
                $CHU_SYAIN['ChuToukiGenDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_DAISU']));
            } else {
                $CHU_SYAIN['ChuToukiGenDai'] = "";
            }
        } else {
            $CHU_SYAIN['ChuUriageDaiatari'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_URIAGE']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['ChuSyaryouRiekiDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['ChuKasouRiekiDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['ChuKappuRiekiDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['ChuTourokuRiekiDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['ChuUchikomikinDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['ChuUriGenkaDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_URI_GENKA']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            if ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SIT_DAISU']) != 0) {
                $CHU_SYAIN['ChuSitadoriSonDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SIT_DAISU']));
            } else {
                $CHU_SYAIN['ChuSitadoriSonDai'] = "";
            }
            $CHU_SYAIN['ChuHanbaitesDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_HANBAITESURYO']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['ChuSyoukairyoDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['ChuChukosyaGenDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['ChuTougetuGenDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            if ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_DAISU']) != 0) {
                $CHU_SYAIN['ChuToukiGenDai'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_DAISU']));
            } else {
                $CHU_SYAIN['ChuToukiGenDai'] = "";
            }
        }

        $CHU_SYAIN['CHU_URIAGE'] = $this->FncValueCnv($CHU_SYAIN['CHU_URIAGE']);
        $CHU_SYAIN['CHU_SYARYOU_RIEKI'] = $this->FncValueCnv($CHU_SYAIN['CHU_SYARYOU_RIEKI']);
        $CHU_SYAIN['CHU_KASOU_RIEKI'] = $this->FncValueCnv($CHU_SYAIN['CHU_KASOU_RIEKI']);
        $CHU_SYAIN['CHU_KAPPU_RIEKI'] = $this->FncValueCnv($CHU_SYAIN['CHU_KAPPU_RIEKI']);
        $CHU_SYAIN['CHU_TOUROKU_RIEKI'] = $this->FncValueCnv($CHU_SYAIN['CHU_TOUROKU_RIEKI']);
        $CHU_SYAIN['CHU_UCHIKOMIKIN'] = $this->FncValueCnv($CHU_SYAIN['CHU_UCHIKOMIKIN']);
        $CHU_SYAIN['CHU_URI_GENKA'] = $this->FncValueCnv($CHU_SYAIN['CHU_URI_GENKA']);
        $CHU_SYAIN['CHU_SITADORI_SON'] = $this->FncValueCnv($CHU_SYAIN['CHU_SITADORI_SON']);
        $CHU_SYAIN['CHU_HANBAITESURYO'] = $this->FncValueCnv($CHU_SYAIN['CHU_HANBAITESURYO']);
        $CHU_SYAIN['CHU_SYOUKAIRYO'] = $this->FncValueCnv($CHU_SYAIN['CHU_SYOUKAIRYO']);
        $CHU_SYAIN['CHU_CHUKOSYA_GENRI'] = $this->FncValueCnv($CHU_SYAIN['CHU_CHUKOSYA_GENRI']);
        $CHU_SYAIN['CHU_TOUGETU_GENRI'] = $this->FncValueCnv($CHU_SYAIN['CHU_TOUGETU_GENRI']);
        $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI'] = $this->FncValueCnv($CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI']);

        $CHU_SYAIN['ChuUriageDaiatari'] = $this->FncValueCnv($CHU_SYAIN['ChuUriageDaiatari']);
        $CHU_SYAIN['ChuSyaryouRiekiDai'] = $this->FncValueCnv($CHU_SYAIN['ChuSyaryouRiekiDai']);
        $CHU_SYAIN['ChuKasouRiekiDai'] = $this->FncValueCnv($CHU_SYAIN['ChuKasouRiekiDai']);
        $CHU_SYAIN['ChuKappuRiekiDai'] = $this->FncValueCnv($CHU_SYAIN['ChuKappuRiekiDai']);
        $CHU_SYAIN['ChuTourokuRiekiDai'] = $this->FncValueCnv($CHU_SYAIN['ChuTourokuRiekiDai']);
        $CHU_SYAIN['ChuUchikomikinDai'] = $this->FncValueCnv($CHU_SYAIN['ChuUchikomikinDai']);
        $CHU_SYAIN['ChuUriGenkaDai'] = $this->FncValueCnv($CHU_SYAIN['ChuUriGenkaDai']);
        $CHU_SYAIN['ChuSitadoriSonDai'] = $this->FncValueCnv($CHU_SYAIN['ChuSitadoriSonDai']);
        $CHU_SYAIN['ChuHanbaitesDai'] = $this->FncValueCnv($CHU_SYAIN['ChuHanbaitesDai']);
        $CHU_SYAIN['ChuSyoukairyoDai'] = $this->FncValueCnv($CHU_SYAIN['ChuSyoukairyoDai']);
        $CHU_SYAIN['ChuChukosyaGenDai'] = $this->FncValueCnv($CHU_SYAIN['ChuChukosyaGenDai']);
        $CHU_SYAIN['ChuTougetuGenDai'] = $this->FncValueCnv($CHU_SYAIN['ChuTougetuGenDai']);
        $CHU_SYAIN['ChuToukiGenDai'] = $this->FncValueCnv($CHU_SYAIN['ChuToukiGenDai']);
    }

    public function GroupFooter4_Format($CHU_SYAIN)
    {
        //----中古車-----
        if ($this->ClsComFnc->FncNz(rtrim($CHU_SYAIN['CHU_DAISU'])) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_URIAGE']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SYARYOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_KASOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_KAPPU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUROKU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_UCHIKOMIKIN']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_URI_GENKA']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SITADORI_SON']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_HANBAITESURYO']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SYOUKAIRYO']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_CHUKOSYA_GENRI']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUGETU_GENRI']) == 0 && $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI']) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function GroupFooter2_BeforePrint(&$TA_SYAIN)
    {
        //----他ﾁｬﾝﾈﾙ-----
        if ($this->ClsComFnc->FncNz(rtrim($TA_SYAIN['TA_DAISU'])) == 0) {

            $TA_SYAIN['TaUriageDaiatari'] = $TA_SYAIN['TA_URIAGE'];
            $TA_SYAIN['TaSyaryouRiekiDai'] = $TA_SYAIN['TA_SYARYOU_RIEKI'];
            $TA_SYAIN['TaKasouRiekiDai'] = $TA_SYAIN['TA_KASOU_RIEKI'];
            $TA_SYAIN['TaKappuRiekiDai'] = $TA_SYAIN['TA_KAPPU_RIEKI'];
            $TA_SYAIN['TaTourokuRiekiDai'] = $TA_SYAIN['TA_TOUROKU_RIEKI'];
            $TA_SYAIN['TaUchikomikinDai'] = $TA_SYAIN['TA_UCHIKOMIKIN'];
            $TA_SYAIN['TaUriGenkaDai'] = $TA_SYAIN['TA_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SIT_DAISU']) != 0) {
                $TA_SYAIN['TaSitadoriSonDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_SIT_DAISU']));
            } else {
                $TA_SYAIN['TaSitadoriSonDai'] = "";
            }
            $TA_SYAIN['TaHanbaitesDai'] = $TA_SYAIN['TA_HANBAITESURYO'];
            $TA_SYAIN['TaSyoukairyoDai'] = $TA_SYAIN['TA_SYOUKAIRYO'];
            $TA_SYAIN['TaChukosyaGenDai'] = $TA_SYAIN['TA_CHUKOSYA_GENRI'];
            $TA_SYAIN['TaTougetuGenDai'] = $TA_SYAIN['TA_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_DAISU']) != 0) {
                $TA_SYAIN['TaToukiGenDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_DAISU']));
            } else {
                $TA_SYAIN['TaToukiGenDai'] = "";
            }
        } else {
            $TA_SYAIN['TaUriageDaiatari'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_URIAGE']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TaSyaryouRiekiDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TaKasouRiekiDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TaKappuRiekiDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TaTourokuRiekiDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TaUchikomikinDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TaUriGenkaDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_URI_GENKA']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            if ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SIT_DAISU']) != 0) {
                $TA_SYAIN['TaSitadoriSonDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_SIT_DAISU']));
            } else {
                $TA_SYAIN['TaSitadoriSonDai'] = "";
            }
            $TA_SYAIN['TaHanbaitesDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_HANBAITESURYO']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TaSyoukairyoDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TaChukosyaGenDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TaTougetuGenDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            if ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_DAISU']) != 0) {
                $TA_SYAIN['TaToukiGenDai'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_DAISU']));
            } else {
                $TA_SYAIN['TaToukiGenDai'] = "";
            }
        }

        $TA_SYAIN['TA_URIAGE'] = $this->FncValueCnv($TA_SYAIN['TA_URIAGE']);
        $TA_SYAIN['TA_SYARYOU_RIEKI'] = $this->FncValueCnv($TA_SYAIN['TA_SYARYOU_RIEKI']);
        $TA_SYAIN['TA_KASOU_RIEKI'] = $this->FncValueCnv($TA_SYAIN['TA_KASOU_RIEKI']);
        $TA_SYAIN['TA_KAPPU_RIEKI'] = $this->FncValueCnv($TA_SYAIN['TA_KAPPU_RIEKI']);
        $TA_SYAIN['TA_TOUROKU_RIEKI'] = $this->FncValueCnv($TA_SYAIN['TA_TOUROKU_RIEKI']);
        $TA_SYAIN['TA_UCHIKOMIKIN'] = $this->FncValueCnv($TA_SYAIN['TA_UCHIKOMIKIN']);
        $TA_SYAIN['TA_URI_GENKA'] = $this->FncValueCnv($TA_SYAIN['TA_URI_GENKA']);
        $TA_SYAIN['TA_SITADORI_SON'] = $this->FncValueCnv($TA_SYAIN['TA_SITADORI_SON']);
        $TA_SYAIN['TA_HANBAITESURYO'] = $this->FncValueCnv($TA_SYAIN['TA_HANBAITESURYO']);
        $TA_SYAIN['TA_SYOUKAIRYO'] = $this->FncValueCnv($TA_SYAIN['TA_SYOUKAIRYO']);
        $TA_SYAIN['TA_CHUKOSYA_GENRI'] = $this->FncValueCnv($TA_SYAIN['TA_CHUKOSYA_GENRI']);
        $TA_SYAIN['TA_TOUGETU_GENRI'] = $this->FncValueCnv($TA_SYAIN['TA_TOUGETU_GENRI']);
        $TA_SYAIN['TA_TOUKI_GENKAIRIEKI'] = $this->FncValueCnv($TA_SYAIN['TA_TOUKI_GENKAIRIEKI']);

        $TA_SYAIN['TaUriageDaiatari'] = $this->FncValueCnv($TA_SYAIN['TaUriageDaiatari']);
        $TA_SYAIN['TaSyaryouRiekiDai'] = $this->FncValueCnv($TA_SYAIN['TaSyaryouRiekiDai']);
        $TA_SYAIN['TaKasouRiekiDai'] = $this->FncValueCnv($TA_SYAIN['TaKasouRiekiDai']);
        $TA_SYAIN['TaKappuRiekiDai'] = $this->FncValueCnv($TA_SYAIN['TaKappuRiekiDai']);
        $TA_SYAIN['TaTourokuRiekiDai'] = $this->FncValueCnv($TA_SYAIN['TaTourokuRiekiDai']);
        $TA_SYAIN['TaUchikomikinDai'] = $this->FncValueCnv($TA_SYAIN['TaUchikomikinDai']);
        $TA_SYAIN['TaUriGenkaDai'] = $this->FncValueCnv($TA_SYAIN['TaUriGenkaDai']);
        $TA_SYAIN['TaSitadoriSonDai'] = $this->FncValueCnv($TA_SYAIN['TaSitadoriSonDai']);
        $TA_SYAIN['TaHanbaitesDai'] = $this->FncValueCnv($TA_SYAIN['TaHanbaitesDai']);
        $TA_SYAIN['TaSyoukairyoDai'] = $this->FncValueCnv($TA_SYAIN['TaSyoukairyoDai']);
        $TA_SYAIN['TaChukosyaGenDai'] = $this->FncValueCnv($TA_SYAIN['TaChukosyaGenDai']);
        $TA_SYAIN['TaTougetuGenDai'] = $this->FncValueCnv($TA_SYAIN['TaTougetuGenDai']);
        $TA_SYAIN['TaToukiGenDai'] = $this->FncValueCnv($TA_SYAIN['TaToukiGenDai']);
    }

    public function GroupFooter2_Format($TA_SYAIN)
    {
        //----他ﾁｬﾝﾈﾙ-----
        if ($this->ClsComFnc->FncNz(rtrim($TA_SYAIN['TA_DAISU'])) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_URIAGE']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_SYARYOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_KASOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_KAPPU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUROKU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_UCHIKOMIKIN']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_URI_GENKA']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_SITADORI_SON']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_HANBAITESURYO']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_SYOUKAIRYO']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_CHUKOSYA_GENRI']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUGETU_GENRI']) == 0 && $this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_GENKAIRIEKI']) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function GroupFooter6_BeforePrint(&$SIN_BUSYO)
    {
        //----売上-----
        if ($this->ClsComFnc->FncNz(rtrim($SIN_BUSYO['SIN_DAISU'])) == 0) {
            $SIN_BUSYO['SinUriageDaiBusyo'] = $SIN_BUSYO['SIN_URIAGE'];
            $SIN_BUSYO['SinSyaryouRieBusDai'] = $SIN_BUSYO['SIN_SYARYOU_RIEKI'];
            $SIN_BUSYO['SinKasouRieBusDai'] = $SIN_BUSYO['SIN_KASOU_RIEKI'];
            $SIN_BUSYO['SinKappuRieBusDai'] = $SIN_BUSYO['SIN_KAPPU_RIEKI'];
            $SIN_BUSYO['SinTourokuRieBusDai'] = $SIN_BUSYO['SIN_TOUROKU_RIEKI'];
            $SIN_BUSYO['SinUchikomiBusDai'] = $SIN_BUSYO['SIN_UCHIKOMIKIN'];
            $SIN_BUSYO['SinUriGenBusDai'] = $SIN_BUSYO['SIN_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SIT_DAISU']) != 0) {
                $SIN_BUSYO['SinSitadoriBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SIT_DAISU']));
            } else {
                $SIN_BUSYO['SinSitadoriBusDai'] = "";
            }
            $SIN_BUSYO['SinHanbaitesBusDai'] = $SIN_BUSYO['SIN_HANBAITESURYO'];
            $SIN_BUSYO['SinSyoukairyoBusDai'] = $SIN_BUSYO['SIN_SYOUKAIRYO'];
            $SIN_BUSYO['SinChukoGenBusDai'] = $SIN_BUSYO['SIN_CHUKOSYA_GENRI'];
            $SIN_BUSYO['SinTougetuBusDai'] = $SIN_BUSYO['SIN_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO']) != 0) {
                $SIN_BUSYO['SinToukiGenBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO']));
            } else {
                $SIN_BUSYO['SinToukiGenBusDai'] = "";
            }
        } else {
            $SIN_BUSYO['SinUriageDaiBusyo'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_URIAGE']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SinSyaryouRieBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SinKasouRieBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SinKappuRieBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SinTourokuRieBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SinUchikomiBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SinUriGenBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_URI_GENKA']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            if ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SIT_DAISU']) != 0) {
                $SIN_BUSYO['SinSitadoriBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SIT_DAISU']));
            } else {
                $SIN_BUSYO['SinSitadoriBusDai'] = "";
            }
            $SIN_BUSYO['SinHanbaitesBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_HANBAITESURYO']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SinSyoukairyoBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SinChukoGenBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SinTougetuBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));

            if ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO']) != 0) {
                $SIN_BUSYO['SinToukiGenBusDai'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO']));
            } else {
                $SIN_BUSYO['SinToukiGenBusDai'] = "";
            }
        }
        $SIN_BUSYO['SIN_URIAGE'] = $this->FncValueCnv($SIN_BUSYO['SIN_URIAGE']);
        $SIN_BUSYO['SIN_SYARYOU_RIEKI'] = $this->FncValueCnv($SIN_BUSYO['SIN_SYARYOU_RIEKI']);
        $SIN_BUSYO['SIN_KASOU_RIEKI'] = $this->FncValueCnv($SIN_BUSYO['SIN_KASOU_RIEKI']);
        $SIN_BUSYO['SIN_KAPPU_RIEKI'] = $this->FncValueCnv($SIN_BUSYO['SIN_KAPPU_RIEKI']);
        $SIN_BUSYO['SIN_TOUROKU_RIEKI'] = $this->FncValueCnv($SIN_BUSYO['SIN_TOUROKU_RIEKI']);
        $SIN_BUSYO['SIN_UCHIKOMIKIN'] = $this->FncValueCnv($SIN_BUSYO['SIN_UCHIKOMIKIN']);
        $SIN_BUSYO['SIN_URI_GENKA'] = $this->FncValueCnv($SIN_BUSYO['SIN_URI_GENKA']);
        $SIN_BUSYO['SIN_SITADORI_SON'] = $this->FncValueCnv($SIN_BUSYO['SIN_SITADORI_SON']);
        $SIN_BUSYO['SIN_HANBAITESURYO'] = $this->FncValueCnv($SIN_BUSYO['SIN_HANBAITESURYO']);
        $SIN_BUSYO['SIN_SYOUKAIRYO'] = $this->FncValueCnv($SIN_BUSYO['SIN_SYOUKAIRYO']);
        $SIN_BUSYO['SIN_CHUKOSYA_GENRI'] = $this->FncValueCnv($SIN_BUSYO['SIN_CHUKOSYA_GENRI']);
        $SIN_BUSYO['SIN_TOUGETU_GENRI'] = $this->FncValueCnv($SIN_BUSYO['SIN_TOUGETU_GENRI']);
        $SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_BUSYO'] = $this->FncValueCnv($SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_BUSYO']);

        $SIN_BUSYO['SinUriageDaiBusyo'] = $this->FncValueCnv($SIN_BUSYO['SinUriageDaiBusyo']);
        $SIN_BUSYO['SinSyaryouRieBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinSyaryouRieBusDai']);
        $SIN_BUSYO['SinKasouRieBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinKasouRieBusDai']);
        $SIN_BUSYO['SinKappuRieBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinKappuRieBusDai']);
        $SIN_BUSYO['SinTourokuRieBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinTourokuRieBusDai']);
        $SIN_BUSYO['SinUchikomiBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinUchikomiBusDai']);
        $SIN_BUSYO['SinUriGenBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinUriGenBusDai']);
        $SIN_BUSYO['SinSitadoriBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinSitadoriBusDai']);
        $SIN_BUSYO['SinHanbaitesBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinHanbaitesBusDai']);
        $SIN_BUSYO['SinSyoukairyoBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinSyoukairyoBusDai']);
        $SIN_BUSYO['SinChukoGenBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinChukoGenBusDai']);
        $SIN_BUSYO['SinTougetuBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinTougetuBusDai']);
        $SIN_BUSYO['SinToukiGenBusDai'] = $this->FncValueCnv($SIN_BUSYO['SinToukiGenBusDai']);
    }

    public function GroupFooter6_Format($SIN_BUSYO)
    {
        //----売上-----
        if ($this->ClsComFnc->FncNz(rtrim($SIN_BUSYO['SIN_DAISU'])) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_URIAGE']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SYARYOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_KASOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_KAPPU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUROKU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_UCHIKOMIKIN']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_URI_GENKA']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SITADORI_SON']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_HANBAITESURYO']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SYOUKAIRYO']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_CHUKOSYA_GENRI']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUGETU_GENRI']) == 0 && $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI']) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function GroupFooter7_BeforePrint(&$CHU_BUSYO)
    {
        //----中古車-----
        if ($this->ClsComFnc->FncNz(rtrim($CHU_BUSYO['CHU_DAISU'])) == 0) {
            $CHU_BUSYO['ChuUriageDaiBusyo'] = $CHU_BUSYO['CHU_URIAGE'];
            $CHU_BUSYO['ChuSyaryouRieBusDai'] = $CHU_BUSYO['CHU_SYARYOU_RIEKI'];
            $CHU_BUSYO['ChuKasouRieBusDai'] = $CHU_BUSYO['CHU_KASOU_RIEKI'];
            $CHU_BUSYO['ChuKappuRieBusDai'] = $CHU_BUSYO['CHU_KAPPU_RIEKI'];
            $CHU_BUSYO['ChuTourokuRieBusDai'] = $CHU_BUSYO['CHU_TOUROKU_RIEKI'];
            $CHU_BUSYO['ChuUchikomiBusDai'] = $CHU_BUSYO['CHU_UCHIKOMIKIN'];
            $CHU_BUSYO['ChuUriGenBusDai'] = $CHU_BUSYO['CHU_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SIT_DAISU']) != 0) {
                $CHU_BUSYO['ChuSitadoriBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SIT_DAISU']));
            } else {
                $CHU_BUSYO['ChuSitadoriBusDai'] = "";
            }
            $CHU_BUSYO['ChuHanbaitesBusDai'] = $CHU_BUSYO['CHU_HANBAITESURYO'];
            $CHU_BUSYO['ChuSyoukairyoBusDai'] = $CHU_BUSYO['CHU_SYOUKAIRYO'];
            $CHU_BUSYO['ChuChukoGenBusDai'] = $CHU_BUSYO['CHU_CHUKOSYA_GENRI'];
            $CHU_BUSYO['ChuTougetuBusDai'] = $CHU_BUSYO['CHU_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO']) != 0) {
                $CHU_BUSYO['ChuToukiGenBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO']));
            } else {
                $CHU_BUSYO['ChuToukiGenBusDai'] = "";
            }
        } else {
            $CHU_BUSYO['ChuUriageDaiBusyo'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_URIAGE']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['ChuSyaryouRieBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['ChuKasouRieBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['ChuKappuRieBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['ChuTourokuRieBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['ChuUchikomiBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['ChuUriGenBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_URI_GENKA']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            if ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SIT_DAISU']) != 0) {
                $CHU_BUSYO['ChuSitadoriBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SIT_DAISU']));
            } else {
                $CHU_BUSYO['ChuSitadoriBusDai'] = "";
            }
            $CHU_BUSYO['ChuHanbaitesBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_HANBAITESURYO']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['ChuSyoukairyoBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['ChuChukoGenBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['ChuTougetuBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));

            if ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO']) != 0) {
                $CHU_BUSYO['ChuToukiGenBusDai'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO']));
            } else {
                $CHU_BUSYO['ChuToukiGenBusDai'] = "";
            }
        }
        $CHU_BUSYO['CHU_URIAGE'] = $this->FncValueCnv($CHU_BUSYO['CHU_URIAGE']);
        $CHU_BUSYO['CHU_SYARYOU_RIEKI'] = $this->FncValueCnv($CHU_BUSYO['CHU_SYARYOU_RIEKI']);
        $CHU_BUSYO['CHU_KASOU_RIEKI'] = $this->FncValueCnv($CHU_BUSYO['CHU_KASOU_RIEKI']);
        $CHU_BUSYO['CHU_KAPPU_RIEKI'] = $this->FncValueCnv($CHU_BUSYO['CHU_KAPPU_RIEKI']);
        $CHU_BUSYO['CHU_TOUROKU_RIEKI'] = $this->FncValueCnv($CHU_BUSYO['CHU_TOUROKU_RIEKI']);
        $CHU_BUSYO['CHU_UCHIKOMIKIN'] = $this->FncValueCnv($CHU_BUSYO['CHU_UCHIKOMIKIN']);
        $CHU_BUSYO['CHU_URI_GENKA'] = $this->FncValueCnv($CHU_BUSYO['CHU_URI_GENKA']);
        $CHU_BUSYO['CHU_SITADORI_SON'] = $this->FncValueCnv($CHU_BUSYO['CHU_SITADORI_SON']);
        $CHU_BUSYO['CHU_HANBAITESURYO'] = $this->FncValueCnv($CHU_BUSYO['CHU_HANBAITESURYO']);
        $CHU_BUSYO['CHU_SYOUKAIRYO'] = $this->FncValueCnv($CHU_BUSYO['CHU_SYOUKAIRYO']);
        $CHU_BUSYO['CHU_CHUKOSYA_GENRI'] = $this->FncValueCnv($CHU_BUSYO['CHU_CHUKOSYA_GENRI']);
        $CHU_BUSYO['CHU_TOUGETU_GENRI'] = $this->FncValueCnv($CHU_BUSYO['CHU_TOUGETU_GENRI']);
        $CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_BUSYO'] = $this->FncValueCnv($CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_BUSYO']);

        $CHU_BUSYO['ChuUriageDaiBusyo'] = $this->FncValueCnv($CHU_BUSYO['ChuUriageDaiBusyo']);
        $CHU_BUSYO['ChuSyaryouRieBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuSyaryouRieBusDai']);
        $CHU_BUSYO['ChuKasouRieBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuKasouRieBusDai']);
        $CHU_BUSYO['ChuKappuRieBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuKappuRieBusDai']);
        $CHU_BUSYO['ChuTourokuRieBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuTourokuRieBusDai']);
        $CHU_BUSYO['ChuUchikomiBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuUchikomiBusDai']);
        $CHU_BUSYO['ChuUriGenBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuUriGenBusDai']);
        $CHU_BUSYO['ChuSitadoriBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuSitadoriBusDai']);
        $CHU_BUSYO['ChuHanbaitesBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuHanbaitesBusDai']);
        $CHU_BUSYO['ChuSyoukairyoBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuSyoukairyoBusDai']);
        $CHU_BUSYO['ChuChukoGenBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuChukoGenBusDai']);
        $CHU_BUSYO['ChuTougetuBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuTougetuBusDai']);
        $CHU_BUSYO['ChuToukiGenBusDai'] = $this->FncValueCnv($CHU_BUSYO['ChuToukiGenBusDai']);
    }

    public function GroupFooter7_Format($CHU_BUSYO)
    {
        //----中古車-----
        if ($this->ClsComFnc->FncNz(rtrim($CHU_BUSYO['CHU_DAISU'])) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_URIAGE']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SYARYOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_KASOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_KAPPU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUROKU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_UCHIKOMIKIN']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_URI_GENKA']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SITADORI_SON']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_HANBAITESURYO']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SYOUKAIRYO']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_CHUKOSYA_GENRI']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUGETU_GENRI']) == 0 && $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI']) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function GroupFooter1_BeforePrint(&$TA_BUSYO)
    {
        //----他ﾁｬﾝﾈﾙ-----
        if ($this->ClsComFnc->FncNz(rtrim($TA_BUSYO['TA_DAISU'])) == 0) {
            $TA_BUSYO['TaUriageDaiBusyo'] = $TA_BUSYO['TA_URIAGE'];
            $TA_BUSYO['TaSyaryouRieBusDai'] = $TA_BUSYO['TA_SYARYOU_RIEKI'];
            $TA_BUSYO['TaKasouRieBusDai'] = $TA_BUSYO['TA_KASOU_RIEKI'];
            $TA_BUSYO['TaKappuRieBusDai'] = $TA_BUSYO['TA_KAPPU_RIEKI'];
            $TA_BUSYO['TaTourokuRieBusDai'] = $TA_BUSYO['TA_TOUROKU_RIEKI'];
            $TA_BUSYO['TaUchikomiBusDai'] = $TA_BUSYO['TA_UCHIKOMIKIN'];
            $TA_BUSYO['TaUriGenBusDai'] = $TA_BUSYO['TA_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SIT_DAISU']) != 0) {
                $TA_BUSYO['TaSitadoriBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_SIT_DAISU']));
            } else {
                $TA_BUSYO['TaSitadoriBusDai'] = "";
            }
            $TA_BUSYO['TaHanbaitesBusDai'] = $TA_BUSYO['TA_HANBAITESURYO'];
            $TA_BUSYO['TaSyoukairyoBusDai'] = $TA_BUSYO['TA_SYOUKAIRYO'];
            $TA_BUSYO['TaChukoGenBusDai'] = $TA_BUSYO['TA_CHUKOSYA_GENRI'];
            $TA_BUSYO['TaTougetuBusDai'] = $TA_BUSYO['TA_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_DAISU_BUSYO']) != 0) {
                $TA_BUSYO['TaToukiGenBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_DAISU_BUSYO']));
            } else {
                $TA_BUSYO['TaToukiGenBusDai'] = "";
            }
        } else {
            $TA_BUSYO['TaUriageDaiBusyo'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_URIAGE']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TaSyaryouRieBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TaKasouRieBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TaKappuRieBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TaTourokuRieBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TaUchikomiBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TaUriGenBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_URI_GENKA']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            if ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SIT_DAISU']) != 0) {
                $TA_BUSYO['TaSitadoriBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_SIT_DAISU']));
            } else {
                $TA_BUSYO['TaSitadoriBusDai'] = "";
            }
            $TA_BUSYO['TaHanbaitesBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_HANBAITESURYO']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TaSyoukairyoBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TaChukoGenBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TaTougetuBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));

            if ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_DAISU_BUSYO']) != 0) {
                $TA_BUSYO['TaToukiGenBusDai'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_DAISU_BUSYO']));
            } else {
                $TA_BUSYO['TaToukiGenBusDai'] = "";
            }
        }
        $TA_BUSYO['TA_URIAGE'] = $this->FncValueCnv($TA_BUSYO['TA_URIAGE']);
        $TA_BUSYO['TA_SYARYOU_RIEKI'] = $this->FncValueCnv($TA_BUSYO['TA_SYARYOU_RIEKI']);
        $TA_BUSYO['TA_KASOU_RIEKI'] = $this->FncValueCnv($TA_BUSYO['TA_KASOU_RIEKI']);
        $TA_BUSYO['TA_KAPPU_RIEKI'] = $this->FncValueCnv($TA_BUSYO['TA_KAPPU_RIEKI']);
        $TA_BUSYO['TA_TOUROKU_RIEKI'] = $this->FncValueCnv($TA_BUSYO['TA_TOUROKU_RIEKI']);
        $TA_BUSYO['TA_UCHIKOMIKIN'] = $this->FncValueCnv($TA_BUSYO['TA_UCHIKOMIKIN']);
        $TA_BUSYO['TA_URI_GENKA'] = $this->FncValueCnv($TA_BUSYO['TA_URI_GENKA']);
        $TA_BUSYO['TA_SITADORI_SON'] = $this->FncValueCnv($TA_BUSYO['TA_SITADORI_SON']);
        $TA_BUSYO['TA_HANBAITESURYO'] = $this->FncValueCnv($TA_BUSYO['TA_HANBAITESURYO']);
        $TA_BUSYO['TA_SYOUKAIRYO'] = $this->FncValueCnv($TA_BUSYO['TA_SYOUKAIRYO']);
        $TA_BUSYO['TA_CHUKOSYA_GENRI'] = $this->FncValueCnv($TA_BUSYO['TA_CHUKOSYA_GENRI']);
        $TA_BUSYO['TA_TOUGETU_GENRI'] = $this->FncValueCnv($TA_BUSYO['TA_TOUGETU_GENRI']);
        $TA_BUSYO['TA_TOUKI_GENKAIRIEKI_BUSYO'] = $this->FncValueCnv($TA_BUSYO['TA_TOUKI_GENKAIRIEKI_BUSYO']);

        $TA_BUSYO['TaUriageDaiBusyo'] = $this->FncValueCnv($TA_BUSYO['TaUriageDaiBusyo']);
        $TA_BUSYO['TaSyaryouRieBusDai'] = $this->FncValueCnv($TA_BUSYO['TaSyaryouRieBusDai']);
        $TA_BUSYO['TaKasouRieBusDai'] = $this->FncValueCnv($TA_BUSYO['TaKasouRieBusDai']);
        $TA_BUSYO['TaKappuRieBusDai'] = $this->FncValueCnv($TA_BUSYO['TaKappuRieBusDai']);
        $TA_BUSYO['TaTourokuRieBusDai'] = $this->FncValueCnv($TA_BUSYO['TaTourokuRieBusDai']);
        $TA_BUSYO['TaUchikomiBusDai'] = $this->FncValueCnv($TA_BUSYO['TaUchikomiBusDai']);
        $TA_BUSYO['TaUriGenBusDai'] = $this->FncValueCnv($TA_BUSYO['TaUriGenBusDai']);
        $TA_BUSYO['TaSitadoriBusDai'] = $this->FncValueCnv($TA_BUSYO['TaSitadoriBusDai']);
        $TA_BUSYO['TaHanbaitesBusDai'] = $this->FncValueCnv($TA_BUSYO['TaHanbaitesBusDai']);
        $TA_BUSYO['TaSyoukairyoBusDai'] = $this->FncValueCnv($TA_BUSYO['TaSyoukairyoBusDai']);
        $TA_BUSYO['TaChukoGenBusDai'] = $this->FncValueCnv($TA_BUSYO['TaChukoGenBusDai']);
        $TA_BUSYO['TaTougetuBusDai'] = $this->FncValueCnv($TA_BUSYO['TaTougetuBusDai']);
        $TA_BUSYO['TaToukiGenBusDai'] = $this->FncValueCnv($TA_BUSYO['TaToukiGenBusDai']);
    }

    public function GroupFooter1_Format($TA_BUSYO)
    {
        //----他ﾁｬﾝﾈﾙ-----
        if ($this->ClsComFnc->FncNz(rtrim($TA_BUSYO['TA_DAISU'])) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_URIAGE']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_SYARYOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_KASOU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_KAPPU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUROKU_RIEKI']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_UCHIKOMIKIN']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_URI_GENKA']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_SITADORI_SON']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_HANBAITESURYO']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_SYOUKAIRYO']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_CHUKOSYA_GENRI']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUGETU_GENRI']) == 0 && $this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_GENKAIRIEKI']) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function GroupFooter8_BeforePrint(&$SIN_TOTAL)
    {
        //----売上-----
        if ($this->ClsComFnc->FncNz(rtrim($SIN_TOTAL['SIN_DAISU'])) == 0) {
            return;
        }
        $SIN_TOTAL['SinUriSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_URIAGE']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SinSyaRieSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SinKasRieSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SinKappuRieSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SinTouRieSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SinUchikomiSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SinUriGenSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_URI_GENKA']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        if ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SIT_DAISU']) != 0) {
            $SIN_TOTAL['SinSitSonSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SIT_DAISU']));
        } else {
            $SIN_TOTAL['SinSitSonSouDai'] = "";
        }
        $SIN_TOTAL['SinHanbaiSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_HANBAITESURYO']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SinSyoukaiSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SinChukoSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SinTougetuSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        //20240530 lujunxia upd s
        //$SIN_TOTAL['SinToukiSouDai'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_TOTAL']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUKI_DAISU_TOTAL']));
        $SIN_TOTAL['SinToukiSouDai'] = $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUKI_DAISU_TOTAL']) == 0 ? '' : (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_TOTAL']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUKI_DAISU_TOTAL']));
        //20240530 lujunxia upd e
        $SIN_TOTAL['SIN_URIAGE'] = $this->FncValueCnv($SIN_TOTAL['SIN_URIAGE']);
        $SIN_TOTAL['SIN_SYARYOU_RIEKI'] = $this->FncValueCnv($SIN_TOTAL['SIN_SYARYOU_RIEKI']);
        $SIN_TOTAL['SIN_KASOU_RIEKI'] = $this->FncValueCnv($SIN_TOTAL['SIN_KASOU_RIEKI']);
        $SIN_TOTAL['SIN_KAPPU_RIEKI'] = $this->FncValueCnv($SIN_TOTAL['SIN_KAPPU_RIEKI']);
        $SIN_TOTAL['SIN_TOUROKU_RIEKI'] = $this->FncValueCnv($SIN_TOTAL['SIN_TOUROKU_RIEKI']);
        $SIN_TOTAL['SIN_UCHIKOMIKIN'] = $this->FncValueCnv($SIN_TOTAL['SIN_UCHIKOMIKIN']);
        $SIN_TOTAL['SIN_URI_GENKA'] = $this->FncValueCnv($SIN_TOTAL['SIN_URI_GENKA']);
        $SIN_TOTAL['SIN_SITADORI_SON'] = $this->FncValueCnv($SIN_TOTAL['SIN_SITADORI_SON']);
        $SIN_TOTAL['SIN_HANBAITESURYO'] = $this->FncValueCnv($SIN_TOTAL['SIN_HANBAITESURYO']);
        $SIN_TOTAL['SIN_SYOUKAIRYO'] = $this->FncValueCnv($SIN_TOTAL['SIN_SYOUKAIRYO']);
        $SIN_TOTAL['SIN_CHUKOSYA_GENRI'] = $this->FncValueCnv($SIN_TOTAL['SIN_CHUKOSYA_GENRI']);
        $SIN_TOTAL['SIN_TOUGETU_GENRI'] = $this->FncValueCnv($SIN_TOTAL['SIN_TOUGETU_GENRI']);
        $SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_TOTAL'] = $this->FncValueCnv($SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_TOTAL']);

        $SIN_TOTAL['SinUriSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinUriSouDai']);
        $SIN_TOTAL['SinSyaRieSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinSyaRieSouDai']);
        $SIN_TOTAL['SinKasRieSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinKasRieSouDai']);
        $SIN_TOTAL['SinKappuRieSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinKappuRieSouDai']);
        $SIN_TOTAL['SinTouRieSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinTouRieSouDai']);
        $SIN_TOTAL['SinUchikomiSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinUchikomiSouDai']);
        $SIN_TOTAL['SinUriGenSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinUriGenSouDai']);
        $SIN_TOTAL['SinSitSonSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinSitSonSouDai']);
        $SIN_TOTAL['SinHanbaiSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinHanbaiSouDai']);
        $SIN_TOTAL['SinSyoukaiSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinSyoukaiSouDai']);
        $SIN_TOTAL['SinChukoSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinChukoSouDai']);
        $SIN_TOTAL['SinTougetuSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinTougetuSouDai']);
        $SIN_TOTAL['SinToukiSouDai'] = $this->FncValueCnv($SIN_TOTAL['SinToukiSouDai']);
    }

    public function GroupFooter8_Format($SIN_TOTAL)
    {
        //----新車-----
        if ($this->ClsComFnc->FncNz(rtrim($SIN_TOTAL['SIN_DAISU'])) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function GroupFooter9_BeforePrint(&$CHU_TOTAL)
    {
        //----中古車-----
        if ($this->ClsComFnc->FncNz(rtrim($CHU_TOTAL['CHU_DAISU'])) == 0) {
            return;
        }
        $CHU_TOTAL['ChuUriSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_URIAGE']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['ChuSyaRieSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['ChuKasRieSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['ChuKappuRieSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['ChuTouRieSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['ChuUchikomiSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['ChuUriGenSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_URI_GENKA']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        if ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SIT_DAISU']) != 0) {
            $CHU_TOTAL['ChuSitSonSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SIT_DAISU']));
        } else {
            $CHU_TOTAL['ChuSitSonSouDai'] = "";
        }
        $CHU_TOTAL['ChuHanbaiSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_HANBAITESURYO']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['ChuSyoukaiSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['ChuChukoSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['ChuTougetuSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        //20240530 lujunxia upd s
        //$CHU_TOTAL['ChuToukiSouDai'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_TOTAL']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUKI_DAISU_TOTAL']));
        $CHU_TOTAL['ChuToukiSouDai'] = $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUKI_DAISU_TOTAL']) == 0 ? '' : (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_TOTAL']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUKI_DAISU_TOTAL']));
        //20240530 lujunxia upd e

        $CHU_TOTAL['CHU_URIAGE'] = $this->FncValueCnv($CHU_TOTAL['CHU_URIAGE']);
        $CHU_TOTAL['CHU_SYARYOU_RIEKI'] = $this->FncValueCnv($CHU_TOTAL['CHU_SYARYOU_RIEKI']);
        $CHU_TOTAL['CHU_KASOU_RIEKI'] = $this->FncValueCnv($CHU_TOTAL['CHU_KASOU_RIEKI']);
        $CHU_TOTAL['CHU_KAPPU_RIEKI'] = $this->FncValueCnv($CHU_TOTAL['CHU_KAPPU_RIEKI']);
        $CHU_TOTAL['CHU_TOUROKU_RIEKI'] = $this->FncValueCnv($CHU_TOTAL['CHU_TOUROKU_RIEKI']);
        $CHU_TOTAL['CHU_UCHIKOMIKIN'] = $this->FncValueCnv($CHU_TOTAL['CHU_UCHIKOMIKIN']);
        $CHU_TOTAL['CHU_URI_GENKA'] = $this->FncValueCnv($CHU_TOTAL['CHU_URI_GENKA']);
        $CHU_TOTAL['CHU_SITADORI_SON'] = $this->FncValueCnv($CHU_TOTAL['CHU_SITADORI_SON']);
        $CHU_TOTAL['CHU_HANBAITESURYO'] = $this->FncValueCnv($CHU_TOTAL['CHU_HANBAITESURYO']);
        $CHU_TOTAL['CHU_SYOUKAIRYO'] = $this->FncValueCnv($CHU_TOTAL['CHU_SYOUKAIRYO']);
        $CHU_TOTAL['CHU_CHUKOSYA_GENRI'] = $this->FncValueCnv($CHU_TOTAL['CHU_CHUKOSYA_GENRI']);
        $CHU_TOTAL['CHU_TOUGETU_GENRI'] = $this->FncValueCnv($CHU_TOTAL['CHU_TOUGETU_GENRI']);
        $CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_TOTAL'] = $this->FncValueCnv($CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_TOTAL']);

        $CHU_TOTAL['ChuUriSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuUriSouDai']);
        $CHU_TOTAL['ChuSyaRieSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuSyaRieSouDai']);
        $CHU_TOTAL['ChuKasRieSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuKasRieSouDai']);
        $CHU_TOTAL['ChuKappuRieSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuKappuRieSouDai']);
        $CHU_TOTAL['ChuTouRieSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuTouRieSouDai']);
        $CHU_TOTAL['ChuUchikomiSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuUchikomiSouDai']);
        $CHU_TOTAL['ChuUriGenSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuUriGenSouDai']);
        $CHU_TOTAL['ChuSitSonSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuSitSonSouDai']);
        $CHU_TOTAL['ChuHanbaiSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuHanbaiSouDai']);
        $CHU_TOTAL['ChuSyoukaiSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuSyoukaiSouDai']);
        $CHU_TOTAL['ChuChukoSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuChukoSouDai']);
        $CHU_TOTAL['ChuTougetuSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuTougetuSouDai']);
        $CHU_TOTAL['ChuToukiSouDai'] = $this->FncValueCnv($CHU_TOTAL['ChuToukiSouDai']);
    }

    public function GroupFooter9_Format($CHU_TOTAL)
    {
        //----中古車-----
        if ($this->ClsComFnc->FncNz(rtrim($CHU_TOTAL['CHU_DAISU'])) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function GroupFooter3_BeforePrint(&$TA_TOTAL)
    {
        //----他ﾁｬﾝﾈﾙ-----
        if ($this->ClsComFnc->FncNz(rtrim($TA_TOTAL['TA_DAISU'])) == 0) {
            return;
        }
        $TA_TOTAL['TaUriSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_URIAGE']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaSyaRieSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaKasRieSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaKappuRieSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaTouRieSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaUchikomiSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaUriGenSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_URI_GENKA']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        if ($this->ClsComFnc->FncNz($TA_TOTAL['TA_SIT_DAISU']) != 0) {
            $TA_TOTAL['TaSitSonSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_SIT_DAISU']));
        } else {
            $TA_TOTAL['TaSitSonSouDai'] = "";
        }
        $TA_TOTAL['TaHanbaiSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_HANBAITESURYO']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaSyoukaiSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaChukoSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaTougetuSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TaToukiSouDai'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_TOUKI_GENKAIRIEKI_TOTAL']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_TOUKI_DAISU_TOTAL']));

        $TA_TOTAL['TA_URIAGE'] = $this->FncValueCnv($TA_TOTAL['TA_URIAGE']);
        $TA_TOTAL['TA_SYARYOU_RIEKI'] = $this->FncValueCnv($TA_TOTAL['TA_SYARYOU_RIEKI']);
        $TA_TOTAL['TA_KASOU_RIEKI'] = $this->FncValueCnv($TA_TOTAL['TA_KASOU_RIEKI']);
        $TA_TOTAL['TA_KAPPU_RIEKI'] = $this->FncValueCnv($TA_TOTAL['TA_KAPPU_RIEKI']);
        $TA_TOTAL['TA_TOUROKU_RIEKI'] = $this->FncValueCnv($TA_TOTAL['TA_TOUROKU_RIEKI']);
        $TA_TOTAL['TA_UCHIKOMIKIN'] = $this->FncValueCnv($TA_TOTAL['TA_UCHIKOMIKIN']);
        $TA_TOTAL['TA_URI_GENKA'] = $this->FncValueCnv($TA_TOTAL['TA_URI_GENKA']);
        $TA_TOTAL['TA_SITADORI_SON'] = $this->FncValueCnv($TA_TOTAL['TA_SITADORI_SON']);
        $TA_TOTAL['TA_HANBAITESURYO'] = $this->FncValueCnv($TA_TOTAL['TA_HANBAITESURYO']);
        $TA_TOTAL['TA_SYOUKAIRYO'] = $this->FncValueCnv($TA_TOTAL['TA_SYOUKAIRYO']);
        $TA_TOTAL['TA_CHUKOSYA_GENRI'] = $this->FncValueCnv($TA_TOTAL['TA_CHUKOSYA_GENRI']);
        $TA_TOTAL['TA_TOUGETU_GENRI'] = $this->FncValueCnv($TA_TOTAL['TA_TOUGETU_GENRI']);
        $TA_TOTAL['TA_TOUKI_GENKAIRIEKI_TOTAL'] = $this->FncValueCnv($TA_TOTAL['TA_TOUKI_GENKAIRIEKI_TOTAL']);

        $TA_TOTAL['TaUriSouDai'] = $this->FncValueCnv($TA_TOTAL['TaUriSouDai']);
        $TA_TOTAL['TaSyaRieSouDai'] = $this->FncValueCnv($TA_TOTAL['TaSyaRieSouDai']);
        $TA_TOTAL['TaKasRieSouDai'] = $this->FncValueCnv($TA_TOTAL['TaKasRieSouDai']);
        $TA_TOTAL['TaKappuRieSouDai'] = $this->FncValueCnv($TA_TOTAL['TaKappuRieSouDai']);
        $TA_TOTAL['TaTouRieSouDai'] = $this->FncValueCnv($TA_TOTAL['TaTouRieSouDai']);
        $TA_TOTAL['TaUchikomiSouDai'] = $this->FncValueCnv($TA_TOTAL['TaUchikomiSouDai']);
        $TA_TOTAL['TaUriGenSouDai'] = $this->FncValueCnv($TA_TOTAL['TaUriGenSouDai']);
        $TA_TOTAL['TaSitSonSouDai'] = $this->FncValueCnv($TA_TOTAL['TaSitSonSouDai']);
        $TA_TOTAL['TaHanbaiSouDai'] = $this->FncValueCnv($TA_TOTAL['TaHanbaiSouDai']);
        $TA_TOTAL['TaSyoukaiSouDai'] = $this->FncValueCnv($TA_TOTAL['TaSyoukaiSouDai']);
        $TA_TOTAL['TaChukoSouDai'] = $this->FncValueCnv($TA_TOTAL['TaChukoSouDai']);
        $TA_TOTAL['TaTougetuSouDai'] = $this->FncValueCnv($TA_TOTAL['TaTougetuSouDai']);
        $TA_TOTAL['TaToukiSouDai'] = $this->FncValueCnv($TA_TOTAL['TaToukiSouDai']);
    }

    public function GroupFooter3_Format($TA_TOTAL)
    {
        //----他ﾁｬﾝﾈﾙ-----
        if ($this->ClsComFnc->FncNz(rtrim($TA_TOTAL['TA_DAISU'])) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}