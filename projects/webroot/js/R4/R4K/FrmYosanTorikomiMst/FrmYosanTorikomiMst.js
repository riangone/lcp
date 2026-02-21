/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150819           #2078						   BUG                              Yuanjh
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmYosanTorikomiMst");

R4.FrmYosanTorikomiMst = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========
    // ========== 変数 start ==========
    me.id = "R4K/FrmYosanTorikomiMst";
    me.grid_id = "#FrmYosanTorikomiMst_sprList";
    me.lastsel = 0;
    me.strSaveSRCD = "";
    me.intSaveRowCnt = 0;
    me.arrInputDatas = new Array();
    me.firstData = new Array();
    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.addData = {
        BUSYO_KB: "",
        LINE_NO: "",
        EXCEL_LINE_NO: "",
        RND_POS: "",
        CAL_KB: "",
        CREATE_DATE: "",
    };
    me.colModel = [
        {
            name: "BUSYO_KB",
            label: "部署区分",
            index: "BUSYO_KB",
            width: 120,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: 1,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 222) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "LINE_NO",
            label: "ラインNo.",
            index: "LINE_NO",
            width: 120,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric1",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "EXCEL_LINE_NO",
            label: "ExcelラインNo.",
            index: "EXCEL_LINE_NO",
            width: 120,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric1",
                maxlength: 3,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "RND_POS",
            label: "単位",
            index: "RND_POS",
            width: 120,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric2",
                maxlength: 1,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 1, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CAL_KB",
            label: "計算区分",
            index: "CAL_KB",
            width: 120,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric2",
                maxlength: 1,
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (key == 229) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 1, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CREATE_DATE",
            label: "作成日付",
            index: "CREATE_DATE",
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmYosanTorikomiMst.cmdKensaku",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmYosanTorikomiMst.cmdInsert",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmYosanTorikomiMst.cmdAction",
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

    // ==========
    // = イベント start =
    // ==========

    // '**********************************************************************
    // '処理概要：検索ボタン押下時
    // '**********************************************************************
    $(".FrmYosanTorikomiMst.cmdKensaku").click(function () {
        //保存用変数に検索条件を保存
        me.strSaveSRCD = $(".FrmYosanTorikomiMst.txtBusyoKB").val().trimEnd();
        me.intSaveRowCnt = 0;

        //ｽﾌﾟﾚｯﾄﾞｸﾘｱ
        $(me.grid_id).jqGrid("clearGridData");
        $(".FrmYosanTorikomiMst.cmdInsert").button("disable");
        $(".FrmYosanTorikomiMst.cmdAction").button("disable");

        //予算取込ﾏｽﾀから基本ﾃﾞｰﾀを抽出
        var data = {
            BUSYO_KB: me.strSaveSRCD,
        };
        me.complete_fun = function (bErrorFlag) {
            //予算取込マスタにデータが存在しない場合
            if (bErrorFlag == "nodata") {
                $(".FrmYosanTorikomiMst.cmdInsert").button("enable");
                $(".FrmYosanTorikomiMst.cmdAction").button("disable");
                $(".FrmYosanTorikomiMst.txtBusyoKB").trigger("select");
                return;
            } else if (bErrorFlag == "normal") {
                me.firstData = $(me.grid_id).jqGrid("getRowData");
                me.intSaveRowCnt = $(me.grid_id).jqGrid(
                    "getGridParam",
                    "records"
                );
                me.fncCompleteDeal();

                //１行目を選択状態にする
                if (me.intSaveRowCnt != 0) {
                    $(me.grid_id).jqGrid("setSelection", 0);
                }

                $(".FrmYosanTorikomiMst.cmdInsert").button("enable");
                $(".FrmYosanTorikomiMst.cmdAction").button("enable");
                //20240624 lujunxia del s
                //検索ボタンを再クリック後、上下キーを押す、エラーが発生する問題
                //20150820	Yuanjh ADD S.
                //$(me.grid_id).jqGrid("bindKeys");
                //20150820	Yuanjh ADD E.
                //20240624 lujunxia del e
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    });

    // '**********************************************************************
    // '処理概要：新規ボタン押下時
    // '**********************************************************************
    $(".FrmYosanTorikomiMst.cmdInsert").click(function () {
        var rowIDs = $(me.grid_id).jqGrid("getDataIDs");

        if (rowIDs.length > 0) {
            $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
            var newLineID = parseInt(rowIDs[rowIDs.length - 1]);
            var rowData = $(me.grid_id).jqGrid("getRowData", newLineID);

            if (
                rowData["BUSYO_KB"] != "" ||
                rowData["LINE_NO"] != "" ||
                rowData["EXCEL_LINE_NO"] != "" ||
                rowData["RND_POS"] != "" ||
                rowData["CAL_KB"] != ""
            ) {
                $(me.grid_id).jqGrid("addRowData", newLineID + 1, me.addData);
                $(me.grid_id).jqGrid("setSelection", newLineID + 1, true);
            } else {
                $(me.grid_id).jqGrid("setSelection", newLineID, true);
            }
        } else {
            $(me.grid_id).jqGrid("addRowData", 0, me.addData);

            me.fncCompleteDeal();
            $(me.grid_id).jqGrid("setSelection", 0, true);
        }

        $(".FrmYosanTorikomiMst.cmdAction").button("enable");
    });

    $(".FrmYosanTorikomiMst.txtBusyoKB").keydown(function (e) {
        var key = e.charCode || e.keyCode;

        if (key == 222) {
            return false;
        }
    });

    //'**********************************************************************
    //'処理概要：更新ボタン押下時
    //'**********************************************************************
    $(".FrmYosanTorikomiMst.cmdAction").click(function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

        //入力チェック
        if (me.fncInputChk() == false) {
            return;
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_load = me.load;

    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.load = function () {
        base_load();

        //ｽﾌﾟﾚｯﾄﾞｸﾘｱ
        $(me.grid_id).jqGrid("clearGridData");
        $(".FrmYosanTorikomiMst.txtBusyoKB").val("");
        $(".FrmYosanTorikomiMst.txtBusyoKB").trigger("focus");
        $(".FrmYosanTorikomiMst.cmdInsert").button("disable");
        $(".FrmYosanTorikomiMst.cmdAction").button("disable");

        var url = me.id + "/fncYosanTorikomiMstSel";
        gdmz.common.jqgrid.init(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 700);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 270 : 338
        );
        //20240624 lujunxia ins s
        $(me.grid_id).jqGrid("bindKeys");
        //20240624 lujunxia ins s
    };

    // '**********************************************************************
    // '処理概要：スプレッドセルクリック
    // '**********************************************************************
    me.fncCompleteDeal = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;

                    if (cellIndex != 0) {
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
                            focusField: cellIndex,
                        });
                    } else {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );

                        //削除確認メッセージを表示する
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                        me.clsComFnc.MessageBox(
                            "削除します、よろしいですか？",
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

                    $(me.grid_id).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: false,
                    });
                }

                $(".numeric1").numeric({
                    decimal: false,
                    negative: false,
                });

                $(".numeric2").numeric({
                    decimal: false,
                    negative: true,
                });
                gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    me.lastsel
                );
            },
        });
    };
    me.inputReplace = function (targetVal, inputLength, keycode) {
        var inputValue = $(targetVal).val();

        if (inputValue == "" && keycode == 45) {
            $(targetVal).val("-0");
            return false;
        } else if (inputValue.indexOf("-") == -1) {
            if (keycode == 45) {
                $(targetVal).val("-" + inputValue);
                return false;
            } else if (
                inputValue.length == inputLength &&
                inputValue != "-0" &&
                inputValue != "0"
            ) {
                return false;
            } else if (
                keycode == 48 &&
                (inputValue == "-0" || inputValue == "0")
            ) {
                return false;
            }
        } else {
            if (keycode == 45) {
                $(targetVal).val(inputValue.substr(1));
                return false;
            }
        }

        if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
            inputValue = inputValue.substr(0, 1) + (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        } else if (inputValue == "0" && keycode >= 49 && keycode <= 57) {
            inputValue = (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        }

        return true;
    };

    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        var url = me.id + "/fncDeleteRowData";
        var data = {
            BUSYO_KB: rowData["BUSYO_KB"],
            LINE_NO: rowData["LINE_NO"],
            EXCEL_LINE_NO: rowData["EXCEL_LINE_NO"],
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                //行削除を行う
                me.delRowDataContent(rowID);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.delRowDataContent = function (rowID) {
        var getDataID = $(me.grid_id).jqGrid("getDataIDs");

        for (var i = parseInt(rowID); i < getDataID.length - 1; i++) {
            var rowData = $(me.grid_id).jqGrid("getRowData", i + 1);
            $(me.grid_id).jqGrid("setRowData", i, rowData);
        }

        $(me.grid_id).jqGrid("delRowData", getDataID.length - 1);
        if (me.firstData.length - 1 >= parseInt(rowID)) {
            me.firstData.splice(parseInt(rowID), 1);
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
        var blnInputFlg = false;
        me.arrInputDatas = new Array();
        var gridIDs = $(me.grid_id).jqGrid("getGridParam", "records");

        for (var rowID = 0; rowID < gridIDs; rowID++) {
            var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
            me.arrInputDatas.push(rowData);

            //どれか一列でも入力されていた場合
            if (
                rowData["BUSYO_KB"].trimEnd() != "" ||
                rowData["LINE_NO"].trimEnd() != "" ||
                rowData["EXCEL_LINE_NO"].trimEnd() != "" ||
                rowData["RND_POS"].trimEnd() != "" ||
                rowData["CAL_KB"].trimEnd() != ""
            ) {
                var iColNo = 0;

                //入力チェック
                for (colID in rowData) {
                    switch (colID) {
                        //0
                        case "BUSYO_KB":
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR2,
                                me.colModel[iColNo]["editoptions"]["maxlength"]
                            );
                            break;
                        //1, 2, 3, 4
                        case "LINE_NO":
                        case "EXCEL_LINE_NO":
                        case "RND_POS":
                        case "CAL_KB":
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[iColNo]["editoptions"]["maxlength"]
                            );
                            break;
                    }

                    if (intRtn != 0) {
                        me.setFocus(rowID, colID);
                        me.clsComFnc.FncMsgBox(
                            "W000" + intRtn * -1,
                            me.colModel[iColNo]["label"]
                        );
                        return false;
                    }

                    iColNo += 1;
                }

                //キー項目の必須ﾁｪｯｸ
                if (rowData["BUSYO_KB"].trimEnd() == "") {
                    me.setFocus(rowID, "BUSYO_KB");
                    me.clsComFnc.FncMsgBox("W0001", me.colModel[0]["label"]);
                    return false;
                }

                //ライン№の必須チェック
                if (rowData["LINE_NO"].trimEnd() == "") {
                    me.setFocus(rowID, "LINE_NO");
                    me.clsComFnc.FncMsgBox("W0001", me.colModel[1]["label"]);
                    return false;
                }

                //エクセルライン№の必須チェック
                if (rowData["EXCEL_LINE_NO"].trimEnd() == "") {
                    me.setFocus(rowID, "EXCEL_LINE_NO");
                    me.clsComFnc.FncMsgBox("W0001", me.colModel[2]["label"]);
                    return false;
                }

                blnInputFlg = true;
            }
        }

        if (!blnInputFlg) {
            me.setFocus(0, "BUSYO_KB");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }

        //重複ﾁｪｯｸ1
        for (var i = 0; i < me.arrInputDatas.length - 1; i++) {
            if (
                me.arrInputDatas[i]["BUSYO_KB"].trimEnd() != "" &&
                me.arrInputDatas[i]["LINE_NO"].trimEnd() != ""
            ) {
                for (var j = i + 1; j < me.arrInputDatas.length; j++) {
                    if (
                        me.arrInputDatas[i]["BUSYO_KB"] ==
                            me.arrInputDatas[j]["BUSYO_KB"] &&
                        me.arrInputDatas[i]["LINE_NO"] ==
                            me.arrInputDatas[j]["LINE_NO"] &&
                        me.arrInputDatas[i]["EXCEL_LINE_NO"] ==
                            me.arrInputDatas[j]["EXCEL_LINE_NO"]
                    ) {
                        var row = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["BUSYO_KB"] !==
                                    me.arrInputDatas[i]["BUSYO_KB"] ||
                                me.firstData[i]["LINE_NO"] !==
                                    me.arrInputDatas[i]["LINE_NO"] ||
                                me.firstData[i]["EXCEL_LINE_NO"] !==
                                    me.arrInputDatas[i]["EXCEL_LINE_NO"]
                            ) {
                                var row = i;
                            }
                        }

                        me.setFocus(row, "BUSYO_KB");
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "キー項目が重複しています"
                        );
                        return false;
                    }
                }
            }
        }

        var url = me.id + "/fncCheckExist";
        var inputData = {
            inputDatas: me.arrInputDatas,
            intSaveRowCnt: me.intSaveRowCnt,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"]) {
                if (result["data"].length != 0) {
                    me.setFocus(parseInt(result["rowNo"]), "BUSYO_KB");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "重複データが存在しています！"
                    );
                    return false;
                } else if (result["data"].length == 0) {
                    //確認メッセージ
                    me.clsComFnc.MsgBoxBtnFnc.Yes =
                        me.fncDelUpdHYOSANTORIKOMIMST;
                    me.clsComFnc.FncMsgBox("QY010");
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return false;
            }
        };

        me.ajax.send(url, inputData, 0);
        return true;
    };

    me.setFocus = function (rowNum, colNum) {
        $(me.grid_id).jqGrid("setSelection", rowNum, true);

        var ceil = rowNum + "_" + colNum;
        me.clsComFnc.ObjFocus = $("#" + ceil);
        me.clsComFnc.ObjSelect = $("#" + ceil);
    };

    me.fncDelUpdHYOSANTORIKOMIMST = function () {
        var url = me.id + "/fncDelUpdHYOSANTORIKOMIMST";
        var inputData = {
            inputDatas: me.arrInputDatas,
            BUSYO_KB: me.strSaveSRCD,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"]) {
                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.ObjFocus = $(".FrmYosanTorikomiMst.txtBusyoKB");
                me.clsComFnc.FncMsgBox("I0008");

                //画面ｸﾘｱ処理
                $(".FrmYosanTorikomiMst.txtBusyoKB").val("");
                $(me.grid_id).jqGrid("clearGridData");
                $(".FrmYosanTorikomiMst.cmdInsert").button("disable");
                $(".FrmYosanTorikomiMst.cmdAction").button("disable");

                me.strSaveSRCD = "";
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return false;
            }
        };

        me.ajax.send(url, inputData, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmYosanTorikomiMst = new R4.FrmYosanTorikomiMst();
    o_R4_FrmYosanTorikomiMst.load();
});
