<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20230103           機能追加　　　　　　          20221226_内部統制_仕様変更        YIN
 * 20230905           機能追加                     内部統制_第3弾.xlsx NO2         lujunxia
 * 20240313                    画面上の表記「常務」を「取締役」に変更お願いします    caina
 * 20240530           機能追加      メール通知機能にて、クールと領域名も一緒に出力する   YIN
 * 20250403           機能追加       		     202504_内部統制_要望.xlsx        CI
 * 20251016           機能追加      202510_内部統制システム_仕様変更対応.xlsx         YIN
 * 20251224     「副社長」——> 「社長」      202512_内部統制_変更要望.xlsx         YIN
 * --------------------------------------------------------------------------------------------
 */
use PHPMailer\PHPMailer\PHPMailer;
try {
    include_once dirname(__DIR__) . '/Login/Component/Mailer/PHPMailer/Exception.php';
    include_once dirname(__DIR__) . '/Login/Component/Mailer/PHPMailer/PHPMailer.php';
    include_once dirname(__DIR__) . '/Login/Component/Mailer/PHPMailer/SMTP.php';
    include_once dirname(__DIR__) . '/Login/Component/Mailer/mail.inc';
    $log_file_path = dirname(dirname(dirname(dirname(__FILE__))));
    $file = $log_file_path . "/logs/email.log";
    $date = date("Y-m-d H:i:s");
    file_put_contents($file, $date . "　内部統制メール通知処理開始" . PHP_EOL, FILE_APPEND);

    // パス取得
    $strPath = dirname(dirname(dirname(__FILE__)));
    $filename = $strPath . "/Model/Component/" . 'HMDB.xml';
    // 値取得
    $xml = simplexml_load_file($filename);
    // XMLの取得
    $Ora = (array) $xml;
    $conn = oci_connect($Ora['userid'], $Ora['password'], $Ora['server'], "utf8");

    if (!$conn) {
        logError("内部統制システム　データベース接続に失敗しました　メール通知処理　異常終了");
        return FALSE;
    }

    // 1. 監査スケジュール設定の催促
    processScheduleReminder($conn);

    $phases = array(
        1 => array("role" => "1", "title" => "監査人が指摘事項を提示"),
        2 => array("role" => "2", "title" => "各領域の改善報告書担当の承認"),
        3 => array("role" => "3", "title" => "店舗が改善結果入力期限"),
        4 => array("role" => "4", "title" => "領域責任者の確認"),
        5 => array("role" => "5", "title" => "キーマン確認依頼"),
        6 => array("role" => "6", "title" => "総括責任者確認依頼"),
        7 => array("role" => "7", "title" => "取締役確認依頼"), // 20240313 常務→取締役
        // 20250403 CI UPD S
        8 => array("role" => "8", "title" => "社長確認依頼"),
        9 => array("role" => "9", "title" => "社長確認依頼")
        // 20250403 CI UPD E
    )

    ;

    foreach ($phases as $phase => $config) {
        processPhase($conn, $phase, $config["role"], $config["title"]);
    }

    // 2.監査人スケジュール登録の催促
    auditorScheduleReminder($conn);

    oci_close($conn);
    logInfo("内部統制システム　メール通知処理　正常終了");
    return TRUE;

} catch (Exception $e) {
    logError("内部統制システム　メール通知処理　エラー：" . $e->getMessage());
    return FALSE;
}

