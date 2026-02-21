/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                              担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150811           #1966	1967 1968				BUG                             Yuanjh
 * 20150812           #1973                                                         FANZHENGZHOU
 * 20150819           #2078                                                         FANZHENGZHOU
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSyasyu");

R4.FrmSyasyu = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmSyasyu";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmSyasyu_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFrmSyasyuSelect";
    me.pager = "";
    // '#FrmSyasyu_pager';
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
    me.delDataFlg = false;
    me.firstData = new Array();
    me.frontLineNo2 = -1;
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmSyasyu.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyu.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyu.cmdCancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyu.cmdCopy",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyu.cmdSearch",
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
    $(".FrmSyasyu.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmSyasyu.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });
    $(".FrmSyasyu.cmdCancel").click(function () {
        me.fnc_click_cmdCancel();
    });
    $(".FrmSyasyu.cmdSearch").click(function () {
        me.fnc_click_cmdSearch();
    });
    $(".FrmSyasyu.txtKANA").keydown(function (e) {
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
        me.FrmSyasyu_load();
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
                width: 80,
                sortable: true,
                align: "left",

                editable: true,
                editoptions: {
                    maxlength: 3,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150814 #1973 fanzhengzhou upd s.
                                if (key == 229) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "SS_CD",
                                        "ARARI",
                                        true,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150814 #1973 fanzhengzhou upd e.
                            },
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
                name: "SS_CD",
                label: "車種コード",
                index: "SS_CD",
                width: 80,
                sortable: true,
                align: "left",
                editable: true,

                editoptions: {
                    maxlength: 8,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150814 #1973 fanzhengzhou upd s.
                                if (key == 229) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "SS_NAME",
                                        "UCOYA_CD",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150814 #1973 fanzhengzhou upd e.
                            },
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
                name: "SS_NAME",
                label: "車種名",
                index: "SS_NAME",
                width: 450,
                sortable: true,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 40,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150814 #1973 fanzhengzhou upd s.
                                if (key == 229) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "ARARI",
                                        "SS_CD",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150814 #1973 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.editDataFlg = true;
                                me.commonTempVar = $(this).val();
                                //me.MathRound(13, this);
                            },
                        },
                    ],
                },
            },
            {
                name: "ARARI",
                label: "粗利率",
                index: "ARARI",
                width: 85,
                sortable: false,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 4,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150814 #1973 fanzhengzhou upd s.
                                if (key == 229) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "UCOYA_CD",
                                        "SS_NAME",
                                        false,
                                        true
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150814 #1973 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.editDataFlg = true;
                                me.commonTempVar = $(this).val();
                            },
                        },
                    ],
                },
            },
            {
                name: "UPD_DATE",
                label: "UPD_DATE",
                index: "UPD_DATE",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
            {
                name: "CREATE_DATE",
                label: "CREATE_DATE",
                index: "CREATE_DATE",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
            {
                name: "UPD_SYA_CD",
                label: "UPD_SYA_CD",
                index: "UPD_SYA_CD",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
            {
                name: "UPD_PRG_ID",
                label: "UPD_PRG_ID",
                index: "UPD_PRG_ID",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
            {
                name: "UPD_CLT_NM",
                label: "UPD_CLT_NM",
                index: "UPD_CLT_NM",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
        ];
        me.complete_fun = function () {
            me.focusSaveRowData();
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            me.loadGridRowCnt = arrIds.length;
            //---add saved row
            if (me.tmpSaveRowData.length > 0) {
                var tmpcnt1 = $(me.grid_id).jqGrid("getDataIDs");
                var tt = 0;
                for (var tmpI = 0; tmpI < me.tmpSaveRowData.length; tmpI++) {
                    $(me.grid_id).jqGrid(
                        "addRowData",
                        parseInt(tmpcnt1.length) + tt,
                        me.tmpSaveRowData[tmpI]
                    );
                    tt++;
                }
            }
            //---
            rowdata = {
                ID: "",
                TOA_NAME: "",
                HTA_PRC: "",
                TNP_PRC: "",
                FZK_PRC: "",
                SOU_HABA: "",
                SYA_PCS: "",
                SIK_PCS: "",
                FZK_PCS: "",
                FZK_RIE: "",
                KTN_PCS: "",
                KTN_HABA: "",
                TYK_HABA: "",
                TYK_PCS: "",
                F_PCS: "",
                F_HABA: "",
            };
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            $(me.grid_id).jqGrid("addRowData", arrIds.length, rowdata);
            if (arrIds.length >= 1) {
                $(me.grid_id).jqGrid("setSelection", 0);
            }
            me.firstData = $(me.grid_id).jqGrid("getRowData");
            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    /*if ( typeof (e) != 'undefined')
                    {
                    me.focusSaveRowData();

                    }
                    if (me.frontLineNo == -1)
                    {
                    me.frontLineNo = rowid;
                    }
                    if (me.frontLineNo != rowid)
                    {
                    console.log("rowid not equal...");
                    me.frontLineNo1 = me.frontLineNo;
                    if (me.showMsgTF != true)
                    {
                    if (me.checkNull())
                    {
                    me.checkUnique();
                    }
                    }
                    }

                    if ( typeof (e) != 'undefined')
                    {
                    $(me.grid_id).jqGrid('saveRow', me.lastsel);
                    me.lastsel = rowid;
                    $(me.grid_id).jqGrid('editRow', rowid, true);
                    }
                    else
                    {
                    $(me.grid_id).jqGrid('saveRow', me.lastsel);
                    $(me.grid_id).jqGrid('editRow', rowid, true);
                    me.lastsel = rowid;
                    }
                    $(".numeric").numeric(
                    {
                    decimal : false,
                    negative : false
                    });
                    me.frontLineNo = rowid;*/
                    //-------------------------------------------------
                    me.delDataFlg = false;
                    //-----20150811 Yuan modify S.
                    var focusIndex =
                        typeof e != "undefined"
                            ? e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex
                            : false;
                    if (typeof e != "undefined") {
                        var cellIndex = e.target.cellIndex;
                        if (cellIndex != 0) {
                            if (rowid && rowid != me.lastsel) {
                                me.focusSaveRowData1(rowid);
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
                                focusField: focusIndex,
                            });
                        } else {
                            me.delDataFlg = true;
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
                            me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                            me.clsComFnc.MessageBox(
                                "削除します。よろしいですか？",
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
                    }
                    /*
                     if ( typeof (e) != 'undefined') {
                     if (rowid && rowid != me.lastsel) {
                     me.focusSaveRowData1(rowid);
                     $(me.grid_id).jqGrid('saveRow', me.lastsel);
                     me.lastsel = rowid;
                     }
                     $(me.grid_id).jqGrid('editRow', rowid, true);
                     $('input,select', e.target).focus();
                     } else {
                     if (rowid && rowid != me.lastsel) {
                     $(me.grid_id).jqGrid('saveRow', me.lastsel);
                     me.lastsel = rowid;
                     }
                     $(me.grid_id).jqGrid('editRow', rowid, true);
                     }
                     */

                    if (me.frontLineNo == -1) {
                        me.frontLineNo = rowid;
                    }

                    if (!me.delDataFlg) {
                        if (me.frontLineNo != rowid) {
                            me.frontLineNo1 = me.frontLineNo;
                            if (me.showMsgTF != true) {
                                if (me.copyButtonTF == "copy") {
                                } else {
                                    if (me.checkNull(e)) {
                                        me.checkUnique(e);
                                    }
                                }
                            }
                        }
                    }
                    //-----20150811 Yuan modify E.
                    $(".numeric").numeric({
                        decimal: false,
                        negative: false,
                    });
                    me.frontLineNo = rowid;
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
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 780);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        //---20150819 #2078 fanzhengzhou add s.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150819 #2078 fanzhengzhou add e.
    };

    me.FrmSyasyu_load = function () {
        me.initGrid();
        // me.fnckeyDown46();
    };
    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        me.buttonActionFlg = "insert";
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < arrIds.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        $(me.grid_id).jqGrid("setSelection", parseInt(arrIds.length) - 1);
        $(me.grid_id).jqGrid("editRow", parseInt(arrIds.length) - 1, true);
        //$("#" + (parseInt(arrIds.length) - 1) + "_UCOYA_CD").trigger("focus");
    };
    me.fnc_click_cmdUpdate = function () {
        me.buttonActionFlg = "update";
        if (me.editDataFlg == false) {
            return;
        }
        //var grid_data = $(me.grid_id).jqGrid('getRowData');

        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        if (arrIds.length == 1) {
            return;
        }
        if (!me.checkNull_Update()) {
            return;
        }
        if (!me.checkUnique_Update()) {
            return;
        }

        //確認メッセージ
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesUpdateFnc;
        me.clsComFnc.MsgBoxBtnFnc.No = me.NoUpdateFnc;
        me.clsComFnc.FncMsgBox("QY010");
    };
    me.fnc_click_cmdCancel = function () {
        me.buttonActionFlg = "cancel";
        var tmpdata = {};
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
    };
    me.fnc_click_cmdSearch = function () {
        me.buttonActionFlg = "search";
        var tmpdata = {
            txtKANA: $(".FrmSyasyu.txtKANA").val().toString().trimEnd(),
        };
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
    };
    //--functions--
    me.keyupAddrow = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");

        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (tmpcnt.length - 1 == rowID) {
            rowdata = {};
            $(me.grid_id).jqGrid("addRowData", tmpcnt.length, rowdata);
        }
    };

    //---20150814 #1973 fanzhengzhou upd s.
    me.setColSelection = function (
        e,
        key,
        colNextName,
        colPreviousName,
        firstCol,
        lastCol
    ) {
        var GridRecords = $(me.grid_id).jqGrid("getGridParam", "records");
        if (key == 13) {
            return false;
        }
        if ((e.shiftKey && key == 37) || (e.shiftKey && key == 39)) {
            return true;
        } else {
            //Shift+Tab&&Left
            if ((e.shiftKey && key == 9) || key == 37) {
                if (firstCol == true && parseInt(me.lastsel) == 0) {
                    return false;
                } else if (firstCol == true && parseInt(me.lastsel) > 0) {
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    $(me.grid_id).jqGrid(
                        "setSelection",
                        parseInt(me.lastsel) - 1,
                        true
                    );
                }
                $("#" + me.lastsel + "_" + colPreviousName).trigger("focus");
                $("#" + me.lastsel + "_" + colPreviousName).trigger("select");
                return false;
            }

            //Tab&&Right
            if (key == 9 || key == 39) {
                if (lastCol == true && me.lastsel == GridRecords - 1) {
                    return false;
                } else if (lastCol == true && me.lastsel < GridRecords - 1) {
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    $(me.grid_id).jqGrid(
                        "setSelection",
                        parseInt(me.lastsel) + 1
                    );
                }
                $("#" + me.lastsel + "_" + colNextName).trigger("focus");
                $("#" + me.lastsel + "_" + colNextName).trigger("select");
                return false;
            }
            if (me.lastsel == GridRecords - 1) {
                if (
                    (key >= 65 && key <= 90) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105) ||
                    (key >= 186 && key <= 222) ||
                    (key >= 109 && key <= 111) ||
                    key == 106 ||
                    key == 107
                ) {
                    me.keyupAddrow();
                }
            }
            if (key == 222) {
                return false;
            }
            return true;
        }
    };
    //---20150814 #1973 fanzhengzhou upd e.

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
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var allRowData = $(me.grid_id).jqGrid("getRowData");
        for (var tmpI = rowID; tmpI < allRowData.length - 1; tmpI++) {
            $(me.grid_id).jqGrid(
                "setRowData",
                parseInt(tmpI),
                allRowData[parseInt(tmpI) + 1]
            );
            $(me.grid_id).jqGrid("delRowData", allRowData.length - 1);
        }
        $(me.grid_id).jqGrid("setSelection", rowID);
        me.setButtonEnableState();
        if (me.firstData.length - 1 >= parseInt(rowID)) {
            me.firstData.splice(parseInt(rowID), 1);
        }
    };

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
                //--20150817 Yuanjh add S.
                var tmpdata = {};
                gdmz.common.jqgrid.reload(
                    me.grid_id,
                    tmpdata,
                    me.complete_fun
                );
                //--20150817 Yuanjh add E.
                me.clsComFnc.MessageBox(
                    "登録完了しました。",
                    "DB登録",
                    "OK",
                    "Information"
                );
            }
        };
        var data = $(me.grid_id).jqGrid("getRowData");
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.NoUpdateFnc = function () {};
    me.fnckeyDown46 = function () {
        me.inp = $(me.grid_id);
        me.inp.on("keydown", function () {
            //---20150811 Yuanjh modify S.
            /*
            var key = e.which;
            var oEvent = window.event;
            if (key == 46) {
            me.delRowData();   //BUG1968
            };
            */
            //---20150811 Yuanjh modify E.
        });
    };
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
    me.yesCorrectRowData = function () {
        $(me.grid_id).jqGrid("editRow", me.frontLineNo1);
        $(me.grid_id).jqGrid("setSelection", me.frontLineNo1);
        $("#" + me.frontLineNo1 + "_UCOYA_CD").trigger("focus");
        $("#" + me.frontLineNo1 + "_UCOYA_CD").trigger("select");
    };
    me.yesCorrectRowData_Update = function () {
        $(me.grid_id).jqGrid("editRow", me.frontLineNo2);
        $(me.grid_id).jqGrid("setSelection", me.frontLineNo2);
        me.showMsgTF = false;
    };
    me.closeCorrectRowData = function () {
        me.showMsgTF = false;
    };
    me.noCorrectRowData = function () {
        $(me.grid_id).jqGrid("setSelection", me.frontLineNo1);
        var currentRowId = parseInt(me.frontLineNo) + 1;
        $(me.grid_id).jqGrid("saveRow", me.frontLineNo, null, "clientArray");
        if (me.loadGridRowCnt < currentRowId) {
            if (me.focusSaveRowDataArr[me.frontLineNo]["UCOYA_CD"] == "") {
                $(me.grid_id).jqGrid(
                    "setRowData",
                    me.frontLineNo,
                    $(me.grid_id).jqGrid(
                        "getRowData",
                        parseInt(me.frontLineNo) + 1
                    )
                );
                $(me.grid_id).jqGrid(
                    "delRowData",
                    parseInt(me.frontLineNo) + 1
                );
                $(me.grid_id).jqGrid("editRow", parseInt(me.frontLineNo));
                $(me.grid_id).jqGrid("setSelection", me.frontLineNo);
            } else {
                $(me.grid_id).jqGrid(
                    "setRowData",
                    me.frontLineNo,
                    me.focusSaveRowDataArr[me.frontLineNo]
                );
                $(me.grid_id).jqGrid("setSelection", me.frontLineNo);
            }
        } else {
            $(me.grid_id).jqGrid(
                "setRowData",
                me.frontLineNo,
                me.focusSaveRowDataArr[me.frontLineNo]
            );
            $(me.grid_id).jqGrid("setSelection", me.frontLineNo);
        }
    };
    me.noCorrectRowData_Update = function () {
        var rowID = parseInt(me.frontLineNo2);
        currentRowId = rowID;
        if (me.loadGridRowCnt < currentRowId) {
            if (me.focusSaveRowDataArr[currentRowId]["UCOYA_CD"] == "") {
                $(me.grid_id).jqGrid(
                    "setRowData",
                    currentRowId,
                    $(me.grid_id).jqGrid(
                        "getRowData",
                        parseInt(currentRowId) + 1
                    )
                );
                $(me.grid_id).jqGrid("delRowData", parseInt(currentRowId) + 1);
                $(me.grid_id).jqGrid("editRow", parseInt(currentRowId));
                $(me.grid_id).jqGrid("setSelection", currentRowId);
                me.showMsgTF = false;
            } else {
                $(me.grid_id).jqGrid(
                    "setRowData",
                    currentRowId,
                    me.focusSaveRowDataArr[currentRowId]
                );
                $(me.grid_id).jqGrid("setSelection", currentRowId);
                me.showMsgTF = false;
            }
        } else {
            $(me.grid_id).jqGrid(
                "setRowData",
                currentRowId,
                me.focusSaveRowDataArr[currentRowId]
            );
            $(me.grid_id).jqGrid("setSelection", currentRowId);
            me.showMsgTF = false;
        }
    };
    me.checkUnique = function (e) {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            for (var j = i + 1; j < grid_data1.length; j++) {
                if (grid_data1[i]["UCOYA_CD"] != "") {
                    if (
                        grid_data1[i]["UCOYA_CD"].toString().toUpperCase() ==
                        grid_data1[j]["UCOYA_CD"].toString().toUpperCase()
                    ) {
                        me.showMsgTF = true;
                        me.frontLineNo1 = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["UCOYA_CD"]
                                    .toString()
                                    .toUpperCase() !==
                                grid_data1[i]["UCOYA_CD"]
                                    .toString()
                                    .toUpperCase()
                            ) {
                                me.frontLineNo1 = i;
                            }
                        }
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesCorrectRowData;
                        me.clsComFnc.MsgBoxBtnFnc.No = me.noCorrectRowData;
                        me.clsComFnc.MsgBoxBtnFnc.Close =
                            me.closeCorrectRowData;
                        me.clsComFnc.MessageBox(
                            "列'UC親コード'は一意であるように制約されています。値'" +
                                grid_data1[i]["UCOYA_CD"] +
                                "' は既に存在します。値を修正しますか?",
                            me.clsComFnc.GSYSTEM_NAME,
                            "YesNo",
                            "Question",
                            me.clsComFnc.MessageBoxDefaultButton.Button2
                        );
                        return false;
                    }
                }
            }
        }
        $(me.grid_id).jqGrid("editRow", me.lastsel, {
            focusField: false,
        });
        if (e != undefined) {
            $("input,select", e.target).trigger("focus");
        }
        return true;
    };
    me.checkUnique_Update = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            for (var j = i + 1; j < grid_data1.length; j++) {
                if (grid_data1[i]["UCOYA_CD"] != "") {
                    if (
                        grid_data1[i]["UCOYA_CD"].toString().toUpperCase() ==
                        grid_data1[j]["UCOYA_CD"].toString().toUpperCase()
                    ) {
                        me.showMsgTF = true;
                        me.frontLineNo2 = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["UCOYA_CD"]
                                    .toString()
                                    .toUpperCase() !==
                                grid_data1[i]["UCOYA_CD"]
                                    .toString()
                                    .toUpperCase()
                            ) {
                                me.frontLineNo2 = i;
                            }
                        }
                        me.clsComFnc.MsgBoxBtnFnc.Yes =
                            me.yesCorrectRowData_Update;
                        me.clsComFnc.MsgBoxBtnFnc.No =
                            me.noCorrectRowData_Update;
                        me.clsComFnc.MsgBoxBtnFnc.Close =
                            me.closeCorrectRowData;
                        me.clsComFnc.MessageBox(
                            "列'UC親コード'は一意であるように制約されています。値'" +
                                grid_data1[i]["UCOYA_CD"] +
                                "' は既に存在します。値を修正しますか?",
                            me.clsComFnc.GSYSTEM_NAME,
                            "YesNo",
                            "Question",
                            me.clsComFnc.MessageBoxDefaultButton.Button2
                        );
                        return false;
                    }
                }
            }
        }
        return true;
    };
    me.checkNull = function (e) {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            if (grid_data1[i]["UCOYA_CD"] == "") {
                var tmpField = "UC親コード";

                if (me.frontLineNo1 == tmpcnt.length - 1) {
                    return true;
                }
                me.showMsgTF = true;
                me.frontLineNo1 = i;
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesCorrectRowData;
                me.clsComFnc.MsgBoxBtnFnc.No = me.noCorrectRowData;
                me.clsComFnc.MsgBoxBtnFnc.Close = me.closeCorrectRowData;
                me.clsComFnc.MessageBox(
                    "列'" +
                        tmpField +
                        "'にNullを使用することはできません。値を修正しますか?",
                    me.clsComFnc.GSYSTEM_NAME,
                    "YesNo",
                    "Question",
                    me.clsComFnc.MessageBoxDefaultButton.Button2
                );
                return false;
            }
        }
        $(me.grid_id).jqGrid("editRow", me.lastsel, {
            focusField: false,
        });
        if (e != undefined) {
            $("input,select", e.target).trigger("focus");
        }
        return true;
    };
    me.checkNull_Update = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            if (
                grid_data1[i]["UCOYA_CD"] == "" &&
                grid_data1[i]["SS_CD"] == "" &&
                grid_data1[i]["SS_NAME"] == "" &&
                grid_data1[i]["ARARI"] == ""
            ) {
            } else {
                if (grid_data1[i]["UCOYA_CD"] == "") {
                    me.showMsgTF = true;
                    var tmpField = "UC親コード";
                    me.frontLineNo2 = i;
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesCorrectRowData_Update;
                    me.clsComFnc.MsgBoxBtnFnc.No = me.noCorrectRowData_Update;
                    me.clsComFnc.MessageBox(
                        "列'" +
                            tmpField +
                            "'にNullを使用することはできません。値を修正しますか?",
                        me.clsComFnc.GSYSTEM_NAME,
                        "YesNo",
                        "Question",
                        me.clsComFnc.MessageBoxDefaultButton.Button2
                    );
                    return false;
                }
            }
        }
        return true;
    };
    me.focusSaveRowData = function () {
        var tt = $(me.grid_id).jqGrid("getGridParam", "selrow");
        //me.focusSaveRowDataArr = $(me.grid_id).jqGrid('getRowData', tt);
        me.focusSaveRowDataArr[tt] = $(me.grid_id).jqGrid("getRowData", tt);
    };
    me.focusSaveRowData1 = function (rowid) {
        //var tt = $(me.grid_id).jqGrid('getGridParam', 'selrow');
        if (rowid && rowid != me.lastsel) {
            $(me.grid_id).jqGrid("saveRow", me.lastsel);
            me.focusSaveRowDataArr[rowid] = $(me.grid_id).jqGrid(
                "getRowData",
                rowid
            );
        }
    };
    me.tempPackage = function () {
        if (me.buttonActionFlg != "update") {
            me.focusSaveRowData();
        }
    };
    me.setButtonEnableState = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        if (arrIds.length >= 1) {
            $(".FrmSyasyu.cmdInsert").button("enable");
            $(".FrmSyasyu.cmdUpdate").button("enable");
            $(".FrmSyasyu.cmdCancel").button("enable");
        } else {
            $(".FrmSyasyu.cmdInsert").button("disable");
            $(".FrmSyasyu.cmdUpdate").button("disable");
            $(".FrmSyasyu.cmdCancel").button("disable");
        }
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyasyu = new R4.FrmSyasyu();
    o_R4_FrmSyasyu.load();
    o_R4K_R4K.FrmSyasyu = o_R4_FrmSyasyu;
});
