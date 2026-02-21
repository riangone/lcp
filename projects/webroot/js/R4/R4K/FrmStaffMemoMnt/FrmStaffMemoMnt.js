/**
 * 説明：
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20150922                  #2164                   BUG                         LI
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmStaffMemoMnt");

R4.FrmStaffMemoMnt = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    // var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmStaffMemoMnt";
    me.sys_id = "R4K";
    me.url = "";
    //-- 20150922 li INS S.
    me.grid_id = "#FrmStaffMemoMnt_sprMeisai";
    //-- 20150922 li INS E.
    me.data = new Array();
    me.lastsel = "";
    me.col = {
        MEMO: "",
        FONT_SIZE: "",
        FONT_TYPE: "",
        CREATE_DATE: "",
    };
    me.columns = {
        MEMO: "",
        FONT_SIZE: "",
        FONT_TYPE: "",
        CREATE_DATE: "",
    };
    me.radFlag = "radSinsya";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmStaffMemoMnt.cmdAction",
        type: "button",
        handle: "",
    });

    me.colModel = [
        {
            name: "MEMO",
            label: "メモ",
            index: "MEMO",
            //-- 20150922 li UPD S.
            // width : 690,
            width: 800,
            //-- 20150922 li UPD E.
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "200",
                //-- 20150922 li INS S.
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 229) {
                                return false;
                            }
                            if (
                                !me.setColSelection(
                                    e,
                                    key,
                                    "FONT_SIZE",
                                    "FONT_TYPE",
                                    true,
                                    false
                                )
                            ) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "focus",
                        fn: function () {
                            me.editDataFlg = true;
                        },
                    },
                ],
                //-- 20150922 li INS E.
            },
        },
        {
            name: "FONT_SIZE",
            label: "サイズ",
            index: "FONT_SIZE",
            width: 100,
            align: "left",
            sortable: false,
            editable: true,
            edittype: "select",
            editoptions: {
                //-- 20150922 li INS S.
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 229) {
                                return false;
                            }
                            if (
                                !me.setColSelection(
                                    e,
                                    key,
                                    "FONT_TYPE",
                                    "MEMO",
                                    false,
                                    false
                                )
                            ) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "focus",
                        fn: function () {
                            me.editDataFlg = true;
                        },
                    },
                ],
                //-- 20150922 li INS E.
                class: "optionWidth1",
                value: {
                    0: "",
                    1: "7pt",
                    2: "9pt",
                },
            },
        },
        {
            name: "FONT_TYPE",
            label: "タイプ",
            index: "FONT_TYPE",
            width: 100,
            align: "left",
            editable: true,
            sortable: false,
            edittype: "select",
            editoptions: {
                //-- 20150922 li INS S.
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 229) {
                                return false;
                            }
                            if (
                                !me.setColSelection(
                                    e,
                                    key,
                                    "MEMO",
                                    "FONT_SIZE",
                                    false,
                                    true
                                )
                            ) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "focus",
                        fn: function () {
                            me.editDataFlg = true;
                        },
                    },
                ],
                //-- 20150922 li INS E.
                class: "optionWidth2",
                value: {
                    0: "",
                    1: "太字",
                },
            },
        },
        {
            name: "CREATE_DATE",
            label: "CREATE_DATE",
            index: "CREATE_DATE",
            width: 100,
            align: "left",
            sortable: false,
            hidden: true,
        },
    ];

    $("#FrmStaffMemoMnt_sprMeisai").jqGrid({
        datatype: "local",
        //-- 20150922 li UPD S.
        //height : 390,
        height: me.ratio === 1.5 ? 270 : 335,
        //-- 20150922 li UPD E.
        colModel: me.colModel,
        rownumbers: true,
        emptyRecordRow: false,
        //-- 20150922 li UPD S.
        //rownumWidth : 30,
        rownumWidth: 15,
        //-- 20150922 li UPD E.
        onSelectRow: function (rowId) {
            if (rowId && rowId !== me.lastsel) {
                $("#FrmStaffMemoMnt_sprMeisai").jqGrid("saveRow", me.lastsel);
                me.lastsel = rowId;
            }
            $("#FrmStaffMemoMnt_sprMeisai").jqGrid("editRow", rowId, true);
        },
    });
    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    me.init_control = function () {
        base_init_control();

        $(".FrmStaffMemoMnt.label2").hide();

        me.FrmOptionInput_Load();
    };

    $(".FrmStaffMemoMnt.radSinsya").click(function () {
        me.radFlag = "radSinsya";
        $(".FrmStaffMemoMnt.lblMihon").css("font-size", "8.25pt");
        $(".FrmStaffMemoMnt.cboFontSize").empty();
        $("<option></option>")
            .val("0")
            .text("")
            .appendTo(".FrmStaffMemoMnt.cboFontSize");
        $("<option></option>")
            .val("1")
            .text("7pt")
            .appendTo(".FrmStaffMemoMnt.cboFontSize");
        $("<option></option>")
            .val("2")
            .text("9pt")
            .appendTo(".FrmStaffMemoMnt.cboFontSize");

        $(".FrmStaffMemoMnt.label1").show();
        $(".FrmStaffMemoMnt.label2").hide();
        $("#FrmStaffMemoMnt_sprMeisai").jqGrid("clearGridData");
        $("#FrmStaffMemoMnt_sprMeisai").setColProp("FONT_SIZE", {
            editoptions: {
                value: {
                    0: "",
                    1: "7pt",
                    2: "9pt",
                },
            },
        });
        me.fncStaffMemoSelect();
    });

    $(".FrmStaffMemoMnt.radChuko").click(function () {
        me.radFlag = "radChuko";

        $(".FrmStaffMemoMnt.lblMihon").css("font-size", "9pt");

        $(".FrmStaffMemoMnt.cboFontSize").empty();

        $("<option></option>")
            .val("0")
            .text("")
            .appendTo(".FrmStaffMemoMnt.cboFontSize");
        $("<option></option>")
            .val("1")
            .text("8pt")
            .appendTo(".FrmStaffMemoMnt.cboFontSize");
        $("<option></option>")
            .val("2")
            .text("11pt")
            .appendTo(".FrmStaffMemoMnt.cboFontSize");

        $(".FrmStaffMemoMnt.label2").show();
        $(".FrmStaffMemoMnt.label1").hide();
        $("#FrmStaffMemoMnt_sprMeisai").jqGrid("clearGridData");

        $("#FrmStaffMemoMnt_sprMeisai").setColProp("FONT_SIZE", {
            editoptions: {
                value: {
                    0: "",
                    1: "8pt",
                    2: "11pt",
                },
            },
        });

        me.colModel[1]["editoptions"]["value"][1] = "8pt";
        me.colModel[1]["editoptions"]["value"][2] = "11pt";
        me.fncStaffMemoSelect();
    });

    $(".FrmStaffMemoMnt.cboFontSize").change(function () {
        var val = $(".FrmStaffMemoMnt.cboFontSize").val().trimEnd();
        if (me.radFlag == "radSinsya") {
            switch (val) {
                case "0":
                    $(".FrmStaffMemoMnt.lblMihon").css("font-size", "8.25pt");
                    break;
                case "1":
                    $(".FrmStaffMemoMnt.lblMihon").css("font-size", "6.75pt");
                    break;
                case "2":
                    $(".FrmStaffMemoMnt.lblMihon").css("font-size", "9pt");
                    break;
                default:
                    break;
            }
        } else {
            switch (val) {
                case "0":
                    $(".FrmStaffMemoMnt.lblMihon").css("font-size", "9pt");
                    break;
                case "1":
                    $(".FrmStaffMemoMnt.lblMihon").css("font-size", "8.25pt");
                    break;
                case "2":
                    $(".FrmStaffMemoMnt.lblMihon").css("font-size", "11.25pt");
                    break;
                default:
                    break;
            }
        }
    });

    $(".FrmStaffMemoMnt.cboFontType").change(function () {
        var cboFontType = $(".FrmStaffMemoMnt.cboFontType").val().trimEnd();
        if (cboFontType == "0") {
            $(".FrmStaffMemoMnt.lblMihon").css("font-weight", "normal");
        } else {
            $(".FrmStaffMemoMnt.lblMihon").css("font-weight", "bold");
        }
    });

    $(".FrmStaffMemoMnt.cmdAction").click(function () {
        $("#FrmStaffMemoMnt_sprMeisai").jqGrid("saveRow", me.lastsel);
        if (me.fncInputChk() == false) {
            return;
        }
        clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteInsertStaffMemo;
        // clsComFnc.MsgBoxBtnFnc.No = me.fncCancel;

        clsComFnc.FncMsgBox("QY010");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.FrmOptionInput_Load = function () {
        me.fncStaffMemoSelect();
    };

    me.fncCancel = function () {
        $("#FrmStaffMemoMnt_sprMeisai").jqGrid("setSelection", 21, true);
    };

    me.fncStaffMemoSelect = function () {
        me.lastsel = "21";
        me.url = me.sys_id + "/" + me.id + "/fncStaffMemoSelect";
        if ($(".FrmStaffMemoMnt.radSinsya").prop("checked") == true) {
            //判断是否已经打勾
            var val = "1";
        } else {
            var val = "2";
        }

        var arr = {
            KB: val,
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            // console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            me.sprData = result["data"];
            // var arr = Array();

            for (key in result["data"]) {
                me.col["MEMO"] = clsComFnc.FncNv(result["data"][key]["MEMO"]);
                me.col["FONT_SIZE"] = result["data"][key]["FONT_SIZE"];
                me.col["CREATE_DATE"] = result["data"][key]["CREATE_DATE"];

                if (result["data"][key]["FONT_TYPE"] == "1") {
                    me.col["FONT_TYPE"] = "太字";
                } else {
                    me.col["FONT_TYPE"] = "";
                }

                $("#FrmStaffMemoMnt_sprMeisai").jqGrid(
                    "addRowData",
                    parseInt(key) + 1,
                    me.col
                );
            }
            var objs = result["data"].length;
            for (i = parseInt(objs) + 1; i <= 21; i++) {
                $("#FrmStaffMemoMnt_sprMeisai").jqGrid(
                    "addRowData",
                    i,
                    me.columns
                );
            }
            $("#21").hide();
            $("#FrmStaffMemoMnt_sprMeisai").jqGrid("setSelection", 21, true);
        };

        ajax.send(me.url, me.data, 0);
    };

    me.getJqData = function () {
        var arr = new Array();
        me.arry = new Array();
        var data = $("#FrmStaffMemoMnt_sprMeisai").jqGrid("getDataIDs");
        var i = -1;
        for (key in data) {
            var tableData = $("#FrmStaffMemoMnt_sprMeisai").jqGrid(
                "getRowData",
                data[key]
            );
            if (
                tableData["MEMO"].toString().trimEnd() != "" ||
                tableData["FONT_SIZE"].toString().trimEnd() != "" ||
                tableData["FONT_TYPE"].toString().trimEnd() != ""
            ) {
                i = key;
            }
        }

        for (key in data) {
            if (parseInt(key) <= parseInt(i)) {
                var tableData = $("#FrmStaffMemoMnt_sprMeisai").jqGrid(
                    "getRowData",
                    data[key]
                );
                arr.push(tableData);
                me.arry.push(key);
            }
        }

        return arr;
    };

    me.fncInputChk = function () {
        var blnInputFlg = false;
        var intRtn = "";
        me.arrSave = me.getJqData();
        // console.log(me.arrSave);
        for (key in me.arrSave) {
            for (key1 in me.arrSave[key]) {
                me.arrSave[key][key1] = me.arrSave[key][key1].trimEnd();
                switch (key1) {
                    case "MEMO":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arrSave[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NONE,
                            me.colModel[0]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = me.arry[key];
                            // console.log(me.rowNum);
                            me.colNum = "MEMO";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[0]["label"]
                            );
                            return false;
                        }
                }
            }

            if (
                me.arrSave[key]["MEMO"].trimEnd() == "" &&
                (me.arrSave[key]["FONT_SIZE"].trimEnd() != "" ||
                    me.arrSave[key]["FONT_TYPE"].trimEnd() != "")
            ) {
                me.rowNum = me.arry[key];
                me.colNum = "MEMO";
                me.focus();
                clsComFnc.FncMsgBox(
                    "W9999",
                    "書式を指定した場合、メモは必ず入力してください！"
                );
                return false;
            }
            blnInputFlg = true;
        }

        if (!blnInputFlg) {
            $("#FrmStaffMemoMnt_sprMeisai").jqGrid("setSelection", 21, true);
            clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }

        return true;
    };

    me.editceil = function () {
        $("#FrmStaffMemoMnt_sprMeisai").jqGrid("editRow", me.lastsel, true);
    };

    me.focus = function () {
        me.editceil();
        var row = parseInt(me.rowNum) + 1;
        if (me.lastsel != row) {
            $("#FrmStaffMemoMnt_sprMeisai").jqGrid("saveRow", me.lastsel);
            $("#FrmStaffMemoMnt_sprMeisai").jqGrid("setSelection", row, true);
            $("#FrmStaffMemoMnt_sprMeisai").jqGrid("editRow", row, true);

            var ceil = parseInt(me.rowNum) + 1 + "_" + me.colNum;
            clsComFnc.ObjFocus = $("#" + ceil);
            clsComFnc.ObjSelect = $("#" + ceil);
        } else {
            var ceil = parseInt(me.rowNum) + 1 + "_" + me.colNum;
            clsComFnc.ObjFocus = $("#" + ceil);
            clsComFnc.ObjSelect = $("#" + ceil);
        }
    };

    me.fncDeleteInsertStaffMemo = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteInsertStaffMemo";
        if ($(".FrmStaffMemoMnt.radSinsya").prop("checked") == true) {
            //判断是否已经打勾
            var val = "1";
        } else {
            var val = "2";
        }

        var arr = {
            KB: val,
            jqData: me.arrSave,
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            // console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if ($(".FrmStaffMemoMnt.radSinsya").prop("checked") == true) {
                //判断是否已经打勾
                clsComFnc.ObjFocus = $(".FrmStaffMemoMnt.radSinsya");
            } else {
                clsComFnc.ObjFocus = $(".FrmStaffMemoMnt.radChuko");
            }
            $("#FrmStaffMemoMnt_sprMeisai").jqGrid("setSelection", 21, true);
            clsComFnc.FncMsgBox("I0008");
        };
        ajax.send(me.url, me.data, 0);
    };

    //-- 20150922 li INS S.
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
                //alert(me.lastsel);
                if (firstCol == true && parseInt(me.lastsel) == 1) {
                    return false;
                } else if (firstCol == true && parseInt(me.lastsel) > 1) {
                    $(me.grid_id).jqGrid("saveRow", me.lastsel);
                    $(me.grid_id).jqGrid(
                        "setSelection",
                        parseInt(me.lastsel) - 1,
                        true
                    );
                }
                setTimeout(() => {
                    $("#" + me.lastsel + "_" + colPreviousName).trigger(
                        "focus"
                    );
                    $("#" + me.lastsel + "_" + colPreviousName).select();
                }, 0);
                return false;
            }

            //Tab
            if (key == 9) {
                //alert(GridRecords);
                if (lastCol == true && me.lastsel == GridRecords - 1) {
                    return false;
                } else if (lastCol == true && me.lastsel < GridRecords - 1) {
                    $(me.grid_id).jqGrid("saveRow", me.lastsel);
                    $(me.grid_id).jqGrid(
                        "setSelection",
                        parseInt(me.lastsel) + 1
                    );
                }
                $("#" + me.lastsel + "_" + colNextName).trigger("focus");
                $("#" + me.lastsel + "_" + colNextName).select();
                return false;
            }

            // if (me.lastsel == GridRecords - 1) {
            //     if (
            //         (key >= 65 && key <= 90) ||
            //         (key >= 48 && key <= 57) ||
            //         (key >= 96 && key <= 105) ||
            //         (key >= 186 && key <= 222) ||
            //         (key >= 109 && key <= 111) ||
            //         key == 106 ||
            //         key == 107
            //     ) {
            //         me.keyupAddrow();
            //     }
            // }
            if (key == 222) {
                return false;
            }
            return true;
        }
    };
    //-- 20150922 li INS E.

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmStaffMemoMnt = new R4.FrmStaffMemoMnt();
    o_R4_FrmStaffMemoMnt.load();
});
