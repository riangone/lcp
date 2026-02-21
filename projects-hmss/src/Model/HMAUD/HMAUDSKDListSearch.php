<?php
/**
 * 履歴：
 * ------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                       担当
 * YYYYMMDD           #ID                       XXXXXX                                    FCSDL
 * 20250219           機能追加            20250219_内部統制_改修要望.xlsx                     YIN
 * ------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDSKDListSearch extends ClsComDb
{
    public function getCours($sysCour)
    {
        return parent::select($this->getCoursSQL($sysCour));
    }

    public function getAudit($coursStr)
    {
        return parent::select($this->getAuditSQL($coursStr));
    }

    public function getCoursSQL($sysCour)
    {
        $strSql = "";
        $strSql .= " SELECT COURS, " . "\r\n";
        $strSql .= "   TO_CHAR(START_DT, 'YYYY/MM/DD')    AS START_DT,    " . "\r\n";
        $strSql .= "   TO_CHAR(END_DT, 'YYYY/MM/DD')      AS END_DT,    " . "\r\n";
        $strSql .= "   TO_CHAR(START_DT, 'YYYY/MM')               AS MONTH1, " . "\r\n";
        $strSql .= "   TO_CHAR(ADD_MONTHS(START_DT,1), 'YYYY/MM') AS MONTH2, " . "\r\n";
        $strSql .= "   TO_CHAR(ADD_MONTHS(START_DT,2), 'YYYY/MM') AS MONTH3, " . "\r\n";
        $strSql .= "   TO_CHAR(ADD_MONTHS(START_DT,3), 'YYYY/MM') AS MONTH4, " . "\r\n";
        $strSql .= "   TO_CHAR(ADD_MONTHS(START_DT,4), 'YYYY/MM') AS MONTH5, " . "\r\n";
        $strSql .= "   TO_CHAR(ADD_MONTHS(START_DT,5), 'YYYY/MM') AS MONTH6 " . "\r\n";
        $strSql .= " FROM HMAUD_MST_COUR " . "\r\n";
        $strSql .= " WHERE COURS = " . $sysCour . "\r\n";
        $strSql .= " ORDER BY COURS " . "\r\n";

        return $strSql;
    }

    public function getAuditSQL($coursStr)
    {
        $strSql = "";
        $strSql .= " SELECT HMAUD_MST_KTN.KYOTEN_CD, " . "\r\n";
        $strSql .= "   HMAUD_MST_KTN.KYOTEN_NAME, " . "\r\n";
        $strSql .= "   HMAUD_AUDIT_MAIN.COURS, " . "\r\n";
        $strSql .= "   HMAUD_MST_KTN.TERRITORY as TERRITORY_KTN, " . "\r\n";
        $strSql .= "   CASE HMAUD_MST_KTN.TERRITORY " . "\r\n";
        $strSql .= "         WHEN '1' THEN " . "\r\n";
        $strSql .= "             '営業' " . "\r\n";
        $strSql .= "         WHEN '2' THEN " . "\r\n";
        $strSql .= "             'サービス' " . "\r\n";
        $strSql .= "         WHEN '3' THEN " . "\r\n";
        $strSql .= "             '管理' " . "\r\n";
        $strSql .= "         WHEN '4' THEN " . "\r\n";
        $strSql .= "             '業売' " . "\r\n";
        $strSql .= "         WHEN '5' THEN " . "\r\n";
        $strSql .= "             '業売管理' " . "\r\n";
        // 20250219 YIN INS S
        $strSql .= "         WHEN '6' THEN " . "\r\n";
        $strSql .= "             'カーセブン' " . "\r\n";
        // 20250219 YIN INS E
        $strSql .= "     END AS TERRITORY_NM, " . "\r\n";
        $strSql .= "   HMAUD_AUDIT_MAIN.TERRITORY as TERRITORY_MAIN, " . "\r\n";
        $strSql .= "   HMAUD_AUDIT_MAIN.CHECK_TIME, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.CHECK_DT, 'YYYY/MM/DD')      AS CHECK_DT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.PLAN_DT, 'YYYY/MM')          AS PLAN_DT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.REPORT2_LIMIT, 'YYYY/MM')     AS REPORT_LIMIT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.CHECK2_LIMIT, 'YYYY/MM') AS KEY_PERSON_LIMIT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.AUDIT_MEET_DT, 'YYYY/MM')    AS AUDIT_MEET_DT, " . "\r\n";
        $strSql .= "   MEMBERTL.MEMBERS " . "\r\n";
        $strSql .= " FROM HMAUD_MST_KTN " . "\r\n";
        $strSql .= " LEFT JOIN HMAUD_AUDIT_MAIN " . "\r\n";
        $strSql .= " ON HMAUD_AUDIT_MAIN.KYOTEN_CD = HMAUD_MST_KTN.KYOTEN_CD " . "\r\n";
        $strSql .= " AND HMAUD_AUDIT_MAIN.TERRITORY = HMAUD_MST_KTN.TERRITORY " . "\r\n";
        $strSql .= " AND HMAUD_AUDIT_MAIN.COURS   IN (" . $coursStr . ") " . "\r\n";
        $strSql .= " LEFT JOIN " . "\r\n";
        $strSql .= "   (SELECT HMAUD_AUDIT_MAIN.CHECK_ID, " . "\r\n";
        //20250509 CI UPD S
        // $strSql .= "     LISTAGG(HSYAINMST.SYAIN_NM,'、') WITHIN GROUP ( " . "\r\n";
        $strSql .= "     LISTAGG(M29MA4.SYAIN_KNJ_SEI, '、') WITHIN GROUP (" . "\r\n";
        //20250509 CI UPD E
        $strSql .= "   ORDER BY M29MA4.SYAIN_KNJ_SEI) AS MEMBERS " . "\r\n";
        $strSql .= "   FROM HMAUD_AUDIT_MAIN " . "\r\n";
        $strSql .= "   LEFT JOIN HMAUD_AUDIT_MEMBER " . "\r\n";
        $strSql .= "   ON HMAUD_AUDIT_MAIN.CHECK_ID = HMAUD_AUDIT_MEMBER.CHECK_ID " . "\r\n";
        $strSql .= "   AND HMAUD_AUDIT_MEMBER.ROLE = '1' " . "\r\n";
        //20250509 CI UPD S
        // $strSql .= "   LEFT JOIN HSYAINMST " . "\r\n";
        // $strSql .= '   ON HSYAINMST.SYAIN_NO = HMAUD_AUDIT_MEMBER."MEMBER" ' . "\r\n";
        $strSql .= "    LEFT JOIN M29MA4" . "\r\n";
        $strSql .= "    ON M29MA4.SYAIN_NO             = HMAUD_AUDIT_MEMBER.MEMBER" . "\r\n";
        //20250509 CI UPD E
        $strSql .= "   GROUP BY HMAUD_AUDIT_MAIN.CHECK_ID " . "\r\n";
        $strSql .= "   ) MEMBERTL ON HMAUD_AUDIT_MAIN.CHECK_ID = MEMBERTL.CHECK_ID " . "\r\n";
        $strSql .= " WHERE HMAUD_MST_KTN.TARGET                = 1 " . "\r\n";
        // 20250219 YIN INS S
        if ((int) $coursStr < 18) {
            $strSql .= " AND HMAUD_MST_KTN.TERRITORY <> '6' " . "\r\n";
        }
        // 20250219 YIN INS E
        $strSql .= " ORDER BY HMAUD_MST_KTN.KYOTEN_CD ,HMAUD_MST_KTN.TERRITORY " . "\r\n";

        return $strSql;
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

}
