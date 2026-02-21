<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE230PresentOrderCareer extends ClsComDb
{
    //店舗コード、店舗名を抽出する
    public function getSQLPL()
    {
        $strSQL = "";
        $strSQL .= " SELECT MIN(START_DATE) IVENTMIN     " . "\r\n";
        $strSQL .= " ,      MAX(START_DATE) IVENTMAX     " . "\r\n";
        $strSQL .= " ,      ADD_MONTHS(SYSDATE,1) TD     " . "\r\n";
        $strSQL .= " FROM   HDTPRESENTBASE               " . "\r\n";

        return parent::select($strSQL);
    }

    //画面初期化表示設定:店舗名を表示する
    public function getSQLTenpo($BusyoCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD,      MST.BUSYO_RYKNM " . "\r\n";
        $strSQL .= "	FROM HBUSYO MST	 " . "\r\n";
        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD                                      " . "\r\n";
        $strSQL .= "             ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL           " . "\r\n";
        $strSQL .= "                     THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO " . "\r\n";
        $strSQL .= "             FROM   HBUSYO) BUS                                   " . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO                                 " . "\r\n";
        $strSQL .= "WHERE  MST.STD_TENPO_DISP_NO IS NOT NULL " . "\r\n";
        $strSQL .= "AND    BUS.BUSYO_CD = '@BUSYOCD'" . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $BusyoCD ?? '', $strSQL);
        return parent::select($strSQL);
    }

    //履歴データ取得
    public function getSQLGrid($BusyoCD, $strB, $strE)
    {
        $strSQL = "";
        $strSQL .= " SELECT TO_CHAR(TO_DATE(IVDT.START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD')" . "\r\n";
        $strSQL .= " || '～' || TO_CHAR(TO_DATE(IVDT.END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') HIDUKE " . "\r\n";
        $strSQL .= " ,      IVDT.IVENT_NM  " . "\r\n";
        $strSQL .= " ,      BASE.HINMEI    " . "\r\n";
        $strSQL .= " ,      BASE.TANKA     " . "\r\n";
        $strSQL .= " ,      DATA.ORDER_NUM " . "\r\n";
        $strSQL .= " ,      (NVL(DATA.ORDER_NUM,0) * BASE.TANKA) KINGAKU   " . "\r\n";
        $strSQL .= " FROM   HDTIVENTDATA IVDT  " . "\r\n";
        $strSQL .= " INNER JOIN HDTPRESENTBASE BASE   " . "\r\n";
        $strSQL .= " ON     BASE.START_DATE = IVDT.START_DATE " . "\r\n";
        $strSQL .= " INNER JOIN HDTPRESENTORDER DATA " . "\r\n";
        $strSQL .= " ON     BASE.START_DATE = DATA.START_DATE " . "\r\n";
        $strSQL .= " AND    DATA.ORDER_NO = BASE.ORDER_NO " . "\r\n";
        $strSQL .= " AND    DATA.BUSYO_CD = '@BUSYOCD' " . "\r\n";
        $strSQL .= " WHERE  BASE.START_DATE >= '@STARTDT' " . "\r\n";
        $strSQL .= " AND    BASE.START_DATE <= '@ENDDT' " . "\r\n";
        $strSQL .= " ORDER BY IVDT.START_DATE " . "\r\n";
        $strSQL .= " ,        BASE.ORDER_NO " . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $BusyoCD ?? '', $strSQL);
        $strSQL = str_replace("@STARTDT", $strB, $strSQL);
        $strSQL = str_replace("@ENDDT", $strE, $strSQL);
        return parent::select($strSQL);
    }

}
