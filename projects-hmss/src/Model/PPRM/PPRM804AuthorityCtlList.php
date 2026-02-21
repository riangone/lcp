<?php
/**
 * 説明：
 *
 *
 * @author CIYUANCHEN
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


class PPRM804AuthorityCtlList extends ClsComDb
{
    //'**********************************************************************
    //'処 理 名：社員別権限管理データを取得
    //'関 数 名：FncGetSql_HSYAINMST
    //'引 数 　：$data,$sysDate
    //'戻 り 値：SQL
    //'処理説明：社員別権限管理データを取得SQL
    //'**********************************************************************
    public function FncGetSql_HSYAINMST($data, $sysDate, $sys_kb)
    {
        $postData = $data;
        $strSQL = "";
        $strSQL .= " SELECT" . " \r\n";
        $strSQL .= "  BUS.BUSYO_CD" . " \r\n";
        $strSQL .= " , BUS.BUSYO_NM " . " \r\n";
        $strSQL .= "  , SYA.SYAIN_NO " . " \r\n";
        $strSQL .= " , SYA.SYAIN_NM " . " \r\n";
        $strSQL .= " , CASE WHEN CTL.SYAIN_NO IS NOT NULL THEN '済' ELSE '未' END " . " \r\n";
        $strSQL .= "            AS FLG " . " \r\n";
        $strSQL .= " , CASE WHEN CTL.SYAIN_NO IS NOT NULL THEN '1' ELSE '0' END " . " \r\n";
        $strSQL .= "            AS KBN " . " \r\n";
        $strSQL .= " , ROW_NUMBER() OVER(ORDER BY BUS.BUSYO_CD, SYA.SYAIN_NO) NO " . " \r\n";
        $strSQL .= " , HAI.START_DATE" . " \r\n";
        $strSQL .= " , DECODE(HAI.END_DATE,'99999999',HAI.END_DATE,HAI.END_DATE) END_DATE " . " \r\n";
        $strSQL .= " FROM HSYAINMST SYA " . " \r\n";
        $strSQL .= " LEFT JOIN HHAIZOKU HAI " . " \r\n";
        $strSQL .= "        ON HAI.SYAIN_NO = SYA.SYAIN_NO " . " \r\n";
        $strSQL .= "       AND HAI.START_DATE <= '@SYSDATE' " . " \r\n";
        $strSQL .= "       AND NVL(HAI.END_DATE, '99999999') >= '@SYSDATE'  " . " \r\n";
        $strSQL .= " LEFT JOIN HBUSYO BUS " . " \r\n";
        $strSQL .= "        ON BUS.BUSYO_CD = HAI.BUSYO_CD " . " \r\n";
        $strSQL .= " LEFT JOIN (SELECT SYS_KB, SYAIN_NO FROM HAUTHORITY_CTL GROUP BY SYS_KB, SYAIN_NO) CTL" . " \r\n";
        $strSQL .= " ON     CTL.SYS_KB = '@SYS_KB'" . " \r\n";
        $strSQL .= " AND    CTL.SYAIN_NO = SYA.SYAIN_NO" . " \r\n";
        $strSQL .= " WHERE 1 = 1 " . " \r\n";
        if ($postData["LvTextUserID"] != "") {
            $strSQL .= "   AND SYA.SYAIN_NO = '@USER_ID' " . " \r\n";
            $strSQL = str_replace("@USER_ID", $postData["LvTextUserID"], $strSQL);
        }
        if ($postData["LvTextUserNM"] != "") {
            $strSQL .= "   AND SYA.SYAIN_NM LIKE '@USER_NM%' " . " \r\n";
            $strSQL = str_replace("@USER_NM", $postData["LvTextUserNM"], $strSQL);
        }
        if ($postData["LvTextBusyoCD"] != "") {
            $strSQL .= "   AND HAI.BUSYO_CD = '@BUSYO_CD' " . " \r\n";
            $strSQL = str_replace("@BUSYO_CD", $postData["LvTextBusyoCD"], $strSQL);
        }
        if ($postData["chkTaisyoku"] == "true") {
            $strSQL .= "   AND SYA.TAISYOKU_DATE IS NULL" . " \r\n";
        }
        if ($postData["rdo"] == "2") {
            $strSQL .= "   AND CTL.SYAIN_NO IS NULL" . " \r\n";
        }
        if ($postData["rdo"] == "3") {
            $strSQL .= "   AND CTL.SYAIN_NO IS NOT NULL" . " \r\n";
        }
        $strSQL .= "  ORDER BY BUS.BUSYO_CD,SYA.SYAIN_NO " . " \r\n";
        //20170920 YIN UPD S
        // $strSQL = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSQL);
        $strSQL = str_replace("@SYS_KB", $sys_kb, $strSQL);
        //20170920 YIN UPD E
        $strSQL = str_replace("@SYSDATE", str_replace("/", "", substr($sysDate, 0, 10)), $strSQL);
        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：ログインテーブル削除処理
    //'関 数 名：FncDelete
    //'引 数 １：(I) $data
    //'戻 り 値：SQL
    //'処理説明：ログインテーブルを削除する
    //'**********************************************************************

    public function FncDelete($data, $sys_kb)
    {
        $postData = $data;
        $strSQL = "";
        $strSQL .= " DELETE " . " \r\n";
        $strSQL .= " FROM    HAUTHORITY_CTL " . " \r\n";
        $strSQL .= " WHERE SYS_KB = '@SYS_KB' " . " \r\n";
        $strSQL .= "   AND SYAIN_NO = '@SYAIN_NO' " . " \r\n";
        //20170920 YIN UPD S
        // $strSQL = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSQL);
        $strSQL = str_replace("@SYS_KB", $sys_kb, $strSQL);
        //20170920 YIN UPD E
        $strSQL = str_replace("@SYAIN_NO", $postData["SYAIN_NO"], $strSQL);
        return parent::delete($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：ログインテーブル存在チェック
    //'関 数 名：FncCheckSQL
    //'引 数 １：(I) $lblUserID
    //'戻 り 値：SQL
    //'処理説明：更新ボタン押下行の存在チェック処理
    //'**********************************************************************

    public function FncCheckSQL($lblUserID, $sys_kb)
    {
        $strSQL = "";
        $strSQL .= " SELECT  SYAIN_NO " . " \r\n";
        $strSQL .= " FROM    HAUTHORITY_CTL " . " \r\n";
        $strSQL .= "WHERE  SYS_KB = '@SYS_KB'" . " \r\n";
        $strSQL .= "AND    SYAIN_NO = '@SYAIN_NO'" . " \r\n";
        //20170920 YIN UPD S
        // $strSQL = str_replace("@SYS_KB", $ClsComFncPprm::GSYSTEM_KB_PPRM, $strSQL);
        $strSQL = str_replace("@SYS_KB", $sys_kb, $strSQL);
        //20170920 YIN UPD E
        $strSQL = str_replace("@SYAIN_NO", $lblUserID, $strSQL);
        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：部署名取得SQL
    //'関 数 名：FncGetBusyoNM
    //'引 数 　：$postdata
    //'戻 り 値：ＳＱＬ
    //'処理説明：部署名取得
    //'**********************************************************************
    public function FncGetBusyoNM($postdata)
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_NM" . " \r\n";
        $strSQL .= "FROM    HBUSYO" . " \r\n";
        $strSQL .= "WHERE BUSYO_CD = '" . $postdata["LvTextBusyoCD"] . "'" . " \r\n";

        return parent::select($strSQL);
    }

    //20170908 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部部署名取得SQL
    //'関 数 名：FncGetBusyoNM
    //'引 数 　：$postdata
    //'戻 り 値：ＳＱＬ
    //'処理説明：部署名取得
    //'**********************************************************************
    public function FncGetAllBusyoNM()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_CD,BUSYO_NM" . " \r\n";
        $strSQL .= "FROM    HBUSYO" . " \r\n";

        return parent::select($strSQL);
    }
    //20170908 ZHANGXIAOLEI INS E
}
