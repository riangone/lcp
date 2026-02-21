<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmSCUriageMeisai extends ClsComDb
{
    //**********************************************************************
    //処 理 名：SQL作成
    //関 数 名：FncSelectMeisaiSQL
    //引    数：無し
    //戻 り 値：SQL
    //処理説明：初期表示のためのSQL作成
    //**********************************************************************
    public function FncSelectMeisaiSQL($CmnNO)
    {
        $strSQL = "";
        $strSQL .= "SELECT    URI.KEIJYO_YM" . "\r\n";
        $strSQL .= ",         URI.NAU_KB" . "\r\n";
        $strSQL .= ",         URI.CMN_NO" . "\r\n";
        $strSQL .= ",         (SELECT COUNT(CMN_NO) FROM HJYOUHEN JYO WHERE JYO.CMN_NO = '@CMNNO') RIR_COUNT" . "\r\n";
        $strSQL .= ",         URI.DATA_KB" . "\r\n";
        $strSQL .= ",         URI.UC_NO" . "\r\n";
        $strSQL .= ",         URI.URI_BUSYO_CD" . "\r\n";
        $strSQL .= ",         URI.URI_TANNO" . "\r\n";
        $strSQL .= ",         URI.URI_GYOSYA" . "\r\n";
        $strSQL .= ",         URI.SAV_KTNCD" . "\r\n";
        $strSQL .= ",         URI.URK_BUSYO_CD" . "\r\n";
        $strSQL .= ",         URI.KYK_HNS" . "\r\n";
        $strSQL .= ",         URI.TOU_HNS" . "\r\n";
        $strSQL .= ",         URI.NTI_USR_CD" . "\r\n";
        $strSQL .= ",         URI.TOU_DATE" . "\r\n";
        $strSQL .= ",         URI.URG_DATE" . "\r\n";
        $strSQL .= ",         URI.KRI_DATE" . "\r\n";
        $strSQL .= ",         URI.CEL_DATE" . "\r\n";
        $strSQL .= ",         URI.SYADAI" . "\r\n";
        $strSQL .= ",         URI.CARNO" . "\r\n";
        $strSQL .= ",         URI.MAKER_CD" . "\r\n";
        $strSQL .= ",         URI.NENSIKI" . "\r\n";
        $strSQL .= ",         URI.SITEI_NO" . "\r\n";
        $strSQL .= ",         URI.RUIBETU_NO" . "\r\n";
        $strSQL .= ",         URI.SS_CD" . "\r\n";
        $strSQL .= ",         URI.TOA_NAME" . "\r\n";
        $strSQL .= ",         URI.HBSS_CD" . "\r\n";
        $strSQL .= ",         URI.KASOUNO" . "\r\n";
        $strSQL .= ",         URI.YOHIN_A" . "\r\n";
        $strSQL .= ",         URI.YOHIN_C" . "\r\n";
        $strSQL .= ",         URI.YOHIN_H" . "\r\n";
        $strSQL .= ",         URI.YOHIN_S" . "\r\n";
        $strSQL .= ",         URI.RIKUJI_CD" . "\r\n";
        $strSQL .= ",         URI.TOURK_NO1" . "\r\n";
        $strSQL .= ",         URI.TOURK_NO2" . "\r\n";
        $strSQL .= ",         URI.TOURK_NO3" . "\r\n";
        $strSQL .= ",         URI.H59" . "\r\n";
        $strSQL .= ",         URI.SYAKEN_EXP_DT" . "\r\n";
        $strSQL .= ",         URI.KKR_CD" . "\r\n";
        $strSQL .= ",         URI.NINKATA_CD" . "\r\n";
        $strSQL .= ",         URI.SYANAI_KOSYO" . "\r\n";
        $strSQL .= ",         URI.CHUKOSYA_SYD_YM" . "\r\n";
        $strSQL .= ",         URI.CHUKOSYA_NYK_YM" . "\r\n";
        $strSQL .= ",         URI.CHUMON_KB" . "\r\n";
        $strSQL .= ",         URI.KASOU_KB" . "\r\n";
        $strSQL .= ",         URI.AIM_FLG" . "\r\n";
        $strSQL .= ",         URI.AIM_KBN" . "\r\n";
        $strSQL .= ",         URI.KYK_KB" . "\r\n";
        $strSQL .= ",         URI.ZERO_KB" . "\r\n";
        $strSQL .= ",         URI.SYOYUKEN_KB" . "\r\n";
        $strSQL .= ",         URI.ITEN_KB" . "\r\n";
        $strSQL .= ",         URI.YOUTO_KB" . "\r\n";
        $strSQL .= ",         URI.KANRI_KB" . "\r\n";
        $strSQL .= ",         URI.HNBCHK_KB" . "\r\n";
        $strSQL .= ",         URI.KGO_KB" . "\r\n";
        $strSQL .= ",         URI.BUY_SHP" . "\r\n";
        $strSQL .= ",         URI.MZD_SIY" . "\r\n";
        $strSQL .= ",         URI.LEASE_KB" . "\r\n";
        $strSQL .= ",         URI.LEASE_KB2" . "\r\n";
        $strSQL .= ",         URI.LES_SHP1" . "\r\n";
        $strSQL .= ",         URI.LES_SHP2" . "\r\n";
        $strSQL .= ",         URI.KAP_KB" . "\r\n";
        $strSQL .= ",         URI.HNB_KB" . "\r\n";
        $strSQL .= ",         URI.OPT_HOK_KNY_KB" . "\r\n";
        $strSQL .= ",         URI.TRK_KB" . "\r\n";
        $strSQL .= ",         URI.ZAIKO_KB" . "\r\n";
        $strSQL .= ",         URI.KSO_KENSA" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_NAIYO" . "\r\n";
        $strSQL .= ",         URI.KSO_KB" . "\r\n";
        $strSQL .= ",         URI.KAZEI_KB" . "\r\n";
        $strSQL .= ",         URI.DAINO_FLG" . "\r\n";
        $strSQL .= ",         URI.JIBAI_FLG" . "\r\n";
        $strSQL .= ",         URI.SIH_SIT_KB" . "\r\n";
        $strSQL .= ",         URI.PAY_OFF_FLG" . "\r\n";
        $strSQL .= ",         URI.SWK_SUM_FLG" . "\r\n";
        $strSQL .= ",         URI.CKG_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_HNB_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_SS_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_SIR_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_SEB_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_MEG_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_MHN_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_UCNO" . "\r\n";
        $strSQL .= ",         URI.CKO_CAR_SER_NO" . "\r\n";
        $strSQL .= ",         URI.JKN_HKD_AK" . "\r\n";
        $strSQL .= ",         URI.JKN_HK_NAIYO" . "\r\n";
        $strSQL .= ",         URI.JKN_HKD" . "\r\n";
        $strSQL .= ",         URI.JKN_NO" . "\r\n";
        $strSQL .= ",         URI.GYO_NAME" . "\r\n";
        $strSQL .= ",         URI.MEG_NAME" . "\r\n";
        $strSQL .= ",         URI.TGT_SIT" . "\r\n";
        $strSQL .= ",         URI.SRY_PRC" . "\r\n";
        $strSQL .= ",         URI.SRY_NBK" . "\r\n";
        $strSQL .= ",         URI.SRY_CMN_PCS" . "\r\n";
        $strSQL .= ",         URI.SRY_KTN_PCS" . "\r\n";
        $strSQL .= ",         URI.SRY_BUY_PCS" . "\r\n";
        $strSQL .= ",         URI.SRY_SHZ_RT" . "\r\n";
        $strSQL .= ",         URI.SRY_SHZ" . "\r\n";
        $strSQL .= ",         URI.FHZ_TEIKA" . "\r\n";
        $strSQL .= ",         URI.FHZ_NBK" . "\r\n";
        $strSQL .= ",         URI.FHZ_KYK" . "\r\n";
        $strSQL .= ",         URI.FHZ_PCS" . "\r\n";
        $strSQL .= ",         URI.FHZ_SHZ" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_TEIKA" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_NBK" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_KYK" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_PCS" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_SHZ" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_KYK" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_KJN" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_RT" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_SHZ" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_KYK" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_KJN" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SHZ" . "\r\n";
        $strSQL .= ",         URI.HOUTEIH_GK" . "\r\n";
        $strSQL .= ",         URI.HKN_GK" . "\r\n";
        $strSQL .= ",         URI.TRA_CAR_ZSI_SUM" . "\r\n";
        $strSQL .= ",         URI.SHR_GK_SUM" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_SIT_KIN" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_SIT_SHZ" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_ATM_KIN" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_TRK_SYH" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_CKO_FTK" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_TGT_KAI" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_TGT_KIN" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_KRJ_KAI" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_KRJ_KIN" . "\r\n";
        $strSQL .= ",         URI.KUREJITGAISYA" . "\r\n";
        $strSQL .= ",         URI.KUREJIT_NO" . "\r\n";
        $strSQL .= ",         URI.TRA_CAR_RCYYTK_SUM" . "\r\n";
        $strSQL .= ",         URI.KAP_GKN" . "\r\n";
        $strSQL .= ",         URI.TRA_CAR_PRC_SUM" . "\r\n";
        $strSQL .= ",         URI.TRA_CAR_STI_SUM" . "\r\n";
        $strSQL .= ",         URI.JIDOUSYA_ZEI" . "\r\n";
        $strSQL .= ",         URI.SYARYOU_ZEI" . "\r\n";
        $strSQL .= ",         URI.EAKON_ZEI" . "\r\n";
        $strSQL .= ",         URI.SUTEREO_ZEI" . "\r\n";
        $strSQL .= ",         URI.JYURYO_ZEI" . "\r\n";
        $strSQL .= ",         URI.SHZ_KEI" . "\r\n";
        $strSQL .= ",         URI.JIBAI_SITEI" . "\r\n";
        $strSQL .= ",         URI.JIBAI_KAISYA" . "\r\n";
        $strSQL .= ",         URI.JIBAI_CAR_KND" . "\r\n";
        $strSQL .= ",         URI.JIBAI_ICOL_CD" . "\r\n";
        $strSQL .= ",         URI.JIBAI_TUKI_SU" . "\r\n";
        $strSQL .= ",         URI.JIBAI_HOK_RYO" . "\r\n";
        $strSQL .= ",         URI.OPTHOK_RYO" . "\r\n";
        $strSQL .= ",         URI.HNB_TES_RYO_KZI_KBN" . "\r\n";
        $strSQL .= ",         URI.HNB_TES_RYO_SHR_CD" . "\r\n";
        $strSQL .= ",         URI.HNB_TES_GKU" . "\r\n";
        $strSQL .= ",         URI.HNB_SHZ" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_KEN" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SYAKEN" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SYAKO_SYO" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_NOUSYA" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SIT_TTK" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SATEI" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_JIKOU" . "\r\n";
        //  登録諸費用その他に含まれていたパックＤＥ753を別項目としたため、またパックＤＥメンテ追加のため

        $strSQL .= ",         (NVL(URI.TOU_SYH_ETC,0) + NVL(URI.PACK_DE_753,0) + NVL(URI.PACK_DE_MENTE,0)) TOU_SYH_ETC" . "\r\n";

        $strSQL .= ",         URI.HOUTEIH_KEN" . "\r\n";
        $strSQL .= ",         URI.HOUTEIH_SYAKEN" . "\r\n";
        $strSQL .= ",         URI.HOUTEIH_SYAKO_SYO" . "\r\n";
        $strSQL .= ",         URI.HOUTEIH_SIT" . "\r\n";
        $strSQL .= ",         URI.HONBU_FTK" . "\r\n";
        $strSQL .= ",         URI.UKM_SNY_TES" . "\r\n";
        $strSQL .= ",         URI.UKM_SINSEI_SYR" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_SGK" . "\r\n";
        $strSQL .= ",         URI.ETC_SKI_RYO" . "\r\n";
        $strSQL .= ",         URI.SRY_GENKAI_RIE" . "\r\n";
        $strSQL .= ",         URI.PENALTY" . "\r\n";
        $strSQL .= ",         URI.EGO_GAI_SYUEKI" . "\r\n";
        $strSQL .= ",         URI.SAI_SONEKI" . "\r\n";
        $strSQL .= ",         URI.TOK_KEI_KHN_MGN" . "\r\n";
        $strSQL .= ",         URI.TOK_KEI_RUI_MGN" . "\r\n";
        $strSQL .= ",         URI.TOK_KEI_KHN_SYR" . "\r\n";
        $strSQL .= ",         URI.TOK_KEI_TOK_KIN" . "\r\n";
        $strSQL .= ",         URI.GNK_HJN_PCS" . "\r\n";
        $strSQL .= ",         URI.GNK_SIT_URI_SKI" . "\r\n";
        $strSQL .= ",         URI.CKO_BAI_SIT_KIN" . "\r\n";
        $strSQL .= ",         URI.CKO_BAI_SATEI" . "\r\n";
        $strSQL .= ",         URI.CKO_SAI_MITUMORI" . "\r\n";
        $strSQL .= ",         URI.CKO_SYOGAKARI" . "\r\n";
        $strSQL .= ",         URI.CKO_BAI_SATEI_SOKAI" . "\r\n";
        $strSQL .= ",         URI.CKO_MIKEI_JDO_KIN" . "\r\n";
        $strSQL .= ",         URI.CKO_MIKEI_JDO_SHZ" . "\r\n";
        $strSQL .= ",         URI.CKO_MIKEI_JIBAI_KIN" . "\r\n";
        $strSQL .= ",         URI.CKO_MIKEI_JIBAI_SHZ" . "\r\n";
        $strSQL .= ",         URI.NYK_KB" . "\r\n";
        $strSQL .= ",         URI.DM_KB" . "\r\n";
        $strSQL .= ",         URI.KYOUSINKAI_KYK" . "\r\n";
        $strSQL .= ",         URI.KYOUSINKAI_SKI" . "\r\n";
        $strSQL .= ",         URI.KYOUSINKAI_KOKEN" . "\r\n";
        $strSQL .= ",         URI.NEBIKI_RT" . "\r\n";
        $strSQL .= ",         URI.KIJUN_NEBIKI_RT" . "\r\n";
        $strSQL .= ",         URI.KOUSEI_SYOSYO" . "\r\n";
        $strSQL .= ",         URI.JAF" . "\r\n";
        $strSQL .= ",         URI.KICK_BACK" . "\r\n";
        $strSQL .= ",         URI.YOTAK_KB" . "\r\n";
        $strSQL .= ",         URI.RCY_YOT_KIN" . "\r\n";
        $strSQL .= ",         URI.RCY_SKN_KAN_HI" . "\r\n";
        $strSQL .= ",         URI.URI_DAISU" . "\r\n";
        $strSQL .= ",         URI.TOU_DAISU" . "\r\n";
        $strSQL .= ",         URI.KYK_YUBIN_NO" . "\r\n";
        $strSQL .= ",         URI.KYK_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= ",         URI.KYK_KEY_CKU_CD" . "\r\n";
        $strSQL .= ",         URI.KYK_KEY_TEL" . "\r\n";
        $strSQL .= ",         URI.KYK_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= ",         URI.KYK_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= ",         URI.KYK_ADR_MEI" . "\r\n";
        $strSQL .= ",         URI.KYK_MEI_KNJ1" . "\r\n";
        $strSQL .= ",         URI.KYK_MEI_KNJ2" . "\r\n";
        $strSQL .= ",         URI.KYK_ADR_KN" . "\r\n";
        $strSQL .= ",         URI.KYK_MEI_KN" . "\r\n";
        $strSQL .= ",         URI.MGN_YUBIN_NO" . "\r\n";
        $strSQL .= ",         URI.MGN_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= ",         URI.MGN_KEY_CKU_CD" . "\r\n";
        $strSQL .= ",         URI.MGN_KEY_TEL" . "\r\n";
        $strSQL .= ",         URI.MGN_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= ",         URI.MGN_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= ",         URI.MGN_ADR_MEI" . "\r\n";
        $strSQL .= ",         URI.MGN_MEI_KNJ1" . "\r\n";
        $strSQL .= ",         URI.MGN_MEI_KNJ2" . "\r\n";
        $strSQL .= ",         URI.MGN_ADR_KN" . "\r\n";
        $strSQL .= ",         URI.MGN_MEI_KN" . "\r\n";
        $strSQL .= ",         URI.DEL_DATE" . "\r\n";
        $strSQL .= ",         URI.UPD_DATE" . "\r\n";
        $strSQL .= ",         URI.CREATE_DATE" . "\r\n";
        $strSQL .= ",         (CASE WHEN NVL(URI.CEL_DATE,' ') = ' ' THEN '' ELSE '解約' END) KAIYAKU" . "\r\n";
        $strSQL .= ",         (SYO.SKP_NM1 || ' ' || SYO.SKP_NM2) SHIHARAI_MEI" . "\r\n";
        $strSQL .= ",         BUS.BUSYO_NM BUSYO_MEI" . "\r\n";

        $strSQL .= ",         SYA.SYAIN_NM SYAIN_MEI" . "\r\n";

        $strSQL .= ",         (TRI.ATO_DTRPITNM1 || ' ' || TRI.ATO_DTRPITNM2) GYOUSYA_MEI" . "\r\n";

        $strSQL .= ",         K_TEN.HANSH_NM KYK_TEN_MEI" . "\r\n";
        $strSQL .= ",         T_TEN.HANSH_NM TOU_TEN_MEI" . "\r\n";

        $strSQL .= ",         DECODE(URI.NYK_KB,'1','有','無') NYK_NM " . "\r\n";
        //入庫約束名
        $strSQL .= ",         DECODE(URI.DM_KB,'1','要','不要') DM_NM " . "\r\n";
        //DM送付
        $strSQL .= ",         M_CRE.MEISYOU_RN CRE_NM" . "\r\n";
        //ｸﾚｼﾞｯﾄ会社名
        $strSQL .= ",         M_ZKB.MEISYOU_RN ZKB_NM" . "\r\n";
        //課税区分
        $strSQL .= ",         M_SYK.MEISYOU_RN SYK_NM" . "\r\n";
        //所有権
        $strSQL .= ",         M_YOT.MEISYOU_RN YOT_NM" . "\r\n";
        //用途区分
        $strSQL .= ",         M_CLR.MEISYOU_RN CLR_NM" . "\r\n";
        //色
        $strSQL .= ",         M_HNB.MEISYOU_RN HNB_NM" . "\r\n";
        //販売区分
        $strSQL .= ",         M_SIR.MEISYOU_RN SIR_NM" . "\r\n";
        //仕入区分
        $strSQL .= ",         M_MGK.MEISYOU_RN MGK_NM" . "\r\n";
        //名変

        $strSQL .= "FROM      HSCURI URI" . "\r\n";
        $strSQL .= "LEFT JOIN " . "\r\n";
        $strSQL .= "          (SELECT SCO.CMN_NO, SCO.SKP_NM1, SCO.SKP_NM2" . "\r\n";
        $strSQL .= "           FROM   M41E13 SCO" . "\r\n";
        $strSQL .= "           ,      (SELECT CMN_NO, MIN(SKP_SEQ_NO) SEQ_NO" . "\r\n";
        $strSQL .= "                   FROM   M41E13 " . "\r\n";
        $strSQL .= "                   GROUP  BY CMN_NO) M_SCO" . "\r\n";
        $strSQL .= "           WHERE  SCO.CMN_NO = M_SCO.CMN_NO" . "\r\n";
        $strSQL .= "           AND    SCO.SKP_SEQ_NO = M_SCO.SEQ_NO" . "\r\n";
        $strSQL .= "          ) SYO" . "\r\n";
        $strSQL .= "ON        SYO.CMN_NO = URI.CMN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON        BUS.BUSYO_CD = URI.URI_BUSYO_CD" . "\r\n";

        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";

        $strSQL .= "ON        SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "LEFT JOIN M28M68 TRI" . "\r\n";
        $strSQL .= "ON        TRI.ATO_DTRPITCD = URI.URI_GYOSYA " . "\r\n";

        $strSQL .= "LEFT JOIN M27M18 K_TEN" . "\r\n";
        $strSQL .= "ON        K_TEN.HANSH_CD = URI.KYK_HNS" . "\r\n";
        $strSQL .= "LEFT JOIN M27M18 T_TEN" . "\r\n";
        $strSQL .= "ON        T_TEN.HANSH_CD = URI.TOU_HNS" . "\r\n";

        //色名称
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '10') M_CLR" . "\r\n";
        $strSQL .= "ON        M_CLR.MEISYOU_CD = URI.JIBAI_ICOL_CD" . "\r\n";
        //ｸﾚｼﾞｯﾄ会社名
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '12') M_CRE" . "\r\n";
        $strSQL .= "ON        M_CRE.MEISYOU_CD = URI.KUREJITGAISYA" . "\r\n";
        //課税区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '11') M_ZKB" . "\r\n";
        $strSQL .= "ON        M_ZKB.MEISYOU_CD = URI.KAZEI_KB" . "\r\n";
        //所有権
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '16') M_SYK" . "\r\n";
        $strSQL .= "ON        M_SYK.MEISYOU_CD = URI.SYOYUKEN_KB" . "\r\n";
        //用途区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '13') M_YOT" . "\r\n";
        $strSQL .= "ON        M_YOT.MEISYOU_CD = URI.YOUTO_KB" . "\r\n";
        //販売区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '17') M_HNB" . "\r\n";
        $strSQL .= "ON        M_HNB.MEISYOU_CD = URI.CKO_HNB_KB" . "\r\n";
        //仕入区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '18') M_SIR" . "\r\n";
        $strSQL .= "ON        M_SIR.MEISYOU_CD = URI.CKO_SIR_KB" . "\r\n";
        //名義人区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '19') M_MGK" . "\r\n";
        $strSQL .= "ON        M_MGK.MEISYOU_CD = URI.CKO_MEG_KB" . "\r\n";

        $strSQL .= " WHERE URI.CMN_NO = '@CMNNO'" . "\r\n";

        $strSQL = str_replace("@CMNNO", $CmnNO, $strSQL);

        return $strSQL;
    }

    public function FncSelectMeisai($CmnNO)
    {
        return parent::select($this->FncSelectMeisaiSQL($CmnNO));
    }

    //**********************************************************************
    //処 理 名：SQL作成
    //関 数 名：FncSelectSitadoriSQL
    //引    数：無し
    //戻 り 値：SQL文
    //処理説明：存在チェックのためのSQL作成
    //**********************************************************************
    public function FncSelectSitadoriSQL($strCMN_NO, $strTblName, $strRIR_NO)
    {
        $strSQL = "";
        $strSQL .= "SELECT CMN_NO SEIRI_NO" . "\r\n";
        $strSQL .= ",      TRA_CARSEQ_NO" . "\r\n";
        $strSQL .= ",      SIT_SW" . "\r\n";
        $strSQL .= ",      MEIGARA" . "\r\n";
        $strSQL .= ",      SYAMEI" . "\r\n";
        $strSQL .= ",      SEIREKI_NEN" . "\r\n";
        $strSQL .= ",      SYAKEN_KAT" . "\r\n";
        $strSQL .= ",      CARNO" . "\r\n";
        $strSQL .= ",      KATASIKI" . "\r\n";
        $strSQL .= ",      RUIIBETU" . "\r\n";
        $strSQL .= ",      SUBSTR(TOU_Y_DT,3,2) TOU_NEN" . "\r\n";
        $strSQL .= ",      SUBSTR(TOU_Y_DT,5,2) TOU_TUKI" . "\r\n";
        $strSQL .= ",      SUBSTR(TOU_Y_DT,7,2) TOU_HI" . "\r\n";
        $strSQL .= ",      (TOU_NO1 || TOU_NO2 || TOU_NO3) TOUROKU_NO" . "\r\n";
        $strSQL .= ",      RIKUJI" . "\r\n";
        $strSQL .= ",      SIT_KIN" . "\r\n";
        $strSQL .= ",      SAT_KIN" . "\r\n";
        $strSQL .= ",      SHZ_RT" . "\r\n";
        $strSQL .= ",      SHZ_GK" . "\r\n";
        $strSQL .= ",      YOTAK_GK" . "\r\n";
        $strSQL .= ",      SHIKIN_KNR_RYOKIN" . "\r\n";
        $strSQL .= ",      YOTAK_KB" . "\r\n";
        $strSQL .= ",      TEBANASHI_KB" . "\r\n";

        $strSQL .= "FROM   @TABLENAME" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";
        if ($strRIR_NO >= "2") {
            $strSQL .= "AND  JKN_HKO_RIRNO = @RIRNO" . "\r\n";
        }
        $strSQL .= "ORDER BY TRA_CARSEQ_NO" . "\r\n";
        $strSQL = str_replace("@TABLENAME", $strTblName, $strSQL);
        $strSQL = str_replace("@CMNNO", $strCMN_NO, $strSQL);
        $strSQL = str_replace("@RIRNO", $strRIR_NO, $strSQL);
        return $strSQL;
    }

    public function FncSelectSitadori($strCMN_NO, $strTblName, $strRIR_NO)
    {
        return parent::select($this->FncSelectSitadoriSQL($strCMN_NO, $strTblName, $strRIR_NO));
    }

    public function fncSelectJyohenSQL($strCmnNO, $intCnt)
    {
        $strSQL = "";
        $strSQL .= "SELECT * FROM (" . "\r\n";
        $strSQL .= "SELECT    URI.KEIJYO_YM" . "\r\n";
        $strSQL .= ",         ROW_NUMBER() OVER (ORDER BY JKN_HKO_RIRNO DESC) CT" . "\r\n";
        //履歴番号でソートをかけたものに連番をふる
        $strSQL .= ",         (SELECT COUNT(CMN_NO) FROM HJYOUHEN JYO WHERE JYO.CMN_NO = '@CMNNO') RIR_COUNT" . "\r\n";
        $strSQL .= ",         URI.JKN_HKO_RIRNO" . "\r\n";
        $strSQL .= ",         URI.NAU_KB" . "\r\n";
        $strSQL .= ",         URI.CMN_NO" . "\r\n";
        $strSQL .= ",         URI.DATA_KB" . "\r\n";
        $strSQL .= ",         URI.UC_NO" . "\r\n";
        $strSQL .= ",         URI.URI_BUSYO_CD" . "\r\n";
        $strSQL .= ",         URI.URI_TANNO" . "\r\n";
        $strSQL .= ",         URI.URI_GYOSYA" . "\r\n";
        $strSQL .= ",         URI.SAV_KTNCD" . "\r\n";
        $strSQL .= ",         URI.URK_BUSYO_CD" . "\r\n";
        $strSQL .= ",         URI.KYK_HNS" . "\r\n";
        $strSQL .= ",         URI.TOU_HNS" . "\r\n";
        $strSQL .= ",         URI.NTI_USR_CD" . "\r\n";
        $strSQL .= ",         URI.TOU_DATE" . "\r\n";
        $strSQL .= ",         URI.URG_DATE" . "\r\n";
        $strSQL .= ",         URI.KRI_DATE" . "\r\n";
        $strSQL .= ",         URI.CEL_DATE" . "\r\n";
        $strSQL .= ",         URI.SYADAI" . "\r\n";
        $strSQL .= ",         URI.CARNO" . "\r\n";
        $strSQL .= ",         URI.MAKER_CD" . "\r\n";
        $strSQL .= ",         URI.NENSIKI" . "\r\n";
        $strSQL .= ",         URI.SITEI_NO" . "\r\n";
        $strSQL .= ",         URI.RUIBETU_NO" . "\r\n";
        $strSQL .= ",         URI.SS_CD" . "\r\n";
        $strSQL .= ",         URI.TOA_NAME" . "\r\n";
        $strSQL .= ",         URI.HBSS_CD" . "\r\n";
        $strSQL .= ",         URI.KASOUNO" . "\r\n";
        $strSQL .= ",         URI.YOHIN_A" . "\r\n";
        $strSQL .= ",         URI.YOHIN_C" . "\r\n";
        $strSQL .= ",         URI.YOHIN_H" . "\r\n";
        $strSQL .= ",         URI.YOHIN_S" . "\r\n";
        $strSQL .= ",         URI.RIKUJI_CD" . "\r\n";
        $strSQL .= ",         URI.TOURK_NO1" . "\r\n";
        $strSQL .= ",         URI.TOURK_NO2" . "\r\n";
        $strSQL .= ",         URI.TOURK_NO3" . "\r\n";
        $strSQL .= ",         URI.H59" . "\r\n";
        $strSQL .= ",         URI.SYAKEN_EXP_DT" . "\r\n";
        $strSQL .= ",         URI.KKR_CD" . "\r\n";
        $strSQL .= ",         URI.NINKATA_CD" . "\r\n";
        $strSQL .= ",         URI.SYANAI_KOSYO" . "\r\n";
        $strSQL .= ",         URI.CHUKOSYA_SYD_YM" . "\r\n";
        $strSQL .= ",         URI.CHUKOSYA_NYK_YM" . "\r\n";
        $strSQL .= ",         URI.CHUMON_KB" . "\r\n";
        $strSQL .= ",         URI.KASOU_KB" . "\r\n";
        $strSQL .= ",         URI.AIM_FLG" . "\r\n";
        $strSQL .= ",         URI.AIM_KBN" . "\r\n";
        $strSQL .= ",         URI.KYK_KB" . "\r\n";
        $strSQL .= ",         URI.ZERO_KB" . "\r\n";
        $strSQL .= ",         URI.SYOYUKEN_KB" . "\r\n";
        $strSQL .= ",         URI.ITEN_KB" . "\r\n";
        $strSQL .= ",         URI.YOUTO_KB" . "\r\n";
        $strSQL .= ",         URI.KANRI_KB" . "\r\n";
        $strSQL .= ",         URI.HNBCHK_KB" . "\r\n";
        $strSQL .= ",         URI.KGO_KB" . "\r\n";
        $strSQL .= ",         URI.BUY_SHP" . "\r\n";
        $strSQL .= ",         URI.MZD_SIY" . "\r\n";
        $strSQL .= ",         URI.LEASE_KB" . "\r\n";
        $strSQL .= ",         URI.LEASE_KB2" . "\r\n";
        $strSQL .= ",         URI.LES_SHP1" . "\r\n";
        $strSQL .= ",         URI.LES_SHP2" . "\r\n";
        $strSQL .= ",         URI.KAP_KB" . "\r\n";
        $strSQL .= ",         URI.HNB_KB" . "\r\n";
        $strSQL .= ",         URI.OPT_HOK_KNY_KB" . "\r\n";
        $strSQL .= ",         URI.TRK_KB" . "\r\n";
        $strSQL .= ",         URI.ZAIKO_KB" . "\r\n";
        $strSQL .= ",         URI.KSO_KENSA" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_NAIYO" . "\r\n";
        $strSQL .= ",         URI.KSO_KB" . "\r\n";
        $strSQL .= ",         URI.KAZEI_KB" . "\r\n";
        $strSQL .= ",         URI.DAINO_FLG" . "\r\n";
        $strSQL .= ",         URI.JIBAI_FLG" . "\r\n";
        $strSQL .= ",         URI.SIH_SIT_KB" . "\r\n";
        $strSQL .= ",         URI.PAY_OFF_FLG" . "\r\n";
        $strSQL .= ",         URI.SWK_SUM_FLG" . "\r\n";
        $strSQL .= ",         URI.CKG_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_HNB_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_SS_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_SIR_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_SEB_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_MEG_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_MHN_KB" . "\r\n";
        $strSQL .= ",         URI.CKO_UCNO" . "\r\n";
        $strSQL .= ",         URI.CKO_CAR_SER_NO" . "\r\n";
        $strSQL .= ",         URI.JKN_HKD_AK" . "\r\n";
        $strSQL .= ",         URI.JKN_HK_NAIYO" . "\r\n";
        $strSQL .= ",         URI.JKN_HKD" . "\r\n";
        $strSQL .= ",         URI.JKN_NO" . "\r\n";
        $strSQL .= ",         URI.GYO_NAME" . "\r\n";
        $strSQL .= ",         URI.MEG_NAME" . "\r\n";
        $strSQL .= ",         URI.TGT_SIT" . "\r\n";
        $strSQL .= ",         URI.SRY_PRC" . "\r\n";
        $strSQL .= ",         URI.SRY_NBK" . "\r\n";
        $strSQL .= ",         URI.SRY_CMN_PCS" . "\r\n";
        $strSQL .= ",         URI.SRY_KTN_PCS" . "\r\n";
        $strSQL .= ",         URI.SRY_BUY_PCS" . "\r\n";
        $strSQL .= ",         URI.SRY_SHZ_RT" . "\r\n";
        $strSQL .= ",         URI.SRY_SHZ" . "\r\n";
        $strSQL .= ",         URI.FHZ_TEIKA" . "\r\n";
        $strSQL .= ",         URI.FHZ_NBK" . "\r\n";
        $strSQL .= ",         URI.FHZ_KYK" . "\r\n";
        $strSQL .= ",         URI.FHZ_PCS" . "\r\n";
        $strSQL .= ",         URI.FHZ_SHZ" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_TEIKA" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_NBK" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_KYK" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_PCS" . "\r\n";
        $strSQL .= ",         URI.TKB_KSH_SHZ" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_KYK" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_KJN" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_RT" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_SHZ" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_KYK" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_KJN" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SHZ" . "\r\n";
        $strSQL .= ",         URI.HOUTEIH_GK" . "\r\n";
        $strSQL .= ",         URI.HKN_GK" . "\r\n";
        $strSQL .= ",         URI.TRA_CAR_ZSI_SUM" . "\r\n";
        $strSQL .= ",         URI.SHR_GK_SUM" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_SIT_KIN" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_SIT_SHZ" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_ATM_KIN" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_TRK_SYH" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_CKO_FTK" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_TGT_KAI" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_TGT_KIN" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_KRJ_KAI" . "\r\n";
        $strSQL .= ",         URI.SHR_JKN_KRJ_KIN" . "\r\n";
        $strSQL .= ",         URI.KUREJITGAISYA" . "\r\n";
        $strSQL .= ",         URI.KUREJIT_NO" . "\r\n";
        $strSQL .= ",         URI.TRA_CAR_RCYYTK_SUM" . "\r\n";
        $strSQL .= ",         URI.KAP_GKN" . "\r\n";
        $strSQL .= ",         URI.TRA_CAR_PRC_SUM" . "\r\n";
        $strSQL .= ",         URI.TRA_CAR_STI_SUM" . "\r\n";
        $strSQL .= ",         URI.JIDOUSYA_ZEI" . "\r\n";
        $strSQL .= ",         URI.SYARYOU_ZEI" . "\r\n";
        $strSQL .= ",         URI.EAKON_ZEI" . "\r\n";
        $strSQL .= ",         URI.SUTEREO_ZEI" . "\r\n";
        $strSQL .= ",         URI.JYURYO_ZEI" . "\r\n";
        $strSQL .= ",         URI.SHZ_KEI" . "\r\n";
        $strSQL .= ",         URI.JIBAI_SITEI" . "\r\n";
        $strSQL .= ",         URI.JIBAI_KAISYA" . "\r\n";
        $strSQL .= ",         URI.JIBAI_CAR_KND" . "\r\n";
        $strSQL .= ",         URI.JIBAI_ICOL_CD" . "\r\n";
        $strSQL .= ",         URI.JIBAI_TUKI_SU" . "\r\n";
        $strSQL .= ",         URI.JIBAI_HOK_RYO" . "\r\n";
        $strSQL .= ",         URI.OPTHOK_RYO" . "\r\n";
        $strSQL .= ",         URI.HNB_TES_RYO_KZI_KBN" . "\r\n";
        $strSQL .= ",         URI.HNB_TES_RYO_SHR_CD" . "\r\n";
        $strSQL .= ",         URI.HNB_TES_GKU" . "\r\n";
        $strSQL .= ",         URI.HNB_SHZ" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_KEN" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SYAKEN" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SYAKO_SYO" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_NOUSYA" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SIT_TTK" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_SATEI" . "\r\n";
        $strSQL .= ",         URI.TOU_SYH_JIKOU" . "\r\n";

        $strSQL .= ",         (NVL(URI.TOU_SYH_ETC,0) + NVL(URI.PACK_DE_753,0) + NVL(URI.PACK_DE_MENTE,0)) TOU_SYH_ETC" . "\r\n";
        $strSQL .= ",         URI.HOUTEIH_KEN" . "\r\n";
        $strSQL .= ",         URI.HOUTEIH_SYAKEN" . "\r\n";
        $strSQL .= ",         URI.HOUTEIH_SYAKO_SYO" . "\r\n";
        $strSQL .= ",         URI.HOUTEIH_SIT" . "\r\n";
        $strSQL .= ",         URI.HONBU_FTK" . "\r\n";
        $strSQL .= ",         URI.UKM_SNY_TES" . "\r\n";
        $strSQL .= ",         URI.UKM_SINSEI_SYR" . "\r\n";
        $strSQL .= ",         URI.KAP_TES_SGK" . "\r\n";
        $strSQL .= ",         URI.ETC_SKI_RYO" . "\r\n";
        $strSQL .= ",         URI.SRY_GENKAI_RIE" . "\r\n";
        $strSQL .= ",         URI.PENALTY" . "\r\n";
        $strSQL .= ",         URI.EGO_GAI_SYUEKI" . "\r\n";
        $strSQL .= ",         URI.SAI_SONEKI" . "\r\n";
        $strSQL .= ",         URI.TOK_KEI_KHN_MGN" . "\r\n";
        $strSQL .= ",         URI.TOK_KEI_RUI_MGN" . "\r\n";
        $strSQL .= ",         URI.TOK_KEI_KHN_SYR" . "\r\n";
        $strSQL .= ",         URI.TOK_KEI_TOK_KIN" . "\r\n";
        $strSQL .= ",         URI.GNK_HJN_PCS" . "\r\n";
        $strSQL .= ",         URI.GNK_SIT_URI_SKI" . "\r\n";
        $strSQL .= ",         URI.CKO_BAI_SIT_KIN" . "\r\n";
        $strSQL .= ",         URI.CKO_BAI_SATEI" . "\r\n";
        $strSQL .= ",         URI.CKO_SAI_MITUMORI" . "\r\n";
        $strSQL .= ",         URI.CKO_SYOGAKARI" . "\r\n";
        $strSQL .= ",         URI.CKO_BAI_SATEI_SOKAI" . "\r\n";
        $strSQL .= ",         URI.CKO_MIKEI_JDO_KIN" . "\r\n";
        $strSQL .= ",         URI.CKO_MIKEI_JDO_SHZ" . "\r\n";
        $strSQL .= ",         URI.CKO_MIKEI_JIBAI_KIN" . "\r\n";
        $strSQL .= ",         URI.CKO_MIKEI_JIBAI_SHZ" . "\r\n";
        $strSQL .= ",         URI.NYK_KB" . "\r\n";
        $strSQL .= ",         URI.DM_KB" . "\r\n";
        $strSQL .= ",         URI.KYOUSINKAI_KYK" . "\r\n";
        $strSQL .= ",         URI.KYOUSINKAI_SKI" . "\r\n";
        $strSQL .= ",         URI.KYOUSINKAI_KOKEN" . "\r\n";
        $strSQL .= ",         URI.NEBIKI_RT" . "\r\n";
        $strSQL .= ",         URI.KIJUN_NEBIKI_RT" . "\r\n";
        $strSQL .= ",         URI.KOUSEI_SYOSYO" . "\r\n";
        $strSQL .= ",         URI.JAF" . "\r\n";
        $strSQL .= ",         URI.KICK_BACK" . "\r\n";
        $strSQL .= ",         URI.YOTAK_KB" . "\r\n";
        $strSQL .= ",         URI.RCY_YOT_KIN" . "\r\n";
        $strSQL .= ",         URI.RCY_SKN_KAN_HI" . "\r\n";
        $strSQL .= ",         URI.URI_DAISU" . "\r\n";
        $strSQL .= ",         URI.TOU_DAISU" . "\r\n";
        $strSQL .= ",         URI.KYK_YUBIN_NO" . "\r\n";
        $strSQL .= ",         URI.KYK_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= ",         URI.KYK_KEY_CKU_CD" . "\r\n";
        $strSQL .= ",         URI.KYK_KEY_TEL" . "\r\n";
        $strSQL .= ",         URI.KYK_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= ",         URI.KYK_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= ",         URI.KYK_ADR_MEI" . "\r\n";
        $strSQL .= ",         URI.KYK_MEI_KNJ1" . "\r\n";
        $strSQL .= ",         URI.KYK_MEI_KNJ2" . "\r\n";
        $strSQL .= ",         URI.KYK_ADR_KN" . "\r\n";
        $strSQL .= ",         URI.KYK_MEI_KN" . "\r\n";
        $strSQL .= ",         URI.MGN_YUBIN_NO" . "\r\n";
        $strSQL .= ",         URI.MGN_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= ",         URI.MGN_KEY_CKU_CD" . "\r\n";
        $strSQL .= ",         URI.MGN_KEY_TEL" . "\r\n";
        $strSQL .= ",         URI.MGN_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= ",         URI.MGN_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= ",         URI.MGN_ADR_MEI" . "\r\n";
        $strSQL .= ",         URI.MGN_MEI_KNJ1" . "\r\n";
        $strSQL .= ",         URI.MGN_MEI_KNJ2" . "\r\n";
        $strSQL .= ",         URI.MGN_ADR_KN" . "\r\n";
        $strSQL .= ",         URI.MGN_MEI_KN" . "\r\n";
        $strSQL .= ",         URI.DEL_DATE" . "\r\n";
        $strSQL .= ",         URI.UPD_DATE" . "\r\n";
        $strSQL .= ",         URI.CREATE_DATE" . "\r\n";
        $strSQL .= ",         (CASE WHEN NVL(URI.CEL_DATE,' ') = ' ' THEN '' ELSE '解約' END) KAIYAKU" . "\r\n";
        $strSQL .= ",         (SYO.SKP_NM1 || ' ' || SYO.SKP_NM2) SHIHARAI_MEI" . "\r\n";
        $strSQL .= ",         BUS.BUSYO_NM BUSYO_MEI" . "\r\n";

        $strSQL .= ",         SYA.SYAIN_NM SYAIN_MEI" . "\r\n";

        $strSQL .= ",         (TRI.ATO_DTRPITNM1 || ' ' || TRI.ATO_DTRPITNM2) GYOUSYA_MEI" . "\r\n";

        $strSQL .= ",         K_TEN.HANSH_NM KYK_TEN_MEI" . "\r\n";
        $strSQL .= ",         T_TEN.HANSH_NM TOU_TEN_MEI" . "\r\n";

        $strSQL .= ",         DECODE(URI.NYK_KB,'1','有','無') NYK_NM " . "\r\n";
        //入庫約束名
        $strSQL .= ",         DECODE(URI.DM_KB,'1','要','不要') DM_NM " . "\r\n";
        //DM送付
        $strSQL .= ",         M_CRE.MEISYOU_RN CRE_NM" . "\r\n";
        //ｸﾚｼﾞｯﾄ会社名
        $strSQL .= ",         M_ZKB.MEISYOU_RN ZKB_NM" . "\r\n";
        //課税区分
        $strSQL .= ",         M_SYK.MEISYOU_RN SYK_NM" . "\r\n";
        //所有権
        $strSQL .= ",         M_YOT.MEISYOU_RN YOT_NM" . "\r\n";
        //用途区分
        $strSQL .= ",         M_CLR.MEISYOU_RN CLR_NM" . "\r\n";
        //色
        $strSQL .= ",         M_HNB.MEISYOU_RN HNB_NM" . "\r\n";
        //販売区分
        $strSQL .= ",         M_SIR.MEISYOU_RN SIR_NM" . "\r\n";
        //仕入区分
        $strSQL .= ",         M_MGK.MEISYOU_RN MGK_NM" . "\r\n";
        //名変

        $strSQL .= "FROM      HJYOUHEN URI" . "\r\n";
        $strSQL .= "LEFT JOIN " . "\r\n";
        $strSQL .= "          (SELECT SCO.CMN_NO, SCO.SKP_NM1, SCO.SKP_NM2" . "\r\n";
        $strSQL .= "           FROM   M41E13 SCO" . "\r\n";
        $strSQL .= "           ,      (SELECT CMN_NO, MIN(SKP_SEQ_NO) SEQ_NO" . "\r\n";
        $strSQL .= "                   FROM   M41E13 " . "\r\n";
        $strSQL .= "                   GROUP  BY CMN_NO) M_SCO" . "\r\n";
        $strSQL .= "           WHERE  SCO.CMN_NO = M_SCO.CMN_NO" . "\r\n";
        $strSQL .= "           AND    SCO.SKP_SEQ_NO = M_SCO.SEQ_NO" . "\r\n";
        $strSQL .= "          ) SYO" . "\r\n";
        $strSQL .= "ON        SYO.CMN_NO = URI.CMN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON        BUS.BUSYO_CD = URI.URI_BUSYO_CD" . "\r\n";

        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";

        $strSQL .= "ON        SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "LEFT JOIN M28M68 TRI" . "\r\n";
        $strSQL .= "ON        TRI.ATO_DTRPITCD = URI.URI_GYOSYA " . "\r\n";
        // 契約店・登録店名は販社マスタから抽出()

        $strSQL .= "LEFT JOIN M27M18 K_TEN" . "\r\n";
        $strSQL .= "ON        K_TEN.HANSH_CD = URI.KYK_HNS" . "\r\n";

        $strSQL .= "LEFT JOIN M27M18 T_TEN" . "\r\n";
        $strSQL .= "ON        T_TEN.HANSH_CD = URI.TOU_HNS" . "\r\n";

        //色名称
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '10') M_CLR" . "\r\n";
        $strSQL .= "ON        M_CLR.MEISYOU_CD = URI.JIBAI_ICOL_CD" . "\r\n";
        //ｸﾚｼﾞｯﾄ会社名
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '12') M_CRE" . "\r\n";
        $strSQL .= "ON        M_CRE.MEISYOU_CD = URI.KUREJITGAISYA" . "\r\n";
        //課税区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '11') M_ZKB" . "\r\n";
        $strSQL .= "ON        M_ZKB.MEISYOU_CD = URI.KAZEI_KB" . "\r\n";
        //所有権
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '16') M_SYK" . "\r\n";
        $strSQL .= "ON        M_SYK.MEISYOU_CD = URI.SYOYUKEN_KB" . "\r\n";
        //用途区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '13') M_YOT" . "\r\n";
        $strSQL .= "ON        M_YOT.MEISYOU_CD = URI.YOUTO_KB" . "\r\n";
        //販売区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '17') M_HNB" . "\r\n";
        $strSQL .= "ON        M_HNB.MEISYOU_CD = URI.CKO_HNB_KB" . "\r\n";
        //仕入区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '18') M_SIR" . "\r\n";
        $strSQL .= "ON        M_SIR.MEISYOU_CD = URI.CKO_SIR_KB" . "\r\n";
        //名義人区分
        $strSQL .= "LEFT JOIN (SELECT * FROM HMEISYOUMST WHERE MEISYOU_ID = '19') M_MGK" . "\r\n";
        $strSQL .= "ON        M_MGK.MEISYOU_CD = URI.CKO_MEG_KB" . "\r\n";

        $strSQL .= "WHERE     URI.CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "ORDER BY  JKN_HKO_RIRNO DESC" . "\r\n";

        $strSQL .= ")" . "\r\n";
        $strSQL .= "WHERE     CT = @CTNUM" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strCmnNO, $strSQL);
        $strSQL = str_replace("@CTNUM", $intCnt, $strSQL);

        return $strSQL;
    }

    public function fncSelectJyohen($strCmnNO, $intCnt)
    {
        return parent::select($this->fncSelectJyohenSQL($strCmnNO, $intCnt));
    }

}