function processScheduleReminder($conn)
{
    $adminEmails = queryAdminEmails($conn);

    $sql = "";
    $sql .= "  SELECT HMK.KYOTEN_NAME," . "\r\n";
    $sql .= "    HAM.COURS" . "\r\n";
    // 20240530 YIN INS S
    $sql .= ",    CASE HAM.TERRITORY" . "\r\n";
    $sql .= "      WHEN '1'" . "\r\n";
    $sql .= "      THEN '営業'" . "\r\n";
    $sql .= "      WHEN '2'" . "\r\n";
    $sql .= "      THEN 'サービス'" . "\r\n";
    $sql .= "      WHEN '3'" . "\r\n";
    $sql .= "      THEN '管理'" . "\r\n";
    $sql .= "      WHEN '4'" . "\r\n";
    $sql .= "      THEN '業売'" . "\r\n";
    $sql .= "      WHEN '5'" . "\r\n";
    $sql .= "      THEN '業売管理'" . "\r\n";
    $sql .= "      ELSE ''" . "\r\n";
    $sql .= "    END AS TERRITORY" . "\r\n";
    // 20240530 YIN INS E
    $sql .= "  FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
    $sql .= "  INNER JOIN HMAUD_MST_KTN HMK" . "\r\n";
    $sql .= "  ON HMK.KYOTEN_CD       =HAM.KYOTEN_CD" . "\r\n";
    $sql .= "  AND HMK.TERRITORY      =HAM.TERRITORY" . "\r\n";
    $sql .= "  INNER JOIN HMAUD_MST_COUR MSTC" . "\r\n";
    $sql .= "  ON MSTC.START_DT <= SYSDATE  " . "\r\n";
    $sql .= "  AND MSTC.END_DT >= SYSDATE  " . "\r\n";
    $sql .= "  AND MSTC.COURS     = HAM.COURS  " . "\r\n";
    $sql .= "  WHERE HAM.PLAN_DT     IS NOT NULL" . "\r\n";
    $sql .= "  AND HAM.PLAN_DT-1     <= SYSDATE" . "\r\n";
    $sql .= "  AND HAM.PLAN_DT+1      > SYSDATE" . "\r\n";
    $sql .= "  AND(HAM.REPORT0_LIMIT IS NULL" . "\r\n";
    $sql .= "  OR HAM.REPORT1_LIMIT  IS NULL" . "\r\n";
    $sql .= "  OR HAM.REPORT2_LIMIT  IS NULL" . "\r\n";
    $sql .= "  OR HAM.CHECK1_LIMIT   IS NULL" . "\r\n";
    $sql .= "  OR HAM.CHECK2_LIMIT   IS NULL" . "\r\n";
    $sql .= "  OR HAM.AUDIT_MEET_DT  IS NULL)" . "\r\n";

    $results = executeQuery($conn, $sql, "監査スケジュール設定の催促");
    //監査スケジュール設定の催促
    if (count($results) > 0) {
        $subject = "【内部統制システム】【催促】監査スケジュール入力依頼";
        $body = "";
        $kyoten = "";

        foreach ($results as $row) {
            $kyoten .= $row['KYOTEN_NAME'] . " ";
            $body .= "クール：" . $row['COURS'] . "  " . "拠点：" . $row['KYOTEN_NAME'] . "・" . $row['TERRITORY'] . "<br/>";
        }

        $body .= "監査予定日の前日に スケジュール未設定" . "<br/><br/>";

        foreach ($adminEmails as $email) {
            if ($email['SYAIN_MAL_ADR']) {
                sendEmail($email['SYAIN_MAL_ADR'], $subject, $body);
                logInfo("監査スケジュール設定の催促 対象：" . $kyoten . "、送付先：" . $email['SYAIN_MAL_ADR']);
            }
        }
    } else {
        logInfo("監査スケジュール設定の催促 該当データなし");
    }
}

