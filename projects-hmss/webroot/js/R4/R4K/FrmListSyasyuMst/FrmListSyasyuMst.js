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
 * 20150819           #2078                                                         FANZHENGZHOU
 * 20150831           #1971                                                         FANZHENGZHOU
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmListSyasyuMst");

R4.FrmListSyasyuMst = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmListSyasyuMst";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmListSyasyuMst_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "subSpreadReShow";
    me.pager = "";
    // '#FrmListSyasyuMst_pager';
    me.sidx = "";
    me.actionFlg = "";
    me.lastsel = 0;
    me.cursel = 0;
    me.tmpSaveRowData = new Array();
    me.frontLineNo = -1;
    me.frontLineNo1 = -1;
    me.saveSelectedRowData = new Array();
    me.commonTempVar = "";
    me.loadGridRowCnt = 0;
    me.focusSaveRowDataArr = new Array();
    me.buttonActionFlg = "";
    me.selectRow_KUKURI_CD_key = "";
    me.reload = false;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmListSyasyuMst.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmListSyasyuMst.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmListSyasyuMst.cmdCancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmListSyasyuMst.cmdSearch",
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
    $(".FrmListSyasyuMst.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmListSyasyuMst.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });
    $(".FrmListSyasyuMst.cmdCancel").click(function () {
        me.fnc_click_cmdCancel();
    });
    $(".FrmListSyasyuMst.cmdSearch").click(function () {
        me.fnc_click_cmdSearch();
    });
    $(".FrmListSyasyuMst.txtKkrCD").keydown(function (e) {
        var key = e.charCode || e.keyCode;
        if (key == 222) {
            return false;
        }
    });
    $(".FrmListSyasyuMst.txtCarKbn").keydown(function (e) {
        var key = e.charCode || e.keyCode;
        if (key == 222) {
            return false;
        }
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
        me.FrmListSyasyuMst_load();
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
                name: "KUKURI_CD",
                label: "括りコード",
                index: "KUKURI_CD",
                width: 80,
                sortable: true,
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
                            fn: function () {},
                        },
                    ],
                },
            },
            {
                name: "LINE_NO",
                label: "ラインNo",
                index: "LINE_NO",
                width: 80,
                sortable: true,
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
                            fn: function () {},
                        },
                    ],
                },
            },
            {
                name: "CAR_KBN",
                label: "区分",
                index: "CAR_KBN",
                width: 450,
                sortable: true,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 2,
                    dataEvents: [
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                            },
                        },
                    ],
                },
            },
            {
                name: "CREATE_DATE",
                label: "作成日付",
                index: "CREATE_DATE",
                width: 85,
                sortable: true,
                align: "left",
                hidden: true,
            },
        ];
        me.complete_fun = function () {
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            me.loadGridRowCnt = arrIds.length;
            if (me.reload == true) {
                $("#0").trigger("focus");
                me.reload = false;
            }
            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    if (typeof e != "undefined") {
                        var cellIndex =
                            e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex;
                    }
                    if (me.frontLineNo == -1) {
                        me.frontLineNo = rowid;
                    }
                    if (me.frontLineNo != rowid) {
                        me.frontLineNo1 = me.frontLineNo;
                    }

                    if (typeof e != "undefined") {
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
                                me.selectRow_KUKURI_CD_key = $(
                                    me.grid_id
                                ).jqGrid("getRowData", rowid);
                            }
                            $(me.grid_id).jqGrid("editRow", rowid, {
                                keys: true,
                                focusField: cellIndex,
                            });
                        } else {
                            //ヘッダークリック
                            $(me.grid_id).jqGrid("saveRow", me.lastsel);

                            $("input,select", e.target).trigger("select");
                            var rowID = $(me.grid_id).jqGrid(
                                "getGridParam",
                                "selrow"
                            );
                            var rowData = $(me.grid_id).jqGrid(
                                "getRowData",
                                rowID
                            );
                            if (
                                rowData["KUKURI_CD"].toString().trimEnd() == ""
                            ) {
                                return;
                            }
                            //削除確認メッセージを表示する
                            me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                            me.clsComFnc.MsgBoxBtnFnc.No = me.cancelDelRowData;
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
                            me.selectRow_KUKURI_CD_key = $(me.grid_id).jqGrid(
                                "getRowData",
                                rowid
                            );
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
                        decimal: false,
                        negative: false,
                    });

                    gdmz.common.jqgrid.setKeybordEvents(
                        me.grid_id,
                        e,
                        me.lastsel
                    );
                    me.frontLineNo = rowid;
                },
            });
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            if (arrIds.length == 0) {
                $(".FrmListSyasyuMst.cmdUpdate").button("disable");
                $(".FrmListSyasyuMst.cmdInsert").button("enable");
                //$(".FrmListSyasyuMst.cmdInsert").button("disable");
            } else {
                $(".FrmListSyasyuMst.cmdUpdate").button("enable");
                $(".FrmListSyasyuMst.cmdInsert").button("enable");
            }
        };
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 690);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        //---20150819 #2078 fanzhengzhou add s.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150819 #2078 fanzhengzhou add e.
    };

    me.FrmListSyasyuMst_load = function () {
        $(".FrmListSyasyuMst.cmdUpdate").button("disable");
        $(".FrmListSyasyuMst.cmdInsert").button("disable");
        me.initGrid();
    };
    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        me.buttonActionFlg = "insert";
        rowdata = {
            ID: "",
            TOA_NAME: "",
            HTA_PRC: "",
            TNP_PRC: "",
        };
        var tmpcnt = 0;
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < arrIds.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data_last1 = $(me.grid_id).jqGrid(
            "getRowData",
            parseInt(arrIds.length) - 1
        );

        for (key in grid_data_last1) {
            if (grid_data_last1[key] == "") {
                tmpcnt++;
            }
        }
        if (tmpcnt != 4) {
            $(me.grid_id).jqGrid("addRowData", arrIds.length, rowdata);
            $(me.grid_id).jqGrid("setSelection", arrIds.length);
            $(me.grid_id).jqGrid("editRow", arrIds.length, true);
        }
    };
    me.fnc_click_cmdUpdate = function () {
        if (!me.fncInputChk()) {
            return;
        }
        me.buttonActionFlg = "update";
        if (!me.checkUnique_Update()) {
            return;
        }
    };
    me.fnc_click_cmdSearch = function () {
        me.buttonActionFlg = "search";
        var tmpdata = {
            txtCarKbn: $(".FrmListSyasyuMst.txtCarKbn")
                .val()
                .toString()
                .trimEnd(),
            txtKkrCD: $(".FrmListSyasyuMst.txtKkrCD")
                .val()
                .toString()
                .trimEnd(),
        };
        me.reload = true;
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
    };

    //---20150819 fanzhengzhou del s.  VB doesn't have this function.
    //--functions--

    // me.keyupAddrow = function() {
    // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
    // var rowID = $(me.grid_id).jqGrid('getGridParam', 'selrow');
    // if (tmpcnt.length - 1 == rowID) {
    // rowdata = {
    //
    // };
    // $(me.grid_id).jqGrid('addRowData', tmpcnt.length, rowdata);
    // $(".FrmListSyasyuMst.cmdUpdate").button("enable");
    // }
    // };
    //---20150819 fanzhengzhou del e.

    //---20150819 #2078 fanzhengzhou upd s.
    // me.setColSelection = function(key, colNowName, colNextName, colPreviousName)
    // {
    // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
    // //enter
    // if (key == 13)
    // {
    // if (me.lastsel < tmpcnt.length - 1)
    // {
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('editRow', (parseInt(me.lastsel) + 1), true);
    // $('#' + (parseInt(me.lastsel) + 1) + '_' + colNowName).focus();
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // }
    // return false;
    // }
    // if (key == 39)
    // {
    // //right
    // $('#' + me.lastsel + '_' + colNextName).focus();
    // return false;
    // }
    // if (key == 37)
    // {
    // //left
    // $('#' + me.lastsel + '_' + colPreviousName).focus();
    // return false;
    // }
    // if (key == 38)
    // {
    // //top
    // if (me.lastsel > 0)
    // {
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('editRow', (parseInt(me.lastsel) - 1 ), true);
    // $('#' + (parseInt(me.lastsel) - 1 ) + '_' + colNowName).focus();
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) - 1));
    // }
    // return false;
    // }
    // if (key == 40)
    // {
    // //bottom
    // if (me.lastsel < tmpcnt.length - 1)
    // {
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('editRow', (parseInt(me.lastsel) + 1), true);
    // $('#' + (parseInt(me.lastsel) + 1) + '_' + colNowName).focus();
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // }
    // return false;
    // }
    // if (key >= 65 && key <= 90 || key >= 48 && key <= 57 || key >= 96 && key <= 105 || key >= 186 && key <= 222 || key >= 109 && key <= 111 || key == 106 || key == 107)
    // {
    // me.keyupAddrow();
    // }
    // if (key == 222)
    // {
    // return false;
    // }
    // return true;
    // };

    //---20150819 #2078 fanzhengzhou upd e.

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
        var data = {
            rowDatas: $(me.grid_id).jqGrid("getRowData"),
            txtCarKbn: $(".FrmListSyasyuMst.txtCarKbn")
                .val()
                .toString()
                .trimEnd(),
            txtKkrCD: $(".FrmListSyasyuMst.txtKkrCD")
                .val()
                .toString()
                .trimEnd(),
        };
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.NoActionFnc = function () {
        return;
    };
    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        me.updateUrl = me.sys_id + "/" + me.id + "/" + "fncSingleDelete";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
                var allRowData = $(me.grid_id).jqGrid("getRowData");
                for (var tmpI = rowID; tmpI <= allRowData.length - 1; tmpI++) {
                    $(me.grid_id).jqGrid(
                        "setRowData",
                        parseInt(tmpI),
                        allRowData[parseInt(tmpI) + 1]
                    );
                    $(me.grid_id).jqGrid("delRowData", allRowData.length - 1);
                }
                $(me.grid_id).jqGrid("setSelection", rowID);
            }
        };
        var data = {
            KUKURI_CD: rowData["KUKURI_CD"],
        };
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.cancelDelRowData = function () {};

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

    me.checkUnique_Update = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length; i++) {
            for (var j = i + 1; j < grid_data1.length; j++) {
                if (
                    grid_data1[i]["KUKURI_CD"] != "" &&
                    grid_data1[j]["KUKURI_CD"] != ""
                ) {
                    if (
                        grid_data1[i]["KUKURI_CD"].toString().toUpperCase() ==
                        grid_data1[j]["KUKURI_CD"].toString().toUpperCase()
                    ) {
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "キー項目が重複しています"
                        );
                        return false;
                    }
                }
            }
        }
        //
        if (grid_data1.length > me.loadGridRowCnt) {
            var whereStr = "KUKURI_CD in (";
            //---20150831 #1971 fanzhengzhou upd s.
            // for (var i = parseInt(me.loadGridRowCnt); i < (parseInt(grid_data1.length) - 1); i++) {
            // whereStr += "'" + grid_data1[i]["KUKURI_CD"] + "'";
            // if (i != parseInt(grid_data1.length) - 2) {
            // whereStr += ",";
            // }
            // }
            for (
                var i = parseInt(me.loadGridRowCnt);
                i < parseInt(grid_data1.length);
                i++
            ) {
                whereStr += "'" + grid_data1[i]["KUKURI_CD"] + "'";
                if (i != parseInt(grid_data1.length) - 1) {
                    whereStr += ",";
                }
            }
            //---20150831 #1971 fanzhengzhou upd e.
            whereStr += ")";
            me.updateUrl = me.sys_id + "/" + me.id + "/" + "fncSelectKUKURICD";
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["result"] == true) {
                    if (result["data"].length > 0) {
                        var grid_data_tmp = $(me.grid_id).jqGrid("getRowData");
                        for (key in grid_data_tmp) {
                            var tmp1 = $(me.grid_id).jqGrid("getRowData", key);
                            if (
                                tmp1["KUKURI_CD"] ==
                                result["data"][0]["KUKURI_CD"]
                            ) {
                                $(me.grid_id).jqGrid("setSelection", key);
                                $(me.grid_id).jqGrid("editRow", key, true);
                                $("#" + key + "_KUKURI_CD").trigger("focus");
                                me.clsComFnc.FncMsgBox(
                                    "E9999",
                                    "括りコード(" +
                                        result["data"][0]["KUKURI_CD"] +
                                        ")は既に存在しています！"
                                );
                            }
                        }
                    } else {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
                        me.clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
                        me.clsComFnc.FncMsgBox("QY010");
                    }
                }
            };
            var data = {
                whereStr: whereStr,
            };
            me.ajax.send(me.updateUrl, data, 0);
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
            me.clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
            me.clsComFnc.FncMsgBox("QY010");
        }

        return true;
    };

    me.focusSaveRowData = function () {
        var tt = $(me.grid_id).jqGrid("getGridParam", "selrow");
        me.focusSaveRowDataArr[tt] = $(me.grid_id).jqGrid("getRowData", tt);
    };
    me.tempPackage = function () {
        if (me.buttonActionFlg != "update") {
            me.focusSaveRowData();
        }
    };
    me.fncInputChk = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        for (k = 0; k < parseInt(arrIds.length); k++) {
            $(me.grid_id).jqGrid("saveRow", k, true);
        }
        var tableHeaderTextArr = {
            KUKURI_CD: "括りコード",
            CAR_KBN: "区分",
            LINE_NO: "ラインNo",
        };
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length; i++) {
            if (
                grid_data1[i]["KUKURI_CD"] != "" ||
                grid_data1[i]["LINE_NO"] != "" ||
                grid_data1[i]["CAR_KBN"] != ""
            ) {
                for (key in grid_data1[i]) {
                    switch (key) {
                        case "KUKURI_CD":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR2,
                                me.colModel[0]["editoptions"]["maxlength"]
                            );
                            break;
                        case "CAR_KBN":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR2,
                                me.colModel[2]["editoptions"]["maxlength"]
                            );
                            break;
                        case "LINE_NO":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
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
                            $("#" + i + "_" + key).trigger("focus");
                            me.clsComFnc.FncMsgBox(
                                "W000" + (parseInt(me.intRtn) * -1).toString(),
                                tableHeaderTextArr[key]
                            );
                            return false;
                    }
                }
                //キー項目の必須ﾁｪｯｸ'
                if (grid_data1[i]["KUKURI_CD"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_KUKURI_CD").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "括りコード");
                    return false;
                }
                //カー区分のチェック
                if (grid_data1[i]["CAR_KBN"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_CAR_KBN").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "区分");
                    return false;
                }
                //ライン№の必須チェック
                if (grid_data1[i]["LINE_NO"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_LINE_NO").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "ラインNo.");
                    return false;
                }
                //区分のチェック
                if (grid_data1[i]["CAR_KBN"] != "") {
                    switch (grid_data1[i]["CAR_KBN"]) {
                        case "0":
                            break;
                        case "1":
                            break;
                        case "2":
                            break;
                        default:
                            $(me.grid_id).jqGrid("setSelection", i);
                            $("#" + i + "_CAR_KBN").trigger("focus");
                            me.clsComFnc.FncMsgBox("W0002", "区分");
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
    var o_R4_FrmListSyasyuMst = new R4.FrmListSyasyuMst();
    o_R4_FrmListSyasyuMst.load();
    o_R4K_R4K.FrmListSyasyuMst = o_R4_FrmListSyasyuMst;
});
