<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
use App\Model\HMTVE\Component\ClsComFncHMTVE;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE360HMENUPATTERNEntry extends ClsComDb
{
    private $ClsComFncHMTVE;
    public function pageloadSQL($CONST_SYS_KB)
    {
        $strSQL = "";
        $strSQL .= "SELECT PATTERN_ID" . "\r\n";
        $strSQL .= ",      PATTERN_NM" . "\r\n";
        $strSQL .= "FROM   HPATTERNMST" . "\r\n";
        $strSQL .= "WHERE  SYS_KB = '@SYSKB'" . "\r\n";
        $strSQL .= "AND    STYLE_ID = '001'" . "\r\n";
        $strSQL .= "ORDER BY PATTERN_ID" . "\r\n";
        $strSQL = str_replace("@SYSKB", $CONST_SYS_KB, $strSQL);
        return parent::select($strSQL);
    }

    public function SelectedIndexChangedSQL($CONST_SYS_KB, $selectedRow)
    {
        $strSQL = "";
        $strSQL .= "SELECT PRO.PRO_NO" . "\r\n";
        $strSQL .= ",      (CASE WHEN PTN.PRO_NO IS NOT NULL THEN 1 ELSE 0 END) KBN" . "\r\n";
        $strSQL .= ",      PRO.PRO_NM " . "\r\n";
        $strSQL .= ",      to_char(PTN.CREATE_DATE,'yyyy/mm/dd hh24:mi:ss') as CREATE_DATE" . "\r\n";
        $strSQL .= "FROM   HPROGRAMMST PRO " . "\r\n";
        $strSQL .= "LEFT JOIN HMENUKANRIPATTERN PTN " . "\r\n";
        $strSQL .= "ON     PTN.SYS_KB = '@SYSKB'" . "\r\n";
        $strSQL .= "AND    PTN.STYLE_ID = '001' " . "\r\n";
        $strSQL .= "AND    PTN.PATTERN_ID = '@PTNID' " . "\r\n";
        $strSQL .= "AND    PRO.PRO_NO = PTN.PRO_NO " . "\r\n";
        $strSQL .= "WHERE  PRO.SYS_KB = '@SYSKB' " . "\r\n";
        $strSQL .= "ORDER BY PRO.PRO_NO" . "\r\n";
        $strSQL = str_replace("@SYSKB", $CONST_SYS_KB, $strSQL);
        $strSQL = str_replace("@PTNID", $selectedRow, $strSQL);
        return parent::select($strSQL);
    }

    public function btnAddClickSQL($CONST_SYS_KB)
    {
        $strSQL = "";
        $strSQL .= "SELECT PRO_NO" . "\r\n";
        $strSQL .= ",      0 KBN" . "\r\n";
        $strSQL .= ",      PRO_NM " . "\r\n";
        $strSQL .= ",      '' CREATE_DATE" . "\r\n";
        $strSQL .= "FROM HPROGRAMMST " . "\r\n";
        $strSQL .= "WHERE SYS_KB = '@SYSKB'" . "\r\n";
        $strSQL = str_replace("@SYSKB", $CONST_SYS_KB, $strSQL);
        return parent::select($strSQL);
    }

    public function btnLoginSelectSQL($CONST_SYS_KB, $txtRightsID)
    {
        $strSQL = "";
        $strSQL .= "SELECT PATTERN_ID" . "\r\n";
        $strSQL .= "FROM   HPATTERNMST" . "\r\n";
        $strSQL .= "WHERE  SYS_KB = '@SYSKB'" . "\r\n";
        $strSQL .= "AND    STYLE_ID = '@STYLEID'" . "\r\n";
        $strSQL .= "AND    PATTERN_ID = '@PTNID'" . "\r\n";
        $strSQL = str_replace("@SYSKB", $CONST_SYS_KB, $strSQL);
        $strSQL = str_replace("@STYLEID", "001", $strSQL);
        $strSQL = str_replace("@PTNID", $txtRightsID, $strSQL);
        return parent::select($strSQL);
    }

    public function btnLoginUpdateSQL($CONST_SYS_KB, $txtRightsName, $txtRightsID)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HPATTERNMST" . "\r\n";
        $strSQL .= "SET    PATTERN_NM = '@PATTERN_NM'" . "\r\n";
        $strSQL .= ",    UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",    UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",    UPD_PRG_ID = 'HMENUPATTERNEntry'" . "\r\n";
        $strSQL .= ",    UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  SYS_KB = '@SYS_KB'" . "\r\n";
        $strSQL .= "AND    STYLE_ID = '001'" . "\r\n";
        $strSQL .= "AND    PATTERN_ID = '@PATTERN_ID'" . "\r\n";
        $strSQL = str_replace("@PATTERN_NM", $txtRightsName, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@SYS_KB", $CONST_SYS_KB, $strSQL);
        $strSQL = str_replace("@PATTERN_ID", $txtRightsID, $strSQL);
        return parent::update($strSQL);
    }

    public function btnLoginInsertSQL($CONST_SYS_KB, $txtRightsName, $txtRightsID)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HPATTERNMST" . "\r\n";
        $strSQL .= "(      SYS_KB" . "\r\n";
        $strSQL .= ",      STYLE_ID" . "\r\n";
        $strSQL .= ",      PATTERN_ID" . "\r\n";
        $strSQL .= ",      PATTERN_NM" . "\r\n";
        $strSQL .= ",      UPD_DATE" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES ('@SYSKB'" . "\r\n";
        $strSQL .= ",       '001'" . "\r\n";
        $strSQL .= ",       '@PATTERN_ID'" . "\r\n";
        $strSQL .= ",       '@PATTERN_NM'" . "\r\n";
        $strSQL .= ",       SYSDATE" . "\r\n";
        $strSQL .= ",       SYSDATE" . "\r\n";
        $strSQL .= ",       '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",       'HMENUPATTERNEntry'" . "\r\n";
        $strSQL .= ",       '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL = str_replace("@SYSKB", $CONST_SYS_KB, $strSQL);
        $strSQL = str_replace("@PATTERN_ID", $txtRightsID, $strSQL);
        $strSQL = str_replace("@PATTERN_NM", $txtRightsName, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }

    public function btnLoginDeleteSQL($CONST_SYS_KB, $txtRightsID)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HMENUKANRIPATTERN" . "\r\n";
        $strSQL .= "WHERE  SYS_KB = '@SYSKB'" . "\r\n";
        $strSQL .= "AND    STYLE_ID = '001'" . "\r\n";
        $strSQL .= "AND    PATTERN_ID = '@PTNID'" . "\r\n";

        $strSQL = str_replace("@SYSKB", $CONST_SYS_KB, $strSQL);
        $strSQL = str_replace("@PTNID", $txtRightsID, $strSQL);
        return parent::delete($strSQL);
    }

    public function btnLoginClickSQL($rowData, $CONST_SYS_KB, $txtRightsID)
    {

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = "";
        $strSQL .= "INSERT INTO HMENUKANRIPATTERN" . "\r\n";
        $strSQL .= "(SYS_KB" . "\r\n";
        $strSQL .= ",PATTERN_ID" . "\r\n";
        $strSQL .= ",STYLE_ID" . "\r\n";
        $strSQL .= ",PRO_NO" . "\r\n";
        $strSQL .= ",UPD_DATE" . "\r\n";
        $strSQL .= ",CREATE_DATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD" . "\r\n";
        $strSQL .= ",UPD_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES ('@SYSKB'" . "\r\n";
        $strSQL .= ",'@PATTERN_ID'" . "\r\n";
        $strSQL .= ",'001'" . "\r\n";
        $strSQL .= ",'@PRO_NO'" . "\r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",  DECODE('@CREDT','',SYSDATE,TO_DATE('@CREDT','YYYY/MM/DD HH24:MI:SS'))" . "\r\n";
        $strSQL .= ",'@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",'HMENUPATTERNEntry'" . "\r\n";
        $strSQL .= ",'@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL = str_replace("@SYSKB", $CONST_SYS_KB, $strSQL);
        $strSQL = str_replace("@PATTERN_ID", $txtRightsID, $strSQL);
        $strSQL = str_replace("@PRO_NO", $rowData['PRO_NO'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CREDT", $this->ClsComFncHMTVE->FncNv($rowData['CREATE_DATE']), $strSQL);
        return parent::insert($strSQL);
    }

    public function btnDeleteClickSQL($CONST_SYS_KB, $txtRightsID)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HPATTERNMST" . "\r\n";
        $strSQL .= "WHERE  SYS_KB = '@SYSKB'" . "\r\n";
        $strSQL .= "AND    STYLE_ID = '001'" . "\r\n";
        $strSQL .= "AND    PATTERN_ID = '@PTNID'" . "\r\n";

        $strSQL = str_replace("@SYSKB", $CONST_SYS_KB, $strSQL);
        $strSQL = str_replace("@PTNID", $txtRightsID, $strSQL);
        return parent::delete($strSQL);
    }

}
