<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                                    担当
 * YYYYMMDD           #ID                                     XXXXXX                                 FCSDL
 * 20240227           20240213_機能改善要望対応 NO6    「科目マスタの使用フラグ、使用フラグ名は撤廃」        YIN
 * 20240318           本番障害.xlsx NO6                仕訳伝票を表示しない。支払伝票のみを抽出する    lujunxia
 * 20240322           本番障害.xlsx NO8         科目名、補助科目名のいずれかしか表示していない箇所があるが  caina
 * 20240606           CSV再出力   CSV出力後に帳票を修正したりした場合、CSV再出力一覧の並び順が変わって   lujunxia
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKReOut4ZenGin extends ClsComDb
{
    function FncGetSql_CSV($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "       HDPOUTGROUPDATA_ZENGIN.CSV_GROUP_NO AS GROUP_NO" . "\r\n";
        $strSQL .= ",      SUBSTRB(HDPOUTGROUPDATA_ZENGIN.CSV_GROUP_NM,1,24) AS CSV_GROUP_NM" . "\r\n";
        $strSQL .= ",      TO_CHAR(HDPOUTGROUPDATA_ZENGIN.CSV_OUT_DT,'YYYY/MM/DD HH24:MI:SS') AS CSV_OUT_DT" . "\r\n";
        $strSQL .= ",      TO_CHAR(HDPOUTGROUPDATA_ZENGIN.UPD_DATE,'YYYYMMDD') AS GROUPDATE" . "\r\n";
        $strSQL .= ",      SUBSTR(HDPOUTGROUPDATA_ZENGIN.KEIRI_DT,1,4) || '/' || SUBSTR(HDPOUTGROUPDATA_ZENGIN.KEIRI_DT,5,2) || '/' || SUBSTR(HDPOUTGROUPDATA_ZENGIN.KEIRI_DT,7,2) AS KEIRI_DT" . "\r\n";
        $strSQL .= ",      SUM(HDPSHIWAKEDATA.ZEIKM_GK) AS SUMMONEY" . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "      HDPOUTGROUPDATA_ZENGIN " . "\r\n";
        $strSQL .= ",     HHAIZOKU " . "\r\n";
        $strSQL .= ",     HDPSHIWAKEDATA " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "     HHAIZOKU.SYAIN_NO = HDPOUTGROUPDATA_ZENGIN.UPD_SYA_CD" . "\r\n";
        $strSQL .= " AND HHAIZOKU.START_DATE <= TO_CHAR(HDPOUTGROUPDATA_ZENGIN.UPD_DATE,'yyyymmdd')" . "\r\n";
        $strSQL .= " AND NVL(HHAIZOKU.END_DATE,'99999999') >= TO_CHAR(HDPOUTGROUPDATA_ZENGIN.UPD_DATE,'yyyymmdd')" . "\r\n";
        $strSQL .= " AND HDPOUTGROUPDATA_ZENGIN.CSV_GROUP_NO = HDPSHIWAKEDATA.CSV_GROUP_NO" . "\r\n";
        //20240318 lujunxia ins s
        //仕訳伝票を表示しない。支払伝票のみを抽出する
        $strSQL .= "AND HDPSHIWAKEDATA.DENPY_KB = '2'" . "\r\n";
        //20240318 lujunxia ins e
        if ($postData['ltxtBusyoCD'] != '') {
            $strSQL .= "AND   HHAIZOKU.BUSYO_CD = '@BUSYOCD' " . "\r\n";
            $strSQL = str_replace("@BUSYOCD", $postData['ltxtBusyoCD'], $strSQL);
        }
        if ($postData['ltxtTantouCD'] != '') {
            $strSQL .= "AND   HDPOUTGROUPDATA_ZENGIN.UPD_SYA_CD ='@TANTOUCD' " . "\r\n";
            $strSQL = str_replace("@TANTOUCD", $postData['ltxtTantouCD'], $strSQL);
        }

        if ($postData['CSVStart'] != '') {
            $strSQL .= "       AND    TO_CHAR(CSV_OUT_DT,'YYYYMMDD') >= '@SHIHARAIFROM' " . "\r\n";
            $strSQL = str_replace("@SHIHARAIFROM", str_replace("/", "", $postData['CSVStart']), $strSQL);
        }

        if ($postData['CSVEnd'] != '') {
            $strSQL .= "        AND    TO_CHAR(CSV_OUT_DT,'YYYYMMDD') <= '@SHIHARAIEND' " . "\r\n";
            $strSQL = str_replace("@SHIHARAIEND", str_replace("/", "", $postData['CSVEnd']), $strSQL);
        }

        if ($postData['txtGroupName'] != '') {
            $strSQL .= " AND   CSV_GROUP_NM LIKE '@GROUPNM%' " . "\r\n";
            $strSQL = str_replace("@GROUPNM", $postData['txtGroupName'], $strSQL);
        }

        $strSQL .= " GROUP BY  " . "\r\n";
        $strSQL .= "       HDPOUTGROUPDATA_ZENGIN.CSV_GROUP_NO,HDPOUTGROUPDATA_ZENGIN.CSV_GROUP_NM, HDPOUTGROUPDATA_ZENGIN.CSV_OUT_DT,HDPOUTGROUPDATA_ZENGIN.UPD_DATE " . "\r\n";
        $strSQL .= " ,    HDPOUTGROUPDATA_ZENGIN.KEIRI_DT " . "\r\n";
        $strSQL .= "  ORDER BY  " . "\r\n";
        $strSQL .= "   HDPOUTGROUPDATA_ZENGIN.CSV_GROUP_NO DESC  " . "\r\n";

        return $strSQL;
    }

    //仕訳データの取得
    function fncSelGroupShiwakeDataSql($postData)
    {
        //既に指定されたグループで出力されている証憑の最新の枝番号のデータを取得する
        $strSQL = "";
        $strSQL .= "SELECT BASE.SYOHY_NO SYOHYO_NO " . "\r\n";
        $strSQL .= ",      BASE.EDA_NO" . "\r\n";
        $strSQL .= ",      (BASE.SYOHY_NO || BASE.EDA_NO) SYOHYO_NO_VIEW" . "\r\n";
        //20240322 caina upd s
        // $strSQL .= ",      DECODE(BASE.L_KOUMK_CD,NULL,KAR.KAMOK_NAME,KAR.SUB_KAMOK_NAME) KARIKATA" . "\r\n";
        $strSQL .= ",         KAR.KAMOK_NAME AS L_KAMOKU" . "\r\n";
        $strSQL .= ",         KAR.SUB_KAMOK_NAME AS L_KOUMKU" . "\r\n";
        //20240322 caina upd e
        $strSQL .= ",      CASE BASE.DENPY_KB  " . "\r\n";
        //20240322 caina upd s
        // $strSQL .= "       WHEN '1' THEN DECODE(BASE.R_KOUMK_CD,NULL,KAS.KAMOK_NAME,KAS.SUB_KAMOK_NAME)" . "\r\n";
        // $strSQL .= "       ELSE (CASE WHEN HMS.MOJI1 IS NULL THEN HMS.MEISYOU ELSE HMS.MOJI1 END) END  KASHIKATA" . "\r\n";
        $strSQL .= "       WHEN '1' THEN KAS.KAMOK_NAME ELSE HMS.MEISYOU END AS R_KAMOKU," . "\r\n";
        $strSQL .= "      CASE BASE.DENPY_KB  " . "\r\n";
        $strSQL .= "       WHEN '1' THEN KAS.SUB_KAMOK_NAME ELSE HMS.MOJI1 END AS R_KOUMKU" . "\r\n";
        //20240322 caina upd E
        $strSQL .= ",      V.GOUKEI KINGAKU" . "\r\n";
        $strSQL .= ",      V.UPD_FLG" . "\r\n";
        $strSQL .= ",      '1' CHK_CSV_STATUS" . "\r\n";
        $strSQL .= ",      'TRUE' CHK_CSV_FLG" . "\r\n";
        $strSQL .= ",      TO_CHAR(BASE.UPD_DATE,'YYYY/MM/DD HH24:MI:SS') UPD_DATE" . "\r\n";
        $strSQL .= ",      ROW_NUMBER() OVER(ORDER BY BASE.CSV_OUT_ORDER, BASE.SYOHY_NO) - 1 RENBAN" . "\r\n";
        $strSQL .= ",      ROW_NUMBER() OVER(ORDER BY BASE.CSV_OUT_ORDER, BASE.SYOHY_NO) SEQNO" . "\r\n";
        $strSQL .= " FROM   HDPSHIWAKEDATA BASE" . "\r\n";
        $strSQL .= " INNER JOIN " . "\r\n";
        $strSQL .= "         (" . "\r\n";
        $strSQL .= "	SELECT GK.SYOHY_NO" . "\r\n";
        $strSQL .= "	,      GK.EDA_NO" . "\r\n";
        $strSQL .= "	,      MIN(GK.GYO_NO) GYO_NO" . "\r\n";
        $strSQL .= "	,      SUM(GK.ZEIKM_GK) GOUKEI" . "\r\n";
        $strSQL .= "	,      MAX(CASE WHEN SEL.M_EDA_NO = GK.EDA_NO THEN 0 ELSE 1 END) UPD_FLG" . "\r\n";
        $strSQL .= "	FROM   HDPSHIWAKEDATA GK" . "\r\n";
        $strSQL .= " INNER JOIN " . "\r\n";
        $strSQL .= "         (" . "\r\n";
        $strSQL .= "		  SELECT SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "		  ,      KEI.M_EDA_NO" . "\r\n";
        $strSQL .= "		  ,      MAX(SWK.EDA_NO) EDA_NO" . "\r\n";
        $strSQL .= "		  FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= " 		  INNER JOIN " . "\r\n";
        $strSQL .= "		  (SELECT SYOHY_NO" . "\r\n";
        $strSQL .= "		  ,      MAX(EDA_NO) M_EDA_NO" . "\r\n";
        $strSQL .= "	      FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "		  WHERE  CSV_GROUP_NO = @GROUPNO" . "\r\n";
        $strSQL .= "		  GROUP BY SYOHY_NO" . "\r\n";
        $strSQL .= "		   ) KEI" . "\r\n";
        $strSQL .= "		  ON     SWK.SYOHY_NO = KEI.SYOHY_NO" . "\r\n";
        $strSQL .= "		  GROUP BY SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "		  ,        KEI.M_EDA_NO" . "\r\n";
        $strSQL .= "		   ) SEL" . "\r\n";
        $strSQL .= "      ON     SEL.SYOHY_NO = GK.SYOHY_NO" . "\r\n";
        $strSQL .= "      AND    SEL.EDA_NO = GK.EDA_NO" . "\r\n";
        $strSQL .= "      GROUP BY GK.SYOHY_NO" . "\r\n";
        $strSQL .= "      ,      GK.EDA_NO" . "\r\n";
        $strSQL .= "       ) V" . "\r\n";
        $strSQL .= " ON      BASE.SYOHY_NO = V.SYOHY_NO" . "\r\n";
        $strSQL .= " AND     BASE.EDA_NO = V.EDA_NO" . "\r\n";
        $strSQL .= " AND     BASE.GYO_NO = V.GYO_NO" . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "        HDK_MST_KAMOKU KAR " . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= " ON     KAR.KAMOK_CD = BASE.L_KAMOK_CD AND DECODE(BASE.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(BASE.L_KOUMK_CD,'999999') AND KAR.USE_FLG = '1' " . "\r\n";
        $strSQL .= " ON     KAR.KAMOK_CD = BASE.L_KAMOK_CD AND DECODE(BASE.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(BASE.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= " LEFT JOIN HDK_MST_KAMOKU KAS" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= " ON     LPAD(KAS.KAMOK_CD,5,'0') = LPAD(BASE.R_KAMOK_CD,5,'0') AND DECODE(BASE.R_KOUMK_CD,NULL,NVL(LPAD(TRIM(KAS.SUB_KAMOK_CD),5,'0'),'999999'),lpad(KAS.SUB_KAMOK_CD,5,'0')) = NVL(lpad(BASE.R_KOUMK_CD,5,'0'),'999999') AND KAS.USE_FLG = '1' " . "\r\n";
        $strSQL .= " ON     LPAD(KAS.KAMOK_CD,5,'0') = LPAD(BASE.R_KAMOK_CD,5,'0') AND DECODE(BASE.R_KOUMK_CD,NULL,NVL(LPAD(TRIM(KAS.SUB_KAMOK_CD),5,'0'),'999999'),lpad(KAS.SUB_KAMOK_CD,5,'0')) = NVL(lpad(BASE.R_KOUMK_CD,5,'0'),'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HMEISYOUMST HMS" . "\r\n";
        $strSQL .= "ON	TO_NUMBER(SUBSTR(HMS.MEISYOU_CD,1,1) || HMS.SUCHI1) = TO_NUMBER(BASE.SHR_KAMOK_KB || BASE.R_KAMOK_CD) AND DECODE(BASE.R_KOUMK_CD,NULL,NVL(TRIM(HMS.SUCHI2),'999999'),HMS.SUCHI2) = NVL(BASE.R_KOUMK_CD,'999999') AND HMS.MEISYOU_ID = 'DK'" . "\r\n";
        $strSQL .= " WHERE BASE.DEL_FLG = '0'" . "\r\n";
        //20240318 lujunxia ins s
        //仕訳伝票を表示しない。支払伝票のみを抽出する
        $strSQL .= "AND BASE.DENPY_KB = '2'" . "\r\n";
        //20240318 lujunxia ins e
        $strSQL .= " ORDER BY BASE.CSV_OUT_ORDER " . "\r\n";
        $strSQL .= " ,        BASE.SYOHY_NO " . "\r\n";

        $strSQL = str_replace("@GROUPNO", $postData['strGroup_no'], $strSQL);

        return $strSQL;

    }

    function fncSelSyohyShiwakeDataSql($postData)
    {
        //既に指定されたグループで出力されている証憑の最新の枝番号のデータを取得する
        $strSQL = "";
        $strSQL .= "SELECT BASE.SYOHY_NO SYOHYO_NO " . "\r\n";
        $strSQL .= ",      BASE.EDA_NO" . "\r\n";
        $strSQL .= ",      (BASE.SYOHY_NO || BASE.EDA_NO) SYOHYO_NO_VIEW" . "\r\n";
        //20240322 caina upd s
        // $strSQL .= ",      DECODE(BASE.L_KOUMK_CD,NULL,KAR.KAMOK_NAME,KAR.SUB_KAMOK_NAME) KARIKATA" . "\r\n";
        $strSQL .= ",         KAR.KAMOK_NAME AS L_KAMOKU" . "\r\n";
        $strSQL .= ",         KAR.SUB_KAMOK_NAME AS L_KOUMKU" . "\r\n";
        //20240322 caina upd e
        $strSQL .= ",      CASE BASE.DENPY_KB  " . "\r\n";
        //20240322 caina upd s
        // $strSQL .= "       WHEN '1' THEN DECODE(BASE.R_KOUMK_CD,NULL,KAS.KAMOK_NAME,KAS.SUB_KAMOK_NAME)" . "\r\n";
        // $strSQL .= "       ELSE (CASE WHEN HMS.MOJI1 IS NULL THEN HMS.MEISYOU ELSE HMS.MOJI1 END) END  KASHIKATA" . "\r\n";
        $strSQL .= "       WHEN '1' THEN KAS.KAMOK_NAME ELSE HMS.MEISYOU END AS R_KAMOKU," . "\r\n";
        $strSQL .= "      CASE BASE.DENPY_KB  " . "\r\n";
        $strSQL .= "       WHEN '1' THEN KAS.SUB_KAMOK_NAME ELSE HMS.MOJI1 END AS R_KOUMKU" . "\r\n";
        //20240322 caina upd e
        $strSQL .= ",      V.GOUKEI KINGAKU" . "\r\n";
        $strSQL .= ",      V.UPD_FLG" . "\r\n";
        $strSQL .= ",      '1' CHK_CSV_STATUS" . "\r\n";
        $strSQL .= ",      'TRUE' CHK_CSV_FLG" . "\r\n";
        $strSQL .= ",      TO_CHAR(BASE.UPD_DATE,'YYYY/MM/DD HH24:MI:SS') UPD_DATE" . "\r\n";
        $strSQL .= ",      ROW_NUMBER() OVER(ORDER BY BASE.SYOHY_NO) - 1 RENBAN" . "\r\n";
        $strSQL .= ",      ROW_NUMBER() OVER(ORDER BY BASE.SYOHY_NO) SEQNO" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA BASE" . "\r\n";
        $strSQL .= "INNER JOIN" . "\r\n";
        $strSQL .= "       (" . "\r\n";
        $strSQL .= "SELECT GK.SYOHY_NO" . "\r\n";
        $strSQL .= "		,      GK.EDA_NO" . "\r\n";
        $strSQL .= "		,      MIN(GK.GYO_NO) GYO_NO" . "\r\n";
        $strSQL .= "		,      SUM(GK.ZEIKM_GK) GOUKEI" . "\r\n";
        $strSQL .= "		,      MAX(CASE WHEN SEL.M_EDA_NO = GK.EDA_NO THEN 0 ELSE 1 END) UPD_FLG" . "\r\n";
        $strSQL .= "		FROM   HDPSHIWAKEDATA GK" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       (" . "\r\n";
        $strSQL .= "				SELECT SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "				,      KEI.M_EDA_NO" . "\r\n";
        $strSQL .= "				,      MAX(SWK.EDA_NO) EDA_NO" . "\r\n";
        $strSQL .= "				FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "				INNER JOIN" . "\r\n";
        $strSQL .= "				       (SELECT SYOHY_NO" . "\r\n";
        $strSQL .= "                         ,      MAX(EDA_NO) M_EDA_NO" . "\r\n";
        $strSQL .= " 				        FROM   HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= " 				        WHERE  CSV_GROUP_NO = '@GROUPNO'" . "\r\n";
        $strSQL .= "				        GROUP BY SYOHY_NO" . "\r\n";
        $strSQL .= "                        ,        EDA_NO" . "\r\n";
        $strSQL .= "				        ) KEI" . "\r\n";
        $strSQL .= "				ON     SWK.SYOHY_NO = KEI.SYOHY_NO" . "\r\n";
        $strSQL .= "				GROUP BY SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "				,        KEI.M_EDA_NO" . "\r\n";
        $strSQL .= "		       ) SEL" . "\r\n";
        $strSQL .= "		ON     SEL.SYOHY_NO = GK.SYOHY_NO" . "\r\n";
        $strSQL .= "		AND    SEL.EDA_NO = GK.EDA_NO" . "\r\n";
        $strSQL .= "		GROUP BY GK.SYOHY_NO" . "\r\n";
        $strSQL .= "	,      GK.EDA_NO" . "\r\n";
        $strSQL .= "        ) V" . "\r\n";
        $strSQL .= "ON      BASE.SYOHY_NO = V.SYOHY_NO" . "\r\n";
        $strSQL .= "AND     BASE.EDA_NO = V.EDA_NO" . "\r\n";
        $strSQL .= "AND     BASE.GYO_NO = V.GYO_NO" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_KAMOKU KAR" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     KAR.KAMOK_CD = BASE.L_KAMOK_CD AND DECODE(BASE.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(BASE.L_KOUMK_CD,'999999') AND KAR.USE_FLG = '1' " . "\r\n";
        $strSQL .= "ON     KAR.KAMOK_CD = BASE.L_KAMOK_CD AND DECODE(BASE.L_KOUMK_CD,NULL,NVL(TRIM(KAR.SUB_KAMOK_CD),'999999'),KAR.SUB_KAMOK_CD) = NVL(BASE.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU KAS" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     LPAD(KAS.KAMOK_CD,5,'0') = LPAD(BASE.R_KAMOK_CD,5,'0') AND DECODE(BASE.R_KOUMK_CD,NULL,NVL(LPAD(TRIM(KAS.SUB_KAMOK_CD),5,'0'),'999999'),lpad(KAS.SUB_KAMOK_CD,5,'0')) = NVL(lpad(BASE.R_KOUMK_CD,5,'0'),'999999') AND KAS.USE_FLG = '1' " . "\r\n";
        $strSQL .= "ON     LPAD(KAS.KAMOK_CD,5,'0') = LPAD(BASE.R_KAMOK_CD,5,'0') AND DECODE(BASE.R_KOUMK_CD,NULL,NVL(LPAD(TRIM(KAS.SUB_KAMOK_CD),5,'0'),'999999'),lpad(KAS.SUB_KAMOK_CD,5,'0')) = NVL(lpad(BASE.R_KOUMK_CD,5,'0'),'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN HMEISYOUMST HMS" . "\r\n";
        $strSQL .= "ON	TO_NUMBER(SUBSTR(HMS.MEISYOU_CD,1,1) || HMS.SUCHI1) = TO_NUMBER(BASE.SHR_KAMOK_KB || BASE.R_KAMOK_CD) AND DECODE(BASE.R_KOUMK_CD,NULL,NVL(TRIM(HMS.SUCHI2),'999999'),HMS.SUCHI2) = NVL(BASE.R_KOUMK_CD,'999999') AND HMS.MEISYOU_ID = 'DK'" . "\r\n";
        $strSQL .= "WHERE BASE.DEL_FLG = '0'" . "\r\n";
        //20240318 lujunxia ins s
        //仕訳伝票を表示しない。支払伝票のみを抽出する
        $strSQL .= "AND BASE.DENPY_KB = '2'" . "\r\n";
        //20240318 lujunxia ins e
        //20240606 lujunxia upd s
        //$strSQL .= "ORDER BY BASE.SYOHY_NO" . "\r\n";
        $strSQL .= "ORDER BY BASE.CSV_OUT_ORDER,BASE.SYOHY_NO" . "\r\n";
        //20240606 lujunxia upd e

        $strSQL = str_replace("@GROUPNO", $postData['strGroup_no'], $strSQL);

        return $strSQL;
    }

    function Fnc_Fill($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT CSV_GROUP_NM FROM HDPOUTGROUPDATA_ZENGIN WHERE CSV_GROUP_NO = '" . $postData['strGroup_no'] . "'" . "\r\n";

        return parent::select($strSQL);
    }

    //CSV出力データを取得Sql
    public function Kensaku_Click($postData)
    {
        $strSql = $this->FncGetSql_CSV($postData);

        return parent::select($strSql);
    }

    //仕訳データの取得Sql
    public function fncSelGroupShiwakeData($postData)
    {
        $strSql = $this->fncSelGroupShiwakeDataSql($postData);

        return parent::select($strSql);
    }

    public function fncSelSyohyShiwakeData($postData)
    {
        $strSql = $this->fncSelSyohyShiwakeDataSql($postData);

        return parent::select($strSql);
    }

    //出力グループ名の重複チェック
    public function FncChkExistGroupNM($txtInputGroupNM, $strGroup_no)
    {
        $strSQL = "";
        $strSQL .= "SELECT   COUNT(*)" . "\r\n";
        $strSQL .= "FROM     HDPOUTGROUPDATA_ZENGIN A" . "\r\n";
        $strSQL .= "WHERE    A.CSV_GROUP_NM = '" . $txtInputGroupNM . "'" . "\r\n";
        $strSQL .= "AND      A.CSV_GROUP_NO <> '" . $strGroup_no . "'" . "\r\n";

        return parent::select($strSQL);
    }

    //グループのチェック(違うグループになっているデータがある場合、未出力に戻すことはできない)
    public function fncCheckOffGroup($strSyohyoNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT   A.EDA_NO" . "\r\n";
        $strSQL .= ",        A.DEL_FLG AS 削除フラグ" . "\r\n";
        $strSQL .= ",        A.CSV_GROUP_NO AS グループ番号" . "\r\n";
        $strSQL .= "FROM     HDPSHIWAKEDATA A" . "\r\n";
        $strSQL .= "         INNER JOIN (SELECT   BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BA.EDA_NO," . "\r\n";
        $strSQL .= "                              BB.CSV_OUT_FLG," . "\r\n";
        $strSQL .= "                              MIN(BA.GYO_NO) AS GYO_NO" . "\r\n";
        $strSQL .= "                     FROM     HDPSHIWAKEDATA BA" . "\r\n";
        $strSQL .= "                              INNER JOIN (SELECT   SYOHY_NO," . "\r\n";
        $strSQL .= "                                                   MAX(CSV_OUT_FLG) AS CSV_OUT_FLG," . "\r\n";
        $strSQL .= "                                                   MAX(EDA_NO) AS EDA_NO" . "\r\n";
        $strSQL .= "                                          FROM     HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "                                          WHERE    SYOHY_NO = '" . substr($strSyohyoNo, 0, 15) . "'" . "\r\n";
        $strSQL .= "                                          GROUP BY SYOHY_NO) BB" . "\r\n";
        $strSQL .= "                                  ON BA.SYOHY_NO = BB.SYOHY_NO" . "\r\n";
        $strSQL .= "                                 AND BA.EDA_NO = BB.EDA_NO" . "\r\n";
        $strSQL .= "                      GROUP BY BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BB.CSV_OUT_FLG," . "\r\n";
        $strSQL .= "                              BA.EDA_NO) B" . "\r\n";
        $strSQL .= "             ON A.SYOHY_NO = B.SYOHY_NO" . "\r\n";
        $strSQL .= "            AND A.EDA_NO = B.EDA_NO" . "\r\n";
        $strSQL .= "            AND A.GYO_NO = B.GYO_NO" . "\r\n";
        $strSQL .= " WHERE    A.SYOHY_NO = '" . substr($strSyohyoNo, 0, 15) . "'" . "\r\n";

        return parent::select($strSQL);

    }

    //'読み取りデータのチェックと読取書類ラベルへのセット
    public function FncChkAndSetShiwakeInfoSql($strSyohyoNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT   A.EDA_NO," . "\r\n";
        $strSQL .= "         DECODE(A.DENPY_KB, '1', '仕訳伝票', '2', '支払伝票', NULL) AS 読取書類," . "\r\n";
        $strSQL .= "         CASE" . "\r\n";
        $strSQL .= "             WHEN A.DENPY_KB = '2' AND" . "\r\n";
        $strSQL .= "                  A.R_KAMOK_CD = '21152' AND" . "\r\n";
        $strSQL .= "                  A.R_KOUMK_CD = '9' AND" . "\r\n";
        $strSQL .= "                  A.TORIHIKISAKI_CD <> '99999' THEN '1'" . "\r\n";
        $strSQL .= "             ELSE '0'" . "\r\n";
        $strSQL .= "         END AS 特別ＣＳＶフラグ," . "\r\n";
        $strSQL .= "         B.CSV_OUT_FLG AS ＣＳＶ出力フラグ," . "\r\n";
        $strSQL .= "         A.DEL_FLG AS 削除フラグ," . "\r\n";
        $strSQL .= "         NVL(A.PRINT_OUT_FLG, '0') AS 印刷フラグ" . "\r\n";
        $strSQL .= "         ,TO_CHAR(UPD_DATE,'YYYY/MM/DD HH24:MI:SS') UPD_DATE" . "\r\n";
        $strSQL .= "FROM     HDPSHIWAKEDATA A" . "\r\n";
        $strSQL .= "         INNER JOIN (SELECT   BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BA.EDA_NO," . "\r\n";
        $strSQL .= "                              BB.CSV_OUT_FLG," . "\r\n";
        $strSQL .= "                              MIN(BA.GYO_NO) AS GYO_NO" . "\r\n";
        $strSQL .= "                     FROM     HDPSHIWAKEDATA BA" . "\r\n";
        $strSQL .= "                              INNER JOIN (SELECT   SYOHY_NO," . "\r\n";
        $strSQL .= "                                                   MAX(CSV_OUT_FLG) AS CSV_OUT_FLG," . "\r\n";
        $strSQL .= "                                                   MAX(EDA_NO) AS EDA_NO" . "\r\n";
        $strSQL .= "                                          FROM     HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "                                          WHERE    SYOHY_NO = '" . substr($strSyohyoNo, 0, 15) . "'" . "\r\n";
        $strSQL .= "                                          GROUP BY SYOHY_NO) BB" . "\r\n";
        $strSQL .= "                                  ON BA.SYOHY_NO = BB.SYOHY_NO" . "\r\n";
        $strSQL .= "                                 AND BA.EDA_NO = BB.EDA_NO" . "\r\n";
        $strSQL .= "                     GROUP BY BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BB.CSV_OUT_FLG," . "\r\n";
        $strSQL .= "                              BA.EDA_NO) B" . "\r\n";
        $strSQL .= "             ON A.SYOHY_NO = B.SYOHY_NO" . "\r\n";
        $strSQL .= "            AND A.EDA_NO = B.EDA_NO" . "\r\n";
        $strSQL .= "            AND A.GYO_NO = B.GYO_NO" . "\r\n";
        $strSQL .= "WHERE    A.SYOHY_NO = '" . substr($strSyohyoNo, 0, 15) . "'" . "\r\n";

        return parent::select($strSQL);
    }

    //グループ№の最新取得
    public function FncGetGroupNo()
    {
        $strSQL = "";
        $strSQL .= "SELECT   NVL(MAX(CSV_GROUP_NO), 0) + 1" . "\r\n";
        $strSQL .= "FROM     HDPOUTGROUPDATA_ZENGIN A" . "\r\n";

        return parent::select($strSQL);
    }

    //出力グループの登録
    public function SubInsertGroupData($postData, $groupNo, $sysDate)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDPOUTGROUPDATA_ZENGIN SET" . "\r\n";
        $strSQL .= "       CSV_GROUP_NM = '@CSV_GROUP_NM'" . "\r\n";
        $strSQL .= ",      CSV_OUT_DT = TO_DATE('@CSV_OUT_DT','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",      KEIRI_DT = '@KEIRI_DT'" . "\r\n";
        $strSQL .= ",      UPD_DATE = TO_DATE('@UPD_DATE','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= " WHERE  CSV_GROUP_NO = '@CSV_GROUP_NO'" . "\r\n";

        $strSQL = str_replace("@CSV_GROUP_NM", $postData['txtInputGroupNM'], $strSQL);
        $strSQL = str_replace("@CSV_OUT_DT", $sysDate, $strSQL);
        $strSQL = str_replace("@KEIRI_DT", str_replace("/", "", $postData["txtInputKeiriDt"]), $strSQL);
        $strSQL = str_replace("@UPD_DATE", $sysDate, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", 'CsvReOut', $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CSV_GROUP_NO", $groupNo, $strSQL);

        return parent::update($strSQL);
    }

    //証憑データの更新
    public function SubUpdateSyohyoData($postData, $strSyohyoNo, $strEdaNo, $groupNo, $sysDate, $intCsvOutOrd, $patternID, $BusyoCD)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA SET" . "\r\n";
        $strSQL .= "KEIRI_DT =  '@KEIRI_DT'," . "\r\n";
        $strSQL .= "CSV_OUT_FLG = '1'," . "\r\n";

        //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
        if ($patternID == $postData['CONST_ADMIN_PTN_NO'] || $patternID == $postData['CONST_HONBU_PTN_NO']) {
            $strSQL .= "HONBU_SYORIZUMI_FLG = '1'," . "\r\n";
        }

        $strSQL .= "CSV_GROUP_NO ='@groupNo'," . "\r\n";
        $strSQL .= "CSV_OUT_ORDER ='@intCsvOutOrd'," . "\r\n";
        $strSQL .= "UPD_DATE = TO_DATE('@sysDate','yyyy/MM/dd HH24:MI:SS')," . "\r\n";
        $strSQL .= "UPD_BUSYO_CD = '@UPD_BUSYO_CD'," . "\r\n";
        $strSQL .= "UPD_SYA_CD = '@UPD_SYA_CD'," . "\r\n";
        $strSQL .= "UPD_PRG_ID = '@UPD_PRG_ID'," . "\r\n";
        $strSQL .= "UPD_CLT_NM ='@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO =  '@strSyohyoNo'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@strEdaNo'" . "\r\n";

        $strSQL = str_replace("@groupNo", $groupNo, $strSQL);
        $strSQL = str_replace("@intCsvOutOrd", $intCsvOutOrd, $strSQL);
        $strSQL = str_replace("@sysDate", $sysDate, $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $BusyoCD, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", 'CsvReOut', $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@strSyohyoNo", $strSyohyoNo, $strSQL);
        $strSQL = str_replace("@strEdaNo", $strEdaNo, $strSQL);
        $strSQL = str_replace("@KEIRI_DT", str_replace("/", "", $postData["txtInputKeiriDt"]), $strSQL);

        return parent::update($strSQL);

    }

    //証憑データの更新(キャンセル)
    public function SubUpdateSyohyoDataCancel($postData, $strSyohyoNo, $sysDate, $patternID)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA SET" . "\r\n";
        $strSQL .= "KEIRI_DT = NULL," . "\r\n";
        $strSQL .= "CSV_OUT_FLG = '0'," . "\r\n";
        $strSQL .= "CSV_GROUP_NO = NULL," . "\r\n";
        $strSQL .= "CSV_OUT_ORDER = NULL," . "\r\n";

        //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
        if ($patternID == $postData['CONST_ADMIN_PTN_NO'] || $patternID == $postData['CONST_HONBU_PTN_NO']) {
            $strSQL .= "HONBU_SYORIZUMI_FLG = '1'," . "\r\n";
        }

        $strSQL .= "UPD_DATE = TO_DATE('@sysDate','yyyy/MM/dd HH24:MI:SS')," . "\r\n";
        $strSQL .= "UPD_SYA_CD = '@UPD_SYA_CD'," . "\r\n";
        $strSQL .= "UPD_PRG_ID='@UPD_PRG_ID'," . "\r\n";
        $strSQL .= "UPD_CLT_NM ='@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO ='@strSyohyoNo'" . "\r\n";

        $strSQL = str_replace("@sysDate", $sysDate, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", 'CsvReOut', $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@strSyohyoNo", $strSyohyoNo, $strSQL);

        return parent::update($strSQL);

    }

}
