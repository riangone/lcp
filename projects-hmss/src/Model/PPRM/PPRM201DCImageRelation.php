<?php

/**
 * 説明：
 *
 * システム名　　：ペーパーレスシステム
 * プログラム名　：イメージファイル関連付け
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package $filename
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         GSDL
 * --------------------------------------------------------------------------------------------
 */

namespace App\Model\PPRM;

use App\Model\Component\ClsComDb;
use App\Model\PPRM\Component\ClsProc;

class PPRM201DCImageRelation extends ClsComDb
{

    //'**********************************************************************
    //'処 理 名：検索条件に一致する値を取得する
    //'関 数 名：fncSelectSearch
    //'引 数 　：店舗コード,日締日,日締№,ｲﾒｰｼﾞﾌｧｲﾙ
    //'戻 り 値：イメージファイル関連付け
    //'処理説明：検索条件に一致する値を取得する
    //'**********************************************************************
    public function fncSelectSearch($postData)
    {
        $clsProc = new ClsProc();
        $strSQL = "";
        $strSQL .= "SELECT KNR.TEN_HJM_NO," . " \r\n";
        $strSQL .= "       TO_CHAR(KNR.HJM_SYR_DTM,'YYYY/MM/DD HH24:MI:SS') AS HJM_SYR_DTM," . " \r\n";
        $strSQL .= "       BS2.BUSYO_RYKNM," . " \r\n";
        $strSQL .= "       KNR.TENPO_CD," . " \r\n";
        $strSQL .= "       NVL2(IMG.IMAGE_FILE_ID,'1','0') AS IMAGE_EXISTS" . " \r\n";
        //'部署別に権限管理を行う必要があるかのフラグを取得する
        $strAuth = $clsProc->SubSetEnabled_Control($postData['Sys_KB'], "PPRM201_DC_ImageRelation", $postData['login_user'], $postData['BusyoCD'], "btnSearch");
        if ($strAuth[1] == "0") {
            $strRet = $clsProc->FncGetAuthInfo($postData['Sys_KB'], "PPRM201_DC_ImageRelation", $postData['BTN_PRINT'], $postData['login_user']);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO) FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = KNR.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') PRINT_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' PRINT_DISP_FLG" . " \r\n";
            }
            //'イメージファイル表示ボタンの権限IDを取得
            $strRet = $clsProc->FncGetAuthInfo($postData['Sys_KB'], "PPRM201_DC_ImageRelation", $postData['BTN_IMAGE'], $postData['login_user']);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO) FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = KNR.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') IMAGE_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' IMAGE_DISP_FLG" . " \r\n";
            }
            //'明細表示ボタンの権限IDを取得
            $strRet = $clsProc->FncGetAuthInfo($postData['Sys_KB'], "PPRM201_DC_ImageRelation", $postData['BTN_MEISAI'], $postData['login_user']);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO) FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = KNR.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') MEISAI_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' MEISAI_DISP_FLG" . " \r\n";
            }
        } else {
            $strSQL .= ",      '1' PRINT_DISP_FLG" . " \r\n";
            $strSQL .= ",      '1' IMAGE_DISP_FLG" . " \r\n";
            $strSQL .= ",      '1' MEISAI_DISP_FLG" . " \r\n";
        }
        $strSQL .= "FROM M41F11 KNR" . " \r\n";
        $strSQL .= "  LEFT JOIN PPRIMAGEFILEDATA IMG" . " \r\n";
        $strSQL .= "         ON KNR.TENPO_CD = IMG.TENPO_CD" . " \r\n";
        $strSQL .= "        AND KNR.TEN_HJM_NO = IMG.TEN_HJM_NO" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS1" . " \r\n";
        $strSQL .= "         ON KNR.TENPO_CD = BS1.BUSYO_CD" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS2" . " \r\n";
        $strSQL .= "         ON BS1.TENPO_CD = BS2.BUSYO_CD" . " \r\n";
        $strSQL .= "WHERE 1=1" . " \r\n";
        //'店舗コード
        if ($postData['txtFromTenpoCD'] != "" && $postData['txtToTenpoCD'] == "") {
            $strSQL .= "  AND KNR.TENPO_CD >= '@FromTenpoCD'" . " \r\n";
        }
        if ($postData['txtFromTenpoCD'] == "" && $postData['txtToTenpoCD'] != "") {
            $strSQL .= "  AND KNR.TENPO_CD <= '@ToTenpoCD'" . " \r\n";
        }
        if ($postData['txtFromTenpoCD'] != "" && $postData['txtToTenpoCD'] != "") {
            $strSQL .= "  AND KNR.TENPO_CD BETWEEN '@FromTenpoCD' AND '@ToTenpoCD'" . " \r\n";
        }
        //'日締日
        if ($postData['txtHJMFromDate'] != "" && $postData['txtHJMToDate'] == "") {
            $strSQL .= "  AND TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') >= '@HJMFromDate'" . " \r\n";
        }
        if ($postData['txtHJMFromDate'] == "" && $postData['txtHJMToDate'] != "") {
            $strSQL .= "  AND TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') <= '@HJMToDate'" . " \r\n";
        }
        if ($postData['txtHJMFromDate'] != "" && $postData['txtHJMToDate'] != "") {
            $strSQL .= "  AND TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') BETWEEN '@HJMFromDate' AND '@HJMToDate'" . " \r\n";
        }
        //'日締№
        if ($postData['txtHJMNo'] != "") {
            $strSQL .= "  AND KNR.TEN_HJM_NO = '@HJMNo'" . " \r\n";
        }
        //'イメージファイル
        if ($postData['rdbImage'] == "1") {
            $strSQL .= "  AND NVL2(IMG.IMAGE_FILE_ID,'1','0') = '0'" . " \r\n";
        }
        if ($postData['rdbImage'] == "2") {
            $strSQL .= "  AND NVL2(IMG.IMAGE_FILE_ID,'1','0') = '1'" . " \r\n";
        }
        if ($strAuth[1] == "0") {
            //'部署別に権限管理を行う場合、部署を絞り込む
            $strSQL .= "AND      KNR.TENPO_CD IN (SELECT BUSYO_CD" . " \r\n";
            $strSQL .= "                              FROM   HAUTHORITY_CTL" . " \r\n";
            $strSQL .= "                              WHERE  MENU_LIST_NO   = '@PRO_NO'" . " \r\n";
            $strSQL .= "                              AND    SYAIN_NO = '@SYAIN_NO'" . " \r\n";
            $strSQL .= "                              AND    HAUTH_ID = '@HAUTH_ID')" . " \r\n";
            $strSQL = str_replace("@SYAIN_NO", $postData['login_user'], $strSQL);
            $strSQL = str_replace("@HAUTH_ID", $strAuth[0], $strSQL);
        }
        //'グループ
        $strSQL .= "GROUP BY KNR.TEN_HJM_NO," . " \r\n";
        $strSQL .= "         HJM_SYR_DTM," . " \r\n";
        $strSQL .= "         BS2.BUSYO_RYKNM," . " \r\n";
        $strSQL .= "         KNR.TENPO_CD," . " \r\n";
        $strSQL .= "         NVL2(IMG.IMAGE_FILE_ID,'1','0')" . " \r\n";
        //'ソート
        $strSQL .= "ORDER BY TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') DESC," . " \r\n";
        $strSQL .= "         KNR.TENPO_CD" . " \r\n";
        $strSQL .= ",        KNR.TEN_HJM_NO DESC" . " \r\n";
        //'値置換
        $strSQL = str_replace("@FromTenpoCD", $postData["txtFromTenpoCD"], $strSQL);
        $strSQL = str_replace("@ToTenpoCD", $postData["txtToTenpoCD"], $strSQL);
        $strSQL = str_replace("@HJMFromDate", str_replace("/", "", $postData["txtHJMFromDate"]), $strSQL);
        $strSQL = str_replace("@HJMToDate", str_replace("/", "", $postData["txtHJMToDate"]), $strSQL);
        $strSQL = str_replace("@HJMNo", $postData["txtHJMNo"], $strSQL);

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：検索条件に一致する値を取得する
    //'関 数 名：fncSelectSearch2
    //'引 数 　：日締№
    //'戻 り 値：明細データ
    //'処理説明：検索条件に一致する値を取得する
    //'**********************************************************************
    public function fncSelectSearch2($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT IMAGE_FILE_ID," . " \r\n";
        $strSQL .= "       IMAGE_FILE_NM," . " \r\n";
        $strSQL .= "       SAVE_PATH" . " \r\n";
        $strSQL .= "FROM PPRIMAGEFILEDATA" . " \r\n";
        $strSQL .= "WHERE 1=1" . " \r\n";
        $strSQL .= "  AND TEN_HJM_NO = '@HJMNo'" . " \r\n";
        //'ソート
        $strSQL .= "ORDER BY IMAGE_FILE_ID DESC" . " \r\n";
        //'値置換
        $strSQL = str_replace("@HJMNo", $postData["strHjmNo"], $strSQL);

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：イメージファイルデータ登録
    //'関 数 名：InsertImageFile
    //'引 数 　：イメージファイルデータ,店舗コード,日締№,
    //'戻 り 値：なし
    //'処理説明：イメージファイルデータ登録
    //'**********************************************************************
    public function InsertImageFile($postData, $tenpoCd, $HJMNo)
    {

        $strSQL = "";
        $strSQL .= "INSERT INTO PPRIMAGEFILEDATA" . " \r\n";
        $strSQL .= " (" . " \r\n";
        $strSQL .= "  IMAGE_FILE_ID," . " \r\n";
        $strSQL .= "  IMAGE_FILE_NM," . " \r\n";
        $strSQL .= "  SAVE_PATH," . " \r\n";
        $strSQL .= "  TORIKOMI_DATE," . " \r\n";
        $strSQL .= "  TENPO_CD," . " \r\n";
        $strSQL .= "  TEN_HJM_NO," . " \r\n";
        $strSQL .= "  UPD_BUSYO_CD," . " \r\n";
        $strSQL .= "  UPD_SYA_CD," . " \r\n";
        $strSQL .= "  UPD_CLT_NM," . " \r\n";
        $strSQL .= "  UPD_DATE," . " \r\n";
        $strSQL .= "  UPD_PRG_ID" . " \r\n";
        $strSQL .= " )" . " \r\n";
        $strSQL .= " VALUES" . " \r\n";
        $strSQL .= " (" . " \r\n";
        $strSQL .= "  '@IMAGE_FILE_ID'," . " \r\n";
        $strSQL .= "  '@IMAGE_FILE_NM'," . " \r\n";
        $strSQL .= "  '@SAVE_PATH'," . " \r\n";
        $strSQL .= "  SYSDATE," . " \r\n";
        $strSQL .= "  '@TENPO_CD'," . " \r\n";
        $strSQL .= "  '@TEN_HJM_NO'," . " \r\n";
        $strSQL .= "  '@UPD_BUSYO_CD'," . " \r\n";
        $strSQL .= "  '@UPD_SYA_CD'," . " \r\n";
        $strSQL .= "  '@UPD_CLT_NM'," . " \r\n";
        $strSQL .= "  SYSDATE," . " \r\n";
        $strSQL .= "  '@UPD_PRG_ID'" . " \r\n";
        $strSQL .= " )" . " \r\n";
        $strID = $this->createID();
        //'値置換
        $strSQL = str_replace("@IMAGE_FILE_ID", $strID, $strSQL);
        $strSQL = str_replace("@IMAGE_FILE_NM", $postData["IMAGE_FILE_NM"], $strSQL);
        $strSQL = str_replace("@SAVE_PATH", $postData["SAVE_PATH"], $strSQL);
        $strSQL = str_replace("@TENPO_CD", $tenpoCd, $strSQL);
        $strSQL = str_replace("@TEN_HJM_NO", $HJMNo, $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $postData["BusyoCD"], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $postData["login_user"], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $postData["MachineNM"], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", $postData["strProgramID"], $strSQL);

        return parent::insert($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：イメージファイルデータ削除
    //'関 数 名：DeleteImageFile
    //'引 数 　：strID
    //'戻 り 値：なし
    //'処理説明：イメージファイルデータ削除
    //'**********************************************************************
    public function DeleteImageFile($strID)
    {
        $strSQL = "";

        $strSQL .= "DELETE PPRIMAGEFILEDATA" . " \r\n";
        $strSQL .= "WHERE IMAGE_FILE_ID = '@strID'" . " \r\n";
        //'値置換
        $strSQL = str_replace("@strID", $strID, $strSQL);

        return parent::delete($strSQL);
    }


    //'**********************************************************************
    //'処 理 名：登録データの件数確認
    //'関 数 名：checkNUM
    //'引 数 　：店舗コード,日締№,
    //'戻 り 値：検索結果
    //'処理説明：登録データの件数確認（100件以上登録できないようにする）
    //'**********************************************************************
    public function checkNUM($tenpoCd, $HJMNo)
    {
        $strSQL = "";

        $strSQL .= "SELECT SAVE_PATH" . " \r\n";
        $strSQL .= "FROM   PPRIMAGEFILEDATA" . " \r\n";
        $strSQL .= "WHERE TENPO_CD = '@TenpoCode'" . " \r\n";
        $strSQL .= "  AND TEN_HJM_NO = '@HJMNo'" . " \r\n";
        //'値置換
        $strSQL = str_replace("@TenpoCode", $tenpoCd, $strSQL);
        $strSQL = str_replace("@HJMNo", $HJMNo, $strSQL);
        //'接続
        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：イメージファイルID作成
    //'関 数 名：createID
    //'引 数 　：なし
    //'戻 り 値：イメージファイルID
    //'処理説明：イメージファイルID作成・チェック
    //'**********************************************************************
    public function createID()
    {
        $createID = "001" . date("Ymdhms");

        $strSQL = "";
        $strSQL .= "SELECT COUNT(IMAGE_FILE_ID) CNT" . " \r\n";
        $strSQL .= "      ,MAX(IMAGE_FILE_ID) IMAGE_FILE_ID" . " \r\n";
        $strSQL .= "FROM PPRIMAGEFILEDATA" . " \r\n";
        $strSQL .= "WHERE IMAGE_FILE_ID LIKE '@ID%'" . " \r\n";
        //'値置換
        $strSQL = str_replace("@ID", $createID, $strSQL);
        //'データを抽出する
        $deployData = parent::select($strSQL);

        $CNT = intval($deployData['data'][0]['CNT']);
        if ($CNT > 0) {
            $createID = $deployData['data'][0]['IMAGE_FILE_ID'];
            $createID = substr($createID, 0, 17) . sprintf("%03s", intval(substr($createID, 17, 3)) + 1);
        } else {
            $createID = $createID . "001";
        }

        return $createID;

    }


}
