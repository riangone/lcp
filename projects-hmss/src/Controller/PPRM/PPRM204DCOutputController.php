<?php
/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM204DCOutput;
use App\Model\PPRM\Component\clsSQLforPrint;
use App\Model\PPRM\Component\ClsComFncPprm;
use App\Model\PPRM\Component\ClsSyounin;

//20170926 YIN INS S
//20180718 YIN DEL S
// App::uses('ClsComFncComponent', 'Controller/Component');
//20180718 YIN DEL E
//20170926 YIN INS E

class PPRM204DCOutputController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $result;
    public $Session;
    private $SessionList;
    public $ClsComFncPprm;
    // public $ClsComFnc;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    private function Session($key)
    {
        $this->Session = $this->request->getSession();
        $this->SessionList[$key] = $this->Session->read($key);
        if ($this->SessionList[$key] == null) {
            $this->Session->write($key, "");
        }
        return $this->SessionList[$key];
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM204DCOutput_layout';
        $this->render('/PPRM/PPRM204DCOutput/index', $layout);
    }

    // '**********************************************************************
    // '処 理 名：日締帳票生成
    // '関 数 名：pdfPrintJimu
    // '引    数：なし
    // '戻 り 値：なし
    // '処理説明：日締帳票を生成する
    // '**********************************************************************
    public function pdfPrintJimu()
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
            $this->Session = $this->request->getSession();

            $pdfSql = new clsSQLforPrint();
            $DB_Conn = $pdfSql->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            //トランザクション開始
            $pdfSql->Do_transaction();
            $r = $pdfSql->subJimuInit("DC_Output", $this->request->clientIp(), date('YmdHis'), $this->Session->read('login_user'), $this->Session->read('SyainNM'), $postData['txtJHjmNO']);
            if (!$r['result']) {
                throw new \Exception($r['data']);
            }
            if ($postData['check'] == "all") {
                $insAll = $pdfSql->insWkAll();
                if (!$insAll['result']) {
                    throw new \Exception($insAll['data']);
                }
            } else {
                //20171011 YIN UPD S
                // if ($postData['check'] == "01")
                if ($postData['suitoEig'] == 'true' || $postData['cardMei'] == 'true' || $postData['sonotaMei'] == 'true')
                //20171011 YIN UPD E
                {
                    $ins01 = $pdfSql->insWkF01();
                    if (!$ins01['result']) {
                        throw new \Exception($ins01['data']);
                    }
                }
                //20171011 YIN UPD S
                // if ($postData['check'] == "03")
                if ($postData['suitoEig'] == 'true' || $postData['cardMei'] == 'true')
                //20171011 YIN UPD E
                {
                    $ins03 = $pdfSql->insWkF03();
                    if (!$ins03['result']) {
                        throw new \Exception($ins03['data']);
                    }
                }
                //20171011 YIN UPD S
                // if ($postData['check'] == "05")
                if ($postData['shiireMei'] == 'true')
                //20171011 YIN UPD E
                {
                    $ins05 = $pdfSql->insWkF05();
                    if (!$ins05['result']) {
                        throw new \Exception($ins05['data']);
                    }
                }
                //20171011 YIN UPD S
                // if ($postData['check'] == "07")
                if ($postData['furikaeMei'] == 'true')
                //20171011 YIN UPD E
                {
                    //20171011 YIN UPD S
                    // $ins07 = $pdfSql -> insWkF05();
                    $ins07 = $pdfSql->insWkF07();
                    //20171011 YIN UPD E
                    if (!$ins07['result']) {
                        throw new \Exception($ins07['data']);
                    }
                }
                $sqlUpd = $pdfSql->updWkTenpoDenpyo();
                if (!$sqlUpd['result']) {
                    throw new \Exception($sqlUpd['data']);
                }
            }

            $rpx_file_names = array();
            $datas = array();
            $print = false;
            //日締出力帳票一覧
            if ($postData['check'] == "all") {
                $objds = $pdfSql->fncCreatHijimeIchiranSQL();
                if (!$objds['result']) {
                    throw new \Exception($objds['data']);
                }
                if ($objds['row'] <> 0) {
                    $rpx_file_names['rptHijimeIchiran'] = $data_fields_HijimeIchiran;
                    $arr = $this->dealData($objds, $postData, "rptHijimeIchiran");
                    $datas['rptHijimeIchiran']['data'] = $arr['data'][0];
                    $datas['rptHijimeIchiran']['mode'] = '0';
                    $print = true;
                }
            }
            //現金出納帳（営業）金種表
            if ($postData['suitoEigKsy'] == 'true') {
                $objds = $pdfSql->fncCreatEigyoKinshuDataSet();
                if (count((array) $objds['data']) <> 0) {
                    $rpx_file_names['rptGenkinSuitochoEigyoKinshu'] = $data_fields_GenkinSuitochoEigyoKinshu;
                    $datas['rptGenkinSuitochoEigyoKinshu'] = $this->dealData($objds, $postData, "rptGenkinSuitochoEigyoKinshu");
                    $datas['rptGenkinSuitochoEigyoKinshu']['mode'] = '5';
                    $print = true;
                }
            }
            //現金出納帳（営業）
            if ($postData['suitoEig'] == 'true') {
                $objds = $pdfSql->fncCreatEigyoGenkinSQL();
                if ($objds['row'] <> 0) {
                    $rpx_file_names['rptGenkinSuitochoEigyo'] = $data_fields_GenkinSuitochoEigyo;
                    $datas['rptGenkinSuitochoEigyo'] = $this->dealData($objds, $postData, "rptGenkinSuitochoEigyo");
                    $datas['rptGenkinSuitochoEigyo']['mode'] = '12';
                    $print = true;
                }
            }
            //カード伝票明細一覧表
            if ($postData['cardMei'] == 'true') {
                //入金
                $objds = $pdfSql->fncCreatCardMeisaiNyuSQL();
                if ($objds['row'] <> 0) {
                    $rpx_file_names['rptCardMeisaiNyukinIchiran'] = $data_fields_CardMeisaiNyukinIchiran;
                    $datas['rptCardMeisaiNyukinIchiran'] = $this->dealData($objds, $postData, "rptCardMeisaiNyukinIchiran");
                    $datas['rptCardMeisaiNyukinIchiran']['mode'] = '13';
                    $print = true;
                }
                //振替
                $objds = $pdfSql->fncCreatCardMeisaiFriSQL();
                if ($objds['row'] <> 0) {
                    $rpx_file_names['rptCardMeisaiFurikaeIchiran'] = $data_fields_CardMeisaiFurikaeIchiran;
                    $datas['rptCardMeisaiFurikaeIchiran'] = $this->dealData($objds, $postData, "rptCardMeisaiFurikaeIchiran");
                    $datas['rptCardMeisaiFurikaeIchiran']['mode'] = '13';
                    $print = true;
                }
            }
            //仕入伝票明細一覧表
            if ($postData['shiireMei'] == 'true') {
                $objds = $pdfSql->fncCreatShiireMeisaiSQL();
                if ($objds['row'] <> 0) {
                    $rpx_file_names['rptShiireMeisaiIchiran'] = $data_fields_ShiireMeisaiIchiran;
                    $datas['rptShiireMeisaiIchiran'] = $this->dealData($objds, $postData, "rptShiireMeisaiIchiran");
                    $datas['rptShiireMeisaiIchiran']['mode'] = '12';
                    $print = true;
                }
            }
            //振替伝票明細一覧表
            if ($postData['furikaeMei'] == 'true') {
                $objds = $pdfSql->fncCreatFurikaeMeisaiSQL();
                if ($objds['row'] <> 0) {
                    $rpx_file_names['rptFurikaeMeisaiIchiran'] = $data_fields_FurikaeMeisaiIchiran;
                    $datas['rptFurikaeMeisaiIchiran'] = $this->dealData($objds, $postData, "rptFurikaeMeisaiIchiran");
                    $datas['rptFurikaeMeisaiIchiran']['mode'] = '12';
                    $print = true;
                }
            }
            //その他伝票明細一覧表
            if ($postData['sonotaMei'] == 'true') {
                $objds = $pdfSql->fncCreatSonotaMeisaiSQL();
                if ($objds['row'] <> 0) {
                    $rpx_file_names['rptSonotaMeisaiIchiran'] = $data_fields_SonotaMeisaiIchiran;
                    $datas['rptSonotaMeisaiIchiran'] = $this->dealData($objds, $postData, "rptSonotaMeisaiIchiran");
                    $datas['rptSonotaMeisaiIchiran']['mode'] = '13';
                    $print = true;
                }
            }
            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf();

            if ($print) {
                $this->result = array(
                    'result' => true,
                    'data' => 'data',
                    'flag' => 'true',
                    'msg' => 'true',
                    'reports' => $pdfPath
                );
            } else {
                $this->result = array(
                    'result' => true,
                    'data' => 'nodata',
                );
            }
            $finalRes = $pdfSql->subJimuFinal();
            if (!$finalRes['result']) {
                throw new \Exception($finalRes['data']);
            }
            $pdfSql->Do_commit();
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $pdfSql->Do_rollback();
        }
        if (isset($pdfSql->conn_orl)) {
            $pdfSql->Do_close();
            unset($pdfSql->conn_orl);
        }

        $this->fncReturn($this->result);
    }

    // '**********************************************************************
    // '処 理 名：整備帳票生成
    // '関 数 名：pdfPrintSeibi
    // '引    数：なし
    // '戻 り 値：なし
    // '処理説明：整備帳票を生成する
    // '**********************************************************************
    public function pdfPrintSeibi()
    {
        $postData = $_POST["data"]["request"];
        try {
            include_once "tcpdf/rpx_to_pdf.php";
            include_once 'tcpdf/rptSeibinippoMaineMain.inc';
            include_once 'tcpdf/rptUriageMeisaiIchiranMain.inc';
            include_once 'tcpdf/rptGaichuKensyuIchiran.inc';
            $this->Session = $this->request->getSession();
            $clsSQL = new clsSQLforPrint();
            $clsSQL->subSeibiInit($this->Session->read('login_user'), $this->Session->read('SyainNM'), $postData['tenpoCD'], $postData['sUriageDate']);
            $rpx_file_names = array();
            $datas = array();
            $print = false;
            if ($postData['allCheck'] == "true" || $postData['chkSeibiNik'] == "true" || $postData['chkUriMei'] == "true") {
                $objdscom = $clsSQL->fncCreatSeibiNippoSQL(0);
                if (!$objdscom['result']) {
                    throw new \Exception($objdscom['data']);
                }
            }

            //整備日報（日計）
            if ($postData['allCheck'] == "true" || $postData['chkSeibiNik'] == "true") {
                if ($objdscom['row'] <> 0) {
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
            }
            //整備日報（月計）
            if ($postData['allCheck'] == "true" || $postData['chkSeibiGek'] == "true") {
                $objds = $clsSQL->fncCreatSeibiNippoSQL(1);
                if ($objds['row'] <> 0) {
                    $arr = $this->GetSeibiData($clsSQL, $objds);
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
            }

            //売上明細一覧表
            if ($postData['allCheck'] == "true" || $postData['chkUriMei'] == "true") {
                //20170926 YIN INS S
                //20180718 YIN DEL S
                // $ClsComFncComponent = new ClsComFncComponent();
                //20180718 YIN DEL E
                //20170926 YIN INS E
                $arr = array();
                if ($objdscom['row'] <> 0) {
                    $arr['rptUriageMeisaiIchiran'] = $objdscom['data'];
                    $postData['sUriageDate'] = str_replace("/", "", $postData['sUriageDate']);
                    $objds1 = $clsSQL->fncCreatUriMeisaiSQL($postData['tenpoCD'], $postData['sUriageDate'], $postData['sUriageDate']);
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
                        foreach ((array) $objds1['data'] as $key => $value) {
                            if ($value['YUMUKBN'] == 0) {
                                $YUDAISU = $YUDAISU + $this->ClsComFnc->FncNz($value['DAISU']);
                                $YUKOCURI = $YUKOCURI + $this->ClsComFnc->FncNz($value['KOC_URI']);
                                $YUBUHURI = $YUBUHURI + $this->ClsComFnc->FncNz($value['BUH_URI']);
                                $YUGACURI = $YUGACURI + $this->ClsComFnc->FncNz($value['GAC_URI']);
                                $YUURITTL = $YUURITTL + $this->ClsComFnc->FncNz($value['URI_TTL']);
                                $YUKOCGEN = $YUKOCGEN + $this->ClsComFnc->FncNz($value['KOC_GEN']);
                                $YUBUHGEN = $YUBUHGEN + $this->ClsComFnc->FncNz($value['BUH_GEN']);
                                $YUGACGEN = $YUGACGEN + $this->ClsComFnc->FncNz($value['GAC_GEN']);
                                $YUGENTTL = $YUGENTTL + $this->ClsComFnc->FncNz($value['GEN_TTL']);
                                $YUARARITTL = $YUARARITTL + $this->ClsComFnc->FncNz($value['ARARI_TTL']);
                                $YUNEBTTL = $YUNEBTTL + $this->ClsComFnc->FncNz($value['NEB_TTL']);
                            } else {
                                $MUDAISU = $MUDAISU + $this->ClsComFnc->FncNz($value['DAISU']);
                                $MUKOCURI = $MUKOCURI + $this->ClsComFnc->FncNz($value['KOC_URI']);
                                $MUBUHURI = $MUBUHURI + $this->ClsComFnc->FncNz($value['BUH_URI']);
                                $MUGACURI = $MUGACURI + $this->ClsComFnc->FncNz($value['GAC_URI']);
                                $MUURITTL = $MUURITTL + $this->ClsComFnc->FncNz($value['URI_TTL']);
                                $MUKOCGEN = $MUKOCGEN + $this->ClsComFnc->FncNz($value['KOC_GEN']);
                                $MUBUHGEN = $MUBUHGEN + $this->ClsComFnc->FncNz($value['BUH_GEN']);
                                $MUGACGEN = $MUGACGEN + $this->ClsComFnc->FncNz($value['GAC_GEN']);
                                $MUGENTTL = $MUGENTTL + $this->ClsComFnc->FncNz($value['GEN_TTL']);
                                $MUARARITTL = $MUARARITTL + $this->ClsComFnc->FncNz($value['ARARI_TTL']);
                                $MUNEBTTL = $MUNEBTTL + $this->ClsComFnc->FncNz($value['NEB_TTL']);
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

                        foreach ((array) $objds1['data'] as $key => $value) {
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
                            $strTorokuNo = $this->ClsComFnc->FncNv($value['RIKUJI_NM'], "  ") . " " . $this->ClsComFnc->FncNv($value['VCLRGTNO_SYU'], " ") . " " . $this->dbc2Sbc(mb_convert_kana($this->ClsComFnc->FncNv($value['VCLRGTNO_KANA'], " "), "Hc")) . " " . $this->ClsComFnc->FncNv($value['VCLRGTNO_REN'], " ");
                            $objds1['data'][$key]['TOROKUNO'] = $strTorokuNo;
                            $objds1['data'][$key]['SHZ_GKU_SUM'] = $SHZ_GKU_SUM;
                            $objds1['data'][$key]['BUH_SEB_TTL_SUM'] = $BUH_SEB_TTL_SUM;
                            $objds1['data'][$key]['TTL_GKU_SUM'] = $TTL_GKU_SUM;
                            $objds1['data'][$key]['SYH_TTL_SUM'] = $SYH_TTL_SUM;
                            //20170926 YIN INS S
                            //20180718 YIN UPD S
                            // $objds1['data'][$key]['SEIKYU_NM'] = $ClsComFncComponent -> FncGetByteString($objds1['data'][$key]['SEIKYU_NM'], 0, 32);
                            $objds1['data'][$key]['SEIKYU_NM'] = $this->ClsComFnc->FncGetByteString($objds1['data'][$key]['SEIKYU_NM'], 0, 32);
                            //20180718 YIN UPD E
                            //20170926 YIN INS E
                        }
                        $arr['rptUriMeisai'] = $objds1['data'];
                    }
                    $objds2 = $clsSQL->fncCreatUriSyohiyoSQL($postData['tenpoCD'], $postData['sUriageDate'], $postData['sUriageDate']);
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
                        foreach ((array) $objds2['data'] as $key => $value) {
                            if ($value['YUMUKBN'] == 0) {
                                $YUHIYOGK = $YUHIYOGK + $this->ClsComFnc->FncNz($value['HIYOUGK']);
                                $YUJIBAI = $YUJIBAI + $this->ClsComFnc->FncNz($value['JIBAI']);
                                $YUJURYO = $YUJURYO + $this->ClsComFnc->FncNz($value['JURYO']);
                                $YUINSHI = $YUINSHI + $this->ClsComFnc->FncNz($value['INSHI']);
                                $YUDAIKO = $YUDAIKO + $this->ClsComFnc->FncNz($value['DAIKO']);
                                $YUDENPYO = $YUDENPYO + $value['YUDENPYOSU'];
                            } else {
                                $MUHIYOGK = $MUHIYOGK + $this->ClsComFnc->FncNz($value['HIYOUGK']);
                                $MUJIBAI = $MUJIBAI + $this->ClsComFnc->FncNz($value['JIBAI']);
                                $MUJURYO = $MUJURYO + $this->ClsComFnc->FncNz($value['JURYO']);
                                $MUINSHI = $MUINSHI + $this->ClsComFnc->FncNz($value['INSHI']);
                                $MUDAIKO = $MUDAIKO + $this->ClsComFnc->FncNz($value['DAIKO']);
                                $MUDENPYO = $MUDENPYO + $value['MUDENPYOSU'];
                            }
                            $DAIKO_SUM = $DAIKO_SUM + $value['DAIKO'];
                            $HIYOUGK_SUM = $HIYOUGK_SUM + $value['HIYOUGK'];
                            $JIBAI_SUM = $JIBAI_SUM + $value['JIBAI'];
                            $JURYO_SUM = $JURYO_SUM + $value['JURYO'];
                            $INSHI_SUM = $INSHI_SUM + $value['INSHI'];
                        }

                        foreach ((array) $objds2['data'] as $key => $value) {
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
                            $strTorokuNo = $this->ClsComFnc->FncNv($value['RIKUJI_NM'], "  ") . $this->ClsComFnc->FncNv($value['VCLRGTNO_SYU'], " ") . $this->dbc2Sbc(mb_convert_kana($this->ClsComFnc->FncNv($value['VCLRGTNO_KANA'], " "), "Hc")) . $this->ClsComFnc->FncNv($value['VCLRGTNO_REN'], " ");
                            $objds2['data'][$key]['TOROKUNO'] = $strTorokuNo;
                            $objds2['data'][$key]['DAIKO_SUM'] = $DAIKO_SUM;
                            $objds2['data'][$key]['HIYOUGK_SUM'] = $HIYOUGK_SUM;
                            $objds2['data'][$key]['JIBAI_SUM'] = $JIBAI_SUM;
                            $objds2['data'][$key]['JURYO_SUM'] = $JURYO_SUM;
                            $objds2['data'][$key]['INSHI_SUM'] = $INSHI_SUM;
                            $objds2['data'][$key]['YUDENPYO'] = $YUDENPYO;
                            $objds2['data'][$key]['MUDENPYO'] = $MUDENPYO;
                            $objds2['data'][$key]['DENPYOSU'] = $YUDENPYO + $MUDENPYO;
                        }
                        $arr['rptUriSyohiyo'] = $objds2['data'];
                    }
                    $objds3 = $clsSQL->fncCreatUriPackSQL($postData['tenpoCD'], $postData['sUriageDate'], $postData['sUriageDate']);
                    if (!$objds3['result']) {
                        throw new \Exception($objds3['data']);
                    }
                    if ($objds3['row'] > 0) {
                        $PACKKIN_SUM = 0;
                        $DENPYOSU = 0;
                        foreach ((array) $objds3['data'] as $key => $value) {
                            $PACKKIN_SUM = $PACKKIN_SUM + $value['PACKKIN'];
                            $DENPYOSU = $DENPYOSU + $value['DENPYOSU'];
                        }
                        foreach ((array) $objds3['data'] as $key => $value) {
                            $objds3['data'][$key]['PACKKIN_SUM'] = $PACKKIN_SUM;
                            $objds3['data'][$key]['DENPYOSU'] = $DENPYOSU;
                        }
                        $arr['rptUriPack'] = $objds3['data'];
                    }

                    $rpx_file_names['rptUriageMeisaiIchiranMain'] = $data_fields_rptUriageMeisaiIchiranMain;
                    $tmp_data = array();
                    $tmp_data['data'][0] = $arr;
                    $tmp_data['mode'] = "14";
                    $datas['rptUriageMeisaiIchiranMain'] = $tmp_data;
                    $print = true;
                }
            }

            //外注検収一覧表
            if ($postData['allCheck'] == "true" || $postData['chkGaichu'] == "true") {
                $objds = $clsSQL->fncGaichuKensyuIchiran();
                if ($objds['row'] <> 0) {
                    $rpx_file_names['rptGaichuKensyuIchiran'] = $data_fields_GaichuKensyuIchiran;
                    $URG_GK_SUM_TOTAL = 0;
                    $HTU_GK_SUM_TOTAL = 0;
                    $KEU_GK_SUM_TOTAL = 0;
                    $GTU_SHZ_GKU_TOTAL = 0;
                    $TOT_SHR_GKU_TOTAL = 0;
                    $GTU_KEU_NO_TOTAL = 0;
                    $GTU_KEU_NO_TOTAL = $objds['row'];
                    foreach ((array) $objds['data'] as $key => $value) {
                        $URG_GK_SUM_TOTAL += intval($value['URG_GK_SUM']);
                        $HTU_GK_SUM_TOTAL += intval($value['HTU_GK_SUM']);
                        $KEU_GK_SUM_TOTAL += intval($value['KEU_GK_SUM']);
                        $GTU_SHZ_GKU_TOTAL += intval($value['GTU_SHZ_GKU']);
                        $TOT_SHR_GKU_TOTAL += intval($value['TOT_SHR_GKU']);
                    }
                    foreach ((array) $objds['data'] as $key1 => $value1) {
                        $strTorokuNo = "";
                        if ($objds['data'][$key1]['RIKUJI_NM'] != "" && $objds['data'][$key1]['RIKUJI_NM'] != null) {
                            $strTorokuNo .= $objds['data'][$key1]['RIKUJI_NM'];
                            $strTorokuNo .= $this->ClsComFnc->FncNv($objds['data'][$key1]['VCLRGTNO_SYU'], " ");
                            $VCLRGTNO_KANA = mb_convert_kana($this->ClsComFnc->FncNv($objds['data'][$key1]['VCLRGTNO_KANA'], " "), "Hc");
                            $VCLRGTNO_KANA = $this->dbc2Sbc($VCLRGTNO_KANA);
                            $strTorokuNo .= $VCLRGTNO_KANA;
                            $strTorokuNo .= $this->ClsComFnc->FncNv($objds['data'][$key1]['VCLRGTNO_REN'], " ");
                            //20171010 YIN UPD S
                            // $objds['data'][$key1]['TOUROKU_NO'] = $strTorokuNo;
                        }
                        $objds['data'][$key1]['TOUROKU_NO'] = $strTorokuNo;
                        //20171010 YIN UPD E
                        $objds['data'][$key1]['URG_GK_SUM_TOTAL'] = $URG_GK_SUM_TOTAL;
                        $objds['data'][$key1]['HTU_GK_SUM_TOTAL'] = $HTU_GK_SUM_TOTAL;
                        $objds['data'][$key1]['KEU_GK_SUM_TOTAL'] = $KEU_GK_SUM_TOTAL;
                        $objds['data'][$key1]['GTU_SHZ_GKU_TOTAL'] = $GTU_SHZ_GKU_TOTAL;
                        $objds['data'][$key1]['TOT_SHR_GKU_TOTAL'] = $TOT_SHR_GKU_TOTAL;
                        $objds['data'][$key1]['GTU_KEU_NO_TOTAL'] = $GTU_KEU_NO_TOTAL;
                    }
                    $tmp_data = array();
                    $tmp = array();
                    $data = $objds['data'];
                    array_push($tmp, $data);
                    $tmp_data['data'] = $tmp;
                    $tmp_data['mode'] = "3";

                    $datas['rptGaichuKensyuIchiran'] = $tmp_data;
                    $print = true;
                }
            }

            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf();

            if ($print) {
                $this->result = array(
                    'result' => true,
                    'data' => 'data',
                    'flag' => 'true',
                    'msg' => 'true',
                    'reports' => $pdfPath
                );
            } else {
                $this->result = array(
                    'result' => true,
                    'data' => 'nodata',
                );
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);
    }

    // // * 将字符转换成unicode
    // // * @param string $char 必须是UTF-8字符
    // // * @return int
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
        return preg_replace_callback(
            '/[\x{0020}\x{0020}-\x{7e}]/u',
            function () {
                return '($unicode=$this->char2Unicode(\'\0\')) == 0x0020 ? $this->unicode2Char（0x3000） : (($code=$unicode+0xfee0) > 256 ? $this->unicode2Char($code) : chr($code))';
            },
            $str
        );
    }

    // '**********************************************************************
    // '処 理 名：データの処理
    // '関 数 名：creatReport
    // '引    数：$objds,$postData,$rpxName
    // '戻 り 値：$tmp_data
    // '処理説明：データの処理
    // '**********************************************************************
    public function dealData($objds, $postData, $rpxName = "")
    {
        $syounin = new ClsSyounin();
        $syouninRes = $syounin->SyouninSQL(substr($postData['txtJHjmNO'], 0, 3), "1", $postData['txtJHjmNO']);
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
        $strTantouFlg = NULL;
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
            }
            ;
            if ($strTenchoFlg == "1") {
                $Text['2'] = $tencho;
            }
            ;
            if ($strKachoFlg == "1") {
                $Text['3'] = $kacho;
            }
            ;
            if ($strTantouFlg == "1") {
                $Text['4'] = $tantou;
            }
        }
        $tmp = array();
        if ($rpxName == "rptGenkinSuitochoEigyoKinshu") {
            $ZANDAKA_SHIHEI_SUM = 0;
            $ZANDAKA_KOUKA_SUM = 0;
            $ZANDAKA_KOGITTE_SUM = 0;
            foreach ($objds['data'] as $key => $value) {
                $ZANDAKA_SHIHEI_SUM += intval($value['ZANDAKA_SHIHEI']);
                $ZANDAKA_KOUKA_SUM += intval($value['ZANDAKA_KOUKA']);
                $ZANDAKA_KOGITTE_SUM += intval($value['ZANDAKA_KOGITTE']);
                if ($objds['data'][$key]['HJM_SYR_DTM'] != "" && $objds['data'][$key]['HJM_SYR_DTM'] != null) {
                    $HJM_SYR_DTM = str_replace("/", "", $value['HJM_SYR_DTM']);
                    $HJM_SYR_DTM = str_replace(":", "", $HJM_SYR_DTM);
                    $objds['data'][$key]['HJM_SYR_DTM'] = substr($HJM_SYR_DTM, 0, 4) . "年" . substr($HJM_SYR_DTM, 4, 2) . "月" . substr($HJM_SYR_DTM, 6, 2) . "日" . substr($HJM_SYR_DTM, 8, 3) . "時" . substr($HJM_SYR_DTM, 11, 2) . "分" . substr($HJM_SYR_DTM, 13, 2) . "秒";
                }
            }
            foreach ($objds['data'] as $key1 => $value1) {
                $objds['data'][$key1]['ZANDAKA_SHIHEI_SUM'] = $ZANDAKA_SHIHEI_SUM;
                $objds['data'][$key1]['ZANDAKA_KOUKA_SUM'] = $ZANDAKA_KOUKA_SUM;
                $objds['data'][$key1]['ZANDAKA_KOGITTE_SUM'] = $ZANDAKA_KOGITTE_SUM;
            }
        }
        if ($rpxName == "rptGenkinSuitochoEigyo") {
            $KARIKATA_SUM = 0;
            $KASHIKATA_SUM = 0;
            $INP_DENPY_NO_SUM = 0;
            foreach ($objds['data'] as $key => $value) {
                $KARIKATA_SUM += intval($value['KARIKATA']);
                $KASHIKATA_SUM += intval($value['KASHIKATA']);
                $INP_DENPY_NO_SUM = count($objds['data']);
            }
            foreach ($objds['data'] as $key1 => $value1) {
                $objds['data'][$key1]['KARIKATA_SUM'] = $KARIKATA_SUM;
                $objds['data'][$key1]['KASHIKATA_SUM'] = $KASHIKATA_SUM;
                $objds['data'][$key1]['INP_DENPY_NO_SUM'] = $INP_DENPY_NO_SUM;
            }
        }
        if ($rpxName == "rptCardMeisaiNyukinIchiran" || $rpxName == "rptCardMeisaiFurikaeIchiran") {
            $KARIKATA_TOTAL = 0;
            $KASHIKATA_TOTAL = 0;
            $i = 0;
            $BANK_HD = $objds['data'][0]['BANK_NM_HD'];
            foreach ($objds['data'] as $key => $value) {
                if ($value['BANK_NM_HD'] == $BANK_HD) {
                    $KARIKATA_TOTAL += intval($value['KARIKATA']);
                    $KASHIKATA_TOTAL += intval($value['KASHIKATA']);
                } else {
                    $BANK_HD = $value['BANK_NM_HD'];
                    $objds['data'][$key - 1]['KARIKATA_SUM'] = $KARIKATA_TOTAL;
                    $objds['data'][$key - 1]['KASHIKATA_SUM'] = $KASHIKATA_TOTAL;
                    $objds['data'][$key - 1]['INP_DENPY_NO_SUM'] = $i;

                    $i = 0;
                    $KARIKATA_TOTAL = 0;
                    $KASHIKATA_TOTAL = 0;
                    $KARIKATA_TOTAL += intval($value['KARIKATA']);
                    $KASHIKATA_TOTAL += intval($value['KASHIKATA']);

                }
                $i++;
            }
            $objds['data'][$key]['KARIKATA_SUM'] = $KARIKATA_TOTAL;
            $objds['data'][$key]['KASHIKATA_SUM'] = $KASHIKATA_TOTAL;
            $objds['data'][$key]['INP_DENPY_NO_SUM'] = $i;

        }
        if ($rpxName == "rptShiireMeisaiIchiran") {
            $KARIKATA_TOTAL = 0;
            $KASHIKATA_TOTAL = 0;
            $INP_DENPY_NO_TOTAL = 0;
            $INP_DENPY_NO_TOTAL = count($objds['data']);
            foreach ($objds['data'] as $key => $value) {
                $KARIKATA_TOTAL += intval($value['KARIKATA']);
                $KASHIKATA_TOTAL += intval($value['KASHIKATA']);
            }
            foreach ($objds['data'] as $key1 => $value1) {

                $objds['data'][$key1]['KARIKATA_TOTAL'] = $KARIKATA_TOTAL;
                $objds['data'][$key1]['KASHIKATA_TOTAL'] = $KASHIKATA_TOTAL;
                $objds['data'][$key1]['INP_DENPY_NO_TOTAL'] = $INP_DENPY_NO_TOTAL;
            }
        }
        if ($rpxName == "rptFurikaeMeisaiIchiran") {
            $KEIJO_GK_TOTAL = 0;
            $INP_DENPY_NO_TOTAL = 0;
            $INP_DENPY_NO_TOTAL = count($objds['data']);
            foreach ($objds['data'] as $key => $value) {
                $KEIJO_GK_TOTAL += intval($value['KEIJO_GK']);
            }
            foreach ($objds['data'] as $key1 => $value1) {
                $objds['data'][$key1]['KEIJO_GK_TOTAL'] = $KEIJO_GK_TOTAL;
                $objds['data'][$key1]['INP_DENPY_NO_TOTAL'] = $INP_DENPY_NO_TOTAL;
            }
        }
        if ($rpxName == "rptSonotaMeisaiIchiran") {
            $KARIKATA_TOTAL = 0;
            $KASHIKATA_TOTAL = 0;
            $i = 0;
            $BANK_HD = $objds['data'][0]['BANK_HD'];
            foreach ($objds['data'] as $key => $value) {
                if ($value['BANK_HD'] == $BANK_HD) {
                    $KARIKATA_TOTAL += intval($value['KARIKATA']);
                    $KASHIKATA_TOTAL += intval($value['KASHIKATA']);
                } else {
                    $BANK_HD = $value['BANK_HD'];
                    $objds['data'][$key - 1]['KARIKATA_TOTAL'] = $KARIKATA_TOTAL;
                    $objds['data'][$key - 1]['KASHIKATA_TOTAL'] = $KASHIKATA_TOTAL;
                    $objds['data'][$key - 1]['INP_DENPY_NO_TOTAL'] = $i;
                    $i = 0;
                    $KARIKATA_TOTAL = 0;
                    $KASHIKATA_TOTAL = 0;
                    $KARIKATA_TOTAL += intval($value['KARIKATA']);
                    $KASHIKATA_TOTAL += intval($value['KASHIKATA']);

                }
                $i++;
            }
            $objds['data'][$key]['KARIKATA_TOTAL'] = $KARIKATA_TOTAL;
            $objds['data'][$key]['KASHIKATA_TOTAL'] = $KASHIKATA_TOTAL;
            $objds['data'][$key]['INP_DENPY_NO_TOTAL'] = $i;
        }
        $j = 0;
        $BANK_HD = "";
        if ($rpxName == "rptCardMeisaiNyukinIchiran" || $rpxName == "rptCardMeisaiFurikaeIchiran") {
            $BANK_HD = $objds['data'][0]['BANK_NM_HD'];
        } elseif ($rpxName == "rptSonotaMeisaiIchiran") {
            $BANK_HD = $objds['data'][0]['BANK_HD'];
        }
        foreach ($objds['data'] as $key => $value) {
            $arr = array();
            $arr = $value;
            if ($strTantouFlg == "1") {
                $arr['CreDate'] = $strCreDate;
                $arr['CreName'] = $strCreNAME;
            }
            if ($rpxName == "rptCardMeisaiNyukinIchiran" || $rpxName == "rptCardMeisaiFurikaeIchiran") {
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
            } elseif ($rpxName == "rptSonotaMeisaiIchiran") {
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
            }
            array_push($tmp, $arr);
        }
        $tmp_data = array();
        $tmp_data['data'][0] = $tmp;
        if ($rpxName == "rptGenkinSuitochoEigyoKinshu" || $rpxName == "rptHijimeIchiran") {
            $tmp_data['data'][0][0]['Text'] = $Text;
            $tmp_data['data'][0][0]['Circle'] = $Circle;
        }
        if ($rpxName == "rptGenkinSuitochoEigyo" || $rpxName == "rptShiireMeisaiIchiran" || $rpxName == "rptFurikaeMeisaiIchiran") {
            $i = 1;
            while (($i * 8 - 8) < count($objds['data'])) {
                $tmp_data['data'][0][$i * 8 - 8]['Text'] = $Text;
                $tmp_data['data'][0][$i * 8 - 8]['Circle'] = $Circle;
                $i = $i + 1;
            }
        }

        return $tmp_data;
    }

    // '**********************************************************************
    // '処 理 名：整備データ取得
    // '関 数 名：creatReport
    // '引    数：$clsSQL,$result
    // '戻 り 値：$arr
    // '処理説明：整備データ取得
    // '**********************************************************************
    public function GetSeibiData($clsSQL, $result)
    {
        $arr = array();
        $arr['result'] = true;

        $arr['data']['rptSeibinippoMaine'] = $result['data'];
        $tenpocd = $result['data'][0]['TENPO_CD'];
        $updstr = $result['data'][0]['URIAGEDT_STA'];
        $updend = $result['data'][0]['URIAGEDT_END'];

        //有償売上分
        $result = $clsSQL->fncCreatSeibiYushoSQL($tenpocd, $updstr, $updend);
        if (!$result['result']) {
            $arr['result'] = false;
            $arr['data'] = $result['data'];
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

            $arr['data']['rptSeibiYusho'] = $result['data'];
        }
        //無償売上分
        $result = $clsSQL->fncCreatSeibiMushoSQL($tenpocd, $updstr, $updend);
        if (!$result['result']) {
            $arr['result'] = false;
            $arr['data'] = $result['data'];
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

            $arr['data']['rptSeibiMusho'] = $result['data'];
        }
        //総計
        $result = $clsSQL->fncCreatSeibiSokeiSQL($tenpocd, $updstr, $updend);
        if (!$result['result']) {
            $arr['result'] = false;
            $arr['data'] = $result['data'];
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

            $arr['data']['rptSeibiSokei'] = $result['data'];
        }
        //諸費用
        $result = $clsSQL->fncCreatSeibiSyohiyoDataSet($tenpocd, $updstr, $updend);
        if (!$result['result']) {
            $arr['result'] = false;
            $arr['data'] = $result['data'];
        }
        if ($result['row'] > 0) {
            $arr['data']['rptSeibiSyohiyo'] = $result['data'];
        }
        //前受金
        $result = $clsSQL->fncCreatSeibiMaeukeSQL($tenpocd, $updstr, $updend);
        if (!$result['result']) {
            $arr['result'] = false;
            $arr['data'] = $result['data'];
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

            $arr['data']['rptSeibiMaeuke'] = $result['data'];
        }

        return $arr;
    }

    // '**********************************************************************
    // '処 理 名：店舗名取得
    // '関 数 名：getTenpoNM
    // '処理説明：店舗名を取得する
    // '**********************************************************************
    public function getTenpoNM()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $postData = $_POST["data"]["request"];
        try {
            $this->ClsComFncPprm = new ClsComFncPprm();
            $result = $this->ClsComFncPprm->FncGetBusyoMstValue_ppr($postData["txtSTenpoCD"], TRUE);
            if ($result['result']) {
                $result['data'] = !empty($result['data']) && isset($result['data'][0])
                    ? $this->ClsComFnc->FncNv($result['data'][0])
                    : null;
            } else {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：店舗コード、日締日時取得
    // '関 数 名：getTenpoCDHjmDT
    // '処理説明：店舗コードと日締日時を取得する
    // '**********************************************************************
    public function getTenpoCDHjmDT()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $postData = $_POST["data"]["request"];
        try {
            $PPRM204DCOutput = new PPRM204DCOutput();
            $result = $PPRM204DCOutput->getTenpoCDHjmDT($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
