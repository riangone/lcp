<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmLoginSel extends ClsComDb
{
    private $clsComFnc;
    public function fncHKEIRICTLSQL()
    {
        $strSQL = '';

        $strSQL = 'SELECT ID ';
        $strSQL .= ",      SYR_YMD || '01' TOUGETU";
        $strSQL .= '   FROM   HKEIRICTL';
        $strSQL .= "   WHERE  ID = '01'";

        return $strSQL;
    }

    public function getComboxListTableSQL()
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = '';

        $strSQL = "SELECT STYLE_ID,STYLE_NM FROM HMENUSTYLE";
        $strSQL .= "   WHERE SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '検索するSQL文
    // '**********************************************************************
    public function fncGetLoginInfoSQL($KJNBI, $SYAIN_NO, $PATTERN_ID)
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = '';

        $strSQL = ' SELECT ';
        $strSQL .= " TO_CHAR(SYA.SYAIN_NO) AS SYAIN_NO ";
        $strSQL .= ',SYA.SYAIN_NM ';
        $strSQL .= ',STYLE.STYLE_NM ';
        $strSQL .= ',PAT.PATTERN_NM ';
        $strSQL .= ",DECODE(LOG.USER_ID,'','未','済')  USER_ID";

        //ｼﾐｭﾚｰｼｮﾝｼｽﾃﾑ・経理ｼｽﾃﾑでログインを切り分けるためにｼｽﾃﾑ区分を追加したことによる変更
        $strSQL .= " FROM (SELECT * FROM M_LOGIN WHERE SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "') LOG";
        $strSQL .= ' , HSYAINMST SYA';
        $strSQL .= " , (SELECT * FROM HMENUSTYLE WHERE SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "') STYLE";
        $strSQL .= " , (SELECT * FROM HPATTERNMST WHERE SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "') PAT";
        $strSQL .= ' WHERE SYA.SYAIN_NO=LOG.USER_ID(+)';
        $strSQL .= ' AND LOG.STYLE_ID=STYLE.STYLE_ID(+)';
        $strSQL .= ' AND LOG.PATTERN_ID=PAT.PATTERN_ID(+)';
        $strSQL .= ' AND LOG.STYLE_ID=PAT.STYLE_ID(+) ';
        $strSQL .= " AND   NVL(SYA.TAISYOKU_DATE,'99999999') >= '@KJNBI'";
        if (strlen($SYAIN_NO) > 0) {
            //画面のユーザＩＤ
            $strSQL .= " AND SYA.SYAIN_NO = '@SYAIN_NO' ";
        }

        if (strlen($PATTERN_ID) > 0) {
            //画面で選択された所属ＩＤ
            $strSQL .= " AND LOG.STYLE_ID = '@PATTERN_ID' ";
        }

        $strSQL .= ' ORDER BY SYA.SYAIN_NO ';
        $strSQL = str_replace("@KJNBI", $KJNBI, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $SYAIN_NO, $strSQL);
        $strSQL = str_replace("@PATTERN_ID", $PATTERN_ID, $strSQL);

        return $strSQL;
    }

    public function fncHKEIRICTL()
    {
        $strsql = $this->fncHKEIRICTLSQL();
        return parent::select($strsql);
    }

    public function getComboxListTable()
    {
        $strsql = $this->getComboxListTableSQL();
        return parent::select($strsql);
    }

    public function fncGetLoginInfo($KJNBI, $SYAIN_NO, $PATTERN_ID)
    {
        $strsql = $this->fncGetLoginInfoSQL($KJNBI, $SYAIN_NO, $PATTERN_ID);
        return parent::select($strsql);
    }

}