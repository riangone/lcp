/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("KRSS.FrmAuthCtl");

KRSS.FrmAuthCtl = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.sys_id = "KRSS";
    me.id = "FrmAuthCtl";
    me.grid_id = "#FrmAuthCtl_sprList";
    me.g_url = "KRSS/FrmAuthCtl/subSpreadReShow";
    me.pager = "#FrmAuthCtl_pager";
    me.sidx = "";

    me.BusyoArr = new Array();
    me.selRowData = new Array();

    me.lastsel = 0;
    me.strTougetu = "";
    me.arrInputData = new Array();

    // ========== 変数 end ==========
    me.option = {
        rowNum: 9999,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 30,
        scroll: 1,
    };
    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "社員番号",
            index: "SYAIN_NO",
            width: 100,
            sortable: false,
            align: "left",
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: me.ratio === 1.5 ? 400 : 450,
            sortable: false,
            align: "left",
        },
        {
            name: "STATE",
            label: "権限付与状態",
            index: "STATE",
            width: me.ratio === 1.5 ? 120 : 160,
            sortable: false,
            align: "left",
        },
        {
            name: "START_DATE",
            label: "配属開始日",
            index: "START_DATE",
            hidden: true,
        },
        {
            name: "END_DATE",
            label: "配属終了日",
            index: "END_DATE",
            hidden: true,
        },
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            hidden: true,
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            hidden: true,
        },
    ];
    // ========== コントロール start ==========
    me.controls.push({
        id: ".KRSS.FrmAuthCtl.cmdBS1",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmAuthCtl.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmAuthCtl.cmdUpdate",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmAuthCtl.cmdDelete",
        type: "button",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = イベント start =
    // ==========
    $("#FrmAuthCtlEdit").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        width: 960,
        height: 500,
        dialogClass: "RemoveCloseMark",
        open: function () {
            $(".RemoveCloseMark .ui-dialog-titlebar-close").hide();
        },
        close: function () {
            if (me.FrmAuthCtlEdit.bolResult == true) {
                me.cmdSearch();
            }
        },
    });

    //部署CD. blur
    $(".KRSS.FrmAuthCtl.txtBusyouCD").on("blur", function () {
        $(".KRSS.FrmAuthCtl.txtBusyouNM").val("");
        if ($(".KRSS.FrmAuthCtl.txtBusyouCD").val().trimEnd() != "") {
            //名称取得
            for (key in me.BusyoArr) {
                if (
                    $(".KRSS.FrmAuthCtl.txtBusyouCD").val().trimEnd() ==
                    me.BusyoArr[key]["BUSYO_CD"]
                ) {
                    $(".KRSS.FrmAuthCtl.txtBusyouNM").val(
                        me.BusyoArr[key]["BUSYO_NM"]
                    );
                    $(".KRSS.FrmAuthCtl.cmdSearch").trigger("focus");
                }
            }
        }
    });

    //**********************************************************************
    //検索(Left)ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmAuthCtl.cmdBS1").click(function () {
        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($("#KRSS_FrmAuthCtl"));
        $("<div></div>")
            .attr("id", "BUSYOCD")
            .insertAfter($("#KRSS_FrmAuthCtl"));
        $("<div></div>")
            .attr("id", "BUSYONM")
            .insertAfter($("#KRSS_FrmAuthCtl"));
        $("<div></div>").attr("id", "RtnCD").insertAfter($("#KRSS_FrmAuthCtl"));

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: 680,
            width: 550,
            resizable: false,
            open: function () {
                $("#RtnCD").hide();
                $("#BUSYONM").hide();
                $("#BUSYOCD").hide();
            },
            close: function () {
                var searchedBusyoCD = $("#BUSYOCD").html();
                var searchedBusyoNM = $("#BUSYONM").html();
                if (searchedBusyoCD != "") {
                    $(".KRSS.FrmAuthCtl.txtBusyouCD").val(searchedBusyoCD);
                }
                if (searchedBusyoNM != "") {
                    $(".KRSS.FrmAuthCtl.txtBusyouNM").val(searchedBusyoNM);
                }
                $("#RtnCD").remove();
                $("#BUSYONM").remove();
                $("#BUSYOCD").remove();
                $("#FrmBusyoSearchDialogDiv").remove();
            },
        });

        var frmId = "FrmBusyoSearch";
        var url = "R4K" + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);
            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
    });

    //**********************************************************************
    //検索(Right)ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmAuthCtl.cmdSearch").click(function () {
        me.cmdSearch();
    });

    //**********************************************************************
    //処理説明：登録ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmAuthCtl.cmdUpdate").click(function () {
        me.cmdUpdate();
    });

    //**********************************************************************
    //削除ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmAuthCtl.cmdDelete").click(function () {
        me.cmdDelete_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_load = me.load;
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.load = function () {
        base_load();
        me.fncGetBusyo();
        //me.frmAuthCtl_Load();
    };

    //**********************************************************************
    //処 理 名：getBusyo
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    me.fncGetBusyo = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetBusyo";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.BusyoArr = result["data"];
                me.frmAuthCtl_Load();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 0);
    };
    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmAuthCtl_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmAuthCtl_Load = function () {
        //画面項目ｸﾘｱ
        me.subClearForm();
        //ｽﾌﾟﾚｯﾄﾞの初期設定
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id,  me.ratio === 1.5 ? 700 : 810);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 225 : 260);
        $("#FrmAuthCtl_pager_center").html("");
        me.sprCostList_CellDoubleClick();
        me.sprCostList_KeyDown();
        $(".KRSS.FrmAuthCtl.txtSyainCDFrom").trigger("focus");
    };

    //**********************************************************************
    //処 理 名：選択行の修正画面を呼び出す
    //関 数 名：sprCostList_CellDoubleClick
    //引    数：無し
    //戻 り 値：無し
    //処理説明：DoubleClickのイベントを呼び出す
    //**********************************************************************
    me.sprCostList_CellDoubleClick = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                me.cmdUpdate();
            },
        });
    };
    //**********************************************************************
    //処 理 名：選択行の修正画面を呼び出す
    //関 数 名：sprCostList_KeyDown
    //引    数：無し
    //戻 り 値：無し
    //処理説明：Enter押下,修正画面を呼び出す
    //**********************************************************************
    me.sprCostList_KeyDown = function () {
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                me.cmdUpdate();
            },
        });
    };

    //**********************************************************************
    //処 理 名：画面項目をｸﾘｱする
    //関 数 名：subClearForm
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面項目をｸﾘｱする
    //**********************************************************************
    me.subClearForm = function () {
        $(".KRSS.FrmAuthCtl.txtSyainCDFrom").val("");
        $(".KRSS.FrmAuthCtl.txtSyainKana").val("");
        $(".KRSS.FrmAuthCtl.txtBusyouCD").val("");
        $(".KRSS.FrmAuthCtl.txtBusyouNM").val("");

        $(".KRSS.FrmAuthCtl.cmdUpdate").button("disable");
        $(".KRSS.FrmAuthCtl.cmdDelete").button("disable");
    };

    //**********************************************************************
    //処 理 名：検索
    //関 数 名：cmdSearch_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：検索ボタン押下時
    //**********************************************************************
    me.cmdSearch = function () {
        //スプレッドの表示を初期化
        $(me.grid_id).jqGrid("clearGridData");
        var data = {
            txtSyainCDFrom: $(".KRSS.FrmAuthCtl.txtSyainCDFrom")
                .val()
                .trimEnd(),
            txtSyainKana: $(".KRSS.FrmAuthCtl.txtSyainKana").val().trimEnd(),
            txtBusyouCD: $(".KRSS.FrmAuthCtl.txtBusyouCD").val().trimEnd(),
        };
        me.complete_fun = function (bErrorFlag) {
            //DB Error
            if (bErrorFlag == "error") {
                return;
            }
            //該当するデータは存在しません。
            if (bErrorFlag == "nodata") {
                //該当データなし
                //me.clsComFnc.FncMsgBox("I0001");
                //表示ボタンを使用不可に変更
                $(".KRSS.FrmAuthCtl.cmdUpdate").button("disable");
                $(".KRSS.FrmAuthCtl.cmdDelete").button("disable");
                return;
            } else {
                //１行目を選択状態にする
                //$(me.grid_id).jqGrid('setSelection', 0);
                //表示ボタンを使用可に変更
                $(".KRSS.FrmAuthCtl.cmdUpdate").button("enable");
                $(".KRSS.FrmAuthCtl.cmdDelete").button("enable");
                $(me.grid_id).trigger("focus");
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
        gdmz.common.jqgrid.set_grid_width(me.grid_id,  me.ratio === 1.5 ? 700 : 820);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 225 : 260);
    };

    //**********************************************************************
    //処 理 名：登録
    //関 数 名：cmdUpdate
    //引    数：無し
    //戻 り 値：無し
    //処理説明：登録ボタン押下時/ダブルクリック
    //**********************************************************************
    me.cmdUpdate = function () {
        //登録対象行が選択されていない場合はエラー
        var selRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (selRow == null) {
            me.clsComFnc.FncMsgBox("W9999", "登録対象行が選択されていません");
            return;
        }
        //テーブルの選択の行の値
        me.selRowData = $(me.grid_id).jqGrid("getRowData", selRow);
        var url = me.sys_id + "/" + "FrmAuthCtlEdit" + "/" + "index";
        me.ajax.receive = function (result) {
            $("#FrmAuthCtlEdit").empty();
            $("#FrmAuthCtlEdit").html(result);
            $("#FrmAuthCtlEdit").dialog(
                "option",
                "title",
                "社員権限管理マスタ"
            );
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：削除
    //関 数 名：cmdDelete_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：削除ボタン押下時
    //**********************************************************************
    me.cmdDelete_Click = function () {
        //削除対象行が選択されていない場合はエラー
        var selRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (selRow == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象行が選択されていません");
            return;
        }
        //削除確認ﾒｯｾｰｼﾞを表示する
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
        me.clsComFnc.FncMsgBox("QY004");
    };

    //**********************************************************************
    //処 理 名：削除処理
    //関 数 名：delRowData
    //引    数：無し
    //戻 り 値：無し
    //処理説明：削除ボタン押下時
    //**********************************************************************
    me.delRowData = function () {
        //テーブルの選択の行を第一列の値
        var selRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowdata = $(me.grid_id).jqGrid("getRowData", selRow);
        var SYAINNO = rowdata["SYAIN_NO"].trimEnd();

        var url = me.sys_id + "/" + me.id + "/" + "cmdDelete_Click";
        var data = SYAINNO;
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.cmdSearch();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_KRSS_FrmAuthCtl = new KRSS.FrmAuthCtl();
    o_KRSS_FrmAuthCtl.load();
    o_KRSS_KRSS_FrmAuthCtl = o_KRSS_FrmAuthCtl;
});
