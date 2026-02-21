<?php

	$strPath = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
	$filename = $strPath . "/Model/Component/" . 'HMDB.xml';
	// 値取得
	$xml = simplexml_load_file($filename);
	// XMLの取得
	$Ora = (array)$xml;

	$Host = "mailgw.mazda.co.jp";
	//$Username = "df13507";
	//$Password = "Cd102905";
	$SMTPSecure = "";
	$From = "oe.h@mazda-dealer.jp";
	$FromName = "デジタル推進Gr";
	$WordWrap = "50";
	$CharSet = "UTF-8";
	$Port = 25;
	$SMTPAuth = $Ora['SMTPAuth'];
	$Encoding = "7bit";

	$Subject = "PASSWORDのお問合せ結果のお知らせ";

	$SubjectIDTIPS = "IDのお問合せ結果のお知らせ";

	$Content1 = "==============================================================<br/>";

	$Content2 = "※このメールは、登録メールアドレス宛に自動的に送信されています。返信の必要はありません。<br/>";

	$ContentTips = "パスワードを変更する場合はシステム管理者へお問合せ下さい。<br/>";

	$ContentID = "GDMZ IDの問合せ結果をお知らせします。<br/>";

	$ContentPASSWORD = "GDMZ PASSWORDの問合せ結果をお知らせします。<br/>";

	$ContentResultID = "お問合せのHiromaz IDは、「";

	$ContentResultPassword = "お問合せのPASSWORDは、「";

	$mark = "」となります。<br/>";

	//20211206 lujunxia ins s
	//データ集計システム-カタログ注文_注文内容確認
	$Subject1 = "カタログ注文【";

	$Subject2 = "】";

	$ContentOrderDay = "注文日：";

	$ContentShopName = "部署　：";

	$ContentHaisou = "【配送を希望しない】<br/><br/>";

	$ContentDetail = "【ご注文明細】<br/>";

	$ContentCut = "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝<br/>";

	$ContentCode = "コード：";

	$ContentName = "カタログ名：";

	$ContentNum = "注文数：";
	//20211206 lujunxia ins e
	//20220727 ciyuanchen ins s
	//内部統制システム-報告書入力-確定、差戻ボタンクリック処理後、通知メールを送付する
	$Hmaud_Subject1 = "単位：";
	$Hmaud_Subject2 = "条件：";
	$Hmaud_Subject3 = "件名：";
	//20220727 ciyuanchen ins e
?>
