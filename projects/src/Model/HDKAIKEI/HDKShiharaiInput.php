<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20231213           #支払伝票入力		          伝票をコピー作成した、作成者、作成部署修正           	 caina
 * 20240129           #支払伝票入力		     全確定を実行して 枝番が＋１、元データの作成者をセットする     YIN
 * 20240227           20240213_機能改善要望対応 NO6    「科目マスタの使用フラグ、使用フラグ名は撤廃」    YIN
 * 20240322           本番障害.xlsx NO8            科目名、補助科目名を両方表示してほしい              YIN
 * 20240408           本番保守.xlsx NO11           貸方科目ブルダウンに 「未払金給与（社員立替）」を追加  LQS
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
use App\Model\HDKAIKEI\Component\ClsComFncHDKAIKEI;
use Cake\Routing\Router;

class HDKShiharaiInput extends ClsComDb
{
    public $ClsComFncHDKAIKEI;
    public $SessionComponent;
    function fncSelShiharaForIchiran($strSyohy_No, $strEda_No)
    {
        $strSQL = "";
        $strSQL .= "SELECT ROW_NUMBER() OVER(ORDER BY SYOHY_NO, EDA_NO, GYO_NO) SEQNO" . "\r\n";
        $strSQL .= ",      SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",      SWK.EDA_NO" . "\r\n";
        $strSQL .= ",      SWK.GYO_NO" . "\r\n";
        // 20240322 YIN UPD S
        // $strSQL .= ",      (CASE WHEN KAR.SUB_KAMOK_NAME IS NULL THEN KAR.KAMOK_NAME ELSE KAR.SUB_KAMOK_NAME END) L_KAMOKU" . "\r\n";
        // $strSQL .= ",      (CASE WHEN KAS.MOJI1 IS NULL THEN KAS.MEISYOU ELSE KAS.MOJI1 END) R_KAMOKU" . "\r\n";
        $strSQL .= ",      KAR.KAMOK_NAME L_KAMOKU" . "\r\n";
        $strSQL .= ",      KAR.SUB_KAMOK_NAME L_KOUMKU" . "\r\n";
        $strSQL .= ",      KAS.MEISYOU  R_KAMOKU" . "\r\n";
        $strSQL .= ",      KAS.MOJI1 R_KOUMKU" . "\r\n";
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
        $strSQL .= "LEFT JOIN HMEISYOUMST KAS" . "\r\n";
        $strSQL .= "ON	TO_NUMBER(SUBSTR(KAS.MEISYOU_CD,1,1) || KAS.SUCHI1) = TO_NUMBER(SWK.SHR_KAMOK_KB || SWK.R_KAMOK_CD) AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUCHI2),'999999'),KAS.SUCHI2) = NVL(SWK.R_KOUMK_CD,'999999') AND KAS.MEISYOU_ID = 'DK'" . "\r\n";
        $strSQL .= "WHERE  SWK.SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    SWK.EDA_NO   = '@EDA_NO'" . "\r\n";
        $strSQL .= "AND    SWK.DENPY_KB = '2'" . "\r\n";
        $strSQL .= "AND    SWK.DEL_FLG = '0'" . "\r\n";
        $strSQL .= "ORDER BY SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",        SWK.EDA_NO" . "\r\n";
        $strSQL .= ",        SWK.GYO_NO";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEda_No, $strSQL);

