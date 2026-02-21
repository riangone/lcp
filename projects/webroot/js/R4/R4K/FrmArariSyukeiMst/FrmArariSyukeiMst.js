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
 * 20150812           #1972          「留保金率」：1の桁の「0」が表示されていない。また、少数点の末尾の「0」が表示されていない。  FANZHENGZHOU
 * 20150819           #2078                                                         FANZHENGZHOU
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmArariSyukeiMst");

R4.FrmArariSyukeiMst = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmArariSyukeiMst";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmArariSyukeiMst_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFrmArariSyukeiMstSelect";
    me.pager = "";
    //'#FrmArariSyukeiMst_sprMeisai_pager';
    // '#FrmArariSyukeiMst_pager';
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
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmArariSyukeiMst.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmArariSyukeiMst.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmArariSyukeiMst.cmdCopy",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmArariSyukeiMst.cmdSearch",
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
    $(".FrmArariSyukeiMst.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmArariSyukeiMst.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });
    $(".FrmArariSyukeiMst.cmdSearch").click(function () {
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
        me.FrmArariSyukeiMst_load();
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
                name: "OYA_CD",
                label: "車種集計コード",
                index: "OYA_CD",
                width: 120,
                sortable: false,
                align: "left",

                editable: false,
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
                name: "SS_NAME",
                label: "車種名",
                index: "SS_NAME",
                width: 300,
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
                            fn: function () {
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "UNTIN_RITU",
                label: "留保金率",
                index: "UNTIN_RITU",
                width: 88,
                sortable: false,
                align: "right",
                hidden: false,
                editable: true,
                //---20150812 #1972 fanzhengzhou add s.
                formatter: "number",
                formatoptions: {
                    decimalSeparator: ".",
                    decimalPlaces: 3,
                    defaultValue: "",
                },
                //---20150812 #1972 fanzhengzhou add e.
                editoptions: {
                    //---20150820 fanzhengzhou add s.
                    class: "numeric",
                    //---20150820 fanzhengzhou add e.
                    maxlength: 6,
                    dataEvents: [
                        {
                            type: "blur",
                            fn: function () {
                                if (!me.keydownCheck_UNTIN_RITUFormat(this)) {
                                    $(this).val(me.saveUNTIN_RITUVal);
                                }
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.saveUNTIN_RITUVal = $(this).val();
                                $(this).trigger("select");
                                me.editDataFlg = true;
                                me.commonTempVar = $(this).val();
                            },
                        },
                        //---20150820 fanzhengzhou del s.
                        // ,{
                        // type : 'keyup',
                        // fn : function(e) {
                        // this.value = this.value.replace(/[^\-\.\d]/g, "");
                        // this.value = this.value.replace(/^\./, "");
                        // this.value = this.value.replace(/.\-/g, "");
                        // }
                        // }
                        //---20150820 fanzhengzhou del e.
                    ],
                },
            },
            {
                name: "DISP_NO",
                label: "出力順",
                index: "DISP_NO",
                width: 88,
                sortable: false,
                align: "right",
                hidden: false,
                editable: true,
                editoptions: {
                    class: "numeric",
                    maxlength: 3,
                    dataEvents: [
                        //---20150820 fan del s.
                        // {
                        // type : 'blur',
                        // fn : function(e) {
                        // me.MathRound(13, this);
                        // },
                        // },
                        //---20150820 fan del e.
                        {
                            type: "focus",
                            fn: function () {
                                $(this).trigger("select");
                                me.editDataFlg = true;
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
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
        ];
        me.complete_fun = function () {
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            me.loadGridRowCnt = arrIds.length;
            if (arrIds.length >= 1) {
                $(me.grid_id).jqGrid("setSelection", 0);
            }

            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    var focusIndex =
                        typeof e != "undefined"
                            ? e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex
                            : false;
                    me.editDataFlg = $(me.grid_id).getColProp(
                        "OYA_CD"
                    ).editable;
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    if (rowid > me.loadGridRowCnt - 1) {
                        $(me.grid_id).setColProp("OYA_CD", {
                            editable: true,
                        });
                    } else {
                        $(me.grid_id).setColProp("OYA_CD", {
                            editable: false,
                        });
                    }

                    if (typeof e != "undefined") {
                        //編集可能なセルをクリック、上下キー
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
                            focusField: focusIndex < 2 ? true : focusIndex,
                        });
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
                    //---20150820 fanzhengzhou upd s.
                    // $(".numeric").numeric({
                    // decimal : true,
                    // negative : true
                    // });
                    $(".numeric").numeric({
                        decimal: ".",
                        negative: true,
                    });
                    //---20150820 fanzhengzhou upd e.
                    gdmz.common.jqgrid.setKeybordEvents(
                        me.grid_id,
                        e,
                        me.lastsel
                    );
                },
            });
            me.setButtonEnableState();
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
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 680);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        //---20150819 #2078 fanzhengzhou add s.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150819 #2078 fanzhengzhou add e.
    };

    me.FrmArariSyukeiMst_load = function () {
        me.initGrid();
    };
    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        me.Addrow();
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
    me.fnc_click_cmdSearch = function () {
        me.buttonActionFlg = "search";
        var tmpdata = {
            txtKANA: $(".FrmArariSyukeiMst.txtKANA").val().toString().trimEnd(),
        };
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
    };

    //---20150820 fanzhengzhou upd s.
    //--functions--
    me.Addrow = function () {
        // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        // for (var t = 0; t < tmpcnt.length; t++) {
        // $(me.grid_id).jqGrid('saveRow', t);
        // };
        // var rowID = $(me.grid_id).jqGrid('getGridParam', 'selrow');
        // rowData = $(me.grid_id).jqGrid("getRowData", (parseInt(tmpcnt.length) - 1));
        // if (!(rowData['OYA_CD'] == "" && rowData['SS_NAME'] == "" && rowData['UNTIN_RITU'] == "" && rowData['DISP_NO'] == "" && rowData['CREATE_DATE'] == "")) {
        // rowdata = {
        // };
        // $(me.grid_id).jqGrid('addRowData', tmpcnt.length, rowdata);
        // }
        // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        // $(me.grid_id).jqGrid('setSelection', (parseInt(tmpcnt.length) - 1));
        // $(me.grid_id).jqGrid('editRow', (parseInt(tmpcnt.length) - 1), true);
        // $('#' + (parseInt(tmpcnt.length) - 1) + '_OYA_CD').focus();

        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var rowdata = {};
        var records = $(me.grid_id).jqGrid("getGridParam", "records");
        $(me.grid_id).jqGrid("addRowData", records, rowdata);
        $(me.grid_id).jqGrid("setSelection", records);
    };
    //---20150820 fanzhengzhou upd e.

    //---20150819 #2078 fanzhengzhou s.
    // me.setColSelection = function(key, colNowName, colNextName, colPreviousName, obj)
    // {
    // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
    // var rowID = $(me.grid_id).jqGrid('getGridParam', 'selrow');
    //
    // //enter
    // if (key == 13)
    // {
    // if (colNowName == "UNTIN_RITU")
    // {
    // if (!me.keydownCheck_UNTIN_RITUFormat(obj))
    // {
    // return false;
    // };
    // };
    // if (me.lastsel < tmpcnt.length - 1)
    // {
    // if (colNowName == "DISP_NO")
    // {
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // $('#' + (parseInt(me.lastsel) + 1) + '_SS_NAME').focus();
    // $('#' + (parseInt(me.lastsel) + 1) + '_SS_NAME').select();
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
    // if (!me.keydownCheck_UNTIN_RITUFormat(obj))
    // {
    // return false;
    // };
    // //right
    // $('#' + me.lastsel + '_' + colNextName).focus();
    // $('#' + me.lastsel + '_' + colNextName).select();
    // return false;
    // }
    // if (key == 37)
    // {
    // if (!me.keydownCheck_UNTIN_RITUFormat(obj))
    // {
    // return false;
    // };
    // //left
    // $('#' + me.lastsel + '_' + colPreviousName).focus();
    // $('#' + me.lastsel + '_' + colPreviousName).select();
    // return false;
    // }
    // if (key == 38)
    // {
    // //top
    // if (!me.keydownCheck_UNTIN_RITUFormat(obj))
    // {
    // return false;
    // };
    //
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
    // if (!me.keydownCheck_UNTIN_RITUFormat(obj))
    // {
    // return false;
    // };
    //
    // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
    // var id = $(me.grid_id).jqGrid('getGridParam', 'selrow');
    // if (id == (parseInt(tmpcnt.length) - 1))
    // {
    // return;
    // };
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
    // me.setColSelection = function (
    //     e,
    //     key,
    //     colNowName,
    //     colNextName,
    //     colPreviousName,
    //     firstCol,
    //     lastCol
    // ) {};
    //---20150819 #2078 fanzhengzhou e.

    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (me.editDataFlg == true) {
            me.loadGridRowCnt--;
        }
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
        me.setButtonEnableState();
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
    me.keydownCheck_UNTIN_RITUFormat = function (obj) {
        if ($(obj).val() != "") {
            if ($(obj).val().toString().charAt(0) == "-") {
                if ($(obj).val().toString().charAt(2) != ".") {
                    var tt = $(obj).val().toString().length;
                    var tmpStr = "";
                    for (var i = 1; i < tt; i++) {
                        if (
                            $(obj).val().toString().charAt(i) <= 9 &&
                            $(obj).val().toString().charAt(i) >= 0 &&
                            $(obj).val().toString().charAt(0) == "-"
                        ) {
                            if (i == 2) {
                                tmpStr += ".";
                            }
                            tmpStr += $(obj).val().toString().charAt(i);
                        } else {
                            return false;
                        }
                    }
                    tmpStr = "-" + tmpStr;
                    if (parseInt(tmpStr.length) > 6) {
                        tmpStr = tmpStr.toString().substr(0, 6);
                    }
                    $(obj).val(tmpStr);
                }
            } else {
                if ($(obj).val().toString().charAt(1) != ".") {
                    var tt = $(obj).val().toString().length;
                    var tmpStr = "";
                    for (var i = 0; i < tt; i++) {
                        if (
                            $(obj).val().toString().charAt(i) <= 9 ||
                            $(obj).val().toString().charAt(i) >= 0
                        ) {
                            if (i == 1) {
                                tmpStr += ".";
                            }
                            tmpStr += $(obj).val().toString().charAt(i);
                        } else {
                            return false;
                        }
                    }
                    if (parseInt(tmpStr.length) >= 6) {
                        tmpStr = tmpStr.toString().substr(0, 5);
                    }
                    $(obj).val(tmpStr);
                }
            }
        }
        return true;
    };
    me.yesCorrectRowData = function () {
        $(me.grid_id).jqGrid("setSelection", me.frontLineNo1);
        me.showMsgTF = false;
    };
    me.tempPackage = function () {
        if (me.buttonActionFlg != "update") {
            me.focusSaveRowData();
        }
    };
    me.setButtonEnableState = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        if (arrIds.length >= 1) {
            $(".FrmArariSyukeiMst.cmdUpdate").button("enable");
        } else {
            $(".FrmArariSyukeiMst.cmdUpdate").button("disable");
        }
    };

    me.fncInputChk = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        for (k = 0; k < parseInt(arrIds.length); k++) {
            $(me.grid_id).jqGrid("saveRow", k, true);
        }
        var tableHeaderTextArr = {
            OYA_CD: "車種集計コード",
            SS_NAME: "車種名",
            UNTIN_RITU: "留保金率",
            DISP_NO: "出力順",
        };
        var blnInputFlg_1 = false;

        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length; i++) {
            if (
                grid_data1[i]["OYA_CD"] != "" ||
                grid_data1[i]["SS_NAME"] != "" ||
                grid_data1[i]["UNTIN_RITU"] != "" ||
                grid_data1[i]["OYA_CD"] != "" ||
                grid_data1[i]["DISP_NO"] != ""
            ) {
                //for (var j = i + 1; j < grid_data1.length; j++)
                for (key in grid_data1[i]) {
                    switch (key) {
                        case "OYA_CD":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR2,
                                me.colModel[0]["editoptions"]["maxlength"]
                            );
                            break;
                        case "SS_NAME":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                me.colModel[1]["editoptions"]["maxlength"]
                            );
                            break;
                        case "UNTIN_RITU":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[2]["editoptions"]["maxlength"]
                            );
                            break;
                        case "DISP_NO":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER1,
                                me.colModel[3]["editoptions"]["maxlength"]
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
                            $(me.grid_id).jqGrid("editRow", i, {
                                keys: true,
                                focusField: false,
                            });
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
                if (grid_data1[i]["OYA_CD"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $(me.grid_id).jqGrid("editRow", i, true);
                    $("#" + i + "_OYA_CD").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "車種集計コード");
                    return false;
                }
                blnInputFlg_1 = true;
            }
        }
        if (blnInputFlg_1 == false) {
            me.clsComFnc.FncMsgBox("W0017", "データ");
            $(me.grid_id).jqGrid("setSelection", 0);
            $(me.grid_id).jqGrid("editRow", 0, true);
            $("#" + 0 + "_OYA_CD").trigger("focus");
            return false;
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            for (var j = i + 1; j < grid_data1.length; j++) {
                if (
                    grid_data1[i]["OYA_CD"] != "" &&
                    grid_data1[j]["OYA_CD"] != ""
                ) {
                    if (
                        grid_data1[i]["OYA_CD"].toString().toUpperCase() ==
                        grid_data1[j]["OYA_CD"].toString().toUpperCase()
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
        return true;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmArariSyukeiMst = new R4.FrmArariSyukeiMst();
    o_R4_FrmArariSyukeiMst.load();
    o_R4K_R4K.FrmArariSyukeiMst = o_R4_FrmArariSyukeiMst;
});
