<?php
/**
 *
 */
function rptSyanaiLeasePrint1($key, $data)
{
    $return = array();
    $return["val"] = true;
    $strToday = "";
    switch ($key) {
        case "TextBox56":
        case "TextBox40":
        case "txtToday":
            switch (substr($data['TODAY'], 0, 1)) {
                case "H":
                    $strToday = "平成";
                    break;
                case "S":
                    $strToday = "昭和";
                    break;
                case "T":
                    $strToday = "大正";
                    break;
                case "M":
                    $strToday = "明治";
                    break;
            }
            switch ($strToday) {
                case "平成":
                case "昭和":
                case "大正":
                case "明治":
                    $strToday = $strToday . substr($data['TODAY'], 1, 2) . "年" . substr($data['TODAY'], 3, 2) . "月" . substr($data['TODAY'], 5, 2) . "日";
                    break;
            }
            $data['TODAY'] = $strToday;
            break;
        case "SYUTOKU_KIN_Total":
            $data['SYUTOKU_KIN_Total'] = (int) $data['SERVICE_KIN'] + (int) $data['KIGU_KIN'] + (int) $data['KIKAI_KIN'] + (int) $data['KOUGU_KIN'];
            if ($data['SYUTOKU_KIN_Total'] == "") {
                $data['SYUTOKU_KIN_Total'] = "0";
            }
            break;
        case "LEASE_RYO_Total":
            $data['LEASE_RYO_Total'] = (int) $data['SERVICE_REASE_RYO'] + (int) $data['KIGU_REASE_RYO'] + (int) $data['KIKAI_REASE_RYO'] + (int) $data['KOUGU_REASE_RYO'];
            if ($data['LEASE_RYO_Total'] == "") {
                $data['LEASE_RYO_Total'] = "0";
            }
            break;
        case "SERVICE_CNT":
            if ($data['SERVICE_CNT'] == "") {
                $data['SERVICE_CNT'] = "0";
            }
            break;
        case "KIGU_CNT":
            if ($data['KIGU_CNT'] == "") {
                $data['KIGU_CNT'] = "0";
            }
            break;
        case "KOUGU_CNT":
            if ($data['KOUGU_CNT'] == "") {
                $data['KOUGU_CNT'] = "0";
            }
            break;
        case "KIKAI_CNT":
            if ($data['KIKAI_CNT'] == "") {
                $data['KIKAI_CNT'] = "0";
            }
            break;
        //---
        case "SERVICE_KIN_footer":
            if ($data['SERVICE_KIN'] == "") {
                $data['SERVICE_KIN_footer'] = "0";
            } else {
                $data['SERVICE_KIN_footer'] = $data['SERVICE_KIN'];
            }
            break;
        case "KIGU_KIN_footer":
            if ($data['KIGU_KIN'] == "") {
                $data['KIGU_KIN_footer'] = "0";
            } else {
                $data['KIGU_KIN_footer'] = $data['KIGU_KIN'];
            }
            break;
        case "KIKAI_KIN_footer":
            if ($data['KIKAI_KIN'] == "") {
                $data['KIKAI_KIN_footer'] = "0";
            } else {
                $data['KIKAI_KIN_footer'] = $data['KIKAI_KIN'];
            }
            break;
        case "KOUGU_KIN_footer":
            if ($data['KOUGU_KIN'] == "") {
                $data['KOUGU_KIN_footer'] = "0";
            } else {
                $data['KOUGU_KIN_footer'] = $data['KOUGU_KIN'];
            }
            break;
        //--
        case "SERVICE_REASE_RYO_footer":
            if ($data['SERVICE_REASE_RYO'] == "") {
                $data['SERVICE_REASE_RYO_footer'] = "0";
            } else {
                $data['SERVICE_REASE_RYO_footer'] = $data['SERVICE_REASE_RYO'];
            }
            break;
        case "KIKAI_REASE_RYO_footer":
            if ($data['KIKAI_REASE_RYO'] == "") {
                $data['KIKAI_REASE_RYO_footer'] = "0";
            } else {
                $data['KIKAI_REASE_RYO_footer'] = $data['KIKAI_REASE_RYO'];
            }
            break;
        case "KOUGU_REASE_RYO_footer":
            if ($data['KOUGU_REASE_RYO'] == "") {
                $data['KOUGU_REASE_RYO_footer'] = "0";
            } else {
                $data['KOUGU_REASE_RYO_footer'] = $data['KOUGU_REASE_RYO'];
            }
            break;
        case "KIGU_REASE_RYO_footer":
            if ($data['KIGU_REASE_RYO'] == "") {
                $data['KIGU_REASE_RYO_footer'] = "0";
            } else {
                $data['KIGU_REASE_RYO_footer'] = $data['KIGU_REASE_RYO'];
            }
            break;
        case "LEASE_RYO_RT":
            break;
    }
    $return["data"] = $data;

    return $return;
}
?>