Namespace.register("HMDPS.HMDPS703SyainSearch");

HMDPS.HMDPS703SyainSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "伝票集計システム";
    me.ajax = new gdmz.common.ajax();
    me.hmdps = new HMDPS.HMDPS();

    // ========== 変数 start ==========

    me.grid_id = "#HMDPS_HMDPS703SyainSearch_sprItyp";
    me.id = "HMDPS703SyainSearch";
    me.sys_id = "HMDPS";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnHyouji_Click";
    //部署
    me.name_busyoSaki = "";
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };

    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "社員№",
            index: "SYAIN_NO",
            width: 113,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 237,
            align: "left",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //表示ボタン
    me.controls.push({
        id: ".HMDPS703SyainSearch.btnHyouji",
        type: "button",
        handle: "",
    });

    //選択ボタン
    me.controls.push({
        id: ".HMDPS703SyainSearch.btnSenntaku",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".HMDPS703SyainSearch.btnModoru",
        type: "button",
        handle: "",
    });

    //検索ボタン
    me.controls.push({
        id: ".HMDPS703SyainSearch.btnSearch",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmdps.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.hmdps.TabKeyDown(me.id);

    //Enterキーのバインド
    me.hmdps.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：表示ボタン押下時
    $(".HMDPS703SyainSearch.btnHyouji").click(function () {
        me.btnHyouji_Click();
    });
    //処理説明：選択ボタン押下時
    $(".HMDPS703SyainSearch.btnSenntaku").click(function () {
        me.gdvShainnBetuKG_RowDataBound();
    });
    //処理説明：戻るボタン押下時
    $(".HMDPS703SyainSearch.btnModoru").click(function () {
        $("#HMDPS703SyainSearchDialogDiv").dialog("close");
    });
    //処理説明：検索ボタン押下時
    $(".HMDPS703SyainSearch.btnSearch").click(function () {
        me.btnSearch_Click();
    });
    //部署フォーカス移動
    $(".HMDPS703SyainSearch.txtBusyo").blur(function () {
        me.txtBusyoCD_LostFocus();
    });
    $(".HMDPS703SyainSearch.txtShainn").on("focus", function () {
        //テキストエリアを全選択する
        $(this).select();
    });
    //禁則文字
    $(".HMDPS703SyainSearch.txtShainn").blur(function () {
        me.hmdps.KinsokuMojiCheck($(this), me.clsComFnc);
    });

    $(".HMDPS703SyainSearch.txtShainn").on("keydown", function (e) {
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
        me.HMDPS703SyainSearch_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HMDPS703SyainSearch_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HMDPS703SyainSearch_load = function () {
        //初期値設定
        $(".HMDPS703SyainSearch.txtShainnNo").trigger("focus");
        $(".HMDPS703SyainSearch.btnSenntaku").hide();

        var ratio = window.devicePixelRatio || 1;
        var height = ratio === 1.5 ? 290 : 312;
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 422);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, height);
        $("#HMDPS_HMDPS703SyainSearch_sprItyp_rn").html("№");

        //部署
        var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoMstValue";
        me.data = {};
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //部署コード
            me.name_busyoSaki = result["data"];
        };
        me.ajax.send(url, me.data, 0);

        //KEYDOWN
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (_rowId, _iRow, _iCol, _e) {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HMDPS703SyainSearchDialogDiv").dialog("close");
            },
            onSelectRow: function (rowId, _status, _e) {
                $(me.grid_id + " tr#" + rowId).on("keydown", function (e) {
                    var key = e.which;
                    e.preventDefault();
                    if (key == 9 && e.shiftKey == false) {
                        $(".HMDPS703SyainSearch.btnSenntaku").trigger("focus");
                    } else if (key == 9 && e.shiftKey == true) {
                        $(".HMDPS703SyainSearch.btnHyouji").trigger("focus");
                    }
                });
            },
        });
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function (_rowid) {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HMDPS703SyainSearchDialogDiv").dialog("close");
            },
        });
        $("#RtnCD").html("-1");
    };

    //'**********************************************************************
    //'処 理 名：表示ボタンクリック
    //'関 数 名：btnHyouji_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：表示ボタンの処理
    //'**********************************************************************
    me.btnHyouji_Click = function () {
        var txtSyainNO = $.trim($(".HMDPS703SyainSearch.txtShainnNo").val());
        var txtSyainNM = $.trim($(".HMDPS703SyainSearch.txtShainnNM").val());
        var txtSyainKN = $.trim(
            $(".HMDPS703SyainSearch.txtShainnNM_Kana").val()
        );
        var txtBusyoCD = $.trim($(".HMDPS703SyainSearch.txtBusyo").val());
        var data = {
            txtSyainNO: txtSyainNO,
            txtSyainNM: txtSyainNM,
            txtSyainKN: txtSyainKN,
            txtBusyoCD: txtBusyoCD,
        };

        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (returnFLG == "nodata") {
                //初期値設定
                $(".HMDPS703SyainSearch.txtShainnNo").trigger("focus");
                $(".HMDPS703SyainSearch.btnSenntaku").hide();
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                //選択ボタンが表示されます。
                $(".HMDPS703SyainSearch.btnSenntaku").show();
                $(".HMDPS703SyainSearch.txtShainnNo").trigger("focus");
                $(".HMDPS703SyainSearch .tableItyp").css(
                    "visibility",
                    "visible"
                );
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };

    //'**********************************************************************
    //'処 理 名：社員グリッド行選択のイベント
    //'関 数 名：gdvShainnBetuKG_RowDataBound
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：社員グリッド行選択の処理
    //'**********************************************************************
    me.gdvShainnBetuKG_RowDataBound = function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }
        //閉じる
        $("#HMDPS703SyainSearchDialogDiv").dialog("close");
    };
    //**********************************************************************
    //処 理 名：検索ボタンクリック
    //関 数 名：btnSearch_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.btnSearch_Click = function () {
        var frmId = "HMDPS702BusyoSearch";
        var dialogId = "HMDPS702BusyoSearchDialogDiv";
        var $rootDiv = $(".HMDPS703SyainSearch.HMDPS-content");
        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }
        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnBusyoCD").insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", "BusyoCD").insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", "BusyoNM").insertAfter($rootDiv).hide();

        var $RtnCD = $rootDiv.parent().find("#RtnBusyoCD");
        var $BusyoCD = $rootDiv.parent().find("#BusyoCD");
        var $BusyoNM = $rootDiv.parent().find("#BusyoNM");
        $(".HMDPS703SyainSearch.txtShainnNo").trigger("focus");
        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if ($RtnCD.html() == 1) {
                    $(".HMDPS703SyainSearch.txtBusyo").val($BusyoCD.html());
                    $(".HMDPS703SyainSearch.lblBusyo").val($BusyoNM.html());
                }
                $RtnCD.remove();
                $BusyoCD.remove();
                $BusyoNM.remove();
                $("#" + dialogId).remove();
                setTimeout(function () {
                    $(".HMDPS703SyainSearch.btnSearch").trigger("focus");
                }, 100);
                $(me.grid_id).jqGrid("clearGridData");
                $(".HMDPS703SyainSearch .tableItyp").css(
                    "visibility",
                    "hidden"
                );
            }

            $("#" + dialogId).hide();
            $("#" + dialogId).append(result);
            o_HMDPS_HMDPS.HMDPS703SyainSearch.HMDPS702BusyoSearch.before_close =
                before_close;
        };
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
            return false;
        } else {
            var rowData = $(me.grid_id).jqGrid("getRowData", SelectRow);
            if (rowData && $.trim(rowData["SYAIN_NO"]) != "") {
                //リターン値
                $("#RtnCD").html("1");
                //社員No
                $("#SyainCD").html($.trim(rowData["SYAIN_NO"]));
                //社員名
                $("#SyainNM").html($.trim(rowData["SYAIN_NM"]));
            } else {
                return false;
            }
        }

        return true;
    };
    /*
     '**********************************************************************
     '処 理 名：部署名取得
     '関 数 名：txtBusyoCD_LostFocus
     '引    数：e(現在選択されているセルオブジェクト)
     '戻 り 値 ：無し
     '処理説明 ：フォーカス移動時に部署名を取得する
     '**********************************************************************
     */
    me.txtBusyoCD_LostFocus = function (_e) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(
            $.trim($(".HMDPS703SyainSearch.txtBusyo").val())
        );
        if (me.name_busyoSaki) {
            var foundNM_array = me.name_busyoSaki.filter(function (element) {
                return element["BUSYO_CD"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".HMDPS703SyainSearch.lblBusyo").val(
            foundNM ? foundNM["BUSYO_NM"] : ""
        );
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMDPS_HMDPS703SyainSearch = new HMDPS.HMDPS703SyainSearch();
    o_HMDPS_HMDPS703SyainSearch.load();
    o_HMDPS_HMDPS.HMDPS703SyainSearch = o_HMDPS_HMDPS703SyainSearch;
});
