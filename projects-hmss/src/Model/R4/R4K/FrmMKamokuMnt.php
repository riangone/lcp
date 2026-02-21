<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmMKamokuMnt extends ClsComDb
{
    // '**********************************************************************
    // '処 理 名：科目費目マスタのデータを抽出する
    // '関 数 名：fncKmkHmkMstSelectSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：科目費目マスタのデータを抽出する
    // '**********************************************************************
    function fncKmkHmkMstSelectSQL($kamokuCD)
    {
        $strSQL = "";
        $strSQL = "SELECT KAMOK_CD";
        $strSQL .= ",      TRIM(KOMOK_CD) KOMOK_CD";
        $strSQL .= ",      KAMOK_NM";
        $strSQL .= ",      KAMOK_KANA";
        $strSQL .= ",      KOMOK_NM";
        $strSQL .= ",      ZEI_KB";
        $strSQL .= ",      TAISK_KB";
        $strSQL .= ",      ICHI";
        $strSQL .= ",      GDMZ_CD";
        $strSQL .= ",      KYOTN_KB";
        $strSQL .= ",      KYOTN_CD";
        $strSQL .= ",      '1' FLAG";

        $strSQL .= "  FROM   M_KAMOKU";

        if ($kamokuCD != "") {
            $strSQL .= "  WHERE  KAMOK_CD = '" . $kamokuCD . "'";
        }

        $strSQL .= "  ORDER BY KAMOK_CD, KOMOK_CD";

        return $strSQL;
    }

    function fncDeleteRowSQL($strKamokuData, $strKomokuData)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM M_KAMOKU WHERE KAMOK_CD = '" . $strKamokuData . "'";
        $strSQL .= " AND KOMOK_CD = '";

        if ($strKomokuData == "") {
            $strSQL .= "     '";
        } else {
            $strSQL .= $strKomokuData . "'";
        }

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：科目マスタの存在チェック
    // '関 数 名：fncKmkChkSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：科目マスタの存在チェック
    // '**********************************************************************
    function fncKmkChkSQL($strKamokuData, $strKomokuData)
    {
        $strKamokuData = rtrim($strKamokuData);

        if ($strKomokuData == "") {
            $strKomokuData = "     ";
        }

        $strSQL = "";
        $strSQL = "SELECT KAMOK_CD";
        $strSQL .= "  FROM   M_KAMOKU";
        $strSQL .= "  WHERE  KAMOK_CD = '" . $strKamokuData . "'";
        $strSQL .= "  AND  KOMOK_CD = '" . $strKomokuData . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：科目費目マスタを削除する
    // '関 数 名：fncDeleteKmkHmkMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：科目費目マスタを削除する
    // '**********************************************************************
    function fncDeleteKmkHmkMst($strKamokuData)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM M_KAMOKU ";

        if ($strKamokuData != "") {
            $strSQL .= " WHERE KAMOK_CD = '" . $strKamokuData . "'";
        }

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：科目費目マスタに追加する
    // '関 数 名：fncInsertKmkHmkMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：科目費目マスタに追加する
    // '**********************************************************************
    function fncInsertKmkHmkMst($arrInputData)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $arrInputData['KAMOK_CD'] = $this->FncSqlNv2(rtrim($arrInputData['KAMOK_CD']), "", 1);

        if (rtrim($arrInputData['KOMOK_CD']) == "") {
            $arrInputData['KOMOK_CD'] = "'     '";
        } else {
            $arrInputData['KOMOK_CD'] = $this->FncSqlNv2($arrInputData['KOMOK_CD'], "", 1);
        }

        $arrInputData['KAMOK_NM'] = $this->FncSqlNv2(rtrim($arrInputData['KAMOK_NM']), "", 1);
        $arrInputData['KAMOK_KANA'] = $this->FncSqlNv2(rtrim($arrInputData['KAMOK_KANA']), "", 1);
        $arrInputData['KOMOK_NM'] = $this->FncSqlNv2(rtrim($arrInputData['KOMOK_NM']), "", 1);
        $arrInputData['ZEI_KB'] = $this->FncSqlNv2(rtrim($arrInputData['ZEI_KB']), "", 1);
        $arrInputData['TAISK_KB'] = $this->FncSqlNv2(rtrim($arrInputData['TAISK_KB']), "", 1);
        $arrInputData['ICHI'] = $this->FncSqlNv2(rtrim($arrInputData['ICHI']), 0, 1);
        $arrInputData['GDMZ_CD'] = $this->FncSqlNv2(rtrim($arrInputData['GDMZ_CD']), "", 1);
        $arrInputData['KYOTN_KB'] = $this->FncSqlNv2(rtrim($arrInputData['KYOTN_KB']), "", 1);
        $arrInputData['KYOTN_CD'] = $this->FncSqlNv2(rtrim($arrInputData['KYOTN_CD']), "", 1);

        $strSQL = "";
        $strSQL = "INSERT INTO M_KAMOKU";
        $strSQL .= "(      KAMOK_CD";
        $strSQL .= ",      KOMOK_CD";
        $strSQL .= ",      KAMOK_NM";
        $strSQL .= ",      KAMOK_KANA";
        $strSQL .= ",      KOMOK_NM";
        $strSQL .= ",      ZEI_KB";
        $strSQL .= ",      TAISK_KB";
        $strSQL .= ",      ICHI";
        $strSQL .= ",      GDMZ_CD";
        $strSQL .= ",      KYOTN_KB";
        $strSQL .= ",      KYOTN_CD";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";

        $strSQL .= ") VALUES ( ";

        $strSQL .= $arrInputData['KAMOK_CD'];
        $strSQL .= " , " . $arrInputData['KOMOK_CD'];
        $strSQL .= " , " . $arrInputData['KAMOK_NM'];
        $strSQL .= " , " . $arrInputData['KAMOK_KANA'];
        $strSQL .= " , " . $arrInputData['KOMOK_NM'];
        $strSQL .= " , " . $arrInputData['ZEI_KB'];
        $strSQL .= " , " . $arrInputData['TAISK_KB'];
        $strSQL .= " , " . $arrInputData['ICHI'];
        $strSQL .= " , " . $arrInputData['GDMZ_CD'];
        $strSQL .= " , " . $arrInputData['KYOTN_KB'];
        $strSQL .= " , " . $arrInputData['KYOTN_CD'];
        $strSQL .= ", '" . $UPDUSER . "'";
        $strSQL .= ", 'MKamokuMnt'";
        $strSQL .= ", '" . $UPDCLTNM . "'";
        $strSQL .= " )";

        return $strSQL;
    }

    public function fncSelect($postData)
    {
        $strSql = $this->fncKmkHmkMstSelectSQL($postData);
        return parent::select($strSql);
    }

    public function fncDeleteRow($strKamokuData, $strKomokuData)
    {
        return parent::delete($this->fncDeleteRowSQL($strKamokuData, $strKomokuData));
    }

    public function fncKmkChk($strKamokuData, $strKomokuData)
    {
        return parent::select($this->fncKmkChkSQL($strKamokuData, $strKomokuData));
    }

    public function fncMKamokuMntDelete($strKamokuData)
    {
        return parent::Do_Execute($this->fncDeleteKmkHmkMst($strKamokuData));
    }

    //科目マスタ更新処理を実行()
    public function fncMKamokuMntInsert($arrInputData)
    {
        return parent::Do_Execute($this->fncInsertKmkHmkMst($arrInputData));
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