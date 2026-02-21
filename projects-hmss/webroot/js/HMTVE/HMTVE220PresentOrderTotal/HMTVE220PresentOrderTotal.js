/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE220PresentOrderTotal");

HMTVE.HMTVE220PresentOrderTotal = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE220PresentOrderTotal";
    me.hmtve = new HMTVE.HMTVE();
    me.grid_id = "#HMTVE220PresentOrderTotal_tblSubMain";
    me.g_url = me.sys_id + "/" + me.id + "/getTenpo";
    me.grid_load = true;
    me.hidTermStart = "";
    me.hidTermEnd = "";

    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "店舗コード",
            index: "BUSYO_CD",
            width: 96,
            align: "center",
            sortable: false,
        },
        {
            name: "BUSYO_RYKNM",
            label: "店舗名",
            index: "BUSYO_RYKNM",
            width: 275,
            align: "left",
            sortable: false,
        },
    ];

    me.option = {
        caption: "",
        rownumbers: false,
        rowNum: 0,
        multiselect: false,
        colModel: me.colModel,
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE220PresentOrderTotal.Button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown();

    //Tabキーのバインド
    me.hmtve.TabKeyDown();

    //Enterキーのバインド
    me.hmtve.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // Excel出力ボタン
    $(".HMTVE220PresentOrderTotal.btnPutout").click(function () {
        // 入力チェックを行う
        if (
            $.trim($(".HMTVE220PresentOrderTotal.lblNumberNum").text()) == "0"
        ) {
            me.clsComFnc.FncMsgBox("W0024");
            return;
        }

        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.btnPutout_Click();
        };
        var dateStr =
            $(".HMTVE220PresentOrderTotal.txtExhibitTimeStart").val() +
            "～" +
            $(".HMTVE220PresentOrderTotal.txtExhibitTimeEnd").val();
        me.clsComFnc.FncMsgBox(
            "QY999",
            dateStr + "のEXCELデータを出力します。よろしいですか？"
        );
    });

    // 表示ボタン
    $(".HMTVE220PresentOrderTotal.btnShow").click(function () {
        me.btnShow_Click();
    });
    //展示会検索ボタン
    $(".HMTVE220PresentOrderTotal.btnExhibitSearch").click(function () {
        me.btnExhibitSearch_Click();
    });

    // ロック解除
    $(".HMTVE220PresentOrderTotal.btnRemove").click(function () {
        if (
            $(".HMTVE220PresentOrderTotal.txtExhibitTimeStart").val() == "" ||
            $(".HMTVE220PresentOrderTotal.txtExhibitTimeEnd").val() == ""
        ) {
            me.clsComFnc.FncMsgBox("W9999", "期間を指定してください。");
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.btnRemove_Click();
        };
        var dateStr =
            $(".HMTVE220PresentOrderTotal.txtExhibitTimeStart").val() +
            "～" +
            $(".HMTVE220PresentOrderTotal.txtExhibitTimeEnd").val();
        me.clsComFnc.FncMsgBox(
            "QY999",
            dateStr +
                "の成約プレゼントデータのロックを解除します。よろしいですか？"
        );
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：init_control
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();

        //プロシージャ:画面初期化
        me.Page_Load();
    };

    // '**********************************************************************
    // '処 理 名：ページロード
    // '関 数 名：Page_Load
    // '戻 り 値：なし
    // '処理説明：ページ初期化
    // '**********************************************************************
    me.Page_Load = function () {
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 400);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 270 : 312);

        // 表示設定
        $(".HMTVE220PresentOrderTotal.tblView").hide();

        $(".HMTVE220PresentOrderTotal.lblNumberNum").hide();
        $(".HMTVE220PresentOrderTotal.lblNumber").hide();
        $(".HMTVE220PresentOrderTotal.btnRemove").hide();
        $(".HMTVE220PresentOrderTotal.btnPutout").hide();
        $(".HMTVE220PresentOrderTotal.HMS-button-pane.bottom-btn").hide();

        // 画面項目をクリアする
        $(".HMTVE220PresentOrderTotal.txtExhibitTimeStart").val("");
        $(".HMTVE220PresentOrderTotal.txtExhibitTimeEnd").val("");

        // 'フォーカス移動
        $(".HMTVE220PresentOrderTotal.btnExhibitSearch").trigger("focus");
    };
    // '**********************************************************************
    // '処 理 名：展示会検索ボタンのイベント
    // '関 数 名：btnExhibitSearch_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：展示会期間の表示
    // '**********************************************************************
    me.btnExhibitSearch_Click = function () {
        var frmId = "HMTVE080ExhibitionSearch";
        var dialogdiv = "HMTVE220PresentOrderTotalDialogDiv";
        var $rootDiv = $(".HMTVE220PresentOrderTotal.HMTVE-content");
        if ($("#" + dialogdiv).length > 0) {
            $("#" + dialogdiv).remove();
        }
        $("<div></div>").attr("id", dialogdiv).insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "lblETStart")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "lblETEnd")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "RtnCD")
            .insertAfter($rootDiv);

        var RtnCD = $rootDiv.parent().find("#RtnCD");

        var hidStart = $rootDiv.parent().find("#lblETStart");
        var hidEnd = $rootDiv.parent().find("#lblETEnd");

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if (RtnCD.html() == 1) {
                    $(".HMTVE220PresentOrderTotal.txtExhibitTimeStart").val(
                        hidStart.html()
                    );
                    $(".HMTVE220PresentOrderTotal.txtExhibitTimeEnd").val(
                        hidEnd.html()
                    );
                    $(".HMTVE220PresentOrderTotal.tblView").hide();
                    $(".HMTVE220PresentOrderTotal.lblNumberNum").hide();
                    $(".HMTVE220PresentOrderTotal.lblNumber").hide();
                    $(".HMTVE220PresentOrderTotal.btnRemove").hide();
                    $(".HMTVE220PresentOrderTotal.btnPutout").hide();
                    $(
                        ".HMTVE220PresentOrderTotal.HMS-button-pane.bottom-btn"
                    ).hide();
                }
                RtnCD.remove();
                hidStart.remove();
                hidEnd.remove();
                $("#" + dialogdiv).remove();
                $(".HMTVE220PresentOrderTotal.txtExhibitTimeStart").trigger(
                    "focus"
                );
            }

            $("#" + dialogdiv).hide();
            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE220PresentOrderTotal.HMTVE080ExhibitionSearch.before_close =
                before_close;
        };
    };

    me.complete_fun = function (_bErrorFlag, result) {
        if (result["error"]) {
            me.clsComFnc.FncMsgBox("E9999", result["error"]);
            return;
        }
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 400);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 270 : 312);

        $(me.grid_id).jqGrid("setSelection", 0);
        //部署データの件数を表示する
        $(".HMTVE220PresentOrderTotal.lblNumberNum").text(result["records"]);

        //画面制御を行う
        $(".HMTVE220PresentOrderTotal.tblView").show();
        $(".HMTVE220PresentOrderTotal.lblNumberNum").show();
        $(".HMTVE220PresentOrderTotal.lblNumber").show();
        $(".HMTVE220PresentOrderTotal.btnRemove").show();
        $(".HMTVE220PresentOrderTotal.btnPutout").show();
        $(".HMTVE220PresentOrderTotal.HMS-button-pane.bottom-btn").show();
    };

    // '**********************************************************************
    // '処 理 名：表示ボタンのイベント
    // '関 数 名：btnShow_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：部署テーブルの表示
    // '**********************************************************************
    me.btnShow_Click = function () {
        //画面項目のクリア処理
        $(".HMTVE220PresentOrderTotal.tblView").hide();
        $(".HMTVE220PresentOrderTotal.lblNumberNum").hide();
        $(".HMTVE220PresentOrderTotal.lblNumber").hide();
        $(".HMTVE220PresentOrderTotal.btnRemove").hide();
        $(".HMTVE220PresentOrderTotal.btnPutout").hide();
        $(".HMTVE220PresentOrderTotal.HMS-button-pane.bottom-btn").hide();

        var data = {
            STARTDT: $.trim(
                $(".HMTVE220PresentOrderTotal.txtExhibitTimeStart").val()
            ).replace(/\//g, ""),
        };

        if (me.grid_load) {
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                me.g_url,
                me.colModel,
                "",
                "",
                me.option,
                data,
                me.complete_fun
            );
            $(me.grid_id).jqGrid("bindKeys");
            me.grid_load = false;
        } else {
            $(me.grid_id).jqGrid("clearGridData");
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                data,
                me.complete_fun
            );
        }
    };
    // '**********************************************************************
    // '処 理 名：Excel出力ボタン
    // '関 数 名：btnPutout_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：展示会成約_集計Excel出力
    // '**********************************************************************
    me.btnPutout_Click = function () {
        var data = {
            STARTDT: $.trim(
                $(".HMTVE220PresentOrderTotal.txtExhibitTimeStart").val()
            ).replace(/\//g, ""),
            ENDDT: $.trim(
                $(".HMTVE220PresentOrderTotal.txtExhibitTimeEnd").val()
            ).replace(/\//g, ""),
        };
        //宣材注文確定データに確定ﾌﾗｸﾞ１で更新する
        var url = me.sys_id + "/" + me.id + "/btnPutoutClick";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                window.location.href = result["data"];
                //未出力データが存在しないかチェックする
                me.checkInput(result);
            } else {
                if (result["error"] == "W0006") {
                    me.clsComFnc.FncMsgBox("W0030");
                } else if (result["error"] == "W0003") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "テンプレートファイルが存在しません。"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.checkInput = function (result) {
        //未出力データを抽出する
        var objdr1 = result["dataCheck"];
        //メッセージを表示する
        //抽出データ("CNT")=0の場合
        if (objdr1[0]["CNT"] == 0) {
            me.clsComFnc.FncMsgBox("I0018");
        } else if (objdr1[0]["CNT"] > 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "未出力データが" +
                    objdr1[0]["CNT"] +
                    "件存在します。再度EXCEL出力を行ってください！"
            );
        }
    };
    // '**********************************************************************
    // '処 理 名：ロック解除クリック
    // '関 数 名：btnRemove_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：ロック解除
    // '**********************************************************************
    me.btnRemove_Click = function () {
        var data = {
            STARTDT: $.trim(
                $(".HMTVE220PresentOrderTotal.txtExhibitTimeStart").val()
            ).replace(/\//g, ""),
        };
        //ロック解除を行う
        //宣材注文確定データに確定ﾌﾗｸﾞ１で更新する
        var url = me.sys_id + "/" + me.id + "/btnRemoveClick";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                if (result["number_of_rows"] == 0) {
                    me.clsComFnc.FncMsgBox("W0024");
                } else {
                    me.clsComFnc.FncMsgBox(
                        "I9999",
                        "ロックの解除を行いました。"
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE220PresentOrderTotal =
        new HMTVE.HMTVE220PresentOrderTotal();
    o_HMTVE_HMTVE.HMTVE220PresentOrderTotal = o_HMTVE_HMTVE220PresentOrderTotal;
    o_HMTVE_HMTVE220PresentOrderTotal.load();
});
