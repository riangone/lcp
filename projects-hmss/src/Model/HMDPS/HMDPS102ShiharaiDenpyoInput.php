<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20240418          svn-ver.38694				          	VBソース変更対応           	 			lqs
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Model\HMDPS;

use App\Model\Component\ClsComDb;
use App\Model\HMDPS\Component\ClsComFncHMDPS;
use Cake\Routing\Router;

class HMDPS102ShiharaiDenpyoInput extends ClsComDb
{
    public $ClsComFncHMDPS;
    public $SessionComponent;
    function fncSelRkamokuForDdl()
    {
        $strSQL = "";
        $strSQL .= "SELECT V.SUCHI1 " . "\r\n";
        $strSQL .= ",	  V.MEISYOU" . "\r\n";
        $strSQL .= "FROM   ( " . "\r\n";
        $strSQL .= "		SELECT TO_NUMBER(SUBSTR(MEI.MEISYOU_CD,1,1) || MEI.SUCHI1) SUCHI1 " . "\r\n";
        $strSQL .= "		,	  MEI.MEISYOU " . "\r\n";
        $strSQL .= "		,	  MAX(MEI.MEISYOU_CD) MEISYOU_CD " . "\r\n";
        $strSQL .= "		,	  2 KBN " . "\r\n";
        $strSQL .= "		FROM   HMEISYOUMST MEI " . "\r\n";
        $strSQL .= "		WHERE  MEI.MEISYOU_ID = 'DK' " . "\r\n";
        $strSQL .= "		GROUP BY " . "\r\n";
        $strSQL .= "			   MEI.SUCHI1 " . "\r\n";
        $strSQL .= "		,	  MEI.MEISYOU " . "\r\n";
        $strSQL .= "		,	  SUBSTR(MEI.MEISYOU_CD,1,1) " . "\r\n";
        $strSQL .= "	 UNION ALL " . "\r\n";
        $strSQL .= "	 SELECT 0 SUCHI1 " . "\r\n";
        $strSQL .= "	 ,	  ''   MEISYOU " . "\r\n";
        $strSQL .= "	 ,	  ''   MEISYOU_CD " . "\r\n";
        $strSQL .= "	 ,	  1 KBN " . "\r\n";
        $strSQL .= "	 FROM   DUAL " . "\r\n";
        $strSQL .= "	 ) V " . "\r\n";
        $strSQL .= "ORDER BY V.KBN, TO_NUMBER(V.MEISYOU_CD) " . "\r\n";

        return parent::select($strSQL);
    }

    function fncSelRkomokuForDdl()
    {
        $strSQL = "";
        $strSQL .= "SELECT MEI.SUCHI2 " . "\r\n";
        $strSQL .= ",  MEI.MOJI1 " . "\r\n";
        $strSQL .= ",  MEI.SUCHI1" . "\r\n";
        $strSQL .= ",  SUBSTR(MEI.MEISYOU_CD,1,1) AS MEISYOUCD" . "\r\n";
        $strSQL .= "FROM   HMEISYOUMST MEI " . "\r\n";
        // $strSQL .= "WHERE  MEI.SUCHI1 = '@KAMOKUCD' " . "\r\n";
        // $strSQL .= "AND	SUBSTR(MEI.MEISYOU_CD,1,1) = '@MEISYOUCD' " . "\r\n";
        $strSQL .= "ORDER BY TO_NUMBER(MEI.MEISYOU_CD) " . "\r\n";

        // $strSQL = str_replace("@KAMOKUCD", substr(str_pad($strKamokuCD, 6), 1), $strSQL);
        // $strSQL = str_replace("@MEISYOUCD", substr(str_pad($strKamokuCD, 6), 0, 1), $strSQL);

        return parent::select($strSQL);
    }

    function fncSelMeisyoForDdl($strMeisyou)
    {
        $strSQL = "";
        $strSQL .= "SELECT MEISYOU_CD" . "\r\n";
        $strSQL .= ",	  MEISYOU" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "SELECT MEISYOU_CD" . "\r\n";
        $strSQL .= ",	  MEISYOU" . "\r\n";
        $strSQL .= ",	  '2' KBNID" . "\r\n";
        $strSQL .= ",CASE WHEN MEISYOU_CD = '04' THEN 4" . "\r\n";
        $strSQL .= "	  WHEN MEISYOU_CD = '05' THEN 3" . "\r\n";
        $strSQL .= "	  WHEN MEISYOU_CD = '06' THEN 2" . "\r\n";
        $strSQL .= "	  WHEN MEISYOU_CD = '07' THEN 1" . "\r\n";
        $strSQL .= "	  ELSE 9 END SORT_ORDER " . "\r\n";
        $strSQL .= "FROM   HMEISYOUMST" . "\r\n";
        $strSQL .= "WHERE  MEISYOU_ID = '@MEISYOU_ID'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT '' MEISYOU_CD" . "\r\n";
        $strSQL .= ",	  '' MEISYOU" . "\r\n";
        $strSQL .= ",	  '1' KBNID" . "\r\n";
        $strSQL .= ",	  0  SORT_ORDER " . "\r\n";
        $strSQL .= "FROM   DUAL" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "ORDER BY KBNID" . "\r\n";
        $strSQL .= ",	 SORT_ORDER" . "\r\n";
        $strSQL .= ",	 MEISYOU_CD" . "\r\n";

        $strSQL = str_replace("@MEISYOU_ID", $strMeisyou, $strSQL);

        return parent::select($strSQL);
    }

    function fncSelPattern($strBusyoCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT PATTERN_NO" . "\r\n";
        $strSQL .= ",	  PATTERN_NM" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "SELECT PATTERN_NO" . "\r\n";
        $strSQL .= ",	  PATTERN_NM" . "\r\n";
        $strSQL .= ",	  '2' KBNID" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= "WHERE  DENPY_KB = '2'" . "\r\n";
        $strSQL .= "AND	TAISYO_BUSYO_KB = '1'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT PATTERN_NO" . "\r\n";
        $strSQL .= ",	  PATTERN_NM" . "\r\n";
        $strSQL .= ",	  '2' KBNID" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= "WHERE  DENPY_KB = '2'" . "\r\n";
        $strSQL .= "AND	TAISYO_BUSYO_CD = '@BUSYOCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 0 PATTERN_NO" . "\r\n";
        $strSQL .= ",	  '' PATTERN_NM" . "\r\n";
        $strSQL .= ",	  '1' KBNID" . "\r\n";
        $strSQL .= "FROM   DUAL" . "\r\n";
        $strSQL .= ") V" . "\r\n";
        $strSQL .= "ORDER BY V.KBNID" . "\r\n";
        $strSQL .= ",		V.PATTERN_NM" . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $strBusyoCD, $strSQL);

