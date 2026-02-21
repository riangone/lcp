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
class HMTVE060TotalKShop extends ClsComDb
{
    public $SessionComponent;
    public function searchDate($postData)
    {
        return parent::select($this->searchDateSQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：searchDate
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：展示会開催期間に初期値
           '**********************************************************************
           */
    public function searchDateSQL($postData)
    {
        $strSQL = "";
        if ($postData['PrePG'] == 'true') {
            $strSQL .= " SELECT END_DATE " . "\r\n";
            $strSQL .= " FROM   HDTIVENTDATA " . "\r\n";
            $strSQL .= " WHERE  START_DATE = '@STARTDT' " . "\r\n";

            $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermFrom']), $strSQL);
        } else {
            $strSQL .= "SELECT  START_DATE " . "\r\n";
            $strSQL .= ",       END_DATE " . "\r\n";
            $strSQL .= "FROM    HDTIVENTDATA " . "\r\n";
            $strSQL .= "WHERE   BASE_FLG = '1' " . "\r\n";
        }

        return $strSQL;
    }

    /*
           ***********************************************************************
           '処 理 名：店舗名を取得
           '関 数 名：getShopName
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：店舗名を取得する
           '**********************************************************************
           */
    public function getShopName($postData)
    {
        return parent::select($this->getShopNameSQL($postData));
    }

    public function getShopNameSQL($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        if (!isset($postData['TenpoCD_S'])) {
            $postData['TenpoCD_S'] = $this->SessionComponent->read('BusyoCD');
        }
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD " . "\r\n";
        $strSQL .= ",      MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= "FROM   HBUSYO MST " . "\r\n";
        $strSQL .= "INNER  JOIN  (SELECT BUSYO_CD " . "\r\n";
        $strSQL .= ",            (CASE WHEN HDT_TENPO_CD IS NOT NULL" . "\r\n";
        $strSQL .= "                   THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO" . "\r\n";
        $strSQL .= "             FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO " . "\r\n";
        $strSQL .= "WHERE  MST.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        $strSQL .= "AND    BUS.BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $postData['TenpoCD_S'] ? $postData['TenpoCD_S'] : "", $strSQL);

        return $strSQL;
    }

    /*
           ***********************************************************************
           '処 理 名：表示ボタンクリック時データの存在チェック
           '関 数 名：dataCheck
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：表示ボタンクリック時データの存在チェックする
           '**********************************************************************
           */
    public function dataCheck($postData)
    {
        return parent::select($this->dataCheckSQL($postData));
    }

    public function dataCheckSQL($postData)
    {

        $strSQL = "";
        $strSQL .= "SELECT KAKU.START_DATE " . "\r\n";
        $strSQL .= "FROM   HSYAINMST SYA" . "\r\n";
        $strSQL .= "INNER JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT' " . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    BUS.HDT_TENPO_CD = '@TENPO_CD'" . "\r\n";
        $strSQL .= "LEFT JOIN HDTWORKMANAGE WKMN" . "\r\n";
        $strSQL .= "ON     WKMN.IVENT_DATE = '@IVENTDT'" . "\r\n";
        $strSQL .= "AND    WKMN.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUDATA KAKU" . "\r\n";
        $strSQL .= "ON     KAKU.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    KAKU.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "AND    KAKU.KAKUTEI_FLG = '1' " . "\r\n";
        $strSQL .= "AND    KAKU.DATA_KB = '1' " . "\r\n";
        $strSQL .= "WHERE  (NVL(HAI.SYOKUSYU_KB,'A') <> '9'" . "\r\n";

        $strSQL .= "AND     NVL(SYA.TAISYOKU_DATE,'99999999') > '@IVENTDT') " . "\r\n";

        $strSQL .= " AND    ((HAI.IVENT_TARGET_FLG = '1' AND (WKMN.SYAIN_NO IS NULL OR WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "        OR" . "\r\n";
        $strSQL .= "        (HAI.IVENT_TARGET_FLG = '0' AND WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "AND    KAKU.START_DATE IS NOT NULL" . "\r\n";

        $strSQL = str_replace("@TENPO_CD", $postData['lblTenpoCD'], $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData['ddlExhibitDay']), $strSQL);

        return $strSQL;
    }

