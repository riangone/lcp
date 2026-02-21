<?php
/**
 * 説明：
 *
 *
 * @author jinmaiai
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * 20150611           ---                       項目取得仕様変更               HM
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH03 extends ClsComDb
{
    /**
     * 注文書情報取得.
     *
     * @param {String} 注文書ＮＯ
     *
     * @return {String} select文
     */
    public function M41E10_sql($cmn_no)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  M41E10.UC_NO, ';
        $str_sql .= '  M41E10.CMN_NO, ';
        $str_sql .= '  M41E10.BUY_SHP, ';
        $str_sql .= '  M41E10.ABHOT_KB, ';
        $str_sql .= '  M41E10.TOU_DT, ';
        $str_sql .= '  M41E10.HBSS_CD, ';
        $str_sql .= '  M41E10.SDI_KAT, ';
        $str_sql .= '  M41E10.CAR_NO, ';
        $str_sql .= "  M41E10.SDI_KAT || '-' || M41E10.CAR_NO AS CARNO, ";
        $str_sql .= '  M41E10.TOURK_NO, ';
        $str_sql .= '  M41C03.VCLNM AS VCLNM, ';
        $str_sql .= '  M41E10.KYK_YBN_NO, ';
        $str_sql .= '  M41E10.KYK_CUS_NM1, ';
        $str_sql .= '  M41E10.SIY_YBN_NO, ';
        $str_sql .= '  M41E10.SIY_CUS_NM1, ';
        $str_sql .= '  M41E10.DAIRITN_CD, ';

        //車両本体価格
        $str_sql .= '  M41E10.SRY_HTA_PRC_ZKM, ';
        //車両本体値引額
        $str_sql .= '  M41E10.SRY_HTA_NBK_GKU_ZKM, ';

        //付属品店頭引渡価格１
        //20150611 Update Start
        //$str_sql .= "  M41E10.FZH_SUM_GKU_ZKM , ";
        $str_sql .= '  M41E10.FZH_SUM_GKU_ZKM - M41E10.FZH_NBK_SUM_GKU_ZKM as FZH_SUM_GKU_ZKM, ';
        //20150611 Update End

        //特別架装
        $str_sql .= '  M41E10.TKB_KSH_SUM_GKU_ZKM, ';

        //特別架装値引
        $str_sql .= '  M41E10.TKB_KSH_NBK_SUM_GKU_ZKM, ';

        //割賦手数料
        $str_sql .= '  M41E10.KAP_TES, ';

        //別途支払費用
        $str_sql .= '  M41E10.BET_SHR_HYO_SUM_GKU_ZKM, ';

        //任意保険料
        $str_sql .= '  M41E10.OPT_HOK_KIN, ';

        //リサイクル預託金相当額
        //20150611 Update Start
//        $str_sql .= "  M41E10.TRA_CAR_RCY_YTK_SUM_GKU, ";
//        $str_sql .= " TO_NUMBER(CASE WHEN M41E11.ATSUKAI_KB ='1' THEN 0 ELSE M41E10.YOTAK_GK END ) AS TRA_CAR_RCY_YTK_SUM_GKU, ";
        $str_sql .= " TO_NUMBER( CASE WHEN M41E10.TOUROKU_UM = '2' THEN M41E10.YOTAK_GK ELSE 0 END )   AS TRA_CAR_RCY_YTK_SUM_GKU, ";
        //20150611 Update End

        //使用済車引取お客様支払額
        //20150611 Update Start
//        $str_sql .= "  M41E10.SIY_SMI_CAR_KNR_HYO, ";
        $str_sql .= " TO_NUMBER(CASE WHEN M41E11.ATSUKAI_KB ='1' THEN M41E11.YOTAK_GK + M41E11.MSY_TOU_TTK_DAIKO_HYO + M41E11.MSY_TOU_AZK_HTE_HYO + M41E11.SIY_SMI_CAR_SYR_HYO + M41E11.SHIKIN_KNR_RYOKIN ELSE 0 END) AS SIY_SMI_CAR_KNR_HYO, ";
        //20150611 Update End

        //現金（含申込金）
        $str_sql .= '  M41E10.SHR_GKN_DPS, ';
        //割賦元金
        //20150611 Update Start
