<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmFukushiUriList extends ClsComDb
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

    public function fncPrintSelectSQL($cboYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT (URI.KYK_MEI_KNJ1 || URI.KYK_MEI_KNJ2) OKYAKUSAMA" . "\r\n";
        $strSQL .= ",      SUBSTR(URI.TOU_DATE,5,2) || '.' || SUBSTR(URI.TOU_DATE,7,2) TOU_DATE" . "\r\n";
        $strSQL .= ",      (URI.RIKUJI_CD || ' ' || SUBSTR(URI.TOURK_NO1,5,4) || URI.TOURK_NO2 || URI.TOURK_NO3) TOURK_NO" . "\r\n";
        $strSQL .= ",      URI.TOA_NAME" . "\r\n";
        $strSQL .= ",      LTRIM(URI.CARNO) CAR_NO" . "\r\n";
        $strSQL .= ",      URI.UC_NO" . "\r\n";
        $strSQL .= ",      URI.URI_BUSYO_CD" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= ",      URI.SRY_PRC" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= ",      ('@NEN' || '/' || '@TUKI') TOUGETU" . "\r\n";
        $strSQL .= "FROM   HSCURI_VW URI" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "WHERE  URI.KYK_HNS = '17349'" . "\r\n";
        $strSQL .= "AND    URI.UC_NO LIKE '@NENGETU%'" . "\r\n";
        $strSQL .= "AND    URI.KEIJYO_YM LIKE '@NENGETU'" . "\r\n";
        $strSQL .= "ORDER BY URI.MGN_MEI_KN" . "\r\n";
        $strSQL = str_replace("@NENGETU", $cboYM, $strSQL);
        $strSQL = str_replace("@NEN", substr($cboYM, 0, 4), $strSQL);
        $strSQL = str_replace("@TUKI", substr($cboYM, 4, 2), $strSQL);
        return $strSQL;
    }

    public function fncPrintSelect($cboYM): array
    {
        return parent::select($this->fncPrintSelectSQL($cboYM));
    }

}