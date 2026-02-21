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

Namespace.register("HMAUD.HMAUDSKDScheduleLimit");

HMAUD.HMAUDSKDScheduleLimit = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDSKDScheduleLimit";
    me.HMAUD = new HMAUD.HMAUD();

    // jqgrid
    me.grid_id = "#HMAUDSKDScheduleLimit_tblMain";
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
            name: "SYAIN_NO",
            label: "監査人",
            index: "SYAIN_NO",
            width: 130,
            align: "right",
            sortable: false,
            editable: false,
        },
        {
            name: "SYAIN_NAME",
            label: "氏名",
            index: "SYAIN_NAME",
            width: 150,
            sortable: false,
            editable: false,
        },
        {
            name: "LIMIT_DT",
            label: "監査日程調整期限",
            index: "LIMIT_DT",
            width: 130,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function () {
                            var nowId = this.parentElement.parentElement.id;
                            var date = this.value;
                            if (!me.dateCheck(date) && date !== "") {
                                this.value = me.initData[nowId]
                                    ? me.initData[nowId]["cell"]["LIMIT_DT"]
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
                        dateFormat: "yy/mm/dd",
                        beforeShow: function () {
                            // 获取画面上选择的年月
                            var ym = $(
                                ".HMAUDSKDScheduleLimit.ymSearchInput"
                            ).val();
                            if (ym) {
                                var parts = ym.split("/");
                                var year = parseInt(parts[0], 10);
                                var month = parseInt(parts[1], 10) - 1;
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
                        onSelect: function () {
                            $(this).trigger("change");
                        },
                    });
                },
            },
        },
        {
            name: "INPUT_STATUS",
            label: "入力状態",
            index: "INPUT_STATUS",
            width: 65,
            sortable: false,
            editable: false,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDSKDScheduleLimit.button",
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
    $(".HMAUDSKDScheduleLimit.coursSearchInput").change(function () {
        $(me.grid_id).closest(".ui-jqgrid-bdiv").scrollTop(0);
        me.fncCourChange();
        me.jqgrid_reload();
    });
    $(".HMAUDSKDScheduleLimit.ymSearchInput").change(function () {
        $(me.grid_id).closest(".ui-jqgrid-bdiv").scrollTop(0);
        me.jqgrid_reload();
    });
    //更新ボタンクリック
    $(".HMAUDSKDScheduleLimit.btnLogin").click(function () {
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
    // 最新情報を表示ボタンクリック
    $(".HMAUDSKDScheduleLimit.cmdDisp").click(function () {
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
                cour: $(".HMAUDSKDScheduleLimit.coursSearchInput").val(),
                ymDate: $(".HMAUDSKDScheduleLimit.ymSearchInput").val()
                    ? $(".HMAUDSKDScheduleLimit.ymSearchInput").val()
                    : new Date().getFullYear() +
                      "/" +
                      ("0" + (new Date().getMonth() + 1)).slice(-2),
            },
            me.complete_fun
        );
        $(me.grid_id).jqGrid("bindKeys");
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 520);
        me.setTableSize();
    };
    me.complete_fun = function (_returnFLG, data) {
        if (data["error"] && data["error"] !== "") {
            $(".HMAUDSKDScheduleLimit.btnLogin").button("disable");
            if (data["error"] == "W0024") {
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                me.clsComFnc.FncMsgBox("E9999", data["error"]);
            }
            return;
        } else {
            $(".HMAUDSKDScheduleLimit.btnLogin").button("enable");
        }
        me.initData = data["rows"];
        if (me.firstload == true) {
            if (data["cour"] && data["cour"].length > 0) {
                $(".HMAUDSKDScheduleLimit.coursSearchInput")
                    .find("option")
                    .remove();
                var courAll = data["cour"];
                me.allCourData = courAll;
                for (var i = 0; i < courAll.length; i++) {
                    //クールselect
                    $("<option></option>")
                        .val(courAll[i]["COURS"])
                        .text(courAll[i]["COURS"])
                        .appendTo(".HMAUDSKDScheduleLimit.coursSearchInput");
                    if (courAll[i]["COURS_NOW"] == "1") {
                        //現在のクール数
                        me.gennzayiCour = courAll[i]["COURS"];
                    }
                }
                //検索条件・クールには 現在のクール数を初期表示
                $(".HMAUDSKDScheduleLimit.coursSearchInput").val(
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
        var cour = $(".HMAUDSKDScheduleLimit.coursSearchInput").val();

        var foundDT = undefined;
        if (me.allCourData) {
            var foundDT_array = me.allCourData.filter(function (element) {
                return element["COURS"] == cour;
            });
            if (foundDT_array.length > 0) {
                foundDT = foundDT_array[0];
            }
            $(".HMAUDSKDScheduleLimit.courPeriod").text(
                foundDT ? foundDT["PERIOD"] : ""
            );
        }
        me.setYmSelectByCourPeriod();
    };
    me.setYmSelectByCourPeriod = function () {
        var courPeriod = $(".HMAUDSKDScheduleLimit.courPeriod").text().trim();
        if (!courPeriod || courPeriod.indexOf("～") === -1) return;

        var startStr = courPeriod.split("～")[0].trim();
        var endStr = courPeriod.split("～")[1].trim();

        var startDate = new Date(startStr);
        var endDate = new Date(endStr);

        endDate.setDate(1);
        endDate.setMonth(endDate.getMonth() + 1);
        endDate.setDate(0);

        var $ymSelect = $(".HMAUDSKDScheduleLimit.ymSearchInput");
        $ymSelect.empty();

        // 逐月循环
        var cur = new Date(startDate);
        while (cur <= endDate) {
            var ym =
                cur.getFullYear() +
                "/" +
                ("0" + (cur.getMonth() + 1)).slice(-2);
            $ymSelect.append($("<option></option>").val(ym).text(ym));
            cur.setMonth(cur.getMonth() + 1);
        }

        // 默认显示当前月
        var today = new Date();
        var ymToday =
            today.getFullYear() +
            "/" +
            ("0" + (today.getMonth() + 1)).slice(-2);

        if ($ymSelect.find(`option[value='${ymToday}']`).length > 0) {
            $ymSelect.val(ymToday);
        } else {
            // 如果不在范围内，默认选第一个
            $ymSelect.val($ymSelect.find("option:first").val());
        }
    };

    me.jqgrid_reload = function () {
        gdmz.common.jqgrid.reloadMessage(
            me.grid_id,
            {
                cour: $(".HMAUDSKDScheduleLimit.coursSearchInput").val(),
                ymDate: $(".HMAUDSKDScheduleLimit.ymSearchInput").val(),
            },
            me.complete_fun,
            me.page,
            true
        );
    };
    me.setTableSize = function () {
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDSKDScheduleLimit.buttonClass").height();
        var fieldsetHeight = $(".HMAUDSKDScheduleLimit fieldset").height();
        var h = me.ratio === 1.5 ? 105 : 88;
        var tableHeight = mainHeight - buttonHeight - fieldsetHeight - h;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - buttonHeight - fieldsetHeight - 90;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
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
        }
        return true;
    };
    me.loginData = function () {
        var griddata = $(me.grid_id).jqGrid("getRowData");

        var data = {
            data: griddata,
            cour: $(".HMAUDSKDScheduleLimit.coursSearchInput").val(),
            ym: $(".HMAUDSKDScheduleLimit.ymSearchInput").val(),
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
    var o_HMAUD_HMAUDSKDScheduleLimit = new HMAUD.HMAUDSKDScheduleLimit();
    o_HMAUD_HMAUDSKDScheduleLimit.load();
    o_HMAUD_HMAUD.HMAUDSKDScheduleLimit = o_HMAUD_HMAUDSKDScheduleLimit;
});
