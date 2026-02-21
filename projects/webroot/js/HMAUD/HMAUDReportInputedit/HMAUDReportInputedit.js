/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20240530           機能追加      メール通知機能にて、クールと領域名も一緒に出力する    YIN
 * 20240612           機能追加      報告書入力で 差戻を実行する際、差戻先を ユーザーが選択可能にしてほしい    CI
 * 20250403           機能追加       		     202504_内部統制_要望.xlsx        CI
 * 20251016           機能追加      202510_内部統制システム_仕様変更対応.xlsx         YIN
 * 20260126     「社長」欄を１つ廃止     202601_内部統制_変更要望.xlsx               YIN
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("HMAUD.HMAUDReportInputedit");

HMAUD.HMAUDReportInputedit = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDReportInputedit";
    me.HMAUD = new HMAUD.HMAUD();

    // ========== 変数 start ==========

    me.id = "HMAUDReportInputedit";
    me.sys_id = "HMAUD";
    me.flag = "";
    me.check_id = "";
    me.report_id = "";
    me.territory = "";
    me.kyoten = "";
    // 20240530 YIN INS S
    me.cour = "";
    // 20240530 YIN INS E
    // 20240613 CI INS S
    me.pageflag = "";
    // 20240613 CI INS E
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDReportInputedit.button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // 登録
    $(".HMAUDReportInputedit.btnOK").click(function () {
        me.btnOK_Click();
    });

    // ichilan
    $(".HMAUDReportInputedit.btnClose").click(function () {
        $("#RtnCD").html("1");
        $(".HMAUDReportInputedit.HMAUDReportInputeditDialog").dialog("close");
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
        // 20240613 CI UPD S
        me.flag = $("#flag").html();
        me.pageflag = $("#pageflag").html();
        if (me.pageflag == "0") {
            $(".HMAUDReportInputedit.HMAUDReportInputeditDialog").dialog({
                autoOpen: false,
                width: 600,
                height: 345,
                modal: true,
                title: "差戻先選択",
                open: function () {},
                close: function () {
                    $("#RtnCD").html("1");
                    me.before_close();
                    $(
                        ".HMAUDReportInputedit.HMAUDReportInputeditDialog",
                    ).remove();
                },
            });
            $(".HMAUDReportInputedit.btnOK").text("差し戻す");
            var returnFLG = "";
            if (me.flag == "5") {
                returnFLG = "94";
            }
            if (me.flag == "7") {
                returnFLG = "95";
            }
            if (me.flag == "9") {
                returnFLG = "96";
            }
            if (me.flag == "11") {
                returnFLG = "97";
            }
            if (me.flag == "13") {
                returnFLG = "98";
            }
            //20250403 CI UPD S
            if (me.flag == "15") {
                returnFLG = "99";
            }
            for (var i = 99; i >= 95; i--) {
                //20250403 CI UPD E
                if (i > parseInt(returnFLG)) {
                    $(".HMAUDReportInputedit.return_" + i.toString()).prop(
                        "disabled",
                        true,
                    );
                }
            }
            return_flag = "";
        } else {
            $(".HMAUDReportInputedit.HMAUDReportInputeditDialog").dialog({
                autoOpen: false,
                width: 600,
                height: 180,
                modal: true,
                title: "コメント入力",
                open: function () {},
                close: function () {
                    $("#RtnCD").html("1");
                    me.before_close();
                    $(
                        ".HMAUDReportInputedit.HMAUDReportInputeditDialog",
                    ).remove();
                },
            });
            $(".HMAUDReportInputedit.return").hide();
        }
        me.before_close = function () {};

        $(".HMAUDReportInputedit.HMAUDReportInputeditDialog").dialog("open");
        // 20240613 CI UPD E
        me.check_id = $("#check_id").html();
        me.report_id = $("#report_id").html();
        me.territory = $("#territory").html();
        me.kyoten = $("#kyoten").html();
        me.skip = $("#skip").html();
        // 20240530 YIN INS S
        me.cour = $("#cour").html();
        // 20240530 YIN INS E
        // 20251016 YIN INS S
        if (me.cour > 18) {
            $(".HMAUDReportInputedit.return_98_display").hide();
        }
        // 20251016 YIN INS E
        if (me.cour >= 20) {
            $(".HMAUDReportInputedit.return_99_display").hide();
        }
        //画面初期化
        //画面項目をクリアする
        $(".HMAUDReportInputedit.txtComment").val($("#userid").html());
    };

    /*
	 '************************************************************************
	 '処 理 名：OKボタンのイベント
	 '関 数 名：btnOK_Click
	 '引    数：なし
	 '戻 り 値 ：なし
	 '処理説明 ：ﾛｸﾞｲﾝ情報の登録処理
	 '************************************************************************
	 */
    me.btnOK_Click = function () {
        // 20240613 CI INS S
        var value = $('input[name="return"]:checked').val();
        var return_flag = "";
        if (me.pageflag == "0") {
            return_flag = value;
        }
        if (me.pageflag == "2") {
            return_flag = "91";
        }
        // 20240613 CI INS E
        if ($(".HMAUDReportInputedit.txtComment").val() == "") {
            me.clsComFnc.ObjFocus = $(".HMAUDReportInputedit.txtComment");
            me.clsComFnc.FncMsgBox("W9999", "コメントを入力してください。");
            return false;
        }
        var url = me.sys_id + "/" + me.id + "/" + "btnOK_Click";
        var data = {
            territory: me.territory,
            kyoten: me.kyoten,
            check_id: me.check_id,
            report_id: me.report_id,
            flag: me.flag,
            skip: me.skip,
            // 20240530 YIN INS S
            cour: me.cour,
            // 20240530 YIN INS E
            comment: $(".HMAUDReportInputedit.txtComment").val(),
            // 20240613 CI INS S
            return_flag: return_flag,
            // 20240613 CI INS E
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                if (result["error"] == "W9999") {
                    me.clsComFnc.MsgBoxBtnFnc.OK = me.backtopage;
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.backtopage;
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "登録は完了しましたが、メール送信できませんでした。管理者に連絡してください",
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            } else {
                $("#RtnCD").html("1");
                $("#lblComment").html(
                    $(".HMAUDReportInputedit.txtComment").val(),
                );
                $(".HMAUDReportInputedit.HMAUDReportInputeditDialog").dialog(
                    "close",
                );
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.backtopage = function () {
        $(".HMAUDReportInputedit.HMAUDReportInputeditDialog").dialog("close");
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDReportInputedit = new HMAUD.HMAUDReportInputedit();
    o_HMAUD_HMAUD.HMAUDReportInput.HMAUDReportInputedit =
        o_HMAUD_HMAUDReportInputedit;
    o_HMAUD_HMAUDReportInputedit.load();
});
