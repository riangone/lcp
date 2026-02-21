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
 * 20170503           #                            SQLフォーマットが改正する            WANG
 * 20170504				#					　　	jqgrid機能が改正する					YIN
 * 20170522				#					　　	jqgrid機能が改正する					LQS
 * * --------------------------------------------------------------------------------------------
 */

//共通クラスの読込み
namespace App\Model\APPM;

use App\Model\Component\ClsComDb;

//*************************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************************
class FrmMessejiIchiranSansho extends ClsComDb
{
    public function Search()
    {
        $strSQL = "";
        $strSQL .= "SELECT MESSEJI_ID" . " \r\n";
        $strSQL .= ",MESSEJI_NAIYO1||MESSEJI_NAIYO2||MESSEJI_NAIYO3 AS MESSEJI_NAIYO" . " \r\n";
        $strSQL .= "FROM T_MESSEJI" . " \r\n";

        return parent::select($strSQL);
    }

    public function fncSearchData($flg)
    {
        $strSQL = "";
        $strSQL .= "SELECT" . " \r\n";
        //内容区分
        $strSQL .= "DISTINCT NAIBU_CD_MEISHO" . " \r\n";
        $strSQL .= ",NAIBU_CD" . " \r\n";
        $strSQL .= "FROM M_CODE " . " \r\n";
        if ($flg == "1") {
            $strSQL .= "WHERE GAIBU_CD='001' " . " \r\n";
        }
        if ($flg == "2") {
            $strSQL .= "WHERE GAIBU_CD='005' " . " \r\n";
        }
        if ($flg == "3") {
            $strSQL .= "WHERE GAIBU_CD='016' " . " \r\n";
        }
        $strSQL .= "AND DEL_FLG='00' " . " \r\n";

        return parent::select($strSQL);
    }

