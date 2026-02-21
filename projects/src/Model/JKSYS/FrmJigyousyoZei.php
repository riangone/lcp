<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

class FrmJigyousyoZei extends ClsComDb
{
    public function FncGetJIGYOU($dtBirth, $intMonthTime)
    {
        $strsql = "";
        for ($intIdx = 0; $intIdx <= $intMonthTime; $intIdx++) {
            $strsql .= $this->FncGetJIGYOUSQL($dtBirth);
            if ($intIdx < $intMonthTime) {
                $strsql .= " UNION" . " \r\n";
                $dtBirth = date("Y/m/d", strtotime("+1 months", strtotime($dtBirth)));

            }
        }
        $strsql .= " GROUP BY DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_CD,JKB2.BUSYO_CD)," . " \r\n";
        $strsql .= "         DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_NM,JKB2.BUSYO_NM)," . " \r\n";
        $strsql .= "         JKB.BUSYO_NM," . " \r\n";
        $strsql .= "         JKS.SYAIN_NM," . " \r\n";
        $strsql .= "         JKK.KEISAN1 - JKSI.SHIKYU18" . " \r\n";
        $strsql .= " ORDER BY BUSYO_CD" . " \r\n";

        return parent::select($strsql);
    }

    public function FncGetJIGYOU2($dtBirth, $txtOld, $intMonthTime)
    {
        $strSQL = "";
        for ($intIdx = 0; $intIdx <= $intMonthTime; $intIdx++) {
            $strSQL .= $this->FncGetJIGYOU2SQL($dtBirth, $txtOld);
            if ($intIdx < $intMonthTime) {
                $strSQL .= "UNION" . " \r\n";
                $dtBirth = date("Y/m/d", strtotime("+1 months", strtotime($dtBirth)));

            }
        }
        $strSQL .= " GROUP BY DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_CD,JKB2.BUSYO_CD)," . " \r\n";
        $strSQL .= "         DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_NM,JKB2.BUSYO_NM)," . " \r\n";
        $strSQL .= "         JKB.BUSYO_NM," . " \r\n";
        $strSQL .= "         JKS.SYAIN_NM," . " \r\n";
        $strSQL .= "         JKK.KEISAN1 - JKSI.SHIKYU18" . " \r\n";
        $strSQL .= " ORDER BY BUSYO_CD,SYAIN_NM,KBN" . " \r\n";
        return parent::select($strSQL);
    }

    public function FncGetJKCMST()
    {
        $strSQL = "";
        $strSQL .= " SELECT KISYU_YMD" . " \r\n";
        $strSQL .= ",KIMATU_YMD" . " \r\n";
        $strSQL .= " FROM JKCONTROLMST JKC " . " \r\n";
        $strSQL .= " WHERE JKC.ID = '01'  " . " \r\n";
        return parent::select($strSQL);
    }

