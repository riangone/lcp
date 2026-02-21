<?php
namespace App\Model\HMDPS;

use App\Model\Component\ClsComDb;
use App\Model\HMDPS\Component\ClsComFncHMDPS;

class HMDPS103PatternSearch extends ClsComDb
{
    public $ClsComFncHMDPS;
    function FncGetSql_Pattern($postData)
    {
        $this->ClsComFncHMDPS = new ClsComFncHMDPS();

        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "       HDPSHIWAKEPATTERNDATA.DENPY_KB" . "\r\n";
        $strSQL .= ",      HDPSHIWAKEPATTERNDATA.PATTERN_NO as PATNO" . "\r\n";
        $strSQL .= ",      HDPSHIWAKEPATTERNDATA.PATTERN_NM as PATTERN_NM" . "\r\n";
        $strSQL .= ",      (CASE WHEN HDPSHIWAKEPATTERNDATA.TAISYO_BUSYO_KB = '1' THEN '共通' ELSE HB1.BUSYO_NM END) AS BUSYO_NM" . "\r\n";
        $strSQL .= ",      DECODE(HDPSHIWAKEPATTERNDATA.L_KOUMK_CD,NULL,M1.KAMOK_SSK_NM,M1.KMK_KUM_NM) AS KMK_KUM_NM1" . "\r\n";
        $strSQL .= ",      HB2.BUSYO_NM  as BUSYO_NM2" . "\r\n";
        $strSQL .= ",      DECODE(HDPSHIWAKEPATTERNDATA.R_KOUMK_CD,NULL,M2.KAMOK_SSK_NM,M2.KMK_KUM_NM) AS KMK_KUM_NM2" . "\r\n";
        $strSQL .= ",      HB3.BUSYO_NM  AS BUSYO_NM3" . "\r\n";
        $strSQL .= ",      substr(HDPSHIWAKEPATTERNDATA.TEKYO,1,10) TEKYO" . "\r\n";
        $strSQL .= ",      SUBSTRB(HDPSHIWAKEPATTERNDATA.SHIHARAISAKI_NM,1,16) AS SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "      HDPSHIWAKEPATTERNDATA" . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     HBUSYO HB1" . "\r\n";
        $strSQL .= " ON  HDPSHIWAKEPATTERNDATA.TAISYO_BUSYO_CD = HB1.BUSYO_CD " . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     HBUSYO HB2" . "\r\n";
        $strSQL .= " ON  HDPSHIWAKEPATTERNDATA.L_HASEI_KYOTN_CD = HB2.BUSYO_CD " . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     HBUSYO HB3" . "\r\n";
        $strSQL .= " ON  HDPSHIWAKEPATTERNDATA.R_HASEI_KYOTN_CD = HB3.BUSYO_CD " . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     M29FZ6 M1 " . "\r\n";
        $strSQL .= " ON  M1.KAMOK_CD = HDPSHIWAKEPATTERNDATA.L_KAMOK_CD " . "\r\n";
        $strSQL .= " AND DECODE(HDPSHIWAKEPATTERNDATA.L_KOUMK_CD,NULL,NVL(TRIM(M1.KOUMK_CD),'999999'),M1.KOUMK_CD) = NVL(HDPSHIWAKEPATTERNDATA.L_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "     M29FZ6 M2 " . "\r\n";
        $strSQL .= " ON  M2.KAMOK_CD = HDPSHIWAKEPATTERNDATA.R_KAMOK_CD" . "\r\n";
        $strSQL .= " AND DECODE(HDPSHIWAKEPATTERNDATA.R_KOUMK_CD,NULL,NVL(TRIM(M2.KOUMK_CD),'999999'),M2.KOUMK_CD) = NVL(HDPSHIWAKEPATTERNDATA.R_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "       1=1 " . "\r\n";

        if ($postData["rdoDENPYO"] == "1" || $postData["rdoDENPYO"] == "2") {
            $strSQL .= " AND HDPSHIWAKEPATTERNDATA.DENPY_KB = '@DENPYKB'" . "\r\n";
            $strSQL = str_replace("@DENPYKB", $this->ClsComFncHMDPS->FncNv($postData['rdoDENPYO']), $strSQL);
        }

        if ($postData["rdoSYURUI"] == "1" || $postData["rdoSYURUI"] == "2") {
            $strSQL .= " AND HDPSHIWAKEPATTERNDATA.TAISYO_BUSYO_KB = '@TAISYOBUSYOKB'" . "\r\n";
            $strSQL = str_replace("@TAISYOBUSYOKB", $this->ClsComFncHMDPS->FncNv($postData['rdoSYURUI']), $strSQL);
            if ($postData['BusyoCD'] != '' && $postData["rdoSYURUI"] == "2") {
                $strSQL .= "  AND   TAISYO_BUSYO_CD = '@BUSYOCD'" . "\r\n";
                $strSQL = str_replace("@BUSYOCD", $this->ClsComFncHMDPS->FncNv($postData['BusyoCD']), $strSQL);
            }
        }

        if ($postData['txtPatternName'] != '') {
            $strSQL .= " AND   PATTERN_NM LIKE '@PATTRERN%'" . "\r\n";
            $strSQL = str_replace("@PATTRERN", $this->ClsComFncHMDPS->FncNv($postData['txtPatternName']), $strSQL);
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
