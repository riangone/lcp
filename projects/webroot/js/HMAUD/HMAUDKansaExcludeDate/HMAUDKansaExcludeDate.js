/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDKansaExcludeDate");

HMAUD.HMAUDKansaExcludeDate = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDKansaExcludeDate";
    me.HMAUD = new HMAUD.HMAUD();

    // jqgrid
    me.grid_id = "#HMAUDKansaExcludeDate_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/pageload";
    me.lastsel = 0;
    me.initData = [];
    //select the last row
    me.firstload = true;
    me.scrollPosition = 0;

    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: false,
    };
    me.colModel = [
        {
            name: "EXCLUDE_DT",
            label: "日付",
            index: "EXCLUDE_DT",
            width: 130,
            align: "right",
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
                                    ? me.initData[nowId]["cell"]["EXCLUDE_DT"]
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
            name: "REMARKS",
            label: "内容",
            index: "REMARKS",
            width: 345,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                dataEvents: [
                    {
                        type: "keyup",
                        fn: function () {
                            let v = $(this).val();
                            let byteLen = new TextEncoder().encode(v).length;
                            const maxBytes = 20;
                            if (byteLen > maxBytes) {
                                while (
                                    new TextEncoder().encode(v).length >
                                    maxBytes
                                ) {
                                    v = v.slice(0, -1);
                                }
                                $(this).val(v);
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
        id: ".HMAUDKansaExcludeDate.button",
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
    $(".HMAUDKansaExcludeDate.coursSearchInput").change(function () {
        $(me.grid_id).closest(".ui-jqgrid-bdiv").scrollTop(0);
        me.fncCourChange();
        me.jqgrid_reload();
    });
    //行追加ボタンクリック
    $(".HMAUDKansaExcludeDate.btnRowAdd").click(function () {
        me.btnRowAdd_Click();
    });
    //更新ボタンクリック
    $(".HMAUDKansaExcludeDate.btnLogin").click(function () {
        if (!me.inputCheck()) {
            return;
        }
        //scroll position:after update the position can not changed
        me.scrollPosition = $(me.grid_id)
            .closest(".ui-jqgrid-bdiv")
            .scrollTop();
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.loginData;
        me.clsComFnc.MsgBoxBtnFnc.No = me.selectRow;
        me.clsComFnc.FncMsgBox("QY010");
    });
    // 再表示ボタンクリック
    $(".HMAUDKansaExcludeDate.cmdDisp").click(function () {
        me.jqgrid_reload();
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
            {
                cour: $(".HMAUDKansaExcludeDate.coursSearchInput").val(),
            },
            me.complete_fun
        );
        $(me.grid_id).jqGrid("bindKeys");
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 500);
        me.setTableSize();
    };
    me.complete_fun = function (_returnFLG, data) {
        if (data["error"] && data["error"] !== "") {
            $(".HMAUDKansaExcludeDate.btnLogin").button("disable");
            if (data["error"] == "W0024") {
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                me.clsComFnc.FncMsgBox("E9999", data["error"]);
                return;
            }
        } else {
            $(".HMAUDKansaExcludeDate.btnLogin").button("enable");
        }

        me.initData = data["rows"] || [];
        if (me.firstload == true) {
            if (data["cour"] && data["cour"].length > 0) {
                $(".HMAUDKansaExcludeDate.coursSearchInput")
                    .find("option")
                    .remove();
                var courAll = data["cour"];
                me.allCourData = courAll;
                for (var i = 0; i < courAll.length; i++) {
                    //クールselect
                    $("<option></option>")
                        .val(courAll[i]["COURS"])
                        .text(courAll[i]["COURS"])
                        .appendTo(".HMAUDKansaExcludeDate.coursSearchInput");
                    if (courAll[i]["COURS_NOW"] == "1") {
                        //現在のクール数
                        me.gennzayiCour = courAll[i]["COURS"];
                    }
                }
                //検索条件・クールには 現在のクール数を初期表示
                $(".HMAUDKansaExcludeDate.coursSearchInput").val(
                    me.gennzayiCour
                );
            }
            //クールchange
            me.fncCourChange();
            me.firstload = false;
        }
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
        if (me.initData.length > 0) {
            $(me.grid_id).jqGrid("setSelection", 0, true);
        }
    };
    me.fncCourChange = function () {
        var cour = $(".HMAUDKansaExcludeDate.coursSearchInput").val();

        var foundDT = undefined;
        if (me.allCourData) {
            var foundDT_array = me.allCourData.filter(function (element) {
                return element["COURS"] == cour;
            });
            if (foundDT_array.length > 0) {
                foundDT = foundDT_array[0];
            }
            $(".HMAUDKansaExcludeDate.courPeriod").text(
                foundDT ? foundDT["PERIOD"] : ""
            );
        }
    };
    me.jqgrid_reload = function () {
        gdmz.common.jqgrid.reloadMessage(
            me.grid_id,
            {
                cour: $(".HMAUDKansaExcludeDate.coursSearchInput").val(),
            },
            me.complete_fun,
            me.page,
            true
        );
    };
    me.setTableSize = function () {
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDKansaExcludeDate.buttonClass").height();
        var fieldsetHeight = $(".HMAUDKansaExcludeDate fieldset").height();
        var h = me.ratio === 1.5 ? 105 : 88;
        var tableHeight = mainHeight - buttonHeight - fieldsetHeight - h;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - buttonHeight - fieldsetHeight - 90;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };
    //'**********************************************************************
    //'処 理 名：行追加ボタンクリック
    //'関 数 名：btnRowAdd_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：行追加ボタンクリック
    //'**********************************************************************
    me.btnRowAdd_Click = function () {
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = 0;
        if (ids.length > 0) {
            rowid = parseInt(ids.pop()) + 1;
        }
        var data = {
            SYAIN_CD: "",
            SYAIN_NAME: "",
        };
        $(me.grid_id).jqGrid("addRowData", rowid, data);
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        $(me.grid_id).jqGrid("editRow", rowid, true);
        $(me.grid_id).jqGrid("setSelection", rowid, true);
        $(".HMAUDKansaExcludeDate.btnLogin").button("enable");
    };
    //'**********************************************************************
    //'処 理 名：検索ボタンクリック
    //'関 数 名：btnSearch_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：検索ボタンクリック
    //'**********************************************************************
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
        var courPeriod = $(".HMAUDKansaExcludeDate .courPeriod").text().trim();
        var startDate = courPeriod.split("～")[0].trim();
        var endDate = courPeriod.split("～")[1].trim();

        for (var i = 0; i < gridDatas.length; i++) {
            if (
                gridDatas[i]["EXCLUDE_DT"] == "" &&
                gridDatas[i]["REMARKS"] == ""
            ) {
                continue;
            }

            if (
                gridDatas[i]["EXCLUDE_DT"] == "" &&
                gridDatas[i]["REMARKS"] !== ""
            ) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_EXCLUDE_DT");
                me.clsComFnc.FncMsgBox("W0017", "日付");
                return false;
            }

            if (!me.dateCheck(gridDatas[i]["EXCLUDE_DT"])) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_EXCLUDE_DT");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "日付「YYYY/MM/DD」書式のようにご入力ください。"
                );
                return false;
            }

            if (
                !(
                    new Date(startDate) <=
                        new Date(gridDatas[i]["EXCLUDE_DT"]) &&
                    new Date(gridDatas[i]["EXCLUDE_DT"]) <= new Date(endDate)
                )
            ) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_EXCLUDE_DT");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "入力した日付は設定期間外です。"
                );
                return false;
            }

            for (var j = i + 1; j < gridDatas.length; j++) {
                //日付重複チェック。
                if (gridDatas[i]["EXCLUDE_DT"] == gridDatas[j]["EXCLUDE_DT"]) {
                    focusRow = me.initData[i]
                        ? me.initData[i]["cell"]["EXCLUDE_DT"] !==
                          gridDatas[i]["EXCLUDE_DT"]
                            ? i
                            : j
                        : j;
                    $(me.grid_id).jqGrid("setSelection", focusRow, true);
                    me.clsComFnc.FncMsgBox("W9999", "日付値が重複しています！");
                    return false;
                }
            }
        }
        return true;
    };
    me.loginData = function () {
        var griddata = $(me.grid_id).jqGrid("getRowData");
        var courPeriod = $(".HMAUDKansaExcludeDate .courPeriod").text().trim();
        var startDate = courPeriod.split("～")[0].trim();
        var endDate = courPeriod.split("～")[1].trim();

        var data = {
            data: griddata,
            startDate: startDate,
            endDate: endDate,
        };
        var url = me.sys_id + "/" + me.id + "/updData";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id)
                        .closest(".ui-jqgrid-bdiv")
                        .scrollTop(me.scrollPosition);
                    //select the selected row
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                };
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
    var o_HMAUD_HMAUDKansaExcludeDate = new HMAUD.HMAUDKansaExcludeDate();
    o_HMAUD_HMAUDKansaExcludeDate.load();
    o_HMAUD_HMAUD.HMAUDKansaExcludeDate = o_HMAUD_HMAUDKansaExcludeDate;
});