function processPhase($conn, $phase, $role, $title)
{
    $sql = "";
    $sql .= "  SELECT HAME.MEMBER," . "\r\n";
    $sql .= "    M.SYAIN_MAL_ADR," . "\r\n";
    $sql .= "    H.KYOTEN_NAME," . "\r\n";
    $sql .= "    H.KYOTEN_CD," . "\r\n";
    // 20240530 YIN INS S
    $sql .= "    H.COURS," . "\r\n";
    $sql .= "    CASE H.TERRITORY" . "\r\n";
    $sql .= "      WHEN '1'" . "\r\n";
    $sql .= "      THEN '営業'" . "\r\n";
    $sql .= "      WHEN '2'" . "\r\n";
    $sql .= "      THEN 'サービス'" . "\r\n";
    $sql .= "      WHEN '3'" . "\r\n";
    $sql .= "      THEN '管理'" . "\r\n";
    $sql .= "      WHEN '4'" . "\r\n";
    $sql .= "      THEN '業売'" . "\r\n";
    $sql .= "      WHEN '5'" . "\r\n";
    $sql .= "      THEN '業売管理'" . "\r\n";
    $sql .= "      ELSE ''" . "\r\n";
    $sql .= "    END AS TERRITORY," . "\r\n";
    // 20240530 YIN INS E
    $sql .= "   HAME.MEMBER" . "\r\n";
    $sql .= "  FROM HMAUD_AUDIT_MEMBER HAME" . "\r\n";
    $sql .= "  LEFT JOIN M29MA4 M" . "\r\n";
    $sql .= "  ON M.SYAIN_NO =HAME.MEMBER" . "\r\n";
    $sql .= "  INNER JOIN" . "\r\n";
    $sql .= "    (SELECT HAM.CHECK_ID," . "\r\n";
    $sql .= "      HMK.KYOTEN_NAME," . "\r\n";
    $sql .= "      HMK.KYOTEN_CD" . "\r\n";
    // 20240530 YIN INS S
    $sql .= ",      MSTC.COURS" . "\r\n";
    $sql .= ",      HAM.TERRITORY" . "\r\n";
    // 20240530 YIN INS E
    if ($role == "1") {
        $sql .= "    FROM HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $sql .= "    INNER JOIN HMAUD_MST_COUR MSTC" . "\r\n";
        $sql .= "    ON MSTC.START_DT <= SYSDATE  " . "\r\n";
        $sql .= "    AND MSTC.END_DT >= SYSDATE  " . "\r\n";
        $sql .= "    AND MSTC.COURS     = HAM.COURS  " . "\r\n";
        $sql .= "    LEFT JOIN HMAUD_AUDIT_DETAIL HAD" . "\r\n";
        $sql .= "    ON HAD.COURS     = HAM.COURS" . "\r\n";
        $sql .= "    AND HAD.TERRITORY= HAM.TERRITORY" . "\r\n";
        $sql .= "    LEFT JOIN HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $sql .= "    ON HAR.CHECK_LST_ID=HAD.ROW_NO" . "\r\n";
        $sql .= "    AND HAR.CHECK_ID   =HAM.CHECK_ID" . "\r\n";
    } else {

        $sql .= "    FROM HMAUD_AUDIT_DETAIL HAD" . "\r\n";
        $sql .= "    INNER JOIN HMAUD_AUDIT_MAIN HAM" . "\r\n";
        $sql .= "    ON HAM.COURS     = HAD.COURS" . "\r\n";
        $sql .= "    AND HAM.TERRITORY= HAD.TERRITORY" . "\r\n";
        $sql .= "    INNER JOIN HMAUD_MST_COUR MSTC" . "\r\n";
        $sql .= "    ON MSTC.START_DT <= SYSDATE  " . "\r\n";
        $sql .= "    AND MSTC.END_DT >= SYSDATE  " . "\r\n";
        $sql .= "    AND MSTC.COURS     = HAM.COURS  " . "\r\n";
        $sql .= "    INNER JOIN HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $sql .= "    ON HAR.CHECK_LST_ID=HAD.ROW_NO" . "\r\n";
        $sql .= "    AND HAR.CHECK_ID=HAM.CHECK_ID" . "\r\n";
    }
    $sql .= "    LEFT JOIN HMAUD_AUDIT_REPORT_HEAD HARH" . "\r\n";
    $sql .= "    ON HARH.CHECK_ID=HAM.CHECK_ID" . "\r\n";
    $sql .= "    LEFT JOIN HMAUD_MST_KTN HMK" . "\r\n";
    $sql .= "    ON HMK.KYOTEN_CD                     =HAM.KYOTEN_CD" . "\r\n";
    $sql .= "    AND HMK.TERRITORY      =HAM.TERRITORY" . "\r\n";
    $sql .= "    WHERE " . getPhaseCondition($phase) . "\r\n";
    //20230905 lujunxia ins s
    $sql .= "    AND (HAD.EXPIRATION_DATE >= MSTC.START_DT OR HAD.EXPIRATION_DATE IS NULL)" . "\r\n";
    //20230905 lujunxia ins e
    if ($role == "1") {
        $sql .= "    AND TO_NUMBER(HARH.STATUS) < 2 " . "\r\n";
    } else {
        $sql .= "     AND TO_NUMBER(HARH.STATUS) = " . $phase . "\r\n";
    }

    $sql .= "    GROUP BY HAM.CHECK_ID ," . "\r\n";
    $sql .= "      HMK.KYOTEN_NAME," . "\r\n";
    $sql .= "      HMK.KYOTEN_CD" . "\r\n";
    // 20240530 YIN INS S
    $sql .= ",      HAM.TERRITORY" . "\r\n";
    $sql .= ",      MSTC.COURS" . "\r\n";
    // 20240530 YIN INS E
    $sql .= "    )H ON H.CHECK_ID =HAME.CHECK_ID" . "\r\n";
    // 20251016 YIN UPD S
    if ($role !== "7") {
        $sql .= "  WHERE HAME.ROLE = " . $role . "\r\n";
    } else {
        $sql .= "  WHERE HAME.ROLE = CASE WHEN H.COURS < 19 THEN " . $role . " ELSE " . $role + 1 . " END " . "\r\n";
    }
    // 20251016 YIN UPD E

    $results = executeQuery($conn, $sql, $title);

    if (count($results) > 0) {
        if ($role == "1") {
            $log_file_path = dirname(dirname(dirname(dirname(__FILE__))));
            $file = $log_file_path . "/logs/email.log";
            $date = date("Y-m-d H:i:s");
            file_put_contents($file, $date . json_encode($results) . PHP_EOL, FILE_APPEND);
        }

        $groupedResults = array();
        foreach ($results as $row) {
            $groupedResults[$row['MEMBER']]['syain_no'] = $row['MEMBER'];
            $groupedResults[$row['MEMBER']]['address'] = $row['SYAIN_MAL_ADR'];
            $groupedResults[$row['MEMBER']]['array'][] = $row;
        }
        $groupedResults = array_values($groupedResults);

        $mailTemplate = getMailTemplate($conn, $phase);
        // 20251016 YIN UPD S
        if ($phase == 7) {
            $mailTemplateCour19 = getMailTemplateCour19($conn, $phase);
        }

        foreach ($groupedResults as $member) {
            $cour18 = 0;
            $cour19 = 0;
            if ($member['address']) {
                $kyoten = "";
                $kyoten19 = "";
                $body19 = "";
                $body = "宛先：" . $member['syain_no'] . "<br/>";
                $body .= "本文：" . str_replace("\n", "<br/>", $mailTemplate['DESCRIPTION']) . "<br/>";
                $body .= "＜対象＞" . "<br/>";

                foreach ($member['array'] as $item) {
                    if ($phase !== 7) {
                        $body .= "クール：" . $item['COURS'] . "　拠点：" . $item['KYOTEN_CD'] . "　" .
                            $item['KYOTEN_NAME'] . "・" . $item['TERRITORY'] . "<br/>";
                        $kyoten .= $item['KYOTEN_NAME'] . " ";
                    } else {
                        if ($item['COURS'] < 19) {
                            $cour18 = 1;
                            $body .= "クール：" . $item['COURS'] . "　拠点：" . $item['KYOTEN_CD'] . "　" .
                                $item['KYOTEN_NAME'] . "・" . $item['TERRITORY'] . "<br/>";
                            $kyoten .= $item['KYOTEN_NAME'] . " ";
                        } else {
                            $cour19 = 1;
                            $body19 .= "クール：" . $item['COURS'] . "　拠点：" . $item['KYOTEN_CD'] . "　" .
                                $item['KYOTEN_NAME'] . "・" . $item['TERRITORY'] . "<br/>";
                            $kyoten19 .= $item['KYOTEN_NAME'] . " ";
                        }
                    }
                }
                if ($phase !== 7) {
                    sendEmail($member['address'], $mailTemplate['TITLE'], $body);
                    logInfo("$title 対象：" . $kyoten . "、送付先：" . $member['address']);
                } else {
                    if ($cour18 == 1) {
                        sendEmail($member['address'], $mailTemplate['TITLE'], $body);
                        logInfo("$title 対象：" . $kyoten . "、送付先：" . $member['address']);
                    }
                    if ($cour19 == 1) {
                        $body = "宛先：" . $member['syain_no'] . "<br/>";
                        $body .= "本文：" . str_replace("\n", "<br/>", $mailTemplateCour19['DESCRIPTION']) . "<br/>";
                        $body .= "＜対象＞" . "<br/>";
                        $body .= $body19;
                        sendEmail($member['address'], $mailTemplateCour19['TITLE'], $body);
                        logInfo("社長確認依頼 対象：" . $kyoten19 . "、送付先：" . $member['address']);
                    }
                }
            }
        }
        // 20251016 YIN UPD E
    } else {
        logInfo("$title 該当データなし");
    }
}

