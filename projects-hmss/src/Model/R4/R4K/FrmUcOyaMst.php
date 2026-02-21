<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150811           #1969                         BUG                             Yuanjh　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmUcOyaMst extends ClsComDb
{
    //--execute sql--
    public function fncUcOyaSelect()
    {
        $strSql = $this->fncUcOyaSelect_sql();
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

    public function fncUcOyaSelect_sql()
    {
        $sqlstr = "";
        $sqlstr .= "SELECT GEN.ID \n ";
        $sqlstr .= ",      UC.HMK_CD \n ";
        $sqlstr .= ",      UC.CREATE_DATE \n ";
        $sqlstr .= "FROM   (SELECT DISTINCT ID \n ";
        $sqlstr .= "        FROM   HGENKAMST) GEN \n ";
        $sqlstr .= "LEFT JOIN  \n ";
        $sqlstr .= "       HUCHMKMST UC \n ";
        $sqlstr .= "ON     UC.UCOYA_CD = GEN.ID \n ";
        $sqlstr .= "WHERE  RTRIM(GEN.ID) IS NOT NULL  \n ";

        return $sqlstr;
    }

    public function fncDelete_sql()
    {
        $sqlstr = "";
        //---20150811 Yuanjh modify  S.
        //$sqlstr = "DELETE FROM HUCHMKMST1";
        //---20150811 Yuanjh modify  E.
        $sqlstr = "DELETE FROM HUCHMKMST";
        return $sqlstr;
    }

    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HUCHMKMST \n ";
        $sqlstr .= "(      UCOYA_CD \n ";
        $sqlstr .= ",      HMK_CD \n ";
        $sqlstr .= ",      UPD_DATE \n ";
        $sqlstr .= ",      CREATE_DATE \n ";
        $sqlstr .= ",      UPD_SYA_CD \n ";
        $sqlstr .= ",      UPD_PRG_ID \n ";
        $sqlstr .= ",      UPD_CLT_NM \n ";
        $sqlstr .= ") VALUES (  \n ";
        $sqlstr .= $this->fncSqlNv2($data['UCOYA_CD']) . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['HMK_CD']) . " \n";
        $sqlstr .= ",SYSDATE \n";
        $tmpVal = $data['CREATE_DATE'] != "" ? "TO_DATE(" . $this->fncSqlNv2($data['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE \n";
        $sqlstr .= "," . $tmpVal;
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strUserID'] . "' \n";
        $sqlstr .= ",'UcOyaMst'\n";
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "'\n";
        $sqlstr .= ")";
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

    public function fncSingleDelete_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HUCHMKMST WHERE UCOYA_CD='" . $data . "'";
        return $sqlstr;
    }

}