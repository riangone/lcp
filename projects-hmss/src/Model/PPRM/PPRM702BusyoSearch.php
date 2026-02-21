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

class PPRM702BusyoSearch extends ClsComDb
{

    public function getDeployDataSQL($data)
    {
        $postData = $data;
        $sql = "";
        $sql .= "SELECT " . " \r\n";
        $sql .= "      BUS.BUSYO_CD" . " \r\n";
        $sql .= " ,    BUS.BUSYO_NM" . " \r\n";
        $sql .= " FROM " . " \r\n";
        $sql .= "     HBUSYO BUS" . " \r\n";
        $sql .= " WHERE " . " \r\n";
        $sql .= "      1=1" . " \r\n";
        if ($postData["txtDeployCode"] != NULL) {
            $sql .= "AND   BUS.BUSYO_CD      = '@BUSYO_CD' " . " \r\n";
            $sql = str_replace("@BUSYO_CD", $postData["txtDeployCode"], $sql);
        }
        if ($postData["txtdeployName"] != NULL) {
            $sql .= "AND   BUS.BUSYO_NM   LIKE '@BUSYO_NM%' " . " \r\n";
            $sql = str_replace("@BUSYO_NM", $postData["txtdeployName"], $sql);
        }
        if ($postData["txtdeployKN"] != NULL) {
            $sql .= "AND   BUS.BUSYO_KANANM LIKE '@BUSYO_KANA%' " . " \r\n";
            $sql = str_replace("@BUSYO_KANA", $postData["txtdeployKN"], $sql);
        }
        if ($postData["rdo"] == "rdoSin") {
            $sql .= "AND   BUS.BUSYO_KB = 'S'" . " \r\n";
        } elseif ($postData["rdo"] == "rdoTyu") {
            $sql .= "AND   BUS.BUSYO_KB = 'C' " . " \r\n";
        } else {
            $sql .= "AND   NVL(BUS.BUSYO_KB,'F') = 'F' " . " \r\n";
        }
        $sql .= "ORDER BY	" . " \r\n";
        $sql .= "BUS.BUSYO_CD" . " \r\n";
        return parent::select($sql);

    }

}
