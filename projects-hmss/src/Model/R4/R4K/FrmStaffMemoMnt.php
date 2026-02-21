<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmStaffMemoMnt extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    public $ClsComFnc;

    function FncSqlNv2($objValue, $objReturn = "", $intKind = 1)
    {
        if ($objValue === null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "''";
            }
        } else {
            if ($objValue == "") {
                return "Null";
            } else {
                if ($intKind == 1) {
                    return "'" . str_replace("'", "''", $objValue) . "'";
                } else {
                    return str_replace("'", "''", $objValue);
                }
            }
        }
    }

    function fncInsertStaffMemoSql($postData = NULL, $value = NULL, $key = NULL)
    {
        $this->ClsComFnc = new ClsComFnc();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "StaffMemoMnt";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];

        $strSQL = "";

        $strSQL .= "INSERT INTO HSTAFFMEMO" . "\r\n";
        $strSQL .= "(      ID" . "\r\n";
        $strSQL .= ",      NAU_KB" . "\r\n";
        $strSQL .= ",      GYO_NO" . "\r\n";
        $strSQL .= ",      MEMO" . "\r\n";
        $strSQL .= ",      FONT_SIZE" . "\r\n";
        $strSQL .= ",      FONT_TYPE" . "\r\n";
        $strSQL .= ",      UPD_DATE" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM" . "\r\n";

        $strSQL .= ") VALUES ( " . "\r\n";
        $strSQL .= "       '@ID'" . "\r\n";
        $strSQL .= ",      '@NAUKB'" . "\r\n";
        $strSQL .= ",      @GYONO" . "\r\n";
        $strSQL .= ",      '@MEMO'" . "\r\n";
        $strSQL .= ",      '@FONTSIZE'" . "\r\n";
        $strSQL .= ",      '@FONTTYPE'" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      @CRE_DT" . "\r\n";
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";

        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@ID", "01", $strSQL);
        $strSQL = str_replace("@NAUKB", $postData['KB'], $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);

        $strSQL = str_replace("@GYONO", $key, $strSQL);
        $strSQL = str_replace("@MEMO", $this->ClsComFnc->FncNv(rtrim($value['MEMO'])), $strSQL);
        $FONTSIZE = $this->ClsComFnc->FncNz($value['FONT_SIZE']) == "0" ? "" : str_replace("pt", "", $this->ClsComFnc->FncNv($value['FONT_SIZE']));
        $FONTTYPE = $this->ClsComFnc->FncNz($value['FONT_TYPE']) == "0" ? "" : "1";
        $CRE_DT = rtrim($value['CREATE_DATE']) != "" ? "TO_DATE(" . $this->FncSqlNv2(rtrim($value['CREATE_DATE'])) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";
        $strSQL = str_replace("@FONTSIZE", $FONTSIZE, $strSQL);
        $strSQL = str_replace("@FONTTYPE", $FONTTYPE, $strSQL);
        $strSQL = str_replace("@CRE_DT", $CRE_DT, $strSQL);


        return $strSQL;
    }

    function fncDeleteStaffMemoSql($postData = NULL)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HSTAFFMEMO" . "\r\n";

        $strSQL .= "WHERE  NAU_KB = '@NAUKB'" . "\r\n";

        $strSQL = str_replace("@NAUKB", $postData['KB'], $strSQL);

        return $strSQL;
    }

    function fncStaffMemoSelectSql($postData = NULL)
    {

        $strSQL = "";

        $strSQL .= "SELECT MEMO" . "\r\n";

        $strSQL .= ",      (CASE WHEN NVL(FONT_SIZE,0) = 0 THEN '' ELSE FONT_SIZE || 'pt' END) FONT_SIZE" . "\r\n";

        $strSQL .= ",      NVL(FONT_TYPE,0) FONT_TYPE" . "\r\n";

        $strSQL .= ",     TO_CHAR(CREATE_DATE,'yyyy/MM/dd hh24:mi:ss') AS CREATE_DATE" . "\r\n";

        $strSQL .= "FROM   HSTAFFMEMO" . "\r\n";

        $strSQL .= " WHERE  ID = '01'" . "\r\n";

        $strSQL .= "AND    NAU_KB = '@KB'" . "\r\n";

        $strSQL .= "ORDER BY GYO_NO" . "\r\n";

        $strSQL = str_replace("@KB", $postData['KB'], $strSQL);

        return $strSQL;
    }

    public function fncStaffMemoSelect($postData = NULL)
    {
        $strSql = $this->fncStaffMemoSelectSql($postData);
        return parent::select($strSql);
    }

    public function fncDeleteStaffMemo($postData = NULL)
    {
        $strSql = $this->fncDeleteStaffMemoSql($postData);
        return parent::Do_Execute($strSql);
    }

    public function fncInsertStaffMemo($postData = NULL, $value = NULL, $key = NULL)
    {
        $strSql = $this->fncInsertStaffMemoSql($postData, $value, $key);
        return parent::Do_Execute($strSql);
    }

}