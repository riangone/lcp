<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmJiKykTaTrkList extends ClsComDb
{
    public function selectsql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= ",      KISYU_YMD KISYU" . "\r\n";
        $strSQL .= ",      KI" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";
        return $strSQL;
    }

    public function fncSelect()
    {
        return parent::select($this->selectsql());
    }

    public function fncPrintSelectSQL($cboYMStart)
    {
        $strSQL = "";
        $strSQL .= "SELECT URI.HBSS_CD" . "\r\n";
        $strSQL .= ",      SUBSTR(URI.HBSS_CD,1,5) || SUBSTR(URI.HBSS_CD,8,1) SITEI_NO" . "\r\n";
        $strSQL .= ",      TRIM(URI.CARNO) CARNO" . "\r\n";
        $strSQL .= ",      REPLACE(REPLACE(KHS.HANSH_NM,'株式会社',''),'有限会社','') KYK_NM" . "\r\n";
        $strSQL .= ",      REPLACE(REPLACE(THS.HANSH_NM,'株式会社',''),'有限会社','') TRK_NM" . "\r\n";
        $strSQL .= ",      URI.UC_NO" . "\r\n";
        $strSQL .= ",      URI.URI_BUSYO_CD" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= ",      (URI.MGN_MEI_KNJ1 || ' ' || URI.MGN_MEI_KNJ2) MGN_NIN" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM   HSCURI_VW URI" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "LEFT JOIN M27M18 KHS" . "\r\n";
        $strSQL .= "ON     KHS.HANSH_CD = URI.KYK_HNS" . "\r\n";
        $strSQL .= "LEFT JOIN M27M18 THS" . "\r\n";
        $strSQL .= "ON     THS.HANSH_CD = URI.TOU_HNS" . "\r\n";
        $strSQL .= "WHERE  NVL(KYK_HNS,' ') <> NVL(TOU_HNS,' ')" . "\r\n";
        $strSQL .= "AND    KYK_HNS = '3634'" . "\r\n";
        $strSQL .= "AND    KEIJYO_YM = '@STARTMONTH'" . "\r\n";
        $strSQL .= "AND    UC_NO LIKE '@STARTMONTH%'" . "\r\n";
        $strSQL .= "ORDER BY  URI.TOU_HNS, URI.HBSS_CD, URI.CARNO" . "\r\n";

        $strSQL = str_replace("@STARTMONTH", $cboYMStart, $strSQL);
        return $strSQL;
    }

    public function fncPrintSelect($cboYMStart): array
    {
        return parent::select($this->fncPrintSelectSQL($cboYMStart));
    }

}