<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE130PublicityOrderConfirm extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //店舗名のＳＱＬ文を取得する
    function getShopNMSQL($BUSYOCD)
    {
        $strSql = "";
        $strSql = $strSql . "  Select MST.BUSYO_CD                                               " . "\r\n";
        $strSql = $strSql . "  ,      MST.BUSYO_RYKNM                                            " . "\r\n";
        $strSql = $strSql . "  FROM   HBUSYO MST                                                 " . "\r\n";
        $strSql = $strSql . "  INNER JOIN  (SELECT BUSYO_CD                                      " . "\r\n";
        $strSql = $strSql . "               ,      (CASE WHEN HDT_TENPO_CD IS NOT NULL           " . "\r\n";
        $strSql = $strSql . "                       THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO " . "\r\n";
        $strSql = $strSql . "               FROM   HBUSYO) BUS                                   " . "\r\n";
        $strSql = $strSql . "  ON     MST.BUSYO_CD = BUS.V_TENPO                                 " . "\r\n";
        $strSql = $strSql . "  WHERE  MST.HDT_TENPO_DISP_NO Is Not NULL                          " . "\r\n";
        $strSql = $strSql . "  AND    BUS.BUSYO_CD = '@BUSYOCD'                                  " . "\r\n";

        $strSql = str_replace("@BUSYOCD", $BUSYOCD, $strSql);

        return $strSql;
    }

    //宣材確定データを取得する
    function getTitleSQL($NENGETU)
    {
        $strSql = "";
        $strSql = $strSql . "   SELECT (GOODS.HINMEI1 || ' @' || GOODS.TANKA1) COL_HED_1 " . "\r\n";
        $strSql = $strSql . "   ,      (GOODS.HINMEI2 || ' @' || GOODS.TANKA2) COL_HED_2 " . "\r\n";
        $strSql = $strSql . "   ,      (GOODS.HINMEI3 || ' @' || GOODS.TANKA3) COL_HED_3 " . "\r\n";
        $strSql = $strSql . "   FROM   HDTPUBLICITYGOODS GOODS                           " . "\r\n";
        $strSql = $strSql . "   WHERE  GOODS.IVENT_YM = '@NENGETU'                       " . "\r\n";

        $strSql = str_replace("@NENGETU", $NENGETU, $strSql);

        return $strSql;
    }

    //展示会データ用SQLの取得
    function getDetailSQL($NENGETU, $BUSYOCD)
    {
        $strSql = "";
        $strSql = $strSql . "  Select IVDT.START_DATE                                                   " . "\r\n";
        $strSql = $strSql . "  ,      TO_CHAR(TO_DATE(IVDT.START_DATE,'YYYY/MM/DD'),'MM/DD') || '～' || " . "\r\n";
        $strSql = $strSql . "        TO_CHAR(TO_DATE(IVDT.END_DATE,'YYYY/MM/DD'),'MM/DD') ||       " . "\r\n";
        $strSql = $strSql . "        '<br/><br/>'  ||  IVDT.IVENT_NM  AS HIDUKE_NM                " . "\r\n";
        //$strSql = $strSql . "         TO_CHAR(TO_DATE(IVDT.START_DATE,'YYYY/MM/DD'),'MM/DD') HIDUKE     " . "\r\n";
        //$strSql = $strSql . "  ,      IVDT.IVENT_NM                                                     " . "\r\n";
        $strSql = $strSql . "  ,      NVL(DATA.ORDER_VAL1,0) ORDER_VAL1                                 " . "\r\n";
        $strSql = $strSql . "  ,      NVL(DATA.ORDER_VAL2,0) ORDER_VAL2                                 " . "\r\n";
        $strSql = $strSql . "  ,      NVL(DATA.ORDER_VAL3,0) ORDER_VAL3                                 " . "\r\n";
        $strSql = $strSql . "  ,      (NVL(DATA.ORDER_VAL1,0) * NVL(GOODS.TANKA1,0)                     " . "\r\n";
        $strSql = $strSql . "        + NVL(DATA.ORDER_VAL2,0) * NVL(GOODS.TANKA2,0)                     " . "\r\n";
        $strSql = $strSql . "        + NVL(DATA.ORDER_VAL3,0) * NVL(GOODS.TANKA3,0)) GOUKEI    　       " . "\r\n";
        $strSql = $strSql . "  ,      PLIV.BIKOU                                                        " . "\r\n";
        $strSql = $strSql . "  FROM   HDTIVENTDATA IVDT                                                 " . "\r\n";
        $strSql = $strSql . "  INNER JOIN HDTPUBLICITYIVENT PLIV                                        " . "\r\n";
        $strSql = $strSql . "  ON     PLIV.START_DATE = IVDT.START_DATE                                 " . "\r\n";
        $strSql = $strSql . "  INNER JOIN HDTPUBLICITYGOODS GOODS                                       " . "\r\n";
        $strSql = $strSql . "  ON     GOODS.IVENT_YM = '@NENGETU'                                       " . "\r\n";
        $strSql = $strSql . "  LEFT JOIN WK_HDTPUBLICITYDATA DATA                                       " . "\r\n";
        $strSql = $strSql . "  ON     DATA.START_DATE = IVDT.START_DATE                                 " . "\r\n";
        $strSql = $strSql . "  AND    DATA.BUSYO_CD = '@BUSYOCD'                                        " . "\r\n";
        $strSql = $strSql . "  WHERE  PLIV.IVENT_YM = '@NENGETU'                                        " . "\r\n";
        $strSql = $strSql . "  ORDER BY IVDT.START_DATE                                                 " . "\r\n";

        $strSql = str_replace("@NENGETU", $NENGETU, $strSql);
        $strSql = str_replace("@BUSYOCD", $BUSYOCD, $strSql);

        return $strSql;
    }

    //宣材確定データのSQLの取得
    function getCheckYMDSQL($NENGETU)
    {
        $strSql = "";
        $strSql = $strSql . "  Select KAKU.KAKUTEI_FLG             " . "\r\n";
        $strSql = $strSql . "  FROM   HDTPUBLICITYKAKUTEIDATA KAKU " . "\r\n";
        $strSql = $strSql . "  WHERE  KAKU.IVENT_YM = '@NENGETU'   " . "\r\n";

        $strSql = str_replace("@NENGETU", $NENGETU, $strSql);

        return $strSql;
    }

    //宣材注文データの更新日付のSQLの取得
    function getOrderUpdateSQL($NENGETU, $BUSYOCD)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT '@NENGETU'                 " . "\r\n";
        $strSql = $strSql . " ,      MAX(DT.UPD_DATE) UPDDT     " . "\r\n";
        $strSql = $strSql . " ,      MAX(W_DT.UPD_DATE) W_UPDDT " . "\r\n";
        $strSql = $strSql . " FROM   HDTPUBLICITYDATA DT        " . "\r\n";
        $strSql = $strSql . " ,      WK_HDTPUBLICITYDATA W_DT   " . "\r\n";
        $strSql = $strSql . " WHERE  DT.IVENT_YM = '@NENGETU'   " . "\r\n";
        $strSql = $strSql . " AND    W_DT.IVENT_YM = '@NENGETU' " . "\r\n";
        $strSql = $strSql . " AND    DT.BUSYO_CD = '@BUSYOCD'   " . "\r\n";
        $strSql = $strSql . " AND    W_DT.BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSql = str_replace("@NENGETU", $NENGETU, $strSql);
        $strSql = str_replace("@BUSYOCD", $BUSYOCD, $strSql);

        return $strSql;
    }

    //宣材注文データの削除処理のSQLの取得
    function getOrderDeleteSQL($NENGETU, $BUSYOCD)
    {
        $strSql = "";
        $strSql = $strSql . " DELETE FROM HDTPUBLICITYDATA " . "\r\n";
        $strSql = $strSql . " WHERE  IVENT_YM = '@NENGETU' " . "\r\n";
        $strSql = $strSql . " AND    BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSql = str_replace("@NENGETU", $NENGETU, $strSql);
        $strSql = str_replace("@BUSYOCD", $BUSYOCD, $strSql);

        return $strSql;
    }

    //宣材注文データに登録のSQLの取得
    function getOrderLoginSQL($IVENTYM, $BUSYOCD)
    {
        $strSql = "";
        $strSql = $strSql . " INSERT INTO HDTPUBLICITYDATA                                              " . "\r\n";
        $strSql = $strSql . " ( IVENT_YM  ,BUSYO_CD ,START_DATE  ,ORDER_VAL1 ,ORDER_VAL2 ,ORDER_VAL3       " . "\r\n";
        $strSql = $strSql . "    ,OUT_FLG  ,CREATE_DATE  ,UPD_DATE  ,UPD_SYA_CD  ,UPD_PRG_ID ,UPD_CLT_NM ) " . "\r\n";
        $strSql = $strSql . " SELECT IVENT_YM                                                              " . "\r\n";
        $strSql = $strSql . " ,      BUSYO_CD                                                              " . "\r\n";
        $strSql = $strSql . " ,      START_DATE                                                            " . "\r\n";
        $strSql = $strSql . " ,      ORDER_VAL1                                                            " . "\r\n";
        $strSql = $strSql . " ,      ORDER_VAL2                                                            " . "\r\n";
        $strSql = $strSql . " ,      ORDER_VAL3                                                            " . "\r\n";
        $strSql = $strSql . " ,      OUT_FLG                                                               " . "\r\n";
        $strSql = $strSql . " ,      DECODE(CREATE_DATE,NULL,SYSDATE,CREATE_DATE)                          " . "\r\n";
        $strSql = $strSql . " ,      SYSDATE                                                               " . "\r\n";
        $strSql = $strSql . " ,      UPD_SYA_CD                                                            " . "\r\n";
        $strSql = $strSql . " ,      UPD_PRG_ID                                                            " . "\r\n";
        $strSql = $strSql . " ,      UPD_CLT_NM                                                            " . "\r\n";
        $strSql = $strSql . " FROM   WK_HDTPUBLICITYDATA                                                   " . "\r\n";
        $strSql = $strSql . " WHERE  IVENT_YM = '@NENGETU'                                                 " . "\r\n";
        $strSql = $strSql . " AND    BUSYO_CD = '@BUSYOCD'                                                 " . "\r\n";
        $strSql = str_replace("@NENGETU", $IVENTYM, $strSql);
        $strSql = str_replace("@BUSYOCD", $BUSYOCD, $strSql);

        return $strSql;
    }

    //ワークテーブルを削除のSQLの取得
    function getWorkDelSQL($IVENTYM, $BUSYOCD)
    {
        $strSql = "";
        $strSql = $strSql . " DELETE FROM WK_HDTPUBLICITYDATA " . "\r\n";
        $strSql = $strSql . " WHERE  IVENT_YM = '@IVENTYM'    " . "\r\n";
        $strSql = $strSql . " AND    BUSYO_CD = '@BUSYOCD'    " . "\r\n";

        $strSql = str_replace("@IVENTYM", $IVENTYM, $strSql);
        $strSql = str_replace("@BUSYOCD", $BUSYOCD, $strSql);

        return $strSql;
    }

    //店舗名を取得する
    public function getShopNM($BUSYOCD)
    {
        return parent::select($this->getShopNMSQL($BUSYOCD));
    }

    //宣材確定データを取得する
    public function getTitle($NENGETU)
    {
        return parent::select($this->getTitleSQL($NENGETU));
    }

    //展示会データ取得を取得する
    public function getDetail($NENGETU, $BUSYOCD)
    {
        return parent::select($this->getDetailSQL($NENGETU, $BUSYOCD));
    }

    //宣材確定データのSQLの取得
    public function getCheckYMD($NENGETU)
    {
        return parent::select($this->getCheckYMDSQL($NENGETU));
    }

    //宣材注文データの更新日付のSQLの取得
    public function getOrderUpdate($NENGETU, $BUSYOCD)
    {
        return parent::select($this->getOrderUpdateSQL($NENGETU, $BUSYOCD));
    }

    //宣材注文データの削除処理のSQLの取得
    public function getOrderDelete($NENGETU, $BUSYOCD)
    {
        return parent::delete($this->getOrderDeleteSQL($NENGETU, $BUSYOCD));
    }

    //宣材注文データに登録のSQLの取得
    public function getOrderLogin($IVENTYM, $BUSYOCD)
    {
        return parent::insert($this->getOrderLoginSQL($IVENTYM, $BUSYOCD));
    }

    //ワークテーブルを削除のSQLの取得
    public function getWorkDel($IVENTYM, $BUSYOCD)
    {
        return parent::delete($this->getWorkDelSQL($IVENTYM, $BUSYOCD));
    }

}
