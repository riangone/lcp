<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmR2KAIKEI extends ClsComDb
{
    function fncRirekiDateSelectSQL()
    {
        $strSQL = "";

        $strSQL = "SELECT to_char(CSV_OUT_DT,'YYYY/MM/DD HH24:MI:SS') CSV_OUT_DT,count(*) CNT";
        $strSQL .= "  FROM R_CSV_KAIKEI";
        $strSQL .= " GROUP BY CSV_OUT_DT ";
        $strSQL .= " ORDER BY CSV_OUT_DT DESC";

        return $strSQL;
    }

    function fncLogUpdateSQL()
    {
        $strSQL = "";

        $strSQL = "   UPDATE  M_CONTROL";
        $strSQL .= "   SET LOCK_ID_7 = '0'";

        return $strSQL;
    }

    function fncUpdControlSQL()
    {
        $strSQL = "";

        $strSQL = "   UPDATE  M_CONTROL";
        $strSQL .= "   SET LOCK_ID_7 = '1'";

        return $strSQL;
    }

    public function fncRirekiDateSelect()
    {
        $strsql = $this->fncRirekiDateSelectSQL();
        return parent::select($strsql);
    }

    public function fncLogUpdate()
    {
        $strsql = $this->fncLogUpdateSQL();
        return parent::update($strsql);
    }

    public function fncUpdControl()
    {
        $strsql = $this->fncUpdControlSQL();
        return parent::update($strsql);
    }

}