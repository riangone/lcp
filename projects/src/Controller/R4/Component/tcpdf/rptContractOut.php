<?php
/**
 *
 */
function rptContractOut(&$key, &$data)
{
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

    $return["val"] = true;
    switch ($key) {
        case "txtZei":
            //20140407 税率暫定対応  St
//				$return["val"] = (int)(round($data["SYOUKEI"]) * 0.05);
//20191007 税率暫定対応  St
//				$return["val"] = (int)(round($data["SYOUKEI"]) * 0.08);
            $return["val"] = (int) (round($data["SYOUKEI"]) * 0.1);
            //20191007 税率暫定対応  Ed
//20140407 税率暫定対応  Ed
            break;
        case "txtGoukei":
            //20140407 税率暫定対応 St
//				$return["val"] = (int)(round($data["SYOUKEI"]) * 0.05) + round($data['SYOUKEI']);
//20191007 税率暫定対応  St
//				$return["val"] = (int)(round($data["SYOUKEI"]) * 0.08) + round($data['SYOUKEI']);
            $return["val"] = (int) (round($data["SYOUKEI"]) * 0.1) + round($data['SYOUKEI']);
            //20191007 税率暫定対応  Ed
//20140407 税率暫定対応 Ed
            break;
        case "txtToiawaseNM":
            if (rtrim($data['TOIAWASENM']) != '') {
                $data['TOIAWASENM'] = "(" . rtrim($data['TOIAWASENM']) . ")";
            }
            break;
        default:
            $return["val"] = true;
            break;
    }

    $return["data"] = $data;

    return $return;
}
;
?>