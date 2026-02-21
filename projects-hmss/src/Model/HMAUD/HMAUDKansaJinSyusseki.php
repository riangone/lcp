<?php
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDKansaJinSyusseki extends ClsComDb
{
    public function selLimit($cours, $params)
    {
        return parent::select($this->selLimitSQL($cours, $params));
    }
    public function getCours($ymDate)
    {
        return parent::select($this->getCoursSQL($ymDate));
    }
    public function delLimit($cours, $params)
    {
        return parent::delete($this->delLimitSQL($cours, $params));
    }
    public function updLimit($cours, $params)
    {
        return parent::update($this->updLimitSQL($cours, $params));
    }
    public function insLimit($cours, $params)
    {
        return parent::insert($this->insLimitSQL($cours, $params));
    }
    public function getSchedule($ymDate)
    {
        return parent::select($this->getScheduleSQL($ymDate));
    }
    public function getExcludeDt($ymDate)
    {
        return parent::select($this->getExcludeDtSQL($ymDate));
    }
    public function selAuditSchedule($cours, $params)
    {
        return parent::select($this->selAuditScheduleSQL($cours, $params));
    }
    public function delAuditSchedule($cours, $params)
    {
        return parent::delete($this->delAuditScheduleSQL($cours, $params));
    }
    public function updAuditSchedule($cours, $params)
    {
        return parent::update($this->updAuditScheduleSQL($cours, $params));
    }
    public function insAuditSchedule($cours, $params)
    {
        return parent::insert($this->insAuditScheduleSQL($cours, $params));
    }
    public function getAdmin()
    {
        $strSQL = "";
        $strSQL .= " SELECT SYAIN_NO " . "\r\n";
        $strSQL .= " FROM HMAUD_MST_ADMIN " . "\r\n";
        $strSQL .= " WHERE HMAUD_MST_ADMIN.SYAIN_NO   = '@SYAIN_NO' " . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);

        return parent::select($strSQL);
    }
    public function selLimitSQL($cours, $params)
    {
        $strSQL = "";
        $strSQL .= "SELECT * " . "\r\n";
        $strSQL .= "    FROM HMAUD_AUDITOR_SCHEDULE_LIMIT HASL" . "\r\n";
        $strSQL .= "WHERE HASL.COURS = @COURS" . "\r\n";
        $strSQL .= "AND HASL.SYAIN_NO = '@SYAIN_NO'" . "\r\n";
        $strSQL .= "AND TO_CHAR(HASL.PLAN_DT,'YYYYMM') = '@PLAN_DT'" . "\r\n";

        $strSQL = str_replace("@COURS", $cours, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $params['syainNo'], $strSQL);
        $strSQL = str_replace("@PLAN_DT", $params['ymDate'], $strSQL);

        return $strSQL;
    }


    public function getCoursSQL($ymDate)
    {
        $ymDateFull = $ymDate . "01";

        $strSql = "";
        $strSql .= " SELECT COURS " . "\r\n";
        $strSql .= "   FROM HMAUD_MST_COUR " . "\r\n";
        $strSql .= "  WHERE START_DT <= TO_DATE('@YM_DATE', 'YYYYMMDD') " . "\r\n";
        $strSql .= "    AND END_DT   >= TO_DATE('@YM_DATE', 'YYYYMMDD') " . "\r\n";

        $strSql = str_replace("@YM_DATE", $ymDateFull, $strSql);

        return $strSql;
    }


    //検索条件・クールには 現在のクール数を初期表示
    // function getInitializeCour()
    // {
    //     $strSQL = "";
    //     $strSQL .= "SELECT COURS,TO_CHAR(START_DT,'YYYY/MM/DD')||' ～ '||TO_CHAR(END_DT,'YYYY/MM/DD') AS PERIOD," . "\r\n";
    //     $strSQL .= "  CASE" . "\r\n";
    //     $strSQL .= "    WHEN SYSDATE BETWEEN START_DT AND END_DT" . "\r\n";
    //     $strSQL .= "    THEN 1" . "\r\n";
    //     $strSQL .= "    ELSE 0" . "\r\n";
    //     $strSQL .= "  END AS COURS_NOW" . "\r\n";
    //     $strSQL .= "FROM HMAUD_MST_COUR" . "\r\n";
    //     $strSQL .= "ORDER BY START_DT DESC" . "\r\n";
    //     return parent::select($strSQL);
    // }
    public function getScheduleSQL($ymDate)
    {
        $strSQL = "";
        $strSQL .= "SELECT A.SYAIN_NO" . "\r\n";
        $strSQL .= "     , M.SYAIN_KNJ_SEI || '　' || M.SYAIN_KNJ_MEI AS SYAIN_NAME" . "\r\n";
        $strSQL .= "     , A.SEQ" . "\r\n";
        $strSQL .= "     , S.ENABLED" . "\r\n";
        $strSQL .= "     , TO_CHAR(S.PLAN_DT, 'YYYY/MM/DD') AS PLAN_DT" . "\r\n";
        $strSQL .= "     , CASE S.AMPM" . "\r\n";
        $strSQL .= "         WHEN '1' THEN 'AM'" . "\r\n";
        $strSQL .= "         WHEN '2' THEN 'PM'" . "\r\n";
        $strSQL .= "         ELSE NULL" . "\r\n";
        $strSQL .= "       END AS AM_PM" . "\r\n";
        $strSQL .= "     , TO_CHAR(L.PLAN_DT, 'YYYY/MM/DD') AS LIMIT_DT" . "\r\n";
        $strSQL .= "FROM HMAUD_MST_AUDITOR A" . "\r\n";
        $strSQL .= "LEFT JOIN M29MA4 M" . "\r\n";
        $strSQL .= "       ON A.SYAIN_NO = M.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HMAUD_AUDITOR_SCHEDULE S" . "\r\n";
        $strSQL .= "       ON A.SYAIN_NO = S.SYAIN_NO" . "\r\n";
        $strSQL .= "      AND TO_CHAR(S.PLAN_DT, 'YYYYMM') = '@YM_DATE'" . "\r\n";
        $strSQL .= "LEFT JOIN HMAUD_AUDITOR_SCHEDULE_LIMIT L" . "\r\n";
        $strSQL .= "       ON A.SYAIN_NO = L.SYAIN_NO" . "\r\n";
        $strSQL .= "      AND TO_CHAR(L.PLAN_DT, 'YYYYMM') = '@YM_DATE'" . "\r\n";
        $strSQL .= "WHERE A.ENABLED = '1'" . "\r\n";
        $strSQL .= "ORDER BY TO_NUMBER(A.SEQ), S.SYAIN_NO," . "\r\n";
        $strSQL .= "         CASE WHEN S.AMPM = '1' THEN 1 ELSE 2 END" . "\r\n";

        $strSQL = str_replace("@YM_DATE", $ymDate, $strSQL);

        return $strSQL;
    }

    public function getExcludeDtSQL($ymDate)
    {
        $strSQL = "";
        $strSQL .= "SELECT TO_CHAR(EXCLUDE_DT, 'YYYY/MM/DD') AS EXCLUDE_DT" . "\r\n";
        $strSQL .= "FROM HMAUD_MST_AUDIT_EXCLUDE_DATE" . "\r\n";
        $strSQL .= "WHERE TO_CHAR(EXCLUDE_DT, 'YYYYMM') = @YM_DATE" . "\r\n";
        $strSQL .= "ORDER BY EXCLUDE_DT" . "\r\n";

        $strSQL = str_replace("@YM_DATE", $ymDate, $strSQL);

        return $strSQL;
    }
    public function delLimitSQL($cours, $params)
    {
        $strSql = "";
        $strSql .= "DELETE FROM HMAUD_AUDITOR_SCHEDULE_LIMIT " . "\r\n";
        $strSql .= "WHERE COURS = @COURS " . "\r\n";
        $strSql .= "  AND SYAIN_NO = '@SYAIN_NO' " . "\r\n";
        $strSql .= "  AND PLAN_DT = TO_DATE('@OLDPLAN_DT', 'YYYY/MM/DD')";

        $strSql = str_replace("@COURS", $cours, $strSql);
        $strSql = str_replace("@SYAIN_NO", $params['syainNo'], $strSql);
        $strSql = str_replace("@PLAN_DT", $params['limitDate'], $strSql);
        $strSql = str_replace("@OLDPLAN_DT", $params['oldDate'], $strSql);

        return $strSql;
    }
    public function updLimitSQL($cours, $params)
    {
        $strSql = "";
        $strSql .= "UPDATE HMAUD_AUDITOR_SCHEDULE_LIMIT " . "\r\n";
        $strSql .= "SET PLAN_DT = TO_DATE('@PLAN_DT', 'YYYY/MM/DD'), " . "\r\n";
        $strSql .= "    UPD_DATE = SYSDATE,\r\n";
        $strSql .= "    UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "'\r\n";
        $strSql .= "WHERE COURS = @COURS " . "\r\n";
        $strSql .= "  AND SYAIN_NO = '@SYAIN_NO' " . "\r\n";
        $strSql .= "  AND PLAN_DT = TO_DATE('@OLDPLAN_DT', 'YYYY/MM/DD')";

        $strSql = str_replace("@COURS", $cours, $strSql);
        $strSql = str_replace("@SYAIN_NO", $params['syainNo'], $strSql);
        $strSql = str_replace("@PLAN_DT", $params['limitDate'], $strSql);
        $strSql = str_replace("@OLDPLAN_DT", $params['oldDate'], $strSql);

        return $strSql;
    }
    public function insLimitSQL($cours, $params)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HMAUD_AUDITOR_SCHEDULE_LIMIT VALUES (\r\n";
        $strSQL .= "     @COURS,\r\n";
        $strSQL .= "     '@SYAIN_NO',\r\n";
        $strSQL .= "     TO_DATE('@PLAN_DT', 'YYYY/MM/DD'),\r\n";
        $strSQL .= "     SYSDATE,\r\n";
        $strSQL .= "     '" . $this->GS_LOGINUSER['strUserID'] . "',\r\n";
        $strSQL .= "     SYSDATE,\r\n";
        $strSQL .= "     '" . $this->GS_LOGINUSER['strUserID'] . "'\r\n";
        $strSQL .= " )";

        $strSQL = str_replace("@COURS", $cours, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $params['syainNo'], $strSQL);
        $strSQL = str_replace("@PLAN_DT", $params['limitDate'], $strSQL);

        return $strSQL;
    }

    public function insertDataSQL($param)
    {
        $strSql = "";
        $strSql .= " INSERT " . "\r\n";
        $strSql .= " INTO HMAUD_MST_AUDIT_EXCLUDE_DATE VALUES " . "\r\n";
        $strSql .= "   ( " . "\r\n";
        $strSql .= "     TO_DATE('" . $param['EXCLUDE_DT'] . "', 'YYYY/MM/DD'), " . "\r\n";
        $strSql .= "     '" . $param['REMARKS'] . "', " . "\r\n";
        $strSql .= "     SYSDATE, " . "\r\n";
        $strSql .= "     '" . $this->GS_LOGINUSER['strUserID'] . "', " . "\r\n";
        $strSql .= "     SYSDATE, " . "\r\n";
        $strSql .= "     '" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSql .= "   ) " . "\r\n";

        return $strSql;
    }
    public function selAuditScheduleSQL($cours, $params)
    {
        $strSQL = "";
        $strSQL .= "SELECT * " . "\r\n";
        $strSQL .= "    FROM HMAUD_AUDITOR_SCHEDULE HAS" . "\r\n";
        $strSQL .= "WHERE HAS.COURS = @COURS" . "\r\n";
        $strSQL .= "AND HAS.SYAIN_NO = '@SYAIN_NO'" . "\r\n";
        $strSQL .= "AND TO_CHAR(HAS.PLAN_DT, 'YYYY/MM/DD') = '@PLAN_DT'" . "\r\n";
        $strSQL .= "AND HAS.AMPM = @AMPM" . "\r\n";

        $strSQL = str_replace("@COURS", $cours, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $params['syainNo'], $strSQL);
        $strSQL = str_replace("@PLAN_DT", $params['planDt'], $strSQL);
        $strSQL = str_replace("@AMPM", $params['am_pm'], $strSQL);

        return $strSQL;
    }
    public function delAuditScheduleSQL($cours, $params)
    {
        $strSql = "";
        $strSql .= "DELETE FROM HMAUD_AUDITOR_SCHEDULE " . "\r\n";
        $strSql .= "WHERE COURS = @COURS " . "\r\n";
        $strSql .= "  AND SYAIN_NO = '@SYAIN_NO' " . "\r\n";
        $strSql .= "  AND TO_CHAR(PLAN_DT, 'YYYY/MM/DD') = '@PLAN_DT'";
        $strSql .= "  AND AMPM = '@AMPM'";

        $strSql = str_replace("@COURS", $cours, $strSql);
        $strSql = str_replace("@SYAIN_NO", $params['syainNo'], $strSql);
        $strSql = str_replace("@PLAN_DT", $params['planDt'], $strSql);
        $strSql = str_replace("@AMPM", $params['am_pm'], $strSql);

        return $strSql;
    }
    public function updAuditScheduleSQL($cours, $params)
    {
        $strSql = "";
        $strSql .= "UPDATE HMAUD_AUDITOR_SCHEDULE " . "\r\n";
        $strSql .= "SET ENABLED = @ENABLED, " . "\r\n";
        $strSql .= "    UPD_DATE = SYSDATE,\r\n";
        $strSql .= "    UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "'\r\n";
        $strSql .= "WHERE COURS = @COURS " . "\r\n";
        $strSql .= "  AND SYAIN_NO = '@SYAIN_NO' " . "\r\n";
        $strSql .= "  AND TO_CHAR(PLAN_DT, 'YYYY/MM/DD') = '@PLAN_DT'";
        $strSql .= "  AND AMPM = '@AMPM'";

        $strSql = str_replace("@COURS", $cours, $strSql);
        $strSql = str_replace("@SYAIN_NO", $params['syainNo'], $strSql);
        $strSql = str_replace("@PLAN_DT", $params['planDt'], $strSql);
        $strSql = str_replace("@ENABLED", $params['enabled'], $strSql);
        $strSql = str_replace("@AMPM", $params['am_pm'], $strSql);

        return $strSql;
    }
    public function insAuditScheduleSQL($cours, $params)
    {
        $strSql = "";
        $strSql .= "INSERT INTO HMAUD_AUDITOR_SCHEDULE VALUES(" . "\r\n";
        $strSql .= "     @COURS,\r\n";
        $strSql .= "     '@SYAIN_NO',\r\n";
        $strSql .= "     TO_DATE('@PLAN_DT', 'YYYY/MM/DD'),\r\n";
        $strSql .= "     '@AMPM',\r\n";
        $strSql .= "     '@ENABLED',\r\n";
        $strSql .= "     SYSDATE,\r\n";
        $strSql .= "     '" . $this->GS_LOGINUSER['strUserID'] . "',\r\n";
        $strSql .= "     SYSDATE,\r\n";
        $strSql .= "     '" . $this->GS_LOGINUSER['strUserID'] . "'\r\n";
        $strSql .= " )";

        $strSql = str_replace("@COURS", $cours, $strSql);
        $strSql = str_replace("@SYAIN_NO", $params['syainNo'], $strSql);
        $strSql = str_replace("@PLAN_DT", $params['planDt'], $strSql);
        $strSql = str_replace("@AMPM", $params['am_pm'], $strSql);
        $strSql = str_replace("@ENABLED", $params['enabled'], $strSql);

        return $strSql;
    }
}