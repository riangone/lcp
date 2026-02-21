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
 * 20160527			  #2529						依頼							  Yinhuaiyu
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmLoginEditKRSS extends ClsComDb
{
    private $clsComFnc;
    //----execute----
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

    public function getPatternID($UserID, $strTougetu, $cboSysKB)
    {
        $strsql = $this->getPatternIDSQL($UserID, $strTougetu, $cboSysKB);
        return parent::select($strsql);
    }

    public function SetPatternCombox($STYLE_ID, $cboSysKB)
    {
        $strsql = $this->SetPatternComboxSQL($STYLE_ID, $cboSysKB);
        return parent::select($strsql);
    }

    public function fncDelMst($USER_ID, $SYS_KB)
    {
        $strsql = $this->fncDelMstSQL($USER_ID, $SYS_KB);
        return parent::Do_Execute($strsql);
    }

    public function fncUpdMst($USER_ID, $PASSWORD, $REC_CRE_DT, $STYLE_ID, $PATTERN_ID, $UPDCLTNM, $SYS_KB)
    {
        $strsql = $this->fncUpdMstSQL($USER_ID, $PASSWORD, $REC_CRE_DT, $STYLE_ID, $PATTERN_ID, $UPDCLTNM, $SYS_KB);
        return parent::Do_Execute($strsql);
    }

    //----sql----
    function fncHKEIRICTLSQL()
    {
        $strSQL = "";

        $strSQL = "SELECT ID ";
        $strSQL .= ",      SYR_YMD || '01' TOUGETU";
        $strSQL .= "   FROM   HKEIRICTL";
        $strSQL .= "   WHERE  ID = '01'";

        return $strSQL;
    }

    function getComboxListTableSQL()
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = "";

        $strSQL = "SELECT STYLE_ID,STYLE_NM FROM HMENUSTYLE";
        $strSQL .= "   WHERE SYS_KB = '" . $_POST['data']['cboSysKB'] . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '検索するSQL文
    // '**********************************************************************
    function getPatternIDSQL($UserID, $strTougetu, $cboSysKB)
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = "";

        $strSQL = "	SELECT 	";
        $strSQL .= " SYA.SYAIN_NO";
        $strSQL .= ",SYA.SYAIN_NM";
        $strSQL .= ",LOG.PASSWORD";
        $strSQL .= ",STYLE.STYLE_NM	";
        $strSQL .= ",PAT.PATTERN_NM	";
        $strSQL .= ",STYLE.STYLE_ID	";
        $strSQL .= ",PAT.PATTERN_ID	";
        $strSQL .= ",LOG.REC_CRE_DT	";
        //ｼﾐｭﾚｰｼｮﾝｼｽﾃﾑと経理ｼｽﾃﾑのログイン切り分けのためメニュー関連にｼｽﾃﾑ区分を追加したことによる変更
        $strSQL .= " FROM (SELECT * FROM M_LOGIN WHERE SYS_KB = '" . $cboSysKB . "') LOG";
        $strSQL .= " , HSYAINMST SYA";
        $strSQL .= " , (SELECT * FROM HMENUSTYLE WHERE SYS_KB = '" . $cboSysKB . "') STYLE";
        $strSQL .= " , (SELECT * FROM HPATTERNMST WHERE SYS_KB = '" . $cboSysKB . "') PAT";
        $strSQL .= " WHERE SYA.SYAIN_NO=LOG.USER_ID(+)	";
        $strSQL .= " AND LOG.STYLE_ID=STYLE.STYLE_ID(+)	";
        $strSQL .= " AND LOG.STYLE_ID=PAT.STYLE_ID(+)	";
        $strSQL .= " AND LOG.PATTERN_ID=PAT.PATTERN_ID(+)	";
        $strSQL .= " AND   NVL(SYA.TAISYOKU_DATE,'99999999') >= '@KJNBI'";
        //frmLoginSelから渡ってきたパラメータ：ユーザＩＤ
        $strSQL .= " AND  SYA.SYAIN_NO= '@SYAIN_NO' ";

        $strSQL = str_replace("@SYAIN_NO", $UserID, $strSQL);
        $strSQL = str_replace("@KJNBI", $strTougetu, $strSQL);

        return $strSQL;
    }

    function SetPatternComboxSQL($STYLE_ID, $cboSysKB)
    {
        $strSQL = "";

        $strSQL = "SELECT PATTERN_ID, PATTERN_NM FROM HPATTERNMST WHERE STYLE_ID='" . $STYLE_ID . "'";
        $strSQL .= "   AND SYS_KB = '" . $cboSysKB . "'";
        //echo $strSQL;
        return $strSQL;
    }

    public function fncDelMstSQL($USER_ID, $SYS_KB)
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = "";

        $strSQL = "delete from M_LOGIN where user_id='" . $USER_ID . "'";
        $strSQL .= "   AND SYS_KB = '" . $SYS_KB . "'";

        return $strSQL;
    }

    public function fncUpdMstSQL($USER_ID, $PASSWORD, $REC_CRE_DT, $STYLE_ID, $PATTERN_ID, $UPDCLTNM, $SYS_KB)
    {
        $strSQL = "";
        $this->clsComFnc = new ClsComFnc();
        $UPDUSER = $this->clsComFnc->FncSqlNv($this->GS_LOGINUSER['strUserID']);

        $strSQL = "insert into M_LOGIN (";
        $strSQL .= " SYS_KB,";
        $strSQL .= " USER_ID,";
        $strSQL .= " PASSWORD,";
        $strSQL .= " STYLE_ID,";
        $strSQL .= " PATTERN_ID,";
        $strSQL .= " REC_UPD_DT,";
        $strSQL .= " REC_CRE_DT, ";
        $strSQL .= " UPD_SYA_CD,";
        $strSQL .= " UPD_PRG_ID,";
        $strSQL .= " UPD_CLT_NM ";
        $strSQL .= " ) values (";

        $strSQL .= " @SYS_KB,";
        $strSQL .= " @USER_ID,";
        $strSQL .= " @PASSWORD,";
        $strSQL .= " @STYLE_ID,";
        $strSQL .= " @PATTERN_ID,";
        $strSQL .= " sysdate,";
        $strSQL .= " @REC_CRE_DT,";
        $strSQL .= " @UPD_SYA_CD,";
        $strSQL .= " @UPD_PRG_ID,";
        $strSQL .= " @UPD_CLT_NM )";

        $strSQL = str_replace("@SYS_KB", $SYS_KB, $strSQL);
        $strSQL = str_replace("@USER_ID", $this->clsComFnc->FncSqlNv($USER_ID), $strSQL);
        $strSQL = str_replace("@PASSWORD", $this->clsComFnc->FncSqlNv($PASSWORD), $strSQL);
        $strSQL = str_replace("@STYLE_ID", $this->clsComFnc->FncSqlNv($STYLE_ID), $strSQL);
        $strSQL = str_replace("@PATTERN_ID", $this->clsComFnc->FncSqlNv($PATTERN_ID), $strSQL);

        if ($REC_CRE_DT == "0") {
            $strSQL = str_replace("@REC_CRE_DT", "sysdate", $strSQL);
        } else {
            $strSQL = str_replace("@REC_CRE_DT", "TO_DATE('" . $REC_CRE_DT . "','yyyy/MM/dd HH24:MI:ss')", $strSQL);
        }

        $strSQL = str_replace("@UPD_SYA_CD", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", $this->clsComFnc->FncSqlNv("frmLoginEdit"), $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->clsComFnc->FncSqlNv($UPDCLTNM), $strSQL);

        return $strSQL;
    }
}
