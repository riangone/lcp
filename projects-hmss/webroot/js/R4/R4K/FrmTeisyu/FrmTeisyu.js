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
 * 20210708           #####					   	   BUG                              Yin
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmTeisyu");

R4.FrmTeisyu = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmTeisyu";
    me.grid_id = "#FrmTeisyu_sprList";
    me.lastsel = 0;
    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.addData = {
        SYAIN_NO: "",
        SYAIN_NM: "",
        BUSYO_NM: "",
        TEISYU: "",
        HOYU: "",
        CREATE_DATE: "",
    };
    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "社員No.",
            index: "SYAIN_NO",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "SYAIN_NM",
            label: "名前",
            index: "SYAIN_NM",
            width: 140,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 260,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "TEISYU",
            label: "定時間月収",
            index: "TEISYU",
            width: 100,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "3",
            },
        },
        {
            name: "HOYU",
            label: "保有台数",
            index: "HOYU",
            width: 100,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "4",
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
        id: ".FrmTeisyu.cmdSearchBs",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmTeisyu.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmTeisyu.cmdAction",
        type: "button",
        handle: "",
    });

    // 20210708 YIN INS S
    me.controls.push({
        id: ".FrmTeisyu.cmdChange",
        type: "button",
        handle: "",
    });
    // 20210708 YIN INS E

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
    // '処理概要：検索(小)ボタン押下時
    // '**********************************************************************
    $(".FrmTeisyu.cmdSearchBs").click(function () {
        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($("#FrmTeisyu"));
        $("<div></div>").attr("id", "BUSYOCD").insertAfter($("#FrmTeisyu"));
        $("<div></div>").attr("id", "BUSYONM").insertAfter($("#FrmTeisyu"));
        $("<div></div>").attr("id", "RtnCD").insertAfter($("#FrmTeisyu"));

        $("<div></div>").attr("id", "BUSYOCD").hide();
        $("<div></div>").attr("id", "BUSYONM").hide();
        $("<div></div>").attr("id", "RtnCD").hide();

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 554 : 680,
            width: 550,
            resizable: false,
            close: function () {
                var flgRtnCD = $("#RtnCD").html();

                if (flgRtnCD == 1) {
                    $(".FrmTeisyu.txtBusyoCD").val($("#BUSYOCD").html());
                    $(".FrmTeisyu.lblBusyoNM").val($("#BUSYONM").html());
                    $(".FrmTeisyu.cmdSearch").trigger("focus");
                } else {
                    $(".FrmTeisyu.txtBusyoCD").trigger("focus");
                }

                $("#RtnCD").remove();
                $("#BUSYONM").remove();
                $("#BUSYOCD").remove();
                $("#FrmBusyoSearchDialogDiv").remove();
            },
        });

        var frmId = "FrmBusyoSearch";
        var url = "R4K/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);

            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
    });
    // '**********************************************************************
    // '処理概要：検索(大)ボタン押下時
    // '**********************************************************************
    $(".FrmTeisyu.cmdSearch").click(function () {
        $(".FrmTeisyu.cmdAction").button("disable");
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        $(me.grid_id).closest(".ui-jqgrid").block();

        var data = {
            Busyo_CD: $(".FrmTeisyu.txtBusyoCD").val(),
        };

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag != "normal") {
                $(me.grid_id).jqGrid("clearGridData");

                //社員マスタにデータが存在
                if (bErrorFlag == "nodata") {
                    me.clsComFnc.FncMsgBox("I0001");
                }
            } else {
                $(".FrmTeisyu.cmdAction").button("enable");

                //20210708 YIN INS S
                $(".FrmTeisyu.cmdChange").button("enable");
                $(".FrmTeisyu.txtBusyoCD").attr("disabled", "disabled");
                $(".FrmTeisyu.cmdSearch").button("disable");
                $(".FrmTeisyu.cmdSearchBs").button("disable");
                //20210708 YIN INS E

                //スプレッドに取得データをセットする
                me.fncCompleteDeal();

                //１行目を選択状態にする
                $(me.grid_id).jqGrid("setSelection", 0);
                $(me.grid_id).closest(".ui-jqgrid").unblock();
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    });

    // '**********************************************************************
    // '処 理 名：名称取得
    // '関 数 名：txtBusyoCDValidating
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：部署名称を取得する
    // '**********************************************************************
    $(".FrmTeisyu.txtBusyoCD").on("blur", function () {
        $(".FrmTeisyu.lblBusyoNM").val("");

        if ($(".FrmTeisyu.txtBusyoCD").val().trimEnd() != "") {
            var url = me.id + "/fncGetBusyoMstValue";
            var data = {
                Busyo_CD: $(".FrmTeisyu.txtBusyoCD").val().trimEnd(),
            };

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == true) {
                    $(".FrmTeisyu.lblBusyoNM").val(
                        result["data"]["strBusyoNM"]
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            me.ajax.send(url, data, 0);
        }
    });

    $(".FrmTeisyu.txtBusyoCD").keydown(function (e) {
        var key = e.charCode || e.keyCode;

        if (key == 222) {
            return false;
        }
    });

    // '**********************************************************************
    // '処理概要：更新ボタン押下時
    // '**********************************************************************
    $(".FrmTeisyu.cmdAction").click(function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

        //入力チェック
        if (me.fncInputChk() == false) {
            return;
        }
        //確認メッセージ
        else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataTeisyuMst;
            me.clsComFnc.FncMsgBox("QY010");
        }
    });
    //20210708 YIN INS S
    //'**********************************************************************
    // '処理概要：条件変更ボタン押下時
    // '**********************************************************************
    $(".FrmTeisyu.cmdChange").click(function () {
        $(".FrmTeisyu.cmdAction").button("disable");
        $(".FrmTeisyu.cmdChange").button("disable");
        $(".FrmTeisyu.txtBusyoCD").attr("disabled", false);
        $(".FrmTeisyu.cmdSearch").button("enable");
        $(".FrmTeisyu.cmdSearchBs").button("enable");
        $(".FrmTeisyu.txtBusyoCD").val("");
        $(".FrmTeisyu.lblBusyoNM").val("");
        $(me.grid_id).jqGrid("clearGridData");
    });
    //20210708 YIN INS E
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

        var data = "load";
        var url = me.id + "/fncFromSyainSelect";

        $(".FrmTeisyu.txtBusyoCD").trigger("focus");
        $(".FrmTeisyu.cmdAction").button("disable");

        //20210708 YIN INS S
        $(".FrmTeisyu.cmdChange").button("disable");
        //20210708 YIN INS E

        gdmz.common.jqgrid.show(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option,
            data,
            ""
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 810);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 269 : 312
        );
        //20150820	Yuanjh ADD S.
        $(me.grid_id).jqGrid("bindKeys");
        //20150820	Yuanjh ADD E.
    };

    me.fncCompleteDeal = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;

                    //ヘッダークリック以外
                    if (cellIndex != 0) {
                        if (rowid && rowid !== me.lastsel) {
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
                    if (rowid && rowid !== me.lastsel) {
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
                    negative: false,
                });

                gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    me.lastsel
                );
            },
        });
    };

    me.setColSelection = function (key, colNowName) {
        //down
        if (key == 40) {
            var selIRow = parseInt(me.lastsel) + 1;
            var getDataCount = $(me.grid_id).jqGrid("getGridParam", "records");

            if (selIRow == getDataCount) {
                return false;
            }

            $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
            $(me.grid_id).jqGrid("setSelection", selIRow, true);

            var selNextId = "#" + selIRow + "_" + colNowName;
            $(selNextId).trigger("focus");
            return false;
        }
        //up
        else if (key == 38) {
            var selIRow = parseInt(me.lastsel) - 1;

            if (selIRow == -1) {
                return false;
            }

            $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
            $(me.grid_id).jqGrid("setSelection", selIRow, true);

            var selNextId = "#" + selIRow + "_" + colNowName;

            $(selNextId).trigger("focus");
            return false;
        }

        return true;
    };

    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);

        var url = me.id + "/frmHTEISYUDeleteRow";
        var data = {
            SYAIN_NO: rowData["SYAIN_NO"],
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                rowData["TEISYU"] = "";
                rowData["HOYU"] = "";
                $(me.grid_id).jqGrid("setRowData", rowID, rowData);

                //選択状態設定する
                $(me.grid_id).jqGrid("setSelection", rowID);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
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
                rowData["TEISYU"].trimEnd() != "" ||
                rowData["HOYU"].trimEnd() != ""
            ) {
                var iColNo = 0;

                //入力チェック
                for (colID in rowData) {
                    switch (colID) {
                        //4,5
                        case "TEISYU":
                        case "HOYU":
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
                            me.colModel[iColNo]["label"].replace(/<br \/>/g, "")
                        );
                        return false;
                    }

                    iColNo += 1;
                }

                //キー項目の必須ﾁｪｯｸ
                if (rowData["SYAIN_NO"].trimEnd() == "") {
                    me.setFocus(rowID, "SYAIN_NO");
                    me.clsComFnc.FncMsgBox("W0001", "社員No.");
                    return false;
                }

                blnInputFlg = true;
            }

            var tmpAttr = {
                SYAIN_NO: "",
                SYAIN_NM: "",
            };

            tmpAttr["SYAIN_NO"] = rowData["SYAIN_NO"];
            tmpAttr["SYAIN_NM"] = rowData["SYAIN_NM"];

            arrCheckData.push(tmpAttr);
        }

        if (!blnInputFlg) {
            me.setFocus(0, "TEISYU");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }

        //重複ﾁｪｯｸ
        for (var i = 0; i < arrCheckData.length - 1; i++) {
            for (var j = i + 1; j < arrCheckData.length; j++) {
                if (
                    arrCheckData[i]["SYAIN_NO"].trimEnd() != "" &&
                    arrCheckData[i]["SYAIN_NM"].trimEnd() != ""
                ) {
                    if (
                        arrCheckData[i]["SYAIN_NO"] ==
                        arrCheckData[j]["SYAIN_NO"]
                    ) {
                        me.setFocus(j, "SYAIN_NO");
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            i +
                                1 +
                                "行目と" +
                                (j + 1) +
                                "行目のキー項目が重複しています"
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

    me.fncDeleteUpdataTeisyuMst = function () {
        var arrInputData = new Array();
        var data = $(me.grid_id).jqGrid("getDataIDs");

        for (key in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[key]);

            if (rowData["TEISYU"] != "" || rowData["HOYU"] != "") {
                arrInputData.push(rowData);
            }
        }

        var url = me.id + "/fncDeleteUpdataTeisyuMst";
        var sendData = {
            inputData: arrInputData,
            Busyo_CD: $(".FrmTeisyu.txtBusyoCD").val().trimEnd(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                $(".FrmTeisyu.txtBusyoCD").val("");
                $(".FrmTeisyu.lblBusyoNM").val("");
                $(".FrmTeisyu.cmdAction").button("disable");
                $(".FrmTeisyu.txtBusyoCD").trigger("focus");
                $(me.grid_id).jqGrid("clearGridData");

                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.FncMsgBox("I0008");
            }
        };
        me.ajax.send(url, sendData, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmTeisyu = new R4.FrmTeisyu();
    o_R4_FrmTeisyu.load();
});
