Namespace.register("HMTVE.HMTVE380MLOGINEntry");

HMTVE.HMTVE380MLOGINEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.hmtve = new HMTVE.HMTVE();

    // ========== 変数 start ==========

    me.id = "HMTVE380MLOGINEntry";
    me.sys_id = "HMTVE";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE380MLOGINEntry.button",
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

    // 登録
    $(".HMTVE380MLOGINEntry.btnLogin").click(function () {
        //password focus
        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.txtPassword");
        };
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnLogin_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "ﾛｸﾞｲﾝ情報を登録します。よろしいですか？"
        );
    });

    // ichilan
    $(".HMTVE380MLOGINEntry.btnAll").click(function () {
        $("#RtnCD").html("1");
        $(".HMTVE380MLOGINEntry.HMTVE380MLOGINEntryDialog").dialog("close");
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_Load();
    };
    /*
	 '************************************************************************
	 '処 理 名：ページロード
	 '関 数 名：Page_Load
	 '引    数：なし
	 '戻 り 値 ：なし
	 '処理説明 ：ページ初期化
	 '************************************************************************
	 */
    me.Page_Load = function () {
        me.before_close = function () {};
        $(".HMTVE380MLOGINEntry.HMTVE380MLOGINEntryDialog").dialog({
            autoOpen: false,
            width: 500,
            height: 270,
            modal: true,
            title: "ログイン情報登録_入力",
            open: function () {},
            close: function () {
                $("#RtnCD").html("1");
                me.before_close();
                $(".HMTVE380MLOGINEntry.HMTVE380MLOGINEntryDialog").remove();
            },
        });
        $(".HMTVE380MLOGINEntry.HMTVE380MLOGINEntryDialog").dialog("open");

        //画面初期化
        //画面項目をクリアする
        $(".HMTVE380MLOGINEntry.txtUserID").val("");
        $(".HMTVE380MLOGINEntry.txtPassword").val("");
        $(".HMTVE380MLOGINEntry.txtPasswordAgain").val("");
        $(".HMTVE380MLOGINEntry.ddlRights").find("option").remove();
        //権限のコンボリストのデータソースを取得する
        var url = me.sys_id + "/" + me.id + "/pageLoad";
        var data = {
            USERID: $("#userid").html(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                $(".HMTVE380MLOGINEntry.btnLogin").button("disable");
                return;
            }
            if (result["data"] && result["data"]["pattern"]) {
                var pattern = result["data"]["pattern"];
                if (pattern.length > 0) {
                    //データが存在する場合
                    //権限のコンボリストを設定する
                    for (var i = 0; i < pattern.length; i++) {
                        $("<option></option>")
                            .val(pattern[i]["PATTERN_ID"])
                            .text(pattern[i]["PATTERN_NM"])
                            .appendTo(".HMTVE380MLOGINEntry.ddlRights");
                    }
                }
            }
            if ($("#userid").html() != "") {
                //引継ぎパラメータが存在する場合
                $(".HMTVE380MLOGINEntry.txtUserID").val($("#userid").html());
                if (
                    result["data"] &&
                    result["data"]["user"] &&
                    result["data"]["user"].length > 0
                ) {
                    //データが存在する場合
                    var user = result["data"]["user"];
                    //ﾛｸﾞｲﾝ情報データをセットする
                    $(".HMTVE380MLOGINEntry.txtPassword").val(
                        user[0]["PASSWORD"]
                    );
                    $(".HMTVE380MLOGINEntry.txtPasswordAgain").val(
                        user[0]["PASSWORD"]
                    );
                    $(".HMTVE380MLOGINEntry.ddlRights").val(
                        user[0]["PATTERN_ID"]
                    );
                    if (user[0]["REC_CRE_DT"]) {
                        $(".HMTVE380MLOGINEntry.hidCrDate").html(
                            user[0]["REC_CRE_DT"]
                        );
                    } else {
                        $(".HMTVE380MLOGINEntry.hidCrDate").html("");
                    }
                    $(".HMTVE380MLOGINEntry.txtPassword").trigger("focus");
                }
            }
        };

        me.ajax.send(url, data, 0);
    };

    /*
	 '************************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：fncInputCheck
	 '引    数：なし
	 '戻 り 値 ：なし
	 '処理説明 ：入力チェック
	 '************************************************************************
	 */
    me.fncInputCheck = function () {
        var txtUserID = $.trim($(".HMTVE380MLOGINEntry.txtUserID").val());
        if (txtUserID == "") {
            //ユーザーID未入力の場合
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.txtUserID");
            me.clsComFnc.FncMsgBox("W9999", "ユーザーIDを入力してください。");
            return false;
        } else if (txtUserID.length > 5) {
            //ユーザーIDは指定されている桁数をオーバーした場合
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.txtUserID");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ユーザーIDは指定されている桁数をオーバーしています。"
            );
            return false;
        }
        var txtPassword = $.trim($(".HMTVE380MLOGINEntry.txtPassword").val());
        if (txtPassword == "") {
            //パースワード未入力の場合
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.txtPassword");
            me.clsComFnc.FncMsgBox("W9999", "パースワードを入力してください。");
            return false;
        } else if (txtPassword.length > 10) {
            //パースワードは指定されている桁数をオーバーした場合
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.txtPassword");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "パースワードは指定されている桁数をオーバーしています。"
            );
            return false;
        }
        var txtPasswordAgain = $.trim(
            $(".HMTVE380MLOGINEntry.txtPasswordAgain").val()
        );
        if (txtPasswordAgain == "") {
            //パースワード確認未入力の場合
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.txtPasswordAgain");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "パースワード確認を入力してください。"
            );
            return false;
        } else if (txtPasswordAgain.length > 10) {
            //パースワードは指定されている桁数をオーバーした場合
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.txtPasswordAgain");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "パースワード確認は指定されている桁数をオーバーしています。"
            );
            return false;
        }
        if (txtPassword != txtPasswordAgain) {
            //パスワードとパスワード確認が一致しない場合
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.txtPasswordAgain");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "パスワードとパスワード確認が一致しません。"
            );
            return false;
        }
        var ddlRights = $.trim($(".HMTVE380MLOGINEntry.ddlRights").val());
        if (ddlRights == "") {
            //権限未選択の場合
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.ddlRights");
            me.clsComFnc.FncMsgBox("W9999", "権限を選択してください。");
            return false;
        } else if (ddlRights.length > 50) {
            //権限は指定されている桁数をオーバーした場合
            me.clsComFnc.ObjFocus = $(".HMTVE380MLOGINEntry.ddlRights");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "権限は指定されている桁数をオーバーしています。"
            );
            return false;
        }
        return true;
    };
    /*
	 '************************************************************************
	 '処 理 名：登録ボタンのイベント
	 '関 数 名：btnLogin_Click
	 '引    数：なし
	 '戻 り 値 ：なし
	 '処理説明 ：ﾛｸﾞｲﾝ情報の登録処理
	 '************************************************************************
	 */
    me.btnLogin_Click = function () {
        if (!me.fncInputCheck()) {
            window.setTimeout(function () {
                var len = $(".ui-dialog-buttons").find(".ui-button").length;
                if (len > 0) {
                    $(".ui-dialog-buttons")
                        .find(".ui-button")
                        .eq(len - 1)
                        .trigger("focus");
                }
            }, 0);
            return;
        }
        var url = me.sys_id + "/" + me.id + "/btnLogin_Click";
        me.loginData = {
            USERID: $.trim($(".HMTVE380MLOGINEntry.txtUserID").val()),
            PASSWORD: $.trim($(".HMTVE380MLOGINEntry.txtPassword").val()),
            PATTERNID: $.trim($(".HMTVE380MLOGINEntry.ddlRights").val()),
            RECUPDDT: $.trim($(".HMTVE380MLOGINEntry.hidCrDate")[0].innerText),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (
                !result.hasOwnProperty("error") &&
                result.hasOwnProperty("data")
            ) {
                me.clsComFnc.FncMsgBox("I0016");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, me.loginData, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE380MLOGINEntry = new HMTVE.HMTVE380MLOGINEntry();
    o_HMTVE_HMTVE.HMTVE370MLOGINList.HMTVE380MLOGINEntry =
        o_HMTVE_HMTVE380MLOGINEntry;
    o_HMTVE_HMTVE380MLOGINEntry.load();
});
