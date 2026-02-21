<?php

/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;

class FrmArariSyukeiBaseH extends ClsComDb
{

    public function fncListSelect()
    {
        $strsql = $this->fncListSelectSQL();
        return parent::select($strsql);
    }

    public function fncSyaSelect()
    {
        $strsql = $this->fncSyaSelectSQL();
        return parent::select($strsql);
    }


    public function frmBasehCdSel()
    {
        $strsql = $this->frmBasehCdSelSQL();
        return parent::select($strsql);
    }

    public function frmUpDate($data, $UPDUSER, $UPDCLTNM, $UPDAPP)
    {
        $strsql = $this->frmUpDateSQL($data, $UPDUSER, $UPDCLTNM, $UPDAPP);
        return parent::update($strsql);
    }

    public function frmInsert($data, $UPDUSER, $UPDCLTNM, $UPDAPP)
    {
        $strsql = $this->frmInsertSQL($data, $UPDUSER, $UPDCLTNM, $UPDAPP);
        return parent::Do_Execute($strsql);
    }

    public function fncToriNmSelect($data)
    {
        $strsql = $this->fncToriNmSelectSQL($data);
        return parent::select($strsql);
    }

    // '**********************************************************************
    // '処 理 名：SpreadリストSQL
    // '関 数 名：fncListSelectSQL
    // '引    数：
    // '戻 り 値：ＳＱＬ文
    // '処理説明：基本情報を抽出する
    // '**********************************************************************
    function fncListSelectSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT MAM.BASEH_CD" . "\r\n";
        $strSQL .= ",MAM.BASEH_KN" . "\r\n";
        $strSQL .= ",HAR.SYASYU_CD" . "\r\n";
        $strSQL .= ",HSY.SS_NAME" . "\r\n";
        $strSQL .= ",TO_NUMBER(HAR.ARARI_RITU, '9.000') ARARI_RITU" . "\r\n";
        $strSQL .= ",TO_NUMBER(HAR.UNTIN_RITU, '9.000') UNTIN_RITU" . "\r\n";
        $strSQL .= ",HAR.DISP_NO" . "\r\n";
        $strSQL .= "FROM   M27AM1 MAM" . "\r\n";
        $strSQL .= "LEFT JOIN  HARARISYUKEIMST_BASEH HAR " . "\r\n";
        $strSQL .= "ON   HAR.BASEH_CD = MAM.BASEH_CD" . "\r\n";
        $strSQL .= "LEFT JOIN   HSYASYUMST HSY " . "\r\n";
        $strSQL .= "ON   HAR.SYASYU_CD   = HSY.UCOYA_CD  " . "\r\n";
        $strSQL .= "ORDER BY HAR.DISP_NO" . "\r\n";
        $strSQL .= ",MAM.BASEH_KN" . "\r\n";
        $strSQL .= ",MAM.BASEH_CD";
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：検索
    // '関 数 名：fncSyaSelectSQL
    // '引    数：
    // '戻 り 値：ＳＱＬ文
    // '処理説明：車種マスタを検索
    // '**********************************************************************
    function fncSyaSelectSQL()
    {

        $strSQL = "";
        $strSQL .= "SELECT HSY.UCOYA_CD " . "\r\n";
        $strSQL .= ", HSY.SS_NAME " . "\r\n";
        $strSQL .= "FROM  HSYASYUMST HSY" . "\r\n";
        return $strSQL;
    }
    // '**********************************************************************
    // '処 理 名：車種マスタを検索
    // '関 数 名：fncToriNmSelectSQL
    // '引    数：$data
    // '戻 り 値：ＳＱＬ文
    // '処理説明：車種マスタを検索
    // '**********************************************************************
    function fncToriNmSelectSQL($data)
    {
        $strSQL = "";
        $strSQL .= "SELECT HSY.SS_NAME" . "\r\n";
        $strSQL .= ",HAR.ARARI_RITU" . "\r\n";
        $strSQL .= ",HAR.UNTIN_RITU" . "\r\n";
        $strSQL .= ",DISP_NO" . "\r\n";
        $strSQL .= "FROM  HSYASYUMST HSY" . "\r\n";
        $strSQL .= "LEFT JOIN   HARARISYUKEIMST_BASEH HAR" . "\r\n";
        $strSQL .= "ON HSY.UCOYA_CD=HAR.SYASYU_CD" . "\r\n";
        $strSQL .= "and HAR.BASEH_CD = '" . $data['BASEH_CD'] . "'" . "\r\n";
        $strSQL .= "WHERE HSY.UCOYA_CD = '" . $data['SYASYU_CD'] . "'";
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：検索
    // '関 数 名：frmBasehCdSelSQL
    // '引    数：
    // '戻 り 値：ＳＱＬ文
    // '処理説明：車種マスタを検索
    // '**********************************************************************
    function frmBasehCdSelSQL()
    {

        $strSQL = "";
        $strSQL .= "SELECT HAR.BASEH_CD " . "\r\n";
        $strSQL .= "FROM  HARARISYUKEIMST_BASEH HAR";
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：更新処理
    // '関 数 名：frmUpDateSQL
    // '引    数：$data, $UPDUSER, $UPDCLTNM, $UPDAPP
    // '戻 り 値：ＳＱＬ文
    // '処理説明：更新処理
    // '**********************************************************************
    function frmUpDateSQL($data, $UPDUSER, $UPDCLTNM, $UPDAPP)
    {

        $strSQL = "";
        $strSQL .= "UPDATE HARARISYUKEIMST_BASEH " . "\r\n";
        $strSQL .= "SET " . "\r\n";
        $strSQL .= "SYASYU_CD = '" . $data['SYASYU_CD'] . "'" . "\r\n";
        $strSQL .= ",ARARI_RITU = " . $data['ARARI_RITU'] . "\r\n";
        $strSQL .= ",UNTIN_RITU = " . $data['UNTIN_RITU'] . "\r\n";
        $strSQL .= ",DISP_NO = " . $data['DISP_NO'] . "\r\n";
        $strSQL .= ",UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD = '" . $UPDUSER . "'" . "\r\n";
        $strSQL .= ",UPD_PRG_ID = '" . $UPDAPP . "'" . "\r\n";
        $strSQL .= ",UPD_CLT_NM = '" . $UPDCLTNM . "'" . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= " BASEH_CD = '" . $data['BASEH_CD'] . "'";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：更新処理
    // '関 数 名：frmUpDateSQL
    // '引    数：$data, $UPDUSER, $UPDCLTNM, $UPDAPP
    // '戻 り 値：ＳＱＬ文
    // '処理説明：更新処理
    // '**********************************************************************
    function frmInsertSQL($data, $UPDUSER, $UPDCLTNM, $UPDAPP)
    {

        $strSQL = "";
        $strSQL .= "INSERT INTO HARARISYUKEIMST_BASEH " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= "BASEH_CD " . "\r\n";
        $strSQL .= ",SYASYU_CD" . "\r\n";
        $strSQL .= ",ARARI_RITU" . "\r\n";
        $strSQL .= ",UNTIN_RITU" . "\r\n";
        $strSQL .= ",DISP_NO" . "\r\n";
        $strSQL .= ",UPD_DATE" . "\r\n";
        $strSQL .= ",CREATE_DATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD" . "\r\n";
        $strSQL .= ",UPD_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_CLT_NM" . "\r\n";
        $strSQL .= " ) " . "\r\n";
        $strSQL .= "VALUES" . "\r\n";
        $strSQL .= "(" . "\r\n";
        $strSQL .= "'" . $data['BASEH_CD'] . "'" . "\r\n";
        $strSQL .= ",'" . $data['SYASYU_CD'] . "'" . "\r\n";
        $strSQL .= "," . $data['ARARI_RITU'] . "\r\n";
        $strSQL .= "," . $data['UNTIN_RITU'] . "\r\n";
        $strSQL .= "," . $data['DISP_NO'] . "\r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",'" . $UPDUSER . "'" . "\r\n";
        $strSQL .= ",'" . $UPDAPP . "'" . "\r\n";
        $strSQL .= ",'" . $UPDCLTNM . "'" . "\r\n";
        $strSQL .= " ) ";

        return $strSQL;
    }
}
