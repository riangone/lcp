<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                                    担当
 * YYYYMMDD           #ID                                     XXXXXX                                 FCSDL
 * 20240227           20240213_機能改善要望対応 NO6    「科目マスタの使用フラグ、使用フラグ名は撤廃」        YIN
 * 20240322           本番障害.xlsx NO8         			科目名、補助科目名は両方表示してほしい  		LHB
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
use App\Model\HDKAIKEI\Component\ClsComFncHDKAIKEI;

class HDKPatternSearch extends ClsComDb
{
    public $ClsComFncHDKAIKEI;
    function FncGetSql_Pattern($postData)
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "       HDPSHIWAKEPATTERNDATA.DENPY_KB" . "\r\n";
        $strSQL .= ",      HDPSHIWAKEPATTERNDATA.PATTERN_NO as PATNO" . "\r\n";
        $strSQL .= ",      HDPSHIWAKEPATTERNDATA.PATTERN_NM as PATTERN_NM" . "\r\n";
        $strSQL .= ",      (CASE WHEN HDPSHIWAKEPATTERNDATA.TAISYO_BUSYO_KB = '1' THEN '共通' ELSE HB1.BUSYO_NM END) AS BUSYO_NM" . "\r\n";
        // 20240322 LHB UPD S
        // $strSQL .= ",      DECODE(HDPSHIWAKEPATTERNDATA.L_KOUMK_CD,NULL,M1.KAMOK_NAME,M1.SUB_KAMOK_NAME) AS KMK_KUM_NM1" . "\r\n";
        $strSQL .= ",      M1.KAMOK_NAME AS L_KAMOKU" . "\r\n";
        $strSQL .= ",      M1.SUB_KAMOK_NAME L_KOUMKU" . "\r\n";
        // 20240322 LHB UPD E
        $strSQL .= ",      HB2.BUSYO_NM  as BUSYO_NM2" . "\r\n";
        // 20240322 LHB UPD S
        // $strSQL .= ",      CASE HDPSHIWAKEPATTERNDATA.DENPY_KB  " . "\r\n";
        // $strSQL .= "       WHEN '1' THEN DECODE(HDPSHIWAKEPATTERNDATA.R_KOUMK_CD,NULL,M2.KAMOK_NAME,M2.SUB_KAMOK_NAME) " . "\r\n";
        // $strSQL .= "       ELSE (CASE WHEN HMS.MOJI1 IS NULL THEN HMS.MEISYOU ELSE HMS.MOJI1 END) END  KMK_KUM_NM2" . "\r\n";
        $strSQL .= ",      CASE HDPSHIWAKEPATTERNDATA.DENPY_KB  " . "\r\n";
        $strSQL .= "       WHEN '1' THEN M2.KAMOK_NAME " . "\r\n";
        $strSQL .= "       ELSE HMS.MEISYOU  END  R_KAMOKU" . "\r\n";
        $strSQL .= ",      CASE HDPSHIWAKEPATTERNDATA.DENPY_KB WHEN '1' THEN  M2.SUB_KAMOK_NAME ELSE HMS.MOJI1 END  R_KOUMKU" . "\r\n";
        // 20240322 LHB UPD E
        $strSQL .= ",      HB3.BUSYO_NM  AS BUSYO_NM3" . "\r\n";
        $strSQL .= ",      substr(HDPSHIWAKEPATTERNDATA.TEKYO,1,10) TEKYO" . "\r\n";
        $strSQL .= ",      SUBSTRB(HDPSHIWAKEPATTERNDATA.TORIHIKISAKI_NAME,1,16) AS TORIHIKISAKI_NAME" . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "      HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     HDK_MST_BUMON HB1" . "\r\n";
        $strSQL .= " ON  HDPSHIWAKEPATTERNDATA.TAISYO_BUSYO_CD = HB1.BUSYO_CD AND HB1.USE_FLG = '1' " . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     HDK_MST_BUMON HB2" . "\r\n";
        $strSQL .= " ON  HDPSHIWAKEPATTERNDATA.L_HASEI_KYOTN_CD = HB2.BUSYO_CD AND HB2.USE_FLG = '1' " . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     HDK_MST_BUMON HB3" . "\r\n";
        $strSQL .= " ON  HDPSHIWAKEPATTERNDATA.R_HASEI_KYOTN_CD = HB3.BUSYO_CD AND HB3.USE_FLG = '1' " . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     HDK_MST_KAMOKU M1 " . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= " ON  M1.KAMOK_CD = HDPSHIWAKEPATTERNDATA.L_KAMOK_CD AND M1.USE_FLG = '1'  " . "\r\n";
        $strSQL .= " ON  M1.KAMOK_CD = HDPSHIWAKEPATTERNDATA.L_KAMOK_CD  " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= " AND DECODE(HDPSHIWAKEPATTERNDATA.L_KOUMK_CD,NULL,NVL(TRIM(M1.SUB_KAMOK_CD),'999999'),M1.SUB_KAMOK_CD) = NVL(HDPSHIWAKEPATTERNDATA.L_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     HDK_MST_KAMOKU M2 " . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= " ON  M2.KAMOK_CD = HDPSHIWAKEPATTERNDATA.R_KAMOK_CD AND M2.USE_FLG = '1' " . "\r\n";
        $strSQL .= " ON  M2.KAMOK_CD = HDPSHIWAKEPATTERNDATA.R_KAMOK_CD " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= " AND DECODE(HDPSHIWAKEPATTERNDATA.R_KOUMK_CD,NULL,NVL(LPAD(TRIM(M2.SUB_KAMOK_CD),5,'0'),'999999'),LPAD(M2.SUB_KAMOK_CD,5,'0')) = NVL(LPAD(HDPSHIWAKEPATTERNDATA.R_KOUMK_CD,5,'0'),'999999')" . "\r\n";
        $strSQL .= " LEFT JOIN HMEISYOUMST HMS" . "\r\n";
        $strSQL .= " ON	TO_NUMBER(SUBSTR(HMS.MEISYOU_CD,1,1) || HMS.SUCHI1) = TO_NUMBER(HDPSHIWAKEPATTERNDATA.SHR_KAMOK_KB || HDPSHIWAKEPATTERNDATA.R_KAMOK_CD) AND DECODE(HDPSHIWAKEPATTERNDATA.R_KOUMK_CD,NULL,NVL(TRIM(HMS.SUCHI2),'999999'),HMS.SUCHI2) = NVL(HDPSHIWAKEPATTERNDATA.R_KOUMK_CD,'999999') AND HMS.MEISYOU_ID = 'DK'" . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "       1=1 " . "\r\n";

