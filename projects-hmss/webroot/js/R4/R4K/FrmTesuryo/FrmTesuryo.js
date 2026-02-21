/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150804           #2016 2019 2020 2021 2022    BUG                              li
 * 20150831           #2013                        BUG                              li
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmTesuryo");

R4.FrmTesuryo = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmTesuryo";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmTesuryo_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFrmTesuryoSelect";
    me.pager = "";
    //'#FrmTesuryo_pager';
    me.sidx = "";
    me.actionFlg = "";
    me.lastsel = 0;
    me.cursel = 0;
    me.tmpSaveRowData = new Array();
    me.frontLineNo = -1;
    me.frontLineNo1 = -1;
    me.saveSelectedRowData = new Array();
    me.grid_data1 = "";
    me.firstData = new Array();
    me.focusRow = 5000000;

    me.emptyRowData = {
        CAL_NISU: "",
        CREATE_DATE: "",
        KIJYUN_DT: "",
        NEN_RT: "",
        SYANAI_RT: "",
        TESURYO: "",
        TORITATERYO: "",
        UPD_CLT_NM: "",
        UPD_DATE: "",
        UPD_PRG_ID: "",
        UPD_SYA_CD: "",
    };
    me.addRowFlag = false;
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmTesuryo.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmTesuryo.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmTesuryo.cmdCancel",
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
    $(".FrmTesuryo.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmTesuryo.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });
    $(".FrmTesuryo.cmdCancel").click(function () {
        me.fnc_click_cmdCancel();
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
        me.FrmTesuryo_load();
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
                label: "基準日",
                index: "KIJYUN_DT",
                width: 100,
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

                                //---20150804 li UPD S.
                                //if (!me.setColSelection(key, "KIJYUN_DT", "NEN_RT", "KIJYUN_DT")) {
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "KIJYUN_DT",
                                        "NEN_RT",
                                        "TORITATERYO",
                                        true,
                                        false
                                    )
                                ) {
                                    //---20150804 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                    ],
                },
            },
            {
                name: "NEN_RT",
                label: "年率",
                index: "NEN_RT",
                width: 100,
                sortable: true,
                align: "right",
                editable: true,
                formatter: "number",
                formatoptions: {
                    decimalPlaces: 2,
                    defaultValue: "",
                },
                editoptions: {
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150804 li UPD S.
                                //if (!me.setColSelection(key, "NEN_RT", "SYANAI_RT", "KIJYUN_DT")) {
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "NEN_RT",
                                        "SYANAI_RT",
                                        "KIJYUN_DT",
                                        false,
                                        false
                                    )
                                ) {
                                    //---20150804 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                    ],
                },
            },
            {
                name: "SYANAI_RT",
                label: "社内率",
                index: "SYANAI_RT",
                width: 100,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                formatter: "number",
                formatoptions: {
                    defaultValue: "",
                },
                editoptions: {
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150804 li UPD S.
                                //if (!me.setColSelection(key, "SYANAI_RT", "TESURYO", "NEN_RT")) {
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "SYANAI_RT",
                                        "TESURYO",
                                        "NEN_RT",
                                        false,
                                        false
                                    )
                                ) {
                                    //---20150804 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                    ],
                },
            },
            {
                name: "TESURYO",
                label: "手数料",
                index: "TESURYO",
                width: 100,
                sortable: true,
                align: "right",
                hidden: false,
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                editable: true,
                editoptions: {
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150804 li DEL S.
                                if (key == 38 || key == 40 || key == 9) {
                                    $(this).val(Math.round($(this).val()));
                                    // if ($(this).val() == 0) {
                                    // $(this).val("");
                                }
                                // if ($(this).val() === "NaN") {
                                // $(this).val("");
                                // }
                                // }
                                //---20150804 li DEL E.
                                //---20150804 li UPD S.
                                //if (!me.setColSelection(key, "TESURYO", "TORITATERYO", "SYANAI_RT")) {
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "TESURYO",
                                        "TORITATERYO",
                                        "SYANAI_RT",
                                        false,
                                        false
                                    )
                                ) {
                                    //---20150804 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                    ],
                },
            },
            {
                name: "TORITATERYO",
                label: "取立料",
                index: "TORITATERYO",
                width: 100,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                editoptions: {
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150804 li DEL S.
                                if (key == 38 || key == 40 || key == 9) {
                                    $(this).val(Math.round($(this).val()));
                                    // if ($(this).val() == 0) {
                                    // $(this).val("");
                                }
                                // if ($(this).val() === "NaN") {
                                // $(this).val("");
                                // }
                                // }
                                // if (key == 13 || (key == 9 && !e.shiftKey)) {
                                // return true;
                                // }
                                //---20150804 li DEL E.
                                //---20150804 li ADD S.
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "TORITATERYO",
                                        "KIJYUN_DT",
                                        "TESURYO",
                                        false,
                                        true
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150804 li ADD E.
                            },
                        },
                    ],
                },
            },
            {
                name: "CAL_NISU",
                label: "ｾｰﾙｽﾏﾝ別対象ﾌﾗｸﾞ",
                index: "CAL_NISU",
                width: 230,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "UPD_DATE",
                label: "ｾｰﾙｽﾏﾝ別対象ﾌﾗｸﾞ",
                index: "UPD_DATE",
                width: 230,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "CREATE_DATE",
                label: "ｾｰﾙｽﾏﾝ別対象ﾌﾗｸﾞ",
                index: "CREATE_DATE",
                width: 230,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "UPD_SYA_CD",
                label: "ｾｰﾙｽﾏﾝ別対象ﾌﾗｸﾞ",
                index: "UPD_SYA_CD",
                width: 230,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "UPD_PRG_ID",
                label: "ｾｰﾙｽﾏﾝ別対象ﾌﾗｸﾞ",
                index: "UPD_PRG_ID",
                width: 230,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "UPD_CLT_NM",
                label: "ｾｰﾙｽﾏﾝ別対象ﾌﾗｸﾞ",
                index: "UPD_CLT_NM",
                width: 230,
                sortable: false,
                align: "left",
                hidden: true,
            },
        ];
        me.complete_fun = function () {
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
            // rowdata = {
            // "GYOSYA_CD" : "",
            // "GYOSYA_NM" : "",
            // "GYOSYA_RNM" : "",
            // "JISSEKI_KB" : "",
            // "SYUKEI_BUSYO_CD" : ""
            // };
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            //$(me.grid_id).jqGrid('addRowData', arrIds.length, rowdata);
            $(me.grid_id).jqGrid("addRowData", arrIds.length, me.emptyRowData);
            me.firstData = $(me.grid_id).jqGrid("getRowData");
            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    var focusIndex =
                        typeof e != "undefined"
                            ? e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex
                            : false;
                    //---20150817 fanzhengzhou add s. If not do this,sometimes the var of me.saveSelectedRowData[rowid] will appear  string like "<input .....>".
                    $(me.grid_id).jqGrid("saveRow", rowid, null, "clientArray");
                    //---20150817 fanzhengzhou add e.
                    var rowData2 = $(me.grid_id).jqGrid("getRowData", rowid);
                    me.saveSelectedRowData[rowid] = rowData2;
                    if (me.frontLineNo == -1) {
                        me.frontLineNo = rowid;
                    }
                    if (me.frontLineNo != rowid) {
                        me.frontLineNo1 = me.frontLineNo;
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.frontLineNo,
                            null,
                            "clientArray"
                        );
                        var rowData3 = $(me.grid_id).jqGrid(
                            "getRowData",
                            me.frontLineNo
                        );
                        //---20150817 #2013 fanzhengzhou add s.
                        if (
                            me.saveSelectedRowData[me.frontLineNo].CAL_NISU ==
                                "" &&
                            me.saveSelectedRowData[me.frontLineNo]
                                .CREATE_DATE == "" &&
                            me.saveSelectedRowData[me.frontLineNo].KIJYUN_DT ==
                                "" &&
                            me.saveSelectedRowData[me.frontLineNo].NEN_RT ==
                                "" &&
                            me.saveSelectedRowData[me.frontLineNo].SYANAI_RT ==
                                "" &&
                            me.saveSelectedRowData[me.frontLineNo].TESURYO ==
                                "" &&
                            me.saveSelectedRowData[me.frontLineNo]
                                .TORITATERYO == "" &&
                            me.saveSelectedRowData[me.frontLineNo].UPD_CLT_NM ==
                                "" &&
                            me.saveSelectedRowData[me.frontLineNo].UPD_DATE ==
                                "" &&
                            me.saveSelectedRowData[me.frontLineNo].UPD_PRG_ID ==
                                "" &&
                            me.saveSelectedRowData[me.frontLineNo].UPD_SYA_CD ==
                                ""
                        ) {
                            me.saveSelectedRowData[me.frontLineNo] = rowData3;
                        }
                        //---20150817 #2013 fanzhengzhou add e.
                        if (rowData3["KIJYUN_DT"] == "") {
                            var arrIds1 = $(me.grid_id).jqGrid("getDataIDs");
                            //---20150831 li UPD S.
                            //if (me.frontLineNo != arrIds1.length - 1) {
                            if (me.frontLineNo != arrIds1.length) {
                                //---20150831 li UPD E.
                                me.grid_data1 = $(me.grid_id).jqGrid(
                                    "getRowData"
                                );
                                me.showCorrectMessg();
                                me.frontLineNo = rowid;
                                return;
                            }
                        }
                        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
                        for (var i = 0; i < grid_data1.length - 1; i++) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                i,
                                null,
                                "clientArray"
                            );
                            for (var j = i + 1; j < grid_data1.length; j++) {
                                $(me.grid_id).jqGrid(
                                    "saveRow",
                                    j,
                                    null,
                                    "clientArray"
                                );
                                if (grid_data1[i]["KIJYUN_DT"] != "") {
                                    if (
                                        grid_data1[i]["KIJYUN_DT"] ==
                                        grid_data1[j]["KIJYUN_DT"]
                                    ) {
                                        me.focusRow = j;
                                        if (me.firstData.length - 1 >= i) {
                                            if (
                                                me.firstData[i]["KIJYUN_DT"] !==
                                                grid_data1[i]["KIJYUN_DT"]
                                            ) {
                                                me.focusRow = i;
                                            }
                                        }
                                        if (parseInt(rowid) !== me.focusRow) {
                                            //---20150806 li INS S.
                                            me.frontLineNo = rowid;
                                            //---20150806 li INS E.
                                            me.clsComFnc.MsgBoxBtnFnc.Yes =
                                                me.yesCorrectRowData;
                                            me.clsComFnc.MsgBoxBtnFnc.No =
                                                me.noCorrectRowData;
                                            me.clsComFnc.MessageBox(
                                                "列'KIJYUN_DT'は一意であるように制約されています。値'" +
                                                    grid_data1[i]["KIJYUN_DT"] +
                                                    "' は既に存在します。値を修正しますか?",
                                                me.clsComFnc.GSYSTEM_NAME,
                                                "YesNo",
                                                "Question",
                                                me.clsComFnc
                                                    .MessageBoxDefaultButton
                                                    .Button2
                                            );
                                            return;
                                        }
                                    }
                                }
                            }
                        }
                        grid_data1 = null;
                    }
                    if (typeof e != "undefined") {
                        var cellIndex = e.target.cellIndex;
                        me.cursel = cellIndex;
                        //ヘッダークリック以外
                        if (cellIndex != 0) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            $(me.grid_id).jqGrid("editRow", rowid, {
                                keys: true,
                                focusField: focusIndex,
                            });
                            me.lastsel = rowid;
                        } else {
                            //ヘッダークリック
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            //---20150818 fanzhengzhou upd s.
                            me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                            me.clsComFnc.MessageBox(
                                "削除します。よろしいですか？",
                                me.clsComFnc.GSYSTEM_NAME,
                                "YesNo",
                                "Question",
                                me.clsComFnc.MessageBoxDefaultButton.Button2
                            );
                            //---20150818 fanzhengzhou upd e.
                            var rowID = $(me.grid_id).jqGrid(
                                "getGridParam",
                                "selrow"
                            );
                            me.jqgridCurrentRowID = rowID;
                        }
                    } else {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        $(me.grid_id).jqGrid("editRow", rowid, {
                            keys: true,
                            focusField: false,
                        });
                        me.lastsel = rowid;
                    }

                    $(".numeric").numeric({
                        decimal: false,
                        negative: false,
                    });
                    me.frontLineNo = rowid;
                },
            });
            //set row count
            //$("#FrmTesuryo_pager_center")
            me.set_pager_row_count();
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
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 600);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        //---20150818 fanzhengzhou add s.Bind enter key ,but do nothing.It seems like strange.:)
        //---But if not,when you press up or down,the selected row will jump to the center of Grid.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150818 fanzhengzhou add e.
    };

    me.FrmTesuryo_load = function () {
        me.initGrid();
        //---20150818 fanzhengzhou del s.
        //me.fnckeyDown46();
        //---20150818 fanzhengzhou del e.
    };
    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");

        // var rowdata = {
        // "GYOSYA_CD" : "",
        // "GYOSYA_NM" : "",
        // "GYOSYA_RNM" : "",
        // "JISSEKI_KB" : "",
        // "SYUKEI_BUSYO_CD" : ""
        // };
        //業者ﾏｽﾀに該当ﾃﾞｰﾀが存在している場合
        if (arrIds.length > 0) {
            var kijyun_dt_isNull = 0;
            for (j = 0; j < arrIds.length; j++) {
                $(me.grid_id).jqGrid("saveRow", j, null, "clientArray");
                var tmp_i_rowData = $(me.grid_id).jqGrid("getRowData", j);
                if (tmp_i_rowData["KIJYUN_DT"] == "") {
                    kijyun_dt_isNull++;
                    if (
                        tmp_i_rowData["KIJYUN_DT"] == "" &&
                        tmp_i_rowData["NEN_RT"] == "" &&
                        tmp_i_rowData["SYANAI_RT"] == "" &&
                        tmp_i_rowData["TORITATERYO"] == "" &&
                        tmp_i_rowData["TESURYO"] == ""
                    ) {
                        $(me.grid_id).jqGrid("setSelection", j);
                        var selNextId = "#" + j + "_KIJYUN_DT";
                        $(selNextId).trigger("focus");
                        return;
                    } else {
                        //---20150817 fanzhengzhou upd s.
                        // $(me.grid_id).jqGrid('setSelection', j);
                        // var selNextId = '#' + j + '_KIJYUN_DT';
                        // $(selNextId).focus();
                        // me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesCorrectRowData;
                        // me.clsComFnc.MsgBoxBtnFnc.No = me.noCorrectRowData;
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesButtonClick;
                        me.clsComFnc.MsgBoxBtnFnc.No = me.noButtonClick;
                        //---20150817 fanzhengzhou upd e.
                        me.clsComFnc.MessageBox(
                            "列'KIJYUN_DT'にNullを使用することはできません。値を修正しますか?",
                            me.clsComFnc.GSYSTEM_NAME,
                            "YesNo",
                            "Question",
                            me.clsComFnc.MessageBoxDefaultButton.Button2
                        );
                        return;
                    }
                }
            }
            me.grid_data1 = $(me.grid_id).jqGrid("getRowData");
            if (me.inputCheck()) {
                if (kijyun_dt_isNull == 0) {
                    var i = arrIds.length;
                    //$(me.grid_id).jqGrid('addRowData', i, rowdata);
                    $(me.grid_id).jqGrid("addRowData", i, me.emptyRowData);
                    $(me.grid_id).jqGrid("setSelection", i);
                    $(me.grid_id).jqGrid("editRow", i, true);
                    var selNextId = "#" + i + "_KIJYUN_DT";
                    $(selNextId).trigger("focus");
                    me.set_pager_row_count();
                }
            }
        } else {
            //$(me.grid_id).jqGrid('addRowData', 0, rowdata);
            $(me.grid_id).jqGrid("addRowData", 0, me.emptyRowData);
            me.set_pager_row_count();
        }
        $(".FrmGyousyaMst.cmdUpdate").button("enable");
    };
    me.fnc_click_cmdUpdate = function () {
        //---20150817 fanzhengzhou add s.
        if (!me.fncCheckKijyunDtIsNull()) {
            return;
        }
        //---20150817 fanzhengzhou add e.
        //入力チェック
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        var kijyun_dt_isNull = 0;
        for (j = 0; j < arrIds.length - 1; j++) {
            //---20150806 li DEL S.
            //var tmp_i_rowData = $(me.grid_id).jqGrid('getRowData', j);
            //$(me.grid_id).jqGrid('editRow', j, true);
            // var id_t = '#' + j + '_TESURYO';
            // $(id_t).val(Math.round($(id_t).val()));
            // if ($(id_t).val() == 0.00)
            // {
            // $(id_t).val("1");
            // }
            // var id_t = '#' + j + '_TORITATERYO';
            // $(id_t).val(Math.round($(id_t).val()));
            // if ($(id_t).val() == 0)
            // {
            // $(id_t).val("1");
            // }
            //---
            //$(me.grid_id).jqGrid('saveRow', j);
            //---20150806 li DEL E.
            //---20150806 li UPD S.
            //if (tmp_i_rowData['KIJYUN_DT'] == "")
            var id_t = "#" + j + "_KIJYUN_DT";
            $(id_t).val(Math.round($(id_t).val()));
            if ($(id_t).val() == "") {
                //---20150806 li UPD E.
                kijyun_dt_isNull++;
                $(me.grid_id).jqGrid("setSelection", j);
                var selNextId = "#" + j + "_KIJYUN_DT";
                $(selNextId).trigger("focus");
                return;
            }
        }
        me.grid_data1 = $(me.grid_id).jqGrid("getRowData");
        if (me.inputCheck()) {
            //確認メッセージ
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesUpdateFnc;
            me.clsComFnc.MsgBoxBtnFnc.No = me.NoUpdateFnc;
            me.clsComFnc.FncMsgBox("QY010");
        }
    };
    me.fnc_click_cmdCancel = function () {
        //---20150817 fanzhengzhou add s.
        if (!me.fncCheckKijyunDtIsNull()) {
            return;
        }
        //---20150817 fanzhengzhou add e.
        var tmpdata = {};
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
    };
    //--functions--
    me.keyupAddrow = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");

        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (tmpcnt.length - 1 == rowID) {
            rowdata = {};
            $(me.grid_id).jqGrid("addRowData", tmpcnt.length, rowdata);
            me.set_pager_row_count();
        }
    };
    //---20150804 li UPD S.
    // me.setColSelection = function(key, colNowName, colNextName, colPreviousName) {
    // //enter
    // if (key == 13) {
    // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
    // if (me.lastsel < tmpcnt.length - 1) {
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('editRow', (parseInt(me.lastsel) + 1), true);
    // $('#' + (parseInt(me.lastsel) + 1) + '_' + colNowName).focus();
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // return false;
    // }
    // }
    //
    // if (key == 39)
    // {
    // //right
    // $('#' + me.lastsel + '_' + colNextName).focus();
    // return false;
    // }
    //
    // if (key == 37)
    // {
    // //left
    // $('#' + me.lastsel + '_' + colPreviousName).focus();
    // return false;
    // }
    //
    // if (key == 38) {
    // //top
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('editRow', (parseInt(me.lastsel) - 1 ), true);
    // $('#' + (parseInt(me.lastsel) - 1 ) + '_' + colNowName).focus();
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) - 1));
    // return false;
    // }
    // if (key == 40) {
    // //bottom
    // $(me.grid_id).jqGrid('saveRow', me.lastsel);
    // $(me.grid_id).jqGrid('editRow', (parseInt(me.lastsel) + 1), true);
    // $('#' + (parseInt(me.lastsel) + 1) + '_' + colNowName).focus();
    // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1));
    // return false;
    // }
    // if (key >= 65 && key <= 90 || key >= 48 && key <= 57 || key >= 96 && key <= 105 || key >= 186 && key <= 222 || key >= 109 && key <= 111 || key == 106 || key == 107) {
    // me.keyupAddrow();
    // me.addRowFlag = true;
    // }
    // $(this).select();
    // return true;
    // };
    me.setColSelection = function (
        e,
        key,
        colNowName,
        colNextName,
        colPreviousName,
        firstCol,
        lastCol
    ) {
        if (key == 13) {
            return false;
        }
        var GridRecords = $(me.grid_id).jqGrid("getGridParam", "records");
        if ((e.shiftKey && key == 37) || (e.shiftKey && key == 39)) {
            if (
                (colNowName == "TESURYO" || colNowName == "TORITATERYO") &&
                $("#" + me.lastsel + "_" + colNowName).val() == 0
            ) {
                $("#" + me.lastsel + "_" + colNowName).val("");
            }
            return true;
        } else {
            //Shift+Tab&&Left
            if ((e.shiftKey && key == 9) || key == 37) {
                if (
                    (colNowName == "TESURYO" || colNowName == "TORITATERYO") &&
                    $("#" + me.lastsel + "_" + colNowName).val() == 0
                ) {
                    $("#" + me.lastsel + "_" + colNowName).val("");
                }
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
                if (
                    (colNowName == "TESURYO" || colNowName == "TORITATERYO") &&
                    $("#" + me.lastsel + "_" + colNowName).val() == 0
                ) {
                    $("#" + me.lastsel + "_" + colNowName).val("");
                }
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
                me.addRowFlag = true;
            }

            if (key == 222) {
                return false;
            }
            return true;
        }
    };
    //---20150804 li UPD E.
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
            //tmpCi++;
        }
        if (me.firstData.length - 1 >= parseInt(rowID)) {
            me.firstData.splice(parseInt(rowID), 1);
        }
        me.set_pager_row_count();
    };
    me.cancelsel = function () {};
    me.yesCorrectRowData = function () {
        if (me.saveSelectedRowData.hasOwnProperty(me.frontLineNo)) {
            //---20150817 fanzhengzhou upd s.
            var records = $(me.grid_id).jqGrid("getGridParam", "records");
            if (me.addRowFlag && me.frontLineNo1 == records - 2) {
                me.saveSelectedRowData[me.frontLineNo1] = $(me.grid_id).jqGrid(
                    "getRowData",
                    me.frontLineNo1
                );
                $(me.grid_id).jqGrid(
                    "setRowData",
                    me.frontLineNo1,
                    me.saveSelectedRowData[me.frontLineNo1]
                );
            } else {
                $(me.grid_id).jqGrid(
                    "setRowData",
                    me.frontLineNo1,
                    me.saveSelectedRowData[me.frontLineNo1]
                );
            }
            //---20150817 fanzhengzhou upd e.
        }
        //---20150806 li UPD S.
        // $(me.grid_id).jqGrid('setSelection', me.frontLineNo);
        // $('#' + me.frontLineNo1 + '_KIJYUN_DT').focus();

        //$(me.grid_id).jqGrid('saveRow', me.frontLineNo1);
        var row = 0;
        if (me.focusRow !== 5000000) {
            row = me.focusRow;
        } else {
            row = me.frontLineNo1;
        }
        $(me.grid_id).jqGrid("setSelection", parseInt(row), true);
        $("#" + row + "_KIJYUN_DT").trigger("focus");
        //---20150806 li UPD E.
    };
    me.noCorrectRowData = function () {
        if (me.saveSelectedRowData.hasOwnProperty(me.frontLineNo)) {
            $(me.grid_id).jqGrid(
                "setRowData",
                me.frontLineNo1,
                me.saveSelectedRowData[me.frontLineNo1]
            );
        }
        //---20150817 fanzhengzhou add s.
        var records = $(me.grid_id).jqGrid("getGridParam", "records");
        //---20150831 li UPD S.
        //if ((me.frontLineNo1 == records - 2) && (me.saveSelectedRowData[me.frontLineNo1].KIJYUN_DT == "")) {
        if (
            me.frontLineNo1 == records - 1 &&
            me.saveSelectedRowData[me.frontLineNo1].KIJYUN_DT == ""
        ) {
            //---20150831 li UPD E.
            $(me.grid_id).jqGrid(
                "setRowData",
                me.frontLineNo1,
                me.emptyRowData
            );
            var delRowId = parseInt(me.frontLineNo1) + 1;
            $(me.grid_id).jqGrid("delRowData", delRowId);
            me.addRowFlag = false;
        }
        //---20150817 fanzhengzhou add e.
        //---20150806 li UPD S.
        // var rowID = $(me.grid_id).jqGrid('getGridParam', 'selrow');
        $(me.grid_id).jqGrid("setSelection", me.frontLineNo);
        $("#" + me.frontLineNo + "_KIJYUN_DT").trigger("focus");
        //---20150806 li UPD E.
    };

    me.YesUpdateFnc = function () {
        me.updateUrl = me.sys_id + "/" + me.id + "/" + "fncUpdate";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
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

    //---20150818 fanzhengzhou del s.
    // me.fnckeyDown46 = function() {
    // me.inp = $(me.grid_id);
    // me.inp.bind('keydown', function(e) {
    // var key = e.which;
    // var oEvent = window.event;
    // if (key == 46) {
    // me.delRowData();
    // };
    // });
    // };
    //---20150818 fanzhengzhou del e.

    me.inputCheck = function () {
        //var grid_data1 = $(me.grid_id).jqGrid('getRowData');
        for (var i = 0; i < me.grid_data1.length; i++) {
            $(me.grid_id).jqGrid("saveRow", i, null, "clientArray");
        }
        for (var i = 0; i < me.grid_data1.length - 1; i++) {
            for (var j = i + 1; j < me.grid_data1.length; j++) {
                if (me.grid_data1[i]["KIJYUN_DT"] != "") {
                    if (
                        me.grid_data1[i]["KIJYUN_DT"] ==
                        me.grid_data1[j]["KIJYUN_DT"]
                    ) {
                        /*$(me.grid_id).jqGrid('setSelection', j, true);
                         var selId = '#' + (j) + '_' + "KIJYUN_DT";
                         $(selId).focus();
                         $(selId).select();
                         */
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesCorrectRowData;
                        me.clsComFnc.MsgBoxBtnFnc.No = me.noCorrectRowData;
                        me.clsComFnc.MessageBox(
                            "列'KIJYUN_DT'は一意であるように制約されています。値'" +
                                me.grid_data1[i]["KIJYUN_DT"] +
                                "'は既に存在します。値を修正しますか?",
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
    me.showCorrectMessg = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesCorrectRowData;
        me.clsComFnc.MsgBoxBtnFnc.No = me.noCorrectRowData;
        me.clsComFnc.MessageBox(
            "列'KIJYUN_DT'にNullを使用することはできません。値を修正しますか?",
            me.clsComFnc.GSYSTEM_NAME,
            "YesNo",
            "Question",
            me.clsComFnc.MessageBoxDefaultButton.Button2
        );
        return;
    };
    me.set_pager_row_count = function () {
        /*
         var ttt = document.getElementById("FrmTesuryo_pager_center");
         var tmp_ttt = ttt.childNodes[0].innerHTML.toString().replace("検索結果 ", "");
         tmp_ttt = tmp_ttt.replace("件を表示しました", "");
         ttt.childNodes[0].innerHTML = "検索結果 " + (parseInt(tmp_ttt) - 1).toString() +  "件を表示しました";
         */
    };

    //---20150817 fanzhengzhou add s.
    me.fncCheckKijyunDtIsNull = function () {
        $(me.grid_id).jqGrid("saveRow", me.frontLineNo, null, "clientArray");
        var rowData = $(me.grid_id).jqGrid("getRowData", me.frontLineNo);
        if (
            rowData.KIJYUN_DT == "" &&
            !(
                rowData.NEN_RT == "" &&
                rowData.SYANAI_RT == "" &&
                rowData.TORITATERYO == "" &&
                rowData.TESURYO == ""
            )
        ) {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesButtonClick;
            me.clsComFnc.MsgBoxBtnFnc.No = me.noButtonClick;
            me.clsComFnc.MessageBox(
                "列'KIJYUN_DT'にNullを使用することはできません。値を修正しますか?",
                me.clsComFnc.GSYSTEM_NAME,
                "YesNo",
                "Question",
                me.clsComFnc.MessageBoxDefaultButton.Button2
            );
            return false;
        }
        return true;
    };

    //when click 新規追加,更新,キャンセル and Question dialog shows.
    me.yesButtonClick = function () {
        if (me.saveSelectedRowData[me.frontLineNo].KIJYUN_DT != "") {
            $(me.grid_id).jqGrid(
                "setRowData",
                me.frontLineNo,
                me.saveSelectedRowData[me.frontLineNo]
            );
        }
        $(me.grid_id).jqGrid("setSelection", me.frontLineNo);
        var selNextId = "#" + me.frontLineNo + "_KIJYUN_DT";
        $(selNextId).trigger("focus");
    };

    me.noButtonClick = function () {
        if (me.saveSelectedRowData[me.frontLineNo].KIJYUN_DT != "") {
            $(me.grid_id).jqGrid(
                "setRowData",
                me.frontLineNo,
                me.saveSelectedRowData[me.frontLineNo]
            );
        } else {
            $(me.grid_id).jqGrid("setRowData", me.frontLineNo, me.emptyRowData);
            $(me.grid_id).jqGrid("resetSelection");
            var delRowId = parseInt(me.frontLineNo) + 1;
            $(me.grid_id).jqGrid("delRowData", delRowId);
            me.addRowFlag = false;
            me.frontLineNo = -1;
        }
    };
    //---20150817 fanzhengzhou add e.
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmTesuryo = new R4.FrmTesuryo();
    o_R4_FrmTesuryo.load();
    o_R4K_R4K.FrmTesuryo = o_R4_FrmTesuryo;
});
