/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE390HDTCOMPANYSEARCH");

HMTVE.HMTVE390HDTCOMPANYSEARCH = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.HMTVE = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();

    me.sys_id = "HMTVE";
    me.id = "HMTVE390HDTCOMPANYSEARCH";
    me.grid_id = "#HMTVE390HDTCOMPANYSEARCH_sprList";
    me.pager = "#HMTVE390HDTCOMPANYSEARCH_pager";
    me.sidx = "";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";

    me.option = {
        pagerpos: "center",
        viewrecords: false,
        multiselect: false,
        caption: "",
        rowNum: 10,
        rowList: [10, 20, 30],
        rownumbers: false,
        scroll: false,
        autowidth: true,
        pager: me.pager,
    };

    me.colModel = [
        {
            label: "会社コード",
            width: 280,
            align: "left",
            name: "COMPANY_CD",
            index: "COMPANY_CD",
            sortable: false,
        },
        {
            label: "会社名",
            width: 365,
            align: "left",
            name: "COMPANY_NM",
            index: "COMPANY_NM",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE390HDTCOMPANYSEARCH.btnView",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE390HDTCOMPANYSEARCH.btnClose",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE390HDTCOMPANYSEARCH.btnSel",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMTVE.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HMTVE.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HMTVE.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //表示ボタンクリック
    $(".HMTVE390HDTCOMPANYSEARCH.btnView").click(function () {
        me.btnView_Click();
    });
    //選択ボタンクリック
    $(".HMTVE390HDTCOMPANYSEARCH.btnSel").click(function () {
        me.close2();
    });
    //閉じるボタンクリック
    $(".HMTVE390HDTCOMPANYSEARCH.btnClose").click(function () {
        $("#HMTVE390HDTCOMPANYSEARCHDialogDiv").dialog("close");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        try {
            base_init_control();

            //ページロード
            me.Page_Load();
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：ページロード
	 '関 数 名：Page_Load
	 '戻 り 値：なし
	 '処理説明：ページ初期化
	 '**********************************************************************
	 */
    me.complete_fun = function (bErrorFlag, data) {
        if (data["error"] && data["error"] != "") {
            me.clsComFnc.FncMsgBox("E9999", data["error"]);
            return;
        } else if (bErrorFlag == "nodata") {
            me.clsComFnc.FncMsgBox("W0024");
        } else {
            //データの取得
            me.DataGetSQL();

            $(".HMTVE390HDTCOMPANYSEARCH.btnSelDiv").hide();
            $(".HMTVE390HDTCOMPANYSEARCH.btnSel").hide();
        }
    };
    me.Page_Load = function () {
        try {
            //初期設定処理
            $("#HMTVE390HDTCOMPANYSEARCHDialogDiv").dialog({
                autoOpen: false,
                modal: true,
                title: "紹介者・窓口会社　検索",
                height: me.ratio === 1.5 ? 420 : 530,
                width: 750,
                resizable: true,
                open: function () {
                    //画面項目№2.会社コード、№3.会社名をクリアする
                    me.Pageclear();

                    $(".HMTVE390HDTCOMPANYSEARCH.tblDetail").hide();
                    $(".HMTVE390HDTCOMPANYSEARCH.btnSelDiv").hide();
                    $(".HMTVE390HDTCOMPANYSEARCH.btnSel").hide();
                },
                close: function () {
                    me.before_close();

                    $("#HMTVE390HDTCOMPANYSEARCHDialogDiv").remove();
                },
            });

            $("#HMTVE390HDTCOMPANYSEARCHDialogDiv").dialog("open");

            gdmz.common.jqgrid.showWithMesgScroll(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                me.sidx,
                me.option,
                {},
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id, 688);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id,
                me.ratio === 1.5 ? 210 : 260
            );
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function () {
                    $(".HMTVE390HDTCOMPANYSEARCH.txtComCode").css(
                        me.clsComFnc.GC_COLOR_NORMAL
                    );
                    $(".HMTVE390HDTCOMPANYSEARCH.txtComName").css(
                        me.clsComFnc.GC_COLOR_NORMAL
                    );

                    $(".HMTVE390HDTCOMPANYSEARCH.btnSelDiv").show();
                    $(".HMTVE390HDTCOMPANYSEARCH.btnSel").show();
                },
                ondblClickRow: function () {
                    //選択値の設定
                    me.close2();
                },
                onPaging: function () {
                    $(".HMTVE390HDTCOMPANYSEARCH.btnSelDiv").hide();
                    $(".HMTVE390HDTCOMPANYSEARCH.btnSel").hide();
                },
            });
            $(me.grid_id).jqGrid("bindKeys", {
                onEnter: function () {
                    //選択値の設定
                    me.close2();
                },
            });
        } catch (ex) {
            console.log(ex);
        }
    };
    me.Pageclear = function () {
        try {
            $(".HMTVE390HDTCOMPANYSEARCH.txtComCode").val("");
            $(".HMTVE390HDTCOMPANYSEARCH.txtComName").val("");
        } catch (ex) {
            console.log(ex);
        }
    };
    me.DataGetSQL = function () {
        try {
            $(".HMTVE390HDTCOMPANYSEARCH.tblDetail").hide();

            //画面項目No2(会社コード)にフォーカス移動
            $(".HMTVE390HDTCOMPANYSEARCH.txtComCode").trigger("focus");
            $(".HMTVE390HDTCOMPANYSEARCH.tblDetail").show();
        } catch (ex) {
            console.log(ex);
        }
    };
    me.DataCheck = function () {
        try {
            var objRegEx_NG1 = /[\'\""]/g;
            var objRegEx_NG2 = /[\,\""]/g;
            //会社コード <> "" の場合
            if ($.trim($(".HMTVE390HDTCOMPANYSEARCH.txtComCode").val()) != "") {
                if (
                    $.trim(
                        $(".HMTVE390HDTCOMPANYSEARCH.txtComCode").val()
                    ).match(objRegEx_NG1)
                ) {
                    $(".HMTVE390HDTCOMPANYSEARCH.txtComCode").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE390HDTCOMPANYSEARCH.txtComCode"
                    );
                    me.clsComFnc.FncMsgBox("E0013", "会社コード");
                    return false;
                } else if (
                    $.trim(
                        $(".HMTVE390HDTCOMPANYSEARCH.txtComCode").val()
                    ).match(objRegEx_NG2)
                ) {
                    $(".HMTVE390HDTCOMPANYSEARCH.txtComCode").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE390HDTCOMPANYSEARCH.txtComCode"
                    );
                    me.clsComFnc.FncMsgBox("E0013", "会社コード");
                    return false;
                }
            }
            //会社名 <> "" の場合
            if ($.trim($(".HMTVE390HDTCOMPANYSEARCH.txtComName").val()) != "") {
                if (
                    $.trim(
                        $(".HMTVE390HDTCOMPANYSEARCH.txtComName").val()
                    ).match(objRegEx_NG1)
                ) {
                    $(".HMTVE390HDTCOMPANYSEARCH.txtComName").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE390HDTCOMPANYSEARCH.txtComName"
                    );
                    me.clsComFnc.FncMsgBox("E0013", "会社名");
                    return false;
                } else if (
                    $.trim(
                        $(".HMTVE390HDTCOMPANYSEARCH.txtComName").val()
                    ).match(objRegEx_NG2)
                ) {
                    $(".HMTVE390HDTCOMPANYSEARCH.txtComName").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE390HDTCOMPANYSEARCH.txtComName"
                    );
                    me.clsComFnc.FncMsgBox("E0013", "会社名");
                    return false;
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：表示ボタンのイベント
	 '関 数 名：btnView_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：展示会データを取得する
	 '**********************************************************************
	 */
    me.btnView_Click = function () {
        try {
            $(".HMTVE390HDTCOMPANYSEARCH.tblDetail").hide();
            $(".HMTVE390HDTCOMPANYSEARCH.btnSelDiv").hide();
            $(".HMTVE390HDTCOMPANYSEARCH.btnSel").hide();

            $(".HMTVE390HDTCOMPANYSEARCH.txtComCode").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
            $(".HMTVE390HDTCOMPANYSEARCH.txtComName").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
            //入力チェック
            if (me.DataCheck()) {
                var data = {
                    txtComCode: $.trim(
                        $(".HMTVE390HDTCOMPANYSEARCH.txtComCode").val()
                    ),
                    txtComName: $.trim(
                        $(".HMTVE390HDTCOMPANYSEARCH.txtComName").val()
                    ),
                };
                //データの取得
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id,
                    data,
                    me.complete_fun
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    me.close2 = function () {
        try {
            var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
            if (rowid == null) {
                me.clsComFnc.FncMsgBox("W9999", "行が選択されていません。");
            } else {
                var rowData = $(me.grid_id).jqGrid("getRowData", rowid);
                //会社名
                $("#hidIntroPeople").html(rowData["COMPANY_NM"]);
                $("#FLAG").html("1");
                //閉じる
                $("#HMTVE390HDTCOMPANYSEARCHDialogDiv").dialog("close");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    me.before_close = function () {};
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE390HDTCOMPANYSEARCH = new HMTVE.HMTVE390HDTCOMPANYSEARCH();
    o_HMTVE_HMTVE390HDTCOMPANYSEARCH.load();

    o_HMTVE_HMTVE.HMTVE280IntroduceConfirmEntry.HMTVE390HDTCOMPANYSEARCH =
        o_HMTVE_HMTVE390HDTCOMPANYSEARCH;
});
