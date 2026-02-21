/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                       担当
 * YYYYMMDD            #ID                          XXXXXX                                    GSDL
 * 20201117            bug                          AJAX.SEND パラメータ数                     lqs
 * 20201117            表示倍率：125％の場合は、「Chrome」でjqGridの見出しが間違っています。       lqs
 * 20220922            #車両業務システム_仕様変更対応(H0009)		  架装明細入力　仕様変更対応           	 YIN
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmSpecialInput");

R4.FrmSpecialInput = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.lastsel = "";
    me.strExFlg = "";
    me.lngTeika = 0;
    me.lngFzkTeika = 0;
    me.lngKasTeika = 0;
    me.rowNum = "";
    me.colNum = "";
    me.arry = "";
    me.id = "FrmSpecialInput";
    me.sys_id = "R4G";
    me.data = "";
    me.arr1 = new Array();
    me.FrmList = null;
    //20180521 lqs INS S
    me.oldGYOUSYA_CD = "";
    //20180521 lqs INS E
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_MEDALCD";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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
            // 20201117 lqs upd S
            // width : 120,
            width: 118,
            // 20201117 lqs upd E
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_BUHINNM";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_BIKOU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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
            // 20201117 lqs upd S
            // width : 100,
            width: 95,
            // 20201117 lqs upd E
            sortable: false,
            align: "right",
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "8",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = $.trim($(e.target).val());
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.prop("id");
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_TEIKA";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_SUURYOU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_SUURYOU";
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
                            var newCodeValue = $(e.target).val();
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.prop("id");
                            var idGet = "#" + rowId + "_" + "TEIKA";
                            var vaGet = $(idGet).val();
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
                                sub = me.limit(this.value);
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "8",
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_GEN";
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
            name: "BUHIN_SYANAI_ZITU_RITU",
            label: "％",
            index: "BUHIN_SYANAI_ZITU_RITU",
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
                            var newCodeValue = $(e.target).val();
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.prop("id");
                            var idGet = "#" + rowId + "_" + "TEIKA";
                            var vaGet = $(idGet).val();
                            var totalVal = Math.round(
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
                                sub = me.limit(this.value);
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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
            sortable: false,
            align: "right",
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "8",
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId =
                                    "#" + selIRow + "_BUHIN_SYANAI_ZITU";
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
                            var rowId = row.prop("id");
                            me.oldGYOUSYA_CD = $(
                                "#" + rowId + "_" + "GYOUSYA_CD"
                            ).val();
                        },
                    },
                    //20180521 lqs INS E
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = me.clsComFnc.FncNv(
                                $(e.target).val()
                            );
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.prop("id");
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_GYOUSYA_CD";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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
            // width : 88,
            width: 85,
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_GYOUSYA_NM";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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
            sortable: false,
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_KAZEIKBN";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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
                            var newCodeValue = $(e.target).val();
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.prop("id");
                            var idGet = "#" + rowId + "_" + "TEIKA";
                            var vaGet = $(idGet).val();
                            var totalVal = Math.round(
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
                                sub = me.limit(this.value);
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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
            sortable: false,
            align: "right",
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "8",
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_GAICYU_GEN";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_GAICYU_GEN";
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
            name: "GAICYU_ZITU_RITU",
            label: "％",
            index: "GAICYU_ZITU_RITU",
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
                            var newCodeValue = $(e.target).val();
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.prop("id");
                            var idGet = "#" + rowId + "_" + "TEIKA";
                            var vaGet = $(idGet).val();
                            var totalVal = Math.round(
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
                            if (
                                (event.keyCode >= 96 && event.keyCode <= 105) ||
                                (event.keyCode >= 48 && event.keyCode <= 57)
                            ) {
                                sub = me.limit(this.value);
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

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
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "8",
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

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_GAICYU_ZITU";
                                $(selNextId).trigger("focus");
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                $("#FrmSpecialInput_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_GAICYU_ZITU";
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
    ];
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
        GAICYU_GEN: " ",
        GAICYU_ZITU_RITU: "",
        GAICYU_ZITU: "",
    };
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //zhenghuiyun s

    me.controls.push({
        id: ".FrmSpecialInput.cmdInsert",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSpecialInput.cmdUpdate",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSpecialInput.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSpecialInput.cmdOption",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSpecialInput.cmdBack",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    //zhenghuiyun e

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // '**********************************************************************
    // '処理概要：追加ボタン押下時
    // '**********************************************************************
    $(".FrmSpecialInput.cmdInsert").click(function () {
        //架装番号を採番する
        me.fncUpdSaiban(false);
    });

    // '**********************************************************************
    // '処理概要：修正ボタン押下時
    // '**********************************************************************
    $(".FrmSpecialInput.cmdUpdate").click(function () {
        $("#jqgh_FrmSpecialInput_sprMeisai_rn").html("");
        //make the jqGrid unblock
        $("#FrmSpecialInput_sprMeisai").closest(".ui-jqgrid").unblock();
        //add label '削除'
        $("#jqgh_FrmSpecialInput_sprMeisai_rn").html("削除");
        $(".FrmSpecialInput.cmdAction").button("enable");
        me.strExFlg = "";
    });

    // '**********************************************************************
    // '処理概要：更新ボタン押下時
    // '**********************************************************************
    $(".FrmSpecialInput.cmdAction").click(function () {
        $("#FrmSpecialInput_sprMeisai").jqGrid("saveRow", me.lastsel);
        if (me.fncInputChk(me.lngTeika) == false) {
            return;
        }
        //架装番号を再取得
        if (me.strExFlg == "1") {
            me.fncUpdSaibanSpecial("fnc41E12TeikaSum");
            return;
        }
        me.fnc41E12TeikaSum();
    });

    // '**********************************************************************
    // '処理概要：付属品ボタン押下時
    // '**********************************************************************
    $(".FrmSpecialInput.cmdOption").click(function () {
        var frmId = "FrmOptionInput";
        var url = me.sys_id + "/" + frmId + "/index";

        me.ajax.receive = function (result) {
            $("#FrmListOptionDialogDiv").html(result);
            $("#FrmListSpecialDialogDiv").html("");
            $("#FrmListSpecialDialogDiv").dialog("close");
            $("#FrmListOptionDialogDiv").dialog(
                "option",
                "title",
                "新車：付属品入力"
            );
            $("#FrmListOptionDialogDiv").dialog("open");
        };
        me.ajax.send(url, frmId, 0);
    });

    // '**********************************************************************
    // '処理概要：閉じるﾎﾞﾀﾝ押下時
    // '**********************************************************************
    $(".FrmSpecialInput.cmdBack").click(function () {
        $("#FrmListSpecialDialogDiv").html("");
        $("#FrmListSpecialDialogDiv").dialog("close");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //jqGrid start
    $("#FrmSpecialInput_sprMeisai").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,

        height: me.ratio === 1.5 ? 250 : 310,
        colModel: me.colModel,
        rownumbers: true,
        onSelectRow: function (rowId, status, e) {
            if (typeof e != "undefined") {
                var cellIndex =
                    e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;
                if (cellIndex != 0) {
                    if (rowId && rowId !== me.lastsel) {
                        $("#FrmSpecialInput_sprMeisai").jqGrid(
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
                        $("#FrmSpecialInput_sprMeisai").jqGrid(
                            "saveRow",
                            me.lastsel
                        );
                        me.lastsel = rowId;
                    }
                } else {
                    //削除確認メッセージを表示する
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "saveRow",
                        me.lastsel
                    );
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                    me.clsComFnc.MsgBoxBtnFnc.No = me.resetsel;
                    me.clsComFnc.MessageBox(
                        "削除します。よろしいですか？",
                        me.clsComFnc.GSYSTEM_NAME,
                        "YesNo",
                        "Question",
                        me.clsComFnc.MessageBoxDefaultButton.Button2
                    );
                }
            } else {
                if (rowId && rowId !== me.lastsel) {
                    $("#FrmSpecialInput_sprMeisai").jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: false,
                    });
                    $(".numeric").numeric({
                        decimal: false,
                        negative: false,
                    });
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "saveRow",
                        me.lastsel
                    );
                    me.lastsel = rowId;
                }
            }
            gdmz.common.jqgrid.setKeybordEvents(
                "#FrmSpecialInput_sprMeisai",
                e,
                me.lastsel
            );
        },
    });

    $("#FrmSpecialInput_sprMeisai").jqGrid("setGroupHeaders", {
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
    //jqGrid end

    //make the jqGrid block
    $("#FrmSpecialInput_sprMeisai").closest(".ui-jqgrid").block();

    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        $(".FrmSpecialInput.cmdAction").button("disable");
        me.subFormClear();
        me.subFormSet();
        me.fncMeisaiSecondSet();
    };

    // '**********************************************************************
    // '処 理 名：画面をクリア
    // '関 数 名：subFormClear
    // '引    数：blnHeaderClear (I)ヘッダーをクリアするかどうか　True：クリア　False：クリアしない
    // '戻 り 値：無し
    // '処理説明：画面をクリア
    // '**********************************************************************
    me.subFormClear = function () {
        $(".FrmSpecialInput.lblBusyoCD").val("");
        $(".FrmSpecialInput.lblBusyoNM").val("");
        $(".FrmSpecialInput.lblCar_NO").html("");
        $(".FrmSpecialInput.lblCmnNO").val("");
        $(".FrmSpecialInput.lblHanbaiCD").val("");
        $(".FrmSpecialInput.lblHanbaiNM").val("");
        $(".FrmSpecialInput.lblHanbaiSyasyu").html("");
        $(".FrmSpecialInput.lblKasouNO").val("");
        $(".FrmSpecialInput.lblKeiyakusya").val("");
        $(".FrmSpecialInput.lblKosyou").val("");
        $(".FrmSpecialInput.lblSiyosya").val("");
        $(".FrmSpecialInput.lblSiyosyaKN").val("");
        $(".FrmSpecialInput.lblSyadaiKata").html("");
        $(".FrmSpecialInput.lblSyainNM").val("");
        $(".FrmSpecialInput.lblSyainNO").val("");
        $(".FrmSpecialInput.lblSyasyu_NM").html("");
        $(".FrmSpecialInput.lblZei").val("");
        me.strExFlg = "";
    };

    // '**********************************************************************
    // '処 理 名：リストからプロパティーで渡されたﾃﾞｰﾀをセットする
    // '関 数 名：subFormSet
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：リストからプロパティーで渡されたﾃﾞｰﾀをセットする
    // '**********************************************************************
    me.subFormSet = function () {
        var lblCmnNO = me.clsComFnc.FncNv($(".FrmList.txtCMNNO").val());
        var lblKeiyakusya = me.clsComFnc.FncNv(
            $(".FrmList.lblKeiyakusya").val()
        );
        var lblSiyosya = me.clsComFnc.FncNv($(".FrmList.lblSiyosya").val());
        var lblSiyosyaKN = me.clsComFnc.FncNv($(".FrmList.lblSiyosyaKN").val());
        var lblBusyoCD = me.clsComFnc.FncNv($(".FrmList.lblBusyoCD").val());
        var lblBusyoNM = me.clsComFnc.FncNv($(".FrmList.lblBusyoNM").val());
        var lblSyainNO = me.clsComFnc.FncNv($(".FrmList.lblSyainNO").val());
        var lblSyainNM = me.clsComFnc.FncNv($(".FrmList.lblSyainNM").val());
        var lblHanbaiCD = me.clsComFnc.FncNv(
            $(".FrmList.lblHanbaitenNO").val()
        );
        var lblHanbaiNM = me.clsComFnc.FncNv(
            $(".FrmList.lblHanbaitenNM").val()
        );
        var lblKasouNO = me.clsComFnc.FncNv($(".FrmList.lblKasouNO").val());
        var lblKosyou = me.clsComFnc.FncNv($(".FrmList.lblKosyo").val());
        var lblZei = me.clsComFnc.FncNv($(".FrmList.lblZei").val());
        var lblSyadaiKata = me.clsComFnc.FncNv(
            $(".FrmList.lblSyadaiKata").html()
        );
        var lblCar_NO = me.clsComFnc.FncNv($(".FrmList.lblCar_NO").html());
        var lblHanbaiSyasyu = me.clsComFnc.FncNv(
            $(".FrmList.lblHanbaiSyasyu").html()
        );
        var lblSyasyu_NM = me.clsComFnc.FncNv(
            $(".FrmList.lblSyasyu_NM").html()
        );

        $(".FrmSpecialInput.lblCmnNO").val(lblCmnNO);
        $(".FrmSpecialInput.lblKeiyakusya").val(lblKeiyakusya);
        $(".FrmSpecialInput.lblSiyosya").val(lblSiyosya);
        $(".FrmSpecialInput.lblSiyosyaKN").val(lblSiyosyaKN);
        $(".FrmSpecialInput.lblBusyoCD").val(lblBusyoCD);
        $(".FrmSpecialInput.lblBusyoNM").val(lblBusyoNM);
        $(".FrmSpecialInput.lblSyainNO").val(lblSyainNO);
        $(".FrmSpecialInput.lblSyainNM").val(lblSyainNM);
        $(".FrmSpecialInput.lblHanbaiCD").val(lblHanbaiCD);
        $(".FrmSpecialInput.lblHanbaiNM").val(lblHanbaiNM);
        $(".FrmSpecialInput.lblKasouNO").val(lblKasouNO);
        $(".FrmSpecialInput.lblKosyou").val(lblKosyou);
        $(".FrmSpecialInput.lblZei").val(lblZei);
        $(".FrmSpecialInput.lblSyadaiKata").html(lblSyadaiKata);
        $(".FrmSpecialInput.lblCar_NO").html(lblCar_NO);
        $(".FrmSpecialInput.lblHanbaiSyasyu").html(lblHanbaiSyasyu);
        $(".FrmSpecialInput.lblSyasyu_NM").html(lblSyasyu_NM);
    };
    me.subSpreadSetup = function () {};
    // '架装明細ﾃｰﾌﾞﾙにデータが存在していない場合は、M41E12から表示する
    me.fncMeisaiFirstSet = function () {
        var funcName = "fncMeisaiFirstSet";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var lblCmnNOVal = $(".FrmSpecialInput.lblCmnNO").val().trimEnd();
        var lblCmnNOpart = lblCmnNOVal.substring(0, 4);
        var arrayVal = {
            CMN_NO: lblCmnNOVal,
        };
        me.data = {
            request: arrayVal,
        };

        me.ajax.receive = function (result) {
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
            $("#FrmSpecialInput_sprMeisai").jqGrid("clearGridData");
            var objDrS = Array();
            for (key in jsonResult.json[0]["data"]) {
                var columns = {
                    MEDALCD: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["MDL_CD"]
                    ),
                    BUHINNM: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["YHN_NM"]
                    ),
                    TEIKA: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["TEIKA"]
                    ),
                    SUURYOU: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["SURYO"]
                    ),
                    KAZEIKBN: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["FUGOU"]
                    ),
                    BUHIN_SYANAI_GEN_RITU: "",
                    BUHIN_SYANAI_GEN: "",
                    // 20220922 YIN INS S
                    JUCHU_DT: me.clsComFnc.FncNv(
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
                            //columns['BUHIN_SYANAI_GEN_RITU'] = "80";
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
                                columns["BUHIN_SYANAI_GEN_RITU"] = "80";
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
                $("#FrmSpecialInput_sprMeisai").jqGrid(
                    "addRowData",
                    parseInt(key) + 1,
                    objDrS[key]
                );
            }
            var countobjDrS = jsonResult.json[0]["data"].length;

            for (var i = countobjDrS; i < 101; i++) {
                $("#FrmSpecialInput_sprMeisai").jqGrid(
                    "addRowData",
                    i + 1,
                    me.columns
                );
            }
            $("#101").hide();
        };
        me.ajax.send(url, me.data, 1);

        $(".FrmSpecialInput.cmdInsert").css("visibility", "hidden");
        $(".FrmSpecialInput.cmdUpdate").css("visibility", "hidden");
        $(".FrmSpecialInput.cmdAction").button("enable");
        $("#FrmSpecialInput_sprMeisai").closest(".ui-jqgrid").unblock();
        $("#jqgh_FrmSpecialInput_sprMeisai_rn").html("削除");
    };

    me.fncMeisaiSecondSet = function () {
        var funcName = "fncMeisaiSecondSet";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var lblCmnNOVal = $(".FrmSpecialInput.lblCmnNO").val().trimEnd();
        var lblKasouNOVal = $(".FrmSpecialInput.lblKasouNO").val().trimEnd();
        var lblCmnNOpart = lblCmnNOVal.substring(0, 4);
        var arrayVal = {
            CMN_NO: lblCmnNOVal,
            KASOUNO: lblKasouNOVal,
        };
        me.data = {
            request: arrayVal,
        };

        me.ajax.receive = function (result) {
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
            if (
                jsonResult.json[0]["data"] == null ||
                jsonResult.json[0]["data"] == ""
            ) {
                me.fncMeisaiFirstSet();
                $(".FrmSpecialInput.cmdAction").trigger("focus");
                return;
            }
            $(".FrmSpecialInput.cmdInsert").trigger("focus");
            $("#FrmSpecialInput_sprMeisai").jqGrid("clearGridData");
            //'架装明細ﾃｰﾌﾞﾙにデータが存在
            var objDrS = Array();
            for (key in jsonResult.json[0]["data"]) {
                var columns = {
                    MEDALCD: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["MEDALCD"]
                    ),
                    BUHINNM: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BUHINNM"]
                    ),
                    BIKOU: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BIKOU"]
                    ),
                    TEIKA: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["TEIKA"]
                    ),
                    SUURYOU: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["SUURYOU"]
                    ),
                    BUHIN_SYANAI_GEN_RITU: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BUHIN_SYANAI_GEN_RITU"]
                    ),
                    BUHIN_SYANAI_GEN: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BUHIN_SYANAI_GEN"]
                    ),
                    BUHIN_SYANAI_ZITU_RITU: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key][
                            "BUHIN_SYANAI_ZITU_RITU"
                        ]
                    ),
                    BUHIN_SYANAI_ZITU: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["BUHIN_SYANAI_ZITU"]
                    ),
                    GYOUSYA_CD: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GYOUSYA_CD"]
                    ),
                    GYOUSYA_NM: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GYOUSYA_NM"]
                    ),
                    KAZEIKBN: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["KAZEIKBN"]
                    ),
                    GAICYU_GEN_RITU: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GAICYU_GEN_RITU"]
                    ),
                    GAICYU_GEN: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GAICYU_GEN"]
                    ),
                    GAICYU_ZITU_RITU: me.clsComFnc.FncNv(
                        jsonResult.json[0]["data"][key]["GAICYU_ZITU_RITU"]
                    ),
                    GAICYU_ZITU: me.clsComFnc.FncNv(
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
                                //columns['BUHIN_SYANAI_GEN_RITU'] = "80";
                                //columns['BUHIN_SYANAI_GEN'] = Math.round(columns['TEIKA'] * columns['BUHIN_SYANAI_GEN_RITU'] / 100);
                                //20170518 Del End
                            }
                        }
                    }
                }
                objDrS.push(columns);
                $("#FrmSpecialInput_sprMeisai").jqGrid(
                    "addRowData",
                    parseInt(key) + 1,
                    objDrS[key]
                );
            }
            var countobjDrS = jsonResult.json[0]["data"].length;

            for (var i = countobjDrS; i < 101; i++) {
                $("#FrmSpecialInput_sprMeisai").jqGrid(
                    "addRowData",
                    i + 1,
                    me.columns
                );
            }
            $("#101").hide();
        };
        me.ajax.send(url, me.data, 1);
    };

    //行削除を行う
    me.delRowData = function () {
        var rowId = $("#FrmSpecialInput_sprMeisai").jqGrid(
            "getGridParam",
            "selrow"
        );
        $("#FrmSpecialInput_sprMeisai").jqGrid("delRowData", rowId);
        me.resetsel();
    };

    // '**********************************************************************
    // '処 理 名：スプレッドの入力チェック
    // '関 数 名：fncInputChk
    // '引    数：lntTeika  (I)定価合計
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：スプレッドの入力チェック
    // '**********************************************************************
    me.fncInputChk = function (lngTeika1) {
        var InputChkFlag = false;
        var intRtn;
        var lngTeika1 = 0;
        me.arr1 = me.jqData();
        //console.log(me.arr1);
        for (key in me.arr1) {
            for (key1 in me.arr1[key]) {
                me.arr1[key][key1] = me.arr1[key][key1].trimEnd();
                switch (key1) {
                    //0
                    case "MEDALCD":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[0]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "MEDALCD";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
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
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[1]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHINNM";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[1]["label"]
                            );
                            return false;
                        }
                    //2
                    case "BIKOU":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[2]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BIKOU";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[2]["label"]
                            );
                            return false;
                        }
                    //3
                    case "TEIKA":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[3]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "TEIKA";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[3]["label"]
                            );
                            return false;
                        }
                    //4
                    case "SUURYOU":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[4]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "SUURYOU";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[4]["label"]
                            );
                            return false;
                        }
                    //5
                    case "BUHIN_SYANAI_GEN_RITU":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[5]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHIN_SYANAI_GEN_RITU";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[5]["label"]
                            );
                            return false;
                        }
                    //6
                    case "BUHIN_SYANAI_GEN":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[6]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHIN_SYANAI_GEN";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[6]["label"]
                            );
                            return false;
                        }
                    //7
                    case "BUHIN_SYANAI_ZITU_RITU":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[7]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHIN_SYANAI_ZITU_RITU";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[7]["label"]
                            );
                            return false;
                        }
                    //8
                    case "BUHIN_SYANAI_ZITU":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[8]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUHIN_SYANAI_ZITU";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[8]["label"]
                            );
                            return false;
                        }
                    //9
                    case "GYOUSYA_CD":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.CHAR2,
                            me.colModel[9]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GYOUSYA_CD";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[9]["label"]
                            );
                            return false;
                        }
                    //10
                    case "GYOUSYA_NM":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[10]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GYOUSYA_NM";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[10]["label"]
                            );
                            return false;
                        }
                    //11
                    case "KAZEIKBN":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[11]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "KAZEIKBN";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[11]["label"]
                            );
                            return false;
                        }
                    //12
                    case "GAICYU_GEN_RITU":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[12]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GAICYU_GEN_RITU";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[12]["label"]
                            );
                            return false;
                        }
                    //13
                    case "GAICYU_GEN":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[13]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GAICYU_GEN";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[13]["label"]
                            );
                            return false;
                        }
                    //14
                    case "GAICYU_ZITU_RITU":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[14]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GAICYU_ZITU_RITU";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[14]["label"]
                            );
                            return false;
                        }
                    //15
                    case "GAICYU_ZITU":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            me.colModel[15]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GAICYU_ZITU";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
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
                me.clsComFnc.FncMsgBox("W0001", "部品名称");
                return false;
            }
            //定価の必須ﾁｪｯｸ
            if (me.arr1[key]["TEIKA"].length == 0) {
                me.rowNum = me.arry[key];
                me.colNum = "TEIKA";
                me.focus();
                me.clsComFnc.FncMsgBox("W0001", "定価");
                return false;
            }
            //定価の合計金額を算出
            lngTeika1 += parseInt(me.clsComFnc.FncNz(me.arr1[key]["TEIKA"]));
            me.lngTeika = lngTeika1;
        }
        InputChkFlag = true;
        return InputChkFlag;
    };
    //M41E12とHKASOUMEISAIに既に登録されている定価との差引金額
    me.fnc41E12TeikaSum = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fnc41E12TeikaSum";
        var lblCmnNOVal = $(".FrmSpecialInput.lblCmnNO").val().trimEnd();
        var lblKasouNOVal = $(".FrmSpecialInput.lblKasouNO").val().trimEnd();
        var arrayVal = {
            CMN_NO: lblCmnNOVal,
            KASOUNO: lblKasouNOVal,
        };
        me.data = {
            request: arrayVal,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            //M41E12の定価の合計を算出
            if (result["data"].length) {
                me.lngFzkTeika = me.clsComFnc.FncNz(
                    result["data"][0]["FZK_TEIKA"]
                );
            }
            var url = me.sys_id + "/" + me.id + "/" + "fncKasouDifTeika";
            // 20201117 lqs upd S
            // me.ajax.send(url, me.data, 0, false);
            me.ajax.send(url, me.data, 0);
            // 20201117 lqs upd E
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                //既に入力されている架装明細の定価の合計を算出
                if (result["data"].length) {
                    me.lngKasTeika = me.clsComFnc.FncNz(
                        result["data"][0]["KASO_TEIKA"]
                    );
                    me.updata1();
                }
            };
        };
        me.ajax.send(url, me.data, 0);
    };
    //get the data of jqGrid
    me.jqData = function () {
        var arr = new Array();
        me.arry = new Array();
        var data = $("#FrmSpecialInput_sprMeisai").jqGrid("getDataIDs");
        for (key in data) {
            var tableData = $("#FrmSpecialInput_sprMeisai").jqGrid(
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
    //定価の合計が一致しない場合でも、登録できるように変更
    me.updata1 = function () {
        if (me.lngFzkTeika - me.lngKasTeika != me.lngTeika) {
            if (me.strExFlg == "1") {
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncUpdSaibanSpecial;
            } else {
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataMeisai;
            }
            me.editselect();
            me.clsComFnc.MessageBox(
                "定価の合計がR4と一致しません。登録しますか？",
                me.clsComFnc.GSYSTEM_NAME,
                me.clsComFnc.MessageBoxButtons.YesNo,
                me.clsComFnc.MessageBoxIcon.Question,
                me.clsComFnc.MessageBoxDefaultButton.Button2
            );
        } else {
            if (me.strExFlg == "1") {
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncUpdSaibanSpecial;
            } else {
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataMeisai;
            }
            //確認メッセージ
            me.clsComFnc.MsgBoxBtnFnc.No = me.resetsel;
            me.clsComFnc.FncMsgBox("QY010");
        }
    };

    //delete and insert
    me.fncDeleteUpdataMeisai = function () {
        var funcName = "fncDeleteUpdataMeisai";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var lblCmnNOVal = $(".FrmSpecialInput.lblCmnNO").val().trimEnd();
        var lblSyadaiKataVal = $(".FrmSpecialInput.lblSyadaiKata")
            .html()
            .trimEnd();
        var lblCar_NOVal = $(".FrmSpecialInput.lblCar_NO").html().trimEnd();
        var lblHanbaiSyasyuVal = $(".FrmSpecialInput.lblHanbaiSyasyu")
            .html()
            .trimEnd();
        var lblKosyouVal = $(".FrmSpecialInput.lblKosyou").val().trimEnd();
        var lblSyasyu_NMVal = $(".FrmSpecialInput.lblSyasyu_NM")
            .html()
            .trimEnd();
        var lblKasouNOVal = $(".FrmSpecialInput.lblKasouNO").val().trimEnd();
        var lblZeiVal = $(".FrmSpecialInput.lblZei").val().trimEnd();
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

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            } else {
                //2014/02/15 Delete Y0010 Start
                //me.clsComFnc.MsgBoxBtnFnc.Close = me.close;
                //me.clsComFnc.FncMsgBox("I0008");
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
        me.ajax.send(url, me.data, 0);
    };

    me.fncUpdSaibanSpecial = function (method) {
        var funcName = "fncUpdSaiban";
        var url = me.sys_id + "/" + "FrmList" + "/" + funcName;

        var arrayVal = {
            blnUpdate: "false",
        };
        me.data = {
            request: arrayVal,
        };

        me.ajax.receive = function (result) {
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
            var strKasouNO = jsonResult.json[0]["fncUpdSaiban"];
            $(".FrmSpecialInput.lblKasouNO").val(strKasouNO);
            if (method == "fnc41E12TeikaSum") {
                me.fnc41E12TeikaSum();
            } else {
                me.fncDeleteUpdataMeisai();
            }
        };
        me.ajax.send(url, me.data, 0);
    };

    //架装番号を採番する
    me.fncUpdSaiban = function () {
        var funcName = "fncUpdSaiban";
        var url = me.sys_id + "/" + "FrmList" + "/" + funcName;
        var arrayVal = {
            blnUpdate: "false",
        };
        me.data = {
            request: arrayVal,
        };

        me.ajax.receive = function (result) {
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
            var strKasouNO = jsonResult.json[0]["fncUpdSaiban"];
            $(".FrmSpecialInput.lblKasouNO").val(strKasouNO);
            me.lastsel = 101;
            $("#FrmSpecialInput_sprMeisai").jqGrid("clearGridData");
            for (i = 0; i < 101; i++) {
                $("#FrmSpecialInput_sprMeisai").jqGrid(
                    "addRowData",
                    i + 1,
                    me.columns
                );
            }
            $("#101").hide();
            $("#jqgh_FrmSpecialInput_sprMeisai_rn").html("");
            //make the jqGrid unblock
            $("#FrmSpecialInput_sprMeisai").closest(".ui-jqgrid").unblock();
            //add label '削除'
            $("#jqgh_FrmSpecialInput_sprMeisai_rn").html("削除");
            $(".FrmSpecialInput.cmdUpdate").button("disable");
            $(".FrmSpecialInput.cmdAction").button("enable");
            me.strExFlg = "1";
        };
        me.ajax.send(url, me.data, 0);
    };

    //20180517 lqs INS S
    me.fncTransferItem = function (val, rowId, e) {
        var percentagel = "";
        var amount1l = "";
        var amount2l = "";
        var percentager = "";
        var amount1r = "";
        var amount2r = "";
        if (rowId != me.lastsel) {
            percentagel = $("#FrmSpecialInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "BUHIN_SYANAI_GEN_RITU"
            );
            amount1l = $("#FrmSpecialInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "BUHIN_SYANAI_GEN"
            );
            amount2l = $("#FrmSpecialInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "BUHIN_SYANAI_ZITU"
            );
            percentager = $("#FrmSpecialInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "GAICYU_GEN_RITU"
            );
            amount1r = $("#FrmSpecialInput_sprMeisai").jqGrid(
                "getCell",
                rowId,
                "GAICYU_GEN"
            );
            amount2r = $("#FrmSpecialInput_sprMeisai").jqGrid(
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
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_GEN_RITU",
                        ""
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_GEN",
                        ""
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_ZITU",
                        ""
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_GEN_RITU",
                        percentagel
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_GEN",
                        amount1l
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
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
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_GEN_RITU",
                        percentager
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_GEN",
                        amount1r
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "BUHIN_SYANAI_ZITU",
                        amount2r
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_GEN_RITU",
                        ""
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
                        "setCell",
                        rowId,
                        "GAICYU_GEN",
                        ""
                    );
                    $("#FrmSpecialInput_sprMeisai").jqGrid(
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
    //取引先名を取得
    me.fncToriNmSelect = function (val, setText, rowId) {
        if (val != "") {
            var funcName = "fncToriNmSelect";
            var url = me.sys_id + "/" + me.id + "/" + funcName;

            var arrayVal = {
                TORICD: val,
            };
            me.data = {
                request: arrayVal,
            };

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["data"].length > 0) {
                    if (rowId != me.lastsel) {
                        $("#FrmSpecialInput_sprMeisai").jqGrid(
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
                        $("#FrmSpecialInput_sprMeisai").jqGrid(
                            "setCell",
                            rowId,
                            "GYOUSYA_NM",
                            ""
                        );
                    } else {
                        $(setText).val("");
                    }
                }
                //20180507 lqs INS E
            };
            me.ajax.send(url, me.data, 0);
        }
        //20180517 lqs INS S
        else {
            if (rowId != me.lastsel) {
                $("#FrmSpecialInput_sprMeisai").jqGrid(
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

    //keep the jqgrid editable
    me.editceil = function () {
        $("#FrmSpecialInput_sprMeisai").jqGrid("editRow", me.lastsel, {
            keys: true,
            focusField: false,
        });
        $(".numeric").numeric({
            decimal: false,
            negative: false,
        });
    };

    //focus the "1_TEIKA" ceil
    me.editselect = function () {
        $("#FrmSpecialInput_sprMeisai").jqGrid("setSelection", "1", true);
        $("#FrmSpecialInput_sprMeisai").jqGrid("editRow", "1", {
            keys: true,
            focusField: false,
        });
        $(".numeric").numeric({
            decimal: false,
            negative: false,
        });
        me.clsComFnc.ObjFocus = $("#1_TEIKA");
        me.clsComFnc.ObjSelect = $("#1_TEIKA");
    };

    //focus and select the error ceil
    me.focus = function () {
        me.editceil();
        var row = parseInt(me.rowNum) + 1;

        if (me.lastsel != row) {
            $("#FrmSpecialInput_sprMeisai").jqGrid("saveRow", me.lastsel);
            $("#FrmSpecialInput_sprMeisai").jqGrid("setSelection", row, true);
            $("#FrmSpecialInput_sprMeisai").jqGrid("editRow", row, {
                keys: true,
                focusField: false,
            });
            $(".numeric").numeric({
                decimal: false,
                negative: false,
            });
            var ceil = parseInt(me.rowNum) + 1 + "_" + me.colNum;
            me.clsComFnc.ObjFocus = $("#" + ceil);
            me.clsComFnc.ObjSelect = $("#" + ceil);
        } else {
            var ceil = parseInt(me.rowNum) + 1 + "_" + me.colNum;
            me.clsComFnc.ObjFocus = $("#" + ceil);
            me.clsComFnc.ObjSelect = $("#" + ceil);
        }
    };

    //[%]bigger than 100,cut down the last one
    me.limit = function (location) {
        var inputval = location;
        if (location > 100) {
            inputval = location.substring(0, 2);
        }
        return inputval;
    };

    //閉じる
    me.close = function () {
        $("#FrmListSpecialDialogDiv").html("");
        $("#FrmListSpecialDialogDiv").dialog("close");
    };

    me.resetsel = function () {
        $("#FrmSpecialInput_sprMeisai").jqGrid("setSelection", 101, true);
        $("#FrmSpecialInput_sprMeisai").jqGrid("saveRow", 101);
        $("#FrmSpecialInput_sprMeisai").jqGrid("resetSelection");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSpecialInput = new R4.FrmSpecialInput();
    o_R4_FrmSpecialInput.load();

    o_R4_R4.FrmList.FrmSpecialInput = o_R4_FrmSpecialInput;
    o_R4_FrmSpecialInput.FrmList = o_R4_R4.FrmList;
});
