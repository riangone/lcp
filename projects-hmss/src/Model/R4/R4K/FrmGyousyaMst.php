<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmGyousyaMst extends ClsComDb
{
    //--execute sql--
    public function fncFromGyosyaSelect()
    {
        $strSql = $this->fncFromGyosyaSelect_sql();
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

    public function fncFromGyosyaSelect_sql()
    {
        $sqlstr = "";
        $sqlstr .= "SELECT GYOSYA_CD \n";
        $sqlstr .= ",      GYOSYA_NM \n";
        $sqlstr .= ",      GYOSYA_RNM \n";
        $sqlstr .= ",      JISSEKI_KB \n";
        $sqlstr .= ",      SYUKEI_BUSYO_CD \n";
        $sqlstr .= ",      CREATE_DATE \n";
        $sqlstr .= "FROM   HGYOSYAMST \n";

        $sqlstr .= "ORDER BY GYOSYA_CD \n";
        return $sqlstr;
    }

    public function fncDelete_sql()
    {
        $sqlstr = "";
        $sqlstr = "DELETE FROM HGYOSYAMST";
        return $sqlstr;
    }

    public function fncInsert_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HGYOSYAMST \n ";
        $sqlstr .= "(      GYOSYA_CD \n ";
        $sqlstr .= ",      GYOSYA_NM \n ";
        $sqlstr .= ",      GYOSYA_RNM \n ";
        $sqlstr .= ",      JISSEKI_KB \n ";
        $sqlstr .= ",      SYUKEI_BUSYO_CD \n ";
        $sqlstr .= ",      UPD_DATE \n ";
        $sqlstr .= ",      CREATE_DATE \n ";
        $sqlstr .= ",      UPD_SYA_CD \n ";
        $sqlstr .= ",      UPD_PRG_ID \n ";
        $sqlstr .= ",      UPD_CLT_NM \n ";

        $sqlstr .= ") VALUES (  \n ";
        $sqlstr .= $this->fncSqlNv2($data['GYOSYA_CD']) . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['GYOSYA_NM']) . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['GYOSYA_RNM']) . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['JISSEKI_KB']) . " \n";
        $sqlstr .= "," . $this->fncSqlNv2($data['SYUKEI_BUSYO_CD']) . " \n";
        $sqlstr .= ",SYSDATE \n";
        $tmpVal = $data['CREATE_DATE'] != "" ? "TO_DATE(" . $this->fncSqlNv2($data['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE \n";
        $sqlstr .= "," . $tmpVal;
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strUserID'] . "' \n";
        $sqlstr .= ",'GyousyaMst'\n";
        $sqlstr .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "'\n";
        $sqlstr .= ")";
        //echo $sqlstr;
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
        $sqlstr .= "DELETE FROM HGYOSYAMST WHERE GYOSYA_CD='" . $data . "'";
        return $sqlstr;
    }

}