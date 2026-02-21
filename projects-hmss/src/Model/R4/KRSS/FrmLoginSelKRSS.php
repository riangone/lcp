<?php
/**
 * 説明：
 *
 *
 * @author li
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;
class FrmLoginSelKRSS extends ClsComDb
{
    private $clsComFnc;
    /**
     * ログイン情報
     * @param {String}
     * @return {String} select文
     */
    //----execute----
    public function fncHKEIRICTL()
    {
        $sqlstr = $this->fncHKEIRICTL_sql();
        return parent::select($sqlstr);
    }

    public function fncHMENUSTYLE($strTougetu)
    {
        $sqlstr = $this->fncHMENUSTYLE_sql($strTougetu);
        return parent::select($sqlstr);
    }

    public function fncButton1Click($strTougetu, $UcUserID, $UcComboBox1, $UcUserID_len, $UcComBox1_len, $cboSysKB)
    {
        $sqlstr = $this->getsql($strTougetu, $UcUserID, $UcComboBox1, $UcUserID_len, $UcComBox1_len, $cboSysKB);
        //			$this -> log($sqlstr);
        return parent::select($sqlstr);

    }

    // public function m_select_frmLoginSel_Load()
    // {
    //     $str_sql = $this->frmLoginSel_Load();
    //     return parent::select($str_sql);
    // }

    public function getComboxListTable($tmpdata)
    {
        $strsql = $this->getComboxListTableSQL($tmpdata);
        return parent::select($strsql);
    }

    //----sql----
    public function fncHKEIRICTL_sql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",  SYR_YMD || '01' TOUGETU" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'" . "\r\n";
        return $strSQL;
    }

    public function fncHMENUSTYLE_sql($strTougetu)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYS_KB  " . "\r\n";
        $strSQL .= ",  STYLE_NM " . "\r\n";
        $strSQL .= ",  '" . $strTougetu . " ' TOUGETU " . "\r\n";
        $strSQL .= "FROM   HMENUSTYLE" . "\r\n";
        $strSQL .= "WHERE  SYS_KB IN  ('1','3','11') " . "\r\n";
        $strSQL .= "ORDER BY  to_number(SYS_KB) " . "\r\n";
        return $strSQL;
    }

    public function getSql($KJNBI, $SYAIN_NO, $PATTERN_ID, $UcUserID_len, $selected_len, $cboSysKB)
    {
        $strSQL = "";
        $strSQL .= "SELECT ";
        $strSQL .= " TO_CHAR(SYA.SYAIN_NO) AS SYAIN_NO ";
        $strSQL .= ",SYA.SYAIN_NM ";
        $strSQL .= ",STYLE.STYLE_NM ";
        $strSQL .= ",PAT.PATTERN_NM ";
        $strSQL .= ",DECODE(LOG.USER_ID,'','未','済') USER_ID ";
        $strSQL .= " FROM (SELECT * FROM M_LOGIN WHERE SYS_KB = '" . $cboSysKB . "') LOG";
        $strSQL .= " , HSYAINMST SYA";
        $strSQL .= " , (SELECT * FROM HMENUSTYLE WHERE SYS_KB = '" . $cboSysKB . "') STYLE";
        $strSQL .= " , (SELECT * FROM HPATTERNMST WHERE SYS_KB = '" . $cboSysKB . "') PAT";
        $strSQL .= " WHERE SYA.SYAIN_NO=LOG.USER_ID(+)";
        $strSQL .= " AND LOG.STYLE_ID=STYLE.STYLE_ID(+)";
        $strSQL .= " AND LOG.PATTERN_ID=PAT.PATTERN_ID(+)";
        $strSQL .= " 　AND LOG.STYLE_ID=PAT.STYLE_ID(+) ";
        $strSQL .= " AND   NVL(SYA.TAISYOKU_DATE,'99999999') >= '" . $KJNBI . "'";
        if ($UcUserID_len > 0) {
            $strSQL .= " AND SYA.SYAIN_NO = '" . $SYAIN_NO . "'";
        }
        ;
        if ($selected_len > 0) {
            //$strSQL .= " AND LOG.PATTERN_ID = '" . $PATTERN_ID."'";
            $strSQL .= " AND LOG.STYLE_ID = '" . $PATTERN_ID . "'";
        }
        ;
        $strSQL .= " ORDER BY SYA.SYAIN_NO ";
        return $strSQL;
    }

    private function getComboxListTableSQL($tmpdata)
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = "SELECT STYLE_ID,STYLE_NM FROM HMENUSTYLE";
        $strSQL .= "   WHERE SYS_KB = '" . $tmpdata . "'";

        return $strSQL;
    }

}