        return parent::select($strSQL);
    }

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
        $strSQL .= "WHERE MEISYOU_ID='DK' " . "\r\n";
        $strSQL .= "ORDER BY TO_NUMBER(MEI.MEISYOU_CD) " . "\r\n";

        return parent::select($strSQL);
    }

    function fncSelMeisyorituForDdl($strMeisyou)
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

    function fncSelMeisyoKBNForDdl()
    {
        $strSQL = "";
        $strSQL .= "SELECT TAX_KBN_CD" . "\r\n";
        $strSQL .= ",   (CASE WHEN NICKNAME IS NULL THEN TAX_KBN_NAME ELSE NICKNAME END) TAX_KBN_NAME" . "\r\n";
        $strSQL .= "FROM   HDK_MST_SHZKBN" . "\r\n";
        $strSQL .= "WHERE  DEL_FLG = '0'" . "\r\n";
        $strSQL .= " AND DISP_CD = '1'" . "\r\n";
        $strSQL .= " ORDER BY TAX_KBN_CD" . "\r\n";

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
        $strSQL .= ",	  MAX(XLSX_OUT_FLG)   XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",	  MAX(DEL_FLG)	   DEL_FLG" . "\r\n";
        $strSQL .= ",	  MAX(CRE_BUSYO_CD)  CRE_BUSYO_CD" . "\r\n";
        // 20240129 YIN INS S
        $strSQL .= ",   MAX(CRE_SYA_CD) CRE_SYA_CD" . "\r\n";
        $strSQL .= ",   MAX(CRE_CLT_NM) CRE_CLT_NM" . "\r\n";
        $strSQL .= ",   MAX(CRE_PRG_ID) CRE_PRG_ID" . "\r\n";
        // 20240129 YIN INS E
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
        $strSQL .= ",	  MAX(XLSX_OUT_FLG)   XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",	  MAX(HONBU_SYORIZUMI_FLG) HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "GROUP BY SYOHY_NO" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_NO, $strSQL);

        return parent::select($strSQL);
    }

    function FncGetLKamokuMst($strKamoku, $strKomoku)
    {
        $strSQL = "";
        $strSQL .= "SELECT KAMOK_NAME, SUB_KAMOK_NAME, KARI_TAX_KBN" . "\r\n";
        $strSQL .= "FROM HDK_MST_KAMOKU" . "\r\n";
        $strSQL .= "WHERE  KAMOK_CD = '@KAMOK_CD'" . "\r\n";
        $strSQL .= "AND  SUB_KAMOK_CD = '@SUB_KAMOK_CD'" . "\r\n";
        // 20240227 YIN DEL S
        // $strSQL .= "AND USE_FLG='1'" . "\r\n";
        // 20240227 YIN DEL E

        $strSQL = str_replace("@KAMOK_CD", $strKamoku, $strSQL);
        $strSQL = str_replace("@SUB_KAMOK_CD", $strKomoku, $strSQL);

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
        $strSQL .= ",	  (CASE WHEN KAR.KAMOK_NAME IS NULL THEN '' ELSE KAR.KAMOK_NAME END) L_KAMOK_NM" . "\r\n";
        $strSQL .= ",	  (CASE WHEN KAR.SUB_KAMOK_NAME IS NULL THEN '' ELSE KAR.SUB_KAMOK_NAME END) L_KOMOK_NM" . "\r\n";
        $strSQL .= ",	  SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	  LB.BUSYO_NM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",	  SWK.L_KAZEI_KB" . "\r\n";
        $strSQL .= ",	  SWK.L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_KAMOK_CD" . "\r\n";
        // 20240408 LQS INS S
        $strSQL .= ",	  SWK.TATEKAE_SYA_CD" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",	  SWK.SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUMK_CD" . "\r\n";
        $strSQL .= ",      (CASE WHEN KAS.MOJI1 IS NULL THEN KAS.MEISYOU ELSE KAS.MOJI1 END) R_KAMOK_NM" . "\r\n";
        $strSQL .= ",	  SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	  RB.BUSYO_NM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",	  SWK.R_KAZEI_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	  SWK.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",	  SWK.TORIHIKISAKI_NAME" . "\r\n";
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
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA SWK" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAR" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON	 KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') AND KAR.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON	 KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HMEISYOUMST KAS" . "\r\n";
        $strSQL .= "ON	TO_NUMBER(SUBSTR(KAS.MEISYOU_CD,1,1) || KAS.SUCHI1) = TO_NUMBER(SWK.SHR_KAMOK_KB || SWK.R_KAMOK_CD) AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUCHI2),'999999'),KAS.SUCHI2) = NVL(SWK.R_KOUMK_CD,'999999') AND KAS.MEISYOU_ID = 'DK'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON LB" . "\r\n";
        $strSQL .= "ON	 LB.BUSYO_CD = SWK.L_HASEI_KYOTN_CD AND LB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON RB" . "\r\n";
        $strSQL .= "ON	 RB.BUSYO_CD = SWK.R_HASEI_KYOTN_CD AND RB.USE_FLG='1'" . "\r\n";
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
        $strSQL .= ",	  (CASE WHEN KAR.KAMOK_NAME IS NULL THEN '' ELSE KAR.KAMOK_NAME END) L_KAMOK_NM" . "\r\n";
        $strSQL .= ",	  (CASE WHEN KAR.SUB_KAMOK_NAME IS NULL THEN '' ELSE KAR.SUB_KAMOK_NAME END) L_KOMOK_NM" . "\r\n";
        $strSQL .= ",	  SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	  LB.BUSYO_NM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",	  SWK.L_KAZEI_KB" . "\r\n";
        $strSQL .= ",	  SWK.L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_KAMOK_CD" . "\r\n";
        // 20240408 LQS INS S
        $strSQL .= ",	  SWK.TATEKAE_SYA_CD" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",	  SWK.SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_KOUMK_CD" . "\r\n";
        $strSQL .= ",	  (CASE WHEN KAS.MOJI1 IS NULL THEN KAS.MEISYOU ELSE KAS.MOJI1 END) R_KAMOK_NM" . "\r\n";
        $strSQL .= ",	  SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	  RB.BUSYO_NM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",	  SWK.R_KAZEI_KB" . "\r\n";
        $strSQL .= ",	  SWK.R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	  SWK.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",	  SWK.TORIHIKISAKI_NAME" . "\r\n";
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
        $strSQL .= ",	  SWK.XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",	  SWK.CSV_GROUP_NO" . "\r\n";
        $strSQL .= ",	  SWK.XLSX_GROUP_NO" . "\r\n";
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
        $strSQL .= ",	  AT.SYOHY_NO AS FILEFLG" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAR" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON	 KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') AND KAR.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON	 KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HMEISYOUMST KAS" . "\r\n";
        $strSQL .= "ON	TO_NUMBER(SUBSTR(KAS.MEISYOU_CD,1,1) || KAS.SUCHI1) = TO_NUMBER(SWK.SHR_KAMOK_KB || SWK.R_KAMOK_CD) AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUCHI2),'999999'),KAS.SUCHI2) = NVL(SWK.R_KOUMK_CD,'999999') AND KAS.MEISYOU_ID = 'DK'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON LB" . "\r\n";
        $strSQL .= "ON	 LB.BUSYO_CD = SWK.L_HASEI_KYOTN_CD AND LB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON RB" . "\r\n";
        $strSQL .= "ON	 RB.BUSYO_CD = SWK.R_HASEI_KYOTN_CD AND RB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_ATTACHMENT AT" . "\r\n";
        $strSQL .= "ON	 SWK.SYOHY_NO = AT.SYOHY_NO AND AT.DEL_FLG = '0'" . "\r\n";
        $strSQL .= "AND	 SWK.EDA_NO = AT.EDA_NO" . "\r\n";
        $strSQL .= "AND	 SWK.GYO_NO = AT.GYO_NO" . "\r\n";
        $strSQL .= "WHERE  SWK.SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND	SWK.EDA_NO = '@EDA_NO'" . "\r\n";
        $strSQL .= "AND	SWK.GYO_NO = '@GYO_NO'" . "\r\n";
        $strSQL .= "AND	SWK.DEL_FLG = '0'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_NO, $strSQL);
        $strSQL = str_replace("@EDA_NO", $strEDa_NO, $strSQL);
        $strSQL = str_replace("@GYO_NO", $strGyo_NO, $strSQL);


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
        $strSQL .= ",	 R_KAMOK_CD" . "\r\n";
        // 20240408 LQS INS S
        $strSQL .= ",	 TATEKAE_SYA_CD" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",	 SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",	 R_KOUMK_CD" . "\r\n";
        $strSQL .= ",	 R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",	 R_KAZEI_KB" . "\r\n";
        $strSQL .= ",	 R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",	 TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",	 TORIHIKISAKI_NAME" . "\r\n";
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
        $strSQL .= ",	 XLSX_OUT_FLG" . "\r\n";
        $strSQL .= ",	 CSV_GROUP_NO" . "\r\n";
        $strSQL .= ",	 XLSX_GROUP_NO" . "\r\n";
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
        $strSQL .= ",	 '@R_KAMOK_CD'" . "\r\n";
        // 20240408 LQS INS S
        $strSQL .= ",	 '@TATEKAE_SYA_CD'" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",	 '@SHR_KAMOK_KB'" . "\r\n";
        $strSQL .= ",	 '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",	 '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",	 @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",	 @R_ZEI_RT_KB" . "\r\n";
        //取引先コード
        $strSQL .= ",	 '@TORIHIKISAKI_CD'" . "\r\n";
        //取引先名
        $strSQL .= ",	 '@TORIHIKISAKI_NAME'" . "\r\n";
        //振込先銀行区分
        $strSQL .= ",	 '@GINKO_KB'" . "\r\n";
        //振込先銀行名
        $strSQL .= ",	 '@GINKO_NM'" . "\r\n";
        //振込先支店名
        $strSQL .= ",	 '@SHITEN_NM'" . "\r\n";
        //振込先預金種別
        $strSQL .= ",	 '@YOKIN_SYUBETU'" . "\r\n";
        //振込先口座番号
        $strSQL .= ",	 '@KOUZA_NO'" . "\r\n";
        //振込先口座名(ｶﾅ)
        $strSQL .= ",	 '@KOUZA_KN'" . "\r\n";
        //振込時期
        $strSQL .= ",	 '@JIKI'" . "\r\n";
        $strSQL .= ",	 '@FUKANZEN_FLG'" . "\r\n";
        //本部処理済みフラグ '2009/03/12 INS Start FLG追加のため
        $strSQL .= ",	 '@HONBUFLG'" . "\r\n";
        //印刷フラグ
        $strSQL .= ",	 0" . "\r\n";
        //ＣＳＶ出力フラグ
        $strSQL .= ",	 0" . "\r\n";
        //XLSX出力フラグ
        $strSQL .= ",	 0" . "\r\n";
        //ＣＳＶ出力グループ名
        $strSQL .= ",	 NULL" . "\r\n";
        $strSQL .= ",	 NULL" . "\r\n";
        //削除フラグ
        $strSQL .= ",	 0" . "\r\n";
        //削除日
        $strSQL .= ",	 NULL" . "\r\n";
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

    function fncMaeShiwakeCopy($strSyohy_no, $intNewEdaNo, $strOldEdaNO, $strDate, $FLG, $newSyohyNo)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
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
        // 20240408 LQS INS S
        $strSQL .= ",     TATEKAE_SYA_CD" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",     SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_NAME" . "\r\n";
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
        if ($newSyohyNo <> '') {
            $strSQL .= "SELECT '@NEW_SYOHY_NO'" . "\r\n";
        } else {
            $strSQL .= "SELECT SYOHY_NO" . "\r\n";
        }
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
        $strSQL .= ",      R_KAMOK_CD" . "\r\n";
        // 20240408 LQS INS S
        $strSQL .= ",      TATEKAE_SYA_CD" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",      SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",      R_KOUMK_CD" . "\r\n";
        $strSQL .= ",      R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      R_KAZEI_KB" . "\r\n";
        $strSQL .= ",      R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",      TORIHIKISAKI_NAME" . "\r\n";
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
        //20231213 caina upd s
        // $strSQL .= ",      CREATE_DATE" . "\r\n";
        // $strSQL .= ",      CRE_BUSYO_CD" . "\r\n";
        // $strSQL .= ",      CRE_SYA_CD" . "\r\n";
        // $strSQL .= ",      CRE_PRG_ID" . "\r\n";
        // $strSQL .= ",      CRE_CLT_NM" . "\r\n";
        // 20240129 YIN UPD S
        if ($newSyohyNo <> '') {
            $strSQL .= ",      TO_DATE('@CREATE_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
            $strSQL .= ",      '@CRE_BUSYO_CD'" . "\r\n";
            $strSQL .= ",      '@CRE_SYA_CD'" . "\r\n";
            $strSQL .= ",      'ShiharaiInput'" . "\r\n";
            $strSQL .= ",      '@CRE_CLT_NM'" . "\r\n";
        } else {
            $strSQL .= ",      CREATE_DATE" . "\r\n";
            $strSQL .= ",      CRE_BUSYO_CD" . "\r\n";
            $strSQL .= ",      CRE_SYA_CD" . "\r\n";
            $strSQL .= ",      CRE_PRG_ID" . "\r\n";
            $strSQL .= ",      CRE_CLT_NM" . "\r\n";
        }
        // 20240129 YIN UPD E
        //20231213 caina upd e
        $strSQL .= ",      TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",      '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",      '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",      'ShiharaiInput'" . "\r\n";
        $strSQL .= ",      '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ",     CSV_OUT_ORDER" . "\r\n";
        $strSQL .= ",     XLSX_OUT_ORDER" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@EDA_NO'" . "\r\n";

        if ($newSyohyNo <> '') {
            $strSQL = str_replace("@NEW_SYOHY_NO", $newSyohyNo, $strSQL);
        }
        $strSQL = str_replace("@NEW_EDA_NO", str_pad($intNewEdaNo, 2, "0", STR_PAD_LEFT), $strSQL);
        //20231213 caina ins s
        $strSQL = str_replace("@CREATE_DATE", $strDate, $strSQL);
        $strSQL = str_replace("@CRE_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        //20231213 caina ins e
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
        $strSQL .= ",		R_KAMOK_CD = '@R_KAMOK_CD'" . "\r\n";
        // 20240408 LQS INS S
        $strSQL .= ",		TATEKAE_SYA_CD = '@TATEKAE_SYA_CD'" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",		SHR_KAMOK_KB = '@SHR_KAMOK_KB'" . "\r\n";
        $strSQL .= ",		R_KOUMK_CD = '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",		R_HASEI_KYOTN_CD = '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",		R_KAZEI_KB = @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",		R_ZEI_RT_KB = @R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",		TORIHIKISAKI_CD = '@TORIHIKISAKI_CD'" . "\r\n";
        $strSQL .= ",		TORIHIKISAKI_NAME = '@TORIHIKISAKI_NAME'" . "\r\n";
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

    function fncLastDelAllUpd($strSyohy_No, $strEdaNO, $strSysdate, $PatternID)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "SET    DEL_FLG = '1'" . "\r\n";
        $strSQL .= ",      DEL_DATE = TO_CHAR(TO_DATE('@DEL_DATE','YYYY/MM/DD HH24:MI:SS'),'YYYYMMDD')" . "\r\n";
        if ($PatternID == '1') {
            $strSQL .= ",      HONBU_SYORIZUMI_FLG = '1'" . "\r\n";
        }
        $strSQL .= ",		UPD_DATE = TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",		UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",		UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",		UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",		UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";

        $strSQL .= ",		DEL_SYA_CD = '@DEL_SYA_CD'" . "\r\n";
        $strSQL .= ",		DEL_PRG_ID = '@DEL_PRG_ID'" . "\r\n";
        $strSQL .= ",		DEL_CLT_NM = '@DEL_CLT_NM'" . "\r\n";

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
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "ShiharaInput", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@DEL_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@DEL_PRG_ID", "ShiharaInput", $strSQL);
        $strSQL = str_replace("@DEL_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    //SQL(全削除処理用)
    function fncAllDeleteUpd($strSyohy_No, $strSysdate, $FLG)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
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

        $strSQL .= ",		DEL_SYA_CD = '@DEL_SYA_CD'" . "\r\n";
        $strSQL .= ",		DEL_PRG_ID = '@DEL_PRG_ID'" . "\r\n";
        $strSQL .= ",		DEL_CLT_NM = '@DEL_CLT_NM'" . "\r\n";

        $strSQL .= "WHERE  SYOHY_NO = '@SYOHY_NO'" . "\r\n";

        $strSQL = str_replace("@SYOHY_NO", $strSyohy_No, $strSQL);
        $strSQL = str_replace("@DEL_DATE", $strSysdate, $strSQL);

        $strSQL = str_replace("@UPD_DATE", $strSysdate, $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "ShiharaInput", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@DEL_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@DEL_PRG_ID", "ShiharaInput", $strSQL);
        $strSQL = str_replace("@DEL_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

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

    function fileDelete($SYOHY_NO, $EDA_NO, $GYO_NO, $DT)
    {
        $strSQL = "UPDATE HDK_ATTACHMENT " . "\r\n";
        $strSQL .= " SET " . "\r\n";
        $strSQL .= " DEL_FLG = 1 " . "\r\n";
        $strSQL .= " ,DEL_DATE=TO_DATE('@SYSDT','YYYY/MM/DD HH24:MI:SS') " . "\r\n";
        $strSQL .= " ,DEL_SYA_CD='@USER_ID'  " . "\r\n";
        $strSQL .= " ,DEL_PRG_ID='@PRG_ID' " . "\r\n";
        $strSQL .= " ,DEL_CLT_NM='@CLT_NM' " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "       SYOHY_NO = '@SYOHY_NO' " . "\r\n";
        if ($EDA_NO != '') {
            $strSQL .= " AND   EDA_NO = '@EDA_NO' " . "\r\n";
            $strSQL = str_replace("@EDA_NO", $EDA_NO, $strSQL);
        }
        if ($GYO_NO != '') {
            $strSQL .= " AND   GYO_NO = @GYO_NO " . "\r\n";
            $strSQL = str_replace("@GYO_NO", $GYO_NO, $strSQL);
        }


        $strSQL = str_replace("@SYSDT", $DT, $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $SYOHY_NO, $strSQL);
        $strSQL = str_replace("@USER_ID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", 'ShiharaInput', $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    //SQL(パターン登録用)
    function fncPatternTrkDispShiwake($postData)
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
        // 20240408 LQS INS S
        $strSQL .= ",     TATEKAE_SYA_CD" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",     SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",     R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_NAME" . "\r\n";
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
        $strSQL .= ",     '@R_KAMOK_CD'" . "\r\n";
        // 20240408 LQS INS S
        $strSQL .= ",     '@TATEKAE_SYA_CD'" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",     '@SHR_KAMOK_KB'" . "\r\n";
        $strSQL .= ",     '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     @R_ZEI_RT_KB" . "\r\n";
        //'取引先コード
        $strSQL .= ",     '@TORIHIKISAKI_CD'" . "\r\n";
        //'取引先名
        $strSQL .= ",     '@TORIHIKISAKI_NAME'" . "\r\n";
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
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", "(SELECT NVL(MAX(PATTERN_NO),0) + 1 FROM HDPSHIWAKEPATTERNDATA WHERE DENPY_KB = '2')", $strSQL);
        $strSQL = str_replace("@PATTERN_NM", $postData['txtPatternNM'], $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_KB", $postData['grpPattern'] == "radPatternKyotu" ? "1" : "2", $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_CD", $postData['grpPattern'] == "radPatternBusyo" ? $this->ClsComFncHDKAIKEI->FncNv($postData['txtPatternBusyo']) : "", $strSQL);

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
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();
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
        $strSQL .= ",     R_KAMOK_CD = '@R_KAMOK_CD'" . "\r\n";
        // 20240408 LQS INS S
        $strSQL .= ",     TATEKAE_SYA_CD = '@TATEKAE_SYA_CD'" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",     SHR_KAMOK_KB = '@SHR_KAMOK_KB'" . "\r\n";
        $strSQL .= ",     R_KOUMK_CD = '@R_KOUMK_CD'" . "\r\n";
        $strSQL .= ",     R_HASEI_KYOTN_CD = '@R_HASEI_KYOTN_CD'" . "\r\n";
        $strSQL .= ",     R_KAZEI_KB = @R_KAZEI_KB" . "\r\n";
        $strSQL .= ",     R_ZEI_RT_KB = @R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_CD = '@TORIHIKISAKI_CD'" . "\r\n";
        $strSQL .= ",     TORIHIKISAKI_NAME = '@TORIHIKISAKI_NAME'" . "\r\n";
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
        $strSQL .= "WHERE DENPY_KB = '@DENPY_KB'" . "\r\n";
        $strSQL .= "AND   PATTERN_NO = '@PATTERN_NO'" . "\r\n";

        $strSQL = str_replace("@PATTERN_NO", $strPattern_No, $strSQL);
        $strSQL = str_replace("@PATTERN_NM", $postData['txtPatternNM'], $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_KB", $postData['grpPattern'] == "radPatternKyotu" ? "1" : "2", $strSQL);
        $strSQL = str_replace("@TAISYO_BUSYO_CD", $postData['grpPattern'] == "radPatternBusyo" ? $this->ClsComFncHDKAIKEI->FncNv($postData['txtPatternBusyo']) : "", $strSQL);
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
        $strSQL .= ",	  (CASE WHEN KAR.KAMOK_NAME IS NULL THEN '' ELSE KAR.KAMOK_NAME END) L_KAMOK_NM" . "\r\n";
        $strSQL .= ",	  (CASE WHEN KAR.SUB_KAMOK_NAME IS NULL THEN '' ELSE KAR.SUB_KAMOK_NAME END) L_KOMOK_NM" . "\r\n";
        $strSQL .= ",      SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      LB.BUSYO_NM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",      SWK.L_KAZEI_KB" . "\r\n";
        $strSQL .= ",      SWK.L_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      SWK.R_KAMOK_CD" . "\r\n";
        // 20240408 LQS INS S
        $strSQL .= ",      SWK.TATEKAE_SYA_CD" . "\r\n";
        // 20240408 LQS INS E
        $strSQL .= ",      SWK.SHR_KAMOK_KB" . "\r\n";
        $strSQL .= ",      SWK.R_KOUMK_CD" . "\r\n";
        $strSQL .= ",	  (CASE WHEN KAS.MOJI1 IS NULL THEN KAS.MEISYOU ELSE KAS.MOJI1 END) R_KAMOK_NM" . "\r\n";
        $strSQL .= ",      SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= ",      RB.BUSYO_NM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",      SWK.R_KAZEI_KB" . "\r\n";
        $strSQL .= ",      SWK.R_ZEI_RT_KB" . "\r\n";
        $strSQL .= ",      SWK.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= ",      SWK.TORIHIKISAKI_NAME" . "\r\n";
        $strSQL .= ",      SWK.GINKO_KB" . "\r\n";
        $strSQL .= ",      SWK.GINKO_NM" . "\r\n";
        $strSQL .= ",      SWK.SHITEN_NM" . "\r\n";
        $strSQL .= ",      SWK.YOKIN_SYUBETU" . "\r\n";
        $strSQL .= ",      SWK.KOUZA_NO" . "\r\n";
        $strSQL .= ",      SWK.KOUZA_KN" . "\r\n";
        $strSQL .= ",      SWK.JIKI" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEPATTERNDATA SWK" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAR" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') AND KAR.USE_FLG='1'" . "\r\n";
        $strSQL .= "ON     KAR.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HMEISYOUMST KAS" . "\r\n";
        $strSQL .= "ON	TO_NUMBER(SUBSTR(KAS.MEISYOU_CD,1,1) || KAS.SUCHI1) = TO_NUMBER(SWK.SHR_KAMOK_KB || SWK.R_KAMOK_CD) AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUCHI2),'999999'),KAS.SUCHI2) = NVL(SWK.R_KOUMK_CD,'999999') AND KAS.MEISYOU_ID = 'DK'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON LB" . "\r\n";
        $strSQL .= "ON     LB.BUSYO_CD = SWK.L_HASEI_KYOTN_CD AND LB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON RB" . "\r\n";
        $strSQL .= "ON     RB.BUSYO_CD = SWK.R_HASEI_KYOTN_CD AND RB.USE_FLG='1'" . "\r\n";
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
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();
        //採番ﾃｰﾌﾞﾙに既に同一年月のものがあればUPDATE、なければINSERT
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
        $this->SessionComponent = Router::getRequest()->getSession();
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();
        $strSQL = str_replace("@ZEIKM_GK", $this->ClsComFncHDKAIKEI->FncNv($postData['txtZeikm_GK']), $strSQL);
        $strSQL = str_replace("@ZEINK_GK", $this->ClsComFncHDKAIKEI->FncNv($postData['lblZeink_GK']), $strSQL);
        $strSQL = str_replace("@SHZEI_GK", $this->ClsComFncHDKAIKEI->FncNv($postData['lblSyohizei']), $strSQL);
        $strSQL = str_replace("@TEKYO", $this->ClsComFncHDKAIKEI->FncNv($postData['txtTekyo']), $strSQL);
        $strSQL = str_replace("@L_KAMOK_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtLKamokuCD']), $strSQL);
        $strSQL = str_replace("@L_KOUMK_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtLKomokuCD']), $strSQL);
        $strSQL = str_replace("@L_HASEI_KYOTN_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtLBusyoCD']), $strSQL);
        $strLKazeiKB = $postData['ddlLSyohizeiKbn'];
        $strSQL = str_replace("@L_KAZEI_KB", "'" . $strLKazeiKB . "'", $strSQL);
        $strSQL = str_replace("@L_ZEI_RT_KB", "'" . $this->ClsComFncHDKAIKEI->FncNv($postData['ddlLSyohizeiritu']) . "'", $strSQL);

        $strSQL = str_replace("@R_KAMOK_CD", $this->ClsComFncHDKAIKEI->FncNv(substr($postData['ddlRKamokuCD'], 1)), $strSQL);
        // 20240408 LQS INS S
        if ($postData['syainCD'] <> "") {
            $strSQL = str_replace("@TATEKAE_SYA_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['syainCD']), $strSQL);
        } else {
            $strSQL = str_replace("@TATEKAE_SYA_CD", "", $strSQL);
        }
        // 20240408 LQS INS E
        $strSQL = str_replace("@SHR_KAMOK_KB", $this->ClsComFncHDKAIKEI->FncNv(substr($postData['ddlRKamokuCD'], 0, 1)), $strSQL);

        if ($postData['ddlRKamokuCD'] <> "") {
            $strSQL = str_replace("@R_KOUMK_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['ddlRKomokuCD']), $strSQL);
        } else {
            $strSQL = str_replace("@R_KOUMK_CD", "", $strSQL);
        }
        $strSQL = str_replace("@R_HASEI_KYOTN_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtRBusyoCD']), $strSQL);

        $strSQL = str_replace("@R_KAZEI_KB", "'" . $postData['ddlRSyohizeiKbn'] . "'", $strSQL);
        $strSQL = str_replace("@R_ZEI_RT_KB", "'" . $this->ClsComFncHDKAIKEI->FncNv($postData['ddlRSyohizeiritu']) . "'", $strSQL);


        if ($strKbn == "PATTERN") {
            //パターン登録の場合は画面に表示されている値を全て登録するように修正
            // 取引発生日
            $strSQL = str_replace("@TORIHIKI_DT", $this->ClsComFncHDKAIKEI->FncNv(str_replace("/", "", $postData['txtTorihikiHasseibi'])), $strSQL);
            // 取引先コード
            $strSQL = str_replace("@TORIHIKISAKI_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtKensakuCD']), $strSQL);
            // 取引先名
            $strSQL = str_replace("@TORIHIKISAKI_NAME", $this->ClsComFncHDKAIKEI->FncNv($postData['lblKensakuNM']), $strSQL);
            if ($postData['grpGinko'] == 'radHiroGinko') {
                // （GD）銀行
                //振込先銀行区分
                $strSQL = str_replace("@GINKO_KB", "1", $strSQL);
                //振込先銀行名
                $strSQL = str_replace("@GINKO_NM", "", $strSQL);
                //振込先支店名
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSonotaShiten']), $strSQL);
            } elseif ($postData['grpGinko'] == 'radMomijiGinko') {
                //もみじ銀行
                //振込先銀行区分
                $strSQL = str_replace("@GINKO_KB", "2", $strSQL);
                //振込先銀行名
                $strSQL = str_replace("@GINKO_NM", "", $strSQL);
                //振込先支店名
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSonotaShiten']), $strSQL);
            } elseif ($postData['grpGinko'] == 'radShinyoKinko') {
                //（GD）信用金庫
                //振込先銀行区分
                $strSQL = str_replace("@GINKO_KB", "3", $strSQL);
                //振込先銀行名
                $strSQL = str_replace("@GINKO_NM", "", $strSQL);
                //振込先支店名
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSonotaShiten']), $strSQL);
            } else {
                //その他銀行
                //振込先銀行区分
                $strSQL = str_replace("@GINKO_KB", "9", $strSQL);
                //振込先銀行名
                $strSQL = str_replace("@GINKO_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSonotaGinko']), $strSQL);
                //振込先支店名
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSonotaShiten']), $strSQL);
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
            // 取引先コード
            $strSQL = str_replace("@TORIHIKISAKI_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtKensakuCD']), $strSQL);
            // 取引先名
            $strSQL = str_replace("@TORIHIKISAKI_NAME", $this->ClsComFncHDKAIKEI->FncNv($postData['lblKensakuNM']), $strSQL);
            if ($postData['txtTorihikiHasseibiEna'] == "1") {
                //取引発生日
                $strSQL = str_replace("@TORIHIKI_DT", $this->ClsComFncHDKAIKEI->FncNv(str_replace("/", "", $postData['txtTorihikiHasseibi'])), $strSQL);
            } else {
                $strSQL = str_replace("@TORIHIKI_DT", "", $strSQL);
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
                $strSQL = str_replace("@GINKO_NM", $postData['grpGinko'] == "radGinkoSonota" ? $this->ClsComFncHDKAIKEI->FncNv($postData['txtSonotaGinko']) : "", $strSQL);
            } else {
                $strSQL = str_replace("@GINKO_NM", "", $strSQL);
            }
            //振込先支店名
            if ($postData['txtSonotaShitenEna'] == "1") {
                $strSQL = str_replace("@SHITEN_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSonotaShiten']), $strSQL);
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
            $strSQL = str_replace("@FUKANZEN_FLG", $postData['fncFukanzenCheck'], $strSQL);
        }
        // 20240129 YIN UPD S
        if (isset($postData['addData'])) {
            $strSQL = str_replace("@CRE_BUSYO_CD", $postData['strCreBusyoCD'], $strSQL);
            $strSQL = str_replace("@CRE_SYA_CD", $postData['strCreSyainCD'], $strSQL);
            $strSQL = str_replace("@CRE_CLT_NM", $postData['strCreCltNM'], $strSQL);
        } else {
            $strSQL = str_replace("@CRE_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
            $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
            $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        }
        $strSQL = str_replace("@CRE_PRG_ID", $strProgramID, $strSQL);
        // 20240129 YIN UPD E
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", $strProgramID, $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

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

}
