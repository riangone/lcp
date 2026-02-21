<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

class FrmSyoyoSikyuCalc extends ClsComDb
{
    //評価取込履歴データ年月取得SQL
    public function fncHyoukaTrkRirekiSQL($_strTeikiSyokyuMonth)
    {
        $strSQL = "";
        $strSQL .= " SELECT substr(JISSHI_YM, 0, 4) || '/' || substr(JISSHI_YM, 5, 2) AS JISSHI_YM  " . " \r\n";
        $strSQL .= " FROM   JKHYOUKATRKRIREKI" . " \r\n";
        $strSQL .= " WHERE" . " \r\n";
        $strSQL .= "     SUBSTR(JISSHI_YM,5,2) <> '" . $_strTeikiSyokyuMonth . "'" . " \r\n";
        $strSQL .= " ORDER BY JISSHI_YM  DESC" . " \r\n";
        return $strSQL;
    }

    //評価取込履歴データ期間取得SQL
    public function fncHyoukaTrkRirekiKikanSQL($cmbYM)
    {
        $strSQL = "";
        $strSQL .= " SELECT to_char(HYOUKA_KIKAN_START, 'YYYY/MM/DD') || ' ～ ' || to_char(HYOUKA_KIKAN_END, 'YYYY/MM/DD') AS KIKAN " . " \r\n";
        $strSQL .= "      , to_char(HYOUKA_KIKAN_START, 'YYYY/MM/DD') AS HYOUKA_KIKAN_START " . " \r\n";
        $strSQL .= "      , to_char(HYOUKA_KIKAN_END, 'YYYY/MM/DD') AS HYOUKA_KIKAN_END " . " \r\n";
        $strSQL .= "      ,JISSHI_YM" . " \r\n";
        $strSQL .= " FROM   JKHYOUKATRKRIREKI  " . " \r\n";
        $strSQL .= " WHERE  JISSHI_YM = '@JISSHI_YM'   " . " \r\n";
        $strSQL = str_replace("@JISSHI_YM", substr($cmbYM, 0, 4) . substr($cmbYM, 5, 2), $strSQL);
        return $strSQL;
    }

    //評価履歴データ取得SQL
    public function fncHyoukaRirekiSQL($cmbYM)
    {
        $strSQL = "";
        $strSQL .= " SELECT JISSHI_YM " . " \r\n";
        $strSQL .= " FROM   JKHYOUKARIREKI " . " \r\n";
        $strSQL .= " WHERE  JISSHI_YM = '@JISSHI_YM'   " . " \r\n";
        $strSQL = str_replace("@JISSHI_YM", substr($cmbYM, 0, 4) . substr($cmbYM, 5, 2), $strSQL);
        return $strSQL;
    }

