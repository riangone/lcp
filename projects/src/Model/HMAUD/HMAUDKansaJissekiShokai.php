<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20230103           機能追加　　　　　　          20221226_内部統制_仕様変更        YIN
 * 20240313                       画面上の表記「常務」を「取締役」に変更お願いします  caina
 * 20240612           要望対応            20240611_内部統制_要望対応               YIN
 * 20250219           機能変更               20250219_内部統制_改修要望.xlsx                    LHB
 * 20250403           機能変更               202504_内部統制_要望.xlsx               lujunxia
 * 20250409           機能変更               202504_内部統制_要望.xlsx               lujunxia
 * 20251224     「副社長」——> 「社長」      202512_内部統制_変更要望.xlsx         YIN
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
class HMAUDKansaJissekiShokai extends ClsComDb
{
    //監査補助人
    function getAuditSubSql($SYAIN_NO)
    {
        $strSQL = "SELECT SYAIN_NO FROM HMAUD_MST_ADMIN WHERE SYAIN_NO='@SYAIN_NO'" . "\r\n";
        $strSQL = str_replace("@SYAIN_NO", $SYAIN_NO, $strSQL);

        return parent::select($strSQL);
    }

    //20230314 LIU INS S
    function getUser($SYAIN_NO)
    {
        $strSQL = "SELECT SYAIN_NO FROM HMAUD_MST_VIEWER WHERE SYAIN_NO='@SYAIN_NO'" . "\r\n";
        $strSQL = str_replace("@SYAIN_NO", $SYAIN_NO, $strSQL);

        return parent::select($strSQL);
    }

    //20230314 LIU INS E
    // 20250403 lujunxia del s
    //役割
    // function getUserRoleSql($userId)
    // {
    // 	$strSQL = "";
    // 	$strSQL .= "SELECT ROLE " . "\r\n";
    // 	$strSQL .= "FROM HMAUD_AUDIT_MEMBER" . "\r\n";
    // 	$strSQL .= "WHERE MEMBER='@MEMBER'" . "\r\n";

    // 	$strSQL = str_replace("@MEMBER", $userId, $strSQL);

    // 	return parent::select($strSQL);
    // }
    // 20250403 lujunxia del e

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

