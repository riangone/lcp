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

class PPRM703SyainSearch extends ClsComDb
{
    //'**********************************************************************
    //'処 理 名：社員データを取得
    //'関 数 名：FncGetSqlSYAYIN
    //'引 数   ：
    //'戻 り 値：
    //'処理説明：社員データを取得
    //'**********************************************************************

    public function FncGetSqlSYAYIN($data)
    {
        $postData = $data;
        $strSQL = "";
        $strSQL .= " SELECT " . " \r\n";
        $strSQL .= "       SYA.SYAIN_NO" . " \r\n";
        $strSQL .= " ,     SYA.SYAIN_NM" . " \r\n";
        $strSQL .= " FROM " . " \r\n";
        $strSQL .= "       HSYAINMST SYA" . " \r\n";
        $strSQL .= " LEFT JOIN " . " \r\n";
        $strSQL .= "       HHAIZOKU HAI" . " \r\n";
        $strSQL .= " ON " . " \r\n";
        $strSQL .= "       HAI.SYAIN_NO = SYA.SYAIN_NO" . " \r\n";
        $strSQL .= " AND " . " \r\n";
        $strSQL .= "       HAI.START_DATE <= TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= " AND " . " \r\n";
        $strSQL .= "       NVL(HAI.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= " WHERE " . " \r\n";
        $strSQL .= "      1=1" . " \r\n";
        if ($postData["txtShainnNo"] != NULL) {
            $strSQL .= " AND   SYA.SYAIN_NO      = '@SYAIN_NO' " . " \r\n";
            $strSQL = str_replace("@SYAIN_NO", $postData["txtShainnNo"], $strSQL);
        }
        if ($postData["txtShainnNM"] != NULL) {
            $strSQL .= " AND   SYA.SYAIN_NM   LIKE '@SYAIN_NM%' " . " \r\n";
            $strSQL = str_replace("@SYAIN_NM", $postData["txtShainnNM"], $strSQL);
        }
        if ($postData["txtShainnNM_Kana"] != NULL) {
            $strSQL .= " AND   SYA.SYAIN_KN   LIKE '@SYAIN_KN%' " . " \r\n";
            $strSQL = str_replace("@SYAIN_KN", $postData["txtShainnNM_Kana"], $strSQL);
        }
        if ($postData["txtBusyo"] != NULL) {
            $strSQL .= " AND   HAI.BUSYO_CD      = '@BUSYO_CD' " . " \r\n";
            $strSQL = str_replace("@BUSYO_CD", $postData["txtBusyo"], $strSQL);
        }
        $strSQL .= " ORDER BY " . " \r\n";
        $strSQL .= "       SYA.SYAIN_NO " . " \r\n";

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
        $strSQL .= "WHERE BUSYO_CD = '" . $postdata["txtBusyo"] . "'" . " \r\n";

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
    public function FncGetALLBusyoNM()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_CD,BUSYO_NM" . " \r\n";
        $strSQL .= "FROM    HBUSYO" . " \r\n";

        return parent::select($strSQL);
    }

    //20170908 ZHANGXIAOLEI INS E
}
