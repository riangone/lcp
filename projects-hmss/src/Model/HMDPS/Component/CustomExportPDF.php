<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240416        ASP版だけに修正                          php側対応                               YIN
 * 20240703   支払伝票帳票出力時に見出し文字列”特例区分：”、”摘要：”が不要なので消してほしいとのことです  caina
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMDPS\Component;

use App\Model\Component\ClsComDb;
use Cake\Routing\Router;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsComFnc
// * 処理説明：共通関数
//*************************************

class CustomExportPDF extends ClsComDb
{
    public $SessionComponent;
    public function FncGetKamokuMstValueSQL($strCode, $strKomoku, $strMstFormat)
    {
        //** ＳＱＬ作成
        $strSql = "";
        if ($strKomoku == "999999") {
            //科目名で検索(項目は科目でグルーピングした最初の項目)
            $strSql .= "SELECT KAMOK_NM" . "\r\n";
            $strSql .= "FROM  M_KAMOKU A" . "\r\n";
            $strSql .= "WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
            $strSql .= "AND   A.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        } else {
            //科目・項目名で検索
            $strSql .= "SELECT (KAMOK_NM || ' ' || KOMOK_NM) KAMOK_NM" . "\r\n";
            $strSql .= "FROM   M_KAMOKU" . "\r\n";
            $strSql .= "WHERE  KAMOK_CD = '@KAMOKUCD'" . "\r\n";

            if ($strMstFormat == "") {
                $strSql .= "AND  NVL(TRIM(KOMOK_CD),'00') = NVL(TRIM('@KOMOKU'),'00')" . "\r\n";
            } else {
                $strSql .= "AND  (CASE WHEN LENGTH(TRIM(KOMOK_CD)) > 2 THEN TRIM(KOMOK_CD) ELSE NVL(LPAD(TRIM(KOMOK_CD),2,'@MSTFORMAT'),'00') END) = NVL(TRIM('@KOMOKU'),'00')" . "\r\n";
            }
        }

        $strSql = str_replace("@KAMOKUCD", $strCode, $strSql);
        $strSql = str_replace("@KOMOKU", $strKomoku, $strSql);
        $strSql = str_replace("@MSTFORMAT", $strMstFormat, $strSql);
        return $strSql;
    }