function getPhaseCondition($phase)
{
    $conditions = array(
        //監査人が指摘事項を提示
        1 => "HAM.PLAN_DT + 7 < sysdate",
        //各領域の改善報告書担当の承認
        2 => "HARH.COMP_DT1 + 7 < SYSDATE",
        //店舗が改善結果入力期限
        3 => "HARH.RESPONSIBLE_CHECK_DT0 + 7 < SYSDATE",
        //領域責任者の確認
        4 => "HARH.RESPONSIBLE_CHECK_DT0 + 7 < SYSDATE AND HAR.CHECK_DT + 7 < SYSDATE",
        //キーマン確認期限
        5 => "HARH.RESPONSIBLE_CHECK_DT2 + 7 < SYSDATE",
        //総括責任者確認依頼
        6 => "HARH.RESPONSIBLE_CHECK_DT3 + 7 < SYSDATE",
        //常務確認依頼
        7 => "HARH.RESPONSIBLE_CHECK_DT4 + 7 < SYSDATE",
        // 20250403 CI UPD S
        //副社長確認依頼
        8 => "HARH.RESPONSIBLE_CHECK_DT5 + 7 < SYSDATE",
        //社長確認依頼
        9 => "HARH.RESPONSIBLE_CHECK_DT6 + 7 < SYSDATE"
        // 20250403 CI UPD E
    )

    ;

    return $conditions[$phase];
}

