<?php
/**
 * 説明：
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
class FrmGENRILISTKRSS extends ClsComDb
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

    public function fncGenriIchiransql($intAuth, $cboYM, $txtBusyoCDFrom, $txtBusyoCDTo, $buttonNM)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $ClsComFncKRSS = new ClsComFncKRSS();
        $strSQL = "";
        $strSQL .= "SELECT   '@TODAY' TODAY" . "\r\n";
        $strSQL .= ",        KANRISYA.SYAIN_NM KANRISYAMEI" . "\r\n";
        $strSQL .= ",        GRI.KUKURI_BUSYO||GRI.ATUKAI_BUSYO KUBUSYO" . "\r\n";
        $strSQL .= ",        GRI.DATA_KB" . "\r\n";
        $strSQL .= ",        GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= ",        BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",        '(' || GRI.ATUKAI_SYAIN || ')' ATUKAI_SYAIN" . "\r\n";
        $strSQL .= ",        SYA.SYAIN_NM SYAINMEI" . "\r\n";
        $strSQL .= ",        (CASE WHEN  NVL(GRI.CKO_CGY,' ') <> '2'" . "\r\n";
        $strSQL .= "               THEN (CASE WHEN GRI.ATUKAI_GYOSYA < '9000' OR GRI.GYOUSYA_NM = '' THEN GYO.GYOSYA_NM ELSE GRI.GYOUSYA_NM END) ELSE '' END) GYOUSYA_NM" . "\r\n";
        $strSQL .= ",        GRI.SYADAIKATA" . "\r\n";
        $strSQL .= ",        GRI.MEIGININ" . "\r\n";
        $strSQL .= ",        GRI.UC_NO" . "\r\n";
        $strSQL .= ",        GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= ",        NVL(GRI.ATUKAI_GYOSYA,' ') ATUKAI_GYOSYA" . "\r\n";
        $strSQL .= ",        (CASE WHEN NVL(GRI.KAIYAKU_YMD,' ') = ' ' THEN '' ELSE 'ｶ' END) KAIYAKU_KB" . "\r\n";
        $strSQL .= ",        GRI.URIAGE SIN_URIAGE" . "\r\n";
        $strSQL .= ",        GRI.SYARYOU_RIEKI SIN_SYARYOU_RIEKI" . "\r\n";
        $strSQL .= ",        GRI.KASOU_RIEKI SIN_KASOU_RIEKI" . "\r\n";
        $strSQL .= ",        0 SIN_KAPPU_RIEKI" . "\r\n";
        $strSQL .= ",        GRI.TOUROKU_RIEKI SIN_TOUROKU_RIEKI" . "\r\n";
        $strSQL .= ",        GRI.UCHIKOMIKIN SIN_UCHIKOMIKIN" . "\r\n";
        $strSQL .= ",        0  SIN_URI_GENKA " . "\r\n";
        $strSQL .= ",        GRI.SITADORI_SON SIN_SITADORI_SON" . "\r\n";
        $strSQL .= ",        GRI.HANBAITESURYO SIN_HANBAITESURYO" . "\r\n";
        $strSQL .= ",        GRI.SYOUKAIRYO SIN_SYOUKAIRYO" . "\r\n";
        $strSQL .= ",        GRI.CHUMONSYO_GENRI SIN_CHUKOSYA_GENRI" . "\r\n";
        $strSQL .= ",        GRI.TOUGETU_GENRI SIN_TOUGETU_GENRI" . "\r\n";
        $strSQL .= ",        GRI.DAISU SIN_DAISU " . "\r\n";
        $strSQL .= ",        GRI.SIT_DAISU SIN_SIT_DAISU" . "\r\n";
        $strSQL .= ",        NULL CHU_URIAGE " . "\r\n";
        $strSQL .= ",        NULL CHU_SYARYOU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL CHU_KASOU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL CHU_KAPPU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL CHU_TOUROKU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL CHU_UCHIKOMIKIN " . "\r\n";
        $strSQL .= ",        NULL CHU_URI_GENKA " . "\r\n";
        $strSQL .= ",        NULL CHU_SITADORI_SON" . "\r\n";
        $strSQL .= ",        NULL CHU_HANBAITESURYO" . "\r\n";
        $strSQL .= ",        NULL CHU_SYOUKAIRYO" . "\r\n";
        $strSQL .= ",        NULL CHU_CHUKOSYA_GENRI" . "\r\n";
        $strSQL .= ",        NULL CHU_TOUGETU_GENRI" . "\r\n";
        $strSQL .= ",        NULL CHU_DAISU" . "\r\n";
        $strSQL .= ",        NULL CHU_SIT_DAISU" . "\r\n";
        $strSQL .= ",        NULL TA_URIAGE " . "\r\n";
        $strSQL .= ",        NULL TA_SYARYOU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL TA_KASOU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL TA_KAPPU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL TA_TOUROKU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL TA_UCHIKOMIKIN " . "\r\n";
        $strSQL .= ",        NULL TA_URI_GENKA " . "\r\n";
        $strSQL .= ",        NULL TA_SITADORI_SON" . "\r\n";
        $strSQL .= ",        NULL TA_HANBAITESURYO" . "\r\n";
        $strSQL .= ",        NULL TA_SYOUKAIRYO" . "\r\n";
        $strSQL .= ",        NULL TA_CHUKOSYA_GENRI" . "\r\n";
        $strSQL .= ",        NULL TA_TOUGETU_GENRI" . "\r\n";
        $strSQL .= ",        NULL TA_DAISU" . "\r\n";
        $strSQL .= ",        NULL TA_SIT_DAISU" . "\r\n";
        $strSQL .= ",        SIN_TOUKI.SIN_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= ",        SIN_TOUKI.SIN_TOUKI_DAISU" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_BUSYO.SIN_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_BUSYO.SIN_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= ",        CHU_TOUKI.CHU_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= ",        CHU_TOUKI.CHU_TOUKI_DAISU" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_BUSYO.CHU_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_BUSYO.CHU_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= ",        TA_TOUKI.TA_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= ",        TA_TOUKI.TA_TOUKI_DAISU" . "\r\n";
        $strSQL .= ",        TA_TOUKI_BUSYO.TA_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= ",        TA_TOUKI_BUSYO.TA_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_TOTAL.SIN_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_TOTAL.SIN_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_TOTAL.CHU_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_TOTAL.CHU_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= ",        TA_TOUKI_TOTAL.TA_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= ",        TA_TOUKI_TOTAL.TA_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= ",        GRI.CMN_NO CMN_NO" . "\r\n";
        $strSQL .= "FROM  HGENRI_VW GRI" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_BUSYO = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_SYAIN = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST KANRISYA" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_BUSYO = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND       BUS.MANEGER_CD = KANRISYA.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HGYOSYAMST GYO" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_GYOSYA = GYO.GYOSYA_CD" . "\r\n";
        $strSQL .= "----新車ｾｰﾙｽ当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           , TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           , TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           , TOUKI_G.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "           , SUM(TOUKI_G.TOUGETU_GENRI) SIN_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= "           , SUM(TOUKI_G.DAISU)  SIN_TOUKI_DAISU" . "\r\n";
        $strSQL .= "        FROM HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           , HKEIRICTL CTL" . "\r\n";
        $strSQL .= "       WHERE  CTL.ID = '01'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.DATA_KB = '1'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "       GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,    TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,    TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,    TOUKI_G.ATUKAI_SYAIN) SIN_TOUKI" . "\r\n";
        $strSQL .= "           ON   SIN_TOUKI.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND  SIN_TOUKI.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           AND  SIN_TOUKI.ATUKAI_SYAIN = GRI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "----新車部署当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           , TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           , TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           , SUM(TOUGETU_GENRI) SIN_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= "           , SUM(TOUKI_G.DAISU)  SIN_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= "        FROM HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           , HKEIRICTL CTL" . "\r\n";
        $strSQL .= "       WHERE  CTL.ID = '01'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.DATA_KB = '1'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "       GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,    TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,    TOUKI_G.ATUKAI_BUSYO) SIN_TOUKI_BUSYO" . "\r\n";
        $strSQL .= "           ON   SIN_TOUKI_BUSYO.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND  SIN_TOUKI_BUSYO.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";

        $strSQL .= "----中古ｾｰﾙｽ当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI)  CHU_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) CHU_TOUKI_DAISU" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.DATA_KB = '2'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_SYAIN) CHU_TOUKI" . "\r\n";
        $strSQL .= "           ON      CHU_TOUKI.KUKURI_BUSYO = SIN_TOUKI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND     CHU_TOUKI.ATUKAI_BUSYO = SIN_TOUKI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           AND     CHU_TOUKI.ATUKAI_SYAIN = SIN_TOUKI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "----中古部署当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI)  CHU_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) CHU_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.DATA_KB = '2'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO) CHU_TOUKI_BUSYO" . "\r\n";
        $strSQL .= "           ON      CHU_TOUKI_BUSYO.KUKURI_BUSYO = SIN_TOUKI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND     CHU_TOUKI_BUSYO.ATUKAI_BUSYO = SIN_TOUKI.ATUKAI_BUSYO" . "\r\n";

        $strSQL .= "----他ｾｰﾙｽ当期計" . "\r\n";
        $strSQL .= "LEFT JOIN ( SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.TOUGETU_GENRI)  TA_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.DAISU) TA_TOUKI_DAISU" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   TOUKI_G.DATA_KB = '3'" . "\r\n";
        $strSQL .= "           AND     CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_SYAIN) TA_TOUKI" . "\r\n";
        $strSQL .= "           ON   TA_TOUKI.KUKURI_BUSYO = SIN_TOUKI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND  TA_TOUKI.ATUKAI_BUSYO = SIN_TOUKI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           AND  TA_TOUKI.ATUKAI_SYAIN = SIN_TOUKI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "----他部署当期計" . "\r\n";
        $strSQL .= "LEFT JOIN ( SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.TOUGETU_GENRI)  TA_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.DAISU) TA_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   TOUKI_G.DATA_KB = '3'" . "\r\n";
        $strSQL .= "           AND     CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO) TA_TOUKI_BUSYO" . "\r\n";
        $strSQL .= "           ON   TA_TOUKI_BUSYO.KUKURI_BUSYO = SIN_TOUKI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND  TA_TOUKI_BUSYO.ATUKAI_BUSYO = SIN_TOUKI.ATUKAI_BUSYO" . "\r\n";

        $strSQL .= "----新車全社当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           , SUM(TOUGETU_GENRI) SIN_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= "           , SUM(TOUKI_G.DAISU)  SIN_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= "        FROM HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           , HKEIRICTL CTL" . "\r\n";
        $strSQL .= "       WHERE  CTL.ID = '01'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.DATA_KB = '1'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "       GROUP BY TOUKI_G.DATA_KB) SIN_TOUKI_TOTAL" . "\r\n";
        $strSQL .= "         ON   1=1" . "\r\n";
        $strSQL .= "----中古全社計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI)  CHU_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) CHU_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.DATA_KB = '2'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB) CHU_TOUKI_TOTAL" . "\r\n";
        $strSQL .= "         ON   1=1" . "\r\n";
        $strSQL .= "----他全社当期計" . "\r\n";
        $strSQL .= "LEFT JOIN ( SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.TOUGETU_GENRI)  TA_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.DAISU) TA_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   TOUKI_G.DATA_KB = '3'" . "\r\n";
        $strSQL .= "           AND     CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB) TA_TOUKI_TOTAL" . "\r\n";
        $strSQL .= "         ON   1=1" . "\r\n";
        $strSQL .= "WHERE GRI.NENGETU = '@NENGETU' " . "\r\n";
        $strSQL .= "  AND GRI.DATA_KB = '1'" . "\r\n";
        $strSQL .= "  AND (GRI.URIAGE <> 0" . "\r\n";
        $strSQL .= "   OR GRI.SYARYOU_RIEKI <> 0" . "\r\n";
        $strSQL .= "   OR GRI.KASOU_RIEKI <> 0" . "\r\n";
        $strSQL .= "   OR GRI.TOUROKU_RIEKI <> 0" . "\r\n";
        $strSQL .= "   OR GRI.UCHIKOMIKIN <> 0" . "\r\n";
        $strSQL .= "   OR GRI.SITADORI_SON <> 0" . "\r\n";
        $strSQL .= "   OR GRI.HANBAITESURYO <> 0" . "\r\n";
        $strSQL .= "   OR GRI.SYOUKAIRYO <> 0" . "\r\n";
        $strSQL .= "   OR GRI.CHUMONSYO_GENRI <> 0" . "\r\n";
        $strSQL .= "   OR GRI.TOUGETU_GENRI <> 0)" . "\r\n";
        //権限のある部署のみ表示

        if ($intAuth == 0) {
            $strSQL .= "  AND GRI.ATUKAI_BUSYO IN (SELECT BUSYO_CD" . "\r\n";
            $strSQL .= "                           FROM   HAUTHORITY_CTL" . "\r\n";
            $strSQL .= "                           WHERE  HAUTH_ID = '@AUTHID'" . "\r\n";
            $strSQL .= "                           AND    SYS_KB = '@SYS_KB'" . "\r\n";
            $strSQL .= "                           AND    SYAIN_NO = '@USERID')" . "\r\n";
            $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);
        }

        if ($txtBusyoCDFrom != "") {
            $strSQL .= "  AND GRI.ATUKAI_BUSYO >= '@BUSYOF'" . "\r\n";
        }

        if ($txtBusyoCDTo != "") {
            $strSQL .= "  AND GRI.ATUKAI_BUSYO <= '@BUSYOT'" . "\r\n";
        }

        $strSQL .= "--" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT   '@TODAY' TODAY" . "\r\n";
        $strSQL .= ",        KANRISYA.SYAIN_NM KANRISYAMEI" . "\r\n";
        $strSQL .= ",        GRI.KUKURI_BUSYO||GRI.ATUKAI_BUSYO KUBUSYO" . "\r\n";
        $strSQL .= ",        GRI.DATA_KB" . "\r\n";
        $strSQL .= ",        GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= ",        BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",        '(' || GRI.ATUKAI_SYAIN || ')' ATUKAI_SYAIN" . "\r\n";
        $strSQL .= ",        SYA.SYAIN_NM SYAINMEI" . "\r\n";
        $strSQL .= ",        (CASE WHEN  NVL(GRI.CKO_CGY,' ') <> '2'" . "\r\n";
        $strSQL .= "               THEN (CASE WHEN GRI.ATUKAI_GYOSYA < '9000' OR GRI.GYOUSYA_NM = '' THEN GYO.GYOSYA_NM ELSE GRI.GYOUSYA_NM END) ELSE '' END) GYOUSYA_NM" . "\r\n";
        $strSQL .= ",        SUBSTR(NENSIKI,3,2)||GRI.SYADAIKATA SYADAIKATA" . "\r\n";
        $strSQL .= ",        GRI.MEIGININ" . "\r\n";
        $strSQL .= "--,        '拠点処分'" . "\r\n";
        $strSQL .= ",        GRI.UC_NO" . "\r\n";
        $strSQL .= ",        GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= ",        NVL(GRI.ATUKAI_GYOSYA,' ') ATUKAI_GYOSYA" . "\r\n";
        $strSQL .= ",        (CASE WHEN NVL(GRI.KAIYAKU_YMD,' ') = ' ' THEN '' ELSE 'ｶ' END) KAIYAKU_KB" . "\r\n";
        $strSQL .= ",          NULL SIN_URIAGE " . "\r\n";
        $strSQL .= ",          NULL SIN_SYARYOU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL SIN_KASOU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL SIN_KAPPU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL SIN_TOUROKU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL SIN_UCHIKOMIKIN " . "\r\n";
        $strSQL .= ",          NULL SIN_URI_GENKA " . "\r\n";
        $strSQL .= ",          NULL SIN_SITADORI_SON" . "\r\n";
        $strSQL .= ",          NULL SIN_HANBAITESURYO" . "\r\n";
        $strSQL .= ",          NULL SIN_SYOUKAIRYO" . "\r\n";
        $strSQL .= ",          NULL SIN_CHUKOSYA_GENRI" . "\r\n";
        $strSQL .= ",          NULL SIN_TOUGETU_GENRI" . "\r\n";
        $strSQL .= ",          NULL SIN_DAISU" . "\r\n";
        $strSQL .= ",          NULL SIN_SIT_DAISU" . "\r\n";
        $strSQL .= ",         GRI.URIAGE" . "\r\n";
        $strSQL .= ",         GRI.SYARYOU_RIEKI" . "\r\n";
        $strSQL .= ",         GRI.KASOU_RIEKI" . "\r\n";
        $strSQL .= ",        0 KAPPU_RIEKI" . "\r\n";
        $strSQL .= ",        GRI.TOUROKU_RIEKI" . "\r\n";
        $strSQL .= ",        GRI.UCHIKOMIKIN" . "\r\n";
        $strSQL .= ",        GRI.URI_GENKA" . "\r\n";
        $strSQL .= ",        GRI.SITADORI_SON" . "\r\n";
        $strSQL .= ",        GRI.HANBAITESURYO" . "\r\n";
        $strSQL .= ",        GRI.SYOUKAIRYO" . "\r\n";
        $strSQL .= ",        GRI.CHUMONSYO_GENRI" . "\r\n";
        $strSQL .= ",        GRI.TOUGETU_GENRI" . "\r\n";
        $strSQL .= ",        GRI.DAISU CHU_DAISU" . "\r\n";
        $strSQL .= ",        GRI.SIT_DAISU CHU_SITDAISU" . "\r\n";
        $strSQL .= ",        NULL TA_URIAGE " . "\r\n";
        $strSQL .= ",        NULL TA_SYARYOU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL TA_KASOU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL TA_KAPPU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL TA_TOUROKU_RIEKI" . "\r\n";
        $strSQL .= ",        NULL TA_UCHIKOMIKIN " . "\r\n";
        $strSQL .= ",        NULL TA_URI_GENKA " . "\r\n";
        $strSQL .= ",        NULL TA_SITADORI_SON" . "\r\n";
        $strSQL .= ",        NULL TA_HANBAITESURYO" . "\r\n";
        $strSQL .= ",        NULL TA_SYOUKAIRYO" . "\r\n";
        $strSQL .= ",        NULL TA_CHUKOSYA_GENRI" . "\r\n";
        $strSQL .= ",        NULL TA_TOUGETU_GENRI" . "\r\n";
        $strSQL .= ",        NULL TA_DAISU" . "\r\n";
        $strSQL .= ",        NULL TA_SIT_DAISU" . "\r\n";
        $strSQL .= ",        SIN_TOUKI.SIN_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= ",        SIN_TOUKI.SIN_TOUKI_DAISU" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_BUSYO.SIN_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_BUSYO.SIN_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= ",        CHU_TOUKI.CHU_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= ",        CHU_TOUKI.CHU_TOUKI_DAISU" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_BUSYO.CHU_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_BUSYO.CHU_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= ",        TA_TOUKI.TA_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= ",        TA_TOUKI.TA_TOUKI_DAISU" . "\r\n";
        $strSQL .= ",        TA_TOUKI_BUSYO.TA_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= ",        TA_TOUKI_BUSYO.TA_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_TOTAL.SIN_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_TOTAL.SIN_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_TOTAL.CHU_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_TOTAL.CHU_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= ",        TA_TOUKI_TOTAL.TA_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= ",        TA_TOUKI_TOTAL.TA_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= ",        GRI.CMN_NO CMN_NO" . "\r\n";
        $strSQL .= "FROM  HGENRI_VW GRI" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_BUSYO = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_SYAIN = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST KANRISYA" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_BUSYO = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND       BUS.MANEGER_CD = KANRISYA.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HGYOSYAMST GYO" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_GYOSYA = GYO.GYOSYA_CD" . "\r\n";
        $strSQL .= "----新車ｾｰﾙｽ当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.TOUGETU_GENRI) SIN_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU)  SIN_TOUKI_DAISU" . "\r\n";
        $strSQL .= "           FROM   HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "                 ,HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE  CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND    TOUKI_G.DATA_KB = '1'" . "\r\n";
        $strSQL .= "           AND    TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_SYAIN) SIN_TOUKI" . "\r\n";
        $strSQL .= "  ON   SIN_TOUKI.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "  AND  SIN_TOUKI.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "  AND  SIN_TOUKI.ATUKAI_SYAIN = GRI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "----新車部署当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.TOUGETU_GENRI) SIN_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU)  SIN_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= "           FROM   HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "                 ,HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE  CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND    TOUKI_G.DATA_KB = '1'" . "\r\n";
        $strSQL .= "           AND    TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO) SIN_TOUKI_BUSYO" . "\r\n";
        $strSQL .= "  ON   SIN_TOUKI_BUSYO.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "  AND  SIN_TOUKI_BUSYO.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "----中古ｾｰﾙｽ当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI)  CHU_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) CHU_TOUKI_DAISU" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.DATA_KB = '2'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_SYAIN) CHU_TOUKI" . "\r\n";
        $strSQL .= "           ON      CHU_TOUKI.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND     CHU_TOUKI.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           AND     CHU_TOUKI.ATUKAI_SYAIN = GRI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "----中古部署当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI)  CHU_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) CHU_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.DATA_KB = '2'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO) CHU_TOUKI_BUSYO" . "\r\n";
        $strSQL .= "           ON      CHU_TOUKI_BUSYO.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND     CHU_TOUKI_BUSYO.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "----他ｾｰﾙｽ当期計" . "\r\n";
        $strSQL .= "LEFT JOIN ( SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,       TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,       TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,       TOUKI_G.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "                  ,SUM(TOUKI_G.TOUGETU_GENRI) TA_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= "                  ,SUM(TOUKI_G.DAISU) TA_TOUKI_DAISU" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   TOUKI_G.DATA_KB = '3'" . "\r\n";
        $strSQL .= "           AND     CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_SYAIN) TA_TOUKI" . "\r\n";
        $strSQL .= "           ON   TA_TOUKI.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND  TA_TOUKI.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           AND  TA_TOUKI.ATUKAI_SYAIN = GRI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "----他部署当期計" . "\r\n";
        $strSQL .= "LEFT JOIN ( SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,       TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,       TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "                  ,SUM(TOUKI_G.TOUGETU_GENRI) TA_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= "                  ,SUM(TOUKI_G.DAISU) TA_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   TOUKI_G.DATA_KB = '3'" . "\r\n";
        $strSQL .= "           AND     CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO) TA_TOUKI_BUSYO" . "\r\n";
        $strSQL .= "           ON   TA_TOUKI_BUSYO.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND  TA_TOUKI_BUSYO.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "----新車全社当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           , SUM(TOUGETU_GENRI) SIN_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= "           , SUM(TOUKI_G.DAISU)  SIN_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= "        FROM HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           , HKEIRICTL CTL" . "\r\n";
        $strSQL .= "       WHERE  CTL.ID = '01'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.DATA_KB = '1'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "       GROUP BY TOUKI_G.DATA_KB) SIN_TOUKI_TOTAL" . "\r\n";
        $strSQL .= "         ON   1=1" . "\r\n";
        $strSQL .= "----中古全社計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI)  CHU_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) CHU_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.DATA_KB = '2'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB) CHU_TOUKI_TOTAL" . "\r\n";
        $strSQL .= "         ON   1=1" . "\r\n";
        $strSQL .= "----他全社当期計" . "\r\n";
        $strSQL .= "LEFT JOIN ( SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.TOUGETU_GENRI)  TA_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.DAISU) TA_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   TOUKI_G.DATA_KB = '3'" . "\r\n";
        $strSQL .= "           AND     CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB) TA_TOUKI_TOTAL" . "\r\n";
        $strSQL .= "         ON   1=1" . "\r\n";
        $strSQL .= "WHERE GRI.NENGETU = '@NENGETU' " . "\r\n";
        $strSQL .= "  AND GRI.DATA_KB = '2'" . "\r\n";
        $strSQL .= "  AND (GRI.URIAGE <> 0" . "\r\n";
        $strSQL .= "   OR  GRI.SYARYOU_RIEKI <> 0" . "\r\n";
        $strSQL .= "   OR  GRI.KASOU_RIEKI <> 0" . "\r\n";
        $strSQL .= "   OR  GRI.TOUROKU_RIEKI <> 0" . "\r\n";
        $strSQL .= "   OR  GRI.UCHIKOMIKIN <> 0" . "\r\n";
        $strSQL .= "   OR  GRI.SITADORI_SON <> 0" . "\r\n";
        $strSQL .= "   OR  GRI.HANBAITESURYO <> 0" . "\r\n";
        $strSQL .= "   OR  GRI.SYOUKAIRYO <> 0" . "\r\n";
        $strSQL .= "   OR  GRI.CHUMONSYO_GENRI <> 0" . "\r\n";
        $strSQL .= "   OR  GRI.TOUGETU_GENRI <> 0)" . "\r\n";

        if ($intAuth == 0) {
            $strSQL .= "  AND GRI.ATUKAI_BUSYO IN (SELECT BUSYO_CD" . "\r\n";
            $strSQL .= "                           FROM   HAUTHORITY_CTL" . "\r\n";
            $strSQL .= "                           WHERE  HAUTH_ID = '@AUTHID'" . "\r\n";

            $strSQL .= "                           AND    SYS_KB = '@SYS_KB'" . "\r\n";

            $strSQL .= "                           AND    SYAIN_NO = '@USERID')" . "\r\n";
            $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);
        }
        if ($txtBusyoCDFrom != "") {
            $strSQL .= "  AND GRI.ATUKAI_BUSYO >= '@BUSYOF'" . "\r\n";
        }
        if ($txtBusyoCDTo != "") {
            $strSQL .= "  AND GRI.ATUKAI_BUSYO <= '@BUSYOT'" . "\r\n";
        }

        $strSQL .= "--" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT   '@TODAY' TODAY" . "\r\n";
        $strSQL .= ",        KANRISYA.SYAIN_NM KANRISYAMEI" . "\r\n";
        $strSQL .= ",        GRI.KUKURI_BUSYO||GRI.ATUKAI_BUSYO KUBUSYO" . "\r\n";
        $strSQL .= ",        GRI.DATA_KB" . "\r\n";
        $strSQL .= ",        GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= ",        BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",        '(' || GRI.ATUKAI_SYAIN || ')' ATUKAI_SYAIN" . "\r\n";
        $strSQL .= ",        SYA.SYAIN_NM SYAINMEI" . "\r\n";
        $strSQL .= ",        (CASE WHEN  NVL(GRI.CKO_CGY,' ') <> '2'" . "\r\n";
        $strSQL .= "               THEN (CASE WHEN GRI.ATUKAI_GYOSYA < '9000' OR GRI.GYOUSYA_NM = '' THEN GYO.GYOSYA_NM ELSE GRI.GYOUSYA_NM END) ELSE '' END) GYOUSYA_NM" . "\r\n";
        $strSQL .= ",        GRI.SYADAIKATA" . "\r\n";
        $strSQL .= ",        GRI.MEIGININ" . "\r\n";
        $strSQL .= "--,        '拠点処分'" . "\r\n";
        $strSQL .= ",        GRI.UC_NO" . "\r\n";
        $strSQL .= ",        GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= ",        NVL(GRI.ATUKAI_GYOSYA,' ') ATUKAI_GYOSYA" . "\r\n";
        $strSQL .= ",        (CASE WHEN NVL(GRI.KAIYAKU_YMD,' ') = ' ' THEN '' ELSE 'ｶ' END) KAIYAKU_KB" . "\r\n";
        $strSQL .= ",          NULL SIN_URIAGE " . "\r\n";
        $strSQL .= ",          NULL SIN_SYARYOU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL SIN_KASOU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL SIN_KAPPU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL SIN_TOUROKU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL SIN_UCHIKOMIKIN " . "\r\n";
        $strSQL .= ",          NULL SIN_URI_GENKA " . "\r\n";
        $strSQL .= ",          NULL SIN_SITADORI_SON" . "\r\n";
        $strSQL .= ",          NULL SIN_HANBAITESURYO" . "\r\n";
        $strSQL .= ",          NULL SIN_SYOUKAIRYO" . "\r\n";
        $strSQL .= ",          NULL SIN_CHUKOSYA_GENRI" . "\r\n";
        $strSQL .= ",          NULL SIN_TOUGETU_GENRI" . "\r\n";
        $strSQL .= ",          NULL SIN_DAISU" . "\r\n";
        $strSQL .= ",          NULL SIN_SIT_DAISU" . "\r\n";
        $strSQL .= ",          NULL CHU_URIAGE " . "\r\n";
        $strSQL .= ",          NULL CHU_SYARYOU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL CHU_KASOU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL CHU_KAPPU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL CHU_TOUROKU_RIEKI" . "\r\n";
        $strSQL .= ",          NULL CHU_UCHIKOMIKIN " . "\r\n";
        $strSQL .= ",          NULL CHU_URI_GENKA " . "\r\n";
        $strSQL .= ",          NULL CHU_SITADORI_SON" . "\r\n";
        $strSQL .= ",          NULL CHU_HANBAITESURYO" . "\r\n";
        $strSQL .= ",          NULL CHU_SYOUKAIRYO" . "\r\n";
        $strSQL .= ",          NULL CHU_CHUKOSYA_GENRI" . "\r\n";
        $strSQL .= ",          NULL CHU_TOUGETU_GENRI" . "\r\n";
        $strSQL .= ",          NULL CHU_DAISU" . "\r\n";
        $strSQL .= ",          NULL CHU_SIT_DAISU" . "\r\n";
        $strSQL .= ",          GRI.URIAGE" . "\r\n";
        $strSQL .= ",          GRI.SYARYOU_RIEKI" . "\r\n";
        $strSQL .= ",          GRI.KASOU_RIEKI" . "\r\n";
        $strSQL .= ",          0 KAPPU_RIEKI" . "\r\n";
        $strSQL .= ",          GRI.TOUROKU_RIEKI" . "\r\n";
        $strSQL .= ",          GRI.UCHIKOMIKIN" . "\r\n";
        $strSQL .= ",          0 URI_GENKA" . "\r\n";
        $strSQL .= ",          GRI.SITADORI_SON" . "\r\n";
        $strSQL .= ",          GRI.HANBAITESURYO" . "\r\n";
        $strSQL .= ",          GRI.SYOUKAIRYO" . "\r\n";
        $strSQL .= ",          GRI.CHUMONSYO_GENRI" . "\r\n";
        $strSQL .= ",          GRI.TOUGETU_GENRI" . "\r\n";
        $strSQL .= ",          GRI.DAISU TA_DAISU" . "\r\n";
        $strSQL .= ",          GRI.SIT_DAISU TA_SIT_DAISU" . "\r\n";
        $strSQL .= ",          SIN_TOUKI.SIN_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= ",          SIN_TOUKI.SIN_TOUKI_DAISU" . "\r\n";
        $strSQL .= ",          SIN_TOUKI_BUSYO.SIN_TOUKI_GENKAIRIEKI_BUSYO " . "\r\n";
        $strSQL .= ",          SIN_TOUKI_BUSYO.SIN_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= ",          CHU_TOUKI.CHU_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= ",          CHU_TOUKI.CHU_TOUKI_DAISU" . "\r\n";
        $strSQL .= ",          CHU_TOUKI_BUSYO.CHU_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= ",          CHU_TOUKI_BUSYO.CHU_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= ",          TA_TOUKI.TA_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= ",          TA_TOUKI.TA_TOUKI_DAISU" . "\r\n";
        $strSQL .= ",          TA_TOUKI_BUSYO.TA_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= ",          TA_TOUKI_BUSYO.TA_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_TOTAL.SIN_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= ",        SIN_TOUKI_TOTAL.SIN_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_TOTAL.CHU_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= ",        CHU_TOUKI_TOTAL.CHU_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= ",        TA_TOUKI_TOTAL.TA_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= ",        TA_TOUKI_TOTAL.TA_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= ",        GRI.CMN_NO CMN_NO" . "\r\n";
        $strSQL .= " FROM HGENRI_VW GRI" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_BUSYO = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_SYAIN = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST KANRISYA" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_BUSYO = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND       BUS.MANEGER_CD = KANRISYA.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HGYOSYAMST GYO" . "\r\n";
        $strSQL .= "ON        GRI.ATUKAI_GYOSYA = GYO.GYOSYA_CD" . "\r\n";
        $strSQL .= "----新車ｾｰﾙｽ当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.TOUGETU_GENRI) SIN_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) SIN_TOUKI_DAISU" . "\r\n";
        $strSQL .= "           FROM   HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "                 ,HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE  CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND    TOUKI_G.DATA_KB = '1'" . "\r\n";
        $strSQL .= "           AND    TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_SYAIN) SIN_TOUKI" . "\r\n";
        $strSQL .= "  ON      SIN_TOUKI.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "  AND     SIN_TOUKI.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "  AND     SIN_TOUKI.ATUKAI_SYAIN = GRI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "----新車部署当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.TOUGETU_GENRI) SIN_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) SIN_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= "           FROM   HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "                 ,HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE  CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND    TOUKI_G.DATA_KB = '1'" . "\r\n";
        $strSQL .= "           AND    TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO) SIN_TOUKI_BUSYO" . "\r\n";
        $strSQL .= "  ON      SIN_TOUKI_BUSYO.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "  AND     SIN_TOUKI_BUSYO.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "----中古ｾｰﾙｽ当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI) CHU_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) CHU_TOUKI_DAISU" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.DATA_KB = '2'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_SYAIN) CHU_TOUKI" . "\r\n";
        $strSQL .= "           ON      CHU_TOUKI.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND     CHU_TOUKI.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           AND     CHU_TOUKI.ATUKAI_SYAIN = GRI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "----中古部署当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI) CHU_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) CHU_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.DATA_KB = '2'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO) CHU_TOUKI_BUSYO" . "\r\n";
        $strSQL .= "           ON      CHU_TOUKI_BUSYO.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND     CHU_TOUKI_BUSYO.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "----他ｾｰﾙｽ当期計" . "\r\n";
        $strSQL .= "LEFT JOIN ( SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI) TA_TOUKI_GENKAIRIEKI" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.DAISU) TA_TOUKI_DAISU" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   TOUKI_G.DATA_KB = '3'" . "\r\n";
        $strSQL .= "           AND     CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_SYAIN) TA_TOUKI" . "\r\n";
        $strSQL .= "           ON   TA_TOUKI.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND  TA_TOUKI.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           AND  TA_TOUKI.ATUKAI_SYAIN = GRI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "----他部署当期計" . "\r\n";
        $strSQL .= "LEFT JOIN ( SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI) TA_TOUKI_GENKAIRIEKI_BUSYO" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.DAISU) TA_TOUKI_DAISU_BUSYO" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   TOUKI_G.DATA_KB = '3'" . "\r\n";
        $strSQL .= "           AND     CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      TOUKI_G.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           ,        TOUKI_G.ATUKAI_BUSYO) TA_TOUKI_BUSYO" . "\r\n";
        $strSQL .= "           ON   TA_TOUKI_BUSYO.KUKURI_BUSYO = GRI.KUKURI_BUSYO" . "\r\n";
        $strSQL .= "           AND  TA_TOUKI_BUSYO.ATUKAI_BUSYO = GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "----新車全社当期計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           , SUM(TOUGETU_GENRI) SIN_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= "           , SUM(TOUKI_G.DAISU)  SIN_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= "        FROM HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           , HKEIRICTL CTL" . "\r\n";
        $strSQL .= "       WHERE  CTL.ID = '01'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.DATA_KB = '1'" . "\r\n";
        $strSQL .= "         AND    TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "       GROUP BY TOUKI_G.DATA_KB) SIN_TOUKI_TOTAL" . "\r\n";
        $strSQL .= "         ON   1=1" . "\r\n";
        $strSQL .= "----中古全社計" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,      SUM(TOUKI_G.TOUGETU_GENRI)  CHU_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= "                 ,SUM(TOUKI_G.DAISU) CHU_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.DATA_KB = '2'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB) CHU_TOUKI_TOTAL" . "\r\n";
        $strSQL .= "         ON   1=1" . "\r\n";
        $strSQL .= "----他全社当期計" . "\r\n";
        $strSQL .= "LEFT JOIN ( SELECT TOUKI_G.DATA_KB" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.TOUGETU_GENRI)  TA_TOUKI_GENKAIRIEKI_TOTAL" . "\r\n";
        $strSQL .= "           ,SUM(TOUKI_G.DAISU) TA_TOUKI_DAISU_TOTAL" . "\r\n";
        $strSQL .= "           FROM    HGENRI_VW TOUKI_G" . "\r\n";
        $strSQL .= "           ,       HKEIRICTL CTL" . "\r\n";
        $strSQL .= "           WHERE   TOUKI_G.DATA_KB = '3'" . "\r\n";
        $strSQL .= "           AND     CTL.ID = '01'" . "\r\n";
        $strSQL .= "           AND     TOUKI_G.NENGETU BETWEEN SUBSTR(CTL.KISYU_YMD,1,6) AND '@NENGETU' " . "\r\n";
        $strSQL .= "           GROUP BY TOUKI_G.DATA_KB) TA_TOUKI_TOTAL" . "\r\n";
        $strSQL .= "         ON   1=1" . "\r\n";
        $strSQL .= "--" . "\r\n";
        $strSQL .= "WHERE GRI.NENGETU = '@NENGETU' " . "\r\n";
        $strSQL .= "  AND GRI.DATA_KB = '3'" . "\r\n";
        $strSQL .= "  AND  (GRI.URIAGE <> 0" . "\r\n";
        $strSQL .= "   OR   GRI.SYARYOU_RIEKI <> 0" . "\r\n";
        $strSQL .= "   OR   GRI.KASOU_RIEKI <> 0" . "\r\n";
        $strSQL .= "   OR   GRI.TOUROKU_RIEKI <> 0" . "\r\n";
        $strSQL .= "   OR   GRI.UCHIKOMIKIN <> 0" . "\r\n";
        $strSQL .= "   OR   GRI.SITADORI_SON <> 0" . "\r\n";
        $strSQL .= "   OR   GRI.HANBAITESURYO <> 0" . "\r\n";
        $strSQL .= "   OR   GRI.SYOUKAIRYO <> 0" . "\r\n";
        $strSQL .= "   OR   GRI.CHUMONSYO_GENRI <> 0" . "\r\n";
        $strSQL .= "   OR   GRI.TOUGETU_GENRI <> 0)" . "\r\n";

        if ($intAuth == 0) {
            $strSQL .= "  AND GRI.ATUKAI_BUSYO IN (SELECT BUSYO_CD" . "\r\n";
            $strSQL .= "                           FROM   HAUTHORITY_CTL" . "\r\n";
            $strSQL .= "                           WHERE  HAUTH_ID = '@AUTHID'" . "\r\n";

            $strSQL .= "                           AND    SYS_KB = '@SYS_KB'" . "\r\n";
            $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);

            $strSQL .= "                           AND    SYAIN_NO = '@USERID')" . "\r\n";
        }

        if ($txtBusyoCDFrom != "") {
            $strSQL .= "  AND GRI.ATUKAI_BUSYO >= '@BUSYOF'" . "\r\n";
        }
        if ($txtBusyoCDTo != "") {
            $strSQL .= "  AND GRI.ATUKAI_BUSYO <= '@BUSYOT'" . "\r\n";
        }

        $strSQL .= "--" . "\r\n";
        $strSQL .= "ORDER BY KUKURI_BUSYO" . "\r\n";
        $strSQL .= ",        ATUKAI_BUSYO" . "\r\n";
        $strSQL .= ",        ATUKAI_SYAIN" . "\r\n";
        $strSQL .= ",        DATA_KB" . "\r\n";
        $strSQL .= ",        ATUKAI_GYOSYA" . "\r\n";
        $strSQL .= ",        UC_NO" . "\r\n";

        $strSQL = str_replace("@TODAY", substr($cboYM, 0, 4) . substr($cboYM, 4, 2), $strSQL);
        $strSQL = str_replace("@NENGETU", $cboYM, $strSQL);

        $strSQL = str_replace("@AUTHID", $buttonNM, $strSQL);
        $strSQL = str_replace("@USERID", $UPDUSER, $strSQL);
        $strSQL = str_replace("@BUSYOF", $txtBusyoCDFrom, $strSQL);
        $strSQL = str_replace("@BUSYOT", $txtBusyoCDTo, $strSQL);
        //        $this->log($strSQL);
        return $strSQL;
    }

    public function fncGenriIchiran($intAuth, $cboYM, $txtBusyoCDFrom, $txtBusyoCDTo, $buttonNM)
    {
        return parent::select($this->fncGenriIchiransql($intAuth, $cboYM, $txtBusyoCDFrom, $txtBusyoCDTo, $buttonNM));
    }

}
