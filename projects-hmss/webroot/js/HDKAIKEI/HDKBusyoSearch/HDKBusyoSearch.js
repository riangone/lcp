Namespace.register("HDKAIKEI.HDKBusyoSearch");

HDKAIKEI.HDKBusyoSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.id = "HDKBusyoSearch";

    // ========== 変数 start ==========

    me.grid_id = "#HDKAIKEI_HDKBusyoSearch_sprItyp";
    me.sys_id = "HDKAIKEI";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnViewClick";
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };

    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 163,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: me.ratio === 1.5 ? 400 : 418,
            align: "left",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //表示ボタン
    me.controls.push({
        id: ".HDKBusyoSearch.btnView",
        type: "button",
        handle: "",
    });

    //選択ボタン
    me.controls.push({
        id: ".HDKBusyoSearch.btnSelect",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".HDKBusyoSearch.btnClose",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：表示ボタン押下時
    $(".HDKBusyoSearch.btnView").click(function () {
        me.btnView_Click();
    });
    //処理説明：選択ボタン押下時
    $(".HDKBusyoSearch.btnSelect").click(function () {
        me.windowClose();
    });
    //処理説明：戻るボタン押下時
    $(".HDKBusyoSearch.btnClose").click(function () {
        //閉じる
        if ($("#syain").val() == "syain") {
            $(".HDKBusyoSearch.body").dialog("close");
        } else {
            $("#HDKBusyoSearchDialogDiv").dialog("close");
        }
    });

    $(".HDKBusyoSearch.txtDeploy").on("focus", function () {
        //テキストエリアを全選択する
        $(this).select();
    });

    $(".HDKBusyoSearch.txtDeploy").on("blur", function () {
        me.HDKAIKEI.KinsokuMojiCheck($(this), me.clsComFnc);
    });

    $(".HDKBusyoSearch.txtDeploy").on("keydown", function (e) {
        var key = e.which;
        if (key == 13 || key == 9) {
            e.preventDefault();

            $(".ui-dialog-buttons").find(".ui-button").trigger("focus");
        }
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
        me.HDKBusyoSearch_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HDKBusyoSearch_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HDKBusyoSearch_load = function () {
        if ($("#syain").val() == "syain") {
            $(".HDKBusyoSearch.body").dialog({
                autoOpen: false,
                height: me.ratio === 1.5 ? 530 : 630,
                width: me.ratio === 1.5 ? 700 : 720,
                modal: true,
                title: "部署マスタ検索",
                open: function () {},
                close: function () {
                    me.before_close();
                    $(".HDKBusyoSearch.body").remove();
                },
            });

            $(".HDKBusyoSearch.body").dialog("open");
        }

        //初期設定処理
        me.SubFirstSet();
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 643 : 653
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 242 : 312
        );
        $("#HDKAIKEI_HDKBusyoSearch_sprItyp_rn").html("№");
        //KEYDOWN
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                if ($("#syain").val() == "syain") {
                    $(".HDKBusyoSearch.body").dialog("close");
                } else {
                    $("#HDKBusyoSearchDialogDiv").dialog("close");
                }
            },
            onSelectRow: function (rowId) {
                $(me.grid_id + " tr#" + rowId).on("keydown", function (e) {
                    var key = e.which;
                    e.preventDefault();
                    if (key == 9 && e.shiftKey == false) {
                        $(".HDKBusyoSearch.btnSelect").trigger("focus");
                    } else if (key == 9 && e.shiftKey == true) {
                        $(".HDKBusyoSearch.btnView").trigger("focus");
                    }
                });
            },
        });
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                if ($("#syain").val() == "syain") {
                    $(".HDKBusyoSearch.body").dialog("close");
                } else {
                    $("#HDKBusyoSearchDialogDiv").dialog("close");
                }
            },
        });

        $("#RtnBusyoCD").html("-1");
    };
    me.before_close = function () {};

    //'**********************************************************************
    //'処 理 名：表示ボタンクリック
    //'関 数 名：btnView_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：表示ボタンの処理
    //'**********************************************************************
    me.btnView_Click = function () {
        var txtDeployCode = $.trim($(".HDKBusyoSearch.txtDeployCode").val());
        var txtdeployName = $.trim($(".HDKBusyoSearch.txtdeployName").val());
        var txtdeployKN = $.trim($(".HDKBusyoSearch.txtdeployKN").val());
        var rdo = $.trim($("input[name='HDKBusyoSearch_radio']:checked").val());

        var data = {
            txtDeployCode: txtDeployCode,
            txtdeployName: txtdeployName,
            txtdeployKN: txtdeployKN,
            rdo: rdo,
        };

        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            if (returnFLG == "nodata") {
                me.SubFirstSet();
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                //選択ボタンが表示されます。
                $(".HDKBusyoSearch.btnSelect").show();
                $(".HDKBusyoSearch.txtDeploy.txtDeployCode").trigger("focus");
                $(".HDKBusyoSearch .sprItyp").css("visibility", "visible");
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };

    //'**********************************************************************
    //'処 理 名：部署グリッド行選択のイベント
    //'関 数 名：windowClose
    //'戻 り 値：なし
    //'処理説明：部署グリッド行選択の処理
    //'**********************************************************************
    me.windowClose = function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }

        //閉じる
        if ($("#syain").val() == "syain") {
            $(".HDKBusyoSearch.body").dialog("close");
        } else {
            $("#HDKBusyoSearchDialogDiv").dialog("close");
        }
    };

    //**********************************************************************
    //処 理 名：選択データの設定
    //関 数 名：FncSetRtnData
    //引    数：無し
    //戻 り 値：True ：正常
    //       　False：異常
    //処理説明：選択したデータを構造体に設定する。
    //**********************************************************************
    me.FncSetRtnData = function () {
        var SelectRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (SelectRow == null) {
            me.clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
            $(".HDKBusyoSearch .sprItyp").css("visibility", "hidden");
            return false;
        } else {
            var rowData = $(me.grid_id).jqGrid("getRowData", SelectRow);
            if (rowData && $.trim(rowData["BUSYO_CD"]) != "") {
                //リターン値
                $("#RtnBusyoCD").html("1");
                //---部署コード---
                $("#BusyoCD").html($.trim(rowData["BUSYO_CD"]));
                //---部署名---
                $("#BusyoNM").html($.trim(rowData["BUSYO_NM"]));
            } else {
                return false;
            }
        }

        return true;
    };

    //**********************************************************************
    //処 理 名：初期設定処理
    //関 数 名：SubFirstSet
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定処理を行う。
    //**********************************************************************
    me.SubFirstSet = function () {
        //フォーカスの設定
        $(".HDKBusyoSearch.txtDeploy.txtDeployCode").trigger("focus");
        //選択ボタンは表示されません。
        $(".HDKBusyoSearch.btnSelect").hide();
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HDKAIKEI_HDKBusyoSearch = new HDKAIKEI.HDKBusyoSearch();
    if ($("#syain").val() == "syain") {
        o_HDKAIKEI_HDKAIKEI.HDKSyainSearch.HDKBusyoSearch =
            o_HDKAIKEI_HDKBusyoSearch;
    }
    o_HDKAIKEI_HDKBusyoSearch.load();
});