        if ($postData["rdoDENPYO"] == "1" || $postData["rdoDENPYO"] == "2") {
            $strSQL .= " AND HDPSHIWAKEPATTERNDATA.DENPY_KB = '@DENPYKB'" . "\r\n";
            $strSQL = str_replace("@DENPYKB", $this->ClsComFncHDKAIKEI->FncNv($postData['rdoDENPYO']), $strSQL);
        }

        if ($postData["rdoSYURUI"] == "1" || $postData["rdoSYURUI"] == "2") {
            $strSQL .= " AND HDPSHIWAKEPATTERNDATA.TAISYO_BUSYO_KB = '@TAISYOBUSYOKB'" . "\r\n";
            $strSQL = str_replace("@TAISYOBUSYOKB", $this->ClsComFncHDKAIKEI->FncNv($postData['rdoSYURUI']), $strSQL);
            if ($postData['BusyoCD'] != '' && $postData["rdoSYURUI"] == "2") {
                $strSQL .= "  AND   TAISYO_BUSYO_CD = '@BUSYOCD'" . "\r\n";
                $strSQL = str_replace("@BUSYOCD", $this->ClsComFncHDKAIKEI->FncNv($postData['BusyoCD']), $strSQL);
            }
        }

        if ($postData['txtPatternName'] != '') {
            $strSQL .= " AND   PATTERN_NM LIKE '@PATTRERN%'" . "\r\n";
            $strSQL = str_replace("@PATTRERN", $this->ClsComFncHDKAIKEI->FncNv($postData['txtPatternName']), $strSQL);
        }
        $strSQL .= "  ORDER BY " . "\r\n";
        $strSQL .= "        HDPSHIWAKEPATTERNDATA.PATTERN_NM " . "\r\n";
        return $strSQL;
    }

    //パターン検索データを取得
    public function Kensaku_Click($postData)
    {
        $strSql = $this->FncGetSql_Pattern($postData);

        return parent::select($strSql);
    }

}

