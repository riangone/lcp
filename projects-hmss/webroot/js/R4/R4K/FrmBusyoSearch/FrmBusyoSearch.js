/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("R4.FrmBusyoSearch");

R4.FrmBusyoSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmBusyoSearch";
    me.sys_id = "R4K";
    me.url = "";
    me.BusyoSearchData = "";
    me.data = new Array();
    me.FrmHendoKobetu = null;
    me.FrmTeisyu = null;
    me.col = {
        BusyoCD: "",
        BusyoNM: "",
        KKRCD: "",
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

    me.colModel = [
        {
            name: "BusyoCD",
            label: "コード",
            index: "BusyoCD",
            width: 113,
            align: "left",
            sortable: false,
        },
        {
            name: "BusyoNM",
            label: "名称",
            index: "BusyoNM",
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

    $("#FrmBusyoSearch_sprMeisai").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        height: me.ratio === 1.5 ? 311 : 389,
        colModel: me.colModel,
        rownumbers: true,
        rownumWidth: 60,
        //選択行の修正画面を呼び出す
        ondblClickRow: function () {
            me.FncSetRtnData();
        },
    });
    //スプレッド上でエンター押下時に修正処理
    $("#FrmBusyoSearch_sprMeisai").jqGrid("bindKeys", {
        onEnter: function () {
            me.FncSetRtnData();
        },
    });
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

    $(".FrmBusyoSearch.cmdSearch").click(function () {
        me.cmdSearch_Click();
    });

    $(".FrmBusyoSearch.cmdChoice").click(function () {
        me.FncSetRtnData();
    });
    $(".FrmBusyoSearch.cmdCancel").click(function () {
        $("#FrmBusyoSearchDialogDiv").dialog("close");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.SubFirstSet = function () {
        $(".FrmBusyoSearch.cmdChoice").button("disable");
        $(".FrmBusyoSearch.txtBusyoCD").trigger("focus");
        $("#FrmBusyoSearch_sprMeisai").jqGrid("clearGridData");
    };

    me.FncSetRtnData = function () {
        $("#RtnCD").html("1");
        me.SelectRow = $("#FrmBusyoSearch_sprMeisai").jqGrid(
            "getGridParam",
            "selrow"
        );
        if ($.trim(me.BusyoSearchData[me.SelectRow]["BUSYOCD"]) != "") {
            // me.FrmHendoKobetu.GetFncSetRtnData(me.SelectRow);
            // me.FrmTeisyu.GetFncSetRtnData(me.SelectRow);
            $("#BUSYOCD").html(
                $.trim(me.BusyoSearchData[me.SelectRow]["BUSYOCD"])
            );
            $("#BUSYONM").html(
                $.trim(me.BusyoSearchData[me.SelectRow]["BUSYONM"])
            );
            $("#KKRBusyoCD").html(
                $.trim(me.BusyoSearchData[me.SelectRow]["KKRCD"])
            );
        }
        $("#FrmBusyoSearchDialogDiv").dialog("close");
    };

    me.cmdSearch_Click = function () {
        $("#FrmBusyoSearch_sprMeisai").jqGrid("clearGridData");
        me.url = me.sys_id + "/" + me.id + "/fncDataSet";

        var txtBusyoNMVal = $(".FrmBusyoSearch.txtBusyoNM").val();
        var txtBusyoCDVal = $(".FrmBusyoSearch.txtBusyoCD").val();

        var arr = {
            txtBusyoNM: txtBusyoNMVal,
            txtBusyoCD: txtBusyoCDVal,
        };

        me.data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["row"] <= 0) {
                me.SubFirstSet();
                clsComFnc.FncMsgBox("I0001");
                return;
            } else {
                $(".FrmBusyoSearch.cmdChoice").button("enable");
            }

            // var MAX_ROW = 10000;
            // var lngPos = 1

            me.BusyoSearchData = result["data"];
            for (key in result["data"]) {
                me.col["BusyoCD"] = result["data"][key]["BUSYOCD"];
                me.col["BusyoNM"] = result["data"][key]["BUSYONM"];
                me.col["KKRCD"] = result["data"][key]["KKRCD"];
                $("#FrmBusyoSearch_sprMeisai").jqGrid(
                    "addRowData",
                    parseInt(key),
                    me.col
                );
            }
            $("#FrmBusyoSearch_sprMeisai").jqGrid("setSelection", 0, true);
            $(".FrmBusyoSearch.cmdChoice").trigger("focus");
        };

        ajax.send(me.url, me.data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmBusyoSearch = new R4.FrmBusyoSearch();
    o_R4_FrmBusyoSearch.load();
});
