/**
 * お買上明細マスタメンテナンス
 * @alias  FrmPrintTanto
 * @author FCSDL
 */
Namespace.register("R4.FrmOkaiageMst");

R4.FrmOkaiageMst = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.lastsel;
    me.rowNum;
    me.colNum;
    me.arry = "";
    me.id = "FrmOkaiageMst";
    me.sys_id = "R4G";
    me.arr1 = new Array();
    me.option = {
        pagerpos: "left",
    };
    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 92,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
            },
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 333,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "40",
            },
        },
        {
            name: "BUSYO_TEL",
            label: "電話番号",
            index: "BUSYO_TEL",
            width: 115,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "15",
                dataEvents: [
                    {
                        type: "keyup",
                        fn: function (event) {
                            if (
                                event.keyCode != 37 &&
                                event.keyCode != 38 &&
                                event.keyCode != 39 &&
                                event.keyCode != 40
                            ) {
                                if (me.GetByteCount1(this.value)) {
                                    this.value = this.value;
                                } else {
                                    this.value = this.value.replace(/\D/g, "");
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "GINKOU_NM_1",
            label: "銀行名_1",
            index: "GINKOU_NM_1",
            width: 333,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "40",
            },
        },
        {
            name: "GINKOUSITEN_NM_1",
            label: "銀行支店名_1",
            index: "GINKOUSITEN_NM_1",
            width: 333,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "40",
            },
        },
        {
            name: "KOUZA_SYU_1",
            label: "預金種別_1",
            index: "KOUZA_SYU_1",
            width: 90,
            sortable: false,
            align: "right",
            editable: true,
            editoptions: {
                maxlength: "10",
            },
        },
        {
            name: "KOUZA_NO_1",
            label: "口座番号_1",
            index: "KOUZA_NO_1",
            width: 170,
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "20",
            },
        },
        {
            name: "KOUZA_MEIGI_1",
            label: "口座名義人_1",
            index: "KOUZA_MEIGI_1",
            width: 259,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "30",
            },
        },
        {
            name: "GINKOU_NM_2",
            label: "銀行名_2",
            index: "GINKOU_NM_2",
            width: 333,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "40",
            },
        },
        {
            name: "GINKOUSITEN_NM_2",
            label: "銀行支店名_2",
            index: "GINKOUSITEN_NM_2",
            width: 333,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "40",
            },
        },
        {
            name: "KOUZA_SYU_2",
            label: "預金種別_2",
            index: "KOUZA_SYU_2",
            width: 90,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
            },
        },
        {
            name: "KOUZA_NO_2",
            label: "口座番号_2",
            index: "KOUZA_NO_2",
            width: 170,
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "20",
            },
        },
        {
            name: "KOUZA_MEIGI_2",
            label: "口座名義人_2",
            index: "KOUZA_MEIGI_2",
            width: 259,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "30",
            },
        },
        {
            name: "GINKOU_NM_3",
            label: "銀行名_3",
            index: "GINKOU_NM_3",
            width: 333,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "40",
            },
        },
        {
            name: "GINKOUSITEN_NM_3",
            label: "銀行支店名_3",
            index: "GINKOUSITEN_NM_3",
            width: 333,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "40",
            },
        },
        {
            name: "KOUZA_SYU_3",
            label: "預金種別_3",
            index: "KOUZA_SYU_3",
            width: 90,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
            },
        },
        {
            name: "KOUZA_NO_3",
            label: "口座番号_3",
            index: "KOUZA_NO_3",
            width: 170,
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "20",
            },
        },
        {
            name: "KOUZA_MEIGI_3",
            label: "口座名義人_3",
            index: "KOUZA_MEIGI_3",
            width: 259,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "30",
            },
        },
    ];
    //-----jqGrid block1 end----
    me.mydata = [
        {
            BUSYO_CD: "",
            BUSYO_NM: "",
            BUSYO_TEL: "",
            GINKOU_NM_1: "",
            GINKOUSITEN_NM_1: "",
            KOUZA_SYU_1: "",
            KOUZA_NO_1: "",
            KOUZA_MEIGI_1: "",
            GINKOU_NM_2: "",
            GINKOUSITEN_NM_2: "",
            KOUZA_SYU_2: "",
            KOUZA_NO_2: "",
            KOUZA_MEIGI_2: "",
            GINKOU_NM_3: "",
            GINKOUSITEN_NM_3: "",
            KOUZA_SYU_3: "",
            KOUZA_NO_3: "",
            KOUZA_MEIGI_3: "",
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmOkaiageMst.button_action",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // '**********************************************************************
    // '処理概要：更新ボタン押下時
    // '**********************************************************************
    $(".FrmOkaiageMst.button_action").click(function () {
        jQuery("#frmOkaiageMst_sprList").jqGrid(
            "saveRow",
            me.lastsel,
            null,
            "clientArray"
        );
        if (me.fncInputChk()) {
            data = {
                request: me.arr1,
            };
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataOkaiageMst;
            me.clsComFnc.MsgBoxBtnFnc.No = me.cancelsel;
            me.clsComFnc.FncMsgBox("QY010");
            $("#frmOkaiageMst_sprList").closest(".ui-jqgrid").unblock();
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    $("#frmOkaiageMst_sprList").jqGrid({
        datatype: "local",
        height: me.ratio === 1.5 ? "331" : "390",
        rownumbers: true,
        colModel: me.colModel,
        //编辑jqGrid
        onSelectRow: function (rowid, _status, e) {
            if (typeof e != "undefined") {
                if (rowid && rowid !== me.lastsel) {
                    //編集可能なセルをクリック、上下キー
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;

                    $("#frmOkaiageMst_sprList").jqGrid("editRow", rowid, {
                        //「editRow」方法の2番目のパラメータと同じ
                        keys: true,
                        focusField: cellIndex,
                    });
                    $(".numeric").numeric({
                        decimal: false,
                        negative: false,
                    });
                    jQuery("#frmOkaiageMst_sprList").jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    me.lastsel = rowid;
                }
            } else {
                if (rowid && rowid !== me.lastsel) {
                    $("#frmOkaiageMst_sprList").jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: false,
                    });
                    $(".numeric").numeric({
                        decimal: false,
                        negative: false,
                    });
                    jQuery("#frmOkaiageMst_sprList").jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    me.lastsel = rowid;
                }
            }
            gdmz.common.jqgrid.setKeybordEvents(
                "#frmOkaiageMst_sprList",
                e,
                me.lastsel
            );
        },
    });

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.fncFrmOkaiageMst();
    };

    me.UpdateDeal = function () {
        var arr = new Array();
        me.arry = new Array();
        var data = $("#frmOkaiageMst_sprList").jqGrid("getDataIDs");
        for (key in data) {
            var tableData = $("#frmOkaiageMst_sprList").jqGrid(
                "getRowData",
                data[key]
            );
            if (
                tableData["BUSYO_CD"] != "" ||
                tableData["BUSYO_NM"] != "" ||
                tableData["BUSYO_TEL"] ||
                tableData["GINKOU_NM_1"] ||
                tableData["GINKOUSITEN_NM_1"] ||
                tableData["KOUZA_SYU_1"] ||
                tableData["KOUZA_NO_1"] ||
                tableData["KOUZA_MEIGI_1"] ||
                tableData["GINKOU_NM_2"] ||
                tableData["GINKOUSITEN_NM_2"] ||
                tableData["KOUZA_SYU_2"] ||
                tableData["KOUZA_NO_2"] ||
                tableData["KOUZA_MEIGI_2"] ||
                tableData["GINKOU_NM_3"] ||
                tableData["GINKOUSITEN_NM_3"] ||
                tableData["KOUZA_SYU_3"] ||
                tableData["KOUZA_NO_3"] ||
                tableData["KOUZA_MEIGI_3"]
            ) {
                arr.push(tableData);
                me.arry.push(key);
            }
        }
        return arr;
    };

    me.editceil = function () {
        $("#frmOkaiageMst_sprList").jqGrid("editRow", me.lastsel, {
            keys: true,
            focusField: false,
        });
        $(".numeric").numeric({
            decimal: false,
            negative: false,
        });
    };

    me.cancelsel = function () {
        $("#frmOkaiageMst_sprList").jqGrid("setSelection", 101, true);
        $("#frmOkaiageMst_sprList").jqGrid("saveRow", 101, null, "clientArray");
        $("#frmOkaiageMst_sprList").jqGrid("resetSelection");
    };

    me.focus = function () {
        me.editceil();
        var row = parseInt(me.rowNum) + 1;
        if (me.lastsel != row) {
            $("#frmOkaiageMst_sprList").jqGrid(
                "saveRow",
                me.lastsel,
                null,
                "clientArray"
            );
            $("#frmOkaiageMst_sprList").jqGrid("setSelection", row);
            $("#frmOkaiageMst_sprList").jqGrid("editRow", row, {
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
    // '**********************************************************************
    // '処 理 名：スプレッドの入力チェック
    // '関 数 名：fncInputChk
    // '引    数：lntTeika  (I)定価合計
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：スプレッドの入力チェック
    // '**********************************************************************
    me.fncInputChk = function () {
        var InputChkFlag = false;
        me.arr1 = me.UpdateDeal();
        var intRtn;
        for (key in me.arr1) {
            for (key1 in me.arr1[key]) {
                me.arr1[key][key1] = me.arr1[key][key1].trimEnd();
                switch (key1) {
                    case "BUSYO_CD":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            1,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[0]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "BUSYO_CD";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[0]["label"]
                            );
                            return false;
                        }
                    case "BUSYO_NM":
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
                            me.colNum = "BUSYO_NM";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[1]["label"]
                            );
                            return false;
                        }
                    case "BUSYO_TEL":
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
                            me.colNum = "BUSYO_TEL";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[2]["label"]
                            );
                            return false;
                        }
                    case "GINKOU_NM_1":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[3]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GINKOU_NM_1";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[3]["label"]
                            );
                            return false;
                        }
                    case "GINKOUSITEN_NM_1":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[4]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GINKOUSITEN_NM_1";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[4]["label"]
                            );
                            return false;
                        }
                    case "KOUZA_SYU_1":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[5]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "KOUZA_SYU_1";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[5]["label"]
                            );
                            return false;
                        }
                    case "KOUZA_NO_1":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[6]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "KOUZA_NO_1";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[6]["label"]
                            );
                            return false;
                        }
                    case "KOUZA_MEIGI_1":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[7]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "KOUZA_MEIGI_1";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[7]["label"]
                            );
                            return false;
                        }
                    case "GINKOU_NM_2":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[8]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GINKOU_NM_2";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[8]["label"]
                            );
                            return false;
                        }
                    case "GINKOUSITEN_NM_2":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[9]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GINKOUSITEN_NM_2";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[9]["label"]
                            );
                            return false;
                        }
                    case "KOUZA_SYU_2":
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
                            me.colNum = "KOUZA_SYU_2";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[10]["label"]
                            );
                            return false;
                        }
                    case "KOUZA_NO_2":
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
                            me.colNum = "KOUZA_NO_2";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[11]["label"]
                            );
                            return false;
                        }
                    case "KOUZA_MEIGI_2":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[12]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "KOUZA_MEIGI_2";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[12]["label"]
                            );
                            return false;
                        }
                    case "GINKOU_NM_3":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[13]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GINKOU_NM_3";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[13]["label"]
                            );
                            return false;
                        }
                    case "GINKOUSITEN_NM_3":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[14]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "GINKOUSITEN_NM_3";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[14]["label"]
                            );
                            return false;
                        }
                    case "KOUZA_SYU_3":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[15]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "KOUZA_SYU_3";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[15]["label"]
                            );
                            return false;
                        }
                    case "KOUZA_NO_3":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[16]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "KOUZA_NO_3";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[16]["label"]
                            );
                            return false;
                        }
                    case "KOUZA_MEIGI_3":
                        intRtn = me.clsComFnc.FncSprCheck(
                            me.arr1[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[17]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            me.rowNum = key;
                            me.colNum = "KOUZA_MEIGI_3";
                            me.focus();
                            me.clsComFnc.FncMsgBox(
                                "W000" + intRtn * -1,
                                me.colModel[17]["label"]
                            );
                            return false;
                        }
                }
            }
            // //部署コードの必須ﾁｪｯｸ
            // if (me.arr1[key]["BUSYO_CD"].length == 0) {
            //     me.rowNum = me.arry[key];
            //     me.colNum = "BUSYO_CD";
            //     me.focus();
            //     me.clsComFnc.FncMsgBox("W0001", "部署コード");
            //     return false;
            // }
        }
        InputChkFlag = true;
        return InputChkFlag;
    };
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.fncFrmOkaiageMst = function () {
        var url = me.sys_id + "/" + me.id + "/fncFrmOkaiageMst";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                for (var i = 0; i < result["data"].length; i++) {
                    $("#frmOkaiageMst_sprList").jqGrid(
                        "addRowData",
                        i + 1,
                        result["data"][i]
                    );
                }
                if (result["data"].length < 100) {
                    for (i = result["data"].length; i < 101; i++) {
                        $("#frmOkaiageMst_sprList").jqGrid(
                            "addRowData",
                            i + 1,
                            me.mydata[0]
                        );
                    }
                }
                $("#101").hide();
            }
        };
        me.ajax.send(url, "", 1);
    };
    //**************************************************************************
    //お買上げ明細マスタのデータを削除する
    //お買上げ明細マスタに追加するためのSQLを発行
    //**************************************************************************
    me.fncDeleteUpdataOkaiageMst = function () {
        me.cancelsel();
        var url = me.sys_id + "/" + me.id + "/fncDeleteUpdataOkaiageMst";
        var data = {
            request: me.arr1,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                //$("#frmOkaiageMst_sprList").closest('.ui-jqgrid').unblock();
            } else {
                me.clsComFnc.FncMsgBox("I0008");
                $("#frmOkaiageMst_sprList").closest(".ui-jqgrid").unblock();
            }
        };
        me.ajax.send(url, data, 0);
    };
    //pan duan quan ban jiao
    me.GetByteCount1 = function (str) {
        var uFF61 = parseInt("FF61", 16);
        var uFF9F = parseInt("FF9F", 16);
        var uFFE8 = parseInt("FFE8", 16);
        var uFFEE = parseInt("FFEE", 16);
        var flagCheck = true;
        if (str != null) {
            for (var i = 0; i < str.length; i++) {
                var c = parseInt(str.charCodeAt(i));
                if (c < 256) {
                    flagCheck = true;
                } else {
                    if (uFF61 <= c && c <= uFF9F) {
                        flagCheck = true;
                    } else if (uFFE8 <= c && c <= uFFEE) {
                        flagCheck = true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return flagCheck;
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmOkaiageMst = new R4.FrmOkaiageMst();
    o_R4_FrmOkaiageMst.load();
});
