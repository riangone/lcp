<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20230103           機能追加　　　　　　          20221226_内部統制_仕様変更        YIN
 * 20250403           機能追加　　　　　 202504_内部統制_要望.xlsx               YIN
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDSKDToroku extends ClsComDb
{
    public function getMainData($postData)
    {
        return parent::select($this->getMainDataSQL($postData));
    }

    public function getMainDataSQL($postData)
    {
        $strSql = "";
        $strSql .= " SELECT HMAUD_AUDIT_MAIN.CHECK_ID, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.PLAN_DT, 'YYYY/MM/DD') AS PLAN_DT, " . "\r\n";
        $strSql .= "   HMAUD_AUDIT_MAIN.PLAN_TIME, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.PLAN_LIMIT, 'YYYY/MM/DD') AS PLAN_LIMIT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.REPORT0_LIMIT, 'YYYY/MM/DD') AS REPORT0_LIMIT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.REPORT1_LIMIT, 'YYYY/MM/DD') AS REPORT1_LIMIT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.REPORT2_LIMIT, 'YYYY/MM/DD') AS REPORT2_LIMIT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.CHECK1_LIMIT, 'YYYY/MM/DD')  AS CHECK1_LIMIT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.CHECK2_LIMIT, 'YYYY/MM/DD')  AS CHECK2_LIMIT, " . "\r\n";
        $strSql .= "   TO_CHAR(HMAUD_AUDIT_MAIN.AUDIT_MEET_DT, 'YYYY/MM/DD') AS AUDIT_MEET_DT, " . "\r\n";
        $strSql .= '   HMAUD_AUDIT_MEMBER."ROLE", ' . "\r\n";
        $strSql .= '   HMAUD_AUDIT_MEMBER."MEMBER", ' . "\r\n";
        $strSql .= '   HSYAINMST.SYAIN_NM ' . "\r\n";
        $strSql .= " FROM HMAUD_AUDIT_MAIN " . "\r\n";
        $strSql .= " LEFT JOIN HMAUD_AUDIT_MEMBER " . "\r\n";
        $strSql .= " ON HMAUD_AUDIT_MEMBER.CHECK_ID = HMAUD_AUDIT_MAIN.CHECK_ID " . "\r\n";
        // 20230103 YIN UPD S
        // $strSql .= " AND HMAUD_AUDIT_MEMBER.\"ROLE\" IN ('2','3','4','5','6','7') " . "\r\n";
        // 20250403 YIN UPD S
        // $strSql .= " AND HMAUD_AUDIT_MEMBER.\"ROLE\" IN ('2','3','4','5','6','7','8') " . "\r\n";
        $strSql .= " AND HMAUD_AUDIT_MEMBER.\"ROLE\" IN ('2','3','4','5','6','7','8','9') " . "\r\n";
        // 20250403 YIN UPD E
        // 20230103 YIN UPD E
        $strSql .= " LEFT JOIN HSYAINMST " . "\r\n";
        $strSql .= " ON HMAUD_AUDIT_MEMBER.\"MEMBER\" = HSYAINMST.SYAIN_NO " . "\r\n";
        $strSql .= " WHERE HMAUD_AUDIT_MAIN.COURS   =  " . $postData['cour'] . "\r\n";
        $strSql .= " AND HMAUD_AUDIT_MAIN.KYOTEN_CD = '" . $postData['kyotenCD'] . "' " . "\r\n";
        $strSql .= " AND HMAUD_AUDIT_MAIN.TERRITORY = '" . $postData['territory'] . "' " . "\r\n";

        return $strSql;
    }

    public function getDefaultMembers($postData)
    {
        return parent::select($this->getDefaultMembersSQL($postData));
    }

    public function getDefaultMembersSQL($postData)
    {
        $strSql = "";
        $strSql .= " SELECT RESPONSIBLE_EIGYO, " . "\r\n";
        $strSql .= "   EIGYO_TBL.SYAIN_NM AS RESPONSIBLE_EIGYO_NAME, " . "\r\n";
        $strSql .= "   RESPONSIBLE_TERRITORY, " . "\r\n";
        $strSql .= "   TERRITORY_TBL.SYAIN_NM AS RESPONSIBLE_TERRITORY_NAME, " . "\r\n";
        $strSql .= "   KEY_PERSON, " . "\r\n";
        $strSql .= "   PERSON_TBL.SYAIN_NM AS KEY_PERSON_NAME " . "\r\n";
        $strSql .= " FROM HMAUD_MST_KTN " . "\r\n";
        $strSql .= " LEFT JOIN HSYAINMST EIGYO_TBL " . "\r\n";
        $strSql .= " ON HMAUD_MST_KTN.RESPONSIBLE_EIGYO = EIGYO_TBL.SYAIN_NO " . "\r\n";
        $strSql .= " LEFT JOIN HSYAINMST TERRITORY_TBL " . "\r\n";
        $strSql .= " ON HMAUD_MST_KTN.RESPONSIBLE_TERRITORY = TERRITORY_TBL.SYAIN_NO " . "\r\n";
        $strSql .= " LEFT JOIN HSYAINMST PERSON_TBL " . "\r\n";
        $strSql .= " ON HMAUD_MST_KTN.KEY_PERSON   = PERSON_TBL.SYAIN_NO " . "\r\n";
        $strSql .= " WHERE HMAUD_MST_KTN.KYOTEN_CD = '" . $postData['kyotenCD'] . "' " . "\r\n";
        $strSql .= " AND HMAUD_MST_KTN.TERRITORY   = '" . $postData['territory'] . "' " . "\r\n";

        return $strSql;
    }

    public function getMembers($check_id)
    {
        return parent::select($this->getMembersSQL($check_id));
    }

    public function getMembersSQL($check_id)
    {
        $strSql = "";
        $strSql .= " SELECT HMAUD_AUDIT_MEMBER.\"MEMBER\", " . "\r\n";
        $strSql .= "   HSYAINMST.SYAIN_NM " . "\r\n";
        $strSql .= " FROM HMAUD_AUDIT_MEMBER " . "\r\n";
        $strSql .= " LEFT JOIN HSYAINMST " . "\r\n";
        $strSql .= " ON HMAUD_AUDIT_MEMBER.\"MEMBER\" = HSYAINMST.SYAIN_NO " . "\r\n";
        $strSql .= " WHERE CHECK_ID                 = '" . $check_id . "' " . "\r\n";
        $strSql .= " AND \"ROLE\"                     = 1 " . "\r\n";

        return $strSql;
    }

    public function getsyains()
    {
        return parent::select($this->getsyainsSQL());
    }

    public function getsyainsSQL()
    {
        $strSql = "";
        $strSql .= " SELECT SYAIN_NO, " . "\r\n";
        $strSql .= "   SYAIN_NM " . "\r\n";
        $strSql .= " FROM HSYAINMST  " . "\r\n";
        // $strSql .= " WHERE TAISYOKU_DATE IS NOT NULL " . "\r\n";

        return $strSql;
    }

    public function updMainData($mainData)
    {
        return parent::update($this->updMainDataSQL($mainData));
    }

    public function updMainDataSQL($mainData)
    {
        $strSql = "";
        $strSql .= " UPDATE HMAUD_AUDIT_MAIN SET  " . "\r\n";
        $strSql .= " PLAN_DT =  " . ($mainData['PLAN_DT'] !== "" ? "TO_DATE('" . $mainData['PLAN_DT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ,PLAN_TIME =  " . ($mainData['PLAN_TIME'] !== "" ? "'" . $mainData['PLAN_TIME'] . "' " : " null ") . "\r\n";
        $strSql .= " ,PLAN_LIMIT =  " . ($mainData['PLAN_LIMIT'] !== "" ? "TO_DATE('" . $mainData['PLAN_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ,REPORT0_LIMIT =  " . ($mainData['REPORT0_LIMIT'] !== "" ? "TO_DATE('" . $mainData['REPORT0_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ,REPORT1_LIMIT =  " . ($mainData['REPORT1_LIMIT'] !== "" ? "TO_DATE('" . $mainData['REPORT1_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ,REPORT2_LIMIT =  " . ($mainData['REPORT2_LIMIT'] !== "" ? "TO_DATE('" . $mainData['REPORT2_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ,CHECK1_LIMIT =  " . ($mainData['CHECK1_LIMIT'] !== "" ? "TO_DATE('" . $mainData['CHECK1_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ,CHECK2_LIMIT =  " . ($mainData['CHECK2_LIMIT'] !== "" ? "TO_DATE('" . $mainData['CHECK2_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ,AUDIT_MEET_DT =  " . ($mainData['AUDIT_MEET_DT'] !== "" ? "TO_DATE('" . $mainData['AUDIT_MEET_DT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ,UPD_DATE = SYSDATE " . "\r\n";
        $strSql .= " ,UPD_SYA_CD =  '" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSql .= " WHERE CHECK_ID = '" . $mainData['CHECK_ID'] . "'  " . "\r\n";

        return $strSql;
    }

    public function memberDel($checkId)
    {
        return parent::delete($this->memberDelSQL($checkId));
    }

    public function memberDelSQL($checkId)
    {
        $strSql = "";
        $strSql .= " DELETE FROM HMAUD_AUDIT_MEMBER " . "\r\n";
        $strSql .= " WHERE CHECK_ID = '" . $checkId . "' " . "\r\n";
        // 20230103 YIN UPD S
        // $strSql .= " AND \"ROLE\" IN (1,2,3,4,5,6,7) " . "\r\n";
        // 20250403 YIN UPD S
        // $strSql .= " AND \"ROLE\" IN (1,2,3,4,5,6,7,8) " . "\r\n";
        $strSql .= " AND \"ROLE\" IN (1,2,3,4,5,6,7,8,9) " . "\r\n";
        // 20250403 YIN UPD E
        // 20230103 YIN UPD E

        return $strSql;
    }

    public function maxMainData()
    {
        return parent::select($this->maxMainDataSQL());
    }

    public function maxMainDataSQL()
    {
        $strSql = "";
        $strSql .= " SELECT MAX(TO_NUMBER(CHECK_ID)) AS CHECK_ID " . "\r\n";
        $strSql .= " FROM HMAUD_AUDIT_MAIN " . "\r\n";

        return $strSql;
    }

    public function insMainData($mainData)
    {
        return parent::insert($this->insMainDataSQL($mainData));
    }

    public function insMainDataSQL($mainData)
    {
        $strSql = "";
        $strSql .= " INSERT " . "\r\n";
        $strSql .= " INTO HMAUD_AUDIT_MAIN " . "\r\n";
        $strSql .= "   ( " . "\r\n";
        $strSql .= "     CHECK_ID " . "\r\n";
        $strSql .= "     ,COURS " . "\r\n";
        $strSql .= "     ,TERRITORY " . "\r\n";
        $strSql .= "     ,KYOTEN_CD " . "\r\n";
        $strSql .= "     ,PLAN_DT " . "\r\n";
        $strSql .= "     ,PLAN_TIME " . "\r\n";
        $strSql .= "     ,PLAN_LIMIT " . "\r\n";
        $strSql .= "     ,REPORT0_LIMIT " . "\r\n";
        $strSql .= "     ,REPORT1_LIMIT " . "\r\n";
        $strSql .= "     ,REPORT2_LIMIT " . "\r\n";
        $strSql .= "     ,CHECK1_LIMIT " . "\r\n";
        $strSql .= "     ,CHECK2_LIMIT " . "\r\n";
        $strSql .= "     ,AUDIT_MEET_DT " . "\r\n";
        $strSql .= "     ,CREATE_DATE " . "\r\n";
        $strSql .= "     ,CREATE_SYA_CD " . "\r\n";
        $strSql .= "     ,UPD_DATE " . "\r\n";
        $strSql .= "     ,UPD_SYA_CD " . "\r\n";
        $strSql .= "   ) " . "\r\n";
        $strSql .= "   VALUES " . "\r\n";
        $strSql .= "   ( " . "\r\n";
        $strSql .= " '" . $mainData['CHECK_ID'] . "' " . "\r\n";
        $strSql .= " , " . $mainData['cour'] . " " . "\r\n";
        $strSql .= " ,'" . $mainData['territory'] . "' " . "\r\n";
        $strSql .= " ,'" . $mainData['kyotenCD'] . "' " . "\r\n";
        $strSql .= " ," . ($mainData['PLAN_DT'] !== "" ? "TO_DATE('" . $mainData['PLAN_DT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ," . ($mainData['PLAN_TIME'] !== "" ? "'" . $mainData['PLAN_TIME'] . "' " : " null ") . "\r\n";
        $strSql .= " ," . ($mainData['PLAN_LIMIT'] !== "" ? "TO_DATE('" . $mainData['PLAN_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ," . ($mainData['REPORT0_LIMIT'] !== "" ? "TO_DATE('" . $mainData['REPORT0_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ," . ($mainData['REPORT1_LIMIT'] !== "" ? "TO_DATE('" . $mainData['REPORT1_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ," . ($mainData['REPORT2_LIMIT'] !== "" ? "TO_DATE('" . $mainData['REPORT2_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ," . ($mainData['CHECK1_LIMIT'] !== "" ? "TO_DATE('" . $mainData['CHECK1_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ," . ($mainData['CHECK2_LIMIT'] !== "" ? "TO_DATE('" . $mainData['CHECK2_LIMIT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ," . ($mainData['AUDIT_MEET_DT'] !== "" ? "TO_DATE('" . $mainData['AUDIT_MEET_DT'] . "','YYYY/MM/DD') " : " null ") . "\r\n";
        $strSql .= " ,SYSDATE" . "\r\n";
        $strSql .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSql .= " ,SYSDATE" . "\r\n";
        $strSql .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSql .= "   ) " . "\r\n";

        return $strSql;
    }

    public function insMemberData($memberData, $checkId)
    {
        return parent::insert($this->insMemberDataSQL($memberData, $checkId));
    }

    public function insMemberDataSQL($memberData, $checkId)
    {
        $strSql = "";
        $strSql .= " INSERT " . "\r\n";
        $strSql .= " INTO HMAUD_AUDIT_MEMBER " . "\r\n";
        $strSql .= "   ( " . "\r\n";
        $strSql .= "     CHECK_MEMBER_ID " . "\r\n";
        $strSql .= "     ,CHECK_ID " . "\r\n";
        $strSql .= "     ,\"ROLE\" " . "\r\n";
        $strSql .= "     ,\"MEMBER\" " . "\r\n";
        $strSql .= "     ,CREATE_DATE " . "\r\n";
        $strSql .= "     ,CREATE_SYA_CD " . "\r\n";
        $strSql .= "     ,UPD_DATE " . "\r\n";
        $strSql .= "     ,UPD_SYA_CD " . "\r\n";
        $strSql .= "   ) " . "\r\n";
        $strSql .= "   VALUES " . "\r\n";
        $strSql .= "   ( " . "\r\n";
        $strSql .= "     (SELECT MAX(TO_NUMBER(CHECK_MEMBER_ID)) +1 AS CHECK_MEMBER_ID " . "\r\n";
        $strSql .= "       FROM HMAUD_AUDIT_MEMBER " . "\r\n";
        $strSql .= "     ) " . "\r\n";
        $strSql .= "     , " . "\r\n";
        $strSql .= " '" . $checkId . "', " . "\r\n";
        $strSql .= " '" . $memberData['ROLE'] . "', " . "\r\n";
        $strSql .= " '" . $memberData['MEMBER'] . "' " . "\r\n";
        $strSql .= " ,SYSDATE" . "\r\n";
        $strSql .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSql .= " ,SYSDATE" . "\r\n";
        $strSql .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSql .= "   ) " . "\r\n";

        return $strSql;
    }

    public function getHeaderData($checkId)
    {
        return parent::select($this->getHeaderDataSQL($checkId));
    }

    public function getHeaderDataSQL($checkId)
    {
        $strSql = "";
        $strSql .= " SELECT * FROM HMAUD_AUDIT_REPORT_HEAD " . "\r\n";
        $strSql .= " WHERE CHECK_ID = '" . $checkId . "' " . "\r\n";

        return $strSql;
    }

    public function insHeaderData($checkId)
    {
        return parent::insert($this->insHeaderDataSQL($checkId));
    }

    public function insHeaderDataSQL($checkId)
    {
        $strSql = "";
        $strSql .= " INSERT " . "\r\n";
        $strSql .= " INTO HMAUD_AUDIT_REPORT_HEAD " . "\r\n";
        $strSql .= "   ( " . "\r\n";
        $strSql .= "     REPORT_ID " . "\r\n";
        $strSql .= "     ,CHECK_ID " . "\r\n";
        $strSql .= "     ,STATUS " . "\r\n";
        $strSql .= "     ,CREATE_DATE " . "\r\n";
        $strSql .= "     ,CREATE_SYA_CD " . "\r\n";
        $strSql .= "     ,UPD_DATE " . "\r\n";
        $strSql .= "     ,UPD_SYA_CD " . "\r\n";
        $strSql .= "   ) " . "\r\n";
        $strSql .= "   VALUES " . "\r\n";
        $strSql .= "   ( " . "\r\n";
        $strSql .= "     (SELECT MAX(REPORT_ID) +1 AS REPORT_ID " . "\r\n";
        $strSql .= "       FROM HMAUD_AUDIT_REPORT_HEAD " . "\r\n";
        $strSql .= "     ) " . "\r\n";
        $strSql .= "     , " . "\r\n";
        $strSql .= " '" . $checkId . "', " . "\r\n";
        $strSql .= " '00' " . "\r\n";
        $strSql .= " ,SYSDATE" . "\r\n";
        $strSql .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSql .= " ,SYSDATE" . "\r\n";
        $strSql .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSql .= "   ) " . "\r\n";

        return $strSql;
    }

}