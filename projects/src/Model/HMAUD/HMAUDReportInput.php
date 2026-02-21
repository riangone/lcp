<?php
/**
 * 履歴：
 * ------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                       担当
 * YYYYMMDD           #ID                       XXXXXX                                    FCSDL
 * 20230103           機能追加　　　　　　          20221226_内部統制_仕様変更                YIN
 * 20240312           機能変更　　実績集計の一覧ソート順を ROW_NO から CHECK_LST_ID 順に変更   lujunxia
 * 20241030           機能変更　　202410_内部統制システム_集計機能改善対応 指摘回数を細分化         LHB
 * 20250403           機能追加       		     202504_内部統制_要望.xlsx        CI
 * 20250508           機能追加       		     202505_内部統制_要望.xlsx        CI
 * 20260128           修正依頼      社員番号に英字を使う更新処理でエラーが出る                YIN
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
class HMAUDReportInput extends ClsComDb
{
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

    //拠点マスタのデータを取得
    function getKyotenSql()
    {
        $strSQL = "SELECT KYOTEN_CD,KYOTEN_NAME,TERRITORY FROM HMAUD_MST_KTN WHERE TARGET='1'";
        return parent::select($strSQL);
    }

    //監査項目マスタテーブルにデータ
    function getDataSql($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT HARD.REPORT_LIST_ID," . "\r\n";
        $strSQL .= "  HARD.CHECK_ID," . "\r\n";
        $strSQL .= "  HAD.ROW_NO as CHECK_LIST_ID," . "\r\n";
        $strSQL .= "  HARD.POINTED," . "\r\n";
        $strSQL .= "  null as ROW_NO1," . "\r\n";
        $strSQL .= "  TO_CHAR(HARD.IMPROVE_PLAN_DT,'YYYY/MM/DD') AS IMPROVE_PLAN_DT," . "\r\n";
        $strSQL .= "  TO_CHAR(HARD.IMPROVE_DT,'YYYY/MM/DD') AS IMPROVE_DT," . "\r\n";
        $strSQL .= "  HARD.IMPROVE_DETAIL," . "\r\n";
        $strSQL .= "  HARD.KEYPERSON_CHECK," . "\r\n";
        $strSQL .= "  HARD.KEYPERSON_COMMENT," . "\r\n";
        //20250508 CI INS S
        $strSQL .= "   HAD.COLUMN1," . "\r\n";
        $strSQL .= "   HAD.COLUMN2," . "\r\n";
        $strSQL .= "   HAD.COLUMN3," . "\r\n";
        $strSQL .= "   HAD.COLUMN4," . "\r\n";
        $strSQL .= "   HAD.COLUMN5," . "\r\n";
        $strSQL .= "   HAD.COLUMN6," . "\r\n";
        //20250508 CI INS E
        $strSQL .= "  HAD.COLUMN7," . "\r\n";
        $strSQL .= "  HAD.ROW_NO," . "\r\n";
        $strSQL .= "  TO_CHAR(HARD.UPD_DATE, 'YYYY/MM/DD HH24:MI:SS') AS UPD_DATE " . "\r\n";

        $strSQL .= " FROM HMAUD_AUDIT_DETAIL HAD" . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= " ON HAM.COURS      = HAD.COURS" . "\r\n";
        $strSQL .= " AND HAM.TERRITORY = HAD.TERRITORY" . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= " ON HAR.CHECK_ID = HAM.CHECK_ID AND HAR.CHECK_LST_ID = HAD.ROW_NO " . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_AUDIT_REPORT_DETAIL HARD" . "\r\n";
        $strSQL .= " ON HARD.CHECK_ID = HAR.CHECK_ID AND HARD.CHECK_LIST_ID = HAR.CHECK_LST_ID " . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_MST_COUR HCOUR" . "\r\n";
        $strSQL .= " ON HAD.COURS=HCOUR.COURS" . "\r\n";
        $strSQL .= " WHERE HAR.RESULT ='2'" . "\r\n";
        $strSQL .= " AND  HAM.COURS   ='@COURS'" . "\r\n";
        if ($postData['TERRITORY'] != '') {
            $strSQL .= " AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
            $strSQL = str_replace("@TERRITORY", $postData['TERRITORY'], $strSQL);
        }
        if ($postData['KYOTEN_CD'] != '') {
            $strSQL .= " AND HAM.KYOTEN_CD = '@KYOTEN_CD'" . "\r\n";
            $strSQL = str_replace("@KYOTEN_CD", $postData['KYOTEN_CD'], $strSQL);
        }
        $strSQL .= " AND (HAD.EXPIRATION_DATE >= HCOUR.START_DT" . "\r\n";
        $strSQL .= " OR HAD.EXPIRATION_DATE IS NULL)" . "\r\n";
        //20240312 lujunxia upd s
        //$strSQL .= " ORDER BY  TO_NUMBER(REPLACE(HAD.ROW_NO,'追加','9999'))" . "\r\n";
        $strSQL .= " ORDER BY HAD.CHECK_LST_ID" . "\r\n";
        //20240312 lujunxia upd e
        $strSQL = str_replace("@COURS", $postData['COURS'], $strSQL);
        return parent::select($strSQL);
    }

    function getHeadDataSql($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT TO_CHAR(HARH.COMP_CHECK_DT,'YYYY/MM/DD') AS COMP_CHECK_DT," . "\r\n";
        $strSQL .= "  HARH.COMP_COMMENT," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT0,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT0," . "\r\n";
        $strSQL .= "  HARH.RESPONSIBLE_COMMENT0," . "\r\n";
        $strSQL .= "   TO_CHAR(HARH.RESPONSIBLE_CHECK_DT1,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT1," . "\r\n";
        $strSQL .= "  HARH.RESPONSIBLE_COMMENT1," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT2,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT2," . "\r\n";
        $strSQL .= "  HARH.RESPONSIBLE_COMMENT2," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT3,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT3," . "\r\n";
        $strSQL .= "  HARH.RESPONSIBLE_COMMENT3," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT4,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT4," . "\r\n";
        $strSQL .= "  HARH.RESPONSIBLE_COMMENT4," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT5,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT5," . "\r\n";
        $strSQL .= "  HARH.RESPONSIBLE_COMMENT5," . "\r\n";
        // 20230103 YIN INS S
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT6,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT6," . "\r\n";
        $strSQL .= "  HARH.RESPONSIBLE_COMMENT6," . "\r\n";
        // 20230103 YIN INS E
        // 20250403 CI INS S
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT7,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT7," . "\r\n";
        $strSQL .= "  HARH.RESPONSIBLE_COMMENT7," . "\r\n";
        // 20250403 CI INS E
        $strSQL .= "  HARH.REPORT_ID," . "\r\n";
        $strSQL .= "  HARH.CHECK_ID," . "\r\n";
        $strSQL .= "  HARH.STATUS" . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_REPORT_HEAD HARH" . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= " ON HARH.CHECK_ID = HAM.CHECK_ID" . "\r\n";
        $strSQL .= " WHERE HAM.COURS   ='@COURS'" . "\r\n";
        if ($postData['TERRITORY'] != '') {
            $strSQL .= " AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
            $strSQL = str_replace("@TERRITORY", $postData['TERRITORY'], $strSQL);
        }
        if ($postData['KYOTEN_CD'] != '') {
            $strSQL .= " AND HAM.KYOTEN_CD = '@KYOTEN_CD'" . "\r\n";
            $strSQL = str_replace("@KYOTEN_CD", $postData['KYOTEN_CD'], $strSQL);
        }
        $strSQL = str_replace("@COURS", $postData['COURS'], $strSQL);
        return parent::select($strSQL);
    }

    function getPersonDataSql($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT" . "\r\n";
        $strSQL .= "  HSM.SYAIN_NO," . "\r\n";
        $strSQL .= "  HSM.SYAIN_NM," . "\r\n";
        $strSQL .= "  HAME.ROLE" . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_REPORT_HEAD HARH" . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= " ON HARH.CHECK_ID = HAM.CHECK_ID" . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_AUDIT_MEMBER HAME" . "\r\n";
        $strSQL .= " ON HAME.CHECK_ID = HAM.CHECK_ID" . "\r\n";
        $strSQL .= " LEFT JOIN HSYAINMST HSM" . "\r\n";
        $strSQL .= " ON HSM.SYAIN_NO = HAME.MEMBER" . "\r\n";
        $strSQL .= " WHERE HAM.COURS   ='@COURS'" . "\r\n";
        $strSQL .= " AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= " AND HAM.KYOTEN_CD = '@KYOTEN_CD'" . "\r\n";
        $strSQL = str_replace("@TERRITORY", $postData['TERRITORY'], $strSQL);
        $strSQL = str_replace("@KYOTEN_CD", $postData['KYOTEN_CD'], $strSQL);
        $strSQL = str_replace("@COURS", $postData['COURS'], $strSQL);
        return parent::select($strSQL);
    }

    function getKyotenDataSql()
    {
        $strSQL = "SELECT KYOTEN_CD,KYOTEN_NAME,TERRITORY FROM HMAUD_MST_KTN WHERE TARGET='1'";
        return parent::select($strSQL);
    }

    function getUserRoleSql($check_id)
    {
        $strSQL = "SELECT ROLE FROM HMAUD_AUDIT_MEMBER WHERE CHECK_ID = '@CHECK_ID' AND MEMBER = '@MEMBER'";
        $strSQL = str_replace("@CHECK_ID", $check_id, $strSQL);
        $strSQL = str_replace("@MEMBER", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::select($strSQL);
    }

    function getMemberdataSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT count(*) AS COUNT" . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_MEMBER HAME" . "\r\n";
        // $strSQL .= " INNER JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        // $strSQL .= " ON HAM.CHECK_ID = HAME.CHECK_ID" . "\r\n";
        $strSQL .= " WHERE HAME.MEMBER   ='@MEMBER'" . "\r\n";
        // $strSQL .= " AND HAM.COURS   ='@COURS'" . "\r\n";
        // $strSQL .= " AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        // $strSQL .= " AND HAM.KYOTEN_CD = '@KYOTEN_CD'" . "\r\n";
        // $strSQL = str_replace("@COURS", $postData['COURS'], $strSQL);
        // $strSQL = str_replace("@KYOTEN_CD", $postData['KYOTEN_CD'], $strSQL);
        // $strSQL = str_replace("@TERRITORY", $postData['TERRITORY'], $strSQL);
        $strSQL = str_replace("@MEMBER", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::select($strSQL);
    }

    function getViewerdataSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT count(*) AS COUNT" . "\r\n";
        $strSQL .= " FROM HMAUD_MST_VIEWER HMV" . "\r\n";

        $strSQL .= " WHERE HMV.SYAIN_NO   ='@SYAIN_NO'" . "\r\n";
        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::select($strSQL);
    }

    function getadminSql()
    {
        $strSQL = "SELECT count(*) AS COUNT FROM HMAUD_MST_ADMIN WHERE SYAIN_NO = '@SYAIN_NO'";
        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::select($strSQL);
    }

    function updReportDetailSql($param)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_DETAIL SET POINTED = '@POINTED' , IMPROVE_DETAIL = '@IMPROVE_DETAIL' ,IMPROVE_PLAN_DT = TO_DATE('@IMPROVE_PLAN_DT','YYYY/MM/DD') ,IMPROVE_DT = TO_DATE('@IMPROVE_DT','YYYY/MM/DD'),KEYPERSON_CHECK = '@KEYPERSON_CHECK',KEYPERSON_COMMENT = '@KEYPERSON_COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND CHECK_LIST_ID = '@CHECK_LIST_ID' AND REPORT_LIST_ID = '@REPORT_LIST_ID'";
        $strSQL = str_replace("@POINTED", $param['POINTED'], $strSQL);
        $strSQL = str_replace("@IMPROVE_DETAIL", $param['IMPROVE_DETAIL'], $strSQL);
        $strSQL = str_replace("@IMPROVE_PLAN_DT", $param['IMPROVE_PLAN_DT'], $strSQL);
        $strSQL = str_replace("@IMPROVE_DT", $param['IMPROVE_DT'], $strSQL);
        $strSQL = str_replace("@KEYPERSON_CHECK", $param['KEYPERSON_CHECK'], $strSQL);
        $strSQL = str_replace("@KEYPERSON_COMMENT", $param['KEYPERSON_COMMENT'], $strSQL);
        $strSQL = str_replace("@CHECK_ID", $param['CHECK_ID'], $strSQL);
        $strSQL = str_replace("@CHECK_LIST_ID", $param['CHECK_LIST_ID'], $strSQL);
        $strSQL = str_replace("@REPORT_LIST_ID", $param['REPORT_LIST_ID'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::update($strSQL);
    }

    function insReportDetailSql($param, $report_id)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_DETAIL" . "\r\n";
        $strSQL .= "VALUES('@REPORT_LIST_ID', '@CHECK_ID', '@CHECK_LIST_ID','@ROW_NO', '@POINTED', '@IMPROVE_PLAN_DT', '@IMPROVE_DT', '@IMPROVE_DETAIL','', '@KEYPERSON_CHECK', '@KEYPERSON_COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        $strSQL = str_replace("@POINTED", $param['POINTED'], $strSQL);
        $strSQL = str_replace("@IMPROVE_DETAIL", $param['IMPROVE_DETAIL'], $strSQL);
        $strSQL = str_replace("@IMPROVE_PLAN_DT", $param['IMPROVE_PLAN_DT'], $strSQL);
        $strSQL = str_replace("@IMPROVE_DT", $param['IMPROVE_DT'], $strSQL);
        $strSQL = str_replace("@KEYPERSON_CHECK", $param['KEYPERSON_CHECK'], $strSQL);
        $strSQL = str_replace("@KEYPERSON_COMMENT", $param['KEYPERSON_COMMENT'], $strSQL);
        $strSQL = str_replace("@CHECK_ID", $param['CHECK_ID'], $strSQL);
        $strSQL = str_replace("@CHECK_LIST_ID", $param['CHECK_LIST_ID'], $strSQL);
        $strSQL = str_replace("@ROW_NO", $param['ROW_NO1'], $strSQL);
        $strSQL = str_replace("@REPORT_LIST_ID", $report_id, $strSQL);
        $strSQL = str_replace("@CREATE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::insert($strSQL);
    }

    function getReportDetailExist($check_id, $report_list_id)
    {
        $strSQL = "";
        $strSQL .= " SELECT count(*) AS COUNT" . "\r\n";
        $strSQL .= "  FROM HMAUD_AUDIT_REPORT_DETAIL" . "\r\n";
        $strSQL .= "  WHERE CHECK_ID      ='@CHECK_ID'" . "\r\n";
        $strSQL .= "  AND REPORT_LIST_ID         ='@REPORT_LIST_ID'" . "\r\n";
        $strSQL = str_replace("@CHECK_ID", $check_id, $strSQL);
        $strSQL = str_replace("@REPORT_LIST_ID", $report_list_id, $strSQL);
        return parent::select($strSQL);
    }

    function getMaxid()
    {
        $strSQL = "SELECT MAX(TO_NUMBER(REPORT_LIST_ID)) +1 AS REPORT_LIST_ID FROM HMAUD_AUDIT_REPORT_DETAIL";

        return parent::select($strSQL);
    }

    function chkrowno($params, $row_no)
    {
        $strSQL = "";
        $strSQL .= " SELECT count(*) AS COUNT" . "\r\n";
        $strSQL .= "  FROM HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= "  INNER JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "  ON HAM.CHECK_ID   =HAR.CHECK_ID" . "\r\n";
        $strSQL .= "  WHERE TO_NUMBER(HAM.COURS)   <=@COURS" . "\r\n";
        // 20241030 LHB upd s
        // $strSQL .= "  AND TO_NUMBER(HAM.COURS)   > @COURS - 3" . "\r\n";
        $strSQL .= "  AND TO_NUMBER(HAM.COURS)   > @COURS - 6" . "\r\n";
        // 20241030 LHB upd e
        $strSQL .= "  AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= "  AND HAM.KYOTEN_CD = '@KYOTEN_CD'" . "\r\n";
        $strSQL .= "  AND HAR.CHECK_LST_ID = '@ROW_NO'" . "\r\n";
        $strSQL .= "  AND HAR.RESULT='2'" . "\r\n";
        $strSQL = str_replace("@COURS", $params['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $params['TERRITORY'], $strSQL);
        $strSQL = str_replace("@KYOTEN_CD", $params['KYOTEN_CD'], $strSQL);
        $strSQL = str_replace("@ROW_NO", $row_no, $strSQL);
        return parent::select($strSQL);

    }

    // 20241030 LHB ins s
    function continuous_chkrowno($params, $row_no)
    {
        $strSQL = "";
        $strSQL .= " SELECT HAME.MEMBER,HAM.COURS" . "\r\n";
        $strSQL .= "  FROM HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= "  INNER JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "  ON HAM.CHECK_ID   =HAR.CHECK_ID" . "\r\n";
        $strSQL .= "  LEFT JOIN HMAUD_AUDIT_MEMBER HAME" . "\r\n";
        $strSQL .= "  ON HAME.CHECK_ID = HAM.CHECK_ID" . "\r\n";
        $strSQL .= "  WHERE TO_NUMBER(HAM.COURS)   <=@COURS" . "\r\n";
        $strSQL .= "  AND TO_NUMBER(HAM.COURS)   > @COURS - 6" . "\r\n";
        $strSQL .= "  AND HAM.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= "  AND HAM.KYOTEN_CD = '@KYOTEN_CD'" . "\r\n";
        $strSQL .= "  AND HAR.CHECK_LST_ID = '@ROW_NO'" . "\r\n";
        $strSQL .= "  AND HAR.RESULT='2'" . "\r\n";
        $strSQL .= "  AND HAME.ROLE = '3'" . "\r\n";
        $strSQL .= "  ORDER BY HAM.COURS DESC" . "\r\n";
        $strSQL = str_replace("@COURS", $params['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $params['TERRITORY'], $strSQL);
        $strSQL = str_replace("@KYOTEN_CD", $params['KYOTEN_CD'], $strSQL);
        $strSQL = str_replace("@ROW_NO", $row_no, $strSQL);
        return parent::select($strSQL);

    }

    function count_row_no($check_id, $check_list_id)
    {
        $strSQL = "";
        $strSQL .= "SELECT nvl(count(*),0) + 1 as ROW_NO" . "\r\n";
        $strSQL .= "  FROM HMAUD_AUDIT_REPORT_DETAIL " . "\r\n";
        $strSQL .= "  WHERE CHECK_ID  ='@CHECK_ID'" . "\r\n";
        $strSQL .= "  AND CHECK_LIST_ID  = '@CHECK_LIST_ID'" . "\r\n";
        $strSQL = str_replace("@CHECK_ID", $check_id, $strSQL);
        $strSQL = str_replace("@CHECK_LIST_ID", $check_list_id, $strSQL);
        return parent::select($strSQL);
    }
    // 20241030 LHB ins e
}
