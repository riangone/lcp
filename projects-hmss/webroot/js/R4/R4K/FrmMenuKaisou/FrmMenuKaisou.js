/**
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150812           #2028	#2078				   BUG                              Yuanjh
 * 20201118           bug                          jqgridのタイトルが2行を表示する                  WANGYING
 * 20201118           bug               dialogプログラム検索にスクロール・バーがあります           WANGYING
 * ------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmMenuKaisou");

R4.FrmMenuKaisou = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmMenuKaisou";
    me.grid_id = "#FrmMenuKaisou_sprList";
    me.lastsel = 0;
    me.timeoutFlag = false;
    me.firstCellFoxcus = true;
    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
        shrinkToFit: me.ratio === 1.5,
    };
    me.addData = {
        KAISOU_ID1: "",
        KAISOU_ID2: "",
        KAISOU_ID3: "",
        KAISOU_ID4: "",
        KAISOU_ID5: "",
        KAISOU_ID6: "",
        KAISOU_ID7: "",
        KAISOU_ID8: "",
        KAISOU_ID9: "",
        KAISOU_ID10: "",
        KAISOU_NM: "",
        PRO_NO: "",
        search: "",
        PRO_NM: "",
        CREATE_DATE: "",
    };
    me.colModel = [
        {
            name: "KAISOU_ID1",
            //20201118 wangying upd S
            // label : "階層ＩＤ",
            label: "階層</br>ＩＤ",
            //20201118 wangying upd E
            index: "KAISOU_ID1",
            width: 40,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //2015/08/19 YINHUAIYU add start
                            //shift+tab
                            if (e.shiftKey && key == 9) {
                                if (parseInt(me.lastsel) == 0) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                } else {
                                    $("#FrmMenuKaisou_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel,
                                        null,
                                        "clientArray"
                                    );
                                    $("#FrmMenuKaisou_sprList").jqGrid(
                                        "setSelection",
                                        parseInt(me.lastsel) - 1,
                                        true
                                    );
                                    setTimeout(() => {
                                        $("#" + me.lastsel + "_search").trigger(
                                            "focus"
                                        );
                                    }, 0);
                                    return false;
                                }
                            }
                            //2015/08/19 YINHUAIYU add end
                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_ID2").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_ID2",
            //20201118 wangying upd S
            // label : "階層ＩＤ2",
            label: "階層</br>ＩＤ2",
            //20201118 wangying upd E
            index: "KAISOU_ID2",
            width: 40, //20150811 modify  Yuanjh
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_ID3").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_ID3",
            //20201118 wangying upd S
            // label : "階層ＩＤ3",
            label: "階層</br>ＩＤ3",
            //20201118 wangying upd E
            index: "KAISOU_ID3",
            //20201118 wangying ups S
            // width : 40,
            width: 42,
            //20201118 wangying ups E
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_ID4").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_ID4",
            //20201118 wangying upd S
            // label : "階層ＩＤ4",
            label: "階層</br>ＩＤ4",
            //20201118 wangying upd E
            index: "KAISOU_ID4",
            //20201118 wangying upd S
            // width : 40,
            width: 42,
            //20201118 wangying upd E
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_ID5").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_ID5",
            //20201118 wangying upd S
            // label : "階層ＩＤ5",
            label: "階層</br>ＩＤ5",
            //20201118 wangying upd E
            index: "KAISOU_ID5",
            //20201118 wangying upd S
            // width : 40,
            width: 42,
            //20201118 wangying upd E
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_ID6").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_ID6",
            //20201118 wangying upd S
            // label : "階層ＩＤ6",
            label: "階層</br>ＩＤ6",
            //20201118 wangying upd E
            index: "KAISOU_ID6",
            //20201118 wangying upd S
            // width : 40,
            width: 42,
            //20201118 wangying upd E
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_ID7").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_ID7",
            //20201118 wangying upd S
            // label : "階層ＩＤ7",
            label: "階層</br>ＩＤ7",
            //20201118 wangying upd E
            index: "KAISOU_ID7",
            //20201118 wangying upd S
            // width : 40,
            width: 42,
            //20201118 wangying upd E
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_ID8").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_ID8",
            //20201118 wangying upd S
            // label : "階層ＩＤ8",
            label: "階層</br>ＩＤ8",
            //20201118 wangying upd E
            index: "KAISOU_ID8",
            //20201118 wangying upd S
            // width : 40,
            width: 42,
            //20201118 wangying upd E
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_ID9").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_ID9",
            //20201118 wangying upd S
            // label : "階層ＩＤ9",
            label: "階層</br>ＩＤ9",
            //20201118 wangying upd E
            index: "KAISOU_ID9",
            //20201118 wangying upd S
            // width : 40,
            width: 42,
            //20201118 wangying upd E
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_ID10").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_ID10",
            //20201118 wangying upd S
            // label : "階層　ＩＤ10",
            label: "階層</br>ＩＤ10",
            //20201118 wangying upd E
            index: "KAISOU_ID10",
            width: 50,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                $("#" + me.lastsel + "_KAISOU_NM").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAISOU_NM",
            label: "階層名称",
            index: "KAISOU_NM",
            //---20150930 LI UPD S.
            //width : 220,
            //20201118 wangying upd S
            // width : 180,
            width: 160,
            //20201118 wangying upd E
            //---20150930 LI UPD E.
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: 50,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 13) {
                                if (!me.addNewRow("KAISOU_NM", "PRO_NO")) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "PRO_NO",
            label: "No.",
            index: "PRO_NO",
            width: 30,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                return false;
                            } else if (key == 13) {
                                if (!me.addNewRow("PRO_NO", "search")) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            } else if (key == 9 && !e.shiftKey) {
                                $(me.grid_id).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                me.getProNM(false, me.lastsel, "tab");
                                return false;
                            } else if (key == 9) {
                                $(me.grid_id).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                me.getProNM(false, me.lastsel, "tabshift");
                                return false;
                            } else if (key == 38 || key == 40) {
                                me.setColSelection(key);
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "search",
            label: "検索",
            index: "search",
            width: "60",
            sortable: false,
            editable: true,
            align: "center",
            edittype: "button",
            editoptions: {
                value: "検索",
                class: "width",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 13) {
                                if (!me.addNewRow("search", "KAISOU_ID1")) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            } else if (key == 9 && !e.shiftKey) {
                                var selIRow = parseInt(me.lastsel) + 1;
                                var getDataCount = $(me.grid_id).jqGrid(
                                    "getGridParam",
                                    "records"
                                );

                                if (selIRow == getDataCount) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return;
                                }

                                $(me.grid_id).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                me.firstCellFoxcus = true;
                                $(me.grid_id).jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                return false;
                            }
                        },
                    },
                    {
                        type: "click",
                        fn: function () {
                            $("<div></div>")
                                .attr("id", "FrmProgramSearchDialogDiv")
                                .insertAfter($("#FrmMenuKaisou"));
                            $("<div></div>")
                                .attr("id", "ProgNO")
                                .insertAfter($("#FrmMenuKaisou"));
                            $("<div></div>")
                                .attr("id", "ProgNM")
                                .insertAfter($("#FrmMenuKaisou"));
                            $("<div></div>")
                                .attr("id", "isOk")
                                .insertAfter($("#FrmMenuKaisou"));

                            $("<div></div>").attr("id", "ProgNO").hide();
                            $("<div></div>").attr("id", "ProgNM").hide();
                            $("<div></div>").attr("id", "isOk").hide();

                            $("#FrmProgramSearchDialogDiv").dialog({
                                autoOpen: false,
                                modal: true,
                                //2020118 wangying upd S
                                // height : 680,
                                height: me.ratio === 1.5 ? 558 : 700,
                                //2020118 wangying upd E
                                width: 550,
                                resizable: false,
                                close: function () {
                                    var flgIsOk = $("#isOk").html();

                                    if (flgIsOk) {
                                        $(me.grid_id).jqGrid(
                                            "saveRow",
                                            me.lastsel,
                                            null,
                                            "clientArray"
                                        );

                                        var rowData = $(me.grid_id).jqGrid(
                                            "getRowData",
                                            me.lastsel
                                        );
                                        rowData["PRO_NO"] = $("#ProgNO").html();
                                        rowData["PRO_NM"] = $("#ProgNM").html();

                                        $(me.grid_id).jqGrid(
                                            "setRowData",
                                            me.lastsel,
                                            rowData
                                        );
                                        $(me.grid_id).jqGrid(
                                            "setSelection",
                                            me.lastsel,
                                            true
                                        );
                                        $("#" + me.lastsel + "_PRO_NO").trigger(
                                            "focus"
                                        );
                                    }

                                    $("#ProgNO").remove();
                                    $("#ProgNM").remove();
                                    $("#isOk").remove();
                                },
                            });

                            var frmId = "FrmProgramSearch";
                            var url = "R4K/" + frmId;
                            me.ajax.send(url, "", 0);
                            me.ajax.receive = function (result) {
                                $("#FrmProgramSearchDialogDiv").html(result);

                                $("#FrmProgramSearchDialogDiv").dialog(
                                    "option",
                                    "title",
                                    "プログラム検索"
                                );
                                $("#FrmProgramSearchDialogDiv").dialog("open");
                            };
                        },
                    },
                ],
            },
        },
        {
            name: "PRO_NM",
            label: "名称",
            index: "PRO_NM",
            //---20150930 LI UPD S.
            //width : 320,
            //20181118 wangying upd S
            // width : 300,
            width: 290,
            //20181118 wangying upd E
            //---20150930 LI UPD E.
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "CREATE_DATE",
            label: "作成日付",
            index: "CREATE_DATE",
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmMenuKaisou.btnSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmMenuKaisou.btnLogin",
        type: "button",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = イベント start =
    // ==========
    // '**********************************************************************
    // '検索ﾎﾞﾀﾝクリック時
    // '**********************************************************************
    $(".FrmMenuKaisou.btnSearch").click(function () {
        var selectVal = $(".FrmMenuKaisou.UcCboStyleID option:selected").text();

        if (selectVal == "") {
            $(me.grid_id).jqGrid("clearGridData");
            $(".FrmMenuKaisou.btnLogin").button("disable");
            me.clsComFnc.ObjFocus = $(".FrmMenuKaisou.UcCboStyleID");
            me.clsComFnc.FncMsgBox("E9999", "所属ＩＤを選択して下さい。");
            return;
        }

        var data = {
            STYLE_ID: $(".FrmMenuKaisou.UcCboStyleID option:selected").val(),
        };

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "error") {
                $(me.grid_id).jqGrid("clearGridData");
                $(".FrmMenuKaisou.btnLogin").button("disable");
                // $(".FrmMenuKaisou.UcCboStyleID").trigger("focus");
            } else {
                var getDataID = $(me.grid_id).jqGrid("getDataIDs");

                if (getDataID.length == 0) {
                    $(me.grid_id).jqGrid("addRowData", 0, me.addData);
                }

                //スプレッドに取得データをセットする
                me.fncCompleteDeal();

                //１行目を選択状態にする
                $(me.grid_id).jqGrid("setSelection", 0, true);
                $(".FrmMenuKaisou.btnLogin").button("enable");
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    });

    $(".FrmMenuKaisou.UcCboStyleID").change(function () {
        $(me.grid_id).jqGrid("clearGridData");
    });

    // '**********************************************************************
    // '登録処理
    // '**********************************************************************
    $(".FrmMenuKaisou.btnLogin").click(function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

        //入力チェック
        if (me.fncInputChk() == false) {
            me.CloseLoading();
            return;
        } else {
            //確認メッセージ
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDelUpdData;
            me.clsComFnc.MsgBoxBtnFnc.No = me.cancel;
            me.clsComFnc.FncMsgBox("QY010");
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_load = me.load;
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.load = function () {
        base_load();

        var url = me.id + "/fncHMENUSTYLESelect";
        var data = {
            STYLE_ID: "load",
        };

        me.complete_fun = function () {
            var url = me.id + "/fncGetSysNM";

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == true) {
                    me.setSelectValues(result["data"]);
                } else {
                    me.clsComFnc.ObjFocus = $(".FrmMenuKaisou.UcCboStyleID");
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                }

                $(".FrmMenuKaisou.btnLogin").button("disable");

                $(me.grid_id).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        {
                            startColumnName: "PRO_NO",
                            numberOfColumns: 3,
                            titleText: "プログラム",
                        },
                    ],
                });
            };
            me.ajax.send(url, "", 1);
        };

        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option,
            data,
            me.complete_fun
        );
        //---20150804 Yuanjh modify S.
        //gdmz.common.jqgrid.set_grid_width(me.grid_id, 1100);
        //---20150930 LI UPD S.
        //gdmz.common.jqgrid.set_grid_width(me.grid_id, 1200);
        //20201118 wangying upd S
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, 1110);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1024 : 1100
        );
        //20201118 wangying upd E
        //---20150930 LI UPD E.
        //---20150930 LI UPD S.
        //gdmz.common.jqgrid.set_grid_height(me.grid_id, 390);
        //20201118 wangying upd S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 330);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 276 : 337
        );
        $(me.grid_id).jqGrid("bindKeys");
        //20201118 wangying upd E
        //---20150930 LI UPD E.
        //---20150804 Yuanjh modify E.
    };

    me.setSelectValues = function (arrResult) {
        $(".FrmMenuKaisou.UcCboStyleID").empty();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".FrmMenuKaisou.UcCboStyleID");

        for (key in arrResult) {
            if (arrResult[key]["STYLE_NM"] != "") {
                arrResult[key]["STYLE_NM"] = me.clsComFnc.fncGetFixVal(
                    arrResult[key]["STYLE_NM"],
                    18
                );
                $("<option></option>")
                    .val(arrResult[key]["STYLE_ID"])
                    .text(arrResult[key]["STYLE_NM"])
                    .appendTo(".FrmMenuKaisou.UcCboStyleID");
            }
        }

        var tmpId = ".FrmMenuKaisou.UcCboStyleID option[value='" + "" + "']";
        $(tmpId).prop("selected", true);
        $(".FrmMenuKaisou.UcCboStyleID").trigger("focus");
    };

    me.fncCompleteDeal = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                var focusIndex =
                    typeof e != "undefined"
                        ? e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex
                        : false;
                if (typeof e != "undefined") {
                    var cellIndex = e.target.cellIndex;

                    //ヘッダークリック以外
                    if (cellIndex != 0) {
                        if (rowid && rowid != me.lastsel) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );

                            var rowData = $(me.grid_id).jqGrid(
                                "getRowData",
                                me.lastsel
                            );
                            rowData["search"] = "";
                            $(me.grid_id).jqGrid(
                                "setRowData",
                                me.lastsel,
                                rowData
                            );

                            me.lastsel = rowid;
                        }

                        $(me.grid_id).jqGrid("editRow", rowid, {
                            keys: true,
                            focusField: focusIndex,
                        });
                    } else {
                        if (rowid && rowid != me.lastsel) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            me.lastsel = rowid;
                        }
                        $(me.grid_id).jqGrid("editRow", rowid, true);
                    }
                } else {
                    if (rowid && rowid != me.lastsel) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );

                        var rowData = $(me.grid_id).jqGrid(
                            "getRowData",
                            me.lastsel
                        );
                        rowData["search"] = "";
                        $(me.grid_id).jqGrid("setRowData", me.lastsel, rowData);

                        me.lastsel = rowid;
                    }

                    $(me.grid_id).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: me.firstCellFoxcus,
                    });
                    me.firstCellFoxcus = false;
                }

                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });

                var selNextId = "#" + rowid + "_search";
                $(selNextId).button();
            },
        });

        $(me.grid_id).contextMenu("FrmMenuKaisou_columnMenu", {
            bindings: {
                FrmMenuKaisou_MenuInsert: function () {
                    var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
                    $(me.grid_id).jqGrid("saveRow", rowID, null, "clientArray");
                    var rowData = $(me.grid_id).jqGrid("getRowData", rowID);

                    if (
                        rowData["KAISOU_ID1"] != "" ||
                        rowData["KAISOU_ID2"] != "" ||
                        rowData["KAISOU_ID3"] != "" ||
                        rowData["KAISOU_ID4"] != "" ||
                        rowData["KAISOU_ID5"] != "" ||
                        rowData["KAISOU_ID6"] != "" ||
                        rowData["KAISOU_ID7"] != "" ||
                        rowData["KAISOU_ID8"] != "" ||
                        rowData["KAISOU_ID9"] != "" ||
                        rowData["KAISOU_ID10"] != "" ||
                        rowData["KAISOU_NM"] != "" ||
                        rowData["PRO_NO"] != ""
                    ) {
                        me.addBlankRow(rowID);
                    } else {
                        $(me.grid_id).jqGrid("setSelection", rowID, true);
                    }
                },
                FrmMenuKaisou_MenuDelete: function () {
                    $("#" + me.lastsel + "_KAISOU_ID1").trigger("focus");

                    //削除確認メッセージを表示する
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                    me.clsComFnc.FncMsgBox(
                        "QY007",
                        parseInt(me.lastsel) + 1 + "行目：メニュー階層マスタ"
                    );
                },
            },
            onContextMenu: function (event /*, menu*/) {
                var rowId = $(event.target).closest("tr.jqgrow").attr("id");

                if (rowId != me.lastsel) {
                    $(me.grid_id).jqGrid("setSelection", rowId, true);
                    $("input,select", event.target).trigger("focus");
                }

                var rowCount = $(me.grid_id).jqGrid("getGridParam", "records");
                if (rowCount <= 0) {
                    return false;
                }
                return true;
            },
        });
    };

    me.addNewRow = function (strCurrentName, strNextName) {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

        var selIRow = parseInt(me.lastsel) + 1;
        var getDataCount = $(me.grid_id).jqGrid("getGridParam", "records");
        var rowData = $(me.grid_id).jqGrid("getRowData", me.lastsel);

        if (strCurrentName == "KAISOU_NM") {
            rowData[strCurrentName] = me.clsComFnc.FncNv(
                rowData[strCurrentName]
            );
        }

        if (selIRow == getDataCount) {
            if (strCurrentName == "PRO_NO") {
                if (me.clsComFnc.FncNz(rowData[strCurrentName]) == 0) {
                    rowData["PRO_NM"] = "";
                    $(me.grid_id).jqGrid("setRowData", me.lastsel, rowData);
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                    $("#" + me.lastsel + "_" + strNextName).trigger("focus");
                } else {
                    me.getProNM(true, -1, "");
                }
            } else {
                if (rowData[strCurrentName] == "") {
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                    $("#" + me.lastsel + "_" + strNextName).trigger("focus");
                } else {
                    //一行追加する
                    me.addCheck(rowData);
                }
            }
            return false;
        }

        //最終列以外又は階層名の列で何も入力されていない又はプログラム№の列で何も入力されていない場合は処理を抜ける
        if (strCurrentName == "PRO_NO") {
            if (me.clsComFnc.FncNz(rowData[strCurrentName]) == 0) {
                rowData["PRO_NM"] = "";
                $(me.grid_id).jqGrid("setRowData", me.lastsel, rowData);
                $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                $("#" + me.lastsel + "_" + strNextName).trigger("focus");
            } else {
                me.getProNM(false, selIRow, "");
            }
        } else {
            if (rowData[strCurrentName] == "") {
                $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                $("#" + me.lastsel + "_" + strNextName).trigger("focus");
            } else {
                //次の行の1列目にフォーカス移動
                me.firstCellFoxcus = true;
                $(me.grid_id).jqGrid("setSelection", selIRow, true);
            }
        }
        return false;
    };

    me.addCheck = function (rowData) {
        if (
            rowData["KAISOU_ID1"] != "" ||
            rowData["KAISOU_ID2"] != "" ||
            rowData["KAISOU_ID3"] != "" ||
            rowData["KAISOU_ID4"] != "" ||
            rowData["KAISOU_ID5"] != "" ||
            rowData["KAISOU_ID6"] != "" ||
            rowData["KAISOU_ID7"] != "" ||
            rowData["KAISOU_ID8"] != "" ||
            rowData["KAISOU_ID9"] != "" ||
            rowData["KAISOU_ID10"] != "" ||
            rowData["KAISOU_NM"] != "" ||
            rowData["PRO_NO"] != ""
        ) {
            $(me.grid_id).jqGrid(
                "addRowData",
                parseInt(me.lastsel) + 1,
                me.addData
            );
            me.firstCellFoxcus = true;
            $(me.grid_id).jqGrid(
                "setSelection",
                parseInt(me.lastsel) + 1,
                true
            );
        } else {
            $(me.grid_id).jqGrid("setSelection", parseInt(me.lastsel), true);
            $("#" + me.lastsel + "_search").trigger("focus");
        }
    };

    me.setColSelection = function (key) {
        //down
        if (key == 40) {
            me.getProNM(false, me.lastsel, "updown");
        }
        //up
        else if (key == 38) {
            me.getProNM(false, me.lastsel, "updown");
        }
        return true;
    };

    me.getProNM = function (bFlagLastRow, selIRow, strFlag) {
        var rowData = $(me.grid_id).jqGrid("getRowData", me.lastsel);

        var url = me.id + "/fncGetProNM";
        if (strFlag == "updown") {
            var data = {
                PRO_NO: $("#" + me.lastsel + "_PRO_NO").val(),
            };
        } else {
            var data = {
                PRO_NO: rowData["PRO_NO"],
            };
        }

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["data"].length <= 0) {
                    rowData["PRO_NM"] = "";
                } else if (rowData["PRO_NO"] == "0") {
                    rowData["PRO_NM"] = "";
                } else if (result["data"].length > 0) {
                    rowData["PRO_NM"] = result["data"][0]["PRO_NM"];
                }

                if (strFlag == "updown") {
                    var proNmValue = rowData["PRO_NM"]
                        ? rowData["PRO_NM"]
                        : " ";
                    $(me.grid_id).jqGrid(
                        "setCell",
                        selIRow,
                        "PRO_NM",
                        proNmValue
                    );
                } else {
                    $(me.grid_id).jqGrid("setRowData", me.lastsel, rowData);
                }

                if (bFlagLastRow) {
                    //一行追加する
                    me.addCheck(rowData);
                } else if (strFlag == "tab") {
                    $(me.grid_id).jqGrid("setSelection", selIRow, true);
                    $("#" + selIRow + "_search").trigger("focus");
                } else if (strFlag == "tabshift") {
                    $(me.grid_id).jqGrid("setSelection", selIRow, true);
                    $("#" + selIRow + "_KAISOU_NM").trigger("focus");
                } else if (strFlag !== "updown") {
                    //次の行の1列目にフォーカス移動
                    me.firstCellFoxcus = true;
                    $(me.grid_id).jqGrid("setSelection", selIRow, true);
                }
            } else if (result["result"] == false) {
                rowData["PRO_NM"] = "";
                if (strFlag == "updown") {
                    $(me.grid_id).jqGrid(
                        "setCell",
                        selIRow,
                        "PRO_NM",
                        rowData["PRO_NM"]
                    );
                    $(me.grid_id).jqGrid("setSelection", selIRow, true);
                } else {
                    $(me.grid_id).jqGrid("setRowData", me.lastsel, rowData);
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                }
                $("#" + me.lastsel + "_PRO_NO").select();
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.addBlankRow = function (rowID) {
        rowID = parseInt(rowID);
        var getDataID = $(me.grid_id).jqGrid("getDataIDs");

        $(me.grid_id).jqGrid("addRowData", getDataID.length, me.addData);

        for (var i = getDataID.length; i > rowID + 1; i--) {
            var rowData = $(me.grid_id).jqGrid("getRowData", i - 1);
            $(me.grid_id).jqGrid("setRowData", i, rowData);
        }

        $(me.grid_id).jqGrid("setRowData", rowID + 1, me.addData);
        $(me.grid_id).jqGrid("setSelection", rowID + 1, true);
    };

    me.delRowData = function () {
        var getDataID = $(me.grid_id).jqGrid("getDataIDs");

        if (getDataID.length == 1) {
            me.ShowLoading();

            if (me.timeoutFlag) {
                clearTimeout(me.timeoutFlag);
            }
            me.timeoutFlag = setTimeout(me.delOneRowData, 100);
        } else {
            me.ShowLoading();

            if (me.timeoutFlag) {
                clearTimeout(me.timeoutFlag);
            }
            me.timeoutFlag = setTimeout(me.delRowDataContent, 300);
        }
    };

    me.ShowLoading = function () {
        $.blockUI({
            css: {
                border: "none",
                padding: "10px",
                backgroundColor: "#fff",
                "-webkit-border-radius": "8px",
                "-moz-border-radius": "8px",
                top: "45%",
                left: "40%",
                color: "#000",
                width: "200px",
            },
            message:
                '<img src="img/1.gif" width="64" height="64" /><br /><B>読み込み中...</B>',
        });
    };

    me.CloseLoading = function () {
        $.unblockUI();
    };

    me.delOneRowData = function () {
        $(me.grid_id).jqGrid("clearGridData");
        $(me.grid_id).jqGrid("addRowData", 0, me.addData);
        $(me.grid_id).jqGrid("setSelection", 0, true);
        me.CloseLoading();
    };

    me.delRowDataContent = function () {
        var getDataID = $(me.grid_id).jqGrid("getDataIDs");
        var getDataIDLength = getDataID.length;
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");

        $(me.grid_id).jqGrid("saveRow", rowID);

        for (var i = parseInt(rowID); i < getDataIDLength - 1; i++) {
            var rowData = $(me.grid_id).jqGrid("getRowData", i + 1);
            $(me.grid_id).jqGrid("setRowData", i, rowData);
        }

        $(me.grid_id).jqGrid("delRowData", getDataIDLength - 1);

        getDataID = $(me.grid_id).jqGrid("getDataIDs");

        if (rowID >= getDataID.length) {
            $(me.grid_id).jqGrid("setSelection", rowID - 1, true);
        } else {
            $(me.grid_id).jqGrid("setSelection", rowID, true);
        }

        me.CloseLoading();
    };

    me.cancel = function () {
        $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
        $("#" + me.lastsel + "_KAISOU_ID1").trigger("focus");
    };

    // '**********************************************************************
    // 'チェック関数
    // '**********************************************************************
    me.fncInputChk = function () {
        me.ShowLoading();

        var selectVal = $(".FrmMenuKaisou.UcCboStyleID option:selected").text();

        if (selectVal == "") {
            me.clsComFnc.ObjFocus = $(".FrmMenuKaisou.UcCboStyleID");
            me.clsComFnc.FncMsgBox("E9999", "所属ＩＤを選択して下さい。");
            return false;
        }
        //明細行なし
        var data = $(me.grid_id).jqGrid("getDataIDs");

        if (data.length == 0) {
            me.clsComFnc.ObjFocus = $(".FrmMenuKaisou.btnSearch");
            me.clsComFnc.FncMsgBox("E9999", "検索ボタンを押下して下さい。");
            return false;
        }

        //デーフォトの値を設定します
        me.getSpreadTable(data);

        for (rowID in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[rowID]);

            //画面[明細情報]階層名称及び画面[明細情報]プログラム（No.）　　が両方入力されている場合はエラー。
            if (
                rowData["KAISOU_NM"].length > 0 &&
                rowData["PRO_NO"].length > 0
            ) {
                $(me.grid_id).jqGrid("setSelection", rowID, true);
                me.clsComFnc.ObjSelect = $("#" + rowID + "_KAISOU_NM");
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "階層名称/プログラム（No.）のどちらか一方しか入力できません。"
                );
                return false;
            }

            if (rowData["KAISOU_NM"].length > 0) {
                if (
                    rowData["KAISOU_ID1"] == "0" ||
                    rowData["KAISOU_ID2"] == "0" ||
                    rowData["KAISOU_ID3"] == "0" ||
                    rowData["KAISOU_ID4"] == "0" ||
                    rowData["KAISOU_ID5"] == "0" ||
                    rowData["KAISOU_ID6"] == "0" ||
                    rowData["KAISOU_ID7"] == "0" ||
                    rowData["KAISOU_ID8"] == "0" ||
                    rowData["KAISOU_ID9"] == "0" ||
                    rowData["KAISOU_ID10"] == "0"
                ) {
                } else {
                    $(me.grid_id).jqGrid("setSelection", rowID, true);
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "階層名称を入力する行は階層ID～階層ID10の中に少なくとも一つは0でなければなりません。"
                    );
                    return false;
                }
            }

            //階層ID～階層ID10までの中で0以外が入力されている場合、左側全てのセルの入力値が同じで自分自身の入力値より小さい入力値の行が存在していなければならない。
            //一つ目
            if (
                rowData["KAISOU_ID1"].length == 0 ||
                rowData["KAISOU_ID1"] == "0"
            ) {
                //フォーカスセット
                $(me.grid_id).jqGrid("setSelection", rowID, true);
                me.clsComFnc.ObjFocus = $("#" + rowID + "_KAISOU_ID1");
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "階層の構成が正しくありません。"
                );
                return false;
            }

            //二つ目チェック:    1   0   1
            var ColCunt = -1;
            for (var i = 1; i < 10; i++) {
                var iNo = me.colModel[i]["index"];

                if (rowData[iNo] == "0") {
                    for (var j = i + 1; j < 10; j++) {
                        var iNoCompare = me.colModel[j]["index"];

                        if (parseInt(rowData[iNoCompare]) > 0) {
                            //フォーカスセット
                            $(me.grid_id).jqGrid("setSelection", rowID, true);
                            me.clsComFnc.ObjSelect = $(
                                "#" + rowID + "_" + iNoCompare
                            );
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "階層の構成が正しくありません。"
                            );
                            return false;
                        }
                    }

                    ColCunt = i;
                    break;
                }
            }

            //三つ目:    1   1   0
            //                1   1   1
            var errorFlag = false;

            for (rowIDCom in data) {
                if (rowIDCom != rowID) {
                    var findFlag = false;
                    var rowDataCom = $(me.grid_id).jqGrid(
                        "getRowData",
                        data[rowIDCom]
                    );

                    for (var i = 0; i < 10; i++) {
                        var iNo = me.colModel[i]["index"];

                        if (rowData[iNo] != rowDataCom[iNo]) {
                            findFlag = true;
                            break;
                        }
                    }

                    if (!findFlag) {
                        errorFlag = true;
                        break;
                    }
                }
            }

            if (errorFlag) {
                //フォーカスセット
                $(me.grid_id).jqGrid("setSelection", rowIDCom, true);
                me.clsComFnc.ObjFocus = $("#" + rowIDCom + "_KAISOU_ID1");
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "階層の構成が正しくありません。"
                );
                return false;
            }

            if (ColCunt > 1) {
                var findedDr = new Array();

                for (rowIDCom in data) {
                    var rowDataCom = $(me.grid_id).jqGrid(
                        "getRowData",
                        data[rowIDCom]
                    );

                    if (
                        rowData[me.colModel[0]["index"]] ==
                        rowDataCom[me.colModel[0]["index"]]
                    ) {
                        var i = 1;

                        for (; i <= ColCunt - 2; i++) {
                            var iNo = me.colModel[i]["index"];

                            if (rowData[iNo] != rowDataCom[iNo]) {
                                break;
                            }
                        }

                        if (i > ColCunt - 2) {
                            for (i = ColCunt - 1; i < 10; i++) {
                                var iNo = me.colModel[i]["index"];

                                if (rowDataCom[iNo] != "0") {
                                    break;
                                }
                            }

                            if (i == 10) {
                                findedDr.push(rowDataCom);
                            }
                        }
                    }
                }

                if (findedDr.length == 0) {
                    //フォーカスセット
                    $(me.grid_id).jqGrid("setSelection", rowID, true);
                    me.clsComFnc.ObjSelect = $(
                        "#" + rowID + "_" + me.colModel[ColCunt - 1]["index"]
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "階層の構成が正しくありません。"
                    );
                    return false;
                } else if ($.trim(findedDr[0]["KAISOU_NM"]) == "") {
                    //フォーカスセット
                    $(me.grid_id).jqGrid("setSelection", rowID, true);
                    me.clsComFnc.ObjSelect = $(
                        "#" + rowID + "_" + me.colModel[ColCunt - 1]["index"]
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "階層の構成が正しくありません。"
                    );
                    return false;
                }
            }

            //画面[明細情報]プログラム（No.）が入力されている場合、左側のセルが０以外でなければならない。
            //2014.8.8 cancel
            // if (rowData['PRO_NO'].length > 0)
            // {
            // var i = 0;
            //
            // for (; i < 10; i++)
            // {
            // var iNo = me.colModel[i]['index'];
            //
            // if (rowDataCom[iNo] != "0")
            // {
            // break;
            // }
            // }
            //
            // if (i == 10)
            // {
            // //フォーカスセット
            // $(me.grid_id).jqGrid('setSelection', rowID, true);
            // me.clsComFnc.FncMsgBox("E9999", "プログラムNoを入力している行の構成が正しくありません。");
            // return false;
            // }
            // }

            //画面[明細情報]階層名称及び画面[明細情報]プログラム（No.）　　が両方入力されていない場合はエラー。
            if (
                rowData["KAISOU_NM"].length == 0 &&
                rowData["PRO_NO"].length == 0
            ) {
                //フォーカスセット
                $(me.grid_id).jqGrid("setSelection", rowID, true);
                me.clsComFnc.ObjSelect = $("#" + rowID + "_KAISOU_NM");
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "階層名称/プログラム（No.）のどちらか一方を入力して下さい。"
                );
                return false;
            }

            //階層名称バイト数チェック
            var intByteCount = me.GetByteCount(rowData["KAISOU_NM"]);

            if (me.colModel[10]["editoptions"]["maxlength"] < intByteCount) {
                //---桁数異常---
                //フォーカスセット
                $(me.grid_id).jqGrid("setSelection", rowID, true);
                me.clsComFnc.ObjSelect = $("#" + rowID + "_KAISOU_NM");
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "階層名称は入力可能な文字数を超えています！"
                );
                return false;
            }
        }

        me.CloseLoading();
        return true;
    };

    //**********************************************************************
    //処　理　名：	バイト数取得
    //関　数　名：	GetByteCount
    //引　　　数：	str　　　　			文字列
    //戻　り　値：　	バイト数
    //処理説明：	バイト数取得。
    //**********************************************************************
    me.GetByteCount = function (str) {
        var bytesCount = 0;

        var uFF61 = parseInt("FF61", 16);
        var uFF9F = parseInt("FF9F", 16);
        var uFFE8 = parseInt("FFE8", 16);
        var uFFEE = parseInt("FFEE", 16);

        if (str != null) {
            for (var i = 0; i < str.length; i++) {
                var c = parseInt(str.charCodeAt(i));
                if (c < 256) {
                    bytesCount = bytesCount + 1;
                } else {
                    if (uFF61 <= c && c <= uFF9F) {
                        bytesCount = bytesCount + 1;
                    } else if (uFFE8 <= c && c <= uFFEE) {
                        bytesCount = bytesCount + 1;
                    } else {
                        bytesCount = bytesCount + 2;
                    }
                }
            }
        }
        return bytesCount;
    };

    me.getSpreadTable = function (data) {
        for (rowID in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[rowID]);

            for (colID in rowData) {
                if (colID == "KAISOU_NM") {
                    break;
                }

                if (rowData[colID] == "") {
                    rowData[colID] = "0";
                }
            }

            $(me.grid_id).jqGrid("setRowData", rowID, rowData);
        }
    };

    me.fncDelUpdData = function () {
        var arrInputData = new Array();
        var data = $(me.grid_id).jqGrid("getDataIDs");

        for (key in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[key]);
            arrInputData.push(rowData);
        }

        var url = me.id + "/fncDelUpdData";
        var sendData = {
            style_id: $(".FrmMenuKaisou.UcCboStyleID option:selected").val(),
            inputData: arrInputData,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                var url = me.id + "/fncGetSysNM";

                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");

                    if (result["result"] == true) {
                        me.setSelectValues(result["data"]);
                    } else {
                        me.clsComFnc.ObjFocus = $(
                            ".FrmMenuKaisou.UcCboStyleID"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    }

                    $(".FrmMenuKaisou.btnLogin").button("disable");
                    $(me.grid_id).jqGrid("clearGridData");
                };

                me.ajax.send(url, "", 0);
            }
        };
        me.ajax.send(url, sendData, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    o_R4_FrmMenuKaisou = new R4.FrmMenuKaisou();
    o_R4_FrmMenuKaisou.load();
});
