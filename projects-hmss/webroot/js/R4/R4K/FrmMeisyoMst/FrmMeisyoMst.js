/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150810           #1982 1979 1980              BUG                              li
 * 20150819           #2078                                                         FANZHENGZHOU
 * 20201118           bug                          jqgridにスクロール・バーがあります           WANGYING
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmMeisyoMst");

R4.FrmMeisyoMst = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmMeisyoMst";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmMeisyoMst_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncMeisyouMstSelect";
    me.pager = "";
    // '#FrmMeisyoMst_pager';
    me.sidx = "";
    me.actionFlg = "";
    me.lastsel = 0;
    me.cursel = 0;
    me.tmpSaveRowData = new Array();
    me.saveSelectedRowData = new Array();
    me.commonTempVar = "";
    me.loadGridRowCnt = 0;
    me.focusSaveRowDataArr = new Array();
    me.buttonActionFlg = "";
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
        id: ".FrmMeisyoMst.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMeisyoMst.cmdSearch",
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
    $(".FrmMeisyoMst.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });
    $(".FrmMeisyoMst.cmdSearch").click(function () {
        me.fnc_click_cmdSearch();
    });
    $(".FrmMeisyoMst.txtID").keyup(function () {
        me.fnc_keyup_txtID();
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
        me.FrmMeisyoMst_load();
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
                name: "MEISYOU_CD",
                label: "名称コード",
                index: "MEISYOU_CD",
                width: 80,
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
                            fn: function () {},
                        },
                    ],
                },
            },
            {
                name: "MEISYOU",
                label: "名称名",
                index: "MEISYOU",
                width: 250,
                sortable: false,
                align: "left",
                editable: true,

                editoptions: {
                    maxlength: 40,
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
                name: "MEISYOU_RN",
                label: "名称略称",
                index: "MEISYOU_RN",
                width: 150,
                sortable: false,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 18,
                    dataEvents: [
                        {
                            type: "blur",
                            fn: function () {},
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
                name: "MOJI1",
                label: "文字1",
                index: "MOJI1",
                width: 100,
                sortable: false,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 40,
                    dataEvents: [
                        {
                            type: "blur",
                            fn: function () {},
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
                name: "MOJI2",
                //20201118 wangying upd S
                // label : "文字 2",
                label: "文字2",
                //20201118 wangying upd E
                index: "MOJI2",
                width: 100,
                sortable: false,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 40,
                    dataEvents: [
                        {
                            type: "blur",
                            fn: function () {},
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
                name: "SUCHI1",
                label: "数値1",
                index: "SUCHI1",
                width: 100,
                sortable: false,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 9,
                    dataEvents: [
                        {
                            type: "blur",
                            fn: function () {
                                //me.MathRound(13, this);
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
                name: "SUCHI2",
                label: "数値2",
                index: "SUCHI2",
                width: 100,
                sortable: false,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 9,
                    dataEvents: [
                        {
                            type: "blur",
                            fn: function () {
                                //me.MathRound(13, this);
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
                label: "CREATE_DATE",
                index: "CREATE_DATE",
                width: 85,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "UPD_DATE",
                label: "UPD_DATE",
                index: "UPD_DATE",
                width: 85,
                sortable: false,
                align: "left",
                hidden: true,
            },
        ];
        me.complete_fun = function () {
            me.firstData = $(me.grid_id).jqGrid("getRowData");
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            //add rows
            var rowdata = {};
            for (var i = arrIds.length; i < 100; i++) {
                $(me.grid_id).jqGrid("addRowData", i, rowdata);
            }
            me.loadGridRowCnt = arrIds.length;
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
                            //---20150810 li DEL S.
                            // if (rowData['MEISYOU_CD'].toString().trimEnd() == "")
                            // {
                            // return;
                            // }
                            //---20150810 li DEL E.
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
                },
            });
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            if (arrIds.length == 0) {
                $(".FrmMeisyoMst.cmdUpdate").button("disable");
                $(".FrmMeisyoMst.cmdInsert").button("enable");
            } else {
                $(".FrmMeisyoMst.cmdUpdate").button("enable");
                $(".FrmMeisyoMst.cmdInsert").button("enable");
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
        //20201118 wangying upd S
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, 970);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 977);
        //20201118 wangying upd E
        //20180302 lqs UPD S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 207 : 250
        );
        //20180302 lqs UPD E
        //---20150819 #2078 fanzhengzhou add s.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150819 #2078 fanzhengzhou add e.
    };

    me.FrmMeisyoMst_load = function () {
        $(".FrmMeisyoMst.cmdUpdate").button("disable");
        $(".FrmMeisyoMst.cmdSearch").button("disable");
        me.initGrid();
    };
    //--event functions--
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
            txtID: $(".FrmMeisyoMst.txtID").val().toString().trimEnd(),
        };
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
    };
    //--keyup functions--
    me.fnc_keyup_txtID = function () {
        var txtIDVal = $(".FrmMeisyoMst.txtID").val().toString().trimEnd();
        if (txtIDVal != "") {
            $(".FrmMeisyoMst.cmdSearch").button("enable");
        } else {
            $(".FrmMeisyoMst.cmdSearch").button("disable");
            $(".FrmMeisyoMst.cmdUpdate").button("disable");
            $(me.grid_id).jqGrid("clearGridData");
        }
    };

    //---20150819 #2078 fanzhengzhou upd s.
    // //--functions--
    // me.setColSelection = function(key, colNowName, colNextName, colPreviousName, shiftKey)
    // {
    // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
    // //---20150810 li INS S.
    // if (shiftKey && key == 9) {
    // if (parseInt(me.lastsel) == 0 && colNowName == "MEISYOU_CD") {
    // return false;
    // } else {
    // if (colNowName == "MEISYOU_CD"){
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) - 1 ), true);
    // var selNextId = '#' + (parseInt(me.lastsel) ) + '_SUCHI2' ;
    // }else
    // {
    // var selNextId = '#' + parseInt(me.lastsel) + '_' + colPreviousName;
    // }
    // $(selNextId).focus();
    // }
    // return false;
    // }
    // //---20150810 li INS E.
    // //enter
    // if (key == 13 || key == 9)
    // {
    // if (me.lastsel < tmpcnt.length - 1)
    // {
    // if (colNowName == colNextName)
    // {
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // $('#' + (parseInt(me.lastsel) + 1 ) + "_MEISYOU_CD").focus();
    // }
    // else
    // {
    // $('#' + me.lastsel + '_' + colNextName).focus();
    // }
    // }
    // return false;
    // }
    // if (key == 9 && key == 16)
    // {
    // if (me.lastsel < tmpcnt.length - 1)
    // {
    // if (colNowName == colNextName)
    // {
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // $('#' + (parseInt(me.lastsel) - 1 ) + "_MEISYOU_CD").focus();
    // }
    // else
    // {
    // $('#' + me.lastsel + '_' + colPreviousName).focus();
    // }
    // }
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
                $(me.grid_id).jqGrid("clearGridData");
                $(".FrmMeisyoMst.txtID").val("");
                $(".FrmMeisyoMst.cmdSearch").button("disable");
                $(".FrmMeisyoMst.cmdUpdate").button("disable");
            }
        };
        var data = {
            rowDatas: $(me.grid_id).jqGrid("getRowData"),
            txtID: $(".FrmMeisyoMst.txtID").val().toString().trimEnd(),
        };
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.NoActionFnc = function () {
        return;
    };
    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
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
                if (me.firstData.length - 1 >= parseInt(rowID)) {
                    me.firstData.splice(parseInt(rowID), 1);
                }
            }
        };
        $(me.grid_id).jqGrid("saveRow", rowID, null, "clientArray");
        var tt = $(me.grid_id).jqGrid("getRowData", rowID);
        var data = {
            MEISYOU_CD: tt["MEISYOU_CD"],
            MEISYOU_ID: $(".FrmMeisyoMst.txtID").val(),
        };
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.cancelDelRowData = function () {};
    //---20150811 li DEL S.
    // me.MathRound = function(key, obj)
    // {
    // if (key == 13 || key >= 37 && key <= 40 || key == 9)
    // {
    // if ($(obj).val() != "")
    // {
    // $(obj).val(Math.round($(obj).val()));
    // }
    // if ($(obj).val() === "NaN")
    // {
    // $(obj).val(me.commonTempVar);
    // }
    // };
    // };
    //---20150811 li DEL E.

    me.checkUnique_Update = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length; i++) {
            for (var j = i + 1; j < grid_data1.length; j++) {
                if (
                    grid_data1[i]["MEISYOU_CD"] != "" &&
                    grid_data1[j]["MEISYOU_CD"] != ""
                ) {
                    if (
                        grid_data1[i]["MEISYOU_CD"].toString().toUpperCase() ==
                        grid_data1[j]["MEISYOU_CD"].toString().toUpperCase()
                    ) {
                        var row = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["MEISYOU_CD"]
                                    .toString()
                                    .toUpperCase() !==
                                grid_data1[i]["MEISYOU_CD"]
                                    .toString()
                                    .toUpperCase()
                            ) {
                                var row = i;
                            }
                        }
                        $(me.grid_id).jqGrid("setSelection", row);
                        $("#" + row + "_MEISYOU_CD").trigger("focus");
                        $("#" + row + "_MEISYOU_CD").trigger("select");
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "キー項目が重複しています"
                        );
                        return false;
                    }
                }
            }
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
        me.clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
        me.clsComFnc.FncMsgBox("QY010");
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
            MEISYOU_CD: "名称コード",
            MEISYOU: "名称名",
            MEISYOU_RN: "名称略称",
            MOJI1: "文字1",
            MOJI2: "文字 2",
            SUCHI1: "数値1",
            SUCHI2: "数値2",
        };
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length; i++) {
            if (
                grid_data1[i]["MEISYOU_CD"] != "" ||
                grid_data1[i]["MEISYOU"] != "" ||
                grid_data1[i]["MEISYOU_RN"] != "" ||
                grid_data1[i]["MOJI1"] != "" ||
                grid_data1[i]["MOJI2"] != "" ||
                grid_data1[i]["SUCHI1"] != "" ||
                grid_data1[i]["SUCHI2"] != ""
            ) {
                for (key in grid_data1[i]) {
                    switch (key) {
                        case "MEISYOU_CD":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR1,
                                me.colModel[0]["editoptions"]["maxlength"]
                            );
                            break;
                        case "MEISYOU":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                me.colModel[1]["editoptions"]["maxlength"]
                            );
                            break;
                        case "MEISYOU_RN":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                me.colModel[2]["editoptions"]["maxlength"]
                            );
                            break;
                        case "MOJI1":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                me.colModel[3]["editoptions"]["maxlength"]
                            );
                            break;
                        case "MOJI2":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                me.colModel[4]["editoptions"]["maxlength"]
                            );
                            break;
                        case "SUCHI1":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[5]["editoptions"]["maxlength"]
                            );
                            break;
                        case "SUCHI2":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[6]["editoptions"]["maxlength"]
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
                if (grid_data1[i]["MEISYOU_CD"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_MEISYOU_CD").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "名称コード");
                    return false;
                }
                //カー区分のチェック
                if (grid_data1[i]["MEISYOU"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_MEISYOU").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "名称名");
                    return false;
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
    var o_R4_FrmMeisyoMst = new R4.FrmMeisyoMst();
    o_R4_FrmMeisyoMst.load();
    o_R4K_R4K.FrmMeisyoMst = o_R4_FrmMeisyoMst;
});
