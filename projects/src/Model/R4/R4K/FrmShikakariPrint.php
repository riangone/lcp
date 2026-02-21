<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use DateTime;

class FrmShikakariPrint extends ClsComDb
{
    public function selectsql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";
        return $strSQL;
    }

    public function reselect()
    {
        return parent::select($this->selectsql());
    }

    public function fncPrintNewSQL($cboYMStart)
    {
        $strSQL = "";
        $strSQL .= "SELECT V.SYADAIKATA" . "\r\n";
        $strSQL .= ",      V.CARNO" . "\r\n";
        $strSQL .= ",      TRUNC((ROWNUM - 1) / 43) NEXTPAGE" . "\r\n";

        $strSQL .= ",      JPDATE(KRI.KIMATU_YMD) TODAY" . "\r\n";

        $strSQL .= ",      V.GOUKEI" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";

        $strSQL .= "        SELECT WK.SYADAIKATA" . "\r\n";
        $strSQL .= "        ,      WK.CARNO" . "\r\n";
        $strSQL .= "        ,      SUM(WK.GOUKEI) GOUKEI" . "\r\n";
        $strSQL .= "        FROM   (" . "\r\n";
        $strSQL .= "                SELECT MAX(SZAI.SDAIKATA_CD) SYADAIKATA" . "\r\n";
        $strSQL .= "                ,      TRIM(TO_CHAR(KAI.HISSU_TEKYO3,'00000000')) CARNO" . "\r\n";
        $strSQL .= "                ,      SUM(KAI.KEIJO_GK) GOUKEI" . "\r\n";
        $strSQL .= "                FROM   M29F01 KAI" . "\r\n";
        $strSQL .= "                INNER JOIN HSHINSYAZAIKO ZAI" . "\r\n";
        $strSQL .= "                ON     KAI.HISSU_TEKYO3 = ZAI.HISSU_TEKYO3" . "\r\n";
        $strSQL .= "                LEFT JOIN M27A02 SZAI" . "\r\n";
        $strSQL .= "                ON     SZAI.CAR_NO = ZAI.HISSU_TEKYO3" . "\r\n";
        $strSQL .= "                AND    SZAI.HBSS_CD LIKE (ZAI.HISSU_TEKYO2 || '%')" . "\r\n";
        $strSQL .= "                WHERE  KAI.KEIJO_DT BETWEEN '@KAISHIBI' AND '@SYURYOBI'" . "\r\n";
        $strSQL .= "                AND    KAI.KAMOK_CD = '42112'" . "\r\n";
        $strSQL .= "                GROUP BY KAI.HISSU_TEKYO3" . "\r\n";
        $strSQL .= "                UNION ALL" . "\r\n";
        $strSQL .= "                SELECT SKK.SYADAIKATA" . "\r\n";
        $strSQL .= "                ,      TRIM(TO_CHAR(SKK.CAR_NO,'00000000'))" . "\r\n";
        $strSQL .= "                ,      SUM(NVL(SKK.KINGAKU,0))" . "\r\n";
        $strSQL .= "                FROM   HSHIKAKARI SKK" . "\r\n";
        $strSQL .= "                WHERE  EXISTS" . "\r\n";
        $strSQL .= "                       (SELECT * FROM HSHINSYAZAIKO ZAI" . "\r\n";
        $strSQL .= "                        WHERE ZAI.HISSU_TEKYO3 = SKK.CAR_NO)" . "\r\n";
        $strSQL .= "                AND    SKK.NENGETU BETWEEN '@KAISHITUKI' AND '@SYURYOTUKI'" . "\r\n";
        $strSQL .= "                GROUP BY SKK.CAR_NO, SKK.SYADAIKATA" . "\r\n";
        $strSQL .= "        ) WK" . "\r\n";
        $strSQL .= "        GROUP BY WK.SYADAIKATA, WK.CARNO" . "\r\n";

        $strSQL .= ") V" . "\r\n";

        $strSQL .= "LEFT JOIN HKEIRICTL KRI" . "\r\n";
        $strSQL .= "ON        KRI.ID = '01'" . "\r\n";
        $strSQL .= "WHERE V.GOUKEI <> 0" . "\r\n";

        $date = new DateTime($cboYMStart . '01');
        $date->modify('-5 month');
        $KAISHIBI = $date->format('Ymd');
        $KAISHITUKI = $date->format('Ym');
        // $DaysInMonth = cal_days_in_month(CAL_GREGORIAN, substr($cboYMStart, 4, 2), substr($cboYMStart, 0, 4));
        $year = substr($cboYMStart, 0, 4);
        $month = substr($cboYMStart, 4, 2);
        $format = new DateTime("{$year}-{$month}-01");
        $DaysInMonth = $format->format('t');

        $strSQL = str_replace("@KAISHIBI", $KAISHIBI, $strSQL);
        $strSQL = str_replace("@SYURYOBI", $cboYMStart . $DaysInMonth, $strSQL);
        $strSQL = str_replace("@KAISHITUKI", $KAISHITUKI, $strSQL);
        $strSQL = str_replace("@SYURYOTUKI", $cboYMStart, $strSQL);

        return $strSQL;
    }

    public function fncPrintNew($cboYMStart)
    {
        return parent::select($this->fncPrintNewSQL($cboYMStart));
    }

    public function fncPrintOldSQL($cboYMStart)
    {
        $strSQL = "";
        $strSQL .= "SELECT V.SYADAIKATA" . "\r\n";
        $strSQL .= ",      V.CARNO" . "\r\n";
        $strSQL .= ",      TRUNC((ROW_NUMBER() OVER(ORDER BY V.CARNO) - 1) / 43) NEXTPAGE" . "\r\n";
        $strSQL .= ",      JPDATE(KRI.KIMATU_YMD) TODAY" . "\r\n";
        $strSQL .= ",      V.BUHIN" . "\r\n";
        $strSQL .= ",      V.GAICHU" . "\r\n";
        $strSQL .= ",      V.GOUKEI" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "		SELECT WK.SYADAIKATA" . "\r\n";
        $strSQL .= "		,      TRIM(TO_CHAR(WK.CAR_NO,'00000000')) CARNO" . "\r\n";
        $strSQL .= "		,      SUM(WK.BUHIN_DAI) BUHIN" . "\r\n";
        $strSQL .= "		,      SUM(WK.GAICHU_DAI) GAICHU" . "\r\n";
        $strSQL .= "		,      SUM(WK.WK_GOUKEI) GOUKEI" . "\r\n";
        $strSQL .= "		FROM   (" . "\r\n";
        $strSQL .= "				SELECT SSI.INP_DATE" . "\r\n";
        $strSQL .= "				,      SSI.EDA_NO" . "\r\n";
        $strSQL .= "				,      SSI.SYADAIKATA" . "\r\n";
        $strSQL .= "				,      SSI.CAR_NO" . "\r\n";
        $strSQL .= "				,      SSI.BUHIN_DAI" . "\r\n";
        $strSQL .= "				,      SSI.GAICHU_DAI" . "\r\n";
        $strSQL .= "				,      NVL(SSI.BUHIN_DAI,0) + NVL(SSI.GAICHU_DAI,0) WK_GOUKEI" . "\r\n";
        $strSQL .= "				FROM   HSAISEISYUKKO SSI" . "\r\n";
        $strSQL .= "                WHERE EXISTS" . "\r\n";
        $strSQL .= "                      (" . "\r\n";
        $strSQL .= "                        SELECT SEIRI_NO, SEIRI_SEQ" . "\r\n";
        $strSQL .= "		                FROM   M41B02 ZAI" . "\r\n";
        $strSQL .= "		                WHERE  NVL(URI_YMD,'99999999') > '@SYURYOBI'" . "\r\n";
        $strSQL .= "                        AND    SSI.CAR_NO = ZAI.CAR_NO" . "\r\n";
        $strSQL .= "		                GROUP BY CAR_NO" . "\r\n";
        $strSQL .= "		              )" . "\r\n";
        $strSQL .= "			    AND  SSI.INP_DATE BETWEEN '@KAISHIBI' AND '@SYURYOBI'" . "\r\n";
        $strSQL .= "		) WK" . "\r\n";
        $strSQL .= "		GROUP BY WK.SYADAIKATA" . "\r\n";
        $strSQL .= "		,        WK.CAR_NO" . "\r\n";
        $strSQL .= ") V" . "\r\n";

        $strSQL .= "LEFT JOIN HKEIRICTL KRI" . "\r\n";
        $strSQL .= "ON        KRI.ID = '01'" . "\r\n";
        $strSQL .= "WHERE V.GOUKEI <> 0" . "\r\n";

        $date = new DateTime($cboYMStart . '01');
        $date->modify('-5 month');
        $KAISHIBI = $date->format('Ymd');

        // $DaysInMonth = cal_days_in_month(CAL_GREGORIAN, substr($cboYMStart, 4, 2), substr($cboYMStart, 0, 4));
        $DaysInMonth = date("t", strtotime(substr($cboYMStart, 0, 4) . '-' . substr($cboYMStart, 4, 2)));

        $strSQL = str_replace("@KAISHIBI", $KAISHIBI, $strSQL);
        $strSQL = str_replace("@SYURYOBI", $cboYMStart . $DaysInMonth, $strSQL);

        return $strSQL;
    }

    public function fncPrintOld($cboYMStart)
    {
        return parent::select($this->fncPrintOldSQL($cboYMStart));
    }

}