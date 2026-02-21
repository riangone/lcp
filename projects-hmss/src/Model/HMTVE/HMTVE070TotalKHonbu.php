<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20251222         機能追加要望                    2026年1月から、法人営業が２Gr体制となります      YIN
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
use App\Model\HMTVE\Component\ClsComFncHMTVE;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE070TotalKHonbu extends ClsComDb
{
    public $ClsComFncHMTVE;
    public function SQL($postData)
    {
        return parent::select($this->SQLSQL($postData));
    }

    public function SQLSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT START_DATE " . "\r\n";
        $strSQL .= " FROM   HDTKAKUHOUDATA KAKU " . "\r\n";
        $strSQL .= " WHERE  KAKU.START_DATE = '@STARTDT' " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);
        return $strSQL;
    }

    public function SQL1($postData)
    {
        return parent::select($this->SQL1SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL1
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：店舗データのSQL文を取得
           '**********************************************************************
           */
    public function SQL1SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT TENPO.BUSYO_CD" . "\r\n";
        $strSQL .= ",      TENPO.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ",      (CASE WHEN NVL(WK_KAKU.CNT,0) >= NVL(WK_KIN.CNT,0) THEN '済' ELSE '未' END) JYOKYO" . "\r\n";
        $strSQL .= "FROM   HBUSYO BUS" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO TENPO  " . "\r\n";
        $strSQL .= "ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD  " . "\r\n";
        $strSQL .= "AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        $strSQL .= "LEFT JOIN (" . "\r\n";
        $strSQL .= "           SELECT HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "           ,      COUNT(KAKU.SYAIN_NO) CNT" . "\r\n";
        $strSQL .= "           FROM   HDTKAKUHOUDATA KAKU" . "\r\n";
        $strSQL .= "           INNER JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "           ON     HAI.SYAIN_NO = KAKU.SYAIN_NO" . "\r\n";
        $strSQL .= "           AND    HAI.BUSYO_CD = KAKU.BUSYO_CD" . "\r\n";
        $strSQL .= "           AND    HAI.START_DATE <= KAKU.IVENT_DATE" . "\r\n";
        $strSQL .= "           AND    NVL(HAI.END_DATE,'99999999') >= KAKU.IVENT_DATE" . "\r\n";
        $strSQL .= "           WHERE  KAKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "           AND    KAKU.KAKUTEI_FLG = '1'" . "\r\n";
        $strSQL .= "           AND    KAKU.DATA_KB = '1'" . "\r\n";
        $strSQL .= "           GROUP BY HAI.BUSYO_CD" . "\r\n";
        $strSQL .= ") WK_KAKU" . "\r\n";
        $strSQL .= "ON        WK_KAKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN (" . "\r\n";
        $strSQL .= "           SELECT V.BUSYO_CD" . "\r\n";
        $strSQL .= "           ,      COUNT(*) CNT" . "\r\n";
        $strSQL .= "           FROM   (" . "\r\n";

        // 展示会開催期間分下記をＵＮＩＯＮで結合してください。
        // @KIKAN = 展示会開催日
        $blnFirst = true;
        $intAdd = 0;
        $date_diff = date_diff(date_create($postData['lblExhibitTermStart']), date_create($postData['lblExhibitTermEnd']));
        while ($date_diff->days >= $intAdd) {
            $dtTemp = strtotime($postData['lblExhibitTermStart'] . "+" . $intAdd . " day");
            $strDate = date('Ymd', $dtTemp);
            if ($blnFirst) {
                $blnFirst = false;
            } else {
                $strSQL .= "                   UNION ALL " . "\r\n";
            }

            $strSQL .= "                   SELECT HAI.BUSYO_CD" . "\r\n";
            $strSQL .= "                   ,      SYA.SYAIN_NO" . "\r\n";
            $strSQL .= "                   FROM   HSYAINMST SYA" . "\r\n";
            $strSQL .= "                   LEFT JOIN HHAIZOKU HAI" . "\r\n";
            $strSQL .= "                   ON     HAI.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
            $strSQL .= "                   AND    HAI.START_DATE <= '@KIKAN'" . "\r\n";
            $strSQL .= "                   AND    NVL(HAI.END_DATE,'99999999') >= '@KIKAN'" . "\r\n";
            $strSQL .= "                   LEFT JOIN HDTWORKMANAGE WKM" . "\r\n";
            $strSQL .= "                   ON     WKM.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
            $strSQL .= "                   AND    WKM.IVENT_DATE = '@KIKAN'" . "\r\n";
            $strSQL .= "                   WHERE  NVL(SYA.TAISYOKU_DATE,'99999999') >= '@KIKAN'" . "\r\n";
            $strSQL .= "                   AND    ((HAI.IVENT_TARGET_FLG = '1' AND (WKM.SYAIN_NO IS NULL OR WKM.WORK_STATE = '1'))" . "\r\n";
            $strSQL .= "                           OR" . "\r\n";
            $strSQL .= "                           (HAI.IVENT_TARGET_FLG = '0' AND WKM.WORK_STATE = '1'))" . "\r\n";
            $strSQL = str_replace("@KIKAN", $strDate, $strSQL);

            $intAdd++;
        }
        $strSQL .= "             ) V" . "\r\n";
        $strSQL .= "             GROUP BY V.BUSYO_CD" . "\r\n";
        $strSQL .= ") WK_KIN" . "\r\n";
        $strSQL .= "ON        WK_KIN.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " WHERE TENPO.BUSYO_CD not in ('443','463') " . "\r\n";
        }
        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'TENPO');

        $strSQL .= "ORDER BY  TENPO.IVENT_TENPO_DISP_NO" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);
        $strSQL = str_replace("@ENDDT", str_replace("/", "", $postData['lblExhibitTermEnd']), $strSQL);

        return $strSQL;
    }

    public function SQL2($postData)
    {
        return parent::select($this->SQL2SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL2
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報入力データのSQL文を取得
           '**********************************************************************
           */
    public function SQL2SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "  SELECT TENPO.BUSYO_CD  " . "\r\n";
        $strSQL .= "  ,TENPO.BUSYO_RYKNM  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= "            + KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU) RAIJYO_KUMI_AB_KOKYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_AB_SINTA)  RAIJYO_KUMI_AB_SINTA_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU) RAIJYO_KUMI_NONAB_KOKYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_NONAB_SINTA_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_NONAB_FREE) RAIJYO_KUMI_NONAB_FREE_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_DM) JIZEN_JYUNBI_DM_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_DH) JIZEN_JYUNBI_DH_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_POSTING) JIZEN_JYUNBI_POSTING_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_TEL) JIZEN_JYUNBI_TEL_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_KAKUYAKU) JIZEN_JYUNBI_KAKUYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_YOBIKOMI) RAIJYO_BUNSEKI_YOBIKOMI_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_KAKUYAKU) RAIJYO_BUNSEKI_KAKUYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_KOUKOKU) RAIJYO_BUNSEKI_KOUKOKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_MEDIA) RAIJYO_BUNSEKI_MEDIA_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_CHIRASHI) RAIJYO_BUNSEKI_CHIRASHI_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_TORIGAKARI) RAIJYO_BUNSEKI_TORIGAKARI_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_SYOKAI) RAIJYO_BUNSEKI_SYOKAI_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_WEB) RAIJYO_BUNSEKI_WEB_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_SONOTA) RAIJYO_BUNSEKI_SONOTA_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ENQUETE_KAISYU) ENQUETE_KAISYU_KEI " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0, " . "\r\n";
        $strSQL .= "        TO_CHAR(SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0))), " . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(KAKU.ENQUETE_KAISYU) / SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1))) ENQUETE_RITU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_KOKYAKU) ABHOT_KOKYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_SINTA) ABHOT_SINTA_KEI " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,DECODE(MAX(KAKU.START_DATE),NULL,NULL,'0'),TO_CHAR(ROUND(SUM(KAKU.ABHOT_KOKYAKU + KAKU.ABHOT_SINTA) / SUM(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU  " . "\r\n";
        $strSQL .= "                   + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100 ,1))) ABHOT_RITU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_ZAN) ABHOT_ZAN_KEI " . "\r\n";

        $strSQL .= " ,      SUM(KAKU.SATEI_KOKYAKU) SATEI_KOKYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SATEI_KOKYAKU_TA,0)) SATEI_KOKYAKU_TA_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SATEI_SINTA) SATEI_SINTA_KEI " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SATEI_SINTA_TA,0)) SATEI_SINTA_TA_KEI " . "\r\n";

        $strSQL .= " ,      SUM(KAKU.DEMO_KENSU) DEMO_KENSU_KEI " . "\r\n";
        $strSQL .= ",       DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0)" . "\r\n";
        $strSQL .= "               + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,DECODE(MAX(KAKU.START_DATE),NULL,NULL,'0')," . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(KAKU.DEMO_KENSU) / SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA  " . "\r\n";
        $strSQL .= "               + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100,1))) DEMO_RITU " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.RUNCOST_KENSU,0)) RUNCOST_KENSU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SKYPLAN_KENSU,0)) SKYPLAN_KENSU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.RUNCOST_SEIYAKU_KENSU,0)) RUNCOST_SEIYAKU_KENSU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SKYPLAN_KEIYAKU_KENSU,0)) SKYPLAN_KEIYAKU_KENSU_KEI " . "\r\n";

        $strSQL .= " ,      SUM(KAKU.SEIYAKU_AB_KOKYAKU + KAKU.SEIYAKU_AB_SINTA + KAKU.SEIYAKU_NONAB_KOKYAKU  " . "\r\n";
        $strSQL .= "            + KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_AB_KOKYAKU) SEIYAKU_AB_KOKYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_AB_SINTA) SEIYAKU_AB_SINTA_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_NONAB_KOKYAKU) SEIYAKU_NONAB_KOKYAKU_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_NONAB_SINTA_KEI " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_NONAB_FREE) SEIYAKU_NONAB_FREE_KEI " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,DECODE(MAX(KAKU.START_DATE),NULL,NULL,'0')," . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(KAKU.SEIYAKU_NONAB_KOKYAKU + KAKU.SEIYAKU_NONAB_SINTA) /  " . "\r\n";
        $strSQL .= "              SUM(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100 ,1))) SOKU_RITU " . "\r\n";
        $strSQL .= "FROM   HBUSYO BUS" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO TENPO" . "\r\n";
        $strSQL .= "ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD  " . "\r\n";
        $strSQL .= "AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL  " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUDATA KAKU" . "\r\n";
        $strSQL .= "ON     KAKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    KAKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    KAKU.KAKUTEI_FLG = '1'" . "\r\n";
        $strSQL .= "AND    KAKU.DATA_KB = '1'" . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " WHERE TENPO.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'TENPO');

        $strSQL .= "GROUP BY TENPO.BUSYO_CD" . "\r\n";
        $strSQL .= ",        TENPO.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ",        TENPO.IVENT_TENPO_DISP_NO" . "\r\n";
        $strSQL .= "ORDER BY TENPO.IVENT_TENPO_DISP_NO" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);
        $strSQL = str_replace("@ENDDT", str_replace("/", "", $postData['lblExhibitTermEnd']), $strSQL);

        return $strSQL;
    }

    public function SQL3($postData)
    {
        return parent::select($this->SQL3SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL3
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報入力合計データのSQL文を取得
           '**********************************************************************
           */
    public function SQL3SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "         SELECT SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU  " . "\r\n";
        $strSQL .= "            + KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU) RAIJYO_KUMI_AB_KOKYAKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_AB_SINTA)  RAIJYO_KUMI_AB_SINTA_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU) RAIJYO_KUMI_NONAB_KOKYAKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_NONAB_SINTA_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_KUMI_NONAB_FREE) RAIJYO_KUMI_NONAB_FREE_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_DM) JIZEN_JYUNBI_DM_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_DH) JIZEN_JYUNBI_DH_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_POSTING) JIZEN_JYUNBI_POSTING_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_TEL) JIZEN_JYUNBI_TEL_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.JIZEN_JYUNBI_KAKUYAKU) JIZEN_JYUNBI_KAKUYAKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_YOBIKOMI) RAIJYO_BUNSEKI_YOBIKOMI_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_KAKUYAKU) RAIJYO_BUNSEKI_KAKUYAKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_KOUKOKU) RAIJYO_BUNSEKI_KOUKOKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_MEDIA) RAIJYO_BUNSEKI_MEDIA_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_CHIRASHI) RAIJYO_BUNSEKI_CHIRASHI_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_TORIGAKARI) RAIJYO_BUNSEKI_TORIGAKARI_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_SYOKAI) RAIJYO_BUNSEKI_SYOKAI_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_WEB) RAIJYO_BUNSEKI_WEB_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.RAIJYO_BUNSEKI_SONOTA) RAIJYO_BUNSEKI_SONOTA_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ENQUETE_KAISYU) ENQUETE_KAISYU_KEI  " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,'0', " . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(KAKU.ENQUETE_KAISYU) / SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1))) ENQUETE_RITU " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_KOKYAKU) ABHOT_KOKYAKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_SINTA) ABHOT_SINTA_KEI  " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,'0'," . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(KAKU.ABHOT_KOKYAKU + KAKU.ABHOT_SINTA) / SUM(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU   " . "\r\n";
        $strSQL .= "                   + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100,1))) ABHOT_RITU  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.ABHOT_ZAN) ABHOT_ZAN_KEI  " . "\r\n";

        $strSQL .= " ,      SUM(KAKU.SATEI_KOKYAKU) SATEI_KOKYAKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SATEI_KOKYAKU_TA,0)) SATEI_KOKYAKU_TA_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SATEI_SINTA) SATEI_SINTA_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SATEI_SINTA_TA,0)) SATEI_SINTA_TA_KEI  " . "\r\n";

        $strSQL .= " ,      SUM(KAKU.DEMO_KENSU) DEMO_KENSU_KEI  " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,'0'," . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(KAKU.DEMO_KENSU) / SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA   " . "\r\n";
        $strSQL .= "               + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100,1))) DEMO_RITU  " . "\r\n";

        $strSQL .= " ,      SUM(NVL(KAKU.RUNCOST_KENSU,0)) RUNCOST_KENSU_KEI    " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SKYPLAN_KENSU,0)) SKYPLAN_KENSU_KEI    " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.RUNCOST_SEIYAKU_KENSU,0)) RUNCOST_SEIYAKU_KENSU_KEI    " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SKYPLAN_KEIYAKU_KENSU,0)) SKYPLAN_KEIYAKU_KENSU_KEI    " . "\r\n";

        $strSQL .= " ,      SUM(KAKU.SEIYAKU_AB_KOKYAKU + KAKU.SEIYAKU_AB_SINTA + KAKU.SEIYAKU_NONAB_KOKYAKU   " . "\r\n";
        $strSQL .= "            + KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_AB_KOKYAKU) SEIYAKU_AB_KOKYAKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_AB_SINTA) SEIYAKU_AB_SINTA_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_NONAB_KOKYAKU) SEIYAKU_NONAB_KOKYAKU_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_NONAB_SINTA_KEI  " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_NONAB_FREE) SEIYAKU_NONAB_FREE_KEI  " . "\r\n";
        $strSQL .= " ,      DECODE(SUM(NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,'0'," . "\r\n";
        $strSQL .= "        TO_CHAR(ROUND(SUM(KAKU.SEIYAKU_NONAB_KOKYAKU + KAKU.SEIYAKU_NONAB_SINTA) /   " . "\r\n";
        $strSQL .= "              SUM(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100,1))) SOKU_RITU  " . "\r\n";

        $strSQL .= "FROM   HBUSYO BUS   " . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO TENPO   " . "\r\n";
        $strSQL .= "ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD   " . "\r\n";
        $strSQL .= "AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL   " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUDATA KAKU" . "\r\n";
        $strSQL .= "ON     KAKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    KAKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    KAKU.KAKUTEI_FLG = '1'" . "\r\n";
        $strSQL .= "AND    KAKU.DATA_KB = '1'" . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " WHERE BUS.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'BUS');

        $strSQL .= "ORDER BY  TENPO.IVENT_TENPO_DISP_NO" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL4()
    {
        return parent::select($this->SQL4SQL());
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL4
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：車種データのSQL文を取得
           '**********************************************************************
           */
    public function SQL4SQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT SYA.SYASYU_NM " . "\r\n";
        $strSQL .= " ,SYASYU_CD " . "\r\n";
        $strSQL .= " FROM   HDTSYASYU SYA " . "\r\n";
        $strSQL .= " ORDER BY SYA.DISP_NO " . "\r\n";
        $strSQL .= " ,        SYA.SYASYU_CD" . "\r\n";

        return $strSQL;
    }

    public function SQL5($postData)
    {
        return parent::select($this->SQL5SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL5
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報車種データのSQL文を取得
           '**********************************************************************
           */
    public function SQL5SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "         SELECT TENPO.BUSYO_CD " . "\r\n";
        $strSQL .= " ,      SYU.SYASYU_CD " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_DAISU_D)　SEIYAKU_DAISU_D " . "\r\n";

        $strSQL .= "FROM   HDTSYASYU SYU" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS  " . "\r\n";
        $strSQL .= "ON     1 = 1" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO TENPO  " . "\r\n";
        $strSQL .= "ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD  " . "\r\n";
        $strSQL .= "AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL  " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU KAKU" . "\r\n";
        $strSQL .= "ON     KAKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    KAKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    KAKU.SYASYU_CD = SYU.SYASYU_CD" . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " WHERE TENPO.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'TENPO');

        $strSQL .= "GROUP BY TENPO.BUSYO_CD" . "\r\n";
        $strSQL .= ",        TENPO.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ",        TENPO.IVENT_TENPO_DISP_NO" . "\r\n";
        $strSQL .= ",        SYU.SYASYU_CD" . "\r\n";
        $strSQL .= ",        SYU.DISP_NO" . "\r\n";
        $strSQL .= "ORDER BY TENPO.IVENT_TENPO_DISP_NO" . "\r\n";
        $strSQL .= ",        TENPO.BUSYO_CD" . "\r\n";
        $strSQL .= ",        NVL(SYU.DISP_NO, 99)" . "\r\n";
        $strSQL .= ",        SYU.SYASYU_CD" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL6($postData)
    {
        return parent::select($this->SQL6SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL4
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：車種データのSQL文を取得
           '**********************************************************************
           */
    public function SQL6SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT SYU.SYASYU_CD " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_DAISU_D)　SEIYAKU_DAISU_KEI " . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYU" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS  " . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " ON BUS.BUSYO_CD not in ('443','463') " . "\r\n";
        } else {
            $strSQL .= "ON     1 = 1" . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'BUS');

        $strSQL .= "INNER JOIN HBUSYO TENPO  " . "\r\n";
        $strSQL .= "ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD  " . "\r\n";
        $strSQL .= "AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL  " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU KAKU" . "\r\n";
        $strSQL .= "ON     KAKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    KAKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    KAKU.SYASYU_CD = SYU.SYASYU_CD" . "\r\n";

        $strSQL .= "GROUP BY SYU.SYASYU_CD" . "\r\n";
        $strSQL .= ",        SYU.DISP_NO" . "\r\n";
        $strSQL .= "ORDER BY NVL(SYU.DISP_NO, 99)" . "\r\n";
        $strSQL .= ",        SYU.SYASYU_CD" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL7($postData)
    {
        return parent::select($this->SQL7SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL7
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報車種データのSQL文を取得
           '**********************************************************************
           */
    public function SQL7SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT TENPO.BUSYO_CD " . "\r\n";
        $strSQL .= " ,      SYU.SYASYU_CD " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_DAISU_D)　SEIYAKU_DAISU_D " . "\r\n";
        $strSQL .= " FROM   HSYAINMST SYA " . "\r\n";
        $strSQL .= " INNER JOIN HDTSYASYU SYU " . "\r\n";
        $strSQL .= " ON     1 = 1 " . "\r\n";
        $strSQL .= " INNER JOIN HHAIZOKU HAI " . "\r\n";
        $strSQL .= " ON     HAI.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= " AND    HAI.START_DATE <= '@STARTDT' " . "\r\n";
        $strSQL .= " AND    NVL(HAI.END_DATE,'99999999') >= '@STARTDT' " . "\r\n";
        $strSQL .= " INNER JOIN HBUSYO BUS " . "\r\n";
        $strSQL .= " ON     BUS.BUSYO_CD = HAI.BUSYO_CD " . "\r\n";
        $strSQL .= " INNER JOIN HBUSYO TENPO " . "\r\n";
        $strSQL .= " ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD " . "\r\n";
        $strSQL .= " AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL " . "\r\n";

        $strSQL .= " LEFT JOIN HDTKAKUHOUSYASYU KAKU " . "\r\n";
        $strSQL .= " ON     KAKU.START_DATE = '@STARTDT' " . "\r\n";
        $strSQL .= " AND    KAKU.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSQL .= " AND    KAKU.SYASYU_CD = SYU.SYASYU_CD " . "\r\n";
        $strSQL .= " AND    NOT EXISTS (SELECT * FROM HDTWORKMANAGE WKMN " . "\r\n";
        $strSQL .= " WHERE WKMN.SYAIN_NO = KAKU.SYAIN_NO " . "\r\n";
        $strSQL .= " AND KAKU.IVENT_DATE = WKMN.IVENT_DATE) " . "\r\n";
        $strSQL .= " WHERE  (NVL(HAI.SYOKUSYU_KB,'A') <> '9' " . "\r\n";

        $strSQL .= " AND    NVL(SYA.TAISYOKU_DATE,'99999999') > '@STARTDT' " . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " AND TENPO.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'TENPO');

        $strSQL .= " GROUP BY TENPO.BUSYO_CD " . "\r\n";
        $strSQL .= " ,        TENPO.BUSYO_RYKNM " . "\r\n";
        $strSQL .= " ,        TENPO.IVENT_TENPO_DISP_NO " . "\r\n";
        $strSQL .= " ,        SYU.SYASYU_CD " . "\r\n";
        $strSQL .= " ,        SYU.DISP_NO " . "\r\n";
        $strSQL .= " ORDER BY TENPO.IVENT_TENPO_DISP_NO " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL8($postData)
    {
        return parent::select($this->SQL8SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL8
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報入力計画データのSQL文を取得
           '**********************************************************************
           */
    public function SQL8SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT KAKU.IVENT_DATE " . "\r\n";
        $strSQL .= "  ,      KAKU.BUSYO_CD " . "\r\n";
        $strSQL .= "  ,      BUS.BUSYO_NM " . "\r\n";
        $strSQL .= "  ,      KAKU.SYAIN_NO " . "\r\n";
        $strSQL .= "  ,      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= "  ,      LENGTHB(KAKU.IVENT_DATE || KAKU.BUSYO_CD || KAKU.SYAIN_NO) KEYCNT" . "\r\n";
        $strSQL .= "  ,      KAKU.DATA_KB " . "\r\n";
        $strSQL .= " ,      (KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= "         + KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_KEI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_AB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_AB_SINTA " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_NONAB_SINTA " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_NONAB_FREE " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_DM " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_DH " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_POSTING " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_TEL " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_KAKUYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_YOBIKOMI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_KAKUYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_KOUKOKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_MEDIA " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_CHIRASHI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_TORIGAKARI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_SYOKAI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_WEB " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_SONOTA " . "\r\n";
        $strSQL .= " ,      KAKU.ENQUETE_KAISYU " . "\r\n";
        $strSQL .= " ,      DECODE((NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,0, " . "\r\n";
        $strSQL .= "        ROUND(KAKU.ENQUETE_KAISYU / (NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1)) ENQUETE_RITU " . "\r\n";
        $strSQL .= " ,      KAKU.ABHOT_KOKYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.ABHOT_SINTA " . "\r\n";
        $strSQL .= ",       DECODE(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA,0,0" . "\r\n";
        $strSQL .= " ,      ROUND((KAKU.ABHOT_KOKYAKU + KAKU.ABHOT_SINTA) / (KAKU.RAIJYO_KUMI_NONAB_KOKYAKU  " . "\r\n";
        $strSQL .= "              + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100,1)) ABHOT_RITU " . "\r\n";
        $strSQL .= " ,      KAKU.ABHOT_ZAN " . "\r\n";

        $strSQL .= " ,      KAKU.SATEI_KOKYAKU " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.SATEI_KOKYAKU_TA,0) SATEI_KOKYAKU_TA  " . "\r\n";
        $strSQL .= " ,      KAKU.SATEI_SINTA " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.SATEI_SINTA_TA,0) SATEI_SINTA_TA  " . "\r\n";

        $strSQL .= " ,      KAKU.DEMO_KENSU " . "\r\n";
        $strSQL .= ",       DECODE(KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA,0,0" . "\r\n";
        $strSQL .= " ,      ROUND(KAKU.DEMO_KENSU / (KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA  " . "\r\n";
        $strSQL .= "             + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100,1)) DEMO_RITU " . "\r\n";

        $strSQL .= " ,      NVL(KAKU.RUNCOST_KENSU,0) RUNCOST_KENSU  " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.SKYPLAN_KENSU,0) SKYPLAN_KENSU  " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.RUNCOST_SEIYAKU_KENSU,0) RUNCOST_SEIYAKU_KENSU  " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.SKYPLAN_KEIYAKU_KENSU,0) SKYPLAN_KEIYAKU_KENSU  " . "\r\n";

        $strSQL .= " ,      (KAKU.SEIYAKU_AB_KOKYAKU + KAKU.SEIYAKU_AB_SINTA + KAKU.SEIYAKU_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= "         + KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_KEI " . "\r\n";
        $strSQL .= " ,      KAKU.SEIYAKU_AB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.SEIYAKU_AB_SINTA " . "\r\n";
        $strSQL .= " ,      KAKU.SEIYAKU_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.SEIYAKU_NONAB_SINTA " . "\r\n";
        $strSQL .= " ,      KAKU.SEIYAKU_NONAB_FREE " . "\r\n";
        $strSQL .= " ,      DECODE((KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA),0,0" . "\r\n";
        $strSQL .= " ,      ROUND((KAKU.SEIYAKU_NONAB_KOKYAKU + KAKU.SEIYAKU_NONAB_SINTA) /  " . "\r\n";
        $strSQL .= "             (KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100,1)) SOKU_RITU " . "\r\n";
        $strSQL .= " FROM   HDTKAKUHOUDATA KAKU " . "\r\n";
        $strSQL .= " LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= " ON        SYA.SYAIN_NO = KAKU.SYAIN_NO" . "\r\n";
        $strSQL .= " LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= " ON        BUS.BUSYO_CD = KAKU.BUSYO_CD" . "\r\n";
        $strSQL .= " WHERE  KAKU.START_DATE = '@STARTDT' " . "\r\n";
        $strSQL .= " AND    KAKU.DATA_KB = '0'" . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " AND KAKU.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'KAKU');

        $strSQL = str_replace("KAKU.BUSYO_RYKNM", "BUS.BUSYO_RYKNM", $strSQL);

        $strSQL .= " ORDER BY IVENT_DATE" . "\r\n";
        $strSQL .= " ,      BUSYO_CD" . "\r\n";
        $strSQL .= " ,      SYAIN_NO" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL9($postData)
    {
        return parent::select($this->SQL9SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL9
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報入力実績データのSQL文を取得
           '**********************************************************************
           */
    public function SQL9SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT KAKU.IVENT_DATE " . "\r\n";
        $strSQL .= " ,      KAKU.BUSYO_CD " . "\r\n";
        $strSQL .= " ,      KAKU.SYAIN_NO " . "\r\n";
        $strSQL .= "  ,      LENGTHB(KAKU.IVENT_DATE || KAKU.BUSYO_CD || KAKU.SYAIN_NO) KEYCNT" . "\r\n";
        $strSQL .= " ,      KAKU.DATA_KB " . "\r\n";
        $strSQL .= " ,      (KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= "         + KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_KEI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_AB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_AB_SINTA " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_NONAB_SINTA " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_KUMI_NONAB_FREE " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_DM " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_DH " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_POSTING " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_TEL " . "\r\n";
        $strSQL .= " ,      KAKU.JIZEN_JYUNBI_KAKUYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_YOBIKOMI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_KAKUYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_KOUKOKU " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_MEDIA " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_CHIRASHI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_TORIGAKARI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_SYOKAI " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_WEB " . "\r\n";
        $strSQL .= " ,      KAKU.RAIJYO_BUNSEKI_SONOTA " . "\r\n";
        $strSQL .= " ,      KAKU.ENQUETE_KAISYU " . "\r\n";
        $strSQL .= " ,      DECODE((NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)),0,0, " . "\r\n";
        $strSQL .= "        ROUND(KAKU.ENQUETE_KAISYU / (NVL(KAKU.RAIJYO_KUMI_AB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_AB_SINTA,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU,0) + NVL(KAKU.RAIJYO_KUMI_NONAB_SINTA,0)) * 100,1)) ENQUETE_RITU " . "\r\n";
        $strSQL .= " ,      KAKU.ABHOT_KOKYAKU " . "\r\n";
        $strSQL .= " ,      KAKU.ABHOT_SINTA " . "\r\n";
        $strSQL .= " ,      DECODE(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA,0,0" . "\r\n";
        $strSQL .= " ,      ROUND((KAKU.ABHOT_KOKYAKU + KAKU.ABHOT_SINTA) / (KAKU.RAIJYO_KUMI_NONAB_KOKYAKU  " . "\r\n";
        $strSQL .= "               + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100,1)) ABHOT_RITU " . "\r\n";
        $strSQL .= " ,      KAKU.ABHOT_ZAN " . "\r\n";

        $strSQL .= " ,      KAKU.SATEI_KOKYAKU " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.SATEI_KOKYAKU_TA,0) SATEI_KOKYAKU_TA  " . "\r\n";
        $strSQL .= " ,      KAKU.SATEI_SINTA " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.SATEI_SINTA_TA,0) SATEI_SINTA_TA" . "\r\n";

        $strSQL .= " ,      KAKU.DEMO_KENSU " . "\r\n";
        $strSQL .= " ,      DECODE(KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA,0,0" . "\r\n";
        $strSQL .= " ,      ROUND(KAKU.DEMO_KENSU / (KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA  " . "\r\n";
        $strSQL .= "              + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA) * 100,1)) DEMO_RITU " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.RUNCOST_KENSU,0) RUNCOST_KENSU " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.SKYPLAN_KENSU,0) SKYPLAN_KENSU " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.RUNCOST_SEIYAKU_KENSU,0) RUNCOST_SEIYAKU_KENSU " . "\r\n";
        $strSQL .= " ,      NVL(KAKU.SKYPLAN_KEIYAKU_KENSU,0) SKYPLAN_KEIYAKU_KENSU " . "\r\n";

        $strSQL .= " ,      (KAKU.SEIYAKU_AB_KOKYAKU + KAKU.SEIYAKU_AB_SINTA + KAKU.SEIYAKU_NONAB_KOKYAKU   " . "\r\n";
        $strSQL .= "           + KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_KEI     " . "\r\n";
        $strSQL .= "   ,      KAKU.SEIYAKU_AB_KOKYAKU    " . "\r\n";
        $strSQL .= "   ,      KAKU.SEIYAKU_AB_SINTA    " . "\r\n";
        $strSQL .= "   ,      KAKU.SEIYAKU_NONAB_KOKYAKU    " . "\r\n";
        $strSQL .= "   ,      KAKU.SEIYAKU_NONAB_SINTA    " . "\r\n";
        $strSQL .= "   ,      KAKU.SEIYAKU_NONAB_FREE    " . "\r\n";
        $strSQL .= " ,        DECODE(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA,0,0" . "\r\n";
        $strSQL .= "   ,      ROUND((KAKU.SEIYAKU_NONAB_KOKYAKU + KAKU.SEIYAKU_NONAB_SINTA) /     " . "\r\n";
        $strSQL .= "                (KAKU.RAIJYO_KUMI_NONAB_KOKYAKU + KAKU.RAIJYO_KUMI_NONAB_SINTA),1)) SOKU_RITU    " . "\r\n";
        $strSQL .= "    FROM   HDTKAKUHOUDATA KAKU   " . "\r\n";
        $strSQL .= " LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= " ON        BUS.BUSYO_CD = KAKU.BUSYO_CD" . "\r\n";
        $strSQL .= "    WHERE  KAKU.START_DATE = '@STARTDT'   " . "\r\n";
        $strSQL .= "    AND    KAKU.DATA_KB = '1'   " . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " AND KAKU.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'KAKU');

        $strSQL = str_replace("KAKU.BUSYO_RYKNM", "BUS.BUSYO_RYKNM", $strSQL);

        $strSQL .= "    ORDER BY IVENT_DATE   " . "\r\n";
        $strSQL .= "    ,      BUSYO_CD   " . "\r\n";
        $strSQL .= "    ,      SYAIN_NO   " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL10($postData)
    {
        return parent::select($this->SQL10SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL10
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報確定データのSQL文を取得
           '**********************************************************************
           */
    public function SQL10SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT START_DATE " . "\r\n";
        $strSQL .= " FROM   HDTKAKUHOUKAKUTEI " . "\r\n";
        $strSQL .= " WHERE  START_DATE = '@IVENTDT' " . "\r\n";

        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL11($postData)
    {
        return parent::insert($this->SQL11SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL11
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：更新処理のSQL文を取得
           '**********************************************************************
           */
    public function SQL11SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTKAKUHOUKAKUTEI " . "\r\n";
        $strSQL .= " (      START_DATE " . "\r\n";
        $strSQL .= " ,      KAKUTEI_FLG " . "\r\n";
        $strSQL .= " ,      UPD_DATE " . "\r\n";
        $strSQL .= " ,      CREATE_DATE " . "\r\n";
        $strSQL .= " ,      UPD_SYA_CD " . "\r\n";
        $strSQL .= " ,      UPD_PRG_ID " . "\r\n";
        $strSQL .= " ,      UPD_CLT_NM " . "\r\n";
        $strSQL .= " ) " . "\r\n";
        $strSQL .= " VALUES ('@STARTDT' " . "\r\n";
        $strSQL .= " ,       '1' " . "\r\n";
        $strSQL .= " ,       SYSDATE " . "\r\n";
        $strSQL .= " ,       SYSDATE " . "\r\n";
        $strSQL .= ",   '@LoginID'     " . "\r\n";
        $strSQL .= " ,  'Total_K_Honbu' " . "\r\n";
        $strSQL .= " ,   '@MachineNM '  " . "\r\n";
        $strSQL .= " ) " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);
        $strSQL = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return $strSQL;
    }

    public function SQL12($postData)
    {
        return parent::update($this->SQL12SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL12
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：更新処理
           '**********************************************************************
           */
    public function SQL12SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HDTKAKUHOUKAKUTEI " . "\r\n";
        $strSQL .= " SET    KAKUTEI_FLG = '1' " . "\r\n";
        $strSQL .= " WHERE  START_DATE = '@STARTDT' " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL13($postData)
    {
        return parent::update($this->SQL13SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL13
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：速報データの出力ﾌﾗｸﾞを"1"で更新する
           '**********************************************************************
           */
    public function SQL13SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HDTKAKUHOUDATA " . "\r\n";
        $strSQL .= " SET    OUT_FLG = '1' " . "\r\n";
        $strSQL .= " WHERE  START_DATE = '@STARTDT' " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL14($postData)
    {
        return parent::update($this->SQL14SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL14
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報確定データの更新処理を行う
           '**********************************************************************
           */
    public function SQL14SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HDTKAKUHOUKAKUTEI " . "\r\n";
        $strSQL .= " SET    KAKUTEI_FLG = '0' " . "\r\n";
        $strSQL .= " WHERE  START_DATE >= '@STARTDT' " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL15($postData)
    {
        return parent::select($this->SQL15SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL15
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：未出力データのSQL文を取得
           '**********************************************************************
           */
    public function SQL15SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT COUNT(START_DATE) CNT " . "\r\n";
        $strSQL .= " FROM   HDTKAKUHOUDATA " . "\r\n";
        $strSQL .= " WHERE  OUT_FLG = '0' " . "\r\n";
        $strSQL .= " AND    START_DATE = '@STARTDT' " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL16($postData)
    {
        return parent::select($this->SQL16SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL16
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：展示会データのSQL文を取得
           '**********************************************************************
           */
    public function SQL16SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT IVENT_NM " . "\r\n";
        $strSQL .= " FROM   HDTIVENTDATA " . "\r\n";
        $strSQL .= " WHERE  START_DATE = '@STARTDT'" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL18($postData)
    {
        return parent::select($this->SQL18SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL18
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報入力実績データのSQL文を取得
           '**********************************************************************
           */
    public function SQL18SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU + KAKU.RAIJYO_KUMI_AB_SINTA + KAKU.RAIJYO_KUMI_NONAB_KOKYAKU" . "\r\n";
        $strSQL .= "          + KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU) + SUM(KAKU.RAIJYO_KUMI_AB_SINTA) RAIJYO_KUMI_AB_GK" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_KUMI_AB_KOKYAKU) RAIJYO_KUMI_AB_KOKYAKU_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_KUMI_AB_SINTA)  RAIJYO_KUMI_AB_SINTA_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU) + SUM(KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_NONAB_GK" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_KUMI_NONAB_KOKYAKU) RAIJYO_KUMI_NONAB_KOKYAKU_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_KUMI_NONAB_SINTA) RAIJYO_KUMI_NONAB_SINTA_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_KUMI_NONAB_FREE) RAIJYO_KUMI_NONAB_FREE_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.ABHOT_KOKYAKU) + SUM(KAKU.ABHOT_SINTA) ABHOT_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SEIYAKU_AB_KOKYAKU + KAKU.SEIYAKU_AB_SINTA + KAKU.SEIYAKU_NONAB_KOKYAKU" . "\r\n";
        $strSQL .= "           + KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SEIYAKU_AB_KOKYAKU) +  SUM(KAKU.SEIYAKU_AB_SINTA) SEIYAKU_AB_GK" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SEIYAKU_AB_KOKYAKU) SEIYAKU_AB_KOKYAKU_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SEIYAKU_AB_SINTA) SEIYAKU_AB_SINTA_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SEIYAKU_NONAB_KOKYAKU) + SUM(KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_NONAB_GK" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SEIYAKU_NONAB_KOKYAKU) SEIYAKU_NONAB_KOKYAKU_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SEIYAKU_NONAB_SINTA) SEIYAKU_NONAB_SINTA_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SEIYAKU_NONAB_FREE) SEIYAKU_NONAB_FREE_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.ABHOT_ZAN) ABHOT_ZAN_KEI" . "\r\n";

        $strSQL .= ",      SUM(KAKU.JIZEN_JYUNBI_DM) JIZEN_JYUNBI_DM_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.JIZEN_JYUNBI_TEL) JIZEN_JYUNBI_TEL_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.JIZEN_JYUNBI_KAKUYAKU) JIZEN_JYUNBI_KAKUYAKU_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_BUNSEKI_KAKUYAKU) RAIJYO_BUNSEKI_KAKUYAKU_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.JIZEN_JYUNBI_DH) JIZEN_JYUNBI_DH_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.ENQUETE_KAISYU) ENQUETE_KAISYU_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SATEI_KOKYAKU) + SUM(NVL(KAKU.SATEI_KOKYAKU_TA,0)) + SUM(KAKU.SATEI_SINTA) + SUM(NVL(KAKU.SATEI_SINTA_TA,0)) SATEI_GK" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SATEI_KOKYAKU) SATEI_KOKYAKU_KEI" . "\r\n";
        $strSQL .= ",      SUM(NVL(KAKU.SATEI_KOKYAKU_TA,0)) SATEI_KOKYAKU_TA_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.SATEI_SINTA) SATEI_SINTA_KEI" . "\r\n";
        $strSQL .= ",      SUM(NVL(KAKU.SATEI_SINTA_TA,0)) SATEI_SINTA_TA_KEI" . "\r\n";

        $strSQL .= ",      SUM(KAKU.RAIJYO_BUNSEKI_MEDIA) RAIJYO_BUNSEKI_MEDIA_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_BUNSEKI_KOUKOKU) RAIJYO_BUNSEKI_KOUKOKU_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_BUNSEKI_CHIRASHI) RAIJYO_BUNSEKI_CHIRASHI_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_BUNSEKI_YOBIKOMI) RAIJYO_BUNSEKI_YOBIKOMI_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_BUNSEKI_TORIGAKARI) RAIJYO_BUNSEKI_TORIGAKARI_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_BUNSEKI_WEB) RAIJYO_BUNSEKI_WEB_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.RAIJYO_BUNSEKI_SONOTA) + SUM(KAKU.RAIJYO_BUNSEKI_SYOKAI) RAIJYO_BUNSEKI_SONOTA_KEI" . "\r\n";
        $strSQL .= ",      SUM(KAKU.DEMO_KENSU) DEMO_KENSU_KEI" . "\r\n";
        $strSQL .= ",      SUM(NVL(KAKU.RUNCOST_KENSU,0)) RUNCOST_KENSU_KEI" . "\r\n";
        $strSQL .= ",      SUM(NVL(KAKU.SKYPLAN_KENSU,0)) SKYPLAN_KENSU_KEI" . "\r\n";

        $strSQL .= ",      SUM(NVL(KAKU.RUNCOST_SEIYAKU_KENSU,0)) RUNCOST_SEIYAKU_KENSU_KEI" . "\r\n";
        $strSQL .= ",      SUM(NVL(KAKU.SKYPLAN_KEIYAKU_KENSU,0)) SKYPLAN_KEIYAKU_KENSU_KEI" . "\r\n";

        $strSQL .= "FROM   HBUSYO BUS" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO TENPO" . "\r\n";
        $strSQL .= "ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD" . "\r\n";
        $strSQL .= "AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " AND TENPO.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'TENPO');

        $strSQL .= "LEFT JOIN HDTKAKUHOUDATA KAKU" . "\r\n";
        $strSQL .= "ON     KAKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    KAKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    KAKU.KAKUTEI_FLG = '1'" . "\r\n";
        $strSQL .= "AND    KAKU.DATA_KB = '1'" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL19($postData, $SYASYU_KB)
    {
        return parent::select($this->SQL19SQL($postData, $SYASYU_KB));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL19
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：車種区分別成約台数データ取得
           '**********************************************************************
           */
    public function SQL19SQL($postData, $SYASYU_KB)
    {
        $strSQL = "";
        $strSQL .= "   SELECT SYU.SYASYU_KB " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SEIYAKU_DAISU_P,0)) KEIKAKU " . "\r\n";
        $strSQL .= " ,      SUM(NVL(KAKU.SEIYAKU_DAISU_D,0)) JISSEKI " . "\r\n";

        $strSQL .= "FROM   HDTSYASYU SYU" . "\r\n";
        $strSQL .= " INNER JOIN HBUSYO BUS " . "\r\n";
        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " ON BUS.BUSYO_CD not in ('443','463') " . "\r\n";
        } else {
            $strSQL .= "ON     1 = 1" . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'BUS');

        $strSQL .= "INNER JOIN HBUSYO TENPO" . "\r\n";
        $strSQL .= "ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD" . "\r\n";
        $strSQL .= "AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " AND TENPO.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'TENPO');

        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU KAKU" . "\r\n";
        $strSQL .= "ON     KAKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    KAKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    KAKU.SYASYU_CD = SYU.SYASYU_CD" . "\r\n";
        $strSQL .= "WHERE  SYU.SYASYU_KB ='@SYASYU_KB'" . "\r\n";

        $strSQL .= "GROUP BY SYU.SYASYU_KB" . "\r\n";
        $strSQL .= "ORDER BY SYU.SYASYU_KB" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);
        $strSQL = str_replace("@SYASYU_KB", $SYASYU_KB, $strSQL);

        return $strSQL;
    }

    public function SQL20($postData)
    {
        return parent::select($this->SQL20SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL20SQL
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：デモ件数データ取得
           '**********************************************************************
           */
    public function SQL20SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "      Select SYU.SYASYU_NM " . "\r\n";
        $strSQL .= " ,      (SELECT SUM(K_S.SIJYO_DAISU) FROM HDTKAKUHOUSYASYU K_S " . "\r\n";
        $strSQL .= " 　　　  WHERE  K_S.START_DATE = '@STARTDT') DEMO_GK " . "\r\n";
        $strSQL .= " 　　　  ,      SUM(KAKU.SIJYO_DAISU)　DEMO_DAISU_KEI " . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYU" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS " . "\r\n";
        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " ON BUS.BUSYO_CD not in ('443','463') " . "\r\n";
        } else {
            $strSQL .= "ON     1 = 1" . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'BUS');

        $strSQL .= "INNER JOIN HBUSYO TENPO" . "\r\n";
        $strSQL .= "ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD" . "\r\n";
        $strSQL .= "AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " AND TENPO.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'TENPO');

        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU KAKU" . "\r\n";
        $strSQL .= "ON     KAKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    KAKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    KAKU.SYASYU_CD = SYU.SYASYU_CD" . "\r\n";
        $strSQL .= "WHERE  SYU.KAKU_DEMO_OUT_FLG = '1'" . "\r\n";

        $strSQL .= "GROUP BY SYU.SYASYU_NM" . "\r\n";
        $strSQL .= ",        SYU.DISP_NO" . "\r\n";
        $strSQL .= "ORDER BY NVL(SYU.DISP_NO, 99)" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL21($postData)
    {
        return parent::select($this->SQL21SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL21
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：成約台数内訳データ取得
           '**********************************************************************
           */
    public function SQL21SQL($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT SYU.SYASYU_NM " . "\r\n";
        $strSQL .= " ,      SUM(KAKU.SEIYAKU_DAISU_D)　SEIYAKU_DAISU_KEI " . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYU" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO BUS " . "\r\n";
        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " ON BUS.BUSYO_CD not in ('443','463') " . "\r\n";
        } else {
            $strSQL .= "ON     1 = 1" . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'BUS');

        $strSQL .= "INNER JOIN HBUSYO TENPO" . "\r\n";
        $strSQL .= "ON     TENPO.BUSYO_CD = BUS.HDT_TENPO_CD " . "\r\n";
        $strSQL .= "AND    TENPO.IVENT_TENPO_DISP_NO IS NOT NULL " . "\r\n";
        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " AND TENPO.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'TENPO');

        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU KAKU" . "\r\n";
        $strSQL .= "ON     KAKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    KAKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    KAKU.SYASYU_CD = SYU.SYASYU_CD" . "\r\n";
        $strSQL .= "WHERE  SYU.SYASYU_KB IN ('0','1')" . "\r\n";
        $strSQL .= "GROUP BY SYU.SYASYU_NM" . "\r\n";
        $strSQL .= ",        SYU.DISP_NO" . "\r\n";
        $strSQL .= "ORDER BY NVL(SYU.DISP_NO, 99)" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function fncSyasyuKeikaku($postData)
    {
        return parent::select($this->fncSyasyuKeikakuSQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：fncSyasyuKeikakuSQL
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報車種計画データ取得(ＣＳＶ出力用)
           '**********************************************************************
           */
    public function fncSyasyuKeikakuSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYA.IVENT_DATE" . "\r\n";
        $strSQL .= ",      SYA.BUSYO_CD" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NO" . "\r\n";
        $strSQL .= ",      SYU.SYASYU_CD" . "\r\n";
        $strSQL .= ",      SYU.DISP_NO" . "\r\n";
        $strSQL .= ",      M_SYASYU.SEIYAKU_DAISU_P SEIYAKU" . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYU" . "\r\n";
        $strSQL .= "INNER JOIN (SELECT SYU.SYAIN_NO" . "\r\n";
        $strSQL .= "            ,      SYU.BUSYO_CD" . "\r\n";
        $strSQL .= "            ,      SYU.IVENT_DATE" . "\r\n";
        $strSQL .= "            ,      TENPO.BUSYO_RYKNM" . "\r\n";
        $strSQL .= "            FROM   HDTKAKUHOUDATA SYU" . "\r\n";
        $strSQL .= "            INNER JOIN HBUSYO TENPO" . "\r\n";
        $strSQL .= "            ON     TENPO.BUSYO_CD = SYU.BUSYO_CD" . "\r\n";
        $strSQL .= "            WHERE  SYU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "            AND    SYU.DATA_KB = '0') SYA" . "\r\n";
        $strSQL .= "ON     1 = 1" . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU M_SYASYU" . "\r\n";
        $strSQL .= "ON     M_SYASYU.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    M_SYASYU.SYASYU_CD = SYU.SYASYU_CD" . "\r\n";
        $strSQL .= "AND    M_SYASYU.IVENT_DATE = SYA.IVENT_DATE" . "\r\n";
        $strSQL .= "AND    M_SYASYU.START_DATE = '@STARTDT'" . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " WHERE SYA.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'SYA');

        $strSQL .= "ORDER BY SYA.IVENT_DATE" . "\r\n";
        $strSQL .= ",        SYA.BUSYO_CD" . "\r\n";
        $strSQL .= ",        SYA.SYAIN_NO" . "\r\n";
        $strSQL .= ",        SYU.DISP_NO" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function fncSyasyuJisseki($postData)
    {
        return parent::select($this->fncSyasyuJissekiSQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：fncSyasyuKeikakuSQL
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：確報車種計画データ取得(ＣＳＶ出力用)
           '**********************************************************************
           */
    public function fncSyasyuJissekiSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYA.IVENT_DATE" . "\r\n";
        $strSQL .= ",      SYA.BUSYO_CD" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NO" . "\r\n";
        $strSQL .= ",      '1'" . "\r\n";
        $strSQL .= ",      SYU.SYASYU_CD" . "\r\n";
        $strSQL .= ",      SYU.DISP_NO" . "\r\n";
        $strSQL .= ",      M_SYASYU.SEIYAKU_DAISU_D" . "\r\n";
        $strSQL .= ",      M_SYASYU.SIJYO_DAISU" . "\r\n";
        $strSQL .= ",      M_SYASYU.RAIJYO_DAISU" . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYU" . "\r\n";
        $strSQL .= "INNER JOIN (SELECT SYU.SYAIN_NO" . "\r\n";
        $strSQL .= "            ,      SYU.BUSYO_CD" . "\r\n";
        $strSQL .= "            ,      SYU.IVENT_DATE" . "\r\n";
        $strSQL .= "            ,      TENPO.BUSYO_RYKNM" . "\r\n";
        $strSQL .= "            FROM   HDTKAKUHOUDATA SYU" . "\r\n";
        $strSQL .= "            INNER JOIN HBUSYO TENPO" . "\r\n";
        $strSQL .= "            ON     TENPO.BUSYO_CD = SYU.BUSYO_CD" . "\r\n";
        $strSQL .= "            WHERE  SYU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "            AND    SYU.DATA_KB = '1') SYA" . "\r\n";
        $strSQL .= "ON     1 = 1" . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU M_SYASYU" . "\r\n";
        $strSQL .= "ON     M_SYASYU.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    M_SYASYU.SYASYU_CD = SYU.SYASYU_CD" . "\r\n";
        $strSQL .= "AND    M_SYASYU.IVENT_DATE = SYA.IVENT_DATE" . "\r\n";
        $strSQL .= "AND    M_SYASYU.START_DATE = '@STARTDT'" . "\r\n";

        if (str_replace("/", "", $postData['lblExhibitTermStart']) >= "20141001") {
            $strSQL .= " WHERE SYA.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData['lblExhibitTermStart']), 'SYA');

        $strSQL .= "ORDER BY SYA.IVENT_DATE" . "\r\n";
        $strSQL .= ",        SYA.BUSYO_CD" . "\r\n";
        $strSQL .= ",        SYA.SYAIN_NO" . "\r\n";
        $strSQL .= ",        SYU.DISP_NO" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function Lock($postData)
    {
        return parent::update($this->LockSQL($postData));
    }

    public function LockSQL($postData)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HDTKAKUHOUKAKUTEI " . "\r\n";
        $strSQL .= " SET    KAKUTEI_FLG = '0' " . "\r\n";
        $strSQL .= " WHERE  KAKUTEI_FLG = 1 " . "\r\n";
        $strSQL .= " AND    START_DATE = '@STARTDT' " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

    public function SQL24($postData)
    {
        return parent::select($this->SQL24SQL($postData));
    }

    /*
           ***********************************************************************
           '処 理 名：SQL生成
           '関 数 名：SQL24
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：展示会開催期間(From)より展示会終了期間SQL文取得
           '**********************************************************************
           */
    public function SQL24SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "  Select  END_DATE " . "\r\n";
        $strSQL .= "  FROM HDTIVENTDATA " . "\r\n";
        $strSQL .= "  WHERE  START_DATE = '@STARTDT' " . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData['lblExhibitTermStart']), $strSQL);

        return $strSQL;
    }

}
