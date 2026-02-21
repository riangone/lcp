/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20230801           機能変更　　　データを更新する後、選択行を保持しておく            lujunxia
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDKuruMente");

HMAUD.HMAUDKuruMente = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDKuruMente";
    me.HMAUD = new HMAUD.HMAUD();

    // jqgrid
    me.grid_id = "#HMAUDKuruMente_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/pageload";
    me.lastsel = 0;
    me.initData = [];
    //20230801 lujunxia ins s
    //select the last row
    me.firstload = true;
    me.scrollPosition = 0;
    //20230801 lujunxia ins e

    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: false,
    };
    me.colModel = [
        {
            name: "COURS",
            label: "クール",
            index: "COURS",
            width: 60,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "11",
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
                    {
                        type: "keyup",
                        fn: function () {
                            var tmptxt = $(this).val();
                            $(this).val(tmptxt.replace(/\D/g, ""));
                        },
                    },
                ],
            },
        },
        {
            name: "START_DT",
            label: "開始日",
            index: "START_DT",
            width: 130,
            align: "center",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width",
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function () {
                            //当前id
                            var nowId = this.parentElement.parentElement.id;
                            var date = this.value;
                            if (!me.dateCheck(date) && date !== "") {
                                this.value = me.initData[nowId]
                                    ? me.initData[nowId]["cell"]["START_DT"]
                                    : "";
                            }
                        },
                    },
                ],
                dataInit: function (elem) {
                    $(elem).datepicker({
                        changeYear: true,
                        changeMonth: true,
                        showButtonPanel: true,
                        onSelect: function () {
                            $(this).trigger("change");
                        },
                    });
                },
            },
        },
        {
            name: "END_DT",
            label: "終了日",
            index: "END_DT",
            width: 130,
            align: "center",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width",
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function () {
                            //当前id
                            var nowId = this.parentElement.parentElement.id;
                            var date = this.value;
                            if (!me.dateCheck(date) && date !== "") {
                                this.value = me.initData[nowId]
                                    ? me.initData[nowId]["cell"]["END_DT"]
                                    : "";
                            }
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Enter,Tab
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter
                                var selIRow = parseInt(me.lastsel) + 1;
                                var DataIDs = $(me.grid_id).jqGrid(
                                    "getDataIDs"
                                );
                                var rowcount = DataIDs.length;
                                if (selIRow >= rowcount) {
                                    var idCours =
                                        "#" + me.lastsel + "_" + "COURS";
                                    var vaCours = $.trim($(idCours).val());
                                    var idDateS =
                                        "#" + me.lastsel + "_" + "START_DT";
                                    var vaDateS = $.trim($(idDateS).val());
                                    var idDateE =
                                        "#" + me.lastsel + "_" + "END_DT";
                                    var vaDateE = $.trim($(idDateE).val());
                                    //添加了回车和Tab的判断区别操作
                                    if (key == 13) {
                                        if (
                                            vaCours != "" &&
                                            vaDateS != "" &&
                                            vaDateE != ""
                                        ) {
                                            $(me.grid_id).jqGrid(
                                                "saveRow",
                                                me.lastsel,
                                                null,
                                                "clientArray"
                                            );

                                            me.colomn = {
                                                COURS: "",
                                                START_DT: "",
                                                END_DT: "",
                                            };

                                            $(me.grid_id).jqGrid(
                                                "addRowData",
                                                selIRow,
                                                me.colomn
                                            );
                                            //保持新追加的行为编辑状态
                                            $(me.grid_id).jqGrid(
                                                "setSelection",
                                                selIRow,
                                                true
                                            );
                                        }
                                    }
                                }
                            }
                        },
                    },
                ],
                dataInit: function (elem) {
                    $(elem).datepicker({
                        changeYear: true,
                        changeMonth: true,
                        showButtonPanel: true,
                        onSelect: function () {
                            $(this).trigger("change");
                        },
                    });
                },
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDKuruMente.button",
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
    // = 宣言 end
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //更新ボタンクリック
    $(".HMAUDKuruMente.btnLogin").click(function () {
        if (!me.inputCheck()) {
            return;
        }
        //20230801 lujunxia ins s
        //scroll position:after update the position can not changed
        me.scrollPosition = $(me.grid_id)
            .closest(".ui-jqgrid-bdiv")
            .scrollTop();
        //20230801 lujunxia ins s
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.loginData;
        me.clsComFnc.MsgBoxBtnFnc.No = me.selectRow;
        me.clsComFnc.FncMsgBox("QY010");
    });
    //実績入力ボタンクリック
    $(".HMAUDKuruMente.btnCancel").click(function () {
        //20230801 lujunxia ins s
        //select the last row
        me.firstload = true;
        //20230801 lujunxia ins s
        me.Page_Load();
    });
    //ウインドウサイズ変更時にグリッドの大きさも追従
    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.Page_Load = function () {
        $.jgrid.gridUnload(me.grid_id);
        gdmz.common.jqgrid.showWithMesgScroll(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
            {},
            me.complete_fun
        );
        $(me.grid_id).jqGrid("bindKeys");
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 355);
        me.setTableSize();
    };
    me.complete_fun = function (_returnFLG, data) {
        if (data["error"] && data["error"] !== "") {
            $(".HMAUDKuruMente.btnLogin").button("disable");
            if (data["error"] == "W0024") {
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                me.clsComFnc.FncMsgBox("E9999", data["error"]);
            }
            return;
        } else {
            $(".HMAUDKuruMente.btnLogin").button("enable");
        }
        me.initData = data["rows"];
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                $("#ui-datepicker-div").css("display", "none");
                var cellIndex = false;
                if (typeof e != "undefined") {
                    //編集可能なセルをクリック、上下キー
                    cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }
                } else {
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }
                }
                $(me.grid_id).jqGrid("editRow", rowid, {
                    keys: false,
                    focusField: cellIndex === 0 ? true : cellIndex,
                });

                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });

                //键盘事件
                var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    me.lastsel
                );
                if (up_next_sel && up_next_sel.length == 2) {
                    me.upsel = up_next_sel[0];
                    me.nextsel = up_next_sel[1];
                }

                $(me.grid_id).find(".width").css("width", "91%");
                $(me.grid_id).find(".overflow").css("overflow", "hidden");
            },
        });
        me.colomn = {
            COURS: "",
            START_DT: "",
            END_DT: "",
        };
        var selIRow = me.initData.length;

        $(me.grid_id).jqGrid("addRowData", selIRow, me.colomn);
        //20230721 lujunxia upd s
        if (me.firstload == true || me.lastsel > selIRow) {
            //[not update] or [delate data caused the row number to exceed]:select the last row
            //保持新追加的行为编辑状态
            $(me.grid_id).jqGrid("setSelection", selIRow, true);
        }
        me.firstload = false;
        //20230721 lujunxia upd e
    };
    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDKuruMente fieldset").width()
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDKuruMente.buttonClass").height();
        var fieldsetHeight = $(".HMAUDKuruMente fieldset").height();
        var tableHeight = mainHeight - buttonHeight - fieldsetHeight - 88;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - buttonHeight - fieldsetHeight - 90;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };
    // //'**********************************************************************
    // //'処 理 名：検索ボタンクリック
    // //'関 数 名：btnSearch_Click
    // //'引    数：無し
    // //'戻 り 値：無し
    // //'処理説明：検索ボタンクリック
    // //'**********************************************************************
    // me.btnSearch_Click = function()
    // {
    // gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    // };
    me.dateCheck = function (date) {
        var patrn =
            /^[1-9]\d{3}(-|\/)(0[1-9]|1[0-2])(-|\/)(0[1-9]|[1-2][0-9]|3[0-1])$/;
        if (!patrn.test($.trim(date))) {
            return false;
        }
        return true;
    };
    me.inputCheck = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var gridDatas = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < gridDatas.length; i++) {
            if (
                gridDatas[i]["COURS"] == "" &&
                gridDatas[i]["START_DT"] == "" &&
                gridDatas[i]["END_DT"] == ""
            ) {
                continue;
            }
            if (gridDatas[i]["COURS"] == "") {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_COURS");
                me.clsComFnc.FncMsgBox("W0017", "クール");
                return false;
            }
            if (gridDatas[i]["START_DT"] == "") {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_START_DT");
                me.clsComFnc.FncMsgBox("W0017", "開始日");
                return false;
            }
            if (!me.dateCheck(gridDatas[i]["START_DT"])) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_START_DT");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "開始日「YYYY/MM/DD」書式のようにご入力ください。"
                );
                return false;
            }
            if (gridDatas[i]["END_DT"] == "") {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_END_DT");
                me.clsComFnc.FncMsgBox("W0017", "終了日");
                return false;
            }
            if (!me.dateCheck(gridDatas[i]["END_DT"])) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_END_DT");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "終了日「YYYY/MM/DD」書式のようにご入力ください。"
                );
                return false;
            }
            var startDt_i = new Date(gridDatas[i]["START_DT"]);
            var endDt_i = new Date(gridDatas[i]["END_DT"]);
            //開始日～終了日大小チェック。
            if (startDt_i > endDt_i) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_START_DT");
                me.clsComFnc.FncMsgBox("W0006", "開始日と終了日");
                return false;
            }
            if (parseInt(gridDatas[i]["COURS"]) > 12) {
                var startDt_i_y = startDt_i.getFullYear();
                var startDt_i_m = startDt_i.getMonth() + 1;
                var endDt_i_y = endDt_i.getFullYear();
                var endDt_i_m = endDt_i.getMonth() + 1;
                if (
                    endDt_i_y * 12 +
                        endDt_i_m -
                        (startDt_i_y * 12 + startDt_i_m) !==
                    5
                ) {
                    $(me.grid_id).jqGrid("setSelection", i, true);
                    me.clsComFnc.ObjSelect = $("#" + i + "_START_DT");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "開始日～終了日期間は6ヶ月ではありません！"
                    );
                    return false;
                }
            }
            for (var j = i + 1; j < gridDatas.length; j++) {
                if (
                    gridDatas[j]["COURS"] == "" &&
                    gridDatas[j]["START_DT"] == "" &&
                    gridDatas[j]["END_DT"] == ""
                ) {
                    continue;
                }
                var startDt_j = new Date(gridDatas[j]["START_DT"]);
                var endDt_j = new Date(gridDatas[j]["END_DT"]);
                //クール重複チェック。
                if (gridDatas[i]["COURS"] == gridDatas[j]["COURS"]) {
                    focusRow = me.initData[i]
                        ? me.initData[i]["cell"]["COURS"] !==
                          gridDatas[i]["COURS"]
                            ? i
                            : j
                        : j;
                    $(me.grid_id).jqGrid("setSelection", focusRow, true);
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "クール値が重複しています！"
                    );
                    return false;
                }
                //期間重複チェック。
                if (startDt_i <= startDt_j && startDt_j <= endDt_i) {
                    focusRow = me.initData[i]
                        ? me.initData[i]["cell"]["START_DT"] !==
                              gridDatas[i]["START_DT"] ||
                          me.initData[i]["cell"]["END_DT"] !==
                              gridDatas[i]["END_DT"]
                            ? i
                            : j
                        : j;
                    $(me.grid_id).jqGrid("setSelection", focusRow, true);
                    me.clsComFnc.ObjSelect = $("#" + focusRow + "_START_DT");
                    me.clsComFnc.FncMsgBox("W9999", "開始日～終了日期間重複！");
                    return false;
                }
                if (startDt_i <= endDt_j && endDt_j <= endDt_i) {
                    focusRow = me.initData[i]
                        ? me.initData[i]["cell"]["START_DT"] !==
                              gridDatas[i]["START_DT"] ||
                          me.initData[i]["cell"]["END_DT"] !==
                              gridDatas[i]["END_DT"]
                            ? i
                            : j
                        : j;
                    $(me.grid_id).jqGrid("setSelection", focusRow, true);
                    me.clsComFnc.ObjSelect = $("#" + focusRow + "_END_DT");
                    me.clsComFnc.FncMsgBox("W9999", "開始日～終了日期間重複！");
                    return false;
                }
                if (startDt_j <= startDt_i && endDt_i <= endDt_j) {
                    focusRow = me.initData[i]
                        ? me.initData[i]["cell"]["START_DT"] !==
                              gridDatas[i]["START_DT"] ||
                          me.initData[i]["cell"]["END_DT"] !==
                              gridDatas[i]["END_DT"]
                            ? i
                            : j
                        : j;
                    $(me.grid_id).jqGrid("setSelection", focusRow, true);
                    me.clsComFnc.ObjSelect = $("#" + focusRow + "_START_DT");
                    me.clsComFnc.FncMsgBox("W9999", "開始日～終了日期間重複！");
                    return false;
                }
                if (
                    gridDatas[j]["START_DT"].substring(0, 7) ==
                        gridDatas[i]["END_DT"].substring(0, 7) ||
                    gridDatas[j]["END_DT"].substring(0, 7) ==
                        gridDatas[i]["START_DT"].substring(0, 7)
                ) {
                    focusRow = me.initData[i]
                        ? me.initData[i]["cell"]["START_DT"] !==
                              gridDatas[i]["START_DT"] ||
                          me.initData[i]["cell"]["END_DT"] !==
                              gridDatas[i]["END_DT"]
                            ? i
                            : j
                        : j;
                    $(me.grid_id).jqGrid("setSelection", focusRow, true);
                    me.clsComFnc.ObjSelect = $("#" + focusRow + "_START_DT");
                    me.clsComFnc.FncMsgBox("W9999", "開始日～終了日期間重複！");
                    return false;
                }
            }
        }

        return true;
    };
    me.loginData = function () {
        var griddata = $(me.grid_id).jqGrid("getRowData");

        var data = {
            data: griddata,
        };
        var url = me.sys_id + "/" + me.id + "/updData";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                //20230801 lujunxia ins s
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id)
                        .closest(".ui-jqgrid-bdiv")
                        .scrollTop(me.scrollPosition);
                    //select the selected row
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                };
                //20230801 lujunxia ins e
                me.clsComFnc.FncMsgBox("I0008");
                me.Page_Load();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.selectRow = function () {
        $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
        setTimeout(function () {
            $("#" + me.lastsel + "_COURS").trigger("focus");
        }, 0);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDKuruMente = new HMAUD.HMAUDKuruMente();
    o_HMAUD_HMAUDKuruMente.load();
    o_HMAUD_HMAUD.HMAUDKuruMente = o_HMAUD_HMAUDKuruMente;
});
