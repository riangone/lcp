<?php
/**
 *
 */
function rptMeisaiOkyaku(&$key, &$data)
{
    $return["val"] = true;

    $lblPackName = "バックDEメンテ";

    switch ($key) {
        case "SIYOU_NM":
            if (isset($data["KEIYAKU_NM1"]) && isset($data["SIYOU_NM"]) && rtrim($data["KEIYAKU_NM1"]) == rtrim($data["SIYOU_NM"])) {
                $data["SIYOU_NM"] = "";
            } elseif (isset($data["SIYOU_NM"]) && rtrim($data["SIYOU_NM"]) != "") {
                $data["SIYOU_NM"] = "(" . rtrim($data["SIYOU_NM"]) . " 様分)";
            }
            break;
        case "KEIYAKU_NM1":
            // 20140318 S0006 Start
            $data["KEIYAKU_NM2"] = preg_replace('/[　]+$/u', '', $data["KEIYAKU_NM2"]);
            // 20140318 S0006 End
            if (isset($data["KEIYAKU_NM2"]) && FncNv(rtrim($data["KEIYAKU_NM2"])) == "") {
                $data["KEIYAKU_NM1"] = (isset($data["KEIYAKU_NM1"]) ? rtrim($data["KEIYAKU_NM1"]) : '') . " 様";
            }
            break;
        case "KEIYAKU_NM2":
            // 20140318 S0006 Start
            $data["KEIYAKU_NM2"] = preg_replace('/[　]+$/u', '', $data["KEIYAKU_NM2"]);
            // 20140318 S0006 End
            if (isset($data["KEIYAKU_NM2"]) && FncNv(rtrim($data["KEIYAKU_NM2"])) != "") {
                $data["KEIYAKU_NM2"] = rtrim($data["KEIYAKU_NM2"]) . " 様";
            }
            break;
        case "txtOsiharaikin":
            $value1 = isset($data["OSHIHARAIKEI"]) ? rtrim($data["OSHIHARAIKEI"]) : '';
            $value2 = isset($data["SITADORI_SUMI_KIN"]) ? rtrim($data["SITADORI_SUMI_KIN"]) : '';
            $value3 = isset($data["SITADORI_SUMI_ZEI"]) ? rtrim($data["SITADORI_SUMI_ZEI"]) : '';
            $return["val"] = FncNz($value1) - FncNz($value2) - FncNz($value3);
            // $return["val"] =  FncNz($data["OSHIHARAIKEI"]?rtrim($data["OSHIHARAIKEI"]):'') -  FncNz($data["SITADORI_SUMI_KIN"]?rtrim($data["SITADORI_SUMI_KIN"]):'') -  FncNz($data["SITADORI_SUMI_ZEI"]?rtrim($data["SITADORI_SUMI_ZEI"]):'');
            break;
        case "lblKozaMeigi1":
            if ($data["KOUZA_MEIGI1"] == null || rtrim($data["KOUZA_MEIGI1"]) == "") {
                $return["val"] = false;
            } else {
                $return["val"] = true;
            }
            break;
        case "lblKozaMeigi2":
            if ($data["KOUZA_MEIGI2"] == null || rtrim($data["KOUZA_MEIGI2"]) == "") {
                $return["val"] = false;
            } else {
                $return["val"] = true;
            }
            break;
        case "lblKozaMeigi3":
            if ($data["KOUZA_MEIGI3"] == null || rtrim($data["KOUZA_MEIGI3"]) == "") {
                $return["val"] = false;
            } else {
                $return["val"] = true;
            }
            break;
        case "SYARYOU_NEBIKI":
            if (isset($data["SYARYOU_NEBIKI"]) && FncNz(rtrim($data["SYARYOU_NEBIKI"])) != 0) {
                $return_val = (Integer) rtrim($data["SYARYOU_NEBIKI"]) * (-1);
                // @formatter:on
                //$return_val = number_format($return_val);
                $data["SYARYOU_NEBIKI"] = $return_val;
            }
            break;
        case "YOTAKU_KIN":
            if (isset($data["YOTAKU_KIN"]) && FncNz(rtrim($data["YOTAKU_KIN"])) != 0) {
                $return_val = (Integer) rtrim($data["YOTAKU_KIN"]) * (-1);
                // @formatter:on
                //$return_val = number_format($return_val);
                $data["YOTAKU_KIN"] = $return_val;
            }
            break;
        case "lblPackDe753":
            $lblPackDe753 = "";
            if (isset($data["PACK_DE_753"]) && FncNz(rtrim($data["PACK_DE_753"])) != 0) {
                $lblPackDe753 = "バックDE753";
                $lblPackName = "・メンテ";
            }
            if (isset($data["ENCHOU_HOSYOU"]) && FncNz(rtrim($data["ENCHOU_HOSYOU"])) != 0) {
                if ($lblPackDe753 != "") {
                    $lblPackDe753 = rtrim($lblPackDe753) . "・";
                } else {
                    $lblPackName = "・パックDEメンテ";
                }
                $lblPackDe753 = rtrim($lblPackDe753) . "延長保証";
                //$data["PACK_DE_753"] = number_format( FncNv(rtrim($data["PACK_DE_753"])) +  FncNv(rtrim($data["ENCHOU_HOSYOU"])));
            }
            if (isset($data["PACK_DE_MENTE"]) && FncNz(rtrim($data["PACK_DE_MENTE"])) != 0) {
                $lblPackDe753 = rtrim($lblPackDe753) . $lblPackName;
                //$data["PACK_DE_753"] = number_format( FncNv(rtrim($data["PACK_DE_753"])) +  FncNv(rtrim($data["ENCHOU_HOSYOU"])));
            }
            $return["val"] = $lblPackDe753;
            break;
        case "PACK_DE_753":
            $value1 = '';
            $value2 = '';
            $value3 = '';
            if (isset($data["PACK_DE_753"]) && FncNz(rtrim($data["PACK_DE_753"])) != 0) {
                $value1 = isset($data["PACK_DE_753"]) ? rtrim($data["PACK_DE_753"]) : '';
            }
            if (isset($data["PACK_DE_MENTE"]) && FncNz(rtrim($data["PACK_DE_MENTE"])) != 0) {
                $value2 = isset($data["PACK_DE_MENTE"]) ? rtrim($data["PACK_DE_MENTE"]) : '';
            }
            if (isset($data["ENCHOU_HOSYOU"]) && FncNz(rtrim($data["ENCHOU_HOSYOU"])) != 0) {
                $value3 = isset($data["ENCHOU_HOSYOU"]) ? rtrim($data["ENCHOU_HOSYOU"]) : '';
            }
            $data["PACK_DE_753"] = FncNz($value1) + FncNz($value2) + FncNz($value3);
            //return $data["PACK_DE_753"];
            break;
        // 20140315 税率対応 ADD start
        case "UTIZEI_KIN2";
            //消費税率が新車と同じ場合は内税金の2段目の内容を表示しない
            if ($data["LBL_UTIZEI_CHU_CALC"] == '') {
                $data["UTIZEI_KIN2"] = '';
            }
            break;
        // 20140315 税率対応 ADD end
        default:
            $return["val"] = true;
            break;
    }
    $return["data"] = $data;

    return $return;
}
;

//**********************************************************************
//処 理 名：Null変換関数(文字)
//関 数 名：FncNv
//引     数：$objValue     (I)文字列
//戻 り 値：変換後の値
//処理説明：Null変換(文字)を行う。
//**********************************************************************
// function FncNv($objValue, $objReturn = "")
// {
// //---NULLの場合---
// if ($objValue == null)
// {
// return $objReturn;
// }
// //---以外の場合---
// else
// {
// return $objValue;
// }
// }
