<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmYosanTTLBusyoMst extends ClsComDb
{
    // '**********************************************************************
    // '処 理 名：ラインマスタからライン№を抽出する
    // '関 数 名：fncBusyoMstSelectSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：ラインマスタからライン№を抽出する
    // '**********************************************************************
    function fncBusyoMstSelectSQL()
    {
        $strSQL = "";

        $strSQL .= "SELECT BUSYO_CD";
        $strSQL .= ",      BUSYO_NM";
        $strSQL .= "   FROM   HBUSYO";
        $strSQL .= "   WHERE  SYUKEI_KB = '1'";
        $strSQL .= "   ORDER BY BUSYO_CD";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：予算集計部署マスタのデータを抽出する
    // '関 数 名：fncYOSANTTLBusyoMstSelectSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：予算集計部署マスタのデータを抽出する
    // '**********************************************************************
    function fncYOSANTTLBusyoMstSelectSQL($strBusyoCD)
    {
        $strSQL = "";

        $strSQL .= "SELECT TTL.BUSYO_CD";
        $strSQL .= ",      BUS.BUSYO_NM ";
        $strSQL .= ",   to_char(TTL.CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HYOSANTTLBUSYO TTL";
        $strSQL .= "  LEFT JOIN HBUSYO BUS";
        $strSQL .= "  ON     TTL.BUSYO_CD = BUS.BUSYO_CD";
        $strSQL .= "  WHERE  TOTAL_BUSYO_CD = '@BUSYOCD'";

        $strSQL = str_replace('@BUSYOCD', $strBusyoCD, $strSQL);

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：部署名を取得
    // '関 数 名：fncBusyoNmSelectSQL
    // '引    数：無し
    // '戻 り 値：SQL
    // '処理説明：部署名を取得
    // '**********************************************************************
    function fncBusyoNmSelectSQL($strBusyoCD)
    {
        $strSQL = "";
        $strSQL = "SELECT BUSYO_NM  FROM   HBUSYO WHERE  BUSYO_CD = '" . $strBusyoCD . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：集計部署マスタを選択行削除する
    // '関 数 名：fncDeleteRowDataSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：集計部署マスタを選択行削除する
    // '**********************************************************************
    function fncDeleteRowDataSQL($strBusyoCD, $strTotal)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HYOSANTTLBUSYO WHERE BUSYO_CD = '";
        $strSQL .= $strBusyoCD . "'";
        $strSQL .= "  AND TOTAL_BUSYO_CD = '";
        $strSQL .= $strTotal . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：集計部署マスタを削除する
    // '関 数 名：fncDeleteYosanTTLBusyoSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：集計部署マスタを削除する
    // '**********************************************************************
    function fncDeleteYosanTTLBusyoSQL($strBusyoCD)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HYOSANTTLBUSYO";
        $strSQL .= "  WHERE  TOTAL_BUSYO_CD = '@TBUS'";
        $strSQL = str_replace("@TBUS", $strBusyoCD, $strSQL);

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：集計部署ﾏｽﾀに追加する
    // '関 数 名：fncInsertYOSANTTLBusyoSQL
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：集計部署マスタに追加する
    // '**********************************************************************
    function fncInsertYOSANTTLBusyoSQL($inputData, $strBusyoCD)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];
        $UPDAPP = "YosanTTLBusyoMst";

        $strSQL = "";

        $strSQL .= "INSERT INTO HYOSANTTLBUSYO";
        $strSQL .= "(      BUSYO_CD";
        $strSQL .= ",      TOTAL_BUSYO_CD";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";

        $strSQL .= ") VALUES ( ";

        $strSQL .= " " . $this->FncSqlNv2(rtrim($inputData['BUSYO_CD']), "", 1);
        $strSQL .= ", '" . $strBusyoCD . "'";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", " . (rtrim($inputData['CREATE_DATE']) != "" ? "TO_DATE(" . $this->FncSqlNv2(rtrim($inputData['CREATE_DATE']), "", 1) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE");
        $strSQL .= ", '@UPDUSER'";
        $strSQL .= ", '@UPDAPP'";
        $strSQL .= ", '@UPDCLT'";

        $strSQL .= ")";

        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);

        return $strSQL;
    }

    public function fncBusyoMstSelect()
    {
        $strsql = $this->fncBusyoMstSelectSQL();
        return parent::select($strsql);
    }

    public function fncYOSANTTLBusyoMstSelect($strBusyoCD)
    {
        $strsql = $this->fncYOSANTTLBusyoMstSelectSQL($strBusyoCD);
        return parent::select($strsql);
    }

    public function fncBusyoNmSelect($strBusyoCD)
    {
        $strSql = $this->fncBusyoNmSelectSQL($strBusyoCD);
        return parent::select($strSql);
    }

    public function fncDeleteRowData($strBusyoCD, $strTotal)
    {
        $strSql = $this->fncDeleteRowDataSQL($strBusyoCD, $strTotal);
        return parent::delete($strSql);
    }

    public function fncDeleteYosanTTLBusyo($strBusyoCD)
    {
        $strsql = $this->fncDeleteYosanTTLBusyoSQL($strBusyoCD);
        return parent::Do_Execute($strsql);
    }

    public function fncInsertYOSANTTLBusyo($inputData, $strBusyoCD)
    {
        $strsql = $this->fncInsertYOSANTTLBusyoSQL($inputData, $strBusyoCD);
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