    /*
           ***********************************************************************
           '処 理 名：成約車種内訳テーブル1の生成
           '関 数 名：fillCarType
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：成約車種内訳テーブル1の生成
           '**********************************************************************
           */
    public function fillCarTypeTbl()
    {
        return parent::select($this->fillCarTypeTblSQL());
    }

    public function fillCarTypeTblSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT SYA.SYASYU_NM " . "\r\n";
        $strSQL .= ",  SYA.SYASYU_CD" . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYA" . "\r\n";
        $strSQL .= "ORDER BY SYA.DISP_NO " . "\r\n";
        $strSQL .= ",        SYA.SYASYU_CD" . "\r\n";

        return $strSQL;
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL2
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：スタッフテーブルのSQL文を取得
           '**********************************************************************
           */
    public function SQL2($postData)
    {
        return parent::select($this->SQL2SQL($postData));
    }

    public function SQL2SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYA.SYAIN_NM " . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NO" . "\r\n";
        $strSQL .= ",      (CASE WHEN KAKU.START_DATE IS NULL THEN '未' ELSE '済' END) JOKYO" . "\r\n";
        $strSQL .= "FROM   HSYAINMST SYA " . "\r\n";
        $strSQL .= "INNER JOIN HHAIZOKU HAI " . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT' " . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS " . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = HAI.BUSYO_CD " . "\r\n";
        $strSQL .= "AND    BUS.HDT_TENPO_CD = '@TENPOCD' " . "\r\n";
        $strSQL .= "LEFT JOIN HDTWORKMANAGE WKMN " . "\r\n";
        $strSQL .= "ON     WKMN.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    WKMN.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUDATA KAKU " . "\r\n";
        $strSQL .= "ON     KAKU.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    KAKU.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "AND    KAKU.KAKUTEI_FLG = '1' " . "\r\n";
        $strSQL .= "AND    KAKU.DATA_KB = '1' " . "\r\n";
        $strSQL .= "WHERE  (NVL(HAI.SYOKUSYU_KB,'A') <> '9' " . "\r\n";
        $strSQL .= " AND     NVL(SYA.TAISYOKU_DATE,'99999999') > '@IVENTDT') " . "\r\n";

        $strSQL .= "AND    ((HAI.IVENT_TARGET_FLG = '1' AND (WKMN.SYAIN_NO IS NULL OR WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "        OR" . "\r\n";
        $strSQL .= "        (HAI.IVENT_TARGET_FLG = '0' AND WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "ORDER BY SYA.SYAIN_NO " . "\r\n";

