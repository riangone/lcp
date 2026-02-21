<?php
/**
 * 説明：
 *
 *
 * @author zhenghuiyun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                        FCSDL
 * 20150526           ---                       新規                           FCSDL
 * 20150610           ---                       本部/店舗の判断方法変更            HM
 * 20150611           ---                       抽出条件に「登録日」を追加          HM
 * 20150619           ---                       契約者検索処理を１回に纏める         HM
 * 20150825           ---                       SDH改善要望(20150819)           Yuanjh
 * 20151029			  ---						SDH改善要望(20150914)			  yinhuaiyu
 * 20151102			  ---						SDH改善要望(20150914)			  yinhuaiyu
 * 20151104 		  #2255						SDH改善要望(20151104)			  yinhuaiyu
 * 20160127           #2373                     依頼                           li
 * 20190227           #2870                     依頼                           YIN
 * 20210219           \99.提供資料\20210217\20210217_SDH_ログイン後の仕様変更.xlsx                       依頼                           CI
 * 20220121           機能追加　　　　　　          N6対応                         Sun
 */

namespace App\Controller\SDH;

use App\Controller\AppController;
use App\Model\SDH\SDH01;
use App\Model\SDH\SDH01_08;
use App\Model\SDH\SDH01_02;
use App\Model\SDH\SDH06;
use App\Model\SDH\SDH01_01;
use App\Model\SDH\SDH01_07;
use App\Model\SDH\SDH01_04;
use App\Model\SDH\SDH01_05;
use App\Model\SDH\SDH03;

/**
 * 車検代替判定画面
 * SDHController.
 */
