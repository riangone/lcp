Namespace.register("HDKAIKEI.HDKSyainSearch");

HDKAIKEI.HDKSyainSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    // ========== 変数 start ==========

    me.grid_id = "#HDKAIKEI_HDKSyainSearch_sprItyp";
    me.id = "HDKSyainSearch";
    me.sys_id = "HDKAIKEI";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnHyoujiClick";
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
            width: 148,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: me.ratio === 1.5 ? 410 : 422,
            align: "left",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //表示ボタン
    me.controls.push({
        id: ".HDKSyainSearch.btnHyouji",
        type: "button",
        handle: "",
    });

    //選択ボタン
    me.controls.push({
        id: ".HDKSyainSearch.btnSenntaku",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".HDKSyainSearch.btnModoru",
        type: "button",
        handle: "",
    });

    //検索ボタン
    me.controls.push({
        id: ".HDKSyainSearch.btnSearch",
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
    $(".HDKSyainSearch.btnHyouji").click(function () {
        me.btnHyouji_Click();
    });
    //処理説明：選択ボタン押下時
    $(".HDKSyainSearch.btnSenntaku").click(function () {
        me.gdvShainnBetuKG_RowDataBound();
    });
    //処理説明：戻るボタン押下時
    $(".HDKSyainSearch.btnModoru").click(function () {
        $("#HDKSyainSearchDialogDiv").dialog("close");
    });
    //処理説明：検索ボタン押下時
    $(".HDKSyainSearch.btnSearch").click(function () {
        me.btnSearch_Click();
    });
    //部署フォーカス移動
    $(".HDKSyainSearch.txtBusyo").on("blur", function () {
        me.txtBusyoCD_LostFocus();
    });
    $(".HDKSyainSearch.txtShainn").on("focus", function () {
        //テキストエリアを全選択する
        $(this).select();
    });
    //禁則文字
    $(".HDKSyainSearch.txtShainn").on("blur", function () {
        me.HDKAIKEI.KinsokuMojiCheck($(this), me.clsComFnc);
    });

    $(".HDKSyainSearch.txtShainn").on("keydown", function (e) {
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
        me.HDKSyainSearch_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HDKSyainSearch_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HDKSyainSearch_load = function () {
        //初期値設定
        $(".HDKSyainSearch.txtShainnNo").trigger("focus");
        $(".HDKSyainSearch.btnSenntaku").hide();

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
            me.ratio === 1.5 ? 633 : 643
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 242 : 312
        );
        $("#HDKAIKEI_HDKSyainSearch_sprItyp_rn").html("№");

        //部署
        var url = me.sys_id + "/" + me.id + "/" + "fncGetBusyoMstValue";
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
            ondblClickRow: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HDKSyainSearchDialogDiv").dialog("close");
            },
            onSelectRow: function (rowId) {
                $(me.grid_id + " tr#" + rowId).bind("keydown", function (e) {
                    var key = e.which;
                    e.preventDefault();
                    if (key == 9 && e.shiftKey == false) {
                        $(".HDKSyainSearch.btnSenntaku").trigger("focus");
                    } else if (key == 9 && e.shiftKey == true) {
                        $(".HDKSyainSearch.btnHyouji").trigger("focus");
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
                $("#HDKSyainSearchDialogDiv").dialog("close");
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
        var txtSyainNO = $.trim($(".HDKSyainSearch.txtShainnNo").val());
        var txtSyainNM = $.trim($(".HDKSyainSearch.txtShainnNM").val());
        var txtSyainKN = $.trim($(".HDKSyainSearch.txtShainnNM_Kana").val());
        var txtBusyoCD = $.trim($(".HDKSyainSearch.txtBusyo").val());
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
                $(".HDKSyainSearch.txtShainnNo").trigger("focus");
                $(".HDKSyainSearch.btnSenntaku").hide();
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                //選択ボタンが表示されます。
                $(".HDKSyainSearch.btnSenntaku").show();
                $(".HDKSyainSearch.txtShainnNo").trigger("focus");
                $(".HDKSyainSearch .tableItyp").css("visibility", "visible");
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
        $("#HDKSyainSearchDialogDiv").dialog("close");
    };
    //**********************************************************************
    //処 理 名：検索ボタンクリック
    //関 数 名：btnSearch_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.btnSearch_Click = function () {
        var frmId = "HDKCreatBusyoSearch";
        var dialogId = "HDKCreatBusyoSearchDialogDiv";
        var $rootDiv = $(".HDKSyainSearch.HDKAIKEI-content");
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
        $(".HDKSyainSearch.txtShainnNo").trigger("focus");
        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if ($RtnCD.html() == 1) {
                    $(".HDKSyainSearch.txtBusyo").val($BusyoCD.html());
                    $(".HDKSyainSearch.lblBusyo").val($BusyoNM.html());
                }
                $RtnCD.remove();
                $BusyoCD.remove();
                $BusyoNM.remove();
                $("#" + dialogId).remove();
                setTimeout(function () {
                    $(".HDKSyainSearch.btnSearch").trigger("focus");
                }, 100);
                $(me.grid_id).jqGrid("clearGridData");
                $(".HDKSyainSearch .tableItyp").css("visibility", "hidden");
            }

            $("#" + dialogId).hide();
            $("#" + dialogId).append(result);
            o_HDKAIKEI_HDKAIKEI.HDKSyainSearch.HDKCreatBusyoSearch.before_close =
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
    me.txtBusyoCD_LostFocus = function () {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(
            $.trim($(".HDKSyainSearch.txtBusyo").val())
        );
        if (me.name_busyoSaki) {
            var foundNM_array = me.name_busyoSaki.filter(function (element) {
                return element["BUSYO_CD"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".HDKSyainSearch.lblBusyo").val(foundNM ? foundNM["BUSYO_NM"] : "");
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HDKAIKEI_HDKSyainSearch = new HDKAIKEI.HDKSyainSearch();
    o_HDKAIKEI_HDKSyainSearch.load();
    o_HDKAIKEI_HDKAIKEI.HDKSyainSearch = o_HDKAIKEI_HDKSyainSearch;
});