        return parent::select($strSQL);
    }

    function fncMemoSelSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT MEISYOU" . "\r\n";
        $strSQL .= "FROM   HMEISYOUMST" . "\r\n";
        $strSQL .= "WHERE  MEISYOU_ID = '90'" . "\r\n";
        $strSQL .= "ORDER BY MEISYOU_CD" . "\r\n";

        return parent::select($strSQL);
    }

    function fncNewSyohyNOSel($strSyohy_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(EDA_NO) EDA_NO" . "\r\n";
        $strSQL .= ",	  MAX(DEL_FLG)" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND	DEL_FLG = '0'" . "\r\n";
        $strSQL .= "GROUP BY SYOHY_NO" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);

        return parent::select($strSQL);
    }

    function fncFlgCheckSQL($strSyohy_NO, $strEDA_NO)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(TO_CHAR(UPD_DATE,'YYYY/MM/DD HH24:MI:SS'))	  UPD_DATE" . "\r\n";
        $strSQL .= ",	  MAX(PRINT_OUT_FLG) PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",	  MAX(CSV_OUT_FLG)   CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",	  MAX(DEL_FLG)	   DEL_FLG" . "\r\n";
        $strSQL .= ",	  MAX(CRE_BUSYO_CD)  CRE_BUSYO_CD" . "\r\n";
        $strSQL .= ",	  MAX(UPD_BUSYO_CD)  UPD_BUSYO_CD" . "\r\n";
        $strSQL .= ",	  MAX(HONBU_SYORIZUMI_FLG) HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND	EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "GROUP BY SYOHY_NO" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_NO, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEDA_NO, $strSQL);

        return parent::select($strSQL);
    }

    function fncDispModeSansyoChk($strSyohy_NO)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(TO_CHAR(UPD_DATE,'YYYY/MM/DD HH24:MI:SS'))	  UPD_DATE" . "\r\n";
        $strSQL .= ",	  MAX(PRINT_OUT_FLG) PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",	  MAX(CSV_OUT_FLG)   CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",	  MAX(HONBU_SYORIZUMI_FLG) HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "GROUP BY SYOHY_NO" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_NO, $strSQL);

        return parent::select($strSQL);
    }

    function fncSyuuseiMaeSyohyoSel($strSyohy_No, $strEda_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(SYOHY_NO) SYOHY_NO" . "\r\n";
        $strSQL .= ",	  MAX(EDA_NO) EDA_NO" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND	EDA_NO   < '@EDA_NO'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEda_No, $strSQL);

        return parent::select($strSQL);
    }

    function fncSelPatternData($strPattern_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.DENPY_KB" . "\r\n";
        $strSQL .= ",	  SWK.PATTERN_NO" . "\r\n";
        $strSQL .= ",	  SWK.PATTERN_NM" . "\r\n";
        $strSQL .= ",	  SWK.TAISYO_BUSYO_KB" . "\r\n";
        $strSQL .= ",	  SWK.TAISYO_BUSYO_CD" . "\r\n";
        $strSQL .= ",	  SWK.KEIRI_DT" . "\r\n";
        $strSQL .= ",	  DECODE(SWK.TORIHIKI_DT,NULL,'',SUBSTR(SWK.TORIHIKI_DT,1,4) || '/' || SUBSTR(SWK.TORIHIKI_DT,5,2) || '/' || SUBSTR(SWK.TORIHIKI_DT,7,2)) TORIHIKI_DT" . "\r\n";
        $strSQL .= ",	  DECODE(SWK.SHIHARAI_DT,NULL,'',SUBSTR(SWK.SHIHARAI_DT,1,4) || '/' || SUBSTR(SWK.SHIHARAI_DT,5,2) || '/' || SUBSTR(SWK.SHIHARAI_DT,7,2)) SHIHARAI_DT" . "\r\n";
        $strSQL .= ",	  SWK.TEKYO" . "\r\n";
        $strSQL .= ",	  SWK.L_KAMOK_CD" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUMK_CD" . "\r\n";
        $strSQL .= ",	  (CASE WHEN L_KOUMK_CD IS NULL THEN KAR.KAMOK_SSK_NM ELSE KAR.KMK_KUM_NM END) L_KAMOK_NM" . "\r\n";
        $strSQL .= ",	  SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	  LB.BUSYO_RYKNM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",	  SWK.L_KAZEI_KB" . "\r\n";
        $strSQL .= ",	  SWK.L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	  SWK.L_TORHK_KB" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",	  SWK.R_KAMOK_CD" . "\r\n";
        $strSQL .= ",	  SWK.SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUMK_CD" . "\r\n";
        $strSQL .= ",	  (CASE WHEN R_KOUMK_CD IS NULL THEN KAS.KAMOK_SSK_NM ELSE KAS.KMK_KUM_NM END) R_KAMOK_NM" . "\r\n";
        $strSQL .= ",	  SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	  RB.BUSYO_RYKNM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",	  SWK.R_KAZEI_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_TORHK_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",	  SWK.SEIKYUSYO_NO" . "\r\n";
        $strSQL .= ",	  SWK.SHIHARAISAKI_CD" . "\r\n";
        $strSQL .= ",	  SWK.SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",	  SWK.GINKO_KB" . "\r\n";
        $strSQL .= ",	  SWK.GINKO_NM" . "\r\n";
        $strSQL .= ",	  SWK.SHITEN_NM" . "\r\n";
        $strSQL .= ",	  SWK.YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",	  SWK.KOUZA_NO" . "\r\n";
        $strSQL .= ",	  SWK.KOUZA_KN" . "\r\n";
        $strSQL .= ",	  SWK.JIKI" . "\r\n";
        $strSQL .= ",	  TO_CHAR(SWK.CREATE_DATE,'YYYY/MM/DD') CREATE_DATE" . "\r\n";
        $strSQL .= ",	  SWK.CRE_SYA_CD" . "\r\n";
        $strSQL .= ",	  SWK.CRE_PRG_ID" . "\r\n";
        $strSQL .= ",	  SWK.CRE_CLT_NM" . "\r\n";
        $strSQL .= ",	  SWK.UPD_DATE" . "\r\n";
        $strSQL .= ",	  SWK.UPD_SYA_CD" . "\r\n";
        $strSQL .= ",	  SWK.UPD_PRG_ID" . "\r\n";
        $strSQL .= ",	  SWK.UPD_CLT_NM" . "\r\n";
        //20240418 lqs INS S
        $strSQL .= ",     SWK.AITESAKI_KB" . "\r\n";
        $strSQL .= ",     SWK.OKYAKU_TORIHIKI_NO" . "\r\n";
        $strSQL .= ",     SWK.JIGYOSYA_NM" . "\r\n";
        $strSQL .= ",     SWK.INVOICE_ENTRYNO" . "\r\n";
        $strSQL .= ",     SWK.TOKUREI_KB" . "\r\n";
        //20240418 lqs INS E
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA SWK" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ6 KAR" . "\r\n";
        $strSQL .= "ON	 KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.KOUMK_CD),'999999'),KAR.KOUMK_CD) = NVL(SWK.L_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ6 KAS" . "\r\n";
        $strSQL .= "ON	 KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.KOUMK_CD),'999999'),KAS.KOUMK_CD) = NVL(SWK.R_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO LB" . "\r\n";
        $strSQL .= "ON	 LB.BUSYO_CD = SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO RB" . "\r\n";
        $strSQL .= "ON	 RB.BUSYO_CD = SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "WHERE  SWK.PATTERN_NO = '@PATTERN_NO'" . "\r\n";
        $strSQL .= "AND	SWK.DENPY_KB = '@DENPY_KB'" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", $strPattern_No, $strSQL);
        $strSQL = str_replace("@DENPY_KB", "2", $strSQL);

        return parent::select($strSQL);
    }

    function fncSelShiwakeData($strSyohy_NO, $strEDa_NO, $strGyo_NO)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",	  SWK.EDA_NO" . "\r\n";
        $strSQL .= ",	  SWK.GYO_NO" . "\r\n";
        $strSQL .= ",	  SWK.DENPY_KB" . "\r\n";
        $strSQL .= ",	  SWK.KEIJO_KB" . "\r\n";
        $strSQL .= ",	  DECODE(SWK.KEIRI_DT,NULL,'',SUBSTR(SWK.KEIRI_DT,1,4) || '/' || SUBSTR(SWK.KEIRI_DT,5,2) || '/' || SUBSTR(SWK.KEIRI_DT,7,2)) KEIRI_DT" . "\r\n";
        $strSQL .= ",	  DECODE(SWK.TORIHIKI_DT,NULL,'',SUBSTR(SWK.TORIHIKI_DT,1,4) || '/' || SUBSTR(SWK.TORIHIKI_DT,5,2) || '/' || SUBSTR(SWK.TORIHIKI_DT,7,2)) TORIHIKI_DT" . "\r\n";
        $strSQL .= ",	  DECODE(SWK.SHIHARAI_DT,NULL,'',SUBSTR(SWK.SHIHARAI_DT,1,4) || '/' || SUBSTR(SWK.SHIHARAI_DT,5,2) || '/' || SUBSTR(SWK.SHIHARAI_DT,7,2)) SHIHARAI_DT" . "\r\n";
        $strSQL .= ",	  SWK.ZEIKM_GK" . "\r\n";
        $strSQL .= ",	  SWK.ZEINK_GK" . "\r\n";
        $strSQL .= ",	  SWK.SHZEI_GK" . "\r\n";
        $strSQL .= ",	  SWK.TEKYO" . "\r\n";
        $strSQL .= ",	  SWK.L_KAMOK_CD" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUMK_CD" . "\r\n";
        $strSQL .= ",	  (CASE WHEN L_KOUMK_CD IS NULL THEN KAR.KAMOK_SSK_NM ELSE KAR.KMK_KUM_NM END) L_KAMOK_NM" . "\r\n";
        $strSQL .= ",	  SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	  LB.BUSYO_RYKNM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",	  SWK.L_KAZEI_KB" . "\r\n";
        $strSQL .= ",	  SWK.L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	  SWK.L_TORHK_KB" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",	  SWK.L_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",	  SWK.L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",	  SWK.R_KAMOK_CD" . "\r\n";
        $strSQL .= ",	  SWK.SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUMK_CD" . "\r\n";
        $strSQL .= ",	  (CASE WHEN R_KOUMK_CD IS NULL THEN KAS.KAMOK_SSK_NM ELSE KAS.KMK_KUM_NM END) R_KAMOK_NM" . "\r\n";
        $strSQL .= ",	  SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	  RB.BUSYO_RYKNM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",	  SWK.R_KAZEI_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_TORHK_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",	  SWK.R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",	  SWK.SEIKYUSYO_NO" . "\r\n";
        $strSQL .= ",	  SWK.SHIHARAISAKI_CD" . "\r\n";
        $strSQL .= ",	  SWK.SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",	  SWK.GINKO_KB" . "\r\n";
        $strSQL .= ",	  SWK.GINKO_NM" . "\r\n";
        $strSQL .= ",	  SWK.SHITEN_NM" . "\r\n";
        $strSQL .= ",	  SWK.YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",	  SWK.KOUZA_NO" . "\r\n";
        $strSQL .= ",	  SWK.KOUZA_KN" . "\r\n";
        $strSQL .= ",	  SWK.JIKI" . "\r\n";
        $strSQL .= ",	  SWK.FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",	  SWK.PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",	  SWK.CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",	  SWK.CSV_GROUP_NO" . "\r\n";
        $strSQL .= ",	  SWK.DEL_FLG" . "\r\n";
        $strSQL .= ",	  SWK.DEL_DATE" . "\r\n";
        $strSQL .= ",	  TO_CHAR(SWK.CREATE_DATE,'YYYY/MM/DD') CREATE_DATE" . "\r\n";
        $strSQL .= ",	  SWK.CRE_SYA_CD" . "\r\n";
        $strSQL .= ",	  SWK.CRE_PRG_ID" . "\r\n";
        $strSQL .= ",	  SWK.CRE_CLT_NM" . "\r\n";
        $strSQL .= ",	  SWK.UPD_DATE" . "\r\n";
        $strSQL .= ",	  SWK.UPD_SYA_CD" . "\r\n";
        $strSQL .= ",	  SWK.UPD_PRG_ID" . "\r\n";
        $strSQL .= ",	  SWK.UPD_CLT_NM" . "\r\n";
        //20240418 lqs INS S
        $strSQL .= ",     SWK.AITESAKI_KB" . "\r\n";
        $strSQL .= ",     SWK.OKYAKU_TORIHIKI_NO" . "\r\n";
        $strSQL .= ",     SWK.JIGYOSYA_NM" . "\r\n";
        $strSQL .= ",     SWK.INVOICE_ENTRYNO" . "\r\n";
        $strSQL .= ",     SWK.TOKUREI_KB" . "\r\n";
        //20240418 lqs INS E
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ6 KAR" . "\r\n";
        $strSQL .= "ON	 KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.KOUMK_CD),'999999'),KAR.KOUMK_CD) = NVL(SWK.L_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ6 KAS" . "\r\n";
        $strSQL .= "ON	 KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.KOUMK_CD),'999999'),KAS.KOUMK_CD) = NVL(SWK.R_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO LB" . "\r\n";
        $strSQL .= "ON	 LB.BUSYO_CD = SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO RB" . "\r\n";
        $strSQL .= "ON	 RB.BUSYO_CD = SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "WHERE  SWK.SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND	SWK.EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "AND	SWK.GYO_NO = '@GYO_NO'" . "\r\n";
        $strSQL .= "AND	SWK.DEL_FLG = '0'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_NO, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEDa_NO, $strSQL);
        $strSQL = str_replace("@GYO_NO", $strGyo_NO, $strSQL);

        return parent::select($strSQL);
    }

    function fncKouzaHittekiKashikata($strkamoku, $strkoumoku)
    {
        $this->ClsComFncHMDPS = new ClsComFncHMDPS();
        $strSQL = "";
        $strSQL .= "SELECT 'K1'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.KOZ_KEY1_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'K2'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.KOZ_KEY2_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'K3'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.KOZ_KEY3_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'K4'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.KOZ_KEY4_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'K5'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.KOZ_KEY5_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H1'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY1_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H2'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY2_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H3'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY3_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H4'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY4_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H5'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY5_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H6'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY6_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H7'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY7_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H8'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY8_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H9'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY9_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT 'H10'" . "\r\n";
        $strSQL .= ",	  KOM.KOBAN_NM" . "\r\n";
        $strSQL .= ",	  PTN.VALUE_DATA" . "\r\n";
        $strSQL .= "FROM   M29FZ6 KAM" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ7 KOM  " . "\r\n";
        $strSQL .= "ON	 KOM.KOBAN = KAM.HIS_TKY10_KOBAN" . "\r\n";
        $strSQL .= "LEFT JOIN HDPKMKKOBANFIRSTDATA PTN" . "\r\n";
        $strSQL .= "ON	 PTN.KOBAN = KOM.KOBAN" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KAMOK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KAMOK_CD)" . "\r\n";
        $strSQL .= "AND	DECODE(PTN.KAMOK_CD,' ','999999',PTN.KOUMK_CD) = DECODE(PTN.KAMOK_CD,' ','999999',KAM.KOUMK_CD)" . "\r\n";
        $strSQL .= "@WHEREKOUMOKU" . "\r\n";
        $strSQL .= "AND	KAM.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        $strSQL = str_replace("@KAMOKUCD", $this->ClsComFncHMDPS->FncNv($strkamoku), $strSQL);
        if ($strkoumoku == "" || $strkoumoku == null) {
            $strSQL = str_replace("@WHEREKOUMOKU", "WHERE  TRIM(KAM.KOUMK_CD) IS NULL", $strSQL);
        } else {
            $strSQL = str_replace("@WHEREKOUMOKU", "WHERE  KAM.KOUMK_CD = '@KOMOKUCD'", $strSQL);
            $strSQL = str_replace("@KOMOKUCD", $strkoumoku, $strSQL);
        }

        return parent::select($strSQL);
    }

    //SQL(追加処理)
    function fncShiwakeDataIns($strSyohy_No, $strEdaNO, $strGyo_NO, $strSysdate, $HONBUFLG, $postData)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HDPSHIWAKEDATA(" . "\r\n";
        $strSQL .= "	  SYOHY_NO" . "\r\n";
        $strSQL .= ",	 EDA_NO" . "\r\n";
        $strSQL .= ",	 GYO_NO" . "\r\n";
        $strSQL .= ",	 DENPY_KB" . "\r\n";
        $strSQL .= ",	 KEIJO_KB" . "\r\n";
        $strSQL .= ",	 KEIRI_DT" . "\r\n";
        $strSQL .= ",	 TORIHIKI_DT" . "\r\n";
        $strSQL .= ",	 SHIHARAI_DT" . "\r\n";
        $strSQL .= ",	 ZEIKM_GK" . "\r\n";
        $strSQL .= ",	 ZEINK_GK" . "\r\n";
        $strSQL .= ",	 SHZEI_GK" . "\r\n";
        $strSQL .= ",	 TEKYO" . "\r\n";
        $strSQL .= ",	 L_KAMOK_CD" . "\r\n";
        $strSQL .= ",	 L_KOUMK_CD" . "\r\n";
        $strSQL .= ",	 L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	 L_KAZEI_KB" . "\r\n";
        $strSQL .= ",	 L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	 L_TORHK_KB" . "\r\n";
        $strSQL .= ",	 L_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",	 L_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",	 L_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",	 L_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",	 L_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",	 L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",	 R_KAMOK_CD" . "\r\n";
        $strSQL .= ",	 SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",	 R_KOUMK_CD" . "\r\n";
        $strSQL .= ",	 R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	 R_KAZEI_KB" . "\r\n";
        $strSQL .= ",	 R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	 R_TORHK_KB" . "\r\n";
        $strSQL .= ",	 R_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",	 R_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",	 R_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",	 R_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",	 R_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",	 R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",	 SEIKYUSYO_NO" . "\r\n";
        $strSQL .= ",	 SHIHARAISAKI_CD" . "\r\n";
        $strSQL .= ",	 SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",	 GINKO_KB" . "\r\n";
        $strSQL .= ",	 GINKO_NM" . "\r\n";
        $strSQL .= ",	 SHITEN_NM" . "\r\n";
        $strSQL .= ",	 YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",	 KOUZA_NO" . "\r\n";
        $strSQL .= ",	 KOUZA_KN" . "\r\n";
        $strSQL .= ",	 JIKI" . "\r\n";
        $strSQL .= ",	 FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",	 HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= ",	 PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",	 CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",	 CSV_GROUP_NO" . "\r\n";
        $strSQL .= ",	 DEL_FLG" . "\r\n";
        $strSQL .= ",	 DEL_DATE" . "\r\n";
        $strSQL .= ",	 CREATE_DATE" . "\r\n";
        $strSQL .= ",	 CRE_BUSYO_CD" . "\r\n";
        $strSQL .= ",	 CRE_SYA_CD" . "\r\n";
        $strSQL .= ",	 CRE_PRG_ID" . "\r\n";
        $strSQL .= ",	 CRE_CLT_NM" . "\r\n";
        $strSQL .= ",	 UPD_DATE" . "\r\n";
        $strSQL .= ",	 UPD_BUSYO_CD" . "\r\n";
        $strSQL .= ",	 UPD_SYA_CD" . "\r\n";
        $strSQL .= ",	 UPD_PRG_ID" . "\r\n";
        $strSQL .= ",	 UPD_CLT_NM" . "\r\n";
        //20240418 lqs INS S
        $strSQL .= ",    AITESAKI_KB" . "\r\n";
        $strSQL .= ",    OKYAKU_TORIHIKI_NO" . "\r\n";
        $strSQL .= ",    JIGYOSYA_NM" . "\r\n";
        $strSQL .= ",    INVOICE_ENTRYNO" . "\r\n";
        $strSQL .= ",    TOKUREI_KB" . "\r\n";
        //20240418 lqs INS E
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES(" . "\r\n";
        $strSQL .= "	  '@SYOHY_NO'" . "\r\n";
        $strSQL .= ",	 '@EDA_NO'" . "\r\n";
        $strSQL .= ",	 @GYO_NO" . "\r\n";
        $strSQL .= ",	 '2'" . "\r\n";
        $strSQL .= ",	 '1'" . "\r\n";
        $strSQL .= ",	 NULL" . "\r\n";
        $strSQL .= ",	 '@TORIHIKI_DT'" . "\r\n";
        $strSQL .= ",	 '@SHIHARAI_DT'" . "\r\n";
        $strSQL .= ",	 @ZEIKM_GK" . "\r\n";
        $strSQL .= ",	 @ZEINK_GK" . "\r\n";
        $strSQL .= ",	 @SHZEI_GK" . "\r\n";
        $strSQL .= ",	 '@TEKYO'" . "\r\n";
        $strSQL .= ",	 '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",	 '@L_KOUMK_CD'" . "\r\n";
        $strSQL .= ",	 '@L_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",	 @L_KAZEI_KB" . "\r\n";
        $strSQL .= ",	 @L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	 @L_TORHK_KB" . "\r\n";
        $strSQL .= ",	 '@L_KOUZA_KEY1'" . "\r\n";
        $strSQL .= ",	 '@L_KOUZA_KEY2'" . "\r\n";
        $strSQL .= ",	 '@L_KOUZA_KEY3'" . "\r\n";
        $strSQL .= ",	 '@L_KOUZA_KEY4'" . "\r\n";
        $strSQL .= ",	 '@L_KOUZA_KEY5'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYO1'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYO2'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYO3'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYO4'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYO5'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYO6'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYO7'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYO8'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYO9'" . "\r\n";
        $strSQL .= ",	 '@L_HISSU_TEKYOA'" . "\r\n";
        $strSQL .= ",	 '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",	 '@SHR_KAMOK_KB'" . "\r\n";
        $strSQL .= ",	 '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",	 '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",	 @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",	 @R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	 @R_TORHK_KB" . "\r\n";
        $strSQL .= ",	 '@R_KOUZA_KEY1'" . "\r\n";
        $strSQL .= ",	 '@R_KOUZA_KEY2'" . "\r\n";
        $strSQL .= ",	 '@R_KOUZA_KEY3'" . "\r\n";
        $strSQL .= ",	 '@R_KOUZA_KEY4'" . "\r\n";
        $strSQL .= ",	 '@R_KOUZA_KEY5'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYO1'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYO2'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYO3'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYO4'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYO5'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYO6'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYO7'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYO8'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYO9'" . "\r\n";
        $strSQL .= ",	 '@R_HISSU_TEKYOA'" . "\r\n";
        $strSQL .= ",	 '@SEIKYUSYO_NO'" . "\r\n";
        //請求書№
        $strSQL .= ",	 '@SHIHARAISAKI_CD'" . "\r\n";
        //支払先コード
        $strSQL .= ",	 '@SHIHARAISAKI_NM'" . "\r\n";
        //支払先名
        $strSQL .= ",	 '@GINKO_KB'" . "\r\n";
        //振込先銀行区分
        $strSQL .= ",	 '@GINKO_NM'" . "\r\n";
        //振込先銀行名
        $strSQL .= ",	 '@SHITEN_NM'" . "\r\n";
        //振込先支店名
        $strSQL .= ",	 '@YOKIN_SYUBETU'" . "\r\n";
        //振込先預金種別
        $strSQL .= ",	 '@KOUZA_NO'" . "\r\n";
        //振込先口座番号
        $strSQL .= ",	 '@KOUZA_KN'" . "\r\n";
        //振込先口座名(ｶﾅ)
        $strSQL .= ",	 '@JIKI'" . "\r\n";
        //振込時期
        $strSQL .= ",	 '@FUKANZEN_FLG'" . "\r\n";
        $strSQL .= ",	 '@HONBUFLG'" . "\r\n";
        //本部処理済みフラグ '2009/03/12 INS Start FLG追加のため
        $strSQL .= ",	 0" . "\r\n";
        //印刷フラグ
        $strSQL .= ",	 0" . "\r\n";
        //ＣＳＶ出力フラグ
        $strSQL .= ",	 NULL" . "\r\n";
        //ＣＳＶ出力グループ名
        $strSQL .= ",	 0" . "\r\n";
        //削除フラグ
        $strSQL .= ",	 NULL" . "\r\n";
        //削除日
        $strSQL .= ",	 TO_DATE('@CREATE_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",	 '@CRE_BUSYO_CD'" . "\r\n";
        $strSQL .= ",	 '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= ",	 '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= ",	 '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= ",	 TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",	 '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",	 '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",	 '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",	 '@UPD_CLT_NM'" . "\r\n";
        //20240418 lqs INS S
        $strSQL .= ",    '@AITESAKI_KB'" . "\r\n";
        $strSQL .= ",    '@OKYAKU_TORIHIKI_NO'" . "\r\n";
        $strSQL .= ",    '@JIGYOSYA_NM'" . "\r\n";
        $strSQL .= ",    '@INVOICE_ENTRYNO'" . "\r\n";
        $strSQL .= ",    '@TOKUREI_KB'" . "\r\n";
        //20240418 lqs INS E
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@GYO_NO", $strGyo_NO, $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEdaNO, $strSQL);
        $strSQL = str_replace("@HONBUFLG", $HONBUFLG, $strSQL);

        $strSQL = $this->subWhereSet($postData, $strSQL);

        $strSQL = str_replace("@CREATE_DATE", $strSysdate, $strSQL);
        $strSQL = str_replace("@UPD_DATE", $strSysdate, $strSQL);

        return parent::insert($strSQL);
    }

    function fncMaeShiwakeCopy($strSyohy_no, $intNewEdaNo, $strOldEdaNO, $strDate, $FLG)
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
        $strSQL .= ",     L_TORHK_KB" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",     R_KAMOK_CD" . "\r\n";
        $strSQL .= ",     SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     R_TORHK_KB" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",     SEIKYUSYO_NO" . "\r\n";
        $strSQL .= ",     SHIHARAISAKI_CD" . "\r\n";
        $strSQL .= ",     SHIHARAISAKI_NM" . "\r\n";
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
        $strSQL .= ",     CSV_GROUP_NO" . "\r\n";
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
        //20240418 lqs INS S
        $strSQL .= ",     AITESAKI_KB" . "\r\n";
        $strSQL .= ",     OKYAKU_TORIHIKI_NO" . "\r\n";
        $strSQL .= ",     JIGYOSYA_NM" . "\r\n";
        $strSQL .= ",     INVOICE_ENTRYNO" . "\r\n";
        $strSQL .= ",     TOKUREI_KB" . "\r\n";
        //20240418 lqs INS E
        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT SYOHY_NO" . "\r\n";
        $strSQL .= ",      '@NEW_EDA_NO'" . "\r\n";
        $strSQL .= ",      GYO_NO" . "\r\n";
        $strSQL .= ",      '2'" . "\r\n";
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
        $strSQL .= ",      L_TORHK_KB" . "\r\n";
        $strSQL .= ",      L_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",      L_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",      L_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",      L_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",      L_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",      L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",      R_KAMOK_CD" . "\r\n";
        $strSQL .= ",      SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",      R_KOUMK_CD" . "\r\n";
        $strSQL .= ",      R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      R_KAZEI_KB" . "\r\n";
        $strSQL .= ",      R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      R_TORHK_KB" . "\r\n";
        $strSQL .= ",      R_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",      R_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",      R_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",      R_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",      R_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",      R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",      SEIKYUSYO_NO" . "\r\n";
        $strSQL .= ",      SHIHARAISAKI_CD" . "\r\n";
        $strSQL .= ",      SHIHARAISAKI_NM" . "\r\n";
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
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      '0'" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      CRE_BUSYO_CD" . "\r\n";
        $strSQL .= ",      CRE_SYA_CD" . "\r\n";
        $strSQL .= ",      CRE_PRG_ID" . "\r\n";
        $strSQL .= ",      CRE_CLT_NM" . "\r\n";
        $strSQL .= ",      TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",      '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",      '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",      'ShiharaiInput'" . "\r\n";
        $strSQL .= ",      '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ",     CSV_OUT_ORDER" . "\r\n";
        //20240418 lqs INS S
        $strSQL .= ",     AITESAKI_KB" . "\r\n";
        $strSQL .= ",     OKYAKU_TORIHIKI_NO" . "\r\n";
        $strSQL .= ",     JIGYOSYA_NM" . "\r\n";
        $strSQL .= ",     INVOICE_ENTRYNO" . "\r\n";
        $strSQL .= ",     TOKUREI_KB" . "\r\n";
        //20240418 lqs INS E
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@EDA_NO'" . "\r\n";

        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = str_replace("@NEW_EDA_NO", str_pad($intNewEdaNo, 2, "0", STR_PAD_LEFT), $strSQL);
        $strSQL = str_replace("@UPD_DATE", $strDate, $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@EDA_NO", $strOldEdaNO, $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $strSyohy_no, $strSQL);

        $strSQL = str_replace("@HONBU_SYORIZUMI_FLG", $FLG, $strSQL);

        return parent::insert($strSQL);
    }

    //SQL(修正処理用)
    function fncUpdateSQL($strSyohy_No, $strEdaNO, $strGyo_NO, $strSysdate, $FLG, $postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "SET		KEIRI_DT = '@KEIRI_DT'" . "\r\n";
        $strSQL .= ",		ZEIKM_GK = '@ZEIKM_GK'" . "\r\n";
        $strSQL .= ",		ZEINK_GK = '@ZEINK_GK'" . "\r\n";
        $strSQL .= ",		SHZEI_GK = '@SHZEI_GK'" . "\r\n";
        $strSQL .= ",		TEKYO = '@TEKYO'" . "\r\n";
        $strSQL .= ",		TORIHIKI_DT = '@TORIHIKI_DT'" . "\r\n";
        $strSQL .= ",		SHIHARAI_DT = '@SHIHARAI_DT'" . "\r\n";
        $strSQL .= ",		L_KAMOK_CD = '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",		L_KOUMK_CD = '@L_KOUMK_CD'" . "\r\n";
        $strSQL .= ",		L_HASEI_KYOTN_CD = '@L_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",		L_KAZEI_KB = @L_KAZEI_KB" . "\r\n";
        $strSQL .= ",		L_ZEI_RT_KB = @L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",		L_TORHK_KB = @L_TORHK_KB" . "\r\n";
        $strSQL .= ",		L_KOUZA_KEY1 = '@L_KOUZA_KEY1'" . "\r\n";
        $strSQL .= ",		L_KOUZA_KEY2 = '@L_KOUZA_KEY2'" . "\r\n";
        $strSQL .= ",		L_KOUZA_KEY3 = '@L_KOUZA_KEY3'" . "\r\n";
        $strSQL .= ",		L_KOUZA_KEY4 = '@L_KOUZA_KEY4'" . "\r\n";
        $strSQL .= ",		L_KOUZA_KEY5 = '@L_KOUZA_KEY5'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO1 = '@L_HISSU_TEKYO1'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO2 = '@L_HISSU_TEKYO2'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO3 = '@L_HISSU_TEKYO3'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO4 = '@L_HISSU_TEKYO4'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO5 = '@L_HISSU_TEKYO5'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO6 = '@L_HISSU_TEKYO6'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO7 = '@L_HISSU_TEKYO7'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO8 = '@L_HISSU_TEKYO8'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO9 = '@L_HISSU_TEKYO9'" . "\r\n";
        $strSQL .= ",		L_HISSU_TEKYO10 = '@L_HISSU_TEKYOA'" . "\r\n";
        $strSQL .= ",		R_KAMOK_CD = '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",		SHR_KAMOK_KB = '@SHR_KAMOK_KB'" . "\r\n";
        $strSQL .= ",		R_KOUMK_CD = '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",		R_HASEI_KYOTN_CD = '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",		R_KAZEI_KB = @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",		R_ZEI_RT_KB = @R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",		R_TORHK_KB = @R_TORHK_KB" . "\r\n";
        $strSQL .= ",		R_KOUZA_KEY1 = '@R_KOUZA_KEY1'" . "\r\n";
        $strSQL .= ",		R_KOUZA_KEY2 = '@R_KOUZA_KEY2'" . "\r\n";
        $strSQL .= ",		R_KOUZA_KEY3 = '@R_KOUZA_KEY3'" . "\r\n";
        $strSQL .= ",		R_KOUZA_KEY4 = '@R_KOUZA_KEY4'" . "\r\n";
        $strSQL .= ",		R_KOUZA_KEY5 = '@R_KOUZA_KEY5'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO1 = '@R_HISSU_TEKYO1'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO2 = '@R_HISSU_TEKYO2'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO3 = '@R_HISSU_TEKYO3'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO4 = '@R_HISSU_TEKYO4'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO5 = '@R_HISSU_TEKYO5'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO6 = '@R_HISSU_TEKYO6'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO7 = '@R_HISSU_TEKYO7'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO8 = '@R_HISSU_TEKYO8'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO9 = '@R_HISSU_TEKYO9'" . "\r\n";
        $strSQL .= ",		R_HISSU_TEKYO10 = '@R_HISSU_TEKYOA'" . "\r\n";
        $strSQL .= ",		SEIKYUSYO_NO = '@SEIKYUSYO_NO'" . "\r\n";
        $strSQL .= ",		SHIHARAISAKI_CD = '@SHIHARAISAKI_CD'" . "\r\n";
        $strSQL .= ",		SHIHARAISAKI_NM = '@SHIHARAISAKI_NM'" . "\r\n";
        $strSQL .= ",		GINKO_KB = '@GINKO_KB'" . "\r\n";
        $strSQL .= ",		GINKO_NM = '@GINKO_NM'" . "\r\n";
        $strSQL .= ",		SHITEN_NM = '@SHITEN_NM'" . "\r\n";
        $strSQL .= ",		YOKIN_SYUBETU = '@YOKIN_SYUBETU'" . "\r\n";
        $strSQL .= ",		KOUZA_NO = '@KOUZA_NO'" . "\r\n";
        $strSQL .= ",		KOUZA_KN = '@KOUZA_KN'" . "\r\n";
        $strSQL .= ",		JIKI = '@JIKI'" . "\r\n";
        $strSQL .= ",		FUKANZEN_FLG = '@FUKANZEN_FLG'" . "\r\n";
        //'経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
        if ($FLG == "1") {
            $strSQL .= ",		HONBU_SYORIZUMI_FLG = '1'" . "\r\n";
        }
        $strSQL .= ",		PRINT_OUT_FLG = '0'" . "\r\n";
        $strSQL .= ",		CSV_OUT_FLG = '0'" . "\r\n";
        $strSQL .= ",		CSV_GROUP_NO = NULL" . "\r\n";
        $strSQL .= ",		DEL_FLG = '0'" . "\r\n";
        $strSQL .= ",		DEL_DATE = NULL" . "\r\n";
        $strSQL .= ",		UPD_DATE = TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",		UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",		UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",		UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",		UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        //20240418 lqs INS S
        $strSQL .= ",		AITESAKI_KB = '@AITESAKI_KB'" . "\r\n";
        $strSQL .= ",		OKYAKU_TORIHIKI_NO = '@OKYAKU_TORIHIKI_NO'" . "\r\n";
        $strSQL .= ",		JIGYOSYA_NM = '@JIGYOSYA_NM'" . "\r\n";
        $strSQL .= ",		INVOICE_ENTRYNO = '@INVOICE_ENTRYNO'" . "\r\n";
        $strSQL .= ",		TOKUREI_KB = '@TOKUREI_KB'" . "\r\n";
        //20240418 lqs INS E
        $strSQL .= "WHERE	SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND		EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "AND		GYO_NO = '@GYO_NO'" . "\r\n";

        $strSQL = str_replace("@GYO_NO", $strGyo_NO, $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEdaNO, $strSQL);
        $strSQL = str_replace("@KEIRI_DT", $postData['txtKeiriSyoriDT'], $strSQL);
        $strSQL = $this->subWhereSet($postData, $strSQL);
        $strSQL = str_replace("@CREATE_DATE", $strSysdate, $strSQL);
        $strSQL = str_replace("@UPD_DATE", $strSysdate, $strSQL);

        return parent::update($strSQL);
    }

    //SQL(全削除処理用)
    function fncAllDeleteUpd($strSyohy_No, $strSysdate, $FLG)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "SET    DEL_FLG = '1'" . "\r\n";
        $strSQL .= ",      DEL_DATE = TO_CHAR(TO_DATE('@DEL_DATE','YYYY/MM/DD HH24:MI:SS'),'YYYYMMDD')" . "\r\n";
        //'経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
        if ($FLG == "1") {
            $strSQL .= ",      HONBU_SYORIZUMI_FLG = '1'" . "\r\n";
        }
        $strSQL .= ",		UPD_DATE = TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",		UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",		UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",		UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",		UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@DEL_DATE", $strSysdate, $strSQL);

        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = str_replace("@UPD_DATE", $strSysdate, $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "ShiwakeInput", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    function fncAllDelete($strSyohy_no, $strEda_No)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@EDA_NO'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_no, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEda_No, $strSQL);
        return parent::delete($strSQL);
    }

    //SQL(パターン登録用)
    function fncPatternTrkDispShiwake($postData)
    {
        $this->ClsComFncHMDPS = new ClsComFncHMDPS();
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
        $strSQL .= ",     L_TORHK_KB" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",     R_KAMOK_CD" . "\r\n";
        $strSQL .= ",     SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     R_TORHK_KB" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",     SEIKYUSYO_NO" . "\r\n";
        $strSQL .= ",     SHIHARAISAKI_CD" . "\r\n";
        $strSQL .= ",     SHIHARAISAKI_NM" . "\r\n";
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
        //20240418 lqs INS S
        $strSQL .= ",     AITESAKI_KB" . "\r\n";
        $strSQL .= ",     OKYAKU_TORIHIKI_NO" . "\r\n";
        $strSQL .= ",     JIGYOSYA_NM" . "\r\n";
        $strSQL .= ",     INVOICE_ENTRYNO" . "\r\n";
        $strSQL .= ",     TOKUREI_KB" . "\r\n";
        //20240418 lqs INS E
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES(" . "\r\n";
        $strSQL .= "      '2'" . "\r\n";
        $strSQL .= ",     @PATTERN_NO" . "\r\n";
        $strSQL .= ",     '@PATTERN_NM'" . "\r\n";
        $strSQL .= ",     '@TAISYO_BUSYO_KB'" . "\r\n";
        $strSQL .= ",     '@TAISYO_BUSYO_CD'" . "\r\n";
        //'経理処理日
        $strSQL .= ",     NULL" . "\r\n";
        //'取引発生日
        $strSQL .= ",     '@TORIHIKI_DT'" . "\r\n";
        //'支払予定日
        $strSQL .= ",     '@SHIHARAI_DT'" . "\r\n";
        $strSQL .= ",     '@TEKYO'" . "\r\n";
        $strSQL .= ",     '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     '@L_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     '@L_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     @L_KAZEI_KB" . "\r\n";
        $strSQL .= ",     @L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     @L_TORHK_KB" . "\r\n";
        $strSQL .= ",     '@L_KOUZA_KEY1'" . "\r\n";
        $strSQL .= ",     '@L_KOUZA_KEY2'" . "\r\n";
        $strSQL .= ",     '@L_KOUZA_KEY3'" . "\r\n";
        $strSQL .= ",     '@L_KOUZA_KEY4'" . "\r\n";
        $strSQL .= ",     '@L_KOUZA_KEY5'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYO1'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYO2'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYO3'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYO4'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYO5'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYO6'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYO7'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYO8'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYO9'" . "\r\n";
        $strSQL .= ",     '@L_HISSU_TEKYOA'" . "\r\n";
        $strSQL .= ",     '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     '@SHR_KAMOK_KB'" . "\r\n";
        $strSQL .= ",     '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     @R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     @R_TORHK_KB" . "\r\n";
        $strSQL .= ",     '@R_KOUZA_KEY1'" . "\r\n";
        $strSQL .= ",     '@R_KOUZA_KEY2'" . "\r\n";
        $strSQL .= ",     '@R_KOUZA_KEY3'" . "\r\n";
        $strSQL .= ",     '@R_KOUZA_KEY4'" . "\r\n";
        $strSQL .= ",     '@R_KOUZA_KEY5'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYO1'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYO2'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYO3'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYO4'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYO5'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYO6'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYO7'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYO8'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYO9'" . "\r\n";
        $strSQL .= ",     '@R_HISSU_TEKYOA'" . "\r\n";
        //請求書№
        $strSQL .= ",     '@SEIKYUSYO_NO'" . "\r\n";
        //'支払先コード
        $strSQL .= ",     '@SHIHARAISAKI_CD'" . "\r\n";
        //'支払先名
        $strSQL .= ",     '@SHIHARAISAKI_NM'" . "\r\n";
        //'振込先銀行区分
        $strSQL .= ",     '@GINKO_KB'" . "\r\n";
        //'振込先銀行名
        $strSQL .= ",     '@GINKO_NM'" . "\r\n";
        //'振込先支店名
        $strSQL .= ",     '@SHITEN_NM'" . "\r\n";
        //'振込先預金種別
        $strSQL .= ",     '@YOKIN_SYUBETU'" . "\r\n";
        //'振込先口座番号
        $strSQL .= ",     '@KOUZA_NO'" . "\r\n";
        //'振込先口座名(ｶﾅ)
        $strSQL .= ",     '@KOUZA_KN'" . "\r\n";
        //'振込時期
        $strSQL .= ",     '@JIKI'" . "\r\n";

        $strSQL .= ",     SYSDATE" . "\r\n";
        $strSQL .= ",     '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= ",     '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= ",     '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= ",     SYSDATE" . "\r\n";
        $strSQL .= ",     '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",     '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",     '@UPD_CLT_NM'" . "\r\n";
        //20240418 lqs INS S
        $strSQL .= ",     '@AITESAKI_KB'" . "\r\n";
        $strSQL .= ",     '@OKYAKU_TORIHIKI_NO'" . "\r\n";
        $strSQL .= ",     '@JIGYOSYA_NM'" . "\r\n";
        $strSQL .= ",     '@INVOICE_ENTRYNO'" . "\r\n";
        $strSQL .= ",     '@TOKUREI_KB'" . "\r\n";
        // 20240418 lqs INS E
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", "(SELECT NVL(MAX(PATTERN_NO),0) + 1 FROM HDPSHIWAKEPATTERNDATA WHERE DENPY_KB = '2')", $strSQL);
        $strSQL = str_replace("@PATTERN_NM", $postData['txtPatternNM'], $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_KB", $postData['grpPattern'] == "radPatternKyotu" ? "1" : "2", $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_CD", $postData['grpPattern'] == "radPatternBusyo" ? $this->ClsComFncHMDPS->FncNv($postData['txtPatternBusyo']) : "", $strSQL);

        $strProgramID = "";
        if ($postData['lblSyohyNoVis'] == "1") {
            $strProgramID = "ShiharaiInput";
        } else {
            $strProgramID = "ShiharaiInputPtn";
        }

        $strSQL = $this->subWhereSet($postData, $strSQL, $strProgramID, "PATTERN");

        return parent::insert($strSQL);
    }

    function fncUpdPatternTrk($strDenpy_Kb, $strPattern_No, $postData)
    {
        $this->ClsComFncHMDPS = new ClsComFncHMDPS();
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= "SET   PATTERN_NM = '@PATTERN_NM'" . "\r\n";
        $strSQL .= ",     TAISYO_BUSYO_KB = '@TAISYO_BUSYO_KB'" . "\r\n";
        $strSQL .= ",     TAISYO_BUSYO_CD = '@TAISYO_BUSYO_CD'" . "\r\n";
        $strSQL .= ",     TORIHIKI_DT = '@TORIHIKI_DT'" . "\r\n";
        $strSQL .= ",     SHIHARAI_DT = '@SHIHARAI_DT'" . "\r\n";
        $strSQL .= ",     TEKYO = '@TEKYO'" . "\r\n";
        $strSQL .= ",     L_KAMOK_CD = '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     L_KOUMK_CD = '@L_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     L_HASEI_KYOTN_CD = '@L_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     L_KAZEI_KB = @L_KAZEI_KB" . "\r\n";
        $strSQL .= ",     L_ZEI_RT_KB = @L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     L_TORHK_KB = @L_TORHK_KB" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY1 = '@L_KOUZA_KEY1'" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY2 = '@L_KOUZA_KEY2'" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY3 = '@L_KOUZA_KEY3'" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY4 = '@L_KOUZA_KEY4'" . "\r\n";
        $strSQL .= ",     L_KOUZA_KEY5 = '@L_KOUZA_KEY5'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO1 = '@L_HISSU_TEKYO1'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO2 = '@L_HISSU_TEKYO2'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO3 = '@L_HISSU_TEKYO3'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO4 = '@L_HISSU_TEKYO4'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO5 = '@L_HISSU_TEKYO5'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO6 = '@L_HISSU_TEKYO6'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO7 = '@L_HISSU_TEKYO7'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO8 = '@L_HISSU_TEKYO8'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO9 = '@L_HISSU_TEKYO9'" . "\r\n";
        $strSQL .= ",     L_HISSU_TEKYO10 = '@L_HISSU_TEKYOA'" . "\r\n";
        $strSQL .= ",     R_KAMOK_CD = '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",     SHR_KAMOK_KB = '@SHR_KAMOK_KB'" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD = '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD = '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     R_KAZEI_KB = @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB = @R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     R_TORHK_KB = @R_TORHK_KB" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY1 = '@R_KOUZA_KEY1'" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY2 = '@R_KOUZA_KEY2'" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY3 = '@R_KOUZA_KEY3'" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY4 = '@R_KOUZA_KEY4'" . "\r\n";
        $strSQL .= ",     R_KOUZA_KEY5 = '@R_KOUZA_KEY5'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO1 = '@R_HISSU_TEKYO1'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO2 = '@R_HISSU_TEKYO2'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO3 = '@R_HISSU_TEKYO3'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO4 = '@R_HISSU_TEKYO4'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO5 = '@R_HISSU_TEKYO5'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO6 = '@R_HISSU_TEKYO6'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO7 = '@R_HISSU_TEKYO7'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO8 = '@R_HISSU_TEKYO8'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO9 = '@R_HISSU_TEKYO9'" . "\r\n";
        $strSQL .= ",     R_HISSU_TEKYO10 = '@R_HISSU_TEKYOA'" . "\r\n";
        $strSQL .= ",     SEIKYUSYO_NO = '@SEIKYUSYO_NO'" . "\r\n";
        $strSQL .= ",     SHIHARAISAKI_CD = '@SHIHARAISAKI_CD'" . "\r\n";
        $strSQL .= ",     SHIHARAISAKI_NM = '@SHIHARAISAKI_NM'" . "\r\n";
        $strSQL .= ",     GINKO_KB = '@GINKO_KB'" . "\r\n";
        $strSQL .= ",     GINKO_NM = '@GINKO_NM'" . "\r\n";
        $strSQL .= ",     SHITEN_NM = '@SHITEN_NM'" . "\r\n";
        $strSQL .= ",     YOKIN_SYUBETU = '@YOKIN_SYUBETU'" . "\r\n";
        $strSQL .= ",     KOUZA_NO = '@KOUZA_NO'" . "\r\n";
        $strSQL .= ",     KOUZA_KN = '@KOUZA_KN'" . "\r\n";
        $strSQL .= ",     JIKI = '@JIKI'" . "\r\n";
        $strSQL .= ",     UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",     UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",     UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        //20240418 lqs INS S
        $strSQL .= ",     AITESAKI_KB = '@AITESAKI_KB'" . "\r\n";
        $strSQL .= ",     OKYAKU_TORIHIKI_NO = '@OKYAKU_TORIHIKI_NO'" . "\r\n";
        $strSQL .= ",     JIGYOSYA_NM = '@JIGYOSYA_NM'" . "\r\n";
        $strSQL .= ",     INVOICE_ENTRYNO = '@INVOICE_ENTRYNO'" . "\r\n";
        $strSQL .= ",     TOKUREI_KB = '@TOKUREI_KB'" . "\r\n";
        //20240418 lqs INS E
        $strSQL .= "WHERE DENPY_KB = '@DENPY_KB'" . "\r\n";
        $strSQL .= "AND   PATTERN_NO = '@PATTERN_NO'" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", $strPattern_No, $strSQL);
        $strSQL = str_replace("@PATTERN_NM", $postData['txtPatternNM'], $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_KB", $postData['grpPattern'] == "radPatternKyotu" ? "1" : "2", $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_CD", $postData['grpPattern'] == "radPatternBusyo" ? $this->ClsComFncHMDPS->FncNv($postData['txtPatternBusyo']) : "", $strSQL);
        $strSQL = str_replace("@DENPY_KB", $strDenpy_Kb, $strSQL);

        $strSQL = $this->subWhereSet($postData, $strSQL, "ShiharaiInputPtn", "PATTERN");

        return parent::update($strSQL);
    }

    //SQL(パターン選択)
    function fncSelectPattern($strPattern_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.DENPY_KB" . "\r\n";
        $strSQL .= ",      SWK.PATTERN_NO" . "\r\n";
        $strSQL .= ",      SWK.PATTERN_NM" . "\r\n";
        $strSQL .= ",      SWK.TAISYO_BUSYO_KB" . "\r\n";
        $strSQL .= ",      SWK.TAISYO_BUSYO_CD" . "\r\n";
        $strSQL .= ",      DECODE(SWK.TORIHIKI_DT,NULL,'',SUBSTR(SWK.TORIHIKI_DT,1,4) || '/' || SUBSTR(SWK.TORIHIKI_DT,5,2) || '/' || SUBSTR(SWK.TORIHIKI_DT,7,2)) TORIHIKI_DT" . "\r\n";
        $strSQL .= ",      DECODE(SWK.SHIHARAI_DT,NULL,'',SUBSTR(SWK.SHIHARAI_DT,1,4) || '/' || SUBSTR(SWK.SHIHARAI_DT,5,2) || '/' || SUBSTR(SWK.SHIHARAI_DT,7,2)) SHIHARAI_DT" . "\r\n";
        $strSQL .= ",      SWK.TEKYO" . "\r\n";
        $strSQL .= ",      SWK.L_KAMOK_CD" . "\r\n";
        $strSQL .= ",      SWK.L_KOUMK_CD" . "\r\n";
        $strSQL .= ",      (CASE WHEN L_KOUMK_CD IS NULL THEN KAR.KAMOK_SSK_NM ELSE KAR.KMK_KUM_NM END) L_KAMOK_NM" . "\r\n";
        $strSQL .= ",      SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      LB.BUSYO_RYKNM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",      SWK.L_KAZEI_KB" . "\r\n";
        $strSQL .= ",      SWK.L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      SWK.L_TORHK_KB" . "\r\n";
        $strSQL .= ",      SWK.L_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",      SWK.L_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",      SWK.L_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",      SWK.L_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",      SWK.L_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",      SWK.L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",      SWK.R_KAMOK_CD" . "\r\n";
        $strSQL .= ",      SWK.SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",      SWK.R_KOUMK_CD" . "\r\n";
        $strSQL .= ",      (CASE WHEN R_KOUMK_CD IS NULL THEN KAS.KAMOK_SSK_NM ELSE KAS.KMK_KUM_NM END) R_KAMOK_NM" . "\r\n";
        $strSQL .= ",      SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      RB.BUSYO_RYKNM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",      SWK.R_KAZEI_KB" . "\r\n";
        $strSQL .= ",      SWK.R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      SWK.R_TORHK_KB" . "\r\n";
        $strSQL .= ",      SWK.R_KOUZA_KEY1" . "\r\n";
        $strSQL .= ",      SWK.R_KOUZA_KEY2" . "\r\n";
        $strSQL .= ",      SWK.R_KOUZA_KEY3" . "\r\n";
        $strSQL .= ",      SWK.R_KOUZA_KEY4" . "\r\n";
        $strSQL .= ",      SWK.R_KOUZA_KEY5" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= ",      SWK.R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= ",      SWK.SEIKYUSYO_NO" . "\r\n";
        $strSQL .= ",      SWK.SHIHARAISAKI_CD" . "\r\n";
        $strSQL .= ",      SWK.SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",      SWK.GINKO_KB" . "\r\n";
        $strSQL .= ",      SWK.GINKO_NM" . "\r\n";
        $strSQL .= ",      SWK.SHITEN_NM" . "\r\n";
        $strSQL .= ",      SWK.YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",      SWK.KOUZA_NO" . "\r\n";
        $strSQL .= ",      SWK.KOUZA_KN" . "\r\n";
        $strSQL .= ",      SWK.JIKI" . "\r\n";
        // 20240418 lqs INS S
        $strSQL .= ",      SWK.AITESAKI_KB" . "\r\n";
        $strSQL .= ",      SWK.OKYAKU_TORIHIKI_NO" . "\r\n";
        $strSQL .= ",      SWK.JIGYOSYA_NM" . "\r\n";
        $strSQL .= ",      SWK.INVOICE_ENTRYNO" . "\r\n";
        $strSQL .= ",      SWK.TOKUREI_KB" . "\r\n";
        // 20240418 lqs INS E
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA SWK" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ6 KAR" . "\r\n";
        $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.KOUMK_CD),'999999'),KAR.KOUMK_CD) = NVL(SWK.L_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN M29FZ6 KAS" . "\r\n";
        $strSQL .= "ON     KAS.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.KOUMK_CD),'999999'),KAS.KOUMK_CD) = NVL(SWK.R_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO LB" . "\r\n";
        $strSQL .= "ON     LB.BUSYO_CD = SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO RB" . "\r\n";
        $strSQL .= "ON     RB.BUSYO_CD = SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "WHERE  PATTERN_NO = '@PATTERN_NO'" . "\r\n";
        $strSQL .= "AND    SWK.DENPY_KB = '2'" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", $strPattern_No, $strSQL);
        return parent::select($strSQL);
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
        $this->ClsComFncHMDPS = new ClsComFncHMDPS();
        //採番ﾃｰﾌﾞﾙに既に同一年月のものがあればUPDATE、なければINSERT
        if ($objDr['row'] > 0) {
            $strSQL = "";
            $strSQL .= "UPDATE HDPSAIBAN" . "\r\n";
            $strSQL .= "   SET SEQNO = " . $this->ClsComFncHMDPS->FncSqlNz($objDr['data'][0]["BANGO"]) . "\r\n";
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


    //SQL(パターン削除用)
    function fncPatternDelete($strPattern_NO)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= "WHERE  PATTERN_NO = '@PATTERN_NO'" . "\r\n";
        $strSQL .= "AND    DENPY_KB   = 2" . "\r\n";
        $strSQL = str_replace("@PATTERN_NO", $strPattern_NO, $strSQL);
        return parent::delete($strSQL);
    }

    function subWhereSet($postData, $strSQL, $strProgramID = 'ShiharaiInput', $strKbn = '')
    {
        $this->ClsComFncHMDPS = new ClsComFncHMDPS();
        $strSQL = str_replace("@ZEIKM_GK", $this->ClsComFncHMDPS->FncNv($postData['txtZeikm_GK']), $strSQL);
        $strSQL = str_replace("@ZEINK_GK", $this->ClsComFncHMDPS->FncNv($postData['lblZeink_GK']), $strSQL);
        $strSQL = str_replace("@SHZEI_GK", $this->ClsComFncHMDPS->FncNv($postData['lblSyohizei']), $strSQL);
        $strSQL = str_replace("@TEKYO", $this->ClsComFncHMDPS->FncNv($postData['txtTekyo']), $strSQL);
        $strSQL = str_replace("@L_KAMOK_CD", $this->ClsComFncHMDPS->FncNv($postData['txtLKamokuCD']), $strSQL);
        $strSQL = str_replace("@L_KOUMK_CD", $this->ClsComFncHMDPS->FncNv($postData['txtLKomokuCD']), $strSQL);
        $strSQL = str_replace("@L_HASEI_KYOTN_CD", $this->ClsComFncHMDPS->FncNv($postData['txtLBusyoCD']), $strSQL);
        $strLKazeiKB = $postData['ddlLSyohizeiKbn'];
        if ($this->ClsComFncHMDPS->FncNv($strLKazeiKB) <> "") {
            $strSQL = str_replace("@L_KAZEI_KB", substr($strLKazeiKB, 0, 1), $strSQL);
            $strSQL = str_replace("@L_ZEI_RT_KB", substr($strLKazeiKB, 1, 1) == "0" ? "NULL" : "'" . substr($strLKazeiKB, 1, 1) . "'", $strSQL);
        } else {
            $strSQL = str_replace("@L_KAZEI_KB", "''", $strSQL);
            $strSQL = str_replace("@L_ZEI_RT_KB", "NULL", $strSQL);
        }

        if ($postData['ddlLTorihikiKbn'] <> "") {
            $strSQL = str_replace("@L_TORHK_KB", "'" . $postData['ddlLTorihikiKbn'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@L_TORHK_KB", "NULL", $strSQL);
        }
        $strSQL = str_replace("@L_KOUZA_KEY1", $this->ClsComFncHMDPS->FncNv($postData['txtLKouzaKey1']), $strSQL);
        $strSQL = str_replace("@L_KOUZA_KEY2", $this->ClsComFncHMDPS->FncNv($postData['txtLKouzaKey2']), $strSQL);
        $strSQL = str_replace("@L_KOUZA_KEY3", $this->ClsComFncHMDPS->FncNv($postData['txtLKouzaKey3']), $strSQL);
        $strSQL = str_replace("@L_KOUZA_KEY4", $this->ClsComFncHMDPS->FncNv($postData['txtLKouzaKey4']), $strSQL);
        $strSQL = str_replace("@L_KOUZA_KEY5", $this->ClsComFncHMDPS->FncNv($postData['txtLKouzaKey5']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYO1", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo1']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYO2", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo2']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYO3", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo3']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYO4", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo4']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYO5", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo5']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYO6", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo6']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYO7", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo7']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYO8", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo8']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYO9", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo9']), $strSQL);
        $strSQL = str_replace("@L_HISSU_TEKYOA", $this->ClsComFncHMDPS->FncNv($postData['txtLHissuTekyo10']), $strSQL);
        $strSQL = str_replace("@R_KAMOK_CD", $this->ClsComFncHMDPS->FncNv(substr(str_pad($postData['ddlRKamokuCD'], 6), 1)), $strSQL);
        $strSQL = str_replace("@SHR_KAMOK_KB", $this->ClsComFncHMDPS->FncNv(substr(str_pad($postData['ddlRKamokuCD'], 6), 0, 1)), $strSQL);

        if ($postData['ddlRKamokuCD'] <> "") {
            $strSQL = str_replace("@R_KOUMK_CD", $this->ClsComFncHMDPS->FncNv($postData['ddlRKomokuCD']), $strSQL);
        } else {
            $strSQL = str_replace("@R_KOUMK_CD", "", $strSQL);
        }
        $strSQL = str_replace("@R_HASEI_KYOTN_CD", $this->ClsComFncHMDPS->FncNv($postData['txtRbusyoCD']), $strSQL);

        if ($this->ClsComFncHMDPS->FncNv($postData['ddlRSyohizeiKbn']) <> "") {
            $strSQL = str_replace("@R_KAZEI_KB", substr($postData['ddlRSyohizeiKbn'], 0, 1), $strSQL);
            $strSQL = str_replace("@R_ZEI_RT_KB", substr($postData['ddlRSyohizeiKbn'], 1, 1) == "0" ? "NULL" : "'" . substr($postData['ddlRSyohizeiKbn'], 1, 1) . "'", $strSQL);
        } else {
            $strSQL = str_replace("@R_KAZEI_KB", "''", $strSQL);
            $strSQL = str_replace("@R_ZEI_RT_KB", "NULL", $strSQL);
        }

        if ($postData['ddlRTorihikiKbn'] <> "") {
            $strSQL = str_replace("@R_TORHK_KB", "'" . $postData['ddlRTorihikiKbn'] . "'", $strSQL);
        } else {
            $strSQL = str_replace("@R_TORHK_KB", "NULL", $strSQL);
        }

        $strSQL = str_replace("@R_KOUZA_KEY1", $this->ClsComFncHMDPS->FncNv($postData['txtRKouzaKey1']), $strSQL);
        $strSQL = str_replace("@R_KOUZA_KEY2", $this->ClsComFncHMDPS->FncNv($postData['txtRKouzaKey2']), $strSQL);
        $strSQL = str_replace("@R_KOUZA_KEY3", $this->ClsComFncHMDPS->FncNv($postData['txtRKouzaKey3']), $strSQL);
        $strSQL = str_replace("@R_KOUZA_KEY4", $this->ClsComFncHMDPS->FncNv($postData['txtRKouzaKey4']), $strSQL);
        $strSQL = str_replace("@R_KOUZA_KEY5", $this->ClsComFncHMDPS->FncNv($postData['txtRKouzaKey5']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYO1", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo1']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYO2", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo2']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYO3", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo3']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYO4", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo4']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYO5", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo5']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYO6", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo6']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYO7", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo7']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYO8", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo8']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYO9", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo9']), $strSQL);
        $strSQL = str_replace("@R_HISSU_TEKYOA", $this->ClsComFncHMDPS->FncNv($postData['txtRHissuTekyo10']), $strSQL);

        if ($strKbn == "PATTERN") {
            //パターン登録の場合は画面に表示されている値を全て登録するように修正
            // 請求書№
            $strSQL = str_replace("@SEIKYUSYO_NO", $this->ClsComFncHMDPS->FncNv($postData['txtSeikyusyoNO']), $strSQL);
            // 取引発生日
            $strSQL = str_replace("@TORIHIKI_DT", $this->ClsComFncHMDPS->FncNv(str_replace("/", "", $postData['txtTorihikiHasseibi'])), $strSQL);
            if ($postData['pnlShiharaiCDVis'] == '1') {
                // 支払先コード
                $strSQL = str_replace("@SHIHARAISAKI_CD", $this->ClsComFncHMDPS->FncNv($postData['txtShiharaisakiCD']), $strSQL);
                // 支払先名
                $strSQL = str_replace("@SHIHARAISAKI_NM", $this->ClsComFncHMDPS->FncNv($postData['lblShiharaisakiNM']), $strSQL);
            } else {
                // 支払先コード
                $strSQL = str_replace("@SHIHARAISAKI_CD", "", $strSQL);
                // 支払先名
                $strSQL = str_replace("@SHIHARAISAKI_NM", $this->ClsComFncHMDPS->FncNv($postData['txtShiharaisaki']), $strSQL);
            }
            if ($postData['grpGinko'] == 'radHiroGinko') {
                // （GD）銀行
                //振込先銀行区分
                $strSQL = str_replace("@GINKO_KB", "1", $strSQL);
                //振込先銀行名
                $strSQL = str_replace("@GINKO_NM", "", $strSQL);
                //振込先支店名
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHMDPS->FncNv($postData['txtSonotaShiten']), $strSQL);
            } elseif ($postData['grpGinko'] == 'radMomijiGinko') {
                //もみじ銀行
                //振込先銀行区分
                $strSQL = str_replace("@GINKO_KB", "2", $strSQL);
                //振込先銀行名
                $strSQL = str_replace("@GINKO_NM", "", $strSQL);
                //振込先支店名
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHMDPS->FncNv($postData['txtSonotaShiten']), $strSQL);
            } elseif ($postData['grpGinko'] == 'radShinyoKinko') {
                //（GD）信用金庫
                //振込先銀行区分
                $strSQL = str_replace("@GINKO_KB", "3", $strSQL);
                //振込先銀行名
                $strSQL = str_replace("@GINKO_NM", "", $strSQL);
                //振込先支店名
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHMDPS->FncNv($postData['txtSonotaShiten']), $strSQL);
            } else {
                //その他銀行
                //振込先銀行区分
                $strSQL = str_replace("@GINKO_KB", "9", $strSQL);
                //振込先銀行名
                $strSQL = str_replace("@GINKO_NM", $this->ClsComFncHMDPS->FncNv($postData['txtSonotaGinko']), $strSQL);
                //振込先支店名
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHMDPS->FncNv($postData['txtSonotaShiten']), $strSQL);
            }
            //振込先預金種別
            if ($postData['grpSyubetu'] == "radSyubetuFutu") {
                $strSQL = str_replace("@YOKIN_SYUBETU", "1", $strSQL);
            } elseif ($postData['grpSyubetu'] == "radSyubetuTouza") {
                $strSQL = str_replace("@YOKIN_SYUBETU", "2", $strSQL);
            } else {
                $strSQL = str_replace("@YOKIN_SYUBETU", "9", $strSQL);
            }
            //振込先口座番号
            $strSQL = str_replace("@KOUZA_NO", $postData['txtKouzaNO'], $strSQL);
            //振込先口座名(ｶﾅ)
            $strSQL = str_replace("@KOUZA_KN", $postData['txtKouzaNM'], $strSQL);
            if ($postData['grpJiki'] == "radJikiSokujitu") {
                //振込時期
                $strSQL = str_replace("@JIKI", "1", $strSQL);
                //支払予定日
                $strSQL = str_replace("@SHIHARAI_DT", "", $strSQL);
            } elseif ($postData['grpJiki'] == "radJikiHiduke") {
                //振込時期
                $strSQL = str_replace("@JIKI", "2", $strSQL);
                //支払予定日
                $strSQL = str_replace("@SHIHARAI_DT", str_replace("/", "", $postData['txtJikiDate']), $strSQL);
            } else {
                //振込時期
                $strSQL = str_replace("@JIKI", "3", $strSQL);
                //支払予定日
                $strSQL = str_replace("@SHIHARAI_DT", "", $strSQL);
            }

        } else {
            //支払伝票登録の場合は今まで通り活性項目のみ登録する
            if ($postData['txtSeikyusyoNOEna'] == "1") {
                //請求書№
                $strSQL = str_replace("@SEIKYUSYO_NO", $this->ClsComFncHMDPS->FncNv($postData['txtSeikyusyoNO']), $strSQL);
            } else {
                $strSQL = str_replace("@SEIKYUSYO_NO", "", $strSQL);
            }
            if ($postData['txtTorihikiHasseibiEna'] == "1") {
                //取引発生日
                $strSQL = str_replace("@TORIHIKI_DT", $this->ClsComFncHMDPS->FncNv(str_replace("/", "", $postData['txtTorihikiHasseibi'])), $strSQL);
            } else {
                $strSQL = str_replace("@TORIHIKI_DT", "", $strSQL);
            }
            if ($postData['pnlShiharaiCDVis'] == "1") {
                //支払先コード
                $strSQL = str_replace("@SHIHARAISAKI_CD", $this->ClsComFncHMDPS->FncNv($postData['txtShiharaisakiCD']), $strSQL);
                //支払先名
                $strSQL = str_replace("@SHIHARAISAKI_NM", $this->ClsComFncHMDPS->FncNv($postData['lblShiharaisakiNM']), $strSQL);
            } else {
                //支払先コード
                $strSQL = str_replace("@SHIHARAISAKI_CD", "", $strSQL);
                //支払先名
                $strSQL = str_replace("@SHIHARAISAKI_NM", $this->ClsComFncHMDPS->FncNv($postData['txtShiharaisaki']), $strSQL);
            }
            //振込先銀行区分
            if ($postData['radHiroGinkoEna'] == "1") {
                if ($postData['grpGinko'] == "radHiroGinko") {
                    $strSQL = str_replace("@GINKO_KB", "1", $strSQL);
                } elseif ($postData['grpGinko'] == "radMomijiGinko") {
                    $strSQL = str_replace("@GINKO_KB", "2", $strSQL);
                } elseif ($postData['grpGinko'] == "radShinyoKinko") {
                    $strSQL = str_replace("@GINKO_KB", "3", $strSQL);
                } else {
                    $strSQL = str_replace("@GINKO_KB", "9", $strSQL);
                }
            } else {
                $strSQL = str_replace("@GINKO_KB", "", $strSQL);
            }
            //振込先銀行名
            if ($postData['txtSonotaGinkoEna'] == "1") {
                $strSQL = str_replace("@GINKO_NM", $postData['grpGinko'] == "radGinkoSonota" ? $this->ClsComFncHMDPS->FncNv($postData['txtSonotaGinko']) : "", $strSQL);
            } else {
                $strSQL = str_replace("@GINKO_NM", "", $strSQL);
            }
            //振込先支店名
            if ($postData['txtSonotaShitenEna'] == "1") {
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHMDPS->FncNv($postData['txtSonotaShiten']), $strSQL);
            } else {
                $strSQL = str_replace("@SHITEN_NM", "", $strSQL);
            }

            if ($postData['radSyubetuTouzaEna']) {
                if ($postData['grpSyubetu'] == "radSyubetuFutu") {
                    //振込先預金種別
                    $strSQL = str_replace("@YOKIN_SYUBETU", "1", $strSQL);
                } elseif ($postData['grpSyubetu'] == "radSyubetuTouza") {
                    $strSQL = str_replace("@YOKIN_SYUBETU", "2", $strSQL);
                } else {
                    $strSQL = str_replace("@YOKIN_SYUBETU", "9", $strSQL);
                }
            } else {
                $strSQL = str_replace("@YOKIN_SYUBETU", "", $strSQL);
            }
            //振込先口座番号
            if ($postData['txtKouzaNOEna'] == "1") {
                $strSQL = str_replace("@KOUZA_NO", $postData['txtKouzaNO'], $strSQL);
            } else {
                $strSQL = str_replace("@KOUZA_NO", "", $strSQL);
            }
            //振込先口座名(ｶﾅ)
            if ($postData['txtKouzaNMEna'] == "1") {
                $strSQL = str_replace("@KOUZA_KN", $postData['txtKouzaNM'], $strSQL);
            } else {
                $strSQL = str_replace("@KOUZA_KN", "", $strSQL);
            }

            if ($postData['grpJiki'] == "radJikiSokujitu") {
                ////振込時期
                $strSQL = str_replace("@JIKI", "1", $strSQL);
                //支払予定日
                if ($strProgramID == "ShiharaiInput") {
                    $strSQL = str_replace("@SHIHARAI_DT", str_replace("/", "", $postData['txtJikiDate']), $strSQL);
                } else {
                    $strSQL = str_replace("@SHIHARAI_DT", "", $strSQL);
                }
            } elseif ($postData['grpJiki'] == "radJikiHiduke") {
                $strSQL = str_replace("@JIKI", "2", $strSQL);
                $strSQL = str_replace("@SHIHARAI_DT", str_replace("/", "", $postData['txtJikiDate']), $strSQL);
            } else {
                $strSQL = str_replace("@JIKI", "3", $strSQL);
                if ($strProgramID == "ShiharaiInput") {
                    $strSQL = str_replace("@SHIHARAI_DT", str_replace("/", "", $postData['txtJikiDate']), $strSQL);
                } else {
                    $strSQL = str_replace("@SHIHARAI_DT", "", $strSQL);
                }
            }
        }
        $this->SessionComponent = Router::getRequest()->getSession();

        $strSQL = str_replace("@FUKANZEN_FLG", $postData['fncFukanzenCheck'], $strSQL);
        $strSQL = str_replace("@CRE_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $strProgramID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", $strProgramID, $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        //20240418 lqs INS S
        $strSQL = str_replace("@AITESAKI_KB", $this->ClsComFncHMDPS->FncNv($postData["ddlAitesakiKBN"]), $strSQL);
        $strSQL = str_replace("@OKYAKU_TORIHIKI_NO", $this->ClsComFncHMDPS->FncNv($postData["txtOkyakusamaNOTorihikisakiNm"]), $strSQL);
        $strSQL = str_replace("@JIGYOSYA_NM", $this->ClsComFncHMDPS->FncNv($postData["txtTorokuNoKazeiMenzeiGyosya"]), $strSQL);
        $strSQL = str_replace("@INVOICE_ENTRYNO", $this->ClsComFncHMDPS->FncNv($postData["txtJigyosyoMeiTorokuNo"]), $strSQL);
        $strSQL = str_replace("@TOKUREI_KB", $this->ClsComFncHMDPS->FncNv($postData["ddlTokureiKBN"]), $strSQL);
        //20240418 lqs INS E

        return $strSQL;
    }

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
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= ",      'DENPYO_SEARCH_PRINT'" . "\r\n";
        $strSQL .= ",      '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", substr($strSyohy_NO, 0, 15), $strSQL);
        $strSQL = str_replace("@EDA_NO", substr($strSyohy_NO, 15, 2), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

    //ワーク証憑№のデータを全件削除する
    function fncAllDelSQL()
    {
        $strSQL = "DELETE FROM WK_SYOHY_NO WHERE CRE_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "'  AND CRE_PRG_ID = 'DENPYO_SEARCH_PRINT' AND CRE_CLT_NM =  '" . $this->GS_LOGINUSER['strClientNM'] . "'";

        return parent::delete($strSQL);
    }

    //20240418 lqs INS S
    function FncGetNameValue($postData)
    {
        $strSQL = "";
        if ($postData['ddlAitesakiKBN'] == "1") {
            $strSQL .= "SELECT CSRNM1 || CSRNM2 as NM" . "\r\n";
            $strSQL .= "FROM   M41C01 " . "\r\n";
            $strSQL .= "WHERE  DLRCSRNO = '@NO'" . "\r\n";
        } else if ($postData['ddlAitesakiKBN'] == "2") {
            $strSQL .= "SELECT ATO_DTRPITNM1 as NM" . "\r\n";
            $strSQL .= "FROM   M28M68 " . "\r\n";
            $strSQL .= "WHERE  ATO_DTRPITCD = '@NO'" . "\r\n";
        } else {
            return false;
        }
        $strSQL = str_replace("@NO", $postData['txtOkyakusamaNOTorihikisakiNm'], $strSQL);

        return parent::select($strSQL);
    }
    //20240418 lqs INS E

}
