<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsGenriMake;

class FrmGENKAIMAKE extends ClsComDb
{
    public $UPDAPP = "GENKAIMAKE";
    // public $UPDUSER = "";
    // public $UPDCLTNM = "";
    public $ClsGenriMake = "";

    // public function __construct()
    // {
    // parent::__construct();
    // $this -> UPDUSER = $this -> GS_LOGINUSER['strUserID'];
    // $this -> UPDCLTNM = $this -> GS_LOGINUSER['strClientNM'];
    // }

    public function selectsql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= ",      KISYU_YMD KISYU" . "\r\n";
        $strSQL .= ",      KI" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";
        return $strSQL;
    }

    public function fncSelect()
    {
        return parent::select($this->selectsql());
    }

    public function fncHscUriExistCheck($ym)
    {
        $strSQL = "";
        $this->ClsGenriMake = new ClsGenriMake();
        $strSQL = $this->ClsGenriMake->fncHscUriExistCheck($ym);
        return parent::select($strSQL);
    }

    public function fncDeleteGenri($ym)
    {
        $strSQL = "";
        $this->ClsGenriMake = new ClsGenriMake();
        $strSQL = $this->ClsGenriMake->fncDeleteGenri($ym);
        return parent::Do_Execute($strSQL);
    }

    public function fncInsertNoExist($ym)
    {
        $strSQL = "";
        $this->ClsGenriMake = new ClsGenriMake();
        $strSQL = $this->ClsGenriMake->fncInsertNoExist($ym, $this->UPDAPP);
        return parent::Do_Execute($strSQL);
    }

    public function fncInsertUriageSagaku($ym)
    {
        $strSQL = "";
        $this->ClsGenriMake = new ClsGenriMake();
        $strSQL = $this->ClsGenriMake->fncInsertUriageSagaku($ym, $this->UPDAPP);
        return parent::Do_Execute($strSQL);
    }

    public function fncInsertAkaJyohen($ym)
    {
        $strSQL = "";
        $this->ClsGenriMake = new ClsGenriMake();
        $strSQL = $this->ClsGenriMake->fncInsertAkaJyohen($ym, $this->UPDAPP);
        return parent::Do_Execute($strSQL);
    }

    public function fncInsertForExist($ym)
    {
        $strSQL = "";
        $this->ClsGenriMake = new ClsGenriMake();
        $strSQL = $this->ClsGenriMake->fncInsertForExist($ym, $this->UPDAPP);
        return parent::Do_Execute($strSQL);
    }

}