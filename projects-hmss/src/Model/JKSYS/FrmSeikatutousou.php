<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

class FrmSeikatutousou extends ClsComDb
{
    //人事コントロールマスタ取得SQL
    public function fncJinjiCtlMstSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT ID" . " \r\n";
        $strSQL .= "     , SYORI_YM" . " \r\n";
        $strSQL .= " FROM   JKCONTROLMST " . " \r\n";
        $strSQL .= " WHERE  ID = '01'  " . " \r\n";
        return $strSQL;
    }

    //調査票1データ取得SQL
    public function fncCyousahyou1SQL($DateTimePicker1)
    {
        $strSql = "";
        $strSql .= " SELECT COUNT(*) AS KENSUU " . "\r\n";
        $strSql .= "      , SUM(CASE WHEN sy.SEIBETU_CD = '0' AND sy.KOYOU_KB_CD = '01' THEN 1 ELSE 0 END)  AS M1_JYUSUU_M " . "\r\n";
        $strSql .= "      , SUM(CASE WHEN sy.SEIBETU_CD = '1' AND sy.KOYOU_KB_CD = '01'  THEN 1 ELSE 0 END) AS M1_JYUSUU_W " . "\r\n";
        $strSql .= "      , SUM(CASE WHEN sy.KOYOU_KB_CD = '01' THEN 1 ELSE 0 END) AS M1_JYUSUU " . "\r\n";
        $strSql .= "      , SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN 1 ELSE 0 END) AS M1_KUMISUU_M " . "\r\n";
        $strSql .= "      , SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN 1 ELSE 0 END) AS M1_KUMISUU_W " . "\r\n";
        $strSql .= "      , SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END) AS M1_KUMISUU " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN MONTHS_BETWEEN(TO_DATE('@TAISYOU_YMD','YYYY/MM/DD'),sy.BIRTHDAY) / 12 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN 1 ELSE 0 END),1) AS M1_KUMIAVNEN_M " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN MONTHS_BETWEEN(TO_DATE('@TAISYOU_YMD','YYYY/MM/DD'),sy.BIRTHDAY) / 12 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN 1 ELSE 0 END), 1) AS M1_KUMIAVNEN_W  " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN MONTHS_BETWEEN(TO_DATE('@TAISYOU_YMD','YYYY/MM/DD'),sy.BIRTHDAY) / 12 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 1) AS M1_KUMIAVNEN  " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN MONTHS_BETWEEN(TO_DATE('@TAISYOU_YMD','YYYY/MM/DD'),sy.NYUSYA_DT) / 12 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN 1 ELSE 0 END), 1) AS M1_KUMIAVKIN_M  " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN MONTHS_BETWEEN(TO_DATE('@TAISYOU_YMD','YYYY/MM/DD'),sy.NYUSYA_DT) / 12 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN 1 ELSE 0 END), 1) AS M1_KUMIAVKIN_W  " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN MONTHS_BETWEEN(TO_DATE('@TAISYOU_YMD','YYYY/MM/DD'),sy.NYUSYA_DT) / 12 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 1) AS M1_KUMIAVKIN " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN so.SONOTA6 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN 1 ELSE 0 END), 1) AS M1_KUMIAVFUSUU_M" . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN so.SONOTA6 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN 1 ELSE 0 END), 1) AS M1_KUMIAVFUSUU_W  " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN so.SONOTA6 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 1) AS M1_KUMIAVFUSUU " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN si.SHIKYU1 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN 1 ELSE 0 END), 0) AS M1_KUMIAVKIKYU_M " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN si.SHIKYU1 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN 1 ELSE 0 END), 0) AS M1_KUMIAVKIKYU_W " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN si.SHIKYU1 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 0) AS M1_KUMIAVKIKYU " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN si.SHIKYU1 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN 1 ELSE 0 END), 0) +  " . "\r\n";
        $strSql .= "        ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN si.SHIKYU2 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN 1 ELSE 0 END), 0) +  " . "\r\n";
        $strSql .= "        ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN si.SHIKYU5 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '0' THEN 1 ELSE 0 END), 0) AS M1_KUMIAVTEGE_M  " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN si.SHIKYU1 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN 1 ELSE 0 END), 0) +  " . "\r\n";
        $strSql .= "        ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN si.SHIKYU2 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN 1 ELSE 0 END), 0) +  " . "\r\n";
        $strSql .= "        ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN si.SHIKYU5 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' AND sy.SEIBETU_CD = '1' THEN 1 ELSE 0 END), 0) AS M1_KUMIAVTEGE_W  " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN si.SHIKYU1 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 0) +  " . "\r\n";
        $strSql .= "        ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN si.SHIKYU2 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 0) +  " . "\r\n";
        $strSql .= "        ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN si.SHIKYU5 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 0) AS M1_KUMIAVTEGE " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN si.SHIKYU1 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 0) AS M2_KIHONKYU " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN si.SHIKYU2 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 0) AS M2_SYOKUMUTE " . "\r\n";
        $strSql .= "      , ROUND(SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN si.SHIKYU5 END) / SUM(CASE WHEN sy.KUMIAI_KB_CD = '1' THEN 1 ELSE 0 END), 0) AS M2_KAZOKUTE  " . "\r\n";
        $strSql .= "FROM   JKSYAIN sy " . "\r\n";
        $strSql .= "LEFT JOIN JKSHIKYU si " . "\r\n";
        $strSql .= "ON   sy.SYAIN_NO = si.SYAIN_NO" . "\r\n";
        $strSql .= "AND    si.TAISYOU_YM = '@TAISYOU_YM' " . "\r\n";
        $strSql .= "AND    si.KS_KB = '1' " . "\r\n";
        $strSql .= "LEFT JOIN JKSONOTA so " . "\r\n";
        $strSql .= "ON     si.SYAIN_NO = so.SYAIN_NO" . "\r\n";
        $strSql .= "AND    si.TAISYOU_YM = so.TAISYOU_YM" . "\r\n";
        $strSql .= "AND    si.KS_KB = so.KS_KB" . "\r\n";
        $strSql .= "LEFT JOIN (SELECT SYAIN_NO " . "\r\n";
        $strSql .= "        FROM JKIDOURIREKI a " . "\r\n";
        $strSql .= "        WHERE ANNOUNCE_DT = (SELECT   MAX(ANNOUNCE_DT) " . "\r\n";
        $strSql .= "        FROM JKIDOURIREKI " . "\r\n";
        $strSql .= "        WHERE(a.SYAIN_NO = SYAIN_NO) " . "\r\n";
        $strSql .= "                             AND      ANNOUNCE_DT <= to_date('@TAISYOU_YMD', 'YYYY/MM/DD')) " . "\r\n";
        $strSql .= "                             GROUP BY SYAIN_NO " . "\r\n";
        $strSql .= "       ) id " . "\r\n";
        $strSql .= "ON     sy.SYAIN_NO = id.SYAIN_NO" . "\r\n";
        $strSql .= "LEFT JOIN (SELECT SYAIN_NO, BEF_KOYOU_KB_NM " . "\r\n";
        $strSql .= "        FROM JKKOYOURIREKI " . "\r\n";
        $strSql .= "        WHERE  BEF_NYUSYA_DT <= to_date('@TAISYOU_YMD', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= "        AND    NVL(BEF_TAISYOKU_DT, to_date( '9999/12/31','YYYY/MM/DD')) >= to_date('@TAISYOU_YMD', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= "        GROUP BY SYAIN_NO, BEF_KOYOU_KB_NM " . "\r\n";
        $strSql .= "       ) ko " . "\r\n";
        $strSql .= "ON    sy.SYAIN_NO = ko.SYAIN_NO" . "\r\n";
        $strSql .= " WHERE  NVL(sy.TAISYOKU_DT, to_date( '9999/12/31','YYYY/MM/DD')) >= to_date('@TAISYOU_YMD', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= " AND    sy.NYUSYA_DT <= TO_DATE('@TAISYOU_YMD','YYYY/MM/DD')" . "\r\n";
        $strSql .= " AND    (CASE WHEN ko.SYAIN_NO IS NOT NULL THEN ko.BEF_KOYOU_KB_NM ELSE sy.KOYOU_KB_NM END) NOT IN ('07','97') " . "\r\n";
        $strSql = str_replace("@TAISYOU_YMD", $DateTimePicker1 . "1231", $strSql);
        $strSql = str_replace("@TAISYOU_YM", $DateTimePicker1 . "12", $strSql);
        return $strSql;
    }

    //調査票2データ取得SQL
    public function fncCyousahyou2SQL($DateTimePicker1)
    {
        $strSQL = "";
        $strSQL .= " SELECT trunc((('@TAISYOU_YMD') - to_char(sy.BIRTHDAY,'yyyymmdd'))/10000,0) AS NENREI " . " \r\n";
        $strSQL .= "      , round(SUM(si.SHIKYU1) / SUM(1), 0) AS KIHONKYUU " . " \r\n";
        $strSQL .= "      , round(SUM(si.SHIKYU1) / SUM(1), 0) + round(SUM(si.SHIKYU2) / SUM(1), 0) + round(SUM(si.SHIKYU5) / SUM(1), 0) AS TEIJI " . " \r\n";
        $strSQL .= "      , SUM(1) AS NINZUU " . " \r\n";
        $strSQL .= "FROM   JKSYAIN sy" . " \r\n";
        $strSQL .= "LEFT JOIN JKSHIKYU si" . " \r\n";
        $strSQL .= "ON     sy.SYAIN_NO = si.SYAIN_NO" . " \r\n";
        $strSQL .= "AND    si.TAISYOU_YM = '@TAISYOU_YM' " . " \r\n";
        $strSQL .= "AND    si.KS_KB = '1' " . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT SYAIN_NO, BEF_KOYOU_KB_NM " . " \r\n";
        $strSQL .= "        FROM JKKOYOURIREKI " . " \r\n";
        $strSQL .= "        WHERE  BEF_NYUSYA_DT <= to_date('@TAISYOU_YMD', 'YYYY/MM/DD') " . " \r\n";
        $strSQL .= "        AND    NVL(BEF_TAISYOKU_DT, to_date( '9999/12/31','YYYY/MM/DD')) >= to_date('@TAISYOU_YMD', 'YYYY/MM/DD') " . " \r\n";
        $strSQL .= "        GROUP BY SYAIN_NO, BEF_KOYOU_KB_NM " . " \r\n";
        $strSQL .= "       ) ko " . " \r\n";
        $strSQL .= " ON    sy.SYAIN_NO = ko.SYAIN_NO" . " \r\n";
        $strSQL .= " WHERE  sy.KUMIAI_KB_CD = '1' " . " \r\n";
        $strSQL .= " AND    NVL(sy.TAISYOKU_DT, to_date( '9999/12/31','YYYY/MM/DD')) >= to_date('@TAISYOU_YMD', 'YYYY/MM/DD') " . " \r\n";
        $strSQL .= " AND    sy.NYUSYA_DT <= TO_DATE('@TAISYOU_YMD','YYYY/MM/DD')" . " \r\n";
        $strSQL .= " AND    (CASE WHEN ko.SYAIN_NO <> '' THEN ko.BEF_KOYOU_KB_NM ELSE sy.KOYOU_KB_NM END) NOT IN ('07','97')" . " \r\n";
        $strSQL .= " GROUP BY trunc((('@TAISYOU_YMD') - to_char(sy.BIRTHDAY,'yyyymmdd'))/10000,0) " . " \r\n";
        $strSQL .= " ORDER BY trunc((('@TAISYOU_YMD') - to_char(sy.BIRTHDAY,'yyyymmdd'))/10000,0) " . " \r\n";
        $strSQL = str_replace("@TAISYOU_YMD", $DateTimePicker1 . "0401", $strSQL);
        $strSQL = str_replace("@TAISYOU_YM", $DateTimePicker1 . "12", $strSQL);
        return $strSQL;
    }

    public function fncJinjiCtlMst()
    {
        $strSQL = $this->fncJinjiCtlMstSQL();
        return parent::select($strSQL);
    }

    public function fncCyousahyou1($DateTimePicker1)
    {
        $strSql = $this->fncCyousahyou1SQL($DateTimePicker1);
        return parent::select($strSql);
    }

    public function fncCyousahyou2($DateTimePicker1)
    {
        $strSQL = $this->fncCyousahyou2SQL($DateTimePicker1);
        return parent::select($strSQL);
    }

}
