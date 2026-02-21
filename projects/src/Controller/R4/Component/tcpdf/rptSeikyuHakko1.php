<?php
function rptSeikyuHakko1(&$key, &$data)
{
    switch ($key) {
        //---20150810 fanzhengzhou add s.
        case "TOU_DATE":
            $data['TOU_DATE'] = str_replace('日', '', $data['TOU_DATE']);
            break;
        //---20150810 fanzhengzhou add e.
        case "SITADORI":
            if (rtrim($data['SITADORI']) !== "0") {
                //---20150810 #1963 fanzhengzhou upd s.
                //$data['SITADORI'] = "▲" . rtrim($data['SITADORI']);
                $data['SITADORI'] = "▲" . number_format(rtrim($data['SITADORI']));
                //---20150810 #1963 fanzhengzhou upd e.
            }
            break;
        case "SITNAME":
            if (rtrim($data['SITADORI']) !== "0") {
                $data['SITNAME'] = "下取車";
            }
            break;
    }

    $return["data"] = $data;
    $return["val"] = true;
    return $return;
}