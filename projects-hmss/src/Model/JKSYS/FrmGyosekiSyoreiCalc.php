<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;

//*************************************
// * 処理名	：FrmGyosekiSyoreiCalc
// * 関数名	：FrmGyosekiSyoreiCalc
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmGyosekiSyoreiCalc extends ClsComDb
{
    public $ClsComFncJKSYS;
    //人事コントロールマスタの処理年月取得
    public function procGetJinjiCtrlMst_YM()
    {
        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    SYORI_YM" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    JKCONTROLMST" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    ID = '01'" . "\r\n";

        return parent::select($strSQL);
    }

    //業績奨励金データの削除_JKGYOSEKISYOREI
    public function proDeleteJKGYOSEKISYOREI($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "DELETE" . "\r\n";
        $strSQL .= "    JKGYOSEKISYOREI" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    SIKYU_YM = @REP" . "\r\n";

        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);

        return parent::delete($strSQL);
    }

    //業績奨励金データの削除_JKGYOSEKISYOREIKEISU
    public function proDeleteJKGYOSEKISYOREIKEISU($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "DELETE" . "\r\n";
        $strSQL .= "    JKGYOSEKISYOREIKEISU" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    SIKYU_YM = @REP" . "\r\n";

        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);

        return parent::delete($strSQL);
    }

    //3.業績奨励金係数データの登録
    public function procInsertGyosekiSyoreiKeisuData($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKGYOSEKISYOREIKEISU (" . "\r\n";
        $strSQL .= "SIKYU_YM," . "\r\n";
        $strSQL .= "SYAIN_NO," . "\r\n";
        $strSQL .= "KEISU_KOMOKU," . "\r\n";
        $strSQL .= "KEISU_MEISYO," . "\r\n";
        $strSQL .= "JISSEKI," . "\r\n";
        $strSQL .= "KEISU," . "\r\n";
        $strSQL .= "JISSEKI_OFF," . "\r\n";
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
        $strSQL .= "    SM.SYAIN_NO," . "\r\n";
        $strSQL .= "    JKSM0.CODE," . "\r\n";
        $strSQL .= "    JKSM0.MEISYO," . "\r\n";
        $strSQL .= "    CASE JKSM0.CODE" . "\r\n";
        $strSQL .= "        WHEN '01' THEN TO_NUMBER(JKSM1.ATAI_1)" . "\r\n";
        $strSQL .= "        WHEN '02' THEN TO_NUMBER(DECODE(JKSM2.CODE, NULL, NULL, NVL(EX1.VALUE16,0)))" . "\r\n";
        $strSQL .= "        WHEN '03' THEN TO_NUMBER(DECODE(JKSM3.CODE, NULL, NULL, DECODE(JKSM1.ATAI_1, '4', NVL(EX2.VALUE8,0), '5', NVL(EX2.VALUE8,0), NVL(EX2.VALUE12,0))))" . "\r\n";
        $strSQL .= "        WHEN '04' THEN TO_NUMBER(DECODE(JKSM4.CODE, NULL, NULL, NVL(EX3.VALUE6,0)))" . "\r\n";
        $strSQL .= "        WHEN '05' THEN TO_NUMBER(DECODE(JKSM5.CODE, NULL, NULL, NVL(EX4.VALUE11,0)))" . "\r\n";
        $strSQL .= "        WHEN '06' THEN TO_NUMBER(DECODE(JKSM6.CODE, NULL, NULL, NVL(EX5.VALUE8,0)))" . "\r\n";
        $strSQL .= "        WHEN '07' THEN TO_NUMBER(DECODE(JKSM7.CODE, NULL, NULL, NVL(EX6.VALUE8,0)))" . "\r\n";
        $strSQL .= "        WHEN '09' THEN TO_NUMBER(DECODE(JKSM9.CODE, NULL, NULL, NVL(EX7.VALUE6,0)))" . "\r\n";
        $strSQL .= "        WHEN '10' THEN TO_NUMBER(DECODE(JKSM10.CODE, NULL, NULL, NVL(EX8.VALUE6,0)))" . "\r\n";
        $strSQL .= "    END AS JISSEKI," . "\r\n";
        $strSQL .= "    1.0 AS KEISU," . "\r\n";
        $strSQL .= "    CASE JKSM0.CODE" . "\r\n";
        $strSQL .= "        WHEN '01' THEN NULL" . "\r\n";
        $strSQL .= "        WHEN '02' THEN TO_NUMBER(DECODE(JKSM2.CODE, NULL, EX1.VALUE16, NULL))" . "\r\n";
        $strSQL .= "        WHEN '03' THEN TO_NUMBER(DECODE(JKSM3.CODE, NULL, DECODE(JKSM1.ATAI_1, '4', EX2.VALUE8, '5', EX2.VALUE8, EX2.VALUE12,NULL)))" . "\r\n";
        $strSQL .= "        WHEN '04' THEN TO_NUMBER(DECODE(JKSM4.CODE, NULL, EX3.VALUE6, NULL))" . "\r\n";
        $strSQL .= "        WHEN '05' THEN TO_NUMBER(DECODE(JKSM5.CODE, NULL, EX4.VALUE11, NULL))" . "\r\n";
        $strSQL .= "        WHEN '06' THEN TO_NUMBER(DECODE(JKSM6.CODE, NULL, EX5.VALUE8, NULL))" . "\r\n";
        $strSQL .= "        WHEN '07' THEN TO_NUMBER(DECODE(JKSM7.CODE, NULL, EX6.VALUE8, NULL))" . "\r\n";
        $strSQL .= "        WHEN '09' THEN TO_NUMBER(DECODE(JKSM9.CODE, NULL, EX7.VALUE6, NULL))" . "\r\n";
        $strSQL .= "        WHEN '10' THEN TO_NUMBER(DECODE(JKSM10.CODE, NULL, EX8.VALUE6, NULL))" . "\r\n";
        $strSQL .= "    END AS JISSEKI_OFF," . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REPA," . "\r\n";
        $strSQL .= "    @REPB," . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REPA," . "\r\n";
        $strSQL .= "    @REPB," . "\r\n";
        $strSQL .= "    @REPC" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    JKSYAIN SM" . "\r\n";
        $strSQL .= "    INNER JOIN (SELECT" . "\r\n";
        $strSQL .= "                    SYAIN_NO," . "\r\n";
        $strSQL .= "                    MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQL .= "                FROM" . "\r\n";
        $strSQL .= "                    JKIDOURIREKI" . "\r\n";
        $strSQL .= "                WHERE" . "\r\n";
        $strSQL .= "                    TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQL .= "                GROUP BY" . "\r\n";
        $strSQL .= "                    SYAIN_NO" . "\r\n";
        $strSQL .= "               ) JKIM" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = JKIM.SYAIN_NO" . "\r\n";
        $strSQL .= "    INNER JOIN JKIDOURIREKI JKI" . "\r\n";
        $strSQL .= "        ON JKIM.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        $strSQL .= "       AND JKIM.ANNOUNCE_DT = JKI.ANNOUNCE_DT" . "\r\n";
        $strSQL .= "    INNER JOIN JKSHIKYU SIK" . "\r\n";
        $strSQL .= "        ON JKI.SYAIN_NO = SIK.SYAIN_NO" . "\r\n";
        $strSQL .= "       AND SIK.TAISYOU_YM = @REP0" . "\r\n";
        $strSQL .= "       AND SIK.KS_KB = '1'" . "\r\n";
        $strSQL .= "    INNER JOIN JKSYOREIKINMST JKSM0" . "\r\n";
        $strSQL .= "        ON JKSM0.SYUBETU_CD = '10000'" . "\r\n";
        //01 販売ルート
        $strSQL .= "    INNER JOIN JKSYOREIKINMST JKSM1" . "\r\n";
        $strSQL .= "        ON JKI.SYOKUSYU_CD || JKI.BUSYO_CD = JKSM1.CODE" . "\r\n";
        $strSQL .= "       AND JKSM1.SYUBETU_CD = '11000'" . "\r\n";
        //02 管理台数
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM2" . "\r\n";
        $strSQL .= "        ON JKSM1.ATAI_1 = JKSM2.CODE" . "\r\n";
        $strSQL .= "       AND JKSM2.SYUBETU_CD = '12002'" . "\r\n";
        $strSQL .= "    LEFT JOIN EXKANRIDAISU001 EX1" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX1.VALUE4" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND EX1.VALUE1 = @REP1" . "\r\n";
        //03 売上台数達成率
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM3" . "\r\n";
        $strSQL .= "        ON JKSM1.ATAI_1 = JKSM3.CODE" . "\r\n";
        $strSQL .= "       AND JKSM3.SYUBETU_CD = '12003'" . "\r\n";
        $strSQL .= "    LEFT JOIN EXURIDAISU00001 EX2" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX2.VALUE4" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND EX2.VALUE1 = @REP1" . "\r\n";
        //04 任意保険件数
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM4" . "\r\n";
        $strSQL .= "        ON JKSM1.ATAI_1 = JKSM4.CODE" . "\r\n";
        $strSQL .= "       AND JKSM4.SYUBETU_CD = '12004'" . "\r\n";
        $strSQL .= "    LEFT JOIN EXNINIHOKEN0001 EX3" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX3.VALUE4" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND EX3.VALUE1 = @REP1" . "\r\n";
        //05 パックdeメンテ
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM5" . "\r\n";
        $strSQL .= "        ON JKSM1.ATAI_1 = JKSM5.CODE" . "\r\n";
        $strSQL .= "       AND JKSM5.SYUBETU_CD = '12005'" . "\r\n";
        $strSQL .= "    LEFT JOIN EXPACKDEMENTE01 EX4" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX4.VALUE4" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND EX4.VALUE1 = @REP1" . "\r\n";
        //06 １年点検台数
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM6" . "\r\n";
        $strSQL .= "        ON JKSM1.ATAI_1 = JKSM6.CODE" . "\r\n";
        $strSQL .= "       AND JKSM6.SYUBETU_CD = '12006'" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT" . "\r\n";
        $strSQL .= "                   VALUE2," . "\r\n";
        $strSQL .= "                   SUM(VALUE8) AS VALUE8" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   EXSVCKOUKEN0001" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                   VALUE1 = @REP1" . "\r\n";
        $strSQL .= "               AND" . "\r\n";
        $strSQL .= "                   VALUE6 IN ('13', '14')" . "\r\n";
        $strSQL .= "               GROUP BY" . "\r\n";
        $strSQL .= "                   VALUE2" . "\r\n";
        $strSQL .= "              ) EX5" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX5.VALUE2" . "\r\n";
        //07 車検台数
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM7" . "\r\n";
        $strSQL .= "        ON JKSM1.ATAI_1 = JKSM7.CODE" . "\r\n";
        $strSQL .= "       AND JKSM7.SYUBETU_CD = '12007'" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT" . "\r\n";
        $strSQL .= "                   VALUE2," . "\r\n";
        $strSQL .= "                   SUM(VALUE8) AS VALUE8" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   EXSVCKOUKEN0001" . "\r\n";
        $strSQL .= "               WHERE" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                   VALUE1 = @REP1" . "\r\n";
        $strSQL .= "               AND" . "\r\n";
        //→HIT出力CSV加工時に'0'が欠落しても取得できるように'1'を条件にいれる
        $strSQL .= "                   VALUE6 IN ('01','1')" . "\r\n";
        $strSQL .= "               GROUP BY" . "\r\n";
        $strSQL .= "                   VALUE2" . "\r\n";
        $strSQL .= "              ) EX6" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX6.VALUE2" . "\r\n";
        //09 ＪＡＦ件数
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM9" . "\r\n";
        $strSQL .= "        ON JKSM1.ATAI_1 = JKSM9.CODE" . "\r\n";
        $strSQL .= "       AND JKSM9.SYUBETU_CD = '12009'" . "\r\n";
        $strSQL .= "    LEFT JOIN EXJAF0000000001 EX7" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX7.VALUE4" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND EX7.VALUE1 = @REP1" . "\r\n";
        //10 （TMRH）リース
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM10" . "\r\n";
        $strSQL .= "        ON JKSM1.ATAI_1 = JKSM10.CODE" . "\r\n";
        $strSQL .= "       AND JKSM10.SYUBETU_CD = '12010'" . "\r\n";
        $strSQL .= "    LEFT JOIN EXHMLEASE000001 EX8" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX8.VALUE4" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND EX8.VALUE1 = @REP1" . "\r\n";
        //正社員　又は　再雇用
        $strSQL .= "WHERE SM.KOYOU_KB_CD IN ('01','3A')" . "\r\n";

        $strSQL = str_replace("@REP0", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -1)), $strSQL);
        $strSQL = str_replace("@REPA", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@REPB", $this->ClsComFncJKSYS->FncSqlNv("GyosekiSyoreiCalc"), $strSQL);
        $strSQL = str_replace("@REPC", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);

        return parent::insert($strSQL);
    }

    //4.業績奨励金係数データの更新(係数)
    public function procUpdateGyosekiSyoreiKeisuData($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "UPDATE JKGYOSEKISYOREIKEISU JKG SET" . "\r\n";
        $strSQL .= "JKG.KEISU = NVL(" . "\r\n";
        $strSQL .= "    CASE JKG.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "        WHEN '06' THEN" . "\r\n";
        $strSQL .= "            DECODE(JISSEKI, NULL, 1.0, " . "\r\n";
        $strSQL .= "            CASE" . "\r\n";
        $strSQL .= "                WHEN (SELECT   ROUND(MONTHS_BETWEEN(to_date(@REP2,'yyyy/mm/dd'), JKS.NYUSYA_DT)/12)" . "\r\n";
        $strSQL .= "                      FROM     JKSYAIN JKS" . "\r\n";
        $strSQL .= "                      WHERE    JKS.SYAIN_NO = JKG.SYAIN_NO) < 4" . "\r\n";
        $strSQL .= "                     AND NVL(JKG.JISSEKI, 0) < 4" . "\r\n";
        $strSQL .= "                    THEN 1.0" . "\r\n";
        $strSQL .= "                ELSE" . "\r\n";
        $strSQL .= "                     (SELECT" . "\r\n";
        $strSQL .= "                          KEISU" . "\r\n";
        $strSQL .= "                     FROM" . "\r\n";
        $strSQL .= "                          JKKEISUMST" . "\r\n";
        $strSQL .= "                     WHERE" . "\r\n";
        $strSQL .= "                          RANGE_FROM <= NVL(JKG.JISSEKI, 0)" . "\r\n";
        $strSQL .= "                     AND" . "\r\n";
        $strSQL .= "                          RANGE_TO >= NVL(JKG.JISSEKI, 0)" . "\r\n";
        $strSQL .= "                     AND" . "\r\n";
        $strSQL .= "                          KEISU_SYURUI = JKG.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "                     AND" . "\r\n";
        $strSQL .= "                          SYOREIKIN_KB = '1')" . "\r\n";
        $strSQL .= "            END)" . "\r\n";
        $strSQL .= "        WHEN '07' THEN" . "\r\n";
        $strSQL .= "            DECODE(JISSEKI, NULL, 1.0, " . "\r\n";
        $strSQL .= "            CASE" . "\r\n";
        $strSQL .= "                WHEN (SELECT   ROUND(MONTHS_BETWEEN(to_date(@REP2,'yyyy/mm/dd'), JKS.NYUSYA_DT)/12)" . "\r\n";
        $strSQL .= "                      FROM     JKSYAIN JKS" . "\r\n";
        $strSQL .= "                      WHERE    JKS.SYAIN_NO = JKG.SYAIN_NO) < 4" . "\r\n";
        $strSQL .= "                     AND NVL(JKG.JISSEKI, 0) < 5" . "\r\n";
        $strSQL .= "                    THEN 1.0" . "\r\n";
        $strSQL .= "                ELSE" . "\r\n";
        $strSQL .= "                     (SELECT" . "\r\n";
        $strSQL .= "                          KEISU" . "\r\n";
        $strSQL .= "                     FROM" . "\r\n";
        $strSQL .= "                          JKKEISUMST" . "\r\n";
        $strSQL .= "                     WHERE" . "\r\n";
        $strSQL .= "                          RANGE_FROM <= NVL(JKG.JISSEKI, 0)" . "\r\n";
        $strSQL .= "                     AND" . "\r\n";
        $strSQL .= "                          RANGE_TO >= NVL(JKG.JISSEKI, 0)" . "\r\n";
        $strSQL .= "                     AND" . "\r\n";
        $strSQL .= "                          KEISU_SYURUI = JKG.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "                     AND" . "\r\n";
        $strSQL .= "                          SYOREIKIN_KB = '1')" . "\r\n";
        $strSQL .= "            END)" . "\r\n";
        $strSQL .= "        WHEN '01' THEN" . "\r\n";
        $strSQL .= "            (SELECT" . "\r\n";
        $strSQL .= "                 KEISU" . "\r\n";
        $strSQL .= "             FROM" . "\r\n";
        $strSQL .= "                 JKKEISUMST" . "\r\n";
        $strSQL .= "             WHERE" . "\r\n";
        $strSQL .= "                 KOUMOKU_NO = JKG.JISSEKI" . "\r\n";
        $strSQL .= "             AND" . "\r\n";
        $strSQL .= "                 KEISU_SYURUI = JKG.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "             AND" . "\r\n";
        $strSQL .= "                 SYOREIKIN_KB = '1')" . "\r\n";
        $strSQL .= "        ELSE" . "\r\n";
        $strSQL .= "            DECODE(JISSEKI, NULL, 1.0, " . "\r\n";
        $strSQL .= "            (SELECT" . "\r\n";
        $strSQL .= "                 KEISU" . "\r\n";
        $strSQL .= "             FROM" . "\r\n";
        $strSQL .= "                 JKKEISUMST" . "\r\n";
        $strSQL .= "             WHERE" . "\r\n";
        $strSQL .= "                 RANGE_FROM <= NVL(JKG.JISSEKI, 0)" . "\r\n";
        $strSQL .= "             AND" . "\r\n";
        $strSQL .= "                 RANGE_TO >= NVL(JKG.JISSEKI, 0)" . "\r\n";
        $strSQL .= "             AND" . "\r\n";
        $strSQL .= "                 KEISU_SYURUI = JKG.KEISU_KOMOKU" . "\r\n";
        $strSQL .= "             AND" . "\r\n";
        $strSQL .= "                 SYOREIKIN_KB = '1'))" . "\r\n";
        $strSQL .= "    END ,0)" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    SIKYU_YM = @REP0" . "\r\n";

        //画面支給年月
        $strSQL = str_replace("@REP0", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        //画面支給年月-1か月
        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -1)), $strSQL);
        //画面支給年月-1か月の末日
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($this->getlastday($dtpYM)), $strSQL);

        return parent::update($strSQL);
    }

    //5.業績奨励金データの登録
    public function procInsertGyosekiSyoureiKinHdrData($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKGYOSEKISYOREI (" . "\r\n";
        $strSQL .= "SIKYU_YM," . "\r\n";
        $strSQL .= "SYAIN_NO," . "\r\n";
        $strSQL .= "SYAIN_NM," . "\r\n";
        $strSQL .= "BUSYO_CD," . "\r\n";
        $strSQL .= "SYOKUSYU_CD," . "\r\n";
        $strSQL .= "KOYOU_KB_CD," . "\r\n";
        $strSQL .= "GENKAI_RIEKI1," . "\r\n";
        $strSQL .= "GENKAI_RIEKI2," . "\r\n";
        $strSQL .= "GENKAI_RIEKI," . "\r\n";
        $strSQL .= "KEISU_TOTAL," . "\r\n";
        $strSQL .= "SANSYUTU_KINGAKU," . "\r\n";
        $strSQL .= "ZEN_KYUYO," . "\r\n";
        $strSQL .= "ZEN_SOUSIKYU," . "\r\n";
        $strSQL .= "SANSYUTU_SYOUREIKIN," . "\r\n";
        $strSQL .= "SHIHARAI_SYOUREIKIN," . "\r\n";
        $strSQL .= "ZANGYO_TEATE," . "\r\n";
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
        $strSQL .= "    SM.SYAIN_NO," . "\r\n";
        $strSQL .= "    SM.SYAIN_NM," . "\r\n";
        $strSQL .= "    JKI.BUSYO_CD," . "\r\n";
        $strSQL .= "    JKI.SYOKUSYU_CD," . "\r\n";
        $strSQL .= "    SM.KOYOU_KB_CD," . "\r\n";
        $strSQL .= "    NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "        NVL(EX1_2.VALUE6, 0) + " . "\r\n";
        $strSQL .= "        NVL(FUR_2.FURIKAE_KIN, 0) AS GENKAI_RIEKI1," . "\r\n";
        $strSQL .= "    NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "        NVL(EX1_1.VALUE6, 0) + " . "\r\n";
        $strSQL .= "        NVL(FUR_1.FURIKAE_KIN, 0) AS GENKAI_RIEKI2," . "\r\n";
        $strSQL .= "    ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "        NVL(EX1_1.VALUE6, 0)) / 2) AS GENKAI_RIEKI," . "\r\n";
        $strSQL .= "    ROUND(JKGK.KEISU_SUM,2) AS KEISU_TOTAL," . "\r\n";
        $strSQL .= "    ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                 NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                 NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                 NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                 NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                 NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                 NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                 NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) AS SANSYUTU_KINGAKU," . "\r\n";
        //基本給
        $strSQL .= "        NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        //職務手当
        $strSQL .= "        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        //業績奨励金Ａ
        $strSQL .= "        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        //家族手当
        $strSQL .= "        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        //調整加給
        $strSQL .= "        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        //通勤手当
        $strSQL .= "        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        //課税通勤手当
        $strSQL .= "        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        //業績奨励金Ｂ
        $strSQL .= "        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        //紹介手当
        $strSQL .= "        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        //車両手当
        $strSQL .= "        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        //社員リース料社員負担
        $strSQL .= "        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        //研修手当
        $strSQL .= "        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU19, 0) AS ZEN_KYUYO," . "\r\n";
        $strSQL .= "       (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "        NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1 AS ZEN_SOUSIKYU," . "\r\n";
        $strSQL .= "    CASE" . "\r\n";
        $strSQL .= "        WHEN" . "\r\n";
        $strSQL .= "            ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "               NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "               NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "               NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "               NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "               NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "               NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "               NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) < " . "\r\n";
        $strSQL .= "            (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "             NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "             NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1" . "\r\n";
        $strSQL .= "        THEN 0" . "\r\n";
        $strSQL .= "        ELSE" . "\r\n";
        $strSQL .= "            (ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                          NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                          NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                          NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                          NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                          NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                          NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                          NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) - " . "\r\n";
        $strSQL .= "             (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "              NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "              NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1) * (SELECT ATAI_1 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '12000' AND CODE = '1')" . "\r\n";
        $strSQL .= "    END AS SANSYUTU_SYOUREIKIN," . "\r\n";
        $strSQL .= "    CASE" . "\r\n";
        $strSQL .= "        WHEN " . "\r\n";
        $strSQL .= "            CASE" . "\r\n";
        $strSQL .= "                WHEN" . "\r\n";
        $strSQL .= "                     ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) < " . "\r\n";
        $strSQL .= "                     (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                      NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1" . "\r\n";
        $strSQL .= "                    THEN 0" . "\r\n";
        $strSQL .= "                ELSE" . "\r\n";
        $strSQL .= "                     (ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) - " . "\r\n";
        $strSQL .= "                     (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                      NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1) * (SELECT ATAI_1 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '12000' AND CODE = '1')" . "\r\n";
        $strSQL .= "            END < TO_NUMBER(NVL(JKSM2.ATAI_1, JKSM2B.ATAI_1))" . "\r\n";
        $strSQL .= "        THEN" . "\r\n";
        $strSQL .= "            CASE" . "\r\n";
        $strSQL .= "                WHEN" . "\r\n";
        $strSQL .= "                     ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) < " . "\r\n";
        $strSQL .= "                     (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                      NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1" . "\r\n";
        $strSQL .= "                    THEN 0" . "\r\n";
        $strSQL .= "                ELSE" . "\r\n";
        $strSQL .= "                     (ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) - " . "\r\n";
        $strSQL .= "                     (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                      NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                      NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1) * (SELECT ATAI_1 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '12000' AND CODE = '1')" . "\r\n";
        $strSQL .= "            END" . "\r\n";
        $strSQL .= "        ELSE" . "\r\n";
        $strSQL .= "            TO_NUMBER(NVL(JKSM2.ATAI_1, JKSM2B.ATAI_1))" . "\r\n";
        $strSQL .= "    END AS SHIHARAI_SYOUREIKIN," . "\r\n";
        $strSQL .= "    NVL(JKS0.SHIKYU19, 0) AS ZANGYO_TEATE," . "\r\n";
        $strSQL .= "    CASE" . "\r\n";
        $strSQL .= "        WHEN " . "\r\n";
        $strSQL .= "        CASE" . "\r\n";
        $strSQL .= "            WHEN " . "\r\n";
        $strSQL .= "                CASE" . "\r\n";
        $strSQL .= "                    WHEN" . "\r\n";
        $strSQL .= "                        ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) < " . "\r\n";
        $strSQL .= "                       (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1" . "\r\n";
        $strSQL .= "                      THEN 0" . "\r\n";
        $strSQL .= "                    ELSE" . "\r\n";
        $strSQL .= "                       (ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) - " . "\r\n";
        $strSQL .= "                       (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1) * (SELECT ATAI_1 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '12000' AND CODE = '1')" . "\r\n";
        $strSQL .= "                END < TO_NUMBER(NVL(JKSM2.ATAI_1, JKSM2B.ATAI_1))" . "\r\n";
        $strSQL .= "            THEN" . "\r\n";
        $strSQL .= "                CASE" . "\r\n";
        $strSQL .= "                    WHEN" . "\r\n";
        $strSQL .= "                        ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) < " . "\r\n";
        $strSQL .= "                       (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1" . "\r\n";
        $strSQL .= "                      THEN 0" . "\r\n";
        $strSQL .= "                    ELSE" . "\r\n";
        $strSQL .= "                       (ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) - " . "\r\n";
        $strSQL .= "                       (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1) * (SELECT ATAI_1 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '12000' AND CODE = '1')" . "\r\n";
        $strSQL .= "                END" . "\r\n";
        $strSQL .= "            ELSE" . "\r\n";
        $strSQL .= "                TO_NUMBER(NVL(JKSM2.ATAI_1, JKSM2B.ATAI_1))" . "\r\n";
        $strSQL .= "        END < NVL(JKS0.SHIKYU19, 0) THEN 0" . "\r\n";
        $strSQL .= "    ELSE" . "\r\n";
        $strSQL .= "        CASE" . "\r\n";
        $strSQL .= "            WHEN " . "\r\n";
        $strSQL .= "                CASE" . "\r\n";
        $strSQL .= "                    WHEN" . "\r\n";
        $strSQL .= "                        ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) < " . "\r\n";
        $strSQL .= "                       (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1" . "\r\n";
        $strSQL .= "                      THEN 0" . "\r\n";
        $strSQL .= "                    ELSE" . "\r\n";
        $strSQL .= "                       (ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) - " . "\r\n";
        $strSQL .= "                       (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1) * (SELECT ATAI_1 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '12000' AND CODE = '1')" . "\r\n";
        $strSQL .= "                END < TO_NUMBER(NVL(JKSM2.ATAI_1, JKSM2B.ATAI_1))" . "\r\n";
        $strSQL .= "            THEN" . "\r\n";
        $strSQL .= "                CASE" . "\r\n";
        $strSQL .= "                    WHEN" . "\r\n";
        $strSQL .= "                        ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) < " . "\r\n";
        $strSQL .= "                       (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1" . "\r\n";
        $strSQL .= "                      THEN 0" . "\r\n";
        $strSQL .= "                    ELSE" . "\r\n";
        $strSQL .= "                       (ROUND(ROUND((NVL(HGVW2.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW2.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(EX1_2.VALUE6, 0) +" . "\r\n";
        $strSQL .= "                        NVL(HGVW1.TOUGETU_GENRI, 0) + " . "\r\n";
        $strSQL .= "                        NVL(HGVW1.KB, 0) + " . "\r\n";
        $strSQL .= "                        NVL(FUR_2.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(FUR_1.FURIKAE_KIN, 0) +" . "\r\n";
        $strSQL .= "                        NVL(EX1_1.VALUE6, 0)) / 2) * ROUND(JKGK.KEISU_SUM,2)) - " . "\r\n";
        $strSQL .= "                       (NVL(JKS1.SHIKYU1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU2, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU3, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU5, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU9, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU18_1, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU4, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU7, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU8, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU12, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU6, 0) + " . "\r\n";
        //奨励手当
        $strSQL .= "                        NVL(JKS1.SHIKYU11, 0) + " . "\r\n";
        $strSQL .= "                        NVL(JKS1.SHIKYU19, 0)) * JKSM3.ATAI_1) * (SELECT ATAI_1 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '12000' AND CODE = '1')" . "\r\n";
        $strSQL .= "                END" . "\r\n";
        $strSQL .= "        ELSE" . "\r\n";
        $strSQL .= "             TO_NUMBER(NVL(JKSM2.ATAI_1, JKSM2B.ATAI_1))" . "\r\n";
        $strSQL .= "        END - NVL(JKS0.SHIKYU19, 0) " . "\r\n";
        $strSQL .= "    END  AS SYOREI_TEATE," . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REPA," . "\r\n";
        $strSQL .= "    @REPB," . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REPA," . "\r\n";
        $strSQL .= "    @REPB," . "\r\n";
        $strSQL .= "    @REPC" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    JKSYAIN SM" . "\r\n";
        $strSQL .= "    INNER JOIN (SELECT" . "\r\n";
        $strSQL .= "                    SYAIN_NO," . "\r\n";
        $strSQL .= "                    MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQL .= "                FROM" . "\r\n";
        $strSQL .= "                    JKIDOURIREKI" . "\r\n";
        $strSQL .= "                WHERE" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "                    TO_CHAR(ANNOUNCE_DT, 'yyyyMM') <= @REP1" . "\r\n";
        $strSQL .= "                GROUP BY" . "\r\n";
        $strSQL .= "                    SYAIN_NO" . "\r\n";
        $strSQL .= "               ) JKIM" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = JKIM.SYAIN_NO" . "\r\n";
        $strSQL .= "    INNER JOIN JKIDOURIREKI JKI" . "\r\n";
        $strSQL .= "        ON JKIM.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        $strSQL .= "       AND JKIM.ANNOUNCE_DT = JKI.ANNOUNCE_DT" . "\r\n";
        $strSQL .= "    INNER JOIN JKSHIKYU SIK" . "\r\n";
        $strSQL .= "        ON JKI.SYAIN_NO = SIK.SYAIN_NO" . "\r\n";
        $strSQL .= "       AND SIK.TAISYOU_YM = @REP0" . "\r\n";
        $strSQL .= "       AND SIK.KS_KB = '1'" . "\r\n";
        $strSQL .= "    INNER JOIN JKSYOREIKINMST JKSM1" . "\r\n";
        $strSQL .= "        ON JKI.SYOKUSYU_CD || JKI.BUSYO_CD = JKSM1.CODE" . "\r\n";
        $strSQL .= "       AND JKSM1.SYUBETU_CD = '11000'" . "\r\n";

        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM2" . "\r\n";
        $strSQL .= "        ON '1' || SM.KOYOU_KB_CD || JKI.SYOKUSYU_CD = JKSM2.CODE" . "\r\n";
        $strSQL .= "       AND JKSM2.SYUBETU_CD = 'JOGEN'" . "\r\n";
        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM2B" . "\r\n";
        $strSQL .= "        ON '1' = JKSM2B.CODE" . "\r\n";
        $strSQL .= "       AND JKSM2B.SYUBETU_CD = 'JOGEN'" . "\r\n";

        $strSQL .= "    LEFT JOIN JKSYOREIKINMST JKSM3" . "\r\n";
        $strSQL .= "        ON JKSM3.SYUBETU_CD = '10001'" . "\r\n";
        $strSQL .= "       AND JKSM3.CODE = JKSM1.ATAI_1" . "\r\n";

        $strSQL .= "    LEFT JOIN (SELECT   ATUKAI_SYAIN," . "\r\n";
        $strSQL .= "                        SUM(TOUGETU_GENRI) AS TOUGETU_GENRI," . "\r\n";
        $strSQL .= "                        SUM(KB) AS KB" . "\r\n";
        $strSQL .= "               FROM     HGENRI_VW" . "\r\n";
        //画面.支給年月-2カ月
        $strSQL .= "               WHERE    NENGETU = @REP2" . "\r\n";
        $strSQL .= "               GROUP BY ATUKAI_SYAIN) HGVW2" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = HGVW2.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT   ATUKAI_SYAIN," . "\r\n";
        $strSQL .= "                        SUM(TOUGETU_GENRI) AS TOUGETU_GENRI," . "\r\n";
        $strSQL .= "                        SUM(KB) AS KB" . "\r\n";
        $strSQL .= "               FROM     HGENRI_VW" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "               WHERE    NENGETU = @REP1" . "\r\n";
        $strSQL .= "               GROUP BY ATUKAI_SYAIN) HGVW1" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = HGVW1.ATUKAI_SYAIN" . "\r\n";

        $strSQL .= "    LEFT JOIN (SELECT   VALUE1," . "\r\n";
        $strSQL .= "                        VALUE4," . "\r\n";
        $strSQL .= "                        SUM(NVL(VALUE6,0)) AS VALUE6" . "\r\n";
        $strSQL .= "               FROM     EXSAILEASE00001" . "\r\n";
        //画面.支給年月-2カ月
        $strSQL .= "               WHERE    VALUE1 = @REP2" . "\r\n";
        $strSQL .= "               GROUP BY VALUE1," . "\r\n";
        $strSQL .= "                        VALUE4) EX1_2" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX1_2.VALUE4" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT   VALUE1," . "\r\n";
        $strSQL .= "                        VALUE4," . "\r\n";
        $strSQL .= "                        SUM(NVL(VALUE6,0)) AS VALUE6" . "\r\n";
        $strSQL .= "               FROM     EXSAILEASE00001" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "               WHERE    VALUE1 = @REP1" . "\r\n";
        $strSQL .= "               GROUP BY VALUE1," . "\r\n";
        $strSQL .= "                        VALUE4) EX1_1" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = EX1_1.VALUE4" . "\r\n";

        ////拠点振替追加
        $strSQL .= "    LEFT JOIN (SELECT   NENGETU," . "\r\n";
        $strSQL .= "                        SYAIN_CD," . "\r\n";
        $strSQL .= "                        SUM(NVL(FURIKAE_KIN,0)) AS FURIKAE_KIN" . "\r\n";
        $strSQL .= "               FROM     HKYOTENFURIKAE" . "\r\n";
        $strSQL .= "               WHERE    NENGETU = @REP2" . "\r\n";
        //画面.支給年月-2カ月
        $strSQL .= "               GROUP BY NENGETU," . "\r\n";
        $strSQL .= "                        SYAIN_CD) FUR_2" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = FUR_2.SYAIN_CD" . "\r\n";
        $strSQL .= "    LEFT JOIN (SELECT   NENGETU," . "\r\n";
        $strSQL .= "                        SYAIN_CD," . "\r\n";
        $strSQL .= "                        SUM(NVL(FURIKAE_KIN,0)) AS FURIKAE_KIN" . "\r\n";
        $strSQL .= "               FROM     HKYOTENFURIKAE" . "\r\n";
        $strSQL .= "               WHERE    NENGETU = @REP1" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "               GROUP BY NENGETU," . "\r\n";
        $strSQL .= "                        SYAIN_CD) FUR_1" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = FUR_1.SYAIN_CD" . "\r\n";
        $strSQL .= "    LEFT JOIN JKSHIKYU JKS0" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = JKS0.SYAIN_NO" . "\r\n";
        //画面年月
        $strSQL .= "       AND JKS0.TAISYOU_YM = @REP0" . "\r\n";
        $strSQL .= "       AND JKS0.KS_KB = '1'" . "\r\n";
        $strSQL .= "    LEFT JOIN JKSHIKYU JKS1" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = JKS1.SYAIN_NO" . "\r\n";
        //画面.支給年月-1カ月
        $strSQL .= "       AND JKS1.TAISYOU_YM = @REP1" . "\r\n";
        $strSQL .= "       AND JKS1.KS_KB = '1'" . "\r\n";

        $strSQL .= "    LEFT JOIN (SELECT" . "\r\n";
        $strSQL .= "                   A.SYAIN_NO," . "\r\n";
        $strSQL .= "                   A.KEISU_SUM" . "\r\n";
        $strSQL .= "               FROM" . "\r\n";
        $strSQL .= "                   (SELECT" . "\r\n";
        $strSQL .= "                        SYAIN_NO," . "\r\n";
        $strSQL .= "                        RNUM," . "\r\n";
        $strSQL .= "                        KEISU," . "\r\n";
        $strSQL .= "                        KEISU_SUM" . "\r\n";
        $strSQL .= "                    FROM     (SELECT" . "\r\n";
        $strSQL .= "                                  SYAIN_NO," . "\r\n";
        $strSQL .= "                                  ROW_NUMBER() OVER(PARTITION BY SYAIN_NO ORDER BY SYAIN_NO, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                  KEISU" . "\r\n";
        $strSQL .= "                              FROM" . "\r\n";
        $strSQL .= "                                  JKGYOSEKISYOREIKEISU" . "\r\n";
        $strSQL .= "                              WHERE" . "\r\n";
        $strSQL .= "                                  SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                             )" . "\r\n";
        $strSQL .= "                    MODEL" . "\r\n";
        $strSQL .= "                        PARTITION BY (SYAIN_NO)" . "\r\n";
        $strSQL .= "                        DIMENSION BY (RNUM)" . "\r\n";
        $strSQL .= "                        MEASURES (KEISU, KEISU KEISU_SUM)" . "\r\n";
        $strSQL .= "                        RULES(" . "\r\n";
        $strSQL .= "                            KEISU_SUM[RNUM IS ANY] ORDER BY RNUM" . "\r\n";
        $strSQL .= "                                = KEISU_SUM[CV(RNUM)] *  NVL(KEISU_SUM[CV(RNUM)-1], 1))" . "\r\n";
        $strSQL .= "                    ORDER BY" . "\r\n";
        $strSQL .= "                        SYAIN_NO" . "\r\n";
        $strSQL .= "                   ) A" . "\r\n";
        $strSQL .= "                   INNER JOIN (SELECT   SYAIN_NO," . "\r\n";
        $strSQL .= "                                        MAX(RNUM) AS RNUM" . "\r\n";
        $strSQL .= "                               FROM     (SELECT" . "\r\n";
        $strSQL .= "                                             SYAIN_NO," . "\r\n";
        $strSQL .= "                                             ROW_NUMBER() OVER(PARTITION BY SYAIN_NO ORDER BY SYAIN_NO, KEISU_KOMOKU) RNUM," . "\r\n";
        $strSQL .= "                                             KEISU" . "\r\n";
        $strSQL .= "                                         FROM" . "\r\n";
        $strSQL .= "                                             JKGYOSEKISYOREIKEISU" . "\r\n";
        $strSQL .= "                                         WHERE" . "\r\n";
        $strSQL .= "                                             SIKYU_YM = @REP0" . "\r\n";
        $strSQL .= "                                        )" . "\r\n";
        $strSQL .= "                               GROUP BY SYAIN_NO" . "\r\n";
        $strSQL .= "                              ) B" . "\r\n";
        $strSQL .= "                       ON A.SYAIN_NO = B.SYAIN_NO" . "\r\n";
        $strSQL .= "                      AND A.RNUM = B.RNUM" . "\r\n";
        $strSQL .= "              ) JKGK" . "\r\n";
        $strSQL .= "        ON SM.SYAIN_NO = JKGK.SYAIN_NO" . "\r\n";
        //正社員　又は　再雇用
        $strSQL .= "WHERE SM.KOYOU_KB_CD IN ('01','3A')" . "\r\n";

        $strSQL = str_replace("@REP0", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -1)), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($this->getPreMonth($dtpYM, -2)), $strSQL);
        $strSQL = str_replace("@REPA", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER["strUserID"]), $strSQL);
        $strSQL = str_replace("@REPB", $this->ClsComFncJKSYS->FncSqlNv("GyosekiSyoreiCalc"), $strSQL);
        $strSQL = str_replace("@REPC", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER["strClientNM"]), $strSQL);

        return parent::insert($strSQL);
    }

    //データの存在チェック（ロジック）
    public function procCheckDataLogic($intMode, $strTblNm, $dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    COUNT(*) AS CNT" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    @TBL" . "\r\n";

        switch ($intMode) {
            case 0:
            case 1:
                //'支給情報:
                $strSQL .= "WHERE" . "\r\n";
                $strSQL .= "    TAISYOU_YM = @REP" . "\r\n";
                break;
            case 10:
                //'限界利益
                $strSQL .= "WHERE" . "\r\n";
                $strSQL .= "    NENGETU = @REP" . "\r\n";
                break;
            default:
                //'（TMRH）リース_再リース情報, 管理台数情報, 売上達成率情報
                //'任意保険新規情報, パックｄｅメンテ情報, サービス貢献度情報
                //'ＪＡＦ情報, （TMRH）リース_新規
                $strSQL .= "WHERE" . "\r\n";
                $strSQL .= "    VALUE1 = @REP" . "\r\n";
                break;
        }

        $strSQL = str_replace("@TBL", $strTblNm, $strSQL);
        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);

        return parent::select($strSQL);
    }

    //画面支給年月-1
    public function getPreMonth($dtpYM, $num)
    {
        $dtpYM = $dtpYM . "01";
        $rtnDate = date('Ym', strtotime("$dtpYM $num month"));

        return $rtnDate;
    }

    //画面支給年月-1か月の末日
    public function getlastday($dtpYM)
    {
        $dtpYM = $dtpYM . "01";
        $lastday = date('Y/m/d', strtotime("$dtpYM -1 day"));

        return $lastday;
    }

}