    //CSV出力データ取得SQL
    public function fncCsvOutputDataSQL($_hyuokaTaisyouKikanSD, $_hyuokaTaisyouKikanED, $cmbYM)
    {
        $strSql = "";
        $strSql .= " SELECT     HYOKA.SYAIN_NO " . "\r\n";
        $strSql .= "          , SYA.SYAIN_NM " . "\r\n";
        $strSql .= "          , SKS.SYOKUSYU_CD " . "\r\n";
        $strSql .= "          , SKSNM.MEISYOU SYOKUSYU_NM " . "\r\n";
        $strSql .= "          , SKK.SHIKAKU_CD " . "\r\n";
        $strSql .= "          , SKKNM.MEISYOU SHIKAKU_NM " . "\r\n";
        $strSql .= "          , IDO.POSITION_CD " . "\r\n";
        $strSql .= "          , IDO.POSITION_NM " . "\r\n";
        $strSql .= "          , (CASE WHEN KOY.SYAIN_NO IS NULL THEN SYA.KOYOU_KB_CD ELSE KOY.BEF_KOYOU_KB_CD END) KOYOU_KUBUN_CD " . "\r\n";
        $strSql .= "          , (CASE WHEN KOY.SYAIN_NO IS NULL THEN KYO.KUBUN_NM ELSE BKYO.KUBUN_NM END) KOYOU_KUBUN_NM " . "\r\n";
        $strSql .= "          , TANKA.KIHONKYU " . "\r\n";
        $strSql .= "          , HYOKA.LAST_HYOUKA " . "\r\n";
        $strSql .= " FROM       JKHYOUKARIREKI HYOKA " . "\r\n";
        $strSql .= " LEFT JOIN  JKSYAIN SYA " . "\r\n";
        $strSql .= " ON         SYA.SYAIN_NO = HYOKA.SYAIN_NO " . "\r\n";
        $strSql .= " LEFT JOIN  JKKUBUNMST KYO " . "\r\n";
        $strSql .= " ON         KYO.KUBUN_CD = SYA.KOYOU_KB_CD " . "\r\n";
        $strSql .= " AND        KYO.KUBUN_ID = 'KOYOU' " . "\r\n";
        $strSql .= " LEFT JOIN  (SELECT     IDO2.SYAIN_NO " . "\r\n";
        $strSql .= "                      , IDO2.ANNOUNCE_DT " . "\r\n";
        $strSql .= "                      , IDO2.POSITION_CD " . "\r\n";
        $strSql .= "                      , YKNM.MEISYOU POSITION_NM " . "\r\n";
        $strSql .= "             FROM       JKIDOURIREKI IDO2 " . "\r\n";
        $strSql .= "             INNER JOIN (SELECT   IDO3.SYAIN_NO " . "\r\n";
        $strSql .= "                                , MAX(IDO3.ANNOUNCE_DT) ANNOUNCE_DT " . "\r\n";
        $strSql .= "                         FROM     JKIDOURIREKI IDO3 " . "\r\n";
        $strSql .= "                         WHERE    IDO3.ANNOUNCE_DT <= TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= "                         GROUP BY IDO3.SYAIN_NO " . "\r\n";
        $strSql .= "                        ) IDO4 " . "\r\n";
        $strSql .= "             ON         IDO2.SYAIN_NO = IDO4.SYAIN_NO " . "\r\n";
        $strSql .= "             AND        IDO2.ANNOUNCE_DT = IDO4.ANNOUNCE_DT " . "\r\n";
        $strSql .= "             LEFT JOIN  JKYAKUSYOKUMST YKNM " . "\r\n";
        $strSql .= "             ON         YKNM.CODE = IDO2.POSITION_CD " . "\r\n";
        $strSql .= "            ) IDO " . "\r\n";
        $strSql .= " ON         IDO.SYAIN_NO = HYOKA.SYAIN_NO " . "\r\n";
        $strSql .= " LEFT JOIN  (SELECT     TKA2.SYAIN_NO " . "\r\n";
        $strSql .= "                      , TKA2.RIVISION_DT " . "\r\n";
        $strSql .= "                      , TKA2.KIHONKYU " . "\r\n";
        $strSql .= "             FROM       JKTANKARIREKI TKA2 " . "\r\n";
        $strSql .= "             INNER JOIN (SELECT   TKA3.SYAIN_NO " . "\r\n";
        $strSql .= "                                , MAX(TKA3.RIVISION_DT) RIVISION_DT " . "\r\n";
        $strSql .= "                         FROM     JKTANKARIREKI TKA3 " . "\r\n";
        $strSql .= "                         WHERE    TKA3.RIVISION_DT <= TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= "                         GROUP BY TKA3.SYAIN_NO " . "\r\n";
        $strSql .= "                        ) TKA4 " . "\r\n";
        $strSql .= "             ON         TKA2.SYAIN_NO = TKA4.SYAIN_NO " . "\r\n";
        $strSql .= "             AND        TKA2.RIVISION_DT = TKA4.RIVISION_DT " . "\r\n";
        $strSql .= "            ) TANKA " . "\r\n";
        $strSql .= " ON         TANKA.SYAIN_NO = HYOKA.SYAIN_NO " . "\r\n";
        $strSql .= " LEFT JOIN  (SELECT MSKS.SYAIN_NO " . "\r\n";
        $strSql .= "                  , MSKS.SYOKUSYU_CD " . "\r\n";
        $strSql .= "             FROM   (SELECT   WK.SYAIN_NO " . "\r\n";
        $strSql .= "                            , ROW_NUMBER() OVER(ORDER BY SYAIN_NO, SUM(MONTHS_BETWEEN(WK.ET, WK.ST)) desc, MAX(WK.ET) desc) - RANK() OVER(ORDER BY SYAIN_NO) RNK " . "\r\n";
        $strSql .= "                            , SUM(MONTHS_BETWEEN(WK.ET, WK.ST)) " . "\r\n";
        $strSql .= "                            , WK.SYOKUSYU_CD " . "\r\n";
        $strSql .= "                     FROM     (SELECT  V.SYAIN_NO " . "\r\n";
        $strSql .= "                                     , (CASE WHEN V.STARTDT < TO_DATE('@HYOUKA_KIKAN_START', 'YYYY/MM/DD') THEN TO_DATE('@HYOUKA_KIKAN_START', 'YYYY/MM/DD') ELSE V.STARTDT END) ST " . "\r\n";
        $strSql .= "                                     , (CASE WHEN NVL(V.ENDDT,TO_DATE('9999/12/31', 'YYYY/MM/DD')) > TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') THEN TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') ELSE V.ENDDT END) ET " . "\r\n";
        $strSql .= "                                     , V.SYOKUSYU_CD " . "\r\n";
        $strSql .= "                               FROM  (SELECT HAI.SYAIN_NO " . "\r\n";
        $strSql .= "                                           , HAI.SYOKUSYU_CD " . "\r\n";
        $strSql .= "                                           , HAI.ANNOUNCE_DT STARTDT " . "\r\n";
        $strSql .= "                                           , LEAD((HAI.ANNOUNCE_DT-1)) OVER(PARTITION BY HAI.SYAIN_NO ORDER BY HAI.SYAIN_NO, HAI.ANNOUNCE_DT) ENDDT " . "\r\n";
        $strSql .= "                                      FROM   JKIDOURIREKI HAI " . "\r\n";
        $strSql .= "                                     ) V " . "\r\n";
        $strSql .= "                               WHERE NVL(V.ENDDT,TO_DATE('9999/12/31', 'YYYY/MM/DD')) >= TO_DATE('@HYOUKA_KIKAN_START', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= "                               AND   V.STARTDT <= TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= "                              ) WK " . "\r\n";
        $strSql .= "                     GROUP BY WK.SYAIN_NO, WK.SYOKUSYU_CD " . "\r\n";
        $strSql .= "                    ) MSKS " . "\r\n";
        $strSql .= "         WHERE(MSKS.RNK = 0) " . "\r\n";
        $strSql .= "            ) SKS " . "\r\n";
        $strSql .= " ON         SKS.SYAIN_NO = HYOKA.SYAIN_NO " . "\r\n";
        $strSql .= " LEFT JOIN  JKCODEMST SKSNM " . "\r\n";
        $strSql .= " ON         SKSNM.CODE = SKS.SYOKUSYU_CD " . "\r\n";
        $strSql .= " AND        SKSNM.ID = 'SYOKUSYU' " . "\r\n";
        $strSql .= " LEFT JOIN  (SELECT MSKK.SYAIN_NO " . "\r\n";
        $strSql .= "                  , MSKK.SHIKAKU_CD " . "\r\n";
        $strSql .= "             FROM   (SELECT   WK.SYAIN_NO " . "\r\n";
        $strSql .= "                            , ROW_NUMBER() OVER(ORDER BY SYAIN_NO, SUM(MONTHS_BETWEEN(WK.ET, WK.ST)) desc, MAX(WK.ET) desc) - RANK() OVER(ORDER BY SYAIN_NO) RNK " . "\r\n";
        $strSql .= "                            , SUM(MONTHS_BETWEEN(WK.ET, WK.ST)) " . "\r\n";
        $strSql .= "                            , WK.SHIKAKU_CD " . "\r\n";
        $strSql .= "                      FROM    (SELECT V.SYAIN_NO " . "\r\n";
        $strSql .= "                                    , (CASE WHEN V.STARTDT < TO_DATE('@HYOUKA_KIKAN_START', 'YYYY/MM/DD') THEN TO_DATE('@HYOUKA_KIKAN_START', 'YYYY/MM/DD') ELSE V.STARTDT END) ST " . "\r\n";
        $strSql .= "                                    , (CASE WHEN NVL(V.ENDDT,TO_DATE('9999/12/31', 'YYYY/MM/DD')) > TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') THEN TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') ELSE V.ENDDT END) ET " . "\r\n";
        $strSql .= "                                    , V.SHIKAKU_CD " . "\r\n";
        $strSql .= "                               FROM   (SELECT HAI.SYAIN_NO " . "\r\n";
        $strSql .= "                                            , HAI.SHIKAKU_CD " . "\r\n";
        $strSql .= "                                            , HAI.ANNOUNCE_DT STARTDT " . "\r\n";
        $strSql .= "                                            , LEAD((HAI.ANNOUNCE_DT-1)) OVER(PARTITION BY HAI.SYAIN_NO ORDER BY HAI.SYAIN_NO, HAI.ANNOUNCE_DT) ENDDT " . "\r\n";
        $strSql .= "                                       FROM   JKIDOURIREKI HAI " . "\r\n";
        $strSql .= "                                      ) V " . "\r\n";
        $strSql .= "                               WHERE  NVL(V.ENDDT,TO_DATE('9999/12/31', 'YYYY/MM/DD')) >= TO_DATE('@HYOUKA_KIKAN_START', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= "                               AND    V.STARTDT <= TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= "                             ) WK " . "\r\n";
        $strSql .= "                     GROUP BY WK.SYAIN_NO, WK.SHIKAKU_CD " . "\r\n";
        $strSql .= "                    ) MSKK " . "\r\n";
        $strSql .= "         WHERE(MSKK.RNK = 0) " . "\r\n";
        $strSql .= "            ) SKK " . "\r\n";
        $strSql .= " ON         SKK.SYAIN_NO = HYOKA.SYAIN_NO " . "\r\n";
        $strSql .= " LEFT JOIN  JKCODEMST SKKNM " . "\r\n";
        $strSql .= " ON         SKKNM.CODE = SKK.SHIKAKU_CD " . "\r\n";
        $strSql .= " AND        SKKNM.ID = 'SHIKAKU' " . "\r\n";
        $strSql .= " LEFT JOIN  JKKOYOURIREKI KOY " . "\r\n";
        $strSql .= " ON         HYOKA.SYAIN_NO = KOY.SYAIN_NO " . "\r\n";
        $strSql .= " AND        KOY.BEF_NYUSYA_DT <= TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= " AND        NVL(KOY.BEF_TAISYOKU_DT, TO_DATE('9999/12/31', 'YYYY/MM/DD')) >= TO_DATE('@HYOUKA_KIKAN_END', 'YYYY/MM/DD') " . "\r\n";
        $strSql .= " LEFT JOIN  JKKUBUNMST BKYO " . "\r\n";
        $strSql .= " ON         BKYO.KUBUN_CD = KOY.BEF_KOYOU_KB_CD " . "\r\n";
        $strSql .= " AND        BKYO.KUBUN_ID = 'KOYOU' " . "\r\n";
        $strSql .= " WHERE      HYOKA.JISSHI_YM = '@JISSHI_YM' " . "\r\n";
        $strSql .= " ORDER BY   HYOKA.SYAIN_NO " . "\r\n";
        $strSql = str_replace("@HYOUKA_KIKAN_START", $_hyuokaTaisyouKikanSD, $strSql);
        $strSql = str_replace("@HYOUKA_KIKAN_END", $_hyuokaTaisyouKikanED, $strSql);
        $strSql = str_replace("@JISSHI_YM", substr($cmbYM, 0, 4) . substr($cmbYM, 5, 2), $strSql);
        return $strSql;
    }

