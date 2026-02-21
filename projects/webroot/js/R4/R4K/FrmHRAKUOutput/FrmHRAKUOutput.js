Namespace.register("R4.FrmHRAKUOutput");

R4.FrmHRAKUOutput = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmHRAKUOutput";

    // ========== 変数 start ==========

    me.grid_id = "#R4_FrmHRAKUOutput_sprItyp";
    me.sys_id = "R4K";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnView_Click";
    me.option = {
        rowNum: 999999,
        multiselect: false,
        rownumbers: true,
        loadui: "disable",
        caption: "",
    };

    me.colModel = [
        {
            name: "GROUP_NO",
            label: "グループ№",
            index: "GROUP_NO",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "GROUP_NM",
            label: "グループ名",
            index: "GROUP_NM",
            width: 350,
            align: "left",
            sortable: false,
        },
        {
            name: "KEIRI_DATE",
            label: "経理処理日",
            index: "KEIRI_DATE",
            width: 110,
            align: "left",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //選択ボタン
    me.controls.push({
        id: ".FrmHRAKUOutput.btnSelect",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".FrmHRAKUOutput.btnClose",
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

    //処理説明：選択ボタン押下時
    $(".FrmHRAKUOutput.btnSelect").click(function () {
        me.check_select();
    });
    //処理説明：戻るボタン押下時
    $(".FrmHRAKUOutput.btnClose").click(function () {
        //閉じる
        $("#FrmHRAKUOutputDialogDiv").dialog("close");
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
        me.FrmHRAKUOutput_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：FrmHRAKUOutput_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.FrmHRAKUOutput_load = function () {
        //初期設定処理
        gdmz.common.jqgrid.showWithMesgScroll(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
            {},
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 653);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 373 : 480
        );
        $("#R4_FrmHRAKUOutput_sprItyp_rn").html("行№");
    };

    me.complete_fun = function (returnFLG, result) {
        if (result["error"]) {
            me.clsComFnc.FncMsgBox("E9999", result["error"]);
            return;
        }
        if (returnFLG != "nodata") {
            //KEYDOWN
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowId) {
                    $(me.grid_id + " tr#" + rowId).on("keydown", function (e) {
                        var key = e.which;
                        e.preventDefault();
                        if (key == 9 && e.shiftKey == false) {
                            $(".FrmHRAKUOutput.btnSelect").trigger("focus");
                        }
                    });
                },
            });
            $(me.grid_id).jqGrid("bindKeys", {
                onEnter: function () {
                    me.check_select();
                },
            });
        } else {
            setTimeout(function () {
                me.clsComFnc.MsgBoxBtnFnc.OK = function () {
                    $("#FrmHRAKUOutputDialogDiv").dialog("close");
                };
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $("#FrmHRAKUOutputDialogDiv").dialog("close");
                };
                me.clsComFnc.FncMsgBox("W0024");
            }, 100);
        }
    };
    me.before_close = function () {};

    me.check_select = function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.HRAKUDataOutput;
        me.clsComFnc.FncMsgBox("QY025");
    };

    //'**********************************************************************
    //'処 理 名：ファイルを作成する
    //'関 数 HRAKUDataOutput
    //'戻 り 値：なし
    //'処理説明：グループ行選択の処理
    //'**********************************************************************
    me.HRAKUDataOutput = function () {
        var SelectRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", SelectRow);
        var data = {
            //出力グループNO
            grNo: rowData["GROUP_NO"],
            //出力グループ名
            grNm: rowData["GROUP_NM"],
            // 経理処理日
            keiriDt: rowData["KEIRI_DATE"],
            // 担当者コード
            strTan: $("#SYAINNO").html(),
            // 入力拠点コード
            strKtn: $("#BUSYOCD").html(),
        };
        var url = me.sys_id + "/" + me.id + "/" + "csvOutput_Click";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "W0024") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                var link = document.createElement("a");
                link.style.display = "none";
                link.href = result["data"]["url"];
                link.setAttribute("download", "");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                $("#FrmHRAKUOutputDialogDiv").dialog("close");
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：選択データの設定
    //関 数 名：FncSetRtnData
    //引    数：無し
    //戻 り 値：True ：正常
    //       　False：異常
    //処理説明：選択したデータを構造体に設定する。
    //**********************************************************************
    me.FncSetRtnData = function () {
        var SelectRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (SelectRow == null) {
            me.clsComFnc.FncMsgBox("W9999", "選択されませんでした。");
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
    var o_R4_FrmHRAKUOutput = new R4.FrmHRAKUOutput();
    o_R4_FrmHRAKUOutput.load();
});