    //20170504 YIN UPD S
    // public function msgSearch($postData)
    //20170522 LQS UPD S
    //public function msgSearch($postData, $sortStr)
    public function msgSearch($postData, $sortStr)
    //20170522 LQS UPD E
    //20170504 YIN UPD E
    {
        //20170504 YIN INS S
        $sortString = "  ";
        if (trim($sortStr) != "") {
            $sortString .= " ORDER BY " . $sortStr . "";
        } else {
            $sortString .= " ORDER BY MESSEJI_RIYO_KIKAN_FROM DESC \n";
        }
        //20170504 YIN INS E

        $strSQL = "";
        $strSQL .= "SELECT MESSEJI_ID" . " \r\n";
        $strSQL .= ",MESSEJI_NAIYO1||MESSEJI_NAIYO2||MESSEJI_NAIYO3 AS MESSEJI_NAIYO" . " \r\n";
        $strSQL .= ",TO_CHAR(TO_DATE(MESSEJI_RIYO_KIKAN_FROM,'YYYY/MM/DD'),'YYYY/MM/DD') AS MESSEJI_RIYO_KIKAN_FROM" . " \r\n";
        $strSQL .= ",TO_CHAR(TO_DATE(MESSEJI_RIYO_KIKAN_TO,'YYYY/MM/DD'),'YYYY/MM/DD') AS MESSEJI_RIYO_KIKAN_TO" . " \r\n";
        $strSQL .= ",NAIYO_KBN" . " \r\n";
        $strSQL .= ",NRQF.NAIBU_CD_MEISHO AS NAIYO_NAME" . " \r\n";
        $strSQL .= ",CASE WHEN KONTAKUTO_BOTAN_FLG='00' THEN '□'" . " \r\n";
        $strSQL .= "WHEN KONTAKUTO_BOTAN_FLG='01' THEN '■'" . " \r\n";
        $strSQL .= "ELSE '' END AS KONTAKUTO_BOTAN_FLG" . " \r\n";
        $strSQL .= ",CASE WHEN SHIJO_YOYAKU_BOTAN_FLG='00' THEN '□'" . " \r\n";
        $strSQL .= "WHEN SHIJO_YOYAKU_BOTAN_FLG='01' THEN '■'" . " \r\n";
        $strSQL .= "ELSE '' END AS SHIJO_YOYAKU_BOTAN_FLG" . " \r\n";
        $strSQL .= ",CASE WHEN NYUKO_YOYAKU_BOTAN_FLG='00' THEN '□'" . " \r\n";
        $strSQL .= "WHEN NYUKO_YOYAKU_BOTAN_FLG='01' THEN '■'" . " \r\n";
        $strSQL .= "ELSE '' END AS NYUKO_YOYAKU_BOTAN_FLG" . " \r\n";
        $strSQL .= ",RENKEI_KBN" . " \r\n";
        $strSQL .= ",LXQF.NAIBU_CD_MEISHO AS RENKEI_NAME" . " \r\n";
        $strSQL .= ",KIDOKU_KAKUNIN_FLG" . " \r\n";
        $strSQL .= ",YW.NAIBU_CD_MEISHO AS KIDOKU_KAKUNIN_NAME" . " \r\n";
        $strSQL .= ",DEL_FLG" . " \r\n";
        $strSQL .= "FROM T_MESSEJI " . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT GAIBU_CD,NAIBU_CD,NAIBU_CD_MEISHO FROM M_CODE WHERE GAIBU_CD='001') NRQF ON T_MESSEJI.NAIYO_KBN=NRQF.NAIBU_CD " . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT GAIBU_CD,NAIBU_CD,NAIBU_CD_MEISHO FROM M_CODE WHERE GAIBU_CD='005') LXQF ON T_MESSEJI.RENKEI_KBN=LXQF.NAIBU_CD " . " \r\n";
        $strSQL .= " LEFT JOIN (SELECT GAIBU_CD,NAIBU_CD,NAIBU_CD_MEISHO FROM M_CODE WHERE GAIBU_CD='002') YW ON T_MESSEJI.KIDOKU_KAKUNIN_FLG=YW.NAIBU_CD  " . " \r\n";
        $strSQL .= "WHERE 1=1" . " \r\n";
        $strSQL .= "AND MESSEJI_RIYO_KIKAN_FROM <= '@txtDate'" . " \r\n";
        $strSQL .= "AND MESSEJI_RIYO_KIKAN_TO >= '@txtDate'" . " \r\n";
        //選択された内容によって抽出条件を追加します
        if ($postData['request']['CONTENT'] != '') {
            $strSQL .= "AND NAIYO_KBN = '" . $postData['request']['CONTENT'] . "'" . " \r\n";
        }
        //選択された内容によって抽出条件を追加します
        if ($postData['request']['RENKEI'] != '') {
            $strSQL .= "AND RENKEI_KBN = '" . $postData['request']['RENKEI'] . "'" . " \r\n";
        }
        //選択された内容によって抽出条件を追加します
        if ($postData['request']['DEL_FLG'] != '') {
            $strSQL .= "AND DEL_FLG = '" . $postData['request']['DEL_FLG'] . "'" . " \r\n";
        }
        if ($postData['request']['MESSAGE'] != '') {
            //20170519 LQS UPD S
            //$strSQL .= "AND MESSEJI_ID LIKE '" . $postData['request']['MESSAGE'] . "%'" . " \r\n";
            $strSQL .= "AND MESSEJI_ID LIKE '@msgId%'" . " \r\n";
            //20170519 LQS UPD E
        }
        //20170519 LQS INS S
        $strSQL = str_replace("@msgId", str_replace("'", "''", $postData['request']['MESSAGE']), $strSQL);
        //20170519 LQS INS E
        //20170504 YIN UPD S
        //$strSQL .= "ORDER BY MESSEJI_RIYO_KIKAN_FROM DESC" . " \r\n";
        $strSQL .= $sortString;
        //20170504 YIN UPD E
        //20170522 LQS INS S
        // if (trim($limit) != "" && trim($start) != "")
        // {
        // $cell = "*";
        // $start = " WHERE RNM >" . $start;
        // $limit = " WHERE ROWNUM<=" . $limit;
        // $strSQL = "SELECT " . $cell . " FROM (SELECT TBL." . $cell . ",ROWNUM RNM FROM ( " . $strSQL . ") TBL " . $limit . ") " . $start;
        // }
        //20170522 LQS INS E

        //20170503 WANG UPD S
        //$strSQL = str_replace("@txtDate", $postData['request']["txtDate"] = str_replace("/", "", $postData['request']["txtDate"]), $strSQL);
        $strSQL = str_replace("@txtDate", str_replace("/", "", $postData['request']["txtDate"]), $strSQL);
        //20170503 WANG UPD E
        return parent::select($strSQL);
    }

}