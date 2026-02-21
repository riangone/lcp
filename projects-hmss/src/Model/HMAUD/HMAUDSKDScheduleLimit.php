<?php
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDSKDScheduleLimit extends ClsComDb
{
    public function selectData($cour, $syainNo, $planDt)
    {
        return parent::select($this->selectDataSQL($cour, $syainNo, $planDt));
    }
    public function insData($cour, $param)
    {
        return parent::insert($this->insertDataSQL($cour, $param));
    }
    public function updData($cour, $planDt, $param)
    {
        return parent::update($this->updDataSQL($cour, $planDt, $param));
    }
    public function delData($cour, $planDt, $param)
    {
        return parent::delete($this->delDataSQL($cour, $planDt, $param));
    }

    public function getCours($sysCour)
    {
        return parent::select($this->getCoursSQL($sysCour));
    }

    public function getSeLimit($ymDate)
    {
        return parent::select($this->getSeLimitSQL($ymDate));
    }
    public function getCoursSQL($sysCour)
    {
        $strSQL = "";
        $strSQL .= " SELECT COURS, " . "\r\n";
        $strSQL .= "   TO_CHAR(START_DT, 'YYYY/MM/DD')    AS START_DT,    " . "\r\n";
        $strSQL .= "   TO_CHAR(END_DT, 'YYYY/MM/DD')      AS END_DT,    " . "\r\n";
        $strSQL .= "   TO_CHAR(START_DT, 'YYYY/MM')               AS MONTH1, " . "\r\n";
        $strSQL .= "   TO_CHAR(ADD_MONTHS(START_DT,1), 'YYYY/MM') AS MONTH2, " . "\r\n";
        $strSQL .= "   TO_CHAR(ADD_MONTHS(START_DT,2), 'YYYY/MM') AS MONTH3, " . "\r\n";
        $strSQL .= "   TO_CHAR(ADD_MONTHS(START_DT,3), 'YYYY/MM') AS MONTH4, " . "\r\n";
        $strSQL .= "   TO_CHAR(ADD_MONTHS(START_DT,4), 'YYYY/MM') AS MONTH5, " . "\r\n";
        $strSQL .= "   TO_CHAR(ADD_MONTHS(START_DT,5), 'YYYY/MM') AS MONTH6 " . "\r\n";
        $strSQL .= " FROM HMAUD_MST_COUR " . "\r\n";
        $strSQL .= " WHERE COURS = " . $sysCour . "\r\n";
        $strSQL .= " ORDER BY COURS " . "\r\n";

        return $strSQL;
    }


    //検索条件・クールには 現在のクール数を初期表示
    function getInitializeCour()
    {
        $strSQL = "";
        $strSQL .= "SELECT COURS,TO_CHAR(START_DT,'YYYY/MM/DD')||' ～ '||TO_CHAR(END_DT,'YYYY/MM/DD') AS PERIOD," . "\r\n";
        $strSQL .= "  CASE" . "\r\n";
        $strSQL .= "    WHEN SYSDATE BETWEEN START_DT AND END_DT" . "\r\n";
        $strSQL .= "    THEN 1" . "\r\n";
        $strSQL .= "    ELSE 0" . "\r\n";
        $strSQL .= "  END AS COURS_NOW" . "\r\n";
        $strSQL .= "FROM HMAUD_MST_COUR" . "\r\n";
        $strSQL .= "ORDER BY START_DT DESC" . "\r\n";
        return parent::select($strSQL);
    }
    public function getSeLimitSQL($ymDate)
    {
        $strSQL = "";
        $strSQL .= "SELECT A.SYAIN_NO" . "\r\n";
        $strSQL .= "     , M.SYAIN_KNJ_SEI || '　' || M.SYAIN_KNJ_MEI AS SYAIN_NAME" . "\r\n";
        $strSQL .= "     , CASE" . "\r\n";
        $strSQL .= "         WHEN S.CNT > 0 THEN '入力済'" . "\r\n";
        $strSQL .= "         ELSE '未入力'" . "\r\n";
        $strSQL .= "       END AS INPUT_STATUS" . "\r\n";
        $strSQL .= "     , TO_CHAR(L.MIN_PLAN_DT, 'YYYY/MM/DD') AS LIMIT_DT" . "\r\n";
        $strSQL .= "FROM HMAUD_MST_AUDITOR A" . "\r\n";
        $strSQL .= "LEFT JOIN (" . "\r\n";
        $strSQL .= "    SELECT SYAIN_NO, COUNT(*) AS CNT" . "\r\n";
        $strSQL .= "    FROM HMAUD_AUDITOR_SCHEDULE" . "\r\n";
        $strSQL .= "    WHERE TO_CHAR(PLAN_DT, 'YYYY/MM') = '@YM_DATE'" . "\r\n";
        $strSQL .= "    GROUP BY SYAIN_NO" . "\r\n";
        $strSQL .= ") S ON A.SYAIN_NO = S.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN M29MA4 M ON A.SYAIN_NO = M.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN (" . "\r\n";
        $strSQL .= "    SELECT SYAIN_NO, MIN(PLAN_DT) AS MIN_PLAN_DT" . "\r\n";
        $strSQL .= "    FROM HMAUD_AUDITOR_SCHEDULE_LIMIT" . "\r\n";
        $strSQL .= "    WHERE TO_CHAR(PLAN_DT, 'YYYY/MM') = '@YM_DATE'" . "\r\n";
        $strSQL .= "    GROUP BY SYAIN_NO" . "\r\n";
        $strSQL .= ") L ON A.SYAIN_NO = L.SYAIN_NO" . "\r\n";
        $strSQL .= "WHERE A.ENABLED = 1" . "\r\n";
        $strSQL .= "ORDER BY TO_NUMBER(A.SEQ), A.SYAIN_NO" . "\r\n";

        $strSQL = str_replace("@YM_DATE", $ymDate, $strSQL);

        return $strSQL;
    }
    public function selectDataSQL($cour, $syainNo, $planDt)
    {
        $strSQL = "";
        $strSQL .= "SELECT * " . "\r\n";
        $strSQL .= "    FROM HMAUD_AUDITOR_SCHEDULE_LIMIT HASL" . "\r\n";
        $strSQL .= "WHERE HASL.COURS = @COURS" . "\r\n";
        $strSQL .= "AND HASL.SYAIN_NO = '@SYAIN_NO'" . "\r\n";
        $strSQL .= "AND TO_CHAR(HASL.PLAN_DT, 'YYYY/MM') = '@PLAN_DT'" . "\r\n";

        $strSQL = str_replace("@COURS", $cour, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $syainNo, $strSQL);
        if (strlen($planDt) === 7) {
            $ym = $planDt;
        } else {
            $ym = date('Y/m', strtotime($planDt));
        }
        $strSQL = str_replace("@PLAN_DT", $ym, $strSQL);

        return $strSQL;
    }
    public function insertDataSQL($cour, $param)
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

        $strSQL = str_replace("@COURS", $cour, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $param['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@PLAN_DT", $param['LIMIT_DT'], $strSQL);

        return $strSQL;
    }

    public function updDataSQL($cour, $planDt, $param)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HMAUD_AUDITOR_SCHEDULE_LIMIT " . "\r\n";
        $strSQL .= "    SET PLAN_DT = TO_DATE('@PLAN_DT', 'YYYY/MM/DD'), " . "\r\n";
        $strSQL .= "        UPD_DATE = SYSDATE, " . "\r\n";
        $strSQL .= "        UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "  WHERE COURS = @COURS " . "\r\n";
        $strSQL .= "    AND SYAIN_NO = '@SYAIN_NO' " . "\r\n";
        $strSQL .= "    AND PLAN_DT = TO_DATE('@OLDPLAN_DT', 'YYYY/MM/DD')" . "\r\n";

        $strSQL = str_replace("@COURS", $cour, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $param['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@PLAN_DT", $param['LIMIT_DT'], $strSQL);
        $strSQL = str_replace("@OLDPLAN_DT", date('Y/m/d', strtotime($planDt)), $strSQL);

        return $strSQL;
    }

    public function delDataSQL($cour, $planDt, $param)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HMAUD_AUDITOR_SCHEDULE_LIMIT " . "\r\n";
        $strSQL .= "  WHERE COURS = @COURS " . "\r\n";
        $strSQL .= "    AND SYAIN_NO = '@SYAIN_NO' " . "\r\n";
        $strSQL .= "    AND TO_CHAR(PLAN_DT, 'YYYY/MM/DD') = '@OLDPLAN_DT'" . "\r\n";

        $strSQL = str_replace("@COURS", $cour, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $param['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@OLDPLAN_DT", date('Y/m/d', strtotime($planDt)), $strSQL);

        return $strSQL;
    }


}