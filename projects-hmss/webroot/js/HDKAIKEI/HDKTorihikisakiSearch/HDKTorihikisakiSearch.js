Namespace.register("HDKAIKEI.HDKTorihikisakiSearch");

HDKAIKEI.HDKTorihikisakiSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.id = "HDKTorihikisakiSearch";

    // ========== 変数 start ==========

    me.grid_id = "#HDKAIKEI_HDKTorihikisakiSearch_sprItyp";
    me.sys_id = "HDKAIKEI";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnHyouji_Click";
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };

    me.colModel = [
        {
            name: "TORIHIKISAKI_CD",
            label: "取引先コード",
            index: "TORIHIKISAKI_CD",
            width: 153,
            align: "left",
            sortable: false,
        },
        {
            name: "TORIHIKISAKI_NAME",
            label: "取引先名称",
            index: "TORIHIKISAKI_NAME",
            width: 428,
            align: "left",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //表示ボタン
    me.controls.push({
        id: ".HDKTorihikisakiSearch.btnView",
        type: "button",
        handle: "",
    });

    //選択ボタン
    me.controls.push({
        id: ".HDKTorihikisakiSearch.btnSelect",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".HDKTorihikisakiSearch.btnClose",
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
    $(".HDKTorihikisakiSearch.btnView").click(function () {
        me.btnView_Click();
    });
    //処理説明：選択ボタン押下時
    $(".HDKTorihikisakiSearch.btnSelect").click(function () {
        me.windowClose();
    });
    //処理説明：戻るボタン押下時
    $(".HDKTorihikisakiSearch.btnClose").click(function () {
        $("#HDKTorihikisakiSearchDialogDiv").dialog("close");
    });

    $(".HDKTorihikisakiSearch.txtTorihiki").on("focus", function () {
        //テキストエリアを全選択する
        $(this).select();
    });

    $(".HDKTorihikisakiSearch.txtTorihiki").on("blur", function () {
        me.HDKAIKEI.KinsokuMojiCheck($(this), me.clsComFnc);
    });

    $(".HDKTorihikisakiSearch.txtTorihiki").on("keydown", function (e) {
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
        me.HDKTorihikisakiSearch_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HDKTorihikisakiSearch_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HDKTorihikisakiSearch_load = function () {
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
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 653);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 282 : 312
        );
        $("#HDKAIKEI_HDKTorihikisakiSearch_sprItyp_rn").html("№");
        //KEYDOWN
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HDKTorihikisakiSearchDialogDiv").dialog("close");
            },
            onSelectRow: function (rowId) {
                $(me.grid_id + " tr#" + rowId).on("keydown", function (e) {
                    var key = e.which;
                    e.preventDefault();
                    if (key == 9 && e.shiftKey == false) {
                        $(".HDKTorihikisakiSearch.btnSelect").trigger("focus");
                    } else if (key == 9 && e.shiftKey == true) {
                        $(".HDKTorihikisakiSearch.btnView").trigger("focus");
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
                $("#HDKTorihikisakiSearchDialogDiv").dialog("close");
            },
        });

        $("#RtnCD").html("-1");
    };

    //'**********************************************************************
    //'処 理 名：表示ボタンクリック
    //'関 数 名：btnView_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：表示ボタンの処理
    //'**********************************************************************
    me.btnView_Click = function () {
        var txtTorihikiCode = $.trim(
            $(".HDKTorihikisakiSearch.txtTorihikiCode").val()
        );
        var txtTorihikiName = $.trim(
            $(".HDKTorihikisakiSearch.txtTorihikiName").val()
        );
        var txtTorihikiKana = $.trim(
            $(".HDKTorihikisakiSearch.txtTorihikiKana").val()
        );

        var data = {
            txtTorihikiCode: txtTorihikiCode,
            txtTorihikiName: txtTorihikiName,
            txtTorihikiKana: txtTorihikiKana,
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
                $(".HDKTorihikisakiSearch.btnSelect").show();
                $(".HDKTorihikisakiSearch.txtTorihiki.txtTorihikiCode").trigger(
                    "focus"
                );
                $(".HDKTorihikisakiSearch .sprItyp").css(
                    "visibility",
                    "visible"
                );
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };

    //'**********************************************************************
    //'処 理 名：取引先グリッド行選択のイベント
    //'関 数 名：windowClose
    //'戻 り 値：なし
    //'処理説明：取引先グリッド行選択の処理
    //'**********************************************************************
    me.windowClose = function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }

        //閉じる
        $("#HDKTorihikisakiSearchDialogDiv").dialog("close");
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
            $(".HDKTorihikisakiSearch .sprItyp").css("visibility", "hidden");
            return false;
        } else {
            var rowData = $(me.grid_id).jqGrid("getRowData", SelectRow);
            if (rowData && $.trim(rowData["TORIHIKISAKI_CD"]) != "") {
                //リターン値
                $("#RtnCD").html("1");
                //---取引先コード---
                $("#KensakuCD").html($.trim(rowData["TORIHIKISAKI_CD"]));
                //---取引先名---
                $("#KensakuNM").html($.trim(rowData["TORIHIKISAKI_NAME"]));
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
        $(".HDKTorihikisakiSearch.txtTorihiki.txtTorihikiCode").trigger(
            "focus"
        );
        //選択ボタンは表示されません。
        $(".HDKTorihikisakiSearch.btnSelect").hide();
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HDKAIKEI_HDKTorihikisakiSearch = new HDKAIKEI.HDKTorihikisakiSearch();
    o_HDKAIKEI_HDKTorihikisakiSearch.load();
});
