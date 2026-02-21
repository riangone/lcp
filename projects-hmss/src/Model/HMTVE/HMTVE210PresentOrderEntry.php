<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE210PresentOrderEntry extends ClsComDb
{
    //店舗名を取得する
    public function getBCD($BusyoCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD                                               " . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM                                            " . "\r\n";
        $strSQL .= "FROM HBUSYO MST                                                   " . "\r\n";
        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD                                      " . "\r\n";
        $strSQL .= "             ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL           " . "\r\n";
        $strSQL .= "                     THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO " . "\r\n";
        $strSQL .= "             FROM   HBUSYO) BUS                                   " . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO                                 " . "\r\n";
        $strSQL .= "WHERE  MST.STD_TENPO_DISP_NO IS NOT NULL                          " . "\r\n";
        $strSQL .= "AND    BUS.BUSYO_CD = '@BUSYOCD'      " . "\r\n";
        $strSQL = str_replace("@BUSYOCD", $BusyoCD, $strSQL);

        return parent::select($strSQL);
    }

    //展示会開催期間に初期値をセット
    public function setExhibitTermDateSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT  START_DATE " . "\r\n";
        $strSQL .= ",       END_DATE " . "\r\n";
        $strSQL .= "FROM    HDTIVENTDATA " . "\r\n";
        $strSQL .= "WHERE   BASE_FLG = '1' " . "\r\n";

        return parent::select($strSQL);
    }

    //登録可能年月かをチェックするデータのＳＱＬ文の取得
    //登録可能年月かをチェックのＳＱＬ文を取得する
    public function getFlagSql($STARTDT)
    {
        $strSQL = "";
        $strSQL .= " SELECT KAKU.KAKUTEI_FLG " . "\r\n";
        $strSQL .= " FROM   HDTPRESENTKAKUTEIDATA KAKU  " . "\r\n";
        $strSQL .= " WHERE  KAKU.START_DATE = '@STARTDT' " . "\r\n";
        $strSQL = str_replace("@STARTDT", $STARTDT, $strSQL);

        return parent::select($strSQL);
    }

    //成約プレゼント注文データ取得ＳＱＬ文を取得する
    public function getDataSql($STARTDT, $BUSYOCD)
    {
        $strSQL = "";
        $strSQL .= " SELECT BASE.ORDER_NO " . "\r\n";
        $strSQL .= " , BASE.HINMEI  " . "\r\n";
        $strSQL .= " , BASE.TANKA " . "\r\n";
        $strSQL .= " , OD.ORDER_NUM" . "\r\n";
        $strSQL .= " , (OD.ORDER_NUM * BASE.TANKA) KINGAKU " . "\r\n";
        $strSQL .= " FROM   HDTPRESENTBASE BASE" . "\r\n";
        $strSQL .= " LEFT JOIN HDTPRESENTORDER OD" . "\r\n";
        $strSQL .= " ON OD.START_DATE = BASE.START_DATE" . "\r\n";
        $strSQL .= " AND OD.ORDER_NO = BASE.ORDER_NO " . "\r\n";
        $strSQL .= " AND OD.BUSYO_CD = '@BUSYOCD'" . "\r\n";
        $strSQL .= " WHERE BASE.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= " ORDER BY BASE.ORDER_NO" . "\r\n";
        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        $strSQL = str_replace("@STARTDT", $STARTDT, $strSQL);

        return parent::select($strSQL);
    }

    //成約プレゼント注文データを削除する
    public function getDelOrder($STARTDT, $BUSYOCD)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTPRESENTORDER " . "\r\n";
        $strSQL .= " WHERE  START_DATE = '@STARTDT' " . "\r\n";
        $strSQL .= " AND    BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $BUSYOCD, $strSQL);
        $strSQL = str_replace("@STARTDT", $STARTDT, $strSQL);

        return parent::delete($strSQL);
    }

    //追加処理のＳＱＬ文の取得する
    public function getInsertOrder($gridData, $START_DATE, $BUSYO_CD)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTPRESENTORDER  " . "\r\n";
        $strSQL .= " (START_DATE,             " . "\r\n";
        $strSQL .= "  BUSYO_CD,               " . "\r\n";
        $strSQL .= "  ORDER_NO,               " . "\r\n";
        $strSQL .= "  ORDER_NUM,              " . "\r\n";
        $strSQL .= "  OUT_FLG,                " . "\r\n";
        $strSQL .= "  UPD_DATE,               " . "\r\n";
        $strSQL .= "  CREATE_DATE,            " . "\r\n";
        $strSQL .= "  UPD_SYA_CD,             " . "\r\n";
        $strSQL .= "  UPD_PRG_ID,             " . "\r\n";
        $strSQL .= "  UPD_CLT_NM)             " . "\r\n";
        $strSQL .= "  VALUES(                 " . "\r\n";
        $strSQL .= "  '@START_DATE',          " . "\r\n";
        $strSQL .= "  '@BUSYO_CD',            " . "\r\n";
        $strSQL .= "  '@ORDER_NO',            " . "\r\n";
        $strSQL .= "  '@ORDER_NUM',           " . "\r\n";
        $strSQL .= "  '@OUT_FLG',             " . "\r\n";
        $strSQL .= "  SYSDATE,                " . "\r\n";
        $strSQL .= "  SYSDATE,                " . "\r\n";
        $strSQL .= "  '@UPD_SYA_CD',          " . "\r\n";
        $strSQL .= "  '@UPD_PRG_ID',          " . "\r\n";
        $strSQL .= "  '@UPD_CLT_NM' )         " . "\r\n";
        $strSQL = str_replace("@START_DATE", $START_DATE, $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $BUSYO_CD, $strSQL);
        $strSQL = str_replace("@ORDER_NO", $gridData['ORDER_NO'], $strSQL);
        $strSQL = str_replace("@ORDER_NUM", $gridData['ORDER_NUM'], $strSQL);
        $strSQL = str_replace("@OUT_FLG", "0", $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "PresentOrderEntry", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

}
