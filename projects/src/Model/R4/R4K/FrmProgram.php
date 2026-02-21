<?php
namespace App\Model\R4\R4K;

// 共通クラスの読込み

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmProgram extends ClsComDb
{
    public function fncFrmProgramSelect()
    {
        $strSql = $this->fncFrmProgramSelect_sql();
        return parent::select($strSql);
    }

    public function fncDelete()
    {
        $strSql = $this->fncDelete_sql();
        return parent::Do_Execute($strSql);
    }

    public function fncInsert($data)
    {
        $strSql = $this->fncInsert_sql($data);
        return parent::Do_Execute($strSql);
    }

    public function fncSingleDelete($data)
    {
        $strSql = $this->fncSingleDelete_sql($data);
        return parent::delete($strSql);
    }

    public function fncFrmProgramSelect_sql()
    {

        $sqlstr = '';
        $sqlstr .= "  SELECT PRO_NO,	";
        $sqlstr .= " PRO_ID,";
        $sqlstr .= " PRO_NM,";
        $sqlstr .= " TO_CHAR(CREATE_DATE,'YYYY/MM/DD HH:MI:SS') as CREATE_DATE";
        $sqlstr .= " FROM HPROGRAMMST ";
        $sqlstr .= " WHERE SYS_KB = '0'";
        $sqlstr .= " ORDER BY PRO_NO	";
        return $sqlstr;
    }

    //--SQL--
    public function fncDelete_sql()
    {
        $sqlstr = "";
        $sqlstr .= " DELETE FROM HPROGRAMMST";
        $sqlstr .= " WHERE  SYS_KB = '0'";
        return $sqlstr;
    }

    //Insert SQL
    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HPROGRAMMST \r\n";
        $sqlstr .= "(			SYS_KB\r\n";
        $sqlstr .= ",			PRO_NO\r\n";
        $sqlstr .= ",			PRO_ID\r\n";
        $sqlstr .= ",			PRO_NM\r\n";
        $sqlstr .= ",			UPD_DATE\r\n";
        $sqlstr .= ",			CREATE_DATE\r\n";
        $sqlstr .= ",			UPD_SYA_CD\r\n";
        $sqlstr .= ",			UPD_PRG_ID\r\n";
        $sqlstr .= ",			UPD_CLT_NM\r\n";

        $sqlstr .= ") VALUES (  \n ";
        $sqlstr .= $this->fncSqlNv2('0') . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['PRO_NO']) . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['PRO_ID']) . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['PRO_NM']) . " \n";
        $sqlstr .= ",SYSDATE \n";
        // CREATE_DATE is ＮＵＬＬ
        if (trim($data['CREATE_DATE']) == "") {
            $sqlstr .= "		,SYSDATE  \n";
        } else {
            $sqlstr .= "		,TO_DATE(" . $this->fncSqlNv2($data['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')";
        }
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strUserID'] . "' \n";
        $sqlstr .= ",'frmProgram'\n";
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "'\n";
        $sqlstr .= ")";

        return $sqlstr;
    }

    public function fncSingleDelete_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= " DELETE FROM HPROGRAMMST";
        $sqlstr .= " WHERE  SYS_KB = '0'";
        $sqlstr .= " AND  PRO_NO = '" . $data . "'";
        return $sqlstr;
    }

    public function fncSqlNv2($objValue, $objReturn = "", $intKind = 1)
    {
        if ($objValue == "" || $objValue === null) {
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
}