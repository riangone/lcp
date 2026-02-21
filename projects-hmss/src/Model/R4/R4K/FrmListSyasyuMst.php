<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150728           #1970                   「更新」ボタン押下時にエラーが発生する        FANZHENGZHOU
 * 20150818           #1971                   「更新」ボタン押下時にエラーが発生する        FANZHENGZHOU
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmListSyasyuMst extends ClsComDb
{
    //--execute sql--
    public function fncListSyasyuMstSel($postData)
    {
        $strSql = $this->fncListSyasyuMstSel_sql($postData);
        return parent::select($strSql);
    }

    public function fncSingleDelete($KUKURI_CD)
    {
        $strSql = $this->fncSingleDelete_sql($KUKURI_CD);
        return parent::delete($strSql);
    }

    public function fncDelete($txtKkrCD, $txtCarKbn)
    {
        $strSql = $this->fncDelete_sql($txtKkrCD, $txtCarKbn);
        return parent::Do_Execute($strSql);
    }

    public function fncInsert($value)
    {
        $strSql = $this->fncInsert_sql($value);
        return parent::Do_Execute($strSql);
    }

    public function fncSelectKUKURI_CD($whereStr)
    {
        $strSql = $this->fncSelectKUKURI_CD_sql($whereStr);
        return parent::select($strSql);
    }

    //--sql--
    public function fncListSyasyuMstSel_sql($postData)
    {
        $strWhere = " WHERE ";
        $sqlstr = "";
        $sqlstr .= "SELECT	KUKURI_CD\n";
        $sqlstr .= ",	    LINE_NO\n";
        $sqlstr .= ",	    CAR_KBN\n";
        $sqlstr .= ",	    CREATE_DATE\n";
        $sqlstr .= "FROM    HLISTSYASYUMST\n ";
        if ($postData['txtCarKbn'] != "") {
            $sqlstr .= $strWhere . " CAR_KBN LIKE '" . $postData['txtCarKbn'] . "%' ";
            $strWhere = " AND ";
        }
        if ($postData['txtKkrCD'] != "") {
            $sqlstr .= $strWhere . " KUKURI_CD LIKE '" . $postData['txtKkrCD'] . "%' ";
        }
        $sqlstr .= " ORDER BY CAR_KBN, LINE_NO ";
        return $sqlstr;
    }

    public function fncSingleDelete_sql($KUKURI_CD)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HLISTSYASYUMST ";
        $sqlstr .= "WHERE  KUKURI_CD = '" . $KUKURI_CD . "'";
        return $sqlstr;
    }

    public function fncDelete_sql($txtKkrCD, $txtCarKbn)
    {
        $sqlstr = "";
        $where = " WHERE ";
        //20150728 #1970 fanzhengzhou upd s.
        //$sqlstr .= "DELETE FROM HLISTSYASYUMST1 ";
        $sqlstr .= "DELETE FROM HLISTSYASYUMST ";
        //20150728 #1970 fanzhengzhou upd e.
        if ($txtKkrCD != "") {
            //---20150818 #1971 fanzhengzhou upd s.
            //$where .= " KUKURI_CD LIKE '" . $txtKkrCD . "%'";
            $sqlstr .= $where . " KUKURI_CD LIKE '" . $txtKkrCD . "%'";
            $where = " AND ";
            //---20150818 #1971 fanzhengzhou upd e.
        }
        if ($txtCarKbn != "") {
            //---20150818 #1971 fanzhengzhou upd s.
            //$where .= " CAR_KBN LIKE '" . $txtCarKbn . "%'";
            $sqlstr .= $where . " CAR_KBN LIKE '" . $txtCarKbn . "%'";
            //---20150818 #1971 fanzhengzhou upd e.
        }
        //---20150818 #1971 fanzhengzhou upd s.
        // if ($where == " WHERE ") {
        // $where = "";
        // }
        //return $sqlstr . $where;
        return $sqlstr;
        //---20150818 #1971 fanzhengzhou upd e.
    }

    public function fncInsert_sql($value)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HLISTSYASYUMST \n";
        $sqlstr .= "(";
        $sqlstr .= "       KUKURI_CD\n";
        $sqlstr .= ",      LINE_NO\n";
        $sqlstr .= ",      CAR_KBN\n";
        $sqlstr .= ",      UPD_DATE\n";
        $sqlstr .= ",      CREATE_DATE\n";
        $sqlstr .= ",      UPD_SYA_CD\n";
        $sqlstr .= ",      UPD_PRG_ID\n";
        $sqlstr .= ",      UPD_CLT_NM\n";
        $sqlstr .= ")VALUES(";
        $sqlstr .= "'" . $value["KUKURI_CD"] . "'\n";
        $sqlstr .= ",     " . $value["LINE_NO"] . "\n";
        $sqlstr .= ",	  '" . $value["CAR_KBN"] . "'\n";
        $sqlstr .= ",     SYSDATE\n";
        $tmpVal = $value['CREATE_DATE'] != "" ? "TO_DATE(" . $this->fncSqlNv2($value['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE \n";
        $sqlstr .= ",	  " . $tmpVal;
        $sqlstr .= ",	  '" . $this->GS_LOGINUSER['strUserID'] . "' \n";
        $sqlstr .= ",	  'ListSyasyuMst'\n";
        $sqlstr .= ",	  '" . $this->GS_LOGINUSER['strClientNM'] . "'\n";

        $sqlstr .= ")";
        return $sqlstr;
    }

    public function fncSelectKUKURI_CD_sql($whereStr)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT      KUKURI_CD\n";
        $sqlstr .= " FROM    HLISTSYASYUMST\n ";
        $sqlstr .= " WHERE " . $whereStr;
        $sqlstr .= "";
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