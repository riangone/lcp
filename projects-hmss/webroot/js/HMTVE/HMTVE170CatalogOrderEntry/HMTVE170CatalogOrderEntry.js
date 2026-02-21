/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE170CatalogOrderEntry");

HMTVE.HMTVE170CatalogOrderEntry = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.HMTVE = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";

    me.sys_id = "HMTVE";
    me.id = "HMTVE170CatalogOrderEntry";
    me.grid_id1 = "#HMTVE170CatalogOrderEntry_sprList1";
    me.grid_id2 = "#HMTVE170CatalogOrderEntry_sprList2";
    me.grid_id3 = "#HMTVE170CatalogOrderEntry_sprList3";

    me.sysDate = "";
    me.sysTime = "";
    me.PatternID = gdmz.SessionPatternID;
    me.last_selected_id1 = "";
    me.last_selected_id2 = "";
    me.last_selected_id3 = "";
    me.objdr = [];
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        caption: "",
        multiselectWidth: 60,
    };

    me.colModel1 = [
        {
            label: "発行年月",
            width: 60,
            align: "left",
            name: "HAKKO_YM",
            index: "HAKKO_YM",
            sortable: false,
            editable: false,
        },
        {
            label: "コード",
            width: 50,
            align: "left",
            name: "CATALOG_CD",
            index: "CATALOG_CD",
            sortable: false,
            editable: false,
        },
        {
            label: "本カタログ",
            width: 170,
            align: "left",
            name: "CATALOG_NM",
            index: "CATALOG_NM",
            sortable: false,
            editable: false,
        },
        {
            label: "単価",
            width: 70,
            align: "right",
            name: "TANKA",
            index: "TANKA",
            sortable: false,
        },
        {
            label: "注文数",
            width: 70,
            align: "right",
            name: "ORDER_NUM",
            index: "ORDER_NUM",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "6",
                class: "align_right",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];
    me.colModel2 = [
        {
            label: "発行年月",
            width: 60,
            align: "left",
            name: "HAKKO_YM",
            index: "HAKKO_YM",
            sortable: false,
        },
        {
            label: "コード",
            width: 50,
            align: "left",
            name: "CATALOG_CD",
            index: "CATALOG_CD",
            sortable: false,
        },
        {
            label: "用品カタログ",
            width: me.ratio === 1.5 ? 160 : 170,
            align: "left",
            name: "CATALOG_NM",
            index: "CATALOG_NM",
            sortable: false,
        },
        {
            label: "単価",
            width: 60,
            align: "right",
            name: "TANKA",
            index: "TANKA",
            sortable: false,
        },
        {
            label: "注文数",
            width: 70,
            align: "right",
            name: "ORDER_NUM2",
            index: "ORDER_NUM2",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "6",
                class: "align_right",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];
    me.colModel3 = [
        {
            label: "コード",
            width: 50,
            align: "left",
            name: "CATALOG_CD",
            index: "CATALOG_CD",
            sortable: false,
        },
        {
            label: "用品",
            width: 170,
            align: "left",
            name: "CATALOG_NM",
            index: "CATALOG_NM",
            sortable: false,
        },
        {
            label: "単価",
            width: 70,
            align: "right",
            name: "TANKA",
            index: "TANKA",
            sortable: false,
        },
        {
            label: "注文数",
            width: 70,
            align: "right",
            name: "ORDER_NUM3",
            index: "ORDER_NUM3",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "6",
                class: "align_right",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE170CatalogOrderEntry.btnETOrder",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMTVE.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMTVE.TabKeyDown();

    //Enterキーのバインド
    me.HMTVE.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //注文ボタンクリック
    $(".HMTVE170CatalogOrderEntry.btnETOrder").click(function () {
        me.btnETOrder_Click();
    });
    //店舗名change
    $(".HMTVE170CatalogOrderEntry.txtShopCD").change(function () {
        me.FoucsMove();
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
            if ($(window).height() <= 739) {
                //垂直スクロールバー
                $(".HMTVE.HMTVE-layout-center").css("overflow-y", "scroll");
            }
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
    me.Page_Load = function () {
        try {
            $(".HMTVE170CatalogOrderEntry.txtShopCD").val("");
            $(".HMTVE170CatalogOrderEntry.lblShopNameShow").text("");
            //jqjrid No1
            //表示設定
            $(".HMTVE170CatalogOrderEntry.tblMain").hide();
            $(".HMTVE170CatalogOrderEntry.btnETOrder").hide();
            var url1 = me.sys_id + "/" + me.id + "/loadRowDataOne";
            var data1 = {
                BUSYOCD: $(".HMTVE170CatalogOrderEntry.txtShopCD").val(),
            };

            gdmz.common.jqgrid.showWithMesg(
                me.grid_id1,
                url1,
                me.colModel1,
                "",
                "",
                me.option,
                data1,
                function (_bErrorFlag, result_jqgrid1) {
                    if (result_jqgrid1["error"]) {
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            result_jqgrid1["error"]
                        );
                        return;
                    }
                    me.objdr = result_jqgrid1["HBUSYO"];
                    me.fncJqgrid(me.grid_id1);
                    if (result_jqgrid1["openTime"]) {
                        me.sysDate = result_jqgrid1["openTime"].substring(
                            0,
                            10
                        );
                        me.sysTime = result_jqgrid1["openTime"].substring(
                            10,
                            19
                        );
                    }

                    //上記以外
                    $(".HMTVE170CatalogOrderEntry.lblOrderDayShow").text(
                        me.sysDate
                    );
                    $(".HMTVE170CatalogOrderEntry.lblOrderTimeShow").text(
                        me.sysTime
                    );
                    //店舗コードを表示する
                    if (
                        me.PatternID == me.HMTVE.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.HMTVE.CONST_HONBU_PTN_NO ||
                        me.PatternID == me.HMTVE.CONST_TESTER_PTN_NO
                    ) {
                        $(".HMTVE170CatalogOrderEntry.txtShopCD").prop(
                            "disabled",
                            false
                        );
                        $(".HMTVE170CatalogOrderEntry.txtShopCD").css(
                            "background-color",
                            "#FFFFFF"
                        );
                    } else {
                        if (result_jqgrid1["BusyoCD"]) {
                            $(".HMTVE170CatalogOrderEntry.txtShopCD").val(
                                result_jqgrid1["BusyoCD"]
                            );
                            me.FoucsMove();
                        }
                        $(".HMTVE170CatalogOrderEntry.txtShopCD").prop(
                            "disabled",
                            true
                        );
                        $(".HMTVE170CatalogOrderEntry.txtShopCD").css(
                            "background-color",
                            "#C0C0C0"
                        );
                    }
                    //jqjrid No2
                    var url2 = me.sys_id + "/" + me.id + "/loadRowDataTwo";
                    var data2 = {
                        BUSYOCD: $(
                            ".HMTVE170CatalogOrderEntry.txtShopCD"
                        ).val(),
                        openTime: me.sysDate + " " + me.sysTime,
                    };
                    gdmz.common.jqgrid.showWithMesg(
                        me.grid_id2,
                        url2,
                        me.colModel2,
                        "",
                        "",
                        me.option,
                        data2,
                        function (_bErrorFlag2, result_jqgrid2) {
                            if (result_jqgrid2["error"]) {
                                me.clsComFnc.FncMsgBox(
                                    "E9999",
                                    result_jqgrid2["error"]
                                );
                                return;
                            }
                            me.fncJqgrid(me.grid_id2);
                            //jqjrid No3
                            var url3 =
                                me.sys_id + "/" + me.id + "/loadRowDataThree";
                            var data3 = {
                                BUSYOCD: $(
                                    ".HMTVE170CatalogOrderEntry.txtShopCD"
                                ).val(),
                                openTime: me.sysDate + " " + me.sysTime,
                            };
                            gdmz.common.jqgrid.showWithMesg(
                                me.grid_id3,
                                url3,
                                me.colModel3,
                                "",
                                "",
                                me.option,
                                data3,
                                function (_bErrorFlag3, result_jqgrid3) {
                                    if (result_jqgrid3["error"]) {
                                        me.clsComFnc.FncMsgBox(
                                            "E9999",
                                            result_jqgrid3["error"]
                                        );
                                        return;
                                    }
                                    me.fncJqgrid(me.grid_id3);
                                    $(
                                        ".HMTVE170CatalogOrderEntry.tblMain"
                                    ).show();
                                    $(
                                        ".HMTVE170CatalogOrderEntry.btnETOrder"
                                    ).show();
                                    if (me.setGridViewDate()) {
                                        if (
                                            me.PatternID ==
                                                me.HMTVE.CONST_ADMIN_PTN_NO ||
                                            me.PatternID ==
                                                me.HMTVE.CONST_HONBU_PTN_NO ||
                                            me.PatternID ==
                                                me.HMTVE.CONST_TESTER_PTN_NO
                                        ) {
                                            $(
                                                ".HMTVE170CatalogOrderEntry.txtShopCD"
                                            ).trigger("focus");
                                        }
                                    }
                                }
                            );
                            gdmz.common.jqgrid.set_grid_height(
                                me.grid_id3,
                                me.ratio === 1.5 ? 70 : 106
                            );
                            gdmz.common.jqgrid.set_grid_width(
                                me.grid_id3,
                                397
                            );
                            $(me.grid_id3).jqGrid("bindKeys");
                        }
                    );
                    gdmz.common.jqgrid.set_grid_height(me.grid_id2, me.ratio === 1.5 ? 180 : 212);
                    gdmz.common.jqgrid.set_grid_width(me.grid_id2, me.ratio === 1.5 ? 420 : 453);
                    $(me.grid_id2).jqGrid("bindKeys");
                }
            );
            gdmz.common.jqgrid.set_grid_height(me.grid_id1, me.ratio === 1.5 ? 300 : 365);
            gdmz.common.jqgrid.set_grid_width(me.grid_id1, 462);
            $(me.grid_id1).jqGrid("bindKeys");
        } catch (ex) {
            console.log(ex);
        }
    };
    //**********************************************************************
    //処 理 名：jqgrid イベント
    //関 数 名：fncJqgrid
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************
    me.fncJqgrid = function (tableId) {
        try {
            var rowSelectId = "";
            if (tableId == me.grid_id1) {
                rowSelectId = me.last_selected_id1;
            } else if (tableId == me.grid_id2) {
                rowSelectId = me.last_selected_id2;
            } else if (tableId == me.grid_id3) {
                rowSelectId = me.last_selected_id3;
            }
            //edit cell
            $(tableId).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    $(tableId).jqGrid(
                        "saveRow",
                        rowSelectId,
                        null,
                        "clientArray"
                    );
                    if (typeof e != "undefined") {
                        if (rowid && rowid != rowSelectId) {
                            rowSelectId = rowid;
                        }

                        $("input,select", e.target).trigger("focus");
                    } else {
                        if (rowid && rowid != rowSelectId) {
                            rowSelectId = rowid;
                        }
                    }
                    $(tableId).jqGrid("editRow", rowid, false);

                    $(tableId).find(".align_right").css("text-align", "right");

                    var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                        tableId,
                        e,
                        rowSelectId
                    );
                    if (up_next_sel && up_next_sel.length == 2) {
                        me.upsel = up_next_sel[0];
                        me.nextsel = up_next_sel[1];
                    }

                    if (tableId == me.grid_id1) {
                        me.last_selected_id1 = rowSelectId;
                    } else if (tableId == me.grid_id2) {
                        me.last_selected_id2 = rowSelectId;
                    } else if (tableId == me.grid_id3) {
                        me.last_selected_id3 = rowSelectId;
                    }
                },
            });
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：注文ボタンのイベント
	 '関 数 名：btnETOrder_Click
	 '戻 り 値：なし
	 '処理説明：注文処理
	 '**********************************************************************
	 */
    me.btnETOrder_Click = function () {
        try {
            //入力チェック
            if (me.checkInput() == false) {
                return;
            }

            //フォーカス移動
            var rowDatas1 = $(me.grid_id1).jqGrid("getRowData");
            var rowDatas2 = $(me.grid_id2).jqGrid("getRowData");
            var rowDatas3 = $(me.grid_id3).jqGrid("getRowData");
            var url = "HMTVE/HMTVE170CatalogOrderEntry/btnETOrderClick";
            var data = {
                BUSYOCD: $(".HMTVE170CatalogOrderEntry.txtShopCD").val(),
                openTime: me.sysDate + " " + me.sysTime,
                rowDatas1: rowDatas1,
                rowDatas2: rowDatas2,
                rowDatas3: rowDatas3,
                checked: $(".HMTVE170CatalogOrderEntry.chkHaisouKibou").prop(
                    "checked"
                ),
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                //表示行数の設定
                if (!result["result"]) {
                    $(me.grid_id3).jqGrid("setSelection", me.last_selected_id3);
                    $(me.grid_id2).jqGrid("setSelection", me.last_selected_id2);
                    $(me.grid_id1).jqGrid("setSelection", me.last_selected_id1);
                    setTimeout(() => {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }, 0);

                    return;
                }

                var $root_div = $(".HMTVE170CatalogOrderEntry.HMTVE-content");
                if ($("#HMTVE180CatalogOrderConfirmDialogDiv").length <= 0) {
                    $("<div></div>")
                        .attr("id", "HMTVE180CatalogOrderConfirmDialogDiv")
                        .insertAfter($root_div);
                    $("<div></div>")
                        .attr("id", "OrderDate")
                        .insertAfter($root_div);
                    $("<div></div>")
                        .attr("id", "OrderTime")
                        .insertAfter($root_div);
                    $("<div></div>")
                        .attr("id", "txtIntroPeople")
                        .insertAfter($root_div);
                }

                var $input = $root_div.parent().find("#txtIntroPeople");
                var $OrderTime = $root_div.parent().find("#OrderTime");
                var $OrderDate = $root_div.parent().find("#OrderDate");
                $input.val($(".HMTVE170CatalogOrderEntry.txtShopCD").val());
                $OrderTime.val(me.sysTime);
                $OrderDate.val(me.sysDate);

                var dialog_url = "HMTVE/HMTVE180CatalogOrderConfirm";
                me.ajax.receive = function (result) {
                    function before_close() {
                        if (
                            $("#HMTVE180CatalogOrderConfirmDialogDiv").length >
                            0
                        ) {
                            $("#txtIntroPeople").remove();
                            $("#HMTVE180CatalogOrderConfirmDialogDiv").remove();
                            $("#OrderDate").remove();
                            $("#OrderTime").remove();
                        }
                        //フォーカス移動
                        var rowDatas1 = $(me.grid_id1).jqGrid("getRowData");
                        var rowDatas2 = $(me.grid_id2).jqGrid("getRowData");
                        var rowDatas3 = $(me.grid_id3).jqGrid("getRowData");
                        if (rowDatas3 != 0) {
                            //本カタログ、用品カタログデータが存在しない場合、用品データが存在する場合は、用品ﾃｰﾌﾞﾙの1行目の注文数にフォーカス移動
                            $(me.grid_id3).jqGrid("setSelection", 0);
                        }
                        if (rowDatas2 != 0) {
                            //本カタログデータが存在しない場合、用品カタログデータが存在する場合は、用品カタログﾃｰﾌﾞﾙの1行目の注文数にフォーカス移動
                            $(me.grid_id2).jqGrid("setSelection", 0);
                        }
                        if (rowDatas1 != 0) {
                            //本カタログデータが存在する場合は、本カタログテーブルの1行目の注文数にフォーカス移動
                            $(me.grid_id1).jqGrid("setSelection", 0);
                        }
                    }
                    $("#HMTVE180CatalogOrderConfirmDialogDiv").html(result);

                    o_HMTVE_HMTVE.HMTVE170CatalogOrderEntry.HMTVE180CatalogOrderConfirm.before_close =
                        before_close;
                };
                me.ajax.send(dialog_url, "", 0);
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：	対象データを取得し、表示する
	 '関 数 名：setGridViewDate
	 '戻 り 値：本カタログﾃﾞｰﾀを取得するSQL
	 '処理説明：対象データを取得し、表示する
	 '**********************************************************************
	 */
    me.setGridViewDate = function () {
        try {
            $(".HMTVE170CatalogOrderEntry.chkHaisouKibou").prop(
                "checked",
                false
            );
            //本カタログ、用品カタログ、用品について取得データが一件も存在しない場合は、エラーメッセージを表示
            var objDR1 = $(me.grid_id1).jqGrid("getRowData");
            var objDR2 = $(me.grid_id2).jqGrid("getRowData");
            var objDR3 = $(me.grid_id3).jqGrid("getRowData");
            if (objDR1 == 0 && objDR2 == 0 && objDR3.length == 0) {
                me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                    //フォーカス移動
                    var rowDatas1 = $(me.grid_id1).jqGrid("getRowData");
                    var rowDatas2 = $(me.grid_id2).jqGrid("getRowData");
                    var rowDatas3 = $(me.grid_id3).jqGrid("getRowData");
                    if (rowDatas3 != 0) {
                        //本カタログ、用品カタログデータが存在しない場合、用品データが存在する場合は、用品ﾃｰﾌﾞﾙの1行目の注文数にフォーカス移動
                        $(me.grid_id3).jqGrid("setSelection", 0);
                    }
                    if (rowDatas2 != 0) {
                        //本カタログデータが存在しない場合、用品カタログデータが存在する場合は、用品カタログﾃｰﾌﾞﾙの1行目の注文数にフォーカス移動
                        $(me.grid_id2).jqGrid("setSelection", 0);
                    }
                    if (rowDatas1 != 0) {
                        //本カタログデータが存在する場合は、本カタログテーブルの1行目の注文数にフォーカス移動
                        $(me.grid_id1).jqGrid("setSelection", 0);
                    }
                };
                if (
                    me.PatternID == me.HMTVE.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.HMTVE.CONST_HONBU_PTN_NO ||
                    me.PatternID == me.HMTVE.CONST_TESTER_PTN_NO
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE170CatalogOrderEntry.txtShopCD"
                    );
                }
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "カタログデータの設定が行われておりません。管理者にお問い合わせください。"
                );
                return false;
            }
            //フォーカス移動
            var rowDatas1 = $(me.grid_id1).jqGrid("getRowData");
            var rowDatas2 = $(me.grid_id2).jqGrid("getRowData");
            var rowDatas3 = $(me.grid_id3).jqGrid("getRowData");
            if (rowDatas3 != 0) {
                //本カタログ、用品カタログデータが存在しない場合、用品データが存在する場合は、用品ﾃｰﾌﾞﾙの1行目の注文数にフォーカス移動
                $(me.grid_id3).jqGrid("setSelection", 0);
            }
            if (rowDatas2 != 0) {
                //本カタログデータが存在しない場合、用品カタログデータが存在する場合は、用品カタログﾃｰﾌﾞﾙの1行目の注文数にフォーカス移動
                $(me.grid_id2).jqGrid("setSelection", 0);
            }
            if (rowDatas1 != 0) {
                //本カタログデータが存在する場合は、本カタログテーブルの1行目の注文数にフォーカス移動
                $(me.grid_id1).jqGrid("setSelection", 0);
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };

    me.checkInput = function () {
        try {
            //部署コード未入力チェック
            if (me.checkText("txtShopCD", "lblShopName") == false) {
                return false;
            }
            //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙ_注文数入力チェック
            var inputFlag = false;
            var rows = $(me.grid_id1).jqGrid("getDataIDs");
            for (index in rows) {
                $(me.grid_id1).jqGrid("saveRow", index);
                var rowData = $(me.grid_id1).jqGrid("getRowData", rows[index]);
                //紹介者ﾃｰﾌﾞﾙ_商談ﾌﾗｸﾞ＝"1"の場合
                if (rowData["ORDER_NUM"].length > 6) {
                    $(me.grid_id1).jqGrid("setSelection", index);
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "注文数は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //整合性チェックを行う
                var patt = /^-?[1-9][0-9]*$/g;
                if (
                    (!rowData["ORDER_NUM"].match(patt) &&
                        rowData["ORDER_NUM"]) ||
                    me.clsComFnc.GetByteCount(rowData["ORDER_NUM"]) !=
                        rowData["ORDER_NUM"].length
                ) {
                    $(me.grid_id1).jqGrid("setSelection", index);
                    setTimeout(() => {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "入力されている値が不正です。"
                        );
                    }, 0);

                    return false;
                }
                if (rowData["ORDER_NUM"].length != 0) {
                    inputFlag = true;
                }
            }
            //用品ｶﾀﾛｸﾞﾃｰﾌﾞﾙ_注文数入力チェック
            var rows = $(me.grid_id2).jqGrid("getDataIDs");
            for (index in rows) {
                $(me.grid_id2).jqGrid("saveRow", index);
                var rowData = $(me.grid_id2).jqGrid("getRowData", rows[index]);
                //紹介者ﾃｰﾌﾞﾙ_商談ﾌﾗｸﾞ＝"1"の場合
                if (rowData["ORDER_NUM2"].length > 6) {
                    $(me.grid_id2).jqGrid("setSelection", index);
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "注文数は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //整合性チェックを行う
                var patt = /^-?[1-9][0-9]*$/g;
                if (
                    (!rowData["ORDER_NUM2"].match(patt) &&
                        rowData["ORDER_NUM2"]) ||
                    me.clsComFnc.GetByteCount(rowData["ORDER_NUM2"]) !=
                        rowData["ORDER_NUM2"].length
                ) {
                    $(me.grid_id2).jqGrid("setSelection", index);
                    setTimeout(() => {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "入力されている値が不正です。"
                        );
                    }, 0);
                    return false;
                }
                if (rowData["ORDER_NUM2"].length != 0) {
                    inputFlag = true;
                }
            }
            //用品ﾃｰﾌﾞﾙ_注文数入力チェック
            var rows = $(me.grid_id3).jqGrid("getDataIDs");
            for (index in rows) {
                $(me.grid_id3).jqGrid("saveRow", index);
                var rowData = $(me.grid_id3).jqGrid("getRowData", rows[index]);
                //紹介者ﾃｰﾌﾞﾙ_商談ﾌﾗｸﾞ＝"1"の場合
                if (rowData["ORDER_NUM3"].length > 6) {
                    $(me.grid_id3).jqGrid("setSelection", index);
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "注文数は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //整合性チェックを行う
                var patt = /^-?[1-9][0-9]*$/g;
                if (
                    (!rowData["ORDER_NUM3"].match(patt) &&
                        rowData["ORDER_NUM3"]) ||
                    me.clsComFnc.GetByteCount(rowData["ORDER_NUM3"]) !=
                        rowData["ORDER_NUM3"].length
                ) {
                    $(me.grid_id3).jqGrid("setSelection", index);
                    setTimeout(() => {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "入力されている値が不正です。"
                        );
                    }, 0);
                    return false;
                }
                if (rowData["ORDER_NUM3"].length != 0) {
                    inputFlag = true;
                }
            }
            if (!inputFlag) {
                //存在チェックを行う
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    var rowDatas1 = $(me.grid_id1).jqGrid("getRowData");
                    var rowDatas2 = $(me.grid_id2).jqGrid("getRowData");
                    var rowDatas3 = $(me.grid_id3).jqGrid("getRowData");
                    if (rowDatas3 != 0) {
                        //本カタログ、用品カタログデータが存在しない場合、用品データが存在する場合は、用品ﾃｰﾌﾞﾙの1行目の注文数にフォーカス移動
                        $(me.grid_id3).jqGrid("setSelection", 0);
                    }
                    if (rowDatas2 != 0) {
                        //本カタログデータが存在しない場合、用品カタログデータが存在する場合は、用品カタログﾃｰﾌﾞﾙの1行目の注文数にフォーカス移動
                        $(me.grid_id2).jqGrid("setSelection", 0);
                    }
                    if (rowDatas1 != 0) {
                        //本カタログデータが存在する場合は、本カタログテーブルの1行目の注文数にフォーカス移動
                        $(me.grid_id1).jqGrid("setSelection", 0);
                    }
                };
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "登録可能なデータが存在しません。"
                );
                return false;
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力ﾃｰﾌﾞﾙの画面項目のチェック　　
	 '関 数 名：checkText
	 '引 数 １：(I)textBox 　　 テキストボックス
	 '引 数 ２：(I)lable      　レッボ
	 '戻 り 値：入力チェック     Boolean
	 '処理説明：入力テキストをチェックする
	 '**********************************************************************
	 */
    me.checkText = function (textBox, lable) {
        try {
            if (
                $.trim($(".HMTVE170CatalogOrderEntry." + textBox).val()) == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE170CatalogOrderEntry." + textBox
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    $(".HMTVE170CatalogOrderEntry." + lable)
                        .text()
                        .trimEnd() + "を入力してください。"
                );
                return false;
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：部署コードのフォーカス移動
	 '関 数 名：moveTab
	 '引 数 １：なし
	 '戻 り 値：なし
	 '処理説明：担当者コンボリスト変更
	 '**********************************************************************
	 */
    me.moveTab = function () {
        try {
            //没有调用
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォーカス
	 '関 数 名：FoucsMove
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：フォーカス移動時
	 '**********************************************************************
	 */
    me.FoucsMove = function () {
        try {
            $(".HMTVE170CatalogOrderEntry.txtShopCD").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
            //画面項目NO18.入力ﾃｰﾌﾞﾙ_部署コードが見入力の場合、処理を抜ける
            if ($.trim($(".HMTVE170CatalogOrderEntry.txtShopCD").val()) != "") {
                var objRegEX_AN = /^[a-zA-Z0-9\-]*$/g;
                if (
                    !$.trim(
                        $(".HMTVE170CatalogOrderEntry.txtShopCD").val()
                    ).match(objRegEX_AN)
                ) {
                    if (
                        $(
                            ".HMTVE170CatalogOrderEntry.lblShopNameShow"
                        ).text() != ""
                    ) {
                        $(".HMTVE170CatalogOrderEntry.lblShopNameShow").text(
                            ""
                        );
                    }
                    $(".HMTVE170CatalogOrderEntry.txtShopCD").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE170CatalogOrderEntry.txtShopCD"
                    );
                    me.clsComFnc.FncMsgBox("E0013", "店舗名");
                    return;
                }
                if (me.objdr) {
                    var lblShopNameShow = me.objdr.filter(function (element) {
                        return (
                            element["BUSYO_CD"] ==
                            $(".HMTVE170CatalogOrderEntry.txtShopCD").val()
                        );
                    });
                    if (lblShopNameShow.length > 0) {
                        $(".HMTVE170CatalogOrderEntry.lblShopNameShow").text(
                            lblShopNameShow[0]["BUSYO_RYKNM"] == null
                                ? ""
                                : lblShopNameShow[0]["BUSYO_RYKNM"]
                        );
                    } else {
                        $(".HMTVE170CatalogOrderEntry.lblShopNameShow").text(
                            ""
                        );
                    }
                }
            } else {
                $(".HMTVE170CatalogOrderEntry.lblShopNameShow").text("");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE170CatalogOrderEntry =
        new HMTVE.HMTVE170CatalogOrderEntry();
    o_HMTVE_HMTVE170CatalogOrderEntry.load();
    o_HMTVE_HMTVE.HMTVE170CatalogOrderEntry = o_HMTVE_HMTVE170CatalogOrderEntry;
});
