<?php
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDViewerMente extends ClsComDb
{
    public function fncSelectMSTdata()
    {
        $strSql = $this->fncSelectMSTdataSQL();
        return parent::select($strSql);
    }

    public function fncSelectMSTdataSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT H.SYAIN_NO AS SYAIN_NO," . "\r\n";
        $strSQL .= "  M.SYAIN_KNJ_SEI || '　' ||M.SYAIN_KNJ_MEI AS SYAIN_NAME" . "\r\n";
        $strSQL .= " FROM HMAUD_MST_VIEWER H" . "\r\n";
        $strSQL .= "  LEFT JOIN M29MA4 M" . "\r\n";
        $strSQL .= "  ON M.SYAIN_NO   =H.SYAIN_NO" . "\r\n";
        return $strSQL;
    }

    public function FncGetSyainMstValue()
    {
        return parent::select($this->FncGetSyainMstValueSQL());
    }

    public function FncGetSyainMstValueSQL()
    {
        $strSql = "";
        $strSql .= "SELECT  SYAIN_KNJ_SEI || '　' ||SYAIN_KNJ_MEI AS SYAIN_NM" . "\r\n";
        $strSql .= ",     SYAIN_NO as SYAIN_NO" . "\r\n";
        $strSql .= "FROM   M29MA4" . "\r\n";

        return $strSql;
    }

    public function DeleteVIEWER()
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HMAUD_MST_VIEWER              " . "\r\n";
        return parent::delete($strSQL);
    }

    public function insertVIEWER($tableData)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HMAUD_MST_VIEWER                  " . "\r\n";
        $strSQL .= " (SYAIN_NO,                                  " . "\r\n";
        $strSQL .= "  CREATE_DATE,                                  " . "\r\n";
        $strSQL .= "  CREATE_SYA_CD,                               " . "\r\n";
        $strSQL .= "  UPD_DATE,                                " . "\r\n";
        $strSQL .= "  UPD_SYA_CD)                                " . "\r\n";
        $strSQL .= "  VALUES(                                    " . "\r\n";
        $strSQL .= "  '@SYAIN_NO',                                " . "\r\n";
        $strSQL .= "  SYSDATE,                                   " . "\r\n";
        $strSQL .= "  '@CREATE_SYA_CD',                                  " . "\r\n";
        $strSQL .= "  SYSDATE,                                   " . "\r\n";
        $strSQL .= "  '@UPD_SYA_CD')                                  " . "\r\n";
        $strSQL = str_replace("@SYAIN_NO", $tableData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CREATE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::insert($strSQL);
    }

}