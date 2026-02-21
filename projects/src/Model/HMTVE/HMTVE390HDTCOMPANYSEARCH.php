<?php
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
class HMTVE390HDTCOMPANYSEARCH extends ClsComDb
{
    public function DataGetSQL($txtComCode, $txtComName)
    {
        $strSQL = "";
        $strSQL .= " SELECT	COMPANY_CD," . "\r\n";
        $strSQL .= " COMPANY_NM" . "\r\n";
        $strSQL .= " FROM HDTCOMPANYMST" . "\r\n";
        $strSQL .= " WHERE 1=1" . "\r\n";
        if ($txtComCode != "") {
            $strSQL .= " AND	COMPANY_CD LIKE '" . $txtComCode . "%'" . "\r\n";
        }
        if ($txtComName != "") {
            $strSQL .= " AND	COMPANY_NM LIKE '" . $txtComName . "%'" . "\r\n";
        }
        $strSQL .= " ORDER BY COMPANY_CD";

        return parent::select($strSQL);
    }

}
