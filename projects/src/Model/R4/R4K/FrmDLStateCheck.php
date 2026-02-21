<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmDLStateCheck extends ClsComDb
{
    function fncHFTS_TRANSFER_LIST_SelSQL($strDBLink)
    {
        $strSQL = "";
        $strSQL = "SELECT 'No' CHECK_FLAG";
        $strSQL .= ",       (ID || START_DATE || START_TIME || CLIENT_NAME) FILE_NM";
        $strSQL .= ",      SUBSTR(START_DATE,1,4) || '/' || SUBSTR(START_DATE,5,2) || '/' || SUBSTR(START_DATE,7,2) ||  ' ' || SUBSTR(START_TIME,1,2) || ':' || SUBSTR(START_TIME,3,2) || ':' || SUBSTR(START_TIME,5,2) DT";
        $strSQL .= ",      STEP";
        $strSQL .= ",      (CASE WHEN STATE = '0' THEN '実行中' ";
        $strSQL .= "             WHEN STATE = '1' THEN '終了'";
        $strSQL .= "             WHEN STATE = '8' THEN 'エラー' END) STATE";
        $strSQL .= ",      MESSAGE";
        $strSQL .= "   FROM   HFTS_TRANSFER_LIST@DBLINK";
        $strSQL .= "   WHERE  CLIENT_NAME = '@CLIENTNM'";
        $strSQL .= "   AND    KAKUNIN = '0'";
        $strSQL .= "   ORDER BY START_DATE DESC, START_TIME DESC";

        $strSQL = str_replace("@DBLINK", $strDBLink, $strSQL);
        //マシン名取得
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = str_replace("@CLIENTNM", $UPDCLTNM, $strSQL);

        return $strSQL;
    }

    function fncHFTS_TRANSFER_LIST_UpdSQL($postData, $strDBLink)
    {
        $strSQL = "";

        $strSQL = "UPDATE HFTS_TRANSFER_LIST@DBLINK";
        $strSQL .= "  SET    KAKUNIN = '1'";
        $strSQL .= "  WHERE  ID = '@ID'";
        $strSQL .= "  AND    START_DATE = '@STARTDATE'";
        $strSQL .= "  AND    START_TIME = '@STARTTIME'";
        $strSQL .= "  AND    CLIENT_NAME = '@CLIENTNM'";

        $strSQL = str_replace("@DBLINK", $strDBLink, $strSQL);
        $strSQL = str_replace("@ID", substr($postData['FILE_NM'], 0, 3), $strSQL);
        $strSQL = str_replace("@STARTDATE", substr($postData['FILE_NM'], 3, 8), $strSQL);
        $strSQL = str_replace("@STARTTIME", substr($postData['FILE_NM'], 11, 6), $strSQL);
        $strSQL = str_replace("@CLIENTNM", substr($postData['FILE_NM'], 17), $strSQL);

        return $strSQL;
    }

    public function fncHFTS_TRANSFER_LIST_Sel($strDBLink)
    {
        $strsql = $this->fncHFTS_TRANSFER_LIST_SelSQL($strDBLink);
        return parent::select($strsql);
    }

    public function fncHFTS_TRANSFER_LIST_Upd($postData, $strDBLink)
    {
        $strSql = $this->fncHFTS_TRANSFER_LIST_UpdSQL($postData, $strDBLink);
        return parent::Do_Execute($strSql);
    }

}