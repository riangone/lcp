<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
use Cake\Log\Log;
class FrmJKSYSLoginEdit extends ClsComDb
{
    public $ClsComFncJKSYS;
    //コントロールマスタ存在ﾁｪｯｸSQL
    public function fncHKEIRICTLSQL()
    {
        $strSQL = "";

        $strSQL = "SELECT ID ";
        $strSQL .= ",      SYORI_YM || '01' TOUGETU";
        $strSQL .= "   FROM   JKCONTROLMST";
        $strSQL .= "   WHERE  ID = '01'";

        return $strSQL;
    }

    // '**********************************************************************
    // '検索するSQL文
    // '**********************************************************************
    public function getPatternIDSQL($UserID, $strTougetu, $cboSysKB)
    {
        $strSQL = "";

        $strSQL = "	SELECT 	";
        $strSQL .= " SYA.SYAIN_NO";
        $strSQL .= ",SYA.SYAIN_NM";
        $strSQL .= ",LOG.PASSWORD";
        $strSQL .= ",PAT.PATTERN_NM	";
        $strSQL .= ",PAT.PATTERN_ID	";
        $strSQL .= ",TO_CHAR(LOG.REC_CRE_DT,'YYYY/MM/DD HH24:MI:SS') AS REC_CRE_DT";

        $strSQL .= " FROM (SELECT * FROM M_LOGIN WHERE SYS_KB = '" . $cboSysKB . "') LOG";
        $strSQL .= " , JKSYAIN SYA";
        $strSQL .= " , (SELECT * FROM HPATTERNMST WHERE SYS_KB = '" . $cboSysKB . "') PAT";

        $strSQL .= " WHERE SYA.SYAIN_NO=LOG.USER_ID(+)	";
        $strSQL .= " AND LOG.STYLE_ID(+)='001'	";
        $strSQL .= " AND PAT.STYLE_ID(+) = LOG.PATTERN_ID	";
        $strSQL .= " AND LOG.PATTERN_ID=PAT.PATTERN_ID(+)	";
//20251020 HD社員は退職扱いになっているので抽出条件から外す
//        $strSQL .= " AND   NVL(SYA.TAISYOKU_DT,'9999/12/31') >= TO_DATE('@KJNBI','YYYY/MM/DD')";
        $strSQL .= " AND  SYA.SYAIN_NO= '@SYAIN_NO' ";

        $strSQL = str_replace("@SYAIN_NO", $UserID, $strSQL);
        $strSQL = str_replace("@KJNBI", $strTougetu, $strSQL);

        return $strSQL;
    }

    // '**********************************************************************
    // 'パターンＩＤコンボボックスの項目に設定するSQL
    // '**********************************************************************
    public function SetPatternComboxSQL($STYLE_ID, $cboSysKB)
    {
        $strSQL = "";

        $strSQL = "SELECT PATTERN_ID, PATTERN_NM FROM HPATTERNMST WHERE STYLE_ID='" . $STYLE_ID . "'";
        $strSQL .= "   AND SYS_KB = '" . $cboSysKB . "'";
        return $strSQL;
    }

    public function fncDelMstSQL($USER_ID, $cboSysKB)
    {
        $strSQL = "";

        $strSQL = "delete from M_LOGIN where user_id='" . $USER_ID . "'";
        $strSQL .= "   AND SYS_KB = '" . $cboSysKB . "'";
        return $strSQL;
    }

    public function getLoginSql($postData)
    {
        $strSQL = "";

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

        $strSQL = str_replace("@SYS_KB", $postData['cboSysKB'], $strSQL);

        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = str_replace("@USER_ID", $this->ClsComFncJKSYS->FncSqlNv($postData['USER_ID']), $strSQL);
        $strSQL = str_replace("@PASSWORD", $this->ClsComFncJKSYS->FncSqlNv($postData['PASSWORD']), $strSQL);
        $strSQL = str_replace("@STYLE_ID", $this->ClsComFncJKSYS->FncSqlNv("001"), $strSQL);
        $strSQL = str_replace("@PATTERN_ID", $this->ClsComFncJKSYS->FncSqlNv($postData['PATTERN_ID']), $strSQL);

        if ($postData['REC_CRE_DT'] == "0") {
            $strSQL = str_replace("@REC_CRE_DT", "sysdate", $strSQL);
        } else {
            $strSQL = str_replace("@REC_CRE_DT", "TO_DATE('" . $postData['REC_CRE_DT'] . "','yyyy/MM/dd HH24:MI:ss')", $strSQL);
        }

        $strSQL = str_replace("@UPD_SYA_CD", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", $this->ClsComFncJKSYS->FncSqlNv("frmLoginEdit"), $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);

        return $strSQL;
    }

    //コントロールマスタ存在ﾁｪｯｸ
    public function fncHKEIRICTL()
    {
        $strsql = $this->fncHKEIRICTLSQL();
        return parent::select($strsql);
    }

    public function getPatternID($UserID, $strTougetu, $cboSysKB)
    {
        $strsql = $this->getPatternIDSQL($UserID, $strTougetu, $cboSysKB);
        return parent::select($strsql);
    }

    // '**********************************************************************
    // 'パターンＩＤコンボボックスの項目に設定する
    // '**********************************************************************
    public function SetPatternCombox($STYLE_ID, $cboSysKB)
    {
        $strsql = $this->SetPatternComboxSQL($STYLE_ID, $cboSysKB);
        return parent::select($strsql);
    }

    public function fncDelMst($USER_ID, $cboSysKB)
    {
        $strsql = $this->fncDelMstSQL($USER_ID, $cboSysKB);
        return parent::delete($strsql);
    }

    public function getLogin($postData)
    {
        $strsql = $this->getLoginSQL($postData);
        return parent::insert($strsql);
    }

}
