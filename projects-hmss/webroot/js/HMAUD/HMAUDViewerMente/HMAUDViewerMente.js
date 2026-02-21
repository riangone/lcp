/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20230801           機能変更　　　データを更新する後、選択行を保持しておく            lujunxia
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDViewerMente");

HMAUD.HMAUDViewerMente = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.sys_id = "HMAUD";
    me.id = "HMAUDViewerMente";
    me.HMAUD = new HMAUD.HMAUD();
    me.lastsel = 0;
    //原始データ
    me.data = "";

    // jqgrid
    me.grid_id = "#HMAUDViewerMente_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.option = {
        rownumbers: false,
        rowNum: 0,
        caption: "",
        loadui: "disable",
        multiselect: false,
    };
    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "担当者コード",
            index: "SYAIN_NO",
            width: 150,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width",
                maxlength: "5",
                dataEvents: [
                    //フォーカスを失ったとき部署名をリセットする
                    {
                        type: "blur",
                        fn: function (e) {
                            me.getSyainName(e);
                        },
                    },
                    //マウス左セルイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードに従って名前イベントを見つける
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 13 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                me.getSyainName(e);
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function () {
                            var tmptxt = $(this).val();
                            $(this).val(tmptxt.replace(/[^0-9a-zA-Z]/g, ""));
                        },
                    },
                ],
            },
        },
        {
            name: "SYAIN_NAME",
            label: "担当者名",
            index: "SYAIN_NAME",
            width: 400,
            align: "left",
            sortable: false,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDViewerMente.button",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMAUDViewerMente.btnRowAdd",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMAUDViewerMente.btnRowDel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMAUDViewerMente.btnUpdata",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =objdrShopSya
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //更新ボタンクリック
    $(".HMAUDViewerMente.btnUpdata").click(function () {
        if (!me.repeatCheck()) {
            return false;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUpdata_Click;
        me.clsComFnc.MsgBoxBtnFnc.No = me.selectRow;
        me.clsComFnc.FncMsgBox("QY012");
    });
    $(".HMAUDViewerMente.btnRowAdd").click(function () {
        me.btnRowAdd_Click();
    });
    $(".HMAUDViewerMente.btnRowDel").click(function () {
        me.btnRowDel_Click();
    });

    $(".HMAUDViewerMente.btnRetrun").click(function () {
        me.btnRetrun_Click();
    });

    //ウインドウサイズ変更時にグリッドの大きさも追従
    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.Page_Load = function () {
        $.jgrid.gridUnload(me.grid_id);
        var completeFun = function (_returnFLG, result) {
            if (result["error"]) {
                $(".HMAUDViewerMente.btnRowAdd").button("disable");
                $(".HMAUDViewerMente.btnRowDel").button("disable");
                $(".HMAUDViewerMente.btnUpdata").button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                if (result["GetSyainMstValue"].length != 0) {
                    me.allSyainName = result["GetSyainMstValue"];
                }
                me.firstData = result["rows"];
                $(me.grid_id).jqGrid("setSelection", 0, true);
            }
        };
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
            "",
            completeFun,
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 580);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 350 : 450,
        );

        $(me.grid_id).jqGrid("bindKeys");

        me.jqgridEditSet();
    };
    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDViewerMente fieldset").width(),
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDViewerMente.buttonClass").height();
        var fieldsetHeight = $(".HMAUDViewerMente fieldset").height();
        var tableHeight = mainHeight - buttonHeight - fieldsetHeight - 90;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - buttonHeight - fieldsetHeight - 98;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };
    //'**********************************************************************
    //'処 理 名：行選択効果の設定
    //'関 数 名：jqgridEditSet
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：行選択効果の設定
    //'**********************************************************************
    me.jqgridEditSet = function () {
        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            //選択行の修正画面を呼び出す
            onSelectRow: function (rowid, _status, e) {
                $(me.grid_id).jqGrid(
                    "saveRow",
                    me.lastsel,
                    null,
                    "clientArray",
                );
                if (typeof e != "undefined") {
                    // var cellIndex = e.target.cellIndex;
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }

                    $("input,select", e.target).trigger("focus");
                } else {
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }
                }
                $(me.grid_id).jqGrid("editRow", rowid, false);
                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });
                var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    rowid,
                );

                if (up_next_sel && up_next_sel.length == 2) {
                    me.upsel = up_next_sel[0];
                    me.nextsel = up_next_sel[1];
                }
                $(me.grid_id).find(".width").css("width", "95%");
            },
        });
    };
    me.selectRow = function () {
        $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
        setTimeout(function () {
            $("#" + me.lastsel + "_SYAIN_NO").trigger("focus");
        }, 0);
    };
    me.btnUpdata_Click = function () {
        var objDR = $(me.grid_id).jqGrid("getRowData");
        var url = "HMAUD/HMAUDViewerMente/btnUpdate_Click";
        var data = {
            tableData: objDR,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                //20230801 lujunxia ins s
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                };
                //20230801 lujunxia ins e
                //更新が完了しました。
                me.clsComFnc.FncMsgBox("I0015");
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.btnRowAdd_Click = function () {
        //获得所有行的ID数组
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = 0;
        if (ids.length > 0) {
            //获得当前最大行号（数据编号）
            rowid = parseInt(ids.pop()) + 1;
        }
        var data = {
            SYAIN_CD: "",
            SYAIN_NAME: "",
        };
        //插入一行
        $(me.grid_id).jqGrid("addRowData", rowid, data);
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

        $(me.grid_id).jqGrid("setSelection", rowid, true);
    };
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
                if (rowid >= 0 && rowid < me.firstData.length) {
                    me.firstData.splice(rowid, 1);
                }
                break;
            }
        }
    };
    me.getSyainName = function (e) {
        var foundNM = undefined;
        var selCellVal = $.trim($(e.target).val());
        if (me.allSyainName) {
            var foundNM_array = me.allSyainName.filter(function (element) {
                return element["SYAIN_NO"] == me.clsComFnc.FncNv(selCellVal);
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(e.target)
            .parent()
            .next()
            .text(foundNM ? foundNM["SYAIN_NM"] : "");
    };

    me.btnRetrun_Click = function () {
        me.Page_Load();
    };
    me.repeatCheck = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var rows = $(me.grid_id).jqGrid("getDataIDs");
        for (index in rows) {
            var rowData = $(me.grid_id).jqGrid("getRowData", rows[index]);
            if (rowData["SYAIN_NO"] == "") {
                $(me.grid_id).jqGrid("setSelection", rows[index], true);
                me.clsComFnc.ObjFocus = $("#" + rows[index] + "_SYAIN_NO");
                me.clsComFnc.FncMsgBox("W0017", "担当者コード");
                return false;
            }
        }
        for (var i = 0; i <= rows.length - 1; i++) {
            var rowData_i = $(me.grid_id).jqGrid("getRowData", rows[i]);
            for (var j = 0; j <= rows.length - 1; j++) {
                var rowData_j = $(me.grid_id).jqGrid("getRowData", rows[j]);
                if (i !== j) {
                    if (
                        rowData_i["SYAIN_NO"] !== "" &&
                        rowData_i["SYAIN_NO"] == rowData_j["SYAIN_NO"]
                    ) {
                        focusRow =
                            me.firstData[i]["cell"]["SYAIN_NO"] !==
                            rowData_i["SYAIN_NO"]
                                ? i
                                : j;
                        $(me.grid_id).jqGrid(
                            "setSelection",
                            rows[focusRow],
                            true,
                        );
                        me.clsComFnc.ObjFocus = $(
                            "#" + rows[focusRow] + "_SYAIN_NO",
                        );
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "コードが重複しています。",
                        );
                        return false;
                    }
                }
            }
        }
        return true;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDViewerMente = new HMAUD.HMAUDViewerMente();
    o_HMAUD_HMAUDViewerMente.load();
    o_HMAUD_HMAUD.HMAUDViewerMente = o_HMAUD_HMAUDViewerMente;
});
