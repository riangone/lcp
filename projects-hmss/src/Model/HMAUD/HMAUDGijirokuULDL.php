<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                         担当
 * YYYYMMDD           #ID                       XXXXXX                                       FCSDL
 * 20250403           機能変更               202504_内部統制_要望.xlsx                        lujunxia
 * --------------------------------------------------------------------------------------------------
 */
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;
class HMAUDGijirokuULDL extends ClsComDb
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

    public function getAdmin()
    {
        $strSQL = "";
        $strSQL .= " SELECT SYAIN_NO " . "\r\n";
        $strSQL .= " FROM HMAUD_MST_ADMIN " . "\r\n";
        $strSQL .= " WHERE HMAUD_MST_ADMIN.SYAIN_NO   = '@SYAIN_NO' " . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);

        return parent::select($strSQL);
    }
    //20230310 CAI INS S
    public function getViewer()
    {
        $strSQL = "";
        $strSQL .= " SELECT SYAIN_NO " . "\r\n";
        $strSQL .= " FROM HMAUD_MST_VIEWER " . "\r\n";
        $strSQL .= " WHERE HMAUD_MST_VIEWER.SYAIN_NO   = '@SYAIN_NO' " . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);

        return parent::select($strSQL);
    }
    //20230310 CAI INS E
    public function getFiles($cour, $keyword)
    {
        $strSQL = "";
        $strSQL .= " SELECT FILE_ID " . "\r\n";
        $strSQL .= " ,FILE_NAME AS FILENAME " . "\r\n";
        $strSQL .= " ,KEYWORD " . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_UPLOAD_FILES " . "\r\n";
        $strSQL .= " WHERE COURS   = '@COUR' " . "\r\n";
        $strSQL .= " AND STATUS  <> 9 " . "\r\n";
        if ($keyword !== "") {
            $strSQL .= " AND KEYWORD  = '@KEYWORD' " . "\r\n";
        }

        $strSQL = str_replace("@COUR", $cour, $strSQL);
        $strSQL = str_replace("@KEYWORD", $keyword, $strSQL);

        return parent::select($strSQL);
    }

    public function checkFile($param)
    {
        $strSQL = "";
        $strSQL .= " SELECT FILE_NAME " . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_UPLOAD_FILES " . "\r\n";
        $strSQL .= " WHERE COURS   = '@COUR' " . "\r\n";
        $strSQL .= " AND STATUS  <> 9 " . "\r\n";
        $strSQL .= " AND FILE_NAME  = '@FILE_NAME' " . "\r\n";

        $strSQL = str_replace("@COUR", $param['COURS'], $strSQL);
        $strSQL = str_replace("@FILE_NAME", $param['txtPath'], $strSQL);

        return parent::select($strSQL);
    }

    public function fileInsert($param)
    {
        $strSQL = "";
        $strSQL .= " INSERT " . "\r\n";
        $strSQL .= " INTO HMAUD_AUDIT_UPLOAD_FILES " . "\r\n";
        $strSQL .= "   ( " . "\r\n";
        $strSQL .= "     FILE_ID " . "\r\n";
        $strSQL .= "     ,COURS " . "\r\n";
        $strSQL .= "     ,FILE_NAME " . "\r\n";
        $strSQL .= "     ,KEYWORD " . "\r\n";
        $strSQL .= "     ,STATUS " . "\r\n";
        $strSQL .= "     ,CREATE_DATE " . "\r\n";
        $strSQL .= "     ,CREATE_SYA_CD " . "\r\n";
        $strSQL .= "     ,UPD_DATE " . "\r\n";
        $strSQL .= "     ,UPD_SYA_CD " . "\r\n";
        $strSQL .= "   ) " . "\r\n";
        $strSQL .= "   VALUES " . "\r\n";
        $strSQL .= "   ( " . "\r\n";
        $strSQL .= "     (SELECT TO_CHAR(NVL(MAX(TO_NUMBER(FILE_ID)),0) +1) AS FILE_ID " . "\r\n";
        $strSQL .= "       FROM HMAUD_AUDIT_UPLOAD_FILES " . "\r\n";
        $strSQL .= "     ) " . "\r\n";
        $strSQL .= "     , " . "\r\n";
        $strSQL .= " @COURS, " . "\r\n";
        $strSQL .= " '@FILE_NAME', " . "\r\n";
        $strSQL .= " '@KEYWORD', " . "\r\n";
        $strSQL .= " 0 " . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "   ) " . "\r\n";

        $strSQL = str_replace("@COURS", $param['COURS'], $strSQL);
        $strSQL = str_replace("@FILE_NAME", $param['txtPath'], $strSQL);
        $strSQL = str_replace("@KEYWORD", $param['keyword'], $strSQL);

        return parent::insert($strSQL);
    }

    public function deleteFiles($gridDatas)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HMAUD_AUDIT_UPLOAD_FILES " . "\r\n";
        $strSQL .= " SET " . "\r\n";
        $strSQL .= " STATUS = 9 " . "\r\n";
        $strSQL .= " ,UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= " ,UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= " FILE_ID IN (@FILE_ID) " . "\r\n";

        $strSQL = str_replace("@FILE_ID", $gridDatas, $strSQL);

        return parent::update($strSQL);
    }

}
