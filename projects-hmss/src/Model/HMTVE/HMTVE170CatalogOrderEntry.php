<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE170CatalogOrderEntry extends ClsComDb
{
    public function loadRowDataOneSql($ORDERDT, $BUSYOCD, $jqgridNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT BASE.HAKKO_YM " . "\r\n";
        $strSQL .= ",      BASE.CATALOG_CD	 " . "\r\n";
        $strSQL .= ",      BASE.CATALOG_NM " . "\r\n";
        $strSQL .= ",      BASE.TANKA " . "\r\n";
        $strSQL .= ",      WK.ORDER_NUM " . "\r\n";

        $strSQL .= "FROM   HDTCATALOGBASE BASE " . "\r\n";
        $strSQL .= "LEFT JOIN WK_HDTCATALOGDATA WK " . "\r\n";
        $strSQL .= "ON     WK.CATALOG_CD = BASE.CATALOG_CD " . "\r\n";
        $strSQL .= "AND    WK.ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS') " . "\r\n";
        $strSQL .= "AND    WK.BUSYO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL .= "WHERE  BASE.CATALOG_KB = '@JQGRIDNO' " . "\r\n";
        $strSQL .= "AND    BASE.SET_DATE = (SELECT MAX(SET_DATE) " . "\r\n";
        $strSQL .= "                        FROM   HDTCATALOGBASE " . "\r\n";
        $strSQL .= "                        WHERE  CATALOG_KB = '@JQGRIDNO' " . "\r\n";
        $strSQL .= "                        AND    SET_DATE <= TO_CHAR(TO_DATE('@ORDERDT', 'YYYY/MM/DD HH24:MI:SS'),'YYYYMMDD')) " . "\r\n";
        $strSQL .= "ORDER BY BASE.CATALOG_CD " . "\r\n";

        $strSQL = str_replace("@ORDERDT", $ORDERDT, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        $strSQL = str_replace("@JQGRIDNO", $jqgridNo, $strSQL);
        return parent::select($strSQL);
    }

    public function FoucsMoveSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= "FROM HBUSYO MST" . "\r\n";
        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= " ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= " THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        return parent::select($strSQL);
    }

    public function DeleteSQL_HDTCATALOGDATA($ORDERDT, $BUSYOCD)
    {
        $strSQL = "";
        $strSQL .= "	DELETE FROM WK_HDTCATALOGDATA	 " . "\r\n";
        $strSQL .= "	WHERE  ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS')	 " . "\r\n";
        $strSQL .= " AND    BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@ORDERDT", $ORDERDT, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        return parent::delete($strSQL);
    }

    public function DeleteSQL_CatalogHaisouKibou($ORDERDT, $BUSYOCD)
    {
        $strSQL = "";
        $strSQL .= "	DELETE FROM WK_HDTCATALOGHAISOUKIBOU " . "\r\n";
        $strSQL .= "	WHERE  ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS')		 " . "\r\n";
        $strSQL .= "	AND    BUSYO_CD = '@BUSYOCD'	 " . "\r\n";

        $strSQL = str_replace("@ORDERDT", $ORDERDT, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        return parent::delete($strSQL);
    }

    public function insertSQL($ORDERDT, $BUSYOCD, $postdata, $ORDER_NUM)
    {
        $strSQL = "";
        $strSQL .= "	INSERT INTO WK_HDTCATALOGDATA	 " . "\r\n";
        $strSQL .= "	(      ORDER_DATE		 " . "\r\n";
        $strSQL .= "	,      BUSYO_CD	 " . "\r\n";
        $strSQL .= "	,      CATALOG_CD	 " . "\r\n";
        $strSQL .= "	,      ORDER_NUM	 " . "\r\n";
        $strSQL .= "	,      UPD_DATE	 " . "\r\n";
        $strSQL .= "	,      CREATE_DATE	 " . "\r\n";
        $strSQL .= "	,      UPD_SYA_CD	 " . "\r\n";
        $strSQL .= "	,      UPD_PRG_ID	 " . "\r\n";
        $strSQL .= "	,      UPD_CLT_NM	 " . "\r\n";
        $strSQL .= "	 )			 " . "\r\n";
        $strSQL .= "	VALUES (TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS')	 " . "\r\n";
        $strSQL .= "	,       '@BUSYOCD'	 " . "\r\n";
        $strSQL .= "	,       '@CATALOG_CD'	 " . "\r\n";
        $strSQL .= "	,       @ORDER_NUM	 " . "\r\n";
        $strSQL .= "	,       SYSDATE	 " . "\r\n";
        $strSQL .= "	,       SYSDATE	 " . "\r\n";
        $strSQL .= "	,       '@UPD_SYA_CD'	 " . "\r\n";
        $strSQL .= "	,       'CatalogOrderEntry'" . "\r\n";
        $strSQL .= "	,       '@UPD_CLT_NM'	 " . "\r\n";
        $strSQL .= "	)	 " . "\r\n";

        $strSQL = str_replace("@ORDERDT", $ORDERDT, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        $strSQL = str_replace("@ORDER_NUM", $ORDER_NUM, $strSQL);
        $strSQL = str_replace("@CATALOG_CD", $postdata['CATALOG_CD'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }

    public function insertSQLCatalogHaisouKibou($ORDERDT, $BUSYOCD)
    {
        $strSQL = "";
        $strSQL .= "	INSERT INTO WK_HDTCATALOGHAISOUKIBOU " . "\r\n";
        $strSQL .= " (      ORDER_DATE		 " . "\r\n";
        $strSQL .= "	,      BUSYO_CD	 " . "\r\n";
        $strSQL .= "	,      UPD_DATE	 " . "\r\n";
        $strSQL .= "	,      CREATE_DATE	 " . "\r\n";
        $strSQL .= "	,      UPD_SYA_CD	 " . "\r\n";
        $strSQL .= "	,      UPD_PRG_ID	 " . "\r\n";
        $strSQL .= "	,      UPD_CLT_NM	 " . "\r\n";
        $strSQL .= "	 )			 " . "\r\n";
        $strSQL .= "	SELECT ORDER_DATE	 " . "\r\n";
        $strSQL .= "	,      BUSYO_CD	 " . "\r\n";
        $strSQL .= "	,      SYSDATE	 " . "\r\n";
        $strSQL .= "	,      SYSDATE	 " . "\r\n";
        $strSQL .= "	,      UPD_SYA_CD	 " . "\r\n";
        $strSQL .= "	,      UPD_PRG_ID " . "\r\n";
        $strSQL .= "	,      UPD_CLT_NM	" . "\r\n";
        $strSQL .= "	FROM   WK_HDTCATALOGDATA	 " . "\r\n";
        $strSQL .= "	WHERE  ORDER_DATE = TO_DATE('@ORDERDT','YYYY/MM/DD HH24:MI:SS')		 " . "\r\n";
        $strSQL .= "	AND    BUSYO_CD = '@BUSYOCD'	 " . "\r\n";
        $strSQL .= "	AND    ROWNUM=1 " . "\r\n";

        $strSQL = str_replace("@ORDERDT", $ORDERDT, $strSQL);
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        return parent::insert($strSQL);
    }

}