    //定期昇給月を取得SQL
    public function fncGetTeikiSyokyuMonthSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT" . " \r\n";
        $strSQL .= "    KAKI_BONUS_MONTH" . " \r\n";
        $strSQL .= " FROM" . " \r\n";
        $strSQL .= "    JKCONTROLMST" . " \r\n";
        $strSQL .= " WHERE" . " \r\n";
        $strSQL .= "    ID = '02'" . " \r\n";
        return $strSQL;
    }

    //評価取込履歴データの取得を行う
    public function fncHyoukaTrkRireki($_strTeikiSyokyuMonth)
    {
        $strSQL = $this->fncHyoukaTrkRirekiSQL($_strTeikiSyokyuMonth);
        return parent::select($strSQL);
    }

    //評価取込履歴データ年月の取得を行う
    public function fncHyoukaTrkRirekiKikan($cmbYM)
    {
        $strSQL = $this->fncHyoukaTrkRirekiKikanSQL($cmbYM);
        return parent::select($strSQL);
    }

    //評価履歴データ取得
    public function fncHyoukaRireki($cmbYM)
    {
        $strSQL = $this->fncHyoukaRirekiSQL($cmbYM);
        return parent::select($strSQL);
    }

    //CSV出力データの取得を行う
    public function fncCsvOutputData($_hyuokaTaisyouKikanSD, $_hyuokaTaisyouKikanED, $cmbYM)
    {
        $strSql = $this->fncCsvOutputDataSQL($_hyuokaTaisyouKikanSD, $_hyuokaTaisyouKikanED, $cmbYM);
        return parent::select($strSql);
    }

    //定期昇給月を取得
    public function fncGetTeikiSyokyuMonth()
    {
        $strSQL = $this->fncGetTeikiSyokyuMonthSQL();
        return parent::select($strSQL);
    }

}
