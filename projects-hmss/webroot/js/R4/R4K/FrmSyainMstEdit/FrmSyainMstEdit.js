/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150810           #2009 2006 2003 2078         BUG                              li
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSyainMstEdit");

R4.FrmSyainMstEdit = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmSyainMstEdit";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmSyainMstEdit_sprMeisai";
    me.subDialogId = "#FrmSyainMstEdit_subFormDialog";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFromSyainSelect";
    //me.pager = '#FrmSyainMstEdit_pager';
    me.sidx = "";
    me.jqgridData_inputCheck = "";
    me.iColNo_inputCheck = "";
    me.iRowNo_inputCheck = 0;
    me.iRowCnt_inputCheck = 0;
    me.rowID_inputCheck = -1;
    me.keydown_position = "";
    me.tmpNowDate = "";
    me.jqgridCurrentRowID = 0;
    me.validatingArr = {
        current: "",
        before: "",
    };
    me.lastsel = 0;
    me.firstData = new Array();
    me.fontSizeTitle = me.ratio === 1.5 ? "10px" : "12px";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmSyainMstEdit.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyainMstEdit.cmdBack",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyainMstEdit.cboTaisyokuYMD",
        type: "datepicker",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //---click event---

    $(me.grid_id).click(function () {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (id >= 0) {
            $(me.grid_id).jqGrid("editRow", id);
        }
    });
    $(".FrmSyainMstEdit.cmdAction").click(function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        me.fnc_click_cmdAction();
    });
    $(".FrmSyainMstEdit.chkTaisyokuYMD").click(function () {
        if ($(".FrmSyainMstEdit.chkTaisyokuYMD").prop("checked")) {
            $(".FrmSyainMstEdit.cboTaisyokuYMD").prop("disabled", false);
            $(".FrmSyainMstEdit.cboTaisyokuYMD").datepicker("option", {
                disabled: false,
            });
        } else {
            $(".FrmSyainMstEdit.cboTaisyokuYMD").prop("disabled", "disabled");
            $(".FrmSyainMstEdit.cboTaisyokuYMD").datepicker("option", {
                disabled: true,
            });
        }
    });
    $(".FrmSyainMstEdit.cmdBack").click(function () {
        var data = $(me.grid_id).jqGrid("getRowData");
        if (data.length > 0) {
            me.o_R4_FrmSyainMstList.fnc_closeSubDialog();
        } else {
            $("#FrmSyainMstList_sub_dialog").dialog("close");
        }
    });

    //---blur event---
    $(".FrmSyainMstEdit.txtSyainNO ").on("blur", function () {
        me.fnc_blur_txtSyainNO();
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
        me.FrmSyainMstEdit_load();
    };
    me.FrmSyainMstEdit_load = function () {
        me.formInit();
    };

    //----functions----
    me.isDate = function (date) {
        if (date.replace(/\//g, "").length < 8) {
            return false;
        }
        var ttdate = date.toString().split("/");
        switch (ttdate[1]) {
            case "01":
            case "03":
            case "05":
            case "07":
            case "08":
            case "10":
            case "12":
                if (ttdate[2] > 31) {
                    return false;
                }
                break;
            case "04":
            case "06":
            case "09":
            case "11":
                if (ttdate[2] > 30) {
                    return false;
                }
                break;
            case "02":
                if (ttdate[2] > 29) {
                    return false;
                }
                break;
            default:
                return false;
        }
        return true;
    };
    me.formInit = function () {
        me.initDate();

        switch (me.o_R4_FrmSyainMstList.operationMode) {
            case "UPD":
                me.initGrid();
                me.fnc_UPD();
                break;
            case "INS":
                me.initGrid();
                me.fnc_INS();
                break;
            case "DEL":
                me.initDeleteGrid();
                me.fnc_DEL();
                break;
        }
    };
    me.initDate = function () {
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        var tmpDay = myDate.getDate().toString();
        if (tmpDay.length < 2) {
            tmpDay = "0" + tmpDay.toString();
        }
        me.tmpNowDate =
            myDate.getFullYear().toString() +
            "/" +
            tmpMonth.toString() +
            "/" +
            tmpDay.toString();
    };
    me.initGrid = function () {
        //20240624 lujunxia upd s
        //me.option1 = {
        me.option = {
            //pagerpos: "left",
            multiselect: false,
            caption: "",
            rowNum: 5000000,
            //multiselectWidth: 30,
            rownumWidth: 40,
        };
        //20240624 lujunxia upd e
        me.colModel = [
            {
                name: "txtSyainNO",
                label: "社員No.",
                index: "txtSyainNO",
                width: 55,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "BUSYO_CD",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_BUSYO_CD'>所属部署</div>",
                index: "BUSYO_CD",
                width: 53,
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
                                //---20150819 li UPD S.
                                //if (!me.setColSelection(key, "BUSYO_CD", "SYUKEI_BUSYO_CD"))
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "BUSYO_CD",
                                        "SYUKEI_BUSYO_CD",
                                        "DAI_HYOUJI"
                                    )
                                ) {
                                    //---20150819 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                    ],
                },
            },
            {
                name: "SYUKEI_BUSYO_CD",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    ";text-align:left;' for='FrmSyainMstEdit_SYUKEI_BUSYO_CD'>集計処理用部署</div>",
                index: "SYUKEI_BUSYO_CD",
                width: 70,
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
                                //---20150819 li UPD S.
                                //if (!me.setColSelection(key, "SYUKEI_BUSYO_CD", "START_DATE"))
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "SYUKEI_BUSYO_CD",
                                        "START_DATE",
                                        "BUSYO_CD"
                                    )
                                ) {
                                    //---20150819 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                    ],
                },
            },
            {
                name: "START_DATE",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_START_DATE'>配属開始日</div>",
                index: "START_DATE",
                width: 90,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 10,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                me.keydown_position = "START_DATE";
                                var key = e.charCode || e.keyCode;
                                //---20150819 li UPD S.
                                //if (!me.setColSelection(key, "START_DATE", "END_DATE"))
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "START_DATE",
                                        "END_DATE",
                                        "SYUKEI_BUSYO_CD"
                                    )
                                ) {
                                    //---20150819 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                    ],
                },
            },
            {
                name: "END_DATE",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_END_DATE'>配属終了日</div>",
                index: "END_DATE",
                width: 90,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 10,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                me.keydown_position = "END_DATE";
                                var key = e.charCode || e.keyCode;
                                //---20150819 li UPD S.
                                //if (!me.setColSelection(key, "END_DATE", "SYOKUSYU_KB"))
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "END_DATE",
                                        "SYOKUSYU_KB",
                                        "START_DATE"
                                    )
                                ) {
                                    //---20150819 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                    ],
                },
            },
            {
                name: "SYOKUSYU_KB",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_SYOKUSYU_KB'>職種区分</div>",
                index: "SYOKUSYU_KB",
                width: 55,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 10,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150819 li UPD S.
                                //if (!me.setColSelection(key, "SYOKUSYU_KB", "DISP_KB"))
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "SYOKUSYU_KB",
                                        "DISP_KB",
                                        "END_DATE"
                                    )
                                ) {
                                    //---20150819 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                    ],
                },
            },
            {
                name: "DISP_KB",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_DISP_KB'>表示区分</div>",
                index: "DISP_KB",
                width: 55,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 1,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150819 li UPD S.
                                //if (!me.setColSelection(key, "DISP_KB", "DAI_HYOUJI"))
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "DISP_KB",
                                        "DAI_HYOUJI",
                                        "SYOKUSYU_KB"
                                    )
                                ) {
                                    //---20150819 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            },
                        },
                        {},
                    ],
                },
            },
            {
                name: "DAI_HYOUJI",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_DAI_HYOUJI'>台数表示区分</div>",
                index: "DAI_HYOUJI",
                width: 80,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 1,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150819 li UPD S.
                                // if (key == 229)
                                // {
                                // return false;
                                // }
                                // else
                                // if (key == 13 || (key == 9 && !e.shiftKey))
                                // {
                                // if (parseInt(me.lastsel) == (rowcount - 1))
                                // {
                                // //---
                                // var selIRow = parseInt(me.lastsel);
                                // var tmpColArr = new Array("BUSYO_CD", "DAI_HYOUJI", "DISP_KB", "SYOKUSYU_KB", "END_DATE", "START_DATE", "SYUKEI_BUSYO_CD");
                                // var tmpCntt = 0;
                                // for (key in tmpColArr)
                                // {
                                // var tmpColId = '#' + selIRow + '_' + tmpColArr[key];
                                // if ($(tmpColId).val().toString().trimEnd() == "")
                                // {
                                // tmpCntt++;
                                // }
                                // }
                                // if (tmpCntt == tmpColArr.length || tmpCntt == tmpColArr.length - 1)
                                // {
                                // return false;
                                // }
                                // else
                                // {
                                // //return false;
                                // var rowdata =
                                // {
                                // txtSyainNO : "",
                                // BUSYO_CD : "",
                                // SYUKEI_BUSYO_CD : "",
                                // START_DATE : "",
                                // END_DATE : "",
                                // SYOKUSYU_KB : "",
                                // DISP_KB : "",
                                // DAI_HYOUJI : "",
                                // };
                                // var arrIds = $(me.grid_id).jqGrid('getDataIDs');
                                // var i = arrIds.length;
                                // $(me.grid_id).jqGrid('addRowData', i, rowdata);
                                // $(me.grid_id).jqGrid('saveRow', me.lastsel);
                                // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1), true);
                                //
                                // }
                                // //---
                                //
                                // }
                                // else
                                // {
                                // $(me.grid_id).jqGrid('saveRow', me.lastsel);
                                // $(me.grid_id).jqGrid('setSelection', (parseInt(me.lastsel) + 1), true);
                                // }
                                //
                                // return false;
                                // }
                                // //down
                                // else
                                // if (key == 40)
                                // {
                                // var selIRow = "";
                                //
                                // if (parseInt(me.lastsel) == rowcount)
                                // {
                                // return false;
                                // }
                                // else
                                // {
                                // selIRow = parseInt(me.lastsel) + 1;
                                // }
                                //
                                // $(me.grid_id).jqGrid('saveRow', me.lastsel);
                                // $(me.grid_id).jqGrid('setSelection', selIRow, true);
                                //
                                // var selNextId = '#' + selIRow + '_CAL_KB';
                                // $(selNextId).focus();
                                // }
                                // //up
                                // else
                                // if (key == 38)
                                // {
                                // var selIRow = parseInt(me.lastsel) - 1;
                                //
                                // if (selIRow == -1)
                                // {
                                // return false;
                                // }
                                //
                                // $(me.grid_id).jqGrid('saveRow', me.lastsel);
                                // $(me.grid_id).jqGrid('setSelection', selIRow, true);
                                //
                                // var selNextId = '#' + selIRow + '_CAL_KB';
                                // $(selNextId).focus();
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "DAI_HYOUJI",
                                        "BUSYO_CD",
                                        "DISP_KB"
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 li UPD E.
                            },
                            //---20150819 li DEL S.
                            // },
                            // {
                            // type : 'keypress',
                            // fn : function(e)
                            // {
                            // /*if (!me.inputReplace(e.target, 1, e.keyCode))
                            // {
                            // return false;
                            // }
                            // */
                            // }
                            // },
                            // {
                            // type : 'keyup',
                            // fn : function(e)
                            // {
                            // var inputValue = $(e.target).val();
                            //
                            // if (inputValue == "-")
                            // {
                            // $(e.target).val("-0");
                            // }
                            // }
                            //---20150819 li DEL E.
                        },
                    ],
                },
            },
            {
                name: "CREATE_DATE",
                label: "作成日付",
                index: "CREATE_DATE",
                width: 55,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "SYAIN_NO",
                label: "j",
                index: "SYAIN_NO",
                width: 10,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "RIR_NO",
                label: "k",
                index: "RIR_NO",
                width: 10,
                sortable: false,
                align: "left",
                hidden: true,
            },
        ];
    };
    me.initDeleteGrid = function () {
        //20240624 lujunxia upd s
        //me.option1 = {
        me.option = {
            //pagerpos: "left",
            multiselect: false,
            caption: "",
            rowNum: 5000000,
            //multiselectWidth: 30,
            rownumWidth: 40,
        };
        //20240624 lujunxia upd e
        me.colModel = [
            {
                name: "txtSyainNO",
                label: "社員No.",
                index: "txtSyainNO",
                width: 55,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "BUSYO_CD",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_BUSYO_CD2'>所属部署</div>",
                index: "BUSYO_CD",
                width: 53,
                sortable: false,
                align: "left",
            },
            {
                name: "SYUKEI_BUSYO_CD",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    ";text-align:left;' for='FrmSyainMstEdit_SYUKEI_BUSYO_CD2'>集計処理用部署</div>",
                index: "SYUKEI_BUSYO_CD",
                width: 70,
                sortable: false,
                align: "left",
            },
            {
                name: "START_DATE",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_START_DATE2'>配属開始日</div>",
                index: "START_DATE",
                width: 90,
                sortable: false,
                align: "left",
            },
            {
                name: "END_DATE",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_END_DATE2'>配属終了日</div>",
                index: "END_DATE",
                width: 90,
                sortable: false,
                align: "left",
            },
            {
                name: "SYOKUSYU_KB",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_SYOKUSYU_KB2'>職種区分</div>",
                index: "SYOKUSYU_KB",
                width: 55,
                sortable: false,
                align: "left",
            },
            {
                name: "DISP_KB",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_DISP_KB2'>表示区分</div>",
                index: "DISP_KB",
                width: 55,
                sortable: false,
                align: "left",
            },
            {
                name: "DAI_HYOUJI",
                label:
                    "<div style='font-size:" +
                    me.fontSizeTitle +
                    "' for='FrmSyainMstEdit_DAI_HYOUJI2'>台数表示区分</div>",
                index: "DAI_HYOUJI",
                width: 80,
                sortable: false,
                align: "left",
            },
            {
                name: "CREATE_DATE",
                label: "作成日付",
                index: "CREATE_DATE",
                width: 55,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "SYAIN_NO",
                label: "j",
                index: "SYAIN_NO",
                width: 10,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "RIR_NO",
                label: "k",
                index: "RIR_NO",
                width: 10,
                sortable: false,
                align: "left",
                hidden: true,
            },
        ];
    };
    me.getGrid = function () {
        me.initGrid();
        var url = me.sys_id + "/FrmSyainMstEdit/fncGetGridValue";
        var tmpdata = {
            txtSyainNO: "",
        };
        gdmz.common.jqgrid.show(
            me.grid_id,
            url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            tmpdata,
            me.complete_fun
        );
        //---20150819 li UPD S.
        //gdmz.common.jqgrid.set_grid_width(me.grid_id, 575);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 590);
        //---20150819 li UPD E.
        //---20150810 li UPD S.
        //gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 170);
        //---20150810 li UPD E.
        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "DISP_KB",
                    numberOfColumns: 2,
                    titleText:
                        "<div style='font-size:" +
                        me.fontSizeTitle +
                        "' for='FrmSyainMstEdit_lblMSG'>固定費カバー率用</div>",
                },
            ],
        });
        //---20150818 li ADD S.
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {},
        });
        //---20150818 li ADD E.
    };
    me.complete_fun = function () {
        me.firstData = $(me.grid_id).jqGrid("getRowData");
        switch (me.o_R4_FrmSyainMstList.operationMode) {
            case "DEL":
                //edit cell
                $(me.grid_id).jqGrid("setGridParam", {
                    onSelectRow: function (_rowid, _status, e) {
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
                                    rowData["BUSYO_CD"].toString().trimEnd() ==
                                    ""
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
                        }

                        $(".numeric").numeric({
                            decimal: false,
                            negative: false,
                        });
                    },
                });
                break;
            case "UPD":
                //append blank row
                var rowdata = {
                    txtSyainNO: "",
                    BUSYO_CD: "",
                    SYUKEI_BUSYO_CD: "",
                    START_DATE: "",
                    END_DATE: "",
                    SYOKUSYU_KB: "",
                    DISP_KB: "",
                    DAI_HYOUJI: "",
                };
                var arrIds = $(me.grid_id).jqGrid("getDataIDs");
                var i = arrIds.length;
                $(me.grid_id).jqGrid("addRowData", i, rowdata);
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

                                var rowData = $(me.grid_id).jqGrid(
                                    "getRowData",
                                    rowid
                                );
                                if (
                                    rowData["BUSYO_CD"].toString().trimEnd() ==
                                    ""
                                ) {
                                    return;
                                }
                                me.jqgridCurrentRowID = rowid;
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
                    },
                });
                break;
            case "INS":
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

                                var rowID = $(me.grid_id).jqGrid(
                                    "getGridParam",
                                    "selrow"
                                );
                                var rowData = $(me.grid_id).jqGrid(
                                    "getRowData",
                                    rowID
                                );
                                if (
                                    rowData["BUSYO_CD"].toString().trimEnd() ==
                                    ""
                                ) {
                                    return;
                                }
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
                    },
                });
                break;
        }
        //me.t = document.getElementById("FrmSyainMstEdit_pager_center");
        //me.t.childNodes[1].innerHTML = "";
    };
    me.delRowData = function () {
        var rowData = $(me.grid_id).jqGrid("getRowData", me.jqgridCurrentRowID);

        me.url_delgridData = me.sys_id + "/" + me.id + "/FncDelGridData";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                // var url = me.sys_id + "/FrmSyainMstEdit/fncGetGridValue";
                // var tmpdata = {
                //     txtSyainNO: me.o_R4_FrmSyainMstList.txtSyainNo,
                // };
                gdmz.common.jqgrid.reload(
                    me.grid_id,
                    me.data,
                    me.complete_fun
                );
                //---20150818 li UPD S.
                //gdmz.common.jqgrid.set_grid_width(me.grid_id, 575);
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 590);
                //---20150818 li UPD E.
                //---20150810 li UPD S.
                //gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
                gdmz.common.jqgrid.set_grid_height(me.grid_id, 170);
                //---20150810 li UPD E.

                //gdmz.common.jqgrid.r(me.grid_id, url, me.colModel, me.pager, me.sidx, me.option, tmpdata, me.complete_fun);
            }
        };
        var data = {
            txtSyainNO: rowData["SYAIN_NO"],
            txtRIR_NO: rowData["RIR_NO"],
        };
        me.ajax.send(me.url_delgridData, data, 0);
    };
    me.cancelsel = function () {
        var getDataID = $(me.grid_id).jqGrid("getDataIDs");

        $(me.grid_id).jqGrid("setSelection", getDataID.length - 1, true);
        $(me.grid_id).jqGrid(
            "saveRow",
            getDataID.length - 1,
            null,
            "clientArray"
        );
        $(me.grid_id).jqGrid("resetSelection");
    };
    //---20150819 li UPD S.
    //me.setColSelection = function(key, colNowName, colNextName)
    me.setColSelection = function (
        e,
        key,
        colNowName,
        colNextName,
        colPreviousName //---20150819 li UPD E.
    ) {
        //---20150819 li ADD S.
        var GridRecords = $(me.grid_id).jqGrid("getGridParam", "records");
        //Shift+Tab&&Left
        if (e.shiftKey && key == 9) {
            if (
                colNowName == "DAI_HYOUJI" &&
                $("#" + me.lastsel + "_" + colNowName).val() == "-"
            ) {
                $("#" + me.lastsel + "_" + colNowName).val("-0");
            }
            if (colNowName == "BUSYO_CD" && parseInt(me.lastsel) == 0) {
                return false;
            } else if (colNowName == "BUSYO_CD" && parseInt(me.lastsel) > 0) {
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
        //Tab
        else if (key == 9) {
            if (colNowName == "DAI_HYOUJI" && me.lastsel == GridRecords - 1) {
                return false;
            } else if (
                colNowName == "DAI_HYOUJI" &&
                me.lastsel < GridRecords - 1
            ) {
                $(me.grid_id).jqGrid(
                    "saveRow",
                    me.lastsel,
                    null,
                    "clientArray"
                );
                $(me.grid_id).jqGrid("setSelection", parseInt(me.lastsel) + 1);
            }
            $("#" + me.lastsel + "_" + colNextName).trigger("focus");
            $("#" + me.lastsel + "_" + colNextName).trigger("select");
            return false;
        }
        //---20150819 li ADD E.
        //enter
        else if (key == 13) {
            //---20150819 li ADD S.
            if (colNowName == "DAI_HYOUJI") {
                if ($("#" + me.lastsel + "_" + colNowName).val() == "-") {
                    $("#" + me.lastsel + "_" + colNowName).val("-0");
                }
                if (
                    $("#" + me.lastsel + "_" + "BUSYO_CD").val() != "" ||
                    $("#" + me.lastsel + "_" + "SYUKEI_BUSYO_CD").val() != "" ||
                    $("#" + me.lastsel + "_" + "START_DATE").val() != "" ||
                    $("#" + me.lastsel + "_" + "END_DATE").val() != "" ||
                    $("#" + me.lastsel + "_" + "SYOKUSYU_KB").val() != "" ||
                    $("#" + me.lastsel + "_" + "DISP_KB").val() != ""
                ) {
                    me.JqgirdAddData();
                    GridRecords = $(me.grid_id).jqGrid(
                        "getGridParam",
                        "records"
                    );
                    if (me.lastsel < GridRecords - 1) {
                        var nowsel = parseInt(me.lastsel) + 1;
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        $(me.grid_id).jqGrid("setSelection", nowsel);
                        $(me.grid_id).jqGrid("editRow", nowsel, true);
                        $("#" + nowsel + "_" + "BUSYO_CD").trigger("focus");
                        $("#" + nowsel + "_" + "BUSYO_CD").trigger("select");
                    }
                    return false;
                }
            }
            //---20150819 li ADD E.
            //---
            if (colNowName == "START_DATE" || colNowName == "END_DATE") {
                //---20150819 li ADD S.
                if ($("#" + me.lastsel + "_" + colNowName).val() != "") {
                    //---20150819 li ADD E.
                    if (
                        $("#" + me.lastsel + "_" + colNowName)
                            .val()
                            .indexOf("/") < 0
                    ) {
                        var tmpDate__ = $("#" + me.lastsel + "_" + colNowName)
                            .val()
                            .substr(0, 8);
                        var tmpYear__ = tmpDate__.substr(0, 4);
                        var tmpMonth__ = tmpDate__.substr(4, 2);
                        var tmpDay__ = tmpDate__.substr(6, 2);
                        $("#" + me.lastsel + "_" + colNowName).val(
                            tmpYear__ + "/" + tmpMonth__ + "/" + tmpDay__
                        );
                    }
                    //---20150819 li ADD S.
                }
                //---20150819 li ADD E.
                //---20150819 li DEL S.
                // var tf = me.isDate($('#' + me.lastsel + '_' + colNowName).val());
                // if (!tf)
                // {
                // return false;
                // }
                //---20150819 li DEL E.
            }
            //---
            $("#" + me.lastsel + "_" + colNextName).trigger("focus");
            return false;
        }
        //down
        else if (key == 40) {
            //---20150819 li ADD S.
            if (
                colNowName == "DAI_HYOUJI" &&
                $("#" + me.lastsel + "_" + colNowName).val() == "-"
            ) {
                $("#" + me.lastsel + "_" + colNowName).val("-0");
            }
            //---20150819 li ADD E.
            //---
            if (colNowName == "START_DATE" || colNowName == "END_DATE") {
                //---20150819 li ADD S.
                if ($("#" + me.lastsel + "_" + colNowName).val() != "") {
                    //---20150819 li ADD E.
                    if (
                        $("#" + me.lastsel + "_" + colNowName)
                            .val()
                            .indexOf("/") < 0
                    ) {
                        var tmpDate__ = $("#" + me.lastsel + "_" + colNowName)
                            .val()
                            .substr(0, 8);
                        var tmpYear__ = tmpDate__.substr(0, 4);
                        var tmpMonth__ = tmpDate__.substr(4, 2);
                        var tmpDay__ = tmpDate__.substr(6, 2);
                        $("#" + me.lastsel + "_" + colNowName).val(
                            tmpYear__ + "/" + tmpMonth__ + "/" + tmpDay__
                        );
                    }
                    //---20150819 li ADD S.
                }
                //---20150819 li ADD E.
                //---20150819 li DEL S.
                // var tf = me.isDate($('#' + me.lastsel + '_' + colNowName).val());
                // if (!tf)
                // {
                // return false;
                // }
                //---20150819 li DEL E.
            }
            //---
            //---20150819 li UPD S.
            // var selIRow = "";
            // var rowcount = parseInt($(me.grid_id).getGridParam("records"));
            //
            // if (parseInt(me.lastsel) == rowcount - 1)
            // {
            // return false;
            // }
            // else
            // {
            // selIRow = parseInt(me.lastsel) + 1;
            // }
            //
            // $(me.grid_id).jqGrid('saveRow', me.lastsel);
            // $(me.grid_id).jqGrid('setSelection', selIRow, true);
            //
            // var selNextId = '#' + selIRow + '_' + colNowName;
            // $(selNextId).focus();
            if (me.lastsel < GridRecords - 1) {
                var nowsel = parseInt(me.lastsel) + 1;
                $(me.grid_id).jqGrid(
                    "saveRow",
                    me.lastsel,
                    null,
                    "clientArray"
                );
                $(me.grid_id).jqGrid("setSelection", nowsel);

                $("#" + nowsel + "_" + colNowName).trigger("focus");
                $("#" + nowsel + "_" + colNowName).trigger("select");
            }
            return false;
            //---20150819 li UPD E.
        }
        //up
        else if (key == 38) {
            //---
            if (colNowName == "START_DATE" || colNowName == "END_DATE") {
                //---20150819 li ADD S.
                if ($("#" + me.lastsel + "_" + colNowName).val() != "") {
                    //---20150819 li ADD E.
                    if (
                        $("#" + me.lastsel + "_" + colNowName)
                            .val()
                            .indexOf("/") < 0
                    ) {
                        var tmpDate__ = $("#" + me.lastsel + "_" + colNowName)
                            .val()
                            .substr(0, 8);
                        var tmpYear__ = tmpDate__.substr(0, 4);
                        var tmpMonth__ = tmpDate__.substr(4, 2);
                        var tmpDay__ = tmpDate__.substr(6, 2);
                        $("#" + me.lastsel + "_" + colNowName).val(
                            tmpYear__ + "/" + tmpMonth__ + "/" + tmpDay__
                        );
                    }
                    //---20150819 li ADD S.
                }
                //---20150819 li ADD E.
                //---20150819 li DEL S.
                // var tf = me.isDate($('#' + me.lastsel + '_' + colNowName).val());
                // if (!tf)
                // {
                // return false;
                // }
                //---20150819 li DEL E.
            }
            //---20150819 li DEL S.
            //---
            // var selIRow = parseInt(me.lastsel) - 1;
            //
            // if (selIRow == -1)
            // {
            // return false;
            // }
            //
            // $(me.grid_id).jqGrid('saveRow', me.lastsel);
            // $(me.grid_id).jqGrid('setSelection', selIRow, true);
            //
            // var selNextId = '#' + selIRow + '_' + colNowName;
            //
            // $(selNextId).focus();
            //---20150819 li DEL E.

            //---20150819 li ADD S.
            if (me.lastsel > 0) {
                var nowsel = parseInt(me.lastsel) - 1;
                $(me.grid_id).jqGrid(
                    "saveRow",
                    me.lastsel,
                    null,
                    "clientArray"
                );
                $(me.grid_id).jqGrid("setSelection", nowsel);

                $("#" + nowsel + "_" + colNowName).trigger("focus");
                $("#" + nowsel + "_" + colNowName).trigger("select");
            }
            return false;
            //---20150819 li ADD E.
        }
        //---20150810 li del S.
        // else
        // if (((key >= 48 && key <= 57) || (key >= 95 && key <= 105)) && (colNowName == "START_DATE" || colNowName == "END_DATE"))
        // {
        // var tmp__date = me.tmpNowDate.substr(1, me.tmpNowDate.length);
        // if (key >= 48 && key <= 57)
        // {
        // tmp__date = (key - 48) + tmp__date;
        // }
        // else
        // {
        // tmp__date = (key - 95) + tmp__date;
        // }
        //
        // if ($('#' + me.lastsel + '_' + colNowName).val().toString().trimEnd() != "")
        // {
        //
        // }
        // else
        // {
        // $('#' + me.lastsel + '_' + colNowName).val(tmp__date);
        // }
        // }
        //---20150810 li del E.
        return true;
    };
    me.getFormValues = function () {
        var url = me.sys_id + "/FrmSyainMstEdit/fncFromSyainSelect";
        var tmpData = {
            txtSyainNO: me.o_R4_FrmSyainMstList.txtSyainNo,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                if (result["data"].length > 0) {
                    me.setFormValues(result["data"][0]);
                    if (me.o_R4_FrmSyainMstList.operationMode == "DEL") {
                        me.getGridValues();
                    } else {
                        me.getGridValues();
                    }
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, tmpData, 1);
    };
    me.setFormValues = function (data) {
        $(".FrmSyainMstEdit.txtSyainNO").val(data["SYAIN_NO"]);
        $(".FrmSyainMstEdit.txtSyainNM").val(data["SYAIN_NM"]);
        $(".FrmSyainMstEdit.txtSikakuCD").val(data["SIKAKU_CD"]);
        $(".FrmSyainMstEdit.txtSyainKN").val(data["SYAIN_KN"]);
        $(".FrmSyainMstEdit.txtKBN").val(
            me.clsComFnc.FncNv(data["SLSSUTAFF_KB"])
        );
        $(".FrmSyainMstEdit.txtCreateDate").val(
            me.clsComFnc.FncNv(data["CREATE_DATE"])
        );
        if (data["TAISYOKU_DATE"] === null) {
            $(".FrmSyainMstEdit.chkTaisyokuYMD").prop("checked", false);
            $(".FrmSyainMstEdit.cboTaisyokuYMD").val(me.tmpNowDate);
            $(".FrmSyainMstEdit.cboTaisyokuYMD").prop("disabled", "disabled");
        } else {
            $(".FrmSyainMstEdit.chkTaisyokuYMD").prop("checked", true);
            $(".FrmSyainMstEdit.cboTaisyokuYMD").val(data["TAISYOKU_DATE"]);
            $(".FrmSyainMstEdit.cboTaisyokuYMD").prop("disabled", false);
        }

        if (me.o_R4_FrmSyainMstList.operationMode == "DEL") {
            $(".FrmSyainMstEdit.txtSyainNO").prop("disabled", "disabled");
            $(".FrmSyainMstEdit.txtSyainNM").prop("disabled", "disabled");
            $(".FrmSyainMstEdit.txtSikakuCD").prop("disabled", "disabled");
            $(".FrmSyainMstEdit.txtSyainKN").prop("disabled", "disabled");
            $(".FrmSyainMstEdit.txtKBN").prop("disabled", "disabled");
            $(".FrmSyainMstEdit.cboTaisyokuYMD").prop("disabled", "disabled");
            $(".FrmSyainMstEdit.cmdAction").text("削除");
        } else {
            $(".FrmSyainMstEdit.cmdAction").text("更新");
            $(".FrmSyainMstEdit.txtSyainNO").prop("disabled", "disabled");
            $(".FrmSyainMstEdit.txtSyainNM").trigger("focus");
            $(".FrmSyainMstEdit.txtSyainNM").trigger("select");
        }
    };
    me.getGridValues = function () {
        var url = me.sys_id + "/FrmSyainMstEdit/fncGetGridValue";
        var tmpdata = {
            txtSyainNO: me.o_R4_FrmSyainMstList.txtSyainNo,
        };
        gdmz.common.jqgrid.show(
            me.grid_id,
            url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            tmpdata,
            me.complete_fun
        );
        //---20150818 li UPD S.
        //gdmz.common.jqgrid.set_grid_width(me.grid_id, 575);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 590);
        //---20150818 li UPD E.
        //1090
        //---20150810 li UPD S.
        //gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 170);
        //---20150810 li UPD E.
        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "DISP_KB",
                    numberOfColumns: 2,
                    titleText:
                        "<div style='font-size:" +
                        me.fontSizeTitle +
                        "' for='FrmSyainMstEdit_lblMSG2'>固定費カバー率用</div>",
                },
            ],
        });
        //---20150818 li ADD S.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150818 li ADD E.
    };
    /*
     '**********************************************************************
     '処 理 名：スプレッドの入力チェック
     '関 数 名：fncInputChk
     '引    数：lntTeika  (I)定価合計
     '戻 り 値：True:正常終了 False:異常終了
     '処理説明：スプレッドの入力チェック
     '**********************************************************************/
    me.fncInputChk = function () {
        me.isError = true;
        me.intDataCount = 0;
        me.strMaxStDate = "";
        me.strMaxEdDate = "";
        //社員№ﾁｪｯｸ
        me.intRtn = me.clsComFnc.FncTextCheck(
            $(".FrmSyainMstEdit.txtSyainNO"),
            1,
            me.clsComFnc.INPUTTYPE.CHAR2
        );
        if (me.intRtn < 0) {
            $(".FrmSyainMstEdit.txtSyainNO").trigger("focus");
            $(".FrmSyainMstEdit.txtSyainNO").trigger("select");
            $(".FrmSyainMstEdit.txtSyainNO").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "社員No.");
            return false;
        }
        //社員名ﾁｪｯｸ
        me.intRtn = me.clsComFnc.FncTextCheck(
            $(".FrmSyainMstEdit.txtSyainNM"),
            1,
            me.clsComFnc.INPUTTYPE.NONE
        );
        if (me.intRtn < 0) {
            $(".FrmSyainMstEdit.txtSyainNM").trigger("focus");
            $(".FrmSyainMstEdit.txtSyainNM").trigger("select");
            $(".FrmSyainMstEdit.txtSyainNM").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "社員名");
            return false;
        }
        //社員名カナﾁｪｯｸ
        //---20150810 li UPD S
        //me.intRtn = me.clsComFnc.FncTextCheck($(".FrmSyainMstEdit.txtSyainKN") , 1, me.clsComFnc.INPUTTYPE.CHAR5);
        me.intRtn = me.clsComFnc.FncTextCheck(
            $(".FrmSyainMstEdit.txtSyainKN"),
            1,
            me.clsComFnc.INPUTTYPE.CHAR6
        );
        //---20150810 li UPD E
        if (me.intRtn < 0) {
            $(".FrmSyainMstEdit.txtSyainKN").trigger("focus");
            $(".FrmSyainMstEdit.txtSyainKN").trigger("select");
            $(".FrmSyainMstEdit.txtSyainKN").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "社員名カナ");
            return false;
        }
        //資格ｺｰﾄﾞﾁｪｯｸ
        me.intRtn = me.clsComFnc.FncTextCheck(
            $(".FrmSyainMstEdit.txtSikakuCD"),
            0,
            me.clsComFnc.INPUTTYPE.NUMBER1
        );
        if (me.intRtn < 0) {
            $(".FrmSyainMstEdit.txtSikakuCD").trigger("focus");
            $(".FrmSyainMstEdit.txtSikakuCD").trigger("select");
            $(".FrmSyainMstEdit.txtSikakuCD").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "資格ｺｰﾄﾞ");
            return false;
        }
        //営業スタッフ区分ﾁｪｯｸ
        me.intRtn = me.clsComFnc.FncTextCheck(
            $(".FrmSyainMstEdit.txtKBN"),
            1,
            me.clsComFnc.INPUTTYPE.CHAR2
        );
        if (me.intRtn < 0) {
            $(".FrmSyainMstEdit.txtKBN").trigger("focus");
            $(".FrmSyainMstEdit.txtKBN").trigger("select");
            $(".FrmSyainMstEdit.txtKBN").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "営業スタッフ区分");
            return false;
        }

        //営業スタッフ区分ﾁｪｯｸ
        var tmpa_flg = 0;
        switch ($(".FrmSyainMstEdit.txtKBN").val()) {
            case "1":
                tmpa_flg++;
            case "3":
                tmpa_flg++;
            case "9":
                tmpa_flg++;
        }
        if (tmpa_flg == 0) {
            $(".FrmSyainMstEdit.txtKBN").trigger("focus");
            $(".FrmSyainMstEdit.txtKBN").trigger("select");
            $(".FrmSyainMstEdit.txtKBN").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W0002", "営業スタッフ区分");
            return false;
        }
        //退職日ﾁｪｯｸ
        if ($(".FrmSyainMstEdit.chkTaisyokuYMD").prop("checked")) {
            me.intRtn = me.clsComFnc.FncTextCheck(
                $(".FrmSyainMstEdit.cboTaisyokuYMD"),
                1,
                me.clsComFnc.INPUTTYPE.DATE1
            );
            if (me.intRtn < 0) {
                $(".FrmSyainMstEdit.cboTaisyokuYMD").trigger("focus");
                $(".FrmSyainMstEdit.cboTaisyokuYMD").trigger("select");
                $(".FrmSyainMstEdit.cboTaisyokuYMD").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "退職日");
                return false;
            }
        } else {
            $(".FrmSyainMstEdit.cboTaisyokuYMD").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
        }
        //check jqgrid
        me.jqgridData_inputCheck = $(me.grid_id).jqGrid("getDataIDs");
        me.iColNo_inputCheck = 0;
        me.iRowNo_inputCheck = 0;
        me.rowID_inputCheck = -1;
        me.iRowCnt_inputCheck = me.jqgridData_inputCheck.length;
        me.inputCheck_callback();
    };
    me.setFocus = function () {
        var rowID = parseInt(me.rowNum);
        $(me.grid_id).jqGrid("setSelection", rowID);
        $(me.grid_id).jqGrid("editRow", rowID, true);

        var ceil = rowID + "_" + me.colNum;
        me.clsComFnc.ObjFocus = $("#" + ceil);
        me.clsComFnc.ObjSelect = $("#" + ceil);
    };
    me.subSetBackColor = function () {};
    me.inputCheck_callback = function () {
        if (me.iRowNo_inputCheck >= me.iRowCnt_inputCheck) {
            //return false;
            me.fncAfterGridCheckIsTrue();
            return false;
        }
        me.iColNo_inputCheck++;
        //どれか一列でも入力されていた場合
        var rowData = $(me.grid_id).jqGrid(
            "getRowData",
            me.jqgridData_inputCheck[me.iRowNo_inputCheck]
        );
        if (rowData["BUSYO_CD"] != "") {
            me.rowID_inputCheck++;
            me.intDataCount += 1;
            //所属部署チェック
            me.intRtn = me.clsComFnc.FncSprCheck(
                rowData["BUSYO_CD"],
                1,
                me.clsComFnc.INPUTTYPE.NUMBER1,
                me.colModel[1]["editoptions"]["maxlength"]
            );
            if (me.intRtn < 0) {
                me.rowNum = me.rowID_inputCheck;
                me.colNum = "BUSYO_CD";
                me.setFocus();
                me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "所属部署");
                return false;
            }
            //集計処理用部署チェック
            me.intRtn = me.clsComFnc.FncSprCheck(
                rowData["SYUKEI_BUSYO_CD"],
                1,
                me.clsComFnc.INPUTTYPE.NUMBER1,
                me.colModel[2]["editoptions"]["maxlength"]
            );
            if (me.intRtn < 0) {
                me.rowNum = me.rowID_inputCheck;
                me.colNum = "SYUKEI_BUSYO_CD";
                me.setFocus();
                me.clsComFnc.FncMsgBox(
                    "W000" + me.intRtn * -1,
                    "集計処理用部署"
                );
                return false;
            }
            //配属開始日チェック
            if (rowData["START_DATE"] == "") {
                me.rowNum = me.rowID_inputCheck;
                me.colNum = "START_DATE";
                me.setFocus();
                me.clsComFnc.FncMsgBox("W0001", "配属開始日");
                return false;
            }
            var tf = me.isDate(rowData["START_DATE"]);
            if (!tf) {
                me.rowNum = me.rowID_inputCheck;
                me.colNum = "START_DATE";
                me.setFocus();
                me.clsComFnc.FncMsgBox("W0002", "配属開始日");
                return false;
            }
            if (rowData["END_DATE"] != "") {
                var tf = me.isDate(rowData["END_DATE"]);
                if (!tf) {
                    me.rowNum = me.rowID_inputCheck;
                    me.colNum = "END_DATE";
                    me.setFocus();
                    //---20150810 li upd S.
                    //me.clsComFnc.FncMsgBox("W0002", "配属開始日");
                    me.clsComFnc.FncMsgBox("W0002", "配属終了日");
                    //---20150810 li upd E.
                    return false;
                }
            }

            //職種区分チェック
            me.intRtn = me.clsComFnc.FncSprCheck(
                rowData["SYOKUSYU_KB"],
                1,
                me.clsComFnc.INPUTTYPE.NUMBER1,
                me.colModel[5]["editoptions"]["maxlength"]
            );
            if (me.intRtn < 0) {
                me.rowNum = me.rowID_inputCheck;
                me.colNum = "SYOKUSYU_KB";
                me.setFocus();
                me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "職種区分");
                return false;
            }
            //表示区分チェック
            me.intRtn = me.clsComFnc.FncSprCheck(
                rowData["DISP_KB"],
                0,
                me.clsComFnc.INPUTTYPE.NUMBER1,
                me.colModel[6]["editoptions"]["maxlength"]
            );
            if (me.intRtn < 0) {
                me.rowNum = me.rowID_inputCheck;
                me.colNum = "DISP_KB";
                me.setFocus();
                me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "表示区分");
                return false;
            }
            //台数表示区分チェック
            me.intRtn = me.clsComFnc.FncSprCheck(
                rowData["DAI_HYOUJI"],
                0,
                me.clsComFnc.INPUTTYPE.NUMBER1,
                me.colModel[7]["editoptions"]["maxlength"]
            );
            if (me.intRtn < 0) {
                me.rowNum = me.rowID_inputCheck;
                me.colNum = "DAI_HYOUJI";
                me.setFocus();
                me.clsComFnc.FncMsgBox("W000" + me.intRtn * -1, "台数表示区分");
                return false;
            }
            //所属部署チェック
            me.url_getBusyoMstValue =
                me.sys_id + "/" + me.id + "/fncGetBusyoMstValue";
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["result"] == true) {
                    //所属部署チェック
                    if (result["data"] < 1) {
                        me.rowNum = me.rowID_inputCheck;
                        me.colNum = "BUSYO_CD";
                        me.setFocus();
                        me.clsComFnc.FncMsgBox("W0008", "所属部署");
                        return false;
                    } else {
                        //集計処理用部署チェック
                        me.ajax.receive = function (result) {
                            result = eval("(" + result + ")");
                            if (result["result"] == false) {
                                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                                return;
                            }
                            if (result["result"] == true) {
                                //所属部署チェック
                                if (result["data"] < 1) {
                                    me.rowNum = me.rowID_inputCheck;
                                    me.colNum = "SYUKEI_BUSYO_CD";
                                    me.setFocus();
                                    me.clsComFnc.FncMsgBox(
                                        "W0008",
                                        "集計処理用部署"
                                    );
                                    return false;
                                } else {
                                    //----------

                                    //表示区分チェック
                                    if (rowData["DISP_KB"] != "") {
                                        var tmpa_flg = 0;
                                        switch (rowData["DISP_KB"]) {
                                            case "1":
                                                tmpa_flg++;
                                            case "2":
                                                tmpa_flg++;
                                            case "3":
                                                tmpa_flg++;
                                            case "9":
                                                tmpa_flg++;
                                        }
                                        if (tmpa_flg == 0) {
                                            me.rowNum = me.rowID_inputCheck;
                                            me.colNum = "DISP_KB";
                                            me.setFocus();
                                            me.clsComFnc.FncMsgBox(
                                                "W0002",
                                                "表示区分"
                                            );
                                            return false;
                                        }
                                    }
                                    //台数表示区分チェック
                                    if (rowData["DAI_HYOUJI"] != "") {
                                        if (rowData["DAI_HYOUJI"] != 1) {
                                            me.rowNum = me.rowID_inputCheck;
                                            me.colNum = "DAI_HYOUJI";
                                            me.setFocus();
                                            me.clsComFnc.FncMsgBox(
                                                "W0002",
                                                "台数表示区分"
                                            );
                                            return false;
                                        }
                                    }
                                    for (
                                        var j = 0;
                                        j < me.iRowCnt_inputCheck;
                                        j++
                                    ) {
                                        if (j != me.iRowNo_inputCheck) {
                                            //配属開始日が重複チェック
                                            var tmp_j_rowID =
                                                me.jqgridData_inputCheck[j];
                                            var tmp_j_RowData = $(
                                                me.grid_id
                                            ).jqGrid("getRowData", tmp_j_rowID);
                                            if (
                                                rowData["START_DATE"] != "" &&
                                                tmp_j_RowData["START_DATE"] !=
                                                    ""
                                            ) {
                                                if (
                                                    rowData["START_DATE"] ==
                                                    tmp_j_RowData["START_DATE"]
                                                ) {
                                                    me.rowNum = j;
                                                    if (
                                                        me.firstData.length -
                                                            1 >=
                                                        me.rowID_inputCheck
                                                    ) {
                                                        if (
                                                            me.firstData[
                                                                me
                                                                    .rowID_inputCheck
                                                            ]["START_DATE"] !==
                                                            rowData[
                                                                "START_DATE"
                                                            ]
                                                        ) {
                                                            me.rowNum =
                                                                me.rowID_inputCheck;
                                                        }
                                                    }
                                                    me.colNum = "START_DATE";
                                                    me.setFocus();
                                                    me.clsComFnc.FncMsgBox(
                                                        "W9999",
                                                        "配属開始日が重複しています！"
                                                    );
                                                    return false;
                                                }
                                            }
                                            //配属開終了が重複チェック
                                            var tmp_j_rowID =
                                                me.jqgridData_inputCheck[j];
                                            var tmp_j_RowData = $(
                                                me.grid_id
                                            ).jqGrid("getRowData", tmp_j_rowID);
                                            if (
                                                rowData["END_DATE"] != "" &&
                                                tmp_j_RowData["END_DATE"] != ""
                                            ) {
                                                if (
                                                    rowData["END_DATE"] ==
                                                    tmp_j_RowData["END_DATE"]
                                                ) {
                                                    me.rowNum = j;
                                                    if (
                                                        me.firstData.length -
                                                            1 >=
                                                        me.rowID_inputCheck
                                                    ) {
                                                        if (
                                                            me.firstData[
                                                                me
                                                                    .rowID_inputCheck
                                                            ]["END_DATE"] !==
                                                            rowData["END_DATE"]
                                                        ) {
                                                            me.rowNum =
                                                                me.rowID_inputCheck;
                                                        }
                                                    }
                                                    me.colNum = "END_DATE";
                                                    me.setFocus();
                                                    me.clsComFnc.FncMsgBox(
                                                        "W9999",
                                                        "配属終了日が重複しています！"
                                                    );
                                                    return false;
                                                }
                                            }
                                            //配属開始日と配属終了日が重複している場合、エラー
                                            if (
                                                rowData["START_DATE"] ==
                                                tmp_j_RowData["END_DATE"]
                                            ) {
                                                me.rowNum = j;
                                                if (
                                                    me.firstData.length - 1 >=
                                                    me.rowID_inputCheck
                                                ) {
                                                    if (
                                                        me.firstData[
                                                            me.rowID_inputCheck
                                                        ]["START_DATE"] !==
                                                        rowData["START_DATE"]
                                                    ) {
                                                        me.rowNum =
                                                            me.rowID_inputCheck;
                                                    }
                                                }
                                                me.colNum = "END_DATE";
                                                me.setFocus();
                                                me.clsComFnc.FncMsgBox(
                                                    "W9999",
                                                    "配属開始日と配属終了日が重複しています！"
                                                );
                                                return false;
                                            }
                                            //配属開終了が重複チェック
                                            if (
                                                rowData["START_DATE"] <
                                                    tmp_j_RowData[
                                                        "START_DATE"
                                                    ] &&
                                                rowData["END_DATE"] >
                                                    tmp_j_RowData["START_DATE"]
                                            ) {
                                                me.rowNum = j;
                                                me.colNum = "START_DATE";
                                                me.setFocus();
                                                me.clsComFnc.FncMsgBox(
                                                    "W9999",
                                                    "配属開始日の範囲が不正です！"
                                                );
                                                return false;
                                            }
                                        }
                                    }
                                    if (
                                        rowData["START_DATE"] != "" &&
                                        rowData["END_DATE"] != ""
                                    ) {
                                        if (
                                            rowData["START_DATE"] >
                                            rowData["END_DATE"]
                                        ) {
                                            me.rowNum = me.rowID_inputCheck;
                                            me.colNum = "END_DATE";
                                            me.setFocus();
                                            me.clsComFnc.FncMsgBox(
                                                "W9999",
                                                "日付の大小関係が不正です！"
                                            );
                                            return false;
                                        }
                                    }
                                    if (me.iRowNo_inputCheck == 0) {
                                        me.strMaxStDate = rowData["START_DATE"];
                                        me.strMaxEdDate = me.clsComFnc.FncNv(
                                            rowData["END_DATE"]
                                        );
                                    }
                                    if (
                                        me.clsComFnc.FncNv(
                                            rowData["START_DATE"]
                                        ) > me.strMaxStDate
                                    ) {
                                        me.strMaxStDate = rowData["START_DATE"];
                                        me.strMaxEdDate = me.clsComFnc.FncNv(
                                            rowData["END_DATE"]
                                        );
                                    }
                                    //----------
                                    me.iRowNo_inputCheck++;
                                    if (
                                        me.rowID_inputCheck <
                                        me.iRowCnt_inputCheck
                                    ) {
                                        me.inputCheck_callback();
                                    } else {
                                        me.fncAfterGridCheckIsTrue();
                                    }
                                }
                            }
                        };
                        var tmp_data = {
                            busyoCD: rowData["SYUKEI_BUSYO_CD"],
                        };
                        me.ajax.send(me.url_getBusyoMstValue, tmp_data, 0);
                    }
                }
            };
            var tmp_data = {
                busyoCD: rowData["BUSYO_CD"],
            };
            me.ajax.send(me.url_getBusyoMstValue, tmp_data, 0);
        } else {
            me.fncAfterGridCheckIsTrue();
        }
    };
    me.fncAfterCheckIsTrue = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
        me.clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
        me.clsComFnc.FncMsgBox("QY010");
    };
    me.fncAfterGridCheckIsTrue = function () {
        //'画面：退職日≠"" AND MAX(配属先情報：配属終了日) ≠ ""の場合はエラー
        if ($(".FrmSyainMstEdit.chkTaisyokuYMD").prop("checked")) {
            if (
                $(".FrmSyainMstEdit.cboTaisyokuYMD").val() != "" &&
                me.strMaxEdDate != ""
            ) {
                $(".FrmSyainMstEdit.cboTaisyokuYMD").trigger("focus");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "退職日を入力した場合は、最終の配属終了日は入力できません！"
                );
                return false;
            }
        }
        //'配属先入力に1件も表示されていない場合は、エラー
        if (me.intDataCount < 1) {
            me.clsComFnc.FncMsgBox("W0017", "ﾃﾞｰﾀ");
            return false;
        }

        //ﾌﾟﾛﾊﾟﾃｨ：モード＝"INS"の場合
        if (me.o_R4_FrmSyainMstList.operationMode == "INS") {
            me.url_getSyainNo = me.sys_id + "/" + me.id + "/fncGetSyainNo";
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["result"] == true) {
                    if (result["data"].length > 0) {
                        $(".FrmSyainMstEdit.txtSyainNO").trigger("focus");
                        $(".FrmSyainMstEdit.txtSyainNO").trigger("select");
                        me.clsComFnc.FncMsgBox("W0013", "社員No.");
                        $(".FrmSyainMstEdit.txtSyainNO").css(
                            me.clsComFnc.GC_COLOR_ERROR
                        );
                        return;
                    } else {
                        //---check is true---
                        me.fncAfterCheckIsTrue();
                    }
                }
            };
            var data = {
                txtSyainNO: $(".FrmSyainMstEdit.txtSyainNO").val(),
            };
            me.ajax.send(me.url_getSyainNo, data, 0);
        } else {
            //---check is true---
            me.fncAfterCheckIsTrue();
        }
    };
    me.NoActionFnc = function () {
        return;
    };
    me.YesActionFnc = function () {
        switch (me.o_R4_FrmSyainMstList.operationMode) {
            case "UPD":
                //社員ﾏｽﾀと配属先の登録処理を行う
                me.url_update = me.sys_id + "/" + me.id + "/fncUpdate";
                var grid_data = $(me.grid_id).jqGrid("getRowData");

                //获取总记录records

                var update_data = {
                    form: {
                        txtSyainNO: $(".FrmSyainMstEdit.txtSyainNO").val(),
                        txtSyainNM: $(".FrmSyainMstEdit.txtSyainNM").val(),
                        txtSyainKN: $(".FrmSyainMstEdit.txtSyainKN").val(),
                        txtSikakuCD: $(".FrmSyainMstEdit.txtSikakuCD").val(),
                        txtKBN: $(".FrmSyainMstEdit.txtKBN").val(),
                        cboTaisyokuYMD: $(
                            ".FrmSyainMstEdit.cboTaisyokuYMD"
                        ).val(),
                        chkTaisyokuYMD: $(
                            ".FrmSyainMstEdit.chkTaisyokuYMD"
                        ).prop("checked"),
                        txtCreateDate: $(
                            ".FrmSyainMstEdit.txtCreateDate"
                        ).val(),
                    },
                    grid: grid_data,
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == false) {
                        me.clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    if (result["result"] == true) {
                        me.o_R4_FrmSyainMstList.fnc_closeSubDialog();
                    }
                };
                me.ajax.send(me.url_update, update_data, 0);
                break;
            case "INS":
                //社員ﾏｽﾀと配属先の登録処理を行う
                me.url_update = me.sys_id + "/" + me.id + "/fncInsert";
                var grid_data = $(me.grid_id).jqGrid("getRowData");

                //获取总记录records

                var update_data = {
                    form: {
                        txtSyainNO: $(".FrmSyainMstEdit.txtSyainNO").val(),
                        txtSyainNM: $(".FrmSyainMstEdit.txtSyainNM").val(),
                        txtSyainKN: $(".FrmSyainMstEdit.txtSyainKN").val(),
                        txtSikakuCD: $(".FrmSyainMstEdit.txtSikakuCD").val(),
                        txtKBN: $(".FrmSyainMstEdit.txtKBN").val(),
                        cboTaisyokuYMD: $(
                            ".FrmSyainMstEdit.cboTaisyokuYMD"
                        ).val(),
                        chkTaisyokuYMD: $(
                            ".FrmSyainMstEdit.chkTaisyokuYMD"
                        ).prop("checked"),
                        txtCreateDate: $(
                            ".FrmSyainMstEdit.txtCreateDate"
                        ).val(),
                    },
                    grid: grid_data,
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == false) {
                        me.clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    if (result["result"] == true) {
                        //if(result['result'])
                        $(".FrmSyainMstEdit.txtSyainNO").val("");
                        $(".FrmSyainMstEdit.txtSyainNM").val("");
                        $(".FrmSyainMstEdit.txtSikakuCD").val("");
                        $(".FrmSyainMstEdit.txtSyainKN").val("");
                        $(".FrmSyainMstEdit.txtKBN").val("");
                        $(".FrmSyainMstEdit.txtCreateDate").val("");
                        $(".FrmSyainMstEdit.chkTaisyokuYMD").prop(
                            "checked",
                            false
                        ),
                            $(".FrmSyainMstEdit.cboTaisyokuYMD").prop(
                                "disabled",
                                "disabled"
                            );
                        $(me.grid_id).jqGrid("clearGridData");
                    }
                };
                me.ajax.send(me.url_update, update_data, 0);
                break;
            case "DEL":
                //社員ﾏｽﾀと配属先の登録処理を行う
                me.url_update = me.sys_id + "/" + me.id + "/fncDelete";
                var grid_data = $(me.grid_id).jqGrid("getRowData");

                //获取总记录records

                var update_data = {
                    form: {
                        txtSyainNO: $(".FrmSyainMstEdit.txtSyainNO").val(),
                    },
                    grid: grid_data,
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == false) {
                        me.clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    if (result["result"] == true) {
                        me.o_R4_FrmSyainMstList.fnc_closeSubDialog();
                    }
                };
                me.ajax.send(me.url_update, update_data, 0);

                break;
        }
    };

    me.fnc_UPD = function () {
        me.getFormValues();
        $(".FrmSyainMstEdit.cboTaisyokuYMD").datepicker("option", {
            disabled: true,
        });
    };
    me.fnc_INS = function () {
        me.getGrid();
        $(".FrmSyainMstEdit.cboTaisyokuYMD").prop("disabled", "disabled");
        $(".FrmSyainMstEdit.chkTaisyokuYMD").prop("checked", false);
        $(".FrmSyainMstEdit.cboTaisyokuYMD").val(me.tmpNowDate);
        $(".FrmSyainMstEdit.cboTaisyokuYMD").datepicker("option", {
            disabled: true,
        });
    };
    me.fnc_DEL = function () {
        me.getFormValues();
        $(".FrmSyainMstEdit.cboTaisyokuYMD").datepicker("option", {
            disabled: true,
        });
    };

    //----event click functions----
    me.fnc_click_cmdAction = function () {
        switch (me.o_R4_FrmSyainMstList.operationMode) {
            case "UPD":
                //ﾌﾟﾛﾊﾟﾃｨ：モード＝"UPD"の場合
                //入力チェックを行う
                me.fncInputChk();

                break;
            case "INS":
                me.fncInputChk();
                break;
            case "DEL":
                //me.fncInputChk();
                me.fncAfterCheckIsTrue();
                break;
        }
    };
    //----event blur functions----
    me.fnc_blur_txtSyainNO = function () {
        if (
            me.o_R4_FrmSyainMstList.operationMode == "INS" &&
            $(".FrmSyainMstEdit.txtSyainNO").val().toString().trimEnd() != ""
        ) {
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            if (arrIds.length == 0) {
                var rowdata = {
                    txtSyainNO: "",
                    BUSYO_CD: "",
                    SYUKEI_BUSYO_CD: "",
                    START_DATE: "",
                    END_DATE: "",
                    SYOKUSYU_KB: "",
                    DISP_KB: "",
                    DAI_HYOUJI: "",
                };
                var arrIds = $(me.grid_id).jqGrid("getDataIDs");
                var i = arrIds.length;
                $(me.grid_id).jqGrid("addRowData", i, rowdata);
            }
        }
    };
    //---20150819 li ADD S.
    me.JqgirdAddData = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");

        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (tmpcnt.length - 1 == rowID) {
            rowdata = {
                txtSyainNO: "",
                BUSYO_CD: "",
                SYUKEI_BUSYO_CD: "",
                START_DATE: "",
                END_DATE: "",
                SYOKUSYU_KB: "",
                DISP_KB: "",
                DAI_HYOUJI: "",
            };
            $(me.grid_id).jqGrid("addRowData", tmpcnt.length, rowdata);
        }
    };
    //---20150819 li ADD E.
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyainMstEdit = new R4.FrmSyainMstEdit();
    o_R4K_R4K.FrmSyainMstList.o_R4_FrmSyainMstEdit = o_R4_FrmSyainMstEdit;
    o_R4_FrmSyainMstEdit.o_R4_FrmSyainMstList = o_R4K_R4K.FrmSyainMstList;
    o_R4_FrmSyainMstEdit.load();
});
