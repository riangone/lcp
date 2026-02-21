/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150819           #2078						   BUG                              yin
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmProgram");

R4.FrmProgram = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmProgram";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmProgram_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFrmProgramSelect";
    me.sidx = "";
    me.actionFlg = "";
    me.lastsel = 0;
    //行を判断して、編集可否を制御
    me.loadGridRowCnt = 0;
    me.editDataFlg = false;
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //SHIFキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmProgram.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmProgram.cmdUpdate",
        type: "button",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmProgram.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmProgram.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmProgram_load();
    };
    me.initGrid = function () {
        me.option = {
            pagerpos: "left",
            multiselect: false,
            caption: "",
            rowNum: 5000000,
            multiselectWidth: 30,
            rownumWidth: 40,
        };
        me.colModel = [
            {
                name: "PRO_NO",
                label: "プログラムNo.",
                index: "PRO_NO",
                width: 150,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 3,
                    class: "numeric",
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                if (
                                    !me.setColSelection(key, "PRO_NO", "PRO_ID")
                                ) {
                                    return false;
                                }
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {},
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "PRO_ID",
                label: "プログラムID",
                index: "PRO_ID",
                width: 300,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 50,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //2015/08/19 yinhuaiyu modify start
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "PRO_ID",
                                        "PRO_NM",
                                        "PRO_NM",
                                        true,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //2015/08/19 yinhuaiyu modify end
                            },
                        },
                        {
                            type: "keyup",
                            fn: function (e) {
                                //2015/08/19 yinhuaiyu modify start
                                if (
                                    e.keyCode != 16 &&
                                    e.keyCode != 8 &&
                                    e.keyCode != 9 &&
                                    e.keyCode != 46 &&
                                    e.keyCode != 110 &&
                                    e.keyCode != 190 &&
                                    (e.keyCode < 35 || e.keyCode > 40)
                                ) {
                                    if (me.GetByteCount1(this.value)) {
                                        this.value = this.value.replace(
                                            /[^\d\-\a-\z\A-\Z\ \.\,]/g,
                                            ""
                                        );
                                    }
                                }
                                //2015/08/19 yinhuaiyu modify end
                            },
                        },
                    ],
                },
            },
            {
                name: "PRO_NM",
                label: "プログラム名称",
                index: "PRO_NM",
                width: 450,
                sortable: false,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 50,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //2015/08/19 yinhuaiyu modify start
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "PRO_NM",
                                        "PRO_ID",
                                        "PRO_ID",
                                        false,
                                        true
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //2015/08/19 yinhuaiyu modify end
                            },
                        },
                    ],
                },
            },
            {
                name: "CREATE_DATE",
                label: "作成日",
                index: "CREATE_DATE",
                width: 33,
                sortable: false,
                align: "left",
                hidden: true,
            },
        ];
        me.complete_fun = function () {
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");

            me.loadGridRowCnt = arrIds.length;

            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    me.editDataFlg = $(me.grid_id).getColProp(
                        "PRO_NO"
                    ).editable;
                    if (typeof e != "undefined") {
                        //編集可能なセルをクリック、上下キー
                        var cellIndex =
                            e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex;

                        //ヘッダークリック以外
                        if (cellIndex != 0) {
                            if (rowid && rowid != me.lastsel) {
                                // $(me.grid_id).jqGrid('saveRow', me.lastsel);
                                $(me.grid_id).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                me.lastsel = rowid;
                            }

                            if (rowid > me.loadGridRowCnt - 1) {
                                $(me.grid_id).setColProp("PRO_NO", {
                                    editable: true,
                                });
                            } else {
                                $(me.grid_id).setColProp("PRO_NO", {
                                    editable: false,
                                });
                            }

                            $(me.grid_id).jqGrid("editRow", rowid, {
                                keys: true,
                                focusField:
                                    me.loadGridRowCnt - 1 == rowid &&
                                    cellIndex == 1
                                        ? cellIndex + 1
                                        : cellIndex,
                            });
                        } else {
                            //ヘッダークリック
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );

                            var rowID = $(me.grid_id).jqGrid(
                                "getGridParam",
                                "selrow"
                            );
                            var rowData = $(me.grid_id).jqGrid(
                                "getRowData",
                                rowID
                            );
                            if (rowData["PRO_NO"].toString().trimEnd() == "") {
                                return;
                            }

                            me.jqgridCurrentRowID = rowID;
                            //削除確認メッセージを表示する
                            me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                            me.clsComFnc.MsgBoxBtnFnc.No = me.cancelsel;
                            me.clsComFnc.FncMsgBox(
                                "QY007",
                                parseInt(rowID) +
                                    1 +
                                    "行目：プログラム一覧マスタ",
                                me.clsComFnc.GSYSTEM_NAME,
                                "YesNo",
                                "Question",
                                me.clsComFnc.MessageBoxDefaultButton.Button2
                            );
                        }
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
                        if (rowid > me.loadGridRowCnt - 1) {
                            $(me.grid_id).setColProp("PRO_NO", {
                                editable: true,
                            });
                        } else {
                            $(me.grid_id).setColProp("PRO_NO", {
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
                        me.lastsel
                    );
                },
            });
            //2015/08/19 yinhuaiyu add start
            $(me.grid_id).jqGrid("setSelection", 0, true);
            //2015/08/19 yinhuaiyu add end
        };
        var tmpdata = {};
        gdmz.common.jqgrid.show(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            tmpdata,
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 1000);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        $(me.grid_id).jqGrid("bindKeys");
    };
    //--Show image--
    me.FrmProgram_load = function () {
        me.initGrid();
    };
    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");

        var rowdata = {
            PRO_NO: "",
            PRO_ID: "",
            PRO_NM: "",
        };

        for (j = 0; j < arrIds.length; j++) {
            $(me.grid_id).jqGrid("saveRow", j, null, "clientArray");
        }

        var i = arrIds.length;
        var i_rowData = $(me.grid_id).jqGrid("getRowData", i - 1);
        if (
            i_rowData.PRO_NO != "" ||
            i_rowData.PRO_ID != "" ||
            i_rowData.PRO_NM != ""
        ) {
            $(me.grid_id).jqGrid("addRowData", i, rowdata);
            $(me.grid_id).jqGrid("setSelection", i, true);
            var selNextId = "#" + i + "_PRO_NO";

            $(selNextId).trigger("focus");
        } else {
            var selNextId = "#" + i - 1 + "_PRO_NO";
            $(me.grid_id).jqGrid("setSelection", i - 1, true);
            $(selNextId).trigger("focus");
        }

        $(".FrmProgram.cmdUpdate").button("enable");
    };

    me.fnc_click_cmdUpdate = function () {
        //入力チェック
        var grid_data = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data.length; i++) {
            $(me.grid_id).jqGrid("saveRow", i, null, "clientArray");
        }

        if (me.fncInputChk()) {
            //重複ﾁｪｯｸ
            var grid_data = $(me.grid_id).jqGrid("getRowData");
            for (var i = 0; i < grid_data.length; i++) {
                $(me.grid_id).jqGrid("saveRow", i, null, "clientArray");
            }
            for (var i = 0; i < grid_data.length - 1; i++) {
                for (var j = i + 1; j < grid_data.length; j++) {
                    if (grid_data[i]["PRO_NO"] != "") {
                        if (grid_data[i]["PRO_NO"] == grid_data[j]["PRO_NO"]) {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + "PRO_NO";
                            $(selId).trigger("focus");
                            $(selId).select();
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "プログラムIDを入力して下さい。"
                            );
                            return;
                        }
                    }
                }
            }
            //確認メッセージ
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
            me.clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
            me.clsComFnc.FncMsgBox("QY010");
        }
    };
    //--functions--
    //2015/08/19 yinhuaiyu modify start
    me.setColSelection = function (key) {
        if (
            (key >= 65 && key <= 90) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105) ||
            (key >= 186 && key <= 222) ||
            (key >= 109 && key <= 111) ||
            key == 106 ||
            key == 107
        ) {
            me.addRowFlag = true;
        }

        if (key == 222) {
            return false;
        }
        return true;
    };
    //2015/08/19 yinhuaiyu modify end
    me.fncInputChk = function () {
        var intRtn = 0;
        //どれか一列でも入力されていた場合
        var grid_data = $(me.grid_id).jqGrid("getRowData");
        //$(me.grid_id).jqGrid('saveRow');
        for (var i = 0; i < grid_data.length; i++) {
            if (grid_data[i]["PRO_NO"].length > 0) {
                //--入力チェック--
                for (key in grid_data[i]) {
                    //--プログラムNO Check--
                    if (key == "PRO_NO") {
                        intRtn = me.clsComFnc.FncSprCheck(
                            grid_data[i]["PRO_NO"],
                            1,
                            me.clsComFnc.INPUTTYPE.NUMBER1,
                            me.colModel[0]["editoptions"]["maxlength"]
                        );
                        //--数値チェック--
                        if (intRtn < 0) {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + key;
                            $(selId).trigger("focus");
                            $(selId).select();
                            me.clsComFnc.FncMsgBox("W0002", "プログラムNO");
                            return false;
                        }
                        //--桁数チェック　３桁以上の場合はエラー--
                        if (grid_data[i]["PRO_NO"].length > 3) {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + key;
                            $(selId).trigger("focus");
                            $(selId).select();
                            me.clsComFnc.FncMsgBox("W0003", "プログラムNO");
                            return false;
                        }
                        //--プログラムNO 登録済チェック--
                        for (var j = 0; j < grid_data.length; j++) {
                            if (i != j) {
                                if (
                                    grid_data[i]["PRO_NO"] ==
                                    grid_data[j]["PRO_NO"]
                                ) {
                                    $(me.grid_id).jqGrid(
                                        "setSelection",
                                        j,
                                        true
                                    );
                                    var selId = "#" + j + "_" + key;
                                    $(selId).trigger("focus");
                                    $(selId).select();
                                    me.clsComFnc.FncMsgBox(
                                        "W0004",
                                        "プログラムNO"
                                    );
                                    return false;
                                }
                            }
                        }
                    }
                    //--プログラムID Check--
                    if (key == "PRO_ID") {
                        //必須チェック　プログラムNOが入力されている場合のみ必須
                        if (grid_data[i]["PRO_ID"].length == 0) {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + key;
                            $(selId).trigger("focus");
                            $(selId).select();
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "プログラムIDを入力して下さい。"
                            );
                            return false;
                        }
                        //桁数チェック　５０桁以上の場合はエラー
                        if (grid_data[i]["PRO_ID"].length > 50) {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + key;
                            $(selId).trigger("focus");
                            $(selId).select();
                            me.clsComFnc.FncMsgBox("W0003", "プログラムID");
                            return false;
                        }
                    }
                    //--プログラム名称 Check--
                    if (key == "PRO_NM") {
                        //必須チェック　プログラム名称が入力されている場合のみ必須
                        if (grid_data[i]["PRO_NM"].length == 0) {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + key;
                            $(selId).trigger("focus");
                            $(selId).select();
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "プログラム名称を入力して下さい。"
                            );
                            return false;
                        }
                        //桁数チェック　５０桁以上の場合はエラー
                        if (grid_data[i]["PRO_NM"].length > 50) {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + key;
                            $(selId).trigger("focus");
                            $(selId).select();
                            me.clsComFnc.FncMsgBox("W0003", "プログラム名称");
                            return false;
                        }
                        intRtn = me.clsComFnc.FncSprCheck(
                            grid_data[i]["PRO_NM"],
                            1,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[2]["editoptions"]["maxlength"]
                        );
                        //--数値チェック--
                        if (intRtn < 0) {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + key;
                            $(selId).trigger("focus");
                            $(selId).select();
                            me.clsComFnc.FncMsgBox("W0003", "プログラム名称");
                            return false;
                        }
                    }
                }
            } else {
                //画面.プログラムID !="" AND 画面.プログラムNo.=""の場合
                if (grid_data[i]["PRO_ID"].length > 0) {
                    $(me.grid_id).jqGrid("setSelection", i, true);
                    var selId = "#" + i + "_" + key;

                    $(selId).trigger("focus");
                    $(selId).select();
                    me.clsComFnc.FncMsgBox("W0001", "プログラムNo");
                    return false;
                }
                //画面.プログラム名称 !="" AND 画面.プログラムID ="" AND 画面.プログラムNo.=""の場合
                if (grid_data[i]["PRO_NM"].length > 0) {
                    $(me.grid_id).jqGrid("setSelection", i, true);
                    var selId = "#" + i + "_" + key;
                    $(selId).trigger("focus");
                    $(selId).select();
                    me.clsComFnc.FncMsgBox(
                        "W0001",
                        "プログラムNoとプログラムID"
                    );
                    return false;
                }
            }
        }
        return true;
    };
    me.YesActionFnc = function () {
        //業者ﾏｽﾀに登録開始
        me.updateUrl = me.sys_id + "/" + me.id + "/" + "fncUpdate";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox("I0008");
            }
        };
        var data = $(me.grid_id).jqGrid("getRowData");
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.NoActionFnc = function () {
        return;
    };
    me.delRowData = function () {
        if (me.editDataFlg == true) {
            me.loadGridRowCnt--;
        }
        me.deleteUrl = me.sys_id + "/" + me.id + "/" + "fncDelete";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                var tmpdata = {};
                gdmz.common.jqgrid.show(
                    me.grid_id,
                    me.g_url,
                    me.colModel,
                    me.pager,
                    me.sidx,
                    me.option,
                    tmpdata,
                    me.complete_fun
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 1000);
                gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
            }
        };

        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        var data = {
            PRO_NO: rowData["PRO_NO"],
        };
        me.ajax.send(me.deleteUrl, data, 0);
    };
    me.GetByteCount1 = function (str) {
        var uFF61 = parseInt("FF61", 16);
        var uFF9F = parseInt("FF9F", 16);
        var uFFE8 = parseInt("FFE8", 16);
        var uFFEE = parseInt("FFEE", 16);
        var flagCheck = true;
        if (str != null) {
            for (var i = 0; i < str.length; i++) {
                var c = parseInt(str.charCodeAt(i));
                if (c < 256) {
                    flagCheck = true;
                } else {
                    if (uFF61 <= c && c <= uFF9F) {
                        flagCheck = true;
                    } else if (uFFE8 <= c && c <= uFFEE) {
                        flagCheck = true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return flagCheck;
    };

    me.cancelsel = function () {};
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmProgram = new R4.FrmProgram();
    o_R4_FrmProgram.load();
    o_R4K_R4K.FrmProgram = o_R4_FrmProgram;
});
