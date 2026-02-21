<?php
namespace App\Model\R4\R4K;


/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20161028           #2596						   BUG                              YIN  　　
 * --------------------------------------------------------------------------------------------
 */
use App\Model\Component\ClsComDb;

class FrmShikakariTorikomi extends ClsComDb
{
    public $cstrTableName = "HSHIKAKARI";
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

    public function fncTableDeleteSQL($strNengetu, $strHaseiMoto)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM @TABLE_NAME" . "\r\n";
        $strSQL .= "WHERE  NENGETU = '@NENGETU'" . "\r\n";
        $strSQL .= "AND    HASEI_MOTO_KB = '@HASEIMOTO'" . "\r\n";
        //ﾃｰﾌﾞﾙ名を設定
        $strSQL = str_replace("@TABLE_NAME", $this->cstrTableName, $strSQL);
        $strSQL = str_replace("@NENGETU", $strNengetu, $strSQL);
        $strSQL = str_replace("@HASEIMOTO", $strHaseiMoto, $strSQL);
        return $strSQL;
    }

    //********************************************************************
    //処理概要：取込ﾃｰﾌﾞﾙの削除処理
    //引　　数：1.処理年月  2.$strHaseiMoto(0/1)
    //戻 り 値：配列
    //********************************************************************
    public function fncTableDelete($strNengetu, $strHaseiMoto)
    {
        return parent::Do_Execute($this->fncTableDeleteSQL($strNengetu, $strHaseiMoto));
    }

    public function fncSeqNOSetSQL($strNengetu, $strSyadaikata, $strCarNO, $strHaseimotKb)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(SEQ_NO) + 1" . "\r\n";
        $strSQL .= "FROM   @TABLE_NAME" . "\r\n";
        $strSQL .= "WHERE  NENGETU = '@NENGETU'" . "\r\n";
        $strSQL .= "AND    SYADAIKATA = '@SYADAIKATA'" . "\r\n";
        $strSQL .= "AND    CAR_NO = '@CARNO'" . "\r\n";
        $strSQL .= "AND    HASEI_MOTO_KB = '@HASEIMOTO'" . "\r\n";
        $strSQL .= "GROUP BY NENGETU" . "\r\n";
        $strSQL .= ",        SYADAIKATA" . "\r\n";
        $strSQL .= ",        CAR_NO" . "\r\n";
        $strSQL .= ",        HASEI_MOTO_KB" . "\r\n";
        $strSQL = str_replace("@TABLE_NAME", $this->cstrTableName, $strSQL);
        $strSQL = str_replace("@NENGETU", $strNengetu, $strSQL);
        $strSQL = str_replace("@SYADAIKATA", $strSyadaikata, $strSQL);
        $strSQL = str_replace("@CARNO", $strCarNO, $strSQL);
        $strSQL = str_replace("@HASEIMOTO", $strHaseimotKb, $strSQL);
        return $strSQL;
    }

    public function fncSeqNOSet($strNengetu, $strSyadaikata, $strCarNO, $strHaseimotKb)
    {
        return parent::Fill($this->fncSeqNOSetSQL($strNengetu, $strSyadaikata, $strCarNO, $strHaseimotKb));
    }

    public function fncGetSqlInsertSQL($postData)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "ShikakariTorikomi";
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        //INSERT文
        $strSQL .= "INSERT INTO @TABLE_NAME (";
        $strSQL .= "  NENGETU";
        $strSQL .= ", SYADAIKATA";
        $strSQL .= ", CAR_NO";
        $strSQL .= ", HASEI_MOTO_KB";
        $strSQL .= ", SEQ_NO";
        $strSQL .= ", BUHIN_MEI";
        $strSQL .= ", KINGAKU";
        $strSQL .= ", SYORI_DT";
        $strSQL .= ", UPD_DATE";
        $strSQL .= ", CREATE_DATE";
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        $strSQL .= ") VALUES (";
        $strSQL .= "  '@NENGETU'";
        $strSQL .= ", '@SYADAIKATA'";
        $strSQL .= ", '@CAR_NO'";
        $strSQL .= ", '@HASEI_MOTO_KB'";
        $strSQL .= ", @SEQ_NO";
        $strSQL .= ", '@BUHIN_MEI'";
        $strSQL .= ", @KINGAKU";
        $strSQL .= ", '@SYORIDT'";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", SYSDATE";
        $strSQL .= ",           '@UPDUSER'" . "\r\n";
        $strSQL .= ",           '@UPDAPP'" . "\r\n";
        $strSQL .= ",           '@UPDCLT'" . "\r\n";
        $strSQL .= ")";

        $strSQL = str_replace("@TABLE_NAME", $this->cstrTableName, $strSQL);
        $strSQL = str_replace("@NENGETU", $postData['cboYM'], $strSQL);
        $strSQL = str_replace("@SYADAIKATA", $postData['SYADAIKATA'], $strSQL);
        $strSQL = str_replace("@CAR_NO", $postData['CAR_NO'], $strSQL);
        $strSQL = str_replace("@SYORIDT", $postData['SYORIDT'], $strSQL);
        $strSQL = str_replace("@HASEI_MOTO_KB", $postData['HASEI_MOTO_KB'], $strSQL);
        $strSQL = str_replace("@SEQ_NO", $postData['SEQ_NO'], $strSQL);
        //20161028 YIN UPD S
        //$strSQL = str_replace("@BUHIN_MEI", $postData['BUHIN_MEI'], $strSQL);
        $strSQL = str_replace("@BUHIN_MEI", str_replace("'", "''", $postData['BUHIN_MEI']), $strSQL);
        //20161028 YIN UPD E
        $strSQL = str_replace("@KINGAKU", $postData['KINGAKU'], $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);

        return $strSQL;
    }

    public function fncGetSqlInsert($postData)
    {
        return parent::Do_Execute($this->fncGetSqlInsertSQL($postData));
    }

}