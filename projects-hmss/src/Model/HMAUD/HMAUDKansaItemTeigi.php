<?php
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

class HMAUDKansaItemTeigi extends ClsComDb
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
        $strSQL .= "ORDER BY START_DT DESC" . "\r\n";
        return parent::select($strSQL);
    }

    function getadminSql()
    {
        $strSQL = "SELECT count(*) AS COUNT FROM HMAUD_MST_ADMIN WHERE SYAIN_NO = '@SYAIN_NO'";
        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::select($strSQL);
    }

    public function fncSelectMaxchecklstid()
    {
        $strSql = $this->fncSelectMaxchecklstidSQL();
        return parent::select($strSql);
    }

    public function fncSelectMaxchecklstidSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT  ";
        $strSQL .= " MAX(TO_NUMBER(CHECK_LST_ID)) as CHECK_LST_ID " . "\r\n";
        $strSQL .= " FROM  " . "\r\n";
        $strSQL .= " HMAUD_AUDIT_DETAIL ";
        return $strSQL;
    }

    public function fncDeleteDetailExist($postdata)
    {
        $strSql = $this->fncDeleteDetailExistSQL($postdata);
        return parent::delete($strSql);
    }

    public function fncDeleteDetailExistSQL($postdata)
    {
        $strSQL = "";
        $strSQL .= " DELETE  " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "  HMAUD_AUDIT_DETAIL " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "  COURS = '@COURS' AND TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL = str_replace("@COURS", $postdata['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $postdata['TERRITORY'], $strSQL);
        return $strSQL;
    }

    public function fncDeleteTableExist($postdata, $table)
    {
        $strSql = $this->fncDeleteTableExistSQL($postdata, $table);
        return parent::delete($strSql);
    }

    public function fncDeleteTableExistSQL($postdata, $table)
    {
        $strSQL = "";
        $strSQL .= " DELETE  " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "  @TABLE " . "\r\n";
        $strSQL .= " WHERE CHECK_ID IN" . "\r\n";
        $strSQL .= "  (SELECT HAM.CHECK_ID" . "\r\n";
        $strSQL .= "  FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "  LEFT JOIN HMAUD_AUDIT_DETAIL HAD" . "\r\n";
        $strSQL .= "  ON HAD.COURS     =HAM.COURS" . "\r\n";
        $strSQL .= "  AND HAD.TERRITORY=HAM.TERRITORY" . "\r\n";
        $strSQL .= "   WHERE HAM.COURS  ='@COURS'" . "\r\n";
        $strSQL .= "   AND HAM.TERRITORY='@TERRITORY'" . "\r\n";
        $strSQL .= "  GROUP BY HAM.CHECK_ID" . "\r\n";
        $strSQL .= "  )" . "\r\n";
        $strSQL = str_replace("@COURS", $postdata['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $postdata['TERRITORY'], $strSQL);
        $strSQL = str_replace("@TABLE", $table, $strSQL);
        return $strSQL;
    }

    public function fncDeleteHeadTableExist($postdata)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HMAUD_AUDIT_REPORT_HEAD " . "\r\n";
        $strSQL .= " SET STATUS = '00' " . "\r\n";
        $strSQL .= ",		COMP_DT1 = NULL  " . "\r\n";
        $strSQL .= ",		COMP_DT2 = NULL  " . "\r\n";
        $strSQL .= ",		COMP_DT3 = NULL  " . "\r\n";
        $strSQL .= ",		COMP_CHECK_DT = NULL  " . "\r\n";
        $strSQL .= ",		COMP_COMMENT = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_CHECK_DT0 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_COMMENT0 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_CHECK_DT1 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_COMMENT1 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_CHECK_DT2 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_COMMENT2 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_CHECK_DT3 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_COMMENT3 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_CHECK_DT4 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_COMMENT4 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_CHECK_DT5 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_COMMENT5 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_CHECK_DT6 = NULL  " . "\r\n";
        $strSQL .= ",		RESPONSIBLE_COMMENT6 = NULL  " . "\r\n";
        $strSQL .= ",		AUDIT_MEET_DT = NULL  " . "\r\n";
        $strSQL .= ",		AUDIT_MEET_COMMENT = NULL  " . "\r\n";
        $strSQL .= ",		UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= " WHERE CHECK_ID IN" . "\r\n";
        $strSQL .= "  (SELECT HAM.CHECK_ID" . "\r\n";
        $strSQL .= "  FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "  LEFT JOIN HMAUD_AUDIT_DETAIL HAD" . "\r\n";
        $strSQL .= "  ON HAD.COURS     =HAM.COURS" . "\r\n";
        $strSQL .= "  AND HAD.TERRITORY=HAM.TERRITORY" . "\r\n";
        $strSQL .= "   WHERE HAM.COURS  ='@COURS'" . "\r\n";
        $strSQL .= "   AND HAM.TERRITORY='@TERRITORY'" . "\r\n";
        $strSQL .= "  GROUP BY HAM.CHECK_ID" . "\r\n";
        $strSQL .= "  )" . "\r\n";
        $strSQL = str_replace("@COURS", $postdata['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $postdata['TERRITORY'], $strSQL);

        return parent::update($strSQL);
    }

    public function InsertData($postdata, $vAry, $id)
    {
        $strSql = $this->InsertDataSQL($postdata, $vAry, $id);
        return parent::insert($strSql);
    }

    public function InsertDataSQL($postdata, $vAry, $id)
    {
        $strSQL = "";
        $strSQL = "INSERT INTO HMAUD_AUDIT_DETAIL " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " CHECK_LST_ID " . "\r\n";
        $strSQL .= " ,COURS " . "\r\n";
        $strSQL .= " ,TERRITORY " . "\r\n";
        $strSQL .= " ,ROW_NO " . "\r\n";
        $strSQL .= " ,COLUMN1 " . "\r\n";
        $strSQL .= " ,COLUMN2 " . "\r\n";
        $strSQL .= " ,COLUMN3 " . "\r\n";
        $strSQL .= " ,COLUMN4 " . "\r\n";
        $strSQL .= " ,COLUMN5 " . "\r\n";
        $strSQL .= " ,COLUMN6 " . "\r\n";
        $strSQL .= " ,COLUMN7 " . "\r\n";
        $strSQL .= " ,REMARKS " . "\r\n";
        $strSQL .= " ,EXPIRATION_DATE " . "\r\n";
        $strSQL .= " ,CREATE_DATE " . "\r\n";
        $strSQL .= " ,CREATE_SYA_CD " . "\r\n";
        $strSQL .= " ,UPD_DATE " . "\r\n";
        $strSQL .= " ,UPD_SYA_CD " . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "Values( " . "\r\n";
        $strSQL .= "  @CHECK_LST_ID " . "\r\n";
        $strSQL .= " ,'@COURS' " . "\r\n";
        $strSQL .= " ,'@TERRITORY' " . "\r\n";
        $strSQL .= " ,'@ROW_NO' " . "\r\n";
        $strSQL .= " ,'@COLUMN1' " . "\r\n";
        $strSQL .= " ,'@COLUMN2' " . "\r\n";
        $strSQL .= " ,'@COLUMN3' " . "\r\n";
        $strSQL .= " ,'@COLUMN4' " . "\r\n";
        $strSQL .= " ,'@COLUMN5' " . "\r\n";
        $strSQL .= " ,'@COLUMN6' " . "\r\n";
        $strSQL .= " ,'@COLUMN7' " . "\r\n";
        $strSQL .= " ,'@REMARKS' " . "\r\n";
        $strSQL .= " ,TO_DATE('@EXPIRATION_DATE','YYYY-MM-DD') " . "\r\n";
        $strSQL .= " ,SYSDATE " . "\r\n";
        $strSQL .= " ,'@CREATE_SYA_CD' " . "\r\n";
        $strSQL .= " ,SYSDATE " . "\r\n";
        $strSQL .= " ,'@UPD_SYA_CD' " . "\r\n";
        $strSQL .= ") ";
        $strSQL = str_replace("@CHECK_LST_ID", $id, $strSQL);
        $strSQL = str_replace("@COURS", $postdata['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $postdata['TERRITORY'], $strSQL);
        $strSQL = str_replace("@ROW_NO", $vAry[0], $strSQL);
        $strSQL = str_replace("@COLUMN1", is_string($vAry[1]) ? $vAry[1] : '', $strSQL);
        $strSQL = str_replace("@COLUMN2", $vAry[2], $strSQL);
        $strSQL = str_replace("@COLUMN3", $vAry[3], $strSQL);
        $strSQL = str_replace("@COLUMN4", $vAry[4], $strSQL);
        $strSQL = str_replace("@COLUMN5", is_string($vAry[5]) ? $vAry[5] : '', $strSQL);
        $strSQL = str_replace("@COLUMN6", is_string($vAry[6]) ? $vAry[6] : '', $strSQL);
        $strSQL = str_replace("@COLUMN7", $vAry[7], $strSQL);
        $strSQL = str_replace("@REMARKS", is_string($vAry[9]) ? $vAry[9] : '', $strSQL);
        $strSQL = str_replace("@EXPIRATION_DATE", $vAry[10], $strSQL);
        $strSQL = str_replace("@CREATE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return $strSQL;
    }

}
