<?php
/**
 * 説明：
 *
 *
 * @author YANGYANG
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */

namespace App\Model\PPRM;

use App\Model\Component\ClsComDb;

class PPRM802MenuAuthMstMnt extends ClsComDb
{

    //'***********************************************************************
    //'処 理 名：左側のjqGridテーブルを取得する
    //'関 数 名：getLjqGridData
    //'引 数   ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：左側のjqGridテーブルを取得する
    //'***********************************************************************
    public function getLjqGridData($sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  PATTERN_ID , " . "\r\n";
        $strSql .= "  PATTERN_NM  " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  HPATTERNMST " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYS_KB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  STYLE_ID = '001'" . "\r\n";
        $strSql .= "ORDER BY " . "\r\n";
        $strSql .= "  PATTERN_ID ";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $sys_kb, $strSql);
        //20170920 YIN UPD E

        return parent::select($strSql);
    }

    //'***********************************************************************
    //'処 理 名：選択ボタンのイベント
    //'関 数 名：gvRights_SelectedIndexChanged
    //'引 数   ：$PTNID
    //'戻 り 値：ＳＱＬ
    //'処理説明：取得データを権限管理ﾃｰﾌﾞﾙの生成
    //'***********************************************************************
    public function gvRights_SelectedIndexChanged($PTNID, $sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  PRO.PRO_NO , " . "\r\n";
        $strSql .= "  (CASE WHEN PTN.PRO_NO IS NOT NULL THEN 1 ELSE 0 END) KBN , " . "\r\n";
        $strSql .= "  PRO.PRO_NM , " . "\r\n";
        $strSql .= "  PTN.CREATE_DATE  " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  HPROGRAMMST PRO " . "\r\n";
        $strSql .= "LEFT JOIN " . "\r\n";
        $strSql .= "  HMENUKANRIPATTERN PTN " . "\r\n";
        $strSql .= "ON " . "\r\n";
        $strSql .= "  PTN.SYS_KB = '@SYSKB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PTN.STYLE_ID = '001'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PTN.PATTERN_ID = '@PTNID'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PRO.PRO_NO = PTN.PRO_NO " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  PRO.SYS_KB = '@SYSKB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PRO.MENU_DISP_FLG = '1'" . "\r\n";
        $strSql .= "ORDER BY " . "\r\n";
        $strSql .= "  PRO.PRO_NO ";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYSKB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYSKB", $sys_kb, $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@PTNID", $PTNID, $strSql);

        return parent::select($strSql);
    }

    //'***********************************************************************
    //'処 理 名：追加ボタンのイベント
    //'関 数 名：btnAdd_click
    //'引 数   ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：取得データを権限管理ﾃｰﾌﾞﾙの生成
    //'***********************************************************************
    public function btnAdd_click($sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  PRO_NO , " . "\r\n";
        $strSql .= "  0 KBN , " . "\r\n";
        $strSql .= "  PRO_NM , " . "\r\n";
        $strSql .= "  '' CREATE_DATE  " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  HPROGRAMMST " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYSKB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  MENU_DISP_FLG = '1'";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYSKB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYSKB", $sys_kb, $strSql);
        //20170920 YIN UPD E

        return parent::select($strSql);
    }

    //'***********************************************************************
    //'処 理 名：メニュー権限名称ﾃﾞｰﾀの取得
    //'関 数 名：getPatternID
    //'引 数   ：$txtRightsID
    //'戻 り 値：ＳＱＬ
    //'処理説明：登録処理(メニュー権限名称ﾃﾞｰﾀの取得)
    //'***********************************************************************
    public function getPatternID($txtRightsID, $sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  PATTERN_ID " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  HPATTERNMST " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYSKB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  STYLE_ID = '@STYLEID'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PATTERN_ID = '@PTNID'";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYSKB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYSKB", $sys_kb, $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@STYLEID", "001", $strSql);
        $strSql = str_replace("@PTNID", $txtRightsID, $strSql);

        return parent::select($strSql);
    }

    //'***********************************************************************
    //'処 理 名：登録処理を行う
    //'関 数 名：UpdateHPATTERNMST
    //'引 数   ：$txtRightsID, $txtRightsName
    //'戻 り 値：ＳＱＬ
    //'処理説明：登録処理(登録処理を行う)
    //'***********************************************************************
    public function UpdateHPATTERNMST($txtRightsID, $txtRightsName, $session)
    {
        $strSql = "";
        $strSql .= "UPDATE  HPATTERNMST " . "\r\n";
        $strSql .= "SET " . "\r\n";
        $strSql .= "  PATTERN_NM = '@PATTERN_NM', " . "\r\n";
        $strSql .= "  UPD_DATE = sysdate , " . "\r\n";
        $strSql .= "  UPD_SYA_CD = '@UPD_SYA_CD', " . "\r\n";
        $strSql .= "  UPD_PRG_ID = 'MenuAuthMstMnt', " . "\r\n";
        $strSql .= "  UPD_CLT_NM = '@UPD_CLT_NM' " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYS_KB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  STYLE_ID = '001'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PATTERN_ID = '@PATTERN_ID'";

        $strSql = str_replace("@PATTERN_NM", $txtRightsName, $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $session["login_user"], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $session["MachineNM"], $strSql);
        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $session['Sys_KB'], $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@PATTERN_ID", $txtRightsID, $strSql);

        return parent::update($strSql);
    }

    //'***********************************************************************
    //'処 理 名：登録処理を行う
    //'関 数 名：InsertHPATTERNMST
    //'引 数   ：$txtRightsID, $txtRightsName
    //'戻 り 値：ＳＱＬ
    //'処理説明：登録処理(登録処理を行う)
    //'***********************************************************************
    public function InsertHPATTERNMST($txtRightsID, $txtRightsName, $session)
    {
        $strSql = "";
        $strSql .= "INSERT INTO HPATTERNMST" . "\r\n";
        $strSql .= "           (SYS_KB" . "\r\n";
        $strSql .= ",           STYLE_ID" . "\r\n";
        $strSql .= ",           PATTERN_ID" . "\r\n";
        $strSql .= ",           PATTERN_NM" . "\r\n";
        $strSql .= ",           UPD_DATE" . "\r\n";
        $strSql .= ",           CREATE_DATE" . "\r\n";
        $strSql .= ",           UPD_SYA_CD" . "\r\n";
        $strSql .= ",           UPD_PRG_ID" . "\r\n";
        $strSql .= ",           UPD_CLT_NM)" . "\r\n";
        $strSql .= " VALUES     " . "\r\n";
        $strSql .= "(           '@SYSKB'" . "\r\n";
        $strSql .= ",           '001'" . "\r\n";
        $strSql .= ",           '@PATTERN_ID'" . "\r\n";
        $strSql .= ",           '@PATTERN_NM'" . "\r\n";
        $strSql .= ",           sysdate " . "\r\n";
        $strSql .= ",           sysdate " . "\r\n";
        $strSql .= ",           '@UPD_SYA_CD'" . "\r\n";
        $strSql .= ",           'MenuAuthMstMnt'" . "\r\n";
        $strSql .= ",           '@UPD_CLT_NM'" . "\r\n";
        $strSql .= ")" . "\r\n";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYSKB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYSKB", $session['Sys_KB'], $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@PATTERN_ID", $txtRightsID, $strSql);
        $strSql = str_replace("@PATTERN_NM", $txtRightsName, $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $session["login_user"], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $session["MachineNM"], $strSql);

        return parent::insert($strSql);
    }

    //'***********************************************************************
    //'処 理 名：メニュー権限管理マスタを削除する
    //'関 数 名：DeleteHMENUKANRIPATTERN
    //'引 数   ：$txtRightsID
    //'戻 り 値：ＳＱＬ
    //'処理説明：登録/削除処理(メニュー権限管理マスタを削除する)
    //'***********************************************************************
    public function DeleteHMENUKANRIPATTERN($txtRightsID, $sys_kb)
    {
        $strSql = "";
        $strSql .= "DELETE HMENUKANRIPATTERN " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYSKB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  STYLE_ID = '001'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PATTERN_ID = '@PTNID'";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYSKB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYSKB", $sys_kb, $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@PTNID", $txtRightsID, $strSql);

        return parent::delete($strSql);
    }

    //'***********************************************************************
    //'処 理 名：権限管理ﾃｰﾌﾞﾙ_追加にチェックが入っている場合
    //'関 数 名：InsertHMENUKANRIPATTERN
    //'引 数   ：$txtRightsID, $PRONO, $KBN, $PRONM, $CREATEDATE
    //'戻 り 値：ＳＱＬ
    //'処理説明：登録処理(権限管理ﾃｰﾌﾞﾙ_追加にチェックが入っている場合)
    //'***********************************************************************
    public function InsertHMENUKANRIPATTERN($txtRightsID, $PRONO, $CREATEDATE, $session)
    {
        $strSql = "";
        $strSql .= "INSERT INTO HMENUKANRIPATTERN" . "\r\n";
        $strSql .= "           (SYS_KB" . "\r\n";
        $strSql .= ",           PATTERN_ID" . "\r\n";
        $strSql .= ",           STYLE_ID" . "\r\n";
        $strSql .= ",           PRO_NO" . "\r\n";
        $strSql .= ",           UPD_DATE" . "\r\n";
        $strSql .= ",           CREATE_DATE" . "\r\n";
        $strSql .= ",           UPD_SYA_CD" . "\r\n";
        $strSql .= ",           UPD_PRG_ID" . "\r\n";
        $strSql .= ",           UPD_CLT_NM)" . "\r\n";
        $strSql .= " VALUES     " . "\r\n";
        $strSql .= "(           '@SYSKB'" . "\r\n";
        $strSql .= ",           '@PATTERN_ID'" . "\r\n";
        $strSql .= ",           '001'" . "\r\n";
        $strSql .= ",           '@PRO_NO'" . "\r\n";
        $strSql .= ",           sysdate " . "\r\n";
        $strSql .= ",           DECODE('@CREDT','',SYSDATE,TO_DATE('@CREDT','YYYY/MM/DD HH24:MI:SS')) " . "\r\n";
        $strSql .= ",           '@UPD_SYA_CD'" . "\r\n";
        $strSql .= ",           'MenuAuthMstMnt'" . "\r\n";
        $strSql .= ",           '@UPD_CLT_NM'" . "\r\n";
        $strSql .= ")" . "\r\n";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYSKB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYSKB", $session['Sys_KB'], $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@PATTERN_ID", $txtRightsID, $strSql);
        $strSql = str_replace("@PRO_NO", $PRONO, $strSql);
        $strSql = str_replace("@CREDT", $CREATEDATE, $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $session["login_user"], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $session["MachineNM"], $strSql);

        return parent::insert($strSql);
    }

    //'***********************************************************************
    //'処 理 名：メニュー権限管理マスタを削除する
    //'関 数 名：DeleteHPATTERNMST
    //'引 数   ：$txtRightsID
    //'戻 り 値：ＳＱＬ
    //'処理説明：削除処理(メニュー権限名称マスタを削除する)
    //'***********************************************************************
    public function DeleteHPATTERNMST($txtRightsID, $sys_kb)
    {
        $strSql = "";
        $strSql .= "DELETE HPATTERNMST " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYSKB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  STYLE_ID = '001'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PATTERN_ID = '@PTNID'";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYSKB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYSKB", $sys_kb, $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@PTNID", $txtRightsID, $strSql);

        return parent::delete($strSql);
    }

}