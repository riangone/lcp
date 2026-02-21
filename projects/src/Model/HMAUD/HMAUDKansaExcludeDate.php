<?php
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDKansaExcludeDate extends ClsComDb
{

    public function insertData($param)
    {
        return parent::insert($this->insertDataSQL($param));
    }

    public function getCours($sysCour)
    {
        return parent::select($this->getCoursSQL($sysCour));
    }

    public function getExclude($startDate, $endDate)
    {
        return parent::select($this->getExcludeSQL($startDate, $endDate));
    }
    public function excludeDel($startDate, $endDate)
    {
        return parent::delete($this->excludeDelSQL($startDate, $endDate));
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
    public function getExcludeSQL($startDate, $endDate)
    {
        $strSQL = "";
        $strSQL .= "SELECT TO_CHAR(EXCLUDE_DT, 'YYYY/MM/DD') AS EXCLUDE_DT" . "\r\n";
        $strSQL .= "     , REMARKS" . "\r\n";
        $strSQL .= "FROM HMAUD_MST_AUDIT_EXCLUDE_DATE" . "\r\n";
        $strSQL .= "WHERE EXCLUDE_DT BETWEEN TO_DATE('@START_DT', 'YYYY-MM-DD') AND TO_DATE('@END_DT', 'YYYY-MM-DD')" . "\r\n";

        $strSQL = str_replace("@START_DT", $startDate, $strSQL);
        $strSQL = str_replace("@END_DT", $endDate, $strSQL);
        return $strSQL;
    }

    public function excludeDelSQL($startDate, $endDate)
    {
        $strSql = "";
        $strSql .= "DELETE FROM HMAUD_MST_AUDIT_EXCLUDE_DATE \r\n";
        $strSql .= "WHERE EXCLUDE_DT BETWEEN TO_DATE('@START_DT','YYYY/MM/DD') \r\n";
        $strSql .= "AND TO_DATE('@END_DT','YYYY/MM/DD')";

        $strSql = str_replace("@START_DT", $startDate, $strSql);
        $strSql = str_replace("@END_DT", $endDate, $strSql);

        return $strSql;
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

}