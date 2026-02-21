<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                         担当
 * YYYYMMDD           #ID                       XXXXXX                                       FCSDL
 * 20240311           機能変更　　　監査実績入力で○、×が入力されたら即 実績集計に反映             lujunxia
 * 20240312           機能変更　　実績集計の一覧ソート順を ROW_NO から CHECK_LST_ID 順に変更     lujunxia
 * 20241030           202410_内部統制システム_集計機能改善対応.xlsx                             caina
 * 20250403           機能変更               202504_内部統制_要望.xlsx                        lujunxia
 * --------------------------------------------------------------------------------------------------
 */
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

class HMAUDJissekiPDFOutput extends ClsComDb
{
    //検索条件・クールには 現在のクール数を初期表示
    public function getInitializeCour()
    {
        $strSQL = "";
        $strSQL .= "SELECT COURS,TO_CHAR(START_DT,'YYYY/MM/DD')||' ～ '||TO_CHAR(END_DT,'YYYY/MM/DD') AS PERIOD," . "\r\n";
        $strSQL .= "  CASE" . "\r\n";
        $strSQL .= "    WHEN SYSDATE BETWEEN START_DT AND END_DT" . "\r\n";
        $strSQL .= "    THEN 1" . "\r\n";
        $strSQL .= "    ELSE 0" . "\r\n";
        $strSQL .= "  END AS COURS_NOW" . "\r\n";
        $strSQL .= "FROM HMAUD_MST_COUR" . "\r\n";
        //20241030 caina ins s
        $strSQL .= "WHERE COURS > 7" . "\r\n";// 集計画面のクールプルダウンリストは 8から選択させるようにしたいです。7は非表示
        //20241030 caina ins e
        $strSQL .= "ORDER BY START_DT DESC" . "\r\n";
        return parent::select($strSQL);
    }

    //20230314 LIU INS S
    public function getViewer()
    {
        $strSQL = "";
        $strSQL .= " SELECT SYAIN_NO " . "\r\n";
        $strSQL .= " FROM HMAUD_MST_VIEWER " . "\r\n";
        $strSQL .= " WHERE HMAUD_MST_VIEWER.SYAIN_NO   = '@SYAIN_NO' " . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);

