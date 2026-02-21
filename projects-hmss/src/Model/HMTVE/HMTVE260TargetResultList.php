<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240326    		受入検証.xlsx NO2     					車種を追加してください             		 LHB caina
 * 20240611    		202406_データ集計システム_CX-80追加        CX-80追加            		 		 LHB
 * 20240712    		CX-80追加判断                           CX-80追加判断            		 	     LHB
 * 20240909    		20240909_error.log                           20240909_error.log            	   LHB
 * 20251118         202511_データ集計システム_機能追加要望   目標と実績＿改修イメージ                YIN
 * 20251224           修正依頼                一覧画面では 法人１G、法人２Ｇを合計して１行に表示     YIN
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
use Cake\Routing\Router;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE260TargetResultList extends ClsComDb
{
    public $SessionComponent;
    public function getShopName()
    {
        return parent::select($this->getShopNameSQL());
    }


    // ***********************************************************************
    // '処 理 名：SQL生成
    // '関 数 名：searchDate
    // '引    数：無し
    // '戻 り 値 ：無し
    // '処理説明 ：展示会開催期間に初期値
    // '**********************************************************************

    public function getShopNameSQL()
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD ,MST.BUSYO_RYKNM " . "\r\n";
        $strSQL .= "	FROM HBUSYO MST	INNER JOIN (SELECT BUSYO_CD ,(CASE WHEN HDT_TENPO_CD IS NOT NULL THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO FROM HBUSYO) BUS " . "\r\n";
        $strSQL .= "ON MST.BUSYO_CD = BUS.V_TENPO " . "\r\n";
        $strSQL .= "WHERE MST.HDT_TENPO_DISP_NO IS NOT NULL " . "\r\n";
        $strSQL .= "AND BUS.BUSYO_CD = '@BUSYOCD'" . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        return $strSQL;
    }
    // 20251222 caina ins s
    /**
     * 年月条件に応じて部署の抽出条件を SQL に追加する
     */
    function appendBusyoCondition(string $strSQL, array $postData): string
    {
        if ($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) >= "201410") {
            // 443：大州販２、463：宇品販２
            $strSQL .= " AND  MST.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        if ($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) >= "201605") {
            // 471：中広・販
            $strSQL .= " AND  MST.BUSYO_CD not in ('471') " . "\r\n";
        }

        if ($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) >= "201704") {
            // 271：ボルボ西販
            $strSQL .= " AND  MST.BUSYO_CD not in ('271') " . "\r\n";
        }

        if ($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) >= "202209") {
            // ・2022/9 以降、黒瀬店 を非表示に
            $strSQL .= " AND  MST.BUSYO_CD NOT IN ('550') " . "\r\n";
        }

        if ($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) >= "202512") {
            // 290（ボルボ大州）、291（ボルボ販）、241（ｶｰｴｰｽ比販）、240（ｶｰｴｰｽ比）を 2025/12以降は非表示
            $strSQL .= " AND  MST.BUSYO_RYKNM NOT LIKE '%ボルボ%' " . "\r\n";
            $strSQL .= " AND  MST.BUSYO_RYKNM NOT LIKE '%カーエース%' " . "\r\n";
            $strSQL .= " AND  MST.BUSYO_RYKNM NOT LIKE '%ﾎﾞﾙﾎﾞ%' " . "\r\n";
            $strSQL .= " AND  MST.BUSYO_RYKNM NOT LIKE '%ｶｰｴｰｽ%' " . "\r\n";
            // ・2025/12以降、新車特約を非表示
            $strSQL .= " AND  MST.BUSYO_CD NOT IN ('191') " . "\r\n";
        }

        if ($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) < "202512") {
            // ・224：大州UC
            // ・261：カーセブンを追加
            // ・新たに表示する部署に中古拡販Grを表示（中古拡販：表示　部署231）
            $strSQL .= " AND  MST.BUSYO_CD not in ('224','261','231') " . "\r\n";
        }

        if ($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) < "202601") {
            // 2025/12 までは 181（法人営業１） のみ
            // 2026/01 からは 181（法人営業１），183（法人営業２） が表示されるようにしたいです
            $strSQL .= " AND  MST.BUSYO_CD NOT IN ('183') " . "\r\n";
        }

        return $strSQL;
    }
    // 20251222 caina ins e

    public function SQL1($postData)
    {
        return parent::select($this->SQL1SQL($postData));
    }


    //  ***********************************************************************
    //  '処 理 名：目標と実績分類明細データを取得するSQL作成
    //  '関 数 名：SQL1
    //  '引    数：無し
    //  '戻 り 値 ：目標と実績分類明細データを取得するSQL
    //  '処理説明 ：目標と実績分類明細データを取得するSQLを作成する
    //  '**********************************************************************

    public function SQL1SQL($postData)
    {

        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD , TR.BUSYO_CD AS BUSYO_CD_1 , MST.BUSYO_RYKNM " . "\r\n";
        $strSQL .= ",   SUM(TR.GENRI_MOKUHYO) GENRI_MOKUHYO " . "\r\n";
        $strSQL .= ",   SUM(TR.GENRI_YOSOU) GENRI_YOSOU " . "\r\n";
        $strSQL .= ",   SUM(TR.GENRI_JISSEKI) - SUM(TR.GENRI_YOSOU) GENRI_SABUN " . "\r\n";
        $strSQL .= ",   SUM(TR.GENRI_JISSEKI) GENRI_JISSEKI " . "\r\n";

        $strSQL .= ",   SUM(TR.URIMOKU_MAIN) URIMOKU_MAIN " . "\r\n";
        $strSQL .= ",   SUM(TR.URIMOKU_TACHANEL) URIMOKU_TACHANEL " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_MAIN_Y) URIYOSOU_MAIN_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_MAIN_S) URIYOSOU_MAIN_S " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_KEI_Y) URIYOSOU_KEI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_KEI_S) URIYOSOU_KEI_S " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_VOLVO_Y) URIYOSOU_VOLVO_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_VOLVO_S) URIYOSOU_VOLVO_S " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_SONOTA_Y) URIYOSOU_SONOTA_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_SONOTA_S) URIYOSOU_SONOTA_S " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_VOLVO_Y) + SUM(TR.URIYOSOU_SONOTA_Y) URIYOSOU_VOLVO_SONOTA_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_VOLVO_S) + SUM(TR.URIYOSOU_SONOTA_S) URIYOSOU_VOLVO_SONOTA_S " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_MAIN_Y) + SUM(TR.URIYOSOU_KEI_Y) + SUM(TR.URIYOSOU_VOLVO_Y) + SUM(TR.URIYOSOU_SONOTA_Y) URI_GK_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_MAIN_S) + SUM(TR.URIYOSOU_KEI_S) + SUM(TR.URIYOSOU_VOLVO_S) + SUM(TR.URIYOSOU_SONOTA_S) URI_GK_S " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_JIJI_Y) TRKDAISU_JIJI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_JIJI_S) TRKDAISU_JIJI_S " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_FUKUSHI_Y) TRKDAISU_FUKUSHI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_FUKUSHI_S) TRKDAISU_FUKUSHI_S " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_TAJI_Y) TRKDAISU_TAJI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_TAJI_S) TRKDAISU_TAJI_S " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_JITA_Y) TRKDAISU_JITA_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_JITA_S) TRKDAISU_JITA_S " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_JIJI_Y) + SUM(TR.TRKDAISU_FUKUSHI_Y) + SUM(TR.TRKDAISU_TAJI_Y) - SUM(TR.TRKDAISU_JITA_Y) TRK_GK_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_JIJI_S) + SUM(TR.TRKDAISU_FUKUSHI_S) + SUM(TR.TRKDAISU_TAJI_S) - SUM(TR.TRKDAISU_JITA_S) TRK_GK_S " . "\r\n";
        $strSQL .= ",   SUM(TR.KEI_TRK_DAISU_Y) KEI_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.KEI_TRK_DAISU_S) KEI_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_KEI_TAJI_Y) + SUM(TR.TRKDAISU_KEI_TAJI_S) TRKDAISU_KEI_TAJI " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_KEI_JITA_Y) + SUM(TR.TRKDAISU_KEI_JITA_S) TRKDAISU_KEI_JITA " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_KEI_FUKUSHI_Y) + SUM(TR.TRKDAISU_KEI_FUKUSHI_S) TRKDAISU_KEI_FUKUSHI " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_Y) + SUM(TR.URIYOSOU_CHUKO_CHOKU_S) CHOKU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_Y) URIYOSOU_CHUKO_CHOKU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_S) URIYOSOU_CHUKO_CHOKU_S " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_Y) + SUM(TR.URIYOSOU_CHUKO_GYOBAI_S) GYOBAI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_Y) URIYOSOU_CHUKO_GYOBAI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_S) URIYOSOU_CHUKO_GYOBAI_S " . "\r\n";

        $strSQL .= ",   SUM(TR.SHURI_HOKEN) SHURI_HOKEN " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_LEASE) SHURI_LEASE " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_LOAN) SHURI_LOAN " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_KIBOU) SHURI_KIBOU " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_P753) SHURI_P753 " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_PMENTE) SHURI_PMENTE " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_BODYCOAT) SHURI_BODYCOAT " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_JAF) SHURI_JAF " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_OSS) SHURI_OSS " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_RENTA_Y) + SUM(TR.TRKDAISU_RENTA_S) TRKDAISU_RENTA " . "\r\n";
        $strSQL .= ",   SUM(ZN.GENRI_JISSEKI) GENRI_JISSEKI_ZEN " . "\r\n";
        $strSQL .= ",   SUM(ZN.URIYOSOU_MAIN_Y) + SUM(ZN.URIYOSOU_KEI_Y) + SUM(ZN.URIYOSOU_VOLVO_Y) + SUM(ZN.URIYOSOU_SONOTA_Y) URI_DAI_Y_ZEN" . "\r\n";
        $strSQL .= ",   SUM(ZN.URIYOSOU_MAIN_S) + SUM(ZN.URIYOSOU_KEI_S) + SUM(ZN.URIYOSOU_VOLVO_S) + SUM(ZN.URIYOSOU_SONOTA_S) URI_DAI_S_ZEN" . "\r\n";
        $strSQL .= ",   SUM(NVL(YS.YSN_GK" . $postData['ddlMonth'] . ", 0)) YSN_GK10 " . "\r\n";
        $strSQL .= ",   DENSE_RANK() OVER(ORDER BY DECODE(YS.YSN_GK" . $postData['ddlMonth'] . ", NULL, 0, 0, 0, SUM(TR.GENRI_JISSEKI) / YS.YSN_GK" . $postData['ddlMonth'] . ") DESC) GENRI_JUNI " . "\r\n";
        $strSQL .= ",   DENSE_RANK() OVER(ORDER BY DECODE(YS.YSN_GK" . $postData['ddlMonth'] . ", NULL, 0, 0, 0, (SUM(TR.URIYOSOU_MAIN_Y) + SUM(TR.URIYOSOU_KEI_Y) + SUM(TR.URIYOSOU_VOLVO_Y) " . "\r\n";
        $strSQL .= "    + SUM(TR.URIYOSOU_SONOTA_Y) + SUM(TR.URIYOSOU_MAIN_S) + SUM(TR.URIYOSOU_KEI_S) + SUM(TR.URIYOSOU_VOLVO_S) + SUM(TR.URIYOSOU_SONOTA_S)) / YS.YSN_GK" . $postData['ddlMonth'] . ") DESC) URIYOSOU_JUNI " . "\r\n";
        $strSQL .= " FROM   HBUSYO MST " . "\r\n";
        $strSQL .= " INNER JOIN (SELECT BUSYO_CD ,  HDT_TENPO_CD V_TENPO FROM HBUSYO) BUS " . "\r\n";
        $strSQL .= " ON     MST.BUSYO_CD = BUS.V_TENPO " . "\r\n";

        $strSQL .= " LEFT JOIN HDTTARGETRESULT TR " . "\r\n";
        $strSQL .= " ON     TR.BUSYO_CD = BUS.BUSYO_CD AND    TR.TAISYOU_YM = '" . $postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "' AND    TR.BUSYO_CD <> '999' " . "\r\n";
        $strSQL .= " LEFT JOIN (" . $this->fncSQLTARGETRESULTTABLE($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT)) . ") ZN " . "\r\n";

        $strSQL .= " ON     ZN.BUSYO_CD = BUS.BUSYO_CD AND    ZN.TAISYOU_YM = '" . ($postData['txtbDuring'] - 1) . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "' AND    ZN.BUSYO_CD <> '999' " . "\r\n";
        $strSQL .= " LEFT JOIN HYOSAN YS " . "\r\n";
        $strSQL .= " ON     YS.BUSYO_CD = BUS.BUSYO_CD AND    YS.KI = @KI AND    YS.BUSYO_CD <> '999'  AND    YS.LINE_NO = 14 " . "\r\n";
        $strSQL .= " WHERE  MST.HDT_TENPO_DISP_NO IS NOT NULL " . "\r\n";

        $strSQL = $this->appendBusyoCondition($strSQL, $postData);
        $strSQL = str_replace("AND  MST.BUSYO_CD NOT IN ('183')", "AND  BUS.BUSYO_CD NOT IN ('183')", $strSQL);

        $strSQL .= " GROUP BY TR.BUSYO_CD ,  MST.BUSYO_CD ,   MST.BUSYO_RYKNM ,  MST.HDT_TENPO_DISP_NO ,  TR.GENRI_JISSEKI ,  YS.YSN_GK" . $postData['ddlMonth'] . "\r\n";
        $strSQL .= " ORDER BY MST.HDT_TENPO_DISP_NO " . "\r\n";

        if ($postData['ddlMonth'] < 10) {
            $strSQL = str_replace("@KI", $postData['txtbDuring'] - 1918, $strSQL);
        } elseif ($postData['ddlMonth'] >= 10) {
            $strSQL = str_replace("@KI", $postData['txtbDuring'] - 1917, $strSQL);
        }

        return $strSQL;
    }

    public function SQL2($postData)
    {
        return parent::select($this->SQL2SQL($postData));
    }


    // ***********************************************************************
    // '処 理 名：目標と実績小計データを取得するSQL作成
    // '関 数 名：SQL2SQL
    // '引    数：無し
    // '戻 り 値 ：目標と実績小計データを取得するSQL
    // '処理説明 ：目標と実績小計データを取得するSQLを作成する
    // '**********************************************************************

    public function SQL2SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SUM(TR.GENRI_MOKUHYO) GENRI_MOKUHYO " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_YOSOU) GENRI_YOSOU " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_JISSEKI) - SUM(TR.GENRI_YOSOU) GENRI_SABUN " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_JISSEKI) GENRI_JISSEKI " . "\r\n";
        $strSQL .= ",      SUM(TR.URIMOKU_MAIN) URIMOKU_MAIN " . "\r\n";

        $strSQL .= ",      SUM(TR.URIMOKU_TACHANEL) URIMOKU_TACHANEL " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_Y) URIYOSOU_MAIN_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_S) URIYOSOU_MAIN_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_KEI_Y) URIYOSOU_KEI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_KEI_S) URIYOSOU_KEI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_Y) URIYOSOU_VOLVO_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_S) URIYOSOU_VOLVO_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_SONOTA_Y) URIYOSOU_SONOTA_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_SONOTA_S) URIYOSOU_SONOTA_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_Y) + SUM(TR.URIYOSOU_SONOTA_Y) URIYOSOU_VOLVO_SONOTA_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_S) + SUM(TR.URIYOSOU_SONOTA_S) URIYOSOU_VOLVO_SONOTA_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_Y) + SUM(TR.URIYOSOU_KEI_Y)	+ SUM(TR.URIYOSOU_VOLVO_Y) + SUM(TR.URIYOSOU_SONOTA_Y) URI_GK_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_S) + SUM(TR.URIYOSOU_KEI_S)	+ SUM(TR.URIYOSOU_VOLVO_S) + SUM(TR.URIYOSOU_SONOTA_S) URI_GK_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_Y) TRKDAISU_JIJI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_S) TRKDAISU_JIJI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_FUKUSHI_Y) TRKDAISU_FUKUSHI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_FUKUSHI_S) TRKDAISU_FUKUSHI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_TAJI_Y) TRKDAISU_TAJI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_TAJI_S) TRKDAISU_TAJI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JITA_Y) TRKDAISU_JITA_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JITA_S) TRKDAISU_JITA_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_Y) + SUM(TR.TRKDAISU_FUKUSHI_Y) + SUM(TR.TRKDAISU_TAJI_Y) - SUM(TR.TRKDAISU_JITA_Y) TRK_GK_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_S) + SUM(TR.TRKDAISU_FUKUSHI_S) + SUM(TR.TRKDAISU_TAJI_S) - SUM(TR.TRKDAISU_JITA_S) TRK_GK_S " . "\r\n";
        $strSQL .= ",   SUM(TR.KEI_TRK_DAISU_Y) KEI_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.KEI_TRK_DAISU_S) KEI_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_KEI_TAJI_Y) + SUM(TR.TRKDAISU_KEI_TAJI_S) TRKDAISU_KEI_TAJI " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_KEI_JITA_Y) + SUM(TR.TRKDAISU_KEI_JITA_S) TRKDAISU_KEI_JITA " . "\r\n";

        $strSQL .= ",   SUM(TR.TRKDAISU_KEI_FUKUSHI_Y) + SUM(TR.TRKDAISU_KEI_FUKUSHI_S) TRKDAISU_KEI_FUKUSHI " . "\r\n";

        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_Y) + SUM(TR.URIYOSOU_CHUKO_CHOKU_S) CHOKU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_Y) URIYOSOU_CHUKO_CHOKU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_S) URIYOSOU_CHUKO_CHOKU_S " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_Y) + SUM(TR.URIYOSOU_CHUKO_GYOBAI_S) GYOBAI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_Y) URIYOSOU_CHUKO_GYOBAI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_S) URIYOSOU_CHUKO_GYOBAI_S " . "\r\n";

        $strSQL .= ",   SUM(TR.SHURI_HOKEN) SHURI_HOKEN " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_LEASE) SHURI_LEASE " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_LOAN) SHURI_LOAN " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_KIBOU) SHURI_KIBOU " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_P753) SHURI_P753 " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_PMENTE) SHURI_PMENTE " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_BODYCOAT) SHURI_BODYCOAT " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_JAF) SHURI_JAF " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_OSS) SHURI_OSS " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_RENTA_Y) + SUM(TR.TRKDAISU_RENTA_S) TRKDAISU_RENTA " . "\r\n";
        $strSQL .= ",   SUM(ZN.GENRI_JISSEKI) GENRI_JISSEKI_ZEN " . "\r\n";
        $strSQL .= ",   SUM(ZN.URIYOSOU_MAIN_Y) + SUM(ZN.URIYOSOU_KEI_Y) + SUM(ZN.URIYOSOU_VOLVO_Y) + SUM(ZN.URIYOSOU_SONOTA_Y) URI_DAI_Y_ZEN" . "\r\n";
        $strSQL .= ",   SUM(ZN.URIYOSOU_MAIN_S) + SUM(ZN.URIYOSOU_KEI_S) + SUM(ZN.URIYOSOU_VOLVO_S) + SUM(ZN.URIYOSOU_SONOTA_S) URI_DAI_S_ZEN" . "\r\n";
        $strSQL .= " FROM   HBUSYO MST " . "\r\n";
        $strSQL .= " LEFT JOIN HDTTARGETRESULT TR " . "\r\n";
        $strSQL .= " ON     TR.BUSYO_CD = MST.BUSYO_CD AND    TR.TAISYOU_YM = '" . $postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "' AND    TR.BUSYO_CD <> '999' " . "\r\n";
        $strSQL .= " LEFT JOIN  (" . $this->fncSQLTARGETRESULTTABLE($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT)) . ")  ZN " . "\r\n";
        $strSQL .= " ON     ZN.BUSYO_CD = MST.BUSYO_CD AND    ZN.TAISYOU_YM = '" . ($postData['txtbDuring'] - 1) . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "' AND    ZN.BUSYO_CD <> '999' " . "\r\n";

        $strSQL .= " WHERE  1 = 1 " . "\r\n";

        $strSQL = $this->appendBusyoCondition($strSQL, $postData);

        return $strSQL;
    }

    public function SQLZennen($postData)
    {
        return parent::select($this->SQLZennenSQL($postData));
    }


    //  ***********************************************************************
    //  '処 理 名：目標と実績前年同月データを取得するSQL作成
    //  '関 数 名：SQLZennen
    //  '引    数：無し
    //  '戻 り 値 ：目標と実績前年同月データを取得するSQL
    //  '処理説明 ：目標と実績前年同月データを取得するSQLを作成する
    //  '**********************************************************************

    public function SQLZennenSQL($postData)
    {

        $strSQL = "";
        $strSQL .= "SELECT SUM(TR.GENRI_MOKUHYO) GENRI_MOKUHYO " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_YOSOU) GENRI_YOSOU " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_JISSEKI) - SUM(TR.GENRI_YOSOU) GENRI_SABUN " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_JISSEKI) GENRI_JISSEKI " . "\r\n";
        $strSQL .= ",      SUM(TR.URIMOKU_MAIN) URIMOKU_MAIN " . "\r\n";
        $strSQL .= ",      SUM(TR.URIMOKU_TACHANEL) URIMOKU_TACHANEL " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_Y) URIYOSOU_MAIN_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_S) URIYOSOU_MAIN_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_KEI_Y) URIYOSOU_KEI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_KEI_S) URIYOSOU_KEI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_Y) URIYOSOU_VOLVO_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_S) URIYOSOU_VOLVO_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_SONOTA_Y) URIYOSOU_SONOTA_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_SONOTA_S) URIYOSOU_SONOTA_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_Y) + SUM(TR.URIYOSOU_SONOTA_Y) URIYOSOU_VOLVO_SONOTA_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_S) + SUM(TR.URIYOSOU_SONOTA_S) URIYOSOU_VOLVO_SONOTA_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_Y) + SUM(TR.URIYOSOU_KEI_Y) + SUM(TR.URIYOSOU_VOLVO_Y) + SUM(TR.URIYOSOU_SONOTA_Y) URI_GK_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_S) + SUM(TR.URIYOSOU_KEI_S) + SUM(TR.URIYOSOU_VOLVO_S) + SUM(TR.URIYOSOU_SONOTA_S) URI_GK_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_Y) TRKDAISU_JIJI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_S) TRKDAISU_JIJI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_FUKUSHI_Y) TRKDAISU_FUKUSHI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_FUKUSHI_S) TRKDAISU_FUKUSHI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_TAJI_Y) TRKDAISU_TAJI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_TAJI_S) TRKDAISU_TAJI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JITA_Y) TRKDAISU_JITA_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JITA_S) TRKDAISU_JITA_S" . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_Y) + SUM(TR.TRKDAISU_FUKUSHI_Y) + SUM(TR.TRKDAISU_TAJI_Y) - SUM(TR.TRKDAISU_JITA_Y) TRK_GK_Y" . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_S) + SUM(TR.TRKDAISU_FUKUSHI_S) + SUM(TR.TRKDAISU_TAJI_S) - SUM(TR.TRKDAISU_JITA_S) TRK_GK_S" . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_Y) KEI_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_S) KEI_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_KEI_TAJI_Y) + SUM(TR.TRKDAISU_KEI_TAJI_S) TRKDAISU_KEI_TAJI " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_KEI_JITA_Y) + SUM(TR.TRKDAISU_KEI_JITA_S) TRKDAISU_KEI_JITA " . "\r\n";

        $strSQL .= ",      SUM(TR.TRKDAISU_KEI_FUKUSHI_Y) + SUM(TR.TRKDAISU_KEI_FUKUSHI_S) TRKDAISU_KEI_FUKUSHI " . "\r\n";

        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_Y) + SUM(TR.URIYOSOU_CHUKO_CHOKU_S) CHOKU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_Y) URIYOSOU_CHUKO_CHOKU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_S) URIYOSOU_CHUKO_CHOKU_S " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_Y) + SUM(TR.URIYOSOU_CHUKO_GYOBAI_S) GYOBAI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_Y) URIYOSOU_CHUKO_GYOBAI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_S) URIYOSOU_CHUKO_GYOBAI_S " . "\r\n";

        $strSQL .= ",      SUM(TR.SHURI_HOKEN) SHURI_HOKEN " . "\r\n";
        $strSQL .= ",      SUM(TR.SHURI_LEASE) SHURI_LEASE " . "\r\n";
        $strSQL .= ",      SUM(TR.SHURI_LOAN) SHURI_LOAN " . "\r\n";
        $strSQL .= ",      SUM(TR.SHURI_KIBOU) SHURI_KIBOU " . "\r\n";
        $strSQL .= ",      SUM(TR.SHURI_P753) SHURI_P753 " . "\r\n";
        $strSQL .= ",      SUM(TR.SHURI_PMENTE) SHURI_PMENTE " . "\r\n";
        $strSQL .= ",      SUM(TR.SHURI_BODYCOAT) SHURI_BODYCOAT " . "\r\n";
        $strSQL .= ",      SUM(TR.SHURI_JAF) SHURI_JAF " . "\r\n";
        $strSQL .= ",      SUM(TR.SHURI_OSS) SHURI_OSS " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_RENTA_Y) + SUM(TR.TRKDAISU_RENTA_S) TRKDAISU_RENTA " . "\r\n";

        $strSQL .= "FROM   HDTTARGETRESULT TR " . "\r\n";

        $strSQL .= "WHERE  TR.TAISYOU_YM = '" . ($postData['txtbDuring'] - 1) . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "' " . "\r\n";

        return $strSQL;
    }

    public function SQL3($postData)
    {
        return parent::select($this->SQL3SQL($postData));
    }


    // ***********************************************************************
    // '処 理 名：目標と実績その他行データを取得するSQL作成
    // '関 数 名：SQL3SQL
    // '引    数：無し
    // '戻 り 値 ：目標と実績その他行データを取得するSQL
    // '処理説明 ：目標と実績その他行データを取得するSQLを作成する
    // '**********************************************************************

    public function SQL3SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT TR.BUSYO_CD " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_MOKUHYO) GENRI_MOKUHYO " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_YOSOU) GENRI_YOSOU " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_JISSEKI) - SUM(TR.GENRI_YOSOU) GENRI_SABUN " . "\r\n";
        $strSQL .= ",      SUM(TR.GENRI_JISSEKI) GENRI_JISSEKI " . "\r\n";

        $strSQL .= ",      SUM(TR.URIMOKU_MAIN) URIMOKU_MAIN " . "\r\n";
        $strSQL .= ",      SUM(TR.URIMOKU_TACHANEL) URIMOKU_TACHANEL " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_Y) URIYOSOU_MAIN_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_S) URIYOSOU_MAIN_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_KEI_Y) URIYOSOU_KEI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_KEI_S) URIYOSOU_KEI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_Y) URIYOSOU_VOLVO_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_S) URIYOSOU_VOLVO_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_SONOTA_Y) URIYOSOU_SONOTA_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_SONOTA_S) URIYOSOU_SONOTA_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_Y) + SUM(TR.URIYOSOU_SONOTA_Y) URIYOSOU_VOLVO_SONOTA_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_VOLVO_S) + SUM(TR.URIYOSOU_SONOTA_S) URIYOSOU_VOLVO_SONOTA_S " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_Y) + SUM(TR.URIYOSOU_KEI_Y)	+ SUM(TR.URIYOSOU_VOLVO_Y) + SUM(TR.URIYOSOU_SONOTA_Y) URI_GK_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.URIYOSOU_MAIN_S) + SUM(TR.URIYOSOU_KEI_S)	+ SUM(TR.URIYOSOU_VOLVO_S) + SUM(TR.URIYOSOU_SONOTA_S) URI_GK_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_Y) TRKDAISU_JIJI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_S) TRKDAISU_JIJI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_FUKUSHI_Y) TRKDAISU_FUKUSHI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_FUKUSHI_S) TRKDAISU_FUKUSHI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_TAJI_Y) TRKDAISU_TAJI_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_TAJI_S) TRKDAISU_TAJI_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JITA_Y) TRKDAISU_JITA_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JITA_S) TRKDAISU_JITA_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_Y) + SUM(TR.TRKDAISU_FUKUSHI_Y) + SUM(TR.TRKDAISU_TAJI_Y) - SUM(TR.TRKDAISU_JITA_Y) TRK_GK_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TRKDAISU_JIJI_S) + SUM(TR.TRKDAISU_FUKUSHI_S) + SUM(TR.TRKDAISU_TAJI_S) - SUM(TR.TRKDAISU_JITA_S) TRK_GK_S " . "\r\n";
        $strSQL .= ",   SUM(TR.KEI_TRK_DAISU_Y) KEI_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.KEI_TRK_DAISU_S) KEI_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_KEI_TAJI_Y) + SUM(TR.TRKDAISU_KEI_TAJI_S) TRKDAISU_KEI_TAJI " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_KEI_JITA_Y) + SUM(TR.TRKDAISU_KEI_JITA_S) TRKDAISU_KEI_JITA " . "\r\n";

        $strSQL .= ",   SUM(TR.TRKDAISU_KEI_FUKUSHI_Y) + SUM(TR.TRKDAISU_KEI_FUKUSHI_S) TRKDAISU_KEI_FUKUSHI " . "\r\n";

        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_Y) + SUM(TR.URIYOSOU_CHUKO_CHOKU_S) CHOKU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_Y) URIYOSOU_CHUKO_CHOKU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_CHOKU_S) URIYOSOU_CHUKO_CHOKU_S " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_Y) + SUM(TR.URIYOSOU_CHUKO_GYOBAI_S) GYOBAI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_Y) URIYOSOU_CHUKO_GYOBAI_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.URIYOSOU_CHUKO_GYOBAI_S) URIYOSOU_CHUKO_GYOBAI_S " . "\r\n";

        $strSQL .= ",   SUM(TR.SHURI_HOKEN) SHURI_HOKEN " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_LEASE) SHURI_LEASE " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_LOAN) SHURI_LOAN " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_KIBOU) SHURI_KIBOU " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_P753) SHURI_P753 " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_PMENTE) SHURI_PMENTE " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_BODYCOAT) SHURI_BODYCOAT " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_JAF) SHURI_JAF " . "\r\n";
        $strSQL .= ",   SUM(TR.SHURI_OSS) SHURI_OSS " . "\r\n";
        $strSQL .= ",   SUM(TR.TRKDAISU_RENTA_Y) + SUM(TR.TRKDAISU_RENTA_S) TRKDAISU_RENTA " . "\r\n";
        $strSQL .= ",   SUM(ZN.GENRI_JISSEKI) GENRI_JISSEKI_ZEN " . "\r\n";
        $strSQL .= ",   SUM(ZN.URIYOSOU_MAIN_Y) + SUM(ZN.URIYOSOU_KEI_Y) + SUM(ZN.URIYOSOU_VOLVO_Y) + SUM(ZN.URIYOSOU_SONOTA_Y) URI_DAI_Y_ZEN" . "\r\n";
        $strSQL .= ",   SUM(ZN.URIYOSOU_MAIN_S) + SUM(ZN.URIYOSOU_KEI_S) + SUM(ZN.URIYOSOU_VOLVO_S) + SUM(ZN.URIYOSOU_SONOTA_S) URI_DAI_S_ZEN" . "\r\n";
        $strSQL .= " FROM   HDTTARGETRESULT TR " . "\r\n";
        $strSQL .= " LEFT JOIN (" . $this->fncSQLTARGETRESULTTABLE($postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT)) . ") ZN " . "\r\n";

        $strSQL .= " ON     ZN.TAISYOU_YM = '" . ($postData['txtbDuring'] - 1) . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "' AND    ZN.BUSYO_CD = '999' " . "\r\n";
        $strSQL .= " WHERE  TR.TAISYOU_YM = '" . $postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "'" . "\r\n";
        $strSQL .= " AND    TR.BUSYO_CD = '999' " . "\r\n";
        $strSQL .= " GROUP BY TR.BUSYO_CD " . "\r\n";

        $strSQL = str_replace("@KI", $postData['txtbDuring'], $strSQL);
        $strSQL = str_replace("@TUKI", str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT), $strSQL);

        return $strSQL;
    }

    public function SQL4($postData)
    {
        return parent::select($this->SQL4SQL($postData));
    }


    // ***********************************************************************
    // '処 理 名：目標と実績車種内訳View明細データを取得するSQL作成
    // '関 数 名：SQL4SQL
    // '引    数：無し
    // '戻 り 値 ：目標と実績車種内訳View明細データを取得するSQL
    // '処理説明 ：目標と実績車種内訳View明細行データを取得するSQLを作成する
    // '**********************************************************************

    public function SQL4SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD, TR.BUSYO_CD AS BUSYO_CD_1 , MST.BUSYO_RYKNM " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.DEMIO_TRK_DAISU_Y,0)) + SUM(NVL(TR.VRW_TRK_DAISU_Y,0)) + SUM(NVL(TR.ATENZA_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.AXS_TRK_DAISU_Y,0)) + SUM(NVL(TR.PREMACY_TRK_DAISU_Y,0)) + SUM(NVL(TR.MPV_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.RX8_TRK_DAISU_Y,0)) + SUM(NVL(TR.CX7_TRK_DAISU_Y,0)) + SUM(NVL(TR.LDSTAR_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.FMV_TRK_DAISU_Y,0)) + SUM(NVL(TR.BONGO_TRK_DAISU_Y,0)) + SUM(NVL(TR.TTD_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.BIANTE_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX3_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX5_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX8_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3S_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3H_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6S_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6W_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M2G_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX30_TRK_DAISU_Y,0))" . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.MX30_TRK_DAISU_Y,0))" . "\r\n";
        //20240326 caina ins s
        $strSQL .= "+ SUM(NVL(TR.CX60_TRK_DAISU_Y,0))" . "\r\n";
        //20240326 caina ins e
        //20240611 LHB ins s
        //20240712 LHB upd s
        // $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_Y,0))" . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_Y,0))" . "\r\n";
        }
        //20240712 LHB upd e
        //20240611 LHB ins e

        $strSQL .= "+ SUM(NVL(TR.TT_TRK_DAISU_Y,0)) DAISU_Y " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.DEMIO_TRK_DAISU_S,0)) + SUM(NVL(TR.VRW_TRK_DAISU_S,0)) + SUM(NVL(TR.ATENZA_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.AXS_TRK_DAISU_S,0)) + SUM(NVL(TR.PREMACY_TRK_DAISU_S,0)) + SUM(NVL(TR.MPV_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.RX8_TRK_DAISU_S,0)) + SUM(NVL(TR.CX7_TRK_DAISU_S,0)) + SUM(NVL(TR.LDSTAR_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.FMV_TRK_DAISU_S,0)) + SUM(NVL(TR.BONGO_TRK_DAISU_S,0)) + SUM(NVL(TR.TTD_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.BIANTE_TRK_DAISU_S,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX5_TRK_DAISU_S,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX3_TRK_DAISU_S,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX8_TRK_DAISU_S,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3S_TRK_DAISU_S,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3H_TRK_DAISU_S,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6S_TRK_DAISU_S,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6W_TRK_DAISU_S,0))" . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.M2G_TRK_DAISU_S,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX30_TRK_DAISU_S,0))" . "\r\n";

        //20240326 caina upd s
        // $strSQL .= "+ SUM(NVL(TR.MX30_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.MX30_TRK_DAISU_S,0))" . "\r\n";
        //20240326 caina upd e
        //20240326 caina ins s
        $strSQL .= "+ SUM(NVL(TR.CX60_TRK_DAISU_S,0))" . "\r\n";
        //20240326 caina ins e
        //20240611 LHB ins s
        //20240712 LHB upd s
        // 	$strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_S,0))" . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_S,0))" . "\r\n";
        }
        //20240712 LHB upd e
        //20240611 LHB ins e

        $strSQL .= "+ SUM(NVL(TR.TT_TRK_DAISU_S,0))  DAISU_S " . "\r\n";
        $strSQL .= ",   SUM(TR.DEMIO_TRK_DAISU_Y) DEMIO_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(TR.DEMIO_TRK_DAISU_S) DEMIO_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",   SUM(NVL(TR.M2G_TRK_DAISU_Y,0)) M2G_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(NVL(TR.M2G_TRK_DAISU_S,0)) M2G_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.VRW_TRK_DAISU_Y) VRW_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.VRW_TRK_DAISU_S) VRW_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.CX3_TRK_DAISU_Y) CX3_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.CX3_TRK_DAISU_S) CX3_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.CX5_TRK_DAISU_Y) CX5_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.CX5_TRK_DAISU_S) CX5_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX8_TRK_DAISU_Y,0)) CX8_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX8_TRK_DAISU_S,0)) CX8_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",   SUM(NVL(TR.CX30_TRK_DAISU_Y,0)) CX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(NVL(TR.CX30_TRK_DAISU_S,0)) CX30_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",   SUM(NVL(TR.MX30_TRK_DAISU_Y,0)) MX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(NVL(TR.MX30_TRK_DAISU_S,0)) MX30_TRK_DAISU_S " . "\r\n";

        //20240326 caina ins s
        $strSQL .= ",   SUM(NVL(TR.CX60_TRK_DAISU_Y,0)) CX60_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(NVL(TR.CX60_TRK_DAISU_S,0)) CX60_TRK_DAISU_S " . "\r\n";
        //20240326 caina ins e
        //20240611 LHB ins s
        //20240712 LHB upd s
        // $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_Y,0)) CX80_TRK_DAISU_Y " . "\r\n";
        // $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_S,0)) CX80_TRK_DAISU_S " . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_Y,0)) CX80_TRK_DAISU_Y " . "\r\n";
            $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_S,0)) CX80_TRK_DAISU_S " . "\r\n";
        }
        //20240712 LHB upd e
        //20240611 LHB ins e

        $strSQL .= ",      SUM(NVL(TR.M3S_TRK_DAISU_Y,0)) M3S_TRK_DAISU_Y" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3S_TRK_DAISU_S,0)) M3S_TRK_DAISU_S" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3H_TRK_DAISU_Y,0)) M3H_TRK_DAISU_Y" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3H_TRK_DAISU_S,0)) M3H_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.M6S_TRK_DAISU_Y,0)) M6S_TRK_DAISU_Y" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6S_TRK_DAISU_S,0)) M6S_TRK_DAISU_S" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6W_TRK_DAISU_Y,0)) M6W_TRK_DAISU_Y" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6W_TRK_DAISU_S,0)) M6W_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.ATENZA_TRK_DAISU_Y) ATENZA_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.ATENZA_TRK_DAISU_S) ATENZA_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.AXS_TRK_DAISU_Y) AXS_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.AXS_TRK_DAISU_S) AXS_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.PREMACY_TRK_DAISU_Y) PREMACY_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.PREMACY_TRK_DAISU_S) PREMACY_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.BIANTE_TRK_DAISU_Y) BIANTE_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.BIANTE_TRK_DAISU_S) BIANTE_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.MPV_TRK_DAISU_Y) MPV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.MPV_TRK_DAISU_S) MPV_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.LDSTAR_TRK_DAISU_Y) LDSTAR_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.LDSTAR_TRK_DAISU_S) LDSTAR_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.FMV_TRK_DAISU_Y) FMV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.FMV_TRK_DAISU_S) FMV_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.BONGO_TRK_DAISU_Y) BONGO_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.BONGO_TRK_DAISU_S) BONGO_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.TT_TRK_DAISU_Y) TT_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TT_TRK_DAISU_S) TT_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_Y) KEI_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_S) KEI_TRK_DAISU_S " . "\r\n";

        $strSQL .= " FROM   HBUSYO MST " . "\r\n";
        $strSQL .= " INNER JOIN (SELECT BUSYO_CD ,  HDT_TENPO_CD V_TENPO FROM HBUSYO) BUS " . "\r\n";
        $strSQL .= " ON     MST.BUSYO_CD = BUS.V_TENPO " . "\r\n";

        $strSQL .= " LEFT JOIN HDTTARGETRESULT TR " . "\r\n";

        $strSQL .= " ON     TR.BUSYO_CD = BUS.BUSYO_CD " . "\r\n";
        $strSQL .= " AND    TR.TAISYOU_YM = '" . $postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "'" . "\r\n";
        $strSQL .= " AND    TR.BUSYO_CD <> '999'" . "\r\n";

        $strSQL .= " WHERE  MST.HDT_TENPO_DISP_NO IS NOT NULL " . "\r\n";

        $strSQL = $this->appendBusyoCondition($strSQL, $postData);
        $strSQL = str_replace("AND  MST.BUSYO_CD NOT IN ('183')", "AND  BUS.BUSYO_CD NOT IN ('183')", $strSQL);

        $strSQL .= " GROUP BY TR.BUSYO_CD	,    MST.BUSYO_CD ,  MST.BUSYO_RYKNM , MST.HDT_TENPO_DISP_NO " . "\r\n";
        $strSQL .= " ORDER BY MST.HDT_TENPO_DISP_NO " . "\r\n";

        $strSQL = str_replace("@KI", $postData['txtbDuring'], $strSQL);
        $strSQL = str_replace("@TUKI", str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT), $strSQL);

        return $strSQL;
    }

    public function SQL5($postData)
    {
        return parent::select($this->SQL5SQL($postData));
    }


    //  ***********************************************************************
    //  '処 理 名：目標と実績車種内訳View小計データを取得するSQL作成
    //  '関 数 名：SQL5SQL
    //  '引    数：無し
    //  '戻 り 値 ：目標と実績車種内訳View小計データを取得するSQL
    //  '処理説明 ：目標と実績車種内訳View小計データを取得するSQLを作成する
    //  '**********************************************************************

    public function SQL5SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT  SUM(NVL(TR.DEMIO_TRK_DAISU_Y,0)) + SUM(NVL(TR.VRW_TRK_DAISU_Y,0)) + SUM(NVL(TR.ATENZA_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.AXS_TRK_DAISU_Y,0)) + SUM(NVL(TR.PREMACY_TRK_DAISU_Y,0)) + SUM(NVL(TR.MPV_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.BIANTE_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.RX8_TRK_DAISU_Y,0)) + SUM(NVL(TR.CX7_TRK_DAISU_Y,0)) + SUM(NVL(TR.LDSTAR_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.FMV_TRK_DAISU_Y,0)) + SUM(NVL(TR.BONGO_TRK_DAISU_Y,0)) + SUM(NVL(TR.TTD_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX5_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX3_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX8_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3S_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3H_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6S_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6W_TRK_DAISU_Y,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.M2G_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX30_TRK_DAISU_Y,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.MX30_TRK_DAISU_Y,0)) " . "\r\n";
        //20240326 caina ins s
        $strSQL .= "+ SUM(NVL(TR.CX60_TRK_DAISU_Y,0))" . "\r\n";
        //20240326 caina ins e
        //20240326 LHB ins s
        //20240712 LHB upd s
        // $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_Y,0))" . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_Y,0))" . "\r\n";
        }
        //20240712 LHB upd e
        //20240326 LHB ins e

        $strSQL .= "+ SUM(NVL(TR.TT_TRK_DAISU_Y,0)) DAISU_Y " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.DEMIO_TRK_DAISU_S,0)) + SUM(NVL(TR.VRW_TRK_DAISU_S,0)) + SUM(NVL(TR.ATENZA_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.AXS_TRK_DAISU_S,0)) + SUM(NVL(TR.PREMACY_TRK_DAISU_S,0)) + SUM(NVL(TR.MPV_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.BIANTE_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.RX8_TRK_DAISU_S,0)) + SUM(NVL(TR.CX7_TRK_DAISU_S,0)) + SUM(NVL(TR.LDSTAR_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.FMV_TRK_DAISU_S,0)) + SUM(NVL(TR.BONGO_TRK_DAISU_S,0)) + SUM(NVL(TR.TTD_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX5_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX3_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX8_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3S_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3H_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6S_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6W_TRK_DAISU_S,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.M2G_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX30_TRK_DAISU_S,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.MX30_TRK_DAISU_S,0)) " . "\r\n";
        //20240326 caina ins s
        $strSQL .= "+ SUM(NVL(TR.CX60_TRK_DAISU_S,0))" . "\r\n";
        //20240326 caina ins e
        //20240326 LHB ins s
        //20240712 LHB upd s
        // 	$strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_S,0))" . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_S,0))" . "\r\n";
        }
        //20240712 LHB upd e
        //20240326 LHB ins e

        $strSQL .= " + SUM(NVL(TR.TT_TRK_DAISU_S,0)) DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.DEMIO_TRK_DAISU_Y) DEMIO_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.DEMIO_TRK_DAISU_S) DEMIO_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.M2G_TRK_DAISU_Y,0)) M2G_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M2G_TRK_DAISU_S,0)) M2G_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.VRW_TRK_DAISU_Y) VRW_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.VRW_TRK_DAISU_S) VRW_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.CX3_TRK_DAISU_Y) CX3_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.CX3_TRK_DAISU_S) CX3_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.CX5_TRK_DAISU_Y) CX5_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.CX5_TRK_DAISU_S) CX5_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.CX8_TRK_DAISU_Y,0)) CX8_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX8_TRK_DAISU_S,0)) CX8_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.CX30_TRK_DAISU_Y,0)) CX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX30_TRK_DAISU_S,0)) CX30_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.MX30_TRK_DAISU_Y,0)) MX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.MX30_TRK_DAISU_S,0)) MX30_TRK_DAISU_S " . "\r\n";

        //20240326 caina ins s
        $strSQL .= ",   SUM(NVL(TR.CX60_TRK_DAISU_Y,0)) CX60_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(NVL(TR.CX60_TRK_DAISU_S,0)) CX60_TRK_DAISU_S " . "\r\n";
        //20240326 caina ins e
        //20240326 LHB ins s
        //20240712 LHB upd s
        // $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_Y,0)) CX80_TRK_DAISU_Y " . "\r\n";
        // $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_S,0)) CX80_TRK_DAISU_S " . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_Y,0)) CX80_TRK_DAISU_Y " . "\r\n";
            $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_S,0)) CX80_TRK_DAISU_S " . "\r\n";
        }
        //20240712 LHB upd e
        //20240326 LHB ins e

        $strSQL .= ",      SUM(NVL(TR.M3S_TRK_DAISU_Y,0)) M3S_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3S_TRK_DAISU_S,0)) M3S_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3H_TRK_DAISU_Y,0)) M3H_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3H_TRK_DAISU_S,0)) M3H_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6S_TRK_DAISU_Y,0)) M6S_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6S_TRK_DAISU_S,0)) M6S_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6W_TRK_DAISU_Y,0)) M6W_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6W_TRK_DAISU_S,0)) M6W_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.ATENZA_TRK_DAISU_Y) ATENZA_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.ATENZA_TRK_DAISU_S) ATENZA_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.AXS_TRK_DAISU_Y) AXS_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.AXS_TRK_DAISU_S) AXS_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.PREMACY_TRK_DAISU_Y) PREMACY_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.PREMACY_TRK_DAISU_S) PREMACY_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.BIANTE_TRK_DAISU_Y) BIANTE_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.BIANTE_TRK_DAISU_S) BIANTE_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.MPV_TRK_DAISU_Y) MPV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.MPV_TRK_DAISU_S) MPV_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.LDSTAR_TRK_DAISU_Y) LDSTAR_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.LDSTAR_TRK_DAISU_S) LDSTAR_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.FMV_TRK_DAISU_Y) FMV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.FMV_TRK_DAISU_S) FMV_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.BONGO_TRK_DAISU_Y) BONGO_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.BONGO_TRK_DAISU_S) BONGO_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.TT_TRK_DAISU_Y) TT_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TT_TRK_DAISU_S) TT_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_Y) KEI_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_S) KEI_TRK_DAISU_S " . "\r\n";

        $strSQL .= " FROM   HBUSYO MST " . "\r\n";

        $strSQL .= " LEFT JOIN HDTTARGETRESULT TR " . "\r\n";

        $strSQL .= " ON     TR.BUSYO_CD = MST.BUSYO_CD " . "\r\n";
        $strSQL .= " AND    TR.TAISYOU_YM = '" . $postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "'" . "\r\n";
        $strSQL .= " AND    TR.BUSYO_CD <> '999'" . "\r\n";
        $strSQL .= " WHERE  1 = 1 " . "\r\n";

        $strSQL = $this->appendBusyoCondition($strSQL, $postData);

        return $strSQL;
    }

    public function SQLZenSyasyu($postData)
    {
        return parent::select($this->SQLZenSyasyuSQL($postData));
    }


    // ***********************************************************************
    // '処 理 名：目標と実績車種内訳View前年同月データを取得するSQL作成
    // '関 数 名：SQLZenSyasyuSQL
    // '引    数：無し
    // '戻 り 値 ：目標と実績車種内訳View前年同月データを取得するSQL
    // '処理説明 ：目標と実績車種内訳View前年同月データを取得するSQLを作成する
    // '**********************************************************************

    public function SQLZenSyasyuSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SUM(NVL(TR.DEMIO_TRK_DAISU_Y,0)) + SUM(NVL(TR.VRW_TRK_DAISU_Y,0)) + SUM(NVL(TR.ATENZA_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.AXS_TRK_DAISU_Y,0)) + SUM(NVL(TR.PREMACY_TRK_DAISU_Y,0)) + SUM(NVL(TR.MPV_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.RX8_TRK_DAISU_Y,0)) + SUM(NVL(TR.CX7_TRK_DAISU_Y,0)) + SUM(NVL(TR.LDSTAR_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.FMV_TRK_DAISU_Y,0)) + SUM(NVL(TR.BONGO_TRK_DAISU_Y,0)) + SUM(NVL(TR.TTD_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.CX3_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.CX5_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.CX8_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.M3S_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.M3H_TRK_DAISU_Y,0)) " . "\r\n";

        $strSQL .= "       + SUM(NVL(TR.M6S_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.M6W_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.M2G_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.CX30_TRK_DAISU_Y,0)) " . "\r\n";

        $strSQL .= "       + SUM(NVL(TR.MX30_TRK_DAISU_Y,0)) " . "\r\n";
        //20240326 caina ins s
        $strSQL .= "+ SUM(NVL(TR.CX60_TRK_DAISU_Y,0))" . "\r\n";
        //20240326 caina ins e
        //20240611 LHB ins s
        //20240712 LHB upd s
        // $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_Y,0))" . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_Y,0))" . "\r\n";
        }
        //20240712 LHB upd e
        //20240611 LHB ins e

        $strSQL .= "       + SUM(NVL(TR.BIANTE_TRK_DAISU_Y,0)) + SUM(NVL(TR.TT_TRK_DAISU_Y,0)) DAISU_Y " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.DEMIO_TRK_DAISU_S,0)) + SUM(NVL(TR.VRW_TRK_DAISU_S,0)) + SUM(NVL(TR.ATENZA_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.AXS_TRK_DAISU_S,0)) + SUM(NVL(TR.PREMACY_TRK_DAISU_S,0)) + SUM(NVL(TR.MPV_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.RX8_TRK_DAISU_S,0)) + SUM(NVL(TR.CX7_TRK_DAISU_S,0)) + SUM(NVL(TR.LDSTAR_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.FMV_TRK_DAISU_S,0)) + SUM(NVL(TR.BONGO_TRK_DAISU_S,0)) + SUM(NVL(TR.TTD_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.CX3_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.CX5_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.CX8_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.M3S_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.M3H_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.M6S_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.M6W_TRK_DAISU_S,0)) " . "\r\n";

        $strSQL .= "       + SUM(NVL(TR.M2G_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "       + SUM(NVL(TR.CX30_TRK_DAISU_S,0)) " . "\r\n";

        $strSQL .= "       + SUM(NVL(TR.MX30_TRK_DAISU_S,0)) " . "\r\n";
        //20240326 caina ins s
        $strSQL .= "+ SUM(NVL(TR.CX60_TRK_DAISU_S,0))" . "\r\n";
        //20240326 caina ins e
        //20240611 LHB ins s
        //20240712 LHB upd s
        // $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_S,0))" . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_S,0))" . "\r\n";
        }
        //20240712 LHB upd e
        //20240611 LHB ins e

        $strSQL .= "       + SUM(NVL(TR.BIANTE_TRK_DAISU_S,0)) + SUM(NVL(TR.TT_TRK_DAISU_S,0)) DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.DEMIO_TRK_DAISU_Y) DEMIO_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.DEMIO_TRK_DAISU_S) DEMIO_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.M2G_TRK_DAISU_Y,0)) M2G_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M2G_TRK_DAISU_S,0)) M2G_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.VRW_TRK_DAISU_Y) VRW_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.VRW_TRK_DAISU_S) VRW_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.CX3_TRK_DAISU_Y) CX3_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.CX3_TRK_DAISU_S) CX3_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.CX5_TRK_DAISU_Y) CX5_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.CX5_TRK_DAISU_S) CX5_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX8_TRK_DAISU_Y,0)) CX8_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX8_TRK_DAISU_S,0)) CX8_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.CX30_TRK_DAISU_Y,0)) CX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX30_TRK_DAISU_S,0)) CX30_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.MX30_TRK_DAISU_Y,0)) MX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.MX30_TRK_DAISU_S,0)) MX30_TRK_DAISU_S " . "\r\n";

        //20240326 caina ins s
        $strSQL .= ",      SUM(NVL(TR.CX60_TRK_DAISU_Y,0)) CX60_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX60_TRK_DAISU_S,0)) CX60_TRK_DAISU_S " . "\r\n";
        //20240326 caina ins e
        //20240611 LHB ins s
        //20240712 LHB upd s
        // $strSQL .= ",      SUM(NVL(TR.CX80_TRK_DAISU_Y,0)) CX80_TRK_DAISU_Y " . "\r\n";
        // $strSQL .= ",      SUM(NVL(TR.CX80_TRK_DAISU_S,0)) CX80_TRK_DAISU_S " . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= ",      SUM(NVL(TR.CX80_TRK_DAISU_Y,0)) CX80_TRK_DAISU_Y " . "\r\n";
            $strSQL .= ",      SUM(NVL(TR.CX80_TRK_DAISU_S,0)) CX80_TRK_DAISU_S " . "\r\n";
        }
        //20240712 LHB upd e
        //20240611 LHB ins e

        $strSQL .= ",      SUM(NVL(TR.M3S_TRK_DAISU_Y,0)) M3S_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3S_TRK_DAISU_S,0)) M3S_TRK_DAISU_S" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3H_TRK_DAISU_Y,0)) M3H_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3H_TRK_DAISU_S,0)) M3H_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6S_TRK_DAISU_Y,0)) M6S_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6S_TRK_DAISU_S,0)) M6S_TRK_DAISU_S" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6W_TRK_DAISU_Y,0)) M6W_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6W_TRK_DAISU_S,0)) M6W_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.ATENZA_TRK_DAISU_Y) ATENZA_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.ATENZA_TRK_DAISU_S) ATENZA_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.AXS_TRK_DAISU_Y) AXS_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.AXS_TRK_DAISU_S) AXS_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.PREMACY_TRK_DAISU_Y) PREMACY_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.PREMACY_TRK_DAISU_S) PREMACY_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.BIANTE_TRK_DAISU_Y) BIANTE_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.BIANTE_TRK_DAISU_S) BIANTE_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.MPV_TRK_DAISU_Y) MPV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.MPV_TRK_DAISU_S) MPV_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.LDSTAR_TRK_DAISU_Y) LDSTAR_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.LDSTAR_TRK_DAISU_S) LDSTAR_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.FMV_TRK_DAISU_Y) FMV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.FMV_TRK_DAISU_S) FMV_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.BONGO_TRK_DAISU_Y) BONGO_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.BONGO_TRK_DAISU_S) BONGO_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TT_TRK_DAISU_Y) TT_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TT_TRK_DAISU_S) TT_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_Y) KEI_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_S) KEI_TRK_DAISU_S " . "\r\n";

        $strSQL .= " FROM   HDTTARGETRESULT TR " . "\r\n";

        $strSQL .= "WHERE  TR.TAISYOU_YM = '" . ($postData['txtbDuring'] - 1) . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "'" . "\r\n";

        return $strSQL;
    }

    public function SQL6($postData)
    {
        return parent::select($this->SQL6SQL($postData));
    }


    //  ***********************************************************************
    //  '処 理 名：目標と実績車種内訳Viewその他行データを取得するSQL作成
    //  '関 数 名：SQL6SQL
    //  '引    数：無し
    //  '戻 り 値 ：目標と実績車種内訳Viewその他行データを取得するSQL
    //  '処理説明 ：目標と実績車種内訳Viewその他行データを取得するSQLを作成する
    //  '**********************************************************************

    public function SQL6SQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT TR.BUSYO_CD " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.DEMIO_TRK_DAISU_Y,0)) + SUM(NVL(TR.VRW_TRK_DAISU_Y,0)) + SUM(NVL(TR.ATENZA_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.AXS_TRK_DAISU_Y,0)) + SUM(NVL(TR.PREMACY_TRK_DAISU_Y,0)) + SUM(NVL(TR.MPV_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.BIANTE_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX5_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX3_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX8_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3S_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3H_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6S_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6W_TRK_DAISU_Y,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.M2G_TRK_DAISU_Y,0))" . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX30_TRK_DAISU_Y,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.MX30_TRK_DAISU_Y,0)) " . "\r\n";
        //20240326 caina ins s
        $strSQL .= "+ SUM(NVL(TR.CX60_TRK_DAISU_Y,0))" . "\r\n";
        //20240326 caina ins e
        //20240611 LHB ins s
        //20240712 LHB upd s
        // $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_Y,0))" . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_Y,0))" . "\r\n";
        }
        //20240712 LHB upd e
        //20240611 LHB ins e

        $strSQL .= "+ SUM(NVL(TR.RX8_TRK_DAISU_Y,0))+ SUM(NVL(TR.CX7_TRK_DAISU_Y,0)) + SUM(NVL(TR.LDSTAR_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.FMV_TRK_DAISU_Y,0)) + SUM(NVL(TR.BONGO_TRK_DAISU_Y,0)) + SUM(NVL(TR.TTD_TRK_DAISU_Y,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.TT_TRK_DAISU_Y,0)) DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.DEMIO_TRK_DAISU_S,0)) + SUM(NVL(TR.VRW_TRK_DAISU_S,0)) + SUM(NVL(TR.ATENZA_TRK_DAISU_S,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.AXS_TRK_DAISU_S,0)) + SUM(NVL(TR.PREMACY_TRK_DAISU_S,0)) + SUM(NVL(TR.MPV_TRK_DAISU_S,0))	 " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.BIANTE_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX3_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX5_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX8_TRK_DAISU_S,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.M3S_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M3H_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6S_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.M6W_TRK_DAISU_S,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.M2G_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.CX30_TRK_DAISU_S,0)) " . "\r\n";

        $strSQL .= "+ SUM(NVL(TR.MX30_TRK_DAISU_S,0)) " . "\r\n";
        //20240326 caina ins s
        $strSQL .= "+ SUM(NVL(TR.CX60_TRK_DAISU_S,0))" . "\r\n";
        //20240326 caina ins e
        //20240611 LHB ins s
        //20240712 LHB upd s
        // 	$strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_S,0))" . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= "+ SUM(NVL(TR.CX80_TRK_DAISU_S,0))" . "\r\n";
        }
        //20240712 LHB upd e
        //20240611 LHB ins e

        $strSQL .= "+ SUM(NVL(TR.RX8_TRK_DAISU_S,0)) + SUM(NVL(TR.CX7_TRK_DAISU_S,0)) + SUM(NVL(TR.LDSTAR_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.FMV_TRK_DAISU_S,0)) + SUM(NVL(TR.BONGO_TRK_DAISU_S,0)) + SUM(NVL(TR.TTD_TRK_DAISU_S,0)) " . "\r\n";
        $strSQL .= "+ SUM(NVL(TR.TT_TRK_DAISU_S,0)) DAISU_S" . "\r\n";

        $strSQL .= ",      SUM(TR.DEMIO_TRK_DAISU_Y) DEMIO_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.DEMIO_TRK_DAISU_S) DEMIO_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.M2G_TRK_DAISU_Y,0)) M2G_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M2G_TRK_DAISU_S,0)) M2G_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.VRW_TRK_DAISU_Y) VRW_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.VRW_TRK_DAISU_S) VRW_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.CX3_TRK_DAISU_Y) CX3_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.CX3_TRK_DAISU_S) CX3_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.CX5_TRK_DAISU_Y) CX5_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.CX5_TRK_DAISU_S) CX5_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX8_TRK_DAISU_Y,0)) CX8_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX8_TRK_DAISU_S,0)) CX8_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.CX30_TRK_DAISU_Y,0)) CX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.CX30_TRK_DAISU_S,0)) CX30_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(NVL(TR.MX30_TRK_DAISU_Y,0)) MX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.MX30_TRK_DAISU_S,0)) MX30_TRK_DAISU_S " . "\r\n";

        //20240326 caina ins s
        $strSQL .= ",   SUM(NVL(TR.CX60_TRK_DAISU_Y,0)) CX60_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",   SUM(NVL(TR.CX60_TRK_DAISU_S,0)) CX60_TRK_DAISU_S " . "\r\n";
        //20240326 caina ins e
        //20240611 LHB ins s
        //20240712 LHB upd s
        // $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_Y,0)) CX80_TRK_DAISU_Y " . "\r\n";
        // $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_S,0)) CX80_TRK_DAISU_S " . "\r\n";
        if ($postData['isExit'] === '1') {
            $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_Y,0)) CX80_TRK_DAISU_Y " . "\r\n";
            $strSQL .= ",   SUM(NVL(TR.CX80_TRK_DAISU_S,0)) CX80_TRK_DAISU_S " . "\r\n";
        }
        //20240712 LHB upd e
        //20240611 LHB ins e

        $strSQL .= ",      SUM(NVL(TR.M3S_TRK_DAISU_Y,0)) M3S_TRK_DAISU_Y" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3S_TRK_DAISU_S,0)) M3S_TRK_DAISU_S" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3H_TRK_DAISU_Y,0)) M3H_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M3H_TRK_DAISU_S,0)) M3H_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6S_TRK_DAISU_Y,0)) M6S_TRK_DAISU_Y" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6S_TRK_DAISU_S,0)) M6S_TRK_DAISU_S" . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6W_TRK_DAISU_Y,0)) M6W_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(NVL(TR.M6W_TRK_DAISU_S,0)) M6W_TRK_DAISU_S " . "\r\n";

        $strSQL .= ",      SUM(TR.ATENZA_TRK_DAISU_Y) ATENZA_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.ATENZA_TRK_DAISU_S) ATENZA_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.AXS_TRK_DAISU_Y) AXS_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.AXS_TRK_DAISU_S) AXS_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.PREMACY_TRK_DAISU_Y) PREMACY_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.PREMACY_TRK_DAISU_S) PREMACY_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.BIANTE_TRK_DAISU_Y) BIANTE_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.BIANTE_TRK_DAISU_S) BIANTE_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.MPV_TRK_DAISU_Y) MPV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.MPV_TRK_DAISU_S) MPV_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.LDSTAR_TRK_DAISU_Y) LDSTAR_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.LDSTAR_TRK_DAISU_S) LDSTAR_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.FMV_TRK_DAISU_Y) FMV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.FMV_TRK_DAISU_S) FMV_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.BONGO_TRK_DAISU_Y) BONGO_TRK_DAISU_Y " . "\r\n";

        $strSQL .= ",      SUM(TR.BONGO_TRK_DAISU_S) BONGO_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.TT_TRK_DAISU_Y) TT_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.TT_TRK_DAISU_S) TT_TRK_DAISU_S " . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_Y) KEI_TRK_DAISU_Y " . "\r\n";
        $strSQL .= ",      SUM(TR.KEI_TRK_DAISU_S) KEI_TRK_DAISU_S " . "\r\n";

        $strSQL .= " FROM   HDTTARGETRESULT TR " . "\r\n";

        $strSQL .= "    WHERE  TR.TAISYOU_YM = '" . $postData['txtbDuring'] . "" . str_pad($postData['ddlMonth'], 2, '0', STR_PAD_LEFT) . "'" . "\r\n";
        $strSQL .= "    AND    TR.BUSYO_CD = '999'" . "\r\n";
        $strSQL .= " GROUP BY TR.BUSYO_CD " . "\r\n";

        return $strSQL;
    }

    public function fncSQLTARGETRESULTTABLE($targetYm)
    {
        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "    TAISYOU_YM," . "\r\n";
        if ($targetYm >= "201410") {
            $strSQL .= "    CASE WHEN BUSYO_CD='443' THEN '441'" . "\r\n";
            $strSQL .= "         WHEN BUSYO_CD='463' THEN '461'" . "\r\n";
            $strSQL .= "         ELSE BUSYO_CD END as BUSYO_CD," . "\r\n";
        } else {
            $strSQL .= "BUSYO_CD," . "\r\n";
        }
        $strSQL .= "sum(GENRI_MOKUHYO) GENRI_MOKUHYO," . "\r\n";
        $strSQL .= "sum(GENRI_YOSOU) GENRI_YOSOU," . "\r\n";
        $strSQL .= "sum(GENRI_JISSEKI) GENRI_JISSEKI," . "\r\n";
        $strSQL .= "sum(URIMOKU_MAIN) URIMOKU_MAIN," . "\r\n";
        $strSQL .= "sum(URIMOKU_TACHANEL) URIMOKU_TACHANEL," . "\r\n";
        $strSQL .= "sum(URIYOSOU_MAIN_Y) URIYOSOU_MAIN_Y," . "\r\n";
        $strSQL .= "sum(URIYOSOU_MAIN_S) URIYOSOU_MAIN_S," . "\r\n";
        $strSQL .= "sum(URIYOSOU_KEI_Y) URIYOSOU_KEI_Y," . "\r\n";
        $strSQL .= "sum(URIYOSOU_KEI_S) URIYOSOU_KEI_S," . "\r\n";
        $strSQL .= "sum(URIYOSOU_VOLVO_Y) URIYOSOU_VOLVO_Y," . "\r\n";
        $strSQL .= "sum(URIYOSOU_VOLVO_S) URIYOSOU_VOLVO_S," . "\r\n";
        $strSQL .= "sum(TRKDAISU_JIJI_Y) TRKDAISU_JIJI_Y," . "\r\n";
        $strSQL .= "sum(TRKDAISU_JIJI_S) TRKDAISU_JIJI_S," . "\r\n";
        $strSQL .= "sum(TRKDAISU_FUKUSHI_Y) TRKDAISU_FUKUSHI_Y," . "\r\n";
        $strSQL .= "sum(TRKDAISU_FUKUSHI_S) TRKDAISU_FUKUSHI_S," . "\r\n";
        $strSQL .= "sum(TRKDAISU_TAJI_Y) TRKDAISU_TAJI_Y," . "\r\n";
        $strSQL .= "sum(TRKDAISU_TAJI_S) TRKDAISU_TAJI_S," . "\r\n";
        $strSQL .= "sum(TRKDAISU_JITA_Y) TRKDAISU_JITA_Y," . "\r\n";
        $strSQL .= "sum(TRKDAISU_JITA_S) TRKDAISU_JITA_S," . "\r\n";
        $strSQL .= "sum(DEMIO_TRK_DAISU_Y) DEMIO_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(DEMIO_TRK_DAISU_S) DEMIO_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(NVL(M2G_TRK_DAISU_Y,0)) M2G_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(NVL(M2G_TRK_DAISU_S,0)) M2G_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(VRW_TRK_DAISU_Y) VRW_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(VRW_TRK_DAISU_S) VRW_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(CX3_TRK_DAISU_Y) CX3_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(CX3_TRK_DAISU_S ) CX3_TRK_DAISU_S, " . "\r\n";
        $strSQL .= "sum(CX5_TRK_DAISU_Y) CX5_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(CX5_TRK_DAISU_S) CX5_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(NVL(CX8_TRK_DAISU_Y,0)) CX8_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(NVL(CX8_TRK_DAISU_S,0)) CX8_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(NVL(CX30_TRK_DAISU_Y,0)) CX30_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(NVL(CX30_TRK_DAISU_S,0)) CX30_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(NVL(MX30_TRK_DAISU_Y,0)) MX30_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(NVL(MX30_TRK_DAISU_S,0)) MX30_TRK_DAISU_S," . "\r\n";

        $strSQL .= "sum(NVL(M3S_TRK_DAISU_Y,0)) M3S_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(NVL(M3S_TRK_DAISU_S,0)) M3S_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(NVL(M3H_TRK_DAISU_Y,0)) M3H_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(NVL(M3H_TRK_DAISU_S,0)) M3H_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(NVL(M6S_TRK_DAISU_Y,0)) M6S_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(NVL(M6S_TRK_DAISU_S,0)) M6S_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(NVL(M6W_TRK_DAISU_Y,0)) M6W_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(NVL(M6W_TRK_DAISU_S,0)) M6W_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(ATENZA_TRK_DAISU_Y) ATENZA_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(ATENZA_TRK_DAISU_S) ATENZA_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(AXS_TRK_DAISU_Y) AXS_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(AXS_TRK_DAISU_S) AXS_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(BIANTE_TRK_DAISU_Y) BIANTE_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(BIANTE_TRK_DAISU_S) BIANTE_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(PREMACY_TRK_DAISU_Y) PREMACY_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(PREMACY_TRK_DAISU_S) PREMACY_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(MPV_TRK_DAISU_Y) MPV_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(MPV_TRK_DAISU_S) MPV_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(LDSTAR_TRK_DAISU_Y) LDSTAR_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(LDSTAR_TRK_DAISU_S) LDSTAR_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(FMV_TRK_DAISU_Y) FMV_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(FMV_TRK_DAISU_S) FMV_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(BONGO_TRK_DAISU_Y) BONGO_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(BONGO_TRK_DAISU_S) BONGO_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(TTD_TRK_DAISU_Y) TTD_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(TTD_TRK_DAISU_S) TTD_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(TT_TRK_DAISU_Y) TT_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(TT_TRK_DAISU_S) TT_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(KEI_TRK_DAISU_Y) KEI_TRK_DAISU_Y," . "\r\n";
        $strSQL .= "sum(KEI_TRK_DAISU_S) KEI_TRK_DAISU_S," . "\r\n";
        $strSQL .= "sum(TRKDAISU_KEI_JIJI_Y) TRKDAISU_KEI_JIJI_Y," . "\r\n";
        $strSQL .= "sum(TRKDAISU_KEI_JIJI_S) TRKDAISU_KEI_JIJI_S," . "\r\n";
        $strSQL .= "sum(TRKDAISU_KEI_TAJI_Y) TRKDAISU_KEI_TAJI_Y," . "\r\n";
        $strSQL .= "sum(TRKDAISU_KEI_TAJI_S) TRKDAISU_KEI_TAJI_S," . "\r\n";
        $strSQL .= "sum(TRKDAISU_KEI_JITA_Y) TRKDAISU_KEI_JITA_Y," . "\r\n";
        $strSQL .= "sum(TRKDAISU_KEI_JITA_S) TRKDAISU_KEI_JITA_S," . "\r\n";

        $strSQL .= "sum(TRKDAISU_KEI_FUKUSHI_Y) TRKDAISU_KEI_FUKUSHI_Y," . "\r\n";
        $strSQL .= "sum(TRKDAISU_KEI_FUKUSHI_S) TRKDAISU_KEI_FUKUSHI_S," . "\r\n";

        $strSQL .= "sum(TRKDAISU_RENTA_Y) TRKDAISU_RENTA_Y," . "\r\n";
        $strSQL .= "sum(TRKDAISU_RENTA_S) TRKDAISU_RENTA_S," . "\r\n";
        $strSQL .= "sum(SHURI_HOKEN) SHURI_HOKEN," . "\r\n";
        $strSQL .= "sum(SHURI_LEASE) SHURI_LEASE," . "\r\n";
        $strSQL .= "sum(SHURI_LOAN) SHURI_LOAN," . "\r\n";
        $strSQL .= "sum(SHURI_KIBOU) SHURI_KIBOU," . "\r\n";
        $strSQL .= "sum(SHURI_P753) SHURI_P753," . "\r\n";
        $strSQL .= "sum(SHURI_PMENTE) SHURI_PMENTE," . "\r\n";
        $strSQL .= "sum(SHURI_BODYCOAT) SHURI_BODYCOAT," . "\r\n";
        $strSQL .= "sum(SHURI_JAF) SHURI_JAF," . "\r\n";
        $strSQL .= "sum(SHURI_OSS) SHURI_OSS," . "\r\n";
        $strSQL .= "sum(URIYOSOU_SONOTA_Y) URIYOSOU_SONOTA_Y," . "\r\n";
        $strSQL .= "sum(URIYOSOU_SONOTA_S) URIYOSOU_SONOTA_S " . "\r\n";
        $strSQL .= " FROM HDTTARGETRESULT " . "\r\n";

        $strSQL .= " WHERE 1=1 " . "\r\n";

        if ($targetYm >= "201605") {
            $strSQL .= " AND BUSYO_CD not in ('471') " . "\r\n";
        }
        if ($targetYm >= "201704") {
            $strSQL .= " AND BUSYO_CD not in ('271') " . "\r\n";
        }

        $strSQL .= "GROUP BY" . "\r\n";
        $strSQL .= "    TAISYOU_YM," . "\r\n";

        if ($targetYm >= "201410") {
            $strSQL .= "    CASE WHEN BUSYO_CD='443' THEN '441'" . "\r\n";
            $strSQL .= "         WHEN BUSYO_CD='463' THEN '461'" . "\r\n";
            $strSQL .= "         ELSE BUSYO_CD END " . "\r\n";
        } else {
            $strSQL .= "BUSYO_CD " . "\r\n";
        }

        return $strSQL;
    }

    // 20240712 LHB upd s
    //CX80存在チェックを行う
    // 20240909 LHB upd s
    // public function checkCX80SQL($BusyoCD)
    // {
    public function checkCX80SQL()
    {
        // 20240909 LHB upd e
        $strSQL = "";
        $strSQL .= "SELECT count(*) as isEXIT" . "\r\n";
        $strSQL .= "FROM USER_TAB_COLUMNS " . "\r\n";
        $strSQL .= "WHERE TABLE_NAME = 'HDTTARGETRESULT' " . "\r\n";
        $strSQL .= "AND column_name='CX80_TRK_DAISU_Y' " . "\r\n";

        return parent::select($strSQL);
    }
    // 20240712 LHB upd e

}
