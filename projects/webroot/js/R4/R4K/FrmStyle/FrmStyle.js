/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150819           #2078						   BUG                              yin
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmStyle");

R4.FrmStyle = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmStyle";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmStyle_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFrmStyleSelect";
    me.sidx = "";
    me.actionFlg = "";
    me.lastsel = 0;
    //行を判断して、編集可否を制御
    me.loadGridRowCnt = 0;
    me.editDataFlg = false;
    me.maxRow = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //SHIFキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmStyle.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmStyle.cmdUpdate",
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
    $(".FrmStyle.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmStyle.cmdUpdate").click(function () {
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
        me.FrmStyle_load();
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
                name: "STYLE_ID",
                label: "所属ID",
                index: "STYLE_ID",
                width: 300,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 3,
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
                                        "STYLE_ID",
                                        "STYLE_NM",
                                        "STYLE_NM",
                                        true,
                                        false
                                    )
                                ) {
                                    return false;
                                }
                                //2015/08/19 yinhuaiyu modify end
                            },
                        },

                        {
                            type: "keyup",
                            fn: function (e) {
                                if (
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
                            },
                        },
                    ],
                },
            },
            {
                name: "STYLE_NM",
                label: "所属名",
                index: "STYLE_NM",
                width: 600,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 50,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                //2015/08/19 yinhuaiyu modify start
                                var row = $(e.target).closest("tr.jqgrow");
                                var rowId = row.attr("id");
                                var key = e.charCode || e.keyCode;
                                if (rowId > me.maxRow - 1) {
                                    if (!me.setColSelection(key)) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                    }
                                } else if (rowId == me.maxRow - 1) {
                                    if (!me.setColSelection(key)) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                    }
                                } else {
                                    if (!me.setColSelection(key)) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                    }
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

            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    me.editDataFlg = $(me.grid_id).getColProp(
                        "STYLE_ID"
                    ).editable;

                    if (typeof e != "undefined") {
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
                                $(me.grid_id).setColProp("STYLE_ID", {
                                    editable: true,
                                });
                            } else {
                                $(me.grid_id).setColProp("STYLE_ID", {
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
                            if (
                                rowData["STYLE_ID"].toString().trimEnd() == ""
                            ) {
                                return;
                            }

                            me.jqgridCurrentRowID = rowID;
                            //削除確認メッセージを表示する
                            me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                            me.clsComFnc.MsgBoxBtnFnc.No = me.cancelsel;
                            me.clsComFnc.FncMsgBox(
                                "QY999",
                                parseInt(rowID) +
                                    1 +
                                    "行目の所属を所属マスタ及びメニュー管理パターンテーブル、パターンマスタ、メニュー階層マスタより削除します。よろしいですか？",
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
                            $(me.grid_id).setColProp("STYLE_ID", {
                                editable: true,
                            });
                        } else {
                            $(me.grid_id).setColProp("STYLE_ID", {
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
            $(me.grid_id).jqGrid("setSelection", 0, true);

            me.maxRow = $(me.grid_id).getDataIDs().length;
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
    me.FrmStyle_load = function () {
        me.initGrid();
    };

    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");

        var rowdata = {
            STYLE_ID: "",
            STYLE_NM: "",
        };

        for (j = 0; j < arrIds.length; j++) {
            $(me.grid_id).jqGrid("saveRow", j, null, "clientArray");
        }

        var i = arrIds.length;
        var i_rowData = $(me.grid_id).jqGrid("getRowData", i - 1);
        if (i_rowData.STYLE_ID != "" || i_rowData.STYLE_NM != "") {
            $(me.grid_id).jqGrid("addRowData", i, rowdata);
            $(me.grid_id).jqGrid("setSelection", i, true);
            var selNextId = "#" + i + "_STYLE_ID";
            $(selNextId).trigger("focus");
        } else {
            var selNextId = "#" + i - 1 + "_STYLE_ID";
            $(me.grid_id).jqGrid("setSelection", i - 1, true);
            $(selNextId).trigger("focus");
            //$(me.grid_id).jqGrid('editRow', i-1, true);
        }

        $(".FrmStyle.cmdUpdate").button("enable");
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
                    if (grid_data[i]["STYLE_ID"] != "") {
                        if (
                            grid_data[i]["STYLE_ID"] == grid_data[j]["STYLE_ID"]
                        ) {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + "STYLE_ID";
                            $(selId).trigger("focus");
                            $(selId).select();
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "所属IDを入力して下さい。"
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
            //me.keyupAddrow();
            me.addRowFlag = true;
        }

        if (key == 222) {
            return false;
        }
        return true;
    };
    //2015/08/19 yinhuaiyu modify end
    me.fncInputChk = function () {
        //どれか一列でも入力されていた場合
        var grid_data = $(me.grid_id).jqGrid("getRowData");
        //$(me.grid_id).jqGrid('saveRow');
        for (var i = 0; i < grid_data.length; i++) {
            //--入力チェック--
            for (key in grid_data[i]) {
                //--所属ID Check--
                if (key == "STYLE_ID") {
                    var selId = "#" + i + "_" + "STYLE_ID";
                    var iColNo = 0;
                    intRtn = me.clsComFnc.FncSprCheck(
                        grid_data[i]["STYLE_ID"],
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        me.colModel[0]["editoptions"]["maxlength"]
                    );
                    if (intRtn != 0) {
                        $(me.grid_id).jqGrid("setSelection", i, true);
                        me.clsComFnc.ObjFocus = $(selId);
                        me.clsComFnc.ObjSelect = $(selId);
                        me.clsComFnc.FncMsgBox(
                            "W000" + intRtn * -1,
                            me.colModel[iColNo]["label"].replace(/<br \/>/g, "")
                        );
                        return false;
                    }
                    //--桁数チェック　３桁以上の場合はエラー--
                    if (grid_data[i]["STYLE_ID"].length > 3) {
                        $(me.grid_id).jqGrid("setSelection", i, true);
                        me.clsComFnc.ObjFocus = $(selId);
                        me.clsComFnc.ObjSelect = $(selId);
                        me.clsComFnc.FncMsgBox("W0003", "所属ID");
                        return false;
                    }
                    //--所属ID 登録済チェック--
                    for (var j = 0; j < grid_data.length; j++) {
                        if (i != j) {
                            if (
                                grid_data[i]["STYLE_ID"] ==
                                grid_data[j]["STYLE_ID"]
                            ) {
                                // i += 1;
                                $(me.grid_id).jqGrid("setSelection", j, true);
                                var selId = "#" + j + "_" + "STYLE_ID";
                                me.clsComFnc.ObjFocus = $(selId);
                                me.clsComFnc.ObjSelect = $(selId);
                                me.clsComFnc.FncMsgBox("W0004", "所属ID");
                                return false;
                            }
                        }
                    }
                }
                //--所属名称 Check--
                if (key == "STYLE_NM") {
                    var selId = "#" + i + "_" + "STYLE_NM";
                    var iColNo = 1;
                    //必須チェック　所属名称が入力されている場合のみ必須
                    if (grid_data[i]["STYLE_NM"].length == 0) {
                        $(me.grid_id).jqGrid("setSelection", i, true);
                        me.clsComFnc.ObjFocus = $(selId);
                        me.clsComFnc.ObjSelect = $(selId);
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "所属名を入力して下さい。"
                        );
                        return false;
                    }
                    intRtn = me.clsComFnc.FncSprCheck(
                        grid_data[i]["STYLE_NM"],
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        me.colModel[iColNo]["editoptions"]["maxlength"]
                    );
                    if (intRtn != 0) {
                        $(me.grid_id).jqGrid("setSelection", i, true);
                        me.clsComFnc.ObjFocus = $(selId);
                        me.clsComFnc.ObjSelect = $(selId);
                        me.clsComFnc.FncMsgBox(
                            "W000" + intRtn * -1,
                            me.colModel[iColNo]["label"].replace(/<br \/>/g, "")
                        );
                        return false;
                    }
                    //桁数チェック　５０桁以上の場合はエラー
                    if (grid_data[i]["STYLE_NM"].length > 50) {
                        $(me.grid_id).jqGrid("setSelection", i, true);
                        me.clsComFnc.ObjFocus = $(selId);
                        me.clsComFnc.ObjSelect = $(selId);
                        me.clsComFnc.FncMsgBox("W0003", "所属名");
                        return false;
                    }
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
            STYLE_ID: rowData["STYLE_ID"],
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
    var o_R4_FrmStyle = new R4.FrmStyle();
    o_R4_FrmStyle.load();
    o_R4K_R4K.FrmStyle = o_R4_FrmStyle;
});
