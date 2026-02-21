<?php
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150929           #1998                        BUG                              Yuanjh
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmYosanMst extends ClsComDb
{
    function frmGetYearMonthSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT ID ";
        $strSQL .= "  ,      (SUBSTR(KISYU_YMD,1,4) || '/' || SUBSTR(KISYU_YMD,5,2) || '/' || SUBSTR(KISYU_YMD,7,2)) TOUGETU ";
        $strSQL .= "  FROM   HKEIRICTL ";
        $strSQL .= "  WHERE  ID = '01' ";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：社員ﾏｽﾀから定収ファイルの基本情報を抽出する
    // '関 数 名：fncFromSyainSelect
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：社員ﾏｽﾀから定収ファイルの基本情報を抽出する
    // '**********************************************************************
    function fncYosanSelectSQL($BUSYOCD, $KI)
    {
        $strSQL = "";
        $strSQL .= "SELECT YSN.LINE_NO";
        $strSQL .= ",      YSN.UPD_FPG";
        $strSQL .= ",      YSN.YSN_GK10";
        $strSQL .= ",      YSN.YSN_GK11";
        $strSQL .= ",      YSN.YSN_GK12";
        $strSQL .= ",      YSN.YSN_GK1";
        $strSQL .= ",      YSN.YSN_GK2";
        $strSQL .= ",      YSN.YSN_GK3";
        $strSQL .= ",      YSN.YSN_GK4";
        $strSQL .= ",      YSN.YSN_GK5";
        $strSQL .= ",      YSN.YSN_GK6";
        $strSQL .= ",      YSN.YSN_GK7";
        $strSQL .= ",      YSN.YSN_GK8";
        $strSQL .= ",      YSN.YSN_GK9";
        $strSQL .= ",   to_char(YSN.CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HYOSAN YSN";
        $strSQL .= "  WHERE  YSN.KI= @KI";

        if (rtrim($BUSYOCD) != "") {
            $strSQL .= "  AND    YSN.BUSYO_CD = '@BUSYOCD'";
        }

        $strSQL .= "  ORDER BY YSN.LINE_NO";

        $strSQL = str_replace("@BUSYOCD", rtrim($BUSYOCD), $strSQL);
        $strSQL = str_replace("@KI", (int) (substr($KI, 0, 4)) - 1917, $strSQL);

        return $strSQL;
    }

    function frmHYOSANDeleteRowSQL($BUSYOCD, $KI, $LINENO)
    {
        $strSQL = "";

        $strSQL = "DELETE FROM HYOSAN WHERE KI = ";
        $strSQL .= (substr($KI, 0, 4)) - 1917;
        $strSQL .= "  AND BUSYO_CD = '";
        $strSQL .= rtrim($BUSYOCD) . "'";
        $strSQL .= "  AND LINE_NO = ";
        $strSQL .= $LINENO + 1;

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：予算ﾏｽﾀを削除する
    // '関 数 名：fncDeleteYosan
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：定収ファイルを削除する
    // '**********************************************************************
    function fncDeleteYosanMst($BUSYOCD, $KI)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM HYOSAN";
        $strSQL .= "   WHERE  BUSYO_CD = '@BUSYO'";
        $strSQL .= "   AND    KI = @KI";

        $strSQL = str_replace("@BUSYO", is_string($BUSYOCD) ? rtrim($BUSYOCD) : '', $strSQL);
        $strSQL = str_replace("@KI", (substr($KI, 0, 4)) - 1917, $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：定収ファイルに追加する
    // '関 数 名：fncInsertTeisyu
    // '引    数：無し
    // '戻 り 値：ＳＱＬ文
    // '処理説明：定収ファイルに追加する
    // '**********************************************************************
    function fncInsertYosanMst($BUSYOCD, $KI, $KKRBUSYO, $INPUTDATA, $LINENO)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];
        $UPDAPP = "YosanMst";

        $strSQL = "";

        $strSQL = "INSERT INTO HYOSAN";
        $strSQL .= "(      YOSAN_YMD";
        $strSQL .= ",      KI";
        $strSQL .= ",      KKR_BUSYO_CD";
        $strSQL .= ",      BUSYO_CD";
        $strSQL .= ",      LINE_NO";
        $strSQL .= ",      UPD_FPG";
        $strSQL .= ",      YSN_GK10";
        $strSQL .= ",      YSN_GK11";
        $strSQL .= ",      YSN_GK12";
        $strSQL .= ",      YSN_GK1";
        $strSQL .= ",      YSN_GK2";
        $strSQL .= ",      YSN_GK3";
        $strSQL .= ",      YSN_GK4";
        $strSQL .= ",      YSN_GK5";
        $strSQL .= ",      YSN_GK6";
        $strSQL .= ",      YSN_GK7";
        $strSQL .= ",      YSN_GK8";
        $strSQL .= ",      YSN_GK9";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";

        $strSQL .= ") VALUES ( ";
        $strSQL .= "       '@YOSANYM'";
        $strSQL .= ",       @KI";
        $strSQL .= ",      '@KKRBUSYO'";
        $strSQL .= ",      '@BUSYOCD'";
        $strSQL .= ",     " . ($LINENO + 1);

        //$strSQL .= "," . $this -> FncSqlNv(rtrim($INPUTDATA['UPD_FPG']));
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['UPD_FPG']) ? rtrim($INPUTDATA['UPD_FPG']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK10']) ? rtrim($INPUTDATA['YSN_GK10']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK11']) ? rtrim($INPUTDATA['YSN_GK11']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK12']) ? rtrim($INPUTDATA['YSN_GK12']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK1']) ? rtrim($INPUTDATA['YSN_GK1']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK2']) ? rtrim($INPUTDATA['YSN_GK2']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK3']) ? rtrim($INPUTDATA['YSN_GK3']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK4']) ? rtrim($INPUTDATA['YSN_GK4']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK5']) ? rtrim($INPUTDATA['YSN_GK5']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK6']) ? rtrim($INPUTDATA['YSN_GK6']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK7']) ? rtrim($INPUTDATA['YSN_GK7']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK8']) ? rtrim($INPUTDATA['YSN_GK8']) : '', "", 2);
        $strSQL .= "," . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK9']) ? rtrim($INPUTDATA['YSN_GK9']) : '', "", 2);
        $strSQL .= ", SYSDATE";
        $strSQL .= ", " . (rtrim($INPUTDATA['CREATE_DATE']) != "" ? "TO_DATE(" . $this->FncSqlNv2(is_string($INPUTDATA['YSN_GK9']) ? rtrim($INPUTDATA['CREATE_DATE']) : '', "", 1) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE");
        $strSQL .= ", '@UPDUSER'";
        $strSQL .= ", '@UPDAPP'";
        $strSQL .= ", '@UPDCLT'";
        $strSQL .= ")";

        $strSQL = str_replace("@BUSYOCD", is_string($BUSYOCD) ? rtrim($BUSYOCD) : '', $strSQL);
        $strSQL = str_replace("@KI", (substr($KI, 0, 4)) - 1917, $strSQL);
        $strSQL = str_replace("@YOSANYM", str_replace("/", "", $KI), $strSQL);
        $strSQL = str_replace("@KKRBUSYO", is_string($KKRBUSYO) ? rtrim($KKRBUSYO) : '', $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);

        return $strSQL;

    }

    public function frmGetYearMonth()
    {
        $strsql = $this->frmGetYearMonthSQL();
        return parent::select($strsql);
    }

    public function fncYosanSelect($BUSYOCD, $KI)
    {
        $strsql = $this->fncYosanSelectSQL($BUSYOCD, $KI);
        return parent::select($strsql);
    }

    public function frmHYOSANDeleteRow($BUSYOCD, $KI, $LINENO)
    {
        $strsql = $this->frmHYOSANDeleteRowSQL($BUSYOCD, $KI, $LINENO);
        return parent::delete($strsql);
    }

    public function fncDelDataMst($BUSYOCD, $KI)
    {
        $strsql = $this->fncDeleteYosanMst($BUSYOCD, $KI);
        return parent::Do_Execute($strsql);
    }

    public function fncUpdDataMst($BUSYOCD, $KI, $KKRBUSYO, $INPUTDATA, $LINENO)
    {
        $strsql = $this->fncInsertYosanMst($BUSYOCD, $KI, $KKRBUSYO, $INPUTDATA, $LINENO);
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
        //--20150929  Yuanjh UPD S.
        //if ($objValue === null)
        if ($objValue == null)
        //--20150929  Yuanjh UPD E.
        {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "''";
            }
        }
        //--20150929  Yuanjh ADD S.
        elseif ($objValue == "*") {
            return "'*'";
        }
        //--20150929  Yuanjh ADD E.
        else {
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