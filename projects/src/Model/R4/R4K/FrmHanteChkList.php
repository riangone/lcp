<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmHanteChkList extends ClsComDb
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

    public function fncSelect()
    {
        return parent::select($this->selectsql());
    }

    public function fncPrintSelectSQL($cboYMStart, $cboYMEnd)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYU.*,HHAIZOKU.BUSYO_CD FROM (" . "\r\n";
        $strSQL .= "SELECT (SUBSTR(URI.KRI_DATE,1,4) || '/' || SUBSTR(URI.KRI_DATE,5,2) || '/' || SUBSTR(URI.KRI_DATE,7,2)) KRI_DATE" . "\r\n";
        $strSQL .= "      ,URI.URI_GYOSYA" . "\r\n";
        $strSQL .= "      ,URI.GYO_NAME" . "\r\n";
        $strSQL .= "      ,URI.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,URI.UC_NO" . "\r\n";
        $strSQL .= "      ,(URI.MGN_MEI_KNJ1 || ' ' || URI.MGN_MEI_KNJ2) MGN_NIN" . "\r\n";
        $strSQL .= "      ,URI.CMN_NO" . "\r\n";
        $strSQL .= "      ,URI.HNB_TES_GKU" . "\r\n";
        $strSQL .= "      ,URI.HNB_SHZ" . "\r\n";
        $strSQL .= "      ,(NVL(URI.HNB_TES_GKU,0) + NVL(URI.HNB_SHZ,0)) HNB_TESURYO" . "\r\n";
        $strSQL .= "      ,SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "      ,KRI_DATE AS WHERE_KRI_DATE" . "\r\n";
        $strSQL .= "      ,KEIJYO_YM" . "\r\n";
        $strSQL .= "FROM  HSCURI_VW URI" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON    SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "         ) SYU " . "\r\n";
        $strSQL .= " LEFT JOIN HHAIZOKU " . "\r\n";
        $strSQL .= " ON    SYU.SYAIN_NO = HHAIZOKU.SYAIN_NO " . "\r\n";
        $strSQL .= " AND   HHAIZOKU.START_DATE <= SYU.WHERE_KRI_DATE " . "\r\n";
        $strSQL .= " AND   NVL(HHAIZOKU.END_DATE,'99999999') >= SYU.WHERE_KRI_DATE " . "\r\n";
        $strSQL .= "WHERE SYU.WHERE_KRI_DATE BETWEEN '@STARTDAY' AND '@ENDDAY'" . "\r\n";
        $strSQL .= "  AND NVL(HNB_TES_GKU,0) <> 0" . "\r\n";
        $strSQL .= "  AND KEIJYO_YM BETWEEN '@STARTMONTH' AND '@ENDMONTH'" . "\r\n";
        $strSQL .= "ORDER BY KRI_DATE, URI_GYOSYA" . "\r\n";
        $strSQL = str_replace("@STARTDAY", str_replace("/", "", $cboYMStart), $strSQL);
        $strSQL = str_replace("@ENDDAY", str_replace("/", "", $cboYMEnd), $strSQL);
        $strSQL = str_replace("@STARTMONTH", substr(str_replace("/", "", $cboYMStart), 0, 6), $strSQL);
        $strSQL = str_replace("@ENDMONTH", substr(str_replace("/", "", $cboYMEnd), 0, 6), $strSQL);
        return $strSQL;
    }

    public function fncPrintSelect($cboYMStart, $cboYMEnd): array
    {
        return parent::select($this->fncPrintSelectSQL($cboYMStart, $cboYMEnd));
    }

}