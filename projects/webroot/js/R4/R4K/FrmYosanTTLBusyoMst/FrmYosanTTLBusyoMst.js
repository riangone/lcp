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
Namespace.register("R4.FrmYosanTTLBusyoMst");

R4.FrmYosanTTLBusyoMst = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmYosanTTLBusyoMst";
    me.grid_Line = "#FrmYosanTTLBusyoMst_sprLine";
    me.grid_Meisai = "#FrmYosanTTLBusyoMst_sprMeisai";
    me.lastsel = 0;
    me.lastSelectCD = "";
    me.flagBlock = false;
    me.strBusyoCD = "";
    me.arrInputData = new Array();
    me.firstData = new Array();

    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };

    me.addDataMeisai = {
        BUSYO_CD: "",
        BUSYO_NM: "",
        CREATE_DATE: "",
    };

    me.colModelLine = [
        {
            name: "BUSYO_CD",
            label: "集計部署ｺｰﾄﾞ",
            index: "BUSYO_CD",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 240,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];

    me.colModelMeisai = [
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 80,
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "3",
            },
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 240,
            sortable: false,
            editable: false,
            align: "left",
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
        id: ".FrmYosanTTLBusyoMst.cmdCancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmYosanTTLBusyoMst.cmdAction",
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
    // '処理概要：ｷｬﾝｾﾙﾎﾞﾀﾝｸﾘｯｸ
    // '**********************************************************************
    $(".FrmYosanTTLBusyoMst.cmdCancel").click(function () {
        me.flagBlock = false;
        me.strBusyoCD = $(me.grid_Line).jqGrid(
            "getCell",
            me.lastRowBusyoCD,
            "BUSYO_CD"
        );

        $(me.grid_Meisai).closest(".ui-jqgrid").block();
        $(".FrmYosanTTLBusyoMst.cmdCancel").button("disable");
        $(".FrmYosanTTLBusyoMst.cmdAction").button("disable");

        me.fncDispTTLBUSYO(false);
    });

    // '**********************************************************************
    // '処理概要：更新ボタン押下時
    // '**********************************************************************
    $(".FrmYosanTTLBusyoMst.cmdAction").click(function () {
        $(me.grid_Meisai).jqGrid("saveRow", me.lastsel, null, "clientArray");

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

        $(".FrmYosanTTLBusyoMst.cmdCancel").button("disable");
        $(".FrmYosanTTLBusyoMst.cmdAction").button("disable");

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag != "normal") {
                gdmz.common.jqgrid.init(
                    me.grid_Meisai,
                    "",
                    me.colModelMeisai,
                    "",
                    "",
                    me.option
                );

                gdmz.common.jqgrid.set_grid_width(me.grid_Meisai, 400);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_Meisai,
                    me.ratio === 1.5 ? 311 : 390
                );

                $(me.grid_Line).closest(".ui-jqgrid").block();
                $(me.grid_Meisai).closest(".ui-jqgrid").block();

                //部署マスタにデータが存在しない場合
                if (bErrorFlag == "nodata") {
                    me.clsComFnc.FncMsgBox("I0001");
                }
            } else {
                //１行目を選択状態にする
                $(me.grid_Line).jqGrid("setSelection", 0, true);

                me.strBusyoCD = $(me.grid_Line).jqGrid(
                    "getCell",
                    0,
                    "BUSYO_CD"
                );
                me.fncDispTTLBUSYO(true);
                me.fncCompleteDealLine();
            }
        };
        //スプレッドに取得データをセットする
        //部署マスタからのデータ
        var g_url = "R4K/FrmYosanTTLBusyoMst/fncBusyoMstSelect";
        gdmz.common.jqgrid.showWithMesg(
            me.grid_Line,
            g_url,
            me.colModelLine,
            "",
            "",
            me.option,
            "",
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_Line, 420);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_Line,
            me.ratio === 1.5 ? 311 : 390
        );
    };

    // '**********************************************************************
    // '処 理 名：集計部署ﾏｽﾀ表示
    // '関 数 名：fncDispTTLBUSYO
    // '引    数：無し
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：集計部署ﾏｽﾀ表示
    // '**********************************************************************
    me.fncDispTTLBUSYO = function (bLoadFlag) {
        var url = me.id + "/fncYOSANTTLBusyoMstSelect";
        var data = {
            Busyo_CD: me.strBusyoCD,
        };

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "error") {
                if (bLoadFlag) {
                    $(me.grid_Line).jqGrid("clearGridData");
                    $(me.grid_Line).closest(".ui-jqgrid").block();
                    $(me.grid_Meisai).closest(".ui-jqgrid").block();
                    return;
                } else if (me.flagBlock) {
                    me.flagBlock = false;
                    $(me.grid_Line).closest(".ui-jqgrid").unblock();
                } else {
                    $(me.grid_Meisai).closest(".ui-jqgrid").unblock();

                    $(".FrmYosanTTLBusyoMst.cmdCancel").button("enable");

                    $(".FrmYosanTTLBusyoMst.cmdAction").button("enable");
                }
            } else {
                me.firstData = $(me.grid_Meisai).jqGrid("getRowData");
                var rowArray = $(me.grid_Meisai).jqGrid(
                    "getGridParam",
                    "records"
                );

                for (var i = rowArray; i < 100; i++) {
                    $(me.grid_Meisai).jqGrid("addRowData", i, me.addDataMeisai);
                }

                if (bLoadFlag) {
                    $(me.grid_Meisai).closest(".ui-jqgrid").block();
                } else if (me.flagBlock) {
                    $(me.grid_Meisai).closest(".ui-jqgrid").unblock();

                    $(".FrmYosanTTLBusyoMst.cmdCancel").button("enable");
                    $(".FrmYosanTTLBusyoMst.cmdCancel").trigger("focus");
                    $(".FrmYosanTTLBusyoMst.cmdAction").button("enable");
                } else {
                    $(me.grid_Line).closest(".ui-jqgrid").unblock();
                }

                me.fncCompleteDealBUSYO();
            }
        };

        if (bLoadFlag) {
            //スプレッドに取得データをセットする
            //集計部署マスタからのデータ
            gdmz.common.jqgrid.showWithMesg(
                me.grid_Meisai,
                url,
                me.colModelMeisai,
                "",
                "",
                me.option,
                data,
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_Meisai, 400);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_Meisai,
                me.ratio === 1.5 ? 311 : 390
            );
            //20150820	Yuanjh ADD S.
            $(me.grid_Meisai).jqGrid("bindKeys");
            //20150820	Yuanjh ADD E.
        } else {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_Meisai,
                data,
                me.complete_fun
            );
        }
    };

    // '**********************************************************************
    // '処理概要：スプレッドセルクリック
    // '**********************************************************************
    me.fncCompleteDealLine = function () {
        $(me.grid_Line).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, _e) {
                me.flagBlock = true;
                me.lastRowBusyoCD = rowid;
                me.strBusyoCD = $(me.grid_Line).jqGrid(
                    "getCell",
                    rowid,
                    "BUSYO_CD"
                );

                $(me.grid_Line).closest(".ui-jqgrid").block();

                me.fncDispTTLBUSYO(false);
            },
        });
    };

    // '**********************************************************************
    // '処理概要：スプレッドセルクリック
    // '**********************************************************************
    me.fncCompleteDealBUSYO = function () {
        $(me.grid_Meisai).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;

                    //ヘッダークリック以外
                    if (cellIndex != 0) {
                        if (rowid && rowid !== me.lastsel) {
                            $(me.grid_Meisai).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );

                            if (rowid != me.lastsel) {
                                me.selectBusyoNM(me.lastsel, me.grid_Meisai);
                            }

                            me.lastsel = rowid;
                        }

                        $(me.grid_Meisai).jqGrid("editRow", rowid, {
                            keys: true,
                            focusField: cellIndex,
                        });
                    } else {
                        //ヘッダークリック
                        $(me.grid_Meisai).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );

                        //削除確認メッセージを表示する
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowDataMeisai;
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
                        $(me.grid_Meisai).jqGrid("saveRow", me.lastsel);

                        if (rowid != me.lastsel) {
                            me.selectBusyoNM(me.lastsel, me.grid_Meisai);
                        }

                        me.lastsel = rowid;
                    }

                    $(me.grid_Meisai).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: false,
                    });
                }

                $(".numeric").numeric({
                    decimal: false,
                    negative: true,
                });

                gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_Meisai,
                    e,
                    me.lastsel
                );
            },
        });
    };

    me.selectBusyoNM = function (rowID, gridNM) {
        var rowData = $(gridNM).jqGrid("getRowData", rowID);
        var strBusyoSelect = rowData["BUSYO_CD"];

        if (me.clsComFnc.FncNv(strBusyoSelect) == "") {
            $(gridNM).jqGrid("setRowData", rowID, me.addDataMeisai);
        } else {
            //入力されている場合
            var url = me.id + "/fncBusyoNmSelect";
            var data = {
                BUSYO_CD: strBusyoSelect,
            };

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["data"].length > 0) {
                    $(gridNM).jqGrid(
                        "setCell",
                        rowID,
                        "BUSYO_NM",
                        result["data"][0]["BUSYO_NM"]
                    );
                } else if (result["data"].length <= 0) {
                    $(gridNM).jqGrid("setRowData", rowID, me.addDataMeisai);
                    $(gridNM).jqGrid(
                        "setCell",
                        rowID,
                        "BUSYO_CD",
                        strBusyoSelect
                    );
                }

                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            me.ajax.send(url, data, 0);
        }
    };

    me.delRowDataMeisai = function () {
        var rowID = $(me.grid_Meisai).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_Meisai).jqGrid("getRowData", rowID);

        if (rowData["BUSYO_CD"] == "") {
            //行削除を行う
            me.delRowDataContent(rowID);
            return;
        } else {
            var url = me.id + "/fncDeleteRow";
            var data = {
                BUSYO_CD: rowData["BUSYO_CD"],
                TOTAL_BUSYO_CD: me.strBusyoCD,
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
        }
    };

    me.delRowDataContent = function (rowID) {
        var getDataID = $(me.grid_Meisai).jqGrid("getDataIDs");

        for (var i = parseInt(rowID); i < getDataID.length - 1; i++) {
            var rowData = $(me.grid_Meisai).jqGrid("getRowData", i + 1);
            $(me.grid_Meisai).jqGrid("setRowData", i, rowData);
        }

        $(me.grid_Meisai).jqGrid(
            "setRowData",
            getDataID.length - 1,
            me.addDataMeisai
        );
        $(me.grid_Meisai).jqGrid("setSelection", 0, true);
        if (me.firstData.length - 1 >= parseInt(rowID)) {
            me.firstData.splice(parseInt(rowID), 1);
        }
    };

    // '**********************************************************************
    // '処 理 名：スプレッドの入力チェック
    // '関 数 名：fncInputChk
    // '引    数：無し
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：スプレッドの入力チェック
    // '**********************************************************************
    me.fncInputChk = function () {
        var blnInputFlg = false;
        me.arrInputData = new Array();
        var data = $(me.grid_Meisai).jqGrid("getDataIDs");

        for (rowID in data) {
            var rowData = $(me.grid_Meisai).jqGrid("getRowData", data[rowID]);

            //どれか一列でも入力されていた場合
            if (rowData["BUSYO_CD"].trimEnd() != "") {
                intRtn = me.clsComFnc.FncSprCheck(
                    rowData["BUSYO_CD"],
                    0,
                    me.clsComFnc.INPUTTYPE.CHAR2,
                    me.colModelMeisai[0]["editoptions"]["maxlength"]
                );

                if (intRtn != 0) {
                    me.setFocus(rowID, "BUSYO_CD");
                    me.clsComFnc.FncMsgBox(
                        "W000" + intRtn * -1,
                        me.colModelMeisai[0]["label"]
                    );
                    return false;
                }

                //キー項目の必須ﾁｪｯｸ
                if (rowData["BUSYO_CD"].trimEnd() == "") {
                    me.setFocus(rowID, "BUSYO_CD");
                    me.clsComFnc.FncMsgBox("W0001", "部署コード");
                    return false;
                }

                blnInputFlg = true;
            }

            var tmpAttr = {
                BUSYO_CD: "",
                BUSYO_NM: "",
                CREATE_DATE: "",
            };

            tmpAttr["BUSYO_CD"] = rowData["BUSYO_CD"];
            tmpAttr["BUSYO_NM"] = rowData["BUSYO_NM"];
            tmpAttr["CREATE_DATE"] = rowData["CREATE_DATE"];

            me.arrInputData.push(tmpAttr);
        }

        if (!blnInputFlg) {
            me.setFocus(0, "BUSYO_CD");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }

        //重複ﾁｪｯｸ
        for (var i = 0; i < me.arrInputData.length - 1; i++) {
            for (var j = i + 1; j < me.arrInputData.length; j++) {
                if (me.arrInputData[i]["BUSYO_CD"].trimEnd() != "") {
                    if (
                        me.arrInputData[i]["BUSYO_CD"] ==
                        me.arrInputData[j]["BUSYO_CD"]
                    ) {
                        var row = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["BUSYO_CD"] !==
                                me.arrInputData[i]["BUSYO_CD"]
                            ) {
                                var row = i;
                            }
                        }
                        me.setFocus(row, "BUSYO_CD");
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "キー項目が重複しています"
                        );
                        return false;
                    }
                }
            }
        }

        //部署ﾏｽﾀの存在チェック
        var url = me.id + "/fncBusyoNmCheck";
        var busyoData = {
            busyoData: me.arrInputData,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    me.setFocus(result["rowNO"], "BUSYO_CD");
                    me.clsComFnc.FncMsgBox("W0007", "部署");
                } else {
                    //確認メッセージ
                    me.clsComFnc.MsgBoxBtnFnc.Yes =
                        me.fncDeleteUpdataYOSANTTLBusyo;
                    me.clsComFnc.FncMsgBox("QY010");
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return false;
            }
        };

        me.ajax.send(url, busyoData, 0);
    };

    me.setFocus = function (rowNum, colNum) {
        var rowID = parseInt(rowNum);
        $(me.grid_Meisai).jqGrid("setSelection", rowID);

        var ceil = rowID + "_" + colNum;
        me.clsComFnc.ObjFocus = $("#" + ceil);
        me.clsComFnc.ObjSelect = $("#" + ceil);
    };

    me.fncDeleteUpdataYOSANTTLBusyo = function () {
        var url = me.id + "/fncDelUpdYOSANTTLBusyo";
        var sendData = {
            INPUT_DATA: me.arrInputData,
            TOTAL_BUSYO_CD: me.strBusyoCD,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                me.flagBlock = false;

                $(".FrmYosanTTLBusyoMst.cmdCancel").button("disable");
                $(".FrmYosanTTLBusyoMst.cmdAction").button("disable");

                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.FncMsgBox("I0008");

                $(me.grid_Line).closest(".ui-jqgrid").unblock();
                $(me.grid_Meisai).closest(".ui-jqgrid").block();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, sendData, 0);
    };

    return me;
};

$(function () {
    var o_R4_FrmYosanTTLBusyoMst = new R4.FrmYosanTTLBusyoMst();
    o_R4_FrmYosanTTLBusyoMst.load();
});
