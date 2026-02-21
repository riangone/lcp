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

class PPRM800LoginList extends ClsComDb
{
    //'**********************************************************************
    //'処 理 名：検索ボタンクリック
    //'関 数 fncGetSqlHSYAINMST
    //'引 数 １：$LvTextUserID, $LvTextUserNM, $LvTextBusyoCD, $lvTaisyoku
    //'戻 り 値：SQL
    //'処理説明：ログイン情報を表示する
    //'**********************************************************************
    public function fncGetSqlHSYAINMST($LvTextUserID, $LvTextUserNM, $LvTextBusyoCD, $lvTaisyoku, $Sys_KB)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  BUS.BUSYO_NM , " . "\r\n";
        $strSql .= "  SYA.SYAIN_NO , " . "\r\n";
        $strSql .= "  SYA.SYAIN_NM , " . "\r\n";
        $strSql .= "  CASE WHEN LOG.USER_ID IS NOT NULL THEN '済' ELSE '未' END AS FLG , " . "\r\n";
        $strSql .= "  PTN.PATTERN_NM , " . "\r\n";
        $strSql .= "  CASE WHEN LOG.USER_ID IS NOT NULL THEN '1' ELSE '0' END AS KBN " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  HSYAINMST SYA " . "\r\n";
        $strSql .= "LEFT JOIN " . "\r\n";
        $strSql .= "  HHAIZOKU HAI " . "\r\n";
        $strSql .= "ON " . "\r\n";
        $strSql .= "  HAI.SYAIN_NO = SYA.SYAIN_NO " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  HAI.START_DATE <= TO_CHAR(SYSDATE, 'YYYYMMDD') " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  NVL(HAI.END_DATE, '99999999') >= TO_CHAR(SYSDATE, 'YYYYMMDD') " . "\r\n";
        $strSql .= "LEFT JOIN " . "\r\n";
        $strSql .= "  M_LOGIN LOG " . "\r\n";
        $strSql .= "ON " . "\r\n";
        $strSql .= "  LOG.USER_ID = SYA.SYAIN_NO " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  LOG.SYS_KB = '@SYS_KB' " . "\r\n";
        $strSql .= "LEFT JOIN " . "\r\n";
        $strSql .= "  HBUSYO BUS " . "\r\n";
        $strSql .= "ON " . "\r\n";
        $strSql .= "  BUS.BUSYO_CD = HAI.BUSYO_CD " . "\r\n";
        $strSql .= "LEFT JOIN " . "\r\n";
        $strSql .= "  HPATTERNMST PTN " . "\r\n";
        $strSql .= "ON " . "\r\n";
        $strSql .= "  PTN.SYS_KB = '@SYS_KB' " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  PTN.PATTERN_ID = LOG.PATTERN_ID " . "\r\n";

        $strSql .= "WHERE 1 = 1 " . "\r\n";
        if (trim($LvTextUserID) != "") {
            $strSql .= "AND " . "\r\n";
            $strSql .= "  SYA.SYAIN_NO = '@USER_ID'  " . "\r\n";
        }
        if (trim($LvTextUserNM) != "") {
            $strSql .= "AND " . "\r\n";
            $strSql .= "  SYA.SYAIN_NM LIKE '@USER_NM%' " . "\r\n";
        }
        if (trim($LvTextBusyoCD) != "") {
            $strSql .= "AND " . "\r\n";
            $strSql .= "  HAI.BUSYO_CD = '@BUSYO_CD' " . "\r\n";
        }
        if ($lvTaisyoku == "true") {
            $strSql .= "AND " . "\r\n";
            $strSql .= "  SYA.TAISYOKU_DATE IS NULL " . "\r\n";
        }

        $strSql .= "ORDER BY " . "\r\n";
        $strSql .= "  BUS.BUSYO_CD,SYA.SYAIN_NO" . "\r\n";
        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $Sys_KB, $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@USER_ID", $LvTextUserID, $strSql);
        $strSql = str_replace("@USER_NM", $LvTextUserNM, $strSql);
        $strSql = str_replace("@BUSYO_CD", $LvTextBusyoCD, $strSql);

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：ログインテーブル存在チェック
    //'関 数 名：FncCheckSQL
    //'引 数 １：$lblUserID(ユーザID)
    //'戻 り 値：SQL
    //'処理説明：ユーザID取得
    //'**********************************************************************
    public function FncCheckSQL($lblUserID, $Sys_KB)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  USER_ID " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  M_LOGIN " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYS_KB' " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  USER_ID = '@COMBO_LIST_ID' " . "\r\n";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $Sys_KB, $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@COMBO_LIST_ID", $lblUserID, $strSql);

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：ログインテーブル削除処理
    //'関 数 名：me.FncDeleteLOGIN
    //'引 数 １：$lblUserID(ユーザID)
    //'戻 り 値：SQL
    //'処理説明：ログインテーブルを削除する
    //'**********************************************************************
    public function FncDeleteLOGIN($lblUserID, $Sys_KB)
    {
        $strSql = "";
        $strSql .= "DELETE M_LOGIN " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  SYS_KB = '@SYS_KB' " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  USER_ID = '@USER_ID' " . "\r\n";

        //20170920 YIN UPD S
        // $strSql = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYS_KB", $Sys_KB, $strSql);
        //20170920 YIN UPD E
        $strSql = str_replace("@USER_ID", $lblUserID, $strSql);

        return parent::delete($strSql);
    }

}