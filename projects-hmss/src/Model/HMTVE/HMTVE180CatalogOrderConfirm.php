<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
use App\Model\HMTVE\Component\ClsComFncHMTVE;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE180CatalogOrderConfirm extends ClsComDb
{
    public $ClsComFncHMTVE;
    //店舗名を取得する
    public function shopSQL($BUSYOCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD,      MST.BUSYO_RYKNM " . "\r\n";
        $strSQL .= "	FROM HBUSYO MST	 " . "\r\n";
        $strSQL .= "WHERE  MST.BUSYO_CD = '@BUSYOCD'" . "\r\n";
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);

        return parent::select($strSQL);
    }

    //本カタログﾃﾞｰﾀSQLを作成する
    public function SQL($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT BASE.HAKKO_YM " . "\r\n";
        $strSQL .= ",      BASE.CATALOG_CD	 " . "\r\n";
        $strSQL .= ",      BASE.CATALOG_NM " . "\r\n";
        $strSQL .= ",      BASE.TANKA " . "\r\n";
        $strSQL .= ",      WK.ORDER_NUM " . "\r\n";
        $strSQL .= ",      (BASE.TANKA * WK.ORDER_NUM) GOUKEI " . "\r\n";

        $strSQL .= "FROM   HDTCATALOGBASE BASE " . "\r\n";
        $strSQL .= "INNER JOIN WK_HDTCATALOGDATA WK " . "\r\n";
        $strSQL .= "ON     WK.CATALOG_CD = BASE.CATALOG_CD " . "\r\n";
        $strSQL .= "AND    WK.ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS') " . "\r\n";
        $strSQL .= "AND    WK.BUSYO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL .= "WHERE  BASE.CATALOG_KB = '1' " . "\r\n";
        $strSQL .= "AND    BASE.SET_DATE = (SELECT MAX(SET_DATE) " . "\r\n";
        $strSQL .= "                        FROM   HDTCATALOGBASE " . "\r\n";
        $strSQL .= "                        WHERE  CATALOG_KB = '1' " . "\r\n";
        $strSQL .= "                        AND    SET_DATE <= TO_CHAR(TO_DATE('@ORDERDT', 'YYYY/MM/DD HH24:MI:SS'),'YYYYMMDD')) " . "\r\n";
        $strSQL .= "ORDER BY BASE.CATALOG_CD " . "\r\n";
        $strSQL = str_replace("@ORDERDT", $lblOrderDayShow . " " . $lblOrderTimeShow, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $lblShopCD, $strSQL);

        return parent::select($strSQL);
    }

    //用品カタログﾃﾞｰﾀSQLを作成する
    public function SQL1($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT BASE.HAKKO_YM " . "\r\n";
        $strSQL .= ",      BASE.CATALOG_CD	 " . "\r\n";
        $strSQL .= ",      BASE.CATALOG_NM " . "\r\n";
        $strSQL .= ",      BASE.TANKA " . "\r\n";
        $strSQL .= ",      WK.ORDER_NUM " . "\r\n";
        $strSQL .= ",      (BASE.TANKA * WK.ORDER_NUM) GOUKEI " . "\r\n";

        $strSQL .= "FROM   HDTCATALOGBASE BASE " . "\r\n";
        $strSQL .= "INNER JOIN WK_HDTCATALOGDATA WK " . "\r\n";
        $strSQL .= "ON     WK.CATALOG_CD = BASE.CATALOG_CD " . "\r\n";
        $strSQL .= "AND    WK.ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS') " . "\r\n";
        $strSQL .= "AND    WK.BUSYO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL .= "WHERE  BASE.CATALOG_KB = '2' " . "\r\n";
        $strSQL .= "AND    BASE.SET_DATE = (SELECT MAX(SET_DATE) " . "\r\n";
        $strSQL .= "                        FROM   HDTCATALOGBASE " . "\r\n";
        $strSQL .= "                        WHERE  CATALOG_KB = '2'	 " . "\r\n";
        $strSQL .= "                        AND    SET_DATE <= TO_CHAR(TO_DATE('@ORDERDT', 'YYYY/MM/DD HH24:MI:SS'),'YYYYMMDD')) " . "\r\n";
        $strSQL .= "ORDER BY BASE.CATALOG_CD " . "\r\n";
        $strSQL = str_replace("@ORDERDT", $lblOrderDayShow . " " . $lblOrderTimeShow, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $lblShopCD, $strSQL);

        return parent::select($strSQL);
    }

    //用品ﾃﾞｰﾀSQLを作成する
    public function SQL2($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT BASE.CATALOG_CD " . "\r\n";
        $strSQL .= ",      BASE.CATALOG_NM	 " . "\r\n";
        $strSQL .= ",      BASE.TANKA " . "\r\n";
        $strSQL .= ",      WK.ORDER_NUM " . "\r\n";
        $strSQL .= ",      ( BASE.TANKA * WK.ORDER_NUM) GOUKEI " . "\r\n";
        $strSQL .= "FROM   HDTCATALOGBASE BASE " . "\r\n";
        $strSQL .= "INNER JOIN WK_HDTCATALOGDATA WK	 " . "\r\n";
        $strSQL .= "ON     WK.CATALOG_CD = BASE.CATALOG_CD " . "\r\n";
        $strSQL .= "AND    WK.ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS') " . "\r\n";
        $strSQL .= "AND    WK.BUSYO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL .= "WHERE  BASE.CATALOG_KB = '3' " . "\r\n";
        $strSQL .= "AND    BASE.SET_DATE = (SELECT MAX(SET_DATE) " . "\r\n";
        $strSQL .= "                        FROM   HDTCATALOGBASE " . "\r\n";
        $strSQL .= "                        WHERE  CATALOG_KB = '3'	 " . "\r\n";
        $strSQL .= "                        AND    SET_DATE <= TO_CHAR(TO_DATE('@ORDERDT', 'YYYY/MM/DD HH24:MI:SS'),'YYYYMMDD'))	 " . "\r\n";
        $strSQL .= "ORDER BY BASE.CATALOG_CD " . "\r\n";
        $strSQL = str_replace("@ORDERDT", $lblOrderDayShow . " " . $lblOrderTimeShow, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $lblShopCD, $strSQL);

        return parent::select($strSQL);
    }

    //カタログ配送希望ﾃﾞｰﾀSQLを作成する
    public function SQL3($lblOrderDayShow, $lblOrderTimeShow, $lblShopCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT * " . "\r\n";
        $strSQL .= "FROM   WK_HDTCATALOGHAISOUKIBOU " . "\r\n";
        $strSQL .= "WHERE  ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS') " . "\r\n";
        $strSQL .= "AND    BUSYO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL = str_replace("@ORDERDT", $lblOrderDayShow . " " . $lblOrderTimeShow, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $lblShopCD, $strSQL);

        return parent::select($strSQL);
    }

    //店舗名を抽出する
    public function ORDER_DATE_GET($BUSYOCD, $ORDERDT)
    {
        $strSQL = "";
        $strSQL .= "SELECT CT.ORDER_DATE " . "\r\n";
        $strSQL .= "	FROM   HDTCATALOGDATA CT	 " . "\r\n";
        $strSQL .= "WHERE  TO_CHAR(CT.ORDER_DATE,'YYYY/MM/DD') = '@ORDERDT' " . "\r\n";
        $strSQL .= "AND    CT.BUSYO_CD = '@BUSYOCD'" . "\r\n";
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        $strSQL = str_replace("@ORDERDT", $ORDERDT, $strSQL);

        return parent::select($strSQL);
    }

    //採番ﾃｰﾌﾞﾙから採番する
    public function fncUpdSaiban_SEL($strNengetu)
    {
        $strSQL = "";
        $strSQL .= "SELECT NVL(SEQNO,0) + 1 BANGO" . "\r\n";
        $strSQL .= "FROM   HDTSAIBAN " . "\r\n";
        $strSQL .= "WHERE  ID = '@PROID' AND NENGETU = '@NENGETU'" . "\r\n";
        $strSQL = str_replace("@NENGETU", $strNengetu, $strSQL);
        $strSQL = str_replace("@PROID", "CatalogOrderEntry", $strSQL);

        return parent::select($strSQL);
    }

    //採番ﾃｰﾌﾞﾙに既に同一年月のものがあればUPDATE、なければINSERT
    public function fncUpdSaiban_UPD($HasRows, $BANGO, $sysDate, $strNengetu)
    {
        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = "";
        if ($HasRows == true) {
            $strSQL .= "UPDATE HDTSAIBAN" . "\r\n";
            $strSQL .= "   SET SEQNO = @BANGO" . "\r\n";
            $strSQL .= "   ,   UPD_DATE = @sysDate " . "\r\n";
            $strSQL .= "   ,   UPD_SYA_CD = '@strUpdSya'" . "\r\n";
            $strSQL .= "   ,   UPD_CLT_NM = '@strUpdClt'" . "\r\n";
            $strSQL .= "   ,   UPD_PRG_ID = '@strProID'" . "\r\n";
            $strSQL .= " WHERE ID = '@strProID'" . "\r\n";
            $strSQL .= "   AND NENGETU = '@strNengetu'" . "\r\n";

            $strSQL = str_replace("@BANGO", $BANGO, $strSQL);
            $strSQL = str_replace("@sysDate", $this->ClsComFncHMTVE->FncSqlDate($sysDate), $strSQL);
            $strSQL = str_replace("@strProID", "CatalogOrderEntry", $strSQL);
            $strSQL = str_replace("@strUpdSya", $this->GS_LOGINUSER['strUserID'], $strSQL);
            $strSQL = str_replace("@strUpdClt", $this->GS_LOGINUSER['strClientNM'], $strSQL);
            $strSQL = str_replace("@strNengetu", $strNengetu, $strSQL);

            return parent::update($strSQL);
        } else {
            $strSQL .= "INSERT INTO HDTSAIBAN" . "\r\n";
            $strSQL .= "(      ID" . "\r\n";
            $strSQL .= ",      NENGETU" . "\r\n";
            $strSQL .= ",      SEQNO" . "\r\n";
            $strSQL .= ",      UPD_SYA_CD" . "\r\n";
            $strSQL .= ",      UPD_CLT_NM" . "\r\n";
            $strSQL .= ",      UPD_PRG_ID" . "\r\n";
            $strSQL .= ",      UPD_DATE" . "\r\n";
            $strSQL .= ",      CREATE_DATE)" . "\r\n";
            $strSQL .= " VALUES (" . "\r\n";
            $strSQL .= "'@strProID'" . "\r\n";
            $strSQL .= ", '@strNengetu'" . "\r\n";
            $strSQL .= ", '1'" . "\r\n";
            $strSQL .= ", '@strUpdSya '" . "\r\n";
            $strSQL .= ", '@strUpdClt'" . "\r\n";
            $strSQL .= ", '@strProID'" . "\r\n";
            $strSQL .= ", @sysDate" . "\r\n";
            $strSQL .= ", @sysDate" . "\r\n";
            $strSQL .= " ) " . "\r\n";

            $strSQL = str_replace("@sysDate", $this->ClsComFncHMTVE->FncSqlDate($sysDate), $strSQL);
            $strSQL = str_replace("@strProID", "CatalogOrderEntry", $strSQL);
            $strSQL = str_replace("@strUpdSya", $this->GS_LOGINUSER['strUserID'], $strSQL);
            $strSQL = str_replace("@strUpdClt", $this->GS_LOGINUSER['strClientNM'], $strSQL);
            $strSQL = str_replace("@strNengetu", $strNengetu, $strSQL);

            return parent::insert($strSQL);
        }

    }

    //カタログ注文データに登録する
    public function insertSQL($strOrderNo, $OrderDate, $OrderTime, $lblShopCD)
    {
        $strSQL = "";
        $strSQL .= "	INSERT INTO HDTCATALOGDATA	 " . "\r\n";
        $strSQL .= " (      ORDER_NO" . "\r\n";
        $strSQL .= "	,      ORDER_DATE		 " . "\r\n";
        $strSQL .= "	,      BUSYO_CD	 " . "\r\n";
        $strSQL .= "	,      CATALOG_CD	 " . "\r\n";
        $strSQL .= "	,      ORDER_NUM	 " . "\r\n";
        $strSQL .= "	,      UPD_DATE	 " . "\r\n";
        $strSQL .= "	,      CREATE_DATE	 " . "\r\n";
        $strSQL .= "	,      UPD_SYA_CD	 " . "\r\n";
        $strSQL .= "	,      UPD_PRG_ID	 " . "\r\n";
        $strSQL .= "	,      UPD_CLT_NM	 " . "\r\n";
        $strSQL .= "	 )			 " . "\r\n";
        $strSQL .= "	SELECT '@ORDERNO'	 " . "\r\n";
        $strSQL .= "	,      ORDER_DATE	 " . "\r\n";
        $strSQL .= "	,      BUSYO_CD	 " . "\r\n";
        $strSQL .= "	,      CATALOG_CD	 " . "\r\n";
        $strSQL .= "	,      ORDER_NUM	 " . "\r\n";
        $strSQL .= "	,      DECODE(CREATE_DATE,NULL,SYSDATE,CREATE_DATE)	 " . "\r\n";
        $strSQL .= "	,      DECODE(UPD_DATE,NULL,SYSDATE,UPD_DATE)	 " . "\r\n";
        $strSQL .= "	,      UPD_SYA_CD	 " . "\r\n";
        $strSQL .= "	,      UPD_PRG_ID " . "\r\n";
        $strSQL .= "	,      UPD_CLT_NM	" . "\r\n";
        $strSQL .= "	FROM   WK_HDTCATALOGDATA	 " . "\r\n";
        $strSQL .= "	WHERE  ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS')		 " . "\r\n";
        $strSQL .= "	AND    BUSYO_CD = '@BUSYOCD'	 " . "\r\n";

        $strSQL = str_replace("@ORDERNO", $strOrderNo, $strSQL);
        $strSQL = str_replace("@ORDERDT", $OrderDate . " " . $OrderTime, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $lblShopCD, $strSQL);

        return parent::insert($strSQL);
    }

    //カタログ明細データSQLを作成する
    public function MailSQL($strOrderNO, $lblOrderDayShow, $lblOrderTimeShow, $lblShopCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT BASE.HAKKO_YM " . "\r\n";
        $strSQL .= ",      BASE.CATALOG_CD	 " . "\r\n";
        $strSQL .= ",      BASE.CATALOG_NM " . "\r\n";
        $strSQL .= ",      BASE.TANKA " . "\r\n";
        $strSQL .= ",      WK.ORDER_NUM " . "\r\n";
        $strSQL .= ",      (BASE.TANKA * WK.ORDER_NUM) GOUKEI " . "\r\n";

        $strSQL .= ",      CASE WHEN CH.ORDER_NO IS NULL THEN '1' ELSE '0' END AS HAISOUKIBOU " . "\r\n";

        $strSQL .= "FROM   HDTCATALOGBASE BASE " . "\r\n";
        $strSQL .= "INNER JOIN HDTCATALOGDATA WK " . "\r\n";
        $strSQL .= "ON WK.CATALOG_CD = BASE.CATALOG_CD " . "\r\n";
        $strSQL .= "AND WK.ORDER_NO = '@ORDERNO'" . "\r\n";

        $strSQL .= "LEFT JOIN HDTCATALOGHAISOUKIBOU CH " . "\r\n";
        $strSQL .= "ON CH.ORDER_NO = WK.ORDER_NO " . "\r\n";

        $strSQL .= " WHERE    BASE.SET_DATE = (SELECT MAX(SET_DATE) " . "\r\n";
        $strSQL .= "                        FROM   HDTCATALOGBASE " . "\r\n";
        $strSQL .= "                        WHERE    SET_DATE <= TO_CHAR(TO_DATE('@ORDERDT', 'YYYY/MM/DD HH24:MI:SS'),'YYYYMMDD')) " . "\r\n";
        $strSQL .= " ORDER BY BASE.CATALOG_CD " . "\r\n";

        $strSQL = str_replace("@ORDERNO", $strOrderNO, $strSQL);
        $strSQL = str_replace("@ORDERDT", $lblOrderDayShow . " " . $lblOrderTimeShow, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $lblShopCD, $strSQL);

        return parent::select($strSQL);
    }

    //ワークテーブルを削除する
    public function DEL1_SQL($OrderDate, $OrderTime, $lblShopCD)
    {
        $strSQL = "";
        $strSQL .= "     DELETE FROM WK_HDTCATALOGDATA    " . "\r\n";
        $strSQL .= "     WHERE  ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS')  " . "\r\n";
        $strSQL .= " AND    BUSYO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL = str_replace("@ORDERDT", $OrderDate . " " . $OrderTime, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $lblShopCD, $strSQL);

        return parent::delete($strSQL);
    }

    //配送希望ワークテーブルを削除する
    public function DEL2_SQL($OrderDate, $OrderTime, $lblShopCD)
    {
        $strSQL = "";
        $strSQL .= "     DELETE FROM WK_HDTCATALOGHAISOUKIBOU    " . "\r\n";
        $strSQL .= "     WHERE  ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS')  " . "\r\n";
        $strSQL .= " AND    BUSYO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL = str_replace("@ORDERDT", $OrderDate . " " . $OrderTime, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $lblShopCD, $strSQL);

        return parent::delete($strSQL);
    }

    //メールアドレスを取得する
    public function MAIL_ADDRESS_SEL()
    {
        $strSQL = " SELECT MAIL_ADDRESS FROM HDTMAILINFO ORDER BY SEQ_NO ";
        return parent::select($strSQL);
    }

}

