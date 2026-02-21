/**
 * 説明：
 *
 *
 * @author caina
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("JKSYS.FrmJinKenHiExclude");

JKSYS.FrmJinKenHiExclude = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.jksys = new JKSYS.JKSYS();
    me.id = "FrmJinKenHiExclude";

    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    // jqgrid
    //他部署振替者氏名および振替先
    me.grid_id = "#JKSYS_FrmJinKenHiExclude_sprList1";

    me.upsel = "";
    me.nextsel = "";
    me.sidx = "";
    me.pager = "";
    me.jinjiYM = "";
    me.firstData = new Array();

    //社員番号,名称データ
    me.name_syain = [];
    me.flg_reload = false;

    me.focus_flag = "";
    me.option = {
        rownumbers: true,
        rownumWidth: 40,
        caption: "",
        multiselect: false,
        rowNum: 0,
    };

    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "社員ID",
            index: "SYAIN_NO",
            sortable: false,
            editable: true,
            width: 120,
            editoptions: {
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            me.getSyainName(e.target);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードで名前を見つける
                            if (
                                key == 38 ||
                                key == 40 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                me.getSyainName(e.target);
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "btnSyainSearch",
            label: "検索",
            index: "btnSyainSearch",
            width: 50,
            align: "left",
            sortable: false,
            formatter: function (_cellvalue, options, _rowObject) {
                var strbtnSyainSearch =
                    "<button onclick=\"rowSyainSearch_Click('" +
                    options.rowId +
                    "')\" id = '" +
                    options.rowId +
                    "_btnSyainSearch' class=\"FrmJinKenHiExclude rowSyainSearch Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>検索</button>";
                return strbtnSyainSearch;
            },
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            sortable: false,
            width: 170,
        },
        {
            name: "REMARKS",
            label: "備考",
            index: "REMARKS",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 595 : 654,
        },
    ];
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmJinKenHiExclude.btnRowAdd",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinKenHiExclude.btnRowDel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinKenHiExclude.btnEnt",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.jksys.Shift_TabKeyDown();
    //Tabキーのバインド
    me.jksys.TabKeyDown();
    //Enterキーのバインド
    me.jksys.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //行追加ボタンクリック
    $(".FrmJinKenHiExclude.btnRowAdd").click(function () {
        me.btnRowAdd_Click();
    });
    //行削除ボタンクリック
    $(".FrmJinKenHiExclude.btnRowDel").click(function () {
        me.btnRowDel_Click();
    });
    //登録ボタンクリック
    $(".FrmJinKenHiExclude.btnEnt").click(function () {
        me.focus_flag = "btnEnt";

        me.btnEnt_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Formit();
    };
    //画面初期化(画面起動時)
    me.Formit = function () {
        //画面初期化(一覧)
        // jqgridデータクリア
        $(me.grid_id).jqGrid("clearGridData");
        //画面初期化データ取得
        var url = me.sys_id + "/" + me.id + "/" + "FrmJinKenHiExclude_load";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.jinjiYM = result["data"]["SYORI_YM"];
                if (!me.flg_reload) {
                    //社員署名取得
                    if (result["data"]["SyainMst"].length > 0) {
                        me.name_syain = result["data"]["SyainMst"];
                    }
                }

                //jqgrid読み込み
                me.jqgridInit();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, "", 0);
    };

    me.jqgridInit = function () {
        //データ取得(人件費集計対象外データ)
        var url = me.sys_id + "/" + me.id + "/" + "fncGetJKTFDAT_load";
        var data = [];
        var complete_fun = function (obj) {
            if (obj && obj.responseJSON && obj.responseJSON["error"]) {
                me.clsComFnc.FncMsgBox("E9999", obj.responseJSON["error"]);
                return;
            }
            me.firstData = $(me.grid_id).jqGrid("getRowData");

            //jqgrid設定
            $(me.grid_id).jqGrid("setSelection", 0, true);
        };
        if (me.flg_reload) {
            gdmz.common.jqgrid.reloadGridOptions(
                me.grid_id,
                data,
                complete_fun
            );
        } else {
            gdmz.common.jqgrid.showGridOptions(
                me.grid_id,
                url,
                me.colModel,
                me.pager,
                me.sidx,
                me.option,
                data,
                complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                me.ratio === 1.5 ? 1019 : 1078
            );
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id,
                me.ratio === 1.5 ? 318 : 445
            );
            //jqgrid設定
            me.setJqgridTF();
        }
    };
    me.setJqgridTF = function () {
        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "SYAIN_NO",
                    numberOfColumns: 3,
                    titleText: "社員",
                },
            ],
        });
        //タイトルを削除
        $(me.grid_id + "_SYAIN_NO").remove();
        $(me.grid_id + "_btnSyainSearch").remove();
        $(me.grid_id + "_SYAIN_NM").remove();

        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if ($(me.grid_id).getColProp("SYAIN_NO").editable) {
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
                            if (cellIndex === 4) {
                                $(me.grid_id).jqGrid("editRow", rowid, {
                                    focusField: cellIndex,
                                });
                            } else {
                                $(me.grid_id).jqGrid("editRow", rowid, true);
                                setTimeout(() => {
                                    $("#" + me.lastsel + "_SYAIN_NO").trigger(
                                        "focus"
                                    );
                                }, 0);
                            }
                            $("input,select", e.target).trigger("focus");
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
                            focusField: false,
                        });
                    }
                    var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                        me.grid_id,
                        e,
                        me.lastsel
                    );
                    if (up_next_sel && up_next_sel.length == 2) {
                        me.upsel = up_next_sel[0];
                        me.nextsel = up_next_sel[1];
                    }
                }
            },
            //ヘッダー選択を無効にする
            beforeSelectRow: function (_rowid, e) {
                var cellIndex = e.target.cellIndex;
                if (cellIndex == 0) {
                    setTimeout(() => {
                        var selNextId = "#" + me.lastsel + "_SYAIN_NO";
                        $(selNextId).trigger("focus");
                        $(selNextId).select();
                    }, 0);

                    return false;
                }
                return true;
            },
        });
        $(me.grid_id).jqGrid("bindKeys");
    };

    //登録ボタンクリック
    me.btnEnt_Click = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.Update;
        me.clsComFnc.FncMsgBox("QY010");
    };
    me.Update = function () {
        //入力チェック
        if (me.InPutCheck()) {
            if (me.InPutCheck2()) {
                me.UpdateAction();
            }
        }
    };
    me.UpdateAction = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var rowDataTfs = $(me.grid_id).jqGrid("getRowData");
        var rows = $(me.grid_id).jqGrid("getDataIDs");
        for (index in rows) {
            var rowData = $(me.grid_id).jqGrid("getRowData", rows[index]);
            if (rowData["SYAIN_NO"] == "") {
                $(me.grid_id).jqGrid("setSelection", rows[index], true);
                me.clsComFnc.ObjFocus = $("#" + rows[index] + "_SYAIN_NO");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "人件費データから空白行を削除してください。"
                );
                return;
            }
        }
        var url = me.sys_id + "/" + me.id + "/" + "Ent_Click";
        var data = {
            DataTF: rowDataTfs,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                //完了メッセージ
                var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
                $(me.grid_id).jqGrid("setSelection", rowid, true);
                me.clsComFnc.ObjFocus = $("#" + rowid + "_SYAIN_NO");
                me.clsComFnc.FncMsgBox("I9999", "登録完了しました。");
            }
            if (rowid == null && result["result"]) {
                me.jqgridInit();
                me.flg_reload = true;
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.InPutCheck = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var rowdata = "";
        //社員番号
        var strSyainNo = "";
        //備考
        var strBikou = "";
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            strSyainNo = me.clsComFnc.FncNv(rowdata["SYAIN_NO"]);
            strBikou = me.clsComFnc.FncNv(rowdata["REMARKS"]);
            if (strSyainNo == "" && strBikou == "") {
                continue;
            }
            //社員番号未入力チェック
            if (strSyainNo == "") {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_SYAIN_NO");
                me.clsComFnc.FncMsgBox("W0001", "社員番号");
                return false;
            }
            //社員番号
            var found_array = me.name_syain.filter(function (element) {
                return element["SYAIN_NO"] == strSyainNo;
            });
            if (found_array.length == 0) {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_SYAIN_NO");
                me.clsComFnc.FncMsgBox("W0008", "社員");
                return false;
            }
            //重複チェック
            var Syainarr = $(me.grid_id).jqGrid("getCol", "SYAIN_NO");
            var allRowsId = $(me.grid_id).jqGrid("getDataIDs");
            for (var i2 = i + 1; i2 < Syainarr.length; i2++) {
                //最後の重複番号の行ID
                if (
                    me.clsComFnc.FncNv(Syainarr[i]) ==
                    me.clsComFnc.FncNv(Syainarr[i2])
                ) {
                    if (
                        me.firstData[parseInt(allRowsId[i])]["SYAIN_NO"] !==
                        Syainarr[i]
                    ) {
                        row = allRowsId[i];
                    } else {
                        row = allRowsId[i2];
                    }
                    $(me.grid_id).jqGrid("setSelection", row, true);
                    me.clsComFnc.ObjFocus = $("#" + row + "_SYAIN_NO");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "社員番号が重複しています。(" + Syainarr[i2] + ")"
                    );
                    return false;
                }
            }
        }
        return true;
    };
    me.InPutCheck2 = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var ids = $(me.grid_id).jqGrid("getDataIDs");

        var strBiko = "";
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            strBiko = me.clsComFnc.FncNv(rowdata["REMARKS"]);
            if (me.clsComFnc.FncSprCheck(strBiko, 0, 13, 200) == -3) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjFocus = $("#" + i + "_REMARKS");
                me.clsComFnc.FncMsgBox("W0003", "備考");
                return false;
            }
        }
        return true;
    };

    //行追加ボタンクリック
    me.btnRowAdd_Click = function () {
        //jqgridロードされた
        if ($("#gview_JKSYS_FrmJinKenHiExclude_sprList1").length > 0) {
            //获得所有行的ID数组
            var ids = $(me.grid_id).jqGrid("getDataIDs");
            var rowid = 0;
            if (ids.length > 0) {
                //获得当前最大行号（数据编号）
                rowid = parseInt(ids.pop()) + 1;
            }
            var strbtnSyainSearch =
                "<button onclick=\"rowSyainSearch_Click('" +
                rowid +
                "')\" id = '" +
                rowid +
                "_btnSyainSearch' class=\"FrmJinKenHiExclude rowSyainSearch Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>検索</button>";
            var data = {
                btnSyainSearch: strbtnSyainSearch,
            };
            //插入一行
            $(me.grid_id).jqGrid("addRowData", rowid, data);
            $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
            $(me.grid_id).jqGrid("setSelection", rowid, true);
        }
    };
    //セルボタンクリック
    rowSyainSearch_Click = function (rowId) {
        var $rootDiv = $(".FrmJinKenHiExclude.JKSYS-content");

        $("<div></div>")
            .attr("id", "FrmSyainSearchDialogDiv")
            .insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYOCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYONM").insertAfter($rootDiv);
        $("<div></div>").attr("id", "SYAINNO").insertAfter($rootDiv);
        $("<div></div>").attr("id", "SYAINNM").insertAfter($rootDiv);
        $("<div></div>").attr("id", "KUJYUNBI").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $BUSYOCD = $rootDiv.parent().find("#BUSYOCD");
        var $BUSYONM = $rootDiv.parent().find("#BUSYONM");
        var $SYAINNO = $rootDiv.parent().find("#SYAINNO");
        var $SYAINNM = $rootDiv.parent().find("#SYAINNM");
        var $KUJYUNBI = $rootDiv.parent().find("#KUJYUNBI");

        var dtpYM = me.jinjiYM.replace(/\//g, "");
        var year = dtpYM.substring(0, 4);
        var month = dtpYM.substring(4, 6);
        //构造一个日期对象：
        var day = new Date(year, month, 0);
        //获取当月天数：
        var daycount = day.getDate();
        $KUJYUNBI.val(dtpYM + daycount);
        $("#FrmSyainSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: 650,
            width: 790,
            resizable: false,
            open: function () {
                $RtnCD.hide();
                $BUSYOCD.hide();
                $BUSYONM.hide();
                $SYAINNO.hide();
                $SYAINNM.hide();
                $KUJYUNBI.hide();
            },
            close: function () {
                if ($RtnCD.html() == 1) {
                    me.SYAINNO = $SYAINNO.html();
                    me.SYAINNM = $SYAINNM.html();

                    $("#" + rowId + "_SYAIN_NO").val(me.SYAINNO);
                    $(me.grid_id).jqGrid(
                        "setCell",
                        rowId,
                        "SYAIN_NM",
                        me.SYAINNM
                    );
                }

                $RtnCD.remove();
                $BUSYOCD.remove();
                $BUSYONM.remove();
                $SYAINNO.remove();
                $SYAINNM.remove();
                $KUJYUNBI.remove();
                $("#FrmSyainSearchDialogDiv").remove();
                $("#" + rowId + "_SYAIN_NO").select();
            },
        });

        var url = me.sys_id + "/" + "FrmJKSYSSyainSearch";
        me.ajax.receive = function (result) {
            $("#FrmSyainSearchDialogDiv").html(result);
            $("#FrmSyainSearchDialogDiv").dialog(
                "option",
                "title",
                "社員番号検索"
            );
            $("#FrmSyainSearchDialogDiv").dialog("open");
        };
        me.ajax.send(url, "", 0);
    };

    //行削除ボタンクリック
    me.btnRowDel_Click = function () {
        var allIds = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (allIds.length == 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

        for (i = 0; i < allIds.length; i++) {
            if (allIds[i] == rowid) {
                if (allIds[i] != allIds.pop()) {
                    $(me.grid_id).jqGrid("delRowData", rowid);

                    $(me.grid_id).jqGrid("setSelection", me.nextsel, true);
                } else {
                    $(me.grid_id).jqGrid("delRowData", rowid);

                    $(me.grid_id).jqGrid("setSelection", me.upsel, true);
                }
                break;
            }
        }
    };
    //番号から名称取得
    /*
	 '**********************************************************************
	 '処 理 名：番号から名称取得
	 '関 数 名：me.getSyainName
	 '引    数：e(当前选中的单元格对象)
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.getSyainName = function (e) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv($.trim($(e).val()));
        if (me.name_syain) {
            var foundNM_array = me.name_syain.filter(function (element) {
                return element["SYAIN_NO"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        var sel_parent = $(e).parent();
        sel_parent
            .nextAll('td[aria-describedby$="SYAIN_NM"]')
            .text(foundNM ? foundNM["SYAIN_NM"] : "");
    };
    return me;
};
$(function () {
    o_FrmJinKenHiExclude_FrmJinKenHiExclude = new JKSYS.FrmJinKenHiExclude();
    o_FrmJinKenHiExclude_FrmJinKenHiExclude.load();
});
