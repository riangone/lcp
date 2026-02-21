<?php
/**
 * 履歴：
 * ----------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                          担当
 * YYYYMMDD           #ID                       XXXXXX                                        FCSDL
 * 20230801           機能修正　 literal does not match format stringエラー訂正               lujunxia
 * 20240312           機能変更　　実績集計の一覧ソート順を ROW_NO から CHECK_LST_ID 順に変更     lujunxia
 * ----------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDKansaJissekiInput extends ClsComDb
{
    //役割
    function getUserRoleSql($userId)
    {
        $strSQL = "";
        $strSQL .= "SELECT ROLE " . "\r\n";
        $strSQL .= "FROM HMAUD_AUDIT_MEMBER" . "\r\n";
        $strSQL .= "WHERE MEMBER='@MEMBER'" . "\r\n";
        //20230801 caina ins s
        $strSQL .= "ORDER BY ROLE ASC" . "\r\n";
        //20230801 caina ins e

        $strSQL = str_replace("@MEMBER", $userId, $strSQL);

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
    function getDataSql($postData, $checkId)
    {
        $strSQL = "";
        $strSQL .= " SELECT HAD.CHECK_LST_ID," . "\r\n";
        $strSQL .= "   HAD.ROW_NO," . "\r\n";
        $strSQL .= "   HAD.COLUMN1," . "\r\n";
        $strSQL .= "   HAD.COLUMN2," . "\r\n";
        $strSQL .= "   HAD.COLUMN3," . "\r\n";
        $strSQL .= "   HAD.COLUMN4," . "\r\n";
        $strSQL .= "   HAD.COLUMN5," . "\r\n";
        $strSQL .= "   HAD.COLUMN6," . "\r\n";
        $strSQL .= "   HAD.COLUMN7," . "\r\n";
        $strSQL .= "   HAR.RESULT," . "\r\n";
        $strSQL .= "   HAR.REMARKS," . "\r\n";
        $strSQL .= "   HAR.CHECK_RESULT_ID," . "\r\n";
        //20230420 caina ins s
        $strSQL .= "   HAR.CHECK_ID," . "\r\n";
        $strSQL .= "   HAR.RESULT AS RES," . "\r\n";
        $strSQL .= "   HAR.REMARKS AS REM," . "\r\n";
        $strSQL .= "   TO_CHAR(HAR.UPD_DATE,'YYYY-MM-DD HH24:MI:SS') AS UPD_DATE" . "\r\n";
        //20230423 caina ins e
        $strSQL .= " FROM HMAUD_AUDIT_DETAIL HAD" . "\r\n";
        $strSQL .= " LEFT JOIN HMAUD_AUDIT_RESULT HAR" . "\r\n";
        $strSQL .= " ON HAR.CHECK_LST_ID=HAD.ROW_NO" . "\r\n";
        if ($checkId != '') {
            $strSQL .= " AND HAR.CHECK_ID   ='@CHECK_ID'" . "\r\n";
            $strSQL = str_replace("@CHECK_ID", $checkId, $strSQL);
        }
        $strSQL .= " LEFT JOIN HMAUD_MST_COUR HCOUR" . "\r\n";
        $strSQL .= " ON HAD.COURS=HCOUR.COURS" . "\r\n";
        $strSQL .= " WHERE HAD.COURS   ='@COURS'" . "\r\n";
        $strSQL .= " AND HAD.TERRITORY = '@TERRITORY'" . "\r\n";
        $strSQL .= " AND (HAD.EXPIRATION_DATE >= HCOUR.START_DT" . "\r\n";
        $strSQL .= " OR HAD.EXPIRATION_DATE IS NULL)" . "\r\n";
        //20240312 lujunxia upd s
        //$strSQL .= " ORDER BY  TO_NUMBER(REPLACE(HAD.ROW_NO,'追加','9999'))" . "\r\n";
        $strSQL .= " ORDER BY HAD.CHECK_LST_ID" . "\r\n";
        //20240312 lujunxia upd e
        $strSQL = str_replace("@COURS", $postData['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $postData['TERRITORY'], $strSQL);
        return parent::select($strSQL);
    }

    //20230421 caina ins s
    function getDateSql($param)
    {
        $strSQL = "SELECT TO_CHAR(HMAUD_AUDIT_RESULT.UPD_DATE,'YYYY-MM-DD HH24:MI:SS') AS UPD_DATE,RESULT,REMARKS FROM HMAUD_AUDIT_RESULT WHERE CHECK_ID='@CHECK_ID' and CHECK_LST_ID = '@CHECK_LST_ID'";
        $strSQL = str_replace("@CHECK_ID", $param['CHECK_ID'], $strSQL);
        $strSQL = str_replace("@CHECK_LST_ID", $param['ROW_NO'], $strSQL);
        return parent::select($strSQL);
    }

    //20230421 caina ins e
    //監査担当者
    function getAuditMemberSql($userId, $checkId)
    {
        $strSQL = "";
        $strSQL .= "SELECT ROLE " . "\r\n";
        $strSQL .= "FROM HMAUD_AUDIT_MEMBER" . "\r\n";
        $strSQL .= "WHERE MEMBER='@MEMBER'" . "\r\n";
        $strSQL .= "AND CHECK_ID='@CHECK_ID'" . "\r\n";
        $strSQL .= "AND ROLE='1'" . "\r\n";
        // 20231117 YIN INS S
        $strSQL .= " ORDER BY ROLE ASC" . "\r\n";
        // 20231117 YIN INS E

        $strSQL = str_replace("@MEMBER", $userId, $strSQL);
        $strSQL = str_replace("@CHECK_ID", $checkId, $strSQL);

        return parent::select($strSQL);
    }

    //監査マスタ.監査ID
    function getCheckIdSql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT CHECK_ID" . "\r\n";
        $strSQL .= " ,TO_CHAR(CHECK_DT, 'YYYY/MM/DD') AS CHECK_DT" . "\r\n";
        $strSQL .= "FROM HMAUD_AUDIT_MAIN" . "\r\n";
        $strSQL .= "WHERE COURS                         ='@COURS'" . "\r\n";
        $strSQL .= "AND TERRITORY                       ='@TERRITORY'" . "\r\n";
        $strSQL .= "AND KYOTEN_CD                       ='@KYOTEN_CD'" . "\r\n";

        $strSQL = str_replace("@COURS", $postData['COURS'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $postData['TERRITORY'], $strSQL);
        $strSQL = str_replace("@KYOTEN_CD", $postData['KYOTEN_CD'], $strSQL);

        return parent::select($strSQL);
    }

    //ステータス
    function getStatusSql($checkId)
    {
        $strSQL = "SELECT STATUS FROM HMAUD_AUDIT_REPORT_HEAD WHERE CHECK_ID='@CHECK_ID'" . "\r\n";
        $strSQL = str_replace("@CHECK_ID", $checkId, $strSQL);

        return parent::select($strSQL);
    }

    //監査補助人
    function getAuditSubSql($SYAIN_NO)
    {
        $strSQL = "SELECT SYAIN_NO FROM HMAUD_MST_ADMIN WHERE SYAIN_NO='@SYAIN_NO'" . "\r\n";
        $strSQL = str_replace("@SYAIN_NO", $SYAIN_NO, $strSQL);

        return parent::select($strSQL);
    }

    //監査実績既存のデータ
    function getAuditResultExistData($checkResultId)
    {
        $strSQL = "SELECT * FROM HMAUD_AUDIT_RESULT WHERE CHECK_RESULT_ID='@CHECK_RESULT_ID'";
        $strSQL = str_replace("@CHECK_RESULT_ID", $checkResultId, $strSQL);

        return parent::select($strSQL);
    }

    //監査実績ID
    function getAuditResultNo()
    {
        $strSQL = "SELECT NVL(MAX(TO_NUMBER(CHECK_RESULT_ID)),0) AS NO FROM HMAUD_AUDIT_RESULT";

        return parent::select($strSQL);
    }

    //監査実績データを追加する
    function insAuditResultSql($param)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HMAUD_AUDIT_RESULT" . "\r\n";
        $strSQL .= "(CHECK_RESULT_ID,CHECK_ID,CHECK_LST_ID,CHECK_DT,RESULT,REMARKS,CREATE_DATE,CREATE_SYA_CD,UPD_DATE,UPD_SYA_CD)" . "\r\n";
        //20230801 lujunxia upd s
        //$strSQL .= "VALUES('@CHECK_RESULT_ID', '@CHECK_ID', '@CHECK_LST_ID', '@CHECK_DT', '@RESULT', '@REMARKS',SYSDATE,'@CREATE_SYA_CD',SYSDATE,'@UPD_SYA_CD')" . "\r\n";
        $strSQL .= "VALUES('@CHECK_RESULT_ID', '@CHECK_ID', '@CHECK_LST_ID', TO_DATE('@CHECK_DT','YYYY-MM-DD HH24:MI:SS'), '@RESULT', '@REMARKS',SYSDATE,'@CREATE_SYA_CD',SYSDATE,'@UPD_SYA_CD')" . "\r\n";
        //20230801 lujunxia upd e
        $strSQL = str_replace("@CHECK_RESULT_ID", $param['CHECK_RESULT_ID'], $strSQL);
        $strSQL = str_replace("@CHECK_ID", $param['CHECK_ID'], $strSQL);
        $strSQL = str_replace("@CHECK_LST_ID", $param['ROW_NO'], $strSQL);
        $strSQL = str_replace("@CHECK_DT", $param['CHECK_DT'], $strSQL);
        $strSQL = str_replace("@RESULT", $param['RESULT'], $strSQL);
        $strSQL = str_replace("@REMARKS", $param['REMARKS'], $strSQL);
        $strSQL = str_replace("@CREATE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::insert($strSQL);
    }

    //監査実績既存のデータ更新
    function updAuditResultSql($param)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HMAUD_AUDIT_RESULT" . "\r\n";
        //20230801 lujunxia upd s
        //$strSQL .= "SET RESULT='@RESULT', REMARKS='@REMARKS',CHECK_DT='@CHECK_DT',UPD_DATE=SYSDATE,UPD_SYA_CD='@UPD_SYA_CD'" . "\r\n";
        $strSQL .= "SET RESULT='@RESULT', REMARKS='@REMARKS',CHECK_DT=TO_DATE('@CHECK_DT','YYYY-MM-DD HH24:MI:SS'),UPD_DATE=SYSDATE,UPD_SYA_CD='@UPD_SYA_CD'" . "\r\n";
        //20230801 lujunxia upd e
        $strSQL .= "WHERE CHECK_RESULT_ID = '@CHECK_RESULT_ID'" . "\r\n";
        $strSQL = str_replace("@CHECK_RESULT_ID", $param['CHECK_RESULT_ID'], $strSQL);
        $strSQL = str_replace("@RESULT", $param['RESULT'], $strSQL);
        $strSQL = str_replace("@REMARKS", $param['REMARKS'], $strSQL);
        $strSQL = str_replace("@CHECK_DT", $param['CHECK_DT'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::update($strSQL);
    }

    //実績入力画面で保存、確定したときに、監査マスタ.実施日を更新
    function updAuditMainSql($checkId, $sysDate)
    {
        //20230801 lujunxia upd s
        //$strSQL = "UPDATE HMAUD_AUDIT_MAIN SET CHECK_DT = '@CHECK_DT',UPD_DATE=SYSDATE,UPD_SYA_CD='@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID'";
        $strSQL = "UPDATE HMAUD_AUDIT_MAIN SET CHECK_DT = TO_DATE('@CHECK_DT','YYYY-MM-DD HH24:MI:SS'),UPD_DATE=SYSDATE,UPD_SYA_CD='@UPD_SYA_CD' WHERE CHECK_ID = '@CHECK_ID'";
        //20230801 lujunxia upd e
        $strSQL = str_replace("@CHECK_DT", $sysDate, $strSQL);
        $strSQL = str_replace("@CHECK_ID", $checkId, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::update($strSQL);
    }

    //報告書ヘッダ.ステータスを 「01.監査実績入力済」に更新
    function setStatusSql($checkId)
    {
        //20230801 lujunxia upd s
        //$strSQL = "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS='01' ,COMP_DT1 = SYSDATE ,UPD_DATE=SYSDATE ,UPD_SYA_CD='@UPD_SYA_CD' WHERE check_id = '@CHECK_ID'";
        $strSQL = "UPDATE HMAUD_AUDIT_REPORT_HEAD SET STATUS='01' ,COMP_DT1 = SYSDATE ,UPD_DATE=SYSDATE ,UPD_SYA_CD='@UPD_SYA_CD' WHERE check_id = '@CHECK_ID'";
        //20230801 lujunxia upd e
        $strSQL = str_replace("@CHECK_ID", $checkId, $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::update($strSQL);
    }
}