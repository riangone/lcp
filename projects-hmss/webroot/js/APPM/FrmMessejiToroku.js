/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD            #ID                          XXXXXX                          FCSDL
 * 20170503                                         变更                             WANGYING
 * 20170504                                         变更                             WANGYING
 * 20170505                                         变更                             WANGYING
 * 20171128            #2807                        变更                             YIN
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("APPM.FrmMessejiToroku");

APPM.FrmMessejiToroku = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    //20170503 LQS UPD S
    //clsComFnc.GSYSTEM_NAME = "メッセージ管理";
    clsComFnc.GSYSTEM_NAME = "ヒロアプ管理";
    //20170503 LQS UPD E
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmMessejiToroku";
    me.sys_id = "APPM";
    me.title = "メッセージ詳細";
    me.url = "";
    me.img = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmMessejiToroku.btnSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiToroku.btnSansho",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiToroku.btnRebu",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiToroku.btnTouroku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiToroku.btnCancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiToroku.txtMFromKikan",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiToroku.txtMToMKikan",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiToroku.txtKFromKikan",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiToroku.txtKToKikan",
        type: "datepicker",
        handle: "",
    });
    // ========== コントロール end ==========

    // ========== イベント start ==========
    //[参照]ボタンクリック
    $(".FrmMessejiToroku.btnSansho").click(function () {
        me.btnSansho();
    });
    //[キャンセル]ボタンクリック
    $(".FrmMessejiToroku.btnCancel").click(function () {
        me.btnCancel();
    });
    //[登録]ボタンクリック
    $(".FrmMessejiToroku.btnTouroku").click(function () {
        me.btnTouroku();
    });
    //[プレビュー]ボタンクリック
    $(".FrmMessejiToroku.btnRebu").click(function () {
        me.btnRebu();
    });
    //[设定]ボタンクリック
    $(".FrmMessejiToroku.btnSearch").click(function () {
        var dateFrom = $(".FrmMessejiToroku.txtMFromKikan").val();
        var dateTo = $(".FrmMessejiToroku.txtMToMKikan").val();
        me.searchList(dateFrom, dateTo, "");
    });
    $(".FrmMessejiToroku.txtTitle").on("blur", function () {
        me.getMaxlength();
    });
    // ========== イベント end ==========

    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));

    if (requestdata) {
        me.strMODE = requestdata["MODE"];
        me.strMESSEJI_ID = requestdata["MESSEJI_ID"];
    }

    localStorage.removeItem("requestdata");

    me.before_close = function () {};

    if (
        me.strMODE == "0" ||
        me.strMODE == "1" ||
        me.strMODE == "2" ||
        me.strMODE == "3"
    ) {
        if (me.strMODE == "0" || me.strMODE == "2" || me.strMODE == "3") {
            if (me.strMESSEJI_ID == "") {
                $(".FrmMessejiToroku.body").remove();
                me.flag = false;
            } else {
                $(".FrmMessejiToroku.body").dialog({
                    autoOpen: false,
                    resizable: false,
                    width: 760,
                    height: 620,
                    modal: true,
                    title: me.title,
                    open: function () {},
                    close: function () {
                        me.before_close();
                        $(".FrmMessejiToroku.body").remove();
                    },
                });
                me.flag = true;
                $(".FrmMessejiToroku.body").dialog("open");
            }
        } else {
            $(".FrmMessejiToroku.body").dialog({
                autoOpen: false,
                resizable: false,
                width: 760,
                height: 620,
                modal: true,
                title: me.title,
                open: function () {},
                close: function () {
                    me.before_close();
                    $(".FrmMessejiToroku.body").remove();
                },
            });
            me.flag = true;
            $(".FrmMessejiToroku.body").dialog("open");
        }
    } else {
        $(".FrmMessejiToroku.body").remove();
        me.flag = false;
    }
    // ========== 関数 start ==========
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
    };

    var base_load = me.load;
    me.load = function () {
        base_load();

        if (
            ((me.strMODE == "0" || me.strMODE == "2" || me.strMODE == "3") &&
                me.strMESSEJI_ID != "") ||
            me.strMODE == "1"
        ) {
            //画面項目使用可否切替,表示切替
            if (me.strMODE == "0") {
                //20170519 LQS INS S
                $(".FrmMessejiToroku.block").block({
                    overlayCSS: {
                        opacity: 0,
                    },
                });
                //20170519 LQS INS E
                $(".FrmMessejiToroku.btnTouroku").button("disable");
                $(".FrmMessejiToroku.block")
                    .find("select")
                    .attr("disabled", "disabled");
                $(".FrmMessejiToroku.block")
                    .find("input")
                    .attr("disabled", "disabled");
                $(".FrmMessejiToroku.btnSansho").button("disable");
                $(".FrmMessejiToroku.btnSearch").button("disable");
            }
            if (me.strMODE == "1") {
                $(".FrmMessejiToroku.txtCode").attr("disabled", "disabled");
                $(".FrmMessejiToroku.detailBlock").css("visibility", "hidden");
                $(".FrmMessejiToroku.btnTouroku").button("disable");
                $(".FrmMessejiToroku.btnRebu").button("disable");
            }
            if (me.strMODE == "2") {
                $(".FrmMessejiToroku.btnTouroku").text("変更");
                $(".FrmMessejiToroku.txtCode").attr("disabled", "disabled");
                $(".FrmMessejiToroku.txtMFromKikan").attr(
                    "disabled",
                    "disabled"
                );
                $(".FrmMessejiToroku.txtMToMKikan").attr(
                    "disabled",
                    "disabled"
                );
                $(".FrmMessejiToroku.btnSearch").button("disable");
                $(".FrmMessejiToroku.msgDateBlock").block({
                    overlayCSS: {
                        opacity: 0,
                    },
                });
            }
            if (me.strMODE == "3") {
                //20170519 LQS INS S
                $(".FrmMessejiToroku.block").block({
                    overlayCSS: {
                        opacity: 0,
                    },
                });
                //20170519 LQS INS E
                $(".FrmMessejiToroku.btnTouroku").text("削除");
                $(".FrmMessejiToroku.block")
                    .find("select")
                    .attr("disabled", "disabled");
                $(".FrmMessejiToroku.block")
                    .find("input")
                    .attr("disabled", "disabled");
                $(".FrmMessejiToroku.btnSansho").button("disable");
                $(".FrmMessejiToroku.btnSearch").button("disable");
            }

            if (me.strMODE == "0" || me.strMODE == "2" || me.strMODE == "3") {
                //[メッセージコード] を条件にDB検索処理を実行
                var url = me.sys_id + "/" + me.id + "/" + "fncSearch";
                var arr = {
                    ID: me.strMESSEJI_ID,
                };
                var data = {
                    request: arr,
                };
                ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    //20170504 LQS UPD S
                    //if (!result['other']['result'])
                    // {
                    // clsComFnc.FncMsgBox("E9999", result['other']['data']);
                    // return;
                    // }
                    if (!result["result"]) {
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    //20170504 LQS UPD E
                    if (result["other"]["row"] <= 0) {
                        //20170517 LQS UPD S
                        //clsComFnc.FncMsgBox("W0016");
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "該当データがありません。"
                        );
                        //20170517 LQS UPD E
                        $(".FrmMessejiToroku.body").dialog("close");
                        return;
                    }
                    if (result["other"]["row"] == 1) {
                        $(".FrmMessejiToroku.txtCode").val(me.strMESSEJI_ID);
                        var mFromKikan =
                            result["other"]["data"][0][
                                "MESSEJI_RIYO_KIKAN_FROM"
                            ].substring(0, 4) +
                            "/" +
                            result["other"]["data"][0][
                                "MESSEJI_RIYO_KIKAN_FROM"
                            ].substring(4, 6) +
                            "/" +
                            result["other"]["data"][0][
                                "MESSEJI_RIYO_KIKAN_FROM"
                            ].substring(6, 8);
                        var mToMKikan =
                            result["other"]["data"][0][
                                "MESSEJI_RIYO_KIKAN_TO"
                            ].substring(0, 4) +
                            "/" +
                            result["other"]["data"][0][
                                "MESSEJI_RIYO_KIKAN_TO"
                            ].substring(4, 6) +
                            "/" +
                            result["other"]["data"][0][
                                "MESSEJI_RIYO_KIKAN_TO"
                            ].substring(6, 8);
                        $(".FrmMessejiToroku.txtMFromKikan").val(mFromKikan);
                        $(".FrmMessejiToroku.txtMToMKikan").val(mToMKikan);
                        me.searchList(mFromKikan, mToMKikan, result);
                    }
                };
                ajax.send(url, data, 0);
            }
        }
    };

    me.searchList = function (dateFrom, dateTo, dataModel) {
        if (me.strMODE == "1") {
            if (fncMsgDateCheck(dateFrom, dateTo) == false) {
                return;
            }
        }

        dateFrom =
            dateFrom.substring(0, 4) +
            dateFrom.substring(5, 7) +
            dateFrom.substring(8, 10);
        dateTo =
            dateTo.substring(0, 4) +
            dateTo.substring(5, 7) +
            dateTo.substring(8, 10);
        //＜入力欄＞
        var url = me.sys_id + "/" + me.id + "/" + "searchTCODE";
        var arr = {
            dateFrom: dateFrom,
            dateTo: dateTo,
        };
        var data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            //20170503 WANG UPD S
            if (result["result"]) {
                var strSelect = "";
                strSelect += "<option value=''></option>";
                for (var i = 0; i < result["content"]["row"]; i++) {
                    strSelect +=
                        "<option value='" +
                        result["content"]["data"][i]["NAIBU_CD"] +
                        "'>" +
                        result["content"]["data"][i]["NAIBU_CD_MEISHO"] +
                        "</option>";
                }
                $(".FrmMessejiToroku.txtContent").html(strSelect);

                var strSelect = "";
                strSelect += "<option value=''></option>";
                for (var i = 0; i < result["have"]["row"]; i++) {
                    strSelect +=
                        "<option value='" +
                        result["have"]["data"][i]["NAIBU_CD"] +
                        "'>" +
                        result["have"]["data"][i]["NAIBU_CD_MEISHO"] +
                        "</option>";
                }
                $(".FrmMessejiToroku.txtKidoku").html(strSelect);
                $(".FrmMessejiToroku.txtMogiri").html(strSelect);

                var strSelect = "";
                strSelect += "<option value=''></option>";
                for (var i = 0; i < result["show"]["row"]; i++) {
                    strSelect +=
                        "<option value='" +
                        result["show"]["data"][i]["NAIBU_CD"] +
                        "'>" +
                        result["show"]["data"][i]["NAIBU_CD_MEISHO"] +
                        "</option>";
                }
                $(".FrmMessejiToroku.txtSharyo").html(strSelect);

                var strSelect = "";
                strSelect += "<option value=''></option>";
                for (var i = 0; i < result["common"]["row"]; i++) {
                    strSelect +=
                        "<option value='" +
                        result["common"]["data"][i]["NAIBU_CD"] +
                        "'>" +
                        result["common"]["data"][i]["NAIBU_CD_MEISHO"] +
                        "</option>";
                }
                $(".FrmMessejiToroku.txtRinku").html(strSelect);
                $(".FrmMessejiToroku.txtShi").html(strSelect);
                $(".FrmMessejiToroku.txtRu").html(strSelect);

                var strSelect = "";
                strSelect += "<option value=''></option>";
                if (result["code"]["row"] > 0) {
                    strSelect += "<option value='00'>なし</option>";
                }
                for (var i = 0; i < result["code"]["row"]; i++) {
                    strSelect +=
                        "<option value='" +
                        result["code"]["data"][i]["NAIYO_CD"] +
                        "'>" +
                        result["code"]["data"][i]["NAIYO_CD_MEISHO"] +
                        "</option>";
                }
                $(".FrmMessejiToroku.txtShaken").html(strSelect);
                if (me.strMODE == "1") {
                    $(".FrmMessejiToroku.detailBlock").css(
                        "visibility",
                        "visible"
                    );
                    $(".FrmMessejiToroku.btnTouroku").button("enable");
                    $(".FrmMessejiToroku.btnRebu").button("enable");
                    $(".FrmMessejiToroku.txtMFromKikan").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".FrmMessejiToroku.txtMToMKikan").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".FrmMessejiToroku.btnSearch").button("disable");
                    $(".FrmMessejiToroku.msgDateBlock").block({
                        overlayCSS: {
                            opacity: 0,
                        },
                    });
                }
                if (dataModel != "") {
                    $(".FrmMessejiToroku.txtContent").val(
                        dataModel["other"]["data"][0]["NAIYO_KBN"]
                    );
                    if (
                        dataModel["other"]["data"][0]["KIDOKU_KAKUNIN_FLG"] !=
                        null
                    ) {
                        $(".FrmMessejiToroku.txtKidoku").val(
                            dataModel["other"]["data"][0]["KIDOKU_KAKUNIN_FLG"]
                        );
                    }
                    $(".FrmMessejiToroku.txtMogiri").val(
                        dataModel["other"]["data"][0]["MOGIRI_FLG"]
                    );
                    if (
                        dataModel["other"]["data"][0]["KUPON_KIGEN_FORM"] !=
                        null
                    ) {
                        var kFromKikan =
                            dataModel["other"]["data"][0][
                                "KUPON_KIGEN_FORM"
                            ].substring(0, 4) +
                            "/" +
                            dataModel["other"]["data"][0][
                                "KUPON_KIGEN_FORM"
                            ].substring(4, 6) +
                            "/" +
                            dataModel["other"]["data"][0][
                                "KUPON_KIGEN_FORM"
                            ].substring(6, 8);
                        $(".FrmMessejiToroku.txtKFromKikan").val(kFromKikan);
                    }
                    if (
                        dataModel["other"]["data"][0]["KUPON_KIGEN_TO"] != null
                    ) {
                        var kToKikan =
                            dataModel["other"]["data"][0][
                                "KUPON_KIGEN_TO"
                            ].substring(0, 4) +
                            "/" +
                            dataModel["other"]["data"][0][
                                "KUPON_KIGEN_TO"
                            ].substring(4, 6) +
                            "/" +
                            dataModel["other"]["data"][0][
                                "KUPON_KIGEN_TO"
                            ].substring(6, 8);
                        $(".FrmMessejiToroku.txtKToKikan").val(kToKikan);
                    }
                    $(".FrmMessejiToroku.txtTitle").val(
                        dataModel["other"]["data"][0]["TAITORU"]
                    );
                    if (
                        dataModel["other"]["data"][0][
                            "SHAKEN_TENKEN_JOHO_KBN"
                        ] != null
                    ) {
                        $(".FrmMessejiToroku.txtShaken").val(
                            dataModel["other"]["data"][0][
                                "SHAKEN_TENKEN_JOHO_KBN"
                            ]
                        );
                    }
                    if (
                        dataModel["other"]["data"][0]["SHARYO_JOHO_FLG"] != null
                    ) {
                        $(".FrmMessejiToroku.txtSharyo").val(
                            dataModel["other"]["data"][0]["SHARYO_JOHO_FLG"]
                        );
                    }
                    $(".FrmMessejiToroku.txtImg").val(
                        dataModel["other"]["data"][0]["MEIN_GAZO_MEI"]
                    );
                    $(".FrmMessejiToroku.txtImgUrl").val(
                        dataModel["other"]["data"][0]["MEIN_GAZO_URL"]
                    );
                    $(".FrmMessejiToroku.txtMessage1").val(
                        dataModel["other"]["data"][0]["MESSEJI_NAIYO1"]
                    );
                    $(".FrmMessejiToroku.txtMessage2").val(
                        dataModel["other"]["data"][0]["MESSEJI_NAIYO2"]
                    );
                    $(".FrmMessejiToroku.txtMessage3").val(
                        dataModel["other"]["data"][0]["MESSEJI_NAIYO3"]
                    );
                    if (
                        dataModel["other"]["data"][0]["KONTAKUTO_BOTAN_FLG"] !=
                        null
                    ) {
                        $(".FrmMessejiToroku.txtRinku").val(
                            dataModel["other"]["data"][0]["KONTAKUTO_BOTAN_FLG"]
                        );
                    }
                    if (
                        dataModel["other"]["data"][0][
                            "SHIJO_YOYAKU_BOTAN_FLG"
                        ] != null
                    ) {
                        $(".FrmMessejiToroku.txtShi").val(
                            dataModel["other"]["data"][0][
                                "SHIJO_YOYAKU_BOTAN_FLG"
                            ]
                        );
                    }
                    if (
                        dataModel["other"]["data"][0][
                            "NYUKO_YOYAKU_BOTAN_FLG"
                        ] != null
                    ) {
                        $(".FrmMessejiToroku.txtRu").val(
                            dataModel["other"]["data"][0][
                                "NYUKO_YOYAKU_BOTAN_FLG"
                            ]
                        );
                    }
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    me.getMaxlength = function () {
        var str = $(".FrmMessejiToroku.txtTitle").val();
        var len = str.length;
        var reLen = 0;
        var maxl = 0;
        for (var i = 0; i < len; i++) {
            if (str.charCodeAt(i) < 27 || str.charCodeAt(i) > 126) {
                // 全角
                reLen += 2;
            } else {
                reLen++;
            }
            if (reLen > 100) {
                maxl = i;
                break;
            } else {
                maxl = i + 1;
            }
        }
        $(".FrmMessejiToroku.txtTitle").val(str.substring(0, maxl));
    };

    //'**********************************************************************
    //'処 理 名：[参照]ボタンクリック
    //'関 数 名：me.btnSansho
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnSansho = function () {
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/" + "fncFile";
        //20170505 WANG UPD S
        // me.file.accept = "image/*";
        me.file.accept = "*";
        //20170505 WANG UPD E
        $("#tmpFileUpload").html("");

        $("#tmpFileUpload").append(me.file.create());
        //20171215 YIN DEL S
        // me.file.select_file();
        //20171215 YIN DEL E

        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();

            //拡張子が "jpg","png" 以外の場合
            if (fileType != "jpg" && fileType != "png") {
                clsComFnc.FncMsgBox(
                    "W9999",
                    "この形式のファイルはサポートされていません"
                );
                return;
            }
            //ファイルが1024KB以上の場合
            if (this.files[i].size > 1024 * 1024) {
                clsComFnc.FncMsgBox("W9999", "ファイルサイズが大きすぎます");
                return;
            }
            var fileName = this.files[i].name;
            var formData = new FormData($("#frmUpload")[0]);

            //指定された画像ファイルをサーバへアップロードする
            $.ajax({
                url: me.sys_id + "/" + me.id + "/" + "fncFile",
                type: "POST",
                data: formData,
                async: false,
                cache: false,
                processData: false,
                contentType: false,
                success: function (result) {
                    result = eval("(" + result + ")");
                    me.img = result["img"];
                    $(".FrmMessejiToroku.txtImg").val(fileName);
                },
                error: function () {
                    clsComFnc.FncMsgBox(
                        "W9999",
                        "ファイルアップロードに失敗しました"
                    );
                    return;
                },
            });
        });
        //20171215 YIN INS S
        me.file.select_file();
        //20171215 YIN INS E
    };

    //'**********************************************************************
    //'処 理 名：[キャンセル]ボタンクリック
    //'関 数 名：me.btnCancel
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnCancel = function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.FncCancelConfirm;
        clsComFnc.FncMsgBox("QY999", "キャンセルします。よろしいですか？");
    };

    //'**********************************************************************
    //'処 理 名：[キャンセル(YES)]ボタンクリック
    //'関 数 名：me.FncCancelConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FncCancelConfirm = function () {
        $(".FrmMessejiToroku.body").dialog("close");
    };

    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：fncInputCheck
    //引    数：無し
    //戻 り 値：True:正常終了 False:異常終了
    //処理説明：入力チェック
    //**********************************************************************
    function fncInputCheck() {
        //20170515 LQS INS S
        if (
            clsComFnc.CheckDate($(".FrmMessejiToroku.txtKFromKikan")) ==
                false &&
            $(".FrmMessejiToroku.txtKFromKikan").val() != ""
        ) {
            clsComFnc.FncMsgBox("W0022", "クーポン期間From", "「YYYY/MM/DD」");
            return false;
        }
        if (
            clsComFnc.CheckDate($(".FrmMessejiToroku.txtKToKikan")) == false &&
            $(".FrmMessejiToroku.txtKToKikan").val() != ""
        ) {
            clsComFnc.FncMsgBox("W0022", "クーポン期間To", "「YYYY/MM/DD」");
            return false;
        }
        //20170515 LQS INS E
        return true;
    }

    function fncMsgDateCheck(dateFrom, dateTo) {
        //メッセージ表示期間From,Toが未入力の場合
        if (dateFrom == "" || dateTo == "") {
            clsComFnc.FncMsgBox(
                "W9999",
                "メッセージ利用期間を指定してください"
            );
            return false;
        }
        //クーポン期間From,Toが未入力の場合
        if (
            clsComFnc.CheckDate($(".FrmMessejiToroku.txtMFromKikan")) == false
        ) {
            clsComFnc.FncMsgBox(
                "W0022",
                "メッセージ利用期間From",
                "「YYYY/MM/DD」"
            );
            return false;
        }
        if (clsComFnc.CheckDate($(".FrmMessejiToroku.txtMToMKikan")) == false) {
            clsComFnc.FncMsgBox(
                "W0022",
                "メッセージ利用期間To",
                "「YYYY/MM/DD」"
            );
            return false;
        }
        //期間チェック
        if (dateFrom > dateTo) {
            clsComFnc.FncMsgBox(
                "W9999",
                "メッセージ期間（至）はメッセージ期間（自）以降の日付を入力してください。"
            );
            return false;
        }
        return true;
    }

    //'**********************************************************************
    //'処 理 名：[登録]ボタンクリック
    //'関 数 名：me.btnTouroku
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnTouroku = function () {
        //[モード] ＝ 1 、2のとき
        if (me.strMODE == "1" || me.strMODE == "2") {
            if (!fncInputCheck()) {
                return;
            }
            var msgDateFrom = $(".FrmMessejiToroku.txtMFromKikan").val();
            var msgDateTo = $(".FrmMessejiToroku.txtMToMKikan").val();
            var kuboDateFrom = $(".FrmMessejiToroku.txtKFromKikan").val();
            var kuboDateTo = $(".FrmMessejiToroku.txtKToKikan").val();

            //系统时间取得
            var today = new Date();
            var day = today.getDate();
            var month = today.getMonth() + 1;
            var year = today.getFullYear();
            month = month < 10 ? "0" + month : month;
            day = day < 10 ? "0" + (day + 2) : parseInt(day) + 2;
            var date = year + "/" + month + "/" + day;
            //内容区分が未入力の場合
            if ($(".FrmMessejiToroku.txtContent").val() == "") {
                clsComFnc.FncMsgBox("W9999", "内容区分を指定してください");
                return;
            }
            if (fncMsgDateCheck(msgDateFrom, msgDateTo) == false) {
                return;
            }
            if ($(".FrmMessejiToroku.txtContent").val() == "02") {
                if (kuboDateFrom == "" || kuboDateTo == "") {
                    clsComFnc.FncMsgBox(
                        "W9999",
                        "クーポン期間を指定してください"
                    );
                    return;
                }
            }
            if ($(".FrmMessejiToroku.txtContent").val() == "02") {
                if (kuboDateFrom > kuboDateTo) {
                    clsComFnc.FncMsgBox(
                        "W9999",
                        "クーポン期間（至）はクーポン期間（自）以降の日付を入力してください。"
                    );
                    return;
                }
                if (msgDateFrom > kuboDateFrom) {
                    clsComFnc.FncMsgBox("W9999", "クーポン期間Fromが不正です");
                    return;
                }
                if (kuboDateFrom < date) {
                    clsComFnc.FncMsgBox("W9999", "クーポン期間Fromが不正です");
                    return;
                }
                if (msgDateTo != kuboDateTo) {
                    clsComFnc.FncMsgBox("W9999", "クーポン期間Toが不正です");
                    return;
                }
            }
            //20170522 LQS INS S
            if (
                clsComFnc.GetByteCount($(".FrmMessejiToroku.txtTitle").val()) >
                100
            ) {
                clsComFnc.FncMsgBox("W0003", "タイトル");
                return;
            }
            if (
                clsComFnc.GetByteCount($(".FrmMessejiToroku.txtImgUrl").val()) >
                300
            ) {
                clsComFnc.FncMsgBox("W0003", "メイン画像URL");
                return;
            }
            if (
                clsComFnc.GetByteCount(
                    $(".FrmMessejiToroku.txtMessage1").val()
                ) > 200
            ) {
                clsComFnc.FncMsgBox("W0003", "メッセージ内容1");
                return;
            }
            if (
                clsComFnc.GetByteCount(
                    $(".FrmMessejiToroku.txtMessage2").val()
                ) > 200
            ) {
                clsComFnc.FncMsgBox("W0003", "メッセージ内容2");
                return;
            }
            if (
                clsComFnc.GetByteCount(
                    $(".FrmMessejiToroku.txtMessage3").val()
                ) > 200
            ) {
                clsComFnc.FncMsgBox("W0003", "メッセージ内容3");
                return;
            }
            //20170522 LQS INS E
            //20170508 WANG UPD E
            //入力文字の機種依存チェック
            var url = me.sys_id + "/" + me.id + "/" + "fncCheckStr";
            var arr = {
                title: $(".FrmMessejiToroku.txtTitle").val(),
                img: $(".FrmMessejiToroku.txtImg").val(),
                imgUrl: $(".FrmMessejiToroku.txtImgUrl").val(),
                msg1: $(".FrmMessejiToroku.txtMessage1").val(),
                msg2: $(".FrmMessejiToroku.txtMessage2").val(),
                msg3: $(".FrmMessejiToroku.txtMessage3").val(),
            };
            var data = {
                request: arr,
            };
            ajax.receive = function (result) {
                result = eval("(" + result + ")");
                //チェック結果＝OKの場合
                if (
                    result["code1"]["result"] &&
                    result["code2"]["result"] &&
                    result["code3"]["result"] &&
                    result["title"]["result"] &&
                    result["img"]["result"] &&
                    result["imgUrl"]["result"]
                ) {
                    if (me.strMODE == "1") {
                        //「はい」を選択したとき
                        clsComFnc.MsgBoxBtnFnc.Yes = me.FncTourokuConfirm;
                        clsComFnc.FncMsgBox("QY010");
                    }
                    if (me.strMODE == "2") {
                        //「はい」を選択したとき
                        clsComFnc.MsgBoxBtnFnc.Yes = me.FncUpdateConfirm;
                        //20170505 WANG UPD S
                        //clsComFnc.FncMsgBox("QO999", "更新します。よろしいですか？");
                        clsComFnc.FncMsgBox(
                            "QY999",
                            "更新します。よろしいですか？"
                        );
                        //20170505 WANG UPD E
                    }
                }
                //チェック結果＝ＮＧの場合
                else {
                    if (!result["title"]["result"]) {
                        $(".FrmMessejiToroku.txtTitle").trigger("focus");
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "使用できない文字が入力されています"
                        );
                        return;
                    }
                    if (!result["img"]["result"]) {
                        $(".FrmMessejiToroku.txtImg").trigger("focus");
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "使用できない文字が入力されています"
                        );
                        return;
                    }
                    if (!result["imgUrl"]["result"]) {
                        $(".FrmMessejiToroku.txtImgUrl").trigger("focus");
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "使用できない文字が入力されています"
                        );
                        return;
                    }
                    if (!result["code1"]["result"]) {
                        $(".FrmMessejiToroku.txtMessage1").trigger("focus");
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "使用できない文字が入力されています"
                        );
                        return;
                    }
                    if (!result["code2"]["result"]) {
                        $(".FrmMessejiToroku.txtMessage2").trigger("focus");
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "使用できない文字が入力されています"
                        );
                        return;
                    }
                    if (!result["code3"]["result"]) {
                        $(".FrmMessejiToroku.txtMessage3").trigger("focus");
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "使用できない文字が入力されています"
                        );
                        return;
                    }
                }
            };
            ajax.send(url, data, 0);
        }
        if (me.strMODE == "3") {
            //「はい」を選択したとき
            clsComFnc.MsgBoxBtnFnc.Yes = me.FncDeleteConfirm;
            //20170505 WANG UPD S
            //clsComFnc.FncMsgBox("QO999", "削除します。よろしいですか？");
            clsComFnc.FncMsgBox("QY999", "削除します。よろしいですか？");
            //20170505 WANG UPD E
        }
    };

    //'**********************************************************************
    //'処 理 名：[登録(YES)]ボタンクリック
    //'関 数 名：me.FncTourokuConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FncTourokuConfirm = function () {
        var url = me.sys_id + "/" + me.id + "/" + "FncTourokuTSaiban";
        var arr = {
            kbn: $(".FrmMessejiToroku.txtContent").val(),
        };
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            //20170504 WANG UPD S
            //if (!result['insMsg']['result'])
            if (!result["result"]) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                $(".FrmMessejiToroku.body").dialog("close");
                return;
            }
            //var txtCode = result['insMsg']['data'][0]['REMBAN'];
            var txtCode = result["data"][0]["REMBAN"];
            //20170504 WANG UPD E

            if (txtCode.length < 3) {
                txtCode = "0000" + txtCode;
            }
            txtCode = txtCode.substring(txtCode.length - 4);

            if ($(".FrmMessejiToroku.txtContent").val() == "01") {
                txtCode = "01" + txtCode;
            }
            if ($(".FrmMessejiToroku.txtContent").val() == "02") {
                txtCode = "02" + txtCode;
            }
            if ($(".FrmMessejiToroku.txtContent").val() == "03") {
                txtCode = "03" + txtCode;
            }
            //20170504 WANG DEL S
            //if (result['insMsg']['row'] > 0)
            //20170504 WANG DEL E
            {
                $(".FrmMessejiToroku.txtCode").val(txtCode);

                var url = me.sys_id + "/" + me.id + "/" + "FncTourokuConfirm";
                var arr = {
                    tmp: me.img,
                    txtContent: $(".FrmMessejiToroku.txtContent").val(),
                    txtCode: $(".FrmMessejiToroku.txtCode").val(),
                    txtKidoku: $(".FrmMessejiToroku.txtKidoku").val(),
                    txtMogiri: $(".FrmMessejiToroku.txtMogiri").val(),
                    txtMFromKikan: $(".FrmMessejiToroku.txtMFromKikan").val(),
                    txtMToMKikan: $(".FrmMessejiToroku.txtMToMKikan").val(),
                    txtKFromKikan: $(".FrmMessejiToroku.txtKFromKikan").val(),
                    txtKToKikan: $(".FrmMessejiToroku.txtKToKikan").val(),
                    txtShaken: $(".FrmMessejiToroku.txtShaken").val(),
                    txtSharyo: $(".FrmMessejiToroku.txtSharyo").val(),
                    txtTitle: $(".FrmMessejiToroku.txtTitle").val(),
                    txtImg: $(".FrmMessejiToroku.txtImg").val(),
                    txtImgUrl: $(".FrmMessejiToroku.txtImgUrl").val(),
                    txtMessage1: $(".FrmMessejiToroku.txtMessage1").val(),
                    txtMessage2: $(".FrmMessejiToroku.txtMessage2").val(),
                    txtMessage3: $(".FrmMessejiToroku.txtMessage3").val(),
                    txtRinku: $(".FrmMessejiToroku.txtRinku").val(),
                    txtShi: $(".FrmMessejiToroku.txtShi").val(),
                    txtRu: $(".FrmMessejiToroku.txtRu").val(),
                };
                var data = {
                    request: arr,
                };
                ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    //20170504 WANG UPD S
                    //if (result['ym']['result'])
                    if (result["result"]) {
                        me.search = true;
                        $(".FrmMessejiToroku.body").dialog("close");
                    } else {
                        me.search = false;
                        //clsComFnc.FncMsgBox("W9999", "新規登録失敗");
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        $(".FrmMessejiToroku.body").dialog("close");
                    }
                    //20170504 WANG UPD E
                };
                ajax.send(url, data, 0);
            }
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：[変更]ボタンクリック
    //'関 数 名：me.FncUpdateConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FncUpdateConfirm = function () {
        var url = me.sys_id + "/" + me.id + "/" + "FncUpdateConfirm";
        var arr = {
            tmp: me.img,
            txtContent: $(".FrmMessejiToroku.txtContent").val(),
            txtCode: $(".FrmMessejiToroku.txtCode").val(),
            txtKidoku: $(".FrmMessejiToroku.txtKidoku").val(),
            txtMogiri: $(".FrmMessejiToroku.txtMogiri").val(),
            txtMFromKikan: $(".FrmMessejiToroku.txtMFromKikan").val(),
            txtMToMKikan: $(".FrmMessejiToroku.txtMToMKikan").val(),
            txtKFromKikan: $(".FrmMessejiToroku.txtKFromKikan").val(),
            txtKToKikan: $(".FrmMessejiToroku.txtKToKikan").val(),
            txtShaken: $(".FrmMessejiToroku.txtShaken").val(),
            txtSharyo: $(".FrmMessejiToroku.txtSharyo").val(),
            txtTitle: $(".FrmMessejiToroku.txtTitle").val(),
            txtImg: $(".FrmMessejiToroku.txtImg").val(),
            txtImgUrl: $(".FrmMessejiToroku.txtImgUrl").val(),
            txtMessage1: $(".FrmMessejiToroku.txtMessage1").val(),
            txtMessage2: $(".FrmMessejiToroku.txtMessage2").val(),
            txtMessage3: $(".FrmMessejiToroku.txtMessage3").val(),
            txtRinku: $(".FrmMessejiToroku.txtRinku").val(),
            txtShi: $(".FrmMessejiToroku.txtShi").val(),
            txtRu: $(".FrmMessejiToroku.txtRu").val(),
        };
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            //20170505 WANG UPD S
            //if (result['number_of_rows'] == 1)
            if (result["result"]) {
                me.search = true;
                $(".FrmMessejiToroku.body").dialog("close");
            } else {
                me.search = false;
                //clsComFnc.FncMsgBox("W9999", "更新失敗");
                clsComFnc.FncMsgBox("E9999", result["data"]);
                //20170505 WANG UPD E
                $(".FrmMessejiToroku.body").dialog("close");
            }
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：[削除]ボタンクリック
    //'関 数 名：me.FncDeleteConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FncDeleteConfirm = function () {
        var url = me.sys_id + "/" + me.id + "/" + "FncDeleteConfirm";
        var arr = {
            txtImg: $(".FrmMessejiToroku.txtImg").val(),
            txtCode: $(".FrmMessejiToroku.txtCode").val(),
        };
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["number_of_rows"] == 1) {
                me.search = true;
                $(".FrmMessejiToroku.body").dialog("close");
            } else {
                me.search = false;
                //20170508 WANG UPD S
                //clsComFnc.FncMsgBox("W9999", "削除失敗");
                clsComFnc.FncMsgBox("E9999", result["data"]);
                //20170508 WANG UPD E
                $(".FrmMessejiToroku.body").dialog("close");
            }
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：[プレビュー]ボタンクリック
    //'関 数 名：me.btnRebu
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnRebu = function () {
        var url = me.sys_id + "/" + "FrmPurebyu";

        var arr = {};
        var data = {
            request: arr,
        };

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                FLG: "1",
                img: me.img,
                MODE: me.strMODE,
                //20170523 LQS DEL S
                //"txtContent" : $(".FrmMessejiToroku.txtContent").val(),
                //20170523 LQS DEL E
                txtShaken: $(".FrmMessejiToroku.txtShaken").val(),
                txtShaken1: $(".FrmMessejiToroku.txtShaken")
                    .find("option:selected")
                    .text(),
                txtSharyo: $(".FrmMessejiToroku.txtSharyo").val(),
                txtSharyo1: $(".FrmMessejiToroku.txtSharyo")
                    .find("option:selected")
                    .text(),
                txtTitle: $(".FrmMessejiToroku.txtTitle").val(),
                txtImg: $(".FrmMessejiToroku.txtImg").val(),
                //20170523 LQS INS S
                imgUrl: $(".FrmMessejiToroku.txtImgUrl").val(),
                //20170523 LQS INS E
                txtMessage1: $(".FrmMessejiToroku.txtMessage1").val(),
                txtMessage2: $(".FrmMessejiToroku.txtMessage2").val(),
                txtMessage3: $(".FrmMessejiToroku.txtMessage3").val(),
                txtRinku: $(".FrmMessejiToroku.txtRinku").val(),
                txtRinku1: $(".FrmMessejiToroku.txtRinku")
                    .find("option:selected")
                    .text(),
                //20170523 LQS INS S
                txtShi: $(".FrmMessejiToroku.txtShi").val(),
                txtShi1: $(".FrmMessejiToroku.txtShi")
                    .find("option:selected")
                    .text(),
                txtRu: $(".FrmMessejiToroku.txtRu").val(),
                txtRu1: $(".FrmMessejiToroku.txtRu")
                    .find("option:selected")
                    .text(),
                //20170523 LQS INS E
            })
        );

        ajax.receive = function (result) {
            //20170505 WANG UPD S
            //$('.FrmMessejiToroku.FrmPurebyu_dialog').append(result);
            $("#FrmMToroku_dialog").append(result);
            //20170505 WANG UPD E

            function before_close() {}
            o_APPM_APPM.FrmPurebyu.before_close = before_close;
        };
        ajax.send(url, data, 0);
    };
    // ========== 関数 end ==========

    return me;
};

$(function () {
    o_APPM_FrmMessejiToroku = new APPM.FrmMessejiToroku();
    o_APPM_FrmMessejiToroku.load();
    o_APPM_APPM.FrmMessejiToroku = o_APPM_FrmMessejiToroku;
});