        return parent::select($strSQL);
    }

    //20230314 LIU INS E

    public function getmember($cour)
    {
        $strSQL = "";
        $strSQL .= " SELECT MEMBER " . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_MEMBER " . "\r\n";
        $strSQL .= " INNER JOIN HMAUD_AUDIT_MAIN " . "\r\n";
        $strSQL .= " ON HMAUD_AUDIT_MEMBER.CHECK_ID = HMAUD_AUDIT_MAIN.CHECK_ID " . "\r\n";
        $strSQL .= " WHERE HMAUD_AUDIT_MAIN.COURS   = '@COUR' " . "\r\n";
        // 20250403 lujunxia upd s
        //$strSQL .= " AND HMAUD_AUDIT_MEMBER.ROLE   IN (1,4,5,6,7,8) " . "\r\n";
        // 副社長を追加
        $strSQL .= " AND HMAUD_AUDIT_MEMBER.ROLE   IN (1,4,5,6,7,8,9) " . "\r\n";
        // 20250403 lujunxia upd e
        $strSQL .= " AND HMAUD_AUDIT_MEMBER.MEMBER  = '@MEMBER' " . "\r\n";

        $strSQL = str_replace("@COUR", $cour, $strSQL);
        $strSQL = str_replace("@MEMBER", $this->GS_LOGINUSER['strUserID'], $strSQL);

        return parent::select($strSQL);
    }

    //監査スケジュールに登録されていないが 管理者マスタに登録済のユーザが ボタンを操作できるようにしてほしい
    public function getManager()
    {
        $strSQL = "";
        $strSQL .= " SELECT SYAIN_NO AS MEMBER" . "\r\n";
        $strSQL .= " FROM HMAUD_MST_ADMIN " . "\r\n";
        $strSQL .= " WHERE SYAIN_NO  = '@MEMBER' " . "\r\n";
        $strSQL = str_replace("@MEMBER", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::select($strSQL);
    }

    //指摘回数のカウントは 累積ではなく 直近３クールで見ること
    function chkrowno($params)
    {
        $strSQL = "";
        $strSQL .= " SELECT count(*) AS COUNT" . "\r\n";
        $strSQL .= "  FROM HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= "  INNER JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "  ON HAM.CHECK_ID   =HAR.CHECK_ID" . "\r\n";
        //20240311 lujunxia del s
        // $strSQL .= "  INNER JOIN HMAUD_AUDIT_REPORT_HEAD HARH" . "\r\n";
        // $strSQL .= "  ON HARH.CHECK_ID   =HAR.CHECK_ID" . "\r\n";
        //20240311 lujunxia del e
        $strSQL .= "  LEFT JOIN" . "\r\n";
        $strSQL .= "    (SELECT HAR.CHECK_LST_ID" . "\r\n";
        $strSQL .= "    FROM HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= "    INNER JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "    ON HAM.CHECK_ID   =HAR.CHECK_ID" . "\r\n";
        //20240311 lujunxia del s
        // $strSQL .= "    INNER JOIN HMAUD_AUDIT_REPORT_HEAD HARH" . "\r\n";
        // $strSQL .= "    ON HARH.CHECK_ID   =HAR.CHECK_ID" . "\r\n";
        //20240311 lujunxia del e
        $strSQL .= "    WHERE TO_NUMBER(HAM.COURS)   <=@COURS" . "\r\n";
        //20241030 caina upd s
        // $strSQL .= "    AND TO_NUMBER(HAM.COURS)   > @COURS - 3" . "\r\n";
        $strSQL .= "    AND TO_NUMBER(HAM.COURS)   > @COURS - 6" . "\r\n";
        $strSQL .= "    AND TO_NUMBER(HAM.COURS)  > 7" . "\r\n";
        //20241030 caina upd e
        $strSQL .= "    AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= "    AND HAM.KYOTEN_CD = '@KYOTEN_CD'" . "\r\n";
        $strSQL .= "    AND HAR.CHECK_LST_ID = '@ROW_NO'" . "\r\n";
        //20240311 lujunxia del s
        //$strSQL .= "    AND TO_NUMBER(HARH.STATUS) > 3" . "\r\n";
        //20240311 lujunxia del e
        $strSQL .= "    AND HAR.RESULT='2'" . "\r\n";
        $strSQL .= "   ) HARO ON HAR.CHECK_LST_ID  = HARO.CHECK_LST_ID" . "\r\n";
        $strSQL .= "  WHERE TO_NUMBER(HAM.COURS)   =@COURS" . "\r\n";
        $strSQL .= "  AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= "  AND HAM.KYOTEN_CD = '@KYOTEN_CD'" . "\r\n";
        $strSQL .= "  AND HAR.CHECK_LST_ID = '@ROW_NO'" . "\r\n";
        //20240311 lujunxia del s
        //$strSQL .= "  AND TO_NUMBER(HARH.STATUS) > 3" . "\r\n";
        //20240311 lujunxia del e
        $strSQL .= "  AND HAR.RESULT='2'" . "\r\n";
        $strSQL = str_replace("@COURS", $params['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $params['TERRITORY'], $strSQL);
        $strSQL = str_replace("@KYOTEN_CD", $params['KYOTEN_CD'], $strSQL);
        $strSQL = str_replace("@ROW_NO", $params['ROW_NO'], $strSQL);

        return parent::select($strSQL);

    }
    //20241030 caina ins s
    //指摘回数のカウントは 連続ではなく 直近6クールで見ること
    function chkrownoContinuity($params)
    {
        $strSQL = "";
        $strSQL .= " SELECT HAR.CHECK_LST_ID," . "\r\n";
        $strSQL .= "    MEN.MEMBER," . "\r\n";
        $strSQL .= "    COURS " . "\r\n";
        $strSQL .= "    FROM HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= "    INNER JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "    ON HAM.CHECK_ID   =HAR.CHECK_ID" . "\r\n";
        $strSQL .= "    LEFT JOIN HMAUD_AUDIT_MEMBER MEN" . "\r\n";
        $strSQL .= "    ON MEN.CHECK_ID = HAR.CHECK_ID" . "\r\n";
        $strSQL .= "    AND MEN.ROLE = '3' " . "\r\n";
        $strSQL .= "    WHERE TO_NUMBER(HAM.COURS)   <=@COURS" . "\r\n";
        $strSQL .= "    AND TO_NUMBER(HAM.COURS)   > @COURS - 6" . "\r\n";
        $strSQL .= "    AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= "    AND HAM.KYOTEN_CD = '@KYOTEN_CD'" . "\r\n";
        $strSQL .= "    AND HAR.CHECK_LST_ID = '@ROW_NO'" . "\r\n";
        $strSQL .= "    AND HAR.RESULT='2'" . "\r\n";
        $strSQL .= "    ORDER BY COURS DESC" . "\r\n";
        $strSQL = str_replace("@COURS", $params['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $params['TERRITORY'], $strSQL);
        $strSQL = str_replace("@KYOTEN_CD", $params['KYOTEN_CD'], $strSQL);
        $strSQL = str_replace("@ROW_NO", $params['ROW_NO'], $strSQL);

        return parent::select($strSQL);
    }
    //20241030 caina ins e

    function getDetail($TERRITORY, $COURS)
    {
        $strSQL = "";
        $strSQL .= " SELECT ROW_NO," . "\r\n";
        $strSQL .= "   HMAUD_AUDIT_DETAIL.COURS," . "\r\n";
        $strSQL .= "   TERRITORY," . "\r\n";
        $strSQL .= "   CHECK_LST_ID," . "\r\n";
        $strSQL .= "   COLUMN1," . "\r\n";
        $strSQL .= "   COLUMN2," . "\r\n";
        $strSQL .= "   COLUMN4," . "\r\n";
        $strSQL .= "   COLUMN7" . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_DETAIL" . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_MST_COUR HCOUR" . "\r\n";
        $strSQL .= " ON HMAUD_AUDIT_DETAIL.COURS=HCOUR.COURS" . "\r\n";
        $strSQL .= " WHERE TERRITORY ='@TERRITORY'" . "\r\n";
        $strSQL .= " AND HMAUD_AUDIT_DETAIL.COURS ='@COURS'" . "\r\n";
        $strSQL .= " AND (HMAUD_AUDIT_DETAIL.EXPIRATION_DATE >= HCOUR.START_DT" . "\r\n";
        $strSQL .= " OR HMAUD_AUDIT_DETAIL.EXPIRATION_DATE IS NULL)" . "\r\n";
        //20240312 lujunxia upd s
        //$strSQL .= " ORDER BY  TO_NUMBER(REPLACE(ROW_NO,'追加','9999'))" . "\r\n";
        $strSQL .= " ORDER BY CHECK_LST_ID" . "\r\n";
        //20240312 lujunxia upd e
        $strSQL = str_replace("@TERRITORY", $TERRITORY, $strSQL);
        $strSQL = str_replace("@COURS", $COURS, $strSQL);

        return parent::select($strSQL);
    }

    function getTitleKyoten($TERRITORY)
    {
        $strSQL = "";
        $strSQL .= " SELECT KYOTEN_CD," . "\r\n";
        $strSQL .= "   KYOTEN_NAME," . "\r\n";
        $strSQL .= "   TERRITORY" . "\r\n";
        $strSQL .= " FROM HMAUD_MST_KTN" . "\r\n";
        $strSQL .= " WHERE HMAUD_MST_KTN.TARGET= 1" . "\r\n";
        $strSQL .= " AND TERRITORY ='@TERRITORY'" . "\r\n";
        $strSQL = str_replace("@TERRITORY", $TERRITORY, $strSQL);

        return parent::select($strSQL);
    }
    // 20241030 caina ins s
    function getKyotenCountArr($TERRITORY, $COURS, $summery)
    {
        $strSQL = "";
        $strSQL .= " SELECT" . "\r\n";
        $strSQL .= "   RANKED.CHECK_LST_ID," . "\r\n";
        $strSQL .= "   RANKED.KYOTEN_COUNT," . "\r\n";
        $strSQL .= "   HAD.COLUMN7," . "\r\n";
        $strSQL .= "   RANKED.TERRITORY" . "\r\n";

        $strSQL .= " FROM (" . "\r\n";
        $strSQL .= "  SELECT CHECK_LST_ID," . "\r\n";
        $strSQL .= "   COUNT(DISTINCT KYOTEN_CD) AS KYOTEN_COUNT," . "\r\n";
        $strSQL .= "   TERRITORY," . "\r\n";
        $strSQL .= "   DENSE_RANK() OVER (ORDER BY COUNT(DISTINCT KYOTEN_CD) DESC) AS RANK_NUM" . "\r\n";
        $strSQL .= "    FROM (" . "\r\n";
        $strSQL .= "   SELECT HAM.KYOTEN_CD," . "\r\n";
        $strSQL .= "          HAR.CHECK_LST_ID," . "\r\n";
        if ($summery === 'cumulative_multiple_issue_ranking_per_territory') {
            $strSQL .= "          COUNT(*) AS COUNT_CHECK_ID," . "\r\n";
        }
        $strSQL .= "          HAM.TERRITORY" . "\r\n";

        $strSQL .= "          FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "          LEFT JOIN HMAUD_AUDIT_RESULT HAR ON HAM.CHECK_ID = HAR.CHECK_ID" . "\r\n";
        $strSQL .= "          LEFT JOIN HMAUD_AUDIT_DETAIL HAD ON HAR.CHECK_LST_ID = HAD.ROW_NO" . "\r\n";
        $strSQL .= "          AND HAD.COURS = HAM.COURS" . "\r\n";
        $strSQL .= "          AND HAD.TERRITORY = HAM.TERRITORY" . "\r\n";
        $strSQL .= "    WHERE " . "\r\n";
        if ($summery === 'issue_ranking_per_territory') {
            $strSQL .= "    HAM.COURS = '@COURS'" . "\r\n";
        } else {
            $strSQL .= "    TO_NUMBER(HAM.COURS) <= @COURS" . "\r\n";
            $strSQL .= "    AND TO_NUMBER(HAM.COURS) > @COURS - 6" . "\r\n";
            $strSQL .= "    AND HAR.CHECK_LST_ID IN" . "\r\n";
            $strSQL .= "    (SELECT DISTINCT CHECK_LST_ID FROM HMAUD_AUDIT_MAIN HAM2" . "\r\n";
            $strSQL .= "        LEFT JOIN HMAUD_AUDIT_RESULT HAR2 ON HAM2.CHECK_ID = HAR2.CHECK_ID" . "\r\n";
            $strSQL .= "        WHERE HAM2.COURS = '@COURS'" . "\r\n";
            $strSQL .= "        AND HAM2.TERRITORY = '@TERRITORY'" . "\r\n";
            $strSQL .= "        AND HAR2.RESULT = '2'" . "\r\n";
            $strSQL .= "        AND HAR2.CHECK_LST_ID = HAR.CHECK_LST_ID" . "\r\n";
            $strSQL .= "        AND HAM2.KYOTEN_CD    = HAM.KYOTEN_CD)" . "\r\n";
        }
        $strSQL .= "    AND HAR.RESULT = '2'" . "\r\n";
        $strSQL .= "    AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= "    AND TO_NUMBER(HAM.COURS) > 7" . "\r\n";

        $strSQL .= " GROUP BY HAM.KYOTEN_CD, HAR.CHECK_LST_ID, HAM.TERRITORY" . "\r\n";
        if ($summery === 'cumulative_multiple_issue_ranking_per_territory') {
            $strSQL .= " HAVING  COUNT(*) >= 2" . "\r\n";
        }
        $strSQL .= " ) SUB" . "\r\n";
        $strSQL .= " GROUP BY CHECK_LST_ID, TERRITORY" . "\r\n";
        $strSQL .= " )RANKED" . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_AUDIT_DETAIL HAD ON RANKED.CHECK_LST_ID = HAD.ROW_NO" . "\r\n";
        $strSQL .= " AND HAD.TERRITORY = RANKED.TERRITORY" . "\r\n";
        $strSQL .= " AND HAD.COURS = '@COURS'" . "\r\n";
        $strSQL .= " WHERE RANKED.RANK_NUM <= 3" . "\r\n";
        $strSQL .= " ORDER BY RANKED.KYOTEN_COUNT DESC, RANKED.CHECK_LST_ID" . "\r\n";

        $strSQL = str_replace("@COURS", $COURS, $strSQL);
        $strSQL = str_replace("@TERRITORY", $TERRITORY, $strSQL);

        return parent::select($strSQL);
    }

    function getpreKyoten($CHECKLSTID, $TERRITORY, $postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT COUNT(*) AS COUNT" . "\r\n";
        if ($postData['SUMMERY'] === 'cumulative_multiple_issue_ranking_per_territory') {
            $strSQL .= "  FROM (" . "\r\n";
            $strSQL .= "  SELECT HAM.KYOTEN_CD," . "\r\n";
            $strSQL .= "         COUNT(*) AS COUNT" . "\r\n";
        }
        $strSQL .= " FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "     LEFT JOIN HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= "     ON HAM.CHECK_ID = HAR.CHECK_ID" . "\r\n";
        $strSQL .= " WHERE HAM.TERRITORY ='@TERRITORY'" . "\r\n";
        if ($postData['SUMMERY'] === 'issue_ranking_per_territory') {
            $strSQL .= "   AND HAM.COURS = @COURS - 1" . "\r\n";
            $strSQL .= "   AND TO_NUMBER(HAM.COURS) > 7" . "\r\n";
        } else {
            $strSQL .= "   AND TO_NUMBER(HAM.COURS) <= @COURS - 1" . "\r\n";
            $strSQL .= "   AND TO_NUMBER(HAM.COURS) > @COURS - 7" . "\r\n";
            $strSQL .= "   AND TO_NUMBER(HAM.COURS) > 7" . "\r\n";
        }
        $strSQL .= "   AND HAR.CHECK_LST_ID ='@CHECKLSTID'" . "\r\n";
        $strSQL .= "   AND HAR.RESULT = '2'" . "\r\n";
        if ($postData['SUMMERY'] === 'cumulative_multiple_issue_ranking_per_territory') {
            $strSQL .= "   GROUP BY HAM.KYOTEN_CD" . "\r\n";
            $strSQL .= "   HAVING COUNT(*) >= 2" . "\r\n";
            $strSQL .= "  ) RESULT" . "\r\n";
        }
        $strSQL = str_replace("@COURS", $postData['COUR'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $TERRITORY, $strSQL);
        $strSQL = str_replace("@CHECKLSTID", $CHECKLSTID, $strSQL);

        return parent::select($strSQL);
    }

    function getIndicationCountArr($TERRITORY, $COURS)
    {
        $strSQL = "";
        $strSQL .= " SELECT COUNT(HAM.KYOTEN_CD) AS CHECK_COUNT," . "\r\n";
        $strSQL .= " HAM.TERRITORY," . "\r\n";
        $strSQL .= " HMT.KYOTEN_CD," . "\r\n";
        $strSQL .= " HMT.KYOTEN_NAME" . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "  LEFT JOIN HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= "   ON HAM.CHECK_ID = HAR.CHECK_ID" . "\r\n";
        $strSQL .= "  LEFT JOIN HMAUD_MST_KTN HMT" . "\r\n";
        $strSQL .= "   ON HMT.KYOTEN_CD = HAM.KYOTEN_CD" . "\r\n";
        $strSQL .= "   AND HMT.TERRITORY = HAM.TERRITORY" . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= " HAM.COURS = @COURS" . "\r\n";
        $strSQL .= " AND HAR.RESULT = '2'" . "\r\n";
        $strSQL .= " AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= " GROUP BY" . "\r\n";
        $strSQL .= " HAM.TERRITORY, HMT.KYOTEN_CD, HMT.KYOTEN_NAME" . "\r\n";
        $strSQL .= " ORDER BY CHECK_COUNT DESC" . "\r\n";

        $strSQL = str_replace("@COURS", $COURS, $strSQL);
        $strSQL = str_replace("@TERRITORY", $TERRITORY, $strSQL);

        return parent::select($strSQL);
    }

    function getPreIndicationArr($KYOTENCD, $TERRITORY, $COURS, $summery)
    {
        $strSQL = "";
        if ($summery === 'issue_ranking') {
            $strSQL .= " SELECT COUNT(*) AS PRECOUNT" . "\r\n";
        } else {
            $strSQL .= " SELECT COUNT(SUBHAM.KYOTEN_CD) AS PRECOUNT FROM" . "\r\n";
            $strSQL .= " (SELECT HAM.KYOTEN_CD, HAR.CHECK_LST_ID,  HAM.TERRITORY, COUNT(HAR.CHECK_LST_ID) AS CHECK_LST_COUNT" . "\r\n";
        }
        $strSQL .= " FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "  LEFT JOIN HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= "   ON HAM.CHECK_ID = HAR.CHECK_ID" . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        if ($summery === 'issue_ranking') {
            $strSQL .= " HAM.COURS = @COURS" . "\r\n";
        } else {
            $strSQL .= " HAR.CHECK_LST_ID IN (SELECT CHECK_LST_ID FROM  HMAUD_AUDIT_MAIN HAM" . "\r\n";
            $strSQL .= " LEFT JOIN HMAUD_AUDIT_RESULT HAR ON HAM.CHECK_ID = HAR.CHECK_ID" . "\r\n";
            $strSQL .= " WHERE HAM.COURS = @COURS + 1" . "\r\n";
            $strSQL .= " AND HAR.RESULT = '2'" . "\r\n";
            $strSQL .= " AND HAM.KYOTEN_CD = '@KYOTENCD'" . "\r\n";
            $strSQL .= " AND HAM.TERRITORY = '@TERRITORY')" . "\r\n";
            $strSQL .= " AND TO_NUMBER(HAM.COURS) <= @COURS" . "\r\n";
            $strSQL .= " AND TO_NUMBER(HAM.COURS) > @COURS - 6" . "\r\n";
            $strSQL .= " AND TO_NUMBER(HAM.COURS) > 7" . "\r\n";
        }
        $strSQL .= " AND HAR.RESULT = '2'" . "\r\n";
        $strSQL .= " AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= " AND HAM.KYOTEN_CD = '@KYOTENCD'" . "\r\n";
        if ($summery === 'cumulative_multiple_issue_ranking') {
            $strSQL .= " GROUP BY HAM.KYOTEN_CD, HAR.CHECK_LST_ID, HAM.TERRITORY" . "\r\n";
            $strSQL .= " HAVING COUNT(HAR.CHECK_LST_ID) >=2) SUBHAM" . "\r\n";
        }
        $strSQL = str_replace("@COURS", $COURS - 1, $strSQL);
        $strSQL = str_replace("@TERRITORY", $TERRITORY, $strSQL);
        $strSQL = str_replace("@KYOTENCD", $KYOTENCD ?? '', $strSQL);

        return parent::select($strSQL);
    }

    function getMulIndicationCount($TERRITORY, $COURS)
    {
        $strSQL = "";
        $strSQL .= " SELECT COUNT(SUBHAM.KYOTEN_CD) AS CHECK_COUNT," . "\r\n";
        $strSQL .= " SUBHAM.KYOTEN_CD," . "\r\n";
        $strSQL .= " SUBHAM.KYOTEN_NAME," . "\r\n";
        $strSQL .= " SUBHAM.TERRITORY" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " (SELECT HAM.KYOTEN_CD," . "\r\n";
        $strSQL .= " HMT.KYOTEN_NAME," . "\r\n";
        $strSQL .= " HAR.CHECK_LST_ID," . "\r\n";
        $strSQL .= " HAM.TERRITORY," . "\r\n";
        $strSQL .= " COUNT(HAR.CHECK_LST_ID) AS CHECK_LST_COUNT" . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "  LEFT JOIN HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= "   ON HAM.CHECK_ID = HAR.CHECK_ID" . "\r\n";
        $strSQL .= "  LEFT JOIN HMAUD_MST_KTN HMT" . "\r\n";
        $strSQL .= "   ON HMT.KYOTEN_CD = HAM.KYOTEN_CD AND HMT.TERRITORY = HAM.TERRITORY" . "\r\n";
        $strSQL .= " WHERE " . "\r\n";

        $strSQL .= " TO_NUMBER(HAM.COURS) <= '@COURS'" . "\r\n";
        $strSQL .= " AND TO_NUMBER(HAM.COURS) > '@COURS' - 6" . "\r\n";
        $strSQL .= " AND TO_NUMBER(HAM.COURS) > 7" . "\r\n";
        $strSQL .= " AND HAR.RESULT = '2'" . "\r\n";
        $strSQL .= " AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";

        $strSQL .= " AND EXISTS (" . "\r\n";
        $strSQL .= " SELECT 1 FROM HMAUD_AUDIT_MAIN HAM22" . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_AUDIT_RESULT HAR22 ON HAM22.CHECK_ID = HAR22.CHECK_ID" . "\r\n";
        $strSQL .= " WHERE HAM22.COURS = '@COURS'" . "\r\n";
        $strSQL .= " AND HAR22.RESULT = '2'" . "\r\n";
        $strSQL .= " AND HAM22.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= " AND HAR22.CHECK_LST_ID = HAR.CHECK_LST_ID" . "\r\n";
        $strSQL .= " AND HAM22.KYOTEN_CD = HAM.KYOTEN_CD)" . "\r\n";

        $strSQL .= " GROUP BY HAM.KYOTEN_CD, HMT.KYOTEN_NAME, HAR.CHECK_LST_ID, HAM.TERRITORY" . "\r\n";
        $strSQL .= " HAVING COUNT(HAR.CHECK_LST_ID) >= 2) SUBHAM" . "\r\n";
        $strSQL .= " GROUP BY SUBHAM.KYOTEN_CD, SUBHAM.KYOTEN_NAME, SUBHAM.TERRITORY" . "\r\n";
        $strSQL .= " ORDER BY CHECK_COUNT DESC" . "\r\n";

        $strSQL = str_replace("@COURS", $COURS, $strSQL);
        $strSQL = str_replace("@TERRITORY", $TERRITORY, $strSQL);

        return parent::select($strSQL);
    }

    function getContinueMulCount($TERRITORY, $COURS, $KYOTENCD, $summery)
    {
        $strSQL = "";
        $strSQL .= " SELECT COUNT(DISTINCT HAR.CHECK_LST_ID) AS CHECK_COUNT," . "\r\n";
        $strSQL .= " HAM.KYOTEN_CD," . "\r\n";
        $strSQL .= " HMK.KYOTEN_NAME," . "\r\n";
        $strSQL .= " HAM.TERRITORY" . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= " INNER JOIN HMAUD_AUDIT_MAIN HAM ON HAM.CHECK_ID = HAR.CHECK_ID" . "\r\n";
        $strSQL .= " INNER JOIN HMAUD_MST_KTN HMK ON HAM.KYOTEN_CD = HMK.KYOTEN_CD" . "\r\n";
        $strSQL .= " INNER JOIN HMAUD_AUDIT_MEMBER HAUM ON HAUM.CHECK_ID = HAM.CHECK_ID" . "\r\n";
        $strSQL .= " INNER JOIN HMAUD_AUDIT_RESULT HARPRE ON HARPRE.CHECK_LST_ID = HAR.CHECK_LST_ID" . "\r\n";
        $strSQL .= " INNER JOIN HMAUD_AUDIT_MAIN HAMPRE ON HAMPRE.CHECK_ID = HARPRE.CHECK_ID" . "\r\n";
        $strSQL .= " INNER JOIN HMAUD_AUDIT_MEMBER HAUMPRE ON HAUMPRE.CHECK_ID = HAMPRE.CHECK_ID" . "\r\n";
        $strSQL .= "  WHERE HAR.RESULT = '2'" . "\r\n";
        $strSQL .= " AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= " AND HAM.COURS = '@COURS'" . "\r\n";
        $strSQL .= " AND HAUM.ROLE = '3'" . "\r\n";
        $strSQL .= " AND HARPRE.RESULT = '2'" . "\r\n";
        $strSQL .= " AND HAMPRE.COURS = @COURS - 1" . "\r\n";
        $strSQL .= " AND HAUMPRE.ROLE = '3'" . "\r\n";
        $strSQL .= " AND HAMPRE.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= " AND HAUM.MEMBER = HAUMPRE.MEMBER" . "\r\n";
        $strSQL .= " AND HAM.KYOTEN_CD = HAMPRE.KYOTEN_CD" . "\r\n";
        $strSQL .= " AND EXISTS (" . "\r\n";
        $strSQL .= " SELECT 1 FROM HMAUD_AUDIT_MAIN HAMPRE2" . "\r\n";
        $strSQL .= " INNER JOIN HMAUD_AUDIT_RESULT HARPRE2 ON HARPRE2.CHECK_ID = HAMPRE2.CHECK_ID" . "\r\n";
        $strSQL .= " WHERE HAMPRE2.COURS = @COURS - 1" . "\r\n";
        $strSQL .= " AND HAMPRE2.KYOTEN_CD = HAM.KYOTEN_CD" . "\r\n";
        $strSQL .= " AND HARPRE2.RESULT = '2'" . "\r\n";
        $strSQL .= " AND HARPRE2.CHECK_LST_ID = HAR.CHECK_LST_ID)" . "\r\n";
        if ($summery === 'consecutive_multiple_issue_ranking') {
            $strSQL .= " AND HAM.KYOTEN_CD = '@KYOTENCD'" . "\r\n";
        }
        $strSQL .= " GROUP BY HAM.KYOTEN_CD, HMK.KYOTEN_NAME, HAM.TERRITORY" . "\r\n";
        $strSQL .= " ORDER BY CHECK_COUNT DESC" . "\r\n";

        if ($summery === 'consecutive_multiple_issue_ranking') {
            $strSQL = str_replace("@COURS", $COURS - 1, $strSQL);
        } else {
            $strSQL = str_replace("@COURS", $COURS, $strSQL);
        }
        $strSQL = str_replace("@TERRITORY", $TERRITORY, $strSQL);
        $strSQL = str_replace("@KYOTENCD", $KYOTENCD, $strSQL);

        return parent::select($strSQL);
    }
    // 20241030 caina ins e
}
