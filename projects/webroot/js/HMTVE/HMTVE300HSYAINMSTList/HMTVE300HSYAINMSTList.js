Namespace.register("HMTVE.HMTVE300HSYAINMSTList");

HMTVE.HMTVE300HSYAINMSTList = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.hmtve = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMTVE300HSYAINMSTList";
    me.sys_id = "HMTVE";

    // ========== 変数 start ==========

    me.grid_id = "#HMTVE300HSYAINMSTListMain";
    me.pager = "#HMTVE300HSYAINMSTList_pager";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnSearch_Click";
    //部署
    me.post_data = "";
    me.isBusyoErr = false;
    //現在選択されているデータの行数
    me.nowSelId = "";
    me.option = {
        pagerpos: "center",
        // viewrecords : false,
        multiselect: false,
        caption: "",
        rowNum: 10,
        rowList: [10, 20, 30],
        rownumbers: false,
        scroll: false,
        autowidth: true,
        pager: me.pager,
        recordpos: "right",
    };

    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "社員№",
            index: "SYAIN_NO",
            width: 74,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 250,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAIN_KN",
            label: "社員名カナ",
            index: "SYAIN_KN",
            width: 225,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYO_RYKNM",
            label: "部署",
            index: "BUSYO_RYKNM",
            width: 115,
            align: "left",
            sortable: false,
        },
        {
            name: "btnEdit",
            label: " ",
            index: "btnEdit",
            width: 62,
            align: "right",
            sortable: false,
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"openAllWindow('" +
                    rowObject["SYAIN_NO"] +
                    "','" +
                    "Edit" +
                    "')\" id = '" +
                    i +
                    "_btnEdit' class=\"HMTVE300HSYAINMSTList btnEdit\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>" +
                    "修正" +
                    "</button>";
                return detail;
            },
        },
        {
            name: "btnDel",
            label: " ",
            index: "btnDel",
            width: 62,
            align: "right",
            sortable: false,
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"openAllWindow('" +
                    rowObject["SYAIN_NO"] +
                    "','" +
                    "Del" +
                    "')\" id = '" +
                    i +
                    "_btnDel' class=\"HMTVE300HSYAINMSTList btnDel\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>" +
                    "削除" +
                    "</button>";
                return detail;
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE300HSYAINMSTList.btnSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMTVE300HSYAINMSTList.btnAdd",
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

    //処理説明：検索ボタン押下時
    $(".HMTVE300HSYAINMSTList.btnSearch").click(function () {
        me.btnSearch_Click();
    });
    //処理説明：追加ボタン押下時
    $(".HMTVE300HSYAINMSTList.btnAdd").click(function () {
        me.openAddWindow();
    });
    //部署 tab/enter
    $(".HMTVE300HSYAINMSTList.txtDispose").bind("keydown", function (e) {
        var key = e.which;
        if (key == 13 || (key == 9 && e.shiftKey == false)) {
            e.preventDefault();
            if (me.isBusyoErr == false) {
                //社員№
                $(".HMTVE300HSYAINMSTList.txtNumber").trigger("focus");
            } else {
                //部署
                $(".HMTVE300HSYAINMSTList.txtDispose").trigger("focus");
            }
        }
    });
    //部署change
    $(".HMTVE300HSYAINMSTList.txtDispose").on("change", function () {
        me.isBusyoErr = false;
        me.FoucsMove();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：init_control
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_Load();
    };

    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：ページ初期化
    //**********************************************************************
    me.Page_Load = function () {
        try {
            //ﾛｸﾞｲﾝ情報ﾃｰﾌﾞﾙを非表示にする
            $(".HMTVE300HSYAINMSTList.pnlList").hide();

            //画面項目をクリアする
            me.ClearScreen();

            gdmz.common.jqgrid.init2(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                "",
                me.option
            );

            gdmz.common.jqgrid.set_grid_width(me.grid_id, 840);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id,
                me.ratio === 1.5 ? 256 : 266
            );

            //部署
            var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoMstValue";
            var data = {
                txtDispose: $.trim(
                    $(".HMTVE300HSYAINMSTList.txtDispose").val()
                ),
            };

            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                //部署コード
                me.post_data = result["data"];
            };
            me.ajax.send(url, data, 0);

            $(me.grid_id).jqGrid("bindKeys");

            $(".HMTVE300HSYAINMSTList.txtDispose").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：画面をクリア
    //関 数 名：ClearScreen
    //引 数 　：なし
    //戻 り 値：なし
    //処理説明：画面をクリア
    //**********************************************************************
    me.ClearScreen = function () {
        try {
            //部署コード
            $(".HMTVE300HSYAINMSTList.txtDispose").val("");
            //社員№
            $(".HMTVE300HSYAINMSTList.txtNumber").val("");
            //社員名カナ
            $(".HMTVE300HSYAINMSTList.txtName").val("");
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：検索ボタンのイベント
    //関 数 名：btnSearch_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ﾛｸﾞｲﾝ情報の検索処理
    //**********************************************************************
    me.btnSearch_Click = function () {
        try {
            //グリッドビューにデータをバインドする
            $(".HMTVE300HSYAINMSTList.pnlList").hide();

            me.BindGridViewData(1);
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：フォーカス
    //関 数 名：FoucsMove
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：フォーカス移動時
    //**********************************************************************
    me.FoucsMove = function () {
        try {
            //'画面項目NO18.入力ﾃｰﾌﾞﾙ_部署コードが見入力の場合、処理を抜ける
            if ($(".HMTVE300HSYAINMSTList.txtDispose").val() != "") {
                var objRegEX_AN = /^[a-zA-Z0-9\-]*$/g;
                if (
                    !objRegEX_AN.test(
                        $.trim($(".HMTVE300HSYAINMSTList.txtDispose").val())
                    )
                ) {
                    me.isBusyoErr = true;
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        //部署
                        $(".HMTVE300HSYAINMSTList.txtDispose").trigger("focus");
                        me.isBusyoErr = false;
                    };
                    me.clsComFnc.FncMsgBox("E0013", "部署");
                    //msg[OK] focus
                    window.setTimeout(function () {
                        var len =
                            $(".ui-dialog-buttons").find(".ui-button").length;
                        if (len > 0) {
                            $(".ui-dialog-buttons")
                                .find(".ui-button")
                                .eq(len - 1)
                                .trigger("focus");
                        }
                    }, 0);
                    $(".HMTVE300HSYAINMSTList.txtDispose").css(
                        "background",
                        "#FF0000"
                    );

                    if ($(".HMTVE300HSYAINMSTList.lblDispose2").val() != "") {
                        $(".HMTVE300HSYAINMSTList.lblDispose2").val("");
                    }
                    return;
                } else {
                    $(".HMTVE300HSYAINMSTList.txtDispose").css(
                        "background",
                        "none"
                    );
                    me.isBusyoErr = false;
                }

                var foundNM = undefined;
                var selCellVal = me.clsComFnc.FncNv(
                    $(".HMTVE300HSYAINMSTList.txtDispose").val()
                );
                if (me.post_data) {
                    var foundNM_array = me.post_data.filter(function (element) {
                        return element["BUSYO_CD"] == selCellVal;
                    });
                    if (foundNM_array.length > 0) {
                        foundNM = foundNM_array[0];
                    }
                    for (var index in me.post_data) {
                        if (
                            me.post_data[index]["BUSYO_CD"] ==
                            $(".HMTVE300HSYAINMSTList.txtDispose").val()
                        ) {
                            me.isBusyoErr = false;
                            $(".HMTVE300HSYAINMSTList.lblDispose2").val(
                                me.post_data[index]["BUSYO_RYKNM"]
                            );
                            //社員№
                            $(".HMTVE300HSYAINMSTList.txtNumber").trigger(
                                "focus"
                            );
                            break;
                        } else {
                            $(".HMTVE300HSYAINMSTList.lblDispose2").val("");
                            $(".HMTVE300HSYAINMSTList.txtDispose").trigger(
                                "focus"
                            );
                        }
                    }
                }
                $(".HMTVE300HSYAINMSTList.lblDispose2").val(
                    foundNM ? foundNM["BUSYO_RYKNM"] : ""
                );
                if ($(".HMTVE300HSYAINMSTList.lblDispose2").val() == "") {
                    me.isBusyoErr = true;
                }
                //twice tab will change focus
                window.setTimeout(function () {
                    me.isBusyoErr = false;
                }, 0);
            } else {
                me.isBusyoErr = false;
                $(".HMTVE300HSYAINMSTList.lblDispose2").val("");
                $(".HMTVE300HSYAINMSTList.txtDispose").css(
                    "background",
                    "none"
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：データバインドのイベント
    //関 数 名：BindGridViewData
    //引 数 　：showPageNum:どのページ
    //戻 り 値：なし
    //処理説明：指定した社員のデータを削除する
    //**********************************************************************
    (me.BindGridViewData = function (showPageNum) {
        try {
            var txtDispose = $.trim(
                $(".HMTVE300HSYAINMSTList.txtDispose").val()
            );
            var txtNumber = $.trim($(".HMTVE300HSYAINMSTList.txtNumber").val());
            var txtName = $.trim($(".HMTVE300HSYAINMSTList.txtName").val());

            var data = {
                txtDispose: txtDispose,
                txtNumber: txtNumber,
                txtName: txtName,
            };

            var complete_fun = function (returnFLG, result) {
                if (result["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                if (returnFLG == "nodata") {
                    //メッセージを表示し、処理を終了
                    me.clsComFnc.FncMsgBox("W0024");
                    $(".HMTVE300HSYAINMSTList.pnlList").hide();
                    return;
                } else {
                    $(".HMTVE300HSYAINMSTList.pnlList").show();
                    //修正
                    if (me.nowSelId) {
                        $(me.grid_id).jqGrid("setSelection", me.nowSelId);
                        me.nowSelId = "";
                    } else {
                        if (result["page"] == "1") {
                            //１行目を選択状態にする
                            $(me.grid_id).jqGrid("setSelection", "0");
                        } else {
                            //ページをめくる後,１行目を選択状態にする
                            var selRow =
                                $(".ui-pg-selbox").val() * (result["page"] - 1);
                            $(me.grid_id).jqGrid("setSelection", selRow);
                        }
                    }
                }
            };
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                data,
                complete_fun,
                showPageNum
            );
        } catch (ex) {
            console.log(ex);
        }
    }),
        //**********************************************************************
        //処 理 名：追加ボタンのイベント
        //関 数 名：openAddWindow
        //引    数：無し
        //戻 り 値：無し
        //処理説明：ﾛｸﾞｲﾝ情報の検索処理
        //**********************************************************************
        (me.openAddWindow = function () {
            try {
                var frmId = "HMTVE310HSYAINMSTEntry";
                var dialogdiv = "HMTVE310HSYAINMSTEntryDialogDiv";
                var $rootDiv = $(".HMTVE300HSYAINMSTList.HMTVE-content");

                //画面に文字が出たら消えます。
                $("<div style='display:none;'></div>")
                    .prop("id", dialogdiv)
                    .insertAfter($rootDiv);
                $("<div style='display:none;'></div>")
                    .prop("id", "MODE")
                    .insertAfter($rootDiv);

                var $MODE = $rootDiv.parent().find("#MODE");
                $MODE.html("");

                var url = me.sys_id + "/" + frmId;
                me.ajax.send(url, "", 0);
                me.ajax.receive = function (result) {
                    function before_close() {
                        $MODE.remove();
                        $("#" + dialogdiv).remove();
                        me.BindGridViewData(1);
                    }

                    $("#" + dialogdiv).append(result);

                    o_HMTVE_HMTVE.HMTVE300HSYAINMSTList.HMTVE310HSYAINMSTEntry.before_close =
                        before_close;
                };
            } catch (ex) {
                console.log(ex);
            }
        });

    //**********************************************************************
    //処 理 名：修正ボタンand削除ボタンのイベント
    //関 数 名：openAllWindow
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ﾛｸﾞｲﾝ情報の検索処理
    //**********************************************************************
    openAllWindow = function (id, row) {
        try {
            var frmId = "HMTVE310HSYAINMSTEntry";
            var dialogdiv = "HMTVE310HSYAINMSTEntryDialogDiv";
            var $rootDiv = $(".HMTVE300HSYAINMSTList.HMTVE-content");
            if ($("#" + dialogdiv).length > 0) {
                $("#" + dialogdiv).remove();
            }
            //画面に文字が出たら消えます。
            $("<div style='display:none;'></div>")
                .prop("id", dialogdiv)
                .insertAfter($rootDiv);
            $("<div style='display:none;'></div>")
                .prop("id", "MODE")
                .insertAfter($rootDiv);
            $("<div style='display:none;'></div>")
                .prop("id", "SYAIN_NO")
                .insertAfter($rootDiv);

            var $MODE = $rootDiv.parent().find("#MODE");
            var $SYAIN_NO = $rootDiv.parent().find("#SYAIN_NO");
            $MODE.html("2");
            if (row === "Del") {
                $MODE.html("3");
            }

            $SYAIN_NO.html(id);

            var url = me.sys_id + "/" + frmId;
            me.ajax.send(url, "", 0);
            me.ajax.receive = function (result) {
                function before_close() {
                    $MODE.remove();
                    $SYAIN_NO.remove();
                    $("#" + dialogdiv).remove();
                    if (row === "Edit") {
                        me.nowSelId = $(me.grid_id).jqGrid(
                            "getGridParam",
                            "selrow"
                        );
                        me.BindGridViewData("");
                    } else {
                        //delete
                        me.BindGridViewData(1);
                    }
                }
                $("#" + dialogdiv).append(result);

                o_HMTVE_HMTVE.HMTVE300HSYAINMSTList.HMTVE310HSYAINMSTEntry.before_close =
                    before_close;
            };
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
    var o_HMTVE_HMTVE300HSYAINMSTList = new HMTVE.HMTVE300HSYAINMSTList();
    o_HMTVE_HMTVE300HSYAINMSTList.load();
    o_HMTVE_HMTVE.HMTVE300HSYAINMSTList = o_HMTVE_HMTVE300HSYAINMSTList;
});
