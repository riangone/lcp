Namespace.register("HMTVE.HMTVE330HDTSYASYUEntry");

HMTVE.HMTVE330HDTSYASYUEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.hmtve = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMTVE330HDTSYASYUEntry";
    me.sys_id = "HMTVE";

    // ========== 変数 start ==========

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE330HDTSYASYUEntry.btnLogin",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE330HDTSYASYUEntry.btnReturn",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.hmtve.TabKeyDown(me.id);

    //Enterキーのバインド
    me.hmtve.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：登録ボタン押下時
    $(".HMTVE330HDTSYASYUEntry.btnLogin").click(function () {
        me.btnLogin_Click();
    });

    //処理説明：一覧へボタン押下時
    $(".HMTVE330HDTSYASYUEntry.btnReturn").click(function () {
        $(".HMTVE330HDTSYASYUEntry.HMTVE330HDTSYASYUEntryDialog").dialog(
            "close"
        );
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：init_control
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();

        //初期設定処理
        $(".HMTVE330HDTSYASYUEntry.HMTVE330HDTSYASYUEntryDialog").dialog({
            autoOpen: false,
            height: me.ratio === 1.5 ? 290 : 360,
            width: me.ratio === 1.5 ? 770 : 795,
            modal: true,
            title: "車種マスタメンテナンス_入力",
            open: function () {},
            close: function () {
                me.before_close();
                $(
                    ".HMTVE330HDTSYASYUEntry.HMTVE330HDTSYASYUEntryDialog"
                ).remove();
            },
        });
        $(".HMTVE330HDTSYASYUEntry.HMTVE330HDTSYASYUEntryDialog").dialog(
            "open"
        );

        me.Page_Load();
    };

    me.before_close = function () {};

    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ページ初期化
    //**********************************************************************
    me.Page_Load = function () {
        try {
            //画面項目をクリアする
            me.PageClear();

            if ($("#MODE").html() == 2) {
                me.UpdateData();
            } else if ($("#MODE").html() == "" || $("#MODE").html() == 1) {
                //登録画面起動時の引継ぎパラメータ(モード)＝"" の場合(新規モード)
                $(".HMTVE330HDTSYASYUEntry.carTypeCode").trigger("focus");
            }
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：PageClear
    //戻 り 値：なし
    //処理説明：ページクリア
    //**********************************************************************
    me.PageClear = function () {
        $(".HMTVE330HDTSYASYUEntry.carTypeCode").val("");
        $(".HMTVE330HDTSYASYUEntry.carTypeCode").prop("enabled", true);
        $(".HMTVE330HDTSYASYUEntry.carTypeName").val("");
        $(".HMTVE330HDTSYASYUEntry.carTypeAbbr").val("");
        $(".HMTVE330HDTSYASYUEntry.r1").prop("checked", true);
        $(".HMTVE330HDTSYASYUEntry.r2").prop("checked", false);
        $(".HMTVE330HDTSYASYUEntry.r3").prop("checked", false);
        $(".HMTVE330HDTSYASYUEntry.r4").prop("checked", false);
        $(".HMTVE330HDTSYASYUEntry.cbOutput").prop("checked", false);
        $(".HMTVE330HDTSYASYUEntry.cbOutput2").prop("checked", false);
        $(".HMTVE330HDTSYASYUEntry.tbOrder").val("");
    };

    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：UpdateData
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ページ初期化
    //**********************************************************************
    me.UpdateData = function () {
        try {
            var url = me.sys_id + "/" + me.id + "/updateData";
            var data = {
                SYASYU_CD: $("#SYASYU_CD").html(),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    var objdr2 = result["data"];
                    $(".HMTVE330HDTSYASYUEntry.carTypeName").trigger("focus");
                    $(".HMTVE330HDTSYASYUEntry.carTypeCode").val(
                        objdr2[0]["SYASYU_CD"]
                    );
                    $(".HMTVE330HDTSYASYUEntry.carTypeCode").prop(
                        "disabled",
                        "disabled"
                    );
                    $(".HMTVE330HDTSYASYUEntry.carTypeName").val(
                        objdr2[0]["SYASYU_NM"]
                    );
                    $(".HMTVE330HDTSYASYUEntry.carTypeAbbr").val(
                        objdr2[0]["SYASYU_RYKNM"]
                    );
                    if (objdr2[0]["SYASYU_KB"] != "") {
                        rb = objdr2[0]["SYASYU_KB"];
                        switch (rb) {
                            case "0":
                                $(".HMTVE330HDTSYASYUEntry.r2").prop(
                                    "checked",
                                    true
                                );
                                break;
                            case "1":
                                $(".HMTVE330HDTSYASYUEntry.r1").prop(
                                    "checked",
                                    true
                                );
                                break;
                            case "2":
                                $(".HMTVE330HDTSYASYUEntry.r3").prop(
                                    "checked",
                                    true
                                );
                                break;
                            case "3":
                                $(".HMTVE330HDTSYASYUEntry.r4").prop(
                                    "checked",
                                    true
                                );
                                break;
                            default:
                        }
                    }
                    if (objdr2[0]["SOKU_SEIYAKU_OUT_FLG"] == "1") {
                        $(".HMTVE330HDTSYASYUEntry.cbOutput").prop(
                            "checked",
                            true
                        );
                    }

                    if (objdr2[0]["KAKU_DEMO_OUT_FLG"] == "1") {
                        $(".HMTVE330HDTSYASYUEntry.cbOutput2").prop(
                            "checked",
                            true
                        );
                    }

                    $(".HMTVE330HDTSYASYUEntry.tbOrder").val(
                        objdr2[0]["DISP_NO"]
                    );
                } else {
                    $(".HMTVE330HDTSYASYUEntry.btnLogin").button("disable");
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：fncInputCheck
    //引    数：無し
    //戻 り 値：無し
    //処理説明：入力の内容をチェック
    //**********************************************************************
    me.fncInputCheck = function () {
        //入力チェック
        //フラグ：－１を含む場合、エラ
        //車種コード
        if ($.trim($(".HMTVE330HDTSYASYUEntry.carTypeCode").val()) == "") {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.carTypeCode").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox("W9999", "車種コードを入力してください。");
            return false;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE330HDTSYASYUEntry.carTypeCode").val())
            ) > 3
        ) {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.carTypeCode").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox(
                "W9999",
                "車種コードは指定されている桁数をオーバーしています。"
            );
            return false;
        } else if (
            $.trim($(".HMTVE330HDTSYASYUEntry.carTypeCode").val()).indexOf(
                "-"
            ) == 0
        ) {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.carTypeCode").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox("W9999", "車種コードの入力値が不正です！");
            return false;
        } else if (
            me.FncChkKeyCharAN2(
                $.trim($(".HMTVE330HDTSYASYUEntry.carTypeCode").val())
            )
        ) {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.carTypeCode").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox("W9999", "車種コードの入力値が不正です！");
            return false;
        }

        //車種名
        if ($.trim($(".HMTVE330HDTSYASYUEntry.carTypeName").val()) == "") {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.carTypeName").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox("W9999", "車種名を入力してください。");
            return false;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE330HDTSYASYUEntry.carTypeName").val())
            ) > 40
        ) {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.carTypeName").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox(
                "W9999",
                "車種名は指定されている桁数をオーバーしています。"
            );
            return false;
        }

        // 車種略称名
        if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE330HDTSYASYUEntry.carTypeAbbr").val())
            ) > 20
        ) {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.carTypeAbbr").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox(
                "W9999",
                "車種略称名は指定されている桁数をオーバーしています。"
            );
            return false;
        }

        //車種区分
        if (
            $(".HMTVE330HDTSYASYUEntry.r1").prop("checked") == false &&
            $(".HMTVE330HDTSYASYUEntry.r2").prop("checked") == false &&
            $(".HMTVE330HDTSYASYUEntry.r3").prop("checked") == false &&
            $(".HMTVE330HDTSYASYUEntry.r4").prop("checked") == false
        ) {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.carTypeName").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox("W9999", "車種区分を選択してください。");
            return false;
        }

        //表示順
        if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE330HDTSYASYUEntry.tbOrder").val())
            ) > 2
        ) {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.tbOrder").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox(
                "W9999",
                "表示順確認は指定されている桁数をオーバーしています。"
            );
            return false;
        } else if (
            me.FncChkKeyCharN(
                $.trim($(".HMTVE330HDTSYASYUEntry.tbOrder").val())
            )
        ) {
            //エラー項目にフォーカス移動
            $(".HMTVE330HDTSYASYUEntry.tbOrder").trigger("focus");
            //エラーメッセージを表示して、処理を中止する
            me.clsComFnc.FncMsgBox("W9999", "表示順の入力値が不正です！");
            return false;
        }
        return true;
    };

    //**********************************************************************
    //処 理 名：登録ボタンのイベント
    //関 数 名：btnLogin_Click
    //引    数：なし
    //戻 り 値：なし
    //処理説明：ﾛｸﾞｲﾝ情報の登録処理
    //**********************************************************************
    me.btnLogin_Click = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            try {
                if (!me.fncInputCheck()) {
                    window.setTimeout(function () {
                        var len =
                            $(".ui-dialog-buttons").find(".ui-button").length;
                        if (len > 0) {
                            $(".ui-dialog-buttons")
                                .find(".ui-button")
                                .eq(len - 1)
                                .trigger("focus");
                        }
                    }, 0);
                    return;
                }
                //データバインドする
                var url = me.sys_id + "/" + me.id + "/btnLogin_Click";
                var data = me.getContent();
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"]) {
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            //画面制御
                            if (
                                $("#MODE").html() == 1 ||
                                $("#MODE").html() == ""
                            ) {
                                me.PageClear();
                            } else {
                                $(
                                    ".HMTVE330HDTSYASYUEntry.HMTVE330HDTSYASYUEntryDialog"
                                ).dialog("close");
                            }
                        };
                        //登録が完了しました。
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE330HDTSYASYUEntry.carTypeCode"
                        );
                        me.clsComFnc.FncMsgBox("I0016");
                    } else {
                        if (result["error"] == "W0025") {
                            me.clsComFnc.ObjFocus = $(
                                ".HMTVE330HDTSYASYUEntry.btnReturn"
                            );
                            //他のユーザーにより更新されています。最新の情報を確認してください。
                            me.clsComFnc.FncMsgBox("W0025");
                        } else if (result["error"] == "E0016") {
                            me.clsComFnc.ObjFocus = $(
                                ".HMTVE330HDTSYASYUEntry.carTypeCode"
                            );
                            //既に登録されています。
                            me.clsComFnc.FncMsgBox("E0016");
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        }
                    }
                };
                me.ajax.send(url, data, 0);
            } catch (ex) {
                console.log(ex);
            }
        };
        me.clsComFnc.FncMsgBox(
            "QY999",
            "車種マスタに登録します。よろしいですか？"
        );
    };

    //**********************************************************************
    //処理名：コンテンツ取得
    //関数名：getContent
    //引   数：なし
    //戻り値：data
    //処理説明：画面の内容を取得する
    //**********************************************************************
    me.getContent = function () {
        var SYASYU_KB = "";
        if ($(".HMTVE330HDTSYASYUEntry.r1").prop("checked")) {
            SYASYU_KB = 1;
        } else if ($(".HMTVE330HDTSYASYUEntry.r2").prop("checked")) {
            SYASYU_KB = 0;
        } else if ($(".HMTVE330HDTSYASYUEntry.r3").prop("checked")) {
            SYASYU_KB = 2;
        } else if ($(".HMTVE330HDTSYASYUEntry.r4").prop("checked")) {
            SYASYU_KB = 3;
        }
        var SOKU_SEIYAKU_OUT_FLG = "";
        if ($(".HMTVE330HDTSYASYUEntry.cbOutput").prop("checked")) {
            SOKU_SEIYAKU_OUT_FLG = 1;
        }
        var KAKU_DEMO_OUT_FLG = "";
        if ($(".HMTVE330HDTSYASYUEntry.cbOutput2").prop("checked")) {
            KAKU_DEMO_OUT_FLG = 1;
        }
        var data = {
            MODE: $("#MODE").html(),
            SYASYU_CD: $.trim($(".HMTVE330HDTSYASYUEntry.carTypeCode").val()),
            SYASYU_NM: $(".HMTVE330HDTSYASYUEntry.carTypeName").val(),
            SYASYU_RYKNM: $(".HMTVE330HDTSYASYUEntry.carTypeAbbr").val(),
            SYASYU_KB: SYASYU_KB,
            SOKU_SEIYAKU_OUT_FLG: SOKU_SEIYAKU_OUT_FLG,
            KAKU_DEMO_OUT_FLG: KAKU_DEMO_OUT_FLG,
            DISP_NO: $.trim($(".HMTVE330HDTSYASYUEntry.tbOrder").val()),
        };
        return data;
    };

    //**********************************************************************
    //処理名：入力時の文字チェック(半角英数字記号)
    //関数名：FncChkKeyCharAN2
    //引   数：strKey     (I) 文字
    //戻り値：True     入力不可文字
    //          False    入力可能文字
    //処理説明：半角英数字以外の入力を制限するためのチェック処理
    //**********************************************************************
    me.FncChkKeyCharAN2 = function (strKey) {
        var Regex = /[^a-zA-Z0-9\-]/;
        if (Regex.test(strKey)) {
            return true;
        } else {
            return false;
        }
    };

    //**********************************************************************
    //処理名：入力時の文字チェック(数字)
    //関数名：FncChkKeyCharN
    //引   数：strKey     (I) 文字
    //戻り値：True     入力不可文字
    //          False    入力可能文字
    //処理説明：数字以外の入力を制限するためのチェック処理
    //**********************************************************************
    me.FncChkKeyCharN = function (strKey) {
        var Regex = /[\D]/;
        if (Regex.test(strKey)) {
            return true;
        } else {
            return false;
        }
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE330HDTSYASYUEntry = new HMTVE.HMTVE330HDTSYASYUEntry();
    o_HMTVE_HMTVE330HDTSYASYUEntry.load();
    o_HMTVE_HMTVE.HMTVE320HDTSYASYUList.HMTVE330HDTSYASYUEntry =
        o_HMTVE_HMTVE330HDTSYASYUEntry;
});
