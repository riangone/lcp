/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150810           #1985    					   BUG                              Yuanjh
 * 20150820           #2078 					   BUG                              Yuanjh
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmKmkLineMst");

R4.FrmKmkLineMst = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmKmkLineMst";
    me.sys_id = "R4K";
    me.grid_idLine = "#FrmKmkLineMst_sprLine";
    me.grid_id = "#FrmKmkLineMst_sprMeisai";
    me.g_url = "R4K/FrmKmkLineMst/fncKmkLineSelectLine";
    me.sidx = "";
    me.lastsel = 0;
    me.lastRowLineNo = "";
    me.flagBlock = false;
    me.strLineNO = "";
    me.firstData = new Array();

    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: false,
        caption: "",
        multiselectWidth: 40,
    };

    me.option1 = {
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
        TAISK_KB: "",
        CAL_KB: "",
        PRN_KB1: "",
        PRN_KB2: "",
        PRN_KB3: "",
        PRN_KB4: "",
        PRN_KB5: "",
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
            width: 80,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "5",
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
            name: "HIMOK_CD",
            label: "費目コード",
            index: "HIMOK_CD",
            width: 80,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "keyup",
                        fn: function (_e) {
                            if (
                                event.keyCode != 8 &&
                                event.keyCode != 9 &&
                                event.keyCode != 46 &&
                                event.keyCode != 110 &&
                                event.keyCode != 190 &&
                                (event.keyCode < 35 || event.keyCode > 40)
                            ) {
                                if (me.GetByteCount1(this.value)) {
                                    this.value = this.value.replace(
                                        /[^\d\-\a-\z\A-\Z\ \.]/g,
                                        ""
                                    );
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "TAISK_KB",
            label: "貸借区分",
            index: "TAISK_KB",
            width: 65,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "1",
            },
        },
        {
            name: "CAL_KB",
            label: "計算区分",
            index: "CAL_KB",
            width: 65,
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

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "PRN_KB1",
            label: "帳票出力区分1",
            index: "PRN_KB1",
            width: 100,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "1",
            },
        },
        {
            name: "PRN_KB2",
            label: "帳票出力区分2",
            index: "PRN_KB2",
            width: 100,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "1",
            },
        },
        {
            name: "PRN_KB3",
            label: "帳票出力区分3",
            index: "PRN_KB3",
            width: 100,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "1",
            },
        },
        {
            name: "PRN_KB4",
            label: "帳票出力区分4",
            index: "PRN_KB4",
            width: 100,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "1",
            },
        },
        {
            name: "PRN_KB5",
            label: "帳票出力区分5",
            index: "PRN_KB5",
            width: 100,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
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
        id: ".FrmKmkLineMst.cmdCancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKmkLineMst.cmdAction",
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

    $(".FrmKmkLineMst.cmdCancel").click(function () {
        me.flagBlock = false;

        me.strLineNO = $(me.grid_idLine).jqGrid(
            "getCell",
            me.lastRowLineNo,
            "LINE_NO"
        );
        me.fncDispKMKLine(false);

        $(".FrmKmkLineMst.cmdCancel").button("disable");
        $(".FrmKmkLineMst.cmdAction").button("disable");

        $(me.grid_id).closest(".ui-jqgrid").block();
        $(me.grid_idLine).closest(".ui-jqgrid").unblock();
    });

    $(".FrmKmkLineMst.cmdAction").click(function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

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

        $(".FrmKmkLineMst.cmdCancel").button("disable");
        $(".FrmKmkLineMst.cmdAction").button("disable");

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag != "normal") {
                gdmz.common.jqgrid.init(
                    me.grid_id,
                    "",
                    me.colModel,
                    "",
                    me.sidx,
                    me.option1
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 900);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    me.ratio === 1.5 ? 331 : 410
                );

                $(me.grid_id).closest(".ui-jqgrid").block();
                $(me.grid_idLine).closest(".ui-jqgrid").block();

                if (bErrorFlag == "nodata") {
                    me.clsComFnc.FncMsgBox("I0001");
                }
            } else {
                var rowArray = $(me.grid_idLine).jqGrid(
                    "getGridParam",
                    "records"
                );

                if (rowArray > 0) {
                    me.strLineNO = $(me.grid_idLine).jqGrid(
                        "getCell",
                        0,
                        "LINE_NO"
                    );
                    me.fncDispKMKLine(true);

                    me.fncCompleteDealLine();
                }
            }
        };
        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_idLine,
            me.g_url,
            me.colModelLine,
            "",
            me.sidx,
            me.option,
            "",
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_idLine, 110);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_idLine,
            me.ratio === 1.5 ? 331 : 410
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
            $(me.grid_id).closest(".ui-jqgrid").block();

            if (bErrorFlag == "error") {
                if (bLoadFlag) {
                    $(me.grid_idLine).jqGrid("clearGridData");
                    $(me.grid_idLine).closest(".ui-jqgrid").block();
                }
            } else {
                var rowArray = $(me.grid_id).jqGrid("getGridParam", "records");

                for (var i = rowArray; i < 30; i++) {
                    $(me.grid_id).jqGrid("addRowData", i, me.addData);
                }
                me.firstData = $(me.grid_id).jqGrid("getRowData");
                me.fncCompleteDealKamoku();

                if (me.flagBlock) {
                    $(me.grid_id).jqGrid("setSelection", 0, true);

                    $(".FrmKmkLineMst.cmdCancel").button("enable");
                    $(".FrmKmkLineMst.cmdAction").button("enable");

                    $(me.grid_id).closest(".ui-jqgrid").unblock();
                    $(me.grid_idLine).closest(".ui-jqgrid").block();
                }

                if (bLoadFlag) {
                    $(me.grid_idLine).jqGrid("setSelection", 0, true);
                }
            }
        };

        if (bLoadFlag) {
            //スプレッドに取得データをセットする
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                url,
                me.colModel,
                "",
                me.sidx,
                me.option1,
                data,
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id, 900);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id,
                me.ratio === 1.5 ? 331 : 410
            );
            //20150820	Yuanjh ADD S.
            $(me.grid_id).jqGrid("bindKeys");
            //20150820	Yuanjh ADD E.
        } else {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                data,
                me.complete_fun
            );
        }
    };

    me.fncCompleteDealLine = function () {
        $(me.grid_idLine).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                me.flagBlock = true;
                me.lastRowLineNo = rowid;

                me.strLineNO = $(me.grid_idLine).jqGrid(
                    "getCell",
                    rowid,
                    "LINE_NO"
                );

                if (typeof e != "undefined") {
                    me.fncDispKMKLine(false);
                }
            },
        });
    };

    me.fncCompleteDealKamoku = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;

                    //ヘッダークリック以外
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
                        //ヘッダークリック
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

                $(".numeric").numeric({
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

    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);

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
        var getDataID = $(me.grid_id).jqGrid("getDataIDs");

        for (var i = parseInt(rowID); i < getDataID.length - 1; i++) {
            var rowData = $(me.grid_id).jqGrid("getRowData", i + 1);
            $(me.grid_id).jqGrid("setRowData", i, rowData);
        }

        $(me.grid_id).jqGrid("setRowData", getDataID.length - 1, me.addData);
        $(me.grid_id).jqGrid("setSelection", rowID, true);
        if (me.firstData.length - 1 >= parseInt(rowID)) {
            me.firstData.splice(parseInt(rowID), 1);
        }
    };

    me.fncGetInputData = function () {
        var arr = new Array();
        var data = $(me.grid_id).jqGrid("getDataIDs");

        for (key in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[key]);

            if (
                rowData["KAMOK_CD"].trimEnd() != "" ||
                rowData["HIMOK_CD"].trimEnd() != "" ||
                rowData["TAISK_KB"].trimEnd() != "" ||
                rowData["CAL_KB"].trimEnd() != "" ||
                rowData["PRN_KB1"].trimEnd() != "" ||
                rowData["PRN_KB2"].trimEnd() != "" ||
                rowData["PRN_KB3"].trimEnd() != "" ||
                rowData["PRN_KB4"].trimEnd() != "" ||
                rowData["PRN_KB5"].trimEnd() != ""
            ) {
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
        var data = $(me.grid_id).jqGrid("getDataIDs");

        for (rowID in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[rowID]);

            //どれか一列でも入力されていた場合
            if (
                rowData["KAMOK_CD"].trimEnd() != "" ||
                rowData["HIMOK_CD"].trimEnd() != "" ||
                rowData["TAISK_KB"].trimEnd() != "" ||
                rowData["CAL_KB"].trimEnd() != "" ||
                rowData["PRN_KB1"].trimEnd() != "" ||
                rowData["PRN_KB2"].trimEnd() != "" ||
                rowData["PRN_KB3"].trimEnd() != "" ||
                rowData["PRN_KB4"].trimEnd() != "" ||
                rowData["PRN_KB5"].trimEnd() != ""
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
                            me.colModel[iColNo]["label"].replace(/<br \/>/g, "")
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

                if (rowData["HIMOK_CD"].trimEnd() == "") {
                    me.setFocus(rowID, "HIMOK_CD");
                    me.clsComFnc.FncMsgBox("W0001", "費目コード");
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
                                    arrCheckData[i]["KAMOK_CD"].trimEnd() ||
                                me.firstData[i]["HIMOK_CD"] !==
                                    arrCheckData[i]["HIMOK_CD"].trimEnd()
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
        $(me.grid_id).jqGrid("setSelection", rowNum);

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
                $(".FrmKmkLineMst.cmdCancel").button("disable");
                $(".FrmKmkLineMst.cmdAction").button("disable");

                $(me.grid_id).closest(".ui-jqgrid").block();
                $(me.grid_idLine).closest(".ui-jqgrid").unblock();
                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.FncMsgBox("I0008");
            }
        };
        me.ajax.send(url, sendData, 0);
    };

    me.inputReplace = function (targetVal, inputLength, keycode) {
        var inputValue = $(targetVal).val();

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
    var o_R4_FrmKmkLineMst = new R4.FrmKmkLineMst();
    o_R4_FrmKmkLineMst.load();
});