//        $str_sql .= "  M41E10.KAP_MOT_KIN, ";
        $str_sql .= '  M41E10.KAP_MOT_KIN + M41E10.KRJ_MOT_KIN KAP_MOT_KIN, ';
        //20150611 Update End

        //割賦手数料
        $str_sql .= '  M41E10.KAP_TES, ';
        //賦払金計
        $str_sql .= '  M41E10.TRA_CAR_PRC_SUM, ';
        $str_sql .= '  M41E10.TRA_CAR_SHZ_SUM, ';
        $str_sql .= '  M41E10.TRA_CAR_ZSI_SUM, ';
        //20150611 Update Start
        //下取車リサイクル預託金相当額
//        $str_sql .= "  M41E10.TRA_CAR_RCY_YTK_SUM_GKU, ";
//        $str_sql .= "  M41E10.YOTAK_GK, ";
//        $str_sql .= "  TO_NUMBER(CASE WHEN M41E11.ATSUKAI_KB = '1' THEN 0 ELSE M41E11.RCYL_GK END) as TRA_CAR_RCY_YTK_SUM_GKU, ";
        //20150611 Update End

        $str_sql .= '  M41E10.SIY_ADR1, ';
        $str_sql .= '  M41E10.SIY_ADR2, ';
        $str_sql .= '  M41E10.SIY_ADR3, ';
        $str_sql .= '  M41E10.KYK_ADR1, ';
        $str_sql .= '  M41E10.KYK_ADR2, ';
        $str_sql .= '  M41E10.KYK_ADR3, ';

        //20150611 Update Start
//        $str_sql .= "  M41E11.RCYL_GK, ";
        $str_sql .= " TO_NUMBER(CASE WHEN M41E11.ATSUKAI_KB ='1' THEN 0 ELSE M41E11.RCYL_GK END ) RCYL_GK, ";
        //20150611 Update End

        //車両店頭引渡価格 = 車両本体価格-車両本体値引額
        $str_sql .= '  M41E10.SRY_HTA_PRC_ZKM - M41E10.SRY_HTA_NBK_GKU_ZKM AS SRY_TET_HWS_KKU, ';

        //現金価格計 = 車両店頭引渡価格+付属品店頭引渡価格1+付属品店頭引渡価格2
        //20150611 Update Start
//        $str_sql .= "  M41E10.SRY_HTA_PRC_ZKM - M41E10.SRY_HTA_NBK_GKU_ZKM + M41E10.FZH_SUM_GKU_ZKM + M41E10.TKB_KSH_SUM_GKU_ZKM AS KGK_KKU_KEI, ";
        $str_sql .= '  (M41E10.SRY_HTA_PRC_ZKM - M41E10.SRY_HTA_NBK_GKU_ZKM)  + (M41E10.FZH_SUM_GKU_ZKM - FZH_NBK_SUM_GKU_ZKM ) + (TKB_KSH_SUM_GKU_ZKM - TKB_KSH_NBK_SUM_GKU_ZKM) AS KGK_KKU_KEI, ';
        //20150611 Update End

        //販売価格合計 = 現金価格計+割賦手数料+別途支払費用+任意保険料
        //20150611 Update Start
//        $str_sql .= "  M41E10.SRY_HTA_PRC_ZKM - M41E10.SRY_HTA_NBK_GKU_ZKM + M41E10.FZH_SUM_GKU_ZKM + M41E10.TKB_KSH_SUM_GKU_ZKM + M41E10.OPT_HOK_KIN + M41E10.BET_SHR_HYO_SUM_GKU_ZKM + M41E10.KAP_TES AS HBA_KKU_GKE, ";
        $str_sql .= '  (M41E10.SRY_HTA_PRC_ZKM - M41E10.SRY_HTA_NBK_GKU_ZKM) + (M41E10.FZH_SUM_GKU_ZKM - FZH_NBK_SUM_GKU_ZKM ) + (TKB_KSH_SUM_GKU_ZKM - TKB_KSH_NBK_SUM_GKU_ZKM) + M41E10.OPT_HOK_KIN + M41E10.BET_SHR_HYO_SUM_GKU_ZKM + M41E10.KAP_TES AS HBA_KKU_GKE, ';
        //20150611 Update End

        //購入条件合計 = 販売価格合計+リサイクル預託金相当額+使用済車引取お客様支払額
        //20150611 Update Start
