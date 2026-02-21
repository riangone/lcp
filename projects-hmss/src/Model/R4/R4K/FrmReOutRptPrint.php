<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmReOutRptPrint extends ClsComDb
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

    public function fncPrintSelectSQL($cboYMStart)
    {
        $strSQL = "";
        $strSQL .= "SELECT SAI.KBN" . "\r\n";
        $strSQL .= ",      SAI.SYADAIKATA" . "\r\n";
        $strSQL .= ",      SAI.CAR_NO" . "\r\n";
        $strSQL .= ",      NVL(SAI.BUHIN_DAI,0) BUHIN" . "\r\n";
        $strSQL .= ",      NVL(SAI.GAICHU_DAI,0) GAICHU" . "\r\n";
        $strSQL .= ",      NVL(SAI.KOUCHIN_DAI,0) KOUCHIN" . "\r\n";
        $strSQL .= ",      (NVL(SAI.BUHIN_DAI,0)" . "\r\n";
        $strSQL .= "       + NVL(SAI.GAICHU_DAI,0)" . "\r\n";
        $strSQL .= "       + NVL(SAI.KOUCHIN_DAI,0)) GOUKEI" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(SAI.INP_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') KANRYOBI" . "\r\n";
        $strSQL .= ",      '@TITLE_TUKI' TOUGETU" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM   HSAISEISYUKKO SAI" . "\r\n";
        $strSQL .= "WHERE  SUBSTR(SAI.INP_DATE,1,6) = '@INPDATE'" . "\r\n";
        $strSQL .= "ORDER BY SAI.KBN DESC" . "\r\n";
        $strSQL .= ",        SAI.SYADAIKATA" . "\r\n";
        $strSQL .= ",        SAI.CAR_NO" . "\r\n";

        $strSQL = str_replace("@INPDATE", $cboYMStart, $strSQL);
        $strSQL = str_replace("@TITLE_TUKI", $cboYMStart, $strSQL);
        return $strSQL;

    }

    public function fncPrintSelect($cboYMStart)
    {
        return parent::select($this->fncPrintSelectSQL($cboYMStart));
    }

}