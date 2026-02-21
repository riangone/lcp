<?php
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDKansaJinMente extends ClsComDb
{
    public function FncGetSyainMstValue()
    {
        return parent::select($this->FncGetSyainMstValueSQL());
    }

    public function FncGetSyainMstValueSQL()
    {
        $strSql = "";
        $strSql .= "SELECT  SYAIN_KNJ_SEI || '　' ||SYAIN_KNJ_MEI AS SYAIN_NM" . "\r\n";
        $strSql .= ",     SYAIN_NO as SYAIN_NO" . "\r\n";
        $strSql .= ",     SYAIN_MAL_ADR as EMAIL" . "\r\n";
        $strSql .= "FROM   M29MA4" . "\r\n";

        return $strSql;
    }
    public function fncSelectMSTdata()
    {
        $strSql = $this->fncSelectMSTdataSQL();
        return parent::select($strSql);
    }

    public function fncSelectMSTdataSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT  ";
        $strSQL .= " H.SYAIN_NO," . "\r\n";
        $strSQL .= " H.EMAIL," . "\r\n";
        $strSQL .= " H.ENABLED," . "\r\n";
        $strSQL .= " H.SEQ," . "\r\n";
        $strSQL .= " M.SYAIN_KNJ_SEI || '　' ||M.SYAIN_KNJ_MEI AS SYAIN_NAME" . "\r\n";
        $strSQL .= " FROM  " . "\r\n";
        $strSQL .= " HMAUD_MST_AUDITOR H ";
        $strSQL .= " LEFT JOIN M29MA4 M  " . "\r\n";
        $strSQL .= " ON M.SYAIN_NO = H.SYAIN_NO" . "\r\n";
        $strSQL .= " ORDER BY TO_NUMBER(SEQ) ASC  " . "\r\n";
        return $strSQL;
    }

    public function SelectMSTdata($SYAIN_NO)
    {
        $strSQL = "";
        $strSQL .= " SELECT SYAIN_NO FROM HMAUD_MST_AUDITOR  " . "\r\n";
        $strSQL .= " WHERE SYAIN_NO = '@SYAIN_NO'  " . "\r\n";
        $strSQL = str_replace("@SYAIN_NO", $SYAIN_NO, $strSQL);

        return parent::select($strSQL);
    }

    public function insertMSTAUD($tableData)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HMAUD_MST_AUDITOR                  " . "\r\n";
        $strSQL .= " (SYAIN_NO,                                  " . "\r\n";
        $strSQL .= "  EMAIL,                                    " . "\r\n";
        $strSQL .= "  ENABLED,                                  " . "\r\n";
        $strSQL .= "  SEQ,                           " . "\r\n";
        $strSQL .= "  CREATE_DATE,                               " . "\r\n";
        $strSQL .= "  CREATE_SYA_CD,                                " . "\r\n";
        $strSQL .= "  UPD_DATE,                                  " . "\r\n";
        $strSQL .= "  UPD_SYA_CD)                                " . "\r\n";
        $strSQL .= "  VALUES(                                    " . "\r\n";
        $strSQL .= "  '@SYAIN_NO',                                " . "\r\n";
        $strSQL .= "  '@EMAIL',                                    " . "\r\n";
        $strSQL .= "  '@ENABLED',                                " . "\r\n";
        $strSQL .= "  '@SEQ',                                    " . "\r\n";
        $strSQL .= "  SYSDATE,                                   " . "\r\n";
        $strSQL .= "  '@CREATE_SYA_CD',                                   " . "\r\n";
        $strSQL .= "  SYSDATE,                                  " . "\r\n";
        $strSQL .= "  '@UPD_SYA_CD')                                  " . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $tableData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@EMAIL", $tableData['EMAIL'], $strSQL);
        $strSQL = str_replace("@ENABLED", $tableData['ENABLED'], $strSQL);
        $strSQL = str_replace("@SEQ", $tableData['SEQ'], $strSQL);

        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CREATE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::insert($strSQL);
    }

    public function updateMSTAUD($tableData)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HMAUD_MST_AUDITOR SET                  " . "\r\n";
        $strSQL .= "  EMAIL = '@EMAIL',                            " . "\r\n";
        $strSQL .= "  ENABLED = '@ENABLED',                        " . "\r\n";
        $strSQL .= "  SEQ = '@SEQ',                                " . "\r\n";
        $strSQL .= "  UPD_DATE = SYSDATE,                          " . "\r\n";
        $strSQL .= "  UPD_SYA_CD = '@UPD_SYA_CD'                   " . "\r\n";
        $strSQL .= " WHERE SYAIN_NO = '@SYAIN_NO'                  " . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $tableData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@EMAIL", $tableData['EMAIL'], $strSQL);
        $strSQL = str_replace("@ENABLED", $tableData['ENABLED'], $strSQL);
        $strSQL = str_replace("@SEQ", $tableData['SEQ'], $strSQL);

        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::update($strSQL);
    }

}