    public function FncGetJIGYOU2SQL($dtBirth, $txtOld)
    {
        $strSQL = "";
        $strSQL .= "SELECT" . " \r\n";
        $strSQL .= " DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_CD,JKB2.BUSYO_CD) BUSYO_CD" . " \r\n";
        $strSQL .= ",DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_NM,JKB2.BUSYO_NM) BUSYO_NM" . " \r\n";
        $strSQL .= ",JKS.SYAIN_NM SYAIN_NM" . " \r\n";
        $strSQL .= ",(JKK.KEISAN1 - JKSI.SHIKYU18) AS KYUUYO_KEI" . " \r\n";
        $strSQL .= ",SUBSTR('@GAITOU_YM',1,4) || SUBSTR('@GAITOU_YM',6,2) AS GAITOU_YM" . " \r\n";
        $strSQL .= ",'1' KBN" . " \r\n";
        $strSQL .= " FROM JKSYAIN JKS" . " \r\n";
        $strSQL .= " INNER JOIN (SELECT BUSYO_CD,SYAIN_NO,ANNOUNCE_DT" . " \r\n";
        $strSQL .= "            FROM JKIDOURIREKI A" . " \r\n";
        $strSQL .= "             WHERE ANNOUNCE_DT = " . " \r\n";
        $strSQL .= "                            (SELECT MAX(ANNOUNCE_DT)" . " \r\n";
        $strSQL .= "                             FROM JKIDOURIREKI " . " \r\n";
        $strSQL .= "                             WHERE ANNOUNCE_DT <= LAST_DAY(TO_DATE(SUBSTR('@GAITOU_YM',1,10),'yyyy/mm/dd'))" . " \r\n";
        $strSQL .= "                             AND A.SYAIN_NO = SYAIN_NO" . " \r\n";
        $strSQL .= "                             GROUP BY SYAIN_NO" . " \r\n";
        $strSQL .= "                             )" . " \r\n";
        $strSQL .= "           ) JKI" . " \r\n";
        $strSQL .= " ON JKS.SYAIN_NO = JKI.SYAIN_NO" . " \r\n";
        $strSQL .= " INNER JOIN JKBUMON JKB " . " \r\n";
        $strSQL .= " ON  JKB.BUSYO_CD = JKI.BUSYO_CD" . " \r\n";
        $strSQL .= " AND JKB.ADDRESS1 LIKE '%（GD）市%'" . " \r\n";
        $strSQL .= " LEFT JOIN JKBUMON JKB2 " . " \r\n";
        $strSQL .= " ON  JKB2.BUSYO_CD = (SUBSTR(JKI.BUSYO_CD,1,2) || '0')" . " \r\n";
        $strSQL .= "  AND JKB2.ADDRESS1 LIKE '%（GD）市%'" . " \r\n";
        $strSQL .= " INNER JOIN JKKEISAN JKK " . " \r\n";
        $strSQL .= " ON  JKI.SYAIN_NO = JKK.SYAIN_NO" . " \r\n";
        $strSQL .= "  AND JKK.TAISYOU_YM = SUBSTR('@GAITOU_YM',1,4) || SUBSTR('@GAITOU_YM',6,2)" . " \r\n";
        $strSQL .= " INNER JOIN JKSHIKYU JKSI" . " \r\n";
        $strSQL .= " ON  JKK.TAISYOU_YM = JKSI.TAISYOU_YM " . " \r\n";
        $strSQL .= "  AND JKK.SYAIN_NO = JKSI.SYAIN_NO" . " \r\n";
        $strSQL .= "  AND JKK.KS_KB = JKSI.KS_KB " . " \r\n";
        $strSQL .= " WHERE JKS.HANDI_KB_CD <> '0'" . " \r\n";
        $strSQL .= "  AND JKS.KOYOU_KB_CD <> '07'" . " \r\n";
        $strSQL .= "  AND (JKS.KOYOU_KB_CD < '09' OR JKS.KOYOU_KB_CD = '3A') " . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '019'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '166'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '167'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '168'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '169'" . " \r\n";
        $strSQL .= " UNION" . " \r\n";
        $strSQL .= " SELECT" . " \r\n";
        $strSQL .= " DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_CD,JKB2.BUSYO_CD) BUSYO_CD" . " \r\n";
        $strSQL .= ",DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_NM,JKB2.BUSYO_NM) BUSYO_NM" . " \r\n";
        $strSQL .= ",JKS.SYAIN_NM SYAIN_NM" . " \r\n";
        $strSQL .= ",(JKK.KEISAN1 - JKSI.SHIKYU18) AS KYUUYO_KEI" . " \r\n";
        $strSQL .= ",SUBSTR('@GAITOU_YM',1,4) || SUBSTR('@GAITOU_YM',6,2) AS GAITOU_YM" . " \r\n";
        $strSQL .= ",'2' KBN" . " \r\n";
        $strSQL .= " FROM JKSYAIN JKS" . " \r\n";
        $strSQL .= " INNER JOIN (SELECT BUSYO_CD,SYAIN_NO,ANNOUNCE_DT" . " \r\n";
        $strSQL .= "            FROM JKIDOURIREKI A" . " \r\n";
        $strSQL .= "            WHERE ANNOUNCE_DT = " . " \r\n";
        $strSQL .= "	                            (SELECT MAX(ANNOUNCE_DT)" . " \r\n";
        $strSQL .= "                             FROM JKIDOURIREKI" . " \r\n";
        $strSQL .= "                             WHERE ANNOUNCE_DT <= LAST_DAY(TO_DATE(SUBSTR('@GAITOU_YM',1,10),'yyyy/mm/dd'))" . " \r\n";
        $strSQL .= "                             AND A.SYAIN_NO = SYAIN_NO" . " \r\n";
        $strSQL .= "                            GROUP BY SYAIN_NO" . " \r\n";
        $strSQL .= "                          )" . " \r\n";
        $strSQL .= "          ) JKI" . " \r\n";
        $strSQL .= " ON JKS.SYAIN_NO = JKI.SYAIN_NO" . " \r\n";
        $strSQL .= " INNER JOIN JKBUMON JKB " . " \r\n";
        $strSQL .= " ON JKB.BUSYO_CD = JKI.BUSYO_CD" . " \r\n";
        $strSQL .= "  AND JKB.ADDRESS1 LIKE '%（GD）市%'" . " \r\n";
        $strSQL .= " LEFT  JOIN JKBUMON JKB2" . " \r\n";
        $strSQL .= " ON  JKB2.BUSYO_CD = (SUBSTR(JKI.BUSYO_CD,1,2) || '0')" . " \r\n";
        $strSQL .= "  AND  JKB2.ADDRESS1 LIKE '%（GD）市%'" . " \r\n";
        $strSQL .= " INNER JOIN JKKEISAN JKK " . " \r\n";
        $strSQL .= " ON  JKI.SYAIN_NO = JKK.SYAIN_NO" . " \r\n";
        $strSQL .= "  AND  JKK.TAISYOU_YM = SUBSTR('@GAITOU_YM',1,4) || SUBSTR('@GAITOU_YM',6,2)" . " \r\n";
        $strSQL .= "  AND  JKK.KS_KB = '1'" . " \r\n";
        $strSQL .= " INNER JOIN JKSHIKYU JKSI " . " \r\n";
        $strSQL .= " ON  JKK.TAISYOU_YM = JKSI.TAISYOU_YM" . " \r\n";
        $strSQL .= "  AND  JKK.SYAIN_NO = JKSI.SYAIN_NO " . " \r\n";
        $strSQL .= "  AND  JKK.KS_KB = JKSI.KS_KB " . " \r\n";
        $strSQL .= " WHERE @NENREI <= TRUNC(" . " \r\n";
        $strSQL .= "                 MONTHS_BETWEEN(" . " \r\n";
        $strSQL .= "                             LAST_DAY(TO_DATE(SUBSTR('@GAITOU_YM',1,10),'yyyy/mm/dd'))," . " \r\n";
        $strSQL .= "                             JKS.BIRTHDAY)" . " \r\n";
        $strSQL .= "                             / 12" . " \r\n";
        $strSQL .= "                 )" . " \r\n";
        $strSQL .= "  AND JKS.HANDI_KB_CD = '0'" . " \r\n";
        $strSQL .= "  AND JKS.KOYOU_KB_CD <> '07'" . " \r\n";
        $strSQL .= "  AND (JKS.KOYOU_KB_CD < '09' OR JKS.KOYOU_KB_CD = '3A') " . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '019'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '166'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '167'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '168'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '169'" . " \r\n";
        $strSQL .= " UNION" . " \r\n";
        $strSQL .= " SELECT" . " \r\n";
        $strSQL .= " DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_CD,JKB2.BUSYO_CD) BUSYO_CD" . " \r\n";
        $strSQL .= ",DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_NM,JKB2.BUSYO_NM) BUSYO_NM" . " \r\n";
        $strSQL .= ",JKS.SYAIN_NM SYAIN_NM" . " \r\n";
        $strSQL .= ",(JKK.KEISAN1 - JKSI.SHIKYU18) AS KYUUYO_KEI" . " \r\n";
        $strSQL .= ",SUBSTR('@GAITOU_YM',1,4) || SUBSTR('@GAITOU_YM',6,2) AS GAITOU_YM" . " \r\n";
        $strSQL .= ",'2' KBN" . " \r\n";
        $strSQL .= " FROM JKSYAIN JKS" . " \r\n";
        $strSQL .= " INNER JOIN (SELECT BUSYO_CD,SYAIN_NO,ANNOUNCE_DT" . " \r\n";
        $strSQL .= "           FROM JKIDOURIREKI A" . " \r\n";
        $strSQL .= "           WHERE ANNOUNCE_DT = " . " \r\n";
        $strSQL .= "                           (SELECT MAX(ANNOUNCE_DT)" . " \r\n";
        $strSQL .= "                            FROM JKIDOURIREKI " . " \r\n";
        $strSQL .= "                            WHERE ANNOUNCE_DT <= LAST_DAY(TO_DATE(SUBSTR('@GAITOU_YM',1,10),'yyyy/mm/dd'))" . " \r\n";
        $strSQL .= "                            AND A.SYAIN_NO = SYAIN_NO" . " \r\n";
        $strSQL .= "                            GROUP BY SYAIN_NO" . " \r\n";
        $strSQL .= "                           )" . " \r\n";
        $strSQL .= "            ) JKI" . " \r\n";
        $strSQL .= " ON JKS.SYAIN_NO = JKI.SYAIN_NO" . " \r\n";
        $strSQL .= " INNER JOIN JKBUMON JKB " . " \r\n";
        $strSQL .= " ON JKB.BUSYO_CD = JKI.BUSYO_CD" . " \r\n";
        $strSQL .= "  AND JKB.ADDRESS1 LIKE '%（GD）市%'" . " \r\n";
        $strSQL .= " LEFT  JOIN JKBUMON JKB2" . " \r\n";
        $strSQL .= " ON  JKB2.BUSYO_CD = (SUBSTR(JKI.BUSYO_CD,1,2) || '0')" . " \r\n";
        $strSQL .= "  AND  JKB2.ADDRESS1 LIKE '%（GD）市%'" . " \r\n";
        $strSQL .= " INNER JOIN JKKEISAN JKK " . " \r\n";
        $strSQL .= " ON  JKI.SYAIN_NO = JKK.SYAIN_NO" . " \r\n";
        $strSQL .= "  AND  JKK.TAISYOU_YM = SUBSTR('@GAITOU_YM',1,4) || SUBSTR('@GAITOU_YM',6,2)" . " \r\n";
        $strSQL .= "  AND  JKK.KS_KB = '2' " . " \r\n";
        $strSQL .= " INNER JOIN JKSHIKYU JKSI" . " \r\n";
        $strSQL .= " ON  JKK.TAISYOU_YM = JKSI.TAISYOU_YM " . " \r\n";
        $strSQL .= "  AND  JKK.SYAIN_NO = JKSI.SYAIN_NO" . " \r\n";
        $strSQL .= "  AND  JKK.KS_KB = JKSI.KS_KB " . " \r\n";
        $strSQL .= " LEFT  JOIN JKSONOTA JKSNT" . " \r\n";
        $strSQL .= " ON  JKSI.TAISYOU_YM = JKSNT.TAISYOU_YM " . " \r\n";
        $strSQL .= "  AND  JKSI.SYAIN_NO = JKSNT.SYAIN_NO" . " \r\n";
        $strSQL .= "  AND  JKSI.KS_KB = JKSNT.KS_KB" . " \r\n";
        $strSQL .= "  AND  JKSI.KS_KB = JKSNT.KS_KB" . " \r\n";
        $strSQL .= " WHERE @NENREI <= TRUNC(" . " \r\n";
        $strSQL .= "                  MONTHS_BETWEEN(" . " \r\n";
        $strSQL .= "                             DECODE(JKSNT.SONOTA1,NULL,LAST_DAY(TO_DATE(SUBSTR('@GAITOU_YM',1,10),'yyyy/mm/dd'))," . " \r\n";
        $strSQL .= "                             JKSNT.SONOTA1)," . " \r\n";
        $strSQL .= "                             JKS.BIRTHDAY )" . " \r\n";
        $strSQL .= "                  ) / 12" . " \r\n";
        $strSQL .= "  AND JKS.HANDI_KB_CD = '0'" . " \r\n";
        $strSQL .= "  AND JKS.KOYOU_KB_CD <> '07'" . " \r\n";
        $strSQL .= "  AND (JKS.KOYOU_KB_CD < '09' OR JKS.KOYOU_KB_CD = '3A') " . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '019'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '166' " . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '167'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '168'" . " \r\n";
        $strSQL .= "  AND JKI.BUSYO_CD <> '169'" . " \r\n";
        $strSQL = str_replace("@GAITOU_YM", $dtBirth, $strSQL);
        $strSQL = str_replace("@NENREI", $txtOld, $strSQL);
        return $strSQL;
    }

