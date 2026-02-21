<?php
// 共通クラスの読込み
namespace App\Model\R4\Component;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名    ：機能名Model相当の処理
// * 関数名    ：ClsKeiriDataMake
// * 処理説明：共通関数
//*************************************

class ClsSimKeieiSeika extends ClsComDb
{
    /**
     * 処 理 名：ﾜｰｸ部署別集計を削除する
     * 関 数 名：fncDeleteWkKanr
     * 引    数：無し
     * 戻 り 値：SQL
     * 処理説明：ﾜｰｸ部署別集計を削除する
     */
    function fncDeleteWkKanrSQL()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM WK_HSIMKANR";

        return $strSQL;
    }

    public function fncDeleteWkKanr()
    {
        $strsql = $this->fncDeleteWkKanrSQL();
        return parent::Do_Execute($strsql);
    }

    /**
     * 処 理 名：集計・部署別集計を行う
     * 関 数 名：fncSyukeiToBusyo
     * 引    数：dtlSyoriYM  (I)処理年月
     * 　    　：dtlTougtuYM (I)当月年月
     * 戻 り 値：SQL
     * 処理説明：集計・部署別集計を行い、部署別集計ﾜｰｸに格納
     */
    function fncSyukeiToBusyoSQL($dtlSyoriYM, $strUpdUser, $strUpdClt, $strUpdPro)
    {
        $strSQL = "";

        $strSQL .= "INSERT INTO WK_HSIMKANR" . "\r\n";
        $strSQL .= "(   KEIJO_DT" . "\r\n";
        $strSQL .= ",   BUSYO_CD " . "\r\n";
        $strSQL .= ",	LINE_NO  " . "\r\n";
        $strSQL .= ",	TOU_ZAN  " . "\r\n";
        $strSQL .= ",    UPD_SYA_CD" . "\r\n";
        $strSQL .= ",    UPD_PRG_ID" . "\r\n";
        $strSQL .= ",    UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "	SELECT  '@TOU_GETU'" . "\r\n";
        $strSQL .= "	,       V.BUSYO_CD" . "\r\n";
        $strSQL .= "	,       V.LINE_NO" . "\r\n";
        $strSQL .= "	,       SUM(V.TOUGETU)" . "\r\n";
        $strSQL .= ",        '@UPDUSER'" . "\r\n";
        $strSQL .= ",        '@UPDAPP'" . "\r\n";
        $strSQL .= ",        '@UPDCLT'" . "\r\n";
        $strSQL .= "	FROM    (" . "\r\n";
        $strSQL .= "			--当月集計" . "\r\n";
        $strSQL .= "	        SELECT  DECODE(BUS.CNV_BUSYO_CD,NULL,TOU.BUSYO_CD,BUS.CNV_BUSYO_CD) BUSYO_CD" . "\r\n";
        $strSQL .= "			,      LINE.LINE_NO" . "\r\n";
        $strSQL .= "			,      SUM(NVL(TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1)) TOUGETU" . "\r\n";
        $strSQL .= "			FROM   HKANRIZ TOU" . "\r\n";
        $strSQL .= "	        INNER JOIN" . "\r\n";
        $strSQL .= "			       HBUSYO BUS" . "\r\n";
        $strSQL .= "			ON     BUS.BUSYO_CD = TOU.BUSYO_CD" . "\r\n";
        $strSQL .= "			INNER JOIN" . "\r\n";
        $strSQL .= "			       HKMKLINEMST KLINE" . "\r\n";
        $strSQL .= "			ON     KLINE.KAMOK_CD = TOU.KAMOKU_CD" . "\r\n";
        $strSQL .= "	        AND    (KLINE.HIMOK_CD = NVL(TRIM(TOU.HIMOKU_CD),'00')" . "\r\n";
        $strSQL .= "             OR (DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(KLINE.HIMOK_CD,1,1),KLINE.HIMOK_CD) = DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(TOU.HIMOKU_CD,1,1),TOU.HIMOKU_CD,1,1)))" . "\r\n";
        $strSQL .= "			INNER JOIN" . "\r\n";
        $strSQL .= "			       HLINEMST LINE" . "\r\n";
        $strSQL .= "			ON     LINE.LINE_NO = KLINE.LINE_NO" . "\r\n";
        $strSQL .= "			WHERE  TOU.KEIJO_DT = '@TOU_GETU'" . "\r\n";
        $strSQL .= "			GROUP BY  DECODE(BUS.CNV_BUSYO_CD,NULL,TOU.BUSYO_CD,BUS.CNV_BUSYO_CD), LINE.LINE_NO" . "\r\n";
        $strSQL .= "			" . "\r\n";
        $strSQL .= "			--当月部署別集計" . "\r\n";
        $strSQL .= "			UNION ALL" . "\r\n";
        $strSQL .= "	" . "\r\n";
        $strSQL .= "			SELECT SBUS.TOTAL_BUSYO_CD" . "\r\n";
        $strSQL .= "			,      LINE.LINE_NO" . "\r\n";
        $strSQL .= "	     	,      SUM(NVL(B_TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1)) TOUGETU" . "\r\n";
        $strSQL .= "			FROM   HKANRIZ B_TOU" . "\r\n";
        $strSQL .= "	        INNER JOIN" . "\r\n";
        $strSQL .= "			       HBUSYO BUS" . "\r\n";
        $strSQL .= "			ON     BUS.BUSYO_CD = B_TOU.BUSYO_CD" . "\r\n";
        $strSQL .= "			INNER JOIN" . "\r\n";
        $strSQL .= "			       HTTLBUSYO SBUS" . "\r\n";
        $strSQL .= "			ON     SBUS.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "			INNER JOIN" . "\r\n";
        $strSQL .= "			       HKMKLINEMST KLINE" . "\r\n";
        $strSQL .= "			ON     KLINE.KAMOK_CD = B_TOU.KAMOKU_CD" . "\r\n";
        $strSQL .= "	        AND    (KLINE.HIMOK_CD = NVL(TRIM(B_TOU.HIMOKU_CD),'00')" . "\r\n";
        $strSQL .= "             OR (DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(KLINE.HIMOK_CD,1,1),KLINE.HIMOK_CD) = DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(B_TOU.HIMOKU_CD,1,1),B_TOU.HIMOKU_CD,1,1)))" . "\r\n";
        $strSQL .= "			INNER JOIN" . "\r\n";
        $strSQL .= "			       HLINEMST LINE" . "\r\n";
        $strSQL .= "			ON     LINE.LINE_NO = KLINE.LINE_NO" . "\r\n";
        $strSQL .= "			WHERE  B_TOU.KEIJO_DT = '@TOU_GETU'" . "\r\n";
        $strSQL .= "			GROUP BY SBUS.TOTAL_BUSYO_CD, LINE.LINE_NO" . "\r\n";
        $strSQL .= "			" . "\r\n";
        $strSQL .= "			--トータル集計(当月)" . "\r\n";
        $strSQL .= "			UNION ALL" . "\r\n";
        $strSQL .= "	" . "\r\n";
        $strSQL .= "			SELECT '000'" . "\r\n";
        $strSQL .= "			,      LINE.LINE_NO" . "\r\n";
        $strSQL .= "	     	,      SUM(NVL(B_TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1)) TOUGETU" . "\r\n";
        $strSQL .= "			FROM   HKANRIZ B_TOU" . "\r\n";
        $strSQL .= "	        INNER JOIN" . "\r\n";
        $strSQL .= "			       HBUSYO BUS" . "\r\n";
        $strSQL .= "			ON     BUS.BUSYO_CD = B_TOU.BUSYO_CD" . "\r\n";
        $strSQL .= "			INNER JOIN" . "\r\n";
        $strSQL .= "			       HKMKLINEMST KLINE" . "\r\n";
        $strSQL .= "			ON     KLINE.KAMOK_CD = B_TOU.KAMOKU_CD" . "\r\n";
        $strSQL .= "	        AND    (KLINE.HIMOK_CD = NVL(TRIM(B_TOU.HIMOKU_CD),'00')" . "\r\n";
        $strSQL .= "             OR (DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(KLINE.HIMOK_CD,1,1),KLINE.HIMOK_CD) = DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(B_TOU.HIMOKU_CD,1,1),B_TOU.HIMOKU_CD,1,1)))" . "\r\n";
        $strSQL .= "			INNER JOIN" . "\r\n";
        $strSQL .= "			       HLINEMST LINE" . "\r\n";
        $strSQL .= "			ON     LINE.LINE_NO = KLINE.LINE_NO" . "\r\n";
        $strSQL .= "			WHERE  B_TOU.KEIJO_DT = '@TOU_GETU'" . "\r\n";
        $strSQL .= "			GROUP BY LINE.LINE_NO" . "\r\n";
        $strSQL .= "			" . "\r\n";
        $strSQL .= "	) V" . "\r\n";
        $strSQL .= "	" . "\r\n";
        $strSQL .= "	GROUP BY V.BUSYO_CD, V.LINE_NO" . "\r\n";

        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@TOU_GETU", $dtlSyoriYM, $strSQL);

        return $strSQL;
    }

    public function fncSyukeiToBusyo($dtlSyoriYM, $strUpdUser, $strUpdClt, $strUpdPro)
    {
        $strsql = $this->fncSyukeiToBusyoSQL($dtlSyoriYM, $strUpdUser, $strUpdClt, $strUpdPro);
        return parent::Do_Execute($strsql);
    }

    /**
     * 処 理 名：ライン集計を行う
     * 関 数 名：fncSyukeiLine
     * 引    数：無し
     * 戻 り 値：SQL
     * 処理説明：ライン集計を行う
     */
    function fncSyukeiLineSQL($strUpdUser, $strUpdClt, $strUpdPro)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO WK_HSIMKANR" . "\r\n";
        $strSQL .= "(   KEIJO_DT" . "\r\n";
        $strSQL .= ",   BUSYO_CD " . "\r\n";
        $strSQL .= ",	LINE_NO  " . "\r\n";
        $strSQL .= ",	TOU_ZAN  " . "\r\n";
        $strSQL .= ",    UPD_SYA_CD" . "\r\n";
        $strSQL .= ",    UPD_PRG_ID" . "\r\n";
        $strSQL .= ",    UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT  SYUKEI.KEIJO_DT" . "\r\n";
        $strSQL .= ",       SYUKEI.BUSYO_CD" . "\r\n";
        $strSQL .= ",       SYUKEI.TOTAL_LINE_NO" . "\r\n";
        $strSQL .= ",       SYUKEI.TOUGETU" . "\r\n";
        $strSQL .= ",       '@UPDUSER'" . "\r\n";
        $strSQL .= ",       '@UPDAPP'" . "\r\n";
        $strSQL .= ",       '@UPDCLT'" . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "		SELECT W_KR.KEIJO_DT" . "\r\n";
        $strSQL .= "        ,      W_KR.BUSYO_CD" . "\r\n";
        $strSQL .= "		,      S_LINE.TOTAL_LINE_NO" . "\r\n";
        $strSQL .= "		,      SUM(W_KR.TOU_ZAN * NVL(S_LINE.CAL_KB,1)) TOUGETU" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "		FROM   WK_HSIMKANR W_KR" . "\r\n";
        $strSQL .= "       	INNER JOIN" . "\r\n";
        $strSQL .= "               HTTLLINEMST S_LINE" . "\r\n";
        $strSQL .= "        ON     S_LINE.LINE_NO = W_KR.LINE_NO" . "\r\n";
        $strSQL .= "        GROUP BY W_KR.KEIJO_DT, W_KR.BUSYO_CD, S_LINE.TOTAL_LINE_NO) SYUKEI" . "\r\n";

        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        return $strSQL;
    }

    public function fncSyukeiLine($strUpdUser, $strUpdClt, $strUpdPro)
    {
        $strsql = $this->fncSyukeiLineSQL($strUpdUser, $strUpdClt, $strUpdPro);
        return parent::Do_Execute($strsql);
    }

    /**
     * 処 理 名：指標を算出する
     * 関 数 名：fncSihyouLine
     * 引    数：strSyoriNengetu (I)画面：処理年月
     * 　    　：strKisyuYMD     (I)経理ｺﾝﾄﾛｰﾙﾏｽﾀ：期首
     * 　    　：strKi           (I)経理ｺﾝﾄﾛｰﾙﾏｽﾀ：期
     * 戻 り 値：SQL
     * 処理説明：指標を算出する(（GD）（DZM）データ連携サブシステムで使用)
     */
    function fncSihyouLineSQL($strSyoriNengetu, $strKi, $intPtnNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "       SUBSTR(JPDATE('@SYORITBI'),2,2) NEN" . "\r\n";
        $strSQL .= ",      SUBSTR(JPDATE('@SYORITBI'),4,2) TUKI" . "\r\n";
        $strSQL .= ",      '@HINICHI' HI" . "\r\n";
        $strSQL .= ",      '@KI' KI" . "\r\n";
        $strSQL .= ",      SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= ",      SLINE.LINE_NO" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",      SLINE.MOJI1" . "\r\n";
        $strSQL .= ",      DECODE(SLINE.DISP_KB,1,0,ROUND(SLINE.TOU_ZAN,NVL(SLINE.RND_POS,0)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1))) LINE1" . "\r\n";
        $strSQL .= ",      NVL((CASE WHEN SIHYOU_KBN = 1 AND NVL(SLINE.TOU_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.TOU_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(SIHYO_ZAN.TOU_ZAN * 100 / SLINE.TOU_ZAN, SLINE.IDX_RND_POS)),'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 2 AND NVL(SLINE.TOU_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.TOU_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(SLINE.TOU_ZAN * 100 / SIHYO_ZAN.TOU_ZAN, SLINE.IDX_RND_POS)),'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 3 AND NVL(SLINE.TOU_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.TOU_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((SIHYO_ZAN.TOU_ZAN / SLINE.TOU_ZAN) / POWER(10,(NVL(LINE_T.RND_POS,0) * -1))), SLINE.IDX_RND_POS)),'FM9,999,999,999.0'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 4 AND NVL(SLINE.TOU_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.TOU_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((SLINE.TOU_ZAN / SIHYO_ZAN.TOU_ZAN) / POWER(10,(NVL(SLINE.RND_POS,0) * -1))) , SLINE.IDX_RND_POS)),'FM9,999,999,999.0')) END),'') LINE2" . "\r\n";
        $strSQL .= ",      DECODE(SLINE.DISP_KB,1,0,ROUND(SLINE.ZEN_ZAN,NVL(SLINE.RND_POS,0)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1))) LINE3" . "\r\n";
        $strSQL .= ",      NVL((CASE WHEN SIHYOU_KBN = 1 AND NVL(SLINE.ZEN_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.ZEN_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(SIHYO_ZAN.ZEN_ZAN * 100 / SLINE.ZEN_ZAN, SLINE.IDX_RND_POS)),'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 2 AND NVL(SLINE.ZEN_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.ZEN_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(SLINE.ZEN_ZAN * 100 / SIHYO_ZAN.ZEN_ZAN, SLINE.IDX_RND_POS)),'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 3 AND NVL(SLINE.ZEN_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.ZEN_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((SIHYO_ZAN.ZEN_ZAN / SLINE.ZEN_ZAN) / POWER(10,(NVL(LINE_T.RND_POS,0) * -1))), SLINE.IDX_RND_POS)),'FM9,999,999,999.0'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 4 AND NVL(SLINE.ZEN_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.ZEN_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((SLINE.ZEN_ZAN / SIHYO_ZAN.ZEN_ZAN) / POWER(10,(NVL(SLINE.RND_POS,0) * -1))) , SLINE.IDX_RND_POS)),'FM9,999,999,999.0')) END),'') LINE4" . "\r\n";
        $strSQL .= ",      DECODE(SLINE.DISP_KB,1,0,ROUND(SLINE.TKI_ZAN,NVL(SLINE.RND_POS,0)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1))) LINE5" . "\r\n";
        $strSQL .= ",      NVL((CASE WHEN SIHYOU_KBN = 1 AND NVL(SLINE.TKI_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.TKI_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(SIHYO_ZAN.TKI_ZAN * 100 / SLINE.TKI_ZAN, SLINE.IDX_RND_POS)),'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 2 AND NVL(SLINE.TKI_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.TKI_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(SLINE.TKI_ZAN * 100 / SIHYO_ZAN.TKI_ZAN, SLINE.IDX_RND_POS)),'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 3 AND NVL(SLINE.TKI_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.TKI_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((SIHYO_ZAN.TKI_ZAN / SLINE.TKI_ZAN) / POWER(10,(NVL(LINE_T.RND_POS,0) * -1))), SLINE.IDX_RND_POS)),'FM9,999,999,999.0'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 4 AND NVL(SLINE.TKI_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.TKI_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((SLINE.TKI_ZAN / SIHYO_ZAN.TKI_ZAN) / POWER(10,(NVL(SLINE.RND_POS,0) * -1))) , SLINE.IDX_RND_POS)),'FM9,999,999,999.0')) END),'') LINE6" . "\r\n";
        $strSQL .= ",      DECODE(SLINE.DISP_KB,1,0,ROUND(SLINE.ZKI_ZAN,NVL(SLINE.RND_POS,0)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1))) LINE7" . "\r\n";
        $strSQL .= ",      NVL((CASE WHEN SIHYOU_KBN = 1 AND NVL(SLINE.ZKI_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.ZKI_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(SIHYO_ZAN.ZKI_ZAN * 100 / SLINE.ZKI_ZAN, SLINE.IDX_RND_POS)),'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 2 AND NVL(SLINE.ZKI_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.ZKI_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(SLINE.ZKI_ZAN * 100 / SIHYO_ZAN.ZKI_ZAN, SLINE.IDX_RND_POS)),'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 3 AND NVL(SLINE.ZKI_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.ZKI_ZAN,0) <> 0 THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((SIHYO_ZAN.ZKI_ZAN / SLINE.ZKI_ZAN) / POWER(10,(NVL(LINE_T.RND_POS,0) * -1))), SLINE.IDX_RND_POS)),'FM9,999,999,999.0'))" . "\r\n";
        $strSQL .= "             WHEN SIHYOU_KBN = 4 AND NVL(SLINE.ZKI_ZAN,0) <> 0 AND NVL(SIHYO_ZAN.ZKI_ZAN,0) <> 0THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((SLINE.ZKI_ZAN / SIHYO_ZAN.ZKI_ZAN) / POWER(10,(NVL(SLINE.RND_POS,0) * -1))) , SLINE.IDX_RND_POS)),'FM9,999,999,999.0')) END),'') LINE8" . "\r\n";
        $strSQL .= ",      (CASE WHEN SLINE.LINE_NO IN ('1','23') " . "\r\n";
        $strSQL .= "             THEN (CASE WHEN SLINE.DISP_KB = '1' OR NVL(SLINE.ZENNENHI,0) = 0 " . "\r\n";
        $strSQL .= "                        THEN ' ' " . "\r\n";
        $strSQL .= "                        ELSE  (CASE WHEN NVL(SLINE.ZEN_ZAN,0) * NVL(LINE_T.CAL_KB,1) < 0 " . "\r\n";
        $strSQL .= "                                    THEN (CASE WHEN NVL(SLINE.ZEN_ZAN,0) * NVL(LINE_T.CAL_KB,1) = 0 " . "\r\n";
        $strSQL .= "                                               THEN ' ' " . "\r\n";
        $strSQL .= "                                               ELSE TRIM(LEADING '0' FROM TO_CHAR(TRUNC((NVL(SLINE.TOU_ZAN,0) * NVL(LINE_T.CAL_KB,1) - NVL(SLINE.ZEN_ZAN,0) * NVL(LINE_T.CAL_KB,1) - NVL(SLINE.ZEN_ZAN,0) * NVL(LINE_T.CAL_KB,1)) / (-1 * (SLINE.ZEN_ZAN * NVL(LINE_T.CAL_KB,1))) * 100 ,1),'FM9,999,999,999.0')) END)" . "\r\n";
        $strSQL .= "                                    ELSE (CASE WHEN NVL(SLINE.ZEN_ZAN,0) * NVL(LINE_T.CAL_KB,1) = 0 " . "\r\n";
        $strSQL .= "                                               THEN ' ' " . "\r\n";
        $strSQL .= "                                               ELSE TRIM(LEADING '0' FROM TO_CHAR(TRUNC((SLINE.TOU_ZAN * NVL(LINE_T.CAL_KB,1)) / (SLINE.ZEN_ZAN  * NVL(LINE_T.CAL_KB,1)) * 100,1),'FM9,999,999,999.0')) END)" . "\r\n";
        $strSQL .= "                               END) " . "\r\n";
        $strSQL .= "                  END)" . "\r\n";
        $strSQL .= "            ELSE (CASE WHEN SLINE.DISP_KB = '1' OR NVL(SLINE.ZENNENHI,0) = 0 THEN ' ' ELSE TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(SLINE.ZENNENHI),'FM9,999,999,999.0')) END)" . "\r\n";
        $strSQL .= "        END) LINE9" . "\r\n";
        $strSQL .= ",      (CASE WHEN NVL(SLINE.IDX_RND_POS,0) <> 0 THEN TO_CHAR(ROUND(YSN.TOUKISIHYO / 10 ,SLINE.IDX_RND_POS), DECODE(SLINE.IDX_RND_POS,1,'FM9,999,999,999.0','FM9,999,999,999.00')) END) LINE10" . "\r\n";
        $strSQL .= ",      SLINE.IDX_TANI" . "\r\n";
        $strSQL .= "FROM(" . "\r\n";
        $strSQL .= "	   SELECT" . "\r\n";
        $strSQL .= "          (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI1 ELSE LINE.IDX_LINE_NO END) SIHYOU_LINE" . "\r\n";
        $strSQL .= "    ,     (CASE WHEN (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE  LINE.IDX_TANI END) = '%' " . "\r\n";
        $strSQL .= "               THEN (CASE WHEN LINE.IDX_CAL_KB = '1' " . "\r\n";
        $strSQL .= "                          THEN 1" . "\r\n";
        $strSQL .= "                          ELSE 2 END)" . "\r\n";
        $strSQL .= "               ELSE (CASE WHEN LINE.IDX_CAL_KB = '1'" . "\r\n";
        $strSQL .= "                        THEN 3" . "\r\n";
        $strSQL .= "                        ELSE 4 END) END) SIHYOU_KBN" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI2 ELSE LINE.IDX_RND_POS END) IDX_RND_POS" . "\r\n";
        $strSQL .= "    ,      LINE.RND_POS" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE LINE.IDX_TANI END) IDX_TANI" . "\r\n";
        $strSQL .= "    ,      LINE.DISP_KB" . "\r\n";
        $strSQL .= "    ,      MEI.MOJI1" . "\r\n";
        $strSQL .= "    ,      W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "    ,      W_LINE.LINE_NO" . "\r\n";
        if ($intPtnNo > 0) {
            $strSQL .= "     ,     W_LINE.PRINT_ORDER" . "\r\n";
        }
        $strSQL .= "    ,      WK.TOU_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZEN_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.TKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZENNENHI" . "\r\n";
        $strSQL .= "	   FROM   " . "\r\n";
        $strSQL .= "           (SELECT BUS.BUSYO_CD" . "\r\n";
        if ($intPtnNo > 0) {
            $strSQL .= "        ,      BUS.PRINT_ORDER" . "\r\n";
        }
        $strSQL .= "	        ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "	        FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "	        ,      (SELECT KR.BUSYO_CD" . "\r\n";
        if ($intPtnNo > 0) {
            $strSQL .= "             ,      PTN.PRINT_ORDER" . "\r\n";
        }
        $strSQL .= "	                FROM   WK_KANR KR" . "\r\n";

        if ($intPtnNo > 0) {
            $strSQL .= "                 INNER JOIN HKSPATTERNLISTMST PTN" . "\r\n";
            $strSQL .= "                 ON    PTN.PATTERN_NO = '@PTNNO'" . "\r\n";
            $strSQL .= "                 AND   PTN.BUSYO_CD = KR.BUSYO_CD" . "\r\n";
        }
        if ($intPtnNo > 0) {
            $strSQL .= "	                GROUP BY KR.BUSYO_CD, PTN.PRINT_ORDER) BUS" . "\r\n";
        } else {
            $strSQL .= "	                GROUP BY KR.BUSYO_CD) BUS" . "\r\n";
        }
        $strSQL .= "	        ) W_LINE" . "\r\n";
        $strSQL .= "    LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "    ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "    AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "	   LEFT JOIN" . "\r\n";
        $strSQL .= "	       HBUSYO BUS" . "\r\n";
        $strSQL .= "    ON     BUS.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "	   LEFT JOIN" . "\r\n";
        $strSQL .= "	       HLINEMST LINE" . "\r\n";
        $strSQL .= "	   ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "	   LEFT   JOIN" . "\r\n";
        $strSQL .= "	       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "	   ON     MEI.MEISYOU_CD = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "	   AND    BUS.BUSYO_KB = MEI.MEISYOU_ID) SLINE" . "\r\n";
        $strSQL .= "LEFT JOIN WK_KANR SIHYO_ZAN" . "\r\n";
        $strSQL .= "ON   SIHYO_ZAN.LINE_NO = SLINE.SIHYOU_LINE" . "\r\n";
        $strSQL .= "AND  SIHYO_ZAN.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HLINEMST LINE_T" . "\r\n";
        $strSQL .= "ON     LINE_T.LINE_NO = SIHYO_ZAN.LINE_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON   BUS.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "        (SELECT KI" . "\r\n";
        $strSQL .= "         ,      BUSYO_CD" . "\r\n";
        $strSQL .= "         ,      LINE_NO" . "\r\n";
        $strSQL .= "         ,      NVL(YSN_GK" . str_replace("0", "", substr(str_replace("/", "", $strSyoriNengetu), 4, 2)) . ",0)" . "\r\n";
        // $strSQL .= "         ,      NVL(YSN_GK" & strSyoriNengetu.Replace("/", "").Substring(4, 2).Replace("0", "") & ",0)" . "\r\n";
        // $strSQL .= "         ,      NVL(YSN_GK" . $strSyoriNengetu . ",0)" . "\r\n";

        $strSQL .= "         AS TOUKISIHYO    " . "\r\n";
        $strSQL .= "         FROM   HSHIHYO" . "\r\n";
        $strSQL .= "         WHERE  KI = '@KI') YSN" . "\r\n";
        $strSQL .= "ON      YSN.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "AND     YSN.LINE_NO = SLINE.LINE_NO" . "\r\n";
        $strSQL .= "WHERE SLINE.LINE_NO < '83'" . "\r\n";
        $strSQL .= "ORDER BY " . "\r\n";

        if ($intPtnNo > 0) {
            $strSQL .= "      SLINE.PRINT_ORDER, " . "\r\n";
        }
        $strSQL .= "SLINE.BUSYO_CD, SLINE.LINE_NO" . "\r\n";

        $strSQL = str_replace("@SYORITBI", str_replace("/", "", $strSyoriNengetu), $strSQL);

        if ($strSyoriNengetu == "") {
            $strSQL = str_replace("@HINICHI", "", $strSQL);
        } else {
            $strSQL = str_replace("@HINICHI", cal_days_in_month($strSyoriNengetu, substr($strSyoriNengetu, 5, 2), substr($strSyoriNengetu, 0, 4)), $strSQL);
        }
        $strSQL = str_replace("@PTNNO", $intPtnNo, $strSQL);
        $strSQL = str_replace("@KI", $strKi, $strSQL);
        return $strSQL;
    }

    public function fncSihyouLine($strSyoriNengetu, $strKi, $intPtnNo)
    {
        $strsql = $this->fncSihyouLineSQL($strSyoriNengetu, $strKi, $intPtnNo);
        return parent::Do_Execute($strsql);
    }

    /**
     * 処 理 名：経営成果対象でないデータを部署別集計ﾜｰｸから削除する
     * 関 数 名：fncDeleteKanr
     * 引    数：strBusyoFrom  (I)画面：開始部署ｺｰﾄﾞ
     * 　    　：strBusyoTo    (I)画面：終了部署ｺｰﾄﾞ
     * 戻 り 値：SQL
     * 処理説明：経営成果対象でないデータを部署別集計ﾜｰｸから削除する(（GD）（DZM）データ連携サブシステムで使用)
     */
    function fncDeleteKanrSQL()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM WK_HSIMKANR KR" . "\r\n";
        $strSQL .= "WHERE   NOT EXISTS" . "\r\n";
        $strSQL .= "       (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "        FROM   HBUSYO BUS" . "\r\n";
        $strSQL .= "        WHERE  KR.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "        AND    BUS.PRN_KB5 = 'O'" . "\r\n";
        $strSQL .= ")" . "\r\n";
        return $strSQL;
    }

    public function fncDeleteKanr()
    {
        $strsql = $this->fncDeleteKanrSQL();
        return parent::Do_Execute($strsql);
    }

    /**
     * 処 理 名：ﾗﾝｷﾝｸﾞ一覧表用SQL
     * 関 数 名：fncRankingSelect
     * 引    数：strSyoriYM   (I)画面：開始部署ｺｰﾄﾞ
     * 　    　：strKI        (I)画面：終了部署ｺｰﾄﾞ
     * 　　　　：intNinzu     (I)画面：人数
     * 　　　　：intDaisu     (I)画面：台数
     * 　　　　：intKind      (I)1：新車ﾗﾝｷﾝｸﾞ　2：中古車ﾗﾝｷﾝｸﾞ　3：整備ﾗﾝｷﾝｸﾞ
     * 戻 り 値：SQL
     * 処理説明：ﾗﾝｷﾝｸﾞ一覧表用SQL(（GD）（DZM）データ連携サブシステムで使用)
     */
    function fncRankingSelectSQL($strSyoriYM, $strKI, $intNinzu, $intDaisu, $intKind)
    {
        $strSQL = "";
        $strSQL .= "SELECT GROUP_CNT" . "\r\n";
        $strSQL .= ",      LINE_NO" . "\r\n";
        $strSQL .= ",      SUBSTR(JPDATE('@SYORIYM'),2,2) NEN" . "\r\n";
        $strSQL .= ",      SUBSTR(JPDATE('@SYORIYM'),4,2) TUKI" . "\r\n";
        $strSQL .= ", '**** 第 @KI 期　部署別人員効率指標（経常利益）ランキング一覧表 ****' TITLE" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= ",      MAX(CASE WHEN GROUP_CNT = 0 THEN '（全社新車台当）' ELSE '' END) HED_OPT2" . "\r\n";
                break;
            case 2:
                $strSQL .= ",      MAX(CASE WHEN GROUP_CNT = 0 THEN '（全社中古車台当）' ELSE '' END) HED_OPT2" . "\r\n";
                break;
            case 3:
                $strSQL .= ",      MAX(CASE WHEN GROUP_CNT = 0 THEN '（全社整備人員当）' ELSE '' END) HED_OPT2" . "\r\n";
                break;
        }
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT = 0 AND JUNI = 0 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HED_BUS1" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT = 0 AND JUNI = 2 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HED_BUS2" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT <> 0 AND JUNI = 0 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER1" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT <> 0 AND JUNI = 0 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED1" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT <> 0 AND JUNI = 1 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER2" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT <> 0 AND JUNI = 1 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED2" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT <> 0 AND JUNI = 2 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER3" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT <> 0 AND JUNI = 2 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED3" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT <> 0 AND JUNI = 3 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER4" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT <> 0 AND JUNI = 3 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED4" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 4 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER5" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 4 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED5" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT <> 0 AND JUNI = 5 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER6" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN GROUP_CNT = 0 AND JUNI = 5 AND RANK > -1 THEN SUBSTRB(BUSYO_NM,1,10) ELSE (CASE WHEN JUNI = 5 THEN '(' || TO_CHAR(RANK - 6) || ')' END) END) TITLE_HED6" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 6 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER7" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 6 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED7" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 7 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER8" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 7 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED8" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 8 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER9" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 8 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED9" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 9 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER10" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 9 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED10" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 10 THEN SUBSTRB(BUSYO_NM,1,10) ELSE '' END) HEDDER11" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 10 AND RANK > -1 THEN '(' || TO_CHAR(RANK - 6) || ')' ELSE '' END) TITLE_HED11" . "\r\n";
        $strSQL .= ",      MEISYO.MOJI1" . "\r\n";
        $strSQL .= ",      (CASE WHEN MEISYO.SUCHI1 IS NOT NULL THEN MEISYO.MOJI2 ELSE LT.IDX_TANI END) TANI" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 0 THEN DECODE(LT.DISP_KB, 1, DECODE(GROUP_CNT, 0, '',JISSEKI), JISSEKI) ELSE '' END) LINE1" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 1 THEN DECODE(LT.DISP_KB, 1, DECODE(GROUP_CNT, 0, '',JISSEKI), JISSEKI) ELSE '' END) LINE2" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 2 THEN DECODE(LT.DISP_KB, 1, DECODE(GROUP_CNT, 0, '',JISSEKI), JISSEKI) ELSE '' END) LINE3" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 3 THEN DECODE(LT.DISP_KB, 1, DECODE(GROUP_CNT, 0, '',JISSEKI), JISSEKI) ELSE '' END) LINE4" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 4 THEN JISSEKI ELSE '' END) LINE5" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 5 THEN JISSEKI ELSE '' END) LINE6" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 6 THEN JISSEKI ELSE '' END) LINE7" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 7 THEN JISSEKI ELSE '' END) LINE8" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 8 THEN JISSEKI ELSE '' END) LINE9" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 9 THEN JISSEKI ELSE '' END) LINE10" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JUNI = 10 THEN JISSEKI ELSE '' END) LINE11" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "		SELECT LINE_NO" . "\r\n";
        $strSQL .= "		,      BUSYO_NM" . "\r\n";
        $strSQL .= "		,      TANI" . "\r\n";
        $strSQL .= "		,      JISSEKI" . "\r\n";
        $strSQL .= "		,      TRUNC(((CASE WHEN JUNI IN (-1,-2,-3) THEN LAST_JUNI + 1" . "\r\n";
        $strSQL .= "		                    ELSE JUNI END) - 0.9) / 11) GROUP_CNT" . "\r\n";
        $strSQL .= "		,      JUNI RANK" . "\r\n";
        $strSQL .= "		--,      LAST_JUNI" . "\r\n";
        $strSQL .= "		,      TRUNC(MOD(((CASE WHEN JUNI IN (-1,-2,-3) THEN LAST_JUNI + (11 - LAST_JUNI + 1 + JUNI)" . "\r\n";
        $strSQL .= "		                    ELSE JUNI END) - 0.9) , 11)) JUNI" . "\r\n";
        $strSQL .= "		FROM   (" . "\r\n";
        $strSQL .= "		        SELECT LINE_NO" . "\r\n";
        $strSQL .= "		        ,      BUSYO_NM" . "\r\n";
        $strSQL .= "		        ,      TANI" . "\r\n";
        $strSQL .= "		        ,      JISSEKI" . "\r\n";
        $strSQL .= "		        ,      JUNI" . "\r\n";
        $strSQL .= "		        ,      TRUNC((ROW_NUMBER()  OVER (ORDER BY JUNI DESC, LINE_NO ASC)  - 0.9) /82) + 1 LAST_JUNI" . "\r\n";
        $strSQL .= "		    " . "\r\n";
        $strSQL .= "				FROM   (" . "\r\n";
        $strSQL .= "						--本社実績" . "\r\n";
        $strSQL .= "						SELECT W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						,      W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						,      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= "						,      LINE.IDX_TANI TANI" . "\r\n";
        $strSQL .= "                     ,      TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(WK.TKI_ZAN,NVL(LINE.RND_POS,0)) / POWER(10,(NVL(LINE.RND_POS,0) * -1))),'FM9,999,999,999')) JISSEKI" . "\r\n";
        $strSQL .= "						,      1 JUNI" . "\r\n";
        $strSQL .= "						FROM" . "\r\n";
        $strSQL .= "						    (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "						    ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "						    FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "						    ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "						            FROM   WK_KANR" . "\r\n";
        $strSQL .= "						            GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "						    ) W_LINE" . "\r\n";
        $strSQL .= "						LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "						ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HBUSYO BUS" . "\r\n";
        $strSQL .= "						ON     BUS.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HLINEMST LINE" . "\r\n";
        $strSQL .= "						ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "						ON     MEI.MEISYOU_CD = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						AND    BUS.BUSYO_KB = MEI.MEISYOU_ID" . "\r\n";
        $strSQL .= "						        " . "\r\n";
        $strSQL .= "						WHERE LINE.LINE_NO < '83'" . "\r\n";
        switch ($intKind) {
            //新車の場合
            case 1:
                $strSQL .= "						AND   BUS.PRN_KB1 = '1'" . "\r\n";
                break;
            //中古車の場合
            case 2:
                $strSQL .= "						AND   BUS.PRN_KB2 = '1'" . "\r\n";
                break;
            //整備の場合
            case 3:
                $strSQL .= "						AND   BUS.PRN_KB3 = '1'" . "\r\n";
                break;
        }
        $strSQL .= "						UNION ALL" . "\r\n";
        $strSQL .= "						SELECT W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						,      W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						,      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= "						,      LINE.IDX_TANI TANI" . "\r\n";
        $strSQL .= "                     ,      TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(ROUND(WK.TKI_ZAN,NVL(LINE.RND_POS,0)) / POWER(10,(NVL(LINE.RND_POS,0) * -1)) / @NINZU,1)),'FM9,999,999,999.0')) HONSYA_SIHYO" . "\r\n";
        $strSQL .= "						,      2 JUNI		" . "\r\n";
        $strSQL .= "						FROM" . "\r\n";
        $strSQL .= "						    (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "						    ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "						    FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "						    ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "						            FROM   WK_KANR" . "\r\n";
        $strSQL .= "						            GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "						    ) W_LINE" . "\r\n";
        $strSQL .= "						LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "						ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HBUSYO BUS" . "\r\n";
        $strSQL .= "						ON     BUS.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HLINEMST LINE" . "\r\n";
        $strSQL .= "						ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "						ON     MEI.MEISYOU_CD = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						AND    BUS.BUSYO_KB = MEI.MEISYOU_ID" . "\r\n";
        $strSQL .= "						        " . "\r\n";
        $strSQL .= "						WHERE LINE.LINE_NO < '83'" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "						AND   BUS.PRN_KB1 = '1'" . "\r\n";
                break;
            case 2:
                $strSQL .= "						AND   BUS.PRN_KB2 = '1'" . "\r\n";
                break;
            case 3:
                $strSQL .= "						AND   BUS.PRN_KB2 = '1'" . "\r\n";
                break;
        }
        $strSQL .= "						" . "\r\n";
        $strSQL .= "						UNION ALL" . "\r\n";
        $strSQL .= "						" . "\r\n";
        $strSQL .= "						--新車本部実績" . "\r\n";
        $strSQL .= "						SELECT W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						,      W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						,      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= "						,      LINE.IDX_TANI TANI" . "\r\n";
        $strSQL .= "                     ,      TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(WK.TKI_ZAN,NVL(LINE.RND_POS,0)) / POWER(10,(NVL(LINE.RND_POS,0) * -1))),'FM9,999,999,999')) HONBU_JISSEKI" . "\r\n";
        $strSQL .= "						,      3 JUNI" . "\r\n";
        $strSQL .= "						FROM" . "\r\n";
        $strSQL .= "						    (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "						    ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "						    FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "						    ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "						            FROM   WK_KANR" . "\r\n";
        $strSQL .= "						            GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "						    ) W_LINE" . "\r\n";
        $strSQL .= "						LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "						ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HBUSYO BUS" . "\r\n";
        $strSQL .= "						ON     BUS.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HLINEMST LINE" . "\r\n";
        $strSQL .= "						ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "						ON     MEI.MEISYOU_CD = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						AND    BUS.BUSYO_KB = MEI.MEISYOU_ID" . "\r\n";
        $strSQL .= "						        " . "\r\n";
        $strSQL .= "						WHERE LINE.LINE_NO < '83'" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "						AND   BUS.PRN_KB1 = '2'" . "\r\n";
                break;
            case 2:
                $strSQL .= "						AND   BUS.PRN_KB2 = '2'" . "\r\n";
                break;
            case 3:
                $strSQL .= "						AND   BUS.PRN_KB3 = '2'" . "\r\n";
                break;
        }

        $strSQL .= "						" . "\r\n";
        $strSQL .= "						UNION ALL" . "\r\n";
        $strSQL .= "						SELECT W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						,      W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						,      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= "						,      LINE.IDX_TANI TANI" . "\r\n";
        $strSQL .= "                     ,      TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(ROUND(WK.TKI_ZAN,NVL(LINE.RND_POS,0)) / POWER(10,(NVL(LINE.RND_POS,0) * -1)) / @DAISU,1)),'FM9,999,999,999.0')) HONBU_SIHYO" . "\r\n";
        $strSQL .= "						,      4 JUNI	" . "\r\n";
        $strSQL .= "						FROM" . "\r\n";
        $strSQL .= "						    (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "						    ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "						    FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "						    ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "						            FROM   WK_KANR" . "\r\n";
        $strSQL .= "						            GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "						    ) W_LINE" . "\r\n";
        $strSQL .= "						LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "						ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HBUSYO BUS" . "\r\n";
        $strSQL .= "						ON     BUS.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HLINEMST LINE" . "\r\n";
        $strSQL .= "						ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN" . "\r\n";
        $strSQL .= "						       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "						ON     MEI.MEISYOU_CD = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "						AND    BUS.BUSYO_KB = MEI.MEISYOU_ID" . "\r\n";
        $strSQL .= "						        " . "\r\n";
        $strSQL .= "						WHERE LINE.LINE_NO < '83'" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "						AND   BUS.PRN_KB1 = '2'" . "\r\n";
                break;
            case 2:
                $strSQL .= "						AND   BUS.PRN_KB2 = '2'" . "\r\n";
                break;
            case 3:
                $strSQL .= "						AND   BUS.PRN_KB3 = '2'" . "\r\n";
                break;
        }

        $strSQL .= "						" . "\r\n";
        $strSQL .= "						UNION ALL" . "\r\n";
        $strSQL .= "						--先頭部署指標" . "\r\n";
        $strSQL .= "						SELECT SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						,      SLINE.LINE_NO" . "\r\n";
        $strSQL .= "						,      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= "						,      SLINE.IDX_TANI" . "\r\n";
        $strSQL .= "	                ,      NVL((CASE WHEN SIHYOU_KBN = 1 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23') " . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN " . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0 " . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SIHYO_ZAN.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                         THEN SLINE.TOU_ZAN ELSE SLINE.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                          , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                    ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 2 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SLINE.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SIHYO_ZAN.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                         , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                     ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 3 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SLINE.TKI_ZAN END)) / POWER(10,(NVL(LINE_T.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                           , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0'))" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 4" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SLINE.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                            , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0')) END)" . "\r\n";
        $strSQL .= "                               ,'') TKI_SIHYO" . "\r\n";
        $strSQL .= "						,      6 JUNI" . "\r\n";
        $strSQL .= "						FROM(" . "\r\n";
        $strSQL .= "							   SELECT" . "\r\n";
        $strSQL .= "          (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI1 ELSE LINE.IDX_LINE_NO END) SIHYOU_LINE" . "\r\n";
        $strSQL .= "    ,     (CASE WHEN (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE  LINE.IDX_TANI END) = '%' " . "\r\n";
        $strSQL .= "               THEN (CASE WHEN LINE.IDX_CAL_KB = '1' " . "\r\n";
        $strSQL .= "                          THEN 1" . "\r\n";
        $strSQL .= "                          ELSE 2 END)" . "\r\n";
        $strSQL .= "               ELSE (CASE WHEN LINE.IDX_CAL_KB = '1'" . "\r\n";
        $strSQL .= "                        THEN 3" . "\r\n";
        $strSQL .= "                        ELSE 4 END) END) SIHYOU_KBN" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI2 ELSE LINE.IDX_RND_POS END) IDX_RND_POS" . "\r\n";
        $strSQL .= "    ,      LINE.RND_POS" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE LINE.IDX_TANI END) IDX_TANI" . "\r\n";
        $strSQL .= "    ,      LINE.DISP_KB" . "\r\n";
        $strSQL .= "    ,      MEI.MOJI1" . "\r\n";
        $strSQL .= "    ,      W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "    ,      W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "    ,      WK.TOU_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZEN_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.TKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZENNENHI" . "\r\n";
        $strSQL .= "							   FROM   " . "\r\n";
        $strSQL .= "						           (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "							        ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "						            FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "							        ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "							                FROM   WK_KANR" . "\r\n";
        $strSQL .= "							                GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "							        ) W_LINE" . "\r\n";
        $strSQL .= "						    LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "						    ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						    AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HBUSYO BUS" . "\r\n";
        $strSQL .= "						    ON     BUS.BUSYO_CD = WK.BUSYO_CD" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HLINEMST LINE" . "\r\n";
        $strSQL .= "							ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "							ON     MEI.MEISYOU_CD = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "							AND    BUS.BUSYO_KB = MEI.MEISYOU_ID) SLINE" . "\r\n";
        $strSQL .= "						LEFT JOIN WK_KANR SIHYO_ZAN" . "\r\n";
        $strSQL .= "						ON   SIHYO_ZAN.LINE_NO = SLINE.SIHYOU_LINE" . "\r\n";
        $strSQL .= "						AND  SIHYO_ZAN.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "				    	LEFT JOIN HLINEMST LINE_T" . "\r\n";
        $strSQL .= "		                ON     LINE_T.LINE_NO = SIHYO_ZAN.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "						ON   BUS.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						" . "\r\n";
        $strSQL .= "						WHERE SLINE.LINE_NO < '83'" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "						AND   BUS.PRN_KB1 = '3'" . "\r\n";
                break;
            case 2:
                $strSQL .= "						AND   BUS.PRN_KB2 = '3'" . "\r\n";
                break;
            case 3:
                $strSQL .= "						AND   BUS.PRN_KB3 = '3'" . "\r\n";
                break;
        }
        $strSQL .= "						" . "\r\n";
        $strSQL .= "						UNION ALL" . "\r\n";
        $strSQL .= "						--ﾗﾝｷﾝｸﾞ対象指標" . "\r\n";
        $strSQL .= "						SELECT  RNK_JSK.BUSYO_CD" . "\r\n";
        $strSQL .= "						,       RNK_JSK.LINE_NO" . "\r\n";
        $strSQL .= "						,       RNK_JSK.BUSYO_NM" . "\r\n";
        $strSQL .= "						,       RNK_JSK.IDX_TANI" . "\r\n";
        $strSQL .= "						,       RNK_JSK.TKI_SIHYO" . "\r\n";
        $strSQL .= "                        ,       TRUNC((ROW_NUMBER() OVER(ORDER BY RNK_JSK.BUSYO_SHIHYO DESC) - 0.9)/82) + 7 BUSYO_CNT" . "\r\n";
        $strSQL .= "						" . "\r\n";
        $strSQL .= "						FROM   (" . "\r\n";
        $strSQL .= "								SELECT SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "								,      SLINE.LINE_NO" . "\r\n";
        $strSQL .= "								,      SLINE.SIHYOU_LINE" . "\r\n";
        $strSQL .= "								,      SLINE.SIHYOU_KBN" . "\r\n";
        $strSQL .= "								,      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= "								,      SLINE.IDX_TANI" . "\r\n";
        $strSQL .= "						        --,      SLINE.SORTKIN" . "\r\n";
        $strSQL .= "								,      SLINE.TOU_ZAN" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "						        ,      BUS.PRN_KB1" . "\r\n";
                break;
            case 2:
                $strSQL .= "						        ,      BUS.PRN_KB2" . "\r\n";
                break;
            case 3:
                $strSQL .= "						        ,      BUS.PRN_KB3" . "\r\n";
                break;
        }
        $strSQL .= "								,      NVL((CASE WHEN LINE82.SIHYOU_KBN82 = 1 AND NVL(LINE82.SORTKIN,0) <> 0 AND NVL(SIHYO_ZAN82.TKI_ZAN,0) <> 0 THEN ROUND(SIHYO_ZAN82.TKI_ZAN * 100 / LINE82.SORTKIN, NVL(LINE82.IDX_RND_POS,0))" . "\r\n";
        $strSQL .= "																             WHEN LINE82.SIHYOU_KBN82 = 2 AND NVL(LINE82.SORTKIN,0) <> 0 AND NVL(SIHYO_ZAN82.TKI_ZAN,0) <> 0 THEN ROUND(LINE82.SORTKIN * 100 / SIHYO_ZAN82.TKI_ZAN, NVL(LINE82.IDX_RND_POS,0))" . "\r\n";
        $strSQL .= "																             WHEN LINE82.SIHYOU_KBN82 = 3 AND NVL(LINE82.SORTKIN,0) <> 0 AND NVL(SIHYO_ZAN82.TKI_ZAN,0) <> 0 THEN ROUND(((SIHYO_ZAN82.TKI_ZAN / LINE82.SORTKIN) / POWER(10,(NVL(LINE82.RND_POS,0) * -1))), NVL(LINE82.IDX_RND_POS,0))" . "\r\n";
        $strSQL .= "																             WHEN LINE82.SIHYOU_KBN82 = 4 AND NVL(LINE82.SORTKIN,0) <> 0 AND NVL(SIHYO_ZAN82.TKI_ZAN,0) <> 0 THEN ROUND(((LINE82.SORTKIN / SIHYO_ZAN82.TKI_ZAN) / POWER(10,(NVL(LINE82.RND_POS,0) * -1))) , NVL(LINE82.IDX_RND_POS,0)) END),0) BUSYO_SHIHYO" . "\r\n";
        $strSQL .= "								,      ROUND(SLINE.TKI_ZAN,NVL(SLINE.RND_POS,0)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1)) TKI_JISSEKI" . "\r\n";
        $strSQL .= "	                ,      NVL((CASE WHEN SIHYOU_KBN = 1 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23') " . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN " . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0 " . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SIHYO_ZAN.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                         THEN SLINE.TOU_ZAN ELSE SLINE.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                          , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                    ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 2 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SLINE.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SIHYO_ZAN.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                         , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                     ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 3 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SLINE.TKI_ZAN END)) / POWER(10,(NVL(LINE_T.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                           , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0'))" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 4" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SLINE.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                            , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0')) END)" . "\r\n";
        $strSQL .= "                               ,'') TKI_SIHYO" . "\r\n";
        $strSQL .= "								FROM(" . "\r\n";
        $strSQL .= "									   SELECT" . "\r\n";
        $strSQL .= "          (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI1 ELSE LINE.IDX_LINE_NO END) SIHYOU_LINE" . "\r\n";
        $strSQL .= "    ,     (CASE WHEN (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE  LINE.IDX_TANI END) = '%' " . "\r\n";
        $strSQL .= "               THEN (CASE WHEN LINE.IDX_CAL_KB = '1' " . "\r\n";
        $strSQL .= "                          THEN 1" . "\r\n";
        $strSQL .= "                          ELSE 2 END)" . "\r\n";
        $strSQL .= "               ELSE (CASE WHEN LINE.IDX_CAL_KB = '1'" . "\r\n";
        $strSQL .= "                        THEN 3" . "\r\n";
        $strSQL .= "                        ELSE 4 END) END) SIHYOU_KBN" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI2 ELSE LINE.IDX_RND_POS END) IDX_RND_POS" . "\r\n";
        $strSQL .= "    ,      LINE.RND_POS" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE LINE.IDX_TANI END) IDX_TANI" . "\r\n";
        $strSQL .= "    ,      LINE.DISP_KB" . "\r\n";
        $strSQL .= "    ,      MEI.MOJI1" . "\r\n";
        $strSQL .= "    ,      W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "    ,      W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "    ,      WK.TOU_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZEN_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.TKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZENNENHI" . "\r\n";
        $strSQL .= "									   FROM   " . "\r\n";
        $strSQL .= "								           (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "									        ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "								            FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "									        ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "									                FROM   WK_KANR" . "\r\n";
        $strSQL .= "									                GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "									        ) W_LINE" . "\r\n";
        $strSQL .= "								    LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "								    ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "								    AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "									LEFT JOIN" . "\r\n";
        $strSQL .= "									       HBUSYO BUS" . "\r\n";
        $strSQL .= "								    ON     BUS.BUSYO_CD = WK.BUSYO_CD" . "\r\n";
        $strSQL .= "									LEFT JOIN" . "\r\n";
        $strSQL .= "									       HLINEMST LINE" . "\r\n";
        $strSQL .= "									ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "									LEFT JOIN" . "\r\n";
        $strSQL .= "									       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "									ON     MEI.MEISYOU_CD = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "									AND    BUS.BUSYO_KB = MEI.MEISYOU_ID) SLINE" . "\r\n";
        $strSQL .= "	                                INNER JOIN (SELECT" . "\r\n";
        $strSQL .= "	                                                      W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "                                                    ,     NVL(WK.TKI_ZAN,0) SORTKIN" . "\r\n";
        $strSQL .= "                                                    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI2 ELSE LINE.IDX_RND_POS END) IDX_RND_POS" . "\r\n";
        $strSQL .= "                                                    ,     LINE.RND_POS" . "\r\n";
        $strSQL .= "	                                                   ,     (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI1 ELSE LINE.IDX_LINE_NO END) SIHYOU_LINE82" . "\r\n";
        $strSQL .= "                                                    ,     (CASE WHEN (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE  LINE.IDX_TANI END) = '%' " . "\r\n";
        $strSQL .= "												                   THEN (CASE WHEN LINE.IDX_CAL_KB = '1' " . "\r\n";
        $strSQL .= "												                              THEN 1" . "\r\n";
        $strSQL .= "												                              ELSE 2 END)" . "\r\n";
        $strSQL .= "												                   ELSE (CASE WHEN LINE.IDX_CAL_KB = '1'" . "\r\n";
        $strSQL .= "												                              THEN 3" . "\r\n";
        $strSQL .= "												                              ELSE 4 END) END) SIHYOU_KBN82" . "\r\n";
        $strSQL .= "	                                                 FROM   " . "\r\n";
        $strSQL .= "											           (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "												        ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "											            FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "												        ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "												                FROM   WK_KANR" . "\r\n";
        $strSQL .= "												                GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "												        ) W_LINE" . "\r\n";
        $strSQL .= " 														LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "													    ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "													    AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "	                                                  	LEFT JOIN" . "\r\n";
        $strSQL .= "														       HBUSYO BUS" . "\r\n";
        $strSQL .= "													    ON     BUS.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "														LEFT JOIN" . "\r\n";
        $strSQL .= "														       HLINEMST LINE" . "\r\n";
        $strSQL .= "														ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "														LEFT JOIN" . "\r\n";
        $strSQL .= "														       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "														ON     MEI.MEISYOU_CD = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "														AND    BUS.BUSYO_KB = MEI.MEISYOU_ID" . "\r\n";
        $strSQL .= "	                                                    WHERE  W_LINE.LINE_NO = '82') LINE82" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "                                ON LINE82.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "								LEFT JOIN WK_KANR SIHYO_ZAN" . "\r\n";
        $strSQL .= "								ON   SIHYO_ZAN.LINE_NO = SLINE.SIHYOU_LINE" . "\r\n";
        $strSQL .= "								AND  SIHYO_ZAN.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "				            	LEFT JOIN HLINEMST LINE_T" . "\r\n";
        $strSQL .= "		                        ON     LINE_T.LINE_NO = SIHYO_ZAN.LINE_NO" . "\r\n";
        $strSQL .= "                                LEFT JOIN WK_KANR SIHYO_ZAN82" . "\r\n";
        $strSQL .= "							    ON   SIHYO_ZAN82.LINE_NO = LINE82.SIHYOU_LINE82" . "\r\n";
        $strSQL .= "                                AND  SIHYO_ZAN82.BUSYO_CD = LINE82.BUSYO_CD" . "\r\n";
        $strSQL .= "								LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "								ON   BUS.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "								" . "\r\n";
        $strSQL .= "								WHERE SLINE.LINE_NO < '83'" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "								AND   BUS.PRN_KB1 = '0'" . "\r\n";
                break;
            case 2:
                $strSQL .= "								AND   BUS.PRN_KB2 = '0'" . "\r\n";
                break;
            case 3:
                $strSQL .= "								AND   BUS.PRN_KB3 = '0'" . "\r\n";
                break;
        }

        $strSQL .= "								ORDER BY SORTKIN , SLINE.BUSYO_CD, SLINE.LINE_NO" . "\r\n";
        $strSQL .= "						        ) RNK_JSK" . "\r\n";
        $strSQL .= "				        UNION ALL" . "\r\n";
        $strSQL .= "						--ﾗｽﾄ1行目" . "\r\n";
        $strSQL .= "				　　　　SELECT SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						,      SLINE.LINE_NO" . "\r\n";
        $strSQL .= "						,      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= "						,      SLINE.IDX_TANI" . "\r\n";
        $strSQL .= "	                ,      NVL((CASE WHEN SIHYOU_KBN = 1 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23') " . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN " . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0 " . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SIHYO_ZAN.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                         THEN SLINE.TOU_ZAN ELSE SLINE.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                          , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                    ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 2 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SLINE.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SIHYO_ZAN.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                         , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                     ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 3 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SLINE.TKI_ZAN END)) / POWER(10,(NVL(LINE_T.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                           , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0'))" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 4" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SLINE.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                            , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0')) END)" . "\r\n";
        $strSQL .= "                               ,'') TKI_SIHYO" . "\r\n";
        $strSQL .= "						,      -3 JUNI" . "\r\n";
        $strSQL .= "						FROM(" . "\r\n";
        $strSQL .= "							   SELECT" . "\r\n";
        $strSQL .= "          (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI1 ELSE LINE.IDX_LINE_NO END) SIHYOU_LINE" . "\r\n";
        $strSQL .= "    ,     (CASE WHEN (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE  LINE.IDX_TANI END) = '%' " . "\r\n";
        $strSQL .= "               THEN (CASE WHEN LINE.IDX_CAL_KB = '1' " . "\r\n";
        $strSQL .= "                          THEN 1" . "\r\n";
        $strSQL .= "                          ELSE 2 END)" . "\r\n";
        $strSQL .= "               ELSE (CASE WHEN LINE.IDX_CAL_KB = '1'" . "\r\n";
        $strSQL .= "                        THEN 3" . "\r\n";
        $strSQL .= "                        ELSE 4 END) END) SIHYOU_KBN" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI2 ELSE LINE.IDX_RND_POS END) IDX_RND_POS" . "\r\n";
        $strSQL .= "    ,      LINE.RND_POS" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE LINE.IDX_TANI END) IDX_TANI" . "\r\n";
        $strSQL .= "    ,      LINE.DISP_KB" . "\r\n";
        $strSQL .= "    ,      MEI.MOJI1" . "\r\n";
        $strSQL .= "    ,      W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "    ,      W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "    ,      WK.TOU_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZEN_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.TKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZENNENHI" . "\r\n";
        $strSQL .= "							   FROM   " . "\r\n";
        $strSQL .= "						           (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "							        ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "						            FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "							        ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "							                FROM   WK_KANR" . "\r\n";
        $strSQL .= "							                GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "							        ) W_LINE" . "\r\n";
        $strSQL .= "						    LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "						    ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						    AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HBUSYO BUS" . "\r\n";
        $strSQL .= "						    ON     BUS.BUSYO_CD = WK.BUSYO_CD" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HLINEMST LINE" . "\r\n";
        $strSQL .= "							ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "							ON     MEI.MEISYOU_CD = WK.LINE_NO" . "\r\n";
        $strSQL .= "							AND    BUS.BUSYO_KB = MEI.MEISYOU_ID) SLINE" . "\r\n";
        $strSQL .= "						LEFT JOIN WK_KANR SIHYO_ZAN" . "\r\n";
        $strSQL .= "						ON   SIHYO_ZAN.LINE_NO = SLINE.SIHYOU_LINE" . "\r\n";
        $strSQL .= "						AND  SIHYO_ZAN.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "				    	LEFT JOIN HLINEMST LINE_T" . "\r\n";
        $strSQL .= "		                ON     LINE_T.LINE_NO = SIHYO_ZAN.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "						ON   BUS.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						" . "\r\n";
        $strSQL .= "						WHERE SLINE.LINE_NO < '83'" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "						AND   BUS.PRN_KB1 = '5'" . "\r\n";
                break;
            case 2:
                $strSQL .= "						AND   BUS.PRN_KB2 = '5'" . "\r\n";
                break;
            case 3:
                $strSQL .= "						AND   BUS.PRN_KB3 = '5'" . "\r\n";
                break;
        }

        $strSQL .= "				" . "\r\n";
        $strSQL .= "				        UNION ALL" . "\r\n";
        $strSQL .= "				        --ﾗｽﾄ2行目" . "\r\n";
        $strSQL .= "						SELECT SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						,      SLINE.LINE_NO" . "\r\n";
        $strSQL .= "						,      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= "						,      SLINE.IDX_TANI" . "\r\n";
        $strSQL .= "	                ,      NVL((CASE WHEN SIHYOU_KBN = 1 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23') " . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN " . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0 " . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SIHYO_ZAN.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                         THEN SLINE.TOU_ZAN ELSE SLINE.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                          , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                    ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 2 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SLINE.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SIHYO_ZAN.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                         , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                     ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 3 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SLINE.TKI_ZAN END)) / POWER(10,(NVL(LINE_T.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                           , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0'))" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 4" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SLINE.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                            , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0')) END)" . "\r\n";
        $strSQL .= "                               ,'') TKI_SIHYO" . "\r\n";
        $strSQL .= "						,      -2 JUNI" . "\r\n";
        $strSQL .= "						FROM(" . "\r\n";
        $strSQL .= "							   SELECT" . "\r\n";
        $strSQL .= "          (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI1 ELSE LINE.IDX_LINE_NO END) SIHYOU_LINE" . "\r\n";
        $strSQL .= "    ,     (CASE WHEN (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE  LINE.IDX_TANI END) = '%' " . "\r\n";
        $strSQL .= "               THEN (CASE WHEN LINE.IDX_CAL_KB = '1' " . "\r\n";
        $strSQL .= "                          THEN 1" . "\r\n";
        $strSQL .= "                          ELSE 2 END)" . "\r\n";
        $strSQL .= "               ELSE (CASE WHEN LINE.IDX_CAL_KB = '1'" . "\r\n";
        $strSQL .= "                        THEN 3" . "\r\n";
        $strSQL .= "                        ELSE 4 END) END) SIHYOU_KBN" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI2 ELSE LINE.IDX_RND_POS END) IDX_RND_POS" . "\r\n";
        $strSQL .= "    ,      LINE.RND_POS" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE LINE.IDX_TANI END) IDX_TANI" . "\r\n";
        $strSQL .= "    ,      LINE.DISP_KB" . "\r\n";
        $strSQL .= "    ,      MEI.MOJI1" . "\r\n";
        $strSQL .= "    ,      W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "    ,      W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "    ,      WK.TOU_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZEN_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.TKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZENNENHI" . "\r\n";
        $strSQL .= "							   FROM   " . "\r\n";
        $strSQL .= "						           (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "							        ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "						            FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "							        ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "							                FROM   WK_KANR" . "\r\n";
        $strSQL .= "							                GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "							        ) W_LINE" . "\r\n";
        $strSQL .= "						    LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "						    ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						    AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HBUSYO BUS" . "\r\n";
        $strSQL .= "						    ON     BUS.BUSYO_CD = WK.BUSYO_CD" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HLINEMST LINE" . "\r\n";
        $strSQL .= "							ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "							ON     MEI.MEISYOU_CD = WK.LINE_NO" . "\r\n";
        $strSQL .= "							AND    BUS.BUSYO_KB = MEI.MEISYOU_ID) SLINE" . "\r\n";
        $strSQL .= "						LEFT JOIN WK_KANR SIHYO_ZAN" . "\r\n";
        $strSQL .= "						ON   SIHYO_ZAN.LINE_NO = SLINE.SIHYOU_LINE" . "\r\n";
        $strSQL .= "						AND  SIHYO_ZAN.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "				    	LEFT JOIN HLINEMST LINE_T" . "\r\n";
        $strSQL .= "		                ON     LINE_T.LINE_NO = SIHYO_ZAN.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "						ON   BUS.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						" . "\r\n";
        $strSQL .= "						WHERE SLINE.LINE_NO < '83'" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "						AND   BUS.PRN_KB1 = '6'" . "\r\n";
                break;
            case 2:
                $strSQL .= "						AND   BUS.PRN_KB2 = '6'" . "\r\n";
                break;
            case 3:
                $strSQL .= "						AND   BUS.PRN_KB3 = '6'" . "\r\n";
                break;
        }
        $strSQL .= "				" . "\r\n";
        $strSQL .= "				        UNION ALL" . "\r\n";
        $strSQL .= "				        --ﾗｽﾄ3行目" . "\r\n";
        $strSQL .= "						SELECT SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						,      SLINE.LINE_NO" . "\r\n";
        $strSQL .= "						,      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= "						,      SLINE.IDX_TANI" . "\r\n";
        $strSQL .= "	                ,      NVL((CASE WHEN SIHYOU_KBN = 1 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23') " . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN " . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0 " . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SIHYO_ZAN.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                         THEN SLINE.TOU_ZAN ELSE SLINE.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                          , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                    ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 2 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(" . "\r\n";
        $strSQL .= "                                                                                         (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                               THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                               ELSE SLINE.TKI_ZAN END) * 100 / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SIHYO_ZAN.TKI_ZAN END)" . "\r\n";
        $strSQL .= "                                                                                         , NVL(SLINE.IDX_RND_POS,0)" . "\r\n";
        $strSQL .= "                                                                                         )" . "\r\n";
        $strSQL .= "                                                                     ,'FM9,999,999,999.00'))" . "\r\n";
        $strSQL .= "                                                   )" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 3 " . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                     THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                     ELSE SLINE.TKI_ZAN END)) / POWER(10,(NVL(LINE_T.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                           , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0'))" . "\r\n";
        $strSQL .= "							             WHEN SIHYOU_KBN = 4" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SLINE.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                              AND NVL((CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                            THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                            ELSE SIHYO_ZAN.TKI_ZAN END),0) <> 0" . "\r\n";
        $strSQL .= "                                         THEN TRIM(LEADING '0' FROM TO_CHAR(TO_CHAR(ROUND(((" . "\r\n";
        $strSQL .= "                                                                                           (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                 THEN SLINE.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                 ELSE SLINE.TKI_ZAN END) / (CASE WHEN SLINE.LINE_NO IN ('1','23')" . "\r\n";
        $strSQL .= "                                                                                                                                 THEN SIHYO_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= "                                                                                                                                 ELSE SIHYO_ZAN.TKI_ZAN END)) / POWER(10,(NVL(SLINE.RND_POS,0) * -1)))" . "\r\n";
        $strSQL .= "                                                                                            , NVL(SLINE.IDX_RND_POS,0))),'FM9,999,999,999.0')) END)" . "\r\n";
        $strSQL .= "                               ,'') TKI_SIHYO" . "\r\n";
        $strSQL .= "						,      -1 JUNI" . "\r\n";
        $strSQL .= "						FROM(" . "\r\n";
        $strSQL .= "							   SELECT" . "\r\n";
        $strSQL .= "          (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI1 ELSE LINE.IDX_LINE_NO END) SIHYOU_LINE" . "\r\n";
        $strSQL .= "    ,     (CASE WHEN (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE  LINE.IDX_TANI END) = '%' " . "\r\n";
        $strSQL .= "               THEN (CASE WHEN LINE.IDX_CAL_KB = '1' " . "\r\n";
        $strSQL .= "                          THEN 1" . "\r\n";
        $strSQL .= "                          ELSE 2 END)" . "\r\n";
        $strSQL .= "               ELSE (CASE WHEN LINE.IDX_CAL_KB = '1'" . "\r\n";
        $strSQL .= "                        THEN 3" . "\r\n";
        $strSQL .= "                        ELSE 4 END) END) SIHYOU_KBN" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.SUCHI2 ELSE LINE.IDX_RND_POS END) IDX_RND_POS" . "\r\n";
        $strSQL .= "    ,      LINE.RND_POS" . "\r\n";
        $strSQL .= "    ,      (CASE WHEN MEI.SUCHI1 IS NOT NULL THEN MEI.MOJI2 ELSE LINE.IDX_TANI END) IDX_TANI" . "\r\n";
        $strSQL .= "    ,      LINE.DISP_KB" . "\r\n";
        $strSQL .= "    ,      MEI.MOJI1" . "\r\n";
        $strSQL .= "    ,      W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "    ,      W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "    ,      WK.TOU_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZEN_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.TKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZKI_ZAN" . "\r\n";
        $strSQL .= "    ,      WK.ZENNENHI" . "\r\n";
        $strSQL .= "							   FROM   " . "\r\n";
        $strSQL .= "						           (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "							        ,      LI.LINE_NO" . "\r\n";
        $strSQL .= "						            FROM   HLINEMST LI" . "\r\n";
        $strSQL .= "							        ,      (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "							                FROM   WK_KANR" . "\r\n";
        $strSQL .= "							                GROUP BY BUSYO_CD) BUS" . "\r\n";
        $strSQL .= "							        ) W_LINE" . "\r\n";
        $strSQL .= "						    LEFT JOIN  WK_KANR WK" . "\r\n";
        $strSQL .= "						    ON         WK.BUSYO_CD = W_LINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						    AND        WK.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HBUSYO BUS" . "\r\n";
        $strSQL .= "						    ON     BUS.BUSYO_CD = WK.BUSYO_CD" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HLINEMST LINE" . "\r\n";
        $strSQL .= "							ON     LINE.LINE_NO = W_LINE.LINE_NO" . "\r\n";
        $strSQL .= "							LEFT JOIN" . "\r\n";
        $strSQL .= "							       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "							ON     MEI.MEISYOU_CD = WK.LINE_NO" . "\r\n";
        $strSQL .= "							AND    BUS.BUSYO_KB = MEI.MEISYOU_ID) SLINE" . "\r\n";
        $strSQL .= "						LEFT JOIN WK_KANR SIHYO_ZAN" . "\r\n";
        $strSQL .= "						ON   SIHYO_ZAN.LINE_NO = SLINE.SIHYOU_LINE" . "\r\n";
        $strSQL .= "						AND  SIHYO_ZAN.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "				    	LEFT JOIN HLINEMST LINE_T" . "\r\n";
        $strSQL .= "		                ON     LINE_T.LINE_NO = SIHYO_ZAN.LINE_NO" . "\r\n";
        $strSQL .= "						LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "						ON   BUS.BUSYO_CD = SLINE.BUSYO_CD" . "\r\n";
        $strSQL .= "						" . "\r\n";
        $strSQL .= "						WHERE SLINE.LINE_NO < '83'" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "						AND   BUS.PRN_KB1 = '7'" . "\r\n";
                break;
            case 2:
                $strSQL .= "						AND   BUS.PRN_KB2 = '7'" . "\r\n";
                break;
            case 3:
                $strSQL .= "						AND   BUS.PRN_KB3 = '7'" . "\r\n";
                break;
        }
        $strSQL .= "				" . "\r\n";
        $strSQL .= "				        ) V" . "\r\n";
        $strSQL .= "		       )WK_TBL" . "\r\n";
        $strSQL .= "		) S_RANK_TBL" . "\r\n";
        $strSQL .= "LEFT JOIN HMEISYOUMST MEISYO" . "\r\n";
        $strSQL .= "ON      MEISYO.MEISYOU_CD = S_RANK_TBL.LINE_NO" . "\r\n";
        switch ($intKind) {
            case 1:
                $strSQL .= "AND     MEISYO.MEISYOU_ID = 'S'" . "\r\n";
                break;
            case 2:
                $strSQL .= "AND     MEISYO.MEISYOU_ID = 'C'" . "\r\n";
                break;
            case 3:
                $strSQL .= "AND     MEISYO.MEISYOU_ID = 'F'" . "\r\n";
                break;
        }

        $strSQL .= "LEFT JOIN HLINEMST LT" . "\r\n";
        $strSQL .= "ON      LT.LINE_NO = S_RANK_TBL.LINE_NO" . "\r\n";
        $strSQL .= "GROUP BY  GROUP_CNT, LINE_NO, MEISYO.MOJI1, IDX_TANI, MEISYO.SUCHI1, MEISYO.MOJI2" . "\r\n";

        $strSQL = str_replace("@NINZU", $intNinzu, $strSQL);
        $strSQL = str_replace("@DAISU", $intDaisu, $strSQL);
        $strSQL = str_replace("@SYORIYM", $strSyoriYM, $strSQL);
        $strSQL = str_replace("@KI", $strKI, $strSQL);

        return $strSQL;
    }

    public function fncRankingSelect($strSyoriYM, $strKI, $intNinzu, $intDaisu, $intKind)
    {
        $strsql = $this->fncRankingSelectSQL($strSyoriYM, $strKI, $intNinzu, $intDaisu, $intKind);
        return parent::Do_Execute($strsql);
    }

}