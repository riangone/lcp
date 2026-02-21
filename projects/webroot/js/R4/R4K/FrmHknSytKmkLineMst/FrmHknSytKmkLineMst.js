/*
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150806           #1994 1993				    BUG                              Yuanjh
 * 20150820           #2078						    BUG                              Yuanjh
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmHknSytKmkLineMst");

R4.FrmHknSytKmkLineMst = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmHknSytKmkLineMst";
    me.grid_Line = "#FrmHknSytKmkLineMst_sprLine";
    me.grid_Kamoku = "#FrmHknSytKmkLineMst_sprMeisai";
    me.lastsel = 0;
    me.strLineNO = "";
    me.lastRowLineNo = "";
    me.flagBlock = false;
    me.firstData = new Array();

    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: false,
        caption: "",
        multiselectWidth: 40,
    };

    me.optionKamoku = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };

    me.addData = {
        KAMOK_CD: "",
        HIMOK_CD: "",
        CAL_KB: "",
        CREATE_DATE: "",
    };

    me.colModelLine = [
        {
            name: "LINE_NO",
            label: "ラインNo.",
            index: "LINE_NO",
            width: 80,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];

    me.colModel = [
        {
            name: "KAMOK_CD",
            label: "科目コード",
            index: "KAMOK_CD",
            width: 90,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                //---20150818	Yuanjh modify  S.
                //class : 'numeric',
                //---20150818	Yuanjh modify  E.
                maxlength: "5",
            },
        },
        {
            name: "HIMOK_CD",
            label: "費目コード",
            index: "HIMOK_CD",
            width: 90,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                //---20150818	Yuanjh modify  S.
                //class : 'numeric',
                //---20150818	Yuanjh modify  E.
                maxlength: "3",
            },
        },
        {
            name: "CAL_KB",
            label: "計算区分",
            index: "CAL_KB",
            width: 90,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "2",
                dataEvents: [
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
                            var key = e.charCode || e.keyCode;
                            if (key != 8) {
                                if (inputValue == "-") {
                                    $(e.target).val("-0");
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmHknSytKmkLineMst.cmdCancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHknSytKmkLineMst.cmdAction",
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
    $(".FrmHknSytKmkLineMst.cmdCancel").click(function () {
        me.flagBlock = false;
        me.strLineNO = $(me.grid_Line).jqGrid(
            "getCell",
            me.lastRowLineNo,
            "LINE_NO"
        );

        $(me.grid_Kamoku).closest(".ui-jqgrid").block();
        $(".FrmHknSytKmkLineMst.cmdCancel").button("disable");
        $(".FrmHknSytKmkLineMst.cmdAction").button("disable");

        me.fncDispKMKLine(false);
    });

    $(".FrmHknSytKmkLineMst.cmdAction").click(function () {
        $(me.grid_Kamoku).jqGrid("saveRow", me.lastsel, null, "clientArray");

        //入力チェック
        if (me.fncInputChk() == false) {
            return;
        }
        //確認メッセージ
        else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataKmk;
            me.clsComFnc.FncMsgBox("QY010");
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

        $(".FrmHknSytKmkLineMst.cmdCancel").button("disable");
        $(".FrmHknSytKmkLineMst.cmdAction").button("disable");

        var url = "R4K/FrmHknSytKmkLineMst/fncKmkLineSelectLine";

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag != "normal") {
                gdmz.common.jqgrid.init(
                    me.grid_Kamoku,
                    "",
                    me.colModel,
                    "",
                    "",
                    me.optionKamoku
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_Kamoku, 360);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_Kamoku,
                    me.ratio === 1.5 ? 352 : 410
                );

                $(me.grid_Line).closest(".ui-jqgrid").block();
                $(me.grid_Kamoku).closest(".ui-jqgrid").block();

                //ラインマスタにデータが存在しない場合
                if (bErrorFlag == "nodata") {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                }
            } else {
                me.strLineNO = $(me.grid_Line).jqGrid("getCell", 0, "LINE_NO");
                $(me.grid_Line).jqGrid("setSelection", 0, true);

                me.fncDispKMKLine(true);
                me.fncCompleteDealLine();
            }
        };
        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_Line,
            url,
            me.colModelLine,
            "",
            "",
            me.option,
            "",
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_Line, 110);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_Line,
            me.ratio === 1.5 ? 352 : 410
        );
    };

    // '**********************************************************************
    // '処理概要：科目jqGridのデータを表示する
    // '**********************************************************************
    me.fncDispKMKLine = function (bLoadFlag) {
        var url = me.id + "/fncKmkLineMstSelect";
        var data = {
            LINE_NO: me.strLineNO,
        };

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "error") {
                if (bLoadFlag) {
                    $(me.grid_Line).jqGrid("clearGridData");
                    $(me.grid_Line).closest(".ui-jqgrid").block();
                    $(me.grid_Kamoku).closest(".ui-jqgrid").block();
                } else if (me.flagBlock) {
                    me.flagBlock = false;
                    $(me.grid_Line).closest(".ui-jqgrid").unblock();
                } else {
                    $(me.grid_Kamoku).closest(".ui-jqgrid").unblock();
                    $(".FrmHknSytKmkLineMst.cmdCancel").button("enable");
                    $(".FrmHknSytKmkLineMst.cmdAction").button("enable");
                }
            } else {
                me.firstData = $(me.grid_Kamoku).jqGrid("getRowData");
                var rowArray = $(me.grid_Kamoku).jqGrid(
                    "getGridParam",
                    "records"
                );

                for (var i = rowArray; i < 30; i++) {
                    $(me.grid_Kamoku).jqGrid("addRowData", i, me.addData);
                }

                if (me.flagBlock) {
                    $(me.grid_Kamoku).jqGrid("setSelection", 0, true);

                    $(".FrmHknSytKmkLineMst.cmdCancel").button("enable");
                    $(".FrmHknSytKmkLineMst.cmdAction").button("enable");

                    $(me.grid_Kamoku).closest(".ui-jqgrid").unblock();
                } else if (bLoadFlag) {
                    $(me.grid_Kamoku).closest(".ui-jqgrid").block();
                } else {
                    $(me.grid_Line).closest(".ui-jqgrid").unblock();
                }

                me.fncCompleteDealKamoku();
            }
        };

        if (bLoadFlag) {
            //スプレッドに取得データをセットする
            gdmz.common.jqgrid.showWithMesg(
                me.grid_Kamoku,
                url,
                me.colModel,
                "",
                "",
                me.optionKamoku,
                data,
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_Kamoku, 360);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_Kamoku,
                me.ratio === 1.5 ? 352 : 410
            );
            //20150820	Yuanjh ADD S.
            $(me.grid_Kamoku).jqGrid("bindKeys");
            //20150820	Yuanjh ADD E.
        } else {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_Kamoku,
                data,
                me.complete_fun
            );
        }
    };

    me.fncCompleteDealLine = function () {
        $(me.grid_Line).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, _e) {
                me.flagBlock = true;
                me.lastRowLineNo = rowid;
                me.strLineNO = $(me.grid_Line).jqGrid(
                    "getCell",
                    rowid,
                    "LINE_NO"
                );

                $(me.grid_Line).closest(".ui-jqgrid").block();
                me.fncDispKMKLine(false);
            },
        });
    };

    me.fncCompleteDealKamoku = function () {
        $(me.grid_Kamoku).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;

                    //ヘッダークリック以外
                    if (cellIndex != 0) {
                        if (rowid && rowid !== me.lastsel) {
                            $(me.grid_Kamoku).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            me.lastsel = rowid;
                        }

                        $(me.grid_Kamoku).jqGrid("editRow", rowid, {
                            keys: true,
                            focusField: cellIndex,
                        });
                    } else {
                        //ヘッダークリック
                        $(me.grid_Kamoku).jqGrid(
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
                    if (rowid && rowid !== me.lastsel) {
                        $(me.grid_Kamoku).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        me.lastsel = rowid;
                    }

                    $(me.grid_Kamoku).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: false,
                    });
                }

                $(".numeric").numeric({
                    decimal: false,
                    negative: true,
                });
                gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_Kamoku,
                    e,
                    me.lastsel
                );
            },
        });
    };

    me.GetByteCount = function (str) {
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

    me.delRowData = function () {
        var rowID = $(me.grid_Kamoku).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_Kamoku).jqGrid("getRowData", rowID);

        var url = me.id + "/frmKmkDeleteRow";
        var data = {
            KAMOK_CD: rowData["KAMOK_CD"],
            HIMOK_CD: rowData["HIMOK_CD"],
            LINE_NO: me.strLineNO,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                me.fncDeleteRowContent(rowID);
            } else if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.fncDeleteRowContent = function (rowID) {
        var getDataID = $(me.grid_Kamoku).jqGrid("getDataIDs");

        for (var i = parseInt(rowID); i < getDataID.length - 1; i++) {
            //選択行の内容変更する
            var rowData = $(me.grid_Kamoku).jqGrid("getRowData", i + 1);
            $(me.grid_Kamoku).jqGrid("setRowData", i, rowData);
        }

        //選択行の内容クリアする
        $(me.grid_Kamoku).jqGrid(
            "setRowData",
            getDataID.length - 1,
            me.addData
        );

        //選択状態設定する
        $(me.grid_Kamoku).jqGrid("setSelection", 0, true);
        if (me.firstData.length - 1 >= parseInt(rowID)) {
            me.firstData.splice(parseInt(rowID), 1);
        }
    };

    me.fncGetInputData = function () {
        var arr = new Array();
        var data = $(me.grid_Kamoku).jqGrid("getDataIDs");

        for (key in data) {
            var rowData = $(me.grid_Kamoku).jqGrid("getRowData", data[key]);

            if (rowData["KAMOK_CD"].trimEnd() != "") {
                arr.push(rowData);
            }
        }

        return arr;
    };

    //'**********************************************************************
    // '処 理 名：スプレッドの入力チェック
    // '関 数 名：fncInputChk
    // '引    数：lntTeika  (I)定価合計
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：スプレッドの入力チェック
    // '**********************************************************************
    me.fncInputChk = function () {
        var intRtn = 0;
        var blnInputFlg = false;
        var arrCheckData = new Array();
        var data = $(me.grid_Kamoku).jqGrid("getDataIDs");

        for (rowID in data) {
            var rowData = $(me.grid_Kamoku).jqGrid("getRowData", data[rowID]);

            //どれか一列でも入力されていた場合
            if (
                rowData["KAMOK_CD"].trimEnd() != "" ||
                rowData["HIMOK_CD"].trimEnd() != "" ||
                rowData["CAL_KB"].trimEnd() != ""
            ) {
                var iColNo = 0;

                //入力チェック
                for (colID in rowData) {
                    switch (colID) {
                        //3
                        case "CAL_KB":
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[iColNo]["editoptions"]["maxlength"]
                            );

                            if (
                                me.clsComFnc.FncNv(rowData[colID]).trimEnd() !=
                                ""
                            ) {
                                if (
                                    !(
                                        me.clsComFnc.FncNz(rowData[colID]) ==
                                            1 ||
                                        me.clsComFnc.FncNz(rowData[colID]) == -1
                                    )
                                ) {
                                    intRtn = -2;
                                }
                            }
                            break;
                        //else
                        case "CREATE_DATE":
                            break;
                        default:
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR2,
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
                if (rowData["KAMOK_CD"].trimEnd() == "") {
                    me.setFocus(rowID, "KAMOK_CD");
                    me.clsComFnc.FncMsgBox("W0001", "科目コード");
                    return false;
                }

                blnInputFlg = true;
            }

            var tmpAttr = {
                KAMOK_CD: "",
                HIMOK_CD: "",
            };

            tmpAttr["KAMOK_CD"] = rowData["KAMOK_CD"];
            tmpAttr["HIMOK_CD"] = rowData["HIMOK_CD"];

            arrCheckData.push(tmpAttr);
        }

        if (!blnInputFlg) {
            me.setFocus(0, "KAMOK_CD");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }

        //重複ﾁｪｯｸ
        for (var i = 0; i < arrCheckData.length - 1; i++) {
            for (var j = i + 1; j < arrCheckData.length; j++) {
                if (
                    arrCheckData[i]["KAMOK_CD"].trimEnd() != "" &&
                    arrCheckData[i]["HIMOK_CD"].trimEnd() != ""
                ) {
                    if (
                        arrCheckData[i]["KAMOK_CD"].trimEnd() ==
                            arrCheckData[j]["KAMOK_CD"].trimEnd() &&
                        arrCheckData[i]["HIMOK_CD"].trimEnd() ==
                            arrCheckData[j]["HIMOK_CD"].trimEnd()
                    ) {
                        var row = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["KAMOK_CD"] !==
                                    arrCheckData[i]["KAMOK_CD"] ||
                                me.firstData[i]["HIMOK_CD"] !==
                                    arrCheckData[i]["HIMOK_CD"]
                            ) {
                                var row = i;
                            }
                        }
                        me.setFocus(row, "KAMOK_CD");
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

    me.setFocus = function (rowID, colID) {
        var rowNum = parseInt(rowID);
        $(me.grid_Kamoku).jqGrid("setSelection", rowNum);

        var ceil = rowNum + "_" + colID;
        me.clsComFnc.ObjFocus = $("#" + ceil);
        me.clsComFnc.ObjSelect = $("#" + ceil);
    };

    me.fncDeleteUpdataKmk = function () {
        var arrInputData = me.fncGetInputData();
        var url = me.id + "/fncKmkLineDelUpd";
        var sendData = {
            inputData: arrInputData,
            LINE_NO: me.strLineNO,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                $(".FrmHknSytKmkLineMst.cmdCancel").button("disable");
                $(".FrmHknSytKmkLineMst.cmdAction").button("disable");

                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.FncMsgBox("I0008");
                $(me.grid_Line).closest(".ui-jqgrid").unblock();
                $(me.grid_Kamoku).closest(".ui-jqgrid").block();
            }
        };
        me.ajax.send(url, sendData, 0);
    };

    me.inputReplace = function (targetVal, inputLength, keycode) {
        var inputValue = $(targetVal).val();
        //20150811  Yuanjh add S.
        if (inputValue >= 0 && inputValue <= 9) {
            inputValue = "";
            $(targetVal).val("");
        }
        //20150811  Yuanjh add E.
        if (inputValue == "" && keycode == 45) {
            $(targetVal).val("-0");
            return false;
        } else if (inputValue.indexOf("-") == -1) {
            if (keycode == 45 && inputValue.length <= inputLength) {
                $(targetVal).val("-" + inputValue);
                return false;
            } else if (inputValue.length == inputLength) {
                if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
                    inputValue =
                        inputValue.substr(0, 1) + (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                } else if (
                    inputValue == "0" &&
                    keycode >= 49 &&
                    keycode <= 57
                ) {
                    inputValue = (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                }

                return false;
            }
        } else {
            if (keycode == 45) {
                $(targetVal).val(inputValue.substr(1));
                return false;
            } else if (keycode >= 48 && keycode <= 57 && inputValue == "-0") {
                $(targetVal).val(
                    inputValue.substr(0, 1) + (keycode - 48).toString()
                );
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
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmHknSytKmkLineMst = new R4.FrmHknSytKmkLineMst();
    o_R4_FrmHknSytKmkLineMst.load();
});
