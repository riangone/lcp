<?php
/**
 * 説明：
 *
 *
 * @author yushuangji
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
class FrmKanrChkList extends ClsComDb
{
    public function selectsql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= ",      SUBSTR(KISYU_YMD,1,6) KISYU" . "\r\n";
        $strSQL .= ",      KI" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";
        return $strSQL;
    }

    public function selectData()
    {
        return parent::select($this->selectsql());
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

    public function fncGetKamokuNM_sql()
    {
        $sqlstr = "";
        $sqlstr .= "SELECT A.KAMOK_CD KAMOK_CD,KAMOK_NM \n";
        $sqlstr .= "FROM  M_KAMOKU A \n";
        $sqlstr .= "WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD) \n";
        //$sqlstr .= "AND   A.KAMOK_CD = '@KAMOKUCD' \n";
        return $sqlstr;

    }

    public function fncGetKamokuNM()
    {
        return parent::select($this->fncGetKamokuNM_sql());
    }

    //**********************************************************************
    //処 理 名：部署別集計ファイルを読み込む
    //関 数 名：fncPrintSelect
    //引    数：無し
    //戻 り 値：SQL
    //処理説明：部署別集計ファイルを読み込む
    //**********************************************************************
    public function fncPrintSelectsql($cboKisyu, $cboYM, $txtKamokuCDFrom, $txtKamokuCDTo)
    {
        $strSQL = "";
        $strSQL .= "SELECT TOUKI.KAMOKU_CD" . "\r\n";
        $strSQL .= ",      DECODE(TOUKI.HIMOKU_CD,'00','',TOUKI.HIMOKU_CD) HIMOKU_CD" . "\r\n";
        $strSQL .= "--,    TOUKI.HIMOKU_CD" . "\r\n";

        $strSQL .= ",      (DECODE(KH.KAMOKUMEI,NULL,KMK.KAMOKUMEI,KH.KAMOKUMEI)) KAMOKUMEI" . "\r\n";

        $strSQL .= ",      (CASE WHEN K_LINE2.LINE_NO IS NOT NULL THEN LTRIM(TO_CHAR(K_LINE2.LINE_NO,'000')) ELSE LTRIM(TO_CHAR(K_LINE.LINE_NO,'000')) END) LINE_NO" . "\r\n";
        $strSQL .= ",      (TOUKI.BUSYO_CD) BUSYO_CD" . "\r\n";
        $strSQL .= ",      (NVL(BUS.BUSYO_NM,'*****')) BUSYOMEI" . "\r\n";
        $strSQL .= ",      (NVL(TOUKI_ZAN.ZANDAKA,0)) ZAN" . "\r\n";
        $strSQL .= ",      NVL(JIT.L_GK,0) LGK" . "\r\n";
        $strSQL .= ",      NVL(JIT.R_GK,0) RGK" . "\r\n";
        $strSQL .= ",      NVL(JIT.TOU_ZAN,0) TOUZAN" . "\r\n";
        $strSQL .= ",      NVL(TOUKI_ZAN.ZANDAKA,0) + NVL(JIT.TOU_ZAN,0) TOUKI_ZANDAKA" . "\r\n";
        $strSQL .= ",      NVL(ZEN_GETU.TOU_ZAN,0) ZEN_DOUGETU" . "\r\n";
        $strSQL .= ",      NVL(ZEN_NEN.ZANDAKA,0) ZEN_DOUNEN" . "\r\n";

        $strSQL .= ",      SUBSTR('@TOUGETU',1,4) NEN" . "\r\n";
        $strSQL .= ",      SUBSTR('@TOUGETU',5,2) TUKI" . "\r\n";

        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "       (SELECT KNR.BUSYO_CD, KNR.KAMOKU_CD, DECODE(TRIM(KNR.HIMOKU_CD),'','00',KNR.HIMOKU_CD) HIMOKU_CD, SUM(NVL(KNR.TOU_ZAN,0)) ZANDAKA" . "\r\n";
        $strSQL .= "        FROM   HKANRIZ KNR" . "\r\n";
        $strSQL .= "        WHERE    KNR.KEIJO_DT BETWEEN '@ZENKISYU' AND '@TOUGETU'" . "\r\n";
        $strSQL .= "        GROUP BY KNR.BUSYO_CD, KNR.KAMOKU_CD, DECODE(TRIM(KNR.HIMOKU_CD),'','00',KNR.HIMOKU_CD)) TOUKI" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT KNR.BUSYO_CD, KNR.KAMOKU_CD, DECODE(TRIM(KNR.HIMOKU_CD),'','00',KNR.HIMOKU_CD) HIMOKU_CD, SUM(NVL(KNR.TOU_ZAN,0)) ZANDAKA" . "\r\n";
        $strSQL .= "        FROM   HKANRIZ KNR" . "\r\n";
        $strSQL .= "        WHERE    KNR.KEIJO_DT BETWEEN '@KISYU' AND '@ZENGETU'" . "\r\n";
        $strSQL .= "        GROUP BY KNR.BUSYO_CD, KNR.KAMOKU_CD, DECODE(TRIM(KNR.HIMOKU_CD),'','00',KNR.HIMOKU_CD)) TOUKI_ZAN" . "\r\n";
        $strSQL .= "ON     TOUKI.BUSYO_CD = TOUKI_ZAN.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    TOUKI.KAMOKU_CD = TOUKI_ZAN.KAMOKU_CD" . "\r\n";
        $strSQL .= "AND    DECODE(TRIM(TOUKI.HIMOKU_CD),'','00',TOUKI.HIMOKU_CD) =  DECODE(TRIM(TOUKI_ZAN.HIMOKU_CD),'','00',TOUKI_ZAN.HIMOKU_CD)" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT * FROM HKANRIZ WHERE KEIJO_DT = '@TOUGETU') JIT" . "\r\n";
        $strSQL .= "ON     TOUKI.BUSYO_CD = JIT.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    TOUKI.KAMOKU_CD = JIT.KAMOKU_CD" . "\r\n";
        $strSQL .= "AND    DECODE(TRIM(TOUKI.HIMOKU_CD),'','00',TOUKI.HIMOKU_CD) =  DECODE(TRIM(JIT.HIMOKU_CD),'','00',JIT.HIMOKU_CD)" . "\r\n";

        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       (SELECT KNR.BUSYO_CD, KNR.KAMOKU_CD, KNR.HIMOKU_CD, KNR.TOU_ZAN" . "\r\n";
        $strSQL .= "        FROM   HKANRIZ KNR" . "\r\n";
        $strSQL .= "        WHERE  KNR.KEIJO_DT = '@ZENNEN') ZEN_GETU" . "\r\n";
        $strSQL .= "ON     ZEN_GETU.BUSYO_CD = TOUKI.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    ZEN_GETU.KAMOKU_CD = TOUKI.KAMOKU_CD" . "\r\n";
        $strSQL .= "AND    DECODE(TRIM(TOUKI.HIMOKU_CD),'','00',TOUKI.HIMOKU_CD) =  DECODE(TRIM(ZEN_GETU.HIMOKU_CD),'','00',ZEN_GETU.HIMOKU_CD)" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       (SELECT KNR.BUSYO_CD, KNR.KAMOKU_CD, DECODE(TRIM(KNR.HIMOKU_CD),'','00',KNR.HIMOKU_CD) HIMOKU_CD, SUM(NVL(KNR.TOU_ZAN,0)) ZANDAKA" . "\r\n";
        $strSQL .= "        FROM   HKANRIZ KNR" . "\r\n";
        $strSQL .= "        WHERE    KNR.KEIJO_DT BETWEEN '@ZENKISYU' AND '@ZENNEN'" . "\r\n";
        $strSQL .= "        GROUP BY KNR.BUSYO_CD, KNR.KAMOKU_CD, DECODE(TRIM(KNR.HIMOKU_CD),'','00',KNR.HIMOKU_CD)) ZEN_NEN" . "\r\n";
        $strSQL .= "ON     ZEN_NEN.BUSYO_CD = TOUKI.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    ZEN_NEN.KAMOKU_CD = TOUKI.KAMOKU_CD" . "\r\n";
        $strSQL .= "AND    DECODE(TRIM(TOUKI.HIMOKU_CD),'','00',TOUKI.HIMOKU_CD) =  DECODE(TRIM(ZEN_NEN.HIMOKU_CD),'','00',ZEN_NEN.HIMOKU_CD)" . "\r\n";

        // 科目マスタ統合のため
        $strSQL .= "LEFT JOIN (SELECT KAMOK_CD, KOMOK_CD, (KAMOK_NM || ' ' || KOMOK_NM) KAMOKUMEI FROM M_KAMOKU WHERE NVL(TRIM(KOMOK_CD),'00') <> '00') KH" . "\r\n";
        $strSQL .= "ON        KH.KAMOK_CD = TOUKI.KAMOKU_CD" . "\r\n";
        $strSQL .= "AND       KH.KOMOK_CD = TOUKI.HIMOKU_CD" . "\r\n";

        $strSQL .= "LEFT JOIN (SELECT KAMOK_CD, KAMOK_NM KAMOKUMEI" . "\r\n";
        $strSQL .= "           FROM M_KAMOKU A" . "\r\n";
        $strSQL .= "           WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        $strSQL .= "           ) KMK" . "\r\n";
        $strSQL .= "ON        KMK.KAMOK_CD = TOUKI.KAMOKU_CD" . "\r\n";

        //		$strSQL .= "LEFT JOIN (SELECT KAMOK_CD, HIMOK_CD, MIN(LINE_NO) LINE_NO FROM HKMKLINEMST GROUP BY KAMOK_CD, HIMOK_CD) K_LINE" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT KAMOK_CD, HIMOK_CD, MIN(LINE_NO) LINE_NO FROM HKMKLINEMST_KEIEISEIKA GROUP BY KAMOK_CD, HIMOK_CD) K_LINE" . "\r\n";
        $strSQL .= "ON     TOUKI.KAMOKU_CD = K_LINE.KAMOK_CD" . "\r\n";
        $strSQL .= "AND    K_LINE.HIMOK_CD = NVL(TRIM(TOUKI.HIMOKU_CD),'00')" . "\r\n";
        //		$strSQL .= "LEFT JOIN (SELECT KAMOK_CD, HIMOK_CD, MIN(LINE_NO) LINE_NO FROM HKMKLINEMST GROUP BY KAMOK_CD, HIMOK_CD) K_LINE2" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT KAMOK_CD, HIMOK_CD, MIN(LINE_NO) LINE_NO FROM HKMKLINEMST_KEIEISEIKA GROUP BY KAMOK_CD, HIMOK_CD) K_LINE2" . "\r\n";
        $strSQL .= "ON     TOUKI.KAMOKU_CD = K_LINE2.KAMOK_CD" . "\r\n";
        $strSQL .= "AND  DECODE(SUBSTR(K_LINE2.HIMOK_CD,2,1),'0',SUBSTR(K_LINE2.HIMOK_CD,1,1),K_LINE2.HIMOK_CD) = DECODE(SUBSTR(K_LINE2.HIMOK_CD,2,1),'0',SUBSTR(TOUKI.HIMOKU_CD,1,1),TOUKI.HIMOKU_CD,1,1)" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     TOUKI.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE ( NVL(TOUKI.ZANDAKA,0)<>0" . "\r\n";
        $strSQL .= "     OR NVL(JIT.L_GK,0)<>0" . "\r\n";
        $strSQL .= "     OR NVL(JIT.R_GK,0)<>0" . "\r\n";
        $strSQL .= "     OR NVL(ZEN_GETU.TOU_ZAN,0)<>0" . "\r\n";
        $strSQL .= "     OR NVL(ZEN_NEN.ZANDAKA,0)<>0" . "\r\n";
        $strSQL .= "     )" . "\r\n";

        //開始科目コードが入力された場合
        if ($txtKamokuCDFrom != "") {
            $strSQL .= "AND     TOUKI.KAMOKU_CD >= '@F_KAMOKU'" . "\r\n";
        }
        //終了科目コードが入力された場合
        if ($txtKamokuCDTo != "") {
            $strSQL .= "AND     TOUKI.KAMOKU_CD <= '@T_KAMOKU'" . "\r\n";
        }
        $strSQL .= "ORDER BY 1,2,4,5" . "\r\n";

        //当期以前の年月を出力できるように変更
        $strSQL = str_replace("@KISYU", str_replace("/", "", $cboKisyu), $strSQL);
        $strSQL = str_replace("@TOUGETU", str_replace("/", "", $cboYM), $strSQL);
        $cboYM = $cboYM . "/01";
        $strSQL = str_replace("@ZENGETU", date("Ym", strtotime("$cboYM -1 month")), $strSQL);
        $strSQL = str_replace("@ZENNEN", date("Ym", strtotime("$cboYM -1 year")), $strSQL);
        //当期以前の年月を出力できるように変更
        $cboKisyu = $cboKisyu . "/01";
        $strSQL = str_replace("@ZENKISYU", date("Ym", strtotime("$cboKisyu -1 year")), $strSQL);

        $strSQL = str_replace("@F_KAMOKU", $txtKamokuCDFrom, $strSQL);
        $strSQL = str_replace("@T_KAMOKU", $txtKamokuCDTo, $strSQL);
        //		$this->log($strSQL);
        return $strSQL;
    }

    public function fncPrintSelect($cboKisyu, $cboYM, $txtKamokuCDFrom, $txtKamokuCDTo)
    {
        return parent::select($this->fncPrintSelectsql($cboKisyu, $cboYM, $txtKamokuCDFrom, $txtKamokuCDTo));
    }

}
