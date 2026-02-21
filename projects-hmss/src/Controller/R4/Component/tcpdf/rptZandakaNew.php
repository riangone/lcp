<?php
function rptZandakaNew(&$key, &$data)
{
    if ($key == "TODAY") {
        $strToday = "";
        switch (substr($data["TODAY"], 0, 1)) {
            case "S":
                $strToday = "昭和";
                break;
            case "H":
                $strToday = "平成";
                break;
            case "R":
                $strToday = "令和";
                break;
        }
        $data["TODAY"] = $strToday . substr($data["TODAY"], 1, 2) . "年" . substr($data["TODAY"], 3, 2) . "月" . substr($data["TODAY"], 5, 2) . "日";
    }
    $return["data"] = $data;
    $return["val"] = true;
    return $return;
}
