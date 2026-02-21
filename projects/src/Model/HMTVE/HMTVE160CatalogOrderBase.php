<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE160CatalogOrderBase extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //本ｶﾀﾛｸﾞテープルのＳＱＬ文の取得
    function getHonCatalogSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT BASE.HAKKO_YM                             " . "\r\n";
        $strSQL .= " ,      BASE.CATALOG_CD                           " . "\r\n";
        $strSQL .= " ,      BASE.CATALOG_NM                           " . "\r\n";
        $strSQL .= " ,      BASE.TANKA                                " . "\r\n";
        $strSQL .= " FROM   HDTCATALOGBASE BASE                       " . "\r\n";
        $strSQL .= " WHERE  BASE.CATALOG_KB = '1'                     " . "\r\n";
        $strSQL .= " AND    BASE.SET_DATE = (SELECT MAX(SET_DATE)     " . "\r\n";
        $strSQL .= "                        FROM   HDTCATALOGBASE     " . "\r\n";
        $strSQL .= "                        WHERE  CATALOG_KB = '1')  " . "\r\n";
        $strSQL .= " ORDER BY BASE.CATALOG_CD                         " . "\r\n";

        return $strSQL;
    }

    //用品ｶﾀﾛｸﾞテープルのＳＱＬ文の取得
    function getYouCatalogSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT BASE.HAKKO_YM                             " . "\r\n";
        $strSQL .= " ,      BASE.CATALOG_CD                           " . "\r\n";
        $strSQL .= " ,      BASE.CATALOG_NM                           " . "\r\n";
        $strSQL .= " ,      BASE.TANKA                                " . "\r\n";
        $strSQL .= " FROM   HDTCATALOGBASE BASE                       " . "\r\n";
        $strSQL .= " WHERE  BASE.CATALOG_KB = '2'                     " . "\r\n";
        $strSQL .= " AND    BASE.SET_DATE = (SELECT MAX(SET_DATE)     " . "\r\n";
        $strSQL .= "                        FROM   HDTCATALOGBASE     " . "\r\n";
        $strSQL .= "                        WHERE  CATALOG_KB = '2')  " . "\r\n";
        $strSQL .= " ORDER BY BASE.CATALOG_CD                         " . "\r\n";

        return $strSQL;
    }

    //用品ｶﾀﾛｸﾞテープルのＳＱＬ文の取得
    function getCatalogSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT BASE.CATALOG_CD                           " . "\r\n";
        $strSQL .= " ,      BASE.CATALOG_NM                           " . "\r\n";
        $strSQL .= " ,      BASE.TANKA                                " . "\r\n";
        $strSQL .= " FROM   HDTCATALOGBASE BASE                       " . "\r\n";
        $strSQL .= " WHERE  BASE.CATALOG_KB = '3'                     " . "\r\n";
        $strSQL .= " AND    BASE.SET_DATE = (SELECT MAX(SET_DATE)     " . "\r\n";
        $strSQL .= "                        FROM   HDTCATALOGBASE     " . "\r\n";
        $strSQL .= "                        WHERE  CATALOG_KB = '3')  " . "\r\n";
        $strSQL .= " ORDER BY BASE.CATALOG_CD                         " . "\r\n";

        return $strSQL;
    }

    //メールアドレステープルのＳＱＬ文の取得
    function getMailSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT SEQ_NO " . "\r\n";
        $strSQL .= " ,      MAIL_ADDRESS " . "\r\n";
        $strSQL .= " FROM   HDTMAILINFO " . "\r\n";
        $strSQL .= " ORDER BY SEQ_NO " . "\r\n";

        return $strSQL;
    }

    //本ｶﾀﾛｸﾞ設定データを削除のＳＱＬ文の取得
    function getHonCatalogDelSQL($strDate)
    {
        $strSQL = "";
        $strSQL .= "  DELETE FROM HDTCATALOGBASE   " . "\r\n";
        $strSQL .= "  WHERE  SET_DATE = '@SETDATE' " . "\r\n";
        $strSQL = str_replace("@SETDATE", $strDate, $strSQL);
        return $strSQL;
    }

    //本ｶﾀﾛｸﾞ設定データに登録のＳＱＬ文の取得
    function getHonCatalogLoginSQL($strDate, $CNT)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTCATALOGBASE " . "\r\n";
        $strSQL .= " ( SET_DATE , CATALOG_CD , CATALOG_NM , HAKKO_YM , TANKA , CATALOG_KB ," . "\r\n";
        $strSQL .= "   UPD_DATE , CREATE_DATE , UPD_SYA_CD , UPD_PRG_ID , UPD_CLT_NM )" . "\r\n";
        $strSQL .= " VALUES" . "\r\n";
        $strSQL .= " ( '@SET_DATE' , '@CATALOG_CD' , '@CATALOG_NM' , '@HAKKO_YM' , '@TANKA' , '1' ," . "\r\n";
        $strSQL .= "   SYSDATE , SYSDATE , '@UPD_SYA_CD' , '@UPD_PRG_ID' , '@UPD_CLT_NM' )" . "\r\n";
        $strSQL = str_replace("@SET_DATE", $strDate, $strSQL);
        $strSQL = str_replace("@CATALOG_CD", $CNT['CATALOG_CD'], $strSQL);
        $strSQL = str_replace("@CATALOG_NM", $CNT['CATALOG_NM'], $strSQL);
        $strSQL = str_replace("@HAKKO_YM", str_replace("/", "", $CNT['HAKKO_YM']), $strSQL);
        $strSQL = str_replace("@TANKA", $CNT['TANKA'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "CatalogOrderBase", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return $strSQL;
    }

    //用品ｶﾀﾛｸﾞ設定データに登録のＳＱＬ文の取得
    function getYouCatalogLoginSQL($strDate, $CNT)
    {
        $strSQL = "";
        $strSQL .= "  INSERT INTO HDTCATALOGBASE   " . "\r\n";
        $strSQL .= "  ( SET_DATE , CATALOG_CD , CATALOG_NM , HAKKO_YM , TANKA , CATALOG_KB ,                     " . "\r\n";
        $strSQL .= "   UPD_DATE , CREATE_DATE , UPD_SYA_CD , UPD_PRG_ID , UPD_CLT_NM )                           " . "\r\n";
        $strSQL .= "   VALUES                                                                                    " . "\r\n";
        $strSQL .= "  ( '@SET_DATE' , '@CATALOG_CD' , '@CATALOG_NM' , '@HAKKO_YM' , '@TANKA' , '2' ,  " . "\r\n";
        $strSQL .= "     SYSDATE , SYSDATE , '@UPD_SYA_CD' , '@UPD_PRG_ID' , '@UPD_CLT_NM' )          " . "\r\n";
        $strSQL = str_replace("@SET_DATE", $strDate, $strSQL);
        $strSQL = str_replace("@CATALOG_CD", $CNT['CATALOG_CD'], $strSQL);
        $strSQL = str_replace("@CATALOG_NM", $CNT['CATALOG_NM'], $strSQL);
        $strSQL = str_replace("@HAKKO_YM", str_replace("/", "", $CNT['HAKKO_YM']), $strSQL);
        $strSQL = str_replace("@TANKA", $CNT['TANKA'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "CatalogOrderBase", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return $strSQL;
    }

    //用品設定ﾃﾞｰﾀに登録のＳＱＬ文の取得
    function getCatalogLoginSQL($strDate, $CNT)
    {
        $strSQL = "";

        $strSQL .= "  INSERT INTO HDTCATALOGBASE   " . "\r\n";
        $strSQL .= "  ( SET_DATE , CATALOG_CD , CATALOG_NM , HAKKO_YM , TANKA , CATALOG_KB ,                     " . "\r\n";
        $strSQL .= "   UPD_DATE , CREATE_DATE , UPD_SYA_CD , UPD_PRG_ID , UPD_CLT_NM )                           " . "\r\n";
        $strSQL .= "   VALUES                                                                                    " . "\r\n";
        $strSQL .= "  ( '@SET_DATE' , '@CATALOG_CD' , '@CATALOG_NM' , '@HAKKO_YM' , '@TANKA' , '3' ,  " . "\r\n";
        $strSQL .= "     SYSDATE , SYSDATE , '@UPD_SYA_CD' , '@UPD_PRG_ID' , '@UPD_CLT_NM' )          " . "\r\n";
        $strSQL = str_replace("@SET_DATE", $strDate, $strSQL);
        $strSQL = str_replace("@CATALOG_CD", $CNT['CATALOG_CD'], $strSQL);
        $strSQL = str_replace("@CATALOG_NM", $CNT['CATALOG_NM'], $strSQL);
        $strSQL = str_replace("@HAKKO_YM", "", $strSQL);
        $strSQL = str_replace("@TANKA", $CNT['TANKA'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "CatalogOrderBase", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return $strSQL;
    }

    //メールアドレス設定ﾃｰﾌﾞﾙに登録のＳＱＬ文の取得
    function getMailInsSQL($CNT)
    {

        $strSQL = "";
        $strSQL .= " INSERT INTO HDTMAILINFO " . "\r\n";
        $strSQL .= " ( SEQ_NO , MAIL_ADDRESS  ,  " . "\r\n";
        $strSQL .= "   UPD_DATE , CREATE_DATE , UPD_SYA_CD , UPD_PRG_ID , UPD_CLT_NM )        " . "\r\n";
        $strSQL .= " VALUES                                                                   " . "\r\n";
        $strSQL .= " ( '@SEQ_NO' , '@MAIL_ADDRESS' ,  										  " . "\r\n";
        $strSQL .= "   SYSDATE , SYSDATE , '@UPD_SYA_CD' , '@UPD_PRG_ID' , '@UPD_CLT_NM' )    " . "\r\n";

        $strSQL = str_replace("@SEQ_NO", $CNT['SEQ_NO'], $strSQL);
        $strSQL = str_replace("@MAIL_ADDRESS", $CNT['MAIL_ADDRESS'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "CatalogOrderBase", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return $strSQL;
    }

    //本ｶﾀﾛｸﾞテープルのＳＱＬ文の取得する
    public function getHonCatalog()
    {
        $strSql = $this->getHonCatalogSQL();

        return parent::select($strSql);
    }

    //用品ｶﾀﾛｸﾞテープルのＳＱＬ文の取得する
    public function getYouCatalog()
    {
        $strSql = $this->getYouCatalogSQL();

        return parent::select($strSql);
    }

    //用品ｶﾀﾛｸﾞテープルのＳＱＬ文の取得する
    public function getCatalog()
    {
        $strSql = $this->getCatalogSQL();

        return parent::select($strSql);
    }

    //メールアドレステープルのＳＱＬ文の取得する
    public function getMail()
    {
        $strSql = $this->getMailSQL();

        return parent::select($strSql);
    }

    //本ｶﾀﾛｸﾞ設定データを削除のＳＱＬ文の取得する
    public function getHonCatalogDel($strDate)
    {
        $strSql = $this->getHonCatalogDelSQL($strDate);

        return parent::delete($strSql);
    }

    //本ｶﾀﾛｸﾞ設定データに登録のＳＱＬ文の取得する
    public function getHonCatalogLogin($strStartDate, $CNT)
    {
        $strSql = $this->getHonCatalogLoginSQL($strStartDate, $CNT);

        return parent::insert($strSql);
    }

    //用品ｶﾀﾛｸﾞ設定データに登録のＳＱＬ文の取得する
    public function getYouCatalogLogin($strStartDate, $CNT)
    {
        $strSql = $this->getYouCatalogLoginSQL($strStartDate, $CNT);

        return parent::insert($strSql);
    }

    //用品設定ﾃﾞｰﾀに登録のＳＱＬ文の取得する
    public function getCatalogLogin($strStartDate, $CNT)
    {
        $strSql = $this->getCatalogLoginSQL($strStartDate, $CNT);

        return parent::insert($strSql);
    }

    //メールアドレス設定ﾃｰﾌﾞﾙに登録のＳＱＬ文の取得
    public function getMailIns($CNT)
    {
        $strSql = $this->getMailInsSQL($CNT);

        return parent::insert($strSql);
    }

    // メール設定データの削除
    function getMailInsDel()
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTMAILINFO " . "\r\n";

        return parent::delete($strSQL);
    }

}

