<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmProgramSearch extends ClsComDb
{
    private $clsComFnc;
    function fncHPROGRAMMSTSelSQL($PRO_NM)
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = '';

        $strSQL = 'SELECT PRO_NO';
        $strSQL .= ' ,      PRO_NM';
        $strSQL .= ' FROM HPROGRAMMST';
        $strSQL .= " WHERE  SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";

        //---科目名---
        if (trim($PRO_NM) != '') {
            $strSQL .= " AND    PRO_NM LIKE '@KAMOK%'";
        }

        $strSQL .= ' ORDER BY PRO_NO';
        $strSQL = str_replace('@KAMOK', $this->clsComFnc->FncNv($PRO_NM), $strSQL);

        return $strSQL;
    }

    public function fncHPROGRAMMSTSel($PRO_NM)
    {
        $strsql = $this->fncHPROGRAMMSTSelSQL($PRO_NM);
        return parent::select($strsql);
    }

}