//        $str_sql .= "  M41E10.SRY_HTA_PRC_ZKM - M41E10.SRY_HTA_NBK_GKU_ZKM + M41E10.FZH_SUM_GKU_ZKM + M41E10.TKB_KSH_SUM_GKU_ZKM + M41E10.OPT_HOK_KIN + M41E10.BET_SHR_HYO_SUM_GKU_ZKM + M41E10.KAP_TES + M41E10.SIY_SMI_CAR_KNR_HYO + M41E10.TRA_CAR_RCY_YTK_SUM_GKU AS KONYU_ZYK_GOK, ";
//        $str_sql .= " (M41E10.SRY_HTA_PRC_ZKM - M41E10.SRY_HTA_NBK_GKU_ZKM) + (M41E10.FZH_SUM_GKU_ZKM - FZH_NBK_SUM_GKU_ZKM ) + (TKB_KSH_SUM_GKU_ZKM - TKB_KSH_NBK_SUM_GKU_ZKM) + M41E10.OPT_HOK_KIN + M41E10.BET_SHR_HYO_SUM_GKU_ZKM + M41E10.KAP_TES + TO_NUMBER(CASE WHEN M41E11.ATSUKAI_KB ='1' THEN M41E11.YOTAK_GK + M41E11.MSY_TOU_TTK_DAIKO_HYO + M41E11.MSY_TOU_AZK_HTE_HYO + M41E11.SIY_SMI_CAR_SYR_HYO + M41E11.SHIKIN_KNR_RYOKIN ELSE 0 END) AS KONYU_ZYK_GOK, ";
        $str_sql .= " (M41E10.SRY_HTA_PRC_ZKM - M41E10.SRY_HTA_NBK_GKU_ZKM) + (M41E10.FZH_SUM_GKU_ZKM - FZH_NBK_SUM_GKU_ZKM ) + (TKB_KSH_SUM_GKU_ZKM - TKB_KSH_NBK_SUM_GKU_ZKM) + M41E10.OPT_HOK_KIN + M41E10.BET_SHR_HYO_SUM_GKU_ZKM + M41E10.KAP_TES + TO_NUMBER( CASE WHEN M41E10.TOUROKU_UM = '2' THEN M41E10.YOTAK_GK ELSE 0 END ) + TO_NUMBER(CASE WHEN M41E11.ATSUKAI_KB ='1' THEN M41E11.YOTAK_GK + M41E11.MSY_TOU_TTK_DAIKO_HYO + M41E11.MSY_TOU_AZK_HTE_HYO + M41E11.SIY_SMI_CAR_SYR_HYO + M41E11.SHIKIN_KNR_RYOKIN ELSE 0 END)  AS KONYU_ZYK_GOK, ";
        //20150611 Update End

        //賦払金計 = 割賦元金+割賦手数料
        //20150611 Update Start
//        $str_sql .= "  M41E10.KAP_MOT_KIN + M41E10.KAP_TES AS KAP_KEI, ";
        $str_sql .= '  M41E10.KAP_MOT_KIN + M41E10.KAP_TES + M41E10.KRJ_MOT_KIN AS KAP_KEI, ';
        //20150611 Update End

        //支払金計 = 現金（含申込金）+賦払金計
        //20150611 Update Start
