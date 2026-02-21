<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmReOutReportEdit extends ClsComDb
{
    public $ClsComFnc = '';
    function __construct()
    {
        parent::__construct();
        $this->ClsComFnc = new ClsComFnc();
    }

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

    public function fncSaiseiSyukkoSetSQL($strInpDate)
    {
        $strSQL = "";
        $strSQL .= "SELECT INP_DATE" . "\r\n";
        $strSQL .= ",      KBN" . "\r\n";
        $strSQL .= ",      NENSIKI" . "\r\n";
        $strSQL .= ",      SYADAIKATA" . "\r\n";
        $strSQL .= ",      CAR_NO" . "\r\n";
        $strSQL .= ",      BUHIN_DAI" . "\r\n";
        $strSQL .= ",      GAICHU_DAI" . "\r\n";
        $strSQL .= ",      KOUCHIN_DAI" . "\r\n";
        $strSQL .= ",      (NVL(BUHIN_DAI,0)" . "\r\n";
        $strSQL .= "       + NVL(GAICHU_DAI,0)" . "\r\n";
        $strSQL .= "       + NVL(KOUCHIN_DAI,0)) GOUKEI" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= "FROM   HSAISEISYUKKO" . "\r\n";
        $strSQL .= "WHERE  INP_DATE = '@INP_DATE'" . "\r\n";
        $strSQL .= "ORDER BY EDA_NO" . "\r\n";
        $strSQL = str_replace("@INP_DATE", str_replace("/", "", $strInpDate), $strSQL);
        return $strSQL;
    }

    public function fncSaiseiSyukkoSet($strInpDate)
    {
        return parent::select($this->fncSaiseiSyukkoSetSQL($strInpDate));
    }

    public function fncExistsCheckSQL($strInpDate)
    {
        $strSQL = "";
        $strSQL .= "SELECT INP_DATE" . "\r\n";
        $strSQL .= "FROM   HSAISEISYUKKO" . "\r\n";
        $strSQL .= "WHERE  INP_DATE = '@INPDATE'" . "\r\n";
        $strSQL = str_replace("@INPDATE", $strInpDate, $strSQL);
        return $strSQL;
    }

    public function fncExistsCheck($strInpDate)
    {
        return parent::select($this->fncExistsCheckSQL($strInpDate));
    }

    public function fncDeleteSaiseiSyukkoSQL($strInpDate)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HSAISEISYUKKO" . "\r\n";
        $strSQL .= "WHERE  INP_DATE = '@INPDATE'" . "\r\n";
        $strSQL = str_replace("@INPDATE", $strInpDate, $strSQL);
        return $strSQL;
    }

    public function fncDeleteSaiseiSyukko($strInpDate)
    {
        return parent::Do_Execute($this->fncDeleteSaiseiSyukkoSQL($strInpDate));
    }

    public function fncInsertHSaiseiSyukkoSQL($strInpDate, $intRow, $GridData)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "ReOutReportEdit";
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strSQL = "";
        $strSQL .= "INSERT INTO HSAISEISYUKKO" . "\r\n";
        $strSQL .= "(      INP_DATE" . "\r\n";
        $strSQL .= ",      EDA_NO" . "\r\n";
        $strSQL .= ",      KBN" . "\r\n";
        $strSQL .= ",      NENSIKI" . "\r\n";
        $strSQL .= ",      SYADAIKATA" . "\r\n";
        $strSQL .= ",      CAR_NO" . "\r\n";
        $strSQL .= ",      BUHIN_DAI" . "\r\n";
        $strSQL .= ",      GAICHU_DAI" . "\r\n";
        $strSQL .= ",      KOUCHIN_DAI" . "\r\n";
        $strSQL .= ",      UPD_DATE" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES" . "\r\n";
        $strSQL .= "(      '@INP_DATE'" . "\r\n";
        $strSQL .= ",      @EDANO" . "\r\n";
        $strSQL .= ",      '@KBN'" . "\r\n";
        $strSQL .= ",      '@NENSIKI'" . "\r\n";
        $strSQL .= ",      '@SYADAIKATA'" . "\r\n";
        $strSQL .= ",      '@CAR_NO'" . "\r\n";
        $strSQL .= ",      @BUHIN_DAI" . "\r\n";
        $strSQL .= ",      @GAICHU_DAI" . "\r\n";
        $strSQL .= ",      @KOUCHIN_DAI" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      TO_DATE(@CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@INP_DATE", $strInpDate, $strSQL);
        $strSQL = str_replace("@EDANO", $intRow + 1, $strSQL);
        $strSQL = str_replace("@KBN", strtoupper((string) $this->ClsComFnc->FncNv($GridData['KBN'])), $strSQL);
        $strSQL = str_replace("@NENSIKI", $this->ClsComFnc->FncNv($GridData['NENSIKI']), $strSQL);
        $strSQL = str_replace("@SYADAIKATA", $this->ClsComFnc->FncNv($GridData['SYADAIKATA']), $strSQL);
        $strSQL = str_replace("@CAR_NO", $this->ClsComFnc->FncNv($GridData['CAR_NO']), $strSQL);
        $strSQL = str_replace("@BUHIN_DAI", $this->fncSqlNull($GridData['BUHIN_DAI'], "NULL"), $strSQL);
        $strSQL = str_replace("@GAICHU_DAI", $this->fncSqlNull($GridData['GAICHU_DAI'], "NULL"), $strSQL);
        $strSQL = str_replace("@KOUCHIN_DAI", $this->fncSqlNull($GridData['KOUCHIN_DAI'], "NULL"), $strSQL);
        $strSQL = str_replace("@CREATE_DATE", rtrim($this->ClsComFnc->FncNv($GridData['CREATE_DATE'])) == "" ? "SYSDATE" : $this->ClsComFnc->FncSqlNv($GridData['CREATE_DATE']), $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);
        return $strSQL;
    }

    public function fncInsertHSaiseiSyukko($strInpDate, $intRow, $GridData)
    {
        return parent::Do_Execute($this->fncInsertHSaiseiSyukkoSQL($strInpDate, $intRow, $GridData));
    }

    //**********************************************************************
    //処 理 名：fncSqlNull
    //関 数 名：fncSqlNull
    //引    数：$vstrWk     (I)文字列
    //　    　：$vstrRtnCD   (I)0：数値　1：文字列型
    //戻 り 値：String
    //処理説明：DB登録項目を編集する
    //**********************************************************************
    public function fncSqlNull($vstrWk, $vstrRtnCD = "")
    {
        if (trim($vstrWk) == "" || $vstrWk == "0") {
            return $vstrRtnCD;
        } else {
            return $vstrWk;
        }

    }

}