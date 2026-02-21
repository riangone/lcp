<?php
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDReportInputHistory extends ClsComDb
{
    public function getMainData($postData)
    {
        return parent::select($this->getMainDataSQL($postData));
    }

    public function getHistoryData($postData)
    {
        return parent::select($this->getHistoryDataSQL($postData));
    }

    public function getMainDataSQL($postData)
    {
        $strSql = "";
        $strSql .= " SELECT " . "\r\n";
        $strSql .= "     HMAUD_AUDIT_MAIN.COURS, " . "\r\n";
        $strSql .= "     HMAUD_MST_KTN.KYOTEN_NAME, " . "\r\n";
        $strSql .= "     CASE HMAUD_AUDIT_MAIN.TERRITORY " . "\r\n";
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
        $strSql .= "     END AS TERRITORY " . "\r\n";
        $strSql .= " FROM  HMAUD_AUDIT_MAIN " . "\r\n";
        $strSql .= " INNER JOIN HMAUD_MST_KTN ON HMAUD_AUDIT_MAIN.KYOTEN_CD = HMAUD_MST_KTN.KYOTEN_CD " . "\r\n";
        $strSql .= " AND HMAUD_AUDIT_MAIN.TERRITORY = HMAUD_MST_KTN.TERRITORY " . "\r\n";
        $strSql .= " WHERE " . "\r\n";
        $strSql .= "     HMAUD_AUDIT_MAIN.CHECK_ID = '@CHECK_ID' " . "\r\n";
        $strSql = str_replace("@CHECK_ID", $postData['CHECK_ID'], $strSql);

        return $strSql;
    }

    public function getHistoryDataSQL($postData)
    {
        $strSql = "";
        $strSql .= " SELECT " . "\r\n";
        $strSql .= "     TO_CHAR(HARH.CHECK_DT, 'YYYY/MM/DD HH24:MI') AS CHECK_DT, " . "\r\n";
        $strSql .= "     HARH.REMARKS, " . "\r\n";
        $strSql .= "     HSM.SYAIN_NM " . "\r\n";
        $strSql .= " FROM " . "\r\n";
        $strSql .= "     HMAUD_AUDIT_REPORT_HISTORY HARH " . "\r\n";
        $strSql .= " LEFT JOIN HSYAINMST HSM" . "\r\n";
        $strSql .= " ON HARH.CHECK_TANTO = HSM.SYAIN_NO " . "\r\n";
        $strSql .= " WHERE " . "\r\n";
        $strSql .= "     HARH.CHECK_ID = '@CHECK_ID' " . "\r\n";
        $strSql .= " ORDER BY " . "\r\n";
        $strSql .= "     HARH.CHECK_DT DESC " . "\r\n";
        $strSql = str_replace("@CHECK_ID", $postData['CHECK_ID'], $strSql);

        return $strSql;
    }

}
