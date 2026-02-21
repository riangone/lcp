/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                      内容                                       担当
 * YYYYMMDD            #ID                             XXXXXX                                    GSSDL
 * 20201117            表示倍率：125％の場合は、「Chrome」でjqGridの見出しが間違っています。       lqs
 * 20220922            #車両業務システム_仕様変更対応(H0009)		  架装明細入力　仕様変更対応           	 YIN
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmOptionInput");

R4.FrmOptionInput = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();
    me.lastsel = "";
    me.ErrorRow = "";
    me.lngTeika = 0;
    me.rowNum = "";
    me.colNum = "";
    me.arry = "";
    me.FrmList = null;
    //20180521 lqs INS S
    me.oldGYOUSYA_CD = "";
    //20180521 lqs INS E
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmOptionInput";
    me.sys_id = "R4G";
    me.data = "";
    me.strExFlg = "";
    me.arr1 = new Array();
    me.columns = {
        MEDALCD: "",
        BUHINNM: "",
        BIKOU: "",
        TEIKA: "",
        SUURYOU: "",
        BUHIN_SYANAI_GEN_RITU: "",
        BUHIN_SYANAI_GEN: "",
        BUHIN_SYANAI_ZITU_RITU: "",
        BUHIN_SYANAI_ZITU: "",
        GYOUSYA_CD: "",
        GYOUSYA_NM: "",
        KAZEIKBN: "",
        GAICYU_GEN_RITU: "",
        GAICYU_GEN: "",
        GAICYU_ZITU_RITU: "",
        GAICYU_ZITU: "",
    };

    me.colModel = [
        {
            name: "MEDALCD",
            label: "メダルコード",
            index: "MEDALCD",
            width: 95,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "20",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_MEDALCD";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_MEDALCD";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUHINNM",
            label: "部品名称（漢字）",
            index: "BUHINNM",
            width: 120,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "80",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_BUHINNM";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_BUHINNM";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BIKOU",
            label: "備考",
            index: "BIKOU",
            width: 58,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "24",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_BIKOU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_BIKOU";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "TEIKA",
            label: "定価",
            index: "TEIKA",
            width: 100,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "7",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = $.trim($(e.target).val());
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.attr("id");
                            var idGet =
                                "#" + rowId + "_" + "BUHIN_SYANAI_GEN_RITU";
                            var vaGet = $.trim($(idGet).val());

                            if (
                                vaGet != null &&
                                vaGet != "" &&
                                newCodeValue != ""
                            ) {
                                var totalVal = Math.round(
                                    (newCodeValue * vaGet) / 100
                                );
                                var idSet =
                                    "#" + rowId + "_" + "BUHIN_SYANAI_GEN";
                                $(idSet).val(totalVal);
                            }

                            var idGet =
                                "#" + rowId + "_" + "BUHIN_SYANAI_ZITU_RITU";
                            var vaGet = $.trim($(idGet).val());
                            if (
                                vaGet != null &&
                                vaGet != "" &&
                                newCodeValue != ""
                            ) {
                                var totalVal = Math.round(
                                    (newCodeValue * vaGet) / 100
                                );
                                var idSet =
                                    "#" + rowId + "_" + "BUHIN_SYANAI_ZITU";
                                $(idSet).val(totalVal);
                            }
                            var idGet = "#" + rowId + "_" + "GAICYU_GEN_RITU";
                            var vaGet = $.trim($(idGet).val());
                            if (
                                vaGet != null &&
                                vaGet != "" &&
                                newCodeValue != ""
                            ) {
                                var totalVal = Math.round(
                                    (newCodeValue * vaGet) / 100
                                );
                                var idSet = "#" + rowId + "_" + "GAICYU_GEN";
                                $(idSet).val(totalVal);
                            }

                            var idGet = "#" + rowId + "_" + "GAICYU_ZITU_RITU";
                            var vaGet = $.trim($(idGet).val());
                            if (
                                vaGet != null &&
                                vaGet != "" &&
                                newCodeValue != ""
                            ) {
                                var totalVal = Math.round(
                                    (newCodeValue * vaGet) / 100
                                );
                                var idSet = "#" + rowId + "_" + "GAICYU_ZITU";
                                $(idSet).val(totalVal);
                            }
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_TEIKA";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_TEIKA";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
        },
        {
            name: "SUURYOU",
            label: "数量",
            index: "SUURYOU",
            width: 33,
            align: "right",
            sortable: false,
            editable: true,
            editrules: {
                integer: true,
            },
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            editoptions: {
                class: "numeric",
                maxlength: "2",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_SUURYOU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_SUURYOU";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUHIN_SYANAI_GEN_RITU",
            label: "％",
            index: "BUHIN_SYANAI_GEN_RITU",
            width: 30,
            sortable: false,
            align: "right",
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = $.trim($(e.target).val());
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.attr("id");
                            var idGet = "#" + rowId + "_" + "TEIKA";
                            var vaGet = $.trim($(idGet).val());
                            var totalVal = Math.round(
                                (newCodeValue * vaGet) / 100
                            );
                            var idSet = "#" + rowId + "_" + "BUHIN_SYANAI_GEN";
                            if (
                                vaGet == "" ||
                                vaGet == null ||
                                newCodeValue == ""
                            ) {
                                $(idSet).val("");
                            } else {
                                $(idSet).val(totalVal);
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (event) {
                            if (
                                (event.keyCode >= 96 && event.keyCode <= 105) ||
                                (event.keyCode >= 48 && event.keyCode <= 57)
                            ) {
                                // this.value = this.value.replace(/\D/g, '');
                                var sub = me.limit(this.value);
                                this.value = sub;
                            }
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_GEN_RITU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_GEN_RITU";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUHIN_SYANAI_GEN",
            label: "金額",
            index: "BUHIN_SYANAI_GEN",
            width: 100,
            align: "right",
            editable: true,
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            editoptions: {
                class: "numeric",
                maxlength: "7",
                //20140114 fuxiaolin add start
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            //console.log(e);
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_GEN";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_GEN";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
                //20140114 fuxiaolin add end
            },
        },
        {
            name: "BUHIN_SYANAI_ZITU_RITU",
            label: "％",
            index: "BUHIN_SYANAI_ZITU_RITU",
            width: 30,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = $.trim($(e.target).val());
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.attr("id");
                            var idGet = "#" + rowId + "_" + "TEIKA";
                            var vaGet = $.trim($(idGet).val());
                            var totalVal = parseInt(
                                (newCodeValue * vaGet) / 100
                            );
                            var idSet = "#" + rowId + "_" + "BUHIN_SYANAI_ZITU";
                            if (
                                vaGet == "" ||
                                vaGet == null ||
                                newCodeValue == ""
                            ) {
                                $(idSet).val("");
                            } else {
                                $(idSet).val(totalVal);
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (event) {
                            if (
                                (event.keyCode >= 96 && event.keyCode <= 105) ||
                                (event.keyCode >= 48 && event.keyCode <= 57)
                            ) {
                                var sub = me.limit(this.value);
                                this.value = sub;
                            }
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_ZITU_RITU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_ZITU_RITU";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUHIN_SYANAI_ZITU",
            label: "金額",
            index: "BUHIN_SYANAI_ZITU",
            width: 100,
            align: "right",
            editable: true,
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            editoptions: {
                class: "numeric",
                maxlength: "7",
                //20140114 fuxiaolin add start
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            //console.log(e);
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_ZITU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_ZITU";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
                //20140114 fuxiaolin add end
            },
        },
        {
            name: "GYOUSYA_CD",
            label: "取引先<br>コード",
            index: "GYOUSYA_CD",
            width: 50,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "5",
                dataEvents: [
                    //20180521 lqs INS S
                    {
                        type: "focus",
                        fn: function (e) {
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.attr("id");
                            me.oldGYOUSYA_CD = $(
                                "#" + rowId + "_" + "GYOUSYA_CD"
                            ).val();
                        },
                    },
                    //20180521 lqs INS E
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = clsComFnc.FncNv(
                                $(e.target).val()
                            );
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.attr("id");
                            var idGet = "#" + rowId + "_" + "GYOUSYA_NM";

                            //20180517 lqs INS S
                            if (newCodeValue != me.oldGYOUSYA_CD) {
                                me.fncTransferItem(newCodeValue, rowId, e);
                            }
                            //20180517 lqs INS E
                            me.fncToriNmSelect(newCodeValue, idGet, rowId, e);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_GYOUSYA_CD";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_GYOUSYA_CD";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "GYOUSYA_NM",
            label: "取引先名",
            index: "GYOUSYA_NM",
            // 20201117 lqs upd S
            // width : 100,
            width: 90,
            // 20201117 lqs upd E
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "30",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_GYOUSYA_NM";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_GYOUSYA_NM";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAZEIKBN",
            // 20201117 lqs upd S
            // label : '非：1課：2',
            label: "非：1<br>課：2",
            // 20201117 lqs upd E
            index: "KAZEIKBN",
            // 20201117 lqs upd S
            // width : 35,
            width: 45,
            // 20201117 lqs upd E
            editable: true,
            editoptions: {
                maxlength: "1",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_KAZEIKBN";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId = "#" + selIRow + "_KAZEIKBN";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "GAICYU_GEN_RITU",
            label: "％",
            index: "GAICYU_GEN_RITU",
            width: 30,
            sortable: false,
            align: "right",
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = $.trim($(e.target).val());
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.attr("id");
                            var idGet = "#" + rowId + "_" + "TEIKA";
                            var vaGet = $.trim($(idGet).val());
                            var totalVal = parseInt(
                                (newCodeValue * vaGet) / 100
                            );
                            var idSet = "#" + rowId + "_" + "GAICYU_GEN";
                            if (
                                vaGet == "" ||
                                vaGet == null ||
                                newCodeValue == ""
                            ) {
                                $(idSet).val("");
                            } else {
                                $(idSet).val(totalVal);
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (event) {
                            if (
                                (event.keyCode >= 96 && event.keyCode <= 105) ||
                                (event.keyCode >= 48 && event.keyCode <= 57)
                            ) {
                                var sub = me.limit(this.value);
                                this.value = sub;
                            }
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_GAICYU_GEN_RITU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_GAICYU_GEN_RITU";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "GAICYU_GEN",
            label: "金額",
            index: "GAICYU_GEN",
            width: 100,
            align: "right",
            editable: true,
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            editoptions: {
                maxlength: "7",
                class: "numeric",
                //20140114 fuxiaolin add start
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            //console.log(e);
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                if (selIRow == 101) {
                                    return false;
                                } else {
                                    var selIRow = parseInt(me.lastsel) + 1;
                                    $("#FrmOptionInput_sprMeisai").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmOptionInput_sprMeisai").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                    var selNextId =
                                        "#" + selIRow + "_GAICYU_GEN";
                                    $(selNextId).trigger("focus");
                                }
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                } else {
                                    $("#FrmOptionInput_sprMeisai").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmOptionInput_sprMeisai").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                    var selNextId =
                                        "#" + selIRow + "_GAICYU_GEN";
                                    $(selNextId).trigger("focus");
                                }
                            }
                        },
                    },
                ],
                //20140114 fuxiaolin add end
            },
        },
        {
            name: "GAICYU_ZITU_RITU",
            label: "％",
            index: "GAICYU_ZITU_RITU",
            width: 30,
            align: "right",
            editable: true,
            sortable: false,
            editoptions: {
                class: "numeric",
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = $.trim($(e.target).val());
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.attr("id");
                            var idGet = "#" + rowId + "_" + "TEIKA";
                            var vaGet = $.trim($(idGet).val());
                            var totalVal = parseInt(
                                (newCodeValue * vaGet) / 100
                            );
                            var idSet = "#" + rowId + "_" + "GAICYU_ZITU";
                            if (
                                vaGet == "" ||
                                vaGet == null ||
                                newCodeValue == ""
                            ) {
                                $(idSet).val("");
                            } else {
                                $(idSet).val(totalVal);
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (event) {
                            // this.value = this.value.replace(/\D/g, '');
                            if (
                                (event.keyCode >= 96 && event.keyCode <= 105) ||
                                (event.keyCode >= 48 && event.keyCode <= 57)
                            ) {
                                var sub = me.limit(this.value);
                                this.value = sub;
                            }
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_GAICYU_ZITU_RITU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmOptionInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                var selNextId =
                                    "#" + selIRow + "_GAICYU_ZITU_RITU";
                                $(selNextId).trigger("focus");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "GAICYU_ZITU",
            label: "金額",
            index: "GAICYU_ZITU",
            width: 100,
            align: "right",
            editable: true,
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            editoptions: {
                class: "numeric",
                maxlength: "7",
                //20140114 fuxiaolin add start
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            //console.log(e);
                            var key = e.charCode || e.keyCode;
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                } else {
                                    $("#FrmOptionInput_sprMeisai").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmOptionInput_sprMeisai").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                    var selNextId =
                                        "#" + selIRow + "_GAICYU_ZITU";
                                    $(selNextId).trigger("focus");
                                }
                                // setTimeout(a, 100);
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                } else {
                                    $("#FrmOptionInput_sprMeisai").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmOptionInput_sprMeisai").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    // $('#FrmOptionInput_sprMeisai').jqGrid('editRow', selIRow, true);
                                    var selNextId =
                                        "#" + selIRow + "_GAICYU_ZITU";
                                    $(selNextId).trigger("focus");
                                }
                            }
                        },
                    },
                ],
                //20140114 fuxiaolin add end
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmOptionInput.cmdInsert",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmOptionInput.cmdUpdate",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmOptionInput.cmdAction",
        type: "button",
        handle: "",
        enable: "false",
    });

    me.controls.push({
        id: ".FrmOptionInput.cmdSpecial",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmOptionInput.cmdBack",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmOptionInput.cmdInsert").click(function () {
        // console.log("1");
        me.cmdInsert_Click();
    });

    $(".FrmOptionInput.cmdUpdate").click(function () {
        $("#jqgh_FrmOptionInput_sprMeisai_rn").html("");
        $("#jqgh_FrmOptionInput_sprMeisai_rn").html("削除");

        $("#FrmOptionInput_sprMeisai").closest(".ui-jqgrid").unblock();

        $(".FrmOptionInput.cmdInsert").button("enable");
        $(".FrmOptionInput.cmdAction").button("enable");
        me.strExFlg = "";
    });

    $(".FrmOptionInput.cmdAction").click(function () {
        me.cmdAction_Click();
    });

    $(".FrmOptionInput.cmdSpecial").click(function () {
        var frmId = "FrmSpecialInput";
        var url = me.sys_id + "/" + frmId + "/index";

        ajax.receive = function (result) {
            $("#FrmListSpecialDialogDiv").html(result);
            $("#FrmListOptionDialogDiv").html("");
            $("#FrmListOptionDialogDiv").dialog("close");
            $("#FrmListSpecialDialogDiv").dialog(
                "option",
                "title",
                "新車：特別仕様入力"
            );
            $("#FrmListSpecialDialogDiv").dialog("open");
        };
        ajax.send(url, "", 0);
    });

    $(".FrmOptionInput.cmdBack").click(function () {
        $("#FrmListOptionDialogDiv").html("");
        $("#FrmListOptionDialogDiv").dialog("close");
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =useColSpanStyle : true,

    // ==========
    $("#FrmOptionInput_sprMeisai").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,

        // 20201117 lqs upd S
        // height : 310,
        height: me.ratio === 1.5 ? 250 : 309,
        // 20201117 lqs upd E
        rownumbers: true,
        colModel: me.colModel,
        onSelectRow: function (rowId, status, e) {
            if (typeof e != "undefined") {
                var cellIndex =
                    e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;
                if (cellIndex != 0) {
                    if (rowId && rowId !== me.lastsel) {
                        $("#FrmOptionInput_sprMeisai").jqGrid(
                            "editRow",
                            rowId,
                            {
                                keys: true,
                                focusField: cellIndex,
                            }
                        );
                        $(".numeric").numeric({
                            decimal: false,
                            negative: false,
                        });
                        $("#FrmOptionInput_sprMeisai").jqGrid(
                            "saveRow",
                            me.lastsel
                        );
                        me.lastsel = rowId;
                    }
                } else {
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "saveRow",
                        me.lastsel
                    );
                    clsComFnc.MsgBoxBtnFnc.Yes = me.del;
                    clsComFnc.MsgBoxBtnFnc.No = me.resetsel;
                    clsComFnc.MessageBox(
                        "削除します。よろしいですか？",
                        clsComFnc.GSYSTEM_NAME,
                        "YesNo",
                        "Question",
                        clsComFnc.MessageBoxDefaultButton.Button2
                    );
                }
            } else {
                if (rowId && rowId !== me.lastsel) {
                    $("#FrmOptionInput_sprMeisai").jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: false,
                    });

                    $(".numeric").numeric({
                        decimal: false,
                        negative: false,
                    });
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "saveRow",
                        me.lastsel
                    );
                    me.lastsel = rowId;
                }
            }
            gdmz.common.jqgrid.setKeybordEvents(
                "#FrmOptionInput_sprMeisai",
                e,
                me.lastsel
            );
        },
    });

    me.resetsel = function () {
        $("#FrmOptionInput_sprMeisai").jqGrid("setSelection", 101, true);
        $("#FrmOptionInput_sprMeisai").jqGrid("saveRow", 101);
        $("#FrmOptionInput_sprMeisai").jqGrid("resetSelection");
    };

    //20180517 lqs INS S
    me.fncTransferItem = function (val, rowId) {
        var percentagel = "";
        var amount1l = "";
        var amount2l = "";
        var percentager = "";
        var amount1r = "";
        var amount2r = "";
        if (rowId != me.lastsel) {
            percentagel = $("#FrmOptionInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "BUHIN_SYANAI_GEN_RITU"
            );
            amount1l = $("#FrmOptionInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "BUHIN_SYANAI_GEN"
            );
            amount2l = $("#FrmOptionInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "BUHIN_SYANAI_ZITU"
            );
            percentager = $("#FrmOptionInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "GAICYU_GEN_RITU"
            );
            amount1r = $("#FrmOptionInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "GAICYU_GEN"
            );
            amount2r = $("#FrmOptionInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "GAICYU_ZITU"
            );
        } else {
            percentagel = $("#" + rowId + "_" + "BUHIN_SYANAI_GEN_RITU").val();
            amount1l = $("#" + rowId + "_" + "BUHIN_SYANAI_GEN").val();
            amount2l = $("#" + rowId + "_" + "BUHIN_SYANAI_ZITU").val();
            percentager = $("#" + rowId + "_" + "GAICYU_GEN_RITU").val();
            amount1r = $("#" + rowId + "_" + "GAICYU_GEN").val();
            amount2r = $("#" + rowId + "_" + "GAICYU_ZITU").val();
        }
        if (val != "") {
            if (rowId != me.lastsel) {
                //20180528 lqs INS S
                if (percentagel != "" || amount1l != "" || amount2l != "") {
                    //20180528 lqs INS E
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_GEN_RITU",
                        ""
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_GEN",
                        ""
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_ZITU",
                        ""
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_GEN_RITU",
                        percentagel
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_GEN",
                        amount1l
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_ZITU",
                        amount2l
                    );
                }
            } else {
                //20180528 lqs INS S
                if (percentagel != "" || amount1l != "" || amount2l != "") {
                    //20180528 lqs INS E
                    $("#" + rowId + "_" + "BUHIN_SYANAI_GEN_RITU").val("");
                    $("#" + rowId + "_" + "BUHIN_SYANAI_GEN").val("");
                    $("#" + rowId + "_" + "BUHIN_SYANAI_ZITU").val("");
                    $("#" + rowId + "_" + "GAICYU_GEN_RITU").val(percentagel);
                    $("#" + rowId + "_" + "GAICYU_GEN").val(amount1l);
                    $("#" + rowId + "_" + "GAICYU_ZITU").val(amount2l);
                }
            }
        }
        if (val == "") {
            if (rowId != me.lastsel) {
                //20180528 lqs INS S
                if (percentager != "" || amount1r != "" || amount2r != "") {
                    //20180528 lqs INS E
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_GEN_RITU",
                        percentager
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_GEN",
                        amount1r
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_ZITU",
                        amount2r
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_GEN_RITU",
                        ""
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_GEN",
                        ""
                    );
                    $("#FrmOptionInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_ZITU",
                        ""
                    );
                }
            } else {
                //20180528 lqs INS S
                if (percentager != "" || amount1r != "" || amount2r != "") {
                    //20180528 lqs INS E
                    $("#" + rowId + "_" + "BUHIN_SYANAI_GEN_RITU").val(
                        percentager
                    );
                    $("#" + rowId + "_" + "BUHIN_SYANAI_GEN").val(amount1r);
                    $("#" + rowId + "_" + "BUHIN_SYANAI_ZITU").val(amount2r);
                    $("#" + rowId + "_" + "GAICYU_GEN_RITU").val("");
                    $("#" + rowId + "_" + "GAICYU_GEN").val("");
                    $("#" + rowId + "_" + "GAICYU_ZITU").val("");
                }
            }
        }
    };
    //20180517 lqs INS E
    me.fncToriNmSelect = function (val, setText, rowId) {
        if (val != "") {
            var url = me.sys_id + "/" + me.id + "/fncToriNmSelect";

            var arrayVal = {
                TORICD: val,
            };
            me.data = {
                request: arrayVal,
            };

            ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                // console.log(result['data'].length);
                if (result["data"].length > 0) {
                    if (rowId != me.lastsel) {
                        $("#FrmOptionInput_sprMeisai").jqGrid(
                            "setCell",
                            rowId,
                            "GYOUSYA_NM",
                            result["data"][0]["ATO_DTRPITNM1"]
                        );
                    } else {
                        $(setText).val(result["data"][0]["ATO_DTRPITNM1"]);
                    }
                }
                //20180517 lqs INS S
                else {
                    if (rowId != me.lastsel) {
                        $("#FrmOptionInput_sprMeisai").jqGrid(
                            "setCell",
                            rowId,
                            "GYOUSYA_NM",
                            ""
                        );
                    } else {
                        $(setText).val("");
                    }
                }
                //20180517 lqs INS E
            };
            ajax.send(url, me.data, 0);
        }
        //20180517 lqs INS S
        else {
            if (rowId != me.lastsel) {
                $("#FrmOptionInput_sprMeisai").jqGrid(
                    "setCell",
                    rowId,
                    "GYOUSYA_NM",
                    ""
                );
            } else {
                $(setText).val("");
            }
        }
        //20180517 lqs INS E
    };

    me.limit = function (location) {
        var inputval = location;
        if (location > 100) {
            inputval = location.substring(0, 2);
        }
        return inputval;
    };

    me.del = function () {
        var rowId = $("#FrmOptionInput_sprMeisai").jqGrid(
            "getGridParam",
            "selrow"
        );
        $("#FrmOptionInput_sprMeisai").jqGrid("delRowData", rowId);
        me.resetsel();
    };

    $("#FrmOptionInput_sprMeisai").jqGrid("setGroupHeaders", {
        useColSpanStyle: true,
        groupHeaders: [
            {
                startColumnName: "BUHIN_SYANAI_GEN_RITU",
                numberOfColumns: 2,
                titleText: "社内原価",
            },
            {
                startColumnName: "BUHIN_SYANAI_ZITU_RITU",
                numberOfColumns: 2,
                titleText: "社内実原価",
            },
            {
                startColumnName: "GAICYU_GEN_RITU",
                numberOfColumns: 2,
                titleText: "外注原価",
            },
            {
                startColumnName: "GAICYU_ZITU_RITU",
                numberOfColumns: 2,
                titleText: "外注実原価",
            },
        ],
    });

    $("#FrmOptionInput_sprMeisai").closest(".ui-jqgrid").block();
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();

        me.subFormClear();

        me.subFormSet();

        me.fncMeisaiSecondSet();
    };

    me.subFormClear = function () {
        // console.log('subFormClear');
        $(".FrmOptionInput.lblBusyoCD").val("");
        $(".FrmOptionInput.lblBusyoNM").val("");
        $(".FrmOptionInput.lblCar_NO").html("");
        $(".FrmOptionInput.lblCmnNO").val("");
        $(".FrmOptionInput.lblHanbaiCD").val("");
        $(".FrmOptionInput.lblHanbaiNM").val("");
        $(".FrmOptionInput.lblHanbaiSyasyu").html("");
        $(".FrmOptionInput.lblKasouNO").val("");
        $(".FrmOptionInput.lblKeiyakusya").val("");
        $(".FrmOptionInput.lblKosyo").val("");
        $(".FrmOptionInput.lblSiyosya").val("");
        $(".FrmOptionInput.lblSiyosyaKN").val("");
        $(".FrmOptionInput.lblSyadaiKata").html("");
        $(".FrmOptionInput.lblSyainNM").val("");
        $(".FrmOptionInput.lblSyainNO").val("");
        $(".FrmOptionInput.lblSyasyu_NM").html("");
        $(".FrmOptionInput.lblZei").val("");
        me.strExFlg = "";
    };

    me.subFormSet = function () {
        // console.log('subFormSet');
        var lblCmnNO = clsComFnc.FncNv($(".FrmList.txtCMNNO").val());
        var lblKeiyakusya = clsComFnc.FncNv($(".FrmList.lblKeiyakusya").val());
        var lblSiyosya = clsComFnc.FncNv($(".FrmList.lblSiyosya").val());
        var lblSiyosyaKN = clsComFnc.FncNv($(".FrmList.lblSiyosyaKN").val());
        var lblBusyoCD = clsComFnc.FncNv($(".FrmList.lblBusyoCD").val());
        var lblBusyoNM = clsComFnc.FncNv($(".FrmList.lblBusyoNM").val());
        var lblSyainNO = clsComFnc.FncNv($(".FrmList.lblSyainNO").val());
        var lblSyainNM = clsComFnc.FncNv($(".FrmList.lblSyainNM").val());
        var lblHanbaiCD = clsComFnc.FncNv($(".FrmList.lblHanbaitenNO").val());
        var lblHanbaiNM = clsComFnc.FncNv($(".FrmList.lblHanbaitenNM").val());
        var lblKasouNO = clsComFnc.FncNv($(".FrmList.lblKasouNO").val());
        var lblKosyou = clsComFnc.FncNv($(".FrmList.lblKosyo").val());
        var lblZei = clsComFnc.FncNv($(".FrmList.lblZei").val());
        var lblSyadaiKata = clsComFnc.FncNv($(".FrmList.lblSyadaiKata").html());
        var lblCar_NO = clsComFnc.FncNv($(".FrmList.lblCar_NO").html());
        var lblHanbaiSyasyu = clsComFnc.FncNv(
            $(".FrmList.lblHanbaiSyasyu").html()
        );
        var lblSyasyu_NM = clsComFnc.FncNv($(".FrmList.lblSyasyu_NM").html());

        $(".FrmOptionInput.lblCmnNO").val(lblCmnNO);
        $(".FrmOptionInput.lblKeiyakusya").val(lblKeiyakusya);
        $(".FrmOptionInput.lblSiyosya").val(lblSiyosya);
        $(".FrmOptionInput.lblSiyosyaKN").val(lblSiyosyaKN);
        $(".FrmOptionInput.lblBusyoCD").val(lblBusyoCD);
        $(".FrmOptionInput.lblBusyoNM").val(lblBusyoNM);
        $(".FrmOptionInput.lblSyainNO").val(lblSyainNO);
        $(".FrmOptionInput.lblSyainNM").val(lblSyainNM);
        $(".FrmOptionInput.lblHanbaiCD").val(lblHanbaiCD);
        $(".FrmOptionInput.lblHanbaiNM").val(lblHanbaiNM);
        $(".FrmOptionInput.lblKasouNO").val(lblKasouNO);
        $(".FrmOptionInput.lblKosyou").val(lblKosyou);
        $(".FrmOptionInput.lblZei").val(lblZei);
        $(".FrmOptionInput.lblSyadaiKata").html(lblSyadaiKata);
        $(".FrmOptionInput.lblCar_NO").html(lblCar_NO);
        $(".FrmOptionInput.lblHanbaiSyasyu").html(lblHanbaiSyasyu);
        $(".FrmOptionInput.lblSyasyu_NM").html(lblSyasyu_NM);
    };

    me.fncMeisaiFirstSet = function () {
        var url = me.sys_id + "/" + me.id + "/fncMeisaiFirstSet";
        var lblCmnNOVal = $(".FrmOptionInput.lblCmnNO").val().trimEnd();
        var lblCmnNOpart = lblCmnNOVal.substring(0, 4);
        var arrayVal = {
            CMN_NO: lblCmnNOVal,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            // console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            $("#FrmOptionInput_sprMeisai").jqGrid("clearGridData");
            var objDrS = Array();
            for (key in jsonResult.json[0]["data"]) {
                var columns = {
                    MEDALCD: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["MDL_CD"]
                    ),
                    BUHINNM: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["YHN_NM"]
                    ),
                    TEIKA: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["TEIKA"]
                    ),
                    SUURYOU: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["SURYO"]
                    ),
                    KAZEIKBN: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["FUGOU"]
                    ),
                    BUHIN_SYANAI_GEN_RITU: "",
                    BUHIN_SYANAI_GEN: "",
                    BUHIN_SYANAI_ZITU_RITU: "",
                    BUHIN_SYANAI_ZITU: "",
                    EC_JUCHU_KB: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["EC_JUCHU_KB"]
                    ),
                    // 20220922 YIN INS S
                    JUCHU_DT: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["JUCHU_DT"]
                    ),
                    // 20220922 YIN INS E
                };
                if (lblCmnNOpart != "271N" && lblCmnNOpart != "291N") {
                    if (columns["TEIKA"] > 1) {
                        // 20220922 YIN INS S
                        if (
                            columns["JUCHU_DT"] !== "" &&
                            columns["JUCHU_DT"] !== null &&
                            columns["JUCHU_DT"] <= "20220930"
                        ) {
                            // 20220922 YIN INS E
                            //20170518 Update Start
                            //columns['BUHIN_SYANAI_GEN_RITU'] = "60";
                            //columns['BUHIN_SYANAI_GEN'] = Math.round(columns['TEIKA'] * columns['BUHIN_SYANAI_GEN_RITU'] / 100);
                            if (columns["MEDALCD"] == "") {
                                columns["BUHIN_SYANAI_GEN_RITU"] = "83";
                                columns["BUHIN_SYANAI_GEN"] = Math.round(
                                    (columns["TEIKA"] *
                                        columns["BUHIN_SYANAI_GEN_RITU"]) /
                                        100
                                );
                                columns["BUHIN_SYANAI_ZITU_RITU"] = "";
                                columns["BUHIN_SYANAI_ZITU"] =
                                    Math.floor(columns["TEIKA"] / 1.3 / 100) *
                                    100;
                            } else if (columns["EC_JUCHU_KB"] == "11") {
                                columns["BUHIN_SYANAI_GEN_RITU"] = "80";
                                columns["BUHIN_SYANAI_GEN"] = Math.round(
                                    (columns["TEIKA"] *
                                        columns["BUHIN_SYANAI_GEN_RITU"]) /
                                        100
                                );
                            } else {
                                columns["BUHIN_SYANAI_GEN_RITU"] = "60";
                                columns["BUHIN_SYANAI_GEN"] = Math.round(
                                    (columns["TEIKA"] *
                                        columns["BUHIN_SYANAI_GEN_RITU"]) /
                                        100
                                );
                            }
                            //20170518 Update End
                            // 20220922 YIN INS S
                        } else {
                            columns["BUHIN_SYANAI_GEN_RITU"] = "70";
                            columns["BUHIN_SYANAI_GEN"] = Math.round(
                                (columns["TEIKA"] *
                                    columns["BUHIN_SYANAI_GEN_RITU"]) /
                                    100
                            );
                        }
                        // 20220922 YIN INS E
                    }
                }
                objDrS.push(columns);

                $("#FrmOptionInput_sprMeisai").jqGrid(
                    "addRowData",
                    parseInt(key) + 1,
                    objDrS[key]
                );
            }

            var countobjDrS = jsonResult.json[0]["data"].length;

            for (var i = countobjDrS; i < 101; i++) {
                $("#FrmOptionInput_sprMeisai").jqGrid(
                    "addRowData",
                    i + 1,
                    me.columns
                );
            }
            $("#101").hide();
            $("#jqgh_FrmOptionInput_sprMeisai_rn").html("");
            $("#jqgh_FrmOptionInput_sprMeisai_rn").html("削除");
            $("#FrmOptionInput_sprMeisai").closest(".ui-jqgrid").unblock();
            $(".FrmOptionInput.cmdAction").button("enable");
            $(".FrmOptionInput.cmdInsert").css("visibility", "hidden");
            $(".FrmOptionInput.cmdUpdate").css("visibility", "hidden");
        };
        ajax.send(url, me.data, 1);
    };

    me.fncMeisaiSecondSet = function () {
        // console.log('fncMeisaiSecondSet');

        var funcName = "fncMeisaiSecondSet";
        var url = me.sys_id + "/" + me.id + "/" + funcName;

        var lblCmnNOVal = $(".FrmOptionInput.lblCmnNO").val().trimEnd();
        var lblKasouNOVal = $(".FrmOptionInput.lblKasouNO").val().trimEnd();
        var lblCmnNOpart = lblCmnNOVal.substring(0, 4);
        var arrayVal = {
            CMN_NO: lblCmnNOVal,
            KASOUNO: lblKasouNOVal,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            // console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == "false") {
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
            if (
                jsonResult.json[0]["data"] == "" ||
                jsonResult.json[0]["data"] == null
            ) {
                me.fncMeisaiFirstSet();
                $(".FrmOptionInput.cmdAction").trigger("focus");
                return;
            }
            $(".FrmOptionInput.cmdInsert").trigger("focus");
            $("#FrmOptionInput_sprMeisai").jqGrid("clearGridData");
            var objDrS = Array();

            for (key in jsonResult.json[0]["data"]) {
                var columns = {
                    MEDALCD: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["MEDALCD"]
                    ),
                    BUHINNM: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BUHINNM"]
                    ),
                    BIKOU: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BIKOU"]
                    ),
                    TEIKA: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["TEIKA"]
                    ),
                    SUURYOU: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["SUURYOU"]
                    ),
                    BUHIN_SYANAI_GEN_RITU: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BUHIN_SYANAI_GEN_RITU"]
                    ),
                    BUHIN_SYANAI_GEN: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BUHIN_SYANAI_GEN"]
                    ),
                    BUHIN_SYANAI_ZITU_RITU: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key][
                            "BUHIN_SYANAI_ZITU_RITU"
                        ]
                    ),
                    BUHIN_SYANAI_ZITU: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BUHIN_SYANAI_ZITU"]
                    ),
                    GYOUSYA_CD: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GYOUSYA_CD"]
                    ),
                    GYOUSYA_NM: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GYOUSYA_NM"]
                    ),
                    KAZEIKBN: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["KAZEIKBN"]
                    ),
                    GAICYU_GEN_RITU: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GAICYU_GEN_RITU"]
                    ),
                    GAICYU_GEN: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GAICYU_GEN"]
                    ),
                    GAICYU_ZITU_RITU: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GAICYU_ZITU_RITU"]
                    ),
                    GAICYU_ZITU: clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GAICYU_ZITU"]
                    ),
                };
                if (
                    columns["BUHIN_SYANAI_GEN_RITU"] == "" ||
                    columns["BUHIN_SYANAI_GEN_RITU"] == null
                ) {
                    if (lblCmnNOpart != "271N" && lblCmnNOpart != "291N") {
                        if (
                            (columns["BUHIN_SYANAI_GEN"] == "" ||
                                columns["BUHIN_SYANAI_GEN"] == null) &&
                            (columns["BUHIN_SYANAI_ZITU"] == "" ||
                                columns["BUHIN_SYANAI_ZITU"] == null) &&
                            (columns["GAICYU_GEN"] == "" ||
                                columns["GAICYU_GEN"] == null) &&
                            (columns["GAICYU_ZITU"] == "" ||
                                columns["GAICYU_ZITU"] == null)
                        ) {
                            if (columns["TEIKA"] > 1) {
                                //20170518 Del Start
                                //columns['BUHIN_SYANAI_GEN_RITU'] = "60";
                                //columns['BUHIN_SYANAI_GEN'] = Math.round(columns['TEIKA'] * columns['BUHIN_SYANAI_GEN_RITU'] / 100);
                                //20170518 Del End
                            }
                        }
                    }
                }
                objDrS.push(columns);

                $("#FrmOptionInput_sprMeisai").jqGrid(
                    "addRowData",
                    parseInt(key) + 1,
                    objDrS[key]
                );
            }

            var countobjDrS = jsonResult.json[0]["data"].length;

            for (var i = countobjDrS; i < 101; i++) {
                $("#FrmOptionInput_sprMeisai").jqGrid(
                    "addRowData",
                    i + 1,
                    me.columns
                );
            }
            $("#101").hide();
        };
        ajax.send(url, me.data, 1);
    };
    me.editceil = function () {
        $("#FrmOptionInput_sprMeisai").jqGrid("editRow", me.lastsel, {
            keys: true,
            focusField: false,
        });

        $(".numeric").numeric({
            decimal: false,
            negative: false,
        });
    };

    me.focus = function () {
        me.editceil();
        var row = parseInt(me.rowNum) + 1;
        if (me.lastsel != row) {
            $("#FrmOptionInput_sprMeisai").jqGrid("saveRow", me.lastsel);
            $("#FrmOptionInput_sprMeisai").jqGrid("setSelection", row, true);
            $("#FrmOptionInput_sprMeisai").jqGrid("editRow", row, {
                keys: true,
                focusField: false,
            });

            $(".numeric").numeric({
                decimal: false,
                negative: false,
            });
            var ceil = parseInt(me.rowNum) + 1 + "_" + me.colNum;
            clsComFnc.ObjFocus = $("#" + ceil);
            clsComFnc.ObjSelect = $("#" + ceil);
        } else {
            var ceil = parseInt(me.rowNum) + 1 + "_" + me.colNum;
            clsComFnc.ObjFocus = $("#" + ceil);
            clsComFnc.ObjSelect = $("#" + ceil);
        }
    };

    me.fncInputChk = function (lngTeika1) {
        // console.log('fncInputChk');
        var lngTeika1 = 0;
        var InputChkFlag = false;
        var intRtn;
        me.arr1 = me.jqData();
        // console.log(me.arr1);
        for (key in me.arr1) {
            for (key1 in me.arr1[key]) {
                me.arr1[key][key1] = me.arr1[key][key1].trimEnd();
                switch (key1) {
                    //0
                    case "MEDALCD":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NONE,
                            me.colModel[0]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.ErrorRow = parseInt(key) + 1;
                            me.rowNum = key;
                            me.colNum = "MEDALCD";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[0]["label"]
                            );
                            return false;
                        }
                    //1
                    case "BUHINNM":
                        //20140215 Add Y0011 Start
                        me.arr1[key][key1] = me.arr1[key][key1].replace(
                            /\'/g,
                            ""
                        );
                        //20140215 Add Y0011 End
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NONE,
                            me.colModel[1]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHINNM";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[1]["label"]
                            );
                            return false;
                        }
                    //2
                    case "BIKOU":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NONE,
                            me.colModel[2]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BIKOU";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[2]["label"]
                            );
                            return false;
                        }
                    //3
                    case "TEIKA":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[3]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "TEIKA";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[3]["label"]
                            );
                            return false;
                        }
                    //4
                    case "SUURYOU":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[4]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "SUURYOU";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[4]["label"]
                            );
                            return false;
                        }
                    //5
                    case "BUHIN_SYANAI_GEN_RITU":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[5]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHIN_SYANAI_GEN_RITU";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[5]["label"]
                            );
                            return false;
                        }
                    //6
                    case "BUHIN_SYANAI_GEN":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[6]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHIN_SYANAI_GEN";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[6]["label"]
                            );
                            return false;
                        }
                    //7
                    case "BUHIN_SYANAI_ZITU_RITU":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[7]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHIN_SYANAI_ZITU_RITU";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[7]["label"]
                            );
                            return false;
                        }
                    //8
                    case "BUHIN_SYANAI_ZITU":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[8]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHIN_SYANAI_ZITU";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[8]["label"]
                            );
                            return false;
                        }
                    //9
                    case "GYOUSYA_CD":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.CHAR2,
                            me.colModel[9]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GYOUSYA_CD";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[9]["label"]
                            );
                            return false;
                        }
                    //10
                    case "GYOUSYA_NM":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NONE,
                            me.colModel[10]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GYOUSYA_NM";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[10]["label"]
                            );
                            return false;
                        }
                    //11
                    case "KAZEIKBN":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NONE,
                            me.colModel[11]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "KAZEIKBN";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[11]["label"]
                            );
                            return false;
                        }
                    //12
                    case "GAICYU_GEN_RITU":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[12]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GAICYU_GEN_RITU";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[12]["label"]
                            );
                            return false;
                        }
                    //13
                    case "GAICYU_GEN":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[13]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GAICYU_GEN";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[13]["label"]
                            );
                            return false;
                        }
                    //14
                    case "GAICYU_ZITU_RITU":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[14]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GAICYU_ZITU_RITU";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[14]["label"]
                            );
                            return false;
                        }
                    //15
                    case "GAICYU_ZITU":
                        intRtn = clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[15]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GAICYU_ZITU";
                            me.focus();
                            clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[15]["label"]
                            );
                            return false;
                        }
                }
            }
            //部品名称の必須ﾁｪｯｸ
            if (me.arr1[key]["BUHINNM"].length == 0) {
                me.rowNum = me.arry[key];
                me.colNum = "BUHINNM";
                me.focus();
                clsComFnc.FncMsgBox("W0001", "部品名称");
                return false;
            }
            //定価の必須ﾁｪｯｸ
            //if (me.arr1[key]['TEIKA'].TrimEnd == ''||me.arr1[key]['TEIKA'].TrimEnd == null) {
            if (me.arr1[key]["TEIKA"].length == 0) {
                me.rowNum = me.arry[key];
                me.colNum = "TEIKA";
                me.focus();
                clsComFnc.FncMsgBox("W0001", "定価");
                return false;
            }
            //定価の合計金額を算出

            lngTeika1 += parseInt(clsComFnc.FncNz(me.arr1[key]["TEIKA"]));
            me.lngTeika = lngTeika1;
        }
        InputChkFlag = true;
        return InputChkFlag;
    };

    me.cmdAction_Click = function () {
        $("#FrmOptionInput_sprMeisai").jqGrid("saveRow", me.lastsel);

        if (me.fncInputChk(me.lngTeika) == false) {
            return;
        }
        //架装番号を再取得
        if (me.strExFlg == "1") {
            me.fncUpdSaibanOption("fnc41E12TeikaSum");
            return;
        }
        me.lngFzkTeika = me.fnc41E12TeikaSum();
    };

    me.fnc41E12TeikaSum = function () {
        var lngFzkTeika = 0;
        var lngKasTeika = 0;
        var url = me.sys_id + "/" + me.id + "/" + "fnc41E12TeikaSum";
        var lblCmnNOVal = $(".FrmOptionInput.lblCmnNO").val().trimEnd();
        var lblKasouNOVal = $(".FrmOptionInput.lblKasouNO").val().trimEnd();
        var arrayVal = {
            CMN_NO: lblCmnNOVal,
            KASOUNO: lblKasouNOVal,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            // console.log(result);
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            // console.log(result);
            // M41E12の定価の合計を算出
            //console.log('M41E12の定価の合計を算出');
            if (
                result["data"]["FzkTeikaTbl"][0]["FZK_TEIKA"] != null &&
                result["data"]["FzkTeikaTbl"][0]["FZK_TEIKA"] != ""
            ) {
                lngFzkTeika = clsComFnc.FncNz(
                    result["data"]["FzkTeikaTbl"][0]["FZK_TEIKA"]
                );
                //		console.log(lngFzkTeika);
            }

            //既に入力されている架装明細の定価の合計を算出
            //console.log('既に入力されている架装明細の定価の合計を算出');
            if (
                result["data"]["KasTeikaTbl"][0]["KASO_TEIKA"] != null &&
                result["data"]["KasTeikaTbl"][0]["KASO_TEIKA"] != ""
            ) {
                lngKasTeika = clsComFnc.FncNz(
                    result["data"]["KasTeikaTbl"][0]["KASO_TEIKA"]
                );
                //console.log(lngKasTeika);
            }
            if (lngFzkTeika - lngKasTeika != me.lngTeika) {
                if (me.strExFlg == "1") {
                    clsComFnc.MsgBoxBtnFnc.Yes = me.fncUpdSaibanOption;
                } else {
                    clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataMeisai;
                }

                me.focusTeika();
                clsComFnc.MessageBox(
                    "定価の合計がR4と一致しません。登録しますか？",
                    clsComFnc.GSYSTEM_NAME,
                    clsComFnc.MessageBoxButtons.YesNo,
                    clsComFnc.MessageBoxIcon.Question,
                    clsComFnc.MessageBoxDefaultButton.Button2
                );
            } else {
                if (me.strExFlg == "1") {
                    clsComFnc.MsgBoxBtnFnc.Yes = me.fncUpdSaibanOption;
                } else {
                    clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataMeisai;
                }
                clsComFnc.MsgBoxBtnFnc.No = me.resetsel;
                clsComFnc.FncMsgBox("QY010");
            }
        };
        ajax.send(url, me.data, 0);
    };

    me.focusTeika = function () {
        $("#FrmOptionInput_sprMeisai").jqGrid("setSelection", 1, true);
        $("#FrmOptionInput_sprMeisai").jqGrid("editRow", 1, {
            keys: true,
            focusField: false,
        });

        $(".numeric").numeric({
            decimal: false,
            negative: false,
        });
        clsComFnc.ObjFocus = $("#1_TEIKA");
        clsComFnc.ObjSelect = $("#1_TEIKA");
    };
    //get the data of jqGrid
    me.jqData = function () {
        var arr = new Array();
        me.arry = new Array();
        var data = $("#FrmOptionInput_sprMeisai").jqGrid("getDataIDs");
        for (key in data) {
            var tableData = $("#FrmOptionInput_sprMeisai").jqGrid(
                "getRowData",
                data[key]
            );
            if (
                tableData["MEDALCD"] != "" ||
                tableData["BUHINNM"] != "" ||
                tableData["BIKOU"] ||
                tableData["TEIKA"] ||
                tableData["SUURYOU"] ||
                tableData["BUHIN_SYANAI_GEN_RITU"] ||
                tableData["BUHIN_SYANAI_GEN"] ||
                tableData["BUHIN_SYANAI_ZITU_RITU"] ||
                tableData["BUHIN_SYANAI_ZITU"] ||
                tableData["GYOUSYA_CD"] ||
                tableData["GYOUSYA_NM"] ||
                tableData["KAZEIKBN"] ||
                tableData["GAICYU_GEN_RITU"] ||
                tableData["GAICYU_GEN"] ||
                tableData["GAICYU_ZITU_RITU"] ||
                tableData["GAICYU_ZITU"]
            ) {
                arr.push(tableData);
                me.arry.push(key);
            }
        }
        return arr;
    };

    me.fncDeleteUpdataMeisai = function () {
        //console.log('架装明細ﾃｰﾌﾞﾙの該当データを削除する');
        var funcName = "fncDeleteUpdataMeisai";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var lblCmnNOVal = $(".FrmOptionInput.lblCmnNO").val().trimEnd();
        var lblSyadaiKataVal = $(".FrmOptionInput.lblSyadaiKata")
            .html()
            .trimEnd();
        var lblCar_NOVal = $(".FrmOptionInput.lblCar_NO").html().trimEnd();
        var lblHanbaiSyasyuVal = $(".FrmOptionInput.lblHanbaiSyasyu")
            .html()
            .trimEnd();
        var lblKosyouVal = $(".FrmOptionInput.lblKosyou").val().trimEnd();
        var lblSyasyu_NMVal = $(".FrmOptionInput.lblSyasyu_NM")
            .html()
            .trimEnd();
        var lblKasouNOVal = $(".FrmOptionInput.lblKasouNO").val().trimEnd();
        var lblZeiVal = $(".FrmOptionInput.lblZei").val().trimEnd();
        var arr2 = {
            CmnNO: lblCmnNOVal,
            SyadaiKata: lblSyadaiKataVal,
            Car_NO: lblCar_NOVal,
            HanbaiSyasyu: lblHanbaiSyasyuVal,
            Kosyou: lblKosyouVal,
            Syasyu_NM: lblSyasyu_NMVal,
            KasouNO: lblKasouNOVal,
            Zei: lblZeiVal,
        };
        var arrayVal = {
            arr: arr2,
            jqData: me.arr1,
        };

        me.data = {
            request: arrayVal,
        };
        //console.log(me.data);

        ajax.receive = function (result) {
            //console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            } else {
                //2014/02/15 Delete Y0010 Start
                //clsComFnc.MsgBoxBtnFnc.Close = me.close;
                //clsComFnc.FncMsgBox("I0008");
                //2014/02/15 Delete Y0010 End
                me.FrmList.PrpFlg = true;
                //2014/02/25 Add Y0010 Start
                //非同期処理の実行後に、確実に自画面を閉じる処理を実行する為にタイマーで実行
                setTimeout(function () {
                    me.close();
                }, 250);
                //2014/02/25 Add Y0010 End
            }
        };
        ajax.send(url, me.data, 0);
    };

    me.close = function () {
        $("#FrmListOptionDialogDiv").html("");
        $("#FrmListOptionDialogDiv").dialog("close");
    };

    me.cmdInsert_Click = function () {
        me.fncUpdSaiban();
    };

    me.fncUpdSaibanOption = function (method) {
        var funcName = "fncUpdSaiban";
        var url = me.sys_id + "/" + "FrmList" + "/" + funcName;

        var arrayVal = {
            blnUpdate: "false",
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            //console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }

            var strKasouNO = jsonResult.json[0]["fncUpdSaiban"];
            $(".FrmOptionInput.lblKasouNO").val(strKasouNO);
            if (method == "fnc41E12TeikaSum") {
                me.lngFzkTeika = me.fnc41E12TeikaSum();
            } else {
                me.fncDeleteUpdataMeisai();
            }
        };
        ajax.send(url, me.data, 0);
    };

    me.fncUpdSaiban = function () {
        //console.log('fncUpdSaiban');
        var funcName = "fncUpdSaiban";
        var url = me.sys_id + "/" + "FrmList" + "/" + funcName;

        var arrayVal = {
            blnUpdate: "false",
        };

        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            // console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
            me.lastsel = 101;
            $("#jqgh_FrmOptionInput_sprMeisai_rn").html("");
            $("#jqgh_FrmOptionInput_sprMeisai_rn").html("削除");
            $("#FrmOptionInput_sprMeisai").jqGrid("clearGridData");
            for (i = 0; i < 101; i++) {
                $("#FrmOptionInput_sprMeisai").jqGrid(
                    "addRowData",
                    i + 1,
                    me.columns
                );
            }
            $("#101").hide();
            $("#FrmOptionInput_sprMeisai").closest(".ui-jqgrid").unblock();

            $(".FrmOptionInput.cmdUpdate").button("disable");
            $(".FrmOptionInput.cmdAction").button("enable");
            me.strExFlg = "1";

            var strKasouNO = jsonResult.json[0]["fncUpdSaiban"];
            $(".FrmOptionInput.lblKasouNO").val(strKasouNO);
        };
        ajax.send(url, me.data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmOptionInput = new R4.FrmOptionInput();
    o_R4_FrmOptionInput.load();

    o_R4_R4.FrmList.FrmOptionInput = o_R4_FrmOptionInput;
    o_R4_FrmOptionInput.FrmList = o_R4_R4.FrmList;
});
