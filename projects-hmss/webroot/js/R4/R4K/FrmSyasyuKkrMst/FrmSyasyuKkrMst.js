/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150819           #2078                        　                               FANZHENGZHOU
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmSyasyuKkrMst");

R4.FrmSyasyuKkrMst = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmSyasyuKkrMst";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmSyasyuKkrMst_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFrmSyasyuKkrMstSelect";
    me.pager = "";
    // '#FrmSyasyuKkrMst_pager';
    me.sidx = "";
    me.actionFlg = "";
    me.lastsel = 0;
    me.cursel = 0;
    me.tmpSaveRowData = new Array();
    me.frontLineNo = -1;
    me.frontLineNo1 = -1;
    me.saveSelectedRowData = new Array();
    me.commonTempVar = "";
    me.showMsgTF = false;
    me.loadGridRowCnt = 0;
    me.focusSaveRowDataArr = new Array();
    me.buttonActionFlg = "";
    me.editDataFlg = false;
    me.saveUNTIN_RITUVal = "";
    me.editableFlg = false;
    me.firstData = new Array();
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmSyasyuKkrMst.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyuKkrMst.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyuKkrMst.cmdCopy",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyuKkrMst.cmdSearch",
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
    $(".FrmSyasyuKkrMst.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmSyasyuKkrMst.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });
    $(".FrmSyasyuKkrMst.cmdSearch").click(function () {
        me.fnc_click_cmdSearch();
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
        me.FrmSyasyuKkrMst_load();
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
                name: "UCOYA_CD",
                label: "UC親コード",
                index: "UCOYA_CD",
                width: 120,
                sortable: false,
                align: "left",

                editable: true,
                editoptions: {
                    maxlength: 3,
                    dataEvents: [
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
                name: "OYA_CD",
                label: "車種集計コード",
                index: "OYA_CD",
                width: 300,
                sortable: false,
                align: "left",
                editable: true,

                editoptions: {
                    maxlength: 3,
                    dataEvents: [
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
                name: "CREATE_DATE",
                label: "CREATE_DATE",
                index: "CREATE_DATE",
                width: 300,
                sortable: true,
                align: "left",
                hidden: true,
            },
        ];
        me.complete_fun = function () {
            me.firstData = $(me.grid_id).jqGrid("getRowData");
            me.setButtonEnableState();
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");

            me.loadGridRowCnt = arrIds.length;
            if (arrIds.length >= 1) {
                $(me.grid_id).jqGrid("setSelection", 0);
            }
            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    if (typeof e != "undefined") {
                        var cellIndex =
                            e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex;
                        //ヘッダークリック以外
                        if (cellIndex != 0) {
                            if (rowid && rowid != me.lastsel) {
                                $(me.grid_id).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                me.lastsel = rowid;
                            }
                            $(me.grid_id).jqGrid("editRow", rowid, {
                                keys: true,
                                focusField: cellIndex,
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
                                rowData["UCOYA_CD"].toString().trimEnd() == ""
                            ) {
                                return;
                            }
                            me.jqgridCurrentRowID = rowID;
                            //削除確認メッセージを表示する
                            me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                            me.clsComFnc.MsgBoxBtnFnc.No = me.cancelsel;
                            me.clsComFnc.MessageBox(
                                "削除します、よろしいですか？",
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
                        $(me.grid_id).jqGrid("editRow", rowid, {
                            keys: true,
                            focusField: false,
                        });
                    }

                    $(".numeric").numeric({
                        decimal: true,
                        negative: true,
                    });
                    gdmz.common.jqgrid.setKeybordEvents(
                        me.grid_id,
                        e,
                        me.lastsel
                    );
                },
            });
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
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 496);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        //---20150819 #2078 fanzhengzhou add s.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150819 #2078 fanzhengzhou add e.
    };

    me.FrmSyasyuKkrMst_load = function () {
        me.initGrid();
    };
    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        me.keyupAddrow();
        me.setButtonEnableState();
    };
    me.fnc_click_cmdUpdate = function () {
        me.buttonActionFlg = "update";
        //確認メッセージ
        if (me.fncInputChk()) {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesUpdateFnc;
            me.clsComFnc.MsgBoxBtnFnc.No = me.NoUpdateFnc;
            me.clsComFnc.FncMsgBox("QY010");
        }
    };
    //--functions--
    me.keyupAddrow = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        rowData = $(me.grid_id).jqGrid(
            "getRowData",
            parseInt(tmpcnt.length) - 1
        );
        if (!(rowData["UCOYA_CD"] == "")) {
            rowdata = {};
            $(me.grid_id).jqGrid("addRowData", tmpcnt.length, rowdata);
        }
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        $(me.grid_id).jqGrid("setSelection", parseInt(tmpcnt.length) - 1);
        $(me.grid_id).jqGrid("editRow", parseInt(tmpcnt.length) - 1, true);
        $("#" + (parseInt(tmpcnt.length) - 1) + "_UCOYA_CD").trigger("focus");
    };

    //---20150819 #2078 fanzhengzhou upd s.
    // me.setColSelection = function(key, colNowName, colNextName, colPreviousName, obj)
    // {
    // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
    // var rowID = $(me.grid_id).jqGrid('getGridParam', 'selrow');
    //
    // //enter
    // if (key == 13)
    // {
    // if (me.lastsel < tmpcnt.length - 1)
    // {
    // if (colNowName == "OYA_CD")
    // {
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // $('#' + (parseInt(me.lastsel) + 1) + '_UCOYA_CD').focus();
    // $('#' + (parseInt(me.lastsel) + 1) + '_UCOYA_CD').select();
    // }
    // else
    // {
    // $('#' + (parseInt(me.lastsel)) + '_' + colNextName).focus();
    // $('#' + (parseInt(me.lastsel)) + '_' + colNextName).select();
    // }
    // return false;
    // }
    // else
    // {
    // $('#' + (parseInt(me.lastsel)) + '_' + colNextName).focus();
    // $('#' + (parseInt(me.lastsel)) + '_' + colNextName).select();
    // }
    // if ((parseInt(tmpcnt.length) - 1) == rowID)
    // {
    // return false;
    // }
    //
    // }
    // if (key == 39)
    // {
    // //right
    // $('#' + me.lastsel + '_' + colNextName).focus();
    // $('#' + me.lastsel + '_' + colNextName).select();
    // return false;
    // }
    // if (key == 37)
    // {
    // //left
    // $('#' + me.lastsel + '_' + colPreviousName).focus();
    // $('#' + me.lastsel + '_' + colPreviousName).select();
    // return false;
    // }
    // if (key == 38)
    // {
    // //top
    // if (me.lastsel == 0)
    // {
    // return;
    // }
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) - 1));
    // $('#' + (parseInt(me.lastsel)) + '_' + colNowName).focus();
    // $('#' + (parseInt(me.lastsel)) + '_' + colNowName).select();
    // return false;
    // }
    // if (key == 40)
    // {
    // //bottom
    // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
    // var id = $(me.grid_id).jqGrid('getGridParam', 'selrow');
    // if (id == (parseInt(tmpcnt.length) - 1))
    // {
    // return;
    // }
    //
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('editRow', (parseInt(me.lastsel) + 1), true);
    // $('#' + (parseInt(me.lastsel) + 1) + '_' + colNowName).focus();
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // $(obj).select();
    // return false;
    // }
    // if (key == 222)
    // {
    // return false;
    // }
    // return true;
    // };
    //---20150819 #2078 fanzhengzhou upd s.

    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        me.deleteUrl = me.sys_id + "/" + me.id + "/" + "fncSingleDelete";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                var allRowData = $(me.grid_id).jqGrid("getRowData");
                for (var tmpI = rowID; tmpI <= allRowData.length - 1; tmpI++) {
                    $(me.grid_id).jqGrid(
                        "setRowData",
                        parseInt(tmpI),
                        allRowData[parseInt(tmpI) + 1]
                    );
                    $(me.grid_id).jqGrid("delRowData", allRowData.length - 1);
                }
                if (me.firstData.length - 1 >= parseInt(rowID)) {
                    me.firstData.splice(parseInt(rowID), 1);
                }
            }
        };
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        var data = {
            UCOYA_CD: rowData["UCOYA_CD"],
        };
        me.ajax.send(me.deleteUrl, data, 0);
    };
    me.cancelsel = function () {};
    me.YesUpdateFnc = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        me.updateUrl = me.sys_id + "/" + me.id + "/" + "fncUpdate";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                me.firstData = $(me.grid_id).jqGrid("getRowData");
                me.clsComFnc.FncMsgBox("I0008");
            }
        };
        var data = $(me.grid_id).jqGrid("getRowData");
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.NoUpdateFnc = function () {};
    me.MathRound = function (key, obj) {
        if (key == 13 || (key >= 37 && key <= 40) || key == 9) {
            if ($(obj).val() != "") {
                $(obj).val(Math.round($(obj).val()));
            }
            if ($(obj).val() === "NaN") {
                $(obj).val(me.commonTempVar);
            }
        }
    };
    me.keydownNumber = function (_key, _obj) {};

    me.tempPackage = function () {
        if (me.buttonActionFlg != "update") {
            me.focusSaveRowData();
        }
    };
    me.setButtonEnableState = function () {
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");

        if (grid_data1.length >= 1) {
            $(".FrmSyasyuKkrMst.cmdUpdate").button("enable");
        } else {
            $(".FrmSyasyuKkrMst.cmdUpdate").button("disable");
        }
    };

    me.fncInputChk = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        for (k = 0; k < parseInt(arrIds.length); k++) {
            $(me.grid_id).jqGrid("saveRow", k, true);
        }
        var tableHeaderTextArr = {
            UCOYA_CD: "UC親コード",
            OYA_CD: "車種集計コード",
        };
        var blnInputFlg_1 = false;

        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length; i++) {
            if (
                grid_data1[i]["UCOYA_CD"] != "" &&
                grid_data1[i]["OYA_CD"] != "" &&
                i == parseInt(grid_data1.length) - 1
            ) {
                blnInputFlg_1 = true;
            }
            if (
                !(
                    grid_data1[i]["UCOYA_CD"] == "" &&
                    grid_data1[i]["OYA_CD"] == ""
                )
            ) {
                for (key in grid_data1[i]) {
                    switch (key) {
                        case "UCOYA_CD":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR2,
                                me.colModel[0]["editoptions"]["maxlength"]
                            );
                            break;
                        case "OYA_CD":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR2,
                                me.colModel[1]["editoptions"]["maxlength"]
                            );
                            break;
                    }
                    switch (me.intRtn) {
                        case 0:
                            break;
                        default:
                            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
                            for (k = 0; k < parseInt(arrIds.length) - 1; k++) {
                                $(me.grid_id).jqGrid("saveRow", k, true);
                            }
                            $(me.grid_id).jqGrid("setSelection", i);
                            $(me.grid_id).jqGrid("editRow", i, true);
                            $("#" + i + "_" + key).trigger("focus");
                            me.clsComFnc.FncMsgBox(
                                "W000" + (parseInt(me.intRtn) * -1).toString(),
                                tableHeaderTextArr[key]
                            );
                            return false;
                    }
                }
                //キー項目の必須ﾁｪｯｸ'
                if (grid_data1[i]["UCOYA_CD"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $(me.grid_id).jqGrid("editRow", i, true);
                    $("#" + i + "_UCOYA_CD").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "UC親コード");
                    return false;
                }
                blnInputFlg_1 = true;
            }
        }
        if (blnInputFlg_1 == false) {
            $(me.grid_id).jqGrid("setSelection", 0);
            $(me.grid_id).jqGrid("editRow", 0, true);
            $("#" + 0 + "_UCOYA_CD").trigger("focus");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            for (var j = i + 1; j < grid_data1.length; j++) {
                if (
                    grid_data1[i]["UCOYA_CD"] != "" &&
                    grid_data1[j]["UCOYA_CD"] != ""
                ) {
                    if (
                        grid_data1[i]["UCOYA_CD"].toString().toUpperCase() ==
                        grid_data1[j]["UCOYA_CD"].toString().toUpperCase()
                    ) {
                        var row = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["UCOYA_CD"]
                                    .toString()
                                    .toUpperCase() !==
                                grid_data1[i]["UCOYA_CD"]
                                    .toString()
                                    .toUpperCase()
                            ) {
                                var row = i;
                            }
                        }
                        $(me.grid_id).jqGrid("setSelection", row);
                        $(me.grid_id).jqGrid("editRow", row, true);
                        $("#" + row + "_UCOYA_CD").trigger("focus");
                        $("#" + row + "_UCOYA_CD").trigger("select");
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "キー項目が重複しています"
                        );
                        return false;
                    }
                }
            }
        }
        return true;
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyasyuKkrMst = new R4.FrmSyasyuKkrMst();
    o_R4_FrmSyasyuKkrMst.load();
    o_R4K_R4K.FrmSyasyuKkrMst = o_R4_FrmSyasyuKkrMst;
});
