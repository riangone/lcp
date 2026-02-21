/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150811           #1978                        BUG                              li
 * 20150812           #1976           金額の欄にマイナスの金額が入力できない                 FANZHENGZHOU
 * 20150812           #1975           金額の欄の表示形式が異なる                            FANZHENGZHOU
 * 20150819           #2078                                                         FANZHENGZHOU
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSyanaiGenkaMst");

R4.FrmSyanaiGenkaMst = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmSyanaiGenkaMst";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmSyanaiGenkaMst_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFrmSyanaiGenkaMstSelect";
    me.pager = "";
    // '#FrmSyanaiGenkaMst_pager';
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
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmSyanaiGenkaMst.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyanaiGenkaMst.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyanaiGenkaMst.cmdSearch",
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
    $(".FrmSyanaiGenkaMst.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmSyanaiGenkaMst.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });
    $(".FrmSyanaiGenkaMst.cmdSearch").click(function () {
        me.fnc_click_cmdSearch();
    });
    $(".FrmSyanaiGenkaMst.txtNauKB").keydown(function (e) {
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
        me.FrmSyanaiGenkaMst_load();
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
                name: "KIJYUN_DT",
                label: "年月日",
                index: "KIJYUN_DT",
                width: 80,
                sortable: true,
                align: "left",

                editable: true,
                editoptions: {
                    maxlength: 8,
                    dataEvents: [
                        {
                            type: "keydown",
                            //type : 'keyup',
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "KIJYUN_DT", "NAU_KB", "KIJYUN_DT")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "NAU_KB",
                                        "HAIBUN_GK3",
                                        true,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                //me.MathRound(13, this);
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
                name: "NAU_KB",
                label: "新中区分",
                index: "NAU_KB",
                width: 80,
                sortable: true,
                align: "left",
                editable: true,

                editoptions: {
                    maxlength: 1,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "NAU_KB", "KJN_GENKA", "KIJYUN_DT")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "KJN_GENKA",
                                        "KIJYUN_DT",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
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
                name: "KJN_GENKA",
                label: "社内原価",
                index: "KJN_GENKA",
                width: 100,
                sortable: true,
                align: "left",
                hidden: false,
                editable: true,
                //---20150812 #1975 fanzhengzhou upd s.
                //formatter : "number",
                // formatoptions :
                // {
                // thousandsSeparator : ",",
                // decimalPlaces : 0,
                // defaultValue : ""
                // },
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                align: "right",
                //---20150812 #1975 fanzhengzhou upd e.
                editoptions: {
                    //---20150812 #1976 fanzhengzhou upd s.
                    class: "numeric",
                    //maxlength : 7,
                    maxlength: 8,
                    //---20150812 #1976 fanzhengzhou upd e.
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "KJN_GENKA", "HAIBUN_GK1", "NAU_KB")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "HAIBUN_GK1",
                                        "NAU_KB",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                //me.MathRound(13, this);
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
                name: "HAIBUN_GK1",
                label: "配分1",
                index: "HAIBUN_GK1",
                width: 100,
                sortable: true,
                align: "left",
                hidden: false,
                //---20150812 #1975 fanzhengzhou upd s.
                //formatter : "number",
                // formatoptions : {
                // thousandsSeparator : ",",
                // decimalPlaces : 0,
                // defaultValue : ""
                // },
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                align: "right",
                //---20150812 #1975 fanzhengzhou upd e.
                editable: true,
                editoptions: {
                    //---20150812 #1976 fanzhengzhou upd s.
                    class: "numeric",
                    //maxlength : 7,
                    maxlength: 8,
                    //---20150812 #1976 fanzhengzhou upd e.
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "HAIBUN_GK1", "HAIBUN_GK2", "KJN_GENKA")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "HAIBUN_GK2",
                                        "KJN_GENKA",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                //me.MathRound(13, this);
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
                name: "HAIBUN_GK2",
                label: "配分2",
                index: "HAIBUN_GK2",
                width: 100,
                sortable: true,
                align: "left",
                hidden: false,
                //---20150812 #1975 fanzhengzhou upd s.
                //formatter : "number",
                // formatoptions : {
                // thousandsSeparator : ",",
                // decimalPlaces : 0,
                // defaultValue : ""
                // },
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                align: "right",
                //---20150812 #1975 fanzhengzhou upd e.
                editable: true,
                editoptions: {
                    //---20150812 #1976 fanzhengzhou upd s.
                    class: "numeric",
                    //maxlength : 7,
                    maxlength: 8,
                    //---20150812 #1976 fanzhengzhou upd e.
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "HAIBUN_GK2", "HAIBUN_GK3", "HAIBUN_GK1")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "HAIBUN_GK3",
                                        "HAIBUN_GK1",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                //me.MathRound(13, this);
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
                name: "HAIBUN_GK3",
                label: "配分3",
                index: "HAIBUN_GK3",
                width: 100,
                sortable: true,
                align: "left",
                hidden: false,
                //---20150812 #1975 fanzhengzhou upd s.
                //formatter : "number",
                // formatoptions : {
                // thousandsSeparator : ",",
                // decimalPlaces : 0,
                // defaultValue : ""
                // },
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                align: "right",
                //---20150812 #1975 fanzhengzhou upd e.
                editable: true,
                editoptions: {
                    //---20150812 #1976 fanzhengzhou upd s.
                    class: "numeric",
                    //maxlength : 7,
                    maxlength: 8,
                    //---20150812 #1976 fanzhengzhou upd e.
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "HAIBUN_GK3", "HAIBUN_GK3", "HAIBUN_GK2")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "KIJYUN_DT",
                                        "HAIBUN_GK2",
                                        false,
                                        true
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                //me.MathRound(13, this);
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
                name: "CREATE_DATE",
                label: "作成日",
                index: "CREATE_DATE",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
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
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            me.loadGridRowCnt = arrIds.length;

            if (arrIds.length >= 1) {
                $(me.grid_id).jqGrid("setSelection", 0);
                $(".FrmSyanaiGenkaMst.cmdInsert").button("enable");
                $(".FrmSyanaiGenkaMst.cmdUpdate").button("enable");
            } else {
                $(".FrmSyanaiGenkaMst.cmdInsert").button("disable");
                $(".FrmSyanaiGenkaMst.cmdUpdate").button("disable");
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
                    if (typeof e != "undefined") {
                        var cellIndex = e.target.cellIndex;
                        //ヘッダークリック以外
                        if (cellIndex != 0) {
                        } else {
                            //ヘッダークリック
                            var rowID = $(me.grid_id).jqGrid(
                                "getGridParam",
                                "selrow"
                            );
                            var rowData = $(me.grid_id).jqGrid(
                                "getRowData",
                                rowID
                            );
                            if (
                                rowData["KIJYUN_DT"].toString().trimEnd() ==
                                    "" &&
                                rowData["NAU_KB"].toString().trimEnd() == "" &&
                                rowData["KJN_GENKA"].toString().trimEnd() == ""
                            ) {
                                return;
                            }
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

                        me.focusSaveRowData();
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
                            focusField: focusIndex,
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
                        //$('input,select', e.target).focus();
                    }
                    $(".numeric").numeric({
                        decimal: false,
                        //---20150812 #1976 fanzhengzhou upd s.
                        //negative : false
                        negative: true,
                        //---20150812 #1976 fanzhengzhou upd s.
                    });
                    me.frontLineNo = rowid;
                },
            });
        };
        //gdmz.common.jqgrid.init(me.grid_id, me.g_url, me.colModel, me.pager, me.sidx, me.option, tmpdata, me.complete_fun);
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 660);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        //---20150819 #2078 fanzhengzhou add s.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150819 #2078 fanzhengzhou add e.
    };

    me.FrmSyanaiGenkaMst_load = function () {
        me.initGrid();
        $(".FrmSyanaiGenkaMst.cmdInsert").button("disable");
        $(".FrmSyanaiGenkaMst.cmdUpdate").button("disable");
    };
    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        me.keyupAddrow();
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
            txtNauKB: $(".FrmSyanaiGenkaMst.txtNauKB")
                .val()
                .toString()
                .trimEnd(),
        };
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
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
        if (
            !(
                rowData["KIJYUN_DT"] == "" &&
                rowData["NAU_KB"] == "" &&
                rowData["KJN_GENKA"] == ""
            )
        ) {
            rowdata = {};
            $(me.grid_id).jqGrid("addRowData", tmpcnt.length, rowdata);
        }
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        $(me.grid_id).jqGrid("setSelection", parseInt(tmpcnt.length) - 1);
        $(me.grid_id).jqGrid("editRow", parseInt(tmpcnt.length) - 1, true);
        $("#" + (parseInt(tmpcnt.length) - 1) + "_KIJYUN_DT").trigger("focus");
    };

    //---20150819 #2078 fanzhengzhou upd s.
    // me.setColSelection = function(key, colNowName, colNextName, colPreviousName) {
    // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
    // //enter
    // if (key == 13) {
    // if (me.lastsel < tmpcnt.length - 1) {
    //
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // $('#' + (parseInt(me.lastsel) ) + '_' + colNowName).focus();
    // }
    // return false;
    // }
    // if (key == 39) {
    // //right
    // $('#' + me.lastsel + '_' + colNextName).focus();
    // return false;
    // }
    // if (key == 37) {
    // //left
    // $('#' + me.lastsel + '_' + colPreviousName).focus();
    // return false;
    // }
    // if (key == 38) {
    // //top
    // if (me.lastsel > 0) {
    //
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) - 1));
    // $('#' + (parseInt(me.lastsel)  ) + '_' + colNowName).focus();
    // }
    // return false;
    // }
    // if (key == 40) {
    // //bottom
    // if (me.lastsel < tmpcnt.length - 1) {
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // $('#' + (parseInt(me.lastsel) ) + '_' + colNowName).focus();
    // }
    // return false;
    // }
    // if (key == 222) {
    // return false;
    // }
    // return true;
    // };
    me.setColSelection = function (
        e,
        key,
        colNextName,
        colPreviousName,
        firstCol,
        lastCol
    ) {
        var GridRecords = $(me.grid_id).jqGrid("getGridParam", "records");
        if ((e.shiftKey && key == 37) || (e.shiftKey && key == 39)) {
            return true;
        } else {
            //Shift+Tab
            if (e.shiftKey && key == 9) {
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

            //Tab&&Enter
            if (key == 9 || key == 13) {
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

            if (key == 222) {
                return false;
            }
            return true;
        }
    };
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
        var data = $(me.grid_id).jqGrid("getRowData");
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
            }
        };
        $(me.grid_id).jqGrid("saveRow", rowID, null, "clientArray");
        var tt = $(me.grid_id).jqGrid("getRowData", rowID);
        var data = {
            KIJYUN_DT: tt["KIJYUN_DT"],
            NAU_KB: tt["NAU_KB"],
            KJN_GENKA: tt["KJN_GENKA"],
        };
        me.ajax.send(me.updateUrl, data, 0);
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
                //---20150811 li UPD S.
                //me.clsComFnc.FncMsgBox("E9999", result['data']);
                me.clsComFnc.FncMsgBox("E9999", "キー項目が重複しています。");
                //---20150811 li UPD E.
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
    me.focusSaveRowData = function () {
        var tt = $(me.grid_id).jqGrid("getGridParam", "selrow");
        //me.focusSaveRowDataArr = $(me.grid_id).jqGrid('getRowData', tt);
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
            KIJYUN_DT: "年月日",
            NAU_KB: "新中区分",
            KJN_GENKA: "社内原価",
            HAIBUN_GK1: "配分１",
            HAIBUN_GK2: "配分２",
            HAIBUN_GK3: "配分３",
        };
        var blnInputFlg_1 = false;
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length; i++) {
            if (
                grid_data1[i]["KIJYUN_DT"] != "" ||
                grid_data1[i]["NAU_KB"] != "" ||
                grid_data1[i]["KJN_GENKA"] != "" ||
                grid_data1[i]["HAIBUN_GK1"] != "" ||
                grid_data1[i]["HAIBUN_GK2"] != "" ||
                grid_data1[i]["HAIBUN_GK3"] != ""
            ) {
                for (key in grid_data1[i]) {
                    switch (key) {
                        case "KIJYUN_DT":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                me.colModel[0]["editoptions"]["maxlength"]
                            );
                            break;
                        case "NAU_KB":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                me.colModel[1]["editoptions"]["maxlength"]
                            );
                            break;
                        case "KJN_GENKA":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[2]["editoptions"]["maxlength"]
                            );
                            break;
                        case "HAIBUN_GK1":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[3]["editoptions"]["maxlength"]
                            );
                            break;
                        case "HAIBUN_GK2":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[4]["editoptions"]["maxlength"]
                            );
                            break;
                        case "HAIBUN_GK3":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data1[i][key],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[5]["editoptions"]["maxlength"]
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
                me.intRtn = 0;
                //キー項目の必須ﾁｪｯｸ'
                if (grid_data1[i]["KIJYUN_DT"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $(me.grid_id).jqGrid("editRow", i, true);
                    $("#" + i + "_KIJYUN_DT").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "年月日");
                    return false;
                }
                //キー項目の必須ﾁｪｯｸ'
                if (grid_data1[i]["NAU_KB"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_NAU_KB").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "新中区分");
                    return false;
                }
                //キー項目の必須ﾁｪｯｸ'
                if (grid_data1[i]["KJN_GENKA"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_KJN_GENKA").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0001", "社内原価");
                    return false;
                }
                //年月日のﾁｪｯｸ
                if (
                    !(
                        grid_data1[i]["KIJYUN_DT"].toString().substr(4, 2) >=
                            "01" &&
                        grid_data1[i]["KIJYUN_DT"].toString().substr(4, 2) <=
                            "12"
                    )
                ) {
                    //---20150811 li DEL S.
                    //alert(grid_data1[i]['KIJYUN_DT'].toString().substr(4, 2));
                    //---20150811 li DEL E.
                    me.intRtn = -1;
                }
                if (
                    !(
                        grid_data1[i]["KIJYUN_DT"].toString().substr(6, 2) >=
                            "01" &&
                        grid_data1[i]["KIJYUN_DT"].toString().substr(6, 2) <=
                            "31"
                    )
                ) {
                    //---20150811 li DEL S.
                    //alert(grid_data1[i]['KIJYUN_DT'].toString().substr(6, 2));
                    //---20150811 li DEL E.
                    me.intRtn = -1;
                }
                if (!(grid_data1[i]["KIJYUN_DT"].toString().length == 8)) {
                    me.intRtn = -1;
                }
                if (me.intRtn < 0) {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_KIJYUN_DT").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0002", "年月日");
                    return false;
                }
                //新中区分のチェック
                if (
                    !(
                        grid_data1[i]["NAU_KB"] == 1 ||
                        grid_data1[i]["NAU_KB"] == 2
                    )
                ) {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_NAU_KB").trigger("focus");
                    me.clsComFnc.FncMsgBox("W0002", "新中区分");
                    return false;
                }

                //金額のチェック
                var HAIBUN_GK1 =
                    grid_data1[i]["HAIBUN_GK1"] == ""
                        ? 0
                        : grid_data1[i]["HAIBUN_GK1"];
                var HAIBUN_GK2 =
                    grid_data1[i]["HAIBUN_GK2"] == ""
                        ? 0
                        : grid_data1[i]["HAIBUN_GK2"];
                var HAIBUN_GK3 =
                    grid_data1[i]["HAIBUN_GK3"] == ""
                        ? 0
                        : grid_data1[i]["HAIBUN_GK3"];

                if (
                    !(
                        parseInt(grid_data1[i]["KJN_GENKA"]) ==
                        parseInt(HAIBUN_GK1) +
                            parseInt(HAIBUN_GK2) +
                            parseInt(HAIBUN_GK3)
                    )
                ) {
                    $(me.grid_id).jqGrid("setSelection", i);
                    $("#" + i + "_KJN_GENKA").trigger("focus");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "社内原価と配分の合計が一致しません！"
                    );
                    return false;
                }
                blnInputFlg_1 = true;
            }
        }
        if (blnInputFlg_1 == false) {
            $(me.grid_id).jqGrid("setSelection", 0);
            $("#" + 0 + "_OYA_CD").trigger("focus");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }
        return true;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyanaiGenkaMst = new R4.FrmSyanaiGenkaMst();
    o_R4_FrmSyanaiGenkaMst.load();
    o_R4K_R4K.FrmSyanaiGenkaMst = o_R4_FrmSyanaiGenkaMst;
});
