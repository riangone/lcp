<?php
/**
 *
 */
function rptArariekiChkList(&$key, &$data)
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

    $lblPackName = "バックDEメンテ";

    $return["val"] = true;
    switch ($key) {

    }

    $return["data"] = $data;

    return $return;
}
;
?>