class SDH01Controller extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = true;
    // public $autoRender = false;
    // public $ClsComFnc = '';
    private $tenpo_cd = '';
    private $tenpo_nm = '';
    private $DLRCSRNO = '';
    private $VIN_WMIVDS = '';
    private $VIN_VIS = '';
    public $hansh_cd = '3634';
    private $m_SDH01;
    private $m_SDH01_08;
    private $m_SDH01_02;
    private $m_SDH06;
    private $m_SDH01_01;
    private $m_SDH01_07;
    private $m_SDH01_04;
    private $TimeLine;
    private $m_SDH03;
    private $Session;
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    /**
     * デフォルトで最初に実行される機能.
     */
    public function index()
    {

        for ($i = 1; $i < 8; ++$i) {
            $this->set('hanteinengetu_' . '0' . $i, '');
        }

        for ($i = 1; $i < 8; ++$i) {
            $this->set('result_text_' . '0' . $i, '');
        }

        // Viewファイル呼出し
        $this->render('/SDH/SDH01/index', 'SDH01_layout');
    }

    private $data = array();

    public function get_tenpo()
    {
        //ipアドレス取得
        $ip = $this->request->clientIp();

        //20150610 add Start
        //ログインユーザID取得
        $session = $this->request->getSession();
        $userid = $session->read('login_user');
        //20150610 add End

        //test
        //$ip = "192.168.042.2";
        //test

        $this->m_SDH01 = new SDH01();
        //20150610 update Start
        //$result = $this -> m_SDH01 -> m_select_kyotn_cd($ip);
        $result = $this->m_SDH01->m_select_kyotn_cd($ip, $userid);
        //20150610 update End
        $data_tenpo = $result['data'];
        //ログインユーザが「本部ユーザ」の場合
        if (count((array) $data_tenpo) > 0) {
            //20210219 CI UPD S
            //$this -> data["sdh01_tenpo"] = $data_tenpo[0];
            $data_tenpo[0] = array('KYOTN_CD' => 'honbu');
            $this->data['sdh01_tenpo'] = $data_tenpo[0];
            //20210219 CI UPD E
        }
        //ログインユーザが「一般ユーザ」の場合
        else {
            //20210219 CI UPD S
            // $data_tenpo[0] = array("KYOTN_CD" => "honbu");
            // $this -> data["sdh01_tenpo"] = $data_tenpo[0];
            $data_tenpo[0] = array('KYOTN_CD' => 'yippann');
            $this->data['sdh01_tenpo'] = $data_tenpo[0];
            //20210219 CI UPD E
        }
    }

    public function get_syain($tenpo_cd)
    {
        $this->m_SDH01 = new SDH01();
        $result = $this->m_SDH01->m_select_syain($tenpo_cd);
        $data_sdh01_syain = $result['data'];
        $this->data['sdh01_syain'] = $data_sdh01_syain;
    }

    //20160201 YIN UPD S
    // public function get_hantei_list($tenpo_cd, $nengetu, $con, $con1, $con2, $con3, $tantousya_type = "ES", $tantousya_code = "")
    public function get_hantei_list($tenpo_cd, $nengetu, $con, $con1, $con2, $con3, $con4, $tantousya_type = 'ES', $tantousya_code = '')
    //20160201 YIN UPD E
    {
        // App::uses('SDH01_08', 'Model/SDH');
        $this->m_SDH01_08 = new SDH01_08();
        $result = array();

        switch ($tantousya_type) {
            case 'ES':
                //20160201 YIN UPD S
                // $result = $this -> m_SDH01_08 -> m_select_sdh01_08_tenpozenin($tenpo_cd, $nengetu, $con, $con1, $con2, $con3);
                $result = $this->m_SDH01_08->m_select_sdh01_08_tenpozenin($tenpo_cd, $nengetu, $con, $con1, $con2, $con3, $con4);
                //20160201 YIN UPD E
                break;
            case 'E1':
                //20160201 YIN UPD S
                // $result = $this -> m_SDH01_08 -> m_select_sdh01_08_eigyozenin($tenpo_cd, $nengetu, $con, $con1, $con2, $con3);
                $result = $this->m_SDH01_08->m_select_sdh01_08_eigyozenin($tenpo_cd, $nengetu, $con, $con1, $con2, $con3, $con4);
                //20160201 YIN UPD E
                break;
            case 'E2':
                //20160201 YIN UPD S
                // $result = $this -> m_SDH01_08 -> m_select_sdh01_08_tantousya($tantousya_code, $nengetu, $con, $con1, $con2, $con3);
                $result = $this->m_SDH01_08->m_select_sdh01_08_tantousya($tantousya_code, $nengetu, $con, $con1, $con2, $con3, $con4);
                //20160201 YIN UPD E
                break;
            case 'S':
                //20160201 YIN UPD S
                //$result = $this -> m_SDH01_08 -> m_select_sdh01_08_saabisuzenin($tenpo_cd, $nengetu, $con, $con1, $con2, $con3);
                $result = $this->m_SDH01_08->m_select_sdh01_08_saabisuzenin($tenpo_cd, $nengetu, $con, $con1, $con2, $con3, $con4);
                //20160201 YIN UPD E
                break;
            default:
        }

        $data_sdh01_hantei_list = $result['data'];

        $this->data['sdh01_hantei_list'] = $data_sdh01_hantei_list;
    }

    public function get_keiyakusya($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    {
        // App::uses('SDH01_02', 'Model/SDH');
        // loadComponent('ClsComFnc')
        // $this->ClsComFnc = new ClsComFncComponent();
        $this->loadComponent('ClsComFnc');
        $BRTDT = '';
        $searchID = '';
        $this->m_SDH01_02 = new SDH01_02();
        $result = $this->m_SDH01_02->m_select_keiyakusya($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);
        $data_sdh01_keiyakusya = $result['data'];

        //20150619 Update Start
        //        if (count($data_sdh01_keiyakusya) != 0) {

        //            $result = $this -> m_SDH01_02 -> m_select_all($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);
        //
        ////20150611 add Start
        //            $data_sdh01_keiyakusya[0]["CSRKNANM"] = $this -> ClsComFnc -> FncNv($result["data"][0]["CSRKNANM"]);
        ////20150611 add End/
        //
        //            $data_sdh01_keiyakusya[0]["SEIBETU"] = $this -> ClsComFnc -> FncNv($result["data"][0]["SEIBETU"]);
        //
        //            $data_sdh01_keiyakusya[0]["TOUROKU_NO"] = $this -> ClsComFnc -> FncNv($result["data"][0]["VCLRGTNM_LAND"]) . $this -> ClsComFnc -> FncNv($data_sdh01_keiyakusya[0]["VCLRGTNO_SYU"]) . $this -> ClsComFnc -> FncNv($data_sdh01_keiyakusya[0]["VCLRGTNO_KANA"]) . $this -> ClsComFnc -> FncNv($data_sdh01_keiyakusya[0]["VCLRGTNO_REN"]);
        //
        //            $data_sdh01_keiyakusya[0]['TOUROKU_NO'] = mb_convert_kana($data_sdh01_keiyakusya[0]['TOUROKU_NO'], "Hc");
        //
        //            $data_sdh01_keiyakusya[0]["KNR_STRNM"] = $this -> ClsComFnc -> FncNv($result["data"][0]["KNR_STRNM"]);/
        //
        //            $data_sdh01_keiyakusya[0]["KNR_STRCD"] = $this -> ClsComFnc -> FncNv($result["data"][0]["KNR_STRCD"]);
        //
        //            $data_sdh01_keiyakusya[0]["SRV_SRVSTRNM"] = $this -> ClsComFnc -> FncNv($result["data"][0]["SRV_SRVSTRNM"]);
        //
        //            $data_sdh01_keiyakusya[0]["SRV_SRVSTRCD"] = $this -> ClsComFnc -> FncNv($result["data"][0]["SRV_SRVSTRCD"]);
        //
        //            $data_sdh01_keiyakusya[0]["SYAIN_KNJ_SEI_KNA_MEI"] = $this -> ClsComFnc -> FncNv($result["data"][0]["SYAIN_KNJ_SEI"]) . " " . $this -> ClsComFnc -> FncNv($result["data"][0]["SYAIN_KNJ_MEI"]);
        //
        //            $data_sdh01_keiyakusya[0]["SYAIN_NO"] = $this -> ClsComFnc -> FncNv($result["data"][0]["SYAIN_NO"]);

        $data_sdh01_keiyakusya[0]['TOUROKU_NO'] = $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['VCLRGTNM_LAND']) . $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['VCLRGTNO_SYU']) . $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['VCLRGTNO_KANA']) . $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['VCLRGTNO_REN']);
        $data_sdh01_keiyakusya[0]['TOUROKU_NO'] = mb_convert_kana($data_sdh01_keiyakusya[0]['TOUROKU_NO'], 'Hc');
        //20150619 Update End

        $strFRGMH = '';
        $strVCLIPEDT = '';
        $strNKSIN1_SOKOKM = '';

        //20150610 add Start
        // $strTOU_DT = '';
        //20150610 add End

        if (null != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['FRGMH']) || '' != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['FRGMH'])) {
            $data_sdh01_keiyakusya[0]['FRGMH'] = substr($data_sdh01_keiyakusya[0]['FRGMH'], 0, 4) . '/' . substr($data_sdh01_keiyakusya[0]['FRGMH'], 4, 2);
            $strFRGMH = $data_sdh01_keiyakusya[0]['FRGMH'];
        }
        if (null != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['VCLIPEDT']) || '' != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['VCLIPEDT'])) {
            $data_sdh01_keiyakusya[0]['VCLIPEDT'] = substr($data_sdh01_keiyakusya[0]['VCLIPEDT'], 0, 4) . '/' . substr($data_sdh01_keiyakusya[0]['VCLIPEDT'], 4, 2) . '/' . substr($data_sdh01_keiyakusya[0]['VCLIPEDT'], 6, 2);
            $strVCLIPEDT = substr($data_sdh01_keiyakusya[0]['VCLIPEDT'], 0, 4) . '/' . substr($data_sdh01_keiyakusya[0]['VCLIPEDT'], 5, 2);
        }

        //月平均走行距離
        if (null != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['VCLMTCDS']) || '' != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['VCLMTCDS'])) {
            $strNKSIN_VCLMTCDS = $data_sdh01_keiyakusya[0]['VCLMTCDS'];
        }
        //20150611 Add Start
        if (null != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['KSA_DT']) || '' != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['KSA_DT'])) {
            $strKSA_DT = $data_sdh01_keiyakusya[0]['KSA_DT'];
        }
        if (null != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['KSA_RUN_KYR']) || '' != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['KSA_RUN_KYR'])) {
            $strKSA_RUN_KYR = $data_sdh01_keiyakusya[0]['KSA_RUN_KYR'];
        }
        // if (null != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['TOU_DT']) || '' != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['TOU_DT'])) {
        //     $strTOU_DT = $data_sdh01_keiyakusya[0]['TOU_DT'];
        // }
        //20150611 Add End

        //20150619 Add Start
        $strNKSIN1_SOKOKM = $data_sdh01_keiyakusya[0]['NKSIN1_SOKOKM'];
        //20150619 Add End

        //予想距離
        //20150611 Update Start
        //            if ($strFRGMH == "" || $strVCLIPEDT == "" || $strNKSIN_VCLMTCDS == "") {
        //                $data_sdh01_keiyakusya[0]["YOSOUKILO"] = "";
        //            } else {
        //                $yoso = $this -> getMonthNum($strFRGMH, $strVCLIPEDT);
        //                $data_sdh01_keiyakusya[0]["YOSOUKILO"] = (int)$strNKSIN_VCLMTCDS * (int)$yoso;
        //            }
        if ('' == $strFRGMH || '' == $strVCLIPEDT || '' == $strNKSIN_VCLMTCDS || '' == $strKSA_DT || '' == $strKSA_RUN_KYR) {
            $data_sdh01_keiyakusya[0]['YOSOUKILO'] = '';
        } else {
            $yoso = $this->getMonthNum($strKSA_DT, $strVCLIPEDT);
            //$yoso = $this -> getMonthNum($strTOU_DT, $strVCLIPEDT);
            //20150619 Update Start
            //$yoso = $this -> getMonthNum($strTOU_DT, $strVCLIPEDT);
            $data_sdh01_keiyakusya[0]['YOSOUKILO'] = (int) $strNKSIN_VCLMTCDS * (int) $yoso + (int) $strNKSIN1_SOKOKM;
            //20150619 Update End
        }
        //20150611 Update End

        //20150611 Add Start
        //登録日
        if (null != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['TOU_DT']) || '' != $this->ClsComFnc->FncNv($data_sdh01_keiyakusya[0]['TOU_DT'])) {
            $data_sdh01_keiyakusya[0]['TOU_DT'] = substr($data_sdh01_keiyakusya[0]['TOU_DT'], 0, 4) . '/' . substr($data_sdh01_keiyakusya[0]['TOU_DT'], 4, 2) . '/' . substr($data_sdh01_keiyakusya[0]['TOU_DT'], 6, 2);
        } else {
            $data_sdh01_keiyakusya[0]['TOU_DT'] = '';
        }
        //20150611 Add End

        //hit_memo s
        $result = $this->m_SDH01_02->m_select_hit_memo($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);
        if (count((array) $result['data']) > 0) {
            $data_sdh01_keiyakusya[0]['SRY_FRE_MEM'] = $result['data'][0]['SRY_FRE_MEM'];
            $data_sdh01_keiyakusya[0]['FRE_MEM'] = $result['data'][0]['FRE_MEM'];
        } else {
            $data_sdh01_keiyakusya[0]['SRY_FRE_MEM'] = '';
            $data_sdh01_keiyakusya[0]['FRE_MEM'] = '';
        }

        //HIT進捗状況
        $result = $this->m_SDH01_02->m_select_hit_situ($VIN_WMIVDS, $VIN_VIS, $data_sdh01_keiyakusya[0]['KNR_STRCD']);

        if (count((array) $result['data']) > 0) {
            $data_sdh01_keiyakusya[0]['SITU'] = '    ' . substr($result['data'][0]['YM'], 0, 4) . '/' . substr($result['data'][0]['YM'], 4, 2) . '        ' . $result['data'][0]['SITU'];
        } else {
            $data_sdh01_keiyakusya[0]['SITU'] = '';
        }

        $data_sdh01_keiyakusya[0]['AGE'] = '';
        $BRTDT = $data_sdh01_keiyakusya[0]['BRTDT'];
        if ('' != trim($BRTDT)) {
            $NOW = date('YMD');
            $AGE = substr($NOW, 0, 4) - substr($BRTDT, 0, 4);
            $data_sdh01_keiyakusya[0]['AGE'] = $AGE;
        }

        $result = $this->m_SDH01_02->getJsonData();

        //CSRRANK カテゴリ
        $searchID = $data_sdh01_keiyakusya[0]['CSRRANK'];
        $obj = $this->m_SDH01_02->retuenJsonData($result, 'CSRRANK', $searchID);
        $data_sdh01_keiyakusya[0]['CSRRANK'] = $obj;

        //XH10CAID　車両区分
        $searchID = $data_sdh01_keiyakusya[0]['XH10CAID'];
        $obj = $this->m_SDH01_02->retuenJsonData($result, 'XH10CAID', $searchID);
        $data_sdh01_keiyakusya[0]['XH10CAID'] = $obj;

        //XG11KOTEIID 固定化
        $searchID = $data_sdh01_keiyakusya[0]['XG11KOTEIID'];
        $obj = $this->m_SDH01_02->retuenJsonData($result, 'XG11KOTEIID', $searchID);
        $data_sdh01_keiyakusya[0]['XG11KOTEIID'] = $obj;

        //DM_FKA_KB DM不可区分
        $searchID = $data_sdh01_keiyakusya[0]['DM_FKA_KB'];
        $obj = $this->m_SDH01_02->retuenJsonData($result, 'DM_FKA_KB', $searchID);
        $data_sdh01_keiyakusya[0]['DM_FKA_KB'] = $obj;

        //XHKTGKBN 引続き区分
        $searchID = $data_sdh01_keiyakusya[0]['XHKTGKBN'];
        $obj = $this->m_SDH01_02->retuenJsonData($result, 'XHKTGKBN', $searchID);
        $data_sdh01_keiyakusya[0]['XHKTGKBN'] = $obj;

        if ('' == trim(str_replace('-', '', $data_sdh01_keiyakusya[0]['TEL_NO']))) {
            $data_sdh01_keiyakusya[0]['TEL_NO'] = '';
        }

        if ('' == trim(str_replace('-', '', $data_sdh01_keiyakusya[0]['MOB_TEL_NO']))) {
            $data_sdh01_keiyakusya[0]['MOB_TEL_NO'] = '';
        }

        //VSLFRMID 販売形態
        $searchID = $data_sdh01_keiyakusya[0]['VSLFRMID'];
        $obj = $this->m_SDH01_02->retuenJsonData($result, 'VSLFRMID', $searchID);
        $data_sdh01_keiyakusya[0]['VSLFRMID'] = $obj;
        //        }

        $this->data['sdh01_keiyakusya'] = $data_sdh01_keiyakusya;
    }

    public function getMonthNum($date1, $date2)
    {
        list($date_1['y'], $date_1['m']) = explode('/', $date1);
        list($date_2['y'], $date_2['m']) = explode('/', $date2);

        return abs(((int) $date_2['y'] - (int) $date_1['y']) * 12 + (int) $date_2['m'] - (int) $date_1['m']);
    }

    public function get_tantou_henkou_rireki($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    {
        // App::uses('SDH06', 'Model/SDH');
        $this->m_SDH06 = new SDH06();
        $result = $this->m_SDH06->m_select_TANT_HENKO_LIST($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);
        $data_sdh01_tantou_henkou_rireki = $result['data'];
        $this->data['sdh01_tantou_henkou_rireki'] = $data_sdh01_tantou_henkou_rireki;
    }

    //--- 20160127 li UPD S
    // public function get_hantei_naiyou($VCLIPEDT, $VIN_WMIVDS, $VIN_VIS)
    public function get_hantei_naiyou($VCLIPEDT, $VIN_WMIVDS, $VIN_VIS, $con4)
    //--- 20160127 li UPD E
    {
        // App::uses('SDH01_01', 'Model/SDH');
        $this->m_SDH01_01 = new SDH01_01();
        $values = array(
            'SYAKENBI' => $VCLIPEDT,
            'SYADAI' => $VIN_WMIVDS,
            'CARNO' => $VIN_VIS,
        );

        if (isset($_POST['data']['nengetu'])) {
            $nengetu = $_POST['data']['nengetu'];
        } else {
            $nengetu = date('Ym');
        }

        //--- 20160127 li UPD S
        // $result = $this -> m_SDH01_01 -> m_select_sdh01_01($values, $this -> hansh_cd, $nengetu);
        if ('1' == $con4 or '2' == $con4) {
            $result = $this->m_SDH01_01->m_select_sdh01_01_sinsya($values, $this->hansh_cd, $con4);
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $result = $this->m_SDH01_01->m_select_sdh01_01($values, $this->hansh_cd, $nengetu);
            //20190227 YIN INS S
        } elseif ('3' == $con4) {
            $result = $this->m_SDH01_01->m_select_sdh01_01_chuko($values, $this->hansh_cd, $con4);
            //20190227 YIN INS E
        }
        //--- 20160127 li UPD E

        $data_sdh01_hantei_naiyou = $result['data'];

        $this->data['sdh01_hantei_naiyou'] = $data_sdh01_hantei_naiyou;
    }

    public function get_memo($VIN_WMIVDS, $VIN_VIS)
    {
        // App::uses('SDH01_07', 'Model/SDH');
        $this->m_SDH01_07 = new SDH01_07();
        $result = $this->m_SDH01_07->m_select_sdh01_07($VIN_WMIVDS, $VIN_VIS);
        $data_sdh01_memo = $result['data'];
        $this->data['sdh01_memo'] = $data_sdh01_memo;
    }

    //20150611 Update Start
    //    public function get_nyuko_rireki($tenpo_cd, $DLRCSRNO, $VIN_WMIVDS, $VIN_VIS) {
    public function get_nyuko_rireki($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    {
        //20150611 Update End
        // App::uses('SDH01_04', 'Model/SDH');

        $this->m_SDH01_04 = new SDH01_04();

        //顧客コード
        $arrayData['DLRCSRNO'] = $DLRCSRNO;

        //ＶＩＮ車台型式
        $arrayData['VIN_SDI_KAT'] = $VIN_WMIVDS;

        //VIN連番
        $arrayData['VIN_RBN'] = $VIN_VIS;

        //登録日
        $arrayData['TOU_DT'] = $TOU_DT;

        $result = $this->m_SDH01_04->m_shd01_04_select($arrayData);
        $this->set('arr_result_shd01_04_01', $result['data']);
        $this->data['sdh01_nyuko_rireki'] = $result['data'];
    }

    //20150611 Update Start
    //    public function get_timeline($CMN_NO, $DLRCSRNO, $VIN_WMIVDS, $VIN_VIS) {
    public function get_timeline($CMN_NO, $DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT)
    {
        try {
            //20150611 Update End
            // App::uses('SDH01_05', 'Model/SDH');
            $this->TimeLine = new SDH01_05();

            $result = array();
            $FRGMH = $this->TimeLine->getFRGMH($VIN_WMIVDS, $VIN_VIS);
            $result['data']['FRGMH'] = $FRGMH['data'];
            $result['data'][1] = $this->data['sdh01_nyuko_rireki'];

            //20150611 Update Start
            //        $result3 = $this -> TimeLine -> getdata3($VIN_WMIVDS, $VIN_VIS );
            $result3 = $this->TimeLine->getdata3($VIN_WMIVDS, $VIN_VIS, $TOU_DT);
            //20150611 Update End
            $result['data'][2] = $result3['data'];

            //20150611 Update Start
            //        $result4 = $this -> TimeLine -> getdata4($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);
            $result4 = $this->TimeLine->getdata4($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT);
            //20150611 Update End

            $result['data'][3] = $result4['data'];

            //20150611 Update Start
            //        $result5 = $this -> TimeLine -> getdata5($CMN_NO);
            $result5 = $this->TimeLine->getdata5($CMN_NO, $TOU_DT);
            //20150611 Update End

            $result['data'][4] = $result5['data'];

            //20150611 Update Start
            //        $result6 = $this -> TimeLine -> getdata6($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);
            $result6 = $this->TimeLine->getdata6($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT);
            if (!$result6['result']) {
                throw new \Exception($result6['data']);
            }
            //20150611 Update End

            $tempresult = $this->TimeLine->gettempdata($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT);
            $result['data'][5]['SONPO_NM'] = '';
            if (0 != $result6['row']) {
                $result['data'][5]['SONPO_NM'] = $result6['data'][0]['SONPO_NM'];
            }
            $result['data'][5]['HOKENSIKI'] = '';
            $result['data'][5]['HOKENSYUKI'] = '';
            if (0 != $tempresult['row']) {
                $result['data'][5]['HOKENSIKI'] = $tempresult['data'][0]['HOKENSIKI'];
                $result['data'][5]['HOKENSYUKI'] = $tempresult['data'][0]['HOKENSYUKI'];
                if ('99' == $tempresult['data'][0]['SYARYO']) {
                    $result['data'][5]['SONPO_NM'] = $result['data'][5]['SONPO_NM'] . '  ＋車両特約付';
                }
            }

            $data_sdh01_tiemline = $result['data'];
            $this->data['sdh01_tiemline'] = $data_sdh01_tiemline;
        } catch (\Exception $e) {
            $this->data['result'] = false;
            $this->data['data'] = $e->getMessage();
        }
    }

    public function sDH01()
    {
        //20220126 sun add s
        try {
            //20220126 sun add e
            $post_data = null;
            if (isset($_POST['data'])) {
                $post_data = $_POST['data'];
            }
            $tenpo_cd = '';
            //拠点コードを取得 s
            if ($post_data && array_key_exists('tenpo_cd', $post_data)) {
                $tenpo_cd = $post_data['tenpo_cd'];
                $this->data['sdh01_tenpo'] = array();
                $this->data['sdh01_tenpo']['KYOTN_CD'] = $tenpo_cd;
            } else {
                $this->get_tenpo();
                $tenpo_cd = $this->data['sdh01_tenpo']['KYOTN_CD'];
            }
            if ('honbu' == $tenpo_cd) {
                $this->fncReturn($this->data);

                return;
            }
            $tenpo_cd_2 = substr($tenpo_cd, 0, 2);

            //拠点コードを取得 e

            //社員 s
            if ($post_data && array_key_exists('is_first_load', $post_data)) {
                $this->get_syain($tenpo_cd_2);

                if (1 == count($post_data)) {
                    $this->data['firsttime_load'] = true;
                    $this->fncReturn($this->data);

                    return;
                }
            } elseif ($post_data && array_key_exists('is_tenpo_changed', $post_data)) {
                $this->get_syain($tenpo_cd_2);
            }
            //年月 s
            $nengetu = '';
            if ($post_data && array_key_exists('nengetu', $post_data)) {
                $nengetu = $post_data['nengetu'];
                $nengetu = substr($nengetu, 0, 6);
            } else {
                $nengetu = date('Ym');
            }
            $this->data['sdh01_server_date'] = date('Ymd');

            //注文書NO s
            $CMN_NO = '';
            if ($post_data && array_key_exists('CMN_NO', $post_data)) {
                $CMN_NO = $post_data['CMN_NO'];
            }
            $con = '0';
            $con1 = '000';
            $con2 = '000';
            $con3 = '0';
            //20160201 YIN INS S
            $con4 = '0';
            //20160201 YIN INS E

            if ($post_data && array_key_exists('condition', $post_data)) {
                $con = $post_data['condition'];
            }

            if ($post_data && array_key_exists('condition1', $post_data)) {
                $con1 = $post_data['condition1'];
            }

            if ($post_data && array_key_exists('condition2', $post_data)) {
                $con2 = $post_data['condition2'];
            }

            if ($post_data && array_key_exists('condition3', $post_data)) {
                $con3 = $post_data['condition3'];
            }

            //20160201 YIN INS S
            if ($post_data && array_key_exists('condition4', $post_data)) {
                $con4 = $post_data['condition4'];
            }
            //20160201 YIN INS E

            //判定リスト s
            //クライアントからのPOSTデータに「注文書NO」が無ければ、判定リストを取得すること
            if ('' == $CMN_NO) {
                if ($post_data && array_key_exists('tantousya_type', $post_data) && array_key_exists('tantousya_code', $post_data)) {
                    $tantousya_type = $post_data['tantousya_type'];
                    $tantousya_code = $post_data['tantousya_code'];
                    $this->data['nengetu'] = $nengetu;

                    //20160201 YIN UPD S
                    //$this -> get_hantei_list($tenpo_cd_2, $nengetu, $con, $con1, $con2, $con3, $tantousya_type, $tantousya_code);
                    $this->get_hantei_list($tenpo_cd_2, $nengetu, $con, $con1, $con2, $con3, $con4, $tantousya_type, $tantousya_code);
                    //20160201 YIN UPD E
                    // print_r("111");
                    // return;
                } else {
                    //20160201 YIN UPD S
                    //$this -> get_hantei_list($tenpo_cd_2, $nengetu, $con, $con1, $con2, $con3);
                    $this->get_hantei_list($tenpo_cd_2, $nengetu, $con, $con1, $con2, $con3, $con4);
                    //20160201 YIN UPD E
                }
                if (0 == count((array) $this->data['sdh01_hantei_list'])) {
                    $this->data['sdh01_error'] = 'no_hantei_list_data';

                    $this->fncReturn($this->data);

                    return;
                }

                $CMN_NO = $this->data['sdh01_hantei_list'][0]['CMN_NO'];
            }
            $VCLIPEDT = '';
            $DLRCSRNO = '';
            $VIN_WMIVDS = '';
            $VIN_VIS = '';
            $TOU_DT = '';
            //@formatter:off
            if ($post_data && array_key_exists('VCLIPEDT', $post_data) && array_key_exists('DLRCSRNO', $post_data) && array_key_exists('VIN_WMIVDS', $post_data) && array_key_exists('VIN_VIS', $post_data)){
                //@formatter:on
                $VCLIPEDT = $post_data['VCLIPEDT'];
                $DLRCSRNO = $post_data['DLRCSRNO'];
                $VIN_WMIVDS = $post_data['VIN_WMIVDS'];
                $VIN_VIS = $post_data['VIN_VIS'];
                //20150611 Add Start
                $TOU_DT = $post_data['TOU_DT'];
                //20150611 Add End
            } else {
                $VCLIPEDT = $this->data['sdh01_hantei_list'][0]['VCLIPEDT'];
                $DLRCSRNO = $this->data['sdh01_hantei_list'][0]['DLRCSRNO'];
                $VIN_WMIVDS = $this->data['sdh01_hantei_list'][0]['VIN_WMIVDS'];
                $VIN_VIS = $this->data['sdh01_hantei_list'][0]['VIN_VIS'];
                //20150611 Add Start
                $TOU_DT = $this->data['sdh01_hantei_list'][0]['TOU_DT'];
                //20150611 Add End
            }

            //契約者 s
            $this->get_keiyakusya($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);

            //担当変更履歴 s
            $this->get_tantou_henkou_rireki($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);

            //判定内容 s
            //--- 20160127 li UPD S
            // $this -> get_hantei_naiyou($VCLIPEDT, $VIN_WMIVDS, $VIN_VIS);
            $this->get_hantei_naiyou($VCLIPEDT, $VIN_WMIVDS, $VIN_VIS, $con4);
            //--- 20160127 li UPD E

            //メモ s
            $this->get_memo($VIN_WMIVDS, $VIN_VIS);

            //入庫履歴を取得 s
            //20150611 Update Start
            //        $this -> get_nyuko_rireki($tenpo_cd, $VIN_WMIVDS, $VIN_VIS);
            $this->get_nyuko_rireki($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT);
            //20150611 Update End

            //TimeLine s
            //20150611 Update Start
            //        $this -> get_timeline($CMN_NO, $DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);
            $this->get_timeline($CMN_NO, $DLRCSRNO, $VIN_WMIVDS, $VIN_VIS, $TOU_DT);
            //20150611 Update End

            //--- 20160127 li UPD S
            // $this -> getMenu();
            $this->getMenu($con4);
            //--- 20160127 li UPD E

            //----20220121 sun add s
            if ('4' == $con4) {
                $this->getTencyou();
            }
            //----20220121 sun add e
        } catch (\Exception $e) {
            $this->data['result'] = false;
            $this->data['error'] = $e->getMessage();
        }

        $this->fncReturn($this->data);
    }

    //----20220121 sun add s
    public function getTencyou()
    {
        // App::uses('SDH01_01', 'Model/SDH');
        $this->m_SDH01_01 = new SDH01_01();
        try {
            $this->Session = $this->request->getSession();
            $result = $this->m_SDH01_01->m_select_gettencyou($this->Session->read('login_user'));
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } elseif (count((array) $result['data']) > 0) {
                $data_tencyou = (int) $result['data'][0]['CNT'];
                if ($data_tencyou > 0) {
                    $this->data['sdh01_tencyou'] = 1;
                } else {
                    $this->data['sdh01_tencyou'] = 0;
                }
            }
        } catch (\Exception $e) {
            $this->data['sdh01_tencyou'] = 0;
        }
    }

    public function sinchokuUpd()
    {
        $result = array();
        // App::uses('SDH01_01', 'Model/SDH');
        $this->m_SDH01_01 = new SDH01_01();
        $transFlg = false;
        try {
            $this->Session = $this->request->getSession();
            $result = $this->m_SDH01_01->m_select_gettencyou($this->Session->read('login_user'));

            if (!$result['result']) {
                throw new \Exception($result['data']);
            } elseif (count((array) $result['data']) > 0) {
                $data_tencyou = (int) $result['data'][0]['CNT'];
                if ($data_tencyou <= 0) {
                    throw new \Exception('not tencyo');
                }
            }
            $data = $_POST['data'];
            $sinchokuRes = $this->m_SDH01_01->m_select_getsinchoku($data['SYADAI'], $data['CARNO'], $data['TENPO']);
            if (!$sinchokuRes['result']) {
                throw new \Exception($sinchokuRes['data']);
            } elseif (count((array) $sinchokuRes['data']) > 0) {
                $data_n6 = $sinchokuRes['data'][0]['CNT'];
                //20220127 sun del s
                // $this -> DB_Conn = $this -> m_SDH01_01 -> Do_conn();
                // if (!$this -> DB_Conn['result'])
                // {
                // throw new Exception($this -> DB_Conn['data']);
                // }
                //20220127 sun del e
                $this->m_SDH01_01->Do_transaction();
                $transFlg = true;
                if (0 == $data_n6) {
                    $sqlRes = $this->m_SDH01_01->m_insert_add_n6_data($data['SYADAI'], $data['CARNO'], $data['TENPO'], $this->Session->read('login_user'), $data['CHECKED']);
                } else {
                    $sqlRes = $this->m_SDH01_01->m_update_upd_n6_data($data['SYADAI'], $data['CARNO'], $data['TENPO'], $this->Session->read('login_user'), $data['CHECKED']);
                }

                if (!$sqlRes['result']) {
                    throw new \Exception($sqlRes['data']);
                }
            }
            $this->m_SDH01_01->Do_commit();
            $result = $sqlRes;
            $result['data'] = '';
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['data'] = $e->getMessage();
            if ('not tencyo' == $result['data']) {
                $result['data'] = '店長ではないのため、操作はできません。';
            }
            if ($transFlg) {
                $this->m_SDH01_01->Do_rollback();
            }
        }
        //20220127 sun del s
        //$this -> m_SDH01_01 -> Do_close();
        //20220127 sun del e

        $this->fncReturn($result);
    }

    //----20220121 sun add e

    //--- 20160127 li UPD S
    // public function getMenu()
    public function getMenu($con4)
    //--- 20160127 li UPD E
    {
        // App::uses('SDH01', 'Model/SDH');
        $this->m_SDH01 = new SDH01();
        //--- 20160127 li UPD S
        // $result = $this -> m_SDH01 -> m_select_menu_top();
        $result = array('data' => '');
        if ('1' == $con4) {
            $result = $this->m_SDH01->m_select_menu_top_sinsya();
        } elseif ('2' == $con4) {
            $result = $this->m_SDH01->m_select_menu_top1_sinsya();
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 || '4' == $con4) {
            //----20220121 sun upd e
            $result = $this->m_SDH01->m_select_menu_top('');
        }
        //--- 20160127 li UPD E

        $data_sdh01_menu = $result['data'];
        $this->data['sdh01_menu'] = $data_sdh01_menu;

        //--- 20160127 li UPD S
        // $result = $this -> m_SDH01 -> m_select_menuLast_top();
        if ('1' == $con4) {
            $result = $this->m_SDH01->m_select_menuLast_top_sinsya();
        } elseif ('2' == $con4) {
            $result = $this->m_SDH01->m_select_menuLast_top1_sinsya();
        }
        //20190227 YIN INS S
        elseif ('3' == $con4) {
            $result = $this->m_SDH01->m_select_menuLast_top_chuko();
        }
        //20190227 YIN INS E
        elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 || '4' == $con4) {
            //----20220121 sun upd e
            $result = $this->m_SDH01->m_select_menuLast_top();
        }
        //--- 20160127 li UPD E

        $data_sdh01_menuLast = $result['data'];
        $this->data['sdh01_menuLast'] = $data_sdh01_menuLast;
    }

    public function sDH0101()
    {
        $result = array();

        $data = $_POST['data'];

        // App::uses('SDH01_01', 'Model/SDH');
        $this->m_SDH01_01 = new SDH01_01();
        try {
            $this->Session = $this->request->getSession();
            //--- 20160127 li UPD S
            // $tempresult = $this -> m_SDH01_01 -> m_sel_before_ins_sdh01_01($data);
            $con4 = $data['condition4'];
            if ('1' == $con4 or '2' == $con4) {
                $tempresult = $this->m_SDH01_01->m_sel_before_ins_sdh01_01_sinsya($data);
                //20190227 YIN INS S
            } elseif ('3' == $con4) {
                $tempresult = $this->m_SDH01_01->m_sel_before_ins_sdh01_01_chuko($data);
                //20190227 YIN INS E
            } elseif //----20220121 sun upd s
            //if ($con4 == "0")
            ('0' == $con4 or '4' == $con4) {
                $tempresult = $this->m_SDH01_01->m_sel_before_ins_sdh01_01($data);
                if ('' != $data['KEKKA_CD'] && null != $data['KEKKA_CD']) {
                    $sinchokuRes = $this->m_SDH01_01->m_select_getsinchoku($data['SYADAI'], $data['CARNO'], $data['TENPO']);
                }
            }
            //----20220121 sun upd e

            //--- 20160127 li UPD E
            //20220127 sun del s
            // $this -> DB_Conn = $this -> m_SDH01_01 -> Do_conn();
            // if (!$this -> DB_Conn['result'])
            // {
            // throw new Exception($this -> DB_Conn['data']);
            // }
            //20220127 sun del e
            $this->m_SDH01_01->Do_transaction();

            if (true == $tempresult['result']) {
                //----20220121 sun upd s
                //if ($con4 == "0")
                if ('0' == $con4 or '4' == $con4) {
                    if ('' != $data['KEKKA_CD'] && null != $data['KEKKA_CD']) {
                        if (count((array) $sinchokuRes['data']) > 0) {
                            $data_n6 = $sinchokuRes['data'][0]['CNT'];
                            if (0 == $data_n6) {
                                $sqlRes = $this->m_SDH01_01->m_insert_add_n6_data($data['SYADAI'], $data['CARNO'], $data['TENPO'], $this->Session->read('login_user'), '1');
                            } else {
                                $sqlRes = $this->m_SDH01_01->m_update_upd_n6_data($data['SYADAI'], $data['CARNO'], $data['TENPO'], $this->Session->read('login_user'), '1');
                            }

                            if (!$sqlRes['result']) {
                                throw new \Exception($sqlRes['data']);
                            }
                        }
                    }
                }
                //----20220121 sun upd e
                if (0 == $tempresult['row'] || null == $tempresult['data'][0]['MAXREVISION']) {
                    $data['REVISION'] = 1;
                    $data['UPDSYACD'] = strtoupper($this->Session->read('login_user'));
                    //--- 20160127 li UPD S
                    // $result1 = $this -> m_SDH01_01 -> m_insert_sdh01_01($data);
                    $con4 = $data['condition4'];
                    if ('1' == $con4 or '2' == $con4) {
                        $result1 = $this->m_SDH01_01->m_insert_sdh01_01_sinsya($data);
                        //20190227 YIN INS S
                    } elseif ('3' == $con4) {
                        $result1 = $this->m_SDH01_01->m_insert_sdh01_01_chuko($data);
                        //20190227 YIN INS E
                    } elseif //----20220121 sun upd s
                    //if ($con4 == "0")
                    ('0' == $con4 or '4' == $con4) {
                        //----20220121 sun upd e
                        $result1 = $this->m_SDH01_01->m_insert_sdh01_01($data);
                    }
                    //--- 20160127 li UPD E
                    if (!$result1['result']) {
                        throw new \Exception($result['data']);
                    }
                    $result1 = $this->m_sdh01_01_HANTEIMEMO($data);
                    if (!$result1['result']) {
                        throw new \Exception($result1['data']);
                    }
                } else {
                    if ($tempresult['data'][0]['MAXREVISION'] < 999) {
                        $data['REVISION'] = $tempresult['data'][0]['MAXREVISION'] + 1;
                        $data['UPDSYACD'] = strtoupper($this->Session->read('login_user'));
                        //--- 20160127 li UPD S
                        // $result1 = $this -> m_SDH01_01 -> m_insert_sdh01_01($data);
                        $con4 = $data['condition4'];
                        if ('1' == $con4 or '2' == $con4) {
                            $result1 = $this->m_SDH01_01->m_insert_sdh01_01_sinsya($data);
                            //20190227 YIN INS S
                        } elseif ('3' == $con4) {
                            $result1 = $this->m_SDH01_01->m_insert_sdh01_01_chuko($data);
                            //20190227 YIN INS E
                        } elseif //----20220121 sun upd s
                        //if ($con4 == "0")
                        ('0' == $con4 or '4' == $con4) {
                            //----20220121 sun upd e
                            $result1 = $this->m_SDH01_01->m_insert_sdh01_01($data);
                        }
                        //--- 20160127 li UPD E
                        if (!$result1['result']) {
                            throw new \Exception($result['data']);
                        }
                        $result1 = $this->m_sdh01_01_HANTEIMEMO($data);
                        if (!$result1['result']) {
                            throw new \Exception($result1['data']);
                        }
                    } else {
                        $data['REVISION'] = $tempresult['data'][0]['MAXREVISION'];
                        $data['UPDSYACD'] = strtoupper($this->Session->read('login_user'));
                        //--- 20160127 li UPD S
                        // $result1 = $this -> m_SDH01_01 -> m_update_sdh01_01($data);
                        $con4 = $data['condition4'];
                        if ('1' == $con4 or '2' == $con4) {
                            $result1 = $this->m_SDH01_01->m_update_sdh01_01_sinsya($data);
                            //20190227 YIN INS S
                        } elseif ('3' == $con4) {
                            $result1 = $this->m_SDH01_01->m_update_sdh01_01_chuko($data);
                            //20190227 YIN INS E
                        } elseif //----20220121 sun upd s
                        //if ($con4 == "0")
                        ('0' == $con4 or '4' == $con4) {
                            //----20220121 sun upd e
                            $result1 = $this->m_SDH01_01->m_update_sdh01_01($data);
                        }
                        //--- 20160127 li UPD E
                        if (!$result1['result']) {
                            throw new \Exception($result['data']);
                        }
                        $result1 = $this->m_sdh01_01_HANTEIMEMO($data);
                        if (!$result1['result']) {
                            throw new \Exception($result1['data']);
                        }
                    }
                }
            }
            $this->m_SDH01_01->Do_commit();

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['data'] = $e->getMessage();
            $this->m_SDH01_01->Do_rollback();
        }
        //20220127 sun del s
        //$this -> m_SDH01_01 -> Do_close();
        //20220127 sun del e

        $this->fncReturn($result);
    }

    public function m_sdh01_01_HANTEIMEMO($data)
    {
        $result2 = array();
        $result2['result'] = true;
        $Do_Excute = $this->m_SDH01_01->m_select_sdh01_07($data);

        if (true == $Do_Excute['result']) {
            if (0 == $Do_Excute['row']) {
                $result2 = $this->m_SDH01_01->m_insert_sdh01_07($data);
                if (!$result2['result']) {
                    throw new \Exception($result2['data']);
                }
            } else {
                if ($data['MEMO'] != $Do_Excute['data'][0]['MEMO']) {
                    $result2 = $this->m_SDH01_01->m_update_sdh01_07($data);
                    if (!$result2['result']) {
                        throw new \Exception($result2['data']);
                    }
                }
            }
        } else {
            $result2 = $this->m_SDH01_01->m_insert_sdh01_07($data);
            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
        }

        return $result2;
    }

    public function checkCmnno()
    {
        // App::uses('SDH03', 'Model/SDH');
        try {
            $result = array();
            $CMN_NO = $_POST['data'];
            $this->m_SDH03 = new SDH03();
            $result = $this->m_SDH03->m_select_M41E10($CMN_NO);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //---20150821 Yuanjh ADD S.
    public function getHanteisyasyu()
    {
        // App::uses('SDH01', 'Model/SDH');
        $this->m_SDH01 = new SDH01();
        //車種コンボボックスのリスト編集
        //先頭行に "全て" を編集
        //判定車種マスタから、車種名の一覧を取得し、全件を２行目から編集
        //最終行に "その他" を編集
        //先頭行 "全て"　を選択状態にする
        $strFull = '全て';
        $strFullValue = '全て';
        $strOther = 'その他';
        $strOtherValue = 'その他';
        $hantei_tmp = '<option value ="{#val}">{#txt}</option>';
        $hantei_tmp1 = '<option value ="{#val}" select="selected">{#txt}</option>';
        $find = array(
            '{#val}',
            '{#txt}',
        );
        $resulthantei = $this->m_SDH01->m_select_hantei();
        $hantei_list = '';
        $replace = array(
            $strFullValue,
            $strFull,
        );
        $hantei = str_replace($find, $replace, $hantei_tmp1);
        $hantei_list .= $hantei;
        foreach ((array) $resulthantei['data'] as $value) {
            $val = $value['SYASYU_NM'];
            $txt = $value['SYASYU_NM'];
            $replace = array(
                $val,
                $txt,
            );
            $hantei = str_replace($find, $replace, $hantei_tmp);
            $hantei_list .= $hantei;
        }
        $replace = array(
            $strOtherValue,
            $strOther,
        );
        $hantei = str_replace($find, $replace, $hantei_tmp);
        $hantei_list .= $hantei;
        $result['data1'] = $hantei_list;

        //最終結果（行タイトル） の編集
        $ym = $_POST['data']['nengetu'];
        //年月
        $tantocd = $_POST['data']['tantocd'];
        //担当者
        $hantei = $_POST['data']['hantei'];
        //車種マスタ
        //20151103 Yin INS S
        $busyocd = $_POST['data']['busyocd'];
        //部署コード
        //20151103 Yin INS E
        //--- 20160127 li UPD S
        // $result['data2'] = $this -> getTablehanteiteilkeiDetails($ym, $hantei, $tantocd, $busyocd);
        $con4 = $_POST['data']['condition4'];
        $result['data2'] = $this->getTablehanteiteilkeiDetails($ym, $hantei, $tantocd, $busyocd, $con4);
        //--- 20160127 li UPD E
        $this->fncReturn($result);
    }

    public function getHanteisyasyudetail()
    {
        $ym = $_POST['data']['nengetu'];
        //年月
        $tantocd = $_POST['data']['tantocd'];
        //担当者
        $hantei = $_POST['data']['hantei'];
        //車種マスタ
        //20151103 Yin INS S
        $busyocd = $_POST['data']['busyocd'];
        //部署コード
        //20151103 Yin INS E
        //--- 20160127 li INS S
        $con4 = $_POST['data']['condition4'];
        //--- 20160127 li INS E
        $result = array();
        //--- 20160127 li UPD S
        // $result['data'] = $this -> getTablehanteiteilkeiDetails($ym, $hantei, $tantocd, $busyocd);
        $result['data'] = $this->getTablehanteiteilkeiDetails($ym, $hantei, $tantocd, $busyocd, $con4);
        //--- 20160127 li UPD E
        $this->fncReturn($result);
    }

    //最終結果（行タイトル） の編集
    //--- 20160127 li UPD S
    // public function getTablehanteiteilkeiDetails($ym, $hantei, $tantocd, $busyocd)
    public function getTablehanteiteilkeiDetails($ym, $hantei, $tantocd, $busyocd, $con4)
    //--- 20160127 li UPD E
    {
        // App::uses('SDH01', 'Model/SDH');
        $this->m_SDH01 = new SDH01();
        //--- 20160127 li UPD S
        // $resultDetails = $this -> m_SDH01 -> m_select_resulthanteiteilkeiDetails($ym, $hantei, $tantocd, $busyocd);
        if ('1' == $con4 or '2' == $con4) {
            $resultDetails = $this->m_SDH01->m_select_resulthanteiteilkeiDetails_sinsya($ym, $hantei, $tantocd, $busyocd);
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $resultDetails = $this->m_SDH01->m_select_resulthanteiteilkeiDetails($ym, $hantei, $tantocd, $busyocd);
        }
        //--- 20160127 li UPD E
        //20151029 Yin ADD S
        //判定定型マスタを検索し、取得したデータを画面に編集する
        //--- 20160127 li UPD S
        // $resultDetails1 = $this -> m_SDH01 -> m_select_resulttittle();
        if ('1' == $con4 or '2' == $con4) {
            $resultDetails1 = $this->m_SDH01->m_select_resulttittle_sinsya($con4);
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $resultDetails1 = $this->m_SDH01->m_select_resulttittle();
        }
        //--- 20160127 li UPD E
        $resultDetails2 = array();
        foreach ((array) $resultDetails1['data'] as $key => $value) {
            $key = $value['TEIKEI_CD'];
            for ($i = 1; $i < 13; ++$i) {
                $filename1 = 'KENSU' . $i;
                $value[$filename1] = 0;
            }
            $resultDetails2[$key] = $value;
        }
        //20151102 Yin ADD S
        //最下行に 合計行を追加
        $resultDetails2['合計'] = $value;
        $resultDetails2['合計']['ITEMNAME1'] = '合 計';
        //20151102 Yin ADD E
        //データを作成
        foreach ((array) $resultDetails['data'] as $key => $value) {
            for ($i = 1; $i < 13; ++$i) {
                $filename = 'COL' . $i;
                $filename1 = 'KENSU' . $i;
                if (0 != $value[$filename]) {
                    $TEIKEI_CD = $value['KEKKA_CD'];
                    foreach ($resultDetails2 as $key1 => $value1) {
                        if ($key1 == $TEIKEI_CD) {
                            $resultDetails2[$key1][$filename1] = $value[$filename];
                            $resultDetails2['合計'][$filename1] = $resultDetails2['合計'][$filename1] + $value[$filename];
                        }
                    }
                }
            }
        }
        //20151029 Yin ADD E
        $strTable['TABLE'] = '';
        $strTable['TABLE'] = '<table>';
        foreach ($resultDetails2 as $key => $value) {
            //20151104 Yin INS S
            if ('合計' == $key) {
                $strTable['TABLE'] .= '<tr><td colspan="13"><div style="border-bottom: 1px grey solid;"></div></td></tr>';
            }
            //20151104 Yin INS E
            $strTable['TABLE'] .= '<tr>';
            $strTable['TABLE'] .= "<td width='120px'><div class='sdh sdh10 label'>";
            $strTable['TABLE'] .= $value['ITEMNAME1'];
            $strTable['TABLE'] .= '</div></td>';
            for ($i = 1; $i < 13; ++$i) {
                $filename = 'KENSU' . $i;
                if (strlen($value[$filename]) > 0) {
                    $strTable['TABLE'] .= "<td width='60px' align='right'><div>" . $value[$filename] . '</div></td>';
                } else {
                    $strTable['TABLE'] .= "<td width='60px' align='right'><div>0</div></td>";
                }
            }
            $strTable['TABLE'] .= '</tr>';
        }
        $strTable['TABLE'] .= '</table>';
        //20151103 Yin INS S
        $strTable['GETDATE'] = $resultDetails2['合計']['GETDATE'];
        //20151103 Yin INS E
        /*
        App::uses('SDH01', 'Model/SDH');
        $this -> m_SDH01 = new SDH01();
        //判定定型マスタを検索し、取得したデータを画面に編集する
        $resulthanteiteilkei = $this -> m_SDH01 -> m_select_hanteiteilkei();
        $strTable = "";
        //検索処理を実行し、画面に表示する
        $strTable = "<table>";
        foreach ($resulthanteiteilkei['data'] as $key => $value) {
        $strTable .= "<tr>";
        $strTable .= "<td width='120px'><div class='sdh sdh10 label'>";
        $strTable .= $value["ITEMNAME1"];
        $strTable .= "</div></td>";
        $nm = $value["ITEMNAME1"];
        for($i = 12;$i >0; $i--){
        $nen =  substr($ym, 0,4)-$i+1;
        $tuki = substr($ym, 4,2);
        $resulthanteiteilkeiDetails = $this -> m_SDH01 -> m_select_resulthanteiteilkeiDetails($nen,$tuki,$nm,$hantei);
        $strTable .= "<td width='60px' align='right'><div>".$resulthanteiteilkeiDetails['data'][0]['KENSU']."</div></td>";
        }
        $strTable .= "</tr>";
        }
        $strTable .= "</table>";
        */
        return $strTable;
    }

    //20150821 Yuanjh ADD E.

    public function getAccumulation()
    {
        // App::uses('SDH01', 'Model/SDH');
        $this->m_SDH01 = new SDH01();
        //--- 20160127 li UPD S
        // $result = $this -> m_SDH01 -> m_select_menu_top();
        //--- 20160127 li INS S
        $con4 = $_POST['data']['condition4'];
        //--- 20160127 li INS E
        if ('1' == $con4) {
            $result = $this->m_SDH01->m_select_menu_top_sinsya();
        } elseif ('2' == $con4) {
            $result = $this->m_SDH01->m_select_menu_top1_sinsya();
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $result = $this->m_SDH01->m_select_menu_top('all');
        }
        //--- 20160127 li UPD E
        $recevieDate['title1'] = $result['data'];
        //--- 20160127 li UPD S
        // $result = $this -> m_SDH01 -> m_select_menuLast_top();
        if ('1' == $con4) {
            $result = $this->m_SDH01->m_select_menuLast_top_sinsya();
        } elseif ('2' == $con4) {
            $result = $this->m_SDH01->m_select_menuLast_top1_sinsya();
        } elseif //----20220121 sun upd s
        //if ($con4 == "0")
        ('0' == $con4 or '4' == $con4) {
            //----20220121 sun upd e
            $result = $this->m_SDH01->m_select_menuLast_top();
        }
        //--- 20160127 li UPD E
        $recevieDate['title2'] = $result['data'];

        $ym = $_POST['data']['nengetu'];
        $cd = $_POST['data']['tantocd'];
        $busyocd = $_POST['data']['busyocd'];
        $tantomark = $_POST['data']['tantomark'];

        $str = "<table border='0'>";
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
        $total7 = 0;
        $total = 0;

        foreach ((array) $recevieDate['title1'] as $value) {
            if ('0' == $value['MENU_TYPE']) {
                $str .= '<tr>';
                //$str .= "<td width='118px'><div class='sdh sdh09 label'>";
                $str .= "<td width='160px'><div class='sdh sdh09 label'>";
                $str .= $value['ITEMNAME1'];
                $str .= '</div></td>';
                //--- 20160127 li UPD S
                // $result = $this -> m_SDH01 -> getAcc(substr($value['TEIKEI_CD'], 0, 2), $ym, $cd, $tantomark, $busyocd);
                if ('1' == $con4 or '2' == $con4) {
                    $result = $this->m_SDH01->getAcc_sinsya(substr($value['TEIKEI_CD'], 0, 2), $ym, $cd, $tantomark, $busyocd);
                } elseif //----20220121 sun upd s
                //if ($con4 == "0")
                ('0' == $con4 or '4' == $con4) {
                    //----20220121 sun upd e
                    $result = $this->m_SDH01->getAcc(substr($value['TEIKEI_CD'], 0, 2), $ym, $cd, $tantomark, $busyocd);
                }
                //--- 20160127 li UPD E

                if ('0' == $result['row']) {
                    //						$str .= "<td width='95px' align='right'><div>0</div></td>";
                    //						$str .= "<td width='95px' align='right'><div>0</div></td>";
                    //						$str .= "<td width='95px' align='right'><div>0</div></td>";
                    //						$str .= "<td width='95px' align='right'><div>0</div></td>";
                    //						$str .= "<td width='95px' align='right'><div>0</div></td>";
                    //						$str .= "<td width='95px' align='right'><div>0</div></td>";
                    //						$str .= "<td width='95px' align='right'><div>0</div></td>";

                    $str .= "<td width='115px' align='right'><div>0</div></td>";
                    $str .= "<td width='115px' align='right'><div>0</div></td>";
                    $str .= "<td width='115px' align='right'><div>0</div></td>";
                    $str .= "<td width='115px' align='right'><div>0</div></td>";
                    $str .= "<td width='115px' align='right'><div>0</div></td>";
                    $str .= "<td width='115px' align='right'><div>0</div></td>";
                    $str .= "<td width='115px' align='right'><div>0</div></td>";
                } else {
                    //						$str .= "<td width='95px' align='right'>" . $result['data'][0]['HANTEI1_CD'] . "</td>";
                    //						$str .= "<td width='95px' align='right'>" . $result['data'][0]['HANTEI2_CD'] . "</td>";
                    //						$str .= "<td width='95px' align='right'>" . $result['data'][0]['HANTEI3_CD'] . "</td>";
                    //						$str .= "<td width='95px' align='right'>" . $result['data'][0]['HANTEI4_CD'] . "</td>";
                    //						$str .= "<td width='95px' align='right'>" . $result['data'][0]['HANTEI5_CD'] . "</td>";
                    //						$str .= "<td width='95px' align='right'>" . $result['data'][0]['HANTEI6_CD'] . "</td>";
                    //						$str .= "<td width='95px' align='right'>" . $result['data'][0]['HANTEI7_CD'] . "</td>";

                    $str .= "<td width='115px' align='right'>" . $result['data'][0]['HANTEI1_CD'] . '</td>';
                    $str .= "<td width='115px' align='right'>" . $result['data'][0]['HANTEI2_CD'] . '</td>';
                    $str .= "<td width='115px' align='right'>" . $result['data'][0]['HANTEI3_CD'] . '</td>';
                    $str .= "<td width='115px' align='right'>" . $result['data'][0]['HANTEI4_CD'] . '</td>';
                    $str .= "<td width='115px' align='right'>" . $result['data'][0]['HANTEI5_CD'] . '</td>';
                    $str .= "<td width='115px' align='right'>" . $result['data'][0]['HANTEI6_CD'] . '</td>';
                    $str .= "<td width='115px' align='right'>" . $result['data'][0]['HANTEI7_CD'] . '</td>';

                    $total1 = $total1 + (int) $result['data'][0]['HANTEI1_CD'];
                    $total2 = $total2 + (int) $result['data'][0]['HANTEI2_CD'];
                    $total3 = $total3 + (int) $result['data'][0]['HANTEI3_CD'];
                    $total4 = $total4 + (int) $result['data'][0]['HANTEI4_CD'];
                    $total5 = $total5 + (int) $result['data'][0]['HANTEI5_CD'];
                    $total6 = $total6 + (int) $result['data'][0]['HANTEI6_CD'];
                    $total7 = $total7 + (int) $result['data'][0]['HANTEI7_CD'];
                }

                $str .= '</tr>';
            }
        }

        $str .= '</table>';
        $str1 = '<table>';
        foreach ((array) $recevieDate['title2'] as $value) {
            if ('0' == $value['MENU_TYPE']) {
                $str1 .= '<tr>';
                //					$str1 .= "<td width='120px'><div class='sdh sdh09 label'>";
                $str1 .= "<td width='150px'><div class='sdh sdh09 label'>";
                $str1 .= $value['ITEMNAME1'];
                $str1 .= '</div></td>';
                //--- 20160127 li UPD S
                // $result = $this -> m_SDH01 -> getAccLast(substr($value['TEIKEI_CD'], 0, 2), $ym, $cd, $tantomark, $busyocd);
                if ('1' == $con4 or '2' == $con4) {
                    $result = $this->m_SDH01->getAccLast_sinsya(substr($value['TEIKEI_CD'], 0, 2), $ym, $cd, $tantomark, $busyocd);
                } elseif //----20220121 sun upd s
                //if ($con4 == "0")
                ('0' == $con4 or '4' == $con4) {
                    //----20220121 sun upd e
                    $result = $this->m_SDH01->getAccLast(substr($value['TEIKEI_CD'], 0, 2), $ym, $cd, $tantomark, $busyocd);
                }
                //--- 20160127 li UPD E

                if (0 == $result['row']) {
                    $str1 .= "<td width='95px' align='right'><div>0</div></td>";
                } else {
                    //$str1 .= "<td width='95px' align='right'>" . $result['data'][0]['KEKKA_CD'] . "</td>";
                    $str1 .= "<td width='115px' align='right'>" . $result['data'][0]['KEKKA_CD'] . '</td>';
                    $total = $total + (int) $result['data'][0]['KEKKA_CD'];
                }
                $str1 .= '</tr>';
            }
        }
        $str1 .= '</table>';

        $str2 = '<table>';
        $str2 .= '<tr>';
        //$str2 .= "<td width='120px'><div class='sdh sdh09 label'>";
        $str2 .= "<td width='150px'><div class='sdh sdh09 label'>";
        $str2 .= '合計';
        $str2 .= '</div></td>';

        //			$str2 .= "<td width='95px' align='right'>" . $total1 . "</td>";
        //			$str2 .= "<td width='95px' align='right'>" . $total2 . "</td>";
        //			$str2 .= "<td width='95px' align='right'>" . $total3 . "</td>";
        //			$str2 .= "<td width='95px' align='right'>" . $total4 . "</td>";
        //			$str2 .= "<td width='95px' align='right'>" . $total5 . "</td>";
        //			$str2 .= "<td width='95px' align='right'>" . $total6 . "</td>";
        //			$str2 .= "<td width='95px' align='right'>" . $total7 . "</td>";

        $str2 .= "<td width='110px' align='right'>" . $total1 . '</td>';
        $str2 .= "<td width='110px' align='right'>" . $total2 . '</td>';
        $str2 .= "<td width='110px' align='right'>" . $total3 . '</td>';
        $str2 .= "<td width='110px' align='right'>" . $total4 . '</td>';
        $str2 .= "<td width='110px' align='right'>" . $total5 . '</td>';
        $str2 .= "<td width='110px' align='right'>" . $total6 . '</td>';
        $str2 .= "<td width='110px' align='right'>" . $total7 . '</td>';

        $str2 .= '<tr>';
        $str2 .= '</table>';

        $str3 = '<table>';
        $str3 .= '<tr>';
        //$str3 .= "<td width='120px'><div class='sdh sdh09 label'>";
        $str3 .= "<td width='150px'><div class='sdh sdh09 label'>";
        $str3 .= '合計';
        $str3 .= '</div></td>';
        //$str3 .= "<td width='95px' align='right'>" . $total . "</td>";
        $str3 .= "<td width='110px' align='right'>" . $total . '</td>';
        $str3 .= '</tr>';
        $str3 .= '</table>';

        $result = array();
        $result['data1'] = $str;
        $result['data2'] = $str1;
        $result['data3'] = $str2;
        $result['data4'] = $str3;

        $this->fncReturn($result);
    }
}