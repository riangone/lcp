<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmKmkLineMst extends ClsComDb
{
    // '**********************************************************************
    // '処 理 名：ラインマスタからライン№を抽出する
    // '関 数 名：fncLineMstSelectSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：ラインマスタからライン№を抽出する
    // '**********************************************************************
    function fncLineMstSelectSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT LINE_NO";
        $strSQL .= "  FROM   HLINEMST";
        $strSQL .= "    ORDER BY LINE_NO";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：科目ラインマスタのデータを抽出する
    // '関 数 名：fncKmkLineMstSelectSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：科目ラインマスタのデータを抽出する
    // '**********************************************************************
    function fncKmkLineMstSelectSQL($strLineNO)
    {
        $strSQL = "";
        $strSQL = "SELECT KAMOK_CD";
        $strSQL .= ",      HIMOK_CD";
        $strSQL .= ",      TAISK_KB";
        $strSQL .= ",      CAL_KB";
        $strSQL .= ",      PRN_KB1";
        $strSQL .= ",      PRN_KB2";
        $strSQL .= ",      PRN_KB3";
        $strSQL .= ",      PRN_KB4";
        $strSQL .= ",      PRN_KB5";
        $strSQL .= ",   to_char(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HKMKLINEMST";
        $strSQL .= "  WHERE  LINE_NO = '" . $strLineNO . "'";
        $strSQL .= "    ORDER BY KAMOK_CD,HIMOK_CD";

        return $strSQL;
    }

    function fncDeleteRowSQL($strKamokuData, $strHimokuData, $strLineNo)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HKMKLINEMST WHERE KAMOK_CD = '";
        $strSQL .= $strKamokuData . "'";
        $strSQL .= "  AND HIMOK_CD = '";
        $strSQL .= $strHimokuData . "'";
        $strSQL .= "  AND LINE_NO = ";
        $strSQL .= $strLineNo;

        return $strSQL;
    }

    function fncDeleteKmkLineMstSQL($strLineNo)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HKMKLINEMST";
        $strSQL .= "  WHERE  LINE_NO = '" . $strLineNo . "'";

        return $strSQL;
    }

    function fncInsertKmkLineMstSQL($arrInputData, $strLineNo)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $arrInputData['KAMOK_CD'] = $this->FncSqlNv2(rtrim($arrInputData['KAMOK_CD']), "", 1);
        $arrInputData['HIMOK_CD'] = $this->FncSqlNv2(rtrim($arrInputData['HIMOK_CD']), "", 1);
        $arrInputData['TAISK_KB'] = $this->FncSqlNv2(rtrim($arrInputData['TAISK_KB']), "", 1);
        $arrInputData['CAL_KB'] = $this->FncSqlNv2(rtrim($arrInputData['CAL_KB']), "", 2);
        $arrInputData['PRN_KB1'] = $this->FncSqlNv2(rtrim($arrInputData['PRN_KB1']), "", 1);
        $arrInputData['PRN_KB2'] = $this->FncSqlNv2(rtrim($arrInputData['PRN_KB2']), "", 1);
        $arrInputData['PRN_KB3'] = $this->FncSqlNv2(rtrim($arrInputData['PRN_KB3']), "", 1);
        $arrInputData['PRN_KB4'] = $this->FncSqlNv2(rtrim($arrInputData['PRN_KB4']), "", 1);
        $arrInputData['PRN_KB5'] = $this->FncSqlNv2(rtrim($arrInputData['PRN_KB5']), "", 1);

        $arrInputData['CREATE_DATE'] = rtrim($arrInputData['CREATE_DATE']) != "" ? "TO_DATE(" . $this->FncSqlNv2(rtrim($arrInputData['CREATE_DATE']), "", 1) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";

        $strSQL = "";
        $strSQL = "INSERT INTO HKMKLINEMST";
        $strSQL .= "(      KAMOK_CD";
        $strSQL .= ",      HIMOK_CD";
        $strSQL .= ",      LINE_NO";
        $strSQL .= ",      TAISK_KB";
        $strSQL .= ",      CAL_KB";
        $strSQL .= ",      PRN_KB1";
        $strSQL .= ",      PRN_KB2";
        $strSQL .= ",      PRN_KB3";
        $strSQL .= ",      PRN_KB4";
        $strSQL .= ",      PRN_KB5";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";

        $strSQL .= ") VALUES ( ";

        $strSQL .= $arrInputData['KAMOK_CD'];
        $strSQL .= " , " . $arrInputData['HIMOK_CD'];
        $strSQL .= " , " . $strLineNo;
        $strSQL .= " , " . $arrInputData['TAISK_KB'];
        $strSQL .= " , " . $arrInputData['CAL_KB'];
        $strSQL .= " , " . $arrInputData['PRN_KB1'];
        $strSQL .= " , " . $arrInputData['PRN_KB2'];
        $strSQL .= " , " . $arrInputData['PRN_KB3'];
        $strSQL .= " , " . $arrInputData['PRN_KB4'];
        $strSQL .= " , " . $arrInputData['PRN_KB5'];
        $strSQL .= " ,  SYSDATE";
        $strSQL .= " , " . $arrInputData['CREATE_DATE'];
        $strSQL .= ", '" . $UPDUSER . "'";
        $strSQL .= ", 'KmkLineMst'";
        $strSQL .= ", '" . $UPDCLTNM . "'";
        $strSQL .= " )";

        return $strSQL;
    }

    public function fncSelectLine()
    {
        $strSql = $this->fncLineMstSelectSQL();
        return parent::select($strSql);
    }

    public function fncSelectKamoku($strLineNO)
    {
        $strSql = $this->fncKmkLineMstSelectSQL($strLineNO);
        return parent::select($strSql);
    }

    public function fncDeleteRow($strKamokuData, $strHimokuData, $strLineNo)
    {
        return parent::delete($this->fncDeleteRowSQL($strKamokuData, $strHimokuData, $strLineNo));
    }

    public function fncDeleteKmkLineMst($strLineNo)
    {
        return parent::Do_Execute($this->fncDeleteKmkLineMstSQL($strLineNo));
    }

    public function fncInsertKmkLineMst($arrInputData, $strLineNo)
    {
        return parent::Do_Execute($this->fncInsertKmkLineMstSQL($arrInputData, $strLineNo));
    }

    // '**********************************************************************
    // '処 理 名：Null変換関数(文字)
    // '関 数 名：FncNv
    // '引    数：objValue     (I)文字列
    // '　　　　：objReturn    (I)NULL変換後の値
    // '戻 り 値：変換後の値
    // '処理説明：Null変換(文字)を行う。
    // '**********************************************************************
    function FncSqlNv2($objValue, $objReturn, $intKind)
    {
        //'---NULLの場合---
        if ($objValue === null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "''";
            }
        } else {
            //'---以外の場合
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