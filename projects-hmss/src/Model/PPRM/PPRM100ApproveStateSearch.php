<?php

/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */

//共通クラスの読込み
namespace App\Model\PPRM;

use App\Model\Component\ClsComDb;
use App\Model\PPRM\Component\ClsProc;

//*************************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************************
class PPRM100ApproveStateSearch extends ClsComDb
{
    public $BTN_KINSYU = "btnUpd_Del";
    public $BTN_SYOUNIN = "btnApprove";

    // '**********************************************************************
    // '処 理 名：検索条件に一致する値を取得する
    // '関 数 名：fncSelectSearch1
    // '引 数 　：$postData
    // '戻 り 値：検索結果
    // '処理説明：対象が事務の場合（日締データ）
    // '**********************************************************************
    public function fncSelectSearch1($postData)
    {
        $postData = $postData["request"];
        $clsProc = new ClsProc();
        $strSQL = "";
        $strSQL .= "SELECT V.HJM_SYR_DTM," . " \r\n";
        $strSQL .= "       V.TENPO_CD," . " \r\n";
        $strSQL .= "       BS2.BUSYO_RYKNM," . " \r\n";
        $strSQL .= "       V.TEN_HJM_NO," . " \r\n";
        $strSQL .= "       (CASE WHEN V.HJM_CHK = '1' THEN '有' ELSE '無' END) HJM_DATA_SONZAI," . " \r\n";
        $strSQL .= "       V.HJM_CHK," . " \r\n";
        $strSQL .= "       V.MNY_CHK," . " \r\n";
        $strSQL .= "       DECODE(SYN.KEIRI_SNN_FLG,1,1,0) AS TANTO1," . " \r\n";
        $strSQL .= "       SUBSTR(SYN.KEIRI_SNN_TANTO_NM,1,INSTR(SYN.KEIRI_SNN_TANTO_NM,'　')-1) AS KEIRI_SNN_TANTO_NM," . " \r\n";
        $strSQL .= "       DECODE(SYN.TENCHO_SNN_FLG,1,1,0) AS TANTO2," . " \r\n";
        $strSQL .= "       SUBSTR(SYN.TENCHO_SNN_TANTO_NM,1,INSTR(SYN.TENCHO_SNN_TANTO_NM,'　')-1) AS TENCHO_SNN_TANTO_NM," . " \r\n";
        $strSQL .= "       DECODE(SYN.KACHO_SNN_FLG ,1,1,0) AS TANTO3," . " \r\n";
        $strSQL .= "       SUBSTR(SYN.KACHO_SNN_TANTO_NM,1,INSTR(SYN.KACHO_SNN_TANTO_NM,'　')-1) AS KACHO_SNN_TANTO_NM," . " \r\n";
        $strSQL .= "       DECODE(SYN.TAN_SNN_FLG,1,1,0) AS TANTO4," . " \r\n";
        $strSQL .= "       SUBSTR(SYN.TAN_SNN_TANTO_NM,1,INSTR(SYN.TAN_SNN_TANTO_NM,'　')-1) AS TAN_SNN_TANTO_NM," . " \r\n";
        $strSQL .= "       (CASE WHEN V.MNY_CHK = '0' AND V.HJM_CHK = '1' AND V.KON_HJM_EGK_KKS_GK <> V.ZEN_HJM_EGK_KKS_GK THEN '1'" . " \r\n";
        $strSQL .= "             WHEN V.MNY_CHK = '1' AND V.HJM_CHK = '0' THEN '2'" . " \r\n";
        $strSQL .= "             WHEN V.MNY_CHK = '1' AND V.HJM_CHK = '1' AND V.MONEY_INPUT_GK <> V.KON_HJM_EGK_KKS_GK THEN '3'" . " \r\n";
        $strSQL .= "             ELSE '0'" . " \r\n";
        $strSQL .= "        END ) JYOUTAI_FLG" . " \r\n";

        //'部署別に権限管理を行う必要があるかのフラグを取得する
        $strAuth = $clsProc->SubSetEnabled_Control($postData["Sys_KB"], "PPRM100ApproveStateSearch", $postData["login_user"], $postData["BusyoCD"], "btnSearch");
        if ($strAuth[1] == "0") {
            //'***コントロールのIDを変更する場合は注意が必要！！constで定義している***
            //'承認ボタンの権限IDを取得
            $strRet = $clsProc->FncGetAuthInfo($postData["Sys_KB"], "PPRM100ApproveStateSearch", $this->BTN_SYOUNIN, $postData["login_user"]);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = V.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') SYOUNIN_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' SYOUNIN_DISP_FLG" . " \r\n";
            }
            //'金種表編集ボタンの権限IDを取得
            $strRet = $clsProc->FncGetAuthInfo($postData["Sys_KB"], "PPRM100ApproveStateSearch", $this->BTN_KINSYU, $postData["login_user"]);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = V.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') KINSYU_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' KINSYU_DISP_FLG" . " \r\n";
            }
        } else {
            $strSQL .= ",      '1' SYOUNIN_DISP_FLG" . " \r\n";
            $strSQL .= ",      '1' KINSYU_DISP_FLG" . " \r\n";
        }


        $strSQL .= ",          V.FUICHI_RIYU" . " \r\n";
        $strSQL .= "FROM   (" . " \r\n";
        $strSQL .= "        SELECT WK.HJM_SYR_DTM," . " \r\n";
        $strSQL .= "               WK.TEN_HJM_NO," . " \r\n";
        $strSQL .= "               WK.TENPO_CD," . " \r\n";
        $strSQL .= "               MAX(WK.HJM_EXISTS) HJM_CHK," . " \r\n";
        $strSQL .= "               MAX(WK.MNY_EXISTS) MNY_CHK," . " \r\n";
        $strSQL .= "               MAX(WK.KON_HJM_EGK_KKS_GK) KON_HJM_EGK_KKS_GK," . " \r\n";
        $strSQL .= "               MAX(WK.ZEN_HJM_EGK_KKS_GK) ZEN_HJM_EGK_KKS_GK," . " \r\n";
        $strSQL .= "               MAX(WK.MONEY_INPUT_GK) MONEY_INPUT_GK" . " \r\n";
        $strSQL .= "              ,MAX(WK.FUICHI_RIYU) FUICHI_RIYU" . " \r\n";
        $strSQL .= "        FROM (" . " \r\n";
        $strSQL .= "              SELECT TO_CHAR(KNR.HJM_SYR_DTM,'YYYY/MM/DD') AS HJM_SYR_DTM," . " \r\n";
        $strSQL .= "                     KNR.TEN_HJM_NO," . " \r\n";
        $strSQL .= "                     KNR.TENPO_CD," . " \r\n";
        $strSQL .= "                     '1' AS HJM_EXISTS," . " \r\n";
        $strSQL .= "                     '0' AS MNY_EXISTS," . " \r\n";
        $strSQL .= "                     KNR.KON_HJM_EGK_KKS_GK," . " \r\n";
        $strSQL .= "                     KNR.ZEN_HJM_EGK_KKS_GK," . " \r\n";
        $strSQL .= "                     0 MONEY_INPUT_GK" . " \r\n";
        $strSQL .= "                     ,NULL FUICHI_RIYU" . " \r\n";
        $strSQL .= "              FROM M41F11 KNR" . " \r\n";
        $strSQL .= "              WHERE 1=1" . " \r\n";
        //'◎条件---
        //'店舗コード
        if ($postData["txtFromTenpoCD"] <> "" && $postData["txtToTenpoCD"] == "") {
            $strSQL .= "                AND KNR.TENPO_CD >= '@FromTenpoCD'" . " \r\n";
        }
        if ($postData["txtFromTenpoCD"] == "" && $postData["txtToTenpoCD"] <> "") {
            $strSQL .= "                AND KNR.TENPO_CD <= '@TOTenpoCD'" . " \r\n";
        }
        if ($postData["txtFromTenpoCD"] <> "" && $postData["txtToTenpoCD"] <> "") {
            $strSQL .= "                AND KNR.TENPO_CD BETWEEN '@FromTenpoCD' AND '@ToTenpoCD'" . " \r\n";
        }
        //'日締日
        if ($postData["txtHJMFromDate"] <> "" && $postData["txtHJMToDate"] == "") {
            $strSQL .= "                AND TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') >= '@HJMFromDate'" . " \r\n";
        }
        if ($postData["txtHJMFromDate"] == "" && $postData["txtHJMToDate"] <> "") {
            $strSQL .= "                AND TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') <= '@HJMToDate'" . " \r\n";
        }
        if ($postData["txtHJMFromDate"] <> "" && $postData["txtHJMToDate"] <> "") {
            $strSQL .= "                AND TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') BETWEEN '@HJMFromDate' AND '@HJMToDate'" . " \r\n";
        }
        //'日締№
        if ($postData["txtHJMNo"] <> "") {
            $strSQL .= "                AND KNR.TEN_HJM_NO = '@HJMNo'" . " \r\n";
        }
        $strSQL .= "              UNION ALL" . " \r\n";
        //20170214 lqs UPD S
        //$strSQL .= "              SELECT TO_CHAR(TO_DATE('20' || SUBSTR(MNY.TEN_HJM_NO,4,6)),'YYYY/MM/DD')," . " \r\n";
        $strSQL .= "                SELECT " . " \r\n";
        $strSQL .= "       CASE WHEN LENGTH(trim(SUBSTR(MNY.TEN_HJM_NO, 4, 6))) = 6 then TO_CHAR(TO_DATE('20' || SUBSTR(MNY.TEN_HJM_NO, 4, 6), 'YYYY/MM/DD'), 'YYYY/MM/DD')" . " \r\n";
        $strSQL .= "             ELSE NULL" . " \r\n";
        $strSQL .= "        END ," . " \r\n";
        //20170214 lqs UPD E
        $strSQL .= "                     MNY.TEN_HJM_NO," . " \r\n";
        $strSQL .= "                     MNY.TENPO_CD," . " \r\n";
        $strSQL .= "                     '0'," . " \r\n";
        $strSQL .= "                     '1'," . " \r\n";
        $strSQL .= "                     0," . " \r\n";
        $strSQL .= "                     0," . " \r\n";
        $strSQL .= "                     MNY.ZAN_GK" . " \r\n";
        $strSQL .= "                    ,MNY.FUICHI_RIYU" . " \r\n";
        $strSQL .= "              FROM PPRHJMMONEYKINDHED MNY" . " \r\n";
        $strSQL .= "              WHERE 1=1" . " \r\n";
        $strSQL .= "                AND EGK_KMY_KBN = '0'" . " \r\n";
        //'◎条件---
        //'店舗コード
        if ($postData["txtFromTenpoCD"] <> "" && $postData["txtToTenpoCD"] == "") {
            $strSQL .= "                AND MNY.TENPO_CD >= '@FromTenpoCD'" . " \r\n";
        }
        if ($postData["txtFromTenpoCD"] == "" && $postData["txtToTenpoCD"] <> "") {
            $strSQL .= "                AND MNY.TENPO_CD <= '@ToTenpoCD'" . " \r\n";
        }
        if ($postData["txtFromTenpoCD"] <> "" && $postData["txtToTenpoCD"] <> "") {
            $strSQL .= "                AND MNY.TENPO_CD BETWEEN '@FromTenpoCD' AND '@ToTenpoCD'" . " \r\n";
        }
        //'日締日
        if ($postData["txtHJMFromDate"] <> "" && $postData["txtHJMToDate"] == "") {
            $strSQL .= "                AND '20' || SUBSTR(MNY.TEN_HJM_NO,4,6) >= '@HJMFromDate'" . " \r\n";
        }
        if ($postData["txtHJMFromDate"] == "" && $postData["txtHJMToDate"] <> "") {
            $strSQL .= "                AND '20' || SUBSTR(MNY.TEN_HJM_NO,4,6) <= '@HJMToDate'" . " \r\n";
        }
        if ($postData["txtHJMFromDate"] <> "" && $postData["txtHJMToDate"] <> "") {
            $strSQL .= "                AND '20' || SUBSTR(MNY.TEN_HJM_NO,4,6) BETWEEN '@HJMFromDate' AND '@HJMToDate'" . " \r\n";
        }
        //'日締№
        if ($postData["txtHJMNo"] <> "") {
            $strSQL .= "                AND MNY.TEN_HJM_NO = '@HJMNo'" . " \r\n";
        }
        $strSQL .= "              )WK" . " \r\n";
        $strSQL .= "        GROUP BY WK.HJM_SYR_DTM," . " \r\n";
        $strSQL .= "                 WK.TEN_HJM_NO," . " \r\n";
        $strSQL .= "                 WK.TENPO_CD" . " \r\n";
        $strSQL .= "       )V" . " \r\n";
        $strSQL .= "  LEFT JOIN PPRHJMAPPROVEDATA SYN" . " \r\n";
        $strSQL .= "         ON V.TENPO_CD = SYN.TENPO_CD" . " \r\n";
        $strSQL .= "        AND V.TEN_HJM_NO = SYN.TEN_HJM_NO" . " \r\n";
        $strSQL .= "        AND SYN.HJM_KIND = '1'" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS1" . " \r\n";
        $strSQL .= "         ON V.TENPO_CD = BS1.BUSYO_CD" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS2" . " \r\n";
        $strSQL .= "         ON BS1.TENPO_CD = BS2.BUSYO_CD" . " \r\n";
        $strSQL .= "WHERE 1=1" . " \r\n";

        //'店舗コード
        if ($postData["txtFromTenpoCD"] <> "" && $postData["txtToTenpoCD"] == "") {
            $strSQL .= "  AND V.TENPO_CD >= '@FromTenpoCD'" . " \r\n";
        }
        if ($postData["txtFromTenpoCD"] == "" && $postData["txtToTenpoCD"] <> "") {
            $strSQL .= "  AND V.TENPO_CD <= '@ToTenpoCD'" . " \r\n";
        }
        if ($postData["txtFromTenpoCD"] <> "" && $postData["txtToTenpoCD"] <> "") {
            $strSQL .= "  AND V.TENPO_CD BETWEEN '@FromTenpoCD' AND '@ToTenpoCD'" . " \r\n";
        }
        //'日締日
        if ($postData["txtHJMFromDate"] <> "" && $postData["txtHJMToDate"] == "") {
            $strSQL .= "  AND REPLACE(V.HJM_SYR_DTM,'/','') >= '@HJMFromDate'" . " \r\n";
        }
        if ($postData["txtHJMFromDate"] == "" && $postData["txtHJMToDate"] <> "") {
            $strSQL .= "  AND REPLACE(V.HJM_SYR_DTM,'/','') <= '@HJMToDate'" . " \r\n";
        }
        if ($postData["txtHJMFromDate"] <> "" && $postData["txtHJMToDate"] <> "") {
            $strSQL .= "  AND REPLACE(V.HJM_SYR_DTM,'/','') BETWEEN '@HJMFromDate' AND '@HJMToDate'" . " \r\n";
        }
        //'日締№
        if ($postData["txtHJMNo"] <> "") {
            $strSQL .= "  AND V.TEN_HJM_NO = '@HJMNo'" . " \r\n";
        }
        //'登録状態（日締データ有り・金種表登録済み）
        if ($postData["rdbJyoutai"] == "rdbJyoutai2") {
            $strSQL .= "  AND (V.HJM_CHK = '1' AND V.MNY_CHK = '1')" . " \r\n";
        }
        //'登録状態（日締データ有り・金種表未登録）
        if ($postData["rdbJyoutai"] == "rdbJyoutai3") {
            $strSQL .= "  AND (V.HJM_CHK = '1' AND V.MNY_CHK = '0')" . " \r\n";
        }
        //'登録状態（日締データ無し・金種表登録済み）
        if ($postData["rdbJyoutai"] == "rdbJyoutai4") {
            $strSQL .= "  AND (V.HJM_CHK = '0' AND V.MNY_CHK = '1')" . " \r\n";
        }
        //'確認状況
        if ($postData["ddlKakunin"] == "0") {
            //'未チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo1") {
                $strSQL .= "  AND DECODE(SYN.KEIRI_SNN_FLG,1,1,0) <> '1'" . " \r\n";
            }
            //'済チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo2") {
                $strSQL .= "  AND DECODE(SYN.KEIRI_SNN_FLG,1,1,0) = '1'" . " \r\n";
            }
        }
        if ($postData["ddlKakunin"] == "1") {
            //'未チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo1") {
                $strSQL .= "  AND DECODE(SYN.TENCHO_SNN_FLG,1,1,0) <> '1'" . " \r\n";
            }
            //'済チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo2") {
                $strSQL .= "  AND DECODE(SYN.TENCHO_SNN_FLG,1,1,0) = '1'" . " \r\n";
            }
        }
        if ($postData["ddlKakunin"] == "2") {
            //'未チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo1") {
                $strSQL .= "  AND DECODE(SYN.KACHO_SNN_FLG ,1,1,0) <> '1'" . " \r\n";
            }
            //'済チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo2") {
                $strSQL .= "  AND DECODE(SYN.KACHO_SNN_FLG ,1,1,0) = '1'" . " \r\n";
            }
        }
        if ($postData["ddlKakunin"] == "3") {
            //'未チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo1") {
                $strSQL .= "  AND DECODE(SYN.TAN_SNN_FLG,1,1,0) <> '1'" . " \r\n";
            }
            //'済チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo2") {
                $strSQL .= "  AND DECODE(SYN.TAN_SNN_FLG,1,1,0) = '1'" . " \r\n";
            }
        }
        //'経理担当の場合
        if ($postData["rdbKakunin"] == "rdbKakunin1") {
            //'経理担当コード
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] == "") {
                $strSQL .= "  AND SYN.KEIRI_SNN_TANTO_CD >= '@FromTantoCD'" . " \r\n";
            }
            if ($postData["txtFromSyainCD"] == "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.KEIRI_SNN_TANTO_CD <= '@ToTantoCD'" . " \r\n";
            }
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.KEIRI_SNN_TANTO_CD BETWEEN '@FromTantoCD' AND '@ToTantoCD'" . " \r\n";
            }
        }
        //'店長の場合
        if ($postData["rdbKakunin"] == "rdbKakunin2") {
            //'店長コード
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] == "") {
                $strSQL .= "  AND SYN.TENCHO_SNN_TANTO_CD >= '@FromTantoCD'" . " \r\n";
            }
            if ($postData["txtFromSyainCD"] == "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.TENCHO_SNN_TANTO_CD <= '@ToTantoCD'" . " \r\n";
            }
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.TENCHO_SNN_TANTO_CD BETWEEN '@FromTantoCD' AND '@ToTantoCD'" . " \r\n";
            }
        }
        //'課長の場合
        if ($postData["rdbKakunin"] == "rdbKakunin3") {
            //'課長コード
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] == "") {
                $strSQL .= "  AND SYN.KACHO_SNN_TANTO_CD >= '@FromTantoCD'" . " \r\n";
            }
            if ($postData["txtFromSyainCD"] == "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.KACHO_SNN_TANTO_CD <= '@ToTantoCD'" . " \r\n";
            }
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.KACHO_SNN_TANTO_CD BETWEEN '@FromTantoCD' AND '@ToTantoCD'" . " \r\n";
            }
        }
        //'担当の場合
        if ($postData["rdbKakunin"] == "rdbKakunin4") {
            //'担当コード
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] == "") {
                $strSQL .= "  AND SYN.TAN_SNN_TANTO_CD >= '@FromTantoCD'" . " \r\n";
            }
            if ($postData["txtFromSyainCD"] == "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.TAN_SNN_TANTO_CD <= '@ToTantoCD'" . " \r\n";
            }
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.TAN_SNN_TANTO_CD BETWEEN '@FromTantoCD' AND '@ToTantoCD'" . " \r\n";
            }
        }

        if ($strAuth[1] == "0") {
            $strSQL .= "AND      V.TENPO_CD IN (SELECT BUSYO_CD" . " \r\n";
            $strSQL .= "                              FROM   HAUTHORITY_CTL" . " \r\n";
            $strSQL .= "                              WHERE  MENU_LIST_NO   = '@PRO_NO'" . " \r\n";
            $strSQL .= "                              AND    SYAIN_NO = '@SYAIN_NO'" . " \r\n";
            $strSQL .= "                              AND    HAUTH_ID = '@HAUTH_ID')" . " \r\n";
            $strSQL = str_replace("@PRO_NO", $clsProc->FncGetProgramNO($postData["Sys_KB"], "PPRM100ApproveStateSearch"), $strSQL);
            $strSQL = str_replace("@SYAIN_NO", $postData['login_user'], $strSQL);
            $strSQL = str_replace("@HAUTH_ID", $strAuth[0], $strSQL);
        }

        $strSQL .= "ORDER BY V.HJM_SYR_DTM," . " \r\n";
        $strSQL .= "         V.TEN_HJM_NO" . " \r\n";
        //'値置換
        $strSQL = str_replace("@FromTenpoCD", $postData["txtFromTenpoCD"], $strSQL);
        $strSQL = str_replace("@ToTenpoCD", $postData["txtToTenpoCD"], $strSQL);
        $strSQL = str_replace("@HJMFromDate", $postData["txtHJMFromDate"] = str_replace("/", "", $postData["txtHJMFromDate"]), $strSQL);
        $strSQL = str_replace("@HJMToDate", $postData["txtHJMToDate"] = str_replace("/", "", $postData["txtHJMToDate"]), $strSQL);
        $strSQL = str_replace("@HJMNo", $postData["txtHJMNo"], $strSQL);
        $strSQL = str_replace("@FromTantoCD", $postData["txtFromSyainCD"], $strSQL);
        $strSQL = str_replace("@ToTantoCD", $postData["txtToSyainCD"], $strSQL);

        return parent::select($strSQL);
    }

    // '**********************************************************************
    // '処 理 名：検索条件に一致する値を取得する
    // '関 数 名：fncSelectSearch2
    // '引 数 　：$postData
    // '戻 り 値：検索結果
    // '処理説明：対象が整備の場合（売上データ）
    // '**********************************************************************
    public function fncSelectSearch2($postData)
    {
        $postData = $postData["request"];
        $clsProc = new ClsProc();
        $strSQL = "";
        //20170925 lqs UPD S
        //$strSQL .= "SELECT TO_CHAR(TO_DATE(URI.URIAGEDT),'YYYY/MM/DD') AS URIAGEDT," . " \r\n";
        $strSQL .= "SELECT TO_CHAR(TO_DATE(URI.URIAGEDT,'YYYY/MM/DD'),'YYYY/MM/DD') AS URIAGEDT," . " \r\n";
        //20170925 lqs UPD E
        $strSQL .= "       URI.TENPO_CD," . " \r\n";
        $strSQL .= "       BS2.BUSYO_RYKNM," . " \r\n";
        $strSQL .= "       DECODE(SYN.KEIRI_SNN_FLG,1,1,0) AS TANTO1," . " \r\n";
        $strSQL .= "       SUBSTR(SYN.KEIRI_SNN_TANTO_NM,1,INSTR(SYN.KEIRI_SNN_TANTO_NM,'　')) AS KEIRI_SNN_TANTO_NM," . " \r\n";
        $strSQL .= "       DECODE(SYN.TENCHO_SNN_FLG,1,1,0) AS TANTO2," . " \r\n";
        $strSQL .= "       SUBSTR(SYN.TENCHO_SNN_TANTO_NM,1,INSTR(SYN.TENCHO_SNN_TANTO_NM,'　')) AS TENCHO_SNN_TANTO_NM," . " \r\n";
        $strSQL .= "       DECODE(SYN.KACHO_SNN_FLG ,1,1,0) AS TANTO3," . " \r\n";
        $strSQL .= "       SUBSTR(SYN.KACHO_SNN_TANTO_NM,1,INSTR(SYN.KACHO_SNN_TANTO_NM,'　')) AS KACHO_SNN_TANTO_NM," . " \r\n";
        $strSQL .= "       DECODE(SYN.TAN_SNN_FLG,1,1,0) AS TANTO4," . " \r\n";
        $strSQL .= "       SUBSTR(SYN.TAN_SNN_TANTO_NM,1,INSTR(SYN.TAN_SNN_TANTO_NM,'　')) AS TAN_SNN_TANTO_NM" . " \r\n";

        //'部署別に権限管理を行う必要があるかのフラグを取得する
        $strAuth = $clsProc->SubSetEnabled_Control($postData['Sys_KB'], "PPRM100ApproveStateSearch", $postData['login_user'], $postData['BusyoCD'], "btnSearch");
        if ($strAuth[1] == "0") {
            //'承認ボタンの権限IDを取得 ***コントロールのIDを変更する場合は注意が必要！！constで定義している***
            $strRet = $clsProc->FncGetAuthInfo($postData['Sys_KB'], "PPRM100ApproveStateSearch", $this->BTN_SYOUNIN, $postData['login_user']);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO) FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = URI.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') SYOUNIN_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' SYOUNIN_DISP_FLG" . " \r\n";
            }
        } else {
            $strSQL .= ",      '1' SYOUNIN_DISP_FLG" . " \r\n";
        }

        $strSQL .= "FROM M41S30 URI" . " \r\n";
        $strSQL .= "  LEFT JOIN PPRHJMAPPROVEDATA SYN" . " \r\n";
        $strSQL .= "         ON URI.TENPO_CD = SYN.TENPO_CD" . " \r\n";
        $strSQL .= "        AND (URI.TENPO_CD || SUBSTR(URI.URIAGEDT,3,6) || 'S01') = SYN.TEN_HJM_NO" . " \r\n";
        $strSQL .= "        AND SYN.HJM_KIND = '2'" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS1" . " \r\n";
        $strSQL .= "         ON URI.TENPO_CD = BS1.BUSYO_CD" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS2" . " \r\n";
        $strSQL .= "         ON BS1.TENPO_CD = BS2.BUSYO_CD" . " \r\n";
        $strSQL .= "WHERE 1=1" . " \r\n";
        //'店舗コード
        if ($postData["txtFromTenpoCD"] <> "" && $postData["txtToTenpoCD"] == "") {
            $strSQL .= "  AND URI.TENPO_CD >= '@FromTenpoCD'" . " \r\n";
        }
        //20170222 lqs INS S
        if ($postData["txtFromTenpoCD"] == "" && $postData["txtToTenpoCD"] <> "") {
            $strSQL .= "  AND URI.TENPO_CD <= '@ToTenpoCD'" . " \r\n";
        }
        //20170222 lqs INS E
        if ($postData["txtFromTenpoCD"] <> "" && $postData["txtToTenpoCD"] <> "") {
            $strSQL .= "  AND URI.TENPO_CD BETWEEN '@FromTenpoCD' AND '@ToTenpoCD'" . " \r\n";
        }
        //'日締日
        if ($postData["txtHJMFromDate"] <> "" && $postData["txtHJMToDate"] == "") {
            $strSQL .= "  AND URI.URIAGEDT >= '@HJMFromDate'" . " \r\n";
        }
        //20170222 lqs INS S
        if ($postData["txtHJMFromDate"] == "" && $postData["txtHJMToDate"] <> "") {
            $strSQL .= "  AND URI.URIAGEDT <= '@HJMToDate'" . " \r\n";
        }
        //20170222 lqs INS E
        if ($postData["txtHJMFromDate"] <> "" && $postData["txtHJMToDate"] <> "") {
            $strSQL .= "  AND URI.URIAGEDT BETWEEN '@HJMFromDate' AND '@HJMToDate'" . " \r\n";
        }
        //'経理担当の場合
        if ($postData["rdbKakunin"] == "rdbKakunin1") {
            //'経理担当コード
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] == "") {
                $strSQL .= "  AND SYN.KEIRI_SNN_TANTO_CD >= '@FromTantoCD'" . " \r\n";
            }
            //20170222 lqs INS S
            if ($postData["txtFromSyainCD"] == "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.KEIRI_SNN_TANTO_CD <= '@ToTantoCD'" . " \r\n";
            }
            //20170222 lqs INS E
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.KEIRI_SNN_TANTO_CD BETWEEN '@FromTantoCD' AND '@ToTantoCD'" . " \r\n";
            }
            //'未チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo1") {
                $strSQL .= "  AND DECODE(SYN.KEIRI_SNN_FLG,1,1,0) <> '1'" . " \r\n";
            }
            //'済チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo2") {
                $strSQL .= "  AND DECODE(SYN.KEIRI_SNN_FLG,1,1,0) = '1'" . " \r\n";
            }
        }

        //'店長の場合
        if ($postData["rdbKakunin"] == "rdbKakunin2") {
            //'店長コード
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] == "") {
                $strSQL .= "  AND SYN.TENCHO_SNN_TANTO_CD >= '@FromTantoCD'" . " \r\n";
            }
            //20170222 lqs INS S
            if ($postData["txtFromSyainCD"] == "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.TENCHO_SNN_TANTO_CD <= '@ToTantoCD'" . " \r\n";
            }
            //20170222 lqs INS E
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.TENCHO_SNN_TANTO_CD BETWEEN '@FromTantoCD' AND '@ToTantoCD'" . " \r\n";
            }
            //'未チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo1") {
                $strSQL .= "  AND DECODE(SYN.TENCHO_SNN_FLG,1,1,0) <> '1'" . " \r\n";
            }
            //'済チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo2") {
                $strSQL .= "  AND DECODE(SYN.TENCHO_SNN_FLG,1,1,0) = '1'" . " \r\n";
            }
        }

        //'課長の場合
        if ($postData["rdbKakunin"] == "rdbKakunin3") {
            //'課長コード
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] == "") {
                $strSQL .= "  AND SYN.KACHO_SNN_TANTO_CD >= '@FromTantoCD'" . " \r\n";
            }
            //20170222 lqs INS S
            if ($postData["txtFromSyainCD"] == "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.KACHO_SNN_TANTO_CD <= '@ToTantoCD'" . " \r\n";
            }
            //20170222 lqs INS E
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.KACHO_SNN_TANTO_CD BETWEEN '@FromTantoCD' AND '@ToTantoCD'" . " \r\n";
            }
            //'未チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo1") {
                $strSQL .= "  AND DECODE(SYN.KACHO_SNN_FLG ,1,1,0) <> '1'" . " \r\n";
            }
            //'済チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo2") {
                $strSQL .= "  AND DECODE(SYN.KACHO_SNN_FLG ,1,1,0) = '1'" . " \r\n";
            }
        }
        //'担当の場合
        if ($postData["rdbKakunin"] == "rdbKakunin4") {
            //'担当コード
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] == "") {
                $strSQL .= "  AND SYN.TAN_SNN_TANTO_CD >= '@FromTantoCD'" . " \r\n";
            }
            //20170222 lqs INS S
            if ($postData["txtFromSyainCD"] == "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.TAN_SNN_TANTO_CD <= '@ToTantoCD'" . " \r\n";
            }
            //20170222 lqs INS E
            if ($postData["txtFromSyainCD"] <> "" && $postData["txtToSyainCD"] <> "") {
                $strSQL .= "  AND SYN.TAN_SNN_TANTO_CD BETWEEN '@FromTantoCD' AND '@ToTantoCD'" . " \r\n";
            }
            //'未チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo1") {
                $strSQL .= "  AND DECODE(SYN.TAN_SNN_FLG,1,1,0) <> '1'" . " \r\n";
            }
            //'済チェックの場合
            if ($postData["rdbJyokyo"] == "rdbJyokyo2") {
                $strSQL .= "  AND DECODE(SYN.TAN_SNN_FLG,1,1,0) = '1'" . " \r\n";
            }
        }

        if ($strAuth[1] == "0") {
            //'部署別に権限管理を行う場合、部署を絞り込む
            $strSQL .= "AND      URI.TENPO_CD IN (SELECT BUSYO_CD" . " \r\n";
            $strSQL .= "                              FROM   HAUTHORITY_CTL" . " \r\n";
            $strSQL .= "                              WHERE  MENU_LIST_NO   = '@PRO_NO'" . " \r\n";
            $strSQL .= "                              AND    SYAIN_NO = '@SYAIN_NO'" . " \r\n";
            $strSQL .= "                              AND    HAUTH_ID = '@HAUTH_ID'" . " \r\n";
            $strSQL = str_replace("@PRO_NO", $clsProc->FncGetProgramNO($postData['Sys_KB'], "PPRM100ApproveStateSearch"), $strSQL);
            $strSQL = str_replace("@SYAIN_NO", $postData['login_user'], $strSQL);
            $strSQL = str_replace("@HAUTH_ID", $strAuth[0], $strSQL);
        }

        $strSQL .= " AND URI.URIAGEDT >= '20100201'" . " \r\n";
        $strSQL .= "GROUP BY URIAGEDT," . " \r\n";
        $strSQL .= "         URI.TENPO_CD," . " \r\n";
        $strSQL .= "         BS2.BUSYO_RYKNM," . " \r\n";
        $strSQL .= "         DECODE(SYN.KEIRI_SNN_FLG,1,1,0)," . " \r\n";
        $strSQL .= "         SUBSTR(SYN.KEIRI_SNN_TANTO_NM,1,INSTR(SYN.KEIRI_SNN_TANTO_NM,'　'))," . " \r\n";
        $strSQL .= "         DECODE(SYN.TENCHO_SNN_FLG,1,1,0)," . " \r\n";
        $strSQL .= "         SUBSTR(SYN.TENCHO_SNN_TANTO_NM,1,INSTR(SYN.TENCHO_SNN_TANTO_NM,'　'))," . " \r\n";
        $strSQL .= "         DECODE(SYN.KACHO_SNN_FLG ,1,1,0)," . " \r\n";
        $strSQL .= "         SUBSTR(SYN.KACHO_SNN_TANTO_NM,1,INSTR(SYN.KACHO_SNN_TANTO_NM,'　'))," . " \r\n";
        $strSQL .= "         DECODE(SYN.TAN_SNN_FLG,1,1,0)," . " \r\n";
        $strSQL .= "         SUBSTR(SYN.TAN_SNN_TANTO_NM,1,INSTR(SYN.TAN_SNN_TANTO_NM,'　'))" . " \r\n";
        //'ソート(2010/06/01 未と済みでソート順を変える場合は変更）
        $strSQL .= "ORDER BY URIAGEDT," . " \r\n";
        $strSQL .= "         URI.TENPO_CD," . " \r\n";
        $strSQL .= "         BS2.BUSYO_RYKNM" . " \r\n";

        //'値置換
        $strSQL = str_replace("@FromTenpoCD", $postData["txtFromTenpoCD"], $strSQL);
        $strSQL = str_replace("@ToTenpoCD", $postData["txtToTenpoCD"], $strSQL);
        $strSQL = str_replace("@HJMFromDate", str_replace("/", "", $postData["txtHJMFromDate"]), $strSQL);
        $strSQL = str_replace("@HJMToDate", str_replace("/", "", $postData["txtHJMToDate"]), $strSQL);
        $strSQL = str_replace("@FromTantoCD", $postData["txtFromSyainCD"], $strSQL);
        $strSQL = str_replace("@ToTantoCD", $postData["txtToSyainCD"], $strSQL);

        return parent::select($strSQL);
    }

    // '**********************************************************************
    // '処 理 名：初期設定
    // '関 数 名：SetInt
    // '引 数 　：$postData
    // '戻 り 値：検索結果
    // '処理説明：リストの初期設定を行う
    // '**********************************************************************
    public function SetInt($postData)
    {

        $strSQL = "";
        $strSQL .= "SELECT NVL(SYUKEI_KB,'0') SYUKEI_KB" . " \r\n";
        $strSQL .= ",      BUSYO_CD" . " \r\n";
        $strSQL .= "FROM   HBUSYO" . " \r\n";
        $strSQL .= "WHERE  MANEGER_CD = '@MCD'" . " \r\n";
        $strSQL .= "ORDER  BY NVL(SYUKEI_KB,'0') DESC" . " \r\n";
        //'条件置換
        $strSQL = str_replace("@MCD", $postData["strMCD"], $strSQL);
        return parent::select($strSQL);
    }
}
