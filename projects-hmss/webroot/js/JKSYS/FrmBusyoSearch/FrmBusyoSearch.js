/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmBusyoSearch");

JKSYS.FrmBusyoSearch = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();

    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.grid_id = "#JKSYS_FrmBusyoSearch_sprItyp";
    me.g_url = "JKSYS/FrmJKSYSBusyoSearch/fncDataSet";

    me.colModel = [
        {
            name: "BUSYOCD",
            label: "コード",
            index: "BUSYOCD",
            width: 113,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYONM",
            label: "名称",
            index: "BUSYONM",
            width: 300,
            align: "left",
            sortable: false,
        },
        {
            name: "KKRCD",
            label: "括り部署コード",
            index: "KKRCD",
            width: 1,
            align: "left",
            sortable: false,
            hidden: true,
        },
    ];
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 60,
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmBusyoSearch.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmBusyoSearch.cmdChoice",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmBusyoSearch.cmdCancel",
        type: "button",
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：検索ボタン押下時
    $(".FrmBusyoSearch.cmdSearch").click(function () {
        me.cmdSearch_Click();
    });
    //処理説明：選択ボタン押下時
    $(".FrmBusyoSearch.cmdChoice").click(function () {
        me.cmdChoice_Click();
    });
    //処理説明：戻るボタン押下時
    $(".FrmBusyoSearch.cmdCancel").click(function () {
        $("#FrmBusyoSearchDialogDiv").dialog("close");
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
        me.frmCM_Kamoku_Load();
    };
    //**********************************************************************
    //処 理 名：LOAD
    //関 数 名：frmCM_Kamoku_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：LOAD
    //**********************************************************************
    me.frmCM_Kamoku_Load = function () {
        //初期設定処理
        me.SubFirstSet();

        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 490);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 300 : 340
        );

        //KEYDOWN
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (_rowId, _iRow, _iCol, _e) {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#FrmBusyoSearchDialogDiv").dialog("close");
            },
        });
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function (_rowid) {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#FrmBusyoSearchDialogDiv").dialog("close");
            },
        });

        $("#RtnCD").html("-1");
    };
    //**********************************************************************
    //処 理 名：「選択」ボタン
    //関 数 名：cmdChoice_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：「選択」
    //**********************************************************************
    me.cmdChoice_Click = function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }

        //閉じる
        $("#FrmBusyoSearchDialogDiv").dialog("close");
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
        var rowData = $(me.grid_id).jqGrid("getRowData", SelectRow);
        if (rowData && $.trim(rowData["BUSYOCD"]) != "") {
            //選択値設定
            //リターン値
            $("#RtnCD").html("1");
            //---部署コード---
            $("#BUSYOCD").html($.trim(rowData["BUSYOCD"]));
            //---部署名---
            $("#BUSYONM").html($.trim(rowData["BUSYONM"]));
        } else {
            return false;
        }
        return true;
    };
    //**********************************************************************
    //処 理 名：「検索」ボタン
    //関 数 名：cmdSearch_Click
    //引    数：無し
    //戻 り 値： 無し
    //処理説明：「検索」ボタン
    //**********************************************************************
    me.cmdSearch_Click = function () {
        var txtBusyoNMVal = $(".FrmBusyoSearch.txtBusyoNM").val();
        var txtBusyoCDVal = $(".FrmBusyoSearch.txtBusyoCD").val();
        var data = {
            txtBusyoNM: txtBusyoNMVal,
            txtBusyoCD: txtBusyoCDVal,
        };
        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var objDR = $(me.grid_id).jqGrid("getRowData");
            if (objDR.length == 0) {
                me.SubFirstSet();
                //該当するデータは存在しません。
                me.clsComFnc.FncMsgBox("I0001");
            } else {
                //１行目選択
                $(me.grid_id).jqGrid("setSelection", 0, true);
                // フォーカスの設定
                $(me.grid_id).trigger("focus");
                //選択ボタンを活性
                $(".FrmBusyoSearch.cmdChoice").button("enable");
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };
    //**********************************************************************
    //処 理 名：初期設定処理
    //関 数 名：SubFirstSet
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定処理を行う。
    //**********************************************************************
    me.SubFirstSet = function () {
        //選択ボタンを不活性
        $(".FrmBusyoSearch.cmdChoice").button("disable");
        //表示行数の設定
        $(me.grid_id).jqGrid("clearGridData");
        //フォーカスの設定
        $(".FrmBusyoSearch.txtBusyoCD").trigger("focus");
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmBusyoSearch = new JKSYS.FrmBusyoSearch();
    o_JKSYS_FrmBusyoSearch.load();
});