    function getDataSql($postData, $audit, $login_user)
    {

        $strSQL = "";
        $strSQL .= "SELECT HAM.CHECK_ID," . "\r\n";
        $strSQL .= "  HAM.KYOTEN_CD," . "\r\n";
        $strSQL .= "  HMK.KYOTEN_NAME," . "\r\n";
        $strSQL .= "  (" . "\r\n";
        $strSQL .= "  CASE HARH.STATUS" . "\r\n";
        $strSQL .= "    WHEN '00'" . "\r\n";
        $strSQL .= "    THEN '0.未実施'" . "\r\n";
        $strSQL .= "    WHEN '01'" . "\r\n";
        $strSQL .= "    THEN '1.監査実績入力済'" . "\r\n";
        $strSQL .= "    WHEN '02'" . "\r\n";
        $strSQL .= "    THEN '2.指摘事項入力済'" . "\r\n";
        $strSQL .= "    WHEN '03'" . "\r\n";
        $strSQL .= "    THEN '3.改善報告書担当確認済'" . "\r\n";
        $strSQL .= "    WHEN '04'" . "\r\n";
        $strSQL .= "    THEN '4.改善取組入力済'" . "\r\n";
        $strSQL .= "    WHEN '05'" . "\r\n";
        $strSQL .= "    THEN '5.領域責任者確認済'" . "\r\n";
        $strSQL .= "    WHEN '06'" . "\r\n";
        $strSQL .= "    THEN '6.キーマン確認済'" . "\r\n";
        $strSQL .= "    WHEN '07'" . "\r\n";
        $strSQL .= "    THEN '7.総括責任者確認済'" . "\r\n";
        $strSQL .= "    WHEN '08'" . "\r\n";
        // 20230103 YIN UPD S
        // $strSQL .= "    THEN '8.社長確認済'" . "\r\n";
        //20240313 caina upd s
        // $strSQL .= "    THEN '8.常務確認済'" . "\r\n";
        $strSQL .= "    THEN '8.取締役確認済'" . "\r\n";
        //20240313 caina upd e
        // 20250403 lujunxia upd s
        $strSQL .= "    WHEN '09'" . "\r\n";
        //$strSQL .= "    THEN '9.社長確認済'" . "\r\n";
        $strSQL .= "    THEN '9.社長確認済'" . "\r\n";
        $strSQL .= "    WHEN '10'" . "\r\n";
        $strSQL .= "    THEN '10.社長確認済'" . "\r\n";
        // 20250403 lujunxia upd e
        // 20240612 YIN INS S
        $strSQL .= "    WHEN '91'" . "\r\n";
        $strSQL .= "    THEN '91.差戻（監査人）'" . "\r\n";
        $strSQL .= "    WHEN '94'" . "\r\n";
        $strSQL .= "    THEN '94.差戻（改善取組責任者）'" . "\r\n";
        $strSQL .= "    WHEN '95'" . "\r\n";
        $strSQL .= "    THEN '95.差戻（各領域責任者）'" . "\r\n";
        $strSQL .= "    WHEN '96'" . "\r\n";
        $strSQL .= "    THEN '96.差戻（キーマン）'" . "\r\n";
        $strSQL .= "    WHEN '97'" . "\r\n";
        $strSQL .= "    THEN '97.差戻（総括責任者）'" . "\r\n";
        $strSQL .= "    WHEN '98'" . "\r\n";
        $strSQL .= "    THEN '98.差戻（取締役）'" . "\r\n";
        // 20240612 YIN INS E
        // 20230103 YIN UPD E
        $strSQL .= "    WHEN '99'" . "\r\n";
        // 20230103 YIN UPD S
        // $strSQL .= "    THEN '9.差戻'" . "\r\n";
        // 20250403 lujunxia upd s
        //$strSQL .= "    THEN '99.差戻'" . "\r\n";
        $strSQL .= "    THEN '99.差戻（社長）'" . "\r\n";
        // 20250403 lujunxia upd e
        // 20230103 YIN UPD E
        $strSQL .= "    ELSE ''" . "\r\n";
        $strSQL .= "  END) AS STATUSVAL," . "\r\n";
        $strSQL .= "  HAM.TERRITORY," . "\r\n";
        $strSQL .= "  (" . "\r\n";
        $strSQL .= "  CASE HAM.TERRITORY" . "\r\n";
        $strSQL .= "    WHEN '1'" . "\r\n";
        $strSQL .= "    THEN '営業'" . "\r\n";
        $strSQL .= "    WHEN '2'" . "\r\n";
        $strSQL .= "    THEN 'サービス'" . "\r\n";
        $strSQL .= "    WHEN '3'" . "\r\n";
        $strSQL .= "    THEN '管理'" . "\r\n";
        $strSQL .= "    WHEN '4'" . "\r\n";
        $strSQL .= "    THEN '業売'" . "\r\n";
        $strSQL .= "    WHEN '5'" . "\r\n";
        $strSQL .= "    THEN '業売管理'" . "\r\n";
        // 20250219 LHB INS S
        $strSQL .= "    WHEN '6'" . "\r\n";
        $strSQL .= "    THEN 'カーセブン'" . "\r\n";
        // 20250219 LHB INS E
        $strSQL .= "    ELSE ''" . "\r\n";
        $strSQL .= " END) AS TERRITORYVAL," . "\r\n";
        // 20250403 lujunxia upd s
        // $strSQL .= "  TO_CHAR(HAM.PLAN_DT,'YYYY/MM/DD') AS PLAN_DT," . "\r\n";
        // $strSQL .= "  TO_CHAR(HAM.CHECK_DT,'YYYY/MM/DD') AS CHECK_DT," . "\r\n";
        $strSQL .= "  TO_CHAR(HAM.PLAN_DT,'MM/DD') AS PLAN_DT," . "\r\n";
        $strSQL .= "  TO_CHAR(HAM.CHECK_DT,'MM/DD') AS CHECK_DT," . "\r\n";
        // 20250403 lujunxia upd e
        // 20250409 lujunxia upd s
        // 監査実施者の姓のみ取得する
        //$strSQL .= "  (SELECT RTRIM(xmlagg(xmlparse(content SYAIN_NM" . "\r\n";
        $strSQL .= "  (SELECT RTRIM(xmlagg(xmlparse(content SYAIN_KNJ_SEI" . "\r\n";
        // 20250409 lujunxia upd e
        $strSQL .= "      ||'、' wellformed)).getclobval(),'、')" . "\r\n";
        $strSQL .= "    FROM HMAUD_AUDIT_MEMBER" . "\r\n";
        // 20250409 lujunxia upd s
        // 監査実施者名の取得先をHSYAINMSTからM29MA4に変更
        // $strSQL .= "    LEFT JOIN HSYAINMST" . "\r\n";
        // $strSQL .= "    ON HSYAINMST.SYAIN_NO             = HMAUD_AUDIT_MEMBER.MEMBER" . "\r\n";
        $strSQL .= "    LEFT JOIN M29MA4" . "\r\n";
        $strSQL .= "    ON M29MA4.SYAIN_NO             = HMAUD_AUDIT_MEMBER.MEMBER" . "\r\n";
        // 20250409 lujunxia upd e
        $strSQL .= "    WHERE HMAUD_AUDIT_MEMBER.CHECK_ID = HAM.CHECK_ID" . "\r\n";
        $strSQL .= "    AND HMAUD_AUDIT_MEMBER.ROLE='1'" . "\r\n";
        $strSQL .= "    ) SYAIN_NM," . "\r\n";
        // 20250403 lujunxia upd s
        // $strSQL .= "  TO_CHAR(HARH.COMP_DT1,'YYYY/MM/DD') AS COMP_DT1," . "\r\n";
        // $strSQL .= "  TO_CHAR(HARH.COMP_DT2,'YYYY/MM/DD') AS COMP_DT2," . "\r\n";
        // $strSQL .= "  TO_CHAR(HARH.COMP_DT3,'YYYY/MM/DD') AS COMP_DT3," . "\r\n";
        // $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT2,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT2," . "\r\n";
        // $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT3,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT3," . "\r\n";
        // $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT4,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT4," . "\r\n";
        // $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT5,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT5," . "\r\n";
        // 20230103 YIN INS S
        // $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT6,'YYYY/MM/DD') AS RESPONSIBLE_CHECK_DT6," . "\r\n";
        // 20230103 YIN INS E
        $strSQL .= "  TO_CHAR(HARH.COMP_DT1,'MM/DD') AS COMP_DT1," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.COMP_DT2,'MM/DD') AS COMP_DT2," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.COMP_DT3,'MM/DD') AS COMP_DT3," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT2,'MM/DD') AS RESPONSIBLE_CHECK_DT2," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT3,'MM/DD') AS RESPONSIBLE_CHECK_DT3," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT4,'MM/DD') AS RESPONSIBLE_CHECK_DT4," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT5,'MM/DD') AS RESPONSIBLE_CHECK_DT5," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT6,'MM/DD') AS RESPONSIBLE_CHECK_DT6," . "\r\n";
        $strSQL .= "  TO_CHAR(HARH.RESPONSIBLE_CHECK_DT7,'MM/DD') AS RESPONSIBLE_CHECK_DT7," . "\r\n";
        // 20250403 lujunxia upd e
        $strSQL .= "  HAM.COURS" . "\r\n";
        $strSQL .= "FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $strSQL .= "INNER JOIN HMAUD_MST_KTN HMK" . "\r\n";
        $strSQL .= "ON HMK.KYOTEN_CD = HAM.KYOTEN_CD" . "\r\n";
        $strSQL .= "AND HMK.TERRITORY = HAM.TERRITORY" . "\r\n";
        if ($audit !== 'sub_audit') {
            $strSQL .= " INNER JOIN " . "\r\n";
            $strSQL .= "   (SELECT HMAUD_AUDIT_MEMBER.CHECK_ID, " . "\r\n";
            $strSQL .= "     HMAUD_AUDIT_MEMBER.MEMBER " . "\r\n";
            $strSQL .= "   FROM HMAUD_AUDIT_MEMBER , " . "\r\n";
            $strSQL .= "     HMAUD_AUDIT_MAIN " . "\r\n";
            $strSQL .= "   WHERE HMAUD_AUDIT_MEMBER.CHECK_ID = HMAUD_AUDIT_MAIN.CHECK_ID " . "\r\n";
            $strSQL .= "   GROUP BY HMAUD_AUDIT_MEMBER.CHECK_ID, " . "\r\n";
            $strSQL .= "     HMAUD_AUDIT_MEMBER.MEMBER " . "\r\n";
            $strSQL .= "   ) HMB " . "\r\n";
            $strSQL .= " ON HAM.CHECK_ID = HMB.CHECK_ID " . "\r\n";
            $strSQL .= " AND HMB.MEMBER  = '@USER' " . "\r\n";
            $strSQL = str_replace("@USER", $login_user, $strSQL);
        }

        $strSQL .= "LEFT JOIN HMAUD_AUDIT_REPORT_HEAD HARH" . "\r\n";
        $strSQL .= "ON HARH.CHECK_ID=HAM.CHECK_ID" . "\r\n";
        $strSQL .= "WHERE    1=1" . "\r\n";
        if ($postData['COURS'] != '') {
            //クール
            $strSQL .= "AND HAM.COURS='@COURS'" . "\r\n";
            $strSQL = str_replace("@COURS", $postData['COURS'], $strSQL);
        }
        if ($postData['KYOTEN_CD'] != '') {
            //拠点
            $strSQL .= "AND HAM.KYOTEN_CD='@KYOTEN_CD'" . "\r\n";
            $strSQL = str_replace("@KYOTEN_CD", $postData['KYOTEN_CD'], $strSQL);
            //領域
            $strSQL .= "AND HAM.TERRITORY ='@TERRITORY'" . "\r\n";
            $strSQL = str_replace("@TERRITORY", $postData['TERRITORY'], $strSQL);
        }
        if ($postData['STATUS'] != '') {
            //ステータス
            $strSQL .= "AND HARH.STATUS='@STATUS'" . "\r\n";
            $strSQL = str_replace("@STATUS", $postData['STATUS'], $strSQL);
        }
        if ($postData['TERRITORYArr'] != '') {
            //領域
            $strSQL .= "AND HAM.TERRITORY IN(@TERRITORY)" . "\r\n";
            $strSQL = str_replace("@TERRITORY", $postData['TERRITORYArr'], $strSQL);
        }
        // 20250219 LHB INS S
        else {
            if ($postData['COURS'] != '' && (int) $postData['COURS'] < 18) {
                $strSQL .= "AND HAM.TERRITORY NOT IN('6')" . "\r\n";
            }
        }
        // 20250219 LHB INS E
        $strSQL .= "ORDER BY HAM.KYOTEN_CD,HAM.TERRITORY" . "\r\n";

        return parent::select($strSQL);
    }

}
