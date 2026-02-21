<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmYosanTorikomiMst extends ClsComDb
{
    public $ClsComFnc;

    // '**********************************************************************
    // '処 理 名：基本情報を抽出する
    // '関 数 名：fncListSyasyuMstSel
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：基本情報を抽出する
    // '**********************************************************************
    function fncYosanTorikomiMstSelSQL($BUSYO_KB)
    {
        $strSQL = "";

        $strSQL = "SELECT BUSYO_KB";
        $strSQL .= ",      LINE_NO";
        $strSQL .= ",      EXCEL_LINE_NO";
        $strSQL .= ",      RND_POS";
        $strSQL .= ",      CAL_KB";
        $strSQL .= ",   to_char(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HYOSANTORIKOMIMST";

        if (rtrim($BUSYO_KB) != "") {
            $strSQL .= "  WHERE BUSYO_KB = '@BUSYOKB'";
        }

        $strSQL .= "  ORDER BY BUSYO_KB, LINE_NO";
        $strSQL = str_replace("@BUSYOKB", rtrim($BUSYO_KB), $strSQL);

        return $strSQL;
    }

    function fncDeleteRowDataSQL($BUSYO_KB, $LINE_NO, $EXCEL_LINE_NO)
    {
        $strSQL = "";
        $this->ClsComFnc = new ClsComFnc();

        $strSQL .= "DELETE FROM HYOSANTORIKOMIMST WHERE BUSYO_KB = '";
        $strSQL .= $BUSYO_KB . "'";
        $strSQL .= " AND   LINE_NO = ";
        $strSQL .= $this->ClsComFnc->FncSqlNz($LINE_NO);
        $strSQL .= " AND   EXCEL_LINE_NO = ";
        $strSQL .= $this->ClsComFnc->FncSqlNz($EXCEL_LINE_NO);

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：重複チェック２ 　新規追加分に重複データがないかチェック
    // '関 数 名：checkExistDataSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：重複チェック２ 　新規追加分に重複データがないかチェック
    // '**********************************************************************
    function checkExistDataSQL($inputDatas)
    {
        $strSQL = "";

        $strSQL .= "SELECT BUSYO_KB, LINE_NO, EXCEL_LINE_NO";
        $strSQL .= "  FROM   HYOSANTORIKOMIMST";
        $strSQL .= "  WHERE  BUSYO_KB = '@BUSYOKB'";
        $strSQL .= "  AND    LINE_NO = @LINENO";
        $strSQL .= "  AND    EXCEL_LINE_NO = @EXCELNO";

        $this->ClsComFnc = new ClsComFnc();

        $strSQL = str_replace("@BUSYOKB", rtrim($inputDatas["BUSYO_KB"]), $strSQL);
        $strSQL = str_replace("@LINENO", $this->ClsComFnc->FncSqlNz(rtrim($inputDatas["LINE_NO"])), $strSQL);
        $strSQL = str_replace("@EXCELNO", $this->ClsComFnc->FncSqlNz(rtrim($inputDatas["EXCEL_LINE_NO"])), $strSQL);

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：削除する
    // '関 数 名：fncDeleteYosanLineMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：削除する
    // '**********************************************************************
    function fncDeleteYosanTorikomiMstSQL($BUSYO_KB)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HYOSANTORIKOMIMST";

        if ($BUSYO_KB != "") {
            $strSQL .= "  WHERE   BUSYO_KB = '@BUSYOKB'";
        }

        $strSQL = str_replace("@BUSYOKB", $BUSYO_KB, $strSQL);

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：マスタに追加する
    // '関 数 名：fncInsertListSyasuMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：マスタに追加する
    // '**********************************************************************
    function fncInsertYosanTorikomiMstSQL($inputDatas)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $inputData['BUSYO_KB'] = $this->FncSqlNv2(rtrim($inputDatas['BUSYO_KB']), "", 1);
        $inputData['LINE_NO'] = $this->FncSqlNv2(rtrim($inputDatas['LINE_NO']), "", 2);
        $inputData['EXCEL_LINE_NO'] = $this->FncSqlNv2(rtrim($inputDatas['EXCEL_LINE_NO']), "", 2);
        $inputData['RND_POS'] = $this->FncSqlNv2(rtrim($inputDatas['RND_POS']), "", 2);
        $inputData['CAL_KB'] = $this->FncSqlNv2(rtrim($inputDatas['CAL_KB']), "", 2);
        $inputData['CREATE_DATE'] = rtrim($inputDatas['CREATE_DATE']) != "" ? "TO_DATE(" . $this->FncSqlNv2(rtrim($inputDatas['CREATE_DATE']), "", 1) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";

        $strSQL = "";

        $strSQL .= "INSERT INTO HYOSANTORIKOMIMST";
        $strSQL .= "(      BUSYO_KB";
        $strSQL .= ",      LINE_NO";
        $strSQL .= ",      EXCEL_LINE_NO";
        $strSQL .= ",      RND_POS";
        $strSQL .= ",      CAL_KB";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";

        $strSQL .= ") VALUES ( ";

        $strSQL .= $inputData['BUSYO_KB'];
        $strSQL .= " ," . $inputData['LINE_NO'];
        $strSQL .= " ," . $inputData['EXCEL_LINE_NO'];
        $strSQL .= " ," . $inputData['RND_POS'];
        $strSQL .= " ," . $inputData['CAL_KB'];
        $strSQL .= " , SYSDATE";
        $strSQL .= " , " . $inputData['CREATE_DATE'];
        $strSQL .= " , '" . $UPDUSER . "'";
        $strSQL .= " ,'YosanTorikomiMst'";
        $strSQL .= " , '" . $UPDCLTNM . "'";
        $strSQL .= ")";

        return $strSQL;
    }

    public function fncYosanTorikomiMstSel($BUSYO_KB)
    {
        $strsql = $this->fncYosanTorikomiMstSelSQL($BUSYO_KB);
        return parent::select($strsql);
    }

    public function fncDeleteRowData($BUSYO_KB, $LINE_NO, $EXCEL_LINE_NO)
    {
        $strsql = $this->fncDeleteRowDataSQL($BUSYO_KB, $LINE_NO, $EXCEL_LINE_NO);
        return parent::delete($strsql);
    }

    public function checkExistData($inputDatas)
    {
        $strsql = $this->checkExistDataSQL($inputDatas);
        return parent::select($strsql);
    }

    public function fncDeleteYosanTorikomiMst($BUSYO_KB)
    {
        $strsql = $this->fncDeleteYosanTorikomiMstSQL($BUSYO_KB);
        return parent::Do_Execute($strsql);
    }

    public function fncInsertYosanTorikomiMst($inputDatas)
    {
        $strsql = $this->fncInsertYosanTorikomiMstSQL($inputDatas);
        return parent::Do_Execute($strsql);
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