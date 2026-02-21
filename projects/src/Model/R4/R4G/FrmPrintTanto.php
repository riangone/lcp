<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：FrmPrintTanto
// * 関数名	：FrmPrintTanto
// * 処理説明	：FrmPrintTanto
//*************************************

class FrmPrintTanto extends ClsComDb
{
    function selectSql()
    {
        $strSql = "";
        $strSql .= "SELECT TANTO_SEI" . "\r\n";
        $strSql .= ",	TANTO_MEI" . "\r\n";
        $strSql .= ",	BUSYO_NM" . "\r\n";
        $strSql .= ",	TO_CHAR(UPD_DATE,'yyyy-mm-dd hh24:mi:ss') UPD_DATE" . "\r\n";
        $strSql .= ",	TO_CHAR(CREATE_DATE,'yyyy-mm-dd hh24:mi:ss') CREATE_DATE" . "\r\n";
        $strSql .= ",	UPD_SYA_CD" . "\r\n";
        $strSql .= ",	UPD_PRG_ID" . "\r\n";
        $strSql .= ",	UPD_PRG_ID" . "\r\n";
        $strSql .= "FROM HPRINTTANTO" . "\r\n";
        return $strSql;
    }

    function insertSql($Array_Insert)
    {
        $strInsSQL = "";
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "PrintTanto";
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strInsSQL .= "INSERT INTO HPRINTTANTO(" . "\r\n";
        $strInsSQL .= "		TANTO_SEI" . "\r\n";
        $strInsSQL .= ",	TANTO_MEI" . "\r\n";
        $strInsSQL .= ",	BUSYO_NM" . "\r\n";
        $strInsSQL .= ",	UPD_DATE" . "\r\n";
        $strInsSQL .= ",	CREATE_DATE" . "\r\n";
        $strInsSQL .= ",	UPD_SYA_CD" . "\r\n";
        $strInsSQL .= ",	UPD_PRG_ID" . "\r\n";
        $strInsSQL .= ",	UPD_CLT_NM" . "\r\n";
        $strInsSQL .= ") VALUES (" . "\r\n";
        $strInsSQL .= "		'@TANTO_SEI'" . "\r\n";
        $strInsSQL .= ",	'@TANTO_MEI'" . "\r\n";
        $strInsSQL .= ",	'@BUSYO_NM'" . "\r\n";
        $strInsSQL .= ",	SYSDATE" . "\r\n";
        $strInsSQL .= ",	@CREATE_DATE" . "\r\n";
        $strInsSQL .= ",	'@UPDUSER'" . "\r\n";
        $strInsSQL .= ",	'@UPDAPP'" . "\r\n";
        $strInsSQL .= ",	'@UPDCLTNM')" . "\r\n";

        $strInsSQL = str_replace("@TANTO_SEI", $Array_Insert['TANTO_SEI'], $strInsSQL);
        $strInsSQL = str_replace("@TANTO_MEI", $Array_Insert['TANTO_MEI'], $strInsSQL);
        $strInsSQL = str_replace("@BUSYO_NM", $Array_Insert['BUSYO_NM'], $strInsSQL);

        if (isset($Array_Insert['CREATE_DATE'])) {
            if (!($this->checkDate($Array_Insert['CREATE_DATE']))) {
                $strInsSQL = str_replace("@CREATE_DATE", "TO_DATE('" . $Array_Insert['CREATE_DATE'] . "','yyyy-mm-dd hh24:mi:ss')", $strInsSQL);
            } else {
                $strInsSQL = str_replace("@CREATE_DATE", "SYSDATE", $strInsSQL);
            }
        } else {
            $strInsSQL = str_replace("@CREATE_DATE", "SYSDATE", $strInsSQL);
        }

        $strInsSQL = str_replace("@UPDUSER", $UPDUSER, $strInsSQL);
        $strInsSQL = str_replace("@UPDAPP", $UPDAPP, $strInsSQL);
        $strInsSQL = str_replace("@UPDCLTNM", $UPDCLTNM, $strInsSQL);
        return $strInsSQL;
    }

    function deleteSql()
    {
        return "DELETE FROM HPRINTTANTO";
    }

    function checkDate($date)
    {
        if ($date != "" && $date != NULL) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    //*************************************
    // * 公開メソッド
    //*************************************

    public function select_data()
    {

        return parent::select($this->selectSql());

    }

    public function delete_data()
    {

        return parent::Do_Execute($this->deleteSql());

    }

    public function insert($Array_Insert)
    {
        return parent::Do_Execute($this->insertSql($Array_Insert));
        //return $this -> insertSql($Array_Insert);
    }

}
