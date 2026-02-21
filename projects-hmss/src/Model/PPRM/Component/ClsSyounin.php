<?php
namespace App\Model\PPRM\Component;

use App\Model\Component\ClsComDb;

/**
 * 説明：
 *
 * システム名　　：ペーパーレスシステム
 * プログラム名　：帳票clsSyounin
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package ClsSyounin
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */

//'************************************************************
//'システム名　　：
//'プロセス名　　：共通
//'プログラム名　：共通関数
//'
class ClsSyounin extends ClsComDb
{
    //***********************************************
    // 処 理 名：承認確認SQL
    // 関 数 名：Syounin
    // 引 数   ：①strTCD（店舗コード）
    //         ：②strKIND（種類（0:事務、1:整備））
    //         ：③strHJMNo（日締№）
    // 戻 り 値：SQL
    // 処理説明：承認状況を確認する
    //************************************************
    public function SyouninSQL($strTCD, $strKIND, $strHJMNo)
    {
        $strSql = "";
        $strSql .= "SELECT KEIRI_SNN_FLG," . " \r\n";
        $strSql .= " TO_CHAR(KEIRI_SNN_DATE,'YYYY/MM/DD') AS KEIRI_SNN_DATE, " . " \r\n";
        $strSql .= " KEIRI_SNN_BUSYO_CD, " . " \r\n";
        $strSql .= " SUBSTR(KEIRI_SNN_TANTO_NM,1,INSTR(KEIRI_SNN_TANTO_NM,'　')-1) AS KEIRI_SNN_TANTO_NM, " . " \r\n";
        $strSql .= " TENCHO_SNN_FLG, " . " \r\n";
        $strSql .= " TO_CHAR(TENCHO_SNN_DATE,'YYYY/MM/DD') AS TENCHO_SNN_DATE, " . " \r\n";
        $strSql .= " TENCHO_SNN_BUSYO_CD, " . " \r\n";
        $strSql .= " SUBSTR(TENCHO_SNN_TANTO_NM,1,INSTR(TENCHO_SNN_TANTO_NM,'　')-1) AS TENCHO_SNN_TANTO_NM, " . " \r\n";
        $strSql .= " KACHO_SNN_FLG, " . " \r\n";
        $strSql .= " TO_CHAR(KACHO_SNN_DATE,'YYYY/MM/DD') AS KACHO_SNN_DATE, " . " \r\n";
        $strSql .= " KACHO_SNN_BUSYO_CD, " . " \r\n";
        $strSql .= " SUBSTR(KACHO_SNN_TANTO_NM,1,INSTR(KACHO_SNN_TANTO_NM,'　')-1) AS KACHO_SNN_TANTO_NM, " . " \r\n";
        $strSql .= " TAN_SNN_FLG, " . " \r\n";
        $strSql .= " TO_CHAR(TAN_SNN_DATE,'YYYY/MM/DD') AS TAN_SNN_DATE, " . " \r\n";
        $strSql .= " TAN_SNN_BUSYO_CD, " . " \r\n";
        $strSql .= " SUBSTR(TAN_SNN_TANTO_NM,1,INSTR(TAN_SNN_TANTO_NM,'　')-1) AS TAN_SNN_TANTO_NM, " . " \r\n";
        $strSql .= " TO_CHAR(TAN_SNN_DATE,'YYYY/MM/DD  HH24:MI:SS') AS CRE_DTM, " . " \r\n";
        $strSql .= " TAN_SNN_TANTO_CD || '　' || TAN_SNN_TANTO_NM AS CRE_NM " . " \r\n";
        $strSql .= " FROM PPRHJMAPPROVEDATA " . " \r\n";
        $strSql .= " WHERE 1=1 " . " \r\n";
        $strSql .= " AND TENPO_CD = '@TCD' " . " \r\n";
        //'条件
        if ($strKIND == "1") {
            $strSql .= " AND HJM_KIND = '1' " . " \r\n";
            $strSql .= " AND TEN_HJM_NO = '@HJMNo' " . " \r\n";
        } else {
            $strSql .= " AND HJM_KIND = '2' " . " \r\n";
        }
        $strSql = str_replace("@TCD", $strTCD, $strSql);
        $strSql = str_replace("@HJMNo", $strHJMNo, $strSql);

        return parent::select($strSql);
    }

    //***********************************************
    // 処 理 名：店舗名（略式）取得
    // 関 数 名：getBusyoRNM
    // 処理説明：店舗名（略式）を取得する
    //***********************************************
    public function getBusyoRNM($strCD)
    {
        // 初期化
        $strSql = "";
        $strSql .= "" . " \r\n";
        //20171011 YIN UPD S
        // $strSql .= " SELECT SUBSTR(BUSYO_RYKNM,1,3) AS BusyoRNM " . " \r\n";
        $strSql .= " SELECT BUSYO_RYKNM AS BusyoRNM " . " \r\n";
        //20171011 YIN UPD E
        $strSql .= " FROM HBUSYO " . " \r\n";
        $strSql .= " WHERE 1=1 " . " \r\n";
        $strSql .= " AND BUSYO_CD = '@BCD' " . " \r\n";

        $strSql = str_replace("@BCD", $strCD, $strSql);

        return parent::select($strSql);
    }

    //***********************************************
    // '処 理 名：和暦取得
    // '関 数 名：chgWAREKI
    // '処理説明：西暦（文字列YY/MM/DD）を和暦に変換する
    //***********************************************
    public function chgWAREKI($strYmd)
    {
        // $strData = "";
        // $strTrm = "";
        $strKbn = "";

        if (!is_numeric($strYmd)) {
            //異常終了
            return "";
        }

        //区分変換

        //20171011 YIN DEL S
        // if ($strYmd >= 18670000 && $strYmd < 19120000)
        // {
        // $strTrm = '明治';
        // $strData = $strYmd - 18670000;
        // }
// 
        // if ($strYmd >= 19120000 && $strYmd < 19260000)
        // {
        // $strTrm = '大正';
        // $strData = $strYmd - 19120000;
        // }
// 
        // if ($strYmd >= 19260000 && $strYmd < 19890000)
        // {
        // $strTrm = '昭和';
        // $strData = $strYmd - 19260000;
        // }
// 
        // if ($strYmd >= 19890000)
        // {
        // $strTrm = '平成';
        // $strData = $strYmd - 19890000;
        // }
        //20171011 YIN DEL E
        //20170925 YIN UPD S
        // $strKbn = $strTrm . (substr($strData, 0, 2) + 1) . "/" . substr($strData, 2, 2) . "/" . substr($strData, 4, 2);
        //20171011 YIN UPD S
        // $strKbn = (substr($strData, 0, 2) + 1) . "." . substr($strData, 2, 2) . "." . substr($strData, 4, 2);
        $strKbn = (substr($strYmd, 2, 2)) . "." . substr($strYmd, 4, 2) . "." . substr($strYmd, 6, 2);
        //20171011 YIN UPD E
        //20170925 YIN UPD E

        //正常終了
        return $strKbn;

    }

}

//'************************************************************
//'Copyright(C) 2005 NIPPONJIMUKI Co. All Rights Reserved
//'************************************************************
