<?php
/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　
 * 20150918			  #2157						   BUG								yinhuaiyu
 *　　　　　
 * * --------------------------------------------------------------------------------------------
 */
function rptArariekiHyo(&$key, &$data)
{
    //print_r($data);
    // Private Sub GroupHeader1_BeforePrint(ByVal sender As Object, ByVal e As System.EventArgs) Handles GroupHeader1.BeforePrint
    // Me.txtZei.Text = (Fix(CInt(Me.txtSyoukei.Text) * 0.05)).ToString
    // Me.txtGoukei.Text = (CInt(Me.txtZei.Text) + CInt(Me.txtSyoukei.Text)).ToString
    // End Sub
    // Private Sub GroupHeader2_BeforePrint(ByVal sender As Object, ByVal e As System.EventArgs) Handles GroupHeader2.BeforePrint
    // If Me.txtToiawaseNM.Text.TrimEnd <> "" Then
    // Me.txtToiawaseNM.Text = "(" & Me.txtToiawaseNM.Text.TrimEnd & ")"
    // End If
    // End Sub
    $return = array();
    $lblPackName = "バックDEメンテ";

    $return["val"] = true;
    switch ($key) {
        case "DAISU":
            break;
        case "UriDai":
            if (FncNz($data['DAISU_total']) == 0) {
                $data['UriDai'] = "0";
            } else {
                $data['UriDai'] = number_format(fncRoundDou(FncNz($data['URIAGEKIN_total']) / FncNz($data['DAISU_total']), 0, 1));
            }
            break;
        case "ArariDai":
            if (FncNz($data['DAISU_total']) == 0) {
                $data['ArariDai'] = "0";
            } else {
                $data['ArariDai'] = number_format(fncRoundDou(FncNz($data['ARARI_total']) / FncNz($data['DAISU_total']), 0, 1));
            }
            break;
        case "RyuhoDai":
            if (FncNz($data['DAISU_total']) == 0) {
                $data['RyuhoDai'] = "0";
            } else {
                $data['RyuhoDai'] = number_format(fncRoundDou(FncNz($data['RYUHO_total']) / FncNz($data['DAISU_total']), 0, 1));
            }
            break;
        case "TouDai":
            if (FncNz($data['DAISU_total']) == 0) {
                $data['TouDai'] = "0";
            } else {
                $data['TouDai'] = number_format(fncRoundDou(FncNz($data['TOUARA_total']) / FncNz($data['DAISU_total']), 0, 1));
            }
            break;
        case "TkiDai":

            $data['TkiDai'] = ($data['TKI_DAI_total'] == 0) ? "0" : number_format(fncRoundDou(FncNz($data['TKIARA_total']) / FncNz($data['TKI_DAI_total']), 0, 1));
            break;
        case "ZkiDai":
            $data['ZkiDai'] = ($data['ZKI_DAI_total'] == 0) ? "0" : number_format(fncRoundDou((FncNz($data['ZKIARA_total'])) / (FncNz($data['ZKI_DAI_total'])), 0, 1));
            break;
        //総監を計算する
        case "SoukanUri":
            $data['SoukanUri'] = fncRoundDou((round(FncNz($data['URIAGEKIN_total'])) + round(FncNz($data['HONTAIGAKU']) / 1000)), 0, 1);
            break;
        case "SoukanArari":
            $data['SoukanArari'] = fncRoundDou((round(FncNz($data['ARARI_total'])) + round(FncNz($data['SYARYOARARI']) / 1000)), 0, 1);

            break;
        //計を千円単位で表示する
        case "ARARI":
            $data['ARARI'] = fncRoundDou(FncNz($data['ARARI']) / 1000, 0, 1);
            break;
        case "RYUHO":
            $data['RYUHO'] = fncRoundDou(FncNz($data['RYUHO']) / 1000, 0, 1);
            break;
        case "TOUARA":
            $data['TOUARA'] = fncRoundDou(FncNz($data['TOUARA']) / 1000, 0, 1);
            break;
        case "TKIARA":
            $data['TKIARA'] = fncRoundDou(FncNz($data['TKIARA']) / 1000, 0, 1);
            break;
        case "ZKIARA":
            $data['ZKIARA'] = fncRoundDou(FncNz($data['ZKIARA']) / 1000, 0, 1);
            break;
        case "URIAGEKIN":
            if ($data['URIAGEKIN'] == "") {
                $data['URIAGEKIN'] = 0;
            }
            $data['URIAGEKIN'] = fncRoundDou(FncNz($data['URIAGEKIN']) / 1000, 0, 1);
            break;

        case "HONTAIGAKU":
            $data['HONTAIGAKU'] = number_format(fncRoundDou(FncNz($data['HONTAIGAKU']) / 1000, 0, 1));
            break;

        case "SYARYOARARI":
            $data['SYARYOARARI'] = number_format(fncRoundDou(FncNz($data['SYARYOARARI']) / 1000, 0, 1));
            break;
    }

    $return["data"] = $data;

    return $return;
}
;

//---clscommonfnc---
function fncRoundDou($dblDou, $intRoundKeta, $strRoundKbn)
{
    $dCom1 = 0.0;
    $dCom2 = 0.0;
    switch ($strRoundKbn) {
        case "0":
            //切り捨て
            $fncRoundDou = intval($dblDou * pow(10, $intRoundKeta)) / pow(10, $intRoundKeta);
            break;
        case "1":
            //四捨五入
            $fncRoundDou = intval(($dblDou * pow(10, $intRoundKeta)) + (($dblDou < 0) ? 0.5 - 1 : 0.5)) / pow(10, $intRoundKeta);
            break;
        case "2":
            //切り上げ
            $dCom1 = $dblDou * pow(10, $intRoundKeta);
            $dCom2 = ($dblDou < 0) ? -0.999 : 0.999;
            $fncRoundDou = intval($dCom1 + $dCom2) / pow(10, $intRoundKeta);
            break;
        default:
            $fncRoundDou = $dblDou;
            break;
    }
    return sprintf("%.1f", $fncRoundDou);
}

function FncNz($objValue)
{
    //---NULLの場合---
    if ($objValue === null) {
        return 0;
    }
    //---空白の場合---
    elseif (trim($objValue) == '') {
        return 0;
    }
    //---その他---
    else {
        return $objValue;
    }
}
?>