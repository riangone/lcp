<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------------
 * 日付            Feature/Bug                       内容                                         担当
 * YYYYMMDD        #ID                              XXXXXX                                       FCSDL
 * 20250508       仕様変更      部署181を441に変換している箇所がありますが、すべて変換しないようにする     lujunxia
 * --------------------------------------------------------------------------------------------------
 */
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;

//*************************************
// * 処理名	：FrmTentyoSyoreiCalc
// * 関数名	：FrmTentyoSyoreiCalc
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmTentyoSyoreiCalc extends ClsComDb
{
    public $ClsComFncJKSYS;
    //人事コントロールマスタの処理年月取得
    public function procGetJinjiCtrlMst_YM()
    {
        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    SYORI_YM" . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "    JKCONTROLMST " . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    ID = '01'" . "\r\n";

        return parent::select($strSQL);
    }

    //1.店長奨励金データの削除(JKTENCHOSYOREI)
    public function proDeleteJKGYOSEKISYOREI($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "DELETE FROM" . "\r\n";
        $strSQL .= "    JKTENCHOSYOREI" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    SIKYU_YM = @REP" . "\r\n";

        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);

        return parent::delete($strSQL);
    }

    //1.店長奨励金データの削除(JKTENCHOSYOREIKEISU)
    public function proDeleteJKGYOSEKISYOREIKEISU($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "DELETE FROM" . "\r\n";
        $strSQL .= "    JKTENCHOSYOREIKEISU" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    SIKYU_YM = @REP" . "\r\n";

        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);

        return parent::delete($strSQL);
    }

    //1.店長奨励金データの削除(JKTENCHOSYOREISYAIN)
    public function proDeleteJKTENCHOSYOREISYAIN($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "DELETE FROM" . "\r\n";
        $strSQL .= "    JKTENCHOSYOREISYAIN" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    SIKYU_YM = @REP" . "\r\n";

        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);

        return parent::delete($strSQL);
    }

    //2.データの存在チェック（ロジック）
    public function procCheckDataLogic($strTblNm, $dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    COUNT(*) AS CNT" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    @TBL" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    VALUE1 = @REP" . "\r\n";

        $strSQL = str_replace("@TBL", $strTblNm, $strSQL);
        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -1)), $strSQL);

        return parent::select($strSQL);
    }

    //3.店長奨励金データの登録(INSERT)
    public function procInsertTenchoSyoreiKinData($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKTENCHOSYOREI (" . "\r\n";
        $strSQL .= "SIKYU_YM," . "\r\n";
        $strSQL .= "BUSYO_CD," . "\r\n";
        $strSQL .= "GENKAI_RIEKI," . "\r\n";
        $strSQL .= "GENKAI_RIEKI_CALC," . "\r\n";
        $strSQL .= "JININ," . "\r\n";
        /** 後で更新 **/
        $strSQL .= "KEISU_TOTAL," . "\r\n";
        /** 後で更新 **/
        $strSQL .= "SANSYUTU_KINGAKU," . "\r\n";
        /** 後で更新 **/
        $strSQL .= "SYOREI_TEATE," . "\r\n";
        $strSQL .= "CREATE_DATE," . "\r\n";
        $strSQL .= "CRE_SYA_CD," . "\r\n";
        $strSQL .= "CRE_PRG_ID," . "\r\n";
        $strSQL .= "UPD_DATE," . "\r\n";
        $strSQL .= "UPD_SYA_CD," . "\r\n";
        $strSQL .= "UPD_PRG_ID," . "\r\n";
        $strSQL .= "UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    @REP0," . "\r\n";
        //20210302 CI INS S
        // $strSQL .= "    TBS.BUSYO_CD," . "\r\n";
        // $strSQL .= "    NVL(HSSK1.TOU_ZAN,0) AS GENKAI_RIEKI," . "\r\n";
        // $strSQL .= "    DECODE(NVL(EX0.VALUE4,0),0,0,ROUND(NVL(HSSK1.TOU_ZAN,0) * JKSMX.ATAI_1 / 100 / EX0.VALUE4)) AS GENKAI_RIEKI_CALC," . "\r\n";
        // $strSQL .= "    NVL(EX0.VALUE4,0) AS JININ," . "\r\n";
        // 20250508 lujunxia upd s
        // $strSQL .= "    CASE WHEN TBS.BUSYO_CD='181' THEN '441' ELSE TBS.BUSYO_CD END as BUSYO_CD," . "\r\n";
        $strSQL .= "    TBS.BUSYO_CD," . "\r\n";
        // 20250508 lujunxia upd e
        $strSQL .= "    SUM(NVL(HSSK1.TOU_ZAN,0)) AS GENKAI_RIEKI," . "\r\n";
        $strSQL .= "    DECODE(SUM(NVL(EX0.VALUE4,0)),0,0,ROUND(SUM(NVL(HSSK1.TOU_ZAN,0)) * MAX(JKSMX.ATAI_1) / 100 / SUM(EX0.VALUE4))) AS GENKAI_RIEKI_CALC," . "\r\n";
        $strSQL .= "    SUM(NVL(EX0.VALUE4,0)) AS JININ," . "\r\n";
        //20210302 CI INS E
        /** 後で更新 **/
        $strSQL .= "    1 AS KEISU_TOTAL," . "\r\n";
        /** 後で更新 **/
        $strSQL .= "    1 AS SANSYUTU_KINGAKU," . "\r\n";
        /** 後で更新 **/
        $strSQL .= "    1 AS SYOREI_TEATE," . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REPA," . "\r\n";
        $strSQL .= "    @REPB," . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REPA," . "\r\n";
        $strSQL .= "    @REPB," . "\r\n";
        $strSQL .= "    @REPC" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    (SELECT DISTINCT" . "\r\n";
        $strSQL .= "         SUBSTR(JKSM1.CODE, 1, 3) AS BUSYO_CD" . "\r\n";
        $strSQL .= "        ,JKSM2.ATAI_2 AS HANBAI_CD" . "\r\n";
        $strSQL .= "     FROM" . "\r\n";
        $strSQL .= "         JKSYAIN SM" . "\r\n";
        $strSQL .= "         INNER JOIN (SELECT" . "\r\n";
        $strSQL .= "                         SYAIN_NO," . "\r\n";
        $strSQL .= "                         MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQL .= "                     FROM" . "\r\n";
        $strSQL .= "                         JKIDOURIREKI" . "\r\n";
        $strSQL .= "                     WHERE" . "\r\n";
        $strSQL .= "                         TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQL .= "                     GROUP BY" . "\r\n";
        $strSQL .= "                         SYAIN_NO" . "\r\n";
        $strSQL .= "                    ) JKIM" . "\r\n";
        $strSQL .= "             ON SM.SYAIN_NO = JKIM.SYAIN_NO" . "\r\n";
        $strSQL .= "         INNER JOIN JKIDOURIREKI JKI" . "\r\n";
        $strSQL .= "             ON JKIM.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        $strSQL .= "            AND JKIM.ANNOUNCE_DT = JKI.ANNOUNCE_DT" . "\r\n";
        $strSQL .= "         INNER JOIN JKSYOREIKINMST JKSM0" . "\r\n";
        $strSQL .= "             ON JKSM0.SYUBETU_CD = '20000'" . "\r\n";
        $strSQL .= "         INNER JOIN JKSYOREIKINMST JKSM1" . "\r\n";
        $strSQL .= "             ON JKI.BUSYO_CD || JKI.SYOKUSYU_CD = JKSM1.CODE" . "\r\n";
        $strSQL .= "            AND JKSM1.SYUBETU_CD = '21000'" . "\r\n";
        $strSQL .= "         INNER JOIN JKSYOREIKINMST JKSM2" . "\r\n";
        $strSQL .= "             ON JKI.BUSYO_CD = JKSM2.CODE" . "\r\n";
        $strSQL .= "            AND JKSM2.SYUBETU_CD = '21100'" . "\r\n";
        $strSQL .= "         LEFT JOIN JKSONOTA JKS" . "\r\n";
        $strSQL .= "             ON SM.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        $strSQL .= "            AND JKS.KS_KB = '1'" . "\r\n";
        $strSQL .= "            AND JKS.TAISYOU_YM = @REP0" . "\r\n";
        $strSQL .= "     WHERE" . "\r\n";
        $strSQL .= "         SM.TAISYOKU_DT IS NULL" . "\r\n";
        $strSQL .= "     OR" . "\r\n";
        $strSQL .= "         (SM.TAISYOKU_DT IS NOT NULL AND" . "\r\n";
        $strSQL .= "         (JKS.SONOTA2 IS NOT NULL AND" . "\r\n";
        $strSQL .= "          JKS.SONOTA2 <= SM.TAISYOKU_DT AND" . "\r\n";
        $strSQL .= "          JKS.SONOTA3 >= SM.TAISYOKU_DT) OR " . "\r\n";
        $strSQL .= "          (@REPYMD <= SM.TAISYOKU_DT))) TBS" . "\r\n";
        $strSQL .= "    LEFT JOIN HSIMRUISEKIKANR_KRSS HSSK1" . "\r\n";
        $strSQL .= "        ON HSSK1.LINE_NO = 87" . "\r\n";
        $strSQL .= "       AND HSSK1.BUSYO_CD = TBS.HANBAI_CD " . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND HSSK1.KEIJO_DT = @REP1" . "\r\n";
        $strSQL .= "    LEFT JOIN EXJININ00000001 EX0" . "\r\n";
        $strSQL .= "        ON TBS.BUSYO_CD = EX0.VALUE2" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND EX0.VALUE1 = @REP1" . "\r\n";
        $strSQL .= "    INNER JOIN JKSYOREIKINMST JKSMX" . "\r\n";
        $strSQL .= "        ON JKSMX.SYUBETU_CD = '22000'" . "\r\n";
        //20210302 CI INS S
        $strSQL .= "  GROUP BY" . "\r\n";
        // 20250508 lujunxia upd s
        // $strSQL .= "  CASE WHEN TBS.BUSYO_CD='181' THEN '441' ELSE TBS.BUSYO_CD END " . "\r\n";
        $strSQL .= "  TBS.BUSYO_CD " . "\r\n";
        // 20250508 lujunxia upd e
        //20210302 CI INS E
        $tmpdate = $dtpYM . "01";
        $tmpdate = date("Y/m/d", strtotime($tmpdate));
        //画面支給年月日
        $strSQL = str_replace("@REPYMD", $this->ClsComFncJKSYS->FncSqlNv($tmpdate), $strSQL);
        //画面支給年月
        $strSQL = str_replace("@REP0", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        //画面支給年月-1カ月
        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -1)), $strSQL);
        $strSQL = str_replace("@REPA", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@REPB", $this->ClsComFncJKSYS->FncSqlNv("TentyoSyoreiCalc"), $strSQL);
        $strSQL = str_replace("@REPC", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);
        return parent::insert($strSQL);
    }

    //4.店長奨励金係数データの登録
    public function procInsertTenchoSyoreiKeisuData($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        //販売ｽﾀｯﾌの条件用
        $strSQLItem = "";
        $strSQLItem .= "  (SELECT JKI.SYAIN_NO " . "\r\n";
        $strSQLItem .= "                                FROM   JKIDOURIREKI JKI" . "\r\n";
        $strSQLItem .= "                                INNER JOIN (SELECT SYAIN_NO," . "\r\n";
        $strSQLItem .= "                                                   MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQLItem .= "                                            FROM  JKIDOURIREKI" . "\r\n";
        $strSQLItem .= "                                            WHERE TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQLItem .= "                                            GROUP BY SYAIN_NO ) JKIM" . "\r\n";
        $strSQLItem .= "                                   ON JKI.SYAIN_NO = JKIM.SYAIN_NO" . "\r\n";
        $strSQLItem .= "                                  AND JKI.ANNOUNCE_DT = JKIM.ANNOUNCE_DT" . "\r\n";
        $strSQLItem .= "                                INNER JOIN (SELECT CODE" . "\r\n";
        $strSQLItem .= "                                            FROM  JKSYOREIKINMST" . "\r\n";
        $strSQLItem .= "                                            WHERE SYUBETU_CD = '11000') JKSIM" . "\r\n";
        $strSQLItem .= "                                   ON JKI.SYOKUSYU_CD || JKI.BUSYO_CD = JKSIM.CODE" . "\r\n";
        $strSQLItem .= "                                INNER JOIN JKSYAIN JKSM" . "\r\n";
        $strSQLItem .= "                                   ON JKI.SYAIN_NO = JKSM.SYAIN_NO" . "\r\n";
        $strSQLItem .= "                                  AND JKSM.KOYOU_KB_CD IN ('01','3A'))" . "\r\n";

        $strSQL .= "INSERT INTO JKTENCHOSYOREIKEISU (" . "\r\n";
        $strSQL .= "SIKYU_YM," . "\r\n";
        $strSQL .= "BUSYO_CD," . "\r\n";
        $strSQL .= "KEISU_KOMOKU," . "\r\n";
        $strSQL .= "KEISU_MEISYO," . "\r\n";
        $strSQL .= "KEIJO_RIEKI_HON," . "\r\n";
        $strSQL .= "KEIJO_RIEKI_ZEN," . "\r\n";
        $strSQL .= "JISSEKI," . "\r\n";
        $strSQL .= "JISSEKI_1," . "\r\n";
        $strSQL .= "KEISU," . "\r\n";
        $strSQL .= "CREATE_DATE," . "\r\n";
        $strSQL .= "CRE_SYA_CD," . "\r\n";
        $strSQL .= "CRE_PRG_ID," . "\r\n";
        $strSQL .= "UPD_DATE," . "\r\n";
        $strSQL .= "UPD_SYA_CD," . "\r\n";
        $strSQL .= "UPD_PRG_ID," . "\r\n";
        $strSQL .= "UPD_CLT_NM" . "\r\n";
        $strSQL .= ") " . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    @REP0," . "\r\n";
        //20210302 CI UPD S
        //$strSQL .= "    TBS.BUSYO_CD," . "\r\n";
        $strSQL .= "    BUSYO_CD" . "\r\n";
        $strSQL .= ", KEISU_KOMOKU" . "\r\n";
        $strSQL .= ", KEISU_MEISYO" . "\r\n";
        $strSQL .= ",SUM(GENRI_1)" . "\r\n";
        $strSQL .= ",SUM(GENRI_13)" . "\r\n";
        $strSQL .= ",SUM(JISSEKI)" . "\r\n";
        $strSQL .= ", JISSEKI_1" . "\r\n";
        $strSQL .= ", KEISU" . "\r\n";
        $strSQL .= ", SYSDATE" . "\r\n";
        $strSQL .= ", @REPA" . "\r\n";
        $strSQL .= ", @REPB" . "\r\n";
        $strSQL .= ", SYSDATE" . "\r\n";
        $strSQL .= ", @REPA" . "\r\n";
        $strSQL .= ", @REPB" . "\r\n";
        $strSQL .= ", @REPC" . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= " (" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        // 20250508 lujunxia upd s
        // $strSQL .= "    CASE WHEN TBS.BUSYO_CD='181' THEN '441' ELSE TBS.BUSYO_CD END BUSYO_CD," . "\r\n";
        $strSQL .= "    TBS.BUSYO_CD," . "\r\n";
        // 20250508 lujunxia upd e
        //20210302 CI UPD E
        $strSQL .= "    TBS.KEISU_KOMOKU," . "\r\n";
        $strSQL .= "    TBS.KEISU_MEISYO," . "\r\n";
        //20210302 CI UPD S
        $strSQL .= "    CASE TBS.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "        WHEN '11' THEN SUM(HSSK1.TOU_ZAN) " . "\r\n";
        $strSQL .= "    ELSE NULL END GENRI_1, " . "\r\n";
        $strSQL .= "    CASE TBS.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "        WHEN '11' THEN SUM(HSSK13.TOU_ZAN) " . "\r\n";
        $strSQL .= "    ELSE NULL END GENRI_13, " . "\r\n";
        $strSQL .= "    CASE TBS.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "        WHEN '04' THEN TO_NUMBER(DECODE(JKSM4.CODE, NULL, NULL, SUM(NVL(EX4.VALUE6,0))))" . "\r\n";
        $strSQL .= "        WHEN '05' THEN TO_NUMBER(DECODE(JKSM5.CODE, NULL, NULL, SUM(NVL(EX5.VALUE11,0))))" . "\r\n";
        $strSQL .= "        WHEN '06' THEN TO_NUMBER(DECODE(JKSM6.CODE, NULL, NULL, SUM(NVL(EX6.VALUE8,0))))" . "\r\n";
        $strSQL .= "        WHEN '07' THEN TO_NUMBER(DECODE(JKSM7.CODE, NULL, NULL, SUM(NVL(EX7.VALUE8,0))))" . "\r\n";
        $strSQL .= "        WHEN '09' THEN TO_NUMBER(DECODE(JKSM9.CODE, NULL, NULL, SUM(NVL(EX9.VALUE6,0))))" . "\r\n";
        $strSQL .= "        WHEN '10' THEN TO_NUMBER(DECODE(JKSM10.CODE, NULL, NULL, SUM(NVL(EX10.VALUE6,0))))" . "\r\n";
        $strSQL .= "        WHEN '11' THEN SUM(ROUND((NVL(HSSK1.TOU_ZAN,0) - NVL(HSSK13.TOU_ZAN,0)) /1000 ))" . "\r\n";
        //20210302 CI UPD E
        $strSQL .= "    END AS JISSEKI," . "\r\n";
        /** 後で更新 **/
        $strSQL .= "    NULL AS JISSEKI_1," . "\r\n";
        /** 後で更新 **/
        $strSQL .= "    '1.0' AS KEISU" . "\r\n";
        //20210302 CI DEL S
        // $strSQL .= "    SYSDATE," . "\r\n";
        // $strSQL .= "    @REPA," . "\r\n";
        // $strSQL .= "    @REPB," . "\r\n";
        // $strSQL .= "    SYSDATE," . "\r\n";
        // $strSQL .= "    @REPA," . "\r\n";
        // $strSQL .= "    @REPB," . "\r\n";
        // $strSQL .= "    @REPC" . "\r\n";
        //20210302 CI DEL E
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    (SELECT DISTINCT" . "\r\n";
        $strSQL .= "         SUBSTR(JKSM1.CODE, 1, 3) AS BUSYO_CD" . "\r\n";
        $strSQL .= "        ,JKSM0.CODE AS KEISU_KOMOKU" . "\r\n";
        $strSQL .= "        ,JKSM0.MEISYO AS KEISU_MEISYO" . "\r\n";
        $strSQL .= "        ,JKSM0.ATAI_1 AS ATAI_1" . "\r\n";
        $strSQL .= "        ,JKSM1.ATAI_1 AS HANBAIROOT" . "\r\n";
        $strSQL .= "        ,JKSM2.ATAI_1 AS TENPO_CD" . "\r\n";
        $strSQL .= "     FROM" . "\r\n";
        $strSQL .= "         JKSYAIN SM" . "\r\n";
        $strSQL .= "         INNER JOIN (SELECT" . "\r\n";
        $strSQL .= "                         SYAIN_NO," . "\r\n";
        $strSQL .= "                         MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQL .= "                     FROM" . "\r\n";
        $strSQL .= "                         JKIDOURIREKI" . "\r\n";
        $strSQL .= "                     WHERE" . "\r\n";
        $strSQL .= "                         TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQL .= "                     GROUP BY" . "\r\n";
        $strSQL .= "                         SYAIN_NO" . "\r\n";
        $strSQL .= "                    ) JKIM" . "\r\n";
        $strSQL .= "             ON SM.SYAIN_NO = JKIM.SYAIN_NO" . "\r\n";
        $strSQL .= "         INNER JOIN JKIDOURIREKI JKI" . "\r\n";
        $strSQL .= "             ON JKIM.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        $strSQL .= "            AND JKIM.ANNOUNCE_DT = JKI.ANNOUNCE_DT" . "\r\n";
        $strSQL .= "         INNER JOIN JKSYOREIKINMST JKSM0" . "\r\n";
        $strSQL .= "             ON JKSM0.SYUBETU_CD = '20000'" . "\r\n";
        $strSQL .= "         INNER JOIN JKSYOREIKINMST JKSM1" . "\r\n";
        $strSQL .= "             ON JKI.BUSYO_CD || JKI.SYOKUSYU_CD = JKSM1.CODE" . "\r\n";
        $strSQL .= "            AND JKSM1.SYUBETU_CD = '21000'" . "\r\n";
        $strSQL .= "         INNER JOIN JKSYOREIKINMST JKSM2" . "\r\n";
        $strSQL .= "             ON JKI.BUSYO_CD  = JKSM2.CODE" . "\r\n";
        $strSQL .= "            AND JKSM2.SYUBETU_CD = '21100'" . "\r\n";
        $strSQL .= "         LEFT JOIN JKSONOTA JKS" . "\r\n";
        $strSQL .= "             ON SM.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        $strSQL .= "            AND JKS.KS_KB = '1'" . "\r\n";
        //画面.支給年月
        $strSQL .= "            AND JKS.TAISYOU_YM = @REP0" . "\r\n";
        $strSQL .= "     WHERE" . "\r\n";
        $strSQL .= "         SM.TAISYOKU_DT IS NULL" . "\r\n";
        $strSQL .= "     OR" . "\r\n";
        $strSQL .= "         (SM.TAISYOKU_DT IS NOT NULL AND" . "\r\n";
        $strSQL .= "         (JKS.SONOTA2 IS NOT NULL AND" . "\r\n";
        $strSQL .= "          JKS.SONOTA2 <= SM.TAISYOKU_DT AND" . "\r\n";
        $strSQL .= "          JKS.SONOTA3 >= SM.TAISYOKU_DT) OR " . "\r\n";
        $strSQL .= "          (@REPYMD <= SM.TAISYOKU_DT))) TBS" . "\r\n";
        $strSQL .= "    LEFT JOIN HSIMRUISEKIKANR_KRSS HSSK1" . "\r\n";
        $strSQL .= "        ON HSSK1.LINE_NO = 114" . "\r\n";
        $strSQL .= "       AND HSSK1.BUSYO_CD = TBS.TENPO_CD" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND HSSK1.KEIJO_DT = @REP1" . "\r\n";
        $strSQL .= "    LEFT JOIN HSIMRUISEKIKANR_KRSS HSSK13" . "\r\n";
        $strSQL .= "        ON HSSK13.LINE_NO = 114" . "\r\n";
        $strSQL .= "       AND HSSK13.BUSYO_CD = TBS.TENPO_CD " . "\r\n";
        //画面.支給年月-13カ月
        $strSQL .= "       AND HSSK13.KEIJO_DT = @REP13" . "\r\n";
        //04 任意保険
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM4" . "\r\n";
        $strSQL .= "        ON TBS.HANBAIROOT = JKSM4.CODE" . "\r\n";
        $strSQL .= "       AND JKSM4.SYUBETU_CD = '22004'" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT" . "\r\n";
        $strSQL .= "                   SUBSTR(VALUE2,1,2) BUSYO_CD," . "\r\n";
        $strSQL .= "                   SUM(VALUE6) AS VALUE6" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   EXNINIHOKEN0001" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                   VALUE1 = @REP1" . "\r\n";
        //社員番号　VALUE4
        $strSQL .= "               AND VALUE4 IN @REPSEL " . "\r\n";
        $strSQL .= "               GROUP BY" . "\r\n";
        $strSQL .= "                   SUBSTR(VALUE2,1,2)" . "\r\n";
        $strSQL .= "              ) EX4" . "\r\n";
        $strSQL .= "        ON SUBSTR(TBS.BUSYO_CD,1,2) = EX4.BUSYO_CD" . "\r\n";
        //05 パックdeメンテ
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM5" . "\r\n";
        $strSQL .= "        ON TBS.HANBAIROOT = JKSM5.CODE" . "\r\n";
        $strSQL .= "       AND JKSM5.SYUBETU_CD = '22005'" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT" . "\r\n";
        $strSQL .= "                   SUBSTR(VALUE2,1,2) BUSYO_CD," . "\r\n";
        $strSQL .= "                   SUM(VALUE11) AS VALUE11" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   EXPACKDEMENTE01" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                   VALUE1 = @REP1" . "\r\n";
        //社員番号　VALUE4
        $strSQL .= "               AND VALUE4 IN @REPSEL " . "\r\n";
        $strSQL .= "               GROUP BY" . "\r\n";
        $strSQL .= "                   SUBSTR(VALUE2,1,2)" . "\r\n";
        $strSQL .= "              ) EX5" . "\r\n";
        $strSQL .= "        ON SUBSTR(TBS.BUSYO_CD,1,2) = EX5.BUSYO_CD" . "\r\n";
        //06 サービス貢献１年点検
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM6" . "\r\n";
        $strSQL .= "        ON TBS.HANBAIROOT = JKSM6.CODE" . "\r\n";
        $strSQL .= "       AND JKSM6.SYUBETU_CD = '22006'" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT" . "\r\n";
        $strSQL .= "                   SUBSTR(IDO.BUSYO_CD,1,2) BUSYO_CD," . "\r\n";
        $strSQL .= "                   SUM(SVC.VALUE8) AS VALUE8" . "\r\n";
        $strSQL .= "               FROM " . "\r\n";
        $strSQL .= "                   JKSYAIN JKSM," . "\r\n";
        $strSQL .= "                  (SELECT CODE" . "\r\n";
        $strSQL .= "                   FROM   JKSYOREIKINMST" . "\r\n";
        $strSQL .= "                   WHERE  SYUBETU_CD = '11000') SRM," . "\r\n";
        $strSQL .= "                   JKIDOURIREKI IDO," . "\r\n";
        $strSQL .= "                   EXSVCKOUKEN0001 SVC" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        $strSQL .= "                   JKSM.KOYOU_KB_CD IN ('01','3A')" . "\r\n";
        $strSQL .= "               AND IDO.SYAIN_NO = JKSM.SYAIN_NO" . "\r\n";
        $strSQL .= "               AND IDO.SYOKUSYU_CD || IDO.BUSYO_CD = SRM.CODE" . "\r\n";
        $strSQL .= "               AND (IDO.SYAIN_NO,IDO.ANNOUNCE_DT) IN (SELECT SYAIN_NO," . "\r\n";
        $strSQL .= "                                                             MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQL .= "                                                      FROM JKIDOURIREKI" . "\r\n";
        $strSQL .= "                                                      WHERE TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQL .= "                                                      GROUP BY SYAIN_NO )" . "\r\n";
        $strSQL .= "               AND SVC.VALUE2 = IDO.SYAIN_NO" . "\r\n";
        $strSQL .= "               AND SVC.VALUE6 IN ('13', '14')" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "               AND SVC.VALUE1 = @REP1" . "\r\n";
        $strSQL .= "               GROUP BY" . "\r\n";
        $strSQL .= "                   SUBSTR(IDO.BUSYO_CD,1,2)" . "\r\n";
        $strSQL .= "              ) EX6" . "\r\n";
        $strSQL .= "        ON SUBSTR(TBS.BUSYO_CD,1,2) = EX6.BUSYO_CD" . "\r\n";
        //07 サービス貢献車検
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM7" . "\r\n";
        $strSQL .= "        ON TBS.HANBAIROOT = JKSM7.CODE" . "\r\n";
        $strSQL .= "       AND JKSM7.SYUBETU_CD = '22007'" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT" . "\r\n";
        $strSQL .= "                   SUBSTR(IDO.BUSYO_CD,1,2) BUSYO_CD," . "\r\n";
        $strSQL .= "                   SUM(SVC.VALUE8) AS VALUE8" . "\r\n";
        $strSQL .= "               FROM " . "\r\n";
        $strSQL .= "                   JKSYAIN JKSM," . "\r\n";
        $strSQL .= "                  (SELECT CODE" . "\r\n";
        $strSQL .= "                   FROM   JKSYOREIKINMST" . "\r\n";
        $strSQL .= "                   WHERE  SYUBETU_CD = '11000') SRM," . "\r\n";
        $strSQL .= "                   JKIDOURIREKI IDO," . "\r\n";
        $strSQL .= "                   EXSVCKOUKEN0001 SVC" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        $strSQL .= "                   JKSM.KOYOU_KB_CD IN ('01','3A')" . "\r\n";
        $strSQL .= "               AND IDO.SYAIN_NO = JKSM.SYAIN_NO" . "\r\n";
        $strSQL .= "               AND IDO.SYOKUSYU_CD || IDO.BUSYO_CD = SRM.CODE" . "\r\n";
        $strSQL .= "               AND (IDO.SYAIN_NO,IDO.ANNOUNCE_DT) IN (SELECT SYAIN_NO," . "\r\n";
        $strSQL .= "                                                             MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQL .= "                                                      FROM JKIDOURIREKI" . "\r\n";
        $strSQL .= "                                                      WHERE TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQL .= "                                                      GROUP BY SYAIN_NO )" . "\r\n";
        $strSQL .= "               AND SVC.VALUE2 = IDO.SYAIN_NO" . "\r\n";
        //→HIT出力CSV加工時に'0'が欠落しても取得できるように'1'を条件にいれる
        $strSQL .= "               AND SVC.VALUE6 IN ('01','1')" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "               AND SVC.VALUE1 = @REP1" . "\r\n";
        $strSQL .= "               GROUP BY" . "\r\n";
        $strSQL .= "                   SUBSTR(IDO.BUSYO_CD,1,2)" . "\r\n";
        $strSQL .= "              ) EX7" . "\r\n";
        $strSQL .= "        ON SUBSTR(TBS.BUSYO_CD,1,2) = EX7.BUSYO_CD" . "\r\n";
        //09 ＪＡＦ
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM9" . "\r\n";
        $strSQL .= "        ON TBS.HANBAIROOT = JKSM9.CODE" . "\r\n";
        $strSQL .= "       AND JKSM9.SYUBETU_CD = '22009'" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT" . "\r\n";
        $strSQL .= "                   SUBSTR(VALUE2,1,2) BUSYO_CD," . "\r\n";
        $strSQL .= "                   SUM(VALUE6) AS VALUE6" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   EXJAF0000000001" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                   VALUE1 = @REP1" . "\r\n";
        //社員番号　VALUE4
        $strSQL .= "               AND VALUE4 IN @REPSEL " . "\r\n";
        $strSQL .= "               GROUP BY" . "\r\n";
        $strSQL .= "                   SUBSTR(VALUE2,1,2)" . "\r\n";
        $strSQL .= "              ) EX9" . "\r\n";
        $strSQL .= "        ON SUBSTR(TBS.BUSYO_CD,1,2) = EX9.BUSYO_CD" . "\r\n";
        //10 （TMRH）リース
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM10" . "\r\n";
        $strSQL .= "        ON TBS.HANBAIROOT = JKSM10.CODE" . "\r\n";
        $strSQL .= "       AND JKSM10.SYUBETU_CD = '22010'" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT" . "\r\n";
        $strSQL .= "                   SUBSTR(VALUE2,1,2) BUSYO_CD," . "\r\n";
        $strSQL .= "                   SUM(VALUE6) AS VALUE6" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   EXHMLEASE000001" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                   VALUE1 = @REP1" . "\r\n";
        //社員番号　VALUE4
        $strSQL .= "               AND VALUE4 IN @REPSEL " . "\r\n";
        $strSQL .= "               GROUP BY" . "\r\n";
        $strSQL .= "                   SUBSTR(VALUE2,1,2)" . "\r\n";
        $strSQL .= "              ) EX10" . "\r\n";
        $strSQL .= "        ON SUBSTR(TBS.BUSYO_CD,1,2) = EX10.BUSYO_CD" . "\r\n";
        //20210302 CI INS S
        $strSQL .= " GROUP BY " . "\r\n";
        // 20250508 lujunxia upd s
        // $strSQL .= "    CASE WHEN TBS.BUSYO_CD='181' THEN '441' ELSE TBS.BUSYO_CD END ," . "\r\n";
        $strSQL .= "    TBS.BUSYO_CD ," . "\r\n";
        // 20250508 lujunxia upd e
        $strSQL .= "    TBS.KEISU_KOMOKU," . "\r\n";
        $strSQL .= "    TBS.KEISU_MEISYO," . "\r\n";
        $strSQL .= "    JKSM4.CODE," . "\r\n";
        $strSQL .= "    JKSM5.CODE," . "\r\n";
        $strSQL .= "    JKSM6.CODE," . "\r\n";
        $strSQL .= "    JKSM7.CODE," . "\r\n";
        $strSQL .= "    JKSM9.CODE," . "\r\n";
        $strSQL .= "    JKSM10.CODE" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= " GROUP BY " . "\r\n";
        $strSQL .= "  BUSYO_CD" . "\r\n";
        $strSQL .= ", KEISU_KOMOKU" . "\r\n";
        $strSQL .= ", KEISU_MEISYO" . "\r\n";
        $strSQL .= ", JISSEKI_1" . "\r\n";
        $strSQL .= ", KEISU" . "\r\n";
        //20210302 CI INS E
        $tmpdate = $dtpYM . "01";
        $tmpdate = date("Y/m/d", strtotime($tmpdate));
        $strSQL = str_replace("@REPSEL", $strSQLItem, $strSQL);
        //画面支給年月日
        $strSQL = str_replace("@REPYMD", $this->ClsComFncJKSYS->FncSqlNv($tmpdate), $strSQL);
        //画面支給年月
        $strSQL = str_replace("@REP0", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        //画面支給年月-13カ月
        $strSQL = str_replace("@REP13", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -13)), $strSQL);
        //画面支給年月-1カ月
        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -1)), $strSQL);
        $strSQL = str_replace("@REPA", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@REPB", $this->ClsComFncJKSYS->FncSqlNv("TentyoSyoreiCalc"), $strSQL);
        $strSQL = str_replace("@REPC", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);

        return parent::insert($strSQL);
    }

    //5.店長奨励金実績1人当データの更新(係数)
    public function procUpdateTenchoSyoreiJisseki_1Data($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //ｻｰﾋﾞｽ貢献度　点検
        $strSQLItem1 = "                          (SELECT JKS.JININ - (NVL(STF.JININ,0) - NVL(GET.JININ,0))" . "\r\n";
        $strSQLItem1 .= "                           FROM   JKTENCHOSYOREI JKS" . "\r\n";
        $strSQLItem1 .= "                                 ,(SELECT COUNT(JKSM.SYAIN_NO) JININ" . "\r\n";
        //20210302 CI UPD S
        // 20250508 lujunxia upd s
        $strSQLItem1 .= "                                         ,SUBSTR(IDO.BUSYO_CD,1,2) BUSYO_CD" . "\r\n";
        // $strSQLItem1 .= "                                         ,SUBSTR(CASE WHEN IDO.BUSYO_CD ='181' THEN '441' ELSE IDO.BUSYO_CD END,1,2) BUSYO_CD" . "\r\n";
        // 20250508 lujunxia upd e
        $strSQLItem1 .= "                                   FROM   JKSYAIN JKSM" . "\r\n";
        $strSQLItem1 .= "                                        ,(SELECT CODE" . "\r\n";
        $strSQLItem1 .= "                                          FROM JKSYOREIKINMST" . "\r\n";
        $strSQLItem1 .= "                                          WHERE  SYUBETU_CD = '11000' ) SRM" . "\r\n";
        $strSQLItem1 .= "                                        ,JKIDOURIREKI IDO" . "\r\n";
        $strSQLItem1 .= "                                   WHERE JKSM.KOYOU_KB_CD IN ('01','3A')" . "\r\n";
        $strSQLItem1 .= "                                     AND IDO.SYAIN_NO = JKSM.SYAIN_NO" . "\r\n";
        $strSQLItem1 .= "                                     AND ROUND(MONTHS_BETWEEN(@REP2, JKSM.NYUSYA_DT)/12) < 2" . "\r\n";
        $strSQLItem1 .= "                                     AND IDO.SYOKUSYU_CD || IDO.BUSYO_CD = SRM.CODE" . "\r\n";
        $strSQLItem1 .= "                                     AND (IDO.SYAIN_NO,IDO.ANNOUNCE_DT) IN (SELECT SYAIN_NO" . "\r\n";
        $strSQLItem1 .= "                                                                                  ,MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQLItem1 .= "                                                                            FROM JKIDOURIREKI" . "\r\n";
        $strSQLItem1 .= "                                                                            WHERE TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQLItem1 .= "                                                                            GROUP BY SYAIN_NO )" . "\r\n";
        // 20250508 lujunxia upd s
        $strSQLItem1 .= "                                   GROUP BY SUBSTR(IDO.BUSYO_CD,1,2) ) STF" . "\r\n";
        // $strSQLItem1 .= "                                  GROUP BY SUBSTR(CASE WHEN IDO.BUSYO_CD ='181' THEN '441' ELSE IDO.BUSYO_CD END,1,2) ) STF" . "\r\n";
        // 20250508 lujunxia upd e
        $strSQLItem1 .= "                                 ,(SELECT COUNT(SVC.VALUE2) JININ" . "\r\n";
        // 20250508 lujunxia upd s
        $strSQLItem1 .= "                                         ,SUBSTR(IDO.BUSYO_CD,1,2) BUSYO_CD" . "\r\n";
        // $strSQLItem1 .= "                                         ,SUBSTR(CASE WHEN IDO.BUSYO_CD ='181' THEN '441' ELSE IDO.BUSYO_CD END,1,2) BUSYO_CD" . "\r\n";
        // 20250508 lujunxia upd e
        $strSQLItem1 .= "                                   FROM   JKSYAIN JKSM" . "\r\n";
        $strSQLItem1 .= "                                        ,(SELECT VALUE2" . "\r\n";
        $strSQLItem1 .= "                                          FROM EXSVCKOUKEN0001" . "\r\n";
        $strSQLItem1 .= "                                          WHERE  VALUE6 IN ('13','14')" . "\r\n";
        $strSQLItem1 .= "                                            AND  VALUE1 = @REP1" . "\r\n";
        $strSQLItem1 .= "                                          GROUP BY VALUE2 ) SVC" . "\r\n";
        $strSQLItem1 .= "                                        ,(SELECT CODE" . "\r\n";
        $strSQLItem1 .= "                                          FROM JKSYOREIKINMST" . "\r\n";
        $strSQLItem1 .= "                                          WHERE  SYUBETU_CD = '11000' ) SRM" . "\r\n";
        $strSQLItem1 .= "                                        ,JKIDOURIREKI IDO" . "\r\n";
        $strSQLItem1 .= "                                   WHERE JKSM.KOYOU_KB_CD IN ('01','3A')" . "\r\n";
        $strSQLItem1 .= "                                     AND IDO.SYAIN_NO = JKSM.SYAIN_NO" . "\r\n";
        $strSQLItem1 .= "                                     AND IDO.SYAIN_NO = SVC.VALUE2" . "\r\n";
        $strSQLItem1 .= "                                     AND ROUND(MONTHS_BETWEEN(@REP2, JKSM.NYUSYA_DT)/12) < 2" . "\r\n";
        $strSQLItem1 .= "                                     AND IDO.SYOKUSYU_CD || IDO.BUSYO_CD = SRM.CODE" . "\r\n";
        $strSQLItem1 .= "                                     AND (IDO.SYAIN_NO,IDO.ANNOUNCE_DT) IN (SELECT SYAIN_NO" . "\r\n";
        $strSQLItem1 .= "                                                                                  ,MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQLItem1 .= "                                                                            FROM JKIDOURIREKI" . "\r\n";
        $strSQLItem1 .= "                                                                            WHERE TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQLItem1 .= "                                                                            GROUP BY SYAIN_NO )" . "\r\n";
        // 20250508 lujunxia upd s
        $strSQLItem1 .= "                                   GROUP BY SUBSTR(IDO.BUSYO_CD,1,2) ) GET" . "\r\n";
        // $strSQLItem1 .= "                                  GROUP BY SUBSTR(CASE WHEN IDO.BUSYO_CD ='181' THEN '441' ELSE IDO.BUSYO_CD END,1,2) ) GET " . "\r\n";
        // 20250508 lujunxia upd e
        $strSQLItem1 .= "                           WHERE  SUBSTR(JKS.BUSYO_CD,1,2) = STF.BUSYO_CD(+)" . "\r\n";
        $strSQLItem1 .= "                             AND  SUBSTR(JKS.BUSYO_CD,1,2) = GET.BUSYO_CD(+)" . "\r\n";
        $strSQLItem1 .= "                             AND  JKS.SIKYU_YM = JKG.SIKYU_YM" . "\r\n";
        $strSQLItem1 .= "                             AND  JKS.BUSYO_CD = JKG.BUSYO_CD)" . "\r\n";

        //ｻｰﾋﾞｽ貢献度　車検
        $strSQLItem3 = "                          (SELECT JKS.JININ - (NVL(STF.JININ,0) - NVL(GET.JININ,0))" . "\r\n";
        $strSQLItem3 .= "                           FROM   JKTENCHOSYOREI JKS" . "\r\n";
        $strSQLItem3 .= "                                 ,(SELECT COUNT(JKSM.SYAIN_NO) JININ" . "\r\n";
        // 20250508 lujunxia upd s
        $strSQLItem3 .= "                                         ,SUBSTR(IDO.BUSYO_CD,1,2) BUSYO_CD" . "\r\n";
        // $strSQLItem3 .= "                                         ,SUBSTR(CASE WHEN IDO.BUSYO_CD ='181' THEN '441' ELSE IDO.BUSYO_CD END,1,2) BUSYO_CD" . "\r\n";
        // 20250508 lujunxia upd e
        $strSQLItem3 .= "                                   FROM   JKSYAIN JKSM" . "\r\n";
        $strSQLItem3 .= "                                        ,(SELECT CODE" . "\r\n";
        $strSQLItem3 .= "                                          FROM JKSYOREIKINMST" . "\r\n";
        $strSQLItem3 .= "                                          WHERE  SYUBETU_CD = '11000' ) SRM" . "\r\n";
        $strSQLItem3 .= "                                        ,JKIDOURIREKI IDO" . "\r\n";
        $strSQLItem3 .= "                                   WHERE JKSM.KOYOU_KB_CD IN ('01','3A')" . "\r\n";
        $strSQLItem3 .= "                                     AND IDO.SYAIN_NO = JKSM.SYAIN_NO" . "\r\n";
        $strSQLItem3 .= "                                     AND ROUND(MONTHS_BETWEEN(@REP2, JKSM.NYUSYA_DT)/12) < 4" . "\r\n";
        $strSQLItem3 .= "                                     AND IDO.SYOKUSYU_CD || IDO.BUSYO_CD = SRM.CODE" . "\r\n";
        $strSQLItem3 .= "                                     AND (IDO.SYAIN_NO,IDO.ANNOUNCE_DT) IN (SELECT SYAIN_NO" . "\r\n";
        $strSQLItem3 .= "                                                                                  ,MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQLItem3 .= "                                                                            FROM JKIDOURIREKI" . "\r\n";
        $strSQLItem3 .= "                                                                            WHERE TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQLItem3 .= "                                                                            GROUP BY SYAIN_NO )" . "\r\n";
        // 20250508 lujunxia upd s
        $strSQLItem3 .= "                                   GROUP BY SUBSTR(IDO.BUSYO_CD,1,2) ) STF" . "\r\n";
        // $strSQLItem3 .= "                                   GROUP BY SUBSTR(CASE WHEN IDO.BUSYO_CD ='181' THEN '441' ELSE IDO.BUSYO_CD END,1,2) ) STF " . "\r\n";
        // 20250508 lujunxia upd e
        $strSQLItem3 .= "                                 ,(SELECT COUNT(SVC.VALUE2) JININ" . "\r\n";
        // 20250508 lujunxia upd s
        $strSQLItem3 .= "                                         ,SUBSTR(IDO.BUSYO_CD,1,2) BUSYO_CD" . "\r\n";
        // $strSQLItem3 .= "                                         ,SUBSTR(CASE WHEN IDO.BUSYO_CD ='181' THEN '441' ELSE IDO.BUSYO_CD END,1,2) BUSYO_CD" . "\r\n";
        // 20250508 lujunxia upd e
        $strSQLItem3 .= "                                   FROM   JKSYAIN JKSM" . "\r\n";
        $strSQLItem3 .= "                                        ,(SELECT VALUE2" . "\r\n";
        $strSQLItem3 .= "                                          FROM EXSVCKOUKEN0001" . "\r\n";
        $strSQLItem3 .= "                                          WHERE  VALUE6 IN ('01','1')" . "\r\n";
        $strSQLItem3 .= "                                            AND  VALUE1 = @REP1" . "\r\n";
        $strSQLItem3 .= "                                          GROUP BY VALUE2 ) SVC" . "\r\n";
        $strSQLItem3 .= "                                        ,(SELECT CODE" . "\r\n";
        $strSQLItem3 .= "                                          FROM JKSYOREIKINMST" . "\r\n";
        $strSQLItem3 .= "                                          WHERE  SYUBETU_CD = '11000' ) SRM" . "\r\n";
        $strSQLItem3 .= "                                        ,JKIDOURIREKI IDO" . "\r\n";
        $strSQLItem3 .= "                                   WHERE JKSM.KOYOU_KB_CD IN ('01','3A')" . "\r\n";
        $strSQLItem3 .= "                                     AND IDO.SYAIN_NO = JKSM.SYAIN_NO" . "\r\n";
        $strSQLItem3 .= "                                     AND IDO.SYAIN_NO = SVC.VALUE2" . "\r\n";
        $strSQLItem3 .= "                                     AND ROUND(MONTHS_BETWEEN(@REP2, JKSM.NYUSYA_DT)/12) < 4" . "\r\n";
        $strSQLItem3 .= "                                     AND IDO.SYOKUSYU_CD || IDO.BUSYO_CD = SRM.CODE" . "\r\n";
        $strSQLItem3 .= "                                     AND (IDO.SYAIN_NO,IDO.ANNOUNCE_DT) IN (SELECT SYAIN_NO" . "\r\n";
        $strSQLItem3 .= "                                                                                  ,MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQLItem3 .= "                                                                            FROM JKIDOURIREKI" . "\r\n";
        $strSQLItem3 .= "                                                                            WHERE TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQLItem3 .= "                                                                            GROUP BY SYAIN_NO )" . "\r\n";
        // 20250508 lujunxia upd s
        $strSQLItem3 .= "                                   GROUP BY SUBSTR(IDO.BUSYO_CD,1,2) ) GET" . "\r\n";
        // $strSQLItem3 .= "                                  GROUP BY SUBSTR(CASE WHEN IDO.BUSYO_CD ='181' THEN '441' ELSE IDO.BUSYO_CD END,1,2) ) GET " . "\r\n";
        // 20250508 lujunxia upd e
        $strSQLItem3 .= "                           WHERE  SUBSTR(JKS.BUSYO_CD,1,2) = STF.BUSYO_CD(+)" . "\r\n";
        $strSQLItem3 .= "                             AND  SUBSTR(JKS.BUSYO_CD,1,2) = GET.BUSYO_CD(+) " . "\r\n";
        $strSQLItem3 .= "                             AND  JKS.SIKYU_YM = JKG.SIKYU_YM" . "\r\n";
        $strSQLItem3 .= "                             AND  JKS.BUSYO_CD = JKG.BUSYO_CD)" . "\r\n";
        //20210302 CI UPD E
        $strSQL = "";
        $strSQL .= "UPDATE JKTENCHOSYOREIKEISU JKG SET" . "\r\n";
        $strSQL .= " JKG.JISSEKI_1 =" . "\r\n";
        $strSQL .= "     CASE JKG.KEISU_KOMOKU" . "\r\n";
        //◆◆◆　ｻｰﾋﾞｽ貢献度　点検　◆◆◆
        $strSQL .= "         WHEN '06' THEN" . "\r\n";
        $strSQL .= "             DECODE((SELECT JKSM.ATAI_1" . "\r\n";
        $strSQL .= "                     FROM  JKSYOREIKINMST JKSM" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                     WHERE JKSM.SYUBETU_CD = '20000'" . "\r\n";
        $strSQL .= "                       AND JKSM.CODE = JKG.KEISU_KOMOKU ),'1'," . "\r\n";
        $strSQL .= "                           DECODE(@REPSEL1,0,0, " . "\r\n";
        $strSQL .= "                           JISSEKI / @REPSEL1)" . "\r\n";
        $strSQL .= "                        , NULL)" . "\r\n";
        //◆◆◆　ｻｰﾋﾞｽ貢献度　車検　◆◆◆
        $strSQL .= "         WHEN '07' THEN" . "\r\n";
        $strSQL .= "             DECODE((SELECT JKSM.ATAI_1" . "\r\n";
        $strSQL .= "                     FROM  JKSYOREIKINMST JKSM" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                     WHERE JKSM.SYUBETU_CD = '20000'" . "\r\n";
        $strSQL .= "                       AND JKSM.CODE = JKG.KEISU_KOMOKU ),'1'," . "\r\n";
        $strSQL .= "                           DECODE(@REPSEL3,0,0, " . "\r\n";
        $strSQL .= "                           JISSEKI / @REPSEL3)" . "\r\n";
        $strSQL .= "                        , NULL)" . "\r\n";
        //◆◆◆　以外　◆◆◆
        $strSQL .= "     ELSE DECODE((SELECT" . "\r\n";
        $strSQL .= "                             JKSM.ATAI_1" . "\r\n";
        $strSQL .= "                         FROM" . "\r\n";
        $strSQL .= "                             JKSYOREIKINMST JKSM" . "\r\n";
        $strSQL .= "                         WHERE" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                             JKSM.SYUBETU_CD = '20000'" . "\r\n";
        $strSQL .= "                         AND" . "\r\n";
        $strSQL .= "                             JKSM.CODE = JKG.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "                        ), '1'" . "\r\n";
        $strSQL .= "                        , JISSEKI / " . "\r\n";
        $strSQL .= "                        (SELECT   JKS.JININ" . "\r\n";
        $strSQL .= "                         FROM     JKTENCHOSYOREI JKS" . "\r\n";
        $strSQL .= "                         WHERE    JKS.SIKYU_YM = JKG.SIKYU_YM" . "\r\n";
        $strSQL .= "                         AND      JKS.BUSYO_CD = JKG.BUSYO_CD)" . "\r\n";
        $strSQL .= "                        , NULL)" . "\r\n";
        $strSQL .= "     END" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    SIKYU_YM = @REP0" . "\r\n";

        $strSQL = str_replace("@REPSEL1", $strSQLItem1, $strSQL);
        $strSQL = str_replace("@REPSEL3", $strSQLItem3, $strSQL);
        //画面支給年月
        $strSQL = str_replace("@REP0", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        //画面支給年月-1か月
        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -1)), $strSQL);
        //画面支給年月-1か月の末日
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($this->getlastday($dtpYM)), $strSQL);
        return parent::update($strSQL);
    }

    //6.店長奨励金係数データの更新(係数)
    public function procUpdateTenchoSyoreiKeisuData($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "UPDATE JKTENCHOSYOREIKEISU JKG SET" . "\r\n";
        $strSQL .= " JKG.KEISU = NVL(DECODE((SELECT" . "\r\n";
        $strSQL .= "                         JKSM.ATAI_1" . "\r\n";
        $strSQL .= "                     FROM" . "\r\n";
        $strSQL .= "                         JKSYOREIKINMST JKSM" . "\r\n";
        $strSQL .= "                     WHERE" . "\r\n";
        $strSQL .= "                         JKSM.SYUBETU_CD = '20000'" . "\r\n";
        $strSQL .= "                     AND" . "\r\n";
        $strSQL .= "                         JKSM.CODE = JKG.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "                    ), '1'" . "\r\n";
        $strSQL .= "                    ,(SELECT" . "\r\n";
        $strSQL .= "                          KEISU" . "\r\n";
        $strSQL .= "                      FROM" . "\r\n";
        $strSQL .= "                          JKKEISUMST" . "\r\n";
        $strSQL .= "                      WHERE" . "\r\n";
        $strSQL .= "                          RANGE_FROM <= JKG.JISSEKI_1" . "\r\n";
        $strSQL .= "                      AND" . "\r\n";
        $strSQL .= "                          RANGE_TO >= JKG.JISSEKI_1" . "\r\n";
        $strSQL .= "                      AND" . "\r\n";
        $strSQL .= "                          KEISU_SYURUI = JKG.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "                      AND" . "\r\n";
        $strSQL .= "                          SYOREIKIN_KB = '2')" . "\r\n";
        $strSQL .= "                    ,(SELECT" . "\r\n";
        $strSQL .= "                          KEISU" . "\r\n";
        $strSQL .= "                      FROM" . "\r\n";
        $strSQL .= "                          JKKEISUMST" . "\r\n";
        $strSQL .= "                      WHERE" . "\r\n";
        $strSQL .= "                          RANGE_FROM <= JISSEKI" . "\r\n";
        $strSQL .= "                      AND" . "\r\n";
        $strSQL .= "                          RANGE_TO >= JISSEKI" . "\r\n";
        $strSQL .= "                      AND" . "\r\n";
        $strSQL .= "                          KEISU_SYURUI = JKG.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "                      AND" . "\r\n";
        $strSQL .= "                          SYOREIKIN_KB = '2')), 1)" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    SIKYU_YM = @REP0" . "\r\n";

        //画面支給年月
        $strSQL = str_replace("@REP0", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);

        return parent::update($strSQL);
    }

    //店長奨励金データの更新(係数)
    public function procUpdateTenchoSyoreiKinData($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "UPDATE JKTENCHOSYOREI JKG SET" . "\r\n";
        $strSQL .= " JKG.KEISU_TOTAL = ROUND(" . "\r\n";
        $strSQL .= "              (SELECT" . "\r\n";
        $strSQL .= "                   A.KEISU_SUM" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   (SELECT" . "\r\n";
        $strSQL .= "                        BUSYO_CD," . "\r\n";
        $strSQL .= "                        RNUM," . "\r\n";
        $strSQL .= "                        KEISU," . "\r\n";
        $strSQL .= "                        KEISU_SUM" . "\r\n";
        $strSQL .= "                    FROM     (SELECT" . "\r\n";
        $strSQL .= "                                  BUSYO_CD," . "\r\n";
        $strSQL .= "                                  ROW_NUMBER() OVER(PARTITION BY BUSYO_CD ORDER BY BUSYO_CD, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                  KEISU" . "\r\n";
        $strSQL .= "                              FROM" . "\r\n";
        $strSQL .= "                                  JKTENCHOSYOREIKEISU" . "\r\n";
        $strSQL .= "                              WHERE" . "\r\n";
        $strSQL .= "                                  SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                             )" . "\r\n";
        $strSQL .= "                    MODEL" . "\r\n";
        $strSQL .= "                        PARTITION BY (BUSYO_CD)" . "\r\n";
        $strSQL .= "                        DIMENSION BY (RNUM)" . "\r\n";
        $strSQL .= "                        MEASURES (KEISU, KEISU KEISU_SUM)" . "\r\n";
        $strSQL .= "                        RULES(" . "\r\n";
        $strSQL .= "                            KEISU_SUM[RNUM IS ANY] ORDER BY RNUM" . "\r\n";
        $strSQL .= "                                = KEISU_SUM[CV(RNUM)] *  NVL(KEISU_SUM[CV(RNUM)-1], 1))" . "\r\n";
        $strSQL .= "                    ORDER BY" . "\r\n";
        $strSQL .= "                        BUSYO_CD" . "\r\n";
        $strSQL .= "                   ) A" . "\r\n";
        $strSQL .= "                   INNER JOIN (SELECT   BUSYO_CD," . "\r\n";
        $strSQL .= "                                        MAX(RNUM) AS RNUM" . "\r\n";
        $strSQL .= "                               FROM     (SELECT" . "\r\n";
        $strSQL .= "                                             BUSYO_CD," . "\r\n";
        $strSQL .= "                                             ROW_NUMBER() OVER(PARTITION BY BUSYO_CD ORDER BY BUSYO_CD, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                             KEISU" . "\r\n";
        $strSQL .= "                                         FROM" . "\r\n";
        $strSQL .= "                                             JKTENCHOSYOREIKEISU" . "\r\n";
        $strSQL .= "                                         WHERE" . "\r\n";
        $strSQL .= "                                             SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                                        )" . "\r\n";
        $strSQL .= "                               GROUP BY BUSYO_CD" . "\r\n";
        $strSQL .= "                              ) B" . "\r\n";
        $strSQL .= "                       ON A.BUSYO_CD = B.BUSYO_CD" . "\r\n";
        $strSQL .= "                      AND A.RNUM = B.RNUM" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        $strSQL .= "                   A.BUSYO_CD = JKG.BUSYO_CD" . "\r\n";
        $strSQL .= "              ), 5)" . "\r\n";
        $strSQL .= ",JKG.SANSYUTU_KINGAKU =  " . "\r\n";
        $strSQL .= "     CASE WHEN GENKAI_RIEKI_CALC < 0 THEN 0 " . "\r\n";
        $strSQL .= "         ELSE ROUND(GENKAI_RIEKI_CALC * ROUND(" . "\r\n";
        $strSQL .= "              (SELECT" . "\r\n";
        $strSQL .= "                   A.KEISU_SUM" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   (SELECT" . "\r\n";
        $strSQL .= "                        BUSYO_CD," . "\r\n";
        $strSQL .= "                        RNUM," . "\r\n";
        $strSQL .= "                        KEISU," . "\r\n";
        $strSQL .= "                        KEISU_SUM" . "\r\n";
        $strSQL .= "                    FROM     (SELECT" . "\r\n";
        $strSQL .= "                                  BUSYO_CD," . "\r\n";
        $strSQL .= "                                  ROW_NUMBER() OVER(PARTITION BY BUSYO_CD ORDER BY BUSYO_CD, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                  KEISU" . "\r\n";
        $strSQL .= "                              FROM" . "\r\n";
        $strSQL .= "                                  JKTENCHOSYOREIKEISU" . "\r\n";
        $strSQL .= "                              WHERE" . "\r\n";
        $strSQL .= "                                  SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                             )" . "\r\n";
        $strSQL .= "                    MODEL" . "\r\n";
        $strSQL .= "                        PARTITION BY (BUSYO_CD)" . "\r\n";
        $strSQL .= "                        DIMENSION BY (RNUM)" . "\r\n";
        $strSQL .= "                        MEASURES (KEISU, KEISU KEISU_SUM)" . "\r\n";
        $strSQL .= "                        RULES(" . "\r\n";
        $strSQL .= "                            KEISU_SUM[RNUM IS ANY] ORDER BY RNUM" . "\r\n";
        $strSQL .= "                                = KEISU_SUM[CV(RNUM)] *  NVL(KEISU_SUM[CV(RNUM)-1], 1))" . "\r\n";
        $strSQL .= "                    ORDER BY" . "\r\n";
        $strSQL .= "                        BUSYO_CD" . "\r\n";
        $strSQL .= "                   ) A" . "\r\n";
        $strSQL .= "                   INNER JOIN (SELECT   BUSYO_CD," . "\r\n";
        $strSQL .= "                                        MAX(RNUM) AS RNUM" . "\r\n";
        $strSQL .= "                               FROM     (SELECT" . "\r\n";
        $strSQL .= "                                             BUSYO_CD," . "\r\n";
        $strSQL .= "                                             ROW_NUMBER() OVER(PARTITION BY BUSYO_CD ORDER BY BUSYO_CD, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                             KEISU" . "\r\n";
        $strSQL .= "                                         FROM" . "\r\n";
        $strSQL .= "                                             JKTENCHOSYOREIKEISU" . "\r\n";
        $strSQL .= "                                         WHERE" . "\r\n";
        $strSQL .= "                                             SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                                        )" . "\r\n";
        $strSQL .= "                               GROUP BY BUSYO_CD" . "\r\n";
        $strSQL .= "                              ) B" . "\r\n";
        $strSQL .= "                       ON A.BUSYO_CD = B.BUSYO_CD" . "\r\n";
        $strSQL .= "                      AND A.RNUM = B.RNUM" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        $strSQL .= "                   A.BUSYO_CD = JKG.BUSYO_CD" . "\r\n";
        $strSQL .= "              ), 5))" . "\r\n";
        $strSQL .= "    END" . "\r\n";
        $strSQL .= ",JKG.SYOREI_TEATE = " . "\r\n";
        $strSQL .= "     CASE WHEN GENKAI_RIEKI_CALC < 0 THEN 0 " . "\r\n";
        $strSQL .= "       ELSE" . "\r\n";
        $strSQL .= "         CASE WHEN" . "\r\n";
        $strSQL .= "             ROUND(GENKAI_RIEKI_CALC * ROUND(" . "\r\n";
        $strSQL .= "              (SELECT" . "\r\n";
        $strSQL .= "                   A.KEISU_SUM" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   (SELECT" . "\r\n";
        $strSQL .= "                        BUSYO_CD," . "\r\n";
        $strSQL .= "                        RNUM," . "\r\n";
        $strSQL .= "                        KEISU," . "\r\n";
        $strSQL .= "                        KEISU_SUM" . "\r\n";
        $strSQL .= "                    FROM     (SELECT" . "\r\n";
        $strSQL .= "                                  BUSYO_CD," . "\r\n";
        $strSQL .= "                                  ROW_NUMBER() OVER(PARTITION BY BUSYO_CD ORDER BY BUSYO_CD, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                  KEISU" . "\r\n";
        $strSQL .= "                              FROM" . "\r\n";
        $strSQL .= "                                  JKTENCHOSYOREIKEISU" . "\r\n";
        $strSQL .= "                              WHERE" . "\r\n";
        $strSQL .= "                                  SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                             )" . "\r\n";
        $strSQL .= "                    MODEL" . "\r\n";
        $strSQL .= "                        PARTITION BY (BUSYO_CD)" . "\r\n";
        $strSQL .= "                        DIMENSION BY (RNUM)" . "\r\n";
        $strSQL .= "                        MEASURES (KEISU, KEISU KEISU_SUM)" . "\r\n";
        $strSQL .= "                        RULES(" . "\r\n";
        $strSQL .= "                            KEISU_SUM[RNUM IS ANY] ORDER BY RNUM" . "\r\n";
        $strSQL .= "                                = KEISU_SUM[CV(RNUM)] *  NVL(KEISU_SUM[CV(RNUM)-1], 1))" . "\r\n";
        $strSQL .= "                    ORDER BY" . "\r\n";
        $strSQL .= "                        BUSYO_CD" . "\r\n";
        $strSQL .= "                   ) A" . "\r\n";
        $strSQL .= "                   INNER JOIN (SELECT   BUSYO_CD," . "\r\n";
        $strSQL .= "                                        MAX(RNUM) AS RNUM" . "\r\n";
        $strSQL .= "                               FROM     (SELECT" . "\r\n";
        $strSQL .= "                                             BUSYO_CD," . "\r\n";
        $strSQL .= "                                             ROW_NUMBER() OVER(PARTITION BY BUSYO_CD ORDER BY BUSYO_CD, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                             KEISU" . "\r\n";
        $strSQL .= "                                         FROM" . "\r\n";
        $strSQL .= "                                             JKTENCHOSYOREIKEISU" . "\r\n";
        $strSQL .= "                                         WHERE" . "\r\n";
        $strSQL .= "                                             SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                                        )" . "\r\n";
        $strSQL .= "                               GROUP BY BUSYO_CD" . "\r\n";
        $strSQL .= "                              ) B" . "\r\n";
        $strSQL .= "                       ON A.BUSYO_CD = B.BUSYO_CD" . "\r\n";
        $strSQL .= "                      AND A.RNUM = B.RNUM" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        $strSQL .= "                   A.BUSYO_CD = JKG.BUSYO_CD" . "\r\n";
        $strSQL .= "              ), 5)) < " . "\r\n";
        $strSQL .= "              (SELECT   ATAI_1" . "\r\n";
        $strSQL .= "               FROM     JKSYOREIKINMST" . "\r\n";
        $strSQL .= "               WHERE    SYUBETU_CD = 'JOGEN'" . "\r\n";
        $strSQL .= "               AND      CODE = '2')" . "\r\n";
        $strSQL .= "          THEN" . "\r\n";
        $strSQL .= "          ROUND(GENKAI_RIEKI_CALC * ROUND(" . "\r\n";
        $strSQL .= "              (SELECT" . "\r\n";
        $strSQL .= "                   A.KEISU_SUM" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   (SELECT" . "\r\n";
        $strSQL .= "                        BUSYO_CD," . "\r\n";
        $strSQL .= "                        RNUM," . "\r\n";
        $strSQL .= "                        KEISU," . "\r\n";
        $strSQL .= "                        KEISU_SUM" . "\r\n";
        $strSQL .= "                    FROM     (SELECT" . "\r\n";
        $strSQL .= "                                  BUSYO_CD," . "\r\n";
        $strSQL .= "                                  ROW_NUMBER() OVER(PARTITION BY BUSYO_CD ORDER BY BUSYO_CD, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                  KEISU" . "\r\n";
        $strSQL .= "                              FROM" . "\r\n";
        $strSQL .= "                                  JKTENCHOSYOREIKEISU" . "\r\n";
        $strSQL .= "                              WHERE" . "\r\n";
        $strSQL .= "                                  SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                             )" . "\r\n";
        $strSQL .= "                    MODEL" . "\r\n";
        $strSQL .= "                        PARTITION BY (BUSYO_CD)" . "\r\n";
        $strSQL .= "                        DIMENSION BY (RNUM)" . "\r\n";
        $strSQL .= "                        MEASURES (KEISU, KEISU KEISU_SUM)" . "\r\n";
        $strSQL .= "                        RULES(" . "\r\n";
        $strSQL .= "                            KEISU_SUM[RNUM IS ANY] ORDER BY RNUM" . "\r\n";
        $strSQL .= "                                = KEISU_SUM[CV(RNUM)] *  NVL(KEISU_SUM[CV(RNUM)-1], 1))" . "\r\n";
        $strSQL .= "                    ORDER BY" . "\r\n";
        $strSQL .= "                        BUSYO_CD" . "\r\n";
        $strSQL .= "                   ) A" . "\r\n";
        $strSQL .= "                   INNER JOIN (SELECT   BUSYO_CD," . "\r\n";
        $strSQL .= "                                        MAX(RNUM) AS RNUM" . "\r\n";
        $strSQL .= "                               FROM     (SELECT" . "\r\n";
        $strSQL .= "                                             BUSYO_CD," . "\r\n";
        $strSQL .= "                                             ROW_NUMBER() OVER(PARTITION BY BUSYO_CD ORDER BY BUSYO_CD, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                             KEISU" . "\r\n";
        $strSQL .= "                                         FROM" . "\r\n";
        $strSQL .= "                                             JKTENCHOSYOREIKEISU" . "\r\n";
        $strSQL .= "                                         WHERE" . "\r\n";
        $strSQL .= "                                             SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                                        )" . "\r\n";
        $strSQL .= "                               GROUP BY BUSYO_CD" . "\r\n";
        $strSQL .= "                              ) B" . "\r\n";
        $strSQL .= "                       ON A.BUSYO_CD = B.BUSYO_CD" . "\r\n";
        $strSQL .= "                      AND A.RNUM = B.RNUM" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        $strSQL .= "                   A.BUSYO_CD = JKG.BUSYO_CD" . "\r\n";
        $strSQL .= "              ), 5))" . "\r\n";
        $strSQL .= "          ELSE" . "\r\n";
        $strSQL .= "              (SELECT   TO_NUMBER(ATAI_1)" . "\r\n";
        $strSQL .= "               FROM     JKSYOREIKINMST" . "\r\n";
        $strSQL .= "               WHERE    SYUBETU_CD = 'JOGEN'" . "\r\n";
        $strSQL .= "               AND      CODE = '2')" . "\r\n";
        $strSQL .= "      END" . "\r\n";
        $strSQL .= "  END" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    SIKYU_YM = @REP0" . "\r\n";

        //画面支給年月
        $strSQL = str_replace("@REP0", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        return parent::update($strSQL);
    }

    //8.店長奨励手当社員別支給データの登録
    public function procInsertTenchoSyoreiKinSyainData($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQLItem = " FROM" . "\r\n";
        $strSQLItem .= "         JKSYAIN SM" . "\r\n";
        $strSQLItem .= "         INNER JOIN (SELECT SYAIN_NO" . "\r\n";
        $strSQLItem .= "                          , MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQLItem .= "                     FROM   JKIDOURIREKI" . "\r\n";
        $strSQLItem .= "                     WHERE  TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQLItem .= "                     GROUP BY SYAIN_NO" . "\r\n";
        $strSQLItem .= "                     ) JKIM" . "\r\n";
        $strSQLItem .= "              ON SM.SYAIN_NO = JKIM.SYAIN_NO" . "\r\n";
        $strSQLItem .= "         INNER JOIN JKIDOURIREKI JKI" . "\r\n";
        $strSQLItem .= "              ON JKIM.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        $strSQLItem .= "             AND JKIM.ANNOUNCE_DT = JKI.ANNOUNCE_DT" . "\r\n";
        $strSQLItem .= "         INNER JOIN JKSYOREIKINMST JKSM0" . "\r\n";
        $strSQLItem .= "              ON JKSM0.SYUBETU_CD = '20000'" . "\r\n";
        $strSQLItem .= "         INNER JOIN JKSYOREIKINMST JKSM1" . "\r\n";
        $strSQLItem .= "              ON JKI.BUSYO_CD || JKI.SYOKUSYU_CD = JKSM1.CODE" . "\r\n";
        $strSQLItem .= "             AND JKSM1.SYUBETU_CD = '21000'" . "\r\n";
        $strSQLItem .= "         LEFT JOIN JKSONOTA JKS" . "\r\n";
        $strSQLItem .= "              ON SM.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        $strSQLItem .= "             AND JKS.KS_KB = '1'" . "\r\n";
        $strSQLItem .= "             AND JKS.TAISYOU_YM = @REP0" . "\r\n";
        $strSQLItem .= "     WHERE SM.TAISYOKU_DT IS NULL" . "\r\n";
        $strSQLItem .= "       AND SM.ZAISEKI_KB_CD <> '2'" . "\r\n";
        $strSQLItem .= "       OR (JKS.SONOTA2 IS NOT NULL" . "\r\n";
        $strSQLItem .= "       AND JKS.SONOTA2 <= SM.TAISYOKU_DT" . "\r\n";
        $strSQLItem .= "       AND JKS.SONOTA3 >= SM.TAISYOKU_DT)" . "\r\n";
        $strSQLItem .= "       OR (@REPYMD <= SM.TAISYOKU_DT)" . "\r\n";

        $strSQL = "";
        $strSQL .= "INSERT INTO JKTENCHOSYOREISYAIN (" . "\r\n";
        $strSQL .= "SIKYU_YM," . "\r\n";
        $strSQL .= "BUSYO_CD," . "\r\n";
        $strSQL .= "SYAIN_NO," . "\r\n";
        $strSQL .= "SYAIN_NM," . "\r\n";
        $strSQL .= "SYOKUSYU_CD," . "\r\n";
        $strSQL .= "SANSYUTU_KINGAKU," . "\r\n";
        $strSQL .= "SYOREI_TEATE," . "\r\n";
        $strSQL .= "CREATE_DATE," . "\r\n";
        $strSQL .= "CRE_SYA_CD," . "\r\n";
        $strSQL .= "CRE_PRG_ID," . "\r\n";
        $strSQL .= "UPD_DATE," . "\r\n";
        $strSQL .= "UPD_SYA_CD," . "\r\n";
        $strSQL .= "UPD_PRG_ID," . "\r\n";
        $strSQL .= "UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    @REP0," . "\r\n";
        $strSQL .= "    TBS.BUSYO_CD," . "\r\n";
        $strSQL .= "    TBS.SYAIN_NO," . "\r\n";
        $strSQL .= "    TBS.SYAIN_NM," . "\r\n";
        $strSQL .= "    TBS.SYOKUSYU_CD," . "\r\n";
        $strSQL .= "    ROUND(JKT.SANSYUTU_KINGAKU / JIN.KENSU) AS SANSYUTU_KINGAKU," . "\r\n";
        $strSQL .= "    CASE WHEN JIN.KENSU = 1 THEN JKT.SYOREI_TEATE" . "\r\n";
        $strSQL .= "         ELSE " . "\r\n";
        $strSQL .= "             CASE WHEN ROUND(JKT.SANSYUTU_KINGAKU / JIN.KENSU) > (SELECT ATAI_1 " . "\r\n";
        $strSQL .= "                                                                  FROM   JKSYOREIKINMST" . "\r\n";
        $strSQL .= "                                                                  WHERE  SYUBETU_CD = 'JOGEN'" . "\r\n";
        $strSQL .= "                                                                    AND  CODE = '2')" . "\r\n";
        $strSQL .= "                  THEN (SELECT TO_NUMBER(ATAI_1) " . "\r\n";
        $strSQL .= "                        FROM   JKSYOREIKINMST" . "\r\n";
        $strSQL .= "                        WHERE  SYUBETU_CD = 'JOGEN'" . "\r\n";
        $strSQL .= "                          AND  CODE = '2')" . "\r\n";
        $strSQL .= "                  ELSE ROUND(JKT.SANSYUTU_KINGAKU / JIN.KENSU) " . "\r\n";
        $strSQL .= "             END " . "\r\n";
        $strSQL .= "    END AS SYOREI_TEATE, " . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REPA," . "\r\n";
        $strSQL .= "    @REPB," . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REPA," . "\r\n";
        $strSQL .= "    @REPB," . "\r\n";
        $strSQL .= "    @REPC" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    (SELECT DISTINCT" . "\r\n";
        $strSQL .= "         SUBSTR(JKSM1.CODE, 1, 3) AS BUSYO_CD," . "\r\n";
        $strSQL .= "         SM.SYAIN_NO," . "\r\n";
        $strSQL .= "         SM.SYAIN_NM," . "\r\n";
        $strSQL .= "         JKI.SYOKUSYU_CD" . "\r\n";
        $strSQL .= "    @REPSEL ) TBS" . "\r\n";
        $strSQL .= "    INNER JOIN JKTENCHOSYOREI JKT" . "\r\n";
        $strSQL .= "        ON TBS.BUSYO_CD = JKT.BUSYO_CD" . "\r\n";
        //画面.支給年月
        $strSQL .= "       AND JKT.SIKYU_YM = @REP0" . "\r\n";
        //部署単位の支給対象者人数
        $strSQL .= "    INNER JOIN (SELECT SUBSTR(JKSM1.CODE, 1, 3) AS BUSYO_CD" . "\r\n";
        $strSQL .= "                     , COUNT(DISTINCT SM.SYAIN_NO) KENSU" . "\r\n";
        $strSQL .= "    @REPSEL " . "\r\n";
        $strSQL .= "       GROUP BY SUBSTR(JKSM1.CODE, 1, 3) ) JIN" . "\r\n";
        $strSQL .= "       ON TBS.BUSYO_CD = JIN.BUSYO_CD" . "\r\n";

        $strSQL = str_replace("@REPSEL", $strSQLItem, $strSQL);
        $tmpdate = $dtpYM . "01";
        $tmpdate = date("Y/m/d", strtotime($tmpdate));
        //画面支給年月日
        $strSQL = str_replace("@REPYMD", $this->ClsComFncJKSYS->FncSqlNv($tmpdate), $strSQL);
        //画面支給年月
        $strSQL = str_replace("@REP0", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        //画面支給年月-1か月
        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -1)), $strSQL);
        $strSQL = str_replace("@REPA", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER["strUserID"]), $strSQL);
        $strSQL = str_replace("@REPB", $this->ClsComFncJKSYS->FncSqlNv("TentyoSyoreiCalc"), $strSQL);
        $strSQL = str_replace("@REPC", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER["strClientNM"]), $strSQL);

        return parent::insert($strSQL);
    }

    //年月-1か月
    public function getPreMonth($dtpYM, $num)
    {
        $dtpYM = $dtpYM . "01";
        $rtnDate = date('Ym', strtotime("$dtpYM $num month"));

        return $rtnDate;
    }

    //年月-1か月の末日
    public function getlastday($dtpYM)
    {
        $dtpYM = $dtpYM . "01";
        $lastday = date('Y/m/d', strtotime("$dtpYM -1 day"));

        return $lastday;
    }

}
