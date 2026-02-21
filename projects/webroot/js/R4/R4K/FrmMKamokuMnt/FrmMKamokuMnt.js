/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD         #ID                      XXXXXX                           FCSDL
 * 20150810           #1985   				   BUG                              Yuanjh
 * 20150820           #2078 					   BUG                              Yuanjh
 * 20151124           #2080 					   BUG                              Yuanjh
 * 20210708           ##### 					   BUG                             yin
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmMKamokuMnt");

R4.FrmMKamokuMnt = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmMKamokuMnt";
    me.sys_id = "R4K";
    me.grid_id = "#FrmMKamokuMnt_sprList";
    me.g_url = "R4K/FrmMKamokuMnt/fncMKamokuSelect";
    me.pager = "#FrmMKamokuMnt_pager";
    me.sidx = "";
    me.lastsel = 0;
    me.flagEditable = false;
    me.searchRowCount = 0;

    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
        shrinkToFit: me.ratio === 1.5,
    };

    me.addData = {
        KAMOK_CD: "",
        KOMOK_CD: "",
        KAMOK_NM: "",
        KAMOK_KANA: "",
        KOMOK_NM: "",
        ZEI_KB: "",
        TAISK_KB: "",
        ICHI: "",
        GDMZ_CD: "",
        KYOTN_KB: "",
        KYOTN_CD: "",
        1: "",
    };

    me.colModel = [
        {
            name: "KAMOK_CD",
            label: "科目コード",
            index: "KAMOK_CD",
            width: 70,
            sortable: false,
            editable: false,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "5",
            },
        },
        {
            name: "KOMOK_CD",
            label: "項目コード",
            index: "KOMOK_CD",
            width: 70,
            sortable: false,
            editable: false,
            align: "left",
            editoptions: {
                maxlength: "5",
            },
        },
        {
            name: "KAMOK_NM",
            label: "科目名",
            index: "KAMOK_NM",
            width: 210,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "50",
            },
        },
        {
            name: "KAMOK_KANA",
            label: "科目名カナ",
            index: "KAMOK_KANA",
            width: 120,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "20",
            },
        },
        {
            name: "KOMOK_NM",
            label: "項目名",
            index: "KOMOK_NM",
            width: 210,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                maxlength: "50",
            },
        },
        {
            name: "ZEI_KB",
            label: "税区分",
            index: "ZEI_KB",
            width: 50,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "1",
            },
        },
        {
            name: "TAISK_KB",
            label: "貸借<br>区分",
            index: "TAISK_KB",
            width: 33,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "1",
            },
        },
        {
            name: "ICHI",
            label: "位置",
            index: "ICHI",
            width: 33,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "1",
            },
        },
        {
            name: "GDMZ_CD",
            label: "（TMrh）<br>コード",
            index: "GDMZ_CD",
            width: 44,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "5",
            },
        },
        {
            name: "KYOTN_KB",
            label: "拠点<br>区分",
            index: "KYOTN_KB",
            width: 34,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "1",
            },
        },
        {
            name: "KYOTN_CD",
            label: "拠点コード",
            index: "KYOTN_CD",
            width: 70,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "5",
            },
        },
        {
            name: "FLAG",
            label: "区分",
            index: "FLAG",
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmMKamokuMnt.cmdAdd",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmMKamokuMnt.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmMKamokuMnt.cmdSearch",
        type: "button",
        handle: "",
    });
    //20210708 YIN INS S
    me.controls.push({
        id: ".FrmMKamokuMnt.cmdChange",
        type: "button",
        handle: "",
    });
    //20210708 YIN INS E

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
    $(".FrmMKamokuMnt.txtKamokuCD").on("focus", function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
    });

    $(".FrmMKamokuMnt.txtKamokuCD").keydown(function (e) {
        var key = e.charCode || e.keyCode;

        if (key == 229) {
            return false;
        } else if (key == 222) {
            return false;
        }
    });
    // **********************************************************************
    // 処 理 名：検索ボタンクリック
    // 関 数 名：cmdSearch_Click
    // 引    数：無し
    // 戻 り 値：無し
    // 処理説明：	検索ボタンクリック
    // **********************************************************************
    $(".FrmMKamokuMnt.cmdSearch").click(function () {
        $(me.grid_id).closest(".ui-jqgrid").block();

        //科目費目マスタからのデータを取得
        var data = {
            kamoku_cd: $(".FrmMKamokuMnt.txtKamokuCD").val().trimEnd(),
        };
        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "error") {
                $(me.grid_id).closest(".ui-jqgrid").block();
                $(".FrmMKamokuMnt.cmdAction").button("disable");
                $(".FrmMKamokuMnt.cmdAdd").button("disable");
                $(".FrmMKamokuMnt.cmdSearch").button("disable");
            } else {
                //スプレッドに取得データをセットする
                var getDataID = $(me.grid_id).jqGrid("getDataIDs");
                me.searchRowCount = getDataID.length;
                $(me.grid_id).jqGrid(
                    "addRowData",
                    getDataID.length,
                    me.addData
                );

                me.fncCompleteDeal();

                //１行目を選択状態にする
                $(me.grid_id).jqGrid("setSelection", 0, true);
                $(me.grid_id).closest(".ui-jqgrid").unblock();
                //20210708 YIN INS S
                //IDにﾌｫｰｶｽ設定
                $(".FrmMKamokuMnt.txtKamokuCD").attr("disabled", "disabled");
                $(".FrmMKamokuMnt.cmdSearch").button("disable");
                $(".FrmMKamokuMnt.cmdAction").button("enable");
                $(".FrmMKamokuMnt.cmdAdd").button("enable");
                //20210708 YIN INS E
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    });

    //**********************************************************************
    // 処 理 名：新規追加ボタンクリック
    // 関 数 名：Button1_Click
    // 引    数：無し
    // 戻 り 値：無し
    // 処理説明：新規追加ボタンクリック
    // **********************************************************************
    $(".FrmMKamokuMnt.cmdAdd").click(function () {
        var getDataID = $(me.grid_id).jqGrid("getDataIDs");

        if (getDataID.length > 0) {
            $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
            var newLineID = parseInt(getDataID[getDataID.length - 1]);
            var rowData = $(me.grid_id).jqGrid("getRowData", newLineID);

            if (
                rowData["KAMOK_CD"].trimEnd() != "" ||
                rowData["KOMOK_CD"].trimEnd() != "" ||
                rowData["KAMOK_NM"].trimEnd() != "" ||
                rowData["KAMOK_KANA"].trimEnd() != "" ||
                rowData["KOMOK_NM"].trimEnd() != "" ||
                rowData["ZEI_KB"].trimEnd() != "" ||
                rowData["TAISK_KB"].trimEnd() != "" ||
                rowData["ICHI"].trimEnd() != "" ||
                rowData["GDMZ_CD"].trimEnd() != "" ||
                rowData["KYOTN_KB"].trimEnd() != "" ||
                rowData["KYOTN_CD"].trimEnd() != ""
            ) {
                $(me.grid_id).jqGrid("addRowData", newLineID + 1, me.addData);
                $(me.grid_id).jqGrid("setSelection", newLineID + 1, true);
            } else {
                $(me.grid_id).jqGrid("setSelection", newLineID, true);
            }
        } else {
            $(me.grid_id).jqGrid("addRowData", 0, me.addData);
            $(me.grid_id).jqGrid("setSelection", 0, true);
        }
    });

    //'**********************************************************************
    // '処理概要：更新ボタン押下時
    // '**********************************************************************
    $(".FrmMKamokuMnt.cmdAction").click(function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

        //入力チェック
        if (me.fncInputChk() == false) {
            return;
        }
    });

    //20210708 YIN INS S
    //'**********************************************************************
    // '処理概要：条件変更ボタン押下時
    // '**********************************************************************
    $(".FrmMKamokuMnt.cmdChange").click(function () {
        $(".FrmMKamokuMnt.cmdAction").button("disable");
        $(".FrmMKamokuMnt.cmdAdd").button("disable");
        $(".FrmMKamokuMnt.txtKamokuCD").attr("disabled", false);
        $(".FrmMKamokuMnt.cmdSearch").button("enable");
        $(".FrmMKamokuMnt.txtKamokuCD").val("");
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

        //科目費目マスタからのデータ
        var data = {
            kamoku_cd: "",
        };
        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "error") {
                $(me.grid_id).closest(".ui-jqgrid").block();
                $(".FrmMKamokuMnt.cmdAction").button("disable");
                $(".FrmMKamokuMnt.cmdAdd").button("disable");
                $(".FrmMKamokuMnt.cmdSearch").button("disable");
            } else {
                var getDataID = $(me.grid_id).jqGrid("getDataIDs");
                me.searchRowCount = getDataID.length;

                //---20150804 Yuanjh modify S.
                //1行追加
                //$(me.grid_id).jqGrid('addRowData', getDataID.length, me.addData);
                //---20150804 Yuanjh modify E.
                me.fncCompleteDeal();

                //20210708 YIN UPD S
                //IDにﾌｫｰｶｽ設定
                // $(".FrmMKamokuMnt.txtKamokuCD").focus();
                $(".FrmMKamokuMnt.txtKamokuCD").attr("disabled", "disabled");
                $(".FrmMKamokuMnt.cmdSearch").button("disable");
                $(".FrmMKamokuMnt.cmdAdd").trigger("focus");
                //20210708 YIN UPD E
            }
        };

        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            data,
            me.complete_fun
        );
        me.t = document.getElementById("FrmMKamokuMnt_pager_center");
        me.t.childNodes[1].innerHTML = "";
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1025 : 1075
        );
        //20180302 lqs UPD S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 340);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 250 : 310
        );
        //20180302 lqs UPD E
        //20150820	Yuanjh ADD S.
        $(me.grid_id).jqGrid("bindKeys");
        //20150820	Yuanjh ADD E.
    };

    //'**********************************************************************
    //'処理概要：スプレッドセルクリック
    //'**********************************************************************
    me.fncCompleteDeal = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                // me.flagEditable = $(me.grid_id).getColProp("KAMOK_CD").editable;
                if (parseInt(rowid) > parseInt(me.searchRowCount) - 1) {
                    me.flagEditable = true;
                } else {
                    me.flagEditable = false;
                }
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

                        if (me.searchRowCount == 0) {
                            $(me.grid_id).setColProp("KAMOK_CD", {
                                editable: true,
                            });
                            $(me.grid_id).setColProp("KOMOK_CD", {
                                editable: true,
                            });
                        } else if (
                            parseInt(rowid) >
                            parseInt(me.searchRowCount) - 1
                        ) {
                            $(me.grid_id).setColProp("KAMOK_CD", {
                                editable: true,
                            });
                            $(me.grid_id).setColProp("KOMOK_CD", {
                                editable: true,
                            });
                        } else {
                            $(me.grid_id).setColProp("KAMOK_CD", {
                                editable: false,
                            });
                            $(me.grid_id).setColProp("KOMOK_CD", {
                                editable: false,
                            });
                        }

                        $(me.grid_id).jqGrid("editRow", rowid, {
                            keys: true,
                            focusField:
                                me.searchRowCount - 1 == rowid && cellIndex < 3
                                    ? 3
                                    : cellIndex,
                        });
                    } else {
                        ////ヘッダークリック
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

                    if (me.searchRowCount == 0) {
                        $(me.grid_id).setColProp("KAMOK_CD", {
                            editable: true,
                        });
                        $(me.grid_id).setColProp("KOMOK_CD", {
                            editable: true,
                        });
                    } else if (
                        parseInt(rowid) >
                        parseInt(me.searchRowCount) - 1
                    ) {
                        $(me.grid_id).setColProp("KAMOK_CD", {
                            editable: true,
                        });
                        $(me.grid_id).setColProp("KOMOK_CD", {
                            editable: true,
                        });
                    } else {
                        $(me.grid_id).setColProp("KAMOK_CD", {
                            editable: false,
                        });
                        $(me.grid_id).setColProp("KOMOK_CD", {
                            editable: false,
                        });
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

    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);

        if (rowData["KAMOK_CD"] != "") {
            var url = me.id + "/frmKamokuDeleteRow";
            var data = {
                KAMOK_CD: rowData["KAMOK_CD"].trimEnd(),
                KOMOK_CD: rowData["KOMOK_CD"].trimEnd(),
            };

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == true) {
                    //行削除を行う
                    if (me.flagEditable == false) {
                        me.searchRowCount--;
                    }

                    me.delRowDataContent(rowID);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            me.ajax.send(url, data, 0);
        } else {
            //行削除を行う
            me.delRowDataContent(rowID);
        }
    };

    me.delRowDataContent = function (rowID) {
        var getDataID = $(me.grid_id).jqGrid("getDataIDs");

        for (var i = parseInt(rowID); i < getDataID.length - 1; i++) {
            var rowData = $(me.grid_id).jqGrid("getRowData", i + 1);
            $(me.grid_id).jqGrid("setRowData", i, rowData);
        }

        $(me.grid_id).jqGrid("delRowData", getDataID.length - 1);
        $(me.grid_id).jqGrid("setSelection", rowID, true);
    };

    me.fncGetInputData = function () {
        var arr = new Array();
        var data = $(me.grid_id).jqGrid("getDataIDs");

        for (key in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[key]);

            if (
                rowData["KAMOK_CD"].trimEnd() != "" ||
                rowData["KOMOK_CD"].trimEnd() != "" ||
                rowData["KAMOK_NM"].trimEnd() != "" ||
                rowData["KAMOK_KANA"].trimEnd() != "" ||
                rowData["KOMOK_NM"].trimEnd() != "" ||
                rowData["ZEI_KB"].trimEnd() != "" ||
                rowData["TAISK_KB"].trimEnd() != "" ||
                rowData["ICHI"].trimEnd() != "" ||
                rowData["GDMZ_CD"].trimEnd() != "" ||
                rowData["KYOTN_KB"].trimEnd() != "" ||
                rowData["KYOTN_CD"].trimEnd() != ""
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
                rowData["KOMOK_CD"].trimEnd() != "" ||
                rowData["KAMOK_NM"].trimEnd() != "" ||
                rowData["KAMOK_KANA"].trimEnd() != "" ||
                rowData["KOMOK_NM"].trimEnd() != "" ||
                rowData["ZEI_KB"].trimEnd() != "" ||
                rowData["TAISK_KB"].trimEnd() != "" ||
                rowData["ICHI"].trimEnd() != "" ||
                rowData["GDMZ_CD"].trimEnd() != "" ||
                rowData["KYOTN_KB"].trimEnd() != "" ||
                rowData["KYOTN_CD"].trimEnd() != ""
            ) {
                var iColNo = 0;

                //入力チェック
                for (colID in rowData) {
                    switch (colID) {
                        //0, 5, 6, 7, 8, 9, 10
                        case "KAMOK_CD":
                        case "ZEI_KB":
                        case "TAISK_KB":
                        case "ICHI":
                        case "GDMZ_CD":
                        case "KYOTN_KB":
                        case "KYOTN_CD":
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER1,
                                me.colModel[iColNo]["editoptions"]["maxlength"]
                            );
                            break;
                        //1
                        case "KOMOK_CD":
                            if (rowData[colID].trimEnd() != "") {
                                intRtn = me.clsComFnc.FncSprCheck(
                                    rowData[colID],
                                    0,
                                    me.clsComFnc.INPUTTYPE.CHAR2,
                                    me.colModel[iColNo]["editoptions"][
                                        "maxlength"
                                    ]
                                );
                            }
                            break;
                        case "FLAG":
                            break;
                        default:
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID].replace(/,/g, ""),
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
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

                //科目コードの必須ﾁｪｯｸ
                if (rowData["KAMOK_CD"].trimEnd() == "") {
                    me.setFocus(rowID, "KAMOK_CD");
                    me.clsComFnc.FncMsgBox("W0001", "科目コード");
                    return false;
                }

                blnInputFlg = true;
            }

            var tmpAttr = {
                KAMOK_CD: "",
                KOMOK_CD: "",
                FLAG: "",
                rowNO: "",
            };

            tmpAttr["KAMOK_CD"] = rowData["KAMOK_CD"];
            tmpAttr["KOMOK_CD"] = rowData["KOMOK_CD"];
            tmpAttr["FLAG"] = rowData["FLAG"];
            tmpAttr["rowNO"] = rowID;

            arrCheckData.push(tmpAttr);
        }

        if (!blnInputFlg) {
            me.setFocus(0, "KAMOK_CD");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }

        //重複ﾁｪｯｸ
        for (var i = 0; i < arrCheckData.length - 1; i++) {
            if (arrCheckData[i]["FLAG"] != "1") {
                for (var j = i + 1; j < arrCheckData.length; j++) {
                    if (
                        arrCheckData[i]["KAMOK_CD"].trimEnd() != "" &&
                        arrCheckData[i]["KOMOK_CD"].trimEnd() != ""
                    ) {
                        //新規ﾃﾞｰﾀとの重複チェック
                        if (
                            arrCheckData[i]["KAMOK_CD"] ==
                                arrCheckData[j]["KAMOK_CD"] &&
                            arrCheckData[i]["KOMOK_CD"] ==
                                arrCheckData[j]["KOMOK_CD"]
                        ) {
                            me.setFocus(j, "KAMOK_CD");
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "キー項目が重複しています"
                            );
                            return false;
                        }
                    }
                }
            }
        }

        //既存ﾃﾞｰﾀとの重複チェック
        var url = me.id + "/frmCheckExit";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["rowNO"] != "none") {
                    me.setFocus(result["rowNO"], "KAMOK_CD");
                    me.clsComFnc.FncMsgBox("E9999", "キー項目が重複しています");
                    return false;
                } else {
                    //確認メッセージ
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataKamokuMst;
                    me.clsComFnc.FncMsgBox("QY010");
                }
            } else if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return false;
            }
        };
        me.ajax.send(url, JSON.stringify(arrCheckData), 0);

        return true;
    };

    me.setFocus = function (rowID, colID) {
        var rowNum = parseInt(rowID);
        $(me.grid_id).jqGrid("setSelection", rowNum);

        var ceil = rowID + "_" + colID;
        me.clsComFnc.ObjFocus = $("#" + ceil);
        me.clsComFnc.ObjSelect = $("#" + ceil);
    };

    me.fncDeleteUpdataKamokuMst = function () {
        var arrInputData = me.fncGetInputData();

        var url = me.id + "/fncMKamokuDelUpd";
        var sendData = {
            inputData: arrInputData,
            kamoku_cd: $(".FrmMKamokuMnt.txtKamokuCD").val().trimEnd(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                //***再表示****
                //科目費目マスタからのデータを取得
                var data = {
                    kamoku_cd: $(".FrmMKamokuMnt.txtKamokuCD").val().trimEnd(),
                };
                me.complete_fun = function (bErrorFlag) {
                    if (bErrorFlag == "error") {
                        $(me.grid_id).closest(".ui-jqgrid").block();
                        $(".FrmMKamokuMnt.cmdAction").button("disable");
                        $(".FrmMKamokuMnt.cmdAdd").button("disable");
                        $(".FrmMKamokuMnt.cmdSearch").button("disable");
                    } else {
                        var getDataID = $(me.grid_id).jqGrid("getDataIDs");
                        me.searchRowCount = getDataID.length;

                        //1行追加
                        //--20151124   Yuanjh   ADD  S.
                        //$(me.grid_id).jqGrid('addRowData', getDataID.length, me.addData);
                        //--20151124   Yuanjh   ADD  E.

                        me.fncCompleteDeal();

                        //１行目を選択状態にする
                        $(me.grid_id).jqGrid("setSelection", 0);

                        //正常終了ﾒｯｾｰｼﾞ
                        me.clsComFnc.FncMsgBox("I0008");
                    }
                };

                //スプレッドに取得データをセットする
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id,
                    data,
                    me.complete_fun
                );
            }
        };
        me.ajax.send(url, JSON.stringify(sendData), 0);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmMKamokuMnt = new R4.FrmMKamokuMnt();
    o_R4_FrmMKamokuMnt.load();
});
