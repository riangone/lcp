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

class PPRM803MenuNameMstMnt extends ClsComDb
{

    //'***********************************************************************
    //'処 理 名：プログラムマスタ取得
    //'関 数 名：FncGetSql_HPROGRAMMST
    //'引 数 1 ：なし
    //'戻 り 値：SQL
    //'処理説明：プログラムマスタを取得
    //'***********************************************************************/
    public function FncGetSql_HPROGRAMMST($sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  ROW_NUMBER() OVER(ORDER BY PRO_NO) NO , " . "\r\n";
        $strSql .= "  PRO_NM , " . "\r\n";
        $strSql .= "  NVL(USER_AUTH_CTL_FLG,'') AS USER_AUTH_CTL_FLG , " . "\r\n";
        $strSql .= "  CASE NVL(USER_AUTH_CTL_FLG,'')  " . "\r\n";
        $strSql .= "  WHEN '0' THEN '管理しない'    " . "\r\n";
        $strSql .= "  WHEN '1' THEN '管理する'    " . "\r\n";
        $strSql .= "  ELSE '' END AS USER_AUTH_CTL_NM , " . "\r\n";
        $strSql .= "  PRO_NO , " . "\r\n";
        $strSql .= "  UPD_DATE , " . "\r\n";
        $strSql .= "  NVL(SYSTEM_AUTH_CTL_FLG,'0') USES_COUNT " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  HPROGRAMMST " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYS_KB'" . "\r\n";
        $strSql .= "ORDER BY " . "\r\n";
        $strSql .= "  PRO_NO ";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $sys_kb, $strSql);
        //20170920 YIN UPD E

        return parent::select($strSql);
    }

    //'***********************************************************************
    //'処 理 名：プログラムマスタ存在チェック
    //'関 数 名：CheckSQL
    //'引 数 １：$lblProNO
    //'戻 り 値：SQL
    //'処理説明：更新ボタン押下行の存在チェック処理
    //'***********************************************************************/
    public function FncCheckSQL($lblProNO, $sys_kb)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  UPD_DATE " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  HPROGRAMMST " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYS_KB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PRO_NO = '@PRO_NO'";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $sys_kb, $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@PRO_NO", $lblProNO, $strSql);

        return parent::select($strSql);
    }

    //'***********************************************************************
    //'処 理 名：プログラムマスタ更新処理
    //'関 数 名：FncUpdate_HPROGRAMMST
    //'引 数 １：$lblProNO, $txtProName, $ddlUserAuthCtlFlg
    //'戻 り 値：SQL
    //'処理説明：プログラムマスタを更新する
    //'***********************************************************************/
    public function FncUpdate_HPROGRAMMST($lblProNO, $txtProName, $ddlUserAuthCtlFlg, $session)
    {
        $strSql = "";
        $strSql .= "UPDATE  HPROGRAMMST " . "\r\n";
        $strSql .= "SET " . "\r\n";
        $strSql .= "  PRO_NM = '@PRO_NM', " . "\r\n";
        $strSql .= "  USER_AUTH_CTL_FLG = '@USER_AUTH_CTL_FLG', " . "\r\n";
        $strSql .= "  UPD_DATE = @SYS_DATE, " . "\r\n";
        $strSql .= "  UPD_SYA_CD = '@LOGIN_ID', " . "\r\n";
        $strSql .= "  UPD_PRG_ID = 'MenuNameMstMnt', " . "\r\n";
        $strSql .= "  UPD_CLT_NM = '@MACHINE_NM' " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYS_KB'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PRO_NO = '@PRO_NO'";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $session['Sys_KB'], $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@PRO_NO", $lblProNO, $strSql);
        $strSql = str_replace("@PRO_NM", $txtProName, $strSql);
        $strSql = str_replace("@USER_AUTH_CTL_FLG", $ddlUserAuthCtlFlg, $strSql);
        $strSql = str_replace("@SYS_DATE", 'SYSDATE', $strSql);
        $strSql = str_replace("@LOGIN_ID", $session["login_user"], $strSql);
        $strSql = str_replace("@MACHINE_NM", $session["MachineNM"], $strSql);

        return parent::update($strSql);
    }

}