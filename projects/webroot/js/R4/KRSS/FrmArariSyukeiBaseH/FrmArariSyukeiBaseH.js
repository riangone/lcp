/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                              担当
 * YYYYMMDD           #ID                          XXXXXX                                           FCSDL
 * 20201118           Bug                          ベースH別粗利集計マスタのjqGridの第一列には、          ZhangBoWen
 *                                                  tab+shiftを押して、blurイベントを行わないです。
 * 20201120           Bug                          ベースH別粗利集計マスタのjqGridはtab+shiftを押して、   ZhangBoWen
 *                                                  車種名には値が割り当てられていません。
 * 20260206           Bug                      パラメータのJSONフォーマット                            YIN
 * * ----------------------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmArariSyukeiBaseH");

KRSS.FrmArariSyukeiBaseH = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.sys_id = "KRSS";
    me.id = "FrmArariSyukeiBaseH";

    me.lastsel = "";
    me.fot = "";
    me.arrSyaData = new Array();
    me.arrInputData = new Array();

    me.arrCdData = new Array();

    me.maxRow = 0;
    me.fotRowId = 0;
    me.firstload = true;

    // ========== 変数 end ==========
    me.option = {
        rowNum: 9999,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 30,
        scroll: 1,
    };
    me.colModel = [
        {
            name: "BASEH_CD",
            label: "ベースHコード",
            index: "BASEH_CD",
            width: 110,
            sortable: false,
            align: "left",
        },
        {
            name: "BASEH_KN",
            label: "ベースH名",
            index: "BASEH_KN",
            width: me.ratio === 1.5 ? 150 : 200,
            sortable: false,
            align: "left",
        },
        {
            name: "SYASYU_CD",
            label: "車種",
            index: "SYASYU_CD",
            width: 50,
            sortable: false,
            editable: true,

            editoptions: {
                maxlength: "3",
                align: "left",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = me.clsComFnc.FncNv(
                                $(e.target).val()
                            );
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.prop("id");
                            var lineTableData = new Array();

                            lineTableData = {
                                BASEH_CD: $(
                                    "#FrmArariSyukeiBaseH_sprList"
                                ).jqGrid("getCell", rowId, "BASEH_CD"),
                                SS_NAME: $(
                                    "#FrmArariSyukeiBaseH_sprList"
                                ).jqGrid("getCell", rowId, "SS_NAME"),
                                SYASYU_CD: newCodeValue,
                            };
                            if (me.fot == 2) {
                            } else {
                                if (newCodeValue == me.arrCdData[rowId - 1]) {
                                } else {
                                    me.fncToriNmSelect(lineTableData, rowId, 1);
                                    me.arrCdData[rowId - 1] = newCodeValue;
                                }
                            }
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab
                            if (e.shiftKey && key == 9) {
                                //20201120 zhangbowen add S
                                var newCodeValue = me.clsComFnc.FncNv(
                                    $(e.target).val()
                                );
                                var row = $(e.target).closest("tr.jqgrow");
                                var rowId = row.prop("id");
                                var lineTableData = new Array();
                                lineTableData = {
                                    BASEH_CD: $(
                                        "#FrmArariSyukeiBaseH_sprList"
                                    ).jqGrid("getCell", rowId, "BASEH_CD"),
                                    SYASYU_CD: newCodeValue,
                                };
                                //20201120 zhangbowen add E
                                if (parseInt(me.lastsel) == 1) {
                                    //20201120 zhangbowen add S
                                    me.fncToriNmSelect(lineTableData, rowId, 1);
                                    //20201120 zhangbowen add E
                                    e.preventDefault();
                                    e.stopPropagation();
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        parseInt(me.lastsel) - 1,
                                        true
                                    );
                                    $("#" + me.lastsel + "_DISP_NO").focus();

                                    //20201120 zhangbowen add S
                                    if (me.fot == 2) {
                                    } else {
                                        if (
                                            newCodeValue ==
                                            me.arrCdData[rowId - 1]
                                        ) {
                                        } else {
                                            me.fncToriNmSelect(
                                                lineTableData,
                                                rowId,
                                                2
                                            );
                                            me.arrCdData[rowId - 1] =
                                                newCodeValue;
                                        }
                                    }
                                    //20201120 zhangbowen add E
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                            if (key == 13) {
                                //enter
                                $("#" + me.lastsel + "_ARARI_RITU").focus();
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 40) {
                                //DOWN
                                var newCodeValue = me.clsComFnc.FncNv(
                                    $(e.target).val()
                                );
                                var row = $(e.target).closest("tr.jqgrow");
                                var rowId = row.prop("id");
                                var lineTableData = new Array();
                                lineTableData = {
                                    BASEH_CD: $(
                                        "#FrmArariSyukeiBaseH_sprList"
                                    ).jqGrid("getCell", rowId, "BASEH_CD"),
                                    SYASYU_CD: newCodeValue,
                                };

                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == me.maxRow) {
                                    return false;
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId =
                                        "#" + selIRow + "_SYASYU_CD";
                                    $(selNextId).focus();
                                }

                                if (me.fot == 2) {
                                } else {
                                    if (
                                        newCodeValue == me.arrCdData[rowId - 1]
                                    ) {
                                    } else {
                                        me.fncToriNmSelect(
                                            lineTableData,
                                            rowId,
                                            2
                                        );
                                        me.arrCdData[rowId - 1] = newCodeValue;
                                    }
                                }
                            }
                            if (key == 38) {
                                //UP
                                var newCodeValue = me.clsComFnc.FncNv(
                                    $(e.target).val()
                                );
                                var row = $(e.target).closest("tr.jqgrow");
                                var rowId = row.prop("id");
                                var lineTableData = new Array();
                                lineTableData = {
                                    BASEH_CD: $(
                                        "#FrmArariSyukeiBaseH_sprList"
                                    ).jqGrid("getCell", rowId, "BASEH_CD"),
                                    SYASYU_CD: newCodeValue,
                                };

                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    //20201118 zhangbowen add S
                                    me.fncToriNmSelect(lineTableData, rowId, 1);
                                    //20201118 zhangbowen add E
                                    return false;
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId =
                                        "#" + selIRow + "_SYASYU_CD";
                                    $(selNextId).focus();
                                }

                                if (me.fot == 2) {
                                } else {
                                    if (
                                        newCodeValue == me.arrCdData[rowId - 1]
                                    ) {
                                    } else {
                                        me.fncToriNmSelect(
                                            lineTableData,
                                            rowId,
                                            2
                                        );
                                        me.arrCdData[rowId - 1] = newCodeValue;
                                    }
                                }
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key >= 65 && key <= 90) {
                                $("#" + me.lastsel + "_SYASYU_CD").val(
                                    $(e.target).val().toLocaleUpperCase()
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SS_NAME",
            label: "車種名",
            index: "SS_NAME",
            width: me.ratio === 1.5 ? 150 : 200,
            sortable: false,
            align: "left",
        },
        {
            name: "ARARI_RITU",
            label: "粗利率",
            index: "ARARI_RITU",
            width: 50,
            sortable: false,
            editable: true,
            align: "right",
            sorttype: "float",

            editoptions: {
                class: "numeric",
                maxlength: "5",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_SYASYU_CD").focus();
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13) {
                                //enter
                                $("#" + me.lastsel + "_UNTIN_RITU").focus();
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == me.maxRow) {
                                    return false;
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId =
                                        "#" + selIRow + "_ARARI_RITU";
                                    $(selNextId).focus();
                                }
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId =
                                        "#" + selIRow + "_ARARI_RITU";
                                    $(selNextId).focus();
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "UNTIN_RITU",
            label: "留保金率",
            index: "UNTIN_RITU",
            width: 70,
            sortable: false,
            align: "right",
            editable: true,
            sorttype: "float",
            editoptions: {
                class: "numeric",
                maxlength: "5",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_ARARI_RITU").focus();
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13) {
                                //enter
                                $("#" + me.lastsel + "_DISP_NO").focus();
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == me.maxRow) {
                                    return false;
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId =
                                        "#" + selIRow + "_UNTIN_RITU";
                                    $(selNextId).focus();
                                }
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId =
                                        "#" + selIRow + "_UNTIN_RITU";
                                    $(selNextId).focus();
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "DISP_NO",
            label: "出力順",
            index: "DISP_NO",
            width: 50,
            sortable: false,
            align: "right",
            editable: true,
            sorttype: "int",
            editoptions: {
                class: "numeric1",
                maxlength: "3",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_UNTIN_RITU").focus();
                                e.preventDefault();
                                e.stopPropagation();
                                return;
                            }
                            if (key == 13 || key == 9) {
                                //enter and tab
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == me.maxRow) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId =
                                        "#" + selIRow + "_SYASYU_CD";
                                    $(selNextId).focus();
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == me.maxRow) {
                                    return false;
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId = "#" + selIRow + "_DISP_NO";
                                    $(selNextId).focus();
                                }
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                } else {
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId = "#" + selIRow + "_DISP_NO";
                                    $(selNextId).focus();
                                }
                            }
                        },
                    },
                ],
            },
        },
    ];

    // ========== コントロール start ==========

    me.controls.push({
        id: ".KRSS.FrmArariSyukeiBaseH.cmdUpdate",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmArariSyukeiBaseH.cmdback",
        type: "button",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.FrmArariSyukeiBaseH_load();
    };
    $(".KRSS.FrmArariSyukeiBaseH.cmdUpdate").click(function () {
        me.cmdUpdate_Click();
    });

    $(".KRSS.FrmArariSyukeiBaseH.cmdback").click(function () {
        me.firstload = true;
        me.FrmArariSyukeiBaseH_load();
    });

    me.FrmArariSyukeiBaseH_load = function () {
        me.subClearForm();
        $("#FrmArariSyukeiBaseH_sprList").jqGrid({
            datatype: "local",
            // jqgridにデータがなし場合、文字表示しない
            emptyRecordRow: false,
            height: me.ratio === 1.5 ? 340 : 470,
            colModel: me.colModel,
            rownumbers: true,
            onSelectRow: function (rowId, _status, e) {
                var focusIndex =
                    typeof e != "undefined"
                        ? e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex
                        : false;
                if (typeof e != "undefined") {
                    if (rowId && rowId !== me.lastsel) {
                        $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                            "saveRow",
                            me.lastsel
                        );
                        me.lastsel = rowId;
                    }
                    if (focusIndex == 1 || focusIndex == 2 || focusIndex == 4) {
                        $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                            "editRow",
                            rowId,
                            true
                        );
                    } else {
                        $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                            "editRow",
                            rowId,
                            {
                                keys: true,
                                focusField: focusIndex,
                            }
                        );
                    }
                } else {
                    if (rowId && rowId !== me.lastsel) {
                        $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                            "saveRow",
                            me.lastsel
                        );
                        me.lastsel = rowId;
                    }
                    $("#FrmArariSyukeiBaseH_sprList").jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: me.firstload ? 3 : focusIndex,
                    });
                    me.firstload = false;
                }
                $(".numeric").numeric({
                    point: true,
                    negative: true,
                });
                $(".numeric1").numeric({
                    decimal: false,
                    negative: true,
                });
            },
        });
        $("#FrmArariSyukeiBaseH_sprList").closest(".ui-jqgrid-bdiv").css({
            "overflow-y": "scroll",
        });
        $("#jqgh_FrmArariSyukeiBaseH_sprList_rn").html("No");
        me.subSpreadReShow(0, 0);
    };

    me.subClearForm = function () {
        $("#FrmArariSyukeiBaseH_sprList").jqGrid("clearGridData");
    };
    //**********************************************************************
    //処 理 名：初期処理
    //関 数 名：subSpreadReShow
    //引    数1：intRow
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    me.subSpreadReShow = function (intRow, flg) {
        var tmpurl = me.sys_id + "/" + me.id + "/subSpreadReShow";
        var data = {};
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data2"].length > 0) {
                    me.arrSyaData = result["data2"];
                }
                if (result["data1"].length > 0) {
                    me.arrInputData = result["data1"];
                    for (key in result["data1"]) {
                        me.arrCdData[key] = result["data1"][key]["SYASYU_CD"];
                    }
                    var mydata = me.arrInputData;

                    me.maxRow = mydata.length + 1;

                    for (var i = intRow; i < mydata.length; i++) {
                        if (
                            mydata[i]["ARARI_RITU"] == null ||
                            mydata[i]["ARARI_RITU"] == 0
                        ) {
                        } else {
                            if (mydata[i]["ARARI_RITU"] < 1) {
                                mydata[i]["ARARI_RITU"] =
                                    "0" + mydata[i]["ARARI_RITU"];
                            }
                        }
                        if (
                            mydata[i]["UNTIN_RITU"] == null ||
                            mydata[i]["UNTIN_RITU"] == 0
                        ) {
                        } else {
                            if (mydata[i]["UNTIN_RITU"] < 1) {
                                mydata[i]["UNTIN_RITU"] =
                                    "0" + mydata[i]["UNTIN_RITU"];
                            }
                        }

                        $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                            "addRowData",
                            i + 1,
                            mydata[i]
                        );
                    }
                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                        "setSelection",
                        "1"
                    );
                } else {
                    $(".KRSS.FrmArariSyukeiBaseH.cmdUpdate").button("disable");
                }
            } else {
                $(".KRSS.FrmArariSyukeiBaseH.cmdUpdate").button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
            if (flg == 1) {
                me.clsComFnc.FncMsgBox("I0008");
            }
        };
        me.ajax.send(tmpurl, data, 0);
    };
    //**********************************************************************
    //処 理 名：更新処理
    //関 数 名：cmdUpdate_Click
    //引    数1：無し
    //戻 り 値：無し
    //処理説明：更新処理
    //**********************************************************************

    me.cmdUpdate_Click = function () {
        $("#FrmArariSyukeiBaseH_sprList").jqGrid("saveRow", me.lastsel);
        var lineIdArr = $("#FrmArariSyukeiBaseH_sprList").jqGrid("getDataIDs");
        var lineArr = new Array();
        var data = me.arrSyaData;
        for (key in lineIdArr) {
            var lineTableData = $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                "getRowData",
                lineIdArr[key]
            );
            var yy = 0;
            if (lineTableData["SYASYU_CD"] == "") {
                yy = 1;
            } else {
                for (var i = 0; i < data.length; i++) {
                    if (data[i]["UCOYA_CD"] == lineTableData["SYASYU_CD"]) {
                        yy = 1;
                    }
                }
            }

            if (yy == 0) {
                var rowId = lineIdArr[key];
                $("#FrmArariSyukeiBaseH_sprList").jqGrid("setSelection", rowId);
                $("#" + rowId + "_SYASYU_CD").focus();
                me.clsComFnc.FncMsgBox("E9999", "「車種コードが不正です」");
                return false;
            }
            if (
                lineTableData["ARARI_RITU"] < 10 &&
                lineTableData["UNTIN_RITU"] < 10
            ) {
                if (
                    lineTableData["ARARI_RITU"] == "" ||
                    lineTableData["ARARI_RITU"] == " "
                ) {
                    lineTableData["ARARI_RITU"] = "''";
                }
                if (
                    lineTableData["UNTIN_RITU"] == "" ||
                    lineTableData["UNTIN_RITU"] == " "
                ) {
                    lineTableData["UNTIN_RITU"] = "''";
                }
                if (
                    lineTableData["DISP_NO"] == "" ||
                    lineTableData["DISP_NO"] == " "
                ) {
                    lineTableData["DISP_NO"] = "''";
                }

                lineArr.push(lineTableData);
            } else {
                if (lineTableData["ARARI_RITU"] >= 10) {
                    var rowId = lineIdArr[key];
                    me.fot = 2;
                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                        "setSelection",
                        rowId
                    );
                    $("#" + rowId + "_ARARI_RITU").focus();
                    me.clsComFnc.FncMsgBox("W0002", "粗利率");
                    me.fot = 0;
                    return false;
                }
                if (lineTableData["UNTIN_RITU"] >= 10) {
                    var rowId = lineIdArr[key];
                    me.fot = 2;
                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                        "setSelection",
                        rowId
                    );
                    $("#" + rowId + "_UNTIN_RITU").focus();
                    me.clsComFnc.FncMsgBox("W0002", "留保金率");
                    me.fot = 0;
                    return false;
                }
            }
        }

        var tmpurl = me.sys_id + "/" + me.id + "/cmdUpdate_Click";
        var data = {
            lineArr: lineArr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                me.subClearForm();
                me.subSpreadReShow(0, 1);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(tmpurl, JSON.stringify(data), 0);
    };

    //**********************************************************************
    //処 理 名：車種マスタを検索
    //関 数 名：fncToriNmSelect
    //引    数1：lineTableData
    //引    数2：rowId
    //引    数3：fot
    //戻 り 値：無し
    //処理説明：車種マスタを検索
    //**********************************************************************
    me.fncToriNmSelect = function (lineTableData, rowId, fot) {
        if (lineTableData["SYASYU_CD"] == "") {
            $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                "setCell",
                rowId,
                "SS_NAME",
                " "
            );
            if (fot == 1) {
                $("#" + rowId + "_" + "ARARI_RITU").val("");
                $("#" + rowId + "_" + "UNTIN_RITU").val("");
                $("#" + rowId + "_" + "DISP_NO").val("");
            } else {
                $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                    "setCell",
                    rowId,
                    "ARARI_RITU",
                    " "
                );
                $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                    "setCell",
                    rowId,
                    "UNTIN_RITU",
                    " "
                );
                $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                    "setCell",
                    rowId,
                    "DISP_NO",
                    " "
                );
            }
        } else {
            var tt = 0;
            var data = me.arrSyaData;
            for (var i = 0; i < data.length; i++) {
                if (
                    me.arrSyaData[i]["UCOYA_CD"] == lineTableData["SYASYU_CD"]
                ) {
                    if (
                        me.arrInputData[rowId - 1]["SYASYU_CD"] ==
                        lineTableData["SYASYU_CD"]
                    ) {
                        $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                            "setCell",
                            rowId,
                            "SS_NAME",
                            me.arrSyaData[i]["SS_NAME"]
                        );
                        if (fot == 1) {
                            $("#" + rowId + "_" + "ARARI_RITU").val(
                                me.arrInputData[rowId - 1]["ARARI_RITU"]
                            );
                            $("#" + rowId + "_" + "UNTIN_RITU").val(
                                me.arrInputData[rowId - 1]["UNTIN_RITU"]
                            );
                            $("#" + rowId + "_" + "DISP_NO").val(
                                me.arrInputData[rowId - 1]["DISP_NO"]
                            );
                        } else {
                            $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                "setCell",
                                rowId,
                                "ARARI_RITU",
                                me.arrInputData[rowId - 1]["ARARI_RITU"]
                            );
                            $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                "setCell",
                                rowId,
                                "UNTIN_RITU",
                                me.arrInputData[rowId - 1]["UNTIN_RITU"]
                            );
                            $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                "setCell",
                                rowId,
                                "DISP_NO",
                                me.arrInputData[rowId - 1]["DISP_NO"]
                            );
                        }
                    } else {
                        $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                            "setCell",
                            rowId,
                            "SS_NAME",
                            me.arrSyaData[i]["SS_NAME"]
                        );
                        if (fot == 1) {
                            $("#" + rowId + "_" + "ARARI_RITU").val("");
                            $("#" + rowId + "_" + "UNTIN_RITU").val("");
                            $("#" + rowId + "_" + "DISP_NO").val("");
                        } else {
                            $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                "setCell",
                                rowId,
                                "ARARI_RITU",
                                " "
                            );
                            $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                "setCell",
                                rowId,
                                "UNTIN_RITU",
                                " "
                            );
                            $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                                "setCell",
                                rowId,
                                "DISP_NO",
                                " "
                            );
                        }
                    }
                    tt = 1;
                }
            }
            if (tt == 0) {
                $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                    "setCell",
                    rowId,
                    "SS_NAME",
                    " "
                );
                if (fot == 1) {
                    $("#" + rowId + "_" + "ARARI_RITU").val("");
                    $("#" + rowId + "_" + "UNTIN_RITU").val("");
                    $("#" + rowId + "_" + "DISP_NO").val("");
                } else {
                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                        "setCell",
                        rowId,
                        "ARARI_RITU",
                        " "
                    );
                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                        "setCell",
                        rowId,
                        "UNTIN_RITU",
                        " "
                    );
                    $("#FrmArariSyukeiBaseH_sprList").jqGrid(
                        "setCell",
                        rowId,
                        "DISP_NO",
                        " "
                    );
                }
            }
        }
    };

    return me;
};

$(function () {
    var o_KRSS_FrmArariSyukeiBaseH = new KRSS.FrmArariSyukeiBaseH();
    o_KRSS_FrmArariSyukeiBaseH.load();
    o_KRSS_KRSS_FrmArariSyukeiBaseH = o_KRSS_FrmArariSyukeiBaseH;
});
