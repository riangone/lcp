<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;
use App\Model\Component\ClsComDb;
use Cake\Routing\Router;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE190CatalogOrderCareer extends ClsComDb
{
    public $SessionComponent;
    // '**********************************************************************
    // '処 理 名：対象期間のＳＱＬ文を取得
    // '関 数 名：getTermSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：対象期間のＳＱＬ文を取得
    // '**********************************************************************
    public function getTermSQL()
    {
        $strSQL = "   ";
        $strSQL .= "SELECT TO_CHAR(MIN(ORDER_DATE), 'YYYY/MM/DD hh24:mi:ss') IVENTMIN " . "\r\n";
        $strSQL .= "  ,TO_CHAR(MAX(ORDER_DATE), 'YYYY/MM/DD hh24:mi:ss') IVENTMAX  " . "\r\n";
        $strSQL .= "  ,TO_CHAR(ADD_MONTHS(SYSDATE,1), 'YYYY/MM/DD hh24:mi:ss') TD   " . "\r\n";
        $strSQL .= "   FROM HDTCATALOGDATA   " . "\r\n";
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：店舗名のＳＱＬ文を取得
    // '関 数 名：getShopSQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：店舗名のＳＱＬ文を取得
    // '**********************************************************************
    public function getShopSQL()
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "   ";
        $strSQL .= "   Select  MST.BUSYO_CD  " . "\r\n";
        $strSQL .= "  ,      MST.BUSYO_RYKNM  " . "\r\n";
        $strSQL .= "  FROM HBUSYO MST  " . "\r\n";
        $strSQL .= "  INNER JOIN HBUSYO BUS  " . "\r\n";
        $strSQL .= "  ON     MST.BUSYO_CD = BUS.TENPO_CD  " . "\r\n";
        $strSQL .= "  WHERE  BUS.BUSYO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：本カタログﾃﾞｰﾀSQL
    // '関 数 名：getThisDirectorySQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：本カタログﾃﾞｰﾀSQLを取得
    // '**********************************************************************
    public function getThisDirectorySQL($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= " SELECT TO_CHAR(WK.ORDER_DATE, 'YYYY/MM/DD hh24:mi:ss') ORDER_DATE" . "\r\n";
        $strSQL .= " ,      ORDER_NO" . "\r\n";
        $strSQL .= " ,      BASE.CATALOG_CD" . "\r\n";
        $strSQL .= " ,      (CASE WHEN BASE.CATALOG_KB = '1' THEN '本カタログ'" . "\r\n";
        $strSQL .= "              WHEN BASE.CATALOG_KB = '2' THEN '用品カタログ'" . "\r\n";
        $strSQL .= "              WHEN BASE.CATALOG_KB = '3' THEN '用品'" . "\r\n";
        $strSQL .= "         END) CATALOG_KB_NM" . "\r\n";
        $strSQL .= " ,      BASE.CATALOG_NM" . "\r\n";
        $strSQL .= " ,      BASE.TANKA" . "\r\n";
        $strSQL .= " ,      NVL(WK.ORDER_NUM,0) ORDER_NUM " . "\r\n";
        $strSQL .= " ,      (NVL(BASE.TANKA,0) * NVL(WK.ORDER_NUM,0)) KINGAKU" . "\r\n";
        $strSQL .= " FROM HDTCATALOGBASE BASE" . "\r\n";
        $strSQL .= " INNER JOIN HDTCATALOGDATA WK" . "\r\n";
        $strSQL .= " ON WK.CATALOG_CD = BASE.CATALOG_CD" . "\r\n";
        $strSQL .= " AND WK.BUSYO_CD = '@BUSYOCD'" . "\r\n";
        $strSQL .= " WHERE  BASE.SET_DATE = (SELECT MAX(SET_DATE)" . "\r\n";
        $strSQL .= "						 FROM HDTCATALOGBASE  " . "\r\n";
        $strSQL .= "						 WHERE  CATALOG_CD = WK.CATALOG_CD " . "\r\n";
        $strSQL .= "                      AND    SET_DATE <= TO_CHAR(WK.ORDER_DATE,'YYYYMMDD')) " . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= " AND      TO_CHAR(WK.ORDER_DATE,'YYYYMMDD') >= '@STARTDT'	" . "\r\n";
        $strSQL .= " AND      TO_CHAR(WK.ORDER_DATE,'YYYYMMDD') <= '@ENDDT' " . "\r\n";
        $strSQL .= " ORDER BY WK.ORDER_DATE DESC" . "\r\n";
        $strSQL .= " ,        ORDER_NO" . "\r\n";
        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@STARTDT", $postData['ddlYearStart'] . $postData['ddlMonthStart'] . $postData['ddlDayStart'], $strSQL);
        $strSQL = str_replace("@ENDDT", $postData['ddlYearEnd'] . $postData['ddlMonthEnd'] . $postData['ddlDayEnd'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：用品カタログﾃﾞｰﾀSQL
    // '関 数 名：getCommodityDySQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：用品カタログﾃﾞｰﾀSQLを取得
    // '**********************************************************************
    public function getCommodityDySQL($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= " Select V.HAKKO_YM " . "\r\n";
        $strSQL .= " ,      V.CATALOG_CD	 " . "\r\n";
        $strSQL .= " ,      V.CATALOG_NM	" . "\r\n";
        $strSQL .= " ,      V.TANKA	" . "\r\n";
        $strSQL .= " ,      SUM(V.NUM) NUM	" . "\r\n";
        $strSQL .= " ,      SUM(V.GOUKEI) GK	" . "\r\n";
        $strSQL .= " FROM   (	" . "\r\n";
        $strSQL .= "         Select BASE.HAKKO_YM " . "\r\n";
        $strSQL .= " 		,      BASE.CATALOG_CD	" . "\r\n";
        $strSQL .= " 		,      BASE.CATALOG_NM	" . "\r\n";
        $strSQL .= " 		,      BASE.TANKA		" . "\r\n";
        $strSQL .= " , WK.ORDER_DATE" . "\r\n";
        $strSQL .= " 		,      NVL(WK.ORDER_NUM,0) NUM	" . "\r\n";
        $strSQL .= " 		,      (NVL(BASE.TANKA,0) * NVL(WK.ORDER_NUM,0))　GOUKEI" . "\r\n";
        $strSQL .= " 		FROM   HDTCATALOGBASE BASE	" . "\r\n";
        $strSQL .= " 		INNER JOIN HDTCATALOGDATA WK" . "\r\n";
        $strSQL .= " 		ON     WK.CATALOG_CD = BASE.CATALOG_CD	" . "\r\n";
        $strSQL .= " 		AND    WK.BUSYO_CD = '@BUSYOCD'		" . "\r\n";
        $strSQL .= " 		WHERE  BASE.CATALOG_KB = '2'		" . "\r\n";
        $strSQL .= " 		AND    BASE.SET_DATE = (SELECT MAX(SET_DATE)	" . "\r\n";
        $strSQL .= "        FROM(HDTCATALOGBASE)" . "\r\n";
        $strSQL .= " 		                        WHERE  CATALOG_KB = '2'	" . "\r\n";
        $strSQL .= " 		　　　　　　　　　　　　AND    CATALOG_CD = WK.CATALOG_CD	 " . "\r\n";
        $strSQL .= " 		                        AND    SET_DATE <= TO_CHAR(WK.ORDER_DATE,'YYYYMMDD'))	" . "\r\n";
        $strSQL .= " 		) V	" . "\r\n";
        $strSQL .= " WHERE    TO_CHAR(V.ORDER_DATE,'YYYYMMDD') >= '@STARTDT'	" . "\r\n";
        $strSQL .= " AND      TO_CHAR(V.ORDER_DATE,'YYYYMMDD') <= '@ENDDT'	 " . "\r\n";
        $strSQL .= " GROUP BY V.HAKKO_YM		" . "\r\n";
        $strSQL .= " ,        V.CATALOG_CD		 " . "\r\n";
        $strSQL .= " ,        V.CATALOG_NM		 " . "\r\n";
        $strSQL .= " ,        V.TANKA			 " . "\r\n";
        $strSQL .= " ORDER BY V.CATALOG_CD	 " . "\r\n";
        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@STARTDT", $postData['ddlYearStart'] . $postData['ddlMonthStart'] . $postData['ddlDayStart'], $strSQL);
        $strSQL = str_replace("@ENDDT", $postData['ddlYearEnd'] . $postData['ddlMonthEnd'] . $postData['ddlDayEnd'], $strSQL);
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：用品ﾃﾞｰﾀSQL
    // '関 数 名：getCommoditySQL
    // '引 数   ：なし
    // '戻 り 値：strSQL　　　String
    // '処理説明：用品ﾃﾞｰﾀSQLを取得
    // '**********************************************************************
    public function getExDelSQL($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= " SELECT V.CATALOG_CD" . "\r\n";
        $strSQL .= " 	,      V.CATALOG_NM" . "\r\n";
        $strSQL .= " 	,      V.TANKA		" . "\r\n";
        $strSQL .= " 	,      SUM(V.NUM) NUM	" . "\r\n";
        $strSQL .= " 	,      SUM(V.GOUKEI) GK	" . "\r\n";
        $strSQL .= " 	FROM   (				" . "\r\n";
        $strSQL .= "        Select BASE.HAKKO_YM " . "\r\n";
        $strSQL .= " 			,      BASE.CATALOG_CD	" . "\r\n";
        $strSQL .= " 			,      BASE.CATALOG_NM	" . "\r\n";
        $strSQL .= " 			,      BASE.TANKA		" . "\r\n";
        $strSQL .= " , WK.ORDER_DATE" . "\r\n";
        $strSQL .= " 			,      NVL(WK.ORDER_NUM,0) NUM	" . "\r\n";
        $strSQL .= " 			,      (NVL(BASE.TANKA,0) * NVL(WK.ORDER_NUM,0))　GOUKEI" . "\r\n";
        $strSQL .= " 			FROM   HDTCATALOGBASE BASE	" . "\r\n";
        $strSQL .= " 			INNER JOIN HDTCATALOGDATA WK	" . "\r\n";
        $strSQL .= " 			ON     WK.CATALOG_CD = BASE.CATALOG_CD	" . "\r\n";
        $strSQL .= " 			AND    WK.BUSYO_CD = '@BUSYOCD'	" . "\r\n";
        $strSQL .= " 			WHERE  BASE.CATALOG_KB = '3'" . "\r\n";
        $strSQL .= " 			AND    BASE.SET_DATE = (SELECT MAX(SET_DATE)" . "\r\n";
        $strSQL .= "        FROM HDTCATALOGBASE " . "\r\n";
        $strSQL .= " 			                        WHERE  CATALOG_KB = '3'	" . "\r\n";
        $strSQL .= " 			　　　　　　　　　　　　AND    CATALOG_CD = WK.CATALOG_CD	" . "\r\n";
        $strSQL .= " 			                        AND    SET_DATE <= TO_CHAR(WK.ORDER_DATE,'YYYYMMDD'))	" . "\r\n";
        $strSQL .= " 			) V	" . "\r\n";
        $strSQL .= " 	WHERE    TO_CHAR(V.ORDER_DATE,'YYYYMMDD') >= '@STARTDT'	" . "\r\n";
        $strSQL .= " 	AND      TO_CHAR(V.ORDER_DATE,'YYYYMMDD') <= '@ENDDT'" . "\r\n";
        $strSQL .= " 	GROUP BY V.HAKKO_YM	" . "\r\n";
        $strSQL .= " 	,        V.CATALOG_CD	" . "\r\n";
        $strSQL .= " 	,        V.CATALOG_NM	" . "\r\n";
        $strSQL .= " 	,        V.TANKA		" . "\r\n";
        $strSQL .= " 	ORDER BY V.CATALOG_CD	" . "\r\n";
        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@STARTDT", $postData['ddlYearStart'] . $postData['ddlMonthStart'] . $postData['ddlDayStart'], $strSQL);
        $strSQL = str_replace("@ENDDT", $postData['ddlYearEnd'] . $postData['ddlMonthEnd'] . $postData['ddlDayEnd'], $strSQL);
        return $strSQL;
    }

    public function getTerm()
    {
        $strSql = $this->getTermSQL();

        return parent::select($strSql);
    }

    public function getShop()
    {
        $strSql = $this->getShopSQL();

        return parent::select($strSql);
    }

    public function getThisDirectory($postData)
    {
        $strSql = $this->getThisDirectorySQL($postData);
        return parent::select($strSql);
    }

    public function getCommodityDy($postData)
    {
        $strSql = $this->getCommodityDySQL($postData);

        return parent::select($strSql);
    }

    // public function getCommodity($postData)
    // {
    //     $strSql = $this->getCommoditySQL($postData);

    //     return parent::select($strSql);
    // }

}