//        $str_sql .= "  M41E10.KAP_MOT_KIN + M41E10.KAP_TES + M41E10.SHR_GKN_DPS AS SIHARAI, ";
        $str_sql .= '  M41E10.KAP_MOT_KIN + M41E10.KAP_TES + M41E10.KRJ_MOT_KIN + M41E10.SHR_GKN_DPS AS SIHARAI, ';
        //20150611 Update End

        $str_sql .= '  M41E11.TRA_GK AS SIYOUZUMI, ';

        //下取/使用済車充当額計 = 下取車価格+下取車消費税額+下取車残債(－)+下取車リサイクル預託金相当額(+使用済車引取販売店支払額)
        //20150611 Update Start
        //$str_sql .= "  M41E10.TRA_CAR_PRC_SUM + M41E10.TRA_CAR_SHZ_SUM + M41E10.TRA_CAR_ZSI_SUM + NVL(M41E11.RCYL_GK,0) AS SITATORI ";
        $str_sql .= " M41E10.TRA_CAR_PRC_SUM + M41E10.TRA_CAR_SHZ_SUM + M41E10.TRA_CAR_ZSI_SUM + TO_NUMBER(CASE WHEN M41E11.ATSUKAI_KB = '1' THEN 0 ELSE nvl(M41E11.RCYL_GK,0) END) + TO_NUMBER(CASE WHEN M41E11.ATSUKAI_KB = '1' THEN nvl(M41E11.TRA_GK,0) ELSE 0 END) AS SITATORI ";
        //20150611 Update End

        $str_sql .= 'FROM ';
        $str_sql .= '  M41E10, ';
        $str_sql .= '  M41C03, ';
        $str_sql .= '  M41C04, ';
        $str_sql .= '  M41E11 ';

        $str_sql .= 'WHERE ';
        $str_sql .= "  M41E10.CMN_NO = '" . $cmn_no . "' ";
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.VIN_WMIVDS = M41E10.SDI_KAT ';
        $str_sql .= 'AND ';
        $str_sql .= '  TRIM(M41C04.VIN_VIS) = TRIM(M41E10.CAR_NO) ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.VIN_WMIVDS = M41C03.VIN_WMIVDS ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41C04.VIN_VIS = M41C03.VIN_VIS ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41E10.CMN_NO = M41E11.CMN_NO(+) ';

        return $str_sql;
    }

    /**
     * 陸事名称取得.
     *
     * @param {String} 陸事コード
     *
     * @return {String} select文
     */
    public function m_select_M27M14_sql($VCLRGTNO_LAND)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  M27M14.SCD_NM AS VCLRGTNO_LAND_NM ';
        $str_sql .= 'FROM ';
        $str_sql .= '  M27M14 ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  M27M14.SCD_SYSID = 'Z' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M27M14.SCD_ID = 'RIKUJI' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M27M14.SCD_VAL = '" . $VCLRGTNO_LAND . "' ";

        return $str_sql;
    }

    /**
     * 付属品明細１取得.
     *
     * @param {String} 注文書ＮＯ
     *
     * @return {String} select文
     */
    public function M41E12_1_sql($cmn_no)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  YHN_NM, ';
        $str_sql .= '  TTH_ICD_PRC_ZKM, ';
        $str_sql .= '  NBK_ZKM ';
        $str_sql .= 'FROM ';
        $str_sql .= '  M41E12,M41E10 ';
        $str_sql .= 'WHERE ';
        $str_sql .= '  FZH_TKB_KSH_KB =0 ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41E10.CMN_NO = M41E12.CMN_NO ';
        $str_sql .= 'AND ';
        $str_sql .= "  M41E10.CMN_NO = '" . $cmn_no . "'  ";

        return $str_sql;
    }

    /**
     * 付属品明細２取得.
     *
     * @param {String} 注文書ＮＯ
     *
     * @return {String} select文
     */
    public function M41E12_2_sql($cmn_no)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  YHN_NM, ';
        $str_sql .= '  TTH_ICD_PRC_ZKM, ';
        $str_sql .= '  NBK_ZKM ';
        $str_sql .= 'FROM ';
        $str_sql .= '  M41E12,M41E10 ';
        $str_sql .= 'WHERE ';
        $str_sql .= '  FZH_TKB_KSH_KB = 1 ';
        $str_sql .= 'AND ';
        $str_sql .= '  M41E10.CMN_NO = M41E12.CMN_NO ';
        $str_sql .= 'AND ';
        $str_sql .= "  M41E10.CMN_NO = '" . $cmn_no . "'  ";

        return $str_sql;
    }

    /**
     * 扱者取得.
     *
     * @param {String} 注文書ＮＯ
     *
     * @return {String} select文
     */
    public function m_select_sdh_08_sql($cmn_no)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  M27M01.KYOTN_RKN, ';
        $str_sql .= '  M29MA4.SYAIN_KNJ_SEI, ';
        $str_sql .= '  M29MA4.SYAIN_KNJ_MEI, ';
        $str_sql .= '  M27M08.DAIRITN_NM ';
        $str_sql .= 'FROM ';
        $str_sql .= '  M41E10 ';
        $str_sql .= '  LEFT JOIN M27M01 ON M27M01.KYOTN_CD = M41E10.HNB_KTN_CD ';
        $str_sql .= '  LEFT JOIN M29MA4 ON M29MA4.SYAIN_NO = M41E10.HNB_TAN_EMP_NO ';
        $str_sql .= '  LEFT JOIN M27M08 ON M27M08.DAIRITN_CD = M41E10.DAIRITN_CD ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  M41E10.CMN_NO = '" . $cmn_no . "' ";
        $str_sql .= 'AND ROWNUM BETWEEN 1 and 10 ';

        return $str_sql;
    }

    /**
     * 使用済車引取販売店支払額.
     *
     * @param {String} 注文書ＮＯ
     *
     * @return {String} select文
     */
    public function m_select_sdh_09_sql($cmn_no)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        //20150611 Update Start
