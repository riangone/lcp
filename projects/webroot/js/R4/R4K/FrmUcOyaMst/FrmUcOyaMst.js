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
 * 20150819           #2078                        　                               FANZHENGZHOU
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmUcOyaMst");

R4.FrmUcOyaMst = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmUcOyaMst";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmUcOyaMst_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFromGyosyaSelect";
    me.pager = "#FrmUcOyaMst_pager";
    me.sidx = "";
    me.actionFlg = "";
    me.lastsel = 0;
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmUcOyaMst.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUcOyaMst.cmdUpdate",
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
    $(".FrmUcOyaMst.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmUcOyaMst.cmdUpdate").click(function () {
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
        me.FrmUcOyaMst_load();
    };
    me.initGrid = function () {
        me.option = {
            //---20150821 fan upd s.
            //pagerpos : "left",
            //---20150821 fan upd e.
            recordpos: "center",
            multiselect: false,
            caption: "",
            rowNum: 5000000,
            multiselectWidth: 30,
            rownumWidth: 40,
        };
        me.colModel = [
            {
                name: "UCOYA_CD",
                label: "UC親コード",
                index: "UCOYA_CD",
                width: 100,
                sortable: false,
                align: "left",
            },
            {
                name: "HMK_CD",
                label: "UC費目",
                index: "HMK_CD",
                width: 320,
                sortable: false,
                align: "left",
                editable: true,
                editoptions: {
                    maxlength: 2,
                },
            },
            {
                name: "CREATE_DATE",
                label: "作成日",
                index: "CREATE_DATE",
                width: 100,
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
                $(".FrmUcOyaMst.cmdUpdate").button("disable");
            }
            //---20150821 fanzhengzhou del s.
            // me.t = document.getElementById("FrmUcOyaMst_pager");
            // me.t.childNodes[1].innerHTML = "";
            //---20150821 fanzhengzhou del e.
            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    if (typeof e != "undefined") {
                        var cellIndex = e.target.cellIndex;
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
                            $(me.grid_id).jqGrid("editRow", rowid, true);
                        } else {
                            //ヘッダークリック
                            $(me.grid_id).jqGrid("saveRow", me.lastsel);

                            var rowID = $(me.grid_id).jqGrid(
                                "getGridParam",
                                "selrow"
                            );
                            var rowData = $(me.grid_id).jqGrid(
                                "getRowData",
                                rowID
                            );
                            if (
                                rowData["UCOYA_CD"].toString().trimEnd() == ""
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
        me.t = document.getElementById("FrmUcOyaMst_pager_center");
        me.t.childNodes[1].innerHTML = "";
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 500);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 312 : 390
        );
        //---20150819 #2078 fanzhengzhou add s.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150819 #2078 fanzhengzhou add e.
    };

    me.FrmUcOyaMst_load = function () {
        me.initGrid();
    };
    //--click event functions--
    me.fnc_click_cmdUpdate = function () {
        //入力チェック
        var grid_data = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data.length; i++) {
            $(me.grid_id).jqGrid("saveRow", i, null, "clientArray");
        }
        if (me.fncInputChk()) {
            //確認メッセージ
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
            me.clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
            me.clsComFnc.FncMsgBox("QY010");
        }
    };

    me.fncInputChk = function () {
        //どれか一列でも入力されていた場合
        var grid_data = $(me.grid_id).jqGrid("getRowData");
        //$(me.grid_id).jqGrid('saveRow');
        for (var i = 0; i < grid_data.length; i++) {
            if (grid_data[i]["HMK_CD"] == "") {
                var grid_data = $(me.grid_id).jqGrid("getRowData");
                for (var j = 0; j < grid_data.length; j++) {
                    $(me.grid_id).jqGrid("saveRow", j, null, "clientArray");
                }
                $(me.grid_id).jqGrid("setSelection", i);
                $(me.grid_id).jqGrid("editRow", i, true);
                $("#" + i + "_HMK_CD").trigger("focus");
                me.clsComFnc.FncMsgBox("W0001", "費目コード");
                return;
            }
            me.intRtn = me.clsComFnc.FncSprCheck(
                grid_data[i]["HMK_CD"],
                1,
                me.clsComFnc.INPUTTYPE.NONE,
                me.colModel[1]["editoptions"]["maxlength"]
            );
            switch (me.intRtn) {
                case 0:
                    break;
                default:
                    var arrIds = $(me.grid_id).jqGrid("getDataIDs");
                    for (k = 0; k < parseInt(arrIds.length) - 1; k++) {
                        $(me.grid_id).jqGrid("saveRow", k, true);
                    }
                    $(me.grid_id).jqGrid("setSelection", i);
                    $(me.grid_id).jqGrid("editRow", i, true);
                    $("#" + i + "_" + key).trigger("focus");
                    me.clsComFnc.FncMsgBox(
                        "W000" + (parseInt(me.intRtn) * -1).toString(),
                        "費目コード"
                    );
                    return false;
            }
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
            }
        };
        var data = $(me.grid_id).jqGrid("getRowData");
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.NoActionFnc = function () {
        return;
    };
    me.delRowData = function () {
        me.deleteUrl = me.sys_id + "/" + me.id + "/" + "fncSingleDelete";
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
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 500);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    me.ratio === 1.5 ? 312 : 390
                );
            }
        };
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        var data = {
            UCOYA_CD: rowData["UCOYA_CD"],
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
    var o_R4_FrmUcOyaMst = new R4.FrmUcOyaMst();
    o_R4_FrmUcOyaMst.load();
    o_R4K_R4K.FrmUcOyaMst = o_R4_FrmUcOyaMst;
});