        $strSQL = str_replace("@TENPOCD", $postData['lblTenpoCD'], $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData['ddlExhibitDay']), $strSQL);

        return $strSQL;
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL3
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報集計明細テーブルのSQL文を取得
           '**********************************************************************
           */
    public function SQL3($postData)
    {
        return parent::select($this->SQL3SQL($postData));
    }

    public function SQL3SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYA.SYAIN_NO " . "\r\n";
        $strSQL .= ",      DECODE(KAKU.START_DATE,NULL,'',(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0)) " . "\r\n";
        $strSQL .= "        + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) RAIJYO_KUMI_KEI " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_KUMI_AB_KOKYAKU " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_KUMI_AB_SINTA " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_KUMI_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_KUMI_NONAB_SINTA	" . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_KUMI_NONAB_FREE " . "\r\n";
        $strSQL .= ",      KAKU.JIZEN_JYUNBI_DM " . "\r\n";
        $strSQL .= ",      KAKU.JIZEN_JYUNBI_DH " . "\r\n";
        $strSQL .= ",      KAKU.JIZEN_JYUNBI_POSTING " . "\r\n";
        $strSQL .= ",      KAKU.JIZEN_JYUNBI_TEL " . "\r\n";
        $strSQL .= ",      KAKU.JIZEN_JYUNBI_KAKUYAKU " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_BUNSEKI_YOBIKOMI " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_BUNSEKI_KAKUYAKU	 " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_BUNSEKI_KOUKOKU " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_BUNSEKI_MEDIA " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_BUNSEKI_CHIRASHI " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_BUNSEKI_TORIGAKARI " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_BUNSEKI_SYOKAI" . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_BUNSEKI_WEB " . "\r\n";
        $strSQL .= ",      KAKU.RAIJYO_BUNSEKI_SONOTA " . "\r\n";
        $strSQL .= ",      KAKU.ENQUETE_KAISYU " . "\r\n";
        $strSQL .= ",      DECODE((NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,DECODE(KAKU.START_DATE,NULL,'',0), " . "\r\n";
        $strSQL .= "       ROUND(NVL(KAKU.ENQUETE_KAISYU,0) / (NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1)) ENQUETE_RITU " . "\r\n";
        $strSQL .= ",      KAKU.ABHOT_KOKYAKU " . "\r\n";
        $strSQL .= ",      KAKU.ABHOT_SINTA " . "\r\n";
        $strSQL .= ",      DECODE((NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0)+ NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,DECODE(KAKU.START_DATE,NULL,'',0),ROUND((NVL(KAKU.ABHOT_KOKYAKU,0) + NVL(KAKU.ABHOT_SINTA,0)) / (NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) " . "\r\n";
        $strSQL .= "       + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1)) ABHOT_RITU " . "\r\n";
        $strSQL .= ",      KAKU.ABHOT_ZAN " . "\r\n";

        $strSQL .= " ,      KAKU.ABHOT_ZAN_SINGATA " . "\r\n";

        $strSQL .= ",      KAKU.SATEI_KOKYAKU " . "\r\n";
        $strSQL .= ",      KAKU.SATEI_SINTA " . "\r\n";
        $strSQL .= ",      KAKU.DEMO_KENSU	" . "\r\n";

        $strSQL .= ",      KAKU.SATEI_KOKYAKU_TA " . "\r\n";
        $strSQL .= ",      KAKU.SATEI_SINTA_TA " . "\r\n";
        $strSQL .= ",      NVL(KAKU.RUNCOST_KENSU,0) RUNCOST_KENSU" . "\r\n";

        $strSQL .= ",      NVL(KAKU.SKYPLAN_KENSU,0) SKYPLAN_KENSU" . "\r\n";

        $strSQL .= ",      NVL(KAKU.RUNCOST_SEIYAKU_KENSU,0) RUNCOST_SEIYAKU_KENSU" . "\r\n";
        $strSQL .= ",      NVL(KAKU.SKYPLAN_KEIYAKU_KENSU,0) SKYPLAN_KEIYAKU_KENSU" . "\r\n";

        $strSQL .= " ,     DECODE((NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) " . "\r\n";
        $strSQL .= "       + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,DECODE(KAKU.START_DATE,NULL,'',0)," . "\r\n";
        $strSQL .= "       ROUND(KAKU.DEMO_KENSU / (NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) " . "\r\n";
        $strSQL .= "       + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1)) DEMO_RITU " . "\r\n";
        $strSQL .= ",      DECODE(KAKU.START_DATE,NULL,'',(NVL(KAKU.SEIYAKU_AB_KOKYAKU,0) + NVL(KAKU.SEIYAKU_AB_SINTA,0) + NVL(KAKU.SEIYAKU_NONAB_KOKYAKU,0) " . "\r\n";
        $strSQL .= "       + NVL(KAKU.SEIYAKU_NONAB_SINTA,0))) SEIYAKU_KEI " . "\r\n";
        $strSQL .= ",      KAKU.SEIYAKU_AB_KOKYAKU " . "\r\n";
        $strSQL .= ",      KAKU.SEIYAKU_AB_SINTA " . "\r\n";
        $strSQL .= ",      KAKU.SEIYAKU_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= ",      KAKU.SEIYAKU_NONAB_SINTA " . "\r\n";
        $strSQL .= ",      KAKU.SEIYAKU_NONAB_FREE " . "\r\n";
        $strSQL .= ",      DECODE((NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,DECODE(KAKU.START_DATE,NULL,'',0)," . "\r\n";
        $strSQL .= "       ROUND((NVL(KAKU.SEIYAKU_NONAB_KOKYAKU,0) + NVL(KAKU.SEIYAKU_NONAB_SINTA,0)) / " . "\r\n";
        $strSQL .= "             (NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1)) SOKU_RITU " . "\r\n";
        $strSQL .= "FROM   HSYAINMST SYA " . "\r\n";
        $strSQL .= "INNER JOIN HHAIZOKU HAI " . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT' " . "\r\n";

        $strSQL .= "INNER JOIN HBUSYO BUS " . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = HAI.BUSYO_CD " . "\r\n";
        $strSQL .= "AND    BUS.HDT_TENPO_CD = '@TENPOCD' " . "\r\n";
        $strSQL .= "LEFT JOIN HDTWORKMANAGE WKMN " . "\r\n";
        $strSQL .= "ON     WKMN.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    WKMN.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUDATA KAKU " . "\r\n";
        $strSQL .= "ON     KAKU.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    KAKU.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "AND    KAKU.DATA_KB = '1' " . "\r\n";
        $strSQL .= "WHERE   (NVL(HAI.SYOKUSYU_KB,'A') <> '9' " . "\r\n";
        $strSQL .= " AND     NVL(SYA.TAISYOKU_DATE,'99999999') > '@IVENTDT') " . "\r\n";

        $strSQL .= " AND    ((HAI.IVENT_TARGET_FLG = '1' AND (WKMN.SYAIN_NO IS NULL OR WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "        OR" . "\r\n";
        $strSQL .= "        (HAI.IVENT_TARGET_FLG = '0' AND WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "ORDER BY SYA.SYAIN_NO " . "\r\n";

        $strSQL = str_replace("@TENPOCD", $postData['lblTenpoCD'], $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData['ddlExhibitDay']), $strSQL);

        return $strSQL;
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL4
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：成約車種内訳テーブルのSQL文を取得
           '**********************************************************************
           */
    public function SQL4($postData)
    {
        return parent::select($this->SQL4SQL($postData));
    }

    public function SQL4SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYA.SYAIN_NO " . "\r\n";
        $strSQL .= ",      SYU.SYASYU_CD " . "\r\n";
        $strSQL .= ",      KAKU.SEIYAKU_DAISU_D	 " . "\r\n";
        $strSQL .= "FROM   HSYAINMST SYA " . "\r\n";
        $strSQL .= "INNER JOIN HDTSYASYU SYU " . "\r\n";
        $strSQL .= "ON     1 = 1 " . "\r\n";
        $strSQL .= "INNER JOIN HHAIZOKU HAI	" . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT' " . "\r\n";

        $strSQL .= "INNER JOIN HBUSYO BUS " . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = HAI.BUSYO_CD " . "\r\n";
        $strSQL .= "AND    BUS.HDT_TENPO_CD = '@TENPOCD' " . "\r\n";
        $strSQL .= "LEFT JOIN HDTWORKMANAGE WKMN " . "\r\n";
        $strSQL .= "ON     WKMN.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    WKMN.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU KAKU " . "\r\n";
        $strSQL .= "ON     KAKU.IVENT_DATE = '@IVENTDT'	 " . "\r\n";
        $strSQL .= "AND    KAKU.SYAIN_NO = SYA.SYAIN_NO	 " . "\r\n";
        $strSQL .= "AND    KAKU.SYASYU_CD = SYU.SYASYU_CD " . "\r\n";
        $strSQL .= "WHERE   (NVL(HAI.SYOKUSYU_KB,'A') <> '9' " . "\r\n";

        $strSQL .= " AND     NVL(SYA.TAISYOKU_DATE,'99999999') > '@IVENTDT') " . "\r\n";

        $strSQL .= " AND    ((HAI.IVENT_TARGET_FLG = '1' AND (WKMN.SYAIN_NO IS NULL OR WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "        OR" . "\r\n";
        $strSQL .= "        (HAI.IVENT_TARGET_FLG = '0' AND WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "ORDER BY SYA.SYAIN_NO " . "\r\n";
        $strSQL .= ",        SYU.DISP_NO " . "\r\n";
        $strSQL .= ",        SYU.SYASYU_CD" . "\r\n";

        $strSQL = str_replace("@TENPOCD", $postData['lblTenpoCD'], $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData['ddlExhibitDay']), $strSQL);

        return $strSQL;
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL5
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報集計合計のSQL文を取得
           '**********************************************************************
           */
    public function SQL5($postData)
    {
        return parent::select($this->SQL5SQL($postData));
    }

    public function SQL5SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "  SELECT BUS.HDT_TENPO_CD  " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) " . "\r\n";
        $strSQL .= "            + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) RAIJYO_KUMI_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU) RAIJYO_KUMI_AB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_AB_SINTA)  RAIJYO_KUMI_AB_SINTA " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU) RAIJYO_KUMI_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_NONAB_SINTA " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_NONAB_FREE) RAIJYO_KUMI_NONAB_FREE " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_DM) JIZEN_JYUNBI_DM " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_DH) JIZEN_JYUNBI_DH " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_POSTING) JIZEN_JYUNBI_POSTING " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_TEL) JIZEN_JYUNBI_TEL " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_KAKUYAKU) JIZEN_JYUNBI_KAKUYAKU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_YOBIKOMI) RAIJYO_BUNSEKI_YOBIKOMI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_KAKUYAKU) RAIJYO_BUNSEKI_KAKUYAKU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_KOUKOKU) RAIJYO_BUNSEKI_KOUKOKU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_MEDIA) RAIJYO_BUNSEKI_MEDIA " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_CHIRASHI) RAIJYO_BUNSEKI_CHIRASHI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_TORIGAKARI) RAIJYO_BUNSEKI_TORIGAKARI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_SYOKAI) RAIJYO_BUNSEKI_SYOKAI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_WEB) RAIJYO_BUNSEKI_WEB " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_SONOTA) RAIJYO_BUNSEKI_SONOTA " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ENQUETE_KAISYU) ENQUETE_KAISYU " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),'0','0', " . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(NVL(KAKU.ENQUETE_KAISYU,0)) / SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1))) ENQUETE_RITU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_KOKYAKU) ABHOT_KOKYAKU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_SINTA) ABHOT_SINTA " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0)+ NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),'0','0',TO_CHAR(ROUND(SUM(NVL(KAKU.ABHOT_KOKYAKU,0) + NVL(KAKU.ABHOT_SINTA,0)) / SUM(NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0)  " . "\r\n";
        $strSQL .= "                   + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1))) ABHOT_RITU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_ZAN) ABHOT_ZAN " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_ZAN_SINGATA) ABHOT_ZAN_SINGATA " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SATEI_KOKYAKU) SATEI_KOKYAKU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SATEI_SINTA) SATEI_SINTA " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.DEMO_KENSU) DEMO_KENSU " . "\r\n";

        $strSQL .= " ,      SUM(KAKU.SATEI_KOKYAKU_TA) SATEI_KOKYAKU_TA " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SATEI_SINTA_TA) SATEI_SINTA_TA " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.RUNCOST_KENSU,0)) RUNCOST_KENSU " . "\r\n";

        $strSQL .= " ,      SUM(NVL(KAKU.SKYPLAN_KENSU,0)) SKYPLAN_KENSU " . "\r\n";

        $strSQL .= " ,      SUM(NVL(KAKU.RUNCOST_SEIYAKU_KENSU,0)) RUNCOST_SEIYAKU_KENSU " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SKYPLAN_KEIYAKU_KENSU,0)) SKYPLAN_KEIYAKU_KENSU " . "\r\n";

        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0)" . "\r\n";
        $strSQL .= "               + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),'0','0'," . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(KAKU.DEMO_KENSU) / SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0)  " . "\r\n";
        $strSQL .= "               + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1))) DEMO_RITU " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SEIYAKU_AB_KOKYAKU,0) + NVL(KAKU.SEIYAKU_AB_SINTA,0) + NVL(KAKU.SEIYAKU_NONAB_KOKYAKU,0)  " . "\r\n";
        $strSQL .= "            + NVL(KAKU.SEIYAKU_NONAB_SINTA,0)) SEIYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_AB_KOKYAKU) SEIYAKU_AB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_AB_SINTA) SEIYAKU_AB_SINTA " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_NONAB_KOKYAKU) SEIYAKU_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_NONAB_SINTA " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_NONAB_FREE) SEIYAKU_NONAB_FREE " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),'0','0'," . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(NVL(KAKU.SEIYAKU_NONAB_KOKYAKU,0) + NVL(KAKU.SEIYAKU_NONAB_SINTA,0)) /  " . "\r\n";
        $strSQL .= "              SUM(NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1))) SOKU_RITU " . "\r\n";
        $strSQL .= " FROM   HSYAINMST SYA " . "\r\n";
        $strSQL .= " INNER JOIN HHAIZOKU HAI " . "\r\n";
        $strSQL .= " ON     HAI.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT' " . "\r\n";

        $strSQL .= " INNER JOIN HBUSYO BUS " . "\r\n";
        $strSQL .= " ON     BUS.BUSYO_CD = HAI.BUSYO_CD " . "\r\n";
        $strSQL .= " AND    BUS.HDT_TENPO_CD = '@TENPOCD' " . "\r\n";
        $strSQL .= " LEFT JOIN HDTWORKMANAGE WKMN " . "\r\n";
        $strSQL .= " ON     WKMN.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= " AND    WKMN.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= " LEFT JOIN HDTKAKUHOUDATA KAKU " . "\r\n";
        $strSQL .= " ON     KAKU.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= " AND    KAKU.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= " AND    KAKU.DATA_KB = '1' " . "\r\n";
        $strSQL .= " WHERE  (NVL(HAI.SYOKUSYU_KB,'A') <> '9' " . "\r\n";
        $strSQL .= " AND     NVL(SYA.TAISYOKU_DATE,'99999999') > '@IVENTDT') " . "\r\n";

        $strSQL .= " AND    ((HAI.IVENT_TARGET_FLG = '1' AND (WKMN.SYAIN_NO IS NULL OR WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "        OR" . "\r\n";
        $strSQL .= "        (HAI.IVENT_TARGET_FLG = '0' AND WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= " GROUP BY BUS.HDT_TENPO_CD " . "\r\n";

        $strSQL = str_replace("@TENPOCD", $postData['lblTenpoCD'], $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData['ddlExhibitDay']), $strSQL);

        return $strSQL;
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL6
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：合計_成約車種内訳テーブル2のSQL文を取得
           '**********************************************************************
           */
    public function SQL6($postData)
    {
        return parent::select($this->SQL6SQL($postData));
    }

    public function SQL6SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "Select SYU.SYASYU_CD " . "\r\n";
        $strSQL .= ", SUM(KAKU.SEIYAKU_DAISU_D) SEIYAKU_DAISU_KEI" . "\r\n";
        $strSQL .= "FROM   HSYAINMST SYA " . "\r\n";
        $strSQL .= "INNER JOIN HDTSYASYU SYU " . "\r\n";
        $strSQL .= "ON     1 = 1 " . "\r\n";
        $strSQL .= "INNER JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= " AND    HAI.START_DATE <= '@IVENTDT' " . "\r\n";
        $strSQL .= " AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT' " . "\r\n";

        $strSQL .= "INNER JOIN HBUSYO BUS " . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = HAI.BUSYO_CD	 " . "\r\n";
        $strSQL .= "AND    BUS.HDT_TENPO_CD = '@TENPOCD' " . "\r\n";
        $strSQL .= "LEFT JOIN HDTWORKMANAGE WKMN " . "\r\n";
        $strSQL .= "ON     WKMN.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    WKMN.SYAIN_NO = SYA.SYAIN_NO	 " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU KAKU " . "\r\n";
        $strSQL .= "ON     KAKU.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    KAKU.SYAIN_NO = SYA.SYAIN_NO	 " . "\r\n";
        $strSQL .= "AND    KAKU.SYASYU_CD = SYU.SYASYU_CD " . "\r\n";
        $strSQL .= "WHERE  (NVL(HAI.SYOKUSYU_KB,'A') <> '9' " . "\r\n";

        $strSQL .= "AND     NVL(SYA.TAISYOKU_DATE,'99999999') >  '@IVENTDT') " . "\r\n";

        $strSQL .= " AND    ((HAI.IVENT_TARGET_FLG = '1' AND (WKMN.SYAIN_NO IS NULL OR WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "        OR" . "\r\n";
        $strSQL .= "        (HAI.IVENT_TARGET_FLG = '0' AND WKMN.WORK_STATE = '1'))" . "\r\n";
        $strSQL .= "GROUP BY SYU.SYASYU_CD	 " . "\r\n";
        $strSQL .= ",        SYU.DISP_NO " . "\r\n";
        $strSQL .= "ORDER BY SYU.DISP_NO " . "\r\n";
        $strSQL .= ",        SYU.SYASYU_CD " . "\r\n";

        $strSQL = str_replace("@TENPOCD", $postData['lblTenpoCD'], $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData['ddlExhibitDay']), $strSQL);

        return $strSQL;
    }

}
