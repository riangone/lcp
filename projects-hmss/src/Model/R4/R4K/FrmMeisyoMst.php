<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmMeisyoMst extends ClsComDb
{

    //--execute sql--
    public function fncMeisyouMstSelect($postData)
    {
        $strSql = $this->fncMeisyouMstSelect_sql($postData);
        return parent::select($strSql);
    }

    public function fncSingleDelete($MEISYOU_CD, $MEISYOU_ID)
    {
        $strSql = $this->fncSingleDelete_sql($MEISYOU_CD, $MEISYOU_ID);
        return parent::delete($strSql);
    }

    public function fncDelete($postData)
    {
        $strSql = $this->fncDelete_sql($postData);
        return parent::Do_Execute($strSql);
    }

    public function fncInsert($value, $txtID)
    {
        $strSql = $this->fncInsert_sql($value, $txtID);
        return parent::Do_Execute($strSql);
        //echo $strSql;
    }

    public function fncSelectKUKURI_CD($whereStr)
    {
        $strSql = $this->fncSelectKUKURI_CD_sql($whereStr);
        return parent::select($strSql);
    }

    //--sql--
    public function fncMeisyouMstSelect_sql($postData)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT MEISYOU_CD\n";
        $sqlstr .= ",      MEISYOU\n";
        $sqlstr .= ",      MEISYOU_RN\n";
        $sqlstr .= ",      MOJI1\n";
        $sqlstr .= ",      MOJI2\n";
        $sqlstr .= ",      SUCHI1\n";
        $sqlstr .= ",      SUCHI2\n";
        $sqlstr .= ",      CREATE_DATE\n";
        $sqlstr .= ",      UPD_DATE\n";
        $sqlstr .= "FROM   HMEISYOUMST\n";
        $sqlstr .= "WHERE  MEISYOU_ID = '" . $postData["txtID"] . "'\n";
        return $sqlstr;
    }

    public function fncSingleDelete_sql($MEISYOU_CD, $MEISYOU_ID)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HMEISYOUMST ";
        $sqlstr .= "WHERE  MEISYOU_ID = '" . $MEISYOU_ID . "'";
        $sqlstr .= "AND  MEISYOU_CD = '" . $MEISYOU_CD . "'";
        return $sqlstr;
    }

    public function fncDelete_sql($postData)
    {
        $sqlstr = "";
        $where = " WHERE ";
        $sqlstr .= "DELETE FROM HMEISYOUMST ";
        if ($postData != "") {
            $where .= " MEISYOU_ID = '" . $postData . "'";
        }
        return $sqlstr . $where;
    }

    public function fncInsert_sql($value, $txtID)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HMEISYOUMST\n";
        $sqlstr .= "(      MEISYOU_ID\n";
        $sqlstr .= ",      MEISYOU_CD\n";
        $sqlstr .= ",      MEISYOU\n";
        $sqlstr .= ",      MEISYOU_RN\n";
        $sqlstr .= ",      MOJI1\n";
        $sqlstr .= ",      MOJI2\n";
        $sqlstr .= ",      SUCHI1\n";
        $sqlstr .= ",      SUCHI2\n";
        $sqlstr .= ",      UPD_DATE\n";
        $sqlstr .= ",      CREATE_DATE\n";
        $sqlstr .= ",      UPD_SYA_CD\n";
        $sqlstr .= ",      UPD_PRG_ID\n";
        $sqlstr .= ",      UPD_CLT_NM\n";
        $sqlstr .= ")VALUES(";
        $sqlstr .= "		'" . $txtID . "'\n";
        $sqlstr .= ",		'" . $value["MEISYOU_CD"] . "'\n";
        $sqlstr .= ",		'" . $value["MEISYOU"] . "'\n";
        $sqlstr .= ",		'" . $value["MEISYOU_RN"] . "'\n";
        $sqlstr .= ",		'" . $value["MOJI1"] . "'\n";
        $sqlstr .= ",		'" . $value["MOJI2"] . "'\n";
        if ($value['SUCHI1'] == "") {
            $sqlstr .= ",0\n";
        } else {
            $sqlstr .= "," . $value["SUCHI1"] . "\n";
        }
        if ($value['SUCHI2'] == "") {
            $sqlstr .= ",0\n";
        } else {
            $sqlstr .= "," . $value["SUCHI2"] . "\n";
        }
        $sqlstr .= ",     SYSDATE\n";
        $tmpVal = $value['CREATE_DATE'] != "" ? "TO_DATE(" . $this->fncSqlNv2($value['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE \n";
        $sqlstr .= ",	  " . $tmpVal;
        $sqlstr .= ",	  '" . $this->GS_LOGINUSER['strUserID'] . "' \n";
        $sqlstr .= ",	  'MeisyoMst'\n";
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
