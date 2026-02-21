<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE380MLOGINEntry extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //権限のコンボリストのデータソースを取得するSQL
    function ddlSearchSQL($SYSKB)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT PTN.PATTERN_ID ";
        $strSql = $strSql . " ,      PTN.PATTERN_NM ";
        $strSql = $strSql . " FROM   HPATTERNMST PTN ";
        $strSql = $strSql . " WHERE  PTN.SYS_KB = '@SYSKB' ";
        $strSql = $strSql . " AND    PTN.STYLE_ID = '001' ";
        $strSql = $strSql . " ORDER BY PTN.PATTERN_ID DESC";
        $strSql = str_replace("@SYSKB", $SYSKB, $strSql);

        return $strSql;
    }

    //ﾛｸﾞｲﾝ情報データを取得するSQL
    function userSearchSQL($SYSKB, $USERID)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT LOG.USER_ID ";
        $strSql = $strSql . " ,      LOG.PASSWORD ";
        $strSql = $strSql . " ,      LOG.PATTERN_ID ";
        $strSql = $strSql . " ,       to_char(LOG.REC_CRE_DT,'yyyy/mm/dd hh24:mi:ss') as REC_CRE_DT ";
        $strSql = $strSql . " FROM   M_LOGIN LOG ";
        $strSql = $strSql . " WHERE  LOG.USER_ID = '@USERID' ";
        $strSql = $strSql . " AND    LOG.SYS_KB = '@SYSKB' ";
        $strSql = str_replace("@USERID", $USERID, $strSql);
        $strSql = str_replace("@SYSKB", $SYSKB, $strSql);

        return $strSql;
    }

    //ﾛｸﾞｲﾝ情報の削除を行うSQL
    function mLoginDeleteSql($SYSKB, $USERID)
    {
        $strSql = "";
        $strSql = $strSql . " DELETE FROM M_LOGIN ";
        $strSql = $strSql . " WHERE  SYS_KB = '@SYSKB' ";
        $strSql = $strSql . " AND    USER_ID = '@USERID' ";
        $strSql = str_replace("@USERID", $USERID, $strSql);
        $strSql = str_replace("@SYSKB", $SYSKB, $strSql);

        return $strSql;
    }

    //ﾛｸﾞｲﾝ情報の登録を行うSQL
    function mLoginInsertSql($SYSKB, $USERID, $PASSWORD, $PATTERN_ID, $REC_UPD_DT = null)
    {
        $strSql = "";
        $strSql = $strSql . " INSERT INTO M_LOGIN " . "\r\n";
        $strSql = $strSql . " (SYS_KB,              " . "\r\n";
        $strSql = $strSql . "  USER_ID,                " . "\r\n";
        $strSql = $strSql . "  PASSWORD,              " . "\r\n";
        $strSql = $strSql . "  STYLE_ID,                " . "\r\n";
        $strSql = $strSql . "  PATTERN_ID,                " . "\r\n";
        $strSql = $strSql . "  REC_UPD_DT,             " . "\r\n";
        $strSql = $strSql . "  REC_CRE_DT,              " . "\r\n";
        $strSql = $strSql . "  UPD_SYA_CD,              " . "\r\n";
        $strSql = $strSql . "  UPD_PRG_ID,              " . "\r\n";
        $strSql = $strSql . "  UPD_CLT_NM )             " . "\r\n";
        $strSql = $strSql . "  VALUES(                  " . "\r\n";
        $strSql = $strSql . "  '@SYS_KB',           " . "\r\n";
        $strSql = $strSql . "  '@USER_ID',             " . "\r\n";
        $strSql = $strSql . "  '@PASSWORD',           " . "\r\n";
        $strSql = $strSql . "  '@STYLE_ID',             " . "\r\n";
        $strSql = $strSql . "  '@PATTERN_ID',             " . "\r\n";
        $strSql = $strSql . "  @REC_UPD_DT,          " . "\r\n";
        $strSql = $strSql . "  @REC_CRE_DT,           " . "\r\n";
        $strSql = $strSql . "  '@UPD_SYA_CD',           " . "\r\n";
        $strSql = $strSql . "  '@UPD_PRG_ID',           " . "\r\n";
        $strSql = $strSql . "  '@UPD_CLT_NM' )          " . "\r\n";

        $strSql = str_replace("@SYS_KB", $SYSKB, $strSql);
        $strSql = str_replace("@USER_ID", $USERID, $strSql);
        $strSql = str_replace("@PASSWORD", $PASSWORD, $strSql);
        $strSql = str_replace("@STYLE_ID", "001", $strSql);
        $strSql = str_replace("@PATTERN_ID", $PATTERN_ID, $strSql);
        if ($REC_UPD_DT === null || $REC_UPD_DT == "") {
            $strSql = str_replace("@REC_UPD_DT", "SYSDATE", $strSql);
        } else {
            $strSql = str_replace("@REC_UPD_DT", "to_date('" . $REC_UPD_DT . "','yyyy-mm-dd hh24:mi:ss')", $strSql);
        }
        $strSql = str_replace("@REC_CRE_DT", "SYSDATE", $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@UPD_PRG_ID", "MLOGINEntry", $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSql);

        return $strSql;
    }

    //ﾛｸﾞｲﾝ情報の登録を行う
    public function mLoginInsert($SYSKB, $USERID, $PASSWORD, $PATTERN_ID, $REC_UPD_DT = null)
    {
        return parent::insert($this->mLoginInsertSql($SYSKB, $USERID, $PASSWORD, $PATTERN_ID, $REC_UPD_DT));
    }

    //ﾛｸﾞｲﾝ情報の削除を行う
    public function mLoginDelete($SYSKB, $USERID)
    {
        return parent::delete($this->mLoginDeleteSql($SYSKB, $USERID));
    }

    //権限のコンボリストのデータソースを取得する
    public function ddlSearch($SYSKB)
    {
        return parent::select($this->ddlSearchSQL($SYSKB));
    }

    //ﾛｸﾞｲﾝ情報データを取得する
    public function userSearch($SYSKB, $USERID)
    {
        return parent::select($this->userSearchSQL($SYSKB, $USERID));
    }

}
