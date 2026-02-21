/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150728           #2012  　　　「更新」ボタンを押すと「業者略名」のエラーが表示されて更新できない FANZHENGZHOU
 * 20150819           #2078  　　　                 BUG                              li
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmGyousyaMst");

R4.FrmGyousyaMst = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmGyousyaMst";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmGyousyaMst_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFromGyosyaSelect";
    me.pager = "";
    // '#FrmGyousyaMst_pager';
    me.sidx = "";
    me.actionFlg = "";
    me.lastsel = 0;
    me.firstData = new Array();
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmGyousyaMst.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmGyousyaMst.cmdUpdate",
        type: "button",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmGyousyaMst.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmGyousyaMst.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmGyousyaMst_load();
    };
    me.initGrid = function () {
        me.option = {
            pagerpos: "left",
            multiselect: false,
            caption: "",
            rowNum: 5000000,
            multiselectWidth: 30,
            rownumWidth: 40,
        };
        me.colModel = [
            {
                name: "GYOSYA_CD",
                label: "業者コード",
                index: "GYOSYA_CD",
                width: 100,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 5,
                },
            },
            {
                name: "GYOSYA_NM",
                label: "業者名",
                index: "GYOSYA_NM",
                width: 220,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 40,
                },
            },
            {
                name: "GYOSYA_RNM",
                label: "業者略名",
                index: "GYOSYA_RNM",
                width: 100,
                sortable: false,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 20,
                },
            },
            {
                name: "JISSEKI_KB",
                label: "ｾｰﾙｽﾏﾝ別対象ﾌﾗｸﾞ",
                index: "JISSEKI_KB",
                width: 230,
                sortable: false,
                align: "left",
                hidden: false,
                editable: true,
                editoptions: {
                    maxlength: 1,
                },
            },
            {
                name: "SYUKEI_BUSYO_CD",
                label: "集計部署",
                index: "SYUKEI_BUSYO_CD",
                width: 130,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 3,
                },
            },
            {
                name: "CREATE_DATE",
                label: "作成日",
                index: "CREATE_DATE",
                width: 33,
                sortable: false,
                align: "left",
                hidden: true,
            },
        ];
        me.complete_fun = function () {
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            if (arrIds.length > 0) {
                $(me.grid_id).jqGrid("setSelection", 0);
            }
            if (arrIds.length <= 0) {
                $(".FrmGyousyaMst.cmdUpdate").button("disable");
            }
            me.firstData = $(me.grid_id).jqGrid("getRowData");
            //me.t = document.getElementById("FrmGyousyaMst_pager_center");
            //me.t.childNodes[1].innerHTML = "";
            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    if (typeof e != "undefined") {
                        //編集可能なセルをクリック、上下キー
                        var cellIndex =
                            e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex;
                        //ヘッダークリック以外
                        if (cellIndex != 0) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            $(me.grid_id).jqGrid("editRow", rowid, {
                                keys: true,
                                focusField: cellIndex,
                            });
                            me.lastsel = rowid;
                        } else {
                            //ヘッダークリック
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );

                            var rowID = $(me.grid_id).jqGrid(
                                "getGridParam",
                                "selrow"
                            );
                            var rowData = $(me.grid_id).jqGrid(
                                "getRowData",
                                rowID
                            );
                            if (
                                rowData["GYOSYA_CD"].toString().trimEnd() == ""
                            ) {
                                return;
                            }
                            me.jqgridCurrentRowID = rowID;
                            //削除確認メッセージを表示する
                            me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                            me.clsComFnc.MsgBoxBtnFnc.No = me.cancelsel;
                            me.clsComFnc.MessageBox(
                                "削除します、よろしいですか？",
                                me.clsComFnc.GSYSTEM_NAME,
                                "YesNo",
                                "Question",
                                me.clsComFnc.MessageBoxDefaultButton.Button2
                            );
                        }
                    } else {
                        //tab、enter、tab+shift
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        $(me.grid_id).jqGrid("editRow", rowid, {
                            keys: true,
                            focusField: false,
                        });
                        me.lastsel = rowid;
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
        var tmpdata = {};
        gdmz.common.jqgrid.show(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            tmpdata,
            me.complete_fun
        );
        //---20150818 li UPD S.
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, 860);
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 870);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 285);
        //---20150818 li UPD E.
        //---20150818 li ADD S.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150818 li ADD E.
    };

    me.FrmGyousyaMst_load = function () {
        me.initGrid();
    };
    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");

        var rowdata = {
            GYOSYA_CD: "",
            GYOSYA_NM: "",
            GYOSYA_RNM: "",
            JISSEKI_KB: "",
            SYUKEI_BUSYO_CD: "",
        };
        //業者ﾏｽﾀに該当ﾃﾞｰﾀが存在している場合
        if (arrIds.length > 0) {
            for (j = 0; j < arrIds.length; j++) {
                $(me.grid_id).jqGrid("saveRow", j, null, "clientArray");
            }

            var i = arrIds.length;
            var i_rowData = $(me.grid_id).jqGrid("getRowData", i - 1);
            if (
                i_rowData.GYOSYA_CD != "" ||
                i_rowData.GYOSYA_NM != "" ||
                i_rowData.GYOSYA_RNM != "" ||
                i_rowData.JISSEKI_KB != "" ||
                i_rowData.SYUKEI_BUSYO_CD != ""
            ) {
                $(me.grid_id).jqGrid("addRowData", i, rowdata);
                var selNextId = "#" + i + "_GYOSYA_CD";
                $(selNextId).trigger("focus");
            } else {
                //$(me.grid_id).jqGrid('editRow', i-1, true);
            }
        } else {
            $(me.grid_id).jqGrid("addRowData", 0, rowdata);
            $(me.grid_id).jqGrid("setSelection", 0, true);
        }
        $(".FrmGyousyaMst.cmdUpdate").button("enable");
    };
    me.fnc_click_cmdUpdate = function () {
        //入力チェック
        var grid_data = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data.length; i++) {
            $(me.grid_id).jqGrid("saveRow", i, null, "clientArray");
        }
        if (me.fncInputChk()) {
            //重複ﾁｪｯｸ
            var grid_data = $(me.grid_id).jqGrid("getRowData");
            for (var i = 0; i < grid_data.length; i++) {
                $(me.grid_id).jqGrid("saveRow", i, null, "clientArray");
            }
            for (var i = 0; i < grid_data.length - 1; i++) {
                for (var j = i + 1; j < grid_data.length; j++) {
                    if (grid_data[i]["GYOSYA_CD"] != "") {
                        if (
                            grid_data[i]["GYOSYA_CD"] ==
                            grid_data[j]["GYOSYA_CD"]
                        ) {
                            var row = j;
                            if (me.firstData.length - 1 >= i) {
                                if (
                                    me.firstData[i]["GYOSYA_CD"] !==
                                    grid_data[i]["GYOSYA_CD"]
                                ) {
                                    var row = i;
                                }
                            }

                            $(me.grid_id).jqGrid("setSelection", row, true);
                            var selId = "#" + row + "_" + "GYOSYA_CD";
                            $(selId).trigger("focus");
                            $(selId).trigger("select");
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "業者コードが重複しています"
                            );
                            return;
                        }
                    }
                }
            }
            //確認メッセージ
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
            me.clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
            me.clsComFnc.FncMsgBox("QY010");
        }
    };
    //--functions--
    //---20150819 li UPD S.
    //me.setColSelection = function(key, colNowName, colNextName) {
    //enter
    // if (key == 13) {
    // $('#' + me.lastsel + '_' + colNextName).focus();
    // return false;
    // }
    // return true;
    // };

    //---20150819 li UPD E.

    me.fncInputChk = function () {
        var fncInputChk_TF = false;
        //どれか一列でも入力されていた場合
        var grid_data = $(me.grid_id).jqGrid("getRowData");
        //$(me.grid_id).jqGrid('saveRow');
        var tmparr = {
            GYOSYA_CD: 0,
            GYOSYA_NM: 1,
            GYOSYA_RNM: 2,
            JISSEKI_KB: 3,
            SYUKEI_BUSYO_CD: 4,
        };
        for (var i = 0; i < grid_data.length; i++) {
            var tmp_cnt = 0;
            for (key in grid_data[i]) {
                if (grid_data[i][key] != "") {
                    tmp_cnt++;
                }
            }

            if (tmp_cnt > 0) {
                //入力チェック
                for (key in grid_data[i]) {
                    //20150728 #2012 fanzhengzhou upd s.
                    // if (key == "GYOSYA_CD")
                    // {
                    // me.intRtn = me.clsComFnc.FncSprCheck(grid_data[i]["GYOSYA_CD"], 0, me.clsComFnc.INPUTTYPE.NONE, me.colModel[0]['editoptions']['maxlength']);
                    // }
                    // else
                    // {
                    // if (key == "GYOSYA_NM")
                    // {
                    // me.intRtn = me.clsComFnc.FncSprCheck(grid_data[i]["GYOSYA_NM"], 0, me.clsComFnc.INPUTTYPE.NONE, me.colModel[1]['editoptions']['maxlength']);
                    // }
                    // else
                    // {
                    // if (key != "CREATE_DATE")
                    // {
                    // me.intRtn = me.clsComFnc.FncSprCheck(grid_data[i][key], 0, me.clsComFnc.INPUTTYPE.CHAR2, me.colModel[tmparr[key]]['editoptions']['maxlength']);
                    // }
                    // }
                    // }
                    switch (key) {
                        case "GYOSYA_NM":
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data[i]["GYOSYA_NM"],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                me.colModel[1]["editoptions"]["maxlength"]
                            );
                            break;
                        case "GYOSYA_RNM":
                            //20150824 #2012 li upd S.
                            //me.intRtn = me.clsComFnc.FncSprCheck(grid_data[i]["GYOSYA_RNM"], 0, me.clsComFnc.INPUTTYPE.NONE, me.colModel[2]['editoptions']['maxlength']);
                            me.intRtn = me.clsComFnc.FncSprCheck(
                                grid_data[i]["GYOSYA_RNM"],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR1,
                                me.colModel[2]["editoptions"]["maxlength"]
                            );
                            //20150824 #2012 li upd E.
                            break;
                        default:
                            if (key != "CREATE_DATE") {
                                me.intRtn = me.clsComFnc.FncSprCheck(
                                    grid_data[i][key],
                                    0,
                                    me.clsComFnc.INPUTTYPE.CHAR2,
                                    me.colModel[tmparr[key]]["editoptions"][
                                        "maxlength"
                                    ]
                                );
                            }
                            break;
                    }
                    //20150728 #2012 fanzhengzhou upd s.
                    switch (me.intRtn) {
                        case 0:
                            break;
                        default:
                            $(me.grid_id).jqGrid("setSelection", i, true);
                            var selId = "#" + i + "_" + key;
                            $(selId).trigger("focus");
                            $(selId).trigger("select");
                            me.clsComFnc.FncMsgBox(
                                "W000" + (me.intRtn * -1).toString(),
                                me.colModel[tmparr[key]]["label"]
                            );
                            return false;
                    }
                }
                //キー項目の必須ﾁｪｯｸ
                if (grid_data[i]["GYOSYA_CD"] == "") {
                    $(me.grid_id).jqGrid("setSelection", i, true);
                    var selId = "#" + i + "_" + "GYOSYA_CD";
                    $(selId).trigger("focus");
                    $(selId).trigger("select");
                    me.clsComFnc.FncMsgBox("W0001", "業者コード");
                    return false;
                }
                fncInputChk_TF = true;
            }
            tmp_cnt = 0;
        }
        if (fncInputChk_TF == false) {
            $(me.grid_id).jqGrid("setSelection", i, true);
            var selId = "#" + i + "_" + "GYOSYA_CD";
            $(selId).trigger("focus");
            $(selId).trigger("select");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }
        return true;
    };
    me.YesActionFnc = function () {
        //業者ﾏｽﾀに登録開始
        me.updateUrl = me.sys_id + "/" + me.id + "/" + "fncUpdate";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox("I0008");
                me.firstData = $(me.grid_id).jqGrid("getRowData");
            }
        };
        var data = $(me.grid_id).jqGrid("getRowData");
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.NoActionFnc = function () {
        return;
    };
    me.delRowData = function () {
        me.deleteUrl = me.sys_id + "/" + me.id + "/" + "fncDelete";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                var tmpdata = {};
                gdmz.common.jqgrid.show(
                    me.grid_id,
                    me.g_url,
                    me.colModel,
                    me.pager,
                    me.sidx,
                    me.option,
                    tmpdata,
                    me.complete_fun
                );
                //---20150818 li UPD S.
                //gdmz.common.jqgrid.set_grid_width(me.grid_id, 900);
                //gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 870);
                gdmz.common.jqgrid.set_grid_height(me.grid_id, 285);
                //---20150818 li UPD E.
            }
        };

        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        var data = {
            GYOSYA_CD: rowData["GYOSYA_CD"],
        };
        me.ajax.send(me.deleteUrl, data, 0);
    };
    me.cancelsel = function () {};
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmGyousyaMst = new R4.FrmGyousyaMst();
    o_R4_FrmGyousyaMst.load();
    o_R4K_R4K.FrmGyousyaMst = o_R4_FrmGyousyaMst;
});
