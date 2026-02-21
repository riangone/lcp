<?php
/**
 * 説明：損益明細書
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */

namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFncKRSS;
class FrmSonekiMeisai extends ClsComDb
{
    public function selectsql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= ",      KISYU_YMD KISYU" . "\r\n";
        $strSQL .= ",      KI" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";
        return $strSQL;
    }

    public function selectData()
    {
        return parent::select($this->selectsql());
    }

    //********************************************************************
    //処理概要：権限チェック
    //引　　数：なし
    //戻 り 値：ＳＱＬ
    //********************************************************************
    public function fncAuthChecksql()
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $ClsComFncKRSS = new ClsComFncKRSS();
        $strSQL = "";
        $strSQL .= " SELECT SYAIN_NO " . "\r\n";
        $strSQL .= "     ,      BUSYO_CD " . "\r\n";
        $strSQL .= " FROM HAUTHORITY_CTL " . "\r\n";
        $strSQL .= " WHERE SYAIN_NO = '@SYAIN_NO' " . "\r\n";
        $strSQL .= " AND   SYS_KB = '@SYS_KB'" . "\r\n";

        $strSQL .= " GROUP BY SYAIN_NO " . "\r\n";
        $strSQL .= "     ,        BUSYO_CD " . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $UPDUSER, $strSQL);
        //$strSQL = str_replace("@SYS_KB", 1, $strSQL);
        $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);
        return $strSQL;
    }

    public function fncAuthCheck()
    {
        return parent::select($this->fncAuthChecksql());
    }

    public function fncGetBusyosql()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_CD ";
        $strSQL .= ", BUSYO_NM ";
        $strSQL .= "  FROM ";
        $strSQL .= "  HBUSYO ";
        $strSQL .= "  WHERE ";
        $strSQL .= "  SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1' ";
        return $strSQL;
    }

    public function fncGetBusyo()
    {
        return parent::select($this->fncGetBusyosql());
    }

    public function fncPrintSelectsql($strKI, $cboKisyu, $cboYM, $txtBusyoCDFrom, $txtBusyoCDTo, $AUTHID)
    {
        $ClsComFncKRSS = new ClsComFncKRSS();
        $strSQL = "";
        $strSQL .= "SELECT  V.BUSYO_CD" . "\r\n";
        $strSQL .= ",       BS.BUSYO_NM" . "\r\n";
        $strSQL .= ",       '@M_KI' KI" . "\r\n";
        $strSQL .= ",       '@GATUDO' GATUDO" . "\r\n";
        $strSQL .= ",       SUBSTR('@KISYU',1,4) || '/' || SUBSTR('@KISYU',5,2) KISYU" . "\r\n";
        $strSQL .= ",       SUBSTR('@SYORITUKI',1,4) || '/' || SUBSTR('@SYORITUKI',5,2) SYORITUKI" . "\r\n";
        $strSQL .= ",       SUBSTR('@ZENGETU1',1,4) || '/' || SUBSTR('@ZENGETU1',5,2) ZENGETU1" . "\r\n";
        $strSQL .= ",       SUBSTR('@ZENGETU2',1,4) || '/' || SUBSTR('@ZENGETU2',5,2) ZENGETU2" . "\r\n";
        $strSQL .= ",       SUBSTR('@ZENGETU3',1,4) || '/' || SUBSTR('@ZENGETU3',5,2) ZENGETU3" . "\r\n";
        $strSQL .= ",       SUBSTR('@ZENGETU4',1,4) || '/' || SUBSTR('@ZENGETU4',5,2) ZENGETU4" . "\r\n";
        $strSQL .= ",       SUBSTR('@ZENGETU5',1,4) || '/' || SUBSTR('@ZENGETU5',5,2) ZENGETU5" . "\r\n";
        $strSQL .= ",       V.LINE_NO" . "\r\n";
        $strSQL .= ",       V.ITEM_NM" . "\r\n";

        $strSQL .= ",       MIN(DECODE(KMK.KAMOKUMEI,NULL,A_K.KAMOKUMEI,KMK.KAMOKUMEI)) MEISYOU" . "\r\n";

        $strSQL .= ",       V.KAMOKU_CD" . "\r\n";
        $strSQL .= ",       DECODE(V.HIMOK_CD,'00','',V.HIMOK_CD) HIMOK_CD" . "\r\n";
        $strSQL .= ",       SUM(V.TOUGETU) S_TOUGETU" . "\r\n";
        $strSQL .= ",       SUM(V.ZENGETU1) S_ZENGETU1" . "\r\n";
        $strSQL .= ",       SUM(V.ZENGETU2) S_ZENGETU2" . "\r\n";
        $strSQL .= ",       SUM(V.ZENGETU3) S_ZENGETU3" . "\r\n";
        $strSQL .= ",       SUM(V.ZENGETU4) S_ZENGETU4" . "\r\n";
        $strSQL .= ",       SUM(V.ZENGETU5) S_ZENGETU5" . "\r\n";
        $strSQL .= ",       SUM(V.TOUKI_JISSEKI) S_JISSEKI" . "\r\n";
        $strSQL .= ",       SUM(V.KAMIKI) S_KAMIKI" . "\r\n";
        $strSQL .= ",       SUM(V.SIMOKI) S_SIMOKI" . "\r\n";
        $strSQL .= ",       YSN.TOUGETUYOSAN" . "\r\n";
        $strSQL .= ",       YSN.TOUKIYOSAN" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "FROM    (SELECT RIZ.BUSYO_CD" . "\r\n";
        $strSQL .= "		,      LINE.LINE_NO" . "\r\n";
        $strSQL .= "        ,      LINE.ITEM_NM" . "\r\n";
        $strSQL .= "		,      RIZ.KAMOKU_CD" . "\r\n";
        $strSQL .= "		,      RIZ.HIMOK_CD" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@SYORITUKI' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) TOUGETU" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU1' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU1" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU2' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU2" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU3' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU3" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU4' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU4" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU5' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU5" . "\r\n";
        $strSQL .= "		,      SUM(NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1)) TOUKI_JISSEKI" . "\r\n";

        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT >= '@KISYU' AND RIZ.KEIJO_DT <= '@HANTUKI' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) KAMIKI" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT > '@HANTUKI' AND RIZ.KEIJO_DT <= '@SYORITUKI' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) SIMOKI" . "\r\n";

        $strSQL .= "		FROM   (SELECT KEIJO_DT" . "\r\n";
        $strSQL .= "		        ,      BUSYO_CD" . "\r\n";
        $strSQL .= "		        ,      KAMOKU_CD" . "\r\n";
        $strSQL .= "		        ,      (CASE WHEN KAMOKU_CD = '43220' THEN '00' ELSE DECODE(TRIM(HIMOKU_CD),'','00',HIMOKU_CD) END) HIMOK_CD" . "\r\n";
        $strSQL .= "		        ,      TOU_ZAN" . "\r\n";
        $strSQL .= "		        FROM   HKANRIZ" . "\r\n";
        $strSQL .= "		        WHERE  SUBSTR(KAMOKU_CD,1,1) > '3') RIZ" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       HBUSYO BUS" . "\r\n";
        $strSQL .= "		ON     BUS.BUSYO_CD = RIZ.BUSYO_CD" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       (SELECT KAMOK_CD" . "\r\n";
        $strSQL .= "		        ,      DECODE(TRIM(HIMOK_CD),'','00',HIMOK_CD) HIMOKU_CD" . "\r\n";
        $strSQL .= "		        ,      LINE_NO" . "\r\n";
        $strSQL .= "		        ,      CAL_KB" . "\r\n";
        //			$strSQL .= "		        FROM   HKMKLINEMST" . "\r\n";
        $strSQL .= "		        FROM   HKMKLINEMST_KEIEISEIKA" . "\r\n";
        $strSQL .= "		        WHERE  LINE_NO <> '999')KLINE" . "\r\n";
        $strSQL .= "		ON     KLINE.KAMOK_CD = RIZ.KAMOKU_CD" . "\r\n";
        $strSQL .= "	    AND   (KLINE.HIMOKU_CD = NVL(TRIM(RIZ.HIMOK_CD),'00')" . "\r\n";
        $strSQL .= "         OR (DECODE(SUBSTR(KLINE.HIMOKU_CD,2,1),'0',SUBSTR(KLINE.HIMOKU_CD,1,1),KLINE.HIMOKU_CD) = DECODE(SUBSTR(KLINE.HIMOKU_CD,2,1),'0',SUBSTR(RIZ.HIMOK_CD,1,1),RIZ.HIMOK_CD,1,1)))" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        //			$strSQL .= "		       HLINEMST LINE" . "\r\n";
        $strSQL .= "		       HLINEMST_KEIEISEIKA LINE" . "\r\n";
        $strSQL .= "		ON     LINE.LINE_NO = KLINE.LINE_NO" . "\r\n";
        $strSQL .= "		WHERE  RIZ.KEIJO_DT >= '@KISYU'" . "\r\n";
        $strSQL .= "		AND    RIZ.KEIJO_DT <= '@SYORITUKI'" . "\r\n";
        //			$strSQL .= "		AND    LINE.SONEK_PRN_FLG = 'O'" . "\r\n";
        $strSQL .= "		AND    LINE.SONEK_PRN_FLG = '0'" . "\r\n";
        $strSQL .= "		" . "\r\n";
        $strSQL .= "		GROUP BY RIZ.BUSYO_CD, LINE.LINE_NO, LINE.ITEM_NM, RIZ.KAMOKU_CD, RIZ.HIMOK_CD" . "\r\n";
        $strSQL .= "		" . "\r\n";
        $strSQL .= "		UNION ALL" . "\r\n";
        $strSQL .= "		SELECT SBUS.TOTAL_BUSYO_CD" . "\r\n";
        $strSQL .= "		,      LINE.LINE_NO" . "\r\n";
        $strSQL .= "     ,      LINE.ITEM_NM" . "\r\n";
        $strSQL .= "		,      RIZ.KAMOKU_CD" . "\r\n";
        $strSQL .= "		,      RIZ.HIMOK_CD" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@SYORITUKI' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) TOUGETU" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU1' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU1" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU2' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU2" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU3' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU3" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU4' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU4" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU5' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU5" . "\r\n";
        $strSQL .= "		,      SUM(NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1)) TOUKI_JISSEKI" . "\r\n";

        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT >= '@KISYU' AND RIZ.KEIJO_DT <= '@HANTUKI' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) KAMIKI" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT > '@HANTUKI' AND RIZ.KEIJO_DT <= '@SYORITUKI' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) SIMOKI" . "\r\n";

        $strSQL .= "		FROM   (SELECT KEIJO_DT" . "\r\n";
        $strSQL .= "		        ,      BUSYO_CD" . "\r\n";
        $strSQL .= "		        ,      KAMOKU_CD" . "\r\n";
        $strSQL .= "		        ,      (CASE WHEN KAMOKU_CD = '43220' THEN '00' ELSE DECODE(TRIM(HIMOKU_CD),'','00',HIMOKU_CD) END) HIMOK_CD" . "\r\n";
        $strSQL .= "		        ,      TOU_ZAN" . "\r\n";
        $strSQL .= "		        FROM   HKANRIZ" . "\r\n";
        $strSQL .= "		        WHERE  SUBSTR(KAMOKU_CD,1,1) > '3') RIZ" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       HBUSYO BUS" . "\r\n";
        $strSQL .= "		ON     BUS.BUSYO_CD = RIZ.BUSYO_CD" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       HTTLBUSYO SBUS" . "\r\n";
        $strSQL .= "		ON     SBUS.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       (SELECT KAMOK_CD" . "\r\n";
        $strSQL .= "		        ,      NVL(TRIM(HIMOK_CD),'00') HIMOKU_CD" . "\r\n";
        $strSQL .= "		        ,      LINE_NO" . "\r\n";
        $strSQL .= "		        ,      CAL_KB" . "\r\n";
        //			$strSQL .= "		        FROM   HKMKLINEMST" . "\r\n";
        $strSQL .= "		        FROM   HKMKLINEMST_KEIEISEIKA " . "\r\n";
        $strSQL .= "		        WHERE  LINE_NO <> '999')KLINE" . "\r\n";
        $strSQL .= "		ON     KLINE.KAMOK_CD = RIZ.KAMOKU_CD" . "\r\n";
        $strSQL .= "	    AND   (KLINE.HIMOKU_CD = NVL(TRIM(RIZ.HIMOK_CD),'00')" . "\r\n";
        $strSQL .= "         OR (DECODE(SUBSTR(KLINE.HIMOKU_CD,2,1),'0',SUBSTR(KLINE.HIMOKU_CD,1,1),KLINE.HIMOKU_CD) = DECODE(SUBSTR(KLINE.HIMOKU_CD,2,1),'0',SUBSTR(RIZ.HIMOK_CD,1,1),RIZ.HIMOK_CD,1,1)))" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        //			$strSQL .= "		       HLINEMST LINE" . "\r\n";
        $strSQL .= "		       HLINEMST_KEIEISEIKA LINE" . "\r\n";
        $strSQL .= "		ON     LINE.LINE_NO = KLINE.LINE_NO" . "\r\n";
        $strSQL .= "	" . "\r\n";
        $strSQL .= "		WHERE  RIZ.KEIJO_DT >= '@KISYU'" . "\r\n";
        $strSQL .= "		AND    RIZ.KEIJO_DT <= '@SYORITUKI'" . "\r\n";
        //			$strSQL .= "		AND    LINE.SONEK_PRN_FLG = 'O'" . "\r\n";