    public function FncGetJIGYOUSQL($dtBirth)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= " DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_CD,JKB2.BUSYO_CD) BUSYO_CD" . "\r\n";
        $strSql .= ",DECODE(JKB2.BUSYO_CD,null,JKB.BUSYO_NM,JKB2.BUSYO_NM) BUSYO_NM" . "\r\n";
        $strSql .= ",JKS.SYAIN_NM SYAIN_NM" . "\r\n";
        $strSql .= ",(JKK.KEISAN1 - JKSI.SHIKYU18) AS KYUUYO_KEI_A" . "\r\n";
        $strSql .= ",SUBSTR('@GAITOU_YM',1,4) || SUBSTR('@GAITOU_YM',6,2) AS GAITOU_YM" . "\r\n";
        $strSql .= " FROM JKSYAIN JKS" . "\r\n";
        $strSql .= " INNER JOIN (SELECT BUSYO_CD,SYAIN_NO,ANNOUNCE_DT" . "\r\n";
        $strSql .= "             FROM JKIDOURIREKI A" . "\r\n";
        $strSql .= "             WHERE ANNOUNCE_DT = " . "\r\n";
        $strSql .= "                            (SELECT MAX(ANNOUNCE_DT)" . "\r\n";
        $strSql .= "                              FROM JKIDOURIREKI  " . "\r\n";
        $strSql .= "                              WHERE ANNOUNCE_DT <= LAST_DAY(TO_DATE(SUBSTR('@GAITOU_YM',1,10),'yyyy/mm/dd'))" . "\r\n";
        $strSql .= "                             AND A.SYAIN_NO = SYAIN_NO" . "\r\n";
        $strSql .= "                             GROUP BY SYAIN_NO " . "\r\n";
        $strSql .= "                            )" . "\r\n";
        $strSql .= "            ) JKI" . "\r\n";

