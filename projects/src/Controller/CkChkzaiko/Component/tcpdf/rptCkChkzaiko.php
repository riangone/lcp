<?php
	function rptCkChkzaiko(&$key, &$data)
	{

		/* vb code
		 *  Private Sub Detail_BeforePrint(ByVal sender As Object, ByVal e As System.EventArgs) Handles Detail.BeforePrint
		 If Me.txtToiawase.Text.TrimEnd <> "" Then
		 Me.txtToiawase.Text = "(" & Me.txtToiawase.Text.TrimEnd.PadRight(8).Substring(0, 5) & Me.txtToiawase.Text.TrimEnd.PadRight(8).Substring(7, 1) & ")"
		 End If
		 End Sub
		 */
		switch ($key)
		{

			case "BRD_SYD" :
				$data["BRD_SYD"] = $data['BRD_CD'] . $data['SYD_TOU_YM'];
				break;
			case "TOU_NO" :
				$data["TOU_NO"] = $data['TOU_NO_RKJ_NM'] . " " . $data['VCLRGTNO_SYU'] . " " . $data['TOU_NO_KNA'] . " " . $data['TOU_NO_RBN'];
				break;
			case "SYAN_SEI_MEI" :
				$data["SYAN_SEI_MEI"] = $data['SYAIN_KNJ_SEI'] . $data['SYAIN_KNJ_MEI'];
				break;
			case "SATEI_GK" :
				if (trim($data["SATEI_GK"]) != "")
				{
					$data["SATEI_GK"] = number_format($data["SATEI_GK"]);
				}
				break;
			case "TRA_GK" :
				//2014-02-25 修正 START 分岐条件を消費税率から税率区分へ修正
				//TRA_GK
				//SATEI_GK
				if (trim($data["SHZ_KB"]) != "")
				{
					if (trim($data["TRA_GK"]) != "")
					{
						switch($data["SHZ_KB"])
						{
							//2014-02-28 修正 START 税抜き価格計算式の修正
							case "4" :
								$tmpV = ceil((int)$data['SATEI_GK'] / 1.05);
								$data["TRA_GK"] = number_format($tmpV);
								break;
							case "5" :
								$tmpV = ceil((int)$data['SATEI_GK'] / 1.08);
								$data["TRA_GK"] = number_format($tmpV);
								break;
							case "6" :
								$tmpV = ceil((int)$data['SATEI_GK'] / 1.1);
								$data["TRA_GK"] = number_format($tmpV);
								break;
							//2014-02-28 修正 END 税抜き価格計算式の修正
							default :
								$tmpV = $data["SATEI_GK"];
								$data["TRA_GK"] = number_format($tmpV);
						}
					}
				}
				//2014-02-25 修正 END 分岐条件を消費税率から税率区分へ修正

				/*	if (trim($data["TRA_GK"]) != "")
				 {
				 $data["TRA_GK"] = number_format($data["TRA_GK"]);
				 }
				 *
				 */
				break;
			case "RUIBETU_NO1" :
				$data["RUIBETU_NO1"] = substr($data["RUIBETU_NO"], 0, 1);
				break;
			case "RUIBETU_NO2" :
				$data["RUIBETU_NO2"] = substr($data["RUIBETU_NO"], 1, 1);
				break;
			case "RUIBETU_NO3" :
				$data["RUIBETU_NO3"] = substr($data["RUIBETU_NO"], 2, 1);
				break;
			case "RUIBETU_NO4" :
				$data["RUIBETU_NO4"] = substr($data["RUIBETU_NO"], 3, 1);
				break;
			case "SITEI_NO1" :
				$data["SITEI_NO1"] = substr($data["SITEI_NO"], 0, 1);
				break;
			case "SITEI_NO2" :
				$data["SITEI_NO2"] = substr($data["SITEI_NO"], 1, 1);
				break;
			case "SITEI_NO3" :
				$data["SITEI_NO3"] = substr($data["SITEI_NO"], 2, 1);
				break;
			case "SITEI_NO4" :
				$data["SITEI_NO4"] = substr($data["SITEI_NO"], 3, 1);
				break;
			case "SITEI_NO5" :
				$data["SITEI_NO5"] = substr($data["SITEI_NO"], 4, 1);
				break;
			//2014-02-25 修正 START 登録日を REC_CRE_DT から TOU_DTに修正
			//case "REC_CRE_DT" :
			//	$tmpT = explode("-", $data["REC_CRE_DT"]);
			//	$year = $tmpT[0];
			//	$month = $tmpT[1];
			//	$day = $tmpT[2];
            case "TOU_DT" :
				$year = substr($data["TOU_DT"], 0, 4);
				$month = substr($data["TOU_DT"], 4, 2);
				$day = substr($data["TOU_DT"], 6, 2);
			//2014-02-25 修正 END 登録日を REC_CRE_DT から TOU_DTに修正

				//平成
				$tmp = "";
				if ($year > 1988)
				{
					$tmp = (int)$year - 1988;
					$tmp = '' . $tmp;
					//$data["REC_CRE_DT"] = $tmp . "年" . $month . "月" . $day . "日";
					$data["TOU_DT"] = $tmp . "年" . $month . "月" . $day . "日";					
                                                                                break;
				}
				//昭和
				if ($year > 1925)
				{
					$tmp = (int)$year - 1925;
					$tmp = '' . $tmp;
					//$data["REC_CRE_DT"] = $tmp . "年" . $month . "月" . $day . "日";
					$data["TOU_DT"] = $tmp . "年" . $month . "月" . $day . "日";
					break;
				}
				//大正
				if ($year > 1911)
				{
					$tmp = (int)$year - 1911;
					$tmp = '' . $tmp;
					//$data["REC_CRE_DT"] = $tmp . "年" . $month . "月" . $day . "日";
					$data["TOU_DT"] = $tmp . "年" . $month . "月" . $day . "日";
					break;
				}
				//明治
				if ($year > 1867)
				{
					$tmp = (int)$year - 1867;
					$tmp = '' . $tmp;
					//$data["REC_CRE_DT"] = $tmp . "年" . $month . "月" . $day . "日";
					$data["TOU_DT"] = $tmp . "年" . $month . "月" . $day . "日";
					break;
				}

				break;
			case "SYD_TOU_YM" :
				$year = substr($data['SYD_TOU_YM'], 0, 4);
				$month = substr($data['SYD_TOU_YM'], 4, 2);

				//平成
				$tmp = "";
				if ($year > 1988)
				{
					$tmp = (int)$year - 1988;
					$tmp = '' . $tmp;
					$data["SYD_TOU_YM"] = $tmp . "/" . $month;
					break;

				}
				//昭和
				if ($year > 1925)
				{
					$tmp = (int)$year - 1925;
					$tmp = '' . $tmp;
					$data["SYD_TOU_YM"] = $tmp . "/" . $month;
					break;
				}
				//大正
				if ($year > 1911)
				{
					$tmp = (int)$year - 1911;
					$tmp = '' . $tmp;
					$data["SYD_TOU_YM"] = $tmp . "/" . $month;
					break;
				}
				//明治
				if ($year > 1867)
				{
					$tmp = (int)$year - 1867;
					$tmp = '' . $tmp;
					$data["SYD_TOU_YM"] = $tmp . "/" . $month;
					break;
				}
				break;
			case "BRD_CD" :
				$data['BRD_CD'] = $data['MEIGARA_MEI'];
				
			//2014-3-4 修正 START 各種料金を出力するよう修正
			case "ASR_RYOKIN" :
				if (trim($data["ASR_RYOKIN"]) != "")
				{
					$data["ASR_RYOKIN"] = number_format($data["ASR_RYOKIN"]);
				}
				break;
			case "AIRBUG_RYOKIN" :
				if (trim($data["AIRBUG_RYOKIN"]) != "")
				{
					$data["AIRBUG_RYOKIN"] = number_format($data["AIRBUG_RYOKIN"]);
				}
				break;
			case "FULON_RYOKIN" :
				if (trim($data["FULON_RYOKIN"]) != "")
				{
					$data["FULON_RYOKIN"] = number_format($data["FULON_RYOKIN"]);
				}
				break;
			case "JOHO_KNR_RYOKIN" :
				if (trim($data["JOHO_KNR_RYOKIN"]) != "")
				{
					$data["JOHO_KNR_RYOKIN"] = number_format($data["JOHO_KNR_RYOKIN"]);
				}
				break;
			case "RCYL_GK" :
				if (trim($data["RCYL_GK"]) != "")
				{
					$data["RCYL_GK"] = number_format($data["RCYL_GK"]);
				}
				break;
			case "RCYL_KEN_NO" :
				if (trim($data["RCYL_KEN_NO"]) != "")
				{
					$no1 = substr($data['RCYL_KEN_NO'], 0, 4);
					$no2 = substr($data['RCYL_KEN_NO'], 4, 4);
					$no3 = substr($data['RCYL_KEN_NO'], 8, 4);
					$data["RCYL_KEN_NO"] = $no1 . "-" . $no2 . "-" . $no3;
				}
				break;
			//2014-3-4 修正 END 各種料金を出力するよう修正
		}
		$return["data"] = $data;
		$return["val"] = true;
		return $return;

	};
?>