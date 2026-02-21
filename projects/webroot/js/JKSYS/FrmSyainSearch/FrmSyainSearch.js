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
Namespace.register("JKSYS.FrmSyainSearch");

JKSYS.FrmSyainSearch = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.grid_id = "#JKSYS_FrmSyainSearch_sprItyp";
    me.g_url = "JKSYS/FrmJKSYSSyainSearch/fncDataSet";

    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
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
    $(".FrmSyainSearch.cmdSearch").click(function () {
        me.cmdSearch_Click();
    });

    //処理説明：選択ボタン押下時
    $(".FrmSyainSearch.cmdChoice").click(function () {
        me.cmdChoice_Click();
    });

    //処理説明：戻るボタン押下時
    $(".FrmSyainSearch.cmdCancel").click(function () {
        //閉じる
        $("#FrmSyainSearchDialogDiv").dialog("close");
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
        // 初期設定処理
        me.SubFirstSet();

        if ($("#BUSYOCD").length > 0) {
            var strBusyoCD = $("#BUSYOCD").val();
            if (strBusyoCD) {
                $(".FrmSyainSearch.txtBusyoCD").val(strBusyoCD);
                $(".FrmSyainSearch.txtSyainCD").trigger("focus");
                $(".FrmSyainSearch.txtBusyoCD").attr("disabled", "true");
            }
        }

        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 700);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 300 : 340
        );

        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (_rowId, _iRow, _iCol, _e) {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#FrmSyainSearchDialogDiv").dialog("close");
            },
        });
        //スプレッド上でエンター押下時に修正処理
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function (_rowid) {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#FrmSyainSearchDialogDiv").dialog("close");
            },
        });

        //リターン値初期化
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
        $("#FrmSyainSearchDialogDiv").dialog("close");
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
        $(".FrmSyainSearch.cmdChoice").button("disable");
        //表示行数の設定
        $(me.grid_id).jqGrid("clearGridData");
        //フォーカスの設定
        $(".FrmSyainSearch.txtBusyoCD").trigger("focus");
    };

    //**********************************************************************
    //処 理 名：選択データの設定
    //関 数 名：FncSetRtnData
    //引    数：無し
    //戻 り 値：True ：正常
    //        　False：異常
    //処理説明：選択したデータを構造体に設定する。
    //**********************************************************************
    me.FncSetRtnData = function () {
        var lngRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", lngRow);

        //選択値設定
        if ($.trim(rowData["BUSYOCD"]) != "") {
            //リターン値
            $("#RtnCD").html("1");
            //部署コード
            $("#BUSYOCD").html($.trim(rowData["BUSYOCD"]));
            //部署名
            $("#BUSYONM").html($.trim(rowData["BUSYONM"]));
            //括り部署コード
            $("#SYAINNO").html($.trim(rowData["SYAINNO"]));
            $("#SYAINNM").html($.trim(rowData["SYAINNM"]));
        } else {
            return false;
        }
        return true;
    };

    //**********************************************************************
    //処 理 名：「検索」ボタン
    //関 数 名：cmdSearch_Click
    //引    数：無し
    //戻 り 値：
    //処理説明：「検索」ボタン
    //**********************************************************************
    me.cmdSearch_Click = function () {
        var txtBusyoCD = $(".FrmSyainSearch.txtBusyoCD").val();
        var txtSyainCD = $(".FrmSyainSearch.txtSyainCD").val();
        var txtSyainKN = $(".FrmSyainSearch.txtSyainKN").val();
        var strKijyunbi = $("#KUJYUNBI").val();
        var data = {
            txtBusyoCD: txtBusyoCD,
            txtSyainCD: txtSyainCD,
            txtSyainKN: txtSyainKN,
            Kijyunbi: strKijyunbi,
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
                $(".FrmSyainSearch.cmdChoice").button("enable");
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmSyainSearch = new JKSYS.FrmSyainSearch();
    o_JKSYS_FrmSyainSearch.load();
});
