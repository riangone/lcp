<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：FrmExcelTorikomiKyufu
// * 関数名	：FrmExcelTorikomiKyufu
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmExcelTorikomiKyufu extends ClsComDb
{

    // * 処理名	：fncSelectMeisaiExist
    // * 関数名	：fncSelectMeisaiExist
    // * 処理説明	：SELECT文を返す（同一年月データ存在チェック）
    public function fncSelectMeisaiExist($vYYYYMM)
    {
        $strSql = $this->fncSelectMeisaiExistSQL($vYYYYMM);
        return parent::select($strSql);
    }

    // * 処理名	：fncSelectMeisaiExistSQL
    // * 関数名	：fncSelectMeisaiExistSQL
    // * 処理説明	：SELECT文を返す（同一年月データ存在チェック）
    public function fncSelectMeisaiExistSQL($vYYYYMM)
    {
        $strSQL = "";
        $strSQL = " SELECT  " . "\r\n";
        $strSQL .= "  * " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "  JKTAISYOKUKYUFU " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "  TAISYOU_YM = '@YYYYMM' " . "\r\n";
        $strSQL = str_replace('@YYYYMM', $vYYYYMM, $strSQL);
        return $strSQL;
    }

    // * 処理名	：fncDeleteMeisaiExist
    // * 関数名	：fncDeleteMeisaiExist
    // * 処理説明	：DELETE文を返す（同一年月データ存在チェック）
    public function fncDeleteMeisaiExist($vYYYYMM)
    {
        $strSql = $this->fncDeleteMeisaiExistSQL($vYYYYMM);
        return parent::delete($strSql);
    }

    // * 処理名	：fncDeleteMeisaiExistSQL
    // * 関数名	：fncDeleteMeisaiExistSQL
    // * 処理説明	：DELETE文を返す（同一年月データ存在チェック）SQL
    public function fncDeleteMeisaiExistSQL($vYYYYMM)
    {
        $strSQL = "";
        $strSQL = " DELETE  " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "  JKTAISYOKUKYUFU " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "  TAISYOU_YM = '@YYYYMM'" . "\r\n";
        $strSQL = str_replace('@YYYYMM', $vYYYYMM, $strSQL);
        return $strSQL;
    }

    // * 処理名	：InsertData
    // * 関数名	：InsertData
    // * 処理説明	：DB新規追加
    public function InsertData($vYYYYMM, $vAry)
    {
        $strSql = $this->InsertDataSQL($vYYYYMM, $vAry);
        return parent::insert($strSql);
    }

    //指定された文字でパディング
    //<param name="target">対象となる文字列</param>
    //<param name="padChar">パディングする文字列</param>
    public function fncPadding($target, $padChar, $length)
    {
        $wkStr = $target;
        for ($i = 0; $i < $length; $i++) {
            $wkStr = $padChar . $wkStr;
        }
        $wkStr = substr($wkStr, strlen($wkStr) - $length, $length);
        return $wkStr;
    }

    // * 処理名	：InsertDataSQL
    // * 関数名	：InsertDataSQL
    // * 処理説明	：DB新規追加SQL
    public function InsertDataSQL($vYYYYMM, $vAry)
    {
        // ini_set('precision', 14);
        $strSQL = "";
        $strSQL = "Insert into JKTAISYOKUKYUFU " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " TAISYOU_YM " . "\r\n";
        $strSQL .= " ,SYAIN_NO " . "\r\n";
        $strSQL .= " ,KINGAKU " . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "Values( " . "\r\n";
        $strSQL .= "  @vYYYYMM " . "\r\n";
        $strSQL .= " ,'@VALUE01' " . "\r\n";
        $strSQL .= " ,'@VALUE02' " . "\r\n";
        $strSQL .= ") ";
        $strSQL = str_replace("@vYYYYMM", $vYYYYMM, $strSQL);
        $strSQL = str_replace("@VALUE01", $this->fncPadding($vAry[0], "0", 5), $strSQL);
        $strSQL = str_replace("@VALUE02", $vAry[1], $strSQL);
        return $strSQL;
    }

}
