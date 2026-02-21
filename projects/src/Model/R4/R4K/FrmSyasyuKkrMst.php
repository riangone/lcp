<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmSyasyuKkrMst extends ClsComDb
{
    public function fncFrmSyasyuKkrMstSelect()
    {
        $strSql = $this->fncFrmSyasyuKkrMstSelect_sql();
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

    //--sql--
    public function fncFrmSyasyuKkrMstSelect_sql()
    {


        $sqlstr = "SELECT * FROM HSYASYUKKRMST   ";
        return $sqlstr;
    }

    public function fncDelete_sql()
    {
        $sqlstr = "";
        $sqlstr = "DELETE FROM HSYASYUKKRMST";
        return $sqlstr;
    }

    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HSYASYUKKRMST(";
        $tmpcnt = 0;
        foreach ($data as $key => $value) {
            if ($tmpcnt > 0) {
                $sqlstr .= ",";
            }
            $sqlstr .= " " . $key . " \n";
            $tmpcnt++;
        }
        $sqlstr .= ", UPD_DATE  \n";
        $sqlstr .= ", UPD_SYA_CD \n ";
        $sqlstr .= ", UPD_PRG_ID \n ";
        $sqlstr .= ", UPD_CLT_NM \n ";
        $sqlstr .= "		) VALUES (";
        $tmpcnt = 0;
        foreach ($data as $key => $value) {
            if ($key != "CREATE_DATE") {
                if ($tmpcnt > 0) {
                    $sqlstr .= ",";
                }
                $sqlstr .= "		'" . $data[$key] . "' \n";
            }
            $tmpcnt++;
        }
        $sqlstr .= "		,SYSDATE";
        if (trim($data['CREATE_DATE']) == "") {
            $sqlstr .= "		,SYSDATE";
        } else {
            $sqlstr .= "		,TO_DATE(" . $this->fncSqlNv2($data['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')";
        }
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strUserID'] . "' \n";
        $sqlstr .= ",'ArariSyukeiMst'\n";
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "'\n";
        $sqlstr .= ")";
        return $sqlstr;
    }

    public function fncSingleDelete_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HSYASYUKKRMST WHERE UCOYA_CD='" . $data . "'";
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