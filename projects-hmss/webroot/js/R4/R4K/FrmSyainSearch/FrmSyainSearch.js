/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */
/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmSyainSearch");

R4.FrmSyainSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.MessageBox = new gdmz.common.MessageBox();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmSyainSearch";
    me.sys_id = "R4K";
    me.url = "";
    me.SyainSearchData = "";
    me.data = new Array();
    me.FrmHendoKobetu = null;
    me.FrmTeisyu = null;
    me.col = {
        BUSYOCD: "",
        BUSYONM: "",
        SYAINNO: "",
        SYAINNM: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyainSearch.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyainSearch.cmdChoice",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyainSearch.cmdCancel",
        type: "button",
        handle: "",
    });
    //jqGriDの設定する
    me.colModel = [
        {
            name: "BUSYOCD",
            label: "部署コード",
            index: "BUSYOCD",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYONM",
            label: "部署名",
            index: "BUSYONM",
            width: 200,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAINNO",
            label: "社員番号",
            index: "SYAINNO",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAINNM",
            label: "名称",
            index: "SYAINNM",
            width: 200,
            align: "left",
            sortable: false,
        },
    ];

    $("#FrmSyainSearch_sprMeisai").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        //20240816 caina upd s dialogは縦スクロールバーを表示しました
        // height: 310,
        height: me.ratio === 1.5 ? 270 : 309,
        //20240816 caina upd e
        rowNum: 999999,
        colModel: me.colModel,
        rownumbers: true,
        rownumWidth: 40,
        //選択行の修正画面を呼び出す
        ondblClickRow: function () {
            me.FncSetRtnData();
        },
    });
    //スプレッド上でエンター押下時に修正処理
    $("#FrmSyainSearch_sprMeisai").jqGrid("bindKeys", {
        onEnter: function () {
            me.FncSetRtnData();
        },
    });
    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

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

    //**********************************************************************
    //処理説明：検索ボタン押下時
    //**********************************************************************

    $(".FrmSyainSearch.cmdSearch").click(function () {
        me.cmdSearch_Click();
    });
    //**********************************************************************
    //処理説明：選択ボタン押下時
    //**********************************************************************
    $(".FrmSyainSearch.cmdChoice").click(function () {
        me.FncSetRtnData();
    });
    //**********************************************************************
    //処理説明：戻るボタン押下時
    //**********************************************************************
    $(".FrmSyainSearch.cmdCancel").click(function () {
        $("#FrmSyainSearchDialogDiv").dialog("close");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //**********************************************************************
    //処 理 名：画面初始化
    //関 数 名：SubFirstSet
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面初始化
    //**********************************************************************
    me.SubFirstSet = function () {
        $(".FrmSyainSearch.cmdChoice").button("disable");
        if ($("#BUSYOCD").html() != "") {
            $(".FrmSyainSearch.txtBusyoCD").val($("#BUSYOCD").html());
            $(".FrmSyainSearch.txtSyainCD").trigger("focus");
        }
        if ($("#SYAINNO").html() != "") {
            $(".FrmSyainSearch.txtSyainCD").val($("#SYAINNO").html());
            $(".FrmSyainSearch.txtSyainCD").trigger("focus");
        }

        $("#FrmSyainSearch_sprMeisai").jqGrid("clearGridData");
    };

    //**********************************************************************
    //処 理 名：数据返回
    //関 数 名：FncSetRtnData
    //引    数：無し
    //戻 り 値：無し
    //処理説明：数据返回
    //**********************************************************************
    me.FncSetRtnData = function () {
        $("#RtnCD").html("1");
        var selectRow = $("#FrmSyainSearch_sprMeisai").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#FrmSyainSearch_sprMeisai").jqGrid(
            "getRowData",
            selectRow
        );

        $("#SYAINNO").html($.trim(rowData["SYAINNO"]));
        $("#BUSYOCD").html($.trim(rowData["BUSYOCD"]));
        $("#SYAINNM").html($.trim(rowData["SYAINNM"]));
        $("#BUSYONM").html($.trim(rowData["BUSYONM"]));

        $("#FrmSyainSearchDialogDiv").dialog("close");
    };

    //**********************************************************************
    //処 理 名：数据检索
    //関 数 名：cmdSearch_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：数据检索
    //**********************************************************************
    me.cmdSearch_Click = function () {
        $("#FrmSyainSearch_sprMeisai").jqGrid("clearGridData");
        me.url = me.sys_id + "/" + me.id + "/fncDataSet";

        var txtBusyoCDVal = $(".FrmSyainSearch.txtBusyoCD").val();
        var txtSyainCDVal = $(".FrmSyainSearch.txtSyainCD").val();
        var txtSyainKNVal = $(".FrmSyainSearch.txtSyainKN").val();

        var arr = {
            txtBusyoCD: txtBusyoCDVal,
            txtSyainCD: txtSyainCDVal,
            txtSyainKN: txtSyainKNVal,
        };

        me.data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["row"] <= 0) {
                if ($("#BUSYOCD").html() != "") {
                    $(".FrmSyainSearch.txtSyainCD").trigger("focus");
                } else {
                    $(".FrmSyainSearch.txtBusyoCD").trigger("focus");
                }
                me.clsComFnc.FncMsgBox("I0001");
                $(".FrmSyainSearch.cmdChoice").button("disable");
                return false;
            } else {
                $(".FrmSyainSearch.cmdChoice").button("enable");
            }

            me.SyainSearchData = result["data"];
            $("#FrmSyainSearch_sprMeisai")
                .jqGrid("setGridParam", {
                    datatype: "local",
                    // jqgridにデータがなし場合、文字表示しない
                    emptyRecordRow: false,
                    data: me.SyainSearchData,
                })
                .trigger("reloadGrid");

            $("#FrmSyainSearch_sprMeisai").jqGrid("setSelection", 1, true);
            $(".FrmSyainSearch.cmdChoice").trigger("focus");
        };

        me.ajax.send(me.url, me.data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyainSearch = new R4.FrmSyainSearch();
    o_R4_FrmSyainSearch.load();
});
