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

class PPRM705R4BusyoSearch extends ClsComDb
{
    //'**********************************************************************
    //'処 理 名：部署データを取得
    //'関 数 名：getDeployDataSQL
    //'引 数 　：なし
    //'戻 り 値：String
    //'処理説明：部署データを取得SQL
    //'**********************************************************************
    public function getDeployDataSQL($data)
    {
        $postData = $data;
        $sql = "";
        $sql .= "SELECT " . " \r\n";
        $sql .= "      BUS.KYOTN_CD BUSYO_CD" . " \r\n";
        // if ($postData["hidTKB"] == "1") {
        $sql .= ",        TEN.BUSYO_RYKNM BUSYO_NM" . " \r\n";
        // } else {
        //     $sql .= " ,    BUS.KYOTN_RKN BUSYO_NM" . " \r\n";
        // }
        $sql .= " FROM " . " \r\n";
        $sql .= "     M27M01 BUS" . " \r\n";
        $sql .= " LEFT JOIN HBUSYO HBUS" . " \r\n";
        $sql .= " ON  BUS.KYOTN_CD = HBUS.BUSYO_CD" . " \r\n";
        $sql .= " LEFT JOIN HBUSYO TEN" . " \r\n";
        $sql .= " ON  TEN.BUSYO_CD = HBUS.TENPO_CD" . " \r\n";
        $sql .= " WHERE " . " \r\n";
        $sql .= "      1=1" . " \r\n";
        $sql .= " AND BUS.HANSH_CD = '3634'" . " \r\n";
        $sql .= " AND BUS.ES_KB = 'E'" . " \r\n";
        // if ($postData["hidTKB"] == "1") {
        $sql .= "AND    KYOTN_CD IN (SELECT TENPO_CD FROM M27M01 GROUP BY TENPO_CD)" . " \r\n";
        // }
        if ($postData["txtDeployCode"] != NULL) {
            $sql .= "AND   BUS.KYOTN_CD      = '@BUSYO_CD' " . " \r\n";
            $sql = str_replace("@BUSYO_CD", $postData["txtDeployCode"], $sql);
        }
        if ($postData["txtdeployName"] != NULL) {
            $sql .= "AND   BUS.KYOTN_RKN  LIKE '@BUSYO_NM%' " . " \r\n";
            $sql = str_replace("@BUSYO_NM", $postData["txtdeployName"], $sql);
        }
        if ($postData["txtdeployKN"] != NULL) {
            $sql .= "AND   BUS.KYOTN_KNM LIKE '@BUSYO_KANA%' " . " \r\n";
            $sql = str_replace("@BUSYO_KANA", $postData["txtdeployKN"], $sql);
        }
        $sql .= "ORDER BY	" . " \r\n";
        $sql .= "BUS.KYOTN_CD" . " \r\n";

        return parent::select($sql);
    }

}
