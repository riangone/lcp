<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20231213           #仕訳伝票入力		          伝票をコピー作成した、作成者、作成部署修正           	 YIN
 * 20240129           #仕訳伝票入力		     全確定を実行して 枝番が＋１、元データの作成者をセットする     YIN
 * 20240227           20240213_機能改善要望対応 NO6    「科目マスタの使用フラグ、使用フラグ名は撤廃」   YIN
 * 20240322           本番障害.xlsx NO8            科目名、補助科目名を両方表示してほしい              YIN
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
use App\Model\HDKAIKEI\Component\ClsComFncHDKAIKEI;

class HDKShiwakeInput extends ClsComDb
{
    public $ClsComFncHDKAIKEI = null;
    function fncGyoNoSel($strsyohyNO, $strEdaNO)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYOHY_NO" . "\r\n";
        $strSQL .= ",      EDA_NO" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "AND    SYOHY_NO = '@SYOHY_NO'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strsyohyNO, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEdaNO, $strSQL);

        return parent::select($strSQL);
    }

    function fncHDKMSTSHZKBN()
    {
        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "  '' TAX_KBN_CD, " . "\r\n";
        $strSQL .= "  '' TAX_KBN_NAME, ";
        $strSQL .= "  '' NICKNAME" . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "   DUAL " . "\r\n";
        $strSQL .= "UNION ALL " . "\r\n";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "  TAX_KBN_CD, " . "\r\n";
        $strSQL .= "  TAX_KBN_NAME, " . "\r\n";
        $strSQL .= "  CASE " . "\r\n";
        $strSQL .= "    WHEN NICKNAME IS NULL " . "\r\n";
        $strSQL .= "    THEN TAX_KBN_NAME " . "\r\n";
        $strSQL .= "    ELSE NICKNAME " . "\r\n";
        $strSQL .= "  END NICKNAME " . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "  HDK_MST_SHZKBN " . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "  DISP_CD   = 1 " . "\r\n";
        $strSQL .= "AND DEL_FLG = 0 " . "\r\n";

        return parent::select($strSQL);
    }

    function fncSelPattern($BusyoCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT PATTERN_NO" . "\r\n";
        $strSQL .= ",      PATTERN_NM" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "SELECT PATTERN_NO" . "\r\n";
        $strSQL .= ",      PATTERN_NM" . "\r\n";
        $strSQL .= ",      '2' KBNID" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= "WHERE  DENPY_KB = '1'" . "\r\n";
        $strSQL .= "AND    TAISYO_BUSYO_KB = '1'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT PATTERN_NO" . "\r\n";
        $strSQL .= ",      PATTERN_NM" . "\r\n";
        $strSQL .= ",      '2' KBNID" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= "WHERE  DENPY_KB = '1'" . "\r\n";
        $strSQL .= "AND    TAISYO_BUSYO_CD = '@BUSYOCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 0 PATTERN_NO" . "\r\n";
        $strSQL .= ",      '' PATTERN_NM" . "\r\n";
        $strSQL .= ",      '1' KBNID" . "\r\n";
        $strSQL .= "FROM   DUAL" . "\r\n";
        $strSQL .= ") V" . "\r\n";
        $strSQL .= "ORDER BY V.KBNID" . "\r\n";
        $strSQL .= ",        V.PATTERN_NM";

        $strSQL = str_replace("@BUSYOCD", $BusyoCD, $strSQL);

        return parent::select($strSQL);
    }

    //SQL(抽出用)
    function fncMemoSelSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT MEISYOU" . "\r\n";
        $strSQL .= "FROM   HMEISYOUMST" . "\r\n";
        $strSQL .= "WHERE  MEISYOU_ID = '90'" . "\r\n";
        $strSQL .= "ORDER BY MEISYOU_CD";

        return parent::select($strSQL);
    }

    function fncNewSyohyNOSel($strSyohy_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(EDA_NO) EDA_NO" . "\r\n";
        $strSQL .= ",      MAX(DEL_FLG)" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    DEL_FLG = '0'" . "\r\n";
        $strSQL .= "GROUP BY SYOHY_NO";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);

        return parent::select($strSQL);
    }

    function fncSelShiwakeForIchiran($strSyohy_No, $strEda_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT ROW_NUMBER() OVER(ORDER BY SYOHY_NO, EDA_NO, GYO_NO) SEQNO" . "\r\n";
        $strSQL .= ",      SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",      SWK.EDA_NO" . "\r\n";
        $strSQL .= ",      SWK.GYO_NO" . "\r\n";
        // 20240322 YIN UPD S
        // $strSQL .= ",      (CASE WHEN KAR.SUB_KAMOK_NAME IS NULL THEN KAR.KAMOK_NAME ELSE KAR.SUB_KAMOK_NAME END) L_KAMOKU" . "\r\n";
        // $strSQL .= ",      (CASE WHEN KAS.SUB_KAMOK_NAME IS NULL THEN KAS.KAMOK_NAME ELSE KAS.SUB_KAMOK_NAME END) R_KAMOKU" . "\r\n";
        $strSQL .= ",      KAR.KAMOK_NAME L_KAMOKU" . "\r\n";
        $strSQL .= ",      KAR.SUB_KAMOK_NAME L_KOUMKU" . "\r\n";
        $strSQL .= ",      KAS.KAMOK_NAME R_KAMOKU" . "\r\n";
        $strSQL .= ",      KAS.SUB_KAMOK_NAME R_KOUMKU" . "\r\n";
        // 20240322 YIN UPD E
        $strSQL .= ",      SWK.ZEIKM_GK	 ZEIKM_GK" . "\r\n";
        $strSQL .= ",      SWK.SHZEI_GK	 SHZEI_GK" . "\r\n";
        $strSQL .= ",      SWK.TEKYO	     TEKYO" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK	" . "\r\n";
        $strSQL .= "LEFT JOIN	" . "\r\n";
        $strSQL .= "       HDK_MST_KAMOKU KAR	" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') AND KAR.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN	" . "\r\n";
        $strSQL .= "       HDK_MST_KAMOKU KAS	" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUB_KAMOK_CD),'999999'),KAS.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999') AND KAS.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON     KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUB_KAMOK_CD),'999999'),KAS.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "WHERE  SWK.SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    SWK.EDA_NO   = '@EDA_NO'" . "\r\n";
        $strSQL .= "AND    SWK.DEL_FLG = '0'" . "\r\n";
        $strSQL .= "ORDER BY SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",        SWK.EDA_NO" . "\r\n";
        $strSQL .= ",        SWK.GYO_NO";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEda_No, $strSQL);

        return parent::select($strSQL);
    }

    function fncFlgCheckSQL($strSyohy_NO, $strEDA_NO)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(TO_CHAR(UPD_DATE,'YYYY/MM/DD HH24:MI:SS'))      UPD_DATE" . "\r\n";
        $strSQL .= ",      MAX(PRINT_OUT_FLG) PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",      MAX(CSV_OUT_FLG)   CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",      MAX(XLSX_OUT_FLG)   XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",      MAX(DEL_FLG)       DEL_FLG" . "\r\n";
        $strSQL .= ",      MAX(CRE_BUSYO_CD)  CRE_BUSYO_CD" . "\r\n";
        $strSQL .= ",      MAX(CRE_SYA_CD) CRE_SYA_CD" . "\r\n";
        $strSQL .= ",      MAX(CRE_CLT_NM) CRE_CLT_NM" . "\r\n";
        $strSQL .= ",      MAX(CRE_PRG_ID) CRE_PRG_ID" . "\r\n";
        $strSQL .= ",      MAX(UPD_BUSYO_CD)  UPD_BUSYO_CD" . "\r\n";
        $strSQL .= ",      MAX(HONBU_SYORIZUMI_FLG) HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "GROUP BY SYOHY_NO";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_NO, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEDA_NO, $strSQL);

        return parent::select($strSQL);
    }

    function fncDispModeSansyoChk($strSyohy_NO)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(TO_CHAR(UPD_DATE,'YYYY/MM/DD HH24:MI:SS'))      UPD_DATE" . "\r\n";
        $strSQL .= ",      MAX(PRINT_OUT_FLG) PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",      MAX(CSV_OUT_FLG)   CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",      MAX(XLSX_OUT_FLG)   XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",      MAX(HONBU_SYORIZUMI_FLG) HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "GROUP BY SYOHY_NO";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_NO, $strSQL);

        return parent::select($strSQL);
    }

    function fncSyuuseiMaeSyohyoSel($strSyohy_No, $strEda_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(SYOHY_NO) SYOHY_NO" . "\r\n";
        $strSQL .= ",      MAX(EDA_NO) EDA_NO" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    EDA_NO   < '@EDA_NO'";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEda_No, $strSQL);

        return parent::select($strSQL);
    }

    function fncSelPatternData($strPattern_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.DENPY_KB" . "\r\n";
        $strSQL .= ",      SWK.PATTERN_NO" . "\r\n";
        $strSQL .= ",      SWK.PATTERN_NM" . "\r\n";
        $strSQL .= ",      SWK.TAISYO_BUSYO_KB" . "\r\n";
        $strSQL .= ",      SWK.TAISYO_BUSYO_CD" . "\r\n";
        $strSQL .= ",      SWK.KEIRI_DT" . "\r\n";
        $strSQL .= ",      SWK.TORIHIKI_DT" . "\r\n";
        $strSQL .= ",      SWK.SHIHARAI_DT" . "\r\n";
        $strSQL .= ",      SWK.TEKYO" . "\r\n";
        $strSQL .= ",      SWK.L_KAMOK_CD" . "\r\n";
        $strSQL .= ",      SWK.L_KOUMK_CD" . "\r\n";
        $strSQL .= ",      KAR.KAMOK_NAME L_KAMOK_NM" . "\r\n";
        $strSQL .= ",      KAR.SUB_KAMOK_NAME L_KOUMK_NM" . "\r\n";
        $strSQL .= ",      SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      LB.BUSYO_NM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",      SWK.L_KAZEI_KB" . "\r\n";
        $strSQL .= ",      SWK.L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      SWK.R_KAMOK_CD" . "\r\n";
        $strSQL .= ",      SWK.R_KOUMK_CD" . "\r\n";
        $strSQL .= ",      KAS.KAMOK_NAME R_KAMOK_NM" . "\r\n";
        $strSQL .= ",      KAS.SUB_KAMOK_NAME R_KOUMK_NM" . "\r\n";
        $strSQL .= ",      SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      RB.BUSYO_NM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",      SWK.R_KAZEI_KB" . "\r\n";
        $strSQL .= ",      SWK.R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      SWK.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",      HMT.TORIHIKISAKI_NAME TORIHIKISAKI_NAME" . "\r\n";
        // $strSQL .= ",      SWK.SHIHARAISAKI_CD" . "\r\n";
        // $strSQL .= ",      SWK.SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",      SWK.GINKO_KB" . "\r\n";
        $strSQL .= ",      SWK.GINKO_NM" . "\r\n";
        $strSQL .= ",      SWK.SHITEN_NM" . "\r\n";
        $strSQL .= ",      SWK.YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",      SWK.KOUZA_NO" . "\r\n";
        $strSQL .= ",      SWK.KOUZA_KN" . "\r\n";
        $strSQL .= ",      SWK.JIKI" . "\r\n";
        $strSQL .= ",      SWK.CREATE_DATE" . "\r\n";
        $strSQL .= ",      SWK.CRE_SYA_CD" . "\r\n";
        $strSQL .= ",      SWK.CRE_PRG_ID" . "\r\n";
        $strSQL .= ",      SWK.CRE_CLT_NM" . "\r\n";
        $strSQL .= ",      SWK.UPD_DATE" . "\r\n";
        $strSQL .= ",      SWK.UPD_SYA_CD" . "\r\n";
        $strSQL .= ",      SWK.UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      SWK.UPD_CLT_NM" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA SWK" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAR" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') AND KAR.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAS" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUB_KAMOK_CD),'999999'),KAS.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999') AND KAS.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON     KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUB_KAMOK_CD),'999999'),KAS.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HDK_MST_BUMON LB" . "\r\n";
        $strSQL .= "ON     LB.BUSYO_CD = SWK.L_HASEI_KYOTN_CD AND LB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON RB" . "\r\n";
        $strSQL .= "ON     RB.BUSYO_CD = SWK.R_HASEI_KYOTN_CD AND RB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_TORIHIKISAKI HMT" . "\r\n";
        $strSQL .= "ON     HMT.TORIHIKISAKI_CD = SWK.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= "WHERE  SWK.PATTERN_NO = '@PATTERN_NO'" . "\r\n";
        $strSQL .= "AND    SWK.DENPY_KB = '@DENPY_KB'";

        $strSQL = str_replace("@PATTERN_NO", $strPattern_No, $strSQL);
        $strSQL = str_replace("@DENPY_KB", "1", $strSQL);

        return parent::select($strSQL);
    }

    function fncSelShiwakeData($strSyohy_NO, $strEDa_NO, $strGyo_NO)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",      SWK.EDA_NO" . "\r\n";
        $strSQL .= ",      SWK.GYO_NO" . "\r\n";
        $strSQL .= ",      SWK.DENPY_KB" . "\r\n";
        $strSQL .= ",      SWK.KEIJO_KB" . "\r\n";
        $strSQL .= ",      DECODE(SWK.KEIRI_DT,NULL,'',SUBSTR(SWK.KEIRI_DT,1,4) || '/' || SUBSTR(SWK.KEIRI_DT,5,2) || '/' || SUBSTR(SWK.KEIRI_DT,7,2)) KEIRI_DT" . "\r\n";
        $strSQL .= ",      SWK.TORIHIKI_DT" . "\r\n";
        $strSQL .= ",      SWK.SHIHARAI_DT" . "\r\n";
        $strSQL .= ",      SWK.ZEIKM_GK" . "\r\n";
        $strSQL .= ",      SWK.ZEINK_GK" . "\r\n";
        $strSQL .= ",      SWK.SHZEI_GK" . "\r\n";
        $strSQL .= ",      SWK.TEKYO" . "\r\n";
        $strSQL .= ",      SWK.L_KAMOK_CD" . "\r\n";
        $strSQL .= ",      SWK.L_KOUMK_CD" . "\r\n";
        $strSQL .= ",      KAR.KAMOK_NAME L_KAMOK_NM" . "\r\n";
        $strSQL .= ",      KAR.SUB_KAMOK_NAME L_KOUMK_NM" . "\r\n";
        $strSQL .= ",      SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      LB.BUSYO_NM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",      SWK.L_KAZEI_KB" . "\r\n";
        $strSQL .= ",      SWK.L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      SWK.L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      SWK.R_KAMOK_CD" . "\r\n";
        $strSQL .= ",      SWK.R_KOUMK_CD" . "\r\n";
        $strSQL .= ",      KAS.KAMOK_NAME R_KAMOK_NM" . "\r\n";
        $strSQL .= ",      KAS.SUB_KAMOK_NAME R_KOUMK_NM" . "\r\n";
        $strSQL .= ",      SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      RB.BUSYO_NM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",      SWK.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",      HMT.TORIHIKISAKI_NAME TORIHIKISAKI_NAME" . "\r\n";
        $strSQL .= ",      SWK.R_KAZEI_KB" . "\r\n";
        $strSQL .= ",      SWK.R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      SWK.R_ZEI_RT_KB" . "\r\n";
        // $strSQL .= ",      SWK.SHIHARAISAKI_CD" . "\r\n";
        // $strSQL .= ",      SWK.SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",      SWK.GINKO_KB" . "\r\n";
        $strSQL .= ",      SWK.GINKO_NM" . "\r\n";
        $strSQL .= ",      SWK.SHITEN_NM" . "\r\n";
        $strSQL .= ",      SWK.YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",      SWK.KOUZA_NO" . "\r\n";
        $strSQL .= ",      SWK.KOUZA_KN" . "\r\n";
        $strSQL .= ",      SWK.JIKI" . "\r\n";
        $strSQL .= ",      SWK.FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",      SWK.PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",      SWK.CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",      SWK.XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",      SWK.CSV_GROUP_NO" . "\r\n";
        $strSQL .= ",      SWK.XLSX_GROUP_NO" . "\r\n";
        $strSQL .= ",      SWK.DEL_FLG" . "\r\n";
        $strSQL .= ",      SWK.DEL_DATE" . "\r\n";
        $strSQL .= ",      SWK.CREATE_DATE" . "\r\n";
        $strSQL .= ",      SWK.CRE_SYA_CD" . "\r\n";
        $strSQL .= ",      SWK.CRE_PRG_ID" . "\r\n";
        $strSQL .= ",      SWK.CRE_CLT_NM" . "\r\n";
        $strSQL .= ",      SWK.UPD_DATE" . "\r\n";
        $strSQL .= ",      SWK.UPD_SYA_CD" . "\r\n";
        $strSQL .= ",      SWK.UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      SWK.UPD_CLT_NM" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAR" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') AND KAR.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAS" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUB_KAMOK_CD),'999999'),KAS.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999') AND KAS.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON     KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUB_KAMOK_CD),'999999'),KAS.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HDK_MST_BUMON LB" . "\r\n";
        $strSQL .= "ON     LB.BUSYO_CD = SWK.L_HASEI_KYOTN_CD AND LB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON RB" . "\r\n";
        $strSQL .= "ON     RB.BUSYO_CD = SWK.R_HASEI_KYOTN_CD AND RB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_TORIHIKISAKI HMT" . "\r\n";
        $strSQL .= "ON     HMT.TORIHIKISAKI_CD = SWK.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= "WHERE  SWK.SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    SWK.EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "AND    SWK.GYO_NO = '@GYO_NO'" . "\r\n";
        $strSQL .= "AND    SWK.DEL_FLG = '0'";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_NO, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEDa_NO, $strSQL);
        $strSQL = str_replace("@GYO_NO", $strGyo_NO, $strSQL);

        return parent::select($strSQL);
    }

    //SQL(追加処理)
    function fncShiwakeDataIns($strSyohy_No, $strEdaNO, $strGyo_NO, $strSysdate, $strCreBusyoCD, $strCreSyaCD, $strCrePrgID, $strCreCltNm, $PatternID, $postData, $BusyoCD)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HDPSHIWAKEDATA(" . "\r\n";
        $strSQL .= "      SYOHY_NO" . "\r\n";
        $strSQL .= ",     EDA_NO" . "\r\n";
        $strSQL .= ",     GYO_NO" . "\r\n";
        $strSQL .= ",     DENPY_KB" . "\r\n";
        $strSQL .= ",     KEIJO_KB" . "\r\n";
        $strSQL .= ",     KEIRI_DT" . "\r\n";
        $strSQL .= ",     TORIHIKI_DT" . "\r\n";
        $strSQL .= ",     SHIHARAI_DT" . "\r\n";
        $strSQL .= ",     ZEIKM_GK" . "\r\n";
        $strSQL .= ",     ZEINK_GK" . "\r\n";
        $strSQL .= ",     SHZEI_GK" . "\r\n";
        $strSQL .= ",     TEKYO" . "\r\n";
        $strSQL .= ",     L_KAMOK_CD" . "\r\n";
        $strSQL .= ",     L_KOUMK_CD" . "\r\n";
        $strSQL .= ",     L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     L_KAZEI_KB" . "\r\n";
        $strSQL .= ",     L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     R_KAMOK_CD" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_NAME " . "\r\n";
        $strSQL .= ",     R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB" . "\r\n";
        // $strSQL .= ",     SHIHARAISAKI_CD" . "\r\n";
        // $strSQL .= ",     SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",     GINKO_KB" . "\r\n";
        $strSQL .= ",     GINKO_NM" . "\r\n";
        $strSQL .= ",     SHITEN_NM" . "\r\n";
        $strSQL .= ",     YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",     KOUZA_NO" . "\r\n";
        $strSQL .= ",     KOUZA_KN" . "\r\n";
        $strSQL .= ",     JIKI" . "\r\n";
        $strSQL .= ",     FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",     HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= ",     PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",     CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",     XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",     CSV_GROUP_NO" . "\r\n";
        $strSQL .= ",     XLSX_GROUP_NO" . "\r\n";
        $strSQL .= ",     DEL_FLG" . "\r\n";
        $strSQL .= ",     DEL_DATE" . "\r\n";
        $strSQL .= ",     CREATE_DATE" . "\r\n";
        $strSQL .= ",     CRE_BUSYO_CD" . "\r\n";
        $strSQL .= ",     CRE_SYA_CD" . "\r\n";
        $strSQL .= ",     CRE_PRG_ID" . "\r\n";
        $strSQL .= ",     CRE_CLT_NM" . "\r\n";
        $strSQL .= ",     UPD_DATE" . "\r\n";
        $strSQL .= ",     UPD_BUSYO_CD" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD" . "\r\n";
        $strSQL .= ",     UPD_PRG_ID" . "\r\n";
        $strSQL .= ",     UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES(" . "\r\n";
        $strSQL .= "      '@SYOHY_NO'" . "\r\n";
        $strSQL .= ",     '@EDA_NO'" . "\r\n";
        $strSQL .= ",     @GYO_NO" . "\r\n";
        $strSQL .= ",     '1'" . "\r\n";
        $strSQL .= ",     '1'" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     @ZEIKM_GK" . "\r\n";
        $strSQL .= ",     @ZEINK_GK" . "\r\n";
        $strSQL .= ",     @SHZEI_GK" . "\r\n";
        $strSQL .= ",     '@TEKYO'" . "\r\n";
        $strSQL .= ",     '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     '@L_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     '@L_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     @L_KAZEI_KB" . "\r\n";
        $strSQL .= ",     @L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     '@TORIHIKISAKI_CD'" . "\r\n";
        $strSQL .= ",     '@TORIHIKISAKI_NAME '" . "\r\n";
        $strSQL .= ",     @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     @R_ZEI_RT_KB" . "\r\n";
        // $strSQL .= ",     NULL" . "\r\n";
        // $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     '@FUKANZEN_FLG'" . "\r\n";
        $strSQL .= ",     '@HONBUFLG'" . "\r\n";
        $strSQL .= ",     0" . "\r\n";
        $strSQL .= ",     0" . "\r\n";
        $strSQL .= ",     0" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     0" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        $strSQL .= ",     TO_DATE('@CREATE_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     '@CRE_BUSYO_CD'" . "\r\n";
        $strSQL .= ",     '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= ",     '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= ",     '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= ",     TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",     '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",     '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",     '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@GYO_NO", $strGyo_NO, $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEdaNO, $strSQL);
        if ($PatternID == $postData["CONST_ADMIN_PTN_NO"] || $PatternID == $postData["CONST_HONBU_PTN_NO"]) {
            $strSQL = str_replace("@HONBUFLG", "1", $strSQL);
        } else {
            $strSQL = str_replace("@HONBUFLG", "0", $strSQL);
        }
        if ($strCreSyaCD == "") {
            $strCreSyaCD = $this->GS_LOGINUSER['strUserID'];
        }
        if ($strCreCltNm == "") {
            $strCreCltNm = $this->GS_LOGINUSER['strClientNM'];
        }
        // 20231213 YIN UPD S
        // $strSQL = str_replace("@CRE_BUSYO_CD", $strCreBusyoCD, $strSQL);
        // $strSQL = str_replace("@CRE_SYA_CD", $strCreSyaCD, $strSQL);
        // $strSQL = str_replace("@CRE_CLT_NM", $strCreCltNm, $strSQL);
        // 20240129 YIN UPD S
        // $strSQL = str_replace("@CRE_BUSYO_CD", SessionComponent::read('BusyoCD'), $strSQL);
        // $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        // $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CRE_BUSYO_CD", $strCreBusyoCD, $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $strCreSyaCD, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $strCreCltNm, $strSQL);
        // 20240129 YIN UPD E
        // 20231213 YIN UPD E
        $strSQL = str_replace("@CRE_PRG_ID", $strCrePrgID, $strSQL);

        $strSQL = $this->subWhereSet($strSQL, $postData, $BusyoCD);

        $strSQL = str_replace("@CREATE_DATE", $strSysdate, $strSQL);
        $strSQL = str_replace("@UPD_DATE", $strSysdate, $strSQL);

        return parent::insert($strSQL);
    }

    function fncMaeShiwakeCopy($strSyohy_no, $intNewEdaNo, $strOldEdaNO, $strDate, $postData, $BusyoCD, $PatternID)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HDPSHIWAKEDATA(" . "\r\n";
        $strSQL .= "      SYOHY_NO" . "\r\n";
        $strSQL .= ",     EDA_NO" . "\r\n";
        $strSQL .= ",     GYO_NO" . "\r\n";
        $strSQL .= ",     DENPY_KB" . "\r\n";
        $strSQL .= ",     KEIJO_KB" . "\r\n";
        $strSQL .= ",     KEIRI_DT" . "\r\n";
        $strSQL .= ",     TORIHIKI_DT" . "\r\n";
        $strSQL .= ",     SHIHARAI_DT" . "\r\n";
        $strSQL .= ",     ZEIKM_GK" . "\r\n";
        $strSQL .= ",     ZEINK_GK" . "\r\n";
        $strSQL .= ",     SHZEI_GK" . "\r\n";
        $strSQL .= ",     TEKYO" . "\r\n";
        $strSQL .= ",     L_KAMOK_CD" . "\r\n";
        $strSQL .= ",     L_KOUMK_CD" . "\r\n";
        $strSQL .= ",     L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     L_KAZEI_KB" . "\r\n";
        $strSQL .= ",     L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     R_KAMOK_CD" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_NAME " . "\r\n";
        $strSQL .= ",     R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB" . "\r\n";
        // $strSQL .= ",     SHIHARAISAKI_CD" . "\r\n";
        // $strSQL .= ",     SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",     GINKO_KB" . "\r\n";
        $strSQL .= ",     GINKO_NM" . "\r\n";
        $strSQL .= ",     SHITEN_NM" . "\r\n";
        $strSQL .= ",     YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",     KOUZA_NO" . "\r\n";
        $strSQL .= ",     KOUZA_KN" . "\r\n";
        $strSQL .= ",     JIKI" . "\r\n";
        $strSQL .= ",     FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",     HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= ",     PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",     CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",     XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",     CSV_GROUP_NO" . "\r\n";
        $strSQL .= ",     XLSX_GROUP_NO" . "\r\n";
        $strSQL .= ",     DEL_FLG" . "\r\n";
        $strSQL .= ",     DEL_DATE" . "\r\n";
        $strSQL .= ",     CREATE_DATE" . "\r\n";
        $strSQL .= ",     CRE_BUSYO_CD" . "\r\n";
        $strSQL .= ",     CRE_SYA_CD" . "\r\n";
        $strSQL .= ",     CRE_PRG_ID" . "\r\n";
        $strSQL .= ",     CRE_CLT_NM" . "\r\n";
        $strSQL .= ",     UPD_DATE" . "\r\n";
        $strSQL .= ",     UPD_BUSYO_CD" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD" . "\r\n";
        $strSQL .= ",     UPD_PRG_ID" . "\r\n";
        $strSQL .= ",     UPD_CLT_NM" . "\r\n";
        $strSQL .= ",     CSV_OUT_ORDER" . "\r\n";
        $strSQL .= ",     XLSX_OUT_ORDER" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT SYOHY_NO" . "\r\n";
        $strSQL .= ",      '@NEW_EDA_NO'" . "\r\n";
        $strSQL .= ",      GYO_NO" . "\r\n";
        $strSQL .= ",      '1'" . "\r\n";
        $strSQL .= ",      '1'" . "\r\n";
        $strSQL .= ",      KEIRI_DT" . "\r\n";
        $strSQL .= ",      TORIHIKI_DT" . "\r\n";
        $strSQL .= ",      SHIHARAI_DT" . "\r\n";
        $strSQL .= ",      ZEIKM_GK" . "\r\n";
        $strSQL .= ",      ZEINK_GK" . "\r\n";
        $strSQL .= ",      SHZEI_GK" . "\r\n";
        $strSQL .= ",      TEKYO" . "\r\n";
        $strSQL .= ",      L_KAMOK_CD" . "\r\n";
        $strSQL .= ",      L_KOUMK_CD" . "\r\n";
        $strSQL .= ",      L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      L_KAZEI_KB" . "\r\n";
        $strSQL .= ",      L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      R_KAMOK_CD" . "\r\n";
        $strSQL .= ",      R_KOUMK_CD" . "\r\n";
        $strSQL .= ",      R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_NAME " . "\r\n";
        $strSQL .= ",      R_KAZEI_KB" . "\r\n";
        $strSQL .= ",      R_ZEI_RT_KB" . "\r\n";
        // $strSQL .= ",      SHIHARAISAKI_CD" . "\r\n";
        // $strSQL .= ",      SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",      GINKO_KB" . "\r\n";
        $strSQL .= ",      GINKO_NM" . "\r\n";
        $strSQL .= ",      SHITEN_NM" . "\r\n";
        $strSQL .= ",      YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",      KOUZA_NO" . "\r\n";
        $strSQL .= ",      KOUZA_KN" . "\r\n";
        $strSQL .= ",      JIKI" . "\r\n";
        $strSQL .= ",      FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",      '@HONBU_SYORIZUMI_FLG'" . "\r\n";
        $strSQL .= ",      '0'" . "\r\n";
        $strSQL .= ",      '0'" . "\r\n";
        $strSQL .= ",      '0'" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      '0'" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        // 20231213 YIN UPD S
        // $strSQL .= ",      CREATE_DATE" . "\r\n";
        // $strSQL .= ",      CRE_BUSYO_CD" . "\r\n";
        // $strSQL .= ",      CRE_SYA_CD" . "\r\n";
        // $strSQL .= ",      CRE_PRG_ID" . "\r\n";
        // $strSQL .= ",      CRE_CLT_NM" . "\r\n";
        // 20240129 YIN UPD S
        // $strSQL .= ",      TO_DATE('@CREATE_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        // $strSQL .= ",      '@CRE_BUSYO_CD'" . "\r\n";
        // $strSQL .= ",      '@CRE_SYA_CD'" . "\r\n";
        // $strSQL .= ",      'ShiwakeInput'" . "\r\n";
        // $strSQL .= ",      '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      CRE_BUSYO_CD" . "\r\n";
        $strSQL .= ",      CRE_SYA_CD" . "\r\n";
        $strSQL .= ",      CRE_PRG_ID" . "\r\n";
        $strSQL .= ",      CRE_CLT_NM" . "\r\n";
        // 20240129 YIN UPD E
        // 20231213 YIN UPD E
        $strSQL .= ",      TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",      '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",      '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",      'ShiwakeInput'" . "\r\n";
        $strSQL .= ",      '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ",     CSV_OUT_ORDER" . "\r\n";
        $strSQL .= ",     XLSX_OUT_ORDER" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@EDA_NO'";

        $strSQL = str_replace("@NEW_EDA_NO", $intNewEdaNo, $strSQL);
        // 20231213 YIN INS S
        // 20240129 YIN UPD S
        // $strSQL = str_replace("@CREATE_DATE", $strDate, $strSQL);
        // $strSQL = str_replace("@CRE_BUSYO_CD", SessionComponent::read('BusyoCD'), $strSQL);
        // $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        // $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        // 20240129 YIN UPD E
        // 20231213 YIN INS E
        $strSQL = str_replace("@UPD_DATE", $strDate, $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $BusyoCD, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@EDA_NO", $strOldEdaNO, $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $strSyohy_no, $strSQL);
        if ($PatternID == $postData['CONST_ADMIN_PTN_NO'] || $PatternID == $postData['CONST_HONBU_PTN_NO']) {
            $strSQL = str_replace("@HONBU_SYORIZUMI_FLG", "1", $strSQL);
        } else {
            $strSQL = str_replace("@HONBU_SYORIZUMI_FLG", "0", $strSQL);
        }

        return parent::insert($strSQL);
    }

    //SQL(修正処理用)
    function fncUpdateSQL($strSyohy_No, $strEdaNO, $strGyo_NO, $strSysdate, $PatternID, $postData, $BusyoCD)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "SET     KEIRI_DT = '@KEIRI_DT'" . "\r\n";
        $strSQL .= ",		ZEIKM_GK = '@ZEIKM_GK'" . "\r\n";
        $strSQL .= ",		ZEINK_GK = '@ZEINK_GK'" . "\r\n";
        $strSQL .= ",		SHZEI_GK = '@SHZEI_GK'" . "\r\n";
        $strSQL .= ",		TEKYO = '@TEKYO'" . "\r\n";
        $strSQL .= ",		L_KAMOK_CD = '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",		L_KOUMK_CD = '@L_KOUMK_CD'" . "\r\n";
        $strSQL .= ",		L_HASEI_KYOTN_CD = '@L_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",		L_KAZEI_KB = @L_KAZEI_KB" . "\r\n";
        $strSQL .= ",		L_ZEI_RT_KB = @L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",		R_KAMOK_CD = '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",		R_KOUMK_CD = '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",		R_HASEI_KYOTN_CD = '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",		TORIHIKISAKI_CD = '@TORIHIKISAKI_CD'" . "\r\n";
        $strSQL .= ",		TORIHIKISAKI_NAME = '@TORIHIKISAKI_NAME'" . "\r\n";
        $strSQL .= ",		R_KAZEI_KB = @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",		R_ZEI_RT_KB = @R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",		FUKANZEN_FLG = '@FUKANZEN_FLG'" . "\r\n";
        if ($PatternID == $postData['CONST_ADMIN_PTN_NO'] || $PatternID == $postData['CONST_HONBU_PTN_NO']) {
            $strSQL .= ",        HONBU_SYORIZUMI_FLG = '1'" . "\r\n";
        }
        $strSQL .= ",		PRINT_OUT_FLG = '0'" . "\r\n";
        $strSQL .= ",		CSV_OUT_FLG = '0'" . "\r\n";
        $strSQL .= ",		XLSX_OUT_FLG = '0'" . "\r\n";
        $strSQL .= ",		CSV_GROUP_NO = NULL" . "\r\n";
        $strSQL .= ",		XLSX_GROUP_NO = NULL" . "\r\n";
        $strSQL .= ",		DEL_FLG = '0'" . "\r\n";
        $strSQL .= ",		DEL_DATE = NULL" . "\r\n";
        $strSQL .= ",		UPD_DATE = TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",		UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",		UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",		UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",		UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE    SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND      EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "AND      GYO_NO = '@GYO_NO'" . "\r\n";

        $strSQL = str_replace("@GYO_NO", $strGyo_NO, $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEdaNO, $strSQL);
        $strSQL = str_replace("@KEIRI_DT", $postData["txtKeiriSyoriDT"], $strSQL);

        $strSQL = $this->subWhereSet($strSQL, $postData, $BusyoCD);

        $strSQL = str_replace("@CREATE_DATE", $strSysdate, $strSQL);
        $strSQL = str_replace("@UPD_DATE", $strSysdate, $strSQL);

        return parent::update($strSQL);
    }

    function fncGyoDelete($strSyohy_No, $strEdaNO, $strGyo_NO, $strSysdate)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "AND    GYO_NO = '@GYO_NO'";

        $strSQL = str_replace("@DEL_DATE", $strSysdate, $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEdaNO, $strSQL);
        $strSQL = str_replace("@GYO_NO", $strGyo_NO, $strSQL);

        return parent::delete($strSQL);
    }

    function fncLastDelAllUpd($strSyohy_No, $strEdaNO, $strSysdate, $BusyoCD, $PatternID, $postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "SET    DEL_FLG = '1'" . "\r\n";
        $strSQL .= ",      DEL_DATE = TO_CHAR(TO_DATE('@DEL_DATE','YYYY/MM/DD HH24:MI:SS'),'YYYYMMDD')" . "\r\n";
        if ($PatternID == $postData['CONST_ADMIN_PTN_NO'] || $PatternID == $postData['CONST_HONBU_PTN_NO']) {
            $strSQL .= ",      HONBU_SYORIZUMI_FLG = '1'" . "\r\n";
        }
        $strSQL .= ",		UPD_DATE = TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",		UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",		UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",		UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",		UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ",		DEL_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",		DEL_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",		DEL_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  NOT EXISTS" . "\r\n";
        $strSQL .= "       (SELECT SYOHY_NO " . "\r\n";
        $strSQL .= "        FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "        WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "        AND    EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "        AND    DEL_FLG = '0')" . "\r\n";
        $strSQL .= "AND    SYOHY_NO = '@SYOHY_NO'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEdaNO, $strSQL);
        $strSQL = str_replace("@DEL_DATE", $strSysdate, $strSQL);

        $strSQL = str_replace("@UPD_DATE", $strSysdate, $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $BusyoCD, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "ShiwakeInput", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }
    function fncHDKATTACHMENT($strSyohy_No, $strEdaNO, $strSysdate, $strGyoNo = null)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDK_ATTACHMENT" . "\r\n";
        $strSQL .= "SET    DEL_FLG = '1'" . "\r\n";
        $strSQL .= ",      DEL_DATE = TO_DATE('@SYSDT','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",      DEL_SYA_CD = '@DEL_SYA_CD'" . "\r\n";
        $strSQL .= ",      DEL_PRG_ID = '@DEL_PRG_ID'" . "\r\n";
        $strSQL .= ",      DEL_CLT_NM = '@DEL_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        if ($strEdaNO != '') {
            $strSQL .= "AND    EDA_NO = '@EDA_NO'" . "\r\n";
        }
        if ($strGyoNo != null) {
            $strSQL .= "    AND    GYO_NO = '@GYO_NO'" . "\r\n";
        }
        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEdaNO == null ? '' : $strEdaNO, $strSQL);
        $strSQL = str_replace("@GYO_NO", $strGyoNo == null ? '' : $strGyoNo, $strSQL);
        $strSQL = str_replace("@SYSDT", $strSysdate, $strSQL);
        $strSQL = str_replace("@DEL_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@DEL_PRG_ID", "ShiwakeInput", $strSQL);
        $strSQL = str_replace("@DEL_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }
    //SQL(全削除処理用)
    function fncAllDeleteUpd($strSyohy_No, $strSysdate, $BusyoCD, $PatternID, $postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "SET    DEL_FLG = '1'" . "\r\n";
        $strSQL .= ",      DEL_DATE = TO_CHAR(TO_DATE('@DEL_DATE','YYYY/MM/DD HH24:MI:SS'),'YYYYMMDD')" . "\r\n";
        if ($PatternID == $postData['CONST_ADMIN_PTN_NO'] || $PatternID == $postData['CONST_HONBU_PTN_NO']) {
            $strSQL .= ",      HONBU_SYORIZUMI_FLG = '1'" . "\r\n";
        }
        $strSQL .= ",       UPD_DATE = TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",       UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",       UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ",       DEL_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",       DEL_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",       DEL_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@DEL_DATE", $strSysdate, $strSQL);

        $strSQL = str_replace("@UPD_DATE", $strSysdate, $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $BusyoCD, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "ShiwakeInput", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    function fncAllDelete($strSyohy_No, $strEda_No)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@EDA_NO'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEda_No, $strSQL);

        return parent::delete($strSQL);
    }
    //'**********************************************************************
    //'処 理 名：ワーク証憑№に登録する
    //'関 数 名：fncInsTaisyoSyohyNOPrint
    //'引 数 １：(I)strSyohy_NO :証憑№
    //'戻 り 値：ＳＱＬ
    //'処理説明：ワーク証憑№に対象欄にチェックが入っている伝票を登録する
    //'**********************************************************************
    function fncInsTaisyoSyohyNOPrint($strSyohy_NO)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO WK_SYOHY_NO" . "\r\n";
        $strSQL .= "(      SYOHY_NO" . "\r\n";
        $strSQL .= ",      EDA_NO" . "\r\n";
        $strSQL .= ",      GYO_NO" . "\r\n";
        $strSQL .= ",      KENSU" . "\r\n";
        $strSQL .= ",      KINGAKU" . "\r\n";
        $strSQL .= ",      FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",      PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",      CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",      XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      CRE_SYA_CD" . "\r\n";
        $strSQL .= ",      CRE_PRG_ID" . "\r\n";
        $strSQL .= ",      CRE_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES (" . "\r\n";
        $strSQL .= "       '@SYOHY_NO'" . "\r\n";
        $strSQL .= ",      '@EDA_NO'" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= ",      'DENPYO_SEARCH_PRINT'" . "\r\n";
        $strSQL .= ",      '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= ")";

        $strSQL = str_replace("@SYOHY_NO", substr($strSyohy_NO, 0, 15), $strSQL);
        $strSQL = str_replace("@EDA_NO", substr($strSyohy_NO, 15, 2), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

    //SQL(パターン登録用)
    function fncPatternTrkDispShiwake($postData, $BusyoCD)
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = "";
        $strSQL .= "INSERT INTO HDPSHIWAKEPATTERNDATA(" . "\r\n";
        $strSQL .= "      DENPY_KB" . "\r\n";
        $strSQL .= ",     PATTERN_NO" . "\r\n";
        $strSQL .= ",     PATTERN_NM" . "\r\n";
        $strSQL .= ",     TAISYO_BUSYO_KB" . "\r\n";
        $strSQL .= ",     TAISYO_BUSYO_CD" . "\r\n";
        $strSQL .= ",     KEIRI_DT" . "\r\n";
        $strSQL .= ",     TORIHIKI_DT" . "\r\n";
        $strSQL .= ",     SHIHARAI_DT" . "\r\n";
        $strSQL .= ",     TEKYO" . "\r\n";
        $strSQL .= ",     L_KAMOK_CD" . "\r\n";
        $strSQL .= ",     L_KOUMK_CD" . "\r\n";
        $strSQL .= ",     L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     L_KAZEI_KB" . "\r\n";
        $strSQL .= ",     L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     R_KAMOK_CD" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_NAME" . "\r\n";
        $strSQL .= ",     R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB" . "\r\n";
        // $strSQL .= ",     SHIHARAISAKI_CD" . "\r\n";
        // $strSQL .= ",     SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",     GINKO_KB" . "\r\n";
        $strSQL .= ",     GINKO_NM" . "\r\n";
        $strSQL .= ",     SHITEN_NM" . "\r\n";
        $strSQL .= ",     YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",     KOUZA_NO" . "\r\n";
        $strSQL .= ",     KOUZA_KN" . "\r\n";
        $strSQL .= ",     JIKI" . "\r\n";
        $strSQL .= ",     CREATE_DATE" . "\r\n";
        $strSQL .= ",     CRE_SYA_CD" . "\r\n";
        $strSQL .= ",     CRE_PRG_ID" . "\r\n";
        $strSQL .= ",     CRE_CLT_NM" . "\r\n";
        $strSQL .= ",     UPD_DATE" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD" . "\r\n";
        $strSQL .= ",     UPD_PRG_ID" . "\r\n";
        $strSQL .= ",     UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES(" . "\r\n";
        $strSQL .= "      '1'" . "\r\n";
        $strSQL .= ",     @PATTERN_NO" . "\r\n";
        $strSQL .= ",     '@PATTERN_NM'" . "\r\n";
        $strSQL .= ",     '@TAISYO_BUSYO_KB'" . "\r\n";
        $strSQL .= ",     '@TAISYO_BUSYO_CD'" . "\r\n";
        $strSQL .= ",     NULL" . "\r\n";
        //'経理処理日
        $strSQL .= ",     NULL" . "\r\n";
        //'取引発生日
        $strSQL .= ",     NULL" . "\r\n";
        //'支払予定日
        $strSQL .= ",     '@TEKYO'" . "\r\n";
        $strSQL .= ",     '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     '@L_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     '@L_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     @L_KAZEI_KB" . "\r\n";
        $strSQL .= ",     @L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     '@TORIHIKISAKI_CD'" . "\r\n";
        $strSQL .= ",     '@TORIHIKISAKI_NAME'" . "\r\n";
        $strSQL .= ",     @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     @R_ZEI_RT_KB" . "\r\n";
        //'請求書№
        $strSQL .= ",     NULL" . "\r\n";
        //'支払先コード
        // $strSQL .= ",     NULL" . "\r\n";
        // //'支払先名
        // $strSQL .= ",     NULL" . "\r\n";
        //'振込先銀行区分
        $strSQL .= ",     NULL" . "\r\n";
        //'振込先銀行名
        $strSQL .= ",     NULL" . "\r\n";
        //'振込先支店名
        $strSQL .= ",     NULL" . "\r\n";
        //'振込先預金種別
        $strSQL .= ",     NULL" . "\r\n";
        //'振込先口座番号
        $strSQL .= ",     NULL" . "\r\n";
        //'振込先口座名(ｶﾅ)
        $strSQL .= ",     NULL" . "\r\n";
        //'振込時期

        $strSQL .= ",     SYSDATE" . "\r\n";
        $strSQL .= ",     '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= ",     '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= ",     '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= ",     SYSDATE" . "\r\n";
        $strSQL .= ",     '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",     '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",     '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", "(SELECT NVL(MAX(PATTERN_NO),0) + 1 FROM HDPSHIWAKEPATTERNDATA WHERE DENPY_KB = '1')", $strSQL);
        $strSQL = str_replace("@PATTERN_NM", $postData['txtPatternNM'], $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_KB", $postData['radPatternKyotu'], $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtPatternBusyo']), $strSQL);

        // $strProgramID = "";

        // if ($postData['lblSyohy_no']) {
        //     $strProgramID = "ShiwakeInput";
        // } else {
        //     $strProgramID = "ShiwakeInputPtn";
        // }
        $strSQL = $this->subWhereSet($strSQL, $postData, $BusyoCD);

        return parent::insert($strSQL);
    }

    function fncUpdPatternTrk($strDenpy_Kb, $postData, $BusyoCD)
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= "SET   PATTERN_NM = '@PATTERN_NM'" . "\r\n";
        $strSQL .= ",     TAISYO_BUSYO_KB = '@TAISYO_BUSYO_KB'" . "\r\n";
        $strSQL .= ",     TAISYO_BUSYO_CD = '@TAISYO_BUSYO_CD'" . "\r\n";
        $strSQL .= ",     TEKYO = '@TEKYO'" . "\r\n";
        $strSQL .= ",     L_KAMOK_CD = '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     L_KOUMK_CD = '@L_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     L_HASEI_KYOTN_CD = '@L_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     L_KAZEI_KB = @L_KAZEI_KB" . "\r\n";
        $strSQL .= ",     L_ZEI_RT_KB = @L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     R_KAMOK_CD = '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD = '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD = '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_CD = '@TORIHIKISAKI_CD'" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_NAME = '@TORIHIKISAKI_NAME'" . "\r\n";
        $strSQL .= ",     R_KAZEI_KB = @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB = @R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",     UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",     UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE DENPY_KB = '@DENPY_KB'" . "\r\n";
        $strSQL .= "AND   PATTERN_NO = '@PATTERN_NO'" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", $postData["hidPatternNO"], $strSQL);
        $strSQL = str_replace("@PATTERN_NM", $postData['txtPatternNM'], $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_KB", $postData['radPatternKyotu'], $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtPatternBusyo']), $strSQL);
        $strSQL = str_replace("@DENPY_KB", $strDenpy_Kb, $strSQL);

        $strSQL = $this->subWhereSet($strSQL, $postData, $BusyoCD, "ShiwakeInputPtn");

        return parent::update($strSQL);
    }

    //SQL(パターン選択)
    function fncSelectPattern($strPattern_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT DENPY_KB" . "\r\n";
        $strSQL .= ",      PATTERN_NO" . "\r\n";
        $strSQL .= ",      PATTERN_NM" . "\r\n";
        $strSQL .= ",      TAISYO_BUSYO_KB" . "\r\n";
        $strSQL .= ",      TAISYO_BUSYO_CD" . "\r\n";
        $strSQL .= ",      TORIHIKI_DT" . "\r\n";
        $strSQL .= ",      SHIHARAI_DT" . "\r\n";
        $strSQL .= ",      TEKYO" . "\r\n";
        $strSQL .= ",      L_KAMOK_CD" . "\r\n";
        $strSQL .= ",      L_KOUMK_CD" . "\r\n";
        $strSQL .= ",      KAR.KAMOK_NAME L_KAMOK_NM" . "\r\n";
        $strSQL .= ",      KAR.SUB_KAMOK_NAME L_KOUMK_NM" . "\r\n";
        $strSQL .= ",      L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      LB.BUSYO_NM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",      L_KAZEI_KB" . "\r\n";
        $strSQL .= ",      L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      R_KAMOK_CD" . "\r\n";
        $strSQL .= ",      R_KOUMK_CD" . "\r\n";
        $strSQL .= ",      KAS.KAMOK_NAME R_KAMOK_NM" . "\r\n";
        $strSQL .= ",      KAS.SUB_KAMOK_NAME R_KOUMK_NM" . "\r\n";
        $strSQL .= ",      R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      RB.BUSYO_NM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",      SWK.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",      HMT.TORIHIKISAKI_NAME TORIHIKISAKI_NAME" . "\r\n";
        $strSQL .= ",      R_KAZEI_KB" . "\r\n";
        $strSQL .= ",      R_ZEI_RT_KB" . "\r\n";
        // $strSQL .= ",      SHIHARAISAKI_CD" . "\r\n";
        // $strSQL .= ",      SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",      GINKO_KB" . "\r\n";
        $strSQL .= ",      GINKO_NM" . "\r\n";
        $strSQL .= ",      SHITEN_NM" . "\r\n";
        $strSQL .= ",      YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",      KOUZA_NO" . "\r\n";
        $strSQL .= ",      KOUZA_KN" . "\r\n";
        $strSQL .= ",      JIKI" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA SWK" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAR" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') AND KAR.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAS" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUB_KAMOK_CD),'999999'),KAS.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999') AND KAS.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON     KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUB_KAMOK_CD),'999999'),KAS.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HDK_MST_BUMON LB" . "\r\n";
        $strSQL .= "ON     LB.BUSYO_CD = SWK.L_HASEI_KYOTN_CD AND LB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON RB" . "\r\n";
        $strSQL .= "ON     RB.BUSYO_CD = SWK.R_HASEI_KYOTN_CD AND RB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_TORIHIKISAKI HMT" . "\r\n";
        $strSQL .= "ON     HMT.TORIHIKISAKI_CD = SWK.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= "WHERE  PATTERN_NO = '@PATTERN_NO'" . "\r\n";
        $strSQL .= "AND    SWK.DENPY_KB = '1'" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", $strPattern_No, $strSQL);

        return parent::select($strSQL);
    }

    //SQL(パターン削除用)
    function fncPatternDelete($strPattern_No)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= "WHERE  PATTERN_NO = '@PATTERN_NO'" . "\r\n";
        $strSQL .= "AND    DENPY_KB   = 1" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", $strPattern_No, $strSQL);

        return parent::delete($strSQL);
    }

    function subWhereSet($strSQL, $postData, $BusyoCD, $strProgramID = "ShiwakeInput"): string
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = str_replace("@ZEIKM_GK", $this->ClsComFncHDKAIKEI->FncNv($postData["txtZeikm_GK"]), $strSQL);
        $strSQL = str_replace("@ZEINK_GK", $this->ClsComFncHDKAIKEI->FncNv($postData["lblZeink_GK"]), $strSQL);
        $strSQL = str_replace("@SHZEI_GK", $this->ClsComFncHDKAIKEI->FncNv($postData["lblSyohizei"]), $strSQL);
        $strSQL = str_replace("@TEKYO", $this->ClsComFncHDKAIKEI->FncNv($postData["txtTekyo"]), $strSQL);
        $strSQL = str_replace("@L_KAMOK_CD", $this->ClsComFncHDKAIKEI->FncNv($postData["txtLKamokuCD"]), $strSQL);
        $strSQL = str_replace("@L_KOUMK_CD", $this->ClsComFncHDKAIKEI->FncNv($postData["txtLKomokuCD"]), $strSQL);
        $strSQL = str_replace("@L_HASEI_KYOTN_CD", $this->ClsComFncHDKAIKEI->FncNv($postData["txtLBusyoCD"]), $strSQL);

        if ($this->ClsComFncHDKAIKEI->FncNv($postData["ddlLSyohizeiKbn"]) != "") {
            $strSQL = str_replace("@L_KAZEI_KB", "'" . $postData["ddlLSyohizeiKbn"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@L_KAZEI_KB", "''", $strSQL);
        }
        // 借方税率区分
        if ($postData["ddlLSyouhiKbn"] != null) {
            $strSQL = str_replace("@L_ZEI_RT_KB", "'" . $postData["ddlLSyouhiKbn"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@L_ZEI_RT_KB", "NULL", $strSQL);
        }

        $strSQL = str_replace("@R_KAMOK_CD", $this->ClsComFncHDKAIKEI->FncNv($postData["txtRKamokuCD"]), $strSQL);
        $strSQL = str_replace("@R_KOUMK_CD", $this->ClsComFncHDKAIKEI->FncNv($postData["txtRKomokuCD"]), $strSQL);
        $strSQL = str_replace("@R_HASEI_KYOTN_CD", $this->ClsComFncHDKAIKEI->FncNv($postData["txtRbusyoCD"]), $strSQL);

        $strSQL = str_replace("@TORIHIKISAKI_CD", $this->ClsComFncHDKAIKEI->FncNv($postData["lblKensakuCD"]), $strSQL);
        $strSQL = str_replace("@TORIHIKISAKI_NAME", $this->ClsComFncHDKAIKEI->FncNv($postData["lblKensakuNM"]), $strSQL);

        if ($this->ClsComFncHDKAIKEI->FncNv($postData["ddlRSyohizeiKbn"]) != "") {
            $strSQL = str_replace("@R_KAZEI_KB", "'" . $postData['ddlRSyohizeiKbn'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@R_KAZEI_KB", "''", $strSQL);
        }
        // 貸方税率区分
        if ($postData["ddlRSyouhiKbn"] != null) {
            $strSQL = str_replace("@R_ZEI_RT_KB", "'" . $postData["ddlRSyouhiKbn"] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@R_ZEI_RT_KB", "NULL", $strSQL);
        }
        $strSQL = str_replace("@FUKANZEN_FLG", $postData["fncFukanzenCheck"], $strSQL);

        $strSQL = str_replace("@CRE_BUSYO_CD", $BusyoCD, $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);

        $strSQL = str_replace("@CRE_PRG_ID", $strProgramID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        $strSQL = str_replace("@UPD_BUSYO_CD", $BusyoCD, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);

        $strSQL = str_replace("@UPD_PRG_ID", $strProgramID, $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return $strSQL;
    }

    //採番する
    function fncSaiban($strKbn, $strBusyoCD, $strNengetu)
    {
        $strSQL = "";
        $strSQL .= "SELECT SEQNO + 1 BANGO" . "\r\n";
        $strSQL .= "FROM   HDPSAIBAN SAI" . "\r\n";
        $strSQL .= "WHERE  SAI.DENPY_KB = '@DENPY_KB'" . "\r\n";
        $strSQL .= "AND    SAI.BUSYO_CD = '@BUSYO_CD'" . "\r\n";
        $strSQL .= "AND    SAI.NENGETU = '@NENGETU'" . "\r\n";

        $strSQL = str_replace("@DENPY_KB", $strKbn, $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $strBusyoCD, $strSQL);
        $strSQL = str_replace("@NENGETU", $strNengetu, $strSQL);

        return parent::select($strSQL);
    }

    function fncSaiban2($strKbn, $strBusyoCD, $strNengetu, $strProID, $objDr)
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        if ($objDr['row'] > 0) {
            $strSQL = "";
            $strSQL .= "UPDATE HDPSAIBAN" . "\r\n";
            $strSQL .= "   SET SEQNO = " . $this->ClsComFncHDKAIKEI->FncSqlNz($objDr['data'][0]["BANGO"]) . "\r\n";
            $strSQL .= "   ,   UPD_DATE = SYSDATE" . "\r\n";
            $strSQL .= "   ,   UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
            $strSQL .= "   ,   UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
            $strSQL .= "   ,   UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
            $strSQL .= " WHERE DENPY_KB = '@DENPY_KB'" . "\r\n";
            $strSQL .= "   AND BUSYO_CD = '@BUSYO_CD'" . "\r\n";
            $strSQL .= "   AND NENGETU = '@NENGETU'" . "\r\n";

            $strSQL = str_replace("@DENPY_KB", $strKbn, $strSQL);
            $strSQL = str_replace("@BUSYO_CD", $strBusyoCD, $strSQL);
            $strSQL = str_replace("@NENGETU", $strNengetu, $strSQL);
            $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
            $strSQL = str_replace("@UPD_PRG_ID", $strProID, $strSQL);
            $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

            return parent::update($strSQL);
        } else {
            $strSQL = "";
            $strSQL .= "INSERT INTO HDPSAIBAN" . "\r\n";
            $strSQL .= "(      DENPY_KB" . "\r\n";
            $strSQL .= ",      BUSYO_CD" . "\r\n";
            $strSQL .= ",      NENGETU" . "\r\n";
            $strSQL .= ",      SEQNO" . "\r\n";
            $strSQL .= ",      CREATE_DATE" . "\r\n";
            $strSQL .= ",      CRE_SYA_CD" . "\r\n";
            $strSQL .= ",      CRE_PRG_ID" . "\r\n";
            $strSQL .= ",      CRE_CLT_NM" . "\r\n";
            $strSQL .= ",      UPD_DATE" . "\r\n";
            $strSQL .= ",      UPD_SYA_CD" . "\r\n";
            $strSQL .= ",      UPD_PRG_ID" . "\r\n";
            $strSQL .= ",      UPD_CLT_NM)" . "\r\n";
            $strSQL .= " VALUES " . "\r\n";
            $strSQL .= "(      '@DENPY_KB'" . "\r\n";
            $strSQL .= ",      '@BUSYO_CD'" . "\r\n";
            $strSQL .= ",      '@NENGETU'" . "\r\n";
            $strSQL .= ",      @SEQNO" . "\r\n";
            $strSQL .= ",      SYSDATE" . "\r\n";
            $strSQL .= ",      '@CRE_SYA_CD'" . "\r\n";
            $strSQL .= ",      '@CRE_PRG_ID'" . "\r\n";
            $strSQL .= ",      '@CRE_CLT_NM'" . "\r\n";
            $strSQL .= ",      SYSDATE" . "\r\n";
            $strSQL .= ",      '@UPD_SYA_CD'" . "\r\n";
            $strSQL .= ",      '@UPD_PRG_ID'" . "\r\n";
            $strSQL .= ",      '@UPD_CLT_NM'" . "\r\n";
            $strSQL .= " ) " . "\r\n";

            $strSQL = str_replace("@DENPY_KB", $strKbn, $strSQL);
            $strSQL = str_replace("@BUSYO_CD", $strBusyoCD, $strSQL);
            $strSQL = str_replace("@NENGETU", $strNengetu, $strSQL);
            $strSQL = str_replace("@SEQNO", "1", $strSQL);
            $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
            $strSQL = str_replace("@CRE_PRG_ID", $strProID, $strSQL);
            $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
            $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
            $strSQL = str_replace("@UPD_PRG_ID", $strProID, $strSQL);
            $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

            return parent::insert($strSQL);
        }
    }

    //ワーク証憑№のデータを全件削除する
    function fncAllDelSQL()
    {
        $strSQL = "DELETE FROM WK_SYOHY_NO WHERE CRE_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' AND CRE_PRG_ID = 'DENPYO_SEARCH_PRINT' AND CRE_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "'";

        return parent::delete($strSQL);
    }

    function fnchmeisyoumst($MEISYOU_ID)
    {
        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "  '' MEISYOU_CD, " . "\r\n";
        $strSQL .= "  '' MEISYOU " . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "  DUAL " . "\r\n";
        $strSQL .= "UNION ALL " . "\r\n";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "  MEISYOU_CD, " . "\r\n";
        $strSQL .= "  MEISYOU " . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "  HMEISYOUMST " . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "  MEISYOU_ID='@MEISYOU_ID' " . "\r\n";

        $strSQL = str_replace("@MEISYOU_ID", $MEISYOU_ID, $strSQL);

        return parent::select($strSQL);
    }
    function fncFileCheck($SYOHY_NO, $EDA_NO, $GYO_NO)
    {
        $strSQL = "";
        $strSQL .= "SELECT SEQ " . "\r\n";
        $strSQL .= "FROM HDK_ATTACHMENT " . "\r\n";
        $strSQL .= "WHERE SYOHY_NO = '@SYOHY_NO' " . "\r\n";
        $strSQL .= "AND EDA_NO     = '@EDA_NO' " . "\r\n";
        $strSQL .= "AND GYO_NO     = '@GYO_NO' " . "\r\n";
        $strSQL .= "AND DEL_FLG    = '0' " . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $SYOHY_NO, $strSQL);
        $strSQL = str_replace("@EDA_NO", $EDA_NO, $strSQL);
        $strSQL = str_replace("@GYO_NO", $GYO_NO, $strSQL);

        return parent::select($strSQL);
    }
}
