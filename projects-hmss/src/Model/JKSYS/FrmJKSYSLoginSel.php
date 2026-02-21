<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use Cake\Log\Log;

class FrmJKSYSLoginSel extends ClsComDb
{
    //コントロールマスタ存在ﾁｪｯｸSQL
    function frmLoginSel_LoadSQL()
    {
        $strSQL = "";

        $strSQL = " SELECT ID ";
        $strSQL .= "      ,SYORI_YM || '01' TOUGETU";
        $strSQL .= "   FROM   JKCONTROLMST";
        $strSQL .= "   WHERE  ID = '01'";

        return $strSQL;
    }

    // '**********************************************************************
    // '検索するSQL文
    // '**********************************************************************
    function getCarrySQL($KJNBI, $SYAIN_NO, $cboSysKB)
    {
        $strsql = "";

        $strsql = " SELECT ";
        $strsql .= " TO_CHAR(SYA.SYAIN_NO) AS SYAIN_NO ";
        $strsql .= ",SYA.SYAIN_NM ";
        $strsql .= ",NULL STYLE_NM ";
        $strsql .= ",PAT.PATTERN_NM ";
        $strsql .= ",DECODE(LOG.USER_ID,'','未','済')  USER_ID";

        $strsql .= " FROM (SELECT * FROM M_LOGIN WHERE SYS_KB = '" . $cboSysKB . "') LOG ";
        $strsql .= "     ,JKSYAIN SYA ";
        $strsql .= "     ,(SELECT * FROM HPATTERNMST WHERE SYS_KB = '" . $cboSysKB . "') PAT ";
        $strsql .= " WHERE SYA.SYAIN_NO=LOG.USER_ID(+) ";
        $strsql .= "     AND LOG.STYLE_ID(+)='001' ";
        $strsql .= "     AND PAT.PATTERN_ID(+) = LOG.PATTERN_ID ";
        $strsql .= "     AND LOG.STYLE_ID=PAT.STYLE_ID(+)  ";
//20251020 UPDATE HD社員は退職扱いになっているので抽出条件から外す
//        $strsql .= "    AND   NVL(SYA.TAISYOKU_DT,'9999/12/31') >= TO_DATE('@KJNBI','YYYY/MM/DD') ";

        //画面初期化以外
        if ($SYAIN_NO != NULL || $SYAIN_NO != "") {
            $strsql .= "     AND SYA.SYAIN_NO  = '@SYAIN_NO' ";
        }
        $strsql .= " ORDER BY SYA.SYAIN_NO ";
        $strsql = str_replace("@KJNBI", $KJNBI, $strsql);
        $strsql = str_replace("@SYAIN_NO", $SYAIN_NO, $strsql);
//Log::debug($strsql);
        return $strsql;
    }

    //コントロールマスタ存在ﾁｪｯｸ
    public function frmLoginSel_Load()
    {
        $strsql = $this->frmLoginSel_LoadSQL();
        return parent::select($strsql);
    }

    //検索するSQL文
    public function getCarry($KJNBI, $SYAIN_NO, $SYS_KB)
    {
        $strsql = $this->getCarrySQL($KJNBI, $SYAIN_NO, $SYS_KB);
        return parent::select($strsql);
    }

}
