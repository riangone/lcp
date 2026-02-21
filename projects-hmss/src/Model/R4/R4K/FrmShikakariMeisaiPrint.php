<?php
namespace App\Model\R4\R4K;

/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150827           #2091						   BUG                              li  　　
 * --------------------------------------------------------------------------------------------
 */
use App\Model\Component\ClsComDb;
use DateTime;

class FrmShikakariMeisaiPrint extends ClsComDb
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

    public function fncPrintMeisaiSQL($cboYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT V.SYADAIKATA" . "\r\n";
        $strSQL .= ",      V.CARNO" . "\r\n";
        $strSQL .= ",      V.GYOUSYA" . "\r\n";
        //---20150827 li UPD S.
        //$strSQL .= ",      V.SYORI_DT" . "\r\n";
        $strSQL .= ",      SUBSTR(V.SYORI_DT,1,6) SYORI_DT" . "\r\n";
        //---20150827 li UPD E.
        $strSQL .= ",      V.DENPY_NO" . "\r\n";
        $strSQL .= ",      V.GOUKEI" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= ",      SUBSTR('@SYURYOTUKI',1,4) || '.' || SUBSTR('@SYURYOTUKI',5,2) TUKI" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "		SELECT SZAI.SDAIKATA_CD SYADAIKATA" . "\r\n";
        $strSQL .= "		,      TRIM(TO_CHAR(KAI.HISSU_TEKYO3,'00000000')) CARNO" . "\r\n";
        $strSQL .= "		,      KAI.HISSU_TEKYO1 GYOUSYA" . "\r\n";
        $strSQL .= "		,      KAI.KEIJO_DT SYORI_DT" . "\r\n";
        $strSQL .= "		,      KAI.SIWAK_NO DENPY_NO" . "\r\n";
        $strSQL .= "		,      KAI.KEIJO_GK GOUKEI" . "\r\n";
        $strSQL .= "		FROM   M29F01 KAI" . "\r\n";
        $strSQL .= "		INNER JOIN HSHINSYAZAIKO ZAI" . "\r\n";
        $strSQL .= "		ON     KAI.HISSU_TEKYO3 = ZAI.HISSU_TEKYO3" . "\r\n";
        $strSQL .= "		LEFT JOIN M27A02 SZAI" . "\r\n";
        $strSQL .= "		ON     SZAI.CAR_NO = ZAI.HISSU_TEKYO3" . "\r\n";
        $strSQL .= "		AND    SZAI.HBSS_CD LIKE (ZAI.HISSU_TEKYO2 || '%')" . "\r\n";
        $strSQL .= "		WHERE  KAI.KEIJO_DT BETWEEN '@KAISHIBI' AND '@SYURYOBI'" . "\r\n";
        $strSQL .= "		AND    KAI.KAMOK_CD = '42112'" . "\r\n";
        $strSQL .= "		UNION ALL" . "\r\n";
        $strSQL .= "		SELECT SKK.SYADAIKATA" . "\r\n";
        $strSQL .= "		,      TRIM(TO_CHAR(SKK.CAR_NO,'00000000'))" . "\r\n";
        $strSQL .= "		,      (CASE WHEN SKK.HASEI_MOTO_KB = '0' THEN 'MAP' ELSE '（DZM）部販' END) GYOUSYA" . "\r\n";
        $strSQL .= "		,      SKK.SYORI_DT" . "\r\n";
        $strSQL .= "		,      '' DENPY_NO" . "\r\n";
        $strSQL .= "		,      NVL(SKK.KINGAKU,0)" . "\r\n";
        $strSQL .= "		FROM   HSHIKAKARI SKK" . "\r\n";
        $strSQL .= "		WHERE  EXISTS" . "\r\n";
        $strSQL .= "		       (SELECT * FROM HSHINSYAZAIKO ZAI" . "\r\n";
        $strSQL .= "		WHERE ZAI.HISSU_TEKYO3 = SKK.CAR_NO)" . "\r\n";
        $strSQL .= "		AND    SKK.NENGETU BETWEEN '@KAISHITUKI' AND '@SYURYOTUKI'" . "\r\n";
        $strSQL .= ") V" . "\r\n";
        $strSQL .= "ORDER BY SYADAIKATA, CARNO, SYORI_DT" . "\r\n";

        $date = new DateTime($cboYM . '01');
        $date->modify('-5 month');
        $KAISHIBI = $date->format('Ymd');
        $KAISHITUKI = $date->format('Ym');
        // echo cal_days_in_month(CAL_GREGORIAN, 2, 1965);
        // $number = cal_days_in_month(CAL_GREGORIAN, 8, 2003); // 31
        // echo "There were {$number} days in August 2003";
        // return '';
        // function days_in_month($month, $year)
        // {
        //     // calculate number of days in a month
        //     return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
        // }
        // $DaysInMonth = days_in_month(substr($cboYM, 4, 2), substr($cboYM, 0, 4));
        // $DaysInMonth = cal_days_in_month(CAL_GREGORIAN, substr($cboYM, 4, 2), substr($cboYM, 0, 4));
        $DaysInMonth = date("t", strtotime(substr($cboYM, 0, 4) . '-' . substr($cboYM, 4, 2)));

        $strSQL = str_replace("@KAISHIBI", $KAISHIBI, $strSQL);
        $strSQL = str_replace("@SYURYOBI", $cboYM . $DaysInMonth, $strSQL);
        $strSQL = str_replace("@KAISHITUKI", $KAISHITUKI, $strSQL);
        $strSQL = str_replace("@SYURYOTUKI", $cboYM, $strSQL);
        return $strSQL;
    }

    public function fncPrintMeisai($cboYM)
    {
        return parent::select($this->fncPrintMeisaiSQL($cboYM));
    }

}