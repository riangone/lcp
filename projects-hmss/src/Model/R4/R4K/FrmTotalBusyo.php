<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmTotalBusyo extends ClsComDb
{
    // '**********************************************************************
    // '処 理 名：部署マスタから抽出する
    // '関 数 名：fncBusyoMstSelect
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：部署マスタから抽出する
    // '**********************************************************************
    function fncBusyoMstSelectSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT BUSYO_CD";
        $strSQL .= ",      BUSYO_NM";
        $strSQL .= "  FROM   HBUSYO";
        $strSQL .= "  WHERE  SYUKEI_KB = '1'";
        $strSQL .= "  ORDER BY BUSYO_CD";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：集計部署マスタのデータを抽出する
    // '関 数 名：fncTTLBusyoMstSelect
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：集計部署マスタのデータを抽出する
    // '**********************************************************************
    function fncTTLBusyoMstSelectSQL($strBusyoCD)
    {
        $strSQL = "";
        $strSQL = "SELECT TTL.BUSYO_CD";
        $strSQL .= ",      BUS.BUSYO_NM ";
        $strSQL .= ",   to_char(TTL.CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HTTLBUSYO TTL";
        $strSQL .= "  LEFT JOIN HBUSYO BUS";
        $strSQL .= "  ON     TTL.BUSYO_CD = BUS.BUSYO_CD";
        $strSQL .= "  WHERE  TOTAL_BUSYO_CD = '" . $strBusyoCD . "'";

        $strSQL .= "  ORDER BY TTL.BUSYO_CD";

        return $strSQL;
    }

    //'**********************************************************************
    // '処 理 名：中古車部門加算部署マスタのデータを抽出する
    // '関 数 名：fncPlusTTLBusyoMstSelect
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：中古車部門加算部署マスタのデータを抽出する
    // '**********************************************************************
    function fncPlusTTLBusyoMstSelectSQL($strBusyoCD)
    {
        $strSQL = "";
        $strSQL = "SELECT PTTL.BUSYO_CD";
        $strSQL .= ",      BUS.BUSYO_NM ";
        $strSQL .= ",   to_char(PTTL.CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HPLUSTTLBUSYO PTTL";
        $strSQL .= "  LEFT JOIN HBUSYO BUS";
        $strSQL .= "  ON     PTTL.BUSYO_CD = BUS.BUSYO_CD";
        $strSQL .= "  WHERE  TOTAL_BUSYO_CD = '" . $strBusyoCD . "'";

        $strSQL .= "  ORDER BY PTTL.BUSYO_CD";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：中古車部門加算科目ライン設定マスタの集計部署コードを抽出する
    // '関 数 名：fncPlusTTLKmkLineMstSelect
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：中古車部門加算科目ライン設定マスタの集計部署コードを抽出する
    // '**********************************************************************
    function fncPlusKMKLineMstSelectSQL($strBusyoCD)
    {
        $strSQL = "";
        $strSQL = "SELECT TOTAL_BUSYO_CD";
        $strSQL .= "  FROM   HPLUSKMKLINEMST ";
        $strSQL .= "WHERE  TOTAL_BUSYO_CD = '" . $strBusyoCD . "'";

        return $strSQL;
    }

    function fncDeleteRowSQL($strBusyoCD, $strTotal)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HTTLBUSYO WHERE BUSYO_CD = '" . $strBusyoCD . "'";
        $strSQL .= "  AND TOTAL_BUSYO_CD = '" . $strTotal . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：取引先名を取得
    // '関 数 名：fncToriNmSelect
    // '引    数：無し
    // '戻 り 値：SQL
    // '処理説明：取引先名を取得
    // '**********************************************************************
    function fncBusyoNmSelectSQL($strBusyoCD)
    {
        $strSQL = "";
        $strSQL = "SELECT BUSYO_NM  FROM   HBUSYO WHERE  BUSYO_CD = '" . $strBusyoCD . "'";

        return $strSQL;
    }

    function fncDeletePlusRowSQL($strBusyoCD, $strTotal)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HPLUSTTLBUSYO WHERE BUSYO_CD = '" . $strBusyoCD . "'";
        $strSQL .= "  AND TOTAL_BUSYO_CD = '" . $strTotal . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：集計部署マスタを削除する
    // '関 数 名：fncDeleteKmkLineMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：集計部署マスタを削除する
    // '**********************************************************************
    function fncDeleteTTLBusyoSQL($strTotal)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HTTLBUSYO";
        $strSQL .= "  WHERE  TOTAL_BUSYO_CD = '" . $strTotal . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：集計部署ﾏｽﾀに追加する
    // '関 数 名：fncInsertOkaiageMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：集計部署マスタに追加する
    // '**********************************************************************
    function fncInsertTTLBusyoSQL($arrInputData, $strTotal)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $arrInputData['BUSYO_CD'] = $this->FncSqlNv2(rtrim($arrInputData['BUSYO_CD']), "", 1);
        $arrInputData['CREATE_DATE'] = rtrim($arrInputData['CREATE_DATE']) != "" ? "TO_DATE(" . $this->FncSqlNv2(rtrim($arrInputData['CREATE_DATE']), "", 1) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";

        $strSQL = "";
        $strSQL = "INSERT INTO HTTLBUSYO";
        $strSQL .= "(      BUSYO_CD";
        $strSQL .= ",      TOTAL_BUSYO_CD";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";

        $strSQL .= ") VALUES ( ";

        $strSQL .= $arrInputData['BUSYO_CD'];
        $strSQL .= " , '" . $strTotal . "'";
        $strSQL .= " , SYSDATE";
        $strSQL .= " , " . $arrInputData['CREATE_DATE'];

        $strSQL .= ", '" . $UPDUSER . "'";
        $strSQL .= ", 'TotalBusyo'";
        $strSQL .= ", '" . $UPDCLTNM . "'";
        $strSQL .= " )";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：中古車部門加算部署マスタを削除する
    // '関 数 名：fncDeletePlusTTLBusyo
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：中古車部門加算部署マスタを削除する
    // '**********************************************************************
    function fncDeletePlusTTLBusyoSQL($strTotal)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HPLUSTTLBUSYO";
        $strSQL .= "  WHERE  TOTAL_BUSYO_CD = '" . $strTotal . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：中古車部門加算部署ﾏｽﾀに追加する
    // '関 数 名：fncInsertPlusTTLBusyo
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：中古車部門加算部署マスタに追加する
    // '**********************************************************************
    function fncInsertPlusTTLBusyoSQL($arrInputData, $strTotal)
    {
        $arrInputData['BUSYO_CD'] = $this->FncSqlNv2(rtrim($arrInputData['BUSYO_CD']), "", 1);
        $arrInputData['CREATE_DATE'] = rtrim($arrInputData['CREATE_DATE']) != "" ? "TO_DATE(" . $this->FncSqlNv2(rtrim($arrInputData['CREATE_DATE']), "", 1) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";

        $strSQL = "";
        $strSQL = "INSERT INTO HPLUSTTLBUSYO";
        $strSQL .= "(      BUSYO_CD";
        $strSQL .= ",      TOTAL_BUSYO_CD";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";

        $strSQL .= ") VALUES ( ";

        $strSQL .= $arrInputData['BUSYO_CD'];
        $strSQL .= " , '" . $strTotal . "'";
        $strSQL .= " , SYSDATE";
        $strSQL .= " , " . $arrInputData['CREATE_DATE'];
        $strSQL .= " )";

        return $strSQL;
    }

    public function fncBusyoMstSelect()
    {
        $strSql = $this->fncBusyoMstSelectSQL();
        return parent::select($strSql);
    }

    public function fncTTLBusyoMstSelect($strBusyoCD)
    {
        $strSql = $this->fncTTLBusyoMstSelectSQL($strBusyoCD);
        return parent::select($strSql);
    }

    public function fncPlusTTLBusyoMstSelect($strBusyoCD)
    {
        $strSql = $this->fncPlusTTLBusyoMstSelectSQL($strBusyoCD);
        return parent::select($strSql);
    }

    public function fncPlusKMKLineMstSelect($strBusyoCD)
    {
        $strSql = $this->fncPlusKMKLineMstSelectSQL($strBusyoCD);
        return parent::select($strSql);
    }

    public function fncDeleteRow($strBusyoCD, $strTotal)
    {
        $strSql = $this->fncDeleteRowSQL($strBusyoCD, $strTotal);
        return parent::delete($strSql);
    }

    public function fncBusyoNmSelect($strBusyoCD)
    {
        $strSql = $this->fncBusyoNmSelectSQL($strBusyoCD);
        return parent::select($strSql);
    }

    public function fncDeletePlusRow($strBusyoCD, $strTotal)
    {
        $strSql = $this->fncDeletePlusRowSQL($strBusyoCD, $strTotal);
        return parent::delete($strSql);
    }

    public function fncDeleteTTLBusyo($strTotal)
    {
        $strSql = $this->fncDeleteTTLBusyoSQL($strTotal);
        return parent::Do_Execute($strSql);
    }

    public function fncInsertTTLBusyo($arrInputData, $strTotal)
    {
        $strSql = $this->fncInsertTTLBusyoSQL($arrInputData, $strTotal);
        return parent::Do_Execute($strSql);
    }

    public function fncDeletePlusTTLBusyo($strTotal)
    {
        $strSql = $this->fncDeletePlusTTLBusyoSQL($strTotal);
        return parent::Do_Execute($strSql);
    }

    public function fncInsertPlusTTLBusyo($arrInputData, $strTotal)
    {
        $strSql = $this->fncInsertPlusTTLBusyoSQL($arrInputData, $strTotal);
        return parent::Do_Execute($strSql);
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
