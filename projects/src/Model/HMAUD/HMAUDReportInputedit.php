<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20230103           機能追加　　　　　　          20221226_内部統制_仕様変更        YIN
 * 20240313                     画面上の表記「常務」を「取締役」に変更お願いします   caina
 * 20240612           機能追加      報告書入力で 差戻を実行する際、差戻先を ユーザーが選択可能にしてほしい    CI
 * 20250403           機能追加       		     202504_内部統制_要望.xlsx        CI
 * 20251224     「副社長」——> 「社長」      202512_内部統制_変更要望.xlsx         YIN
 * 20260128           修正依頼      社員番号に英字を使う更新処理でエラーが出る     YIN
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
class HMAUDReportInputedit extends ClsComDb
{

    function insHistorySql($postData)
    {
        $strSQL = "";
        //監査人確定ボタンクリック
        if ($postData['flag'] == '0') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '1', '監査人入力：承認　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //改善報告書担当＿確定ボタンクリック
        if ($postData['flag'] == '1') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '2', '改善報告書担当確認：承認　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //改善報告書担当＿差戻ボタンクリック時
        if ($postData['flag'] == '2') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '9', '2', '改善報告書担当確認：差戻　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //改善取組責任者＿提出ボタンクリック時
        if ($postData['flag'] == '3') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '3', '改善責任者確認：承認　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //各領域責任者＿提出ボタンクリック時
        if ($postData['flag'] == '4') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '4', '領域責任者確認：承認　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //各領域責任者＿差戻ボタンクリック時
        if ($postData['flag'] == '5') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '9', '4', '領域責任者確認：差戻　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //キーマン＿確認ボタンクリック時
        if ($postData['flag'] == '6') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '5', 'キーマン確認：承認　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //キーマン＿差戻ボタンクリック時
        if ($postData['flag'] == '7') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '9', '5', 'キーマン確認：差戻　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //総括責任者＿確認ボタンクリック時
        if ($postData['flag'] == '8') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '6', '総括責任者確認：承認　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //総括責任者＿差戻ボタンクリック時
        if ($postData['flag'] == '9') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '9', '6', '総括責任者確認：差戻　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        // 20230103 YIN INS S
        //常務＿確認ボタンクリック時
        if ($postData['flag'] == '10') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            //20240313 caina upd s
            // $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '7', '常務確認：承認　コメント：@COMMENT', SYSDATE, @CREATE_SYA_CD, SYSDATE, @UPD_SYA_CD)" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '7', '取締役確認：承認　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
            //20240313 caina upd e
        }
        //常務＿差戻ボタンクリック時
        if ($postData['flag'] == '11') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            //20240313 caina upd s
            // $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '9', '7', '常務確認：差戻　コメント：@COMMENT', SYSDATE, @CREATE_SYA_CD, SYSDATE, @UPD_SYA_CD)" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '9', '7', '取締役確認：差戻　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
            //20240313 caina upd e
        }
        // 20230103 YIN INS E
        // 20250403 CI UPD S
        //副社長＿確認ボタンクリック時
        if ($postData['flag'] == '12') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '8', '社長確認：承認　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //副社長＿差戻ボタンクリック時
        if ($postData['flag'] == '13') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '9', '8', '社長確認：差戻　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }

        //社長＿確認ボタンクリック時
        if ($postData['flag'] == '14') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '1', '9', '社長確認：承認　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }
        //社長＿差戻ボタンクリック時
        if ($postData['flag'] == '15') {
            $strSQL .= "INSERT INTO HMAUD_AUDIT_REPORT_HISTORY" . "\r\n";
            $strSQL .= "VALUES('@REPORT_HISTORY_ID', '@REPORT_ID', '@CHECK_ID', SYSDATE, '@CHECK_TANTO', '9', '9', '社長確認：差戻　コメント：@COMMENT', SYSDATE, '@CREATE_SYA_CD', SYSDATE, '@UPD_SYA_CD')" . "\r\n";
        }

        // 20250403 CI UPD E
        $strSQL = str_replace("@REPORT_HISTORY_ID", $postData['REPORT_HISTORY_ID'], $strSQL);
        $strSQL = str_replace("@REPORT_ID", $postData['report_id'], $strSQL);
        $strSQL = str_replace("@CHECK_ID", $postData['check_id'], $strSQL);
        $strSQL = str_replace("@COMMENT", $postData['comment'], $strSQL);
        $strSQL = str_replace("@CHECK_TANTO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CREATE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::insert($strSQL);
    }

    function updReportHeadSql($postData)
    {
        $strSQL = "";
        //監査人確定ボタンクリック
        if ($postData['flag'] == '0') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '02' ,COMP_CHECK_DT = SYSDATE ,COMP_DT2 = SYSDATE, COMP_COMMENT = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

        }
        //改善報告書担当＿確定ボタンクリック
        if ($postData['flag'] == '1') {
            if ($postData['skip'] == '1') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '04' ,RESPONSIBLE_CHECK_DT0 = SYSDATE , RESPONSIBLE_CHECK_DT1 = SYSDATE ,COMP_DT3 = SYSDATE, RESPONSIBLE_COMMENT0 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

            } else {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '03' ,RESPONSIBLE_CHECK_DT0 = SYSDATE , RESPONSIBLE_COMMENT0 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            }

        }
        //改善報告書担当＿差戻ボタンクリック
        if ($postData['flag'] == '2') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT0 = NULL , RESPONSIBLE_COMMENT0 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

        }
        //改善取組責任者＿提出ボタンクリック
        if ($postData['flag'] == '3') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '04' ,RESPONSIBLE_CHECK_DT1 = SYSDATE ,COMP_DT3 = SYSDATE, RESPONSIBLE_COMMENT1 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

        }
        //各領域責任者＿提出ボタンクリック
        if ($postData['flag'] == '4') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '05' ,RESPONSIBLE_CHECK_DT2 = SYSDATE , RESPONSIBLE_COMMENT2 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

        }
        //各領域責任者＿差戻ボタンクリック
        if ($postData['flag'] == '5') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT2 = NULL , RESPONSIBLE_COMMENT2 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

        }
        //キーマン＿確認ボタンクリック
        if ($postData['flag'] == '6') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '06' ,RESPONSIBLE_CHECK_DT3 = SYSDATE , RESPONSIBLE_COMMENT3 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

        }
        //キーマン＿差戻ボタンクリック
        if ($postData['flag'] == '7') {
            //20240614 CI UPD S
            //$strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL , RESPONSIBLE_COMMENT3 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            if ($postData['return_flag'] == '95') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_COMMENT3 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL , RESPONSIBLE_COMMENT3 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            }
            //20240614 CI UPD E
        }
        //総括責任者＿確認ボタンクリック
        if ($postData['flag'] == '8') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '07' ,RESPONSIBLE_CHECK_DT4 = SYSDATE , RESPONSIBLE_COMMENT4 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

        }
        //総括責任者＿差戻ボタンクリック
        if ($postData['flag'] == '9') {
            //20240614 CI UPD S
            //$strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL , RESPONSIBLE_COMMENT4 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            if ($postData['return_flag'] == '96') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT4 = NULL, RESPONSIBLE_COMMENT4 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '95') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL, RESPONSIBLE_COMMENT4 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL , RESPONSIBLE_COMMENT4 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            }
            //20240614 CI UPD E
        }
        // 20230103 YIN INS S
        //常務＿確認ボタンクリック
        if ($postData['flag'] == '10') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '08' ,RESPONSIBLE_CHECK_DT5 = SYSDATE , RESPONSIBLE_COMMENT5 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
        }
        //常務＿差戻ボタンクリック
        if ($postData['flag'] == '11') {
            //20240614 CI UPD S
            // $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT5 = NULL ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL  , RESPONSIBLE_COMMENT5 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            if ($postData['return_flag'] == '97') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT5 = NULL , RESPONSIBLE_COMMENT5 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '96') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT5 = NULL ,RESPONSIBLE_CHECK_DT4 = NULL, RESPONSIBLE_COMMENT5 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '95') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT5 = NULL ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL, RESPONSIBLE_COMMENT5 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT5 = NULL ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL  , RESPONSIBLE_COMMENT5 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            }
            //20240614 CI UPD E
        }
        // 20230103 YIN INS E
        // 20250403 CI UPD S
        // 副社長＿確認ボタンクリック
        if ($postData['flag'] == '12') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '09' ,RESPONSIBLE_CHECK_DT6 = SYSDATE , RESPONSIBLE_COMMENT6 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

        }
        // 副社長＿確認ボタンクリック
        if ($postData['flag'] == '13') {
            //20240614 CI UPD S
            // $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL  , RESPONSIBLE_COMMENT6 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            if ($postData['return_flag'] == '98') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT6= NULL , RESPONSIBLE_COMMENT6 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '97') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL , RESPONSIBLE_COMMENT6 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '96') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL ,RESPONSIBLE_CHECK_DT4 = NULL  , RESPONSIBLE_COMMENT6 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '95') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL  , RESPONSIBLE_COMMENT6 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL  , RESPONSIBLE_COMMENT6 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            }
            //20240614 CI UPD E

        }

        //社長＿確認ボタンクリック
        if ($postData['flag'] == '14') {
            $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '10' ,RESPONSIBLE_CHECK_DT7 = SYSDATE , RESPONSIBLE_COMMENT7 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";

        }
        //社長＿差戻ボタンクリック
        if ($postData['flag'] == '15') {
            //20240614 CI UPD S
            // $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL  , RESPONSIBLE_COMMENT6 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            if ($postData['return_flag'] == '99') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT7= NULL, RESPONSIBLE_COMMENT7 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '98') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT7= NULL ,RESPONSIBLE_CHECK_DT6= NULL , RESPONSIBLE_COMMENT7 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '97') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT7= NULL,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL , RESPONSIBLE_COMMENT7 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '96') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT7= NULL,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL ,RESPONSIBLE_CHECK_DT4 = NULL  , RESPONSIBLE_COMMENT7 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else if ($postData['return_flag'] == '95') {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT7= NULL,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL  , RESPONSIBLE_COMMENT7 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            } else {
                $strSQL .= "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS = '" . $postData['return_flag'] . "' ,RESPONSIBLE_CHECK_DT7= NULL,RESPONSIBLE_CHECK_DT6= NULL ,RESPONSIBLE_CHECK_DT5= NULL ,RESPONSIBLE_CHECK_DT4 = NULL,RESPONSIBLE_CHECK_DT3 = NULL,RESPONSIBLE_CHECK_DT2 = NULL  , RESPONSIBLE_COMMENT7 = '@COMMENT', UPD_DATE = SYSDATE, UPD_SYA_CD = '@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID' AND REPORT_ID = '@REPORT_ID'";
            }
            //20240614 CI UPD E

        }

        // 20250403 CI UPD E
        $strSQL = str_replace("@COMMENT", $postData['comment'], $strSQL);
        $strSQL = str_replace("@CHECK_ID", $postData['check_id'], $strSQL);
        $strSQL = str_replace("@REPORT_ID", $postData['report_id'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::update($strSQL);
    }

    function getMaxid()
    {
        $strSQL = "SELECT MAX(TO_NUMBER(REPORT_HISTORY_ID)) +1 AS REPORT_HISTORY_ID FROM HMAUD_AUDIT_REPORT_HISTORY";
        return parent::select($strSQL);
    }

    function getEmailAddress($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT M.SYAIN_MAL_ADR AS EMAIL" . "\r\n";
        $strSQL .= " FROM HMAUD_AUDIT_MEMBER HAME" . "\r\n";
        $strSQL .= "  LEFT JOIN M29MA4 M" . "\r\n";
        $strSQL .= "  ON M.SYAIN_NO   =HAME.MEMBER" . "\r\n";
        $strSQL .= " WHERE HAME.ROLE='@ROLE'" . "\r\n";
        $strSQL .= " AND HAME.CHECK_ID = '@CHECK_ID'" . "\r\n";
        $strSQL = str_replace("@ROLE", $postData['ROLE'], $strSQL);
        $strSQL = str_replace("@CHECK_ID", $postData['check_id'], $strSQL);
        return parent::select($strSQL);
    }

    function getmaildata($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT TITLE," . "\r\n";
        $strSQL .= "   DESCRIPTION" . "\r\n";
        $strSQL .= " FROM HMAUD_MST_MAIL" . "\r\n";
        $strSQL .= " WHERE TYPE ='@TYPE'" . "\r\n";
        $strSQL .= " AND PATTERN = '@PATTERN'" . "\r\n";
        $strSQL .= " AND PHASE = '@PHASE'" . "\r\n";
        $strSQL = str_replace("@TYPE", $postData['type'], $strSQL);
        $strSQL = str_replace("@PATTERN", $postData['pattren'], $strSQL);
        $strSQL = str_replace("@PHASE", $postData['phase'], $strSQL);
        return parent::select($strSQL);
    }

}
