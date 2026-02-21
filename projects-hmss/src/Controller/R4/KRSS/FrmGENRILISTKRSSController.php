<?php
/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150806           #2070                                                    FANZHENGZHOU
 * 20160915           -----                      罫線描画修正                                 HM
 * 20150921           -----                      全社分ページの罫線描画追加           HM
 * 20250911             error.log                                               caina
 * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmGENRILISTKRSS;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class FrmGENRILISTKRSSController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsComFncKRSS');
        $this->loadComponent('ClsLogControl');

    }
    public $FrmGENRILISTKRSS = "";
    public $intpattern = 0;
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmGENRILISTKRSS_layout');
    }

    public function fncGetBusyo()
    {
        $result = array();
        try {
            $this->FrmGENRILISTKRSS = new FrmGENRILISTKRSS();
            $result = $this->FrmGENRILISTKRSS->fncGetBusyo();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function frmGenkaiMakeLoad()
    {
        $result = array();
        try {
            $this->FrmGENRILISTKRSS = new FrmGENRILISTKRSS();
            $result = $this->FrmGENRILISTKRSS->selectData();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncAuthCheck()
    {
        $result = array();
        try {
            $this->FrmGENRILISTKRSS = new FrmGENRILISTKRSS();
            $result = $this->FrmGENRILISTKRSS->fncAuthCheck();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['result'] == TRUE && count((array) $result['data']) == 1 && (String) $result['data'][0]["BUSYO_CD"] != "000") {
                $this->ClsComFnc->FncGetBusyoMstValue((String) $result['data'][0]["BUSYO_CD"], $this->ClsComFnc->GS_BUSYOMST);
                $result['BusyoMst'] = $this->ClsComFnc->GS_BUSYOMST;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncAuthorityInvest()
    {
        $result = array();
        $strBusyoCD = "";
        $strSyainNo = "";
        $CurrentForm = array();
        try {
            $this->FrmGENRILISTKRSS = new FrmGENRILISTKRSS();
            $strSyainNo = $this->FrmGENRILISTKRSS->GS_LOGINUSER['strUserID'];
            $strBusyoCD = $_POST['data']['BusyoCd'];
            // 20250911 caina upd s
            // $CurrentForm = $_POST['data']['controls'];
            $CurrentForm = isset($_POST['data']['controls']) && is_array($_POST['data']['controls'])
                ? $_POST['data']['controls']
                : [];
            // 20250911 caina upd e
            $result = $this->ClsComFncKRSS->fncAuthorityInvest($CurrentForm, $strSyainNo, $strBusyoCD);
            if ($result['result'] == FALSE) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：一覧表ボタン押下
    //関 数 名：cmdAct_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：限界利益一覧表を作成する
    //**********************************************************************
    public function cmdActClick()
    {
        $result = array();
        $postArr = array();
        $intState = 0;
        $lngOutCnt = 0;
        try {
            $postArr = $_POST['data'];
            $cboYM = $_POST['data']['cboYM'];
            $strBusyoF = $_POST['data']['txtBusyoCDFrom'];
            $strBusyoT = $_POST['data']['txtBusyoCDTo'];
            $intAuth = $_POST['data']['intAuth'];

            if ($intAuth == 0) {
                //全社の欄は印字しない
                $this->intpattern = 0;
            } elseif ($strBusyoF != "" || $strBusyoT != "") {
                //全社の欄は印字しない
                $this->intPattern = 0;
            } else {
                //全社の欄を印字する
                $this->intpattern = 1;
            }

            //ログ管理
            $intState = 9;

            $this->FrmGENRILISTKRSS = new FrmGENRILISTKRSS();
            $result = $this->FrmGENRILISTKRSS->fncGenriIchiran($postArr['intAuth'], str_replace("/", "", $postArr['cboYM']), $postArr['txtBusyoCDFrom'], $postArr['txtBusyoCDTo'], str_replace("cmd", "", "cmd003"));

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $lngOutCnt = count((array) $result['data']);

            $USERID = $this->FrmGENRILISTKRSS->GS_LOGINUSER['strUserID'];

            // print_r($ExcelData);
            // return;
            //*********************构造数据****************************
            // print_r($result['data']);
            // return;
            if (count((array) $result['data']) > 0) {
                // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";
                $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
                $tmpPath2 = "webroot/files/KRSS/";
                $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
                $file = $tmpPath . "車両限界利益一覧表_" . $USERID . ".xlsx";

                if (!file_exists($tmpPath)) {
                    if (!mkdir($tmpPath, 0777, TRUE)) {
                        $result["data"] = "Execl Error";
                        throw new \Exception($result["data"]);
                    }
                }

                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmGENRILISTKRSSTemplate1.xlsx";
                //テンプレートファイルの存在確認
                if (file_exists($strTemplatePath) == FALSE) {
                    $result["data"] = "EXCELテンプレートが見つかりません！";
                    throw new \Exception($result["data"]);
                }

                //********************构造数据*****************************
                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/KRSS/FrmGENRILISTKRSS.inc';
                $ExcelData = array();
                $Curernt_BUSYO = "";
                $Last_BUSYO = "";
                $Curernt_SYAIN = "";
                $Last_SYAIN = "";

                foreach ((array) $result['data'] as $key => $value) {
                    //全社　合計 s.
                    //新車
                    foreach ($SIN_TOTAL as $key11 => $val11) {
                        $SIN_TOTAL[$key11] = (int) $SIN_TOTAL[$key11] + (int) $value[$key11];
                    }
                    $SIN_TOTAL['TODAY'] = $value['TODAY'];
                    $SIN_TOTAL['SIN_TOUKI_DAISU_TOTAL'] = $value['SIN_TOUKI_DAISU_TOTAL'];
                    $SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_TOTAL'] = $value['SIN_TOUKI_GENKAIRIEKI_TOTAL'];
                    //中古車
                    foreach ($CHU_TOTAL as $key22 => $val22) {
                        $CHU_TOTAL[$key22] = (int) $CHU_TOTAL[$key22] + (int) $value[$key22];
                    }
                    $CHU_TOTAL['TODAY'] = $value['TODAY'];
                    $CHU_TOTAL['CHU_TOUKI_DAISU_TOTAL'] = $value['CHU_TOUKI_DAISU_TOTAL'];
                    $CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_TOTAL'] = $value['CHU_TOUKI_GENKAIRIEKI_TOTAL'];
                    //他ﾁｬﾝﾈﾙ
                    foreach ($TA_TOTAL as $key33 => $val33) {
                        $TA_TOTAL[$key33] = (int) $TA_TOTAL[$key33] + (int) $value[$key33];
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
                            $SIN_BUSYO[$key1] = (int) $SIN_BUSYO[$key1] + (int) $val1;
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
                        array_push($ExcelData, $SIN_SYAIN);
                        //reset the value
                        $SIN_SYAIN = $SIN_SYAIN1;

                        //中古車
                        foreach ($CHU_SYAIN as $key2 => $val2) {
                            $CHU_BUSYO[$key2] = (int) $CHU_BUSYO[$key2] + (int) $val2;
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
                        array_push($ExcelData, $CHU_SYAIN);
                        //reset the value
                        $CHU_SYAIN = $CHU_SYAIN1;

                        //他ﾁｬﾝﾈﾙ
                        foreach ($TA_SYAIN as $key3 => $val3) {
                            $TA_BUSYO[$key3] = (int) $TA_BUSYO[$key3] + (int) $val3;
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
                        array_push($ExcelData, $TA_SYAIN);
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
                            $SIN_BUSYO[$key1] = (int) $SIN_BUSYO[$key1] + (int) $val1;
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
                        array_push($ExcelData, $SIN_SYAIN);
                        //reset the value
                        $SIN_SYAIN = $SIN_SYAIN1;

                        //中古車
                        foreach ($CHU_SYAIN as $key2 => $val2) {
                            $CHU_BUSYO[$key2] = (int) $CHU_BUSYO[$key2] + (int) $val2;
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
                        array_push($ExcelData, $CHU_SYAIN);
                        //reset the value
                        $CHU_SYAIN = $CHU_SYAIN1;

                        //他ﾁｬﾝﾈﾙ
                        foreach ($TA_SYAIN as $key3 => $val3) {
                            $TA_BUSYO[$key3] = (int) $TA_BUSYO[$key3] + (int) $val3;
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
                        array_push($ExcelData, $TA_SYAIN);
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
                        array_push($ExcelData, $SIN_BUSYO);
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
                        array_push($ExcelData, $CHU_BUSYO);
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
                        array_push($ExcelData, $TA_BUSYO);
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
                    array_push($ExcelData, $value);
                }
                //新車
                foreach ($SIN_SYAIN as $key1 => $val1) {
                    $SIN_BUSYO[$key1] = (int) $SIN_BUSYO[$key1] + (int) $val1;
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
                array_push($ExcelData, $SIN_SYAIN);
                //reset the value
                $SIN_SYAIN = $SIN_SYAIN1;

                //中古車
                foreach ($CHU_SYAIN as $key2 => $val2) {
                    $CHU_BUSYO[$key2] = (int) $CHU_BUSYO[$key2] + (int) $val2;
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
                array_push($ExcelData, $CHU_SYAIN);
                //reset the value
                $CHU_SYAIN = $CHU_SYAIN1;

                //他ﾁｬﾝﾈﾙ
                foreach ($TA_SYAIN as $key3 => $val3) {
                    $TA_BUSYO[$key3] = (int) $TA_BUSYO[$key3] + (int) $val3;
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
                array_push($ExcelData, $TA_SYAIN);
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
                array_push($ExcelData, $SIN_BUSYO);
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
                array_push($ExcelData, $CHU_BUSYO);
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
                array_push($ExcelData, $TA_BUSYO);
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
                array_push($ExcelData, $SIN_TOTAL);
                //中古車
                $this->GroupFooter9_BeforePrint($CHU_TOTAL);
                $CHU_TOTAL['flag'] = "GroupFooter9";
                if ($this->GroupFooter9_Format($CHU_TOTAL)) {
                    $CHU_TOTAL['visible'] = 1;
                } else {
                    $CHU_TOTAL['visible'] = 0;
                }
                array_push($ExcelData, $CHU_TOTAL);
                //他ﾁｬﾝﾈﾙ
                $this->GroupFooter3_BeforePrint($TA_TOTAL);
                $TA_TOTAL['flag'] = "GroupFooter3";
                if ($this->GroupFooter3_Format($TA_TOTAL)) {
                    $TA_TOTAL['visible'] = 1;
                } else {
                    $TA_TOTAL['visible'] = 0;
                }
                array_push($ExcelData, $TA_TOTAL);

                // print_r($ExcelData);
                // return;
                //*********************构造数据****************************

                $objReader = IOFactory::createReader('Xlsx');
                $objPHPExcel = $objReader->load($strTemplatePath);
                $objPHPExcel->setActiveSheetIndex(0);
                $objActSheet = $objPHPExcel->getActiveSheet();

                $objActSheet->setCellValue('C' . 1, substr($ExcelData[0]['TODAY'], 0, 4) . "年" . substr($ExcelData[0]['TODAY'], 4, 2) . "月度");
                //set sheet's name.
                $objActSheet->setTitle($ExcelData[0]['BUSYO_NM']);

                $SheetCount = 1;
                $LAST_BUSYO_CD = $ExcelData[0]['ATUKAI_BUSYO'];
                $NOW_BUSYO_CD = "";
                foreach ($ExcelData as $key => $value) {
                    if (isset($ExcelData[$key]['ATUKAI_BUSYO'])) {
                        $NOW_BUSYO_CD = $value['ATUKAI_BUSYO'];
                    }
                    if ($NOW_BUSYO_CD != $LAST_BUSYO_CD) {
                        $objClonedWorksheet = clone $objPHPExcel->getSheetByName($ExcelData[0]['BUSYO_NM']);
                        $objClonedWorksheet->setTitle($value['BUSYO_NM'] == null ? 'sheet_' . $key : $value['BUSYO_NM']);
                        $objPHPExcel->addSheet($objClonedWorksheet);
                        $SheetCount++;
                    }
                    $LAST_BUSYO_CD = $NOW_BUSYO_CD;
                }
                if ($this->intpattern != 0) {
                    //Add全社Sheet
                    $objClonedWorksheet = clone $objPHPExcel->getSheetByName($ExcelData[0]['BUSYO_NM']);
                    $objClonedWorksheet->setTitle("全社");
                    $objPHPExcel->addSheet($objClonedWorksheet);
                    $SheetCount++;
                }

                //detail row start.
                $DetailStartRow = 6;
                $j = $DetailStartRow;
                //sheetNum
                $i = 0;
                $Curernt_SYAIN = "";
                $Last_SYAIN = "";
                $Last_BUSYO_CD = "";
                $Curernt_BUSYO_CD = "";

                foreach ($ExcelData as $key => $value) {
                    if (isset($ExcelData[$key]['ATUKAI_BUSYO'])) {
                        $Curernt_BUSYO_CD = $ExcelData[$key]['ATUKAI_BUSYO'];
                    }
                    if ($Curernt_BUSYO_CD != $Last_BUSYO_CD) {
                        //管理者
                        $objActSheet->setCellValue('B' . 3, $ExcelData[$key]['KANRISYAMEI']);
                        //部署CD ,NM
                        $objActSheet->setCellValue('L' . 3, $ExcelData[$key]['ATUKAI_BUSYO']);
                        $objActSheet->setCellValue('M' . 3, $ExcelData[$key]['BUSYO_NM']);
                    }
                    $Last_BUSYO_CD = $Curernt_BUSYO_CD;

                    if (isset($ExcelData[$key]['ATUKAI_SYAIN'])) {
                        $Curernt_SYAIN = $ExcelData[$key]['ATUKAI_SYAIN'];
                        //部署Array['ATUKAI_SYAIN']=="0"
                        if ($Curernt_SYAIN == "0") {
                            $Curernt_SYAIN = $Last_SYAIN;
                        }
                    }
                    if ($Curernt_SYAIN != $Last_SYAIN) {
                        $objActSheet->setCellValue('A' . $j, $ExcelData[$key]['SYAINMEI']);
                        $objActSheet->setCellValue('B' . $j, $ExcelData[$key]['ATUKAI_SYAIN']);
                        $j++;
                    }
                    $Last_SYAIN = $Curernt_SYAIN;

                    switch ($ExcelData[$key]['flag']) {
                        case 'Detail':
                            foreach ($Detail as $key1 => $value1) {
                                if ($ExcelData[$key][$key1] != "") {
                                    $objActSheet->setCellValue($value1 . $j, $ExcelData[$key][$key1]);
                                }
                            }
                            $j++;
                            break;
                        case 'GroupFooter5':
                            if ($ExcelData[$key]['visible'] != 0) {
                                $objActSheet->setCellValue('A' . $j, 'ｾｰﾙｽ:新車');
                                $objActSheet->setCellValue('C' . $j, '売上  ' . $ExcelData[$key]['SIN_DAISU'] . " 下取  " . $ExcelData[$key]['SIN_SIT_DAISU']);
                                foreach ($SIN_Tatol_Excel as $key2 => $value2) {
                                    $objActSheet->setCellValue($value2 . $j, $ExcelData[$key][$key2]);
                                }
                                $j++;
                                $objActSheet->setCellValue('C' . $j, '（台当り）');
                                $objActSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                foreach ($SIN_AVG_Excel as $key3 => $value3) {
                                    if ($ExcelData[$key][$key3] == 0) {
                                        $ExcelData[$key][$key3] = "";
                                    }
                                    $objActSheet->setCellValue($value3 . $j, $ExcelData[$key][$key3]);
                                }
                                $j++;
                            }
                            break;
                        case 'GroupFooter4':
                            if ($ExcelData[$key]['visible'] != 0) {
                                $objActSheet->setCellValue('A' . $j, 'ｾｰﾙｽ:中古');
                                $objActSheet->setCellValue('C' . $j, '中古車  ' . $ExcelData[$key]['CHU_DAISU'] . " 下取  " . $ExcelData[$key]['CHU_SIT_DAISU']);
                                foreach ($CHU_Tatol_Excel as $key4 => $value4) {
                                    $objActSheet->setCellValue($value4 . $j, $ExcelData[$key][$key4]);
                                }
                                $j++;
                                $objActSheet->setCellValue('C' . $j, '（台当り）');
                                $objActSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                foreach ($CHU_AVG_Excel as $key5 => $value5) {
                                    if ($ExcelData[$key][$key5] == 0) {
                                        $ExcelData[$key][$key5] = "";
                                    }
                                    $objActSheet->setCellValue($value5 . $j, $ExcelData[$key][$key5]);
                                }
                                $j++;
                            }
                            break;
                        case 'GroupFooter2':
                            if ($ExcelData[$key]['visible'] != 0) {
                                $objActSheet->setCellValue('A' . $j, 'ｾｰﾙｽ:他ﾁｬﾝﾈﾙ');
                                $objActSheet->setCellValue('C' . $j, '他ﾁｬﾝﾈﾙ  ' . $ExcelData[$key]['TA_DAISU'] . " 下取  " . $ExcelData[$key]['TA_SIT_DAISU']);
                                foreach ($TA_Tatol_Excel as $key6 => $value6) {
                                    $objActSheet->setCellValue($value6 . $j, $ExcelData[$key][$key6]);
                                }
                                $j++;
                                $objActSheet->setCellValue('C' . $j, '（台当り）');
                                $objActSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                foreach ($TA_AVG_Excel as $key7 => $value7) {
                                    if ($ExcelData[$key][$key7] == 0) {
                                        $ExcelData[$key][$key7] = "";
                                    }
                                    $objActSheet->setCellValue($value7 . $j, $ExcelData[$key][$key7]);
                                }
                                $j++;
                            }
                            break;
                        case 'GroupFooter6':
                            if ($ExcelData[$key]['visible'] != 0) {
                                $objActSheet->setCellValue('A' . $j, '部署:新車');
                                $objActSheet->setCellValue('C' . $j, '売上  ' . $ExcelData[$key]['SIN_DAISU'] . " 下取  " . $ExcelData[$key]['SIN_SIT_DAISU']);
                                foreach ($SIN_Tatol_Excel as $key8 => $value8) {
                                    if ($key8 == "SIN_TOUKI_GENKAIRIEKI") {
                                        $key8 = "SIN_TOUKI_GENKAIRIEKI_BUSYO";
                                    }
                                    $objActSheet->setCellValue($value8 . $j, $ExcelData[$key][$key8]);
                                }
                                $j++;
                                $objActSheet->setCellValue('C' . $j, '（台当り）');
                                $objActSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                foreach ($SIN_AVG_Excel as $key9 => $value9) {
                                    if ($ExcelData[$key][$key9] == 0) {
                                        $ExcelData[$key][$key9] = "";
                                    }
                                    $objActSheet->setCellValue($value9 . $j, $ExcelData[$key][$key9]);
                                }
                                $j++;
                            }
                            break;
                        case 'GroupFooter7':
                            if ($ExcelData[$key]['visible'] != 0) {
                                $objActSheet->setCellValue('A' . $j, '部署:中古');
                                $objActSheet->setCellValue('C' . $j, '中古車  ' . $ExcelData[$key]['CHU_DAISU'] . " 下取  " . $ExcelData[$key]['CHU_SIT_DAISU']);
                                foreach ($CHU_Tatol_Excel as $key10 => $value10) {
                                    if ($key10 == "CHU_TOUKI_GENKAIRIEKI") {
                                        $key10 = "CHU_TOUKI_GENKAIRIEKI_BUSYO";
                                    }
                                    $objActSheet->setCellValue($value10 . $j, $ExcelData[$key][$key10]);
                                }
                                $j++;
                                $objActSheet->setCellValue('C' . $j, '（台当り）');
                                $objActSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                foreach ($CHU_AVG_Excel as $key11 => $value11) {
                                    if ($ExcelData[$key][$key11] == 0) {
                                        $ExcelData[$key][$key11] = "";
                                    }
                                    $objActSheet->setCellValue($value11 . $j, $ExcelData[$key][$key11]);
                                }
                                $j++;
                            }
                            break;
                        case 'GroupFooter1':
                            if ($ExcelData[$key]['visible'] != 0) {
                                $objActSheet->setCellValue('A' . $j, '部署:他ﾁｬﾝﾈﾙ');
                                $objActSheet->setCellValue('C' . $j, '他ﾁｬﾝﾈﾙ  ' . $ExcelData[$key]['TA_DAISU'] . " 下取  " . $ExcelData[$key]['TA_SIT_DAISU']);
                                foreach ($TA_Tatol_Excel as $key12 => $value12) {
                                    if ($key12 == "TA_TOUKI_GENKAIRIEKI") {
                                        $key12 = "TA_TOUKI_GENKAIRIEKI_BUSYO";
                                    }
                                    $objActSheet->setCellValue($value12 . $j, $ExcelData[$key][$key12]);
                                }
                                $j++;
                                $objActSheet->setCellValue('C' . $j, '（台当り）');
                                $objActSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                foreach ($TA_AVG_Excel as $key13 => $value13) {
                                    if ($ExcelData[$key][$key13] == 0) {
                                        $ExcelData[$key][$key13] = "";
                                    }
                                    $objActSheet->setCellValue($value13 . $j, $ExcelData[$key][$key13]);
                                }
                                $j++;
                            }

                            //20160915 Del Start
                            //---20150708 fan add s.Draw border.
//                            if ($j > 53) {
                            if ($j > 51) {
                                //---20150806 #2070 fanzhengzhou del s.
                                //template's bottom border's postion.
                                //$btmLinePos='A52:R52';
                                //$objActSheet -> getStyle($btmLinePos) -> getBorders() -> getBottom() -> setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
                                //---20150806 #2070 fanzhengzhou del e.
                                //borders' color.
//                                $color = array('rgb' => '808000');
//                                $styleArray = array('borders' => array('vertical' => array('style' => PHPExcel_Style_Border::BORDER_THICK, 'color' => $color, ), 'left' => array('style' => PHPExcel_Style_Border::BORDER_THICK, 'color' => $color, ), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK, 'color' => $color, ), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK, 'color' => $color, ), ), );
//                                $objActSheet -> getStyle('A52:R' . --$j) -> applyFromArray($styleArray);
//                                $objActSheet -> getStyle('A51:R' . --$j) -> applyFromArray($styleArray);
                                //---20150806 #2070 fanzhengzhou add s.
//                                $bottomstyle = array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK, 'color' => $color)));
//                                $KK = 1;
//                                while ((52 + 47 * $KK) < (--$j)) {
//                                    $BottomRow = (52 + 47 * $KK);
//                                while ((51 + 46  * $KK) < (--$j)) {
//                                    $BottomRow = (51 + 46 * $KK);
//                                    $PageBottomLinePos = "A{$BottomRow}:R{$BottomRow}";
//                                    $objActSheet -> getStyle($PageBottomLinePos) -> applyFromArray($bottomstyle);
//                                    ++$KK;
//                                }

                                //---20150806 #2070 fanzhengzhou add e.
                            }
                            //20160915 Del End


                            //20160915 Add Start
                            $color = array('rgb' => '808000');
                            $styleArray = array('borders' => array('vertical' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color, ), 'left' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color, ), 'right' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color, ), 'bottom' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color, ), ), );
                            //                                $BottomRow =  $j -1;
                            if ($j - 1 > 52) {
                                $BottomRow = 5 + ceil(($j - 5) / 47) * 47;
                            } else {
                                $BottomRow = 52;
                            }

                            //                                $objActSheet -> getStyle('A4:R' . $j ) -> applyFromArray($styleArray);
                            $objActSheet->getStyle('A4:R' . $BottomRow)->applyFromArray($styleArray);


                            $bottomstyle = array('borders' => array('bottom' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color)));
                            $PageBottomLinePos = "A{$BottomRow}:R{$BottomRow}";
                            $objActSheet->getStyle($PageBottomLinePos)->applyFromArray($bottomstyle);
                            //20160915 Add End

                            //---20150708 fan add e.
                            //Reset the active sheet.
                            $i++;
                            if ($i < $SheetCount) {
                                $objPHPExcel->setActiveSheetIndex($i);
                                $objActSheet = $objPHPExcel->getActiveSheet();
                                $j = $DetailStartRow;
                            }
                            break;
                        case 'GroupFooter8':
                            //管理者
                            $objActSheet->setCellValue('B' . 3, "");
                            //部署CD ,NM
                            if ($ExcelData[$key]['visible'] == 1) {
                                $objActSheet->setCellValue('L' . 3, '全社');
                                $objActSheet->setCellValue('M' . 3, '合計');
                            }
                            $j = 8;
                            if ($ExcelData[$key]['visible'] != 0) {
                                $objActSheet->setCellValue('A' . $j, '全社:新車');
                                $objActSheet->setCellValue('C' . $j, '売上      ' . $ExcelData[$key]['SIN_DAISU'] . " 下取  " . $ExcelData[$key]['SIN_SIT_DAISU']);
                                foreach ($SIN_Tatol_Excel as $key14 => $value14) {
                                    if ($key14 == "SIN_TOUKI_GENKAIRIEKI") {
                                        $key14 = "SIN_TOUKI_GENKAIRIEKI_TOTAL";
                                    }
                                    $objActSheet->setCellValue($value14 . $j, $ExcelData[$key][$key14]);
                                }
                                $j++;
                                $objActSheet->setCellValue('C' . $j, '（台当り）');
                                $objActSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                foreach ($SIN_AVG_Excel as $key15 => $value15) {
                                    if ($ExcelData[$key][$key15] == 0) {
                                        $ExcelData[$key][$key15] = "";
                                    }
                                    $objActSheet->setCellValue($value15 . $j, $ExcelData[$key][$key15]);
                                }
                                $j++;
                            }
                            break;
                        case 'GroupFooter9':
                            if ($ExcelData[$key]['visible'] != 0) {
                                $objActSheet->setCellValue('A' . $j, '全社:中古');
                                $objActSheet->setCellValue('C' . $j, '中古車    ' . $ExcelData[$key]['CHU_DAISU'] . " 下取  " . $ExcelData[$key]['CHU_SIT_DAISU']);
                                foreach ($CHU_Tatol_Excel as $key16 => $value16) {
                                    if ($key16 == "CHU_TOUKI_GENKAIRIEKI") {
                                        $key16 = "CHU_TOUKI_GENKAIRIEKI_TOTAL";
                                    }
                                    $objActSheet->setCellValue($value16 . $j, $ExcelData[$key][$key16]);
                                }
                                $j++;
                                $objActSheet->setCellValue('C' . $j, '（台当り）');
                                $objActSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                foreach ($CHU_AVG_Excel as $key17 => $value17) {
                                    if ($ExcelData[$key][$key17] == 0) {
                                        $ExcelData[$key][$key17] = "";
                                    }
                                    $objActSheet->setCellValue($value17 . $j, $ExcelData[$key][$key17]);
                                }
                                $j++;
                            }
                            break;
                        case 'GroupFooter3':
                            if ($ExcelData[$key]['visible'] != 0) {
                                $objActSheet->setCellValue('A' . $j, '全社:他ﾁｬﾝﾈﾙ');
                                $objActSheet->setCellValue('C' . $j, '他ﾁｬﾝﾈﾙ  ' . $ExcelData[$key]['TA_DAISU'] . " 下取  " . $ExcelData[$key]['TA_SIT_DAISU']);
                                foreach ($TA_Tatol_Excel as $key18 => $value18) {
                                    if ($key18 == "TA_TOUKI_GENKAIRIEKI") {
                                        $key18 = "TA_TOUKI_GENKAIRIEKI_TOTAL";
                                    }
                                    $objActSheet->setCellValue($value18 . $j, $ExcelData[$key][$key18]);
                                }
                                $j++;
                                $objActSheet->setCellValue('C' . $j, '（台当り）');
                                $objActSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                foreach ($TA_AVG_Excel as $key19 => $value19) {
                                    if ($ExcelData[$key][$key19] == 0) {
                                        $ExcelData[$key][$key19] = "";
                                    }
                                    $objActSheet->setCellValue($value19 . $j, $ExcelData[$key][$key19]);
                                }
                                $j++;
                            }

                            //20160921 Add Start
                            $color = array('rgb' => '808000');
                            $styleArray = array('borders' => array('vertical' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color, ), 'left' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color, ), 'right' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color, ), 'bottom' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color, ), ), );
                            $BottomRow = 52;
                            $objActSheet->getStyle('A4:R' . $BottomRow)->applyFromArray($styleArray);
                            $bottomstyle = array('borders' => array('bottom' => array('borderStyle' => Border::BORDER_THICK, 'color' => $color)));
                            $PageBottomLinePos = "A{$BottomRow}:R{$BottomRow}";
                            $objActSheet->getStyle($PageBottomLinePos)->applyFromArray($bottomstyle);
                            //20160921 Add End

                            break;
                    }
                }

                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
                $objWriter->save($file);
                $result['data'] = "files/KRSS/" . "車両限界利益一覧表_" . $USERID . ".xlsx";
            }
            //ログ管理
            $intState = 1;
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //ログ管理 Start
        if ($intState != 0) {
            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmGENRILIST_Excel", $intState, $lngOutCnt, $cboYM, $strBusyoF, $strBusyoT);
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
        $objText = is_null($objText) ? "" : $objText;
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

            $SIN_SYAIN['SIN_URIAGE_AVG'] = $SIN_SYAIN['SIN_URIAGE'];
            $SIN_SYAIN['SIN_SYARYOU_RIEKI_AVG'] = $SIN_SYAIN['SIN_SYARYOU_RIEKI'];
            $SIN_SYAIN['SIN_KASOU_RIEKI_AVG'] = $SIN_SYAIN['SIN_KASOU_RIEKI'];
            $SIN_SYAIN['SIN_KAPPU_RIEKI_AVG'] = $SIN_SYAIN['SIN_KAPPU_RIEKI'];
            $SIN_SYAIN['SIN_TOUROKU_RIEKI_AVG'] = $SIN_SYAIN['SIN_TOUROKU_RIEKI'];
            $SIN_SYAIN['SIN_UCHIKOMIKIN_AVG'] = $SIN_SYAIN['SIN_UCHIKOMIKIN'];
            $SIN_SYAIN['SIN_URI_GENKA_AVG'] = $SIN_SYAIN['SIN_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SIT_DAISU']) != 0) {
                $SIN_SYAIN['SIN_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SIT_DAISU']));
            } else {
                $SIN_SYAIN['SIN_SITADORI_SON_AVG'] = "";
            }
            $SIN_SYAIN['SIN_HANBAITESURYO_AVG'] = $SIN_SYAIN['SIN_HANBAITESURYO'];
            $SIN_SYAIN['SIN_SYOUKAIRYO_AVG'] = $SIN_SYAIN['SIN_SYOUKAIRYO'];
            $SIN_SYAIN['SIN_CHUKOSYA_GENRI_AVG'] = $SIN_SYAIN['SIN_CHUKOSYA_GENRI'];
            $SIN_SYAIN['SIN_TOUGETU_GENRI_AVG'] = $SIN_SYAIN['SIN_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_DAISU']) != 0) {
                $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_DAISU']));
            } else {
                $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_AVG'] = "";
            }
        } else {
            $SIN_SYAIN['SIN_URIAGE_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_URIAGE']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SIN_SYARYOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SIN_KASOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SIN_KAPPU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SIN_TOUROKU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SIN_UCHIKOMIKIN_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SIN_URI_GENKA_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_URI_GENKA']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            if ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SIT_DAISU']) != 0) {
                $SIN_SYAIN['SIN_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SIT_DAISU']));
            } else {
                $SIN_SYAIN['SIN_SITADORI_SON_AVG'] = "";
            }
            $SIN_SYAIN['SIN_HANBAITESURYO_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_HANBAITESURYO']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SIN_SYOUKAIRYO_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SIN_CHUKOSYA_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            $SIN_SYAIN['SIN_TOUGETU_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_DAISU']));
            if ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_DAISU']) != 0) {
                $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($SIN_SYAIN['SIN_TOUKI_DAISU']));
            } else {
                $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_AVG'] = "";
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

        $SIN_SYAIN['SIN_URIAGE_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_URIAGE_AVG']);
        $SIN_SYAIN['SIN_SYARYOU_RIEKI_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_SYARYOU_RIEKI_AVG']);
        $SIN_SYAIN['SIN_KASOU_RIEKI_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_KASOU_RIEKI_AVG']);
        $SIN_SYAIN['SIN_KAPPU_RIEKI_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_KAPPU_RIEKI_AVG']);
        $SIN_SYAIN['SIN_TOUROKU_RIEKI_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_TOUROKU_RIEKI_AVG']);
        $SIN_SYAIN['SIN_UCHIKOMIKIN_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_UCHIKOMIKIN_AVG']);
        $SIN_SYAIN['SIN_URI_GENKA_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_URI_GENKA_AVG']);
        $SIN_SYAIN['SIN_SITADORI_SON_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_SITADORI_SON_AVG']);
        $SIN_SYAIN['SIN_HANBAITESURYO_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_HANBAITESURYO_AVG']);
        $SIN_SYAIN['SIN_SYOUKAIRYO_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_SYOUKAIRYO_AVG']);
        $SIN_SYAIN['SIN_CHUKOSYA_GENRI_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_CHUKOSYA_GENRI_AVG']);
        $SIN_SYAIN['SIN_TOUGETU_GENRI_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_TOUGETU_GENRI_AVG']);
        $SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_AVG'] = $this->FncValueCnv($SIN_SYAIN['SIN_TOUKI_GENKAIRIEKI_AVG']);
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

            $CHU_SYAIN['CHU_URIAGE_AVG'] = $CHU_SYAIN['CHU_URIAGE'];
            $CHU_SYAIN['CHU_SYARYOU_RIEKI_AVG'] = $CHU_SYAIN['CHU_SYARYOU_RIEKI'];
            $CHU_SYAIN['CHU_KASOU_RIEKI_AVG'] = $CHU_SYAIN['CHU_KASOU_RIEKI'];
            $CHU_SYAIN['CHU_KAPPU_RIEKI_AVG'] = $CHU_SYAIN['CHU_KAPPU_RIEKI'];
            $CHU_SYAIN['CHU_TOUROKU_RIEKI_AVG'] = $CHU_SYAIN['CHU_TOUROKU_RIEKI'];
            $CHU_SYAIN['CHU_UCHIKOMIKIN_AVG'] = $CHU_SYAIN['CHU_UCHIKOMIKIN'];
            $CHU_SYAIN['CHU_URI_GENKA_AVG'] = $CHU_SYAIN['CHU_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SIT_DAISU']) != 0) {
                $CHU_SYAIN['CHU_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SIT_DAISU']));
            } else {
                $CHU_SYAIN['CHU_SITADORI_SON_AVG'] = "";
            }
            $CHU_SYAIN['CHU_HANBAITESURYO_AVG'] = $CHU_SYAIN['CHU_HANBAITESURYO'];
            $CHU_SYAIN['CHU_SYOUKAIRYO_AVG'] = $CHU_SYAIN['CHU_SYOUKAIRYO'];
            $CHU_SYAIN['CHU_CHUKOSYA_GENRI_AVG'] = $CHU_SYAIN['CHU_CHUKOSYA_GENRI'];
            $CHU_SYAIN['CHU_TOUGETU_GENRI_AVG'] = $CHU_SYAIN['CHU_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_DAISU']) != 0) {
                $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_DAISU']));
            } else {
                $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_AVG'] = "";
            }
        } else {
            $CHU_SYAIN['CHU_URIAGE_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_URIAGE']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['CHU_SYARYOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['CHU_KASOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['CHU_KAPPU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['CHU_TOUROKU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['CHU_UCHIKOMIKIN_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['CHU_URI_GENKA_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_URI_GENKA']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            if ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SIT_DAISU']) != 0) {
                $CHU_SYAIN['CHU_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SIT_DAISU']));
            } else {
                $CHU_SYAIN['CHU_SITADORI_SON_AVG'] = "";
            }
            $CHU_SYAIN['CHU_HANBAITESURYO_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_HANBAITESURYO']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['CHU_SYOUKAIRYO_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['CHU_CHUKOSYA_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            $CHU_SYAIN['CHU_TOUGETU_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_DAISU']));
            if ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_DAISU']) != 0) {
                $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($CHU_SYAIN['CHU_TOUKI_DAISU']));
            } else {
                $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_AVG'] = "";
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

        $CHU_SYAIN['CHU_URIAGE_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_URIAGE_AVG']);
        $CHU_SYAIN['CHU_SYARYOU_RIEKI_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_SYARYOU_RIEKI_AVG']);
        $CHU_SYAIN['CHU_KASOU_RIEKI_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_KASOU_RIEKI_AVG']);
        $CHU_SYAIN['CHU_KAPPU_RIEKI_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_KAPPU_RIEKI_AVG']);
        $CHU_SYAIN['CHU_TOUROKU_RIEKI_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_TOUROKU_RIEKI_AVG']);
        $CHU_SYAIN['CHU_UCHIKOMIKIN_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_UCHIKOMIKIN_AVG']);
        $CHU_SYAIN['CHU_URI_GENKA_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_URI_GENKA_AVG']);
        $CHU_SYAIN['CHU_SITADORI_SON_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_SITADORI_SON_AVG']);
        $CHU_SYAIN['CHU_HANBAITESURYO_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_HANBAITESURYO_AVG']);
        $CHU_SYAIN['CHU_SYOUKAIRYO_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_SYOUKAIRYO_AVG']);
        $CHU_SYAIN['CHU_CHUKOSYA_GENRI_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_CHUKOSYA_GENRI_AVG']);
        $CHU_SYAIN['CHU_TOUGETU_GENRI_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_TOUGETU_GENRI_AVG']);
        $CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_AVG'] = $this->FncValueCnv($CHU_SYAIN['CHU_TOUKI_GENKAIRIEKI_AVG']);
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

            $TA_SYAIN['TA_URIAGE_AVG'] = $TA_SYAIN['TA_URIAGE'];
            $TA_SYAIN['TA_SYARYOU_RIEKI_AVG'] = $TA_SYAIN['TA_SYARYOU_RIEKI'];
            $TA_SYAIN['TA_KASOU_RIEKI_AVG'] = $TA_SYAIN['TA_KASOU_RIEKI'];
            $TA_SYAIN['TA_KAPPU_RIEKI_AVG'] = $TA_SYAIN['TA_KAPPU_RIEKI'];
            $TA_SYAIN['TA_TOUROKU_RIEKI_AVG'] = $TA_SYAIN['TA_TOUROKU_RIEKI'];
            $TA_SYAIN['TA_UCHIKOMIKIN_AVG'] = $TA_SYAIN['TA_UCHIKOMIKIN'];
            $TA_SYAIN['TA_URI_GENKA_AVG'] = $TA_SYAIN['TA_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SIT_DAISU']) != 0) {
                $TA_SYAIN['TA_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_SIT_DAISU']));
            } else {
                $TA_SYAIN['TA_SITADORI_SON_AVG'] = "";
            }
            $TA_SYAIN['TA_HANBAITESURYO_AVG'] = $TA_SYAIN['TA_HANBAITESURYO'];
            $TA_SYAIN['TA_SYOUKAIRYO_AVG'] = $TA_SYAIN['TA_SYOUKAIRYO'];
            $TA_SYAIN['TA_CHUKOSYA_GENRI_AVG'] = $TA_SYAIN['TA_CHUKOSYA_GENRI'];
            $TA_SYAIN['TA_TOUGETU_GENRI_AVG'] = $TA_SYAIN['TA_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_DAISU']) != 0) {
                $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_DAISU']));
            } else {
                $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_AVG'] = "";
            }
        } else {
            $TA_SYAIN['TA_URIAGE_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_URIAGE']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TA_SYARYOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TA_KASOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TA_KAPPU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TA_TOUROKU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TA_UCHIKOMIKIN_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TA_URI_GENKA_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_URI_GENKA']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            if ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SIT_DAISU']) != 0) {
                $TA_SYAIN['TA_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_SIT_DAISU']));
            } else {
                $TA_SYAIN['TA_SITADORI_SON_AVG'] = "";
            }
            $TA_SYAIN['TA_HANBAITESURYO_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_HANBAITESURYO']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TA_SYOUKAIRYO_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TA_CHUKOSYA_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            $TA_SYAIN['TA_TOUGETU_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_DAISU']));
            if ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_DAISU']) != 0) {
                $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_GENKAIRIEKI']) / $this->ClsComFnc->FncNz($TA_SYAIN['TA_TOUKI_DAISU']));
            } else {
                $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_AVG'] = "";
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

        $TA_SYAIN['TA_URIAGE_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_URIAGE_AVG']);
        $TA_SYAIN['TA_SYARYOU_RIEKI_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_SYARYOU_RIEKI_AVG']);
        $TA_SYAIN['TA_KASOU_RIEKI_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_KASOU_RIEKI_AVG']);
        $TA_SYAIN['TA_KAPPU_RIEKI_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_KAPPU_RIEKI_AVG']);
        $TA_SYAIN['TA_TOUROKU_RIEKI_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_TOUROKU_RIEKI_AVG']);
        $TA_SYAIN['TA_UCHIKOMIKIN_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_UCHIKOMIKIN_AVG']);
        $TA_SYAIN['TA_URI_GENKA_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_URI_GENKA_AVG']);
        $TA_SYAIN['TA_SITADORI_SON_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_SITADORI_SON_AVG']);
        $TA_SYAIN['TA_HANBAITESURYO_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_HANBAITESURYO_AVG']);
        $TA_SYAIN['TA_SYOUKAIRYO_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_SYOUKAIRYO_AVG']);
        $TA_SYAIN['TA_CHUKOSYA_GENRI_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_CHUKOSYA_GENRI_AVG']);
        $TA_SYAIN['TA_TOUGETU_GENRI_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_TOUGETU_GENRI_AVG']);
        $TA_SYAIN['TA_TOUKI_GENKAIRIEKI_AVG'] = $this->FncValueCnv($TA_SYAIN['TA_TOUKI_GENKAIRIEKI_AVG']);
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
            $SIN_BUSYO['SIN_URIAGE_AVG'] = $SIN_BUSYO['SIN_URIAGE'];
            $SIN_BUSYO['SIN_SYARYOU_RIEKI_AVG'] = $SIN_BUSYO['SIN_SYARYOU_RIEKI'];
            $SIN_BUSYO['SIN_KASOU_RIEKI_AVG'] = $SIN_BUSYO['SIN_KASOU_RIEKI'];
            $SIN_BUSYO['SIN_KAPPU_RIEKI_AVG'] = $SIN_BUSYO['SIN_KAPPU_RIEKI'];
            $SIN_BUSYO['SIN_TOUROKU_RIEKI_AVG'] = $SIN_BUSYO['SIN_TOUROKU_RIEKI'];
            $SIN_BUSYO['SIN_UCHIKOMIKIN_AVG'] = $SIN_BUSYO['SIN_UCHIKOMIKIN'];
            $SIN_BUSYO['SIN_URI_GENKA_AVG'] = $SIN_BUSYO['SIN_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SIT_DAISU']) != 0) {
                $SIN_BUSYO['SIN_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SIT_DAISU']));
            } else {
                $SIN_BUSYO['SIN_SITADORI_SON_AVG'] = "";
            }
            $SIN_BUSYO['SIN_HANBAITESURYO_AVG'] = $SIN_BUSYO['SIN_HANBAITESURYO'];
            $SIN_BUSYO['SIN_SYOUKAIRYO_AVG'] = $SIN_BUSYO['SIN_SYOUKAIRYO'];
            $SIN_BUSYO['SIN_CHUKOSYA_GENRI_AVG'] = $SIN_BUSYO['SIN_CHUKOSYA_GENRI'];
            $SIN_BUSYO['SIN_TOUGETU_GENRI_AVG'] = $SIN_BUSYO['SIN_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO']) != 0) {
                $SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO']));
            } else {
                $SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_AVG'] = "";
            }
        } else {
            $SIN_BUSYO['SIN_URIAGE_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_URIAGE']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SIN_SYARYOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SIN_KASOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SIN_KAPPU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SIN_TOUROKU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SIN_UCHIKOMIKIN_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SIN_URI_GENKA_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_URI_GENKA']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            if ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SIT_DAISU']) != 0) {
                $SIN_BUSYO['SIN_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SIT_DAISU']));
            } else {
                $SIN_BUSYO['SIN_SITADORI_SON_AVG'] = "";
            }
            $SIN_BUSYO['SIN_HANBAITESURYO_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_HANBAITESURYO']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SIN_SYOUKAIRYO_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SIN_CHUKOSYA_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));
            $SIN_BUSYO['SIN_TOUGETU_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_DAISU']));

            if ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO']) != 0) {
                $SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($SIN_BUSYO['SIN_TOUKI_DAISU_BUSYO']));
            } else {
                $SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_AVG'] = "";
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

        $SIN_BUSYO['SIN_URIAGE_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_URIAGE_AVG']);
        $SIN_BUSYO['SIN_SYARYOU_RIEKI_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_SYARYOU_RIEKI_AVG']);
        $SIN_BUSYO['SIN_KASOU_RIEKI_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_KASOU_RIEKI_AVG']);
        $SIN_BUSYO['SIN_KAPPU_RIEKI_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_KAPPU_RIEKI_AVG']);
        $SIN_BUSYO['SIN_TOUROKU_RIEKI_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_TOUROKU_RIEKI_AVG']);
        $SIN_BUSYO['SIN_UCHIKOMIKIN_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_UCHIKOMIKIN_AVG']);
        $SIN_BUSYO['SIN_URI_GENKA_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_URI_GENKA_AVG']);
        $SIN_BUSYO['SIN_SITADORI_SON_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_SITADORI_SON_AVG']);
        $SIN_BUSYO['SIN_HANBAITESURYO_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_HANBAITESURYO_AVG']);
        $SIN_BUSYO['SIN_SYOUKAIRYO_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_SYOUKAIRYO_AVG']);
        $SIN_BUSYO['SIN_CHUKOSYA_GENRI_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_CHUKOSYA_GENRI_AVG']);
        $SIN_BUSYO['SIN_TOUGETU_GENRI_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_TOUGETU_GENRI_AVG']);
        $SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_AVG'] = $this->FncValueCnv($SIN_BUSYO['SIN_TOUKI_GENKAIRIEKI_AVG']);
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
            $CHU_BUSYO['CHU_URIAGE_AVG'] = $CHU_BUSYO['CHU_URIAGE'];
            $CHU_BUSYO['CHU_SYARYOU_RIEKI_AVG'] = $CHU_BUSYO['CHU_SYARYOU_RIEKI'];
            $CHU_BUSYO['CHU_KASOU_RIEKI_AVG'] = $CHU_BUSYO['CHU_KASOU_RIEKI'];
            $CHU_BUSYO['CHU_KAPPU_RIEKI_AVG'] = $CHU_BUSYO['CHU_KAPPU_RIEKI'];
            $CHU_BUSYO['CHU_TOUROKU_RIEKI_AVG'] = $CHU_BUSYO['CHU_TOUROKU_RIEKI'];
            $CHU_BUSYO['CHU_UCHIKOMIKIN_AVG'] = $CHU_BUSYO['CHU_UCHIKOMIKIN'];
            $CHU_BUSYO['CHU_URI_GENKA_AVG'] = $CHU_BUSYO['CHU_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SIT_DAISU']) != 0) {
                $CHU_BUSYO['CHU_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SIT_DAISU']));
            } else {
                $CHU_BUSYO['CHU_SITADORI_SON_AVG'] = "";
            }
            $CHU_BUSYO['CHU_HANBAITESURYO_AVG'] = $CHU_BUSYO['CHU_HANBAITESURYO'];
            $CHU_BUSYO['CHU_SYOUKAIRYO_AVG'] = $CHU_BUSYO['CHU_SYOUKAIRYO'];
            $CHU_BUSYO['CHU_CHUKOSYA_GENRI_AVG'] = $CHU_BUSYO['CHU_CHUKOSYA_GENRI'];
            $CHU_BUSYO['CHU_TOUGETU_GENRI_AVG'] = $CHU_BUSYO['CHU_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO']) != 0) {
                $CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO']));
            } else {
                $CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_AVG'] = "";
            }
        } else {
            $CHU_BUSYO['CHU_URIAGE_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_URIAGE']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['CHU_SYARYOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['CHU_KASOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['CHU_KAPPU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['CHU_TOUROKU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['CHU_UCHIKOMIKIN_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['CHU_URI_GENKA_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_URI_GENKA']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            if ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SIT_DAISU']) != 0) {
                $CHU_BUSYO['CHU_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SIT_DAISU']));
            } else {
                $CHU_BUSYO['CHU_SITADORI_SON_AVG'] = "";
            }
            $CHU_BUSYO['CHU_HANBAITESURYO_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_HANBAITESURYO']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['CHU_SYOUKAIRYO_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['CHU_CHUKOSYA_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));
            $CHU_BUSYO['CHU_TOUGETU_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_DAISU']));

            if ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO']) != 0) {
                $CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($CHU_BUSYO['CHU_TOUKI_DAISU_BUSYO']));
            } else {
                $CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_AVG'] = "";
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

        $CHU_BUSYO['CHU_URIAGE_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_URIAGE_AVG']);
        $CHU_BUSYO['CHU_SYARYOU_RIEKI_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_SYARYOU_RIEKI_AVG']);
        $CHU_BUSYO['CHU_KASOU_RIEKI_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_KASOU_RIEKI_AVG']);
        $CHU_BUSYO['CHU_KAPPU_RIEKI_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_KAPPU_RIEKI_AVG']);
        $CHU_BUSYO['CHU_TOUROKU_RIEKI_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_TOUROKU_RIEKI_AVG']);
        $CHU_BUSYO['CHU_UCHIKOMIKIN_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_UCHIKOMIKIN_AVG']);
        $CHU_BUSYO['CHU_URI_GENKA_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_URI_GENKA_AVG']);
        $CHU_BUSYO['CHU_SITADORI_SON_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_SITADORI_SON_AVG']);
        $CHU_BUSYO['CHU_HANBAITESURYO_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_HANBAITESURYO_AVG']);
        $CHU_BUSYO['CHU_SYOUKAIRYO_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_SYOUKAIRYO_AVG']);
        $CHU_BUSYO['CHU_CHUKOSYA_GENRI_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_CHUKOSYA_GENRI_AVG']);
        $CHU_BUSYO['CHU_TOUGETU_GENRI_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_TOUGETU_GENRI_AVG']);
        $CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_AVG'] = $this->FncValueCnv($CHU_BUSYO['CHU_TOUKI_GENKAIRIEKI_AVG']);
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
            $TA_BUSYO['TA_URIAGE_AVG'] = $TA_BUSYO['TA_URIAGE'];
            $TA_BUSYO['TA_SYARYOU_RIEKI_AVG'] = $TA_BUSYO['TA_SYARYOU_RIEKI'];
            $TA_BUSYO['TA_KASOU_RIEKI_AVG'] = $TA_BUSYO['TA_KASOU_RIEKI'];
            $TA_BUSYO['TA_KAPPU_RIEKI_AVG'] = $TA_BUSYO['TA_KAPPU_RIEKI'];
            $TA_BUSYO['TA_TOUROKU_RIEKI_AVG'] = $TA_BUSYO['TA_TOUROKU_RIEKI'];
            $TA_BUSYO['TA_UCHIKOMIKIN_AVG'] = $TA_BUSYO['TA_UCHIKOMIKIN'];
            $TA_BUSYO['TA_URI_GENKA_AVG'] = $TA_BUSYO['TA_URI_GENKA'];
            if ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SIT_DAISU']) != 0) {
                $TA_BUSYO['TA_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_SIT_DAISU']));
            } else {
                $TA_BUSYO['TA_SITADORI_SON_AVG'] = "";
            }
            $TA_BUSYO['TA_HANBAITESURYO_AVG'] = $TA_BUSYO['TA_HANBAITESURYO'];
            $TA_BUSYO['TA_SYOUKAIRYO_AVG'] = $TA_BUSYO['TA_SYOUKAIRYO'];
            $TA_BUSYO['TA_CHUKOSYA_GENRI_AVG'] = $TA_BUSYO['TA_CHUKOSYA_GENRI'];
            $TA_BUSYO['TA_TOUGETU_GENRI_AVG'] = $TA_BUSYO['TA_TOUGETU_GENRI'];
            if ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_DAISU_BUSYO']) != 0) {
                $TA_BUSYO['TA_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_DAISU_BUSYO']));
            } else {
                $TA_BUSYO['TA_TOUKI_GENKAIRIEKI_AVG'] = "";
            }
        } else {
            $TA_BUSYO['TA_URIAGE_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_URIAGE']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TA_SYARYOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TA_KASOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TA_KAPPU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TA_TOUROKU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TA_UCHIKOMIKIN_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TA_URI_GENKA_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_URI_GENKA']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            if ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SIT_DAISU']) != 0) {
                $TA_BUSYO['TA_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_SIT_DAISU']));
            } else {
                $TA_BUSYO['TA_SITADORI_SON_AVG'] = "";
            }
            $TA_BUSYO['TA_HANBAITESURYO_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_HANBAITESURYO']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TA_SYOUKAIRYO_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TA_CHUKOSYA_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));
            $TA_BUSYO['TA_TOUGETU_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_DAISU']));

            if ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_DAISU_BUSYO']) != 0) {
                $TA_BUSYO['TA_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_GENKAIRIEKI_BUSYO']) / $this->ClsComFnc->FncNz($TA_BUSYO['TA_TOUKI_DAISU_BUSYO']));
            } else {
                $TA_BUSYO['TA_TOUKI_GENKAIRIEKI_AVG'] = "";
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

        $TA_BUSYO['TA_URIAGE_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_URIAGE_AVG']);
        $TA_BUSYO['TA_SYARYOU_RIEKI_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_SYARYOU_RIEKI_AVG']);
        $TA_BUSYO['TA_KASOU_RIEKI_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_KASOU_RIEKI_AVG']);
        $TA_BUSYO['TA_KAPPU_RIEKI_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_KAPPU_RIEKI_AVG']);
        $TA_BUSYO['TA_TOUROKU_RIEKI_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_TOUROKU_RIEKI_AVG']);
        $TA_BUSYO['TA_UCHIKOMIKIN_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_UCHIKOMIKIN_AVG']);
        $TA_BUSYO['TA_URI_GENKA_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_URI_GENKA_AVG']);
        $TA_BUSYO['TA_SITADORI_SON_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_SITADORI_SON_AVG']);
        $TA_BUSYO['TA_HANBAITESURYO_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_HANBAITESURYO_AVG']);
        $TA_BUSYO['TA_SYOUKAIRYO_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_SYOUKAIRYO_AVG']);
        $TA_BUSYO['TA_CHUKOSYA_GENRI_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_CHUKOSYA_GENRI_AVG']);
        $TA_BUSYO['TA_TOUGETU_GENRI_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_TOUGETU_GENRI_AVG']);
        $TA_BUSYO['TA_TOUKI_GENKAIRIEKI_AVG'] = $this->FncValueCnv($TA_BUSYO['TA_TOUKI_GENKAIRIEKI_AVG']);
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
        $SIN_TOTAL['SIN_URIAGE_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_URIAGE']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SIN_SYARYOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SIN_KASOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SIN_KAPPU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SIN_TOUROKU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SIN_UCHIKOMIKIN_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SIN_URI_GENKA_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_URI_GENKA']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        if ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SIT_DAISU']) != 0) {
            $SIN_TOTAL['SIN_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SITADORI_SON']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SIT_DAISU']));
        } else {
            $SIN_TOTAL['SIN_SITADORI_SON_AVG'] = "";
        }
        $SIN_TOTAL['SIN_HANBAITESURYO_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_HANBAITESURYO']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SIN_SYOUKAIRYO_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SIN_CHUKOSYA_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        $SIN_TOTAL['SIN_TOUGETU_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_DAISU']));
        if ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUKI_DAISU_TOTAL']) != 0) {
            $SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_TOTAL']) / $this->ClsComFnc->FncNz($SIN_TOTAL['SIN_TOUKI_DAISU_TOTAL']));
        } else {
            $SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_AVG'] = "";
        }

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

        $SIN_TOTAL['SIN_URIAGE_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_URIAGE_AVG']);
        $SIN_TOTAL['SIN_SYARYOU_RIEKI_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_SYARYOU_RIEKI_AVG']);
        $SIN_TOTAL['SIN_KASOU_RIEKI_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_KASOU_RIEKI_AVG']);
        $SIN_TOTAL['SIN_KAPPU_RIEKI_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_KAPPU_RIEKI_AVG']);
        $SIN_TOTAL['SIN_TOUROKU_RIEKI_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_TOUROKU_RIEKI_AVG']);
        $SIN_TOTAL['SIN_UCHIKOMIKIN_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_UCHIKOMIKIN_AVG']);
        $SIN_TOTAL['SIN_URI_GENKA_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_URI_GENKA_AVG']);
        $SIN_TOTAL['SIN_SITADORI_SON_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_SITADORI_SON_AVG']);
        $SIN_TOTAL['SIN_HANBAITESURYO_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_HANBAITESURYO_AVG']);
        $SIN_TOTAL['SIN_SYOUKAIRYO_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_SYOUKAIRYO_AVG']);
        $SIN_TOTAL['SIN_CHUKOSYA_GENRI_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_CHUKOSYA_GENRI_AVG']);
        $SIN_TOTAL['SIN_TOUGETU_GENRI_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_TOUGETU_GENRI_AVG']);
        $SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_AVG'] = $this->FncValueCnv($SIN_TOTAL['SIN_TOUKI_GENKAIRIEKI_AVG']);
    }

    public function GroupFooter8_Format($SIN_TOTAL)
    {
        //----新車-----
        if ($this->intpattern == 0) {
            return FALSE;
        } elseif ($this->ClsComFnc->FncNz(rtrim($SIN_TOTAL['SIN_DAISU'])) == 0) {
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
        $CHU_TOTAL['CHU_URIAGE_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_URIAGE']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_SYARYOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_KASOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_KAPPU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_TOUROKU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_UCHIKOMIKIN_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_URI_GENKA_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_URI_GENKA']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        if ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SIT_DAISU']) != 0) {
            $CHU_TOTAL['CHU_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SITADORI_SON']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SIT_DAISU']));
        } else {
            $CHU_TOTAL['CHU_SITADORI_SON_AVG'] = "";
        }
        $CHU_TOTAL['CHU_HANBAITESURYO_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_HANBAITESURYO']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_SYOUKAIRYO_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_CHUKOSYA_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_TOUGETU_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_DAISU']));
        $CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_TOTAL']) / $this->ClsComFnc->FncNz($CHU_TOTAL['CHU_TOUKI_DAISU_TOTAL']));

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

        $CHU_TOTAL['CHU_URIAGE_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_URIAGE_AVG']);
        $CHU_TOTAL['CHU_SYARYOU_RIEKI_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_SYARYOU_RIEKI_AVG']);
        $CHU_TOTAL['CHU_KASOU_RIEKI_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_KASOU_RIEKI_AVG']);
        $CHU_TOTAL['CHU_KAPPU_RIEKI_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_KAPPU_RIEKI_AVG']);
        $CHU_TOTAL['CHU_TOUROKU_RIEKI_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_TOUROKU_RIEKI_AVG']);
        $CHU_TOTAL['CHU_UCHIKOMIKIN_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_UCHIKOMIKIN_AVG']);
        $CHU_TOTAL['CHU_URI_GENKA_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_URI_GENKA_AVG']);
        $CHU_TOTAL['CHU_SITADORI_SON_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_SITADORI_SON_AVG']);
        $CHU_TOTAL['CHU_HANBAITESURYO_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_HANBAITESURYO_AVG']);
        $CHU_TOTAL['CHU_SYOUKAIRYO_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_SYOUKAIRYO_AVG']);
        $CHU_TOTAL['CHU_CHUKOSYA_GENRI_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_CHUKOSYA_GENRI_AVG']);
        $CHU_TOTAL['CHU_TOUGETU_GENRI_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_TOUGETU_GENRI_AVG']);
        $CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_AVG'] = $this->FncValueCnv($CHU_TOTAL['CHU_TOUKI_GENKAIRIEKI_AVG']);
    }

    public function GroupFooter9_Format($CHU_TOTAL)
    {
        //----中古車-----
        if ($this->intpattern == 0) {
            return FALSE;
        } elseif ($this->ClsComFnc->FncNz(rtrim($CHU_TOTAL['CHU_DAISU'])) == 0) {
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
        $TA_TOTAL['TA_URIAGE_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_URIAGE']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_SYARYOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_SYARYOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_KASOU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_KASOU_RIEKI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_KAPPU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_KAPPU_RIEKI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_TOUROKU_RIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_TOUROKU_RIEKI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_UCHIKOMIKIN_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_UCHIKOMIKIN']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_URI_GENKA_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_URI_GENKA']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        if ($this->ClsComFnc->FncNz($TA_TOTAL['TA_SIT_DAISU']) != 0) {
            $TA_TOTAL['TA_SITADORI_SON_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_SITADORI_SON']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_SIT_DAISU']));
        } else {
            $TA_TOTAL['TA_SITADORI_SON_AVG'] = "";
        }
        $TA_TOTAL['TA_HANBAITESURYO_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_HANBAITESURYO']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_SYOUKAIRYO_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_SYOUKAIRYO']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_CHUKOSYA_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_CHUKOSYA_GENRI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_TOUGETU_GENRI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_TOUGETU_GENRI']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_DAISU']));
        $TA_TOTAL['TA_TOUKI_GENKAIRIEKI_AVG'] = (int) ($this->ClsComFnc->FncNz($TA_TOTAL['TA_TOUKI_GENKAIRIEKI_TOTAL']) / $this->ClsComFnc->FncNz($TA_TOTAL['TA_TOUKI_DAISU_TOTAL']));

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

        $TA_TOTAL['TA_URIAGE_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_URIAGE_AVG']);
        $TA_TOTAL['TA_SYARYOU_RIEKI_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_SYARYOU_RIEKI_AVG']);
        $TA_TOTAL['TA_KASOU_RIEKI_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_KASOU_RIEKI_AVG']);
        $TA_TOTAL['TA_KAPPU_RIEKI_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_KAPPU_RIEKI_AVG']);
        $TA_TOTAL['TA_TOUROKU_RIEKI_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_TOUROKU_RIEKI_AVG']);
        $TA_TOTAL['TA_UCHIKOMIKIN_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_UCHIKOMIKIN_AVG']);
        $TA_TOTAL['TA_URI_GENKA_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_URI_GENKA_AVG']);
        $TA_TOTAL['TA_SITADORI_SON_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_SITADORI_SON_AVG']);
        $TA_TOTAL['TA_HANBAITESURYO_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_HANBAITESURYO_AVG']);
        $TA_TOTAL['TA_SYOUKAIRYO_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_SYOUKAIRYO_AVG']);
        $TA_TOTAL['TA_CHUKOSYA_GENRI_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_CHUKOSYA_GENRI_AVG']);
        $TA_TOTAL['TA_TOUGETU_GENRI_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_TOUGETU_GENRI_AVG']);
        $TA_TOTAL['TA_TOUKI_GENKAIRIEKI_AVG'] = $this->FncValueCnv($TA_TOTAL['TA_TOUKI_GENKAIRIEKI_AVG']);
    }

    public function GroupFooter3_Format($TA_TOTAL)
    {
        //----他ﾁｬﾝﾈﾙ-----
        if ($this->intpattern == 0) {
            return FALSE;
        } elseif ($this->ClsComFnc->FncNz(rtrim($TA_TOTAL['TA_DAISU'])) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    //**********************************************************************
    //処 理 名：チェックリストボタン押下
    //関 数 名：checklist_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：限界利益チェックリストを作成する
    //**********************************************************************
    public function checklistClick()
    {
        $result = array();
        $postArr = array();
        $intState = 0;
        $lngOutCnt = 0;
        try {
            $postArr = $_POST['data'];
            $cboYM = $_POST['data']['cboYM'];
            $strBusyoF = $_POST['data']['txtBusyoCDFrom'];
            $strBusyoT = $_POST['data']['txtBusyoCDTo'];
            $intAuth = $_POST['data']['intAuth'];
            //ログ管理
            $intState = 9;

            $this->FrmGENRILISTKRSS = new FrmGENRILISTKRSS();
            $result = $this->FrmGENRILISTKRSS->fncGenriIchiran($postArr['intAuth'], str_replace("/", "", $postArr['cboYM']), $postArr['txtBusyoCDFrom'], $postArr['txtBusyoCDTo'], str_replace("cmd", "", "cmd003"));

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $lngOutCnt = count((array) $result['data']);

            $USERID = $this->FrmGENRILISTKRSS->GS_LOGINUSER['strUserID'];
            if (count((array) $result['data']) > 0) {
                // include_once dirname(__DIR__) . "/Component/Classes/PHPExcel.php";
                $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
                $tmpPath2 = "webroot/files/KRSS/";
                $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
                $file = $tmpPath . "車両限界利益チェックリスト_" . $USERID . ".xlsx";

                if (!file_exists($tmpPath)) {
                    if (!mkdir($tmpPath, 0777, TRUE)) {
                        $result["data"] = "Execl Error";
                        throw new \Exception($result["data"]);
                    }
                }

                //エクセルのテンプレートが保存されている場所を取得
                $strTemplatePath = $this->ClsComFnc->FncGetPath("ExcelLayoutPath");
                $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath . "KRSS/FrmGENRILISTKRSSTemplate2.xlsx";
                //テンプレートファイルの存在確認
                if (file_exists($strTemplatePath) == FALSE) {
                    $result["data"] = "EXCELテンプレートが見つかりません！";
                    throw new \Exception($result["data"]);
                }

                $path_rpxTopdf = dirname(__DIR__);
                include_once $path_rpxTopdf . '/Component/tcpdf/KRSS/FrmGENRILISTKRSS.inc';
                $ExcelData = $result['data'];
                $Curernt_SYAIN = "";
                $Last_SYAIN = "";

                $objReader = IOFactory::createReader('Xlsx');
                $objPHPExcel = $objReader->load($strTemplatePath);
                $objPHPExcel->setActiveSheetIndex(0);
                $objActSheet = $objPHPExcel->getActiveSheet();

                $objActSheet->setCellValue('E' . 2, substr($ExcelData[0]['TODAY'], 0, 4) . "年" . substr($ExcelData[0]['TODAY'], 4, 2) . "月度");

                //the style of border.
                $styleArrayOut = array('borders' => array('outline' => array('borderStyle' => Border::BORDER_MEDIUM)));
                $styleArrayIn = array('borders' => array('inside' => array('borderStyle' => Border::BORDER_THIN)));

                //start line .
                $j = 7;

                $i = $j;
                $Curernt_SYAIN = $ExcelData[0]['ATUKAI_SYAIN'];
                foreach ((array) $ExcelData as $key => $value) {
                    $Curernt_SYAIN = $value['ATUKAI_SYAIN'];
                    $this->Detail_BeforePrint($value);
                    foreach ($checklist as $key1 => $value1) {
                        if ($value[$key1] != "") {
                            if ($value[$key1] == '0') {
                                $value[$key1] = "";
                            }
                            $objActSheet->setCellValue($value1 . $j, $value[$key1]);
                        }
                    }
                    if ($Last_SYAIN != "" && $Curernt_SYAIN != $Last_SYAIN) {
                        $objActSheet->getStyle('A' . $i . ':' . 'T' . ($j - 1))->applyFromArray($styleArrayIn);
                        $objActSheet->getStyle('A' . $i . ':' . 'T' . ($j - 1))->applyFromArray($styleArrayOut);
                        $i = $j;
                    }
                    $Last_SYAIN = $Curernt_SYAIN;
                    $j++;
                }
                $objActSheet->getStyle('A' . $i . ':' . 'T' . ($j - 1))->applyFromArray($styleArrayIn);
                $objActSheet->getStyle('A' . $i . ':' . 'T' . ($j - 1))->applyFromArray($styleArrayOut);

                $objActSheet->getStyle('G7:' . 'T' . ($j - 1))->getNumberFormat()->setFormatCode("#,###");

                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
                $objWriter->save($file);
                $result['data'] = "files/KRSS/" . "車両限界利益チェックリスト_" . $USERID . ".xlsx";
            }
            //ログ管理
            $intState = 1;
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //ログ管理 Start
        if ($intState != 0) {
            //$intState!=0の場合、ログ管理テーブルに登録
            $this->ClsLogControl->fncLogEntry("frmGENRILIST_Excel", $intState, $lngOutCnt, $cboYM, $strBusyoF, $strBusyoT);
        }
        //ログ管理 End
        $this->fncReturn($result);
    }

}