//        $str_sql .= "  M41E11.SIY_SMI_CAR_SYR_HYO - M41E10.SIY_SMI_CAR_KNR_HYO as 支払額 ";
        $str_sql .= " TO_NUMBER(CASE WHEN M41E11.ATSUKAI_KB = '1' THEN M41E11.TRA_GK ELSE 0 END) as 支払額 ";
        //20150611 Update End
        $str_sql .= 'FROM ';
        $str_sql .= '  M41E10 , M41E11 ';
        $str_sql .= 'WHERE ';
        $str_sql .= '  M41E10.CMN_NO = M41E11.CMN_NO ';
        $str_sql .= "AND M41E10.CMN_NO = '" . $cmn_no . "' ";

        return $str_sql;
    }

    /**
     * 購入形態取得.
     *
     * @param {String} 購入形態コード
     *
     * @return {String} 購入形態名称
     */
    public function m_select_BUY_KEITAI_sql($id)
    {
        $BUY_KEITAI = array('1' => '新規', '2' => '自社代替', '3' => '他社代替', '4' => '自車先方処分', '5' => '他車先方処分', '6' => '増車継続', '7' => '増車他車継続');

        return $BUY_KEITAI[$id];
    }

    /**
     * 販売形態取得 登録日＜20130401の場合.
     *
     * @param {String} 販売形態コード
     *
     * @return {String} 販売形態名称
     */
    public function m_select_SALE_KEITAI_BF_sql($id)
    {
        $SALE_KEITAI_BF = array('01' => '平常活動', '02' => '来店来電', '05' => '店頭展示会', '06' => '出張展示会', '07' => 'ｲﾝﾀ-ﾈｯﾄ紹介', '10' => '顧客紹介', '11' => '基盤客紹介', '12' => '知人紹介', '31' => '認定法人', '30' => '準法人認定', '33' => 'ﾏﾂﾀﾞ関連紹介', '32' => '職域販売', '34' => '自社社員紹介', '29' => 'ﾏﾂﾀﾞ関連紹介', '39' => '自社社員販売', '28' => 'ﾏﾂﾀﾞ社員販売', '35' => '取引関連紹介', '38' => '社用車', '40' => '他社仕切', '04' => '業販店紹介', '50' => '副売');

        return $SALE_KEITAI_BF[$id];
    }

    /**
     * 販売形態取得 登録日>=20130401の場合.
     *
     * @param {String} 販売形態コード
     *
     * @return {String} 販売形態名称
     */
    public function m_select_SALE_KEITAI_AF_sql($id)
    {
        $SALE_KEITAI_AF = array('01' => '平常日促進活動', '02' => '平常日来店・来電', '05' => '店頭展示会', '06' => '出張展示会', '32' => '職域促進活動', '07' => 'ｲﾝﾀ-ﾈｯﾄ源泉', '10' => '紹介', '04' => '業販', '36' => '解体', '40' => 'ﾏﾂﾀﾞ販社間取引', '31' => 'MC認定法人', '30' => '一般法人', '41' => 'ﾀｲﾑｽﾞﾚﾝﾀｶｰ', '42' => '大口ﾚﾝﾀｶｰ', '28' => 'MC社員販売', '29' => 'MC社員紹介', '43' => 'MC関連社員販売', '33' => 'MC関連社員紹介', '39' => '自社社員販売', '34' => '自社社員紹介', '38' => 'デモカー', '44' => 'サービスカー', '45' => '社用車');

        return $SALE_KEITAI_AF[$id];
    }

    public function m_select_M41E10($cmn_no)
    {
        $str_sql = $this->M41E10_sql($cmn_no);

        return parent::select($str_sql);
    }

    public function m_select_M27M14($VCLRGTNO_LAND)
    {
        $str_sql = $this->m_select_M27M14_sql($VCLRGTNO_LAND);

        return parent::select($str_sql);
    }

    public function m_select_M41E12_1_sql($cmn_no)
    {
        $str_sql = $this->M41E12_1_sql($cmn_no);

        return parent::select($str_sql);
    }

    public function m_select_M41E12_2_sql($cmn_no)
    {
        $str_sql = $this->M41E12_2_sql($cmn_no);

        return parent::select($str_sql);
    }

    public function m_select_sdh_08($cmn_no)
    {
        $str_sql = $this->m_select_sdh_08_sql($cmn_no);

        return parent::select($str_sql);
    }

    public function m_select_sdh_09($cmn_no)
    {
        $str_sql = $this->m_select_sdh_09_sql($cmn_no);

        return parent::select($str_sql);
    }
}