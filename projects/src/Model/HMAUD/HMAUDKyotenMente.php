<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                            FCSDL
 * 20230801           機能修正　 literal does not match format stringエラー訂正     caina
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDKyotenMente extends ClsComDb
{
    public function fncSelectMSTdata()
    {
        $strSql = $this->fncSelectMSTdataSQL();
        return parent::select($strSql);
    }

    public function fncSelectMSTdataSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT  ";
        $strSQL .= " KYOTEN_CD," . "\r\n";
        $strSQL .= " KYOTEN_NAME," . "\r\n";
        $strSQL .= " TERRITORY," . "\r\n";
        $strSQL .= " TO_CHAR(START_DT,'YYYY/MM/DD') AS START_DT," . "\r\n";
        $strSQL .= " TO_CHAR(END_DT,'YYYY/MM/DD') AS END_DT," . "\r\n";
        $strSQL .= " DISP_SEQ," . "\r\n";
        $strSQL .= " RESPONSIBLE_EIGYO," . "\r\n";
        $strSQL .= " RESPONSIBLE_TERRITORY," . "\r\n";
        $strSQL .= " KEY_PERSON," . "\r\n";
        $strSQL .= " TARGET" . "\r\n";
        $strSQL .= " FROM  " . "\r\n";
        $strSQL .= " HMAUD_MST_KTN  ";
        $strSQL .= " ORDER BY DISP_SEQ ASC  " . "\r\n";
        return $strSQL;
    }

    public function DeleteMSTKTN()
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HMAUD_MST_KTN              " . "\r\n";
        return parent::delete($strSQL);
    }

    public function insertMSTKTN($tableData)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HMAUD_MST_KTN                  " . "\r\n";
        $strSQL .= " (KYOTEN_CD,                                  " . "\r\n";
        $strSQL .= "  KYOTEN_NAME,                                    " . "\r\n";
        $strSQL .= "  TERRITORY,                                  " . "\r\n";
        $strSQL .= "  START_DT,                           " . "\r\n";
        $strSQL .= "  END_DT,                                " . "\r\n";
        $strSQL .= "  DISP_SEQ,                                  " . "\r\n";
        $strSQL .= "  TARGET,                               " . "\r\n";
        $strSQL .= "  RESPONSIBLE_EIGYO,                               " . "\r\n";
        $strSQL .= "  RESPONSIBLE_TERRITORY,                               " . "\r\n";
        $strSQL .= "  KEY_PERSON,                               " . "\r\n";
        $strSQL .= "  UPD_DATE,                                  " . "\r\n";
        $strSQL .= "  CREATE_DATE,                               " . "\r\n";
        $strSQL .= "  UPD_SYA_CD,                                " . "\r\n";
        $strSQL .= "  CREATE_SYA_CD)                                " . "\r\n";
        $strSQL .= "  VALUES(                                    " . "\r\n";
        $strSQL .= "  '@KYOTEN_CD',                                " . "\r\n";
        $strSQL .= "  '@KYOTEN_NAME',                                    " . "\r\n";
        $strSQL .= "  '@TERRITORY',                                " . "\r\n";
        //20230801 caina upd s
        // $strSQL .= "  '@START_DT',                               " . "\r\n";
        $strSQL .= "  TO_DATE('@START_DT','YYYY-MM-DD HH24:MI:SS'),                               " . "\r\n";
        // $strSQL .= "  '@END_DT',                                  " . "\r\n";
        $strSQL .= "  TO_DATE('@END_DT','YYYY-MM-DD HH24:MI:SS'),                                  " . "\r\n";
        //20230801 caina upd e
        $strSQL .= "  '@DISP_SEQ',                                    " . "\r\n";
        $strSQL .= "  '@TARGET',                               " . "\r\n";
        $strSQL .= "  '@RESPONSIBLE_EIGYO',                               " . "\r\n";
        $strSQL .= "  '@RESPONSIBLE_TERRITORY',                               " . "\r\n";
        $strSQL .= "  '@KEY_PERSON',                               " . "\r\n";
        $strSQL .= "  SYSDATE,                                   " . "\r\n";
        $strSQL .= "  SYSDATE,                                   " . "\r\n";
        $strSQL .= "  '@UPD_SYA_CD',                                  " . "\r\n";
        $strSQL .= "  '@CREATE_SYA_CD')                                  " . "\r\n";

        $strSQL = str_replace("@KYOTEN_CD", $tableData['KYOTEN_CD'], $strSQL);
        $strSQL = str_replace("@KYOTEN_NAME", $tableData['KYOTEN_NAME'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $tableData['TERRITORY'], $strSQL);
        $strSQL = str_replace("@DISP_SEQ",isset($tableData['DISP_SEQ']) ?  $tableData['DISP_SEQ'] : '', $strSQL);
        $strSQL = str_replace("@START_DT", $tableData['START_DT'] != '' ? str_replace("/", '', $tableData['START_DT']) : '', $strSQL);
        $strSQL = str_replace("@END_DT", $tableData['END_DT'] != '' ? str_replace("/", '', $tableData['END_DT']) : '', $strSQL);
        $strSQL = str_replace("@TARGET", isset($tableData['DISP_SEQ']) ? $tableData['rdoTenjikai'] : '', $strSQL);
        $strSQL = str_replace("@RESPONSIBLE_EIGYO", $tableData['RESPONSIBLE_EIGYO'], $strSQL);
        $strSQL = str_replace("@RESPONSIBLE_TERRITORY", $tableData['RESPONSIBLE_TERRITORY'], $strSQL);
        $strSQL = str_replace("@KEY_PERSON", $tableData['KEY_PERSON'], $strSQL);

        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CREATE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::insert($strSQL);
    }

}
