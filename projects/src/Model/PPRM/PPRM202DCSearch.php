<?php

/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
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
class PPRM202DCSearch extends ClsComDb
{
    public $BTN_PRINT = "btnPrintView";
    public $BTN_IMAGE = "btnImageDisp";
    public $BTN_REF = "btnRef";

    //'**********************************************************************
    //'処 理 名：検索条件に一致する値を取得する
    //'関 数 名：fncSelectHJM
    //'引 数 　：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：対象が事務の場合（日締データ）
    //'**********************************************************************
    public function fncSelectHJM($postData)
    {
        $clsProc = new ClsProc();
        $strSQL = "";
        $strSQL .= "SELECT TO_CHAR(KNR.HJM_SYR_DTM,'YYYY/MM/DD HH24:MI:SS') AS HJM_SYR_DTM," . " \r\n";
        $strSQL .= "       BS2.BUSYO_RYKNM," . " \r\n";
        $strSQL .= "       KNR.TEN_HJM_NO," . " \r\n";
        $strSQL .= "       KNR.EGK_KEJ_KENSU," . " \r\n";
        $strSQL .= "       NVL2(KNR.EGK_STA_DEN_NO,KNR.EGK_STA_DEN_NO || ' ～ ' || KNR.EGK_END_DEN_NO,'') AS DENPYO1," . " \r\n";
        $strSQL .= "       KNR.KMY_KEJ_KENSU," . " \r\n";
        $strSQL .= "       NVL2(KNR.KMY_STA_DEN_NO,KNR.KMY_STA_DEN_NO || ' ～ ' || KNR.KMY_END_DEN_NO,'') AS DENPYO2," . " \r\n";
        $strSQL .= "       KNR.CRD_DEN_DTL_KEJ_KENSU," . " \r\n";
        $strSQL .= "       NVL2(KNR.CRD_DEN_DTL_STA_DEN_NO,KNR.CRD_DEN_DTL_STA_DEN_NO || ' ～ ' || KNR.CRD_DEN_DTL_END_DEN_NO,'') AS DENPYO3," . " \r\n";
        $strSQL .= "       KNR.SIR_DEN_DTL_KEJ_KENSU," . " \r\n";
        $strSQL .= "       NVL2(KNR.SIR_DEN_DTL_STA_DEN_NO,KNR.SIR_DEN_DTL_STA_DEN_NO || ' ～ ' || KNR.SIR_DEN_DTL_END_DEN_NO,'') AS DENPYO4," . " \r\n";
        $strSQL .= "       KNR.FRK_DEN_DTL_KEJ_KENSU," . " \r\n";
        $strSQL .= "       NVL2(KNR.FRK_DEN_DTL_STA_DEN_NO,KNR.FRK_DEN_DTL_STA_DEN_NO || ' ～ ' || KNR.FRK_DEN_DTL_END_DEN_NO,'') AS DENPYO5," . " \r\n";
        $strSQL .= "       KNR.ETC_DEN_DTL_KEJ_KENSU," . " \r\n";
        $strSQL .= "       NVL2(KNR.ETC_DEN_DTL_STA_DEN_NO,KNR.ETC_DEN_DTL_STA_DEN_NO || ' ～ ' || KNR.ETC_DEN_DTL_END_DEN_NO,'') AS DENPYO6," . " \r\n";
        $strSQL .= "       KNR.TENPO_CD," . " \r\n";
        $strSQL .= "       NVL2(IMG.TENPO_CD,'1','') AS SONZAI" . " \r\n";
        //部署別に権限管理を行う必要があるかのフラグを取得する
        $strAuth = $clsProc->SubSetEnabled_Control($postData['Sys_KB'], "PPRM202_DC_Search", $postData['login_user'], $postData['BusyoCd'], "btnSearch");

        if ($strAuth[1] == "0") {
            $strRet = $clsProc->FncGetAuthInfo($postData['Sys_KB'], "PPRM202_DC_Search", $this->BTN_PRINT, $postData['login_user']);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO) FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = KNR.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') PRINT_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' PRINT_DISP_FLG" . " \r\n";
            }
            $strRet = $clsProc->FncGetAuthInfo($postData['Sys_KB'], "PPRM202_DC_Search", $this->BTN_IMAGE, $postData['login_user']);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO) FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = KNR.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') IMAGE_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' IMAGE_DISP_FLG" . " \r\n";
            }
            $strRet = $clsProc->FncGetAuthInfo($postData['Sys_KB'], "PPRM202_DC_Search", $this->BTN_REF, $postData['login_user']);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO) FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = KNR.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') KINSYU_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' KINSYU_DISP_FLG" . " \r\n";
            }
        } else {
            $strSQL .= ",      '1' PRINT_DISP_FLG" . " \r\n";
            $strSQL .= ",      '1' IMAGE_DISP_FLG" . " \r\n";
            $strSQL .= ",      '1' KINSYU_DISP_FLG" . " \r\n";
        }
        $strSQL .= "FROM   M41F11 KNR" . " \r\n";
        $strSQL .= "  LEFT JOIN (SELECT TENPO_CD," . " \r\n";
        $strSQL .= "                  TEN_HJM_NO" . " \r\n";
        $strSQL .= "             FROM PPRIMAGEFILEDATA IMG" . " \r\n";
        $strSQL .= "             GROUP BY TENPO_CD,TEN_HJM_NO" . " \r\n";
        $strSQL .= "             ) IMG" . " \r\n";
        $strSQL .= "         ON KNR.TENPO_CD = IMG.TENPO_CD" . " \r\n";
        $strSQL .= "        AND KNR.TEN_HJM_NO = IMG.TEN_HJM_NO" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS1" . " \r\n";
        $strSQL .= "         ON KNR.TENPO_CD = BS1.BUSYO_CD" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS2" . " \r\n";
        $strSQL .= "         ON BS1.TENPO_CD = BS2.BUSYO_CD" . " \r\n";
        $strSQL .= "WHERE 1=1" . " \r\n";
        //店舗コード
        if ($postData["request"]["txtFromTenpoCD"] != "" && $postData["request"]["txtToTenpoCD"] == "") {
            $strSQL .= "  AND KNR.TENPO_CD >= '" . $postData["request"]["txtFromTenpoCD"] . "'" . " \r\n";
        }
        if ($postData["request"]["txtFromTenpoCD"] == "" && $postData["request"]["txtToTenpoCD"] != "") {
            $strSQL .= "  AND KNR.TENPO_CD <= '" . $postData["request"]["txtToTenpoCD"] . "'" . " \r\n";
        }
        if ($postData["request"]["txtFromTenpoCD"] != "" && $postData["request"]["txtToTenpoCD"] != "") {
            $strSQL .= "  AND KNR.TENPO_CD BETWEEN '" . $postData["request"]["txtFromTenpoCD"] . "' AND '" . $postData["request"]["txtToTenpoCD"] . "'" . " \r\n";
        }
        //日締日
        if ($postData["request"]["txtHJMFromDate"] != "" && $postData["request"]["txtHJMToDate"] == "") {
            $strSQL .= "  AND TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') >= '" . str_replace("/", "", $postData["request"]["txtHJMFromDate"]) . "'" . " \r\n";
        }
        if ($postData["request"]["txtHJMFromDate"] == "" && $postData["request"]["txtHJMToDate"] != "") {
            $strSQL .= "  AND TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') <= '" . str_replace("/", "", $postData["request"]["txtHJMToDate"]) . "'" . " \r\n";
        }
        if ($postData["request"]["txtHJMFromDate"] != "" && $postData["request"]["txtHJMToDate"] != "") {
            $strSQL .= "  AND TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') BETWEEN '" . str_replace("/", "", $postData["request"]["txtHJMFromDate"]) . "' AND '" . str_replace("/", "", $postData["request"]["txtHJMToDate"]) . "'" . " \r\n";
        }
        //日締№
        if ($postData["request"]["tdtxtHJM"] != NULL) {
            $strSQL .= "  AND KNR.TEN_HJM_NO = '" . $postData["request"]["tdtxtHJM"] . "'" . " \r\n";
        }
        if ($strAuth[1] == "0") {
            //部署別に権限管理を行う場合、部署を絞り込む
            $strSQL .= "AND      KNR.TENPO_CD IN (SELECT BUSYO_CD" . " \r\n";
            $strSQL .= "                              FROM   HAUTHORITY_CTL" . " \r\n";
            $strSQL .= "                              WHERE  MENU_LIST_NO   = '" . $clsProc->FncGetProgramNO($postData['Sys_KB'], "PPRM202_DC_SEARCH") . "'" . " \r\n";
            $strSQL .= "                              AND    SYAIN_NO = '@SYAIN_NO'" . " \r\n";
            $strSQL .= "                              AND    HAUTH_ID = '@HAUTH_ID')" . " \r\n";
            $strSQL = str_replace("@SYAIN_NO", $postData['login_user'], $strSQL);
            $strSQL = str_replace("@HAUTH_ID", $strAuth[0], $strSQL);
        }
        $strSQL .= "ORDER BY TO_CHAR(KNR.HJM_SYR_DTM,'YYYYMMDD') DESC," . " \r\n";
        $strSQL .= "        KNR.TENPO_CD" . " \r\n";
        $strSQL .= ",        KNR.TEN_HJM_NO DESC" . " \r\n";

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：検索条件に一致する値を取得する
    //'関 数 名：fncSelectURI
    //'引 数 　：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：対象が整備の場合（売上データ）
    //'**********************************************************************
    public function fncSelectURI($postData)
    {
        $clsProc = new ClsProc();
        $strSQL = "";
        //20170925 lqs UPD S
        //$strSQL .= "SELECT TO_CHAR(TO_DATE(URI.URIAGEDT),'YYYY/MM/DD') AS URIAGEDT," . " \r\n";
        $strSQL .= "SELECT TO_CHAR(TO_DATE(URI.URIAGEDT,'YYYY/MM/DD'),'YYYY/MM/DD') AS URIAGEDT," . " \r\n";
        //20170925 lqs UPD E
        $strSQL .= "       BS2.BUSYO_RYKNM," . " \r\n";
        $strSQL .= "       URI.TENPO_CD" . " \r\n";
        //部署別に権限管理を行う必要があるかのフラグを取得する
        $strAuth = $clsProc->SubSetEnabled_Control($postData['Sys_KB'], "PPRM202_DC_Search", $postData['login_user'], $postData['BusyoCd'], "btnSearch");
        if ($strAuth[1] == "0") {
            $strRet = $clsProc->FncGetAuthInfo($postData['Sys_KB'], "PPRM202_DC_Search", $this->BTN_PRINT, $postData['login_user']);
            if ($strRet[1] == "0") {
                $strSQL .= ",      (SELECT COUNT(SYAIN_NO) FROM HAUTHORITY_CTL CTL " . " \r\n";
                $strSQL .= "         WHERE CTL.BUSYO_CD = KNR.TENPO_CD AND MENU_LIST_NO = '@PRO_NO' AND  HAUTH_ID = '@HAUTH_ID' AND CTL.SYAIN_NO = '@SYAIN_NO') IMAGE_DISP_FLG" . " \r\n";
                $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
            } else {
                $strSQL .= ",      '1' IMAGE_DISP_FLG" . " \r\n";
            }
        } else {
            $strSQL .= ",      '1' PRINT_DISP_FLG" . " \r\n";
        }
        $strSQL .= "FROM   M41S30 URI" . " \r\n";
        $strSQL .= "INNER JOIN M41S40 S40 ON S40.SEB_NOU_NO = URI.SEB_NOU_NO AND S40.DENPYOKB = URI.DENPYOKB" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS1" . " \r\n";
        $strSQL .= "         ON URI.TENPO_CD = BS1.BUSYO_CD" . " \r\n";
        $strSQL .= "  LEFT JOIN HBUSYO BS2" . " \r\n";
        $strSQL .= "         ON BS1.TENPO_CD = BS2.BUSYO_CD" . " \r\n";
        $strSQL .= "WHERE 1=1" . " \r\n";
        //店舗コード
        if ($postData["request"]["txtFromTenpoCD"] != "") {
            $strSQL .= "  AND URI.TENPO_CD >= '" . $postData["request"]["txtFromTenpoCD"] . "'" . " \r\n";
        }
        if ($postData["request"]["txtFromTenpoCD"] == "" and $postData["request"]["txtToTenpoCD"] != "") {
            $strSQL .= "  AND URI.TENPO_CD >= '" . $postData["request"]["txtToTenpoCD"] . "'" . " \r\n";
        }
        if ($postData["request"]["txtFromTenpoCD"] != "" and $postData["request"]["txtToTenpoCD"] != "") {
            $strSQL .= "  AND URI.TENPO_CD BETWEEN '" . $postData["request"]["txtFromTenpoCD"] . "' AND '" . $postData["request"]["txtToTenpoCD"] . "'" . " \r\n";
        }
        //売上日
        if ($postData["request"]["txtHJMFromDate"] != "" and $postData["request"]["txtHJMToDate"] == "") {
            $strSQL .= "  AND URI.URIAGEDT >= '" . str_replace("/", "", $postData["request"]["txtHJMFromDate"]) . "'" . " \r\n";
        }
        if ($postData["request"]["txtHJMFromDate"] == "" and $postData["request"]["txtHJMToDate"] != "") {
            $strSQL .= "  AND URI.URIAGEDT <= '" . str_replace("/", "", $postData["request"]["txtHJMToDate"]) . "'" . " \r\n";
        }
        if ($postData["request"]["txtHJMFromDate"] != "" and $postData["request"]["txtHJMToDate"] != "") {
            //20170906 ZHANGXIAOLEI UPD S
            // $strSQL .= "  AND URI.URIAGEDT BETWEEN '" . str_replace("/", "", $postData["request"]["txtHJMFromDate"]) . " AND " . str_replace("/", "", $postData["request"]["txtHJMToDate"]) . "'" . " \r\n";
            $strSQL .= "  AND URI.URIAGEDT BETWEEN '" . str_replace("/", "", $postData["request"]["txtHJMFromDate"]) . "' AND '" . str_replace("/", "", $postData["request"]["txtHJMToDate"]) . "'" . " \r\n";
            //20170906 ZHANGXIAOLEI UPD E
        }
        if ($strAuth[1] == "0") {
            //部署別に権限管理を行う場合、部署を絞り込む
            $strSQL .= "AND      URI.TENPO_CD IN (SELECT BUSYO_CD" . " \r\n";
            $strSQL .= "                              FROM   HAUTHORITY_CTL" . " \r\n";
            $strSQL .= "                              WHERE  MENU_LIST_NO   = '" . $clsProc->FncGetProgramNO($postData['Sys_KB'], "PPRM202_DC_SEARCH") . "'" . " \r\n";
            $strSQL .= "                              AND    SYAIN_NO = '@SYAIN_NO'" . " \r\n";
            $strSQL .= "                              AND    HAUTH_ID = '@HAUTH_ID'" . " \r\n";
            $strSQL = str_replace("@SYAIN_NO", $postData['login_user'], $strSQL);
            $strSQL = str_replace("@HAUTH_ID", $strRet[0], $strSQL);
        }
        $strSQL .= " AND URI.URIAGEDT >= '20100201'" . " \r\n";
        //グループ化
        $strSQL .= "GROUP BY URIAGEDT," . " \r\n";
        $strSQL .= "         BS2.BUSYO_RYKNM," . " \r\n";
        $strSQL .= "         URI.TENPO_CD" . " \r\n";
        //ソート
        $strSQL .= "ORDER BY URIAGEDT DESC," . " \r\n";
        $strSQL .= "         URI.TENPO_CD," . " \r\n";
        $strSQL .= "         BS2.BUSYO_RYKNM" . " \r\n";

        return parent::select($strSQL);
    }

}