Namespace.register("HMAUD.HMAUDKansaJinMente");

HMAUD.HMAUDKansaJinMente = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDKansaJinMente";
    me.HMAUD = new HMAUD.HMAUD();
    me.lastsel = 0;
    me.loadGridRowCnt = 0;
    me.allSyainName = null;

    me.grid_id = "#HMAUDKansaJinMente_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.lastsel = 0;
    me.option = {
        rownumbers: true,
        rownumWidth: 40,
        rowNum: 0,
        caption: "",
        multiselect: false,
    };

    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "監査人",
            index: "SYAIN_NO",
            width: 60,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width",
                maxlength: "5",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.getSyainName(e);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 13 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                me.getSyainName(e);
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function () {
                            var tmptxt = $(this).val();
                            $(this).val(tmptxt.replace(/[^0-9a-zA-Z]/g, ""));
                        },
                    },
                ],
            },
        },
        {
            name: "SYAIN_NAME",
            label: " ",
            index: "SYAIN_NAME",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "EMAIL",
            label: "email",
            index: "EMAIL",
            width: 250,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "30",
            },
        },
        {
            name: "ENABLED",
            label: "ENABLED",
            index: "ENABLED",
            sortable: false,
            align: "center",
            width: 135,
            hidden: true,
        },
        {
            name: "ENABLED1",
            label: "利用可否",
            index: "ENABLED1",
            sortable: false,
            align: "center",
            width: 100,
            formatter: function (_cellvalue, options, rowObject) {
                var radio1StringOnclick = '<input onclick="selection(';
                var radio1StringFirst = ")\" class='HMAUDKansaJinMente ";
                var radio1StringSecond = "_rdoRiyou1' type='radio' name='";
                var radio1StringName =
                    "_rdoRiyou' value='1' " +
                    (rowObject["ENABLED"] == 1
                        ? "checked='true'/>可  "
                        : "/>可  ");

                var radio2StringOnclick = '<input onclick="selection(';
                var radio2StringFirst = ")\" class='HMAUDKansaJinMente ";
                var radio2StringSecond = "_rdoRiyou2' type='radio' name='";
                var radio2StringName =
                    "_rdoRiyou' value='0' " +
                    (rowObject["ENABLED"] == 0
                        ? "checked='true'/>不可"
                        : "/>不可");
                var detail =
                    radio1StringOnclick +
                    options.rowId +
                    ",1" +
                    radio1StringFirst +
                    options.rowId +
                    radio1StringSecond +
                    options.rowId +
                    radio1StringName +
                    radio2StringOnclick +
                    options.rowId +
                    ",0" +
                    radio2StringFirst +
                    options.rowId +
                    radio2StringSecond +
                    options.rowId +
                    radio2StringName;
                return detail;
            },
        },
        {
            name: "SEQ",
            label: "並び順",
            index: "SEQ",
            width: 60,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width numeric",
                maxlength: "2",
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMAUDKansaJinMente.button",
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
    // = 宣言 end =
    // ==========
    // ==========
    // = イベント start =
    // ==========

    $(".HMAUDKansaJinMente.btnRowAdd").click(function () {
        me.btnRowAdd_Click();
    });
    $(".HMAUDKansaJinMente.btnSearch").click(function () {
        me.Page_Load();
    });
    //更新ボタンクリック
    $(".HMAUDKansaJinMente.btnUpdata").click(function () {
        if (!me.repeatCheck()) {
            return false;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUpdata_Click;
        me.clsComFnc.MsgBoxBtnFnc.No = me.selectRow;
        me.clsComFnc.FncMsgBox("QY012");
    });

    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };

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
        me.complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                $(".HMAUDKansaJinMente.btnRowAdd").button("disable");
                $(".HMAUDKansaJinMente.btnUpdata").button("disable");
                $(".HMAUDKansaJinMente.btnSearch").button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (result["GetSyainMstValue"].length != 0) {
                me.allSyainName = result["GetSyainMstValue"];
            }
            me.firstData = result["rows"];
            $(".HMAUDKansaJinMente.btnUpdata").button("enable");
            $(".HMAUDKansaJinMente.btnRowAdd").button("enable");
            $(".HMAUDKansaJinMente.btnRowDel").button("enable");

            $("#HMAUDKansaJinMente_tblMain_SYAIN_NAME").remove();
            $("#HMAUDKansaJinMente_tblMain_SYAIN_NO").prop("colspan", "2");
            $("#HMAUDKansaJinMente_tblMain_SYAIN_NO").css("width", "auto");

            me.jqgridEditSet();
        };
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
            "",
            me.complete_fun,
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 660);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 350 : 450,
        );
        $(me.grid_id).jqGrid("bindKeys");
    };
    //'**********************************************************************
    //'処 理 名：行選択効果の設定
    //'関 数 名：jqgridEditSet
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：行選択効果の設定
    //'**********************************************************************
    me.jqgridEditSet = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");

        me.loadGridRowCnt = arrIds.length;

        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    //編集可能なセルをクリック、上下キー
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;

                    //ヘッダークリック以外
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray",
                    );
                    me.lastsel = rowid;

                    if (rowid > me.loadGridRowCnt - 1) {
                        $(me.grid_id).setColProp("SYAIN_NO", {
                            editable: true,
                        });
                    } else {
                        $(me.grid_id).setColProp("SYAIN_NO", {
                            editable: false,
                        });
                    }
                    $(me.grid_id).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField:
                            me.loadGridRowCnt - 1 >= rowid &&
                            cellIndex >= 0 &&
                            cellIndex < 3
                                ? 3
                                : cellIndex == 0 || cellIndex == 2
                                  ? 1
                                  : cellIndex,
                    });
                } else {
                    if (rowid && rowid != me.lastsel) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray",
                        );
                        me.lastsel = rowid;
                    }
                    if (rowid > me.loadGridRowCnt - 1) {
                        $(me.grid_id).setColProp("SYAIN_NO", {
                            editable: true,
                        });
                    } else {
                        $(me.grid_id).setColProp("SYAIN_NO", {
                            editable: false,
                        });
                    }
                    $(me.grid_id).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: false,
                    });
                }

                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });
                //键盘事件
                gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    me.lastsel,
                );
                $(me.grid_id).find(".width").css("width", "94%");
            },
        });
        $(me.grid_id).jqGrid("setSelection", 0, true);
    };

    me.selectRow = function () {
        $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
    };

    selection = function (cl, target) {
        $(me.grid_id).jqGrid("setSelection", cl, true);
        $(me.grid_id).jqGrid("setCell", cl, "ENABLED", target);
    };
    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDKansaJinMente fieldset").width(),
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDKansaJinMente.HMS-button-pane").height();
        var tableHeight = mainHeight - buttonHeight - 100;
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };

    me.btnRowAdd_Click = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        var max_SEQ = 0;

        var rowdata = {
            SYAIN_NO: "",
            SYAIN_NAME: "",
            EMAIL: "",
            ENABLED: 1,
            SEQ: "",
        };

        for (j = 0; j < arrIds.length; j++) {
            $(me.grid_id).jqGrid("saveRow", j, null, "clientArray");
        }

        for (var i = 0; i < arrIds.length; i++) {
            var getRowData = $(me.grid_id).jqGrid("getRowData", arrIds[i]);
            if (Number(getRowData["SEQ"]) > max_SEQ) {
                max_SEQ = Number(getRowData["SEQ"]);
            }
        }
        rowdata.SEQ = max_SEQ + 1;

        var i = arrIds.length;
        var i_rowData = $(me.grid_id).jqGrid("getRowData", i - 1);
        if (i_rowData.SYAIN_NO != "" || i_rowData.EMAIL != "") {
            $(me.grid_id).jqGrid("addRowData", i, rowdata);
            $(me.grid_id).jqGrid("setSelection", i, true);
            var selNextId = "#" + i + "_SYAIN_NO";

            $(selNextId).trigger("focus");
        } else {
            var selNextId = "#" + i - 1 + "_SYAIN_NO";
            $(me.grid_id).jqGrid("setSelection", i - 1, true);
            $(selNextId).trigger("focus");
        }
    };

    me.btnUpdata_Click = function () {
        var objDR = $(me.grid_id).jqGrid("getRowData");
        var url = "HMAUD/HMAUDKansaJinMente/btnUpdate_Click";
        var data = {
            tableData: JSON.stringify(objDR),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                };
                me.clsComFnc.FncMsgBox("I0015");
                me.Page_Load();
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.getSyainName = function (e) {
        var foundNM = undefined;
        var selCellVal = $.trim($(e.target).val());
        if (me.allSyainName) {
            var foundNM_array = me.allSyainName.filter(function (element) {
                return element["SYAIN_NO"] == me.clsComFnc.FncNv(selCellVal);
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
                if ($(e.target).parent().next().next().children().val() == "") {
                    $(e.target)
                        .parent()
                        .next()
                        .next()
                        .children()
                        .val(foundNM ? foundNM["EMAIL"] : "");
                }
            }
        }
        $(e.target)
            .parent()
            .next()
            .text(foundNM ? foundNM["SYAIN_NM"] : "");
    };

    me.repeatCheck = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var rows = $(me.grid_id).jqGrid("getDataIDs");
        for (index in rows) {
            var rowData = $(me.grid_id).jqGrid("getRowData", rows[index]);
            if (
                rowData["SYAIN_NO"] == "" &&
                rowData["EMAIL"] == "" &&
                rowData["SEQ"] == ""
            ) {
                continue;
            }
            if (rowData["SYAIN_NO"].trim() == "") {
                $(me.grid_id).jqGrid("setSelection", rows[index], true);
                me.clsComFnc.ObjFocus = $("#" + rows[index] + "_SYAIN_NO");
                me.clsComFnc.FncMsgBox("W0017", "監査人コード");
                return false;
            }
            if (rowData["SEQ"].trim() == "") {
                $(me.grid_id).jqGrid("setSelection", rows[index], true);
                me.clsComFnc.ObjFocus = $("#" + rows[index] + "_SEQ");
                me.clsComFnc.FncMsgBox("W0017", "並び順");
                return false;
            }
            if (
                rowData["EMAIL"].trim() !== "" &&
                !me.clsComFnc.CheckEmail(rowData["EMAIL"].trim())
            ) {
                $(me.grid_id).jqGrid("setSelection", rows[index], true);
                me.clsComFnc.ObjFocus = $("#" + rows[index] + "_EMAIL");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "メールの形式が正しくありません。再入力してください。",
                );
                return false;
            }
        }
        for (var i = 0; i <= rows.length - 1; i++) {
            var rowData_i = $(me.grid_id).jqGrid("getRowData", rows[i]);
            for (var j = 0; j <= rows.length - 1; j++) {
                var rowData_j = $(me.grid_id).jqGrid("getRowData", rows[j]);
                if (i !== j) {
                    if (
                        rowData_i["SYAIN_NO"] !== "" &&
                        rowData_i["SYAIN_NO"] == rowData_j["SYAIN_NO"]
                    ) {
                        $(me.grid_id).jqGrid("setSelection", rows[j], true);
                        me.clsComFnc.ObjFocus = $("#" + rows[j] + "_SYAIN_NO");
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "コードが重複しています。",
                        );
                        return false;
                    }
                    if (
                        rowData_i["SEQ"] !== "" &&
                        rowData_i["SEQ"] == rowData_j["SEQ"]
                    ) {
                        if (
                            me.firstData[i] &&
                            me.firstData[i]["cell"]["SEQ"] == rowData_i["SEQ"]
                        ) {
                            $(me.grid_id).jqGrid("setSelection", rows[j], true);
                            me.clsComFnc.ObjFocus = $("#" + rows[j] + "_SEQ");
                        } else {
                            $(me.grid_id).jqGrid("setSelection", rows[i], true);
                            me.clsComFnc.ObjFocus = $("#" + rows[i] + "_SEQ");
                        }
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "並び順が重複しています。",
                        );
                        return false;
                    }
                }
            }
        }
        return true;
    };
    // ==========
    // = イベント end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDKansaJinMente = new HMAUD.HMAUDKansaJinMente();
    o_HMAUD_HMAUDKansaJinMente.load();
    o_HMAUD_HMAUD.HMAUDKansaJinMente = o_HMAUD_HMAUDKansaJinMente;
});