//			$strSQL .= "		AND    LINE.SONEK_PRN_FLG = '0'" . "\r\n";
        $strSQL .= "		AND    LINE.SONEK_PRN_FLG is not null " . "\r\n";
        $strSQL .= "		GROUP BY SBUS.TOTAL_BUSYO_CD, LINE.LINE_NO, LINE.ITEM_NM, RIZ.KAMOKU_CD, RIZ.HIMOK_CD" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "		UNION ALL" . "\r\n";
        $strSQL .= "		SELECT '000'" . "\r\n";
        $strSQL .= "		,      LINE.LINE_NO" . "\r\n";
        $strSQL .= "        ,      LINE.ITEM_NM" . "\r\n";
        $strSQL .= "		,      RIZ.KAMOKU_CD" . "\r\n";
        $strSQL .= "		,      RIZ.HIMOK_CD" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@SYORITUKI' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) TOUGETU" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU1' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU1" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU2' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU2" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU3' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU3" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU4' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU4" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT = '@ZENGETU5' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) ZENGETU5" . "\r\n";
        $strSQL .= "		,      SUM(NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1)) TOUKI_JISSEKI" . "\r\n";

        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT >= '@KISYU' AND RIZ.KEIJO_DT <= '@HANTUKI' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) KAMIKI" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN RIZ.KEIJO_DT > '@HANTUKI' AND RIZ.KEIJO_DT <= '@SYORITUKI' THEN NVL(RIZ.TOU_ZAN,0)*NVL(KLINE.CAL_KB,1) ELSE 0 END) SIMOKI" . "\r\n";

        $strSQL .= "		FROM   (SELECT KEIJO_DT" . "\r\n";
        $strSQL .= "		        ,      BUSYO_CD" . "\r\n";
        $strSQL .= "		        ,      KAMOKU_CD" . "\r\n";
        $strSQL .= "		        ,      (CASE WHEN KAMOKU_CD = '43220' THEN '00' ELSE DECODE(TRIM(HIMOKU_CD),'','00',HIMOKU_CD) END) HIMOK_CD" . "\r\n";
        $strSQL .= "		        ,      TOU_ZAN" . "\r\n";
        $strSQL .= "		        FROM   HKANRIZ" . "\r\n";
        $strSQL .= "		        WHERE  SUBSTR(KAMOKU_CD,1,1) > '3') RIZ" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       HBUSYO BUS" . "\r\n";
        $strSQL .= "		ON     BUS.BUSYO_CD = RIZ.BUSYO_CD" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       (SELECT KAMOK_CD" . "\r\n";
        $strSQL .= "		        ,      NVL(TRIM(HIMOK_CD),'00') HIMOKU_CD" . "\r\n";
        $strSQL .= "		        ,      LINE_NO" . "\r\n";
        $strSQL .= "		        ,      CAL_KB" . "\r\n";
        //			$strSQL .= "		        FROM   HKMKLINEMST" . "\r\n";
        $strSQL .= "		        FROM   HKMKLINEMST_KEIEISEIKA" . "\r\n";
        $strSQL .= "		        WHERE  LINE_NO <> '999')KLINE" . "\r\n";
        $strSQL .= "		ON     KLINE.KAMOK_CD = RIZ.KAMOKU_CD" . "\r\n";
        $strSQL .= "	    AND   (KLINE.HIMOKU_CD = NVL(TRIM(RIZ.HIMOK_CD),'00')" . "\r\n";
        $strSQL .= "         OR (DECODE(SUBSTR(KLINE.HIMOKU_CD,2,1),'0',SUBSTR(KLINE.HIMOKU_CD,1,1),KLINE.HIMOKU_CD) = DECODE(SUBSTR(KLINE.HIMOKU_CD,2,1),'0',SUBSTR(RIZ.HIMOK_CD,1,1),RIZ.HIMOK_CD,1,1)))" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        //			$strSQL .= "		       HLINEMST LINE" . "\r\n";
        $strSQL .= "		       HLINEMST_KEIEISEIKA LINE" . "\r\n";
        $strSQL .= "		ON     LINE.LINE_NO = KLINE.LINE_NO" . "\r\n";
        $strSQL .= "	" . "\r\n";
        $strSQL .= "		WHERE  RIZ.KEIJO_DT >= '@KISYU'" . "\r\n";
        $strSQL .= "		AND    RIZ.KEIJO_DT <= '@SYORITUKI'" . "\r\n";
        //			$strSQL .= "		AND    LINE.SONEK_PRN_FLG = 'O'" . "\r\n";
//			$strSQL .= "		AND    LINE.SONEK_PRN_FLG = '0'" . "\r\n";
        $strSQL .= "		AND    LINE.SONEK_PRN_FLG is not null " . "\r\n";
        $strSQL .= "		GROUP BY  LINE.LINE_NO, LINE.ITEM_NM, RIZ.KAMOKU_CD, RIZ.HIMOK_CD" . "\r\n";
        $strSQL .= ") V" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "INNER JOIN " . "\r\n";
        $strSQL .= "        HBUSYO BS" . "\r\n";
        $strSQL .= "ON      BS.BUSYO_CD = V.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "        (SELECT KI" . "\r\n";
        $strSQL .= "         ,      BUSYO_CD" . "\r\n";
        $strSQL .= "         ,      LINE_NO" . "\r\n";
        //			$strSQL .= "         ,      NVL(YSN_GK6,0) TOUGETUYOSAN" . "\r\n";
        $strSQL .= "         ,      CASE WHEN  '@GATUDO' ='10' THEN NVL(YSN_GK10,0) " . "\r\n";
        $strSQL .= "                          WHEN  '@GATUDO' ='11' THEN NVL(YSN_GK11,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='12' THEN NVL(YSN_GK12,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='01' THEN NVL(YSN_GK1,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='02' THEN NVL(YSN_GK2,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='03' THEN NVL(YSN_GK3,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='04' THEN NVL(YSN_GK4,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='05' THEN NVL(YSN_GK5,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='06' THEN NVL(YSN_GK6,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='07' THEN NVL(YSN_GK7,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='08' THEN NVL(YSN_GK8,0) " . "\r\n";
        $strSQL .= "                          WHEN '@GATUDO' ='09' THEN NVL(YSN_GK9,0) " . "\r\n";
        $strSQL .= "                          ELSE 0 END TOUGETUYOSAN " . "\r\n";
        $strSQL .= "         ,      NVL(YSN_GK10,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK11,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK12,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK1,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK2,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK3,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK4,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK5,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK6,0)" . "\r\n";

        $strSQL .= "         +      NVL(YSN_GK7,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK8,0)" . "\r\n";
        $strSQL .= "         +      NVL(YSN_GK9,0)" . "\r\n";

        $strSQL .= "         AS TOUKIYOSAN     " . "\r\n";
        //			$strSQL .= "         FROM   HYOSAN" . "\r\n";
        $strSQL .= "         FROM   HYOSAN_NEW" . "\r\n";
        //			$strSQL .= "         WHERE  KI = '@KISYU') YSN" . "\r\n";
        $strSQL .= "         WHERE  YOSAN_YMD = '@KISYU') YSN" . "\r\n";
        $strSQL .= "ON      YSN.BUSYO_CD = V.BUSYO_CD" . "\r\n";
        $strSQL .= "AND     YSN.LINE_NO = V.LINE_NO" . "\r\n";

        $strSQL .= "LEFT JOIN (SELECT KAMOK_CD, KOMOK_CD, (KAMOK_NM || ' ' || KOMOK_NM) KAMOKUMEI FROM M_KAMOKU WHERE NVL(TRIM(KOMOK_CD),'00') <> '00') KMK" . "\r\n";
        $strSQL .= "ON        KMK.KAMOK_CD = V.KAMOKU_CD" . "\r\n";
        $strSQL .= "AND       KMK.KOMOK_CD = V.HIMOK_CD" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT KAMOK_CD, KAMOK_NM KAMOKUMEI" . "\r\n";
        $strSQL .= "           FROM M_KAMOKU A" . "\r\n";
        $strSQL .= "           WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        $strSQL .= "           ) A_K" . "\r\n";
        $strSQL .= "ON        A_K.KAMOK_CD = V.KAMOKU_CD" . "\r\n";

        $strSQL .= "WHERE   BS.PRN_KB5 = 'O'" . "\r\n";
        $strSQL .= "AND     BS.PRN_KB4 = 'O'" . "\r\n";

        //ﾕｰｻﾞが保持している権限の部署のみを表示できるように追加
        $strSQL .= "AND     V.BUSYO_CD IN (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "                       FROM   HAUTHORITY_CTL AUT" . "\r\n";
        $strSQL .= "                       WHERE AUT.SYAIN_NO = '@LOGINUSER'" . "\r\n";
        $strSQL .= "                       AND    AUT.SYS_KB = '@SYS_KB'" . "\r\n";

        $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);
        $strSQL .= "                       AND AUT.HAUTH_ID = '@AUTHID')" . "\r\n";

        $strSQL .= "" . "\r\n";

        if ($txtBusyoCDFrom != "") {
            $strSQL .= "AND     V.BUSYO_CD >= '@F_BUSYO'" . "\r\n";
        }

        if ($txtBusyoCDTo != "") {
            $strSQL .= "AND     V.BUSYO_CD <= '@T_BUSYO'" . "\r\n";
        }

        $strSQL .= "GROUP BY V.BUSYO_CD, V.LINE_NO, V.KAMOKU_CD, V.HIMOK_CD, YSN.TOUGETUYOSAN, YSN.TOUKIYOSAN, BS.BUSYO_NM, V.ITEM_NM" . "\r\n";
        $strSQL .= "HAVING   SUM(V.TOUKI_JISSEKI) <> 0" . "\r\n";
        $strSQL .= "ORDER BY V.BUSYO_CD, V.LINE_NO" . "\r\n";
        $strSQL = str_replace("@M_KI", $strKI, $strSQL);
        $strSQL = str_replace("@GATUDO", substr($cboYM, 5, 2), $strSQL);
        $strSQL = str_replace("@KISYU", str_replace("/", "", $cboKisyu), $strSQL);
        $strSQL = str_replace("@SYORITUKI", str_replace("/", "", $cboYM), $strSQL);
        $cboYM = $cboYM . "/01";
        $strSQL = str_replace("@ZENGETU1", date("Ym", strtotime("$cboYM -1 month")), $strSQL);
        $strSQL = str_replace("@ZENGETU2", date("Ym", strtotime("$cboYM -2 month")), $strSQL);
        $strSQL = str_replace("@ZENGETU3", date("Ym", strtotime("$cboYM -3 month")), $strSQL);
        $strSQL = str_replace("@ZENGETU4", date("Ym", strtotime("$cboYM -4 month")), $strSQL);
        $strSQL = str_replace("@ZENGETU5", date("Ym", strtotime("$cboYM -5 month")), $strSQL);
        $strKisyuYMD = $cboKisyu . "/01";
        $strSQL = str_replace("@HANTUKI", date("Ym", strtotime("$strKisyuYMD +5 month")), $strSQL);
        $strSQL = str_replace("@F_BUSYO", $txtBusyoCDFrom, $strSQL);
        $strSQL = str_replace("@T_BUSYO", $txtBusyoCDTo, $strSQL);

        //ﾛｸﾞｲﾝﾕｰｻﾞ
        $strSQL = str_replace("@LOGINUSER", $this->GS_LOGINUSER['strUserID'], $strSQL);
        //登録権限のＩＤをセット
        $strSQL = str_replace("@AUTHID", str_replace("cmd", "", $AUTHID), $strSQL);
        //$this->log($strSQL);
        return $strSQL;
    }

    public function fncPrintSelect($strKI, $cboKisyu, $cboYM, $txtBusyoCDFrom, $txtBusyoCDTo, $AUTHID)
    {
        return parent::select($this->fncPrintSelectsql($strKI, $cboKisyu, $cboYM, $txtBusyoCDFrom, $txtBusyoCDTo, $AUTHID));
    }

}
