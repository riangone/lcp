/**
 * 説明：
 *
 *
 * @author YANGYANG
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("PPRM.PPRM801LoginEntry");

PPRM.PPRM801LoginEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "PPRM801LoginEntry";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.USER_ID = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".PPRM801LoginEntry.btnUpdate",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM801LoginEntry.btnBack",
        type: "button",
        handle: "",
    });

    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // 20170922 lqs INS S
    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    clsComFnc.TabKeyDown();
    // 20170922 lqs INS E

    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));

    if (requestdata) {
        me.USER_ID = requestdata["USER_ID"];
    }

    me.before_close = function () {};
    $(".PPRM801LoginEntry.body").dialog({
        autoOpen: false,
        width: me.ratio === 1.5 ? 415 : 450,
        height: me.ratio === 1.5 ? 240 : 250,
        modal: true,
        title: "ログイン情報登録",
        open: function () {},
        close: function () {
            me.before_close();
            $(".PPRM801LoginEntry.body").remove();
            localStorage.removeItem("requestdata");
        },
    });
    $(".PPRM801LoginEntry.body").dialog("open");

    //登録ボタン押下
    $(".PPRM801LoginEntry.btnUpdate").click(function () {
        me.btnUpdate_Click();
    });

    //戻るボタン押下
    $(".PPRM801LoginEntry.btnBack").click(function () {
        me.btnBack_Click();
    });

    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        me.PPRM801LoginEntry_load();
    };

    //ページ初期化
    me.PPRM801LoginEntry_load = function () {
        $(".PPRM801LoginEntry.LvTextUserID").prop("disabled", "disabled");
        $(".PPRM801LoginEntry.LvTextUserID").val(me.USER_ID);
        $(".PPRM801LoginEntry.ddlRights").html("");
        $(".PPRM801LoginEntry.LvTextPass").trigger("focus");

        me.subComboSet();
    };

    //'***********************************************************************
    //'処 理 名：権限ドロップダウンリスト表示
    //'関 数 名：me.subComboSet
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：権限ドロップダウンリスト表示
    //'***********************************************************************
    me.subComboSet = function () {
        var url = me.sys_id + "/" + me.id + "/subComboSet";

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                data = result["data"];

                if (data.length > 0) {
                    for (i = 0; i < data.length; i++) {
                        patternID = data[i]["PATTERN_ID"];
                        patternNM = data[i]["PATTERN_NM"];

                        if (patternID == null) {
                            patternID = "";
                        }
                        if (patternNM == null) {
                            patternNM = "";
                        }

                        var html =
                            "<option value = " +
                            patternID +
                            ">" +
                            patternNM +
                            "</option>";
                        $(".PPRM801LoginEntry.ddlRights").append(html);
                    }
                }

                //登録情報表示
                if (
                    me.USER_ID != "" &&
                    me.USER_ID != null &&
                    me.USER_ID != undefined
                ) {
                    me.subInfoSet();
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, {}, 0);
    };

    //'***********************************************************************
    //'処 理 名：ﾛｸﾞｲﾝ情報データを取得する
    //'関 数 名：me.subInfoSet
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：ﾛｸﾞｲﾝ情報データを取得する
    //'***********************************************************************
    me.subInfoSet = function () {
        var LvTextUserID = $(".PPRM801LoginEntry.LvTextUserID").val();

        var url = me.sys_id + "/" + me.id + "/subInfoSet";
        var data = {
            LvTextUserID: LvTextUserID,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                data = result["data"];

                if (data.length > 0) {
                    $(".PPRM801LoginEntry.LvTextPass").val(data[0]["PASSWORD"]);
                    $(".PPRM801LoginEntry.LvTextPassConfirm").val(
                        data[0]["PASSWORD"]
                    );

                    var patternID = data[0]["PATTERN_ID"];
                    $(".PPRM801LoginEntry.ddlRights option").each(function () {
                        if ($(this).val() == patternID) {
                            $(this).prop("selected", true);
                        }
                    });
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：登録ボタンクリック
    //'関 数 名：me.btnUpdate_Click
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：コンボボックス名を追加する
    //'**********************************************************************
    me.btnUpdate_Click = function () {
        //入力チェック
        if (me.InputCheck() == false) {
            return;
        }

        me.FncUpdateConfirm();
    };

    //'**********************************************************************
    //'処 理 名：入力チェック
    //'関 数 名：me.InputCheck
    //'引 数 １：なし
    //'戻 り 値：Boolean
    //'処理説明：入力内容のチェックを行う
    //'**********************************************************************
    me.InputCheck = function () {
        //パスワードチェック
        var LvTextPass = $(".PPRM801LoginEntry.LvTextPass");

        intRtnCD1 = clsComFnc.FncTextCheck(
            LvTextPass,
            1,
            clsComFnc.INPUTTYPE.CHAR2,
            10
        );

        switch (intRtnCD1) {
            case -1:
                //必須エラー
                $(".PPRM801LoginEntry.LvTextPass").trigger("focus");
                clsComFnc.FncMsgBox("E0001_PPRM", "パスワード");
                return false;

            case -2:
                //不正文字エラー
                $(".PPRM801LoginEntry.LvTextPass").trigger("focus");
                clsComFnc.FncMsgBox("E0002_PPRM", "パスワード");
                return false;

            case -3:
                //桁数エラー
                $(".PPRM801LoginEntry.LvTextPass").trigger("focus");
                clsComFnc.FncMsgBox("E0013_PPRM", "パスワード", "10");
                return false;
        }

        //パスワード確認チェック
        var LvTextPassConfirm = $(".PPRM801LoginEntry.LvTextPassConfirm");

        intRtnCD2 = clsComFnc.FncTextCheck(
            LvTextPassConfirm,
            1,
            clsComFnc.INPUTTYPE.CHAR2,
            10
        );

        switch (intRtnCD2) {
            case -1:
                //必須エラー
                $(".PPRM801LoginEntry.LvTextPassConfirm").trigger("focus");
                clsComFnc.FncMsgBox("E0001_PPRM", "パスワード確認");
                return false;

            case -2:
                //不正文字エラー
                $(".PPRM801LoginEntry.LvTextPassConfirm").trigger("focus");
                clsComFnc.FncMsgBox("E0002_PPRM", "パスワード確認");
                return false;

            case -3:
                //桁数エラー
                $(".PPRM801LoginEntry.LvTextPassConfirm").trigger("focus");
                clsComFnc.FncMsgBox("E0013_PPRM", "パスワード確認", "10");
                return false;
        }

        //整合性チェック
        if (
            $(".PPRM801LoginEntry.LvTextPass").val() !=
            $(".PPRM801LoginEntry.LvTextPassConfirm").val()
        ) {
            $(".PPRM801LoginEntry.LvTextPass").trigger("focus");
            clsComFnc.FncMsgBox(
                "E0011_PPRM",
                "パスワードとパスワード確認が一致しません。"
            );
            return false;
        }

        if (
            $(".PPRM801LoginEntry.ddlRights").val() == null ||
            $(".PPRM801LoginEntry.ddlRights").val() == ""
        ) {
            $(".PPRM801LoginEntry.ddlRights").trigger("focus");
            clsComFnc.FncMsgBox("E0011_PPRM", "権限を選択して下さい");
            return false;
        }
    };

    //'**********************************************************************
    //'処 理 名：ログイン情報を更新する
    //'関 数 名：me.FncUpdateConfirm
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FncUpdateConfirm = function () {
        var LvTextUserID = $(".PPRM801LoginEntry.LvTextUserID").val();
        var LvTextPass = $(".PPRM801LoginEntry.LvTextPass").val();
        var ddlRights = $(".PPRM801LoginEntry.ddlRights").val();

        var url = me.sys_id + "/" + me.id + "/fncUpdateConfirm";
        var data = {
            LvTextUserID: LvTextUserID,
            LvTextPass: LvTextPass,
            ddlRights: ddlRights,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                clsComFnc.FncMsgBox("I0002_PPRM");
                me.windowClose();
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：戻るボタンクリック（コンボボックス名）
    //'関 数 名：me.btnBack_Click
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：コンボボックス名を追加する
    //'**********************************************************************
    me.btnBack_Click = function () {
        me.windowClose2();
    };

    //'**********************************************************************
    //'処 理 名：ウィンドウを閉じます
    //'関 数 名：me.windowClose
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：ウィンドウを閉じます
    //'**********************************************************************
    me.windowClose = function () {
        me.flg = 1;
        $(".PPRM801LoginEntry.body").dialog("close");
    };

    //'**********************************************************************
    //'処 理 名：ウィンドウを閉じます
    //'関 数 名：me.windowClose2
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：ウィンドウを閉じます
    //'**********************************************************************
    me.windowClose2 = function () {
        me.flg = 2;
        $(".PPRM801LoginEntry.body").dialog("close");
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM801LoginEntry = new PPRM.PPRM801LoginEntry();
    o_PPRM_PPRM801LoginEntry.load();
    o_PPRM_PPRM.PPRM801LoginEntry = o_PPRM_PPRM801LoginEntry;
});