    public function fncPrintSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.SYOHY_NO || SWK.EDA_NO SYOHY_NO" . "\r\n";
        // 20240416 YIN UPD S
        // $strSQL .= ",      TO_CHAR(TO_DATE(SWK.KEIRI_DT),'YYYY/MM/DD') KEIRI_DT" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(SWK.KEIRI_DT,'YYYYMMDD'),'YYYY/MM/DD') KEIRI_DT" . "\r\n";
        // 20240416 YIN UPD E
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') PRINT_DATE" . "\r\n";
        $strSQL .= ",      (CASE WHEN SWK.L_KOUMK_CD IS NULL THEN SWK.L_KAMOK_CD ELSE SWK.L_KAMOK_CD || '-' || SWK.L_KOUMK_CD END) L_KAMOKU_CD" . "\r\n";
        $strSQL .= ",      (CASE WHEN SWK.L_KOUMK_CD IS NULL THEN LKMK.KAMOK_SSK_NM ELSE LKMK.KMK_KUM_NM END) L_KAMOKU" . "\r\n";
        $strSQL .= ",      (CASE WHEN SWK.R_KOUMK_CD IS NULL THEN SWK.R_KAMOK_CD ELSE SWK.R_KAMOK_CD || '-' || SWK.R_KOUMK_CD END) R_KAMOKU_CD" . "\r\n";
        $strSQL .= ",      (CASE WHEN SWK.R_KOUMK_CD IS NULL THEN RKMK.KAMOK_SSK_NM ELSE RKMK.KMK_KUM_NM END) R_KAMOKU" . "\r\n";
        $strSQL .= ",      (DECODE(LKMK.KOZ_KEY1_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.KOZ_KEY1_KOBAN) || ':' || L_KOUZA_KEY1 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.KOZ_KEY2_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.KOZ_KEY2_KOBAN)  || ':' || L_KOUZA_KEY2 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.KOZ_KEY3_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.KOZ_KEY3_KOBAN)  || ':' || L_KOUZA_KEY3 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.KOZ_KEY4_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.KOZ_KEY4_KOBAN) || ':' || L_KOUZA_KEY4 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.KOZ_KEY5_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.KOZ_KEY5_KOBAN) || ':' || L_KOUZA_KEY5 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY1_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY1_KOBAN) || ':' || L_HISSU_TEKYO1 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY2_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY2_KOBAN) || ':' || L_HISSU_TEKYO2 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY3_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY3_KOBAN)  || ':' || L_HISSU_TEKYO3 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY4_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY4_KOBAN)  || ':' || L_HISSU_TEKYO4 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY5_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY5_KOBAN) || ':' || L_HISSU_TEKYO5 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY6_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY6_KOBAN) || ':' || L_HISSU_TEKYO6 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY7_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY7_KOBAN)  || ':' || L_HISSU_TEKYO7 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY8_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY8_KOBAN)  || ':' || L_HISSU_TEKYO8 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY9_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY9_KOBAN)  || ':' || L_HISSU_TEKYO9 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(LKMK.HIS_TKY10_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = LKMK.HIS_TKY10_KOBAN)  || ':' || L_HISSU_TEKYO10))" . "\r\n";
        $strSQL .= "       AS UCHIWAKE_KARI" . "\r\n";
        $strSQL .= ",      (DECODE(RKMK.KOZ_KEY1_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.KOZ_KEY1_KOBAN) || ':' || R_KOUZA_KEY1 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.KOZ_KEY2_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.KOZ_KEY2_KOBAN) || ':' || R_KOUZA_KEY2 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.KOZ_KEY3_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.KOZ_KEY3_KOBAN) || ':' || R_KOUZA_KEY3 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.KOZ_KEY4_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.KOZ_KEY4_KOBAN) || ':' || R_KOUZA_KEY4 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.KOZ_KEY5_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.KOZ_KEY5_KOBAN) || ':' || R_KOUZA_KEY5 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY1_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY1_KOBAN) || ':' || R_HISSU_TEKYO1 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY2_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY2_KOBAN) || ':' || R_HISSU_TEKYO2 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY3_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY3_KOBAN) || ':' || R_HISSU_TEKYO3 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY4_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY4_KOBAN) || ':' || R_HISSU_TEKYO4 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY5_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY5_KOBAN) || ':' || R_HISSU_TEKYO5 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY6_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY6_KOBAN) || ':' || R_HISSU_TEKYO6 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY7_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY7_KOBAN) || ':' || R_HISSU_TEKYO7 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY8_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY8_KOBAN) || ':' || R_HISSU_TEKYO8 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY9_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY9_KOBAN) || ':' || R_HISSU_TEKYO9 || ' ') ||" . "\r\n";
        $strSQL .= "       DECODE(RKMK.HIS_TKY10_KOBAN,NULL,'',(SELECT KOBAN_NM FROM M29FZ7 WHERE KOBAN = RKMK.HIS_TKY10_KOBAN) || ':' || R_HISSU_TEKYO10))" . "\r\n";
        $strSQL .= "       AS UCHIWAKE_KASHI" . "\r\n";
        $strSQL .= ",      SWK.L_HASEI_KYOTN_CD L_BUSYO_CD" . "\r\n";
        $strSQL .= ",      LBS.BUSYO_RYKNM L_BUSYO" . "\r\n";
        $strSQL .= ",      SWK.R_HASEI_KYOTN_CD R_BUSYO_CD" . "\r\n";
        $strSQL .= ",      RBS.BUSYO_RYKNM R_BUSYO" . "\r\n";
        if ($postData == "2") {
            $strSQL .= ",      SWK.SHIHARAISAKI_CD" . "\r\n";
            $strSQL .= ",      SWK.SHIHARAISAKI_NM" . "\r\n";
            $strSQL .= ",      (CASE WHEN SWK.JIKI = '1' THEN '即日支払'" . "\r\n";
            // 20240416 YIN UPD S
            // $strSQL .= "             WHEN SWK.JIKI = '2' THEN TO_CHAR(TO_DATE(SWK.SHIHARAI_DT),'MM/DD') || '支払'" . "\r\n";
            $strSQL .= "             WHEN SWK.JIKI = '2' THEN TO_CHAR(TO_DATE(SWK.SHIHARAI_DT,'YYYYMMDD'),'MM/DD') || '支払'" . "\r\n";
            // 20240416 YIN UPD E
            $strSQL .= "             WHEN SWK.JIKI = '3' THEN '1ヵ月後締切後支払' ELSE '' END) SHIHARAI_DT" . "\r\n";
            $strSQL .= ",      (SELECT MEI.MEISYOU || DECODE(MEI.MOJI1,'','','(' || MEI.MOJI1 || ')') FROM HMEISYOUMST MEI WHERE  NVL(MEI.SUCHI2,00) = NVL(SWK.R_KOUMK_CD,00) AND SUBSTR(MEISYOU_CD,1,1) = SWK.SHR_KAMOK_KB AND MEI.SUCHI1 = SWK.R_KAMOK_CD) SHIKIN" . "\r\n";
            $strSQL .= ",      (CASE WHEN SWK.GINKO_KB = '1' THEN '（GD）銀行'" . "\r\n";
            $strSQL .= "             WHEN SWK.GINKO_KB = '2' THEN 'もみじ銀行'" . "\r\n";
            $strSQL .= "             WHEN SWK.GINKO_KB = '3' THEN '（GD）信用金庫'" . "\r\n";
            $strSQL .= "             ELSE SWK.GINKO_NM END) GINKOMEI" . "\r\n";
            $strSQL .= ",      SWK.SHITEN_NM" . "\r\n";
            $strSQL .= ",      (CASE WHEN SWK.YOKIN_SYUBETU = '1' THEN '普通'" . "\r\n";
            $strSQL .= "             WHEN SWK.YOKIN_SYUBETU = '2' THEN '当座'" . "\r\n";
            $strSQL .= "             WHEN SWK.YOKIN_SYUBETU = '9' THEN 'その他' ELSE '' END) SYUBETU" . "\r\n";
            $strSQL .= ",      SWK.KOUZA_NO" . "\r\n";
            $strSQL .= ",      SWK.KOUZA_KN" . "\r\n";
            // 20240416 YIN UPD S
            // $strSQL .= ",      DECODE(SWK.TORIHIKI_DT,NULL,'','取引発生日：' || TO_CHAR(TO_DATE(SWK.TORIHIKI_DT),'YYYY/MM/DD'))  UCHIWAKE1" . "\r\n";
            $strSQL .= ",      DECODE(SWK.TORIHIKI_DT,NULL,'','取引発生日：' || TO_CHAR(TO_DATE(SWK.TORIHIKI_DT,'YYYYMMDD'),'YYYY/MM/DD'))  UCHIWAKE1" . "\r\n";
            // 20240416 YIN UPD E
            $strSQL .= ",      DECODE(SWK.TORIHIKI_DT,NULL,'','請求書№：' || SWK.SEIKYUSYO_NO) UCHIWAKE_TA" . "\r\n";
        }
        $strSQL .= ",      SWK.ZEIKM_GK" . "\r\n";
        $strSQL .= ",      (CASE WHEN SWK.L_KAZEI_KB = '0' THEN DECODE(SWK.L_ZEI_RT_KB,'0','0%','4','5%','5','8%','6','8%軽減','7','10%')" . "\r\n";
        $strSQL .= "             WHEN SWK.L_KAZEI_KB = '1' THEN '非課税'" . "\r\n";
        $strSQL .= "             WHEN SWK.L_KAZEI_KB = '9' THEN '対象外' END) L_SYOHIZEI" . "\r\n";
        $strSQL .= ",      (CASE WHEN SWK.R_KAZEI_KB = '0' THEN DECODE(SWK.R_ZEI_RT_KB,'0','0%','4','5%','5','8%','6','8%軽減','7','10%')" . "\r\n";
        $strSQL .= "             WHEN SWK.R_KAZEI_KB = '1' THEN '非課税'" . "\r\n";
        $strSQL .= "             WHEN SWK.R_KAZEI_KB = '9' THEN '対象外' END) R_SYOHIZEI" . "\r\n";
        // 20240416 YIN UPD S
        // $strSQL .= ",      TEKYO" . "\r\n";
        $strSQL .= ",      (CASE WHEN SWK.TOKUREI_KB IS NULL THEN SWK.TEKYO " . "\r\n";
        //20240703 caina upd s
        // $strSQL .= "             ELSE  '特例区分：'||TOKUREI.MEISYOU_RN|| '　' ||'摘要：'||SWK.TEKYO END） AS TEKYO" . "\r\n";
        $strSQL .= "             ELSE  TOKUREI.MEISYOU_RN|| '　' ||SWK.TEKYO END） AS TEKYO" . "\r\n";
        //20240703 caina upd e
        // 20240416 YIN UPD E
        $strSQL .= ",      TO_CHAR(SWK.CREATE_DATE,'YYYY/MM/DD') CREATE_DATE" . "\r\n";
        $strSQL .= ",      '' BIKOU" . "\r\n";
        // $strSQL .= ",      (SELECT SUM(GK.ZEIKM_GK) FROM HDPSHIWAKEDATA GK WHERE SWK.SYOHY_NO = GK.SYOHY_NO AND SWK.EDA_NO = GK.EDA_NO) ZEIKM_GK_GOUKEI" . "\r\n";
        $strSQL .= ",      (SELECT SUM(GK.ZEIKM_GK) FROM HDPSHIWAKEDATA GK WHERE SWK.SYOHY_NO = GK.SYOHY_NO AND SWK.EDA_NO = GK.EDA_NO AND GK.KEIRI_DT IS NOT NULL) ZEIKM_GK_WITH_DATE" . "\r\n";
        $strSQL .= ",      (SELECT SUM(GK.ZEIKM_GK) FROM HDPSHIWAKEDATA GK WHERE SWK.SYOHY_NO = GK.SYOHY_NO AND SWK.EDA_NO = GK.EDA_NO AND GK.KEIRI_DT IS NULL) ZEIKM_GK_WITHOUT_DATE" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "INNER JOIN" . "\r\n";
        $strSQL .= "       WK_SYOHY_NO SYOHY" . "\r\n";
        $strSQL .= "ON     SYOHY.SYOHY_NO = SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.EDA_NO = SWK.EDA_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_SYA_CD = '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_PRG_ID = '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_CLT_NM = '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       M29FZ6 LKMK" . "\r\n";
        $strSQL .= "ON     LKMK.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(LKMK.KOUMK_CD),'999999'),LKMK.KOUMK_CD) = NVL(SWK.L_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       M29FZ6 RKMK" . "\r\n";
        $strSQL .= "ON     RKMK.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(RKMK.KOUMK_CD),'999999'),RKMK.KOUMK_CD) = NVL(SWK.R_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HBUSYO LBS" . "\r\n";
        $strSQL .= "ON     LBS.BUSYO_CD = SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HBUSYO RBS" . "\r\n";
        $strSQL .= "ON     RBS.BUSYO_CD = SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = SWK.CRE_SYA_CD" . "\r\n";
        // 20240416 YIN INS S
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "        HMEISYOUMST TOKUREI" . "\r\n";
        $strSQL .= "ON     TOKUREI.MEISYOU_ID = 'DR'" . "\r\n";
        $strSQL .= "AND    TOKUREI.MEISYOU_CD = SWK.TOKUREI_KB" . "\r\n";
        // 20240416 YIN INS E
        $strSQL .= "WHERE  SWK.DENPY_KB = '@DENPY_KB'" . "\r\n";
        $strSQL .= "ORDER BY SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",        SWK.EDA_NO" . "\r\n";
        $strSQL .= ",        SWK.GYO_NO" . "\r\n";
        $strSQL = str_replace("@DENPY_KB", $postData, $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);
        return $strSQL;
    }



    public function fncGroupSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.SYOHY_NO || SWK.EDA_NO SYOHY_NO" . "\r\n";
        // 20240416 YIN UPD S
        // $strSQL .= ",      TO_CHAR(TO_DATE(SWK.KEIRI_DT),'YYYY/MM/DD') KEIRI_DT" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(SWK.KEIRI_DT,'YYYYMMDD'),'YYYY/MM/DD') KEIRI_DT" . "\r\n";
        // 20240416 YIN UPD E
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "INNER JOIN" . "\r\n";
        $strSQL .= "       WK_SYOHY_NO SYOHY" . "\r\n";
        $strSQL .= "ON     SYOHY.SYOHY_NO = SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.EDA_NO = SWK.EDA_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_SYA_CD = '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_PRG_ID = '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_CLT_NM = '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       M29FZ6 LKMK" . "\r\n";
        $strSQL .= "ON     LKMK.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(LKMK.KOUMK_CD),'999999'),LKMK.KOUMK_CD) = NVL(SWK.L_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       M29FZ6 RKMK" . "\r\n";
        $strSQL .= "ON     RKMK.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(RKMK.KOUMK_CD),'999999'),RKMK.KOUMK_CD) = NVL(SWK.R_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HBUSYO LBS" . "\r\n";
        $strSQL .= "ON     LBS.BUSYO_CD = SWK.L_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HBUSYO RBS" . "\r\n";
        $strSQL .= "ON     RBS.BUSYO_CD = SWK.R_HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = SWK.CRE_SYA_CD" . "\r\n";
        $strSQL .= "WHERE  SWK.DENPY_KB = '@DENPY_KB'" . "\r\n";
        $strSQL .= "GROUP BY SWK.SYOHY_NO || SWK.EDA_NO,KEIRI_DT" . "\r\n";
        $strSQL .= "ORDER BY SWK.SYOHY_NO || SWK.EDA_NO" . "\r\n";
        $strSQL = str_replace("@DENPY_KB", $postData, $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);

        return $strSQL;
    }

    public function fncUpdPrintFlgSQL($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "SET    PRINT_OUT_FLG = '1'" . "\r\n";
        if ($this->SessionComponent->read('PatternID') == $postData['CONST_ADMIN_PTN_NO'] || ($this->SessionComponent->read('PatternID') == $postData['CONST_HONBU_PTN_NO'])) {
            $strSQL .= ",      HONBU_SYORIZUMI_FLG = '1'" . "\r\n";
        }
        $strSQL .= ",      UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",      UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  EXISTS " . "\r\n";
        $strSQL .= "       (SELECT SYOHY.SYOHY_NO" . "\r\n";
        $strSQL .= "        FROM   WK_SYOHY_NO SYOHY" . "\r\n";
        $strSQL .= "        WHERE  SYOHY.SYOHY_NO = SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "        AND    SYOHY.EDA_NO = SWK.EDA_NO" . "\r\n";
        $strSQL .= "        AND    SYOHY.CRE_SYA_CD = '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= "        AND    SYOHY.CRE_PRG_ID = '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= "        AND    SYOHY.CRE_CLT_NM = '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= "        )" . "\r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $postData['BusyoCD'], $strSQL);

        return $strSQL;
    }

    public function fncPrint($postData)
    {
        $strSql = $this->fncPrintSQL($postData);

        return parent::select($strSql);
    }
    // public function fncHDKPrint($postData)
    // {
    //     $strSql = $this->fncHDKPrintSQL($postData);

    //     return parent::select($strSql);
    // }

    public function fncGroup($postData)
    {
        $strSql = $this->fncGroupSQL($postData);

        return parent::select($strSql);
    }

    // public function fncHDKGroup($postData)
    // {
    //     $strSql = $this->fncHDKGroupSQL($postData);

    //     return parent::select($strSql);
    // }

    public function fncUpdPrintFlg($postData)
    {
        $strSql = $this->fncUpdPrintFlgSQL($postData);

        return parent::update($strSql);
    }


}
