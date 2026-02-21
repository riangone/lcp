<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmTeisyu extends ClsComDb
{
    // '**********************************************************************
    // '処 理 名：社員ﾏｽﾀから定収ファイルの基本情報を抽出する
    // '関 数 名：fncFromSyainSelect
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：社員ﾏｽﾀから定収ファイルの基本情報を抽出する
    // '**********************************************************************
    function fncFromSyainSelectSQL($strBusyuCD)
    {
        $strSQL = "";
        $strSQL = "SELECT SYA.SYAIN_NO";
        $strSQL .= ",      SYA.SYAIN_NM ";
        $strSQL .= ",      BUS.BUSYO_NM ";
        $strSQL .= ",      TSY.TEISYU ";
        $strSQL .= ",      TSY.HOYU ";
        $strSQL .= ",   to_char(TSY.CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HSYAINMST SYA ";
        $strSQL .= "  LEFT JOIN  ";
        $strSQL .= "       HTEISYU TSY ";
        $strSQL .= "  ON     TSY.SYAIN_NO = SYA.SYAIN_NO ";
        $strSQL .= "  LEFT JOIN  ";
        $strSQL .= "       HKEIRICTL KRI ";
        $strSQL .= "  ON KRI.ID = '01' ";
        $strSQL .= "  LEFT JOIN HHAIZOKU M_HAI  ";
        $strSQL .= "  ON      M_HAI.SYAIN_NO = SYA.SYAIN_NO  ";
        $strSQL .= "  AND     M_HAI.START_DATE <= TO_CHAR(LAST_DAY(TO_DATE(KRI.SYR_YMD || '01','YYYYMMDD')),'YYYYMMDD')  ";
        $strSQL .= "  AND     NVL(M_HAI.END_DATE,'99999999')>= TO_CHAR(LAST_DAY(TO_DATE(KRI.SYR_YMD || '01','YYYYMMDD')),'YYYYMMDD')  ";
        $strSQL .= "  LEFT JOIN ";
        $strSQL .= "       HBUSYO BUS  ";
        $strSQL .= "  ON     BUS.BUSYO_CD = M_HAI.BUSYO_CD  ";

        $strSQL .= "  WHERE KRI.SYR_YMD || '01' <= NVL(SYA.TAISYOKU_DATE,'99999999')";

        if ($strBusyuCD != "") {
            $strSQL .= "  AND  M_HAI.BUSYO_CD = '@BUSYOCD'  ";
        }

        $strSQL .= "  ORDER BY M_HAI.BUSYO_CD, SYA.SYAIN_NO";
        $strSQL = str_replace("@BUSYOCD", rtrim($strBusyuCD), $strSQL);

        return $strSQL;
    }

    function frmHTEISYUDeleteRowSQL($SYAIN_NO)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HTEISYU WHERE SYAIN_NO = '" . $SYAIN_NO . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：定収ファイルを削除する
    // '関 数 名：fncDeleteTeisyuMst
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：定収ファイルを削除する
    // '**********************************************************************
    function fncDeleteTeisyuMstSQL($Busyo_CD)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HTEISYU A  ";
        $strSQL .= "WHERE  EXISTS  ";
        $strSQL .= "       (SELECT B.SYAIN_NO";
        $strSQL .= "        FROM   HSYAINMST B";
        $strSQL .= "        LEFT JOIN";
        $strSQL .= "               HKEIRICTL KRI";
        $strSQL .= "        ON KRI.ID = '01'";
        $strSQL .= "        LEFT JOIN HHAIZOKU M_HAI";
        $strSQL .= "        ON      M_HAI.SYAIN_NO = B.SYAIN_NO";
        $strSQL .= "        AND     M_HAI.START_DATE <= TO_CHAR(LAST_DAY(TO_DATE(KRI.SYR_YMD || '01','YYYYMMDD')),'YYYYMMDD')";
        $strSQL .= "        AND     NVL(M_HAI.END_DATE,'99999999')>= TO_CHAR(LAST_DAY(TO_DATE(KRI.SYR_YMD || '01','YYYYMMDD')),'YYYYMMDD')";
        $strSQL .= "        WHERE  A.SYAIN_NO = B.SYAIN_NO";

        if ($Busyo_CD != "") {
            $strSQL .= "         AND    M_HAI.BUSYO_CD = '@BUSYO'";
        }

        $strSQL .= ")";

        $strSQL = str_replace("@BUSYO", $Busyo_CD, $strSQL);

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：定収ファイルに追加する
    // '関 数 名：fncInsertTeisyu
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：定収ファイルに追加する
    // '**********************************************************************
    function fncInsertTeisyuSQL($inputData)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $inputData['SYAIN_NO'] = $this->FncSqlNv2(rtrim($inputData['SYAIN_NO']), "", 1);
        $inputData['TEISYU'] = $this->FncSqlNv2(rtrim($inputData['TEISYU']), "", 2);
        $inputData['HOYU'] = $this->FncSqlNv2(rtrim($inputData['HOYU']), "", 2);
        $inputData['CREATE_DATE'] = rtrim($inputData['CREATE_DATE']) != "" ? "TO_DATE(" . $this->FncSqlNv2(rtrim($inputData['CREATE_DATE']), "", 1) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";

        $strSQL = "";
        $strSQL = "INSERT INTO HTEISYU";
        $strSQL .= "(      SYAIN_NO";
        $strSQL .= " ,      TEISYU";
        $strSQL .= " ,      HOYU";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= " ,      CREATE_DATE";
        $strSQL .= " ,      UPD_SYA_CD";
        $strSQL .= " ,      UPD_PRG_ID";
        $strSQL .= " ,      UPD_CLT_NM";
        $strSQL .= ") VALUES ( ";

        $strSQL .= $inputData['SYAIN_NO'];
        $strSQL .= " ," . $inputData['TEISYU'];
        $strSQL .= " ," . $inputData['HOYU'];
        $strSQL .= " , SYSDATE";
        $strSQL .= " , " . $inputData['CREATE_DATE'];
        $strSQL .= " , '" . $UPDUSER . "'";
        $strSQL .= " ,'Teisyu'";
        $strSQL .= " , '" . $UPDCLTNM . "'";

        $strSQL .= ")";

        return $strSQL;
    }

    public function fncFromSyainSelect($strBusyoCD)
    {
        $strSql = $this->fncFromSyainSelectSQL($strBusyoCD);
        return parent::select($strSql);
    }

    public function frmHTEISYUDeleteRow($SYAIN_NO)
    {
        $strSql = $this->frmHTEISYUDeleteRowSQL($SYAIN_NO);
        return parent::delete($strSql);
    }

    public function fncDeleteTeisyuMst($Busyo_CD)
    {
        $strSql = $this->fncDeleteTeisyuMstSQL($Busyo_CD);
        return parent::Do_Execute($strSql);
    }

    public function fncInsertTeisyu($inputData)
    {
        $strSql = $this->fncInsertTeisyuSQL($inputData);
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
