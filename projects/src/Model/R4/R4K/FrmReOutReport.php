<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmReOutReport extends ClsComDb
{
    public function selectsql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";

        return $strSQL;
    }
    public function reselect()
    {
        return parent::select($this->selectsql());
    }

    public function fncSearchSaiseiSyukkoSQL($cboDateFrom, $cboDateTo)
    {
        $strSQL = "";
        $strSQL .= "SELECT	SUBSTR(INP_DATE,1,4) || '/' || SUBSTR(INP_DATE,5,2) || '/' || SUBSTR(INP_DATE,7,2) INP_DATE" . "\r\n";
        //入力年月
        $strSQL .= ",	    SUM(NVL(BUHIN_DAI,0)" . "\r\n";
        $strSQL .= "             + NVL(GAICHU_DAI,0)" . "\r\n";
        $strSQL .= "             + NVL(KOUCHIN_DAI,0)) GOUKEI" . "\r\n";
        //合計
        $strSQL .= "FROM    HSAISEISYUKKO" . "\r\n";
        $strSQL .= "WHERE   INP_DATE BETWEEN '@INPSTART' AND '@INPEND'" . "\r\n";
        $strSQL .= "GROUP BY INP_DATE" . "\r\n";
        $strSQL .= "ORDER BY INP_DATE" . "\r\n";

        //パラメータに値をセットする
        $strSQL = str_replace("@INPSTART", str_replace("/", "", $cboDateFrom), $strSQL);
        $strSQL = str_replace("@INPEND", str_replace("/", "", $cboDateTo), $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：再生出庫データ抽出
    //関 数 名：fncSearchSaiseiSyukko
    //引    数：
    //戻 り 値：
    //処理説明：再生出庫データ抽出する
    //**********************************************************************
    public function fncSearchSaiseiSyukko($cboDateFrom, $cboDateTo)
    {
        return parent::select($this->fncSearchSaiseiSyukkoSQL($cboDateFrom, $cboDateTo));
    }

    public function fncDeleteSaiseiSyukkoSQL($INP_DATE)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HSAISEISYUKKO" . "\r\n";
        $strSQL .= "WHERE  INP_DATE = '@INPDATE'" . "\r\n";
        $strSQL = str_replace("@INPDATE", str_replace("/", "", $INP_DATE), $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：再生出庫データ削除
    //関 数 名：fncDeleteSaiseiSyukko
    //引    数：
    //戻 り 値：
    //処理説明：再生出庫データ削除する
    //**********************************************************************
    public function fncDeleteSaiseiSyukko($INP_DATE)
    {
        return parent::delete($this->fncDeleteSaiseiSyukkoSQL($INP_DATE));
    }

}