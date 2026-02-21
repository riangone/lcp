<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

class FrmPassMente extends ClsComDb
{
    //プログラムマスタ取得SQL
    public function fncGetPGMSTSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT TO_CHAR(PRO_NO) AS PRO_NO," . " \r\n";
        $strSQL .= "        PRO_NM" . " \r\n";
        $strSQL .= " FROM HPROGRAMMST" . " \r\n";
        $strSQL .= " ORDER BY TO_NUMBER(PRO_NO)" . " \r\n";
        return parent::select($strSQL);
    }

    //パスワードマスタ存在チェック
    public function mSonzaiCheck($cmbPGNM)
    {
        $strSQL = "";
        $strSQL .= " SELECT PRO_NO" . " \r\n";
        $strSQL .= " FROM JKPASSMST" . " \r\n";
        $strSQL .= " WHERE PRO_NO = @PRO_NO" . " \r\n";
        $strSQL = str_replace("@PRO_NO", $cmbPGNM, $strSQL);

        return parent::select($strSQL);
    }

    //パスワード取得
    public function fncGetPass($cmbPGNM)
    {
        $strSQL = "";
        $strSQL .= " SELECT PASS" . " \r\n";
        $strSQL .= " FROM   JKPASSMST" . " \r\n";
        $strSQL .= " WHERE PRO_NO = @PRO_NO" . " \r\n";
        $strSQL = str_replace("@PRO_NO", $cmbPGNM, $strSQL);

        return parent::select($strSQL);
    }

    //パスワードマスタデータ删除SQL
    public function fncDelPassSQL($cmbPGNM)
    {
        $strSQL = "";
        $strSQL .= " DELETE JKPASSMST" . " \r\n";
        $strSQL .= " WHERE PRO_NO = @PRO_NO" . " \r\n";
        $strSQL = str_replace("@PRO_NO", $cmbPGNM, $strSQL);

        return parent::delete($strSQL);
    }

    //パスワードマスタデータ更新SQL
    public function fncUpdPassSQL($cmbPGNM, $txtPass1)
    {
        $strSQL = "";
        $strSQL .= " UPDATE JKPASSMST" . "\r\n";
        $strSQL .= " SET PASS = '@PASS'," . "\r\n";
        $strSQL .= "     UPD_DATE = SYSDATE," . " \r\n";
        $strSQL .= "     UPD_SYA_CD = '@SYA_CD'," . " \r\n";
        $strSQL .= "     UPD_PRG_ID = '@PRG_ID'," . "\r\n";
        $strSQL .= "     UPD_CLT_NM = '@CLT_NM'" . " \r\n";
        $strSQL .= " WHERE PRO_NO = @PRO_NO" . "\r\n";

        $strSQL = str_replace("@PRO_NO", $cmbPGNM, $strSQL);
        $strSQL = str_replace("@PASS", $txtPass1, $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", "PassMente", $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    //パスワードマスタデータ登録SQL
    public function fncInsPassSQL($cmbPGNM, $txtPass1)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO JKPASSMST" . "\r\n";
        $strSQL .= " (" . "\r\n";
        $strSQL .= "  PRO_NO," . "\r\n";
        $strSQL .= "  PASS," . "\r\n";
        $strSQL .= "  CREATE_DATE," . "\r\n";
        $strSQL .= "  CRE_SYA_CD," . "\r\n";
        $strSQL .= "  CRE_PRG_ID," . "\r\n";
        $strSQL .= "  UPD_DATE," . "\r\n";
        $strSQL .= "  UPD_SYA_CD," . "\r\n";
        $strSQL .= "  UPD_PRG_ID," . "\r\n";
        $strSQL .= "  UPD_CLT_NM" . "\r\n";
        $strSQL .= " )" . "\r\n";
        $strSQL .= " VALUES" . "\r\n";
        $strSQL .= " (" . "\r\n";
        $strSQL .= "  @PRO_NO," . "\r\n";
        $strSQL .= "  '@PASS'," . "\r\n";
        $strSQL .= "  SYSDATE," . "\r\n";
        $strSQL .= "  '@SYA_CD'," . "\r\n";
        $strSQL .= "  '@PRG_ID'," . "\r\n";
        $strSQL .= "  SYSDATE," . "\r\n";
        $strSQL .= "  '@SYA_CD'," . "\r\n";
        $strSQL .= "  '@PRG_ID'," . "\r\n";
        $strSQL .= "  '@CLT_NM'" . "\r\n";
        $strSQL .= " )";

        $strSQL = str_replace("@PRO_NO", $cmbPGNM, $strSQL);
        $strSQL = str_replace("@PASS", $txtPass1, $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", "PassMente", $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

}
