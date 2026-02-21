<?php
	/**
	 *
	 */
	function rptOutFitOrder(&$key, &$data)
	{
		// Private Sub Detail_BeforePrint(ByVal sender As Object, ByVal e As System.EventArgs) Handles Detail.BeforePrint
		// If Me.txtToiawase.Text.TrimEnd <> "" Then
		// Me.txtToiawase.Text = "(" & Me.txtToiawase.Text.TrimEnd.PadRight(8).Substring(0, 5) & Me.txtToiawase.Text.TrimEnd.PadRight(8).Substring(7, 1) & ")"
		// End If
		// End Sub

		$return = array();

		$lblPackName = "バックDEメンテ";

		$return["val"] = true;

		switch ($key)
		{
			case "txtToiawase" :
				if (rtrim($data["HANBAISYASYU"]) != "")
				{
					$data["HANBAISYASYU"] = "(" . substr(str_pad(rtrim($data["HANBAISYASYU"]), 8), 0, 5) . substr(str_pad(rtrim($data["HANBAISYASYU"]), 8), 7, 1) . ")";
				}
				break;
			default :
				$return["val"] = true;
				break;
		}
		$return["data"] = $data;

		return $return;
	};
?>