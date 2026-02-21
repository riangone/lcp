<?php
function rptSyasyuTouroku1(&$key, &$data)
{
    if ($key == "lblSyurui") {
        switch ($data['CAR_KBN']) {
            case "0":
                $data["lblSyurui"] = "(　商用車　)";
                break;
            case "1":
                $data["lblSyurui"] = "(　乗用車　)";
                break;

            case "2":
                $data["lblSyurui"] = "(　他チャネル　)";
                break;
        }
    }
    $return["data"] = $data;
    $return["val"] = true;
    return $return;
}