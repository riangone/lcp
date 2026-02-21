<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmSinsyaZaikoTorikomi extends ClsComDb
{
    const cstrTableName = "HSHINSYAZAIKO";
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

    //********************************************************************
    //処理概要：INSER文を返す
    //引　　数：なし
    //戻 り 値：String            INSERT文
    //********************************************************************
    public function fncGetSqlInsert($post)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "SinsyaZaikoTorikomi";
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];
        //INSERT文
        $strSQL = "";
        $strSQL .= "INSERT INTO @TABLE_NAME (";
        $strSQL .= "  KAMOK_CD";
        $strSQL .= ", KOMOK_CD";
        $strSQL .= ", KOMOK_NM";
        $strSQL .= ", NENGAPPI";
        $strSQL .= ", HISSU_TEKYO2";
        $strSQL .= ", HISSU_TEKYO3";
        $strSQL .= ", UPD_DATE";
        $strSQL .= ", CREATE_DATE";
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        $strSQL .= ") VALUES (";
        $strSQL .= "  '@KAMOK_CD'";
        $strSQL .= ", '@KOMOK_CD'";
        $strSQL .= ", '@KOMOK_NM'";
        $strSQL .= ", '@NENGAPPI'";
        $strSQL .= ", '@HISSU_TEKYO2'";
        $strSQL .= ", '@HISSU_TEKYO3'";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", '@UPDUSER'" . "\r\n";
        $strSQL .= ", '@UPDAPP'" . "\r\n";
        $strSQL .= ", '@UPDCLT'" . "\r\n";
        $strSQL .= ")";

        $strSQL = str_replace("@TABLE_NAME", self::cstrTableName, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);
        $strSQL = str_replace("@KAMOK_CD", $post['KAMOK_CD'], $strSQL);
        $strSQL = str_replace("@KOMOK_CD", $post['KOMOK_CD'], $strSQL);
        $strSQL = str_replace("@KOMOK_NM", $post['KOMOK_NM'], $strSQL);
        $strSQL = str_replace("@NENGAPPI", $post['NENGAPPI'], $strSQL);
        $strSQL = str_replace("@HISSU_TEKYO2", $post['HISSU_TEKYO2'], $strSQL);
        $strSQL = str_replace("@HISSU_TEKYO3", $post['HISSU_TEKYO3'], $strSQL);
        return $strSQL;
    }

    public function fncInsert($post)
    {
        return parent::Do_Execute($this->fncGetSqlInsert($post));
    }

    public function fncGetSqlDelete()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM @TABLE_NAME";
        //ﾃｰﾌﾞﾙ名を設定
        $strSQL = str_replace("@TABLE_NAME", self::cstrTableName, $strSQL);
        return $strSQL;
    }

    //********************************************************************
    //処理概要：取込ﾃｰﾌﾞﾙの削除処理
    //引　　数：なし
    //戻 り 値：配列
    //********************************************************************
    public function fncDelete()
    {
        return parent::Do_Execute($this->fncGetSqlDelete());
    }

}