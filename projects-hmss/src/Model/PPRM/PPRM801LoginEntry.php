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

class PPRM801LoginEntry extends ClsComDb
{
    //'***********************************************************************
    //'処 理 名：権限ドロップダウンリスト表示
    //'関 数 名：subComboSet
    //'引 数 1 ：なし
    //'戻 り 値：SQL
    //'処理説明：権限ドロップダウンリスト表示
    //'***********************************************************************
    public function subComboSet($sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT " . "\r\n";
        $strSql .= "  V.PATTERN_ID , " . "\r\n";
        $strSql .= "  V.PATTERN_NM " . "\r\n";
        $strSql .= "FROM (" . "\r\n";
        $strSql .= "SELECT " . "\r\n";
        $strSql .= "  PTN.PATTERN_ID , " . "\r\n";
        $strSql .= "  PTN.PATTERN_NM , " . "\r\n";
        $strSql .= "  '2' KBN " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  HPATTERNMST PTN " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  PTN.SYS_KB = '@SYSKB' " . "\r\n";
        $strSql .= "UNION ALL " . "\r\n";
        $strSql .= "SELECT " . "\r\n";
        $strSql .= "  NULL , " . "\r\n";
        $strSql .= "  NULL , " . "\r\n";
        $strSql .= "  '1' KBN " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  DUAL " . "\r\n";
        $strSql .= ") V " . "\r\n";

        $strSql .= "ORDER BY " . "\r\n";
        $strSql .= "  V.KBN, V.PATTERN_ID " . "\r\n";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYSKB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYSKB", $sys_kb, $strSql);
        //20170920 YIN UPD E

        return parent::select($strSql);
    }

    //'***********************************************************************
    //'処 理 名：ﾛｸﾞｲﾝ情報データを取得する
    //'関 数 名：subInfoSet
    //'引 数 1 ：$LvTextUserID
    //'戻 り 値：SQL
    //'処理説明：ﾛｸﾞｲﾝ情報データを取得する
    //'***********************************************************************
    public function subInfoSet($LvTextUserID, $sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT " . "\r\n";
        $strSql .= "  LOG.USER_ID , " . "\r\n";
        $strSql .= "  LOG.PASSWORD , " . "\r\n";
        $strSql .= "  LOG.PATTERN_ID , " . "\r\n";
        $strSql .= "  LOG.REC_CRE_DT " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  M_LOGIN LOG " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  LOG.USER_ID = '@USERID' " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  LOG.SYS_KB = '@SYSKB' " . "\r\n";

        $strSql = str_replace("@USERID", $LvTextUserID, $strSql);
        //20170920 YIN UPD S
        // $strSql = str_replace("@SYSKB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYSKB", $sys_kb, $strSql);
        //20170920 YIN UPD E

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：ログイン情報の削除処理
    //'関 数 fncDeleteLogin
    //'引 数 １：$LvTextUserID
    //'戻 り 値：SQL
    //'処理説明：ログイン情報の削除処理
    //'**********************************************************************/
    public function fncDeleteLogin($LvTextUserID, $sys_kb)
    {
        $strSql = "";
        $strSql .= "DELETE M_LOGIN " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYS_KB' " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  USER_ID = '@USER_ID' " . "\r\n";

        $strSql = str_replace("@USER_ID", $LvTextUserID, $strSql);
        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $sys_kb, $strSql);
        //20170920 YIN UPD E
        return parent::delete($strSql);
    }

    //'**********************************************************************
    //'処 理 名：ログイン情報の追加処理
    //'関 数 fncInsertLogin
    //'引 数 １：$LvTextUserID, $LvTextPass, $ddlRights
    //'戻 り 値：SQL
    //'処理説明：ログイン情報の追加処理
    //'**********************************************************************/
    public function fncInsertLogin($LvTextUserID, $LvTextPass, $ddlRights, $session)
    {
        $strSql = "";
        $strSql .= "INSERT INTO M_LOGIN" . "\r\n";
        $strSql .= "           (SYS_KB" . "\r\n";
        $strSql .= ",           USER_ID" . "\r\n";
        $strSql .= ",           PASSWORD" . "\r\n";
        $strSql .= ",           STYLE_ID" . "\r\n";
        $strSql .= ",           PATTERN_ID" . "\r\n";
        $strSql .= ",           REC_UPD_DT" . "\r\n";
        $strSql .= ",           REC_CRE_DT" . "\r\n";
        $strSql .= ",           UPD_SYA_CD" . "\r\n";
        $strSql .= ",           UPD_PRG_ID" . "\r\n";
        $strSql .= ",           UPD_CLT_NM)" . "\r\n";
        $strSql .= " VALUES     " . "\r\n";
        $strSql .= "(           '@SYS_KB'" . "\r\n";
        $strSql .= ",           '@USER_ID'" . "\r\n";
        $strSql .= ",           '@PASSWORD'" . "\r\n";
        $strSql .= ",           '@STYLE_ID'" . "\r\n";
        $strSql .= ",           '@PATTERN_ID'" . "\r\n";
        $strSql .= ",  sysdate" . "\r\n";
        $strSql .= ",  sysdate" . "\r\n";
        $strSql .= ",           '@LOGIN_ID'" . "\r\n";
        $strSql .= ",           '@PROGRAM_ID'" . "\r\n";
        $strSql .= ",           '@MACHINE_NM'" . "\r\n";
        $strSql .= ")" . "\r\n";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $session['Sys_KB'], $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@USER_ID", $LvTextUserID, $strSql);
        $strSql = str_replace("@PASSWORD", $LvTextPass, $strSql);
        $strSql = str_replace("@STYLE_ID", "001", $strSql);
        $strSql = str_replace("@LOGIN_ID", $session["login_user"], $strSql);
        $strSql = str_replace("@PROGRAM_ID", "strprogramid", $strSql);
        $strSql = str_replace("@MACHINE_NM", $session["MachineNM"], $strSql);
        $strSql = str_replace("@PATTERN_ID", $ddlRights, $strSql);
        return parent::insert($strSql);
    }


}