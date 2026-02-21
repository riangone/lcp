<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE191CatalogOrderFinish extends ClsComDb
{
    //店舗名を取得する
    public function fncOrderSql($OrderNO)
    {
        $strSQL = "";
        $strSQL .= "SELECT TO_CHAR(DT.ORDER_DATE,'YYYY/MM/DD') ORDER_DATE" . "\r\n";
        $strSQL .= ",      DT.BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ",      TO_CHAR(SUM(DT.ORDER_NUM * BASE.TANKA),'9,999,999,999') ORDER_SUM" . "\r\n";
        $strSQL .= "FROM   HDTCATALOGDATA DT" . "\r\n";
        $strSQL .= "INNER JOIN HDTCATALOGBASE BASE" . "\r\n";
        $strSQL .= "ON     DT.CATALOG_CD = BASE.CATALOG_CD" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS " . "\r\n";
        $strSQL .= "ON    BUS.BUSYO_CD = DT.BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE  BASE.SET_DATE = (SELECT MAX(SET_DATE) " . "\r\n";
        $strSQL .= "                        FROM   HDTCATALOGBASE " . "\r\n";
        $strSQL .= "                        WHERE    SET_DATE <= TO_CHAR(DT.ORDER_DATE,'YYYYMMDD')) " . "\r\n";
        $strSQL .= "AND    DT.ORDER_NO = '@ORDERNO'" . "\r\n";
        $strSQL .= "GROUP BY TO_CHAR(DT.ORDER_DATE,'YYYY/MM/DD')" . "\r\n";
        $strSQL .= ",        DT.BUSYO_CD" . "\r\n";
        $strSQL .= ",        BUS.BUSYO_RYKNM" . "\r\n";
        $strSQL = str_replace("@ORDERNO", $OrderNO, $strSQL);

        return parent::select($strSQL);
    }

}