function queryAdminEmails($conn)
{
    $sql = "";
    $sql .= "  SELECT M.SYAIN_MAL_ADR" . "\r\n";
    $sql .= "  FROM HMAUD_MST_ADMIN HMA" . "\r\n";
    $sql .= "  LEFT JOIN M29MA4 M ON M.SYAIN_NO=HMA.SYAIN_NO" . "\r\n";
    return executeQuery($conn, $sql, "内部統制システム　メール通知処理");
}

function getMailTemplate($conn, $phase)
{
    $sql = "";
    $sql .= "  SELECT TITLE," . "\r\n";
    $sql .= "     DESCRIPTION" . "\r\n";
    $sql .= "    FROM HMAUD_MST_MAIL" . "\r\n";
    $sql .= "   WHERE TYPE  = 2" . "\r\n";
    $sql .= "    AND PATTERN = 1" . "\r\n";
    $sql .= "     AND PHASE   = " . $phase . "\r\n";
    $result = executeQuery($conn, $sql, "内部統制システム　メール通知処理");
    return $result[0];
}
// 20251016 YIN INS S
function getMailTemplateCour19($conn, $phase)
{
    $sql = "";
    $sql .= "  SELECT TITLE," . "\r\n";
    $sql .= "     DESCRIPTION" . "\r\n";
    $sql .= "    FROM HMAUD_MST_MAIL" . "\r\n";
    $sql .= "   WHERE TYPE  = 2" . "\r\n";
    $sql .= "    AND PATTERN = 3" . "\r\n";
    $sql .= "     AND PHASE   = " . $phase . "\r\n";
    $result = executeQuery($conn, $sql, "内部統制システム　メール通知処理");
    return $result[0];
}
// 20251016 YIN INS E

