/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("R4.FrmKamokuSearch");

R4.FrmKamokuSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();

    // ========== 変数 start ==========

    me.id = "FrmKamokuSearch";
    me.sys_id = "R4K";
    me.url = "";

    me.data = new Array();

    me.col = {
        KAMOKUCD: "",
        KAMOKUNM: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKamokuSearch.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKamokuSearch.cmdChoice",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKamokuSearch.cmdCancel",
        type: "button",
        handle: "",
    });

    me.colModel = [
        {
            name: "KAMOKUCD",
            label: "コード",
            index: "KAMOKUCD",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "KAMOKUNM",
            label: "名称",
            index: "KAMOKUNM",
            width: 280,
            align: "left",
            sortable: false,
        },
    ];

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    me.init_control = function () {
        base_init_control();
        me.SubFirstSet();
    };

    $(".FrmKamokuSearch.cmdSearch").click(function () {
        me.cmdSearch_Click();
    });

    $(".FrmKamokuSearch.cmdChoice").click(function () {
        me.FncSetRtnData();
    });
    $(".FrmKamokuSearch.cmdCancel").click(function () {
        $("#FrmKamokuSearchDialogDiv").dialog("close");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.SubFirstSet = function () {
        $(".FrmKamokuSearch.cmdChoice").button("disable");

        me.g_url = "R4K/FrmKamokuSearch/fncDataSet";
        me.grid_id = "#FrmKamokuSearch_sprMeisai";
        me.pager = "";
        me.sidx = "";

        me.option = {
            colModel: me.colModel,
            multiselect: false,
            rownumbers: true,
            multiselectWidth: 50,
            caption: "",
            loadui: "disable",
            // scroll : false,
            rowNum: 500000,
        };
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 490);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 311 : 390
        );
    };

    me.FncSetRtnData = function () {
        $("#RtnCD").html("1");
        me.SelectRow = $("#FrmKamokuSearch_sprMeisai").jqGrid(
            "getGridParam",
            "selrow"
        );
        me.KamokuSearchData = $(me.grid_id).jqGrid("getRowData", me.SelectRow);
        if ($.trim(me.KamokuSearchData["KAMOKUCD"]) != "") {
            // me.FrmHendoKobetu.GetFncSetRtnData(me.SelectRow);
            // me.FrmTeisyu.GetFncSetRtnData(me.SelectRow);
            $("#KAMOKUCD").html($.trim(me.KamokuSearchData["KAMOKUCD"]));
            $("#KAMOKUNM").html($.trim(me.KamokuSearchData["KAMOKUNM"]));
            // $("#KKRBusyoCD").html($.trim(me.KamokuSearchData[me.SelectRow]['KKRCD']));
        }
        $("#FrmKamokuSearchDialogDiv").dialog("close");
    };

    me.cmdSearch_Click = function () {
        $("#FrmKamokuSearch_sprMeisai").jqGrid("clearGridData");

        var txtBusyoNMVal = $(".FrmKamokuSearch.txtKamokuNM").val();
        var txtBusyoCDVal = $(".FrmKamokuSearch.txtKamokCD").val();

        var arr = {
            txtNM: txtBusyoNMVal,
            txtCD: txtBusyoCDVal,
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, arr, me.complete_fun);
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                me.FncSetRtnData();
            },
        });

        // スプレッド上でエンター押下時に修正処理
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                me.FncSetRtnData();
            },
        });
    };
    me.complete_fun = function (bErrorFlag) {
        if (bErrorFlag == "error") {
            // $(me.grid_id).closest('.ui-jqgrid').block();
            // $(".FrmMKamokuMnt.cmdAction").button("disable");
            // $(".FrmMKamokuMnt.cmdAdd").button("disable");
            // $(".FrmMKamokuMnt.cmdSearch").button("disable");
        } else if (bErrorFlag == "nodata") {
            clsComFnc.FncMsgBox("I0001");
            $(".FrmKamokuSearch.cmdChoice").button("disable");
        } else {
            $(".FrmKamokuSearch.cmdChoice").button("enable");
            $("#FrmKamokuSearch_sprMeisai").jqGrid("setSelection", 0, true);
            $(".FrmKamokuSearch.cmdChoice").trigger("focus");
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmKamokuSearch = new R4.FrmKamokuSearch();
    o_R4_FrmKamokuSearch.load();
});
