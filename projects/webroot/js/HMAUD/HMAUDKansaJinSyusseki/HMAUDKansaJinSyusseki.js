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

Namespace.register("HMAUD.HMAUDKansaJinSyusseki");

HMAUD.HMAUDKansaJinSyusseki = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDKansaJinSyusseki";
    me.HMAUD = new HMAUD.HMAUD();
    me.SessionUserId = gdmz.SessionUserId;
    me.isAdmin = false;

    // jqgrid
    me.grid_id = "#HMAUDKansaJinSyusseki_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/pageload";
    me.lastsel = 0;
    me.initData = [];
    me.holidayCols = [];
    //select the last row
    me.scrollPosition = 0;

    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: false,
        shrinkToFit: true,
        hoverrows: false,
    };
    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "",
            index: "SYAIN_NO",
            align: "center",
            hidden: true,
        },
        {
            name: "SYAIN_NAME",
            label: "監査人",
            index: "SYAIN_NAME",
            width: 100,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "LIMIT_DT",
            label: "入力期限",
            index: "LIMIT_DT",
            width: 60,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width",

                dataInit: function (elem) {
                    var rowId = $(elem).closest("tr.jqgrow").attr("id");
                    var initVal = me.initData[rowId]["cell"]["LIMIT_DT"];
                    if (initVal) {
                        $(elem).data("full-date", initVal);
                        $(elem).data("old-date", initVal);
                        setTimeout(function () {
                            $(elem).val(initVal.substring(5));
                        }, 0);
                    }
                    $(elem).on("blur", function () {
                        var oldDate = $(elem).data("old-date");
                        var val = $(elem).val();

                        if (!val && oldDate) {
                            me.saveLimitDate(rowId, "", oldDate, function () {
                                $(elem).data("old-date", "");
                                me.initData[rowId]["cell"]["LIMIT_DT"] = "";
                            });
                        }
                    });

                    $(elem).datepicker({
                        changeYear: true,
                        changeMonth: true,
                        showButtonPanel: true,
                        dateFormat: "yy/mm/dd",
                        beforeShow: function () {
                            var full = $(elem).data("full-date");
                            if (full) {
                                $(elem).datepicker("setDate", full);
                            }
                            var ym = $(
                                ".HMAUDKansaJinSyusseki.dateInput"
                            ).val();
                            if (/^\d{6}$/.test(ym)) {
                                var year = parseInt(ym.substring(0, 4), 10);
                                var month =
                                    parseInt(ym.substring(4, 6), 10) - 1;
                                $(elem).datepicker(
                                    "option",
                                    "minDate",
                                    new Date(year, month, 1)
                                );
                                $(elem).datepicker(
                                    "option",
                                    "maxDate",
                                    new Date(year, month + 1, 0)
                                );
                            }
                        },
                        onSelect: function (_dateText, inst) {
                            var year = inst.selectedYear;
                            var month = ("0" + (inst.selectedMonth + 1)).slice(
                                -2
                            );
                            var day = ("0" + inst.selectedDay).slice(-2);
                            var fullDate = year + "/" + month + "/" + day;
                            var oldDate = $(elem).data("old-date");

                            $(elem).data("full-date", fullDate);
                            me.initData[rowId]["cell"]["LIMIT_DT"] = fullDate;

                            $(elem).trigger("change");

                            if (fullDate !== oldDate) {
                                $(elem).val(fullDate.substring(5));
                                me.saveLimitDate(
                                    rowId,
                                    fullDate,
                                    oldDate,
                                    function () {
                                        $(elem).data("old-date", fullDate);
                                    }
                                );
                            }
                        },
                    });
                },
                dataEvents: [
                    {
                        type: "focus",
                        fn: function (e) {
                            setTimeout(function () {
                                var newValue = $(e.target).val();
                                if (/^\d{4}\/\d{2}\/\d{2}$/.test(newValue)) {
                                    $(e.target).val(newValue.substring(5));
                                    $(e.target).select();
                                }
                            }, 0);
                        },
                    },
                ],
            },
            formatter: function (cellValue) {
                if (!cellValue) return "";
                if (/^\d{4}\/\d{2}\/\d{2}$/.test(cellValue)) {
                    return cellValue.substring(5);
                }

                return cellValue;
            },
        },
        {
            name: "AM_PM",
            label: " ",
            index: "",
            width: 50,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "SEQ",
            label: "",
            index: "SEQ",
            align: "center",
            hidden: true,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDKansaJinSyusseki.button",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMAUDKansaJinSyusseki.dateInput",
        type: "datepicker3",
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
    //検索ボタンクリック
    $(".HMAUDKansaJinSyusseki.btnSearch").click(function () {
        // me.btnSearch_Click();
        me.jqgrid_reload();
    });
    //更新ボタンクリック
    $(".HMAUDKansaJinSyusseki.btnLogin").click(function () {
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
        $(".HMAUDKansaJinSyusseki.dateInput").val(new Date().Format("yyyyMM"));
        const ym = $(".HMAUDKansaJinSyusseki.dateInput").val();
        me.buildDateColumns(ym);

        $.jgrid.gridUnload(me.grid_id);
        gdmz.common.jqgrid.showWithMesgScroll(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
            {
                ymDate: ym,
            },
            me.complete_fun
        );
        if (me.groupHeaders && me.groupHeaders.length > 0) {
            $(me.grid_id).jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: me.groupHeaders,
            });
        }
        $(me.grid_id).jqGrid("bindKeys");
        me.setTableSize();
    };

    me.complete_fun = function (_returnFLG, data) {
        if (data["error"] && data["error"] !== "") {
            if (data["error"] == "W0024") {
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                me.clsComFnc.FncMsgBox("E9999", data["error"]);
            }
            return;
        }
        me.initData = data["rows"];
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if (data["admin"].length === 0) {
                    return;
                } else {
                    me.isAdmin = true;
                }
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
                    if (cellIndex > 3) {
                        return;
                    }
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }
                } else {
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }
                }
                if (
                    parseInt(rowid, 10) % 2 === 1 &&
                    (cellIndex == 2 || cellIndex == 3)
                ) {
                    $(me.grid_id).jqGrid("editRow", rowid - 1, true);
                    me.lastsel = rowid - 1;
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
        me.mergeRows(["SYAIN_NAME", "LIMIT_DT"]);
        me.paintCols(data["exclude"]);

        // ====== 绑定 d1~dN 单元格双击切换事件 ======
        $(me.grid_id)
            .off(
                "dblclick",
                "td[aria-describedby^='HMAUDKansaJinSyusseki_tblMain_d']"
            )
            .on(
                "dblclick",
                "td[aria-describedby^='HMAUDKansaJinSyusseki_tblMain_d']",
                function () {
                    var $cell = $(this);
                    var rowId = $cell.closest("tr.jqgrow").attr("id");
                    var syainNo = me.initData[rowId]["cell"]["SYAIN_NO"];
                    // ====== 权限判断 start ======
                    if (syainNo !== me.SessionUserId && !me.isAdmin) {
                        return;
                    }
                    // ====== 权限判断 end ======
                    var colName = $cell
                        .attr("aria-describedby")
                        .split("_")
                        .pop();
                    const ym = $(".HMAUDKansaJinSyusseki.dateInput").val();
                    var day = colName.replace(/^d/, "");
                    var planDt =
                        ym.substring(0, 4) +
                        "/" +
                        ym.substring(4, 6) +
                        "/" +
                        day.padStart(2, "0");

                    var val = $cell.text().trim();
                    var newVal;
                    if (val === "〇") newVal = "×";
                    else if (val === "×") newVal = "";
                    else newVal = "〇";

                    var oldVal = val;
                    var sendVal =
                        newVal === "〇" ? 1 : newVal === "×" ? 0 : undefined;
                    me.saveSchedule(
                        rowId,
                        planDt,
                        sendVal,
                        $cell,
                        newVal,
                        oldVal,
                        colName
                    );
                }
            );
    };

    me.buildDateColumns = function (ym) {
        const year = parseInt(ym.substring(0, 4));
        const month = parseInt(ym.substring(4, 6));
        const lastDay = new Date(year, month, 0).getDate();
        me.colModel = me.colModel.filter((col) => !/^d\d+$/.test(col.name));
        me.groupHeaders = [];
        me.holidayCols = [];

        for (let d = 1; d <= lastDay; d++) {
            const yyyyMMdd = `${year}/${String(month).padStart(
                2,
                "0"
            )}/${String(d).padStart(2, "0")}`;
            const dayOfWeek = new Date(yyyyMMdd).getDay();
            const wd = ["日", "月", "火", "水", "木", "金", "土"][dayOfWeek];
            // 曜日＝ 火、土、日は常に入力不可
            if (dayOfWeek === 0 || dayOfWeek === 2 || dayOfWeek === 6) {
                me.holidayCols.push({
                    EXCLUDE_DT: `${year}/${String(month).padStart(
                        2,
                        "0"
                    )}/${String(d).padStart(2, "0")}`,
                });
            }

            me.colModel.push({
                name: "d" + d,
                label: `${wd}`,
                width: 30,
                align: "center",
                formatter: function (cellValue) {
                    if (cellValue === "1" || cellValue === 1) return "〇";
                    else if (cellValue === "0" || cellValue === 0) return "×";
                    else return "";
                },
            });

            me.groupHeaders.push({
                startColumnName: "d" + d,
                numberOfColumns: 1,
                titleText: d.toString(),
            });
        }
    };
    me.mergeRows = function (colNames) {
        var rows = $(me.grid_id).find("tr.jqgrow");
        var lastVals = {};
        var lastSeq = null;
        var rowspans = {};
        var firstCells = {};

        colNames.forEach(function (col) {
            lastVals[col] = undefined;
            rowspans[col] = 1;
            firstCells[col] = null;
        });

        rows.each(function (_, row) {
            var rowId = $(row).attr("id");
            var rowData = $(me.grid_id).jqGrid("getRowData", rowId);

            var seq = rowData["SEQ"] ? rowData["SEQ"].trim() : "";

            colNames.forEach(function (col) {
                var val = rowData[col] ? rowData[col].trim() : "";

                var cell = $(row).find(
                    "td[aria-describedby='" +
                        me.grid_id.substring(1) +
                        "_" +
                        col +
                        "']"
                );

                // 判断是否连续
                if (val === lastVals[col] && seq === lastSeq) {
                    rowspans[col]++;
                    cell.css("display", "none");
                    firstCells[col].attr("rowspan", rowspans[col]);
                } else {
                    lastVals[col] = val;
                    rowspans[col] = 1;
                    firstCells[col] = cell;
                }
            });
            lastSeq = seq;
        });

        $(me.grid_id).jqGrid("setSelection", 0, true);
    };

    me.paintCols = function (excludeDatas) {
        var ids = $(me.grid_id).jqGrid("getDataIDs");

        var allExclude = excludeDatas.concat(me.holidayCols);
        allExclude.forEach(function (item) {
            var day = parseInt(item.EXCLUDE_DT.split("/")[2], 10);
            var colName = "d" + day;

            ids.forEach(function (rowId) {
                var $cell = $(me.grid_id).find(
                    "tr#" +
                        rowId +
                        " td[aria-describedby='HMAUDKansaJinSyusseki_tblMain_" +
                        colName +
                        "']"
                );
                // 设置灰色背景 + 禁止点击
                $cell.css({
                    background: "#D9D9D9",
                    color: "#666",
                    "pointer-events": "none",
                    cursor: "not-allowed",
                    position: "relative",
                });
            });
        });

        $(me.grid_id)
            .on("mouseenter", "td", function () {
                var $cell = $(this);
                var rowId = $cell.closest("tr.jqgrow").attr("id");
                var syainNo = me.initData[rowId]["cell"]["SYAIN_NO"];

                if (syainNo !== me.SessionUserId && !me.isAdmin) {
                    return;
                }

                var colName = $(this).attr("aria-describedby").split("_").pop();
                if (
                    /^d\d+$/.test(colName) &&
                    !$(this).hasClass("forbid-cell")
                ) {
                    $(this).css("background-color", "#E0F7FA");
                }
            })
            .on("mouseleave", "td", function () {
                var $cell = $(this);
                var rowId = $cell.closest("tr.jqgrow").attr("id");
                var syainNo = me.initData[rowId]["cell"]["SYAIN_NO"];

                if (syainNo !== me.SessionUserId && !me.isAdmin) {
                    return;
                }
                var colName = $(this).attr("aria-describedby").split("_").pop();
                if (
                    /^d\d+$/.test(colName) &&
                    !$(this).hasClass("forbid-cell")
                ) {
                    $(this).css("background-color", "");
                }
            });

        $(me.grid_id)
            .find("tr.jqgrow")
            .each(function () {
                $(this).find("td:eq(1), td:eq(3)").css({
                    background: "#DDEBF7",
                    border: "1px solid #77d5f7",
                    color: "#222222",
                    "font-weight": "normal",
                });
            });
    };
    //'**********************************************************************
    //'処 理 名：保存入力期限
    //'関 数 名：saveLimitDate
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：保存入力期限
    //'**********************************************************************
    me.saveLimitDate = function (rowId, limitDate, oldDate, callback) {
        var rowData = $(me.grid_id).jqGrid("getRowData", rowId);
        const ym = $(".HMAUDKansaJinSyusseki.dateInput").val();

        var data = {
            ymDate: ym,
            oldDate: oldDate,
            limitDate: limitDate,
            syainNo: rowData["SYAIN_NO"],
        };
        var url = me.sys_id + "/" + me.id + "/updData";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (typeof callback === "function") {
                    callback();
                }

                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id)
                        .closest(".ui-jqgrid-bdiv")
                        .scrollTop(me.scrollPosition);
                    //select the selected row
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                };
                me.clsComFnc.FncMsgBox("I0008");
                // me.Page_Load();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：保存監査人スケジュール
    //'関 数 名：saveSchedule
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：保存監査人スケジュール
    //'**********************************************************************
    me.saveSchedule = function (
        rowId,
        planDt,
        val,
        $cell,
        newVal,
        oldVal,
        colName
    ) {
        var rowData = $(me.grid_id).jqGrid("getRowData", rowId);
        const ym = $(".HMAUDKansaJinSyusseki.dateInput").val();

        var data = {
            ymDate: ym,
            am_pm: rowData["AM_PM"] == "AM" ? 1 : 2,
            planDt: planDt,
            syainNo: rowData["SYAIN_NO"],
            enabled: val,
        };
        var url = me.sys_id + "/" + me.id + "/updScheduleData";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                $cell.text(newVal);
                me.initData[rowId]["cell"][colName] = newVal;

                if (newVal === "〇") {
                    me.initData[rowId]["cell"][colName + "_real"] = 1;
                } else if (newVal === "×") {
                    me.initData[rowId]["cell"][colName + "_real"] = 0;
                } else {
                    delete me.initData[rowId]["cell"][colName + "_real"];
                }

                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id)
                        .closest(".ui-jqgrid-bdiv")
                        .scrollTop(me.scrollPosition);
                    //select the selected row
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                };
            } else {
                $cell.text(oldVal);
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.jqgrid_reload = function () {
        const ym = $(".HMAUDKansaJinSyusseki.dateInput").val();
        me.buildDateColumns(ym);

        $.jgrid.gridUnload(me.grid_id);

        gdmz.common.jqgrid.showWithMesgScroll(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
            {
                ymDate: ym,
            },
            me.complete_fun
        );

        if (me.groupHeaders && me.groupHeaders.length > 0) {
            $(me.grid_id).jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: me.groupHeaders,
            });
        }

        me.setTableSize();
    };
    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDKansaJinSyusseki fieldset").width()
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDKansaJinSyusseki.buttonClass").height();
        var fieldsetHeight = $(".HMAUDKansaJinSyusseki fieldset").height();
        var h = me.ratio === 1.5 ? 115 : 100;
        var tableHeight = mainHeight - buttonHeight - fieldsetHeight - h;

        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };

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
        var courPeriod = $(".HMAUDSKDScheduleLimit .courPeriod").text().trim();
        var startDate = courPeriod.split("～")[0].trim();
        var endDate = courPeriod.split("～")[1].trim();

        for (var i = 0; i < gridDatas.length; i++) {
            if (gridDatas[i]["LIMIT_DT"] == "") {
                continue;
            }

            if (!me.dateCheck(gridDatas[i]["LIMIT_DT"])) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_LIMIT_DT");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "日付「YYYY/MM/DD」書式のようにご入力ください。"
                );
                return false;
            }

            if (
                !(
                    new Date(startDate) <= new Date(gridDatas[i]["LIMIT_DT"]) &&
                    new Date(gridDatas[i]["LIMIT_DT"]) <= new Date(endDate)
                )
            ) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjSelect = $("#" + i + "_LIMIT_DT");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "入力した日付は設定期間外です。"
                );
                return false;
            }
        }
        return true;
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
    var o_HMAUD_HMAUDKansaJinSyusseki = new HMAUD.HMAUDKansaJinSyusseki();
    o_HMAUD_HMAUDKansaJinSyusseki.load();
    o_HMAUD_HMAUD.HMAUDKansaJinSyusseki = o_HMAUD_HMAUDKansaJinSyusseki;
});
