<?php
function rptScUriageChk(&$key, &$data)
{
    switch ($key) {
        case "txtErrMessage":
            switch ($data['ERR_NO']) {
                case "1":
                    $data["txtErrMessage"] = "①　売上部署が未登録です";
                    break;
                case "2":
                    $data["txtErrMessage"] = "②　売掛部署が未登録です";
                    break;

                case "3":
                    $data["txtErrMessage"] = "③　社員番号が未登録です";
                    break;

                case "4":
                    $data["txtErrMessage"] = "④　配属先マスタの部署が違います";
                    break;

                case "5":
                    $data["txtErrMessage"] = "⑤　配属先マスタの職種区分が新車、中古車ではありません";
                    break;

                case "6":
                    $data["txtErrMessage"] = "⑥　売上部署変換マスタにより部署を変更しました";
                    break;
            }

            break;
        case "txtErrNM1":
            switch ($data['ERR_NO']) {
                case "1":
                    $data["txtErrNM1"] = "部署コード＝";
                    break;
                case "2":
                    $data["txtErrNM1"] = "部署コード＝";
                    break;

                case "3":
                    $data["txtErrNM1"] = "社員番号＝";
                    break;

                case "4":
                    $data["txtErrNM1"] = "社員番号＝";
                    break;

                case "5":
                    $data["txtErrNM1"] = "職種＝";
                    break;

                case "6":
                    $data["txtErrNM1"] = "社員番号＝";
                    break;
            }
            break;

        case "txtErrNM2":
            switch ($data['ERR_NO']) {
                case "1":
                    $data["txtErrNM2"] = "";
                    break;
                case "2":
                    $data["txtErrNM2"] = "";
                    break;

                case "3":
                    $data["txtErrNM2"] = "";
                    break;

                case "4":
                    $data["txtErrNM2"] = "売掛部署＝";
                    break;

                case "5":
                    $data["txtErrNM2"] = "新中区分＝";
                    break;

                case "6":
                    $data["txtErrNM2"] = "売掛部署＝";
                    break;
            }

            break;

        case "txtErrNM3":
            switch ($data['ERR_NO']) {
                case "1":
                    $data["txtErrNM3"] = "";
                    break;
                case "2":
                    $data["txtErrNM3"] = "";
                    break;

                case "3":
                    $data["txtErrNM3"] = "";
                    break;

                case "4":
                    $data["txtErrNM3"] = "配属部署＝";
                    break;

                case "5":
                    $data["txtErrNM3"] = "";
                    break;

                case "6":
                    $data["txtErrNM3"] = "販売拠点＝";
                    break;
            }

            break;
    }
    ;

    $return["data"] = $data;
    // print_r($key);
    // print_r($data);
    $return["val"] = true;
    return $return;
}