function auditorScheduleReminder($conn)
{
    $sql = "";
    $sql .= " SELECT " . "\r\n";
    $sql .= "     SH.SYAIN_NO, " . "\r\n";
    $sql .= "     HM.EMAIL, " . "\r\n";
    $sql .= "     M.SYAIN_KNJ_SEI || '　' ||M.SYAIN_KNJ_MEI AS SYAIN_NAME " . "\r\n";
    $sql .= " FROM " . "\r\n";
    $sql .= "     HMAUD_AUDITOR_SCHEDULE_LIMIT SH " . "\r\n";
    $sql .= "     LEFT JOIN HMAUD_MST_AUDITOR HM " . "\r\n";
    $sql .= "       ON HM.SYAIN_NO = SH.SYAIN_NO " . "\r\n";
    $sql .= "     LEFT JOIN M29MA4 M " . "\r\n";
    $sql .= "       ON M.SYAIN_NO = SH.SYAIN_NO " . "\r\n";
    $sql .= " WHERE " . "\r\n";
    $sql .= "     NOT EXISTS ( " . "\r\n";
    $sql .= "         SELECT " . "\r\n";
    $sql .= "             SYAIN_NO " . "\r\n";
    $sql .= "         FROM " . "\r\n";
    $sql .= "             HMAUD_AUDITOR_SCHEDULE SD " . "\r\n";
    $sql .= "         WHERE " . "\r\n";
    $sql .= "                 SD.SYAIN_NO = SH.SYAIN_NO " . "\r\n";
    $sql .= "             AND TO_CHAR(SD.PLAN_DT, 'yyyymm') = TO_CHAR(SH.PLAN_DT, 'yyyymm') " . "\r\n";
    $sql .= "     ) " . "\r\n";
    $sql .= "         AND TO_CHAR(SH.PLAN_DT, 'yyyymm') = TO_CHAR(SYSDATE, 'yyyymm') " . "\r\n";
    $sql .= "         AND SH.PLAN_DT - SYSDATE < 2 " . "\r\n";
    $sql .= "         AND HM.ENABLED = '1' " . "\r\n";

    $results = executeQuery($conn, $sql, "監査人スケジュール登録の催促");

    if (count($results) > 0) {
        $subject = "【内部統制システム】監査人スケジュール登録依頼";
        foreach ($results as $row) {
            if ($row['EMAIL']) {
                $body = "日程調整期限の２日前です。監査人スケジュールを登録してください。";
                sendEmail($row['EMAIL'], $subject, $body);
                logInfo("監査人スケジュール登録の催促 対象：" . $row['SYAIN_NAME'] . "、送付先：" . $row['EMAIL']);
            }
        }
    } else {
        logInfo("監査人スケジュール登録の催促 該当データなし");
    }

}


function executeQuery($conn, $sql, $queryName)
{
    $stmt = oci_parse($conn, $sql);
    if (!$stmt) {
        $e = oci_error($conn);
        logError("$queryName エラー：［" . $e['message'] . "］");
        return array();
    }

    if (!oci_execute($stmt, OCI_COMMIT_ON_SUCCESS)) {
        $e = oci_error($stmt);
        logError("$queryName エラー：［" . $e['message'] . "］");
        return array();
    }

    $results = array();
    oci_fetch_all($stmt, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
    return $results;
}

/**
 * 邮件送信
 */
function sendEmail($to, $subject, $body)
{
    global $Host, $SMTPSecure, $Port, $Ora, $Username, $Password, $From, $FromName, $WordWrap, $CharSet, $Encoding;

    $mail = new PHPMailer();
    // 启用SMTP
    $mail->IsSMTP();
    $mail->Host = $Host;
    // sets the prefix to the servier
    $mail->SMTPSecure = $SMTPSecure;
    //SMTP服务器
    $mail->Port = $Port;
    //开启SMTP认证
    $mail->SMTPAuth = $Ora['SMTPAuth'];
    // SMTP username SMTP用户名
    //$mail->Username = $Username;
    // SMTP password SMTP密码
    //$mail->Password = $Password;
    //发件人地址
    $mail->From = $From;
    //发件人
    $mail->FromName = $FromName;
    // set word wrap to 50 characters
    $mail->WordWrap = $WordWrap;
    // set email format to HTML
    $mail->IsHTML(true);

    $bcc = $From;

    // 设置编码
    $mail->CharSet = $CharSet;
    $mail->Encoding = $Encoding;

    $mail->Subject = $subject;
    $mail->Body = $body;

    if (!$mail->AddAddress($to)) {
        logError("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }

    if (!$mail->AddBcc($bcc)) {
        logError("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }

    if (!$mail->Send()) {
        logError("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }

    $mail->ClearAllRecipients();
    return true;
}

function logInfo($message)
{
    $log_file_path = dirname(dirname(dirname(dirname(__FILE__))));
    $file = $log_file_path . "/logs/email.log";
    $date = date("Y-m-d H:i:s");
    file_put_contents($file, $date . "　" . $message . PHP_EOL, FILE_APPEND);
}

function logError($message)
{
    $log_file_path = dirname(dirname(dirname(dirname(__FILE__))));
    $file = $log_file_path . "/logs/email.log";
    $date = date("Y-m-d H:i:s");
    file_put_contents($file, $date . "　内部統制システム　" . $message . "　異常終了" . PHP_EOL, FILE_APPEND);
}