        $strSql .= " ON JKS.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        $strSql .= " INNER JOIN JKBUMON JKB  " . "\r\n";
        $strSql .= " ON  JKB.BUSYO_CD = JKI.BUSYO_CD" . "\r\n";
        $strSql .= "  AND JKB.ADDRESS1 LIKE '%（GD）市%'" . "\r\n";
        $strSql .= " LEFT JOIN JKBUMON JKB2  " . "\r\n";
        $strSql .= " ON  JKB2.BUSYO_CD = (SUBSTR(JKI.BUSYO_CD,1,2) || '0')" . "\r\n";

        $strSql .= "  AND JKB2.ADDRESS1 LIKE '%（GD）市%'" . "\r\n";
        $strSql .= " INNER JOIN JKKEISAN JKK  " . "\r\n";
        $strSql .= " ON  JKI.SYAIN_NO = JKK.SYAIN_NO " . "\r\n";
        $strSql .= "  AND JKK.TAISYOU_YM = SUBSTR('@GAITOU_YM',1,4) || SUBSTR('@GAITOU_YM',6,2)" . "\r\n";
        $strSql .= " INNER JOIN JKSHIKYU JKSI" . "\r\n";
        $strSql .= " ON  JKK.TAISYOU_YM = JKSI.TAISYOU_YM " . "\r\n";
        $strSql .= "  AND JKK.SYAIN_NO = JKSI.SYAIN_NO " . "\r\n";

        $strSql .= "  AND JKK.KS_KB = JKSI.KS_KB " . "\r\n";
        $strSql .= " WHERE (JKS.KOYOU_KB_CD < '09' OR JKS.KOYOU_KB_CD = '3A') " . "\r\n";
        $strSql .= "  AND JKI.BUSYO_CD <> '019' " . "\r\n";
        $strSql .= "  AND JKI.BUSYO_CD <> '166'  " . "\r\n";
        $strSql .= "  AND JKI.BUSYO_CD <> '167' " . "\r\n";
        $strSql .= "  AND JKI.BUSYO_CD <> '168' " . "\r\n";
        $strSql .= "  AND JKI.BUSYO_CD <> '169'" . "\r\n";
        $strSql .= "  AND JKS.KOYOU_KB_CD <> '07'" . "\r\n";
        $strSql = str_replace("@GAITOU_YM", $dtBirth, $strSql);
        return $strSql;
    }

}
